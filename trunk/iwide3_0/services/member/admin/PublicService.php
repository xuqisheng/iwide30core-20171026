<?php

namespace App\services\member\admin;

use App\services\MemberBaseService;
use EA_const_url;

/**
 * Class PublicService
 * @package App\services\member\admin
 * @author liwensong [septet-l@outlook.com]
 */
class PublicService extends MemberBaseService
{
    protected $saler_protected = false;
    protected $saler_protected_info = array();
    protected $saler_id = 0;
    protected $res_data = array();

    public function __construct()
    {
        $this->res_data = array(
            'status' => 2,
            'data' => array()
        );
    }

    /**
     * 获取服务实例方法
     * @return PublicService
     */
    public static function getInstance()
    {
        return self::init(self::class);
    }

    public static function getDefaultColumn()
    {
        $conf = array(
            'balance_use' => 't',
            'credit_use' => 't',
            'balance' => array(
                'default' => 'f',
                'name' => '余额'
            ),
            'credit' => array(
                'default' => 'f',
                'name' => '积分'
            ),
        );
        return $conf;
    }

    public static function getAuth($column_num = 1, $template = 'phase2'){
        switch ($column_num){
            case 1: //栏目开关
                $auth_arr = array(
                    'highclass',
                    'highclass#white'
                );
                break;
            case 2: //栏目更名
                $auth_arr = array(
                    'highclass',
                    'highclass#white',
                    'phase2',
                    'version4',
                    'green',
                    'yasite'
                );
                break;
            case 3: //订单入口开关
                $auth_arr = array(
                    'highclass',
                    'highclass#white'
                );
                break;
            case 4: //更换按钮颜色
                $auth_arr = array(
                    'highclass',
                    'highclass#white',
                    'phase2',
                    'green',
                    'yasite'
                );
                break;
            case 5: //更换按钮文字颜色
                $auth_arr = array(
                    'highclass',
                    'highclass#white',
                    'phase2',
                    'green',
                    'yasite'
                );
                break;
            case 6: //更换Banner颜色
                $auth_arr = array(
                    'phase2',
                    'green',
                    'yasite'
                );
                break;
            case 7: //菜单图标设置
                $auth_arr = array(
                    'highclass',
                    'highclass#white',
                    'phase2',
                    'green',
                    'yasite',
                    'version4'
                );
                break;
            case 8: //菜单名称&链接设置
                $auth_arr = array(
                    'highclass',
                    'highclass#white',
                    'phase2',
                    'green',
                    'yasite',
                    'version4'
                );
                break;
            case 9: //菜单登陆后显示
                $auth_arr = array(
                    'highclass',
                    'highclass#white',
                    'phase2',
                    'green',
                    'yasite'
                );
                break;
            case 10: //菜单登陆后显示
                $auth_arr = array(
                    'highclass',
                    'highclass#white'
                );
                break;
            default:
                $auth_arr = array();
                break;
        }
        if(in_array($template,$auth_arr)) return true;
        return false;
    }

    public static function get_module_link($id = ''){
        return array(
            'depositcard' => array(
                'name' => '购买会员卡',
                'url' => EA_const_url::inst()->get_url('membervip/depositcard',array('id'=>$id))
            ),
            'buydeposit' => array(
                'name' => '会员充值',
                'url' => EA_const_url::inst()->get_url('membervip/depositcard/buydeposit',array('id'=>$id))
            ),
            'sign' => array(
                'name' => '积分签到',
                'url' => EA_const_url::inst()->get_url('membervip/sign',array('id'=>$id))
            ),
            'invitate' => array(
                'name' => '邀请好友',
                'url' => EA_const_url::inst()->get_url('membervip/invitate',array('id'=>$id))
            ),
            'invitatedkim' => array(
                'name' => '邀金活动',
                'url' => EA_const_url::inst()->get_url('membervip/invitatedkim/raiders',array('id'=>$id))
            ),
            'distribute' => array(
                'name' => '分销中心',
                'url' => EA_const_url::inst()->get_url('distribute/distribute/reg',array('id'=>$id))
            ),
            'club' => array(
                'name' => '社群客',
                'url' => EA_const_url::inst()->get_url('club/club',array('id'=>$id))
            ),
            'hotel' => array(
                'name' => '订房首页',
                'url' => EA_const_url::inst()->get_url('hotel/hotel/search',array('id'=>$id))
            ),
            'soma' => array(
                'name' => '商城首页',
                'url' => EA_const_url::inst()->get_url('soma/package',array('id'=>$id))
            )
        );
    }

    public static function index_input_fields(){

    }
}