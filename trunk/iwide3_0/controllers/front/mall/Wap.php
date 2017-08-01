<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
header("Content-type:text/html;charset=utf-8");
if(ENVIRONMENT=='development') error_reporting(E_ALL);

class Wap extends MY_Front {
    
	public $datas;
	public $openid;
	public $share;
	public $saler;
	public $fans_id;
	public $theme;
	public $configs;

	public $topic;
	
	public $session_coupon;
	
protected function _get_domain()
{
return '';
//return 'http://ihotels.iwide.cn';
    $inter_id= $this->inter_id;
    $this->load->model('wx/publics_model');
    $model= $this->publics_model->get_public_by_id($inter_id);
    if(isset($model['domain']) && $model['domain'] ){
        $domain= $model['domain'];
    } else {
        $domain= 'ihotels.iwide.cn';
    }
    return 'http://'. $domain;
}

    public function __construct()
    {
        parent::__construct();
        //		$this->output->enable_profiler(true);
        $this->inter_id=$this->session->userdata('inter_id');
        $this->openid=$this->session->userdata($this->inter_id.'openid');
    
        $this->saler   = $this->input->get('saler');
        $this->fans_id = $this->input->get('f');
        if(!$this->saler) $this->saler = $this->input->post('saler');
        if(!$this->fans_id) $this->fans_id = $this->input->post('f');
        if(!$this->saler) $this->saler = $this->input->post('saler');
        if(!$this->fans_id) $this->fans_id = $this->input->post('f');
        $identity = $this->input->get('t');
        /** 解决首次打开，经微信授权跳转后丢失 get参数的问题  */
        //if( !$this->saler ) $this->saler= $this->session->userdata('topic_saler');
        //if( !$this->fans_id ) $this->fans_id= $this->session->userdata('topic_fans_id');
        if( !$identity ) $identity= $this->session->userdata('topic_identity');
        /** 解决首次打开，经微信授权跳转后丢失 get参数的问题  */
        
        //echo $identity;die;
    
        $this->load->model('mall/shp_topic');
        $topic_model= $this->shp_topic;
        if( $identity ){
            //从专题标识找到专题
            $topic = $this->shp_topic->find(array(
                'identity'=> $identity,
                'status'=> $topic_model::STATUS_T,
            ));
            $topic= !empty($topic)? $topic: array();
        }
    
        if( !$identity || !$topic || count($topic)==0){
            //找不到的情况下寻找公众号下直接对应的专题
            $topic= $this->shp_topic->get_default_topic(array('inter_id'=> $this->inter_id));
            $topic= !empty($topic[0])? $topic[0]: array();
        }
    
        if( !$topic || count($topic)==0){
            //还是找不到？
            die('该公众号无任何专题内容');
    
        } else {
            /** Array ( [0] => Array (
             [topic_id] => 5
             [identity] => weixin
             [hotel_id] => 171
             [inter_id] => a445091342
             [page_theme] => default
             [page_title] => 123
             [page_starttime] => 2015-11-19 17:06:06
             [page_endtime] => 2015-12-19 17:06:06
             [share_title] => fenxiang
             [share_link] => http://www.iwide.com
             [share_img] =>
             [share_desc] => fenxiang
             [share_title_gift] => fenxiang
             [share_link_gift] => http://www.iwide.com
             [share_img_gift] =>
             [share_desc_gift] =>
             [is_invoice] => 1
             [sort] => 2
             [status] => 0
            ) )
            */
            //print_r($topic);die;
            $this->topic= $topic;
            $this->hotel_id= $this->topic['hotel_id'];
            $this->theme= $this->topic['page_theme'];
            $page_title= $this->topic['page_title'];
    
            $this->share['title'] = $this->topic['share_title'];
            $this->share['link']  = $this->topic['share_link'];
            $this->share['imgUrl']= $this->_get_domain(). $this->topic['share_img'];
            $this->share['desc']  = $this->topic['share_desc'];
        }
        //print_r($this->topic);die;
    
        if(!$this->hotel_id){
            $this->hotel_id=$this->session->userdata($this->inter_id.'icard_hotel');
        } else {
            $this->session->set_userdata(array($this->inter_id.'icard_hotel'=> $this->hotel_id));
        }
    
//规则：如果自己是分销员，将覆盖传入分销ID
$this->load->model('distribute/staff_model');
$staff_info= $this->staff_model->find( array(
    'inter_id'=>$this->inter_id,
    'openid'=>$this->openid,
));
if( $staff_info ) $this->saler = $staff_info['qrcode_id'];
    
//根据coupon参数值取出优惠幅度
$this->session_coupon = $this->inter_id. '_'. $this->openid. '_coupon_arr';
$coupon_arr= $this->session->userdata($this->session_coupon);
if($coupon_arr){
    $this->datas['coupon_arr'] = $coupon_arr;
}

        $info= $this->session->userdata($this->inter_id.'fans_info');
        if(empty($info)){
            $this->load->model('mall/mine_model');
            $this->session->set_userdata($this->inter_id.'fans_info',
                $this->mine_model->get_fans_details($this->inter_id,$this->session->userdata($this->inter_id.'openid'))
            );
        }
    
        $fans_info = $this->session->userdata($this->inter_id.'fans_info');
        if (stripos ( $this->share['link'], '?' ) === FALSE)
            $this->share['link'] = $this->share['link'] . '?saler='. $this->saler. '&f=' .$fans_info['id'];
        else
            $this->share['link'] = $this->share['link'] . '&saler='. $this->saler. '&f=' .$fans_info['id'];
    
        $this->datas['inter_id']   = $this->inter_id;
        $this->datas['hotel']      = $this->hotel_id;
        $this->datas['title']      = $page_title;
        $this->datas['saler']      = $this->saler;
        $this->datas['fans_id']    = $this->fans_id;
        $this->datas['topic']      = $this->topic;
        $this->datas['theme']      = $this->topic['page_theme'];
        $this->datas['identity']   = $this->topic['identity'];
        $this->datas['share']      = $this->share;
        //print_r($this->share);die;
        $this->datas['signPackage']= $this->getSignPackage();
    
        $this->load->model('common/record_model');
        $this->record_model->visit_log(array(
            'openid'  => $this->openid,
            'inter_id'=> $this->session->userdata('inter_id'),
            'title'   => $page_title,
            'url'     => $_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI'],
            'visit_time'=> date('Y-m-d H:i:s'),
            'des'     => $this->topic['share_desc'],
        ));
    }

	/* Call Demo: http://tf.iwide.cn/mall/wap/topic?id=a429262687&t=iwide
	 * 默认主题入口
	 * 适用于：[default]; [less];
	 * */
	public function topic()
	{
	    $this->load->model('mall/shp_advs');
	    $this->load->model('mall/shp_topic');
	    $this->load->model('mall/shp_category');
		$topic_model= $this->shp_topic->load($this->topic['topic_id']);
		$this->datas['goods']= $topic_model->get_topic_link('goods');
		$this->datas['advs']= $topic_model->get_topic_link('advs', 'cate="1"');
		if( $this->theme == Shp_topic::THEME_CARD ) {
			$this->load->model('mall/shp_goods', 'goods_model');
			$cats= $topic_model->get_topic_link('category');
			$this->datas['categories'] = $cat_list= $cats;
			foreach($cat_list as $k=> $v){
				$cat_list[$k]['products']= $this->datas['sales'] = $this->goods_model
					->get_cat_goods($this->topic['topic_id'], $v['cat_id'], 0, 4);
			}
			$this->datas['news'] = $this->goods_model->get_new_goods($this->topic['topic_id'], null, 0, 4);
			$this->datas['sales'] = $this->goods_model->get_wellsales_goods($this->topic['topic_id'], null, 0, 4);
			$this->datas['cat_list']= $cat_list;
			$this->datas['shp_category'] = $this->shp_category;
		}
		if( $this->theme == Shp_topic::THEME_MULTI ) {
			$this->load->model('mall/shp_goods', 'goods_model');
			$cats= $topic_model->get_topic_link('category');
			$this->datas['categories'] = $cat_list= $cats;
			foreach($cat_list as $k=> $v){
				$cat_list[$k]['products']= $this->datas['sales'] = $this->goods_model
					->get_cat_goods($this->topic['topic_id'], $v['cat_id'], 0, 4);
			}
			$this->datas['sales'] = $this->goods_model->get_wellsales_goods($this->topic['topic_id'], null, 0, 4);
			$this->datas['promo'] = $this->goods_model->get_promotion_goods($this->topic['topic_id'], null, 0, 4);
			$this->datas['cat_list']= $cat_list;
			$this->datas['shp_category'] = $this->shp_category;
		}
		if( $this->theme == Shp_topic::THEME_SUIT ) {
    		$this->datas['advs2']= $topic_model->get_topic_link('advs', 'cate="2"');
    		$this->datas['advs3']= $topic_model->get_topic_link('advs', 'cate="3"');
			$this->load->model('mall/shp_goods', 'goods_model');
			$cats= $topic_model->get_topic_link('category');
			$this->datas['categories'] = $cat_list= $cats;
			foreach($cat_list as $k=> $v){
				$cat_list[$k]['products']= $this->datas['sales'] = $this->goods_model
					->get_cat_goods($this->topic['topic_id'], $v['cat_id'], 0, 4);
			}
			$this->datas['sales'] = $this->goods_model->get_wellsales_goods($this->topic['topic_id'], null, 0, 4);
			$this->datas['promo'] = $this->goods_model->get_promotion_goods($this->topic['topic_id'], null, 0, 4);
			$this->datas['cat_list']= $cat_list;
			$this->datas['shp_category'] = $this->shp_category;
		}
		$this->datas['shp_advs'] = $this->shp_advs;
        //print_r($this->datas);die;
		
		$this->_view($this->theme. '/header', $this->datas);
		$this->_view($this->theme. '/index');
		$this->_view($this->theme. '/public_share');
	}
	
	protected function _view($file, $datas=array() )
	{
	    $this->load->model('mall/shp_topic');
	    $path= 'mall'. DS. 'wap'. DS;
	    $search= array(
	        Shp_topic::THEME_CARD,
	        Shp_topic::THEME_MULTI,
	        Shp_topic::THEME_SUIT,
	        Shp_topic::THEME_DEFAULT,
	    );
	    $replace= array(
	        Shp_topic::THEME_LESS,
	        Shp_topic::THEME_LESS,
	        Shp_topic::THEME_LESS,
	        Shp_topic::THEME_LESS,
	    );
	    if( in_array($this->theme, $search) ){
	        if( !file_exists(VIEWPATH. $path. $file. '.php') ){
	            $file= str_replace($search , $replace, $file); 
	        }
	    }
	    //$this->load->view($path. $file, $datas);
	    $html = $this->load->view($path. $file, $datas,true);
	    
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
	    $html = str_replace($search, $replace, $html);
	    echo $html;
	    
	}
	
	
	/**
	 * 兼容函数
	 */
	public function index()
	{
	    $this->topic();
	}

	/**
	 * @deprecated 已丢弃此方法
	 *  Call Demo: http://tf.iwide.cn/mall/wap/topic?id=a429262687&h=1 */
	public function _index()
	{
		$this->load->model('mall/shp_goods', 'goods_model');
		if($this->theme == 'mall/wap/multi') {
			$this->datas['categories'] = $this->goods_model->get_goods_category($this->inter_id, $this->hotel_id);
			$this->datas['hots'] = $this->goods_model->get_hot_goods($this->inter_id, null, null, 0, 2);
			
		} elseif($this->theme == 'mall/wap/less') {
		    $filter= array('inter_id'=>$this->inter_id, 'hotel_id'=>$this->hotel_id);
			$this->datas['goods'] = $this->goods_model->get_good_records($filter);
		}
		
		//advs
		$this->load->model('mall/shp_advs','ads_model');
		
		//首页广告图 cate:0
		$this->datas['advs'] = $this->ads_model
		      ->get_ads_by_category(0, $this->inter_id, $this->hotel_id)->result();

		if(isset($this->configs['MALL_HOTEL_THRME']['val']))
			$this->datas['title'] = $this->configs['MALL_INDEX_TITLE']['val'];
		
		$this->_view($this->theme.'/header',$this->datas);
		$this->_view($this->theme.'/index');
		$this->_view($this->theme.'/public_share');
	}
	
/**
 * 商品详情页面
 */
public function goods_details()
{
	$goods_id = $this->uri->segment(4);
	$this->load->model('mall/shp_goods', 'goods_model');
	$this->datas['details'] = $this->goods_model->get_single_goods_details($goods_id );
	
	$this->load->model('mall/shp_cart', 'cart_model');
	$this->datas['cart_count'] = $this->cart_model->get_cart_product_count($this->openid,$this->inter_id,$this->hotel_id);
	
	$this->_view($this->theme.'/header',$this->datas);
	$this->_view($this->theme.'/goods_details');
	$this->_view($this->theme.'/public_share');
}

