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

namespace CMSModule\quiz\list_questions;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("quiz/QuizUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Preparing options
		$rows_per_page = isset($settings["rows_per_page"]) && $settings["rows_per_page"] > 0 ? $settings["rows_per_page"] : null;
		$options = array("limit" => $rows_per_page, "sort" => array());
		
		//Preparing pagination
		if (!empty($settings["top_pagination_type"]) || !empty($settings["bottom_pagination_type"])) {
			include_once get_lib("org.phpframework.util.web.html.pagination.PaginationLayout");
			
			$current_page = isset($_GET["current_page"]) && is_numeric($_GET["current_page"]) ? $_GET["current_page"] : 0;
			$rows_per_page = $rows_per_page > 0 ? $rows_per_page : 50;
			$options["start"] = \PaginationHandler::getStartValue($current_page, $rows_per_page);
		}
		
		//Getting questions
		if (!empty($settings["catalog_sort_column"]))
			$options["sort"][] = array("column" => $settings["catalog_sort_column"], "order" => isset($settings["catalog_sort_order"]) ? $settings["catalog_sort_order"] : null);
		
		$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
		
		//Getting questions
		$questions_type = isset($settings["questions_type"]) ? $settings["questions_type"] : null;
		$object_type_id = isset($settings["object_type_id"]) ? $settings["object_type_id"] : null;
		$object_id = isset($settings["object_id"]) ? $settings["object_id"] : null;
		$group = isset($settings["group"]) ? $settings["group"] : null;
		$total = $questions = null;
		
		switch ($questions_type) {
			case "all":
				$total = $conditions ? \QuizUtil::countQuestionsByConditions($brokers, $conditions, null) : \QuizUtil::countAllQuestions($brokers);
				$questions = $conditions ? \QuizUtil::getQuestionsByConditions($brokers, $conditions, null, $options) : \QuizUtil::getAllQuestions($brokers, $options);
				break;
			case "parent":
				$total = \QuizUtil::countQuestionsByObject($brokers, $object_type_id, $object_id);
				$questions = \QuizUtil::getQuestionsByObject($brokers, $object_type_id, $object_id, null, $options);
				break;
			case "parent_group":
				$total = \QuizUtil::countQuestionsByObjectGroup($brokers, $object_type_id, $object_id, $group);
				$questions = \QuizUtil::getQuestionsByObjectGroup($brokers, $object_type_id, $object_id, $group, $options);
				break;
		}
		
		$html = '<div class="module_list_questions ' . (isset($settings["block_class"]) ? $settings["block_class"] : null) . '">';
		$settings["block_class"] = null;
		
		//Getting questions
		$settings["total"] = $total;
		$settings["data"] = $questions;
		$settings["css_file"] = $project_common_url_prefix . 'module/quiz/list_questions.css';
		$settings["class"] = "";
		$settings["edit_page_url"] .= (isset($settings["edit_page_url"]) && strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "question_id=#[idx][question_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/quiz/list_questions/delete_question?question_id=#[idx][question_id]#";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "quiz/list_questions", $settings);
		$html .= \CommonModuleUI::getListHtml($EVC, $settings);
		
		$html .= '</div>';
		
		return $html;
	}
}
?>
