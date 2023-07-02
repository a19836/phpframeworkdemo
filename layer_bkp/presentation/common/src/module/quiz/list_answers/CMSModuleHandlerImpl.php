<?php
namespace CMSModule\quiz\list_answers;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("quiz/QuizUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
		
		//Getting answers
		$settings["current_page"] = is_numeric($_GET["current_page"]) ? $_GET["current_page"] : null;
		$settings["rows_per_page"] = 50;
		$settings["total"] = $conditions ? \QuizUtil::countAnswersByConditions($brokers, $conditions, null) : \QuizUtil::countAllAnswers($brokers);
		
		$options = array(
			"start" => \PaginationHandler::getStartValue($settings["current_page"], $settings["rows_per_page"]), 
			"limit" => $settings["rows_per_page"], 
			"sort" => null
		);
		$settings["data"] = $conditions ? \QuizUtil::getAnswersByConditions($brokers, $conditions, null, $options) : \QuizUtil::getAllAnswers($brokers, $options);
		
		$settings["css_file"] = $project_common_url_prefix . 'module/quiz/list_answers.css';
		$settings["class"] = "module_list_answers";
		$settings["edit_page_url"] .= (strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "answer_id=#[idx][answer_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/quiz/list_answers/delete_answer?answer_id=#[idx][answer_id]#";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "quiz/list_answers", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