	/**
	 * 商品详情页面
	 * 适用于：[default]; 
	 */
	public function goods_buy()
	{
		$goods_id = $this->uri->segment(4);
		$this->load->model('mall/shp_goods', 'goods_model');
		
		$this->datas['details'] = $this->goods_model->get_single_goods_details($goods_id );
		$this->datas['gallery'] = $this->goods_model->get_single_goods_gallery($goods_id);
//print_r($this->datas['details']);die;

		$this->load->model('mall/shp_cart', 'cart_model');
		$this->datas['cart_count'] = $this->cart_model->get_cart_product_count($this->openid,$this->inter_id,$this->hotel_id);

		$this->_view($this->theme.'/header',$this->datas);
		$this->_view($this->theme.'/im_buy');
		$this->_view($this->theme.'/public_share');
	}
	
	/**
	 * 产品列表/更多产品
	 * 适用于：[multi];
	 */
	public function plist()
	{
		$cat_id = $this->uri->segment(4);
		$type = $this->uri->segment(5);
		$saler= $this->saler? $this->saler: $this->input->get('saler');
		if( empty($cat_id) ) {
			redirect(site_url('mall/wap/topic').'?id='.$this->inter_id.'&t='.$this->topic['identity'].'&saler='.$saler.'&f='.$this->fans_id );
		
		} else {
		    $this->load->model('mall/shp_goods');
		    if( $cat_id== 'all' && empty($type) ) {
		    	//特征不过滤，分类不过滤
		        $products= $this->shp_goods->get_cat_goods( $this->topic['topic_id'], $cat_id );
		        $title= '全部商品';
		        	
		    } else if( !empty($type)) {
		    	//特征过滤，分类不过滤
		    	if($cat_id=='all') $cat_id= NULL;
		    	//特征过滤，分类过滤
		    	switch ($type) {
		    		case 'promo':
		    			$title= '促销商品';
		    			$products= $this->shp_goods->get_promotion_goods( $this->topic['topic_id'], $cat_id );
		    			break;
		    		case 'sales':
		    			$title= '畅销商品';
		    			$products= $this->shp_goods->get_wellsales_goods( $this->topic['topic_id'], $cat_id );
		    			break;
		    		case 'hot':
		    			$title= '热销商品';
		    			$products= $this->shp_goods->get_hot_goods( $this->topic['topic_id'], $cat_id );
		    			break;
		    		case 'news':
		    			$title= '首发新品';
		    			$products= $this->shp_goods->get_new_goods( $this->topic['topic_id'], $cat_id );
		    			break;
		    		default:
		    			$title= '商品列表';
		    			$products= array();
		    			break;
		    	}
		    } else {
		    	//特征不过滤，分类过滤
		    	$this->load->model('mall/shp_category');
		    	$title= $this->shp_category->load($cat_id)->m_get('cat_name');
		    	$products= $this->shp_goods->get_cat_goods( $this->topic['topic_id'], $cat_id );
		    }
			$this->datas['title'] = $title;
			$this->datas['products'] = $products;
			$this->_view($this->theme.'/header', $this->datas);
			$this->_view($this->theme.'/list' );
		}
	}
	/**
	 * 产品列表（旧）
	 * 适用于：[multi];
	 * @deprecated 不兼容热销/促销产品，已废弃
	 */
	public function products()
	{
		$category = $this->uri->segment(4);
		$saler= $this->saler? $this->saler: $this->input->get('saler');
		if(empty($category)) {
			redirect(site_url('mall/wap/topic').'?id='.$this->inter_id.'&t='.$this->topic['identity'].'&saler='.$saler.'&f='.$this->fans_id );
		
		} else {
		    $filter= array(
		        $this->inter_id,
		        $this->hotel_id,
		        $category,
		    );
			$this->load->model('mall/shp_goods', 'goods_model');
			$this->datas['products'] = $this->goods_model->get_good_records($filter);
			$this->_view($this->theme.'/header', $this->datas);
			$this->_view($this->theme.'/list' );
		}
	}
	
	/**
	 * 我的购物车
	 * 适用于：[less];
	 */
	public function cart()
	{
		$this->load->model('mall/shp_cart', 'cart_model');
		$products = $this->cart_model->get_cart_products($this->openid, $this->inter_id,$this->hotel_id);
		$this->datas['products'] = $products->result_array();
		//print_r($this->datas['products']);die;
		
		$this->_view($this->theme.'/header',$this->datas);
		$this->_view($this->theme.'/shop_car');
		$this->_view($this->theme.'/public_share');
	}
	
	/**
	 * 购物车购买确认
	 * 适用于：[less];
	 */
// 	public function __ccon_firm()
// 	{
// 		$rec_products = $this->input->post('sps');
// 		$rec_ps_arr = json_decode($rec_products,true);
// 		$saler= $this->saler? $this->saler: $this->input->get('saler');
// 		if(empty($rec_ps_arr)){
// 			redirect(site_url('mall/wap/cart').'?id='.$this->inter_id.'&t='.$this->topic['identity'].'&saler='.$saler.'&f='.$this->fans_id);exit();
// 		}
// 		$this->load->model('mall/shp_goods', 'goods_model');
// 		$this->datas['products'] = $this->goods_model->get_products_by_ids(array_keys($rec_ps_arr) );
		
// 		$this->datas['pjson']    = $rec_ps_arr;
// 		$this->_view($this->theme.'/header',$this->datas);
// 		$this->_view($this->theme.'/pay');
// 		$this->_view($this->theme.'/public_share');
// 	}

	public function cconfirm()
	{
	    $rec_products = $this->input->post('sps');
	    $rec_ps_arr = json_decode($rec_products,true);
	    $saler= $this->saler? $this->saler: $this->input->get('saler');
	    if(empty($rec_ps_arr)){
	        redirect(site_url('mall/wap/cart').'?id='.$this->inter_id.'&t='.$this->topic['identity'].'&saler='.$saler.'&f='.$this->fans_id);exit();
	    }
	    $this->load->model('mall/shp_goods', 'goods_model');
        //print_r($rec_ps_arr);die;   //Array ( [12] => 1 ) gs_id=> 数量
	    $goods = $this->goods_model->get_good_records( array('gs_id'=> array_keys($rec_ps_arr) ) );

	    $can_gift= $can_pickup= TRUE;
	    $item_mail= $item_other= $item_all= array();
	    foreach ($goods as $v){
	        $item_all[$v['gs_id']] = $v;
	        if($v['can_mail']==EA_base::STATUS_TRUE_){
	            $item_mail[$v['gs_id']]= $v+ array('qty'=> $rec_ps_arr[$v['gs_id']] );
	            if($v['can_gift']==EA_base::STATUS_FALSE_) $can_gift= FALSE;
	            if($v['can_pickup']==EA_base::STATUS_FALSE_) $can_pickup= FALSE;
	            
	        } else {
	            $item_other[$v['gs_id']]= $v+ array('qty'=> $rec_ps_arr[$v['gs_id']] );
	        }
	    }
	    if(count($item_mail)>0 && count($item_other)>0  ){
	        $is_ext_order= TRUE;
	    } else {
	        $is_ext_order= FALSE;
	    }
	    
	    $this->datas['is_ext_order']= $is_ext_order;
	    $this->datas['item_mail']= $item_mail;
	    $this->datas['can_gift']= $can_gift;
	    $this->datas['can_pickup']= $can_pickup;
	    $this->datas['item_other']= $item_other;
	    
	    $this->datas['products'] = $item_all;
	    $this->datas['pjson']    = $rec_ps_arr;

	    $this->load->model('mall/shp_order_items');
	    $this->_view($this->theme.'/header',$this->datas);
	    $this->_view($this->theme.'/pay');
	    $this->_view($this->theme.'/public_share');
	}
	
	/**
	 * 立即购买确认
	 * 适用于：[default]; 
	 */
// 	public function __no_confirm()
// 	{
// 		//$this->output->enable_profiler(true)
// 	    $this->load->model('mall/shp_topic');
// 		$topic_model= $this->shp_topic->load($this->topic['topic_id']);

// 		$goods_id = $this->input->get_post('key');
// 		$goods_count = $this->input->get_post('count');
		
// 		$this->load->model('mall/shp_goods', 'goods_model');
// 		$this->datas['details'] = $this->goods_model->get_single_goods_details($goods_id );
// 		$this->datas['details']['nums'] = $goods_count;
		
// 		$this->_view($this->theme. '/header', $this->datas);
// 		$this->_view($this->theme. '/pay');
// 		$this->_view($this->theme. '/public_share');
// 	}
	public function noconfirm()
	{
		//$this->output->enable_profiler(true)
	    $this->load->model('mall/shp_topic');
		$topic_model= $this->shp_topic->load($this->topic['topic_id']);

		$goods_id = $this->input->get_post('key');
		$goods_count = $this->input->get_post('count');
		
		$this->load->model('mall/shp_goods', 'goods_model');
        //print_r($rec_ps_arr);die;   //Array ( [12] => 1 ) gs_id=> 数量
	    $goods = $this->goods_model->load( $goods_id )->m_data();

		$this->datas['details'] = $goods;
		$this->datas['details']['nums'] = $goods_count;
        //print_r($goods);die;
		
		$this->_view($this->theme. '/header', $this->datas);
		$this->_view($this->theme. '/pay');
		$this->_view($this->theme. '/public_share');
	}
	
    /**
     * 优惠码使用（未完成）
     */
	public function coupon_apply()
	{
	    $return= array('status'=>2, 'message'=>'校验码超时。');
	    try {
	        $this->load->helper('encrypt');
	        $encrypt_util= new Encrypt();
	        $token= $encrypt_util->encrypt($this->openid. date('YmdH') );
	         
	        $key= $this->input->post('key');
	        $code= $this->input->post('code');
	        if($key && $key==$token){

	            //校验优惠码可否使用，返回一行规则记录，
	            $result= array(
	                'code'=> '123456',
	                'discount'=> '20',
	            );
	            
	            if( isset($result['status']) && $result['status']==1){
	                $return['status']= 1;
	                $return['message']= $result['message'];
	
	                //记录优惠码信息到session当中
                    $this->session->unset_userdata($this->session_coupon);
	
	            } else {
    	            $return['message']= $result['message'];
	            }
	        }
	        echo json_encode($return);
	        
	    } catch (Exception $e) {
	        //echo $e->getMessage();
	        $return['message']= '处理过程出现问题！';
	        echo json_encode($return);
	    }
	}
	
	public function coupon_cancel()
	{
	    $return= array('status'=>2, 'message'=>'取消优惠券失败。');
	    try {
            $this->session->unset_userdata($this->session_coupon);
            $return['status']= '1';
            $return['message']= '成功取消优惠券。';
            echo json_encode($return);
	        
        } catch (Exception $e) {
            //echo $e->getMessage();
            $return['message']= '处理过程出现问题！';
            echo json_encode($return);
        }
	}
	
