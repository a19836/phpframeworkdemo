<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include $EVC->getConfigPath("config"); mcbe6bb6d6830($EVC, $user_global_variables_file_path, $user_beans_folder_path); include $EVC->getUtilPath("sanitize_html_in_post_request", $EVC->getCommonProjectName()); include $EVC->getConfigPath("authentication"); function mcbe6bb6d6830($EVC, $user_global_variables_file_path, $user_beans_folder_path) { $pb9be6168 = substr(LA_REGEX, strpos(LA_REGEX, "]") + 1); if ($pb9be6168 == -1) $v5c1c342594 = true; else if (is_numeric($pb9be6168)) { include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $v6ee393d9fb = CMSPresentationLayerHandler::getPresentationLayersProjectsFiles($user_global_variables_file_path, $user_beans_folder_path, "webroot", false, 0); $v03f4b4ed53 = 0; if ($v6ee393d9fb) foreach ($v6ee393d9fb as $v7dffdb5a5b) if (!empty($v7dffdb5a5b["projects"])) { $v03f4b4ed53 += count($v7dffdb5a5b["projects"]); if (array_key_exists("common", $v7dffdb5a5b["projects"])) $v03f4b4ed53--; } if ($v03f4b4ed53 <= $pb9be6168) $v5c1c342594 = true; } $v258de04f2e = $v5c1c342594 ? "646566696e65282250524f4a454354535f434845434b4544222c20313233293b" : "596f752065786365656420746865206d6178696d756d206e756d626572206f662070726f6a65637473207468617420796f7572206c6963656e636520616c6c6f772e"; $v1db8fcc7cd = ""; for ($v43dd7d0051 = 0, $pe2ae3be9 = strlen($v258de04f2e); $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051 += 2) $v1db8fcc7cd .= chr( hexdec($v258de04f2e[$v43dd7d0051] . ($v43dd7d0051+1 < $pe2ae3be9 ? $v258de04f2e[$v43dd7d0051+1] : "") ) ); if ($v5c1c342594) eval($v1db8fcc7cd); else { echo $v1db8fcc7cd; die(1); } } include $EVC->getControllerPath("index", $EVC->getCommonProjectName()); ?>
