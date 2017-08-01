<?php
class Club_model extends MY_Model {
	function __construct() {
		parent::__construct ();
	}
	const TAB_CLUB_MEMBER = 'iwide_club_staff';
    const TAB_HOTEL_STA = 'iwide_hotel_staff';
    const TAB_CLUB_LIST = 'iwide_club_list';
    const TAB_CLUB_CUSTOMER = 'iwide_club_customer';

	function _load_db() {
		return $this->db;
	}

    public function table_name()
    {
        return 'club_staff';
    }

    public function table_primary_key()
    {
        return 'qrcode_id';
    }

    public function attribute_labels()
    {
        return array(
            'name'=> '姓名',
            'qrcode_id'=> '分销号',
            'openid'=> '微信ID',
            'inter_id'=> '公众号',
            'status'=> '状态',
            'amount'=> '现开通数量',
            'limited_amount'=> '可开通数量',
            'create_time'=> '添加时间',
            'update_time'=> '修改时间',
            'club_price_code'=> '价格代码',
            'is_grade'=> '粉丝归属',

        );
    }

    /**
     * 后台管理的表格中要显示哪些字段
     */
    public function grid_fields()
    {
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
        return array(
            'qrcode_id',
            'name',
            'amount',
            'limited_amount',
            'status',
            'is_grade',
            'create_time',
        );
    }

