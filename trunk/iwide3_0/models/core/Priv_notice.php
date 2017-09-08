<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Priv_notice extends MY_Model
{

    /***** 以下为必填函数信息  *****/
    public function get_resource_name()
    {
        return '公告列表';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function table_name()
    {
        return 'notice';
    }

    public function table_primary_key()
    {
        return 'id';
    }

    /**
     * 定义字段标签名称，数组的key一定要严格按照实际数据库字段
     * @return array
     */
    public function attribute_labels()
    {
        return array(
            'id' => 'ID',
            'title' => '公告标题',
            'ymd' => '创建日期',
            'content' => '公告内容',
            'file_url' => '操作手册',
            'file_name' => '操作手册',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
            'update_by' => '修改者',
            'create_by' => '修改时间',
            'status' => '状态',
        );
    }

    /**
     * 后台管理的表格中要显示哪些字段
     */
    public function grid_fields()
    {
        return array('id', 'title', 'ymd');
    }

    /**
     *   在EasyUI grid中的 date-option 定义，包括宽度，是否排序等等
     *   type: grid中的表头类型定义
     *   form_type: form中的元素类型定义
     *   form_ui: form中的属性补充定义，如加disabled 在<input “disabled” /> 使元素禁用
     *   form_tips: form中的label信息提示
     *   form_default: form中的默认值，请用字符类型，不要用数字
     *   select: form中的类型为 combobox时，定义其下来列表
     */
    public function attribute_ui()
    {
        //tp: numberbox数字框|combobox下拉框|text不写时默认|datebox
        /** @var EA_base $base_util */
        $base_util = EA_base::inst();
        return array(
            'id' => array(
                'grid_ui' => '',
                'grid_width' => '5%',
                'form_ui' => '',
                'type' => 'text',
                'hide' => true
            ),
            'title' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                'form_ui' => '',
                'type' => 'text',
            ),
            'content' => array(
                'grid_ui' => '',
                'grid_width' => '100%',
                'form_ui' => '',
                'type' => 'textarea',
            ),
            'ymd' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                'form_ui' => '',
                'form_type' => 'form',
                'form_hide' => true,
            ),
            'file_name' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                'form_ui' => '',
                'type' => 'text',
                'form_type' => 'text',
            ),
            'file_url' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                'form_ui' => 'hidden',
                'type' => 'text',
                'form_type' => 'form',
//                'form_hide' => true
            ),
            'create_by' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                'form_ui' => 'hidden',
                'type' => 'text',
                'form_type' => 'form',
                'form_hide' => true
            ),
            'update_by' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                'form_ui' => 'hidden',
                'type' => 'text',
                'form_type' => 'form',
                'form_hide' => true
            ),
            'update_time' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                'form_ui' => 'hidden',
                'type' => 'text',
                'form_type' => 'form',
                'form_hide' => true
            ),
            'create_time' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                'form_ui' => 'hidden',
                'type' => 'text',
                'form_type' => 'form',
                'form_hide' => true
            ),
            'status' => array(
                'grid_ui' => '',
                'grid_width' => '10%',
                'form_ui' => 'hidden',
                'type' => 'combobox',
                'select'=> $base_util::get_status_options(),
            ),

        );
    }

    /**
     * grid表格中默认哪个字段排序，排序方向
     */
    public static function default_sort_field()
    {
        return array('field' => 'ymd', 'sort' => 'desc');
    }

    /**
     * grid表格中的过滤器匹配方式数组
     */


    /***** 以下上为必填函数信息  *****/

    /**
     * 获取最新的公告
     * @return Priv_notice
     */
    public function getLast()
    {
        $table = $this->table_name();
        /** @var CI_DB_query_builder $db */
        $db = $this->_db();
        /** @var CI_DB_mysqli_result $result */
        $result = $db
            ->select(['title', 'id'])
            ->from($table)
            ->order_by('id', 'DESC')
            ->where('status', 1)
            ->limit(1)
            ->get();
        return $result->first_row();
    }

}
