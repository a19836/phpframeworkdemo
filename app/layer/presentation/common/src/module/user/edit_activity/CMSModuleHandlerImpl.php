<?php
namespace CMSModule\user\edit_activity;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Activity Details
		$activity_id = $_GET["activity_id"];
		$data = $activity_id ? \UserUtil::getActivitiesByConditions($brokers, array("activity_id" => $activity_id), null, true) : null;
		$data = $data[0];
		
		$reserved_activity_ids = \UserUtil::getReservedActivityIds();
	
		//Preparing Action
		if ($_POST) {
			if (in_array($data["activity_id"], $reserved_activity_ids)) {
				$error_message = "This activity is native and cannot be edit!";
			}
			else if ($_POST["delete"] && $settings["allow_deletion"]) {
				$status = !$data || \UserUtil::deleteActivity($brokers, $data["activity_id"]);
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
						if ($settings["allow_insertion"] && empty($data["activity_id"])) {
							$status = \UserUtil::insertActivity($brokers, $new_data);
							if (strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "activity_id=$status";
							}
						}
						else if ($settings["allow_update"] && $data["activity_id"]) {
							$status = \UserUtil::updateActivity($brokers, $new_data);
						}
					}
				}
			}
		}
		
		if ($_POST["save"]) {
			$form_data = array(
				"activity_id" => $settings["show_activity_id"] ? $activity_id : $data["activity_id"],
				"name" => $settings["show_name"] ? $name : $data["name"],
			);
			$form_data = $new_data ? array_merge($new_data, $form_data) : ($settings["allow_view"] && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else 
			$form_data = $settings["allow_view"] && $data ? $data : array();
		
		if (in_array($data["activity_id"], $reserved_activity_ids) && !$error_message)
			$error_message = 'This is a reserved activity.';
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/user/edit_activity.css';
		$settings["class"] = "module_edit_activity";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		$is_insertion = $settings["allow_insertion"] && !$data["activity_id"];
		
		if ($settings["allow_update"] && $data["activity_id"] && in_array($data["activity_id"], $reserved_activity_ids)) 
			$settings["allow_update"] = false;
		
		if ($settings["allow_deletion"] && $data["activity_id"] && in_array($data["activity_id"], $reserved_activity_ids)) 
			$settings["allow_deletion"] = false;
		
		if ($settings["show_activity_id"])
			$settings["fields"]["activity_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/edit_activity", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
