<?php
use App\services\soma\ScopeDiscountService;
use App\models\soma\ScopeDiscount as ScopeDiscountModel;

/**
 * User: renshuai <renshuai@mofly.cn>
 * Date: 2017/5/15
 * Time: 16:57
 */
class Inner_api extends MY_Controller
{
    /**
     * Inner_api constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->somaDatabase($this->db_soma);
        $this->load->somaDatabaseRead($this->db_soma_read);

        //没有到这个model，不过index的方法的model在命名空间里，无法加载父类model所以只能随便找个父类load出来了。
        $this->load->model('soma/adv_model');
    }

    /**
     *
     * 返回公众号可用的价格配置
     *
     * @example request http://frontdomain/soma/inner_api/scope_list?id=a450089706&limit=1&page=1
     *
     * return json format
     * <code>
     * [
     *  {
     *      "id": "3",  //价格配置的ID
     *      "inter_id": "a450089706",
     *      "name": "3333", //价格配置的名称
     *      "scope": "1",
     *      "status": "1",
     *      "start_time": "2016-04-29 10:27:49",
     *      "end_time": "2018-04-29 10:27:49",
     *      "extra": null,
     *      "created_at": null,
     *      "updated_at": null
     *  }
     * ]
     * </code>
     *
     * @author renshuai  <renshuai@mofly.cn>
     * @return void
     */
    public function scope_list()
    {
        $inter_id = $this->input->get('id', true, null);
        $page = $this->input->get('page', true, 1);
        $limit = $this->input->get('limit', true, 10);
        if (empty($inter_id)) {
            show_404();
        }
        $result = ScopeDiscountService::getInstance()->getAvailableList($inter_id, ScopeDiscountModel::SCOPE_SOCIAL, $page, $limit);
        $this->output->set_content_type('Content-Type: application/json');
        $this->output->set_output(json_encode($result));
    }

}