<?php
include $EVC->getConfigPath("config");
include $EVC->getUtilPath("WorkFlowBeansFileHandler");
include $EVC->getUtilPath("CMSPresentationFormSettingsUIHandler");

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

$bean_name = $_GET["bean_name"];
$bean_file_name = $_GET["bean_file_name"];
$path = $_GET["path"];
$db_layer_file = $_GET["db_layer_file"];
$dal_broker = $_GET["dal_broker"]; //data access layer
$db_driver = $_GET["db_driver"];
$type = $_GET["type"];

$include_db_driver = !empty($db_driver);
$db_driver = $db_driver ? $db_driver : $GLOBALS["default_db_driver"];

$settings = $_POST["settings"];

$db_driver_db_broker = WorkFlowBeansFileHandler::getLayerLocalDBBrokerNameForChildBrokerDBDriver($user_global_variables_files_path, $user_beans_folder_path, $PEVC->getPresentationLayer(), $db_driver);
$include_db_broker = !empty($db_driver_db_broker);

$form_settings = CMSPresentationFormSettingsUIHandler::getFormSettings($user_global_variables_file_path, $user_beans_folder_path, $workflow_paths_id, $webroot_cache_folder_path, $webroot_cache_folder_url, $bean_name, $bean_file_name, $path, null, $dal_broker, $db_driver_db_broker, $include_db_broker, $db_driver, $include_db_driver, $type, null, $settings);
$form_settings = CMSPresentationFormSettingsUIHandler::convertFormSettingsToJavascriptSettings($form_settings);

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);

header("Content-Type: application/json");
echo json_encode($form_settings);
?>
