<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); include_once $EVC->getUtilPath("VideoTutorialHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $active_tab = $_GET["active_tab"]; $filter_by_layout = $_GET["filter_by_layout"]; $filter_by_layout_permission = UserAuthenticationHandler::$PERMISSION_BELONG_NAME; $filter_by_layout = str_replace("../", "", $filter_by_layout); $admin_type = !empty($_COOKIE["admin_type"]) ? $_COOKIE["admin_type"] : "simple"; $tutorials = VideoTutorialHandler::getSimpleTutorials($project_url_prefix, $online_tutorials_url_prefix); $filtered_tutorials = VideoTutorialHandler::filterTutorials($tutorials, $entity, $admin_type); $presentation_brokers = array(); $project_details = null; $layers = AdminMenuHandler::getLayers($user_global_variables_file_path); $presentation_layers = $layers["presentation_layers"]; if (is_array($presentation_layers)) foreach ($presentation_layers as $bn => $bfn) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bfn, $user_global_variables_file_path); $P = $WorkFlowBeansFileHandler->getBeanObject($bn); $presentation_broker_name = WorkFlowBeansFileHandler::getLayerObjFolderName($P); $layer_bean_folder_name = $presentation_broker_name . "/"; if (substr($filter_by_layout, 0, strlen($layer_bean_folder_name)) == $layer_bean_folder_name) { $presentation_brokers[] = array($presentation_broker_name, $bfn, $bn); $proj_name = substr($filter_by_layout, strlen($layer_bean_folder_name)); $layer_path = $P->getLayerPathSetting(); $is_project = $proj_name ? is_dir($layer_path . $proj_name . "/" . $P->settings["presentation_webroot_path"]) : false; if ($is_project) { $bean_name = $bn; $bean_file_name = $bfn; $project_details = CMSPresentationLayerHandler::getPresentationLayerProjectFiles($user_global_variables_file_path, $user_beans_folder_path, $bean_file_name, $bean_name, $layer_path, $proj_name, "", false, 0, true); $project_id = $project_details["element_type_path"]; $project_id = preg_replace("/^[\/]+/", "", $project_id); $project_id = preg_replace("/[\/]+$/", "", $project_id); $project_details["project_id"] = $project_id; $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $project_id); $PHPVariablesFileHandler = new PHPVariablesFileHandler(array($user_global_variables_file_path, $PEVC->getConfigPath("pre_init_config"))); $PHPVariablesFileHandler->startUserGlobalVariables(); $default_extension = "." . $P->getPresentationFileExtension(); $available_templates = CMSPresentationLayerHandler::getAvailableTemplatesList($PEVC, $default_extension); $available_templates = array_keys($available_templates); $available_templates_props = CMSPresentationLayerHandler::getAvailableTemplatesProps($PEVC, $project_id, $available_templates); $project_default_template = $GLOBALS["project_default_template"]; $files = CMSPresentationLayerHandler::getFolderFilesTree($layer_path, $layer_path . $proj_name . "/" . $P->settings["presentation_entities_path"], false, -1); $num_of_created_pages = countProjectPages($files); $is_fresh_project = $num_of_created_pages <= 1; $PHPVariablesFileHandler->endUserGlobalVariables(); } } } function countProjectPages($v6ee393d9fb) { $v7e0d3fab7c = 0; if ($v6ee393d9fb) foreach ($v6ee393d9fb as $v250a1176c9 => $v305588d5f0) { if ($v305588d5f0["type"] == "php_file") $v7e0d3fab7c++; else if ($v305588d5f0["type"] == "folder") $v7e0d3fab7c += countProjectPages($v305588d5f0["sub_files"]); } return $v7e0d3fab7c; } ?>
