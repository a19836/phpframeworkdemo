<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once $EVC->getUtilPath("CMSDeploymentHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); UserAuthenticationHandler::checkUsersMaxNum($UserAuthenticationHandler); UserAuthenticationHandler::checkActionsMaxNum($UserAuthenticationHandler); $server_name = $_GET["server"]; $template_id = $_GET["template_id"]; $deployment_id = $_GET["deployment_id"]; $action = $_GET["action"]; $li = $EVC->getPresentationLayer()->getPHPFrameWork()->gLI(); $CMSDeploymentHandler = new CMSDeploymentHandler($workflow_paths_id, $webroot_cache_folder_path, $webroot_cache_folder_url, $deployments_temp_folder_path, $user_beans_folder_path, $user_global_variables_file_path, $user_global_settings_file_path, $li); $res = $CMSDeploymentHandler->executeServerAction($server_name, $template_id, $deployment_id, $action); if ($res && $res["status"]) $UserAuthenticationHandler->incrementUsedActionsTotal(); ?>
