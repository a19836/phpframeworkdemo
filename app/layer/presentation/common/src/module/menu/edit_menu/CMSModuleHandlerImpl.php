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

namespace CMSModule\menu\edit_menu;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("menu/MenuUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$options = array();
		
		//Getting Menu
		$group_id_type = isset($settings["group_id_type"]) ? $settings["group_id_type"] : null;
		$group_id = $items = null;
		
		switch ($group_id_type) {
			case "first_group_id_by_tag_and":
				$tags = isset($settings["tags"]) ? $settings["tags"] : null;
				if ($tags) {
					$items = \MenuUtil::getMenuGroupsWithAllTags($brokers, $tags, array(), null, $options);
					$group_id = isset($items[0]["group_id"]) ? $items[0]["group_id"] : null;
				}
				break;
			case "first_group_id_by_tag_or":
				$tags = isset($settings["tags"]) ? $settings["tags"] : null;
				if ($tags) {
					$items = \MenuUtil::getMenuGroupsByTags($brokers, $tags, array(), null, $options);
					$group_id = isset($items[0]["group_id"]) ? $items[0]["group_id"] : null;
				}
				break;
			case "first_group_id_from_related_object": 
				$items = !empty($settings["group_id_by_object"]["object_type_id"]) && !empty($settings["group_id_by_object"]["object_id"]) ? \MenuUtil::getMenuGroupsByObject($brokers, $settings["group_id_by_object"]["object_type_id"], $settings["group_id_by_object"]["object_id"], $options) : array();
				$group_id = isset($items[0]["group_id"]) ? $items[0]["group_id"] : null;
				break;
			case "first_group_id_from_related_object_group": 
				$items = !empty($settings["group_id_by_object"]["object_type_id"]) && !empty($settings["group_id_by_object"]["object_id"]) ? \MenuUtil::getMenuGroupsByObjectGroup($brokers, $settings["group_id_by_object"]["object_type_id"], $settings["group_id_by_object"]["object_id"], isset($settings["group_id_by_object"]["group"]) ? $settings["group_id_by_object"]["group"] : null, $options) : array();
				$group_id = isset($items[0]["group_id"]) ? $items[0]["group_id"] : null;
				break;
			default:
				$group_id = isset($settings["group_id"]) ? $settings["group_id"] : null;
		}
		
		$data = $group_id ? \MenuUtil::getMenuGroupsByConditions($brokers, array("group_id" => $group_id), null, null, true) : null;
		$data = isset($data[0]) ? $data[0] : null;
		
		if ($data) {
			$data["tags"] = \TagUtil::getObjectTagsString($brokers, \ObjectUtil::MENU_OBJECT_TYPE_ID, $group_id, array(), true);
			
			$options = array("sort" => array(array("column" => "order", "order" => "asc")));
			$items = \MenuUtil::getMenuItemsByConditions($brokers, array("group_id" => $group_id), null, $options, true);
			$items = \MenuUtil::encapsulateMenuGroupItems($items);
		}
		
		$is_editable = (!empty($settings["allow_insertion"]) && empty($data["group_id"])) || (!empty($settings["allow_update"]) && !empty($data["group_id"]));
									
		//Saving Menu
		if (!empty($_POST)) {
			if (!empty($_POST["delete"]) && !empty($settings["allow_deletion"])) {
				$status = empty($data) || (\MenuUtil::deleteMenuItemsByGroupId($brokers, $group_id) && \MenuUtil::deleteMenuObjectGroupsByGroupId($brokers, $group_id) && \MenuUtil::deleteMenuGroup($brokers, $group_id));
				
				if ($status) {
					//Add Join Point creating a new action of some kind
					$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull menu deleting action", array(
						"EVC" => &$EVC,
						"group_id" => $group_id,
						"menu_data" => &$data,
						"error_message" => &$error_message,
					));
				}
			}
			else if (!empty($_POST["save"])) {
				$group_name = isset($_POST["group_name"]) ? $_POST["group_name"] : null;
				$group_tags = isset($_POST["group_tags"]) ? $_POST["group_tags"] : null;
				$old_items = $items;
				$items = isset($_POST["menu_items"]) ? $_POST["menu_items"] : null;
				
				//Saving new group data
				if (!$data || $group_name != $data["name"]) {
					$group_name = !empty($settings["show_group_name"]) ? $group_name : $data["name"];
					
					$d = array("group_name" => $group_name);
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $d);
					$group_name = isset($d["group_name"]) ? $d["group_name"] : null;
					
					if (\CommonModuleUI::checkIfEmptyField($settings, "group_name", $group_name)) {
						$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, "group_name");
					}
					else {
						$new_data = $data;
						$new_data["name"] = $group_name;
						
						if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
							$new_data["object_groups"] = isset($settings["object_to_objects"]) ? $settings["object_to_objects"] : null;
						
							if (!empty($settings["allow_insertion"]) && empty($data["group_id"])) {
								$status = \MenuUtil::insertMenuGroup($brokers, $new_data);
								if (isset($settings["on_insert_ok_action"]) && strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
									$settings["on_insert_ok_redirect_url"] .= (isset($settings["on_insert_ok_redirect_url"]) && strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "group_id=$status";
								}
							}
							else if (!empty($settings["allow_update"]) && !empty($data["group_id"])) {
								$status = \MenuUtil::updateMenuGroup($brokers, $new_data);
							}
						}
					}
				}
				else {
					$status = true;
				}
				
				//Saving new items data
				if (!empty($status) && empty($error_message) && $is_editable) {
					$group_id = !empty($data["group_id"]) ? $data["group_id"] : $status;
					$data_tags = isset($data["tags"]) ? $data["tags"] : null;
					
					//save tags
					if ($group_tags != $data_tags) {
						$group_tags = !empty($settings["show_group_tags"]) ? $group_tags : $data_tags;
						
						$d = array("group_tags" => $group_tags);
						\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $d);
						$group_tags = isset($d["group_tags"]) ? $d["group_tags"] : null;
						
						if (\CommonModuleUI::checkIfEmptyField($settings, "group_tags", $group_tags)) {
							$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, "group_tags");
						}
						else {
							$new_data = $data;
							$new_data["tags"] = $group_tags;
							
							if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
								$status = \TagUtil::updateObjectTags($brokers, $group_tags, \ObjectUtil::MENU_OBJECT_TYPE_ID, $group_id);
							}
						}
					}
					
					if ($status) {
						$item_settings = $settings;
						$item_settings["fields"]["label"] = isset($item_settings["fields"]["item_label"]) ? $item_settings["fields"]["item_label"] : null;
						$item_settings["fields"]["title"] = isset($item_settings["fields"]["item_title"]) ? $item_settings["fields"]["item_title"] : null;
						$item_settings["fields"]["class"] = isset($item_settings["fields"]["item_class"]) ? $item_settings["fields"]["item_class"] : null;
						$item_settings["fields"]["url"] = isset($item_settings["fields"]["item_url"]) ? $item_settings["fields"]["item_url"] : null;
						$item_settings["fields"]["previous_html"] = isset($item_settings["fields"]["item_previous_html"]) ? $item_settings["fields"]["item_previous_html"] : null;
						$item_settings["fields"]["next_html"] = isset($item_settings["fields"]["item_next_html"]) ? $item_settings["fields"]["item_next_html"] : null;
						
						$status = self::deleteOldItems($brokers, $old_items, $items) && self::saveMenuItems($EVC, $brokers, $item_settings, $items, $error_message, $group_id);
								
						if ($status) {
							if (!isset($new_data))
								$new_data = null;
							
							//Add Join Point creating a new action of some kind
							$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull menu saving action", array(
								"EVC" => &$EVC,
								"group_id" => $group_id,
								"menu_data" => &$new_data,
								"error_message" => &$error_message,
							));
						}
					}
				}
			}
		}
		
		if (!empty($_POST["save"])) {
			$form_data = array(
				"group_name" => $group_name,
				"group_tags" => $group_tags,
			);
			$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : (!empty($settings["allow_view"]) && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else
			$form_data = array(
				"group_name" => !empty($settings["allow_view"]) && isset($data["name"]) ? $data["name"] : null,
				"group_tags" => !empty($settings["allow_view"]) && isset($data["tags"]) ? $data["tags"] : null,
			);
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/menu/edit_menu.css';
		$settings["js_file"] = $project_common_url_prefix . 'module/menu/edit_menu.js';
		$settings["class"] = "module_edit_menu";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		$settings["form_on_submit"] = "saveMenu(this)";
		
		$settings["next_html"] = '
		<div class="menu_items' . (!empty($settings["menu_class"]) ? ' ' . $settings["menu_class"] : '') . '">
			<label>' . translateProjectText($EVC, "Items") . ': </label>
			<span class="icon add" onClick="addMenuItem(this)">' . translateProjectText($EVC, "Add") . '</span>
			
			<ol class="items' . (!empty($settings["list_class"]) ? ' ' . $settings["list_class"] : '') . '"></ol>
		</div>';
		
		$fields = array(
			"item_label" => isset($settings["fields"]["item_label"]) ? $settings["fields"]["item_label"] : null,
			"item_title" => isset($settings["fields"]["item_title"]) ? $settings["fields"]["item_title"] : null,
			"item_class" => isset($settings["fields"]["item_class"]) ? $settings["fields"]["item_class"] : null,
			"item_url" => isset($settings["fields"]["item_url"]) ? $settings["fields"]["item_url"] : null,
			"item_previous_html" => isset($settings["fields"]["item_previous_html"]) ? $settings["fields"]["item_previous_html"] : null,
			"item_next_html" => isset($settings["fields"]["item_next_html"]) ? $settings["fields"]["item_next_html"] : null,
		);
		unset($settings["fields"]["item_label"]);
		unset($settings["fields"]["item_title"]);
		unset($settings["fields"]["item_class"]);
		unset($settings["fields"]["item_url"]);
		unset($settings["fields"]["item_previous_html"]);
		unset($settings["fields"]["item_next_html"]);
		
		$html = '<script>
		var menu_item_html = \'' . addcslashes(str_replace("\n", "", self::getItemHtml($EVC, $settings, $fields)), "\\'") . '\';
		var menu_items = ' . json_encode($items, true) . ';
		</script>';
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "menu/edit_menu", $settings);
		$html .= \CommonModuleUI::getFormHtml($EVC, $settings);
		
		return $html;
	}
	
	private static function getItemHtml($EVC, $settings, $fields) {
		$fields["item_label"]["field"]["input"]["type"] = $fields["item_title"]["field"]["input"]["type"] = $fields["item_class"]["field"]["input"]["type"] = $fields["item_url"]["field"]["input"]["type"] = "text";
		$fields["item_previous_html"]["field"]["input"]["type"] = $fields["item_next_html"]["field"]["input"]["type"] = "textarea";
		
		$form_settings = array("form_containers" => &$fields);
		translateProjectFormSettings($EVC, $form_settings);
		
		$HtmlFormHandler = new \HtmlFormHandler($form_settings);
		
		$item_label = isset($fields["item_label"]) ? $fields["item_label"] : null;
		$item_url = isset($fields["item_url"]) ? $fields["item_url"] : null;
		$item_title = isset($fields["item_title"]) ? $fields["item_title"] : null;
		$item_class = isset($fields["item_class"]) ? $fields["item_class"] : null;
		$item_previous_html = isset($fields["item_previous_html"]) ? $fields["item_previous_html"] : null;
		$item_next_html = isset($fields["item_next_html"]) ? $fields["item_next_html"] : null;
		
		$html = '
		<li class="menu_item' . (!empty($settings["list_item_class"]) ? ' ' . $settings["list_item_class"] : '') . '">
			<div class="item_id">
				<input type="hidden" />
			</div>
			
			' . $HtmlFormHandler->createElements(array($item_label)) . '
			<span class="icon maximize" onClick="toggleMenuItem(this)">' . translateProjectText($EVC, "Toggle") . '</span>
			<span class="icon add" onClick="addMenuItem(this)">' . translateProjectText($EVC, "Add") . '</span>
			<span class="icon up" onClick="moveUpMenuItem(this)">' . translateProjectText($EVC, "Move Up") . '</span>
			<span class="icon down" onClick="moveDownMenuItem(this)">' . translateProjectText($EVC, "Move Down") . '</span>
			<span class="icon out" onClick="moveOutMenuItem(this)">' . translateProjectText($EVC, "Move Out") . '</span>
			<span class="icon in" onClick="moveInMenuItem(this)">' . translateProjectText($EVC, "Move In") . '</span>
			<span class="icon delete" onClick="removeMenuItem(this)">' . translateProjectText($EVC, "Remove") . '</span>
			' . (!empty($settings["show_item_url"]) ? $HtmlFormHandler->createElements(array($fields["item_url"])) : '') . '
			' . (!empty($settings["show_item_title"]) ? $HtmlFormHandler->createElements(array($fields["item_title"])) : '') . '
			' . (!empty($settings["show_item_class"]) ? $HtmlFormHandler->createElements(array($fields["item_class"])) : '') . '
			' . (!empty($settings["show_item_previous_html"]) ? $HtmlFormHandler->createElements(array($fields["item_previous_html"])) : '') . '
			' . (!empty($settings["show_item_next_html"]) ? $HtmlFormHandler->createElements(array($fields["item_next_html"])) : '') . '
			
			<ol class="items' . (!empty($settings["list_class"]) ? ' ' . $settings["list_class"] : '') . '"></ol>
		</li>';
		
		return $html;
	}
	
	private static function saveMenuItems($EVC, $brokers, $settings, &$items, &$error_message, $group_id, $parent_id = 0) {
		if (is_array($items)) {
			$order = 1;
			
			foreach ($items as $idx => $item) {
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, $item);
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
					return false;
				}
				else {
					$new_data = $item;
					unset($new_data["items"]);
					$new_data["group_id"] = $group_id;
					$new_data["parent_id"] = $parent_id;
					$new_data["order"] = $order;
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						
						
						if (empty($new_data["item_id"]))
							$new_data["item_id"] = \MenuUtil::insertMenuItem($brokers, $new_data);
						else if (!\MenuUtil::updateMenuItem($brokers, $new_data))
							return false;
						
						if (!empty($new_data["item_id"])) {
							$items[$idx]["item_id"] = $new_data["item_id"];
							
							if (!self::saveMenuItems($EVC, $brokers, $settings, $items[$idx]["items"], $error_message, $group_id, $new_data["item_id"]))
								return false;
						}
						else
							return false;
					}
					else
						return false;
				}
				
				$order++;
			}
		}
		
		return true;
	}
	
	private static function deleteOldItems($brokers, $old_items, $new_items) {
		$status = true;
		
		$new_items_decapsulated = \MenuUtil::decapsulateMenuGroupItems($new_items);
		$old_items_decapsulated = \MenuUtil::decapsulateMenuGroupItems($old_items);
		
		$new_items_ids = array();
		foreach ($new_items_decapsulated as $idx => $new_item)
			if (!empty($new_item["item_id"]))
				$new_items_ids[ $new_item["item_id"] ] = $idx;
		
		$t = count($old_items_decapsulated);
		for ($i = $t - 1; $i >= 0; --$i) {
			$old_item = $old_items_decapsulated[$i];
			$old_item_id = isset($old_item["item_id"]) ? $old_item["item_id"] : null;
			$idx = isset($new_items_ids[$old_item_id]) ? $new_items_ids[$old_item_id] : null;
			
			if (!isset($idx) && !\MenuUtil::deleteMenuItem($brokers, $old_item_id))
				$status = false;
		}
		
		return $status;
	}
}
?>
