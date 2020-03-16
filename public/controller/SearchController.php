<?php

namespace controller;

use model\Search;
use Request;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class SearchController extends AbstractController
{
    public function render()
    {
        $post = $this->request->postParams();
        if (isset($post["searchProducts"])) {
            $controller = new Search($post["search"]);
            $controller->render();
            exit();
        }
    }
}

