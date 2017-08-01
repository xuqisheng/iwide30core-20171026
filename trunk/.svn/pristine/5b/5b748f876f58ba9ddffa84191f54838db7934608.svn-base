<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dada extends MY_Admin_Roomservice {

	protected $label_module= '房间订餐';
	protected $label_controller= '达达配置';
	protected $label_action= '达达配置';

    protected $city = '[{"cityName":"上海","cityCode":"021"},{"cityName":"北京","cityCode":"010"},{"cityName":"合肥","cityCode":"0551"},{"cityName":"南京","cityCode":"025"},{"cityName":"苏州","cityCode":"0512"},{"cityName":"武汉","cityCode":"027"},{"cityName":"无锡","cityCode":"0510"},{"cityName":"常州","cityCode":"0519"},{"cityName":"杭州","cityCode":"0571"},{"cityName":"广州","cityCode":"020"},{"cityName":"深圳","cityCode":"0755"},{"cityName":"重庆","cityCode":"023"},{"cityName":"长沙","cityCode":"0731"},{"cityName":"成都","cityCode":"028"},{"cityName":"天津","cityCode":"022"},{"cityName":"厦门","cityCode":"0592"},{"cityName":"福州","cityCode":"0591"},{"cityName":"大连","cityCode":"0411"},{"cityName":"青岛","cityCode":"0532"},{"cityName":"哈尔滨","cityCode":"0451"},{"cityName":"济南","cityCode":"0531"},{"cityName":"郑州","cityCode":"0371"},{"cityName":"西安","cityCode":"029"},{"cityName":"宁波","cityCode":"0574"},{"cityName":"温州","cityCode":"0577"},{"cityName":"芜湖","cityCode":"0553"},{"cityName":"南通","cityCode":"0513"},{"cityName":"南昌","cityCode":"0791"},{"cityName":"石家庄","cityCode":"0311"},{"cityName":"潍坊","cityCode":"0536"},{"cityName":"嘉兴","cityCode":"0573"},{"cityName":"金华","cityCode":"0579"},{"cityName":"绍兴","cityCode":"0575"},{"cityName":"烟台","cityCode":"0535"},{"cityName":"扬州","cityCode":"0514"},{"cityName":"昆山","cityCode":"0512"},{"cityName":"佛山","cityCode":"0757"},{"cityName":"东莞","cityCode":"0769"},{"cityName":"马鞍山","cityCode":"0555"}]';
	
	function __construct(){
		parent::__construct();
	}


	public function index(){

        $filter = array();
        $filter['inter_id'] = $this->inter_id;
        //get请求接收参数
        $params= $this->input->get();
        if(is_array($params) && count($params)>0 )
            $params= array_merge($params, $filter);
        //post请求接收参数
        $post = $this->input->post();
        if(is_array($post)){
            $params = array_merge($post,$filter);
        }//var_dump($this->uri->segment(3));die;
       // $filter['hotel_id'] = empty($this->hotel_id)?'':$this->hotel_id;
        $filter['wd'] = $this->input->get('wd')?addslashes($this->input->get('wd')):'';
        $search_url = '?';
        if($filter['wd']!=''){
            $search_url .= 'wd='.$filter['wd'];
        }

        //获取公众号下的酒店
        $this->load->model ( 'hotel/hotel_model' );
        $filterH = array('inter_id'=>$this->inter_id);
        if(!empty($this->session->get_admin_hotels())){
            $filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
            $filter['in_hotel_id'] = explode(',',$this->session->get_admin_hotels());
        }
        $hotels = $this->hotel_model->get_hotel_hash ($filterH );
        $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );

        $per_page = 30;
        $cur_page = empty($this->uri->segment(4)) ? 0 : ($this->uri->segment(4));
        $this->load->model('roomservice/roomservice_dada_model');//var_dump($filter);die;
        $res = $this->roomservice_dada_model->get_page($filter,$cur_page,$per_page);
        $data = isset($res[1])?$res[1]:array();
        $total_count = isset($res[0])?$res[0]:0;
        $base_url = site_url('/take-away/dada/index/');
        $first_url = site_url('/take-away/dada/index/').$search_url;
        $suffix = $search_url;
        //分页
        $this->pagination($per_page,$cur_page,$base_url,$total_count,4,$first_url,$search_url,$suffix);
        $view_params = array (
            'pagination' => $this->pagination->create_links (),
            'hotels'=>$hotels,
            'res' =>$data,
            'inter_id' => $this->inter_id,
            'total'=>$total_count,
        );
        echo $this->_render_content ( $this->_load_view_file ( 'index' ), $view_params, TRUE );
	}
	
	public function add()
	{
		if($this->inter_id== FULL_ACCESS){
            $message= $this->session->put_notice_msg('超管!');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
        };
       $post =  $this->input->post ();
       // $submit = addslashes($this->input->post('submit'));
        if($post){//add数据
            if(empty($post['app_secret'])||empty($post['source_id'])){
                $this->session->put_notice_msg('关键数据不能为空！');
                $this->_redirect(EA_const_url::inst()->get_url('*/*/add'));
            }
            $filter = array();
            $filter['inter_id'] = $this->inter_id;
            $filter['hotel_id'] = !empty($post['hotel_id'])?intval($post['hotel_id']):'';
            $filter['status'] = $post['status'];
            $filter['app_key'] = !empty($post['app_key'])? trim($post['app_key']):'';
            $filter['app_secret'] = empty($post['app_secret'])?'':trim($post['app_secret']);
            $filter['source_id'] = empty($post['source_id'])?'': trim($post['source_id']);
            $filter['shop_no'] = empty($post['shop_no'])?'':$post['shop_no'];
            $filter['is_prepay'] = empty($post['is_prepay'])?1:$post['is_prepay'];
            $filter['status'] = empty($post['status'])?1:$post['status'];
            $filter['city_code'] = empty($post['city_code'])?'':$post['city_code'];
            $filter['create_time'] = date('Y-m-d H:i:s');
            $filter['expected_fetch_time'] = empty($post['expected_fetch_time'])?0:$post['expected_fetch_time'];
            $filter['type'] =  empty($post['type'])? '2' : intval($post['type']);
            $filter['callback_status'] =  empty($post['callback_status'])? '1' : intval($post['callback_status']);
            $filter['api_status'] =  empty($post['api_status'])? '1' : intval($post['api_status']);
            $result =  $this->db->insert('roomservice_dada',$filter);
            $message= ($result)?
                $this->session->put_success_msg('新增成功'):
                $this->session->put_notice_msg('新增失败');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
            die;
        }
        //页面
        //获取公众号下的酒店
        $this->load->model ( 'hotel/hotel_model' );
        $filterH = array('inter_id'=>$this->inter_id);
        if(!empty($this->session->get_admin_hotels())){
            $filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
        }
        $hotels = $this->hotel_model->get_hotel_hash ($filterH );
        $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
        $dadacity = json_decode($this->city,true);
        $city_tmp = array();
        foreach($dadacity as $k=>$v){
            $city_tmp[$v['cityCode']]=$v['cityName'];
        }
        $view_params = array(
            'hotel' => $hotels,
            'dadacity'=>$city_tmp,
        );
        $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
        echo $html;
	}

    //编辑
    public function edit()
    {
        if($this->inter_id== FULL_ACCESS){
            $message= $this->session->put_notice_msg('超管!');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
        };
        $post =  $this->input->post ();
        $id = $this->input->get('ids',true);
        if(empty($id)){
            echo 'empty id';
            die;
        }//var_dump($post);die;
        if($post&&$id){//update数据
            $filter = array();
            $filter['inter_id'] = $this->inter_id;
            $filter['hotel_id'] = !empty($post['hotel_id'])?intval($post['hotel_id']):'';
            $filter['hotel_id'] = $post['hotel_id'];
            $filter['status'] = $post['status'];
            if(!empty($post['app_key'])){
                $filter['app_key'] = trim($post['app_key']);
            }
            if(!empty($post['app_secret'])){
                $filter['app_secret'] = trim($post['app_secret']);
            }
            if(!empty($post['source_id'])){
                $filter['source_id'] = trim($post['source_id']);
            }
            if(!empty($post['shop_no'])){
                $filter['shop_no'] = $post['shop_no'];
            }
            $filter['is_prepay'] = empty($post['is_prepay'])?1:$post['is_prepay'];
            $filter['status'] = empty($post['status'])?1:$post['status'];
            $filter['city_code'] = empty($post['city_code'])?'':$post['city_code'];
            $filter['expected_fetch_time'] = empty($post['expected_fetch_time'])?0:$post['expected_fetch_time'];

            $filter['callback_status'] =  empty($post['callback_status'])? '1' : intval($post['callback_status']);
            $filter['api_status'] =  empty($post['api_status'])? '1' : intval($post['api_status']);

            $where= array('inter_id'=>$this->inter_id,'id'=>$id);
            $result =  $this->db->update('roomservice_dada',$filter,$where);
            $message= ($result)?
                $this->session->put_success_msg('更新成功'):
                $this->session->put_notice_msg('更新失败');
            $this->_redirect(EA_const_url::inst()->get_url('*/*/index'));
            die;
        }
        //获取该条信息
        $this->load->model('roomservice/roomservice_dada_model');//var_dump($filter);die;
        $res = $this->roomservice_dada_model->get(array('id'=>$id));
        if(!empty($res)){
            //获取公众号下的酒店
            $this->load->model ( 'hotel/hotel_model' );
            $filterH = array('inter_id'=>$this->inter_id);
            if(!empty($this->session->get_admin_hotels())){
                $filterH['hotel_id'] = explode(',',$this->session->get_admin_hotels());
            }
            $hotels = $this->hotel_model->get_hotel_hash ($filterH );
            $hotels = $this->hotel_model->array_to_hash ( $hotels, 'name', 'hotel_id' );
            $dadacity = json_decode($this->city,true);
            $city_tmp = array();
            foreach($dadacity as $k=>$v){
                $city_tmp[$v['cityCode']]=$v['cityName'];
            }
            //将数据用*代替输出显示
            foreach($res as $rk=>$rv){
                if($rk=='app_key' || $rk=='app_secret' || $rk=='source_id' || $rk=='shop_no'){
                    $res[$rk] = substr_replace($rv,'****',strlen ( $rv ) * 0.2,strlen ( $rv ) * 0.5);
                }
            }
            $view_params = array(
                'id'=>$id,
                'posts' =>$res,
                'hotel' => $hotels,
                'dadacity'=>$city_tmp,
            );
            $html= $this->_render_content($this->_load_view_file('edit'), $view_params, TRUE);
            echo $html;
        }else{
            echo 'empty data';
            die;
        }

    }

}
