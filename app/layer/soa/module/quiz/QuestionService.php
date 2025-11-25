<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

namespace Module\Quiz;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/QuestionDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class QuestionService extends \soa\CommonService {
	private $Question;
	
	private function getQuestionHbnObj($b, $options) {
		if (!$this->Question)
			$this->Question = $b->callObject("module/quiz", "Question", $options);
		
		return $this->Question;
	}
	
	/**
	 * @param (name=data[title], type=varchar, not_null=1, min_length=1, max_length=1000)
	 * @param (name=data[description], type=longblob, default="")  
	 * @param (name=data[published], type=bool, default="0")  
	 */
	public function insertQuestion($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if (empty($data["published"]))
			$data["published"] = 0;
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["title"] = addcslashes($data["title"], "\\'");
			$data["description"] = isset($data["description"]) ? addcslashes($data["description"], "\\'") : "";
			
			$status = $b->callInsert("module/quiz", "insert_question", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Question = $this->getQuestionHbnObj($b, $options);
			$ids = null;
			$status = $Question->insert($data, $ids);
			return $status ? (isset($ids["question_id"]) ? $ids["question_id"] : null) : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$status = $b->insertObject("mq_question", array(
					"title" => $data["title"], 
					"description" => isset($data["description"]) ? $data["description"] : null, 
					"published" => $data["published"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/quiz", "QuestionService.insertQuestion", $data, $options);
	}
	
	/**
	 * @param (name=data[question_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[title], type=varchar, not_null=1, min_length=1, max_length=1000)
	 * @param (name=data[description], type=longblob, default="")  
	 * @param (name=data[published], type=bool, default="0")  
	 */
	public function updateQuestion($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if (empty($data["published"]))
			$data["published"] = 0;
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["title"] = addcslashes($data["title"], "\\'");
			$data["description"] = isset($data["description"]) ? addcslashes($data["description"], "\\'") : "";
			
			return $b->callUpdate("module/quiz", "update_question", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Question = $this->getQuestionHbnObj($b, $options);
			return $Question->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mq_question", array(
					"title" => $data["title"], 
					"description" => isset($data["description"]) ? $data["description"] : "", 
					"published" => $data["published"], 
					"modified_date" => $data["modified_date"]
				), array(
					"question_id" => $data["question_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/quiz", "QuestionService.updateQuestion", $data, $options);
	}
	
	/**
	 * @param (name=data[question_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteQuestion($data) {
		$question_id = $data["question_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/quiz", "delete_question", array("question_id" => $question_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Question = $this->getQuestionHbnObj($b, $options);
			return $Question->delete($question_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mq_question", array("question_id" => $question_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/quiz", "QuestionService.deleteQuestion", $data, $options);
	}
	
	/**
	 * @param (name=data[question_id], type=bigint, not_null=1, length=19)  
	 */
	public function getQuestion($data) {
		$question_id = $data["question_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/quiz", "get_question", array("question_id" => $question_id), $options);
			return isset($result[0]) ? $result[0] : null;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Question = $this->getQuestionHbnObj($b, $options);
			return $Question->findById($question_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mq_question", null, array("question_id" => $question_id), $options);
			return isset($result[0]) ? $result[0] : null;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/quiz", "QuestionService.getQuestion", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][question_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][title], type=varchar|array, length=1000) 
	 * @param (name=data[conditions][description], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array) 
	 */
	public function getQuestionsByConditions($data) {
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $conditions_join);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/quiz", "get_questions_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Question = $this->getQuestionHbnObj($b, $options);
				return $Question->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $conditions_join;
				return $b->findObjects("mq_question", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/quiz", "QuestionService.getQuestionsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][question_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][title], type=varchar|array, length=1000) 
	 * @param (name=data[conditions][description], type=longblob|array)  
	 * @param (name=data[conditions][published], type=bool|array)
	 */
	public function countQuestionsByConditions($data) {
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $conditions_join);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/quiz", "count_questions_by_conditions", array("conditions" => $cond), $options);
				return isset($result[0]["total"]) ? $result[0]["total"] : null;
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Question = $this->getQuestionHbnObj($b, $options);
				return $Question->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $conditions_join;
				return $b->countObjects("mq_question", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/quiz", "QuestionService.countQuestionsByConditions", $data, $options);
		}
	}
	
	public function getAllQuestions($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callSelect("module/quiz", "get_all_questions", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Question = $this->getQuestionHbnObj($b, $options);
			return $Question->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mq_question", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/quiz", "QuestionService.getAllQuestions", null, $options);
	}
	
	public function countAllQuestions($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/quiz", "count_all_questions", null, $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Question = $this->getQuestionHbnObj($b, $options);
			return $Question->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mq_question", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/quiz", "QuestionService.countAllQuestions", null, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function getQuestionsByObject($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callSelect("module/quiz", "get_questions_by_object", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Question = $this->getQuestionHbnObj($b, $options);
			return $Question->callSelect("module/quiz", "get_questions_by_object", $data, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = QuestionDBDAOServiceUtil::get_questions_by_object($data);
			
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/quiz", "QuestionService.getQuestionsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function countQuestionsByObject($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/quiz", "count_questions_by_object", $data, $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Question = $this->getQuestionHbnObj($b, $options);
			$result = $Question->callSelect("module/quiz", "count_questions_by_object", $data, $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = QuestionDBDAOServiceUtil::count_questions_by_object($data);
			
			$result = $b->getSQL($sql, $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/quiz", "QuestionService.countQuestionsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[group], type=bigint, not_null=1, length=19)
	 */
	public function getQuestionsByObjectGroup($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/quiz", "get_questions_by_object_group", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Question = $this->getQuestionHbnObj($b, $options);
			return $Question->callSelect("module/quiz", "get_questions_by_object_group", $data, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = QuestionDBDAOServiceUtil::get_questions_by_object_group($data);
			
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/quiz", "QuestionService.getQuestionsByObjectGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[group], type=bigint, not_null=1, length=19)
	 */
	public function countQuestionsByObjectGroup($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/quiz", "count_questions_by_object_group", $data, $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Question = $this->getQuestionHbnObj($b, $options);
			$result = $Question->callSelect("module/quiz", "count_questions_by_object_group", $data, $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = QuestionDBDAOServiceUtil::count_questions_by_object_group($data);
			
			$result = $b->getSQL($sql, $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/quiz", "QuestionService.countQuestionsByObjectGroup", $data, $options);
	}
}
?>
