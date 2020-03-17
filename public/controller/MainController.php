<?php

namespace controller;

class MainController extends AbstractController
{
    public function render()
    {
        include_once "view/header.php";
        include_once "view/main.php";
    }

    public function render404()
    {
        include_once "view/header.php";
        include_once "view/404.php";
    }
}