<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.workflow.WorkFlowTaskHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH); $WorkFlowTaskHandler->setAllowedTaskTypes(array("if", "switch")); $WorkFlowTaskHandler->setTasksContainers(array("content_with_only_if" => array("if"), "content_with_only_switch" => array("switch"))); $get_workflow_file_path = "/home/jplpinto/Desktop/phpframework/trunk/app/lib/org/phpframework/workflow/test/tasks3.xml"; $set_workflow_file_path = "/tmp/test_tasks.xml"; ?>
