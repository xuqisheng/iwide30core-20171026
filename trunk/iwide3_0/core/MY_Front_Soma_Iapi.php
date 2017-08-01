<?php
use App\libraries\Iapi\CommonLib;

/**
 * Class MY_Front_Soma_Iapi
 * @author renshuai  <renshuai@mofly.cn>
 *
 *
 * @property Shard_config_model $shardConfigModel
 */
class MY_Front_Soma_Iapi extends MY_Front_Iapi
{
    /**
     * 常用页面链接
     * @var array
     */
    public $link;

    /**
     * MY_Front_Soma_Iapi constructor.
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->current_inter_id = $this->inter_id;
        $this->link = array(
            'home' => site_url('soma/package/index')."?id=".$this->inter_id,
            'product_link' => site_url('soma/package/package_detail')."?id=".$this->inter_id.'&pid=',
            'order_link' => site_url('soma/order/my_order_list')."?id=".$this->inter_id,
            'center_link' => site_url("membervip/center")."?id=".$this->inter_id
        );

    }

    /**
     * @param $method
     * @param array $params
     * @return mixed
     * @author renshuai  <renshuai@jperation.cn>
     *
     */
    public function _remap($method, $params = array())
    {
        $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
        $method = "{$requestMethod}_{$method}";

        if (method_exists($this, $method))
        {
            //数据库链接
            $this->load->somaDatabase($this->db_soma);
            $this->load->somaDatabaseRead($this->db_soma_read);
            //初始化数据库分片配置
            $this->load->model('soma/shard_config_model', 'shardConfigModel');
            $this->db_shard_config = $this->shardConfigModel->build_shard_config($this->inter_id);

            return call_user_func_array(array($this, $method), $params);
        } else {
            show_404('api not found');
        }
    }

    /**
     * @param $result
     * @param $msg
     * @param $data
     * @param $fun
     * @param $extra
     * @param $msg_lv
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function json($result, $msg = '', $data = array(), $fun = '', $extra = array(), $msg_lv = 0)
    {
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode(CommonLib::create_put_msg ( 'jwx', $result, $msg, $data, $fun, $extra, $msg_lv )));
    }

    /**
     * Gets the redis instance.
     *
     * @param      string $select The select
     *
     * @return     Redis|null  The redis instance.
     */
    public function get_redis_instance($select = 'soma_redis')
    {
        $this->load->library('Redis_selector');
        if ($redis = $this->redis_selector->get_soma_redis($select)) {
            return $redis;
        }

        return null;
    }

}