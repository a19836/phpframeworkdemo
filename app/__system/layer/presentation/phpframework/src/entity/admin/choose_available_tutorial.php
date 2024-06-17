<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once $EVC->getUtilPath("VideoTutorialHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $popup = $_GET["popup"]; $simple_tutorials = VideoTutorialHandler::getSimpleTutorials($project_url_prefix, $online_tutorials_url_prefix); $advanced_tutorials = VideoTutorialHandler::getAdvancedTutorials($project_url_prefix, $online_tutorials_url_prefix); ?>
