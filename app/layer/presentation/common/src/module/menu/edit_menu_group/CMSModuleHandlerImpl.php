<?php
namespace CMSModule\menu\edit_menu_group;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("menu/MenuUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Menu Group Details
		$group_id = $_GET["group_id"];
		$data = $group_id ? \MenuUtil::getMenuGroupsByConditions($brokers, array("group_id" => $group_id), null, null, true) : null;
		$data = $data[0];
		
		//Preparing Menu Group
		if ($_POST) {
			if ($_POST["delete"] && $settings["allow_deletion"]) {
				$status = !$data || (MenuUtil::deleteMenuItemsByGroupId($brokers, $data["group_id"]) && \MenuUtil::deleteMenuGroup($brokers, $data["group_id"]));
			}
			else if ($_POST["save"]) {
				$name = $_POST["name"];
			
				if (\CommonModuleUI::checkIfEmptyField($settings, "name", $name)) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, "name");
				}
				else {
					$new_data = $data;
					$new_data["name"] = $settings["show_name"] ? $name : $new_data["name"];
				
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						$new_data["object_groups"] = $settings["object_to_objects"];
						
						if ($settings["allow_insertion"] && empty($data["group_id"])) {
							$status = \MenuUtil::insertMenuGroup($brokers, $new_data);
							if (strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "group_id=$status";
							}
						}
						else if ($settings["allow_update"] && $data["group_id"]) {
							$status = \MenuUtil::updateMenuGroup($brokers, $new_data);
						}
					}
				}
			}
		}
		
		if ($_POST["save"]) {
			$form_data = array(
				"group_id" => $settings["show_group_id"] ? $group_id : $data["group_id"],
				"name" => $settings["show_name"] ? $name : $data["name"],
			);
			$form_data = $new_data ? array_merge($new_data, $form_data) : ($settings["allow_view"] && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = $settings["allow_view"] && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/menu/edit_menu_group.css';
		$settings["class"] = "module_edit_menu_group";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		$is_insertion = $settings["allow_insertion"] && !$data["group_id"];
		
		if ($settings["show_group_id"])
			$settings["fields"]["group_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "menu/edit_menu_group", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
