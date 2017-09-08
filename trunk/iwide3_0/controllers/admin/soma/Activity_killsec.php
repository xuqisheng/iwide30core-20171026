<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use App\models\soma\Activity_killsec as ActivityKillsecModel;
use App\models\soma\Activity_killsec_instance as ActivityKillsecInstanceModel;
use App\services\soma\KillsecService;

class Activity_killsec extends MY_Admin_Soma
{

    protected $label_module = NAV_PACKAGE_GROUPON;        //统一在 constants.php 定义
    protected $label_controller = '秒杀活动';        //在文件定义
    protected $label_action = '';                //在方法中定义

    public  function main_model_name()
    {
        return 'soma/activity_killsec_model';
    }

    /**
     * 秒杀2.0列表页
     * @author luguihong  <luguihong@jperation.com>
     */
    public function grid()
    {
        $inter_id = $this->current_inter_id;

        /**
         * @var activity_killsec_model $killsecModel
         */
        //$model_name = $this->main_model_name();
        //$killsecModel = $this->_load_model($model_name);
        $killsecModel   = new ActivityKillsecModel();
        $instanceModel  = new ActivityKillsecInstanceModel();

        //空在这里的意义是代表为全部
        $status = $this->input->get('status');

        $per_page = $this->input->get('per_page',TRUE);
        if( $per_page && $per_page > 0 )
        {
            $page = $per_page;
        } else {
            $page = 1;
        }

        //分页
        $getPerPageNum = $this->input->get('per_page_num') ? $this->input->get('per_page_num') : 20;
        $postPerPageNum = $this->input->post('per_page_num') ? $this->input->post('per_page_num') : 0;
        if( $postPerPageNum && $postPerPageNum > 0 )
        {
            $perPageNum = $postPerPageNum + 0;
        } else {
            $perPageNum = $getPerPageNum + 0;
        }
//echo $perPageNum,$getPerPageNum,$postPerPageNum;die;
        $this->load->library('pagination');
        $config['per_page']             = $perPageNum;
        $config['use_page_numbers']     = TRUE;
        $config['cur_page']             = $page;

        $search = $this->input->post('search') ? $this->input->post('search') : '';
//        var_dump( $search );die;
        $search = trim( $search );
        if( $search )
        {
            $result = $killsecModel->searchKillsecList($inter_id, $search);
            $total  = count($result);
        } else {
            $result = $killsecModel->getKillsecList($inter_id, $status, $config['cur_page'], $config['per_page']);
            $total  = $killsecModel->getKillsecTotal($inter_id, $status);
        }

        //如果没有筛选时间状态的，状态值对应着实例表的状态值
        if( $result && !$status )
        {
            foreach( $result as $k=>$v )
            {
                $nowTime = date('Y-m-d H:i:s');
                if( $v['start_time'] > $nowTime )
                {
                    //未开始
                    $result[$k]['notice_status'] = '';
                } elseif( $v['start_time'] < $nowTime && $nowTime < $v['killsec_time'] ) {
                    //准备开始
                    $result[$k]['notice_status'] = $instanceModel::STATUS_PREVIEW;
                } elseif( $v['killsec_time'] < $nowTime && $nowTime < $v['end_time'] ) {
                    //进行中
                    $result[$k]['notice_status'] = $instanceModel::STATUS_GOING;
                } elseif( $v['end_time'] < $nowTime ) {
                    //已结束
                    $result[$k]['notice_status'] = $instanceModel::STATUS_FINISH;
                }
            }
        }
//var_dump( $result );die;
        $config['page_query_string']    = TRUE;
        $config['base_url']             = Soma_const_url::inst()->get_url('*/*/*');
        $config['total_rows']           = $total;
        $config['cur_tag_open']         = '<ib pagebtn_gray nowpage>';
        $config['cur_tag_close']        = '</ib>';
        $config['num_tag_open']         = '<ib pagebtn_gray>';
        $config['num_tag_close']        = '</ib>';
        $config['prev_tag_open']        = '<ib pagebtn_gray>';
        $config['prev_tag_close']       = '</ib>';
        $config['next_tag_open']        = '<ib pagebtn_gray>';
        $config['next_tag_close']       = '</ib>';
        $config['next_link']            = '>'; // 下一页显示
        $config['prev_link']            = '<'; // 上一页显示
        $this->pagination->initialize($config);

        $pageTotal = ceil( $total / $config['per_page'] );//计算出有多少页
        $tokenArr = array(
            'name' => $this->security->get_csrf_token_name(),
            'value' => $this->security->get_csrf_hash()
        );
        $view_params = array(
            'check_data'    => FALSE,
            'data'          => $result,
            'tokenArr'      => $tokenArr,
            'page_count'    => count($result),
            'total'         => $total,
            'search'        => $search,
            'page'          => $page,
            'pageTotal'     => $pageTotal,
            'status'        => $status,
            'model'         => $killsecModel,
            'instanceModel' => $instanceModel,
            'perPageNum'    => $perPageNum,
            'pageUrl'       => Soma_const_url::inst()->get_url('*/*/*',array('status'=>$status,'per_page_num'=>$perPageNum,'per_page'=>'')),
            'statusUrl'     => Soma_const_url::inst()->get_url('*/*/*',array('status'=>'')),
            'editUrl'       => Soma_const_url::inst()->get_url('*/*/edit',array('ids'=>'')),
            'pagination'    => $this->pagination->create_links(),
        );
//var_dump( $result );
        $html= $this->_render_content($this->_load_view_file('grid_new'), $view_params, TRUE);
        echo $html;
    }

    /**
     * 检查redis实例化情况
     * @author zhangyi  <zhangyi@mofly.cn>
     */
    public function check_instance_status(){
        $return = array(
            'status' => Soma_base::STATUS_FALSE,
            'msg'   => '获取实例化失败'
        );

        $actId = $this->input->post('act_id');
        $model_name = $this->main_model_name();
        $model = $this->_load_model($model_name);
        $model = $model->load($actId);
        $killsec = $model->getByID($actId);

        if (empty($killsec)) {
            $return['msg'] = '获取秒杀活动信息失败';
            echo json_encode($return);
            die;
        }

        $instanceModel  = new ActivityKillsecInstanceModel();
        $instance = $instanceModel->getUsingRow($killsec['act_id'], $killsec['killsec_time'], $killsec['end_time']);

        if(empty($instance)){
            $return['msg'] = '获取秒杀活动实例失败，实例尚未生成';
            echo json_encode($return);
            die;
        }

        $key =    "soma_killsec2_".$instance['instance_id']; //放秒杀活动的库存

        $this->config->load('redis', true, true);
        $redis_config = $this->config->item('redis');
        $redis = new Redis();
        if ( ! $redis->connect($redis_config['host'], $redis_config['port'], $redis_config['timeout'])) {
            throw new RuntimeException('redis connect fail');
        }

//        $this->load->library('Redis_selector');
//        $redis = $this->redis_selector->get_soma_redis('soma_redis');
        $result = $redis->get($key);
        if(  $result !== false ){
            $return['status'] = Soma_base::STATUS_TRUE;
            $return['msg'] = "实例ID：".$instance['instance_id']." | Redis：".$result;
        }else{
            $return['msg'] = '获取秒杀活动实例失败<br/>实例ID：'.$instance['instance_id']."<br/>Redis没有成功赋值";
            echo json_encode($return);
            die;
        }

        echo json_encode($return);
    }
    /**
     * 使秒杀失效
     * @author luguihong  <luguihong@jperation.com>
     */
    public function disable_status()
    {
        $actId = $this->input->post('act_id');

        $result = KillsecService::getInstance()->disable($actId);

        $model_name = $this->main_model_name();
        $model = $this->_load_model($model_name);
        $model = $model->load($actId);
        $this->_log($model);

        $this->json($result->toArray());
    }

