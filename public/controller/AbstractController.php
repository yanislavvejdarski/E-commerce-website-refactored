<?php

namespace controller;

use helpers\Request;

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
    }
}
