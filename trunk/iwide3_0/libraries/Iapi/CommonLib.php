<?php
namespace App\libraries\Iapi;

class CommonLib
{
    /**
     * @param unknown $data 传入数据，只能为数组
     * @param unknown $mode 数据筛选模板 Wxapp_conf中$dehydrate_samples定义
     * @return unknown|NULL[]|unknown
     */
    public static function data_dehydrate($data, $mode)
    {
        if (empty ($mode)) {
            return $data;
        }
        $tmp = array();
        if (!empty ($mode ['keys'])) {
            $mode ['keys'] = array_flip($mode ['keys']);
            $tmp = array_intersect_key($data, $mode ['keys']);
        }
        if (!empty ($mode ['arr'])) {
            foreach ($mode ['arr'] as $mk => $mod) {
                $tmp [$mk] = isset ($data [$mk]) ? self::data_dehydrate($data [$mk], $mod) : null;
            }
        }
        if (!empty ($mode ['mul_arr'])) {
            foreach ($mode ['mul_arr'] as $mk => $mod) {
                if (isset ($data [$mk])) {
                    foreach ($data [$mk] as $fk => $fm) {
                        $tmp [$mk] [$fk] = self::data_dehydrate($fm, $mod);
                    }
                } else {
                    $tmp [$mk] = null;
                }
            }
        }

        return $tmp;
    }

    public static function create_put_msg($web_area, $result, $msg = '', $data = array(), $fun = '', $extra = array(), $msg_lv = 0)
    {
        if ($web_area == 'jwx') {
            $const_obj = new FrontConst ();
        } elseif ($web_area == 'jmp') {
            $const_obj = new AdminConst ();
        } else {
            return array(
                'status' => '1004',
                'msg'    => 'no web area',
            );
        }

        $info = array();
        $status_arr = $const_obj::$oper_status;
        $msg_lvs = $const_obj::$msg_lv;
        $result = isset ($status_arr [$result]) ? $status_arr [$result] : 1004;
        $info ['status'] = $result;
        $info ['msg'] = $msg;
        $info ['msg_type'] = $msg_lvs [$msg_lv];
        $info ['web_data'] = array();
        if (!empty ($data)) {
            $data = json_decode(json_encode($data), true);
            $info ['web_data'] = self::data_dehydrate($data, $const_obj::get_dehydrate_samples($fun));
        }
        isset ($extra ['links']) and $info ['web_data'] ['page_resource'] ['links'] = $extra ['links'];
        isset ($extra ['page']) and $info ['web_data'] ['page_resource'] ['page'] = $extra ['page'];
        isset ($extra ['count']) and $info ['web_data'] ['page_resource'] ['count'] = $extra ['count'];
        isset ($extra ['size']) and $info ['web_data'] ['page_resource'] ['size'] = $extra ['size'];

        return $info;
    }
}