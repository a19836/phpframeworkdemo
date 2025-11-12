<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

include_once $EVC->getModulePath("objectsgroup/ObjectsGroupUtil", $EVC->getCommonProjectName());

class ObjectsGroupAdminUtil {
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
					"label" => "Objects Groups",
					"menus" => array(
						array(
							"label" => "Objects Groups List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_objects_groups"),
							"title" => "View List of Objects Groups",
							"class" => "",
						),
						array(
							"label" => "Add Objects Group",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_objects_group"),
							"title" => "Add new Objects Group",
							"class" => "",
						),
					)
				),
				array(
					"label" => "Object Objects Groups",
					"class" => "large",
					"menus" => array(
						array(
							"label" => "Object Objects Groups List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_object_objects_groups"),
							"title" => "View List of Object Objects Groups",
							"class" => "",
						),
						array(
							"label" => "Add Object Objects Group",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_object_objects_group"),
							"title" => "Add new Object Objects Group",
							"class" => "",
						),
					)
				),
			)
		);
	}
	
	public function initObjectObjectsGroups($brokers) {
		$this->object_types = ObjectUtil::getAllObjectTypes($brokers, true);
		$this->object_types = $this->object_types ? $this->object_types : array();
	}
	
	public function getAvailableObjectTypes() {
		$available_object_types = array();
		foreach ($this->object_types as $object_type)
			if (isset($object_type["object_type_id"]))
				$available_object_types[ $object_type["object_type_id"] ] = isset($object_type["name"]) ? $object_type["name"] : null;
		
		return $available_object_types;
	}
	
	public function getObjectTypeOptions() {
		$object_type_options = array();
		foreach ($this->object_types as $object_type)
			$object_type_options[] = array(
				"value" => isset($object_type["object_type_id"]) ? $object_type["object_type_id"] : null, 
				"label" => isset($object_type["name"]) ? $object_type["name"] : null
			);
		
		return $object_type_options;
	}
}
?>
