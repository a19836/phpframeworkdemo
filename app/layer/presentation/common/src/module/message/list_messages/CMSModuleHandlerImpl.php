<?php
namespace CMSModule\message\list_messages;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("message/MessageUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
		
		//Getting actions
		$settings["current_page"] = is_numeric($_GET["current_page"]) ? $_GET["current_page"] : null;
		$settings["rows_per_page"] = 50;
		$settings["total"] = $conditions ? \MessageUtil::countMessagesByConditions($brokers, $conditions, null) : \MessageUtil::countAllMessages($brokers);
		
		$options = array(
			"start" => \PaginationHandler::getStartValue($settings["current_page"], $settings["rows_per_page"]), 
			"limit" => $settings["rows_per_page"], 
			"sort" => null
		);
		$settings["data"] = $conditions ? \MessageUtil::getMessagesByConditions($brokers, $conditions, null, $options) : \MessageUtil::getAllMessages($brokers, $options);
		
		$settings["css_file"] = $project_common_url_prefix . 'module/message/list_messages.css';
		$settings["class"] = "module_list_messages";
		$settings["edit_page_url"] .= (strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "message_id=#[idx][message_id]#&from_user_id=#[idx][from_user_id]#&to_user_id=#[idx][to_user_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/message/list_messages/delete_message?message_id=#[idx][message_id]#&from_user_id=#[idx][from_user_id]#&to_user_id=#[idx][to_user_id]#";
		
		if ($settings["show_from_user_id"]) 
			\CommonModuleUtil::prepareUserIdListSettingsField($EVC, $settings, "from_user_id");
		
		if ($settings["show_to_user_id"]) 
			\CommonModuleUtil::prepareUserIdListSettingsField($EVC, $settings, "to_user_id");
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "message/list_messages", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
