<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sign
{	
	protected $values = array();
	
	public function fromXml($xml)
	{
		libxml_disable_entity_loader(true);
        $this->values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        
        return $this;
	}
	
	public function toUrlParams()
	{
		$buff = "";
		foreach ($this->values as $k => $v)
		{
			if($k != "sign" && $v != "" && !is_array($v)){
				$buff .= $k . "=" . $v . "&";
			}
		}
	
		$buff = trim($buff, "&");
		return $buff;
	}
	
	public function makeSign($key)
	{
		ksort($this->values);
		$string = $this->toUrlParams();
		$string = $string . "&key=".$key;
		$string = md5($string);
		$result = strtoupper($string);
		return $result;
	}
}