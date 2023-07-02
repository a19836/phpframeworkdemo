<?php
namespace CMSModule\user\list_user_sessions;

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
		$settings["total"] = $conditions ? \UserUtil::countUserSessionsByConditions($brokers, $conditions, null) : \UserUtil::countAllUserSessions($brokers);
		
		$options = array(
			"start" => \PaginationHandler::getStartValue($settings["current_page"], $settings["rows_per_page"]), 
			"limit" => $settings["rows_per_page"], 
			"sort" => null
		);
		$data = $conditions ? \UserUtil::getUserSessionsByConditions($brokers, $conditions, null, $options) : \UserUtil::getAllUserSessions($brokers, $options);
		
		if ($data) {
			$t = count($data);
			for ($i = 0; $i < $t; $i++) {
				if ($data[$i]["login_time"])
					$data[$i]["login_time"] = date("Y-m-d H:i:s", $data[$i]["login_time"]);
				
				if ($data[$i]["logout_time"])
					$data[$i]["logout_time"] = date("Y-m-d H:i:s", $data[$i]["logout_time"]);
				
				if ($data[$i]["failed_login_time"])
					$data[$i]["failed_login_time"] = date("Y-m-d H:i:s", $data[$i]["failed_login_time"]);
			}
		}
		
		$settings["data"] = $data;
		
		$settings["css_file"] = $project_common_url_prefix . 'module/user/list_user_sessions.css';
		$settings["class"] = "module_list_user_sessions";
		$settings["delete_page_url"] = "{$project_url_prefix}module/user/list_user_sessions/delete_user_session?username=#[idx][username]#";
		
		\CMSModule\user\UserModuleUtil::prepareListSettingsFields($EVC, $settings);
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/list_user_sessions", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
