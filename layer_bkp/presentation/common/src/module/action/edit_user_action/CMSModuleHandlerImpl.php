<?php
namespace CMSModule\action\edit_user_action;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("action/ActionUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting User Actions
		$user_id = $_GET["user_id"];
		$action_id = $_GET["action_id"];
		$object_type_id = $_GET["object_type_id"];
		$object_id = $_GET["object_id"];
		$time = $_GET["time"];
		
		$data = $user_id && $action_id && $object_type_id && $object_id && isset($time) ? \ActionUtil::getUserActionsByConditions($brokers, array("user_id" => $user_id, "action_id" => $action_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time), null, null, true) : null;
		$data = $data[0];
		
		//Preparing Action
		if ($_POST) {
			if ($_POST["delete"] && $settings["allow_deletion"]) {
				$status = !$data || \ActionUtil::deleteUserAction($brokers, $data["user_id"], $data["action_id"], $data["object_type_id"], $data["object_id"], $data["time"]);
			}
			else if ($_POST["save"]) {
				$value = $_POST["value"];
				
				if ($settings["allow_insertion"] && empty($data)) {
					$user_id = $_POST["user_id"];
					$action_id = $_POST["action_id"];
					$object_type_id = $_POST["object_type_id"];
					$object_id = $_POST["object_id"];
					$time = $_POST["time"];
					
					$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("user_id" => $user_id, "action_id" => $action_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time));
					if ($empty_field_name) {
						$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
					}
					else {
						$new_data = array(
							"user_id" => $user_id,
							"action_id" => $action_id,
							"object_type_id" => $object_type_id,
							"object_id" => $object_id,
							"time" => $time,
							"value" => $value,
						);
						
						\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
						
						if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
							$status = \ActionUtil::insertUserAction($brokers, $new_data);
							if (strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "user_id=$user_id&action_id=$action_id&object_type_id=$object_type_id&object_id=$object_id&time=$time";
							}
						}
					}
				}
				else if ($settings["allow_update"] && $data) {
					if (\CommonModuleUI::checkIfEmptyField($settings, "value", $value)) {
						$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, "value");
					}
					else {
						$new_data = $data;
						$new_data["value"] = $value;
						
						\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
						
						if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
							$status = \ActionUtil::updateUserAction($brokers, $new_data);
						}
					}
				}
			}
		}
		
		if ($_POST["save"]) {
			$form_data = array(
				"user_id" => $settings["show_user_id"] ? $user_id : $data["user_id"],
				"action_id" => $settings["show_action_id"] ? $action_id : $data["action_id"],
				"object_type_id" => $settings["show_object_type_id"] ? $object_type_id : $data["object_type_id"],
				"object_id" => $settings["show_object_id"] ? $object_id : $data["object_id"],
				"time" => $settings["show_time"] ? $time : $data["time"],
				"value" => $settings["show_value"] ? $value : $data["value"],
			);
			$form_data = $new_data ? array_merge($new_data, $form_data) : ($settings["allow_view"] && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else
			$form_data = $settings["allow_view"] && $data ? $data : array();
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/action/edit_user_action.css';
		$settings["class"] = "module_edit_user_action";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		$is_insertion = $settings["allow_insertion"] && !$data;
		
		if ($settings["show_action_id"]) {
			$actions = \ActionUtil::getAllActions($brokers);
			$action_options = array();
			$available_actions = array();
			
			if ($actions) {
				$t = count($actions);
				for ($i = 0; $i < $t; $i++) {
					if ($is_insertion) 
						$action_options[] = array("value" => $actions[$i]["action_id"], "label" => $actions[$i]["name"]);
					else
						$available_actions[ $actions[$i]["action_id"] ] = $actions[$i]["name"];
				}
			}
			
			$settings["fields"]["action_id"]["field"]["input"]["type"] = $is_insertion ? "select" : "label";
			$settings["fields"]["action_id"]["field"]["input"]["options"] = $action_options;
			$settings["fields"]["action_id"]["field"]["input"]["available_values"] = $available_actions;
		}
		
		if ($settings["show_user_id"]) 
			\CommonModuleUtil::prepareUserIdFormSettingsField($EVC, $settings, $is_insertion);
		
		if ($settings["show_object_type_id"])
			\CommonModuleUtil::prepareObjectTypeIdFormSettingsField($EVC, $settings, $is_insertion);
		
		if ($settings["show_object_id"])
			$settings["fields"]["object_id"]["field"]["input"]["type"] = $is_insertion ? "text" : "label";
		
		if ($settings["show_time"])
			$settings["fields"]["time"]["field"]["input"]["type"] = $is_insertion ? "text" : "label";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "action/edit_user_action", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
