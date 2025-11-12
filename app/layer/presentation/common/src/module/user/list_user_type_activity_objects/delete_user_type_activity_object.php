<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

include_once $EVC->getModulePath("user/UserUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (isset($_GET["user_type_id"]) && isset($_GET["activity_id"]) && isset($_GET["object_type_id"]) && isset($_GET["object_id"]) && UserUtil::deleteUserTypeActivityObject($brokers, $_GET["user_type_id"], $_GET["activity_id"], $_GET["object_type_id"], $_GET["object_id"])) {
	echo "1";
}
?>
