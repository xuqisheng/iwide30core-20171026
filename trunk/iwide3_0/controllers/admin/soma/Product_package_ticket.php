<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_package_ticket extends MY_Admin_Soma {

    protected $label_controller= '门店管理';		//在文件定义
    protected $label_action= '';

    protected function main_model_name()
    {
        return 'soma/Product_package_ticket_model';
    }

    /**
     * 获取通用过滤条件
     * 公众号，酒店ID
     */
    protected function _common_filter() {
        $filter = array();

        $inter_id = $this->session->get_admin_inter_id();
        if(!$inter_id) { $filter['inter_id'] = 'deny';}
        if($inter_id != FULL_ACCESS) { $filter['inter_id'] = $inter_id; }

        return $filter;
    }

    public function grid() {
        $this->label_action = '配置门店';
        $this->_init_breadcrumb($this->label_action);

        $filter = $this->_common_filter();

        if(is_ajax_request()){
            $model_name= $this->main_model_name();
            $model= $this->_load_model($model_name);
            $get_filter= $this->_ajax_params_parse( $this->input->post(null, true), $model );
        } else {
            $get_filter= $this->input->get('filter', true);
        }
        if(is_array($get_filter)) {
            if(isset($get_filter['inter_id'])) {
                unset($get_filter['inter_id']);
            }
            $filter += $get_filter;
        }

        //皮肤选择
        $this->load->model('soma/Theme_config_model','somaThemeConfigModel');
        $themeList = $this->somaThemeConfigModel->get_themes( $this->session->get_admin_inter_id() );
        $themeIds = array();
        if( $themeList )
        {
            foreach( $themeList as $k=>$v )
            {
                $themeIds[$v['theme_id']] = $v['theme_name'];
            }
        }
        $viewdata = array(
            'themeIds'=>$themeIds,
        );
        $this->_grid($filter, $viewdata);
    }

    public function edit() {
        $this->label_action= '配置门店维护';
        $this->_init_breadcrumb($this->label_action);

        $model_name= $this->main_model_name();
        $model= $this->_load_model($model_name);

        $id= intval($this->input->get('ids'));
        if($id){ $model= $model->load($id); }
        if(!$model) { $model= $this->_load_model(); }

        //越权查看数据跳转
        if( !$this->_can_edit($model) ){
            $this->session->put_error_msg('找不到该数据');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
        }

        // 阻止非业务账号操作 _get_real_inter_id
        $filter = $this->_common_filter();
        $inter_id = $this->_get_real_inter_id(true);
        $filter['inter_id'] = $inter_id;

        $fields_config= $model->get_field_config('form');

        $this->load->model('soma/Product_package_model');
        $product_model= $this->Product_package_model;
        $products= $product_model->get_package_list($inter_id, array('inter_id'=>$inter_id));

        $grid_data = array();
        $this->load->helper('soma/package');
        foreach ($products as $p) {
            $row = array();
            $row[] = '';
            $row[] = $p['product_id'];
            $row[] = $p['name'];
            $row[] = $p['price_package'];
            $row['DT_RowId'] = $p['product_id'];
            $grid_data[] = $row;
        }

        $view_params= array(
            'model'=> $model,
            'fields_config'=> $fields_config,
            'check_data'=> FALSE,
            'products' => $products,
            'grid_data' => $grid_data,
            'tr_slt' => $model->m_get('product_ids'),
        );

        $view_params = $this->_view_params( $model, $inter_id, $view_params );

        $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
        //echo $html;die;
        echo $html;
    }

    protected function _view_params( $model, $inter_id, $view_params )
    {
        //分类列表
        $this->load->model('soma/Category_package_model','somaCategoryPackageModel');
        $cateList = $this->somaCategoryPackageModel->get_package_category_list( $inter_id );
        $cateIds = array();
        if( $cateList )
        {
            foreach( $cateList as $v )
            {
                $cateIds[$v['cat_id']] = $v;
                $cateIds[$v['cat_id']]['items'] = array();
            }

            $this->load->model('soma/Product_package_model','somaProductPackageModel');
            $listNum = count( $cateIds ) * 20;
            $pageNum = 1;
            $isTicket = TRUE;
            $productList = $this->somaProductPackageModel->get_product_package_list( array_keys( $cateIds ), $inter_id, $pageNum, $listNum, $isTicket );
            //var_dump( $productList );die;
            if( $productList )
            {
                foreach ( $productList as $v )
                {
                    if( $cateIds[$v['cat_id']] )
                    {
                        $data = array();
                        $data['product_id'] = $v['product_id'];
                        $data['name'] = $v['name'];
                        $data['cat_id'] = $v['cat_id'];
                        $cateIds[$v['cat_id']]['items'][] = $data;
                    }
                }
            }
        }
//        var_dump( $cateIds );die;
        $view_params['catIds'] = $cateIds;

        //皮肤选择
        $this->load->model('soma/Theme_config_model','somaThemeConfigModel');
        $themeList = $this->somaThemeConfigModel->get_themes( $inter_id );
        if( $themeList )
        {
            $themeArr = array('v1','ticket','zongzi', 'mooncake4');
            $themeListNew = array();
            //先去掉除了v1、ticket、zongzi的皮肤
            foreach( $themeList as $k=>$v )
            {
                if( in_array( $v['theme_path'], $themeArr ) )
                {
                    $themeListNew[$k] = $v;
                }
            }

            $themeList = $themeListNew;
        }
        $view_params['themeList'] = $themeList;

        //查找出公众号名
        $this->load->model( 'wx/Publics_model' );
        $publics = $this->Publics_model->get_public_by_id($inter_id);
        $interIds = array();
        if( $publics ){
            $interIds[$inter_id] = $publics['name'];
        }else{
            $interIds[$inter_id] = '';
        }

        $view_params['interIds'] = $interIds;
        //$view_params['hotelIds'] = $hotelIds;
        $view_params['inter_id'] = $inter_id;

        $post_url = Soma_const_url::inst()->get_url('*/*/edit_post',array('inter_id'=>$inter_id));
        $view_params['post_url'] = $post_url;

        $pk= $model->table_primary_key();
        $view_params['pk'] = $pk;

        return $view_params;
    }

    /**
     * 权限验证->数据验证->数据提取->数据保存
     * 保存规则表-->保存规则产品表
     */
    public function edit_post() {
        $post = $this->input->post(null, true);
        $admin = $this->session->admin_profile;
        $post['op_user'] = $admin['username'];
        $model= $this->_load_model();
        $pk = $model->table_primary_key();
        if( isset( $post[$pk] ) && !empty( $post[$pk] ) )
        {
            $model = $model->load($post[$pk]);
            if( !$model )
            {
                $model= $this->_load_model();
            }
        }

        $post['name'] = htmlspecialchars( $post['name'] );

        //展位内容，前端传过来的是json格式
        $post['block_arr'] = isset( $post['block_arr'] ) ? $post['block_arr'] : '';
        if( $post['block_arr'] )
        {
            $blockArr = array();
            $block_arr = json_decode( $post['block_arr'], true );
            foreach( $block_arr as $k=>$v )
            {
                if( !$v['block_content'] && !$v['block_link'] && !$v['block_img'] )
                {
                    //都为空，就不保存
                } else {
                    $v['block_content'] = htmlspecialchars( $v['block_content'] );
                    $v['block_link']    = htmlspecialchars( $v['block_link'] );

                    $blockArr[] = $v;
                }
            }

            $post['block_arr'] = $blockArr ? json_encode( $blockArr ) : '';
        }

        //组装适用商品配置，前端传过来的是数组形式，以json格式保存到数据库
        $post['product_ids'] = isset( $post['product_ids'] ) ? json_encode( $post['product_ids'] ) : '';
        if( $post['product_ids'] )
        {
            /**
             array(2) {
                [3]=&gt;//cat_id
                array(1) {
                    [11696]=&gt;//product_id
                    string(5) "11696"
                }
                [10041]=&gt;
                array(1) {
                    [10021]=&gt;
                    string(5) "10021"
                }
             }
             */
        }
//var_dump( $model, $this->_can_edit($model), $model->form_validation($post) );die;
        if($model !=null
            && $this->_can_edit($model)
            && $model->form_validation($post)) {
            $res = $model->m_save($post);
            $this->_log($model);
            if($res) {

                $set = array();
                if( isset( $post[$pk] ) && !empty( $post[$pk] ) )
                {
                    $set['update_time'] = date('Y-m-d H:i:s');
                    $ticketId = $post[$pk];
                } else {
                    $ticketId = $res;
                    $model = $model->load( $res );
                    if( !$model )
                    {
                        $model= $this->_load_model();
                    }

                    $set['create_time'] = date('Y-m-d H:i:s');

                }

                $this->load->model('wx/Publics_model','somaPublicsModel');
                $publics= $this->somaPublicsModel->get_public_by_id( $post['inter_id'] );
                $link = '';
                if( $publics ){
                    $link = isset( $publics['domain'] ) ? 'http://' . $publics['domain']
                        . DS . 'index.php'
                        . DS . 'soma'
                        . DS . 'package'
                        . DS . 'index'
                        . DS . '?id='.$post['inter_id'] . '&tkid='.$ticketId . '&catid=' : '';
                }

                $set['link'] = $link;

                if( $set )
                {
                    $model->m_sets( $set )->m_save();
                }

                $this->session->put_success_msg('操作成功！');
            } else {
                $this->session->put_error_msg('操作失败，请稍后再重新尝试');
            }

        } else {
            $this->session->put_notice_msg('不允许修改的数据或提交的数据有误，请稍后再重新尝试');
        }
        $this->_redirect(EA_const_url::inst()->get_url('*/*/grid'));
    }

}
