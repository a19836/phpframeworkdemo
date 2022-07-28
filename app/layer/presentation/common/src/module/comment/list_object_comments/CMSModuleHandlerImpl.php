<?php
namespace CMSModule\comment\list_object_comments;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("comment/CommentUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
		
		//Getting actions
		$settings["current_page"] = is_numeric($_GET["current_page"]) ? $_GET["current_page"] : null;
		$settings["rows_per_page"] = 50;
		$settings["total"] = $conditions ? \CommentUtil::countObjectCommentsByConditions($brokers, $conditions, null) : \CommentUtil::countAllObjectComments($brokers);
		
		$options = array(
			"start" => \PaginationHandler::getStartValue($settings["current_page"], $settings["rows_per_page"]), 
			"limit" => $settings["rows_per_page"], 
			"sort" => null
		);
		$settings["data"] = $conditions ? \CommentUtil::getObjectCommentsByConditions($brokers, $conditions, null, $options) : \CommentUtil::getAllObjectComments($brokers, $options);
		
		$settings["css_file"] = $project_common_url_prefix . 'module/comment/list_object_comments.css';
		$settings["class"] = "module_list_object_comments";
		$settings["edit_page_url"] .= (strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "comment_id=#[idx][comment_id]#&object_type_id=#[idx][object_type_id]#&object_id=#[idx][object_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/comment/list_object_comments/delete_object_comment?comment_id=#[idx][comment_id]#&object_type_id=#[idx][object_type_id]#&object_id=#[idx][object_id]#";
		
		if ($settings["show_object_type_id"]) 
			\CommonModuleUtil::prepareObjectTypeIdListSettingsField($EVC, $settings);
			
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "comment/list_object_comments", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
