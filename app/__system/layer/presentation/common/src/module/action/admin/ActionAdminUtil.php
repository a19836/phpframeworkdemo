<?php
include $EVC->getModulePath("action/ActionUtil", $EVC->getCommonProjectName());

class ActionAdminUtil {
	private $CommonModuleAdminUtil;
	private $actions;
	
	public function __construct($CommonModuleAdminUtil) {
		$this->CommonModuleAdminUtil = $CommonModuleAdminUtil;
	}
	
	public function getMenuSettings() {
		return array(
			"class" => "",
			"menus" => array(
				array(
					"label" => "Actions",
					"menus" => array(
						array(
							"label" => "Actions List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_actions"),
							"title" => "View List of Actions",
							"class" => "",
						),
						array(
							"label" => "Add Action",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_action"),
							"title" => "Add new Action",
							"class" => "",
						),
					)
				),
				array(
					"label" => "User Actions",
					"menus" => array(
						array(
							"label" => "User Actions List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_user_actions"),
							"title" => "View List of User Actions",
							"class" => "",
						),
						array(
							"label" => "Add User Action",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_user_action"),
							"title" => "Add new User Action",
							"class" => "",
						),
					)
				),
			)
		);
	}
	
	public function initUserActions($brokers) {
		$this->actions = ActionUtil::getAllActions($brokers, true);
		$this->actions = $this->actions ? $this->actions : array();
	}
	
	public function getAvailableActions() {
		$available_actions = array();
		foreach ($this->actions as $action) {
			$available_actions[ $action["action_id"] ] = $action["name"];
		}
		return $available_actions;
	}
	
	public function getActionOptions($data) {
		$action_options = array();
		$default_id = $data ? $data["action_id"] : null;
		$exists = false;
		
		foreach ($this->actions as $action) {
			$action_options[] = array("value" => $action["action_id"], "label" => $action["name"]);
			
			if ($default_id && $action["action_id"] == $default_id)
				$exists = true;
		}
		
		if ($default_id && !$exists)
			$action_options[] = array("value" => $default_id, "label" => $default_id);
		
		return $action_options;
	}
}
?>
