<?php
namespace router;

use router\Authenticate;
use helpers\Request;

class Router
{
    /**
     * @var bool
     */
    public $flag = false;

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
     * @return NULL
     */
    public function route($url, $pattern,$permission = null)
    {
        $request = $this->request;

        $uriParams = explode("/", $request->getUri());

        $helperUrl = $this->generateDynamicRoute($uriParams);
        return $this->matchRoute($helperUrl, $url, $uriParams, $pattern,$permission);
    }

    /**
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

    /**
     * Match Route
     *
     * @param string $helperUrl
     * @param string $url
     * @param array $uriParams
     * @param string $pattern
     * @return NULL
     */
    public function matchRoute(
        $helperUrl,
        $url,
        $uriParams,
        $pattern,
        $permission
    )
    {
        if ($helperUrl == $url) {
            $explodedUrl = explode('/', $url);
            $this->flag = true;
            for ($i = 0; $i < count($uriParams); $i++) {
                if (is_numeric($uriParams[$i])) {

                    $this->request->setGetParam($explodedUrl[$i - 1], $uriParams[$i]);
                }
            }
            $command = explode("@", $pattern);
            $controller = "controller\\" . $command[0];

            $action = $command[1];
            $object = new $controller();

            if ($permission == "user") {
                Authenticate::authenticateLoggedUser();
            }
            elseif ($permission == "admin"){
               Authenticate::authenticateAdmin();
            }
            if (empty($this->request->getParams())) {

                    return $object->$action();
            } else {

                    return $object->$action($this->request->getParams());
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