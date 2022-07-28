<?php
namespace CMSModule\user\list_user_user_types;

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
		$settings["total"] = $conditions ? \UserUtil::countUserUserTypesByConditions($brokers, $conditions, null) : \UserUtil::countAllUserUserTypes($brokers);
		
		$options = array(
			"start" => \PaginationHandler::getStartValue($settings["current_page"], $settings["rows_per_page"]), 
			"limit" => $settings["rows_per_page"], 
			"sort" => null
		);
		$settings["data"] = $conditions ? \UserUtil::getUserUserTypesByConditions($brokers, $conditions, null, $options) : \UserUtil::getAllUserUserTypes($brokers, $options);
		
		$settings["css_file"] = $project_common_url_prefix . 'module/user/list_user_user_types.css';
		$settings["class"] = "module_list_user_user_types";
		$settings["edit_page_url"] .= (strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "user_id=#[idx][user_id]#&user_type_id=#[idx][user_type_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/user/list_user_user_types/delete_user_user_type?user_id=#[idx][user_id]#&user_type_id=#[idx][user_type_id]#";
		
		\CMSModule\user\UserModuleUtil::prepareListSettingsFields($EVC, $settings);
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/list_user_user_types", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
