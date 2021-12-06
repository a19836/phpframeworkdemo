<?php
namespace CMSModule\quiz\list_questions;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("quiz/QuizUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Preparing options
		$rows_per_page = $settings["rows_per_page"] > 0 ? $settings["rows_per_page"] : null;
		$options = array("limit" => $rows_per_page, "sort" => array());
		
		//Preparing pagination
		if ($settings["top_pagination_type"] || $settings["bottom_pagination_type"]) {
			include_once get_lib("org.phpframework.util.web.html.pagination.PaginationLayout");
			
			$current_page = is_numeric($_GET["current_page"]) ? $_GET["current_page"] : 0;
			$rows_per_page = $rows_per_page > 0 ? $rows_per_page : 50;
			$options["start"] = \PaginationHandler::getStartValue($current_page, $rows_per_page);
		}
		
		//Getting questions
		if ($settings["catalog_sort_column"])
			$options["sort"][] = array("column" => $settings["catalog_sort_column"], "order" => $settings["catalog_sort_order"]);
		
		$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
		
		//Getting questions
		switch ($settings["questions_type"]) {
			case "all":
				$total = $conditions ? \QuizUtil::countQuestionsByConditions($brokers, $conditions, null) : \QuizUtil::countAllQuestions($brokers);
				$questions = $conditions ? \QuizUtil::getQuestionsByConditions($brokers, $conditions, null, $options) : \QuizUtil::getAllQuestions($brokers, $options);
				break;
			case "parent":
				$total = \QuizUtil::countQuestionsByObject($brokers, $settings["object_type_id"], $settings["object_id"], $conditions, null);
				$questions = \QuizUtil::getQuestionsByObject($brokers, $settings["object_type_id"], $settings["object_id"], $conditions, null, $options);
				break;
			case "parent_group":
				$total = \QuizUtil::countQuestionsByObjectGroup($brokers, $settings["object_type_id"], $settings["object_id"], $settings["group"], $conditions, null);
				$questions = \QuizUtil::getQuestionsByObjectGroup($brokers, $settings["object_type_id"], $settings["object_id"], $settings["group"], $conditions, null, $options);
				break;
		}
		
		$html = '<div class="module_list_questions ' . ($settings["block_class"]) . '">';
		$settings["block_class"] = null;
		
		//Getting questions
		$settings["total"] = $total;
		$settings["data"] = $questions;
		$settings["css_file"] = $project_common_url_prefix . 'module/quiz/list_questions.css';
		$settings["class"] = "";
		$settings["edit_page_url"] .= (strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "question_id=#[idx][question_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/quiz/list_questions/delete_question?question_id=#[idx][question_id]#";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "quiz/list_questions", $settings);
		$html .= \CommonModuleUI::getListHtml($EVC, $settings);
		
		$html .= '</div>';
		
		return $html;
	}
}
?>
