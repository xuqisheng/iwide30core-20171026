<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Statistics_model extends CI_Model {

	const TAB_PUB = 'publics';
	
	public function addStatisticsWebsite($inter_id,$hotel_name,$main_url){
		
		$url = "http://mf.iwide.cn/addSite.php";
		
		$data = array(
			'website_id'=>substr($inter_id,1),	
			'website_name'=>$hotel_name,
			'main_url'=>$main_url
		);
		
		$res = $this->doCurlGetRequest($url,$data);
		
		return $res;
		
	}
	
	public function addStatisticsWebsiteByInterid($inter_id){
		
		$this->db->where ( 'inter_id', $inter_id );
		$publics_info = $this->db->get ( self::TAB_PUB )->result_array ();
		
		if(count( $publics_info )> 0 ){
			
			$publics_info = $publics_info[0];
			
			return $this->addStatisticsWebsite($inter_id,$publics_info['wechat_name'],"http://".$publics_info['domain']);
		
		}else{
			
			return "error:not kown";
			
		}
			
	}
	
	public function getAllPublics(){
		$publics_info = $this->db->get ( self::TAB_PUB )->result_array ();
		return $publics_info;
	}
	
	private function doCurlGetRequest($url, $data = array(), $timeout = 10){
		if($url == "" || $timeout <= 0){
			return false;
		}
		if($data != array()){
			$url = $url . '?' . http_build_query($data);
		}
	
		$con = curl_init(( string )$url);
		curl_setopt($con, CURLOPT_HEADER, false);
		curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($con, CURLOPT_TIMEOUT, ( int )$timeout);
		curl_setopt($con, CURLOPT_SSL_VERIFYPEER, false);
	
		return curl_exec($con);
	}
	
	public function outputJs($inter_id,$openid,$title=""){
		
		$website_id = substr($inter_id,1);
		/* if($title != ""){
			$addjs = "_paq.push(['action_name', '{$title}']);";
		}else{
			$addjs ="";
		} */
		$jsData = <<<EOF
			<!-- Piwik -->
				<script type="text/javascript">
				var _paq = _paq || [];
				_paq.push(['trackPageView']);
				_paq.push(['enableLinkTracking']);
				(function() {
					var u="//mf.iwide.cn/";
					_paq.push(['setTrackerUrl', u+'piwik.php']);
					_paq.push(['setSiteId', '{$website_id}']);
					_paq.push(['setUserId', '{$openid}']);
					var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
					g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
				})();
				</script>
				<noscript><p><img src="//mf.iwide.cn/piwik.php?idsite={$website_id}" style="border:0;" alt="" /></p></noscript>
				<!-- End Piwik Code -->
EOF;
	
		return $jsData;
		
	}
		
	
}
