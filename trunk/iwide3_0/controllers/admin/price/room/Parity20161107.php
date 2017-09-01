<?php
/*
 * 比价系统
 * Date 2016-08-08
 * author chenjunyu
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Parity20161107 extends MY_Admin {
	private $roomkeys = array(
		'first'=>array('高级','豪华','行政','总统','商务',),
		'refirst'=>array('皇室','蜜月','家庭','精品','情侣','亲子','童趣','观景','休闲','温泉','主题','婚庆','棋牌','特惠','麻将','花园','标准','海景','园景','江','湖景','山景','雅景','高尔夫景','无障碍','雅致','和式','贵宾','平江','天香','世家','女宾','特价','好莱坞','庭院','景观','特色','情趣','热销','府邸','小镇','天井小院','时尚','休闲','数码','迷你','圆床','中式','日式','风情','风雅','精选','精致','都会','顶级','普通','温馨','概念','欧式','水床','娱乐','生日','假日','钟点','零压','阳光','浴缸','现代','简约','街','精英','迷幻','日租','经济','白银时代','流金岁月','书香门第',),
		'second'=>array('单床','大床','双床','多床','三床','四床',),
		'third'=>array('无早','单早','双早','三早','四早','五早','六早','七早','八早','九早','十早',),
		'fourth'=>array('套','别墅','一卧','二卧','三卧','四卧','五卧','六卧','七卧',),
		);
	private $timerooms = array('钟点','时租','半日',);
	private $prior = array('总统',);//如果房型属性包含当中一种，则直接判断为匹配对
	private $special = array('高级商务'=>'商务','行政商务'=>'行政','豪华行政'=>'行政','豪华商务'=>'豪华','高级商务'=>'高级','豪华商务'=>'商务',);//一些特殊的房型名，用于特别匹配，'房型名'=>'可匹配的房型名'
	private $ignore = array('总统','别墅',);//如果房型名完全相同又都包含这些属性，则直接匹配
	private $htmldir = '/data/parity/';
	private $htmldir1 = './public/ctriphtml/';
	private $ctriprooms = array();
	private $norefirst = array();
	private $verifcode = array(
		'a421641095'=>'feTZhKgOqClIry7B',//碧桂园
		'a449675133'=>'UzylHGarFacxQmIK',//书香
		'a441098524'=>'ExG4SQkJbEvbuxXc',//逸柏
		'a445223616'=>'cEKm2ZywY9HOruJB',//云盟
		'a464919542'=>'Q6cgfdUjcN6mYWLO',//清沐
		'a455510007'=>'BMqlgUL4VhF2XrJc',//速8
		'a426755343'=>'GTf8LI2giy7HRWN1',//岭南佳园
		'a452223043'=>'p7H0molXrfAR9i8x',//莫林风尚
		'a464177542'=>'GjTKFq5ICLz9Ulgp',//百时快捷
		'a434597274'=>'fVAc5Rnaeoy0tjZG',//流花宾馆
		'a450682197'=>'Eg0zh8SwbgV18Thx',//江门柏丽宜居
		'a456970175'=>'oMImQhAz1P5s1biB',//君亭
		'a454320235'=>'UIAzITCW5hPHjdmr',//智尚
		'a457946152'=>'Gclejig0zn36SrNU',//隐居
		'a468209719'=>'ca7BezJnEiMhbs69',//戴斯
		'a472731074'=>'NUq09F5c1nMaKJjd',//金泰旅游
		'a440577876'=>'KjgSGuc9kHFdr0NC',//远洲
		'a472731996'=>'QscEdCFvlHBqUhk6',//雅斯特
		);
	private $hotelgroupname = array(
		'a421641095'=>'碧桂园酒店集团',//碧桂园
		'a449675133'=>'书香酒店',//书香
		'a441098524'=>'逸柏酒店',//逸柏
		'a445223616'=>'云盟酒店',//云盟
		'a464919542'=>'清沐连锁酒店',//清沐
		'a455510007'=>'速8酒店',//速8
		'a426755343'=>'岭南佳园连锁酒店',//岭南佳园
		'a452223043'=>'莫林风尚连锁酒店',//莫林风尚
		'a464177542'=>'百时快捷酒店',//百时快捷
		'a434597274'=>'流花宾馆',//流花宾馆
		'a450682197'=>'江门柏丽宜居连锁酒店',//江门柏丽宜居
		'a456970175'=>'君亭酒店',//君亭
		'a454320235'=>'智尚酒店',//智尚
		'a457946152'=>'隐居酒店',//隐居
		'a468209719'=>'戴斯酒店集团',//戴斯
		'a472731074'=>'金泰旅游',//金泰旅游
		'a440577876'=>'远洲酒店',//远洲
		'a472731996'=>'雅斯特酒店',//雅斯特
		); 
	private static $redis=null;
	private $bgysort = array(
		15=>56,
		38=>52,
		25=>50,
		12=>44,
		34=>39,
		37=>32,
		54=>31,
		50=>27,
		31=>26,
		271=>25,
		60=>25,
		32=>25,
		45=>22,
		13=>20,
		39=>20,
		44=>17,
		19=>16,
		7=>16,
		61=>16,
		17=>16,
		9=>15,
		5=>13,
		20=>13,
		27=>13,
		23=>12,
		26=>11,
		10=>11,
		6=>11,
		29=>10,
		18=>7,
		1=>7,
		3=>6,
		59=>6,
		28=>6,
		2=>6,
		24=>5,
		33=>5,
		35=>4,
		22=>4,
		36=>3,
		11=>3,
		14=>3,
		40=>2,
		4=>2,
		42=>2,
		43=>2,
		41=>2,
		8=>1,
		63=>1,
		30=>0,
		21=>0,
		3362=>0,
		3365=>0,
		16=>0,);
	private $currcname = '';
	private $curriname = '';
	const TAB_PCH = 'price_third_hotels';//酒店匹配表
	const TAB_PWR = 'price_weixin_rooms';//微信房型信息表
	const TAB_PRP = 'price_room_parity';//房型匹配表
	const TAB_PTR = 'price_third_rooms';//第三方酒店房型信息表
	public function __construct(){
		parent::__construct();
		if(!in_array($this->input->get('verifcode'),$this->verifcode)){
			exit('非法访问！');
		}
		$this->load->library('LOG');
	}

	public function index(){
		
	}

	/*
	 * 对比价格
	 */
	public function showList(){
		error_reporting(E_ALL ^ E_NOTICE);
		set_time_limit(0);
		ini_set('memory_limit','-1');
		// $ctrip_id = $this->input->get('ctrip_id');
		// $hotel_id = $this->input->get('hotel_id');
		// $inter_id = $this->input->get('inter_id');
		// if($ctrip_id){
		// 	$where = "where ctrip_id='$ctrip_id'";
		// }elseif($hotel_id&&$inter_id){
		// 	$where = "where hotel_id='$hotel_id' and inter_id='$inter_id'";
		// }else{
		// 	die('缺少相关参数');
		// }
		$data['hgname'] = $this->hotelgroupname[array_search($this->input->get('verifcode'),$this->verifcode)];
		$where = "where (ctrip_id is not null or ctrip_id<>'') and grab_flag=1 and inter_id='".array_search($this->input->get('verifcode'),$this->verifcode)."'";
		/*
		$this->load->library('pagination');
	 	$config['base_url'] = base_url().'index.php/parity/showList/';
	 	$config ['uri_segment'] = 3;
	 	$count = $this->db->query("select count(*) as count from ctrip_hotels ".$where);
	 	$countarr = $count->result_array();
	 	$config['total_rows'] = $countarr[0]['count'];
	 	$config['per_page'] = '3';
	 	$config ['first_link'] = '首页';
  		$config ['last_link'] = '末页';
  		$config ['next_link'] = '下一页>';
  		$config ['prev_link'] = '<上一页';
  		$config['use_page_numbers'] = true;
	 	$pagination = $this->pagination->initialize($config);
	 	$page = $this->pagination->create_links();
	 	$data['page'] = $page; 
	 	$p = $this->uri->segment(3)?max(1,min(ceil($config['total_rows']/$config['per_page']),$this->uri->segment(3))):1;
	 	$offset =  ($p-1)*$config['per_page'];
		$out = $this->db->query("select * from ctrip_hotels ".$where." limit $offset,".$config['per_page']);
		*/
		$out = $this->db->query("select * from ".$this->db->dbprefix(self::TAB_PCH)." ".$where);
		$result_array = $out->result_array();
		$result_array_sort = array();
		//判断是碧桂园
		if($this->input->get('verifcode')=='feTZhKgOqClIry7B'){
			foreach ($this->bgysort as $ksort=>$vsort) {
				foreach ($result_array as $vr) {
					if($ksort==$vr['hotel_id']){
						$vr['hid'] = $ksort;
						$vr['num'] = $vsort; 
						$result_array_sort[] = $vr;
						break;
					}	
				}
			}
		}else{
			$result_array_sort = $out->result_array();
		}
		$lists = array();
		foreach ($result_array_sort as $ch => $vh) {
			// $this->load->model ( 'price/Order_api_model' );
			// $rkey = md5($vh['inter_id'].$vh['hotel_id']);
			// $foo = $this->RunRedis($rkey,'get');
			// if(empty($foo)){
			// 	$iwiderooms = $this->Order_api_model->get_roomstate($vh['inter_id'],$vh['hotel_id']);
			// 	$jsoni = json_encode($iwiderooms);
			// 	$this->RunRedis($rkey,'set',$jsoni,12*60*60);
			// }else{
			// 	$iwiderooms = json_decode($foo,1);
			// }
			$outinfo = $this->db->query("SELECT * FROM ".$this->db->dbprefix(self::TAB_PWR)." WHERE inter_id='{$vh['inter_id']}' AND hotel_id='{$vh['hotel_id']}' AND batch=(SELECT MAX(batch) FROM ".$this->db->dbprefix(self::TAB_PWR)." WHERE inter_id='{$vh['inter_id']}' AND hotel_id='{$vh['hotel_id']}')");
			$iwiderooms = $outinfo->result_array();
			$htmlfile = $vh['ctrip_id'].'.html';
			$rooms = $this->getCtriprooms($htmlfile);
			if(is_array($rooms)){
				$mins = $this->brushrooms($rooms);
			}else{
				Log::w($rooms,'parity');
				continue;
			}
			$nomins = $mins;
			// var_dump($rooms);exit;
			// if($iwiderooms['result_code']==1&&$iwiderooms['errmsg']=='查询成功'){
			$attrs = array();
			foreach ($iwiderooms as $key => $value) {
				$attrs1 = $value['inter_id'].$value['hotel_id'].$value['hotel_name'].$value['room_name'].$value['price_name'].$value['price_code'].$value['total_price'].$value['book_status'];
				$attrs[$key] = $attrs1;
			}
			$attrskey = array_keys(array_unique($attrs));
			$iwideroomsarr = array();
			$k = 0;	
			foreach ($iwiderooms as $key => $value) {
				if(in_array($key, $attrskey)){
					$iwideroomsarr[$k]['price'] = $value['total_price'];
					$bf = '';
					if($value['price_name']){
						if($pos = mb_strpos($value['price_name'],'早')){
							$bf = mb_substr($value['price_name'],$pos-1);
						}
					}
					$iwideroomsarr[$k]['roomname'] = trim($value['room_name']).$bf;
					$iwideroomsarr[$k]['room_id'] = $value['room_id'];
					$iwideroomsarr[$k]['price_name'] = trim($value['room_name']).'_'.$value['price_name'];
					$iwideroomsarr[$k]['book_status'] = trim($value['book_status'])=='full'?'(已满)':'';
					$iwideroomsarr[$k]['room_name'] = trim($value['room_name']);
					$k++;
				}
			}
			$noiwiderooms = $iwideroomsarr;
			// }else{
			// Log::w('errno:'.$iwiderooms['result_code'].',error:'.$iwiderooms['errmsg'],'parity');
			// continue;
			// }
			// var_dump($iwideroomsarr);exit;
			$data['hotel_name'][] = $vh['name'];
			$output = array();
			$repeatroom = array();//记录已经出现过的两边完全相同的房型名
			$cirooms = $this->db->query("select * from ".$this->db->dbprefix(self::TAB_PRP)." where third_id='".$vh['third_id']."'");
			$ciroomsinfo = $cirooms->result_array();
			if($ciroomsinfo){
				$inpricerooms = '';
				foreach ($ciroomsinfo as $row) {
					$iprice = '';
					$cprice = '';
					$book_status = '';
					foreach ($iwideroomsarr as $iwide) {
						if($iwide['room_id']==$row['room_id']){
							$iprice = $iwide['price'];
							$book_status = $iwide['book_status'];
							break; 
						}	
					}
					foreach ($mins as $min) {
						if($min['roomname']==$row['ctrip_name']){
							$cprice = $min['price'];
							break;
						}
					}
					$output[] = array(
						'ctrip_name'=>$this->getCtripOriginal($row['ctrip_name']),
						'ctrip_price'=>'¥'.number_format((float)$cprice,2),
						'iwide_name'=>$row['iwide_name'],
						'iwide_price'=>'¥'.number_format((float)$iprice,2),
						'chajia'=>bcsub($cprice,$iprice,2),
						'book_status'=>$book_status,
						);
				}
			}else{
				$crefirst = array();
				foreach ($mins as $km => $vm) {
					$kroom = $this->splitRoomName(trim($vm['roomname']));
					if(isset($kroom['refirst'])){
						$crefirst = array_merge($crefirst,$kroom['refirst']);
					}
				}
				$crefirst = array_unique($crefirst);
				$irefirst = array();
				foreach ($iwideroomsarr as $ki => $vi) {
					$iroom = $this->splitRoomName(trim($vi['roomname']));
					if(isset($iroom['refirst'])){
						$irefirst = array_merge($irefirst,$iroom['refirst']);
					}
				}
				$irefirst = array_unique($irefirst);
				$this->norefirst = array_intersect($crefirst, $irefirst);
				$isfor = 0;
				foreach ($mins as $k => $v) {
					$ok = 0;
					$roomarr = array();
					$reoutput = array();
					$samename = true;
					$this->currcname = $v['room_name'];
					//房型名去掉类似（xx）这种
					if(($lpos=mb_strpos($this->currcname,'('))!==false&&($rpos=mb_strpos($this->currcname,')'))!==false){
						$this->currcname = mb_substr($this->currcname,0,$lpos).mb_substr($this->currcname,$rpos+1);
					}elseif(($lpos=mb_strpos($this->currcname,'（'))!==false&&($rpos=mb_strpos($this->currcname,'）'))!==false){
						$this->currcname = mb_substr($this->currcname,0,$lpos).mb_substr($this->currcname,$rpos+1);
					}
					//判断当前房型是是否是钟点房或时租房
					$iszd = false; //默认不是
					foreach ($this->ctriprooms as $kc => $vc) {
						if($vc['nkey']==$v['nkey']){
							foreach ($this->timerooms as $vt) {
								if(mb_strpos($vc['remark'],$vt)!==false){
									$iszd = true;
									$remarks = $vc['remark'];
									break;
								}
							}
							if($iszd){
								break;
							}
						}
					}
					foreach ($iwideroomsarr as $ke => $val) {
						// $status = $this->analysisRoom($v['roomname'],$val['roomname']);var_dump($status);exit;
						$this->curriname = $val['room_name'];
						//兼容房型名中的"间"和"房"
						if(in_array(mb_substr($this->curriname, -1,1),array('间','房'))&&in_array(mb_substr($this->currcname, -1,1),array('间','房'))){
							$this->curriname = mb_substr($this->curriname,0,mb_strlen($this->curriname)-1).mb_substr($this->currcname,-1,1);
						}
						if(mb_strpos($val['price_name'], '积分')===false&&$this->analysisRoom($v['roomname'],$val['roomname'])){						
							//如果是钟点/时租房且当前公众号房型又不是钟点/时租房则跳过此次循环
							$istime = false;
							foreach ($this->timerooms as $vt) {
								if($iszd===true&&mb_strpos($val['price_name'], $vt)===false){
									$istime = true;
									break;
								}
							}
							if($istime===true){
								continue;
							}

							//如果当前公众号房型是钟点/时租房而携程房型不是则跳过此次循环
							$isnotime = false;
							foreach ($this->timerooms as $vt) {
								if(mb_strpos($val['price_name'], $vt)!==false&&mb_strpos($v['roomname'], $vt)===false){
									$isnotime = true;
									break;
								}
							}
							if($isnotime){
								continue;
							}

							//如果当前公众号房型是日租房而携程房型不是则跳过此次循环
							if(mb_strpos($val['price_name'], '日租')!==false&&mb_strpos($v['roomname'], '日租')===false){
								continue;
							}
							//差价大于或等于公众号本身价格的视为匹配异常
							if(bcsub($v['price'],$val['price'],2)>=$val['price']){
								continue;
							}
							//如果公众号房型名没有“房”字而携程有且两边都有字母，则补上.“间”与“房”视为相同
							preg_match_all("/[a-zA-Z]{1}/",$this->currcname,$arrc);
							$cnum = count($arrc[0]);
							preg_match_all("/[a-zA-Z]{1}/",$this->curriname,$arri);
							$inum = count($arri[0]);
							$cletter = mb_substr($this->currcname,mb_strlen($this->currcname)-$cnum,$cnum);
							$iletter = mb_substr($this->curriname,mb_strlen($this->curriname)-$cnum,$inum);
							if(preg_match ("/^[A-Za-z]/",$cletter)&&preg_match ("/^[A-Za-z]/",$iletter)){ 
								if(mb_strpos($this->currcname, '间')!==false&&mb_strpos($this->curriname, '房')!==false&&mb_strpos($this->curriname, '间')===false){
									$this->curriname = mb_substr($this->curriname,0,mb_strlen($this->curriname)-$inum-1).'间'.$iletter;
								}elseif(mb_strpos($this->currcname, '房')!==false&&mb_strpos($this->curriname, '间')!==false&&mb_strpos($this->curriname, '房')===false){
									$this->curriname = mb_substr($this->curriname,0,mb_strlen($this->curriname)-$inum-1).'房'.$iletter;
								}elseif(mb_strpos($this->currcname, '房')!==false&&mb_strpos($this->curriname, '房')===false){
									$this->curriname = mb_substr($this->curriname,0,mb_strlen($this->curriname)-$inum).'房'.$iletter;
								}elseif(mb_strpos($this->currcname, '间')!==false&&mb_strpos($this->curriname, '间')===false){
									$this->curriname = mb_substr($this->curriname,0,mb_strlen($this->curriname)-$inum).'间'.$iletter;
								}
							}else{
								//房型名不包含字母，两边都去掉“房”、“间”
								if(in_array(mb_substr($this->currcname, -1,1),array('间','房'))){
									$this->currcname = mb_substr($this->currcname,0,mb_strlen($this->currcname)-1);
								}
								if(in_array(mb_substr($this->curriname, -1,1),array('间','房'))){
									$this->curriname = mb_substr($this->curriname,0,mb_strlen($this->curriname)-1);
								}
							}
							$roomarr[] = $this->curriname;
							$issame = false;
							if(strnatcasecmp($this->currcname,$this->curriname)==0){
								$issame = true;
								$samename = false;
								$repeatroom[] = $this->curriname;
							}
							$insert = "inter_id='".$out->row()->inter_id."',hotel_id='".$out->row()->hotel_id."',room_id='".$val['room_id']."',iwide_name='".$val['roomname']."',ctrip_name='".$v['roomname']."',ctrip_id='".$out->row()->ctrip_id."'";
							// $this->db->query("insert into ctrip_iwide_rooms set ".$insert);
							$reoutput[] = array(
								'ctrip_name'=>$this->getCtripOriginal($v['nkey']),
								'cname'=>$this->currcname,
								'ctrip_price'=>'¥'.number_format((float)$v['price'],2),
								'iwide_name'=>$val['price_name'],
								'iname'=>$this->curriname,
								'iwide_price'=>'¥'.number_format((float)$val['price'],2),
								'chajia'=>bcsub($v['price'],$val['price'],2),
								'book_status'=>$val['book_status'],
								'issame'=>$issame,
								);
							$ok = 1;
							unset($noiwiderooms[$ke]);
							// Log::w($vh['name'].'=>携程：“'.$v['roomname'].'”，iwide：“'.$val['roomname'].'”，匹配成功','parity');
						}
					}
					if($ok==0){
						$insert = "ctrip_name='".$v['roomname']."',ctrip_id='".$out->row()->ctrip_id."'";
						// $this->db->query("insert into ctrip_iwide_rooms set ".$insert);
						$output[] = array(
							'ctrip_name'=>$this->getCtripOriginal($v['nkey']),
							'cname'=>$this->currcname,
							'ctrip_price'=>'¥'.number_format((float)$v['price'],2),
							'iwide_name'=>'',
							'iname'=>'',
							'iwide_price'=>'',
							'chajia'=>'',
							'book_status'=>'',
							);
						// Log::w($vh['name'].'=>携程：“'.$v['roomname'].'”，无匹配','parity');
					}else{
						$isfor = 1;
						if(in_array($this->currcname,$roomarr)||in_array(strtolower($this->currcname),$roomarr)||in_array(strtoupper($this->currcname),$roomarr)){
							if($samename===false){
								foreach ($reoutput as $key => $value) {
									if($value['issame']===false){
										unset($reoutput[$key]);
									}
								}
							}
						}
						if(!empty($reoutput)&&is_array($reoutput)){
							$ismore = true;
							foreach ($reoutput as $key => $value) {
								if($value['chajia']>=0.00){
									$ismore = false;
								}
							}
							if($ismore===false){
								foreach ($reoutput as $key => $value) {
									$reoutput[$key]['ismore'] = 1;
									break;
								}
							}
						}
						$output = array_merge($output,$reoutput);
						unset($nomins[$k]);
					}
				}
				if($isfor==0){
					LOG::w('该酒店无一匹配，name:'.$vh['name'].'，inter_id:'.$vh['inter_id'].'，hotel_id:'.$vh['hotel_id'],'parity');
				}
				$renoiwiderooms = $noiwiderooms;
				foreach ($nomins as $k => $v) {
					foreach ($noiwiderooms as $ke=>$val) {
						//兼容房型名中的"间"和"房"
						if(in_array(mb_substr($val['room_name'], -1,1),array('间','房'))&&in_array(mb_substr($v['room_name'], -1,1),array('间','房'))){
							$val['room_name'] = mb_substr($val['room_name'],0,mb_strlen($val['room_name'])-1).mb_substr($v['room_name'],-1,1);
						}
						if($val['room_name']==$v['room_name']){
							foreach ($output as $key => $value) {
								if($value['ctrip_name']==$this->getCtripOriginal($v['nkey'])&&$value['iwide_name']==''&&mb_strpos($val['price_name'],'积分')===false){
									//排除钟点/时租房
									$iszsr = false;
									foreach ($this->timerooms as $vt) {
										if((mb_strpos($value['ctrip_name'], $vt)!==false&&mb_strpos($value['price_name'], $vt)===false)||(mb_strpos($value['ctrip_name'], $vt)===false&&mb_strpos($value['price_name'], $vt)!==false)){
											$iszsr = true;
										}
									}
									if($iszsr===true){
										break;
									}
									if((mb_strpos($value['ctrip_name'], '日租')!==false&&mb_strpos($value['price_name'], '日租')===false)||(mb_strpos($value['ctrip_name'], '日租')===false&&mb_strpos($value['price_name'], '日租')!==false)){
										break;
									}
									$output[$key]['iwide_name'] = $val['price_name'];
									$output[$key]['iwide_price'] = '¥'.number_format((float)$val['price'],2);
									$output[$key]['chajia'] = bcsub($v['price'],$val['price'],2);
									$output[$key]['book_status'] = $val['book_status'];
									unset($renoiwiderooms[$ke]);
									break;
								}
							}
						}
					}
				}
				foreach ($renoiwiderooms as $nv){ 
					if(mb_strpos($nv['price_name'],'积分')!==false){
						$iwide_price = number_format($nv['price']).'积分';
					}else{
						$iwide_price = '¥'.number_format((float)$nv['price'],2);
					}
					$output[] = array(
						'ctrip_name'=>'',
						'cname'=>'',
						'ctrip_price'=>'',
						'iwide_name'=>$nv['price_name'],
						'iname'=>$this->curriname,
						'iwide_price'=>$iwide_price,
						'chajia'=>'',
						'book_status'=>$nv['book_status'],
						);					
				}
			}

			foreach ($output as $ko => $vo) {
				// 剔除两边房型名不一样，公众号房型名又有在别的房型匹配中被因为房型名完全相同而匹配过
				if(!empty($vo['cname'])&&!empty($vo['iname'])&&$vo['cname']!=$vo['iname']&&in_array($vo['iname'],$repeatroom)){
					unset($output[$ko]);
				}	
			}
			//重置索引
			$output = array_values($output);

			$o = 1;
			$ciminprices = array();
			$zdian1 = true;//默认全是带钟点/时租或日租的房型
			$zdian2 = true;//默认全是带钟点/时租或日租的房型
			$zdianx1 = true;
			$zdianx2 = true;
			foreach ($output as $ko => $vo) {
				foreach ($this->timerooms as $vt) {
					if(!empty($vo['ctrip_name'])&&mb_strpos($vo['ctrip_name'],$vt)===false&&mb_strpos($vo['ctrip_name'],'日租')===false){
						$zdianx1 = false;
					}else{
						$zdianx1 = true;
					}
					if(!empty($vo['iwide_name'])&&mb_strpos($vo['iwide_name'],$vt)===false&&mb_strpos($vo['iwide_name'],'日租')===false){
						$zdianx2 = false;
					}else{
						$zdianx2 = true;
					}
				}
				if($zdianx1===false){
					$zdian1 = false;
				}
				if($zdianx2===false){
					$zdian2 = false;
				}
			}
			foreach ($output as $ko=>$vo) {
				if($zdian1){
					if(!empty($vo['ctrip_price'])){
						$ciminprices['cprices'][] = str_replace(',','',trim($vo['ctrip_price'],'¥'));
						$ciminprices['croominfo'][] = $vo['ctrip_name'];
					}
				}else{
					if(!empty($vo['ctrip_price'])&&mb_strpos($vo['ctrip_name'],'钟点')===false&&mb_strpos($vo['ctrip_name'],'时租')===false&&mb_strpos($vo['ctrip_name'],'半日')===false&&mb_strpos($vo['ctrip_name'],'日租')===false){
						$ciminprices['cprices'][] = str_replace(',','',trim($vo['ctrip_price'],'¥'));
						$ciminprices['croominfo'][] = $vo['ctrip_name'];
					}
				}
				if($zdian2){
					if(!empty($vo['iwide_price'])&&mb_strpos($vo['iwide_price'],'积分')===false){
						$ciminprices['iprices'][] = str_replace(',','',trim($vo['iwide_price'],'¥'));
						$ciminprices['iroominfo'][] = $vo['iwide_name'];
					}
				}else{
					if(!empty($vo['iwide_price'])&&mb_strpos($vo['iwide_price'],'积分')===false&&mb_strpos($vo['iwide_name'],'钟点')===false&&mb_strpos($vo['iwide_name'],'时租')===false&&mb_strpos($vo['iwide_name'],'半日')===false&&mb_strpos($vo['iwide_name'],'日租')===false){
						$ciminprices['iprices'][] = str_replace(',','',trim($vo['iwide_price'],'¥'));
						$ciminprices['iroominfo'][] = $vo['iwide_name'];
					}
				}
				if($ko>=1&&$vo['ctrip_name']==$output[$ko-1]['ctrip_name']&&!empty($vo['ctrip_name'])){
					$o++;
					if($ko==(count($output)-1)){
						$output[$ko-$o+1]['cop'] = $o;
					}
				}else{
					if($ko==0){
						$output[0]['cop'] = $o;
					}else{
						$output[$ko-$o]['cop'] = $o;
					}
					$o=1;
				}
			}
			// var_dump($ciminprices);exit;
			$unshift = array();
			if(!empty($ciminprices['cprices'])&&!empty($ciminprices['iprices'])&&is_array($ciminprices['cprices'])&&is_array($ciminprices['iprices'])){
				$cprice = min($ciminprices['cprices']);
				$croominfo = $ciminprices['croominfo'][array_search($cprice,$ciminprices['cprices'])];
				$iprice = min($ciminprices['iprices']);
				$iroominfo = $ciminprices['iroominfo'][array_search($iprice,$ciminprices['iprices'])];
				$unshift = array(
					'ctrip_name'=>'最低价房型：'.$croominfo,
					'ctrip_price'=>'¥'.$cprice,
					'iwide_name'=>'最低价房型：'.$iroominfo,
					'iwide_price'=>'¥'.$iprice,
					'chajia'=>bcsub($cprice,$iprice,2),
					'book_status'=>'',
					);
			}
			if(isset($vh['hid'])&&isset($vh['num'])){
				$lists[$vh['name'].'(id='.$vh['hid'].') '.$vh['num']] = $output;
				if(!empty($unshift)){
					array_unshift($lists[$vh['name'].'(id='.$vh['hid'].') '.$vh['num']],$unshift);
				}
			}else{
				$lists[$vh['name']] = $output;
				if(!empty($unshift)){
					array_unshift($lists[$vh['name']],$unshift);
				}
			}
			$this->ctriprooms = array();
			// var_dump($lists);exit;
		}
		$data['lists'] = $lists;
		// echo $output;
		$this->_render_content ( $this->_load_view_file ( 'room/parity_list' ), $data, false );
	}

	/*
	 * redis调用
	 * @param1 $key 键
	 * @param2 $value 值
	 * @param3 $time 有效期
	 * @param4 $type 读(get)/写(set)
	 * return bo,'get'ol 
	 */
	private function RunRedis($key,$type,$value='',$time=0){
		if(null===self::$redis){
			self::$redis = new Redis ();
			self::$redis->connect('127.0.0.1',6379);
		} 
		if($type=='set'){
			return self::$redis->set( $key, $value, $time);
		}elseif($type=='get'){
			return self::$redis->get( $key );
		}else{
			return false;
		}
	}

	/*
	 * 取回携程初始房型属性
	 * @param1 String $roomname 需要找回的名字
	 * return String 初始属性
	 */
	private function getCtripOriginal($nkey){
		if($nkey===null||$nkey===false){
			return false;
		}
		foreach ($this->ctriprooms as $key => $value) {
			if($value['nkey']==$nkey){
				unset($value['price']);
				$gift = '';
				if($value['gift']){
					$gift = '('.$value['gift'].')';
				}
				unset($value['gift']);
				unset($value['nkey']);
				$res = implode('--',$value);
				return $res?$res.$gift:false;				
			}
		}
		// $chuangrpos = mb_strrpos($roomname,'床');
		// foreach ($this->ctriprooms as $key => $value) {
		// 	if(!empty($value['bed'])){
		// 		if(mb_strpos($value['bed'],'/')!==false){
		// 			$bedlen = mb_strlen($value['bed'])-1;
		// 		}else{
		// 			$bedlen = mb_strlen($value['bed']);
		// 		}
		// 		$cname = mb_substr($roomname,0,$chuangrpos-$bedlen+1);
		// 		$cbed = mb_substr($roomname,$chuangrpos-$bedlen+1,$bedlen-1);
		// 		if(!empty($cbed)&&mb_strpos($value['bed'],$cbed)!==false){
		// 			if(!empty($value['breakfast'])){
		// 				if(mb_strpos($roomname,$value['breakfast'])!==false){
		// 					if($value['roomname']==$cname){
		// 						unset($value['price']);
		// 						$gift = '';
		// 						if($value['gift']){
		// 							$gift = '('.$value['gift'].')';
		// 						}
		// 						unset($value['gift']);
		// 						$res = implode('--',$value);
		// 						return $res?$res.$gift:false;
		// 					}
		// 				}
		// 			}else{
		// 				if($value['roomname']==$cname){
		// 					unset($value['price']);
		// 					$gift = '';
		// 					if($value['gift']){
		// 						$gift = '('.$value['gift'].')';
		// 					}
		// 					unset($value['gift']);
		// 					$res = implode('--',$value);
		// 					return $res?$res.$gift:false;
		// 				}
		// 			}
		// 		}
		// 	}else{
		// 		if(!empty($value['breakfast'])&&mb_strpos($roomname,$value['breakfast'])!==false){
		// 			if($value['roomname']==$this->currcname){
		// 				unset($value['price']);
		// 				$gift = '';
		// 				if($value['gift']){
		// 					$gift = '('.$value['gift'].')';
		// 				}
		// 				unset($value['gift']);
		// 				$res = implode('--',$value);
		// 				return $res?$res.$gift:false;
		// 			}
		// 		}else{
		// 			if($value['roomname']==$this->currcname){
		// 				unset($value['price']);
		// 				$gift = '';
		// 				if($value['gift']){
		// 					$gift = '('.$value['gift'].')';
		// 				}
		// 				unset($value['gift']);
		// 				$res = implode('--',$value);
		// 				return $res?$res.$gift:false;
		// 			}
		// 		}
		// 	}
		// }
		LOG::w('获取携程初始房型属性失败，roomname:'.$roomname,'parity');
	}


	/*
	 *生成数组唯一值
	 * @param1 Array $arr 需要转的数组
	 * return String 生成的唯一字符 
	 */
	private function getArrToStr($arr){
		$str = '';
		foreach ($arr as $key => $value) {
			$str .= $value;
		}
		return md5($str);
	}

	//获取携程网城市列表信息
	private function getCtripCityList(){
		$lastidtime = $this->db->query('select addtime from ctrip_city order by cid desc limit 1');
		if(!$lastidtime->row()||$lastidtime->row()->addtime+60*60*24<time()){
			if(!$ctripCityInfo = $this->cache->get('ctripCityInfo')){
				$ctripCityInfo = doCurlGetRequest($this->ctripCityUrl);
				$this->cache->save('ctripCityInfo',$ctripCityInfo,60*60*24);
			}  
			$arr = $this->handleCtripCityString($ctripCityInfo);
			$rows = $this->db->insert_batch('ctrip_city',$arr);
			echo '成功插入：'.$rows.'条数据，时间：'.date('Y-m-d H:i:s');
		}else{
			echo '数据已存在';
		}
	}

	/*
	 * 携程城市列表字符串处理
	 * @param1 $str 需要处理的字符串
	 * return Array $rearr 处理完毕的数组
	 */
	private function handleCtripCityString($str){
		$hstr = mb_substr($str,mb_strpos($str,'{d'),mb_strlen($str)-mb_strpos($str,'{d')-2);
		$arr = explode('},{',$hstr);
		$arr[0] = trim($arr[0],'{');
		$arr[count($arr)-1] = trim(end($arr),'}');
		$rearr = array();
		foreach ($arr as $k => $v) {
			$rev = explode(',',$v);
			foreach ($rev as $ke => $val) {
				$reval = explode(':',$val);
				switch ($ke) {
					case '0':
						$rearr[$k]['cityname'] = trim($reval[1],'"');
						break;
					case '1':
						$pinyin = explode('|',trim($reval[1],'"'));
						$rearr[$k]['pinyin'] = $pinyin[0];
						$rearr[$k]['ctripid'] = $pinyin[2];
						break;
					case '2':
						$rearr[$k]['group'] = trim($reval[1],'"');
						break;
					default:
						break;
				}
			}
			$rearr[$k]['addtime'] = time(); 
		}
		return $rearr;
	}

	/*
	 * 截取房型内容
	 * param1 $htmlfile 网页文本字符串
	 * return Array 对应携程酒店房型属性
	 */
	private function getCtriprooms($htmlfile){
		if(is_file($this->htmldir.$htmlfile)||is_file($this->htmldir1.$htmlfile)){
			$handler = @fopen($this->htmldir1.$htmlfile, 'r');
			if(!$handler){
				$handler = @fopen($this->htmldir.$htmlfile, 'r');
			}
			if(!$handler){
				return 'ERROR:File cannot open';
			}
			$gistate = 0;
			$s = '';
			while(!feof($handler)&&$line = fgets($handler,4096)){
				if(mb_strpos($line,'<div id="ComboInfo"></div>')!==false){
					break;
				}
				switch($gistate){
					case 0:
						$ptag = '<h1>';
						$ftag = '</h1>';
						$p = mb_strpos($line,$ptag);
						$fp = mb_strpos($line,$ftag);
						if($p!==false&&$fp!==false){
							$p += mb_strlen($ptag);
							$s .= 'hotelname::'.mb_substr($line,$p,$fp-$p).',,';
							$gistate = 1;
						}
						break;
					case 1:
						$ptag = 'onclick="HotelRoom.onNameClick(this)">';
						$p = mb_strpos($line,$ptag);
						if($p!==false){
							$gistate = 2;
						}
						break;
					case 2:
						$s .= 'roomname::'.$line.',,';
						$gistate = 3;
						break;
					case 3:
						$ptag = 'onclick="HotelRoom.onNameClick(this)">';
						$p = mb_strpos($line,$ptag); 
						if($p!==false){
							$gistate = 2;
						}else{
							$ptag = '<span class="room_type_name">';
							$ftag = '</span>';
							$p = mb_strpos($line,$ptag);
							$fp = mb_strpos($line,$ftag);
							if($p!==false&&$fp!==false){
								$p += mb_strlen($ptag);
								$mb = mb_substr($line,$p,$fp-$p);
								if($mb==strip_tags($mb)){
									$s .= 'remark::'.$mb.',,';
								}else{
									$s .= 'remark::'.strip_tags($mb).',,';
								}
								$gistate = 4;
							}
						}
						break;
					case 4:
						$ptag = '<span class="label_onsale_orange " data-role="jmp"';
						$ftag = '>';
						$fftag = '</span>';
						$p = mb_strpos($line, $ptag);
						$fp = mb_strpos($line, $ftag);
						$ffp = mb_strpos($line, $fftag);
						if($p!==false&&$fp!==false&&$ffp!==false){
							$s .= 'gift::'.mb_substr($line, $fp+1,$ffp-$fp-1).',,';
							$gistate = 4;
						}else{
							$ptag = '<td>';
							$ftag = '</td>';
							$p = mb_strpos($line,$ptag);
							$fp = mb_strpos($line,$ftag);
							if($p!==false&&$fp!==false){
								$p += mb_strlen($ptag);
								$s .= 'bed::'.mb_substr($line,$p,$fp-$p).',,';
								$gistate = 5;
							}
						}
						break;
					case 5:
						$ptag = '<td>';
						$ftag = '</td>';
						$p = mb_strpos($line,$ptag);
						$fp = mb_strpos($line,$ftag);
						if($p!==false&&$fp!==false){
							$p += mb_strlen($ptag);
							$s .= 'breakfast::'.mb_substr($line,$p,$fp-$p).',,';
							$gistate = 6;
						}
						break;
					// case 6:
					// 	$ptag = '<span class="base_price"><dfn>';
					// 	$ftag = '</dfn>';
					// 	$p = mb_strpos($line,$ptag);
					// 	$fp = mb_strpos($line,$ftag);
					// 	if($p!==false&&$fp!==false){
					// 		$p += mb_strlen($ptag);
					// 		$s .= 'price:'.mb_substr($line,$p,$fp-$p);
					// 		$fftag = '</span>';
					// 		$ffp = mb_strpos($line,$fftag);
					// 		if($ffp!==false){
					// 			$fp += mb_strlen($ftag);
					// 			$isprice = mb_substr($line,$fp,$ffp-$fp);
					// 			$s .= mb_substr($line,$fp,$ffp-$fp).';';
					// 			$gistate = 3;
					// 		}
					// 	}
					case 6:
						$ptag = '<del class="rt_origin_price"><dfn>';
						$ftag = '</dfn>';
						$p = mb_strpos($line,$ptag);
						$fp = mb_strpos($line, $ftag);
						if($p!==false&&$fp!==false){
							$fftag = '</del>';
							$pptag = '<span class="label_onsale_txt"><i>';
							$ffftag = '</span>';
							$ffp = mb_strpos($line, $fftag);
							$fffp = mb_strpos($line, $pptag);
							$fpr = mb_strrpos($line, $ftag);
							$ffffp = mb_strpos($line, $ffftag);
							if($ffp!==false&&$fffp!==false&&$fpr!==false&&$ffffp!==false){
								$flen = mb_strlen($ftag);
								$fp += $flen;
								$originprice = mb_substr($line, $fp,$ffp-$fp);
								$fpr += $flen;
								$saleprice = mb_substr($line, $fpr,$ffffp-$fpr);
								$isprice = $originprice-$saleprice;
								$s .= 'price::¥'.$isprice.';;';
								$gistate = 3;
							}else{
								$flen = mb_strlen($ftag);
								$fp += $flen;
								$isprice = mb_substr($line, $fp,$ffp-$fp);
								$s .= 'price::¥'.$isprice.';;';
								$gistate = 3;
							} 
						}else{
							// $ptag = '<a data-ismember="false" class="btn_buy" data-price="';
							$ptag = '<a data-ismember="false" class="btns_base22" data-price="';
							$ftag = '" rel="nofollow"';
							$p = mb_strpos($line,$ptag);
							$fp = mb_strpos($line,$ftag);
							if($p!==false&&$fp!==false){
								$p += mb_strlen($ptag);
								$s .= 'price::¥'.mb_substr($line,$p,$fp-$p).';;';
								$gistate = 3;
							}
						}
						break;
					default:
						break;
				}
			}
			fclose($handler);
			$m = explode(';;',htmlspecialchars_decode(rtrim($s,';;')));
			if(mb_strpos(end($m),'{')!==false||mb_strpos(end($m),'$')!==false||mb_strpos(end($m),'}')!==false){
				unset($m[count($m)-1]);
			}
			$res = array();
			$p = 0;
			$roomname = '';
			if(!empty($m)&&is_array($m)){
				foreach ($m as $key => $value) {
					$arr1 = explode(',,',$value);
					if(!empty($arr1)&&is_array($arr1)){
						foreach ($arr1 as $ke => $val) {
							$arr2 = explode('::',$val);
							if(!empty($arr2[0])&&!empty($arr2[1])){
								if($arr2[0]=='roomname'){
									$roomname = $arr2[1];
								}
								if(mb_strpos($value,'remark')!==false&&mb_strpos($value,'套票')!==false){
									continue;
								}else{
									if($arr2[0]=='hotelname'){
										$res['hotelname'] = $arr2[1];
									}else{
										$res['room'][$p]['roomname'] = $roomname;
										$res['room'][$p][$arr2[0]] = $arr2[1]; 
									}
								}
							}
						}
					}
					$p++;
				}
			}
			return $res;
		}else{
			if(!is_dir($this->htmldir1)){
				return 'ERROR:File do not exist:'.$this->htmldir.$htmlfile;
			}
			return 'ERROR:File do not exist:'.$this->htmldir1.$htmlfile;
		}
	}

	/*
	 * 刷选酒店同一房型中的最低价格
	 * @param1 Array $rooms 酒店所有房型数据
	 * return Array $brushrooms 刷选后的酒店房型数据
	 */
	private function brushRooms($rooms){
		if(is_array($rooms)){
			$brushrooms = trimArray($rooms);
		}
		$prices = array();
		foreach ($brushrooms as $k => $v) {
			if($k=='room'){
				foreach ($v as $key => $value) {
					$value['nkey'] = $key;
					$this->ctriprooms[] = $value;
					if(mb_strpos($value['bed'],'/')!==false){
						$biganddouble = explode('/',$value['bed']);
						$big = $biganddouble[0].'床';
						$double = $biganddouble[1].'床';
						$prices[] = array('roomname'=>$value['roomname'],'bed'=>$big,'price'=>ltrim($value['price'],'¥'),'breakfast'=>$value['breakfast'],'nkey'=>$value['nkey'],'remark'=>isset($value['remark'])?$value['remark']:'');
						$prices[] = array('roomname'=>$value['roomname'],'bed'=>$double,'price'=>ltrim($value['price'],'¥'),'breakfast'=>$value['breakfast'],'nkey'=>$value['nkey'],'remark'=>isset($value['remark'])?$value['remark']:'');
					}else{
						$prices[] = array('roomname'=>$value['roomname'],'bed'=>$value['bed'],'price'=>ltrim($value['price'],'¥'),'breakfast'=>$value['breakfast'],'nkey'=>$value['nkey'],'remark'=>isset($value['remark'])?$value['remark']:'');
					}
				}
			}
		}
		$mins = array();
		foreach ($prices as $ke => $val) {
			$mins[$val['roomname'].$val['bed'].$val['breakfast']][$val['nkey']] = $val['price'];
			$mins[$val['roomname'].$val['bed'].$val['breakfast']]['room_name'] = $val['roomname'];
			if(mb_strpos($val['remark'],'钟点')!==false){
				if(isset($mins[$val['roomname'].$val['bed'].$val['breakfast']]['zd_price'])){
					if($mins[$val['roomname'].$val['bed'].$val['breakfast']]['zd_price']['price']>$val['price']){
						$mins[$val['roomname'].$val['bed'].$val['breakfast']]['zd_price'] = array('price'=>$val['price'],'nkey'=>$val['nkey']);
					}
				}else{
					$mins[$val['roomname'].$val['bed'].$val['breakfast']]['zd_price'] = array('price'=>$val['price'],'nkey'=>$val['nkey']);
				}
			}
		}
		$minarr = array();
		foreach ($mins as $mk => &$mv) {
			$room_name = $mv['room_name'];
			unset($mins[$mk]['room_name']);
			if(isset($mv['zd_price'])){
				$minarr[] = array('roomname'=>$mk,'price'=>$mv['zd_price']['price'],'room_name'=>$room_name,'nkey'=>$mv['zd_price']['nkey']);
				unset($mins[$mk][$mv['zd_price']['nkey']]);
				unset($mins[$mk]['zd_price']);
			}
			if(!empty($mv)){
				asort($mv);
				$minvalue = reset($mv);
				$minkey = key($mv);
				$minarr[] = array('roomname'=>$mk,'price'=>$minvalue,'room_name'=>$room_name,'nkey'=>$minkey);
			}
		}
		return $minarr;
	}

	/*
	 * 比较携程网房型和iwide房型是否是同一种
	 * @param1 携程网房型名字 
	 * @param2 iwide房型名字
	 * return boolean true相同，false不同
	 */
	private function analysisRoom($ctripname,$iwidename){
		if($ctripname&&$iwidename){
			$cp = $this->splitRoomName(trim($ctripname));
			$iwp = $this->splitRoomName(trim($iwidename));
			$status = false;
			$roomkeys = array_keys($this->roomkeys);
			foreach ($roomkeys as $rval) {
				if(isset($cp[$rval])&&isset($iwp[$rval])){
					$cp_int = array_intersect($cp[$rval], $this->prior);
					$iwp_int = array_intersect($iwp[$rval], $this->prior);
				}
				if(!empty($cp_int)&&!empty($iwp_int)){
					return true;
				}
			}
			if($this->currcname==$this->curriname){
				foreach ($this->ignore as $vi) {
					if(mb_strpos($this->currcname,$vi)!==false){
						return true;
					}
				}
				$status = true;
			}elseif(mb_strpos($this->curriname,$this->currcname)!==false){
				$status = true;
			}else{
				//如果两边有一边除房型名以外，没有其他属性或两边都没有，则判断若一边包含一边则直接匹配，否则继续往下走
				if((!isset($cp['refirst'])&&!isset($cp['second'])&&!isset($cp['third'])&&!isset($cp['fourth']))||(!isset($iwp['refirst'])&&!isset($iwp['second'])&&!isset($iwp['third'])&&!isset($iwp['fourth']))){
					//如果公众号房型名没有“房”字而携程有且两边都有字母，则补上.“间”与“房”视为相同
					preg_match_all("/[a-zA-Z]{1}/",$this->currcname,$arrc);
					$cnum = count($arrc[0]);
					preg_match_all("/[a-zA-Z]{1}/",$this->curriname,$arri);
					$inum = count($arri[0]);
					$cletter = mb_substr($this->currcname,mb_strlen($this->currcname)-$cnum,$cnum);
					$iletter = mb_substr($this->curriname,mb_strlen($this->curriname)-$cnum,$inum);
					if(preg_match ("/^[A-Za-z]/",$cletter)&&preg_match ("/^[A-Za-z]/",$iletter)){ 
						if(mb_strpos($this->currcname, '间')!==false&&mb_strpos($this->curriname, '房')!==false&&mb_strpos($this->curriname, '间')===false){
							$this->curriname = mb_substr($this->curriname,0,mb_strlen($this->curriname)-$inum-1).'间'.$iletter;
						}elseif(mb_strpos($this->currcname, '房')!==false&&mb_strpos($this->curriname, '间')!==false&&mb_strpos($this->curriname, '房')===false){
							$this->curriname = mb_substr($this->curriname,0,mb_strlen($this->curriname)-$inum-1).'房'.$iletter;
						}elseif(mb_strpos($this->currcname, '房')!==false&&mb_strpos($this->curriname, '房')===false){
							$this->curriname = mb_substr($this->curriname,0,mb_strlen($this->curriname)-$inum).'房'.$iletter;
						}elseif(mb_strpos($this->currcname, '间')!==false&&mb_strpos($this->curriname, '间')===false){
							$this->curriname = mb_substr($this->curriname,0,mb_strlen($this->curriname)-$inum).'间'.$iletter;
						}
					}
					if((mb_strpos($this->curriname,$this->currcname)!==false)||(mb_strpos($this->currcname,$this->curriname)!==false)){
						return true;
					}
				}			
				if(isset($cp['first'])&&isset($iwp['first'])&&isset($cp['refirst'])&&isset($iwp['refirst'])){
					$cp1 = implode('', $cp['first']);
					$iwp1 = implode('', $iwp['first']);
					$recp1 = implode('', $cp['refirst']);
					$reiwp1 = implode('', $iwp['refirst']);
					similar_text($cp1.$recp1, $iwp1.$reiwp1,$per1);
					similar_text($cp1, $iwp1,$per2);
					similar_text($recp1, $reiwp1,$per3);
					if($per1==100){
						$status = true;
					}elseif($per2==100&&$per3>=50){
						$status = true;
					}
				}elseif(isset($cp['first'])&&isset($iwp['first'])){
					$cp1 = implode('', $cp['first']);
					$iwp1 = implode('', $iwp['first']);
					if(in_array($cp1, array_keys($this->special))){
						$wd = $this->special[$cp1];
						if($wd==$iwp1){
							$status = true;
						}
					}elseif (in_array($iwp1, array_keys($this->special))) {
						$wd = $this->special[$iwp1];
						if($wd==$cp1){
							$status = true;
						}
					}else{	
						similar_text($cp1, $iwp1,$per2);
						if($per2==100){
							$status = true;
							if((isset($cp['refirst'])&&array_intersect($cp['refirst'],$this->norefirst))||(isset($iwp['refirst'])&&array_intersect($iwp['refirst'],$this->norefirst))){
								$status = false;
							}
						} 
					}						
				}elseif(isset($cp['refirst'])&&isset($iwp['refirst'])){
					$recp1 = implode('', $cp['refirst']);
					$reiwp1 = implode('', $iwp['refirst']);
					similar_text($recp1, $reiwp1,$per3);
					preg_match_all("/[a-zA-Z]{1}/",$this->currcname,$arrc);
					$cnum = count($arrc[0]);
					preg_match_all("/[a-zA-Z]{1}/",$this->curriname,$arri);
					$inum = count($arri[0]);
					$cletter = mb_substr($this->currcname,mb_strlen($this->currcname)-$cnum,$cnum);
					$iletter = mb_substr($this->curriname,mb_strlen($this->curriname)-$cnum,$inum);
					if(preg_match ("/^[A-Za-z]/",$cletter)&&preg_match ("/^[A-Za-z]/",$iletter)){
						if($per3==100&&$cletter==$iletter){
							$status = true;
							if(isset($cp['first'])||isset($iwp['first'])){
								$status = false;
							}
						}
					}else{
						if($per3==100){
							$status = true;
							if(isset($cp['first'])||isset($iwp['first'])){
								$status = false;
							}
						}
					}
				}elseif(!isset($cp['first'])&&!isset($iwp['first'])&&!isset($cp['refirst'])&&!isset($iwp['refirst'])){
					$status = true;
				}else{
					$status = false;
				}
			}
			//前面匹配为true，判断如果一边带“标间”且没有床型属性,而另一边又不带“双床”，则到此步骤为不匹配
			if($status){
				if(isset($cp['second'])&&!isset($iwp['second'])&&!in_array('双床',$cp['second'])&&mb_strpos($iwidename,'标间')!==false){
					$status = false;
				}elseif(isset($iwp['second'])&&!isset($cp['second'])&&!in_array('双床',$iwp['second'])&&mb_strpos($ctripname,'标间')!==false){
					$status = false;
				}
			}
			//两边房型名带有“标准”或“标间”则到此步骤为匹配
			if(!$status){
				if(isset($cp['first'])&&isset($iwp['first'])){
					$imcp = implode('', $cp['first']);
					$imiwp = implode('', $iwp['first']);
					similar_text($imcp, $imiwp,$imper);
					if($imper==100){
						if((mb_strpos($ctripname, '标准')!==false&&mb_strpos($iwidename, '标间')!==false)||(mb_strpos($iwidename, '标准')!==false&&mb_strpos($ctripname, '标间')!==false)){
							$status = true;
						}
					}
				}elseif(!isset($cp['first'])&&!isset($iwp['first'])){
					if((mb_strpos($ctripname, '标准')!==false&&mb_strpos($iwidename, '标间')!==false)||(mb_strpos($iwidename, '标准')!==false&&mb_strpos($ctripname, '标间')!==false)){
						$status = true;
					}
					if(mb_strpos($iwidename, '标准')!==false&&(mb_strpos($ctripname, '双床')!==false||mb_strpos($ctripname, '双人')!==false)){
						$status = true;
					}
				}
			}
			if(isset($cp['second'])&&isset($iwp['second'])){
				if($status){
					foreach ($cp['second'] as $cpf) {
						foreach ($iwp['second'] as $ipf) {
							if($cpf==$ipf){
								$status = true;
							}elseif(($cpf=='大床'||$cpf=='单床')&&($ipf=='大床'||$ipf=='单床')){
								$status = true;
							}else{
								$status = false;
								break;
							}
						}
						if($status){
							break;
						}
					}
				}
			}else{
				$status = $status?true:false;
			}
			if(isset($cp['third'])&&isset($iwp['third'])){
				if($status){
					foreach ($cp['third'] as $cpf) {
						foreach ($iwp['third'] as $ipf) {
							if($cpf==$ipf){
								$status = true;
							}else{
								$status = false;
								break;
							}
						}
						if(!$status){
							break;
						}
					}
				}
			}else{
				$status = $status?true:false;
			}
			if(isset($cp['fourth'])&&isset($iwp['fourth'])){
				if($status){
					if($cp['fourth'][0] == $iwp['fourth'][0]){
						$status = true;
					}else{
						$status = false;
					}
				}
			}elseif(!isset($cp['fourth'])&&!isset($iwp['fourth'])&&$status){
				$status = true;
			}else{
				$status = false;
			}
		}
		return $status?$status:false;
	}

	/*
	 * 房型分词
	 * @param1 $roomname 房型名称
	 * return Array 分词结果 
	 */
	private function splitRoomName($roomname){
		$item = array();
		foreach ($this->roomkeys as $k=>$val) {
			foreach ($val as $rk=>$rkeys) {
				if($k=='second'){
					$key1 = mb_substr($rkeys,0,1);
					if(($pos = mb_strpos($roomname,'('.$key1.')'))!==false||($pos = mb_strpos($roomname,'（'.$key1.'）'))!==false){
						$roomname = mb_substr($roomname,0,$pos).$key1.'床'.mb_substr($roomname,$pos+mb_strlen('('.$key1.')'));
					}
					if(($pos = mb_strpos($roomname,$key1.'人'))!==false&&mb_strpos($roomname, $key1.'床')===false){
						$roomname = mb_substr($roomname, 0,$pos).$key1.'床'.mb_substr($roomname,$pos+mb_strlen($key1.'人'));
					}
				}
				if($k=='third'){
					$key2 = $rk.'早';
					if(($pos = mb_strpos($roomname,$key2))!==false&&mb_strpos($roomname,$rkeys)===false){
						$roomname = mb_substr($roomname,0,$pos).$rkeys.mb_substr($roomname,$pos+mb_strlen($key2));
					}
				}
				if(mb_strpos($roomname,$rkeys)!==false){
					$item[$k][] = $rkeys;
				}
			}
		}
		return $item;
	}

	public function getRoomStateTest(){
		echo '<pre>';
		$this->load->model ( 'price/Order_api_model' );
		$iwiderooms = $this->Order_api_model->get_roomstate($this->input->get('inter_id'),$this->input->get('hotel_id'));
		var_dump($iwiderooms);
		// $ctriprooms = $this->getCtriprooms($this->input->get('ctrip_id').'.html');
		// $rooms = $this->brushrooms($ctriprooms);
		// var_dump($rooms);
		// $status = $this->analysisRoom('商务高级房双床双早','商务双床房双早');
		// var_dump($status);
	}

	public function getCtripOriginalTest(){
		echo '<pre>';
		$ctriprooms = $this->getCtriprooms($this->input->get('ctrip_id').'.html');
		// $rooms = $this->brushrooms($ctriprooms);
		// $croominfo = $this->getCtripOriginal($this->input->get('cname'));
		var_dump($rooms);
	}

	//链接列表
	public function aList(){
		foreach ($this->verifcode as $key => $value) {
			echo '<a target="_blank" href="'.site_url('price/room/'.__CLASS__.'/showList').'?verifcode='.$value.'">'.$this->hotelgroupname[$key].'</a><br/>';
		}
	}
}

	/*
	 * 去除数组所有元素两边的空格
	 * @param1 Array $input 要处理的数组
	 * return Array 返回处理过的数组
	 */
	function trimArray($input){
		if(!is_array($input)){
			return trim($input);
		}
		return array_map('trimArray',$input);
	}