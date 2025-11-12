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

if (!empty($PEVC) && isset($_GET["object_type_id"])) {
	include $EVC->getModulePath("object/ObjectUtil", $common_project_name);
	
	$reserved_object_type_ids = ObjectUtil::getReservedObjectTypeIds();
	
	if (in_array($_GET["object_type_id"], $reserved_object_type_ids)) {
		echo "This object type is native and cannot be deleted!";
	}
	else if (ObjectUtil::deleteObjectType($brokers, $_GET["object_type_id"])) {
		echo "1";
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
