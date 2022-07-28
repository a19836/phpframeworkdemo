<?php
include_once get_lib("org.phpframework.object.ObjType");

class ItemTest extends ObjType {
	private $status;
	public $test;
	
	public function __construct() {
		$this->data = array();
	}
	
	public function setData($data) {
		parent::setData($data);

		if(is_array($data) && isset($data["status"])) { 
			$this->status = $data["status"]; 
		}
	}
	
	public function getData() {
		$this->data["status"] = $this->status;
		
		return parent::getData();
	}
	
	public function setRownum($rownum) {$this->data["rownum"] = $rownum;}
	public function getRownum() {return $this->data["rownum"];}

	public function setType($type) {$this->data["type"] = $type;}
	public function getType() {return $this->data["type"];}
	
	public function setId($id) {$this->data["id"] = $id;}
	public function getId() {return $this->data["id"];}

	public function setTitle($title) {$this->data["title"] = $title;}
	public function getTitle() {return $this->data["title"];}
	
	public function setStatus($status) {$this->status = $status;}
	public function getStatus() {return $this->status;}
}
?>
