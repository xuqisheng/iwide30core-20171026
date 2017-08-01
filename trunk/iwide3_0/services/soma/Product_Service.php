<?php

/**
 * User: renshuai <renshuai@mofly.cn>
 * @property Product_package_model $somaProductPackageModel
 * @property Product_package_ticket_model $somaProductPackageTicketModel
 * @property Product_specification_setting_model $somaProductSpecificationSettingModel
 * @property Theme_config_model $somaThemeConfigModel
 * Date: 2017/3/1
 * Time: 11:15
 */
class Product_Service extends MY_Service
{
    /**
     * Product_Service constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $path = $this->modelName(Product_package_model::class);
        $alias = $this->modelAlias(Product_package_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);

        $path = $this->modelName(Product_package_ticket_model::class);
        $alias = $this->modelAlias(Product_package_ticket_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);

        $path = $this->modelName(Product_specification_setting_model::class);
        $alias = $this->modelAlias(Product_specification_setting_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);

        $path = $this->modelName(Theme_config_model::class);
        $alias = $this->modelAlias(Theme_config_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);
    }


    public function create(Array $arr)
    {

        $path = $this->modelName(Product_package_model::class);
        $alias = $this->modelAlias(Product_package_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);


        return true;
    }

    /**
     * 根据门店id获取门店配置
     * @param $ticketId
     * @return array
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function getProductPackageTicketProductIds( $ticketId, $catId=NULL )
    {
        //$this->load->model('soma/Product_package_ticket_model','somaProductPackageTicketModel');
        //$somaProductPackageTicketModel = $this->somaProductPackageTicketModel;
        $ticketList = $this->somaProductPackageTicketModel->get_product_package_ticket_byIds( array($ticketId), $this->inter_id );
        if( !$ticketList )
        {
            return array();
        }

        //$this->load->model('soma/Product_package_model','somaProductPackageModel');
        //$somaProductPackageModel = $this->somaProductPackageModel;

        //$this->load->model('soma/Theme_config_model','somaThemeConfigModel');
        //$somaThemeConfigModel = $this->somaThemeConfigModel;

        $productIds = $products = array();
        foreach( $ticketList as $k=>$v )
        {

            $themeList = $this->somaThemeConfigModel->get_theme_detail( array( $v['theme_id'] ), $this->inter_id );
            if( $themeList )
            {
                $themeDetail = current( $themeList );
                $ticketList[$k]['theme_path'] = isset( $themeDetail['theme_path'] ) ? $themeDetail['theme_path'] : '';
            }

            //处理产品id
            if( $v['scope'] == Soma_base::STATUS_TRUE )
            {
                if( $catId )
                {
                    $products = $this->somaProductPackageModel->get_package_list( $this->inter_id, array('inter_id'=>$this->inter_id, 'cat_id'=>$catId) );
                } else {
                    $products = $this->somaProductPackageModel->get_package_list( $this->inter_id, array('inter_id'=>$this->inter_id) );
                }
            } elseif( $v['scope'] == Soma_base::STATUS_FALSE ) {

                if( isset( $v['product_ids'] ) && !empty( $v['product_ids'] ) )
                {
                    $product_ids = json_decode( $v['product_ids'], true );
                    if( $product_ids )
                    {
                        if( $catId )
                        {
                            $productIds = $product_ids[$catId];
                        } else {
                            foreach( $product_ids as $sk=>$sv )
                            {
                                $productIds = array_merge( $productIds, array_values( $sv ) );
                            }
                        }

                        $select = '*';
                        $sort = 'sort DESC';
                        $products = $this->somaProductPackageModel->get_product_package_by_ids($productIds, $this->inter_id, $select, $sort);
                    }
                }
            }

            //处理展位内容
            if( isset( $v['block_arr'] ) && !empty( $v['block_arr'] ) )
            {
                $ticketList[$k]['block_arr'] = json_decode( $v['block_arr'], true );
            }

        }

        return array(
            'ticketList'    =>$ticketList,
            'product_Ids'   =>$productIds,
            'products'      =>$products,
        );
    }

    /**
     * 根据产品ID和规格类型获取规格信息列表
     * @param $prodcutId
     * @param $type
     * @return mixed
     * @author luguihong  <luguihong@mofly.cn>
     */
    public function getSettingInfoByProductId( $prodcutId, $type=NULL )
    {
        //$this->load->model('soma/Product_specification_setting_model', 'somaProductSpecificationSettingModel');
        //$somaProductSpecificationSettingModel = $this->somaProductSpecificationSettingModel;
        $settingList = $this->somaProductSpecificationSettingModel->get_full_specification_compose($this->inter_id, $prodcutId, $type);
        return $settingList;
    }

    /**
     * 获取公众号下的子商品信息
     *
     * @param      string     $inter_id   公众号ID
     * @param      array      $hotel_ids  酒店ID数组，默认为空
     * @param      array      $filter     其他过滤条件
     *
     * @throws     Exception  公众号ID为空异常
     *
     * @return     array      The compose product list.
     *
     * @author     fengzhongcheng <fengzhongcheng@mofly.cn>
     */
    public function getComposeProductList($inter_id, $hotel_ids = array(), $filter = array())
    {
        if(!is_string($inter_id))
        {
            throw new Exception("Inter id must be a string!", 1);
        }
        $filter['inter_id'] = $inter_id;

        if(!empty($hotel_ids))
        {
            $filter['hotel_id'] = array_values($hotel_ids);
        }

        $base_info = $this->somaProductPackageModel->getComposeProductBaseInfo($filter);
        $pids = array();
        foreach($base_info as $product)
        {
            $pids[] = $product['product_id'];
        }
        
        $spec_info = $fmt_spec_info = array();
        if(!empty($pids))
        {
            $spec_info = $this->somaProductSpecificationSettingModel->get_inter_product_spec_setting($inter_id, $pids);
        }

        foreach($spec_info as $spec_row)
        {
            $tmp_spec_row = $spec_row;
            $tmp_spec_row['setting_spec_compose'] = json_decode($spec_row['setting_spec_compose'], true);
            $fmt_spec_info[$spec_row['product_id']][] = $tmp_spec_row;
        }

        $fmt_data = array();

        foreach($base_info as $product)
        {   
            $product['spec_info'] = array();
            if(isset($fmt_spec_info[$product['product_id']]))
            {
                $product['spec_info'] = $fmt_spec_info[$product['product_id']];
            }

            $fmt_data[$product['cat_id']]['cat_id'] = $product['cat_id'];
            $fmt_data[$product['cat_id']]['cat_name'] = $product['cat_name'];
            $fmt_data[$product['cat_id']]['product'][] = $product;
        }

        return $fmt_data;
    }

}