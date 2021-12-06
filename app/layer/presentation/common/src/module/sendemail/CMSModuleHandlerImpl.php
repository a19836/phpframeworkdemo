<?php
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
		
		if ($_POST["save"]) {
			$from = $_POST["from"];
			$to = $_POST["to"];
			$reply_to = $_POST["reply_to"];
			$name = $_POST["name"];
			$subject = $_POST["subject"];
			$message = $_POST["message"];
			
			$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("from" => $from, "to" => $to, "reply_to" => $reply_to, "name" => $name, "subject" => $subject, "message" => $message));
			
			if ($empty_field_name)
				$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
			else {
				$new_data = array();
				$new_data["from"] = $settings["show_from"] ? $from : "";
				$new_data["to"] = $settings["show_to"] ? $to : "";
				$new_data["reply_to"] = $settings["show_reply_to"] ? $reply_to : "";
				$new_data["name"] = $settings["show_name"] ? $name : "";
				$new_data["subject"] = $settings["show_subject"] ? $subject : "";
				$new_data["message"] = $settings["show_message"] ? $message : "";
				
				\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
				
				if (!$error_message && \CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
					$email_subject = $settings["subject"];
					$email_message = $settings["message"];
					
					//prepare subject and message with dynamic POST attributes
					$pos_data = array_merge($_POST, $new_data);
					
					foreach ($pos_data as $k => $v) {
						$email_subject = str_replace("#$k#", $v, $email_subject);
						$email_message = str_replace("#$k#", $v, $email_message);
					}
					
					$Email = new \SmtpEmail($settings["smtp_host"], $settings["smtp_port"], $settings["smtp_user"], $settings["smtp_pass"], $settings["smtp_secure"]);

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
		
		if ($_POST["save"]) {
			if ($status && $settings["reset_after_send"])
				$form_data = array();
			else {
				$form_data = array(
					"from" => $settings["show_from"] ? $from : "",
					"to" => $settings["show_to"] ? $to : "",
					"reply_to" => $settings["show_reply_to"] ? $reply_to : "",
					"name" => $settings["show_name"] ? $name : "",
					"subject" => $settings["show_subject"] ? $subject : "",
					"message" => $settings["show_message"] ? $message : "",
				);
				
				$form_data = $new_data ? array_merge($new_data, $form_data) : $form_data;
			}
		}
		else
			$form_data = array();
		
		$settings["data"] = array();
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/sendemail/style.css';
		$settings["class"] = "module_send_email";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		//Add join point creating new fields in the form.
		$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("New Send Email bottom fields", array(
			"EVC" => &$EVC,
			"settings" => &$settings,
		));
		
		$html .= \CommonModuleUI::getFormHtml($EVC, $settings);
				
		return $html;
	}
}
?>
