<?php

class Test extends MY_Front_Member
{
    
    const SEND_URL = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=';
    
    function test1()
    {
        ini_set('memory_limit', - 1); // 无内存限制
        set_time_limit(0); // 无时间限制
        $dataset = $this->read_Excel('wn.xlsx');
        // var_dump($dataset);
        $this->make_data($dataset, [
            'membership_number' => 0,
            'id_card_no' => 7,
            'name' => 2,
            'phone' => 6,
            'credit' => 9,
            'birth' => 4,
            'sex' => 3
        ], 'a497596757', true, 995);
        
        exit();
    }
    
    // function test2(){
    // $str='{"cat_id":"","brand_name":"1231","card_type":"1","module":["shop","vip"],"title":"124","sub_title":"124","card_stock":"124","logo_url":"","notice":"1","card_note":"1","description":"","is_online":"1","can_give_friend":"f","passwd":"","page_config":"","header_url":"","hotel_header_url":"","soma_header_url":"","shop_header_url":"","least_cost":"1","over_limit":"12","reduce_cost":"1","remark":"","time_start":1498752000,"time_end":1499443200,"use_time_start":1498752000,"use_time_end_model":"g","use_time_end":1499443200,"is_active":"t","is_f":"f","inter_id":"a421641095","createtime":1498806608}';
    // $data=json_decode($str,true);
    // $this->load->model('membervip/common/Public_model','pm_model');
    // $list_fields=$this->pm_model->_shard_db()->list_fields('card');
    // foreach ($data as $key => $item){
    // if(!in_array($key, $list_fields)) unset($data[$key]);
    // }
    // var_dump($data);
    // }
    function test2()
    {
        ini_set('memory_limit', - 1); // 无内存限制
        set_time_limit(0); // 无时间限制
        $dataset = $this->read_Excel('bgy.xlsx');
        unset($dataset['1']);
        // var_dump($dataset);
        $new = array();
        $ext=array();
        foreach ($dataset as $key => $val) {
            if (isset($new[$val['0']])){
                $ext[]=$val['0'];
                continue;
            }
            unset($val['2']);
            unset($val['3']);
            unset($val['4']);
            unset($val['10']);
            unset($val['11']);
            foreach ($val as $k => $v) {
                // 去除多余符号
                $v = trim($v);
                $val[$k] = str_replace('`', '', $v);
            }
            $new[$val['0']]['inter_id'] = 'a421641095';
            $new[$val['0']]['openid'] = $val['0'];
            $new[$val['0']]['code'] = $val['1'];
            // $new[$val['0']]['balance'] = $val['5'];
            // $new[$val['0']]['name'] = $val['6'];
            $new[$val['0']]['password'] = $val['6'];
            // $new[$val['0']]['id_card_no'] = $val['8'];
        }
        //         自动绑定
        foreach ($ext as $b=> $a){
            unset($new[$a]);
        }
        
//         var_dump($new);exit;
        $post_save_url = PMS_PATH_URL . "member/bind_gift_card_save";
        
        $res=[];
        foreach ($new as $key=>$val){
//             var_dump($val);
            $flag=$this->get($val['inter_id'],$val['openid']);
            var_dump($flag);
            if(!$flag){
                continue;
            }
            $res[$key] = parent::doCurlPostRequest($post_save_url, $val);
            
        }
        
        var_dump($res);
        
        exit();
    }
    
    function get($inter_id, $openid)
    {
        $post_center_url = PMS_PATH_URL . "member/center";
        $post_center_data = array(
            'inter_id' => $inter_id,
            'openid' => $openid
        );
        $center_data = $this->doCurlPostRequest($post_center_url, $post_center_data)['data'];
        var_dump($center_data);
        if ($center_data['is_login'] == 'f' && $center_data['value'] == 'login') {
            // redirect('membervip/login?id=' . $this->inter_id);
            return false;
        }
        $this->center_data = $center_data;
        return true;
    }
    
