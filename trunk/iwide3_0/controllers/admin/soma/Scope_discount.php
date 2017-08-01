<?php

use App\services\soma\ScopeDiscountService;
use App\models\soma\ScopeDiscount as ScopeDiscountModel;
use App\models\soma\ScopeProductLink as ScopeProductLinkModel;
/**
 * User: renshuai <renshuai@mofly.cn>
 * Date: 2017/5/5
 * Time: 10:39
 */

class Scope_discount extends MY_Admin_Soma
{
    /**
     *
     * 返回公众号可用的价格配置
     *
     * @example request http://adminhost/soma/scope_discount/index?limit=1&page=1
     *
     * return json format
     * <code>
     * [
     *  {
     *      "id": "3",  //价格配置的ID
     *      "inter_id": "a450089706",
     *      "name": "3333", //价格配置的名称
     *      "scope": "1",
     *      "status": "1",
     *      "start_time": "2016-04-29 10:27:49",
     *      "end_time": "2018-04-29 10:27:49",
     *      "extra": null,
     *      "created_at": null,
     *      "updated_at": null
     *  }
     * ]
     * </code>
     *
     * @author renshuai  <renshuai@mofly.cn>
     * @return void
     */
    public function index()
    {
        $inter_id = $this->current_inter_id;
        $page = $this->input->get('page', null, 1);
        $limit = $this->input->get('limit',null, 10);

        $result = ScopeDiscountService::getInstance()->getAvailableList($inter_id, ScopeDiscountModel::SCOPE_SOCIAL, $page, $limit);
        echo $this->json($result);
    }

    /**
     * 后台列表页
     * @author luguihong  <luguihong@jperation.com>
     */
    public function grid()
    {
        $inter_id = $this->current_inter_id;
        $scopeModel = new ScopeDiscountModel();

        $per_page = $this->input->get('per_page',TRUE);
        if( $per_page && $per_page > 0 )
        {
            $page = $per_page;
        } else {
            $page = 1;
        }

        //分页
        $this->load->library('pagination');
        $config['per_page']             = 20;
        $config['use_page_numbers']     = TRUE;
        $config['cur_page']             = $page;

        $search = $this->input->post('search') ? $this->input->post('search') : '';
        $search = trim( $search );
        if( $search )
        {
            $result = $scopeModel->searchScopeProductList($inter_id, $search);
            $total  = count($result);
        } else {
            $result = $scopeModel->getScopeProductList($inter_id, $config['cur_page'], $config['per_page']);
            $total  = $scopeModel->getScopeProductTotal($inter_id);
        }

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

        $status = $scopeModel->getStatus();
        $view_params = array(
            'check_data'    => FALSE,
            'data'          => $result,
            'page_count'    => count($result),
            'total'         => $total,
            'status'        => $status,
            'search'        => $search,
            'pagination' => $this->pagination->create_links(),
        );

        $html= $this->_render_content($this->_load_view_file('grid'), $view_params, TRUE);
        echo $html;
    }