	/**
	 * 地址列表
	 */
	public function addresses()
	{
		$order_id = $this->uri->segment(4);
		$this->load->model('mall/mine_model');
		$addresses = $this->mine_model->get_address($this->inter_id,$this->hotel_id,$this->openid);
		$this->datas['addresses'] = $addresses;
		$this->datas['order_id'] = $order_id;
		$this->_view($this->theme.'/header',$this->datas);
		$this->_view($this->theme.'/address');
		$this->_view($this->theme.'/public_share');
	}
	/**
	 * 编辑、新增地址
	 */
	public function address_edit()
	{
		$from_segment = $this->uri->segment(4);
		$aid = $this->input->get('oaid',true);
		if($aid){
			$this->datas['address'] = $this->mine_model->get_single_address($this->inter_id,$this->hotel_id,$this->openid,$aid);
		}
		$this->datas['from_seg'] = $from_segment;
		$this->_view($this->theme.'/header',$this->datas);
		$this->_view($this->theme.'/address_edit');
		$this->_view($this->theme.'/public_share');
	}
	/**
	 * 支付成功页面
	 * 'kargomall.iwide.cn/mall/wap/pay_success/1177?id=a453956624&t=m2ah88'
	 */
	public function pay_success()
	{
		$oid = $this->uri->segment(4);
		$this->datas['oid'] = $oid;
		
		$this->load->model('mall/shp_orders', 'orders_model');
		$this->load->model('mall/shp_order_items', 'items_model');
		$orders_model= $this->orders_model;
		
		$order = $this->orders_model->find( array('order_id'=>$oid, 'pay_status'=> $orders_model::PAYMENT_T) );
		$items= $this->items_model->find_all( array( 'order_id'=>$oid ) );
		
		$this->load->helper ( 'phpqrcode' );

		//生成并写入二维码
		if(isset($order['out_trade_no']) ){
		    $en_order_no= $this->orders_model->qr_order_no($order['out_trade_no']);
		    $qrcode_name= $this->inter_id. '_'. $order['out_trade_no']. '_'. $en_order_no;
		    $url= $this->_get_qrcode_png($order['out_trade_no'], $qrcode_name, 5, 1);
		    $this->orders_model->load($oid)->m_set('qrcode_url', $url)->m_save();
		}
		//$path= FCPATH. DS. FD_PUBLIC. DS. 'qrcode'. DS. 'mall'. DS. 'wap'. DS. 'pay_success'. DS;
        //$file= $path. $this->inter_id. '_'. $order['out_trade_no']. '.png';
        
		//对于部分对接接口的客户需要回写订单/写回支付状态
		if( $this->inter_id=='a453956624' ){
		    if($order && $order['transaction_id']){
		        $payment= TRUE;
    		    $this->load->library('Mall/Lib_kargo');
    		    $result= Lib_kargo::inst()->order_create($order, $items, $payment);
    		    if($result){
    		        //获取单号卡购的单号（以供查询之用）和key（解密卡号之用），写入订单中
    		        $this->orders_model->load($order['order_id'])->m_save( array(
    		            'out_order_id'=> $result['kc-ord-id'],
    		            'out_order_key'=> $result['kc-ord-key'],
    		        ) );
    		    }
		    }
		}
		$this->_view($this->theme.'/header',$this->datas);
		$this->_view($this->theme.'/pay_success');
		$this->_view($this->theme.'/public_share');
	}
	
	
	/**
	 * 支付失败页面
	 */
	public function pay_error()
	{
		$oid = $this->uri->segment(4);
		$this->datas['oid'] = $oid;
        //echo '支付流程已被终止。';
		redirect(site_url('mall/wap/my_orders').'?id='.$this->inter_id.'&t='.$this->topic['identity'].'&saler='.$saler.'&f='.$this->fans_id );
	}
	/**
	 * 我的订单列表
	 * 适用于：[default]; [less];
	 */
	public function my_orders()
	{
		$this->load->model('mall/shp_orders', 'orders_model');
		$this->load->model('mall/shp_order_items', 'items_model');
		$this->datas['orders'] = $this->orders_model->get_my_orders($this->inter_id,$this->openid);
		$this->datas['orders_model']= $this->orders_model;
		$this->datas['items_model']= $this->items_model;
		$this->datas['my_openid']= $this->openid;
	 
		if( $this->inter_id=='a453956624' ){
		    //print_r($this->datas['orders']);
		}
        
        //把gift_log表中内容取出，用来匹配中间转赠人的归属订单
		$this->load->model('mall/shp_gift_log', 'gift_log');
		$gift_log= $this->gift_log;
		$filter= array('ge_openid'=> $this->openid);
		$status= array( $gift_log::STATUS_GETTED, $gift_log::STATUS_GIFTING );
		$logs1= $gift_log->get_filter_log($filter, $status);
        $logs1= $gift_log->array_to_hash($logs1, 'ge_openid', 'order_id');

        $filter= array('gt_openid'=> $this->openid);
        $status= array( $gift_log::STATUS_GETTED );
        $logs2= $gift_log->get_filter_log($filter, $status);
        $logs2= $gift_log->array_to_hash($logs2, 'ge_openid', 'order_id');
        
        //array('orderid'=>'openid',.....) 获取某个openid接收到的订单
		$this->datas['my_gift_order1']= array_keys($logs1); 
		$this->datas['my_gift_order2']= array_keys($logs2); 
		
//print_r(array_keys($logs2));die;
        /** 获取接收礼物订单列表  **/
		$gift_order= $pid= array();
		if( count(array_keys($logs2))>0 ){
    		$gift_tmp= $this->orders_model->get_data_filter( array('order_id'=> array_keys($logs2) ) );
    		foreach ($gift_tmp as $k=>$v){
    		    $gift_order[$v['order_id']]= $v;
    		}
    		krsort($gift_order); 
    		//if( $this->inter_id=='a453956624' ){
    		//    print_r($gift_order); 
    		//}
    		$gift_tmp= $this->items_model->get_data_filter( array('order_id'=> array_keys($logs2) ) );
    		foreach ($gift_tmp as $k=>$v){
	            $pid[]= $v['gs_id']; 
    	        if( isset( $gift_order[$v['order_id']]['items'][$v['gs_id']]) ) 
    	            $gift_order[$v['order_id']]['items'][$v['gs_id']]['nums']++;
    	        else $gift_order[$v['order_id']]['items'][$v['gs_id']]= $v+ array('nums'=>1);
    		}
            //print_r($gift_order);die;
    		$this->datas['my_gift_order']= $gift_order;
		}
		
		/** 获取接收礼物订单列表  **/

		$this->load->model('mall/shp_goods', 'goods_model');
		$this->datas['goods']= $this->goods_model->get_products_by_ids($pid);
		$this->datas['goods_model']= $this->goods_model;
		
		$this->_view($this->theme.'/header',$this->datas);
		$this->_view($this->theme.'/order');
		$this->_view($this->theme.'/public_share');
	}
	/**
	 * 我的订单列表 => 订单详情
	 * 适用于：[default]; [less];
	 */
// 	public function __order__details()
// 	{
// 		$oid = $this->uri->segment(4);
// 		$this->load->model('mall/shp_orders', 'orders_model');
// 		$this->load->model('mall/shp_order_items', 'items_model');
// 		$this->datas['openid'] = $this->openid;
// 		$this->datas['orders'] = $this->orders_model->get_order_details($this->inter_id,null,$oid);
// 		$this->datas['items_model']= $this->items_model;
		
// 		$this->_view($this->theme.'/header',$this->datas);
// 		$this->_view($this->theme.'/order_detail');
// 		$this->_view($this->theme.'/public_share');
// 	}
	public function order_details()
	{
		$oid = $this->uri->segment(4);
		$this->load->model('mall/shp_orders', 'orders_model');
		$this->load->model('mall/shp_order_items', 'items_model');
		$this->load->model('mall/shp_goods', 'goods_model');
		$this->load->model('mall/shp_gift_log', 'gift_log');
		$item_model= $this->items_model;
		$orders_model= $this->orders_model;
		$goods_model= $this->goods_model;
		$gift_log= $this->gift_log;
		
		$this->datas['openid'] = $this->openid;
		$this->datas['order'] = $this->orders_model->find( array('order_id'=>$oid, 'pay_status'=>$orders_model::PAYMENT_T) );
		$this->datas['items']= $this->items_model->find_all( array( 'order_id'=>$oid ) );
		
		$gift_status= '';
		$can_mail= $can_pickup= $can_gift= TRUE;
		$pid= $item_mail= $item_other= array();
		
		foreach ($this->datas['items'] as $v){
		    if( $v['status']!= $item_model::STATUS_DEFAULT || $v['can_gift']== $goods_model::STATUS_F  )
		        $can_gift= FALSE; //凡部分货品已经处理将无法进行赠送
		    
		    $pid[]= $v['gs_id'];
		    if($v['can_mail']==EA_base::STATUS_TRUE_){
		        if( isset($item_mail[$v['gs_id']]) ) $item_mail[$v['gs_id']]['qty']++;
		        else $item_mail[$v['gs_id']]= $v+ array('qty'=>1);
		        
		        //判断能否在列表页再做邮寄处理
		        if($v['status']!= $item_model::STATUS_DEFAULT) $can_mail=FALSE;
		    } else {
		        if( isset($item_other[$v['gs_id']]) ) $item_other[$v['gs_id']]['qty']++;
		        else $item_other[$v['gs_id']]= $v+ array('qty'=>1);
		        
		        //判断能否在列表页再做自提处理
		        if($v['status']!= $item_model::STATUS_DEFAULT) $can_pickup=FALSE;
		    }
		    
		    //有成功赠送记录，即不能处理
		    $filter= array('ge_openid'=> $this->openid);
		    $status= array( $gift_log::STATUS_GETTED );
		    $logs2_= $gift_log->get_filter_log($filter, $status);
		    $logs2= $gift_log->array_to_hash($logs2_, 'ge_openid', 'order_id');
		    
		    
		    //判断一旦有商品处于赠送状态，则显示已赠送
	        $this->load->model('wx/publics_model');
		    if( in_array($v['status'], array($item_model::STATUS_GIFTING, $item_model::STATUS_GIFTED) )
                || in_array($this->datas['order']['order_id'], array_keys($logs2) ) 
		        ){
		        if($v['status']==$item_model::STATUS_GIFTING) {
		            $logs= $this->gift_log->find_all( array('order_id'=>$oid, 'ge_openid'=>$this->openid, 'status'=> $gift_log::STATUS_GIFTING ) );
		            if( isset($logs[0]) ){
		                $gid= $logs[0]['gt_openid'];
		                $fans_info= $this->publics_model->get_fans_info( $gid );
		                $nickname= $fans_info['nickname'];
		            }
		            else $nickname= '';
		            $gift_status= '订单正在赠送好友 '. $nickname;
		        }
		        
		        if($gift_status=='') {

		            //兼容送回给自己
		            $filter= array('order_id'=> $oid);
		            $status= array( $gift_log::STATUS_GETTED );
		            $logs3= $gift_log->get_last_filter_log($filter, $status);
		            if( count($logs3)>0 && $logs3[0]['gt_openid']== $this->openid ){
		                $gift_status= '';
		                 
		            } else {
    		            $logs= $this->gift_log->find_all( array('order_id'=>$oid, 'ge_openid'=>$this->openid, 'status'=> $gift_log::STATUS_GETTED ) );
    		            if( isset($logs[0]) ){
    		                $gid= $logs[0]['gt_openid'];
    		                $fans_info= $this->publics_model->get_fans_info( $gid );
    		                $nickname= $fans_info['nickname'];
    		            }
    		            else $nickname= '';
    		            $gift_status= '订单已赠送好友'. $nickname;
		            }
		        }
		    }
		}
		$this->datas['items_mail']= $item_mail;
		$this->datas['items_other']= $item_other;
		$this->datas['item_model']= $item_model;
		$this->datas['can_mail']= $can_mail;
		$this->datas['can_gift']= $can_gift;
		$this->datas['can_pickup']= $can_pickup;
		$this->datas['gift_status']= $gift_status;
		
		//获取几个商品的详细信息（带扩展字段）数组键值为gs_id方便调取
		$this->datas['goods']= $this->goods_model->get_products_by_ids($pid);
		$this->datas['gift_log']= $this->gift_log->find_all( array('order_id'=>$oid) );
//print_r($this->datas);die;
		$this->_view($this->theme.'/header',$this->datas);
		$this->_view($this->theme.'/order_detail');
		$this->_view($this->theme.'/public_share');
	}
	
	public function share_order()
	{
		$type = 0;//0:order,1:item
	}
	public function cancel_share() { }
	public function update_share() { }
	
