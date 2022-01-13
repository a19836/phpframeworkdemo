<?php
namespace CMSModule\user\edit_profile;

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
		
		$settings["allow_view"] = $settings["allow_update"] = true;
		
		//Getting User Details
		$session_id = $_COOKIE[ \UserUtil::getConstantVariable("USER_SESSION_ID_VARIABLE_NAME") ];
		$user_session = \UserUtil::isLoggedIn($brokers, $session_id, \UserUtil::getConstantVariable("DEFAULT_USER_SESSION_EXPIRATION_TTL"), true);
		if (empty($user_session["user_id"])) {
			$settings["on_undefined_object_message"] = 'User session is expired. Please login...';
		}
		else {
			$data = \UserUtil::getUsersByConditions($brokers, array("user_id" => $user_session["user_id"]), null, null, true);
			$data = $data[0];
			if (empty($data["user_id"])) {
				$settings["on_undefined_object_message"] = 'User data is invalid. Please logout and then login...';
			}
			else {
				//Getting User Extra Details
				$data_extra = $CommonModuleTableExtraAttributesUtil->getTableExtra(array("user_id" => $data["user_id"]), true);
				$data = $data_extra ? array_merge($data, $data_extra) : $data;
				
				//Add Join Point
				$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Preparing user data", array(
					"EVC" => $EVC,
					"settings" => &$settings,
					"user_data" => &$data,
				), "Use this join point to change the loaded user data.");
			}
		}
		
		//Preparing Action
		if ($_POST) {
			$username = strtolower(trim($_POST["username"]));
			$current_password = trim($_POST["current_password"]);
			$password = trim($_POST["password"]);
			$name = $_POST["name"];
			$email = strtolower($_POST["email"]);
			$security_question_1 = $_POST["security_question_1"];
			$security_answer_1 = $_POST["security_answer_1"];
			$security_question_2 = $_POST["security_question_2"];
			$security_answer_2 = $_POST["security_answer_2"];
			$security_question_3 = $_POST["security_question_3"];
			$security_answer_3 = $_POST["security_answer_3"];
			
			$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("username" => $username, "current_password" => $current_password, /*"password" => $password, */"name" => $name, "email" => $email, "security_question_1" => $security_question_1, "security_answer_1" => $security_answer_1, "security_question_2" => $security_question_2, "security_answer_2" => $security_answer_2, "security_question_3" => $security_question_3, "security_answer_3" => $security_answer_3));
			
			if (!$empty_field_name)
				$empty_field_name = $CommonModuleTableExtraAttributesUtil->checkIfEmptyFields($settings, $_POST);
			
			if ($empty_field_name)
				$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
			else if ($data["user_id"]) {
				$new_data = $data;
				
				if ($settings["show_current_password"]) {
					$users = \UserUtil::getUsersByConditions($brokers, array("user_id" => $data["user_id"]), null, null, true);
					
					if (!$users[0]["user_id"] || !\UserUtil::validatePassword($users[0]["password"], $current_password, $settings["do_not_encrypt_password"])) 
						$error_message = "Invalid user credentials. Please insert the correct credentials.";
				}
				
				if (!$error_message && $settings["show_username"]) {
					if (empty($username)) {
						$new_data["username"] = $username;
					}
					else if (strtolower($data["username"]) != strtolower($username)) {
						$users = \UserUtil::getUsersByConditionsAccordingWithUserEnvironmentsSettings($brokers, $settings["user_environments"], array("username" => $username), null, null, true);
						$user_exists = $users[0]["user_id"];
				
						if ($user_exists) {
							$username_label = \CommonModuleUI::getFieldLabel($settings, "username");
							$error_message = translateProjectText($EVC, "This #username# already exists! Please choose another #username#...");
							$error_message = str_replace("#username#", $username_label, $error_message);
						}
						else {
							$new_data["username"] = $username;
						}
					}
				}
				
				if (!$error_message) {
					$new_data["password"] = $settings["show_password"] ? (strlen($password) ? $password : $new_data["password"]) : $new_data["password"];
					$new_data["name"] = $settings["show_name"] ? $name : $new_data["name"];
					$new_data["email"] = $settings["show_email"] ? $email : $new_data["email"];
					$new_data["security_question_1"] = $settings["show_security_question_1"] ? $security_question_1 : $new_data["security_question_1"];
					$new_data["security_answer_1"] = $settings["show_security_answer_1"] ? $security_answer_1 : $new_data["security_answer_1"];
					$new_data["security_question_2"] = $settings["show_security_question_2"] ? $security_question_2 : $new_data["security_question_2"];
					$new_data["security_answer_2"] = $settings["show_security_answer_2"] ? $security_answer_2 : $new_data["security_answer_2"];
					$new_data["security_question_3"] = $settings["show_security_question_3"] ? $security_question_3 : $new_data["security_question_3"];
					$new_data["security_answer_3"] = $settings["show_security_answer_3"] ? $security_answer_3 : $new_data["security_answer_3"];
					
					$CommonModuleTableExtraAttributesUtil->prepareFieldsWithNewData($settings, $new_data, $data, $_POST);
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message) && $CommonModuleTableExtraAttributesUtil->areFileFieldsValid($EVC, $settings, $error_message)) {
						$new_data["object_users"] = isset($new_data["object_users"]) ? $new_data["object_users"] : $settings["object_to_objects"];
						$new_data["user_environments"] = isset($new_data["user_environments"]) ? $new_data["user_environments"] : $settings["user_environments"];
						
						$status = \UserUtil::updateUser($EVC, $new_data, $brokers);
						
						if ($status && $settings["show_password"] && strlen($password)) {
							$new_data["do_not_encrypt_password"] = $settings["do_not_encrypt_password"];
							
							//only update password if exists any change
							if (!$new_data["do_not_encrypt_password"] || $data["password"] != $new_data["password"])
								$status = \UserUtil::updateUserPassword($brokers, $new_data);
						}
						
						if ($status) {
							if ($settings["show_username"] && $username && strtolower($data["username"]) != strtolower($username)) {
								//Delete sessions from new_username. There should not exist any sessions, but just in case we remove them anyways...
								$old_user_sessions = \UserUtil::getUserSessionsByConditionsAccordingWithUserEnvironmentsSettings($brokers, $settings["user_environments"], array("username" => $username), null, null, true);
								if ($old_user_sessions)
									foreach ($old_user_sessions as $us)
										\UserUtil::deleteUserSession($brokers, $us["username"], $us["environment_id"]);
		
								\UserUtil::deleteUserSessionBySessionId($brokers, $session_id);
								
								$user_session["username"] = $username;
								\UserUtil::insertUserSession($brokers, $user_session);
							}
							
							//save user attachments
							$status = \AttachmentUtil::saveObjectAttachments($EVC, \ObjectUtil::USER_OBJECT_TYPE_ID, $data["user_id"], \UserUtil::USER_ATTACHMENTS_GROUP_ID, $error_message);
							
							if ($status) {
								//save user extra
								$status = $CommonModuleTableExtraAttributesUtil->insertOrUpdateTableExtra($new_data);
								$CommonModuleTableExtraAttributesUtil->reloadSavedTableExtra($settings, array("user_id" => $data["user_id"]), $data, $new_data, $_POST);
								
								if ($status) {
									//Add Join Point creating a new action of some kind
									$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull user profile saving action", array(
										"EVC" => $EVC,
										"settings" => &$settings,
										"user_id" => $data["user_id"],
										"user_data" => &$new_data,
										"error_message" => &$error_message,
									));
								}
							}
						}
					}
				}
			}
		}
		
		if ($_POST) {
			$form_data = array(
				"username" => $settings["show_username"] ? $username : $data["username"],
				"password" => $settings["show_password"] ? $password : $data["password"],
				"name" => $settings["show_name"] ? $name : $data["name"],
				"email" => $settings["show_email"] ? $email : $data["email"],
				"security_question_1" => $settings["show_security_question_1"] ? $security_question_1 : $data["security_question_1"],
				"security_answer_1" => $settings["show_security_answer_1"] ? $security_answer_1 : $data["security_answer_1"],
				"security_question_2" => $settings["show_security_question_2"] ? $security_question_2 : $data["security_question_2"],
				"security_answer_2" => $settings["show_security_answer_2"] ? $security_answer_2 : $data["security_answer_2"],
				"security_question_3" => $settings["show_security_question_3"] ? $security_question_3 : $data["security_question_3"],
				"security_answer_3" => $settings["show_security_answer_3"] ? $security_answer_3 : $data["security_answer_3"],
			);
			
			$CommonModuleTableExtraAttributesUtil->prepareFieldsWithNewData($settings, $form_data, $data, $_POST);
			
			$form_data = $new_data ? array_merge($new_data, $form_data) : ($settings["allow_view"] && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = $settings["allow_view"] && $data ? $data : array();
			$form_data["password"] = "";
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/user/edit_profile.css';
		$settings["js_file"] = $project_common_url_prefix . 'module/user/edit_profile.js';
		$settings["class"] = "module_edit_profile";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		$is_editable = $settings["allow_update"] && $data;
		
		$CommonModuleTableExtraAttributesUtil->prepareFileFieldsSettings($EVC, $settings);
		
		if ($settings["show_current_password"])
			$settings["fields"]["current_password"]["field"]["input"]["type"] = $is_editable ? "password" : "label";
		
		if ($settings["show_password"]) {
			$settings["fields"]["password"]["field"]["input"]["type"] = $is_editable ? "password" : "label";
			
			if ($is_editable && $settings["fields"]["password"]["field"]["input"]["password_generator"])
				\CMSModule\user\UserModuleUI::addPasswordGeneratorToPasswordField($settings);
		}
		
		if ($settings["show_security_question_1"]) 
			$settings["fields"]["security_question_1"]["field"]["input"]["type"] = $is_editable ? "select" : "label";
		
		if ($settings["show_security_question_2"]) 
			$settings["fields"]["security_question_2"]["field"]["input"]["type"] = $is_editable ? "select" : "label";
		
		if ($settings["show_security_question_3"]) 
			$settings["fields"]["security_question_3"]["field"]["input"]["type"] = $is_editable ? "select" : "label";
		
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
			"object_id" => &$data["user_id"],
			"group_id" => \UserUtil::USER_ATTACHMENTS_GROUP_ID,
		));
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/edit_profile", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
