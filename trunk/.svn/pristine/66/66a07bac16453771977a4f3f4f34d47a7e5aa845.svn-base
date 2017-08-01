<?php
class Company_dispose_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	const TAB_H = 'hotels';
	const TAB_O = 'hotel_orders';
	const TAB_HOI = 'hotel_order_items';
	const TAB_HI = 'hotel_images';
	const TAB_HR = 'hotel_rooms';
	const TAB_HRN = 'hotel_room_numbers';
	const TAB_HLP = 'hotel_lowest_price';
	const TAB_HFM = 'hotel_front_marks';
	const TAB_HC = 'hotel_config';
	const TAB_HCT = 'hotel_comments';
	const TAB_FANS = 'fans';
    const TAB_C = 'company_list';


	public function get_hotel_hash($params = array(), $select = array(), $format = 'array', $table = NULL) {
		return $this->get_data_hash ( $params, $select, $format, self::TAB_H );
	}
	public function get_hotel_room_hash($params = array(), $select = array(), $format = 'array', $table = NULL) {
		return $this->get_data_hash ( $params, $select, $format, self::TAB_HR );
	}
	public function get_hotel_order_hash($params = array(), $select = array(), $format = 'array', $table = NULL) {
		return $this->get_data_hash ( $params, $select, $format, self::TAB_O );
	}


    public function get_company_hash($params = array(), $select = array(), $format = 'array', $table = NULL) {
        return $this->get_data_hash ( $params, $select, $format, self::TAB_C );
    }

	/**
	 *
	 * @author libinyan
	 */
	public function get_data_hash($params = array(), $select = array(), $format = 'array', $table = NULL) {
        $db_read = $this->load->database('iwide_r1',true);
		$select = count ( $select ) == 0 ? '*' : implode ( ',', $select );
        $db_read->select ( " {$select} " );

		$where = array ();
		$dbfields = array_values ( $fields = $db_read->list_fields ( $table ) );
		foreach ( $params as $k => $v ) {
			// 过滤非数据库字段，以免产生sql报错
			if (in_array ( $k, $dbfields ) && is_array ( $v )) {
                $db_read->where_in ( $k, $v );
			} else if (in_array ( $k, $dbfields )) {
                $db_read->where ( $k, $v );
			}
		}
		$result = $db_read->get ( $table );
		if ($format == 'object')
			return $result->result ();
		else
			return $result->result_array ();
	}
	/**
	 *
	 * @author libinyan
	 */
	public function array_to_hash($array, $label_key, $value_key = NULL) {
		$data = array ();
		foreach ( $array as $k => $v ) {
			// 过滤额外增加的数据 如 key=0的不完整数据
			if (isset ( $v [$label_key] )) {
				if ($value_key == NULL) {
					$key = $k;
				} else {
					$key = $v [$value_key];
				}
				$data [$key] = $v [$label_key];
			}
		}
		return $data;
	}

    public  function get_hotel_list($inter_id){

        $db_read = $this->load->database('iwide_r1',true);

        $result = $db_read->query("SELECT * FROM `iwide_hotels` WHERE inter_id = '{$inter_id}'")->result_array();

        return $result;
    }

    public  function getCompanyById($id){   //获取公司名称

        $db_read = $this->load->database('iwide_r1',true);

        $result = $db_read->query("SELECT * FROM `iwide_company_list` WHERE company_id = '{$id}'")->result_array();

        return $result[0];

    }


    public  function getCompanyPrice($id){   //获取当前公司的协议价情况

        $db_read = $this->load->database('iwide_r1',true);

        $result = $db_read->query("SELECT * FROM `iwide_company_price` WHERE company_id = '{$id}'")->row();

        return $result;

    }

    public  function getAllCompanyPriceCode($inter_id){   //获取当前公众号所有价格代码

        $db_read = $this->load->database('iwide_r1',true);

        $result = $db_read->query("SELECT * FROM `iwide_hotel_price_info` WHERE inter_id = '{$inter_id}' AND type='protrol'")->result_array();

        return $result;

    }


    public  function getCompanyHotel($inter_id,$company_id){   //获取加入协议客的酒店

        $db_read = $this->load->database('iwide_r1',true);

        $result = $db_read->query("SELECT * FROM `iwide_company_price` WHERE inter_id = '{$inter_id}' AND company_id='{$company_id}' AND status=1")->result_array();

        return $result;

    }


    public function  newCompanyHotel($company_id,$cp_code,$price_code,$hotel_id,$inter_id,$valid_time){   //新增合作单位签订的新酒店

        $result=$this->db->query("INSERT INTO
                                        `iwide_company_price`(`company_id`,`cp_code`,`price_code`,`hotel_id`,`inter_id`,`status`,`valid_time`)
                                     VALUES
                                        ($company_id,'{$cp_code}',$price_code,$hotel_id,'{$inter_id}',1,'{$valid_time}')");

        return $result;

    }

    public function  updateCompanyHotelStatus($company_id,$hotel_id,$inter_id,$status,$price_code,$cp_code,$valid_time){    //改变协议客的酒店状态

        $result=$this->db->query("UPDATE
                    `iwide_company_price`
                SET
                    status=$status,cp_code='{$cp_code}',price_code=$price_code,valid_time='{$valid_time}'
                WHERE
                     inter_id='{$inter_id}'
                AND
                    company_id='{$company_id}'
                AND
                     hotel_id=$hotel_id
                "
        );

        return $result;

    }


    public function confirmHotel($inter_id,$company_id,$hotel_id){   //合作的酒店

        $db_read = $this->load->database('iwide_r1',true);

        $result = $db_read->query("SELECT * FROM `iwide_company_price` WHERE inter_id = '{$inter_id}' AND company_id='{$company_id}' AND hotel_id=$hotel_id")->row();

        return $result;

    }


    public function cancelHotel($inter_id,$company_id,$hotel_id){   //取消酒店

        $result=$this->db->query("UPDATE `iwide_company_price` SET status=2 WHERE inter_id='{$inter_id}' AND company_id='{$company_id}' AND hotel_id NOT IN ($hotel_id)");

        return $result;

    }


}