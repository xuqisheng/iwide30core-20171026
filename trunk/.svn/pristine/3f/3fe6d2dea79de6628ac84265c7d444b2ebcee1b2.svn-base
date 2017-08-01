<?php
class Service_model extends MY_Model {
    const TAB_SERVICE = 'hotel_services';
    function __construct() {
        parent::__construct ();
    }
    function get_service($inter_id, $para) {
        $db = $this->load->database ( 'iwide_r1', true );
        $db->where ( array (
                'inter_id' => $inter_id 
        ) );
        if (! empty ( $para ['service_type'] )) {
            $db->where ( 'service_type', $para ['service_type'] );
        }
        if (isset ( $para ['status'] )) {
            $db->where ( 'status', $para ['status'] );
        }
        if (isset ( $para ['add_occasion'] )) {
            is_array ( $para ['add_occasion'] ) ? $db->where_in ( 'add_occasion', $para ['add_occasion'] ) : $db->where ( 'add_occasion', $para ['add_occasion'] );
        }
        $data = $db->get ( self::TAB_SERVICE )->result_array ();
        $services = array ();
        foreach ( $data as $d ) {
            $services [$d ['service_id']] = $d;
        }
        return $services;
    }
    function replace_service($inter_id, $para, $reservice) {
        $services = $this->get_service ( $inter_id, $para );
        foreach ( $reservice as $ak => $ass ) {
            if (! empty ( $services [$ak] )) {
                foreach ( $services [$ak] as $sk => $sv ) {
                    if (! isset ( $reservice [$ak] [$sk] ) || $reservice [$ak] [$sk] == '') {
                        $reservice [$ak] [$sk] = $sv;
                    }
                }
            } else {
                unset ( $reservice [$ak] );
            }
        }
        return $reservice;
    }
    function classify_service($service) {
        $data = array ();
        foreach ( $service as $sk => $s ) {
            $data [$s ['add_rule']] [$sk] = $s;
        }
        return $data;
    }
    function check_service_rule($rule_type, $paras) {
        switch ($rule_type) {
            case 'add_time' :
                return $this->check_add_time_rule ( $paras );
                break;
            default :
                break;
        }
        return FALSE;
    }
    function check_add_time_rule($paras) {
        $add_times = array ();
        $i = 1;
        $today = date ( 'Ymd' );
        $temp = date ( 'YmdH00', strtotime ( $today . $paras ['max_time'] ) );
        for($t = $paras ['begin_time'];; $i ++) {
            if (floatval ( $t ) > floatval ( $temp )) {
                array_pop ( $add_times );
                break;
            }
            if ($i > $paras ['max_num']) {
                break;
            }
            $add_times [] = $i;
            $t = date ( 'YmdH00', strtotime ( '+ 1 hour', strtotime ( $t ) ) );
        }
        return $add_times;
    }
    public function format_book_service($services) {
        $data = array ();
        $data ['transport'] = array ();
        if (! empty ( $services ['transport'] ) && $services ['transport'] ['status'] == 1) {
            if (! empty ( $services ['transport'] ['pickup'] ) || ! empty ( $services ['transport'] ['takeoff'] )) {
                $data ['transport'] ['des'] = $services ['transport'] ['des'];
                $data ['transport'] ['tips'] = $services ['transport'] ['tips'];
                if (! empty ( $services ['transport'] ['pickup'] ['ways'] )) {
                    $data ['transport'] ['pickup'] ['des'] = $services ['transport'] ['pickup'] ['des'];
                    foreach ( $services ['transport'] ['pickup'] ['ways'] as $w ) {
                        $fdata = $this->service_format ( 'transport', $w );
                        $data ['transport'] ['pickup'] ['ways'] [$w] ['name'] = $fdata ['name'];
                        $data ['transport'] ['pickup'] ['ways'] [$w] ['items'] = $fdata ['items'];
                    }
                }
                if (! empty ( $services ['transport'] ['takeoff'] ['ways'] )) {
                    $data ['transport'] ['takeoff'] ['des'] = $services ['transport'] ['takeoff'] ['des'];
                    foreach ( $services ['transport'] ['takeoff'] ['ways'] as $w ) {
                        $fdata = $this->service_format ( 'transport', $w );
                        $data ['transport'] ['takeoff'] ['ways'] [$w] ['name'] = $fdata ['name'];
                        $data ['transport'] ['takeoff'] ['ways'] [$w] ['items'] = $fdata ['items'];
                    }
                }
            }
            if (! empty ( $services ['parking'] ['status'] )) {
                $data ['parking'] ['des'] = $services ['parking'] ['des'];
                $fdata = $this->service_format ( 'parking', $w );
                $data ['parking'] ['items'] = $fdata ['items'];
            }
            if (! empty ( $services ['invoice'] ['status'] )) {
                $data ['invoice']['status'] = 1;
            }
        }
        return $data;
    }
    public function service_format($type, $way) {
        $fdata = array ();
        switch ($type) {
            case 'transport' :
                switch ($way) {
                    case 'train' :
                        $fdata ['name'] = '火车';
                        $fdata ['items'] [] = array (
                                'name' => 'train_name',
                                'des' => '车站',
                                'tips' => '请输入站名',
                                'tag' => 'input',
                                'type' => 'text' 
                        );
                        $fdata ['items'] [] = array (
                                'name' => 'train_no',
                                'des' => '车次',
                                'tips' => '请输入车次',
                                'tag' => 'input',
                                'type' => 'text' 
                        );
                        $fdata ['items'] [] = array (
                                'name' => 'train_time',
                                'des' => '到达时间',
                                'tips' => '到达时间',
                                'tag' => 'input',
                                'type' => 'datetime' 
                        );
                        break;
                    case 'airport' :
                        $fdata ['name'] = '飞机';
                        $fdata ['items'] [] = array (
                                'name' => 'airport_name',
                                'des' => '机场名',
                                'tips' => '请输入机场名',
                                'tag' => 'input',
                                'type' => 'text' 
                        );
                        $fdata ['items'] [] = array (
                                'name' => 'plain_no',
                                'des' => '航班号',
                                'tips' => '请输入航班号',
                                'tag' => 'input',
                                'type' => 'text' 
                        );
                        $fdata ['items'] [] = array (
                                'name' => 'plain_time',
                                'des' => '到达时间',
                                'tips' => '到达时间',
                                'tag' => 'input',
                                'type' => 'datetime' 
                        );
                        break;
                    default :
                        break;
                }
                break;
            case 'parking' :
                $fdata ['items'] [] = array (
                        'name' => 'need_parking',
                        'des' => '否',
                        'tag' => 'input',
                        'type' => 'radio',
                        'value' => 0,
                        'default' => 1 
                );
                $fdata ['items'] [] = array (
                        'name' => 'need_parking',
                        'des' => '是',
                        'tag' => 'input',
                        'type' => 'radio',
                        'value' => 1 
                );
                $fdata ['items'] [] = array (
                        'name' => 'car_no',
                        'des' => '车牌号',
                        'tag' => 'input',
                        'type' => 'text',
                        'tips' => '车牌号' 
                );
                break;
        }
        return $fdata;
    }
    public function check_book_formdata($formdata, $rule, $type = 'addit_service', $params = array()) {
        $info = array (
                's' => 0 
        );
        $this->load->helper('string');
        switch ($type) {
            case 'addit_service' :
                if (isset ( $formdata ['addit_service_pickup_val'] ) && $formdata ['addit_service_pickup_val'] != 'none') {
                    if (isset ( $rule ['transport'] ['pickup'] ) && in_array ( $formdata ['addit_service_pickup_val'], $rule ['transport'] ['pickup'] ['ways'] ) && $rule ['transport'] ['status'] == 1) {
                        switch ($formdata ['addit_service_pickup_val']) {
                            case 'airport' :
                                if (empty ( $formdata ['addit_service_pickup_airport'] )) {
                                    $info ['errmsg'] = '请输入机场名';
                                    return $info;
                                } else if (empty ( $formdata ['addit_service_pickup_plain'] )) {
                                    $info ['errmsg'] = '请输入航班班次';
                                    return $info;
                                } else if (empty ( $formdata ['addit_service_pickup_plaintime'] ) 
                                        || strtotime ( $formdata ['addit_service_pickup_plaintime'] ) > strtotime ( $params ['enddate'] ) + 86399
                                        || strtotime ( $formdata ['addit_service_pickup_plaintime'] ) < strtotime ( $params ['startdate'] ) 
                                        ) {
                                    $info ['errmsg'] = '请输入正确的到达时间';
                                    return $info;
                                } else {
                                    $info ['data'] ['pickup'] ['way'] = 'plain';
                                    $info ['data'] ['pickup'] ['station'] = sbc_dbc_tran($formdata ['addit_service_pickup_airport']);
                                    $info ['data'] ['pickup'] ['tno'] = sbc_dbc_tran($formdata ['addit_service_pickup_plain']);
                                    $info ['data'] ['pickup'] ['arrtime'] = date ( 'Y-m-d H:i:s', strtotime ( $formdata ['addit_service_pickup_plaintime'] ) );
                                }
                                break;
                            case 'train' :
                                if (empty ( $formdata ['addit_service_pickup_trainstation'] )) {
                                    $info ['errmsg'] = '请输入车站名';
                                    return $info;
                                } else if (empty ( $formdata ['addit_service_pickup_train'] )) {
                                    $info ['errmsg'] = '请输入火车班次';
                                    return $info;
                                } else if (empty ( $formdata ['addit_service_pickup_traintime'] ) 
                                        || strtotime ( $formdata ['addit_service_pickup_traintime'] ) > strtotime ( $params ['enddate'] ) + 86399
                                        || strtotime ( $formdata ['addit_service_pickup_traintime'] ) < strtotime ( $params ['startdate'] ) 
                                        ) {
                                    $info ['errmsg'] = '请输入正确的到达时间';
                                    return $info;
                                } else {
                                    $info ['data'] ['pickup'] ['way'] = 'train';
                                    $info ['data'] ['pickup'] ['station'] = sbc_dbc_tran($formdata ['addit_service_pickup_trainstation']);
                                    $info ['data'] ['pickup'] ['tno'] = sbc_dbc_tran($formdata ['addit_service_pickup_train']);
                                    $info ['data'] ['pickup'] ['arrtime'] = date ( 'Y-m-d H:i:s', strtotime ( $formdata ['addit_service_pickup_traintime'] ) );
                                }
                                break;
                            default :
                                $info ['errmsg'] = '不支持所选接送方式';
                                return $info;
                                break;
                        }
                    } else {
                        $info ['errmsg'] = '暂无接送服务';
                        return $info;
                    }
                }
                if (isset ( $formdata ['addit_service_takeoff_val'] ) && $formdata ['addit_service_takeoff_val'] != 'none') {
                    if (isset ( $rule ['transport'] ['takeoff'] ) && in_array ( $formdata ['addit_service_takeoff_val'], $rule ['transport'] ['takeoff'] ['ways'] ) && $rule ['transport'] ['status'] == 1) {
                        switch ($formdata ['addit_service_takeoff_val']) {
                            case 'airport' :
                                if (empty ( $formdata ['addit_service_takeoff_airport'] )) {
                                    $info ['errmsg'] = '请输入机场名';
                                    return $info;
                                } else if (empty ( $formdata ['addit_service_takeoff_plain'] )) {
                                    $info ['errmsg'] = '请输入航班班次';
                                    return $info;
                                } else if (empty ( $formdata ['addit_service_takeoff_plaintime'] ) 
                                           || strtotime ( $formdata ['addit_service_takeoff_plaintime'] ) < strtotime ( $params ['startdate'] ) 
                                           || strtotime ( $formdata ['addit_service_takeoff_plaintime'] ) > strtotime ( $params ['enddate'] ) + 86399
                                           || (! empty ( $info ['data'] ['pickup'] ['arrtime'] ) && strtotime ( $formdata ['addit_service_takeoff_plaintime'] ) < strtotime ( $info ['data'] ['pickup'] ['arrtime'] ))
                                        ) {
                                    $info ['errmsg'] = '请输入正确的回程时间';
                                    return $info;
                                } else {
                                    $info ['data'] ['takeoff'] ['way'] = 'plain';
                                    $info ['data'] ['takeoff'] ['station'] = sbc_dbc_tran($formdata ['addit_service_takeoff_airport']);
                                    $info ['data'] ['takeoff'] ['tno'] = sbc_dbc_tran($formdata ['addit_service_takeoff_plain']);
                                    $info ['data'] ['takeoff'] ['arrtime'] = date ( 'Y-m-d H:i:s', strtotime ( $formdata ['addit_service_takeoff_plaintime'] ) );
                                }
                                break;
                            case 'train' :
                                if (empty ( $formdata ['addit_service_takeoff_trainstation'] )) {
                                    $info ['errmsg'] = '请输入车站名';
                                    return $info;
                                } else if (empty ( $formdata ['addit_service_takeoff_train'] )) {
                                    $info ['errmsg'] = '请输入火车班次';
                                    return $info;
                                } else if (empty ( $formdata ['addit_service_takeoff_traintime'] ) 
                                           || strtotime ( $formdata ['addit_service_takeoff_traintime'] ) < strtotime ( $params ['startdate'] ) 
                                           || strtotime ( $formdata ['addit_service_takeoff_traintime'] ) > strtotime ( $params ['enddate'] ) + 86399
                                           || (! empty ( $info ['data'] ['pickup'] ['arrtime'] ) && strtotime ( $formdata ['addit_service_takeoff_traintime'] ) < strtotime ( $info ['data'] ['pickup'] ['arrtime'] ))
                                          ) {
                                    $info ['errmsg'] = '请输入正确的回程时间';
                                    return $info;
                                } else {
                                    $info ['data'] ['takeoff'] ['way'] = 'train';
                                    $info ['data'] ['takeoff'] ['station'] = sbc_dbc_tran($formdata ['addit_service_takeoff_trainstation']);
                                    $info ['data'] ['takeoff'] ['tno'] = sbc_dbc_tran($formdata ['addit_service_takeoff_train']);
                                    $info ['data'] ['takeoff'] ['arrtime'] = date ( 'Y-m-d H:i:s', strtotime ( $formdata ['addit_service_takeoff_traintime'] ) );
                                }
                                break;
                            default :
                                $info ['errmsg'] = '不支持所选回程接送方式';
                                return $info;
                                break;
                        }
                    } else {
                        $info ['errmsg'] = '暂无回程接送服务';
                        return $info;
                    }
                }
                if (isset ( $formdata ['addit_service_need_parking_val'] ) && $formdata ['addit_service_need_parking_val'] == 1) {
                    if (isset ( $rule ['parking'] ) && $rule ['parking'] ['status'] == 1) {
                        if (empty ( $formdata ['addit_service_car_no'] )) {
                            $info ['errmsg'] = '请输入车牌号';
                            return $info;
                        } else {
                            $info ['data'] ['parking'] ['car_no'] = $formdata ['addit_service_car_no'];
                        }
                    } else {
                        $info ['errmsg'] = '暂不能预留车位';
                        return $info;
                    }
                }
                if (isset ( $formdata ['invoice_val'] ) && $formdata ['invoice_val'] == 1) {
                    if (isset ( $rule ['invoice'] ) && $rule ['invoice'] ['status'] == 1) {
                        if (empty ( $formdata ['invoice'] )) {
                            $info ['errmsg'] = '请填写发票信息';
                            return $info;
                        } else {
                            $invoice_info=json_decode($formdata ['invoice'],TRUE);
                            if ($invoice_info){
                                if (!empty($invoice_info['invoice_id'])){
                                    $this->load->model ( 'invoice/Invoice_model' );
                                    $invoice=$this->Invoice_model->getInvoiceById($params['openid'],$invoice_info['invoice_id'],$params['inter_id']);
                                    if ($invoice){
                                        $info ['data']['invoice']=array('title'=>$invoice['title'],'type'=>$invoice['type'],'id'=>$invoice_info['invoice_id']);
                                        if($invoice['type']==2){
                                            $info['data']['invoice']['content']=json_decode($invoice['content'],TRUE);
                                        }
                                    }else{
                                        $info ['errmsg'] = '所选发票不存在';
                                        return $info;
                                    }
                                }else if (($invoice_info['invoice_type']=='pp' ||$invoice_info['invoice_type']=='zz') && !empty($invoice_info['title'])){
                                    $type=$invoice_info['invoice_type']=='zz'?2:1;
                                    $this->load->model ( 'invoice/Invoice_model' );
                                    $invoice_result=$this->Invoice_model->add_invoice($params['inter_id'],$params['openid'],$invoice_info['title'],$type,$invoice_info);
                                    if ($invoice_result['s']==1){
                                        $info ['data']['invoice']=array('title'=>$invoice_info['title'],'type'=>$type,'id'=>$invoice_result['invoice_id']);
                                        if($type==2){
                                            $info['data']['invoice']['content']=$invoice_info;
                                        }
                                    }else {
                                        $info ['errmsg'] = $invoice_result['errmsg'];
                                        return $info;
                                    }
                                }else{
                                    $info ['errmsg'] = '发票信息错误';
                                    return $info;
                                }
                            }else {
                                $info ['errmsg'] = '发票信息不能为空';
                                return $info;
                            }
                        }
                    } else {
                        $info ['errmsg'] = '暂不支持开具发票';
                        return $info;
                    }
                }
                $info ['s'] = 1;
                return $info;
                break;
            case 'multi_inner' :
                if (! empty ( $rule ['multi_fill'] )) {
                    $roomnums = empty ( $params ['roomnums'] ) ? 1 : $params ['roomnums'];
                    if (! empty ( $rule ['adult'] ['num'] )) {
                        if (empty ( $formdata ['multi_inners_adult'] ) || count ( $formdata ['multi_inners_adult'] ) != $rule ['adult'] ['num'] * $roomnums) {
                            $info ['errmsg'] = '请输入入住成人名';
                            return $info;
                        } else {
                            $i = 0;
                            $j = 0;
                            array_shift($formdata ['multi_inners_adult']);
                            array_unshift($formdata ['multi_inners_adult'], $params ['first_man']);
                            foreach ( $formdata ['multi_inners_adult'] as $adult ) {
                                if (empty ( $adult )) {
                                    $info ['errmsg'] = '请输入正确的入住成人名';
                                    return $info;
                                }
                                if ($i >= $rule ['adult'] ['num']) {
                                    $i = 0;
                                    $j ++;
                                }
                                $info ['data'] [$j] ['adults'] [$i] ['name'] = $adult;
                                $i ++;
                            }
                        }
                    }
                    if (! empty ( $rule ['child'] ['num'] )) {
                        if (empty ( $formdata ['multi_inners_child'] ) || count ( $formdata ['multi_inners_child'] ) != $rule ['child'] ['num'] * $roomnums) {
                            $info ['errmsg'] = '请输入正确的入住儿童名';
                            return $info;
                        }
                        if (! empty ( $rule ['child'] ['birthday'] )) {
                            if (empty ( $formdata ['multi_inners_child_birthday'] ) || count ( $formdata ['multi_inners_child_birthday'] ) != $rule ['child'] ['num'] * $roomnums) {
                                $info ['errmsg'] = '请输入入住儿童生日';
                                return $info;
                            } else {
                                $formdata ['multi_inners_child_birthday'] = array_values ( $formdata ['multi_inners_child_birthday'] );
                            }
                        }
                        $i = 0;
                        $j = 0;
                        foreach ( $formdata ['multi_inners_child'] as $child ) {
                            if (empty ( $child )) {
                                $info ['errmsg'] = '请输入入住儿童名';
                                return $info;
                            }
                            if ($i >= $rule ['child'] ['num']) {
                                $i = 0;
                                $j ++;
                            }
                            $info ['data'] [$j] ['children'] [$i] ['name'] = $child;
                            if (! empty ( $rule ['child'] ['birthday'] )) {
                                if (strtotime ( $formdata ['multi_inners_child_birthday'] [$i] ) >= time ()) {
                                    $info ['errmsg'] = '请输入正确的入住儿童生日';
                                    return $info;
                                } else {
                                    $info ['data'] [$j] ['children'] [$i] ['birthday'] = date ( 'Y-m-d', strtotime ( $formdata ['multi_inners_child_birthday'] [$i] ) );
                                }
                            }
                            $i ++;
                        }
                    }
                    if (! empty ( $rule ['baby'] ['choose'] )) {
                        if (! empty ( $formdata ['multi_inners_baby_inn_val'] )) {
                            if (empty ( $formdata ['multi_inners_baby_num'] ) || intval ( $formdata ['multi_inners_baby_num'] ) <= 0) {
                                $info ['errmsg'] = '请输入婴儿数';
                                return $info;
                            }
                            $baby_num = intval ( $formdata ['multi_inners_baby_num'] );
                            if ($baby_num > 20) {
                                $info ['errmsg'] = '婴儿数过多，请重新输入';
                                return $info;
                            }
                            $info ['data'] [0] ['baby'] ['num'] = $baby_num;
                            // for($i = 0; $i < $baby_num;) {
                            // foreach ( $info ['data'] as $k => $d ) {
                            // $i ++;
                            // $info ['data'] [$k] ['baby'] ['num'] = isset ( $info ['data'] [$k] ['baby'] ['num'] ) ? $info ['data'] [$k] ['baby'] ['num'] + 1 : 1;
                            // if ($i >= $baby_num)
                            // break;
                            // }
                            // }
                        }
                    }
                }
                $info ['s'] = 1;
                return $info;
                break;
            default :
                $info ['errmsg'] = '暂无此服务';
                return $info;
        }
    }
}