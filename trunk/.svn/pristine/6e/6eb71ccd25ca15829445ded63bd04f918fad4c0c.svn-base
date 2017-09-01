<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shard_config_model extends MY_Model_Soma {

    const DB_RESOURCE = 'iwide_soma';
    const TABLE_SUFFIX= '_1001';
    const SHARD_CONFIG_FILE = 'soma_shard_config.php';

    public function get_shard_ids()
    {
        $shard_db= $this->db_soma;  //define at MY_Model_Soma
        $shard= $this->_db( $shard_db )->get('soma_shard' )->result_array();
        $ids= array();
        foreach ($shard as $v){
            $ids[]= $v['shard_id'];
        }
        return $ids;
    }
    
    /**
     * 根据分片配置信息，初始化创建分片空表
     * @param String $shard_id
     * @return boolean
     */
    public function init_shard_table($shard_id)
    {
        $shard_db= $this->db_soma;  //define at MY_Model_Soma
        if($shard_id){
            $shard_con= $this->_shard_db_r('iwide_soma_r')->get_where('soma_shard', array('shard_id'=>$shard_id) )->result_array();
            //print_r($shard_con);die;
            if( isset($shard_con[0]) && count($shard_con[0])>0 ){
                //按照分片生成新表
                $table_prefix= $this->_db( $shard_db )->dbprefix;
                $table_gen= $this->shard_tables();
                //print_r($table_gen);die;
                foreach ( $table_gen as $k=> $v){
                    //循环生成各个表
                    $sql= "create table IF NOT EXISTS {$table_prefix}{$v}{$shard_con[0]['table_suffix']} like {$table_prefix}{$v};";
                    //echo $sql. "<br/>\n";
                    $this->_db( $shard_con[0]['db_resource'] )->query($sql);
                }
            }
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * 修改公众号对应的分片数据源
     * @param unknown $inter_id
     * @param string $shard_id
     * @return unknown|string
     */
    public function modify_shard_data($inter_id, $shard_id= '1')
    {
        $shard_db= $this->db_soma;  //define at MY_Model_Soma
        if($inter_id && strlen($inter_id)==10 ){
            $data = array(
                'inter_id' => $inter_id,
                'db_resource' => '*',
                'shard_id' => $shard_id
            );
            $result= $this->_db( $shard_db )->replace('soma_shard_link', $data);
            $this->bulid_shard_config_file();
            //echo $this->_db($shard_db)->last_query(). "<br/>/n";
            //print_r($result);die;
            return $result;
    
        } else
            return '';
    }
    public function reflesh_shard_data($inter_id, $shard_id= '1')
    {
        $shard_db= $this->db_soma;  //define at MY_Model_Soma
        if($inter_id && strlen($inter_id)==10 ){
            $data = array(
                'inter_id' => $inter_id,
                'db_resource' => '*',
            );
            $result= $this->_shard_db_r('iwide_soma_r')->get_where('soma_shard_link', $data)->result_array();
            if( !$result ){
                $this->_db( $shard_db )->insert('soma_shard_link', $data+ array('shard_id' => $shard_id) );
                $this->bulid_shard_config_file();
            }
            return $result;
        
        } else
            return '';
    }
    
    /**
     * 根据分片配置表生成公众号数据源配置数组，在父类控制器调用
     * @return Ambigous <multitype:, multitype:Ambigous <multitype:unknown > >
     * Array (
            [a429262687] => Array  (
                [*] => Array (
                    [db_resource] => iwide_soma
                    [table_suffix] => _1001
                )
        )
     */
    public function build_shard_config($inter_id=NULL)
    {
        $file = APPPATH. 'config'. DS. self::SHARD_CONFIG_FILE;
        if(!file_exists($file)) { 
            $this->bulid_shard_config_file();
        }

        $shard_config = include($file);

        if($inter_id===NULL){
            return $shard_config;
            
        } elseif( $inter_id && array_key_exists($inter_id, $shard_config) ){
            return $shard_config[$inter_id];
            
        } else {
            if(! array_key_exists($inter_id, $shard_config) ) {
                // 提供inter_id却没有找到配置信息，刷新一次配置文件
                $shard_config = $this->bulid_shard_config_file();
            }
            return array_key_exists($inter_id, $shard_config) ? $shard_config[$inter_id] : array();
        }

        return array();

        // 作废
        $shard_db= $this->db_soma;  //define at MY_Model_Soma
        $shard_config= $shard_setting= array();
        
        $data= $this->_shard_db_r('iwide_soma_r')->get('soma_shard')->result_array();
        foreach ($data as $k=>$v){
            $shard_setting[$v['shard_id']]= array(
                'db_resource'=> $v['db_resource'],
                'table_suffix'=> $v['table_suffix']
            );
        }
        /**
         * 获取分片配置信息
         * array(1=> array(
         *     'db_resource'=> 'iwide_soma', 
         *     'table_suffix'=> '_1001', 
         * ))
         */ 
        $data= $this->_shard_db_r('iwide_soma_r')->get('soma_shard_link')->result_array();
        foreach ($data as $k=>$v){
            if( isset($shard_setting[$v['shard_id']]) ){
                $shard_config[$v['inter_id']]= array(
                    '*'=> $shard_setting[$v['shard_id']],
                );
            }
        }
        /**
         * 利用配置信息组装为所有inter_id对应的配置
         * array( 'a23262362' => 
         *     array( '*' => 
         *         array(
         *             'db_resource'=> 'iwide_soma', 
         *             'table_suffix'=> '_1001', 
         *         ) 
         *     ) 
         * )
         */ 
        if($inter_id===NULL){
            return $shard_config;
            
        } elseif( $inter_id && array_key_exists($inter_id, $shard_config) ){
            return $shard_config[$inter_id];
            
        } else {
            return array();
        }
    }

    /**
     * 建立分片配置文件
     */
    public function bulid_shard_config_file() {

        $shard_db= $this->db_soma;  //define at MY_Model_Soma
        $shard_config= $shard_setting= array();
        
        $data= $this->_shard_db_r('iwide_soma_r')->get('soma_shard')->result_array();
        foreach ($data as $k=>$v){
            $shard_setting[$v['shard_id']]= array(
                'db_resource'=> $v['db_resource'],
                'table_suffix'=> $v['table_suffix']
            );
        }
        /**
         * 获取分片配置信息
         * array(1=> array(
         *     'db_resource'=> 'iwide_soma', 
         *     'table_suffix'=> '_1001', 
         * ))
         */ 
        $data= $this->_shard_db_r('iwide_soma_r')->get('soma_shard_link')->result_array();
        foreach ($data as $k=>$v){
            if( isset($shard_setting[$v['shard_id']]) ){
                $shard_config[$v['inter_id']]= array(
                    '*'=> $shard_setting[$v['shard_id']],
                );
            }
        }

        $this->array_to_config_file($shard_config);
        return $shard_config;

    }

    protected function array_to_config_file($arr, $lv_cnt = 1) {

        $str = '';
        if($lv_cnt == 1) $str .= "<?php\r\nreturn ";

        $out_tab_cnt = $tab_cnt = "";
        for ($i=0; $i < $lv_cnt; $i++) {
            if($tab_cnt != "") {
                $out_tab_cnt .= "\t";
            }
            $tab_cnt .= "\t";
        }

        $str .= "array(";
        foreach ($arr as $key => $value) {            
            $str .= "\r\n" . $tab_cnt . '"' . $key . '" => ';
            if(!is_array($value)) {
                $str .= '"' . $value . '",';
            } else {
                $lv = $lv_cnt + 1;
                $str .= $this->array_to_config_file($value, $lv);
            }
        }
        $str .= "\r\n" . $out_tab_cnt . ")";

        if($lv_cnt == 1) {
            $str .= ';';
            $file= APPPATH. 'config'. DS. self::SHARD_CONFIG_FILE;
            $fp = fopen( $file, 'w');
            fwrite($fp, $str);
            fclose($fp);
        } else {
            return $str .= ',';
        }
    }

}
