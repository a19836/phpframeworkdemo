<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("user/UserUtil", $common_project_name);
	
	if (isset($_GET["thread_id"]) && isset($_GET["user_id"]) && isset($_GET["activity_id"]) && isset($_GET["object_type_id"]) && isset($_GET["object_id"]) && UserUtil::deleteUserActivityObject($brokers, $_GET["thread_id"], $_GET["user_id"], $_GET["activity_id"], $_GET["object_type_id"], $_GET["object_id"], isset($_GET["time"]) ? $_GET["time"] : null)) {
		echo "1";
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
