<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("quiz/admin/QuizAdminUtil", $common_project_name);
	
	$QuizAdminUtil = new QuizAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$question_id = $_GET["question_id"];
	
	if ($_POST) {
		if ($_POST["add"] || $_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"question_id" => $question_id,
				"title" => $_POST["title"],
				"description" => $_POST["description"],
				"published" => is_numeric($_POST["published"]) ? $_POST["published"] : 0,
			);
			$status = $_POST["add"] ? QuizUtil::insertQuestion($brokers, $data) : QuizUtil::updateQuestion($brokers, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = QuizUtil::deleteUserAnswersByQuestionIds($brokers, $question_id) && QuizUtil::deleteAnswersByQuestionId($brokers, $question_id) && QuizUtil::deleteQuestion($brokers, $question_id);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "Question ${action}d successfully!";
				
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_question") . "question_id=$status";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this question. Please try again...";
			}
		}
	}
	
	$data = QuizUtil::getQuestionsByConditions($brokers, array("question_id" => $question_id), null, false, true);
	$data = $data[0];
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Question '$question_id'" : "Add Question",
		"fields" => array(
			"title" => "text",
			"description" => "textarea",
			"published" => array(
				"type" => "checkbox",
				"options" => array(array("value" => 1))
			),
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_question.css" type="text/css" charset="utf-8" />';
	$menu_settings = $QuizAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
