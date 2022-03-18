<?php
include_once get_lib("org.phpframework.object.ObjType");

class MySubItem extends ObjType {
	public function __construct() {
		
	}
	
	//By default the system already creates these methods:
	
	//public function setId($id) {$this->data["id"] = $id;}
	//public function getId() {return $this->data["id"];}
	
	//public function setItemId($item_id) {$this->data["item_id"] = $item_id;}
	//public function getItemId() {return $this->data["item_id"];}
	
	//public function setTitle($title) {$this->data["title"] = $title;}
	//public function getTitle() {return $this->data["title"];}
}
?>