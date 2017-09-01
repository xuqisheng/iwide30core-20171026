<?php

/**
 * User: renshuai <renshuai@mofly.cn>
 * Date: 2017/4/1
 * Time: 11:18
 *
 *
 * @property Category_package_model $somaCategoryPackageModel
 *
 */
class Category_Service extends MY_Service
{

    /**
     * @param int $catID
     * @return array
     * @author renshuai  <renshuai@mofly.cn>
     */
    public function info($catID)
    {
        $path = $this->modelName(Category_package_model::class);
        $alias = $this->modelAlias(Category_package_model::class);
        $this->CI->load->modelWithDBconn($path, $alias, $this->db, $this->db_read);

        $rows = $this->somaCategoryPackageModel->get('cat_id', $catID);

        if (empty($rows)) return array();

        return $rows[0];
    }

}