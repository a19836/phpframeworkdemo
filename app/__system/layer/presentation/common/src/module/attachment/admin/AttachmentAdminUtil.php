<?php
include_once $EVC->getModulePath("object/ObjectUtil", $EVC->getCommonProjectName());
include $EVC->getModulePath("attachment/AttachmentUtil", $EVC->getCommonProjectName());

class AttachmentAdminUtil {
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
					"label" => "Attachments",
					"menus" => array(
						array(
							"label" => "Attachments List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_attachments"),
							"title" => "View List of Attachments",
							"class" => "",
						),
						array(
							"label" => "Add Attachment",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_attachment"),
							"title" => "Add new Attachment",
							"class" => "",
						),
					)
				),
				array(
					"label" => "Object Attachments",
					"class" => "large",
					"menus" => array(
						array(
							"label" => "Object Attachments List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_object_attachments"),
							"title" => "View List of Object Attachments",
							"class" => "",
						),
						array(
							"label" => "Add Object Attachment",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_object_attachment"),
							"title" => "Add new Object Attachment",
							"class" => "",
						),
					)
				),
				array(
					"label" => "Settings",
					"class" => "settings",
					"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_settings"),
					"title" => "Edit this module settings",
				),
			)
		);
	}
	
	public function initObjectAttachments($brokers) {
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
