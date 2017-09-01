<?php
/**
 * 平台日志类，用于平台日常记录
 * @author Race
 * @version 1.0
 */
class LOG{
	
	//日志分隔符，统一，不能改动
	const _SQER = " | ";
	
	//PMS访问及错误日志log的目录
	const _PMS_ACCESS_LOG_DIR = 'pms_log';
	

	/**
	 * 日志写入
	 * 格式：
	 * Y-m-d H:i:s | 进程id | 客户端IP | session id | 访问地址 | POST=post参数(json格式) | 内容
	 * @param unknown $content 内容
	 * @param unknown $dir 目录名称
	 * @param string $file_key	文件名标记
	 */
	static public function w( $content,$dir = 'default',$file_key = '' )
	{
		
		$file= date('Y-m-d').$file_key. '.log';
		//echo $tmpfile;die;
		$path= APPPATH.'logs'.DS. $dir. DS;
	
		if( !file_exists($path) ) {
			@mkdir($path, 0777, TRUE);
		}
	
		$fp = fopen( $path. $file, "a");
	
		$sql = str_replace("\r"," ",$content);
		$sql = str_replace("\n"," ",$content);
	
		//echo __FILE__
		$content = date("Y-m-d H:i:s")." | ".getmypid()." | ".$_SERVER['REMOTE_ADDR']." | ".session_id()." | ".$_SERVER['REQUEST_URI'].' | POST='.json_encode($_POST).' | '.$content."\n";
	
		fwrite($fp, $content);
		fclose($fp);
		
	}
	
	
	
	
	/**
	 * pms接口调用日志记录
	 * 格式：
	 * self::w()格式 | inter_id | 等待时间 | api类型 | api地址 | 发送记录 | 接收记录 | 备注
	 * @param unknown $inter_id
	 * @param unknown $pms_wait_time 接口调用耗时
	 * @param unknown $api_type api类型，用于不同酒店的api有多个接口，用于识别不同的接口
	 * @param unknown $api_url api接口链接
	 * @param unknown $send_content 发送内容
	 * @param unknown $record_content 接收到的内容
	 * @param unknown $remark 备注
	 */
	static public function pms_access_record($inter_id,$pms_wait_time,$api_type,$api_url,$send_content,$record_content,$remark){
		
		//时间 酒店inter_id 调用耗时(ms） openid 接口类别 发送记录 接收记录
		//self::w($content, $dir)
		$arr[] = $inter_id;
		$arr[] = $pms_wait_time;
		$arr[] = $api_type;
		$arr[] = $api_url;
		$arr[] = $send_content;
		$arr[] = $record_content;
		$arr[] = $remark;
		$content = implode(self::_SQER,$arr);
		
		self::w($content,self::_PMS_ACCESS_LOG_DIR,"_access");
		
		
	}
	
	/**
	 * pms接口调用日志记录
	 * 格式：
	 * self::w()格式 | ERROR=错误级别 1：严重，2：中等  | 错误提示 | inter_id | 等待时间 | api类型 | api地址 | 发送记录 | 接收记录 | 备注	 
	 * @param unknown $inter_id 
	 * @param unknown $pms_wait_time 接口调用耗时
	 * @param unknown $api_type api类型，用于不同酒店的api有多个接口，用于识别不同的接口
	 * @param unknown $api_url api接口链接
	 * @param unknown $send_content 发送内容
	 * @param unknown $record_content 接收到的内容
	 * @param unknown $remark 备注
	 * @param unknown $error_lev 错误级别 1：严重，2：中等
	 * @param unknown $error_msg 错误提示
	 */
	static public function pms_error_record($inter_id,$pms_wait_time,$api_type,$api_url,$send_content,$record_content,$remark,$error_lev,$error_msg){
	
		//时间 酒店inter_id 调用耗时(ms） openid 接口类别 发送记录 接收记录
		//self::w($content, $dir)
		//例子：2016-01-01 11:11:11 | ERROR=1 | 获取酒店信息失败 | a452342111 | 2000 | asdsedczveweqweqwsad | getHotels | {hotel_id:100} | {hotel_name:名称}
		$arr[] = "ERROR=".$error_lev;
		$arr[] = $error_msg;
		
		$arr[] = $inter_id;
		$arr[] = $pms_wait_time;
		$arr[] = $api_type;
		$arr[] = $api_url;
		$arr[] = $send_content;
		$arr[] = $record_content;
		$arr[] = $remark;
		
		$content = implode(self::_SQER,$arr);
	
		self::w($content,self::_PMS_ACCESS_LOG_DIR,"_error");
	
	
	}
	
	
	
	
}