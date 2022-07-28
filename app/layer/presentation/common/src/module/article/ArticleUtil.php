<?php
include_once $EVC->getModulePath("tag/TagUtil", $EVC->getCommonProjectName());
include_once $EVC->getModulePath("object/ObjectUtil", $EVC->getCommonProjectName());
include_once $EVC->getModulePath("attachment/AttachmentUtil", $EVC->getCommonProjectName());
include_once $EVC->getModulePath("comment/CommentUtil", $EVC->getCommonProjectName());
include_once __DIR__ . "/ArticleDBDAOUtil.php"; //this file will be automatically generated on this module installation
include_once __DIR__ . "/ObjectArticleDBDAOUtil.php"; //this file will be automatically generated on this module installation

class ArticleUtil {
	
	const ARTICLE_PHOTO_GROUP_ID = 0;
	const ARTICLE_ATTACHMENTS_GROUP_ID = 1;
	const ARTICLE_SUMMARY_HTML_IMAGE_GROUP_ID = 3;
	const ARTICLE_CONTENT_HTML_IMAGE_GROUP_ID = 4;
	
	public static function prepareArticlesPhotos($EVC, &$articles, $no_cache = false, $brokers = array()) {
		if ($articles) {
			$attachment_ids = array();
			$indexes = array();
			
			foreach ($articles as $idx => $article) {
				$photo_id = $article["photo_id"];
				
				if ($photo_id) {
					$attachment_ids[] = $photo_id;
					$indexes[$photo_id] = $idx;
				}
			}
			
			$attachments = AttachmentUtil::getAttachmentsByIds($brokers, $attachment_ids, $no_cache);
			
			if ($attachments) {
				$folder_path = AttachmentUtil::getAttachmentsFolderPath($EVC);
				$url = AttachmentUtil::getAttachmentsFolderUrl($EVC);
				
				foreach ($attachments as $attachment) {
					$path = $attachment["path"];
					
					if ($path) {
						$idx = $indexes[ $attachment["attachment_id"] ];
						
						if ($articles[$idx]) {
							$articles[$idx]["photo_path"] = $folder_path . $path;
							$articles[$idx]["photo_url"] = $url . $path;
						}
					}
				}
			}
		}
	}
	
