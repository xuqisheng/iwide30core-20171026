<?php
use App\services\soma\KillsecService;
use App\services\soma\ScopeDiscountService;
use App\services\soma\WxService;
use App\services\soma\ExpressService;
use App\services\member\CenterService;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Package
 * @property  Product_package_model $productPackageModel
 * @property Activity_killsec_model $activityKillsecModel
 */
class Package extends MY_Front_Soma
{

    /**
     * 商城首页入口
     */
    public function index()
    {
        $this->handleDistribute();

        $this->package_list();
    }

    /**
     * 处理规格信息
     * @param $psp_setting
     * @return array
     * @author luguihong  <luguihong@mofly.cn>
     */
    protected function _get_setting_info($psp_setting)
    {
        $dataTicket = $data = array();
        //判断是否有门票类的
        $isTicket = false;
        foreach ($psp_setting as $row) {
            if ($row['type'] == Soma_base::STATUS_TRUE) {
                $data[$row['product_id']][] = $row;
            } elseif ($row['type'] == Soma_base::STATUS_FALSE) {
                $isTicket = true;
                $dataTicket[$row['product_id']][] = $row;
            }
        }

        if ($isTicket && $dataTicket) {
            $psp_setting = $dataTicket;
        } else {
            $psp_setting = $data;
        }

        $ticketId = '';
        if ($this->session->userdata('tkid')) {
            $ticketId = $this->session->userdata('tkid');
        }

        if (!$ticketId || $ticketId <= 0) {
            //如果获取不到门店的内容
            //$isTicket = FALSE;
            //$psp_setting = $data;
        }

        return array(
            'isTicket'    => $isTicket,
            'settingInfo' => $psp_setting,
        );
    }

    /**
     *  套票展示页面
     */
    public function package_list()
    {

        //$this->getTicketTheme();

        $header = $this->page_basic_config();
        $url = \App\libraries\Support\Url::current();

        //点击分享之后开启这些按钮
        $js_menu_show = array('menuItem:share:appMessage', 'menuItem:share:timeline');
        $uparams = $this->input->get() + array('id' => $this->inter_id);

        //取出分享配置
        $this->load->model('soma/Share_config_model', 'ShareConfigModel');
        $ShareConfigModel = $this->ShareConfigModel;
        $position = $ShareConfigModel::POSITION_DEFAULT;//分享类型
        $share_config_detail = $ShareConfigModel->get_share_config_list($position, $this->inter_id);

        // 分享标题双语翻译
        if ($this->langDir == self::LANG_DIR_EN) {
            if (!empty($share_config_detail['share_title_en']) && !empty($share_config_detail['share_desc_en'])) {
                $share_config_detail['share_title'] = $share_config_detail['share_title_en'];
                $share_config_detail['share_desc'] = $share_config_detail['share_desc_en'];
            }
        }

        $default_share_config = $this->get_default_sharing();

        $share_config = array(
            'title'  => isset($share_config_detail['share_title']) && !empty($share_config_detail['share_title']) ? $share_config_detail['share_title'] : $default_share_config['default_title'],
            'desc'   => isset($share_config_detail['share_desc']) && !empty($share_config_detail['share_desc']) ? $share_config_detail['share_desc'] : $default_share_config['default_desc'],
            'link'   => Soma_const_url::inst()->get_share_url($this->openid, '*/*/*', $uparams),
            'imgUrl' => isset($share_config_detail['share_img']) && !empty($share_config_detail['share_img']) ? $share_config_detail['share_img'] : $default_share_config['share_img'],
        );


        $this->getTicketTheme();


        $ticketList = array();
        $ticketId = $this->input->get('tkid');
        if ($ticketId) {
            $this->session->set_userdata('tkid', $ticketId);

            //获取产品id列表
            $serviceName = $this->serviceName(Product_Service::class);
            $serviceAlias = $this->serviceAlias(Product_Service::class);
            $this->load->service($serviceName, null, $serviceAlias);
            $catId = $this->input->get('catid');

            $info = $this->soma_product_service->getProductPackageTicketProductIds($ticketId, $catId);
            if (!$info) {
                die('没有找到该门店内容！');
            }

            $products = $info['products'];
            $ticketList = $info['ticketList'];

            //门店设置了皮肤
            $ticketDetail = current($ticketList);
            if (isset($ticketDetail['theme_path']) && $ticketDetail['theme_path']) {
                $this->theme = $ticketDetail['theme_path'];
            }

            $title = isset($ticketDetail['name']) ? $ticketDetail['name'] : '门店商品列表';

            $header = array(
                'title' => $title,
            );

            $this->headerDatas['title'] = $title;
        }

        if (!$this->isNewTheme()) {

            $is_show_navigation = isset($this->themeConfig['is_show_navigation']) ? $this->themeConfig['is_show_navigation'] : Soma_base::STATUS_FALSE;
            $is_show_lang_btn = isset($this->themeConfig['is_show_lang_btn']) ? $this->themeConfig['is_show_lang_btn'] : Soma_base::STATUS_FALSE;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


            $this->load->model('soma/Product_package_model', 'productModel');
            $productModel = $this->productModel;

            //advs
            $this->load->model('soma/Adv_model', 'ads_model');
            //首页广告图 cate:0
            $this->datas['advs'] = $this->ads_model->get_ads_by_category($this->inter_id);

            //获取酒店城市列表
            $this->load->model('hotel/hotel_model', 'HotelModel');
            $params = array(
                'inter_id' => $this->inter_id
            );
            $HotelModel = $this->HotelModel;
            $hotelCites = $HotelModel->get_hotel_hash($params, array('city', 'hotel_id'), 'array');
            $citesArr = $hotelsIds = array();

            foreach ($hotelCites as $v) {
                if (empty($v['city'])) {
                    continue;  //城市为空
                }

                if (in_array($v['city'], $citesArr)) {
                    continue;
                } else {
                    array_push($citesArr, $v['city']);
                }

                $hotelsIds[$v['hotel_id']] = $v['city'];
            }
            $filter_cat = $this->input->get('fcid');

            $this->load->model('soma/Category_package_model', 'categoryModel');
            $this->datas['categories'] = $this->categoryModel->get_package_category_list($this->inter_id, null, 5, $filter_cat);

            if (!$this->input->get('tkid')) {
                $this->session->set_userdata('tkid', '');
                $products = $this->productModel->get_product_package_list($filter_cat, $this->inter_id, null, null, false, true);

            }

            //$this->theme = 'v1';
            //$this->theme = 'mooncake4';

            $result = $productIds = $hotelsArr = $pointProductIds = array();
            foreach ($products as $k => $p) {
                //做过期处理过滤
                if ($p['goods_type'] != $productModel::SPEC_TYPE_TICKET && $p['date_type'] == $productModel::DATE_TYPE_STATIC) {
                    //固定有效期
                    $time = time();
                    $expireTime = isset($p['expiration_date']) ? strtotime($p['expiration_date']) : null;
                    if ($expireTime && $expireTime < $time) {
                        //如果已经过了有效期，停止本次循环，并在此列表删除该商品
                        // var_dump( $products[$k] );die;
                        unset($products[$k]);
                        continue;
                    }
                }

                if (isset($hotelsArr[$p['hotel_id']])) {
                    $hotelsArr[$p['hotel_id']]++;
                } else {
                    $hotelsArr[$p['hotel_id']] = 1;
                }

                //首页是否显示
                if ($p['is_hide'] == Soma_base::STATUS_TRUE) {
                    $productIds[] = $p['product_id'];
                    $result[$p['product_id']] = $p;
                    // $productCites = $HotelModel->get_hotel_hash(array('inter_id'=>$this->inter_id,'hotel_id'=> $p['hotel_id']),array('city'),'array');
                    // $result[$p['product_id']]['city'] = isset( $productCites[0]['city'] ) ? $productCites[0]['city'] : NULL;
                    $result[$p['product_id']]['city'] = isset($hotelsIds[$p['hotel_id']]) ? $hotelsIds[$p['hotel_id']] : '';


                    //如果是积分商品，去掉小数点，向上取整
                    if ($p['type'] == $productModel::PRODUCT_TYPE_POINT) {
                        $result[$p['product_id']]['price_package'] = ceil($p['price_package']);
                        $result[$p['product_id']]['price_market'] = ceil($p['price_market']);
                        $pointProductIds[] = $p['product_id'];
                        // var_dump( $result[$p['product_id']] );
                    }
                }
            }

            //拼团列表
            $this->load->model('soma/Activity_groupon_model', 'activityGrouponModel');
            $groupons = $this->activityGrouponModel->groupon_list_by_productIds($productIds, $this->inter_id);
            foreach ($groupons as $groupon) {
                if (in_array($groupon['product_id'], $pointProductIds)) {
                    $groupon['group_price'] = ceil($groupon['group_price']);
                }
                $result[$groupon['product_id']]['groupon'] = $groupon;

            }

            //秒杀列表
            $this->load->model('soma/Activity_killsec_model', 'activityKillsecModel');
            $killsecs = $this->activityKillsecModel->killsec_list_by_productIds($productIds, $this->inter_id);

            foreach ($killsecs as $killsec) {
                if (in_array($killsec['product_id'], $pointProductIds)) {
                    $killsec['killsec_price'] = ceil($killsec['killsec_price']);
                }
                //列表页显示秒杀倒计时
                $killsec['killsec_countdown'] = strtotime($killsec['killsec_time']) * 1000;

                /** 对秒杀开始时间进行处理 */
                $killsec['killsec_time'] = date('Y-m-d H:i:s', strtotime($killsec['killsec_time']) - Activity_killsec_model::PRESTART_TIME);
                $has = false;
                foreach ($result as $value){
                    if(isset($value['killsec']) && $value['killsec']['product_id'] == $killsec['product_id']){
                        $has = true;
                        break;
                    }
                }
                if($has === false){
                    $result[$killsec['product_id']]['killsec'] = $killsec;
                }
                else{
                    foreach ($result as $value){
                        if(isset($value['killsec']) && $value['killsec']['product_id'] == $killsec['product_id']){
                            if($value['killsec']['killsec_time'] > $killsec['killsec_time'] && $killsec['killsec_time'] > 0){
                                $result[$killsec['product_id']]['killsec'] = $killsec;
                            }
                            break;
                        }
                    }
                }
            }

            //满减活动
            $this->load->model('soma/Sales_rule_model', 'salesRuleModel');
            $rules = $this->salesRuleModel->get_product_rule($productIds, $this->inter_id);
            if ($rules) {
                foreach ($rules as $rule) {
                    if ($rule['scope'] == Soma_base::STATUS_TRUE) {
                        //全部适用
                        // 非满减规则过滤
                        $not_auto_rule_arr = array(Sales_rule_model::RULE_TYPE_POINT, Sales_rule_model::RULE_TYPE_BALENCE);
                        if (!in_array($rule['rule_type'], $not_auto_rule_arr)) {
                            foreach ($productIds as $v) {
                                if (!isset($result[$v]['auto_rule'])) {
                                    $result[$v]['auto_rule'] = $rule;
                                }
                            }
                        }
                    } else {
                        foreach ($rule['product_id'] as $rule_pid) {
                            $result[$rule_pid]['auto_rule'] = $rule;
                        }
                    }
                }
            }

            // 商品多规格,多规格商品显示最低的规格价格
            $this->load->model('soma/Product_specification_setting_model', 'psp_model');
            if ($productIds) {
                $psp_setting = $this->psp_model->get_inter_product_spec_setting($this->inter_id, $productIds);

                // var_dump($psp_setting);exit;
                if (!empty($psp_setting)) {

                    $tmp_setting = array();
                    foreach ($psp_setting as $row) {
                        $tmp_setting[$row['product_id']][] = $row;
                    }

                    foreach ($tmp_setting as $pid => $setting) {
                        $result[$pid]['psp_setting'] = $setting;
                        $result[$pid]['price_package'] = $setting[0]['spec_price'];
                    }
                }
            }

            $this->datas['products'] = $result;
            $this->datas['packageModel'] = $this->productModel;
            $this->datas['advs_url'] = Soma_const_url::inst()->get_package_detail() . '&pid=';



            $this->load->helper('soma/package');
            // write_log(json_encode( $share_config_detail ), 'share_config_detail.txt' );


            $my_order_url = Soma_const_url::inst()->get_url('soma/order/my_order_list/', array('id' => $this->inter_id, 'bsn' => 'package'));
            if ($this->theme == 'junting') {
                $this->load->library('Soma/Api_member');
                $api = new Api_member($this->inter_id);
                $result = $api->get_token();
                $result['data'] = isset($result['data']) ? $result['data'] : array();
                $api->set_token($result['data']);
                $result = $api->point_info($this->openid);
                $result['data'] = isset($result['data']) ? $result['data'] : '';
                $this->datas['point'] = $result['data'];
                // var_dump( $result['data'] );die;
            }

            //是否显示“附近”导航栏功能
            $this->datas['multi_hotel'] = count($hotelsArr) > 1 ? true : false;
            $this->datas['multi_city'] = count($citesArr) > 1 ? true : false;
            $this->datas['filter_cat'] = $filter_cat;

            $this->datas['cities'] = $citesArr;
            $this->datas['themeConfig'] = $this->themeConfig;
            $this->datas['my_order_url'] = $my_order_url;
            $this->datas['is_show_navigation'] = $is_show_navigation;//是否显示首页导航栏
            $this->datas['is_show_lang_btn'] = $is_show_lang_btn;//是否显示语言切换按钮
            $this->datas['ticketList'] = $ticketList;//门店列表信息
            $this->datas['ticketId'] = $ticketId;//门店列表信息
            $this->datas['zongzi_bg'] = $this->themeConfig['index_bg'];
            $this->datas['catId'] = $this->input->get('catid');
            $this->datas['theme'] = $this->themeConfig;

            // 双语翻译
            if ($this->langDir == self::LANG_DIR_EN) {
                // var_dump($this->datas['advs']);exit;
                $en_advs = $this->datas['advs'];
                foreach ($this->datas['advs'] as $key => $adv) {
                    if (!empty($adv->name_en)) {
                        $en_advs[$key]->name = $adv->name_en;
                    }
                }
                $this->datas['advs'] = $en_advs;

                $en_categories = $this->datas['categories'];
                foreach ($this->datas['categories'] as $key => $category) {
                    if (!empty($category['cat_name_en'])) {
                        $en_categories[$key]['cat_name'] = $category['cat_name_en'];
                    }
                }
                $this->datas['categories'] = $en_categories;
                // var_dump($this->datas['categories']);exit;

                $en_fields = $this->productModel->en_fields();
                $en_product_info = $this->productModel->getProductEnInfoList($productIds, $this->inter_id);
                $en_products = $this->datas['products'];
                foreach ($this->datas['products'] as $key => $product) {
                    if (isset($en_product_info[$product['product_id']])) {
                        foreach ($en_fields as $field) {
                            if (!empty($en_product_info[$product['product_id']][$field])) {
                                $en_products[$key][$field] = $en_product_info[$product['product_id']][$field];
                            }
                        }
                    }
                }
                $this->datas['products'] = $en_products;
            }

            if ($this->theme == 'zongzi' || $this->theme == 'mooncake4') {
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
                    || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                $member_url = "$protocol$_SERVER[HTTP_HOST]/index.php/membervip/center?id=" . $this->inter_id;
                $this->datas['member_url'] = $member_url;

                //是否是分销员，页面上显示分销员标志，只做zongzi皮肤
                $jsonStaff = $this->session->userdata('isSaler' . $this->inter_id . $this->openid);
                if (!$jsonStaff) {
                    //如果没有记录，就要发起一次分销员查询
                    $staff = $this->get_user_saler_or_fans_id();
                } else {
                    $staff = json_decode($jsonStaff, true);
                }

                //如果是泛分销员的去掉，不显示标示
                if (!isset($staff['saler_type']) || $staff['saler_type'] != 'STAFF') {
                    $staff = array();
                    $this->session->set_userdata('isSaler' . $this->inter_id . $this->openid, '');
                } else {
                    $this->session->set_userdata('isSaler' . $this->inter_id . $this->openid, json_encode($staff));
                }
                $this->datas['staff'] = $staff;
            }


            //获取用户头像
            if($this->theme == 'mooncake4'){
                $fan = $this->_get_wx_userinfo();
                isset($fan['headimgurl'])? $fan['headimgurl']: base_url('public/soma/images/ucenter_headimg.jpg');
                $this->datas['fan'] = $fan;
            }


            //给商品追加价格配置的东西
            ScopeDiscountService::getInstance()->appendScopeDiscount($this->datas['products'], $this->current_inter_id, $this->openid);

        }

        $this->datas['js_menu_show'] = $js_menu_show;
        $this->datas['js_share_config'] = $share_config;
        $this->datas['url'] = $url;

        $act_params = [
            'id' => $this->inter_id,
            'origin_url' => $url . '&fans_act=1',
        ];
        $this->datas['act_url'] = Soma_const_url::inst()->get_url('*/*/fans_saler_active', $act_params);

        $this->_view("header", $header);
        $this->_view('index', $this->datas);

    }

