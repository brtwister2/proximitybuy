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

error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();


$app = new \Slim\Slim(array(
    'mode' => 'production',
    'debug' => true,
    'log.enabled' => true,
    'log.level' => \Slim\Log::DEBUG,
    'log.writer' => new Slim\LogWriter(fopen('log/log.txt', 'a'))
        ));

*/

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


$app->get('/campaign', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Campaign.php';
	$service = new Campaign();
	$campaigns = $service->getAllCampaigns();


	$result = json_encode(array("campaigns"=>$campaigns));

    return $response->withHeader('Content-type', $contentType)->write($result);
});

$app->get('/campaign/{id}', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Campaign.php';
	$route = $request->getAttribute('route');
    $campaignId = $route->getArgument('id');

	$service = new Campaign();
	$campaigns = $service->getCampaignWithId($campaignId);

	$result = json_encode(array("campaign"=>$campaigns));

    return $response->withHeader('Content-type', $contentType)->write($result);
});


$app->put('/campaign/{id}', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Campaign.php';
	$route = $request->getAttribute('route');
    $campaignId = $route->getArgument('id');

	$service = new Campaign();
	$campaigns = $service->updateCampaignWithId($campaignId,$request->getBody());

	$result = json_encode(array("campaign"=>$campaigns));

    return $response->withHeader('Content-type', $contentType)->write($result);
});

$app->post('/campaign', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Campaign.php';
	
	$service = new Campaign();
	$campaigns = $service->addCampaign($request->getBody());

	$result = json_encode(array("campaign"=>"ok"));

    return $response->withHeader('Content-type', $contentType)->write($result);
});


$app->delete('/campaign/{id}', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Campaign.php';
	$route = $request->getAttribute('route');
    $campaignId = $route->getArgument('id');

	$service = new Campaign();
	$campaigns = $service->deleteCampaign($campaignId);

	$result = json_encode(array("campaign"=>"ok"));

    return $response->withHeader('Content-type', $contentType)->write($result);
});



$app->run();

function connect_db() {

    $server = 'localhost';
	$user = 'root';
	$pass = 'root';
	$database = 'proximitybuy';

    $connection = new mysqli($server, $user, $pass, $database);

    return $connection;
}



function ckParam($array, $key) {
    if (is_array($array)) {
        if (array_key_exists($key, $array)) {
            $param = $array[$key];
            // var_dump($param);
            if (!is_null($param)) {
                if (isset($param)) {
                    if (!is_array($param)) {
                        if (trim($param) != "") {
                            return true;
                        }
                    } else {
                        return true;
                    }
                }
            }
        }
    }
    return false;
}