    /**
     * 改变库存，这里暂时只能增加不能减少
     * @author luguihong  <luguihong@jperation.com>
     */
    public function modify_stock()
    {
        $return = array( 'status'=>Soma_base::STATUS_FALSE, 'msg'=>'操作失败', 'data'=>'' );

        $inter_id = $this->session->get_admin_inter_id();
        $actId = $this->input->post('act_id');
        $addStockNum = $this->input->post('add_stock_num');

        if(!is_numeric($actId) || $actId < 0 || !is_numeric($addStockNum) || $addStockNum <= 0 ){
            echo json_encode($return);
            exit;
        }


        $stocksInfoObj = KillsecService::getInstance()->getStock($actId);
        $stocksInfo = $stocksInfoObj->toArray();

        if($stocksInfo && $stocksInfo['status'] && isset($stocksInfo['data']['status']) && $stocksInfo['data']['status']){
            $model_name = $this->main_model_name();
            $model = $this->_load_model($model_name);
            $model = $model->load($actId);
            $killsec = $model->getByID($actId);

            //iwide_soma_activity_killsec
            $updateData['act_id'] = $actId;
            $updateData['killsec_count'] = $addStockNum + $killsec['killsec_count'];

            $result = $model->_activity_edit($updateData, $inter_id);
            $this->_log($model);
            //iwide_soma_activity_killsec_instance
            $instanceModel  = new ActivityKillsecInstanceModel();
            $instance = $instanceModel->getUsingRow($killsec['act_id'], $killsec['killsec_time'], $killsec['end_time']);

            $decreaseResult = $instanceModel->increase($instance['instance_id'], 'killsec_count', $addStockNum);


            //设置redis上的库存
            $result = KillsecService::getInstance()->addRedisStock($actId,$addStockNum);
            if($result){
                $return['status']  = Soma_base::STATUS_TRUE;
                $return['msg']  = '操作成功！';
            }else{
                $return['msg']  = '操作失败！[参考信息：Redis设置失败]';
            }

        }else{
            $return['msg'] = '获取秒杀信息失败[参考信息：Redis获取失败]';
        }

        echo json_encode( $return );
    }

    public function grid2()
    {
        $this->label_action = '活动列表';
        $inter_id = $this->session->get_admin_inter_id();
        if ($inter_id == FULL_ACCESS) {
            $filter = array();
        } else {
            if ($inter_id) {
                $filter = array('inter_id' => $inter_id);
            } else {
                $filter = array('inter_id' => 'deny');
            }
        }
        //print_r($filter);die;

        $ent_ids = $this->session->get_admin_hotels();
        $hotel_ids = $ent_ids ? explode(',', $ent_ids) : array();
        if (count($hotel_ids) > 0) {
            $filter += array('hotel_id' => $hotel_ids);
        }

        $model_name = $this->main_model_name();
        $model = $this->_load_model($model_name);

        /* 兼容grid变为ajax加载加这一段 */
        if (is_ajax_request()) //处理ajax请求，参数规格不一样
        {
            $get_filter = $this->input->post();
        } else {
            $get_filter = $this->input->get('filter');
        }

        if (!$get_filter) {
            $get_filter = $this->input->get('filter');
        }

        if (is_array($get_filter)) {
            $filter = $get_filter + $filter;
        }
        /* 兼容grid变为ajax加载加这一段 */

        $sts = Soma_base::get_status_options();
        $ops = '';
        foreach ($sts as $k => $v) {
            if (isset($filter['status']) && $filter['status'] == $k) {
                $ops .= '<option value="' . $k . '" selected="selected">' . $v . '</option>';
            } else {
                $ops .= '<option value="' . $k . '">' . $v . '</option>';
            }
        }

        if (!isset($filter['status']) || $filter['status'] === NULL) {
            $active = '';
        } else {
            $active = 'btn-success';
        }

        $jsfilter_btn = '&nbsp;&nbsp;<div class="input-group">'
            . '<div class="input-group-btn"><button type="button" class="btn btn-sm ' . $active . '"><i class="fa fa-filter"></i> 状态</button></div>'
            . '<select class="form-control input-sm" name="filter[status]" id="filter_status" >'
            . '<option value="-">全部</option>' . $ops
            . '</select>'
            . '<span class="input-group-btn"><button id="refresh_sync" type="button" class="btn btn-sm btn-success"><i class="fa fa fa-refresh"></i> 同步到中心平台</button></span>'
            . '</div>';

        $current_url = current_url();
        $sync_url = Soma_const_url::inst()->get_url('*/*/sync');
        $jsfilter = <<<EOF
$('#filter_status').change(function(){
	var go_url= '?'+ $(this).attr('name')+ '='+  $(this).val();
	//alert(go_url);
	if($(this).val()=='-') window.location= '{$current_url}';
	else window.location= '{$current_url}'+ go_url;
});
$("#refresh_sync").click(function(){
	var ids = '';
	$(".bg-gray").each(function(){
		ids += $(this).attr('id') + ',';
	});
	if( ids != '' ){
		window.location= '{$sync_url}?ids='+ ids;
	}else{
		alert('请选择活动！');
	}
});
EOF;
        $viewdata = array(
            'js_filter_btn' => $jsfilter_btn,
            'js_filter' => $jsfilter,
        );
        $this->_grid($filter, $viewdata);
    }

