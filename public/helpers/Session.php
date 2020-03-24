<?php

namespace helpers;

class Session
{
    /**
     * @var Session
     */
    private static $instance;

    /**
     * @var array
     */
    private $session;

    /**
     * @var bool
     */
    private $sessionStarted = false;

    /**
     * Session constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return Session
     */
    public static function getInstance()
    {
        if (Session::$instance == null) {
            Session::$instance = new Session;
            Session::$instance->sessionStart();
        }

        return Session::$instance;
    }

    /**
     * Start Session
     */
    public function sessionStart()
    {
        if ($this->sessionStarted == false) {
            session_start();
            $this->session = $_SESSION;
            $this->sessionStarted = true;
        }
    }

    /**
     * Destroy Session
     */
    public function sessionDestroy()
    {
        if ($this->sessionStarted == true) {
            unset($this->session);
            session_destroy();
            $this->sessionStarted = false;
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function setSessionParam(
        $key,
        $value
    ) {
        $this->session[$key] = $value;
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     * @param null $defaultReturn
     *
     * @return mixed|null
     */
    public function getSessionParam($key, $defaultReturn = null)
    {
        if (isset($this->session[$key])) {

            return $this->session[$key];
        }

        return $defaultReturn;
    }

    /**
     * @return array
     */
    public function getSessionParams()
    {

        return $this->session;
    }
}