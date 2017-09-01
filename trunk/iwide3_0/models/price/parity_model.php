<?php
class Parity_model extends MY_Model{
	
	const TAB_PCH = 'price_third_hotels';//酒店匹配表
	const TAB_PWR = 'price_weixin_rooms';//微信房型信息表
	const TAB_PRP = 'price_room_parity';//房型匹配表
	const TAB_PTR = 'price_third_rooms';//第三方酒店房型信息表
	const HTMLDIR = '/data/parity/';//抓取文件存放位置
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
	private $ctriprooms = array();
	private $norefirst = array();
	private $currcname = '';
	private $curriname = '';
	function __construct() {
		parent::__construct ();
		$this->load->library('MYLOG');
	}

	// 获取某个酒店的房型匹配结果 $type 第三方类型1.携程，2.美团，3.阿里，4.去哪儿
	function get_hotel_contrast($inter_id,$hotel_id,$type){
		$maxb_pwr = "(SELECT * FROM ".$this->db->dbprefix(self::TAB_PWR)." WHERE inter_id='$inter_id' AND hotel_id='$hotel_id' AND batch=(SELECT a.batch FROM (SELECT MAX(batch) batch FROM ".$this->db->dbprefix(self::TAB_PWR)." WHERE inter_id='$inter_id' AND hotel_id='$hotel_id') a))";
		$maxb_ptr = "(SELECT * FROM ".$this->db->dbprefix(self::TAB_PTR)." WHERE batch=(SELECT b.batch FROM (SELECT MAX(batch) batch FROM ".$this->db->dbprefix(self::TAB_PTR)." WHERE third_type='$type') b))";
		$fields_pwr = 'b.hotel_name,b.room_name,b.price_name,b.total_price,b.book_status';
		$fields_ptr = 'c.hotel_name t_hotel_name,c.room_name t_room_name,c.breakfast,c.bed,c.price,c.remark,c.gift';
		$sql = "SELECT a.*,$fields_pwr,$fields_ptr FROM ".$this->db->dbprefix(self::TAB_PRP)." a LEFT JOIN $maxb_pwr b ON a.inter_id=b.inter_id AND a.hotel_id=b.hotel_id LEFT JOIN $maxb_ptr c ON a.third_id=c.third.id AND a.third_room_id=c.id WHERE a.inter_id='$third_id' AND a.hotel_id='$hotel_id' AND a.third_type='$type'";
		//去房型匹配表找
		$rooms = $this->db->get(self::TAB_PRP)->result_array();
		if(!empty($rooms)){
			return $rooms;
		}
		$this->db->where(array(
			'inter_id'=>$inter_id,
			'hotel_id'=>$hotel_id,
			));
		//房型匹配表没有则去拿抓取到的文件匹配
		$thotel = $this->db->get(self::TAB_PCH)->row_array();
		if(!empty($thotel)){
			$roomarrs = $this->get_third_rooms($thotel['ctrip_id']);
			if(is_array($roomarrs)){
				$mins = $this->brushrooms($roomarrs);
				$nomins = $mins;
				$iwideroomsarr = $this->get_weixin_rooms($inter_id,$hotel_id);
				$noiwiderooms = $iwideroomsarr;
				$repeatroom = array();//记录已经出现过的两边完全相同的房型名
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
				$output = $this->first_match_process($mins,$iwideroomsarr);
				$renoiwiderooms = $noiwiderooms;
			}else{
				MYLOG::w($roomarrs,'price');
			}
		}
		return false;
	}

	/*
	 * 房型初步匹配过程
	 * param1 Array $mins,Array $iwideroomsarr
	 * return Array $output 初步匹配结果
	 */
	private function first_match_process($mins,$iwideroomsarr){
		$output = array();
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
		return $output;
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
		MYLOG::w('获取携程初始房型属性失败，roomname:'.$roomname,'price');
	}

	/*
	 * 获取微信房型信息
	 * param1 $inter_id param2 $hotel_id 
	 * return Array 对应微信房型信息
	 */
	private function get_weixin_rooms($inter_id,$hotel_id){
		$outinfo = $this->db->query("SELECT * FROM ".$this->db->dbprefix(self::TAB_PWR)." WHERE inter_id='$inter_id' AND hotel_id='$hotel_id' AND batch=(SELECT MAX(batch) FROM ".$this->db->dbprefix(self::TAB_PWR)." WHERE inter_id='$inter_id' AND hotel_id='$hotel_id')");
		$iwiderooms = $outinfo->result_array();
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
		return $iwideroomsarr;
	}

	/*
	 * 截取房型内容
	 * param1 $third_id 第三方酒店内部id
	 * return Array 对应携程酒店房型属性
	 */
	private function get_third_rooms($third_id){
		$third_id?$htmlfile = $third_id.'.html':return false;
		if(is_file(self::HTMLDIR.$htmlfile)){
			$handler = @fopen(self::HTMLDIR.$htmlfile, 'r');
			if(!$handler){
				return 'ERROR:'.$$htmlfile.' File cannot open';
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
			return 'ERROR:'.$htmlfileFile.' do not exist:';
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