<?php

namespace controller;

use Request;

abstract class AbstractController
{
    protected $request;

    public function __construct()
    {
        $this->request = Request::getInstance();
    }
}
