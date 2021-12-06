<?php
include_once $EVC->getModulePath("article/ArticleUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (ArticleUtil::deleteObjectArticle($brokers, $_GET["article_id"], $_GET["object_type_id"], $_GET["object_id"])) {
	echo "1";
}
?>
