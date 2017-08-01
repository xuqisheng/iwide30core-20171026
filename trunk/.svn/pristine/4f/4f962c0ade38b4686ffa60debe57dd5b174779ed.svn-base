<?php

/**
 * 会员4.0后台数据处理模块
 * Created by knight.
 * User: ibuki
 * Date: 16/7/30
 * Time: 下午9:25
 */
class Public_log_model extends MY_Model_Member {

    const SPACE = ' '; //空格符
    protected $_pk = '';
    public $saveData = array();
    public $_status = null;
    public $args = array();

    public function save_log_init($old_data,$new_data,$relate_id,$result,$type = '',$obj = null, $args = array()){
        //操作记录信息
        $this->load->model('membervip/admin/config/attribute_model','ui_model');
        $key_map = $this->ui_model->get_logs_keymap($type);

        $logs = array(
            'name'=>!empty($args['name'])?$args['name']:'',
            'log_title'=>!empty($args['title'])?$args['title']:'后台配置',
            'log_type'=>$type,
            'old_data'=>$old_data,
            'is_json'=>!empty($args['is_json'])?$args['is_json']:false,
            'new_data'=>$new_data,
            'rule_name'=>!empty($args['rule_name'])?$args['rule_name']:'',
            'filter'=>!empty($args['filter'])?$args['filter']:array(),
            'key_mapping' => $key_map,
            'relate_id'=>$relate_id,
            'res'=>$result,
            'log_msg'=>!empty($args['log_msg'])?$args['log_msg']:'',
        );
        $this->args = $args;
        $this->save_logs($logs,$obj); //添加操作记录
    }

    /**
     * 读取操作记录
     * @param array $where 读取条件
     * @param int $offset 读取开始行数
     * @param int $limit 读取行数
     * @return array
     */
    public function get_record($where=array(),$offset=0,$limit=500){
        $result = $this->_shard_db()->where($where)->order_by('log_id desc')->limit($offset, $limit)->get()->result_array();
        if(empty($result)) return array();
        foreach ($result as &$v){
            $v['content'] = json_decode($v['content'],true);
        }
        return $result;
    }

    /**
     * 保存操作记录
     * @param array $logs
     * @param null $obj
     * @return bool
     */
    public function save_logs($logs = array(),$obj = null){
        $old_data = !empty($logs['old_data'])?$logs['old_data']:array();
        $new_data = !empty($logs['new_data'])?$logs['new_data']:array();
        $relate_id = !empty($logs['relate_id'])?$logs['relate_id']:0;
        $rule_name = !empty($logs['rule_name'])?$logs['rule_name']:0;
        $log_type = !empty($logs['log_type'])?$logs['log_type']:'';
        $res = !empty($logs['res'])?$logs['res']:null;
        $name = !empty($logs['name'])?$logs['name']:$relate_id;
        $content = array();
        $_new = '';
        if(!empty($old_data)){
            foreach ($old_data as $k=>$v){
                if(isset($new_data[$k])){
                    if(!empty($logs['filter']) && in_array($k,$logs['filter'])) continue;
                    $key_name = !empty($logs['key_mapping'][$k])?$logs['key_mapping'][$k]:$k;
                    if(is_array($new_data[$k]) || is_object($new_data[$k])) $new_data[$k] = json_encode($new_data[$k]);
                    if(is_array($v) || is_object($v)) $v = json_encode($v);
                    if(!empty($new_data[$k]) && strcmp($v,$new_data[$k]) !== 0) {
                        if(!empty($logs['is_json']) && $logs['is_json']===true){ //处理json格式数据
                            $v = json_decode($v,true);
                            $new_data[$k] = json_decode($new_data[$k],true);
                            foreach ($new_data[$k] as $nk=>$nv){
                                if(!empty($v[$nk])){
                                    if(strcmp($nv,$v[$nk]) !== 0) $content[] = $key_name.'：'.$nk.'：'.$v[$nk].' ➜ '.$nv;
                                }else $content[] = $key_name.'：'.$nk.'：null ➜ '.$nv;
                            }
                        }else $content[] = $key_name.'：'.$v.' ➜ '.$new_data[$k];
                    }
                }
            }

        }else if(!empty($new_data)){
            $_new = '新增：'.PHP_EOL;
            foreach ($new_data as $k =>$v){
                $key_name = !empty($logs['key_mapping'][$k])?$logs['key_mapping'][$k]:$k;
                $content[] = $key_name.'：'.$v;
            }
        }
        $this->saveData['content'] = $content;
        $this->saveData['log_title'] = !empty($logs['log_title'])?$logs['log_title']:'';
        if(!empty($this->saveData['content'])){
            $this->saveData['content'] = $_new.implode('; ',$this->saveData['content']);
            if(!empty($name)){
                $this->saveData['content'] = "[{$name}] ".PHP_EOL." {$this->saveData['content']}";
            }
        }elseif (!empty($logs['log_msg'])){
            $this->saveData['content'] = $logs['log_msg'];
            if(!empty($name)){
                $this->saveData['content'] = "[{$name}] ".PHP_EOL." {$this->saveData['content']}";
            }
        }else{
            return false;
        }
        $this->saveData['relate_id'] = $relate_id;
        $this->saveData['log_type'] = $log_type;
        $this->saveData['rule_name'] = $rule_name;
        $this->_status = $res;
        $this->save($obj);
    }

