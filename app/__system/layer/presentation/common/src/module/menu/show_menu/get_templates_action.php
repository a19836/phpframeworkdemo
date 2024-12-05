<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

include $EVC->getModulePath("common/CommonModuleSettingsUtil", $EVC->getCommonProjectName());
echo CommonModuleSettingsUtil::getTemplatesAction($EVC, isset($_GET) ? $_GET : null);
?>
