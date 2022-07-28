<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

include $EVC->getModulePath("common/CommonModuleSettingsUtil", $EVC->getCommonProjectName());
$data = CommonModuleSettingsUtil::getAllObjectTypes($EVC);
echo $data ? json_encode($data) : "";
?>
