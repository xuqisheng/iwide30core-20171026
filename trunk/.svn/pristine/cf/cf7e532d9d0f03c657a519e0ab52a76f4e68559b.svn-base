<?php
/**
 * Created by PhpStorm.
 * User: vvanjack
 * Date: 2017/1/5
 * Time: 12:33
 */

class Wxmember_model extends MY_Model_Member {

    // 接受微信会员卡审核信息
    public function wx_card_check ($data, $id) {

        $post_data['data'] = $data;
        $post_data['inter_id'] = $id;
        $post_url = PMS_PATH_URL."receivewxpush/wxcardcheck";
        $this->load->helper('common');
        $res = doCurlPostRequest($post_url, http_build_query($post_data));
        return $res;
    }



}
