<?php
namespace Module\Article;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/ArticleDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class ArticleService extends \soa\CommonService {
	private $Article;
	
	private function getArticleHbnObj($b, $options) {
		if (!$this->Article)
			$this->Article = $b->callObject("module/article", "Article", $options);
		
		return $this->Article;
	}
	
	/**
	 * @param (name=data[title], type=varchar, default="", length=1000)  
	 * @param (name=data[sub_title], type=varchar, default="", length=1000)  
	 * @param (name=data[summary], type=longblob, default="")  
	 * @param (name=data[content], type=longblob, default="")  
	 * @param (name=data[published], type=bool, default="0")  
	 * @param (name=data[photo_id], type=bigint, default=0, length=19)
	 * @param (name=data[allow_comments], type=bool, default="1")
	 */
	public function insertArticle($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if (empty($data["published"]))
			$data["published"] = 0;
		
		if (empty($data["photo_id"]))
			$data["photo_id"] = 0;
		
		$data["allow_comments"] = empty($data["allow_comments"]) ? 0 : 1;
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["title"] = addcslashes($data["title"], "\\'");
			$data["sub_title"] = addcslashes($data["sub_title"], "\\'");
			$data["summary"] = addcslashes($data["summary"], "\\'");
			$data["content"] = addcslashes($data["content"], "\\'");
			
			$status = $b->callInsert("module/article", "insert_article", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Article = $this->getArticleHbnObj($b, $options);
			$status = $Article->insert($data, $ids);
			return $status ? $ids["article_id"] : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$status = $b->insertObject("ma_article", array(
					"title" => $data["title"], 
					"sub_title" => $data["sub_title"], 
					"summary" => $data["summary"], 
					"content" => $data["content"], 
					"published" => $data["published"], 
					"photo_id" => $data["photo_id"], 
					"allow_comments" => $data["allow_comments"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/article", "ArticleService.insertArticle", $data, $options);
	}
	
	/**
	 * @param (name=data[article_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[title], type=varchar, length=1000)  
	 * @param (name=data[sub_title], type=varchar, length=1000)  
	 * @param (name=data[summary], type=longblob)  
	 * @param (name=data[content], type=longblob)  
	 * @param (name=data[published], type=bool)  
	 * @param (name=data[photo_id], type=bigint, length=19)
	 * @param (name=data[allow_comments], type=bool) 
	 */
	public function updateArticle($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if (empty($data["published"]))
			$data["published"] = 0;
		
		if (empty($data["photo_id"]))
			$data["photo_id"] = 0;
		
		$data["allow_comments"] = empty($data["allow_comments"]) ? 0 : 1;
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["title"] = addcslashes($data["title"], "\\'");
			$data["sub_title"] = addcslashes($data["sub_title"], "\\'");
			$data["summary"] = addcslashes($data["summary"], "\\'");
			$data["content"] = addcslashes($data["content"], "\\'");
		
			return $b->callUpdate("module/article", "update_article", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Article = $this->getArticleHbnObj($b, $options);
			return $Article->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("ma_article", array(
					"title" => $data["title"], 
					"sub_title" => $data["sub_title"], 
					"summary" => $data["summary"], 
					"content" => $data["content"], 
					"published" => $data["published"], 
					"photo_id" => $data["photo_id"], 
					"allow_comments" => $data["allow_comments"], 
					"modified_date" => $data["modified_date"]
				), array(
					"article_id" => $data["article_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/article", "ArticleService.updateArticle", $data, $options);
	}
	
	/**
	 * @param (name=data[article_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteArticle($data) {
		$article_id = $data["article_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/article", "delete_article", array("article_id" => $article_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Article = $this->getArticleHbnObj($b, $options);
			return $Article->delete($article_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("ma_article", array("article_id" => $article_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/article", "ArticleService.deleteArticle", $data, $options);
	}
	
	/**
	 * @param (name=data[article_id], type=bigint, not_null=1, length=19)  
	 */
	public function getArticle($data) {
		$article_id = $data["article_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/article", "get_article", array("article_id" => $article_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Article = $this->getArticleHbnObj($b, $options);
			return $Article->findById($article_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("ma_article", null, array("article_id" => $article_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/article", "ArticleService.getArticle", $data, $options);
	}
	
	/**
	 * @param (name=data[article_id], type=bigint, not_null=1, length=19)  
	 */
	public function getArticleAllowComments($data) {
		$article_id = $data["article_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/article", "get_article_allow_comments", array("article_id" => $article_id), $options);
			return $result[0]["allow_comments"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Article = $this->getArticleHbnObj($b, $options);
			$result = $Article->findById($article_id, array("attributes" => array("allow_comments")));
			return $result["allow_comments"];
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("ma_article", array("allow_comments"), array("article_id" => $article_id), $options);
			return $result[0]["allow_comments"];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/article", "ArticleService.getArticleAllowComments", $data, $options);
	}
	
	/**
	 * @param (name=data[article_id], type=bigint, not_null=1, length=19)  
	 */
	public function getArticlePublished($data) {
		$article_id = $data["article_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/article", "get_article_published", array("article_id" => $article_id), $options);
			return $result[0]["published"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Article = $this->getArticleHbnObj($b, $options);
			$result = $Article->findById($article_id, array("attributes" => array("published")));
			return $result["published"];
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("ma_article", array("published"), array("article_id" => $article_id), $options);
			return $result[0]["published"];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/article", "ArticleService.getArticlePublished", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][article_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][summary], type=longblob|array)  
	 * @param (name=data[conditions][content], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][allow_comments], type=bool|array) 
	 */
	public function getArticlesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/article", "get_articles_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Article = $this->getArticleHbnObj($b, $options);
				return $Article->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("ma_article", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/article", "ArticleService.getArticlesByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][article_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][summary], type=longblob|array)  
	 * @param (name=data[conditions][content], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 */
	public function countArticlesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/article", "count_articles_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Article = $this->getArticleHbnObj($b, $options);
				return $Article->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("ma_article", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/article", "ArticleService.countArticlesByConditions", $data, $options);
		}
	}
	
	public function getAllArticles($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/article", "get_all_articles", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Article = $this->getArticleHbnObj($b, $options);
			return $Article->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("ma_article", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/article", "ArticleService.getAllArticles", $data, $options);
	}
	
	public function countAllArticles($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/article", "count_all_articles", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Article = $this->getArticleHbnObj($b, $options);
			return $Article->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("ma_article", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/article", "ArticleService.countAllArticles", $data, $options);
	}
	
	/**
	 * @param (name=data[article_ids], type=mixed, not_null=1)  
	 */
	public function getArticlesByIds($data) {
		$article_ids = $data["article_ids"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($article_ids) {
			$article_ids_str = "";//just in case the user tries to hack the sql query. By default all article_id should be numeric.
			$article_ids = is_array($article_ids) ? $article_ids : array($article_ids);
			foreach ($article_ids as $article_id) 
				$article_ids_str .= ($article_ids_str ? ", " : "") . "'" . addcslashes($article_id, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) 
				return $b->callSelect("module/article", "get_articles_by_ids", array("article_ids" => $article_ids_str), $options);
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Article = $this->getArticleHbnObj($b, $options);
				$conditions = array("article_id" => array("operator" => "in", "value" => $article_ids));
				return $Article->find(array("conditions" => $conditions), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				return $b->findObjects("ma_article", null, array("article_id" => array("operator" => "in", "value" => $article_ids)), $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/article", "ArticleService.getArticlesByIds", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][article_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][summary], type=longblob|array)  
	 * @param (name=data[conditions][content], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 */
	public function getArticlesByTags($data) {
		$tags = $data["tags"];
		$object_type_id = $data["object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
				return $b->callSelect("module/article", "get_articles_by_tags", array("tags" => $tags_str, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
				$Article = $this->getArticleHbnObj($b, $options);
				return $Article->callSelect("get_articles_by_tags", array("tags" => $tags_str, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				$sql = ArticleDBDAOServiceUtil::get_articles_by_tags(array("tags" => $tags_str, "object_type_id" => $object_type_id, "conditions" => $cond));
				
				return $b->getSQL($sql, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/article", "ArticleService.getArticlesByTags", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][article_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][summary], type=longblob|array)  
	 * @param (name=data[conditions][content], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 */
	public function countArticlesByTags($data) {
		$tags = $data["tags"];
		$object_type_id = $data["object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
				$result = $b->callSelect("module/article", "count_articles_by_tags", array("tags" => $tags_str, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
				$Article = $this->getArticleHbnObj($b, $options);
				$result = $Article->callSelect("count_articles_by_tags", array("tags" => $tags_str, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				$sql = ArticleDBDAOServiceUtil::count_articles_by_tags(array("tags" => $tags_str, "object_type_id" => $object_type_id, "conditions" => $cond));
				
				$result = $b->getSQL($sql, $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/article", "ArticleService.countArticlesByTags", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[article_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][article_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][summary], type=longblob|array)  
	 * @param (name=data[conditions][content], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 */
	public function getArticlesByObjectAndTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$tags = $data["tags"];
		$article_object_type_id = $data["article_object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
				return $b->callSelect("module/article", "get_articles_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "article_object_type_id" => $article_object_type_id, "conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
				$Article = $this->getArticleHbnObj($b, $options);
				return $Article->callSelect("get_articles_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "article_object_type_id" => $article_object_type_id, "conditions" => $cond), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				$sql = ArticleDBDAOServiceUtil::get_articles_by_object_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "article_object_type_id" => $article_object_type_id, "conditions" => $cond));
				
				return $b->getSQL($sql, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/article", "ArticleService.getArticlesByObjectAndTags", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[article_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][article_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][summary], type=longblob|array)  
	 * @param (name=data[conditions][content], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 */
	public function countArticlesByObjectAndTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$tags = $data["tags"];
		$article_object_type_id = $data["article_object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
				$result = $b->callSelect("module/article", "count_articles_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "article_object_type_id" => $article_object_type_id, "conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
				$Article = $this->getArticleHbnObj($b, $options);
				$result = $Article->callSelect("count_articles_by_object_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "article_object_type_id" => $article_object_type_id, "conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				$sql = ArticleDBDAOServiceUtil::count_articles_by_object_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "article_object_type_id" => $article_object_type_id, "conditions" => $cond));
				
				$result = $b->getSQL($sql, $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/article", "ArticleService.countArticlesByObjectAndTags", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)   
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[article_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][article_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][summary], type=longblob|array)  
	 * @param (name=data[conditions][content], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 */
	public function getArticlesByObjectGroupAndTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$tags = $data["tags"];
		$article_object_type_id = $data["article_object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
				return $b->callSelect("module/article", "get_articles_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "article_object_type_id" => $article_object_type_id, "conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
				$Article = $this->getArticleHbnObj($b, $options);
				return $Article->callSelect("get_articles_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "article_object_type_id" => $article_object_type_id, "conditions" => $cond), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				$sql = ArticleDBDAOServiceUtil::get_articles_by_object_group_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "article_object_type_id" => $article_object_type_id, "conditions" => $cond));
				
				return $b->getSQL($sql, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/article", "ArticleService.getArticlesByObjectGroupAndTags", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[article_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][article_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][summary], type=longblob|array)  
	 * @param (name=data[conditions][content], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 */
	public function countArticlesByObjectGroupAndTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$tags = $data["tags"];
		$article_object_type_id = $data["article_object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
				$result = $b->callSelect("module/article", "count_articles_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "article_object_type_id" => $article_object_type_id, "conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
				$Article = $this->getArticleHbnObj($b, $options);
				$result = $Article->callSelect("count_articles_by_object_group_and_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "article_object_type_id" => $article_object_type_id, "conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				$sql = ArticleDBDAOServiceUtil::count_articles_by_object_group_and_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "article_object_type_id" => $article_object_type_id, "conditions" => $cond));
				
				$result = $b->getSQL($sql, $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/article", "ArticleService.countArticlesByObjectGroupAndTags", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1) 
	 * @param (name=data[conditions][article_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][summary], type=longblob|array)  
	 * @param (name=data[conditions][content], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array) 
	 */
	public function getArticlesWithAllTags($data) {
		$tags = $data["tags"];
		$object_type_id = $data["object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$tags_count = count($tags);
			
			if ($tags_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
					return $b->callSelect("module/article", "get_articles_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
					$Article = $this->getArticleHbnObj($b, $options);
					return $Article->callSelect("get_articles_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
					$sql = ArticleDBDAOServiceUtil::get_articles_with_all_tags(array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id, "conditions" => $cond));
					
					return $b->getSQL($sql, $options);
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/article", "ArticleService.getArticlesWithAllTags", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1) 
	 * @param (name=data[conditions][article_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][summary], type=longblob|array)  
	 * @param (name=data[conditions][content], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array) 
	 */
	public function countArticlesWithAllTags($data) {
		$tags = $data["tags"];
		$object_type_id = $data["object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$tags_count = count($tags);
			
			if ($tags_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
					$result = $b->callSelect("module/article", "count_articles_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
					$Article = $this->getArticleHbnObj($b, $options);
					$result = $Article->callSelect("count_articles_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
					$sql = ArticleDBDAOServiceUtil::count_articles_with_all_tags(array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id, "conditions" => $cond));
					
					$result = $b->getSQL($sql, $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/article", "ArticleService.countArticlesWithAllTags", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[article_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][article_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][summary], type=longblob|array)  
	 * @param (name=data[conditions][content], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 */
	public function getArticlesByObjectWithAllTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$tags = $data["tags"];
		$article_object_type_id = $data["article_object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$tags_count = count($tags);
			
			if ($tags_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
					return $b->callSelect("module/article", "get_articles_by_object_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => $article_object_type_id, "conditions" => $cond), $options);
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
					$Article = $this->getArticleHbnObj($b, $options);
					return $Article->callSelect("get_articles_by_object_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => $article_object_type_id, "conditions" => $cond), $options);
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
					$sql = ArticleDBDAOServiceUtil::get_articles_by_object_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => $article_object_type_id, "conditions" => $cond));
					
					return $b->getSQL($sql, $options);
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/article", "ArticleService.getArticlesByObjectWithAllTags", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[article_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][article_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][summary], type=longblob|array)  
	 * @param (name=data[conditions][content], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 */
	public function countArticlesByObjectWithAllTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$tags = $data["tags"];
		$article_object_type_id = $data["article_object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$tags_count = count($tags);
			
			if ($tags_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
					$result = $b->callSelect("module/article", "count_articles_by_object_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => $article_object_type_id, "conditions" => $cond), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
					$Article = $this->getArticleHbnObj($b, $options);
					$result = $Article->callSelect("count_articles_by_object_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => $article_object_type_id, "conditions" => $cond), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
					$sql = ArticleDBDAOServiceUtil::count_articles_by_object_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => $article_object_type_id, "conditions" => $cond));
					
					$result = $b->getSQL($sql, $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/article", "ArticleService.countArticlesByObjectWithAllTags", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)    
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[article_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][article_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][summary], type=longblob|array)  
	 * @param (name=data[conditions][content], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 */
	public function getArticlesByObjectGroupWithAllTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$tags = $data["tags"];
		$article_object_type_id = $data["article_object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$tags_count = count($tags);
			
			if ($tags_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
					return $b->callSelect("module/article", "get_articles_by_object_group_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => $article_object_type_id, "conditions" => $cond), $options);
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
					$Article = $this->getArticleHbnObj($b, $options);
					return $Article->callSelect("get_articles_by_object_group_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => $article_object_type_id, "conditions" => $cond), $options);
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
					$sql = ArticleDBDAOServiceUtil::get_articles_by_object_group_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => $article_object_type_id, "conditions" => $cond));
					
					return $b->getSQL($sql, $options);
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/article", "ArticleService.getArticlesByObjectGroupWithAllTags", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)   
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[article_object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][article_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][summary], type=longblob|array)  
	 * @param (name=data[conditions][content], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 */
	public function countArticlesByObjectGroupWithAllTags($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$tags = $data["tags"];
		$article_object_type_id = $data["article_object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$tags_count = count($tags);
			
			if ($tags_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
					$result = $b->callSelect("module/article", "count_articles_by_object_group_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => $article_object_type_id, "conditions" => $cond), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
					$Article = $this->getArticleHbnObj($b, $options);
					$result = $Article->callSelect("count_articles_by_object_group_with_all_tags", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => $article_object_type_id, "conditions" => $cond), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
					$sql = ArticleDBDAOServiceUtil::count_articles_by_object_group_with_all_tags(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "tags" => $tags_str, "tags_count" => $tags_count, "article_object_type_id" => $article_object_type_id, "conditions" => $cond));
					
					$result = $b->getSQL($sql, $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/article", "ArticleService.countArticlesByObjectGroupWithAllTags", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[conditions][article_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][summary], type=longblob|array)  
	 * @param (name=data[conditions][content], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 */
	public function getArticlesByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
			return $b->callSelect("module/article", "get_articles_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
			$Article = $this->getArticleHbnObj($b, $options);
			return $Article->callSelect("get_articles_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
			$sql = ArticleDBDAOServiceUtil::get_articles_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
			
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/article", "ArticleService.getArticlesByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[conditions][article_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][summary], type=longblob|array)  
	 * @param (name=data[conditions][content], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 */
	public function countArticlesByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
			$result = $b->callSelect("module/article", "count_articles_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
			$Article = $this->getArticleHbnObj($b, $options);
			$result = $Article->callSelect("count_articles_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
			$sql = ArticleDBDAOServiceUtil::count_articles_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
			
			$result = $b->getSQL($sql, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/article", "ArticleService.countArticlesByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[conditions][article_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][summary], type=longblob|array)  
	 * @param (name=data[conditions][content], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 */
	public function getArticlesByObjectGroup($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
			return $b->callSelect("module/article", "get_articles_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
			$Article = $this->getArticleHbnObj($b, $options);
			return $Article->callSelect("get_articles_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
			$sql = ArticleDBDAOServiceUtil::get_articles_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond));
			
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/article", "ArticleService.getArticlesByObjectGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[conditions][article_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][sub_title], type=varchar|array, length=1000)  
	 * @param (name=data[conditions][summary], type=longblob|array)  
	 * @param (name=data[conditions][content], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)  
	 * @param (name=data[conditions][photo_id], type=bigint|array, length=19) 
	 * @param (name=data[conditions][allow_comments], type=bool|array)
	 */
	public function countArticlesByObjectGroup($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
			$result = $b->callSelect("module/article", "count_articles_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
			$Article = $this->getArticleHbnObj($b, $options);
			$result = $Article->callSelect("count_articles_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
			$sql = ArticleDBDAOServiceUtil::count_articles_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond));
			
			$result = $b->getSQL($sql, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/article", "ArticleService.countArticlesByObjectGroup", $data, $options);
	}
	
	private static function getSQLConditions($conditions, $conditions_join, $key_prefix) {
		$cond = \DB::getSQLConditions($conditions, $conditions_join, $key_prefix);
		return $cond ? $cond : '1=1';
	}
}
?>
