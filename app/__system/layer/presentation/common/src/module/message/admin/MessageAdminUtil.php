<?php
include_once $EVC->getModulePath("message/MessageUtil", $EVC->getCommonProjectName());

class MessageAdminUtil {
	private $CommonModuleAdminUtil;
	
	public function __construct($CommonModuleAdminUtil) {
		$this->CommonModuleAdminUtil = $CommonModuleAdminUtil;
	}
	
	public function getMenuSettings() {
		return array(
			"class" => "",
			"menus" => array(
				array(
					"label" => "Messages",
					"menus" => array(
						array(
							"label" => "Messages List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_messages"),
							"title" => "View List of Messages",
							"class" => "",
						),
						array(
							"label" => "Add Message",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_message"),
							"title" => "Add new Message",
							"class" => "",
						),
					)
				),
			)
		);
	}
}
?>
