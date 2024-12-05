<?php
class ArticleUI {
	
	public static function getArticlesFromSettings($EVC, $settings, $brokers, &$options) {
		$common_project_name = $EVC->getCommonProjectName();
		
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once $EVC->getModulePath("article/ArticleUtil", $common_project_name);
		
		if (!empty($settings["catalog_sort_column"])) {
			$options["sort"][] = array("column" => $settings["catalog_sort_column"], "order" => isset($settings["catalog_sort_order"]) ? $settings["catalog_sort_order"] : null);
		}
		
		$conditions = CommonModuleUI::getConditionsFromSearchValues($settings);
		
		if (!empty($settings["filter_by_published"]))
			$conditions["published"] = 1;
		
		//Getting articles
		$articles_type = isset($settings["articles_type"]) ? $settings["articles_type"] : null;
		$tags = isset($settings["tags"]) ? $settings["tags"] : null;
		$object_type_id = isset($settings["object_type_id"]) ? $settings["object_type_id"] : null;
		$object_id = isset($settings["object_id"]) ? $settings["object_id"] : null;
		$group = isset($settings["group"]) ? $settings["group"] : null;
		$total = $articles = null;
		
		switch ($articles_type) {
			case "all":
				$total = $conditions ? ArticleUtil::countArticlesByConditions($brokers, $conditions, null) : ArticleUtil::countAllArticles($brokers);
				$articles = $conditions ? ArticleUtil::getArticlesByConditions($brokers, $conditions, null, $options) : ArticleUtil::getAllArticles($brokers, $options);
				break;
			case "tags_and":
				if ($tags) {
					$total = ArticleUtil::countArticlesWithAllTags($brokers, $tags, $conditions, null);
					$articles = ArticleUtil::getArticlesWithAllTags($brokers, $tags, $conditions, null, $options);
				}
				break;
			case "tags_or":
				if ($tags) {
					$total = ArticleUtil::countArticlesByTags($brokers, $tags, $conditions, null);
					$articles = ArticleUtil::getArticlesByTags($brokers, $tags, $conditions, null, $options);
				}
				break;
			case "parent":
				$total = ArticleUtil::countArticlesByObject($brokers, $object_type_id, $object_id, $conditions, null);
				$articles = ArticleUtil::getArticlesByObject($brokers, $object_type_id, $object_id, $conditions, null, $options);
				break;
			case "parent_group":
				$total = ArticleUtil::countArticlesByObjectGroup($brokers, $object_type_id, $object_id, $group, $conditions, null);
				$articles = ArticleUtil::getArticlesByObjectGroup($brokers, $object_type_id, $object_id, $group, $conditions, null, $options);
				break;
			case "parent_tags_and":
				if ($tags) {
					$total = ArticleUtil::countArticlesByObjectWithAllTags($brokers, $object_type_id, $object_id, $tags, $conditions, null);
					$articles = ArticleUtil::getArticlesByObjectWithAllTags($brokers, $object_type_id, $object_id, $tags, $conditions, null, $options);
				}
				break;
			case "parent_tags_or":
				if ($tags) {
					$total = ArticleUtil::countArticlesByObjectAndTags($brokers, $object_type_id, $object_id, $tags, $conditions, null);
					$articles = ArticleUtil::getArticlesByObjectAndTags($brokers, $object_type_id, $object_id, $tags, $conditions, null, $options);
				}
				break;
			case "parent_group_tags_and":
				if ($tags) {
					$total = ArticleUtil::countArticlesByObjectGroupWithAllTags($brokers, $object_type_id, $object_id, $group, $tags, $conditions, null);
					$articles = ArticleUtil::getArticlesByObjectGroupWithAllTags($brokers, $object_type_id, $object_id, $group, $tags, $conditions, null, $options);
				}
				break;
			case "parent_group_tags_or":
				if ($tags) {
					$total = ArticleUtil::countArticlesByObjectGroupAndTags($brokers, $object_type_id, $object_id, $group, $tags, $conditions, null);
					$articles = ArticleUtil::getArticlesByObjectGroupAndTags($brokers, $object_type_id, $object_id, $group, $tags, $conditions, null, $options);
				}
				break;
			case "selected":
				$article_ids = isset($settings["article_ids"]) ? $settings["article_ids"] : null;
				if ($article_ids) {
					$total = count($article_ids);
					$items = ArticleUtil::getArticlesByIds($brokers, $article_ids, $options);
				
					$articles = array();
					if (is_array($items) && !empty($items)) {
						$t = count($article_ids);
						for ($i = 0; $i < $t; $i++) {
							foreach ($items as $item) {
								$item_article_id = isset($item["article_id"]) ? $item["article_id"] : null;
								
								if ($item_article_id == $article_ids[$i] && (empty($settings["filter_by_published"]) || !empty($item["published"]))) {
									$articles[] = $item;
									break;
								}
							}
						}
					}
				}
				break;
		}
		
		//get photos
		ArticleUtil::prepareArticlesPhotos($EVC, $articles, false, $brokers);
		
		return array($total, $articles);
	}
}
?>
