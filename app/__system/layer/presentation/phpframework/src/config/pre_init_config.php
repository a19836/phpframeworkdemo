<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$project_path = dirname(dirname(str_replace(DIRECTORY_SEPARATOR, "/", __DIR__))) . "/"; $layer_path = dirname($project_path) . "/"; $presentation_id = substr($project_path, strlen($layer_path), -1); $project_default_template = "main"; $project_with_auto_view = true; $log_level = 3; define("IS_SYSTEM_PHPFRAMEWORK", true); ?>
