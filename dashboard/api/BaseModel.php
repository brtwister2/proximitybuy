<?php

class BaseModel{

	var $entity;
	var $result = array("status"=> true, "msg"=> "ok");

	function getAll(){
		$db = connect_db();
    
		$sql = "select * from $this->entity";
	    $r = $db->query($sql);
	    $results = array();
	    if ($r !== false) {
	        while ($row = $r->fetch_assoc()) {
	          	$results[] = $row; 	
	        }
	    }

	    return $results;
	}

	function getWithId($id){
		$db = connect_db();
    
		$sql = "select * from $this->entity where id = $id";
	    $r = $db->query($sql);
	    if ($r !== false) {
	       	return $r->fetch_assoc();
	    }

	    return null;
	}

	function updateWithId($id,$campaign){
		$db = connect_db();
    	$campaign = json_decode($campaign);
 
		$sql = "update campaign set name = '$campaign->name', title = '$campaign->title', img = '$campaign->img', link = '$campaign->link', bid ='$campaign->bid',description = '$campaign->description',budget = $campaign->budget  where id = $id";
	    $r = $db->query($sql);

	    if ($r === false) {	   
	    	$this->result["status"] = false;
	    	$this->result["msg"] = $db->error;
	    }

	    return $this->result;
	}

	function add($data){
		$db = connect_db();

		$data = json_decode($data);

		$values = "";
		$fields = "";
		foreach ($data as $key => $value) {
			if ($fields != "") {
				$fields .= ",";
				$values .= ",";
			}

			if (!is_numeric($value)) {
				$values .="'$value'";
			}else{
				$values .= $value;
			}

			$fields .= "$key";
		}

		$sql = "INSERT INTO campaign ($fields) VALUES($values)";
	   	$r = $db->query($sql);

	    if ($r !== false) {
	       	return true;
	    }

	    return $db->error;
	}

	function delete($id){
		$db = connect_db();

		$sql = "delete from $this->entity where id = $id";
	    $r = $db->query($sql);

	    if ($r !== false) {
	       	return true;
	    }

	    return null;
	}


}