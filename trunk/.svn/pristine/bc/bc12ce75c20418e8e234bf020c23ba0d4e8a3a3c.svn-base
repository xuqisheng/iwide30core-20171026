<?php
/**
 * 分销绩效数据接口
 * @author John
 * @todo 分销模块接口
 * @package models\distribute
 */
Class Idistribute_model extends CI_Model{
	private $CI;
	private $_model;
	private $_distribution_model;
	private $_notice_model;
	private $_grades_model;
	private $_grades_ext_model;
	
	/**
	 * @todo 写入新员工分销绩效
	 * @param Array $params 写入新绩效时应该写入的数组<pre>Array {"inter_id":"公众号ID",
"hotel_id":"下单酒店ID",
"saler":"分销号，粉丝归属的分销号不用传，按次计算需要传",
"grade_openid":"粉丝openid",
"grade_table":"订房iwide_hotels_order，商城iwide_shp_orders，套票iwide_product_package_orders",
"grade_id":"记录产生绩效的表的主键值",
"grade_id_name":"记录产生绩效的表的ID名称",
"order_amount":"订单总金额（包含优惠折扣金额）",
"grade_total":"绩效总金额（默认-1）",
"grade_amount":"订单计算绩效部分的金额",
"status":"",
"grade_amount_rate":"绩效值/比例（grade_total等于-1，此字段可以不传）",
"grade_rate_type":"计算类型0：固定金额，1：比例（grade_total等于-1，此字段可以不传）",
"remark":"备注",
"product":"产品名称",
"order_status":"订单状态",
"fans_hotel":"粉丝所属酒店",
"hotel_rate":"酒店绩效规则值",
"group_rate":"集团绩效规则值",
"jfk_rate":"金房卡绩效规则值",
"hotel_grades":"酒店绩效金额",
"group_grades":"集团绩效金额",
"jfk_grades":"金房卡绩效金额",
"grade_typ":"分销来源类型,1|粉丝归属,2|分享绩效",
"order_id":"订单号(如果有PMS订单号传PMS订单号)"}</pre>更新数据时传入的数组跟上面的一样，但是只有<code>$inter_id,$grade_table,$grade_id,$grade_typ</code>为必填项，其他参数只需传入需要更新的参数,例如：<code>array('inter_id'=>'','grade_table'=>,'grade_id'=>'','order_status'=>'',"status"=>'',"grade_typ")</code><br /><p>'status'字段对应值：</p><pre>1 => '已核定－未发放',//交易成功（不论消费方式一律按7天核定奖励）、拼团成功（成团立即核定奖励）<br />2 => '已核定－已发放',
4 => '未核定－尚未离店',<br />5 => '已核定－无绩效',//发生退款（立即撤销奖励）<br />6 => '未核定-付款成功'<br />7 => '收益发放失败－余额不足',<br />8 => '收益发放失败－金额过小'<br />9 => '发放异常'</pre>
	 * @return boolean
	 */
	public function create_dist($params){
		try {
			return $this->get_distribute_model()->create_grade($params);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		return false;
	}
	
	/**
	 * 更新绩效状态为已核定
	 * @param string $inter_id 公众号ID
	 * @param string $grade_table 绩效类型
	 * @param string $grade_id 绩效订单项目编号
	 * @return boolean
	 */
	public function audit_grade($inter_id,$grade_table,$grade_id){
		try {
			return $this->get_grade_model()->audit_grade($inter_id,$grade_table,$grade_id);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		return false;
	}

	/**
	 * 取得一个绩效项的基础信息
	 * @param string $inter_id
	 * @param string $grade_table
	 * @param string $grade_id
	 * @param string $grade_typ
	 * @return boolean
	 */
	public function get_single_grade_base($inter_id,$grade_table,$grade_id,$grade_typ=1){
		try {
			return $this->get_grade_model()->get_single_grade_base($inter_id,$grade_table,$grade_id,$grade_typ);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		return false;
	}

	public function create_notice($params){
		try {
			return $this->get_dist_notice_model()->create_notice($params);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		return false;
	}
	public function delete_notice($params){
		try {
			return $this->get_dist_notice_model()->delete_notice($code,$value,$serialize,$inter_id);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		return false;
	}
	/**
	 * 将消息标记为已读
	 * @param string $msg_id 消息ID
	 */
	public function do_read_msg($msg_id){
		try {
			return $this->get_dist_notice_model()->do_read_msg($msg_id);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		return false;
	}
	/**
	 * 取未读消息数
	 * @param string $openid 用户OPENID
	 */
	public function get_my_new_msg_count($openid,$category = NULL,$top = NULL){
		try {
			return $this->get_dist_notice_model()->get_my_new_msg_count($openid,$category,$top);
		} catch (Exception $e) {
			log_message('error',$e->getMessage()); 
		}
		return 0; 
	}
	/**
	 * @todo 取openid下的系统消息
	 * @param string $openid 用户OPENID
	 * @param string $inter_id 公众号ID
	 * @param int $offset 起始位置
	 * @param int $limit 取的数量
	 */
	public function get_my_notices($openid,$inter_id,$offset = 0,$limit = 20){
		try {
			return $this->get_dist_notice_model()->get_my_notices($openid,$inter_id,$offset = 0,$limit = 20);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		return false;
	}
	/**
	 * @todo 取openid下的系统消息
	 * @param string $openid 用户OPENID
	 * @param string $inter_id 公众号ID
	 * @param int $offset 起始位置
	 * @param int $limit 取的数量
	 */
	public function get_top_notice($inter_id, $openid, $top=2,$limit=20){
		try {
			return $this->get_dist_notice_model()->get_top_notice($inter_id, $openid, $top,$limit);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		return false;
	}
	/**
	 * @todo 根据消息分类取openid下的系统消息
	 * @param string $openid
	 * @param string $inter_id 公众号ID
	 * @param int $category 消息分类 -1表示所有分类
	 * @param int $offset 起始位置
	 * @param int $limit 取的数量
	 */
	public function get_my_notices_by_category($openid,$inter_id,$category,$offset = 0,$limit = 20){
		try {
			return $this->get_dist_notice_model()->get_my_notices_by_category($openid,$inter_id,$category,$offset = 0,$limit = 20);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		return false;
	}
	/**
	 * @todo 取单条系统消息
	 * @param string $nc_id 消息ID
	 * @param string $inter_id 公众号ID
	 * @param string $openid 用户openid
	 */
	public function get_single_notice($nc_id,$inter_id,$openid = NULL){
		try {
			return $this->get_dist_notice_model()->get_single_notice($nc_id,$inter_id,$openid = NULL);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		return false;
	}
	
	/**
	 * @todo 取指定时间段绩效的总额
	 * @param string $inter_id
	 * @param int $saler 分销号
	 * @param string $date Y-m-d 不传进来则返回全部
	 * @param string $type NEW|已核定，未发放，OLD|已发放，ALL|已核定（包括未发放和已发放），PRE|未核定
	 * @return decimal | boolean
	 */
	public function get_saler_grades_by_date($inter_id,$saler,$date=NULL,$type='ALL'){
		try {
			return $this->get_grade_model()->get_saler_grades_by_date($inter_id,$saler,$date,$type);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		return false;
	}
	
	/**
	 * @todo 取分销员收益记录
	 * @param string $inter_id
	 * @param int $saler
	 * @param string $type
	 * @param int $offset
	 * @param int $limit
	 */
	public function get_saler_grades_logs($inter_id,$saler,$type='ALL',$offset=0,$limit=20){
		try {
			return $this->get_grade_model()->get_saler_grades_logs($inter_id,$saler,$type,$offset,$limit);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		return false;
	}
	
	/**
	 * 写入新的全员分销绩效记录，记录已经存在时只进行更新操作
	 * @param $params <pre>Array{inter_id      => 公众号唯一编号
								 hotel_id      => 下单酒店编号
								 saler         => 分销号
								 grade_openid  => 下单人openid
								 grade_table   => 绩效模块类型
								 grade_id      => 订单唯一编号，有子单号优先子单号，有PMS单号优先PMS单号
								 order_amount  => 订单金额
								 grade_total   => 绩效金额
								 grade_amount  => 参与绩效的金额
								 status        => 绩效状态
								 remark        => 备注
								 order_hotel   => 下单酒店
								 order_status  => 订单状态
								 order_time    => 下单时间
								 grade_rule    => 绩效规则
								 grade_typ     => 绩效类型,1:粉丝归属,2:按次
								 product_count => 商品数量
								 product       => 产品名称}</pre>
	 * @return boolean
	 */
	public function create_ext_grade($params){
		try {
			MYLOG::w('泛分销接收数据：' . json_encode($params), 'distribute_extends');
			return $this->get_distribute_ext_model()->create_dist($params);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		return false;
	}
	/**
	 * 更新全员分销绩效记录，只更新传过来的字段
	 * @param $params <pre>Array{inter_id      => 公众号唯一编号
								 hotel_id      => 下单酒店编号
								 saler         => 分销号
								 grade_openid  => 下单人openid
								 grade_table   => 绩效模块类型
								 grade_id      => 订单唯一编号，有子单号优先子单号，有PMS单号优先PMS单号
								 order_amount  => 订单金额
								 grade_total   => 绩效金额
								 grade_amount  => 参与绩效的金额
								 status        => 绩效状态
								 remark        => 备注
								 order_hotel   => 下单酒店
								 order_status  => 订单状态
								 order_time    => 下单时间
								 grade_rule    => 绩效规则
								 grade_typ     => 绩效类型,1:粉丝归属,2:按次
								 product_count => 商品数量
								 product       => 产品名称}</pre>
	 * @return boolean
	 */
	public function update_ext_grade($params){
		try {
			MYLOG::w('update泛分销接收数据：' . json_encode($params), 'distribute_extends');
			return $this->get_distribute_ext_model()->update_grades($params);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		return false;
	}
	
	/**
	 * 取得一个全员分销绩效项的基础信息
	 * @param string $inter_id
	 * @param string $grade_id
	 * @param string $grade_table
	 * @param string $grade_typ
	 * @return boolean
	 */
	public function is_grades_exist($inter_id,$grade_id,$grade_table,$grade_typ,$return_row_info = FALSE){
		try {
			return $this->get_distribute_ext_model()->is_grades_exist($inter_id,$grade_id,$grade_table,$grade_typ,$return_row_info);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		return false;
	}

	/**
	 * 检索指定OPENID的分销身份信息
	 * @todo 全民分销检索指定OPENID的分销身份信息
	 * @param string $inter_id 公众号唯一识别编号
	 * @param string $openid 粉丝OPENID
	 * @param string $rtn_JSON 是否返回JSON格式
	 * @return boolean 没有找到对应分销员的信息（传入的OPENID非分销员）返回FALSE,如果是全民分销的分销员返回<code>Array{'typ':'FANS','info':{'saler':'粉丝粉丝分销号','nickname':'','qrcode_url':''}}</code>,
	 * 如果是员工分销员身份返回<code>Array{'typ':'STAFF','info':{'saler':'员工分销号','nickname':'','qrcode_url':''}}</code>
	 */
	public function fans_is_saler($inter_id,$openid,$rtn_JSON = TRUE){
		try {
			return $this->get_distribute_ext_model()->check_fans($inter_id,$openid,$rtn_JSON);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
		return false;
	}
	
	/**
	 * 取分销保护状态配置
	 * @param string $inter_id 公众号inter_id
	 * @return Object <code>status->CLOSED|OPEN</code>
	 * <pre>
	 * 状态关闭时返回object(stdClass)[32]
	 *	  public 'status' => string 'CLOSED' (length=6)
	 *	  public 'protection_time' => int 0
	 * 状态开启时返回object(stdClass)[32]
	 *	  public 'status' => string 'OPEN' (length=6)
	 *	  public 'protection_time' => int 1503480263
	 * </pre>
	 */
	public function get_distribution_protection_config($inter_id){
		try {
			return $this->get_distribution_model()->get_distribution_protection_config($inter_id);
		}catch (Exception $e){
			log_message('error',$e->getMessage());
		}
	}
	/**
	 * 查询受保护的分销员
	 * @param string $openid 用户openid
	 * @param string $inter_id 公众号inter_id
	 * @return int 查询到受保护的分销员时返回分销员的分销号，查询没有结果则返回0
	 */
	public function get_protection_saler($openid,$inter_id = ''){
		try {
			return $this->get_distribution_model()->get_protection_saler($openid,$inter_id);
		}catch (Exception $e){
			log_message('error',$e->getMessage());
		}
	}
	/**
	 * 保存分销员分销保护源信息
	 *
	 * @param string $inter_id 公众号inter_id
	 * @param string $source_openid
	 *        	来源用户openid
	 * @param string $source
	 *        	来源链接
	 * @param int $saler
	 *        	分销号
	 * @param int $current_time
	 *        	受保护开始时间戳，默认当前时间戳
	 * @param string $module
	 *        	模块名称，默认为空
	 * @return boolean true|false
	 */
	public function save_saler_protection_info($inter_id, $source_openid, $source, $saler, $current_time = '', $module = ''){
		try {
			return $this->get_distribution_model()->save_saler_protection_info($inter_id,$source_openid,$source,$saler,$current_time,$module);
		}catch (Exception $e){
			log_message('error',$e->getMessage());
		}
	}
	
	private function get_distribute_model(){
		if(!isset($this->_model)){
			$this->load->model('distribute/grades_model');
			$this->_model = $this->grades_model;
		}
		return $this->_model;
	}
	private function get_distribute_ext_model(){
		if(!isset($this->_grades_ext_model)){
			$this->load->model('distribute/distribute_ext_model');
			$this->_grades_ext_model = $this->distribute_ext_model;
		}
		return $this->_grades_ext_model;
	}
	private function get_distribution_model(){
		if(!isset($this->_distribution_model)){
			$this->load->model('distribute/distribute_model');
			$this->_distribution_model = $this->distribute_model;
		}
		return $this->_distribution_model;
	}
	private function get_dist_notice_model(){
		if(!isset($this->_notice_model)){
			$this->load->model('distribute/distribute_notice_model');
			$this->_notice_model = $this->distribute_notice_model;
		}
		return $this->_notice_model;
	}
	private function get_grade_model(){
		if(!isset($this->_grades_model)){
			$this->load->model('distribute/grades_model');
			$this->_grades_model = $this->grades_model;
		}
		return $this->_grades_model;
	}
}