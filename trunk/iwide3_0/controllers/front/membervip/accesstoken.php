<?php

/**
 * Created by PhpStorm.
 * User: vvanjack
 * Date: 2016/12/23
 * Time: 14:59
 */
class accesstoken extends CI_Controller {

    // 获取access_token
    public function getaccesstoken () {
        // $inter_id
        $data = $this->input->get();
        $inter_id = array_key_exists('inter_id', $data) ? $data['inter_id'] : '';
        $token = array_key_exists('token',$data) ? $data['token'] : '';
        if ($token != 'ehXHgxjUMl0cQv6EYRhhFBNddnNV75Rrvui8552CnE3uqtHwlXG9Q3orxyWTkBma') {
            echo '{"errcode":402, "msg":"临时token不正确","data": "" }';exit;
        }
        if (!$inter_id) {
            echo '{"errcode":403, "msg":"inter_id不正确","data": "" }';exit;
        }
        // 获取access_token
        $this->load->model('wx/access_token_model');
        $access_token = $this->access_token_model->get_access_token($inter_id);
        if ($access_token) {
            echo '{"errcode":0, "msg":"成功","data": {"access_token": "'. $access_token .'"} }';exit;
            json_decode('{"errcode":0, "msg":"成功","data": {"access_token": "'. $access_token .'"} }');

        }

    }
}