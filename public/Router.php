<?php


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
    public function route($url, $pattern)
    {
        $request = $this->request;

        $uriParams = explode("/", $request->getUri());

        $helperUrl = $this->generateDynamicRoute($uriParams);
        return $this->matchRoute($helperUrl, $url, $uriParams, $pattern,$request);
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
        $pattern
    ) {
        if ($helperUrl == $url) {
            $explodedUrl = explode('/', $url);
            $this->flag= true;
            for ($i = 0; $i < count($uriParams); $i++) {
                if (is_numeric($uriParams[$i])) {

                    $this->request->setGetParam($explodedUrl[$i-1], $uriParams[$i]);
                }
            }
            $command = explode("@", $pattern);
            $controller = "controller\\" . $command[0];

            $action = $command[1];
            $object = new $controller();

            if (empty($this->request->getParams())) {
                if (class_exists($controller) && method_exists($object,$action)){
                    return $object->$action();
                }
                else{
                    header("Location:/http");
                }
            } else {
                if (class_exists($controller) && method_exists($object,$action)){
                    return $object->$action($this->request->getParams());
                }
                else{
                    header("Location:/http");
                }
            }
        }
    }


    public function error404(){
        if (!$this->flag){
            include_once "view/404.php";
        }
    }
}