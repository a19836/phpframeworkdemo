<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

namespace CMSModule\action\edit_action;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("action/ActionUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Action Details
		$action_id = isset($_GET["action_id"]) ? $_GET["action_id"] : null;
		$data = $action_id ? \ActionUtil::getActionsByConditions($brokers, array("action_id" => $action_id), null, true) : null;
		$data = isset($data[0]) ? $data[0] : null;
		
		//Preparing Action
		if (!empty($_POST)) {
			if (!empty($_POST["delete"]) && !empty($settings["allow_deletion"])) {
				$data_action_id = isset($data["action_id"]) ? $data["action_id"] : null;
				
				$status = !$data || \ActionUtil::deleteAction($brokers, $data_action_id);
			}
			else if (!empty($_POST["save"])) {
				$name = isset($_POST["name"]) ? $_POST["name"] : null;
				
				if (\CommonModuleUI::checkIfEmptyField($settings, "name", $name)) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, "name");
				}
				else {
					$new_data = $data;
					$new_data["name"] = !empty($settings["show_name"]) ? $name : (isset($new_data["name"]) ? $new_data["name"] : null);
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						if (!empty($settings["allow_insertion"]) && empty($data["action_id"])) {
							$status = \ActionUtil::insertAction($brokers, $new_data);
							if (isset($settings["on_insert_ok_action"]) && strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (isset($settings["on_insert_ok_redirect_url"]) && strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "action_id=$status";
							}
						}
						else if (!empty($settings["allow_update"]) && !empty($data["action_id"])) {
							$status = \ActionUtil::updateAction($brokers, $new_data);
						}
					}
				}
			}
		}
		
		if (!empty($_POST["save"])) {
			$form_data = array(
				"action_id" => !empty($settings["show_action_id"]) ? $action_id : $data["action_id"],
				"name" => !empty($settings["show_name"]) ? (isset($name) ? $name : null) : $data["name"],
			);
			$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : (!empty($settings["allow_view"]) && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/action/edit_action.css';
		$settings["class"] = "module_edit_action";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		$is_insertion = !empty($settings["allow_insertion"]) && !$data;
		
		if (!empty($settings["show_action_id"]))
			$settings["fields"]["action_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "action/edit_action", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