    /**
     *  套票展示页面
     */
    public function zongzi_index()
    {
        $this->handleDistribute();

        $catId = $this->input->get('catid');

        $this->theme = 'zongzi';
        $is_show_navigation = isset($this->themeConfig['is_show_navigation']) ? $this->themeConfig['is_show_navigation'] : Soma_base::STATUS_FALSE;
        $is_show_lang_btn = isset($this->themeConfig['is_show_lang_btn']) ? $this->themeConfig['is_show_lang_btn'] : Soma_base::STATUS_FALSE;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


        $this->load->model('soma/Product_package_model', 'productModel');
        $productModel = $this->productModel;

        //advs
        $this->load->model('soma/Adv_model', 'ads_model');
        //首页广告图 cate:0
        $this->datas['advs'] = $this->ads_model->get_ads_by_category($this->inter_id);

        //       $pageTitle = '套票'; //月饼说把标题换掉
        $header = $this->page_basic_config();

        //获取酒店城市列表
        $this->load->model('hotel/hotel_model', 'HotelModel');
        $params = array(
            'inter_id' => $this->inter_id
        );
        $HotelModel = $this->HotelModel;
        $hotelCites = $HotelModel->get_hotel_hash($params, array('city', 'hotel_id'), 'array');
        $citesArr = $hotelsIds = array();

        foreach ($hotelCites as $v) {
            if (empty($v['city'])) {
                continue;  //城市为空
            }

            if (in_array($v['city'], $citesArr)) {
                continue;
            } else {
                array_push($citesArr, $v['city']);
            }

            $hotelsIds[$v['hotel_id']] = $v['city'];
        }
        $filter_cat = $this->input->get('fcid');

        $this->load->model('soma/Category_package_model', 'categoryModel');
        $this->datas['categories'] = $this->categoryModel->get_package_category_list($this->inter_id, null, 5, $filter_cat);

        $ticketList = array();
        $ticketId = $this->input->get('tkid');
        if ($ticketId) {
            $this->session->set_userdata('tkid', $ticketId);

            //获取产品id列表
            $serviceName = $this->serviceName(Product_Service::class);
            $serviceAlias = $this->serviceAlias(Product_Service::class);
            $this->load->service($serviceName, null, $serviceAlias);
            $info = $this->soma_product_service->getProductPackageTicketProductIds($ticketId, $catId);
            if (!$info) {
                die('没有找到该门店内容！');
            }

            $products = $info['products'];
            $ticketList = $info['ticketList'];

            //门店设置了皮肤
            $ticketDetail = current($ticketList);
            if (isset($ticketDetail['theme_path']) && $ticketDetail['theme_path']) {
                //$this->theme = $ticketDetail['theme_path'];
            }

            $header = array(
                'title' => isset($ticketDetail['name']) ? $ticketDetail['name'] : '门店商品列表',
            );

        } else {
            $this->session->set_userdata('tkid', '');
            $products = $this->productModel->get_product_package_list($filter_cat, $this->inter_id, null, null, false, true);
        }

        $result = $productIds = $hotelsArr = $pointProductIds = array();
        foreach ($products as $k => $p) {
            //做过期处理过滤
            if ($p['goods_type'] != $productModel::SPEC_TYPE_TICKET && $p['date_type'] == $productModel::DATE_TYPE_STATIC) {
                //固定有效期
                $time = time();
                $expireTime = isset($p['expiration_date']) ? strtotime($p['expiration_date']) : null;
                if ($expireTime && $expireTime < $time) {
                    //如果已经过了有效期，停止本次循环，并在此列表删除该商品
                    // var_dump( $products[$k] );die;
                    unset($products[$k]);
                    continue;
                }
            }

            if (isset($hotelsArr[$p['hotel_id']])) {
                $hotelsArr[$p['hotel_id']]++;
            } else {
                $hotelsArr[$p['hotel_id']] = 1;
            }

            //首页是否显示
            if ($p['is_hide'] == Soma_base::STATUS_TRUE) {
                $productIds[] = $p['product_id'];
                $result[$p['product_id']] = $p;
                // $productCites = $HotelModel->get_hotel_hash(array('inter_id'=>$this->inter_id,'hotel_id'=> $p['hotel_id']),array('city'),'array');
                // $result[$p['product_id']]['city'] = isset( $productCites[0]['city'] ) ? $productCites[0]['city'] : NULL;
                $result[$p['product_id']]['city'] = isset($hotelsIds[$p['hotel_id']]) ? $hotelsIds[$p['hotel_id']] : '';


                //如果是积分商品，去掉小数点，向上取整
                if ($p['type'] == $productModel::PRODUCT_TYPE_POINT) {
                    $result[$p['product_id']]['price_package'] = ceil($p['price_package']);
                    $result[$p['product_id']]['price_market'] = ceil($p['price_market']);
                    $pointProductIds[] = $p['product_id'];
                    // var_dump( $result[$p['product_id']] );
                }
            }
        }

        //拼团列表
        $this->load->model('soma/Activity_groupon_model', 'activityGrouponModel');
        $groupons = $this->activityGrouponModel->groupon_list_by_productIds($productIds, $this->inter_id);
        foreach ($groupons as $groupon) {
            if (in_array($groupon['product_id'], $pointProductIds)) {
                $groupon['group_price'] = ceil($groupon['group_price']);
            }
            $result[$groupon['product_id']]['groupon'] = $groupon;

        }

        //秒杀列表
        $this->load->model('soma/Activity_killsec_model', 'activityKillsecModel');
        $killsecs = $this->activityKillsecModel->killsec_list_by_productIds($productIds, $this->inter_id);
        foreach ($killsecs as $killsec) {
            if (in_array($killsec['product_id'], $pointProductIds)) {
                $killsec['killsec_price'] = ceil($killsec['killsec_price']);
            }
            /** 对秒杀开始时间进行处理 */
            $killsec['killsec_time'] = date('Y-m-d H:i:s', strtotime($killsec['killsec_time']) - Activity_killsec_model::PRESTART_TIME);
            $result[$killsec['product_id']]['killsec'] = $killsec;
        }

        //满减活动
        $this->load->model('soma/Sales_rule_model', 'salesRuleModel');
        $rules = $this->salesRuleModel->get_product_rule($productIds, $this->inter_id);
        if ($rules) {
            foreach ($rules as $rule) {
                if ($rule['scope'] == Soma_base::STATUS_TRUE) {
                    //全部适用
                    // 非满减规则过滤
                    $not_auto_rule_arr = array(Sales_rule_model::RULE_TYPE_POINT, Sales_rule_model::RULE_TYPE_BALENCE);
                    if (!in_array($rule['rule_type'], $not_auto_rule_arr)) {
                        foreach ($productIds as $v) {
                            if (!isset($result[$v]['auto_rule'])) {
                                $result[$v]['auto_rule'] = $rule;
                            }
                        }
                    }
                } else {
                    foreach ($rule['product_id'] as $rule_pid) {
                        $result[$rule_pid]['auto_rule'] = $rule;
                    }
                }
            }
        }

        // 商品多规格,多规格商品显示最低的规格价格
        $this->load->model('soma/Product_specification_setting_model', 'psp_model');
        if ($productIds) {
            $psp_setting = $this->psp_model->get_inter_product_spec_setting($this->inter_id, $productIds);

            // var_dump($psp_setting);exit;
            if (!empty($psp_setting)) {

                $tmp_setting = array();
                foreach ($psp_setting as $row) {
                    $tmp_setting[$row['product_id']][] = $row;
                }

                foreach ($tmp_setting as $pid => $setting) {
                    $result[$pid]['psp_setting'] = $setting;
                    $result[$pid]['price_package'] = $setting[0]['spec_price'];
                }
            }
        }

        $this->datas['products'] = $result;
        $this->datas['packageModel'] = $this->productModel;
        $this->datas['advs_url'] = Soma_const_url::inst()->get_package_detail() . '&pid=';

        //点击分享之后开启这些按钮
        $js_menu_show = array('menuItem:share:appMessage', 'menuItem:share:timeline');
        $uparams = $this->input->get() + array('id' => $this->inter_id);

        //取出分享配置
        $this->load->model('soma/Share_config_model', 'ShareConfigModel');
        $ShareConfigModel = $this->ShareConfigModel;
        $position = $ShareConfigModel::POSITION_DEFAULT;//分享类型
        $share_config_detail = $ShareConfigModel->get_share_config_list($position, $this->inter_id);

        // 分享标题双语翻译
        if ($this->langDir == self::LANG_DIR_EN) {
            if (!empty($share_config_detail['share_title_en'])
                && !empty($share_config_detail['share_desc_en'])
            ) {
                $share_config_detail['share_title'] = $share_config_detail['share_title_en'];
                $share_config_detail['share_desc'] = $share_config_detail['share_desc_en'];
            }
        }

        $this->load->helper('soma/package');
        // write_log(json_encode( $share_config_detail ), 'share_config_detail.txt' );
        $default_share_config = $this->get_default_sharing();

        $share_config = array(
            'title'  => isset($share_config_detail['share_title']) && !empty($share_config_detail['share_title']) ? $share_config_detail['share_title'] : $default_share_config['default_title'],
            'desc'   => isset($share_config_detail['share_desc']) && !empty($share_config_detail['share_desc']) ? $share_config_detail['share_desc'] : $default_share_config['default_desc'],
            'link'   => Soma_const_url::inst()->get_share_url($this->openid, '*/*/*', $uparams),//$share_config_detail['share_link'],
            'imgUrl' => isset($share_config_detail['share_img']) && !empty($share_config_detail['share_img']) ? $share_config_detail['share_img'] : $default_share_config['share_img'],
        );

        $my_order_url = Soma_const_url::inst()->get_url('soma/order/my_order_list/', array('id' => $this->inter_id, 'bsn' => 'package'));
        if ($this->theme == 'junting') {
            $this->load->library('Soma/Api_member');
            $api = new Api_member($this->inter_id);
            $result = $api->get_token();
            $result['data'] = isset($result['data']) ? $result['data'] : array();
            $api->set_token($result['data']);
            $result = $api->point_info($this->openid);
            $result['data'] = isset($result['data']) ? $result['data'] : '';
            $this->datas['point'] = $result['data'];
            // var_dump( $result['data'] );die;
        }

        //是否显示“附近”导航栏功能
        $this->datas['multi_hotel'] = count($hotelsArr) > 1 ? true : false;
        $this->datas['multi_city'] = count($citesArr) > 1 ? true : false;
        $this->datas['filter_cat'] = $filter_cat;

        $this->datas['cities'] = $citesArr;
        $this->datas['js_menu_show'] = $js_menu_show;
        $this->datas['js_share_config'] = $share_config;
        $this->datas['themeConfig'] = $this->themeConfig;
        $this->datas['url'] = $url;
        $this->datas['my_order_url'] = $my_order_url;
        $this->datas['is_show_navigation'] = $is_show_navigation;//是否显示首页导航栏
        $this->datas['is_show_lang_btn'] = $is_show_lang_btn;//是否显示语言切换按钮
        $this->datas['ticketList'] = $ticketList;//门店列表信息
        $this->datas['ticketId'] = $ticketId;//门店列表信息
        $this->datas['zongzi_cat_bg'] = $this->themeConfig['cat_bg'];
        $this->datas['catId'] = $catId;

        // 双语翻译
        if ($this->langDir == self::LANG_DIR_EN) {
            // var_dump($this->datas['advs']);exit;
            $en_advs = $this->datas['advs'];
            foreach ($this->datas['advs'] as $key => $adv) {
                if (!empty($adv->name_en)) {
                    $en_advs[$key]->name = $adv->name_en;
                }
            }
            $this->datas['advs'] = $en_advs;

            $en_categories = $this->datas['categories'];
            foreach ($this->datas['categories'] as $key => $category) {
                if (!empty($category['cat_name_en'])) {
                    $en_categories[$key]['cat_name'] = $category['cat_name_en'];
                }
            }
            $this->datas['categories'] = $en_categories;
            // var_dump($this->datas['categories']);exit;

            $en_fields = $this->productModel->en_fields();
            $en_product_info = $this->productModel->getProductEnInfoList($productIds, $this->inter_id);
            $en_products = $this->datas['products'];
            foreach ($this->datas['products'] as $key => $product) {
                if (isset($en_product_info[$product['product_id']])) {
                    foreach ($en_fields as $field) {
                        if (!empty($en_product_info[$product['product_id']][$field])) {
                            $en_products[$key][$field] = $en_product_info[$product['product_id']][$field];
                        }
                    }
                }
            }
            $this->datas['products'] = $en_products;
        }

        //是否是分销员，页面上显示分销员标志，只做zongzi皮肤
        $jsonStaff = $this->session->userdata('isSaler' . $this->inter_id . $this->openid);
        if (!$jsonStaff) {
            //如果没有记录，就要发起一次分销员查询
            $staff = $this->get_user_saler_or_fans_id();
        } else {
            $staff = json_decode($jsonStaff, true);
        }

        //如果是泛分销员的去掉，不显示标示
        if (!isset($staff['saler_type']) || $staff['saler_type'] != 'STAFF') {
            $staff = array();
            $this->session->set_userdata('isSaler' . $this->inter_id . $this->openid, '');
        } else {
            $this->session->set_userdata('isSaler' . $this->inter_id . $this->openid, json_encode($staff));
        }
        $this->datas['staff'] = $staff;

        $this->_view("header", $header);
        $this->_view('search', $this->datas);
    }


