<?php
namespace CMSModule\menu\edit_menu_group;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("menu/MenuUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Menu Group Details
		$group_id = isset($_GET["group_id"]) ? $_GET["group_id"] : null;
		$data = $group_id ? \MenuUtil::getMenuGroupsByConditions($brokers, array("group_id" => $group_id), null, null, true) : null;
		$data = isset($data[0]) ? $data[0] : null;
		
		//Preparing Menu Group
		if (!empty($_POST)) {
			if (!empty($_POST["delete"]) && !empty($settings["allow_deletion"])) {
				$data_group_id = isset($data["group_id"]) ? $data["group_id"] : null;
				
				$status = !$data || (\MenuUtil::deleteMenuItemsByGroupId($brokers, $data_group_id) && \MenuUtil::deleteMenuGroup($brokers, $data_group_id));
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
		}
		
		if (!empty($_POST["save"])) {
			$form_data = array(
				"group_id" => !empty($settings["show_group_id"]) ? $group_id : (isset($data["group_id"]) ? $data["group_id"] : null),
				"name" => !empty($settings["show_name"]) ? $name : (isset($data["name"]) ? $data["name"] : null),
			);
			$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : (!empty($settings["allow_view"]) && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/menu/edit_menu_group.css';
		$settings["class"] = "module_edit_menu_group";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		$is_insertion = !empty($settings["allow_insertion"]) && empty($data["group_id"]);
		
		if (!empty($settings["show_group_id"]))
			$settings["fields"]["group_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "menu/edit_menu_group", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
