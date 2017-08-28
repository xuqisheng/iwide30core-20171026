<?php

namespace App\services\member;

use App\services\MemberBaseService;
use MYLOG;

/**
 * Class SupportService
 * @package App\services\member
 * @author lijiaping  <lijiaping@mofly.cn>
 */
class SupportService extends MemberBaseService
{
    protected $saler_protected = false;
    protected $saler_protected_info = array();
    protected $saler_id = 0;

    /**
     * 获取服务实例方法
     * @return SupportService
     */
    public static function getInstance()
    {
        return self::init(self::class);
    }

    /**
     * 检测分销保护状态
     * @param string $inter_id 酒店集团ID
     */
    private function distribution_protection_config($inter_id = '')
    {
        $this->saler_protected_info = $this->idistribute_model()->get_distribution_protection_config($inter_id);
        MYLOG::w("get_distribution_protection_config :" . json_encode($this->saler_protected_info) . '|' . $inter_id, 'membervip/debug-log');
        if (!empty($this->saler_protected_info) && $this->saler_protected_info->status == 'OPEN') {
            $this->saler_protected = true;
        }
    }

    /**
     * 获取分销实例
     * @return mixed
     */
    private function idistribute_model()
    {
        if (!isset($this->getCI()->idistribute_model)) {
            $this->getCI()->load->model('distribute/Idistribute_model', 'idistribute_model');
        }
        return $this->getCI()->idistribute_model;
    }

    /**
     * 检测和获取分销员ID
     * @param string $inter_id 酒店集团ID
     * @param string $openid 用户微信ID
     * @param int $saler_id 分销员ID
     * @param string $type 模块
     * @return int|mixed
     */
    public function check_set_saler($inter_id = '', $openid = '', $saler_id = 0, $type = '')
    {
        $this->distribution_protection_config($inter_id); //检测分销保护状态
        $this->saler_id = $saler_id;
        $types = array('reg', 'pan');
        if ($this->saler_protected === true) { //已开启分销保护
            if (empty($saler_id) OR $saler_id === 0) {
                $saler_info = $this->idistribute_model()->get_protection_saler($openid, $inter_id);
                MYLOG::w("get_protection_saler :" . json_encode($saler_info) . '|' . $saler_id . '|' . $inter_id . " | " . $openid, 'membervip/debug-log');
                if (!empty($saler_info)) {
                    $saler_info = (array)$saler_info;
                    if ($saler_info['protect_to'] > time()) { //在保护期内
                        $this->saler_id = $saler_info['saler'];
                    }
                }
            } else {
                $saler_info = $this->idistribute_model()->save_saler_protection_info($inter_id, $openid, $_SERVER['REQUEST_URI'], $saler_id, time(), 'menbervip');
                MYLOG::w("save_saler_protection_info :" . json_encode($saler_info) . '|' . $saler_id . '|' . $inter_id . " | " . $openid, 'membervip/debug-log');
            }
        } elseif (empty($this->saler_id) && !in_array($type, $types)) {
            $this->saler_id = $this->getCI()->session->userdata('salesId');
        }
        return $this->saler_id;
    }
}