    /**
     * 月饼说展示页面
     * @author: liguanglong  <liguanglong@mofly.cn>
     */
    public function mooncake4_index(){
        $header = $this->page_basic_config();
        $url = \App\libraries\Support\Url::current();

        //点击分享之后开启这些按钮
        $js_menu_show = array('menuItem:share:appMessage', 'menuItem:share:timeline');
        $uparams = $this->input->get() + array('id' => $this->inter_id);

        //取出分享配置
        $this->load->model('soma/Share_config_model', 'ShareConfigModel');
        $ShareConfigModel = $this->ShareConfigModel;
        $position = $ShareConfigModel::POSITION_DEFAULT;//分享类型
        $share_config_detail = $ShareConfigModel->get_share_config_list($position, $this->inter_id);

        // 分享标题双语翻译
        if ($this->langDir == self::LANG_DIR_EN) {
            if (!empty($share_config_detail['share_title_en']) && !empty($share_config_detail['share_desc_en'])) {
                $share_config_detail['share_title'] = $share_config_detail['share_title_en'];
                $share_config_detail['share_desc'] = $share_config_detail['share_desc_en'];
            }
        }

        $default_share_config = $this->get_default_sharing();

        $share_config = array(
            'title'  => isset($share_config_detail['share_title']) && !empty($share_config_detail['share_title']) ? $share_config_detail['share_title'] : $default_share_config['default_title'],
            'desc'   => isset($share_config_detail['share_desc']) && !empty($share_config_detail['share_desc']) ? $share_config_detail['share_desc'] : $default_share_config['default_desc'],
            'link'   => Soma_const_url::inst()->get_share_url($this->openid, '*/*/*', $uparams),
            'imgUrl' => isset($share_config_detail['share_img']) && !empty($share_config_detail['share_img']) ? $share_config_detail['share_img'] : $default_share_config['share_img'],
        );

        $this->getTicketTheme();

        if (!$this->isNewTheme()) {

            $is_show_navigation = isset($this->themeConfig['is_show_navigation']) ? $this->themeConfig['is_show_navigation'] : Soma_base::STATUS_FALSE;
            $is_show_lang_btn = isset($this->themeConfig['is_show_lang_btn']) ? $this->themeConfig['is_show_lang_btn'] : Soma_base::STATUS_FALSE;

            $this->load->model('soma/Product_package_model', 'productModel');
            $productModel = $this->productModel;

            //advs
            $this->load->model('soma/Adv_model', 'ads_model');
            //首页广告图 cate:0
            $this->datas['advs'] = $this->ads_model->get_ads_by_category($this->inter_id);

            //获取酒店城市列表
            $this->load->model('hotel/hotel_model', 'HotelModel');
            $params = array(
                'inter_id' => $this->inter_id
            );
            $HotelModel = $this->HotelModel;
            $hotelCites = $HotelModel->get_hotel_hash($params, array('city', 'hotel_id'), 'array');
            $citesArr = $hotelsIds = array();

            foreach ($hotelCites as $v) {
                if (empty($v['city'])) {
                    continue;  //城市为空
                }

                if (in_array($v['city'], $citesArr)) {
                    continue;
                } else {
                    array_push($citesArr, $v['city']);
                }

                $hotelsIds[$v['hotel_id']] = $v['city'];
            }
            $filter_cat = $this->input->get('fcid');

            $this->load->model('soma/Category_package_model', 'categoryModel');
            $this->datas['categories'] = $this->categoryModel->get_package_category_list($this->inter_id, null, 5, $filter_cat);

            $ticketList = array();
            $ticketId = $this->input->get('tkid');
            if ($ticketId) {
                $this->session->set_userdata('tkid', $ticketId);

                //获取产品id列表
                $serviceName = $this->serviceName(Product_Service::class);
                $serviceAlias = $this->serviceAlias(Product_Service::class);
                $this->load->service($serviceName, null, $serviceAlias);
                $catId = $this->input->get('catid');
                $info = $this->soma_product_service->getProductPackageTicketProductIds($ticketId, $catId);
                if (!$info) {
                    die('没有找到该门店内容！');
                }

                $products = $info['products'];
                $ticketList = $info['ticketList'];

                //门店设置了皮肤
                $ticketDetail = current($ticketList);
                if (isset($ticketDetail['theme_path']) && $ticketDetail['theme_path']) {
                    $this->theme = $ticketDetail['theme_path'];
                }

                $header = array(
                    'title' => isset($ticketDetail['name']) ? $ticketDetail['name'] : '门店商品列表',
                );

            } else {
                $this->session->set_userdata('tkid', '');
                $products = $this->productModel->get_product_package_list($filter_cat, $this->inter_id, null, null, false, true);
            }


            //$this->theme = 'v1';
           // $this->theme = 'mooncake4';

            $result = $productIds = $hotelsArr = $pointProductIds = array();
            foreach ($products as $k => $p) {
                //做过期处理过滤
                if ($p['goods_type'] != $productModel::SPEC_TYPE_TICKET && $p['date_type'] == $productModel::DATE_TYPE_STATIC) {
                    //固定有效期
                    $time = time();
                    $expireTime = isset($p['expiration_date']) ? strtotime($p['expiration_date']) : null;
                    if ($expireTime && $expireTime < $time) {
                        //如果已经过了有效期，停止本次循环，并在此列表删除该商品
                        // var_dump( $products[$k] );die;
                        unset($products[$k]);
                        continue;
                    }
                }

                if (isset($hotelsArr[$p['hotel_id']])) {
                    $hotelsArr[$p['hotel_id']]++;
                } else {
                    $hotelsArr[$p['hotel_id']] = 1;
                }

                //首页是否显示
                if ($p['is_hide'] == Soma_base::STATUS_TRUE) {
                    $productIds[] = $p['product_id'];
                    $result[$p['product_id']] = $p;
                    // $productCites = $HotelModel->get_hotel_hash(array('inter_id'=>$this->inter_id,'hotel_id'=> $p['hotel_id']),array('city'),'array');
                    // $result[$p['product_id']]['city'] = isset( $productCites[0]['city'] ) ? $productCites[0]['city'] : NULL;
                    $result[$p['product_id']]['city'] = isset($hotelsIds[$p['hotel_id']]) ? $hotelsIds[$p['hotel_id']] : '';


                    //如果是积分商品，去掉小数点，向上取整
                    if ($p['type'] == $productModel::PRODUCT_TYPE_POINT) {
                        $result[$p['product_id']]['price_package'] = ceil($p['price_package']);
                        $result[$p['product_id']]['price_market'] = ceil($p['price_market']);
                        $pointProductIds[] = $p['product_id'];
                        // var_dump( $result[$p['product_id']] );
                    }
                }
            }

            //拼团列表
            $this->load->model('soma/Activity_groupon_model', 'activityGrouponModel');
            $groupons = $this->activityGrouponModel->groupon_list_by_productIds($productIds, $this->inter_id);
            foreach ($groupons as $groupon) {
                if (in_array($groupon['product_id'], $pointProductIds)) {
                    $groupon['group_price'] = ceil($groupon['group_price']);
                }
                $result[$groupon['product_id']]['groupon'] = $groupon;

            }

            //秒杀列表
            $this->load->model('soma/Activity_killsec_model', 'activityKillsecModel');
            $killsecs = $this->activityKillsecModel->killsec_list_by_productIds($productIds, $this->inter_id);

            foreach ($killsecs as $killsec) {
                if (in_array($killsec['product_id'], $pointProductIds)) {
                    $killsec['killsec_price'] = ceil($killsec['killsec_price']);
                }
                //列表页显示秒杀倒计时
                $killsec['killsec_countdown'] = strtotime($killsec['killsec_time']) * 1000;

                /** 对秒杀开始时间进行处理 */
                $killsec['killsec_time'] = date('Y-m-d H:i:s', strtotime($killsec['killsec_time']) - Activity_killsec_model::PRESTART_TIME);
                $result[$killsec['product_id']]['killsec'] = $killsec;
            }

            //满减活动
            $this->load->model('soma/Sales_rule_model', 'salesRuleModel');
            $rules = $this->salesRuleModel->get_product_rule($productIds, $this->inter_id);
            if ($rules) {
                foreach ($rules as $rule) {
                    if ($rule['scope'] == Soma_base::STATUS_TRUE) {
                        //全部适用
                        // 非满减规则过滤
                        $not_auto_rule_arr = array(Sales_rule_model::RULE_TYPE_POINT, Sales_rule_model::RULE_TYPE_BALENCE);
                        if (!in_array($rule['rule_type'], $not_auto_rule_arr)) {
                            foreach ($productIds as $v) {
                                if (!isset($result[$v]['auto_rule'])) {
                                    $result[$v]['auto_rule'] = $rule;
                                }
                            }
                        }
                    } else {
                        foreach ($rule['product_id'] as $rule_pid) {
                            $result[$rule_pid]['auto_rule'] = $rule;
                        }
                    }
                }
            }

            // 商品多规格,多规格商品显示最低的规格价格
            $this->load->model('soma/Product_specification_setting_model', 'psp_model');
            if ($productIds) {
                $psp_setting = $this->psp_model->get_inter_product_spec_setting($this->inter_id, $productIds);

                // var_dump($psp_setting);exit;
                if (!empty($psp_setting)) {

                    $tmp_setting = array();
                    foreach ($psp_setting as $row) {
                        $tmp_setting[$row['product_id']][] = $row;
                    }

                    foreach ($tmp_setting as $pid => $setting) {
                        $result[$pid]['psp_setting'] = $setting;
                        $result[$pid]['price_package'] = $setting[0]['spec_price'];
                    }
                }
            }

            $this->datas['products'] = $result;
            $this->datas['packageModel'] = $this->productModel;
            $this->datas['advs_url'] = Soma_const_url::inst()->get_package_detail() . '&pid=';



            $this->load->helper('soma/package');
            // write_log(json_encode( $share_config_detail ), 'share_config_detail.txt' );


            $my_order_url = Soma_const_url::inst()->get_url('soma/order/my_order_list/', array('id' => $this->inter_id, 'bsn' => 'package'));
            if ($this->theme == 'junting') {
                $this->load->library('Soma/Api_member');
                $api = new Api_member($this->inter_id);
                $result = $api->get_token();
                $result['data'] = isset($result['data']) ? $result['data'] : array();
                $api->set_token($result['data']);
                $result = $api->point_info($this->openid);
                $result['data'] = isset($result['data']) ? $result['data'] : '';
                $this->datas['point'] = $result['data'];
                // var_dump( $result['data'] );die;
            }

            //是否显示“附近”导航栏功能
            $this->datas['multi_hotel'] = count($hotelsArr) > 1 ? true : false;
            $this->datas['multi_city'] = count($citesArr) > 1 ? true : false;
            $this->datas['filter_cat'] = $filter_cat;

            $this->datas['cities'] = $citesArr;
            $this->datas['themeConfig'] = $this->themeConfig;
            $this->datas['my_order_url'] = $my_order_url;
            $this->datas['is_show_navigation'] = $is_show_navigation;//是否显示首页导航栏
            $this->datas['is_show_lang_btn'] = $is_show_lang_btn;//是否显示语言切换按钮
            $this->datas['ticketList'] = $ticketList;//门店列表信息
            $this->datas['ticketId'] = $ticketId;//门店列表信息
            $this->datas['zongzi_bg'] = $this->themeConfig['index_bg'];
            $this->datas['catId'] = $this->input->get('catid');
            $this->datas['theme'] = $this->themeConfig;

            // 双语翻译
            if ($this->langDir == self::LANG_DIR_EN) {
                // var_dump($this->datas['advs']);exit;
                $en_advs = $this->datas['advs'];
                foreach ($this->datas['advs'] as $key => $adv) {
                    if (!empty($adv->name_en)) {
                        $en_advs[$key]->name = $adv->name_en;
                    }
                }
                $this->datas['advs'] = $en_advs;

                $en_categories = $this->datas['categories'];
                foreach ($this->datas['categories'] as $key => $category) {
                    if (!empty($category['cat_name_en'])) {
                        $en_categories[$key]['cat_name'] = $category['cat_name_en'];
                    }
                }
                $this->datas['categories'] = $en_categories;
                // var_dump($this->datas['categories']);exit;

                $en_fields = $this->productModel->en_fields();
                $en_product_info = $this->productModel->getProductEnInfoList($productIds, $this->inter_id);
                $en_products = $this->datas['products'];
                foreach ($this->datas['products'] as $key => $product) {
                    if (isset($en_product_info[$product['product_id']])) {
                        foreach ($en_fields as $field) {
                            if (!empty($en_product_info[$product['product_id']][$field])) {
                                $en_products[$key][$field] = $en_product_info[$product['product_id']][$field];
                            }
                        }
                    }
                }
                $this->datas['products'] = $en_products;
            }

            if ($this->theme == 'zongzi' || $this->theme == 'mooncake4') {
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
                    || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                $member_url = "$protocol$_SERVER[HTTP_HOST]/index.php/membervip/center?id=" . $this->inter_id;
                $this->datas['member_url'] = $member_url;

                //是否是分销员，页面上显示分销员标志，只做zongzi皮肤
                $jsonStaff = $this->session->userdata('isSaler' . $this->inter_id . $this->openid);
                if (!$jsonStaff) {
                    //如果没有记录，就要发起一次分销员查询
                    $staff = $this->get_user_saler_or_fans_id();
                } else {
                    $staff = json_decode($jsonStaff, true);
                }

                //如果是泛分销员的去掉，不显示标示
                if (!isset($staff['saler_type']) || $staff['saler_type'] != 'STAFF') {
                    $staff = array();
                    $this->session->set_userdata('isSaler' . $this->inter_id . $this->openid, '');
                } else {
                    $this->session->set_userdata('isSaler' . $this->inter_id . $this->openid, json_encode($staff));
                }
                $this->datas['staff'] = $staff;
            }


            //获取用户头像
            if($this->theme == 'mooncake4'){
                $fan = $this->_get_wx_userinfo();
                isset($fan['headimgurl'])? $fan['headimgurl']: base_url('public/soma/images/ucenter_headimg.jpg');
                $this->datas['fan'] = $fan;
            }


            //给商品追加价格配置的东西
            ScopeDiscountService::getInstance()->appendScopeDiscount($this->datas['products'], $this->current_inter_id, $this->openid);

        }

        $this->datas['js_menu_show'] = $js_menu_show;
        $this->datas['js_share_config'] = $share_config;
        $this->datas['url'] = $url;

        $act_params = [
            'id' => $this->inter_id,
            'origin_url' => $url . '&fans_act=1',
        ];
        $this->datas['act_url'] = Soma_const_url::inst()->get_url('*/*/fans_saler_active', $act_params);

        $this->_view("header", $header);
        $this->_view('search', $this->datas);
    }

    //分类、专题显示页面
    public function category_list()
    {
        $this->handleDistribute();

        $ticketId = '';
        if ($this->session->userdata('tkid')) {
            $ticketId = $this->session->userdata('tkid');
        }

        //门票皮肤没有详情页，先默认为v1皮肤的。ticket有自己的header头
        $themeArr = array(
            'ticket',
            'zongzi',
        );
        if (in_array($this->theme, $themeArr) || $ticketId) {
            $this->theme = 'v1';
        }

        $catId = $this->input->get('catid');

        $searchKey = $this->input->get('city');

        if (empty($catId) && empty($searchKey)) {
            return false;
        }

        $catId = intval($catId);
        if ($catId) {
            $serviceName = $this->serviceName(Category_Service::class);
            $serviceAlias = $this->serviceAlias(Category_Service::class);
            $this->load->service($serviceName, null, $serviceAlias);

            $categoryInfo = $this->soma_category_service->info($catId);

            if (!empty($categoryInfo)) {
                $title = $categoryInfo['cat_name'];
                if ($this->langDir == self::LANG_DIR_EN) {
                    $title = !empty($categoryInfo['cat_name_en']) ? $categoryInfo['cat_name_en'] : $categoryInfo['cat_name'];
                }
            }

        } elseif ($searchKey) {
            $title = $searchKey . '地区列表';
        } else {
            $title = 'not found';
        }

        $this->load->model('soma/Product_package_model', 'productPackageModel');
        $this->load->model('soma/Activity_groupon_model', 'activityGrouponModel');
        $this->load->model('soma/Activity_killsec_model', 'activityKillsecModel');

        $this->datas['packageModel'] = $this->productPackageModel;

        if (!empty($searchKey)) {
            $params = array(
                'inter_id'     => $this->inter_id,
                'product_city' => $searchKey
            );
        } else {
            $params = array(
                'inter_id' => $this->inter_id,
                'cat_id'   => $catId
            );
        }

        //点击分享之后开启这些按钮
        // $js_menu_show = array( 'menuItem:share:appMessage', 'menuItem:share:timeline' );
        $js_menu_show = array('menuItem:share:appMessage', 'menuItem:share:timeline');
        $uparams = $this->input->get() + array('id' => $this->inter_id);

        //取出分享配置
        $this->load->model('soma/Share_config_model', 'ShareConfigModel');
        $ShareConfigModel = $this->ShareConfigModel;
        $position = $ShareConfigModel::POSITION_DEFAULT;//分享类型
        $share_config_detail = $ShareConfigModel->get_share_config_list($position, $this->inter_id);

        // 分享标题双语翻译
        if ($this->langDir == self::LANG_DIR_EN) {
            if (!empty($share_config_detail['share_title_en'])
                && !empty($share_config_detail['share_desc_en'])
            ) {
                $share_config_detail['share_title'] = $share_config_detail['share_title_en'];
                $share_config_detail['share_desc'] = $share_config_detail['share_desc_en'];
            }
        }

        $default_share_config = $this->get_default_sharing();
        $share_config = array(
            'title'  => isset($share_config_detail['share_title']) && !empty($share_config_detail['share_title']) ? $share_config_detail['share_title'] : $default_share_config['default_title'],
            'desc'   => isset($share_config_detail['share_desc']) && !empty($share_config_detail['share_desc']) ? $share_config_detail['share_desc'] : $default_share_config['default_desc'],
            'link'   => Soma_const_url::inst()->get_share_url($this->openid, '*/*/*', $uparams),//$share_config_detail['share_link'],
            'imgUrl' => isset($share_config_detail['share_img']) && !empty($share_config_detail['share_img']) ? $share_config_detail['share_img'] : $default_share_config['share_img'],
        );


        $packages = $this->productPackageModel->get_package_list($this->inter_id, $params);
        $productModel = $this->productPackageModel;

        ScopeDiscountService::getInstance()->appendScopeDiscount($packages, $this->current_inter_id, $this->openid);

        // 商品多规格,多规格商品显示最低的规格价格
        $tmp_setting = [];
        if($packages){
            $this->load->model('soma/Product_specification_setting_model', 'psp_model');
            $psp_setting = $this->psp_model->get_inter_product_spec_setting($this->inter_id, array_column($packages, 'product_id'));
            if (!empty($psp_setting)) {
                foreach ($psp_setting as $row) {
                    $tmp_setting[$row['product_id']][] = $row;
                }
            }
        }


        $p_ids = array();
        foreach ($packages as $k => $p) {
            //做过期处理过滤
            if ($p['goods_type'] != $productModel::SPEC_TYPE_TICKET && $p['date_type'] == $productModel::DATE_TYPE_STATIC) {
                //固定有效期
                $time = time();
                $expireTime = isset($p['expiration_date']) ? strtotime($p['expiration_date']) : null;
                if ($expireTime && $expireTime < $time) {
                    //如果已经过了有效期，停止本次循环，并在此列表删除该商品
                    unset($packages[$k]);
                    continue;
                }
            }

            $p_ids[] = $p['product_id'];

            //如果是积分商品，去掉小数点，向上取整
            if ($p['type'] == $productModel::PRODUCT_TYPE_POINT) {
                $packages[$k]['price_package'] = ceil($p['price_package']);
                $packages[$k]['price_market'] = ceil($p['price_market']);
                // var_dump( $result[$p['product_id']] );
            }

            if(!empty($tmp_setting)){
                foreach ($tmp_setting as $key => $val){
                    if($p['product_id'] == $key){
                        $packages[$k]['price_package'] = $val[0]['spec_price'];
                        break;
                    }
                }
            }

        }

        $g_list = $this->activityGrouponModel->groupon_list_by_productIds($p_ids, $this->inter_id);
        $k_list = $this->activityKillsecModel->killsec_list_by_productIds($p_ids, $this->inter_id);

        $g_hash = $k_hash = array();
        foreach ($g_list as $row) {
            $g_hash[$row['product_id']] = $row;
        }
        foreach ($k_list as $row) {
            $k_hash[$row['product_id']] = $row;
        }

        $tmp = array();
        foreach ($packages as $k => $p) {
            if ($p['is_hide'] == Soma_base::STATUS_TRUE) {
                $tmp[$k] = $p;
                if (isset($g_hash[$p['product_id']])) {
                    $tmp[$k]['groupon'] = $g_hash[$p['product_id']];
                }
                if (isset($k_hash[$p['product_id']])) {
                    $tmp[$k]['killsec'] = $k_hash[$p['product_id']];
                }
            }
        }
        $packages = $tmp;


        /*
        $packages_new = array();
        foreach($packages as $k => $p){
          //首页是否显示
          if( $p['is_hide'] == Soma_base::STATUS_TRUE ){
            $packages_new[$k] = $p;
            $groupons = $this->activityGrouponModel->groupon_list($p['product_id']);
            if(!empty($groupons)){
                $packages_new[$k]['groupon'] = $groupons[0];
            }

            $killsecs = $this->activityKillsecModel->killsec_list_by_productIds(array($p['product_id']),$this->inter_id);
            if(!empty($killsecs)){
                $packages_new[$k]['killsec'] = $killsecs[0];
            }

          }
        }

        $packages = $packages_new;
        */

        $this->datas['packages'] = $packages;
        $this->datas['js_menu_show'] = $js_menu_show;
        $this->datas['js_share_config'] = $share_config;

        // 产品信息双语翻译
        if ($this->langDir == self::LANG_DIR_EN) {
            $en_fields = $this->productPackageModel->en_fields();
            $en_product_info = $this->productPackageModel->getProductEnInfoList($p_ids, $this->inter_id);
            $en_products = $this->datas['packages'];
            foreach ($this->datas['packages'] as $key => $product) {
                if (isset($en_product_info[$product['product_id']])) {
                    foreach ($en_fields as $field) {
                        if (!empty($en_product_info[$product['product_id']][$field])) {
                            $en_products[$key][$field] = $en_product_info[$product['product_id']][$field];
                        }
                    }
                }
            }
            $this->datas['packages'] = $en_products;
        }


        $header = array(
            'title' => $title
        );
        $this->_view("header", $header);
        $this->_view("search", $this->datas);
    }

