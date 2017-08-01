<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Statis_sales extends MY_Admin_Soma {

    protected $label_controller= '订单数据';		//在文件定义
    protected $label_action= '';				//在方法中定义
    
    const MAX_DAYS= 90;  //查看最长统计天数
    const MIN_DAYS= 1;  //查看最短统计天数

    const DATA_LEVEL_INTER = 'inter';  // 数据层次：公众号
    const DATA_LEVEL_HOTEL = 'hotel';  // 数据测试：酒店
    
    public function index()
    {
		$this->label_action= '对比及图表分析';
		$this->_init_breadcrumb($this->label_action);
		
		//$date_sort= $this->input->post('sort');
		$end= $this->input->post('date');
		
        if( !$end ) $end= date('Y-m-d');
        elseif( !preg_match('/^20\d{2}-\d{2}-\d{2}$/i', $end) ){
            $this->session->put_error_msg('请正确填写日期格式：如“2016-01-01”');
            redirect(Soma_const_url::inst()->get_url('*/*/*'));
        }

        $days= $this->input->post('days');
        if($days> self::MAX_DAYS) $days= self::MAX_DAYS;
        if($days< self::MIN_DAYS) $days= self::MIN_DAYS;
        $chart_data= $this->_get_chart_data($end, $days-1);
        
        $total_data = $this->_get_total_data($end, $days-1);

        $pie_data = $this->_get_pie_chart_data($end, $days-1);
        // var_dump($total_data);exit;

        // var_dump($chart_data);exit;

        $view_params= array(
            'end'=> $end,
            'days'=> $days,
            'min_days'=> self::MIN_DAYS,
            'max_days'=> self::MAX_DAYS,
            //'model'=> $statis_model,
        );

        $data = $view_params + $chart_data + $total_data + $pie_data;

        $html= $this->_render_content($this->_load_view_file('index'), $data, TRUE);
        //echo $html;die;
        echo $html;
    }

    protected function _get_compare_data( $data_array )
    {
        $basic1= $basic2= $basic3= $basic4= 0;
        $lastday1= $lastday2= $lastday3= $lastday4= 0;
        $week_sum1= $week_sum2= $week_sum3= $week_sum4= 0;
        //print_r($data_array['data1']);die;
        for($i=1; $i<=8; $i++){
            $tmp= current( $data_array['data1'] );
            if($i==1) $basic1= $tmp;
            if($i==2) $lastday1= $tmp;
            if($i>1) $week_sum1+= $tmp;
            
            $tmp= current( $data_array['data2'] );
            if($i==1) $basic2= $tmp;
            if($i==2) $lastday2= $tmp;
            if($i>1) $week_sum2+= $tmp;

            $tmp= current( $data_array['data3'] );
            if($i==1) $basic3= $tmp;
            if($i==2) $lastday3= $tmp;
            if($i>1) $week_sum3+= $tmp;
            
            $tmp= current( $data_array['data4'] );
            if($i==1) $basic4= $tmp;
            if($i==2) $lastday4= $tmp;
            if($i>1) $week_sum4+= $tmp;
            
            next( $data_array['data1'] );
            next( $data_array['data2'] );
            next( $data_array['data3'] );
            next( $data_array['data4'] );
        }
        $week_avg1= round($week_sum1/7, 4);
        $week_avg2= round($week_sum2/7, 4);
        $week_avg3= round($week_sum3/7, 4);
        $week_avg4= round($week_sum4/7, 4);
        $this->load->helper('soma/math');
        return array(
            'line1'=> array( round($basic1,2), round($basic2,2), round($basic3,2), round($basic4,2), ),
            'line2'=> array( round($lastday1,2), round($lastday2,2), round($lastday3,2), round($lastday4,2), ),
            'line3'=> array( $week_avg1, $week_avg2, $week_avg3, $week_avg4, ),
            'line4'=> array( 
                $lastday1==0? 0: ($basic1- $lastday1)/$lastday1, 
                $lastday2==0? 0: ($basic2- $lastday2)/$lastday2, 
                $lastday3==0? 0: ($basic3- $lastday3)/$lastday3, 
                $lastday4==0? 0: ($basic4- $lastday4)/$lastday4, 
             ),
            'line5'=> array( 
                $week_avg1==0? 0: ($basic1- $week_avg1 )/$week_avg1,
                $week_avg2==0? 0: ($basic2- $week_avg2 )/$week_avg2,
                $week_avg3==0? 0: ($basic3- $week_avg3 )/$week_avg3,
                $week_avg4==0? 0: ($basic4- $week_avg4 )/$week_avg4,
             ),
        );
    }
    
    /**
     * 异步获取图标原始数据
     */
    protected function _get_chart_data($end, $days=30)
    {
        $start= date('Y-m-d', strtotime($end)- 3600*24* $days );
        
        $this->load->model('soma/Statis_sales_model', 'Statis_sales_model');
        $statis_model= $this->Statis_sales_model->init_service();

        $this->load->model('wx/publics_model');
        $publics_array= $this->publics_model->get_public_hash();
        $publics_hash= $this->publics_model->array_to_hash($publics_array, 'name', 'inter_id');
        
        $inter_id= $this->current_inter_id? $this->current_inter_id: 'ALL';
        $data_title= ( array_key_exists($inter_id, $publics_hash) )? 
            $publics_hash[$this->current_inter_id]: '所有公众号';
        
        $data1= $statis_model->get_sales_data($inter_id, $statis_model::K_SALE_TOTAL, $start, $end);
        $data2= $statis_model->get_sales_data($inter_id, $statis_model::K_SALE_COUNT, $start, $end);
        $data3= $statis_model->get_sales_data($inter_id, $statis_model::K_SALE_QTY, $start, $end);
        $data4= $statis_model->get_sales_data($inter_id, $statis_model::K_CONSUME_QTY, $start, $end);
        //var_dump($inter_id, $statis_model::K_SALE_TOTAL, $start, $end, $data4);die;
        
        $table_data= array();
        foreach ($data1 as $k=>$v){
            $tmp1= isset($data1[$k])? $data1[$k]: 0;
            $tmp2= isset($data2[$k])? $data2[$k]: 0;
            $tmp3= isset($data3[$k])? $data3[$k]: 0;
            $tmp4= isset($data4[$k])? $data4[$k]: 0;
            $table_data[]= array( $k, show_price_prefix(round($tmp1, 2)), $tmp2, $tmp3, $tmp4 );
            //$table_data[]= array('date'=>$k, 'total'=> round($data1[$k], 2), 'count'=> $data2[$k], 'qty'=> $data3[$k], 'c_qty'=> $data4[$k] );
        }
        $table_head= array('日期','订单总额','订单数量','购买份数','核销份数',);
        
        $chart_data1= $chart_data2= $chart_data3= $chart_data4= array();
        foreach ($data1 as $k=>$v){
            $tmp1= isset($data1[$k])? $data1[$k]: 0;
            $tmp2= isset($data2[$k])? $data2[$k]: 0;
            $tmp3= isset($data3[$k])? $data3[$k]: 0;
            $tmp4= isset($data4[$k])? $data4[$k]: 0;
            $chart_data1[]= array('date'=>$k, 'amount'=> round($tmp1, 2) );
            $chart_data2[]= array('date'=>$k, 'amount'=> $tmp2);
            $chart_data3[]= array('date'=>$k, 'amount'=> $tmp3);
            $chart_data4[]= array('date'=>$k, 'amount'=> $tmp4);
        }
        
        $sum_1= number_format( array_sum($data1), 2);
        $sum_2= array_sum($data2);
        $sum_3= array_sum($data3);
        $sum_4= array_sum($data4);
        $scope= " {$start} ~ {$end} ";
        $chart_head= array( 
            1=> "交易额分析（ {$scope}总计 <b>￥{$sum_1}</b> 元）", 
            2=> "订单数分析（ {$scope}总计 <b>{$sum_2}</b>单）",  
            3=> "购买件数（ {$scope}总计 <b>{$sum_3}</b>件）",  
            4=> "核销件数（ {$scope}总计 <b>{$sum_4}</b>件）" 
        );
        
        $compare_data= $this->_get_compare_data( array(
            'data1'=> array_reverse($data1), //倒转数组后以第一个为统计的日期
            'data2'=> array_reverse($data2), 
            'data3'=> array_reverse($data3), 
            'data4'=> array_reverse($data4), 
        ) );
        
        return array(
            'data_title'=> $data_title,
            
            'table_head'=> $table_head,
            'table_data'=> $table_data,

            'compare_data'=> $compare_data,
            
            'chart_head'=> $chart_head,
            'data_1'=> $chart_data1,
            'data_2'=> $chart_data2,
            'data_3'=> $chart_data3,
            'data_4'=> $chart_data4,
        );
    }
    
    /**
     * 获取销售总额数据,客单价
     * 
     * @param  [type] $end  日期：2016-07-21
     * @param  [type] $days 统计天数，如：1，即统计2016-07-20到2016-07-21的数据
     * @return [type]       数组
     */
    protected function _get_total_data($end, $days) {

        // 获取ids数据
        $inter_id = $this->_get_real_inter_id();
        $prefix = $id_name = '';
        $ids_hash = array();
        if($inter_id == FULL_ACCESS) {
            $id_name = '公众号名称';
            $ids_hash = $this->_get_total_inter_ids();
        } else {
            $id_name = '酒店名称';
            $prefix = $inter_id . '_';
            $ids_hash = $this->_get_total_hotel_ids($inter_id);
        }

        // 获取统计数据
        $this->load->model('soma/Statis_sales_model');
        $s_model = $this->Statis_sales_model;
        $start= date('Y-m-d', strtotime($end)- 3600*24* $days );

        $total_data = $total_value = array();
        $avg_data = $avg_value = array();
        foreach ($ids_hash as $id => $name) {
            
            $t_data = $s_model->get_sales_data($prefix . $id, $s_model::K_SALE_TOTAL, $start, $end);
            $c_data = $s_model->get_sales_data($prefix . $id, $s_model::K_SALE_COUNT, $start, $end);
            $q_data = $s_model->get_sales_data($prefix . $id, $s_model::K_SALE_QTY, $start, $end);

            $total_sum = array_sum($t_data);
            $count_sum = array_sum($c_data);
            $qty_sum = array_sum($q_data);
            
            if($count_sum <= 0) {
                $total_avg = 0;
            } else {
                $total_avg = $total_sum/$count_sum;
            }

            $total_data[] = array( $name, $qty_sum, $count_sum, $total_sum);
            $total_value[] = $total_sum;
            
            $avg_data[] = array($name, $count_sum, $total_avg);
            $avg_value[] = $total_avg;
        }

        // 销售额排序
        array_multisort($total_value, SORT_DESC, $total_data);
        array_multisort($avg_value, SORT_DESC, $avg_data);

        // 数值转化为金钱格式，插入序号
        for($i=0; $i<count($total_data); $i++) {
            
            $t_row = $total_data[$i];
            $t_row[3] = '￥' . number_format($t_row[3], 2);

            $a_row = $avg_data[$i];
            $a_row[2] = '￥' . number_format($a_row[2], 2);

            $total_data[$i] = array_merge( array($i+1), $t_row );
            $avg_data[$i] = array_merge( array($i+1), $a_row );
        }

        $total_head = array('TOP排行榜', $id_name, "购买件数", "订单数量", "销售额（{$start} ~ {$end}）");
        $avg_head = array('TOP排行榜', $id_name, "订单数量", "客单价（{$start} ~ {$end}）");

        return array(
            'total_data' => $total_data,
            'total_head' => $total_head,
            'avg_data' => $avg_data,
            'avg_head' => $avg_head,
        );

    }

    /**
     * 获取结算方式饼状图数据
     * 
     * @param  [type] $end  截止时间
     * @param  [type] $days 统计天数，如：1，即统计2016-07-20到2016-07-21的数据
     * @return [type]       数组
     */
    protected function _get_pie_chart_data($end, $days) {

        $inter_id = $this->_get_real_inter_id();
        if($inter_id == FULL_ACCESS) { $inter_id = 'ALL'; }

        $this->load->model('soma/Statis_sales_model');
        $s_model = $this->Statis_sales_model;
        $start= date('Y-m-d', strtotime($end)- 3600*24* $days );

        // 来源于Statis_sales_model的父类MY_Model_Soma
        $settle_label = $s_model->get_settle_label();
        $total_pie = $count_pie = $qty_pie = array();

        foreach ($settle_label as $settle => $label) {
            $stl_key = '_' . strtoupper($settle);

            $total = $s_model->get_sales_data($inter_id, 
                $s_model::K_SALE_TOTAL, $start, $end, TRUE, 0, 10000000, $stl_key);
            $count = $s_model->get_sales_data($inter_id, 
                $s_model::K_SALE_COUNT, $start, $end, TRUE, 0, 10000000, $stl_key);
            $qty = $s_model->get_sales_data($inter_id, 
                $s_model::K_SALE_QTY, $start, $end, TRUE, 0, 10000000, $stl_key);

            $total_pie[] = array('label' => $label, 'value' => array_sum($total));
            $count_pie[] = array('label' => $label, 'value' => array_sum($count));
            $qty_pie[] = array('label' => $label, 'value' => array_sum($qty));

        }

        // var_dump($total_pie, $count_pie);exit;

        return array( 'total_pie' => $total_pie, 'count_pie' => $count_pie, 'qty_pie' => $qty_pie );

    }

    /* 旧版，弃用
    protected function _get_inter_total_data($end, $days) {
        
        $this->load->model('wx/publics_model');
        $this->load->model('soma/Statis_sales_model');
        $s_model = $this->Statis_sales_model;
        $start= date('Y-m-d', strtotime($end)- 3600*24* $days );

        $publics_array= $this->publics_model->get_public_hash();
        $publics_hash= $this->publics_model->array_to_hash($publics_array, 'name', 'inter_id');

        $total_data = array();   // ['inter_id'=> array('data' => array, 'name' => '公众号名')]

        // $cnt = 1;
        $total_value = array();
        foreach ($publics_hash as $inter_id => $name) {
            $inter_data = $s_model->get_sales_data($inter_id, $s_model::K_SALE_TOTAL, $start, $end);
            $total_sum = '￥'. number_format( array_sum($inter_data), 2);
            $total_data[] = array( $name, $total_sum);
            $total_value[] = $total_sum;
        }

        // 销售额排序
        array_multisort($total_value, SORT_DESC, $total_data);

        // 插入序号
        $cnt = 1;
        foreach ($total_data as $key => $value) {
            $total_data[$key] = array_merge( array($cnt++), $value);
        }

        $total_head = array('TOP排行榜', '公众号名称', "销售额（{$start} ~ {$end}）");

        return array('total_data' => $total_data, 'total_head' => $total_head);
    }

    protected function _get_hotel_total_data($end, $days) {
        $this->_get_total_hotel_ids($this->_get_real_inter_id());
        return array('total_data' => array(), 'total_head' => array());
    }
    */

    protected function _get_total_inter_ids() {
        $this->load->model('wx/publics_model');
        $publics_array= $this->publics_model->get_public_hash();
        $publics_hash= $this->publics_model->array_to_hash($publics_array, 'name', 'inter_id');
        return $publics_hash;
    }

    protected function _get_total_hotel_ids($inter_id) {
        $this->load->model('hotel/Hotel_model');
        $hotels_array = $this->Hotel_model->get_all_hotels($inter_id);
        $hotels_hash = $this->Hotel_model->array_to_hash($hotels_array, 'name', 'hotel_id');
        return $hotels_hash;
    }
    
    /**
     * 异步获取图标原始数据
     */
    public function list_chart_ajax()
    {
        $end= $this->input->post('date');
        $chart_data= $this->_get_chart_data($end);
        $result= array('status'=>1, 'message'=>'', 'data'=> $chart_data );
        echo json_encode($result);
    }
    
    public function reflush()
    {
        $this->load->model('soma/Statis_sales_model', 'Statis_sales_model');
        $statis_model= $this->Statis_sales_model->init_service();
        $result= $this->Statis_sales_model->flush_sales_data();
        if($result){
            $this->session->put_success_msg('缓存数据已经清理，请等候3分钟，缓存数据将会重新生成。');
        } else {
            $this->session->put_error_msg('清空缓存失败');
        }
        redirect(Soma_const_url::inst()->get_url('*/*/index'));
    }
    
    
}
