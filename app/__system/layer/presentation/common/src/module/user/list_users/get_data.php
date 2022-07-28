<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("object/ObjectUtil", $common_project_name);
	include $EVC->getModulePath("user/UserUtil", $common_project_name);
	
	$object_types = ObjectUtil::getAllObjectTypes($brokers, true);
	
	$reserved_user_type_ids = UserUtil::getReservedUserTypeIds();
	
	$available_user_types = array();
	$user_types = UserUtil::getAllUserTypes($brokers, true);
	
	if ($user_types) {
		$t = count($user_types);
		for ($i = 0; $i < $t; $i++)
			if (!in_array($user_types[$i]["user_type_id"], $reserved_user_type_ids))
				$available_user_types[] = $user_types[$i];
	}
	
	$data = array("object_types" => $object_types, "user_types" => $available_user_types);
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);

echo $data ? json_encode($data) : "";
?>
