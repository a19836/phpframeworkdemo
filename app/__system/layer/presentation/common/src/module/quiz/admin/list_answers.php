<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("quiz/admin/QuizAdminUtil", $common_project_name);
	
	$QuizAdminUtil = new QuizAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$question_id = $_GET["question_id"];
	
	if ($question_id) {
		$total = QuizUtil::countAnswersByConditions($brokers, array("question_id" => $question_id), null, true);
		$data = QuizUtil::getAnswersByConditions($brokers, array("question_id" => $question_id), null, $options, true);
	}
	else {
		$total = QuizUtil::countAllAnswers($brokers, true);
		$data = QuizUtil::getAllAnswers($brokers, $options, true);
	}
	
	$pks = "answer_id=#[\$idx][answer_id]#";
	
	$list_settings = array(
		"title" => "Answers List" . ($answer_id ? " for answer: '" . $answer_id . "'" : ""),
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_answer") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_answer") . $pks,
		"other_urls" => array($CommonModuleAdminUtil->getAdminFileUrl("list_user_answers") . $pks),
		"fields" => array(
			"question_id", 
			"answer_id",  
			"title",   
			"description", 
			"created_date", 
			"modified_date"
		),
		"total" => $total,
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_answers.css" type="text/css" charset="utf-8" />';
	$menu_settings = $QuizAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
