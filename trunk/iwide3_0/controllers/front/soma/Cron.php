<?php
use App\services\soma\CronService;
use App\services\soma\KillsecService;
use App\services\soma\ScopeDiscountService;
use Monolog\Handler\StreamHandler;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Cron计划任务
 *
 * @property Consumer_code_model $consumer_code_model
 * @property Sales_order_model $sales_order_model
 * @property Activity_killsec_model $Activity_killsec_model
 * @property Message_wxtemp_template_model $Message_wxtemp_template_model
 * @property Publics_model $publics
 * @property Shard_config_model $model_shard_config
 */
class Cron extends MY_Controller
{

    /**
     * 第一台灰度发布的机器CI_AB 是100
     */
    const AB = 100;

    /**
     *
     * 这个值来确定是不是灰度发布的机器
     * @var null|int
     */
    private $current_ab;


    /**
     * Cron constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->somaDatabase($this->db_soma);
        $this->load->somaDatabaseRead($this->db_soma_read);

        $this->current_ab = isset($_SERVER['CI_AB']) ? $_SERVER['CI_AB'] : 100;

        $this->load->model('soma/shard_config_model', 'model_shard_config');
        $this->controllerLogHandler(__CLASS__);
    }

    /**
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
    private function _get_abs()
    {
        return array(
            self::AB
        );
    }

    /**
     *
     * 1，灰度机器上只跑指定的公众号
     * 2，生产机器上跑除了灰度机器上跑的公众号
     *
     * @param $inter_id
     * @return bool
     * @author renshuai  <renshuai@mofly.cn>
     */
    private function _is_executable($inter_id)
    {
        $abInterIds = config_item('ab_cron_inter_id_list');
        if (is_null($abInterIds)) {
            return true;
        }

        if (in_array($this->current_ab, $this->_get_abs())) {
            if (in_array($inter_id, $abInterIds)) {
                return true;
            }
            return false;
        } else {
            if (in_array($inter_id, $abInterIds)) {
                return false;
            }
            return true;
        }
    }

    /**
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function ab_test()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__);


        $this->load->library('Redis_selector');
        $redis = $this->redis_selector->get_soma_redis('soma_redis');

        $key = 'soma_killsec2_2923';
        $redis->watch($key);
        $redis->multi();
        $redis->set($key, 4);

        $redis->exec();

    }

    /**
     * 运行日志记录
     *
     * @param String $content
     * @param bool $flag
     * @param string $log_lv
     */
    private function _write_log($content, $flag = false, $log_lv = 'info')
    {
        $handler = new StreamHandler(APPPATH . 'logs/soma/cron/log_' . date('Y-m-d') . '.log', \Monolog\Logger::DEBUG);
        $this->monoLog->setHandlers(array($handler));

        $ip = $this->input->ip_address();

        if (!$flag) {
            $content =  "{$content} starting...";
        }

        $this->monoLog->$log_lv($content, array(
            'ip' => $ip,
        ));
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

    /**
     * 此方法用于检测任务的可否执行。计划任务分来3类：
     * 1 类是可以重复执行的，不加任何限制；
     * 2 类是绝对不能重复执行的，要在执行之前加一个 remote_ip 的判断，只允许某一个服务器触发，其他ip一律不认
     * 3 类是 担心会漏发（这个特许授权服务器ip挂掉了），必须在其他服务器加以保障的，跟第1类的区别是，第1类可以少发无实质性影响
     *
     * @return boolean|void TRUE可执行 false不可执行
     */
    protected function _check_access()
    {
        if (ENVIRONMENT === 'production') {
            $ip_whitelist = array(
                //'10.46.75.203', //test 1
                '10.25.168.86', //redis01
                '10.25.3.85',  //redis02
                '10.27.237.22', // mp
                '10.46.74.165', //crontab
                '10.24.254.116', //灰度发布机器
                '10.25.1.106', //crontab_soma
                '127.0.0.1'
            );
            $client_ip = $this->input->ip_address();
            if (in_array($client_ip, $ip_whitelist)) {
                return true;

            } else {
                $msg = $this->action . ' 拒绝非法IP执行任务！';
                $this->_write_log($msg);
                die($msg);
            }

        } else {
            return true;
        }
    }


    /**
     * 定时执行产生核销码，后改为分配资产时分配新的code
     * 每1小时执行/全量处理
     *
     *
     * 灰度发布不执行！！！！
     */
    public function consume_code_generate()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__);