	/**
	 * 我的订单列表 => 订单详情=> 处理订单（订单邮寄）
	 * 适用于：[default]; [less];
	 */
// 	public function __mail__order()
// 	{
// 		$order_id = $this->uri->segment(4);
// 		$saler= $this->saler? $this->saler: $this->input->get('saler');
// 		if($this->input->get('roid',true)){
// 			$order_id = $this->input->get('roid',true); 
// 		}
// 		if(empty($order_id)){//没找到订单信息，跳回到首页
// 			redirect(site_url('mall/wap/topic').'?id='.$this->inter_id.'&t='.$this->topic['identity'].'&saler='.$saler.'&f='.$this->fans_id);
// 			exit();
// 		}
		
// 		$address_id = $this->uri->segment(5);
// 		//选择完邮寄地址，naid参数传回
// 		if($this->input->get('naid',true)){
// 			$address_id = $this->input->get('naid',true);
// 		}
		
// 		$this->load->model('mall/shp_orders', 'orders_model');
// 		$orders_model= $this->orders_model;
// 		$order_infos = $orders_model->get_order_details($this->inter_id,$this->hotel_id,$order_id);
// 		if(!$order_infos){//没找到订单信息，跳回到首页
// 			redirect(site_url('mall/wap/topic').'?id='.$this->inter_id.'&t='.$this->topic['identity'].'&saler='.$saler.'&f='.$this->fans_id);
// 			exit();
// 		}
// 		if($order_infos['status'] > 0 || ($order_infos['items'][0]['status'] == 0 && $order_infos['items'][0]['openid'] != $this->openid)){//
// 			redirect(site_url('mall/wap/order_details/'.$order_id).'?id='.$this->inter_id.'&t='.$this->topic['identity'].'&saler='.$saler.'&f='.$this->fans_id);
// 			exit();
// 		}
// 		if($order_infos['items'][0]['status'] == 0){
			
// 			$this->load->model('mall/mine_model');
// 			$my_info = $this->mine_model->get_openid_info($this->inter_id,$this->openid);
			
// 			$this->load->helper('guid');
// 			$this->datas['guid'] = Guid::toString();
			
// //print_r($this->topic);die;
			
// /********* 分享开始  *********/
// $this->datas['signPackage'] = $this->getSignPackage();
// $public=$this->db->get_where('publics', array('inter_id'=>$this->inter_id))->row_array();

// $this->share['title'] = $my_info['nickname']. '送您一份小礼物';//Index
// if(isset($this->topic['share_title_gift']))
// 	$this->share['title'] = $my_info['nickname']. $this->topic['share_title_gift'];

// $fans_info = $this->session->userdata($this->inter_id. 'fans_info');
// $this->share['link'] = site_url('mall/wap/receive/'.$order_id.'/order/'.$this->datas['guid']). '?id='. $this->inter_id
//     .'&t='. $this->topic['identity']. '&scope=snsapi_userinfo&saler='. $this->saler.'&f='. $fans_info['id'];
// // if( isset( $this->topic['share_link_gift'] ) ){
// // 	if (stripos ( $this->topic['share_link_gift'], '?' ) === FALSE)
// // 		$this->share['link'] = $this->topic['share_link_gift'] . '?saler='. $this->saler.'&f=' .$fans_info['id']; 
// // 	else
// // 		$this->share['link'] = $this->topic['share_link_gift'] . '&saler='. $this->saler.'&f=' .$fans_info['id'];
// // }

// $this->share['imgUrl']  = base_url('public/mall/default/images/box.png');
// if( $this->topic['share_img_gift'] )
// 	$this->share['imgUrl'] = $this->_get_domain(). $this->topic['share_img_gift'];

// $this->share['desc']    = '小声告诉你，嘘！已经付过钱了，全国包邮，快快领取吧';//Index
// if(isset($this->topic['share_desc_gift']))
// 	$this->share['desc'] = $this->topic['share_desc_gift'];

// $this->share['type']    = '';
// $this->share['dataUrl'] = '';

// //$this->vars('share',$this->share);
// $this->datas['share'] = $this->share;
// /********* 分享结束  *********/

// //($this->datas['share']);die;
// 			$address = '';
// 			$this->load->model('mall/mine_model');
			
// 			if($address_id){
// 				$address = $this->mine_model->get_single_address($this->inter_id,$this->hotel_id,$this->openid,$address_id);
// 			}
// 			if(empty($address)){
// 				$address = $this->mine_model->rand_single_address($this->inter_id,$this->hotel_id,$this->openid);
// 			}
// //var_dump($address);die;
// 			$itemc = '';
// 			$can_mail= $can_pickup= TRUE;
// 			foreach ($order_infos['items'] as $item){
// 				$itemc .= $item['id']. ',';
// 				//判断能否邮寄？能否到店核销
// 				if( $item['can_mail']== $orders_model::CAN_MAIL_F ){
// 				    $can_mail= FALSE;
// 				}
// 				if( $item['can_pickup']== $orders_model::CAN_PICKUP_F ){
// 				    $can_pickup= FALSE;
// 				}
// 			}
// 			$itemc = substr ( $itemc , 0 , -1);
// 			$this->datas['order_info'] = $order_infos;
// 			$this->datas['address'] = $address;
// 			$this->datas['itemc'] = $itemc;
// 			$this->datas['oid'] = $order_id;
// 			$this->datas['can_mail'] = $can_mail;
// 			$this->datas['can_pickup'] = $can_pickup;
			
// 			//$path= FCPATH. DS. FD_PUBLIC. DS. 'qrcode'. DS. 'mall'. DS. 'wap'. DS. 'pay_success'. DS;
// 			$qr_path= base_url('/'. FD_PUBLIC. '/qrcode/mall/wap/pay_success');
// 			$this->datas['qrcode'] = $qr_path. '/'. $this->inter_id. '_'. $order_infos['out_trade_no']. '.png';
			
// 			$this->_view($this->theme.'/header',$this->datas);
// 			$this->_view($this->theme.'/mail');
			
// 		} else {
// 			redirect(site_url('mall/wap/order_status/'.$order_id).'?id='.$this->inter_id.'&t='.$this->topic['identity'].'&saler='.$this->saler.'&f='.$this->fans_id);
// 		}
// 	}

	public function mail_order()
	{
	    $order_id = $this->uri->segment(4);
	    $saler= $this->saler? $this->saler: $this->input->get('saler');
	    if($this->input->get('roid',true)){
	        $order_id = $this->input->get('roid',true);
	    }
	    if(empty($order_id)){//没找到订单信息，跳回到首页
	        redirect(site_url('mall/wap/topic'). '?id='. $this->inter_id
	           . '&t='. $this->topic['identity']. '&saler='. $saler. '&f='. $this->fans_id);
	        exit();
	    }

	    //处理订单入口分为 <可邮寄:1> 和 <自提:2> 2种，不指定直接跳走
	    $mail_type= $this->input->get('mail');
	    if( !$mail_type ){
	        $mail_type= 1;
	        //redirect(site_url('mall/wap/topic'). '?id='. $this->inter_id
	        //   . '&t='. $this->topic['identity']. '&saler='. $saler. '&f='. $this->fans_id);
	        //exit();
	    }

	    $this->load->model('mall/shp_orders', 'orders_model');
	    $this->load->model('mall/shp_order_items', 'items_model');
	    $this->load->model('mall/shp_goods', 'goods_model');
	    $orders_model= $this->orders_model;
	    $items_model= $this->items_model;
	    $goods_model= $this->goods_model;
	    $this->datas['orders_model'] = $orders_model;
	    $this->datas['items_model'] = $items_model;
	    $this->datas['goods_model'] = $goods_model;
	    
	    $order_infos = $this->orders_model->find( array('order_id'=>$order_id, 'pay_status'=>$orders_model::PAYMENT_T ) );
	    $order_items = $this->items_model->find_all( array('order_id'=>$order_id, 'status'=>$items_model::STATUS_DEFAULT ) );
	    $check_items = $this->items_model->find_all( array('order_id'=>$order_id ) );
	    
	    $can_gift= TRUE; //能否赠送需要根据整个订单的处理情况来决定，因为当前赠送是采取整单赠送原则
	    $pid= $item_mail= $item_other= $item_all= array();
	    foreach ($check_items as $v){
	        if( $v['status']!= $items_model::STATUS_DEFAULT )
	            $can_gift= FALSE; //凡部分货品已经处理将无法进行赠送
	    }
	    foreach ($order_items as $v){
	        //用此code可查看item的核销二维码。
	        $v['consume_code']= $this->_gen_consume_code($order_id, $v['id']);
	        $pid[]= $v['gs_id'];
	        
	        if($v['can_mail']==EA_base::STATUS_TRUE_){
	            if( isset($item_mail[$v['gs_id']]) ) $item_mail[$v['gs_id']]['qty']++;
	            else $item_mail[$v['gs_id']]= $v+ array('qty'=>1);
	            
	        } else {
	            if( isset($item_other[$v['gs_id']]) ) $item_other[$v['gs_id']]['qty']++;
	            else $item_other[$v['gs_id']]= $v+ array('qty'=>1);
	        }
	        
	        if( isset($item_all[$v['gs_id']]) ) $item_all[$v['gs_id']]['qty']++;
	        else $item_all[$v['gs_id']]= $v+ array('qty'=>1);
	    }

	    //没找到订单信息，跳回到首页
	    if(!$order_infos){
	        redirect(site_url('mall/wap/topic'). '?id='. $this->inter_id
	           . '&t='.$this->topic['identity'] .'&saler='. $saler. '&f='. $this->fans_id );
	        exit();
	    }

	    //已经处理完的订单/邮寄、自提单品已经处理完，跳转到下一步，3为查看赠送状态
	    if( ( $mail_type==1 && count($item_mail)==0 ) || ( $mail_type==3 ) ){
	        redirect(site_url('mall/wap/order_status/'. $order_id) .'?id=' .$this->inter_id. '&mail='. $mail_type
	           .'&t='.$this->topic['identity'] .'&saler=' .$saler .'&f=' .$this->fans_id);
	        exit();
	    }
	    
	    if( $order_infos['status'] != $orders_model::STATUS_GIFTED  ){

	        $this->load->model('wx/publics_model');
	        $this->load->model('mall/mine_model');
	        $my_info = $this->mine_model->get_openid_info($this->inter_id,$this->openid);
	        	
	        $this->load->helper('guid');
	        $this->datas['guid'] = Guid::toString();
	        
	        //print_r($this->topic);die;
	        
/********* 分享开始  *********/
$this->datas['signPackage'] = $this->getSignPackage();
$public=$this->publics_model->get_public_by_id( $this->inter_id);

$this->share['title'] = $my_info['nickname']. '送您一份小礼物';//Index
if(isset($this->topic['share_title_gift']))
    $this->share['title'] = $my_info['nickname']. $this->topic['share_title_gift'];

$fans_info = $this->session->userdata($this->inter_id. 'fans_info');
$this->share['link'] = site_url('mall/wap/receive/'.$order_id.'/order/'.$this->datas['guid']). '?id='. $this->inter_id
    .'&t='. $this->topic['identity']. '&scope=snsapi_userinfo&saler='. $this->saler.'&f='. $fans_info['id'];

$this->share['imgUrl'] = base_url('public/mall/default/images/box.png');
if( $this->topic['share_img_gift'] )
    $this->share['imgUrl'] = $this->_get_domain(). $this->topic['share_img_gift'];

$this->share['desc'] = '小声告诉你，嘘！已经付过钱了，全国包邮，快快领取吧';//Index
if(isset($this->topic['share_desc_gift']))
    $this->share['desc'] = $this->topic['share_desc_gift'];

$this->share['type']    = '';
$this->share['dataUrl'] = '';
$this->datas['share'] = $this->share;
/********* 分享结束  *********/

    		$address_id = $this->uri->segment(5);
    		//选择完邮寄地址，naid参数传回
    		if($this->input->get('naid',true)){
    			$address_id = $this->input->get('naid',true);
    		}
	        $this->load->model('mall/mine_model');
	        if($address_id){
	            $address = $this->mine_model->get_single_address($this->inter_id,$this->hotel_id,$this->openid,$address_id);
	        }
	        if(empty($address)){
	            $address = $this->mine_model->rand_single_address($this->inter_id,$this->hotel_id,$this->openid);
	        }
	        //var_dump($address);die;

	        $itemc = '';
	        $can_mail= $can_pickup= TRUE;
	        if($mail_type==1){
	            //可邮寄商品
	            foreach ( $item_mail as $v){
	                $itemc .= $v['id']. ',';
	                if( $v['can_pickup']== EA_base::STATUS_FALSE_ ){
	                    $can_pickup= FALSE;
	                }
	                if( $v['can_gift']== EA_base::STATUS_FALSE_ ){
	                    $can_gift= FALSE;
	                }
	            }
	            $this->datas['order_items'] = $item_mail;
	            
	        } else{
	            $can_mail= FALSE;
	            foreach ( $item_other as $v){
	                $itemc .= $v['id']. ',';
	                if( $v['can_gift']== EA_base::STATUS_FALSE_ ){
	                    $can_gift= FALSE;
	                }
	            }
	            $this->datas['order_items'] = $item_other;
	        }
	        $itemc = substr ( $itemc , 0 , -1);
	        
    	    //获取几个商品的详细信息（带扩展字段）数组键值为gs_id方便调取
	        $this->datas['item_all'] = $item_all;
    	    $this->datas['goods']= $this->goods_model->get_products_by_ids($pid);
    	    $this->datas['mail_type']= $mail_type;
	        $this->datas['order_info'] = $order_infos;
	        $this->datas['address'] = $address;
	        $this->datas['itemc'] = $itemc;
	        $this->datas['openid'] = $this->openid;
	        $this->datas['oid'] = $order_id;
	        $this->datas['can_mail'] = $can_mail;
	        $this->datas['can_gift'] = $can_gift;
	        $this->datas['can_pickup'] = $can_pickup;
	        $this->datas['checkjson'] = base64_encode($this->orders_model->item_consume_check($order_id, $this->inter_id));
	        
//$path= FCPATH. DS. FD_PUBLIC. DS. 'qrcode'. DS. 'mall'. DS. 'wap'. DS. 'pay_success'. DS;
$qr_path= base_url('/'. FD_PUBLIC. '/qrcode/mall/wap/pay_success');
$this->datas['qrcode'] = $qr_path. '/'. $this->inter_id. '_'. $order_infos['out_trade_no']. '.png';
	        
	        $this->_view($this->theme.'/header',$this->datas);
	        $this->_view($this->theme.'/mail');
	        
	    } else { 
	        redirect(site_url('mall/wap/order_status/' .$order_id) .'?id=' .$this->inter_id. '&mail='. $mail_type
	           .'&t=' .$this->topic['identity'] .'&saler=' .$this->saler .'&f=' .$this->fans_id);
	    }
	}
	
