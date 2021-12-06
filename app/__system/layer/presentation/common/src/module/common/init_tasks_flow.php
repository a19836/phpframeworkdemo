<?php
include_once get_lib("org.phpframework.workflow.WorkFlowTaskHandler");
include_once $EVC->getUtilPath("WorkFlowUIHandler");

if ($tasks) {
	$WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url);
	$WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH);
	$WorkFlowTaskHandler->setAllowedTaskTags($tasks);

	$WorkFlowUIHandler = new WorkFlowUIHandler($WorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url);
	$tasks_settings = $WorkFlowTaskHandler->getLoadedTasksSettings();

	$head = $WorkFlowUIHandler->printTasksCSSAndJS();
	$contents = array();
	$js_load_functions = array();

	foreach ($tasks_settings as $group_id => $group_tasks) {
		foreach ($group_tasks as $task_type => $task_settings) {
			if (is_array($task_settings)) {
				$tag = $task_settings["tag"];
		
				$contents[$tag] = $task_settings["task_properties_html"];
				$js_load_functions[$tag] = $task_settings["settings"]["callback"]["on_load_task_properties"];
			}
		}
	}
	
	$tasks_data = array("head" => $head, "contents" => $contents, "js_load_functions" => $js_load_functions);
}
?>
