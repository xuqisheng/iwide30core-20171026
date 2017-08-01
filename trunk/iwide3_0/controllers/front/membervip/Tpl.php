<?php

class Tpl extends MY_Controller{
    public function pay(){
        $data['filed_name']['balance_name'] = '会员中心';
        $this->load->view('member/phase2/pay',$data);
    }
}