        $total_qty = 100;   //保持多少数量的可用核销码
        $this->load->model('soma/consumer_code_model');
        $result = $this->consumer_code_model->generate_newcode($total_qty);
        echo $result ? 'SUCCESS' : 'FAIL';
    }

    /**
     * 定时扫描并将未接受的赠礼退回到资产账户
     * 每10分钟执行/处理量20个
     *
     * todo 和公众号有关
     */
    public function gift_auto_rollback()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__);

        $redis = $this->get_redis_instance();
        $lock_key = 'SOMA_CRON:GIFT_AUTO_ROLLBACK_LOCK';
        $lock = $redis->setnx($lock_key, 'lock');
        if (!$lock) {
            $this->_write_log(__FUNCTION__ . ' lock fail!', true, 'error');
            die('FAILURE!');
        }

        $limit = 50;  //每次处理20个
        $this->load->model('soma/gift_order_model');
        $this->load->model('soma/shard_config_model', 'model_shard_config');
        $order_expired = $this->gift_order_model->get_expired_orders($limit);
        $order_unsent  = $this->gift_order_model->getUnsentOrder($limit);

        $order = array();
        foreach ($order_expired as $item) {
            $order[ $item['gift_id'] ] = $item;
        }

        foreach ($order_unsent as $item) {
            if(empty($order[ $item['gift_id'] ])) {
                $item['unsent'] = Soma_base::STATUS_TRUE;
                $order[] = $item;
            }
        }

        //print_r($order);die;
        $result = true;
        if (count($order) > 0) {
            foreach ($order as $k => $v) {
                //初始化数据库分片配置
                if (!$this->current_inter_id || $this->current_inter_id != $v['inter_id']) {
                    $this->current_inter_id = $v['inter_id'];
                    $this->db_shard_config = $this->model_shard_config->build_shard_config($this->current_inter_id);
                }
                $model = $this->gift_order_model->load($v['gift_id']);
                //print_r($v);
                if ($model) {
                    $result = $model->order_rollback($v['business'], $v['inter_id']);

                    //发送模版消息
                    if ($result && empty($v['unsent'])) {
                        /***********************发送模版消息****************************/
                        $this->load->model('soma/Message_wxtemp_template_model', 'MessageWxtempTemplateModel');
                        $MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;

                        $type = $MessageWxtempTemplateModel::TEMPLATE_GIFT_RETURN;//礼物退回
                        $openid = $v['openid_give'];
                        $inter_id = $v['inter_id'];
                        $business = $v['business'];

                        $MessageWxtempTemplateModel->send_template_by_gift_success($type, $model, $openid, $inter_id, $business);
                        /***********************发送模版消息****************************/
                    }


                }//else
                //    die('Can not find gift #'. $v['gift_id']. ' in idx.');
            }
        }
        $redis->delete($lock_key);
        echo $result ? 'SUCCESS' : 'FAIL';
    }

    /**
     * 拼团失败检测
     * 每10分钟执行/处理量
     * @author zhangyi@mofly.cn
     *
     * todo 和公众号有关
     */
    public function groupon_refund_scan()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__);

        $this->load->model('soma/activity_groupon_model');
        $this->load->model('soma/sales_order_model');
        $this->load->helper('soma/package');

        $GrouponModel = $this->activity_groupon_model;
        //$db= $this->load->database('iwide_soma', TRUE);
        $db = $this->load->database('iwide_soma_r', true);
        $table = $db->dbprefix('soma_shard_link');

        $interIdArr = $db->select('inter_id')
            ->group_by('inter_id')
            ->get($table)->result_array();
        if (!empty($interIdArr)) {

            write_log("失效拼团扫描 @" . date("Y-m-d H:i:s", time()));

            foreach ($interIdArr as $v) {
                $groups = $GrouponModel->get_unavailable_groupon($v['inter_id']); //遍历获取每个公众号前100条
                if (is_array($groups)) {
//                    write_log("公众号：".$v['inter_id']."\n待处理过期拼团数目：".count($groups));
                    foreach ($groups as $group) {
                        //记录到带退款团表
                        $result = $GrouponModel->move_unavailable_groupon($group, $v['inter_id']);
                        write_log("interID is " . $v['inter_id'] . "\n" . json_encode($result));

                        if (!empty($result['cancelList'])) {
                            foreach ($result['cancelList'] as $cancelOrder) {
                                $inter_id = $cancelOrder['inter_id'];
                                if ($inter_id) {
                                    //初始化数据库分片配置，微信接口关闭订单需要初始化shard_id
                                    $this->load->model('soma/shard_config_model', 'model_shard_config');
                                    $this->current_inter_id = $cancelOrder['inter_id'];
                                    $this->db_shard_config = $this->model_shard_config->build_shard_config($cancelOrder['inter_id']);
                                }
                                $OrderModel = $this->SalesOrderModel->load($cancelOrder['order_id']);
                                if ($OrderModel) {
                                    $this->load->model('soma/Reward_benefit_model', 'RewardBenefitModel');
                                    $RewardBenefitModel = $this->RewardBenefitModel;
                                    $benefitState = $RewardBenefitModel->modify_benefit_queue_refund($cancelOrder['inter_id'], $OrderModel);
                                    if ($benefitState) {
                                        write_log("order : " . $cancelOrder['order_id'] . "取消业绩状态 Success");
                                    } else {
                                        write_log("order : " . $cancelOrder['order_id'] . "取消业绩状态 Failed");
                                    }
                                }
                            }
                        }
                    }
                }
            }
            echo "success";
        } else {
            write_log("没有公众号列表");
            echo "没有公众号列表";
        }
    }

    /**
     * 拼团失败用户退款
     * 每10分钟执行/处理量
     * @author zhangyi@mofly.cn
     *
     * todo 和公众号有关
     */
    public function groupon_refund_exc()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__);

        $this->load->model('soma/activity_groupon_model');
        $this->load->model('soma/sales_refund_model');
        $this->load->model('soma/sales_order_model');
        $this->load->helper('soma/package');

        $SalesRefundModel = $this->sales_refund_model;
        $SalesOrderModel = $this->sales_order_model;

        $refundUsers = $this->activity_groupon_model->refund_users(100, 0, 'join_time DESC');

        if (is_array($refundUsers) && !empty($refundUsers)) {
            foreach ($refundUsers as $user) {
                $inter_id = $user['inter_id'];
                if ($inter_id) {
                    //初始化数据库分片配置，微信接口关闭订单需要初始化shard_id
                    $this->load->model('soma/shard_config_model', 'model_shard_config');
                    $this->current_inter_id = $user['inter_id'];
                    $this->db_shard_config = $this->model_shard_config->build_shard_config($user['inter_id']);
                }

                $this->activity_groupon_model->refund_exc($SalesOrderModel, $SalesRefundModel, $user);


            }
        }
        echo "success";
    }

    /**
     * 拼团失败退款
     * 每10分钟执行/处理量
     * @author zhangyi@mofly.cn
     *
     * todo 和公众号有关
     */
    public function groupon_auto_refund()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__);

        $this->load->model('soma/activity_groupon_model');
        $this->load->model('soma/sales_refund_model');
        $this->load->model('soma/sales_order_model');

        $this->load->helper('soma/package');

        $SalesRefundModel = $this->sales_refund_model;
        $SalesOrderModel = $this->sales_order_model;
        $GrouponModel = $this->activity_groupon_model;

        //$db= $this->load->database('iwide_soma', TRUE);
        $db = $this->load->database('iwide_soma_r', true);
        $table = $db->dbprefix('soma_shard_link');
        $interIdArr = $db->select('inter_id')
            ->group_by('inter_id')
            ->get($table)->result_array();

        $res = array();
        $strTips = " Cron group refund : ";

        if (!empty($interIdArr)) {
            foreach ($interIdArr as $v) {
                $groups = $GrouponModel->get_unavailable_groupon($v['inter_id']);
                write_log($strTips . json_encode($groups));
                $res[$v['inter_id']] = $GrouponModel->set_unavailable_groupon($groups, $SalesOrderModel, $SalesRefundModel);
                write_log($strTips . json_encode($res));
            }

        }

        return $res;
    }

    /**
     * 根据最新的公众号表，生成对应的数据库分片记录
     * 每1小时执行/全量处理
     * @author libinyan@mofly.cn
     *
     * 灰度发布不执行！！！！
     */
    public function order_blacklist_clean()
    {
        $this->_write_log(__FUNCTION__);

        $this->load->model('soma/sales_order_model');
        $result = $this->sales_order_model->clean_order_client_ip();
        echo $result ? 'SUCCESS' : 'FAIL';
    }

    /**
     * 根据最新的公众号表，生成对应的数据库分片记录
     * 每1小时执行/处理量
     * @author libinyan@mofly.cn
     *
     * 灰度发布不执行！！！！
     */
    public function shard_init()
    {
        $this->_write_log(__FUNCTION__);

        $this->load->model('wx/publics_model', 'publics');
        $this->load->model('soma/shard_config_model', 'shard');
        $publics = $this->publics->get_public();
        //print_r($publics);die;

        //根据分片定义表创建对应的表格
        $shard_ids = $this->shard->get_shard_ids();
        foreach ($shard_ids as $sv) {
            $this->shard->init_shard_table($sv);
        }
        //将新的公众号更新到配置表中
        foreach ($publics as $v) {
            $default_shard = 1;
            $this->shard->reflesh_shard_data($v->inter_id, $default_shard);
        }
        echo 'table created finish';
    }

    /**
     * 发送微信场景模板消息
     * 每1分钟执行/处理量100条
     * @author luguihong@mofly.cn
     *
     * todo 和公众号无关
     */
    public function message_wxtemp_sending()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__);

        $limit = 100;
        $this->load->model('soma/Message_wxtemp_template_model', 'MessageWxtempTemplateModel');
        $this->MessageWxtempTemplateModel->sending_template_message($limit);
        echo 'ok';
    }

    /**
     * 套票过期生成模版消息
     * 每1分钟执行/处理量100条
     * @author luguihong@mofly.cn
     *
     * todo 和公众号无关
     */
    public function message_wxtemp_package_expire()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__);

        $limit = 10000;

        //$db = $this->load->database('iwide_soma', TRUE);
        $db = $this->load->database('iwide_soma_r', true);
        $table = $db->dbprefix('soma_shard_link');
        $interIdArr = $db->select('inter_id')
            ->group_by('inter_id')
            ->get($table)
            ->result_array();

        if (!empty($interIdArr)) {

            $this->load->model('soma/Message_wxtemp_template_model', 'MessageWxtempTemplateModel');
            $MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;

            foreach ($interIdArr as $v) {
                $inter_id = $v['inter_id'];
                if ($inter_id) {
                    //初始化数据库分片配置，微信接口关闭订单需要初始化shard_id
                    $this->load->model('soma/shard_config_model', 'model_shard_config');
                    $this->current_inter_id = $v['inter_id'];
                    $this->db_shard_config = $this->model_shard_config->build_shard_config($v['inter_id']);
                    //print_r($this->db_shard_config);
                }
                $MessageWxtempTemplateModel->create_message_wxtemp($limit, $inter_id);
            }

        }
        echo 'ok';
    }

    /**
     * 套票过期如果是礼包，自动把礼包发送给当前用户
     * 每1分钟执行/处理量100条
     * @author luguihong@mofly.cn
     *
     * todo 和公众号无关
     */
    public function auto_member_package_to_user()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__);

        $limit = 100;

        $redis = $this->get_redis_instance();
        $lock_key = 'SOMA_CRON:AUTO_MEMBER_PACKAGE_TO_USER_LOCK';
        $lock = $redis->setnx($lock_key, 'lock');
        if (!$lock) {
            $this->_write_log(__FUNCTION__ . ' lock fail!', true, 'error');
            die('FAILURE!');
        }
        $redis->setex($lock_key, 90, 'auto_member_package_to_user_lock');

        //$db = $this->load->database('iwide_soma', TRUE);
        $db = $this->load->database('iwide_soma_r', true);
        $table = $db->dbprefix('soma_shard_link');
        $interIdArr = $db->select('inter_id')
            ->group_by('inter_id')
            ->get($table)
            ->result_array();

        if (!empty($interIdArr)) {

            $this->load->model('soma/Asset_item_package_model', 'AssetItemModel');
            $AssetItemModel = $this->AssetItemModel;

            foreach ($interIdArr as $v) {
                $inter_id = $v['inter_id'];
                if ($inter_id) {
                    //初始化数据库分片配置，微信接口关闭订单需要初始化shard_id
                    $this->load->model('soma/shard_config_model', 'model_shard_config');
                    $this->current_inter_id = $inter_id;
                    $this->db_shard_config = $this->model_shard_config->build_shard_config($inter_id);
                    //print_r($this->db_shard_config);
                }
                $AssetItemModel->package_to_user($inter_id, $limit);
            }

        }

        $redis->delete($lock_key);
        echo 'ok';
    }

    /**
     * 发送分销业绩数据
     * 每1分钟执行/处理量100条
     * @author luguihong@mofly.cn
     * @deprecation
     *
     * todo 和公众号无关
     */
    public function order_reward_sending()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__);

        $limit = 100;
        $this->load->library('Soma/Api_distribute');
        $this->load->model('soma/Reward_benefit_model');
        $result = $this->Reward_benefit_model->send_benefit_queue($limit);
        //print_r($result);die;
        $success_ids = array();
        $api_distribute = new Api_distribute();
        $full_field_status = array(
            Reward_benefit_model::REWARD_STATUS_6,
            Reward_benefit_model::REWARD_STATUS_11,
        );
        $return = false;
        foreach ($result as $k => $v) {
            //针对这些状态做全字段信息推送
            if (in_array($v['reward_status'], $full_field_status)) {
                //新增绩效提成
                $return = $api_distribute->reward_sending($v);
                if ($return) {
                    $success_ids[] = $v['reward_id'];
                }
            }
        }
        foreach ($result as $k => $v) {
            //针对这些状态做部分字段信息推送
            if (!in_array($v['reward_status'], $full_field_status)) {
                //处理业绩提成
                $return = $api_distribute->reward_modify($v);
                if ($return) {
                    $success_ids[] = $v['reward_id'];
                }
            }
        }
        //print_r($success_ids);die;
        //处理已经发送成功的记录
        $this->Reward_benefit_model->update_reward_status($success_ids, Reward_benefit_model::STATUS_SENDED);
        echo $return ? 'SUCCESS' : 'EMPTY';
    }

    /**
     * 业绩推送定时任务,新版
     *
     * @author     F.oris <fengzhongcheng@mofly.com>
     *
     * todo 和公众号无关
     */
    public function push_order_reward_info()
    {
        $this->_check_access();    //拒绝非法IP执行任务
        $this->_write_log(__FUNCTION__);

        $limit = 100;
        $this->load->model('soma/Reward_benefit_model', 'r_model');
        $result = $this->r_model->send_benefit_queue($limit);
        $ext_rewards = $this->r_model->fill_reword_info_with_order($result);

        $this->load->library('Soma/Api_idistribute');
        $api = new Api_idistribute();

        // 所有订单付款后产生的分销业绩记录状态为6或11，
        // 这些订单肯定没有推送过，所以对这些订单进行信息完全推送
        $full_field_status = array(
            Reward_benefit_model::REWARD_STATUS_6,
            Reward_benefit_model::REWARD_STATUS_11,
            Reward_benefit_model::REWARD_STATUS_16,
        );

        $success_ids = array();

        foreach ($ext_rewards as $key => $reward) {
            $success = false;
            if (in_array($reward['reward_status'], $full_field_status)) {
                $success = $api->post_saler_sales_info($reward);
                if (!$success) {
                    $success = $api->post_fans_sales_info($reward);
                }
            } else {
                $success = $api->update_saler_sales_info($reward);
                if (!$success) {
                    $success = $api->update_fans_sales_info($reward);
                }
            }
            if ($success) {
                $success_ids[] = $reward['reward_id'];
            }
        }

        $res = false;
        if (count($success_ids) > 0) {
            $res = $this->r_model->update_reward_status($success_ids, Reward_benefit_model::STATUS_SENDED);
        }

        echo $res ? 'SUCCESS' : 'FAIL';
    }

    /**
     * 查找七天内无退款的业绩，进行成功标记
     * 每1分钟执行/处理量100条
     * @author luguihong@mofly.cn
     *
     * todo 和公众号无关
     */
    public function order_reward_checking()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__);

        //sleep(3); //为防止与order_reward_sending并发产生数据错误，延迟执行该方法
        $limit = 100;
        $this->load->model('soma/Reward_benefit_model');
        $result = $this->Reward_benefit_model->set_benefit_norefund($limit);
        echo $result ? 'SUCCESS' : 'EMPTY';
    }


    #  以下为秒杀流程控制   ################################################

    /**
     * 不执行旧秒杀逻辑的公众号
     *
     * @return     array  公众号数组
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    protected function skipKillsecCronTaskInterId()
    {
        return KillsecService::getInstance()->skipOldKillsecCronTaskInterId();
    }

    /**
     * 更新周期秒杀活动的活动时间
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function update_times()
    {
        $this->_check_access();
        $this->_write_log(__FUNCTION__ . ' start!', true);

        KillsecService::getInstance()->updateScheduleCycleTime();
    }

    /**
     * 秒杀活动初始化
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function killsec_init()
    {
        $this->_check_access();
        $this->_write_log(__FUNCTION__ . ' start!', true);

        $generator = CronService::getInstance()->killsecInit();

        while($generator->valid()) {
            $result = $generator->current();

            $generator->next();
        }

        $this->_write_log(__FUNCTION__ . ' success!', true);
    }

    /**
     *
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function killsec_rollback()
    {
        $this->_check_access();
        $this->_write_log(__FUNCTION__ . ' start!', true);

        $redis = $this->get_redis_instance();

        $lock_key = 'SOMA_CRON:KILLSEC2_ROLLBACK_LOCK';
        $lock = $redis->setnx($lock_key, 'lock');
        if (!$lock) {
            $this->_write_log(__FUNCTION__ . ' lock fail!', true, 'error');
            die('FAILURE!');
        }

        KillsecService::getInstance()->killsecRollback();

        $redis->delete($lock_key);

        $this->_write_log(__FUNCTION__ . ' success!', true);
    }


    /**
     *
     *
     * todo 和公众号有关
     */
    public function killsec_instance_init()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__ . ' start!', true);

        // $cache= $this->_load_cache();
        // $redis= $cache->redis->redis_instance();
        $redis = $this->get_redis_instance();

        $lock_key = 'SOMA_CRON:KILLSEC_INSTANCE_INIT_LOCK';
        $lock = $redis->setnx($lock_key, 'lock');
        if (!$lock) {
            $this->_write_log(__FUNCTION__ . ' lock fail!', true, 'error');
            die('FAILURE!');
        }
        // 设置300秒内超时
        // $redis->setex($lock_key, 300, 'killsec_instance_init_lock');

        $this->load->model('soma/Activity_killsec_model');
        $activitys = $this->Activity_killsec_model->get_preview_activity();
        //print_r($activitys);//die;
        $result = false;
        $skip_inter_id = $this->skipKillsecCronTaskInterId();
        if(!empty($skip_inter_id)) {
            foreach ($activitys as $k => $v) {
                if (isset($v['inter_id']) && $v['inter_id']) {
                    if(in_array($v['inter_id'], $skip_inter_id)) {
                        continue;
                    }
                    //清理过期的实例
                    $this->Activity_killsec_model->close_timeout_instance($v['inter_id'], $v);
                    //批量处理加入新实例
                    $result = $this->Activity_killsec_model->insert_new_instance($v['inter_id'], $v);
                }
            }
        }

        $redis->delete($lock_key);

        $this->_write_log(__FUNCTION__ . ' success!', true);

        echo $result ? 'SUCCESS' : 'EMPTY';
    }

    /**
     *
     * todo 和公众号无关
     */
    public function killsec_user_order_cleaning()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__ . ' start!', true);

        // $cache= $this->_load_cache();
        // $redis= $cache->redis->redis_instance();
        $redis = $this->get_redis_instance();

        $lock_key = 'SOMA_CRON:KILLSEC_USER_ORDER_CLEANING_LOCK';
        $lock = $redis->setnx($lock_key, 'lock');
        if (!$lock) {
            $this->_write_log(__FUNCTION__ . ' lock fail!', true, 'error');
            die('FAILURE!');
        }
        // 设置90秒内超时
        // $redis->setex($lock_key, 300, 'killsec_user_order_cleaning_lock');

        $this->load->model('soma/Activity_killsec_model');
        $instance = $this->Activity_killsec_model->get_aviliable_instance();
        // print_r($instance);//die;
        $result = false;
        $skip_inter_id = $this->skipKillsecCronTaskInterId();
        if(!empty($skip_inter_id)) {
            foreach ($instance as $k => $v) {
                if (isset($v['inter_id']) && $v['inter_id']) {
                    if(in_array($v['inter_id'], $skip_inter_id)) {
                        continue;
                    }
                    $result = $this->Activity_killsec_model->instance_processing($v['inter_id'], $v);
                }
            }
        }

        // 拼接支付名额任务
        $this->killsec_user_payment_cleaning();

        $redis->delete($lock_key);

        $this->_write_log(__FUNCTION__ . ' success!', true);

        echo $result ? 'SUCCESS' : 'EMPTY';
    }

    /**
     * todo 和公众号无关
     */
    public function killsec_user_payment_cleaning()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__ . ' start!', true);

        $this->load->model('soma/Activity_killsec_model');
        $instance = $this->Activity_killsec_model->get_aviliable_instance();
        //print_r($activitys);//die;
        $result = false;
        $skip_inter_id = $this->skipKillsecCronTaskInterId();
        if(!empty($skip_inter_id)) {
            foreach ($instance as $k => $v) {
                if (isset($v['inter_id']) && $v['inter_id']) {
                    if(in_array($v['inter_id'], $skip_inter_id)) {
                        continue;
                    }
                    //初始化数据库分片配置，微信接口关闭订单需要初始化shard_id
                    if ($v['inter_id']) {
                        $this->load->model('soma/shard_config_model', 'model_shard_config');
                        $this->current_inter_id = $v['inter_id'];
                        $this->db_shard_config = $this->model_shard_config->build_shard_config($v['inter_id']);
                        //print_r($this->db_shard_config);
                    }

                    $result = $this->Activity_killsec_model->instance_payment_clean($v['inter_id'], $v);
                }
            }
        }

        $this->_write_log(__FUNCTION__ . ' success!', true);

        echo $result ? 'SUCCESS' : 'EMPTY';
    }

    /**
     *
     * 秒杀订阅发送
     * 执行频率每分钟
     * @return string
     *
     * todo 和公众号无关
     */
    public function killsec_notice_sending()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->monoLog->notice('cron killsec_notice_sending invoked begin', [
            'current_time' => microtime(true),
            'function' => __FUNCTION__
        ]);

        CronService::getInstance()->sendKillsecBeginNotice();

