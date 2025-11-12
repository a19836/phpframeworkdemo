<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

namespace CMSModule\user\validate_user_activity;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		
		include $EVC->getModulePath("user/include_user_session_activities_handler", $EVC->getCommonProjectName());
		
		$activity_id = isset($settings["activity_id"]) ? $settings["activity_id"] : null;
		$object_type_id = isset($settings["object_type_id"]) ? $settings["object_type_id"] : null;
		$object_id = isset($settings["object_id"]) ? $settings["object_id"] : null;
		
		return $GLOBALS["UserSessionActivitiesHandler"]->validateUserActivity($activity_id, $object_type_id, $object_id, $settings);
	}
}
?>
