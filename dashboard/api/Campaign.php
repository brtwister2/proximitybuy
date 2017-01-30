<?php
require_once("BaseModel.php");

class Campaign extends BaseModel {

	function __construct() {
   		$this->entity = "campaign c";    
   	}

   	function getCampaignByBeacon($bid){

   		$this->joins = "inner join beacon b on b.id = c.beaconid";
   		$this->conditions = "b.bid = '$bid'";

   		return parent::getAll();

   	}

}