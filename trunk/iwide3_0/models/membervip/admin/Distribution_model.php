<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SignIn_model
 *
 * @author vencelyang
 */
class Distribution_model extends MY_Model_Member
{
    protected $table_record = 'iwide_distribution_record';//分销发放记录表
    protected $table_rule= 'iwide_distribution_rule';//分销配置表

    protected $table_relation  = 'iwide_distribution_relation'; //分销关系表

    //过滤插入数据库的必填字段
    protected $relation_filed_array = array(
        'inter_id',
        'saler_id',
        'saler_openid',
        'fans_openid',
        'createtime',
        'updatetime',
        'source',
        'status'
    );

    //获取分销规则
    public function get_distribution_rule($inter_id , $type = 'reg' ,$status = ''){

        if(empty($inter_id)) return false;


        $where = array(
            'inter_id' => $inter_id,
            'rule_type' => $type,
        );

        if(!empty($status)) $where['status'] = $status;

        $rule_info = $this->_shard_db()->select('*')
            ->get_where($this->table_rule, $where)
            ->row_array();

        if($rule_info){
            return $rule_info;
        }else{
            return array();
        }


    }

    public function get_rule_by_id($inter_id,$rule_id){
        if(empty($inter_id) || empty($rule_id)) return false;

        $where = array(
            'inter_id' => $inter_id,
            'rule_id'    => $rule_id
        );
        $rule_info = $this->_shard_db()->select('*')
            ->get_where($this->table_rule, $where)
            ->row_array();

        if($rule_info){
            return $rule_info;
        }else{
            return array();
        }
    }

    /**
     * add Rule
     * @param $inter_id
     * @param $data = array(   'inter_id', 'reward', 'rule_title', 'status','rule_type' );
     * @return mixed
     */
    public function add_rule($inter_id,$data){
        $data = array(
            'inter_id' => $inter_id,
            'reward' => $data['reward'],
            'title' => $data['rule_title'],
            'status' => $data['status'],
            'rule_type' => $data['rule_type']
        );

        $this->_shard_db(true)->set($data)->insert($this->table_rule);
        $last_id = $this->_shard_db(true)->insert_id();
        return $last_id;
    }
    /**
     * Save/update Rule
     * @param $inter_id
     * @param $data
     * @return mixed
     */
    public function save_rule($inter_id,$data){
        $type= $data['rule_type'];
        $rule_id = $data['rule_id'];
        $data = array(
            'inter_id' => $inter_id,
            'reward' => $data['reward'],
            'title' => $data['rule_title'],
            'last_update_time' => Date("Y-m-d H:i:s",time()),
            'status' => $data['status']
        );
        $where = array(
            'inter_id' => $inter_id,
            'rule_type' => $type,
            'rule_id'   => $rule_id
        );
        $result = $this->_shard_db(true)->where($where)->set($data)->update($this->table_rule);
        return $result;
    }


    /**
     * @param $inter_id
     * @param $data
     * @return mixed
     */
    public function add_distribution_record($inter_id,$data){
        $type = $data['type'];
        $open_id = $data['open_id'];

        /*先检索有没有记录*/
        $where = array(
            'inter_id' => $inter_id,
            'open_id' => $open_id,
            'reward'    => $data['reward'],
            'type' => $type,
            'sn' => $data['sn']
        );
        $record = $this->_shard_db()->select('*')
            ->get_where($this->table_record, $where)
            ->row_array();
        if(!empty($record))
            return false;


        /*插入*/
        $insertData = array(
            'inter_id' => $inter_id,
            'open_id' => $open_id,
            'type' => $type,
            'record_title' => $data['record_title'],
            'reward' => $data['reward'],
            'sn' => $data['sn'],
            'status' => $data['status']
        );

        if( isset($data['sales_id']) && !empty($data['sales_id']))   $insertData['sales_id']  =  $data['sales_id'];
        if( isset($data['sales_name']) &&!empty($data['sales_name']))   $insertData['sales_name']  =  $data['sales_name'];
        if( isset($data['hotel_name']) &&!empty($data['hotel_name']))   $insertData['sales_hotel']  =  $data['hotel_name'];
        if( isset($data['hotel_id']) &&!empty($data['hotel_id']))   $insertData['hotel_id']  =  $data['hotel_id'];

        $this->_shard_db(true)->set($insertData)->insert($this->table_record);
        $last_id = $this->_shard_db(true)->insert_id();
        return $last_id;
    }

