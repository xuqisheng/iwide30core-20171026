<?php

use App\controllers\iapi\admin\traits\Soma;
use App\services\soma\PackageService;

class Package extends MY_Admin_Iapi {

    use Soma;


    /**
     * 获取套票列表 //http://admin.iwide.cn/index.php/iapi/v1/soma/package/index?page=1&status=2&word=xxx&cat=12
     * @author liguanglong  <liguanglong@mofly.cn>
     */
	public function index()
    {
        $result = PackageService::getInstance()->index($this->input->get());

        $catList = [0 => '全部'];
        foreach (PackageService::getInstance()->getCatalog($this->inter_id) as $val) {
            $catList[$val['cat_id']] = $val['cat_name'];
        }

        $data = [
            'items' => $result['data'],
            'page_resource' => [
                'links' => [
                    'add' => base_url('index.php/soma/product_package/add'),
                    'edit' => base_url('index.php/soma/product_package/edit')."?ids="
                ],
                'page' => $result['page_num'],
                'count' => $result['total'],
                'size'=> $result['page_size'],
                'nav' => [1 => '上线中', 0 => '全部商品', 3 => '已下架'],
                'title' => ['ID分类', '商品类型', '封面图', '商品名称价格', '库存', '首页是否显示', '创建时间', '优先级', '状态', '操作'],
                'category' => $catList,
            ],
        ];

        $this->out_put_msg(1, '', $data);
    }

}
