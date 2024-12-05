<?php
namespace CMSModule\user\edit_user_type;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting User Type Details
		$user_type_id = isset($_GET["user_type_id"]) ? $_GET["user_type_id"] : null;
		$data = $user_type_id ? \UserUtil::getUserTypesByConditions($brokers, array("user_type_id" => $user_type_id), null, true) : null;
		$data = isset($data[0]) ? $data[0] : null;
		
		$reserved_user_type_ids = \UserUtil::getReservedUserTypeIds();
		
		//Preparing Action
		if (!empty($_POST)) {
			if (isset($data["user_type_id"]) && in_array($data["user_type_id"], $reserved_user_type_ids)) 
				$error_message = "This user type is native and cannot be edit!";
			else if (!empty($_POST["delete"]) && !empty($settings["allow_deletion"])) {
				$data_user_type_id = isset($data["user_type_id"]) ? $data["user_type_id"] : null;
				
				$status = !$data || \UserUtil::deleteUserType($brokers, $data_user_type_id);
			}
			else if (!empty($_POST["save"])) {
				$name = isset($_POST["name"]) ? $_POST["name"] : null;
				
				if (\CommonModuleUI::checkIfEmptyField($settings, "name", $name)) 
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, "name");
				else {
					$new_data = $data;
					$new_data["name"] = !empty($settings["show_name"]) ? $name : (isset($new_data["name"]) ? $new_data["name"] : null);
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						if (!empty($settings["allow_insertion"]) && empty($data["user_type_id"])) {
							$status = \UserUtil::insertUserType($brokers, $new_data);
							if (isset($settings["on_insert_ok_action"]) && strpos($settings["on_insert_ok_action"], "_redirect") !== false) 
								$settings["on_insert_ok_redirect_url"] .= (isset($settings["on_insert_ok_redirect_url"]) && strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "user_type_id=$status";
						}
						else if (!empty($settings["allow_update"]) && !empty($data["user_type_id"]))
							$status = \UserUtil::updateUserType($brokers, $new_data);
					}
				}
			}
		}
		
		if (!empty($_POST["save"])) {
			$form_data = array(
				"user_type_id" => !empty($settings["show_user_type_id"]) ? $user_type_id : (isset($data["user_type_id"]) ? $data["user_type_id"] : null),
				"name" => !empty($settings["show_name"]) ? $name : (isset($data["name"]) ? $data["name"] : null),
			);
			$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : (!empty($settings["allow_view"]) && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else
			$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		
		if (in_array($data["user_type_id"], $reserved_user_type_ids) && !$error_message)
			$error_message = 'This is a reserved user type.';
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/user/edit_user_type.css';
		$settings["class"] = "module_edit_user_type";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		$is_insertion = !empty($settings["allow_insertion"]) && empty($data["user_type_id"]);
		
		if (!empty($settings["allow_update"]) && !empty($data["user_type_id"]) && in_array($data["user_type_id"], $reserved_user_type_ids))
			$settings["allow_update"] = false;
		
		if (!empty($settings["allow_deletion"]) && !empty($data["user_type_id"]) && in_array($data["user_type_id"], $reserved_user_type_ids))
			$settings["allow_deletion"] = false;
		
		if (!empty($settings["show_user_type_id"])) 
			$settings["fields"]["user_type_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/edit_user_type", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
