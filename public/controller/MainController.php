<?php
namespace controller;
class MainController {
    public function render(){
        $myVariable = 6;
        include_once "view/header.php";
        include_once "view/main.php";

    }
}