<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Package extends MY_Front_Soma_Wxapp {

    public function __construct()
    {
        parent::__construct();
    }

    //luguihong 20161102 首页查询分销号，如果是分销员在链接上加上分销号(取代异步查询)
    public function get_saler_id_by_index()
    {
      $staff = $this->_get_saler_id( $this->inter_id, $this->openid );
      if( $staff ){

        $saler_type = isset( $staff['typ'] ) && !empty( $staff['typ'] ) ? $staff['typ'] : '';
        $saler_id = isset( $staff['info']['saler'] ) && !empty( $staff['info']['saler'] ) ? $staff['info']['saler'] : 0;
        if( $saler_id && $saler_type ){

          //判断是分销员还是泛分销 $staff['typ'] ＝ 'STAFF'(分销员), 'FANS'(泛分销)
          $saler = 0;
          $saler_key = '';
          if( $saler_type == 'STAFF' ){
            $saler = $this->input->get('saler');
            $saler_key = 'saler';
          }elseif( $saler_type == 'FANS' ){
            $saler = $this->input->get('fans_saler');
            $saler_key = 'fans_saler';
          }

          $can_jump = Soma_base::STATUS_FALSE;
          if( empty($saler) ){
              //1,链接无saler,[跳转]
              $can_jump = Soma_base::STATUS_TRUE;
          }else if( $saler!= $saler_id ){
              //2,链接有saler,但与本人不符合,[跳转]
              $can_jump = Soma_base::STATUS_TRUE;
          } else if( $saler== $saler_id ){
              //3,连接有saler,并且符合,不跳转
              $can_jump = Soma_base::STATUS_FALSE;
          } else {
              $can_jump = Soma_base::STATUS_FALSE;
          }

          //如果是分销员,并且要跳转
          if( $can_jump == Soma_base::STATUS_TRUE && $saler_key && $saler_id ){
            //需要跳转
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' 
                        || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]&saler=&fans_saler=&$saler_key=".$saler_id;
            header("Location: $url");
            die;
          }

        }

      }

    }

    public function index()
    {
//        $this->load->model('hotel/hotel_model','HotelModel');
//        $params = array(
//            'inter_id'  => $this->inter_id
//        );
//        $HotelModel = $this->HotelModel;
//        $rs = $HotelModel->get_data_hash($params,array('city'),'array');
//
//        print_r($rs);
        //$this->get_saler_id_by_index();

        $this->package_list();
    }

   //套票展示页面
   public function package_list()
   {
      $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
           || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
       $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

       //advs
       $this->load->model('soma/Adv_model','ads_model');
       //首页广告图 cate:0
       $this->datas['advs'] = $this->ads_model
           ->get_ads_by_category($this->inter_id);

//       $pageTitle = '套票'; //月饼说把标题换掉
       $header = $this->page_basic_config();

       //获取酒店城市列表
       $this->load->model('hotel/hotel_model','HotelModel');
       $params = array(
           'inter_id'  => $this->inter_id
       );
       $HotelModel = $this->HotelModel;
       $hotelCites = $HotelModel->get_hotel_hash($params,array( 'city','hotel_id' ), 'array');
       $citesArr = $hotelsIds = array();
       foreach($hotelCites as $v){
           if(empty($v['city'])) continue;  //城市为空
           if(in_array($v['city'], $citesArr) ){
               continue;
           } else{
               array_push($citesArr,$v['city']);
           }

           $hotelsIds[$v['hotel_id']] = $v['city'];
       }
       $filter_cat= $this->input->get('fcid');

       $this->load->model('soma/Category_package_model','categoryModel');
       $this->datas['categories'] = $this->categoryModel->get_package_category_list($this->inter_id, NULL, 5, $filter_cat);

       $this->load->model('soma/Product_package_model','productModel');
       $productModel = $this->productModel;
       $products = $this->productModel->get_product_package_list($filter_cat, $this->inter_id,1,100);

       $result = $productIds = $hotelsArr = array();
       foreach($products as $k=> $p){
          //做过期处理过滤
          if( $p['date_type'] == $productModel::DATE_TYPE_STATIC ){
            //固定有效期
            $time = time();
            $expireTime = isset( $p['expiration_date'] ) ? strtotime( $p['expiration_date'] ) : NULL;
            if( $expireTime && $expireTime < $time ){
              //如果已经过了有效期，停止本次循环，并在此列表删除该商品
              // var_dump( $products[$k] );die;
              unset( $products[$k] );
              continue;
            }
          }

          if( isset($hotelsArr[$p['hotel_id']]) )
             $hotelsArr[$p['hotel_id']]++;
          else 
             $hotelsArr[$p['hotel_id']]= 1;

          //首页是否显示
          if( $p['is_hide'] == Soma_base::STATUS_TRUE ){
              $productIds[] = $p['product_id'];
              $result[$p['product_id']] = $p;
              // $productCites = $HotelModel->get_hotel_hash(array('inter_id'=>$this->inter_id,'hotel_id'=> $p['hotel_id']),array('city'),'array');
              // $result[$p['product_id']]['city'] = isset( $productCites[0]['city'] ) ? $productCites[0]['city'] : NULL;
              $result[$p['product_id']]['city'] = isset( $hotelsIds[$p['hotel_id']] ) ? $hotelsIds[$p['hotel_id']] : '';
          }
       }

       //拼团列表
       $this->load->model('soma/Activity_groupon_model','activityGrouponModel');
       $groupons = $this->activityGrouponModel->groupon_list_by_productIds($productIds,$this->inter_id);
       foreach($groupons as $groupon){
           $result[$groupon['product_id']]['groupon'] = $groupon;
       }

       //秒杀列表
       $this->load->model('soma/Activity_killsec_model','activityKillsecModel');
       $killsecs = $this->activityKillsecModel->killsec_list_by_productIds($productIds, $this->inter_id);
       foreach($killsecs as $killsec){
           /** 对秒杀开始时间进行处理 */
           // 小程序首页进行秒杀倒计时，因此不需要扣减秒杀准备时间60秒，商城需要扣减是因为商城首页显示的就是可以进行秒杀的时间点，而根据秒杀的逻辑，秒杀需要进行60秒的准备，所以商城首页要扣减
           // $killsec['killsec_time']= date('Y-m-d H:i:s', strtotime($killsec['killsec_time'])- Activity_killsec_model::PRESTART_TIME );
           $killsec['killsec_time_ms']= strtotime($killsec['killsec_time']) * 1000;
           $killsec['end_time_ms']= strtotime($killsec['end_time']) * 1000;
           $result[$killsec['product_id']]['killsec'] = $killsec;
       }

       //满减活动
       $this->load->model('soma/Sales_rule_model', 'salesRuleModel');
       $rules = $this->salesRuleModel->get_product_rule( $productIds, $this->inter_id );
       if($rules){
           foreach($rules as $rule){
              if( $rule['scope'] == Soma_base::STATUS_TRUE ){
                //全部适用
                foreach( $productIds as $v ){
                  if( !isset( $result[$v]['auto_rule'] ) ){
                    $result[$v]['auto_rule'] = $rule;
                  }
                }
              }else{
                foreach ($rule['product_id'] as $rule_pid) {
                  $result[$rule_pid]['auto_rule'] = $rule;
                }
              }
           }
       }
        
       $this->datas['products'] = $result;
       $this->datas['packageModel'] = $this->productModel;
       $this->datas['advs_url'] = Soma_const_url::inst()->get_package_detail() . '&pid=';

       //点击分享之后开启这些按钮
       $js_menu_show = array( 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:copyUrl' );
       $uparams= $this->input->get()+ array('id'=> $this->inter_id);

       //取出分享配置
       $this->load->model( 'soma/Share_config_model', 'ShareConfigModel' );
       $ShareConfigModel = $this->ShareConfigModel;
       $position = $ShareConfigModel::POSITION_DEFAULT;//分享类型
       $share_config_detail = $ShareConfigModel->get_share_config_list( $position, $this->inter_id );
       $this->load->helper('soma/package');
       // write_log(json_encode( $share_config_detail ), 'share_config_detail.txt' );
        $default_share_config = $this->get_default_sharing();

       $share_config = array(
            'title'=> isset( $share_config_detail['share_title'] ) && !empty( $share_config_detail['share_title'] ) ? $share_config_detail['share_title'] : $default_share_config['default_title'],
            'desc'=> isset( $share_config_detail['share_desc'] ) && !empty( $share_config_detail['share_desc'] ) ? $share_config_detail['share_desc'] : $default_share_config['default_desc'],
            'link'=> Soma_const_url::inst()->get_share_url( $this->openid, '*/*/*', $uparams ),//$share_config_detail['share_link'],
            'imgUrl'=> isset( $share_config_detail['share_img'] ) && !empty( $share_config_detail['share_img'] ) ? $share_config_detail['share_img'] : $default_share_config['share_img'],
        );
       
       //是否显示“附近”导航栏功能
       $this->datas['multi_hotel'] = count($hotelsArr)>1? TRUE: FALSE;
       $this->datas['multi_city'] = count($citesArr)>1? TRUE: FALSE;
       $this->datas['filter_cat'] = $filter_cat;

       $this->datas['cities'] = $citesArr;
       $this->datas['js_menu_show']= $js_menu_show;
       $this->datas['js_share_config']= $share_config;
       $this->datas['themeConfig'] = $this->themeConfig;
       $this->datas['url'] = $url;
       
       
       //$package = $this->datas['products'];
       $product_list = array();
       $packageModel = $this->productModel;
       foreach($this->datas['products'] as $key => $v){
       	
	       	if(isset($v['killsec'])){ //有秒杀
	       		$this->datas['products'][$key]['price_name'] = '秒杀价';
	       	}elseif(isset($v['groupon'])){
	       		$this->datas['products'][$key]['price_name'] = $v['groupon']['group_count'].'人团';
	       	}else{
	       	
		       	if($v['type'] != $packageModel::PRODUCT_TYPE_BALANCE){
		       		$this->datas['products'][$key]['price_name'] = '惊喜价';
		       	}else{
		       		$this->datas['products'][$key]['price_name'] = '储值价';
		       	}
	       	}
	       	
	      //cdn处理
	      $this->datas['products'][$key]['face_img'] = $this->_replace_cdn_url($this->datas['products'][$key]['face_img']);
	      unset($this->datas['products'][$key]['img_detail']); 	
	      
          $product_list[] = $this->datas['products'][$key];
       }
       $this->datas['products'] = $product_list;
                  
       //$this->_view("header",$header);
       $this->_view('index', $this->datas);
   }


    //分类、专题显示页面
    public function category_list()
    {
        $catId = $this->input->get('catid');
        $catId = intval($catId);

        $searchKey = $this->input->get('city');

        if(empty($catId) && empty($searchKey)){
            return false;
        }

        $this->load->model('soma/Product_package_model','productPackageModel');
        $this->load->model('soma/Activity_groupon_model','activityGrouponModel');
        $this->load->model('soma/Activity_killsec_model','activityKillsecModel');
        
        if( $catId ){
            $this->load->model('soma/Category_package_model','categoryPackageModel');
            // $categoryM = $this->categoryPackageModel->load($catId);
            // if( !$categoryM ) return false;
            // $categoryName = $categoryM->m_get('cat_name');
            // $title = isset($categoryName) ? $categoryName : '分类列表' ;
            $cateInfo = $this->categoryPackageModel->category_package_list_by_catIds( array('cat_id'=>$catId), $this->inter_id ,"",1,100);
            $categoryName = isset( $cateInfo[0]['cat_name'] ) ? $cateInfo[0]['cat_name'] : '';
            $title = isset($categoryName) ? $categoryName : '分类列表' ;
            // var_dump( $catId, $cateInfo );
        } elseif( $searchKey ) {
            $title = $searchKey. '地区列表' ;
        }

        $this->datas['packageModel'] = $this->productPackageModel;

        if(!empty($searchKey)){
            $params = array(
                'inter_id'    => $this->inter_id,
                'product_city'    => $searchKey
            );
        }else{
            $params = array(
                'inter_id'    => $this->inter_id,
                'cat_id'    => $catId
            );
        }

        //点击分享之后开启这些按钮
        $js_menu_show = array( 'menuItem:share:appMessage', 'menuItem:share:timeline' );
        $uparams= $this->input->get()+ array('id'=> $this->inter_id);

        //取出分享配置
        $this->load->model( 'soma/Share_config_model', 'ShareConfigModel' );
        $ShareConfigModel = $this->ShareConfigModel;
        $position = $ShareConfigModel::POSITION_DEFAULT;//分享类型
        $share_config_detail = $ShareConfigModel->get_share_config_list( $position, $this->inter_id );
        $default_share_config = $this->get_default_sharing();
        $share_config = array(
            'title'=> isset( $share_config_detail['share_title'] ) && !empty( $share_config_detail['share_title'] ) ? $share_config_detail['share_title'] : $default_share_config['default_title'],
            'desc'=> isset( $share_config_detail['share_desc'] ) && !empty( $share_config_detail['share_desc'] ) ? $share_config_detail['share_desc'] : $default_share_config['default_desc'],
            'link'=> Soma_const_url::inst()->get_share_url( $this->openid, '*/*/*', $uparams ),//$share_config_detail['share_link'],
            'imgUrl'=> isset( $share_config_detail['share_img'] ) && !empty( $share_config_detail['share_img'] ) ? $share_config_detail['share_img'] : $default_share_config['share_img'],
        );


        $packages =  $this->productPackageModel->get_package_list($this->inter_id,$params);
        $productModel = $this->productPackageModel;

        
        $p_ids = array();
        foreach ($packages as $k => $p) { 
          //做过期处理过滤
          if( $p['date_type'] == $productModel::DATE_TYPE_STATIC ){
            //固定有效期
            $time = time();
            $expireTime = isset( $p['expiration_date'] ) ? strtotime( $p['expiration_date'] ) : NULL;
            if( $expireTime && $expireTime < $time ){
              //如果已经过了有效期，停止本次循环，并在此列表删除该商品
              unset( $packages[$k] );
              continue;
            }
          }

          $p_ids[] = $p['product_id']; 
        }

        $g_list = $this->activityGrouponModel->groupon_list_by_productIds($p_ids, $this->inter_id);
        $k_list = $this->activityKillsecModel->killsec_list_by_productIds($p_ids, $this->inter_id);

        $g_hash = $k_hash = array();
        foreach ($g_list as $row) { $g_hash[$row['product_id']] = $row; }
        foreach ($k_list as $row) { $k_hash[$row['product_id']] = $row; }

        $tmp = array();
        foreach ($packages as $k => $p) {
          if( $p['is_hide'] == Soma_base::STATUS_TRUE ){
            $tmp[$k] = $p;
            if(isset($g_hash[ $p['product_id'] ])) { $tmp[$k]['groupon'] = $g_hash[ $p['product_id'] ]; }
            if(isset($k_hash[ $p['product_id'] ])) { $tmp[$k]['killsec'] = $k_hash[ $p['product_id'] ]; }
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

        $this->datas['packages'] =$packages;
        $this->datas['js_menu_show']= $js_menu_show;
        $this->datas['js_share_config']= $share_config;

        $header = array(
            'title'   => $title
        );
        $package = $this->datas['packages'];
        $packageModel = $this->datas['packageModel'];
         
        foreach($this->datas['packages'] as $key => $v){
        
        	if(isset($v['killsec'])){ //有秒杀
        		$this->datas['packages'][$key]['price_name'] = '秒杀价';
        	}elseif(isset($v['groupon'])){
        		$this->datas['packages'][$key]['price_name'] = $v['groupon']['group_count'].'人团';
        	}else{
        		 
        		if($v['type'] != $packageModel::PRODUCT_TYPE_BALANCE){
        			$this->datas['packages'][$key]['price_name'] = '惊喜价';
        		}else{
        			$this->datas['packages'][$key]['price_name'] = '储值价';
        		}
        	}
        }
        //$this->_view("header",$header);
        $this->_view("search",$this->datas);
    }

    /* 兼容函数 */
    public function killsec_detail()
    {
        $this->package_detail();
    }
    public function killsec_pay()
    {
        $uparams= $this->input->get();
        $url= Soma_const_url::inst()->get_url('*/killsec/killsec_pay', $uparams );
    }

    /**
     * 套票详情页页面
     * 复合[default|groupon|killsec]三种类型页面判断
     */
    public function package_detail()
    {
      $this->load->model('soma/Product_package_model','productPackageModel');
      $this->load->model('soma/Activity_groupon_model','grouponModel');
      $productId = intval($this->input->get('pid'));

      if(empty($productId)){
          return '';
      }

      //获取推荐位
      $uri = 'soma_package_package_detail';
      $block = $this->get_page_block( $uri );

      $productDetail =  $this->productPackageModel
                          ->get_product_package_detail_by_product_id($productId,$this->inter_id);
      if( !$productDetail ){
        //查找不出来，就是商品下架了
        //添加false条件，秒杀更新不涉及到快照功能，先屏蔽，2016-11-4 10:37:11，2016年11月7日11:08:02已重新开启
        $header = array(
            'title'   => '商品下架',
        );
        $this->_view("header",$header);
        $this->_view("offline",array('block'=>$block));
      }else{

        $productGallery = $this->productPackageModel
                            ->get_gallery_front( $productId, $this->inter_id );

        $groupons = $this->grouponModel->groupon_list($productId);
        if( $groupons && count( $groupons ) > 1 ){
            $groupons[0] = array_pop($groupons);
        }

        $this->load->model('soma/Activity_killsec_model','activityKillsecModel');
        $killsec = $this->activityKillsecModel->killsec_by_product_id($productId,$this->inter_id);
        if( is_array($killsec) && count( $killsec ) > 1 ){
          $killsec['killsec_time_ms']= strtotime($killsec['killsec_time']) * 1000;
          $killsec['end_time_ms']= strtotime($killsec['end_time']) * 1000;
        }

        //查找出公众号名
        $this->load->model( 'wx/Publics_model' );
        $publics = $this->Publics_model->get_public_by_id($productDetail['inter_id']);
        if( $publics ){
          $inter_id_name = $publics['name'];
        }else{
          $inter_id_name = '';
        }

        //点击分享之后开启这些按钮
        $js_menu_show = array( 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:copyUrl' );
        $uparams= $this->input->get()+ array('id'=> $this->inter_id);
        $share_config = array(
            'title'=> isset( $productDetail['name'] ) && !empty( $productDetail['name'] ) ? $productDetail['name'] : '发现一家好去处，快点开看看',//商品的标题
            // 'desc'=> isset( $productDetail['hotel_name'] ) && !empty( $productDetail['hotel_name'] ) ? $productDetail['hotel_name'].'精品推荐' : '优惠不等人',//酒店名+精品推荐
            'desc'=> isset( $inter_id_name ) && !empty( $inter_id_name ) ? $inter_id_name.'精品推荐' : '优惠不等人',//酒店名+精品推荐
            'link'=> Soma_const_url::inst()->get_share_url( $this->openid, '*/*/*', $uparams ),
            'imgUrl'=> isset( $productDetail['face_img'] ) && !empty( $productDetail['face_img'] ) ? $productDetail['face_img'] : base_url('public/soma/images/sharing_package.png'),//商品的logo
        );

        $header = array(
            'title'   => $productDetail['name']
        );
        $this->_view("header",$header);

        //做过期处理过滤
        $productModel = $this->productPackageModel;
        $is_expire = FALSE;
        if( $productDetail['date_type'] == $productModel::DATE_TYPE_STATIC ){
          $time = time();
          $expireTime = isset( $productDetail['expiration_date'] ) ? strtotime( $productDetail['expiration_date'] ) : NULL;
          if( $expireTime && $expireTime < $time ){
            $is_expire = TRUE;
            //添加false条件，秒杀更新不涉及到快照功能，先屏蔽，2016-11-4 10:37:11，2016年11月7日11:08:02已重新开启
            //商品已过期，就是商品下架了
            $header = array(
                'title'   => '商品下架',
            );
            $this->_view("header",$header);
            $this->_view("offline",array('block'=>$block));
            die;
          }
        }
        $this->datas['is_expire']= $is_expire;

        // 如果加载不到，显示信息驿站
        $this->load->model('wx/publics_model');
        $public_info = $publics;
        if(!isset($public_info['name'])){ $public_info['name'] = ''; }
        $this->datas['public'] = $public_info;

        /** 秒杀结束判断标记 */
        $finish_killsec= FALSE;
        $ks_model= $this->activityKillsecModel;
        if( $killsec ){
        //if( $killsec && $killsec['schedule_type']== $ks_model::SCHEDULE_TYPE_CYC ){
            $instance= $ks_model->get_aviliable_instance( array('act_id'=>$killsec['act_id'], 'status'=>$ks_model::INSTANCE_STATUS_FINISH ) );
            if( isset($instance[0]) && $instance[0]['close_time']> date('Y-m-d H:i:s') ){
                $finish_killsec= TRUE;  //离秒杀开始时间超过半小时，显示秒杀已经结束
            }
        }
        
        /** 秒杀库存计算(已改为 ajax更新) */
        if( false && $killsec ){
//             $instance= $ks_model->get_aviliable_instance( array('act_id'=>$killsec['act_id'], 'status < '=>$ks_model::INSTANCE_STATUS_FINISH ) );
//             if( isset($instance[0]) && $instance[0]['status']==$ks_model::INSTANCE_STATUS_GOING ){
//                 $cache= $this->_load_cache();
//                 $redis= $cache->redis->redis_instance();
//                 $key= $this->activityKillsecModel->redis_token_key($instance[0]['instance_id']);
//                 $ks_stock = $redis->lSize($key);
//                 $ks_count = $instance[0]['killsec_count'];
        
//             } else {
//                 $ks_count = $killsec['killsec_count'];
//                 $ks_stock = $killsec['killsec_count'];
//             }
//             $ks_percent= round($ks_stock / $ks_count, 2);
//             $this->datas['ks_stock'] = $ks_stock;
//             $this->datas['ks_count'] = $ks_count;
//             $this->datas['ks_percent'] = ( $ks_percent>1? 1: $ks_percent ) * 100;
        }
        //秒杀库存刷新频率
        if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
            $this->datas['stock_reflesh_rate'] = 60000;
        } else {
            $this->datas['stock_reflesh_rate'] = 10000;
        }
        
        /** 促销规则加载 */
        $this->load->model('soma/Sales_rule_model');
        $auto_rule= $this->Sales_rule_model->get_product_rule(array($productId), $this->inter_id, 'auto_rule');
        $auto_rule_new = array();
        if( $auto_rule && count( $auto_rule ) > 0 ){
            foreach( $auto_rule as $v ){
                $auto_rule_new[] = $v;
            }
        }
        $this->datas['auto_rule'] = $auto_rule_new;
        
        /** 对秒杀开始时间进行处理 */
        // 不用减少一分钟，导致前端显示有误
        // if($killsec) $killsec['killsec_time']= date('Y-m-d H:i:s', strtotime($killsec['killsec_time'])- Activity_killsec_model::PRESTART_TIME );
        
        $this->datas['gallery'] = $productGallery;
        $this->datas['packageModel'] =  $this->productPackageModel;
        $this->datas['package'] = $productDetail;
        $this->datas['groupons'] = $groupons;   //拼团
        $this->datas['killsec']  = $killsec;    //秒杀
        $this->datas['finish_killsec']  = $finish_killsec;    //秒杀
        $this->datas['js_menu_show']= $js_menu_show;
        $this->datas['js_share_config']= $share_config;
        $this->datas['block']= $block;
        
        $package = $this->datas['package'];
        $packageModel = $this->productPackageModel;
        $tips_list = array();   
        if($package['can_refund'] == $packageModel::CAN_T && $package['type'] != $packageModel::PRODUCT_TYPE_BALANCE){
        	
        	$oj = array();
        	$oj['tips'] = "购买后，您可以在订单中心直接申请退款，并原路退回";
        	$oj['text'] = "微信退款";
        	$tips_list[] = $oj;
        	
        }
        if($package['can_gift'] == $packageModel::CAN_T){
        	 
        	$oj = array();
        	$oj['tips'] = "该商品购买成功后，可微信转赠给好友，好友可继续使用";
        	$oj['text'] = "赠送朋友";
        	$tips_list[] = $oj;
        	 
        }
        if($package['can_mail'] == $packageModel::CAN_T){ 
        	 
        	$oj = array();
        	$oj['tips'] = "这件商品，是可以邮寄的商品哟";
        	$oj['text'] = "邮寄到家";
        	$tips_list[] = $oj;
        	 
        }
        if($package['can_pickup'] == $packageModel::CAN_T){
        	 
        	$oj = array();
        	$oj['tips'] = "此商品支持您到店使用／自提";
        	$oj['text'] = "到店自提";
        	$tips_list[] = $oj;
        	 
        }
        if($package['can_invoice'] == $packageModel::CAN_T){
        
        	$oj = array();
        	$oj['tips'] = "此商品购买成功后，您可以提交发票信息开票";
        	$oj['text'] = "开具发票";
        	$tips_list[] = $oj;
        
        }
        if($package['can_split_use'] == $packageModel::CAN_T){
        
        	$oj = array();
        	$oj['tips'] = "此商品分时可用";
        	$oj['text'] = "分时可用";
        	$tips_list[] = $oj;
        
        }
        
        if( isset($this->datas['finish_killsec']) && $this->datas['finish_killsec'] ){       	
        	$this->datas['killsec_state_name'] = "本轮秒杀已结束";      	
        }else{
        	$this->datas['killsec_state_name'] = "秒杀进行中";
        }
        
        $this->datas['package']['compose'] = unserialize($this->datas['package']['compose']);
        if(!empty($this->datas['package']['compose'])){
        	$this->datas['package']['show_compose'] = true;
        }
        //暂关闭商品内容
        $this->datas['package']['show_compose'] = false;
        $this->datas['package']['order_notice'] = strip_tags($this->datas['package']['order_notice']);
        
        //将img转成image标签
        //$this->datas['package']['img_detail'] = str_replace("<img","<image>",$this->datas['package']['img_detail']);
        //$this->datas['package']['img_detail'] = str_replace("/>","></image>",$this->datas['package']['img_detail']);
        $reg = '/<img +src=[\'"](http.*?)[\'"]/i';
		preg_match_all( $reg , $this->datas['package']['img_detail'] , $matches );
        $this->datas['package']['img_detail'] = $matches[1];
        
        // 将<br/><Br/>替换成/n
        $replace = array(
          "<br>", "<Br>", "<br/>", "<Br/>", "<br />", "<Br />",
          "&lt;br&gt;", "&lt;Br&gt;", "&lt;br/&gt;", "&lt;Br/&gt;", "&lt;br /&gt;", "&lt;Br /&gt;",
          "&#60;br&#62;", "&#60;Br&#62;", "&#60;br/&#62;", "&#60;Br/&#62;", "&#60;br /&#62;", "&#60;Br /&#62;",
        );
        $this->datas['package']['order_notice'] = str_replace($replace, "\n", $this->datas['package']['order_notice']);
        $this->datas['package']['order_notice'] = str_replace("&nbsp;", ' ', $this->datas['package']['order_notice']);
        $this->datas['package']['img_detail'] = str_replace($replace, "\n", $this->datas['package']['img_detail']);
        $this->datas['package']['img_detail'] = str_replace("&nbsp;", ' ', $this->datas['package']['img_detail']);
        
		//购买按钮显示
        $finish_killsec = $this->datas['finish_killsec'];
        $killsec = $this->datas['killsec'];
        $package = $this->datas['package'];
        $packageModel = $this->datas['packageModel'];
        $groupons = $this->datas['groupons'];
        if( isset($finish_killsec) && $finish_killsec ){
        	$btn_name = $killsec['killsec_price']."已售馨";
        	$btn_disable = false;
        	$btn_event = "";
        }elseif( isset($killsec) && !empty($killsec)){
        	$temp = ($package['type'] != $packageModel::PRODUCT_TYPE_BALANCE) ? "秒杀购买" : "储值秒杀";
        	$btn_name = "¥{$killsec['killsec_price']}".$temp;
        	$btn_disable = true;
        	$btn_event = "killsec";
        } elseif( !empty($groupons) && !$this->datas['is_expire'] ){
        	
        	foreach($groupons as $k=>$v){
        		
        		$btn_name = "¥{$v['group_price']} | ".$v['group_count']."人团";
        		$btn_disable = true;
        		$btn_event = "tuan";
        		break;
        	}
     
        }elseif( isset($this->datas['auto_rule'][0]) ){
        	$btn_name = "团购特惠";
        	$btn_disable = true;
        	$btn_event = "tuan";
        }else{
        	$btn_name = "";
        	$btn_disable = false;
        	$btn_event = "tuan";
        }
        
        if( $this->datas['is_expire'] ){
        	$btn_2_name = "已过期";
        	$btn_2_disable = false;
        	$btn_2_event = "";
        }else{
        	$btn_2_name = "¥{$package['price_package']} ";
        	$temp = ($package['type'] != $packageModel::PRODUCT_TYPE_BALANCE) ? "立即购买" : "储值购买";
        	$btn_2_name .= $temp;
        	$btn_2_disable = true;
        	$btn_2_event = "buy";
        }

        $this->datas['btn_1_name'] = $btn_name;
        $this->datas['btn_1_disable'] = $btn_disable;
        $this->datas['btn_1_event'] = $btn_event;
        
        $this->datas['btn_2_name'] = $btn_2_name;
        $this->datas['btn_2_disable'] = $btn_2_disable;
        $this->datas['btn_2_event'] = $btn_2_event;
        
        $this->datas['tips_list'] = $tips_list; 
        
        //cdn处理
        foreach($this->datas['gallery'] as $key=>$d){
        	$this->datas['gallery'][$key]['gry_url'] = $this->_replace_cdn_url($this->datas['gallery'][$key]['gry_url']);
        	
        }
        $this->datas['package']['img_detail'] = $this->_replace_cdn_url($this->datas['package']['img_detail']);
       
        $this->_view("package_detail",$this->datas);
      }
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
        unset( $postArr['card_type'] );
        // var_dump( $postArr );die;
        foreach($postArr as $pid=>$qty){
            $product_hash[] = $pid;
        }

        $this->load->model('soma/Product_package_model');
        $products = $this->Product_package_model->get_product_package_by_ids($product_hash, $this->inter_id);
        $subtotal= 0;
        if(!empty($products)){
            foreach($products as $k=>$v){
                //$proInfo[$v['product_id']]['price_package'] = $v['price_package'];
                $subtotal+= $v['price_package']* $postArr[$v['product_id']];  //累计订单总额
            }
        }else{
            $result = array(
                'status'=> Soma_base::STATUS_FALSE,
                'data'  => '',
                'message'  => '没有可用的优惠券'
            );
            echo json_encode($result);
            exit;
        }

/** 读取购买人的可用券 ********************************/
        $this->load->library('Soma/Api_member');
        $api= new Api_member($this->inter_id);
        $result= $api->get_token();
        $api->set_token($result['data']);
        $result= $api->conpon_sign_list($this->openid);
        //$coupons = $result['data'];
        // var_dump( $result['data'] );die;
/**  ***********************/
        $card_ids = array();
        if( isset($result['data']) && count($result['data'])>0 ){
            $coupons= array();
            foreach ($result['data'] as $v){
                if( !in_array($v->card_id, $card_ids) ) $card_ids[]= $v->card_id;
            }
            $this->load->model('soma/Sales_order_discount_model');
            $discountModel = $this->Sales_order_discount_model;
            $this->load->model('soma/Sales_coupon_model');
            $link_all= $this->Sales_coupon_model->get_coupon_product_list( $card_ids, $this->inter_id );

            //取出适用所有商品的优惠券，格式：array('card_id'=>'券1',)
            $wide_scope_coupon= $this->Sales_coupon_model->get_wide_scope_coupon($this->inter_id, TRUE);

            foreach ($result['data'] as $k=>$v){
                //逐张优惠券判断是否满足购物条件
                $tmp = (array) $v;

                if( array_key_exists( $tmp['card_id'], $wide_scope_coupon)) {
                    if( isset($tmp['least_cost']) && $tmp['least_cost'] > $subtotal ){
                        $tmp['usable'] = FALSE;

                    } else if( isset($tmp['over_limit']) && $tmp['over_limit'] > 0 && $tmp['over_limit'] < $subtotal ){
                        $tmp['usable'] = FALSE;

					          } else {
                        $tmp['usable'] = TRUE;  //该卡属于宽泛匹配卡id
                    }
                    $tmp['scopeType'] = '全部商品适用';
                
                } else {
                    foreach ($link_all as $sk=>$sv){
                        //匹配配置表中的各个配置商品，匹配到为止
                        if( isset($tmp['usable']) && $tmp['usable']== TRUE ){
                            continue;  //匹配到之后跳出不再循环匹配。
                        }
                    
                        //已经配置了该卡券 && 配置的商品、数量 跟当前购物清单匹配
                        if( isset($tmp['least_cost']) && $tmp['least_cost'] > $subtotal ){
                            $tmp['usable'] = FALSE;

                        } else if( isset($tmp['over_limit']) && $tmp['over_limit'] > 0 && $tmp['over_limit'] < $subtotal ){
                            $tmp['usable'] = FALSE;

                        } else {
                            if( $sv['card_id']== $tmp['card_id']
                                && in_array($sv['product_id'], $product_hash)
                                && $postArr[$sv['product_id']]>= $sv['qty']
                            ){
                                $tmp['usable'] = TRUE;  //该卡满足配置和数量条件
                                $tmp['scopeType'] = '部分商品适用';
                    
                            } else{
                                $tmp['usable'] = FALSE;  //该卡不符合使用条件
                                $tmp['scopeType'] = '无适用商品';
                            }
                    
                        }
                    }
                }

                //判断是否到了可用时间
                if( time() < $tmp['use_time_start'] ){
                  $tmp['usable'] = FALSE;  //该卡不符合使用条件,没有到使用时间
                }
                
                #######################################################
                // 跟会员组了解过
                // 券的过期时间设置是 2016-11-11 00:00:00，但是实际过期时间是2016-11-11 23:59:59
                $expire_date= date('Y-m-d', $tmp['expire_time']);
                $expire_time = strtotime($expire_date);
                if($tmp['expire_time'] == $expire_time) {
                  $real_expire_date = $expire_date . ' 23:59:59';
                  $tmp['expire_time'] = strtotime($real_expire_date);
                }
                #######################################################
                
                $minusTime = $tmp['expire_time'] - time();
                if($minusTime<=0){
                    continue;
                } elseif( ($minusTime/86400) <= 10) {
                    $tmp['expire_time'] = '还有 '. ceil($minusTime/ 86400). "天过期";
                } else {
                    $tmp['expire_time'] = '有效期至：'. date("Y-m-d", $tmp['expire_time']);
                }
                
                $coupons[]= $tmp;
            }

            //将不可用的券排到最后面
            $can_use_arr= array();
            foreach ($coupons as $k=>$v){
                if( isset($v['usable']) && $v['usable']==TRUE ){
                    $can_use_arr[]= $v;
                    unset($coupons[$k]);
                }
            }
            foreach ($can_use_arr as $k=>$v){
                array_unshift($coupons, $v);
            }

            //luguihong 20161107 把优惠券分成抵扣券、兑换券、折扣券
            $dj = array();
            $zk = array();
            $dh = array();
            $cz = array();
            foreach( $coupons as $k=>$v ){
              if( $v['card_type'] == $discountModel::TYPE_COUPON_DJ ){
                //代金券
                $dj[] = $v;
              }elseif( $v['card_type'] == $discountModel::TYPE_COUPON_ZK ){
                //折扣券
                $zk[] = $v;
              }elseif( $v['card_type'] == $discountModel::TYPE_COUPON_DH ){
                //兑换券
                $dh[] = $v;
              }elseif( $v['card_type'] == $discountModel::TYPE_COUPON_CZ ){
                //储值券
                $cz[] = $v;
              }
            }

            if( $cardType == $discountModel::TYPE_COUPON_DJ ){
              //代金券
              $coupons = $dj;
            }elseif( $cardType == $discountModel::TYPE_COUPON_ZK ){
              //折扣券
              $coupons = $zk;
            }elseif( $cardType == $discountModel::TYPE_COUPON_DH ){
              //兑换券
              $coupons = $dh;
            }elseif( $cardType == $discountModel::TYPE_COUPON_CZ ){
              //储值券
              $coupons = $cz;
            }
            // var_dump( $subtotal, $coupons );die;

            $result = array(
                'status'=> Soma_base::STATUS_TRUE,
                'data'  => $coupons,
                // 'data'  => $coupons_new,
                'message'   => ''
            );
        } else {
            $result = array(
                'status'=> Soma_base::STATUS_TRUE,
                'data'  => array(),
                'message'   => '参数有误'
            );
        }

        echo json_encode($result);
    }

    /**
     * 优惠券金额计算
     */
    public function coupon_calulate_ajax()
    {
        $result= array('status'=>2, 'message'=>'获取优惠券信息失败', );
        $member_card_id= $this->input->post('mcid');
        $pid= $this->input->post('product_id');
        $pqty= $this->input->post('qty');
    
        $this->load->library('Soma/Api_member');
        $api= new Api_member($this->inter_id);
        $result= $api->get_token();
        $api->set_token($result['data']);
        $result= $api->conpon_sign_info($member_card_id, $this->openid);
        $result= (array) $result['data'];
    
        if($result){
            $subtotal= 0;
            $this->load->model('soma/Product_package_model');
            $pids= array($pid);
            $products= $this->Product_package_model->get_product_package_by_ids($pids, $this->inter_id );
            $subtotal= 0;
            foreach ($products as $k=>$v){
                $products[$k]['qty']= $pqty;
                $subtotal+= $v['price_package']* $pqty;
            }
    
            $this->load->model('soma/Sales_order_model');
            $this->load->model('soma/Sales_order_discount_model');
            $order= $this->Sales_order_model->subtotal= $subtotal;
            $total= $this->Sales_order_discount_model->calculate_discount( $result, $products, Sales_order_discount_model::TYPE_COUPON, $order );
            //echo $total;
            //name优惠券名称
            //amount优惠金额
            //mcid
            //status状态1成功
    
            $result['name']= $result['title'];
            $result['mcid']= $result['member_card_id'];
            $result['amount']= $total;
            $result['status']= 1;
            $result['message']= 'success';
        }
        echo json_encode($result );
    }
    
    /**
     * 异步拉取当前适用的优惠规则
返回格式： array(
//立减活动
	'activity'=>array(
		'status' => 1 , //1:有,2没有
		'auto_rule' => array(
			'rule_type' => 
			'name'=>'已优惠￥100元',
			'reduce_cost' =>  100,
			’least_cost' => 50,
			'can_use_coupon' => 1
		)
	)
)
,
//积分储值
	'asset'=> array(
		'status' => 1 , //1:有,2没有
		'cal_rule' => array(
			'rule_type' =>
			'quote' =>
			'reduce_cost' =>
			'can_use_coupon' => 1
		)
	)
);
     */
    public function discount_rule_ajax()
    {
        $return= array('status'=>2, 'message'=>'获取OPENID失败', 'data'=> array() );
        if( !$this->openid || !$this->inter_id ){
            die( json_encode($return) );
        }
        try {
            $settlement= $this->input->post('stl');
            $pid= $this->input->post('pid');
            $pqty= $this->input->post('qty');

            $this->load->model('soma/Product_package_model');
            $this->load->model('soma/Sales_rule_model');

            $pids= array($pid);
            $products= $this->Product_package_model->get_product_package_by_ids($pids, $this->inter_id );
            $subtotal= 0;
            foreach ($products as $k=>$v){
                $products[$k]['qty']= $pqty;
                $subtotal+= $v['price_package']* $pqty;
            }
            
            $rules= $this->Sales_rule_model->get_discount_rule($this->inter_id, $this->openid, $products, $subtotal, $settlement );
            /**
             * 返回格式
 array(  //key相同为二选一显示, quote为使用额度，如5000积分，scale为比例值，least_cost为最低使用额，can_use_coupon为2指限制使用优惠券
     'auto_rule'=> array('rule_type'=>51, 'name'=>'xx满减活动', 'reduce_cost'=>'￥10.00', 'least_cost'=>'100.00', 'can_use_coupon'=>2 ),
     'auto_rule'=> array('rule_type'=>52, 'name'=>'xx满额打折', 'reduce_cost'=>'￥15.00', 'least_cost'=>'100.00', 'can_use_coupon'=>2 ),
     'auto_rule'=> array('rule_type'=>55, 'name'=>'xx随机立减', 'reduce_cost'=>'￥3.00', 'least_cost'=>'0', 'can_use_coupon'=>2 ),
     'cal_rule'=> array('rule_type'=>30, 'quote'=>'5000', 'reduce_cost'=>'￥50.00', 'can_use_coupon'=>1 ),
     'cal_rule'=> array('rule_type'=>40, 'quote'=>'100', 'reduce_cost'=>'￥100.00', 'can_use_coupon'=>1 ),
 )
             */
            if( isset($rules['auto_rule']) ){
                $activity= array(
                    'status'=> Soma_base::STATUS_TRUE,
                    'auto_rule'=> $rules['auto_rule'],
                );
            } else {
                $activity= array(
                    'status'=> Soma_base::STATUS_FALSE,
                    'auto_rule'=> array(),
                );
            }
            if( isset($rules['cal_rule']) ){
                $asset= array(
                    'status'=> Soma_base::STATUS_TRUE,
                    'cal_rule'=> $rules['cal_rule'],
                );
            } else {
                $asset= array(
                    'status'=> Soma_base::STATUS_FALSE,
                    'cal_rule'=> array(),
                );
            }

            $return['message']= 'success';
            $return['status']= Soma_base::STATUS_TRUE;
            $return['data']= array('activity'=> $activity, 'asset'=> $asset, 'base_info'=> $rules['base_info'] );
            
        } catch (Exception $e) {
            $return['status']= Soma_base::STATUS_FALSE;
            $return['message']= $e->getMessage();
            $return['data']= $rules;
        }
        
       $this->out_put_msg ( 1, '', $return,'package/discount_rule_ajax' );
       // echo json_encode($return);
    }
    
    /**
     * 直接显示分销二维码
     */
    public function show_saler_qrcode()
    {
        $inter_id= $this->inter_id;
        $openid= $this->openid;
        $this->load->model('distribute/Staff_model');
        $staff= $this->Staff_model->get_my_base_info_openid( $inter_id, $openid );
        if( $staff && $staff['qrcode_id'] ){
            if($inter_id){
                $url= front_site_url($inter_id). '/soma/package/index?id='. $inter_id. '&saler='. $staff['qrcode_id'];
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
     * 获取当前openid的分销员ID
     */
    protected function _get_saler_id($inter_id, $openid)
    {   
        $this->load->library('Soma/Api_idistribute');
        $api = new Api_idistribute();
        return $api->get_saler_info($inter_id, $openid);
        /*
        $this->load->model('distribute/Staff_model');
        $staff= $this->Staff_model->get_my_base_info_openid( $inter_id, $openid );
        if( $staff && $staff['qrcode_id'] ){
            return $staff['qrcode_id'];
        } else {
            return FALSE;
        }
        */
    }
    /**
     *  套票支付
     */
    public function package_pay(){

        $productId = intval($this->input->get('pid'));
        if(empty($productId)){
            return '';
        }
        $this->load->model('soma/Product_package_model','productPackageModel');

        $productDetail =  $this->productPackageModel
            ->get_product_package_detail_by_product_id($productId,$this->inter_id);
        if( !$productDetail ){
          $header = array(
              'title'   => '商品下架',
          );
          $this->_view("header",$header);
          $this->_view("offline",array('block'=>''));
          die;
        }
        $header = array(
            'title' => '购买支付'
        );

        $productDetail['type'] = isset( $productDetail['type'] ) ? $productDetail['type'] : NULL;

        //做过期处理过滤
        $productModel = $this->productPackageModel;
        $is_expire = FALSE;
        if( $productDetail['date_type'] == $productModel::DATE_TYPE_STATIC ){
          $time = time();
          $expireTime = isset( $productDetail['expiration_date'] ) ? strtotime( $productDetail['expiration_date'] ) : NULL;
          if( $expireTime && $expireTime < $time ){
            $is_expire = TRUE;
            //添加false条件，秒杀更新不涉及到快照功能，先屏蔽，2016-11-4 10:37:11，2016年11月7日11:08:02已重新开启
            //商品已过期，就是商品下架了
            $header = array(
                'title'   => '商品下架',
            );
            $this->_view("header",$header);
            $this->_view("offline",array('block'=>''));
            die;
          }
        }
        $this->datas['is_expire']= $is_expire;

        //点击分享之后开启这些按钮
        $js_menu_show = array( 'menuItem:share:appMessage', 'menuItem:share:timeline' );
        $uparams= $this->input->get()+ array('id'=> $this->inter_id);

        //取出分享配置
        $this->load->model( 'soma/Share_config_model', 'ShareConfigModel' );
        $ShareConfigModel = $this->ShareConfigModel;
        $position = $ShareConfigModel::POSITION_DEFAULT;//分享类型
        $share_config_detail = $ShareConfigModel->get_share_config_list( $position, $this->inter_id );
        $default_share_config = $this->get_default_sharing();
        $share_config = array(
            'title'=> isset( $share_config_detail['share_title'] ) && !empty( $share_config_detail['share_title'] ) ? $share_config_detail['share_title'] : $default_share_config['default_title'],
            'desc'=> isset( $share_config_detail['share_desc'] ) && !empty( $share_config_detail['share_desc'] ) ? $share_config_detail['share_desc'] : $default_share_config['default_desc'],
            'link'=> Soma_const_url::inst()->get_share_url( $this->openid, '*/package/package_detail', $uparams ),
            'imgUrl'=> isset( $share_config_detail['share_img'] ) && !empty( $share_config_detail['share_img'] ) ? $share_config_detail['share_img'] : $default_share_config['share_img'],
        );

        //取出联系人和电话
        $filter = array();
        $filter['openid'] = $this->openid;
        $customer_info = $this->productPackageModel->get_customer_contact( $filter );
        $this->datas['customer_info']= $customer_info;
        // var_dump( $customer_contact );exit;
        
        /** 读取购买人的可用券 ********************************/
        $this->load->library('Soma/Api_member');
        $api= new Api_member($this->inter_id);
	    $result= $api->get_token();
        $result['data'] = isset($result['data']) ? $result['data']:array();
	    $api->set_token($result['data']);
        $result= $api->conpon_sign_list($this->openid);
        $result['data'] = isset($result['data']) ? $result['data']:array();
        $this->datas['coupons'] = $result['data'];
        /**  ***********************/

        // 储值类型商品读取购买人的储值信息
        $this->datas['balance'] = null;
        if($productDetail['type'] 
          && $productDetail['type'] == Product_package_model::PRODUCT_TYPE_BALANCE) {
          $result = $api->get_token();
          $result['data'] = isset($result['data']) ? $result['data']:array();
          $api->set_token($result['data']);
          $balance = $api->balence_info($this->openid);
          $balance['data'] = isset($balance['data']) ? $balance['data']:0;
          $this->datas['balance'] = $balance['data'];

          $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
           || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
          $balance_url = "$protocol$_SERVER[HTTP_HOST]/index.php/membervip/depositcard/buydeposit?id=".$this->inter_id;
          $this->datas['balance_url'] = $balance_url;
        }

        $this->load->helper('soma/time_calculate');

        $this->load->model('soma/Sales_rule_model');
        $this->load->model('soma/Sales_order_discount_model');
        $this->load->model('soma/Sales_order_model');

        $salesRuleModel = $this->Sales_rule_model;

        
        /** 根据rid规则ID参数确定应该默认买多少份 ********************************/
        $fix_rule= $salesRuleModel->find( array('rule_id'=> $this->input->get('rid') ) );
        if($fix_rule && $fix_rule['lease_cost'] && $productDetail['price_package']){
            $fix_qty= $fix_rule['lease_cost']/$productDetail['price_package'];
            if( $fix_qty< 1 ){
                $fix_qty= 1;
            } else if( $fix_qty> 1 ){
                $fix_qty = ceil($fix_qty);
            } else {
                $fix_qty = intval($fix_qty);
            }
            $this->datas['buy_default'] = $fix_qty > 200 ? 200 : $fix_qty;
        }
        /**  ***********************/
        
        
        $payParams = array('id'=> $this->inter_id );

        $bType = $this->input->get('bType');
        if(!empty($bType)){
            $payParams['bType'] = $bType;
        }

        $this->datas['payParams'] = $payParams;
        $this->datas['userReduceObj']  = array('type' => $salesRuleModel::RULE_TYPE_POINT, 'total_amount'=> 1 , 'usable_amount' => 1 );
        $this->datas['salesRuleModel'] = $salesRuleModel;
        $this->datas['discountModel'] = $this->Sales_order_discount_model;
        $this->datas['salesOrderModel'] = $this->Sales_order_model;
        $this->datas['packageModel'] =  $this->productPackageModel;
        $this->datas['package'] = $productDetail;
        $this->datas['js_menu_show']= $js_menu_show;
        $this->datas['js_share_config']= $share_config;

        $this->datas['show_balance_passwd'] = Soma_base::STATUS_FALSE;
        $balance_inter_ids = array('a457946152', 'a471258436');
        if(in_array($this->inter_id, $balance_inter_ids)) {
          $this->datas['show_balance_passwd'] = Soma_base::STATUS_TRUE;
        }

/** 检测 自己saler_id ********************************/
        // 修改个人分销信息获取 fengzhongcheng
        // $saler_id= $this->_get_saler_id( $this->inter_id, $this->openid );
        // if($saler_id) $this->datas['saler_self'] = $saler_id;

        $saler_info= $this->_get_saler_id( $this->inter_id, $this->openid );
        if($saler_info) {
          $data_key = 'saler_self';
          if($saler_info['typ'] == 'FANS') {
            $data_key = 'fans_saler_self';
          }
          $this->datas[$data_key] = $saler_info['info']['saler'];
        }
/**  ***********************/
        
        $package = $this->datas['package'];
        $packageModel = $this->productPackageModel;
        $tips_list = array();
        if($package['can_refund'] == $packageModel::CAN_T && $package['type'] != $packageModel::PRODUCT_TYPE_BALANCE){
        	 
        	$oj = array();
        	$oj['tips'] = "购买后，您可以在订单中心直接申请退款，并原路退回";
        	$oj['text'] = "微信退款";
        	$tips_list[] = $oj;
        	 
        }
        if($package['can_gift'] == $packageModel::CAN_T){
        
        	$oj = array();
        	$oj['tips'] = "该商品购买成功后，可微信转赠给好友，好友可继续使用";
        	$oj['text'] = "赠送朋友";
        	$tips_list[] = $oj;
        
        }
        if($package['can_mail'] == $packageModel::CAN_T){
        
        	$oj = array();
        	$oj['tips'] = "这件商品，是可以邮寄的商品哟";
        	$oj['text'] = "邮寄到家";
        	$tips_list[] = $oj;
        
        }
        if($package['can_pickup'] == $packageModel::CAN_T){
        
        	$oj = array();
        	$oj['tips'] = "此商品支持您到店使用／自提";
        	$oj['text'] = "到店自提";
        	$tips_list[] = $oj;
        
        }
        if($package['can_invoice'] == $packageModel::CAN_T){
        
        	$oj = array();
        	$oj['tips'] = "此商品购买成功后，您可以提交发票信息开票";
        	$oj['text'] = "开具发票";
        	$tips_list[] = $oj;
        
        }
        if($package['can_split_use'] == $packageModel::CAN_T){
        
        	$oj = array();
        	$oj['tips'] = "此商品分时可用";
        	$oj['text'] = "分时可用";
        	$tips_list[] = $oj;
        
        }
        $this->datas['tips_list'] = $tips_list;
        
        
        //$this->_view("header",$header);
        $this->_view("package_pay",$this->datas);
    }

    /**
     *拼团支付
     */
    public function groupon_pay(){

        $this->_get_wx_userinfo();//获取用户头像

        $actId = $this->input->get('act_id');
        $this->load->model('soma/Activity_groupon_model','grouponModel');

        $grouponDetail = $this->grouponModel->groupon_detail($actId);

        $this->load->model('soma/Product_package_model','productPackageModel');

        $productDetail =  $this->productPackageModel
            ->get_product_package_detail_by_product_id($grouponDetail['product_id'],$this->inter_id);
        if( !$productDetail ){
          $header = array(
              'title'   => '商品下架',
          );
          $this->_view("header",$header);
          $this->_view("offline",array('block'=>''));
          die;
        }

        $productModel = $this->productPackageModel;
        $is_expire = FALSE;
        if( $productDetail['date_type'] == $productModel::DATE_TYPE_STATIC ){
          $time = time();
          $expireTime = isset( $productDetail['expiration_date'] ) ? strtotime( $productDetail['expiration_date'] ) : NULL;
          if( $expireTime && $expireTime < $time ){
            //商品已经过期，跳回原来页面
            $is_expire = TRUE;
            $header = array(
                'title'   => '商品下架',
            );
            $this->_view("header",$header);
            $this->_view("offline",array('block'=>''));
            die;
          }
        }
        $this->datas['is_expire']= $is_expire;

        $this->datas['packageModel'] = $this->productPackageModel;
        $this->datas['product'] = $productDetail;
        $this->datas['groupon'] = $grouponDetail;

        $header = array(
            'title'   => $grouponDetail['act_name']."详情"
        );

        //点击分享之后开启这些按钮
        $js_menu_show = array( 'menuItem:share:appMessage', 'menuItem:share:timeline' );
        $uparams= $this->input->get()+ array('id'=> $this->inter_id);
        $uparams['pid']= $grouponDetail['product_id'];

        //取出分享配置
        $this->load->model( 'soma/Share_config_model', 'ShareConfigModel' );
        $ShareConfigModel = $this->ShareConfigModel;
        $position = $ShareConfigModel::POSITION_DEFAULT;//分享类型
        $share_config_detail = $ShareConfigModel->get_share_config_list( $position, $this->inter_id );
        $default_share_config = $this->get_default_sharing();
        $share_config = array(
            'title'=> isset( $share_config_detail['share_title'] ) && !empty( $share_config_detail['share_title'] ) ? $share_config_detail['share_title'] : $default_share_config['default_title'],
            'desc'=> isset( $share_config_detail['share_desc'] ) && !empty( $share_config_detail['share_desc'] ) ? $share_config_detail['share_desc'] : $default_share_config['default_desc'],
            'link'=> Soma_const_url::inst()->get_share_url( $this->openid, '*/package/package_detail', $uparams ),
            'imgUrl'=> isset( $share_config_detail['share_img'] ) && !empty( $share_config_detail['share_img'] ) ? $share_config_detail['share_img'] : $default_share_config['share_img'],
        );
        
        $group_id = $this->input->get('grid');

        if($group_id){
            $this->datas['type'] = 'join';
        }else{
            $this->datas['type'] = 'add';
        }
        $this->datas['grid'] = $group_id;
        $this->datas['js_menu_show']= $js_menu_show;
        $this->datas['js_share_config']= $share_config;

        //取出联系人和电话
        $filter = array();
        $filter['openid'] = $this->openid;
        $customer_info = $this->productPackageModel->get_customer_contact( $filter );
        $this->datas['customer_info']= $customer_info;
        // var_dump( $customer_contact );exit;

/** 检测 自己saler_id ********************************/
        // 修改个人分销信息获取 fengzhongcheng
        // $saler_id= $this->_get_saler_id( $this->inter_id, $this->openid );
        // if($saler_id) $this->datas['saler_self'] = $saler_id;

        $saler_info= $this->_get_saler_id( $this->inter_id, $this->openid );
        if($saler_info) {
          $data_key = 'saler_self';
          if($saler_info['typ'] == 'FANS') {
            $data_key = 'fans_saler_self';
          }
          $this->datas[$data_key] = $saler_info['info']['saler'];
        }
/**  ***********************/
        
        $this->_view("header",$header);
        $this->_view("group_pay",$this->datas);
    }


    /**
     *获取附近套票
     */
    public function get_packages_nearby(){
        $lat = $this->input->post('lat');
        $lng = $this->input->post('lng');
        $products = array();
        if(empty($lat) || empty($lng) ){
            if(is_ajax_request()){
                echo json_encode($products);
                exit;
            }else{
                return $products;
            }
        }

//        $lat = '23.136202'; //测试
//        $lng = '113.3291';  //测试
        $this->load->model('soma/Product_package_model','ProductPackageModel');
        $this->load->model('soma/Activity_groupon_model','activityGrouponModel');
        $products = $this->ProductPackageModel->get_packages_nearby($lat,$lng,'',$this->inter_id);
        $productModel = $this->ProductPackageModel;
        
        foreach($products as $k=> $p){
            if( $p['date_type'] == $productModel::DATE_TYPE_STATIC ){
              $time = time();
              $expireTime = isset( $p['expiration_date'] ) ? strtotime( $p['expiration_date'] ) : NULL;
              if( $expireTime && $expireTime < $time ){
                unset( $products[$k] );
                continue;
              }
            }

            $groupons = $this->activityGrouponModel->groupon_list($p['product_id']);
            if(!empty($groupons)){
                $products[$k]['groupon'] = $groupons[0];
            }
        }

//        print_r($result);
        if(is_ajax_request()){
        	
        	//cdn
        	if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
        		$search= array(
        				'file.iwide.cn',
        		);
        		$replace= array(
        				'7n.cdn.iwide.cn',
        		);
        	} else {
        		$search= array(
        				'30.iwide.cn:821',
        		);
        		$replace= array(
        				'soma.cdn.iwide.cn',
        		);
        	}
        	echo str_replace($search, $replace, json_encode($products));
        	//echo $this->_replace_cdn_url(json_encode($products));
           // echo json_encode($products);
        }else{
            return $products;
        }

    }


    /**
     * //支付成功页面
     */
    public function success(){

        $header = array(
            'title'     => '支付成功'
        );

        $settlement = '';
        $settlement = $this->input->get('settlement');
        $order_id = $this->input->get('order_id');
        if(!empty($settlement) && $settlement == 'groupon'){
            $this->load->model('soma/Activity_groupon_model','activityGrouponModel');
            $grouponInfo = $this->activityGrouponModel->get_groupon_by_order_id($order_id,$this->inter_id);
            $grouponId = $grouponInfo['group_id'];


            $params = array(
                'id' => $this->inter_id,
                'grid'      => $grouponId

            );
            $jumpLink = Soma_const_url::inst()->get_url('soma/groupon/groupon_detail/',$params);

        }else{
            $params = array(
                'id' => $this->inter_id,
            );

            $this->load->library('session');
            $u_type = $this->session->userdata('order_use_type');
            
            if($u_type == "1") {
              // 送自己
              $params['oid'] = $order_id;
              $jumpLink = Soma_const_url::inst()->get_url('soma/order/self_use', $params);
            } else if ($u_type == "2") {
              // 送朋友
              
              $this->load->model('soma/Sales_order_model', 'o_model');
              $order = $this->o_model->load($order_id);

              $this->load->model('soma/Gift_order_model');
              $model = $this->Gift_order_model;
              $params['bsn']           = $order->m_get('business');
              $params['send_from']     = $model::SEND_FROM_ORDER;
              $params['send_order_id'] = $order_id;
              $params['oid']           = $order_id;

              $jumpLink = Soma_const_url::inst()->get_url('soma/gift/package_pre_send',$params);
            } else {
              $jumpLink = Soma_const_url::inst()->get_url('soma/order/my_order_list/',$params);
            }

        }




        $this->datas['jumpLink'] = $jumpLink;
        $this->_view("header",$header);
        $this->_view('pay_success', $this->datas);
    }

    /**
     * //支付失败
     */
    public function fail(){
        $this->load->model('soma/activity_groupon_model');
        $GrouponModel = $this->activity_groupon_model;
        $settlement = $this->input->get('settlement');
        $order_id = $this->input->get('order_id');
        $inter_id = $this->input->get('id');
        if($settlement == 'groupon'){



            $user = $GrouponModel->get_users_by_order_id($order_id,$inter_id);
            if(empty($user)){ //虚假订单或者用户
                redirect(Soma_const_url::inst()->get_pacakge_home_page());
            }else{

                    if($user['openid'] == $this->openid && $user['status'] == $GrouponModel::GROUP_ADD_STATUS_WAITING_PAY){  //第一重验证,避免而已释放

                        $grouponDetail =  $GrouponModel->get_groupon_by_order_id($order_id,$inter_id);
                        if($grouponDetail['status'] == $GrouponModel::GROUP_STATUS_ING && $grouponDetail['join_count'] >= 2){   //第二充验证，验证是否满足释放条件
                            $GrouponModel->update_groupon_group_join($grouponDetail['group_id'],'release',$inter_id);  //释放一个人数
                            $GrouponModel->groupon_user_update($grouponDetail['group_id'],$order_id,$this->openid,$GrouponModel::GROUP_ADD_STATUS_EXPIRATION, null,$inter_id); //改变用户状态

                        }

                    }
               // echo "finished release";
            }

        }
        redirect(Soma_const_url::inst()->get_pacakge_home_page());
    }


    //展示为以后的皮肤做扩展
    protected function _view($file, $datas=array() )
    {
        parent::_view( 'package'. DS. $file, $datas);
    }

    /**
     * Ajax 拉取商品列表HTML
     * @deprecated
     */
    public function page_block_ajax()
    {
        $current_url= $this->input->get('u');
        $filter= array('inter_id'=> $this->inter_id);
        $this->load->model('soma/Cms_block_model');
        $this->load->model('soma/Product_package_model');
        $pids= $this->Cms_block_model->show_in_page($current_url, $filter);
        
        $products = array();
        if( $pids ){
            $products= $this->Product_package_model->get_product_package_by_ids($pids, $this->inter_id);
        }

        //获取酒店城市列表
        $this->load->model('hotel/hotel_model','HotelModel');
        $params = array(
            'inter_id'  => $this->inter_id
        );
        $HotelModel = $this->HotelModel;

        foreach($products as $k => $p){
            $productCites = $HotelModel->get_hotel_hash(array('inter_id'=>$this->inter_id,'hotel_id'=> $p['hotel_id']),array('city'),'array');
            $products[$k]['city'] = isset( $productCites[0]['city'] ) ? $productCites[0]['city'] : NULL;
        }

        //var_dump($pids);die;
        if( $pids && $products && count($products)>0 ){
            $html = '';
            if( $this->theme == 'default' ){

                $html= '<div id="load_page_block" class="tp_list bgcolor_fff border martop"><div style="padding-bottom:3%;padding-left:3%; margin-bottom:3%" class="border_bottom h2">其他用户还看了</div>';
                foreach ($products as $k=>$v ){
                    $url= Soma_const_url::inst()->get_url('*/*/package_detail', array('id'=>$this->inter_id, 'pid'=> $v['product_id']) );
                    $can_gift= ($v['can_gift']== Product_package_model::CAN_T)? '<div class="fn"><span>可赠好友</span></div>': '';
                    $default_pic= base_url('public/soma/images/default.jpg');
                    $html.= 
"<a href='{$url}' class='item'>
  <div class='img'><img src='{$v['face_img']}' />{$can_gift}</div>
  <p class='txtclip'>{$v['name']}</p>
  <div class='foot h2'>
      <p class='color_fff m_bg tp_price'>
      	<span>惊喜价</span>
  		<span class='y'>{$v['price_package']}</span>
          <span class='m_bg2'>去购买<em class='iconfont'>&#xe61b;</em></span>
      </p>
      <p class='tp_local txtclip'>{$v['city']}</p>
  </div>
</a>";
                }
                $html.= '</div>';

            } elseif( $this->theme == 'v1' ){
                $is_odd= ( count($products) % 2 )>0;
                if( $is_odd ) array_pop($products);
                
                $html= '<link href="'.base_url("public/soma/v1/v1.css"). config_item("css_debug").'" rel="stylesheet">
                <div id="load_page_block" class="tp_list bgcolor_fff border martop"><div style="padding-bottom:3%;padding-left:3%; margin-bottom:3%" class="border_bottom h2">其他用户还看了</div>';
                foreach ($products as $k=>$v ){
                    $url= Soma_const_url::inst()->get_url('*/*/package_detail', array('id'=>$this->inter_id, 'pid'=> $v['product_id']) );
                    $default_pic= base_url('public/soma/images/default.jpg');
                    $html.= 
"<a href='{$url}' class='item bg_fff'>
  <div class='img'>
      <img src='{$v['face_img']}' />
  </div>
  <p class='h3 color_888'>{$v['name']}</p>
  <p class='item_foot'>惊喜价<em>|</em><span class='color_main y'>{$v['price_package']}</span></p>
</a>";
                }
                $html.= '</div>';
            }
            echo $html;
            
        } else {
            echo '';
            
        }
    }


    /**
     * 月饼说皮肤配置
     */
    public function page_basic_config($pageTitle = NULL)
    {
        $title= '';
        $themeConfig = $this->themeConfig;

        $this->load->model('wx/publics_model');
        $public_info = $this->publics_model->get_public_by_id($this->inter_id);
        $_prefix = isset($public_info['name']) ? $public_info['name'] . '-' : '';

        if(!empty($themeConfig)){
            if( isset($themeConfig['theme_title']) && !empty($themeConfig['theme_title']) && empty($pageTitle) ) {
                if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' ){
                    $title = $_prefix . '月饼说 - '. $themeConfig['theme_title'];
                } else {
                    $title = '商城 - '. $themeConfig['theme_title'];
                }
            }
            if( empty($header) ){
                if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' ){
                    $title= $pageTitle? $pageTitle: $_prefix . '月饼说';
                } else {
                    $title= $pageTitle? $pageTitle: '商城';
                }
            }
            $header['title'] = $title; //$pageTitle;
            if(isset($themeConfig['main_color']) && !empty($themeConfig['main_color']))
                $header['main_color'] = $themeConfig['main_color'];
            if(isset($themeConfig['sub_color']) && !empty($themeConfig['sub_color']))
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
        $filter_cat= $this->input->get('fcid');
        $this->load->model('soma/Product_package_model','productModel');
        $products = $this->productModel->get_product_package_list($filter_cat, $this->inter_id);

        $result = $productIds = array();
        foreach($products as  $p){
            if( $p['is_hide'] == Soma_base::STATUS_TRUE ){
              $productIds[] = $p['product_id'];
              $result[$p['product_id']] = $p;
            }
        }

        //拼团列表
        $this->load->model('soma/Activity_groupon_model','activityGrouponModel');
        $groupons = $this->activityGrouponModel->groupon_list_by_productIds($productIds,$this->inter_id);
        foreach($groupons as $groupon){
            $result[$groupon['product_id']]['groupon'] = $groupon;
        }

        //秒杀列表
        $this->load->model('soma/Activity_killsec_model','activityKillsecModel');
        $killsecs = $this->activityKillsecModel->killsec_list_by_productIds($productIds,$this->inter_id);
        foreach($killsecs as $killsec){
            /** 对秒杀开始时间进行处理 */
            $killsec['killsec_time']= date('Y-m-d H:i:s', strtotime($killsec['killsec_time'])- Activity_killsec_model::PRESTART_TIME );
            $result[$killsec['product_id']]['killsec'] = $killsec;
        }

        $this->datas['products'] = $result;
        $this->datas['packageModel'] = $this->productModel;

        //点击分享之后开启这些按钮
        $js_menu_show = array( 'menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:copyUrl' );
        $uparams= $this->input->get()+ array('id'=> $this->inter_id);

        //取出分享配置
        $this->load->model( 'soma/Share_config_model', 'ShareConfigModel' );
        $ShareConfigModel = $this->ShareConfigModel;
        $position = $ShareConfigModel::POSITION_DEFAULT;//分享类型
        $share_config_detail = $ShareConfigModel->get_share_config_list( $position, $this->inter_id );
        $this->load->helper('soma/package');
        // write_log(json_encode( $share_config_detail ), 'share_config_detail.txt' );
        $default_share_config = $this->get_default_sharing();
        $share_config = array(
            'title'=> isset( $share_config_detail['share_title'] ) && !empty( $share_config_detail['share_title'] ) ? 
                $share_config_detail['share_title'] : $default_share_config['default_title'],
            'desc'=> isset( $share_config_detail['share_desc'] ) && !empty( $share_config_detail['share_desc'] ) ? 
                $share_config_detail['share_desc'] : $default_share_config['default_desc'],
            'link'=> Soma_const_url::inst()->get_share_url( $this->openid, '*/*/*', $uparams ),//$share_config_detail['share_link'],
            'imgUrl'=> isset( $share_config_detail['share_img'] ) && !empty( $share_config_detail['share_img'] ) ? 
                $share_config_detail['share_img'] : $default_share_config['share_img'],
        );

        $this->datas['filter_cat'] = $filter_cat;
        $this->datas['js_menu_show']= $js_menu_show;
        $this->datas['js_share_config']= $share_config;
        $this->datas['themeConfig'] = $this->themeConfig;
        // var_dump($this->themeConfig);exit;

        $this->_view("header",$header);
        $this->_view('search', $this->datas);

    }

    public function get_default_sharing()
    {
      if( defined('PROJECT_AREA') && PROJECT_AREA=='mooncake' ){
        $share_img = base_url('public/soma/images/sharing_mooncake.png');
        $default_title = '月饼说，送您一份中秋好礼物';
        $default_desc = '微信送礼更有趣';
      } else {
        $share_img = base_url('public/soma/images/sharing_package.png');
        $default_title = '发现一家好去处，快点开看看';
        $default_desc = '优惠不等人';
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
      echo json_encode( $return );exit;

      $saler = $this->input->post('saler');

      $return = array();
      $this->load->model('distribute/Staff_model');
      $staff= $this->Staff_model->get_my_base_info_openid( $this->inter_id, $this->openid );
      if( $staff && $staff['qrcode_id'] ){

        //查询链接携带的分销ID
        //$url_staff = $this->Staff_model->get_my_base_info_saler( $this->inter_id, $saler );
        $url_staff = $staff;

        $return['status'] = Soma_base::STATUS_TRUE;
        $return['message'] = '该用户是分销员';
        $return['sid'] = isset( $url_staff['qrcode_id'] ) ? $url_staff['qrcode_id'] : '';
        $return['name'] = isset( $url_staff['name'] ) ? $url_staff['name'] : '';
        
        if( empty($saler) ){
            //1,链接无saler,[跳转]
            $return['jump_url'] = Soma_base::STATUS_TRUE;
            $return['show_button'] = Soma_base::STATUS_FALSE;
            
        }else if( $saler!= $url_staff['qrcode_id'] ){
            //2,链接有saler,但与本人不符合,[跳转]
            $return['jump_url'] = Soma_base::STATUS_TRUE;
            $return['show_button'] = Soma_base::STATUS_FALSE;
            
        } else if( $saler== $url_staff['qrcode_id'] ){
            //3,连接有saler,并且符合,[显示角标]
            $return['jump_url'] = Soma_base::STATUS_FALSE;
            $return['show_button'] = Soma_base::STATUS_TRUE;
            
        } else {
            $return['jump_url'] = Soma_base::STATUS_FALSE;
            $return['show_button'] = Soma_base::STATUS_FALSE;
        }
        $return['url'] = Soma_const_url::inst()->get_url( 'distribute/dis_v1/mine', array('id'=>$this->inter_id) );
        
      } else {
        $return['status'] = Soma_base::STATUS_FALSE;
        $return['message'] = '该用户不是分销员';
        
      }
      echo json_encode( $return );
    }

    public function get_lvl_info_ajax()
    {
        $result= array('status'=> Soma_base::STATUS_FALSE, 'message'=>'会员身份识别有误，积分暂不能使用');
        
        $this->load->library('Soma/Api_member');
        $api= new Api_member($this->inter_id);
        $result= $api->get_token();
        $api->set_token($result['data']);
        $result= $api->member_lv_info($this->openid);
        
        if( isset($lvl_info['data']['member_lvl_id']) && $lvl_info['data']['member_lvl_id'] ){
            $result['status']= Soma_base::STATUS_TRUE;
            $result['message']= 'lvl_id:'. $lvl_info['data']['member_lvl_id'];
        }
        echo json_encode($result);
    }

    /**
     * 泛分销激活页面
     */
    public function fans_saler_active() {
        $this->_get_wx_userinfo();
        $header['title'] = '泛分销信息激活';
        $rtn_url = Soma_const_url::inst()->get_url('*/*/index', array('id'=>$this->inter_id));
        $this->datas['rtn_url'] = $rtn_url;
        $this->datas['t'] = base64_encode(urlencode($rtn_url).'***'.$this->openid.'***'.$this->inter_id);
        $this->_view("header",$header);
        $this->_view('fans_saler_active', $this->datas);
    }
}