	/**
	 * 核销状态检测
	 */
	public function item_consume_check()
	{
	    $order_id = $this->input->post('oid');
	    $this->load->model('mall/shp_orders');
	    $checkjson= base64_encode($this->shp_orders->item_consume_check($order_id, $this->inter_id));
        //echo $json;die;
	    $match= $this->input->post('s');
	    if($match && $match== $checkjson){
	        $result= array('status'=>1, 'message'=>'');
	        echo json_encode($result);
	        
	    } else {
	        $result= array('status'=>2, 'message'=>'');
	        echo json_encode($result);
	    }
	}

	/**
	 * 核销二维码查看加密参数
	 * @param String $order_id
	 * @param String $item_id
	 * @return string
	 */
	protected function _gen_consume_code($order_id, $item_id)
	{
        $this->load->helper('encrypt');
        $encrypt_util= new Encrypt();
        
	    $code= $this->input->get('code');
	    $string= base64_decode($code);
	    $json= json_encode( array('order_id'=>$order_id, 'item_id'=>$item_id) );
	    $string= $encrypt_util->encrypt($json);
	    return base64_encode($string);
	}
	
	/**
	 * 核销二维码解密参数，显示图片
	 */
	public function item_consume_qrcode()
	{
        $this->load->helper('encrypt');
        $encrypt_util= new Encrypt();
        
	    $code= $this->input->get('code');
	    $string= base64_decode($code);
	    
	    $json= $encrypt_util->decrypt($string);
	    $array= (array) json_decode($json);
        //print_r($array);die;
	    if( isset($array['order_id']) && isset($array['item_id']) && $order_id= $array['order_id']){
	        $this->load->model('mall/shp_orders');
	        $order= $this->shp_orders->load($order_id);
	        if($order){
	            $ccode= $order->qr_order_no($order->m_get('out_trade_no'), $array['item_id'], 'en');
	            $this->_get_qrcode_png($ccode, FALSE);
	        }
	    }
	    echo 'Wrong code paramters.';
	}

	/**
	 * 卡购专题录音操作
	 */
	public function record()
	{
	    //根据openid获取头像和昵称
	    $this->load->model('wx/publics_model');
	    $fans_info= $this->publics_model->get_fans_info($this->openid);
	    //print_r($fans_info);die;

	    //订单数据
	    $oid = $this->uri->segment(4);
	    $this->load->model('mall/shp_orders', 'orders_model');
	    $this->load->model('mall/shp_order_items', 'items_model');
	    $this->load->model('mall/shp_goods', 'goods_model');
	    $this->load->model('mall/shp_wishes', 'wishes_model');
	    $item_model= $this->items_model;
	    $orders_model= $this->orders_model;
	    $goods_model= $this->goods_model;
	    
	    $this->load->helper('guid');
	    $this->datas['guid'] = Guid::toString();
/********* 分享开始  *********/ 
$this->datas['signPackage'] = $this->getSignPackage();
$public=$this->publics_model->get_public_by_id( $this->inter_id);
	    
$this->share['title'] = $my_info['nickname']. '送您一份小礼物';//Index
if(isset($this->topic['share_title_gift']))
    $this->share['title'] = $my_info['nickname']. $this->topic['share_title_gift'];

$fans_info = $this->session->userdata($this->inter_id. 'fans_info');
// $this->share['link'] = site_url('mall/wap/opengift/'. $oid ). '/?id='. $this->inter_id
// .'&t='. $this->topic['identity']. '&saler='. $this->saler.'&f='. $fans_info['id'];
$this->share['link'] = site_url('mall/wap/receive_card/'. $oid. '/order/'.$this->datas['guid']). '?id='.$this->inter_id
    .'&t='. $this->topic['identity']. '&scope=snsapi_userinfo&saler='. $this->saler.'&f='. $fans_info['id'];

$this->share['imgUrl'] = base_url('public/mall/default/images/box.png');
if( $this->topic['share_img_gift'] )
    $this->share['imgUrl'] = $this->_get_domain(). $this->topic['share_img_gift'];

$this->share['desc'] = '小声告诉你，嘘！已经付过钱了，快快领取吧';//Index
if(isset($this->topic['share_desc_gift']))
    $this->share['desc'] = $this->topic['share_desc_gift'];

$this->share['type']    = '';
$this->share['dataUrl'] = '';
$this->datas['share'] = $this->share;
/********* 分享结束  *********/

	    //print_r($fans_info);die;
	    $order = $this->orders_model->find( array('order_id'=>$oid, 'pay_status'=>$orders_model::PAYMENT_T) );
	    $items= $this->items_model->find_all( array('order_id'=>$oid ) );
	    
	    $pid= $item_qty= array();
	    foreach ($items as $k=> $v){
	        $pid[]= $v['gs_id'];
	        if( isset($item_qty[$v['gs_id']]) ) $item_qty[$v['gs_id']]['qty']++;
	        else $item_qty[$v['gs_id']]= $v+ array('qty'=>1);
	    }
	    
	    //获取订单对应的祝福记录
	    $wishes= $this->wishes_model->find( array('order_id'=>$oid ) ); 
	    if( $wishes && count($wishes)>0 ) $is_record= TRUE;
	    else $is_record= FALSE;

	    $messages= file_get_contents( file_site_url(). '/public/mall/common/kargo_bg/message.txt' );
	    $messages= explode("||", str_replace(array("'","\r","\r\n","\n"), array("","","",""), $messages) );
	    
	    $pics= array();
	    for($i=1; $i<8; $i++){
	        $pics[]= file_site_url(). '/public/mall/common/kargo_bg/bg'. str_pad($i, 4, '0', STR_PAD_LEFT). '.jpg';
	    }
	    
	    //获取几个商品的详细信息（带扩展字段）数组键值为gs_id方便调取
	    $this->datas['goods']= $this->goods_model->get_products_by_ids($pid);
	    $this->datas['openid'] = $this->openid;
	    $this->datas['fans_info'] = $fans_info;
	    $this->datas['is_record'] = $is_record;
	    $this->datas['wishes']= $wishes;
	    $this->datas['oid'] = $oid;
	    $this->datas['order'] = $order;
	    $this->datas['items']= $item_qty;
	    $this->datas['messages'] = $messages;
	    $this->datas['pics'] = $pics;

	    //$this->_view($this->theme.'/header', $this->datas);
	    $this->_view($this->theme.'/record', $this->datas);
	}

	/**
	 * 卡购专题打开礼物
	 */
	public function opengift()
	{
	    //订单数据
	    $oid = $this->uri->segment(4);
	    $this->load->model('mall/shp_orders', 'orders_model');
	    $this->load->model('mall/shp_order_items', 'items_model');
	    $this->load->model('mall/shp_goods', 'goods_model');
	    $this->load->model('mall/shp_wishes', 'wishes_model');
	    $item_model= $this->items_model;
	    $orders_model= $this->orders_model;
	    $goods_model= $this->goods_model;

	    //print_r($fans_info);die;
	    $order = $this->orders_model->find( array('order_id'=>$oid, 'pay_status'=>$orders_model::PAYMENT_T) );
	    $items= $this->items_model->find_all( array('order_id'=>$oid ) );
	    
	    //对于部分对接接口获取订单卡密信息
	    if($order){
	        $this->load->library('Mall/Lib_kargo');
	        $order= Lib_kargo::inst()->order_datail($order );
	        
	        if($order) $this->datas['return_card']= TRUE;
	        else $this->datas['return_card']= FALSE;
// $order['cards']= array(
//     '8862100000060'=> array(
//        0=> array('card_no'=> '123456789012','code'=> '123456789012'),
//        1=> array('card_no'=> '987654321098','code'=> '987654321098'),
//     ),
// );
	        /** print_r($order);die;
	         [cards] => Array (
    	         [8862100000060] => Array (
        	         [0] => Array (
            	         [card_no] => fshgi8Xhgs391hgag
            	         [code] => hjajrgi8Xhgs391hgag
        	         )
    	         )
	         )
	        */
	    }
	    $pid= $card_ids= $item_qty= array();
	    foreach ($items as $k=> $v){
	        $pid[]= $v['gs_id']; 
	        $card_ids[$v['gs_id']]= $v['wx_card_id'];  //需要拉取的
	        
	        if( isset($order['cards'][$v['sku']] ) && count($order['cards'][$v['sku']])>0 ){
	            //$items[$k]
	            foreach ( $order['cards'][$v['sku']] as $sk=> $sv){
	                $items[$sk]['cards']= $sv;
	                unset($order['cards'][$v['sku']][$sk]);
	            }
	        }
	    }
        //print_r($items);die;
	    
	    //获取订单对应的祝福记录
	    $wishes= $this->wishes_model->find( array('order_id'=>$oid ) );
	    if( $wishes && count($wishes)>0 ) $is_record= TRUE;
	    else $is_record= FALSE;

	    //收礼人打开，即标识订单为已处理、并且细单核销
	    if($items[0]['get_openid']!=$this->openid){
	        $order_model= $this->orders_model;
	        $order_model->load($oid)->m_set('status', $order_model::STATUS_COMPLETE )->m_save();
	    }
	    
	    //根据openid获取头像和昵称
	    $this->load->model('wx/publics_model');
	    $fans_info= $this->publics_model->get_fans_info($this->openid);

	    $this->datas['fans_info'] = $fans_info;
	    $this->datas['openid'] = $this->openid;
	    $this->datas['inter_id'] = $this->inter_id;
	    
	    //获取几个商品的详细信息（带扩展字段）数组键值为gs_id方便调取
	    $this->datas['goods']= $this->goods_model->get_products_by_ids($pid);
	    $this->datas['goods_model']= $this->goods_model;
	    $this->datas['is_record'] = $is_record;
	    $this->datas['wishes']= $wishes;
	    $this->datas['order'] = $order;
	    $this->datas['items']= $items;
	    
	    $this->_view($this->theme.'/header', $this->datas);
	    $this->_view($this->theme.'/opengift');
	}

