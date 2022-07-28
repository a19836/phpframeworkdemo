<?php
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
		foreach ($this->groups as $group) {
			$available_groups[ $group["group_id"] ] = $group["name"];
		}
		return $available_groups;
	}
	
	public function getAvailableItems() {
		$available_items = array();
		foreach ($this->items as $item) {
			$available_items[ $item["item_id"] ] = $item["item_id"] . "- " . $item["label"];
		}
		return $available_items;
	}
	
	public function getAvailableObjectTypes() {
		$available_object_types = array();
		foreach ($this->object_types as $object_type) {
			$available_object_types[ $object_type["object_type_id"] ] = $object_type["name"];
		}
		return $available_object_types;
	}
	
	public function getGroupOptions() {
		$group_options = array();
		foreach ($this->groups as $group) {
			$group_options[] = array("value" => $group["group_id"], "label" => $group["name"]);
		}
		return $group_options;
	}
	
	public function getItemOptions() {
		$item_options = array(
			array("value" => 0, "label" => "")
		);
		
		foreach ($this->items as $item) {
			$item_options[] = array("value" => $item["item_id"], "label" => $item["item_id"] . "- " . $item["label"]);
		}
		return $item_options;
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
