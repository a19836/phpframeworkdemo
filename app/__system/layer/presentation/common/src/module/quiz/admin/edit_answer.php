<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("quiz/admin/QuizAdminUtil", $common_project_name);
	
	$QuizAdminUtil = new QuizAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$answer_id = $_GET["answer_id"];
	
	if ($_POST) {
		if ($_POST["add"] || $_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"answer_id" => $answer_id,
				"question_id" => $_POST["question_id"],
				"title" => $_POST["title"],
				"description" => $_POST["description"],
			);
			$status = $_POST["add"] ? QuizUtil::insertAnswer($brokers, $data) : QuizUtil::updateAnswer($brokers, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = QuizUtil::deleteUserAnswersByAnswerId($brokers, $answer_id) && QuizUtil::deleteAnswer($brokers, $answer_id);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "Answer ${action}d successfully!";
				
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_answer") . "answer_id=$status";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this answer. Please try again...";
			}
		}
	}
	
	$data = QuizUtil::getAnswersByConditions($brokers, array("answer_id" => $answer_id), null, false, true);
	$data = $data[0];
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Answer '$answer_id'" : "Add Answer",
		"fields" => array(
			"question_id" => "text",
			"title" => "text",
			"description" => "textarea",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_answer.css" type="text/css" charset="utf-8" />';
	$menu_settings = $QuizAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
