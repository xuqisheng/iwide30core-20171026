<?php

/**
 * Class CollectTest
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class CollectRefact extends MY_Front_Soma_Iapi
{
    /**
     * use helper function data_get
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function get_test()
    {
        $arr = [
            'id' => '123',
            'num' => 233,
            'product' => [
                'id' => 1,
                'name' => 'test',
                'price' => 2,
                'images' => [
                    'link1',
                    'link2'
                ]
            ]
        ];

        $data['id'] = data_get($arr, 'id');
        $data['product_id'] = data_get($arr, 'product.id');
        $data['product_images'] = data_get($arr, 'product.images.*');
        $this->json(\App\libraries\Iapi\FrontConst::OPER_STATUS_SUCCESS, '', $data);
    }

    /**
     * @author renshuai  <renshuai@jperation.cn>
     */
    public function get_test2()
    {
        $orders = [[
            'id'            =>      1,
            'user_id'       =>      1,
            'number'        =>      '13908080808',
            'status'        =>      0,
            'fee'           =>      10,
            'discount'      =>      44,
            'order_products'=> [
                ['order_id'=>1,'product_id'=>1,'param'=>'6寸','price'=>555.00,'product'=>['id'=>1,'name'=>'蛋糕名称','images'=>[]]],
                ['order_id'=>1,'product_id'=>1,'param'=>'7寸','price'=>333.00,'product'=>['id'=>1,'name'=>'蛋糕名称','images'=>[]]],
            ],
        ]];
        $data = collect($orders)->pluck('order_products.*.price')->flatten(1)->sum();
        $this->json(\App\libraries\Iapi\FrontConst::OPER_STATUS_SUCCESS, '', $data);
    }

    public function get_test3()
    {
        $orders = [
            [
                'id'            =>      1,
                'user_id'       =>      1,
                'number'        =>      '13908080808',
                'status'        =>      0,
                'fee'           =>      10,
                'discount'      =>      44,
                'order_products'=> [
                    ['order_id'=>12345,'product_id'=>1,'param'=>'6寸','price'=>555.00,'product'=>['id'=>1,'name'=>'蛋糕名称','images'=>[1]]],
                    ['order_id'=>543211,'product_id'=>1,'param'=>'7寸','price'=>333.00,'product'=>['id'=>1,'name'=>'蛋糕名称','images'=>[2]]],
                ]
            ],
            [
                'id'            =>      1,
                'user_id'       =>      1,
                'number'        =>      '13908080808',
                'status'        =>      0,
                'fee'           =>      10,
                'discount'      =>      44,
                'order_products'=> [
                    ['order_id'=>12345,'product_id'=>1,'param'=>'6寸','price'=>555.00,'product'=>['id'=>1,'name'=>'蛋糕名称','images'=>[1]]],
                    ['order_id'=>543211,'product_id'=>1,'param'=>'7寸','price'=>333.00,'product'=>['id'=>1,'name'=>'蛋糕名称','images'=>[2]]],
                ]
            ]
        ];

        $data = collect($orders)->flatten(2)->toArray();
        $this->json(\App\libraries\Iapi\FrontConst::OPER_STATUS_SUCCESS, '', $data);
    }


    public function get_test4()
    {
        $orders = [
            [
                'id'            =>      434,
                'user_id'       =>      1,
                'number'        =>      '13908080808',
                'status'        =>      0,
                'fee'           =>      10,
                'discount'      =>      44,
                'order_products'=> [
                    ['order_id'=>12345,'product_id'=>1,'param'=>'6寸','price'=>555.00,'product'=>['id'=>1,'name'=>'蛋糕名称','images'=>[1]]],
                    ['order_id'=>543211,'product_id'=>1,'param'=>'7寸','price'=>3333.00,'product'=>['id'=>1,'name'=>'蛋糕名称','images'=>[2]]],
                ]
            ],
            [
                'id'            =>      353,
                'user_id'       =>      1,
                'number'        =>      '13908080808',
                'status'        =>      0,
                'fee'           =>      10,
                'discount'      =>      44,
                'order_products'=> [
                    ['order_id'=>12345,'product_id'=>1,'param'=>'6寸','price'=>555.00,'product'=>['id'=>1,'name'=>'蛋糕名称','images'=>[1]]],
                    ['order_id'=>543211,'product_id'=>1,'param'=>'7寸','price'=>3334.00,'product'=>['id'=>1,'name'=>'蛋糕名称','images'=>[2]]],
                ]
            ]
        ];

        $rowsCollect = collect($orders);
        $data[] = $rowsCollect->pluck('order_products.0.price')->toArray();
        $data[] = $rowsCollect->pluck('order_products.1.price')->toArray();
        $data[] = $rowsCollect->pluck('order_products', 'id')->toArray();
        $this->json(\App\libraries\Iapi\FrontConst::OPER_STATUS_SUCCESS, '', $data);
    }

}