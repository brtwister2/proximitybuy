<?php
require_once("BaseModel.php");

class Campaign extends BaseModel {

	function __construct() {
   		$this->entity = "campaign";    
   	}

	function updateWithId($id,$campaign){
		$db = connect_db();
    	$campaign = json_decode($campaign);
 

		$sql = "update campaign set name = '$campaign->name', title = '$campaign->title', img = '$campaign->img', link = '$campaign->link', big ='$campaign->bid',description = '$campaign->description',budget = $campaign->budget  where id = $id";
	    $r = $db->query($sql);
	    if ($r !== false) {
	       	return $r->fetch_assoc();
	    }

	    return null;
	}

	function add($campaign){
		$db = connect_db();

		$campaign = json_decode($campaign);
    	
		$sql = "INSERT INTO campaign (link,img, name,title,bid,description,budget) VALUES( '$campaign->link', '$campaign->img', '$campaign->name', '$campaign->title', '$campaign->bid', '$campaign->description', $campaign->budget)";
	    $r = $db->query($sql);

	    //die($sql);
	    if ($r !== false) {
	       	return true;
	    }

	    return null;
	}

	function delete($id){
		$db = connect_db();

		$sql = "delete from campaign where id = $id";
	    $r = $db->query($sql);

	    if ($r !== false) {
	       	return true;
	    }

	    return null;
	}
}