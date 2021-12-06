<?php
include_once $EVC->getModulePath("object/ObjectUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();
$reserved_object_type_ids = ObjectUtil::getReservedObjectTypeIds();

if (in_array($_GET["object_type_id"], $reserved_object_type_ids)) {
	echo "This object type is native and cannot be deleted!";
}
else if (ObjectUtil::deleteObjectType($brokers, $_GET["object_type_id"])) {
	echo "1";
}
?>