    /**
     * 编辑价格配置
     * @author luguihong  <luguihong@jperation.com>
     */
    public function edit()
    {
        $scopeId = $this->input->get('id');
        $interId = $this->current_inter_id;

        $scopeModel = new ScopeDiscountModel();
        $scopeData = $productList = $checkIds = array();
        if( $scopeId )
        {
            //获取价格配置信息
            $newScopeModel = $scopeModel->load( $scopeId );
            if( $newScopeModel )
            {
                $scopeData = $newScopeModel->m_data();

                if( $scopeData['inter_id'] != $interId )
                {
                    //不是自己公众号下面的数据
                    $url = Soma_const_url::inst()->get_url('*/*/edit?id='.$scopeId);
                    redirect($url);
                } else {
                    if( $scopeData['extra'] )
                    {
                        /**
                        $data['extra'] = array(
                            'limit_num'   => 0,                //0代表不限购，数值代表每个商品限购多少份
                            'four_rules'  => array(
                                'four_rule'=>1,                //1.加，2.减，3.乘，4.除
                                'float_num'=>0.01,             //四则运算数量
                            )
                        )
                         */
                        $scopeData['extra'] = json_decode( $scopeData['extra'], TRUE );
                    }
                }

            }

            //获取配置商品列表
            $scopeProductModel = new ScopeProductLinkModel();
            $productList = $scopeProductModel->getByScopeId( $scopeId );
            if( $productList )
            {
                /**
                array(2) {
                    [12068]=&gt;
                        array(2) {
                        [153]=&gt;
                            array(2) {
                                ["price"]=&gt;
                                string(4) "0.51"
                                ["id"]=&gt;
                                string(2) "12"
                            }
                        [156]=&gt;
                            array(2) {
                                ["price"]=&gt;
                                string(4) "0.52"
                                ["id"]=&gt;
                                string(2) "13"
                            }
                        }
                    [12029]=&gt;
                        array(2) {
                            ["price"]=&gt;
                            string(4) "0.51"
                            ["id"]=&gt;
                            string(2) "14"
                        }
                }
                 */
                foreach( $productList as $k=>$v )
                {
                    if( isset($v['setting_id']) && !empty($v['setting_id']) )
                    {
                        $checkIds[$v['product_id']][$v['setting_id']]['price']  = $v['price'];
                        $checkIds[$v['product_id']][$v['setting_id']]['id']     = $v['id'];
                    } else {
                        $checkIds[$v['product_id']]['price']    = $v['price'];
                        $checkIds[$v['product_id']]['id']       = $v['id'];
                    }
                }
            }

        }

        //要替换的字符串
        $replace = array(
            "<br>", "<Br>", "<br/>", "<Br/>", "<br />", "<Br />", "<b>", "</b>",
            "&lt;br&gt;", "&lt;Br&gt;", "&lt;br/&gt;", "&lt;Br/&gt;", "&lt;br /&gt;", "&lt;Br /&gt;", "&lt;b&gt;", "&lt;/b&gt;",
            "&#60;br&#62;", "&#60;Br&#62;", "&#60;br/&#62;", "&#60;Br/&#62;", "&#60;br /&#62;", "&#60;Br /&#62;", "&#60;b&#62;", "&#60;b/&#62;",
        );

        //下面三个变量用于价格配置选择分类、商品、规格
        $the_cate = $the_product = $the_setting = array();

        //获取分类
        $this->load->model('soma/Category_package_model','somaCategoryPackageModel');
        $cateList = $this->somaCategoryPackageModel->get_package_category_list( $interId );
        if( $cateList )
        {
            foreach( $cateList as $v )
            {
                $data = array();
                $data['cate_id']    = $v['cat_id'];
                $data['cate_name']  = $v['cat_name'];
                $data['checked']    = FALSE;
                $the_cate[$v['cat_id']] = $data;
            }

            /**
             * @var Product_package_model $somaProductPackageModel
             */
            $this->load->model('soma/Product_package_model','somaProductPackageModel');
            $somaProductPackageModel = $this->somaProductPackageModel;
            $cateProductList = $somaProductPackageModel->get_product_package_list( array_keys( $the_cate ), $interId, NULL, NULL, TRUE );
            if( $cateProductList )
            {
                //先过滤掉运费补差、积分等商品
                $proTypeArr = array(
                    $somaProductPackageModel::PRODUCT_TYPE_POINT,//积分商品
                    $somaProductPackageModel::PRODUCT_TYPE_SHIPPING,//运费补差
                );

                //组装商品信息
                foreach ( $cateProductList as $v )
                {
                    if( !in_array($v['type'],$proTypeArr) && $the_cate[$v['cat_id']] )
                    {
                        $data = array();
                        $data['cate_id']         = $v['cat_id'];
                        $data['product_id']      = $v['product_id'];

                        $data['product_name'] = str_replace( $replace, '', htmlspecialchars( $v['name'] ) );
                        //$data['product_name']    = $v['name'];

                        if( isset($checkIds[$v['product_id']]) && isset($checkIds[$v['product_id']]['id']) )
                        {
                            //存在商品，那么他的分类选中
                            $the_cate[$v['cat_id']]['checked'] = TRUE;

                            //没有规格的
                            $data['order_price']     = $v['price_package'];
                            $data['price']           = $checkIds[$v['product_id']]['price'];
                            $data['checked_id']      = $checkIds[$v['product_id']]['id'];
                            $data['checked']         = TRUE;
                        } else {
                            //有规格的或者没有商品的都默认为不选中先
                            $data['order_price']     = $v['price_package'];
                            $data['price']           = $v['price_package'];
                            $data['checked_id']      = '';
                            $data['checked']         = FALSE;
                        }

                        $the_product[$v['product_id']] = $data;

                    }

                }

                if( $the_product )
                {
                    $this->load->model('soma/Product_specification_setting_model','somaProductSpecificationSettingModel');
                    $somaProductSpecificationSettingModel = $this->somaProductSpecificationSettingModel;
                    $specSettingList = $somaProductSpecificationSettingModel->get_inter_product_spec_setting($interId, array_keys( $the_product ) );

                    if( $specSettingList )
                    {
                        foreach ($specSettingList as $specRow)
                        {
                            $setting_spec_compose = json_decode($specRow['setting_spec_compose'], true);

                            $data = array();
                            $data['cate_id']        = $the_product[$specRow['product_id']]['cate_id'];
                            $data['product_id']     = $specRow['product_id'];
                            $data['setting_id']     = $specRow['setting_id'];

                            //配置规格名称
                            if( $specRow['type'] == $somaProductPackageModel::SPEC_TYPE_TICKET )
                            {
                                foreach( $setting_spec_compose as $k=>$v )
                                {
                                    $data['setting_name'] = implode( '', $v['spec_name'] );
                                }
                            } elseif ( $specRow['type'] == $somaProductPackageModel::SPEC_TYPE_SCOPE ) {
                                $data['setting_name']   = implode( ',', $setting_spec_compose[$specRow['setting_id']]['spec_name'] );
                            }
                            //$data['setting_name'] = $the_product[$specRow['product_id']]['product_name'].'('.$data['setting_name'].')';

                            //配置价格
                            if( isset($checkIds[$specRow['product_id']][$specRow['setting_id']]) )
                            {
                                //存在规格，那么他的商品选中
                                $the_product[$specRow['product_id']]['checked'] = TRUE;

                                $data['order_price']     = $specRow['spec_price'];
                                $data['price']           = $checkIds[$specRow['product_id']][$specRow['setting_id']]['price'];
                                $data['checked_id']      = $checkIds[$specRow['product_id']][$specRow['setting_id']]['id'];
                                $data['checked']         = TRUE;
                            } else {
                                $data['order_price']     = $specRow['spec_price'];
                                $data['price']           = $specRow['spec_price'];
                                $data['checked_id']      = '';
                                $data['checked']         = FALSE;

                            }

                            //对于存在规格的商品，把商品里面的价格去掉
                            if( isset( $the_product[$specRow['product_id']] ) )
                            {
                                unset($the_product[$specRow['product_id']]['order_price']);
                                unset($the_product[$specRow['product_id']]['price']);
                            }

                            $the_setting[$specRow['setting_id']] = $data;
                        }
                    }
                }
            }
        }

//        var_dump( $the_setting );die;

        $view_params = array(
            'check_data'    => FALSE,
            'data'          => $scopeData,
            'model'         => $scopeModel,
            'the_cate'      => $the_cate,
            'the_product'   => $the_product,
            'the_setting'   => $the_setting,
            'typeList'      => $scopeModel->getScope(),
            'statusList'    => $scopeModel->getStatus(),
        );

        $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
        echo $html;
    }

