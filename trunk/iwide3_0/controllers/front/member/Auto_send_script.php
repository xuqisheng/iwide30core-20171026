<?php

class Auto_send_script extends CI_Controller
{

    public function index($inteid = 'a455510007 ')
    {
        if ($inteid == 'a455510007 ') {
            $data['template_id'] = 'tvo4CVM2jvxdUU4wiXx8c6hSgN1E4FyRfLhG0GV6AoA';
        }
        if (empty($data['template_id'])) {
            exit();
        }
        
        $data['url'] = "http://super8.iwide.cn/index.php/member/account/bind_show/?id=a455510007";
        $data['data'] = [
            'first' => [
                'value' => '速8中国提醒您，注意及时在微信中绑定速8会员：查询预订更便捷，立得现金券10元，微信订房获得2-18元随机现金券！',
                'size' => '3',
                "color" => "#000000"
            ],
            'keynote1' => [
                'value' => '微信绑定会员后可见',
                "color" => "#000000"
            ],
            'keynote2' => [
                'value' => '激活后2年',
                "color" => "#000000"
            ],
            'remark' => [
                'value' => '欢迎您通过微信绑定速8会员，非常简单，只需一步，点进前往！',
                "color" => "#0000FF"
            ]
        ]
        ;
        $startime = date("Y-m-d H:i:s", strtotime("-10 day")); // 目前是写死是前一天的时间
        $endtime = date("Y-m-d H:i:s", time());
        $this->load->model('member/Auto_send');
        $this->load->model('plugins/Template_msg_model');
        $this->load->model('member/member');
        $this->load->library ("MYLOG");
        $this->load->library('Baseapi/Subaapi_webservice', array(
            'testModel' => false
        ));
        $suba = new Subaapi_webservice(false);
        $list = $this->Auto_send->getNotsent($inteid, $startime, $endtime);
        
        foreach ($list as $arr) {
           $member=$this->member->getMemberInfoById($arr['open_id']);
              if (empty($member)||empty($member->membership_number)){
                       //不是会员，发送消息
            $data['touser'] = $arr['open_id'];
            $res = $this->Template_msg_model->send_template_msg($inteid, $data);
            if ($res['s'] == 1) {
                // 发送成功
                $update_data = [
                    'is_send' => 2,
                    'send_count' => $arr['send_count'] + 1
                ];
                MYLOG::w('OPEN_ID为 '.$arr['open_id'].' 用户,第'.($arr['send_count'] + 1).'次发送，发送成功');
            } else {
                $update_data = [
                    'is_send' => 1,
                    'send_count' => $arr['send_count'] + 1
                ];
                MYLOG::w('OPEN_ID为 '.$arr['open_id'].' 用户,第'.($arr['send_count'] + 1).'次发送，发送失败');
            }
        }
        else{
            //检索时已经是会员，踢出队列
            $update_data = [
                'is_send' => 2,
                'send_count' => $arr['send_count']
            ];
             MYLOG::w('OPEN_ID为 '.$arr['open_id'].' 用户,检索时已经是会员无需发送');
        }
        $res = $this->Auto_send->update($arr['open_id'], $inteid, $arr['id'], $update_data);
        }
    }
}