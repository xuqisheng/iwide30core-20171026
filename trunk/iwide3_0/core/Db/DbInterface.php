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
}