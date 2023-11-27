<?php
namespace CMSModule\user\single_sign_on;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("common/ObjectToObjectValidationHandler", $common_project_name);
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		include_once get_lib("lib.vendor.auth0.autoload");
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$current_page_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
		$current_page_url .= strpos($current_page_url, "?") !== false ? "" : "?";
		$current_page_url = preg_replace("/action=[^&]*/", "", $current_page_url);
		
		$session_id_var_name = \UserUtil::getConstantVariable("USER_SESSION_ID_VARIABLE_NAME");
		$session_id = $_COOKIE[$session_id_var_name];
		
		if ($session_id)
			$user_session = \UserUtil::isLoggedIn($brokers, $session_id, \UserUtil::getConstantVariable("DEFAULT_USER_SESSION_EXPIRATION_TTL"), true);
		
		switch ($_GET["action"]) {
			case "login":
				$remote_error = $_GET["error"];
				$remote_error_description = $_GET["error_description"];
				$remote_code = $_GET["code"];
				$remote_state = $_GET["state"];
				$remote_nonce = $_COOKIE["auth0__nonce"];
				
				// Handle errors sent back by Auth0.
				if (!empty($remote_error) || !empty($remote_error_description))
				    $error_message = "Error: \n" . htmlspecialchars($remote_error_description);
				else if (!$remote_code) 
					$error_message = "Error trying to login to auth0!";
				else if (rand(0, 100) > 80 && \UserUtil::usersCountExceedLimit($EVC)) //check if users count exceeded the licence limit. This should only happen if the user hacked this module and delete the same checks in other modules. Otherwise this should not happen ever!!!
					$error_message = "Users count exceeded the licence limit. Please renew your licence with more users...";
				else { 
					//get user info from auth0
					try {
						$auth0 = new \Auth0\SDK\Auth0(array(
							'domain' => $settings['domain'],
							'client_id' => $settings['client_id'],
							'client_secret' => $settings['secret'],
							'audience' => $settings['audience'],
							'scope' => 'openid profile email',
							'redirect_uri' => $current_page_url,
						));
						
						$user_info = $auth0->getUser();
						
						if (!$user_info)
							$error_message = "Error: no user data for logged user!";
					}
					catch (\Exception $e) {
						$error_message = $e->getMessage();
					}
				}
				
				//if user info was got correctly from auth0 servers
				if (!$error_message && $user_info) {
					$sub = $user_info["sub"];
					$parts = explode("|", $sub);
					$social_network_type = $parts[0];
					$social_network_user_id = $parts[1];
					
					if (!$social_network_type)
						$error_message = "Error: social network type is undefined!";
					else if (!$social_network_user_id)
						$error_message = "Error: social network user id is undefined!";
					else { //if social_network_type and social_network_user_id
						//gets external users
						$existent_external_users = \UserUtil::getExternalUsersByConditions($brokers, array("social_network_type" => $social_network_type, "social_network_user_id" => $social_network_user_id), null);
						$found_ea = null;
						
						//prepare environment_ids
						$environment_ids = \UserUtil::getUserEnvironmentIds($settings["user_environments"]);
						
						//if external users
						if ($existent_external_users) {
							//if no environment_ids defined, gets the first external user, but gives priority to external users already with user id.
							if (!$environment_ids) {
								$found_ea = $existent_external_users[0];
								
								//checks if exists any external users already with a user_id
								if (!$found_ea["user_id"])
									foreach ($existent_external_users as $ea)
										if ($ea["user_id"]) {
											$found_ea = $ea;
											break;
										}
							}
							else {
								//check if external_users belongs to environment_ids
								foreach ($existent_external_users as $ea) {
									if ($ea["user_id"]) {
										$is_same_environment = \UserUtil::countUserEnvironmentsByConditions($brokers, array(
											"user_id" => $ea["user_id"],
											"environment_id" => array(
												"value" => $environment_ids,
												"operator" => "in"
											),
										), null);
										
										if ($is_same_environment > 0) {
											$found_ea = $ea;
											break;
										}
									}
									else { //if no user_id, choose this user so we can use it bc is lost in the DB with no relations
										$found_ea = $ea;
										break;
									}
								}
							}
						}
						
						//if no external user found, creates one.
						if (!$found_ea) {
							$external_user_data = array(
								"external_type_id" => 0, //0: auth0
								"social_network_type" => $social_network_type,
								"social_network_user_id" => $social_network_user_id,
								"token_1" => $remote_code,
								"token_2" => $remote_state,
								"token_3" => $remote_nonce,
								"data" => json_encode($user_info),
							);
							$external_user_id = \UserUtil::insertExternalUser($brokers, $external_user_data);
							
							if ($external_user_id) {
								$external_user_data["external_user_id"] = $external_user_id;
								$found_ea = $external_user_data;
							}
						}
						else { //updates new 
							$found_ea["token_1"] = $remote_code;
							$found_ea["token_2"] = $remote_state;
							$found_ea["token_3"] = $remote_nonce;
							$found_ea["data"] = json_encode($user_info);
							
							\UserUtil::updateExternalUser($brokers, $found_ea);
						}
						
						if (!$found_ea)
							$error_message = "Error trying to create new external user. Please try again...";
						else {
							//if no user_id in external user
							if (!$found_ea["user_id"]) {
								//if create_new_user_if_none_found
								if ($settings["user_settings_type"] == "create_new_user_if_none_found") {
									//if relates_user_by_email and $user_info["email"]
									if ($settings["relates_user_by_email"] && $user_info["email"]) {
										$choose_users_html = $this->prepareUsersWithSameEmails($settings, $brokers, $user_info, $environment_ids, $selected_user_id);
										
										//if user was already selected from client
										if ($selected_user_id)
											$found_ea["user_id"] = $selected_user_id;
										else if ($choose_users_html) //otherwise show found users with the same email and environments, but only if this wasn't shown before, this is, if $html exists.
											return $choose_users_html;
									}
									
									//if no user selection from client or if NOT relates_user_by_email or NOT confirms_user_relation_by_email 
									if (!$found_ea["user_id"]) {
										//prepare register action or html
										$register_html = $this->registerUser($settings, $brokers, $user_info, $error_message, $registered_user_id); 
										
										if ($registered_user_id) 
											$found_ea["user_id"] = $registered_user_id;
										else if ($register_html) //show register form html
											return $register_html; 
									}
								}
								//if users creation is not allowed
								else if ($settings["user_settings_type"] == "new_user_creation_not_allowed")
									$error_message = "Error: This user is not related with any registered native user yet and new users creations are not allowed. Please create this relation first, by opening the edit_profile or edit_user page.";
								//if should relate with a harded coded user id
								else if ($settings["user_settings_type"] == "relate_with_specific_user" && $settings["user_id"])
									$found_ea["user_id"] = $settings["user_id"];
								//if should relate with a logged user
								else if ($settings["user_settings_type"] == "relate_with_logged_user" && $user_session && $user_session["user_id"])
									$found_ea["user_id"] = $user_session["user_id"];
								
								//if user id, updates the external user with it, otherwise error...
								if (!$found_ea["user_id"])
									$error_message = $error_message ? $error_message : "Error: wrong user id.";
								else if (!\UserUtil::updateExternalUser($brokers, $found_ea))
									$error_message = "Error trying to update new user id to external user.";
							}
							
							//prepare other social connections that the user may have
							if (!$error_message && $user_info["identities"]) {
								foreach ($user_info["identities"] as $identity) {
									$sn_type = $identity["provider"];
									$sn_user_id = $identity["user_id"];
									
									if ($identity["sub"] && (!$sn_type || !$sn_user_id)) {
										$parts = explode("|", $identity["sub"]);
										
										if (!$sn_type)
											$sn_type = $parts[0];
										
										if (!$sn_user_id)
											$sn_user_id = $parts[1];
									}
									
									//add new external user for this selected user
									if ($sn_type && $sn_user_id && $sn_type != $social_network_type && $sn_user_id != $social_network_user_id) {
										//check if already exists
										$exists = \UserUtil::countExternalUsersByConditions($brokers, array(
											"user_id" => $found_ea["user_id"],
											"social_network_type" => $sn_type,
											"social_network_user_id" => $sn_user_id,
										), null);
										
										//if not exists yet, creates a new external user
										if (!$exists) {
											$external_user_data = array(
												"user_id" => $found_ea["user_id"],
												"external_type_id" => 0, //0: auth0
												"social_network_type" => $sn_type,
												"social_network_user_id" => $sn_user_id,
												"token_1" => $remote_code,
												"token_2" => $remote_state,
												"token_3" => $remote_nonce,
												"data" => json_encode($user_info),
											);
											
											if (!\UserUtil::insertExternalUser($brokers, $external_user_data))
												$error_message = "Error inserting extra external user with other social connections.";
										}
									}
								}
							}
							
							if (!$error_message) {
								//prepare new session
								if (!$user_session || !$user_session["session_id"] || $user_session["user_id"] != $found_ea["user_id"]) {
									$user_session = \UserUtil::externalLogin($brokers, $found_ea["external_user_id"], \UserUtil::getConstantVariable("DEFAULT_USER_SESSION_BLOCKED_TTL"), $settings, true);
									
									if ($user_session == \UserUtil::USER_BLOCKED) //This should only happen if user tries to login manually via the native login panel...
										$error_message = "This user is blocked. Please try again later...";
									else if (!is_array($user_session))
										$error_message = "Error: User session could not be created. Please try again...";
								}
								
								//if session is ok
								if (!$error_message && $user_session && $user_session["session_id"]) {
									//Delete old session_id, in case the user tries to login with a different username
									if ($session_id && $user_session["session_id"] && $session_id != $user_session["session_id"])
										\UserUtil::deleteUserSessionBySessionId($brokers, $session_id);
									
									//Add Join Point
									$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("On external user login", array(
										"EVC" => &$EVC,
										"user_session" => &$user_session,
										"error_message" => &$error_message,
										"settings" => &$settings,
									));
									
									//set session cookies
									$show_captcha_var_name = \UserUtil::getConstantVariable("USER_LOGIN_SHOW_CAPTCHA_VARIABLE_NAME");
									
									$session_id = $user_session["session_id"];
									$show_captcha = false;
									
									$extra_flags = \UserUtil::getConstantVariable("USER_SESSION_COOKIES_EXTRA_FLAGS");
									\CookieHandler::setSafeCookie($session_id_var_name, $session_id, 0, "/", $extra_flags); //this method already sets $_COOKIE[$session_id_var_name] = $session_id;
									\CookieHandler::setSafeCookie($show_captcha_var_name, $show_captcha, 0, "/", $extra_flags);
									
									//add session control bc of xss and csfr attacks
									$user_session_control_var_name = \UserUtil::getConstantVariable("USER_SESSION_CONTROL_VARIABLE_NAME");
									$user_session_control_expired_time = \UserUtil::getConstantVariable("USER_SESSION_CONTROL_EXPIRED_TIME");
									$user_session_control_encryption_key = \CryptoKeyHandler::hexToBin( \UserUtil::getConstantVariable("USER_SESSION_CONTROL_ENCRYPTION_KEY_HEX") );
									$ttl = \CryptoKeyHandler::encryptText( time() + $user_session_control_expired_time, $user_session_control_encryption_key );
									$ttl = \CryptoKeyHandler::binToHex($ttl);
									\CookieHandler::setSafeCookie($user_session_control_var_name, $ttl, 0, "/", $extra_flags);
								}
							}
						}
					}
				}
				break;
			
			default:
				//only opens social login panel if no session active
				if (!$user_session["session_id"]) {
					try {
						$auth0 = new \Auth0\SDK\Auth0(array(
							'domain' => $settings['domain'],
							'client_id' => $settings['client_id'],
							'audience' => $settings['audience'],
							'scope' => 'openid profile email',
							'redirect_uri' => $current_page_url . "&action=login",
						));
						
						$auth0->login();
					}
					catch (\Exception $e) {
						$error_message = $e->getMessage();
					}
				}
		}
		
		//prepare validation or non_validation actions
		if ($error_message)
			$settings["non_validation_message"] .= ($settings["non_validation_message"] ? "\n" : "") . $error_message;
		
		$settings["style_type"] = $settings["actions_style_type"];
		return \ObjectToObjectValidationHandler::validate($EVC, empty($error_message), $settings);
		
	}
	
	private function prepareUsersWithSameEmails($settings, $brokers, $user_info, $environment_ids, &$selected_user_id) {
		//if user was already selected from client
		if ($_POST["choose_user_with_same_email"])
			$selected_user_id = $_POST["selected_user_id"]; //even if no selected user id, don't show user list anymore bc it should show the register form.
		else if (!$_POST["save"]) { //otherwise show found users with the same email and environments, but only if this wasn't shown before. The $_POST["save"] button is from the registerUser method
			$users = \UserUtil::getUsersByConditions($brokers, array("email" => $user_info["email"]), null);
			
			if ($users) {
				$found_users = array();
				
				if (!$environment_ids)
					$found_users = $users;
				else //prepare user with the same environments
					foreach ($users as $user) {
						$is_same_environment = \UserUtil::countUserEnvironmentsByConditions($brokers, array(
							"user_id" => $user["user_id"],
							"environment_id" => array(
								"value" => $environment_ids,
								"operator" => "in"
							),
						), null);
						
						if ($is_same_environment)
							$found_users[] = $user;
					}
				
				//if any found users
				if ($found_users) {
					//if confirms_user_relation_by_email show a list of found users so the client can choose which user which to relate him-self.
					if ($settings["confirms_user_relation_by_email"])
						return $this->showFoundUsersWithSameEmail($settings, $brokers, $found_users); //html with list of found users
					else //chooses the first found user
						$selected_user_id = $found_users[0]["user_id"];
				}
			}
		}
		
		return null;
	}
	
	private function showFoundUsersWithSameEmail($settings, $brokers, $found_users) {
		$EVC = $this->getEVC();
		
		include_once $EVC->getModulePath("common/CommonModuleUI", $EVC->getCommonProjectName());

		$list_settings = array(
			"with_form" => true,
			"class" => "module_single_sign_on module_single_sign_on_users " . $settings["found_users_list_class"],
			"style_type" => $settings["style_type"],
			"css" => $settings["css"],
			"js" => $settings["js"],
			"data" => $found_users,
			"next_html" => '
				<div class="buttons">
					<div class="button_choose submit_button button"><input type="submit" class="btn btn-primary" value="Choose" name="choose_user_with_same_email"></div>
				</div>',
			"fields" => array(
				"selected_user_id" => array(
					"field" => array(
						"class" => "selected_user_id",
						"label" => array(
							"type" => "label",
							"value" => "",
						),
						"input" => array(
							"type" => "radio",
							"name" => "selected_user_id",
							"options" => array(
								array("value" => "#[\$idx][user_id]#")
							),
						),
					)
				),
				"user_id" => array(
					"field" => array(
						"class" => "user_id",
						"label" => array(
							"type" => "label",
							"value" => "User Id:",
						),
						"input" => array(
							"type" => "label",
							"value" => "#[\$idx][user_id]#",
						),
					)
				),
				"username" => array(
					"field" => array(
						"class" => "username",
						"label" => array(
							"type" => "label",
							"value" => "Username:",
						),
						"input" => array(
							"type" => "label",
							"value" => "#[\$idx][username]#",
						),
					)
				),
				"name" => array(
					"field" => array(
						"class" => "name",
						"label" => array(
							"type" => "label",
							"value" => "Name:",
						),
						"input" => array(
							"type" => "label",
							"value" => "#[\$idx][name]#",
						),
					)
				),
				"email" => array(
					"field" => array(
						"class" => "email",
						"label" => array(
							"type" => "label",
							"value" => "Email:",
						),
						"input" => array(
							"type" => "label",
							"value" => "#[\$idx][email]#",
						),
					)
				),
			),
			"show_selected_user_id" => true,
			"show_user_id" => true,
			"show_username" => true,
			"show_name" => true,
			"show_email" => true,
		);
		
		return \CommonModuleUI::getListHtml($EVC, $list_settings);
	}
	
	private function registerUser($settings, $brokers, $user_info, &$error_message, &$registered_user_id) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("user/UserModuleUI", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleTableExtraAttributesUtil", $common_project_name);
		
		$CommonModuleTableExtraAttributesUtil = new \CommonModuleTableExtraAttributesUtil($this, $GLOBALS["default_db_driver"], $settings, "user");
		
		//Preparing Action
		if ($_POST["save"]) {
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
										
										if ($status)
											$registered_user_id = $user_id;
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
		
		if (!$_POST["save"]) {
			$form_data["name"] = $user_info["name"];
			$form_data["email"] = $user_info["email"];
		}
		
		$CommonModuleTableExtraAttributesUtil->prepareFieldsWithNewData($settings, $form_data, array(), $_POST);
		
		$form_data = $data ? array_merge($data, $form_data) : $form_data;//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/user/single_sign_on.css';
		$settings["js_file"] = $project_common_url_prefix . 'module/user/single_sign_on.js';
		$settings["class"] = "module_single_sign_on module_single_sign_on_register";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		$settings["allow_insertion"] = true;
		$settings["on_insert_ok_action"] = "do_nothing";
		$settings["on_insert_error_message"] = "User NOT registered! Please try again...";
		$settings["on_insert_error_action"] = "show_message";
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
		$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("New User bottom register fields", array(
			"EVC" => &$EVC,
			"settings" => &$settings,
			"object_type_id" => \ObjectUtil::USER_OBJECT_TYPE_ID,
			"object_id" => &$user_id,
			"group_id" => \UserUtil::USER_ATTACHMENTS_GROUP_ID,
		));
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/single_sign_on", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
