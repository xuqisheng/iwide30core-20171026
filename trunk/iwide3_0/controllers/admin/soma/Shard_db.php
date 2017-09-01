<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shard_db extends MY_Admin_Soma {

    /**
     * 初始化分片数据，调用格式：/soma/shard_db/init_config?id=all&shard_id=1
     */
    public function init_config()
    {
        $inter_id= $this->input->get('id');
        $shard_id= $this->input->get('shard_id');
	    $this->load->model('wx/publics_model', 'publics');
	    $this->load->model('soma/shard_config_model', 'shard');

	    if( !$shard_id ) die('不能缺少 shard_id 参数。');
	    //生成表
	    $this->shard->init_shard_table($shard_id);
	    
	    if($inter_id && strlen($inter_id)==10 ){
	        $this->shard->modify_shard_data($inter_id, $shard_id);
	        //echo 'update inter_id '. $inter_id;
	        
	    } elseif( $inter_id=='all' ){
	        $publics = $this->publics->get_public();
	        //print_r($publics);die;
	        	  
	        foreach ($publics as $v){
	            $this->shard->modify_shard_data($v->inter_id, $shard_id);
	        }
	        echo 'update all inter_id ';
	        
	    } else {
	        echo 'inter fromat error.';
	    }
    }
    
}
