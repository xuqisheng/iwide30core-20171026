<?php

/**
 * Created by knight.
 * User: ibuki
 * Date: 16/7/30
 * Time: 下午9:25
 */
class Card_model extends MY_Model_Member {



    /**
     * 查询礼包列表
     * @param int $inter_id 酒店集团ID
     * @param string $field 获取字段
     * @param array $extra 扩展参数
     * @return array
     */
    public function get_card_list($inter_id=0,$field='*',$extra = array()){
        if(empty($inter_id)) return array();
        $where = array('inter_id'=>$inter_id);
        $list_fields = $this->_shard_db(true)->list_fields('card');
        foreach ($list_fields as $_field){
            if(isset($extra[$_field])) $where[$_field] = $extra[$_field];
        }
        $result = $this->_shard_db()->select($field)->where($where)->get('card')->result_array();
        if($this->input->get('debug')=='1'){
            echo 'get_card_list - sql:';
            echo $this->_shard_db()->last_query();
        }
        return $result;
    }

    public function get_field_by_field($inter_id=0,$extra = array()){
        if(empty($inter_id)) return array();
        $where = array('inter_id'=>$inter_id);
        $list_fields = $this->_shard_db(true)->list_fields('card');
        foreach ($list_fields as $_field){
            if(isset($extra[$_field])) $where[$_field] = $extra[$_field];
        }
        $card_list = array();
        $result = $this->_shard_db()->select('card_id,title')->where($where)->get('card')->result_array();
        if($this->input->get('debug')=='1'){
            echo 'get_card_list - sql:';
            echo $this->_shard_db()->last_query();
        }
        if(!empty($result)){
            foreach ($result as $item){
                $card_list[$item['card_id']] = $item['title'];
            }
        }
        return $card_list;
    }

    /**
     * 根据条件获取会员优惠券的数量
     * @param array $where 查询条件
     * @return array
     */
    public function get_member_card_total($where=array()){
        if(empty($where)) return array();
        $result = $this->_shard_db()->select('COUNT(member_card_id) as count')->where($where)->get('member_card')->row_array();
        if($this->input->get('debug')=='1'){
            echo 'get_member_card_total - sql:';
            echo $this->_shard_db()->last_query();
        }
        return $result;
    }

    /**
     * 获取优惠券信息
     * @param $inter_id 酒店集团ID
     * @param null $kid 优惠券ID
     * @param string $field 获取字段
     * @return array
     */
    public function get_card_info($inter_id,$kid=null,$field='*'){
        if(empty($inter_id)) return array();
        $this->_shard_db()->select($field)->from('card')->where('inter_id',$inter_id);
        if(is_array($kid)) {
            $ids = array_keys($kid);
            $this->_shard_db()->where_in('card_id',$ids);
        }elseif (!empty($kid)) $this->_shard_db()->where('card_id',$kid);
        $_info = $this->_shard_db()->order_by('card_id desc')->get()->result_array();
        if($this->input->get('debug')=='1'){
            echo 'get_card_info - sql:';
            echo $this->_shard_db()->last_query();
        }
        $card_info = array();
        foreach ($_info as $item){
            if(is_array($kid) && !empty($kid) && !empty($kid[$item['card_id']])){
                $item['count'] = $kid[$item['card_id']];
            }
            $card_info[$item['card_id']]=$item;
        }
        return $card_info;
    }

    /**
     * 获取有效可领取的优惠券
     * @param string $inter_id 酒店集团ID
     * @param string $card_id 优惠券ID
     * @param string $field 筛选字段
     * @param boolean $single 是否返回一维数组(前提条件是必须只有一条数据的时候)
     * @return bool | array
     */
    public function get_can_received($inter_id = '', $card_id = '', $field = '*', $single = true){
        if(empty($inter_id)){
            return false;
        }
        $where = [
            'inter_id'=>$inter_id,
            'is_f'=>'f',
            'is_active'=>'t'
        ];

        $this->_shard_db()->select($field)->where($where)->from('card');
        if(!empty($card_id) && is_numeric($card_id)){
            $where['card_id'] = $card_id;
            $this->_shard_db()->where($where);
        }elseif(!empty($card_id) && is_array($card_id)){
            $this->_shard_db()->where_in('card_id',$card_id);
        }

        $result = $this->_shard_db()->order_by('createtime desc, card_id desc')->get()->result_array();

        $card_list = array();
        if(!empty($result)){
            foreach ($result as $k => $vo){
                $todaystart = strtotime(date('Y-m-d'));
                $todayend = strtotime(date('Y-m-d 23:59:59'));

                if($vo['time_start'] > $todayend){
                    $vo['state'] = 0;
                    $vo['err_msg'] = "\"{$vo['title']}\"领取时间未到";
                }elseif ($vo['time_end'] < $todaystart){
                    $vo['state'] = 0;
                    $vo['err_msg'] = "\"{$vo['title']}\"已过领取时间";
                }elseif ($vo['use_time_end_model']=='g') {
                    $use_time_end = strtotime(date('Y-m-d 23:59:59',$vo['use_time_end']));
                    if($use_time_end < time()){
                        $vo['state'] = 0;
                        $vo['err_msg'] = "\"{$vo['title']}\"使用期限已过";
                    }
                }elseif ($vo['card_stock'] <= 0){
                    $vo['state'] = 0;
                    $vo['err_msg'] = "\"{$vo['title']}\"库存为 0 ";
                }
                 $card_list[$vo['card_id']] = $vo;
            }
        }

        if(!empty($card_id) && !empty($card_list) && is_numeric($card_id) && $single === true){
            $card_list = reset($card_list);
        }

        return $card_list;
    }

