<?php

namespace router;

use http\Header;
use router\Authenticator;
use helpers\Request;

class Router
{
    /**
     * @var bool
     */
    public $flag = true;

    /**
     * @var instance
     */
    public $request;

    /**
     * Router constructor.
     */
    public function __construct()
    {

        $this->request = Request::getInstance();
    }

    /**
     * This is route functioning
     *
     * @param string $url
     * @param string $pattern
     * @param null|string $permission
     *
     * @return NULL
     */
    public function route(
        $url,
        $pattern,
        $permission = null
    ) {
        $request = $this->request;

        $uriParams = explode("/", $request->getUri());

        $helperUrl = $this->generateDynamicRoute($uriParams);

        return $this->matchRoute(
            $helperUrl,
            $url,
            $uriParams,
            $pattern,
            $permission
        );
    }

    /**
     * Generating Dynamic Route
     * @param string $uriParams
     *
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

    /**
     * Match Route
     *
     * @param string $helperUrl
     * @param string $url
     * @param array $uriParams
     * @param string $pattern
     * @param string $permission
     *
     * @return NULL
     */
    public function matchRoute(
        $helperUrl,
        $url,
        $uriParams,
        $pattern,
        $permission
    ) {
        if ($helperUrl == $url) {
            $explodedUrl = explode('/', $url);
            $this->flag = true;
            for ($i = 0; $i < count($uriParams); $i++) {
                if (is_numeric($uriParams[$i])) {

                    $this->request->setGetParam(
                        $explodedUrl[$i - 1],
                        $uriParams[$i]
                    );
                }
            }

            $command = explode("@", $pattern);
            $controller = "controller\\" . $command[0];
            $action = $command[1];
            $object = new $controller;

            if ($permission == "user") {
                Authenticator::authenticateLoggedUser();
            } elseif ($permission == "admin") {
                Authenticator::authenticateAdmin();
            }

            if (class_exists($controller) && method_exists($object, $action)) {

                return $object->$action();
            }
            else{
                header("Location: /home");
            }
        }
    }

    /**
     *  Render error page
     */
    public function error404()
    {
        if (!$this->flag) {
            include_once "view/404.php";
        }
    }
}