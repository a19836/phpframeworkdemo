<?php
namespace CMSModule\message\edit_message;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("message/MessageUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Message Details
		$message_id = $_GET["message_id"];
		$from_user_id = $_GET["from_user_id"];
		$to_user_id = $_GET["to_user_id"];
		$data = $message_id && $from_user_id && $to_user_id ? \MessageUtil::getMessagesByConditions($brokers, array("message_id" => $message_id, "from_user_id" => $from_user_id, "to_user_id" => $to_user_id), null, null, true) : null;
		$data = $data[0];
		
		//Preparing Action
		if ($_POST) {
			if ($_POST["delete"] && $settings["allow_deletion"]) {
				$status = !$data || \MessageUtil::deleteMessage($brokers, $data["message_id"], $data["from_user_id"], $data["to_user_id"]);
			}
			else if ($_POST["save"]) {
				$from_user_id = $_POST["from_user_id"];
				$to_user_id = $_POST["to_user_id"];
				$subject = $_POST["subject"];
				$content = $_POST["content"];
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id, "subject" => $subject, "content" => $content));
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				}
				else {
					$new_data = array();
					$new_data["from_user_id"] = $settings["show_from_user_id"] ? $from_user_id : null;
					$new_data["to_user_id"] = $settings["show_to_user_id"] ? $to_user_id : null;
					$new_data["subject"] = $settings["show_subject"] ? $subject : null;
					$new_data["content"] = $settings["show_content"] ? $content : null;
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						if ($settings["allow_insertion"] && empty($data["message_id"])) {
							$status = \MessageUtil::insertMessage($brokers, $new_data);
							if (strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "message_id=$status&from_user_id={$new_data['from_user_id']}&to_user_id={$new_data['to_user_id']}";
							}
						}
					}
				}
			}
		}
		
		if ($_POST["save"]) {
			$form_data = array(
				"message_id" => $settings["show_message_id"] ? $message_id : $data["message_id"],
				"from_user_id" => $settings["show_from_user_id"] ? $from_user_id : $data["from_user_id"],
				"to_user_id" => $settings["show_to_user_id"] ? $to_user_id : $data["to_user_id"],
				"subject" => $settings["show_subject"] ? $subject : $data["subject"],
				"content" => $settings["show_content"] ? $content : $data["content"],
			);
			$form_data = $new_data ? array_merge($new_data, $form_data) : ($settings["allow_view"] && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = $settings["allow_view"] && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/message/edit_message.css';
		$settings["class"] = "module_edit_message";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		$settings["allow_update"] = false;
		$is_insertion = $settings["allow_insertion"] && !$data["message_id"];
		
		if ($settings["allow_deletion"] && !$data)
			$settings["allow_deletion"] = false;
		
		if ($settings["show_message_id"])
			$settings["fields"]["message_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		if ($settings["show_from_user_id"]) 
			\CommonModuleUtil::prepareUserIdFormSettingsField($EVC, $settings, $is_insertion, "from_user_id");
		
		if ($settings["show_to_user_id"]) 
			\CommonModuleUtil::prepareUserIdFormSettingsField($EVC, $settings, $is_insertion, "to_user_id");
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "message/edit_message", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
