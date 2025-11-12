<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

include $EVC->getModulePath("common/CommonModuleSettingsUtil", $EVC->getCommonProjectName());
$data = CommonModuleSettingsUtil::getAllObjectTypes($EVC);
echo $data ? json_encode($data) : "";
?>
