<?php
namespace CMSModule\user\edit_user_type;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting User Type Details
		$user_type_id = $_GET["user_type_id"];
		$data = $user_type_id ? \UserUtil::getUserTypesByConditions($brokers, array("user_type_id" => $user_type_id), null, true) : null;
		$data = $data[0];
		
		$reserved_user_type_ids = \UserUtil::getReservedUserTypeIds();
		
		//Preparing Action
		if ($_POST) {
			if (in_array($data["user_type_id"], $reserved_user_type_ids)) 
				$error_message = "This user type is native and cannot be edit!";
			else if ($_POST["delete"] && $settings["allow_deletion"]) 
				$status = !$data || \UserUtil::deleteUserType($brokers, $data["user_type_id"]);
			else if ($_POST["save"]) {
				$name = $_POST["name"];
				
				if (\CommonModuleUI::checkIfEmptyField($settings, "name", $name)) 
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, "name");
				else {
					$new_data = $data;
					$new_data["name"] = $settings["show_name"] ? $name : $new_data["name"];
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						if ($settings["allow_insertion"] && empty($data["user_type_id"])) {
							$status = \UserUtil::insertUserType($brokers, $new_data);
							if (strpos($settings["on_insert_ok_action"], "_redirect") !== false) 
								$settings["on_insert_ok_redirect_url"] .= (strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "user_type_id=$status";
						}
						else if ($settings["allow_update"] && $data["user_type_id"]) 
							$status = \UserUtil::updateUserType($brokers, $new_data);
					}
				}
			}
		}
		
		if ($_POST["save"]) {
			$form_data = array(
				"user_type_id" => $settings["show_user_type_id"] ? $user_type_id : $data["user_type_id"],
				"name" => $settings["show_name"] ? $name : $data["name"],
			);
			$form_data = $new_data ? array_merge($new_data, $form_data) : ($settings["allow_view"] && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else
			$form_data = $settings["allow_view"] && $data ? $data : array();
		
		if (in_array($data["user_type_id"], $reserved_user_type_ids) && !$error_message)
			$error_message = 'This is a reserved user type.';
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/user/edit_user_type.css';
		$settings["class"] = "module_edit_user_type";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		$is_insertion = $settings["allow_insertion"] && !$data["user_type_id"];
		
		if ($settings["allow_update"] && $data["user_type_id"] && in_array($data["user_type_id"], $reserved_user_type_ids))
			$settings["allow_update"] = false;
		
		if ($settings["allow_deletion"] && $data["user_type_id"] && in_array($data["user_type_id"], $reserved_user_type_ids))
			$settings["allow_deletion"] = false;
		
		if ($settings["show_user_type_id"]) 
			$settings["fields"]["user_type_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/edit_user_type", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