//        $this->load->model('wx/publics_model', 'publics');
//        $publics = $this->publics->get_public();
//
//        $this->load->model('soma/Activity_killsec_model');
//        $this->load->model('soma/Message_wxtemp_template_model');
//
//        $result = false;
//        $limit = 30;
//        foreach ($publics as $v) {
//            $inter_id = $v->inter_id;
//            $sendlist = $this->Activity_killsec_model->get_waiting_notice_list($inter_id, $limit);
//
//            if (count($sendlist) > 0) {
//                $result = $this->Message_wxtemp_template_model->send_template_by_killsec_subscriber($inter_id, $sendlist, (array) $v);
//                $this->Activity_killsec_model->set_waiting_notice_list($inter_id, $result);
//            }
//        }
//        echo $result ? 'SUCCESS' : 'EMPTY';

        //清理一段时间内的记录，执行时间3点01分
        if (date('H') == '03' && date('i') == '01') {
            $this->Activity_killsec_model->cleanup_waiting_notice_list(7);
        }

        $this->monoLog->notice('cron killsec_notice_sending invoked end', [
            'current_time' => microtime(true),
            'function' => __FUNCTION__
        ]);


        if (ENVIRONMENT === 'dev') $this->output->enable_profiler(true);
    }

    /**
     * 销售统计信息维护
     *
     * todo 和公众号无关
     */
    public function statis_update_sales()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        // $cache= $this->_load_cache();
        // $redis= $cache->redis->redis_instance();
        $redis = $this->get_redis_instance();

        $lock_key = 'SOMA_CRON:SALES_STATIS_CRONTAB_LOCK';
        $lock = $redis->setnx($lock_key, 'lock');
        if (!$lock) {
            $this->_write_log(__FUNCTION__ . ' lock fail!', true, 'error');
            die('FAILURE!');
        }

        // 设置90秒内超时
        $redis->setex($lock_key, 90, 'statis_update_sales_lock');

        $this->_write_log(__FUNCTION__);

        $this->load->model('soma/Statis_sales_model', 'Statis_sales_model');
        $statis_model = $this->Statis_sales_model->init_service();

        if ($statis_model->check_sales_data()) {
            $result = $statis_model->update_sales_data(date('Y-m-d') . ' 00:00:00');

        } else {
            $result = $statis_model->update_sales_data();
        }

        $redis->delete($lock_key);
        $redis->close(); // 将update_sales_data()统计中的关闭，移动到此处

        echo $result ? 'SUCCESS' : 'FAIL';
    }


    #  以下为测试方法    ######################################################
    /**
     * 拼团失败退款失败修复
     *
     * todo 和公众号无关
     */
    public function groupon_auto_refund_retry()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__);

        $this->load->model('soma/activity_groupon_model');
        $this->load->model('soma/sales_refund_model');
        $this->load->model('soma/sales_order_model');

        $this->load->helper('soma/package');

        $SalesRefundModel = $this->sales_refund_model;
        $SalesOrderModel = $this->sales_order_model;
        $GrouponModel = $this->activity_groupon_model;

        //$db= $this->load->database('iwide_soma', TRUE);
        $db = $this->load->database('iwide_soma_r', true);
        $table = $db->dbprefix('soma_shard_link');
        $interIdArr = $db->select('inter_id')
            ->group_by('inter_id')
            ->get($table)->result_array();

        $res = array();
        $strTips = " Cron group refund Retry (BUG FIX): ";

        $this->load->model('soma/shard_config_model', 'model_shard_config');

        if (!empty($interIdArr)) {
            foreach ($interIdArr as $v) {
                $groups = $GrouponModel->get_unavailable_groupon_failed($v['inter_id']);

                //write_log($strTips. json_encode($groups));
                $this->current_inter_id = $v['inter_id'];
                $this->db_shard_config = $this->model_shard_config->build_shard_config($v['inter_id']);

                $res[$v['inter_id']] = $GrouponModel->set_unavailable_groupon($groups, $SalesOrderModel, $SalesRefundModel);
                write_log($strTips . json_encode($res));
            }

        }

        return $res;
    }

    /**
     *
     * FOR test
     */
    public function checkRefund()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__);

        $order_id = 1000001403;
        $inter_id = 'a429262687';
        $this->load->model('soma/sales_refund_model');

        $this->load->model('soma/shard_config_model', 'model_shard_config');
        $this->current_inter_id = $inter_id;
        $this->db_shard_config = $this->model_shard_config->build_shard_config($inter_id);

        $x = $this->sales_refund_model->wx_refund_check($order_id, 'package', $inter_id);

        var_dump($x);
    }

    /* public function order_blacklist()
    {
        $this->_write_log(__FUNCTION__);

        $this->load->model('soma/sales_order_model');
        $customer= new Sales_order_attr_customer('1231233');
        $this->sales_order_model->customer= $customer;
        $r= $this->sales_order_model->remark_order_ip($customer, 'package');
        var_dump($r);
        if($this->sales_order_model->check_client_can_order($customer, 'package'))
            echo '允许下单';
        else
            echo '超过限制';
        echo $this->sales_order_model->clean_order_client_ip();
    } */

    /**
     * 订单统计信息
     * 执行频率5分钟一次
     * @return string
     *
     * todo 和公众号无关
     */
    public function order_statis_summary()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__);

        // 构建过滤器，只过滤：$start_time, $end_time, $limit, $offset
        // $date = date('Y-m-d');

        $start_time = $this->input->get('start_time');
        $end_time = $this->input->get('end_time');
        $init = $this->input->get('init');

        if ($init) {
            $start_time = '1970-01-01 00:00:00';
            $end_time = date('Y-m-d') . ' 23:59:59';
        } else {
            $start_date = date("Y-m-d", strtotime("-1 day"));
            $now_date = date('Y-m-d');
            if (!$start_time) {
                $start_time = $start_date . ' 12:00:00';
            }
            if (!$end_time) {
                $end_time = $now_date . ' 23:59:59';
            }
        }

        $limit = $offset = null;
        $filter = compact("start_time", "end_time", "limit", "offset");

        // 初始化数据库分片配置，微信接口关闭订单需要初始化shard_id
        // model 里面 $this->_share_db($inter_id)  #inter_id 不能为空
        // 控制器设置了$this->current_inter_id，可以使用$this->_share_db()
        // $this->load->model('soma/shard_config_model', 'model_shard_config');
        // $this->db_shard_config= $this->model_shard_config->build_shard_config();

        $this->load->model('soma/sales_order_model', 'o_model');
        $summary = $this->o_model->get_order_summary($filter);
        $res = $this->o_model->save_order_summary($summary);

        echo $res ? 'SUCCESS' : 'Failed';

    }

    /**
     * 订单异常报警
     * 执行频率1小时1次
     *
     * todo 和公众号无关
     */
    public function order_exception_warning()
    {
        $this->_write_log(__FUNCTION__);

        $warning_avg_price = 1;    //客单价1元
        $warning_avg_count = 50;   //检测超过50单
        $warning_count = 100;  //超过一百单

        $cache = $this->_load_cache();
        $redis = $cache->redis->redis_instance();

        //数量异常
        $this->load->model('soma/Statis_sales_model');
        $statis_model = $this->Statis_sales_model;

        $check_member = array(date('Y-m-d'), date('Y-m-d', strtotime('-1 days')));
        //print_r($check_member);die;

        $order_message = '';
        $order_summary = 0;
        $count_summary = 0;
        $has_execption = false;
        $this->load->model('wx/Publics_model');
        $public_array = $this->Publics_model->get_public_hash();
        if (defined('PROJECT_AREA') && PROJECT_AREA == 'mooncake') {
            $project_name = '月饼说';
        } else {
            $project_name = '社交商城';
        }
        foreach ($public_array as $k => $v) {
            $qty_key = $statis_model->redis_token_key($v['inter_id'], $statis_model::K_SALE_QTY);
            $total_key = $statis_model->redis_token_key($v['inter_id'], $statis_model::K_SALE_TOTAL);
            $count_key = $statis_model->redis_token_key($v['inter_id'], $statis_model::K_SALE_COUNT);

            //订单数量异常，超过100
            $exception1 = $redis->zRangeByScore($count_key, $warning_count, -1, array('withscores' => true));
            foreach ($exception1 as $sk => $sv) {
                //过滤不报警的日期
                if (!in_array($sk, $check_member)) {
                    unset($exception1[$sk]);
                }
            }

            //客单价异常，超过 订单总金额/订单数量 < 某金额
            $exception2 = $redis->zRangeByScore($count_key, $warning_avg_count, 10000000, array('withscores' => true));
            $exception3 = $redis->zRangeByScore($total_key, 0, 10000000, array('withscores' => true));
            $today_total = isset($exception3[date('Y-m-d')]) ? $exception3[date('Y-m-d')] : 0;
            foreach ($exception2 as $sk => $sv) {
                //过滤不报警的日期
                if (!in_array($sk, $check_member)) {
                    unset($exception2[$sk]);
                    unset($exception3[$sk]);

                } elseif ($exception3[$sk] / $exception2[$sk] < $warning_avg_price) {
                    //订单量达到一定量，并且客单价过低，进入报警

                } else {
                    unset($exception2[$sk]);
                    unset($exception3[$sk]);
                }
            }

            $this->load->model('soma/Message_wxtemp_template_model', 'MessageWxtempTemplateModel');
            $MessageWxtempTemplateModel = $this->MessageWxtempTemplateModel;
            $type = $MessageWxtempTemplateModel::TEMPLATE_CONSUMER_SUCCESS;
            $openid_arr = $MessageWxtempTemplateModel->get_notice_openids();
            $inter_id = 'a450089706';
            $business = 'package';

            if (count($exception1) > 0) {
                //异常情况发送警告信息
                $message = "{$project_name}【{$v['name']}】系统订单数量超过【{$warning_count}】单，异常日期：【" .
                    implode(',', array_keys($exception1)) . '】，请即时查看该公众号订单数据。具体数据【' . json_encode($exception1) . '】';

                /***********************发送模版消息****************************/
                //发送模版消息
                foreach ($openid_arr as $k => $v) {
                    $openid = $v;
                    $MessageWxtempTemplateModel->send_template_by_consume_or_booking_success($type, '', $openid, $inter_id, $business, $message, false);
                }
                /***********************发送模版消息****************************/

                $has_execption = true;
                echo $message;
            }
            if (count($exception2) > 0) {
                //异常情况发送警告信息
                $message = "{$project_name}【{$v['name']}】系统订单累计超过【{$warning_avg_count}单】客单价低于【￥{$warning_avg_price}】，异常日期：【"
                    . implode(',', array_keys($exception2)) . '】，请即时查看该公众号订单数据。';

                /***********************发送模版消息****************************/
                //发送模版消息
                foreach ($openid_arr as $k => $v) {
                    $openid = $v;
                    $MessageWxtempTemplateModel->send_template_by_consume_or_booking_success($type, '', $openid, $inter_id, $business, $message, false);
                }
                /***********************发送模版消息****************************/

                $has_execption = true;
                echo $message;
            }

            if ($today_total > 0) {
                $order_count = $redis->zScore($statis_model->redis_token_key($v['inter_id'], $statis_model::K_SALE_COUNT), date('Y-m-d'));
                $order_summary += $today_total;
                $count_summary += $order_count;
                $today_total = number_format($today_total, 2);
                $order_message .= "【{$v['name']}】:【￥{$today_total}，{$order_count}单】\n";
            }

        }
        if (!$has_execption) {
            echo 'Release, Nothing Happen.';
        }

        $order_summary = number_format($order_summary, 2);
        $order_message = "{$project_name}今日累计【￥{$order_summary}，{$count_summary}单】\n" . $order_message;

        //每日早晚定时汇总销售额
        if ($this->input->get('report') == 1 || (in_array(date('H'), array('13', '23')) &&
                isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV'] == 'production')
        ) {
            /***********************发送模版消息****************************/
            //发送模版消息
            foreach ($openid_arr as $k => $v) {
                $openid = $v;
                $MessageWxtempTemplateModel->send_template_by_consume_or_booking_success($type, '', $openid, $inter_id, $business, $order_message, false);
            }
            /***********************发送模版消息****************************/
        }
    }

    /**
     * 商品数据统计
     *
     * todo 和公众号无关
     */
    public function update_product_statis()
    {
        $this->_check_access();    //拒绝非法IP执行任务
        ini_set('memory_limit','1024M');
        // $cache= $this->_load_cache();
        // $redis= $cache->redis->redis_instance();
        $redis = $this->get_redis_instance();

        $lock_key = 'SOMA_CRON:PRODUCT_STATIS_CRONTAB_LOCK';
        $lock = $redis->setnx($lock_key, 'lock');
        if (!$lock) {
            $this->_write_log(__FUNCTION__ . ' lock fail!', true, 'error');
            die('FAILURE!');
        }
        // 设置90秒内超时
        $redis->setex($lock_key, 90, 'update_product_statis_lock');

        $this->_write_log(__FUNCTION__);
        $this->load->model('soma/statis_product_model', 's_model');
        $data_check = $this->s_model->check_statis_data();
        if ($data_check) {
            // 存在数据，更新当天数据
            $s_time = date('Y-m-d', strtotime("-1 days")) . ' 00:00:00';
            $e_time = date('Y-m-d H:i:s');
            // var_dump($s_time, $e_time);exit;
            $this->s_model->update_statis_data($s_time, $e_time);
        } else {
            // 不存在数据，更新90天前的数据，防止数据溢出，15天为更新梯度
            $s_time = date('Y-m-d', strtotime("-180 days")) . ' 00:00:00';
            $e_time = date('Y-m-d', strtotime("+10 days", strtotime($s_time)));
            $now_time = date('Y-m-d H:i:s');

            while (strtotime($s_time) < strtotime($now_time)) {
                $this->s_model->update_statis_data($s_time, $e_time);
                $s_time = $e_time;
                $e_time = date('Y-m-d', strtotime("+10 days", strtotime($s_time)));
            }
        }

        $redis->delete($lock_key);

        echo "success";
    }


    /**
     *
     * todo 和公众号无关
     */
    public function consumer_code_fixed()
    {
        $limit = 100;
        $log_txt = "码表维护记录:--\n";
        $db = $this->load->database('iwide_soma', true);
        $table_code = 'iwide_soma_consumer_code';
        $table_order = 'iwide_soma_sales_order_idx';
        $table_ai1 = 'iwide_soma_asset_item_package_1001';
        $table_ai2 = 'iwide_soma_asset_item_package_1002';

        //更新有order_id 的记录
        $result = $db->query("update {$table_code} as c, {$table_order} as o set c.inter_id=o.inter_id "
            . " where c.order_id=o.order_id and c.inter_id is null");
        //var_dump($result);die;
        if ($result) {
            $log_txt .= "根据order_id成功更新所有的记录--\n";
        }

        $codes = $db->where('inter_id', null)->where('asset_item_id is not', null)
            ->where('status', 2)->get($table_code)->result_array();
// echo $db->last_query();die;
        // var_dump( $codes );die;
        $assetItemIds = array();//记录没有处理的

        $code_cnt = count($codes);
        if ($code_cnt > 0) {
            $index_i = 1;
            $log_txt .= "需要循环更新的code记录有{$code_cnt}条--\n";
            foreach ($codes as $k => $v) {
                $flag = true;
                if ($index_i > $limit) {
                    break;
                }

                //首先各自找出2个分片中的资产记录。
                $ai1 = $db->where('item_id', $v['asset_item_id'])->get($table_ai1)->row_array();
                $ai2 = $db->where('item_id', $v['asset_item_id'])->get($table_ai2)->row_array();
                //分析情况1：一边有记录，另外一边没有该asset_item_id
                if ($ai1 && !$ai2) {
                    $result = $db->query("update `{$table_code}` set inter_id='{$ai1['inter_id']}', asset_id='{$ai1['asset_id']}', "
                        . "order_item_id='{$ai1['order_item_id']}', order_id='{$ai1['order_id']}' where code_id='{$v['code_id']}'");
                    $log_txt .= $index_i++ . "ID:【{$v['code_id']}】从分片1细单【{$ai1['item_id']}】更新--\n";
                    $flag = false;
                } elseif (!$ai1 && $ai2) {
                    $result = $db->query("update `{$table_code}` set inter_id='{$ai2['inter_id']}', asset_id='{$ai2['asset_id']}', "
                        . "order_item_id='{$ai2['order_item_id']}', order_id='{$ai2['order_id']}' where code_id='{$v['code_id']}'");
                    $log_txt .= $index_i++ . "ID:【{$v['code_id']}】从分片2细单【{$ai2['item_id']}】更新--\n";
                    $flag = false;
                } elseif ($ai1 && $ai2) {
                    //各自搜索两分片的qty与code数量是否匹配，数量匹配则不修正
                    $ai1_code = $db->where('inter_id', $ai1['inter_id'])->where('asset_item_id', $ai1['item_id'])->where('status', 2)->get($table_code)->result_array();
                    if (count($ai1_code) < $ai1['qty']) {
                        $result = $db->query("update `{$table_code}` set inter_id='{$ai1['inter_id']}', asset_id='{$ai1['asset_id']}', "
                            . "order_item_id='{$ai1['order_item_id']}', order_id='{$ai1['order_id']}' where code_id='{$v['code_id']}'");
                        $log_txt .= $index_i++ . "ID:【{$v['code_id']}】匹配分片1细单【{$ai1['item_id']}】更新--\n";
                        $flag = false;
                    } else {
                        $ai2_code = $db->where('inter_id', $ai2['inter_id'])->where('asset_item_id', $ai2['item_id'])->where('status', 2)->get($table_code)->result_array();
                        if (count($ai2_code) < $ai2['qty']) {
                            $result = $db->query("update `{$table_code}` set inter_id='{$ai2['inter_id']}', asset_id='{$ai2['asset_id']}', "
                                . "order_item_id='{$ai2['order_item_id']}', order_id='{$ai2['order_id']}' where code_id='{$v['code_id']}'");
                            $log_txt .= $index_i++ . "ID:【{$v['code_id']}】匹配分片2细单【{$ai2['item_id']}】更新--\n";
                            $flag = false;
                        }

                    }
                }

                //记录没有处理的
                if ($flag) {
                    $assetItemIds[] = $v['asset_item_id'];
                }

            }

            if (count($assetItemIds) > 0) {
                $assetItemIds_str = implode(',', $assetItemIds);
                $log_txt .= "【没有处理的资产细单ID：{$assetItemIds_str}】\n";
            }

        } else {
            $log_txt .= "找不到需要循环更新的code记录--\n";
        }

        //写入log文件
        $file = date('Y-m-d') . '.txt';
        $path = APPPATH . 'logs' . DS . 'soma' . DS . 'code' . DS;
        if (!file_exists($path)) {
            @mkdir($path, 0777, true);
        }
        $ip = $this->input->ip_address();
        $fp = fopen($path . $file, 'a');
        $content = str_repeat('-', 40) . "\n[" . date('Y-m-d H:i:s') . ']'
            . "\n" . $ip . "\n" . $log_txt . "\n";
        fwrite($fp, $content);
        fclose($fp);
        echo $log_txt;
    }

    /**
     * Calculates the order point.
     * todo 和公众号无关
     */
    public function calc_order_point()
    {
        $this->_check_access();    //拒绝非法IP执行任务
        $this->_write_log(__FUNCTION__);
        $this->load->model('soma/sales_point_model', 'sp_model');
        $this->sp_model->trans_begin();
        try {

            $s_time = date('Y-m-d H:i:s', strtotime("-2 hours"));

            $days = $this->input->get('d');
            if ($days) {
                $s_time = date('Y-m-d', strtotime("-$days days")) . ' 00:00:00';
            }

            $this->sp_model->update_point_queue($s_time);
            $this->sp_model->trans_commit();
            die('SUCCESS');
        } catch (Exception $e) {
            $this->sp_model->trans_rollback();
        }
        die('Failed');
    }


    /**
     * Pushes a point information.
     *
     * todo 和公众号无关
     */
    public function push_point_info()
    {
        $this->_check_access();    //拒绝非法IP执行任务
        $this->_write_log(__FUNCTION__);
        $this->load->model('soma/sales_point_model', 'sp_model');
        $res = $this->sp_model->push_point_info();
        echo $res ? 'SUCCESS' : 'Failed';
    }

    /**
     * 生成订单，没有支付的单，回退储值、积分、库存
     * 每5分钟执行/处理量100个
     *
     */
    public function order_auto_rollback()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__);

        $limit = 100;  //每次处理20个

        $debug = true;
        $log_txt = '';

        $redis = $this->get_redis_instance();
        $lock_key = 'SOMA_CRON:ORDER_AUTO_ROLLBACK_LOCK';
        $lock = $redis->setnx($lock_key, 'lock');
        if (!$lock) {
            $this->_write_log(__FUNCTION__ . ' lock fail!', true, 'error');
            die('FAILURE!');
        }
        $redis->setex($lock_key, 90, 'order_auto_rollback_lock');

        //$db = $this->load->database('iwide_soma', TRUE);
        $db = $this->load->database('iwide_soma_r', true);
        $table = $db->dbprefix('soma_shard_link');
        $interIdArr = $db->select('inter_id')
            ->group_by('inter_id')
            ->get($table)
            ->result_array();

        // if( $debug ) $log_txt .= '获取到的公众号列表：'.json_encode( $interIdArr )."\r\n";
        if (!empty($interIdArr)) {

            $this->load->model('soma/Sales_order_model', 'SalesOrderModel');
            $SalesOrderModel = $this->SalesOrderModel;

            $this->load->model('soma/Sales_item_package_model', 'SalesItemModel');
            $SalesItemModel = $this->SalesItemModel;

            $this->load->model('soma/Sales_order_discount_model', 'SalesDiscountModel');
            $SalesDiscountModel = $this->SalesDiscountModel;

            $this->load->model('soma/Product_package_model', 'ProductModel');
            $ProductModel = $this->ProductModel;

            $this->load->model('soma/Sales_refund_model', 'RefundModel');
            $RefundModel = $this->RefundModel;

            $this->load->model('soma/shard_config_model', 'model_shard_config');

            foreach ($interIdArr as $v) {
                $inter_id = $v['inter_id'];
                if ($inter_id) {
                    //初始化数据库分片配置，微信接口关闭订单需要初始化shard_id
                    $this->current_inter_id = $v['inter_id'];
                    $this->db_shard_config = $this->model_shard_config->build_shard_config($v['inter_id']);
                    //print_r($this->db_shard_config);
                }

                //根据公众号获取改公众号下面的未支付订单，拉取支付后15分钟内没有支付的单
                $start = date('Y-m-d H:i:s', time() - 7200);//这里处理为，以现在时间为基准往前推2个小时
                $end = date('Y-m-d H:i:s', time() - 900);//这里处理为，以现在时间为基准往前推15分钟。就是2个小时前到15分钟前的订单都会回滚
                // var_dump( $start, $end );die;
                $select = 'order_id,scope_product_link_id,inter_id,openid,row_qty,create_time,status';
                $orders = $SalesOrderModel->get_un_pay_orders($inter_id, $limit, $start, $end, $select);
                if (!$orders) {
                    continue;
                }

                $orderIds = array();
                foreach ($orders as $k => $v) {
                    $orderIds[$v['order_id']] = $v;
                }
                if ($debug) {
                    $log_txt .= '公众号：' . $inter_id . ', 订单列表：' . json_encode(array_keys($orderIds)) . "\r\n";
                }

                //查找商品ID
                $items = $SalesItemModel->get_order_items_byIds(array_keys($orderIds), 'package', $inter_id);
                if (!$items) {
                    continue;
                }

                $itemIds = array();
                foreach ($items as $k => $v) {
                    $data = array();
                    $data['item_id'] = $v['item_id'];
                    $data['product_id'] = $v['product_id'];
                    $itemIds[$v['order_id']] = $data;

                    $orderIds[$v['order_id']]['product_id'] = $v['product_id'];
                    if ($v['can_split_use'] == Soma_base::STATUS_TRUE) {
                        //如果是分时可以的，要乘以分时数量。这里不使用细单的数量，是因为分时可用支付成功后，才会改变细单的购买数量。但下单的时候只是保存了购买数量，但是已经扣了商品数量（分时数量＊购买数量）
                        $orderIds[$v['order_id']]['row_qty_old'] = $orderIds[$v['order_id']]['row_qty'];
                        $orderIds[$v['order_id']]['row_qty'] = $v['use_cnt'] * $orderIds[$v['order_id']]['row_qty'];//要回退的库存
                    }
                }
                if ($debug) {
                    $log_txt .= '公众号：' . $inter_id . ', 订单列表：' . json_encode(array_keys($orderIds)) . ', 订单细单列表：' . json_encode($itemIds) . "\r\n";
                }

                //这里查找的状态为未支付的
                $discount_select = 'discount_id,order_id,openid,type,inter_id,quote,create_time,status';
                $discounts = $SalesDiscountModel->get_discount_by_orderIds($inter_id, array_keys($orderIds), $start, $end, $discount_select);
                if (!$discounts) {
                    //不能结束，有可能是没有使用优惠
                    // continue;
                    if ($debug) {
                        $log_txt .= '公众号：' . $inter_id . ', 订单列表：' . json_encode(array_keys($orderIds)) . ', 没有查找到订单优惠列表，可能没有使用优惠' . "\r\n";
                    }
                } else {
                    $discountIds = array();
                    foreach ($discounts as $k => $v) {
                        //这里使用订单号作为键，可能同一个订单下面的优惠只保留一条。回滚的时候是根据订单号去检索的，所以这里只要有一条需要回滚的订单号就好
                        //根据订单号回滚
                        $data = array();
                        $data['order_id'] = $v['order_id'];
                        $data['discount_id'] = $v['discount_id'];
                        $data['quote'] = $v['quote'];
                        $data['type'] = $v['type'];
                        $discountIds[$v['order_id']] = $data;
                    }
                    if ($debug) {
                        $log_txt .= '公众号：' . $inter_id . ', 订单列表：' . json_encode(array_keys($orderIds)) . ', 订单优惠列表：' . json_encode($discountIds) . "\r\n";
                    }
                }

                foreach ($orderIds as $k => $v) {

                    if ($debug) {
                        $log_txt .= '公众号：' . $inter_id . ', 订单号：' . $v['order_id'] . ', 回滚开始' . "\r\n";
                    }

                    // 是否使用了优惠
                    if (isset($discountIds[$k]) && !empty($discountIds[$k])) {

                        //优惠规则，全部回滚
                        $isOrderRollback = TRUE;//如果是订单回滚的，不处理优惠券
                        $rollback_rs = $SalesDiscountModel->rollback_discount($discountIds[$k]['order_id'], $inter_id, $isOrderRollback);
                        if ($debug) {
                            $log_txt .= '公众号：' . $inter_id . ', 订单号：' . $discountIds[$k]['order_id'] . ', 优惠回滚：' . $rollback_rs . "\r\n";
                        }
                    }

                    //修改库存
                    if (isset($v['product_id']) && !empty($v['product_id'])) {
                        // 添加库存
                        $add_rs = $ProductModel->update_stock($inter_id, $v['product_id'], $v['row_qty'], $ProductModel::STOCK_ADD);
                        if ($debug) {
                            $log_txt .= '公众号：' . $inter_id . ', 订单号：' . $v['order_id'] . ', 产品号：' . $v['product_id'] . ', 回滚库存：' . $add_rs . "\r\n";
                        }
                    }

                    //修改价格配置库存
                    if (isset($v['scope_product_link_id']) && !empty($v['scope_product_link_id'])) {
                        $row_qty = isset($v['row_qty_old']) ? $v['row_qty_old'] : $v['row_qty'];
                        $return_result = ScopeDiscountService::getInstance()->updateStock($inter_id, $v['openid'], $v['scope_product_link_id'], $row_qty, '-');
                        if ($debug) {
                            $log_txt .= '公众号：' . $inter_id . ', 订单号：' . $v['order_id'] . ', 产品号：' . $v['product_id'] . ', 回滚价格配置库存：' . $return_result . "\r\n";
                        }
                    }
                    //分账支付，改为调分账主动关闭订单 add by yu 20170607
                    $wx_total = $SalesOrderModel->load($v['order_id'])->m_get('wx_total');
                    $pay_status = $SalesOrderModel->load($v['order_id'])->m_get('status');
                    $log_txt .= 'wx_total：'.$wx_total.', pay_status：'.$pay_status;
                    if($wx_total>0&&$pay_status==11){
                        $log_txt .= ', order_id：'.$v['order_id'];
                        $this->load->model('iwidepay/Iwidepay_model');
                        $rclose = $this->Iwidepay_model->close_order($v['order_id']);
                        $log_txt .= ', 分账关闭订单结果：'.json_encode($rclose)."\r\n";
                    }

                    //把订单状态改为无效订单
                    $order_rs = $SalesOrderModel->load($v['order_id'])->order_un_valid();
                    if ($debug) {
                        $log_txt .= '公众号：' . $inter_id . ', 订单号：' . $v['order_id'] . ', 修改订单状态为无效订单：' . $order_rs . "\r\n";
                    }

                    //关闭微信订单
                    $wx_rs = $RefundModel->wx_order_close($v['order_id'], 'package', $inter_id);
                    if ($debug) {
                        $log_txt .= '公众号：' . $inter_id . ', 订单号：' . $v['order_id'] . ', 关闭微信订单：' . $wx_rs . "\r\n";
                    }

                    if ($debug) {
                        $log_txt .= '公众号：' . $inter_id . ', 订单号：' . $v['order_id'] . ', 回滚结束' . "\r\n\r\n";
                    }

                }


            }

        }

        $redis->delete($lock_key);

        if ($log_txt) {
            //写入log文件
            $file = date('Y-m-d') . '.txt';
            $path = APPPATH . 'logs' . DS . 'soma' . DS . 'order_rollback' . DS;
            if (!file_exists($path)) {
                @mkdir($path, 0777, true);
            }
            $ip = $this->input->ip_address();
            $fp = fopen($path . $file, 'a');
            $content = str_repeat('-', 40) . "\n[" . date('Y-m-d H:i:s') . ']'
                . "\n" . $ip . "\n" . $log_txt . "\n";
            fwrite($fp, $content);
            fclose($fp);
        }

        echo 'SUCCESS';
    }

    /**
     * 如果商品过了下架时间，执行下架操作
     * 每5分钟执行
     *
     * todo 和公众号无关
     */
    public function product_auto_un_validity_date()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__);

        //$db = $this->load->database('iwide_soma', TRUE);
        $db = $this->load->database('iwide_soma_r', true);
        $table = $db->dbprefix('soma_shard_link');
        $interIdArr = $db->select('inter_id')
            ->group_by('inter_id')
            ->get($table)
            ->result_array();
        // var_dump( $interIdArr );die;

        if (!empty($interIdArr)) {

            $this->load->model('soma/Product_package_model', 'ProductModel');
            $ProductModel = $this->ProductModel;
            // var_dump( $ProductModel );die;

            foreach ($interIdArr as $v) {
                $inter_id = $v['inter_id'];

                $ProductModel->update_status($inter_id);

            }

        }

        echo 'SUCCESS';
    }

    /**
     * 修改20161226号前商品预约电话，字段为空才修改
     * 每5分钟执行
     *
     * todo 和公众号无关
     */
    public function product_auto_change_hotel_tel()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__);

        //$db = $this->load->database('iwide_soma', TRUE);
        $db = $this->load->database('iwide_soma_r', true);
        $table = $db->dbprefix('soma_shard_link');
        $interIdArr = $db->select('inter_id')
            ->group_by('inter_id')
            ->get($table)
            ->result_array();
        // var_dump( $interIdArr );die;

        if (!empty($interIdArr)) {

            $this->load->model('soma/Product_package_model', 'ProductModel');
            $ProductModel = $this->ProductModel;
            // var_dump( $ProductModel );die;

            foreach ($interIdArr as $v) {
                $inter_id = $v['inter_id'];

                $ProductModel->update_hotel_tel($inter_id);

            }

        }

        echo 'SUCCESS';
    }

    /**
     * 赠送会员礼包
     * @author     luguihong    2017/02/23
     *
     * todo 和公众号无关
     */
    public function send_member_card()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        // $cache= $this->_load_cache();
        // $redis= $cache->redis->redis_instance();
        $redis = $this->get_redis_instance();

        $lock_key = 'SOMA_CRON:SEND_MEMBER_CARD_LOCK';
        $lock = $redis->setnx($lock_key, 'lock');
        if (!$lock) {
            $this->_write_log(__FUNCTION__ . ' lock fail!', true, 'error');
            die('FAILURE!');
        }
        $redis->setex($lock_key, 90, 'send_member_card_lock');

        $this->_write_log(__FUNCTION__);

        //$db = $this->load->database('iwide_soma', TRUE);
        $db = $this->load->database('iwide_soma_r', true);
        $table = $db->dbprefix('soma_shard_link');
        $interIdArr = $db->select('inter_id')
            ->group_by('inter_id')
            ->get($table)
            ->result_array();
        // var_dump( $interIdArr );die;

        if (!empty($interIdArr)) {

            $this->load->model('soma/Config_member_package_model', 'somaConfigMemberModel');
            $somaConfigMemberModel = $this->somaConfigMemberModel;
            // var_dump( $ProductModel );die;

            $limit = 100;
            foreach ($interIdArr as $v) {
                $interId = $v['inter_id'];

                $recordList = $somaConfigMemberModel->get_record($interId, $limit);
                if ($recordList) {
                    foreach ($recordList as $v) {
                        $result = $somaConfigMemberModel->send_package($interId, $v['openid'], $v['send_id'], $v['product_id'], $v['num'], $v['type']);

                        $data = array('update_time' => date('Y-m-d H:i:s'));
                        if ($result) {
                            $data['status'] = $somaConfigMemberModel::RECORD_STATUS_SUCCESS;
                        } else {
                            $data['status'] = $somaConfigMemberModel::RECORD_STATUS_FAIL;
                        }
                        $res = $somaConfigMemberModel->update_record($interId, $v['id'], $data);

                    }
                }

            }

        }


        $redis->delete($lock_key);

        echo "success";
    }

    /**
     * 定时更新或创建批量秒杀的记录
     * @author renshuai  <renshuai@mofly.cn>
     *
     * todo 和公众号无关
     */
    public function killsec_group()
    {
        $this->_check_access();

        $this->_write_log(__FUNCTION__);

        $serviceName = $this->serviceName(Kill_Service::class);
        $serviceAlias = $this->serviceAlias(Kill_Service::class);
        $this->load->service($serviceName, null, $serviceAlias);

        $this->soma_kill_service->killsecBatch();
    }

    /**
     *
     * todo 和公众号无关
     */
    public function killsec_monitor()
    {

        $this->_check_access();    //拒绝非法IP执行任务
        $this->_write_log(__FUNCTION__);

        $serviceName = $this->serviceName('Kill_Service');
        $serviceAlias = $this->serviceAlias('Kill_Service');
        $this->load->service($serviceName, null, $serviceAlias);

        $this->soma_kill_service->monitorKillsecService();
    }

    /**
     * 同步订单到智游宝，一分钟同步一次
     * @author luguihong  <luguihong@jperation.com>
     */
    public function order_conn_devices()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__);

        $limit= 100;

        $redis = $this->get_redis_instance();
        $lock_key = 'SOMA_CRON:ORDER_CONN_DEVICES_LOCK';
        $lock = $redis->setnx($lock_key, 'lock');
        if(!$lock) {
            $this->_write_log(__FUNCTION__ . ' lock fail!', true, 'error');
            die('FAILURE!');
        }
        $redis->setex($lock_key, 90, 'order_conn_devices_lock');

        $db = $this->load->database('iwide_soma_r', TRUE);
        $table = $db->dbprefix('soma_shard_link');
        $interIdArr = $db->select('inter_id')
            ->group_by('inter_id')
            ->get($table)
            ->result_array();

        if(!empty($interIdArr)){

            $this->load->model('soma/Sales_order_model','somaSalesOrderModel');
            $somaSalesOrderModel = $this->somaSalesOrderModel;

            foreach($interIdArr as $v){
                $inter_id = $v['inter_id'];
                if( $inter_id )
                {
                    //初始化数据库分片配置，微信接口关闭订单需要初始化shard_id
                    $this->load->model('soma/shard_config_model', 'model_shard_config');
                    $this->current_inter_id= $v['inter_id'];
                    $this->db_shard_config= $this->model_shard_config->build_shard_config( $v['inter_id'] );
                }

                /**
                 * @var Sales_order_model $somaSalesOrderModel
                 */
                $db = $somaSalesOrderModel->_shard_db_r('iwide_soma_r');
                $table = $somaSalesOrderModel->table_name($inter_id);
                $select = 'order_id,conn_devices_status,status';
                $orderList = $db->where( 'inter_id', $inter_id )
                    ->where( 'status', $somaSalesOrderModel::STATUS_PAYMENT )
                    ->where( 'conn_devices_status', $somaSalesOrderModel::CONN_DEVICES_DEFAULT )
                    ->select( $select )
                    ->limit( $limit )
                    ->get( $table )
                    ->result_array();
                if( $orderList )
                {
                    //对接智游宝
                    $this->load->library('Soma/Api_zhiyoubao');
                    $api= new Api_zhiyoubao( $inter_id );
                    foreach( $orderList as $k=>$v )
                    {
                        $api->send_order( $v['order_id'] );
                    }
                }
            }

        }

        $redis->delete($lock_key);
        echo 'ok';
    }

    /**
     * 如果价格配置过了有效期，执行状态修改为无效操作
     * 每5分钟执行
     *
     * todo 和公众号无关
     */
    public function scope_product_auto_update_status()
    {
        $this->_check_access();    //拒绝非法IP执行任务

        $this->_write_log(__FUNCTION__);

        $db = $this->load->database('iwide_soma_r', true);
        $table = $db->dbprefix('soma_shard_link');
        $interIdArr = $db->select('inter_id')
            ->group_by('inter_id')
            ->get($table)
            ->result_array();

        if (!empty($interIdArr)) {

            $this->load->model('soma/adv_model');
            ScopeDiscountService::getInstance()->updateScopeDiscountStatus( $interIdArr );
        }

        echo 'SUCCESS';
    }

    /**
     * 发送短信接口
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.com>
     */
    public function send_sms()
    {
        $this->_check_access();    //拒绝非法IP执行任务
        $this->load->model('soma/Sms_model', 'sms_model');
        $this->sms_model->send_sms();
        // $service = SmsService::getInstance();
        // $service->send_sms();
        echo 'SUCCESS';
    }


    /**
     * 手动模拟微信支付回调
     * @author liguanglong  <liguanglong@mofly.cn>
     */
    public function order(){


//        $list = '[{"inter_id":"a493195389","orderid":"1000371431","transaction_id":"4000492001201708309061014865","state":"SUCCESS"},{"inter_id":"a493195389","orderid":"1000371433","transaction_id":"4001912001201708309061045767","state":"SUCCESS"},{"inter_id":"a493195389","orderid":"1000371435","transaction_id":"4001912001201708309059738914","state":"SUCCESS"},{"inter_id":"a497858474","orderid":"1000371459","transaction_id":"4006282001201708309061832909","state":"SUCCESS"},{"inter_id":"a497858474","orderid":"1000371464","transaction_id":"4006362001201708309064751396","state":"SUCCESS"},{"inter_id":"a497858474","orderid":"1000371484","transaction_id":"4002892001201708309066807965","state":"SUCCESS"},{"inter_id":"a496803399","orderid":"1000371551","transaction_id":"4004442001201708309070786809","state":"SUCCESS"},{"inter_id":"a495012935","orderid":"1000371585","transaction_id":"4008182001201708309067843837","state":"SUCCESS"},{"inter_id":"a496803399","orderid":"1000371587","transaction_id":"4000252001201708309067790373","state":"SUCCESS"},{"inter_id":"a497858474","orderid":"1000371600","transaction_id":"4002402001201708309072373868","state":"SUCCESS"},{"inter_id":"a495782075","orderid":"1000371618","transaction_id":"4002132001201708309069636151","state":"SUCCESS"},{"inter_id":"a495782075","orderid":"1000371624","transaction_id":"4001222001201708309071471740","state":"SUCCESS"},{"inter_id":"a496803399","orderid":"1000371635","transaction_id":"4007512001201708309071591994","state":"SUCCESS"},{"inter_id":"a496803399","orderid":"1000371649","transaction_id":"4007512001201708309074586804","state":"SUCCESS"},{"inter_id":"a496803399","orderid":"1000371657","transaction_id":"4004492001201708309073255367","state":"SUCCESS"},{"inter_id":"a496803399","orderid":"1000371663","transaction_id":"4006342001201708309076411353","state":"SUCCESS"},{"inter_id":"a496803399","orderid":"1000371664","transaction_id":"4004492001201708309074871514","state":"SUCCESS"},{"inter_id":"a493195389","orderid":"1000371691","transaction_id":"4007372001201708309075145736","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371723","transaction_id":"4001412001201708309074256452","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371724","transaction_id":"4004802001201708309077262580","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371725","transaction_id":"4008562001201708309075763920","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371726","transaction_id":"4006562001201708309075281885","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371727","transaction_id":"4004592001201708309079033782","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371728","transaction_id":"4009602001201708309077338424","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371729","transaction_id":"4000222001201708309079097819","state":"SUCCESS"},{"inter_id":"a490321436","orderid":"1000371732","transaction_id":"4008482001201708309079111839","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371737","transaction_id":"4000282001201708309075871094","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371741","transaction_id":"4008492001201708309075916067","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371757","transaction_id":"4000972001201708309077639046","state":"SUCCESS"},{"inter_id":"a493195389","orderid":"1000371763","transaction_id":"4004942001201708309077748297","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371766","transaction_id":"4008572001201708309077774837","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371768","transaction_id":"4004552001201708309082390711","state":"SUCCESS"},{"inter_id":"a495012935","orderid":"1000371773","transaction_id":"4001962001201708309079658292","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371776","transaction_id":"4005312001201708309080866314","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371780","transaction_id":"4001892001201708309079787808","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371786","transaction_id":"4000082001201708309078112226","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371803","transaction_id":"4008122001201708309081359579","state":"SUCCESS"},{"inter_id":"a493717254","orderid":"1000371814","transaction_id":"4004812001201708309084513967","state":"SUCCESS"},{"inter_id":"a483687344","orderid":"1000371820","transaction_id":"4009332001201708309084661605","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371822","transaction_id":"4006712001201708309083178000","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371825","transaction_id":"4008942001201708309081524296","state":"SUCCESS"},{"inter_id":"a493717254","orderid":"1000371827","transaction_id":"4004772001201708309081546744","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371828","transaction_id":"4008912001201708309083219240","state":"SUCCESS"},{"inter_id":"a497339744","orderid":"1000371839","transaction_id":"4009672001201708309084869785","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371861","transaction_id":"4002552001201708309085179208","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371874","transaction_id":"4009282001201708309087055300","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371884","transaction_id":"4007552001201708309085481388","state":"SUCCESS"},{"inter_id":"a488187132","orderid":"1000371897","transaction_id":"4009562001201708309088458611","state":"SUCCESS"},{"inter_id":"a488187132","orderid":"1000371906","transaction_id":"4003872001201708309085726342","state":"SUCCESS"},{"inter_id":"a493717254","orderid":"1000371936","transaction_id":"4001702001201708309087822274","state":"SUCCESS"},{"inter_id":"a483687344","orderid":"1000371944","transaction_id":"4000932001201708309087964728","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371948","transaction_id":"4006562001201708309089094102","state":"SUCCESS"},{"inter_id":"a491466008","orderid":"1000371949","transaction_id":"4005602001201708309090783964","state":"SUCCESS"},{"inter_id":"a483687344","orderid":"1000371954","transaction_id":"4006722001201708309090891139","state":"SUCCESS"},{"inter_id":"a493195389","orderid":"1000371966","transaction_id":"4001692001201708309089347399","state":"SUCCESS"},{"inter_id":"a493195389","orderid":"1000371970","transaction_id":"4001332001201708309089416896","state":"SUCCESS"},{"inter_id":"a483687344","orderid":"1000372007","transaction_id":"4001862001201708309091796957","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000372015","transaction_id":"4001322001201708309090112975","state":"SUCCESS"},{"inter_id":"a493195389","orderid":"1000372016","transaction_id":"4007372001201708309090127917","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000372020","transaction_id":"4001482001201708309090190123","state":"SUCCESS"},{"inter_id":"a493195389","orderid":"1000372029","transaction_id":"4007372001201708309093617018","state":"SUCCESS"},{"inter_id":"a483687344","orderid":"1000372032","transaction_id":"4002522001201708309093676510","state":"SUCCESS"},{"inter_id":"a498529802","orderid":"1000372042","transaction_id":"4009562001201708309096569235","state":"SUCCESS"},{"inter_id":"a493195389","orderid":"1000372043","transaction_id":"4005092001201708309093867630","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000372067","transaction_id":"4007852001201708309096877998","state":"SUCCESS"},{"inter_id":"a493195389","orderid":"1000372070","transaction_id":"4004532001201708309096973668","state":"SUCCESS"},{"inter_id":"a483687344","orderid":"1000372084","transaction_id":"4005642001201708309096196811","state":"SUCCESS"},{"inter_id":"a493195389","orderid":"1000372085","transaction_id":"4007342001201708309100537424","state":"SUCCESS"},{"inter_id":"a483687344","orderid":"1000372095","transaction_id":"4008212001201708309102599123","state":"SUCCESS"},{"inter_id":"a499321368","orderid":"1000372135","transaction_id":"4009202001201708309099851876","state":"SUCCESS"}]';


//        $list = '[{"inter_id":"a493195389","orderid":"1000371431","transaction_id":"4000492001201708309061014865","state":"SUCCESS"},{"inter_id":"a493195389","orderid":"1000371433","transaction_id":"4001912001201708309061045767","state":"SUCCESS"}]';

//        $list = '[{"inter_id":"a493195389","orderid":"1000371435","transaction_id":"4001912001201708309059738914","state":"SUCCESS"},{"inter_id":"a497858474","orderid":"1000371459","transaction_id":"4006282001201708309061832909","state":"SUCCESS"},{"inter_id":"a497858474","orderid":"1000371464","transaction_id":"4006362001201708309064751396","state":"SUCCESS"}]';

//          $list = '[{"inter_id":"a497858474","orderid":"1000371484","transaction_id":"4002892001201708309066807965","state":"SUCCESS"},{"inter_id":"a496803399","orderid":"1000371551","transaction_id":"4004442001201708309070786809","state":"SUCCESS"},{"inter_id":"a495012935","orderid":"1000371585","transaction_id":"4008182001201708309067843837","state":"SUCCESS"},{"inter_id":"a496803399","orderid":"1000371587","transaction_id":"4000252001201708309067790373","state":"SUCCESS"},{"inter_id":"a497858474","orderid":"1000371600","transaction_id":"4002402001201708309072373868","state":"SUCCESS"},{"inter_id":"a495782075","orderid":"1000371618","transaction_id":"4002132001201708309069636151","state":"SUCCESS"},{"inter_id":"a495782075","orderid":"1000371624","transaction_id":"4001222001201708309071471740","state":"SUCCESS"},{"inter_id":"a496803399","orderid":"1000371635","transaction_id":"4007512001201708309071591994","state":"SUCCESS"},{"inter_id":"a496803399","orderid":"1000371649","transaction_id":"4007512001201708309074586804","state":"SUCCESS"},{"inter_id":"a496803399","orderid":"1000371657","transaction_id":"4004492001201708309073255367","state":"SUCCESS"},{"inter_id":"a496803399","orderid":"1000371663","transaction_id":"4006342001201708309076411353","state":"SUCCESS"},{"inter_id":"a496803399","orderid":"1000371664","transaction_id":"4004492001201708309074871514","state":"SUCCESS"},{"inter_id":"a493195389","orderid":"1000371691","transaction_id":"4007372001201708309075145736","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371723","transaction_id":"4001412001201708309074256452","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371724","transaction_id":"4004802001201708309077262580","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371725","transaction_id":"4008562001201708309075763920","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371726","transaction_id":"4006562001201708309075281885","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371727","transaction_id":"4004592001201708309079033782","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371728","transaction_id":"4009602001201708309077338424","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371729","transaction_id":"4000222001201708309079097819","state":"SUCCESS"},{"inter_id":"a490321436","orderid":"1000371732","transaction_id":"4008482001201708309079111839","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371737","transaction_id":"4000282001201708309075871094","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371741","transaction_id":"4008492001201708309075916067","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371757","transaction_id":"4000972001201708309077639046","state":"SUCCESS"},{"inter_id":"a493195389","orderid":"1000371763","transaction_id":"4004942001201708309077748297","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371766","transaction_id":"4008572001201708309077774837","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371768","transaction_id":"4004552001201708309082390711","state":"SUCCESS"},{"inter_id":"a495012935","orderid":"1000371773","transaction_id":"4001962001201708309079658292","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371776","transaction_id":"4005312001201708309080866314","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371780","transaction_id":"4001892001201708309079787808","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371786","transaction_id":"4000082001201708309078112226","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371803","transaction_id":"4008122001201708309081359579","state":"SUCCESS"},{"inter_id":"a493717254","orderid":"1000371814","transaction_id":"4004812001201708309084513967","state":"SUCCESS"},{"inter_id":"a483687344","orderid":"1000371820","transaction_id":"4009332001201708309084661605","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371822","transaction_id":"4006712001201708309083178000","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371825","transaction_id":"4008942001201708309081524296","state":"SUCCESS"},{"inter_id":"a493717254","orderid":"1000371827","transaction_id":"4004772001201708309081546744","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371828","transaction_id":"4008912001201708309083219240","state":"SUCCESS"},{"inter_id":"a497339744","orderid":"1000371839","transaction_id":"4009672001201708309084869785","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371861","transaction_id":"4002552001201708309085179208","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371874","transaction_id":"4009282001201708309087055300","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371884","transaction_id":"4007552001201708309085481388","state":"SUCCESS"},{"inter_id":"a488187132","orderid":"1000371897","transaction_id":"4009562001201708309088458611","state":"SUCCESS"},{"inter_id":"a488187132","orderid":"1000371906","transaction_id":"4003872001201708309085726342","state":"SUCCESS"},{"inter_id":"a493717254","orderid":"1000371936","transaction_id":"4001702001201708309087822274","state":"SUCCESS"},{"inter_id":"a483687344","orderid":"1000371944","transaction_id":"4000932001201708309087964728","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000371948","transaction_id":"4006562001201708309089094102","state":"SUCCESS"},{"inter_id":"a491466008","orderid":"1000371949","transaction_id":"4005602001201708309090783964","state":"SUCCESS"},{"inter_id":"a483687344","orderid":"1000371954","transaction_id":"4006722001201708309090891139","state":"SUCCESS"},{"inter_id":"a493195389","orderid":"1000371966","transaction_id":"4001692001201708309089347399","state":"SUCCESS"},{"inter_id":"a493195389","orderid":"1000371970","transaction_id":"4001332001201708309089416896","state":"SUCCESS"},{"inter_id":"a483687344","orderid":"1000372007","transaction_id":"4001862001201708309091796957","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000372015","transaction_id":"4001322001201708309090112975","state":"SUCCESS"},{"inter_id":"a493195389","orderid":"1000372016","transaction_id":"4007372001201708309090127917","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000372020","transaction_id":"4001482001201708309090190123","state":"SUCCESS"},{"inter_id":"a493195389","orderid":"1000372029","transaction_id":"4007372001201708309093617018","state":"SUCCESS"},{"inter_id":"a483687344","orderid":"1000372032","transaction_id":"4002522001201708309093676510","state":"SUCCESS"},{"inter_id":"a498529802","orderid":"1000372042","transaction_id":"4009562001201708309096569235","state":"SUCCESS"},{"inter_id":"a493195389","orderid":"1000372043","transaction_id":"4005092001201708309093867630","state":"SUCCESS"},{"inter_id":"a494902849","orderid":"1000372067","transaction_id":"4007852001201708309096877998","state":"SUCCESS"},{"inter_id":"a493195389","orderid":"1000372070","transaction_id":"4004532001201708309096973668","state":"SUCCESS"},{"inter_id":"a483687344","orderid":"1000372084","transaction_id":"4005642001201708309096196811","state":"SUCCESS"},{"inter_id":"a493195389","orderid":"1000372085","transaction_id":"4007342001201708309100537424","state":"SUCCESS"},{"inter_id":"a483687344","orderid":"1000372095","transaction_id":"4008212001201708309102599123","state":"SUCCESS"},{"inter_id":"a499321368","orderid":"1000372135","transaction_id":"4009202001201708309099851876","state":"SUCCESS"},{"inter_id":"a499321368","orderid":"1000372135","transaction_id":"4009202001201708309099851876","state":"SUCCESS"}]';

        //2017-08-31 15:22:00
        $list = '[{"inter_id":"a494688060","orderid":"1000371378","transaction_id":"4004252001201708309048301003","state":"SUCCESS"}, {"inter_id":"a495708609","orderid":"so","transaction_id":"4009632001201708309048041265","state":"SUCCESS"}]';

        $list = json_decode($list, true);

        $combineList = [];

        foreach ($list as $key => $val){
            $combineList[$val['orderid']] = ['transaction_id' => $val['transaction_id']];
        }


        $db = $this->load->database('iwide_soma_r', TRUE);
        $table = $db->dbprefix('soma_shard_link');
        $interIdArr = $db->select('inter_id')
            ->group_by('inter_id')
            ->get($table)
            ->result_array();

        //遍历订单
        foreach ($combineList as $items => $values){

            foreach($interIdArr as $v){

                $inter_id = $v['inter_id'];
                if( $inter_id )
                {
                    //初始化数据库分片配置，微信接口关闭订单需要初始化shard_id
                    $this->load->model('soma/shard_config_model', 'model_shard_config');
                    $this->current_inter_id= $v['inter_id'];
                    $this->db_shard_config= $this->model_shard_config->build_shard_config( $v['inter_id'] );
                }

                $this->load->model('soma/Sales_order_model','somaSalesOrderModel');
                $somaSalesOrderModel = $this->somaSalesOrderModel;

                /**
                 * @var Sales_order_model $somaSalesOrderModel
                 */
                $db = $somaSalesOrderModel->_shard_db_r('iwide_soma_r');
                $table = $somaSalesOrderModel->table_name($inter_id);

                $orderList = [];
                foreach ($combineList as $val){
                    $orderList = $db->where( 'order_id', $items )
                        ->select( '*' )
                        ->get( $table )
                        ->result_array();
                }

                if(!empty($orderList)){

                    $orderIDs = array_column($orderList, 'order_id');

                    //明细
                    $this->load->model('soma/Sales_item_package_model', 'salesItemPackageModel');
                    $salesItemPackageModel = $this->salesItemPackageModel;
                    $orderItemList = $salesItemPackageModel->get(['order_id'], [$orderIDs], ['limit' => null]);

                    //支付成功
                    foreach ($orderList as $val){

                        $this->load->model('soma/sales_payment_model');
                        $paymentModel= $this->sales_payment_model;

                        //主单
                        $this->load->model('soma/Sales_order_model', 'salesOrderModel');
                        $salesOrderModel = $this->salesOrderModel;

                        //初始化
                        $salesOrderModel->load($val['order_id']);

                        //细单
                        if(!empty($orderItemList)){
                            foreach ($orderItemList as $vale){
                                if($vale['order_id'] == $val['order_id']){
                                    $salesOrderModel->item = [$vale];
                                    break;
                                }
                            }
                        }
                        if(!$salesOrderModel->item){
                            $this->_write_log('手工支付失败，原因：没有订单明细。订单号：'.$val['order_id']."\r\n");
                            continue;
                        }

                        //微信订单号
                        $transactionId = null;
                        foreach ($combineList as $item => $vale){
                            if($item == $val['order_id']){
                                $transactionId = $vale['transaction_id'];
                                break;
                            }
                        }
                        if(!$transactionId){
                            $this->_write_log('手工支付失败，原因：没有微信订单号。订单号：'.$val['order_id']."\r\n");
                            continue;
                        }

                        $log_data['paid_ip'] = '0.0.0.0';
                        $log_data['paid_type'] = $paymentModel::PAY_TYPE_WX;
                        $log_data['order_id'] = $val['order_id'];
                        $log_data['openid'] = $val['openid'];
                        $log_data['business'] = $val['business'];
                        $log_data['settlement'] = $val['settlement'];
                        $log_data['inter_id'] = $val['inter_id'];
                        $log_data['hotel_id'] = $val['hotel_id'];
                        $log_data['grand_total'] = $val['grand_total'];
                        $log_data['transaction_id'] = $transactionId;

                        print_r($log_data);

                        //保存
                        $salesOrderModel->order_payment($log_data);
                        $salesOrderModel->order_payment_post();
                        $paymentModel->save_payment($log_data, NULL);

                    }

                    echo('成功，订单号：'.$items."\r\n");
                    break;
                }
                else{
                    echo('失败，订单号：'.$items."\r\n");
                }

            }

        }

        exit('结束');

    }


    /**
     * 手动任务
     * 获取泛分销员自己下单自己获取绩效名单
     * @author liguanglong  <liguanglong@mofly.cn>
     */
    public function fansalerorders(){

        $db = $this->load->database('iwide_soma_r', TRUE);
        $table = $db->dbprefix('soma_shard_link');
        $interIdArr = $db->select('inter_id')
                         ->group_by('inter_id')
                         ->get($table)
                         ->result_array();

        //获取某一时间段内所有订单
        $problemOrders = [];
        foreach($interIdArr as $v){
            $inter_id = $v['inter_id'];
            if( $inter_id )
            {
                //初始化数据库分片配置，微信接口关闭订单需要初始化shard_id
                $this->load->model('soma/shard_config_model', 'model_shard_config');
                $this->current_inter_id= $v['inter_id'];
                $this->db_shard_config= $this->model_shard_config->build_shard_config( $v['inter_id'] );
            }
            $this->load->model('soma/Sales_order_model','somaSalesOrderModel');
            $somaSalesOrderModel = $this->somaSalesOrderModel;

            /**
             * @var Sales_order_model $somaSalesOrderModel
             */
            $db = $somaSalesOrderModel->_shard_db_r('iwide_soma_r');
            $table = $somaSalesOrderModel->table_name($inter_id);

            $orderList = $db->where( 'create_time >= ', '2017-09-15 00:00:00' )
                            ->where( 'create_time <= ', '2017-09-15 23:59:59' )
                            ->where('inter_id', $inter_id)
                            ->select( '*' )
                            ->get( $table )
                            ->result_array();
            if(!empty($orderList)){
                foreach ($orderList as $val){
                    $this->load->library('Soma/Api_idistribute');
                    $staff = $this->api_idistribute->get_saler_info($val['inter_id'], $val['openid']);
                    if(!empty($staff)){
                        $saler_type = isset($staff['typ']) && ! empty($staff['typ']) ? $staff['typ'] : '';
                        $saler_id = isset($staff['info']['saler']) && ! empty($staff['info']['saler']) ? $staff['info']['saler'] : 0;
                        if($saler_type == 'FANS' && $val['fans_saler_id'] == $saler_id){
                            $problemOrders[] = [
                                'inter_id' => $val['inter_id'],
                                'order_id' => $val['order_id'],
                                'openid' => $val['openid'],
                                'fans_saler_id' => $val['fans_saler_id'],
                            ];
                        }
                    }
                }
            }
        }

        $content = json_encode($problemOrders);
        $handler = new StreamHandler(APPPATH . 'logs/soma/cron/fansalerorders.log', \Monolog\Logger::DEBUG);
        $this->monoLog->setHandlers(array($handler));
        $this->monoLog->info($content);
    }

}
