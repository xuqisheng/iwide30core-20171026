<?php

namespace App\services\member;

use App\services\MemberBaseService;
use EA_const_url;
/**
 * Class PosterService
 * @package App\services\member
 * @author lijiaping  <lijiaping@mofly.cn>
 */
class PosterService extends MemberBaseService
{

    private   $res_data = array();
    protected $args = array();

    //加载基本类、设置基础信息
    public function getBase(){
        $this->getCI()->load->library("MYLOG");
        $this->getCI()->load->helper('common_helper');
        $this->res_data = array(
            'status'=>2,
            'data'=>array()
        );
    }

    /**
     * 获取服务实例方法
     * @return PosterService
     */
    public static function getInstance()
    {
        return self::init(self::class);
    }


    //我的二维码海报（分销）
    public function mineposter($inter_id = '', $openid = '' , $user_info = array()){
        $data = array();
        $face = 'noface';
        $this->getCI()->load->model('distribute/Distribute_ext_model','distribute_ext_model');
        $is_fens = $this->getCI()->distribute_ext_model->check_fans($inter_id,$openid,false);
        if($this->getCI()->input->get('debug')=='on'){
            var_dump($is_fens);
        }
        $identity = '';
        $buycount = '';
        if(!empty($is_fens)){
            $service = \App\services\soma\IdistributeService::getInstance();
            if($is_fens['typ'] == 'FANS' OR $is_fens['typ'] == 'STAFF'){
                $identity = '招募者';
            }

            if($is_fens['typ'] == 'FANS' && isset($is_fens['info'])){
                $is_fens['info'] = (array) $is_fens['info'];
                $fansSalerData = $service->getSalerProductSalesInfo($inter_id,'150812',$is_fens['info']['saler'],1);
                if(!empty($fansSalerData['status']) && $fansSalerData['status'] == '1'){
                    $buycount =!empty($fansSalerData['data']['150812']) ? floatval($fansSalerData['data']['150812']) : 0;
                }
            }elseif ($is_fens['typ'] == 'STAFF' && isset($is_fens['info'])){
                $is_fens['info'] = (array) $is_fens['info'];
                $salerData = $service->getSalerProductSalesInfo($inter_id,'150812',$is_fens['info']['saler'],2);
                if(!empty($salerData['status']) && $salerData['status'] == '1'){
                    $buycount =!empty($salerData['data']['150812']) ? floatval($salerData['data']['150812']) : 0;
                }
            }
        }

        if(empty($identity)){
            $this->res_data['status'] = 3;
            $this->res_data['msg'] = '您不是粉丝分销员或员工分销员';
            $this->res_data['jump'] = 1;
            $this->res_data['redirect_uri'] = 'http://1.njt3s.com/index.php/soma/package/package_detail?pid=150812&id=a490782373';
            $this->res_data['data'] = array();
            return $this->res_data;
        }

        $identity2 = '';

        if($buycount > 0){
            $identity2 = '馅饼侠';
            $face = 'face';
        }

        $Aug20 = '2017-08-20';
        $nowDate = date('Y-m-d');
        $count_down = 0;
        if(strtotime($Aug20) > strtotime($nowDate)){
            $count_down = date('d',strtotime($Aug20)) - date('d',strtotime($nowDate));
        }elseif(strtotime($Aug20) < strtotime($nowDate)){
            $count_down = -1;
        }

        $lvl_name = '青铜';
        if($buycount >= 1 && $buycount <= 4){
            $lvl_name = '白银';
        }elseif ($buycount >= 5 && $buycount <= 9){
            $lvl_name = '黄金';
        }elseif ($buycount >= 10){
            $lvl_name = '王者';
        }

        $data['identity_type'] = $face;
        $data['nickname'] = !empty($user_info['nickname']) ? $user_info['nickname'] : '微信粉丝';
        $data['countdown'] = $count_down;
        $data['buycount'] = $buycount;
        $data['endtime'] = '8月20日24:00';
        $data['identity'] = $identity;
        $data['identity2'] = $identity2;
        $data['lvl_name'] = $lvl_name;

        $this->res_data['status'] = 1;
        $this->res_data['msg_lvl'] = 1;
        $this->res_data['msg'] = 'ok';
        $this->res_data['data'] = $data;
        return $this->res_data;
    }
}