    /* 兼容函数 */
    public function killsec_detail()
    {
        $this->package_detail();
    }

    public function killsec_pay()
    {
        $uparams = $this->input->get();
        $url = Soma_const_url::inst()->get_url('*/killsec/killsec_pay', $uparams);
    }

    /**
     * 套票详情页页面
     * 复合[default|groupon|killsec]三种类型页面判断
     */
    public function package_detail()
    {
        $productId = intval($this->input->get('pid'));
        if (empty($productId)) {
            show_404();
        }

        $this->load->model('soma/Product_package_model', 'productPackageModel');
        $productDetail = $this->productPackageModel->getByID($productId, $this->inter_id);
        if (empty($productDetail)) {
            show_404();
        }

        $this->handleDistribute();

        //点击分享之后开启这些按钮
        $js_menu_show = array('menuItem:share:appMessage', 'menuItem:share:timeline');
        $uparams = $this->input->get() + array('id' => $this->inter_id);
        $shareTitle = isset($productDetail['name']) && !empty($productDetail['name']) ? $productDetail['name'] : '';
        if ($shareTitle) {
            $replace = array(
                "<br>", "<Br>", "<br/>", "<Br/>", "<br />", "<Br />", "<b>", "</b>",
                "&lt;br&gt;", "&lt;Br&gt;", "&lt;br/&gt;", "&lt;Br/&gt;", "&lt;br /&gt;", "&lt;Br /&gt;", "&lt;b&gt;", "&lt;/b&gt;",
                "&#60;br&#62;", "&#60;Br&#62;", "&#60;br/&#62;", "&#60;Br/&#62;", "&#60;br /&#62;", "&#60;Br /&#62;", "&#60;b&#62;", "&#60;b/&#62;",
            );
            $shareTitle = str_replace($replace, '', htmlspecialchars($shareTitle));
        }
        $share_config = array(
            'title'  => $shareTitle ? $shareTitle : $this->lang->line('default_share_title'),//商品的标题
            'desc'   => isset($inter_id_name) && !empty($inter_id_name) ? $inter_id_name . $this->lang->line('recommend') : $this->lang->line('default_share_desc'),//酒店名+精品推荐
            'link'   => Soma_const_url::inst()->get_share_url($this->openid, '*/*/*', $uparams),
            'imgUrl' => isset($productDetail['face_img']) && !empty($productDetail['face_img']) ? $productDetail['face_img'] : base_url('public/soma/images/sharing_package.png'),//商品的logo
        );

        $this->getTicketTheme();

        if(!$this->isNewTheme()){
            $header = array('title' => $shareTitle);

            $isOff = $this->productPackageModel->isOff($productDetail);
            $productGallery = $this->productPackageModel->get_gallery_front($productId, $this->inter_id);

            if (is_null($isOff) || $isOff === true) {
                $header = array(
                    'title' => $this->lang->line('goods_off'),
                );
                $this->datas['gallery'] = $productGallery;
                $this->datas['packageModel'] = $this->productPackageModel;
                $this->datas['package'] = $productDetail;

                $this->_view("header", $header);
                $this->_view("offline", $this->datas);
                exit();
            }

            //门票皮肤没有详情页，先默认为v1皮肤的。ticket有自己的header头
            $ticketId = $this->session->userdata('tkid');
            if($this->theme != 'mooncake4'){
                $themeArr = array('ticket', 'zongzi');
                if(in_array($this->theme, $themeArr) || $ticketId){
                    //$this->theme = 'v1';
                }
            }


            //$this->theme = 'v1';
            //$this->theme = 'mooncake4';

            $bType = $this->input->get('bType');
            if ($bType) {
                $this->session->set_userdata('b_type', $bType);
            }

            $this->load->model('soma/Activity_groupon_model', 'grouponModel');

            //获取推荐位
            $uri = 'soma_package_package_detail';
            $block = $this->get_page_block($uri);

            $productModel = $this->productPackageModel;
            $is_expire = false;
            $this->datas['is_expire'] = $is_expire;

            //给商品追加价格配置的东西
            $products = array($productDetail);
            ScopeDiscountService::getInstance()->appendScopeDiscount($products, $this->current_inter_id, $this->openid, false);
            $productDetail = $products[0];

            //公众号名
            $publics = $this->public_info;
            $inter_id_name = '';
            if ($publics) {
                $inter_id_name = $publics['name'];
                if (!isset($publics['name'])) {
                    $publics['name'] = '';
                }
            }
            $this->datas['public'] = $publics;

            // 产品信息双语化
            if ($this->langDir == self::LANG_DIR_EN) {
                $en_info = $this->productPackageModel->getProductEnInfoList(array($productId), $this->inter_id);
                if (isset($en_info[$productId])) {
                    foreach ($this->productPackageModel->en_fields() as $field) {
                        if (!empty($en_info[$productId][$field])) {
                            $productDetail[$field] = $en_info[$productId][$field];
                        }
                    }
                }
            }

            $groupons = $this->grouponModel->groupon_list($productId);
            if ($groupons && count($groupons) > 1) {
                $groupons[0] = array_pop($groupons);
            }

            //秒杀相关
            $finish_killsec = false;
            $killsec = [];
            if (!$is_expire) {
                $killsec = KillsecService::getInstance()->getInfo($productId);
                if ($killsec) {
                    $finish_killsec = $killsec['finish'];
                    //月饼说皮肤：秒杀倒计时
                    $killsec['killsec_countdown'] = strtotime($killsec['killsec_time']) * 1000;
                }
            }

            //秒杀库存刷新频率
            $this->datas['stock_reflesh_rate'] = 10000;
            if (ENVIRONMENT === 'production') {
                $this->datas['stock_reflesh_rate'] = 60000;
            }

            //如果是积分商品，去掉小数点，向上取整
            if ($productDetail['type'] == $productModel::PRODUCT_TYPE_POINT) {
                $productDetail['price_package'] = ceil($productDetail['price_package']);
                $productDetail['price_market'] = ceil($productDetail['price_market']);
                if ($killsec) {
                    $killsec['killsec_price'] = ceil($killsec['killsec_price']);
                }
                if ($groupons) {
                    $groupons[0]['group_price'] = ceil($groupons[0]['group_price']);
                }
            }

            // 积分商品设置规则为空
            $this->datas['auto_rule'] = [];
            if ($productDetail['type'] != Product_package_model::PRODUCT_TYPE_POINT && empty($productDetail['scopes'])) {
                /** 促销规则加载 */
                $this->load->model('soma/Sales_rule_model');
                $auto_rule = $this->Sales_rule_model->get_product_rule(array($productId), $this->inter_id, 'auto_rule');
                $auto_rule_new = array();
                if ($auto_rule && count($auto_rule) > 0) {
                    foreach ($auto_rule as $v) {
                        $auto_rule_new[] = $v;
                    }
                }
                $this->datas['auto_rule'] = $auto_rule_new;

                // 活动规则双语
                if ($this->langDir == self::LANG_DIR_EN) {
                    $ar = $this->datas['auto_rule'];
                    foreach ($this->datas['auto_rule'] as $key => $r) {
                        if (!empty($r['name_en'])) {
                            $ar[$key]['name'] = $r['name_en'];
                        }
                    }
                    $this->datas['auto_rule'] = $ar;
                }
            }


            //加载多规格信息,页面显示价格为最低规格价
            $isTicket = false;
            if($productId) {
                $this->datas['spec_product'] = false;
                $this->load->model('soma/Product_specification_model', 'ps_info_model');
                $this->load->model('soma/Product_specification_setting_model', 'ps_detail_model');

                $ps_info = $this->ps_info_model->get_spec_list($this->inter_id, $productId);
                $ps_detail = $this->ps_detail_model->get_inter_product_spec_setting($this->inter_id, array($productId));
                if (!empty($ps_detail) && !empty($ps_info)) {

                    $settingList = $this->_get_setting_info($ps_detail);
                    $ps_detail = $settingList['settingInfo'];
                    $isTicket = $settingList['isTicket'];

                    //可能存在门票的时间规格，不存在多规格，且链接地址不带参数tkid
                    if ($ps_detail) {
                        $productDetail['price_package'] = $ps_detail[$productId][0]['spec_price'];

                        if ($isTicket) {
                            $this->datas['psp_summary'] = $ps_info[$productModel::SPEC_TYPE_TICKET];
                        } else {
                            $this->datas['psp_summary'] = $ps_info[$productModel::SPEC_TYPE_SCOPE];
                        }
                        $this->datas['psp_setting'] = $ps_detail;
                        $this->datas['spec_product'] = true;
                    }
                }
                // 规格信息双语翻译
                if($this->langDir == self::LANG_DIR_EN) {
                    if ($this->datas['spec_product'] && !$isTicket) {
                        $sp_en_compose = json_decode($this->datas['psp_summary']['spec_compose'], true);
                        $sp_label = $this->ps_info_model->get_sepc_type_label();
                        $sp_label_hash = array_flip($sp_label);
                        $sp_label_key = $this->ps_info_model->get_sepc_type_label_lang_ley();

                        foreach ($sp_en_compose['spec_type'] as $key => $type_name) {
                            if (isset($sp_label_hash[$type_name])) {
                                $sp_en_compose['spec_type'][$key] = $this->lang->line($sp_label_key[$sp_label_hash[$type_name]]);
                            }
                        }

                        $this->datas['psp_summary']['spec_compose'] = json_encode($sp_en_compose);
                    }
                }
            }

            // 加载销售总额，屏蔽从mongodb读取销量信息，沿用读取产品数据表，by fengzhongcheng
            // $this->load->model('soma/Statis_product_model', 'statis_model');
            // $productDetail['sales_cnt'] = $this->statis_model->get_product_total_sales_qty($productId);

            // 查询自身分销员信息
            $this->datas['saler_info'] = $this->get_user_saler_or_fans_id();
            // 查询适用分销规则信息
            $this->datas['effective_rule'] = array();
            $this->load->model('soma/Reward_rule_model', 'r_model');
            $rules = $this->r_model->getRewardRules($this->inter_id);
            if (!empty($rules) && !empty($this->datas['saler_info'])) {
                $effective_rule = false;
                // 同等优先级下，选择秒杀规则优先
                foreach ($rules as $rule) {
                    // 粉丝不显示 拼团不显示 规则设置不显示的不显示
                    if ($rule['reward_source'] == Reward_rule_model::REWARD_SOURCE_FIXED
                        || $rule['rule_type'] == Reward_rule_model::SETTLE_GROUPON
                        || $rule['can_show_hip'] == Reward_rule_model::STATUS_CAN_NO) {
                        continue;
                    }

                    // 检查产品是否符合分销规则，不符合不显示
                    if (!empty($rule['product_ids'])
                        && strpos($rule['product_ids'], $productId . '') === false) {
                        continue;
                    }

                    // 身份为泛分销员，规则不为泛分销规则不显示
                    if ($this->datas['saler_info']['saler_type'] == 'FANS'
                        && $rule['reward_source'] != Reward_rule_model::REWARD_SOURCE_FANS_SALER) {
                        continue;
                    }

                    // 身份为分销员，规则不为分销规则不显示
                    $saler_rule_source = array(
                        Reward_rule_model::REWARD_SOURCE_FIXED,
                        Reward_rule_model::REWARD_SOURCE_SALER
                    );
                    if ($this->datas['saler_info']['saler_type'] == 'STAFF'
                        && !in_array($rule['reward_source'], $saler_rule_source)) {
                        continue;
                    }

                    // 不存在秒杀时不显示秒杀规则
                    if(!$killsec && $rule['rule_type'] == Reward_rule_model::SETTLE_KILLSEC) {
                        continue;
                    }

                    // 第一条规则
                    if($effective_rule == false) {
                        $effective_rule = $rule;
                    }

                    // 秒杀优先
                    if ($killsec && $rule['sort'] == $effective_rule['sort']
                        && $rule['rule_type'] == Reward_rule_model::SETTLE_KILLSEC
                    ) {
                        $effective_rule = $rule;
                        break;
                    }
                }
                if ($effective_rule) {
                    if ($effective_rule['reward_type'] == Reward_rule_model::REWARD_TYPE_FIXED) {
                        // 固定金额保留两位小数
                        $effective_rule['reward_rate'] = round($effective_rule['reward_rate'], 2);
                    } else {
                        // 界面显示为百分比
                        $effective_rule['reward_rate'] = $effective_rule['reward_rate'] * 100;
                    }
                    $this->datas['effective_rule'] = $effective_rule;
                }
            }

            // 通过分销id查询分销员信息
            $this->datas['saler_info_by_id'] = false;
            $this->load->library('Soma/Api_idistribute');
            if ($saler_id = $this->input->get('saler', true)) {
                $this->datas['saler_info_by_id'] = $this->api_idistribute->getSalerInfoBySalerId($this->inter_id, $saler_id);
            }
            // 没有分销员信息就查泛分销员信息
            if ($this->datas['saler_info_by_id'] == false
                && $fans_saler_id = $this->input->get('fans_saler', true)) {
                $this->datas['saler_info_by_id'] = $this->api_idistribute->getFansSalerInfoBySalerId($this->inter_id, $fans_saler_id);
                if(!empty($this->datas['saler_info_by_id'])) {
                    $this->datas['saler_info_by_id']['name'] = $this->datas['saler_info_by_id']['nickname'];
                }
            }


            //粉丝
            $this->datas['fan'] = $this->_get_wx_userinfo();

            $this->datas['gallery'] = $productGallery;
            $this->datas['packageModel'] = $this->productPackageModel;
            $this->datas['package'] = $productDetail;
            $this->datas['groupons'] = $groupons;   //拼团
            $this->datas['killsec'] = $killsec;    //秒杀
            $this->datas['finish_killsec'] = $finish_killsec;    //秒杀

            $this->datas['block'] = $block;
            $this->datas['ticketId'] = $ticketId;
            $this->datas['isTicket'] = $isTicket;
            $this->datas['bType'] = $bType;
            $this->datas['theme'] = $this->themeConfig;

            // 泛分销域名被封时记得更换此处域名
            $this->datas['act_url'] = 'http://1.weimeids.com/index.php/soma/fans_saler/hsl_qrcode?v='.rand();
            $this->datas['idistributInterId'] = $this->idistributInterId;

            $this->_view("header", $header);
        }
        else{
            $this->headerDatas['title'] = '商品详情';

            //商品下架
            $isOff = $this->productPackageModel->isOff($productDetail);
            if (is_null($isOff) || $isOff === true) {
                $this->_view("header", array('title' => $shareTitle));
                $this->_view("offline");
                exit();
            }
        }

        $this->datas['js_menu_show'] = $js_menu_show;
        $this->datas['js_share_config'] = $share_config;
        $this->_view("package_detail", $this->datas);

    }


