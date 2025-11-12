<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

namespace CMSModule\user\register;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = $user_id = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("user/UserModuleUI", $common_project_name);
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleTableExtraAttributesUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$CommonModuleTableExtraAttributesUtil = new \CommonModuleTableExtraAttributesUtil($this, isset($GLOBALS["default_db_driver"]) ? $GLOBALS["default_db_driver"] : null, $settings, "user");
		
		//Preparing Action
		if (!empty($_POST)) {
			$user_type_id = isset($settings["user_type_id"]) ? $settings["user_type_id"] : null;
			$username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
			$password = isset($_POST["password"]) ? trim($_POST["password"]) : "";
			$name = isset($_POST["name"]) ? $_POST["name"] : null;
			$email = isset($_POST["email"]) ? $_POST["email"] : null;
			$security_question_1 = isset($_POST["security_question_1"]) ? $_POST["security_question_1"] : null;
			$security_answer_1 = isset($_POST["security_answer_1"]) ? $_POST["security_answer_1"] : null;
			$security_question_2 = isset($_POST["security_question_2"]) ? $_POST["security_question_2"] : null;
			$security_answer_2 = isset($_POST["security_answer_2"]) ? $_POST["security_answer_2"] : null;
			$security_question_3 = isset($_POST["security_question_3"]) ? $_POST["security_question_3"] : null;
			$security_answer_3 = isset($_POST["security_answer_3"]) ? $_POST["security_answer_3"] : null;
			
			$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("username" => $username, "password" => $password, "name" => $name, "email" => $email, "security_question_1" => $security_question_1, "security_answer_1" => $security_answer_1, "security_question_2" => $security_question_2, "security_answer_2" => $security_answer_2, "security_question_3" => $security_question_3, "security_answer_3" => $security_answer_3));
			
			if (!$empty_field_name)
				$empty_field_name = $CommonModuleTableExtraAttributesUtil->checkIfEmptyFields($settings, $_POST);
			
			if ($empty_field_name)
				$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
			else {
				if (!empty($settings["show_username"]) && $username) {
					$users = \UserUtil::getUsersByConditionsAccordingWithUserEnvironmentsSettings($brokers, $settings["user_environments"], array("username" => $username), null, null, true);	
					$user_exists = isset($users[0]["user_id"]) ? $users[0]["user_id"] : null;
					
					if ($user_exists) {
						$username_label = \CommonModuleUI::getFieldLabel($settings, "username");
						$error_message = translateProjectText($EVC, "This #username# already exists! Please choose another #username#...");
						$error_message = str_replace("#username#", $username_label, $error_message);
					}
				}
				
				if (empty($error_message)) {
					$data = array(
						"username" => !empty($settings["show_username"]) ? $username : "",
						"password" => !empty($settings["show_password"]) ? $password : "",
						"name" => !empty($settings["show_name"]) ? $name : "",
						"email" => !empty($settings["show_email"]) ? $email : "",
						"security_question_1" => !empty($settings["show_security_question_1"]) ? $security_question_1 : "",
						"security_answer_1" => !empty($settings["show_security_answer_1"]) ? $security_answer_1 : "",
						"security_question_2" => !empty($settings["show_security_question_2"]) ? $security_question_2 : "",
						"security_answer_2" => !empty($settings["show_security_answer_2"]) ? $security_answer_2 : "",
						"security_question_3" => !empty($settings["show_security_question_3"]) ? $security_question_3 : "",
						"security_answer_3" => !empty($settings["show_security_answer_3"]) ? $security_answer_3 : "",
					);
					
					$CommonModuleTableExtraAttributesUtil->prepareFieldsWithNewData($settings, $data, array(), $_POST);
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $data, $error_message) && $CommonModuleTableExtraAttributesUtil->areFileFieldsValid($EVC, $settings, $error_message)) {
						//check if users count exceeded the licence limit
						if (\UserUtil::usersCountExceedLimit($EVC))
							$error_message = translateProjectText($EVC, "Users count exceeded the licence limit. Please renew your licence with more users...");
						else {
							$data["object_users"] = isset($settings["object_to_objects"]) ? $settings["object_to_objects"] : null;
							$data["user_environments"] = isset($settings["user_environments"]) ? $settings["user_environments"] : null;
							$data["do_not_encrypt_password"] = isset($settings["do_not_encrypt_password"]) ? $settings["do_not_encrypt_password"] : null;
							
							$status = \UserUtil::insertUser($EVC, $data, $brokers);
							$user_id = $status;
							
							if ($status) {
								if (!is_array($user_type_id))
									$user_type_id = explode(",", $user_type_id);
								
								for ($i = 0, $t = count($user_type_id); $i < $t; $i++) 
									if (!\UserUtil::insertUserUserType($brokers, array("user_id" => $user_id, "user_type_id" => $user_type_id[$i])))
										$status = false;
							}
							
							if ($status) {	
								//save user attachments
								$status = \AttachmentUtil::saveObjectAttachments($EVC, \ObjectUtil::USER_OBJECT_TYPE_ID, $user_id, \UserUtil::USER_ATTACHMENTS_GROUP_ID, $error_message);
								
								if ($status) {
									//save user extra
									$new_extra_data = $data;
									$new_extra_data["user_id"] = $user_id;
									$status = $CommonModuleTableExtraAttributesUtil->insertOrUpdateTableExtra($new_extra_data);
									$aux = null;
									$CommonModuleTableExtraAttributesUtil->reloadSavedTableExtra($settings, array("user_id" => $user_id), $aux, $data, $_POST);
									
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
				else if (!empty($user_exists)) {
					$user_data = isset($users[0]) ? $users[0] : null;
					
					//Add Join Point creating a new action of some kind
					$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On repeated user register action", array(
						"EVC" => $EVC,
						"settings" => &$settings,
						"user_id" => $user_exists,
						"user_data" => &$user_data,
						"error_message" => &$error_message,
					));
					
					if (isset($users[0]) && $user_data != $users[0])
						$users[0] = $user_data;
				}
			}
		}
		
		$form_data = array(
			"username" => isset($username) ? $username : null,
			"password" => isset($password) ? $password : null,
			"name" => isset($name) ? $name : null,
			"email" => isset($email) ? $email : null,
			"security_question_1" => isset($security_question_1) ? $security_question_1 : null,
			"security_answer_1" => isset($security_answer_1) ? $security_answer_1 : null,
			"security_question_2" => isset($security_question_2) ? $security_question_2 : null,
			"security_answer_2" => isset($security_answer_2) ? $security_answer_2 : null,
			"security_question_3" => isset($security_question_3) ? $security_question_3 : null,
			"security_answer_3" => isset($security_answer_3) ? $security_answer_3 : null,
		);
		
		$CommonModuleTableExtraAttributesUtil->prepareFieldsWithNewData($settings, $form_data, array(), isset($_POST) ? $_POST : null);
		
		$form_data = !empty($data) ? array_merge($data, $form_data) : $form_data;//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/user/register.css';
		$settings["js_file"] = $project_common_url_prefix . 'module/user/register.js';
		$settings["class"] = "module_register";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		$settings["allow_insertion"] = true;
		$settings["redirect_page_url"] = isset($settings["redirect_page_url"]) ? $settings["redirect_page_url"] : null;
		$settings["on_insert_ok_message"] = array_key_exists("on_insert_ok_message", $settings) ? $settings["on_insert_ok_message"] : "User registered successfully!";
		$settings["on_insert_ok_action"] = array_key_exists("on_insert_ok_action", $settings) ? $settings["on_insert_ok_action"] : (
			$settings["redirect_page_url"] ? "alert_message_and_redirect" : "show_message_and_stop"
		);
		$settings["on_insert_ok_redirect_url"] = $settings["redirect_page_url"] . (strpos($settings["redirect_page_url"], "?") !== false ? "&" : "?") . "user_id=$user_id";
		$settings["on_insert_error_message"] = array_key_exists("on_insert_error_message", $settings) ? $settings["on_insert_error_message"] : "User NOT registered! Please try again...";
		$settings["on_insert_error_action"] = array_key_exists("on_insert_error_action", $settings) ? $settings["on_insert_error_action"] : "show_message";
		
		if (empty($settings["buttons"]) || !array_key_exists("insert", $settings["buttons"]))
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
		
		if (!empty($settings["show_password"])) {
			$settings["fields"]["password"]["field"]["input"]["type"] = "password";
			
			if (!empty($settings["fields"]["password"]["field"]["input"]["password_generator"]))
				\CMSModule\user\UserModuleUI::addPasswordGeneratorToPasswordField($settings);
		}
		
		if (!empty($settings["show_security_question_1"]))
			$settings["fields"]["security_question_1"]["field"]["input"]["type"] = "select";
		
		if (!empty($settings["show_security_question_2"]))
			$settings["fields"]["security_question_2"]["field"]["input"]["type"] = "select";
		
		if (!empty($settings["show_security_question_3"]))
			$settings["fields"]["security_question_3"]["field"]["input"]["type"] = "select";
		
		if (!empty($settings["show_user_attachments"])) {
			include_once $EVC->getModulePath("attachment/AttachmentUI", $common_project_name);
			
			$attachments_settings = array(
				"style_type" => isset($settings["style_type"]) ? $settings["style_type"] : null,
				"class" => isset($settings["fields"]["user_attachments"]["field"]["class"]) ? $settings["fields"]["user_attachments"]["field"]["class"] : null,
				"title" => isset($settings["fields"]["user_attachments"]["field"]["label"]["value"]) ? $settings["fields"]["user_attachments"]["field"]["label"]["value"] : null,
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