	/**
	 * 异步保存录音
	 */
	public function save_vioce()
	{
	    $serverId= $this->input->get('sid');
	    $orderId= $this->input->get('oid');
	    $msg= $this->input->get('msg');
	    $pic_url= $this->input->get('pic');
	    
	    if($serverId){
	        $this->load->model('wx/access_token_model');
	        $access_token= $this->access_token_model->get_access_token( $this->inter_id );
	        $down_url= "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$access_token}&media_id={$serverId}";
	        //echo $down_url;die;
	        $local_path= substr(FCPATH, 0,-1). DS. FD_PUBLIC. DS. 'mall'. DS. 'common'. DS. 'kargo_voice'. DS;
	        if( !file_exists($local_path) ) mkdir($local_path, 777, TRUE);
	        //echo $local_path;die;
	         
	        //下载远程文件到本地
	        $content= file_get_contents($down_url);
	        $rj= json_decode($content);
	        if( $rj && isset($rj->errcode ) && in_array($rj->errcode , array(40001,40014,41001,42001) ) ){
	            $access_token= $this->access_token_model->reflash_access_token( $this->inter_id );
	            $down_url= "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$access_token}&media_id={$serverId}";
	            $content= file_get_contents($down_url);
	        }
	        
	        $filename= $serverId. '.amr';
	        $file_path= $local_path. $filename;
	        $fp = fopen($file_path, 'w');
	        fwrite($fp, $content);
	        fclose($fp);
	         
	        //ftp开始，初始化测试服务器ftp
	        if( isset($_SERVER['CI_ENV']) && $_SERVER['CI_ENV']=='production' ){
	            $this->ftp= $this->_ftp_server('prod');
	        } else {
	            $this->ftp= $this->_ftp_server('test');
	        }
	        //print_r($this->ftp);die;
	         
	        $to_file = $this->ftp->floder. FD_PUBLIC. '/mall/common/kargo_voice/';
	        if (empty($to_file)) {
	            $this->ftp->mkdir($to_file);
	        }
	        
	        $to_file= str_replace(array('\\','//'), array('/','/'), $to_file. $filename);
	        //echo $to_file; echo $file_path;die;
	        if( !file_exists($file_path) ) echo '原上传文件不存在！';
	        else $result= $this->ftp->upload($file_path, $to_file, 'binary', 0775);
	        $this->ftp->close();
	        
	    } else {
	        $result= FALSE;
	        $serverId= '';
	    }
	    
	    //根据openid获取头像和昵称
	    $this->load->model('wx/publics_model');
	    $fans_info= $this->publics_model->get_fans_info($this->openid);
	    
	    $data= array(
	        'order_id'=> $orderId,
	        'inter_id'=> $this->inter_id,
	        'openid'=> $this->openid,
	        'headimgurl'=> $fans_info['headimgurl'],
	        'nickname'=> $fans_info['nickname'],

	        'message'=> $msg,
	        'bg_url'=> $pic_url,
	        //'voice_url'=
	        'serverId' => $serverId,
	        'create_time'=> date('Y-m-d H:i:s'),
	    );
	    if($result){
	        @unlink($file_path);
	        $data['voice_url']= $this->ftp->weburl. '/'. FD_PUBLIC. '/mall/common/kargo_voice/'. $filename;
	        
	    } else {
	        $data['voice_url']= '/'. FD_PUBLIC. '/mall/common/kargo_voice/'. $filename;
	    }
	    $this->load->model('mall/shp_wishes');
	    $wish= $this->shp_wishes->find( array('order_id'=> $orderId) );
	    
	    if($wish && $wish['wish_id']){
	        $result= $this->shp_wishes->load($wish['wish_id'])->m_save($data);
	    } else {
	        $result= $this->shp_wishes->m_save($data);
	    }

	    //收礼人打开，即标识订单为已处理、并且细单核销，改为在分享完毕之后，通过ajax进行状态处理
//         $this->load->model('mall/shp_orders');
//         $order_model= $this->shp_orders;
//         $result= $order_model->load($orderId)->m_set('status', $order_model::STATUS_PROCESSING )->m_save();
        
	    if($result){
	        //$this->shp_orders->order_status_flush($orderId);
	        echo json_encode(array('status'=>1, 'url'=> '' ));
	        //echo json_encode(array('status'=>1, 'url'=>$data['voice_url'] ));
	    } else {
	        echo json_encode(array('status'=>2 ));
	    }
	}
	
	public function pushwx()
	{
	    echo 'push card to weixin card package';
	}
	
	/**
	 * 我的订单列表 => 订单详情=> 订单处理后状态
	 * 适用于：[default]; [less];
	 */
// 	public function __order__status()
// 	{
//	    /** 方法到达之前，用ajax方式调用save_mail_order，保存个人收货地址  **/
// // 		$this->output->enable_profiler(true);
// 	    $order_id = $this->uri->segment(4);
// 	    $this->load->model('mall/shp_orders', 'orders_model');
// 	    $this->load->model('mall/shp_order_items', 'order_items');
// 	    $item_model= $this->order_items;
	    
// 	    $order_infos = $this->orders_model->get_order_details_with_to_frns($this->inter_id, $this->hotel_id, $order_id);

// 	    $address = $order_infos['items'][0]['addr_id'];

// 	    $this->load->model('mall/mine_model');
// 	    if($order_infos['items'][0]['status'] != $item_model::STATUS_GIFTED 
// 	        || $order_infos['items'][0]['status'] != $item_model::STATUS_DEFAULT 
// 	    ){
// 	        $address = $this->mine_model->get_single_address($this->inter_id,$this->hotel_id,$this->openid,$order_infos['items'][0]['addr_id']);
// 	        $this->datas['address'] = $address;
// 	    }
	    
// 		//var_dump($address);die;
// 	    $this->datas['order_infos'] = $order_infos;
// 	    $this->datas['openid'] = $this->openid;
// 	    $this->datas['oid'] = $order_id;

// 	    $total = 0;
// 	    $get_nums = 0;//已领取的数量
// 	    $share_code = '';
// 	    foreach ($this->datas['order_infos']['items'] as $v ){
// 	        $total += $v['nums'];
// 	        if($v['openid'] != $this->openid)
// 	            $get_nums += $v['nums'];
// 	        else 
// 	            $share_code = $v['share_code'];
// 	    }
	    
// 	    //保存发票申请信息
// 	    $order= $this->orders_model->load($order_id)->m_data(); 
// 	    $inv_need= $this->input->post('inv_need');
// 	    //2个条件：购买人申请开发票；此订单未曾开发票
// 	    if($inv_need==EA_base::STATUS_TRUE && $order['is_invoice']!=EA_base::STATUS_TRUE ){
//     	    $this->load->model('mall/shp_invoice');
//     	    $inv_model= $this->shp_invoice;
// 	        $inv_title= $this->input->post('inv_title');
// 	        $shipping_amount= 0;
// 	        $discount_amount= 0;
// 	        $data= array(
//     	        'title'=> $inv_title,
//     	        'openid'=> $this->openid,
//     	        'hotel_id'=> $this->hotel_id,
//     	        'inter_id'=> $this->inter_id,
//     	        'order_id'=> $order_id,
//     	        'out_trade_no'=> $order['out_trade_no'],
//     	        'grand_total'=> $order['total_fee'],
//     	        'shipping_amount'=> $shipping_amount,
//     	        'discount_amount'=> $discount_amount,
//     	        'subtotal'=> $discount_amount+ $order['total_fee'],
//     	        'create_time'=> date('Y-m-d H:i:s'),
//     	        'single'=> $inv_model::SINGLE_F,
//     	        'status'=> $inv_model::STATUS_DEFAULT,
//     	        'address_id'=> $address['id'],
// 	        );
// 	        $inv_model->m_save($data);
// 	        //修改订单可否申请发票
// 	        $this->orders_model->load($order_id)->m_set('is_invoice', EA_base::STATUS_TRUE )->m_save();
// 	    }
// 	    //保存发票申请信息
	    
// 	    $this->datas['share_code'] = $share_code;
// 	    $this->datas['get_nums'] = $get_nums;
// 	    $this->datas['total'] = $total;
// 	    $this->datas['address'] = $address;

// 	    $this->datas['item_model'] = $this->order_items;
	     
// 	    $this->_view($this->theme.'/header', $this->datas);
// 	    $this->_view($this->theme.'/mail_status');
// 	    $this->_view($this->theme.'/public_share');
// 	}
    
    /**
     * 本页面场景汇总：
         1.1，购买人：填写邮寄地址后->马上显示邮寄申请信息（筛选出订单中的可邮寄商品）
         1.2，购买人：填写邮寄地址之后->从处理按钮进去
         2.1，购买人：自提核销->马上显示信息（筛选出订单中的可邮寄商品）
         2.2，购买人：自提核销之后->从处理按钮进去
         3，购买人：赠送好友后->马上显示赠送清单（显示出订单的所有赠送商品）
         4，购买人：赠送好友后->从赠送链接进入
         
         5，收礼人：接受赠送->
         6，收礼人：
     */
	public function order_status()
	{
	    /** 方法到达之前，用ajax方式调用save_mail_order，保存个人收货地址  **/
// 		$this->output->enable_profiler(true);
	    $order_id = $this->uri->segment(4);
	    $this->load->model('mall/shp_orders', 'orders_model');
	    $this->load->model('mall/shp_order_items', 'items_model');
	    $this->load->model('mall/shp_goods', 'goods_model');
	    $this->load->model('mall/shp_gift_log', 'gift_model');
	    $orders_model= $this->orders_model;
	    $item_model= $this->items_model;
	    $goods_model= $this->goods_model;
	    
	    //<可邮寄:1>\<自提:2>\<赠送:3>
	    $mail_type= $this->input->get('mail');
	    $mail_type= $mail_type? $mail_type: '3';

	    switch ($mail_type){
	        case 1:
	            $filter=  array( 'order_id'=>$order_id, 'can_mail'=> EA_base::STATUS_TRUE_ );
	            break;
	        case 2:
	            $filter=  array( 'order_id'=>$order_id, 'can_pickup'=> EA_base::STATUS_TRUE_ );
	            break;
	        case 3:
	        default:
	            $filter=  array( 'order_id'=>$order_id  );
	            break;
	    }
	    
	    $order_infos = $this->orders_model->find( array('order_id'=>$order_id, 'pay_status'=>$orders_model::PAYMENT_T ) );
	    $order_items = $this->items_model->find_all( $filter );
//print_r($order_items);die;	    
	    
	    $gift_logs = $this->gift_model->find_all( array( 'order_id'=>$order_id ) );
	    //$gt_gift_logs = $this->gift_model->find_all( array( 'order_id'=>$order_id, 'gt_openid'=>$this->openid  ) );
//print_r($gift_logs);
	    $this->datas['gift_logs'] = $gift_logs;

	    $address = $order_items[0]['addr_id'];
	    $this->load->model('mall/mine_model');
	    if( $order_items[0]['status'] != $item_model::STATUS_GIFTED 
	        || $order_items[0]['status'] != $item_model::STATUS_DEFAULT 
	    ){
	        $address = $this->mine_model->get_single_address($this->inter_id,$this->hotel_id,$this->openid,$order_items[0]['addr_id']);
	        $this->datas['address'] = $address;
	    }
	    
		//var_dump($address);die;
	    
	    $total = 0;
	    $get_nums = 0;//已领取的数量
	    $share_code = '';
	    $pid= array();
	    foreach ($order_items as $v ){
	        $pid[]= $v['gs_id'];
	        $total += 1;
	        if($v['openid'] != $this->openid)  //送回给自己判断失误？？
	            $get_nums += 1;
	        else 
	            $share_code = $v['share_code'];
	    }
	    $pid= array_unique($pid);
	    
	    $echo_item= array();
	    foreach ($order_items as $v ){
	        if( isset($echo_item[$v['gs_id']]) ) $echo_item[$v['gs_id']]['qty']++;
	        else $echo_item[$v['gs_id']]= $v+ array('qty'=>1);
	    }
	    //print_r($echo_item);die;
	    
	    
	    //保存发票申请信息
	    $order= $this->orders_model->load($order_id)->m_data(); 
	    $inv_need= $this->input->post('inv_need');
	    
	    //2个条件：发起开发票申请；此订单未曾开发票
	    if( $inv_need== EA_base::STATUS_TRUE && $order['is_invoice']!= EA_base::STATUS_TRUE ){
    	    $this->load->model('mall/shp_invoice');
    	    $inv_model= $this->shp_invoice;
	        $inv_title= $this->input->post('inv_title');
	        $shipping_amount= 0;
	        $discount_amount= 0;
	        $data= array(
    	        'title'=> $inv_title,
    	        'openid'=> $this->openid,
    	        'hotel_id'=> $this->hotel_id,
    	        'inter_id'=> $this->inter_id,
    	        'order_id'=> $order_id,
    	        'out_trade_no'=> $order['out_trade_no'],
    	        'grand_total'=> $order['total_fee'],
    	        'shipping_amount'=> $shipping_amount,
    	        'discount_amount'=> $discount_amount,
    	        'subtotal'=> $discount_amount+ $order['total_fee'],
    	        'create_time'=> date('Y-m-d H:i:s'),
    	        'single'=> $inv_model::SINGLE_F,
    	        'status'=> $inv_model::STATUS_DEFAULT,
    	        'address_id'=> $address['id'],
	        );
	        $inv_model->m_save($data);
	        //修改订单可否申请发票
	        $this->orders_model->load($order_id)->m_set('is_invoice', EA_base::STATUS_TRUE )->m_save();
	    }
	    //保存发票申请信息

	    $this->load->model('wx/publics_model');
	    
	    
	    //获取几个商品的详细信息（带扩展字段）数组键值为gs_id方便调取
	    $this->datas['openid'] = $this->openid;
	    $this->datas['goods']= $this->goods_model->get_products_by_ids($pid);
	    $this->datas['oid'] = $order_id;
	    $this->datas['order_infos'] = $order_infos;
	    //$this->datas['order_items'] = $order_items;
	    $this->datas['order_items'] = $echo_item;
	    
	    $this->datas['share_code'] = $share_code;
	    $this->datas['get_nums'] = $get_nums;  //已领取个数
	    $this->datas['total'] = $total;
	    $this->datas['address'] = $address;

	    $this->datas['mail_type'] = $mail_type;

	    $this->datas['publics_model'] = $this->publics_model;
	    $this->datas['item_model'] = $this->items_model;
	    $this->datas['order_model'] = $this->orders_model;
	     
	    $this->_view($this->theme.'/header', $this->datas);
	    $this->_view($this->theme.'/mail_status');
	    $this->_view($this->theme.'/public_share');
	}

