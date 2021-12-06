<?php
include $EVC->getModulePath("event/EventUtil", $EVC->getCommonProjectName());
include $EVC->getModulePath("zip/ZipUtil", $EVC->getCommonProjectName());

class EventAdminUtil {
	private $CommonModuleAdminUtil;
	private $object_types;
	private $countries;
	
	public function __construct($CommonModuleAdminUtil) {
		$this->CommonModuleAdminUtil = $CommonModuleAdminUtil;
	}
	
	public function getMenuSettings() {
		return array(
			"class" => "",
			"menus" => array(
				array(
					"label" => "Events",
					"menus" => array(
						array(
							"label" => "Events List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_events"),
							"title" => "View List of Events",
							"class" => "",
						),
						array(
							"label" => "Add Event",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_event"),
							"title" => "Add new Event",
							"class" => "",
						),
						array(
							"label" => "Extra Attributes",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("manage_event_extra_attributes"),
							"title" => "Manage Event Extra Attributes",
							"class" => "",
						),
					)
				),
				array(
					"label" => "Object Events",
					"class" => "large",
					"menus" => array(
						array(
							"label" => "Object Events List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_object_events"),
							"title" => "View List of Object Events",
							"class" => "",
						),
						array(
							"label" => "Add Object Event",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_object_event"),
							"title" => "Add new Object Event",
							"class" => "",
						),
					)
				),
			)
		);
	}
	
	public function initObjectEvents($brokers) {
		$this->object_types = ObjectUtil::getAllObjectTypes($brokers, true);
		$this->object_types = $this->object_types ? $this->object_types : array();
		
		$this->countries = ZipUtil::getAllCountries($brokers, false, true);
		$this->countries = $this->countries ? $this->countries : array();
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
	
	public function getCountryOptions() {
		$country_options = array();
		foreach ($this->countries as $country) {
			$country_options[] = array("value" => $country["country_id"], "label" => $country["name"]);
		}
		return $country_options;
	}
}
?>
