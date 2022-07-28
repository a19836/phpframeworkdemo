<?php
include_once $EVC->getModulePath("object/ObjectUtil", $EVC->getCommonProjectName());
include $EVC->getModulePath("tag/TagUtil", $EVC->getCommonProjectName());

class TagAdminUtil {
	private $CommonModuleAdminUtil;
	private $object_types;
	
	public function __construct($CommonModuleAdminUtil) {
		$this->CommonModuleAdminUtil = $CommonModuleAdminUtil;
	}
	
	public function getMenuSettings() {
		return array(
			"class" => "",
			"menus" => array(
				array(
					"label" => "Tags",
					"menus" => array(
						array(
							"label" => "Tags List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_tags"),
							"title" => "View List of Tags",
							"class" => "",
						),
						array(
							"label" => "Add Tag",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_tag"),
							"title" => "Add new Tag",
							"class" => "",
						),
					)
				),
				array(
					"label" => "Object Tags",
					"menus" => array(
						array(
							"label" => "Object Tags List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_object_tags"),
							"title" => "View List of Object Tags",
							"class" => "",
						),
						array(
							"label" => "Add Object Tag",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_object_tag"),
							"title" => "Add new Object Tag",
							"class" => "",
						),
					)
				),
			)
		);
	}
	
	public function initObjectTags($brokers) {
		$this->object_types = ObjectUtil::getAllObjectTypes($brokers, true);
		$this->object_types = $this->object_types ? $this->object_types : array();
	}
	
	public function getAvailableObjectTypes() {
		$available_object_types = array();
		foreach ($this->object_types as $object_type) {
			$available_object_types[ $object_type["object_type_id"] ] = $object_type["name"];
		}
		return $available_object_types;
	}
	
	public function getObjectTypeOptions() {
		$object_type_options = array();
		foreach ($this->object_types as $object_type) {
			$object_type_options[] = array("value" => $object_type["object_type_id"], "label" => $object_type["name"]);
		}
		return $object_type_options;
	}
}
?>
