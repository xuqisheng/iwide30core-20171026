<?php
// error_reporting ( 0 );
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Ctrip_interface extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->output->enable_profiler ( false );
		ini_set ( 'display_errors', 0 );
		if (version_compare ( PHP_VERSION, '5.3', '>=' )) {
			error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_USER_NOTICE & ~ E_USER_DEPRECATED );
		} else {
			error_reporting ( E_ALL & ~ E_NOTICE & ~ E_STRICT & ~ E_USER_NOTICE );
		}
	}


    function connent_db(){

        $conf = array(
            'dsn' => 'mysql:dbname=iwide30price;host=100.98.255.50',
            'user' => 'iwide30price',
            'password' => 'kk6593%jdfk87tH',
            'comment' => ''

        );

        return new PDO( $conf['dsn'], $conf['user'], $conf['password'] ,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8';" ));

    }


    function getinfo($sql){     //获取全部数据

        $res = array();

        $res['iwide30dev'] = $this->db->query($sql)->result_array();

        $dbh = $this->connent_db();
        $stat = $dbh->query($sql);

        $res['iwide30price'] = $stat->fetchAll( PDO::FETCH_ASSOC );

        return $res;

    }


    function check_new_hotels(){             //返回新的酒店


        $sql = "SELECT * FROM `iwide_hotels` WHERE 1";

        $hotels = $this->getinfo($sql);

        $new_hotels = array();
        $price_arr = array();
        $dev_arr = array();

        if(isset($hotels['iwide30price']) && isset($hotels['iwide30dev'])){

            foreach($hotels['iwide30price'] as $price){

                $price_arr[$price['inter_id'].'_'.$price['hotel_id']]=$price;

            }

            foreach ($hotels['iwide30dev'] as $dev){

                if(!isset($price_arr[$dev['inter_id'].'_'.$dev['hotel_id']])){

                    $new_hotels[]=$dev;

                }
            }


            echo json_encode($new_hotels);

        }

        echo false;


    }




    function check_new_publics(){    //返回新的公众号

        $sql = "SELECT * FROM `iwide_publics` WHERE status = 0";

        $publics = $this->getinfo($sql);

        $new_publics = array();
        $price_arr = array();
        $dev_arr = array();

        if(isset($publics['iwide30price']) && isset($publics['iwide30dev'])){

            foreach($publics['iwide30price'] as $price){

                $price_arr[$price['inter_id']]=$price;

            }

            foreach ($publics['iwide30dev'] as $dev){

                if(!isset($price_arr[$dev['inter_id']])){

                    $new_publics[]=$dev;

                }
            }

            echo json_encode($new_publics);

        }

        echo false;


    }




}