<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
 $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $user_actions_count = $UserAuthenticationHandler->getUsedActionsTotal(); $li = $EVC->getPresentationLayer()->getPHPFrameWork()->gLI(); $li_data = array(); if (is_array($li)) foreach ($li as $k => $v) { $parts = explode("_", $k); $k = ""; for ($i = count($parts) - 1; $i >= 0; $i--) $k .= $parts[$i][0]; if (!array_key_exists($k, $li)) $li_data[$k] = $v; } unset($li); ?>
