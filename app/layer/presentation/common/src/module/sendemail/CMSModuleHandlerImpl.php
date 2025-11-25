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

namespace CMSModule\sendemail;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once get_lib("org.phpframework.util.web.SmtpEmail");
		
		//Add Join Point
		$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Preparing send email", array(
			"EVC" => $EVC,
			"settings" => &$settings,
		), "Use this join point to change the POST data.");
		
		if (!empty($_POST["save"])) {
			$from = isset($_POST["from"]) ? $_POST["from"] : null;
			$to = isset($_POST["to"]) ? $_POST["to"] : null;
			$reply_to = isset($_POST["reply_to"]) ? $_POST["reply_to"] : null;
			$name = isset($_POST["name"]) ? $_POST["name"] : null;
			$subject = isset($_POST["subject"]) ? $_POST["subject"] : null;
			$message = isset($_POST["message"]) ? $_POST["message"] : null;
			
			$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("from" => $from, "to" => $to, "reply_to" => $reply_to, "name" => $name, "subject" => $subject, "message" => $message));
			
			if ($empty_field_name)
				$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
			else {
				$new_data = array();
				$new_data["from"] = !empty($settings["show_from"]) ? $from : "";
				$new_data["to"] = !empty($settings["show_to"]) ? $to : "";
				$new_data["reply_to"] = !empty($settings["show_reply_to"]) ? $reply_to : "";
				$new_data["name"] = !empty($settings["show_name"]) ? $name : "";
				$new_data["subject"] = !empty($settings["show_subject"]) ? $subject : "";
				$new_data["message"] = !empty($settings["show_message"]) ? $message : "";
				
				\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
				
				if (empty($error_message) && \CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
					$email_subject = isset($settings["subject"]) ? $settings["subject"] : null;
					$email_message = isset($settings["message"]) ? $settings["message"] : null;
					
					//prepare subject and message with dynamic POST attributes
					$pos_data = array_merge($_POST, $new_data);
					
					foreach ($pos_data as $k => $v) {
						$email_subject = str_replace("#$k#", $v, $email_subject);
						$email_message = str_replace("#$k#", $v, $email_message);
					}
					
					$smtp_host = isset($settings["smtp_host"]) ? $settings["smtp_host"] : null;
					$smtp_port = isset($settings["smtp_port"]) ? $settings["smtp_port"] : null;
					$smtp_user = isset($settings["smtp_user"]) ? $settings["smtp_user"] : null;
					$smtp_pass = isset($settings["smtp_pass"]) ? $settings["smtp_pass"] : null;
					$smtp_secure = isset($settings["smtp_secure"]) ? $settings["smtp_secure"] : null;
					
					$Email = new \SmtpEmail($smtp_host, $smtp_port, $smtp_user, $smtp_pass, $smtp_secure);

					$status = $Email->send($new_data["from"], $new_data["name"], $new_data["reply_to"], null, $new_data["to"], null, $email_subject, $email_message);

					if (!$status)
						$error_message = "Error sending information: " . $Email->getErrorInfo();
					else {
						//Add Join Point creating a new action of some kind
						$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull sending email action", array(
							"EVC" => &$EVC,
							"data" => &$new_data,
							"error_message" => &$error_message,
						));
					}
				}
			}
		}
		
		if (!empty($_POST["save"])) {
			if (!empty($status) && !empty($settings["reset_after_send"]))
				$form_data = array();
			else {
				$form_data = array(
					"from" => !empty($settings["show_from"]) ? $from : "",
					"to" => !empty($settings["show_to"]) ? $to : "",
					"reply_to" => !empty($settings["show_reply_to"]) ? $reply_to : "",
					"name" => !empty($settings["show_name"]) ? $name : "",
					"subject" => !empty($settings["show_subject"]) ? $subject : "",
					"message" => !empty($settings["show_message"]) ? $message : "",
				);
				
				$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : $form_data;
			}
		}
		else
			$form_data = array();
		
		$settings["data"] = array();
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/sendemail/style.css';
		$settings["class"] = "module_send_email";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		//Add join point creating new fields in the form.
		$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("New Send Email bottom fields", array(
			"EVC" => &$EVC,
			"settings" => &$settings,
		));
		
		$html = \CommonModuleUI::getFormHtml($EVC, $settings);
				
		return $html;
	}
}
?>
