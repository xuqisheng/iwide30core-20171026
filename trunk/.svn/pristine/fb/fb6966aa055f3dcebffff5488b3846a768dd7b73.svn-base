<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Amqp消息队列
 */
class Amqp extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function server()
	{
		//设置你的连接
		$conn_args = array(
		    'host' => '30.iwide.cn',
		    'port' => '5672',
		    'login' => 'root',
		    'password' => '123456'
		);
		/** @see http://php.net/manual/pl/class.amqpconnection.php  */
		$conn = new AMQPConnection($conn_args);
		
		if ($conn->connect()) {
			echo "Established a connection to the broker \n";
		} else {
			echo "Cannot connect to the broker \n ";
		}
		
		//你的消息
		$message = json_encode(array('Hello World3!', 'php3', 'c++3:'));
		
		//创建channel
		/** @see http://php.net/manual/pl/class.amqpchannel.php  */
		$channel = new AMQPChannel($conn);
		
		//创建exchange
		/** @see http://php.net/manual/pl/class.amqpexchange.php  */
		$ex = new AMQPExchange($channel);
		$ex->setName('SOMA');             //创建名字
		$ex->setType(AMQP_EX_TYPE_DIRECT);    //交换器类型
		//$ex->setFlags(AMQP_DURABLE);          //持久化
		//$ex->setFlags(AMQP_AUTODELETE);
		echo "exchange status:". $ex->declareExchange();  //创建交换机结果
		echo "\n";

		$routingkey='key';
		for($i=0;$i<100;$i++){
			if($routingkey=='key2'){
				$routingkey='key';
			} else {
				$routingkey='key2';
			}
			$ex->publish($message, $routingkey);
		}
		/*
		$ex->publish($message,$routingkey);
		创建队列
		$q = new AMQPQueue($channel);
		设置队列名字 如果不存在则添加
		$q->setName('queue');
		$q->setFlags(AMQP_DURABLE | AMQP_AUTODELETE);
		echo "queue status: ".$q->declare();
		echo "\n";
		echo 'queue bind: '.$q->bind('exchange','route.key');
		将你的队列绑定到routingKey
		echo "\n";

		$channel->startTransaction();
		echo "send: ".$ex->publish($message, 'route.key'); //将你的消息通过制定routingKey发送
		$channel->commitTransaction();
		$conn->disconnect();
		*/
	}
	
	public function client()
	{
		//连接RabbitMQ
		$conn_args = array(
		    'host'=>'30.iwide.cn',
		    'port'=> '5672',
		    'login' => 'root',
		    'password' => '123456',
		    'vhost' =>'/'
		);
		
		/** @see http://php.net/manual/pl/class.amqpconnection.php  */
		$conn = new AMQPConnection($conn_args);
		$conn->connect();
		
		//设置queue名称，使用exchange，绑定routingkey
		/** @see http://php.net/manual/pl/class.amqpchannel.php  */
		$channel = new AMQPChannel($conn);

		$bindingkey='key2';
		
		/** @see http://php.net/manual/pl/class.amqpqueue.php  */
		$q = new AMQPQueue($channel);
		$q->setName('SOMA');
		//$q->setFlags(AMQP_DURABLE);
		$q->declareQueue(); 
		$q->bind('SOMA', $bindingkey);
		
		//消息获取
		$messages = $q->get(AMQP_AUTOACK) ;
		if ($messages){
			var_dump(json_decode($messages->getBody(), true ));
		}
		//$conn->disconnect();
	}
	
}
