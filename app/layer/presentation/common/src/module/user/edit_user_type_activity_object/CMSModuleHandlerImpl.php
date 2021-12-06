<?php
namespace CMSModule\user\edit_user_type_activity_object;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		include_once $EVC->getModulePath("user/UserModuleUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting User Type Activities
		$user_type_id = $_GET["user_type_id"];
		$activity_id = $_GET["activity_id"];
		$object_type_id = $_GET["object_type_id"];
		$object_id = $_GET["object_id"];
		
		$data = $user_type_id && $activity_id && $object_type_id && $object_id ? \UserUtil::getUserTypeActivityObjectsByConditions($brokers, array("user_type_id" => $user_type_id, "activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id), null, null, true) : null;
		$data = $data[0];
		
		//Preparing Action
		if ($_POST) {
			if ($_POST["delete"] && $settings["allow_deletion"]) {
				$status = !$data || \UserUtil::deleteUserTypeActivityObject($brokers, $data["user_type_id"], $data["activity_id"], $data["object_type_id"], $data["object_id"]);
			}
			else if ($_POST["save"]) {
				$new_user_type_id = $_POST["user_type_id"];
				$new_activity_id = $_POST["activity_id"];
				$new_object_type_id = $_POST["object_type_id"];
				$new_object_id = $_POST["object_id"];
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("user_type_id" => $new_user_type_id, "activity_id" => $new_activity_id, "object_type_id" => $new_object_type_id, "object_id" => $new_object_id));
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				}
				else {
					if ($settings["allow_insertion"] && empty($data)) {
						$new_data = $data;
						$new_data["user_type_id"] = $new_user_type_id;
						$new_data["activity_id"] = $new_activity_id;
						$new_data["object_type_id"] = $new_object_type_id;
						$new_data["object_id"] = $new_object_id;
						
						\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
						
						if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
							$status = \UserUtil::insertUserTypeActivityObject($brokers, $new_data);
							if (strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "user_type_id=${new_data['user_type_id']}&activity_id=${new_data['activity_id']}&object_type_id=${new_data['object_type_id']}&object_id=${new_data['object_id']}";
							}
						}
					}
					else if ($settings["allow_update"] && $data) {
						$new_data = array();
						$new_data["old_user_type_id"] = $data["user_type_id"];
						$new_data["old_activity_id"] = $data["activity_id"];
						$new_data["old_object_type_id"] = $data["object_type_id"];
						$new_data["old_object_id"] = $data["object_id"];
						$new_data["new_user_type_id"] = $settings["show_user_type_id"] ? $new_user_type_id : $data["user_type_id"];
						$new_data["new_activity_id"] = $settings["show_activity_id"] ? $new_activity_id : $data["activity_id"];
						$new_data["new_object_type_id"] = $settings["show_object_type_id"] ? $new_object_type_id : $data["object_type_id"];
						$new_data["new_object_id"] = $settings["show_object_id"] ? $new_object_id : $data["object_id"];
						
						\CommonModuleUI::prepareFieldWithDefaultValue($settings, "user_type_id", $new_data["new_user_type_id"]);
						\CommonModuleUI::prepareFieldWithDefaultValue($settings, "activity_id", $new_data["new_activity_id"]);
						\CommonModuleUI::prepareFieldWithDefaultValue($settings, "object_type_id", $new_data["new_object_type_id"]);
						\CommonModuleUI::prepareFieldWithDefaultValue($settings, "object_id", $new_data["new_object_id"]);
						
						$fields_to_validade = array("user_type_id" => $new_data["new_user_type_id"], "activity_id" => $new_data["new_activity_id"], "object_type_id" => $new_data["new_object_type_id"], "object_id" => $new_data["new_object_id"]);
						
						if (\CommonModuleUI::areFieldsValid($EVC, $settings, $fields_to_validade, $error_message)) {
							$status = \UserUtil::updateUserTypeActivityObject($brokers, $new_data);
							if (strpos($settings["on_update_ok_action"], "_redirect") !== false) {
								$settings["on_update_ok_redirect_url"] .= (strpos($settings["on_update_ok_redirect_url"], "?") !== false ? "&" : "?") . "user_type_id=${new_data['new_user_type_id']}&activity_id=${new_data['new_activity_id']}&object_type_id=${new_data['new_object_type_id']}&object_id=${new_data['new_object_id']}";
							}
						}
					}
				}
			}
		}
		
		if ($_POST["save"]) {
			$form_data = array(
				"user_type_id" => $settings["show_user_type_id"] ? $new_user_type_id : $data["user_type_id"],
				"activity_id" => $settings["show_activity_id"] ? $new_activity_id : $data["activity_id"],
				"object_type_id" => $settings["show_object_type_id"] ? $new_object_type_id : $data["object_type_id"],
				"object_id" => $settings["show_object_id"] ? $new_object_id : $data["object_id"],
			);
			$form_data = $new_data ? array_merge($new_data, $form_data) : ($settings["allow_view"] && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else
			$form_data = $settings["allow_view"] && $data ? $data : array();
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/user/edit_user_type_activity_object.css';
		$settings["class"] = "module_edit_user_type_activity_object";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		$is_editable = ($settings["allow_update"] && $data) || ($settings["allow_insertion"] && !$data);
		\CMSModule\user\UserModuleUtil::prepareFormSettingsFields($EVC, $settings, $is_editable);
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/edit_user_type_activity_object", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
