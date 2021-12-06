<?php
namespace CMSModule\user\list_user_activity_objects;

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
		$settings["total"] = $conditions ? \UserUtil::countUserActivityObjectsByConditions($brokers, $conditions, null) : \UserUtil::countAllUserActivityObjects($brokers);
		
		$options = array(
			"start" => \PaginationHandler::getStartValue($settings["current_page"], $settings["rows_per_page"]), 
			"limit" => $settings["rows_per_page"], 
			"sort" => null
		);
		$data = $conditions ? \UserUtil::getUserActivityObjectsByConditions($brokers, $conditions, null, $options) : \UserUtil::getAllUserActivityObjects($brokers, $options);
		
		if ($data) {
			$t = count($data);
			for ($i = 0; $i < $t; $i++)
				if ($data[$i]["time"])
					$data[$i]["time_date"] = date("Y-m-d H:i:s", $data[$i]["time"]);
		}
		$settings["data"] = $data;
		
		$settings["css_file"] = $project_common_url_prefix . 'module/user/list_user_activity_objects.css';
		$settings["class"] = "module_list_user_activity_objects";
		$settings["delete_page_url"] = "{$project_url_prefix}module/user/list_user_activity_objects/delete_user_activity_object?thread_id=#[idx][thread_id]#&user_id=#[idx][user_id]#&activity_id=#[idx][activity_id]#&object_type_id=#[idx][object_type_id]#&object_id=#[idx][object_id]#&time=#[idx][time]#";
		
		\CMSModule\user\UserModuleUtil::prepareListSettingsFields($EVC, $settings);
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/list_user_activity_objects", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
