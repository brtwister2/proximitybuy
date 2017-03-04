<?php
require_once("BaseModel.php");
class Report extends BaseModel {

	function __construct() {
   		$this->entity = "report_impression_by_campaign";    
   	}


	function getBasicDetails(){
		$db = connect_db();
    
		$sql = "select * from report_impression_by_campaign JOIN campaign on campaign.id = cid;";
	    $r = $db->query($sql);
	    if ($r !== false) {

	    	$top_campaigns_arr = [];
	    	$campaigns_date_arr = [];
	    	$top_countries_arr = [];
	    	$top_campaigns_total = 0;

	       	while($row = $r->fetch_assoc()){

	       		$cc = strtolower($row['countryCode']);
	       		$cn = $row['name'];

	       		if(!isset($top_campaigns_arr[$cn])) $top_campaigns_arr[$cn] = 0;
	       		if(!isset($top_countries_arr[$cc])) $top_countries_arr[$cc] = ['name' => '', 'count' => 0];


	       		$campaigns_date_arr[$cn][$row['created_at']] = 1;
	       		$top_campaigns_date_arr = [];
	       		$top_campaigns_arr[$cn] += 1;
	       		$top_countries_arr[$cc]['name'] = $row['country'];
	       		$top_countries_arr[$cc]['count'] += 1;
	       		$top_campaigns_total++;
	       	}
	    }

		asort($top_campaigns_arr);
		asort($top_countries_arr);
		
	    return [
	    	'top_campaigns' => array_slice($top_campaigns_arr, 0, 4),
	    	'top_countries' => array_slice($top_countries_arr, 0, 4),
	    	'dataset' => $campaigns_date_arr,
	    	'top_campaigns_total' => $top_campaigns_total
	    ];

	}


	function reportImpressionByCampaign($cid){
		$db = connect_db();
    	
		$ip = $this->getUserIP(); // the IP address to query
		$ip = '138.197.118.32';
		$data = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
		if($data && $data['status'] == 'success') {

		  	$data['cid'] = $cid;
		  	$data['ip'] = $ip;
		  	$as = $data['as'];
		  	$data['ascol'] = $as;
		  	unset($data['as']);
		  	unset($data['status']);
		  	unset($data['query']);
		  	$this->add(json_encode($data));
		}

	}

	function getUserIP(){

	    $client  = @$_SERVER['HTTP_CLIENT_IP'];
	    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	    $remote  = $_SERVER['REMOTE_ADDR'];

	    if(filter_var($client, FILTER_VALIDATE_IP))
	    {
	        $ip = $client;
	    }
	    elseif(filter_var($forward, FILTER_VALIDATE_IP))
	    {
	        $ip = $forward;
	    }
	    else
	    {
	        $ip = $remote;
	    }

	    return $ip;
	}	



}