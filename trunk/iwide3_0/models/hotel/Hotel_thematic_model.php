<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Hotel_thematic_model extends MY_Model {
	function __construct() {
        parent::__construct ();
    }
    const TAB_TP = 'hotel_thematic_project';

    function _load_db() {
        return $this->db;
    }

    public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function table_name()
	{
		return self::TAB_TP;
	}
	
	public function table_primary_key()
	{
		return 'id';
	}

    function create_tp($data){

        $db = $this->_load_db ();

        return $db->insert ( self::TAB_TP, $data );

    }

	function get_list($condit=array(),$count = false){
        $db_read = $this->load->database('iwide_r1',true);

        $db_read->select ('*');
        $db_read->where ( 'status', 1 );
		$db_read->where ( 'inter_id', $condit['inter_id'] );

        if(isset($condit['nowtime'])){
            $db_read->where('start_time<=', $condit['nowtime']);
            $db_read->where('end_time>=', $condit['nowtime']);
        }

        $db_read->from ( self::TAB_TP);

		$db_read->order_by ( 'sort desc' );

        if($count){
            return $db_read->count_all_results ();
        }
        if(isset($condit['size']) && isset($condit['page'])){
            $db_read->limit($condit['size'], $condit['page']);
        }
        return $db_read->get ()->result_array ();
    }

    function get_row ( $inter_id,$id,$condit=array()){

        $db_read = $this->load->database('iwide_r1',true);

        $db_read->select ('*');

        $db_read->where('inter_id', $inter_id);
		$db_read->where('id', $id);

        if(isset($condit['nowtime'])){
            $db_read->where('start_time<=', $condit['nowtime']);
            $db_read->where('end_time>=', $condit['nowtime']);
        }

        if(isset($condit['status'])){
            $db_read->where('status', $condit['status']);
        }

        $db_read->from ( self::TAB_TP);

        return $db_read->get ()->row_array ();
    }

    //更新
    function update_data($inter_id,$id,$data){
    	$db = $this->_load_db ();
        $db->where('inter_id', $inter_id);
        $db->where('id', $id);
        $this->load->helper ( 'array' );
        $data = elements(array('act_name','act_intro','start_time','end_time','intro_img','status','pre_days','min_days','price_codes','hotelids','sort'), $data);
		return $db->update(self::TAB_TP,$data);
    }
}