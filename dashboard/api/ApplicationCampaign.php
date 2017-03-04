<?php
require_once("BaseModel.php");
class ApplicationCampaign extends BaseModel {

	function __construct() {
   		$this->entity = "campaign_application";    
   	}

   	function getCampaignForBeaconId($bid){
		$db = connect_db();
    
		$sql = "select * from campaign where beaconid = $bid";
	    $r = $db->query($sql);
	    if ($r !== false) {
	       	return $r->fetch_assoc();
	    }

	    return null;
	}

	function getCampaignForBeaconMinor($minor){
		$db = connect_db();
    
		$sql = "select * from campaign where beaconid IN (select id from beacon where minor = $minor)";
	    $r = $db->query($sql);
	    if ($r !== false) {
	       	return $r->fetch_assoc();
	    }

	    return null;
	}



}