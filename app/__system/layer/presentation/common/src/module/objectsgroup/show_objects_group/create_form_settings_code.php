<?php
include get_lib("org.phpframework.workflow.WorkFlowTaskHandler");

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$settings = $_POST["settings"];

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
	$task = $task[0];
	$task["properties"] = $settings;
	$task["obj"]->data = $task;
	
	//Preparing form_settings code
	$form_settings_code = $task["obj"]->printCode(null, null);
	$form_settings_code = str_replace("HtmlFormHandler::createHtmlForm(", "", $form_settings_code);
	$form_settings_code = trim($form_settings_code);
	$form_settings_code = substr($form_settings_code, 0, strrpos($form_settings_code, ","));//remove ", null);"
	
	//Preparing action_settings code
	$action_settings_code = MyArray::arrayToString($settings["action_settings"]);
	$action_settings_code = $action_settings_code == "''" ? "null" : $action_settings_code;
	
	$code = "array(\"form_settings\" => $form_settings_code, \n\t\"action_settings\" => $action_settings_code\n)";
}

header("Content-Type: application/json");
echo json_encode(array("code" => $code));
?>
