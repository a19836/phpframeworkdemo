<?php
include_once $EVC->getUtilPath("WorkFlowTasksFileHandler");
include_once get_lib("org.phpframework.workflow.WorkFlowTaskHandler");

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();

$WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url);
$WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH);
$form_tasks_folder_path = $EVC->getModulesPath($common_project_name) . "form/tasks/";
$WorkFlowTaskHandler->addTasksFolderPath($form_tasks_folder_path);
$WorkFlowTaskHandler->initWorkFlowTasks();

$task_file_path = WorkFlowTasksFileHandler::getTaskFilePathByPath($workflow_paths_id, $_GET["path"], $_GET["path_extra"]);

if ($task_file_path && file_exists($task_file_path)) {
	$loops = $WorkFlowTaskHandler->getLoopsTasksFromFile($task_file_path);
	$res = $WorkFlowTaskHandler->parseFile($task_file_path, $loops, array("return_obj" => true));
	
	if (isset($res)) {
		$tasks = convertResultsIntoTasks($res);
		$actions = convertTasksIntoSettingsActions($tasks);
		$settings = array("actions" => $actions);
		$obj = array("settings" => $settings);
		
		if (!empty($loops)) {
			$t = count($loops);
			for ($i = 0; $i < $t; $i++) {
				$loop = $loops[$i];
				$is_loop_allowed = $loop[2];
			
				if (!$is_loop_allowed)
					$obj["error"]["infinit_loop"][] = array("source_task_id" => $loop[0], "target_task_id" => $loop[1]);
			}
		}
		
		echo json_encode($obj);
	}
}

function convertResultsIntoTasks($items) {
	$tasks = array();
	
	foreach ($items as $item) {
		$task = $item["code"];
		
		if (isset($task["inner"]))
			$task["inner"] = convertResultsIntoTasks($task["inner"]);
		
		if (isset($task["next"]))
			$task["next"] = convertResultsIntoTasks($task["next"]);
		
		if (!$task["inner"])
			unset($task["inner"]);
		
		if (!$task["next"])
			unset($task["next"]);
		
		$tasks[] = $task;
	}
	
	return $tasks;
}

function convertTasksIntoSettingsActions($tasks) {
	$actions = array();
	
	foreach ($tasks as $task) {
		$item = $task["properties"];
		
		if (isset($task["inner"]))
			$item["action_value"]["actions"] = convertTasksIntoSettingsActions($task["inner"]);
		
		$actions[] = $item;
		
		if (isset($task["next"])) {
			$actions_aux = convertTasksIntoSettingsActions($task["next"]);
			$actions = array_merge($actions, $actions_aux);
		}
	}
	
	return $actions;
}
?>
