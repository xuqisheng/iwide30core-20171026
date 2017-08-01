<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Rooms extends MY_Admin_Iapi {
	protected $label_module = NAV_HOTEL;
	protected $label_controller = '房型配置';
	protected $label_action = '';
	function __construct() {
		parent::__construct ();
		$this->inter_id = $this->session->get_admin_inter_id ();
		$this->module = 'hotel';
	}
	protected function main_model_name() {
		return 'hotel/rooms_model';
	}
	
	public function get_rooms()
	{
		$condit ['page'] = $this->input->get ( 'page' )>0 ? intval($this->input->get ( 'page' )) : 1;
		$condit ['size'] = $this->input->get ( 'size' )>0 ? intval($this->input->get ( 'size' )) : 10;
		$condit ['keyword'] = $this->input->get ( 'keyword' ) ? $this->input->get ( 'keyword' ) : '';
		$condit ['offset'] = $condit ['size']*($condit ['page']-1);

		$entity_id = $this->session->get_admin_hotels ();
		$entity_id_arr = explode(',',$entity_id);

		//获取酒店列表
		$return = array();
		$ext = array();
		$this->load->model ( 'hotel/Hotel_model' );
		$room_hotel_ids = $this->Hotel_model->get_room_hotels($this->inter_id);

		$hotel_ids = array();
		foreach ($room_hotel_ids as $room_hotel_id) {
			if(empty($entity_id) || in_array($room_hotel_id['hotel_id'],$entity_id_arr)){
				$hotel_ids[] = $room_hotel_id['hotel_id'];
			}
		}
		$hotels_new = array();
		if( !empty($hotel_ids) ){
			$entity_id = implode(',',$hotel_ids);
			$hotels = $this->Hotel_model->get_hotel_by_ids( $this->inter_id, $entity_id ,null ,'' ,'array',$condit);
			$condit ['is_count'] = true;
			$ext['count'] = $this->Hotel_model->get_hotel_by_ids( $this->inter_id, $entity_id ,null ,'' ,'array',$condit);
			if( $hotels ){
				foreach( $hotels as $v ){
					//删除一些不需要的字段
					$data = array();
					$data['hotel_id'] = $v['hotel_id'];
					$data['inter_id'] = $v['inter_id'];
					$data['name'] = $v['name'];
					$data['address'] = $v['province'].$v['city'].$v['address'];
					$data['latitude'] = $v['latitude'];
					$data['longitude'] = $v['longitude'];
					$data['tel'] = $v['tel'];
					$hotels_new[$v['hotel_id']] = $data;
				}
			}
			
			//获取所有酒店的房型
			$this->load->model ( 'hotel/Rooms_model' );
			$rooms = $this->Rooms_model->get_hotels_rooms( $this->inter_id, array_keys( $hotels_new ), 'name,room_id,hotel_id' );
			if( $hotels_new ){
				foreach( $rooms as $k=>$v ){
					$hotels_new[$k]['room_ids'] = $v; 
				}
			}
		}
		$return['items'] = $hotels_new;
		$ext ['page'] = $condit ['page'];
		$ext ['size'] = $condit ['size'];
		$this->out_put_msg(1,'',$return,'hotel/rooms/get_rooms',200,$ext);
	}

	public function get_rooms_by_code(){
		$pcode = intval( $this->input->get ( 'pcode' ) );
		if($pcode < 1)
			$this->out_put_msg(3,'缺少参数');
		$entity_id = $this->session->get_admin_hotels ();
		//获取有设置对应价格代码的酒店ids
		$this->load->model ( 'hotel/Hotel_model' );
		$pcode_hotel_ids = $this->Hotel_model->get_pcode_hotels($this->inter_id,$entity_id,$pcode);

		$hotel_ids = array_map(function($key) { return $key['hotel_id']; }, $pcode_hotel_ids);
		//获取选中的房型
		$this->load->model ( 'hotel/Rooms_model' );
		$pcode_room_ids = $this->Rooms_model->get_pcode_roomids($this->inter_id,implode(',',$hotel_ids),$pcode);
		$room_ids = array_map(function($key) { return $key['room_id']; }, $pcode_room_ids);

		$rooms = $this->Rooms_model->get_hotels_rooms( $this->inter_id, $hotel_ids, 'name,room_id,hotel_id' ,true ,null,$room_ids );

		$hotels = $this->Hotel_model->get_hotel_by_ids( $this->inter_id, implode(',',$hotel_ids),null,'key');

		if( $hotels ){
			foreach( $rooms as $k=>$v ){
				$hotels[$k]['room_ids'] = $v; 
			}
			$return['items'] = $hotels;
		}else{
			$return['items'] = null;
		}

		$this->out_put_msg(1,'',$return,'hotel/rooms/get_rooms_by_code');

	}
}
