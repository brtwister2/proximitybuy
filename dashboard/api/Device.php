<?php

require_once("BaseModel.php");

class Device extends BaseModel {

	function __construct() {
   	$this->entity = "device";    
   }
   
   function add($data){
      $db = connect_db();
      $data = json_decode($data);

      $trackid = $data->trackid;
    
      $sql = "select * from application where trackid = '$trackid'";
      $r = $db->query($sql);

      if ($r !== false) {
         $obj = $r->fetch_assoc();
      }else {
         return false;
      }

      unset($data->trackid);
      $data->appid = $obj["id"];
      $db->close();

      //die();

      return parent::add(json_encode($data));

   }

}