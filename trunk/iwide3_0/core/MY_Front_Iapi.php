<?php
use App\libraries\Iapi\BaseConst;
use App\libraries\Iapi\CommonLib;

/**
 * Class MY_Front_Iapi
 *
 * @property Publics_model $Publics_model
 * @property CI_Output $output
 */
class MY_Front_Iapi extends MY_Controller {
    /**
     *
     * @var mixed|null
     */
    public $inter_id;
    /**
     *
     * @var mixed|null
     */
    public $openid;
    public $token;
    public $source;
    protected $module = '';
    protected $controller = '';
    protected $action = '';
    /**
     * 公众号信息
     */
    public $public;
    /**
     * 当前登录用户信息
     */
    public $user;
    /**
     * MY_Front_Iapi constructor.
     *
     */
    public function __construct()
    {
        parent::__construct ();
        $this->_init_router ();
        $this->inter_id = $this->session->userdata ( 'inter_id' );
        $this->openid = $this->session->userdata ( $this->inter_id . 'openid' );
        //方便调试
        if ((ENVIRONMENT === 'dev' || ENVIRONMENT === 'development') && (!$this->inter_id || !$this->openid)){
            $this->inter_id = $this->input->get_post('id');
            $this->openid = $this->input->get_post('openid');
        }
        $this->_base_input_valid ();
        // @Editor lGh 停服跳转
        $this->load->model ( 'wx/Publics_model' );
        $this->public = $this->Publics_model->get_public_by_id ( $this->inter_id );
        if (isset ( $this->public ['run_status'] ) && $this->public ['run_status'] == 'stop') {
            $this->out_put_msg ( 6 );
        }
        // $this->openid = 'oX3WojhfNUD4JzmlwTzuKba1MywY';
    }
    /**
     *
     */
    protected function _base_input_valid()
    {
        $this->source = json_decode ( file_get_contents ( 'php://input' ), TRUE );

        if (empty ( $this->inter_id ) || empty ( $this->openid )) {
            $this->out_put_msg ( BaseConst::OPER_STATUS_NOTLOGIN );
        }
    }
    
    /**
     * @param int $result 运行结果 具体值看Front_const
     * @param string $msg 显示给用户的信息
     * @param array $data 数据集
     * @param string $fun 调用的方法的标识 如hotel/hotel/search
     * @param array $extra 非主体数据，含元素:array(
     *                                          'links'=>array(
     *                                              'edit'=>'','add'=>''
     *                                          ),//操作跳转链接
     *                                          'page'=>'页码',
     *                                          'count'=>'总页数',
     *                                          'size'=>'每页数据量'
     *                                          )
     * @param number $msg_lv 消息级别  具体值看Front_const
     * @param string $exit 输出数据后是否退出整个程序
     */
    protected function out_put_msg($result, $msg = '', $data = array(), $fun = '', $extra = array(), $msg_lv = 0, $exit = true)
    {
        echo json_encode ( CommonLib::create_put_msg ( 'jwx', $result, $msg, $data, $fun, $extra, $msg_lv ), JSON_UNESCAPED_UNICODE );
        if ($exit) {
            exit ();
        }
    }
    /**返回source中数据
     * @param string $index 数据在数组中下标
     * @param string $filter 过滤函数 按需添加
     * @param string $in TRUE 在send_data中取,FALSE 在外层数据取
     * @return NULL|mixed
     */
    protected function get_source($index = '', $filter = '', $in = TRUE) {
        if ($index === '')
            return $this->source;
        if ($in)
            $data = isset ( $this->source ['send_data'] [$index] ) ? $this->source ['send_data'] [$index] : NULL;
        else
            $data = isset ( $this->source [$index] ) ? $this->source [$index] : NULL;
        if (isset ( $data ) && ! empty ( $filter )) {
            switch ($filter) {
                case 'int' :
                    $data = intval ( $data );
                    break;
                default :
                    break;
            }
        }
        return $data;
    }
    /**
     *
     * @author renshuai  <renshuai@jperation.cn>
     */
    protected function _init_router()
    {
        $segments = $this->uri->segments;
        $this->module = $segments [2];
        $this->controller = isset ( $segments [3] ) ? $segments [3] : 'index';
        $this->action = isset ( $segments [4] ) ? $segments [4] : 'index';

    }
    
    /**
     *
     * 判断当前action是否用get请求，部分功能如图片验证码，二维码等需要用到
     * 如果是使用get的action，则返回true,否侧返回false
     */
    protected function checkActionRequestUseGet() {
        
        // 以后移至配置文件里
        $_use_GET_action = array (
                "pic_code" 
        );
        
        if (in_array ( $this->action, $_use_GET_action )) {
            return true;
        } else {
            return false;
        }
    }
    protected function get_display_view($paras) {
        $paras = explode ( '/', $paras );
        $paras [2] = empty ( $paras [2] ) ? $paras [1] : $paras [2];
        $paras [2] = str_replace ( '.', '/', $paras [2] );
        $paras [3] = empty ( $paras [3] ) ? 0 : $paras [3];
        $sql = "SELECT s.skin_name,s.overall_style,a.* from " . $this->db->dbprefix ( 'view_skin_set' ) . " s
          	  left join " . $this->db->dbprefix ( 'view_disp_set' ) . " a
                 ON s.inter_id = a.inter_id and s.module=a.module AND a.`func` = '" . $paras [1] . "' AND a.`status` = 1
          	  		WHERE s.`inter_id` = '" . $this->inter_id . "' AND s.`status` = 1 AND s.`module` = '" . $paras [0] . "' AND s.`hotel_id` in (0, " . $paras [3] . ")
                 	 order by s.hotel_id desc limit 1";
        
        return $this->db->query ( $sql )->row_array ();
    }
}
require_once dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . "MY_Front_Hotel_Iapi.php";
require_once dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . "MY_Front_Soma_Iapi.php";
require_once dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . "MY_Front_Member_Iapi.php";
