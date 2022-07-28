<?php
include_once $EVC->getModulePath("object/ObjectUtil", $EVC->getCommonProjectName());

class ObjectAdminUtil {
	private $CommonModuleAdminUtil;
	
	public function __construct($CommonModuleAdminUtil) {
		$this->CommonModuleAdminUtil = $CommonModuleAdminUtil;
	}
	
	public function getMenuSettings() {
		return array(
			"class" => "",
			"menus" => array(
				array(
					"label" => "Object Types",
					"menus" => array(
						array(
							"label" => "Object Types List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_object_types"),
							"title" => "View List of Object Types",
							"class" => "",
						),
						array(
							"label" => "Add Object Type",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_object_type"),
							"title" => "Add new Object Type",
							"class" => "",
						),
					)
				),
			)
		);
	}
}
?>
