<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

namespace CMSModule\user\manage_user_type_activity_objects;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("user/ManageUserTypeActivityObjectsUtil", $common_project_name);
		
		//Including Stylesheet
		$html = '';
		if (empty($settings["style_type"])) {
			$html .= '<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/user/manage_user_type_activity_objects.css" type="text/css" charset="utf-8" />';
		}
		
		$html .= '<script type="text/javascript" src="' . $project_common_url_prefix . 'module/user/manage_user_type_activity_objects.js"></script>';
		$html .= !empty($settings["css"]) ? '<style>' . $settings["css"] . '</style>' : '';
		$html .= !empty($settings["js"]) ? '<script type="text/javascript">' . $settings["js"] . '</script>' : '';
		
		//Execute Action and Get Html
		$html .= \ManageUserTypeActivityObjectsUtil::getHtml($EVC, $settings);
		
		return $html;
	}
}
?>
