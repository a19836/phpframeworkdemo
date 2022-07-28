<?php
include_once $EVC->getModulePath("article/ArticleUtil", $EVC->getCommonProjectName());
include $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName());

validateModuleUserActivity($EVC, "delete", $module_path);

$brokers = $EVC->getPresentationLayer()->getBrokers();

if (ArticleUtil::deleteArticle($EVC, $_GET["article_id"]) && ArticleUtil::deleteObjectArticlesByArticleId($brokers, $_GET["article_id"])) {
	echo "1";
}
?>
