<?php

namespace controller;

class MainController extends AbstractController
{
    /**
     * Show Main Page
     */
    public function render()
    {
        include_once "view/header.php";
        include_once "view/main.php";
    }

    /**
     * Show Error404 Page
     */
    public function render404()
    {
        include_once "view/header.php";
        include_once "view/404.php";
    }
}