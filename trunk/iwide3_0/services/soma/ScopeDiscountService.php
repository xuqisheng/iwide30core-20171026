<?php
namespace App\services\soma;

use App\models\soma\ScopeDiscount;
use App\models\soma\ScopeProductLink;

use App\models\soma\ScopeProductLinkUser;
use App\services\BaseService;

/**
 *
 * 以后使用命名空间！！！
 *
 *
 * Class ScopeDiscountService
 * @package App\services\soma
 * @author renshuai  <renshuai@mofly.cn>
 *
 * @date 2017-04-26
 */
class ScopeDiscountService extends BaseService
{
    /**
     * 获取服务实例方法
     * @return ScopeDiscountService
     */
    public static function getInstance()
    {
        return self::init(self::class);
    }

    /**
     *
     * 获取公众号 价格配置列表
     * @param $interID
     * @param $scope
     * @param int $page
     * @param int $limit
     * @param string $orderBy
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getAvailableList($interID, $scope, $page = 1, $limit = 10, $orderBy = 'id desc')
    {
        $scopeDiscountModel = new ScopeDiscount();

        return $scopeDiscountModel->getAvailableList($interID, $scope, $page, $limit, $orderBy);
    }

    /**
     * 是否可以使用专属价购买
     * @param $interID
     * @param $openid
     * @param $scope_product_link_id
     * @param $num
     * @return bool
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function checkStock($interID, $openid, $scope_product_link_id, $num)
    {
        $scopeProductLinkModel = new ScopeProductLink();
        $link = $scopeProductLinkModel->getById($scope_product_link_id);
        if ( empty($link) ) {
            return false;
        }

        $scopeProductLinkUserModel = new ScopeProductLinkUser();
        $linkUser = $scopeProductLinkUserModel->get(
            [
                'inter_id',
                'openid',
                'scope_link_id'
            ],
            [
                $interID,
                $openid,
                $scope_product_link_id
            ]
        );

        //用户没有购买过
        if (!isset($linkUser[0])) {
            return true;
        }

        //不限制购买次数
        if ($link['limit_num'] === '0') {
            return true;
        }

        //没有超过购买次数限制
        if ($link['limit_num'] >= ($linkUser[0]['used_num'] + $num) )
        {
            return true;
        }

        return false;
    }

    /**
     * @param $interID
     * @param $openid
     * @param $scope_product_link_id
     * @param $num
     * @param string $opt + | -
     * @return object
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function updateStock($interID, $openid, $scope_product_link_id, $num, $opt = '+')
    {
        $scopeProductLinkUserModel = new ScopeProductLinkUser();
        $linkUser = $scopeProductLinkUserModel->get(
            [
                'inter_id',
                'openid',
                'scope_link_id'
            ],
            [
                $interID,
                $openid,
                $scope_product_link_id
            ]
        );

        $date = date('Y-m-d H:i:s');
        if (isset($linkUser[0])) {
            $data = [
                'inter_id' => $interID,
                'openid' => $openid,
                'scope_link_id' => $scope_product_link_id,
            ];
            if ($opt === '-') {
                $data['used_num>='] = $num;
            }

            return $scopeProductLinkUserModel->soma_db_conn
                    ->set('used_num', 'used_num' . $opt . $num, false)
                    ->set('updated_at', $date)
                    ->where($data)
                    ->update($scopeProductLinkUserModel->table_name());

        } else {
            if ($opt == '+') {

                $data = [
                    'inter_id' => $interID,
                    'openid' => $openid,
                    'scope_link_id' => $scope_product_link_id,
                    'used_num' => $num,
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
                return $scopeProductLinkUserModel->soma_db_conn->set($data)->insert($scopeProductLinkUserModel->table_name());

            } else {
                return true;
            }

        }

    }

    /**
     *
     * todo 数据放缓存里
     *
     * 拉订房的接口获得用户的  scope_discount id list
     * @param $interID
     * @param $openid
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function getUserScopeDiscount($interID, $openid)
    {
        $uri = $_SERVER['HTTP_HOST'] . "/index.php/api/ClubApi/getSomaClub?inter_id=$interID&openid=$openid";
        $result = ScopeDiscountService::getInstance()->request($uri);
        $arr = isset($result['soma_club']) ? $result['soma_club'] : [];

//        $arr = [
//            [
//                'social' => [ //社群客人的信息
//                    'id' => 1,
//                    'name' => 'test shuai',
//                ],
//                'somaPriceList' => [ //这个社群客对应的商城的可用改价格
//                    1,
//                    2,
//                    3,
//                    6
//                ]
//            ]
//        ];

        $scopeIDs = [];
        $socials = [];
        foreach($arr as $item)
        {
            $scopeIDs = array_merge($scopeIDs, $item['somaPriceList']);
            foreach($item['somaPriceList'] as $id) {
                $socials[$id] = $item['social'];
            }
        }

        return [
            'scopeIDs' => $scopeIDs,
            'socials' => $socials
        ];
    }

    /**
     * 给商品追加用户对应的专属价格
     *
     * @param array $products  多个product的array
     * @param string $interID
     * @param string $openid
     * @param bool $miniFlag  是否只把最低价追加过去
     *
     * @author renshuai  <renshuai@mofly.cn>
     * @return void
     */
    public function appendScopeDiscount(&$products, $interID, $openid, $miniFlag = true)
    {
        //优先显示有使用次数并且价格低的价格
        $socialsResult = $this->getUserScopeDiscount($interID, $openid);
        $scopeIDs = $socialsResult['scopeIDs'];
        $socials = $socialsResult['socials'];

        $scopeDiscountModel = new ScopeDiscount();
        $scopes = $scopeDiscountModel->getAvailableByIds($scopeIDs);

        $newScopeIDs = array();
        foreach ($scopes as $scope) {
            $newScopeIDs[] = $scope['id'];
        }

        $productIDs = [];
        foreach($products as $product)
        {
            $productIDs[] = $product['product_id'];
        }

        if (empty($newScopeIDs) || empty($productIDs)) {
            return;
        }

        $scopeProductLinkModel = new ScopeProductLink();
        $scopeLinks = $scopeProductLinkModel->get(
            array(
                'scope_id',
                'product_id',
                'deleted_at'
            ),
            array(
                $newScopeIDs,
                $productIDs,
                '0000-00-00 00:00:00'
            ),
            '*',
            array(
                'limit' => count($newScopeIDs) + count($productIDs),
                'debug' => false
            )
        );

        $scopeLinkIds = [];
        foreach ($scopeLinks as $scopeLink) {
            $scopeLinkIds[] = $scopeLink['id'];
        }

        $linkUsers = [];
        $scopeLinkUserModel = new ScopeProductLinkUser();
        if (!empty($scopeLinkIds)) {
            $linkUsers = $scopeLinkUserModel->get(
                array(
                    'inter_id',
                    'openid',
                    'scope_link_id'
                ),
                array(
                    $interID,
                    $openid,
                    $scopeLinkIds
                ),
                '*',
                array(
                    'limit' => count($scopeLinkIds),
                    'debug' => false
                )
            );
        }

        //用户已经使用的数量
        $userUsedRecord = array();
        foreach ($linkUsers as $linkUser) {
            $userUsedRecord[$linkUser['scope_link_id']] = $linkUser['used_num'];
        }

        foreach ($products as &$product) {
            foreach($scopeLinks as $scopeLink) {
                if ($scopeLink['product_id'] == $product['product_id']) {
                    if ($miniFlag) {
                        if ( !isset($product['scopes']) || (isset($product['scopes']) && $scopeLink['price'] < $product['scopes']['price'] )) {
                            if ( isset($userUsedRecord[$scopeLink['id']]) && ($scopeLink['limit_num'] == '0' ||  $scopeLink['limit_num'] > $userUsedRecord[$scopeLink['id']]) ) {
                                $scopeLink['used_num'] = $userUsedRecord[$scopeLink['id']];
                                $product['scopes'] = $scopeLink;
                            } else {
                                $scopeLink['used_num'] = 0;
                                $product['scopes'] = $scopeLink;
                            }
                        }
                    } else {
                        if ( isset($userUsedRecord[$scopeLink['id']]) && ($scopeLink['limit_num'] == '0' ||  $scopeLink['limit_num'] >= $userUsedRecord[$scopeLink['id']]) ) {
                            $scopeLink['used_num'] = $userUsedRecord[$scopeLink['id']];
                            $product['scopes'][] = $scopeLink;
                        } else {
                            $scopeLink['used_num'] = 0;
                            $product['scopes'][] = $scopeLink;
                        }
                    }
                }
            }
        }

        //如果是去不列出来的话按照价格排序，最小的价格在前边
        if ($miniFlag === false) {
            foreach($products as &$product) {
                if (isset($product['scopes'])) {
                    usort($product['scopes'], array($this, 'compareScopeLink'));
                }
            }

            foreach($products as &$product) {
                if (isset($product['scopes']) && isset($product['scopes'][0])) {
                    $product['social'] = $socials[$product['scopes'][0]['scope_id']];
                }
            }

        }


    }

