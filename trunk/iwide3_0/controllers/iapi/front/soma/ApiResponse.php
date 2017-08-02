<?php

/**
 * 不需要上线，方便写文档
 * @SWG\Definition(type="object")
 */
class ApiResponse
{

    /**
     * @SWG\Property(format="int32")
     * @var int
     */
    public $status;
    /**
     * @SWG\Property
     * @var string
     */
    public $msg;
    /**
     * @SWG\Property
     * @var string
     */
    public $msg_type;
    /**
     * @SWG\Property
     * @var array
     */
    public $web_data;

}