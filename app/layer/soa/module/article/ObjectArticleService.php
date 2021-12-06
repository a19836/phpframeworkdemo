<?php
namespace Module\Article;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/ObjectArticleDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class ObjectArticleService extends \soa\CommonService {
	private $ObjectArticle;
	
	private function getObjectArticleHbnObj($b, $options) {
		if (!$this->ObjectArticle)
			$this->ObjectArticle = $b->callObject("module/article", "ObjectArticle", $options);
		
		return $this->ObjectArticle;
	}
	
	/**
	 * @param (name=data[article_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[order], type=smallint, default=0)
	 */
	public function insertObjectArticle($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callInsert("module/article", "insert_object_article", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectArticle = $this->getObjectArticleHbnObj($b, $options);
			return $ObjectArticle->insert($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->insertObject("ma_object_article", array(
					"article_id" => $data["article_id"], 
					"object_type_id" => $data["object_type_id"], 
					"object_id" => $data["object_id"], 
					"group" => $data["group"], 
					"order" => $data["order"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/article", "ObjectArticleService.insertObjectArticle", $data, $options);
	}
	
	/**
	 * @param (name=data[new_article_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_article_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[order], type=smallint, default=0)
	 */
	public function updateObjectArticle($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/article", "update_object_article", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectArticle = $this->getObjectArticleHbnObj($b, $options);
			return $ObjectArticle->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("ma_object_article", array(
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
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/article", "ObjectArticleService.updateObjectArticle", $data, $options);
	}
	
	/**
	 * @param (name=data[new_article_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_article_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 */
	public function updateObjectArticleIds($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/article", "update_object_article_ids", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectArticle = $this->getObjectArticleHbnObj($b, $options);
			return $ObjectArticle->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("ma_object_article", array(
					"article_id" => $data["new_article_id"], 
					"object_type_id" => $data["new_object_type_id"], 
					"object_id" => $data["new_object_id"], 
					"modified_date" => $data["modified_date"]
				), array(
					"article_id" => $data["old_article_id"], 
					"object_type_id" => $data["old_object_type_id"], 
					"object_id" => $data["old_object_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/article", "ObjectArticleService.updateObjectArticleIds", $data, $options);
	}
	
	/**
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 */
	public function changeObjectArticlesObjectIds($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/article", "change_object_articles_object_ids", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectArticle = $this->getObjectArticleHbnObj($b, $options);
			return $ObjectArticle->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("ma_object_article", array(
					"object_type_id" => $data["new_object_type_id"], 
					"object_id" => $data["new_object_id"], 
					"modified_date" => $data["modified_date"]
				), array(
					"object_type_id" => $data["old_object_type_id"], 
					"object_id" => $data["old_object_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/article", "ObjectArticleService.changeObjectArticlesObjectIds", $data, $options);
	}
	
	/**
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[parent_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[parent_object_id], type=bigint, not_null=1, length=19)
	 */
	public function changeObjectArticlesObjectIdsOfParentObject($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/article", "change_object_articles_object_ids_of_parent_object", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectArticle = $this->getObjectArticleHbnObj($b, $options);
			return $ObjectArticle->callUpdate("change_object_articles_object_ids_of_parent_object", $data, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = ObjectArticleDBDAOServiceUtil::change_object_articles_object_ids_of_parent_object($data);
			return $b->setSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/article", "ObjectArticleService.changeObjectArticlesObjectIdsOfParentObject", $data, $options);
	}
	
	/**
	 * @param (name=data[article_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[order], type=smallint, default=0)
	 */
	public function updateObjectArticleOrder($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/article", "update_object_article_order", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectArticle = $this->getObjectArticleHbnObj($b, $options);
			return $ObjectArticle->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("ma_object_article", array(
					"order" => $data["order"], 
					"modified_date" => $data["modified_date"]
				), array(
					"article_id" => $data["article_id"], 
					"object_type_id" => $data["object_type_id"], 
					"object_id" => $data["object_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/article", "ObjectArticleService.updateObjectArticleOrder", $data, $options);
	}
	
	/**
	 * @param (name=data[article_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[order], type=smallint, default=0)
	 */
	public function updateObjectArticleGroupOrder($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/article", "update_object_article_group_order", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectArticle = $this->getObjectArticleHbnObj($b, $options);
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
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("ma_object_article", array(
					"order" => $data["order"], 
					"modified_date" => $data["modified_date"]
				), array(
					"article_id" => $data["article_id"], 
					"object_type_id" => $data["object_type_id"], 
					"object_id" => $data["object_id"], 
					"group" => $data["group"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/article", "ObjectArticleService.updateObjectArticleGroupOrder", $data, $options);
	}
	
	/**
	 * @param (name=data[article_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteObjectArticle($data) {
		$article_id = $data["article_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/article", "delete_object_article", array("article_id" => $article_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectArticle = $this->getObjectArticleHbnObj($b, $options);
			return $ObjectArticle->delete(array("article_id" => $article_id, "object_type_id" => $object_type_id, "object_id" => $object_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("ma_object_article", array("article_id" => $article_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/article", "ObjectArticleService.deleteObjectArticle", $data, $options);
	}
	
	/**
	 * @param (name=data[article_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteObjectArticlesByArticleId($data) {
		$article_id = $data["article_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/article", "delete_object_articles_by_article_id", array("article_id" => $article_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectArticle = $this->getObjectArticleHbnObj($b, $options);
			$conditions = array("article_id" => $article_id);
			return $ObjectArticle->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("ma_object_article", array("article_id" => $article_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/article", "ObjectArticleService.deleteObjectArticlesByArticleId", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteObjectArticlesByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/article", "delete_object_articles_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectArticle = $this->getObjectArticleHbnObj($b, $options);
			$conditions = array("object_type_id" => $object_type_id, "object_id" => $object_id);
			return $ObjectArticle->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("ma_object_article", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/article", "ObjectArticleService.deleteObjectArticlesByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][article_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function deleteObjectArticlesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callDelete("module/article", "delete_object_articles_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectArticle = $this->getObjectArticleHbnObj($b, $options);
				return $ObjectArticle->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]));
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->deleteObject("ma_object_article", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/article", "ObjectArticleService.deleteObjectArticlesByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[article_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function getObjectArticle($data) {
		$article_id = $data["article_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/article", "get_object_article", array("article_id" => $article_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectArticle = $this->getObjectArticleHbnObj($b, $options);
			return $ObjectArticle->findById(array("article_id" => $article_id, "object_type_id" => $object_type_id, "object_id" => $object_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("ma_object_article", null, array("article_id" => $article_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/article", "ObjectArticleService.getObjectArticle", $data, $options);
	}
	
	/**
	 * @param (name=data[article_id], type=bigint, not_null=1, length=19)
	 */
	public function getObjectArticlesByArticleId($data) {
		$article_id = $data["article_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/article", "get_object_articles_by_article_id", array("article_id" => $article_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectArticle = $this->getObjectArticleHbnObj($b, $options);
			$conditions = array("article_id" => $article_id);
			return $ObjectArticle->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("ma_object_article", null, array("article_id" => $article_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/article", "ObjectArticleService.getObjectArticlesByArticleId", $data, $options);
	}
	
	/**
	 * @param (name=data[article_id], type=bigint, not_null=1, length=19)
	 */
	public function countObjectArticlesByArticleId($data) {
		$article_id = $data["article_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/article", "count_object_articles_by_article_id", array("article_id" => $article_id), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectArticle = $this->getObjectArticleHbnObj($b, $options);
			return $ObjectArticle->count(array("article_id" => $article_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("ma_object_article", array("article_id" => $article_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/article", "ObjectArticleService.countObjectArticlesByArticleId", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function getObjectArticlesByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callSelect("module/article", "get_object_articles_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectArticle = $this->getObjectArticleHbnObj($b, $options);
			$conditions = array("object_type_id" => $object_type_id, "object_id" => $object_id);
			return $ObjectArticle->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("ma_object_article", null, array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/article", "ObjectArticleService.getObjectArticlesByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][article_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function getObjectArticlesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/article", "get_object_articles_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectArticle = $this->getObjectArticleHbnObj($b, $options);
				return $ObjectArticle->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("ma_object_article", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/article", "ObjectArticleService.getObjectArticlesByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][article_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function countObjectArticlesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/article", "count_object_articles_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$ObjectArticle = $this->getObjectArticleHbnObj($b, $options);
				return $ObjectArticle->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("ma_object_article", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/article", "ObjectArticleService.countObjectArticlesByConditions", $data, $options);
		}
	}
	
	public function getAllObjectArticles($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/article", "get_all_object_articles", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectArticle = $this->getObjectArticleHbnObj($b, $options);
			return $ObjectArticle->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("ma_object_article", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/article", "ObjectArticleService.getAllObjectArticles", null, $options);
	}
	
	public function countAllObjectArticles($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/article", "count_all_object_articles", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ObjectArticle = $this->getObjectArticleHbnObj($b, $options);
			return $ObjectArticle->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("ma_object_article", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/article", "ObjectArticleService.countAllObjectArticles", null, $options);
	}
}
?>