    /**
     * 读取Excel表格内容，返回数组
     *
     * @param
     *            path 文件名，iwide3_0下存放的excel文件
     * @param
     *            array
     */
    function read_Excel($path, $excel_type = 'Excel2007')
    {
        $this->load->library('PHPExcel/IOFactory');
        $objPHPExcel = IOFactory::createReader($excel_type);
        // PHPExcel_Shared_Date::ExcelToPHP($value)
        $data = $objPHPExcel->load(APPPATH . $path);
        $sheet = $data->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumm = $sheet->getHighestColumn();
        
        for ($row = 1; $row <= $highestRow; $row ++) { // 行数是以第1行开始
            for ($column = 'A'; $column <= $highestColumm; $column ++) { // 列数是以第0列开始
                $dataset[$row][] = trim($sheet->getCell($column . $row)->getValue());
            }
        }
        return $dataset;
    }
    
    /**
     * 组装数据 兼备插入数据库功能
     *
     * @param
     *            dataset 需要处理的数据
     * @param
     *            init 處理數據用的配置項
     * @param
     *            insert 是否需要插入數據庫
     * @return array
     */
    function make_data($dataset, $init, $inter = '', $insert = false, $level = 0)
    {
        $filtration = false;
        if (! empty($init['notnull'])) {
            // 如果此项有配置，则过滤不符合条件的记录
            $notnull = $init['notnull'];
            unset($init['notnull']);
            $filtration = true;
        }
        foreach ($dataset as $key => $val) {
            if ($filtration) {
                $continue = false;
                foreach ($notnull as $k => $v) {
                    if (empty($val[$v])) {
                        $continue = true;
                        break;
                    }
                }
                if ($continue) {
                    continue;
                }
            }
            foreach ($init as $e => $a) {
                if ($e == 'sex') {
                    $newdata[$key][$e] = $val[$a] == '男' ? 1 : 2;
                } else
                    $newdata[$key][$e] = $val[$a];
                    
                    if ($e == 'birth') {
                        $newdata[$key][$e] = PHPExcel_Shared_Date::ExcelToPHP($val[$a]);
                    }
            }
        }
        $this->load->model('membervip/admin/Public_model', 'pum');
        foreach ($newdata as $key => $val) {
            if (! empty($level)) {
                $val['level'] = $level;
            }
            $data_db[$key]['inter_id'] = $inter;
            $data_db[$key]['pms_mark'] = $val['phone'];
            $data_db[$key]['type'] = 'member_info';
            $data_db[$key]['value'] = json_encode($val);
            $data_db[$key]['status'] = 1;
            if ($insert) {
                $res = $this->pum->_shard_db()->insert('temp_record', $data_db[$key]);
            }
        }
        echo '<pre>';
        print_r($data_db);
        exit();
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
    protected function doCurlPostRequest_1($url, $post_data, $timeout = 5)
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
            $result = $this->doCurlPostRequest_wx($url, $json_data);
            // 保存日志
            MYLOG::w(json_encode(array(
                'res' => $result,
                'url' => $url,
                'data' => $json_data
            )), 'front/membervip/verify', 'request_send_template');
            
            $result_data = json_decode($result, true);
            if ($result_data['errcode'] == 0 && $result_data['errmsg'] == 'ok') {
                return $this->return_json('发送成功', $result_data['errcode'], true);
            } elseif ($result_data['errcode'] == '40001') {
                $access_token = $this->access_token_model->reflash_access_token($inter_id);
                $url = self::SEND_URL . $access_token;
                $result = $this->doCurlPostRequest_wx($url, $json_data);
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
                $result = $this->doCurlPostRequest_wx($url, $json_data);
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
    
    function doCurlPostRequest_wx($url, $requestString, $extra = array(), $timeout = 20)
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
        // var_dump(curl_error($con));
        return $res;
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
}
?>

