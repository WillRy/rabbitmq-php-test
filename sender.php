<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\Sender;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');


$payload = json_encode([
    'class' => "App\\Mail",
    'body' => [
        "title" => "New Open Jobs"
    ]
]);

try {
    $sender = new Sender($connection);

    $sender->openConnection();
    $sender->setQueue('Mail-Alert');
    $sender->queue(false, false, false, false);
    $sender->setMessage($payload);
    $sender->closeConnection();
} catch (\Exception $e) {
    print_r($e->getMessage());
}
