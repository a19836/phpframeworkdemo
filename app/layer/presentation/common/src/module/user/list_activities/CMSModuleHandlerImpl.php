<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

namespace CMSModule\user\list_activities;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
		
		//Getting actions
		$settings["data"] = $conditions ? \UserUtil::getActivitiesByConditions($brokers, $conditions, null) : \UserUtil::getAllActivities($brokers);
		$settings["css_file"] = $project_common_url_prefix . 'module/user/list_activities.css';
		$settings["class"] = "module_list_activities";
		$settings["edit_page_url"] .= (isset($settings["edit_page_url"]) && strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "activity_id=#[idx][activity_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/user/list_activities/delete_activity?activity_id=#[idx][activity_id]#";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/list_activities", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
