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





    }

}
