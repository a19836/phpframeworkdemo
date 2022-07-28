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
		$total = QuizUtil::countObjectQuestionsByQuestionId($brokers, $question_id, true);
		$data = QuizUtil::getObjectQuestionsByQuestionId($brokers, $question_id, $options, true);
	}
	else {
		$total = QuizUtil::countAllObjectQuestions($brokers, true);
		$data = QuizUtil::getAllObjectQuestions($brokers, $options, true);
	}
	
	$available_object_types = $CommonModuleAdminUtil->getAvailableObjectTypes($brokers);
	
	$pks = "question_id=#[\$idx][question_id]#&object_type_id=#[\$idx][object_type_id]#&object_id=#[\$idx][object_id]#";
	
	$list_settings = array(
		"title" => "Object Questions List" . ($question_id ? " for question: '" . $question_id . "'" : ""),
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_object_question") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_object_question") . $pks,
		"fields" => array(
			"question_id", 
			"object_type_id" => array("available_values" => $available_object_types), 
			"object_id", 
			"group", 
			"order", 
			"created_date", 
			"modified_date"
		),
		"total" => $total,
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_object_questions.css" type="text/css" charset="utf-8" />';
	$menu_settings = $QuizAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
