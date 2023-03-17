<?php
namespace CMSModule\user\register;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("user/UserModuleUI", $common_project_name);
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleTableExtraAttributesUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$CommonModuleTableExtraAttributesUtil = new \CommonModuleTableExtraAttributesUtil($this, $GLOBALS["default_db_driver"], $settings, "user");
		
		//Preparing Action
		if ($_POST) {
			$user_type_id = $settings["user_type_id"];
			$username = trim($_POST["username"]);
			$password = trim($_POST["password"]);
			$name = $_POST["name"];
			$email = $_POST["email"];
			$security_question_1 = $_POST["security_question_1"];
			$security_answer_1 = $_POST["security_answer_1"];
			$security_question_2 = $_POST["security_question_2"];
			$security_answer_2 = $_POST["security_answer_2"];
			$security_question_3 = $_POST["security_question_3"];
			$security_answer_3 = $_POST["security_answer_3"];
			
			$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("username" => $username, "password" => $password, "name" => $name, "email" => $email, "security_question_1" => $security_question_1, "security_answer_1" => $security_answer_1, "security_question_2" => $security_question_2, "security_answer_2" => $security_answer_2, "security_question_3" => $security_question_3, "security_answer_3" => $security_answer_3));
			
			if (!$empty_field_name)
				$empty_field_name = $CommonModuleTableExtraAttributesUtil->checkIfEmptyFields($settings, $_POST);
			
			if ($empty_field_name)
				$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
			else {
				if ($settings["show_username"] && $username) {
					$users = \UserUtil::getUsersByConditionsAccordingWithUserEnvironmentsSettings($brokers, $settings["user_environments"], array("username" => $username), null, null, true);	
					$user_exists = $users[0]["user_id"];
					
					if ($user_exists) {
						$username_label = \CommonModuleUI::getFieldLabel($settings, "username");
						$error_message = translateProjectText($EVC, "This #username# already exists! Please choose another #username#...");
						$error_message = str_replace("#username#", $username_label, $error_message);
					}
				}
				
				if (!$error_message) {
					$data = array(
						"username" => $settings["show_username"] ? $username : "",
						"password" => $settings["show_password"] ? $password : "",
						"name" => $settings["show_name"] ? $name : "",
						"email" => $settings["show_email"] ? $email : "",
						"security_question_1" => $settings["show_security_question_1"] ? $security_question_1 : "",
						"security_answer_1" => $settings["show_security_answer_1"] ? $security_answer_1 : "",
						"security_question_2" => $settings["show_security_question_2"] ? $security_question_2 : "",
						"security_answer_2" => $settings["show_security_answer_2"] ? $security_answer_2 : "",
						"security_question_3" => $settings["show_security_question_3"] ? $security_question_3 : "",
						"security_answer_3" => $settings["show_security_answer_3"] ? $security_answer_3 : "",
					);
					
					$CommonModuleTableExtraAttributesUtil->prepareFieldsWithNewData($settings, $data, array(), $_POST);
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $data, $error_message) && $CommonModuleTableExtraAttributesUtil->areFileFieldsValid($EVC, $settings, $error_message)) {
						//check if users count exceeded the licence limit
						if (\UserUtil::usersCountExceedLimit($EVC))
							$error_message = translateProjectText($EVC, "Users count exceeded the licence limit. Please renew your licence with more users...");
						else {
							$data["object_users"] = $settings["object_to_objects"];
							$data["user_environments"] = $settings["user_environments"];
							$data["do_not_encrypt_password"] = $settings["do_not_encrypt_password"];
							
							$status = \UserUtil::insertUser($EVC, $data, $brokers);
							$user_id = $status;
							
							if ($status && !\UserUtil::insertUserUserType($brokers, array("user_id" => $user_id, "user_type_id" => $user_type_id)))
								$status = false;
							
							if ($status) {	
								//save user attachments
								$status = \AttachmentUtil::saveObjectAttachments($EVC, \ObjectUtil::USER_OBJECT_TYPE_ID, $user_id, \UserUtil::USER_ATTACHMENTS_GROUP_ID, $error_message);
								
								if ($status) {
									//save user extra
									$new_extra_data = $data;
									$new_extra_data["user_id"] = $user_id;
									$status = $CommonModuleTableExtraAttributesUtil->insertOrUpdateTableExtra($new_extra_data);
									$CommonModuleTableExtraAttributesUtil->reloadSavedTableExtra($settings, array("user_id" => $user_id), $aux = null, $data, $_POST);
									
									if ($status) {
										//Add Join Point creating a new action of some kind
										$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull user register action", array(
											"EVC" => $EVC,
											"settings" => &$settings,
											"user_id" => $user_id,
											"user_data" => &$data,
											"error_message" => &$error_message,
										));
									}
								}
							}
							else if ($user_id)
								\UserUtil::deleteUser($EVC, $user_id, $brokers);
						}
					}
				}
				else if ($user_exists) {
					//Add Join Point creating a new action of some kind
					$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On repeated user register action", array(
						"EVC" => $EVC,
						"settings" => &$settings,
						"user_id" => $user_exists,
						"user_data" => &$users[0],
						"error_message" => &$error_message,
					));
				}
			}
		}
		
		$form_data = array(
			"username" => $username,
			"password" => $password,
			"name" => $name,
			"email" => $email,
			"security_question_1" => $security_question_1,
			"security_answer_1" => $security_answer_1,
			"security_question_2" => $security_question_2,
			"security_answer_2" => $security_answer_2,
			"security_question_3" => $security_question_3,
			"security_answer_3" => $security_answer_3,
		);
		
		$CommonModuleTableExtraAttributesUtil->prepareFieldsWithNewData($settings, $form_data, array(), $_POST);
		
		$form_data = $data ? array_merge($data, $form_data) : $form_data;//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/user/register.css';
		$settings["js_file"] = $project_common_url_prefix . 'module/user/register.js';
		$settings["class"] = "module_register";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		$settings["allow_insertion"] = true;
		$settings["on_insert_ok_message"] = array_key_exists("on_insert_ok_message", $settings) ? $settings["on_insert_ok_message"] : "User registered successfully!";
		$settings["on_insert_ok_action"] = array_key_exists("on_insert_ok_action", $settings) ? $settings["on_insert_ok_action"] : (
			$settings["redirect_page_url"] ? "alert_message_and_redirect" : "show_message_and_stop"
		);
		$settings["on_insert_ok_redirect_url"] = $settings["redirect_page_url"] . (strpos($settings["redirect_page_url"], "?") !== false ? "&" : "?") . "user_id=$user_id";
		$settings["on_insert_error_message"] = array_key_exists("on_insert_error_message", $settings) ? $settings["on_insert_error_message"] : "User NOT registered! Please try again...";
		$settings["on_insert_error_action"] = array_key_exists("on_insert_error_action", $settings) ? $settings["on_insert_error_action"] : "show_message";
		
		if (!$settings["buttons"] || !array_key_exists("insert", $settings["buttons"]))
			$settings["buttons"]["insert"] = array(
				"field" => array(
					"class" => "submit_button",
					"input" => array(
						"type" => "submit",
						"name" => "save",
						"value" => "Register",
					)
				)
			);
		else
			$settings["buttons"]["insert"]["field"]["input"]["name"] = "save"; //force button name to be login
		
		$CommonModuleTableExtraAttributesUtil->prepareFileFieldsSettings($EVC, $settings);
		
		if ($settings["show_password"]) {
			$settings["fields"]["password"]["field"]["input"]["type"] = "password";
			
			if ($settings["fields"]["password"]["field"]["input"]["password_generator"])
				\CMSModule\user\UserModuleUI::addPasswordGeneratorToPasswordField($settings);
		}
		
		if ($settings["show_security_question_1"])
			$settings["fields"]["security_question_1"]["field"]["input"]["type"] = "select";
		
		if ($settings["show_security_question_2"])
			$settings["fields"]["security_question_2"]["field"]["input"]["type"] = "select";
		
		if ($settings["show_security_question_3"])
			$settings["fields"]["security_question_3"]["field"]["input"]["type"] = "select";
		
		if ($settings["show_user_attachments"]) {
			include_once $EVC->getModulePath("attachment/AttachmentUI", $common_project_name);
			
			$attachments_settings = array(
				"style_type" => $settings["style_type"],
				"class" => $settings["fields"]["user_attachments"]["field"]["class"],
				"title" => $settings["fields"]["user_attachments"]["field"]["label"]["value"],
			);
			
			unset($settings["fields"]["user_attachments"]["field"]);
			
			$settings["fields"]["user_attachments"]["container"] = array(
				"previous_html" => \AttachmentUI::getEditObjectAttachmentsHtml($EVC, $attachments_settings, \ObjectUtil::USER_OBJECT_TYPE_ID, $user_id, \UserUtil::USER_ATTACHMENTS_GROUP_ID),
			);
		}
		
		//Add join point creating new fields in the user form.
		$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("New User bottom fields", array(
			"EVC" => &$EVC,
			"settings" => &$settings,
			"object_type_id" => \ObjectUtil::USER_OBJECT_TYPE_ID,
			"object_id" => &$user_id,
			"group_id" => \UserUtil::USER_ATTACHMENTS_GROUP_ID,
		));
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/register", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
