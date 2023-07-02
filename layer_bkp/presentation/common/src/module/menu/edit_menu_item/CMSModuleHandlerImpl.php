<?php
namespace CMSModule\menu\edit_menu_item;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("menu/MenuUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Menu Item Details
		$item_id = $_GET["item_id"];
		$data = $item_id ? \MenuUtil::getMenuItemsByConditions($brokers, array("item_id" => $item_id), null, null, true) : null;
		$data = $data[0];
		
		//Preparing Menu Item
		if ($_POST) {
			if ($_POST["delete"] && $settings["allow_deletion"]) {
				$status = !$data || (\MenuUtil::deleteMenuItemsByGroupId($brokers, $data["item_id"]) && \MenuUtil::deleteMenuItem($brokers, $data["item_id"]));
			}
			else if ($_POST["save"]) {
				$group_id = $_POST["group_id"];
				$parent_id = $_POST["parent_id"];
				$label = $_POST["label"];
				$title = $_POST["title"];
				$class = $_POST["class"];
				$url = $_POST["url"];
				$previous_html = $_POST["previous_html"];
				$next_html = $_POST["next_html"];
				$order = $_POST["order"];
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("group_id" => $group_id, "label" => $label, "title" => $title, "class" => $class, "url" => $url, "previous_html" => $previous_html, "next_html" => $next_html));
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				}
				else {
					$new_data = $data;
					$new_data["group_id"] = $settings["show_group_id"] ? $group_id : $new_data["group_id"];
					$new_data["parent_id"] = $settings["show_parent_id"] ? $parent_id : $new_data["parent_id"];
					$new_data["label"] = $settings["show_label"] ? $label : $new_data["label"];
					$new_data["title"] = $settings["show_title"] ? $title : $new_data["title"];
					$new_data["class"] = $settings["show_class"] ? $class : $new_data["class"];
					$new_data["url"] = $settings["show_url"] ? $url : $new_data["url"];
					$new_data["previous_html"] = $settings["show_previous_html"] ? $previous_html : $new_data["previous_html"];
					$new_data["next_html"] = $settings["show_next_html"] ? $next_html : $new_data["next_html"];
					$new_data["order"] = $settings["show_order"] ? $order : $new_data["order"];
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					$new_data["parent_id"] = is_numeric($new_data["parent_id"]) ? $new_data["parent_id"] : 0;
					$new_data["order"] = is_numeric($new_data["order"]) ? $new_data["order"] : 0;
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						if ($settings["allow_insertion"] && empty($data["item_id"])) {
							$status = \MenuUtil::insertMenuItem($brokers, $new_data);
							if (strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "item_id=$status";
							}
						}
						else if ($settings["allow_update"] && $data["item_id"]) {
							$status = \MenuUtil::updateMenuItem($brokers, $new_data);
						}
					}
				}
			}
		}
		
		if ($_POST["save"]) {
			$form_data = array(
				"group_id" => $settings["show_group_id"] ? $group_id : $data["group_id"],
				"parent_id" => $settings["show_parent_id"] ? $parent_id : $data["parent_id"],
				"item_id" => $settings["show_item_id"] ? $item_id : $data["item_id"],
				"label" => $settings["show_label"] ? $label : $data["label"],
				"title" => $settings["show_title"] ? $title : $data["title"],
				"class" => $settings["show_class"] ? $class : $data["class"],
				"url" => $settings["show_url"] ? $url : $data["url"],
				"previous_html" => $settings["show_previous_html"] ? $previous_html : $data["previous_html"],
				"next_html" => $settings["show_next_html"] ? $next_html : $data["next_html"],
				"order" => $settings["show_order"] ? $order : $data["order"],
			);
			$form_data = $new_data ? array_merge($new_data, $form_data) : ($settings["allow_view"] && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = $settings["allow_view"] && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/menu/edit_menu_item.css';
		$settings["class"] = "module_edit_menu_item";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		$is_insertion = $settings["allow_insertion"] && !$data["item_id"];
		$is_editable = ($settings["allow_update"] && $data["item_id"]) || $is_insertion;
		
		if ($settings["show_item_id"])
			$settings["fields"]["item_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		if ($settings["show_group_id"]) {
			$groups = \MenuUtil::getAllMenuGroups($brokers);
			$group_options = array();
			$available_groups = array();
			
			if ($groups) {
				$t = count($groups);
				for ($i = 0; $i < $t; $i++) {
					if ($is_editable) 
						$group_options[] = array("value" => $groups[$i]["group_id"], "label" => $groups[$i]["name"]);
					else
						$available_groups[ $groups[$i]["group_id"] ] = $groups[$i]["name"];
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
