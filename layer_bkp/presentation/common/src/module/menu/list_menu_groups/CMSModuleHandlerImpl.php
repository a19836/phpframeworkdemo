<?php
namespace CMSModule\menu\list_menu_groups;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("menu/MenuUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
		
		//Getting menu groups
		$settings["data"] = $conditions ? \MenuUtil::getMenuGroupsByConditions($brokers, $conditions, null) : \MenuUtil::getAllMenuGroups($brokers);
		$settings["css_file"] = $project_common_url_prefix . 'module/menu/list_menu_groups.css';
		$settings["class"] = "module_list_menu_groups";
		$settings["edit_page_url"] .= (strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "group_id=#[idx][group_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/menu/list_menu_groups/delete_menu_group?group_id=#[idx][group_id]#";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "menu/list_menu_groups", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
