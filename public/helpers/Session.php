<?php

namespace helpers;

class Session
{
    /**
     * @var array
     */
    private $session;

    /**
     * Session constructor.
     */
    public function __construct()
    {
        $this->session = $_SESSION;
    }

    /**
     * @var instance
     */
    private static $instance;

    /**
     * @return instance
     */
    public static function getInstance()
    {
        if (Session::$instance == null) {
            session_start();
            Session::$instance = new Session;
        }
        return Session::$instance;

    }

    public function sessionDestroy()
    {
        unset($this->session);
        session_destroy();
    }

    public function setSessionParam(
        $key,
        $value
    ) {

        return $this->session[$key] = $value;
    }

    public function getSessionParam($key)
    {

        return $this->session[$key];
    }

    public function getSessionParams()
    {

        return $this->session;
    }
}