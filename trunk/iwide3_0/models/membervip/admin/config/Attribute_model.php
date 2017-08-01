<?php

/**
 * 会员4.0后台模板字段配置
 * Created by knight.
 * User: ibuki
 * Date: 16/7/30
 * Time: 下午9:25
 */
class Attribute_model
{

    /**
     * 获取默认排序字段在grid罗列字段中的索引序号（grid模板datatable.js中使用）
     *
     * @param unknown $field
     * @return Ambigous <number, unknown>
     */
    public function field_index_in_grid($field = '', $flag = 1)
    {
        $index = 0;

        $fields = $this->grid_fields($flag);
        foreach ($fields as $k => $v) {
            if ($v == $field)
                $index = $k;
        }
        return $index;
    }

    /**
     * 后台管理的表格中要显示哪些字段
     */
    protected function grid_fields($type = 1)
    {
        // 主键字段一定要放在第一位置，否则 grid位置会发生偏移
        $show = array();
        switch ($type) {
            case 1:
                $show = array(
                    'member_info_id',
                    'nickname',
                    'member_mode',
                    'name',
                    'membership_number',
                    'lvl_name',
                    'credit',
                    'balance',
                    'mcount',
//                    'is_active',
                    'is_login',
                    'createtime',
                    'operation'
                );
                break;
            case 2:
                $show = array(
                    'package_id',
                    'name',
                    'remark',
                    'credit',
                    'balance',
                    'lvl_name',
                    'is_active',
                    'createtime',
                    'operation'
                );
                break;
            case 3:
                $show = array(
                    'card_id',
                    'title',
                    'description',
                    'card_type',
                    'card_stock',
                    'createtime',
                    'is_active',
                    'operation'
                );
                break;
            case 4:
                $show = array(
                    'coupon_code',
                    'member_info_id',
                    'membership_number',
                    'nickname',
                    'name',
//                    'title',
//                    'receive_module',
                    'receive_time',
                    'expire_time',
                    'is_expire',
                    'card_state',
//                    'use_module',
                    'use_time',
//                    'useoff_module',
                    'useoff_time',
//                    'card_module',
                    'remark'
                );
                break;
            case 5:
                $show = array(
                    'ir_membership_number',
                    'ir_name',
                    'createtime',
                    'invited_time',
                    'membership_number',
                    'name',
                    'lvl_name'
                );
                break;
            case 6:
                $show = array(
                    'member_info_id',
                    'nickname',
                    'name',
                    'sex',
                    'membership_number',
                    'telephone',
                    'lvl_name',
                    'is_active',
                    'is_login',
                    'member_type',
                    'company_name',
                    'employee_id',
                    'subtime',
                    'audit'
                );
                break;
            case 7:
                $show = array(
                    'log_title',
                    'log_type',
                    'content',
                    'createtime',
                    'admin_id',
                    'result'
                );
                break;
            case 8:
                $show = array(
                    'check',
                    'membership_number',
                    'member_lvl_id',
                    'name',
                    'telephone',
                    'company_name',
                    'duty',
                    'subtime',
                    'type',
                    'audit',
                    'remark',
                    'unpass_reason',
                    'id',
                );
                break;
            case 9:
                $show = array(
                    'card_rule_id',
                    'rule_title',
                    'active',
                    'frequency',
                    'common_name',
//                    'common_notice',
                    'common_type',
                    'common_stock',
                    'createtime',
                    'is_active',
                    'operation',
                );
                break;
            case 10:
                $show = [
                    'credit_log_id',
                    'nickname',
                    'name',
                    'membership_number',
                    'amount',
                    'note',
                    'createtime'
                ];
                break;
            case 11:
                $show = [
                    'deposit_card_pay_id',
                    'order_num',
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
                ];
                break;
            case 12:
                $show = [
                    'balance_log_id',
                    'order_id',
                    'name',
                    'nickname',
                    'membership_number',
                    'amount',
                    'note',
                    'createtime'
                ];
                break;
            case 13:
                $show = [
                    'coupon_code',
                    'use_time',
                    'card_id',
                    'title',
                    'membership_number',
                    'name',
                    'telephone',
                    'status',
                    'operation'
                ];
                break;
            case 14:
                $show = [
                    'id',
                    'nickname',
                    'authtime',
                    'operation'
                ];
                break;
            case 15:
                $show = [
                    'coupon_code',
                    'use_time',
                    'card_id',
                    'title',
                    'membership_number',
                    'name',
                    'telephone',
                    'status',
                    'remark'
                ];
                break;
        }
        return $show;
    }

