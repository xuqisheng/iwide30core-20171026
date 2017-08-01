<?php
class Bonus_view_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	const DEFAULT_SKIN='default2';
    const TAB_HC = 'hotel_config';


    function update_hotel_config($inter_id,$fun,$data){

        $this->db->where(array(
            'inter_id' => $inter_id,
            'module'=>'HOTEL',
            'param_name'=>$fun,
            'hotel_id'=>0
            )
        );

        return $this->db->update(self :: TAB_HC,$data);


    }


    function new_bonus_view_setting($inter_id,$update,$fun='BONUS_VIEW_SETTING'){

        $data = array(
                'inter_id' => $inter_id,
                'module'=>'HOTEL',
                'param_name'=>$fun,
                'hotel_id'=>0,
                'param_value'=>$update['param_value']
            );

        return $this->db->insert(self :: TAB_HC,$data);


    }

}