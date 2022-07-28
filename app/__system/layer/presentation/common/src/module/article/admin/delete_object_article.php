<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("article/ArticleUtil", $common_project_name);
	
	if (ArticleUtil::deleteObjectArticle($brokers, $_GET["article_id"], $_GET["object_type_id"], $_GET["object_id"])) {
		echo "1";
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