    /**
     * 在EasyUI grid中的 date-option 定义，包括宽度，是否排序等等
     *   type: grid中的表头类型定义
     *   form_type: form中的元素类型定义
     *   form_ui: form中的属性补充定义，如加disabled 在< input “disabled” / > 使元素禁用
     *   form_tips: form中的label信息提示
     *   form_hide: form中自动化输出中剔除
     *   form_default: form中的默认值，请用字符类型，不要用数字
     *   select: form中的类型为 combobox时，定义其下来列表
     */
    public function attribute_ui()
    {
        /* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,checkbox,validatebox. */
        //type: numberbox数字框|combobox下拉框|text不写时默认|datebox
        $base_util= EA_base::inst();
        $modules= config_item('admin_panels')? config_item('admin_panels'): array();


        $status = array (
            '0' => '审核中',
            '1' => '有效',
            '2' => '失效'
        );

        $grade = array (
            '0' => '关',
            '1' => '开',
        );


        return array(
            'limited_amount' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
//                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
//                'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'amount' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
//                'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'qrcode_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '20%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
//                'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'openid' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'inter_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'status' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'type' => 'combobox',
                'select' => $status,
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
//                'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
            ),
            'name' => array(
                'grid_ui'=> '',
                'grid_width'=> '20%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'create_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '30%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                //'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'update_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'club_price_code' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            'is_grade' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'select'=>$grade,
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
            ),
        );
    }


    public static function default_sort_field() {
        return array (
            'field' => 'club_staff_id',
            'sort' => 'desc'
        );
    }

    function check_club($inter_id, $openid,$qrcode_id) {    //检测社群客销售员是否存在
        $db =$this->load->database('iwide_r1',true);
        $db->where ( 'inter_id', $inter_id );
        $db->where ( 'qrcode_id', $qrcode_id );
        if(!empty($openid))$db->where ( 'openid', $openid );
        $result = $db->get ( self::TAB_CLUB_MEMBER )->row_array ();

        return $result;
    }


    function getOpenid($inter_id,$qrcode_id) {    //获取openid
        $db = $this->load->database('iwide_r1',true);
        $db->where ( 'inter_id', $inter_id );
        $db->where ( 'qrcode_id', $qrcode_id );
        $result = $db->get ( self::TAB_HOTEL_STA )->row_array ();

        return $result;
    }

    function add_club($data,$status='') {   //新增社群客
        $db = $this->_load_db ();
        $data ['create_time'] = date ( 'Y-m-d H:i:s' );
        $data ['update_time'] = date ( 'Y-m-d H:i:s' );
        $data ['status'] = $status;
        $result=$db->insert ( self::TAB_CLUB_MEMBER, $data );

        if ($result){
            $this->load->model('hotel/Hotel_log_model');
            unset($data ['inter_id']);
            unset($data ['create_time']);
            $this->Hotel_log_model->add_admin_log('club_staff#'.$data['qrcode_id'],'add',$data);
        }

        return $result;
    }


    function update_club($data, $updata) {   //更新社群客
        $db = $this->_load_db ();
        $db->where ($data );
        $result=$db->update ( self::TAB_CLUB_MEMBER, $updata );

        if ($result&&$db->affected_rows()>0){
            $db->where ( array (
                'inter_id' => $data['inter_id'],
                'qrcode_id' => $data['qrcode_id']
            ) );
            $result=$db->update ( 'iwide_club_staff', array('update_time'=>date ( 'Y-m-d H:i:s' )) );

            if(isset($data['openid']))$openid = $data['openid'];else $openid='';

            $check = $this->check_club ( $data['inter_id'],$openid, $data['qrcode_id']);

            $update_diff=array();
            foreach ($check as $k=>$c){
                if (isset($updata[$k])&&$check[$k]!=$updata[$k]){
                    $update_diff[$k]=array('old'=>$c,'new'=>$updata[$k]);
                }
            }
            $this->load->model('hotel/Hotel_log_model');
            $this->Hotel_log_model->add_admin_log('club_staff#'.$data['qrcode_id'],'edit',$update_diff);
        }

        return $result;

    }



    function getAllClub($inter_id,$con='') {  //公众号下所有的社群客销售员

        $db = $this->_load_db ();

        $sql="
              SELECT
                    t1.qrcode_id,t1.name,t1.limited_amount,t1.amount,t1.status,t1.is_grade,t1.create_time,t1.club_price_code,t1.auth_price_code,t1.soma_code
              FROM
                    `iwide_club_staff` as t1,
                    `iwide_hotel_staff` as t2
              WHERE
                    t1.inter_id = '{$inter_id}'
              AND
                    t2.inter_id = '{$inter_id}'
              AND
                    t1.qrcode_id = t2.qrcode_id
              ".$con;

        $result = $db->query ($sql)->result_array ();

        return $result;
    }


    function getSalerByClubQrcodeid($opneid,$inter_id){   //社群客发展的粉丝获取对应的分销员的信息

        $db = $this->load->database('iwide_r1',true);

        $sql="SELECT
                    t3.*
                from
                    `iwide_fans_subs` as t1,
                    `iwide_club_staff` as t2,
                    `iwide_hotel_staff` as t3
                WHERE
                    t1.openid='{$opneid}'
                AND
                    t1.inter_id='{$inter_id}'
                AND
                    t1.inter_id=t2.inter_id
                AND
                    t3.inter_id='{$inter_id}'
                AND
                    t1.source=t2.qrcode_id
                AND
                    t2.qrcode_id=t3.qrcode_id
                AND
                    t2.is_grade=1
                    ";

        $result = $db->query ($sql)->row_array ();

        return $result;
    }


    function checkGradeStatus($inter_id,$status=1){     //检查该公众号社群客粉丝归属
        $db = $this->load->database('iwide_r1',true);
        $db ->select('is_grade');
        $db->where ( 'inter_id', $inter_id );
        $result = $db->get ( self::TAB_CLUB_MEMBER )->row_array ();

        return $result['is_grade'];
    }



    function turn_grade($inter_id,$is_grade,$qrcode_id=''){

        $con = array();
        $con['inter_id'] = $inter_id;
        if(!empty($qrcode_id)){$con['qrcode_id']=$qrcode_id;};

//        $now_grade=$this->checkGradeStatus($inter_id);

//        if($is_grade==$now_grade){
//            return array('errmsg'=>'same','success'=>1);
//        }else{
            $this->db->where($con);
            $query=$this->db->update(self::TAB_CLUB_MEMBER,array('is_grade'=>$is_grade,'update_time'=>date("Y-m-d H:i:s")));
//        }

        if($query){
            $this->load->model('hotel/Hotel_log_model');
            $this->Hotel_log_model->add_admin_log('club_staff_grade','edit',$is_grade.'_'.$qrcode_id);
            return array('errmsg'=>'ok','success'=>1);
        }else{
            return array('errmsg'=>'fail','success'=>0);
        }
    }

	public function getClubInfoByClubId($club_id, $inter_id, $is_grade=1){

        $map=[
			'cl.club_id'=>(int)$club_id,
			'cl.inter_id'=>$inter_id,
            'cs.inter_id'=>$inter_id
		];

        if($is_grade==1)$map['cs.is_grade']=$is_grade;

		$row=$this->db->from('club_list cl')->join('club_staff cs','cs.qrcode_id=cl.id','inner')->where($map)->select('cl.club_name,cs.*')->get()->row_array();
		return $row;

    }


    /**
     * @param $inter_id
     * @param $openid
     * @return string
     * @author renshuai  <renshuai@mofly.cn>
     */
    function somaClub($inter_id, $openid){

        $info = array(
            'status' => 0,
            'message'=>'缺少参数',
            'soma_club'=>[]
        );

        if(empty($inter_id) ||  empty($openid)){
            return json_encode($info);
        }

        $where=array(
            'cc.inter_id'=>$inter_id,
            'cl.inter_id'=>$inter_id,
            'cc.openid'=>$openid,
            'cl.soma_code !='=>''
        );

        $result=$this->db->from('club_customer cc')->join('club_list cl','cc.club_id=cl.club_id','inner')->where($where)->get()->result_array();

        $info['status'] = 1;
        $info['message'] = '没有数据';

        if(!empty($result)){
            $soma_club = [];
            foreach($result as $arr){
                $temp['social'] =  array(
                    'id'=>$arr['club_id'],
                    'name'=>$arr['club_name']
                );
                $temp['somaPriceList'] = explode(',',$arr['soma_code']);
                $soma_club[] = $temp;
            }
            $info['status'] = 1;
            $info['message'] = '有社群客';
            $info['soma_club'] = $soma_club;
        }

        return json_encode($info);
    }


}