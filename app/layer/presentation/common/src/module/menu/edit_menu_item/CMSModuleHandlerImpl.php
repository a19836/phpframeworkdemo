<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

namespace CMSModule\menu\edit_menu_item;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("menu/MenuUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Menu Item Details
		$item_id = isset($_GET["item_id"]) ? $_GET["item_id"]: null;
		$data = $item_id ? \MenuUtil::getMenuItemsByConditions($brokers, array("item_id" => $item_id), null, null, true) : null;
		$data = isset($data[0]) ? $data[0] : null;
		
		//Preparing Menu Item
		if (!empty($_POST)) {
			if (!empty($_POST["delete"]) && !empty($settings["allow_deletion"])) {
				$data_item_id = isset($data["item_id"]) ? $data["item_id"] : null;
				
				$status = !$data || (\MenuUtil::deleteMenuItemsByGroupId($brokers, $data_item_id) && \MenuUtil::deleteMenuItem($brokers, $data_item_id));
			}
			else if (!empty($_POST["save"])) {
				$group_id = isset($_POST["group_id"]) ? $_POST["group_id"] : null;
				$parent_id = isset($_POST["parent_id"]) ? $_POST["parent_id"] : null;
				$label = isset($_POST["label"]) ? $_POST["label"] : null;
				$title = isset($_POST["title"]) ? $_POST["title"] : null;
				$class = isset($_POST["class"]) ? $_POST["class"] : null;
				$url = isset($_POST["url"]) ? $_POST["url"] : null;
				$previous_html = isset($_POST["previous_html"]) ? $_POST["previous_html"] : null;
				$next_html = isset($_POST["next_html"]) ? $_POST["next_html"] : null;
				$order = isset($_POST["order"]) ? $_POST["order"] : null;
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("group_id" => $group_id, "label" => $label, "title" => $title, "class" => $class, "url" => $url, "previous_html" => $previous_html, "next_html" => $next_html));
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				}
				else {
					$new_data = $data;
					$new_data["group_id"] = !empty($settings["show_group_id"]) ? $group_id : (isset($new_data["group_id"]) ? $new_data["group_id"] : null);
					$new_data["parent_id"] = !empty($settings["show_parent_id"]) ? $parent_id : (isset($new_data["parent_id"]) ? $new_data["parent_id"] : null);
					$new_data["label"] = !empty($settings["show_label"]) ? $label : (isset($new_data["label"]) ? $new_data["label"] : null);
					$new_data["title"] = !empty($settings["show_title"]) ? $title : (isset($new_data["title"]) ? $new_data["title"] : null);
					$new_data["class"] = !empty($settings["show_class"]) ? $class : (isset($new_data["class"]) ? $new_data["class"] : null);
					$new_data["url"] = !empty($settings["show_url"]) ? $url : (isset($new_data["url"]) ? $new_data["url"] : null);
					$new_data["previous_html"] = !empty($settings["show_previous_html"]) ? $previous_html : (isset($new_data["previous_html"]) ? $new_data["previous_html"] : null);
					$new_data["next_html"] = !empty($settings["show_next_html"]) ? $next_html : (isset($new_data["next_html"]) ? $new_data["next_html"] : null);
					$new_data["order"] = !empty($settings["show_order"]) ? $order : (isset($new_data["order"]) ? $new_data["order"] : null);
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					$new_data["parent_id"] = is_numeric($new_data["parent_id"]) ? $new_data["parent_id"] : 0;
					$new_data["order"] = is_numeric($new_data["order"]) ? $new_data["order"] : 0;
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						if (!empty($settings["allow_insertion"]) && empty($data["item_id"])) {
							$status = \MenuUtil::insertMenuItem($brokers, $new_data);
							if (isset($settings["on_insert_ok_action"]) && strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (isset($settings["on_insert_ok_redirect_url"]) && strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "item_id=$status";
							}
						}
						else if (!empty($settings["allow_update"]) && !empty($data["item_id"])) {
							$status = \MenuUtil::updateMenuItem($brokers, $new_data);
						}
					}
				}
			}
		}
		
		if (!empty($_POST["save"])) {
			$form_data = array(
				"group_id" => !empty($settings["show_group_id"]) ? $group_id : (isset($data["group_id"]) ? $data["group_id"] : null),
				"parent_id" => !empty($settings["show_parent_id"]) ? $parent_id : (isset($data["parent_id"]) ? $data["parent_id"] : null),
				"item_id" => !empty($settings["show_item_id"]) ? $item_id : (isset($data["item_id"]) ? $data["item_id"] : null),
				"label" => !empty($settings["show_label"]) ? $label : (isset($data["label"]) ? $data["label"] : null),
				"title" => !empty($settings["show_title"]) ? $title : (isset($data["title"]) ? $data["title"] : null),
				"class" => !empty($settings["show_class"]) ? $class : (isset($data["class"]) ? $data["class"] : null),
				"url" => !empty($settings["show_url"]) ? $url : (isset($data["url"]) ? $data["url"] : null),
				"previous_html" => !empty($settings["show_previous_html"]) ? $previous_html : (isset($data["previous_html"]) ? $data["previous_html"] : null),
				"next_html" => !empty($settings["show_next_html"]) ? $next_html : (isset($data["next_html"]) ? $data["next_html"] : null),
				"order" => !empty($settings["show_order"]) ? $order : (isset($data["order"]) ? $data["order"] : null),
			);
			$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : (!empty($settings["allow_view"]) && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/menu/edit_menu_item.css';
		$settings["class"] = "module_edit_menu_item";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		$is_insertion = !empty($settings["allow_insertion"]) && empty($data["item_id"]);
		$is_editable = (!empty($settings["allow_update"]) && !empty($data["item_id"])) || $is_insertion;
		
		if (!empty($settings["show_item_id"]))
			$settings["fields"]["item_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		if (!empty($settings["show_group_id"])) {
			$groups = \MenuUtil::getAllMenuGroups($brokers);
			$group_options = array();
			$available_groups = array();
			
			if ($groups) {
				$t = count($groups);
				for ($i = 0; $i < $t; $i++) {
					$av_group_id = isset($groups[$i]["group_id"]) ? $groups[$i]["group_id"] : null;
					$av_group_name = isset($groups[$i]["name"]) ? $groups[$i]["name"] : null;
					
					if ($is_editable) 
						$group_options[] = array("value" => $av_group_id, "label" => $av_group_name);
					else
						$available_groups[$av_group_id] = $av_group_name;
				}
			}
			
			$settings["fields"]["group_id"]["field"]["input"]["type"] = $is_editable ? "select" : "label";
			$settings["fields"]["group_id"]["field"]["input"]["options"] = $group_options;
			$settings["fields"]["group_id"]["field"]["input"]["available_values"] = $available_groups;
		}
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "menu/edit_menu_item", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
