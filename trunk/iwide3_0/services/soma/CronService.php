<?php
namespace App\services\soma;

use App\libraries\Support\Log;
use App\models\soma\Activity_killsec;
use App\services\BaseService;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;

/**
 *
 * 以后使用命名空间！！！
 *
 *
 * Class CronService
 * @package App\services\soma
 * @author renshuai  <renshuai@mofly.cn>
 *
 * @date 2017-05-22
 *
 */
class CronService extends BaseService
{
    /**
     * 获取服务实例方法
     * @return CronService
     */
    public static function getInstance()
    {
        return self::init(self::class);
    }


    public function send()
    {
        $this->serviceLogHandler(__CLASS__);

        $inter_id = 'a450089706';

        $this->getCI()->load->model('wx/access_token_model');
        $access_token = $this->getCI()->access_token_model->reflash_access_token ( $inter_id );

        $this->getCI()->monoLog->notice('refresh access token', [$access_token]);
        return 1;
    }

    /**
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function sendKillsecBeginNotice()
    {

        $this->serviceLogHandler(__CLASS__);
        $this->getCI()->load->model('soma/Activity_killsec_model');
        $this->getCI()->load->model('soma/Activity_killsec_notice_model');
        $this->getCI()->load->model('soma/Message_wxtemp_template_model');
        $this->getCI()->load->model('wx/access_token_model');
        $this->getCI()->load->model( 'soma/Message_wxtemp_record_model', 'MessageWxtempRecordModel' );

        $recordModel = $this->getCI()->MessageWxtempRecordModel;

        /**
         * @var \Activity_killsec_model $activityKillsecModel
         */
        $activityKillsecModel = $this->getCI()->Activity_killsec_model;

        /**
         * @var \Activity_killsec_notice_model $activityKillsecNoticeModel
         */
        $activityKillsecNoticeModel = $this->getCI()->Activity_killsec_notice_model;

        /**
         * @var \Message_wxtemp_template_model $messageModel
         */
        $messageModel = $this->getCI()->Message_wxtemp_template_model;

        //拉取待发送的消息的列表
        $limit = 500;
        $waitingList = $activityKillsecModel->get_waiting_notices($limit);
        $this->getCI()->monoLog->notice(' sendKillsecBeginNotice wait list count is ', [
            'count' => count($waitingList)
        ]);
        $interIDs = [];
        foreach($waitingList as $item) {
            if(!in_array($item['inter_id'], $interIDs)) {
                $interIDs[] = $item['inter_id'];
            }
        }

        //拉取公众号的模版消息详情
        $type = \Message_wxtemp_template_model::TEMPLATE_KILLSEC_SUBSCRIBER;//订阅秒杀活动通知
        $templateInfos = $messageModel->get_template_detail_by_type($interIDs, $type);

        $templateInfoArr = [];
        foreach($templateInfos as $info) {
            $access_token = $this->getCI()->access_token_model->get_access_token( $info['inter_id'] );
            $info['access_token'] = $access_token;
            $templateInfoArr[$info['inter_id']] = $info;
        }

        //拼装数据
        $business = 'package';
        $templateMessageArr = [];
        $failList = [];
        foreach($waitingList as $item) {
            $inter_id = $item['inter_id'];
            $openid = $item['openid'];
            $template_id = $templateInfoArr[$inter_id]['template_id'];

            $array['name'] = $item['product_name']. '(秒杀商品)';
            $array['time'] = $item['killsec_time'];
            $array['address'] = '';
            $array['money'] = '￥'. $item['killsec_price']. '(秒杀价)';
            $array['product_id'] = $item['product_id'];
            $sort_array = $messageModel->get_template_send_sort();
            $array['sort'] = $sort_array[$type];

            //todo create_template_message内部方法需要优化
            $templateMessageArr[] = $messageModel->create_template_message( $openid, $template_id, $type, $array, $inter_id, $business );
        }

        //并发请求，暂定50并发！！！！！！！
        $client = new Client();

        $requests = function ($template_message_arr, $templateInfoArr) {
            foreach($template_message_arr as $item) {
                $access_token = $templateInfoArr[$item['data']['inter_id']]['access_token'];
                $uri = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token;
                yield new Request('POST', $uri, [], $item['message']);
            }
        };

        $failList = [];

        $pool = new Pool($client, $requests($templateMessageArr, $templateInfoArr), [
            'concurrency' => 50,
            'fulfilled' => function ($response, $index) use ($recordModel, $messageModel, &$templateMessageArr, &$waitingList, &$failList){
                $body = $response->getBody()->getContents();

                $result_array = json_decode($body, true);
                $return = [];

                if( isset($result_array['errcode']) && ($result_array['errcode'] == 0 || $result_array['errcode'] == 43004) ){
                    $return['status'] = 1;
                    $return['message'] = '发送成功';
                    $templateMessageArr[$index]['response'] = $body;

                }else{
                    $return['status'] = 2;
                    $return['message'] = '发送失败';

                    $failList[$index] = $waitingList[$index];
                    unset($templateMessageArr[$index]);
                    unset($waitingList[$index]);
                }

            },
            'rejected' => function ($reason, $index) {
                Log::debug('sendKillsecBeginNotice  rejected ', [$index, $reason]);
            }
        ]);

        $promise = $pool->promise();
        $promise->wait();

        if(!empty($failList)){
            foreach($failList as $val){
                $activityKillsecNoticeModel->increase($val['notice_id'], 'send_count', 1);
            }
        }

        if (!empty($waitingList)) {
            $this->getCI()->monoLog->notice('sendKillsecBeginNotice trans begin');

            $this->getCI()->soma_db_conn->trans_begin();
            //批量插入发送记录
            $recordArr = [];
            foreach($templateMessageArr as $templateMessage) {
                $data = $templateMessage['data'];
                $data['result'] = $templateMessage['response'];
                $data['create_time'] = date( "Y-m-d H:i:s", time() );
                $data['status'] = $recordModel::STATUS_SUCCESS;
                unset( $data['sort'] );

                $recordArr[] = $data;
            }

            $saveRecordResult = $recordModel->save_records($recordArr, true);

            //发送成功修改notice状态
            $noticeIDs = [];
            foreach($waitingList as $item) {
                if(!in_array($item['notice_id'], $noticeIDs)) {
                    $noticeIDs[] = $item['notice_id'];
                }
            }
            $updateNoticeResult = $activityKillsecModel->update_waiting_notice_by_ids($noticeIDs);

            if ($this->getCI()->soma_db_conn->trans_complete()) {
                $this->getCI()->soma_db_conn->trans_commit();

                $this->getCI()->monoLog->notice('sendKillsecBeginNotice trans complete ');
            } else {

                $this->getCI()->monoLog->notice('sendKillsecBeginNotice trans fail ', [
                    'saveRecordResult' => $saveRecordResult,
                    'updateNoticeResult' => $updateNoticeResult
                ]);
                $this->getCI()->soma_db_conn->trans_rollback();
            }
        }
    }


    /**
     *
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function killsecInit()
    {
        $killsecModel = new Activity_killsec();
        $activitys = $killsecModel->getPrepareList('act_id, inter_id, status, type, killsec_time', 100);

        foreach($activitys as $key => $val) {
            $result = [
                'act_id' => $val['act_id'],
                'result' => KillsecService::getInstance()->initData($val['act_id'])
            ];
            
            yield $result;
        }
    }




}