<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once $EVC->getUtilPath("WorkFlowTasksFileHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $path = $_GET["path"]; $path_extra = $_GET["path_extra"]; $path = str_replace("../", "", $path);$path_extra = str_replace("../", "", $path_extra); UserAuthenticationHandler::checkUsersMaxNum($UserAuthenticationHandler); $path = WorkFlowTasksFileHandler::getTaskFilePathByPath($workflow_paths_id, $path, $path_extra); if (isset($_POST["save"])) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); UserAuthenticationHandler::checkActionsMaxNum($UserAuthenticationHandler); $status = WorkFlowTasksFileHandler::createTasksFile($path, $_POST["data"], $_POST["file_read_date"]); if ($status) $UserAuthenticationHandler->incrementUsedActionsTotal(); } ?>
