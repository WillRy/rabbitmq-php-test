<?php

namespace App;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Sender extends Queue
{

    public function __construct(AMQPStreamConnection $connection)
    {
        parent::__construct($connection);
    }

    /**
     * Cria a nova mensagem
     */
    public function setMessage(string $payload)
    {
        $msg = new AMQPMessage($payload);
        $this->channel->basic_publish($msg, '', $this->queue);
    }
}