    /**
     * 根据购买清单拉取能用的优惠券
     */
    public function coupon_list_ajax()
    {
        /*format:  array('pid1'=>qty1, 'pid2'=>qty2, ) */
//        $product_hash= $this->input->post('p_arr');
//        $postArr = array(10016=>3400);
        $postArr = $this->input->post();
        $cardType = $postArr['card_type'] + 0;
        unset($postArr['card_type']);
        // var_dump( $postArr );die;
        foreach ($postArr as $pid => $qty) {
            $product_hash[] = $pid;
        }

        $this->load->model('soma/Product_package_model');
        $products = $this->Product_package_model->get_product_package_by_ids($product_hash, $this->inter_id);
        $subtotal = 0;
        if (!empty($products)) {
            foreach ($products as $k => $v) {
                //$proInfo[$v['product_id']]['price_package'] = $v['price_package'];
                $subtotal += $v['price_package'] * $postArr[$v['product_id']];  //累计订单总额
            }
        } else {
            $result = array(
                'status'  => Soma_base::STATUS_FALSE,
                'data'    => '',
                'message' => $this->lang->line('no_coupon_available')
            );
            echo json_encode($result);
            exit;
        }

        /** 读取购买人的可用券 ********************************/

        $this->load->library('Soma/Api_member');
        $api = new Api_member($this->inter_id);
        $result = $api->get_token();
        $api->set_token($result['data']);
        $result = $api->conpon_sign_list($this->openid);
        /**  ***********************/
        $card_ids = array();
        if (isset($result['data']) && count($result['data']) > 0) {
            $coupons = array();
            foreach ($result['data'] as $v) {
                if (!in_array($v->card_id, $card_ids)) {
                    $card_ids[] = $v->card_id;
                }
            }
            $this->load->model('soma/Sales_order_discount_model');
            $discountModel = $this->Sales_order_discount_model;
            $this->load->model('soma/Sales_coupon_model');
            $link_all = $this->Sales_coupon_model->get_coupon_product_list($card_ids, $this->inter_id);

            //取出适用所有商品的优惠券，格式：array('card_id'=>'券1',)
            $wide_scope_coupon = $this->Sales_coupon_model->get_wide_scope_coupon($this->inter_id, true);

            foreach ($result['data'] as $k => $v) {
                //逐张优惠券判断是否满足购物条件
                $tmp = (array)$v;

                if (array_key_exists($tmp['card_id'], $wide_scope_coupon)) {
                    if (isset($tmp['least_cost']) && $tmp['least_cost'] > $subtotal) {
                        $tmp['usable'] = false;

                    } else {
                        if (isset($tmp['over_limit']) && $tmp['over_limit'] > 0 && $tmp['over_limit'] < $subtotal) {
                            $tmp['usable'] = false;

                        } else {
                            $tmp['usable'] = true;  //该卡属于宽泛匹配卡id
                        }
                    }
                    $tmp['scopeType'] = $this->lang->line('all_goods_apply');

                } else {
                    foreach ($link_all as $sk => $sv) {
                        //匹配配置表中的各个配置商品，匹配到为止
                        if (isset($tmp['usable']) && $tmp['usable'] == true) {
                            continue;  //匹配到之后跳出不再循环匹配。
                        }

                        //已经配置了该卡券 && 配置的商品、数量 跟当前购物清单匹配
                        if (isset($tmp['least_cost']) && $tmp['least_cost'] > $subtotal) {
                            $tmp['usable'] = false;

                        } else {
                            if (isset($tmp['over_limit']) && $tmp['over_limit'] > 0 && $tmp['over_limit'] < $subtotal) {
                                $tmp['usable'] = false;

                            } else {
                                if ($sv['card_id'] == $tmp['card_id']
                                    && in_array($sv['product_id'], $product_hash)
                                    && $postArr[$sv['product_id']] >= $sv['qty']
                                ) {
                                    $tmp['usable'] = true;  //该卡满足配置和数量条件
                                    $tmp['scopeType'] = $this->lang->line('some_goods_apple');

                                } else {
                                    $tmp['usable'] = false;  //该卡不符合使用条件
                                    $tmp['scopeType'] = $this->lang->line('no_goods_can_apply');
                                }

                            }
                        }
                    }
                }

                //判断是否到了可用时间
                if (time() < $tmp['use_time_start']) {
                    $tmp['usable'] = false;  //该卡不符合使用条件,没有到使用时间
                }

                #######################################################
                // 跟会员组了解过
                // 券的过期时间设置是 2016-11-11 00:00:00，但是实际过期时间是2016-11-11 23:59:59
                $expire_date = date('Y-m-d', $tmp['expire_time']);
                $expire_time = strtotime($expire_date);
                if ($tmp['expire_time'] == $expire_time) {
                    $real_expire_date = $expire_date . ' 23:59:59';
                    $tmp['expire_time'] = strtotime($real_expire_date);
                }
                #######################################################

                $minusTime = $tmp['expire_time'] - time();
                if ($minusTime <= 0) {
                    continue;
                } elseif (($minusTime / 86400) <= 10) {
                    $tmp['expire_time'] = str_replace('[0]', ceil($minusTime / 86400), $this->lang->line('expire_after_some_days'));
                } else {
                    $tmp['expire_time'] = $this->lang->line('expire_at_') . '：' . date("Y-m-d", $tmp['expire_time']);
                }

                $coupons[] = $tmp;
            }

            //将不可用的券排到最后面
            $can_use_arr = array();
            foreach ($coupons as $k => $v) {
                if (isset($v['usable']) && $v['usable'] == true) {
                    $can_use_arr[] = $v;
                    unset($coupons[$k]);
                }
            }
            foreach ($can_use_arr as $k => $v) {
                array_unshift($coupons, $v);
            }

            //luguihong 20161107 把优惠券分成抵扣券、兑换券、折扣券
            $dj = array();
            $zk = array();
            $dh = array();
            $cz = array();
            foreach ($coupons as $k => $v) {
                if ($v['card_type'] == $discountModel::TYPE_COUPON_DJ) {
                    //代金券
                    $dj[] = $v;
                } elseif ($v['card_type'] == $discountModel::TYPE_COUPON_ZK) {
                    //折扣券
                    $zk[] = $v;
                } elseif ($v['card_type'] == $discountModel::TYPE_COUPON_DH) {
                    //兑换券
                    $dh[] = $v;
                } elseif ($v['card_type'] == $discountModel::TYPE_COUPON_CZ) {
                    //储值券
                    $cz[] = $v;
                }
            }

            if ($cardType == $discountModel::TYPE_COUPON_DJ) {
                //代金券
                $coupons = $dj;
            } elseif ($cardType == $discountModel::TYPE_COUPON_ZK) {
                //折扣券
                $coupons = $zk;
            } elseif ($cardType == $discountModel::TYPE_COUPON_DH) {
                //兑换券
                $coupons = $dh;
            } elseif ($cardType == $discountModel::TYPE_COUPON_CZ) {
                //储值券
                $coupons = $cz;
            }
            // var_dump( $subtotal, $coupons );die;

            $result = array(
                'status'  => Soma_base::STATUS_TRUE,
                'data'    => $coupons,
                // 'data'  => $coupons_new,
                'message' => ''
            );
        } else {
            $result = array(
                'status'  => Soma_base::STATUS_TRUE,
                'data'    => array(),
                'message' => '参数有误'
            );
        }

        echo json_encode($result);
    }

    /**
     * 优惠券金额计算
     */
    public function coupon_calulate_ajax()
    {
        $result = array('status' => 2, 'message' => '获取优惠券信息失败',);
        $member_card_id = $this->input->post('mcid');
        $pid = $this->input->post('product_id');
        $pqty = $this->input->post('qty');

        $this->load->library('Soma/Api_member');
        $api = new Api_member($this->inter_id);
        $result = $api->get_token();
        $api->set_token($result['data']);
        $result = $api->conpon_sign_info($member_card_id, $this->openid);
        $result = (array)$result['data'];

        if ($result) {
            $subtotal = 0;
            $this->load->model('soma/Product_package_model');
            $pids = array($pid);
            $products = $this->Product_package_model->get_product_package_by_ids($pids, $this->inter_id);
            $subtotal = 0;
            foreach ($products as $k => $v) {
                $products[$k]['qty'] = $pqty;
                $subtotal += $v['price_package'] * $pqty;
            }

            $this->load->model('soma/Sales_order_model');
            $this->load->model('soma/Sales_order_discount_model');
            $order = $this->Sales_order_model->subtotal = $subtotal;
            $total = $this->Sales_order_discount_model->calculate_discount($result, $products, Sales_order_discount_model::TYPE_COUPON, $order);
            //echo $total;
            //name优惠券名称
            //amount优惠金额
            //mcid
            //status状态1成功

            $result['name'] = $result['title'];
            $result['mcid'] = $result['member_card_id'];
            $result['amount'] = $total;
            $result['status'] = 1;
            $result['message'] = 'success';
        }
        echo json_encode($result);
    }

    /**
     * 异步拉取当前适用的优惠规则
     * 返回格式： array(
     * //立减活动
     * 'activity'=>array(
     * 'status' => 1 , //1:有,2没有
     * 'auto_rule' => array(
     * 'rule_type' =>
     * 'name'=>'已优惠￥100元',
     * 'reduce_cost' =>  100,
     * ’least_cost' => 50,
     * 'can_use_coupon' => 1
     * )
     * )
     * )
     * ,
     * //积分储值
     * 'asset'=> array(
     * 'status' => 1 , //1:有,2没有
     * 'cal_rule' => array(
     * 'rule_type' =>
     * 'quote' =>
     * 'reduce_cost' =>
     * 'can_use_coupon' => 1
     * )
     * )
     * );
     */
    public function discount_rule_ajax()
    {
        $return = array('status' => 2, 'message' => '获取OPENID失败', 'data' => array());
        if (!$this->openid || !$this->inter_id) {
            die(json_encode($return));
        }
        try {
            $settlement = $this->input->post('stl');
            $pid = $this->input->post('pid');
            $pqty = $this->input->post('qty');

            $this->load->model('soma/Product_package_model');
            $this->load->model('soma/Sales_rule_model');

            //为了复用方法，哎
            $pids = array($pid);
            $products = $this->Product_package_model->get_product_package_by_ids($pids, $this->inter_id);
            $subtotal = 0;

            ScopeDiscountService::getInstance()->appendScopeDiscount($products, $this->inter_id, $this->openid);

            $p_type = Product_package_model::PRODUCT_TYPE_DEFAULT;

            // 多规格，替换原来产品中的库存与价格
            $psp_sid = $this->input->post('sid', true);
            $this->load->model('soma/Product_specification_setting_model', 'psp_model');
            // foreach ($products as $key => $product) {
            //   if($psp_setting = $this->psp_model->load($psp_sid)) {
            //     $products[$key]['price_package'] = $psp_setting->m_get('spec_price');
            //   }
            // }
            if (!empty($products)
                && $psp_setting = $this->psp_model->load($psp_sid)
            ) {
                $products[0]['price_package'] = $psp_setting->m_get('spec_price');
            }

            foreach ($products as $k => $v) {
                $p_type = $v['type'];
                $products[$k]['qty'] = $pqty;
                $subtotal += $v['price_package'] * $pqty;
            }

            $rules = $this->Sales_rule_model->get_discount_rule($this->inter_id, $this->openid, $products, $subtotal, $settlement);
            /**
             * 返回格式
             * array(  //key相同为二选一显示, quote为使用额度，如5000积分，scale为比例值，least_cost为最低使用额，can_use_coupon为2指限制使用优惠券
             * 'auto_rule'=> array('rule_type'=>51, 'name'=>'xx满减活动', 'reduce_cost'=>'￥10.00', 'least_cost'=>'100.00', 'can_use_coupon'=>2 ),
             * 'auto_rule'=> array('rule_type'=>52, 'name'=>'xx满额打折', 'reduce_cost'=>'￥15.00', 'least_cost'=>'100.00', 'can_use_coupon'=>2 ),
             * 'auto_rule'=> array('rule_type'=>55, 'name'=>'xx随机立减', 'reduce_cost'=>'￥3.00', 'least_cost'=>'0', 'can_use_coupon'=>2 ),
             * 'cal_rule'=> array('rule_type'=>30, 'quote'=>'5000', 'reduce_cost'=>'￥50.00', 'can_use_coupon'=>1 ),
             * 'cal_rule'=> array('rule_type'=>40, 'quote'=>'100', 'reduce_cost'=>'￥100.00', 'can_use_coupon'=>1 ),
             * )
             */
            if (isset($rules['auto_rule'])) {
                $activity = array(
                    'status'    => Soma_base::STATUS_TRUE,
                    'auto_rule' => $rules['auto_rule'],
                );
            } else {
                $activity = array(
                    'status'    => Soma_base::STATUS_FALSE,
                    'auto_rule' => array(),
                );
            }

            if (isset($rules['cal_rule'])) {
                $asset = array(
                    'status'   => Soma_base::STATUS_TRUE,
                    'cal_rule' => $rules['cal_rule'],
                );
            } else {
                $asset = array(
                    'status'   => Soma_base::STATUS_FALSE,
                    'cal_rule' => array(),
                );

            }

            $return['message'] = 'success';
            $return['status'] = Soma_base::STATUS_TRUE;
            $return['data'] = array('activity' => $activity, 'asset' => $asset, 'base_info' => $rules['base_info']);

            if ($p_type == Product_package_model::PRODUCT_TYPE_POINT) {
                // 积分商品不使用任何规则
                $return['data'] = array();
            }

            if (isset($products[0]['scopes'])) {
                $return['data'] = [
                    'asset' => [
                        'status'   => Soma_base::STATUS_FALSE,
                        'cal_rule' => [
                            'can_use_coupon' => Soma_base::STATUS_FALSE
                        ]
                    ]
                ];
            }


            // var_dump($rules);exit;
            // 双语化翻译
            if ($this->langDir == self::LANG_DIR_EN) {

                if (!empty($return['data']['activity']['auto_rule']['name_en'])) {
                    $return['data']['activity']['auto_rule']['name'] = $return['data']['activity']['auto_rule']['name_en'];
                }

                if (isset($return['data']['asset']['cal_rule']['can_use'])
                    && $return['data']['asset']['cal_rule']['can_use'] == Soma_base::STATUS_FALSE
                ) {
                    if ($return['data']['asset']['cal_rule']['rule_type'] == Sales_rule_model::RULE_TYPE_BALENCE) {
                        $return['data']['asset']['cal_rule']['label'] = $this->lang->line('stored_value');
                    }
                    if ($return['data']['asset']['cal_rule']['rule_type'] == Sales_rule_model::RULE_TYPE_POINT) {
                        $return['data']['asset']['cal_rule']['label'] = $this->lang->line('point');
                    }
                }
                if (!isset($return['data']['asset']['cal_rule']['can_use'])
                    && !empty($return['data']['asset']['cal_rule']['name_en'])
                ) {
                    $return['data']['asset']['cal_rule']['name'] = $return['data']['asset']['cal_rule']['name_en'];
                } else {

                }
            }

            // var_dump($return);exit;

        } catch (Exception $e) {
            $return['status'] = Soma_base::STATUS_FALSE;
            $return['message'] = $e->getMessage();
            $return['data'] = $rules;
        }
        echo json_encode($return);
    }

    /**
     * 直接显示分销二维码
     */
    public function show_saler_qrcode()
    {
        $inter_id = $this->inter_id;
        $openid = $this->openid;
        $this->load->model('distribute/Staff_model');
        $staff = $this->Staff_model->get_my_base_info_openid($inter_id, $openid);
        if ($staff && $staff['qrcode_id']) {
            if ($inter_id) {
                $url = front_site_url($inter_id) . '/soma/package/index?id=' . $inter_id . '&saler=' . $staff['qrcode_id'];
                //echo $url;die;
                $this->_get_qrcode_png($url);

            } else {
                die('URL 格式错误');
            }
        } else {
            die('您还不是分销员');
        }
    }

