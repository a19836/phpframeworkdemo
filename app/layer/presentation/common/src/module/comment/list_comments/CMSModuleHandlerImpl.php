<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

namespace CMSModule\comment\list_comments;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("comment/CommentUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
		
		//Getting actions
		$settings["current_page"] = isset($_GET["current_page"]) && is_numeric($_GET["current_page"]) ? $_GET["current_page"] : null;
		$settings["rows_per_page"] = 50;
		$settings["total"] = $conditions ? \CommentUtil::countCommentsByConditions($brokers, $conditions, null) : \CommentUtil::countAllComments($brokers);
		
		$options = array(
			"start" => \PaginationHandler::getStartValue($settings["current_page"], $settings["rows_per_page"]), 
			"limit" => $settings["rows_per_page"], 
			"sort" => null
		);
		$settings["data"] = $conditions ? \CommentUtil::getCommentsByConditions($brokers, $conditions, null, $options) : \CommentUtil::getAllComments($brokers, $options);
		
		$settings["css_file"] = $project_common_url_prefix . 'module/comment/list_comments.css';
		$settings["class"] = "module_list_comments";
		$settings["edit_page_url"] .= (isset($settings["edit_page_url"]) && strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "comment_id=#[idx][comment_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/comment/list_comments/delete_comment?comment_id=#[idx][comment_id]#";
		
		if (!empty($settings["show_user_id"])) 
			\CommonModuleUtil::prepareUserIdListSettingsField($EVC, $settings);
			
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "comment/list_comments", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
