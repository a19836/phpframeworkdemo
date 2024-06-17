<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include $EVC->getUtilPath("WorkFlowUIHandler"); $WorkFlowUIHandler = new WorkFlowUIHandler($WorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $external_libs_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $head = $WorkFlowUIHandler->getHeader(); $head .= $WorkFlowUIHandler->getJS($get_workflow_file_path, $set_workflow_file_path, array("is_droppable_connection" => true, "add_default_start_task" => true, "resizable_task_properties" => true, "resizable_connection_properties" => true)); $head .= '<style type="text/css">
	.tasks_flow #content_with_only_if {width:100%; height:50%; background-color:#FF0000;}
	.tasks_flow #content_with_only_switch {width:100%; height:50%; background-color:#00FFFF;}
</style>'; $main_content = $WorkFlowUIHandler->getContent(); ?>
