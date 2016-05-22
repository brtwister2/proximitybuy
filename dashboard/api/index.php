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

class Campaign {
	function getAllCampaigns(){
		$db = connect_db();
    
		$sql = "select * from campaign";
	    $r = $db->query($sql);
	    $campaigns = array();
	    if ($r !== false) {
	        while ($row = $r->fetch_assoc()) {
	          	$campaigns[] = $row;
	          	
	        }
	    }

	    return $campaigns;
	}

	function getCampaignWithId($id){
		$db = connect_db();
    
		$sql = "select * from campaign where id = $id";
	    $r = $db->query($sql);
	    if ($r !== false) {
	       	return $r->fetch_assoc();
	    }

	    return null;
	}

	function updateCampaignWithId($id,$campaign){
		$db = connect_db();
    	$campaign = json_decode($campaign);
 

		$sql = "update campaign set name = '$campaign->name', title = '$campaign->title', img = '$campaign->img', link = '$campaign->link', big ='$campaign->bid',budget = $campaign->budget  where id = $id";
	    $r = $db->query($sql);
	    if ($r !== false) {
	       	return $r->fetch_assoc();
	    }

	    return null;
	}

	function addCampaign($campaign){
		$db = connect_db();

		$campaign = json_decode($campaign);
    	
		$sql = "INSERT INTO campaign (link,img, name,title,bid,budget) VALUES( '$campaign->link', '$campaign->img', '$campaign->name', '$campaign->title', '$campaign->bid', $campaign->budget)";
	    $r = $db->query($sql);

	    //die($sql);
	    if ($r !== false) {
	       	return true;
	    }

	    return null;
	}
}



$app->get('/hello', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	$service = new Service();
    return $response->withHeader('Content-type', $contentType)->write(json_encode($service->hello()));
});


$app->get('/campaign', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	$service = new Campaign();
	$campaigns = $service->getAllCampaigns();


	$result = json_encode(array("campaigns"=>$campaigns));

    return $response->withHeader('Content-type', $contentType)->write($result);
});

$app->get('/campaign/{id}', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	
	$route = $request->getAttribute('route');
    $campaignId = $route->getArgument('id');

	$service = new Campaign();
	$campaigns = $service->getCampaignWithId($campaignId);

	$result = json_encode(array("campaign"=>$campaigns));

    return $response->withHeader('Content-type', $contentType)->write($result);
});


$app->put('/campaign/{id}', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	$route = $request->getAttribute('route');
    $campaignId = $route->getArgument('id');

	$service = new Campaign();
	$campaigns = $service->updateCampaignWithId($campaignId,$request->getBody());

	$result = json_encode(array("campaign"=>$campaigns));

    return $response->withHeader('Content-type', $contentType)->write($result);
});

$app->post('/campaign', function (ServerRequestInterface $request, ResponseInterface $response) use ($contentType) {
	$service = new Campaign();
	$campaigns = $service->addCampaign($request->getBody());

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