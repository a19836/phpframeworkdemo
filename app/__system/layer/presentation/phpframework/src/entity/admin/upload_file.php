<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $item_type = $_GET["item_type"]; $on_success_js_func = $_GET["on_success_js_func"]; $filter_by_layout = $_GET["filter_by_layout"]; $popup = $_GET["popup"]; $path = str_replace("../", "", $path);$filter_by_layout = str_replace("../", "", $filter_by_layout); if ($item_type == "dao") $root_path = DAO_PATH; else if ($item_type == "vendor") $root_path = VENDOR_PATH; else if ($item_type == "test_unit") $root_path = TEST_UNIT_PATH; else if ($item_type == "other") $root_path = OTHER_PATH; else if ($bean_name) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); if ($item_type != "presentation") $obj = $WorkFlowBeansFileHandler->getBeanObject($bean_name); else { $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); $obj = $PEVC ? $PEVC->getPresentationLayer() : null; } $root_path = $obj->getLayerPathSetting(); } $file_path = $root_path . $path; ?>