	//$conditions must be an array containing multiple conditions
	public static function getArticlesByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/article", "ArticleService.getArticlesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callSelect("module/article", "get_articles_by_conditions", array("conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Article = $broker->callObject("module/article", "Article");
					return $Article->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options["conditions_join"] = $conditions_join;
					return $broker->findObjects("ma_article", null, $conditions, $options);
				}
			}
		}
	}
	
	//$conditions must be an array containing multiple conditions
	public static function countArticlesByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/article", "ArticleService.countArticlesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/article", "count_articles_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Article = $broker->callObject("module/article", "Article");
					return $Article->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("ma_article", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	public static function getArticlesByIds($brokers, $article_ids, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$article_ids_str = is_array($article_ids) ? implode(', ', $article_ids) : $article_ids;
			$article_ids_str = str_replace(array("'", "\\"), "", $article_ids_str);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/article", "ArticleService.getArticlesByIds", array("article_ids" => $article_ids, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/article", "get_articles_by_ids", array("article_ids" => $article_ids_str), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Article = $broker->callObject("module/article", "Article");
					$conditions = array("article_id" => array("operator" => "in", "value" => $article_ids));
					return $Article->find(array("conditions" => $conditions), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("ma_article", null, array("article_id" => array("operator" => "in", "value" => $article_ids)), $options);
				}
			}
		}
	}
	
	//$tags is a string containing multiple article tags
	//This method will return the articles that contains at least one tag in $tags
	public static function getArticlesByTags($brokers, $tags, $conditions = null, $conditions_join = null, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/article", "ArticleService.getArticlesByTags", array("tags" => $tags, "object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					return $broker->callSelect("module/article", "get_articles_by_tags", array("tags" => $tags_str, "object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					$Article = $broker->callObject("module/article", "Article");
					return $Article->callSelect("get_articles_by_tags", array("tags" => $tags_str, "object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					$sql = ArticleDBDAOUtil::get_articles_by_tags(array("tags" => $tags_str, "object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond));
					
					return $broker->getSQL($sql, $options);
				}
			}
		}
	}
	
	//$tags is a string containing multiple article tags
	//This method will return the articles that contains at least one tag in $tags
	public static function countArticlesByTags($brokers, $tags, $conditions = null, $conditions_join = null, $no_cache = false) {
		if (is_array($brokers)) {
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/article", "ArticleService.countArticlesByTags", array("tags" => $tags, "object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					$result = $broker->callSelect("module/article", "count_articles_by_tags", array("tags" => $tags_str, "object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					$Article = $broker->callObject("module/article", "Article");
					$result = $Article->callSelect("count_articles_by_tags", array("tags" => $tags_str, "object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					$sql = ArticleDBDAOUtil::count_articles_by_tags(array("tags" => $tags_str, "object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond));
					
					$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
			}
		}
	}
	
	//$tags is a string containing multiple article tags
	//This method will return the articles that contains at least one tag in $tags
	public static function getArticlesByObjectAndTags($brokers, $object_type_id, $object_id, $tags, $conditions = null, $conditions_join = null, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/article", "ArticleService.getArticlesByObjectAndTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					return $broker->callSelect("module/article", "get_articles_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					$Article = $broker->callObject("module/article", "Article");
					return $Article->callSelect("get_articles_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					$sql = ArticleDBDAOUtil::get_articles_by_object_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond));
					
					return $broker->getSQL($sql, $options);
				}
			}
		}
	}
	
	//$tags is a string containing multiple article tags
	//This method will return the articles that contains at least one tag in $tags
	public static function countArticlesByObjectAndTags($brokers, $object_type_id, $object_id, $tags, $conditions = null, $conditions_join = null, $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/article", "ArticleService.countArticlesByObjectAndTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					$result = $broker->callSelect("module/article", "count_articles_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					$Article = $broker->callObject("module/article", "Article");
					$result = $Article->callSelect("count_articles_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					$sql = ArticleDBDAOUtil::count_articles_by_object_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond));
					
					$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
			}
		}
	}
	
	//$tags is a string containing multiple article tags
	//This method will return the articles that contains at least one tag in $tags
	public static function getArticlesByObjectGroupAndTags($brokers, $object_type_id, $object_id, $group = null, $tags, $conditions = null, $conditions_join = null, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$group = is_numeric($group) ? $group : null;
					
					return $broker->callBusinessLogic("module/article", "ArticleService.getArticlesByObjectGroupAndTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					return $broker->callSelect("module/article", "get_articles_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					$Article = $broker->callObject("module/article", "Article");
					return $Article->callSelect("get_articles_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					$sql = ArticleDBDAOUtil::get_articles_by_object_group_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond));
					
					return $broker->getSQL($sql, $options);
				}
			}
		}
	}
	
	//$tags is a string containing multiple article tags
	//This method will return the articles that contains at least one tag in $tags
	public static function countArticlesByObjectGroupAndTags($brokers, $object_type_id, $object_id, $group = null, $tags, $conditions = null, $conditions_join = null, $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$group = is_numeric($group) ? $group : null;
					
					return $broker->callBusinessLogic("module/article", "ArticleService.countArticlesByObjectGroupAndTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					$result = $broker->callSelect("module/article", "count_articles_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					$Article = $broker->callObject("module/article", "Article");
					$result = $Article->callSelect("count_articles_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					$sql = ArticleDBDAOUtil::count_articles_by_object_group_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond));
					
					$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
			}
		}
	}
	
	//$tags is a string containing multiple article tags
	//This method will return the articles that contains all $tags
	public static function getArticlesWithAllTags($brokers, $tags, $conditions = null, $conditions_join = null, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			$tags_count = count($tags);
			
			if ($tags_str) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/article", "ArticleService.getArticlesWithAllTags", array("tags" => $tags, "object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
						return $broker->callSelect("module/article", "get_articles_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						
						$Article = $broker->callObject("module/article", "Article");
						return $Article->callSelect("get_articles_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						$sql = ArticleDBDAOUtil::get_articles_with_all_tags(array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond));
						
						return $broker->getSQL($sql, $options);
					}
				}
			}
		}
	}
	
	public static function countArticlesWithAllTags($brokers, $tags, $conditions = null, $conditions_join = null, $no_cache = false) {
		if (is_array($brokers)) {
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			$tags_count = count($tags);
			
			if ($tags_str) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/article", "ArticleService.countArticlesWithAllTags", array("tags" => $tags, "object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						
						$result = $broker->callSelect("module/article", "count_articles_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						
						$Article = $broker->callObject("module/article", "Article");
						$result = $Article->callSelect("count_articles_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						$sql = ArticleDBDAOUtil::count_articles_with_all_tags(array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond));
						
						$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
				}
			}
		}
	}
	
	//$tags is a string containing multiple article tags
	//This method will return the articles that contains all $tags
	public static function getArticlesByObjectWithAllTags($brokers, $object_type_id, $object_id, $tags, $conditions = null, $conditions_join = null, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			$tags_count = count($tags);
			
			if ($tags_str) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/article", "ArticleService.getArticlesByObjectWithAllTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						
						return $broker->callSelect("module/article", "get_articles_by_object_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						
						$Article = $broker->callObject("module/article", "Article");
						return $Article->callSelect("get_articles_by_object_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						$sql = ArticleDBDAOUtil::get_articles_by_object_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond));
						
						return $broker->getSQL($sql, $options);
					}
				}
			}
		}
	}
	
	public static function countArticlesByObjectWithAllTags($brokers, $object_type_id, $object_id, $tags, $conditions = null, $conditions_join = null, $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			$tags_count = count($tags);
			
			if ($tags_str) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/article", "ArticleService.countArticlesByObjectWithAllTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						
						$result = $broker->callSelect("module/article", "count_articles_by_object_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						
						$Article = $broker->callObject("module/article", "Article");
						$result = $Article->callSelect("count_articles_by_object_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						$sql = ArticleDBDAOUtil::count_articles_by_object_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond));
						
						$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
				}
			}
		}
	}
	
	//$tags is a string containing multiple article tags
	//This method will return the articles that contains all $tags
	public static function getArticlesByObjectGroupWithAllTags($brokers, $object_type_id, $object_id, $group = null, $tags, $conditions = null, $conditions_join = null, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			$tags_count = count($tags);
			
			if ($tags_str) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$group = is_numeric($group) ? $group : null;
					
						return $broker->callBusinessLogic("module/article", "ArticleService.getArticlesByObjectGroupWithAllTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						
						return $broker->callSelect("module/article", "get_articles_by_object_group_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						
						$Article = $broker->callObject("module/article", "Article");
						return $Article->callSelect("get_articles_by_object_group_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						$sql = ArticleDBDAOUtil::get_articles_by_object_group_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond));
						
						return $broker->getSQL($sql, $options);
					}
				}
			}
		}
	}
	
	public static function countArticlesByObjectGroupWithAllTags($brokers, $object_type_id, $object_id, $group = null, $tags, $conditions = null, $conditions_join = null, $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			$tags_count = count($tags);
			
			if ($tags_str) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$group = is_numeric($group) ? $group : null;
					
						return $broker->callBusinessLogic("module/article", "ArticleService.countArticlesByObjectGroupWithAllTags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						
						$result = $broker->callSelect("module/article", "count_articles_by_object_group_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						
						$Article = $broker->callObject("module/article", "Article");
						$result = $Article->callSelect("count_articles_by_object_group_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						$sql = ArticleDBDAOUtil::count_articles_by_object_group_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => ObjectUtil::ARTICLE_OBJECT_TYPE_ID, "conditions" => $cond));
						
						$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
				}
			}
		}
	}
	
	public static function getAllArticles($brokers, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => $options);
					return $broker->callBusinessLogic("module/article", "ArticleService.getAllArticles", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/article", "get_all_articles", null, $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Article = $broker->callObject("module/article", "Article");
					return $Article->find(null, $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("ma_article", null, null, $options);
				}
			}
		}
	}
	
	public static function countAllArticles($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/article", "ArticleService.countAllArticles", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/article", "count_all_articles", null, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Article = $broker->callObject("module/article", "Article");
					return $Article->count(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("ma_article", null, array("no_cache" => $no_cache));
				}
			}
		}
	}
	
	public static function getArticleProperties($EVC, $article_id, $no_cache = false, $brokers = array()) {
		$data = null;
		
		$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
		if (is_array($brokers) && is_numeric($article_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = $broker->callBusinessLogic("module/article", "ArticleService.getArticle", array("article_id" => $article_id), array("no_cache" => $no_cache));
					break;
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data = $broker->callSelect("module/article", "get_article", array("article_id" => $article_id), array("no_cache" => $no_cache));
					$data = $data[0];
					break;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Article = $broker->callObject("module/article", "Article", array("no_cache" => $no_cache));
					$data = $Article->findById($article_id, null, array("no_cache" => $no_cache));
					break;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$data = $broker->findObjects("ma_article", null, array("article_id" => $article_id), array("no_cache" => $no_cache));
					$data = $data[0];
					break;
				}
			}
		
			if ($data) {
				$data["tags"] = TagUtil::getObjectTagsString($broker, ObjectUtil::ARTICLE_OBJECT_TYPE_ID, $article_id);
				
				if ($data["photo_id"]) {
					$attachment_data = AttachmentUtil::getAttachmentsByConditions($brokers, array("attachment_id" => $data["photo_id"]), null, null, $no_cache);
					$data["photo_path"] = AttachmentUtil::getAttachmentsFolderPath($EVC) . $attachment_data[0]["path"];
					$data["photo_url"] = AttachmentUtil::getAttachmentsFolderUrl($EVC) . $attachment_data[0]["path"];
				}
			}
		}
		
		return $data;
	}
	
	public static function setArticleProperties($EVC, $article_id, $data, $file = null, $brokers = array(), $is_local_file = false) {
		$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
		if (is_array($brokers)) {
			$is_update = false;
			if ($article_id) {
				$is_update = true;
				$data["article_id"] = $article_id;
			}
			
			$article_id = self::insertOrUpdateArticle($brokers, $data);
			
			if ($article_id) {
				$status = true;
				
				//delete photo
				if ($is_update) {
					$db_data = self::getArticleProperties($EVC, $article_id, $no_cache);
					
					$delete_photo = !$data["photo_id"] || $data["photo_id"] != $db_data["photo_id"]; //bc of the default_value that could be set
					
					if ($delete_photo)
						AttachmentUtil::deleteFileByObject($EVC, ObjectUtil::ARTICLE_OBJECT_TYPE_ID, $article_id, self::ARTICLE_PHOTO_GROUP_ID, $brokers);
				}
				
				if ($file["tmp_name"]) {
					//insert or update photo
					$photo_id = AttachmentUtil::replaceObjectFile($EVC, $file, $data["photo_id"], ObjectUtil::ARTICLE_OBJECT_TYPE_ID, $article_id, self::ARTICLE_PHOTO_GROUP_ID, 0, $brokers, $is_local_file);
					
					if (!$photo_id) {
						$status = false;
					}
					else if ($photo_id != $data["photo_id"]) {//update photo_id in article if different
						$data["article_id"] = $article_id;//in case be an insert. Otherwise it inserts 2 records into DB.
						$data["photo_id"] = $photo_id;
						$article_id = self::insertOrUpdateArticle($brokers, $data);
					}
				}
				
				return $status ? $article_id : false;
			}
		}
	}
	
	public static function insertOrUpdateArticle($brokers, $data) {
		if (is_array($brokers)) {
			$status = false;
					
			$data["published"] = empty($data["published"]) ? 0 : 1;
			$data["photo_id"] = empty($data["photo_id"]) ? 0 : $data["photo_id"];
			$data["allow_comments"] = empty($data["allow_comments"]) ? 0 : 1;
			
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
			
			$article_id = $data["article_id"];
			$is_insert = !$article_id;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					if ($is_insert) {
						$article_id = $broker->callBusinessLogic("module/article", "ArticleService.insertArticle", $data);
						$status = $article_id ? true : false;
					}
					else {
						$status = $broker->callBusinessLogic("module/article", "ArticleService.updateArticle", $data);
					}
					break;
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["title"] = addcslashes($data["title"], "\\'");
					$data["sub_title"] = addcslashes($data["sub_title"], "\\'");
					$data["summary"] = addcslashes($data["summary"], "\\'");
					$data["content"] = addcslashes($data["content"], "\\'");
					$data["published"] = is_numeric($data["published"]) ? $data["published"] : 0;
					$data["photo_id"] = is_numeric($data["photo_id"]) ? $data["photo_id"] : 0;
					$data["allow_comments"] = is_numeric($data["allow_comments"]) ? $data["allow_comments"] : 1;
					
					if ($is_insert) {
						$status = $broker->callInsert("module/article", "insert_article", $data);
						$article_id = $broker->getInsertedId();
					}
					else if(is_numeric($article_id)) {
						$status = $broker->callUpdate("module/article", "update_article", $data);
					}
					break;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Article = $broker->callObject("module/article", "Article", array("no_cache" => true));
					$status = $Article->insertOrUpdate($data, $ids);
				
					if ($status && !$article_id && $ids["article_id"])
						$article_id = $ids["article_id"];
					
					break;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$article_data = array(
						"title" => $data["title"], 
						"sub_title" => $data["sub_title"], 
						"summary" => $data["summary"], 
						"content" => $data["content"], 
						"published" => $data["published"], 
						"photo_id" => $data["photo_id"], 
						"allow_comments" => $data["allow_comments"], 
						"created_date" => $data["created_date"], 
						"modified_date" => $data["modified_date"]
					);
					
					if ($is_insert) {
						$status = $broker->insertObject("ma_article", $article_data);
						$article_id = $broker->getInsertedId();
					}
					else if(is_numeric($article_id)) {
						$status = $broker->updateObject("ma_article", $article_data, array("article_id" => $article_id));
					}
					break;
				}
			}
			
			if ($status && $article_id) {
				$status = TagUtil::updateObjectTags($broker, $data["tags"], ObjectUtil::ARTICLE_OBJECT_TYPE_ID, $article_id) && self::updateObjectArticlesByArticleId(array($broker), $article_id, $data);
			
				return $status ? $article_id : false;
			}
		}	
	}
	
	public static function deleteArticle($EVC, $article_id, $brokers = array()) {
		$status = false;
			
		$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
		if (is_array($brokers) && is_numeric($article_id)) {
			$status = AttachmentUtil::deleteFileByObject($EVC, ObjectUtil::ARTICLE_OBJECT_TYPE_ID, $article_id, null, $brokers);
			
			if ($status) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$status = $broker->callBusinessLogic("module/article", "ArticleService.deleteArticle", array("article_id" => $article_id));
						break;
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$status = $broker->callDelete("module/article", "delete_article", array("article_id" => $article_id));
						break;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Article = $broker->callObject("module/article", "Article");
						$status = $Article->delete($article_id);
						break;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("ma_article", array("article_id" => $article_id));
					}
				}
			
				if ($status) {
					//Related attachments are already deleted above through the AttachmentUtil::deleteFileByObject method
					$status = self::deleteObjectArticlesByArticleId(array($broker), $article_id) 
					  && TagUtil::deleteObjectTagsByObject(array($broker), ObjectUtil::ARTICLE_OBJECT_TYPE_ID, $article_id) 
					  && CommentUtil::deleteCommentsByObject(array($broker), ObjectUtil::ARTICLE_OBJECT_TYPE_ID, $article_id); 
				}
			}
		}
		
		return $status;
	}
	
	public static function getArticlesByObject($brokers, $object_type_id, $object_id, $conditions = null, $conditions_join = null, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/article", "ArticleService.getArticlesByObject", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					return $broker->callSelect("module/article", "get_articles_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					$Article = $broker->callObject("module/article", "Article");
					return $Article->callSelect("get_articles_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					$sql = ArticleDBDAOUtil::get_articles_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
				
					return $broker->getSQL($sql, $options);
				}
			}
		}
	}
	
	public static function countArticlesByObject($brokers, $object_type_id, $object_id, $conditions = null, $conditions_join = null, $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/article", "ArticleService.countArticlesByObject", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					$result = $broker->callSelect("module/article", "count_articles_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					$Article = $broker->callObject("module/article", "Article");
					$result = $Article->callSelect("count_articles_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					$sql = ArticleDBDAOUtil::count_articles_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
				
					$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
			}
		}
	}

	public static function getArticlesByObjectGroup($brokers, $object_type_id, $object_id, $group = null, $conditions = null, $conditions_join = null, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$group = is_numeric($group) ? $group : null;
					
					return $broker->callBusinessLogic("module/article", "ArticleService.getArticlesByObjectGroup", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					return $broker->callSelect("module/article", "get_articles_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					$Article = $broker->callObject("module/article", "Article");
					return $Article->callSelect("get_articles_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					$sql = ArticleDBDAOUtil::get_articles_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond));
				
					return $broker->getSQL($sql, $options);
				}
			}
		}
	}
	
	public static function countArticlesByObjectGroup($brokers, $object_type_id, $object_id, $group = null, $conditions = null, $conditions_join = null, $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$group = is_numeric($group) ? $group : null;
					
					return $broker->callBusinessLogic("module/article", "ArticleService.countArticlesByObjectGroup", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					$result = $broker->callSelect("module/article", "count_articles_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					$Article = $broker->callObject("module/article", "Article");
					$result = $Article->callSelect("count_articles_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					$sql = ArticleDBDAOUtil::count_articles_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond));
				
					$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
			}
		}
	}
	
	private static function getSQLConditions($conditions, $conditions_join, $key_prefix) {
		$cond = DB::getSQLConditions($conditions, $conditions_join, $key_prefix);
		return $cond ? $cond : '1=1';
	}
	
	/* OBJECT ARTICLE FUNCTIONS */

	public static function insertObjectArticle($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["article_id"]) && is_numeric($data["object_type_id"]) && is_numeric($data["object_id"])) {
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					
					return $broker->callBusinessLogic("module/article", "ObjectArticleService.insertObjectArticle", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : 0;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : 0;
					
					return $broker->callInsert("module/article", "insert_object_article", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
					
					$ObjectArticle = $broker->callObject("module/article", "ObjectArticle");
					return $ObjectArticle->insert($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
					
					return $broker->insertObject("ma_object_article", array(
							"article_id" => $data["article_id"], 
							"object_type_id" => $data["object_type_id"], 
							"object_id" => $data["object_id"], 
							"group" => $data["group"], 
							"order" => $data["order"], 
							"created_date" => $data["created_date"], 
							"modified_date" => $data["modified_date"]
						));
				}
			}
		}
	}

	public static function updateObjectArticle($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["new_article_id"]) && is_numeric($data["new_object_type_id"]) && is_numeric($data["new_object_id"]) && is_numeric($data["old_article_id"]) && is_numeric($data["old_object_type_id"]) && is_numeric($data["old_object_id"])) {
			$data["modified_date"] = date("Y-m-d H:i:s");
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					
					return $broker->callBusinessLogic("module/article", "ObjectArticleService.updateObjectArticle", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : 0;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : 0;
					
					return $broker->callUpdate("module/article", "update_object_article", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
					
					$ObjectArticle = $broker->callObject("module/article", "ObjectArticle");
					return $ObjectArticle->updatePrimaryKeys($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
					
					return $broker->updateObject("ma_object_article", array(
							"article_id" => $data["new_article_id"], 
							"object_type_id" => $data["new_object_type_id"], 
							"object_id" => $data["new_object_id"], 
							"group" => $data["group"], 
							"order" => $data["order"], 
							"modified_date" => $data["modified_date"]
						), array(
							"article_id" => $data["old_article_id"], 
							"object_type_id" => $data["old_object_type_id"], 
							"object_id" => $data["old_object_id"], 
						));
				}
			}
		}
	}
	
	private static function updateObjectArticlesByArticleId($brokers, $article_id, $data) {
		if (is_array($brokers) && is_numeric($article_id)) {
			if (self::deleteObjectArticlesByArticleId($brokers, $article_id)) {
				$status = true;
				$object_articles = is_array($data["object_articles"]) ? $data["object_articles"] : array();
				
				foreach ($object_articles as $object_article) {
					if (is_numeric($object_article["object_type_id"]) && is_numeric($object_article["object_id"])) {
						$object_article["article_id"] = $article_id;
					
						if (!self::insertObjectArticle($brokers, $object_article)) {
							$status = false;
						}
					}
				}
				
				return $status;
			}
		}
	}
	
	public static function updateObjectArticleOrder($brokers, $article_id, $object_type_id, $object_id, $order) {
		if (is_array($brokers) && is_numeric($article_id) && is_numeric($object_type_id) && is_numeric($object_id) && is_numeric($order)) {
			$data = array("article_id" => $article_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "order" => $order, "modified_date" => date("Y-m-d H:i:s"));
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/article", "ObjectArticleService.updateObjectArticleOrder", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callUpdate("module/article", "update_object_article_order", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectArticle = $broker->callObject("module/article", "ObjectArticle");
					return $ObjectArticle->update($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->updateObject("ma_object_article", array(
							"order" => $data["order"], 
							"modified_date" => $data["modified_date"]
						), array(
							"article_id" => $data["article_id"], 
							"object_type_id" => $data["object_type_id"], 
							"object_id" => $data["object_id"], 
						));
				}
			}
		}
	}
	
	public static function updateObjectArticleGroupOrder($brokers, $article_id, $object_type_id, $object_id, $group, $order) {
		if (is_array($brokers) && is_numeric($article_id) && is_numeric($object_type_id) && is_numeric($object_id) && is_numeric($group) && is_numeric($order)) {
			$data = array("article_id" => $article_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "order" => $order, "modified_date" => date("Y-m-d H:i:s"));
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/article", "ObjectArticleService.updateObjectArticleGroupOrder", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callUpdate("module/article", "update_object_article_group_order", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectArticle = $broker->callObject("module/article", "ObjectArticle");
					$data = array(
						"attributes" => array(
							"order" => $data["order"], 
							"modified_date" => $data["modified_date"]
						),
						"conditions" => array(
							"article_id" => $data["article_id"], 
							"object_type_id" => $data["object_type_id"], 
							"object_id" => $data["object_id"], 
							"group" => $data["group"]
						),
					);
					return $ObjectArticle->updateByConditions($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->updateObject("ma_object_article", array(
							"order" => $data["order"], 
							"modified_date" => $data["modified_date"]
						), array(
							"article_id" => $data["article_id"], 
							"object_type_id" => $data["object_type_id"], 
							"object_id" => $data["object_id"], 
							"group" => $data["group"]
						));
				}
			}
		}
	}

	public static function deleteObjectArticle($brokers, $article_id, $object_type_id, $object_id) {
		if (is_array($brokers) && is_numeric($article_id) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$data = array("article_id" => $article_id, "object_type_id" => $object_type_id, "object_id" => $object_id);
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/article", "ObjectArticleService.deleteObjectArticle", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/article", "delete_object_article", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectArticle = $broker->callObject("module/article", "ObjectArticle");
					return $ObjectArticle->deleteByConditions(array("conditions" => $data));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("ma_object_article", $data);
				}
			}
		}
	}

	public static function deleteObjectArticlesByArticleId($brokers, $article_id) {
		if (is_array($brokers) && is_numeric($article_id)) {
			$data = array("article_id" => $article_id);
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/article", "ObjectArticleService.deleteObjectArticlesByArticleId", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/article", "delete_object_articles_by_article_id", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectArticle = $broker->callObject("module/article", "ObjectArticle");
					return $ObjectArticle->deleteByConditions(array("conditions" => $data));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("ma_object_article", $data);
				}
			}
		}
	}

	public static function deleteObjectArticlesByConditions($brokers, $conditions, $conditions_join) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/article", "ObjectArticleService.deleteObjectArticlesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callDelete("module/article", "delete_object_articles_by_conditions", array("conditions" => $cond));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectArticle = $broker->callObject("module/article", "ObjectArticle");
					return $ObjectArticle->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $conditions_join));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("ma_object_article", $conditions, array("conditions_join" => $conditions_join));
				}
			}
		}
	}

	public static function getObjectArticle($brokers, $article_id, $object_type_id, $object_id, $no_cache = false) {
		if (is_array($brokers) && is_numeric($article_id) && is_numeric($object_type_id) && is_numeric($object_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/article", "ObjectArticleService.getObjectArticle", array("article_id" => $article_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/article", "get_object_article", array("article_id" => $article_id, "object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
					return $result[0];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectArticle = $broker->callObject("module/article", "ObjectArticle");
					return $ObjectArticle->findById(array("article_id" => $article_id, "object_type_id" => $object_type_id, "object_id" => $object_id), null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$result = $broker->findObjects("ma_object_article", null, array("article_id" => $article_id, "object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
					return $result[0];
				}
			}
		}
	}

	//$conditions must be an array containing multiple conditions
	public static function getObjectArticlesByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/article", "ObjectArticleService.getObjectArticlesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callSelect("module/article", "get_object_articles_by_conditions", array("conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectArticle = $broker->callObject("module/article", "ObjectArticle");
					return $ObjectArticle->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options = $options ? $options : array();
					$options["conditions_join"] = $conditions_join;
					return $broker->findObjects("ma_object_article", null, $conditions, $options);
				}
			}
		}
	}

	//$conditions must be an array containing multiple conditions
	public static function countObjectArticlesByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/article", "ObjectArticleService.countObjectArticlesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/article", "count_object_articles_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectArticle = $broker->callObject("module/article", "ObjectArticle");
					return $ObjectArticle->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("ma_object_article", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}

	public static function getAllObjectArticles($brokers, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => $options);
					return $broker->callBusinessLogic("module/article", "ObjectArticleService.getAllObjectArticles", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/article", "get_all_object_articles", null, $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectArticle = $broker->callObject("module/article", "ObjectArticle");
					return $ObjectArticle->find(null, $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("ma_object_article", null, null, $options);
				}
			}
		}
	}

	public static function countAllObjectArticles($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/article", "ObjectArticleService.countAllObjectArticles", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/article", "count_all_object_articles", null, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectArticle = $broker->callObject("module/article", "ObjectArticle");
					return $ObjectArticle->count(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("ma_object_article", null, array("no_cache" => $no_cache));
				}
			}
		}
	}

	public static function getObjectArticlesByArticleId($brokers, $article_id, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($article_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("article_id" => $article_id, "options" => $options);
					return $broker->callBusinessLogic("module/article", "ObjectArticleService.getObjectArticlesByArticleId", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/article", "get_object_articles_by_article_id", array("article_id" => $article_id), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectArticle = $broker->callObject("module/article", "ObjectArticle");
					return $ObjectArticle->find(array("conditions" => array("article_id" => $article_id)), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("ma_object_article", null, array("article_id" => $article_id), $options);
				}
			}
		}
	}

	public static function countObjectArticlesByArticleId($brokers, $article_id, $no_cache = false) {
		if (is_array($brokers) && is_numeric($article_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("article_id" => $article_id, "options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/article", "ObjectArticleService.countObjectArticlesByArticleId", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/article", "count_object_articles_by_article_id", array("article_id" => $article_id), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectArticle = $broker->callObject("module/article", "ObjectArticle");
					return $ObjectArticle->count(array("conditions" => array("article_id" => $article_id)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("ma_object_article", array("article_id" => $article_id), array("no_cache" => $no_cache));
				}
			}
		}
	}
}
?>
