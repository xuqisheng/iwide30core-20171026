<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 自动发送礼包（优程定制）
 *
 * @author 杨成峰
 * @copyright www.iwide.cn
 * @version 4.0
 *          @Email 445315045@qq.com
 *         
 */
class AutoGift extends CI_Controller
{

    protected $url = 'http://115.29.185.74:8020/WebSiteApi/api';

    protected $inter_id = 'a480304439';

    protected $update_flag = TRUE;

    protected $ModifyUserID = '519';

    protected $ModifyUserName = '金房卡';

    protected $start_time = 1494993600;

    function __construct()
    {
        parent::__construct();
        $this->load->model('membervip/admin/Public_model', 'pum');
    }

    public function index()
    {
        // $map为pms类型与礼包ID关系数组，key为PMS业务类型，val为对应发的礼包
        $map = [
            '4' => [
                'packgeId' => '315',
                'ruleId' => '649'
            ],
            '5' => [
                'packgeId' => '315',
                'ruleId' => '649'
            ],
            '7' => [
                'packgeId' => '314',
                'ruleId' => '648'
            ],
            '20' => [
                'packgeId' => '513',
                'ruleId' => '910'
            ],
            '1003' => [
                'packgeId' => '315',
                'ruleId' => '649'
            ],
            '1002' => [
                'packgeId' => '314',
                'ruleId' => '648'
            ]
        ];
        $mo = '/EB/GetMebChangeEvent';
        $i = 0;
        foreach ($map as $key => $val) {
            $post_data = [
                'nEventID' => '0',
                'eEMebEventBusType' => $key,
                'nHandelCount' => 0,
                'nPageSize' => '9999'
            ];
            $get_data = $this->GetServer($mo, $post_data);
            if (empty($get_data)) {
                continue;
            }
            foreach ($get_data as $k => $v) {
                $flag = false;
                if ($v['ModifyUserID'] == '519' ||strtotime($v['CreateTime']) < $this->start_time) {
                    continue; // 进行过修改操作或者创建时间在开始之前的忽略 $v['ModifyUserID'] == '519' ||
                }
                $i ++;
                $where['pms_user_id'] = $v['MebID'];
                $where['inter_id'] = $this->inter_id;
                // 查询记录是否存在
                $data = $this->pum->_shard_db()
                    ->where($where)
                    ->get('member_info')
                    ->row_array();
                if (empty($data)) {
                    // 如果是空，则加入记录
                    $flag = $this->add_record($v['MebID'], $key);
                    MYLOG::w("record : " . json_encode([
                        'pms_user_id' => $v['MebID'],
                        'type' => $key
                    ]), 'front/membervip/autogift', 'addrecod');
                } else {
                    // 发送礼包
                    $packge_url = INTER_PATH_URL . 'package/give';
                    $package_data = array(
                        'token' => '',
                        'inter_id' => $this->inter_id,
                        'openid' => $data['open_id'],
                        'uu_code' => uniqid(),
                        'package_id' => $val['packgeId'],
                        'card_rule_id' => $val['ruleId']
                    );
                    $package_res = $this->doCurlPostRequest($packge_url, $package_data);
                    
                    MYLOG::w("package_deposit : " . json_encode($package_res) . ' param: ' . json_encode($package_data), 'front/membervip/autogift', 'autogift');
                    $flag = $this->add_record($v['MebID'], $key, true);
                }
                if ($flag) {
                    // 执行修改记录操作，以确保下一次不会再有重复数据
                    $ChangeEventUrl = '/EB/HandelMebChangeEvent';
                    $EventId = $v['EventID'];
                    $post_data = [
                        'EventID' => $EventId,
                        'State' => 2,
                        'ModifyUserID' => $this->ModifyUserID,
                        'ModifyUserName' => $this->ModifyUserName,
                        'Remarks' => '标记'
                    ];
                    echo json_encode($post_data) . '</br>';
                    if ($this->update_flag) {
                        $res = $this->curl_post_json($ChangeEventUrl, json_encode($post_data));
                    }
                    // var_dump($res);
                    // exit;
                }
            }
        }
        echo $i;
    }

