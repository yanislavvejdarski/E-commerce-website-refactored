<?php

namespace controller;

use helpers\Request;
use helpers\Session;
use helpers\Validator;

abstract class AbstractController
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * AbstractController constructor.
     */
    public function __construct()
    {
        $this->request = Request::getInstance();
        $this->session = Session::getInstance();
        $this->validator = new Validator();
    }
}