    public function edit()
    {
        $this->label_action = '活动修改';
        $this->_init_breadcrumb($this->label_action);

        $model_name = $this->main_model_name();
        $model = $this->_load_model($model_name);

        $id = intval($this->input->get('ids'));
// var_dump( $id );exit;
        $model = $model->load($id);
        if (!$model) {
            $model = $this->_load_model();
        }
        $fields_config = $model->get_field_config('form');

        //越权查看数据跳转
        if (!$this->_can_edit($model)) {
            $this->session->put_error_msg('找不到该数据');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
        }

        $temp_id = $this->session->get_temp_inter_id();
        if ($temp_id) {
            $inter_id = $temp_id;
        } else {
            $inter_id = $this->session->get_admin_inter_id();
        }

        $disabled = FALSE;
        if ($inter_id == FULL_ACCESS) {
            $disabled = TRUE;
        }

        $this->load->model('soma/Activity_model');

// 		$options = '';
// 		$act_type = $this->get_act_type_label();
// 		foreach( $act_type as $k=>$v ){
// 			$options .= '<option value="'.$k.'">'.$v.'</option>';
// 		}

        $this->load->model('wx/Publics_model','PublicsModel');
        $publics = $this->PublicsModel->get_public_by_id($inter_id);

        //活动开始前一个小时不能修改
        if(isset($id) && !empty($id))
            $isTrue = $model->load($id)->can_modify();
        else
            $isTrue = true;

        $this->load->model( 'soma/product_package_model', 'product_package' );
        $products_arr = $this->product_package->get_product_package_list( '', $inter_id , NULL, 1000);

        foreach($products_arr as $product){
            $productsInfo[$product['product_id']]['stock'] = $product['stock'];
            $productsInfo[$product['product_id']]['price_package'] = $product['price_package'];
        }

        $view_params = array(
            'disabled' => $disabled,
            'model' => $model,
            'fields_config' => $fields_config,
            'check_data' => FALSE,
            'ActivityModel' => $this->Activity_model,
            'public'    =>  $publics,
            'can_modify'    =>  $isTrue,
            'product_info' => $productsInfo
//		    'options'=>$options,
        );
        if(!empty($id)) {
            $killSecInfo = $model->getByID($id);
            $view_params['killSecInfo'] =   $killSecInfo;
        }

        $html = $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
        //echo $html;die;
        echo $html;
    }


    public function edit_post()
    {
        $this->label_action = '信息维护';
        $this->_init_breadcrumb($this->label_action);

        $model_name = $this->main_model_name();
        $model = $this->_load_model($model_name);
        $pk = $model->table_primary_key();

        //cat_id从ticket_center->get_id_category('package')获取
        $this->load->model('soma/ticket_center_model', 'ticket_center');
        $activityId = $this->ticket_center->get_id_product('package');

        $this->load->library('form_validation');
        $post = $this->input->post();

        $temp_id = $this->session->get_temp_inter_id();
        if ($temp_id) {
            $inter_id = $temp_id;
        } else {
            $inter_id = $this->session->get_admin_inter_id();
        }

        $post['inter_id'] = isset($post['inter_id']) ? $post['inter_id'] : $inter_id;
        $labels = $model->attribute_labels();
        $base_rules = array(
            'act_name' => array(
                'field' => 'act_name',
                'label' => $labels['act_name'],
                'rules' => 'trim|required',
            ),
            'product_id' => array(
                'field' => 'product_id',
                'label' => $labels['product_id'],
                'rules' => 'trim|required',
            ),
            'hotel_id' => array(
                'field' => 'hotel_id',
                'label' => $labels['hotel_id'],
                'rules' => 'trim|required',
            ),
            'killsec_count' => array(
                'field' => 'killsec_count',
                'label' => $labels['killsec_count'],
                'rules' => 'trim|required',
            ),
            'killsec_price' => array(
                'field' => 'killsec_price',
                'label' => $labels['killsec_price'],
                'rules' => 'trim|required',
            ),
        );
        //检测并上传文件。
        $post = $this->_do_upload($post, 'banner_url');
//var_dump( $post );die();

        if (isset($post['schedule_type']) && $post['schedule_type'] == 2) {

            if ($post['end_time'] <= 0 || $post['end_time'] > 23) {
                $this->session->put_error_msg('按星期进行的秒杀时长必须在1~23之间!');
                $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit'));
            }

            //按星期
            $date_array = $model->update_last_week_date($post['schedule'], $post['killsec_time'], $post['end_time']);

            $post['schedule'] = implode(',', $post['schedule']);
            $post['killsec_time'] = $date_array['killsec_time'];
            $post['end_time'] = $date_array['end_time'];

            if (empty($post['schedule'])) {
                $this->session->put_error_msg('必须勾选星期选项！');
                $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
            }

        } else {

            if ($post['end_time'] <= 0 || $post['end_time'] > 72) {
                $this->session->put_error_msg('按日期进行的秒杀时长必须在1~72之间!');
                $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit'));
            }

            $post['schedule'] = '';
            $post['killsec_time'] = substr(date('Y-m-d') . ' ' . $post['killsec_time'], -19);
            $post['end_time'] = date('Y-m-d H:i:s', strtotime($post['killsec_time']) + $post['end_time'] * 3600);
        }

        if (!$post['start_time'] || !$post['killsec_time']) {
            $this->session->put_error_msg('此次数据修改失败，请设置好活动的开始时间和秒杀时间！');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));

        } else {
            if (date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime($post['killsec_time']) - 1800)) {
                $this->session->put_error_msg('秒杀时间至少预留半小时，请重新调整！');
                $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));

            } else {
                if ($post['start_time'] > date('Y-m-d H:i:s', strtotime($post['killsec_time']) - 1800)) {
                    $this->session->put_error_msg('活动开始时间必须早于秒杀时间半小时！');
                    $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
                }
            }
        }

        //秒杀价
        $killsec_price = isset($post['killsec_price']) ? $post['killsec_price'] + 0 : 0;
        if ($killsec_price < 0) {
            $post['killsec_price'] = 0;
        } else {
            $post['killsec_price'] = $killsec_price;
        }

        //秒杀数量
        $killsec_count = isset($post['killsec_count']) ? $post['killsec_count'] + 0 : 0;
        if ($killsec_count < 0) {
            $post['killsec_count'] = 0;
        } else {
            $post['killsec_count'] = $killsec_count;
        }

        //商品名称
        $this->load->model('soma/product_package_model', 'product_package');
        $product_id = isset($post['product_id']) ? $post['product_id'] : NULL;
        if ($product_id) {
            $product_info = $this->product_package->get_product_package_detail_by_product_id($product_id, $inter_id);
            if ($product_info) {
                $post['product_name'] = $product_info['name'];
            } else {
                $post['product_name'] = '';
            }
        } else {
            $post['product_name'] = NULL;
        }

