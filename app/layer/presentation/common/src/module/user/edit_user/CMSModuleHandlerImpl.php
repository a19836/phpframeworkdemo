<?php
namespace CMSModule\user\edit_user;

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
		
		//Getting User Details
		$user_id = $_GET["user_id"];
		$data = $user_id ? \UserUtil::getUsersByConditions($brokers, array("user_id" => $user_id), null, null, true) : null;
		$data = $data[0];
		
		if ($data["user_id"]) {
			$data["user_type_id"] = array();
			$user_user_type_data = \UserUtil::getUserUserTypesByConditions($brokers, array("user_id" => $data["user_id"]), null);
			if ($user_user_type_data)
				foreach ($user_user_type_data as $item)
					$data["user_type_id"][] = $item["user_type_id"];
			
			//Getting User Extra Details
			$data_extra = $CommonModuleTableExtraAttributesUtil->getTableExtra(array("user_id" => $data["user_id"]), true);
			$data = $data_extra ? array_merge($data, $data_extra) : $data;
		}
		
		//Add Join Point
		$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Preparing user data", array(
			"EVC" => $EVC,
			"settings" => &$settings,
			"user_data" => &$data,
		), "Use this join point to change the loaded user data.");
		
		$user_id = $data["user_id"];
		
		//Preparing Action
		if ($_POST) {
			if ($_POST["delete"] && $settings["allow_deletion"]) {
				$status = !$data || \UserUtil::deleteUser($EVC, $data["user_id"], $brokers);
				
				if ($status && $data["user_id"])
					$status = $CommonModuleTableExtraAttributesUtil->deleteTableExtra(array("user_id" => $data["user_id"]));
				
				if ($status) {	
					//Add Join Point creating a new action of some kind
					$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull user deletion", array(
						"EVC" => &$EVC,
						"user_id" => $data["user_id"],
						"user_data" => &$data,
						"error_message" => &$error_message,
					));
				}
			}
			else if ($_POST["save"]) {
				$user_type_id = $_POST["user_type_id"];
				$username = strtolower(trim($_POST["username"]));
				$password = trim($_POST["password"]);
				$name = $_POST["name"];
				$email = strtolower($_POST["email"]);
				$security_question_1 = $_POST["security_question_1"];
				$security_answer_1 = $_POST["security_answer_1"];
				$security_question_2 = $_POST["security_question_2"];
				$security_answer_2 = $_POST["security_answer_2"];
				$security_question_3 = $_POST["security_question_3"];
				$security_answer_3 = $_POST["security_answer_3"];
				$active = $_POST["active"];
				
				if ($settings["show_user_type_id"] && is_array($user_type_id)) {
					foreach ($user_type_id as $utid)
						if (\CommonModuleUI::checkIfEmptyField($settings, "user_type_id", $utid)) {
							$empty_field_name = "user_type_id";
							break;
						}
				}
				
				if (!$empty_field_name)
					$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("username" => $username, /*"password" => $password, */"name" => $name, "email" => $email, "security_question_1" => $security_question_1, "security_answer_1" => $security_answer_1, "security_question_2" => $security_question_2, "security_answer_2" => $security_answer_2, "security_question_3" => $security_question_3, "security_answer_3" => $security_answer_3, "active" => $active));
				
				if (!$empty_field_name)
					$empty_field_name = $CommonModuleTableExtraAttributesUtil->checkIfEmptyFields($settings, $_POST);
				
				if ($empty_field_name)
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				else {
					$new_data = $data;
				
					if ($settings["show_username"]) {
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
						$new_data["active"] = $settings["show_active"] ? $active : $new_data["active"];
						
						if ($settings["show_user_type_id"])
							$new_data["user_type_id"] = is_array($user_type_id) ? $user_type_id : (strlen($user_type_id) > 0 ? array($user_type_id) : array());
						
						$CommonModuleTableExtraAttributesUtil->prepareFieldsWithNewData($settings, $new_data, $data, $_POST);
						
						\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
						
						if (strlen($new_data["active"]) == 0) //if active is allow_null and is a checkbox the POST will not contain the active field and bc is allow null, the prepareFieldsWithDefaultValue won't set the default value. So we need to do it manually.
							$new_data["active"] = is_numeric($settings["active_default_value"]) ? $settings["active_default_value"] : 0;
						
						$active = $new_data["active"]; //set $active with default values with apply
						
						if (empty($new_data["user_type_id"]))
							$new_data["user_type_id"] = array(\UserUtil::PUBLIC_USER_TYPE_ID);
						
						if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message) && $CommonModuleTableExtraAttributesUtil->areFileFieldsValid($EVC, $settings, $error_message)) {
							$new_data["object_users"] = isset($new_data["object_users"]) ? $new_data["object_users"] : $settings["object_to_objects"];
							$new_data["user_environments"] = isset($new_data["user_environments"]) ? $new_data["user_environments"] : $settings["user_environments"];
							$new_data["do_not_encrypt_password"] = $settings["do_not_encrypt_password"];
							
							if ($settings["allow_insertion"] && empty($data["user_id"])) {
								$status = \UserUtil::insertUser($EVC, $new_data, $brokers);
								$inserted_user_id = $status;
								
								//add user types
								if ($status && $new_data["user_type_id"]) {
									foreach ($new_data["user_type_id"] as $utid)
										if (!\UserUtil::insertUserUserType($brokers, array("user_id" => $inserted_user_id, "user_type_id" => $utid)))
											$status = false;
								}
								
								if (strpos($settings["on_insert_ok_action"], "_redirect") !== false)
									$settings["on_insert_ok_redirect_url"] .= (strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "user_id=$status";
							}
							else if ($settings["allow_update"] && $data["user_id"] && \UserUtil::updateUser($EVC, $new_data, $brokers)) {
								$status = true;
								
								if ($settings["show_password"] && strlen($password)) {
									//only update password if exists any change
									if (!$new_data["do_not_encrypt_password"] || $data["password"] != $new_data["password"])
										$status = \UserUtil::updateUserPassword($brokers, $new_data);
								}
								
								//add user types
								if ($status && $data["user_type_id"] != $new_data["user_type_id"]) {
									//delete old user type ids
									if ($data["user_type_id"]) {
										$user_type_ids_to_delete = array_diff($data["user_type_id"], $new_data["user_type_id"]);
										
										if ($user_type_ids_to_delete)
											foreach ($user_type_ids_to_delete as $utid)
												if (!\UserUtil::deleteUserUserType($brokers, $data["user_id"], $utid))
													$status = false;
									}
									
									//add new user type ids
									if ($status && $new_data["user_type_id"]) {	
										$user_type_ids_to_add = $data["user_type_id"] ? array_diff($new_data["user_type_id"], $data["user_type_id"]) : $new_data["user_type_id"];
										
										if ($user_type_ids_to_add)
											foreach ($user_type_ids_to_add as $utid)
												if (!\UserUtil::insertUserUserType($brokers, array("user_id" => $data["user_id"], "user_type_id" => $utid)))
													$status = false;
									}
								}
							}
						}
						
						if ($status) {
							$user_id = $settings["allow_insertion"] && empty($data["user_id"]) ? $status : $data["user_id"];
							
							if ($settings["show_username"] && $username && strtolower($data["username"]) != strtolower($username))
								\UserUtil::changeUserSessionUsernameByUsername($brokers, $settings, $data["username"], $username);
							
							//save user active status
							if ($settings["show_active"])
								$status = \UserUtil::updateUserActiveStatus($brokers, array("user_id" => $user_id, "active" => $active));
							
							//save user attachments
							$status = $status && \AttachmentUtil::saveObjectAttachments($EVC, \ObjectUtil::USER_OBJECT_TYPE_ID, $user_id, \UserUtil::USER_ATTACHMENTS_GROUP_ID, $error_message);
							
							if ($status) {
								//save user extra
								$new_extra_data = $new_data;
								$new_extra_data["user_id"] = $user_id;
								$status = $CommonModuleTableExtraAttributesUtil->insertOrUpdateTableExtra($new_extra_data);
								$CommonModuleTableExtraAttributesUtil->reloadSavedTableExtra($settings, array("user_id" => $user_id), $data, $new_data, $_POST);
								
								if ($status) {
									//Add Join Point creating a new action of some kind
									$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull user saving action", array(
										"EVC" => $EVC,
										"settings" => &$settings,
										"user_id" => $user_id,
										"user_data" => &$new_data,
										"error_message" => &$error_message,
									));
								}
							}
						}
						else if ($settings["allow_insertion"] && empty($data["user_id"]) && $inserted_user_id)
							\UserUtil::deleteUser($EVC, $inserted_user_id, $brokers);
					}
				}
			}
		}
		
		if ($_POST["save"]) {
			$form_data = array(
				"user_id" => $settings["show_user_id"] ? $user_id : $data["user_id"],
				"user_type_id" => $settings["show_user_type_id"] ? $user_type_id : $data["user_type_id"],
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
				"active" => $settings["show_active"] ? $active : $data["active"],
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
		$settings["css_file"] = $project_common_url_prefix . 'module/user/edit_user.css';
		$settings["js_file"] = $project_common_url_prefix . 'module/user/edit_user.js';
		$settings["class"] = "module_edit_user";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		$is_insertion = $settings["allow_insertion"] && !$data;
		$is_editable = ($settings["allow_update"] && $data) || $is_insertion;
		
		$CommonModuleTableExtraAttributesUtil->prepareFileFieldsSettings($EVC, $settings);
		
		if ($settings["show_user_id"])
			$settings["fields"]["user_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		if ($settings["show_user_type_id"]) {
			$user_types = \UserUtil::getAllUserTypes($brokers);
			$user_type_options = array();
			
			if ($settings["fields"]["user_type_id"]["field"]["input"]["allow_null"])
				$user_type_options[] = array("label" => "");
			
			$available_user_types = array();
				
			if ($user_types) {
				$t = count($user_types);
				for ($i = 0; $i < $t; $i++) {
					if ($user_types[$i]["user_type_id"] != \UserUtil::PUBLIC_USER_TYPE_ID) {
						if ($is_editable) 
							$user_type_options[] = array("value" => $user_types[$i]["user_type_id"], "label" => $user_types[$i]["name"]);
						else
							$available_user_types[ $user_types[$i]["user_type_id"] ] = $user_types[$i]["name"];
					}
				}
			}
			
			$settings["fields"]["user_type_id"]["field"]["input"]["name"] = "user_type_id[]";
			$settings["fields"]["user_type_id"]["field"]["input"]["type"] = $is_editable ? "select" : "label";
			$settings["fields"]["user_type_id"]["field"]["input"]["options"] = $user_type_options;
			$settings["fields"]["user_type_id"]["field"]["input"]["available_values"] = $available_user_types;
		}
		
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
			"object_id" => &$user_id,
			"group_id" => \UserUtil::USER_ATTACHMENTS_GROUP_ID,
		));
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/edit_user", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
