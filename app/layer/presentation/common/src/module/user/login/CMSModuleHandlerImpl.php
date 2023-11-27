<?php
namespace CMSModule\user\login;

include_once get_lib("org.phpframework.util.web.html.HtmlFormHandler");

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$username_attribute_label = $settings["fields"]["username"]["field"]["label"]["value"];
		$password_attribute_label = $settings["fields"]["password"]["field"]["label"]["value"];
		$register_attribute_label = $settings && array_key_exists("register_attribute_label", $settings) ? $settings["register_attribute_label"] : "Register?";
		$forgot_credentials_attribute_label = $settings && array_key_exists("forgot_credentials_attribute_label", $settings) ? $settings["forgot_credentials_attribute_label"] : "Forgot Credentials?";
		$single_sign_on_attribute_label = $settings && array_key_exists("single_sign_on_attribute_label", $settings) ? $settings["single_sign_on_attribute_label"] : "Single Sign On?";
		
		$username_attribute_label = explode(":", $username_attribute_label);
		$username_attribute_label = $username_attribute_label[0];
		$password_attribute_label = explode(":", $password_attribute_label);
		$password_attribute_label = $password_attribute_label[0];
		
		$username_attribute_default_value = $settings["username_default_value"];
		$password_attribute_default_value = $settings["password_default_value"];
		
		$session_id_var_name = \UserUtil::getConstantVariable("USER_SESSION_ID_VARIABLE_NAME");
		$session_id = $_COOKIE[$session_id_var_name];
		
		//if session id doesn't exist, captcha can never be shown bc it is related with the session. If someone deletes the session cookie on purpose when captcha is already active in the DB, it's fine bc the system will detect this case and ask the user to login again but with with a captcha.
		if (!$session_id) 
		    $settings["show_captcha"] = 0; //Do not remove this please!
		
		$show_captcha_var_name = \UserUtil::getConstantVariable("USER_LOGIN_SHOW_CAPTCHA_VARIABLE_NAME");
		$show_captcha = $settings["show_captcha"] && $_COOKIE[$show_captcha_var_name] ? true : false;
		
		//Including Stylesheet
		$html = '';
		if (empty($settings["style_type"]))
			$html .= '<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/user/login.css" type="text/css" charset="utf-8" />';
		
		$html .= $settings["css"] ? '<style>' . $settings["css"] . '</style>' : '';
		$html .= $settings["js"] ? '<script type="text/javascript">' . $settings["js"] . '</script>' : '';
		
		//Preparing Action
		if ($_POST["login"]) {
			$username = strtolower($_POST["username"]);
			$password = $_POST["password"];
			$captcha = $_POST["captcha"];
			
			$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("username" => $username, "password" => $password));
			if ($empty_field_name)
				$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
			else if (rand(0, 100) > 80 && \UserUtil::usersCountExceedLimit($EVC)) //check if users count exceeded the licence limit. This should only happen if the user hacked this module and delete the same checks in other modules. Otherwise this should not happen ever!!!
				$error_message = translateProjectText($EVC, "Users count exceeded the licence limit. Please renew your licence with more users...");
			else {
				$user_session = \UserUtil::login($brokers, $username, $password, $captcha, \UserUtil::getConstantVariable("DEFAULT_USER_SESSION_BLOCKED_TTL"), $settings, true);
				
				if ($user_session == \UserUtil::USER_BLOCKED) {
					//blocked_user
					$show_captcha = true;
					$error_message = translateProjectText($EVC, "This user is blocked. Please try again later...") . str_replace("#hours#", \UserUtil::getConstantVariable("DEFAULT_USER_SESSION_BLOCKED_TTL") / 60 / 60, translateProjectText($EVC, "User blocked for #hours# hours."));
					unset($user_session);
				}
				else if ($user_session == \UserUtil::WRONG_CAPTCHA) {
					//wrong captcha
					$show_captcha = true;
					$error_message = "Please insert the right captcha text!";
					unset($user_session);
				}
				else if ($user_session == \UserUtil::DUPLICATED_USERNAME) {
					//wrong DB values
					$error_message = "There was an internal error because it seems your username is repeated in the DB. Please contact the sysadmin before continue...";
					unset($user_session);
				}
				else if ($user_session == \UserUtil::INACTIVE_USERNAME) {
					//wrong DB values
					$error_message = "This user is inactive.";
					unset($user_session);
				}
				else if (is_array($user_session)) {
					if ($user_session["logged_status"]) {
						$show_captcha = false;
						$error_message = "Login successfully...";
					}
					else {
						if ($settings["show_captcha"] && $user_session["failed_login_attempts"] >= $settings["maximum_login_attempts_to_show_captcha"])
							$show_captcha = true;
						
						$error_message = str_replace("#password_label#", translateProjectText($EVC, $password_attribute_label), str_replace("#username_label#", translateProjectText($EVC, $username_attribute_label), translateProjectText($EVC, "Incorrect #username_label# or #password_label#...")));
					}
				}
				else
					$error_message = "There was an error trying to login user. Please try again...";
			}
		}
		else if ($session_id)
			$user_session = \UserUtil::isLoggedIn($brokers, $session_id, \UserUtil::getConstantVariable("DEFAULT_USER_SESSION_EXPIRATION_TTL"), true);
		
		//Delete old session_id, in case the user tries to login with a different username
		if ($session_id && is_array($user_session) && $user_session["session_id"] && $session_id != $user_session["session_id"])
			\UserUtil::deleteUserSessionBySessionId($brokers, $session_id);
		
		if ($user_session["session_id"]) {
			//Add Join Point
			$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("On user login", array(
				"EVC" => &$EVC,
				"user_session" => &$user_session,
				"show_captcha" => &$show_captcha,
				"error_message" => &$error_message,
				"settings" => &$settings,
			));
		}
		
		$session_id = $user_session["session_id"] ? $user_session["session_id"] : $session_id;
		$show_captcha = $settings["show_captcha"] ? $show_captcha : false;
		
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
		
		//Preparing HTML
		$html .= '<div class="module_login ' . ($settings["block_class"]) . '">';
		
		if  ($user_session["logged_status"]) {
			if ($settings["welcoming_message"]) {
				$welcoming_message = translateProjectText($EVC, $settings["welcoming_message"]);
				
				foreach ($user_session as $key => $value)
					$welcoming_message = preg_replace("/#$key#/iu", $value, $welcoming_message); //'/u' means with accents and ç too.
				
				$html .= '<div class="info">' . $welcoming_message . '</div>';
			}
			
			if ($settings["redirect_page_url"]) {
				header("Location: " . $settings["redirect_page_url"]);
				$html .= '<script>document.location = \'' . $settings["redirect_page_url"] . '\';</script>';
			}
		}
		else {
			if ($error_message)
				$html .= \CommonModuleUI::getModuleMessagesHtml($EVC, null, $error_message);
			
			$settings["fields"]["username"]["field"]["class"] = "form_field " . $settings["fields"]["username"]["field"]["class"];
			$settings["fields"]["username"]["field"]["input"]["type"] = "text";
			$settings["fields"]["username"]["field"]["input"]["name"] = "username";
			$settings["fields"]["username"]["field"]["input"]["value"] = "#username#";
			
			$settings["fields"]["password"]["field"]["class"] = "form_field " . $settings["fields"]["password"]["field"]["class"];
			$settings["fields"]["password"]["field"]["input"]["type"] = "password";
			$settings["fields"]["password"]["field"]["input"]["name"] = "password";
			$settings["fields"]["password"]["field"]["input"]["value"] = "#password#";
			
			if ($show_captcha) {
				$captcha_url = $project_url_prefix . 'module/user/login/get_captcha_image?session_id=' . $session_id;
				
				$settings["fields"]["captcha"] = array(
					"container" => array(
						"class" => "captcha",
						"previous_html" => '
	<span class="captcha_image">
		<img src="' . $captcha_url . '" onError="$(this).parent().remove()" />
	</span>
	<span class="captcha_refresh">
		<a href="#" onclick="$(this).parent().parent().find(\'.captcha_image img\')[0].src=\'' . $captcha_url . '&\'+Math.random(); $(this).parent().parent().find(\'.form_field input\').focus();" id="change-image">' . translateProjectText($EVC, "Not readable? Change text.") . '</a>
	</span>
						',
						"elements" => array(
							0 => array(
								"field" => array(
									"class" => "form_field",
									"label" => array(
										"value" => "Captcha: ",
									),
									"input" => array(
										"type" => "text",
										"name" => "captcha",
										"value" => "",
										"extra_attributes" => array(
											0 => array(
												"name" => "allowNull",
												"value" => "false"
											),
											1 => array(
												"name" => "validationMessage",
												"value" => str_replace("#label#", "Captcha", translateProjectText($EVC, "#label# cannot be undefined!"))
											),
											2 => array(
												"name" => "autocomplete",
												"value" => "off"
											)
										),
									)
								)
							),
						)
					)
				);
			}
			
			if (!$settings["buttons"] || !array_key_exists("insert", $settings["buttons"]))
				$login_button = array(
					"field" => array(
						"class" => "submit_button",
						"input" => array(
							"type" => "submit",
							"name" => "login",
							"value" => "Login",
						)
					)
				);
			else {
				$settings["buttons"]["insert"]["field"]["input"]["name"] = "login"; //force button name to be login
				$login_button = $settings["buttons"]["insert"];
			}
			
			$buttons = array();
			$buttons["submit"] = array(
				"container" => array(
					"class" => "buttons",
					"elements" => array(
						0 => $login_button,
					)
				)
			);
			
			if ($settings["register_page_url"])
				$buttons["register"] = array(
					"container" => array(
						"class" => "register",
						"elements" => array(
							0 => array(
								"field" => array(
									"input" => array(
										"type" => "link",
										"value" => $register_attribute_label,
										"href" => $settings["register_page_url"],
									)
								)
							),
						)
					)
				);
			
			if ($settings["forgot_credentials_page_url"])
				$buttons["forgot-credentials"] = array(
					"container" => array(
						"class" => "forgot_credentials",
						"elements" => array(
							0 => array(
								"field" => array(
									"input" => array(
										"type" => "link",
										"value" => $forgot_credentials_attribute_label,
										"href" => $settings["forgot_credentials_page_url"],
									)
								)
							),
						)
					)
				);
			
			if ($settings["single_sign_on_page_url"])
				$buttons["single-sign-on"] = array(
					"container" => array(
						"class" => "single_sign_on",
						"elements" => array(
							0 => array(
								"field" => array(
									"input" => array(
										"type" => "link",
										"value" => $single_sign_on_attribute_label,
										"href" => $settings["single_sign_on_page_url"],
									)
								)
							),
						)
					)
				);
			
			$form_settings = array(
				"with_form" => 1,
				"form_id" => "",
				"form_method" => "post",
				"form_class" => "",
				"form_on_submit" => "",
				"form_action" => "",
				"form_containers" => array(
					0 => array(
						"container" => array(
							"class" => "form_fields",
							"previous_html" => "",
							"next_html" => "",
							"elements" => array(),
						)
					),
				)
			);
			
			//prepare settings with selected template html if apply
			\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/login", $settings);
			
			if ($settings["ptl"]) {
				$HtmlFormHandler = new \HtmlFormHandler(array("ptl" => $settings["ptl"]));
				
				foreach ($settings["fields"] as $field_name => $field)
					\CommonModuleUI::prepareBlockFieldPTLCode($EVC, $HtmlFormHandler, $settings["ptl"]["code"], $field_name, $field, $form_data);
				
				foreach ($buttons as $button_name => $button)
					\CommonModuleUI::prepareBlockFieldPTLCode($EVC, $HtmlFormHandler, $settings["ptl"]["code"], $button_name, $button, $form_data);
				
				\CommonModuleUI::cleanBlockPTLCode($settings["ptl"]["code"]);
				
				$form_settings["form_containers"][0]["container"]["elements"] = array(array("ptl" => $settings["ptl"]));
			}
			else {
				$form_settings["form_containers"][0]["container"]["elements"] = $settings["fields"];
				
				foreach ($buttons as $button)
					$form_settings["form_containers"][] = $button;
			}
			
			$form_data = array(
				"username" => $username ? $username : $username_attribute_default_value,
				"password" => $password ? $password : $password_attribute_default_value,
			);
		
			translateProjectFormSettings($EVC, $form_settings);
			
			$form_settings["CacheHandler"] = $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler");
			
			$html .= \HtmlFormHandler::createHtmlForm($form_settings, $form_data);
		}
		
		$html .= '</div>';
		
		return $html;
	}
}
?>
