<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

use App\Mail\TestMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;


class RabbitmqService
{
    private AMQPStreamConnection $connection;

    public function __construct()
    {
//        $this->connection = new AMQPStreamConnection('rabbitmq', 5672, 'valera', 'qwerty');
    }
    public function produce(array $data, string $queueName)
    {
        $channel = $this->connection->channel();
        // метод queue_declare  это метод который создает очередь
        $channel->queue_declare($queueName, false, false, false, false);
        $data = json_encode($data); // json преобразует наш массив в строки: "id":1, "name": "egor"
        $msg = new AMQPMessage($data);
        $channel->basic_publish($msg, '', $queueName);

        $channel->close();
    }
    public function consume(string $queueName,callable $callback)
    {
        $channel = $this->connection->channel();

        $channel->queue_declare($queueName, false, false, false, false);

        $channel->basic_consume($queueName, '', false, true, false, false, $callback);

        try {
            $channel->consume();
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
            $channel->close();
            exit(1);
        }
    }
}
