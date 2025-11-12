<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

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
		foreach ($this->object_types as $object_type)
			if (isset($object_type["object_type_id"]))
				$available_object_types[ $object_type["object_type_id"] ] = isset($object_type["name"]) ? $object_type["name"] : null;
		
		return $available_object_types;
	}
	
	public function getObjectTypeOptions() {
		$object_type_options = array();
		foreach ($this->object_types as $object_type) {
			$object_type_options[] = array(
				"value" => isset($object_type["object_type_id"]) ? $object_type["object_type_id"] : null, 
				"label" => isset($object_type["name"]) ? $object_type["name"] : null
			);
		}
		return $object_type_options;
	}
}
?>
