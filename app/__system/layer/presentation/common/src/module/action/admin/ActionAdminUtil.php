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
		foreach ($this->actions as $action) 
			if (isset($action["action_id"]))
				$available_actions[ $action["action_id"] ] = isset($action["name"]) ? $action["name"] : null;
			
		return $available_actions;
	}
	
	public function getActionOptions($data) {
		$action_options = array();
		$default_id = isset($data["action_id"]) ? $data["action_id"] : null;
		$exists = false;
		
		foreach ($this->actions as $action) {
			$action_id = isset($action["action_id"]) ? $action["action_id"] : null;
			$action_options[] = array(
				"value" => $action_id, 
				"label" => isset($action["name"]) ? $action["name"] : null
			);
			
			if ($default_id && $action_id == $default_id)
				$exists = true;
		}
		
		if ($default_id && !$exists)
			$action_options[] = array("value" => $default_id, "label" => $default_id);
		
		return $action_options;
	}
}
?>
