<?php

namespace controller;

use helpers\Request;
use helpers\Session;

abstract class AbstractController
{
    /**
     * @var instance
     */
    protected $request;

    /**
     * AbstractController constructor.
     */
    public function __construct()
    {
        $this->request = Request::getInstance();
        $this->session = Session::getInstance();
    }
}
