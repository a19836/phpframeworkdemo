<?php
namespace CMSModule\user\list_user_types;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
		
		//Getting actions
		$settings["data"] = $conditions ? \UserUtil::getUserTypesByConditions($brokers, $conditions, null) : \UserUtil::getAllUserTypes($brokers);
		$settings["css_file"] = $project_common_url_prefix . 'module/user/list_user_types.css';
		$settings["class"] = "module_list_user_types";
		$settings["edit_page_url"] .= (strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "user_type_id=#[idx][user_type_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/user/list_user_types/delete_user_type?user_type_id=#[idx][user_type_id]#";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "user/list_user_types", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
