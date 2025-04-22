<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\Models\User;

class TestMailController
{
    public function send()
    {
        $data = ['name' => 'Valerii'];
        Mail::to('regaska0384@mail.ru')->send(new TestMail($data));
        echo "письмо успешно отправлено";
    }
    public function receive()
    {

        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'valera', 'qwerty');
        $channel = $connection->channel();

        $channel->queue_declare('hello', false, false, false, false);

        $callback = function ($msg) {
            // в переменной $msg будет передаваться как юзерконтроллере айдиншник пользователя
            $user = User::query()->find($msg->body);
            $email = $user->email;
            $data = ['name' => $user->name];
            Mail::to("$email")->send(new TestMail($data));
        };

        $channel->basic_consume('hello', '', false, true, false, false, $callback);

        try {
            $channel->consume();
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
            $channel->close();
            $connection->close();
            exit(1);
        }




    }

}
