<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Cron计划任务
 */
class Cron extends MY_Controller {

    public $db_shard_config= array();
    public $current_inter_id= '';
    
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 运行日志记录
	 * @param String $content
	 */
	protected function _write_log( $content , $flag = false, $log_lv = 'log')
	{
	    $file= date('Y-m-d_H'). '.txt';
	    $path= APPPATH. 'logs'. DS. 'soma'. DS. 'cron'. DS;
	    if( !file_exists($path) ) {
	        @mkdir($path, 0777, TRUE);
	    }
	    $ip= $this->input->ip_address();
	    $fp = fopen( $path. $file, 'a');
		
		if(!$flag) {
		    $content= "\n[". date('Y-m-d H:i:s'). '] [' . $ip. "] [" . $log_lv . "] Task '". $content. "' starting...";
		} else {
			$content= "\n[". date('Y-m-d H:i:s'). '] [' . $ip. "] [" . $log_lv . "] Task '". $content;
		}
	    fwrite($fp, $content);
	    fclose($fp);
	}

	/**
	 * 此方法用于检测任务的可否执行。计划任务分来3类：
	 * 1 类是可以重复执行的，不加任何限制；
	 * 2 类是绝对不能重复执行的，要在执行之前加一个 remote_ip 的判断，只允许某一个服务器触发，其他ip一律不认
	 * 3 类是 担心会漏发（这个特许授权服务器ip挂掉了），必须在其他服务器加以保障的，跟第1类的区别是，第1类可以少发无实质性影响
	 * @param boolean $result TRUE可执行 false不可执行
	 */
	protected function _check_access()
	{
	    if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){ 
	        $ip_whitelist= array(
	            //'10.46.75.203', //test 1
	            '10.25.168.86', //redis01
	            '10.25.3.85',  //redis02
	        );
	        $client_ip= $this->input->ip_address();
	        if( in_array($client_ip, $ip_whitelist) ){
	            return TRUE;
	             
	        } else {
	            $msg= $this->action. ' 拒绝非法IP执行任务！';
	            $this->_write_log($msg);
	            return die($msg);
	        }
	        
	    } else {
	        return TRUE;
	    }
	}
	
	
	/**
	 * 定时执行产生核销码，后改为分配资产时分配新的code
	 * 每1小时执行/全量处理
	 */
	public function consume_code_generate()
	{
	    $this->_check_access();    //拒绝非法IP执行任务
	    
	    $this->_write_log(__FUNCTION__);
	    
	    $total_qty= 100;   //保持多少数量的可用核销码
	    $this->load->model('soma/consumer_code_model');
	    $result= $this->consumer_code_model->generate_newcode($total_qty);
	    echo $result? 'SUCCESS': 'FAIL';
	}
	
	/**
	 * 定时扫描并将未接受的赠礼退回到资产账户
	 * 每10分钟执行/处理量20个
	 */
	public function gift_auto_rollback()
	{
	    $this->_check_access();    //拒绝非法IP执行任务
	    
	    $this->_write_log(__FUNCTION__);
	    
	    $limit = 100;  //每次处理20个
	    $this->load->model('soma/gift_order_model' );
	    $this->load->model('soma/shard_config_model', 'model_shard_config');
	    $order= $this->gift_order_model->get_expired_orders($limit);
	    //print_r($order);die;
	    $result= TRUE;
	    if( count($order)>0 ){
            foreach ( $order as $k=> $v ) {
                //初始化数据库分片配置
                if( !$this->current_inter_id || $this->current_inter_id!= $v['inter_id'] ){
                    $this->current_inter_id= $v['inter_id'];
                    $this->db_shard_config= $this->model_shard_config->build_shard_config($this->current_inter_id);
                }
                $model= $this->gift_order_model->load($v['gift_id']);
                //print_r($v);
                if($model){
                    $result= $model->order_rollback( $v['business'], $v['inter_id'] );

                    //发送模版消息
                    if( $result ){
						/***********************发送模版消息****************************/
						$this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
						$MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;

						$type = $MessageWxtempTemplateModel::TEMPLATE_GIFT_RETURN;//礼物退回
						$openid = $v['openid_give'];
						$inter_id = $v['inter_id'];
		                $business = $v['business'];

		                $MessageWxtempTemplateModel->send_template_by_gift_success( $type, $model, $openid, $inter_id, $business);
						/***********************发送模版消息****************************/
                    }


                }//else 
                //    die('Can not find gift #'. $v['gift_id']. ' in idx.');
            }
	    }
	    echo $result? 'SUCCESS': 'FAIL';
	}
	
    /**
     * 拼团失败检测
     * 每10分钟执行/处理量
     * @author zhangyi@mofly.cn
     */
    public function groupon_refund_scan()
    {
	    $this->_check_access();    //拒绝非法IP执行任务
	    
	    $this->_write_log(__FUNCTION__);
	    
        $this->load->model('soma/activity_groupon_model');
        $this->load->model('soma/sales_order_model');
        $this->load->helper('soma/package');

        $GrouponModel = $this->activity_groupon_model;
        $db= $this->load->database('iwide_soma', TRUE);
        $table= $db->dbprefix('soma_shard_link');

        $interIdArr = $db->select('inter_id')
            ->group_by('inter_id')
            ->get($table)->result_array();
        if(!empty($interIdArr)){

            write_log("失效拼团扫描 @".date("Y-m-d H:i:s",time()));

            foreach($interIdArr as $v){
                $groups = $GrouponModel->get_unavailable_groupon($v['inter_id']); //遍历获取每个公众号前100条
                if(is_array($groups)){
//                    write_log("公众号：".$v['inter_id']."\n待处理过期拼团数目：".count($groups));
                    foreach($groups as $group){
                        //记录到带退款团表
                        $result = $GrouponModel->move_unavailable_groupon($group,$v['inter_id']);
                        write_log("interID is ".$v['inter_id']."\n".json_encode($result));

                        if(!empty($result['cancelList'])){
                                foreach($result['cancelList'] as $cancelOrder){
                                    $inter_id = $cancelOrder['inter_id'];
                                    if( $inter_id ){
                                        //初始化数据库分片配置，微信接口关闭订单需要初始化shard_id
                                        $this->load->model('soma/shard_config_model', 'model_shard_config');
                                        $this->current_inter_id= $cancelOrder['inter_id'];
                                        $this->db_shard_config= $this->model_shard_config->build_shard_config( $cancelOrder['inter_id'] );
                                    }
                                    $OrderModel = $this->SalesOrderModel->load( $cancelOrder['order_id'] );
                                    if( $OrderModel ){
                                        $this->load->model('soma/Reward_benefit_model','RewardBenefitModel');
                                        $RewardBenefitModel = $this->RewardBenefitModel;
                                        $benefitState = $RewardBenefitModel->modify_benefit_queue_refund(  $cancelOrder['inter_id'], $OrderModel );
                                        if($benefitState)
                                            write_log("order : " .$cancelOrder['order_id'] ."取消业绩状态 Success");
                                        else
                                            write_log("order : " .$cancelOrder['order_id'] ."取消业绩状态 Failed");
                                    }
                                }
                        }
                    }
                }
            }
            echo "success";
        }else{
            write_log("没有公众号列表");
            echo "没有公众号列表";
        }
    }

    /**
     * 拼团失败用户退款
     * 每10分钟执行/处理量
     * @author zhangyi@mofly.cn
     */
    public function groupon_refund_exc()
    {
	    $this->_check_access();    //拒绝非法IP执行任务
	    
	    $this->_write_log(__FUNCTION__);
	    
        $this->load->model('soma/activity_groupon_model');
        $this->load->model('soma/sales_refund_model');
        $this->load->model('soma/sales_order_model');
        $this->load->helper('soma/package');

        $SalesRefundModel = $this->sales_refund_model;
        $SalesOrderModel = $this->sales_order_model;

        $refundUsers = $this->activity_groupon_model->refund_users();

        if(is_array($refundUsers) && !empty($refundUsers)){
            foreach($refundUsers as $user){
                $inter_id = $user['inter_id'];
                if( $inter_id ){
                    //初始化数据库分片配置，微信接口关闭订单需要初始化shard_id
                    $this->load->model('soma/shard_config_model', 'model_shard_config');
                    $this->current_inter_id= $user['inter_id'];
                    $this->db_shard_config= $this->model_shard_config->build_shard_config( $user['inter_id'] );
                }

                $this->activity_groupon_model->refund_exc($SalesOrderModel,$SalesRefundModel,$user);


            }
        }
        echo "success";
    }

    /**
     * 拼团失败退款
 	 * 每10分钟执行/处理量
      * @author zhangyi@mofly.cn
     */
     public function groupon_auto_refund()
     {
	     $this->_check_access();    //拒绝非法IP执行任务
	    
	     $this->_write_log(__FUNCTION__);
	    
         $this->load->model('soma/activity_groupon_model');
         $this->load->model('soma/sales_refund_model');
         $this->load->model('soma/sales_order_model');

         $this->load->helper('soma/package');

         $SalesRefundModel = $this->sales_refund_model;
         $SalesOrderModel = $this->sales_order_model;
         $GrouponModel = $this->activity_groupon_model;

         $db= $this->load->database('iwide_soma', TRUE);
         $table= $db->dbprefix('soma_shard_link');
         $interIdArr = $db->select('inter_id')
             ->group_by('inter_id')
             ->get($table)->result_array();

         $res = array();
         $strTips = " Cron group refund : ";

         if(!empty($interIdArr)){
             foreach($interIdArr as $v){
                 $groups = $GrouponModel->get_unavailable_groupon($v['inter_id']);
                 write_log($strTips. json_encode($groups));
                 $res[$v['inter_id']] = $GrouponModel->set_unavailable_groupon($groups,$SalesOrderModel,$SalesRefundModel);
                 write_log($strTips .json_encode( $res));
             }

         }
         return $res;
     }

     /**
      * 根据最新的公众号表，生成对应的数据库分片记录
	  * 每1小时执行/全量处理
      * @author libinyan@mofly.cn
      */
     public function order_blacklist_clean()
     {
	     $this->_write_log(__FUNCTION__);
	    
         $this->load->model('soma/sales_order_model');
         $result= $this->sales_order_model->clean_order_client_ip();
         echo $result? 'SUCCESS': 'FAIL';
     }

	/**
	 * 根据最新的公众号表，生成对应的数据库分片记录
 	 * 每1小时执行/处理量
	 * @author libinyan@mofly.cn
	 */
	public function shard_init()
	{
	    $this->_write_log(__FUNCTION__);
	    
	    $this->load->model('wx/publics_model', 'publics');
	    $this->load->model('soma/shard_config_model', 'shard');
	    $publics = $this->publics->get_public();
	    //print_r($publics);die;

	    //根据分片定义表创建对应的表格
        $shard_ids= $this->shard->get_shard_ids();
        foreach ($shard_ids as $sv){
            $this->shard->init_shard_table($sv);
        }
        //将新的公众号更新到配置表中
	    foreach ($publics as $v){
	        $default_shard= 1;
	        $this->shard->reflesh_shard_data( $v->inter_id, $default_shard );
	    } 
	    echo 'table created finish';
	}

	/**
	 * 发送微信场景模板消息
 	 * 每1分钟执行/处理量100条
 	 * @author luguihong@mofly.cn
	 */
	public function message_wxtemp_sending()
	{
	    $this->_check_access();    //拒绝非法IP执行任务
	    
        $this->_write_log(__FUNCTION__);
        
        $limit= 100;
        $this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
        $this->MessageWxtempTemplateModel->sending_template_message( $limit );
        echo 'ok';
	}

	/**
	 * 套票过期生成模版消息
 	 * 每1分钟执行/处理量100条
 	 * @author luguihong@mofly.cn
	 */
	public function message_wxtemp_package_expire()
	{
	    $this->_check_access();    //拒绝非法IP执行任务
	    
	    $this->_write_log(__FUNCTION__);
	    
		$limit= 100;

		$db = $this->load->database('iwide_soma', TRUE);
		$table = $db->dbprefix('soma_shard_link');
		$interIdArr = $db->select('inter_id')
						 ->group_by('inter_id')
						 ->get($table)
						 ->result_array();

		if(!empty($interIdArr)){

			$this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
			$MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;

			foreach($interIdArr as $v){
				$inter_id = $v['inter_id'];
				if( $inter_id ){
					//初始化数据库分片配置，微信接口关闭订单需要初始化shard_id
	                $this->load->model('soma/shard_config_model', 'model_shard_config');
	                $this->current_inter_id= $v['inter_id'];
	                $this->db_shard_config= $this->model_shard_config->build_shard_config( $v['inter_id'] );
	                //print_r($this->db_shard_config);
				}
				$MessageWxtempTemplateModel->create_message_wxtemp( $limit, $inter_id );
			}

		}
		echo 'ok';
	}

	/**
	 * 套票过期如果是礼包，自动把礼包发送给当前用户
 	 * 每1分钟执行/处理量100条
 	 * @author luguihong@mofly.cn
	 */
	public function auto_member_package_to_user()
	{
	    $this->_check_access();    //拒绝非法IP执行任务
	    
	    $this->_write_log(__FUNCTION__);
	    
		$limit= 100;

		$db = $this->load->database('iwide_soma', TRUE);
		$table = $db->dbprefix('soma_shard_link');
		$interIdArr = $db->select('inter_id')
						 ->group_by('inter_id')
						 ->get($table)
						 ->result_array();

		if(!empty($interIdArr)){

			$this->load->model('soma/Asset_item_package_model','AssetItemModel');
			$AssetItemModel = $this->AssetItemModel;

			foreach($interIdArr as $v){
				$inter_id = $v['inter_id'];
				if( $inter_id ){
					//初始化数据库分片配置，微信接口关闭订单需要初始化shard_id
	                $this->load->model('soma/shard_config_model', 'model_shard_config');
	                $this->current_inter_id= $inter_id;
	                $this->db_shard_config= $this->model_shard_config->build_shard_config( $inter_id );
	                //print_r($this->db_shard_config);
				}
				$AssetItemModel->package_to_user( $inter_id, $limit );
			}

		}
		echo 'ok';
	}

	/**
	 * 发送分销业绩数据
	 * 每1分钟执行/处理量100条
	 * @author luguihong@mofly.cn
	 */
	public function order_reward_sending()
	{
	    $this->_check_access();    //拒绝非法IP执行任务
	    	  
	    $this->_write_log(__FUNCTION__);
	    
	     $limit= 100;
	     $this->load->library('Soma/Api_distribute');
	     $this->load->model('soma/Reward_benefit_model');
	     $result= $this->Reward_benefit_model->send_benefit_queue($limit);
	     //print_r($result);die;
	     $success_ids= array();
	     $api_distribute= new Api_distribute();
	     $full_field_status= array(
	         Reward_benefit_model::REWARD_STATUS_6 , 
	         Reward_benefit_model::REWARD_STATUS_11 ,
	     );
	     $return= FALSE;
	     foreach ($result as $k=>$v){
	         //针对这些状态做全字段信息推送
	         if( in_array( $v['reward_status'], $full_field_status) ) {
	             //新增绩效提成
	             $return = $api_distribute->reward_sending($v);
	             if( $return ) $success_ids[]= $v['reward_id'];
	         }
	     }
	     foreach ($result as $k=>$v){
	         //针对这些状态做部分字段信息推送
	         if( !in_array( $v['reward_status'], $full_field_status) ) {
	             //处理业绩提成
	             $return = $api_distribute->reward_modify($v );
	             if( $return ) $success_ids[]= $v['reward_id'];
	         }
	     }
	     //print_r($success_ids);die;
	     //处理已经发送成功的记录
	     $this->Reward_benefit_model->update_reward_status($success_ids, Reward_benefit_model::STATUS_SENDED );
	     echo $return? 'SUCCESS': 'EMPTY';
	}

	/**
	 * 业绩推送定时任务,新版
	 * 
	 * @author     F.oris <fengzhongcheng@mofly.com>
	 */
	public function push_order_reward_info() {
		$this->_check_access();    //拒绝非法IP执行任务
		$this->_write_log(__FUNCTION__);

		$limit = 100;
		$this->load->model('soma/Reward_benefit_model', 'r_model');
		$result = $this->r_model->send_benefit_queue($limit);
		$ext_rewards = $this->r_model->fill_reword_info_with_order($result);

		$this->load->library('Soma/Api_idistribute');
		$api = new Api_idistribute();

		// 所有订单付款后产生的分销业绩记录状态为6或11，
		// 这些订单肯定没有推送过，所以对这些订单进行信息完全推送
		$full_field_status= array(
			Reward_benefit_model::REWARD_STATUS_6 , 
			Reward_benefit_model::REWARD_STATUS_11 ,
		);

		$success_ids = array();

		foreach ($ext_rewards as $key => $reward) {
			$success = false;
			if(in_array($reward['reward_status'], $full_field_status)) {
				$success = $api->post_saler_sales_info($reward);
				if(!$success) {
					$success = $api->post_fans_sales_info($reward);
				}
			} else {
				$success = $api->update_saler_sales_info($reward);
				if(!$success) {
					$success = $api->update_fans_sales_info($reward);
				}
			}
			if($success) { $success_ids[] = $reward['reward_id']; }
		}

		$res = false;
		if(count($success_ids) > 0) {
			$res = $this->r_model->update_reward_status($success_ids, Reward_benefit_model::STATUS_SENDED);
		}

		echo $res ? 'SUCCESS' : 'FAIL';
	}

	/**
	 * 查找七天内无退款的业绩，进行成功标记
	 * 每1分钟执行/处理量100条
	 * @author luguihong@mofly.cn
	 */
	public function order_reward_checking()
	{
	     $this->_check_access();    //拒绝非法IP执行任务
	    
	     $this->_write_log(__FUNCTION__);
	    
	     //sleep(3); //为防止与order_reward_sending并发产生数据错误，延迟执行该方法
	     $limit= 100;
	     $this->load->model('soma/Reward_benefit_model');
	     $result= $this->Reward_benefit_model->set_benefit_norefund($limit);
	     echo $result? 'SUCCESS': 'EMPTY';
	}


	#  以下为秒杀流程控制   ################################################
	
	public function killsec_instance_init()
	{
		    $this->_check_access();    //拒绝非法IP执行任务
		   
		    $this->_write_log(__FUNCTION__ . ' start!', true);

		    $cache= $this->_load_cache();
		    $redis= $cache->redis->redis_instance();
		    $lock_key = 'SOMA:INSTANCE_CRONTAB_LOCK';
		    $lock = $redis->setnx($lock_key, 'lock');
		    if(!$lock) {
		    	$this->_write_log(__FUNCTION__ . ' lock fail!', true, 'error');
		    	die('FAILURE!');
		    }
		   
		    $this->load->model('soma/Activity_killsec_model');
		    $activitys= $this->Activity_killsec_model->get_preview_activity();
		       //print_r($activitys);//die;
		    $result= FALSE;
		    foreach ($activitys as $k=>$v) {
		        if( isset($v['inter_id']) && $v['inter_id'] ){
		      	     //清理过期的实例
		      	     $this->Activity_killsec_model->close_timeout_instance( $v['inter_id'], $v );
		            //批量处理加入新实例
		            $result= $this->Activity_killsec_model->insert_new_instance($v['inter_id'], $v );
		        }
		    }

		    $redis->delete($lock_key);

		    $this->_write_log(__FUNCTION__ . ' success!', true);

		    echo $result? 'SUCCESS': 'EMPTY';
	}
	public function killsec_user_order_cleaning()
	{
	    $this->_check_access();    //拒绝非法IP执行任务
	    
	    $this->_write_log(__FUNCTION__ . ' start!', true);

	    $cache= $this->_load_cache();
	    $redis= $cache->redis->redis_instance();
	    $lock_key = 'SOMA:INSTANCE_TOKEN_CRONTAB_LOCK';
	    $lock = $redis->setnx($lock_key, 'lock');
	    if(!$lock) {
	    	$this->_write_log(__FUNCTION__ . ' lock fail!', true, 'error');
	    	die('FAILURE!');
	    }
	    
	    $this->load->model('soma/Activity_killsec_model');
        $instance= $this->Activity_killsec_model->get_aviliable_instance();
        //print_r($instance);//die;
	    $result= FALSE;
	    foreach ($instance as $k=>$v) {
	        if( isset($v['inter_id']) && $v['inter_id'] ){
	            $result= $this->Activity_killsec_model->instance_processing($v['inter_id'], $v );
	        }
	    }
	    
	    // 拼接支付名额任务
	    $this->killsec_user_payment_cleaning();

	    $redis->delete($lock_key);

	    $this->_write_log(__FUNCTION__ . ' success!', true);

	    echo $result? 'SUCCESS': 'EMPTY';
	}
	public function killsec_user_payment_cleaning()
	{
	    $this->_check_access();    //拒绝非法IP执行任务

	    $this->_write_log(__FUNCTION__ . ' start!', true);
	    
	    $this->load->model('soma/Activity_killsec_model');
        $instance= $this->Activity_killsec_model->get_aviliable_instance();
	    //print_r($activitys);//die;
	    $result= FALSE;
	    foreach ($instance as $k=>$v) {
	        if( isset($v['inter_id']) && $v['inter_id'] ){

	            //初始化数据库分片配置，微信接口关闭订单需要初始化shard_id
	            if( $v['inter_id'] ){
	                $this->load->model('soma/shard_config_model', 'model_shard_config');
	                $this->current_inter_id= $v['inter_id'];
	                $this->db_shard_config= $this->model_shard_config->build_shard_config( $v['inter_id'] );
	                //print_r($this->db_shard_config);
	            }
	            
	            $result= $this->Activity_killsec_model->instance_payment_clean($v['inter_id'], $v );
	        }
	    }

	    $this->_write_log(__FUNCTION__ . ' success!', true);

	    echo $result? 'SUCCESS': 'EMPTY';
	}

	/**
	 * 秒杀订阅发送
	 * 执行频率每分钟
	 * @return [type] [description]
	 */
	public function killsec_notice_sending()
	{
	    $this->_check_access();    //拒绝非法IP执行任务
	    
	    $this->_write_log(__FUNCTION__);
	    
	    $limit= 50; //每次每个号的发送量；视群发情况设置
	    if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production') {
	        $is_all= FALSE;    //为TRUE时所有公众号一起发送；FALSE时按照分片发送
	        	  
	    } else {
	        $is_all= TRUE;    //为TRUE时所有公众号一起发送；FALSE时按照分片发送
	    }
	    
	    $min= substr(date('i'), 1,1 );
	    $this->load->model('wx/publics_model', 'publics');
	    $publics = $this->publics->get_public();
	    $des_pub = array();
	    foreach ($publics as $k=>$v ){
	        $v= (array) $v;
	        if( $is_all || (substr($v['inter_id'], -1)==$min && strlen($v['inter_id'])==10) ){
	            $des_pub[]= $v;
	        }
	        //if( $v['inter_id']=='a450089706' ) $des_pub[]= $v;   //测试放心住
	    }
	    //print_r($des_pub);die;
	    $result= FALSE;
	    foreach ($des_pub as $v){
	        $inter_id = $v['inter_id'];
	        $this->load->model('soma/Activity_killsec_model');
	        $sendlist= $this->Activity_killsec_model->get_waiting_notice_list($inter_id, $limit);
//if($v['inter_id']=='a450089706'){
//    print_r($sendlist);die;
//} 
	        if( count($sendlist)>0 ){
	            $this->load->model('soma/Message_wxtemp_template_model');
	            $result= $this->Message_wxtemp_template_model->send_template_by_killsec_subscriber( $inter_id, $sendlist, $v );

	            $this->Activity_killsec_model->set_waiting_notice_list($inter_id, $result );
	        }
	    }
	    
	    //清理一段时间内的记录，执行时间3点01分
	    if( date('H')=='03' && date('i')=='01' )
	        $this->Activity_killsec_model->cleanup_waiting_notice_list(7);
	    
	    echo $result? 'SUCCESS': 'EMPTY';
	}
	
	/**
	 * 销售统计信息维护
	 */
	public function statis_update_sales()
	{
	    $this->_check_access();    //拒绝非法IP执行任务
	    
	    $this->_write_log(__FUNCTION__);
	    
        $this->load->model('soma/Statis_sales_model', 'Statis_sales_model');
	    $statis_model= $this->Statis_sales_model->init_service();
	    
	    if( $statis_model->check_sales_data() ){
	        $result= $statis_model->update_sales_data( date('Y-m-d'). ' 00:00:00' );
	        
	    } else {
	        $result= $statis_model->update_sales_data();
	    }
	    echo $result? 'SUCCESS': 'FAIL';
	}

	
	
	
	#  以下为测试方法    ######################################################
    /**
     * *拼团失败退款失败修复
     */
    public function groupon_auto_refund_retry()
    {
	    $this->_check_access();    //拒绝非法IP执行任务
	    
	    $this->_write_log(__FUNCTION__);
	    
        $this->load->model('soma/activity_groupon_model');
        $this->load->model('soma/sales_refund_model');
        $this->load->model('soma/sales_order_model');

        $this->load->helper('soma/package');

        $SalesRefundModel = $this->sales_refund_model;
        $SalesOrderModel = $this->sales_order_model;
        $GrouponModel = $this->activity_groupon_model;

        $db= $this->load->database('iwide_soma', TRUE);
        $table= $db->dbprefix('soma_shard_link');
        $interIdArr = $db->select('inter_id')
            ->group_by('inter_id')
            ->get($table)->result_array();

        $res = array();
        $strTips = " Cron group refund Retry (BUG FIX): ";

        $this->load->model('soma/shard_config_model', 'model_shard_config');

        if(!empty($interIdArr)){
            foreach($interIdArr as $v){
                $groups = $GrouponModel->get_unavailable_groupon_failed($v['inter_id']);

                //write_log($strTips. json_encode($groups));
                $this->current_inter_id= $v['inter_id'];
                $this->db_shard_config= $this->model_shard_config->build_shard_config($v['inter_id']);

                $res[$v['inter_id']] = $GrouponModel->set_unavailable_groupon($groups,$SalesOrderModel,$SalesRefundModel);
                write_log($strTips .json_encode( $res));
            }

        }
        return $res;
    }

    public function checkRefund()
    {
        $this->_check_access();    //拒绝非法IP执行任务
	    
        $this->_write_log(__FUNCTION__);
         
        $order_id = 1000001403;
        $inter_id = 'a429262687';
        $this->load->model('soma/sales_refund_model');

        $this->load->model('soma/shard_config_model', 'model_shard_config');
        $this->current_inter_id= $inter_id;
        $this->db_shard_config= $this->model_shard_config->build_shard_config($inter_id);

        $x = $this->sales_refund_model->wx_refund_check($order_id,'package',$inter_id);

        var_dump($x);
    }
    
	/* public function order_blacklist()
	{
	    $this->_write_log(__FUNCTION__);
	    
	    $this->load->model('soma/sales_order_model');
	    $customer= new Sales_order_attr_customer('1231233');
	    $this->sales_order_model->customer= $customer;
	    $r= $this->sales_order_model->remark_order_ip($customer, 'package');
	    var_dump($r);
	    if($this->sales_order_model->check_client_can_order($customer, 'package'))
	        echo '允许下单';
	    else 
	        echo '超过限制';
	    echo $this->sales_order_model->clean_order_client_ip();
	} */

	/**
	 * 订单统计信息
	 * 执行频率5分钟一次
	 * @return [type] [description]
	 */
	public function order_statis_summary()
	{
	    $this->_check_access();    //拒绝非法IP执行任务
	    
		$this->_write_log(__FUNCTION__);

		// 构建过滤器，只过滤：$start_time, $end_time, $limit, $offset
		// $date = date('Y-m-d');
		
		$start_time = $this->input->get('start_time');
		$end_time = $this->input->get('end_time');
		$init = $this->input->get('init');

		if($init) {
			$start_time = '1970-01-01 00:00:00';
			$end_time = date('Y-m-d') . ' 23:59:59';
		} else {
			$start_date = date("Y-m-d",strtotime("-1 day"));
			$now_date = date('Y-m-d');		
			if(!$start_time) { $start_time = $start_date . ' 12:00:00'; }
			if(!$end_time) { $end_time = $now_date . ' 23:59:59'; }
		}
		
		$limit = $offset = null;
		$filter = compact("start_time", "end_time", "limit", "offset");

		// 初始化数据库分片配置，微信接口关闭订单需要初始化shard_id
		// model 里面 $this->_share_db($inter_id)  #inter_id 不能为空
		// 控制器设置了$this->current_inter_id，可以使用$this->_share_db()
		// $this->load->model('soma/shard_config_model', 'model_shard_config');
		// $this->db_shard_config= $this->model_shard_config->build_shard_config();

		$this->load->model('soma/sales_order_model', 'o_model');
		$summary = $this->o_model->get_order_summary($filter);
		$res = $this->o_model->save_order_summary($summary);

		echo $res ? 'SUCCESS' : 'Failed';

	}

	/**
	 * 订单异常报警
	 * 执行频率1小时1次
	 * @return [type] [description]
	 */
	public function order_exception_warning()
	{
		$this->_write_log(__FUNCTION__);

		$warning_avg_price= 1;    //客单价1元
		$warning_avg_count= 50;   //检测超过50单
		$warning_count= 100;  //超过一百单
		
		$cache= $this->_load_cache();
		$redis= $cache->redis->redis_instance();
		 
	    //数量异常
	    $this->load->model('soma/Statis_sales_model');
	    $statis_model= $this->Statis_sales_model;
	    
	    $check_member= array( date('Y-m-d'), date('Y-m-d', strtotime('-1 days') ) );
	    //print_r($check_member);die;

	    $order_message= '';
	    $order_summary= 0;
	    $count_summary= 0;
	    $has_execption= FALSE;
	    $this->load->model('wx/Publics_model');
	    $public_array= $this->Publics_model->get_public_hash();
	    if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' ){
	        $project_name= '月饼说';
	    } else {
	        $project_name= '社交商城';
	    }
	    foreach ($public_array as $k=>$v){
	        $qty_key= $statis_model->redis_token_key($v['inter_id'], $statis_model::K_SALE_QTY );
	        $total_key= $statis_model->redis_token_key($v['inter_id'], $statis_model::K_SALE_TOTAL );
	        $count_key= $statis_model->redis_token_key($v['inter_id'], $statis_model::K_SALE_COUNT );

	        //订单数量异常，超过100
	        $exception1 = $redis->zRangeByScore($count_key, $warning_count, -1, array('withscores' => TRUE) );
	        foreach ($exception1 as $sk=> $sv){
	            //过滤不报警的日期
	            if( !in_array($sk, $check_member ) ) unset($exception1[$sk]);
	        }

	        //客单价异常，超过 订单总金额/订单数量 < 某金额
	        $exception2 = $redis->zRangeByScore($count_key, $warning_avg_count, 10000000, array('withscores' => TRUE) );
	        $exception3 = $redis->zRangeByScore($total_key, 0, 10000000, array('withscores' => TRUE) );
	        $today_total= isset($exception3[date('Y-m-d')])? $exception3[date('Y-m-d')]: 0;
	        foreach ($exception2 as $sk=> $sv){
	            //过滤不报警的日期
	            if( !in_array($sk, $check_member ) ) {
	                unset($exception2[$sk]);
	                unset($exception3[$sk]);
	                 
	            } elseif( $exception3[$sk]/$exception2[$sk]< $warning_avg_price ) {
	                //订单量达到一定量，并且客单价过低，进入报警
	                
	            } else {
	                unset($exception2[$sk]);
	                unset($exception3[$sk]);
	            }
	        }
	        
	        $this->load->model('soma/Message_wxtemp_template_model','MessageWxtempTemplateModel');
            $MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;
            $type = $MessageWxtempTemplateModel::TEMPLATE_CONSUMER_SUCCESS;
            $openid_arr = $MessageWxtempTemplateModel->get_notice_openids();
            $inter_id= 'a450089706';
            $business= 'package';
            
	        if( count($exception1)>0 ){
	            //异常情况发送警告信息
	            $message= "{$project_name}【{$v['name']}】系统订单数量超过【{$warning_count}】单，异常日期：【". 
	                implode(',', array_keys($exception1)) . '】，请即时查看该公众号订单数据。具体数据【'. json_encode($exception1). '】';
	            
	            /***********************发送模版消息****************************/
                //发送模版消息
                foreach ($openid_arr as $k => $v) {
                    $openid = $v;
                    $MessageWxtempTemplateModel->send_template_by_consume_or_booking_success( $type, '', $openid, $inter_id, $business, $message, FALSE);
                }
                /***********************发送模版消息****************************/
                
                $has_execption= TRUE;
	            echo $message;
	        }
	        if( count($exception2)>0 ){
	            //异常情况发送警告信息
	            $message= "{$project_name}【{$v['name']}】系统订单累计超过【{$warning_avg_count}单】客单价低于【￥{$warning_avg_price}】，异常日期：【"
	                . implode(',', array_keys($exception2)) . '】，请即时查看该公众号订单数据。';
	            
	            /***********************发送模版消息****************************/
                //发送模版消息
                foreach ($openid_arr as $k => $v) {
                    $openid = $v;
                    $MessageWxtempTemplateModel->send_template_by_consume_or_booking_success( $type, '', $openid, $inter_id, $business, $message, FALSE);
                }
                /***********************发送模版消息****************************/
                
                $has_execption= TRUE;
	            echo $message;
	        }
	        
	        if($today_total>0 ){
	            $order_count= $redis->zScore( $statis_model->redis_token_key($v['inter_id'], $statis_model::K_SALE_COUNT), date('Y-m-d') );
	            $order_summary += $today_total;
	            $count_summary += $order_count;
	            $today_total= number_format($today_total, 2);
	            $order_message.= "【{$v['name']}】:【￥{$today_total}，{$order_count}单】\n";
	        }

	    }
	    if( !$has_execption ){
	        echo 'Release, Nothing Happen.';
	    }
	    
	    $order_summary= number_format($order_summary, 2);
	    $order_message= "{$project_name}今日累计【￥{$order_summary}，{$count_summary}单】\n". $order_message;
	    
	    //每日早晚定时汇总销售额
	    if( $this->input->get('report')==1 || ( in_array(date('H'), array('13', '23') ) && 
	        isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production') ){
	        /***********************发送模版消息****************************/
	        //发送模版消息
	        foreach ($openid_arr as $k => $v) {
	            $openid = $v;
	            $MessageWxtempTemplateModel->send_template_by_consume_or_booking_success( $type, '', $openid, $inter_id, $business, $order_message, FALSE);
	        }
	        /***********************发送模版消息****************************/
	    }
	}
	
	public function update_product_statis() {
		$this->_check_access();    //拒绝非法IP执行任务
		$this->_write_log(__FUNCTION__);
		$this->load->model('soma/statis_product_model', 's_model');
		$data_check = $this->s_model->check_statis_data();
		if($data_check) {
			// 存在数据，更新当天数据
			$s_time = date('Y-m-d') . ' 00:00:00';
			$e_time = date('Y-m-d H:i:s');
			// var_dump($s_time, $e_time);exit;
			$this->s_model->update_statis_data($s_time, $e_time);
		} else {
			// 不存在数据，更新90天前的数据，防止数据溢出，15天为更新梯度
			$s_time = date('Y-m-d', strtotime("-90 days")) . ' 00:00:00';
			$e_time = date('Y-m-d', strtotime("+10 days", strtotime($s_time)));
			$now_time = date('Y-m-d H:i:s');

			while (strtotime($s_time) < strtotime($now_time)) {
				$this->s_model->update_statis_data($s_time, $e_time);
				$s_time = $e_time;
				$e_time = date('Y-m-d', strtotime("+10 days", strtotime($s_time)));
			}
		}
		echo "success";
	}
	
	
    //修正码表脚本
	public function consumer_code_fixed()
	{
	    $limit= 100;
	    $log_txt= "码表维护记录:--\n";
	    $db= $this->load->database('iwide_soma', TRUE);
	    $table_code= 'iwide_soma_consumer_code';
	    $table_order= 'iwide_soma_sales_order_idx';
	    $table_ai1= 'iwide_soma_asset_item_package_1001';
	    $table_ai2= 'iwide_soma_asset_item_package_1002';
	
	    //更新有order_id 的记录
	    $result= $db->query("update {$table_code} as c, {$table_order} as o set c.inter_id=o.inter_id "
	    . " where c.order_id=o.order_id and c.inter_id is null" );
	    //var_dump($result);die;
	    if($result) $log_txt.= "根据order_id成功更新所有的记录--\n";
	     
	    $codes= $db->where('inter_id', NULL)->where('asset_item_id is not', NULL)
	    ->where('status', 2)->get($table_code)->result_array();
// echo $db->last_query();die;
	    // var_dump( $codes );die;
	    $assetItemIds = array();//记录没有处理的

	    $code_cnt= count($codes);
	    if( $code_cnt >0 ){
	        $index_i= 1;
	        $log_txt.= "需要循环更新的code记录有{$code_cnt}条--\n";
	        foreach ($codes as $k=>$v){
	        	$flag = TRUE;
	            if( $index_i> $limit ) break;
	             
	            //首先各自找出2个分片中的资产记录。
	            $ai1= $db->where('item_id', $v['asset_item_id'])->get($table_ai1)->row_array();
	            $ai2= $db->where('item_id', $v['asset_item_id'])->get($table_ai2)->row_array();
	            //分析情况1：一边有记录，另外一边没有该asset_item_id
	            if( $ai1 && !$ai2 ){
	                $result= $db->query("update `{$table_code}` set inter_id='{$ai1['inter_id']}', asset_id='{$ai1['asset_id']}', "
	                   . "order_item_id='{$ai1['order_item_id']}', order_id='{$ai1['order_id']}' where code_id='{$v['code_id']}'" );
	                $log_txt.= $index_i++. "ID:【{$v['code_id']}】从分片1细单【{$ai1['item_id']}】更新--\n";
	                $flag = FALSE;
	            } elseif( !$ai1 && $ai2 ){
	                $result= $db->query("update `{$table_code}` set inter_id='{$ai2['inter_id']}', asset_id='{$ai2['asset_id']}', "
	                    . "order_item_id='{$ai2['order_item_id']}', order_id='{$ai2['order_id']}' where code_id='{$v['code_id']}'" );
                    $log_txt.= $index_i++. "ID:【{$v['code_id']}】从分片2细单【{$ai2['item_id']}】更新--\n";
                    $flag = FALSE;
	            } elseif( $ai1 && $ai2 ){
	                //各自搜索两分片的qty与code数量是否匹配，数量匹配则不修正
                    $ai1_code= $db->where('inter_id', $ai1['inter_id'])->where('asset_item_id', $ai1['item_id'])->where('status', 2)->get($table_code)->result_array();
                    if( count($ai1_code)< $ai1['qty'] ){
                        $result= $db->query("update `{$table_code}` set inter_id='{$ai1['inter_id']}', asset_id='{$ai1['asset_id']}', "
                            . "order_item_id='{$ai1['order_item_id']}', order_id='{$ai1['order_id']}' where code_id='{$v['code_id']}'" );
                        $log_txt.= $index_i++. "ID:【{$v['code_id']}】匹配分片1细单【{$ai1['item_id']}】更新--\n";
                        $flag = FALSE;
                    } else {
	                    $ai2_code= $db->where('inter_id', $ai2['inter_id'])->where('asset_item_id', $ai2['item_id'])->where('status', 2)->get($table_code)->result_array();
	                    if( count($ai2_code)< $ai2['qty'] ){
	                        $result= $db->query("update `{$table_code}` set inter_id='{$ai2['inter_id']}', asset_id='{$ai2['asset_id']}', "
	                            . "order_item_id='{$ai2['order_item_id']}', order_id='{$ai2['order_id']}' where code_id='{$v['code_id']}'" );
	                        $log_txt.= $index_i++. "ID:【{$v['code_id']}】匹配分片2细单【{$ai2['item_id']}】更新--\n";
	                        $flag = FALSE;
	                    }

                    }
                }

                //记录没有处理的
                if( $flag ){
                	$assetItemIds[] = $v['asset_item_id'];
                }

	        }

	        if( count( $assetItemIds ) > 0 ){
	        	$assetItemIds_str = implode(',', $assetItemIds);
	        	$log_txt.= "【没有处理的资产细单ID：{$assetItemIds_str}】\n";
	        }
	         
	    } else {
	        $log_txt.= "找不到需要循环更新的code记录--\n";
	    }
	     
	    //写入log文件
	    $file= date('Y-m-d'). '.txt';
	    $path= APPPATH. 'logs'. DS. 'soma'. DS. 'code'. DS;
	    if( !file_exists($path) ) {
	        @mkdir($path, 0777, TRUE);
	    }
	    $ip= $this->input->ip_address();
	    $fp = fopen( $path. $file, 'a');
	    $content= str_repeat('-', 40). "\n[". date('Y-m-d H:i:s'). ']'
	        ."\n". $ip. "\n". $log_txt. "\n";
	    fwrite($fp, $content);
	    fclose($fp);
	    echo $log_txt;
	}

	/**
	 * Calculates the order point.
	 */
	public function calc_order_point(){
		$this->_check_access();    //拒绝非法IP执行任务
		$this->_write_log(__FUNCTION__);
		$this->load->model('soma/sales_point_model', 'sp_model');
		$this->sp_model->trans_begin();
		try {

			$s_time = date('Y-m-d H:i:s', strtotime("-2 hours"));

			$days = $this->input->get('d');
			if($days) {
				$s_time = date('Y-m-d', strtotime("-$days days")) . ' 00:00:00';
			}

			$this->sp_model->update_point_queue($s_time);
			$this->sp_model->trans_commit();
			die('SUCCESS');
		} catch (Exception $e) {
			$this->sp_model->trans_rollback();
		}
		die('Failed');
	}


	/**
	 * Pushes a point information.
	 */
	public function push_point_info() {
		$this->_check_access();    //拒绝非法IP执行任务
		$this->_write_log(__FUNCTION__);
		$this->load->model('soma/sales_point_model', 'sp_model');
		$res = $this->sp_model->push_point_info();
		echo $res ? 'SUCCESS' : 'Failed';
	}
	
}
