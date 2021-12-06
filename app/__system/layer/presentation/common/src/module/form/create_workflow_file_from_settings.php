<?php
include_once get_lib("org.phpframework.workflow.WorkFlowTaskHandler");
include_once $EVC->getUtilPath("WorkFlowTasksFileHandler");

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$settings = $_POST["settings"];
$status = false;

if (isset($settings)) {
	$common_project_name = $EVC->getCommonProjectName();
	$allowed_tasks_tag = array("formitemsingle", "formitemgroup");
	
	$WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url);
	$WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH);
	$form_tasks_folder_path = $EVC->getModulesPath($common_project_name) . "form/tasks/";
	$WorkFlowTaskHandler->addTasksFolderPath($form_tasks_folder_path);
	$WorkFlowTaskHandler->setAllowedTaskTags($allowed_tasks_tag);
	$WorkFlowTaskHandler->initWorkFlowTasks();
	
	$tasks_settings = $WorkFlowTaskHandler->getLoadedTasksSettings();
	$formitemsingle = $WorkFlowTaskHandler->getTasksByTag("formitemsingle");
	$formitemgroup = $WorkFlowTaskHandler->getTasksByTag("formitemgroup");
	$all_others_task_type_id = $formitemsingle[0]["type"];
	$loop_or_group_task_type_id = $formitemgroup[0]["type"];
	
	//print_r($settings);
	$tasks = array();
	$repeated_tasks_id = array();
	
	$offset_top = 20;
	$offset_left = 20;
	prepareActionsTasks($settings["actions"], $tasks, $loop_or_group_task_type_id, $all_others_task_type_id, $repeated_tasks_id, $offset_top, $offset_left);
	
	$tasks_ids = array_keys($tasks);
	$tasks[ $tasks_ids[0] ]["start"] = 1;
	$tasks = array("tasks" => $tasks);
	
	$task_file_path = WorkFlowTasksFileHandler::getTaskFilePathByPath($workflow_paths_id, $_GET["path"], $_GET["path_extra"]);
	$status = WorkFlowTasksFileHandler::createTasksFile($task_file_path, $tasks);
}

echo $status;

function prepareActionsTasks($actions, &$tasks, $loop_or_group_task_type_id, $all_others_task_type_id, &$repeated_tasks_id, $offset_top, $offset_left) {	
	$width = 200;
	$height = 50;
	
	if ($actions)
		foreach ($actions as $i => $action) {
			$action_type = $action["action_type"];
			
			$task_id = getActionTaskId($action, $loop_or_group_task_type_id, $all_others_task_type_id, $repeated_tasks_id);
			$repeated_tasks_id[] = $task_id;
			$task_properties = $action;
			$task_exits = array();
			
			if ($action_type == "loop" || $action_type == "group") {
				$is_loop_or_group = true;
				$task_type = $loop_or_group_task_type_id;
				$task_tag = "formitemgroup";
				$task_properties_exits = array(
					"inside_group_exit" => array("color" => "#3c963c"),
					"outside_group_exit" => array("color" => "#000")
				);
				
				unset($task_properties["action_value"]["actions"]);
			}
			else {
				$is_loop_or_group = false;
				$task_type = $all_others_task_type_id;
				$task_tag = "formitemsingle";
				$task_properties_exits = array(
					"default_exit" => array("color" => "#3c963c")
				);
			}
			
			//prepare task exits
			if ($is_loop_or_group) {
				if (is_array($action["action_value"]["actions"]) && $action["action_value"]["actions"][0]) 
					$task_exits["inside_group_exit"] = array(
						"task_id" => getActionTaskId($action["action_value"]["actions"][0], $loop_or_group_task_type_id, $all_others_task_type_id, $repeated_tasks_id),
					);
				
				if ($actions[$i + 1])
					$task_exits["outside_group_exit"] = array(
						"task_id" => getActionTaskId($actions[$i + 1], $loop_or_group_task_type_id, $all_others_task_type_id, $repeated_tasks_id),
					);
			}
			else if ($actions[$i + 1])
				$task_exits["default_exit"] = array(
					"task_id" => getActionTaskId($actions[$i + 1], $loop_or_group_task_type_id, $all_others_task_type_id, $repeated_tasks_id),
				);
			
			
			$tasks[$task_id] = array(
				"label" => ($action["result_var_name"] ? '$' . $action["result_var_name"] . " = " : "") . $action_type . " (...)",
				"id" => $task_id,
				"type" => $task_type,
				"tag" => $task_tag,
				"offset_top" => $offset_top,
				"offset_left" => $offset_left,
				"width" => $width,
				"height" => $height,
				"properties" => array(
					"exits" => $task_properties_exits,
					"properties" => $task_properties,
				),
				"exits" => $task_exits,
			);
			
			//must be at the end, this is the current $task must be added before the inner tasks, so we can add the start flag to the first item in the function above.
			if ($is_loop_or_group)
				prepareActionsTasks($action["action_value"]["actions"], $tasks, $loop_or_group_task_type_id, $all_others_task_type_id, $repeated_tasks_id, $offset_top + floor($height / 2), $offset_left + $width + 50);
			
			$offset_top += $height + 50;
			$offset_left += $width + 50;
		}
}

function getActionTaskId($action, $loop_or_group_task_type_id, $all_others_task_type_id, $repeated_tasks_id) {
	$task_type = $action["action_type"] == "loop" || $action["action_type"] == "group" ? $loop_or_group_task_type_id : $all_others_task_type_id;
	$task_id = "task_" . $task_type . "_" . md5(serialize($action));
	
	while (in_array($task_id, $repeated_tasks_id))
		$task_id .= "_";
	
	return $task_id;
}
?>
