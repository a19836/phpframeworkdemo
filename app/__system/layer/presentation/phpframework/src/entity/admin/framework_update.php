<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once $EVC->getUtilPath("FlushCacheHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $is_remote_update_allowed = function_exists("exec") && function_exists("posix_getpwuid") && file_exists(SYSTEM_PATH); $step = 0; if ($is_remote_update_allowed) { $web_server_user = posix_getpwuid(posix_getuid()); $os_account_user = posix_getpwuid(fileowner(SYSTEM_PATH)); $is_remote_update_allowed = !empty($web_server_user["name"]) && !empty($os_account_user["name"]) && $web_server_user["name"] == $os_account_user["name"]; if ($is_remote_update_allowed && !empty($_POST)) { $step = isset($_POST["step"]) ? $_POST["step"] : null; if ($step == 2) { exec("/bin/git pull '" . CMS_PATH . "'", $output); } else if ($step == 1) { $changed_files = array("asdasd"); exec("/bin/git ls-files -m", $changed_files); if (empty($changed_files)) { exec("/bin/git pull '" . CMS_PATH . "'", $output); FlushCacheHandler::flushCache($EVC, $webroot_cache_folder_path, $webroot_cache_folder_url, $workflow_paths_id, $user_global_variables_file_path, $user_beans_folder_path, $css_and_js_optimizer_webroot_cache_folder_path, $deployments_temp_folder_path); } } } } ?>
