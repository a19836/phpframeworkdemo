<?php
include_once $EVC->getModulePath("attachment/AttachmentUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "write", $module_path);

if ($_POST) {
	if (AttachmentUtil::uploadObjectFile($EVC, $_FILES["file"], $_POST["object_type_id"], $_POST["object_id"], $_POST["group"])) {
		$status = true;
	}
}

if (!$status) {
	header("HTTP/1.1 500 Internal Server Error");
	echo translateProjectText($EVC, "Internal Server Error");
}
else {
	echo "1";
}
?>
