<?php
namespace CMSModule\user\list_user_environments;

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
		$settings["total"] = $conditions ? \UserUtil::countUserEnvironmentsByConditions($brokers, $conditions, null) : \UserUtil::countAllUserEnvironments($brokers);
		
		$options = array(
			"start" => \PaginationHandler::getStartValue($settings["current_page"], $settings["rows_per_page"]), 
			"limit" => $settings["rows_per_page"], 
			"sort" => null
		);
		$settings["data"] = $conditions ? \UserUtil::getUserEnvironmentsByConditions($brokers, $conditions, null, $options) : \UserUtil::getAllUserEnvironments($brokers, $options);
		
		$settings["css_file"] = $project_common_url_prefix . 'module/user/list_user_environments.css';
		$settings["class"] = "module_list_user_environments";
		$settings["edit_page_url"] .= (strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "user_id=#[idx][user_id]#&environment_id=#[idx][environment_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/user/list_user_environments/delete_user_environment?user_id=#[idx][user_id]#&environment_id=#[idx][environment_id]#";
		
		\CMSModule\user\UserModuleUtil::prepareListSettingsFields($EVC, $settings);
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/list_user_environments", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
