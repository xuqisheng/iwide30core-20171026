<?php
class User_model extends CI_Model {
    function __construct() {
        parent::__construct ();
    }
    const TAB_FANS_EXT = 'iwide_fans_ext';
    const TAB_FANS = 'iwide_fans';
    const TAB_ZB_FANS_EXT = 'iwide_zb_fans_ext';
    function get_fans_info($iwideid) {
        if (! $iwideid)
            return array ();
        $db = $this->load->database ( 'iwide_r1', true );
        $db->select ( 'fx.iwideid,f.*' );
        $db->from ( self::TAB_FANS_EXT . ' fx' );
        $db->join ( self::TAB_FANS . ' f', 'fx.openid=f.openid' );
        is_array ( $iwideid ) ? $db->where_in ( 'fx.iwideid', $iwideid ) : $db->where ( 'fx.iwideid', $iwideid );
        return $db->get ()->result_array ();
    }
    function get_zb_fans_ext($iwideid) {
        $db = $this->load->database ( 'iwide_r1', true );
        if (is_array ( $iwideid )) {
            $db->where_in ( 'iwideid', $iwideid );
            return $db->get ( self::TAB_ZB_FANS_EXT )->result_array ();
        } else {
            $db->limit ( 1 );
            $db->where ( 'iwideid', $iwideid );
            return $db->get ( self::TAB_ZB_FANS_EXT )->row_array ();
        }
    }
    function change_fans_mibi($iwideid, $change_amount) {
        $change_amount = intval ( $change_amount );
        if ($change_amount) {
            $sql = 'update ' . self::TAB_ZB_FANS_EXT . ' set mibi=mibi';
            $sql .= $change_amount > 0 ? ' + ' . $change_amount : ' - ' . abs($change_amount);
            $sql .= ' where iwideid="' . $iwideid . '"';
            $this->db->query ( $sql );
            return $this->db->affected_rows () > 0;
        }
        return FALSE;
    }
}
