<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("workerpool/admin/WorkerPoolAdminUtil", $common_project_name);
	
	$WorkerPoolAdminUtil = new WorkerPoolAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	if ($_POST) {
		$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
		
		$properties = array(
			"RUNNING_PROCESSES_MAXIMUM_NUMBER" => is_numeric($_POST["RUNNING_PROCESSES_MAXIMUM_NUMBER"]) ? $_POST["RUNNING_PROCESSES_MAXIMUM_NUMBER"] : 0,
			"PROCESS_MAXIMUM_EXECUTION_TIME" => is_numeric($_POST["PROCESS_MAXIMUM_EXECUTION_TIME"]) ? $_POST["PROCESS_MAXIMUM_EXECUTION_TIME"] : 0,
			"PROCESS_MAXIMUM_EXPIRATION_EXECUTION_TIME" => is_numeric($_POST["PROCESS_MAXIMUM_EXPIRATION_EXECUTION_TIME"]) ? $_POST["PROCESS_MAXIMUM_EXPIRATION_EXECUTION_TIME"] : 0,
			"WORKERS_MAXIMUM_NUMBER_PER_PROCESS" => is_numeric($_POST["WORKERS_MAXIMUM_NUMBER_PER_PROCESS"]) ? $_POST["WORKERS_MAXIMUM_NUMBER_PER_PROCESS"] : 0,
			"WORKER_FAILED_ATTEMPTS_MAXIMUM_NUMBER" => is_numeric($_POST["WORKER_FAILED_ATTEMPTS_MAXIMUM_NUMBER"]) ? $_POST["WORKER_FAILED_ATTEMPTS_MAXIMUM_NUMBER"] : 0,
			"WORKER_MAXIMUM_EXPIRATION_EXECUTION_TIME" => is_numeric($_POST["WORKER_MAXIMUM_EXPIRATION_EXECUTION_TIME"]) ? $_POST["WORKER_MAXIMUM_EXPIRATION_EXECUTION_TIME"] : 0,
		);
		
		if ($CommonModuleAdminUtil->setModuleSettings($PEVC, "workerpool/WorkerPoolSettings", $properties)) {
			$status_message = "Settings saved successfully";
		}
		else {
			$error_message = "Error trying to save new settings. Please try again...";
		}
	}
	
	$data = $CommonModuleAdminUtil->getModuleSettings($PEVC, "workerpool/WorkerPoolSettings");
		
	//Preparing HTML
	$form_settings = array(
		"title" => "Edit Settings",
		"fields" => array(
			"RUNNING_PROCESSES_MAXIMUM_NUMBER" => array("type" => "text", "next_html" => '<div class="info">Only works if not windows os.</div>'),
			"PROCESS_MAXIMUM_EXECUTION_TIME" => array("type" => "text", "next_html" => '<div class="info">in secs. 3600secs = 60secs * 60min = 1h. Only works if not windows os.</div>'),
			"PROCESS_MAXIMUM_EXPIRATION_EXECUTION_TIME" => array("type" => "text", "next_html" => '<div class="info">in secs. 604800 = 3600secs * 24h * 7days = 1 week. Note that this variable only make sense if the workers get executed in less time than this value, otherwise the process will be killed during the execution of a worker. Additionally this value must be bigger than $WORKER_MAXIMUM_EXPIRATION_EXECUTION_TIME. (Only works if not windows os)</div>'),
			"WORKERS_MAXIMUM_NUMBER_PER_PROCESS" => array("type" => "text"),
			"WORKER_FAILED_ATTEMPTS_MAXIMUM_NUMBER" => array("type" => "text"),
			"WORKER_MAXIMUM_EXPIRATION_EXECUTION_TIME" => array("type" => "text", "next_html" => '<div class="info">in secs. 604800 = 3600secs * 24h * 7days = 1 week. </div>'),
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_settings.css" type="text/css" charset="utf-8" />';
	$menu_settings = $WorkerPoolAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
