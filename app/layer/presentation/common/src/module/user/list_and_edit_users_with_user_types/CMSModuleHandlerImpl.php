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

namespace CMSModule\user\list_and_edit_users_with_user_types;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	private $CommonModuleTableExtraAttributesUtil;
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleTableExtraAttributesUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$this->CommonModuleTableExtraAttributesUtil = new \CommonModuleTableExtraAttributesUtil($this, isset($GLOBALS["default_db_driver"]) ? $GLOBALS["default_db_driver"] : null, $settings, "user");
		$continue = true;
		
		//Executing buttons' actions
		if (!empty($_POST) && !empty($_POST["users"])) {
			$is_insert = !empty($settings["allow_insertion"]) && !empty($_POST["save"]);
			$is_update = !empty($settings["allow_update"]) && !empty($_POST["save"]);
			$is_delete = !empty($settings["allow_deletion"]) && !empty($_POST["delete"]);
				
			if ($is_insert || $is_update || $is_delete) {
				$to_insert = array();
				$to_update = array();
				$to_delete = array();
				$files_to_insert = array();
				$files_to_update = array();
				$user_ids_to_load = array();
				
				$files = $this->CommonModuleTableExtraAttributesUtil->convertMultipleFilesToFormattedArray(isset($_FILES["users"]) ? $_FILES["users"] : null);
				
				foreach ($_POST["users"] as $idx => $user) 
					if (!empty($user["selected_item"])) {
						if (!empty($user["user_id"])) {
							if (!empty($settings["allow_deletion"]) && !empty($_POST["delete"]))
								$to_delete[] = $user;
							else if (!empty($settings["allow_update"]) && !empty($_POST["save"])) {
								$to_update[] = $user;
								$files_to_update[] = $files[$idx];
								$user_ids_to_load[] = $user["user_id"];
							}
						}
						else if (!empty($settings["allow_insertion"]) && !empty($_POST["save"])) {
							$to_insert[] = $user;
							$files_to_insert[] = $files[$idx];
							$user_ids_to_load[] = isset($user["user_id"]) ? $user["user_id"] : null;
						}
					}
					
				if ($to_insert || $to_update || $to_delete) {
					$status = true;
					$error_message = null;
					$old_users = array();
					
					if ($user_ids_to_load) {
						$loaded_users = \UserUtil::getUsersWithUserTypesByConditions($brokers, array("user_id" => array(
							"operator" => "in",
							"value" => $user_ids_to_load,
						)), null);
						
						if ($loaded_users)
							foreach ($loaded_users as $loaded_user) {
								if (!empty($loaded_user["user_type_ids"]))
									$loaded_user["user_type_ids"] = explode(",", $loaded_user["user_type_ids"]);
								
								$loaded_user_id = isset($loaded_user["user_id"]) ? $loaded_user["user_id"] : null;
								$old_users[$loaded_user_id] = $loaded_user;
							}
					}
					
					foreach ($to_delete as $user) 
						if (!$this->deleteUser($EVC, $settings, $brokers, $user))
							$status = false;
					
					foreach ($to_update as $idx => $user) {
						$user_id = isset($user["user_id"]) ? $user["user_id"] : null;
						$old_user = isset($old_users[$user_id]) ? $old_users[$user_id] : null;
						
						if (!$this->editUser($EVC, $settings, $brokers, $old_user, $user, $files_to_update[$idx], $error_message))
							$status = false;
					}
					
					foreach ($to_insert as $idx => $user) {
						$user_id = isset($user["user_id"]) ? $user["user_id"] : null;
						$old_user = isset($old_users[$user_id]) ? $old_users[$user_id] : null;
						
						if (!$this->editUser($EVC, $settings, $brokers, $old_user, $user, $files_to_insert[$idx], $error_message))
							$status = false;
					}
					
					if ($status)
						//Add Join Point creating a new action of some kind
						$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull user action", array(
							"EVC" => &$EVC,
							"POST" => isset($_POST) ? $_POST : null,
							"users_to_delete" => $to_delete,
							"users_to_update" => $to_update,
							"users_to_insert" => $to_insert,
							"error_message" => &$error_message,
						));
					
					if (!empty($error_message))
						$msg = \CommonModuleUI::getModuleMessagesHtml($EVC, null, $error_message);
					else {
						if ($to_insert && !$to_update)
							$is_update = false;
						
						$action_type = $is_delete ? "delete" : ($is_update ? "update" : "insert");
						$action = $is_delete ? "delete" : "saved";
						$settings["on_${action_type}_ok_message"] = "Users $action successfully.";
						$settings["on_${action_type}_error_message"] = "Error: Users not $action successfully!";
						
						$msg = \CommonModuleUI::executeStatusAction($EVC, $action_type, $status, $settings, $continue);
					}
				}
				else
					$msg = \CommonModuleUI::getModuleMessagesHtml($EVC, null, "Error: You must select at least one user!");
			}
		}
		
		//prepare settings
		$settings["css_file"] = $project_common_url_prefix . 'module/user/list_and_edit_users_with_user_types.css';
		$settings["class"] = "module_list_and_edit_users_with_user_types";
		$settings["show_edit_button"] = $settings["edit_page_url"] = $settings["show_delete_button"] = null;
		$settings["previous_html"] = isset($msg) ? $msg : null;
		
		$html = "";
		
		if (!$continue) 
			$settings["fields"] = null;
		else {
			//Getting data
			$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
			
			$settings["current_page"] = isset($_GET["current_page"]) && is_numeric($_GET["current_page"]) ? $_GET["current_page"] : null;
			$settings["rows_per_page"] = 50;
			
			$options = array(
				"start" => \PaginationHandler::getStartValue($settings["current_page"], $settings["rows_per_page"]), 
				"limit" => $settings["rows_per_page"], 
				"sort" => null
			);
			
			$query_type = isset($settings["query_type"]) ? $settings["query_type"] : null;
			$object_type_id = isset($settings["object_type_id"]) ? $settings["object_type_id"] : null;
			$object_id = isset($settings["object_id"]) ? $settings["object_id"] : null;
			$group = isset($settings["group"]) ? $settings["group"] : null;
			
			switch ($query_type) {
				case "user_by_user_type": 
					if (!empty($settings["user_type_id"])) {
						$settings["total"] = \UserUtil::countUsersByUserTypesAndConditions($brokers, array($settings["user_type_id"]), $conditions, null);
						$settings["data"] = \UserUtil::getUsersByUserTypesAndConditions($brokers, array($settings["user_type_id"]), $conditions, null, $options);
					}
					break;
				case "parent": 
					$settings["total"] = \UserUtil::countUsersByObjectAndConditions($brokers, $object_type_id, $object_id, $conditions, null);
					$settings["data"] = \UserUtil::getUsersByObjectAndConditions($brokers, $object_type_id, $object_id, $conditions, null, $options);
					break;
				case "parent_group": 
					$settings["total"] = \UserUtil::countUsersByObjectGroupAndConditions($brokers, $object_type_id, $object_id, $group, $conditions, null);
					$settings["data"] = \UserUtil::getUsersByObjectGroupAndConditions($brokers, $object_type_id, $object_id, $group, $conditions, null, $options);
					break;
				case "parent_and_user_type": 
					if (!empty($settings["user_type_id"])) {
						$settings["total"] = \UserUtil::countUsersByObjectAndUserTypesAndConditions($brokers, $object_type_id, $object_id, array($settings["user_type_id"]), $conditions, null);
						$settings["data"] = \UserUtil::getUsersByObjectAndUserTypesAndConditions($brokers, $object_type_id, $object_id, array($settings["user_type_id"]), $conditions, null, $options);
					}
					break;
				case "parent_group_and_user_type": 
					if (!empty($settings["user_type_id"])) {
						$settings["total"] = \UserUtil::countUsersByObjectGroupAndUserTypesAndConditions($brokers, $object_type_id, $object_id, $group, array($settings["user_type_id"]), $conditions, null);
						$settings["data"] = \UserUtil::getUsersByObjectGroupAndUserTypesAndConditions($brokers, $object_type_id, $object_id, $group, array($settings["user_type_id"]), $conditions, null, $options);
					}
					break;
				default:
					$settings["total"] = \UserUtil::countUsersWithUserTypesByConditions($brokers, $conditions, null);
					$settings["data"] = \UserUtil::getUsersWithUserTypesByConditions($brokers, $conditions, null, $options);
			}
			
			//prepare data
			if (!empty($settings["data"])) {
				foreach ($settings["data"] as $idx => $row)
					if (!empty($row["user_type_ids"]))
						$settings["data"][$idx]["user_type_ids"] = explode(",", $row["user_type_ids"]);
				
				//Getting User Extra Details
				$this->CommonModuleTableExtraAttributesUtil->prepareItemsWithTableExtra($settings["data"], "user_id");
			}
			else
				$settings["data"] = null;
			
			//Add Join Point
			$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Preparing user data", array(
				"EVC" => $EVC,
				"settings" => &$settings,
				"user_data" => &$settings["data"],
			), "Use this join point to change the loaded user data.");
				
			//prepare user types
			$user_type_options = array( array("value" => "", "label" => "") );
			$user_types = \UserUtil::getAllUserTypes($brokers, true);
			
			if ($user_types) {
				$reserved_user_type_ids = \UserUtil::getReservedUserTypeIds();
				
				$t = count($user_types);
				for ($i = 0; $i < $t; $i++) {
					$user_type_id = isset($user_types[$i]["user_type_id"]) ? $user_types[$i]["user_type_id"] : null;
					$user_type_name = isset($user_types[$i]["name"]) ? $user_types[$i]["name"] : null;
					
					if (!in_array($user_type_id, $reserved_user_type_ids))
						$user_type_options[] = array("value" => $user_type_id, "label" => $user_type_name);
				}
			}
			$settings["fields"]["user_type_ids"]["field"]["input"]["options"] = $user_type_options;
			
			//prepare buttons
			$buttons = array();
			
			if (!empty($settings["allow_update"]) && !empty($settings["total"])) {
				$button = !empty($settings["buttons"]["update"]["field"]) ? $settings["buttons"]["update"] : array(
					"field" => array(
						"class" => "submit_button",
						"input" => array(
							"type" => "submit",
							"value" => "Save",
						)
					)
				);
				$button["field"]["input"]["name"] = "save";
				
				$buttons["update"] = $button;
			}
			else if (!empty($settings["allow_insertion"])) {
				$button = !empty($settings["buttons"]["insert"]["field"]) ? $settings["buttons"]["insert"] : array(
					"field" => array(
						"class" => "submit_button",
						"input" => array(
							"type" => "submit",
							"value" => "Add",
						)
					)
				);
				$button["field"]["input"]["name"] = "save";
				
				$buttons["insert"] = $button;
			}

			if (!empty($settings["allow_deletion"]) && !empty($settings["total"])) {
				$button = !empty($settings["buttons"]["delete"]["field"]) ? $settings["buttons"]["delete"] : array(
					"field" => array(
						"class" => "submit_button",
						"input" => array(
							"type" => "submit",
							"value" => "Delete",
							"extra_attributes" => array(
								0 => array(
									"name" => "onClick",
									"value" => "return confirm('" . translateProjectText($EVC, "Do you wish to delete this item?") . "');"
								),
							)
						)
					)
				);
				$button["field"]["input"]["name"] = "delete";
				
				$buttons["delete"] = $button;
			}
			
			$HtmlFormHandler = null;
			
			if (!empty($settings["ptl"])) {
				$HtmlFormHandler = new \HtmlFormHandler(array("ptl" => $settings["ptl"]));
				
				$settings["ptl"]["code"] = isset($settings["ptl"]["code"]) ? $settings["ptl"]["code"] : null;
				
				foreach ($buttons as $button_name => $button)
					\CommonModuleUI::prepareBlockFieldPTLCode($EVC, $HtmlFormHandler, $settings["ptl"]["code"], $button_name, $button, $settings["data"]);
				
				\CommonModuleUI::prepareBlockFieldPTLCode($EVC, $HtmlFormHandler, $settings["ptl"]["code"], "insert", null, $settings["data"]);
			}
			else {
				foreach ($buttons as $button_name => $button)
					translateProjectFormSettingsElement($EVC, $button);
				
				$container = array(
					"container" => array(
						"class" => "buttons",
						"elements" => array_values($buttons)
					)
				);
				
				$HtmlFormHandler = new \HtmlFormHandler(array("parse_values" => false));
				$settings["next_html"] = $HtmlFormHandler->createElement($container);
			}
			
			//prepare other settings
			$settings["with_form"] = $settings["show_selected_item"] = !empty($buttons);
			$settings["fields"]["user_id"]["field"]["input"]["next_html"] = '<input type="hidden" name="users[#$idx#][user_id]" value="#[$idx][user_id]#" />';
			
			foreach ($settings["fields"] as $field_name => $field)
				$settings["fields"][$field_name]["field"]["input"]["name"] = 'users[#$idx#][' . $field_name . ']';
			
			$settings["fields"]["user_type_ids"]["field"]["input"]["name"] = 'users[#$idx#][user_type_ids][]';
			
			$this->CommonModuleTableExtraAttributesUtil->prepareFileFieldsSettings($EVC, $settings);
			
			//prepare javascript for insert icon
			$tr = '';
			
			if (!empty($settings["allow_insertion"])) {
				$HtmlFormHandler = new \HtmlFormHandler(array("parse_values" => false));
				$fields_aux = $settings["fields"];
				
				if (empty($fields_aux["selected_item"]["field"]["input"]["extra_attributes"]))
					$fields_aux["selected_item"]["field"]["input"]["extra_attributes"] = array();
				
				$fields_aux["selected_item"]["field"]["input"]["extra_attributes"][] = array("name" => "checked", "value" => "");
				$fields_aux["selected_item"]["field"]["input"]["extra_attributes"][] = array("name" => "style", "value" => "display:none;");
				$fields_aux["selected_item"]["field"]["input"]["next_html"] .= '<a class="glyphicon glyphicon-trash icon delete" href="javascript:void(0);" onClick="onListRemoveNewUser(this)">Remove</a>';
				
				$tr = '<tr>';
				foreach ($fields_aux as $field_name => $field) 
					if (!empty($settings["show_" . $field_name])) {
						$class = ' class="list_column' . (!empty($field["field"]["class"]) ? ' ' . str_replace('"', '&quot;', $HtmlFormHandler->getParsedValueFromData($field["field"]["class"], null)) : '') . '"';
						$field["field"]["class"] = "";
						$field["field"]["disable_field_group"] = 1;
						$field["field"]["label"] = null;
						
						$tr .= '<td' . $class . '>' . $HtmlFormHandler->createElement($field)  . '</td>';
					}
				$tr .= '</tr>';
				
				$tr = preg_replace('/#\[\$idx\]\[([\w \-\+\.])+\]#/iu', "", $tr); //'\w' means all words with '_' and '/u' means with accents and ç too.
				$tr = preg_replace('/#\$idx#/i', "#idx#", $tr);
			}
			
			$html .= '<script>var new_user_html = \'' . str_replace("<script>", "<' + 'script>", str_replace("</script>", "</' + 'script>", addcslashes(str_replace("\n", "", $tr), "\\'"))) . '\'</script>';
		}
		
		//Add Join Point
		$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Preparing UI settings", array(
			"EVC" => $EVC,
			"settings" => &$settings,
		), "Use this join point to change the UI settings.");
		
		//print_r($settings);die();
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/list_and_edit_users_with_user_types", $settings);
		return $html . \CommonModuleUI::getListHtml($EVC, $settings);
	}
	
	private function deleteUser($EVC, $settings, $brokers, $user) {
		$status = false;
		
		if (!empty($user["user_id"])) {
			$status = \UserUtil::deleteUser($EVC, $user["user_id"], $brokers);
			
			if ($status)
				$status = $this->CommonModuleTableExtraAttributesUtil->deleteTableExtra(array("user_id" => $user["user_id"]));
		}
		
		return $status;
	}
	
	//this method was copied from user/presentation/edit_user/CMSModuleHandlerImpl.php action and then updated for this class
	private function editUser($EVC, $settings, $brokers, $old_user, $new_user, $files, &$error_message) {
		$status = true;
		
		$user_type_ids = isset($new_user["user_type_ids"]) ? $new_user["user_type_ids"] : null;
		$username = isset($new_user["username"]) ? strtolower(trim($new_user["username"])) : null;
		$password = isset($new_user["password"]) ? trim($new_user["password"]) : null;
		$name = isset($new_user["name"]) ? $new_user["name"] : null;
		$email = isset($new_user["email"]) ? strtolower($new_user["email"]) : null;
		$security_question_1 = isset($new_user["security_question_1"]) ? $new_user["security_question_1"] : null;
		$security_answer_1 = isset($new_user["security_answer_1"]) ? $new_user["security_answer_1"] : null;
		$security_question_2 = isset($new_user["security_question_2"]) ? $new_user["security_question_2"] : null;
		$security_answer_2 = isset($new_user["security_answer_2"]) ? $new_user["security_answer_2"] : null;
		$security_question_3 = isset($new_user["security_question_3"]) ? $new_user["security_question_3"] : null;
		$security_answer_3 = isset($new_user["security_answer_3"]) ? $new_user["security_answer_3"] : null;
		$active = isset($new_user["active"]) ? $new_user["active"] : null;
		
		$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("user_type_ids" => $user_type_ids, "username" => $username, /*"password" => $password, */"name" => $name, "email" => $email, "security_question_1" => $security_question_1, "security_answer_1" => $security_answer_1, "security_question_2" => $security_question_2, "security_answer_2" => $security_answer_2, "security_question_3" => $security_question_3, "security_answer_3" => $security_answer_3, "active" => $active), $files);
		
		if (!$empty_field_name)
			$empty_field_name = $this->CommonModuleTableExtraAttributesUtil->checkIfEmptyFields($settings, $new_user, $files);
		
		if ($empty_field_name) 
			$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
		else {
			$new_data = $old_user ? $old_user : array();
		
			if (!empty($settings["show_username"])) {
				if (empty($username))
					$new_data["username"] = $username;
				else if (!isset($old_user["username"]) || strtolower($old_user["username"]) != strtolower($username)) {
					$users = \UserUtil::getUsersByConditionsAccordingWithUserEnvironmentsSettings($brokers, isset($settings["user_environments"]) ? $settings["user_environments"] : null, array("username" => $username), null, null, true);
					$user_exists = !empty($users[0]["user_id"]);
		
					if ($user_exists) {
						$username_label = \CommonModuleUI::getFieldLabel($settings, "username");
						$error_message = translateProjectText($EVC, "This #username# already exists! Please choose another #username#...");
						$error_message = str_replace("#username#", $username_label, $error_message);
					}
					else
						$new_data["username"] = $username;
				}
			}
		
			if (empty($error_message)) {
				$new_data["password"] = isset($new_data["password"]) ? $new_data["password"] : null;
				$new_data["password"] = !empty($settings["show_password"]) ? (strlen($password) ? $password : $new_data["password"]) : $new_data["password"];
				$new_data["name"] = !empty($settings["show_name"]) ? $name : (isset($new_data["name"]) ? $new_data["name"] : null);
				$new_data["email"] = !empty($settings["show_email"]) ? $email : (isset($new_data["email"]) ? $new_data["email"] : null);
				$new_data["security_question_1"] = !empty($settings["show_security_question_1"]) ? $security_question_1 : (isset($new_data["security_question_1"]) ? $new_data["security_question_1"] : null);
				$new_data["security_answer_1"] = !empty($settings["show_security_answer_1"]) ? $security_answer_1 : (isset($new_data["security_answer_1"]) ? $new_data["security_answer_1"] : null);
				$new_data["security_question_2"] = !empty($settings["show_security_question_2"]) ? $security_question_2 : (isset($new_data["security_question_2"]) ? $new_data["security_question_2"] : null);
				$new_data["security_answer_2"] = !empty($settings["show_security_answer_2"]) ? $security_answer_2 : (isset($new_data["security_answer_2"]) ? $new_data["security_answer_2"] : null);
				$new_data["security_question_3"] = !empty($settings["show_security_question_3"]) ? $security_question_3 : (isset($new_data["security_question_3"]) ? $new_data["security_question_3"] : null);
				$new_data["security_answer_3"] = !empty($settings["show_security_answer_3"]) ? $security_answer_3 : (isset($new_data["security_answer_3"]) ? $new_data["security_answer_3"] : null);
				
				$new_data["active"] = !empty($settings["show_active"]) ? $active : (isset($new_data["active"]) ? $new_data["active"] : null);
				$new_data["user_type_ids"] = !empty($settings["show_user_type_ids"]) ? $user_type_ids : (isset($new_data["user_type_ids"]) ? $new_data["user_type_ids"] : null);
				
				$this->CommonModuleTableExtraAttributesUtil->prepareFieldsWithNewData($settings, $new_data, $old_user, $new_user);
				
				\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data, $files);
				
				if (strlen($new_data["active"]) == 0) //if active is allow_null and is a checkbox the POST will not contain the active field and bc is allow null, the prepareFieldsWithDefaultValue won't set the default value. So we need to do it manually.
					$new_data["active"] = isset($settings["active_default_value"]) && is_numeric($settings["active_default_value"]) ? $settings["active_default_value"] : 0;
				
				$active = $new_data["active"]; //set $active with default values with apply
				$inserted_user_id = null;
				
				if ($new_data["user_type_ids"])
					$new_data["user_type_ids"] = \UserUtil::PUBLIC_USER_TYPE_ID;
				
				if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message, $files) && $this->CommonModuleTableExtraAttributesUtil->areFileFieldsValid($EVC, $settings, $error_message, $files)) {
					$new_data["object_users"] = isset($new_data["object_users"]) ? $new_data["object_users"] : (isset($settings["object_to_objects"]) ? $settings["object_to_objects"] : null);
					$new_data["user_environments"] = isset($new_data["user_environments"]) ? $new_data["user_environments"] : (isset($settings["user_environments"]) ? $settings["user_environments"] : null);
					$new_data["do_not_encrypt_password"] = isset($settings["do_not_encrypt_password"]) ? $settings["do_not_encrypt_password"] : null;
					
					if (!empty($settings["allow_insertion"]) && empty($old_user["user_id"])) {
						$status = \UserUtil::insertUser($EVC, $new_data, $brokers);
						$inserted_user_id = $status;
						
						if ($new_data["user_type_ids"]) {
							if (is_array($new_data["user_type_ids"])) {
								foreach ($new_data["user_type_ids"] as $user_type_id)
									if ($user_type_id && !\UserUtil::insertUserUserType($brokers, array("user_id" => $inserted_user_id, "user_type_id" => $user_type_id)))
										$status = false;
							}
							else if ($new_data["user_type_ids"] && !\UserUtil::insertUserUserType($brokers, array("user_id" => $inserted_user_id, "user_type_id" => $new_data["user_type_ids"])))
								$status = false;
						}
					}
					else if (!empty($settings["allow_update"]) && !empty($old_user["user_id"]) && \UserUtil::updateUser($EVC, $new_data, $brokers)) {
						if (!empty($settings["show_password"]) && strlen($new_data["password"])) {
							//only update password if exists any change
							if (empty($new_data["do_not_encrypt_password"]) || !isset($old_user["password"]) || $old_user["password"] != $new_data["password"])
								$status = \UserUtil::updateUserPassword($brokers, $new_data);
						}
						
						$old_user_type_ids = isset($old_user["user_type_ids"]) ? $old_user["user_type_ids"] : null;
						
						if ($status && $$old_user_type_ids != $new_data["user_type_ids"]) {
							$status = !$$old_user_type_ids || \UserUtil::deleteUserUserTypesByConditions($brokers, array("user_id" => $old_user["user_id"]), null);
							
							if ($new_data["user_type_ids"]) {
								if (is_array($new_data["user_type_ids"])) {
									foreach ($new_data["user_type_ids"] as $user_type_id)
										if ($user_type_id && !\UserUtil::insertUserUserType($brokers, array("user_id" => $old_user["user_id"], "user_type_id" => $user_type_id)))
											$status = false;
								}
								else if ($new_data["user_type_ids"] && !\UserUtil::insertUserUserType($brokers, array("user_id" => $old_user["user_id"], "user_type_id" => $new_data["user_type_ids"])))
									$status = false;
							}
						}
					}
				}
				
				if ($status) {
					$user_id = !empty($settings["allow_insertion"]) && empty($old_user["user_id"]) ? $inserted_user_id : (isset($old_user["user_id"]) ? $old_user["user_id"] : null);
					
					//save user extra
					$new_extra_data = $new_data;
					$new_extra_data["user_id"] = $user_id;
					$s = $this->CommonModuleTableExtraAttributesUtil->insertOrUpdateTableExtra($new_extra_data, $files);
					//No need to call the $this->CommonModuleTableExtraAttributesUtil->reloadSavedTableExtra bc we will get the new items from the DB after after this method.
					
					if (!$s) //note that on insert the status contains the new user id
						$status = false;
					
					if (!empty($settings["show_username"]) && $username && (!isset($old_user["username"]) || strtolower($old_user["username"]) != strtolower($username)))
						\UserUtil::changeUserSessionUsernameByUsername($brokers, $settings, $old_user["username"], $username);
					
					//save user active status
					if (!empty($settings["show_active"])) 
						$status = \UserUtil::updateUserActiveStatus($brokers, array("user_id" => $user_id, "active" => $active));
				}
				else if (!empty($settings["allow_insertion"]) && empty($old_user["user_id"]) && $inserted_user_id)
					\UserUtil::deleteUser($EVC, $inserted_user_id, $brokers);
			}
		}
		
		return $status;
	}
}
?>
