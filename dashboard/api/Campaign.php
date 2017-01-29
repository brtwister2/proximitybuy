<?php
require_once("BaseModel.php");

class Campaign extends BaseModel {

	function __construct() {
   		$this->entity = "campaign";    
   	}

   	function listBeacons(){


   		return parent::getAll();
   	}

}