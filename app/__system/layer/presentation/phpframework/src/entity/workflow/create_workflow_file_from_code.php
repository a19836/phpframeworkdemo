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
 include_once $EVC->getUtilPath("WorkFlowTasksFileHandler"); include get_lib("org.phpframework.workflow.WorkFlowTaskCodeParser"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $path = isset($_GET["path"]) ? $_GET["path"] : null; $path_extra = isset($_GET["path_extra"]) ? $_GET["path_extra"] : null; $path = str_replace("../", "", $path); $path_extra = str_replace("../", "", $path_extra); $status = false; if (isset($_POST)) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $code = htmlspecialchars_decode( file_get_contents("php://input"), ENT_NOQUOTES); $WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH); $WorkFlowTaskHandler->addTasksFoldersPath($code_workflow_editor_user_tasks_folders_path); $loaded_tasks_settings_cache_id = isset($_GET["loaded_tasks_settings_cache_id"]) ? $_GET["loaded_tasks_settings_cache_id"] : null; $loaded_tasks_settings = $WorkFlowTaskHandler->getCachedLoadedTasksSettings($loaded_tasks_settings_cache_id); if ($loaded_tasks_settings) { $allowed_tasks_tag = array(); foreach ($loaded_tasks_settings as $group_id => $group_tasks) foreach ($group_tasks as $task_type => $task_settings) $allowed_tasks_tag[] = isset($task_settings["tag"]) ? $task_settings["tag"] : null; if ($allowed_tasks_tag) $WorkFlowTaskHandler->setAllowedTaskTags($allowed_tasks_tag); } $WorkFlowTaskHandler->initWorkFlowTasks(); $WorkFlowTaskCodeParser = new WorkFlowTaskCodeParser($WorkFlowTaskHandler); $xml = $WorkFlowTaskCodeParser->getParsedCodeAsXml($code); $task_file_path = WorkFlowTasksFileHandler::getTaskFilePathByPath($workflow_paths_id, $path, $path_extra); $folder = dirname($task_file_path); if (is_dir($folder) || mkdir($folder, 0775, true)) if (file_put_contents($task_file_path, $xml) > 0) $status = true; } ?>
