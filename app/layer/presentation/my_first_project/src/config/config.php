<?php
include $EVC->getConfigPath("config", $EVC->getCommonProjectName());

$parts = explode("/", $_SERVER["REQUEST_URI"]);
	if ($parts[1] == "phpframeworkdemo") {
	    $project_url_prefix = $project_protocol . $_SERVER["HTTP_HOST"] . "/phpframeworkdemo/my_first_project/";
	    $project_common_url_prefix = $project_protocol . $_SERVER["HTTP_HOST"] . "/phpframeworkdemo/" . $EVC->getCommonProjectName() . "/";
	}
?>
