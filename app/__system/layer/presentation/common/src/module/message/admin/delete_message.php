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
	include $EVC->getModulePath("message/MessageUtil", $common_project_name);
	
	if (isset($_GET["message_id"]) && isset($_GET["from_user_id"]) && isset($_GET["to_user_id"]) && MessageUtil::deleteMessage($brokers, $_GET["message_id"], $_GET["from_user_id"], $_GET["to_user_id"])) {
		echo "1";
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