	/** 发货后的发票填写页面 */
	public function bill()
	{
	    $this->_view($this->theme.'/header',$this->datas);
	    $this->_view($this->theme.'/bill');
	    $this->_view($this->theme.'/public_share');
	}
	
	public function stores()
	{
		$this->load->helper('common');
		$this->load->model('wx/access_token_model');
		$access_token = $this->access_token_model->get_access_token($this->session->userdata('inter_id'));
		$url = 'https://api.weixin.qq.com/cgi-bin/poi/getpoilist?access_token='.$access_token;
		$result = json_decode(doCurlPostRequest($url,json_encode(array('begin'=>0,'limit'=>'50'))));
		$this->datas['business_list'] = $result->business_list;
		$this->_view($this->theme.'/header',$this->datas);
		$this->_view($this->theme.'/store_location');
		$this->_view($this->theme.'/public_share');
	}
	
	/**
	 * 接收转赠入口，打开礼盒
	 * 适用于：[default]; [less];
/index.php/mall/wap/receive/__/order/D8CDF28E-C754-F924-F3CC-98613498ACC3?id=__&t=__&scope=snsapi_userinfo&saler=&f=12
	 */
	public function receive()
	{
// 		$this->output->enable_profiler(true);
		$this->load->model('mall/shp_orders', 'orders_model');
		
		$share_log = $this->orders_model->share_myself($this->uri->segment(6),$this->openid,$this->uri->segment(4));
		if($share_log){
		    //自己查看自己分享的记录跳转到产品页面
			redirect(site_url('mall/wap/order_details/'. $this->uri->segment(4))
			    .'?id='.$this->inter_id.'&t='.$this->topic['identity'].'&saler='.$this->saler.'&f='.$this->fans_id);exit();
		}
		$query = $this->orders_model->get_share_log($this->uri->segment(6),$this->uri->segment(4));
		if(empty($query)){
		    //没有分享信息跳到首页
			redirect(site_url('mall/wap/topic').'?id='.$this->inter_id.'&t='.$this->topic['identity'].'&saler='.$this->saler.'&f='.$this->fans_id);
		}
		if($query[0]['gstatus'] == 1){
		    //分享状态为1，显示卡券页面
			
		    redirect(site_url('mall/wap/vote/'.$this->uri->segment(4)).'?id='.$this->inter_id
			   .'&t='.$this->topic['identity'].'&saler='.$this->saler.'&f='.$this->fans_id);
		}
		
		$this->load->model('hotel/hotel_model');
		$this->datas['hotel'] = $this->hotel_model->get_hotel_detail($this->inter_id, $this->hotel_id);
		
		$this->datas['sc'] = $this->uri->segment(6);   //分享码
		$this->datas['order_id'] = $this->uri->segment(4);
		$this->datas['details'] = $this->orders_model->get_share_man_details($this->uri->segment(6),$this->uri->segment(4));
		
		$this->_view($this->theme.'/header',$this->datas);
		$this->_view($this->theme.'/receive');
		$this->_view($this->theme.'/public_share');
	}
	public function receive_card()
	{
// 		$this->output->enable_profiler(true);
		$this->load->model('mall/shp_orders', 'orders_model');
		$share_log = $this->orders_model->share_myself($this->uri->segment(6),$this->openid,$this->uri->segment(4));

		if($share_log){
		    //自己查看自己分享的记录跳转到产品页面
			redirect(site_url('mall/wap/my_orders/')
			    .'?id='.$this->inter_id.'&t='.$this->topic['identity'].'&saler='.$this->saler.'&f='.$this->fans_id);exit();
			//redirect(site_url('mall/wap/record/'. $this->uri->segment(4))
			//    .'?id='.$this->inter_id.'&t='.$this->topic['identity'].'&saler='.$this->saler.'&f='.$this->fans_id);exit();
		}
		$query = $this->orders_model->get_share_log($this->uri->segment(6),$this->uri->segment(4));
		if(empty($query)){
		    //没有分享信息跳到首页
			redirect(site_url('mall/wap/topic').'?id='.$this->inter_id.'&t='.$this->topic['identity'].'&saler='.$this->saler.'&f='.$this->fans_id);
		}
		if($query[0]['gstatus'] == 1){
		    //分享状态为1，显示卡券页面
		    redirect(site_url('mall/wap/opengift/'.$this->uri->segment(4)).'?id='.$this->inter_id
			   .'&t='.$this->topic['identity'].'&saler='.$this->saler.'&f='.$this->fans_id);
		}
		
		$this->load->model('hotel/hotel_model');
		$this->datas['hotel'] = $this->hotel_model->get_hotel_detail($this->inter_id, $this->hotel_id);
		
		$this->datas['sc'] = $this->uri->segment(6);   //分享码
		$this->datas['order_id'] = $this->uri->segment(4);
		$this->datas['details'] = $this->orders_model->get_share_man_details($this->uri->segment(6),$this->uri->segment(4));
		
		$this->_view($this->theme.'/header',$this->datas);
		$this->_view($this->theme.'/receive');
		$this->_view($this->theme.'/public_share');
	}
	
	/**
	 * 显示礼盒后的卡券页面
	 */
	public function vote()
	{
		$this->load->model('mall/shp_orders', 'orders_model');
		
		$query = $this->orders_model->get_gift_items($this->uri->segment(4),$this->openid);
		$order_id= $this->uri->segment(4);
		$this->datas['orders'] = $this->orders_model->get_order_details($this->session->userdata('inter_id'), null, $order_id);
		
		if(empty($query) && $this->datas['orders']['items'][0]['get_openid'] != $this->openid){
		    //没有分享记录，且原始购买人不等于当前访客，跳到首页
			redirect(site_url('mall/wap/topic').'?id='.$this->inter_id.'&t='.$this->topic['identity'].'&saler='.$this->saler.'&f='.$this->fans_id);
		
		} elseif (empty($query) && $this->datas['orders']['items'][0]['get_openid'] == $this->openid){
		    //没有分享记录，且原始购买人等于当前访客，跳到订单详情页
		    redirect( site_url('mall/wap/order_details/'. $this->uri->segment(4) )
			    .'?id='.$this->inter_id.'&t='.$this->topic['identity'].'&saler='.$this->saler.'&f='.$this->fans_id);
		}

		$this->load->model('hotel/hotel_model');
		$this->datas['hotel'] = $this->hotel_model->get_hotel_detail($this->inter_id, $this->hotel_id);
		
		$qr_path= base_url('/'. FD_PUBLIC. '/qrcode/mall/wap/pay_success');
		$this->datas['qrcode'] = $qr_path. '/'. $this->inter_id. '_'. $this->datas['orders']['out_trade_no']. '.png';
			
		$this->datas['order_id'] = $order_id;
		$this->datas['details'] = $this->orders_model->get_share_man_details($this->datas['orders']['items'][0]['share_code'],$this->uri->segment(4));

		if( $this->inter_id=='a453956624' ){
		    //print_r($this->datas['orders']);die;
		    redirect(site_url('mall/wap/opengift/'. $order_id ).'?id='.$this->inter_id
		        .'&t='.$this->topic['identity'].'&saler='.$this->saler.'&f='.$this->fans_id);
		    
		} else {
    		$this->_view($this->theme.'/header',$this->datas);
    		$this->_view($this->theme.'/vote');
    		$this->_view($this->theme.'/public_share');
		}
	}
	