    /**
     *  套票支付
     */
    public function package_pay()
    {
        $this->handleDistribute();

        $header = array('title' => $this->lang->line('purchase_payment'));

        //分享
        $js_menu_show = array('menuItem:share:appMessage', 'menuItem:share:timeline');
        $uparams = $this->input->get() + array('id' => $this->inter_id);
        //取出分享配置
        $this->load->model('soma/Share_config_model', 'ShareConfigModel');
        $ShareConfigModel = $this->ShareConfigModel;
        $position = $ShareConfigModel::POSITION_DEFAULT;//分享类型
        $share_config_detail = $ShareConfigModel->get_share_config_list($position, $this->inter_id);
        // 分享配置双语翻译
        if ($this->langDir == self::LANG_DIR_EN) {
            if (!empty($share_config_detail['share_title_en'])
                && !empty($share_config_detail['share_desc_en'])
            ) {
                $share_config_detail['share_title'] = $share_config_detail['share_title_en'];
                $share_config_detail['share_desc'] = $share_config_detail['share_desc_en'];
            }
        }

        $default_share_config = $this->get_default_sharing();
        $share_config = array(
            'title'  => isset($share_config_detail['share_title']) && !empty($share_config_detail['share_title']) ? $share_config_detail['share_title'] : $default_share_config['default_title'],
            'desc'   => isset($share_config_detail['share_desc']) && !empty($share_config_detail['share_desc']) ? $share_config_detail['share_desc'] : $default_share_config['default_desc'],
            'link'   => Soma_const_url::inst()->get_share_url($this->openid, '*/package/package_detail', $uparams),
            'imgUrl' => isset($share_config_detail['share_img']) && !empty($share_config_detail['share_img']) ? $share_config_detail['share_img'] : $default_share_config['share_img'],
        );

        //商品
        $productId = intval($this->input->get('pid'));
        if (empty($productId)) {
            return '';
        }
        $this->load->model('soma/Product_package_model', 'productPackageModel');
        $productDetail = $this->productPackageModel->get_product_package_detail_by_product_id($productId, $this->inter_id);

        //做过期处理过滤
        $productModel = $this->productPackageModel;
        $productDetail['type'] = isset($productDetail['type']) ? $productDetail['type'] : null;
        if ($productDetail['type'] == $productModel::PRODUCT_TYPE_BALANCE
            || $productDetail['type'] == $productModel::PRODUCT_TYPE_POINT
        ) {
            $this->check_member_is_login(Soma_const_url::inst()->get_url('*/*/*', $this->input->get()));

        }


        if($this->isNewTheme()){

            $this->headerDatas['title'] = '立即购买';

            //商品下架
            $isOff = false;
            if($productDetail){
                $isOff = $this->productPackageModel->isOff($productDetail);
            }
            if(empty($productDetail) || is_null($isOff) || $isOff === true){
                $this->_view("header");
                $this->_view("offline");
                exit();
            }

            //秒杀
            $back_url= Soma_const_url::inst()->get_url('*/package/package_detail',
                array('id'=> $this->inter_id, 'pid'=> $productId)
            );
            $act_id = intval($this->input->get('act_id'));
            $instance_id= $this->input->get('inid');
            $token = $this->input->get('token');
            if($act_id && $instance_id && $token){
                $this->load->model('soma/Activity_killsec_model','activityKillsecModel');
                $this->load->model('soma/Product_package_model','productPackageModel');
                $killsec = $this->activityKillsecModel->find( array('act_id'=> $act_id, 'inter_id'=> $this->inter_id));
                if($killsec){
                    $productDetail = $this->productPackageModel->get_product_package_detail_by_product_id($killsec['product_id'],$this->inter_id);
                    if(!$productDetail){
                        redirect($back_url);die;
                    }
                }
                $validResult = KillsecService::getInstance()->vaild($this->inter_id, $instance_id, $this->openid);
                if ($validResult->getStatus() === \App\services\Result::STATUS_FAIL) {
                    redirect($back_url);die;
                }
            }
        }


        if(!$this->isNewTheme()){
            $ticketId = '';
            if ($this->session->userdata('tkid')) {
                $ticketId = $this->session->userdata('tkid');
            }

            //门票皮肤、粽子皮肤没有支付页，先默认为v1皮肤的。ticket、zongzi都有自己的header头
            $themeArr = array(
                'ticket',
                'zongzi',
            );
            if (in_array($this->theme, $themeArr) || $ticketId) {
                $this->theme = 'v1';
            }

            if (!$productDetail) {
                $header = array(
                    'title' => $this->lang->line('goods_off'),
                );
                $this->_view("header", $header);
                $this->_view("offline", array('block' => ''));
                die;
            }

            // 产品信息双语化
            if ($this->langDir == self::LANG_DIR_EN) {
                $en_info = $this->productPackageModel->getProductEnInfoList(array($productId), $this->inter_id);
                if (isset($en_info[$productId])) {
                    foreach ($this->productPackageModel->en_fields() as $field) {
                        if (!empty($en_info[$productId][$field])) {
                            $productDetail[$field] = $en_info[$productId][$field];
                        }
                    }
                }
            }

            $is_expire = false;
            if ($productDetail['goods_type'] != $productModel::SPEC_TYPE_TICKET && $productDetail['date_type'] == $productModel::DATE_TYPE_STATIC) {
                $time = time();
                $expireTime = isset($productDetail['expiration_date']) ? strtotime($productDetail['expiration_date']) : null;
                if ($expireTime && $expireTime < $time) {
                    $is_expire = true;
                    //添加false条件，秒杀更新不涉及到快照功能，先屏蔽，2016-11-4 10:37:11，2016年11月7日11:08:02已重新开启
                    //商品已过期，就是商品下架了
                    $header = array(
                        'title' => $this->lang->line('goods_off'),
                    );
                    $this->_view("header", $header);
                    $this->_view("offline", array('block' => ''));
                    die;
                }
            }
            $this->datas['is_expire'] = $is_expire;

            //如果是积分商品，去掉小数点，向上取整
            if ($productDetail['type'] == $productModel::PRODUCT_TYPE_POINT) {
                $productDetail['price_package'] = ceil($productDetail['price_package']);
                $productDetail['price_market'] = ceil($productDetail['price_market']);
            }

            //取出联系人和电话
            $filter = array();
            $filter['openid'] = $this->openid;
            $customer_info = $this->productPackageModel->get_customer_contact($filter);
            $this->datas['customer_info'] = $customer_info;
            // var_dump( $customer_contact );exit;

            /** 读取购买人的可用券 ********************************/
            $this->load->library('Soma/Api_member');
            $api = new Api_member($this->inter_id);
            $result = $api->get_token();
            $result['data'] = isset($result['data']) ? $result['data'] : array();
            $api->set_token($result['data']);
            $result = $api->conpon_sign_list($this->openid);
            $result['data'] = isset($result['data']) ? $result['data'] : array();
            $this->datas['coupons'] = $result['data'];
            /**  ***********************/

            // 储值类型商品读取购买人的储值信息
            $this->datas['balance'] = null;
            if ($productDetail['type']
                && $productDetail['type'] == Product_package_model::PRODUCT_TYPE_BALANCE
            ) {
                $result = $api->get_token();
                $result['data'] = isset($result['data']) ? $result['data'] : array();
                $api->set_token($result['data']);
                $balance = $api->balence_info($this->openid);
                $balance['data'] = isset($balance['data']) ? $balance['data'] : 0;
                $this->datas['balance'] = $balance['data'];

                //购买储值链接
                // $this->load->model('wx/Publics_model','PublicsModel');
                // $publics= $this->PublicsModel->get_public_by_id($this->inter_id);
                // $balance_url = '';
                // if( $publics ){
                //   $balance_url = isset( $publics['domain'] ) ? 'http://' . $publics['domain']
                //                       . DS . 'index.php'
                //                       . DS . 'membervip'
                //                       . DS . 'depositcard'
                //                       . DS . 'buydeposit'
                //                       . DS . '?id='.$this->inter_id : '';
                // }
                // $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
                //  || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                // $balance_url = "$protocol$_SERVER[HTTP_HOST]/index.php/membervip/depositcard/buydeposit?id=".$this->inter_id;
                $this->datas['balance_url'] = $api->balence_deposit_url($this->inter_id);
            }

            // 积分商品拉取用户积分信息
            $this->datas['point'] = null;
            if ($productDetail['type']
                && $productDetail['type'] == Product_package_model::PRODUCT_TYPE_POINT
            ) {
                $result = $api->get_token();
                $result['data'] = isset($result['data']) ? $result['data'] : array();
                $api->set_token($result['data']);
                $point = $api->point_info($this->openid);
                $this->datas['point'] = isset($point['data']) ? $point['data'] : 0;
            }

            $this->load->helper('soma/time_calculate');

            $this->load->model('soma/Sales_rule_model');
            $this->load->model('soma/Sales_order_discount_model');
            $this->load->model('soma/Sales_order_model');

            $salesRuleModel = $this->Sales_rule_model;


            /** 根据rid规则ID参数确定应该默认买多少份 ********************************/
            $fix_rule = $salesRuleModel->find(array('rule_id' => $this->input->get('rid')));
            if ($fix_rule && $fix_rule['lease_cost'] && $productDetail['price_package']) {
                $fix_qty = $fix_rule['lease_cost'] / $productDetail['price_package'];
                if ($fix_qty < 1) {
                    $fix_qty = 1;
                } else {
                    if ($fix_qty > 1) {
                        $fix_qty = ceil($fix_qty);
                    } else {
                        $fix_qty = intval($fix_qty);
                    }
                }
                $this->datas['buy_default'] = $fix_qty > 200 ? 200 : $fix_qty;
            }
            /**  ***********************/


            $payParams = array('id' => $this->inter_id);

            $bType = $this->input->get('bType');
            if (!empty($bType)) {
                $payParams['bType'] = $bType;
            } else {
//            $bType = $this->session->userdata('b_type');
//            $this->session->set_userdata('b_type','');
            }

            $this->datas['bType'] = $bType;

            // 加载产品规格信息
            $psp_sid = $this->input->get('psp_sid', true);
            if ($psp_sid) {
                $this->load->model('soma/Product_specification_setting_model', 'psp_model');
                $psp_setting = $this->psp_model->get_specification_compose($this->inter_id, $productId, $psp_sid);
                if (!empty($psp_setting)) {
                    $setting_val = array_values($psp_setting);
                    $this->datas['psp_setting'] = $setting_val;
                    // 替换原产品价格信息
                    $productDetail['price_package'] = $setting_val[0]['specprice'];
                }
            }

            /**
             * 判断是否使用价格配置的价格
             * 如果使用的花就不能使用优惠券了
             */
            $scope_product_link = ScopeDiscountService::getInstance()->useScopeDiscount($this->inter_id, $this->openid, $productDetail, $psp_sid);
            $this->datas['scope_product_link'] = $scope_product_link;
            if (!empty($scope_product_link)) {
                $productDetail['price_package'] = $scope_product_link['price'];
            }

            /**
             * 邮寄
             */
            $defaultAddress = array();
            if( isset($productDetail['can_mail']) &&  $productDetail['can_mail'] == Product_package_model::CAN_T){
                $userAddressList = ExpressService::getInstance()->getUserAddressList($this->openid,$this->inter_id,100);
                if(!empty($userAddressList))
                    $defaultAddress = $userAddressList[0];

                $userAddress = json_encode($userAddressList);
            }else{
                $userAddress = "null";
            }


            /*
            1.选择时间，价格没有跟着变
            2.进入选择日期页面，立即购买按钮变灰，选择了时间才能点击
            3.可选的时间段内，且库存不为0的规格，才可以选择时间
            */
            // 产品规格信息加载完毕

            $this->datas['payParams'] = $payParams;
            $this->datas['userReduceObj'] = array('type' => $salesRuleModel::RULE_TYPE_POINT, 'total_amount' => 1, 'usable_amount' => 1);
            $this->datas['salesRuleModel'] = $salesRuleModel;
            $this->datas['discountModel'] = $this->Sales_order_discount_model;
            $this->datas['salesOrderModel'] = $this->Sales_order_model;
            $this->datas['packageModel'] = $this->productPackageModel;
            $this->datas['package'] = $productDetail;
            $this->datas['userAddress'] = $userAddress;
            $this->datas['defaultAddress'] = $defaultAddress;
            $this->datas['show_balance_passwd'] = Soma_base::STATUS_FALSE;
            $balance_inter_ids = array('a457946152', 'a471258436', 'a450089706');
            if (in_array($this->inter_id, $balance_inter_ids)) {
                $this->datas['show_balance_passwd'] = Soma_base::STATUS_TRUE;
            }

            /** 检测 自己saler_id ********************************/
            // 修改个人分销信息获取 fengzhongcheng
            // $saler_id= $this->_get_saler_id( $this->inter_id, $this->openid );
            // if($saler_id) $this->datas['saler_self'] = $saler_id;

            /*
            $saler_info = $this->_get_saler_id($this->inter_id, $this->openid);
            if ($saler_info)
            {
                $data_key = 'saler_self';
                if ($saler_info['typ'] == 'FANS')
                {
                    $data_key = 'fans_saler_self';
                }
                $this->datas[$data_key] = $saler_info['info']['saler'];
            }
            */

            $this->datas = $this->getDistribute($this->datas);

            /**  ***********************/
        }

        $this->datas['js_menu_show'] = $js_menu_show;
        $this->datas['js_share_config'] = $share_config;
        $this->_view("header", $header);
        $this->_view("package_pay", $this->datas);
    }

    /**
     *拼团支付
     */
    public function groupon_pay()
    {
        $this->handleDistribute();

        $ticketId = '';
        if ($this->session->userdata('tkid')) {
            $ticketId = $this->session->userdata('tkid');
        }

        //门票皮肤没有详情页，先默认为v1皮肤的。ticket有自己的header头
        $themeArr = array(
            'ticket',
            'zongzi',
        );
        if (in_array($this->theme, $themeArr) || $ticketId) {
            $this->theme = 'v1';
        }


        $this->_get_wx_userinfo();//获取用户头像

        $actId = $this->input->get('act_id');
        $this->load->model('soma/Activity_groupon_model', 'grouponModel');

        $grouponDetail = $this->grouponModel->groupon_detail($actId);

        $this->load->model('soma/Product_package_model', 'productPackageModel');

        $productDetail = $this->productPackageModel
            ->get_product_package_detail_by_product_id($grouponDetail['product_id'], $this->inter_id);
        if (!$productDetail) {
            $header = array(
                'title' => '商品下架',
            );
            $this->_view("header", $header);
            $this->_view("offline", array('block' => ''));
            die;
        }

        $productModel = $this->productPackageModel;
        $is_expire = false;
        if ($productDetail['goods_type'] != $productModel::SPEC_TYPE_TICKET && $productDetail['date_type'] == $productModel::DATE_TYPE_STATIC) {
            $time = time();
            $expireTime = isset($productDetail['expiration_date']) ? strtotime($productDetail['expiration_date']) : null;
            if ($expireTime && $expireTime < $time) {
                //商品已经过期，跳回原来页面
                $is_expire = true;
                $header = array(
                    'title' => $this->lang->line('goods_off'),
                );
                $this->_view("header", $header);
                $this->_view("offline", array('block' => ''));
                die;
            }
        }
        $this->datas['is_expire'] = $is_expire;

        $this->datas['packageModel'] = $this->productPackageModel;
        $this->datas['product'] = $productDetail;
        $this->datas['groupon'] = $grouponDetail;

        $header = array(
            'title' => $grouponDetail['act_name'] . "详情"
        );

        //点击分享之后开启这些按钮
        $js_menu_show = array('menuItem:share:appMessage', 'menuItem:share:timeline');
        $uparams = $this->input->get() + array('id' => $this->inter_id);
        $uparams['pid'] = $grouponDetail['product_id'];

        //取出分享配置
        $this->load->model('soma/Share_config_model', 'ShareConfigModel');
        $ShareConfigModel = $this->ShareConfigModel;
        $position = $ShareConfigModel::POSITION_DEFAULT;//分享类型
        $share_config_detail = $ShareConfigModel->get_share_config_list($position, $this->inter_id);
        $default_share_config = $this->get_default_sharing();
        $share_config = array(
            'title'  => isset($share_config_detail['share_title']) && !empty($share_config_detail['share_title']) ? $share_config_detail['share_title'] : $default_share_config['default_title'],
            'desc'   => isset($share_config_detail['share_desc']) && !empty($share_config_detail['share_desc']) ? $share_config_detail['share_desc'] : $default_share_config['default_desc'],
            'link'   => Soma_const_url::inst()->get_share_url($this->openid, '*/package/package_detail', $uparams),
            'imgUrl' => isset($share_config_detail['share_img']) && !empty($share_config_detail['share_img']) ? $share_config_detail['share_img'] : $default_share_config['share_img'],
        );

        $group_id = $this->input->get('grid');

        if ($group_id) {
            $this->datas['type'] = 'join';
        } else {
            $this->datas['type'] = 'add';
        }
        $this->datas['grid'] = $group_id;
        $this->datas['js_menu_show'] = $js_menu_show;
        $this->datas['js_share_config'] = $share_config;

        //取出联系人和电话
        $filter = array();
        $filter['openid'] = $this->openid;
        $customer_info = $this->productPackageModel->get_customer_contact($filter);
        $this->datas['customer_info'] = $customer_info;
        // var_dump( $customer_contact );exit;

        /** 检测 自己saler_id ********************************/
        // 修改个人分销信息获取 fengzhongcheng
        // $saler_id= $this->_get_saler_id( $this->inter_id, $this->openid );
        // if($saler_id) $this->datas['saler_self'] = $saler_id;

        /*
        $saler_info = $this->_get_saler_id($this->inter_id, $this->openid);
        if ($saler_info)
        {
            $data_key = 'saler_self';
            if ($saler_info['typ'] == 'FANS')
            {
                $data_key = 'fans_saler_self';
            }
            $this->datas[$data_key] = $saler_info['info']['saler'];
        }
        */

        $this->datas = $this->getDistribute($this->datas);
        /**  ***********************/

        $this->_view("header", $header);
        $this->_view("group_pay", $this->datas);
    }


    /**
     *获取附近套票
     */
    public function get_packages_nearby()
    {
        $lat = $this->input->post('lat');
        $lng = $this->input->post('lng');
        $products = array();
        if (empty($lat) || empty($lng)) {
            if (is_ajax_request()) {
                echo json_encode($products);
                exit;
            } else {
                return $products;
            }
        }

//        $lat = '23.136202'; //测试
//        $lng = '113.3291';  //测试
        $this->load->model('soma/Product_package_model', 'ProductPackageModel');
        $this->load->model('soma/Activity_groupon_model', 'activityGrouponModel');
        $products = $this->ProductPackageModel->get_packages_nearby($lat, $lng, '', $this->inter_id);
        $productModel = $this->ProductPackageModel;

        foreach ($products as $k => $p) {

            //首页是否显示
            if ($p['is_hide'] != Soma_base::STATUS_TRUE) {
                unset($products[$k]);
                continue;
            }

            if ($p['goods_type'] != $productModel::SPEC_TYPE_TICKET && $p['date_type'] == $productModel::DATE_TYPE_STATIC) {
                $time = time();
                $expireTime = isset($p['expiration_date']) ? strtotime($p['expiration_date']) : null;
                if ($expireTime && $expireTime < $time) {
                    unset($products[$k]);
                    continue;
                }
            }

            $groupons = $this->activityGrouponModel->groupon_list($p['product_id']);
            if (!empty($groupons)) {
                $products[$k]['groupon'] = $groupons[0];
            }
        }

//        print_r($result);
        if (is_ajax_request()) {

            //cdn
            if (isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV'] == 'production') {
                $search = array(
                    'file.iwide.cn',
                );
                $replace = array(
                    '7n.cdn.iwide.cn',
                );
            } else {
                $search = array(
                    '30.iwide.cn:821',
                );
                $replace = array(
                    'soma.cdn.iwide.cn',
                );
            }
            echo str_replace($search, $replace, json_encode($products));
            //echo $this->_replace_cdn_url(json_encode($products));
            // echo json_encode($products);
        } else {
            return $products;
        }

    }

