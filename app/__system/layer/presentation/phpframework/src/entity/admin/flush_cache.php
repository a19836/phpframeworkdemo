<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once $EVC->getUtilPath("FlushCacheHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "delete"); $status = FlushCacheHandler::flushCache($EVC, $webroot_cache_folder_path, $webroot_cache_folder_url, $workflow_paths_id, $user_global_variables_file_path, $user_beans_folder_path, $css_and_js_optimizer_webroot_cache_folder_path, $deployments_temp_folder_path); ?>
