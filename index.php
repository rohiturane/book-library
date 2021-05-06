<?php
require 'vendor/autoload.php';
require './Books.php';
$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);
$pointer= array_search('index.php', $uri_segments);
$controller = ucfirst($uri_segments[$pointer+1]); 
$request = new $controller();
header('Content-Type: application/json');
if(empty($uri_segments[$pointer+2]))
{ 
    $request->index();
} else {
        $data = $_REQUEST;
        $request->{$uri_segments[$pointer+2]}($data);
}