    /**
     *
     * 支付成功一个停留页面
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function pay_success_stay()
    {

        $packageService = \App\services\soma\PackageService::getInstance();
        $layout = $packageService->getParams()['layout'];
        $tkId = $packageService->getParams()['tkid'];
        $brandName = $packageService->getParams()['brandname'];

        $this->datas = [];
        if (!$this->isNewTheme()) {
            $oid = $this->input->get('oid');

            if(empty($oid)) {
                show_404();
            }

            $this->load->model('soma/Sales_order_model', 'o_model');
            $order = $this->o_model->getByID($oid);

            if (empty($order)) {
                show_404();
            }

            $this->load->model('soma/Sales_item_package_model');
            $items = $this->Sales_item_package_model->get(
                [
                    'order_id'
                ],
                [
                    $oid
                ],
                'order_id, product_id'
            );
            if (empty($items) || empty($items[0])) {
                show_404();
            }

            $header = array(
                'title' => '购买成功'
            );
            if ($this->inter_id == 'a490782373') {
                $header['title'] = '申请成功';
            }

            $productID = $items[0]['product_id'];

            $params = [
                'oid' => $oid,
                'id' => $this->inter_id,
                'bsn' => $order['business'],
                'layout' => $layout,
                'tkid' => $tkId,
                'brandname' => $brandName,
            ];
            $orderDetailLink = Soma_const_url::inst()->get_url('soma/order/order_detail/', $params);
            $productDetailLink = Soma_const_url::inst()->get_url('soma/package/package_detail/', [
                'pid' => $productID, 'id' => $this->inter_id,
                'layout' => $layout,
                'tkid' => $tkId,
                'brandname' => $brandName,
            ]);

            $result = WxService::getInstance()->getQrcode(WxService::QR_CODE_KILLSEC_SUBSCRIBE);
            $this->load->model('wx/Fans_model', 'fansModel');
            $subscribeStatus = $this->fansModel->subscribeStatus($this->inter_id, $this->openid);

            //获取推荐位
            $uri = 'soma_package_package_detail';
            $block = $this->get_page_block($uri);


            $this->datas['block'] = $block;
            $this->datas['info'] = $this->public_info;
            $this->datas['order_detail_link'] = $orderDetailLink;
            $this->datas['product_detail_link'] = $productDetailLink;
            $this->datas['qr_code'] = $result->getData();
            $this->datas['subscribe_status'] = $subscribeStatus;

            $this->_view("header", $header);
        }

       $this->_view('pay_success_stay', $this->datas);

    }

    /**
     * 支付成功页面，过度方法
     *
     */
    public function success()
    {
        // 直播页面跳转
        if($zb_url = $this->input->get('zburl', true)) {
            $url = urldecode($zb_url);
            redirect($url);
        }

        //默认皮肤
        $this->theme = 'default';

        $header = array(
            'title' => '支付成功'
        );

        $packageService = \App\services\soma\PackageService::getInstance();
        $layout = $packageService->getParams()['layout'];
        $tkId = $packageService->getParams()['tkid'];
        $brandName = $packageService->getParams()['brandname'];

        $params = array(
            'id' => $this->inter_id,
            'layout' => $layout,
            'tkid' => $tkId,
            'brandname' => $brandName,
        );

        $settlement = $this->input->get('settlement');
        $order_id = $this->input->get('order_id');

        if ( ! empty($settlement) && $settlement == 'groupon') {
            $this->load->model('soma/Activity_groupon_model', 'activityGrouponModel');
            $grouponInfo = $this->activityGrouponModel->get_groupon_by_order_id($order_id, $this->inter_id);
            $grouponId = $grouponInfo['group_id'];

            $params['grid'] = $grouponId;

//            $params = array(
//                'id' => $this->inter_id,
//                'grid' => $grouponId
//
//            );
            $jumpLink = Soma_const_url::inst()->get_url('soma/groupon/groupon_detail/', $params);

        } else {

            $this->load->library('session');
            $u_type = $this->session->userdata('order_use_type');

            if ($u_type == "1") {
                // 送自己
                $params['oid'] = $order_id;
                $jumpLink = Soma_const_url::inst()->get_url('soma/order/self_use', $params);
            } else if ($u_type == "2") {
                // 送朋友

                $this->load->model('soma/Sales_order_model', 'o_model');
                $order = $this->o_model->load($order_id);

                $this->load->model('soma/Gift_order_model');
                $model = $this->Gift_order_model;
                $params['bsn'] = $order->m_get('business');
                $params['send_from'] = $model::SEND_FROM_ORDER;
                $params['send_order_id'] = $order_id;
                $params['oid'] = $order_id;
                $jumpLink = Soma_const_url::inst()->get_url('soma/gift/package_pre_send', $params);
            } else {
                $params['oid'] = $order_id;
                $jumpLink = Soma_const_url::inst()->get_url('soma/package/pay_success_stay', $params);
//                $jumpLink = Soma_const_url::inst()->get_url('soma/order/my_order_list/', $params);
            }

        }


        $this->datas['jumpLink'] = $jumpLink;
        $this->_view("header", $header);
        $this->_view('pay_success', $this->datas);
    }

    /**
     * //支付失败
     */
    public function fail()
    {
        $this->load->model('soma/activity_groupon_model');
        $GrouponModel = $this->activity_groupon_model;
        $settlement = $this->input->get('settlement');
        $order_id = $this->input->get('order_id');
        $inter_id = $this->input->get('id');
        if ($settlement == 'groupon')
        {
            $user = $GrouponModel->get_users_by_order_id($order_id, $inter_id);
            if (empty($user)) { //虚假订单或者用户
                redirect(Soma_const_url::inst()->get_pacakge_home_page());
            } else {

                if ($user['openid'] == $this->openid && $user['status'] == $GrouponModel::GROUP_ADD_STATUS_WAITING_PAY)
                {  //第一重验证,避免而已释放

                    $grouponDetail = $GrouponModel->get_groupon_by_order_id($order_id, $inter_id);
                    if ($grouponDetail['status'] == $GrouponModel::GROUP_STATUS_ING && $grouponDetail['join_count'] >= 2)
                    {   //第二充验证，验证是否满足释放条件
                        $GrouponModel->update_groupon_group_join($grouponDetail['group_id'], 'release', $inter_id);  //释放一个人数
                        $GrouponModel->groupon_user_update($grouponDetail['group_id'], $order_id, $this->openid, $GrouponModel::GROUP_ADD_STATUS_EXPIRATION, null, $inter_id); //改变用户状态

                    }
                }
                // echo "finished release";
            }

        }

        if ($order_id) {
            $this->load->model('soma/Sales_order_product_record_model', 'salesOrderProductRecordModel');
            $order = $this->salesOrderProductRecordModel->get('order_id', $order_id);

            if (!empty($order) && isset($order[0]))
                redirect(Soma_const_url::inst()->get_package_detail([
                    'id' => $inter_id,
                    'pid' => $order[0]['product_id']
                ]));
        }

        redirect(Soma_const_url::inst()->get_pacakge_home_page());
    }


    //展示为以后的皮肤做扩展
    protected function _view($file, $datas = array())
    {
        parent::_view('package' . DS . $file, $datas);
    }

    /**
     * Ajax 拉取商品列表HTML
     * @deprecated
     */
    public function page_block_ajax()
    {
        $current_url = $this->input->get('u');
        $filter = array('inter_id' => $this->inter_id);
        $this->load->model('soma/Cms_block_model');
        $this->load->model('soma/Product_package_model');
        $pids = $this->Cms_block_model->show_in_page($current_url, $filter);

        $products = array();
        if ($pids)
        {
            $products = $this->Product_package_model->get_product_package_by_ids($pids, $this->inter_id);
        }

        //获取酒店城市列表
        $this->load->model('hotel/hotel_model', 'HotelModel');
        $params = array(
            'inter_id' => $this->inter_id
        );
        $HotelModel = $this->HotelModel;

        foreach ($products as $k => $p)
        {
            $productCites = $HotelModel->get_hotel_hash(array('inter_id' => $this->inter_id, 'hotel_id' => $p['hotel_id']), array('city'), 'array');
            $products[$k]['city'] = isset($productCites[0]['city']) ? $productCites[0]['city'] : NULL;
        }

        //var_dump($pids);die;
        if ($pids && $products && count($products) > 0)
        {
            // 双语翻译
            if($this->langDir == self::LANG_DIR_EN)
            {
                $new_products = $products;
                $en_info = $this->productModel->getProductEnInfoList($pids, $this->inter_id);
                foreach($products as $key => $product)
                {
                    if(isset($en_info[$product['product_id']]))
                    {
                        foreach($this->productModel->en_fields() as $field)
                        {
                            if(!empty($en_info[$product['product_id']][$field]))
                            {
                                $new_products[$key][$field] = $en_info[$product['product_id']][$field];
                            }
                        }
                    }
                }
                $products = $new_products;
            }

            $html = '';
            if ($this->theme == 'default')
            {

                $html = '<div id="load_page_block" class="tp_list bgcolor_fff border martop"><div style="padding-bottom:3%;padding-left:3%; margin-bottom:3%" class="border_bottom h2">' . $this->lang->line('trending') . '</div>';
                foreach ($products as $k => $v)
                {
                    $url = Soma_const_url::inst()->get_url('*/*/package_detail', array('id' => $this->inter_id, 'pid' => $v['product_id']));
                    $can_gift = ($v['can_gift'] == Product_package_model::CAN_T) ? '<div class="fn"><span>' . $this->lang->line('send_friend') . '</span></div>' : '';
                    $default_pic = base_url('public/soma/images/default.jpg');
                    $html .=
                        "<a href='{$url}' class='item'>
  <div class='img'><img src='{$v['face_img']}' />{$can_gift}</div>
  <p class='txtclip'>{$v['name']}</p>
  <div class='foot h2'>
      <p class='color_fff m_bg tp_price'>
      	<span>" . $this->lang->line('surprise_offer') . "</span>
  		<span class='y'>{$v['price_package']}</span>
          <span class='m_bg2'>" . $this->lang->line('buy') . "<em class='iconfont'>&#xe61b;</em></span>
      </p>
      <p class='tp_local txtclip'>{$v['city']}</p>
  </div>
</a>";
                }
                $html .= '</div>';

            } elseif ($this->theme == 'v1')
            {
                $is_odd = (count($products) % 2) > 0;
                if ($is_odd) array_pop($products);

                $html = '<link href="' . base_url("public/soma/v1/v1.css") . config_item("css_debug") . '" rel="stylesheet">
                <div id="load_page_block" class="tp_list bgcolor_fff border martop"><div style="padding-bottom:3%;padding-left:3%; margin-bottom:3%" class="border_bottom h2">' . $this->lang->line('trending') . '</div>';
                foreach ($products as $k => $v)
                {
                    $url = Soma_const_url::inst()->get_url('*/*/package_detail', array('id' => $this->inter_id, 'pid' => $v['product_id']));
                    $default_pic = base_url('public/soma/images/default.jpg');
                    $html .=
                        "<a href='{$url}' class='item bg_fff'>
  <div class='img'>
      <img src='{$v['face_img']}' />
  </div>
  <p class='h3 color_888'>{$v['name']}</p>
  <p class='item_foot'>" . $this->lang->line('surprise_offer') . "<em>|</em><span class='color_main y'>{$v['price_package']}</span></p>
</a>";
                }
                $html .= '</div>';
            }
            echo $html;

        } else
        {
            echo '';

        }
    }


    /**
     * 月饼说皮肤配置
     */
    public function page_basic_config($pageTitle = NULL)
    {
        $title = '';
        $themeConfig = $this->themeConfig;

        $public_info = $this->Publics_model->get_public_by_id($this->inter_id);
        $_prefix = isset($public_info['name']) ? $public_info['name'] . '-' : '';

        if ( ! empty($themeConfig))
        {
            if (isset($themeConfig['theme_title']) && ! empty($themeConfig['theme_title']) && empty($pageTitle))
            {
                if (defined('PROJECT_AREA') && PROJECT_AREA == 'mooncake')
                {
                    $title = $_prefix . '月饼说 - ' . $themeConfig['theme_title'];
                } else
                {
                    $title = $this->lang->line('title') .' - ' . $themeConfig['theme_title'];
                }
            }
            if (empty($header))
            {
                if (defined('PROJECT_AREA') && PROJECT_AREA == 'mooncake')
                {
                    $title = $pageTitle ? $pageTitle : $_prefix . '月饼说';
                } else
                {
                    $title = $pageTitle ? $pageTitle : $this->lang->line('title');
                }
            }
            $header['title'] = $title; //$pageTitle;
            if (isset($themeConfig['main_color']) && ! empty($themeConfig['main_color']))
                $header['main_color'] = $themeConfig['main_color'];
            if (isset($themeConfig['sub_color']) && ! empty($themeConfig['sub_color']))
                $header['sub_color'] = $themeConfig['sub_color'];
        }
        return $header;
    }

    /**
     * 月饼说主页
     */
    public function mooncake_list()
    {
        $header = $this->page_basic_config();
        $filter_cat = $this->input->get('fcid');
        $this->load->model('soma/Product_package_model', 'productModel');
        $products = $this->productModel->$this->productModel->get_product_package_list($filter_cat, $this->inter_id, null, null, false, true);

        $result = $productIds = array();
        foreach ($products as $p)
        {
            if ($p['is_hide'] == Soma_base::STATUS_TRUE)
            {
                $productIds[] = $p['product_id'];
                $result[$p['product_id']] = $p;
            }
        }

        //拼团列表
        $this->load->model('soma/Activity_groupon_model', 'activityGrouponModel');
        $groupons = $this->activityGrouponModel->groupon_list_by_productIds($productIds, $this->inter_id);
        foreach ($groupons as $groupon)
        {
            $result[$groupon['product_id']]['groupon'] = $groupon;
        }

        //秒杀列表
        $this->load->model('soma/Activity_killsec_model', 'activityKillsecModel');
        $killsecs = $this->activityKillsecModel->killsec_list_by_productIds($productIds, $this->inter_id);
        foreach ($killsecs as $killsec)
        {
            /** 对秒杀开始时间进行处理 */
            $killsec['killsec_time'] = date('Y-m-d H:i:s', strtotime($killsec['killsec_time']) - Activity_killsec_model::PRESTART_TIME);
            $result[$killsec['product_id']]['killsec'] = $killsec;
        }

        $this->datas['products'] = $result;
        $this->datas['packageModel'] = $this->productModel;

        //点击分享之后开启这些按钮
        $js_menu_show = array('menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:copyUrl');
        $uparams = $this->input->get() + array('id' => $this->inter_id);

        //取出分享配置
        $this->load->model('soma/Share_config_model', 'ShareConfigModel');
        $ShareConfigModel = $this->ShareConfigModel;
        $position = $ShareConfigModel::POSITION_DEFAULT;//分享类型
        $share_config_detail = $ShareConfigModel->get_share_config_list($position, $this->inter_id);
        $this->load->helper('soma/package');
        // write_log(json_encode( $share_config_detail ), 'share_config_detail.txt' );
        $default_share_config = $this->get_default_sharing();
        $share_config = array(
            'title' => isset($share_config_detail['share_title']) && ! empty($share_config_detail['share_title']) ?
                $share_config_detail['share_title'] : $default_share_config['default_title'],
            'desc' => isset($share_config_detail['share_desc']) && ! empty($share_config_detail['share_desc']) ?
                $share_config_detail['share_desc'] : $default_share_config['default_desc'],
            'link' => Soma_const_url::inst()->get_share_url($this->openid, '*/*/*', $uparams),//$share_config_detail['share_link'],
            'imgUrl' => isset($share_config_detail['share_img']) && ! empty($share_config_detail['share_img']) ?
                $share_config_detail['share_img'] : $default_share_config['share_img'],
        );

        $this->datas['filter_cat'] = $filter_cat;
        $this->datas['js_menu_show'] = $js_menu_show;
        $this->datas['js_share_config'] = $share_config;
        $this->datas['themeConfig'] = $this->themeConfig;
        // var_dump($this->themeConfig);exit;

        $this->_view("header", $header);
        $this->_view('search', $this->datas);

    }

