<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("quiz/admin/QuizAdminUtil", $common_project_name);
	
	$QuizAdminUtil = new QuizAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$answer_id = isset($_GET["answer_id"]) ? $_GET["answer_id"] : null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["add"]) || !empty($_POST["save"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"answer_id" => $answer_id,
				"question_id" => isset($_POST["question_id"]) ? $_POST["question_id"] : null,
				"title" => isset($_POST["title"]) ? $_POST["title"] : null,
				"description" => isset($_POST["description"]) ? $_POST["description"] : null,
			);
			$status = !empty($_POST["add"]) ? QuizUtil::insertAnswer($brokers, $data) : QuizUtil::updateAnswer($brokers, $data);
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = QuizUtil::deleteUserAnswersByAnswerId($brokers, $answer_id) && QuizUtil::deleteAnswer($brokers, $answer_id);
		}
		
		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "Answer ${action}d successfully!";
				
				if (!empty($_POST["add"])) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_answer") . "answer_id=$status";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
			}
			else {
				$error_message = "There was an error trying to $action this answer. Please try again...";
			}
		}
	}
	
	$data = QuizUtil::getAnswersByConditions($brokers, array("answer_id" => $answer_id), null, false, true);
	$data = isset($data[0]) ? $data[0] : null;
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit Answer '$answer_id'" : "Add Answer",
		"fields" => array(
			"question_id" => "text",
			"title" => "text",
			"description" => "textarea",
		),
		"data" => $data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_answer.css" type="text/css" charset="utf-8" />';
	$menu_settings = $QuizAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