    /**
     * 插入操作记录
     * @param null $obj
     * @return string|mixed
     */
    public function save($obj = null){
        MYLOG::w(json_encode(array('data'=>$this->saveData)),'admin/membervip/logs_model', 'param');
        if(empty($this->saveData)) return 'data is null';
        $saveData = $this->_parseFields($this->saveData,'iwide_admin_operation_log'); //保存数据检测过滤
        MYLOG::w(json_encode(array('res'=>$saveData,'param'=>$this->saveData)),'admin/membervip/logs_model', '_parseFields');
        if(isset($saveData['log_id'])) unset($saveData['log_id']);
        $admin_profile = $this->session->userdata('admin_profile');
        $saveData['admin_id'] = !empty($this->args['admin_id'])?$this->args['admin_id']:$admin_profile['admin_id'];
        $saveData['inter_id'] = !empty($this->args['inter_id'])?$this->args['inter_id']:$admin_profile['inter_id'];
        $saveData['cur_ip'] = $this->get_client_ip(0,true);
        $saveData['result'] = $this->_status;
        $add_result = $obj->add_data($saveData,'admin_operation_log');
        MYLOG::w(json_encode(array('res'=>$add_result,'sql'=>$obj->_shard_db()->last_query())),'admin/membervip/logs_model', 'save');
        return $add_result;
    }

    /**
     * 数据检测
     * @access protected
     * @param mixed $data 数据
     * @param string $key 字段名
     * @return void
     */
    protected function _parseFields($data,$table='') {
        $fieldsdata = $this->_shard_db()->query("SHOW FULL COLUMNS FROM $table")->result_array();
        $saveData = array();
        foreach ($fieldsdata as $arr){
            if(isset($data[$arr['Field']])){
                $saveData[$arr['Field']] = $data[$arr['Field']];
                if($arr['Null']=='NO' && is_null($arr['Default']) && is_null($saveData[$arr['Field']])){ //过滤不合格数据
                    $saveData[$arr['Field']] = 0; continue;
                }

                if(is_array($data[$arr['Field']]) || is_object($data[$arr['Field']]))
                    $data[$arr['Field']] = json_encode($data[$arr['Field']]);

                $fieldType = explode('(',$arr['Type'])[0];
                if($fieldType == 'bigint' || $fieldType == 'int') {
                    $saveData[$arr['Field']]   =  intval($saveData[$arr['Field']]);
                }elseif($fieldType == 'float' || $fieldType == 'double'){
                    $saveData[$arr['Field']]   =  floatval($saveData[$arr['Field']]);
                }elseif($fieldType == 'bool'){
                    $saveData[$arr['Field']]   =  (bool)$data[$arr['Field']];
                }
            }
        }
        return $saveData;
    }

    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    protected function get_client_ip($type = 0,$adv=false) {
        $type = $type ? 1 : 0;
        static $ip = NULL;
        if ($ip !== NULL) return $ip[$type];
        if($adv){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos = array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip = trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        //IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }
}