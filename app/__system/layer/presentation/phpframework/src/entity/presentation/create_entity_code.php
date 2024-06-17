<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once $EVC->getUtilPath("SequentialLogicalActivitySettingsCodeCreator"); include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $selected_project_id = $_GET["project"]; $default_extension = $_GET["default_extension"]; $object = $_POST["object"]; if ($object["sla_settings"] && !$object["sla_settings_code"]) $object["sla_settings_code"] = SequentialLogicalActivitySettingsCodeCreator::getActionsCode($webroot_cache_folder_path, $webroot_cache_folder_url, $object["sla_settings"], "\t"); $code = CMSPresentationLayerHandler::createEntityCode($object, $selected_project_id, $default_extension); ?>
