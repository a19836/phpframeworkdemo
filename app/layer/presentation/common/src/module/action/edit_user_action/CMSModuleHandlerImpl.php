<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

namespace CMSModule\action\edit_user_action;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("action/ActionUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting User Actions
		$user_id = isset($_GET["user_id"]) ? $_GET["user_id"] : null;
		$action_id = isset($_GET["action_id"]) ? $_GET["action_id"] : null;
		$object_type_id = isset($_GET["object_type_id"]) ? $_GET["object_type_id"] : null;
		$object_id = isset($_GET["object_id"]) ? $_GET["object_id"] : null;
		$time = isset($_GET["time"]) ? $_GET["time"] : null;
		
		$data = $user_id && $action_id && $object_type_id && $object_id && isset($time) ? \ActionUtil::getUserActionsByConditions($brokers, array("user_id" => $user_id, "action_id" => $action_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time), null, null, true) : null;
		$data = isset($data[0]) ? $data[0] : null;
		
		//Preparing Action
		if (!empty($_POST)) {
			if (!empty($_POST["delete"]) && !empty($settings["allow_deletion"])) {
				$data_user_id = isset($data["user_id"]) ? $data["user_id"] : null;
				$data_action_id = isset($data["action_id"]) ? $data["action_id"] : null;
				$data_object_type_id = isset($data["object_type_id"]) ? $data["object_type_id"] : null;
				$data_object_id = isset($data["object_id"]) ? $data["object_id"] : null;
				$data_time = isset($data["time"]) ? $data["time"] : null;
				
				$status = !$data || \ActionUtil::deleteUserAction($brokers, $data_user_id, $data_action_id, $data_object_type_id, $data_object_id, $data_time);
			}
			else if (!empty($_POST["save"])) {
				$value = isset($_POST["value"]) ? $_POST["value"] : null;
				
				if (!empty($settings["allow_insertion"]) && empty($data)) {
					$user_id = isset($_POST["user_id"]) ? $_POST["user_id"] : null;
					$action_id = isset($_POST["action_id"]) ? $_POST["action_id"] : null;
					$object_type_id = isset($_POST["object_type_id"]) ? $_POST["object_type_id"] : null;
					$object_id = isset($_POST["object_id"]) ? $_POST["object_id"] : null;
					$time = isset($_POST["time"]) ? $_POST["time"] : null;
					
					$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("user_id" => $user_id, "action_id" => $action_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time));
					if ($empty_field_name) {
						$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
					}
					else {
						$new_data = array(
							"user_id" => $user_id,
							"action_id" => $action_id,
							"object_type_id" => $object_type_id,
							"object_id" => $object_id,
							"time" => $time,
							"value" => $value,
						);
						
						\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
						
						if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
							$status = \ActionUtil::insertUserAction($brokers, $new_data);
							if (isset($settings["on_insert_ok_action"]) && strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (isset($settings["on_insert_ok_redirect_url"]) && strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "user_id=$user_id&action_id=$action_id&object_type_id=$object_type_id&object_id=$object_id&time=$time";
							}
						}
					}
				}
				else if (!empty($settings["allow_update"]) && $data) {
					if (\CommonModuleUI::checkIfEmptyField($settings, "value", $value)) {
						$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, "value");
					}
					else {
						$new_data = $data;
						$new_data["value"] = $value;
						
						\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
						
						if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
							$status = \ActionUtil::updateUserAction($brokers, $new_data);
						}
					}
				}
			}
		}
		
		if (!empty($_POST["save"])) {
			$form_data = array(
				"user_id" => !empty($settings["show_user_id"]) ? $user_id : (isset($data["user_id"]) ? $data["user_id"] : null),
				"action_id" => !empty($settings["show_action_id"]) ? $action_id : (isset($data["action_id"]) ? $data["action_id"] : null),
				"object_type_id" => !empty($settings["show_object_type_id"]) ? $object_type_id : (isset($data["object_type_id"]) ? $data["object_type_id"] : null),
				"object_id" => !empty($settings["show_object_id"]) ? $object_id : (isset($data["object_id"]) ? $data["object_id"] : null),
				"time" => !empty($settings["show_time"]) ? $time : (isset($data["time"]) ? $data["time"] : null),
				"value" => !empty($settings["show_value"]) ? (isset($value) ? $value : null) : (isset($data["value"]) ? $data["value"] : null),
			);
			$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : (!empty($settings["allow_view"]) && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else
			$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/action/edit_user_action.css';
		$settings["class"] = "module_edit_user_action";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		$is_insertion = !empty($settings["allow_insertion"]) && !$data;
		
		if (!empty($settings["show_action_id"])) {
			$actions = \ActionUtil::getAllActions($brokers);
			$action_options = array();
			$available_actions = array();
			
			if ($actions) {
				$t = count($actions);
				for ($i = 0; $i < $t; $i++) {
					$item_action_id = isset($actions[$i]["action_id"]) ? $actions[$i]["action_id"] : null;
					$item_action_name = isset($actions[$i]["name"]) ? $actions[$i]["name"] : null;
					
					if ($is_insertion) 
						$action_options[] = array("value" => $item_action_id, "label" => $item_action_name);
					else
						$available_actions[$item_action_id] = $item_action_name;
				}
			}
			
			$settings["fields"]["action_id"]["field"]["input"]["type"] = $is_insertion ? "select" : "label";
			$settings["fields"]["action_id"]["field"]["input"]["options"] = $action_options;
			$settings["fields"]["action_id"]["field"]["input"]["available_values"] = $available_actions;
		}
		
		if (!empty($settings["show_user_id"])) 
			\CommonModuleUtil::prepareUserIdFormSettingsField($EVC, $settings, $is_insertion);
		
		if (!empty($settings["show_object_type_id"]))
			\CommonModuleUtil::prepareObjectTypeIdFormSettingsField($EVC, $settings, $is_insertion);
		
		if (!empty($settings["show_object_id"]))
			$settings["fields"]["object_id"]["field"]["input"]["type"] = $is_insertion ? "text" : "label";
		
		if (!empty($settings["show_time"]))
			$settings["fields"]["time"]["field"]["input"]["type"] = $is_insertion ? "text" : "label";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "action/edit_user_action", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