    public function get_default_sharing()
    {
        if (defined('PROJECT_AREA') && PROJECT_AREA == 'mooncake')
        {
            $share_img = base_url('public/soma/images/sharing_mooncake.png');
            $default_title = '月饼说，送您一份中秋好礼物';
            $default_desc = '微信送礼更有趣';
        } else
        {
            $share_img = base_url('public/soma/images/sharing_package.png');
            // $default_title = '发现一家好去处，快点开看看';
            // $default_desc = '优惠不等人';
            // 根据运行环境进行双语翻译
            $default_title = $this->lang->line('default_share_title');
            $default_desc = $this->lang->line('default_share_desc');
        }

        $default_share_config = array(
            'share_img' => $share_img,
            'default_title' => $default_title,
            'default_desc' => $default_desc,
        );
        return $default_share_config;
    }

    //luguihong 20160818 异步查询分销号，如果是分销员在页面弹窗
    public function get_saler_id_by_ajax()
    {

        $return['status'] = Soma_base::STATUS_FALSE;
        $return['message'] = '此接口作废';
        echo json_encode($return);
        exit;

        $saler = $this->input->post('saler');

        $return = array();
        $this->load->model('distribute/Staff_model');
        $staff = $this->Staff_model->get_my_base_info_openid($this->inter_id, $this->openid);
        if ($staff && $staff['qrcode_id'])
        {

            //查询链接携带的分销ID
            //$url_staff = $this->Staff_model->get_my_base_info_saler( $this->inter_id, $saler );
            $url_staff = $staff;

            $return['status'] = Soma_base::STATUS_TRUE;
            $return['message'] = '该用户是分销员';
            $return['sid'] = isset($url_staff['qrcode_id']) ? $url_staff['qrcode_id'] : '';
            $return['name'] = isset($url_staff['name']) ? $url_staff['name'] : '';

            if (empty($saler))
            {
                //1,链接无saler,[跳转]
                $return['jump_url'] = Soma_base::STATUS_TRUE;
                $return['show_button'] = Soma_base::STATUS_FALSE;

            } else if ($saler != $url_staff['qrcode_id'])
            {
                //2,链接有saler,但与本人不符合,[跳转]
                $return['jump_url'] = Soma_base::STATUS_TRUE;
                $return['show_button'] = Soma_base::STATUS_FALSE;

            } else if ($saler == $url_staff['qrcode_id'])
            {
                //3,连接有saler,并且符合,[显示角标]
                $return['jump_url'] = Soma_base::STATUS_FALSE;
                $return['show_button'] = Soma_base::STATUS_TRUE;

            } else
            {
                $return['jump_url'] = Soma_base::STATUS_FALSE;
                $return['show_button'] = Soma_base::STATUS_FALSE;
            }
            $return['url'] = Soma_const_url::inst()->get_url('distribute/dis_v1/mine', array('id' => $this->inter_id));

        } else
        {
            $return['status'] = Soma_base::STATUS_FALSE;
            $return['message'] = '该用户不是分销员';

        }
        echo json_encode($return);
    }

    public function get_lvl_info_ajax()
    {
        $result = array('status' => Soma_base::STATUS_FALSE, 'message' => '会员身份识别有误，积分暂不能使用');

        $this->load->library('Soma/Api_member');
        $api = new Api_member($this->inter_id);
        $result = $api->get_token();
        $api->set_token($result['data']);
        $result = $api->member_lv_info($this->openid);

        if (isset($lvl_info['data']['member_lvl_id']) && $lvl_info['data']['member_lvl_id'])
        {
            $result['status'] = Soma_base::STATUS_TRUE;
            $result['message'] = 'lvl_id:' . $lvl_info['data']['member_lvl_id'];
        }
        echo json_encode($result);
    }

    /**
     * 泛分销激活页面
     */
    public function fans_saler_active()
    {
        $this->_get_wx_userinfo();
        $header['title'] = '泛分销信息激活';

        // $rtn_url = Soma_const_url::inst()->get_url('*/*/index', array('id' => $this->inter_id));

        // $t = base64_encode(urlencode($rtn_url) . '***' . $this->openid . '***' . $this->inter_id);
        // $params = array('id' => $this->inter_id, 't' => $t);
        // $act_url = Soma_const_url::inst()->get_url('distribute/dis_ext/act_confirm', $params);

        // $this->datas['rtn_url'] = $rtn_url;
        // $this->datas['act_url'] = $act_url;
        // $this->datas['not_allow_hint'] = false;

        // // 是分销员不允许激活
        // $staff = $this->get_user_saler_or_fans_id();
        // if (!empty($staff)) {
        //     $this->datas['not_allow_hint'] = true; 
        // }
        // // $this->datas['t'] = base64_encode(urlencode($rtn_url).'***'.$this->openid.'***'.$this->inter_id);

        $this->datas['origin_url'] = urldecode($this->input->get('origin_url', true));


        $this->_view("fans_saler_header", $header);
        $this->_view('fans_saler_active', $this->datas);
    }

    //首页ajax加载分页
    public function ajax_get_product_list()
    {

        $return = array('status' => Soma_base::STATUS_FALSE, 'data' => array(), 'msg' => '');

        $ticketId = $this->input->get('tkid');
        if( $ticketId )
        {
            //如果是门店的，没有分页加载
            $return['msg'] = '查找数据为空！';
            echo json_encode( $return );die;
        }

        $inter_id = $this->inter_id;

        $limit = 20;
        $page = $this->input->post('p');
        if ( ! $page || $page < 1)
        {
            $page = 1;
        } else
        {
            $page = $page + 1;
        }
        $offset = $limit * $page;

        $this->load->model('soma/Product_package_model', 'ProductModel');
        $productModel = $this->ProductModel;

        $filter = array('inter_id' => $inter_id);
        $filter['is_hide'] = $productModel::STATUS_CAN_YES;
        $products = $productModel->get_package_list($inter_id, $filter, $limit, $offset);

        ScopeDiscountService::getInstance()->appendScopeDiscount($products, $this->current_inter_id, $this->openid);

        $result = array();
        if ($products)
        {

            $result = $productIds = $pointProductIds = array();
            foreach ($products as $k => $p)
            {
                //做过期处理过滤
                if ( $p['goods_type'] != $productModel::SPEC_TYPE_TICKET && $p['date_type'] == $productModel::DATE_TYPE_STATIC)
                {
                    //固定有效期
                    $time = time();
                    $expireTime = isset($p['expiration_date']) ? strtotime($p['expiration_date']) : NULL;
                    if ($expireTime && $expireTime < $time)
                    {
                        unset($products[$k]);
                        continue;
                    }
                }

                //首页是否显示
                if ($p['is_hide'] == Soma_base::STATUS_TRUE)
                {
                    $productIds[] = $p['product_id'];
                    $result[$p['product_id']] = $p;
                    $result[$p['product_id']]['city'] = isset($hotelsIds[$p['hotel_id']]) ? $hotelsIds[$p['hotel_id']] : '';

                    //如果是积分商品，去掉小数点，向上取整
                    if ($p['type'] == $productModel::PRODUCT_TYPE_POINT)
                    {
                        $result[$p['product_id']]['price_package'] = ceil($p['price_package']);
                        $result[$p['product_id']]['price_market'] = ceil($p['price_market']);
                        $pointProductIds[] = $p['product_id'];
                    }
                }
            }

            //拼团列表
            $this->load->model('soma/Activity_groupon_model', 'activityGrouponModel');
            $groupons = $this->activityGrouponModel->groupon_list_by_productIds($productIds, $this->inter_id);
            foreach ($groupons as $groupon)
            {
                if (in_array($groupon['product_id'], $pointProductIds))
                {
                    $groupon['group_price'] = ceil($groupon['group_price']);
                }
                $result[$groupon['product_id']]['groupon'] = $groupon;

            }

            //秒杀列表
            $this->load->model('soma/Activity_killsec_model', 'activityKillsecModel');
            $killsecs = $this->activityKillsecModel->killsec_list_by_productIds($productIds, $this->inter_id);
            foreach ($killsecs as $killsec)
            {
                if (in_array($killsec['product_id'], $pointProductIds))
                {
                    $killsec['killsec_price'] = ceil($killsec['killsec_price']);
                }
                /** 对秒杀开始时间进行处理 */
                $killsec['killsec_time'] = date('Y-m-d H:i:s', strtotime($killsec['killsec_time']) - Activity_killsec_model::PRESTART_TIME);
                $result[$killsec['product_id']]['killsec'] = $killsec;
            }

            //满减活动
            $this->load->model('soma/Sales_rule_model', 'salesRuleModel');
            $rules = $this->salesRuleModel->get_product_rule($productIds, $this->inter_id);
            if ($rules)
            {
                foreach ($rules as $rule)
                {
                    if ($rule['scope'] == Soma_base::STATUS_TRUE)
                    {
                        //全部适用
                        // 非满减规则过滤
                        $not_auto_rule_arr = array(Sales_rule_model::RULE_TYPE_POINT, Sales_rule_model::RULE_TYPE_BALENCE);
                        if ( ! in_array($rule['rule_type'], $not_auto_rule_arr))
                        {
                            foreach ($productIds as $v)
                            {
                                if ( ! isset($result[$v]['auto_rule']))
                                {
                                    $result[$v]['auto_rule'] = $rule;
                                }
                            }
                        }
                    } else
                    {
                        foreach ($rule['product_id'] as $rule_pid)
                        {
                            $result[$rule_pid]['auto_rule'] = $rule;
                        }
                    }
                }
            }

            if ($result)
            {
                //2017/03/14 luguihong ajax请求，前端js会自动排序，这里使用产品id作为键不合适
                $result_new = array();
                if( $result )
                {
                    foreach ($result as $v)
                    {
                        $result_new[] = $v;
                    }
                    $result = $result_new;
                }

                // 双语翻译
                if($this->langDir == self::LANG_DIR_EN) 
                {
                    $en_result = $result;
                    $en_fields = $productModel->en_fields();
                    $en_info = $productModel->getProductEnInfoList($productIds, $inter_id);
                    foreach($result as $key => $product)
                    {
                        if(isset($en_info[$product['product_id']]))
                        {
                            foreach($en_fields as $field)
                            {
                                $en_result[$key][$field] = $en_info[$product['product_id']][$field];
                            }
                        }
                    }
                    $result = $en_result;
                }

                $return['status'] = Soma_base::STATUS_TRUE;
                $return['data'] = $result;
                $return['msg'] = '成功';
            } else
            {
                $return['msg'] = '处理完后的数据为空！';
            }
        } else
        {
            $return['msg'] = '查找数据为空！';
        }

        $this->json($return);
        // var_dump( $result );
//        echo json_encode($return);

    }

    /**
     * 前端ajax获取产品规格数据
     */
    public function ajax_product_spec()
    {

        $pid = $this->input->get('pid', true);
        $op_res = array('status' => Soma_base::STATUS_FALSE, 'message' => '获取数据，请稍后重新尝试!');

        try
        {
            $this->load->model('soma/Product_specification_setting_model', 'psp_model');
            $sp = $this->psp_model->get_full_specification_compose($this->inter_id, $pid, Soma_base::STATUS_TRUE);

            // 规格信息双语化
            if($this->langDir == self::LANG_DIR_EN)
            {
                if(!empty($sp))
                {
                    $this->load->model('soma/Product_specification_model', 'sp_model');
                    $sp_label = $this->sp_model->get_sepc_type_label();
                    $sp_label_hash = array_flip($sp_label);
                    $sp_label_key = $this->sp_model->get_sepc_type_label_lang_ley();

                    foreach($sp['spec_type'] as $key => $type_name)
                    {
                        if(isset($sp_label_hash[$type_name]))
                        {
                            $sp['spec_type'][$key] = $this->lang->line($sp_label_key[$sp_label_hash[$type_name]]);
                        }
                    }
                }
            }

            $op_res['status'] = Soma_base::STATUS_TRUE;
            $op_res['message'] = '';
            $op_res['data'] = $sp;
        } catch (Exception $e) {

        }

        echo json_encode($op_res);
    }

    /**
     * 时间多规格的选择
     * @author luguihong  <luguihong@mofly.cn>
     *
     */
    public function ticket_select_time()
    {
        $this->theme = 'ticket';

        $interId = $this->inter_id;
        $productId = $this->input->get('pid');
        if ( !$productId )
        {
            //如果不存在，跳回详情页
            redirect(Soma_const_url::inst()->get_url('*/*/package_detail/', array('id' => $interId, 'bsn' => 'package', 'pid' => $productId, 'tkid' => $ticketId)));
        }

        $this->load->model('soma/Product_package_model','somaProductPackageModel');
        $productDetail = $this->somaProductPackageModel->get_product_package_detail_by_product_id($productId, $this->inter_id);
        if( !$productDetail )
        {
            //如果不存在，跳回详情页
            redirect( Soma_const_url::inst()->get_url('*/*/package_detail/', array('id' => $interId, 'bsn' => 'package','pid'=>$productId, 'tkid'=>$ticketId)) );
        }
        //给商品追加价格配置的东西
        $products = array($productDetail);
        ScopeDiscountService::getInstance()->appendScopeDiscount($products, $this->current_inter_id, $this->openid, false);
        $productDetail = $products[0];

        //获取产品id列表
        $serviceName = $this->serviceName(Product_Service::class);
        $serviceAlias = $this->serviceAlias(Product_Service::class);
        $this->load->service($serviceName, null, $serviceAlias);

        //获取门店规格信息
        $settingList = $this->soma_product_service->getSettingInfoByProductId($productId, Soma_base::STATUS_FALSE);
        if ( ! $settingList['data'] )
        {
            //不存在时间的规格，看看是否存在通用的多规格
            $settList = $this->soma_product_service->getSettingInfoByProductId($productId, Soma_base::STATUS_TRUE);
            if( isset( $settList['data'] ) && $settList['data'] )
            {
                //如果存在，跳回详情页
                redirect(Soma_const_url::inst()->get_url('*/*/package_detail/', array('id' => $interId, 'bsn' => 'package', 'pid' => $productId)));
            } else {
                //如果不存在，跳回支付页
                redirect(Soma_const_url::inst()->get_url('*/*/package_pay/', array('id' => $interId, 'bsn' => 'package', 'pid' => $productId)));
            }
        }



        //处理规格信息
        $settingData = array();
        if (isset($settingList['data']) && $settingList['data'])
        {
            //使用用户的 价格配置 修改价格
            if (isset($productDetail['scopes'])) {
                foreach($settingList['data'] as &$setting) {
                    foreach ($productDetail['scopes'] as $scope) {
                        if ($setting['setting_id'] == $scope['setting_id']) {
                            $setting['specprice'] = $scope['price'];
                            $setting['spec_price'] = $scope['price'];
                        }
                    }

                }
            }
            foreach ($settingList['data'] as $k => $v)
            {
                $da                                             = array();
                $yearMonth                                      = date('Y/n',$k);
                $da['time']                                     = date('Y-n-j',$k);
                $da['money']                                    = $v['specprice'];
                $da['stock']                                    = $v['spec_stock'];
                $da['psp_sid']                                  = $v['setting_id'];
                $settingData[$yearMonth]['data']                = $yearMonth;
                $settingData[$yearMonth]['month'][]             = $da;
            }
        }



        //获取推荐位
        $uri = 'soma_package_package_detail';
        $block = $this->get_page_block($uri);

        //做过期处理过滤
        $somaProductPackageModel = $this->somaProductPackageModel;
        $is_expire = FALSE;
        if ( $productDetail['goods_type'] != $somaProductPackageModel::SPEC_TYPE_TICKET && $productDetail['date_type'] == $somaProductPackageModel::DATE_TYPE_STATIC)
        {
            $time = time();
            $expireTime = isset($productDetail['expiration_date']) ? strtotime($productDetail['expiration_date']) : NULL;
            if ($expireTime && $expireTime < $time)
            {
                $is_expire = TRUE;

                //商品已过期，就是商品下架了
                $header = array(
                    'title' => $this->lang->line('goods_off'),
                );
                $this->_view("header", $header);
                $this->_view("offline", array('block' => $block));
                die;
            }
        }

        //如果是积分商品，去掉小数点，向上取整
        if ($productDetail['type'] == $somaProductPackageModel::PRODUCT_TYPE_POINT)
        {
            $productDetail['price_package'] = ceil($productDetail['price_package']);
            $productDetail['price_market'] = ceil($productDetail['price_market']);
        }

        $header = array(
            'title'=>'选择日期',
        );

        $bType = $this->input->get('bType');

//        $this->output->set_content_type('application/json')->set_output(json_encode($productDetail));

        $this->datas = array(
            'productDetail'                 =>$productDetail,
            'settingList'                   =>$settingList,
            'settingData'                   =>$settingData,
            'productId'                     =>$productId,
            'interId'                       =>$interId,
            'bsn'                           =>'package',
            'is_expire'                     =>$is_expire,
            'bType'                         =>$bType,
        );

        $this->_view('header', $header);
        $this->_view('se_data', $this->datas);

    }


    public function moon_cake(){
      $this->_view('mooncake4/package/header');
      $this->_view('mooncake4/package/index');
    }


    public function distribute_products(){

        $redis = $this->get_redis_instance();
        $redis->set('theme_config_distribute', json_encode($this->themeConfig), 3600 * 24 * 100);
        //新版商城尚未做此页面，为防止切换皮肤导致页面异常，故写死
        $this->theme = 'default';
        if(!$this->isNewTheme()){
            $this->_view('header', ['title' => '奖励商品列表']);
            $this->_view('distribute_products');
        }

    }

}
