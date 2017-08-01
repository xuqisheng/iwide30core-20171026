<?php
namespace App\controllers\iapi\admin\traits;

/**
 * Class Soma
 * @package App\controllers\iapi\admin\traits
 * @author renshuai  <renshuai@mofly.cn>
 *
 * @property \Shard_config_model $model_shard_config
 */
trait Soma{

    private $route;
    private $inter_id;

    public function __construct()
    {
        parent::__construct();

        $this->inter_id = $this->session->get_admin_inter_id ();
        $this->module = 'soma';
        $this->common_data ['csrf_token'] = $this->security->get_csrf_token_name ();
        $this->common_data ['csrf_value'] = $this->security->get_csrf_hash ();

        $this->load->somaDatabase($this->db_soma);
        $this->load->somaDatabaseRead($this->db_soma_read);

        $this->route = $this->module.'/'.$this->controller.'/'.$this->action;

        $this->load->model('soma/shard_config_model', 'model_shard_config');
        $this->db_shard_config = $this->model_shard_config->build_shard_config($this->inter_id);
    }

    public function get_redis_instance($select = 'soma_redis')
    {
        $this->load->library('Redis_selector');
        if ($redis = $this->redis_selector->get_soma_redis($select)) {
            return $redis;
        }

        return null;
    }


}
