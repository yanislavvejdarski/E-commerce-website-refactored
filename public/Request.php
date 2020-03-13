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

    /**
     * @param $param
     */
    public function sanitize ($param){
        $param = trim($param);
        $param = htmlentities($param);
        return $param;
    }

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        if (Request::$instance == null) {
            Request::$instance = new Request;
        }
        return Request::$instance;
    }

    /**
     * @return mixed
     */
    public function postParams()
    {
        foreach ($this->post as $item){
            $this->sanitize($item);
        }
        return $this->post;
    }
    public function postParam(
        $key,
        $defaultReturn = null
    ){
        $post = $this->post[$key];
        return isset($this->post[$key]) ? $post : $defaultReturn;
    }

    public function getParams()
    {
        return $this->get;
    }

    /**
     *
     * @param $key
     * @param null $defaultReturn
     * @return void|null
     */
    public function getParam(
        $key,
        $defaultReturn = null
    ) {
        return isset($this->get[$key]) ? $this->sanitize($this->get[$key]) : $defaultReturn;
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