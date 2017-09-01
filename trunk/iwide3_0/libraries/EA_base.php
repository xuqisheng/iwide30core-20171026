<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EA_base
{
	private static $_objs=array();  //对象容器
    
	public function __construct()
	{
	    return $this;
	}

	public static function inst($className=__CLASS__)
	{
		if(isset(self::$_objs[$className])) {
			return self::$_objs[$className];
			
		} else {
			return self::$_objs[$className]=new $className(null);
		}
	}
	
    //正确的状态标记
	const STATUS_TRUE	=1;
	const STATUS_FALSE	=2;

	//当前的状态标记
	const STATUS_TRUE_	=0;
	const STATUS_FALSE_	=1;
	
	public static function get_status_options( $alias= array() )
	{
	    if( count($alias)>1 ){
	        $array= $alias;
	        
	    } else {
	        $array= array(
	            self::STATUS_TRUE=> '正常',
	            self::STATUS_FALSE=> '禁用',
	        );
	    }
		return $array;
	}
	public static function get_status_options_( $alias= array() )
	{
		$array= array(
			'0'=> '是',
			'1'=> '否',
		);
		return $array;
	}
	public static function get_status_label($value=null)
	{
		$array = self::get_status_options();
		if ($value===null || !isset($array[$value]) ) {
			return '-';
		} else {
			return $array[$value];
		}
	}
	public static function get_status_label_($value=null)
	{
		$array = self::get_status_options_();
		if ($value===null || !isset($array[$value]) ) {
			return '-';
		} else {
			return $array[$value];
		}
	}
}
