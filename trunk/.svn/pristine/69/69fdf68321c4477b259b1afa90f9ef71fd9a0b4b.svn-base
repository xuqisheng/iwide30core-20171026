<?php
defined('BASEPATH') OR exit('No direct script access allowed');


function string_format_fee_to_money($fee){
	return "￥".number_format(floatval($fee/100),2);
}

function string_format_pay_status($pay_status){
	$paystatus = array(0 => '不可用', 1 => '待支付',3=>'支付成功',4=>'已退款');
	return $paystatus[$pay_status];
}
function string_format_pay_ways($pay_ways){
    $payways = array(1 => '微信',2=>'余额');
    return $payways[$pay_ways];
}

if ( ! function_exists('convert_to_gbk'))
{
    /**
     * 字符集转换为GBK，用于excel导出等
     * @param String $str1
     * @param String $str2
     * @return boolean
     */

    function convert_to_gbk($str, $encoding='UTF-8' )
    {
        if (MB_ENABLED) {
            return mb_convert_encoding($str, 'GBK', $encoding);

        } elseif (ICONV_ENABLED) {
            return @iconv($encoding, 'GBK', $str);
        }
        return FALSE;
    }
}

if ( ! function_exists('two_string_match'))
{
    /**
     * 判断2个字符串是否为对方的子字符串
     * @param String $str1
     * @param String $str2
     * @return boolean
     */
    function two_string_match( $str1, $str2)
    {
        if( !$str1 || !$str2) return FALSE;
    	$str1= str_replace(array('/'), array(''), $str1);
    	$str2= str_replace(array('/'), array(''), $str2);
        if( strstr($str1, $str2) ) {
            return TRUE;
        }
        if ( strstr($str2, $str1) ) {
            
            return TRUE;
        }
        return FALSE;
        
    }
}

