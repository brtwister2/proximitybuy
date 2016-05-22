<?php

require_once("BaseModel.php");

class Application extends BaseModel {

	function __construct() {
   		$this->entity = "Application";    
   	}

   	function add($data){

   		include "crawller.php";

   		$data = json_decode($data);

   		$data->icon  = crawStore($data->platform,$data->appid);

   		$data->trackid = $this->GUID( );

   		$data = json_encode($data);


   		return parent::add($data);

   	}

   	private function GUID()
	{
	    if (function_exists('com_create_guid') === true)
	    {
	        return trim(com_create_guid(), '{}');
	    }

	    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}

}