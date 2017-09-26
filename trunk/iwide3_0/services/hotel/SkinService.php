<?php

namespace App\services\hotel;

use App\libraries\Support\Log;
use App\services\HotelBaseService;
use App\services\Result;

if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

class SkinService extends HotelBaseService
{
    public $common_data;
    public $module;
    public $share;

    /**
     * 获取服务实例方法
     * @return HotelService
     */
    public static function getInstance()
    {
        return self::init(self::class);
    }

    function __construct()
    {
        parent::__construct();

        $this->module = 'hotel';
//        \MYLOG::hotel_tracker($this->getCI()->openid, $this->getCI()->inter_id);
//        $this->module = 'hotel';
//        $this->member_no = '';
//        $this->member_lv = '';
//        if (!empty ($this->getCI()->member_info) && isset ($this->getCI()->member_info->mem_id)) {
//            $this->member_no = $this->getCI()->member_info->mem_card_no;
//            $this->member_lv = $this->getCI()->member_info->level;
//        }
//        $this->getCI()->load->model('wx/Access_token_model');
//        $this->common_data ['signPackage'] = $this->getCI()->Access_token_model->getSignPackage($this->getCI()->inter_id);
//        $this->common_data ['pagetitle'] = $this->getCI()->public ['name'];
//        $this->common_data ['member'] = $this->getCI()->member_info;
//        $this->common_data ['inter_id'] = $this->getCI()->inter_id;
//        $this->common_data ['csrf_token'] = $this->getCI()->security->get_csrf_token_name();
//        $this->common_data ['csrf_value'] = $this->getCI()->security->get_csrf_hash();
//        $this->share ['title'] = $this->getCI()->public ['name'] . '-微信订房';
//        $slink = $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
//        if (strpos($slink, '?'))
//            $slink = $slink . "&id=" . $this->getCI()->inter_id;
//        else
//            $slink = $slink . "?id=" . $this->getCI()->inter_id;
//        $this->share ['link'] = $slink;
//        $this->share ['imgUrl'] = 'http://7n.cdn.iwide.cn/public/uploads/201609/qf051934149038.jpg';
//        $this->share ['desc'] = $this->getCI()->public ['name'] . '欢迎您使用微信订房,享受快捷服务...';
//        $this->share ['type'] = '';
//        $this->share ['dataUrl'] = '';
//        $this->common_data ['share'] = $this->share;
//
//        $this->common_data ['index_url'] = $this->getCI()->public ['is_multy'] == 1 ? \Hotel_base::inst()->get_url('INDEX') : \Hotel_base::inst()->get_url('SEARCH');;
    }


    /**
     * 保存皮肤详情设置
     * 分享设置从hotel_config表取 首页样式从enum_desc表取  轮播图从public_images表取 view_skin_set取字体设置
     * 标准版  logo和底部菜单从hotel_config表取
     * @param $post
     * @return bool
     * @author daikanwu <daikanwu@jperation.com>
     */
    public function save_setting($post)
    {

        $this->getCI()->load->model('common/Skins_model');

        //开启一个事务
        $this->getCI()->db->trans_start();

        //保存分享设置
        if (!empty($post['share_setting'])) {
            $data['id'] = $post['share_setting']['id'] ? $post['share_setting']['id'] : 0;
            $data ['param_value'] = json_encode(
                [
                    'page_title' => $post['share_setting']['page_title'],
                    'page_desc' => $post['share_setting']['page_desc'],
                    'share_icon' => $post['share_setting']['share_icon'],
                ]
            );
            $data ['param_name'] = 'SHARE_SETTING';
            $data ['module'] = 'HOTEL';
            $data ['inter_id'] = $this->getCI()->inter_id;
            $data ['hotel_id'] = 0;
            $this->getCI()->load->model('hotel/hotel_config_model');
            $this->getCI()->hotel_config_model->replace_config($data);
        }

        //保存首页样式 页面logo 底部菜单
        $home_disp = $post['home_setting']['home_disp'];
        $disp_set = $this->getCI()->Skins_model->get_disp_set($this->inter_id, 'hotel/search');
        $selected_skin = $this->Skins_model->get_skin_set($this->inter_id, $this->module);
        $set = array();
        if (!empty ($disp_set)) {
            if ($home_disp == 'new' && $selected_skin['skin_name'] == 'default2') {
                $set ['view_subfix'] = 'new';
            } else {
                $set ['view_subfix'] = '';
            }
            $this->getCI()->Skins_model->update_disp_set($this->inter_id, $this->module, $disp_set ['id'], $set);
        } else {
            if ($home_disp == 'new' && $selected_skin['skin_name'] == 'default2') {
                $set ['view_subfix'] = 'new';
                $set ['func'] = 'search';
                $this->getCI()->Skins_model->add_skin_set($this->inter_id, $this->module, array(
                    'skin_name' => 'default2'
                ));
                $this->getCI()->Skins_model->add_disp_set($this->inter_id, $this->module, $set);
            }
        }


        $id = $post['home_setting']['id'];
        if ($id > 0) {
            $setting_data ['id'] = $id;
        } else {
            $setting_data ['id'] = 0;
        }

        // 描述过滤标签
        $post_menu = $post['home_setting']['menu'];
        foreach ($post_menu as &$val) {
            $val['desc'] = strip_tags($val['desc']);
        }
        unset($val);

        $tmp = [
            'home_disp' => $home_disp,
            'img' => $post['home_setting']['logo'],
            'open' => 2,
            'menu' => $post_menu  //起始从1开始
        ];

        $setting_data ['param_value'] = json_encode($tmp);
        $setting_data ['param_name'] = 'HOME_SETTING';
        $setting_data ['module'] = 'HOTEL';
        $setting_data ['inter_id'] = $this->getCI()->inter_id;
        $setting_data ['hotel_id'] = 0;
        $this->getCI()->load->model('hotel/hotel_config_model');
        $this->getCI()->hotel_config_model->replace_config($setting_data);
//
        //保存字体设置
        $skin_set = $this->getCI()->Skins_model->get_skin_set($this->getCI()->inter_id, $this->module);

        $font_set ['overall_style'] = json_encode(
            [
                'theme_color' => $post['font_setting']['font_color'],
                'fontx' => $post['font_setting']['font_size']
            ]
        );

        // 已有的话更新 没有则新增
        if (!empty($skin_set)) {
            $this->getCI()->Skins_model->update_skin_set($this->getCI()->inter_id, 'hotel', $skin_set ['id'], $font_set);
        } else {
            $font_set['skin_name'] = $post['skin_name'];
            $this->getCI()->Skins_model->add_skin_set($this->getCI()->inter_id, $this->module, $font_set);
        }

        // 保存轮播图
        if (!empty($post['roasting_setting'])) {
            $this->getCI()->load->model('wx/Publics_model');

            foreach ($post['roasting_setting'] as $v) {
                $v['inter_id'] = $this->getCI()->inter_id;
                $this->getCI()->Publics_model->update_focus_new($v);
            }
        }

        $this->getCI()->db->trans_complete();

        if ($this->getCI()->db->trans_status() === FALSE) {
            return false;
        }

        return true;
    }

