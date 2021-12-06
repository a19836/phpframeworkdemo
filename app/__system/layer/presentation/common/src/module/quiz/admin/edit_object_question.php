<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("quiz/admin/QuizAdminUtil", $common_project_name);
	
	$QuizAdminUtil = new QuizAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$question_id = $_GET["question_id"];
	$object_type_id = $_GET["object_type_id"];
	$object_id = $_GET["object_id"];
	
	if ($_POST) {
		if ($_POST["add"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"question_id" => $_POST["question_id"],
				"object_type_id" => $_POST["object_type_id"],
				"object_id" => $_POST["object_id"],
				"group" => $_POST["group"],
			);
			$status = QuizUtil::insertObjectQuestion($brokers, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = QuizUtil::deleteObjectQuestion($brokers, $question_id, $object_type_id, $object_id);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "Object Question ${action}d successfully!";
				
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_object_question") . "question_id=${data['question_id']}&object_type_id=${data['object_type_id']}&object_id=${data['object_id']}";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this object question. Please try again...";
			}
		}
	}
	
	$data = QuizUtil::getObjectQuestionsByConditions($brokers, array("question_id" => $question_id, "object_type_id" => $object_type_id, "object_id" => $object_id), null, null, true);
	$data = $data[0];
	
	$object_type_options = $CommonModuleAdminUtil->getObjectTypeOptions($brokers, $data);
	$available_object_types = $CommonModuleAdminUtil->getAvailableObjectTypes($brokers);
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Object Question" : "Add Object Question",
		"fields" => array(
			"question_id" => $data ? "label" : "text",
			"object_type_id" => $data ? array("type" => "label", "available_values" => $available_object_types) : array("type" => "select", "options" => $object_type_options),
			"object_id" => $data ? "label" : "text",
			"group" => $data ? "label" : "text",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_object_question.css" type="text/css" charset="utf-8" />';
	$menu_settings = $QuizAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
