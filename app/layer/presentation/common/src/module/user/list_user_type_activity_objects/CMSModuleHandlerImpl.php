<?php
namespace CMSModule\user\list_user_type_activity_objects;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		include_once $EVC->getModulePath("user/UserModuleUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
		
		//Getting actions
		$settings["current_page"] = is_numeric($_GET["current_page"]) ? $_GET["current_page"] : null;
		$settings["rows_per_page"] = 50;
		$settings["total"] = $conditions ? \UserUtil::countUserTypeActivityObjectsByConditions($brokers, $conditions, null) : \UserUtil::countAllUserTypeActivityObjects($brokers);
		
		$options = array(
			"start" => \PaginationHandler::getStartValue($settings["current_page"], $settings["rows_per_page"]), 
			"limit" => $settings["rows_per_page"], 
			"sort" => null
		);
		$settings["data"] = $conditions ? \UserUtil::getUserTypeActivityObjectsByConditions($brokers, $conditions, null, $options) : \UserUtil::getAllUserTypeActivityObjects($brokers, $options);
		
		$settings["css_file"] = $project_common_url_prefix . 'module/user/list_user_type_activity_objects.css';
		$settings["class"] = "module_list_user_type_activity_objects";
		$settings["edit_page_url"] .= (strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "user_type_id=#[idx][user_type_id]#&activity_id=#[idx][activity_id]#&object_type_id=#[idx][object_type_id]#&object_id=#[idx][object_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/user/list_user_type_activity_objects/delete_user_type_activity_object?user_type_id=#[idx][user_type_id]#&activity_id=#[idx][activity_id]#&object_type_id=#[idx][object_type_id]#&object_id=#[idx][object_id]#";
		
		\CMSModule\user\UserModuleUtil::prepareListSettingsFields($EVC, $settings);
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/list_user_type_activity_objects", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
