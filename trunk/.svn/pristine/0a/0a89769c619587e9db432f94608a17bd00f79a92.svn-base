<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 后台微信会员卡配置
 * @author vvanjack
 * @modify by Jake cheung @ 2017/2/21
 * @time 2016/12/21
 * @version 1.0
 */

class Wxmember extends MY_Admin_Api {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('membervip/front/Wechat_membercard_model',"wechatMembercard");
        $this->inter_id = $this->session->get_admin_inter_id();
    }

    /**
     * 微信会员卡配置页面
     */
    public function setting(){
        $card_data = array();
        $inter_id = $this->session->get_admin_inter_id();
        $data = $this->wechatMembercard->get_config($inter_id);

        $this->load->model('wx/Publics_model');
        $inter_public= $this->Publics_model->get_public_by_id($this->session->get_admin_inter_id());


        if(!empty($data)){
            $card_info = $this->wechatMembercard->get_config_from_wechat($inter_id,$data['card_id']);
            if(!empty($card_info)){
                $card_data = $card_info['member_card'];
                $card_data['local_logo_url'] = $data['local_logo_url'];
                $card_data['local_background_url'] = $data['local_background_url'];
                $card_data['qrc'] = json_decode($this->wechatMembercard->syc_qrc($inter_id,$data['card_id']),true);
                $card_data['get_link'] = $inter_public['domain']."/index.php/membervip/wechatcard/?id=".$inter_id;
            }else{
                $card_data['error'] = true;
                $card_data['error_msg'] = '与微信服务器同步失败,请刷新或等候服务器连通后再尝试操作';
            }
        }


        $html = $this->_render_content($this->_load_view_file('setting'),$card_data,true);
        echo $html;

    }


    /**
     * 微信会员卡配置页面
     */
    public function index()
    {
        $inter_id = $this->session->get_admin_inter_id();
        /*测试写死有节目*/
        $post_data['inter_id'] = $inter_id;

        //请求登录配置信息URL
        $post_config_url = PMS_PATH_URL."adminwxmember/getcardconfig";
        $config = $this->doCurlPostRequest($post_config_url, $post_data);

        if ($config['err'] == 0 &&  isset($config['data'])) {
            $data =  $config['data'];
        }else{
            $data = array();
        }
        // 输出页面
       $this->_render_content($this->_load_view_file('conf'),$data,false);

    }

    /**
     * 保存配置页面数据
     */
    public function saveconfig()
    {
        $data = $this->input->post();
        $inter_id = $this->session->get_admin_inter_id();

        if(isset($data['fileselect'])) unset($data['fileselect']) ;



        $this->load->model('wx/Publics_model');
        $inter_public= $this->Publics_model->get_public_by_id($this->session->get_admin_inter_id());

        /*默认初始值*/
        if(empty($data['card_id'])) unset($data['card_id']);
        $data['supply_balance'] = false;
        $data['supply_bonus'] = false;
        $activate_type = "";

        if(isset($data['activate_type']) && !empty($data['activate_type'])){
            $activate_type = $data['activate_type'];
            unset($data['activate_type']);
        }

        $data['base_info']['color'] = 'Color010'; //卡背景颜色
        $data['base_info']['code_type'] = 'CODE_TYPE_QRCODE';
        $data['base_info']['get_limit'] = 1;                  //个人领取次数限制
        $data['base_info']['can_share'] = false;             //可以分享
        $data['base_info']['can_give_friend'] = false;      //可以转赠
        $data['base_info']['need_push_on_view'] = true;     //进入会员卡推送
        $data['base_info']['use_custom_code'] = false;      //自定义code
        /*默认初始值*/

        if(isset($data['custom_field_check'])){
            $count = 0;
            foreach($data['custom_field_check'] as $check_value){

                switch($check_value){
                    case "supply_bonus":
                        $data['supply_bonus'] = true;
                        $data['balance_url'] = (isset( $data['balance_url']) && !empty($data['balance_url']) ) ? $data['balance_url'] : $inter_public['domain']."/index.php/membervip/balance/?id=".$inter_id;
                        $count ++ ;
                        break;
                    case "supply_balance":
                        $data['supply_balance'] = true;
                        $data['bonus_url'] = (isset( $data['bonus_url']) && !empty($data['bonus_url']) ) ? $data['bonus_url'] : $inter_public['domain']."/index.php/membervip/bonus/?id=".$inter_id;
                        $count ++ ;
                        break;
                    default:
                        $temp[] = $check_value;
                        $count ++ ;
                }
            }
            if($count > 3){
                echo json_encode(array('err'=> 40003,'msg'=>'自定义栏目过多，最多只能选3项'));exit;
            }

        //快捷栏处理
        $i = 1;
        if(isset($temp) && !empty($temp)){
            while($i <= 3){
                if(!in_array('custom_field'.$i,$temp)){
                    unset($data['custom_field'.$i]);
                }
                $i++;
            }
        }else{
            while($i <= 3){
                unset($data['custom_field'.$i]);
                $i++;
            }
        }
        unset($data['custom_field_check']);
        /*判定快捷栏是否过多*/

        //自定义栏目

        }else{ //一项都没选
            $i = 1;
            while($i <= 3){
                unset($data['custom_field'.$i]);
                $i++;
            }

        }

        $i = 1;
        while(isset($data['custom_cell'.$i])){
            $custom_cell = $data['custom_cell'.$i];
            if(empty($custom_cell['name']) || empty($custom_cell['url'])){
//                unset($data['custom_cell'.$i]);
            }
            $i++;
        }


        //更新
        if(isset($data['card_id']) && !empty($data['card_id'])){

            $dbData = $this->wechatMembercard->get_config($inter_id,$data['card_id']); //原数据
            if(empty($dbData)) echo json_encode(array('err'=>40003,'msg'=>'更新出错，请刷新重试'));



            $local_logo =$data['base_info']['logo_url'];
            $local_background = $data['background_pic_url'];
            if(empty($dbData['local_logo_url']) || $dbData['local_logo_url'] != $local_logo ){
                $logo_res = $this->wechatMembercard->upload_img($inter_id,$data['base_info']['logo_url']);
                if(!$logo_res['error']){
                    $data['base_info']['logo_url'] = $logo_res['url'];
                    $wechat_config_data['local_logo_url'] = $local_logo;
                }else{
                    die($logo_res['errmsg']);
                }
            }else{
                unset(  $data['base_info']['logo_url']);
            }

            if(empty($dbData['local_background_url']) || $dbData['local_background_url'] != $local_background ){
            $background_res = $this->wechatMembercard->upload_img($inter_id,$data['background_pic_url']);
                if(!$background_res['error']){
                    $data['background_pic_url'] = $background_res['url'];
                    $wechat_config_data['local_background_url'] = $local_logo;
                }else{
                    die($background_res['errmsg']);
                }
            }else{
                unset( $data['background_pic_url'] );
            }
            $update_res = $this->wechatMembercard->update_card($inter_id,$data);
            echo $update_res;

            if(isset($wechat_config_data) && !empty($wechat_config_data)){
                $this->wechatMembercard->update_config($inter_id,$data['card_id'],$wechat_config_data);
            }
            exit;
        }


        switch($activate_type){
            case "wx_activate":
                $data['wx_activate']   = true;
                //开卡设定

                $requireData['required_form'] = array(
                    "can_modify" => false,  //是否可以修改
                    "common_field_id_list" => array(
                        "USER_FORM_INFO_FLAG_MOBILE", //电话-必填
                        "USER_FORM_INFO_FLAG_NAME", //姓名-必填
                    ),
                );

               $common_field_id_list_array = array(
                   'telephone'=>  'USER_FORM_INFO_FLAG_MOBILE',            	//手机号
                   'sex'=>  'USER_FORM_INFO_FLAG_SEX',	               //性别
                   'name'=>  'USER_FORM_INFO_FLAG_NAME',            	//姓名
                   'birthday'=>  'USER_FORM_INFO_FLAG_BIRTHDAY',            	//生日
                   'idNo'=>  'USER_FORM_INFO_FLAG_IDCARD',            	//身份证
                   'email'=>  'USER_FORM_INFO_FLAG_EMAIL',            	//邮箱
                   'address'=>  'USER_FORM_INFO_FLAG_LOCATION',            	//详细地址
                   'education'=>  'USER_FORM_INFO_FLAG_EDUCATION_BACKGRO',        	//教育背景
                   'industry'=>  'USER_FORM_INFO_FLAG_INDUSTRY',            	//行业
                   'income'=>  'USER_FORM_INFO_FLAG_INCOME',            	//收入
                   'habit'=>  'USER_FORM_INFO_FLAG_HABIT',            	//兴趣爱好
               );
                if(isset($data['activate_select']) && !empty($data['activate_select'])){
                    foreach($data['activate_select'] as $value){
                        if(isset($common_field_id_list_array[$value])){
                            $requireData['required_form']['common_field_id_list'][] = $common_field_id_list_array[$value];
                        }
                    }
                }

                break;
            case "auto_activate":
                $data['auto_activate']   = true;
                break;
            default:
                $data['auto_activate'] = false;//设置为true时用户领取会员卡后系统自动将其激活，无需调用激活接口，详情见自动激活。            
                $data['wx_activate']   = false;//设置为true时会员卡支持一键开卡，不允许同时传入activate_url字段，否则设置wx_activate失效。填入该字段后仍需调用接口设置开卡项方可生效，详情见一键开卡。
                $data['activate_url'] =  $inter_public['domain']."/index.php/membervip/wechatcard/verify_user_login?id=".$inter_id;  //激活会员卡链接
                break;
        }


        $logo_res = $this->wechatMembercard->upload_img($inter_id,$data['base_info']['logo_url']);
        if(!$logo_res['error']){
            $data['base_info']['logo_url'] = $logo_res['url'];
        }else{
            die($logo_res['errmsg']);
        }

        $background_res = $this->wechatMembercard->upload_img($inter_id,$data['background_pic_url']);
        if(!$background_res['error']){
            $data['background_pic_url'] = $background_res['url'];
        }else{
            die($background_res['errmsg']);
        }


        //新增
        if(isset($data['activate_select'])) unset($data['activate_select']);
        $result = $this->wechatMembercard->create_wechat_membercard($inter_id,$data);
        $result = json_decode($result,true);
        if(isset($result['errcode']) && $result['errcode']== 0 && isset($result['card_id'])){   //保存成功
            $this->wechatMembercard->save_config($inter_id,$result['card_id'],json_encode($data));

            //同步一次配置文件到本地
            $card_config =  $this->wechatMembercard->get_config_from_wechat($inter_id,$result['card_id']);
            $qrc = $this->wechatMembercard->syc_qrc($inter_id,$result['card_id']);
            if(!empty($card_config)){
                $wechat_config_data = array(
                  'content'  => json_encode($card_config),
                   'qrc_info'   => $qrc,
                    'local_logo_url' => $data['base_info']['logo_url'],
                    'local_background_url' => $data['background_pic_url']
                );
                $this->wechatMembercard->update_config($inter_id,$result['card_id'],$wechat_config_data);
            }

            if( $data['wx_activate']){
                $requireData['card_id'] = $result['card_id'];
                $this->wechatMembercard->wx_activate_card_set($inter_id,$requireData);
            }


        }

        echo  json_encode($result);

        exit;
    }

    /**
     * 保存配置页面数据
     */
    public function modify_quantity(){

        $data = $_POST;
        if(empty($data) || !isset($data['type']) || !isset($data['modifyNumber']) || !isset($data['card_id']) || $data['modifyNumber'] <1){
            echo json_encode(array('err'=>40003,'msg'=>'参数不正确，请确认填写无误'));
            exit;
        }

        if($data['type'] == 1){
            $increase =  $data['modifyNumber'];
            $reduce = 0;
        }else if($data['type'] == 2){
            $increase = 0;
            $reduce =  $data['modifyNumber'];
        }else{
            echo json_encode(array('err'=>40003,'msg'=>'参数不正确，请确认填写无误'));
            exit;
        }
        $inter_id = $this->inter_id;
        $card_id = $data['card_id'];
        $res = $this->wechatMembercard->modify_stock($inter_id,$card_id,$increase,$reduce);

        echo $res;exit;
    }
}