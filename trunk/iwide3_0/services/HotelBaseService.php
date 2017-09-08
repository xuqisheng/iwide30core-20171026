<?php
namespace App\services;

use RuntimeException;

/**
 * Class HotelBaseService
 * @package App\services
 * @author lijiaping  <lijiaping@mofly.cn>
 *
 */
class HotelBaseService extends BaseService
{

    public function __construct(){

        //统一处理数据流
        $post = json_decode($this->getCI()->input->raw_input_stream,true);
        if(!empty($post)&&is_array($post)){
            foreach ($post as $key => $value) {
                if(!isset($_POST[$key])){
                    $_POST[$key] = $value;
                }
            }
        }
        $this->_hotel_ci = $this->getCI();
    }

    public function __get($key)
    {
        // Debugging note:
        //  If you're here because you're getting an error message
        //  saying 'Undefined Property: system/core/Model.php', it's
        //  most likely a typo in your model code.
        if(!isset($this->getCI()->$key)){
            throw new RuntimeException('getting an Undefined Property :'.$key);
        }
        return $this->getCI()->$key;
    }
    public function __call($key,$params)
    {

        if(!method_exists($this->getCI(),$key)){
            throw new RuntimeException('getting an Undefined method :'.$key);
        }
        return call_user_func_array(array($this->getCI(),$key),$params);
    }
}