<?php
include $EVC->getConfigPath("config");
@include_once $EVC->getModulePath("translator/include_text_translator_handler", $EVC->getCommonProjectName());//@ in case it doens't exist
include $EVC->getUtilPath("sanitize_html_in_post_request", $EVC->getCommonProjectName());

include $EVC->getControllerPath("module", $EVC->getCommonProjectName());
?>
