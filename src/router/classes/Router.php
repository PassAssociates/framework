<?php
namespace RajaSaadhvi\Framework\Router\Classes;
use RajaSaadhvi\Framework\Request\Classes\Request;
class Router
{
    /**
     * @var object To Store Request object
     */
    private static $request;
    /**
     * @var array To store available http methods
     */
    private static $supportedHttpMethods = array(
        "GET",
        "POST"
    );
    /**
     * @var array to store get route and callback method as key per value
     */
    private static $get;
    /**
     * @var array to store post route and callback method as key per value
     */
    private static $post;

    /**
     * @param $name
     * @param $arguments
     */
    public static function __callStatic($name, $arguments)
    {
        if ($name=="init"){
            self::resolve();
            exit;
        }
        self::$request = new Request();
        list($route, $method) = $arguments;
        if(!in_array(strtoupper($name), self::$supportedHttpMethods))
        {
            self::invalidMethodHandler();
        }
        self::${strtolower($name)}[self::formatRoute($route)] = $method;
    }

    /**
     * @param $route
     * @return string
     * To remove extra slash form route or to remove tile slash of route
     */
    private static function formatRoute($route)
    {
        $result = rtrim($route, '/');
        if ($result === '')
        {
            return '/';
        }
        return $result;
    }

    /**
     * To send response with 405
     */
    private static function invalidMethodHandler()
    {
        header(self::$request->serverProtocol." 405 Method Not Allowed");
    }

    /**
     * To send response with 404
     */
    private static function defaultRequestHandler()
    {
        header(self::$request->serverProtocol." 404 Not Found");
    }

    /**
     * Resolves a route
     */
    private static function resolve()
    {
        $methodDictionary = self::${strtolower(self::$request->requestMethod)};
        $formatedRoute = self::formatRoute(self::$request->requestUri);
        $method = $methodDictionary[$formatedRoute];
        if(is_null($method))
        {
            self::defaultRequestHandler();
            return;
        }
        echo call_user_func_array($method, array(self::$request));
    }
}
