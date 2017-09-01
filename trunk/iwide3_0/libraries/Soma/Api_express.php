<?php
class Api_express extends Soma_base
{
    //const REDIS_DB = 2;
    const REDIS_BASE = 'Soma_Express';
    
    private $appkey = '4572d44964a631a8a180157c14193e15'; //聚合数据申请的快递查询APPKEY
    private $queryUrl = 'http://v.juhe.cn/exp/index';  //查询快递信息
    private $comUrl = 'http://v.juhe.cn/exp/com';   //查询快递公司
    //文档地址：https://www.juhe.cn/docs/api/id/43
    
    public $redis= '';

    public $expressCom = array(
        'EMS'   => 'EMS中国邮政',
        'sf'    => '顺丰速递',
        'sto'   => '申通快递',
        'yt'    => '圆通快递',
        'yd'    => '韵达快递',
        'tt'    => '天天快递',
        'ht'    => '汇通快递',
        'zto'   => '中通快递',
        'qf'    => '全峰快递',
        'db'    => '德邦物流'
    );
    public $expressComMatch = array(
        'a_yzgn1'=> 'EMS',   //'邮政国内',
        'a_yzgj2'=> 'EMS',   //'邮政国际',
        'a_yzgn4'=> 'EMS',   //'中国邮政快递',
        'a_ems1'=> 'EMS',   //'EMS快递查询',
        'g_emsguoji'=> 'EMS',   //'EMS国际快递查询',
        'a_ems2'=> 'EMS',   //'邮政EMS速递',
        'a_sf'=> 'sf',   //'顺丰速递',
        'a_nsf'=> 'sf',   //'新顺丰（NSF）',
        'a_st'=> 'sto',   //'申通快递',
        'a_ewl'=> 'sto',   //'申通E物流',
        'a_yt'=> 'yt',   //'圆通快递',
        'a_yd'=> 'yd',   //'韵达快递',
        'a_tt1'=> 'tt',   //'天天快递',
        'a_zt'=> 'zto',   //'中通快递',
        'a_qfkd'=> 'qf',   //'全峰快递',
        'a_dbwl'=> 'db',   //'德邦物流'
        'ht'=> 'ht',   //'汇通快递',
    );

    public function __construct($inter_id=NULL)
    {

    }
    
    /**
     * Gets the redis instance.
     *
     * @param      string      $select  The select
     *
     * @return     Redis|null  The redis instance.
     */
    public function get_redis_instance($select = 'soma_redis') {
        $CI = & get_instance();
        $CI->load->library('Redis_selector');
        if($redis = $CI->redis_selector->get_soma_redis($select)) {
            return $redis;
        }
        return null;
    }

    /**
     * 初始化redis实例
     * @return Statis_sales_model
     */
    public function init_service()
    {
        // $cache= $this->_load_cache();
        // //$cache->redis->select_db(self::REDIS_DB);  //由redis.php 配置文件自动识别哪个库
        // $this->redis= $cache->redis->redis_instance();
        $this->redis = $this->get_redis_instance();
        return $this;
    }

    /**
     * 加载缓存组件
     * @see MY_Controller::_load_cache()
     */
    protected function _load_cache( $name='Cache' )
    {
        if(!$name || $name=='cache') //不能为小写cache
        $name='Cache';
        $CI = & get_instance();
        $CI->load->driver('cache',
            array('adapter' => 'redis', 'backup' => 'file', 'key_prefix' => 'soma_'),
            $name
        );
        return $CI->$name;
    }

    /**
     * 返回支持的快递公司公司列表
     * @return array
     */
    public function getComs()
    {
        $params = 'key='. $this->appkey;
        $content = $this->juhecurl($this->comUrl,$params);
        return $this->_returnArray($content);
    }

    /**
     * 快递跟踪查询
     * @param String $com
     * @param String $no
     * @return Ambigous <multitype:, mixed>
     */
    public function query($com,$no)
    {
        $params = array(
            'key' => $this->appkey,
            'com' => $com,
            'no' => $no
        );
        $content = $this->juhecurl($this->queryUrl,$params,1);
        return $this->_returnArray($content);
    }

    /**
     * 将JSON内容转为数据，并返回
     * @param string $content [内容]
     * @return array
     */
    public function _returnArray($content){
        return json_decode($content,true);
    }

    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    public function juhecurl($url,$params=false,$ispost=0)
    {
        $httpInfo = array();
        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        if( $ispost ) {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
            
        } else {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }

    /**
     * @param $com 快递公司编码
     * @param $expressNo 快递编号
     * @return array
     */
    public function get_express_info($com, $expressNo)
    {
        //快递公司标识转换
        if( $com && array_key_exists($com, $this->expressComMatch ) ){
            $com= $this->expressComMatch[$com];
        }
        
        if( !array_key_exists($com, $this->expressCom) ){
            return array(
                'state'   =>  Soma_base::STATUS_TRUE,
                'content' => "不支持该物流商的物流追踪信息"
            );
        }

        header('Content-type:text/html;charset=utf-8');
        $params = array(
            'key' => $this->appkey, //您申请的快递appkey
            'com' => $com,     //快递公司编码，可以通过$exp->getComs()获取支持的公司列表
            'no'  => $expressNo //快递编号
        );

        $result = $this->query($params['com'],$params['no']); //执行查询

        if($result['error_code'] == 0){//查询成功
            return array(
                'state'   =>  Soma_base::STATUS_TRUE,
                'content'  =>    $result
            );
        }else{
            return array(
                'state'   =>  Soma_base::STATUS_FALSE,
                'content'   => "获取失败，原因：".$result['reason']
            ) ;
        }
    }


    public function get_express_from_redis($com,$expressNo,$inter_id , $expTime = 7200)
    {
        $redisH =  self::REDIS_BASE.":".$inter_id.":";
        $redisKey = $com."|".$expressNo;

        $this->init_service();
        $redis= $this->redis;

        $expressResultArr = $redis->get($redisH.$redisKey);

        if($expressResultArr){
            return json_decode($expressResultArr,true);
            
        }else{
            $expressInfo = $this->get_express_info($com,$expressNo);
            $expressResultArr = $this->formatReturnData($expressInfo);

            if(!$expressResultArr){
                return false;
            }
            if($expressResultArr['status'] == 1){
                $redis->set($redisH.$redisKey,json_encode($expressResultArr));
            }else{
                $redis->setex($redisH.$redisKey,$expTime,json_encode($expressResultArr)); // x will disappear in $expTime seconds.
            }
            return $expressResultArr;
        }
    }


    //聚合数据格式化返回
    private function formatReturnData($resultArr)
    {
        //返回数据有误
        if(!isset($resultArr['content'])
            || !isset($resultArr['content']['result'])
            ||  !isset($resultArr['content']['resultcode'])
            || $resultArr['content']['resultcode'] != 200)
            return false;
        if( !isset($resultArr['content']['result']) || !is_array($resultArr['content']['result'])){
            return false;
        }else{
            return $resultArr['content']['result'];
        }
    }


}