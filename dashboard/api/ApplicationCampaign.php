<?php
require_once("BaseModel.php");
class ApplicationCampaign extends BaseModel {

	function __construct() {
   		$this->entity = "campaign_application";    
   	}

   	function getCampaignForBeaconId($bid){
		$db = connect_db();
    
		$sql = "select * from campaign where bid = $bid";
	    $r = $db->query($sql);
	    if ($r !== false) {
	       	return $r->fetch_assoc();
	    }

	    return null;
	}



}