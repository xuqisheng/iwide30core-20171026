<?php

namespace App\core\Db;
/**
 * @author liwensong <septet-l@outlook.com>
 * Interface BaseDbInterface
 * @package App\core\Db
 */
interface DbInterface
{
    public static function getInstance(); //Get an instance of this class

    public function getCI(); //Get an CI instance object

    public function _shard_db(); //Get an Database object

    public function _db(); //Get an Database object

    public function database(); //Get an Database Loader
}