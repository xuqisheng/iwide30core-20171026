<?php

class Auto_run extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		if(isset($_GET['debug'])){
			$this->output->enable_profiler(true);
		}
	}
	
	public function index(){
		echo 'arrival';
	}
	
	/**
	 * 自动发放绩效
	 */
// 	public function auto_deliver(){
// 		$cookie_jar = dirname(__FILE__)."/auto_deliver.cookie";
// 		$ch = curl_init();
// 		curl_setopt($ch, CURLOPT_URL, site_url('distribute/auto_run/do_deliver'));
// 		curl_setopt($ch, CURLOPT_HEADER, false);
// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
// 		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
// 		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
// 		$result=curl_exec($ch);
// 		echo $result;
// 		curl_close($ch);
// 	}
	
	/**
	 * 生产-自动发放绩效 
	 */
	public function do_deliver_product(){
		
		$cookie_jar = dirname(__FILE__)."/deliver_product.cookie";
		$ch = curl_init();
		if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
			curl_setopt($ch, CURLOPT_URL, 'http://cron.iwide.cn/index.php/distribute/auto_run/deliver_n');
		}else{
			curl_setopt($ch, CURLOPT_URL, 'http://credit.iwide.cn/index.php/distribute/auto_run/deliver_n');
		}
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
		$result=curl_exec($ch);
		echo $result;
		curl_close($ch);
		
	}
	public function do_deliver_fas(){
		
		$cookie_jar = dirname(__FILE__)."/deliver_product.cookie";
		$ch = curl_init();
		if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
			curl_setopt($ch, CURLOPT_URL, 'http://cron.iwide.cn/index.php/distribute/auto_run/deliver_f');
		}else{
			curl_setopt($ch, CURLOPT_URL, 'http://credit.iwide.cn/index.php/distribute/auto_run/deliver_f');
		}
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
		$result=curl_exec($ch);
		echo $result;
		curl_close($ch);
		
	}
	
	private function gen_order_no(){
		$date_code= array(
				'0','1','2','3','4','5','6','7','8','9',
				'A','C','D','E','F','G','H','J','K',
				'M','N','P','Q','R','T','U','V','W','X','Y','Z','S');
		//eg: C 15 X 94737 74906 00
		return strtoupper( dechex(date('m'))). date('y'). $date_code[intval(date('d'))]
		. substr(time(),-5). substr(microtime(),2,5) .sprintf('%02d',rand(0,99));
	}

	public function auto_grade(){
		set_time_limit ( 55 );
		$this->load->model('distribute/grades_model');
		$query = $this->grades_model->deal_queue();
		// foreach ($query as $item){
		// 	$this->grades_model->_create_grade($item->rec_content,$item->id);

		// }
		echo 'success';exit;
	}
	
	public function deliver_n(){
		
		log_message('error', date('Y-m-d H:i:s').' : '.microtime(TRUE).' 开始查询分销绩效信息...');
		echo date('Y-m-d H:i:s').' : '.microtime(TRUE).' 开始查询分销绩效信息...<br/>';
		if($this->get_redis_key_status() == 'false'){
			return false;
		}
		//加redis锁 by situguanchen 2017-02-24
		$redis = $this->get_redis_instance();
		$lock_key = "DELIVER_N_LOCK_KEY";//分销发放
		$lock = $redis->setnx($lock_key, 'lock');
		if(!$lock) {
	    	log_message('error', date('Y-m-d H:i:s').' : '.microtime(TRUE).'绩效发放锁住状态');
	    	die('FAILURE!');
	    }
	    
		set_time_limit ( 55 );
		$this->load->model('distribute/grades_model');
		$this->load->model('distribute/distribute_model');
		$current_date = $this->get_redis_key_status('CURRENT_DATE');
		if(!$current_date || ($current_date && $current_date == date('Ymd',strtotime('-1 day')))){
			$this->rd_set('CURRENT_DATE',date('Ymd'));
			$this->distribute_model->reset_fails_grades();
		}
		//取所有待发放的分销员
		$salers = $this->distribute_model->get_auto_deliver_salers();

		echo '待发放人数'.count($salers).'<br/>';
		
		$batch_no = $this->gen_order_no();
		$saler_arr = $auto_deliver_salers = array();
		if($this->get_redis_key_status('_AUTO_DELIVER_SALERS')){
			$auto_deliver_salers = json_decode($this->get_redis_key_status('_AUTO_DELIVER_SALERS'),true);
			if($auto_deliver_salers['date'] == date('Ymd')){
				$saler_arr = $auto_deliver_salers['salers'];
			}else{
				$auto_deliver_salers['salers'] = $saler_arr;
				$auto_deliver_salers['date']   = date('Ymd');
				$this->rd_set('_AUTO_DELIVER_SALERS',json_encode($auto_deliver_salers));
			}
		}else{
			$auto_deliver_salers['salers'] = $saler_arr;
			$auto_deliver_salers['date']   = date('Ymd');
			$this->rd_set('_AUTO_DELIVER_SALERS',json_encode($auto_deliver_salers));
		}
		foreach ($salers as $saler){
			//添加余额不足公众号判断 situguanchen 2017-03-20
			/*$notenough_data = $inter_id_arr = array();
			if($this->get_redis_key_status('_NOTENOUGH_INTER_ID')){
				$notenough_data = json_decode($this->get_redis_key_status('_NOTENOUGH_INTER_ID'),true);
				if($notenough_data['date'] == date('Ymd')){
					$inter_id_arr = $notenough_data['inter_id_arr'];
				}
			}
			if(in_array($saler->inter_id,$inter_id_arr)){//如果是余额不足，后续的今天不发了
				continue;
			}*///放到distribute_model 里面
			if(!in_array($saler->inter_id.$saler->saler,$saler_arr)){
				$saler_arr[] = $saler->inter_id.$saler->saler;
				
				$auto_deliver_salers['salers'] = $saler_arr;
				$this->rd_set('_AUTO_DELIVER_SALERS',json_encode($auto_deliver_salers));
				
				$auto_batch_no = $this->get_redis_key_status($saler->inter_id.'AUTO');
				if($auto_batch_no){
					$auto_config = json_decode($auto_batch_no,TRUE);
					if($auto_config['date'] == date('Y-m-d')){
						$batch_no = $auto_config['batch_no'];
					}else{
						$this->rd_set($saler->inter_id.'AUTO',json_encode(array('batch_no'=>$batch_no,'date'=>date('Y-m-d'))));
					}
				}else{
					$this->rd_set($saler->inter_id.'AUTO',json_encode(array('batch_no'=>$batch_no,'date'=>date('Y-m-d'))));
				}
				$this->distribute_model->send_grades_by_saler_yestoday($saler->inter_id,$saler->saler,$batch_no);
			}
		}
		log_message('error', date('Y-m-d H:i:s').' : '.microtime(TRUE).' 分销绩效发放结束...');
		
		echo date('Y-m-d H:i:s').' : '.microtime(TRUE).' 分销绩效发放结束...';
		
		//更新最后发放时间
		$this->distribute_model->update_last_deliver_time();
		$redis->delete($lock_key);//解锁
		echo 'success';
		
	}
	public function deliver_f(){
		
		log_message('error', date('Y-m-d H:i:s').' : '.microtime(TRUE).' 开始查询分销绩效信息(粉丝)...');
		echo date('Y-m-d H:i:s').' : '.microtime(TRUE).' 开始查询分销绩效信息(粉丝)...<br/>';
		if($this->get_redis_key_status() == 'false'){
			return false;
		}
		//加redis锁 by situguanchen 2017-02-24
		$redis = $this->get_redis_instance();
		$lock_key = "DELIVER_FAN_LOCK_KEY";//泛分销发放
		$lock = $redis->setnx($lock_key, 'lock');
		if(!$lock) {
	    	log_message('error', date('Y-m-d H:i:s').' : '.microtime(TRUE).'泛分销绩效发放锁住状态');
	    	die('FAILURE!');
	    }
		
		set_time_limit ( 55 );
		$this->load->model('distribute/grades_model');
		$this->load->model('distribute/distribute_ext_model');
		$current_date = $this->get_redis_key_status('CURRENT_DATE_FAS');
		if(!$current_date || ($current_date && $current_date == date('Ymd',strtotime('-1 day')))){
			$this->rd_set('CURRENT_DATE_FAS',date('Ymd'));
			$this->distribute_ext_model->reset_fails_grades();
		}
		//取所有待发放的分销员
		$salers = $this->distribute_ext_model->get_auto_deliver_salers();
		
		echo '待发放人数'.count($salers).'<br/>';
		
		$batch_no = $this->gen_order_no();
		$saler_arr = $auto_deliver_salers = array();
		if($this->get_redis_key_status('_AUTO_DELIVER_FAS')){
			$auto_deliver_salers = json_decode($this->get_redis_key_status('_AUTO_DELIVER_FAS'),true);
			if($auto_deliver_salers['date'] == date('Ymd')){
				$saler_arr = $auto_deliver_salers['salers'];
			}else{
				$auto_deliver_salers['salers'] = $saler_arr;
				$auto_deliver_salers['date']   = date('Ymd');
				$this->rd_set('_AUTO_DELIVER_FAS',json_encode($auto_deliver_salers));
			}
		}else{
			$auto_deliver_salers['salers'] = $saler_arr;
			$auto_deliver_salers['date']   = date('Ymd');
			$this->rd_set('_AUTO_DELIVER_FAS',json_encode($auto_deliver_salers));
		}
		foreach ($salers as $saler){
			if(!in_array($saler->inter_id.$saler->saler,$saler_arr)){
				$saler_arr[] = $saler->inter_id.$saler->saler;
				
				$auto_deliver_salers['salers'] = $saler_arr;
				$this->rd_set('_AUTO_DELIVER_FAS',json_encode($auto_deliver_salers));
				
				//发放批号
				$auto_batch_no = $this->get_redis_key_status($saler->inter_id.'AUTO_FAS');
				if($auto_batch_no){
					$auto_config = json_decode($auto_batch_no,TRUE);
					if($auto_config['date'] == date('Y-m-d')){
						$batch_no = $auto_config['batch_no'];
					}else{
						$this->rd_set($saler->inter_id.'AUTO_FAS',json_encode(array('batch_no'=>$batch_no,'date'=>date('Y-m-d'))));
					}
				}else{
					$this->rd_set($saler->inter_id.'AUTO_FAS',json_encode(array('batch_no'=>$batch_no,'date'=>date('Y-m-d'))));
				}
				$this->distribute_ext_model->send_grades_by_saler_yestoday($saler->inter_id,$saler->saler,$batch_no);
			}
		}
		log_message('error', date('Y-m-d H:i:s').' : '.microtime(TRUE).' 分销绩效发放结束(粉丝)...');
		
		echo date('Y-m-d H:i:s').' : '.microtime(TRUE).' 分销绩效发放结束(粉丝)...';
		
		//更新最后发放时间
		$this->distribute_ext_model->update_last_deliver_time();
		$redis->delete($lock_key);//解锁
		echo 'success';
		
	}
	
	protected function _load_cache( $name='Cache' ){
		if(!$name || $name=='cache')
			$name='Cache';
		$this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file', 'key_prefix' => 'dis_ato_'), $name );
		return $this->$name;
	}
	public function get_redis_key_status($key = 'CONTINUE_DELIVER'){
		$cache= $this->_load_cache();
		$redis= $cache->redis->redis_instance();
		return $redis->get( $key );
	}
	//添加实例redis stgc 2017-02-24
	protected function get_redis_instance(){
		$cache= $this->_load_cache();
		$redis= $cache->redis->redis_instance();
		return $redis;
	}
	
	public function ck_dl(){
		print_r( $this->get_redis_key_status($this->input->get('key')));
	}
	public function set_dl(){
		print_r( $this->rd_set($this->input->get('key'),$this->input->get('val')));
	}
	public function s_dl($key = 'CONTINUE_DELIVER',$val = 'false'){
		$cache= $this->_load_cache();
		$redis= $cache->redis->redis_instance();
		print_r( $redis->set( $key , $val));
	}
	public function rd_set($key = 'CONTINUE_DELIVER',$val='false'){
		$cache= $this->_load_cache();
		$redis= $cache->redis->redis_instance();
		print_r( $redis->set( $key , $val));
	}
	
	public function sysc_summ(){
		set_time_limit(-1);
		$this->load->model('distribute/report_model');
		$this->report_model->sysc_summ();
		echo 'success';
	}
}