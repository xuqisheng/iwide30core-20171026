<?php

namespace App\services;

/**
 * Class Result
 * @package App\services
 * @author renshuai  <renshuai@mofly.cn>
 *
 */
class Result
{
    /**
     *
     */
    const STATUS_OK = 1;

    /**
     *
     */
    const STATUS_FAIL = 2;

    /**
     * @var
     */
    private $status;

    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $data;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }


    /**
     * Result constructor.
     * @param $status
     * @param string $message
     * @param array $data
     */
    public function __construct($status = self::STATUS_FAIL, $message = '', $data = [])
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }


}