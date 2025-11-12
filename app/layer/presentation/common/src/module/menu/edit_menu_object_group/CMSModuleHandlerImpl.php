<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

namespace CMSModule\menu\edit_menu_object_group;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("menu/MenuUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Menu Object Groups
		$group_id = isset($_GET["group_id"]) ? $_GET["group_id"] : null;
		$object_type_id = isset($_GET["object_type_id"]) ? $_GET["object_type_id"] : null;
		$object_id = isset($_GET["object_id"]) ? $_GET["object_id"] : null;
		
		$data = $group_id && $object_type_id && $object_id ? \MenuUtil::getMenuObjectGroupsByConditions($brokers, array("group_id" => $group_id, "object_type_id" => $object_type_id, "object_id" => $object_id), null, null, true) : null;
		$data = isset($data[0]) ? $data[0] : null;
		
		//Preparing Action
		if (!empty($_POST)) {
			if (!empty($_POST["delete"]) && !empty($settings["allow_deletion"])) {
				$data_group_id = isset($data["group_id"]) ? $data["group_id"] : null;
				$data_object_type_id = isset($data["object_type_id"]) ? $data["object_type_id"] : null;
				$data_object_id = isset($data["object_id"]) ? $data["object_id"] : null;
				
				$status = !$data || \MenuUtil::deleteMenuObjectGroup($brokers, $data_group_id, $data_object_type_id, $data_object_id);
			}
			else if (!empty($_POST["save"])) {
				$new_group_id = isset($_POST["group_id"]) ? $_POST["group_id"] : null;
				$new_object_type_id = isset($_POST["object_type_id"]) ? $_POST["object_type_id"] : null;
				$new_object_id = isset($_POST["object_id"]) ? $_POST["object_id"] : null;
				$new_group = isset($_POST["group"]) ? $_POST["group"] : null;
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("group_id" => $new_group_id, "object_type_id" => $new_object_type_id, "object_id" => $new_object_id, "group" => $new_group));
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				}
				else {
					if (!empty($settings["allow_insertion"]) && empty($data)) {
						$new_data = $data;
						$new_data["group_id"] = $new_group_id;
						$new_data["object_type_id"] = $new_object_type_id;
						$new_data["object_id"] = $new_object_id;
						$new_data["group"] = $new_group;
						
						\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
						
						if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
							$status = \MenuUtil::insertMenuObjectGroup($brokers, $new_data);
							if (isset($settings["on_insert_ok_action"]) && strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (isset($settings["on_insert_ok_redirect_url"]) && strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "group_id=${new_data['group_id']}&object_type_id=${new_data['object_type_id']}&object_id=${new_data['object_id']}";
							}
						}
					}
					else if (!empty($settings["allow_update"]) && $data) {
						$new_data = array();
						$new_data["old_group_id"] = isset($data["group_id"]) ? $data["group_id"] :null;
						$new_data["old_object_type_id"] = isset($data["object_type_id"]) ? $data["object_type_id"] :null;
						$new_data["old_object_id"] = isset($data["object_id"]) ? $data["object_id"] :null;
						$new_data["new_group_id"] = !empty($settings["show_group_id"]) ? $new_group_id : $new_data["old_group_id"];
						$new_data["new_object_type_id"] = !empty($settings["show_object_type_id"]) ? $new_object_type_id : $new_data["old_object_type_id"];
						$new_data["new_object_id"] = !empty($settings["show_object_id"]) ? $new_object_id : $new_data["old_object_id"];
						$new_data["group"] = $new_group;
						
						\CommonModuleUI::prepareFieldWithDefaultValue($settings, "group_id", $new_data["new_group_id"]);
						\CommonModuleUI::prepareFieldWithDefaultValue($settings, "object_type_id", $new_data["new_object_type_id"]);
						\CommonModuleUI::prepareFieldWithDefaultValue($settings, "object_id", $new_data["new_object_id"]);
						
						$fields_to_validade = array("group_id" => $new_data["new_group_id"], "object_type_id" => $new_data["new_object_type_id"], "object_id" => $new_data["new_object_id"], "group" => $new_data["group"]);
						
						if (\CommonModuleUI::areFieldsValid($EVC, $settings, $fields_to_validade, $error_message)) {
							$status = \MenuUtil::updateMenuObjectGroup($brokers, $new_data);
							if (isset($settings["on_update_ok_action"]) && strpos($settings["on_update_ok_action"], "_redirect") !== false) {
								$settings["on_update_ok_redirect_url"] .= (isset($settings["on_update_ok_redirect_url"]) && strpos($settings["on_update_ok_redirect_url"], "?") !== false ? "&" : "?") . "group_id=${new_data['new_group_id']}&object_type_id=${new_data['new_object_type_id']}&object_id=${new_data['new_object_id']}";
							}
						}
					}
				}
			}
		}
		
		if (!empty($_POST["save"])) {
			$form_data = array(
				"group_id" => !empty($settings["show_group_id"]) ? $new_group_id : (isset($data["group_id"]) ? $data["group_id"] : null),
				"object_type_id" => !empty($settings["show_object_type_id"]) ? $new_object_type_id : (isset($data["object_type_id"]) ? $data["object_type_id"] : null),
				"object_id" => !empty($settings["show_object_id"]) ? $new_object_id : (isset($data["object_id"]) ? $data["object_id"] : null),
				"group" => !empty($settings["show_group"]) ? $new_group : (isset($data["group"]) ? $data["group"] : null),
			);
			$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : (!empty($settings["allow_view"]) && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/menu/edit_menu_object_group.css';
		$settings["class"] = "module_edit_menu_object_group";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		$is_editable = (!empty($settings["allow_update"]) && $data) || (!empty($settings["allow_insertion"]) && !$data);
		
		if (!empty($settings["show_object_type_id"])) {
			include_once $EVC->getModulePath("object/ObjectUtil", $common_project_name);
			
			$object_types = \ObjectUtil::getAllObjectTypes($brokers);
			$object_type_options = array();
			$available_object_types = array();
			
			if ($object_types) {
				$t = count($object_types);
				for ($i = 0; $i < $t; $i++) {
					$av_object_type_id = isset($object_types[$i]["object_type_id"]) ? $object_types[$i]["object_type_id"] : null;
					$av_object_type_name = isset($object_types[$i]["name"]) ? $object_types[$i]["name"] : null;
					
					if ($is_editable) 
						$object_type_options[] = array("value" => $av_object_type_id, "label" => $av_object_type_name);
					else
						$available_object_types[$av_object_type_id] = $av_object_type_name;
				}
			}
			
			$settings["fields"]["object_type_id"]["field"]["input"]["type"] = $is_editable ? "select" : "label";
			$settings["fields"]["object_type_id"]["field"]["input"]["options"] = $object_type_options;
			$settings["fields"]["object_type_id"]["field"]["input"]["available_values"] = $available_object_types;
		}
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "menu/edit_menu_object_group", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
