<?php
include_once $EVC->getModulePath("attachment/AttachmentUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (AttachmentUtil::deleteObjectAttachment($brokers, $_GET["attachment_id"], $_GET["object_type_id"], $_GET["object_id"])) {
	echo "1";
}
?>
