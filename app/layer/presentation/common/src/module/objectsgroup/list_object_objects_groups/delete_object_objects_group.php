<?php
include_once $EVC->getModulePath("objectsgroup/ObjectsGroupUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (ObjectsGroupUtil::deleteObjectObjectsGroup($brokers, $_GET["objects_group_id"], $_GET["object_type_id"], $_GET["object_id"])) {
	echo "1";
}
?>
