<?php
// error_reporting ( 0 );
//if (! defined ( 'BASEPATH' ))
//    exit ( 'No direct script access allowed' );

/**
 * Class ClubApi
 *
 * @property Club_model $Club_model
 */
class ClubApi extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }


    public function getSomaClub()
    {

        $inter_id = $this->input->get('inter_id', true);
        $openid = $this->input->get('openid', true);

        $this->load->model('club/Club_model');

        $res = $this->Club_model->somaClub($inter_id, $openid);

        $this->output->set_content_type('Content-Type: application/json');
        $this->output->set_output($res);
    }


}

?>