<?php
use RajaSaadhvi\Framework\Router\Classes\Router;
use RajaSaadhvi\Framework\Request\Classes\Request;

require_once __DIR__.'/vendor/autoload.php';

Router::get('/', function() {
    return "index";
});

Router::get('/profile', function(Request $request) {
    return $request->requestMethod;
});

Router::post('/data', function(Request $request) {
    return json_encode($request->getBody());
});

Router::init();