    /**
     * 获取皮肤的配置
     * 分享设置从hotel_config表取 首页样式从enum_desc表取  轮播图从public_images表取 view_skin_set取字体设置
     * 标准版  logo和底部菜单从hotel_config表取
     * @return array
     * @author daikanwu <daikanwu@jperation.com>
     */
    public function get_setting()
    {
        //获取分享配置
        $this->getCI()->load->model('hotel/hotel_config_model');

        $share = $this->getCI()->hotel_config_model->get_hotels_config_row(
            $this->getCI()->inter_id, 'HOTEL', 0, 'SHARE_SETTING'
        );
        if (!empty($share['param_value'])) {
            $share['param_value'] = json_decode($share['param_value'], true);
        }

        //获取首页样式
        $this->getCI()->load->model('common/Enum_model');
        $page_ori_type = $this->getCI()->Enum_model->get_enum_des('HOME_ORI_PAGE_TYPE', 1, $this->getCI()->inter_id);
        $page_new_type = $this->getCI()->Enum_model->get_enum_des('HOME_NEW_PAGE_TYPE', 1, $this->getCI()->inter_id);

        $tmp = [];
        if (!empty($page_ori_type)) {
            $tmp['ori'] = ['image' => implode(',', array_keys($page_ori_type)), 'desc' => '简约样式图'];
        }
        if (!empty($page_new_type)) {
            $tmp['new'] = ['image' => implode(',', array_keys($page_new_type)), 'desc' => '标准样式图'];
        }

        //轮播图
        $this->getCI()->load->model('wx/Publics_model');
        $roast = $this->getCI()->Publics_model->get_pub_imgs($this->getCI()->inter_id, 'hotelslide');

        //字体设置
        $this->getCI()->load->model('common/Skins_model');
        $skin_set = $this->getCI()->Skins_model->get_skin_set($this->getCI()->inter_id, $this->module);

        //logo 和底部菜单
        $logo = $this->getCI()->hotel_config_model->get_hotels_config_row(
            $this->getCI()->inter_id, 'HOTEL', 0, 'HOME_SETTING'
        );
        if (!empty($logo)) {
            $logo['param_value'] = json_decode($logo['param_value'], true);
        }

        $res = [
            'share' => empty($share) ? [] : $share,
            'page_type' => $tmp,
            'roast' => empty($roast) ? [] : $roast,
            'font' => empty($skin_set['overall_style']) ? ['theme_color' => '#FF9900', 'fontx' => '14px'] : json_decode($skin_set['overall_style'], true),
            'home_setting' => empty($logo) ? [] : $logo,

            //前端要返回的图片
            'demonamemap' => [
                'share' => 'http://7n.cdn.iwide.cn/public/uploads/201709/qf211936056283.jpg',
                'roast' => [
                    'ori' => 'http://7n.cdn.iwide.cn/public/uploads/201709/qf211936462095.jpg',
                    'new' => 'http://7n.cdn.iwide.cn/public/uploads/201709/qf211938244896.jpg',
                    'bigger' => 'http://7n.cdn.iwide.cn/public/uploads/201709/qf211938496093.jpg',
                    'highclass' => 'http://7n.cdn.iwide.cn/public/uploads/201709/qf211939156479.jpg'
                ],
                'color' => 'http://7n.cdn.iwide.cn/public/uploads/201709/qf211939398173.jpg',
                'menu' => [
                    'new' => 'http://7n.cdn.iwide.cn/public/uploads/201709/qf211940045285.jpg',
                    'bigger' => 'http://7n.cdn.iwide.cn/public/uploads/201709/qf211940232033.jpg',
                    'highclass' => 'http://7n.cdn.iwide.cn/public/uploads/201709/qf211940397294.jpg'
                ]
            ]
        ];

        return $res;
    }
}