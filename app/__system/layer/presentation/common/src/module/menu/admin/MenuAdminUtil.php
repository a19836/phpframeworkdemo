<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

include_once $EVC->getModulePath("menu/MenuUtil", $EVC->getCommonProjectName());
include_once $EVC->getModulePath("object/ObjectUtil", $EVC->getCommonProjectName());

class MenuAdminUtil {
	private $CommonModuleAdminUtil;
	private $groups;
	private $items;
	private $object_types;
	
	public function __construct($CommonModuleAdminUtil) {
		$this->CommonModuleAdminUtil = $CommonModuleAdminUtil;
	}
	
	public function getMenuSettings() {
		return array(
			"class" => "",
			"menus" => array(
				array(
					"label" => "Menu Groups",
					"menus" => array(
						array(
							"label" => "Menu Groups List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_menu_groups"),
							"title" => "View List of Menu Groups",
							"class" => "",
						),
						array(
							"label" => "Add Menu Group",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_menu_group"),
							"title" => "Add new Menu Group",
							"class" => "",
						),
					)
				),
				array(
					"label" => "Menu Items",
					"menus" => array(
						array(
							"label" => "Menu Items List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_menu_items"),
							"title" => "View List of Menu Items",
							"class" => "",
						),
						array(
							"label" => "Add Menu Item",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_menu_item"),
							"title" => "Add new Menu Item",
							"class" => "",
						),
					)
				),
				array(
					"label" => "Menu Object Groups",
					"class" => "large",
					"menus" => array(
						array(
							"label" => "Menu Object Groups List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_menu_object_groups"),
							"title" => "View List of Menu Object Groups",
							"class" => "",
						),
						array(
							"label" => "Add Menu Object Group",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_menu_object_group"),
							"title" => "Add new Menu Object Group",
							"class" => "",
						),
					)
				),
			)
		);
	}
	
	public function initMenuGroups($brokers) {
		$this->groups = MenuUtil::getAllMenuGroups($brokers, true);
		$this->groups = $this->groups ? $this->groups : array();
	}
	
	public function initMenuItems($brokers) {
		$this->items = MenuUtil::getAllMenuItems($brokers);
		$this->items = $this->items ? $this->items : array();
	}
	
	public function initMenuObjectGroups($brokers) {
		$this->object_types = ObjectUtil::getAllObjectTypes($brokers, true);
		$this->object_types = $this->object_types ? $this->object_types : array();
	}
	
	public function getAvailableGroups() {
		$available_groups = array();
		foreach ($this->groups as $group)
			if (isset($group["group_id"]))
				$available_groups[ $group["group_id"] ] = isset($group["name"]) ? $group["name"] : null;
		
		return $available_groups;
	}
	
	public function getAvailableItems() {
		$available_items = array();
		foreach ($this->items as $item)
			if (isset($item["item_id"]))
				$available_items[ $item["item_id"] ] = $item["item_id"] . "- " . (isset($item["label"]) ? $item["label"] : null);
		
		return $available_items;
	}
	
	public function getAvailableObjectTypes() {
		$available_object_types = array();
		foreach ($this->object_types as $object_type)
			if (isset($object_type["object_type_id"]))
				$available_object_types[ $object_type["object_type_id"] ] = isset($object_type["name"]) ? $object_type["name"] : null;
		
		return $available_object_types;
	}
	
	public function getGroupOptions() {
		$group_options = array();
		foreach ($this->groups as $group)
			$group_options[] = array(
				"value" => isset($group["group_id"]) ? $group["group_id"] : null, 
				"label" => isset($group["name"]) ? $group["name"] : null
			);
		
		return $group_options;
	}
	
	public function getItemOptions() {
		$item_options = array(
			array("value" => 0, "label" => "")
		);
		
		foreach ($this->items as $item) {
			$item_id = isset($item["item_id"]) ? $item["item_id"] : null;
			
			$item_options[] = array(
				"value" => $item_id, 
				"label" => $item_id . "- " . (isset($item["label"]) ? $item["label"] : null)
			);
		}
		
		return $item_options;
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
