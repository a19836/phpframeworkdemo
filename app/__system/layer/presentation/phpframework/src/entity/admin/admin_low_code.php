<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); include $EVC->getEntityPath("admin/admin_citizen"); if ($layers["presentation_layers"]) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $Layer = $WorkFlowBeansFileHandler->getBeanObject($bean_name); $project_dir = dirname($project); $project_dir = $project_dir && $project_dir != "." ? "$project_dir/" : ""; $projects = AdminMenuHandler::getBeanLayerObjs($Layer, $project_dir, 1); $project_name = basename($project); $projects = array($project_name => $projects[$project_name]); foreach ($layers["presentation_layers"] as $layer_name => $layer) $layers["presentation_layers"][$layer_name] = array_merge($layer, $projects); } ?>
