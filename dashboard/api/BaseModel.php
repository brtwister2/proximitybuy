<?php

require_once("BaseModel.php");
require_once("Upload.php");

class BaseModel{

	var $entity;
	var $fields = "*";
	var $primaryKey = "id";
	var $conditions = "1=1 ";
	var $joins = "";
	var $order = "";
	var $quantidade = 10;
	var $pagina = 1;
	var $result = array("status"=> true, "msg"=> "ok");

	function getAll(){
		$db = connect_db();
		$start = ($this->quantidade * ($this->pagina - 1)) ;
		$sql = "select $this->fields from $this->entity $this->joins where $this->conditions $this->order limit $start, $this->quantidade";
	    $r = $db->query($sql);

	    $results = array();
	    if ($r !== false) {
	        while ($row = $r->fetch_assoc()) {
	          	$results[] = $row; 	
	        }
	    }
		//die($sql);
	    //die($db->error);
	    $db->close();
	    return $results;
	}

	function getSingleAll(){
		$db = connect_db();
    
		$sql = "select $this->fields from $this->entity where $this->conditions";
	    $r = $db->query($sql);
	    
	    $results = array();
	    if ($r !== false) {
	        while ($row = $r->fetch_assoc()) {
	          	$results[] = $row[$this->fields]; 	
	        }
	    }
	    $db->close();
	    return $results;
	}

	function getWithId($id){
		$db = connect_db();
    
		$sql = "select $this->fields from $this->entity $this->joins where $this->primaryKey = $id and $this->conditions";
	    $r = $db->query($sql);

	    //die("ddddd");
	    //print_r("/n<br>");
	    if ($r !== false) {
	       	return $r->fetch_assoc();
	    }
	    //die($db->error);
	    $db->close();

	    return null;
	}

	function updateWithId($id,$data){
		$db = connect_db();
    	$data = json_decode($data);
    	$fields = "";
    	foreach ($data as $key => $value) {
			if ($fields != "") $fields .= ",";

			if ($key == "img") {
				$upload = new Upload();
				$nome = $upload->uploadImage($value);
				if ($nome !== false) {
					$value = $nome;	
				}
				$fields .= "$key = '$value'";
			}else{

				if (!is_numeric($value)) {
					$fields .= "$key = '$value'";
				}else{
					$fields .= "$key = $value";
				}
			}
		}
 
		$sql = "update $this->entity set $fields where $this->primaryKey = $id";
	    $r = $db->query($sql);
	    //die($sql);
	    //die($db->error);
	    if ($r === false) {	   
	    	$this->result["status"] = false;
	    	$this->result["msg"] = $db->error;

	    	$error = "UpdateUser-".$db->error;
  			$sql = "INSERT INTO TB_SISTEM_LOG (ERRO) VALUES( '$error' )";
  			$r = $db->query($sql);
	    }

	    $db->close();

	    return $this->result;
	}

	function addWithUser($data){
    	$data = json_decode($data);
    	$data->cod_user = getCod(apache_request_headers()['Authorization']);
    	return $this->add(json_encode($data));
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

			if ($key == "img") {
				$upload = new Upload();
				$nome = $upload->uploadImage($value);
				if ($nome !== false) {
					$value = $nome;	
				}

				$values .="'$value'";
				$fields .= "$key";
			}else{

				if (!is_numeric($value)) {
					$values .="'$value'";
				}else{
					$values .= $value;
				}

				$fields .= "$key";
			}
		}

		$sql = "INSERT INTO $this->entity ($fields) VALUES($values)";
	   	$r = $db->query($sql);

	   	//die($db->error);
	   	//die($sql);
	   	//var_dump($r);
	    if ($r !== false) {
	    	$lastInsertId = mysqli_insert_id ( $db );
	    	$db->close();
	       	return $lastInsertId;
	    }

	    return $db->error;
	}

	function delete($id){
		$db = connect_db();

		$sql = "delete from $this->entity where $this->primaryKey = $id and $this->conditions";
	    $r = $db->query($sql);
	    //print_r($sql);
	    $db->close();
	    if ($r !== false) {
	       	return true;
	    }

	    return false;
	}


}