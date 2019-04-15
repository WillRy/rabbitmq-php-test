<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\Receiver;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$receiver = new Receiver($connection);
$receiver->openConnection();
$receiver->setQueue('Mail-Alert');
$receiver->queue(false, true, false, false);



/**
 * Função que vai receber e tratar efetivamente a mensagem
 */
$callback = function ($msg){
    try {
        print_r($msg->body);
        print_r(PHP_EOL);
        $number = rand(0,1);
        
        if($number==1){
            throw new Exception("Error Processing Request", 1);
        }

        // confirma a mensagem
        $msg->delivery_info['channel']->
            basic_ack($msg->delivery_info['delivery_tag']);
       
    } catch (\Exception $th) {
        // recoloca tarefa na fila novamente
        $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag'],false,true);
       
    }
};

 /**
  * 
  *
  * @param callback funcao a ser executada 
  *
  * @param no_ack determina se marcara a mensagem como entregue automaticamente
  */
$receiver->consume($callback,false);
$receiver->run();
$receiver->closeConnection();
