<?php

class Router
{
    public $uri;


    public function __construct()
    {
        $this->uri = $_SERVER["REQUEST_URI"];
    }

    /*
     * This is route functioning
     *
     * @param string $url
     * @param string $pattern
     * @return NULL
     */
    public function route($url, $pattern)
    {
        $uriParams = explode("/", $this->uri);

        $helperUrl = $this->generateDynamicRoute($uriParams);
        return $this->matchRoute($helperUrl, $url, $uriParams, $pattern);
    }

    /*
     * generating Dynamic Route
     *
     * @param string $uriParams
     * @return NULL
     */
    public function generateDynamicRoute($uriParams)
    {
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
        return $helperUrl;
    }

    /*
    * Match Route
    *
    * @param string $helperUrl
    * @param string $url
    * @param array $uriParams
    * @param string $pattern
    * @return NULL
    */
    public function matchRoute($helperUrl, $url, $uriParams, $pattern)
    {
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
                if (class_exists($controller) && method_exists($object,$action)){
                    return $object->$action();
                }
                else{
                    header("Location:/http");
                }
            } else {
                if (class_exists($controller) && method_exists($object,$action)){
                    return $object->$action($params);
                }
                else{
                    header("Location:/http");
                }
            }
        }
    }
    public function error404(){

    }
}