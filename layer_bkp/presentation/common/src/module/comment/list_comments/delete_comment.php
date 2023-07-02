<?php
include_once $EVC->getModulePath("comment/CommentUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (CommentUtil::deleteComment($brokers, $_GET["comment_id"])) {
	echo "1";
}
?>
