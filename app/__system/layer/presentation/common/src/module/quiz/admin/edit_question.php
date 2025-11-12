<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("quiz/admin/QuizAdminUtil", $common_project_name);
	
	$QuizAdminUtil = new QuizAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$question_id = isset($_GET["question_id"]) ? $_GET["question_id"] : null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["add"]) || !empty($_POST["save"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"question_id" => $question_id,
				"title" => isset($_POST["title"]) ? $_POST["title"] : null,
				"description" => isset($_POST["description"]) ? $_POST["description"] : null,
				"published" => isset($_POST["published"]) && is_numeric($_POST["published"]) ? $_POST["published"] : 0,
			);
			$status = !empty($_POST["add"]) ? QuizUtil::insertQuestion($brokers, $data) : QuizUtil::updateQuestion($brokers, $data);
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = QuizUtil::deleteUserAnswersByQuestionIds($brokers, $question_id) && QuizUtil::deleteAnswersByQuestionId($brokers, $question_id) && QuizUtil::deleteQuestion($brokers, $question_id);
		}
		
		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "Question ${action}d successfully!";
				
				if (!empty($_POST["add"])) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_question") . "question_id=$status";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
				}
			}
			else {
				$error_message = "There was an error trying to $action this question. Please try again...";
			}
		}
	}
	
	$data = QuizUtil::getQuestionsByConditions($brokers, array("question_id" => $question_id), null, false, true);
	$data = isset($data[0]) ? $data[0] : null;
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit Question '$question_id'" : "Add Question",
		"fields" => array(
			"title" => "text",
			"description" => "textarea",
			"published" => array(
				"type" => "checkbox",
				"options" => array(array("value" => 1))
			),
		),
		"data" => $data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_question.css" type="text/css" charset="utf-8" />';
	$menu_settings = $QuizAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