    /**
     * @param $record_id
     * @param $inter_id
     * @param $open_id
     * @param string $status
     * @return mixed
     */
    public function update_distribution_record($record_id,$inter_id,$open_id,$status = 't'){
        $updateData = array(
            'status' => $status,
            'last_update_time' => date("Y-m-d H:i:s",time())
        );
        $where = array(
            'inter_id' => $inter_id,
            'open_id'   => $open_id,
            'record_id'   => $record_id,
            'status'    => 'f'
        );
        $this->_shard_db(true)->where($where)->set($updateData)->update($this->table_record);
        $result = $this->_shard_db(true)->affected_rows();
        return $result;
    }


    /**
     * @param string $inter_id
     * @param string $type
     * @param string $status
     * @return array
     */
    public function get_distribution_list($inter_id='',$type='',$status='f'){
        if(!empty($inter_id)) $where['inter_id'] = $inter_id;
        if(!empty($type)) $where['type'] = $type;

        if(!empty($status)) $where['status'] = $status;

        $records = $this->_shard_db(true)->select('*')
            ->get_where($this->table_record, $where)
            ->result_array();

        if($records){
            return $records;
        }else{
            return array();
        }
    }


    /**
     * @param $inter_id
     * @param $openid
     * @param array $extra
     */
    public function check_distribution_relation($inter_id,$open_id,$extra = array()){
        $where = array(
            'inter_id' => $inter_id,
            'fans_openid'   => $open_id
        );
        if(!empty($extra) && is_array($extra)){
            foreach($extra as $key => $value){
                $where[$key]    = $value;
            }
        }
        $records = $this->_shard_db(true)->select('*')
            ->get_where($this->table_relation, $where)
            ->result_array();
        if($records){
            return $records;
        }else{
            return array();
        }
    }

    /**
     * @param $inter_id
     * @param $data(array)
     * @return bool
     */
    public function add_distribution_relation($inter_id,$data){
        if(empty($inter_id) || !is_array($data) || empty($data))
            return false;

        //过滤非数据库字段
        foreach($data as $key => $value){
            if(!in_array($key,$this->relation_filed_array)){
                unset($data[$key]);
            }
        }
        $data['createtime'] = date("Y-m-d H:i:s",time());
        if(!isset($data['inter_id'])) $data['inter_id'] = $inter_id;
        $this->_shard_db(true)->set($data)->insert($this->table_relation);
        $last_id = $this->_shard_db(true)->insert_id();
        return $last_id;
    }

    /**
     * @param $inter_id
     * @param $data
     */
    public function update_distribution_relation($inter_id,$setData,$where){
        if(!isset($where['fans_openid'])) return false;
        if(!isset($where['inter_id'])) $where['inter_id']  = $inter_id;



        $result = $this->_shard_db(true)->where($where)->set($setData)->update($this->table_relation);
        return $result;
    }


    /**
     * 运行日志记录
     * @param String $content
     */
    public function _write_log($content,$type,$dir_path='distribution') {
        if(is_array($content) || is_object($content))
            $content = json_encode($content);
        $file= date('Y-m-d_H'). '.txt';
        $path= APPPATH. 'logs'. DS. 'membervip'. DS. $dir_path. DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $ip= $this->input->ip_address();
        $fp = fopen( $path. $file, 'a');
        $content= "\n[". date('Y-m-d H:i:s'). '] [' . $ip. "] $type '". $content;
        fwrite($fp, $content);
        fclose($fp);
    }


}
