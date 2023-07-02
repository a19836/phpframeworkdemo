<?php
namespace CMSModule\quiz\list_user_answers;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("quiz/QuizUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
		
		//Getting user answers
		$settings["current_page"] = is_numeric($_GET["current_page"]) ? $_GET["current_page"] : null;
		$settings["rows_per_page"] = 50;
		$settings["total"] = $conditions ? \QuizUtil::countUserAnswersByConditions($brokers, $conditions, null) : \QuizUtil::countAllUserAnswers($brokers);
		
		$options = array(
			"start" => \PaginationHandler::getStartValue($settings["current_page"], $settings["rows_per_page"]), 
			"limit" => $settings["rows_per_page"], 
			"sort" => null
		);
		$settings["data"] = $conditions ? \QuizUtil::getUserAnswersByConditions($brokers, $conditions, null, $options) : \QuizUtil::getAllUserAnswers($brokers, $options);
		
		$settings["css_file"] = $project_common_url_prefix . 'module/quiz/list_user_answers.css';
		$settings["class"] = "module_list_user_answers";
		$settings["edit_page_url"] .= (strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "user_id=#[idx][user_id]#&answer_id=#[idx][answer_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/quiz/list_user_answers/delete_user_answer?user_id=#[idx][user_id]#&answer_id=#[idx][answer_id]#";
		
		if ($settings["show_user_id"]) 
			\CommonModuleUtil::prepareUserIdListSettingsField($EVC, $settings);
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "quiz/list_user_answers", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
