<?php
/**
 * 小程序
 *
 */
class Programs_model extends MY_Model {
	function __construct() {
        parent::__construct ();
    }
    const TAB_PROGRAMS = 'programs';

    function _load_db() {
        return $this->db;
    }

    public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function table_name()
	{
		return 'programs';
	}
	
	public function table_primary_key()
	{
		return 'pro_id';
	}

    function new_program($data){

        $db = $this->_load_db ();

        return $db->insert ( self::TAB_PROGRAMS, $data );

    }

	function get_list($condit=array(),$count = false){
        $db = $this->_load_db ();
        $select = ',';
        if(isset($condit['select']) && !empty($condit['select'])){
            $select .= $condit['select'];
        }
        $db->select ('pro_id,name,short_intro,intro,intro_img,hit,qrcode_img'.$select);
        if(!isset($condit['all'])){
    		$db->where ( 'status', 1 );
        }
        if(isset($condit['keywords']) && !empty($condit['keywords'])){
            $db->like ( 'name', $condit['keywords'] );
            $db->or_like ( 'short_intro', $condit['keywords'] );
            $db->or_like ( 'intro', $condit['keywords'] );
        }
        $db->from ( self::TAB_PROGRAMS);

        if(isset($condit['type']) && !empty($condit['type'])){
        	if($condit['type']=='hit'){//点击量
                $db->limit(10,0);
        		$db->order_by ( 'hit desc' );
        	}elseif($condit['type']=='time'){//时间
                $db->limit(10,0);
        		$db->order_by ( 'create_time desc' );
        	}elseif($condit['type']=='default'){//推荐
                $db->limit(10,0);
        		$db->order_by ( 'recommend desc' );
        	}else{
                $db->order_by ( 'recommend desc' );
            }
        }else{//推荐
    		$db->order_by ( 'recommend desc' );
    	}

        if($count){
            return $db->count_all_results ();
        }
        if(isset($condit['size']) && isset($condit['page'])){
            $db->limit($condit['size'], $condit['page']);
        }
        return $db->get ()->result_array ();
        // return $db->last_query();

    }

    function get_row($pro_id,$select=''){
        $db = $this->_load_db ();
        $db->select ('pro_id,name,short_intro,intro,intro_img,hit,qrcode_img,author,detail_img'.$select);

		$db->where('pro_id', $pro_id);

        $db->from ( self::TAB_PROGRAMS);

        return $db->get ()->row_array ();
        // return $db->last_query();

    }

    //更新点击量
    function update_hit($pro_id){
    	$db = $this->_load_db ();
    	$db->set('hit', 'hit+1', FALSE);
		$db->where('pro_id', $pro_id);
		return $db->update(self::TAB_PROGRAMS);
    }
	
}