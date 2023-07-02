<?php
namespace CMSModule\attachment\list_attachments;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("attachment/AttachmentUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
		
		//Getting actions
		$settings["current_page"] = is_numeric($_GET["current_page"]) ? $_GET["current_page"] : null;
		$settings["rows_per_page"] = 50;
		$settings["total"] = $conditions ? \AttachmentUtil::countAttachmentsByConditions($brokers, $conditions, null) : \AttachmentUtil::countAllAttachments($brokers);
		
		$options = array(
			"start" => \PaginationHandler::getStartValue($settings["current_page"], $settings["rows_per_page"]), 
			"limit" => $settings["rows_per_page"], 
			"sort" => null
		);
		$settings["data"] = $conditions ? \AttachmentUtil::getAttachmentsByConditions($brokers, $conditions, null, $options) : \AttachmentUtil::getAllAttachments($brokers, $options);
		
		$settings["css_file"] = $project_common_url_prefix . 'module/attachment/list_attachments.css';
		$settings["class"] = "module_list_attachments";
		$settings["edit_page_url"] .= (strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "attachment_id=#[idx][attachment_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/attachment/list_attachments/delete_attachment?attachment_id=#[idx][attachment_id]#";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "attachment/list_attachments", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
