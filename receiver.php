<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\Receiver;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$receiver = new Receiver($connection);
$receiver->openConnection();
$receiver->setQueue('Mail-Alert');
$receiver->queue(false, false, false, false);



/**
 * Função que vai receber e tratar efetivamente a mensagem
 */
$callback = function ($msg) {
    print_r($msg->body);
    print_r(PHP_EOL);
};


echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$receiver->consume($callback);
$receiver->run();
$receiver->closeConnection();