    public function edit_post()
    {
        $interId = $this->current_inter_id;
        $posts = $this->input->post(null,true);
        //var_dump( $posts );die;

        /**
         * -------------------
         * 参数验证
         * -------------------
         */
        $this->load->library('form_validation');
        $rules = array(
            'name' => array(
                'field' => 'name',
                'rules' => 'required'
            ),
            'scope' => array(
                'field' => 'scope',
                'rules' => 'numeric'
            ),
            'limit_num' => array(
                'field' => 'limit_num',
                'rules' => 'numeric'
            ),
            'start_time' => array(
                'field' => 'start_time',
                'rules' => 'required'
            ),
            'end_time' => array(
                'field' => 'end_time',
                'rules' => 'required'
            ),
            'status' => array(
                'field' => 'status',
                'rules' => 'numeric'
            )
        );

        $this->form_validation->set_data($posts);
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === false)
        {
            //$result['message'] = '参数有误！' . $this->form_validation->error_string();
            $url = Soma_const_url::inst()->get_url('*/*/edit?id='.$posts['id']);
            redirect($url);
        }

        $id = isset( $posts['id'] ) && !empty( $posts['id'] ) ? $posts['id'] + 0 :'';

        //处理数据
        $scopeData = array();
        $scopeData['inter_id']   = $interId;
        $scopeData['name']       = isset($posts['name'])?$posts['name']:'';
        $scopeData['scope']      = isset($posts['scope'])?$posts['scope']:'';
        $scopeData['start_time'] = isset($posts['start_time'])?$posts['start_time']:'';
        $scopeData['end_time']   = isset($posts['end_time'])?$posts['end_time']:'';
        $scopeData['status']     = isset($posts['status'])?$posts['status']:'';

