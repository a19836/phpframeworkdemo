<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once $EVC->getUtilPath("PHPVariablesFileHandler"); include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler(SYSTEM_BEAN_PATH . "db_layer.xml", GLOBAL_VARIABLES_PROPERTIES_FILE_PATH); $WorkFlowBeansFileHandler->init(); $db_layer_brokers_names = $WorkFlowBeansFileHandler->getBeanBrokersReferences("DBLayer"); $available_drivers = array(); if ($db_layer_brokers_names) foreach ($db_layer_brokers_names as $driver_name => $driver_reference) if ($driver_name) $available_drivers[$driver_name] = $driver_reference ? $driver_reference : $driver_name; if (!$available_drivers && !$is_local_db) { launch_exception(new Exception("No DB Drivers in __system. Please fix your internal bean xml files!")); die(); } $authentication_db_driver = $GLOBALS["default_db_driver"] ? $GLOBALS["default_db_driver"] : ""; $authentication_db_extension = $GLOBALS[$authentication_db_driver . "_db_extension"]; $authentication_db_host = $GLOBALS[$authentication_db_driver . "_db_host"]; $authentication_db_name = $GLOBALS[$authentication_db_driver . "_db_name"]; $authentication_db_username = $GLOBALS[$authentication_db_driver . "_db_username"]; $authentication_db_password = $GLOBALS[$authentication_db_driver . "_db_password"]; $authentication_db_port = $GLOBALS[$authentication_db_driver . "_db_port"]; $authentication_db_persistent = $GLOBALS[$authentication_db_driver . "_db_persistent"]; $authentication_db_new_link = $GLOBALS[$authentication_db_driver . "_db_new_link"]; $authentication_db_encoding = $GLOBALS[$authentication_db_driver . "_db_encoding"]; $authentication_db_schema = $GLOBALS[$authentication_db_driver . "_db_schema"]; $authentication_db_odbc_data_source = $GLOBALS[$authentication_db_driver . "_db_odbc_data_source"]; $authentication_db_odbc_driver = $GLOBALS[$authentication_db_driver . "_db_odbc_driver"]; $authentication_db_extra_dsn = $GLOBALS[$authentication_db_driver . "_db_extra_dsn"]; if ($_POST) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $maximum_failed_attempts = $_POST["maximum_failed_attempts"]; $user_blocked_expired_time = $_POST["user_blocked_expired_time"]; $login_expired_time = $_POST["login_expired_time"]; $auth_db_path = $_POST["auth_db_path"]; $is_local_db = $_POST["is_local_db"]; $authentication_db_driver = $_POST["authentication_db_driver"]; $authentication_db_extension = $_POST["authentication_db_extension"]; $authentication_db_host = $_POST["authentication_db_host"]; $authentication_db_port = $_POST["authentication_db_port"]; $authentication_db_name = $_POST["authentication_db_name"]; $authentication_db_username = $_POST["authentication_db_username"]; $authentication_db_password = $_POST["authentication_db_password"]; $authentication_db_persistent = $_POST["authentication_db_persistent"]; $authentication_db_new_link = $_POST["authentication_db_new_link"]; $authentication_db_encoding = $_POST["authentication_db_encoding"]; $authentication_db_schema = $_POST["authentication_db_schema"]; $authentication_db_odbc_data_source = $_POST["authentication_db_odbc_data_source"]; $authentication_db_odbc_driver = $_POST["authentication_db_odbc_driver"]; $authentication_db_extra_dsn = $_POST["authentication_db_extra_dsn"]; if (!is_numeric($maximum_failed_attempts) || $maximum_failed_attempts < 0) $error_message = "Maximum # of Failed Attempts must be numeric and bigger or equal than 0! Please try again..."; else if (!is_numeric($user_blocked_expired_time) || $user_blocked_expired_time < 0) $error_message = "User Blocked Expired Time must be numeric and bigger or equal than 0! Please try again..."; else if (!is_numeric($login_expired_time) || $login_expired_time < 0) $error_message = "Login Expired Time must be numeric and bigger or equal than 0! Please try again..."; else { $authentication_config_file_path = $EVC->getConfigPath("authentication"); if (file_exists($authentication_config_file_path)) { $code = file_get_contents($authentication_config_file_path); replaceVarInCode($code, "maximum_failed_attempts", $maximum_failed_attempts); replaceVarInCode($code, "user_blocked_expired_time", $user_blocked_expired_time); replaceVarInCode($code, "login_expired_time", $login_expired_time); replaceVarInCode($code, "is_local_db", $is_local_db ? "true" : "false"); if ($auth_db_path) { if (substr($auth_db_path, 0, 1) == "/") replaceVarInCode($code, "authentication_db_path", '"' . $auth_db_path . '"'); else { $auth_db_path = CMS_PATH . $auth_db_path; if (strpos($auth_db_path, SYSTEM_PATH) !== false) $auth_db_path_str = 'SYSTEM_PATH . "' . str_replace(SYSTEM_PATH, "", $auth_db_path) . '"'; else if (strpos($auth_db_path, LAYER_PATH) !== false) $auth_db_path_str = 'LAYER_PATH . "' . str_replace(LAYER_PATH, "", $auth_db_path) . '"'; else if (strpos($auth_db_path, APP_PATH) !== false) $auth_db_path_str = 'APP_PATH . "' . str_replace(APP_PATH, "", $auth_db_path) . '"'; else $auth_db_path_str = 'CMS_PATH . "' . str_replace(CMS_PATH, "", $auth_db_path) . '"'; replaceVarInCode($code, "authentication_db_path", $auth_db_path_str); } } if (file_put_contents($authentication_config_file_path, $code) !== false) { $global_variables = PHPVariablesFileHandler::getVarsFromFileContent(GLOBAL_VARIABLES_PROPERTIES_FILE_PATH); $global_variables["default_db_driver"] = $authentication_db_driver; $global_variables[$authentication_db_driver . "_db_extension"] = $authentication_db_extension; $global_variables[$authentication_db_driver . "_db_host"] = $authentication_db_host; $global_variables[$authentication_db_driver . "_db_port"] = $authentication_db_port; $global_variables[$authentication_db_driver . "_db_name"] = $authentication_db_name; $global_variables[$authentication_db_driver . "_db_username"] = $authentication_db_username; $global_variables[$authentication_db_driver . "_db_password"] = $authentication_db_password; $global_variables[$authentication_db_driver . "_db_persistent"] = $authentication_db_persistent; $global_variables[$authentication_db_driver . "_db_new_link"] = $authentication_db_new_link; $global_variables[$authentication_db_driver . "_db_encoding"] = $authentication_db_encoding; $global_variables[$authentication_db_driver . "_db_schema"] = $authentication_db_schema; $global_variables[$authentication_db_driver . "_db_odbc_data_source"] = $authentication_db_odbc_data_source; $global_variables[$authentication_db_driver . "_db_odbc_driver"] = $authentication_db_odbc_driver; $global_variables[$authentication_db_driver . "_db_extra_dsn"] = $authentication_db_extra_dsn; if (PHPVariablesFileHandler::saveVarsToFile(GLOBAL_VARIABLES_PROPERTIES_FILE_PATH, $global_variables, true)) { if ($UserAuthenticationHandler->moveLocalDBToRemoteDBOrViceVersa($is_local_db, $global_variables)) { if ($UserAuthenticationHandler->moveLocalDBToAnotherFolder($auth_db_path)) { $authentication_db_path = $auth_db_path; $status_message = "Auth Settings changed successfully..."; } else { replaceVarInCode($code, "authentication_db_path", '"' . $authentication_db_path . '"'); file_put_contents($authentication_config_file_path, $code); $error_message = "There was an error trying to move Local DB to another location. Please try again..."; } } else $error_message = "There was an error trying to move " . ($is_local_db ? "Remote DB to Local DB" : "Local DB to Remote DB") . ". Please try again..."; } else $error_message = "There was an error trying to save DB credentials to global variables. Please try again..."; } else $error_message = "There was an error trying to change the auth settings. Please try again..."; } else $error_message = "Config Authentication file doesn't exist. Please talk with the SysAdmin for further information..."; } } $data = array(); $data["maximum_failed_attempts"] = $maximum_failed_attempts; $data["user_blocked_expired_time"] = $user_blocked_expired_time; $data["login_expired_time"] = $login_expired_time; $data["auth_db_path"] = str_replace(CMS_PATH, "", $authentication_db_path); $data["is_local_db"] = $is_local_db; $data["authentication_db_driver"] = $authentication_db_driver; $data["authentication_db_extension"] = $authentication_db_extension; $data["authentication_db_host"] = $authentication_db_host; $data["authentication_db_port"] = $authentication_db_port; $data["authentication_db_name"] = $authentication_db_name; $data["authentication_db_username"] = $authentication_db_username; $data["authentication_db_password"] = $authentication_db_password; $data["authentication_db_persistent"] = $authentication_db_persistent; $data["authentication_db_new_link"] = $authentication_db_new_link; $data["authentication_db_encoding"] = $authentication_db_encoding; $data["authentication_db_schema"] = $authentication_db_schema; $data["authentication_db_odbc_data_source"] = $authentication_db_odbc_data_source; $data["authentication_db_odbc_driver"] = $authentication_db_odbc_driver; $data["authentication_db_extra_dsn"] = $authentication_db_extra_dsn; $local_and_remote_options = array( array("value" => "1", "label" => "YES, IS A LOCAL DB!") ); if ($available_drivers) $local_and_remote_options[] = array("value" => "", "label" => "NO, IS A REMOTE DB!"); $drivers_extensions = DB::getAllExtensionsByType(); $available_extensions_options = array(); if ($authentication_db_driver && is_array($drivers_extensions[$authentication_db_driver])) foreach ($drivers_extensions[$authentication_db_driver] as $idx => $enc) $available_extensions_options[] = array("value" => $enc, "label" => $enc . ($idx == 0 ? " - Default" : "")); if ($authentication_db_extension && (!$drivers_extensions[$authentication_db_driver] || !in_array($authentication_db_extension, $drivers_extensions[$authentication_db_driver]))) $available_extensions_options[] = array("value" => $authentication_db_extension, "label" => $authentication_db_extension . " - DEPRECATED"); $drivers_encodings = DB::getAllDBCharsetsByType(); $available_encodings_options = array(array("value" => "", "label" => "-- Default --")); if ($authentication_db_driver && is_array($drivers_encodings[$authentication_db_driver])) foreach ($drivers_encodings[$authentication_db_driver] as $enc => $label) $available_encodings_options[] = array("value" => $enc, "label" => $label); if ($authentication_db_encoding && (!$drivers_encodings[$authentication_db_driver] || !array_key_exists($authentication_db_encoding, $drivers_encodings[$authentication_db_driver]))) $available_encodings_options[] = array("value" => $authentication_db_encoding, "label" => $authentication_db_encoding . " - DEPRECATED"); $drivers_ignore_connection_options = DB::getAllIgnoreConnectionOptionsByType(); $drivers_ignore_connection_options_by_extension = DB::getAllIgnoreConnectionOptionsByExtensionAndType(); function replaceVarInCode(&$v067674f4e4, $v1cfba8c105, $pa6209df1) { if (strpos($v067674f4e4, '$' . $v1cfba8c105) !== false) $v067674f4e4 = preg_replace('/\$' . $v1cfba8c105 . '\s*=\s*([^;]+);/u', '$' . $v1cfba8c105 . ' = ' . $pa6209df1 . ';', $v067674f4e4); else $v067674f4e4 = str_replace("?>", '$' . $v1cfba8c105 . ' = ' . $pa6209df1 . ';' . "\n?>", $v067674f4e4, $v15f3268002 = 1); } ?>
