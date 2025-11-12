<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

include get_lib("org.phpframework.workflow.WorkFlowTaskHandler");

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$settings = isset($_POST["settings"]) ? $_POST["settings"] : null;
$code = null;

if (is_array($settings)) {
	MyArray::arrKeysToLowerCase($settings, true);
	
	$allowed_tasks = array("createform");
	$WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url);
	$WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH);
	$WorkFlowTaskHandler->setAllowedTaskTags($allowed_tasks);
	$WorkFlowTaskHandler->initWorkFlowTasks();
	
	$settings["form_input_data"] = "";
	$settings["form_input_data_type"] = "";
	
	$task = $WorkFlowTaskHandler->getTasksByTag("createform");
	$task = isset($task[0]) ? $task[0] : null;
	$task["properties"] = $settings;
	$task["obj"]->data = $task;
	
	//Preparing form_settings code
	$form_settings_code = $task["obj"]->printCode(null, null);
	$form_settings_code = str_replace("HtmlFormHandler::createHtmlForm(", "", $form_settings_code);
	$form_settings_code = trim($form_settings_code);
	$form_settings_code = substr($form_settings_code, 0, strrpos($form_settings_code, ","));//remove ", null);"
	
	//Preparing action_settings code
	$action_settings_code = MyArray::arrayToString(isset($settings["action_settings"]) ? $settings["action_settings"] : null);
	$action_settings_code = $action_settings_code == "''" ? "null" : $action_settings_code;
	
	$code = "array(\"form_settings\" => $form_settings_code, \n\t\"action_settings\" => $action_settings_code\n)";
}

header("Content-Type: application/json");
echo json_encode(array("code" => $code));
?>
