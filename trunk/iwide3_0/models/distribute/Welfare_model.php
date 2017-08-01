<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author John
 * @package models\distribute
 */
class Welfare_model extends MY_Model {

	public function get_resource_name()
	{
		return '福利信息';
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    public function _shard_db($inter_id=NULL)
    {
        return $this->_db();
    }

    public function _shard_table($basename, $inter_id=NULL )
    {
        return $basename;
    }

	/**
	 * @return string the associated database table name
	 */
	public function table_name()
	{
		return 'welfare';
	}

    /**
     * 保存福利发放配置
     * @param unknown $inter_id
     * @param unknown $params
     */
    public function save_config($inter_id,$params){
    	$config_entity = $this->get_config($inter_id);
    	if($config_entity){
    		if(isset($params['upper_limit_typ']) && $config_entity->upper_limit_typ != $params['upper_limit_typ']){
    			$avgs['upper_limit_typ'] = $params['upper_limit_typ'];
    		}
    		if(isset($params['upper_limit_day_times']) && $config_entity->upper_limit_day_times != $params['upper_limit_day_times']){
    			$avgs['upper_limit_day_times'] = $params['upper_limit_day_times'];
    		}
    		if(isset($params['upper_limit_day_amount']) && $config_entity->upper_limit_day_amount != $params['upper_limit_day_amount']){
    			$avgs['upper_limit_day_amount'] = $params['upper_limit_day_amount'];
    		}
    		if(isset($params['welfare']) && $config_entity->welfare != $params['welfare']){
    			$avgs['welfare'] = $params['welfare'];
    		}
    		$avgs['last_update_time'] = date("Y-m-d H:i:s");
    		$this->_db('iwide_rw')->where(array('inter_id'=>$inter_id));
    		$res = $this->_db('iwide_rw')->update('welfare_config',$avgs);
    		//--log
    		$this->_log_operation('更新福利配置',json_encode(array('OLD'=>$config_entity,'NEW'=>$params)),1);
    		return $res;
    	}else{
    		$params['inter_id'] = $inter_id;
    		$params['create_time'] = date("Y-m-d H:i:s");
    		$res = $this->_db('iwide_rw')->insert('welfare_config',$params);
    		//--log
    		$this->_log_operation('写入福利配置',json_encode($params),1);
    		return $res;
    	}
    }
    
    /**
     * @param unknown $inter_id
     */
    public function get_config($inter_id = NULL){
    	if(empty($inter_id)){
    		$admin_profiler = $this->session->userdata ( 'admin_profile' );
    		$inter_id = $admin_profiler['inter_id'];
    	}
    	$this->_db('iwide_r1')->where('inter_id',$inter_id);
    	$this->_db('iwide_r1')->limit(1);
    	return $this->_db('iwide_r1')->get('welfare_config')->row();
    }
    public function get_operation_log($inter_id,$btime = NULL,$etime = NULL,$admin = '',$limit = NULL,$offset = 0){
    	$sql = "SELECT o.*,i.amount,i.out_trade_no,i.saler,i.send_time,i.`status`,COUNT(i.id) persons,SUM(i.amount) amounts,s.name FROM iwide_welfare_orders o LEFT JOIN iwide_welfare_order_items i ON o.id=i.oid LEFT JOIN iwide_hotel_staff s ON o.inter_id=s.inter_id AND i.saler=s.qrcode_id WHERE o.inter_id=?";
    	$params[] = $inter_id;
    	if(!empty($btime)){
    		$sql .= " AND o.create_time>=?";
    		$params[] = $btime;
    	}
    	if(!empty($etime)){
    		$sql .= " AND o.create_time<=?";
    		$params[] = $etime.' 23:59:59';
    	}
        if(!empty($admin)){
            $sql .= " AND o.admin_username LIKE ?";
            $params[] = "%$admin%";
        }
    	$sql .= " GROUP BY o.id ORDER BY o.id DESC";
    	if(!empty($limit)){
    		$sql .= " LIMIT ?,?";
    		$params[] = $offset;
    		$params[] = $limit;
    	}
    	return $this->_db('iwide_r1')->query($sql,$params);
    }
    public function get_operation_log_count($inter_id,$btime = NULL,$etime = NULL,$admin = ''){
    	$sql = "SELECT count(id) nums FROM iwide_welfare_orders o WHERE o.inter_id=?";
    	$params[] = $inter_id;
    	if(!empty($btime)){
    		$sql .= " AND o.create_time>=?";
    		$params[] = $btime;
    	}
    	if(!empty($etime)){
    		$sql .= " AND o.create_time<=?";
    		$params[] = $etime.' 23:59:59';
    	}
        if(!empty($admin)){
            $sql .= " AND o.admin_username LIKE ?";
            $params[] = "%$admin%";
        }
    	$query = $this->_db('iwide_r1')->query($sql,$params)->row();
    	return is_null($query->nums) ? 0 : $query->nums;
    }
    public function create_welfare($salers,$amount,$title,$typ=1){
			// $this->_db ( 'iwide_rw' )->trans_begin ();
		$this->load->helper('common');
		$admin_profiler = $this->session->userdata ( 'admin_profile' );
		$params = array (
				'batch_no'       => $this->gen_order_no (),
				'inter_id'       => $admin_profiler ['inter_id'],
				'title'          => $title,
				'remote_ip'      => getIp(),
				'create_time'    => date('Y-m-d H:i:s'),
				'admin_username' => $admin_profiler ['username'],
				'admin_id'       => $admin_profiler ['admin_id'] 
		);
		if ($this->_db ( 'iwide_rw' )->insert ( 'welfare_orders', $params )) {
			$wid = $this->_db ( 'iwide_rw' )->insert_id();
			$salers = $this->get_salers_openid_by_saler_id ( $admin_profiler ['inter_id'], explode ( ',', $salers ) );
			// $sql = "INSERT INTO iwide_welfare_order_items (inter_id,saler,openid,amount,status,out_trade_no,send_time,oid)
			// SELECT ?,qrcode_id,openid,?,1,NOW(),? FROM iwide_hotel_staff hs WHERE hs.inter_id=? AND hs.qrcode_id IN ({implode(',',$salers)})";
			// $this->_db ( 'iwide_rw' )->query ( $sql, array ( $admin_profiler ['inter_id'], $amount, $wid ) );
			$batch_arr = array ();
			$deal_res = array (
					'success' => 0,
					'failed'  => 0,
					'error'   => 0 
			);
			$send_inter_id = $admin_profiler ['inter_id'];
			if($typ == 2){
				//配置的第三方公众号ID
				$distribution_delier_account = $this->get_redis_key_status ( '__DISTRIBUTION_DELIER_ACCOUNT' );
				if (!$distribution_delier_account){
					//return array ( 'errmsg' => 'faild', 'return_msg' => 'MISSING_RELATIONSHIP_INTER_ID' );
					$deal_res ['failed'] = $salers->num_rows();
					return $deal_res;
				}
				$send_inter_id = $distribution_delier_account;
				$this->load->model ( 'distribute/openid_rel_model' );
			}
			foreach ( $salers->result () as $saler ) {
				$res = array();
				$tmp_arr = array();
				if($saler->qrcode_id > 0 && $this->check_config($saler->qrcode_id,2,$amount)){
					if ($typ == 1) {//酒店公众号发放
						$res = $this->company_pay ( $saler->openid, $amount * 100, $send_inter_id );
					} else {//第三方公众号发放
						$rel_obj = $this->openid_rel_model->get_openid_relationship ( $admin_profiler ['inter_id'], $saler->openid, $distribution_delier_account );
						if (isset ( $rel_obj->m_openid ) && ! empty ( $rel_obj->m_openid )) {
							$tmp_arr['rel_openid']   = $rel_obj->m_openid;
							$tmp_arr['rel_inter_id'] = $distribution_delier_account;
							$tmp_arr['typ']          = 2;
							$rel_openid = $rel_obj->m_openid;
							$res = $this->company_pay ( $rel_obj->m_openid, $amount * 100, $send_inter_id );
						} else{
							$deal_res ['failed'] = $salers->num_rows();
							return $deal_res;
							//return array ( 'errmsg' => 'faild', 'return_msg' => 'MISSING_RELATIONSHIP_OPENID' );
						}
					}
				}else{
					$res['errmsg']           = 'failed';
					$res['partner_trade_no'] = '';
					$res['send_content']     = '';
					$res['rec_content']      = '';
					$res['ckres']            = '不满足发放规则';
				}
				$tmp_arr += array (
						'inter_id'     => $admin_profiler ['inter_id'],
						'saler'        => $saler->qrcode_id,
						'openid'       => $saler->openid,
						'amount'       => $amount,
						'out_trade_no' => $res ['partner_trade_no'],
						'send_content' => $res ['send_content'],
						'rec_content'  => $res ['rec_content'],
						'send_time'    => date ( 'Y-m-d H:i:s' ),
						'oid'          => $wid 
				);
				if ($res ['errmsg'] == 'ok') {
					$tmp_arr ['status'] = 1;
					$deal_res['success'] += 1;
				} else if ($res ['errmsg'] == 'failed') {
					$tmp_arr ['status'] = 2;
					$deal_res['failed'] += 1;
				} else {
					$tmp_arr ['status'] = 3;
					$deal_res['error'] += 1;
				}
				if (isset ( $res ['ckres'] )) {
					$tmp_arr ['remark'] = $res ['ckres'];
				}
				$batch_arr [] = $tmp_arr;
			}
			if(empty($batch_arr)){
				return array ( 'success' => 0, 'failed'  => 0, 'error'   => 0 );
			}
			if($this->_db ( 'iwide_rw' )->insert_batch ( 'welfare_order_items', $batch_arr )){
// 				echo $this->_db ( 'iwide_rw' )->last_query();
				return $deal_res;
			}else{
// 				echo $this->_db ( 'iwide_rw' )->last_query();
				log_message('error', '发放记录写入失败：'.json_encode($batch_arr));
				return array ( 'success' => 0, 'failed'  => 0, 'error'   => 0 );
			}
		}
		// if ($this->_db ( 'iwide_rw' )->trans_status === FALSE) {
		// $this->_db ( 'iwide_rw' )->trans_rollback ();
		// return FALSE;
		// } else {
		// $this->_db ( 'iwide_rw' )->trans_commit ();
		// return TRUE;
		// }
	}

	/**
	 * 分销员当天的已发放次数
	 * @param unknown $saler
	 * @param unknown $inter_id
	 */
	public function get_saler_today_send_times($saler, $inter_id) {
		$sql = "SELECT COUNT(saler) nums FROM iwide_welfare_order_items WHERE DATE_FORMAT(send_time,'%Y%m%d')=? AND saler=? AND inter_id=? AND (`status`=1 OR `status`=3)";
		$query = $this->_db ( 'iwide_r1' )->query ( $sql, array ( date ( 'Ymd' ), $saler, $inter_id ) )->row();
		return is_null($query->nums) ? 0 : $query->nums;
	}
	/**
	 * 分销员当天的已发放金额
	 * @param unknown $saler
	 * @param unknown $inter_id
	 */
	public function get_saler_today_send_amounts($saler, $inter_id) {
		$sql = "SELECT SUM(amount) amounts FROM iwide_welfare_order_items WHERE DATE_FORMAT(send_time,'%Y%m%d')=? AND saler=? AND inter_id=? AND (`status`=1 OR `status`=3)";
		$query = $this->_db ( 'iwide_r1' )->query ( $sql, array ( date ( 'Ymd' ), $saler, $inter_id ) )->row();
		return is_null($query->amounts) ? 0 : $query->amounts;
	}
	private function get_salers_openid_by_saler_id($inter_id,$saler_ids){
		$this->_db('iwide_r1')->where(array('inter_id'=>$inter_id));
		if(is_array($saler_ids)){
			$this->_db('iwide_r1')->where_in('qrcode_id',$saler_ids);
		}else{
			$this->_db('iwide_r1')->where(array('qrcode_id'=>$saler_ids));
		}
		$this->_db('iwide_r1')->select(array('qrcode_id','openid'));
		return $this->_db('iwide_r1')->get('hotel_staff');
	}
	/**
	 * Check if the welfare can be deliver
	 * @param unknown $saler
	 * @param number $stype
	 * @param number $amount
	 * @param string $inter_id
	 * @return boolean
	 */
	public function check_config($saler,$stype=2,$amount = 0,$inter_id = NULL){
		if(empty($inter_id)){
			$admin_profiler = $this->session->userdata ( 'admin_profile' );
			$inter_id = $admin_profiler['inter_id'];
		}
		$config = $this->get_config($inter_id);
		if($config){
			if(($stype == 2 && $config->welfare != 2) || $config->upper_limit_day_times <= $this->get_saler_today_send_times($saler, $inter_id)){
				return FALSE;
			}
			$upper_limit = $config->upper_limit_day_amount;
			if($config->upper_limit_typ == 1){
				$upper_limit = $this->get_saler_undeliver_grades($saler, $inter_id);
			}
			return ($upper_limit >= $this->get_saler_today_send_amounts($saler, $inter_id) + $amount);
		}else{
			return FALSE;
		}
		
	}
	/**
	 * 分销员待发放的绩效
	 * @param unknown $saler
	 * @param unknown $inter_id
	 */
	public function get_saler_undeliver_grades($saler,$inter_id){
		$sql = "SELECT SUM(grade_total) amounts FROM iwide_distribute_grade_all WHERE saler=? AND inter_id=? AND status=1";
		$query = $this->_db('iwide_r1')->query($sql,array($saler,$inter_id))->row();
		return is_null($query->amounts) ? 0 : $query->amounts;
	}
    /**
     * 日志
     * @param unknown $description
     * @param unknown $remark
     * @param unknown $typ
     */
    public function _log_operation($description,$remark='',$typ=1){
    	$this->load->helper('common');
    	$admin_profiler = $this->session->userdata('admin_profile');
    	$params['inter_id']       = $admin_profiler['inter_id'];
    	$params['description']    = $description;
    	$params['remark']         = $remark;
    	$params['typ']            = $typ;
    	$params['admin_id']       = $admin_profiler['admin_id'];
    	$params['admin_username'] = $admin_profiler['username'];
    	$params['create_time']    = date("Y-m-d H:i:s");
    	$params['remote_ip']      = getIp();
    	return $this->_db('iwide_rw')->insert('welfare_logs',$params);
    }
    /**
     * @todo 取公众号支付参数
     * @param string 公众号唯一识别码
     * @param string 酒店ID号
     * @return Query result 支付参数集
     */
    function get_account_confg($inter_id,$hotel_id = NULL){
    	$where = array('inter_id'=>$inter_id);
    	if(!empty($hotel_id)){
    		$where['hotel_id'] = $hotel_id;
    	}
    	$this->_db('iwide_r1')->where($where);
    	$query  = $this->_db('iwide_r1')->get('pay_params')->result();
    	$result = array ();
    	foreach ( $query as $item ) {
    		$result [$item->param_name] = $item->param_value;
    	}
    	return $result;
    }
    public function get_salers_send_log_by_day($inter_id,$date = NULL){
    	$sql = "SELECT inter_id,saler,SUM(`status`=1) counts,SUM(`status`=3) ecounts,SUM(IF(`status`=1,amount,0)) amounts,SUM(IF(`status`=3,amount,0)) eamounts FROM iwide_welfare_order_items WHERE inter_id=? AND (status=1 OR status=3) ";
    	$params[] = array($inter_id);
    	if(!empty($date)){
    		$sql .= " AND send_time>=? AND send_time<?";
    		$params[] = $date;
    		$params[] = date('Y-m-d H:i:s',strtotime('+1 day',strtotime($date)));
    	}
    	$sql .= ' GROUP BY inter_id,saler ORDER BY send_time DESC';
    	return $this->_db('iwide_r1')->query($sql,$params);
    }
    public function get_salers_send_log_day_array($inter_id,$date = NULL){
    	$res = $this->get_salers_send_log_by_day($inter_id,$date);
    	$arr = array();
    	foreach ($res->result() as $item){
    		$arr[$item->saler] = $item;
    	}
    	return $arr;
    }
    public function get_saler_send_log_by_time($begin_time = NULL,$end_time = NULL){
    	$sql = "SELECT inter_id,saler,SUM(`status`=1) counts,SUM(`status`=3) ecounts,SUM(IF(`status`=1,amount,0)) amounts,SUM(IF(`status`=3,amount,0)) eamounts FROM iwide_welfare_order_items GROUP BY inter_id,saler ORDER BY send_time DESC";
    }

    public function get_send_logs($params = array(),$limit = NULL,$offset=0){
        $sql = "SELECT o.admin_id,o.admin_username,o.batch_no,o.title,o.remote_ip,o.create_time,i.saler,i.amount,i.`status`,i.out_trade_no,i.send_time,i.typ,i.rel_inter_id,i.rec_content,i.remark,s.`name`,s.business,s.master_dept,s.hotel_id,s.hotel_name,s.cellphone,s.`status` saler_status,i.remark FROM iwide_welfare_orders o LEFT JOIN iwide_welfare_order_items i ON o.inter_id=i.inter_id AND o.id=i.oid LEFT JOIN iwide_hotel_staff s ON i.saler=s.qrcode_id AND i.inter_id=s.inter_id WHERE o.inter_id=? AND NOT ISNULL(i.id)";
        if(!isset($params['inter_id'])){
            $admin_profiler = $this->session->userdata ( 'admin_profile' );
            $params['inter_id'] = $admin_profiler['inter_id']; 
        }
        $avgs[] = $params['inter_id'];
        if(!empty($params['btime'])){
            $sql .= " AND i.send_time>=?";
            $avgs[] = $params['btime'];
        }
        if(!empty($params['etime'])){
            $sql .= " AND i.send_time<=?";
            $avgs[] = $params['etime'].' 23:59:59';
        }
        if(!empty($params['saler_name'])){
            $sql .= "AND s.`name` LIKE ?";
            $avgs[] = "%{$params['saler_name']}%";
        }
        if(!empty($params['saler_no'])){
            $sql .= " AND i.saler=?";
            $avgs[] = $params['saler_no'];
        }
        if(!empty($params['status'])){
            $sql .= " AND i.status=?";
            $avgs[] = $params['status'];
        }
        if(!empty($params['hotel'])){
            $sql .= " AND s.hotel_name LIKE ?";
            $avgs[] = "%{$params['hotel']}%";
        }
        if(!empty($params['dept'])){
            $sql .= " AND s.master_dept=?";
            $avgs[] = $params['dept'];
        }
        $sql .= ' ORDER BY i.send_time DESC';
        if(!empty($limit)){
            $sql .= " LIMIT ?,?";
            $avgs[] = $offset;
            $avgs[] = $limit;
        }
        return $this->_db('iwide_r1')->query($sql,$avgs);
//         $query = $this->_db('iwide_rw')->query($sql,$avgs);
//         echo $this->_db('iwide_rw')->last_query();
//         return $query;
    }

    public function get_send_logs_count($params = array()){
        $sql = "SELECT COUNT(i.id) counts FROM iwide_welfare_orders o LEFT JOIN iwide_welfare_order_items i ON o.inter_id=i.inter_id AND o.id=i.oid LEFT JOIN iwide_hotel_staff s ON i.saler=s.qrcode_id AND i.inter_id=s.inter_id WHERE o.inter_id=? AND NOT ISNULL(i.id)";
        if(!isset($params['inter_id'])){
            $admin_profiler = $this->session->userdata ( 'admin_profile' );
            $params['inter_id'] = $admin_profiler['inter_id'];
        }
        $avgs[] = $params['inter_id'];
        if(!empty($params['btime'])){
            $sql .= " AND i.send_time>=?";
            $avgs[] = $params['btime'];
        }
        if(!empty($params['etime'])){
            $sql .= " AND i.send_time<=?";
            $avgs[] = $params['etime'].' 23:59:59';
        }
        if(!empty($params['saler_name'])){
            $sql .= "AND s.`name` LIKE ?";
            $avgs[] = "%{$params['saler_name']}%";
        }
        if(!empty($params['saler_no'])){
            $sql .= " AND i.saler=?";
            $avgs[] = $params['saler_no'];
        }
        if(!empty($params['status'])){
            $sql .= " AND i.status=?";
            $avgs[] = $params['status'];
        }
        if(!empty($params['hotel'])){
            $sql .= " AND s.hotel_name LIKE ?";
            $avgs[] = "%{$params['hotel']}%";
        }
        if(!empty($params['dept'])){
            $sql .= " AND s.master_dept=?";
            $avgs[] = $params['dept'];
        }
        $query = $this->_db('iwide_r1')->query($sql,$avgs)->row();
        return is_null($query->counts) ? 0 : $query->counts;
    }

    /**
     * 分销业绩企业支付发放
     * @param string openid
     * @param int 发放金额（单位：分）
     * @param string 公众号识别码
     * @param string 绩效ID
     * @param int 分销号
     * @param string 发放描述
     * @return string[]|unknown[]|Query[]|string[]
     */
    function company_pay($openid,$amount,$inter_id,$desc=null){
//     	return array ( 'errmsg' => 'ok', 'partner_trade_no' => '11111111122222', 'send_content'=>'send_content','rec_content'=>'rec_content', 'ckres' => 'ckres');
    	$this->load->model ( 'wx/publics_model' );
    	$public_info = $this->publics_model->get_public_by_id ( $inter_id );
    	$account_info = $this->get_account_confg ( $inter_id );
    	if (! empty ( $public_info ) && ! empty ( $account_info )) {
    		// 收款账号与支付账号分开
    		if (isset ( $account_info ['pay_key'] )) {
    			$account_info ['key'] = $account_info ['pay_key'];
    		}
    		if (isset ( $account_info ['pay_mch_id'] )) {
    			$account_info ['mch_id'] = $account_info ['pay_mch_id'];
    		}
    		$this->load->helper ( 'common' );
    		$partner_trade_no = '202' . date ( 'Ymd' ) . substr ( time (), 3 ) . mt_rand ( 100, 999 );
    		$this->load->model ( 'pay/wxpay_model' );
    		$arr = array (
    				'mch_appid'        => $public_info ['app_id'],
    				'mchid'            => $account_info ['mch_id'],
    				'nonce_str'        => createNoncestr (),
    				'partner_trade_no' => $partner_trade_no,
    				'openid'           => $openid,
    				'check_name'       => 'NO_CHECK',
    				'amount'           => $amount,
    				'spbill_create_ip' => $_SERVER ["REMOTE_ADDR"],
    				'desc'             => empty ( $desc ) ? $public_info ['name'] . '付款' : $desc
    		);
    		$public_app_id = $public_info ['app_id'];
    		if (isset ( $account_info ['pay_app_id'] ) && $account_info ['pay_app_id'] != $public_app_id) {
    			$arr ['mch_appid'] = $account_info ['pay_app_id'];
    			$public_info ['app_id'] = $account_info ['pay_app_id'];
    			unset ( $arr ['openid'] );
    			$arr ['sub_openid'] = $openid;
    		}
    		$arr ['sign'] = $this->wxpay_model->getSign ( $arr, array ( 'key' => $account_info ['key'], 'app_id' => $public_info ['app_id'] ) );
    		$extras = array ();
    		$extras ['CURLOPT_CAINFO']  = realpath ( '../certs' ) . DS . "rootca_" . $account_info ['mch_id'] . ".pem";
    		$extras ['CURLOPT_SSLCERT'] = realpath ( '../certs' ) . DS . "apiclient_cert_" . $account_info ['mch_id'] . '.pem';
    		$extras ['CURLOPT_SSLKEY']  = realpath ( '../certs' ) . DS . "apiclient_key_" . $account_info ['mch_id'] . '.pem';
    		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
    		$send_content = $this->wxpay_model->arrayToXml ( $arr );
    		$result = doCurlPostRequest ( $url, $send_content, $extras );
    		log_message('error', '福利发放 | '.$send_content.' | '.$result);
    		$data = json_decode ( json_encode ( simplexml_load_string ( $result, NULL, LIBXML_NOCDATA ) ), true );
    		if ($data ['return_code'] == 'SUCCESS' && $data ['result_code'] == 'SUCCESS') {
    			return array ( 'errmsg' => 'ok', 'partner_trade_no' => $arr ['partner_trade_no'], 'send_content'=>$send_content,'rec_content'=>$result );
    		} else {
    			$account_info ['mch_appid'] = $public_info ['app_id'];
    			$check_res = $this->check_company_pay ( $partner_trade_no, $account_info );
    			if($check_res['errmsg'] == 'ok'){
    				return array ( 'errmsg' => 'ok', 'partner_trade_no' => $arr ['partner_trade_no'], 'send_content'=>$send_content,'rec_content'=>$result, 'ckres' => $check_res['send_contene'].'-->'.$check_res['result'] );
    			}else if($check_res['errmsg'] == 'failed'){
    				return array ( 'errmsg' => 'failed', 'partner_trade_no' => $arr ['partner_trade_no'], 'send_content'=>$send_content,'rec_content'=>$result, 'ckres' => $check_res['send_contene'].'-->'.$check_res['result'] );
    			}else{//异常发放记录
    				return array ( 'errmsg' => 'error', 'partner_trade_no' => $arr ['partner_trade_no'], 'send_content'=>$send_content,'rec_content'=>$result, 'ckres' => $check_res['send_contene'].'-->'.$check_res['result'] );
    			}
    		}
    	} else {
    		return array ( 'errmsg' => 'faild','rec_content' => 'PARAM_ERROR' );
    	}
    }
    /**
     * @todo 支付结果主动查询
     * @param string 商户单号
     * @param array 商户支付参数
     * @return string[]|请求成功返回成功结构，否则返回FALSE[]
     */
    function check_company_pay($trade_no,$paras){
    	$this->load->helper ( 'common' );
    	$arr ['appid']            = $paras ['mch_appid'];
    	$arr ['mch_id']           = $paras ['mch_id'];
    	$arr ['nonce_str']        = createNoncestr ();
    	$arr ['partner_trade_no'] = $trade_no;
    
    	$this->load->model ( 'pay/wxpay_model' );
    	$arr ['sign'] = $this->wxpay_model->getSign ( $arr, array ('key' => $paras ['key'],'app_id' => $arr ['appid'] ) );
    	$extras = array ();
    	$extras['CURLOPT_CAINFO'] = realpath('../').DS."certs".DS."rootca_".$paras['mch_id'].".pem";
    	$extras['CURLOPT_SSLCERT'] = realpath('../').DS."certs".DS."apiclient_cert_".$paras['mch_id'].".pem";
    	$extras['CURLOPT_SSLKEY'] = realpath('../').DS."certs".DS."apiclient_key_".$paras['mch_id'].".pem";
    	$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo';
    	$xml = $this->wxpay_model->arrayToXml ( $arr );
    	$result = doCurlPostRequest ( $url, $xml, $extras );
    	log_message('error', '福利发放校验 | '.$xml.' | '.$result);
    	$data = json_decode ( json_encode ( simplexml_load_string ( $result, NULL, LIBXML_NOCDATA ) ), true );
    	if ($data ['return_code'] == 'SUCCESS' && $data ['result_code'] == 'SUCCESS' && $data ['status'] == 'SUCCESS') {
    		return array ('errmsg' => 'ok','result' => $result ,'send_contene'=>$xml);
    	} else if ($data ['return_code'] == 'FAILED') {
    		return array ('errmsg' => 'failed','result' => $result,'send_contene'=>$xml );
    	} else {
    		return array ('errmsg' => 'wrong','result' => $result,'send_contene'=>$xml);
    	}
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
    public function set_redis_key_status($key = 'CONTINUE_DELIVER',$val = 'false'){
    	$cache= $this->_load_cache();
    	$redis= $cache->redis->redis_instance();
    	return $redis->set( $key , $val);
    }
}
