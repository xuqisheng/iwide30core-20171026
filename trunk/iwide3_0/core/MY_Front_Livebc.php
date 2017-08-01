<?php
class MY_Front_Livebc extends MY_Front {
    public $iwideid = '';
    public $source;
    public function __construct() {
        include_once APPPATH."config/zb_config.php";
        $_GET ['scope'] = "snsapi_userinfo";
        $_GET ['id'] = ZB_INTER_ID;
        parent::__construct ();
        $this->iwideid = $this->session->userdata ( $this->inter_id . 'iwideid' );
        if (empty ( $this->iwideid )) {
            $this->load->model ( 'wx/Publics_model' );
            $fans_ext = $this->Publics_model->get_fans_ext ( $this->inter_id, $this->openid );
            $this->iwideid = empty ( $fans_ext ['iwideid'] ) ? $this->openid : $fans_ext ['iwideid'];
        }
        $this->source = json_decode ( file_get_contents ( 'php://input' ), TRUE );
    }
    protected function get_source($index = '', $filter = '', $in = TRUE) {
        if ($index === '')
            return $this->source;
        if ($in)
            $data = isset ( $this->source ['send_data'] [$index] ) ? $this->source ['send_data'] [$index] : NULL;
        else
            $data = isset ( $this->source [$index] ) ? $this->source [$index] : NULL;
        if (isset ( $data ) && ! empty ( $filter )) {
            switch ($filter) {
                case 'int' :
                    $data = intval ( $data );
                    break;
                default :
                    break;
            }
        }
        return $data;
    }
}
