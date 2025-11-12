<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

include_once $EVC->getModulePath("attachment/AttachmentUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "write", $module_path);

if (!empty($_POST)) {
	$file = isset($_FILES["file"]) ? : null;
	$object_type_id = isset($_POST["object_type_id"]) ? $_POST["object_type_id"] : null;
	$object_id = isset($_POST["object_id"]) ? $_POST["object_id"] : null;
	$group = isset($_POST["group"]) ? $_POST["group"] : null;
	
	if (AttachmentUtil::uploadObjectFile($EVC, $file, $object_type_id, $object_id, $group)) {
		$status = true;
	}
}

if (empty($status)) {
	header("HTTP/1.1 500 Internal Server Error");
	echo translateProjectText($EVC, "Internal Server Error");
}
else {
	echo "1";
}
?>
