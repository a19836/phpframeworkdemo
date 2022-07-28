<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

include $EVC->getModulePath("common/CommonModuleSettingsUtil", $EVC->getCommonProjectName());
echo CommonModuleSettingsUtil::getTemplatesAction($EVC, $_GET);
?>