    /**
     * 重组数组，为数组添加优惠券所属范围,已使用数量,已过期数量,已核销数量,是否可以领取
     * @param array $data 数据集
     * @param int $id 卡券ID
     * @return mixed
     */
    protected function get_card_module_by_data($data=array(),$id=0){
        $return['data'] = $data;
        $select = array('title','time_start','time_end','is_active','card_stock','use_time_end_model','use_time_end_day','use_time_end');
        $card = array();
        if(empty($data)) {
            $card = $this->_shard_db()
                ->select(implode(',',$select))
                ->where('card_id',$id)->get()->row_array();
        }else if(is_array($data)){
            foreach ($select as $vo){
                $card[$vo]=isset($data[0][$vo])?$data[0][$vo]:'';
            }
        }

        $return['title']=isset($card['title'])?$card['title']:'';

        $this->_shard_db()->from('card_module');
        if(is_array($id)){
            $this->_shard_db()->where_in('card_id',$id);
        }else{
            $this->_shard_db()->where('card_id',$id);
        }

        $result = $this->_shard_db()->group_by('card_module_id')->order_by('card_module_id desc')->get()->result_array();

        //增加时间判断逻辑
        $return['is_get']=2;
        if(isset($card['use_time_end_model']) &&$card['use_time_end_model']=='y'){
            $time_end = time() + (( 3600*24 ) * $card['use_time_end_day']);
            $use_time_end = strtotime(date('Y-m-d 23:59:59',$time_end));
        }elseif (isset($card['use_time_end_model']) && $card['use_time_end_model']=='g') {
            $use_time_end = $card['use_time_end'];
        }

        if(!empty($card) && $card['time_start']<=time() && $card['time_end']>time() && $use_time_end>=time() && $card['is_active']=='t' && floatval($card['card_stock'])>0) $return['is_get']=1; //检查优惠券是否可领取

        if($this->input->get('debug')=='1'){
            echo 'get_card_module - sql:';
            echo $this->_shard_db()->last_query();
        }
        $card_module=array();
        if(!empty($result)){
            foreach ($result as $vo){
                if(isset($card_module[$vo['card_id']])){
                    $card_module[$vo['card_id']][]=$vo['module'];
                }else{
                    $card_module[$vo['card_id']][]=$vo['module'];
                }
            }
        }

        foreach ($data as $k=>$vo){
            if(isset($card_module[$vo['card_id']])){
                $vo['card_module']=$card_module[$vo['card_id']];
            }
            $data[$k]=$vo;
        }


        $where = array('inter_id'=>$data[0]['inter_id'],'card_id'=>$id,'is_use'=>'t');
        $use_num=$this->get_member_card_total($where);//使用数量
        $return['use_num']=isset($use_num['count'])?$use_num['count']:0;
        $where = array('inter_id'=>$data[0]['inter_id'],'card_id'=>$id,'is_useoff'=>'t');
        $useoff_num=$this->get_member_card_total($where); //核销数量
        $return['useoff_num']=isset($useoff_num['count'])?$useoff_num['count']:0;
        $expire_time=strtotime(date('Y-m-d 00:00:00'));
        $where = array('inter_id'=>$data[0]['inter_id'],'card_id'=>$id,'expire_time <'=>$expire_time);
        $expire_num=$this->get_member_card_total($where); //过期数量
        $return['expire_num']=isset($expire_num['count'])?$expire_num['count']:0;
        $where = array('inter_id'=>$data[0]['inter_id'],'card_id'=>$id,'is_giving'=>'t');
        $giving_num=$this->get_member_card_total($where); //过期数量
        $return['giving_num']=isset($giving_num['count'])?$giving_num['count']:0;
        $return['data']=$data;
        return $return;
    }

    /**
     * 解析领取优惠券信息，添加使用范围
     * @param array $data
     * @return array
     */
    public function parse_member_card_by_data($data=array(),$card_id=0){
        if(empty($data)) return array();
        $datas['data']=$data;
        if(!empty($card_id)){
            $datas = $this->get_card_module_by_data($data,$card_id);
        }
        return $datas;
    }

    /**
     * 重组数组，改变键值
     * @param array $data 数据集
     * @param string $key 指定键值
     * @param string $vkey 指定$key所对应的值
     * @return array
     */
    protected function field_by_value($data=array(),$key='',$vkey=''){
        if(empty($data)) return array();
        $list = array();
        foreach ($data as $k => $vo){
            $list[$vo[$key]] = $vo[$vkey];
        }
        return $list;
    }

    /**
     * 根据某字段的值自定义排序
     * @param array $data 数组
     * @param string $field 字段
     * @param string $value 字段的值
     * @return array
     */
    protected function custom_sort($data=array(),$field='',$value=''){
        if(empty($data)) return array();
        $first = array();
        foreach ($data as $key => $item){
            if(isset($item[$field]) && $item[$field]==$value){
                $first = $item;
                unset($data[$key]);
            }
        }
        array_unshift($data,$first); //插入到最開始的位置
        return $data;
    }

    /**
     * 运行日志记录
     * @param String $content
     */
    protected function _write_log( $content,$type ) {
        $file= date('Y-m-d_H'). '.txt';
        $path= APPPATH. 'logs'. DS. 'membervip'. DS. 'customize'. DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $ip= $this->input->ip_address();
        $fp = fopen( $path. $file, 'a');

        $content= "\n[". date('Y-m-d H:i:s'). '] [' . $ip. "] $type '". $content. "' starting...";
        fwrite($fp, $content);
        fclose($fp);
    }
}