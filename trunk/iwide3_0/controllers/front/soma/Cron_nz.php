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
        // 1000331591//
        $inter_id = 'a484122795';
        $template_id = 'BM20NbWAxqv0D-EBD1csiilkMPdRoonm5QHdsjJCvOo';
        $file = $this->_basic_path . 'sjczj_0911.csv';

        $data['template_id'] = $template_id;
        $data['url'] = '';
        $data['topcolor'] = '#000000';
        $subdata['first'] = array(
            'value' => '您购买的618活动商品即将在9月18日到期，因会展活动9月11日-16日酒店不可接待已经为您延期至9月30日',
            'color' => '#000000'
        );
        $subdata['keyword1'] = array(
            'value' => '',
            'color' => '#000000'
        );
        $subdata['keyword2'] = array(
            'value' => '',
            'color' => '#000000'
        );
        $subdata['remark'] = array(
            'value' => '请在有效期前使用完毕',
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
}