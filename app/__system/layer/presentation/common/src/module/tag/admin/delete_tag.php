<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("tag/TagUtil", $common_project_name);
	
	if (TagUtil::deleteObjectTagsByTagId($brokers, $_GET["tag_id"]) && TagUtil::deleteTag($brokers, $_GET["tag_id"])) {
		echo "1";
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
