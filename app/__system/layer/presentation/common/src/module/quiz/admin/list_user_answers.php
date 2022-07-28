<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("quiz/admin/QuizAdminUtil", $common_project_name);
	
	$QuizAdminUtil = new QuizAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$answer_id = $_GET["answer_id"];
	
	if ($answer_id) {
		$total = QuizUtil::countUserAnswersByConditions($brokers, array("answer_id" => $answer_id), null, true);
		$data = QuizUtil::getUserAnswersByConditions($brokers, array("answer_id" => $answer_id), null, $options, true);
	}
	else {
		$total = QuizUtil::countAllUserAnswers($brokers, true);
		$data = QuizUtil::getAllUserAnswers($brokers, $options, true);
	}
	
	$available_users = $CommonModuleAdminUtil->getSelectedUsers($brokers, $data);
	
	$pks = "user_id=#[\$idx][user_id]#&answer_id=#[\$idx][answer_id]#";
	
	$list_settings = array(
		"title" => "User Answers List" . ($answer_id ? " for answer: '" . $answer_id . "'" : ""),
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_user_answer") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_user_answer") . $pks,
		"fields" => array(
			"user_id" => array("available_values" => $available_users), 
			"answer_id", 
			"created_date", 
			"modified_date"
		),
		"total" => $total,
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_user_answers.css" type="text/css" charset="utf-8" />';
	$menu_settings = $QuizAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