	/**
	 * 评论订单
	 */
	public function comment()
	{
		$type = $this->uri->segment ( 4 );
		$this->_view($this->theme.'/header',$this->datas);
		switch ($type) {
			case 'new' :
				$this->load->model('mall/shp_orders', 'orders_model');
				if(!$this->uri->segment ( 5 )){
					redirect(site_url('mall/wap/topic').'?id='.$this->inter_id.'&t='.$this->topic['identity'].'&saler='.$this->saler.'&f='.$this->fans_id);
					exit;
				}
				$this->datas['orders'] = $this->orders_model->get_order_details($this->inter_id,$this->hotel_id,$this->uri->segment ( 5 ));
				$this->_view($this->theme.'/comment_new',$this->datas);
				break;
				
			case 'success' :
				$comment_id = $this->input->get ( 'cid' );
				if(!$comment_id){
					redirect(site_url('mall/wap/topic').'?id='.$this->inter_id.'&t='.$this->topic['identity'].'&saler='.$this->saler.'&f='.$this->fans_id);
					exit;
				}

//print_r($this->topic);die;
	
/********* 分享开始  *********/
$this->datas['signPackage'] = $this->getSignPackage();
$public=$this->db->get_where('publics', array('inter_id'=>$this->inter_id))->row_array();

$this->share['title'] = $my_info['nickname']. '送您一份小礼物';//Index
if(isset($this->topic['share_title_gift']))
	$this->share['title'] = $my_info['nickname']. $this->topic['share_title_gift'];

$fans_info = $this->session->userdata($this->inter_id. 'fans_info');
$this->share['link'] = site_url('mall/wap/receive/'.$order_id.'/order/'.$this->datas['guid']). '?id='.$this->inter_id
    .'&t='. $this->topic['identity']. '&scope=snsapi_userinfo&saler='. $this->saler.'&f='. $fans_info['id'];
// if( isset( $this->topic['share_link_gift'] ) ){
//     if (stripos ( $this->topic['share_link_gift'], '?' ) === FALSE)
//         $this->share['link'] = $this->topic['share_link_gift'] . '?saler='. $this->saler.'&f=' .$fans_info['id'];
//     else
//         $this->share['link'] = $this->topic['share_link_gift'] . '&saler='. $this->saler.'&f=' .$fans_info['id'];
// }

$this->share['imgUrl']  = base_url('public/mall/default/images/box.png');
if( $this->topic['share_img_gift'] )
    $this->share['imgUrl'] = $this->_get_domain(). $this->topic['share_img_gift'];

$this->share['desc']    = '小声告诉你，嘘！已经付过钱了，全国包邮，快快领取吧';//Index
if(isset($this->topic['share_desc_gift']))
    $this->share['desc'] = $this->topic['share_desc_gift'];

$this->share['type']    = '';
$this->share['dataUrl'] = '';

//$this->vars('share',$this->share);
$this->datas['share'] = $this->share;
/********* 分享结束  *********/


				$this->datas['comment_id'] = $comment_id;
				$this->_view($this->theme.'/comment_success',$this->datas);
				break;
				
			case 'show' :
				$comment_id = $this->uri->segment ( 5 );
				$this->load->model('mall/shp_comments', 'comment_model');
				if(!$comment_id){
					redirect(site_url('mall/wap/topic').'?id='.$this->inter_id.'&t='.$this->topic['identity'].'&saler='.$this->saler.'&f='.$this->fans_id);
					exit;
				}
				$this->datas['comments'] = $this->comment_model->get_comment_details($this->inter_id,$this->hotel_id,$this->uri->segment ( 5 ));
				
				
//print_r($this->topic);die;
	
/********* 分享开始  *********/
$this->datas['signPackage'] = $this->getSignPackage();
$public=$this->db->get_where('publics', array('inter_id'=>$this->inter_id))->row_array();

$this->share['title'] = $my_info['nickname']. '送您一份小礼物';//Index
if(isset($this->topic['share_title_gift']))
	$this->share['title'] = $my_info['nickname']. $this->topic['share_title_gift'];

$fans_info = $this->session->userdata($this->inter_id. 'fans_info');
$this->share['link'] = site_url('mall/wap/receive/'.$order_id.'/order/'.$this->datas['guid']). '?id='.$this->inter_id
    .'&t='. $this->topic['identity']. '&scope=snsapi_userinfo&saler='. $this->saler.'&f='. $fans_info['id'];
// if( isset( $this->topic['share_link_gift'] ) ){
//     if (stripos ( $this->topic['share_link_gift'], '?' ) === FALSE)
//         $this->share['link'] = $this->topic['share_link_gift'] . '?saler='. $this->saler.'&f=' .$fans_info['id'];
//     else
//         $this->share['link'] = $this->topic['share_link_gift'] . '&saler='. $this->saler.'&f=' .$fans_info['id'];
// }

$this->share['imgUrl']  = base_url('public/mall/default/images/box.png');
if( $this->topic['share_img_gift'] )
    $this->share['imgUrl'] = $this->_get_domain(). $this->topic['share_img_gift'];

$this->share['desc']    = '小声告诉你，嘘！已经付过钱了，全国包邮，快快领取吧';//Index
if(isset($this->topic['share_desc_gift']))
    $this->share['desc'] = $this->topic['share_desc_gift'];

$this->share['type']    = '';
$this->share['dataUrl'] = '';

//$this->vars('share',$this->share);
$this->datas['share'] = $this->share;
/********* 分享结束  *********/
				
				
				$this->datas['is_mine'] = $this->datas['comments']['openid'] == $this->session->userdata($this->inter_id.'openid');
				
				$this->_view($this->theme.'/comment_show',$this->datas);
				break;
				
			default :
				redirect(site_url('mall/wap/topic').'?id='.$this->inter_id.'&t='.$this->topic['identity'].'&saler='.$this->saler.'&f='.$this->fans_id);
				break;
		}
		$this->_view($this->theme.'/public_share');
	}
	
	
	/*  AJAX方法调用 开始   */
	public function do_save_comment()
	{
		$this->load->model('mall/shp_comments', 'comment_model');
		$comment['openid'] = $this->session->userdata($this->inter_id.'openid');
		$comment['gs_id']  = $this->input->post('gsid',true);
		$comment['inter_id'] = $this->inter_id;
		$comment['hotel_id'] = $this->hotel_id;
		$comment['order_id'] = $this->input->post('oid',true);
		$comment['contents'] = $this->input->post('content',true);
		$cid = $this->comment_model->create_comment($comment);
		if($cid > 0){
			echo json_encode(array('errmsg'=>'ok','cid'=>$cid));
		}else{
			echo json_encode(array('errmsg'=>'faild'));
		}
	}
	
	public function mail_item()
	{

	}
	public function save_mail_item()
	{
		
	}
	/**
	 * ajax 保存订单，
	 * 适用于：[default];
	 */
	public function new_order()
	{
		$arr ['openid']   = $this->openid;
		$arr ['inter_id'] = $this->inter_id;
		$arr ['hotel_id'] = $this->hotel_id;
		$arr ['saler']    = $this->saler;
		$arr ['fans_id']  = $this->fans_id;
		$arr ['topic_id'] = $this->topic['topic_id'];
		$arr ['products'] = array(
		    $this->input->post('gid', true)  =>  $this->input->post('nums',true)
		);
		$arr ['topic'] = $this->topic;
		$this->load->model('mall/shp_orders', 'orders_model');
		$res = $this->orders_model->create_order($arr);
		if($res){
			echo json_encode(array('errmsg'=>'ok', 'oid'=>$res));
		}else{
			echo json_encode(array('errmsg'=>'faild'));
		}
	}
	/**
	 * ajax 保存订单，
	 * 适用于：[less];
	 */
	public function cart_order()
	{
		$arr ['openid']   = $this->openid;
		$arr ['inter_id'] = $this->inter_id;
		$arr ['hotel_id'] = $this->hotel_id;
		$arr ['fans_id']  = $this->fans_id;
		$arr ['saler']    = $this->saler;
		$arr ['topic']  = $this->topic;
		$arr ['products'] = $this->input->post('sps',true);
		
		$this->load->model('mall/shp_orders', 'orders_model');
		$res = $this->orders_model->create_order($arr);
		if($res){
			echo json_encode(array('errmsg'=>'ok','oid'=>$res));
		}else{
			echo json_encode(array('errmsg'=>'faild'));
		}
	}
	/**
	 * ajax 保存地址，
	 * 适用于：[default];
	 */
	public function save_address()
	{
		$data['inter_id'] = $this->inter_id;
		$data['hotel_id'] = $this->hotel_id;
		$data['openid']   = $this->openid;
		$data['province'] = $this->input->post('province');
		$data['city']     = $this->input->post('city');
		$data['address']  = $this->input->post('address');
		$data['phone']    = $this->input->post('phone');
		$data['contact']  = $this->input->post('contact');
		$this->load->model('mall/mine_model');
		$aid = 0;
		$key= $this->input->post('key');
		if(empty($key)){
			$aid = $this->mine_model->create_address($this->inter_id, $this->hotel_id, $data);
		}else{
			$aid = $this->mine_model->update_address($data);
		}
		if($aid){
			echo json_encode(array('errmsg'=>'ok','aid' => $aid));
		}else{
			echo json_encode(array('errmsg'=>'faild'));
		}
	}
	/**
	 * 分享给朋友，成功后回调，标记
	 */
	public function save_share()
	{
		$this->load->model('mall/shp_orders', 'orders_model');
		$this->orders_model->save_share(
		    $this->inter_id,
		    $this->openid,
		    $this->input->get('c',true),
		    $this->input->get('o',true),
		    explode(',', $this->input->get('i',true) ),
		    $this->input->get('t',true)
		);
		echo '{"errmsg":"ok"}';
	}
	/**
	 * 回收心意
	 */
	public function recy_share()
	{
		$share_code = $this->uri->segment(4);
		$this->load->model('mall/shp_orders', 'orders_model');
		if($this->orders_model->recy_share($share_code)){
			echo '{"errmsg":"ok"}';
		}else{
			echo '{"errmsg":"faild"}';
		}
	}
	public function save_receive()
	{
		$this->load->model('mall/shp_orders', 'orders_model');
		if($this->orders_model->save_receive(
		    $this->openid,
		    $this->input->get('sc',true),
		    $this->input->get('o',true),0)
		){
			echo '{"errmsg":"ok"}';
		}else{
			echo '{"errmsg":"faild"}';
		}
	}
	/**
	 * 我的订单列表 => 订单详情=> 处理订单（订单邮寄）=> 确认邮寄地址（关联address和item数据）
	 * 适用于：[default]; [less];
	 */
	public function save_mail_order()
	{
		$order_id = $this->input->post('oid',true);
		$addr_id  = $this->input->post('aid',true);
		$this->load->model('mall/shp_orders', 'orders_model');
		if($this->orders_model->save_mail_order($order_id,$addr_id)){
		    // 已在 save_mail_order 中处理订单状态
		    //$this->orders_model->order_status_flush($order_id);
			echo json_encode(array('errmsg'=>'ok'));
			
		}else{
			echo json_encode(array('errmsg'=>'faild'));
		}
	}
	/**
	 * 加入购物车
	 * 适用于：[less];
	 */
	public function add_to_cart()
	{
		$this->load->model('mall/shp_cart', 'cart_model');
		$product_id= $this->input->get('pid',true);
		//echo $product_id;die;
		if($this->cart_model->add_to_cart($this->openid,$this->inter_id,$this->hotel_id,$product_id,'',1)){
			echo json_encode(array('errmsg'=>'ok'));
		}else{
			echo json_encode(array('errmsg'=>'faild'));
		}
	}
	/**
	 * 删除购物车项目
	 * 适用于：[less]; 
	 */
	public function del_from_cart()
	{
		$this->load->model('mall/shp_cart', 'cart_model');
		$product_id=$this->input->get('pid',true);
		if($this->cart_model->del_from_cart($this->openid,$this->inter_id,$this->hotel_id,$product_id)){
			echo json_encode(array('errmsg'=>'ok'));
		}else{
			echo json_encode(array('errmsg'=>'faild'));
		}
	}
	
	/*  AJAX方法调用 结束    */
	
	
	
	private function getSignPackage($url='')
	{
		$this->load->helper('common');
		$this->load->model('wx/publics_model', 'publics');
		$this->load->model('wx/access_token_model');
		$jsapiTicket = $this->access_token_model->get_api_ticket($this->session->userdata('inter_id'));
		//$jsapiTicket = $this->access_token_model->get_api_ticket($this->session->userdata('inter_id'), $this->openid);
		
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		if(!$url)
			$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		
		$timestamp = time();
		$nonceStr = createNonceStr();
		$public = $this->publics->get_public_by_id( $this->session->userdata('inter_id') );
		
		// 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		$signature = sha1($string);
		$signPackage = array(
			"appId"     => $public['app_id'],
			"nonceStr"  => $nonceStr,
			"timestamp" => $timestamp,
			"url"       => $url,
			"signature" => $signature,
			"rawString" => $string
		);
		return $signPackage;
	}
	
	public function coupon()
	{
		$arr['nonce_str'] = '6cefdb308e1e2e8aabd48cf79e546a02';
		$arr['mch_billno'] = '1219936901'.date('Ymd').time();
		//组成： mch_id+yyyymmdd+10位一天内不能重复的数字。
		$arr['mch_id'] = '1219936901';
		$arr['wxappid'] = 'wx07108d6280b84cb8';
		$arr['nick_name'] = '信息驿站';
		$arr['send_name'] = '信息驿站';
		$arr['re_openid'] = 'oo89wtzD-EPZaLEH41cyl3G_hJjg';
		$arr['total_amount'] = 100;
		$arr['min_value'] = 100;
		$arr['max_value'] = 100;
		$arr['total_num'] = 1;
		$arr['wishing'] = '再接再厉';
		$arr['client_ip'] = $_SERVER["REMOTE_ADDR"];
		$arr['act_name'] = '送红包活动';
		$arr['remark'] = '送红包活动';
		$this->load->model('wxpay_model');
		$arr['sign'] =  $this->wxpay_model->getSign($arr,array('key'=>'iwide1563134567df124qwered23ew4g','app_id'=>'wx07108d6280b84cb8'));
		$this->load->helper('common');
		$extras = array();
		$extras['CURLOPT_CAINFO'] = realpath('./media/pay_certi'). '/rootca_1219936901.pem';
		$extras['CURLOPT_SSLCERT'] = realpath('./media/pay_certi'). '/apiclient_cert_1219936901.pem';
		$extras['CURLOPT_SSLKEY'] = realpath('./media/pay_certi'). '/apiclient_key_1219936901.pem';
		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack ';
		// 		exit('11');
		var_dump(doCurlPostRequest($url,$this->wxpay_model->arrayToXml($arr),$extras));
	}
}

class Signature
{
	public function __construct()
	{
		$this->data = array();
	}
	public function add_data($str)
	{
		array_push($this->data, (string)$str);
	}
	public function get_signature()
	{
		sort( $this->data,SORT_LOCALE_STRING );
		$string = implode( $this->data );
		return sha1( $string );
	}
}