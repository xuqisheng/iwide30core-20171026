<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Ownerusers extends MY_Admin_Member {
    // protected $label_module = NAV_HOTEL;
    protected $label_controller = '会员管理';
    protected $label_action = '';

    protected function main_model_name(){
        return 'member/Member_info_model';
    }

    public function grid(){
        $admin_profile = $this->session->userdata('admin_profile');

        /* 兼容grid变为ajax加载加这一段 */
        if(is_ajax_request()){
            //处理ajax请求，参数规格不一样
            $get_filter= $this->input->post();
            $_get_filter= $this->input->get();
            if(!empty($_get_filter) && is_array($_get_filter)) $get_filter = $get_filter + $_get_filter;
        }else
            $get_filter= $this->input->get();

        if( !$get_filter) $get_filter = $this->input->get('filter');

        $this->load->model('membervip/admin/Public_model','pum');

        $params['table_name'] = 'member_info';
        $params['alias'] = 'm';
        $select = array('member_info_id','inter_id','nickname','name','sex','membership_number','telephone','cellphone','member_lvl_id','credit','balance','is_active','is_login','member_type','company_name','employee_id','createtime','subtime','audittime','audit');
        foreach ($select as &$n){
            $n = $params['alias'].'.'.$n;
        }

        $params['sort_field'] = 'm.subtime,m.createtime';
        $params['sort_direct'] = 'desc';

        //排序字段
        $order_columns = array('member_info_id','nickname','name','sex','membership_number','telephone','member_lvl_id','is_active','is_login','member_type','company_name','employee_id','subtime','audit');
        foreach ($order_columns as &$n){
            $n = $params['alias'].'.'.$n;
        }

        if(is_array($get_filter)) {
            $params = $get_filter + $params;
        }

        if(isset($params['order']) && !empty($params['order'])){
            $params['sort_field'] = $order_columns[$params['order'][0]['column']];
            $params['sort_direct'] = $params['order'][0]['dir'];
            if(isset($params['order'][1]) && !empty($params['order'][1])){
                $params['sort_field'] = $order_columns[$params['order'][1]['column']];
                $params['sort_direct'] = $params['order'][1]['dir'];
            }
        }

        $inter_id = $admin_profile['inter_id'];
        $params['m.inter_id'] = $inter_id;
        $params['m.member_type'] = array('97','98');
        $params['m.audit'] = array('0','1','2');
        $params['m.is_active'] = 't';

        $params['opt'] = 5;
        $params['ui_type'] = 6;
        $params['f_type'] = 6;

        $counts = $this->pum->_shard_db()->query("SELECT COUNT(member_info_id) as count FROM iwide_member_info WHERE inter_id = '$inter_id' AND member_type in ('97','98') AND audit in ('0','1','2')  AND is_active='t'")->row_array();
        $result['data'] = array();
        $result['total'] = $counts['count'];
        $this->load->model('membervip/admin/Member_model','m_obj');
        $member_mode = $this->m_obj->get_member_mode($admin_profile['inter_id']);
        if(is_ajax_request()){
            //处理ajax请求
            $params['page_size'] = 20;
            $result = $this->pum->get_admin_filter($params,$select,$member_mode);
            echo json_encode($result);exit;
        }else{
            //HTML输出
            $this->label_action= '业主/员工';
            $this->_init_breadcrumb($this->label_action);

            //base grid data..
            $num = (config_item('grid_static_num'))? config_item('grid_static_num'): 500;
            if($result['total'] < $num) $result = $this->pum->get_admin_filter($params,$select,$member_mode);
            $this->load->model('membervip/admin/config/attribute_model','ui_model');
            $_moedel = $this->ui_model;
            $fields_config = $_moedel->get_field_config('grid',$params['f_type']);
            $default_sort= array('field'=>'subtime', 'sort'=>$params['sort_direct']);
            $view_params= array(
                'module'=> $this->ui_model,
                'model'=> $this->pum,
                'result'=> $result,
                'fields_config'=> $fields_config,
                'default_sort'=> $default_sort,
                'get'=>$get_filter,
            );
            $html = $this->_render_content($this->_load_view_file('grid'), $view_params,true);
            echo $html;
        }
    }

    public function member_audit(){
        if(is_ajax_request()){
            $this->load->model('membervip/admin/Public_model','pum');
            $inter_id = $this->session->get_admin_inter_id();
            $member_info_id = !empty($this->input->get('member_info_id'))?$this->input->get('member_info_id'):0;
            $audit = $this->input->get('audit');
            $keymaps = array('0','1');
            if(!in_array($audit,$keymaps)) $this->_ajaxReturn($audit,'参数错误',0);
            if(empty($inter_id)) $this->_ajaxReturn('fail','无效的公众号',0);
            $where['inter_id'] = trim($inter_id);
            $where['member_mode'] = 2;
            $where['member_info_id'] = $member_info_id;
            $member_info = $this->pum->_shard_db()->select('member_info_id,member_id,open_id,name,telephone,membership_number,audit')
                                     ->where($where)
                                     ->where_in('audit',array('2','0'))
                                     ->where_in('member_type',array('97','98'))
                                     ->order_by('member_info_id desc')->get('member_info')->row_array();
            MYLOG::w(json_encode(array('data'=>$member_info,'where'=>$where,'where_in'=>array(array('2','0'),array('97','98')),'sql'=>$this->pum->_shard_db()->last_query())),'membervip/ownerusers','member_audit');
            if(!empty($member_info)){
                $level_url = PMS_PATH_URL.'member/members_review'; //会员员工／业主审核
                if(!empty($member_info['open_id']) && !empty($member_info['membership_number']) && !empty($member_info['telephone'])){
                    $reqs = array(
                        'inter_id'=>$inter_id,
                        'openid'=>$member_info['open_id'],
                        'card_num'=>$member_info['membership_number'],
                        'levelcode'=>'R',
                        'data'=>array(
                            'telephone'=>$member_info['telephone'],
                            'audit'=>$audit
                        ),
                    );
                    MYLOG::w(json_encode(array('data'=>$reqs)),'membervip/ownerusers','member_level_datas');
                    $startime = microtime(true);
                    $rew = $this->doCurlPostRequest($level_url,$reqs);
                    $endtime = microtime(true);
                    $usetime = $endtime - $startime;
                    MYLOG::w(json_encode(array('res'=>$rew,'url'=>$where,'data'=>$reqs,'usetime'=>$usetime)),'membervip/ownerusers','member_members_review');
                    if($rew['err']=='0' && $audit=='0'){
                        $this->add_message_queue($inter_id,$member_info);
                    }
                    if(floatval($rew['err'])>0){
                        $this->_ajaxReturn("审核不通过，{$rew['msg']} 卡号：{$member_info['membership_number']}",'',0);
                    }
                    $this->_ajaxReturn('ok',"审核成功，卡号：{$member_info['membership_number']}",1);
                }else{
                    $this->_ajaxReturn("审核不通过, 卡号：{$member_info['membership_number']}",'',0);
                }
            }
            $this->_ajaxReturn('null',"{$member_info_id}",1);
        }
        $this->_ajaxReturn('fail','请求失败',0);
    }

    public function add_message_queue($inter_id='',$data=array()){
        if(empty($inter_id) || empty($data)) return false;
        $this->load->model('membervip/admin/Public_model','pum');
        $data['audittime'] = time();
        //添加模版消息队列
        $save_data =array(
            'inter_id'=>$inter_id,
            'openid'=>$data['open_id'],
            'business_model'=>4,
            'content'=>json_encode($data),
            'message_type'=>2,
            'createtime'=>time(),
            'expiretime'=>strtotime('+1 day'),
        );
        $add_message_queue = $this->pum->add_data($save_data,'template_message_queue');
        MYLOG::w(json_encode(array('res'=>$add_message_queue,'params'=>$save_data)),'front/membervip/invitate', 'add_message_queue');
    }

    /**
     * 封装curl的调用接口，post的请求方式
     * @param string URL
     * @param string POST表单值
     * @param array 扩展字段值
     * @param second 超时时间
     * @return 请求成功返回成功结构，否则返回FALSE
     */
    protected function doCurlPostRequest( $url , $post_data , $timeout = 20) {
        $requestString = http_build_query($post_data);
        if ($url == "" || $timeout <= 0) {
            return false;
        }
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, false);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //設置請求數據返回的過期時間
        curl_setopt ( $curl, CURLOPT_TIMEOUT, ( int ) $timeout );
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, true);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $requestString);
        //执行命令
        $res = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //写入日志
        $log_data = array(
            'url'=>$url,
            'post_data'=>$post_data,
            'result'=>$res,
        );
        $this->_write_log(serialize($log_data) );
        return json_decode($res,true);
    }

    /**
     * 并行调用接口，HTTP POST的请求方式
     * @param $query_arr [url,data]
     * @return mixed
     */
    protected function curl_multi ($query_arr) {
        $startime = microtime(true);
        $ch = curl_multi_init(); // 创建批处理cURL句柄
        $count = count($query_arr);
        $ch_arr = array();
        for ($i = 0; $i < $count; $i++) {
            $query_string = $query_arr[$i]['url'];
            $requestString = http_build_query($query_arr[$i]['data']);
            $ch_arr[$i] = curl_init($query_string); // 创建cURL资源
            curl_setopt($ch_arr[$i], CURLOPT_RETURNTRANSFER, true); // 设置URL和相应的选项
            curl_setopt($ch_arr[$i], CURLOPT_POSTFIELDS, $requestString);
            curl_multi_add_handle($ch, $ch_arr[$i]); // 增加句柄
        }

        // 执行批处理句柄
        $running = null;
        do {
            curl_multi_exec($ch, $running);
        } while ($running > 0);
        for ($i = 0; $i < $count; $i++) {
            $results[$i] = curl_multi_getcontent($ch_arr[$i]); //返回获取的输出的文本流
            curl_multi_remove_handle($ch, $ch_arr[$i]);// 关闭全部句柄
        }
        curl_multi_close($ch);
        $endtime = microtime(true);
        $usetime = $endtime - $startime;
        //写入日志
        MYLOG::w(json_encode(array('res'=>$results,'url_data'=>$query_arr,'usetime'=>$usetime)),'membervip/ownerusers','curl_multi');
        return $results;
    }

    /**
     * 运行日志记录
     * @param String $content
     */
    protected function _write_log( $content ) {
        $file= date('Y-m-d_H'). '.txt';
        $path= APPPATH. 'logs'. DS. 'admin'. DS. 'membervip'. DS.'ownerusers'.DS;
        if( !file_exists($path) ) {
            @mkdir($path, 0777, TRUE);
        }
        $ip= $this->input->ip_address();
        $fp = fopen( $path. $file, 'a');

        $content= "\n[". date('Y-m-d H:i:s'). '] [' . $ip. "] Task '". $content. "' starting...";
        fwrite($fp, $content);
        fclose($fp);
    }
}