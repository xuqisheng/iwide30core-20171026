<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );


/**
 * //验证手机格式
 * @param $mobilephone
 * @return bool
 */
function check_phone($mobilephone){
    if(preg_match("/^1[0-9]{10}$/",$mobilephone)){
        //验证通过
        return true;
    }else{
        //手机号码格式不对
        return false;
    }

}