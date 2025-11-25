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

namespace CMSModule\object\edit_object_type;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("object/ObjectUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Object Type Details
		$object_type_id = isset($_GET["object_type_id"]) ? $_GET["object_type_id"] : null;
		$data = $object_type_id ? \ObjectUtil::getObjectTypesByConditions($brokers, array("object_type_id" => $object_type_id), null, null, true) : null;
		$data = isset($data[0]) ? $data[0] : null;
		$data_object_type_id = isset($data["object_type_id"]) ? $data["object_type_id"] : null;
		
		$reserved_object_type_ids = \ObjectUtil::getReservedObjectTypeIds();
		
		//Preparing Action
		if (!empty($_POST)) {
			if (in_array($data_object_type_id, $reserved_object_type_ids)) {
				$error_message = "This object type is native and cannot be edit!";
			}
			else if (!empty($_POST["delete"]) && !empty($settings["allow_deletion"])) {
				$status = !$data || \ObjectUtil::deleteObjectType($brokers, $data_object_type_id);
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
						if (!empty($settings["allow_insertion"]) && !$data_object_type_id) {
							$status = \ObjectUtil::insertObjectType($brokers, $new_data);
							if (isset($settings["on_insert_ok_action"]) && strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (isset($settings["on_insert_ok_redirect_url"]) && strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "object_type_id=$status";
							}
						}
						else if (!empty($settings["allow_update"]) && $data_object_type_id) {
							$status = \ObjectUtil::updateObjectType($brokers, $new_data);
						}
					}
				}
			}
		}
		
		if (!empty($_POST["save"])) {
			$form_data = array(
				"object_type_id" => !empty($settings["show_object_type_id"]) ? $object_type_id : $data_object_type_id,
				"name" => !empty($settings["show_name"]) ? $name : (isset($data["name"]) ? $data["name"] : null),
			);
			$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : (!empty($settings["allow_view"]) && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		}
		
		if (in_array($data_object_type_id, $reserved_object_type_ids) && empty($error_message)) {
			$error_message = 'This is a reserved object type.';
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/object/edit_object_type.css';
		$settings["class"] = "module_edit_object_type";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		$is_insertion = !empty($settings["allow_insertion"]) && !$data_object_type_id;
		
		if (!empty($settings["allow_update"]) && $data_object_type_id && in_array($data_object_type_id, $reserved_object_type_ids)) {
			$settings["allow_update"] = false;
		}
		
		if (!empty($settings["allow_deletion"]) && $data_object_type_id && in_array($data_object_type_id, $reserved_object_type_ids)) {
			$settings["allow_deletion"] = false;
		}
		
		if (!empty($settings["show_object_type_id"])) {
			$settings["fields"]["object_type_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		}
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "object/edit_object_type", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
