<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$is_admin_ui_simple_allowed = $UserAuthenticationHandler->isFilePermissionAllowed("simple", "admin_ui", "access"); $is_admin_ui_citizen_allowed = $UserAuthenticationHandler->isFilePermissionAllowed("citizen", "admin_ui", "access"); $is_admin_ui_low_code_allowed = $UserAuthenticationHandler->isFilePermissionAllowed("low_code", "admin_ui", "access"); $is_admin_ui_advanced_allowed = $UserAuthenticationHandler->isFilePermissionAllowed("advanced", "admin_ui", "access"); $is_admin_ui_expert_allowed = $UserAuthenticationHandler->isFilePermissionAllowed("expert", "admin_ui", "access"); $admin_uis_count = 0; if ($is_admin_ui_simple_allowed) $admin_uis_count++; if ($is_admin_ui_citizen_allowed) $admin_uis_count++; if ($is_admin_ui_low_code_allowed) $admin_uis_count++; if ($is_admin_ui_advanced_allowed) $admin_uis_count++; if ($is_admin_ui_expert_allowed) $admin_uis_count++; $is_switch_admin_ui_allowed = $admin_uis_count > 1; ?>
