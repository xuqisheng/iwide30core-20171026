<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Club_list_model extends MY_Model {

    const TAB_CLUB_LIST = 'club_list';

	public function get_resource_name()
	{
		return 'Club_list_model';
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function table_name()
	{
		return 'club_list';
	}

	public function table_primary_key()
	{
	    return 'club_id';
	}

	public function attribute_labels()
	{
		return array(
		'club_id'=> '编号',
		'club_name'=> '社群客名称',
		'inter_id'=> '公众号',
        'limited_amount'=>'限制人数',
        'amount'=>'已加入成员数',
		'status'=> '状态',
		'create_time'=> '添加时间',
        'update_time'=> '通过时间',
        'remark'=>'备注',
        'valid_time'=> '有效期',
        'id'=> '分销号',
        'price_code'=> '价格代码',
        'hotel_id'=> '适用酒店',
         'openid'=> 'openid',
         'img_url'=> '二维码',
         'club_code'=> '字符串',
         'qrcode_id'=>'二维码ID'

		);
	}
	public function list_table_fields()
	{
		return array(
		'club_id'=> '',
		'club_name'=> '',
        'limited_amount'=>'',
        'amount'=>'',
        'valid_time'=> '',
        'id'=> '',
        'price_code'=> '',
        'hotel_id'=> '',
        'qrcode_id'=> '',
         'update_time'=> '',
         'remark'=> ''
		);
	}

	/**
	 * 后台管理的表格中要显示哪些字段
	 */
	public function grid_fields()
	{
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
	    return array(
		'club_id',
		'club_name',
        'limited_amount',
		'valid_time',
        'price_code',
        'hotel_id',
        'id',
        'status',
        'qrcode_id',
        'amount',
        'update_time',
        'remark',
//         'openid'
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

        $inter_id = $this->session->get_admin_inter_id ();

        $this->load->model('club/Clubs_model','Clubs_model');
        $this->load->model('hotel/Hotel_model','Hotel_model');

        $allhotels = $this->Hotel_model->get_all_hotels($inter_id,1);

        $hotels=array();
        $hotels[0]='全部酒店';

        if($allhotels){
            foreach($allhotels as $arr){
                $hotels[$arr['hotel_id']]=$arr['name'];
            }
        }


        $price_name=$this->Clubs_model->get_all_price_codes($inter_id);

        if($price_name){
            foreach($price_name as $key=>$arr){
                $price[$key]=$arr['price_name'];
            }
            for($i=0;$i<=100;$i++){
                if(empty($price[$i])){
                    $price[$i]='价格代码无效';
                }
            }
        }else{
            for($i=0;$i<=100;$i++){
                    $price[$i]='';
            }
        }


        $saler = $this->get_all_hotel_staff($inter_id);



	    return array(
                 'club_id' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '10%',
                    //'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
                    'form_hide'=> TRUE,
                    //'function'=> 'show_price_prefix|￥',
//                     'select'=>$this->getCompanyName(),
//                     'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                ),
            'openid' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
//                     'select'=>$this->getCompanyName(),
//                     'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
            ),
            'valid_time' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
//                     'select'=>$this->getCompanyName(),
//                     'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
            ),
            'id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'select'=>$saler,
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
            ),
                'club_name' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '10%',
                    'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
//                    'form_hide'=> TRUE,
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
                                          'limited_amount' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '10%',
                    //'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
//                    'form_hide'=> TRUE,
                    //'function'=> 'show_price_prefix|￥',
                    'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),
                                        'status' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '10%',
                    //'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
//                    'form_hide'=> TRUE,
                    'select'=>array('1'=>'通过','2'=>'不通过','0'=>'审核中','3'=>'失效'),
                    //'function'=> 'show_price_prefix|￥',
                    'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
                ),
                                        'create_time' => array(
                    'grid_ui'=> '',
                    'grid_width'=> '10%',
                    //'form_ui'=> ' disabled ',
                    //'form_default'=> '0',
                    //'form_tips'=> '注意事项',
                    'form_hide'=> TRUE,
                    //'function'=> 'show_price_prefix|￥',
                    'type'=>'text',	//textarea|text|combobox|number|email|url|price
                ),

            'price_code' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
//                'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'select'=>$price,
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
            ),

            'hotel_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'select'=>$hotels,
                'type'=>'combobox',	//textarea|text|combobox|number|email|url|price
            ),

            'img_url' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),

            'club_code' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),

            'amount' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
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

            'qrcode_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> TRUE,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),

            'remark' => array(
                'grid_ui'=> '',
                'grid_width'=> '10%',
                //'form_ui'=> ' disabled ',
                //'form_default'=> '0',
                //'form_tips'=> '注意事项',
                'form_hide'=> false,
                //'function'=> 'show_price_prefix|￥',
                'type'=>'text',	//textarea|text|combobox|number|email|url|price
            ),
            	    );


	}

	/**
	 * grid表格中默认哪个字段排序，排序方向
	 */
	public static function default_sort_field()
	{
	    return array('field'=>'status', 'sort'=>'DESC');
	}

	/* 以上为AdminLTE 后台UI输出配置函数 */


