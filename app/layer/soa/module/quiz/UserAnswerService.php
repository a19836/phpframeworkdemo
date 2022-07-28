<?php
namespace Module\Quiz;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/UserAnswerDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class UserAnswerService extends \soa\CommonService {
	private $UserAnswer;
	
	private function getUserAnswerHbnObj($b, $options) {
		if (!$this->UserAnswer)
			$this->UserAnswer = $b->callObject("module/quiz", "UserAnswer", $options);
		
		return $this->UserAnswer;
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[answer_id], type=bigint, not_null=1, length=19)
	 */
	public function insertUserAnswer($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callInsert("module/quiz", "insert_user_answer", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAnswer = $this->getUserAnswerHbnObj($b, $options);
			return $UserAnswer->insert($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->insertObject("mq_user_answer", array(
					"user_id" => $data["user_id"], 
					"answer_id" => $data["answer_id"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/quiz", "UserAnswerService.insertUserAnswer", $data, $options);
	}
	
	/**
	 * @param (name=data[new_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_answer_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_answer_id], type=bigint, not_null=1, length=19)
	 */
	public function updateUserAnswerPks($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/quiz", "update_user_answer_pks", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAnswer = $this->getUserAnswerHbnObj($b, $options);
			return $UserAnswer->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mq_user_answer", array(
					"user_id" => $data["new_user_id"], 
					"answer_id" => $data["new_answer_id"], 
					"modified_date" => $data["modified_date"]
				), array(
					"user_id" => $data["old_user_id"], 
					"answer_id" => $data["old_answer_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/quiz", "UserAnswerService.updateUserAnswerPks", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[answer_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteUserAnswer($data) {
		$user_id = $data["user_id"];
		$answer_id = $data["answer_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/quiz", "delete_user_answer", array("user_id" => $user_id, "answer_id" => $answer_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAnswer = $this->getUserAnswerHbnObj($b, $options);
			return $UserAnswer->delete(array("user_id" => $user_id, "answer_id" => $answer_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mq_user_answer", array("user_id" => $user_id, "answer_id" => $answer_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/quiz", "UserAnswerService.deleteUserAnswer", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteUserAnswersByUserId($data) {
		$user_id = $data["user_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/quiz", "delete_user_answers_by_user_id", array("user_id" => $user_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAnswer = $this->getUserAnswerHbnObj($b, $options);
			$conditions = array("user_id" => $user_id);
			return $UserAnswer->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mq_user_answer", array("user_id" => $user_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/quiz", "UserAnswerService.deleteUserAnswersByUserId", $data, $options);
	}
	
	/**
	 * @param (name=data[answer_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteUserAnswersByAnswerId($data) {
		$answer_id = $data["answer_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/quiz", "delete_user_answers_by_answer_id", array("answer_id" => $answer_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAnswer = $this->getUserAnswerHbnObj($b, $options);
			$conditions = array("answer_id" => $answer_id);
			return $UserAnswer->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mq_user_answer", array("answer_id" => $answer_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/quiz", "UserAnswerService.deleteUserAnswersByAnswerId", $data, $options);
	}
	
	/**
	 * @param (name=data[question_ids], type=mixed, not_null=1)
	 */
	public function deleteUserAnswersByQuestionIds($data) {
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
					return $b->callDelete("module/quiz", "delete_user_answers_by_question_ids", array("question_ids" => $question_ids_str), $options);
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$UserAnswer = $this->getUserAnswerHbnObj($b, $options);
					return $UserAnswer->callDelete("delete_user_answers_by_question_ids", array("question_ids" => $question_ids_str), $options);
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$sql = UserAnswerDBDAOServiceUtil::delete_user_answers_by_question_ids(array("question_ids" => $question_ids_str));
					
					return $b->setSQL($sql, $options);
				}
				else if (is_a($b, "IBusinessLogicBrokerClient"))
					return $b->callBusinessLogic("module/quiz", "UserAnswerService.deleteUserAnswersByQuestionIds", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[question_ids], type=mixed, not_null=1)
	 */
	public function deleteUserAnswersByUserAndQuestionIds($data) {
		$question_ids = $data["question_ids"];
		$user_id = $data["user_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($user_id && $question_ids) {
			$question_ids_str = "";
			$question_ids = is_array($question_ids) ? $question_ids : array($question_ids);
			foreach ($question_ids as $question_id) 
				if (is_numeric($question_id))
					$question_ids_str .= ($question_ids_str ? ", " : "") . $question_id;
			
			if ($question_ids_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) 
					return $b->callDelete("module/quiz", "delete_user_answers_by_user_and_question_ids", array("question_ids" => $question_ids_str, "user_id" => $user_id), $options);
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$UserAnswer = $this->getUserAnswerHbnObj($b, $options);
					return $UserAnswer->callDelete("delete_user_answers_by_user_and_question_ids", array("question_ids" => $question_ids_str, "user_id" => $user_id), $options);
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$sql = UserAnswerDBDAOServiceUtil::delete_user_answers_by_user_and_question_ids(array("question_ids" => $question_ids_str, "user_id" => $user_id));
					
					return $b->setSQL($sql, $options);
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/quiz", "UserAnswerService.deleteUserAnswersByUserAndQuestionIds", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[answer_id], type=bigint, not_null=1, length=19)
	 */
	public function getUserAnswer($data) {
		$user_id = $data["user_id"];
		$answer_id = $data["answer_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/quiz", "get_user_answer", array("user_id" => $user_id, "answer_id" => $answer_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAnswer = $this->getUserAnswerHbnObj($b, $options);
			return $UserAnswer->findById(array("user_id" => $user_id, "answer_id" => $answer_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mq_user_answer", null, array("user_id" => $user_id, "answer_id" => $answer_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/quiz", "UserAnswerService.getUserAnswer", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][answer_id], type=bigint|array, length=19)
	 */
	public function getUserAnswersByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/quiz", "get_user_answers_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$UserAnswer = $this->getUserAnswerHbnObj($b, $options);
				return $UserAnswer->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mq_user_answer", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/quiz", "UserAnswerService.getUserAnswersByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][answer_id], type=bigint|array, length=19)
	 */
	public function countUserAnswersByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/quiz", "count_user_answers_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$UserAnswer = $this->getUserAnswerHbnObj($b, $options);
				return $UserAnswer->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mq_user_answer", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/quiz", "UserAnswerService.countUserAnswersByConditions", $data, $options);
		}
	}
	
	public function getAllUserAnswers($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/quiz", "get_all_user_answers", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAnswer = $this->getUserAnswerHbnObj($b, $options);
			return $UserAnswer->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mq_user_answer", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/quiz", "UserAnswerService.getAllUserAnswers", null, $options);
	}
	
	public function countAllUserAnswers($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/quiz", "count_all_user_answers", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAnswer = $this->getUserAnswerHbnObj($b, $options);
			return $UserAnswer->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mq_user_answer", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/quiz", "UserAnswerService.countAllUserAnswers", null, $options);
	}
	
	/**
	 * @param (name=data[question_ids], type=mixed, not_null=1)
	 */
	public function getUserAnswersByQuestionIds($data) {
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
					return $b->callSelect("module/quiz", "get_user_answers_by_question_ids", array("question_ids" => $question_ids_str), $options);
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$UserAnswer = $this->getUserAnswerHbnObj($b, $options);
					return $UserAnswer->callSelect("module/quiz", "get_user_answers_by_question_ids", array("question_ids" => $question_ids_str), $options);
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$sql = UserAnswerDBDAOServiceUtil::get_user_answers_by_question_ids(array("question_ids" => $question_ids_str));
					
					return $b->getSQL($sql, $options);
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/quiz", "UserAnswerService.getUserAnswersByQuestionIds", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[question_ids], type=mixed, not_null=1)
	 */
	public function getUserAnswersByUserAndQuestionIds($data) {
		$user_id = $data["user_id"];
		$question_ids = $data["question_ids"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($user_id && $question_ids) {
			$question_ids_str = "";
			$question_ids = is_array($question_ids) ? $question_ids : array($question_ids);
			foreach ($question_ids as $question_id) 
				if (is_numeric($question_id))
					$question_ids_str .= ($question_ids_str ? ", " : "") . $question_id;
				
			if ($question_ids_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) 
					return $b->callSelect("module/quiz", "get_user_answers_by_user_and_question_ids", array("user_id" => $user_id, "question_ids" => $question_ids_str), $options);
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$UserAnswer = $this->getUserAnswerHbnObj($b, $options);
					return $UserAnswer->callSelect("module/quiz", "get_user_answers_by_user_and_question_ids", array("user_id" => $user_id, "question_ids" => $question_ids_str), $options);
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$sql = UserAnswerDBDAOServiceUtil::get_user_answers_by_user_and_question_ids(array("user_id" => $user_id, "question_ids" => $question_ids_str));
					
					return $b->getSQL($sql, $options);
				}
				else if (is_a($b, "IBusinessLogicBrokerClient"))
					return $b->callBusinessLogic("module/quiz", "UserAnswerService.getUserAnswersByUserAndQuestionIds", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function getUserAnswersByQuestionObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/quiz", "get_user_answers_by_question_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserAnswer = $this->getUserAnswerHbnObj($b, $options);
			return $UserAnswer->callSelect("module/quiz", "get_user_answers_by_question_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = UserAnswerDBDAOServiceUtil::get_user_answers_by_question_object(array("object_type_id" => $object_type_id, "object_id" => $object_id));
			
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/quiz", "UserAnswerService.getUserAnswersByQuestionObject", $data, $options);
	}
	
	/**
	 * @param (name=data[question_ids], type=mixed, not_null=1)
	 */
	public function getUserAnswersByQuestionIdsGroupedByUsers($data) {
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
					return $b->callSelect("module/quiz", "get_user_answers_by_question_ids_grouped_by_users", array("question_ids" => $question_ids_str), $options);
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$UserAnswer = $this->getUserAnswerHbnObj($b, $options);
					return $UserAnswer->callSelect("module/quiz", "get_user_answers_by_question_ids_grouped_by_users", array("question_ids" => $question_ids_str), $options);
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$sql = UserAnswerDBDAOServiceUtil::get_user_answers_by_question_ids_grouped_by_users(array("question_ids" => $question_ids_str));
					
					return $b->getSQL($sql, $options);
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/quiz", "UserAnswerService.getUserAnswersByQuestionIdsGroupedByUsers", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[question_ids], type=mixed, not_null=1)
	 */
	public function countUserAnswersByQuestionIdsGroupedByUsers($data) {
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
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$result = $b->callSelect("module/quiz", "count_user_answers_by_question_ids_grouped_by_users", array("question_ids" => $question_ids_str), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$UserAnswer = $this->getUserAnswerHbnObj($b, $options);
					$result = $UserAnswer->callSelect("module/quiz", "count_user_answers_by_question_ids_grouped_by_users", array("question_ids" => $question_ids_str), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$sql = UserAnswerDBDAOServiceUtil::count_user_answers_by_question_ids_grouped_by_users(array("question_ids" => $question_ids_str));
					
					$result = $b->getSQL($sql, $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/quiz", "UserAnswerService.countUserAnswersByQuestionIdsGroupedByUsers", $data, $options);
			}
		}
	}
}
?>
