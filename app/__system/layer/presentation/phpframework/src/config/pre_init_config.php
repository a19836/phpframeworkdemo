<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
$project_path = dirname(dirname(str_replace(DIRECTORY_SEPARATOR, "/", __DIR__))) . "/"; $layer_path = dirname($project_path) . "/"; $presentation_id = substr($project_path, strlen($layer_path), -1); $project_default_template = "main"; $project_with_auto_view = true; $log_level = 3; if (!defined("IS_SYSTEM_PHPFRAMEWORK")) define("IS_SYSTEM_PHPFRAMEWORK", true); ?>
