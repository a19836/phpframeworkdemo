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

namespace CMSModule\user\logout;

include_once get_lib("org.phpframework.util.web.html.HtmlFormHandler");
include_once get_lib("org.phpframework.util.HashCode");

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("common/ObjectToObjectValidationHandler", $common_project_name);
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once get_lib("lib.vendor.auth0.autoload");
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//remove session cookie
		$session_id_var_name = \UserUtil::getConstantVariable("USER_SESSION_ID_VARIABLE_NAME");
		$session_id = isset($_COOKIE[$session_id_var_name]) ? $_COOKIE[$session_id_var_name] : null;
		unset($_COOKIE[$session_id_var_name]);
		\CookieHandler::setSafeCookie($session_id_var_name, "", time() - 3600, "/");
		
		//remove captcha cookie
		$show_captcha_var_name = \UserUtil::getConstantVariable("USER_LOGIN_SHOW_CAPTCHA_VARIABLE_NAME");
		unset($_COOKIE[$show_captcha_var_name]);
		\CookieHandler::setSafeCookie($show_captcha_var_name, "", time() - 3600, "/");
		
		//remove session control cookie
		$user_session_control_var_name = \UserUtil::getConstantVariable("USER_SESSION_CONTROL_VARIABLE_NAME");
		unset($_COOKIE[$user_session_control_var_name]);
		\CookieHandler::setSafeCookie($user_session_control_var_name, "", time() - 3600, "/");
		
		$status = true;
		
		//logout from DB
		if ($session_id)
			$status = \UserUtil::logout($brokers, $session_id);
		
		//logout from auth0
		if (!empty($settings['domain']) && !empty($settings['client_id'])) {
			$current_page_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . (isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : null) . (isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : null);
			
			try {
				$auth0 = new \Auth0\SDK\Auth0(array(
					'domain' => isset($settings['domain']) ? $settings['domain'] : null,
					'client_id' => isset($settings['client_id']) ? $settings['client_id'] : null,
					'redirect_uri' => $current_page_url,
				));
				
				$auth0->logout();
			}
			catch (\Exception $e) {
				$status = false;
				$settings["non_validation_message"] .= (!empty($settings["non_validation_message"]) ? "\n\n" : "") . $e->getMessage();
			}
		}
		
		//if logout is unsuccessfully and message is empty, sets default error if native action
		if (empty($status) && empty($settings["non_validation_action"])) { //it means is the native action which is showing the error, so we must change this to show_message.
			$settings["non_validation_action"] = "show_message";
			
			if (empty($settings["non_validation_message"]))
				$settings["non_validation_message"] = "Error trying to logout...";
		}
		else if (!empty($settings["message"]) || !empty($settings["block_class"])) {
			$settings["validation_action"] = "show_message";
			$settings["validation_message"] = isset($settings["message"]) ? $settings["message"] : null;
			$settings["validation_class"] = isset($settings["block_class"]) ? $settings["block_class"] : null;
		}
		
		//execute action
		$html = \ObjectToObjectValidationHandler::validate($EVC, $status, $settings);
		
		return $html;
	}
}
?>
