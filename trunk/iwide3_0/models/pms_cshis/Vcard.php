<?php
class Vcard extends CI_Model
{
	public $email='';//邮件地址
	public $code='999';//代号
	public $code_name="mofly";
	public $pay_method="4-微支付";
	
	public static $products = array(
		'1000'=>'0',
		'3000'=>'300',
		'5000'=>'600',
		'10000'=>'1500',
		'20000'=>'3500',
		'30000'=>'6000',
	);
	
	
	public function getString($order, $info, $transaction_id)
	{
		$this->load->model('bgyhotel/hotel');
		
		$string = '';
	    if(ENVIRONMENT=='production') {
			$string .= "1&".$order->note.",";
		    $string .= $this->hotel->getHotelName($order->note).",";
		} else {
			$string .= "1&099,";
		    $string .= "酒店管理公司,";
		}
		$string .= $info->name.",";
		$string .= $info->telephone.",";
		$string .= $info->identity_card.",";
		$string .= $this->email.",";
		$string .= date('Y-m-d').",";
		$string .= $this->code.",";
		$string .= $this->code_name.",";
		$string .= date('Y-m-d').",";
		$string .= $order->amount.",";
		$string .= $this->pay_method.",";
		$string .= $transaction_id;
		$string .= "&".$this->getPrice($order);
		
		return $string;
	}
	
	public function parseResult($result)
	{
		$r = explode("&",$result);
		
		if(strpos($r[1], ";") !== false) {
			$r2 = explode(";",$r[1]);
			
			$ret=array();
			foreach($r2 as $k=>$v) {
				$v2 = explode(",",$v);
				$ret['code'][$k] = $v2[0];
				$ret['pwd'][$k] = $v2[1];
			}
		} else {
			$v2 = explode(",",$r[1]);
			$ret['code'][] = $v2[0];
			$ret['pwd'][] = $v2[1];
		}
		
		return $ret;
	}
	
	public function getPrice($order)
	{
		$order->unit_price = intval($order->unit_price);
		if($order->num==1) {
			return $order->unit_price.",".self::$products[$order->unit_price];
		} else {
			$arr = array();
			for($i=1;$i<=$order->num;$i++) {
				$arr[] = $order->unit_price.",".self::$products[$order->unit_price];
			}
			return implode(";",$arr);
		}
	}
}