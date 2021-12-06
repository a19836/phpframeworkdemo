<?php
namespace Module\Quiz;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/AnswerDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class AnswerService extends \soa\CommonService {
	private $Answer;
	
	private function getAnswerHbnObj($b, $options) {
		if (!$this->Answer)
			$this->Answer = $b->callObject("module/quiz", "Answer", $options);
		
		return $this->Answer;
	}
	
	/**
	 * @param (name=data[question_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[title], type=varchar, not_null=1, min_length=1, max_length=1000)
	 * @param (name=data[description], type=longblob, default="")  
	 * @param (name=data[value], type=smallint, default=1)  
	 */
	public function insertAnswer($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["title"] = addcslashes($data["title"], "\\'");
			$data["description"] = addcslashes($data["description"], "\\'");
			
			$status = $b->callInsert("module/quiz", "insert_answer", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Answer = $this->getAnswerHbnObj($b, $options);
			$status = $Answer->insert($data, $ids);
			return $status ? $ids["answer_id"] : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$status = $b->insertObject("mq_answer", array(
					"question_id" => $data["question_id"], 
					"title" => $data["title"], 
					"description" => $data["description"], 
					"value" => $data["value"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/quiz", "AnswerService.insertAnswer", $data, $options);
	}
	
	/**
	 * @param (name=data[answer_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[question_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[title], type=varchar, not_null=1, min_length=1, max_length=1000)
	 * @param (name=data[description], type=longblob, default="")  
	 * @param (name=data[value], type=smallint, default=1)  
	 */
	public function updateAnswer($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["title"] = addcslashes($data["title"], "\\'");
			$data["description"] = addcslashes($data["description"], "\\'");
			
			return $b->callUpdate("module/quiz", "update_answer", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Answer = $this->getAnswerHbnObj($b, $options);
			return $Answer->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mq_answer", array(
					"question_id" => $data["question_id"], 
					"title" => $data["title"], 
					"description" => $data["description"], 
					"value" => $data["value"], 
					"modified_date" => $data["modified_date"]
				), array(
					"answer_id" => $data["answer_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/quiz", "AnswerService.updateAnswer", $data, $options);
	}
	
	/**
	 * @param (name=data[answer_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteAnswer($data) {
		$answer_id = $data["answer_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/quiz", "delete_answer", array("answer_id" => $answer_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Answer = $this->getAnswerHbnObj($b, $options);
			return $Answer->delete($answer_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mq_answer", array("answer_id" => $answer_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/quiz", "AnswerService.deleteAnswer", $data, $options);
	}
	
	/**
	 * @param (name=data[question_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteAnswersByQuestionId($data) {
		$question_id = $data["question_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callDelete("module/quiz", "delete_answers_by_question_id", array("question_id" => $question_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Answer = $this->getAnswerHbnObj($b, $options);
			$conditions = array("question_id" => $question_id);
			return $Answer->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mq_answer", array("question_id" => $question_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/quiz", "AnswerService.deleteAnswersByQuestionId", $data, $options);
	}
	
	/**
	 * @param (name=data[answer_id], type=bigint, not_null=1, length=19)  
	 */
	public function getAnswer($data) {
		$answer_id = $data["answer_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/quiz", "get_answer", array("answer_id" => $answer_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Answer = $this->getAnswerHbnObj($b, $options);
			return $Answer->findById($answer_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mq_answer", null, array("answer_id" => $answer_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/quiz", "AnswerService.getAnswer", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][answer_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][question_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][title], type=varchar|array, length=1000) 
	 * @param (name=data[conditions][description], type=longblob|array)
	 * @param (name=data[conditions][value], type=smallint|array)  
	 */
	public function getAnswersByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/quiz", "get_answers_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Answer = $this->getAnswerHbnObj($b, $options);
				return $Answer->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mq_answer", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/quiz", "AnswerService.getAnswersByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][answer_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][question_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][title], type=varchar|array, length=1000) 
	 * @param (name=data[conditions][description], type=longblob|array)
	 * @param (name=data[conditions][value], type=smallint|array)
	 */
	public function countAnswersByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/quiz", "count_answers_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Answer = $this->getAnswerHbnObj($b, $options);
				return $Answer->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mq_answer", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/quiz", "AnswerService.countAnswersByConditions", $data, $options);
		}
	}
	
	public function getAllAnswers($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/quiz", "get_all_answers", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Answer = $this->getAnswerHbnObj($b, $options);
			return $Answer->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mq_answer", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/quiz", "AnswerService.getAllAnswers", null, $options);
	}
	
	public function countAllAnswers($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/quiz", "count_all_answers", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Answer = $this->getAnswerHbnObj($b, $options);
			return $Answer->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mq_answer", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/quiz", "AnswerService.countAllAnswers", null, $options);
	}
	
	/**
	 * @param (name=data[question_ids], type=mixed, not_null=1)
	 */
	public function getAnswersStatsByQuestionIds($data) {
		$question_ids = $data["question_ids"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($question_ids) {
			$question_ids_str = "";
			$question_ids = is_array($question_ids) ? $question_ids : array($question_ids);
			foreach ($question_ids as $question_id) 
				if (is_numeric($question_id))
					$question_ids_str .= ($question_ids_str ? ", " : "") . $question_id;
			
			if ($question_ids_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) 
					return $b->callSelect("module/quiz", "get_answers_stats_by_question_ids", array("question_ids" => $question_ids_str), $options);
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$Answer = $this->getAnswerHbnObj($b, $options);
					return $Answer->callSelect("module/quiz", "get_answers_stats_by_question_ids", array("question_ids" => $question_ids_str), $options);
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$sql = AnswerDBDAOServiceUtil::get_answers_stats_by_question_ids(array("question_ids" => $question_ids_str));
					
					return $b->getSQL($sql, $options);
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/quiz", "AnswerService.getAnswersStatsByQuestionIds", $data, $options);
			}
		}
	}
}
?>
