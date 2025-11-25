<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

include_once get_lib("org.phpframework.workflow.WorkFlowTaskHandler");
include_once $EVC->getUtilPath("WorkFlowUIHandler");

if (!empty($tasks)) {
	$WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url);
	$WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH);
	$WorkFlowTaskHandler->setAllowedTaskTags($tasks);

	$WorkFlowUIHandler = new WorkFlowUIHandler($WorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $external_libs_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url);
	$tasks_settings = $WorkFlowTaskHandler->getLoadedTasksSettings();

	$head = $WorkFlowUIHandler->printTasksCSSAndJS();
	$contents = array();
	$js_load_functions = array();

	foreach ($tasks_settings as $group_id => $group_tasks) {
		foreach ($group_tasks as $task_type => $task_settings) {
			if (is_array($task_settings)) {
				$tag = isset($task_settings["tag"]) ? $task_settings["tag"] : null;
		
				$contents[$tag] = isset($task_settings["task_properties_html"]) ? $task_settings["task_properties_html"] : null;
				$js_load_functions[$tag] = isset($task_settings["settings"]["callback"]["on_load_task_properties"]) ? $task_settings["settings"]["callback"]["on_load_task_properties"] : null;
			}
		}
	}
	
	$tasks_data = array("head" => $head, "contents" => $contents, "js_load_functions" => $js_load_functions);
}
?>
