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

namespace CMSModule\user\edit_user_user_type;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		include_once $EVC->getModulePath("user/UserModuleUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting User User Type
		$user_id = isset($_GET["user_id"]) ? $_GET["user_id"] : null;
		$user_type_id = isset($_GET["user_type_id"]) ? $_GET["user_type_id"] : null;
		
		$data = $user_id && $user_type_id ? \UserUtil::getUserUserTypesByConditions($brokers, array("user_id" => $user_id, "user_type_id" => $user_type_id), null, true) : null;
		$data = isset($data[0]) ? $data[0] : null;
		
		//Preparing Action
		if (!empty($_POST)) {
			if (!empty($_POST["delete"]) && !empty($settings["allow_deletion"])) {
				$data_user_id = isset($data["user_id"]) ? $data["user_id"] : null;
				$data_user_type_id = isset($data["user_type_id"]) ? $data["user_type_id"] : null;
				
				$status = !$data || \UserUtil::deleteUserUserType($brokers, $data_user_id, $data_user_type_id);
			}
			else if (!empty($_POST["save"])) {
				$new_user_id = isset($_POST["user_id"]) ? $_POST["user_id"] : null;
				$new_user_type_id = isset($_POST["user_type_id"]) ? $_POST["user_type_id"] : null;
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("user_id" => $new_user_id, "user_type_id" => $new_user_type_id));
				if ($empty_field_name)
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				else {
					if (!empty($settings["allow_insertion"]) && empty($data)) {
						$new_data = $data;
						$new_data["user_id"] = $new_user_id;
						$new_data["user_type_id"] = $new_user_type_id;
						
						\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
						
						if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
							$status = \UserUtil::insertUserUserType($brokers, $new_data);
							
							if (isset($settings["on_insert_ok_action"]) && strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$new_data_user_id = isset($new_data["user_id"]) ? $new_data["user_id"] : null;
								$new_data_user_type_id = isset($new_data["user_type_id"]) ? $new_data["user_type_id"] : null;
								
								$settings["on_insert_ok_redirect_url"] .= (isset($settings["on_insert_ok_redirect_url"]) && strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "user_id=$new_data_user_id&user_type_id=$new_data_user_type_id";
							}
						}
					}
					else if (!empty($settings["allow_update"]) && $data) {
						$new_data = array();
						$new_data["old_user_id"] = isset($data["user_id"]) ? $data["user_id"] : null;
						$new_data["old_user_type_id"] = isset($data["user_type_id"]) ? $data["user_type_id"] : null;
						$new_data["new_user_id"] = !empty($settings["show_user_id"]) ? $new_user_id : $new_data["old_user_id"];
						$new_data["new_user_type_id"] = !empty($settings["show_user_type_id"]) ? $new_user_type_id : $new_data["old_user_type_id"];
						
						\CommonModuleUI::prepareFieldWithDefaultValue($settings, "user_id", $new_data["new_user_id"]);
						\CommonModuleUI::prepareFieldWithDefaultValue($settings, "user_type_id", $new_data["new_user_type_id"]);
						
						$fields_to_validade = array("user_id" => $new_data["new_user_id"], "user_type_id" => $new_data["new_user_type_id"]);
						
						if (\CommonModuleUI::areFieldsValid($EVC, $settings, $fields_to_validade, $error_message)) {
							$status = \UserUtil::updateUserUserType($brokers, $new_data);
							if (isset($settings["on_update_ok_action"]) && strpos($settings["on_update_ok_action"], "_redirect") !== false)
								$settings["on_update_ok_redirect_url"] .= (isset($settings["on_update_ok_redirect_url"]) && strpos($settings["on_update_ok_redirect_url"], "?") !== false ? "&" : "?") . "user_id=${new_data['new_user_id']}&user_type_id=${new_data['new_user_type_id']}";
						}
					}
				}
			}
		}
		
		if (!empty($_POST["save"])) {
			$form_data = array(
				"user_id" => !empty($settings["show_user_id"]) ? $new_user_id : (isset($data["user_id"]) ? $data["user_id"] : null),
				"user_type_id" => !empty($settings["show_user_type_id"]) ? $new_user_type_id : (isset($data["user_type_id"]) ? $data["user_type_id"] : null),
			);
			$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : (!empty($settings["allow_view"]) && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else 
			$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/user/edit_user_user_type.css';
		$settings["class"] = "module_edit_user_user_type";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		$is_editable = (!empty($settings["allow_update"]) && $data) || (!empty($settings["allow_insertion"]) && !$data);
		\CMSModule\user\UserModuleUtil::prepareFormSettingsFields($EVC, $settings, $is_editable);
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/edit_user_user_type", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