//var_dump( $post );exit;
        if (empty($post[$pk])) {
            //add data.
            // $post[$pk] = $activityId;
            $post['create_time'] = date('Y-m-d H:i:s', time());

            $this->form_validation->set_rules($base_rules);

            if ($this->form_validation->run() != FALSE) {

                $result = $model->_activity_save($post, $inter_id);

                $message = ($result) ?
                    $this->session->put_success_msg('已新增数据！') :
                    $this->session->put_notice_msg('此次数据保存失败！');
                $this->_log($model);
                $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));

            } else {
                $model = $this->_load_model();
            }

        } else {

            //活动开始前一个小时不能修改
            $pk = $model->table_primary_key();
            $post['act_type'] = $model::ACT_TYPE_KILLSEC;
            $isTrue = $model->load($post[$pk])->can_modify();
            if (!$isTrue) {
                $this->session->put_error_msg('活动准备与进行期间不能修改');
                $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
            }

            //修改数据的时候，如果改变了状态，需要发送一个信号到中心平台，修改中心平台的状态
            if (isset($post['status']) && !empty($post['status']) && $post['status'] == Soma_base::STATUS_FALSE) {
                $this->load->model('soma/Center_activity_model', 'CenterModel');
                $this->CenterModel->update_status_byActIds($post['act_id']);
            }

            $this->form_validation->set_rules($base_rules);

            if ($this->form_validation->run() != FALSE) {
                //
                $result = $model->_activity_edit($post, $inter_id);

                $message = ($result) ?
                    $this->session->put_success_msg('已保存数据！') :
                    $this->session->put_notice_msg('此次数据修改失败！');
                $this->_log($model);
                $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));

            } else {
                $model = $model->load($post[$pk]);
            }
        }

        //验证失败的情况
        $validat_obj = _get_validation_object();
        $message = $validat_obj->error_html();
        //页面没有发生跳转时用寄存器存储消息
        $this->session->put_error_msg($message, 'register');

        $this->load->model('soma/Activity_model');

        $fields_config = $model->get_field_config('form');
        $view_params = array(
            'model' => $model,
            'fields_config' => $fields_config,
            'check_data' => TRUE,
            'ActivityModel' => $this->Activity_model,
        );
        $html = $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
        echo $html;
    }


    //欠验证时间交集
    public function edit_post2()
    {

        $this->label_action = '信息维护';
        $this->_init_breadcrumb($this->label_action);

        $model_name = $this->main_model_name();
        $model = $this->_load_model($model_name);
        $pk = $model->table_primary_key();

        //cat_id从ticket_center->get_id_category('package')获取
        $this->load->model('soma/ticket_center_model', 'ticket_center');
        $activityId = $this->ticket_center->get_id_product('package');

        $this->load->library('form_validation');
        $post = $this->input->post();

        $temp_id = $this->session->get_temp_inter_id();
        if ($temp_id) {
            $inter_id = $temp_id;
        } else {
            $inter_id = $this->session->get_admin_inter_id();
        }

        $post['inter_id'] = isset($post['inter_id']) ? $post['inter_id'] : $inter_id;
        $post['is_stock'] = isset($post['is_stock']) &&  $post['is_stock'] == 1 ? $post['is_stock'] : 0;

        $labels = $model->attribute_labels();
        $base_rules = array(
            'act_name' => array(
                'field' => 'act_name',
                'label' => $labels['act_name'],
                'rules' => 'trim|required',
            ),
            'product_id' => array(
                'field' => 'product_id',
                'label' => $labels['product_id'],
                'rules' => 'trim|required',
            ),
            'killsec_count' => array(
                'field' => 'killsec_count',
                'label' => $labels['killsec_count'],
                'rules' => 'trim|required',
            ),
            'killsec_price' => array(
                'field' => 'killsec_price',
                'label' => $labels['killsec_price'],
                'rules' => 'trim|required',
            ),
        );

        if( strlen($post['act_name']) > 90){
            $this->session->put_error_msg('活动名称字数不能超过30个汉字！');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit'));
        }

        if(strlen($post['keyword']) > 150){
            $this->session->put_error_msg('关键字描述字数不能超过50个汉字！');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit'));
        }

        if (isset($post['schedule_type']) && $post['schedule_type'] == 2) {

            if ($post['end_time'] <= 0 || $post['end_time'] > 23) {
                $this->session->put_error_msg('按星期进行的秒杀时长必须在1~23之间!');
                $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit'));
            }

            //按星期
//            $date_array = $model->update_last_week_date($post['schedule'], $post['killsec_time'], $post['end_time']);

            $schedule =   $post['schedule'];
            $post['schedule'] = implode(',', $post['schedule']);
//            $post['killsec_time'] = $date_array['killsec_time'];
//            $post['end_time'] = $date_array['end_time'];

            if (empty($post['schedule'])) {
                $this->session->put_error_msg('必须勾选星期选项！');
                $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit'));
            }


            /*计算时间最接近的日期*/
            $nowDateTime = date("Y-m-d H:i:s",time());
            if (isset($post['schedule_type']) && $post['schedule_type'] == 2) {             //周期是以周期开始日期计算
                if($post['cycle_stime']." ".$post['killsec_time'] < $nowDateTime){
                    $startTime = $nowDateTime;
                }else{
                    $startTime = $post['cycle_stime']." ".$post['killsec_time'];
                }
            }else{
                if($post['start_time'] < $nowDateTime){
                    $startTime = $nowDateTime;
                }else{
                    $startTime = $post['cycle_stime'];
                }
            }

            $w = date("w", strtotime($startTime));
            if($w == 0){
                $w = 7;
            }
            if(is_array($schedule) && in_array($w,$schedule) && ( date("Y-m-d",strtotime($startTime))." ".$post['killsec_time'] ) > date("Y-m-d H:i:s",time() + 600)){
                $startTime = date("Y-m-d",strtotime($startTime))." ".$post['killsec_time'];
            }else if(is_array($schedule)){
                $i = 0;
                while($w){
                    $w ++;
                    $temp  = $w % 7;
                    $i ++;
                    if(in_array($temp,$schedule) || ($temp == 0 && in_array($w,$schedule)))
                        break;

                }
                $startTime = date("Y-m-d",strtotime($startTime) + $i* 86400)." ".$post['killsec_time'];
            }
            $post['killsec_time'] = $startTime;
            $post['end_time']  =   date("Y-m-d H:i:s" , strtotime($startTime) + $post['end_time'] * 3600);
            /*end 时间最接近的日期*/

        } else {

            if ($post['end_time'] <= 0 || $post['end_time'] > 72) {
                $this->session->put_error_msg('按日期进行的秒杀时长必须在1~72之间!');
                $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit'));
            }

//            $dateTime = date("Y-m-d",strtotime($post['start_time']));
//            $startTime = date("H:i",strtotime($post['killsec_time']));
//            $post['killsec_time'] = $dateTime." ".$startTime;

            $post['schedule'] = '';
            $post['end_time'] = date('Y-m-d H:i:s', strtotime($post['killsec_time']) + $post['end_time'] * 3600);
        }

        if (!$post['start_time'] || !$post['killsec_time']) {
            $this->session->put_error_msg('此次数据修改失败，请设置好活动的开始时间和秒杀时间！');
            $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit'));

        } else {
            if (date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime($post['killsec_time']) - 600)) {
                $this->session->put_error_msg('秒杀时间至少预留10分钟，请重新调整！');
                $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit'));

            } else {
                if ($post['start_time'] > date('Y-m-d H:i:s', strtotime($post['killsec_time']) - 600)) {
                    $this->session->put_error_msg('活动开始时间必须早于秒杀时间10分钟！');
                    $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit'));
                }
            }
        }

        //秒杀价
        $killsec_price = isset($post['killsec_price']) ? $post['killsec_price'] + 0 : 0;
        if ($killsec_price < 0) {
            $post['killsec_price'] = 0;
        } else {
            $post['killsec_price'] = $killsec_price;
        }

        //秒杀数量
        $killsec_count = isset($post['killsec_count']) ? $post['killsec_count'] + 0 : 0;
        if ($killsec_count < 0) {
            $post['killsec_count'] = 0;
        } else {
            $post['killsec_count'] = $killsec_count;
        }

        //商品名称
        $this->load->model('soma/product_package_model', 'product_package');
        $product_id = isset($post['product_id']) ? $post['product_id'] : NULL;
        if ($product_id) {
            $product_info = $this->product_package->get_product_package_detail_by_product_id($product_id, $inter_id);
            if ($product_info) {
                $post['product_name'] = $product_info['name'];
            } else {
                $post['product_name'] = '';
            }
        } else {
            $post['product_name'] = NULL;
        }


        if(isset($post[$pk]) && !empty($post[$pk]))
            $act_id = $post[$pk];
        else
            $act_id = '';

        $post['cycle_stime'] = date("Y-m-d H:i:s",strtotime($post['cycle_stime']));
        $post['cycle_etime'] = date("Y-m-d H:i:s",strtotime($post['cycle_etime']));
        /*时间交集验证*/
        $checkIntersectResult = KillsecService::getInstance()->checkIntersect($product_id,$post['schedule_type'],$post['killsec_time'],$post['end_time'],$act_id,$post['cycle_stime'],$post['cycle_etime'],$post['schedule']);
        if(isset($checkIntersectResult['status']) && $checkIntersectResult['status'] == true){
            $this->session->put_error_msg('此次数据保存失败,该商品的秒杀时间有重合！已存在的秒杀编号：'.$checkIntersectResult['killsec']['act_id']);
            if(!empty($act_id)){
                $this->_redirect(Soma_const_url::inst()->get_url('*/*/edit'));
            }else{
                $this->_redirect(Soma_const_url::inst()->get_url('*/*/add'));
            }

        }
        /*end 时间交集验证*/

        if (empty($post[$pk])) {
            //add data.
            // $post[$pk] = $activityId;
            $post['create_time'] = date('Y-m-d H:i:s', time());

            $post['status'] = Activity_killsec_model::STATUS_TRUE;
            $post['type'] =  2;//目前先写死了秒杀的type


            $this->form_validation->set_rules($base_rules);

            if ($this->form_validation->run() != FALSE) {

                $result = $model->_activity_save($post, $inter_id);

                $message = ($result) ?
                    $this->session->put_success_msg('已新增数据！') :
                    $this->session->put_notice_msg('此次数据保存失败！');
                $this->_log($model);
                $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));

            } else {
                $model = $this->_load_model();
            }

        } else {

            //活动开始前一个小时不能修改
            $pk = $model->table_primary_key();
            $post['act_type'] = $model::ACT_TYPE_KILLSEC;
            $isTrue = $model->load($post[$pk])->can_modify();
            if (!$isTrue) {
                $this->session->put_error_msg('活动准备与进行期间不能修改');
                $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));
            }

            //修改数据的时候，如果改变了状态，需要发送一个信号到中心平台，修改中心平台的状态
            if (isset($post['status']) && !empty($post['status']) && $post['status'] == Soma_base::STATUS_FALSE) {
                $this->load->model('soma/Center_activity_model', 'CenterModel');
                $this->CenterModel->update_status_byActIds($post['act_id']);
            }

            $this->form_validation->set_rules($base_rules);

            if ($this->form_validation->run() != FALSE) {
                //
                $result = $model->_activity_edit($post, $inter_id);

                $message = ($result) ?
                    $this->session->put_success_msg('已保存数据！') :
                    $this->session->put_notice_msg('此次数据修改失败！');
                $this->_log($model);
                $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));

            } else {
                $model = $model->load($post[$pk]);
            }
        }

        //验证失败的情况
        $validat_obj = _get_validation_object();
        $message = $validat_obj->error_html();
        //页面没有发生跳转时用寄存器存储消息
        $this->session->put_error_msg($message, 'register');

        $this->load->model('soma/Activity_model');

        $fields_config = $model->get_field_config('form');
        $view_params = array(
            'model' => $model,
            'fields_config' => $fields_config,
            'check_data' => TRUE,
            'ActivityModel' => $this->Activity_model,
        );
        $html = $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
        echo $html;
    }
    public function delete()
    {

    }

    public function kick_user()
    {
        //防止其他账号使用该功能。
        $this->_toolkit_writelist();

        $user_id = $this->input->get('user_id');
        $inter_id = $this->input->get('inter_id');
        $instance_id = $this->input->get('instance_id');

        $model_name = $this->main_model_name();
        $model = $this->_load_model($model_name);
        $model->kick_user_by_filter($inter_id, $user_id);

        redirect(Soma_const_url::inst()->get_url('*/*/instance_user', array(
            'instance_id' => $instance_id, 'inter_id' => $inter_id
        )));
    }

    public function instance_user()
    {
        //防止其他账号使用该功能。
        $this->_toolkit_writelist();

        $model_name = $this->main_model_name();
        $model = $this->_load_model($model_name);
        $inter_id = $this->input->get('inter_id');
        $instance_id = $this->input->get('instance_id');
        header("Content-type: text/html; charset=utf-8");

        $users = $model->get_user_by_filter($inter_id, array('instance_id' => $instance_id));
        $redis = $model->get_join_status_by_instance($instance_id);

        echo '--[参与用户]' . str_repeat('-', 50) . '<p>';
        $success_count = $order_count = $join_count = 0;
        if (count($users) == 0) {
            echo '此活动暂无参与用户';

        } else {
            $i = 0;
            echo '<script>var sst=true; window.setTimeout(function(){ if(sst==true) document.location.reload();}, 5000);' .
                'function stop_refresh(){ if(sst==true) sst=false; else sst=true; }</script>';
            echo '<table border=1 width=1300 cellspacing=0 cellpadding=5 align=center>';
            //echo '<tr><th>序号</th><th>用户ID</th><th>业务类型</th><th>实例编号</th><th>Token</th><th>活动编号</th><th>Inter_id</th><th>
            //    openid</th><th>参与时间</th><th>下单时间</th><th>支付时间</th><th>订单ID</th><th>IP地址</th><th>状态</th><th>进入时长</th></tr>';
            echo '<tr><th>序号</th><th>用户ID</th><th>实例编号</th><th>Token</th><th>openid</th><th>参与时间</th>
	            <th>下单时间</th><th>支付时间</th><th>订单ID</th><th>IP地址</th><th>状态</th><th>进入时长</th><th>操作</th></tr>';
            foreach ($users as $k => $v) {
                $i++;
                $enter_time = time() - strtotime($v['join_time']);
                if ($enter_time > 3600) {
                    $secs = '1小时前';

                } else {
                    $mins = intval($enter_time / 60);
                    $secs = intval($enter_time % 60);
                    $secs = ($mins > 0 ? $mins . ' 分钟 ' : '') . $secs . ' 秒前';
                }
                unset($v['inter_id']);
                unset($v['act_id']);
                unset($v['business']);
                if ($v['status'] == Activity_killsec_model::USER_STATUS_PAYMENT) {
                    $success_count++;
                    echo "<tr style='color:gray;'><td>{$i}</td><td>" . implode('</td><td>', $v) . "</td><td>{$secs}</td>";
                    echo '<td> - </td></tr>';

                } elseif ($v['status'] == Activity_killsec_model::USER_STATUS_ORDER) {
                    $order_count++;
                    echo "<tr style='color:red;'><td>{$i}</td><td>" . implode('</td><td>', $v) . "</td><td>{$secs}</td>";
                    echo '<td> - </td></tr>';

                } elseif ($v['status'] == Activity_killsec_model::USER_STATUS_JOIN) {
                    $join_count++;
                    echo "<tr style='color:blue;'><td>{$i}</td><td>" . implode('</td><td>', $v) . "</td><td>{$secs}</td>";

                    $kick_url = Soma_const_url::inst()->get_url('*/*/kick_user', array(
                        'instance_id' => $instance_id, 'inter_id' => $inter_id, 'user_id' => $v['user_id']
                    ));
                    echo '<td><a href="' . $kick_url . '">[剔除]</a></td></tr>';
                }
            }
            echo '</table>';
            echo '<p>状态标记：' . json_encode($model->get_user_status(), JSON_UNESCAPED_UNICODE) . '</p>';
            echo "<p>秒杀商品：{$redis['product']['name']}, ￥{$redis['product']['price_package']} &nbsp; <button onclick='javascript:stop_refresh();'>停止刷新</button> </p>";
            echo ($success_count > 0) ? '<p>目前成交份数：<b style="color:gray;">' . $success_count . '</b> 份 ；下单未支付：<b style="color:red;">' .
                $order_count . '</b> 人 ；进入未下单：<b style="color:blue;">' . $join_count . '</b> 人</p><br/>' : '';
        }
        echo '<p>--[redis信息]' . str_repeat('-', 50) . '<ol>';
        echo '<li>', '目前队列配额：<b>', json_encode($redis['token']), '</b> 个</li>';
        echo '<li>', '进入用户（5分钟会释放）：<p>', str_replace(',', ',<br/>', json_encode($redis['cache'])), '</p></li>';
        echo '<li>', '点击用户（每个点击算一次）：<p>', str_replace(',', ',<br/>', json_encode($redis['click'])), '</p></li>';
        echo '</ol><br/>';
    }

    public function instance()
    {
        //防止其他账号使用该功能。
        $this->_toolkit_writelist();

        $this->label_action = '实例监控';
        $this->_init_breadcrumb($this->label_action);

        $model_name = $this->main_model_name();
        $model = $this->_load_model($model_name);

        $inter_id = $this->session->get_admin_inter_id();
        if ($inter_id == FULL_ACCESS) {
            $filter = array();
        } else {
            $filter = array('inter_id', $inter_id);
        }

        $this->load->model('wx/Publics_model');
        $publics = $this->Publics_model->get_public_hash();
        $publics = $this->Publics_model->array_to_hash($publics, 'name', 'inter_id');
        $status = $model->get_instance_status();

        //取所有状态，不传则取出拼团检测的有效状态
        $filter['status'] = array_keys($status);
        $instance = $model->get_aviliable_instance($filter);

        $header = array(
            'instance_id' => '实例编号',
            'act_id' => '活动编号',
            'inter_id' => '公众号',
            'product_id' => '商品',
            'join_count' => '参与人数',
            'killsec_price' => '秒杀价格',
            'killsec_count' => '秒杀配额',
            'start_time' => '活动开始',
            'finish_time' => '秒杀完毕',
            'create_time' => '创建实例',
            'close_time' => '关闭实例',
            'status' => '状态',
        );

        $data = array();
        $view_url = Soma_const_url::inst()->get_url('*/*/instance_user') . '?instance_id=';
        $act_url = Soma_const_url::inst()->get_url('*/activity_killsec/edit') . '?ids=';
        $product_url = Soma_const_url::inst()->get_url('*/product_package/edit') . '?ids=';
        foreach ($instance as $k => $v) {
            $instance[$k]['instance_id'] = "<a href='{$view_url}{$v['instance_id']}&inter_id={$v['inter_id']}' target='_blank'>" .
                $instance[$k]['instance_id'] . "</a>";
            $instance[$k]['act_id'] = "<a href='{$act_url}{$v['act_id']}' target='_blank'>" . $instance[$k]['act_id'] . "</a>";
            $instance[$k]['product_id'] = "<a href='{$product_url}{$v['product_id']}' target='_blank'>" . $instance[$k]['product_id'] . "</a>";

            if (array_key_exists($v['status'], $status)) {
                $instance[$k]['status'] = $status[$v['status']];
            }
            if (array_key_exists($v['inter_id'], $publics)) {
                $instance[$k]['inter_id'] = $publics[$v['inter_id']];
            }
            $temp = array();
            foreach ($header as $sk => $sv) {
                $temp[] = $instance[$k][$sk];
            }
            $data[] = $temp;
        }
        //print_r($instance);die;

        $view_params = array(
            'model' => $model,
            'header' => $header,
            'data' => $data,
        );
        $html = $this->_render_content($this->_load_view_file('instance'), $view_params, TRUE);
        //echo $html;die;
        echo $html;
    }

    /*
    * 同步到中心平台
    * @param 	act_id 		string(如果是多个则使用逗号链接，例如：1,2,3)
    */
    public function sync()
    {

        //检查该管理员，是否有权限操作

        $temp_id = $this->session->get_temp_inter_id();
        if ($temp_id) {
            $inter_id = $temp_id;
        } else {
            $inter_id = $this->session->get_admin_inter_id();
        }

        $hotel_ids = $this->session->get_admin_hotels();

        //获取到的活动ID列表
        $ids = $this->input->get('ids');
        $message = '同步失败';
        $result = FALSE;
        if ($ids) {
            $ids = trim($ids, ',');
            $actIds = explode(',', $ids);
            if (count($actIds) > 0) {
                $model_name = $this->main_model_name();
                $model = $this->_load_model($model_name);
                $actList = $model->get_activity_killsec_list_byActIds($actIds, $inter_id);
                if (count($actList) > 0) {

                    $this->load->model('wx/Publics_model', 'PublicsModel');
                    $publics = $this->PublicsModel->get_public_by_id($inter_id);
                    $link = '';
                    if ($publics) {
                        $link = isset($publics['domain']) ? 'http://' . $publics['domain']
                            . DS . 'index.php'
                            . DS . 'soma'
                            . DS . 'package'
                            . DS . 'package_detail'
                            . DS . '?id=' . $inter_id . '&pid=' : '';
                    }

                    //查找出没有同步的数据
                    $syncs = array();
                    foreach ($actList as $k => $v) {
                        //这里是否要过滤字段已经是同步到中心平台，暂时不处理，中心平台会有相应的处理
                        // if( $v['is_sync_center'] != Soma_base::STATUS_TRUE ){
                        // 	//没有同步
                        // 	$syncs[$v['act_id']] = $v['act_id'];
                        // }
                        $v['link'] = $link ? $link . $v['product_id'] : '';
                        $syncs[$v['act_id']] = $v;
                        break;//这里添加break退出循环的作用是，这一个版本只做单个同步(2016-09-22 luguihong)。以后需要批量同步，只需去掉break即可
                    }

                    //没有同步的数据
                    if (count($syncs) > 0) {

                        // var_dump( $actList );die;
                        //调用接口，同步到中心平台。现在还没有分系统，可以不使用curl方式发送

                        //同步信息需要记录操作员是谁
                        $post_admin = $this->session->get_admin_username();
                        $remote_ip = $this->input->ip_address();

                        /*
                        * 同步到中心平台
                        * @param 	act_info 		string(一个或多个数据)
                        * @return 	result
                        */
                        $this->load->model('soma/Center_activity_model', 'CenterModel');
                        $CenterModel = $this->CenterModel;

                        $json = array();
                        $json['data'] = $syncs;
                        $json['id'] = $inter_id;
                        $json['hid'] = $hotel_ids;
                        $json['ip'] = $remote_ip;
                        $json['admin'] = $post_admin;
                        $json['type'] = $model::ACT_TYPE_KILLSEC;
                        // $json['link'] = $link;//暂时加载公众号下面对应的域名

                        $return = $CenterModel->sync_activitys_to_center(json_encode($json), $inter_id);
                        $return_result = json_decode($return, TRUE);
                        // var_dump( $return_result );die;

                        if ($return_result['return_code'] == 'SUCCESS' && $return_result['result_code'] == 'SUCCESS') {
                            $successList = $return_result['success_list'];//处理成功的列表
                            $failList = $return_result['fail_list'];//处理失败的列表
                            //根据同步成功标识，修改is_sync_center这个状态位
                            if (count($successList) > 0) {
                                foreach ($successList as $k => $v) {
                                    $act_id = $v['act_id'];
                                    $model = $model->load($act_id);
                                    if ($model) {
                                        $model->m_save(array('is_sync_center' => $model::STATUS_TRUE));

                                        //记录后台操作日志
                                        $this->_log($model);
                                    }
                                }
                            }

                            $result = TRUE;

                        } else {
                            $message = isset($return_result['message']) ? $return_result['message'] : '此次同步数据到中心平台失败！';
                        }
                    } else {
                        $message = '没有需要同步的数据！';
                    }

                } else {
                    $message = '查找不到活动信息！是否已经禁用';
                }
            } else {
                $message = '系统出错！请稍后再试';
            }
        } else {
            $message = '系统出错！请稍后再试';
        }

        if ($result) {
            $this->session->put_success_msg('已同步数据到中心平台！');
        } else {
            $this->session->put_notice_msg($message);
        }

        $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));

    }


    /**
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function group()
    {

        $serviceName = $this->serviceName(Kill_Service::class);
        $serviceAlias = $this->serviceAlias(Kill_Service::class);
        $this->load->service($serviceName, null, $serviceAlias);


        $data = $this->soma_kill_service->groupInfo($this->current_inter_id);

        $this->label_action= '活动修改';
        $this->_init_breadcrumb($this->label_action);

        $data['form_url'] = Soma_const_url::inst()->get_url('*/*/update_group');
        if (empty($data['group']['id'])) {

            $data['form_url'] = Soma_const_url::inst()->get_url('*/*/create_group');
        }

        $data['token'] = array(
            'name' => $this->security->get_csrf_token_name(),
            'value' => $this->security->get_csrf_hash()
        );

        $html= $this->_render_content($this->_load_view_file('group'), $data, TRUE);
        echo $html;
   }

    /**
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function create_group()
    {
        $post = $this->input->post();

        $this->load->library('form_validation');

        $rules = array(
            'name' => array(
                'label' => '活动名称',
                'field' => 'name',
                'rules' => 'trim|required|min_length[1]|max_length[12]'
            ),
            'bg_img' => array(
                'label' => '首页背景图片',
                'field' => 'bg_img',
                'rules' => 'trim|required|valid_url'
            ),
            'products' => array(
                'label' => '商品详情',
                'field' => 'products',
                'rules' => 'required|valid_json[2]'
            ),
            'start_time' => array(
                'label' => '开始时间',
                'field' => 'start_time',
                'rules' => 'required|valid_date'
            ),
            'end_time' => array(
                'label' => '结束时间',
                'field' => 'end_time',
                'rules' => 'required|valid_date'
            ),
            'redirect_info[name]' => array(
                'label' => '提前进入',
                'field' => 'redirect_info[name]',
                'rules' => 'trim|required|min_length[1]|max_length[30]'
            ),
            'redirect_info[url]' => array(
                'label' => '提前进入',
                'field' => 'redirect_info[url]',
                'rules' => 'required|valid_url'
            ),
            'show_time' => array(
                'label' => '活动展示时间',
                'field' => 'show_time',
                'rules' => 'trim|required|valid_date_format[H:i:s]'
            ),
            'kill_time' => array(
                'label' => '活动开始时间',
                'field' => 'kill_time',
                'rules' => 'trim|required|valid_date_format[H:i:s]'
            ),
            'buy_limit' => array(
                'label' => '每人限购数量',
                'field' => 'buy_limit',
                'rules' => 'required|is_natural_no_zero'
            ),
            'last_time' => array(
                'label' => '抢购持续时间',
                'field' => 'last_time',
                'rules' => 'required|is_natural_no_zero'
            ),
            'share_info[title]' => array(
                'label' => '分享名称',
                'field' => 'share_info[title]',
                'rules' => 'required|min_length[1]|max_length[20]'
            ),
            'share_info[desc]' => array(
                'label' => '分享简介',
                'field' => 'share_info[desc]',
                'rules' => 'required|min_length[1]|max_length[50]'
            ),
            'share_info[img]' => array(
                'label' => '分享的缩略图',
                'field' => 'share_info[img]',
                'rules' => 'required|valid_url'
            )
        );

        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() == FALSE) {

            $this->session->put_error_msg($this->form_validation->error_string());

        } else {

            $showTime = date_create(date('Y-m-d') . $post['show_time']);
            $killTime = date_create(date('Y-m-d') . $post['kill_time']);

            if ($showTime > $killTime->modify(' -1 hour')) {
                $this->session->put_error_msg('活动开始时间必须早于秒杀时间一小时！');
                $this->_redirect('soma/activity_killsec/group');
            }

            if ($post['last_time'] > (22 - $killTime->diff($showTime)->h) ) {
                $this->session->put_error_msg('持续时间太长！');
                $this->_redirect('soma/activity_killsec/group');
            }

            $startTime = date_create($post['start_time']);
            $endTime = date_create($post['end_time']);

            if ($endTime < $startTime) {
                $this->session->put_error_msg('活动开始时间必须早于结束时间！');
                $this->_redirect('soma/activity_killsec/group');
            }

            $serviceName = $this->serviceName(Kill_Service::class);
            $serviceAlias = $this->serviceAlias(Kill_Service::class);
            $this->load->service($serviceName, null, $serviceAlias);

            $post['inter_id'] = $this->current_inter_id;

            $result = $this->soma_kill_service->createGroup($post);
            if (!$result) {
                $this->session->put_error_msg('创建失败');
            }
            $this->session->put_success_msg('创建成功');
        }
        $this->_redirect('soma/activity_killsec/group');

    }

    /**
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function update_group()
    {
        $post = $this->input->post();

        $this->load->library('form_validation');

        $rules = array(
            'id' => array(
                'field' => 'id',
                'rules' => 'is_natural_no_zero'
            ),
            'name' => array(
                'label' => '活动名称',
                'field' => 'name',
                'rules' => 'required|min_length[1]|max_length[12]'
            ),
            'bg_img' => array(
                'label' => '首页背景图片',
                'field' => 'bg_img',
                'rules' => 'required|valid_url'
            ),
            'products' => array(
                'label' => '商品详情',
                'field' => 'products',
                'rules' => 'required|valid_json[2]'
            ),
            'start_time' => array(
                'label' => '开始时间',
                'field' => 'start_time',
                'rules' => 'required|valid_date'
            ),
            'end_time' => array(
                'label' => '结束时间',
                'field' => 'end_time',
                'rules' => 'required|valid_date'
            ),
            'redirect_info[name]' => array(
                'label' => '提前进入',
                'field' => 'redirect_info[name]',
                'rules' => 'trim|required|min_length[1]|max_length[30]'
            ),
            'redirect_info[url]' => array(
                'label' => '提前进入',
                'field' => 'redirect_info[url]',
                'rules' => 'required|valid_url'
            ),
            'show_time' => array(
                'label' => '活动展示时间',
                'field' => 'show_time',
                'rules' => 'trim|required|valid_date_format[H:i:s]'
            ),
            'kill_time' => array(
                'label' => '活动开始时间',
                'field' => 'kill_time',
                'rules' => 'trim|required|valid_date_format[H:i:s]'
            ),
            'buy_limit' => array(
                'label' => '每人限购数量',
                'field' => 'buy_limit',
                'rules' => 'required|is_natural_no_zero'
            ),
            'last_time' => array(
                'label' => '抢购持续时间',
                'field' => 'last_time',
                'rules' => 'required|is_natural_no_zero'
            ),
            'share_info[title]' => array(
                'label' => '分享名称',
                'field' => 'share_info[title]',
                'rules' => 'required|min_length[1]|max_length[20]'
            ),
            'share_info[desc]' => array(
                'label' => '分享简介',
                'field' => 'share_info[desc]',
                'rules' => 'required|min_length[1]|max_length[50]'
            ),
            'share_info[img]' => array(
                'label' => '分享的缩略图',
                'field' => 'share_info[img]',
                'rules' => 'required|valid_url'
            )
        );


        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() == FALSE) {

            $this->session->put_error_msg($this->form_validation->error_string());

        } else {

            $killTime = date_create(date('Y-m-d') . $post['kill_time']);
            $showTime = date_create(date('Y-m-d') . $post['show_time']);

            if ($showTime > $killTime->modify(' -1 hour')) {
                $this->session->put_error_msg('展示时间必须早于秒杀时间一小时！');
                $this->_redirect('soma/activity_killsec/group');
            }

            if ($post['last_time'] > (22 - $killTime->diff($showTime)->h) ) {
                $this->session->put_error_msg('持续时间太长！');
                $this->_redirect('soma/activity_killsec/group');
            }

            $startTime = date_create($post['start_time']);
            $endTime = date_create($post['end_time']);

            if ($endTime < $startTime) {
                $this->session->put_error_msg('开始时间必须早于结束时间！');
                $this->_redirect('soma/activity_killsec/group');
            }


            $serviceName = $this->serviceName(Kill_Service::class);
            $serviceAlias = $this->serviceAlias(Kill_Service::class);
            $this->load->service($serviceName, null, $serviceAlias);

            $post['inter_id'] = $this->current_inter_id;
            $result = $this->soma_kill_service->updateGroup($post);
            if (!$result) {
                $this->session->put_error_msg('修改失败');
            } else {
                $this->session->put_success_msg('修改成功');
            }

        }

        $this->_redirect('soma/activity_killsec/group');
    }


	# 秒杀实例监控代码开始

	/**
	 * 秒杀实例监控
	 * 获取秒杀未结束以及结束未到1小时的秒杀实例，监控实例情况
	 */
	public function monitor_grid()
	{	
        $this->label_action = '秒杀监控列表';
        $this->_init_breadcrumb($this->label_action);

        $temp_id = $this->session->get_temp_inter_id();
        if ($temp_id) {
            $inter_id = $temp_id;
        } else {
            $inter_id = $this->session->get_admin_inter_id();
        }
        $act_id = $this->input->get('act_id', true);

        $serviceName  = $this->serviceName(Kill_Service::class);
        $serviceAlias = $this->serviceAlias(Kill_Service::class);
        $this->load->service($serviceName, null, $serviceAlias);

        $data['data_set']   = $this->soma_kill_service->getKillsecMonitorData($inter_id, $act_id);
        $data['column_set'] = $this->soma_kill_service->getKillsecMonitorGridHeader();

        $html= $this->_render_content($this->_load_view_file('monitor_grid'), $data, TRUE);
        echo $html;
	}

    /**
     * 删除秒杀相关锁
     */
    public function del_killsec_lock()
    {
        $type = $this->input->post('type', true);

        $serviceName  = $this->serviceName(Kill_Service::class);
        $serviceAlias = $this->serviceAlias(Kill_Service::class);
        $this->load->service($serviceName, null, $serviceAlias);

        if(in_array($type, array(1, 2))
            && $this->soma_kill_service->deleteKillsecLock($type))
        {
            echo json_encode(array('status' => Soma_base::STATUS_TRUE, 'message' => '删除成功'));
        }
        else
        {
            echo json_encode(array('status' => Soma_base::STATUS_FALSE, 'message' => '删除失败，请联系开发人员'));
        }
    }

    public function test_monitor_log()
    {
        $serviceName  = $this->serviceName(Kill_Service::class);
        $serviceAlias = $this->serviceAlias(Kill_Service::class);
        $this->load->service($serviceName, null, $serviceAlias);
        $this->soma_kill_service->monitorKillsecServiceLogSimple();
    }

	# 秒杀实例监控代码结束

}
