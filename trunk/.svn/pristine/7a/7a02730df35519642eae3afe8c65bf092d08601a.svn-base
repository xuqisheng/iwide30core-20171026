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
class Pandistribution_model extends MY_Model_Member
{
    protected $table_member = 'iwide_distribution_member';//泛分销会员记录表


    public function get_member($inter_id,$open_id,$data){
        $where['inter_id'] = $inter_id;
        $where['open_id']   = $open_id;
        $where['member_info_id'] = $data['member_info_id'];
        $record = $this->_shard_db()->select('*')
            ->get_where($this->table_member, $where)
            ->row_array();
        if(empty($record))
            return false;
        else
            return $record;
    }

    //增加泛分销会员表
    /**
     * @param $inter_id
     * @param $data
     * @param int $status
     * @param int $notify
     * @return mixed
     */
    public function add_member($inter_id,$data,$status = 1 ,$notify = 0){
        $data = array(
            'inter_id' => $inter_id,
            'member_info_id'    => $data['member_info_id'],
            'open_id' => $data['open_id'],
            'createtime'=> date('Y-m-d H:i:s',time()),
            'status' => $status,
            'notify' => $notify
        );
        $this->_shard_db(true)->set($data)->insert($this->table_member);
        $last_id = $this->_shard_db(true)->insert_id();
        return $last_id;
    }

    //更新会员状态
    /**
     * @param $inter_id
     * @param $data
     * @param int $status
     * @param int $notify
     * @return mixed
     */
    public function update_member($inter_id,$data,$status = 0 ,$notify = 0){
        $where['inter_id'] = $inter_id;
        $where['open_id'] = $data['open_id'];
        $where['member_info_id'] = $data['member_info_id'];
        $setData = array(
            'status'    => $status,
            'notify'    => $notify,
            'last_update_time'=> date('Y-m-d H:i:s',time()),
        );
        $result = $this->_shard_db(true)->where($where)->set($setData)->update($this->table_member);
        return $result;
    }


    /**
     * 运行日志记录
     * @param String $content
     */
    public function write_log($content,$type,$dir_path='pan_distribution') {
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
