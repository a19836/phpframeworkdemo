<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

include_once $EVC->getModulePath("user/UserUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();
$reserved_user_type_ids = UserUtil::getReservedUserTypeIds();

if (isset($_GET["user_type_id"])) {
	if (in_array($_GET["user_type_id"], $reserved_user_type_ids)) {
		echo "This user type is native and cannot be deleted!";
	}
	else if (UserUtil::deleteUserType($brokers, $_GET["user_type_id"])) {
		echo "1";
	}
}
?>
