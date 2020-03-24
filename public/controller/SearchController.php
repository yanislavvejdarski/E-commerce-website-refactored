<?php

namespace controller;

use model\Search;
use helpers\Request;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class SearchController extends AbstractController
{
    /**
     * Search Bar
     */
    public function render()
    {
        $postParams = $this->request->postParams();
        if (isset($postParams["searchProducts"])) {
            $controller = new Search($postParams["search"]);
            $controller->render();
            exit();
        }
    }
}

