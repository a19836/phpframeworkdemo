<?php
class ArticleUI {
	
	public static function getArticlesFromSettings($EVC, $settings, $brokers, &$options) {
		$common_project_name = $EVC->getCommonProjectName();
		
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once $EVC->getModulePath("article/ArticleUtil", $common_project_name);
		
		if ($settings["catalog_sort_column"]) {
			$options["sort"][] = array("column" => $settings["catalog_sort_column"], "order" => $settings["catalog_sort_order"]);
		}
		
		$conditions = CommonModuleUI::getConditionsFromSearchValues($settings);
		
		if ($settings["filter_by_published"])
			$conditions["published"] = 1;
		
		//Getting articles
		switch ($settings["articles_type"]) {
			case "all":
				$total = $conditions ? ArticleUtil::countArticlesByConditions($brokers, $conditions, null) : ArticleUtil::countAllArticles($brokers);
				$articles = $conditions ? ArticleUtil::getArticlesByConditions($brokers, $conditions, null, $options) : ArticleUtil::getAllArticles($brokers, $options);
				break;
			case "tags_and":
				$tags = $settings["tags"];
				if ($tags) {
					$total = ArticleUtil::countArticlesWithAllTags($brokers, $tags, $conditions, null);
					$articles = ArticleUtil::getArticlesWithAllTags($brokers, $tags, $conditions, null, $options);
				}
				break;
			case "tags_or":
				$tags = $settings["tags"];
				if ($tags) {
					$total = ArticleUtil::countArticlesByTags($brokers, $tags, $conditions, null);
					$articles = ArticleUtil::getArticlesByTags($brokers, $tags, $conditions, null, $options);
				}
				break;
			case "parent":
				$total = ArticleUtil::countArticlesByObject($brokers, $settings["object_type_id"], $settings["object_id"], $conditions, null);
				$articles = ArticleUtil::getArticlesByObject($brokers, $settings["object_type_id"], $settings["object_id"], $conditions, null, $options);
				break;
			case "parent_group":
				$total = ArticleUtil::countArticlesByObjectGroup($brokers, $settings["object_type_id"], $settings["object_id"], $settings["group"], $conditions, null);
				$articles = ArticleUtil::getArticlesByObjectGroup($brokers, $settings["object_type_id"], $settings["object_id"], $settings["group"], $conditions, null, $options);
				break;
			case "parent_tags_and":
				$tags = $settings["tags"];
				if ($tags) {
					$total = ArticleUtil::countArticlesByObjectWithAllTags($brokers, $settings["object_type_id"], $settings["object_id"], $tags, $conditions, null);
					$articles = ArticleUtil::getArticlesByObjectWithAllTags($brokers, $settings["object_type_id"], $settings["object_id"], $tags, $conditions, null, $options);
				}
				break;
			case "parent_tags_or":
				$tags = $settings["tags"];
				if ($tags) {
					$total = ArticleUtil::countArticlesByObjectAndTags($brokers, $settings["object_type_id"], $settings["object_id"], $tags, $conditions, null);
					$articles = ArticleUtil::getArticlesByObjectAndTags($brokers, $settings["object_type_id"], $settings["object_id"], $tags, $conditions, null, $options);
				}
				break;
			case "parent_group_tags_and":
				$tags = $settings["tags"];
				if ($tags) {
					$total = ArticleUtil::countArticlesByObjectGroupWithAllTags($brokers, $settings["object_type_id"], $settings["object_id"], $settings["group"], $tags, $conditions, null);
					$articles = ArticleUtil::getArticlesByObjectGroupWithAllTags($brokers, $settings["object_type_id"], $settings["object_id"], $settings["group"], $tags, $conditions, null, $options);
				}
				break;
			case "parent_group_tags_or":
				$tags = $settings["tags"];
				if ($tags) {
					$total = ArticleUtil::countArticlesByObjectGroupAndTags($brokers, $settings["object_type_id"], $settings["object_id"], $settings["group"], $tags, $conditions, null);
					$articles = ArticleUtil::getArticlesByObjectGroupAndTags($brokers, $settings["object_type_id"], $settings["object_id"], $settings["group"], $tags, $conditions, null, $options);
				}
				break;
			case "selected":
				$article_ids = $settings["article_ids"];
				if ($article_ids) {
					$total = count($article_ids);
					$items = ArticleUtil::getArticlesByIds($brokers, $article_ids, $options);
				
					$articles = array();
					if (is_array($items) && !empty($items)) {
						$t = count($article_ids);
						for ($i = 0; $i < $t; $i++) {
							foreach ($items as $item) {
								if ($item["article_id"] == $article_ids[$i] && (!$settings["filter_by_published"] || $item["published"])) {
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