        //结束时间不能少于开始时间
        if( $scopeData['start_time'] && $scopeData['end_time'] && $scopeData['end_time'] < $scopeData['start_time'] )
        {
            $url = Soma_const_url::inst()->get_url('*/*/edit?id='.$posts['id']);
            redirect($url);
        }

        //限购数量
        $goodsLimit = isset($posts['goods_limit'])?$posts['goods_limit']+0:'';
        if( $goodsLimit && $goodsLimit == ScopeDiscountModel::BUY_UNLIMIT )
        {
            //不限购，那么limit_num就要=0
            $limitNum = 0;
        } else {
            $limitNum = isset($posts['limit_num'])?$posts['limit_num']+0:'';
        }

        if( $limitNum < 0 )
        {
            $limitNum = '';
        }

        //加减乘除
        $fourRule = isset($posts['four_rule'])?$posts['four_rule'] + 0:'';

        //限购数量
        $floatNum = isset($posts['float_num'])?$posts['float_num'] + 0:'';
        if( $floatNum < 0 )
        {
            $floatNum = 0;
        }

        $scopeData['extra'] = array(
            'limit_num'         => $limitNum,                       //0代表不限购，数值代表每个商品限购多少份
            'four_rules'        => array(
                                    'four_rule'  => $fourRule,      //1.加，2.减，3.乘，4.除
                                    'float_num'  => $floatNum,      //四则运算数量
            )
        );
        $scopeData['extra'] = json_encode( $scopeData['extra'] );

        $addProductLinkData = $updateProductLinkData = $configProductIds = array();
        $config = isset($posts['config']) ? json_decode( $posts['config'], TRUE ) : array();
        if( $config )
        {
            //获取配置商品列表
            $scopeProductModel = new ScopeProductLinkModel();
            $productList = $scopeProductModel->getByScopeId( $id );
            if( $productList )
            {
                foreach( $productList as $k=>$v )
                {
                    $configProductIds[$v['id']] = $v;
                }
            }

            //组装价格配置的商品列表
            foreach( $config as $k=>$v )
            {
                $data = array();

                $checkedId = isset($v['checked_id']) && $v['setting_id'] != 'undefined'?$v['checked_id']:'';
                $productId = isset($v['product_id'])?$v['product_id']:'';
                $settingId = isset($v['setting_id']) && $v['setting_id'] != 'null'?$v['setting_id']:'';
                $data['product_id'] = $productId;
                $data['setting_id'] = $settingId;

                //如果价格少于0的
                $price = $v['price'] + 0;
                if( $price < 0 )
                {
                    $data['price']  = 0;
                } else {
                    $data['price']  = $price;
                }

                $data['limit_num']  = $limitNum;

                if( $id )
                {
                    $data['scope_id'] = $id;

                    if( isset($configProductIds[$checkedId]) )
                    {
                        $data['id'] = $checkedId;
                        $data['updated_at'] = date('Y-m-d H:i:s');
                        $updateProductLinkData[] = $data;

                        //去掉存在的，剩下的就是删除的
                        unset($configProductIds[$checkedId]);
                    } else {
                        //不存在就是add
                        $data['created_at'] = date('Y-m-d H:i:s');
                        $addProductLinkData[] = $data;
                    }

                } else {
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $addProductLinkData[] = $data;
                }

            }
        }

        //需要删除的数据
        $deleteProductLinkIds = array();
        if( $configProductIds )
        {
            foreach( $configProductIds as $k=>$v )
            {
                $deleteProductLinkIds[] = $v['id'];
            }
        }

        $scopeDiscountModel = new ScopeDiscountModel();
        $scopeDiscountModel->addProductLinkData       = $addProductLinkData;
        $scopeDiscountModel->updateProductLinkData    = $updateProductLinkData;
        $scopeDiscountModel->deleteProductLinkIds     = $deleteProductLinkIds;
        if( $id )
        {
            //edit
            $scopeData['updated_at'] = date('Y-m-d H:i:s');
            $result = $scopeDiscountModel->scopeDiscountUpdate( $id, $scopeData, $interId );

        } else {
            //add
            $scopeData['created_at'] = date('Y-m-d H:i:s');
            $result = $scopeDiscountModel->scopeDiscountSave( $scopeData, $interId );
        }

        if( $result )
        {
            //echo 'success';
            $this->session->put_success_msg('已保存数据！');
        } else {
            //echo 'fail';
            $this->session->put_notice_msg('此次数据修改失败！');
        }

        $this->_redirect(Soma_const_url::inst()->get_url('*/*/grid'));

    }

}