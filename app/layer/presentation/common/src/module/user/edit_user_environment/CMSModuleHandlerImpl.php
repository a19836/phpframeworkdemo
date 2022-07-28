<?php
namespace CMSModule\user\edit_user_environment;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		include_once $EVC->getModulePath("user/UserModuleUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting User Environments
		$user_id = $_GET["user_id"];
		$environment_id = $_GET["environment_id"];
		
		$data = $user_id && $environment_id ? \UserUtil::getUserEnvironmentsByConditions($brokers, array("user_id" => $user_id, "environment_id" => $environment_id), null, null, true) : null;
		$data = $data[0];
		
		//Preparing Action
		if ($_POST) {
			if ($_POST["delete"] && $settings["allow_deletion"]) {
				$status = !$data || \UserUtil::deleteUserEnvironment($brokers, $data["user_id"], $data["environment_id"]);
			}
			else if ($_POST["save"]) {
				$new_user_id = $_POST["user_id"];
				$new_environment_id = $_POST["environment_id"];
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("user_id" => $new_user_id, "environment_id" => $new_environment_id));
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				}
				else {
					if ($settings["allow_insertion"] && empty($data)) {
						$new_data = $data;
						$new_data["user_id"] = $new_user_id;
						$new_data["environment_id"] = $new_environment_id;
						
						\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
						
						if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
							$status = \UserUtil::insertUserEnvironment($brokers, $new_data);
							if (strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "user_id=${new_data['user_id']}&environment_id=${new_data['environment_id']}";
							}
						}
					}
					else if ($settings["allow_update"] && $data) {
						$new_data = array();
						$new_data["old_user_id"] = $data["user_id"];
						$new_data["old_environment_id"] = $data["environment_id"];
						$new_data["new_user_id"] = $settings["show_user_id"] ? $new_user_id : $data["user_id"];
						$new_data["new_environment_id"] = $settings["show_environment_id"] ? $new_environment_id : $data["environment_id"];
						
						\CommonModuleUI::prepareFieldWithDefaultValue($settings, "user_id", $new_data["new_user_id"]);
						\CommonModuleUI::prepareFieldWithDefaultValue($settings, "environment_id", $new_data["new_environment_id"]);
						
						$fields_to_validade = array("user_id" => $new_data["new_user_id"], "environment_id" => $new_data["new_environment_id"]);
						
						if (\CommonModuleUI::areFieldsValid($EVC, $settings, $fields_to_validade, $error_message)) {
							$status = \UserUtil::updateUserEnvironment($brokers, $new_data);
							if (strpos($settings["on_update_ok_action"], "_redirect") !== false) {
								$settings["on_update_ok_redirect_url"] .= (strpos($settings["on_update_ok_redirect_url"], "?") !== false ? "&" : "?") . "user_id=${new_data['new_user_id']}&environment_id=${new_data['new_environment_id']}";
							}
						}
					}
				}
			}
		}
		
		if ($_POST["save"]) {
			$form_data = array(
				"user_id" => $settings["show_user_id"] ? $new_user_id : $data["user_id"],
				"environment_id" => $settings["show_environment_id"] ? $new_environment_id : $data["environment_id"],
			);
			$form_data = $new_data ? array_merge($new_data, $form_data) : ($settings["allow_view"] && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = $settings["allow_view"] && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/user/edit_user_environment.css';
		$settings["class"] = "module_edit_user_environment";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		$is_editable = ($settings["allow_update"] && $data) || ($settings["allow_insertion"] && !$data);
		\CMSModule\user\UserModuleUtil::prepareFormSettingsFields($EVC, $settings, $is_editable);
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/edit_user_environment", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
