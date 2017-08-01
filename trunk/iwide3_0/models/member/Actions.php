<?php 
class Actions
{	
	protected $modules = array(
		'all'=>'所有模块',
		'member'=>'会员模块',
	    'room'=>'订房模块'
	);
	
	protected $handles = array(
		'member_online_after_checkout'=>'会员模块在线付款后',
	    'member_online_before_checkout'=>'会员模块在线付款前',
		'member_focuks_after'=>'会员关注之后',
	);
	
	protected $fields;
	
	public function getModules()
	{
		return $this->modules;
	}
	
	public function getHandles($module='')
	{
		return $this->handles;
	}
}