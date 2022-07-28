<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("quiz/admin/QuizAdminUtil", $common_project_name);
	
	$QuizAdminUtil = new QuizAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$pks = "question_id=#[\$idx][question_id]#";
	
	$total = QuizUtil::countAllQuestions($brokers, true);
	$data = QuizUtil::getAllQuestions($brokers, $options, true);
	
	$list_settings = array(
		"title" => "Questions List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_question") . $pks,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_question") . $pks,
		"other_urls" => array($CommonModuleAdminUtil->getAdminFileUrl("list_answers") . $pks),
		"fields" => array("question_id", "title", "description", "published", "created_date", "modified_date"),
		"total" => $total,
		"data" => $data,
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_questions.css" type="text/css" charset="utf-8" />';
	$menu_settings = $QuizAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