    private function add_record($pms_user_id, $type, $is_send = false)
    {
        $record = $this->pum->_shard_db()
            ->where([
            'pms_user_id' => $pms_user_id
        ])
            ->get('gift_record')
            ->row_array();
        
        $data = [
            'pms_user_id' => $pms_user_id,
            'type' => $type,
            'last_update_time' => date('Y-m-d H:i:s')
        ];
        if ($is_send) {
            $data['is_send'] = 1;
            $data['send_time'] = date('Y-m-d H:i:s');
        }
        if (empty($record)) {
            $data['cretetime'] = date('Y-m-d H:i:s');
            return $this->pum->_shard_db()->insert('gift_record', $data);
        } else {
            if ($record['is_send'] == '1') {
                // 如果已经发送过礼包，则不处理
                return true;
            }
            
            return $this->pum->_shard_db()->update('gift_record', $data, [
                'pms_user_id' => $pms_user_id
            ]);
        }
    }

    private function GetServer($func, $get_data)
    {
        $data = http_build_query($get_data);
        
        // 构造完整请求地址
        
        $url = $this->url . $func . '?' . $data;
        $con = curl_init(urldecode((string) $url));
        curl_setopt($con, CURLOPT_HEADER, false);
        curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($con, CURLOPT_TIMEOUT, (int) 10);
        curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);
        $startTime = microtime(true);
        $res = curl_exec($con);
        // $res = file_get_contents(urldecode($url));
        $end = microtime(true);
        $time = round($end - $startTime, 6);
        $end = microtime(true);
        $time = round($end - $startTime, 6);
        MYLOG::pms_access_record('a480304439', $time, $func, $url, json_encode($get_data), json_encode($res), "优程");
        $res = json_decode($res, true);
        return $res;
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
    protected function doCurlPostRequest($url, $post_data, $timeout = 5)
    {
        $requestString = http_build_query($post_data);
        if ($url == "" || $timeout <= 0) {
            return false;
        }
        $curl = curl_init();
        // 设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        // 设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, false);
        // 设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // 設置請求數據返回的過期時間
        curl_setopt($curl, CURLOPT_TIMEOUT, (int) $timeout);
        // 设置post方式提交
        curl_setopt($curl, CURLOPT_POST, true);
        // 设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $requestString);
        // 执行命令
        $res = curl_exec($curl);
        // 关闭URL请求
        curl_close($curl);
        // 写入日志
        $log_data = array(
            'url' => $url,
            'post_data' => $post_data,
            'result' => $res
        );
        $this->api_write_log(serialize($log_data));
        return json_decode($res, true);
    }

    public function write_log($log, $path = '', $key = '')
    {
        $this->load->library('MYLOG');
        MYLOG::w($log, $path, $key);
    }

    function curl_post_json($url, $json)
    {
        $header[] = "Content-type: text/json";
        $ch = curl_init($this->url . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $start = microtime(true);
        $response = curl_exec($ch);
        $end = microtime(true);
        $time = round($end - $start, 6);
        MYLOG::pms_access_record('a480304439', $time, $url, $url, $json, $response, "优程");
        if (curl_errno($ch)) {
            curl_close($ch);
            return null;
        } else {
            curl_close($ch);
            return $response;
        }
    }

    /**
     * 把请求/返回记录记入文件
     * 
     * @param String $content            
     * @param string $type            
     */
    protected function api_write_log($content, $type = 'request')
    {
        $file = date('Y-m-d_H') . '.txt';
        $path = APPPATH . 'logs' . DS . 'admin' . DS . 'apimember' . DS;
        if (! file_exists($path)) {
            @mkdir($path, 0777, TRUE);
        }
        $CI = & get_instance();
        $ip = $CI->input->ip_address();
        $fp = fopen($path . $file, 'a');
        
        $content = str_repeat('-', 40) . "\n[" . $type . ' : ' . date('Y-m-d H:i:s') . ' : ' . $ip . ']' . "\n" . $content . "\n";
        fwrite($fp, $content);
        fclose($fp);
    }
}

?>