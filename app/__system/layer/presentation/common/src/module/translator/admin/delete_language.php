<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("translator/TranslatorUtil", $common_project_name);
	
	if (isset($_GET["language"]) && TranslatorUtil::deleteLanguage($PEVC, $_GET["language"], isset($_GET["category"]) ? $_GET["category"] : null)) {
		echo "1";
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