if ( ! function_exists('hide_string_prefix'))
{
    /**
     * 隐藏部分字符
     * @param String $string
     * @param number $hide_num
     * @return string
     */
    function hide_string_prefix($string, $hide_num=3)
    {
        if($string)
            return substr($string, 0, -$hide_num). str_repeat('*', $hide_num);
        else return '';
    }
}
if ( ! function_exists('show_price_prefix'))
{
    /**
     * 隐藏部分字符
     * @param String $string
     * @param number $hide_num
     * @return string
     */
    function show_price_prefix($string, $prefix='￥', $dotnum=2)
    {
        return $prefix. number_format($string, $dotnum);
    }
}
if ( ! function_exists('show_wxtemp_content'))
{
    /**
     * 显现模版消息内容
     * @param String $string
     * @param number $hide_num
     * @return string
     */
    function show_wxtemp_content($string)
    {
        $msg = json_decode( $string, TRUE );
        $data = isset( $msg['data'] ) ? $msg['data'] : array();
// var_dump( $data );exit;
        $content = '';
        foreach ($data as $k => $v) {
                if( $k == 'first' ){
                    // $content .= '头部内容：'.$v['value']."\r\n";
                }elseif( $k == 'remark' ){
                    // $content .= '尾部内容：'.$v['value']."\r\n";
                }else{
                    $content .= $v['value'].";&nbsp;&nbsp;&nbsp;";
                }
        }
        return $content;
    }
}
if ( ! function_exists('show_string_notice'))
{
    /**
     * 以注释形式显示字符
     * @param String $string
     * @param number $hide_num
     * @return string
     */
    function show_string_notice($string, $notice )
    {
        return "<abbr title='{$notice}'>{$string}</abbr>";
    }
}
if ( ! function_exists('show_good_stock'))
{
    /**
     * 显示库存数量
     */
    function show_good_stock($string, $warn_num=10 )
    {
        $num= intval($string);
        if($num>= $warn_num){
            return '<span style="color:green;">'. $num. ' (充足)</span>';
        } else {
            return '<span style="color:red;">'. $num. ' (紧缺)</span>';
        }
    }
}
if ( ! function_exists('show_status_color'))
{
    /**
     * 颜色显示状态标签
     */
    function show_status_color($string, $success='正常', $fail='禁用' )
    {
        if($string== $success){
            return '<span style="color:green;">'. $string. '</span>';
        } elseif($string== $fail){
            return '<span style="color:red;">'. $string. '</span>';
        } else {
            return '<span style="color:gray;">'. $string. '</span>';
        }
    }
}
if ( ! function_exists('show_status_toggle'))
{
    /**
     * 状态切换按钮
     * @param String $string
     * @param String $fa_icon
     * @param String $base_url 处理的基本url
     * @param String $on_value 代表开启的值
     * @return string
     */
    function show_status_toggle($string, $toggle_url=NULL, $on_value=1 )
    {
        if($string== $on_value){
            $l= '<i class="fa fa-toggle-on"></i> 关闭';
        } else {
            $l= '<i class="fa fa-toggle-off"></i> 开启';
        }
        return "<a href='{$toggle_url}'>{$l}</a>";
    }
}
if ( ! function_exists('get_first_py'))
{
	/**返回输入字符串的首字符拼音，utf8
	 * @param string $s
	 * @return string|NULL
	 */
	function get_first_py($s){
		require_once(APPPATH . 'libraries/Str_Py.php');
		$firstchar_ord=ord(strtoupper($s{0}));
		if (($firstchar_ord>=65 and $firstchar_ord<=91)or($firstchar_ord>=48 and $firstchar_ord<=57)) return $s{0};
		$back=$s;
		$check_spec=Str_Py::check_special_py($back);
		if (isset($check_spec)){
			return $check_spec;
		}
		$s=iconv("UTF-8","gb2312", $s);
		$asc=ord($s{0})*256+ord($s{1})-65536;
		if($asc>=-20319 and $asc<=-20284)return "A";
		if($asc>=-20283 and $asc<=-19776)return "B";
		if($asc>=-19775 and $asc<=-19219)return "C";
		if($asc>=-19218 and $asc<=-18711)return "D";
		if($asc>=-18710 and $asc<=-18527)return "E";
		if($asc>=-18526 and $asc<=-18240)return "F";
		if($asc>=-18239 and $asc<=-17923)return "G";
		if($asc>=-17922 and $asc<=-17418)return "H";
		if($asc>=-17417 and $asc<=-16475)return "J";
		if($asc>=-16474 and $asc<=-16213)return "K";
		if($asc>=-16212 and $asc<=-15641)return "L";
		if($asc>=-15640 and $asc<=-15166)return "M";
		if($asc>=-15165 and $asc<=-14923)return "N";
		if($asc>=-14922 and $asc<=-14915)return "O";
		if($asc>=-14914 and $asc<=-14631)return "P";
		if($asc>=-14630 and $asc<=-14150)return "Q";
		if($asc>=-14149 and $asc<=-14091)return "R";
		if($asc>=-14090 and $asc<=-13319)return "S";
		if($asc>=-13318 and $asc<=-12839)return "T";
		if($asc>=-12838 and $asc<=-12557)return "W";
		if($asc>=-12556 and $asc<=-11848)return "X";
		if($asc>=-11847 and $asc<=-11056)return "Y";
		if($asc>=-11055 and $asc<=-10247)return "Z";
		if($s != ''){
			$s = mb_substr($back, 0, 1, 'UTF-8');
			return Str_Py::getPy($s);
		}
		return null;
	}
}
if ( ! function_exists('trim_space'))
{
	/**删除字符串空格
	 * @param string $s
	 * @return string
	 */
	function trim_space($s){ 
		$find=array(" ","　","\t","\n","\r");
		return str_replace($find, array('','','','',''), $s);
	}
}
if ( ! function_exists('get_url_domain'))
{
	/**获取链接域名
	 * @param string $url
	 * @return string
	 */
	function get_url_domain($url){
		$url = str_replace('http://','',$url);
		$pos = strpos($url,'/');
		if($pos === false){
		 	return $url;
		}else { 
			return substr($url, 0, $pos); 
		} 
	}
}
if ( ! function_exists('cubstr'))
{
    /**截取中英文混合的字符串
     * @param unknown $string
     * @param unknown $beginIndex
     * @param unknown $length
     * @return string
     */
    function cubstr($string, $beginIndex, $length){
        if(strlen($string) < $length){
            return substr($string, $beginIndex);
        }
         
        $char = ord($string[$beginIndex + $length - 1]);
        if($char >= 224 && $char <= 239){
            $str = substr($string, $beginIndex, $length - 1);
            return $str;
        }
         
        $char = ord($string[$beginIndex + $length - 2]);
        if($char >= 224 && $char <= 239){
            $str = substr($string, $beginIndex, $length - 2);
            return $str;
        }
         
        return substr($string, $beginIndex, $length);
    }
}
if ( ! function_exists('htmlblank_replace'))
{
    /**将html &nbsp、br替换
     * @param unknown $string
     */
    function htmlblank_replace($string){
        $brs = array(
                "<br>", "<Br>", "<br/>", "<Br/>", "<br />", "<Br />", "</br>", "</Br>",
                "&lt;br&gt;", "&lt;Br&gt;", "&lt;br/&gt;", "&lt;Br/&gt;", "&lt;br /&gt;", "&lt;Br /&gt;",
                "&#60;br&#62;", "&#60;Br&#62;", "&#60;br/&#62;", "&#60;Br/&#62;", "&#60;br /&#62;", "&#60;Br /&#62;",
        );
        $nbsps = array("&nbsp;","&nbsp");
        $string = str_replace($brs, "\n", $string);
        $string = str_replace($nbsps, ' ', $string);
        return $string;
    }
}
if ( ! function_exists('dismantle_manname'))
{
    /**将人名拆分为姓与名
     * 1,有'·',' '，则前名后姓
     * 2,仅有一个字符，同姓同名
     * 3,仅有两个字符，前姓后名
     * 4,第一个字符不为字母，则截取前两个字符，为复姓则复姓为姓，余下为名，否则第一个字为姓，余下为名
     *   第一个字符为字母，截取首字母到后面第一个大写字母为名，无大写，则到a,e,i,o,u,y为名，余下为姓，再无，则首次字母为名
     * @param unknown $string
     */
    function dismantle_manname($name) {
        $names = array (
                'first' => $name,
                'last' => $name 
        );
        $arr = str2array ( $name );
        $str_len = count ( $arr );
        $first_text = $arr [0];
        if ($str_len == 2) {
            $names ['first'] = $arr [0];
            $names ['last'] = $arr [1];
        } else if ($str_len > 2) {
            if (preg_match ( '/[" "]/', $name, $surname_check, NULL, 1 )) {
                $split_check = strpos ( $name, $surname_check [0] );
                $split_len = 1;
            }
            $split_cn = strpos ( $name, '·' );
            if ($split_cn && (empty ( $split_check ) || $split_check > $split_cn)) {
                $split_check = $split_cn;
                $split_len = 2;
            }
            if (! empty ( $split_check )) {
                $names ['first'] = substr ( $name, 0, $split_check );
                $names ['last'] = substr ( $name, $split_check + $split_len );
            } else {
                if (preg_match ( '/^[a-zA-Z]+$/', $first_text )) {
                    if (preg_match ( '/[A-Z]/', $name, $surname_check, NULL, 1 )) {
                        $target = array_search ( $surname_check [0], $arr );
                        $names ['first'] = substr ( $name, 0, $target );
                    } else if (preg_match ( '/[a,e,i,o,u,y,A,E,I,O,U,Y]/', $name, $surname_check, NULL, 1 )) {
                        $target = array_search ( $surname_check [0], $arr );
                        $names ['first'] = substr ( $name, 0, $target + 1 );
                    } else {
                        $names ['first'] = substr ( $name, 0, 2 );
                    }
                } else {
                    $pre_words = $arr [0] . $arr [1];
                    if (in_array ( $pre_words, get_chinese_multisurname () )) {
                        $names ['first'] = $pre_words;
                    } else {
                        $names ['first'] = $first_text;
                    }
                }
                $names ['last'] = substr ( $name, strlen ( $names ['first'] ) );
            }
        }
        if (! preg_match ( '/^[a-zA-Z]+$/', $first_text )) {
            $tmp = $names ['last'];
            $names ['last'] = $names ['first'];
            $names ['first'] = $tmp;
        }
        return $names;
    }
}
if ( ! function_exists('str2array'))
{
    /*字符串转数组（支持中文）
     * @param unknown $string
     */
    function str2array($string) {
        preg_match_all('/./u', $string,$arr);
        return $arr[0];
    }
}
if ( ! function_exists('get_chinese_multisurname'))
{
    /*
     * @param unknown $string
     */
    function get_chinese_multisurname() {
        return array('欧阳','太史','端木','上官','司马','东方','独孤','南宫','万俟','闻人','夏侯','诸葛','尉迟','公羊','赫连','澹台','皇甫',  
        '宗政','濮阳','公冶','太叔','申屠','公孙','慕容','仲孙','钟离','长孙','宇文','城池','司徒','鲜于','司空','汝嫣','闾丘','子车','亓官',  
        '司寇','巫马','公西','颛孙','壤驷','公良','漆雕','乐正','宰父','谷梁','拓跋','夹谷','轩辕','令狐','段干','百里','呼延','东郭','南门',  
        '羊舌','微生','公户','公玉','公仪','梁丘','公仲','公上','公门','公山','公坚','左丘','公伯','西门','公祖','第五','公乘','贯丘','公皙',  
        '南荣','东里','东宫','仲长','子书','子桑','即墨','达奚','褚师'); 
    }
}
if ( ! function_exists('sbc_dbc_tran'))
{
    /**全角半角互转
     * @param unknown $str
     * @return mixed
     */
    function sbc_dbc_tran($str,$type=1) {
        $dbc = Array(
                '０' , '１' , '２' , '３' , '４' ,
                '５' , '６' , '７' , '８' , '９' ,
                'Ａ' , 'Ｂ' , 'Ｃ' , 'Ｄ' , 'Ｅ' ,
                'Ｆ' , 'Ｇ' , 'Ｈ' , 'Ｉ' , 'Ｊ' ,
                'Ｋ' , 'Ｌ' , 'Ｍ' , 'Ｎ' , 'Ｏ' ,
                'Ｐ' , 'Ｑ' , 'Ｒ' , 'Ｓ' , 'Ｔ' ,
                'Ｕ' , 'Ｖ' , 'Ｗ' , 'Ｘ' , 'Ｙ' ,
                'Ｚ' , 'ａ' , 'ｂ' , 'ｃ' , 'ｄ' ,
                'ｅ' , 'ｆ' , 'ｇ' , 'ｈ' , 'ｉ' ,
                'ｊ' , 'ｋ' , 'ｌ' , 'ｍ' , 'ｎ' ,
                'ｏ' , 'ｐ' , 'ｑ' , 'ｒ' , 'ｓ' ,
                'ｔ' , 'ｕ' , 'ｖ' , 'ｗ' , 'ｘ' ,
                'ｙ' , 'ｚ' , '－' , '　' , '：' ,
                '．' , '，' , '／' , '％' , '＃' ,
                '！' , '＠' , '＆' , '（' , '）' ,
                '＜' , '＞' , '＂' , '＇' , '？' ,
                '［' , '］' , '｛' , '｝' , '＼' ,
                '｜' , '＋' , '＝' , '＿' , '＾' ,
                '￥' , '￣' , '｀','；'
                );
        $sbc = Array( // 半角
                '0', '1', '2', '3', '4',
                '5', '6', '7', '8', '9',
                'A', 'B', 'C', 'D', 'E',
                'F', 'G', 'H', 'I', 'J',
                'K', 'L', 'M', 'N', 'O',
                'P', 'Q', 'R', 'S', 'T',
                'U', 'V', 'W', 'X', 'Y',
                'Z', 'a', 'b', 'c', 'd',
                'e', 'f', 'g', 'h', 'i',
                'j', 'k', 'l', 'm', 'n',
                'o', 'p', 'q', 'r', 's',
                't', 'u', 'v', 'w', 'x',
                'y', 'z', '-', ' ', ':',
                '.', ',', '/', '%', '#',
                '!', '@', '&', '(', ')',
                '<', '>', '"', '\'','?',
                '[', ']', '{', '}', '\\',
                '|', '+', '=', '_', '^',
                '$', '~', '`',';'
                );
        return $type==1?str_replace($dbc, $sbc, $str):str_replace($sbc, $dbc, $str);
    }
}