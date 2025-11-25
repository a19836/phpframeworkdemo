<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

namespace CMSModule\message\edit_message;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("message/MessageUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Message Details
		$message_id = isset($_GET["message_id"]) ? $_GET["message_id"] : null;
		$from_user_id = isset($_GET["from_user_id"]) ? $_GET["from_user_id"] : null;
		$to_user_id = isset($_GET["to_user_id"]) ? $_GET["to_user_id"] : null;
		
		$data = $message_id && $from_user_id && $to_user_id ? \MessageUtil::getMessagesByConditions($brokers, array("message_id" => $message_id, "from_user_id" => $from_user_id, "to_user_id" => $to_user_id), null, null, true) : null;
		$data = isset($data[0]) ? $data[0] : null;
		
		//Preparing Action
		if (!empty($_POST)) {
			if (!empty($_POST["delete"]) && !empty($settings["allow_deletion"])) {
				$data_message_id = isset($data["message_id"]) ? $data["message_id"] : null;
				$data_from_user_id = isset($data["from_user_id"]) ? $data["from_user_id"] : null;
				$data_to_user_id = isset($data["to_user_id"]) ? $data["to_user_id"] : null;
				
				$status = !$data || \MessageUtil::deleteMessage($brokers, $data_message_id, $data_from_user_id, $data_to_user_id);
			}
			else if (!empty($_POST["save"])) {
				$from_user_id = isset($_POST["from_user_id"]) ? $_POST["from_user_id"] : null;
				$to_user_id = isset($_POST["to_user_id"]) ? $_POST["to_user_id"] : null;
				$subject = isset($_POST["subject"]) ? $_POST["subject"] : null;
				$content = isset($_POST["content"]) ? $_POST["content"] : null;
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("from_user_id" => $from_user_id, "to_user_id" => $to_user_id, "subject" => $subject, "content" => $content));
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				}
				else {
					$new_data = array();
					$new_data["from_user_id"] = !empty($settings["show_from_user_id"]) ? $from_user_id : null;
					$new_data["to_user_id"] = !empty($settings["show_to_user_id"]) ? $to_user_id : null;
					$new_data["subject"] = !empty($settings["show_subject"]) ? $subject : null;
					$new_data["content"] = !empty($settings["show_content"]) ? $content : null;
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						if (!empty($settings["allow_insertion"]) && empty($data["message_id"])) {
							$status = \MessageUtil::insertMessage($brokers, $new_data);
							if (isset($settings["on_insert_ok_action"]) && strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (isset($settings["on_insert_ok_redirect_url"]) && strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "message_id=$status&from_user_id={$new_data['from_user_id']}&to_user_id={$new_data['to_user_id']}";
							}
						}
					}
				}
			}
		}
		
		if (!empty($_POST["save"])) {
			$form_data = array(
				"message_id" => !empty($settings["show_message_id"]) ? $message_id : (isset($data["message_id"]) ? $data["message_id"] : null),
				"from_user_id" => !empty($settings["show_from_user_id"]) ? $from_user_id : (isset($data["from_user_id"]) ? $data["from_user_id"] : null),
				"to_user_id" => !empty($settings["show_to_user_id"]) ? $to_user_id : (isset($data["to_user_id"]) ? $data["to_user_id"] : null),
				"subject" => !empty($settings["show_subject"]) ? $subject : (isset($data["subject"]) ? $data["subject"] : null),
				"content" => !empty($settings["show_content"]) ? $content : (isset($data["content"]) ? $data["content"] : null),
			);
			$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : (!empty($settings["allow_view"]) && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/message/edit_message.css';
		$settings["class"] = "module_edit_message";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		$settings["allow_update"] = false;
		$is_insertion = !empty($settings["allow_insertion"]) && empty($data["message_id"]);
		
		if (!empty($settings["allow_deletion"]) && !$data)
			$settings["allow_deletion"] = false;
		
		if (!empty($settings["show_message_id"]))
			$settings["fields"]["message_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		if (!empty($settings["show_from_user_id"])) 
			\CommonModuleUtil::prepareUserIdFormSettingsField($EVC, $settings, $is_insertion, "from_user_id");
		
		if (!empty($settings["show_to_user_id"]))
			\CommonModuleUtil::prepareUserIdFormSettingsField($EVC, $settings, $is_insertion, "to_user_id");
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "message/edit_message", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
