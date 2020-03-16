<?php

class Request
{
    /**
     * @var string
     */
    private $uri;

    /**
     * @var array
     */
    private $post;

    /**
     * @var array
     */
    private $get;

    /**
     * @var array
     */
    private $requestMethod;

    private static $instance;


    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
        $this->uri = $_SERVER["REQUEST_URI"];
        $this->get = $_GET;
        $this->post = $_POST;
    }
    /**
     * @return instance
     */
    public static function getInstance()
    {
        if (Request::$instance == null) {
            Request::$instance = new Request;

        }
        return Request::$instance;
    }

    /**
     * @param $param
     *
     * @return mixed
     */
    private function sanitize ($param)
    {
        $param = trim($param);
        $param = htmlentities($param);
        return $param;
    }

    /**
     * @return mixed
     */
    public function postParams()
    {
        foreach ($this->post as $key => $value){
           $this->post[$key] = $this->sanitize($value);
        }
        return $this->post;
    }

    /**
     * @param $key
     * @param null $defaultReturn
     *
     * @return mixed|null
     */
    public function postParam(
        $key,
        $defaultReturn = null
    ){

        return isset($this->post[$key]) ? $this->sanitize($this->post[$key]) : $defaultReturn;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        foreach ($this->get as $key => $value){
            $this->get[$key] = $this->sanitize($value);
        }
        return $this->get;
    }

    /**
     *
     * @param $key
     * @param null $defaultReturn
     *
     * @return string
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

    /**
     * @return array
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }
}