    /**
     * @param $interID
     * @param $openid
     * @param $productDetail
     * @param $pspID
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function useScopeDiscount($interID, $openid, $productDetail, $pspID)
    {
        $products = array($productDetail);
        $this->appendScopeDiscount($products, $interID, $openid, false);
        $productDetail = $products[0];

        $result = [];
        if (isset($productDetail['scopes']) && !empty($productDetail['scopes'])) {
            if (empty($pspID)) {
                $result = $productDetail['scopes'][0];
            } else {
                foreach($productDetail['scopes'] as $scope) {
                    if ($pspID == $scope['setting_id']) {
                        $result =  $scope;
                        break;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param $a
     * @param $b
     * @return int
     * @author renshuai  <renshuai@mofly.cn>
     */
    private function compareScopeLink($a, $b)
    {
        if ($a['price'] === $b['price'] ) return 0;
        return $a['price'] > $b['price'] ? 1 : -1;
    }

    /**
     * 更新价格配置的状态
     * @param $interIdArr
     * @author luguihong  <luguihong@jperation.com>
     */
    public function updateScopeDiscountStatus( $interIdArr )
    {
        $ScopeDiscountModel = new ScopeDiscount();
        foreach ($interIdArr as $v) {
            $inter_id = $v['inter_id'];

            $ScopeDiscountModel->updateStatus($inter_id);

        }
    }


}