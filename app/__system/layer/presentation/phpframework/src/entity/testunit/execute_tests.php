<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once $EVC->getUtilPath("WorkFlowTestUnitHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); UserAuthenticationHandler::checkUsersMaxNum($UserAuthenticationHandler); UserAuthenticationHandler::checkActionsMaxNum($UserAuthenticationHandler); if ($_POST) { $selected_paths = $_POST["selected_paths"]; if ($selected_paths) { $WorkFlowTestUnitHandler = new WorkFlowTestUnitHandler($user_global_variables_file_path, $user_beans_folder_path); $WorkFlowTestUnitHandler->initBeanObjects(); $responses = array(); foreach ($selected_paths as $test_path) $WorkFlowTestUnitHandler->executeTest($test_path, $responses); $UserAuthenticationHandler->incrementUsedActionsTotal(); } } ?>
