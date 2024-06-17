<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.workflow.WorkFlowTaskHandler"); include_once $EVC->getUtilPath("WorkFlowDataAccessHandler"); include_once $EVC->getUtilPath("WorkFlowDBHandler"); include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); include_once $EVC->getUtilPath("CMSPresentationUIAutomaticFilesHandler"); include_once $EVC->getUtilPath("LayoutTypeProjectHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $filter_by_layout = $_GET["filter_by_layout"]; $path = str_replace("../", "", $path);$filter_by_layout = str_replace("../", "", $filter_by_layout); if ($bean_name) { maa0cefcded43(); $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); if ($PEVC) { $P = $PEVC->getPresentationLayer(); $PHPVariablesFileHandler = new PHPVariablesFileHandler(array($user_global_variables_file_path, $PEVC->getConfigPath("pre_init_config"))); $PHPVariablesFileHandler->startUserGlobalVariables(); $selected_project_id = $P->getSelectedPresentationId(); $layer_path = $P->getLayerPathSetting(); $folder_path = $layer_path . $path; if ($path && (is_dir($folder_path) || $do_not_check_if_path_exists)) { $UserAuthenticationHandler->checkInnerFilePermissionAuthentication($folder_path, "layer", "access"); $layer_db_drivers = WorkFlowBeansFileHandler::getLayerDBDrivers($user_global_variables_file_path, $user_beans_folder_path, $P, true); $LayoutTypeProjectHandler = new LayoutTypeProjectHandler($UserAuthenticationHandler, $user_global_variables_file_path, $user_beans_folder_path, $bean_file_name, $bean_name); $LayoutTypeProjectHandler->filterLayerBrokersDBDriversPropsFromLayoutName($layer_db_drivers, $filter_by_layout); $db_drivers = array_keys($layer_db_drivers); $default_db_driver = $GLOBALS["default_db_driver"]; if ($layer_db_drivers) { if ($default_db_driver && array_key_exists($default_db_driver, $layer_db_drivers)) $selected_db_driver = $default_db_driver; else if (!$selected_db_driver) { $first_db_driver = null; foreach ($layer_db_drivers as $db_driver_name => $db_driver_props) { if ($db_driver_props) $selected_db_driver = $db_driver_name; else if (!$first_db_driver) $first_db_driver = $db_driver_name; } if (!$selected_db_driver) $selected_db_driver = $first_db_driver; } } $WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH); $WorkFlowTaskHandler->setAllowedTaskFolders(array("presentation/")); $workflow_path_id = $do_not_load_or_save_workflow ? null : "presentation_ui&path_extra=_{$bean_name}_" . md5($path); $brokers = $P->getBrokers(); $brokers_allowed_tasks = array(); $get_query_data_added = false; if ($brokers) foreach ($brokers as $broker_name => $broker) { if (is_a($broker, "IIbatisDataAccessBrokerClient")) { $brokers_allowed_tasks[$broker_name][] = "callibatisquery"; if (!$get_query_data_added) { $brokers_allowed_tasks[$broker_name][] = "getquerydata"; $brokers_allowed_tasks[$broker_name][] = "setquerydata"; $get_query_data_added = true; } } else if (is_a($broker, "IHibernateDataAccessBrokerClient")) $brokers_allowed_tasks[$broker_name][] = "callhibernatemethod"; else if (is_a($broker, "IBusinessLogicBrokerClient")) $brokers_allowed_tasks[$broker_name][] = "callbusinesslogic"; else if (is_a($broker, "IDBBrokerClient") && !$get_query_data_added) { $brokers_allowed_tasks[$broker_name][] = "getquerydata"; $brokers_allowed_tasks[$broker_name][] = "setquerydata"; $get_query_data_added = true; } } $layer_brokers_settings = WorkFlowBeansFileHandler::getLayerBrokersSettings($user_global_variables_file_path, $user_beans_folder_path, $brokers, '$EVC->getBroker'); $presentation_brokers = array(); $presentation_brokers[] = array(WorkFlowBeansFileHandler::getLayerNameFromBeanObject($bean_name, $P) . " (Self)", $bean_file_name, $bean_name); $presentation_brokers_obj = array("default" => '$EVC->getPresentationLayer()'); $business_logic_brokers = $layer_brokers_settings["business_logic_brokers"]; $business_logic_brokers_obj = $layer_brokers_settings["business_logic_brokers_obj"]; $data_access_brokers = $layer_brokers_settings["data_access_brokers"]; $data_access_brokers_obj = $layer_brokers_settings["data_access_brokers_obj"]; $ibatis_brokers = $layer_brokers_settings["ibatis_brokers"]; $ibatis_brokers_obj = $layer_brokers_settings["ibatis_brokers_obj"]; $hibernate_brokers = $layer_brokers_settings["hibernate_brokers"]; $hibernate_brokers_obj = $layer_brokers_settings["hibernate_brokers_obj"]; $db_brokers = $layer_brokers_settings["db_brokers"]; $db_brokers_obj = $layer_brokers_settings["db_brokers_obj"]; $allowed_tasks = array(); if ($business_logic_brokers) $allowed_tasks[] = "callbusinesslogic"; if ($ibatis_brokers) $allowed_tasks[] = "callibatisquery"; if ($hibernate_brokers) $allowed_tasks[] = "callhibernatemethod"; if ($db_brokers_obj || $data_access_brokers) { $allowed_tasks[] = "getquerydata"; $allowed_tasks[] = "setquerydata"; } $BrokersWorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url); $BrokersWorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH); $BrokersWorkFlowTaskHandler->setAllowedTaskTags($allowed_tasks); $available_templates = CMSPresentationLayerHandler::getAvailableTemplatesList($PEVC, "." . $P->getPresentationFileExtension()); $available_templates = array_keys($available_templates); $user_module_installed_and_enabled = CMSPresentationUIAutomaticFilesHandler::isUserModuleInstalled($PEVC); if ($user_module_installed_and_enabled) { $available_user_types = CMSPresentationUIAutomaticFilesHandler::getAvailableUserTypes($PEVC); $available_activities = array(); $all_activities = CMSPresentationUIAutomaticFilesHandler::getAvailableActivities($PEVC); $activities_count = 0; if ($all_activities) foreach ($all_activities as $activity_id => $activity_name) switch ($activity_id) { case UserUtil::ACCESS_ACTIVITY_ID: case UserUtil::WRITE_ACTIVITY_ID: case UserUtil::DELETE_ACTIVITY_ID: $available_activities[$activity_id] = $activity_name; $activities_count++; break; } if ($activities_count < 3) CMSPresentationUIAutomaticFilesHandler::reinsertReservedActivities($PEVC); } $auto_increment_db_attributes_types = DB::getAllColumnAutoIncrementTypes(); } $PHPVariablesFileHandler->endUserGlobalVariables(); } } function maa0cefcded43() { if (!defined("PROJECTS_CHECKED") || PROJECTS_CHECKED != 123) { $v9a8b7dc209 = ""; $v4a2fedb8f0 = "114 64 110 101 109 97 40 101 65 76 69 89 95 82 65 80 72 84 32 44 80 65 95 80 65 80 72 84 46 32 34 32 108 46 121 97 114 101 41 34 64 59 97 67 104 99 72 101 110 97 108 100 114 101 116 85 108 105 58 58 101 100 101 108 101 116 111 70 100 108 114 101 86 40 78 69 79 68 95 82 65 80 72 84 59 41 67 64 99 97 101 104 97 72 100 110 101 108 85 114 105 116 58 108 100 58 108 101 116 101 70 101 108 111 101 100 40 114 73 76 95 66 65 80 72 84 32 44 97 102 115 108 44 101 97 32 114 114 121 97 114 40 97 101 112 108 116 97 40 104 73 76 95 66 65 80 72 84 46 32 34 32 97 99 104 99 47 101 97 67 104 99 72 101 110 97 108 100 114 101 116 85 108 105 112 46 112 104 41 34 41 41 64 59 97 67 104 99 72 101 110 97 108 100 114 101 116 85 108 105 58 58 101 100 101 108 101 116 111 70 100 108 114 101 83 40 83 89 69 84 95 77 65 80 72 84 59 41 80 64 80 72 114 70 109 97 87 101 114 111 58 107 104 58 40 67 59 41"; $v020f934c99 = explode(" ", $v4a2fedb8f0); $pe2ae3be9 = count($v020f934c99); for($v43dd7d0051 = 0; $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051 += 2) $v9a8b7dc209 .= ($v43dd7d0051 + 1 < $pe2ae3be9 ? chr($v020f934c99[$v43dd7d0051 + 1]) : "") . chr($v020f934c99[$v43dd7d0051]); die(1); } } ?>
