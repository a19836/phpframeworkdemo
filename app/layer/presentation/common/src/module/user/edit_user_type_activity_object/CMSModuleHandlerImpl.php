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

namespace CMSModule\user\edit_user_type_activity_object;

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
		
		//Getting User Type Activities
		$user_type_id = isset($_GET["user_type_id"]) ? $_GET["user_type_id"] : null;
		$activity_id = isset($_GET["activity_id"]) ? $_GET["activity_id"] : null;
		$object_type_id = isset($_GET["object_type_id"]) ? $_GET["object_type_id"] : null;
		$object_id = isset($_GET["object_id"]) ? $_GET["object_id"] : null;
		
		$data = $user_type_id && $activity_id && $object_type_id && $object_id ? \UserUtil::getUserTypeActivityObjectsByConditions($brokers, array("user_type_id" => $user_type_id, "activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id), null, null, true) : null;
		$data = isset($data[0]) ? $data[0] : null;
		
		//Preparing Action
		if (!empty($_POST)) {
			if (!empty($_POST["delete"]) && !empty($settings["allow_deletion"])) {
				$data_user_type_id = isset($data["user_type_id"]) ? $data["user_type_id"] : null;
				$data_activity_id = isset($data["activity_id"]) ? $data["activity_id"] : null;
				$data_object_type_id = isset($data["object_type_id"]) ? $data["object_type_id"] : null;
				$data_object_id = isset($data["object_id"]) ? $data["object_id"] : null;
				
				$status = !$data || \UserUtil::deleteUserTypeActivityObject($brokers, $data_user_type_id, $data_activity_id, $data_object_type_id, $data_object_id);
			}
			else if (!empty($_POST["save"])) {
				$new_user_type_id = isset($_POST["user_type_id"]) ? $_POST["user_type_id"] : null;
				$new_activity_id = isset($_POST["activity_id"]) ? $_POST["activity_id"] : null;
				$new_object_type_id = isset($_POST["object_type_id"]) ? $_POST["object_type_id"] : null;
				$new_object_id = isset($_POST["object_id"]) ? $_POST["object_id"] : null;
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("user_type_id" => $new_user_type_id, "activity_id" => $new_activity_id, "object_type_id" => $new_object_type_id, "object_id" => $new_object_id));
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				}
				else {
					if (!empty($settings["allow_insertion"]) && empty($data)) {
						$new_data = $data;
						$new_data["user_type_id"] = $new_user_type_id;
						$new_data["activity_id"] = $new_activity_id;
						$new_data["object_type_id"] = $new_object_type_id;
						$new_data["object_id"] = $new_object_id;
						
						\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
						
						if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
							$status = \UserUtil::insertUserTypeActivityObject($brokers, $new_data);
							if (isset($settings["on_insert_ok_action"]) && strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$new_data_user_type_id = isset($new_data["user_type_id"]) ? $new_data["user_type_id"] : null;
								$new_data_activity_id = isset($new_data["activity_id"]) ? $new_data["activity_id"] : null;
								$new_data_object_type_id = isset($new_data["object_type_id"]) ? $new_data["object_type_id"] : null;
								$new_data_object_id = isset($new_data["object_id"]) ? $new_data["object_id"] : null;
								
								$settings["on_insert_ok_redirect_url"] .= (isset($settings["on_insert_ok_redirect_url"]) && strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "user_type_id=$new_data_user_type_id&activity_id=$new_data_activity_id&object_type_id=$new_data_object_type_id&object_id=$new_data_object_id";
							}
						}
					}
					else if (!empty($settings["allow_update"]) && $data) {
						$new_data = array();
						$new_data["old_user_type_id"] = isset($data["user_type_id"]) ? $data["user_type_id"] : null;
						$new_data["old_activity_id"] = isset($data["activity_id"]) ? $data["activity_id"] : null;
						$new_data["old_object_type_id"] = isset($data["object_type_id"]) ? $data["object_type_id"] : null;
						$new_data["old_object_id"] = isset($data["object_id"]) ? $data["object_id"] : null;
						
						$new_data["new_user_type_id"] = !empty($settings["show_user_type_id"]) ? $new_user_type_id : (isset($data["user_type_id"]) ? $data["user_type_id"] : null);
						$new_data["new_activity_id"] = !empty($settings["show_activity_id"]) ? $new_activity_id : (isset($data["activity_id"]) ? $data["activity_id"] : null);
						$new_data["new_object_type_id"] = !empty($settings["show_object_type_id"]) ? $new_object_type_id : (isset($data["object_type_id"]) ? $data["object_type_id"] : null);
						$new_data["new_object_id"] = !empty($settings["show_object_id"]) ? $new_object_id : (isset($data["object_id"]) ? $data["object_id"] : null);
						
						\CommonModuleUI::prepareFieldWithDefaultValue($settings, "user_type_id", $new_data["new_user_type_id"]);
						\CommonModuleUI::prepareFieldWithDefaultValue($settings, "activity_id", $new_data["new_activity_id"]);
						\CommonModuleUI::prepareFieldWithDefaultValue($settings, "object_type_id", $new_data["new_object_type_id"]);
						\CommonModuleUI::prepareFieldWithDefaultValue($settings, "object_id", $new_data["new_object_id"]);
						
						$fields_to_validade = array("user_type_id" => $new_data["new_user_type_id"], "activity_id" => $new_data["new_activity_id"], "object_type_id" => $new_data["new_object_type_id"], "object_id" => $new_data["new_object_id"]);
						
						if (\CommonModuleUI::areFieldsValid($EVC, $settings, $fields_to_validade, $error_message)) {
							$status = \UserUtil::updateUserTypeActivityObject($brokers, $new_data);
							if (isset($settings["on_update_ok_action"]) && strpos($settings["on_update_ok_action"], "_redirect") !== false) {
								$settings["on_update_ok_redirect_url"] .= (isset($settings["on_update_ok_redirect_url"]) && strpos($settings["on_update_ok_redirect_url"], "?") !== false ? "&" : "?") . "user_type_id=${new_data['new_user_type_id']}&activity_id=${new_data['new_activity_id']}&object_type_id=${new_data['new_object_type_id']}&object_id=${new_data['new_object_id']}";
							}
						}
					}
				}
			}
		}
		
		if (!empty($_POST["save"])) {
			$form_data = array(
				"user_type_id" => !empty($settings["show_user_type_id"]) ? $new_user_type_id : (isset($data["user_type_id"]) ? $data["user_type_id"] : null),
				"activity_id" => !empty($settings["show_activity_id"]) ? $new_activity_id : (isset($data["activity_id"]) ? $data["activity_id"] : null),
				"object_type_id" => !empty($settings["show_object_type_id"]) ? $new_object_type_id : (isset($data["object_type_id"]) ? $data["object_type_id"] : null),
				"object_id" => !empty($settings["show_object_id"]) ? $new_object_id : (isset($data["object_id"]) ? $data["object_id"] : null),
			);
			$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : (!empty($settings["allow_view"]) && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else
			$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/user/edit_user_type_activity_object.css';
		$settings["class"] = "module_edit_user_type_activity_object";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		$is_editable = (!empty($settings["allow_update"]) && $data) || (!empty($settings["allow_insertion"]) && !$data);
		\CMSModule\user\UserModuleUtil::prepareFormSettingsFields($EVC, $settings, $is_editable);
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/edit_user_type_activity_object", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
