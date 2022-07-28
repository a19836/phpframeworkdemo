<?php
include_once $EVC->getModulePath("message/MessageUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (MessageUtil::deleteMessage($brokers, $_GET["message_id"], $_GET["from_user_id"], $_GET["to_user_id"])) {
	echo "1";
}
?>
