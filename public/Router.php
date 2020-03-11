<?php

class Router
{
    public static function route($url, $pattern, $uri)
    {
        $uriParams = explode("/", $uri);
        $helperUrl = '';

        foreach ($uriParams as $item) {
            if (empty($item)) {
                continue;
            }


            if (is_numeric($item)) {
                $helperUrl .= '/{:id}';
                continue;
            }

            $helperUrl .= '/' . $item;
        }

        if ($helperUrl == $url) {
            $explodedUrl = explode('/', $url);
            $params = [];

            for ($i = 0; $i < count($uriParams); $i++) {
                if (is_numeric($uriParams[$i])) {
                    $params[$explodedUrl[$i - 1]] = $uriParams[$i];

                }
            }

            $command = explode("@", $pattern);
            $controller = "controller\\" . $command[0];

            $action = $command[1];

            $object = new $controller;

            if (empty($params)) {
                $object->$action();
            } else {
                $object->$action($params);
            }
        }
    }
}