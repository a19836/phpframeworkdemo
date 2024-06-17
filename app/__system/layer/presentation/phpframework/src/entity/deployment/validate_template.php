<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once $EVC->getUtilPath("WorkFlowDeploymentHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $server_name = $_GET["server"]; $template_id = $_GET["template_id"]; $li = $EVC->getPresentationLayer()->getPHPFrameWork()->gLI(); $status = WorkFlowDeploymentHandler::validateTemplate($server_name, $template_id, $workflow_paths_id, $li, $error_message); echo $error_message ? $error_message : $status; die(); ?>
