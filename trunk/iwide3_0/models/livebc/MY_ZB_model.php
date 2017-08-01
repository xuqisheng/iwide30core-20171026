<?php
include_once APPPATH."/models/livebc/MY_ZB_model.php";
class MY_ZB_model extends CI_Model {
    

    
    function __construct() {
        parent::__construct ();
    }
    
    
   
    
    protected function db_read(){
        
        $db_read = $this->load->database('iwide_r1',true);
        return $db_read;
        
    }
    
    protected function db_write(){
        
        return $this->db;
    }
    
    protected function db_soma_read(){
        
        $db_soma_read = $this->load->database('iwide_soma_r',true);
        return $db_soma_read;
        
        
    }
    
    
}
