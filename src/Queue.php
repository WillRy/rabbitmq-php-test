<?php

namespace App;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

abstract class Queue
{

    protected $connection;
    protected $channel;
    protected $queue = '';

    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Inicia a conexão
     */
    public function openConnection()
    {
        $this->channel = $this->connection->channel();
    }

    /**
     * Declara qual a fila que será usada
     */
    public function queue(
        $passive = false,
        $durable = false,
        $exclusive = false,
        $auto_delete = true,
        $nowait = false,
        $arguments = array(),
        $ticket = null
    ) {
        $this->channel->queue_declare($this->queue, $passive, $durable, $exclusive, $auto_delete);
    }

    /**
     * Encerra conexão
     */
    public function closeConnection()
    {
        $this->channel->close();
        $this->connection->close();
    }

    public function setQueue(string $queue)
    {
        $this->queue = $queue;
    }
}
