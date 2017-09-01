<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Prices extends MY_Admin_Cprice {
    protected $label_module = '协议价';
    protected $label_controller = '协议价';
    protected $label_action = '';
    function __construct() {
        parent::__construct ();
    }

    protected function main_model_name()
    {
        return 'company/Company_model';
    }

    public function  old_gcp()
    {
        $inter_id = $this->session->get_admin_inter_id ();

        if ($inter_id == FULL_ACCESS)
            $filter = array ();
        else if ($inter_id)
            $filter = array (
                'inter_id' => $inter_id
            );
        else
            $filter = array (
                'inter_id' => 'deny'
            );
        $entity_id = $this->session->get_admin_hotels ();
        if (! empty ( $entity_id )) {
            // $filter['hotel_id']
        }
        // print_r($filter);die;

        /* 兼容grid变为ajax加载加这一段 */
        if (is_ajax_request ())
            // 处理ajax请求，参数规格不一样
        $get_filter = $this->input->post ();
        else
            $get_filter = $this->input->get ( 'filter' );

        if (! $get_filter)
            $get_filter = $this->input->get ( 'filter' );

        if (is_array ( $get_filter ))
            $filter = $get_filter + $filter;
        /* 兼容grid变为ajax加载加这一段 */

        if( $this->input->get ( 'ids' ) ) {
            $id = intval ( $this->input->get ( 'ids' ) );
            $this->c_grid ( $filter,'','grid',$id );
        }else{
            $this->c_grid ( $filter,'','grid','' );
        }


    }


    public function edit() {
        $this->label_action = '公司协议价';
        $this->_init_breadcrumb ( $this->label_action );
        $inter_id = $this->session->get_admin_inter_id ();

        $model_name = $this->main_model_name ();
        $model = $this->_load_model ( $model_name );
        $id = intval ( $this->input->get ( 'ids' ) );
        $this->load->model ( 'company/Company_model' );
        if ($id) {
            // for edit page.
            // $model= $this->hotel_ext_model->load($id);
            $model = $model->load ( $id );
            $fields_config = $model->get_field_config ( 'form' );
            $detail_field = array ();
            if (count ( $detail_field ) > 0) {
                $detail_field = $detail_field [0] ['attr_value'];
            } else {
                $detail_field = '';
            }
        } else {
            // for add page.
            // $model= $this->hotel_ext_model->load($id);
            $model = $model->load ( $id );

            if (! $model)
                $model = $this->_load_model ();
            $fields_config = $model->get_field_config ( 'form' );


            $detail_field = '';
        }

        $view_params = array (
            'model' => $model,
            'fields_config' => $fields_config,
            'check_data' => FALSE,
            'detail_field' => $detail_field,
            'services' => '',
            'hotel_ser' => ''
        )
            // 'gallery'=> $gallery,
        ;

        $html = $this->_render_content ( $this->_load_view_file ( 'edit' ), $view_params, TRUE );

        echo $html;
    }

    public function  edit_post(){
        $this->label_action = '信息维护';
        $inter_id = $this->session->get_admin_inter_id ();

        $model_name = $this->main_model_name ();
        $model = $this->_load_model ( $model_name );
        $post = $this->input->post ();

            $hotel_id=$post['chose_hotel'];
            $price_code=$post['ci_id'];
            $cp_code=$post['cp_code'];
            $company_id=$post['company_id'];
            $valid_time=$post['valid_time'];

        $this->cancelHotel($inter_id,$company_id,$hotel_id);

        $hotel_id=explode(',',$hotel_id);

        foreach($hotel_id as $arr){

            $result=$this->findHotel($inter_id,$company_id,$arr);

            if(!empty($result)){

                $this->updateCompanyHotelStatus($company_id,$arr,$inter_id,1,$price_code,$cp_code,$valid_time);

            }else{

                $this->newCompanyHotel($company_id,$cp_code,$price_code,$arr,$inter_id,$valid_time);

            }

        }


        $message = $this->session->put_success_msg ( '已保存数据！' );
//        $this->_log ( $model );
        $this->_redirect ( EA_const_url::inst ()->get_url ( '*/co_company/co_list' ) );


    }


    public function edit_post_bg() {
        $this->label_action = '信息维护';
        $this->_init_breadcrumb ( $this->label_action );

        $inter_id = $this->session->get_admin_inter_id ();

        $model_name = $this->main_model_name ();
        $model = $this->_load_model ( $model_name );
        $pk = $model->table_primary_key ();

        $this->load->library ( 'form_validation' );
        $post = $this->input->post ();

        $post['status']=1;

        $labels = $model->attribute_labels ();
        $base_rules = array (

            'company_id' => array (
                'field' => 'company_id',
                'label' => $labels ['company_id'],
                'rules' => 'trim'
            ),

            'cp_code' => array (
                'field' => 'cp_code',
                'label' => $labels ['cp_code'],
                'rules' => 'trim|required'
            ),

            'price_code' => array (
                'field' => 'price_code',
                'label' => $labels ['price_code'],
                'rules' => 'trim|required'
            ),

            'hotel_id' => array (
                'field' => 'hotel_id',
                'label' => $labels ['hotel_id'],
                'rules' => 'trim|required'
            ),

        );

        // 检测并上传文件。
        $post = $this->_do_upload ( $post, 'intro_img' );

        $adminid = $this->session->get_admin_id ();

        if (empty ( $post [$pk] )) {
            // add data.
            $this->form_validation->set_rules ( $base_rules );

            if ($this->form_validation->run () != FALSE) {
//                $post ['add_date'] = date ( 'Y-m-d H:i:s' );
                $post ['inter_id'] = $inter_id;

                $check_cp=$model->checkCompanyCp($post);

                if(!empty($check_cp)){

                    $this->session->put_notice_msg ( '已存在该协议价' );

                }else{


                $result = $model->m_sets ( $post )->m_save ( $post );
                $message = ($result) ? $this->session->put_success_msg ( '已新增数据！' ) : $this->session->put_notice_msg ( '此次数据保存失败！' );
                // $this->_log($model);

                }

                if($post['company_id']){
                    $id=$post['company_id'];
                    $this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/gcp?ids='.$id ) );
                }else{
                    $this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/gcp' ) );
                }

            } else
                $model = $this->_load_model ();
        } else {
            $this->form_validation->set_rules ( $base_rules );
            if ($this->form_validation->run () != FALSE) {
//                $post ['last_update_time'] = date ( 'Y-m-d H:i:s' );
                $post ['inter_id'] = $inter_id;


                $check_cp=$model->checkCompanyCp($post);

                if(!empty($check_cp)){

                    $this->session->put_notice_msg ( '已存在该协议价' );

                }else{

                $result = $model->load ( $post [$pk] )->m_sets ( $post )->m_save ( $post );
                $message = ($result) ? $this->session->put_success_msg ( '已保存数据！' ) : $this->session->put_notice_msg ( '此次数据修改失败！' );

                }

                $this->_log ( $model );
                if($post['company_id']){
                    $id=$post['company_id'];
                    $this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/gcp?ids='.$id ) );
                }else{
                    $this->_redirect ( EA_const_url::inst ()->get_url ( '*/*/gcp' ) );
                }
            } else
                $model = $model->load ( $post [$pk] );
        }

        // 验证失败的情况
        $validat_obj = _get_validation_object ();
        $message = $validat_obj->error_html ();
        // 页面没有发生跳转时用寄存器存储消息
        $this->session->put_error_msg ( $message, 'register' );

        $fields_config = $model->get_field_config ( 'form' );
        $view_params = array (
            'model' => $model,
            'fields_config' => $fields_config,
            'check_data' => TRUE
        );
        $html = $this->_render_content ( $this->_load_view_file ( 'edit' ), $view_params, TRUE );
        echo $html;
    }

    public function qrcode_front()
    {
        if($id= $this->input->get('ids')){
            $inter_id = $this->session->get_admin_inter_id ();
            $url= EA_const_url::inst()->get_front_url($inter_id, 'company/Company/index',
                array('cid'=> $id,'id'=>$inter_id));
            $this->_get_qrcode_png($url);
        } else
            echo '参数错误';
    }


    public function gcp(){

        $inter_id = $this->session->get_admin_inter_id ();

        $company_id = $this->input->get('ids');

        if(!$company_id){
            $this->_redirect ( EA_const_url::inst ()->get_url ( '*/co_company/co_list' ) );
        }

//        $data['hotels'] = $this->getHotels();

        $companyInfo=$this->getCompanyInfo($company_id);
        $price_code=$this->getAllPriceCode($inter_id);
        $temp_hotel=$this->getCompanyHotel($inter_id,$company_id);
        $company_hotel=array();

        foreach($temp_hotel as $arr){
            $company_hotel[$arr['hotel_id']]=$arr;
        }

        $data=array(
            'hotels'=>$this->getHotels(),
            'companyInfo'=>$companyInfo,
            'all_price_code'=>$price_code,
            'company_hotel'=>$company_hotel
        );


        if ($inter_id == FULL_ACCESS)
            $filter = array ();
        else if ($inter_id)
            $filter = array (
                'inter_id' => $inter_id
            );
        else
            $filter = array (
                'inter_id' => 'deny'
            );
        $entity_id = $this->session->get_admin_hotels ();
        if (! empty ( $entity_id )) {
            // $filter['hotel_id']
        }
        // print_r($filter);die;

        /* 兼容grid变为ajax加载加这一段 */
        if (is_ajax_request ())
            // 处理ajax请求，参数规格不一样
        $get_filter = $this->input->post ();
        else
            $get_filter = $this->input->get ( 'filter' );

        if (! $get_filter)
            $get_filter = $this->input->get ( 'filter' );

        if (is_array ( $get_filter ))
            $filter = $get_filter + $filter;
        /* 兼容grid变为ajax加载加这一段 */

        $html= $this->_render_content($this->_load_view_file('cpedit'), $data, false);

        echo $html;


    }


    protected function getHotels()           //获取该公众号所有的酒店
    {
        $this->load->model('hotel/Hotel_model');
        $hotels = $this->Hotel_model->get_all_hotels($this->session->get_admin_inter_id(),1);

        $ret = array();
        foreach($hotels as $hotel) {
            $ret[$hotel['hotel_id']] = $hotel['name'];
        }
        return $ret;
    }


    protected function getCompanyInfo($id){      //当前协议客公司的资料

        $this->load->model('company/Company_dispose_model');

        $result=array();

        $result['companyInfo'] = $this->Company_dispose_model->getCompanyById($id);
        $result['priceInfo'] = $this->Company_dispose_model->getCompanyPrice($id);


        return $result;

    }

    protected function getAllPriceCode($inter_id){      //公众号所有的价格代码

        $this->load->model('company/Company_dispose_model');

        $result=$this->Company_dispose_model->getAllCompanyPriceCode($inter_id);

        return $result;

    }


    protected function getCompanyHotel($inter_id,$id){      //所有已经与该公司签订协议客的酒店

        $this->load->model('company/Company_dispose_model');

        $result=$this->Company_dispose_model->getCompanyHotel($inter_id,$id);


        return $result;
    }


    protected function newCompanyHotel($company_id,$cp_code,$price_code,$hotel_id,$inter_id,$valid_time){

        $this->load->model('company/Company_dispose_model');

        $result=$this->Company_dispose_model->newCompanyHotel($company_id,$cp_code,$price_code,$hotel_id,$inter_id,$valid_time);

        return $result;

    }


    protected function updateCompanyHotelStatus($company_id,$hotel_id,$inter_id,$status,$price_code,$cp_code,$valid_time){

        $this->load->model('company/Company_dispose_model');

        if(!empty($hotel_id) && $hotel_id!=''){

            $result=$this->Company_dispose_model->updateCompanyHotelStatus($company_id,$hotel_id,$inter_id,$status,$price_code,$cp_code,$valid_time);

            return $result;

        }else{

            return true;

        }

    }


    protected function findHotel($inter_id,$company_id,$hotel_id){

        $this->load->model('company/Company_dispose_model');

        $result=$this->Company_dispose_model->confirmHotel($inter_id,$company_id,$hotel_id);

        return $result;

    }


    protected function cancelHotel($inter_id,$company_id,$hotel_id){

        $this->load->model('company/Company_dispose_model');

        $result=$this->Company_dispose_model->cancelHotel($inter_id,$company_id,$hotel_id);

        return $result;


    }



}
