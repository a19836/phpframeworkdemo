<?php
include_once $EVC->getModulePath("attachment/AttachmentUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

if (AttachmentUtil::deleteFile($EVC, $_GET["attachment_id"])) {
	echo "1";
}
?>
