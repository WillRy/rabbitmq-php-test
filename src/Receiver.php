<?php

namespace App;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Receiver extends Queue
{

   
    public function __construct(AMQPStreamConnection $connection)
    {
        parent::__construct($connection);
    }


    public function consume($callback)
    {
        $this->channel->basic_consume($this->queue, '', false, true, false, false, $callback);
    }

    public function run()
    {
        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }
}
