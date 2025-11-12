<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

include $EVC->getConfigPath("config");
include $EVC->getUtilPath("WorkFlowBeansFileHandler");
include $EVC->getUtilPath("CMSPresentationFormSettingsUIHandler");

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

$bean_name = isset($_GET["bean_name"]) ? $_GET["bean_name"] : null;
$bean_file_name = isset($_GET["bean_file_name"]) ? $_GET["bean_file_name"] : null;
$path = isset($_GET["path"]) ? $_GET["path"] : null;
$db_layer_file = isset($_GET["db_layer_file"]) ? $_GET["db_layer_file"] : null;
$dal_broker = isset($_GET["dal_broker"]) ? $_GET["dal_broker"] : null; //data access layer
$db_driver = isset($_GET["db_driver"]) ? $_GET["db_driver"] : null;
$type = isset($_GET["type"]) ? $_GET["type"] : null;

$include_db_driver = !empty($db_driver);
$db_driver = !empty($db_driver) ? $db_driver : (isset($GLOBALS["default_db_driver"]) ? $GLOBALS["default_db_driver"] : null);

$settings = isset($_POST["settings"]) ? $_POST["settings"] : null;

$db_driver_db_broker = WorkFlowBeansFileHandler::getLayerLocalDBBrokerNameForChildBrokerDBDriver($user_global_variables_file_path, $user_beans_folder_path, $PEVC->getPresentationLayer(), $db_driver);
$include_db_broker = !empty($db_driver_db_broker);

$form_settings = CMSPresentationFormSettingsUIHandler::getFormSettings($user_global_variables_file_path, $user_beans_folder_path, $workflow_paths_id, $webroot_cache_folder_path, $webroot_cache_folder_url, $bean_name, $bean_file_name, $path, null, $dal_broker, $db_driver_db_broker, $include_db_broker, $db_driver, $include_db_driver, $type, null, $settings);
$form_settings = CMSPresentationFormSettingsUIHandler::convertFormSettingsToJavascriptSettings($form_settings);

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);

header("Content-Type: application/json");
echo json_encode($form_settings);
?>
