<?php
namespace CMSModule\action\list_actions;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("action/ActionUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
		
		//Getting actions
		$settings["data"] = $conditions ? \ActionUtil::getActionsByConditions($brokers, $conditions, null) : \ActionUtil::getAllActions($brokers);
		$settings["css_file"] = $project_common_url_prefix . 'module/action/list_actions.css';
		$settings["class"] = "module_list_actions";
		$settings["edit_page_url"] .= (strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "action_id=#[idx][action_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/action/list_actions/delete_action?action_id=#[idx][action_id]#";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "action/list_actions", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
