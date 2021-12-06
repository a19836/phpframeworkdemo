<?php
include_once get_lib("org.phpframework.object.ObjType");

class MyItem extends ObjType {
	public function __construct() {
		
	}
	
	public function getId() {return $this->data["id"];}
	public function getName() {return $this->data["name"];}
	public function getStatus() {return $this->data["status"];}
}
?>