    /**
     * 后台模版表格表头字典
     *
     * @return array
     */
    public function attribute_labels($type = 1)
    {
        $attribute = array();
        switch ($type) {
            case 1:
                $attribute = array(
                    'member_info_id' => '会员ID',
                    'nickname' => '会员昵称',
                    'member_mode' => '会员类型',
                    'name' => '会员名称',
                    'membership_number' => '会员卡号',
                    'lvl_name' => '会员等级',
                    'credit' => '会员积分',
                    'balance' => '储值余额',
                    'mcount' => '有效卡券总数',
//                    'is_active' => '是否冻结',
                    'is_login' => '是否登录',
                    'createtime' => '注册时间',
                    'operation' => '操作'
                );
                break;
            case 2:
                $attribute = array(
                    'package_id' => '套餐ID',
                    'name' => '套餐名称',
                    'remark' => '套餐说明',
                    'credit' => '赠送积分',
                    'balance' => '赠送储值',
                    'lvl_name' => '赠送等级',
                    'is_active' => '状态',
                    'createtime' => '创建时间',
                    'operation' => '操作'
                );
                break;
            case 3:
                $attribute = array(
                    'card_id' => 'ID',
                    'title' => '名称',
                    'description' => '使用说明',
                    'card_type' => '类型',
                    'card_stock' => '库存',
                    'createtime' => '创建时间',
                    'is_active' => '状态',
                    'operation' => '操作'
                );
                break;
            case 4:
                $attribute = array(
                    'coupon_code'=>'券码',
                    'member_info_id' => '会员ID',
                    'membership_number' => '会员卡号',
                    'nickname' => '会员昵称',
                    'name' => '会员名称',
//                    'title' => '卡券名称',
//                    'receive_module' => '领取来源',
                    'receive_time' => '领取时间',
                    'expire_time' => '失效时间',
                    'is_expire' => '是否过期',
                    'card_state' => '优惠券状态',
//                    'use_module' => '使用场景',
                    'use_time' => '使用时间',
//                    'useoff_module' => '核销场景',
                    'useoff_time' => '核销时间',
//                    'card_module' => '使用范围',
                    'remark' => '使用/核销备注'
                );
                break;
            case 5:
                $attribute = array(
                    'ir_membership_number' => '会员卡号',
                    'ir_name' => '会员名称',
                    'createtime' => '领取时间',
                    'invited_time' => '邀请时间',
                    'membership_number' => '邀请会员卡号',
                    'name' => '邀请会员名称',
                    'lvl_name' => '邀请资格'
                );
                break;
            case 6:
                $attribute = array(
                    'member_info_id' => '会员ID',
                    'nickname' => '昵称',
                    'name' => '姓名',
                    'sex' => '性别',
                    'membership_number' => '会员卡号',
                    'telephone' => '手机号',
                    'lvl_name' => '会员等级',
                    'is_active' => '状态',
                    'is_login' => '是否登录',
                    'member_type' => '业主/员工',
                    'company_name' => '公司名称',
                    'employee_id' => '员工号',
                    'subtime' => '提交时间',
                    'audit' => '审核状态'
                );
                break;
            case 7:
                $attribute = array(
                    'log_title' => '标题',
                    'log_type' => '类型',
                    'content' => '内容',
                    'createtime' => '操作时间',
                    'admin_id' => '管理员',
                    'result' => '操作状态'
                );
                break;
            case 8:
                $attribute = array(
                    'check' => '选择',
                    'membership_number'=>'会员卡号',
                    'member_lvl_id'=>'会员等级',
                    'name'=>'会员名称',
                    'telephone'=>'手机号码',
                    'company_name'=>'公司名称',
                    'duty'=>'职务',
                    'subtime'=>'提交时间',
                    'type'=>'业务类型',
                    'audit'=>'审核状态',
                    'remark'=>'备注',
                    'unpass_reason'=>'审核不通过原因',
                    'id'=>'会员ID',
                );
                break;
            case 9:
                $attribute = array(
                    'card_rule_id' => '规则ID',
                    'rule_title' => '规则名称',
                    'active' => '领取渠道',
                    'frequency' => '领取次数',
                    'common_name' => '套餐名称',
//                    'common_notice' => '套餐说明',
                    'common_type' => '类型',
                    'common_stock' => '库存',
                    'createtime' => '创建时间',
                    'is_active' => '状态',
                    'operation' => '操作'
                );
                break;
            case 10:
                $attribute = array(
                    'credit_log_id' => 'ID',
                    'nickname' => '昵称',
                    'name' => '姓名',
                    'membership_number' => '卡号',
                    'amount' => '积分变更',
                    'note' => '来源',
                    'createtime' => '记录时间',
                );
                break;
            case 11:
                $attribute = [
                    'deposit_card_pay_id'=> 'ID',
                    'order_num' => '订单号',
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
                ];
                break;
            case 12:
                $attribute = array(
                    'balance_log_id' => 'ID',
                    'order_id' => '订单号',
                    'nickname' => '昵称',
                    'name' => '姓名',
                    'membership_number' => '卡号',
                    'amount' => '储值变更',
                    'note' => '来源',
                    'createtime' => '记录时间',
                );
                break;
            case 13:
                $attribute = array(
                    'coupon_code' => '券码',
                    'use_time' => '使用时间',
                    'card_id' => '优惠券ID',
                    'title' => '券名',
                    'membership_number' => '会员号',
                    'name' => '会员名称',
                    'telephone' => '手机号码',
                    'status' => '状态',
                    'operation' => '操作'
                );
                break;
            case 14:
                $attribute = array(
                    'id' => 'ID',
                    'nickname' => '核销员',
                    'authtime' => '授权时间',
                    'operation' => '操作'
                );
                break;
            case 15:
                $attribute = array(
                    'coupon_code' => '券码',
                    'use_time' => '使用时间',
                    'card_id' => '优惠券ID',
                    'title' => '券名',
                    'membership_number' => '会员号',
                    'name' => '会员名称',
                    'telephone' => '手机号码',
                    'status' => '状态',
                    'remark' => '备注',
                );
                break;
        }
        return $attribute;
    }

