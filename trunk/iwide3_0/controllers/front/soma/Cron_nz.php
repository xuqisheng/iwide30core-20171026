<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_nz extends MY_Controller
{
    //request /index.php/soma/cron_nz/gzfljy_808_fans

    protected $_basic_path;
    protected $_redis = null;

    public function __construct() {
        parent::__construct();
        $this->_basic_path = APPPATH . '..' . DS . 'www_admin' . DS . 'public' . DS . 'import' . DS;
        $this->_redis = $this->get_redis_instance();
        if($this->_redis == null)
        {
            die('redis connect fail!');
        }
    }

    protected function _parse_csv_file($file) {
        $csv = fopen($file, 'r');
        $csv_data = array(); 
        $n = 0; 
        while ($data = fgetcsv($csv)) { 
            $num = count($data); 
            for ($i = 0; $i < $num; $i++) { 
                $csv_data[$n][$i] = mb_convert_encoding($data[$i], 'utf-8', 'gbk');//$data[$i]; 
            } 
            $n++; 
        }
        return $csv_data;
    }

    protected function get_inter_domain($inter_id)
    {
        $this->load->model('wx/publics_model', 'wxp_model');
        $public = $this->wxp_model->get_public_by_id($inter_id);
        return empty($public['domain']) ? false : $public['domain'];
    }

    protected function get_target_openids_from_csv($inter_id, $file)
    {
        // 抽取需要发送的订单号、openid
        $csv_data = $this->_parse_csv_file($file);
        unset($csv_data[0]);

        $order_ids = $openids = array();
        foreach ( $csv_data as $row)
        {
            if(!empty($row[0]))
            {
                $order_ids[] = $row[0];
            }
            if(!empty($row[1]) && !in_array($row[1], $openids))
            {
                $openids[] = $row[1];
            }
        }

        //初始化数据库分片配置，微信接口关闭订单需要初始化shard_id
        $this->load->model('soma/shard_config_model', 'model_shard_config');
        $this->current_inter_id= $inter_id;
        $this->db_shard_config= $this->model_shard_config->build_shard_config( $inter_id );

        if(!empty($order_ids))
        {
            $this->load->model('soma/Sales_order_model', 'o_model');
            $orders = $this->o_model->get_order_list('package', $inter_id, array('order_id' => $order_ids));
            if(!empty($orders))
            {
                foreach($orders as $order)
                {
                    if(!in_array($order['openid'], $openids))
                    {
                        $openids[] = $order['openid'];
                    }
                }
            }
        }

        return $openids;
    }

    /**
     * Gets the redis instance.
     *
     * @param      string $select The select
     *
     * @return     Redis|null  The redis instance.
     */
    public function get_redis_instance($select = 'soma_redis')
    {
        $this->load->library('Redis_selector');
        if ($redis = $this->redis_selector->get_soma_redis($select)) {
            return $redis;
        }
        return null;
    }

    public function wdm()
    {
        die('fail');
        $inter_id = 'a495077577';
        $template_id = 'mAvBTWgcXSs1gmqeyd3bEL0FUZdWOyWECmr7foF0aSg';
        $file = $this->_basic_path . 'wdm.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://mp.weixin.qq.com/s/rzdXvxyLA2AFHRgmPkwg1Q';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '粉丝福利｜您有一份福利待确认',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '电影票、音乐会门票',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '即日起至2017年6月18日22:00',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '点击详情立即参与活动',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function upsky_gx()
    {
        die('fail');
        $inter_id = 'a470377478';
        $template_id = 'DJvbl5nW_Xh18S9b8ugGCqxj2JUsZ4zxu0QUPgJzxQk';
        $file = $this->_basic_path . 'upsky_gx.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://hotels.liyewl.com/index.php/soma/package/index/?id=a470377478&tkid=82&catid=';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '亲爱的你，UPSKY广西区年中大促即将开启',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '149元自助晚餐、39.9元双人下午茶、799元2晚商务套房限时抢购',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '6月15日20:00正式开启',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '点击本消息立即订阅',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function jt()
    {
        die('fail');
        $inter_id = 'a441624001';
        $template_id = 'eyqP2VTvIuf3qh2jniLXrXzaODF3SGtjxjRTuch8HOE';
        $file = $this->_basic_path . 'jt.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://chatinn.iwide.cn/index.php/soma/package/index?id=a441624001';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '您好：街町酒店618已经开启',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '207元起两晚客房券秒杀',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '6月16日12:00正式开启',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '点击模版即刻抢购',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function symldm()
    {
        die('fail');
        $inter_id = 'a466508403';
        $template_id = 'ZcyMuLi9dFCwI9cxrGfpQXjS3ta5wjqtuqOwBsx0TNE';
        $file = $this->_basic_path . 'symldm.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://symarry.iwide.cn/index.php/soma/package/package_detail?pid=116689&id=a466508403';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '您好：沈阳碧桂园玛丽蒂姆酒店618已经开启',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '618元两晚客房券秒杀、316元买一大一小送一大自助晚餐;',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '6月16日12:00已经正式开启',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '点击即刻抢购（分享给好友，购买成功即可获得分销奖励5元）',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function csxld()
    {
        die('fail');
        $inter_id = 'a492660851';
        $template_id = 'cC6s1U6bGxhWybVtjxej_jVmYkClL8Rud9kAs7XSacc';
        $file = $this->_basic_path . 'csxld.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://hotels.tyxbf.com/index.php/membervip/card/getcard?id=a492660851&card_rule_id=957';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '粉丝福利 | 我们为您预约了一份专属大礼',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '10元商城优惠券（任意正价商品抵用）',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '2017年6月18日-6月23日可用',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '点击详情立即领取',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function btebg()
    {
        die('fail');
        $inter_id = 'a467780350';
        $template_id = '0R1187s4zjDHDsOpUIw8bsDsxziNUdmS8ELKmm1Dbss';
        $file = $this->_basic_path . 'btebg.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://hotels.liyewl.com/index.php/soma/package/index?id=a467780350';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '尊敬的顾客，广州白天鹅宾馆618大促已经开启',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '分享秒杀产品，您的好友购买后，您将获得8元奖励',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '秒杀活动将于明日结束，先到先得',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '点击模版马上分享',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function cdsjc_open()
    {
        die('fail');
        $inter_id = 'a484122795';
        $template_id = 'zm7KXr7RKzYuM55TD2OC--mmrLVUQCcnYEF9jVYACJg';
        $file = $this->_basic_path . 'cdsjc.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://hotels.iwide.cn/index.php/soma/package/index?id=a484122795';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '成都世纪城天堂洲际大饭店·618父出真爱秒杀即将开启~',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '直降100元自助餐24小时限时抢',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '6月18日00:00正式开启',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '点击即刻订阅',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function cdsjc_close()
    {
        die('fail');
        $inter_id = 'a484122795';
        $template_id = 'zm7KXr7RKzYuM55TD2OC--mmrLVUQCcnYEF9jVYACJg';
        $file = $this->_basic_path . 'cdsjc.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://hotels.iwide.cn/index.php/soma/package/index?id=a484122795';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '成都世纪城天堂洲际大饭店·618父出真爱秒杀火热进行中~',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '直降100元自助餐24小时限时抢',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '6月18日23:59秒杀关闭',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '赶紧点击抢购',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function cdhq_open()
    {
        die('fail');
        $inter_id = 'a484123441';
        $template_id = 'bNfvt9JC8eUP81GjHdaax1g5Pbjmp99CqBzOGkGSpaI';
        $file = $this->_basic_path . 'cdhq.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://hotels.iwide.cn/index.php/soma/package/index?id=a484123441';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '成都环球中心洲际天堂大酒店丨微信商城618父亲节福利秒杀火热进行时~',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '感恩父亲节全场五折秒杀限量抢购',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '6月17日00:00开启中',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '点击即刻抢购',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function cdhq_close()
    {
        die('fail');
        $inter_id = 'a484123441';
        $template_id = 'bNfvt9JC8eUP81GjHdaax1g5Pbjmp99CqBzOGkGSpaI';
        $file = $this->_basic_path . 'cdhq.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://hotels.iwide.cn/index.php/soma/package/index?id=a484123441';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '成都环球中心洲际天堂大酒店丨微信商城618父亲节福利秒杀结束倒计时6小时~',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '感恩父亲节全场五折秒杀限量抢购',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '6月18日23:59秒杀通道关闭',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '点击即刻抢购',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function wdm_622()
    {
        die('fail');
        $inter_id = 'a495077577';
        $template_id = 'mAvBTWgcXSs1gmqeyd3bEL0FUZdWOyWECmr7foF0aSg';
        $file = $this->_basic_path . 'wdm_622.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://mp.weixin.qq.com/s/hsX2K36C49c7evOkARaWsA';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '粉丝福利｜您有一份福利待确认',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '免费意大利美食节品鉴',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '即日起至2017年6月23日12:00',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '点击详情进入链接即可参与活动',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function qdhsl_628()
    {
        die('fail');
        $inter_id = 'a490782373';
        $template_id = 'hQyT3Lm7R-pnQ4R9Q3eS1vpOzuC6T6nmZsCaPlQxmdA';
        $file = $this->_basic_path . 'qdhsl_628.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://mp.weixin.qq.com/s/MOIIXj0h9biQuBKAuAjGmw';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '青岛红树林702欢乐开启泡泡趴/荧光夜跑活动即将开始',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '您报名的青岛红树林702欢乐开启泡泡趴/荧光夜跑活动将于6月29日-7月1日10:00-16:00在红树林婚礼堂预发装备包，请在此时间段内前往红树林领取。同时请点击填写提交您的个人信息，参与7.02泡泡趴/荧光跑活动保险。点击查看详情＞',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '6月29日-7月1日10:00-16:00',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '点击模版进入详情',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function qdhsl_630()
    {
        die('fail');
        $inter_id = 'a490782373';
        $template_id = 'hQyT3Lm7R-pnQ4R9Q3eS1vpOzuC6T6nmZsCaPlQxmdA';
        $file = $this->_basic_path . 'qdhsl_630.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://1.kuai354.com/index.php/soma/package/index?id=a490782373';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '青岛红树林7.02海边自助晚餐即将启动',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '尊敬的客人您好，您已成功购买青岛红树林度假世界7.02海边自助产品。由于最近天气情况多变，如遇大雨大风等特殊天气，7月2日海边自助盛宴将转移至珊瑚酒店1楼珊瑚宴会厅举行，如有更多问题，请拨打咨询电话：17685596192.',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '7月2日00:00-23:59',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '点击模版进入商城',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function shuxiang_630()
    {
        die('fail');
        $inter_id = 'a449675133';
        $template_id = 'LVCqCmcettpg_vVQbEp2a0c7nBDQnIR_zFtKqc3r8gg';
        $file = $this->_basic_path . 'shuxiang_630.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://soocor.iwide.cn/index.php/soma/package/?id=a449675133';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '书香酒店【周五秒杀】已经开启',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '358元秒杀|荡口古镇+梁鸿湿地双景包价',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '6月30日18:00正式开启',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '点击即刻抢购',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function csstkbsj_703()
    {
        die('fail');
        $inter_id = 'a497598114';
        $template_id = 'h4x2NItvYtL7XplrryHdx4iwB1bpn2UXLWBLvTYDW9k';
        $file = $this->_basic_path . 'csstkbsj_703.csv';

        $data['template_id'] = $template_id;
        $data['url'] = '';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '长沙顺天凯宾斯基酒店商城  粉丝提醒：',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '您秒杀的商品【黄金鸡】',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '商品使用有效期：2017.7.1-2017.7.31 ',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '使用前请提前至少24小时预约，预约电话：0731-8463 3333转祈顺中餐厅',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function syxlwdm_710()
    {
        die('fail');
        $inter_id = 'a495426640';
        $template_id = 'N_GqbrNSgQLaWlv559VvReuy4TE89ez8hLfWJ08UDC4';
        $file = $this->_basic_path . 'syxlwdm_710.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://hotels.hfmc99.com/index.php/soma/groupon/groupon_detail?grid=3013&id=a495426640&saler=&fans_saler=15130756&rel_res=';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '亲，你发起的拼团人数距离71人差距尚远，我们已发起官方拼团，您可点击此消息参与',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '龙虾、海鲜自助晚餐（拼团）',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '7.1',
            'color' => '#000000'
        );
        $subdata['keyword3'] = array(
            'value' => '7.1',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '您原支付的钱款我们已在发起退款，预计第二天到账。',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function csxld_713()
    {
        die('fail');
        // 1000277687
        $inter_id = 'a492660851';
        $template_id = 'dBkhAgQDVOCjiXtq-a-Wg9uA3vVKep-Ei1SHBUlWsFI';
        $file = $this->_basic_path . 'csxld_713.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://hotels.tyxbf.com/index.php/soma/package/index?id=a492660851&saler=&fans_saler=12896531';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '亲爱的您，自助餐通用券到期提醒',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '您于2017年5月18日购买的150元自助餐通用券即将到期，敬请于有效期内前往营业点使用。预约电话：0731-84888887',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '有效期截止至2017年8月19日',
            'color' => '#000000'
        );
        $subdata['keyword3'] = array(
            'value' => '长沙运达喜来登酒店三楼盛宴西餐厅',
            'color' => '#000000'
        );
        $subdata['keyword4'] = array(
            'value' => '150元（费用已支付）',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function zzmdwh_717()
    {
        die('fail');
        $inter_id = 'a496652649';
        $template_id = 'YUOT8BHMgDWzr0YlOYFwbAryoUw02LLw2T37tgakNLs';
        $file = $this->_basic_path . 'zzmdwh_717.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://hotels.hfmc99.com/index.php/soma/package/index?id=a496652649&code=071Tuaox1AcWff0s0gox1brgox1TuaoJ&state=STATE&saler=&fans_saler=13890315';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '二周年商城秒杀7月18日零点开启',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '餐饮&客房大放价，17:00视频直播开启，带您边看直播边秒杀',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '7月18日00:00-23:59',
            'color' => '#000000'
        );
        $subdata['keyword3'] = array(
            'value' => '株洲美的万豪酒店',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function whxgll_718()
    {
        die('fail');
        // 1000286253
        $inter_id = 'a492152200';
        $template_id = 'bWe5spsQVo3rDuZEwmB0Qq_KjjCU8I5FZJy3mIEoqdk';
        $file = $this->_basic_path . 'whxgll_718.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://hotels.hfmc99.com/index.php/soma/package/index?id=a492152200';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '商城秒杀7月18日零点开启',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '餐饮&红酒&月饼&房券大礼包，年中钜惠，何止五折！',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '7月18日00:00-23:59',
            'color' => '#000000'
        );
        $subdata['keyword3'] = array(
            'value' => '武汉香格里拉大酒店',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function qdhsl_725()
    {
        die('fail');
        // 1000229820
        $pos = $this->input->get('pos', true);
        $inter_id = 'a490782373';
        $template_id = 'hQyT3Lm7R-pnQ4R9Q3eS1vpOzuC6T6nmZsCaPlQxmdA';
        $file = $this->_basic_path . 'qdhsl_725_' . $pos . '.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://1.njt3s.com/index.php/distribute/distribute/reg?id=a490782373';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '恭喜您已成功成为馅饼侠',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '同时您获得了1个“推荐有礼”的资格。您只要成功推荐1名新馅饼侠，即可获得“双升”海景房的机会。即您与新馅饼侠都可获得升海景房资格。',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '即日起至8月20日23:59',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '点击加入：我们一起寻找新的馅饼侠',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function whhkfhxld()
    {
        die('fail');
        // 1000306435
        $inter_id = 'a499844461';
        $template_id = 'qbavjDfEm8CaZeNnpuBBh4Mteln67-nZRotULCa9VDc';
        $file = $this->_basic_path . 'whhkfhxld.csv';

        $data['template_id'] = $template_id;
        $data['url'] = '';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '亲，您购买的波士顿龙虾使用时间有变更哦',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '波士顿龙虾使用时间更改',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '购买日起--2017/07/21-2017/09/17',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '武汉汉口泛海喜来登大酒店',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function cskbsj_731()
    {
        die('fail');
        $inter_id = 'a497598114';
        $template_id = 'h4x2NItvYtL7XplrryHdx4iwB1bpn2UXLWBLvTYDW9k';
        $file = $this->_basic_path . 'cskbsj_731.csv';

        $data['template_id'] = $template_id;
        $data['url'] = '';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '尊敬的客人，您的自助餐通用券到期提醒',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '您于2017年6月29日-7月2日秒杀购买的180元自助餐通用券即将到期，敬请于有效期内前往营业点使用。预约电话：0731-8463 3333',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '有效期截止至2017年7月31日',
            'color' => '#000000'
        );
        $subdata['keyword3'] = array(
            'value' => '酒店二楼 元素西餐厅',
            'color' => '#000000'
        );
        $subdata['keyword4'] = array(
            'value' => '180元（费用已支付）',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function csxld_803_drxs()
    {
        die('fail');
        $inter_id = 'a492660851';
        $template_id = '61atiVMiaqqx314Xq1b0z0mukIr_zqRdsiGTFgMqe2s';
        $file = $this->_basic_path . 'csxld_803_drxs.csv';

        $data['template_id'] = $template_id;
        $data['url'] = '';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '亲爱的您，您的赞吧·国际餐厅西式单人套餐到期提醒',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '您于2017年5月18日购买的98元西式单人套餐即将到期，敬请于有效期内前往营业点使用。预约电话：0731-84888887',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '有效期截止至2017年8月19日',
            'color' => '#000000'
        );
        $subdata['keyword3'] = array(
            'value' => '长沙运达喜来登酒店三楼赞吧·国际餐厅',
            'color' => '#000000'
        );
        $subdata['keyword4'] = array(
            'value' => '98元（费用已支付）',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function csxld_803_ps()
    {
        die('fail');
        $inter_id = 'a492660851';
        $template_id = '61atiVMiaqqx314Xq1b0z0mukIr_zqRdsiGTFgMqe2s';
        $file = $this->_basic_path . 'csxld_803_ps.csv';

        $data['template_id'] = $template_id;
        $data['url'] = '';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '亲爱的您，您的赞吧·国际餐厅12寸四拼披萨到期提醒',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '您于2017年5月18日购买的58元12寸四拼披萨即将到期，敬请于有效期内前往营业点使用。预约电话：0731-84888887',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '有效期截止至2017年8月19日',
            'color' => '#000000'
        );
        $subdata['keyword3'] = array(
            'value' => '长沙运达喜来登酒店三楼赞吧·国际餐厅',
            'color' => '#000000'
        );
        $subdata['keyword4'] = array(
            'value' => '58元（费用已支付）',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function qdhsl_803()
    {
        die('fail');
        $inter_id = 'a490782373';
        $template_id = 'xDpGc91llgjAZPxOssP4BkWP1RJBYgBhoVtY8igRehw';
        $file = $this->_basic_path . 'qdhsl_803.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://1.njt3s.com/index.php/soma/package/package_detail?pid=150812&id=a490782373&saler=1131&channel=15906';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '第三届青岛红树林度假世界服务号馅饼侠活动已销售超过2800套！限量5000套，先到先得！',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '红树林度假世界',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '',
            'color' => '#000000'
        );
        $subdata['keyword3'] = array(
            'value' => '',
            'color' => '#000000'
        );
        $subdata['keyword4'] = array(
            'value' => '5000',
            'color' => '#000000'
        );
        $subdata['keyword5'] = array(
            'value' => '待支付',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '只需5000元定金即可获得青岛/三亚红树林度假世界7间夜标间权益，一年后5000元全额返还！快点击消息申请吧→',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function send_hsl_fans_message()
    {
        // $inter_id = 'a490782373';
        $inter_id = 'a450089706';
        $template_id = 'xDpGc91llgjAZPxOssP4BkWP1RJBYgBhoVtY8igRehw';
        $file = $this->_basic_path . 'hsl_fans_openids.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://1.njt3s.com/index.php/soma/package/package_detail?pid=150812&id=a490782373&saler=1131&channel=15906';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '第三届青岛红树林度假世界服务号馅饼侠活动已销售超过2800套！限量5000套，先到先得！',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '红树林度假世界',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '',
            'color' => '#000000'
        );
        $subdata['keyword3'] = array(
            'value' => '',
            'color' => '#000000'
        );
        $subdata['keyword4'] = array(
            'value' => '5000',
            'color' => '#000000'
        );
        $subdata['keyword5'] = array(
            'value' => '待支付',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '只需5000元定金即可获得青岛/三亚红树林度假世界7间夜标间权益，一年后5000元全额返还！快点击消息申请吧→',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $origin_openids = $this->get_target_openids_from_csv($inter_id, $file);
        $openids = $this->filter_not_pay_openids($inter_id, $origin_openids);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_hsl:' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            // $res = $this->t_model->send_template(json_encode($data), $inter_id);
            // $this->_redis->set($redis_key, json_encode($res));
            $this->_redis->set($redis_key, json_encode($data));
        }

        $pos = $this->_redis->get('Soma_hsl:openid_pos');
        $pos = empty($pos) ? 0 : $pos;

        echo '发送Excel表第[' . ($pos + 1) . '~'. ($pos + 300) . ']' . '行的openid模板消息成功!';
    }

    protected function filter_not_pay_openids($inter_id, $openids)
    {
        if (empty($openids)) {
            return [];
        }

        $key      = 'Soma_hsl:openid_pos';
        $hour_key = 'Soma_hsl:send_hour';
        $pos = $this->_redis->get($key);
        $pos = empty($pos) ? 0 : $pos;

        $last_send_hour = $this->_redis->get($hour_key);
        $last_send_hour = empty($last_send_hour) ? date('Y-m-d H') : $last_send_hour;
        $this->_redis->set($hour_key, date('Y-m-d H'));

        $limit_openids = [];
        for ($i = $pos; $i < count($openids) && count($limit_openids) < 300; $i++) {
            $limit_openids[] = $openids[$i];
        }

        if ($last_send_hour != date('Y-m-d H')) {
            $this->_redis->set($key, $pos + count($limit_openids));
        }

        $this->load->model('soma/Sales_order_model', 'o_model');
        $filter = [
            'inter_id'  => $inter_id,
            'openid'    => $limit_openids,
            'status !=' => 11
        ];
        $orders = $this->o_model->get(
            array_keys($filter),
            array_values($filter),
            'openid',
            ['limit' => null]
        );

        $openids_hash = array_flip($limit_openids);   
        if(!empty($orders))
        {
            foreach($orders as $order)
            {
                if(in_array($order['openid'], $limit_openids))
                {
                    unset($openids_hash[$order['openid']]);
                }
            }
        }
        $limit_openids = array_flip($openids_hash);

        return $limit_openids;
    }

    public function gzfljy_808_fans()
    {
        // 1000322982
        $inter_id = 'a495782075';
        $template_id = 'ut5fABg6fb-vSfbBuwNC-0LKAHC3gLU-MtHdTAQYerE';
        $file = $this->_basic_path . 'gzfljy_808_fans.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://hotels.tianai123.com/index.php/soma/package/index?id=a495782075';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '最后一轮月饼秒杀活动即将开始',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '君悦月饼礼盒每款产品限量参与秒杀，购完即止',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '2017年8月8日10:00',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '点击立即查看详情',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function gzfljy_808_saler()
    {
        // 1000322982
        $inter_id = 'a495782075';
        $template_id = 'ut5fABg6fb-vSfbBuwNC-0LKAHC3gLU-MtHdTAQYerE';
        $file = $this->_basic_path . 'gzfljy_808_saler.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://hotels.tianai123.com/index.php/soma/package/index?id=a495782075';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '温馨提醒',
            'color' => '#000000' 
        );
        $subdata['keyword1'] = array(
            'value' => '最后一轮月饼秒杀即将开始，您已注册成为分销员，每成功销售一份产品将获得奖励',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '即日起至9月15日',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '点击立即参与',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);
        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    /**
     *
     * /index.php/soma/cron_nz/hsl_0810
     */
    public function hsl_0810()
    {
        $inter_id = 'a490782373';
        $template_id = 'hQyT3Lm7R-pnQ4R9Q3eS1vpOzuC6T6nmZsCaPlQxmdA';
        $file = $this->_basic_path . 'hsl_0810.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://1.njt3s.com/index.php/distribute/distribute/reg?id=a490782373';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '恭喜您已成功成为馅饼侠',
            'color' => '#000000'
        );
        $subdata['keyword1'] = array(
            'value' => '同时您获得了“推荐有礼”的资格。您只要通过【青岛红树林度假世界服务号】成功推荐1名新馅饼侠，即可获得“双升”海景房的机会，即您与新馅饼侠都可获得升海景房权利1次。成功邀请多位好友加入，可以多次获得升级权利次数。活动时间：8月10日16:00至8月11日23:59。（1次升级海景权利：如果您1间房一次性连续7晚在青岛住完，则这间房7晚均升级为海景房。如分开住或分多间房，则只能选择其中一间连续入住的房间升级。）',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '点击加入：我们一起寻找新的馅饼侠',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);

        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    /**
     *
     * /index.php/soma/cron_nz/hsl_0810
     */
    public function cssjjy_811()
    {
        // 1000331591
        $inter_id = 'a496629410';
        $template_id = '8bT1by79o5GRYCCjk62r7TzjWSZZ60eLnCTG8CgAWNo';
        $file = $this->_basic_path . 'cssjjy_811.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://hotels.hfmc99.com/index.php/soma/package/index/?id=a496629410&tkid=188&catid=&code=021XIqYW0EJpUW1JUS0X0czCYW0XIqYa&state=STATE&saler=15';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '长沙世纪金源大饭店8月12日秒杀即将开启',
            'color' => '#000000'
        );
        $subdata['keyword1'] = array(
            'value' => '周一至周五99元自助午餐，更有金源月饼80元包邮',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '8月12日7:00正式开启',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '用餐地点：长沙世纪金源大饭店一楼自助餐厅',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);

        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

    public function dfbg_818()
    {
        // 1000331591
        $inter_id = 'a462353539';
        $template_id = 'AkHbmUmkE_Hm92Z96QC7zhoDj3DhL5aI4cDGA7A9ekc';
        $file = $this->_basic_path . 'dfbg_818.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://1.njt3s.com/index.php/soma/package/package_detail?pid=150812&id=a490782373';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '亲爱的，感谢你15年16年对东方宾馆微信月饼的支持，现为回馈老客户的支持，特推出专属价格',
            'color' => '#000000'
        );
        $subdata['keyword1'] = array(
            'value' => '老客户专属月饼价格',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '抢购正在进行中',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '到8月20日21:00截止，点击模版即刻抢购',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);

        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }
    
    public function qdhsl20170818()
    {
        // 1000331591
        $inter_id = 'a490782373';
        $template_id = 'xDpGc91llgjAZPxOssP4BkWP1RJBYgBhoVtY8igRehw';
        $file = $this->_basic_path . 'qdhsl20170818.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://1.njt3s.com/index.php/soma/package/package_detail?pid=150812&id=a490782373';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '本季馅饼侠活动仅剩200余名额！错过本次再等一年！',
            'color' => '#000000'
        );
        $subdata['keyword1'] = array(
            'value' => '红树林度假世界',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '',
            'color' => '#000000'
        );
        $subdata['keyword3'] = array(
            'value' => '',
            'color' => '#000000'
        );
        $subdata['keyword4'] = array(
            'value' => '5000',
            'color' => '#000000'
        );
        $subdata['keyword5'] = array(
            'value' => '待支付',
            'color' => '#000000'
        );
        
        $subdata['remark'] = array(
            'value' => '【终极招募令】第三季馅饼侠活动已成功招募9800人，余量不足200，错过就是1年，速来！点击模版消息进入申请通道→',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);

        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }
 /**
     * Wuqd 2017-09-01
     * 手动发送微信模板消息
     * 武汉泛海喜来登
     */
    public function fhxld_0901()
    {
        // 1000331591//
        $inter_id = 'a499844461';
        $template_id = 'qbavjDfEm8CaZeNnpuBBh4Mteln67-nZRotULCa9VDc';
        $file = $this->_basic_path . 'fhxld_0901.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://hotels.hfmc99.com/index.php/soma/package/index?id=a499844461';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '武汉汉口泛海喜来登大酒店7.20大促商品有效日期到期提醒',
            'color' => '#000000'
        );
        $subdata['keyword1'] = array(
            'value' => '请在“武汉泛海喜来登大酒店”公众号→“我”→“订单”查询产品到期日期，逾期无效',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '具体到期时间以商品信息为准',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '温馨提醒：请于产品有效期内使用商品，逾期无效',
            'color' => '#000000'
        );
        $data['data'] = $subdata;

        $openids = $this->get_target_openids_from_csv($inter_id, $file);

        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($openids as $openid) {
            $redis_key = $base_key . $openid;
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $openid;
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }
    
    public function testcsv(){
        $file = APPPATH . '..' . DS . 'www_admin' . DS . 'public' . DS . 'import' . DS . 'bgy_reward_hotels.csv';
        $csv = fopen($file, 'r');
        $csv_data = array(); 
        $n = 0; 
        while ($data = fgetcsv($csv)) { 
            $num = count($data); 
            for ($i = 0; $i < $num; $i++) { 
                $csv_data[$n][$i] = mb_convert_encoding($data[$i], 'utf-8', 'gbk');//$data[$i]; 
            } 
            $n++; 
        }

        $hotels = [];
        foreach ($csv_data as $row) {
            $hotels[] = $row[0];
        }
        var_dump($hotels);
    }


    /**
     * Wuqd 2017-09-08
     * 手动发送微信模板消息
     * 南昌力高皇冠假日酒店
     */
    public function lghgjr_0908()
    {


        $orderArrInfo = array(
            array('order_id' => '1000329409','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjjQ48o2a2UMhrmU0Oo6cXjk'),
            array('order_id' => '1000329444','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjpBYLeei1KKDW3xJzXvdxZU'),
            array('order_id' => '1000329452','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjoyDGnkxWZDJf_qAw9hZy20'),
            array('order_id' => '1000329459','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjuf2HjWiWB7i0_ISQww-oVA'),
            array('order_id' => '1000329467','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjnvs_qOuKLO-hpKQErL9c4c'),
            array('order_id' => '1000329470','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjqUf9qorSnI_wTSv1-dgLC4'),
            array('order_id' => '1000329471','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjufiVBF0bM_h3rm4_t-U-6A'),
            array('order_id' => '1000329490','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjsm4sa2Y_yH9KYjs_y8tQiY'),
            array('order_id' => '1000329495','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjlbCAWe2rDY1DUoZ8ITjAuk'),
            array('order_id' => '1000329502','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjrtiXj3q0gQn4jf3dShn8Yw'),
            array('order_id' => '1000329507','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjrEjDLB8bHVgGfodLYTlHo4'),
            array('order_id' => '1000329513','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjrnFvnYhqS9Dpvm2siOKh8w'),
            array('order_id' => '1000329516','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjpXgVNuo251_1oFCe1hLrD8'),
            array('order_id' => '1000329517','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjkEFoWMU5PXIrGn--OIZLJw'),
            array('order_id' => '1000329530','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjmirUfEw0R3If9-VYUVbPTM'),
            array('order_id' => '1000329532','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjs_oQ5XFSddd6gIKwgl41kY'),
            array('order_id' => '1000329535','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjpX0NtV_UUWreuWojtdrrBs'),
            array('order_id' => '1000329536','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjrQNlC_dFbuiQ3xe2mUuvt8'),
            array('order_id' => '1000329538','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjkEFoWMU5PXIrGn--OIZLJw'),
            array('order_id' => '1000329545','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjihac7pT3l6Uxc4--CQwlwc'),
            array('order_id' => '1000329553','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjqubPZ6SfXbjYXTH3IppUa8'),
            array('order_id' => '1000329563','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjq6Pi0O4qxyXBZqudAv3hP4'),
            array('order_id' => '1000329565','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjrP8hP1U5y5-PS22MqWWhSQ'),
            array('order_id' => '1000329566','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjsXF25wTGz23idMb1xCS5RI'),
            array('order_id' => '1000329573','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjsC5F6L6HiSrPYPLMcmgq0A'),
            array('order_id' => '1000329595','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjsWOZsx0W3PpRJA1Ria1gkA'),
            array('order_id' => '1000329596','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjn1W4Y_aSRjccpQBCh1VwJA'),
            array('order_id' => '1000329618','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjufiVBF0bM_h3rm4_t-U-6A'),
            array('order_id' => '1000329624','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjhgKjh484P6dukMwWkBjDR8'),
            array('order_id' => '1000329633','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjhgKjh484P6dukMwWkBjDR8'),
            array('order_id' => '1000329635','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjigWJNpye_unDwPLpZbOkfs'),
            array('order_id' => '1000329642','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjvi26cvVj9IOBc-xkwWtIXs'),
            array('order_id' => '1000329649','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjoNA4gYh3TLOO8dVEOzh-No'),
            array('order_id' => '1000329664','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjjXfncf2j4usZYLqkn9tUGw'),
            array('order_id' => '1000329687','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjl6VxGybRvdFC48XEO4MFR8'),
            array('order_id' => '1000329689','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjnfb7h9ImtVdvbQModB2cfU'),
            array('order_id' => '1000329699','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjnx9OsIXruBJ9zuQgrv5jJo'),
            array('order_id' => '1000329700','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjmhsIKDSPIVrv_JpnAANmSU'),
            array('order_id' => '1000329703','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjlTGdgm2Fo_waFfACFDW2n4'),
            array('order_id' => '1000329708','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjtgoZ07ts-QrdYXFOzPuUno'),
            array('order_id' => '1000329710','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjilTgmgt8PBjtrMZK8cF-gM'),
            array('order_id' => '1000329720','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjnzGlu00adNuyM2JTpLmg1Y'),
            array('order_id' => '1000329723','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjtIOyCHyovYynW2rRG3biKk'),
            array('order_id' => '1000329742','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjm_UmpXMWJjTm-Gm9BtjK_M'),
            array('order_id' => '1000329751','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjsVUU6a0LLFwTVCqj_GmeHU'),
            array('order_id' => '1000329764','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjnIJn4ibeEMIRpetOUmwslU'),
            array('order_id' => '1000329767','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjhp20iijU8iWH7IpoxQa1mg'),
            array('order_id' => '1000329786','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjtR3D5PVA39maPSMsP4dXls'),
            array('order_id' => '1000329787','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjvr7jvhgbEMmmJua35RUt9I'),
            array('order_id' => '1000329795','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjjUe10ImPtovKsFsjncmhT0'),
            array('order_id' => '1000329798','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjvqbYm-148mplIgzhN7W2Jw'),
            array('order_id' => '1000329806','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjp6WrFhD08kpGsC-Lh0kQ1I'),
            array('order_id' => '1000329822','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjjUe10ImPtovKsFsjncmhT0'),
            array('order_id' => '1000329827','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjm1G7Se7k6nUjYuT-tuE_NI'),
            array('order_id' => '1000329840','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjmZIi4dSX5BkQI_Au1CqjJw'),
            array('order_id' => '1000329842','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjmkFSPTY6-OsmliPpbNofs0'),
            array('order_id' => '1000329854','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjjUdBCDvopNiB7xLu78E1nI'),
            array('order_id' => '1000329861','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjhBEdJjVvLcIR5KdCih-s0k'),
            array('order_id' => '1000329869','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjrOIPBNYdyi9DmpAMAtuM6Q'),
            array('order_id' => '1000329891','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjlpp3wsq3O7EE1L5UKOKxLU'),
            array('order_id' => '1000329897','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjhkaIQjvp7hKd9fuxFpvIpg'),
            array('order_id' => '1000329907','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjvRLztlps_kzinqMlwsAPKk'),
            array('order_id' => '1000329920','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjsuFxMVG6y248EiozXz2FeE'),
            array('order_id' => '1000329926','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjr6IL1kno8edNe9tCg2mWCQ'),
            array('order_id' => '1000329959','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjr8uIpw_lTLtaxi1q2oL6SA'),
            array('order_id' => '1000329960','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjjmI7U63s8-Wpde0JeL2vYM'),
            array('order_id' => '1000329968','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjjmI7U63s8-Wpde0JeL2vYM'),
            array('order_id' => '1000329976','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjs0C8ehYVWX2nR2Oe_Ca50Q'),
            array('order_id' => '1000329980','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjqVwY-h0IWirCWRrcVm7EEk'),
            array('order_id' => '1000329982','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjh1gIb3czOUr4XYT4VHih6Y'),
            array('order_id' => '1000329997','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjr6IL1kno8edNe9tCg2mWCQ'),
            array('order_id' => '1000329998','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjiRWHbaDPYi8gWwAfxSVUgQ'),
            array('order_id' => '1000329999','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjvYF0AOuqWYvIyNUphrYCE4'),
            array('order_id' => '1000330007','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjjr1Agm1IQm_FjlVj5Pu1-g'),
            array('order_id' => '1000330015','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjpzerf0QKbBDnMWOskdHfQU'),
            array('order_id' => '1000330038','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjuRkepfVD5AGrjypuCrXIzw'),
            array('order_id' => '1000330039','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjlG-g90krBcobLcPJSFBU8w'),
            array('order_id' => '1000330044','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjlo3eKafMvOLVCWpmjut12c'),
            array('order_id' => '1000330058','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjoBeZVpzQTDMhhHO1N0tVZA'),
            array('order_id' => '1000330070','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjtREg-iMJuW8y1i58Gkb7wQ'),
            array('order_id' => '1000330072','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjivS5bTAIHRptnzDyreB0BI'),
            array('order_id' => '1000330089','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjqwSD_5UMRjKclw9d8r58k8'),
            array('order_id' => '1000330104','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjqwSD_5UMRjKclw9d8r58k8'),
            array('order_id' => '1000330108','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjqwSD_5UMRjKclw9d8r58k8'),
            array('order_id' => '1000330114','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjpE0ezCj02DbuK3f9SaAQVs'),
            array('order_id' => '1000330126','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjsGC837ZoYaiKfRVvEu0BSI'),
            array('order_id' => '1000330156','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjqigsLo3D_iE6ff5oMbKhls'),
            array('order_id' => '1000330169','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjuj6NTIop7Re6gNCiChVMsk'),
            array('order_id' => '1000330280','expiration_date' => '2017-10-10 12:08:59','openid' => 'o7B6cjhrE0fVrcvfgC1YcMNydt0s'),
            array('order_id' => '1000330297','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjs1ES3INEtdjYvjD8J_ewRY'),
            array('order_id' => '1000330303','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjm6Aui199Sr0ASyUByb1GPg'),
            array('order_id' => '1000330344','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjsGC837ZoYaiKfRVvEu0BSI'),
            array('order_id' => '1000330354','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjkMoXosXuu02E3HfV2D92qQ'),
            array('order_id' => '1000330381','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjm0SJfbUDqCMjtPdlgJWNyc'),
            array('order_id' => '1000330385','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjp3SGQ6upmjDLYGPQGnpdXc'),
            array('order_id' => '1000330395','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjhFu_2MtjwO3PT0SL4pTzJQ'),
            array('order_id' => '1000330428','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjlCxXJJWO9MQTskcgEyqcZI'),
            array('order_id' => '1000330464','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjoM670RF9CZcGaErOq-WoMw'),
            array('order_id' => '1000330467','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjvb7xugbtLqNIF4jpxcaAzI'),
            array('order_id' => '1000330482','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjn6t8Ydjt83yRwhDDbKtCwc'),
            array('order_id' => '1000330512','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjvZSrJ4eTWHhUWv29Q8DJN8'),
            array('order_id' => '1000330528','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjoseWHaW6sQMyFpKDMM4UkA'),
            array('order_id' => '1000330565','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjl70AbpJYsrOyihkYnf6iYE'),
            array('order_id' => '1000330673','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjrItqjNedjeUM9pchOeLJ1g'),
            array('order_id' => '1000330679','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjhrE0fVrcvfgC1YcMNydt0s'),
            array('order_id' => '1000330681','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjrItqjNedjeUM9pchOeLJ1g'),
            array('order_id' => '1000330704','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjoFlAP1K0paUhur0vJvDJDA'),
            array('order_id' => '1000330715','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjg45uhFH6F2wpW5ECAWH2rA'),
            array('order_id' => '1000330719','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjg45uhFH6F2wpW5ECAWH2rA'),
            array('order_id' => '1000330733','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjr-vrZNEYPbCyFhHDrCum9g'),
            array('order_id' => '1000330735','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjrfotFcAOr5B_5UW4twq_sk'),
            array('order_id' => '1000330797','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjsVr5NPf9gL39Q7dQqFGQNE'),
            array('order_id' => '1000330804','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjtTwW24cWuUV4lMubKdaLHU'),
            array('order_id' => '1000330809','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjhYjD_EX-lGyCYJWXTLvJ9Y'),
            array('order_id' => '1000330810','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjhYjD_EX-lGyCYJWXTLvJ9Y'),
            array('order_id' => '1000330824','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjnQaiT-wK8ZScs12a6GNPjw'),
            array('order_id' => '1000330831','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjuYVS8kqpS13KH2WB0wFUUc'),
            array('order_id' => '1000330850','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjsdquqz5maSnBO-lMVKW_ig'),
            array('order_id' => '1000330872','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjlLlmnHtG4K4K7UR4bdrq8I'),
            array('order_id' => '1000330911','expiration_date' => '2017-10-04 23:59:59','openid' => 'o7B6cjp-I-kw_ZqzAFqrC_cW_xQI'),
            array('order_id' => '1000330971','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjhVJec33qQDomfAVBPPhIxE'),
            array('order_id' => '1000331059','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjpTCDV7rRpx3bZTKA2vCnPk'),
            array('order_id' => '1000331114','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjhVJec33qQDomfAVBPPhIxE'),
            array('order_id' => '1000331115','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjrYO5FXZdhxr3ih_R9kghLw'),
            array('order_id' => '1000331267','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjnraKpz_ANSqZawDsEI1j6o'),
            array('order_id' => '1000331270','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjq4sP5ZGRupHYaCBA9AP8tU'),
            array('order_id' => '1000331276','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjnraKpz_ANSqZawDsEI1j6o'),
            array('order_id' => '1000331296','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjlblilY8CKRZ2nmdkfn9JEs'),
            array('order_id' => '1000331301','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjkU8llvBUZZ9oZcmxIIaCG0'),
            array('order_id' => '1000331306','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjlblilY8CKRZ2nmdkfn9JEs'),
            array('order_id' => '1000331327','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjhyoYso-_Z1gBzVSGm31L0k'),
            array('order_id' => '1000331351','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjgQ29VU7CoUdxrLgNrIeoI4'),
            array('order_id' => '1000331356','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjhBm2ZiTUr16-lCXJ2ifzk4'),
            array('order_id' => '1000331363','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjmKOlhxvJAvzPOXozErfEP4'),
            array('order_id' => '1000331368','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjuOdo7Hzhfgq9d5OPzndgBU'),
            array('order_id' => '1000331557','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjqizjfSjV02K9J1RocGsixg'),
            array('order_id' => '1000331593','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjtgOo9u0C8Ak_2jzR01f3Tw'),
            array('order_id' => '1000331674','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjhIjIy3ZLFTNRjBubKxlZXc'),
            array('order_id' => '1000331917','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjgztwuwEi0lg8Y5QH-RDpmc'),
            array('order_id' => '1000332007','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjgcV3_EwIYm_eDkFLyuJ8kk'),
            array('order_id' => '1000332011','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjsxmE2j2gymdEyj2xRHhgdM'),
            array('order_id' => '1000332014','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjoW_H-9SHbOf--9wINl0-00'),
            array('order_id' => '1000332154','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjrJImGx4Oowsrw80iHWO5Ac'),
            array('order_id' => '1000332197','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjnLGIZui7rJ6XfDDaIHkFfo'),
            array('order_id' => '1000332262','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjqjtO-p3wi38LsJbGRxFxRU'),
            array('order_id' => '1000332310','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjlM2XL0rgfj6S7EJE-0obz0'),
            array('order_id' => '1000332348','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjs2fYZ1SI8JHC69ckh2irKY'),
            array('order_id' => '1000332388','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjurmElxrQkzsREWeYG2_uz4'),
            array('order_id' => '1000332418','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjscb0RfTve52iXcsc-PteTc'),
            array('order_id' => '1000332437','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjlCxXJJWO9MQTskcgEyqcZI'),
            array('order_id' => '1000332475','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjl_hqoM-Hgji-SbpbGxdmgQ'),
            array('order_id' => '1000332477','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjl_hqoM-Hgji-SbpbGxdmgQ'),
            array('order_id' => '1000332496','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjgnC-tgI1p_vHdIBAI2QKH8'),
            array('order_id' => '1000332806','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjkEXLZWo-_zt6jSnUSimWV0'),
            array('order_id' => '1000332843','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjkSq9KNVGmyeSD_wmcQAoBE'),
            array('order_id' => '1000332932','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjgztwuwEi0lg8Y5QH-RDpmc'),
            array('order_id' => '1000332987','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjrFov6R1yzwnwCaQ8kxs3vw'),
            array('order_id' => '1000332991','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjvjzvvGxPoRC0GZz9FfkBnA'),
            array('order_id' => '1000333023','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjsqhO0jD4Zd6MBeyNmDZ0hI'),
            array('order_id' => '1000333050','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjs2zoywdhthV74VMO5itp5s'),
            array('order_id' => '1000333180','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjkCRVl42zln61kEyPhf8OXA'),
            array('order_id' => '1000333386','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjp-I-kw_ZqzAFqrC_cW_xQI'),
            array('order_id' => '1000333444','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjlHYujFNBkvrQO5tgJQtknM'),
            array('order_id' => '1000333484','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjtljSpqbcpWAYh9cRTZV0QM'),
            array('order_id' => '1000333587','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjrumlP-xM4geEVU9H53lxwY'),
            array('order_id' => '1000333615','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjlDRTXs_6hZI68k2firivRQ'),
            array('order_id' => '1000333671','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjtTms5DEwjUOzzJV2f3EbtY'),
            array('order_id' => '1000333696','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjo17lxDOFFl3I_ZJL3uqEfk'),
            array('order_id' => '1000333729','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjqZfM5YpYhmHUi0mQC6WSBw'),
            array('order_id' => '1000333855','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjm3O-OYdTRC1YMb5F7qbrMo'),
            array('order_id' => '1000333864','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjlHYujFNBkvrQO5tgJQtknM'),
            array('order_id' => '1000333868','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjlHYujFNBkvrQO5tgJQtknM'),
            array('order_id' => '1000333880','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjvlqE2B6ErzUBWYo2OPO8UE'),
            array('order_id' => '1000334017','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjkx-lAm-YyBh0J_312x36_g'),
            array('order_id' => '1000334050','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjpJp4IXBu1lPFFdglP2TLas'),
            array('order_id' => '1000334078','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjptoEceipeJG-Yp9wstcyzI'),
            array('order_id' => '1000334115','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjq6Q1EEztDRL-uYhGCds8vg'),
            array('order_id' => '1000334174','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjvJxZU1WfaaFLFGKz-6aI84'),
            array('order_id' => '1000334290','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjj6fMFx3bBXUVi88I2x0GrE'),
            array('order_id' => '1000334312','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjpN4dVRDarGdeO5Y9cJIbvg'),
            array('order_id' => '1000334329','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjrQvlPoSTao0fZJSWaAfU_E'),
            array('order_id' => '1000334344','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjsJQOHZZPJVNMntJ9z50ipg'),
            array('order_id' => '1000334362','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjluYKMOd4GHZ8Nho4aRPncs'),
            array('order_id' => '1000334373','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjt8RiI5BHEBpCcsuORfxGpE'),
            array('order_id' => '1000334383','expiration_date' => '2018-02-08 21:07:14','openid' => 'o7B6cjoCB_EhyVbTRZ2tbTH9EHOo'),
            array('order_id' => '1000334385','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjoCB_EhyVbTRZ2tbTH9EHOo'),
            array('order_id' => '1000334405','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjopd56J6tDWknRmeS7wRKV0'),
            array('order_id' => '1000334431','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjvjTpYXBAy1bmJMYeAMBeNI'),
            array('order_id' => '1000334433','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjvvWtSclk3HxUlRqm6-TTyU'),
            array('order_id' => '1000334483','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjhIjIy3ZLFTNRjBubKxlZXc'),
            array('order_id' => '1000334488','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjupUSptcTqQnfVQ4Lz-vliM'),
            array('order_id' => '1000334510','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjhLbTR84QsX0zbQt86RZ71s'),
            array('order_id' => '1000334518','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjiRlHaBjTIBF7u85KLrku1k'),
            array('order_id' => '1000334528','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjqR3fyghQelKZlBcZ7QsYzk'),
            array('order_id' => '1000334542','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjutJGY9LcW04s6vnUkNwkHg'),
            array('order_id' => '1000334604','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjmJORAZYUOdg3vrkT0s_KYo'),
            array('order_id' => '1000334609','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjjK-PfHY3KsVZAJpYdV-5Iw'),
            array('order_id' => '1000334634','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjmxJG14xARGxZEGIyERCnVs'),
            array('order_id' => '1000334639','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjtuGfRa53r_nKSLTIZR_CzI'),
            array('order_id' => '1000334643','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjsgyk16TQ689tecq_x0ttsc'),
            array('order_id' => '1000334644','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjv3nFU26CH7JuDuUjygkyzA'),
            array('order_id' => '1000334655','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjlzvHYEh3sT4SZwU3SQaCG8'),
            array('order_id' => '1000334670','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjnBoQHYSwCZTbSifff8mz2k'),
            array('order_id' => '1000334684','expiration_date' => '2017-10-31 23:59:59','openid' => 'o7B6cjhVNbFiIkiA8_fS6YS_k2q4'),
            array('order_id' => '1000360814','expiration_date' => '2017-09-24 10:01:11','openid' => 'o7B6cjmJIdTUf6ngWPncsltIJsAs'),
            array('order_id' => '1000360816','expiration_date' => '2017-09-24 10:01:14','openid' => 'o7B6cjmQZo301ET70HPsUyFReWWk'),
            array('order_id' => '1000377487','expiration_date' => '2017-10-04 23:59:59','openid' => 'o7B6cjiBIgT-WOBUrc-PMUK458MA')
        );
        // 1000331591//
        $inter_id = 'a501733480';
        $template_id = 'swxh6IRg24ET70J-un2k0o74MU6yMPX2zhg-ZICZ958';
        $file = $this->_basic_path . 'lghgjr_0908.csv';

        $data['template_id'] = $template_id;
        $data['url'] = 'http://assist.iwide.cn/index.php/soma/order/my_order_list?id=a501733480';
        $data['topcolor'] = '#000000';

        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($orderArrInfo as $key=>$val) {
            //动态模板内容
            $subdata['first'] = array(
                'value' => '您的券即将到期',
                'color' => '#000000'
            );
            $subdata['keyword1'] = array(
                'value' => $val['order_id'],
                'color' => '#000000'
            );
            $subdata['keyword2'] = array(
                'value' => '',
                'color' => '#000000'
            );
            $subdata['keyword3'] = array(
                'value' => $val['expiration_date'],
                'color' => '#000000'
            );
            $subdata['remark'] = array(
                'value' => '点击立即使用',
                'color' => '#000000'
            );
            $data['data'] = $subdata;


            $redis_key = $base_key . $val['openid'];
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $val['openid'];
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';
    }

	/**
	 * 烟台万达文华酒店
	 */
	public function ytwdwh_booking_suceess()
	{
		// 1000322982
		$inter_id = 'a503998048';
		$template_id = 'RhKVOACzhoPfWttuAnM9AyQA1nbuj2fLV_00auElYbA';
		$file = $this->_basic_path . 'ytwdwh_booking_suceess.csv';

		$data['template_id'] = $template_id;
		$data['url'] = 'http://assist.iwide.cn/index.php/soma/package/index?id=a503998048&saler=290';
		$data['topcolor'] = '#000000';
		$subdata['first'] = array(
			'value' => '您已成功预约烟台万达文华酒店开业三周年微信商城促销活动！',
			'color' => '#000000'
		);
		$subdata['keyword1'] = array(
			'value' => '微信商城首促活动',
			'color' => '#000000'
		);
		$subdata['keyword2'] = array(
			'value' => '',
			'color' => '#000000'
		);
		$subdata['remark'] = array(
			'value' => '活动倒计时14小时！错过本次再等一年！点击详情进入购买',
			'color' => '#000000'
		);
		$data['data'] = $subdata;

		$openids = $this->get_target_openids_from_csv($inter_id, $file);
		// 已下单openid
		$orderOpenidsData = $this->soma_db_conn_read
			->distinct()
			->select('openid')
			->where('inter_id', 'a503998048')
			->from('iwide_soma_sales_order_1001')
			->get()
			->result_array();
		$orderOpenids = array_column($orderOpenidsData, 'openid');
		// 没下单openid
		$openids = array_diff($openids, $orderOpenids);

		$this->load->model('soma/Message_wxtemp_template_model', 't_model');
		$base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
		foreach ($openids as $openid) {
			$redis_key = $base_key . $openid;
			if($this->_redis->exists($redis_key))
			{
				continue;
			}
			$data['touser'] = $openid;
			$res = $this->t_model->send_template(json_encode($data), $inter_id);
			$this->_redis->set($redis_key, json_encode($res));
			echo $openid . PHP_EOL; 
		}
		echo 'success';
	}
	
	
	  /**
     * Wuqd 2017-09-11
     * 手动发送微信模板消息
     * 成都世纪城洲际大饭店
     */
    public function sjczj_0911()
    {

        $orderArrInfo = array(
            array('order_id' => '1000212116','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_BbtCYJMSjJOJCVrylN8sQ','create_time' => '2017-06-17 23:55:48'),
            array('order_id' => '1000212149','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtxcxraE-DNZoaybnhEEu5t0','create_time' => '2017-06-18 00:01:00'),
            array('order_id' => '1000212152','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt9HKG9Ze9fK5NMURycQ5fo8','create_time' => '2017-06-18 00:01:09'),
            array('order_id' => '1000212153','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt9mnOXQbokBejRBIXK3AQIo','create_time' => '2017-06-18 00:01:09'),
            array('order_id' => '1000212161','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt6VJX7xIM4fezqJPjDo5N9o','create_time' => '2017-06-18 00:01:15'),
            array('order_id' => '1000212164','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt1xYUN6BzHZPBqegdZeEjp4','create_time' => '2017-06-18 00:01:17'),
            array('order_id' => '1000212165','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtwjzVvkyz_ja-3mEOacOJ9g','create_time' => '2017-06-18 00:01:17'),
            array('order_id' => '1000212169','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtzlt38L3nPVoNs0IaPT3JSI','create_time' => '2017-06-18 00:01:20'),
            array('order_id' => '1000212173','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_6iULTv1c-rshpu-es7VEQ','create_time' => '2017-06-18 00:01:24'),
            array('order_id' => '1000212174','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtznMVgihEZlILMvJx7YKrLo','create_time' => '2017-06-18 00:01:24'),
            array('order_id' => '1000212183','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt1_f8L8uJIdQKka8zJkHqGU','create_time' => '2017-06-18 00:01:29'),
            array('order_id' => '1000212184','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtwjOa937SW2MYDHQUGN4_vo','create_time' => '2017-06-18 00:01:29'),
            array('order_id' => '1000212185','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_G8MDfHQrKfT2J06Ow4Dvc','create_time' => '2017-06-18 00:01:30'),
            array('order_id' => '1000212187','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt0paFaV-2GrcKmzu3YTxuaw','create_time' => '2017-06-18 00:01:31'),
            array('order_id' => '1000212188','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtzDornzz2bdG7LfCyVugkzg','create_time' => '2017-06-18 00:01:32'),
            array('order_id' => '1000212190','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt29M1H2jx3IS_HExzJumTSs','create_time' => '2017-06-18 00:01:34'),
            array('order_id' => '1000212193','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt31A95ucaQDtiWJVbqJKORk','create_time' => '2017-06-18 00:01:38'),
            array('order_id' => '1000212199','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtw6_PEv3wX1-xjmOf2K8tWI','create_time' => '2017-06-18 00:01:44'),
            array('order_id' => '1000212201','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_wMEXFmnRQmHO3ibjYm59o','create_time' => '2017-06-18 00:01:45'),
            array('order_id' => '1000212202','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt3yybYG189I642S1asDxFtg','create_time' => '2017-06-18 00:01:46'),
            array('order_id' => '1000212207','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt3SbBbUxgO6hMJIo496qf-Q','create_time' => '2017-06-18 00:01:52'),
            array('order_id' => '1000212212','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_mV8Sl2-xNew10kFLW2dH8','create_time' => '2017-06-18 00:01:58'),
            array('order_id' => '1000212218','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt1hi33ZzKPEeOjqA8sQIglE','create_time' => '2017-06-18 00:02:00'),
            array('order_id' => '1000212219','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt022ea8lTJyzMVdxIPr51tg','create_time' => '2017-06-18 00:02:01'),
            array('order_id' => '1000212220','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtwwk2N1oKt63SRJBBCFgEcA','create_time' => '2017-06-18 00:02:02'),
            array('order_id' => '1000212225','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt2k2vrmtSROygjkirUy5I-M','create_time' => '2017-06-18 00:02:14'),
            array('order_id' => '1000212227','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtznMVgihEZlILMvJx7YKrLo','create_time' => '2017-06-18 00:02:15'),
            array('order_id' => '1000212228','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_6wKIisZ660GR_j5bPcPHY','create_time' => '2017-06-18 00:02:17'),
            array('order_id' => '1000212231','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt4Ou71vTIv4JmimEYafc-0I','create_time' => '2017-06-18 00:02:19'),
            array('order_id' => '1000212233','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt798x0Hy8OpEQJ8TrcjQ4Fo','create_time' => '2017-06-18 00:02:22'),
            array('order_id' => '1000212238','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_qAcTg9YPDKsL1_HuF1Ea4','create_time' => '2017-06-18 00:02:24'),
            array('order_id' => '1000212243','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtzughisFS1qW6byFCZ__3g0','create_time' => '2017-06-18 00:02:32'),
            array('order_id' => '1000212245','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt07LQRHZnw7ULvCwmfFTJj4','create_time' => '2017-06-18 00:02:36'),
            array('order_id' => '1000212247','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt8jXSUDR6dHQFBJWRSqJie8','create_time' => '2017-06-18 00:02:40'),
            array('order_id' => '1000212249','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt8r_PglABP3k0j1IoK1QSz8','create_time' => '2017-06-18 00:02:43'),
            array('order_id' => '1000212251','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_QdIGWX5vbtxNXhyR3MUgs','create_time' => '2017-06-18 00:02:45'),
            array('order_id' => '1000212252','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-p4enjLkuDP8dwpaZwMXz0','create_time' => '2017-06-18 00:02:46'),
            array('order_id' => '1000212259','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt7vVJpZ0nhxSpC8jp0wCEZ0','create_time' => '2017-06-18 00:02:51'),
            array('order_id' => '1000212267','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt5QQXPNYOuo9JjEprmdkZjs','create_time' => '2017-06-18 00:03:20'),
            array('order_id' => '1000212269','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt9dTCZkWltBfoLuvTVXKOUk','create_time' => '2017-06-18 00:03:24'),
            array('order_id' => '1000212274','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt94N01QG3nAHDQnRRaTvCfs','create_time' => '2017-06-18 00:03:46'),
            array('order_id' => '1000212278','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt6QrwRjGjtIZzk9BEbA5X_M','create_time' => '2017-06-18 00:04:03'),
            array('order_id' => '1000212286','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt3Quky18ar353wbt8-4ZcTg','create_time' => '2017-06-18 00:04:31'),
            array('order_id' => '1000212287','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_yGTU8rEn1gL70_R8ON19A','create_time' => '2017-06-18 00:04:33'),
            array('order_id' => '1000212292','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt4JjGEycQKFRe0HmH5Bxp4s','create_time' => '2017-06-18 00:04:46'),
            array('order_id' => '1000212303','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_GGilUc9DDhxhl6cOxyS9A','create_time' => '2017-06-18 00:05:36'),
            array('order_id' => '1000212307','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtwjOa937SW2MYDHQUGN4_vo','create_time' => '2017-06-18 00:05:49'),
            array('order_id' => '1000212308','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-tAkopO7YjWwNDv0Bxz1j8','create_time' => '2017-06-18 00:05:58'),
            array('order_id' => '1000212311','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtwOpFXoBpiyKxjIvQMEuo8E','create_time' => '2017-06-18 00:06:06'),
            array('order_id' => '1000212314','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_BbtCYJMSjJOJCVrylN8sQ','create_time' => '2017-06-18 00:06:20'),
            array('order_id' => '1000212316','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_GGilUc9DDhxhl6cOxyS9A','create_time' => '2017-06-18 00:06:36'),
            array('order_id' => '1000212320','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt6WLfggTE0K3d7iZlGQEHdU','create_time' => '2017-06-18 00:06:54'),
            array('order_id' => '1000212324','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtwu5KJkbicgb5_uiOTYFgmc','create_time' => '2017-06-18 00:07:13'),
            array('order_id' => '1000212325','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt7xfNedG8sU4ksuhGYOx1j4','create_time' => '2017-06-18 00:07:14'),
            array('order_id' => '1000212334','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtweUEBaY049npLocCZOCfS8','create_time' => '2017-06-18 00:08:21'),
            array('order_id' => '1000212337','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt6BB9BiPb0M4TATIwBg-25Y','create_time' => '2017-06-18 00:08:36'),
            array('order_id' => '1000212339','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt6B2T92jWqV8FN0Ek4BTZSE','create_time' => '2017-06-18 00:08:38'),
            array('order_id' => '1000212341','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-Gq0iOrVPzUZ1kwg3RA-VU','create_time' => '2017-06-18 00:08:51'),
            array('order_id' => '1000212342','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt7xfNedG8sU4ksuhGYOx1j4','create_time' => '2017-06-18 00:08:52'),
            array('order_id' => '1000212350','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt07nwHo7jET3XvQNt96PCvw','create_time' => '2017-06-18 00:09:45'),
            array('order_id' => '1000212351','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt11pqZVPigrHI_guso625dA','create_time' => '2017-06-18 00:10:06'),
            array('order_id' => '1000212354','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt7yaoUyoRtFu7N-jB2vTR0I','create_time' => '2017-06-18 00:10:22'),
            array('order_id' => '1000212362','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt44CkNOuq2CV19DZhTpnDhs','create_time' => '2017-06-18 00:11:09'),
            array('order_id' => '1000212369','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt9gWuSOaUwPiduK5MI6rBpk','create_time' => '2017-06-18 00:11:58'),
            array('order_id' => '1000212372','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_Skt72AhdjNdCpFJIeT7gI','create_time' => '2017-06-18 00:12:22'),
            array('order_id' => '1000212375','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtxsZzWVpHVlynMRQiaqYuFU','create_time' => '2017-06-18 00:12:33'),
            array('order_id' => '1000212377','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt1oYenI4m8okY4EzoWh6wRQ','create_time' => '2017-06-18 00:12:36'),
            array('order_id' => '1000212381','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-1eBH1_htzZKZjvqAyMIDw','create_time' => '2017-06-18 00:12:56'),
            array('order_id' => '1000212386','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt25sjMHuCvPb46hCzdlApEo','create_time' => '2017-06-18 00:14:02'),
            array('order_id' => '1000212390','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt8Fym9Ry2aXu3F38nG4tMtc','create_time' => '2017-06-18 00:14:22'),
            array('order_id' => '1000212395','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt0xHWf4TZ62SJwhoL2VM3iE','create_time' => '2017-06-18 00:15:14'),
            array('order_id' => '1000212402','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-wcnTB-5-jKQzVOjoJR8dw','create_time' => '2017-06-18 00:16:24'),
            array('order_id' => '1000212404','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt5KjzlVbDZ12lYlW0_1gg5E','create_time' => '2017-06-18 00:16:48'),
            array('order_id' => '1000212410','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt5R315UI5wWxMFoLPWmkf50','create_time' => '2017-06-18 00:17:40'),
            array('order_id' => '1000212411','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_oatBwSD-KPG4GrrfJF-Ew','create_time' => '2017-06-18 00:18:20'),
            array('order_id' => '1000212414','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt3eBGmMTR5gB7FB6AGDKvkw','create_time' => '2017-06-18 00:18:27'),
            array('order_id' => '1000212421','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt9CzfROqaA6znmfGpkcKsGY','create_time' => '2017-06-18 00:20:03'),
            array('order_id' => '1000212422','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt2Cu5hZDUTm3ojFrybbL3XA','create_time' => '2017-06-18 00:20:10'),
            array('order_id' => '1000212425','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtykknYzSffXtUpl1AXe76SM','create_time' => '2017-06-18 00:20:30'),
            array('order_id' => '1000212427','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_IM70oFWrcWsf9D9h80iIM','create_time' => '2017-06-18 00:20:53'),
            array('order_id' => '1000212428','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt4qSdZMOSK6FQ653NX4dodI','create_time' => '2017-06-18 00:20:54'),
            array('order_id' => '1000212434','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtyvQZw6GfMn3t2kKnTOt1uo','create_time' => '2017-06-18 00:22:35'),
            array('order_id' => '1000212437','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt0-NfcxE60gwGgXa7fsRl0E','create_time' => '2017-06-18 00:22:59'),
            array('order_id' => '1000212438','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtxRd8gGdXTj7CqnnrfzWjUE','create_time' => '2017-06-18 00:23:01'),
            array('order_id' => '1000212439','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt6FySSy_LSIhd2UIy-611j0','create_time' => '2017-06-18 00:23:11'),
            array('order_id' => '1000212440','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt6OvLMbJzdtVt0aJ6Ui7Y8c','create_time' => '2017-06-18 00:23:18'),
            array('order_id' => '1000212441','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-cab1_sRnPOTqli8CQHU1k','create_time' => '2017-06-18 00:23:53'),
            array('order_id' => '1000212455','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt1G84iKk3Trur1cm08bD60M','create_time' => '2017-06-18 00:27:08'),
            array('order_id' => '1000212458','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtyi9w_XTYSEzPPrqQIRYMg0','create_time' => '2017-06-18 00:27:22'),
            array('order_id' => '1000212464','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt596VIP_hr-zxnRIWn2MC8I','create_time' => '2017-06-18 00:28:23'),
            array('order_id' => '1000212468','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtx_oLPmleLH_MmkxV8o40W8','create_time' => '2017-06-18 00:29:06'),
            array('order_id' => '1000212470','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-cab1_sRnPOTqli8CQHU1k','create_time' => '2017-06-18 00:30:12'),
            array('order_id' => '1000212474','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt10ZvFNJA1CSn9FsHEe85Ik','create_time' => '2017-06-18 00:31:33'),
            array('order_id' => '1000212481','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtxXjsWJB0wOc-y4qdJgy4Zs','create_time' => '2017-06-18 00:36:15'),
            array('order_id' => '1000212490','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtw6qBD-wHJzizyTLzCV8Cg8','create_time' => '2017-06-18 00:40:32'),
            array('order_id' => '1000212491','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtwRXcq5tVjbh1MIhHzMzJ_I','create_time' => '2017-06-18 00:41:11'),
            array('order_id' => '1000212499','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQty087YDhfuKqzEaTy11ZFSY','create_time' => '2017-06-18 00:44:35'),
            array('order_id' => '1000212501','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt9Ob9S6SCCLf2iD4zeSrMJo','create_time' => '2017-06-18 00:45:40'),
            array('order_id' => '1000212507','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt55eMq3sVR3-b9TkYI8kQvQ','create_time' => '2017-06-18 00:48:12'),
            array('order_id' => '1000212508','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt3p__TorIU1sefo0GVYTXWI','create_time' => '2017-06-18 00:49:03'),
            array('order_id' => '1000212518','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtyRPlgUf5R5L7phznwlBesc','create_time' => '2017-06-18 00:52:58'),
            array('order_id' => '1000212525','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtxGjFVeYCdg3HAIpbJg7ZYw','create_time' => '2017-06-18 00:55:11'),
            array('order_id' => '1000212539','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt0AFsiZ1LW6IwOpTj6TtBXo','create_time' => '2017-06-18 01:00:47'),
            array('order_id' => '1000212546','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt5T33G7eP4XpA2SqsOzR6uw','create_time' => '2017-06-18 01:06:17'),
            array('order_id' => '1000212548','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtwayrZ6ZzbHuvLFCQa8SAO8','create_time' => '2017-06-18 01:07:54'),
            array('order_id' => '1000212550','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt3RUWCFaiypzmdGboX5sRcI','create_time' => '2017-06-18 01:09:39'),
            array('order_id' => '1000212557','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_RBbT5TyF-r8-V5wx43l5I','create_time' => '2017-06-18 01:15:24'),
            array('order_id' => '1000212565','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt9KofpyJk6_EbbU8eAg6cJE','create_time' => '2017-06-18 01:20:54'),
            array('order_id' => '1000212576','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt1T0aGr2YLnPhzrMwTmgt6Y','create_time' => '2017-06-18 01:32:41'),
            array('order_id' => '1000212580','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtxvmWa49uDrRD_0njfM8aiE','create_time' => '2017-06-18 01:35:31'),
            array('order_id' => '1000212582','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtxWxxEvfvpYdr0GrWZHCSBE','create_time' => '2017-06-18 01:43:54'),
            array('order_id' => '1000212585','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt3DSTAPckWucJ6MHc47dhhA','create_time' => '2017-06-18 01:45:32'),
            array('order_id' => '1000212587','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt1qWS3K2752P4N4lRkkSxkI','create_time' => '2017-06-18 01:49:45'),
            array('order_id' => '1000212589','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt3X_gs1XswxbgLbfBEMxv88','create_time' => '2017-06-18 01:52:18'),
            array('order_id' => '1000212590','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt3X_gs1XswxbgLbfBEMxv88','create_time' => '2017-06-18 01:53:25'),
            array('order_id' => '1000212595','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt3V8V-wnTztot80RgDs_VtE','create_time' => '2017-06-18 02:00:47'),
            array('order_id' => '1000212600','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtx2ycqkTre5_96Hosd3bgbU','create_time' => '2017-06-18 02:07:22'),
            array('order_id' => '1000212603','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt3TimdeG0zMf0CfeddgCtq4','create_time' => '2017-06-18 02:14:20'),
            array('order_id' => '1000212608','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt9PNOPsO9fHeS_6dvYPiszQ','create_time' => '2017-06-18 02:25:24'),
            array('order_id' => '1000212613','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt2X_3jTnqjHusG-_mOmZhJ4','create_time' => '2017-06-18 02:39:09'),
            array('order_id' => '1000212617','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt3JhHfI-y1FXrfwcz8uQmPA','create_time' => '2017-06-18 02:42:00'),
            array('order_id' => '1000212623','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtw_f-ECqN3kg4R2NtBPFP6M','create_time' => '2017-06-18 02:49:52'),
            array('order_id' => '1000212628','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_JnvnuyRh7KPGzY0krz6b0','create_time' => '2017-06-18 03:09:56'),
            array('order_id' => '1000212634','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt6QvWW-dReuRy5T82suXhhw','create_time' => '2017-06-18 03:33:57'),
            array('order_id' => '1000212640','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt2wfEi8g13_wZRW1xcgp_bw','create_time' => '2017-06-18 04:27:35'),
            array('order_id' => '1000212641','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt1bFypMVQJMwupm_1VkfaJ0','create_time' => '2017-06-18 04:30:19'),
            array('order_id' => '1000212652','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt5OC5O1MjEuXJ-yilJFOItY','create_time' => '2017-06-18 05:34:52'),
            array('order_id' => '1000212657','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt5k_BnQNv8jwUrB9tkinG4E','create_time' => '2017-06-18 05:56:23'),
            array('order_id' => '1000212659','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt3ekF1KiKc_Z5uTQ3iUCYwU','create_time' => '2017-06-18 06:01:13'),
            array('order_id' => '1000212661','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-FhHjgL853vqNCP7M8lUAA','create_time' => '2017-06-18 06:10:09'),
            array('order_id' => '1000212663','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt2lxbyoFJZrRWnmv4Bcovxc','create_time' => '2017-06-18 06:14:24'),
            array('order_id' => '1000212665','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtzMdywDR8mN6b1EP6wpMQm4','create_time' => '2017-06-18 06:16:13'),
            array('order_id' => '1000212669','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-d71WVnqpve7R6vbRUiqCU','create_time' => '2017-06-18 06:24:35'),
            array('order_id' => '1000212673','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt5LbS9nTBY5e0pCvAjxPCsA','create_time' => '2017-06-18 06:36:18'),
            array('order_id' => '1000212681','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtx9euyw8tKSQu4i63w0iWLI','create_time' => '2017-06-18 06:54:12'),
            array('order_id' => '1000212683','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt3zx-VHFZ50mND-KnAH3lRY','create_time' => '2017-06-18 06:59:09'),
            array('order_id' => '1000212685','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt07hhtjwEY91N9hd-6UsAEM','create_time' => '2017-06-18 07:00:59'),
            array('order_id' => '1000212702','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-KO929YlGxWo6ElnBASrdQ','create_time' => '2017-06-18 07:25:54'),
            array('order_id' => '1000212707','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt5eGcDZYxwRHurrqyrM8Oeg','create_time' => '2017-06-18 07:27:53'),
            array('order_id' => '1000212718','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-0KBTKDhzt3SSGzouy7-0s','create_time' => '2017-06-18 07:37:36'),
            array('order_id' => '1000212722','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt0ysDbhHdBTRDcJBjNAXt0s','create_time' => '2017-06-18 07:41:00'),
            array('order_id' => '1000212726','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt8Ert0c-QpKdL0UneK4hgeg','create_time' => '2017-06-18 07:43:32'),
            array('order_id' => '1000212727','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt0ysDbhHdBTRDcJBjNAXt0s','create_time' => '2017-06-18 07:43:32'),
            array('order_id' => '1000212732','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt5eGcDZYxwRHurrqyrM8Oeg','create_time' => '2017-06-18 07:51:00'),
            array('order_id' => '1000212742','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt8gSlE-CbtTevat-nPrN4-A','create_time' => '2017-06-18 07:59:26'),
            array('order_id' => '1000212743','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt3KDFnZjHkcCd7ZAQNjPlQA','create_time' => '2017-06-18 07:59:27'),
            array('order_id' => '1000212747','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt9wQuCrd2sx1Olaqd4X2KN4','create_time' => '2017-06-18 08:01:08'),
            array('order_id' => '1000212750','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt2mA6J7Y4Mwi7X_Xb3MVsGs','create_time' => '2017-06-18 08:07:14'),
            array('order_id' => '1000212756','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt4-sv9zez1UrzDx4HQ5l-Ls','create_time' => '2017-06-18 08:12:37'),
            array('order_id' => '1000212773','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_XDWiVnSbAOyfJLnaYo-VA','create_time' => '2017-06-18 08:24:01'),
            array('order_id' => '1000212777','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtwmvpzRX3-AvKAYnALCqysQ','create_time' => '2017-06-18 08:25:12'),
            array('order_id' => '1000212781','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt1w9BAm0LsHbTleXfrZwydM','create_time' => '2017-06-18 08:27:03'),
            array('order_id' => '1000212791','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt2wNczoQSvqcBnciOHH5KPU','create_time' => '2017-06-18 08:32:50'),
            array('order_id' => '1000212800','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt9MNl-IBQIzSp1ZAno953DQ','create_time' => '2017-06-18 08:36:14'),
            array('order_id' => '1000212809','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtwe3BeJxG4j6wgsSd0QYJc8','create_time' => '2017-06-18 08:46:42'),
            array('order_id' => '1000212811','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtwe3BeJxG4j6wgsSd0QYJc8','create_time' => '2017-06-18 08:47:18'),
            array('order_id' => '1000212817','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtw8ZNsHXW-LZn2c4t0ZMBm8','create_time' => '2017-06-18 08:50:28'),
            array('order_id' => '1000212822','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-mLJS2jXsKxGLF1PJE4yRU','create_time' => '2017-06-18 08:51:41'),
            array('order_id' => '1000212829','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt5eZbKBesD79lJvHxvaajgg','create_time' => '2017-06-18 08:55:37'),
            array('order_id' => '1000212832','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-DaSHN9H-628MbJAl8G9JY','create_time' => '2017-06-18 08:56:25'),
            array('order_id' => '1000212848','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt8IWlkAjqStBPiIhF5A0N08','create_time' => '2017-06-18 09:02:47'),
            array('order_id' => '1000212856','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQty9-TzVHqbo8C76iYLHIlSg','create_time' => '2017-06-18 09:08:26'),
            array('order_id' => '1000212859','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt2MrsPz-o2H6sS4W1JE2omA','create_time' => '2017-06-18 09:09:11'),
            array('order_id' => '1000212895','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt9JAUm6IZzQ9daJ-RHEHLgw','create_time' => '2017-06-18 09:21:21'),
            array('order_id' => '1000212909','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtzr0dq9azgVMECoSu_F9xRE','create_time' => '2017-06-18 09:25:01'),
            array('order_id' => '1000212917','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtzvDaXMUc7oXW1SALsm8BBQ','create_time' => '2017-06-18 09:28:34'),
            array('order_id' => '1000212920','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt0UsYSGuaE-2a4u8VkI1qmc','create_time' => '2017-06-18 09:30:55'),
            array('order_id' => '1000212962','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt990gKLCaLs87rnNFwr2o3k','create_time' => '2017-06-18 09:46:18'),
            array('order_id' => '1000212964','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtx1kIhkPFiJ_sZfRfFeMZMA','create_time' => '2017-06-18 09:46:52'),
            array('order_id' => '1000212969','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt9D5hXDWcpIJZRNeMdLOxAk','create_time' => '2017-06-18 09:48:52'),
            array('order_id' => '1000212978','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt7iUTD1S2ODjpkcBIiC7m_k','create_time' => '2017-06-18 09:51:36'),
            array('order_id' => '1000212994','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt5bZAGAUZarGaDM9EEdBuDc','create_time' => '2017-06-18 09:55:26'),
            array('order_id' => '1000212996','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt62xGMi93-pdAPUyPdidVnk','create_time' => '2017-06-18 09:55:52'),
            array('order_id' => '1000213001','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtzFY0WkqbMlpHM0r0lsQ5MY','create_time' => '2017-06-18 09:57:41'),
            array('order_id' => '1000213006','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt78Pb4ZOrEcEs1i957EiT6M','create_time' => '2017-06-18 09:58:55'),
            array('order_id' => '1000213009','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt7nFyMjGoM50A5Gy4qEE95c','create_time' => '2017-06-18 09:59:05'),
            array('order_id' => '1000213226','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt97TVIwfEk84_L-KtGPajqg','create_time' => '2017-06-18 10:04:57'),
            array('order_id' => '1000213340','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtxDn3EdV3Vf-PcPFhPON4Po','create_time' => '2017-06-18 10:12:02'),
            array('order_id' => '1000213373','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtxdQgapZALDQk9bh9wQnZMs','create_time' => '2017-06-18 10:14:47'),
            array('order_id' => '1000213383','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtyX08gzSjnfMRcl9GD6sdMQ','create_time' => '2017-06-18 10:16:31'),
            array('order_id' => '1000213430','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt4FeKl3Zhw93LDg6cRpiS4s','create_time' => '2017-06-18 10:22:08'),
            array('order_id' => '1000213455','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt6NFB93awd5WzxqMDv45VjM','create_time' => '2017-06-18 10:25:49'),
            array('order_id' => '1000213497','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-p0aq2S3gPdibki6gPCJuI','create_time' => '2017-06-18 10:30:54'),
            array('order_id' => '1000213544','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt8pStCaE8p4EBvmK2uuP-Wc','create_time' => '2017-06-18 10:38:10'),
            array('order_id' => '1000213650','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtz8FTDxCRsK04_y7bC4qvtY','create_time' => '2017-06-18 11:02:03'),
            array('order_id' => '1000213666','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt9Nl1rPZysvHtLQ6GwSxbHg','create_time' => '2017-06-18 11:04:35'),
            array('order_id' => '1000213678','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt7mISCaDyjmMwtjchjnFaDI','create_time' => '2017-06-18 11:07:03'),
            array('order_id' => '1000213728','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_n1b3xb-8twEntIP2hwDMg','create_time' => '2017-06-18 11:18:48'),
            array('order_id' => '1000213807','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt2BegDEPi6n4QXRriLmUyiM','create_time' => '2017-06-18 11:39:15'),
            array('order_id' => '1000213861','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt83ZB3P4nX1rR8W0cszNui8','create_time' => '2017-06-18 11:49:39'),
            array('order_id' => '1000213877','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt2Oa0vuc1FbZjsHg9nN9Y5U','create_time' => '2017-06-18 11:53:17'),
            array('order_id' => '1000213899','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_lZqzZg6AXN3jyCDNSfdKc','create_time' => '2017-06-18 11:55:14'),
            array('order_id' => '1000213902','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt2Oa0vuc1FbZjsHg9nN9Y5U','create_time' => '2017-06-18 11:56:15'),
            array('order_id' => '1000213906','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_zCieZBS6tB9aPypIbiECc','create_time' => '2017-06-18 11:58:03'),
            array('order_id' => '1000213912','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt8aif5686smBvzf9pRG4i8g','create_time' => '2017-06-18 11:59:20'),
            array('order_id' => '1000213919','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt5h-rxlpLiXjMwMkAolP1ZI','create_time' => '2017-06-18 12:02:01'),
            array('order_id' => '1000213950','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt2_Xgaj2wdeMHyhx59OhEoA','create_time' => '2017-06-18 12:14:13'),
            array('order_id' => '1000213986','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtygaU9vXC0lOBjyA8DoTHdI','create_time' => '2017-06-18 12:20:37'),
            array('order_id' => '1000213992','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_O21kGeXw6OVdSfleD39Jw','create_time' => '2017-06-18 12:21:22'),
            array('order_id' => '1000213994','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt990gKLCaLs87rnNFwr2o3k','create_time' => '2017-06-18 12:21:53'),
            array('order_id' => '1000214024','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt5GxaqhnsnzOxzfSKthU6vg','create_time' => '2017-06-18 12:29:00'),
            array('order_id' => '1000214079','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt6uuT7XQcgzolHAo8AReHKE','create_time' => '2017-06-18 12:41:34'),
            array('order_id' => '1000214084','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtzE00bMIwh2n2Gpmi5VKMjM','create_time' => '2017-06-18 12:42:11'),
            array('order_id' => '1000214086','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt6RiejnL3uCVlor_z8lDTA0','create_time' => '2017-06-18 12:42:28'),
            array('order_id' => '1000214147','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt9e2qMUm6qMsDfXdQD0i-us','create_time' => '2017-06-18 12:53:58'),
            array('order_id' => '1000214187','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtx7v4o6zLiZcWGCDM1j-tP0','create_time' => '2017-06-18 13:06:45'),
            array('order_id' => '1000214233','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt4BVNbj3M-FTAkUzHXWRPMg','create_time' => '2017-06-18 13:19:08'),
            array('order_id' => '1000214285','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt8toYk7pPQOgRsHtCEZn85c','create_time' => '2017-06-18 13:32:32'),
            array('order_id' => '1000214305','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtwxybkfOFcrXAW-WoLfB2ME','create_time' => '2017-06-18 13:40:39'),
            array('order_id' => '1000214306','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt7TkmZYjlvHggbhTuQKYYUA','create_time' => '2017-06-18 13:41:02'),
            array('order_id' => '1000214309','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt7Rophyf7-SSzaLvV7MKo2w','create_time' => '2017-06-18 13:44:20'),
            array('order_id' => '1000214322','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt8w-TBW7tp7L9n7p8JHN0Nk','create_time' => '2017-06-18 13:49:34'),
            array('order_id' => '1000214339','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_B2JHUEdkxH-BcakhpLy-o','create_time' => '2017-06-18 13:54:24'),
            array('order_id' => '1000214341','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-HKKcepbGTMJqRfok8gugg','create_time' => '2017-06-18 13:55:00'),
            array('order_id' => '1000214343','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt6hVEMH2-Oneg5-Cnug-5xo','create_time' => '2017-06-18 13:55:55'),
            array('order_id' => '1000214375','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-si3W7cqK_iJnRECNTXlX4','create_time' => '2017-06-18 14:15:14'),
            array('order_id' => '1000214426','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt6LJwy2Lfnq2qyyeENpiTOI','create_time' => '2017-06-18 14:42:44'),
            array('order_id' => '1000214455','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt39bFWqEeJgoi83qgy_2Zcs','create_time' => '2017-06-18 14:56:36'),
            array('order_id' => '1000214465','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt39bFWqEeJgoi83qgy_2Zcs','create_time' => '2017-06-18 14:58:36'),
            array('order_id' => '1000214486','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_ObxOWzS69ZjbYq9NwCoOs','create_time' => '2017-06-18 15:12:49'),
            array('order_id' => '1000214487','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt49ncEWzdXijhxSpjpOJ6Ks','create_time' => '2017-06-18 15:13:02'),
            array('order_id' => '1000214497','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtxkY5cepclPDGLFZB0IlswA','create_time' => '2017-06-18 15:20:46'),
            array('order_id' => '1000214499','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_xaZnjAaqDw4ORVrCFpTv4','create_time' => '2017-06-18 15:22:19'),
            array('order_id' => '1000214517','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt7LTUu60VFjuh3KZVa6PUJ0','create_time' => '2017-06-18 15:37:00'),
            array('order_id' => '1000214538','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt385x4mjSXPozo1q9gMAXUo','create_time' => '2017-06-18 15:47:07'),
            array('order_id' => '1000214547','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_nRwVPILCP0U66SzqqkTTY','create_time' => '2017-06-18 15:54:16'),
            array('order_id' => '1000214555','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt9hvUDUcY3C1NpqFYidaGwQ','create_time' => '2017-06-18 15:58:46'),
            array('order_id' => '1000214597','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt0j7i1IpKO7pxHeJAu7mZPA','create_time' => '2017-06-18 16:19:19'),
            array('order_id' => '1000214606','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt9leFBqf8NJAb9P2AAnQH74','create_time' => '2017-06-18 16:22:21'),
            array('order_id' => '1000214653','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt2YRBIsubZOh41vRvPQ2V6s','create_time' => '2017-06-18 16:45:42'),
            array('order_id' => '1000214659','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt4mv3OW92l9c8-lb3qiV5eE','create_time' => '2017-06-18 16:47:49'),
            array('order_id' => '1000214712','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt0P4ERpQAC9SpDSKvDhxcUo','create_time' => '2017-06-18 17:12:53'),
            array('order_id' => '1000214766','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-VR9SjT6EdefJAaZLK_FXY','create_time' => '2017-06-18 17:25:55'),
            array('order_id' => '1000214877','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-FYpBeyCO9F84anGTC_7cY','create_time' => '2017-06-18 18:10:49'),
            array('order_id' => '1000214917','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtzOBee9dTt1iV-bHta-QkIQ','create_time' => '2017-06-18 18:25:26'),
            array('order_id' => '1000214921','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt4vWSgofm6xrxd3Ab4-y5yA','create_time' => '2017-06-18 18:26:24'),
            array('order_id' => '1000214925','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt1sxeXuPPwdKhJhTm9-h1SM','create_time' => '2017-06-18 18:27:05'),
            array('order_id' => '1000214930','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt87ZNbg9PLar1_bWA_Vv8K0','create_time' => '2017-06-18 18:27:46'),
            array('order_id' => '1000214988','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt3LCXj3Ty82aYxbAqYCNPEE','create_time' => '2017-06-18 18:31:03'),
            array('order_id' => '1000215014','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_Rb6IduXqDd3wOYgH6CyOU','create_time' => '2017-06-18 18:32:36'),
            array('order_id' => '1000215063','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt9-BOnQdGMfhBEN-RVc3qRY','create_time' => '2017-06-18 18:35:25'),
            array('order_id' => '1000215064','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-jF0uxeRzyeBsk9CYZSlSE','create_time' => '2017-06-18 18:35:25'),
            array('order_id' => '1000215087','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt9a0DnabO-pQM2_LJ7Abypo','create_time' => '2017-06-18 18:37:56'),
            array('order_id' => '1000215092','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt4Q3Z1jbDtUA7GE9QikKIcE','create_time' => '2017-06-18 18:38:34'),
            array('order_id' => '1000215109','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtxLVq21Z_M0ELMolZifT45M','create_time' => '2017-06-18 18:40:53'),
            array('order_id' => '1000215125','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtxbMbYiSTQoW9HLGBo2aq0Y','create_time' => '2017-06-18 18:42:30'),
            array('order_id' => '1000215129','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-HnXuIj-X_fH8Ded2X08Sc','create_time' => '2017-06-18 18:42:45'),
            array('order_id' => '1000215167','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt5X21fWOMqMCbBFUvvAnr0M','create_time' => '2017-06-18 18:47:19'),
            array('order_id' => '1000215176','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt5X21fWOMqMCbBFUvvAnr0M','create_time' => '2017-06-18 18:48:54'),
            array('order_id' => '1000215224','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt8Pn2LsINqfU70pZ_J_Q3q4','create_time' => '2017-06-18 18:55:39'),
            array('order_id' => '1000215249','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtyvCJzGjLb5I_cuY1evDn1E','create_time' => '2017-06-18 18:59:37'),
            array('order_id' => '1000215273','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt1ZKTEGyX0Wfpv6JlwLGoiY','create_time' => '2017-06-18 19:02:22'),
            array('order_id' => '1000215338','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt8u63Bce0r6zQoCxoSbhRZc','create_time' => '2017-06-18 19:16:16'),
            array('order_id' => '1000215344','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtwnTmPqTan1HWOwos2mwyg0','create_time' => '2017-06-18 19:17:54'),
            array('order_id' => '1000215356','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtyFspWuRRIt-DgAae_jOE14','create_time' => '2017-06-18 19:21:06'),
            array('order_id' => '1000215361','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt7DjtxlGtaGOWiRGNj3gmiY','create_time' => '2017-06-18 19:21:53'),
            array('order_id' => '1000215374','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt6FTwVrAgwM-we9downo2ic','create_time' => '2017-06-18 19:25:18'),
            array('order_id' => '1000215377','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtyrxE-HUeLhubYx3bUiGZU4','create_time' => '2017-06-18 19:27:10'),
            array('order_id' => '1000215409','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt8-0Lo2jZ9AXqkyGXPkWxDw','create_time' => '2017-06-18 19:35:04'),
            array('order_id' => '1000215412','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt85Q-q7EfuorBbRa5z6CmDU','create_time' => '2017-06-18 19:35:33'),
            array('order_id' => '1000215441','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt1OaL7z4OV6be0W2W3RlZV0','create_time' => '2017-06-18 19:41:28'),
            array('order_id' => '1000215443','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_wZ8dJWVSdB_H8aTHAcn6k','create_time' => '2017-06-18 19:41:36'),
            array('order_id' => '1000215477','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-wWW3hAVmrHLzpCPvn1yFo','create_time' => '2017-06-18 19:51:49'),
            array('order_id' => '1000215496','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt7waFycVMU1bD7b5XltCMmw','create_time' => '2017-06-18 19:58:46'),
            array('order_id' => '1000215539','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt54uF6ELuokoiwGZFm2viOo','create_time' => '2017-06-18 20:13:08'),
            array('order_id' => '1000215611','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtwlUKrHtCn2BlYxixBoRqjc','create_time' => '2017-06-18 20:39:04'),
            array('order_id' => '1000215632','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt5JXBwk1nfiZTCV1XU2SCDA','create_time' => '2017-06-18 20:47:42'),
            array('order_id' => '1000215634','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtxhMjr2ma45rV1so1laCNUU','create_time' => '2017-06-18 20:48:27'),
            array('order_id' => '1000215643','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtzdpGUt8dvO9jDzersjtTPU','create_time' => '2017-06-18 20:51:07'),
            array('order_id' => '1000215644','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt2-ewYG62Xj2OzU1rWt-kmU','create_time' => '2017-06-18 20:51:13'),
            array('order_id' => '1000215664','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt3ndCc36O2haqQQTv6VyiZk','create_time' => '2017-06-18 20:59:18'),
            array('order_id' => '1000215678','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt9xHG07X-hRFoMNMQVShB8c','create_time' => '2017-06-18 21:08:13'),
            array('order_id' => '1000215707','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt1ICK0wkiE6ve518a6M6RGo','create_time' => '2017-06-18 21:22:33'),
            array('order_id' => '1000215727','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt5t-j5BkkTxVd8FFxwe5GwQ','create_time' => '2017-06-18 21:31:11'),
            array('order_id' => '1000215728','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt3ChifZ_OZUHV0V6EkB5buM','create_time' => '2017-06-18 21:31:26'),
            array('order_id' => '1000215734','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt2ddH7pErlxvm1pf3Jttl8Y','create_time' => '2017-06-18 21:36:42'),
            array('order_id' => '1000215739','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_K6h0iaUxUFX2UURxUxjYQ','create_time' => '2017-06-18 21:38:06'),
            array('order_id' => '1000215749','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt3KH9_kndJlUjT3jFAtcXes','create_time' => '2017-06-18 21:41:21'),
            array('order_id' => '1000215754','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt3ChifZ_OZUHV0V6EkB5buM','create_time' => '2017-06-18 21:44:02'),
            array('order_id' => '1000215764','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt5LW4pmdmGT6Sj31vKlktaU','create_time' => '2017-06-18 21:48:59'),
            array('order_id' => '1000215770','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt6-iryd4QhFTmPNRcVF8Tag','create_time' => '2017-06-18 21:51:46'),
            array('order_id' => '1000215798','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt9wUS1ndYtsfjhVQb_ic0r8','create_time' => '2017-06-18 22:01:28'),
            array('order_id' => '1000215799','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt9wUS1ndYtsfjhVQb_ic0r8','create_time' => '2017-06-18 22:02:13'),
            array('order_id' => '1000215809','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt64iqA1exrILt4-hb0GKqpg','create_time' => '2017-06-18 22:06:17'),
            array('order_id' => '1000215810','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt5ivi_MppcUQQfX21RgggPQ','create_time' => '2017-06-18 22:06:21'),
            array('order_id' => '1000215826','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtyt4nr0shZvJAkgr5VIumO8','create_time' => '2017-06-18 22:12:52'),
            array('order_id' => '1000215842','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtyIjzXb9totPeloW8l45uuk','create_time' => '2017-06-18 22:20:13'),
            array('order_id' => '1000215843','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_G8MDfHQrKfT2J06Ow4Dvc','create_time' => '2017-06-18 22:20:43'),
            array('order_id' => '1000215854','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt4kC4bdCZHtMU7Mfy_SfoE0','create_time' => '2017-06-18 22:23:35'),
            array('order_id' => '1000215861','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt8oA2LIG0kSvRU-1awH5sXo','create_time' => '2017-06-18 22:27:35'),
            array('order_id' => '1000215866','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtywLm1-1SbBhPbCJtGjNXuo','create_time' => '2017-06-18 22:31:45'),
            array('order_id' => '1000215870','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt15x75gVFAByXRYeRfCZpJI','create_time' => '2017-06-18 22:35:00'),
            array('order_id' => '1000215875','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt5KPiWAk9mWyIQhX1QUiusM','create_time' => '2017-06-18 22:38:25'),
            array('order_id' => '1000215888','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt4weRE6DjXZhfqL87gLM3qw','create_time' => '2017-06-18 22:47:48'),
            array('order_id' => '1000215889','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt3C-wNsf49s9nPsLythQPG4','create_time' => '2017-06-18 22:48:24'),
            array('order_id' => '1000215892','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-G2MHwMfdODVuVcQMGRf8w','create_time' => '2017-06-18 22:50:52'),
            array('order_id' => '1000215898','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtwwEGRL6KKG7Ag_EEXauIAk','create_time' => '2017-06-18 22:54:34'),
            array('order_id' => '1000215901','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtwgFIAw8CfoMDRydCA5sVII','create_time' => '2017-06-18 22:56:46'),
            array('order_id' => '1000215911','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt63yyBLh9mm2KgY6RDSqtGM','create_time' => '2017-06-18 23:02:03'),
            array('order_id' => '1000215914','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt7uCOitsIa7VE13eQyvpSTM','create_time' => '2017-06-18 23:02:20'),
            array('order_id' => '1000215932','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQty0Bm1wP2UuIVJjaEK-znC4','create_time' => '2017-06-18 23:09:50'),
            array('order_id' => '1000215934','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt6r6TiK9hNjiWaE9QjnvjFM','create_time' => '2017-06-18 23:11:39'),
            array('order_id' => '1000215952','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt48NqY_8n-YpBqfIC_t0iVQ','create_time' => '2017-06-18 23:22:09'),
            array('order_id' => '1000215954','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt72xoeHvBtXnqyQwlaX99mU','create_time' => '2017-06-18 23:23:30'),
            array('order_id' => '1000215957','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt2K-I5hDOY-kA4v3yA3MM9I','create_time' => '2017-06-18 23:23:54'),
            array('order_id' => '1000215965','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt4IG5_js4-rEdMTgbLQZ6eg','create_time' => '2017-06-18 23:25:50'),
            array('order_id' => '1000215972','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtyh0jS0mJYvtmTly4HkGTAo','create_time' => '2017-06-18 23:31:37'),
            array('order_id' => '1000215973','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt_GA2bD8uqHP8XVnZ2noe6g','create_time' => '2017-06-18 23:31:56'),
            array('order_id' => '1000215974','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQtxnrCF8v64D0SAkRyzUsGrY','create_time' => '2017-06-18 23:32:31'),
            array('order_id' => '1000215985','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt0394aedFRgPyhNuL0nSQ8k','create_time' => '2017-06-18 23:48:03'),
            array('order_id' => '1000215997','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt5_Mq7JN4Yuxb7fyfuf4ltU','create_time' => '2017-06-18 23:53:39'),
            array('order_id' => '1234567890','expiration_date' => '2017-09-30 23:59:59','openid' => 'okXyQt-0B0P8ikyc6a4zemuRWFso','create_time' => '2017-06-18 22:55:28'),
        );
        // 1000331591//
        $inter_id = 'a484122795';
        $template_id = 'BM20NbWAxqv0D-EBD1csiilkMPdRoonm5QHdsjJCvOo';
        $file = $this->_basic_path . 'sjczj_0911.csv';

        $data['template_id'] = $template_id;
        $data['url'] = '';
        $data['topcolor'] = '#000000';

        $this->load->model('soma/Message_wxtemp_template_model', 't_model');
        $base_key = 'Soma_cron_nz:' . date('Y-m-d') . ':' . __FUNCTION__ . ':';
        foreach ($orderArrInfo as $key=>$val) {
            //动态模板内容
            $subdata['first'] = array(
                'value' => '您购买的618活动商品即将在9月18日到期，因会展活动9月11日-16日酒店不可接待已经为您延期至9月30日',
                'color' => '#FF0000'
            );
            $subdata['keyword1'] = array(
                'value' => $val['order_id'],
                'color' => '#000000'
            );
            $subdata['keyword2'] = array(
                'value' => $val['create_time'],
                'color' => '#000000'
            );
            $subdata['keyword3'] = array(
                'value' => $val['expiration_date'],
                'color' => '#000000'
            );
            $subdata['remark'] = array(
                'value' => '请在有效期前使用完毕',
                'color' => '#000000'
            );
            $data['data'] = $subdata;


            $redis_key = $base_key . $val['openid'];
            if($this->_redis->exists($redis_key))
            {
                continue;
            }
            $data['touser'] = $val['openid'];
            $res = $this->t_model->send_template(json_encode($data), $inter_id);
            $this->_redis->set($redis_key, json_encode($res));
        }
        echo 'success';

    }
}