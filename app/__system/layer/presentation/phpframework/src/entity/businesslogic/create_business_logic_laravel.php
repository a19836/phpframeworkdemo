<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); include_once $EVC->getEntityPath("cms/laravel/create_laravel_project"); if ($obj && is_a($obj, "BusinessLogicLayer") && !empty($_POST["step_1"]) && !empty($status)) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $common_service_file_path = isset($obj->settings["business_logic_modules_service_common_file_path"]) ? $obj->settings["business_logic_modules_service_common_file_path"] : null; if (!LaravelInstallationHandler::createLaravelServiceFile($project_folder_path, $common_service_file_path)) $status = false; } ?>
