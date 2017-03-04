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

$contentType = 'application/json; charset: utf-8';

//----- DEVICE ------
$app->post('/device', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Device.php';
	$service = new Device();
	$objects = $service->add($request->getBody());
	$result = json_encode(array("status"=>$objects));
    return $response->withHeader('Content-type', $contentType)->write($result);
});
//----- FIM DEVICE ------

$app->get('/appcampaignbid/{trackid}/{bid}', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'ApplicationCampaign.php';
	

	$route = $request->getAttribute('route');
    $bid = $route->getArgument('bid');
	$service = new ApplicationCampaign();
	$campaigns = $service->getCampaignForBeaconId($bid);
	$result = json_encode(array("campanha"=>$campaigns));

    return $response->withHeader('Content-type', $contentType)->write($result);
});

$app->get('/appcampaign/{trackid}/{minor}', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'ApplicationCampaign.php';
	require 'Report.php';

	$route = $request->getAttribute('route');
    $minor = $route->getArgument('minor');

	$service = new ApplicationCampaign();
	$campaigns = $service->getCampaignForBeaconMinor($minor);

	if($campaigns != null){
		$report = new Report();	
		$report->reportImpressionByCampaign($campaigns['id']);
	}	

	$result = json_encode(array("campanha"=>$campaigns));

    return $response->withHeader('Content-type', $contentType)->write($result);
});

$app->get('/application', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Application.php';
	$service = new Application();
	$campaigns = $service->getAll();


	$result = json_encode(array("list"=>$campaigns));

    return $response->withHeader('Content-type', $contentType)->write($result);
});


$app->put('/application/{id}', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Application.php';
	$route = $request->getAttribute('route');
    $campaignId = $route->getArgument('id');
	$service = new Application();
	$campaigns = $service->updateWithId($campaignId,$request->getBody());
	$result = json_encode($campaigns);
    return $response->withHeader('Content-type', $contentType)->write($result);
});

$app->post('/application', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Application.php';
	$service = new Application();
	$campaigns = $service->add($request->getBody());
	$result = json_encode(array("status"=>$campaigns));
    return $response->withHeader('Content-type', $contentType)->write($result);
});


$app->delete('/application/{id}', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Application.php';
	$route = $request->getAttribute('route');
    $campaignId = $route->getArgument('id');
	$service = new Application();
	$campaigns = $service->delete($campaignId);
	$result = json_encode(array("status"=>$campaigns));

    return $response->withHeader('Content-type', $contentType)->write($result);
});
//beacon

$app->get('/beacon', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Beacon.php';
	$service = new Beacon();
	$objects = $service->getAll();
	$result = json_encode(array("objects"=>$objects));

    return $response->withHeader('Content-type', $contentType)->write($result);
}); 


$app->get('/beacon/{id}', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Beacon.php';
	$route = $request->getAttribute('route');
    $id = $route->getArgument('id');
	$service = new Beacon();
	$objects = $service->getWithId($id);
	$result = json_encode(array("objects"=>$objects));
    return $response->withHeader('Content-type', $contentType)->write($result);
});


$app->put('/beacon/{id}', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Beacon.php';
	$route = $request->getAttribute('route');
    $id = $route->getArgument('id');
	$service = new Beacon();
	$objects = $service->updateWithId($id,$request->getBody());
	$result = json_encode($objects);
    return $response->withHeader('Content-type', $contentType)->write($result);
});

$app->post('/beacon', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Beacon.php';
	$service = new Beacon();
	$objects = $service->add($request->getBody());
	$result = json_encode(array("status"=>$objects));
    return $response->withHeader('Content-type', $contentType)->write($result);
});


$app->delete('/beacon/{id}', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Beacon.php';
	$route = $request->getAttribute('route');
    $id = $route->getArgument('id');
	$service = new Beacon();
	$objects = $service->delete($id);
	$result = json_encode(array("status"=>$objects));

    return $response->withHeader('Content-type', $contentType)->write($result);
});

// fim beacon

//campagin

$app->get('/campaign', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Campaign.php';
	$service = new Campaign();
	$campaigns = $service->getAll();
	$result = json_encode(array("campaigns"=>$campaigns));

    return $response->withHeader('Content-type', $contentType)->write($result);
});

$app->get('/campaign/{trackid}/{bid}', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Campaign.php';
	$route = $request->getAttribute('route');
    $trackid = $route->getArgument('trackid');
    $bid = $route->getArgument('bid');
	$service = new Campaign();
	$objects = $service->getCampaignByBeacon($bid);
	$result = json_encode(array("objects"=>$objects));
    return $response->withHeader('Content-type', $contentType)->write($result);
});

$app->get('/campaign/{id}', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Campaign.php';
	$route = $request->getAttribute('route');
    $campaignId = $route->getArgument('id');
	$service = new Campaign();
	$campaigns = $service->getWithId($campaignId);
	$result = json_encode(array("campaign"=>$campaigns));
    return $response->withHeader('Content-type', $contentType)->write($result);
});

$app->put('/campaign/{id}', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Campaign.php';
	$route = $request->getAttribute('route');
    $campaignId = $route->getArgument('id');
	$service = new Campaign();
	$postdata = $request->getParams();
	
	if(isset($postdata['startdate']) && strtotime($postdata['startdate'])){
		$date = new DateTime($postdata['startdate']);
		$postdata['startdate'] = $date->format('Y-m-d H:i:s');
	}

	if(isset($postdata['enddate']) && strtotime($postdata['enddate'])){
		$date = new DateTime($postdata['enddate']);
		$postdata['enddate'] = $date->format('Y-m-d H:i:s');
	}else{
		unset($postdata['enddate']);
	}

	$campaigns = $service->updateWithId($campaignId, json_encode($postdata));
	$result = json_encode($campaigns);
    return $response->withHeader('Content-type', $contentType)->write($result);

});

$app->post('/campaign', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Campaign.php';
	$service = new Campaign();
	$campaigns = $service->add($request->getBody());
	$result = json_encode(array("status"=>$campaigns));
    return $response->withHeader('Content-type', $contentType)->write($result);
});


$app->delete('/campaign/{id}', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Campaign.php';
	$route = $request->getAttribute('route');
    $campaignId = $route->getArgument('id');
	$service = new Campaign();
	$campaigns = $service->delete($campaignId);
	$result = json_encode(array("status"=>$campaigns));

    return $response->withHeader('Content-type', $contentType)->write($result);
});

$app->get('/report', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	require 'Report.php';
	$report = new Report();
	$result = json_encode(array($report->getBasicDetails()));
    return $response->withHeader('Content-type', $contentType)->write($result);
});


$app->run();

function connect_db() {

    $server = 'localhost';
	$user = 'root';
	$pass = '';
	$database = 'proximitybuy';

	//$user = 'pbuy';
	//$pass = 'w5aruX8PcG7HY2Tf';
	//$pass = '71x7OeAH43sEczRP';

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