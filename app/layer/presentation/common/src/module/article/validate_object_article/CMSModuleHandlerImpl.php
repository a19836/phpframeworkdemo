<?php
namespace CMSModule\article\validate_object_article;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include_once $EVC->getModulePath("common/ObjectToObjectValidationHandler", $common_project_name);
		include_once $EVC->getModulePath("article/ArticleUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$article_ids = is_array($settings["article_id"]) ? $settings["article_id"] : array($settings["article_id"]);
		
		foreach ($article_ids as $article_id) {
			if (!is_numeric($article_id))
				$status = false;
			else {
				if (is_numeric($settings["group"]))
					$result = \ArticleUtil::getObjectArticlesByConditions($brokers, array(
						"article_id" => $article_id, 
						"object_type_id" => $settings["object_type_id"], 
						"object_id" => $settings["object_id"],
						"group" => $settings["group"],
					), null);
				else
					$result = \ArticleUtil::getObjectArticle($brokers, $article_id, $settings["object_type_id"], $settings["object_id"]);
		
				$status = !empty($result);
			}
			
			if (!$status)
				break;
		}
		
		return \ObjectToObjectValidationHandler::validate($EVC, $status, $settings);
	}
}
?>
