<?php
class Test extends CI_Controller{
	public function __construct()
	{
		parent::__construct();
	}
	
	private function redis(){
		//120.27.132.97:16379
		//30.iwide.cn
		$redis = new Redis();
		$redis->connect('10.168.162.35', 6379,3);//允许最大3秒的连接超时时间
		$redis->select(2);
		return $redis;
	}
	
	public function tshow(){
		/* $name = $this->redis()->get("arrdata");
		if(empty($name)){
			$data = array("aaa","bbb","ccc");
			$str = serialize($data);
			
			$this->redis()->set("arrdata",$str,30);
			$name = $this->redis()->get("arrdata");
			echo "数据取自第一次设置后的 redis  --->";
		}else{
			echo "数据直接来源于 redis --->";
		}
		$arr = unserialize($name);
		foreach($arr as $key=>$val){
			echo $key."____".$val."<br/>";
		} */
	}
	
	function my_serialize( $obj )
	{
		return base64_encode(gzcompress(serialize($obj)));
	}
	
	//反序列化
	function my_unserialize($txt)
	{
		return unserialize(gzuncompress(base64_decode($txt)));
	}
	
	
	public function show(){
		/* $name = $this->redis()->get("name");
		if(empty($name)){
			$obj = new person();
			$obj->age = 23;
			$obj->name = "张三";
			
			echo $obj->age."____".$obj->name."<br/>";
			
			$this->redis()->set("name",$obj,10);
			$name = $this->redis()->get("name");
			echo "数据取自第一次设置后的 redis  --->";
		}else{
			echo "数据直接来源于 redis --->";
		}
		print_r($name);
		
		echo "<br/>";
		$person = new person();
		$person = $name;
		echo $person->age."___".$person->name; */
	}
	
	public function test2(){
		/* $inter_id	= "a449664652";
		
		$this->load->model('distribute/Stafftest_model' );
		$result = $this->Stafftest_model->i_get_fans_ranking($inter_id,'YEAR',50);
		
		echo "<br/>".sizeof($result)."<br/>";
		var_dump($result);
		
		echo "<br/>";
		
		foreach($result as $key=>$val){
			foreach($val as $k=>$v){
				echo $k.":".$v.",";
			}
			echo "<br/>";
		} */
		
	}
	
	public function test5(){
		//a421641095_get_fans_ranking_MONTH
		$this->load->model ('distribute/Staff_model' );
		$data = $this->Staff_model->i_get_fans_ranking("a421641095","MONTH",50);
		var_dump($data);
		
		echo "<br/><hr><br/>";
		$str = $this->redis()->get("a421641095_get_fans_ranking_MONTH");
		$data2 = $this->my_unserialize($str);
		
		var_dump($data2);
		
	}
	
	public function test(){
		//oGClOuIZxl4GdQ4QNJTHK5_E3Cgg
		/* $openid 	= "oGClOuIZxl4GdQ4QNJTHK5_E3Cgg";
		$inter_id	= "a449664652";
		
		$this->load->model ('distribute/Stafftest_model' );
		$result = $this->Stafftest_model->get_room_rec_info($openid,$inter_id);
		$data = $result->result_array();
		foreach($data as $key=>$val){
			echo $val['id']."<br/>";
		}
		
		echo "<br/>---------<br/>";
		echo "数据大小：".sizeof($data); */
		
	}
	
	public function test_del(){
		$key = $_REQUEST['key'];
		$key = urldecode($key);
		if(!empty($key) && $key != "*"){
			$result = $this->redis()->del($key);
			echo $result;
		}else{
			echo "....";
		}
		
	}
	
	public function test4(){
		$key = $_REQUEST['key'];
		$key = urldecode($key);
		
		$str = $this->redis()->get($key);
		$data = $this->my_unserialize($str);
		
		var_dump($data);
	}
	
	public function testlog(){
		$key = $_REQUEST['key'];
		$key = urldecode($key);
		
		$incr_key = $key."_1";
		$log_key = $key."_log";
		
		$data = $this->redis()->get($incr_key);
		var_dump($data);
		
		echo "<hr/>";
		
		$data2 = $this->redis()->lrange($log_key,0,-1);
		var_dump($data2);
	}
	
	public function testlog2(){
		$key = $_REQUEST['key'];
	
		$data2 = $this->redis()->lrange($key,0,-1);
		echo "begin-----<br/>";
		var_dump($data2);
		echo "end-------<br/>";
	}
	
	public function testlog3(){
		$key = $_REQUEST['key'];
	
		$data2 = $this->redis()->get($key);
		echo "begin-----<br/>";
		var_dump($data2);
		echo "end-------<br/>";
	}
	
	
	public function test3(){
		$keys = $this->redis()->keys("*");
		var_dump($keys);
	}
	
	public function logs(){
		$key = $_REQUEST['key'];
		$this->load->model ('distribute/Staff_model' );
		$data = $this->Staff_model->get_redis_log($key);
		
		var_dump($data);
	}
	
	public function loog(){
		
		$this->load->model ('distribute/Staff_model' );
		$data = $this->Staff_model->getcf();
		
		var_dump($data);
		
		echo "<br/>";
		echo $data['test_redis_host'];
	}
	
	
	public function testbykey(){
		//a450682197_get_user_ranking_ALL
		$this->load->model ('distribute/Staff_model' );
		$data = $this->Staff_model->i_get_user_ranking("a450682197","ALL",50);
		var_dump($data);
		
	}
	
	public function test6(){
		$key = $_REQUEST['key'];
		
		$this->load->model ('distribute/Staff_model' );
		$result = $this->Staff_model->get_redis_log($key);
		
		var_dump($result);
	}
	
}

class person{
	var $name;
	var $age;
}