//* 批量通过审核未通过人员
//*
//* @param unknown $inter_id
//* @return string[]|number[]
//*/
    function batch_auth($inter_id){

        $db_read = $this->load->database('iwide_r1',true);

        $db_read->where(array('inter_id'=>$inter_id,'status'=>0));
        $query = $db_read->get('club_list');
        $res = $query->result_array();
        if($query->num_rows() > 0){
            $success_count = 0;
            foreach ($res as $club){
                if($club['status'] == 0){
                    $this->ensure_club($inter_id, $club['club_id']);//统一审核方法
                }
                $success_count ++;
            }
            $keys = array_column($res, 'id');
            return array('errmsg'=>'ok','success'=>$success_count);
        }else{
            return array('errmsg'=>'ok','success'=>0);
        }
    }

    function ensure_club($inter_id,$club_id){
        $this->db->where(array('inter_id'=>$inter_id,'club_id'=>$club_id));
    	$res = $this->db->update('club_list',array('status'=>1,'update_time'=>date("Y-m-d H:i:s")));
        if($res){
            $club_info = $this->get_club_by_id($inter_id,$club_id);
            if($club_info){
                $this->load->model ( 'plugins/Template_msg_model' );
                $params=array(
                    'openid'=>$club_info['openid'],
                    'keyword2'=>date('Y-m-d H:i:s',time())
                );
                $this->Template_msg_model->hotel_club_templates ($inter_id,$params ,'hotel_club_auth' );
            }
        }

        return $res;
    }
    
	function search_staff($inter_id,$keyword) {
        $db_read = $this->load->database('iwide_r1',true);

		$sql="SELECT * FROM `iwide_hotel_staff` WHERE inter_id = ? and ( qrcode_id=? or name like ? ) and qrcode_id !='' and qrcode_id is not NULL and openid !='' and openid is not NULL";
		return $db_read->query($sql,array($inter_id,$keyword,"%$keyword%"))->result_array();
	}
	
	function get_club_by_id($inter_id,$club_id,$format=TRUE){
		$db =$this->load->database('iwide_r1',true);;
		$db->where(array('inter_id'=>$inter_id,'club_id'=>$club_id));
		$result=$db->get('club_list')->row_array();
		if (!empty($result)&&$format){
			$result['valid_times']=empty($result['valid_time'])?array('',''):explode('-', $result['valid_time']);
            $db->where(array('qrcode_id'=>$result['id'],'inter_id'=>$inter_id));
			$staff=$db->get('hotel_staff')->row_array();
			$result['staff_name']=empty($staff)?'':$staff['name'];
		}
		return $result;
	}
	
	function hotels_check($inter_id, $club_hotel_id = array(), $hotel_ids = NULL) {
		$this->load->model ( 'hotel/Hotel_model' );
		$hotel_check=array();
		if (! empty ( $hotel_ids )) {
			$hotels = $this->Hotel_model->get_hotel_by_ids ( $inter_id, $hotel_ids, 1, 'key' );
		} else {
			$hotels = $this->Hotel_model->get_all_hotels ( $inter_id, 1, 'key' );
		}
	
		if ( ! empty ( $hotels )) {
			foreach ( $hotels as $hotel_id => $hr ) {
				$hotel_check [$hotel_id]['name']=$hr['name'];
				if ( !empty($club_hotel_id)&&in_array ( $hotel_id, $club_hotel_id )) {
					$hotel_check [$hotel_id] ['check'] = 1;
				}else {
					$hotel_check [$hotel_id] ['check'] = 0;
				}
			}
		}
		return $hotel_check;
	}
	
	function save_club($inter_id,$club_id,$updata) {
		$db=$this->db;
		$db->where(array(
				'inter_id'=>$inter_id,
				'club_id'=>$club_id
		));
		$result=$db->update('club_list',$updata);
		if ($result&&$db->affected_rows()>0){
			$db->where ( array (
					'inter_id' => $inter_id,
					'club_id' => $club_id
			) );
			$result=$db->update ( 'club_list', array('update_time'=>date ( 'Y-m-d H:i:s' )) );
		}
		return $result;
	}
	
	function add_club($inter_id,$data){
		$db = $this->db;
		$data ['inter_id'] = $inter_id;
		$data ['create_time'] = date ( 'Y-m-d H:i:s' );
		$result=$db->insert ( 'club_list', $data );
		$club_id=$db->insert_id();
		$this->ensure_club($inter_id, $club_id);
		return $result;
	}


    function update_club_staff_amount($inter_id,$qrcode_id,$func = 'add'){

        $db = $this->db;

        if($func=='add'){
            $amount = 'amount+1';
        }else{
            $amount = 'amount-1';
        }

        $res=$db->query("UPDATE `iwide_club_staff` SET amount={$amount} WHERE qrcode_id={$qrcode_id} AND inter_id='{$inter_id}'");

        return $res;
    }


    function get_all_hotel_staff($inter_id){
        $db = $this->load->database('iwide_r1',true);
        $db->where(array('inter_id'=>$inter_id));
        $result=$db->get('hotel_staff')->result_array();
        if($result){
            $res = array();
            foreach($result as $arr){
//                $res[$arr['qrcode_id']]=$arr['qrcode_id'].'/'.$arr['name'].'/'.$arr['cellphone'];
                $res[$arr['qrcode_id']]=$arr['name'];
            }
            $result = $res;
        }

        return $result;
    }


    function get_salers($inter_id,$status=2,$is_club=1,$qrcode_id=''){

        $db = $this->load->database('iwide_r1',true);

        $db->where(array('inter_id'=>$inter_id));
        $db->where(array('status'=>$status));
        $db->where(array('is_club'=>$is_club));

        if(!empty($qrcode_id)){
            $db->where(array('qrcode_id'=>$qrcode_id));
            return $db->get('hotel_staff')->row_array();
        }

        $result=$db->get('hotel_staff')->result_array();

        return $result;
    }


    function update_saler($inter_id,$club_id,$saler_id,$qrcode_id,$openid) {

        $db=$this->db;

        $db->where(array(
            'inter_id'=>$inter_id,
            'club_id'=>$club_id
        ));

        $result=$db->update ( 'club_list', array('id'=>$saler_id,'openid'=>$openid) );

        if($result && $qrcode_id!=$saler_id){

            $saler = $this->getHotelStaffByQrcode($inter_id,$qrcode_id);
            $new_saler = $this->getHotelStaffByQrcode($inter_id,$saler_id);

            $this->load->model ( 'hotel/Hotel_log_model' );
            $this->Hotel_log_model->add_admin_log ( 'change_saler#' . $club_id, 'add', $saler['name'].'('.$qrcode_id.')'.','.$new_saler['name'].'('.$saler_id.')' );
        }

        return $result;

    }


    function getHotelStaffByQrcode($inter_id,$qrcode_id,$status=2) {    //酒店员工信息

        $db = $this->load->database('iwide_r1',true);

        $db->where ( 'inter_id', $inter_id );
        $db->where ( 'qrcode_id', $qrcode_id );
        $db->where ( 'status', $status );
        $result = $db->get ( 'hotel_staff')->row_array ();

        return $result;
    }


    function getStaffPriceCode($inter_id,$qrcode_id,$status=1){

        $db=$this->load->database('iwide_r1',true);

        $db->where ( 'inter_id', $inter_id );
        $db->where ( 'qrcode_id', $qrcode_id );
        $db->where ( 'status', $status );
        $result = $db->get ( 'club_staff')->row_array ();

        return $result;

    }


    function addClubStaffAmount($inter_id,$qrcode_id){

        $db=$this->db;

        $sql ="UPDATE `iwide_club_staff` set amount = amount + 1 WHERE inter_id = '{$inter_id}' and qrcode_id = '{$qrcode_id}'";

        return $db->query($sql);

    }

    function reduceClubStaffAmount($inter_id,$qrcode_id){

        $db=$this->db;

        $sql ="UPDATE `iwide_club_staff` set amount = amount - 1 WHERE inter_id = '{$inter_id}' and qrcode_id = '{$qrcode_id}'";

        return $db->query($sql);

    }


    function getAllClubList($inter_id,$condition=''){

        $db=$this->db;

        $sql = "SELECT
                    club_id,club_name,limited_amount,valid_time,price_code,hotel_id,id,status,qrcode_id,amount,update_time,remark,soma_code
              FROM
                    `iwide_club_list`
              WHERE
                    inter_id = '{$inter_id}'".$condition;

        return $db->query($sql)->result_array ();

    }


}
