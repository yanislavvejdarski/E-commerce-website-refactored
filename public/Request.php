<?php

class Request
{
    private $uri;
    private $post;
    private $get;
    private static $instance;

    public function __construct()
    {
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        $this->uri = $_SERVER["REQUEST_URI"];
        $this->get = $_GET;
        $this->post = $_POST;

    }

    public static function getInstance()
    {
        if (Request::$instance == null) {
            Request::$instance = new Request;
        }
        return Request::$instance;
    }

    public function postParams()
    {
        return $this->post;
    }
    public function postParam($key)
    {
        if(!empty($this->post[$key])){
            return $this->post[$key];
        }
    }

    public function getParams()
    {
        return $this->get;
    }

    /**
     *
     * @param $key
     * @param null $defaultReturn
     * @return |null
     */
    public function getParam($key , $defaultReturn = null)
    {
        if(!empty($this->get[$key])){
            return $this->get[$key];
        }

        return $defaultReturn;
    }

    /**
     *
     * @param $key
     * @param $value
     */
    public function setPostParam($key, $value)
    {
        $this->post[$key] = $value;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setGetParam($key, $value)
    {
        $this->get[$key] = $value;
    }

}