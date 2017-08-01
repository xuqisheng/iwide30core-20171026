<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Verify extends MY_Admin_Member
{

    // protected $label_module = NAV_HOTEL;
    protected $label_action = '';

    const SEND_URL = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=';

    protected $lv_default = '';

    protected $lvlid = '';

    protected $package_id = null;

    protected $rule_id = null;

    function __construct()
    {
        parent::__construct();
        $admin_profile = $this->session->userdata('admin_profile');
        $this->load->model('membervip/admin/Public_model', 'pum');
        $member_info = $this->pum->_shard_db()
            ->query("SELECT * FROM iwide_member_lvl a  where a.inter_id ='{$admin_profile['inter_id']}' and a.is_default='t' ")
            ->row_array();
        if (empty($member_info)) {
            $this->_ajaxReturn('fail', '初始级别没有配置', 0);
        }
        
        $this->lv_default = $member_info['member_lvl_id'];
        if ($admin_profile['inter_id'] == 'a483582961') {
            $this->lvlid = '734'; // 审核通过后升级ID
            $this->package_id = '387'; // 审核通过后礼包ID
            $this->rule_id = '943';
        } elseif ($admin_profile['inter_id'] == 'a476352963') {
            // 长春名人
            $this->lvlid = '958';
        }
    }

    public function grid()
    {
        $admin_profile = $this->session->userdata('admin_profile');
        /* 兼容grid变为ajax加载加这一段 */
        if (is_ajax_request()) {
            // 处理ajax请求，参数规格不一样
            $get_filter = $this->input->post();
            $_get_filter = $this->input->get();
            if (! empty($_get_filter) && is_array($_get_filter))
                $get_filter = $get_filter + $_get_filter;
        } else
            $get_filter = $this->input->get();
        
        if (! $get_filter)
            $get_filter = $this->input->get('filter');
        
        $params['table_name'] = 'member_verify';
        $params['alias'] = 'm';
        $select = array(
            'membership_number',
            'member_lvl_id',
            'name',
            'inter_id',
            'telephone',
            'company_name',
            'duty',
            'subtime',
            'type',
            'audit',
            'remark',
            'unpass_reason',
            'id'
        );
        
        foreach ($select as &$n) {
            $n = $params['alias'] . '.' . $n;
        }
        
        $params['sort_field'] = 'm.subtime';
        $params['sort_direct'] = 'desc';
        
        // 排序字段
        $order_columns = array(
            'membership_number',
            'member_lvl_id',
            'name',
            'inter_id',
            'telephone',
            'company_name',
            'duty',
            'subtime',
            'type',
            'audit',
            'remark',
            'unpass_reason',
            'id'
        );
        foreach ($order_columns as &$n) {
            $n = $params['alias'] . '.' . $n;
        }
        
        if (is_array($get_filter)) {
            $params = $get_filter + $params;
        }
        
        if (isset($params['order']) && ! empty($params['order'])) {
            $params['sort_field'] = $order_columns[$params['order'][0]['column']];
            $params['sort_direct'] = $params['order'][0]['dir'];
            if (isset($params['order'][1]) && ! empty($params['order'][1])) {
                $params['sort_field'] = $order_columns[$params['order'][1]['column']];
                $params['sort_direct'] = $params['order'][1]['dir'];
            }
        }
        $inter_id = $admin_profile['inter_id'];
        $params['m.inter_id'] = $inter_id;
        $params['m.audit'] = array(
            '0',
            '1',
            '2',
            '3'
        );
        $params['opt'] = 5;
        $params['ui_type'] = 8;
        $params['f_type'] = 8;
        $counts = $this->pum->_shard_db()
            ->query("SELECT COUNT(id) as count FROM iwide_member_verify WHERE inter_id = '$inter_id'  ")
            ->row_array();
        $result['data'] = array();
        $result['total'] = $counts['count'];
        $this->load->model('membervip/admin/Member_model', 'm_obj');
        
        // 获取等级配置
        $lvl_data = $this->pum->_shard_db()
            ->query("SELECT member_lvl_id, lvl_name   FROM iwide_member_lvl WHERE inter_id = '$inter_id'  ")
            ->result_array();
        $lvl = [];
        foreach ($lvl_data as $key => $val) {
            $lvl[$val['member_lvl_id']] = $val['lvl_name'];
        }
        
        if (is_ajax_request()) {
            // 处理ajax请求
            $params['page_size'] = 20;
            $member_mode = $this->m_obj->get_member_mode($admin_profile['inter_id']);
            $result = $this->pum->get_admin_filter($params, $select, $member_mode);
            
            foreach ($result['data'] as $key => $val) {
                $switch = 'false';
                if (strpos($val[9], '审核通过') > 0) {
                    $switch = 'true';
                }
                $str = '<td class="input_checkbox">
											<div class="btn_input">
												<input type="radio"
                                                     data-switch="' . $switch . '"
                                                        id="' . $val['12'] . '"
													name="member_id" value="' . $val['12'] . '"> <label
													for="' . $val['12'] . '"></label>
											</div>
										</td>';
                unset($result['data'][$key]['0']);
                array_unshift($result['data'][$key], $str);
                // 额外处理审核类型
                switch ($val['8']) {
                    case 'old':
                        $result['data'][$key]['8'] = '会员绑定';
                        break;
                    case 'new':
                        $result['data'][$key]['8'] = '新会员注册';
                        break;
                }
                // 额外处理等级
                if (! empty($val['2'])) {
                    $result['data'][$key]['2'] = isset($lvl[$val['2']]) ? $lvl[$val['2']] : $lvl[$this->lv_default];
                }
                // 长春名人
                if ($admin_profile['inter_id'] == 'a476352963') {
                    unset($result['data'][$key]['5']);
                    unset($result['data'][$key]['6']);
                    $result['data'][$key] = array_values($result['data'][$key]);
                }
            }
            echo json_encode($result);
            exit();
        } else {
            // HTML输出
            $this->label_action = '会员审核';
            $this->_init_breadcrumb($this->label_action);
            
            // base grid data..
            
            $this->load->model('membervip/admin/config/attribute_model', 'ui_model');
            $_moedel = $this->ui_model;
            $fields_config = $_moedel->get_field_config('grid', $params['f_type']);
            
            $default_sort = array(
                'field' => 'subtime',
                'sort' => $params['sort_direct']
            );
            $view_params = array(
                'module' => $this->ui_model,
                'model' => $this->pum,
                'fields_config' => $fields_config,
                'result' => '',
                'default_sort' => $default_sort,
                'get' => $get_filter,
                'inter_id' => $inter_id
            );
            if ($inter_id == 'a476352963') {
                unset($view_params['fields_config']['company_name']);
                unset($view_params['fields_config']['duty']);
                $html = $this->_render_content($this->_load_view_file('grid2'), $view_params, true);
            } else {
                $html = $this->_render_content($this->_load_view_file('grid'), $view_params, true);
            }
            echo $html;
        }
    }

    public function ajax_get_member_info()
    {
        $id = $this->input->get('id');
        $inter_id = $inter_id = $this->session->get_admin_inter_id();
        if (empty($id) || empty($inter_id))
            exit('数据不合法');
        $member_info = $this->pum->_shard_db()
            ->query("SELECT * FROM iwide_member_verify a  where a.inter_id ='" . $inter_id . "' and a.id= " . $id)
            ->row_array();
        if (! empty($member_info)) {
            if (! empty($member_info['member_lvl_id'])) {
                $lvl = $this->pum->_shard_db()
                    ->query("SELECT * FROM iwide_member_lvl a  where a.inter_id ='" . $inter_id . "' and member_lvl_id= " . $member_info['member_lvl_id'])
                    ->row_array();
                if (! empty($lvl)) {
                    $member_info['lvl_name'] = $lvl['lvl_name'];
                }
            }
            $member_info['createtime'] = date('Y-m-d H:i:s', $member_info['subtime']);
            echo json_encode([
                'err' => 0,
                'data' => $member_info
            ]);
            exit();
        }
        echo json_encode([
            'err' => 40003,
            'data' => ''
        ]);
    }

    public function member_audit_pass()
    {
        if (is_ajax_request()) {
            
            $inter_id = $this->session->get_admin_inter_id();
            $id = $this->input->get('id');
            $member_ship_num = $this->input->get('member_ship_num');
            $company = $this->input->get('company');
            $duty = $this->input->get('duty');
            $remark = $this->input->get('remark');
            $audit = $this->input->get('audit');
            $audit = ! empty($audit) ? $audit : '';
            if ($inter_id == 'a476352963') {
                // 长春名人
                if (preg_match('/^[A-Za-z]{4}[0-9]{5}$/', $member_ship_num) != 1) {
                    $this->_ajaxReturn('fail', '会员卡号录入有误，会员号为9位, 前4位为字母，后5位为数字，请修改后重新提交', 0);
                }
            }
            
            if (empty($member_ship_num) || strlen($member_ship_num) < 9)
                $this->_ajaxReturn('fail', '会员号不能短于9位', 0);
            if (empty($inter_id))
                $this->_ajaxReturn('fail', '无效的公众号', 0);
            $where['inter_id'] = trim($inter_id);
            // $where['member_mode'] = 2;
            $where['id'] = $id;
            $member_verify = $this->pum->_shard_db()
                ->select('*')
                ->where($where)
                ->order_by('id desc')
                ->get('member_verify')
                ->row();
            
            if (! empty($member_verify)) {
                // 检查会员号是否存在
                $check_ship_num = $this->pum->_shard_db()
                    ->select('member_info_id')
                    ->where([
                    'membership_number' => $member_ship_num,
                    'inter_id' => $inter_id
                ])
                    ->get('member_info')
                    ->result_array();
                if (isset($check_ship_num['0']['member_info_id']) && $check_ship_num['0']['member_info_id'] > 0) {
                    $this->_ajaxReturn('fail', '该会员号已存在，请确认后重新输入', 0);
                }
                
                if ($member_verify->audit == 1) {
                    $this->_ajaxReturn('fail', '该会员已经审核过', 0);
                }
                $data = array(
                    'membership_number' => $member_ship_num,
                    'company_name' => $company,
                    'duty' => $duty,
                    'member_lvl_id' => $this->lvlid,
                    'audittime' => time(),
                    'audit' => $audit,
                    'unpass_reason' => ' ',
                    'remark' => $remark
                );
                // 更新审核表
                $res = $this->pum->_shard_db()
                    ->where($where)
                    ->update('member_verify', $data);
                
                if (count($res) > 0) {
                    // 更新info表数据
                    $select = [
                        'name',
                        'open_id',
                        'telephone',
                        'email',
                        'id_card_no',
                        'membership_number',
                        'company_name',
                        'duty',
                        'audit'
                    ];
                    $data_verify = $this->pum->_shard_db()
                        ->select($select)
                        ->where($where)
                        ->order_by('id desc')
                        ->get('member_verify')
                        ->result_array()['0'];
                    unset($where['id']);
                    $data_verify['cellphone'] = $data_verify['telephone'];
                    $data_verify['member_lvl_id'] = $this->lvlid;
                    $where['member_mode'] = 1;
                    $data_verify['audit'] = '1';
                    $where['open_id'] = $data_verify['open_id'];
                    $res = $this->pum->_shard_db()
                        ->where($where)
                        ->update('member_info', $data_verify);
                    // 更新info表数据 END
                    if ($res) {
                        // 组装模板消息数据
                        // 获取默认等级名称和新等级名称
                        $lvl = $this->pum->_shard_db()
                            ->select('*')
                            ->where([
                            'inter_id' => $inter_id
                        ])
                            ->where_in('member_lvl_id', [
                            $this->lvlid,
                            $this->lv_default
                        ])
                            ->get('iwide_member_lvl')
                            ->result_array();
                        foreach ($lvl as $key => $val) {
                            switch ($val['member_lvl_id']) {
                                case $this->lvlid:
                                    $new_lvl_name = $val['lvl_name'];
                                    break;
                                case $this->lv_default:
                                    $old_lvl_name = $val['lvl_name'];
                                    break;
                            }
                        }
                        
                        // 拼装模板消息数据
                        $this->load->model('member/Message_wxtemp_model', 'wxtemp_model');
                        $wxtemp_model = $this->wxtemp_model;
                        $type = $wxtemp_model::SEND_VERIFY_LVL_UP;
                        $temps_where = array(
                            'inter_id' => $inter_id,
                            'type' => $type
                        );
                        $temps = $this->pum->get_info($temps_where, 'member_message_template');
                        if (empty($temps)) {
                            $this->_ajaxReturn('fail', '模板消息未配置', 0);
                        }
                        $templateUrl = $temps['link'];
                        $url = $this->geturl($templateUrl, $inter_id);
                        $message['touser'] = $member_verify->open_id; // 发送给哪个用户
                        $message['template_id'] = $temps['template_id']; // 微信模版ID
                        $message['url'] = $url; // url
                        $message['data']['first'] = array(
                            'value' => '恭喜您成功升级会员等级',
                            'color' => '#000000'
                        );
                        $message['data']['remark'] = array(
                            'value' => '马上预定享受更多会员折扣。',
                            'color' => '#000000'
                        );
                        $message['data']['keyword1'] = [
                            'value' => $old_lvl_name
                        ];
                        $message['data']['keyword2'] = [
                            'value' => $new_lvl_name
                        ];
                        $json_data = @json_encode($message);
                        // 组装模板消息数据 END
                        $sendResult = $this->request_send_template($inter_id, $json_data); // 发送模板消息
                        
                        if (! is_null($this->package_id)) {
                            // 有配置礼包，则执行赠送礼包流程
                            $packge_url = INTER_PATH_URL . 'package/give';
                            $package_data = array(
                                'token' => '',
                                'inter_id' => $inter_id,
                                'openid' => $data_verify['open_id'],
                                'uu_code' => uniqid(),
                                'package_id' => $this->package_id
                            );
                            if (! is_null($this->rule_id)) {
                                $package_data['card_rule_id'] = $this->rule_id;
                            }
                            $package_data = http_build_query($package_data);
                            $package_res = $this->doCurlPostRequest($packge_url, $package_data);
                        }
                        
                        $this->_ajaxReturn('ok', $sendResult, 1);
                    }
                }
            }
            $this->_ajaxReturn('null', null, 1);
        }
        $this->_ajaxReturn('fail', '请求失败', 0);
    }

    public function ajax_unpass()
    {
        if (is_ajax_request()) {
            $inter_id = $this->session->get_admin_inter_id();
            $id = $this->input->get('id');
            $type = $this->input->get('type');
            $remark = $this->input->get('remark');
            $reason_remark = '资料不符合要求';
            if ($type == 2) {
                $reason_remark = $this->input->get('reason_remark');
                if ($this->utf8_strlen($reason_remark) < 5) {
                    $this->_ajaxReturn('fail', '请输入至少五个字', 0);
                }
            }
            
            $where['inter_id'] = trim($inter_id);
            // $where['member_mode'] = 2;
            $where['id'] = $id;
            $member_verify = $this->pum->_shard_db()
                ->select('*')
                ->where($where)
                ->order_by('id desc')
                ->get('member_verify')
                ->row();
            
            if (! empty($member_verify)) {
                if ($member_verify->audit == 0) {
                    $this->_ajaxReturn('fail', '该会员尚未更新资料，无需重新审核。', 0);
                }
                
                $data['audit'] = '0';
                $data['unpass_reason'] = $reason_remark;
                $data['remark'] = $remark;
                $res = $this->pum->_shard_db()
                    ->where($where)
                    ->update('member_verify', $data);
                if ($res > 0) {
                    // 预留发送模板消息
                    $this->load->model('member/Message_wxtemp_model', 'wxtemp_model');
                    $wxtemp_model = $this->wxtemp_model;
                    $type = $wxtemp_model::SEND_VERIFY_UNPASS;
                    $temps_where = array(
                        'inter_id' => $inter_id,
                        'type' => $type
                    );
                    $temps = $this->pum->get_info($temps_where, 'member_message_template');
                    if (empty($temps)) {
                        $this->_ajaxReturn('fail', '模板消息未配置', 0);
                    }
                    $templateUrl = $temps['link'];
                    $url = $this->geturl($templateUrl, $inter_id);
                    $message['touser'] = $member_verify->open_id; // 发送给哪个用户
                    $message['template_id'] = $temps['template_id']; // 微信模版ID
                    $param = array(
                        'id' => $inter_id
                    );
                    $message['url'] = EA_const_url::inst()->get_front_url($inter_id, 'membervip/verify/show_old_member_reg', $param); // url
                    $message['data']['first'] = array(
                        'value' => '您提交的会员资料已完成审核，结果如下：',
                        'color' => '#000000'
                    );
                    $message['data']['remark'] = array(
                        'value' => '请您修改后重新提交，谢谢。',
                        'color' => '#000000'
                    );
                    $message['data']['keyword1'] = [
                        'value' => '未通过'
                    ];
                    // ..组装提交过来的审核不通过原因
                    $message['data']['keyword2'] = [
                        'value' => $reason_remark
                    ];
                    $json_data = @json_encode($message);
                    // 组装模板消息数据 END
                    $sendResult = $this->request_send_template($inter_id, $json_data); // 发送模板消息
                    
                    $this->_ajaxReturn('ok', $sendResult . $reason_remark, 1);
                }
            }
            $this->_ajaxReturn('null', null, 1);
        }
        $this->_ajaxReturn('fail', '请求失败', 0);
    }

    public function ajax_modify()
    {
        if (is_ajax_request()) {
            $inter_id = $this->session->get_admin_inter_id();
            $id = $this->input->get('id');
            $member_ship_num = $this->input->get('member_ship_num');
            $company = $this->input->get('company');
            $duty = $this->input->get('duty');
            $remark = $this->input->get('remark');
            $send = (bool) $this->input->get('send');
            
            if (empty($member_ship_num) || strlen($member_ship_num) != 9)
                $this->_ajaxReturn('fail', '会员号必须为9位', 0);
            if (empty($inter_id))
                $this->_ajaxReturn('fail', '无效的公众号', 0);
            $where['inter_id'] = trim($inter_id);
            // $where['member_mode'] = 2;
            $where['id'] = $id;
            if (! empty($ids)) {
                $ids = explode(',', $ids);
            }
            $member_info = $this->pum->_shard_db()
                ->select('*')
                ->where($where)
                ->order_by('id desc')
                ->get('member_verify')
                ->result_array();
            if ($member_info['0']['audit'] == 1) {
                // 如果已经审核过，则不发送信息
                $send = false;
            }
            if (! empty($member_info)) {
                // 检查会员号是否存在
                $check_ship_num = $this->pum->_shard_db()
                    ->select([
                    'member_info_id',
                    'open_id'
                ])
                    ->where([
                    'membership_number' => $member_ship_num,
                    'inter_id' => $inter_id
                ])
                    ->get('member_info')
                    ->row();
                
                if (! empty($check_ship_num) && $check_ship_num->member_info_id > 0 && $check_ship_num->open_id != $member_info[0]['open_id']) {
                    $this->_ajaxReturn('fail', '该会员号已存在，请确认后重新输入', 0);
                }
                
                $data = array(
                    'membership_number' => $member_ship_num,
                    'company_name' => $company,
                    'duty' => $duty,
                    'audit' => '1',
                    'member_lvl_id' => $this->lvlid,
                    'remark' => $remark,
                    'audittime' => time()
                );
                $res = $this->pum->_shard_db()
                    ->where($where)
                    ->update('member_verify', $data);
                if (count($res) > 0) {
                    // 更新info表
                    $select = [
                        'name',
                        'open_id',
                        'telephone',
                        'email',
                        'id_card_no',
                        'member_lvl_id',
                        'membership_number',
                        'company_name',
                        'duty',
                        'audit'
                    ];
                    $data = $this->pum->_shard_db()
                        ->select($select)
                        ->where($where)
                        ->order_by('id desc')
                        ->get('member_verify')
                        ->row();
                    $where['open_id'] = $data->open_id;
                    unset($where['id']);
                    unset($data->id);
                    if (! $send)
                        unset($data->member_lvl_id);
                    $res_info = $this->pum->_shard_db()
                        ->where($where)
                        ->update('member_info', $data);
                    if ($res_info) {
                        // 发送模板消息 注册成功发送等级变更通知\
                        if ($send == true) {
                            $this->load->model('member/Message_wxtemp_model', 'wxtemp_model');
                            $wxtemp_model = $this->wxtemp_model;
                            $type = $wxtemp_model::SEND_VERIFY_REG_PASS;
                            $temps_where = array(
                                'inter_id' => $inter_id,
                                'type' => $type
                            );
                            $temps = $this->pum->get_info($temps_where, 'member_message_template');
                            if (empty($temps)) {
                                $this->_ajaxReturn('fail', '模板消息未配置', 0);
                            }
                            $templateUrl = $temps['link'];
                            $url = $this->geturl($templateUrl, $inter_id);
                            $message['touser'] = $data->open_id; // 发送给哪个用户
                            $message['template_id'] = $temps['template_id']; // 微信模版ID
                            $message['url'] = $url; // url
                            $message['data']['first'] = array(
                                'value' => '恭喜您！您已成功成为洲际优悦会会员。',
                                'color' => '#000000'
                            );
                            $message['data']['remark'] = array(
                                'value' => '马上预定享受更多会员折扣。',
                                'color' => '#000000'
                            );
                            $message['data']['keyword1'] = [
                                'value' => $member_ship_num
                            ];
                            $message['data']['keyword2'] = [
                                'value' => $data->name
                            ];
                            $message['data']['keyword3'] = [
                                'value' => $data->telephone
                            ];
                            $json_data = @json_encode($message);
                            // 组装模板消息数据 END
                            $sendResult = $this->request_send_template($inter_id, $json_data); // 发送模板消息
                            
                            $this->_ajaxReturn('ok', $sendResult . $remark, 1);
                        }
                        $this->_ajaxReturn('ok', count($res_info), 1);
                    }
                }
            }
            $this->_ajaxReturn('null', null, 1);
        }
        $this->_ajaxReturn('fail', '请求失败', 0);
    }

    public function add_message_queue($inter_id = '', $data = array())
    {
        if (empty($inter_id) || empty($data))
            return false;
        $data['audittime'] = time();
        // 添加模版消息队列
        $save_data = array(
            'inter_id' => $inter_id,
            'openid' => $data['open_id'],
            'business_model' => 4,
            'content' => json_encode($data),
            'message_type' => 2,
            'createtime' => time(),
            'expiretime' => strtotime('+1 day')
        );
        $add_message_queue = $this->pum->add_data($save_data, 'template_message_queue');
        MYLOG::w(json_encode(array(
            'res' => $add_message_queue,
            'params' => $save_data
        )), 'front/membervip/invitate', 'add_message_queue');
    }

    /**
     * 封装curl的调用接口，post的请求方式
     *
     * @param
     *            string URL
     * @param
     *            string POST表单值
     * @param
     *            array 扩展字段值
     * @param
     *            second 超时时间
     * @return 请求成功返回成功结构，否则返回FALSE
     */
    function doCurlPostRequest($url, $requestString, $extra = array(), $timeout = 5)
    {
        if ($url == "" || $requestString == "" || $timeout <= 0) {
            return false;
        }
        $con = curl_init((string) $url);
        curl_setopt($con, CURLOPT_HEADER, false);
        curl_setopt($con, CURLOPT_POSTFIELDS, $requestString);
        curl_setopt($con, CURLOPT_POST, true);
        curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($con, CURLOPT_TIMEOUT, (int) $timeout);
        curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($con, CURLOPT_SSL_VERIFYHOST, 0);
        
        if (! empty($extra) && is_array($extra)) {
            $headers = array();
            foreach ($extra as $opt => $value) {
                if (strexists($opt, 'CURLOPT_')) {
                    curl_setopt($con, constant($opt), $value);
                } elseif (is_numeric($opt)) {
                    curl_setopt($con, $opt, $value);
                } else {
                    $headers[] = "{$opt}: {$value}";
                }
            }
            if (! empty($headers)) {
                curl_setopt($con, CURLOPT_HTTPHEADER, $headers);
            }
        }
        $res = curl_exec($con);
        return $res;
    }

    /**
     * 发送模版消息
     *
     * @param
     *            $json_data
     * @return string
     */
    public function request_send_template($inter_id = null, $json_data = array())
    {
        MYLOG::w(json_encode(array(
            'inter_id' => $inter_id,
            'data' => $json_data
        )), 'front/membervip/api/openapi', 'request_send_template');
        if (empty($inter_id) || empty($json_data))
            return $this->return_json('缺少必要参数!', - 1, true);
        
        $this->load->model('wx/access_token_model');
        $access_token = $this->access_token_model->get_access_token($inter_id);
        $url = self::SEND_URL . $access_token;
        $result = $this->doCurlPostRequest($url, $json_data);
        // 保存日志
        MYLOG::w(json_encode(array(
            'res' => $result,
            'url' => $url,
            'data' => $json_data
        )), 'admin/membervip/verify', 'request_send_template');
        
        $result_data = json_decode($result, true);
        if ($result_data['errcode'] == 0 && $result_data['errmsg'] == 'ok') {
            return $this->return_json('发送成功', $result_data['errcode'], true);
        } elseif ($result_data['errcode'] == '40001') {
            $access_token = $this->access_token_model->reflash_access_token($inter_id);
            $url = self::SEND_URL . $access_token;
            $result = $this->doCurlPostRequest($url, $json_data);
            // 保存日志
            MYLOG::w(json_encode(array(
                'res' => $result,
                'url' => $url,
                'data' => $json_data
            )), 'admin/membervip/verify', 'request_send_template');
            
            $result_data = json_decode($result, true);
            if ($result_data['errcode'] == 0 && $result_data['errmsg'] == 'ok') {
                return $this->return_json('发送成功', $result_data['errcode'], true);
            }
        } elseif ($result_data['errcode'] == '42001') {
            $access_token = $this->access_token_model->reflash_access_token($inter_id);
            $url = self::SEND_URL . $access_token;
            $result = $this->doCurlPostRequest($url, $json_data);
            // 保存日志
            MYLOG::w(json_encode(array(
                'res' => $result,
                'url' => $url,
                'data' => $json_data
            )), 'admin/membervip/verify', 'request_send_template');
            
            $result_data = json_decode($result, true);
            if ($result_data['errcode'] == 0 && $result_data['errmsg'] == 'ok') {
                return $this->return_json('发送成功', $result_data['errcode'], true);
            }
        }
        return $this->return_json('发送失败！', '40001', true);
    }

    function geturl($key, $inter_id)
    {
        $param = array(
            'id' => $inter_id
        );
        $exp = explode('_', $key, 3);
        switch ($key) {
            case 'membervip_center':
                $url = EA_const_url::inst()->get_front_url($inter_id, $exp[0] . '/' . $exp[1], $param);
                break;
            case 'membervip_card':
                $url = EA_const_url::inst()->get_front_url($inter_id, $exp[0] . '/' . $exp[1], $param);
                break;
            case 'membervip_card_cardinfo':
                $param['member_card_id'] = isset($datas['member_card_id']) ? $datas['member_card_id'] : '';
                $url = EA_const_url::inst()->get_front_url($inter_id, $exp[0] . '/' . $exp[1] . '/' . $exp[2], $param);
                break;
            case 'membervip_reg':
                $url = EA_const_url::inst()->get_front_url($inter_id, $exp[0] . '/' . $exp[1], $param);
                break;
            case 'membervip_invitate':
                $url = EA_const_url::inst()->get_front_url($inter_id, $exp[0] . '/' . $exp[1], $param);
                break;
            default:
                break;
        }
        return $url;
    }

    /**
     * 输出JSON提示
     *
     * @param string $errmsg
     *            提示信息
     * @param int $errcode
     *            状态码
     */
    protected function return_json($errmsg = '系统繁忙', $errcode = -1, $flag = false)
    {
        header('Content-Type:application/json; charset=utf-8');
        $result = new stdClass();
        $result->errcode = $errcode;
        $result->errmsg = $errmsg;
        if ($flag === true)
            return json_encode($result);
        exit(json_encode($result));
    }

    function utf8_strlen($string = null)
    {
        // 将字符串分解为单元
        preg_match_all('/./us', $string, $match);
        // 返回单元个数
        return count($match[0]);
    }

    /**
     * 运行日志记录
     *
     * @param String $content            
     */
    protected function _write_log($content)
    {
        $file = date('Y-m-d_H') . '.txt';
        $path = APPPATH . 'logs' . DS . 'admin' . DS . 'membervip' . DS . 'ownerusers' . DS;
        if (! file_exists($path)) {
            @mkdir($path, 0777, TRUE);
        }
        $ip = $this->input->ip_address();
        $fp = fopen($path . $file, 'a');
        
        $content = "\n[" . date('Y-m-d H:i:s') . '] [' . $ip . "] Task '" . $content . "' starting...";
        fwrite($fp, $content);
        fclose($fp);
    }
}