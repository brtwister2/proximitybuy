<?php
$origin = isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:$_SERVER['HTTP_HOST'];
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, X-Requested-With');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
include_once('vendor/simple-html-dom/simple-html-dom/simple_html_dom.php');
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

$app = new \Slim\App;

$contentType = 'application/json; charset: utf-8';

class Service {
	public $VERSION_CODE = "0.0.1a";

	function hello(){
		return array("title" => "Hello", 'msg' => "You are welcome!", 'version' => $this->VERSION_CODE);
	}

}




$app->get('/hello', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	$service = new Service();
    return $response->withHeader('Content-type', $contentType)->write(json_encode($service->hello()));
});


$app->run();
