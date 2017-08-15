<?php
class mysql extends PDO{
	public $server;
	public $database;
	public $user;
	public $password;
	public $sql;
	public function __construct($server,$database,$user,$password,$port=3306){
		$this->server = $server;
		$this->database = $database;
		$this->user = $user;
		$this->password = $password;
		parent::__construct("mysql:host=$server;port=$port;dbname=$database",$user,$password);
		$this->query('SET NAMES utf8');
	}
	public function drop($table){
		$sql = 'DROP TABLE '.$table.';';
		$re = $this->query($sql);
		if($re){
			return true;
		}else{
			return false;
		}
	}
	public function insert($table,$name,$value=null){
		$sql = "INSERT INTO ".$table.'(';
		if($value == null){
		$arrname = array_keys($name);
		$arrvalue = array_values($name);
		}else{
		$arrname = explode('|', $name);
		$arrvalue = explode('|', $value);
		}
		for($i=0;$i<count($arrname);$i++){
			if($i==count($arrname)-1){
				$sql = $sql.$arrname[$i];
			}else{
				$sql = $sql.$arrname[$i].",";
			}
		}
		$sql = $sql.")VALUES(";
		for($i=0;$i<count($arrvalue);$i++){
			if($i==count($arrvalue)-1){
				$sql = $sql."'".$arrvalue[$i]."'";
			}else{
				$sql = $sql."'".$arrvalue[$i]."',";
			}
		}
		$sql .=");";
		$re = $this->query($sql);
		if($re){
			return true;
		}else{
			return false;
		}
	}
	public function delete($table,$Conditionsname,$Conditionsvalue=null){
		if($Conditionsvalue!=null){
			$sql = "DELETE FROM ".$table." WHERE ".$Conditionsname."='".$Conditionsvalue."';";
		}else{
			$sql = "DELETE FROM ".$table." WHERE ";
			$arrname = array_keys($Conditionsname);
			$arrvalue = array_values($Conditionsname);
			for($i=0;$i<count($arrname);$i++){
				if($i==count($arrname)-1){
					$sql.=$arrname[$i].'='."'".$arrvalue[$i]."'";
				}else{
					$sql.=$arrname[$i].'='."'".$arrvalue[$i]."',";
				}
			}
			$sql.=';';
		}
		$re = $this->query($sql);
		if($re){
			return true;
		}else{
			return false;
		}
	}
	public function select($table,$name,$Conditionsname,$Conditionsvalue=null){
		if($Conditionsvalue!=null){
			$sql = "SELECT ".$name." FROM ".$table." WHERE ".$Conditionsname."='".$Conditionsvalue."';";
		}else{
			$sql = "SELECT ".$name." FROM ".$table." WHERE ";
			$arrname = array_keys($Conditionsname);
			$arrvalue = array_values($Conditionsname);
			for($i=0;$i<count($arrname);$i++){
				if($i==count($arrname)-1){
					$sql.=$arrname[$i].'='."'".$arrvalue[$i]."'";
				}else{
					$sql.=$arrname[$i].'='."'".$arrvalue[$i]."' and ";
				}
			}
			$sql.=';';
		}
		$re = $this->query($sql);
		$row = $re->fetch();
		return $row[$name];
	}
	public function update($table,$name,$value,$Conditionsname,$Conditionsvalue=null){
		if($Conditionsvalue!=null){
			$sql = "UPDATE ".$table." SET ".$name."= '".$value."' WHERE ".$Conditionsname."='".$Conditionsvalue."';";
		}else{
			$sql = "UPDATE ".$table." SET ".$name."= '".$value."' WHERE ";
			$arrname = array_keys($Conditionsname);
			$arrvalue = array_values($Conditionsname);
			for($i=0;$i<count($arrname);$i++){
				if($i==count($arrname)-1){
					$sql.=$arrname[$i].'='."'".$arrvalue[$i]."'";
				}else{
					$sql.=$arrname[$i].'='."'".$arrvalue[$i]."' and ";
				}
			}
			$sql.=';';
		}
		$re = $this->query($sql);
		if($re){
			return true;
		}else{
			return false;
		}
	}
	public function group($table,$name){
		$sql = "SELECT ".$name." FROM ".$table.";";
		$return = array();
		$re = $this->query($sql);
		while($row = $re->fetch(PDO::FETCH_ASSOC)){
			array_push($return,$row[$name]);
		}
		return $return;
	}
	public function fetchall($sql){
		$return = array();
		$re = $this->query($sql);
		while($row = $re->fetch(PDO::FETCH_ASSOC)){
			array_push($return,$row);
		}
		return $return;
	}
}