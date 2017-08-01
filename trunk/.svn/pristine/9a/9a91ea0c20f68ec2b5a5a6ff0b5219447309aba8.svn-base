<?php
use App\services\Result;
use  App\services\soma\express\ExpressProvider;
use App\services\soma\ExpressService;

/**
 * Class Express
 * @author renshuai  <renshuai@mofly.cn>
 *
 * @property Customer_address_model $customerAddressModel
 */
class Express extends MY_Front_Soma
{

    /**
     *
     * 获取地区
     *
     * @example request http://front.iwide.cn/index.php/soma/express/tree?id=a450089706&openid=o9Vbtw4pZhVD6A1w1Cdr9xP
     *
     * return json format
     * <code>
     * {
     *   status: 1,
     *   message: '',
     *   data: [
     *             [
     *                 {
     *                 "region_id":"1",
     *                 "region_name":"北京",
     *                 "parent_id":"0",
     *                 "children":
     *                             [
     *                                 {
                                          "region_id":"1",
     *                                    "region_name":"北京",
     *                                    "parent_id":"0",
     *                                    "children": [
     *
     *                                                ]
     *                                  }
     *                             ]
     *                  }
     *              ]
     *
     *         ]
     * }
     * </code>
     *
     * @author liguanglong  <liguanglong@jperation.cn>
     * @return void
     */
    public function tree()
    {
        $result = ExpressService::getInstance()->regionTree();
        $this->json($result->toArray());
    }




    /**
     *
     * 获取用户邮寄地址
     *
     * @example request http://front.iwide.cn/index.php/soma/express/my?id=a450089706&openid=o9Vbtw4pZhVD6A1w1Cdr9xP
     *
     * return json format
     * <code>
                    {
                        "status": 1,
                        "message": "",
                        "data": [
                                    {
                                    "address_id": "10282",
                                    "openid": "o9Vbtw4pZhVD6A1w1Cdr9xPX_Y6U",
                                    "hotel_id": null,
                                    "inter_id": "o9Vbtw4pZh",
                                    "country": null,
                                    "province": null,
                                    "city": null,
                                    "region": null,
                                    "address": null,
                                    "zip_code": null,
                                    "phone": null,
                                    "contact": null,
                                    "status": "1",
                                    "created_at": "2017-06-28 18:31:24",
                                    "updated_at": "2017-06-28 18:31:24"
                                    }
     *                           ]
     *              }
     * </code>
     *
     * @author liguanglong  <liguanglong@jperation.cn>
     * @return void
     */
    public function my()
    {
        $result = new Result();
        $openid = $this->openid;
        $interID = $this->inter_id;

        $this->load->model('soma/Customer_address_model', 'customerAddressModel');
        $list = $this->customerAddressModel->get(
            [
                'inter_id',
                'openid'
            ],
            [
                $interID,
                $openid
            ],
            '*',
            [
                'limit' => 5
            ]
        );

        $result->setData($list);
        $result->setStatus(Result::STATUS_OK);

        $this->json($result->toArray());
    }


    /**
     *
     * 保存（增加、修改）地址数据
     *
     * @example post http://front.iwide.cn/index.php/soma/express/save
     *                    ?id=a450089706&openid=o9Vbtw4pZhVD6A1w1Cdr9xPX_Y6U&address_id=10025
     *                    &province=6&city=76&region=693&address=haha&phone=13533446996&contact=Rual
     *
     * return json format
     * <code>
     * {
     *   status: 1,
     *   message: '',
     *   data: []
     * }
     * </code>
     *
     * @author liguanglong  <liguanglong@jperation.cn>
     * @return void
     */
    public function save(){

        $this->load->library('form_validation');

        $rules = array(
            'province' => array(
                'field' => 'province',
                'rules' => 'required'
            ),
            'city' => array(
                'field' => 'city',
                'rules' => 'required'
            ),
//            'region' => array(
//                'field' => 'region',
//                'rules' => 'required'
//            ),
            'address' => array(
                'field' => 'address',
                'rules' => 'required'
            ),
            'phone' => array(
                'field' => 'phone',
                'rules' => 'required',
            ),
            'contact' => array(
                'field' => 'contact',
                'rules' => 'required'
            ),
        );

        $this->form_validation->set_data($this->input->post());
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === false) {
            $result['message'] = $this->form_validation->error_string();
            $this->json($result);
            return;
        }

        /**
         * * @example post http://front.iwide.cn/index.php/soma/express/save
         *                    ?id=a450089706&openid=o9Vbtw4pZhVD6A1w1Cdr9xPX_Y6U&address_id=10025
         *                    &province=6&city=76&region=693&address=haha&phone=13533446996&contact=Rual
         */
        $item = [
            'openid' => $this->openid,
            'inter_id' => $this->inter_id,
            'address_id' => $this->input->post('address_id'),   //address_id=10025
            'province' => $this->input->post('province'),  // province=6
            'city' => $this->input->post('city'),          //city=76
            'region' => $this->input->post('region'),     //region=693
            'address' => $this->input->post('address'),   //文字，如：广东省广州市天河区壬丰大厦
            'phone' => $this->input->post('phone'),       //phone=13533446996
            'contact' => $this->input->post('contact'),   //contact=Rual
        ];

        $result = new Result();
        $result->setStatus(Result::STATUS_FAIL);
        $result->setMessage('保存失败');
        $returnData = ExpressService::getInstance()->saveRegion($item);
        if($returnData){
            $result->setData(array('address_id'=>$returnData));
            $result->setStatus(Result::STATUS_OK);
            $result->setMessage('保存成功');
        }

        $this->json($result->toArray());
    }



    /**
     *
     * 删除地址
     *
     * @example request http://front.iwide.cn/index.php/soma/express/destroy
     *                  ?id=a450089706&openid=o9Vbtw4pZhVD6A1w1Cdr9xPX_Y6U&address_id=10025
     *
     *
     * return json format
     * <code>
     * {
     *   status: 1,
     *   message: '',
     *   data: []
     * }
     * </code>
     *
     * @author liguanglong  <liguanglong@jperation.cn>
     * @return void
     */
    public function destroy(){
        $result = new Result();
        $result->setStatus(Result::STATUS_FAIL);
        $destroy = ExpressService::getInstance()
                                 ->deleteRegion($this->input->get('address_id'), $this->openid, $this->inter_id);
        if($destroy){
            $result->setStatus(Result::STATUS_OK);
            $result->setMessage('删除成功');
        }

        $this->json($result->toArray());
    }


}