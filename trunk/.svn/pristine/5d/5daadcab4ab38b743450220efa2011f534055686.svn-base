<?php

/**
 * Created by PhpStorm.
 * User: ibuki
 * Date: 16/8/10
 * Time: 下午12:09
 */
class Member_buycardrecord_model extends MY_Model_Member {
    /**
     * @return string the associated database table name
     */
    public function table_name($inter_id=NULL){
        return 'deposit_card_pay';
    }

    /**
     * @return string the associated database jion table name
     */
    public function join_table_name($inter_id=NULL){
        return 'deposit_card';
    }

    public function join_table_fields(){
        return 'deposit_card.deposit_card_id,deposit_card.deposit_type,deposit_card.inter_id';
    }

    public function _join_table_fields(){
        return array('deposit_card.deposit_type'=>'g');
    }

    /**
     * 返回关联条件
     * @return array
     */
    public function join_table_where(){
        return $this->join_table_name().'.deposit_card_id = '.$this->table_name().'.deposit_card_id';
    }

    /**
     * 返回表deposit_card_pay的主键
     * @return string
     */
    public function table_primary_key() {
        return 'deposit_card_pay_id';
    }

    /**
     * 后台模版表格表头字典
     * @return array
     */
    public function attribute_labels() {
        return array(
            'deposit_card_pay_id'=> 'ID',
            'member_lvl_name'=>'会员卡名称',
            'name'=> '会员名称',
            'nickname'=> '微信昵称',
            'membership_number'=> '会员卡号',
            'pay_money'=> '购卡金额(元)',
            'deposit'=> '赠储值',
            'credit'=> '赠积分',
            'card_count'=> '优惠劵(张)',
            'distribution_num'=>'分销号',
            'createtime'=> '购卡时间',
        );
    }

    /**
     * 后台管理的表格中要显示哪些字段
     */
    public function grid_fields() {
        //主键字段一定要放在第一位置，否则 grid位置会发生偏移
        return array(
            'deposit_card_pay_id',
            'member_lvl_name',
            'name',
            'nickname',
            'membership_number',
            'pay_money',
            'deposit',
            'credit',
            'card_count',
            'distribution_num',
            'createtime',
        );
    }

    //定义 m_save 保存时不做转义字段
    public function unaddslashes_field() {
        return array(
            'msg',
            'result',
            'content',
        );
    }

    /**
     * 在EasyUI grid中的 date-option 定义，包括宽度，是否排序等等
     *   type: grid中的表头类型定义
     *   form_type: form中的元素类型定义
     *   form_ui: form中的属性补充定义，如加disabled 在< input “disabled” / > 使元素禁用
     *   form_tips: form中的label信息提示
     *   form_hide: form中自动化输出中剔除
     *   form_default: form中的默认值，请用字符类型，不要用数字
     *   select: form中的类型为 combobox时，定义其下来列表
     */
    public function attribute_ui() {
        /* text,textbox,numberbox,numberspinner, combobox,combotree,combogrid,datebox,datetimebox, timespinner,datetimespinner, textarea,checkbox,validatebox. */
        //type: numberbox数字框|combobox下拉框|text不写时默认|datebox
        $Eabase_util= EA_base::inst();
        $modules= config_item('admin_panels')? config_item('admin_panels'): array();

        /** 获取本管理员的酒店权限  */
        $hotels_hash= $this->get_hotels_hash();
        $publics = $hotels_hash['publics'];
        $hotels = $hotels_hash['hotels'];
        $filter = $hotels_hash['filter'];
        $filterH = $hotels_hash['filterH'];
        /** 获取本管理员的酒店权限  */

        return array(
            'deposit_card_pay_id' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'member_lvl_name' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),'name' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'nickname' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'membership_number' => array(
                'grid_ui'=> '',
                'grid_width'=> '7%',
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'pay_money' => array(
                'grid_ui'=> '',
                'grid_width'=> '8%',
                'form_hide'=> TRUE,
                'type'=>'price', //textarea|text|combobox|number|email|url|price
            ),
            'deposit' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'type'=>'price', //textarea|text|combobox|number|email|url|price
            ),
            'credit' => array(
                'grid_ui'=> '',
                'grid_width'=> '5%',
                'form_hide'=> TRUE,
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'card_count' => array(
                'grid_ui'=> '',
                'grid_width'=> '7%',
                'form_hide'=> TRUE,
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'distribution_num'=>array(
                'grid_ui'=> '',
                'grid_width'=> '7%',
                'form_hide'=> TRUE,
                'type'=>'text', //textarea|text|combobox|number|email|url|price
            ),
            'createtime' => array(
                'grid_ui'=> '',
                'grid_width'=> '15%',
                'form_hide'=> TRUE,
                'form_default'=> date('Y-m-d H:i:s'),
                'type'=>'datetime', //textarea|text|combobox|number|email|url|price
            ),
        );
    }

    /**
     * grid表格中默认哪个字段排序，排序方向
     */
    public static function default_sort_field() {
        return array('field'=>'deposit_card_pay_id', 'sort'=>'desc');
    }
    /* 以上为AdminLTE 后台UI输出配置函数 */
}