    /**
     * 在EasyUI grid中的 date-option 定义，包括宽度，是否排序等等
     * type: grid中的表头类型定义
     * form_type: form中的元素类型定义
     * form_ui: form中的属性补充定义，如加disabled 在< input “disabled” / > 使元素禁用
     * form_tips: form中的label信息提示
     * form_hide: form中自动化输出中剔除
     * form_default: form中的默认值，请用字符类型，不要用数字
     * select: form中的类型为 combobox时，定义其下来列表
     */
    public function attribute_ui($type = 1)
    {
        $attribute_ui = array();
        switch ($type) {
            case 1:
                $attribute_ui = array(
                    'member_info_id' => array(
                        'grid_ui' => '',
                        'grid_width' => '7%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'nickname' => array(
                        'grid_ui' => '',
                        'grid_width' => '8%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'bSortable' => false
                    ),
                    'member_mode' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_get_member_mode'
                    ),
                    'name' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'membership_number' => array(
                        'grid_ui' => '',
                        'grid_width' => '7%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'lvl_name' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'credit' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'form_hide' => TRUE,
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'balance' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'form_hide' => TRUE,
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'mcount' => array(
                        'grid_ui' => '',
                        'grid_width' => '8%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'is_login' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_get_is_login'
                    ),
                    'createtime' => array(
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parsedatetime'
                    ),
                    'operation' => array(
                        'grid_ui' => '',
                        'grid_width' => '16%',
                        'form_hide' => TRUE,
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_get_operation'
                    )
                );
                break;
            case 2:
                $attribute_ui = array(
                    'package_id' => array(
                        'grid_ui' => '',
                        'grid_width' => '4%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'name' => array(
                        'grid_ui' => '',
                        'grid_width' => '8%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'bSortable' => false
                    ),
                    'remark' => array(
                        'grid_ui' => '',
                        'grid_width' => '15%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'credit' => array(
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'balance' => array(
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'lvl_name' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'is_active' => array(
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'form_hide' => TRUE,
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parse_is_active'
                    ),
                    'createtime' => array(
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parsedatetime'
                    ),
                    'operation' => array(
                        'grid_ui' => '',
                        'grid_width' => '8%',
                        'form_hide' => TRUE,
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_get_operation'
                    )
                );
                break;
            case 3:
                $attribute_ui = array(
                    'card_id' => array(
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'title' => array(
                        'grid_ui' => '',
                        'grid_width' => '8%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'bSortable' => false
                    ),
                    'description' => array(
                        'grid_ui' => '',
                        'grid_width' => '15%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'card_type' => array(
                        'grid_ui' => '',
                        'grid_width' => '2%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parse_card_type'
                    ),
                    'card_stock' => array(
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'createtime' => array(
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parse_createtime'
                    ),
                    'is_active' => array(
                        'grid_ui' => '',
                        'grid_width' => '2%',
                        'form_hide' => TRUE,
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parse_is_active'
                    ),
                    'operation' => array(
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'form_hide' => TRUE,
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_get_operation'
                    )
                );
                break;
            case 4:
                $attribute_ui = array(
                    'coupon_code'=>array(
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'type' => 'text'
                    ),
                    'member_info_id' => array(
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'membership_number' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'nickname' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'bSortable' => false
                    ),
                    'name' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'receive_time' => array(
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parsedatetime'
                    ),
                    'expire_time' => array(
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'form_hide' => TRUE,
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parsedate'
                    ),
                    'is_expire' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'form_hide' => TRUE,
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parse_is_expire'
                    ),
                    'card_state' => array(
                        'grid_ui' => '',
                        'grid_width' => '7%',
                        'form_hide' => TRUE,
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parse_card_state'
                    ),
                    'use_time' => array(
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'form_hide' => TRUE,
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parsedatetime'
                    ),
                    'useoff_time' => array(
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'form_hide' => TRUE,
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parsedatetime'
                    ),
                    'remark' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'form_hide' => TRUE,
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price

                );
                break;
            case 5:
                $attribute_ui = array(
                    'ir_membership_number' => array(
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'ir_name' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'createtime' => array(
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parsedatetime'
                    ),
                    'invited_time' => array(
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parsedatetime'
                    ),
                    'membership_number' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'name' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'lvl_name' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price

                );
                break;
            case 6:
                $attribute_ui = array(
                    'member_info_id' => array(
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'nickname' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'name' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'sex' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parse_sex'
                    ),
                    'membership_number' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'telephone' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'lvl_name' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'is_active' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parse_is_active'
                    ),
                    'is_login' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_get_is_login'
                    ),
                    'member_type' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parse_member_type'
                    ),
                    'company_name' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'employee_id' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'subtime' => array(
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parsedatetime'
                    ),
                    'audit' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parse_audit'
                    )
                );
                break;
            case 7:
                $attribute_ui = array(
                    'log_title' => array(
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'log_type' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parselog_type'
                    ),
                    'content' => array(
                        'grid_ui' => '',
                        'grid_width' => '16%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'createtime' => array(
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'admin_id' => array(
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'result' => array(
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price

                );
                break;
            case 8:
                $attribute_ui = array(
                    'check' => array(
                        'grid_ui' => '',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'membership_number' => array(
                        'grid_ui' => '',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'member_lvl_id' => array(
                        'grid_ui' => '',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'name' => array(
                        'grid_ui' => '',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'telephone' => array(
                        'grid_ui' => '',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'company_name' => array(
                        'grid_ui' => '',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'duty' => array(
                        'grid_ui' => '',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'subtime' => array(
                        'grid_ui' => '',
                        'grid_function' => '_parsedatetime'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'type' => array(
                        'grid_ui' => '',
                        'type' => 'text'
                    ) // textarea|text|combobox|number|email|url|price
                ,
                    'audit' => array(
                        'grid_ui' => '',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parse_audit'
                    ),
                    'remark' => array(
                        'grid_ui' => '',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => ''
                    ),
                    'unpass_reason' => array(
                        'grid_ui' => '',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                    ),
                    'id' => array(
                        'grid_ui' => '',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => ''
                    )
                );
                break;
            case 9:
                $attribute_ui = array(
                    'card_rule_id' => array(
                        'grid_ui' => '',
                        'grid_width' => '4%',
                        'type' => 'text'
                    ),
                    'rule_title' => array(
                        'grid_ui' => '',
                        'grid_width' => '6%',
                        'type' => 'text'
                    ),
                    'active' => array(
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'type' => 'text',
                        'grid_function' => '_parse_channel'
                    ),
                    'frequency' => array(
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'type' => 'text'
                    ),
                    'common_name' => array(
                        'grid_ui' => '',
                        'grid_width' => '9%',
                        'type' => 'text'
                    ),
                    'common_type' => array(
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'type' => 'text'
                    ),
                    'common_stock' => array(
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'type' => 'text'
                    ),
                    'createtime' => array(
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                    ),
                    'is_active' => array(
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parse_is_active'
                    ),
                    'operation' => array(
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'type' => 'text', // textarea|text|combobox|number|email|url|price
                        'grid_function' => '_get_operation'
                    )
                );
                break;
            case 10:
                $attribute_ui = [
                    'credit_log_id' => [
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'type' => 'text'
                    ],
                    'nickname' => [
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'type' => 'text'
                    ],
                    'name' => [
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'type' => 'text',
                    ],
                    'membership_number' => [
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'type' => 'text'
                    ],
                    'amount' => [
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'type' => 'text'
                    ],
                    'note' => [
                        'grid_ui' => '',
                        'grid_width' => '10%',
                        'type' => 'text'
                    ],
                    'createtime' => [
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'type' => 'text',
                        'grid_function' => '_parsedatetime'
                    ]
                ];
                break;
            case 11:
                $attribute_ui = array(
                    'deposit_card_pay_id' => array(
                        'grid_ui'=> '',
                        'grid_width'=> '5%',
                        'type'=>'text', //textarea|text|combobox|number|email|url|price
                    ),
                    'order_num' => array(
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
                        'grid_width'=> '5%',
                        'form_hide'=> TRUE,
                        'form_default'=> date('Y-m-d H:i:s'),
                        'type'=>'datetime', //textarea|text|combobox|number|email|url|price
                        'grid_function' => '_parsedatetime'
                    ),
                );
                break;
            case 12:
                $attribute_ui = [
                    'balance_log_id' => [
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'type' => 'text'
                    ],
                    'order_id' => [
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'type' => 'text'
                    ],
                    'nickname' => [
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'type' => 'text'
                    ],
                    'name' => [
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'type' => 'text',
                    ],
                    'membership_number' => [
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'type' => 'text'
                    ],
                    'amount' => [
                        'grid_ui' => '',
                        'grid_width' => '3%',
                        'type' => 'text'
                    ],
                    'note' => [
                        'grid_ui' => '',
                        'grid_width' => '10%',
                        'type' => 'text'
                    ],
                    'createtime' => [
                        'grid_ui' => '',
                        'grid_width' => '5%',
                        'type' => 'text',
                        'grid_function' => '_parsedatetime'
                    ]
                ];
                break;
            case 13:
                $attribute_ui = [
                    'coupon_code' => [
                        'grid_ui' => '',
                        'type' => 'text'
                    ],
                    'use_time' => [
                        'grid_ui' => '',
                        'type' => 'text',
                        'grid_function' => '_parsedatetime'
                    ],
                    'card_id' => [
                        'grid_ui' => '',
                        'type' => 'text'
                    ],
                    'title' => [
                        'grid_ui' => '',
                        'type' => 'text',
                    ],
                    'membership_number' => [
                        'grid_ui' => '',
                        'type' => 'text'
                    ],
                    'name' => [
                        'grid_ui' => '',
                        'type' => 'text'
                    ],
                    'telephone' => [
                        'grid_ui' => '',
                        'type' => 'text'
                    ],
                    'status' => [
                        'grid_ui' => '',
                        'type' => 'text',
                    ],
                    'operation' => [
                        'grid_ui' => '',
                        'type' => 'text',
                        'grid_function' => '_get_operation'
                    ]
                ];
                break;
            case 14:
                $attribute_ui = [
                    'id' => [
                        'grid_ui' => '',
                        'type' => 'text'
                    ],
                    'nickname' => [
                        'grid_ui' => '',
                        'type' => 'text',
                    ],
                    'authtime' => [
                        'grid_ui' => '',
                        'type' => 'text'
                    ],
                    'operation' => [
                        'grid_ui' => '',
                        'type' => 'text',
                        'grid_function' => '_get_operation'
                    ]
                ];
                break;
            case 15:
                $attribute_ui = [
                    'coupon_code' => [
                        'grid_ui' => '',
                        'type' => 'text'
                    ],
                    'use_time' => [
                        'grid_ui' => '',
                        'type' => 'text',
                        'grid_function' => '_parsedatetime'
                    ],
                    'card_id' => [
                        'grid_ui' => '',
                        'type' => 'text'
                    ],
                    'title' => [
                        'grid_ui' => '',
                        'type' => 'text',
                    ],
                    'membership_number' => [
                        'grid_ui' => '',
                        'type' => 'text',
                    ],
                    'name' => [
                        'grid_ui' => '',
                        'type' => 'text',
                    ],
                    'telephone' => [
                        'grid_ui' => '',
                        'type' => 'text',
                    ],
                    'status' => [
                        'grid_ui' => '',
                        'type' => 'text'
                    ],
                    'remark' => [
                        'grid_ui' => '',
                        'type' => 'text',
                    ]
                ];
                break;
        }
        return $attribute_ui;
    }

    /**
     * 统一生成字段配置数组，赋予模板
     * @param string $type
     * @param int $flag
     * @return array
     */
    public function get_field_config($type = 'grid', $flag = 1)
    {
        $data = array();
        $show = $this->grid_fields($flag);
        $fields = $this->attribute_labels($flag);
        $fields_ui = $this->attribute_ui($flag);
        foreach ($show as $v) {
            $data[$v]['label'] = $fields[$v];
            if ($type == 'grid') {
                // grid所需配置信息
                if (array_key_exists($v, $fields_ui)) {
                    $data[$v]['grid_ui'] = isset($fields_ui[$v]['grid_ui']) ? $fields_ui[$v]['grid_ui'] : '';
                    $data[$v]['grid_width'] = isset($fields_ui[$v]['grid_width']) ? $fields_ui[$v]['grid_width'] : "";
                    $data[$v]['grid_function'] = isset($fields_ui[$v]['grid_function']) ? $fields_ui[$v]['grid_function'] : FALSE;
                    $data[$v]['function'] = isset($fields_ui[$v]['function']) ? $fields_ui[$v]['function'] : FALSE;
                    $data[$v]['type'] = isset($fields_ui[$v]['type']) ? $fields_ui[$v]['type'] : 'text';
                    if ($data[$v]['type'] == 'combobox')
                        $data[$v]['select'] = $fields_ui[$v]['select'];
                }
            } else
                if ($type == 'form') {
                    // form所需配置信息
                    $data[$v]['js_config'] = isset($fields_ui[$v]['js_config']) ? $fields_ui[$v]['js_config'] : '';
                    $data[$v]['input_unit'] = isset($fields_ui[$v]['input_unit']) ? "<div class='input-group-addon'>{$fields_ui[$v]['input_unit']}</div>" : '';
                    $data[$v]['form_ui'] = isset($fields_ui[$v]['form_ui']) ? $fields_ui[$v]['form_ui'] : '';
                    $data[$v]['form_tips'] = ! empty($fields_ui[$v]['form_tips']) ? $fields_ui[$v]['form_tips'] : NULL;
                    $data[$v]['form_default'] = isset($fields_ui[$v]['form_default']) ? $fields_ui[$v]['form_default'] : NULL;
                    $data[$v]['form_hide'] = isset($fields_ui[$v]['form_hide']) ? $fields_ui[$v]['form_hide'] : FALSE;
                    $data[$v]['function'] = isset($fields_ui[$v]['function']) ? $fields_ui[$v]['function'] : FALSE;
                    $data[$v]['type'] = isset($fields_ui[$v]['type']) ? $fields_ui[$v]['type'] : 'text';
                    if ($data[$v]['type'] == 'combobox')
                        $data[$v]['select'] = $fields_ui[$v]['select'];
                    if (isset($fields_ui[$v]['form_type']))
                        $data[$v]['type'] = $fields_ui[$v]['form_type'];
                }
        }
        return $data;
    }

    // 导航栏图标
    public function get_uiicon()
    {
        return array(
            'icon1' => '我的身份',
            'icon2' => '酒店订单',
            'icon3' => '商城订单',
            'icon4' => '在线预订',
            'icon5' => '我的收藏',
            'icon6' => '我的地址',
            'icon7' => '全员营销',
            'icon8' => '社群客',
            'icon9' => '关于金房卡',
            'icon37' => '我的权益',
            'icon11' => '我的余额',
            'icon12' => '记录',
            'icon13' => '绑定',
            'icon14' => '充值',
            'icon15' => '购卡'
        );
    }

    // 等级图标
    public function get_lvl_uiicon()
    {
        return array(
            'icon_collect' => '1',
            'icon_crown' => '2',
            'icon_diamond' => '3',
            'icon_flower' => '4',
            'icon_gift' => '5',
            'icon_heart' => '6',
            'icon_like' => '7',
            'icon_octagon' => '8',
            'icon_person' => '9',
            'icon_rice' => '10',
            'icon_star' => '11',
            'icon_v' => '12'
        );
    }

    /**
     * 获取保存操作日志的字段映射
     * @param string $type 日志类型
     * @return array|bool|mixed
     */
    public function get_logs_keymap($type = ''){
        if(empty($type)) return false;
        $key_maping = array(
            'coupon'=>array(
                'card_type'=>'优惠券类型',
                'apply_inter'=>'多酒店集团优惠券列',
                'module'=>'渠道类型',
                'title'=>'优惠券名称',
                'sub_title'=>'优惠券副名',
                'card_stock'=>'优惠券库存',
                'logo_url'=>'logo',
                'notice'=>'使用提醒',
                'card_note'=>'优惠券说明',
                'description'=>'使用说明',
                'is_online'=>'运营范围',
                'can_give_friend'=>'优惠券转赠',
                'passwd'=>'消费密码',
                'page_config'=>'页面属性',
                'header_url'=>'通用链接',
                'hotel_header_url'=>'订房链接',
                'soma_header_url'=>'套票链接',
                'shop_header_url'=>'商城链接',
                'least_cost'=>'起用金额',
                'over_limit'=>'抵用上限',
                'reduce_cost'=>'抵减金额',
                'discount'=>'打折额度',
                'exchange'=>'兑换券说明',
                'money'=>'储值券金额',
                'remark'=>'备注',
                'time_start'=>'领取起始时间',
                'time_end'=>'领取结束时间',
                'use_time_start'=>'起用时间',
                'use_time_end_model'=>'失效模式',
                'use_time_end'=>'失效时间',
                'use_time_end_day'=>'使用失效天数',
                'is_active'=>'优惠券状态'
            ),
            'package'=>array(
                'name'=>'礼包名称',
                'remark'=>'礼包描述',
                'credit'=>'赠送积分',
                'deposit'=>'赠送储值',
                'lvl_name'=>'赠送等级',
                'is_active'=>'是否激活',
                'card'=>'赠送卡券',
            ),
            'invite_viewconf'=>array(
                'home'=>'邀请好友主页面',
                'activity_rule'=>'活动规则页面',
                'invited_share'=>'分享推送',
                'reg_login'=>'注册和登陆页面',
                'upgrade_success'=>'升级成功页面',
                'custom'=>'自定义跳转',
                'inter_id'=>'酒店集团ID'
            ),
            'invite_settings'=>array(
                'is_open'=>'是否启动',
                'effective_time'=>'设置会员邀请好友权限的有效期',
                'is_keep'=>'升级保留邀请资格',
                'to_activate'=>'被邀请的新会员如何激活邀请权益',
                'activate_value'=>'该会员当年有入住间夜',
                'expiretime'=>'下一次重置时间',
                'is_active'=>'活动状态'
            ),
            'invite_level_equity'=>array(
                'act_id'=>'活动ID',
                'hold_lvl_group'=>'邀请权益配置',
                'inter_id'=>'酒店集团ID'
            ),
            'member_unbind'=>array(
                'is_login'=>'是否登录'
            )
        );
        $keymap = !empty($key_maping[$type])?$key_maping[$type]:array();
        return $keymap;
    }
}