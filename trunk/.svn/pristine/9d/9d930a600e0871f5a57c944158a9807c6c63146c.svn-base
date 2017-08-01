<?php
/**
 * 微信基础
 *
 */
class Wxapi_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	/* 新增永久图文素材 */
	public function add_news($articles, $acctoken = '') {
		$post_data ['articles'] = $articles;

		$url = "https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=$acctoken";
		
		$result = $this->http_post($url, $post_data);
		return $result;
	}

	/* 上传图文消息内的图片获取URL */
	public function uploadimg($file,$filename, $acctoken = '') {
		$url = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=$acctoken";
		$file_info = array(
			'media'=>"@{$file}".";filename=$filename"
			);
		$result = $this->http_upload($url, $file_info);
		return $result;
	}

	/* 新增其他类型永久素材 */
	public function add_material($file,$filename, $type = 'image', $acctoken = '',$description=array()) {
		$post_data ['media'] = "@{$file}".";filename=$filename";
		// $post_data ['type'] = $type;//媒体文件类型，分别有图片（image）、语音（voice）、视频（video）和缩略图（thumb）
		if($type=='video'){
			$post_data ['description'] = $description;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=$acctoken&type=$type";
		// return $post_data;
		$result = $this->http_upload($url, $post_data);
		return $result;
	}

	/* 删除永久素材 */
	public function del_material($media_ids, $acctoken = '') {
		$result = array();
		$result['false']=0;
		$result['succ']=0;
		foreach ($media_ids as $media_id) {
			$post_data ['media_id'] = $media_id;
			$url = "https://api.weixin.qq.com/cgi-bin/material/del_material?access_token=$acctoken";
			$tmp = $this->http_post($url, $post_data);
			if($tmp['errcode'] !=0){
				$result['false']++;
			}else{
				$result['succ']++;
			}
		}
		return $result;
	}

	/* 修改永久图文素材 */
	public function update_news($media_id, $index=0,$acctoken = '',$articles=array()) {
		$post_data ['media_id'] = $media_id;
		$post_data ['index'] = $index;//要更新的文章在图文消息中的位置（多图文消息时，此字段才有意义），第一篇为0
		$post_data ['articles'] = $articles;
		$url = "https://api.weixin.qq.com/cgi-bin/material/update_news?access_token=$acctoken";
		$result = $this->http_post($url, $post_data);
		return $result;
	}

	/* 获取永久素材 */
	public function get_material($media_id, $acctoken = '') {
		$post_data ['media_id'] = $media_id;
		$url = "https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=$acctoken";
		$result = $this->http_post($url, $post_data);
		return $result;
	}

	/* 获取素材总数 */
	public function get_materialcount($acctoken = '') {
		$url = "https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=$acctoken";
		$result = $this->http_get($url);
		return $result;
	}

	/* 获取公众号已创建的标签 */
	public function get_tags($acctoken = '') {
		$url = "https://api.weixin.qq.com/cgi-bin/tags/get?access_token=$acctoken";
		$result = $this->http_get($url);
		return $result;
	}
	/* 获取素材列表 */
	public function batchget_material($type = 'image',$offset=0,$count=9,$acctoken = '') {
		$post_data ['type'] = $type;//素材的类型，图片（image）、视频（video）、语音 （voice）、图文（news）
		$post_data ['offset'] = $offset;//从全部素材的该偏移位置开始返回，0表示从第一个素材 返回
		$post_data ['count'] = $count;//返回素材的数量，取值在1到20之间
		$url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=$acctoken";
		$result = $this->http_post($url, $post_data);
		return $result;
	}

	/* 根据标签进行群发 */
	public function sendall($type = 'mpnews',$tag_id=0,$send_ignore_reprint=0,$param=array(),$acctoken = '') {
		if($type == 'news'){
			$type = 'mpnews';
		}
		$post_data ['filter'] = array("is_to_all"=>true);
		if($tag_id>0){
			$tag_id = intval($tag_id);
			$post_data ['filter'] = array("is_to_all"=>false,"tag_id"=>$tag_id);
		}
		$post_data [$type] = $param;
		$post_data ['msgtype'] = $type;
		if($type=="mpnews"){
			$post_data['send_ignore_reprint'] = $send_ignore_reprint;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=$acctoken";

		$result = $this->http_post($url, $post_data);
		return $result;
	}

	/**
	 * GET 请求
	 *
	 * @param string $url        	
	 */
	private function http_get($url) {
		$oCurl = curl_init ();
		if (stripos ( $url, "https://" ) !== FALSE) {
			curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYPEER, FALSE );
			curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYHOST, FALSE );
		}
		curl_setopt ( $oCurl, CURLOPT_URL, $url );
		curl_setopt ( $oCurl, CURLOPT_RETURNTRANSFER, 1 );
		$sContent = curl_exec ( $oCurl );
        MYLOG::w($sContent, 'weixin_api');
		$aStatus = curl_getinfo ( $oCurl );
		curl_close ( $oCurl );
		if (intval ( $aStatus ["http_code"] ) == 200) {
			return $sContent;
		} else {
			return false;
		}
	}
	
	/**
	 * POST 请求
	 *
	 * @param string $url        	
	 * @param array $param        	
	 * @return string content
	 */
	private function http_post($url, $param) {
		$oCurl = curl_init ();
		if (stripos ( $url, "https://" ) !== FALSE) {
			curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYPEER, FALSE );
			curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYHOST, false );
		}
		if (is_string ( $param )) {
			$strPOST = $param;
		} else {
			// $aPOST = array ();
			// foreach ( $param as $key => $val ) {
			// 	$aPOST [] = $key . "=" . urlencode ( $val );
			// }
			// $strPOST = join ( "&", $aPOST );
			$strPOST = json_encode($param,JSON_UNESCAPED_UNICODE);

		}
		curl_setopt ( $oCurl, CURLOPT_URL, $url );
		curl_setopt ( $oCurl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $oCurl, CURLOPT_POST, true );
		curl_setopt ( $oCurl, CURLOPT_POSTFIELDS, $strPOST );
		$sContent = curl_exec ( $oCurl );
        MYLOG::w($sContent.'|'.$strPOST, 'weixin_api');
		$aStatus = curl_getinfo ( $oCurl );
		curl_close ( $oCurl );
		if (intval ( $aStatus ["http_code"] ) == 200) {
			return $sContent;
		} else {
			return false;
		}
	}
	
	private function http_upload($url,$strPOST){
		$oCurl = curl_init ();  
        curl_setopt ( $oCurl, CURLOPT_SAFE_UPLOAD, false);  
        if (stripos ( $url, "https://" ) !== FALSE) {  
            curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYPEER, FALSE );  
            curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYHOST, false );  
        }  
  
        curl_setopt ( $oCurl, CURLOPT_URL, $url );  
        curl_setopt ( $oCurl, CURLOPT_RETURNTRANSFER, 1 );  
        curl_setopt ( $oCurl, CURLOPT_POST, true );  
        curl_setopt ( $oCurl, CURLOPT_POSTFIELDS, $strPOST );  
        $sContent = curl_exec ( $oCurl );
        MYLOG::w($sContent, 'weixin_api');
        $aStatus = curl_getinfo ( $oCurl );  
        curl_close ( $oCurl );  
        if (intval ( $aStatus ["http_code"] ) == 200) {  
            return $sContent;  
        } else {  
            return false;  
        }
	}

}