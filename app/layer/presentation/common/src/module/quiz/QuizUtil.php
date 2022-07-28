<?php
include_once get_lib("org.phpframework.encryption.CryptoKeyHandler");
include_once __DIR__ . "/QuestionDBDAOUtil.php"; //this file will be automatically generated on this module installation
include_once __DIR__ . "/AnswerDBDAOUtil.php"; //this file will be automatically generated on this module installation
include_once __DIR__ . "/UserAnswerDBDAOUtil.php"; //this file will be automatically generated on this module installation

class QuizUtil {

	const QUESTION_DESCRIPTION_HTML_IMAGE_GROUP_ID = 3;
	const ANSWER_DESCRIPTION_HTML_IMAGE_GROUP_ID = 3;
	
	/* QUESTION FUNCTIONS */
	
	public static function insertQuestion($brokers, $data) {
		if (is_array($brokers)) {
			$data["published"] = empty($data["published"]) ? 0 : 1;
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$question_id = $broker->callBusinessLogic("module/quiz", "QuestionService.insertQuestion", $data);
					$status = $question_id ? true : false;
					break;
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["title"] = addcslashes($data["title"], "\\'");
					$data["description"] = addcslashes($data["description"], "\\'");
					
					$status = $broker->callInsert("module/quiz", "insert_question", $data);
					$question_id = $status ? $broker->getInsertedId() : $status;
					break;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Question = $broker->callObject("module/quiz", "Question");
					$status = $Question->insert($data, $ids);
					$question_id = $status ? $ids["question_id"] : $status;
					break;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$status = $broker->insertObject("mq_question", array(
							"title" => $data["title"], 
							"description" => $data["description"], 
							"published" => $data["published"], 
							"created_date" => $data["created_date"], 
							"modified_date" => $data["modified_date"]
						));
					$question_id = $status ? $broker->getInsertedId() : $status;
					break;
				}
			}
			
			if ($status && $question_id) {
				$status = self::updateObjectQuestionsByQuestionId(array($broker), $question_id, $data);
			
				return $status ? $question_id : false;
			}
		}
	}
	
	public static function updateQuestion($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["question_id"])) {
			$data["published"] = empty($data["published"]) ? 0 : 1;
			$data["modified_date"] = date("Y-m-d H:i:s");
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$status = $broker->callBusinessLogic("module/quiz", "QuestionService.updateQuestion", $data);
					break;
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["title"] = addcslashes($data["title"], "\\'");
					$data["description"] = addcslashes($data["description"], "\\'");
					
					$status = $broker->callUpdate("module/quiz", "update_question", $data);
					break;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Question = $broker->callObject("module/quiz", "Question");
					$status = $Question->update($data);
					break;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$status = $broker->updateObject("mq_question", array(
							"title" => $data["title"], 
							"description" => $data["description"], 
							"published" => $data["published"], 
							"modified_date" => $data["modified_date"]
						), array(
							"question_id" => $data["question_id"]
						));
					break;
				}
			}
			
			if ($status) {
				$status = self::updateObjectQuestionsByQuestionId(array($broker), $data["question_id"], $data);
			
				return $status;
			}
		}
	}
	
	public static function deleteQuestion($brokers, $question_id) {
		if (is_array($brokers) && is_numeric($question_id)) {
			if (self::deleteObjectQuestionsByQuestionId($brokers, $question_id))
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/quiz", "QuestionService.deleteQuestion", array("question_id" => $question_id));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/quiz", "delete_question", array("question_id" => $question_id));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Question = $broker->callObject("module/quiz", "Question");
						return $Question->delete($question_id);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mq_question", array("question_id" => $question_id));
					}
				}
		}
	}
	
	public static function getAllQuestions($brokers, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/quiz", "QuestionService.getAllQuestions", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/quiz", "get_all_questions", null, $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Question = $broker->callObject("module/quiz", "Question");
					return $Question->find(null, $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mq_question", null, null, $options);
				}
			}
		}
	}
	
	public static function countAllQuestions($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/quiz", "QuestionService.countAllQuestions", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/quiz", "count_all_questions", null, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Question = $broker->callObject("module/quiz", "Question");
					return $Question->count(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mq_question", null, array("no_cache" => $no_cache));
				}
			}
		}
	}
	
	public static function getQuestionsByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "QuestionService.getQuestionsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callSelect("module/quiz", "get_questions_by_conditions", array("conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Question = $broker->callObject("module/quiz", "Question");
					return $Question->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options["conditions_join"] = $conditions_join;
					return $broker->findObjects("mq_question", null, $conditions, $options);
				}
			}
		}
	}
	
	public static function countQuestionsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "QuestionService.countQuestionsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/quiz", "count_questions_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Question = $broker->callObject("module/quiz", "Question");
					return $Question->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mq_question", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	public static function getQuestionsByObject($brokers, $object_type_id, $object_id, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "QuestionService.getQuestionsByObject", array("object_type_id" => $object_type_id, "object_id" => $object_id, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/quiz", "get_questions_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Question = $broker->callObject("module/quiz", "Question");
					return $Question->callSelect("module/quiz", "get_questions_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = QuestionDBDAOUtil::get_questions_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id));
					
					return $broker->getSQL($sql, $options);
				}
			}
		}
	}
	
	public static function countQuestionsByObject($brokers, $object_type_id, $object_id, $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "QuestionService.countQuestionsByObject", array("object_type_id" => $object_type_id, "object_id" => $object_id, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/quiz", "count_questions_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Question = $broker->callObject("module/quiz", "Question");
					$result = $Question->callSelect("module/quiz", "count_questions_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = QuestionDBDAOUtil::count_questions_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id));
					
					$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
			}
		}
	}
	
	public static function getQuestionsByObjectGroup($brokers, $object_type_id, $object_id, $group, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id) && is_numeric($group)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "QuestionService.getQuestionsByObjectGroup", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/quiz", "get_questions_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Question = $broker->callObject("module/quiz", "Question");
					return $Question->callSelect("module/quiz", "get_questions_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = QuestionDBDAOUtil::get_questions_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group));
					
					return $broker->getSQL($sql, $options);
				}
			}
		}
	}
	
	public static function countQuestionsByObjectGroup($brokers, $object_type_id, $object_id, $group, $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id) && is_numeric($group)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "QuestionService.countQuestionsByObjectGroup", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/quiz", "count_questions_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Question = $broker->callObject("module/quiz", "Question");
					$result = $Question->callSelect("module/quiz", "count_questions_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = QuestionDBDAOUtil::count_questions_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group));
					
					$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
			}
		}
	}
	
	/* ANSWER FUNCTIONS */
	
	public static function insertAnswer($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["question_id"])) {
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data["value"] = is_numeric($data["value"]) ? $data["value"] : null;
					
					return $broker->callBusinessLogic("module/quiz", "AnswerService.insertAnswer", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["title"] = addcslashes($data["title"], "\\'");
					$data["description"] = addcslashes($data["description"], "\\'");
					$data["value"] = is_numeric($data["value"]) ? $data["value"] : 1;
					
					$status = $broker->callInsert("module/quiz", "insert_answer", $data);
					return $status ? $broker->getInsertedId() : $status;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$data["value"] = is_numeric($data["value"]) ? $data["value"] : null;
					
					$Answer = $broker->callObject("module/quiz", "Answer");
					$status = $Answer->insert($data, $ids);
					return $status ? $ids["answer_id"] : $status;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$status = $broker->insertObject("mq_answer", array(
							"question_id" => $data["question_id"], 
							"title" => $data["title"], 
							"description" => $data["description"], 
							"value" => $data["value"], 
							"created_date" => $data["created_date"], 
							"modified_date" => $data["modified_date"]
						));
					return $status ? $broker->getInsertedId() : $status;
				}
			}
		}
	}
	
	public static function updateAnswer($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["answer_id"]) && is_numeric($data["question_id"])) {
			$data["modified_date"] = date("Y-m-d H:i:s");
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data["value"] = is_numeric($data["value"]) ? $data["value"] : null;
					
					return $broker->callBusinessLogic("module/quiz", "AnswerService.updateAnswer", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["title"] = addcslashes($data["title"], "\\'");
					$data["description"] = addcslashes($data["description"], "\\'");
					$data["value"] = is_numeric($data["value"]) ? $data["value"] : 1;
					
					return $broker->callUpdate("module/quiz", "update_answer", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$data["value"] = is_numeric($data["value"]) ? $data["value"] : null;
					
					$Answer = $broker->callObject("module/quiz", "Answer");
					return $Answer->update($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->updateObject("mq_answer", array(
							"question_id" => $data["question_id"], 
							"title" => $data["title"], 
							"description" => $data["description"], 
							"value" => $data["value"], 
							"modified_date" => $data["modified_date"]
						), array(
							"answer_id" => $data["answer_id"]
						));
				}
			}
		}
	}
	
	public static function deleteAnswer($brokers, $answer_id) {
		if (is_array($brokers) && is_numeric($answer_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "AnswerService.deleteAnswer", array("answer_id" => $answer_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/quiz", "delete_answer", array("answer_id" => $answer_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Answer = $broker->callObject("module/quiz", "Answer");
					return $Answer->delete($answer_id);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mq_answer", array("answer_id" => $answer_id));
				}
			}
		}
	}
	
	public static function deleteAnswersByQuestionId($brokers, $question_id) {
		if (is_array($brokers) && is_numeric($question_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "AnswerService.deleteAnswersByQuestionId", array("question_id" => $question_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/quiz", "delete_answers_by_question_id", array("question_id" => $question_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Answer = $broker->callObject("module/quiz", "Answer");
					$conditions = array("question_id" => $question_id);
					return $Answer->deleteByConditions(array("conditions" => $conditions));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mq_answer", array("question_id" => $question_id));
				}
			}
		}
	}
	
	public static function getAllAnswers($brokers, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/quiz", "AnswerService.getAllAnswers", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/quiz", "get_all_answers", null, $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Answer = $broker->callObject("module/quiz", "Answer");
					return $Answer->find(null, $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mq_answer", null, null, $options);
				}
			}
		}
	}
	
	public static function countAllAnswers($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/quiz", "AnswerService.countAllAnswers", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/quiz", "count_all_answers", null, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Answer = $broker->callObject("module/quiz", "Answer");
					return $Answer->count(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mq_answer", null, array("no_cache" => $no_cache));
				}
			}
		}
	}
	
	public static function getAnswersByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "AnswerService.getAnswersByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callSelect("module/quiz", "get_answers_by_conditions", array("conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Answer = $broker->callObject("module/quiz", "Answer");
					return $Answer->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options["conditions_join"] = $conditions_join;
					return $broker->findObjects("mq_answer", null, $conditions, $options);
				}
			}
		}
	}
	
	public static function countAnswersByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "AnswerService.countAnswersByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/quiz", "count_answers_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Answer = $broker->callObject("module/quiz", "Answer");
					return $Answer->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mq_answer", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	public static function getAnswersStatsByQuestionIds($brokers, $question_ids, $options = array(), $no_cache = false) {
		if (is_array($brokers) && $question_ids) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$question_ids_str = "";
			$question_ids = is_array($question_ids) ? $question_ids : array($question_ids);
			foreach ($question_ids as $question_id)
				if (is_numeric($question_id))
					$question_ids_str .= ($question_ids_str ? ", " : "") . $question_id;
			
			if ($question_ids_str) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/quiz", "AnswerService.getAnswersStatsByQuestionIds", array("question_ids" => $question_ids, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/quiz", "get_answers_stats_by_question_ids", array("question_ids" => $question_ids_str), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Answer = $broker->callObject("module/quiz", "Answer");
						return $Answer->callSelect("module/quiz", "get_answers_stats_by_question_ids", array("question_ids" => $question_ids_str), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$sql = AnswerDBDAOUtil::get_answers_stats_by_question_ids(array("question_ids" => $question_ids_str));
						
						return $broker->getSQL($sql, $options);
					}
				}
			}
		}
	}
	
	/* USER ANSWER FUNCTIONS */
	
	public static function insertUserAnswer($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["user_id"]) && is_numeric($data["answer_id"])) {
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "UserAnswerService.insertUserAnswer", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callInsert("module/quiz", "insert_user_answer", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAnswer = $broker->callObject("module/quiz", "UserAnswer");
					return $UserAnswer->insert($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->insertObject("mq_user_answer", array(
							"user_id" => $data["user_id"], 
							"answer_id" => $data["answer_id"], 
							"created_date" => $data["created_date"], 
							"modified_date" => $data["modified_date"]
						));
				}
			}
		}
	}
	
	public static function deleteUserAnswer($brokers, $user_id, $answer_id) {
		if (is_array($brokers) && is_numeric($user_id) && is_numeric($answer_id)) {
			$data = array("user_id" => $user_id, "answer_id" => $answer_id);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "UserAnswerService.deleteUserAnswer", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/quiz", "delete_user_answer", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAnswer = $broker->callObject("module/quiz", "UserAnswer");
					return $UserAnswer->deleteByConditions(array("conditions" => $data));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mq_user_answer", $data);
				}
			}
		}
	}
	
	public static function deleteUserAnswersByAnswerId($brokers, $answer_id) {
		if (is_array($brokers) && is_numeric($answer_id)) {
			$data = array("answer_id" => $answer_id);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "UserAnswerService.deleteUserAnswersByAnswerId", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/quiz", "delete_user_answers_by_answer_id", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAnswer = $broker->callObject("module/quiz", "UserAnswer");
					return $UserAnswer->deleteByConditions(array("conditions" => $data));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mq_user_answer", $data);
				}
			}
		}
	}
	
	public static function deleteUserAnswersByUserId($brokers, $user_id) {
		if (is_array($brokers) && is_numeric($user_id)) {
			$data = array("user_id" => $user_id);
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "UserAnswerService.deleteUserAnswersByUserId", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/quiz", "delete_user_answers_by_user_id", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAnswer = $broker->callObject("module/quiz", "UserAnswer");
					return $UserAnswer->deleteByConditions(array("conditions" => $data));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mq_user_answer", $data);
				}
			}
		}
	}
	
	public static function deleteUserAnswersByQuestionIds($brokers, $question_ids) {
		if (is_array($brokers) && $question_ids) {
			$question_ids_str = "";
			$question_ids = is_array($question_ids) ? $question_ids : array($question_ids);
			foreach ($question_ids as $question_id)
				if (is_numeric($question_id))
					$question_ids_str .= ($question_ids_str ? ", " : "") . $question_id;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "UserAnswerService.deleteUserAnswersByQuestionIds", array("question_ids" => $question_ids));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/quiz", "delete_user_answers_by_question_ids", array("question_ids" => $question_ids_str));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAnswer = $broker->callObject("module/quiz", "UserAnswer");
					return $UserAnswer->callDelete("delete_user_answers_by_question_ids", array("question_ids" => $question_ids_str));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = UserAnswerDBDAOUtil::delete_user_answers_by_question_ids(array("question_ids" => $question_ids_str));
					
					return $broker->setSQL($sql);
				}
			}
		}
	}
	
	public static function deleteUserAnswersByUserAndQuestionIds($brokers, $user_id, $question_ids) {
		if (is_array($brokers) && is_numeric($user_id) && $question_ids) {
			$question_ids_str = "";
			$question_ids = is_array($question_ids) ? $question_ids : array($question_ids);
			foreach ($question_ids as $question_id)
				if (is_numeric($question_id))
					$question_ids_str .= ($question_ids_str ? ", " : "") . $question_id;
			
			if ($question_ids_str) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/quiz", "UserAnswerService.deleteUserAnswersByUserAndQuestionIds", array("question_ids" => $question_ids, "user_id" => $user_id));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/quiz", "delete_user_answers_by_user_and_question_ids", array("question_ids" => $question_ids_str, "user_id" => $user_id));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserAnswer = $broker->callObject("module/quiz", "UserAnswer");
						return $UserAnswer->callDelete("delete_user_answers_by_user_and_question_ids", array("question_ids" => $question_ids_str, "user_id" => $user_id));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$sql = UserAnswerDBDAOUtil::delete_user_answers_by_user_and_question_ids(array("question_ids" => $question_ids_str, "user_id" => $user_id));
						
						return $broker->setSQL($sql);
					}
				}
			}
		}
	}
	
	//$conditions must be an array containing multiple conditions
	public static function getUserAnswersByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "UserAnswerService.getUserAnswersByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callSelect("module/quiz", "get_user_answer_by_conditions", array("conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAnswer = $broker->callObject("module/quiz", "UserAnswer");
					return $UserAnswer->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options["conditions_join"] = $conditions_join;
					return $broker->findObjects("mq_user_answer", null, $conditions, $options);
				}
			}
		}
	}
	
	//$conditions must be an array containing multiple conditions
	public static function countUserAnswersByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "UserAnswerService.countUserAnswersByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/quiz", "count_user_answer_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAnswer = $broker->callObject("module/quiz", "UserAnswer");
					return $UserAnswer->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mq_user_answer", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	public static function getAllUserAnswers($brokers, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => $options);
					return $broker->callBusinessLogic("module/quiz", "UserAnswerService.getAllUserAnswers", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/quiz", "get_all_user_answers", null, $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAnswer = $broker->callObject("module/quiz", "UserAnswer");
					return $UserAnswer->find(null, $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mq_user_answer", null, null, $options);
				}
			}
		}
	}
	
	public static function countAllUserAnswers($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/quiz", "UserAnswerService.countAllUserAnswers", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/quiz", "count_all_user_answers", null, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAnswer = $broker->callObject("module/quiz", "UserAnswer");
					return $UserAnswer->count(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mq_user_answer", null, array("no_cache" => $no_cache));
				}
			}
		}
	}
	
	public static function getUserAnswersByQuestionIds($brokers, $question_ids, $options = array(), $no_cache = false) {
		if (is_array($brokers) && $question_ids) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$question_ids_str = "";
			$question_ids = is_array($question_ids) ? $question_ids : array($question_ids);
			foreach ($question_ids as $question_id)
				if (is_numeric($question_id))
					$question_ids_str .= ($question_ids_str ? ", " : "") . $question_id;
			
			if ($question_ids_str) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/quiz", "UserAnswerService.getUserAnswersByQuestionIds", array("question_ids" => $question_ids, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/quiz", "get_user_answers_by_question_ids", array("question_ids" => $question_ids_str), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserAnswer = $broker->callObject("module/quiz", "UserAnswer");
						return $UserAnswer->callSelect("module/quiz", "get_user_answers_by_question_ids", array("question_ids" => $question_ids_str), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$sql = UserAnswerDBDAOUtil::get_user_answers_by_question_ids(array("question_ids" => $question_ids_str));
						
						return $broker->getSQL($sql, $options);
					}
				}
			}
		}
	}
	
	public static function getUserAnswersByUserAndQuestionIds($brokers, $user_id, $question_ids, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($user_id) && $question_ids) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$question_ids_str = "";
			$question_ids = is_array($question_ids) ? $question_ids : array($question_ids);
			foreach ($question_ids as $question_id)
				if (is_numeric($question_id))
					$question_ids_str .= ($question_ids_str ? ", " : "") . $question_id;
			
			if ($question_ids_str) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/quiz", "UserAnswerService.getUserAnswersByUserAndQuestionIds", array("user_id" => $user_id, "question_ids" => $question_ids, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/quiz", "get_user_answers_by_user_and_question_ids", array("user_id" => $user_id, "question_ids" => $question_ids_str), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserAnswer = $broker->callObject("module/quiz", "UserAnswer");
						return $UserAnswer->callSelect("module/quiz", "get_user_answers_by_user_and_question_ids", array("user_id" => $user_id, "question_ids" => $question_ids_str), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$sql = UserAnswerDBDAOUtil::get_user_answers_by_user_and_question_ids(array("user_id" => $user_id, "question_ids" => $question_ids_str));
						
						return $broker->getSQL($sql, $options);
					}
				}
			}
		}
	}
	
	public static function getUserAnswersByQuestionObject($brokers, $object_type_id, $object_id, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "UserAnswerService.getUserAnswersByQuestionObject", array("object_type_id" => $object_type_id, "object_id" => $object_id, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/quiz", "get_user_answers_by_question_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$UserAnswer = $broker->callObject("module/quiz", "UserAnswer");
					return $UserAnswer->callSelect("module/quiz", "get_user_answers_by_question_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = UserAnswerDBDAOUtil::get_user_answers_by_question_object(array("object_type_id" => $object_type_id, "object_id" => $object_id));
					
					return $broker->getSQL($sql, $options);
				}
			}
		}
	}
	
	public static function getUserAnswersByQuestionIdsGroupedByUsers($brokers, $question_ids, $options = array(), $no_cache = false) {
		if (is_array($brokers) && $question_ids) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$question_ids_str = "";
			$question_ids = is_array($question_ids) ? $question_ids : array($question_ids);
			foreach ($question_ids as $question_id)
				if (is_numeric($question_id))
					$question_ids_str .= ($question_ids_str ? ", " : "") . $question_id;
			
			if ($question_ids_str) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/quiz", "UserAnswerService.getUserAnswersByQuestionIdsGroupedByUsers", array("question_ids" => $question_ids, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/quiz", "get_user_answers_by_question_ids_grouped_by_users", array("question_ids" => $question_ids_str), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserAnswer = $broker->callObject("module/quiz", "UserAnswer");
						return $UserAnswer->callSelect("module/quiz", "get_user_answers_by_question_ids_grouped_by_users", array("question_ids" => $question_ids_str), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$sql = UserAnswerDBDAOUtil::get_user_answers_by_question_ids_grouped_by_users(array("question_ids" => $question_ids_str));
						
						return $broker->getSQL($sql, $options);
					}
				}
			}
		}
	}
	
	public static function countUserAnswersByQuestionIdsGroupedByUsers($brokers, $question_ids, $options = array(), $no_cache = false) {
		if (is_array($brokers) && $question_ids) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$question_ids_str = "";
			$question_ids = is_array($question_ids) ? $question_ids : array($question_ids);
			foreach ($question_ids as $question_id)
				if (is_numeric($question_id))
					$question_ids_str .= ($question_ids_str ? ", " : "") . $question_id;
			
			if ($question_ids_str) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/quiz", "UserAnswerService.countUserAnswersByQuestionIdsGroupedByUsers", array("question_ids" => $question_ids, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/quiz", "count_user_answers_by_question_ids_grouped_by_users", array("question_ids" => $question_ids_str), $options);
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserAnswer = $broker->callObject("module/quiz", "UserAnswer");
						$result = $UserAnswer->callSelect("module/quiz", "count_user_answers_by_question_ids_grouped_by_users", array("question_ids" => $question_ids_str), $options);
						return $result[0]["total"];
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$sql = UserAnswerDBDAOUtil::count_user_answers_by_question_ids_grouped_by_users(array("question_ids" => $question_ids_str));
						
						$result = $broker->getSQL($sql, $options);
						return $result[0]["total"];
					}
				}
			}
		}
	}
	
	/* OBJECT QUESTION FUNCTIONS */

	public static function insertObjectQuestion($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["question_id"]) && is_numeric($data["object_type_id"]) && is_numeric($data["object_id"])) {
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					
					return $broker->callBusinessLogic("module/quiz", "ObjectQuestionService.insertObjectQuestion", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : 0;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : 0;
					
					return $broker->callInsert("module/quiz", "insert_object_question", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
					
					$ObjectQuestion = $broker->callObject("module/quiz", "ObjectQuestion");
					return $ObjectQuestion->insert($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
					
					return $broker->insertObject("mq_object_question", array(
							"question_id" => $data["question_id"], 
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

	public static function updateObjectQuestion($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["new_question_id"]) && is_numeric($data["new_object_type_id"]) && is_numeric($data["new_object_id"]) && is_numeric($data["old_question_id"]) && is_numeric($data["old_object_type_id"]) && is_numeric($data["old_object_id"])) {
			$data["modified_date"] = date("Y-m-d H:i:s");
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					
					return $broker->callBusinessLogic("module/quiz", "ObjectQuestionService.updateObjectQuestion", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : 0;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : 0;
					
					return $broker->callUpdate("module/quiz", "update_object_question", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
					
					$ObjectQuestion = $broker->callObject("module/quiz", "ObjectQuestion");
					return $ObjectQuestion->updatePrimaryKeys($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
					
					return $broker->updateObject("mq_object_question", array(
							"question_id" => $data["new_question_id"], 
							"object_type_id" => $data["new_object_type_id"], 
							"object_id" => $data["new_object_id"], 
							"group" => $data["group"], 
							"order" => $data["order"], 
							"modified_date" => $data["modified_date"]
						), array(
							"question_id" => $data["old_question_id"], 
							"object_type_id" => $data["old_object_type_id"], 
							"object_id" => $data["old_object_id"]
						));
				}
			}
		}
	}
	
	private static function updateObjectQuestionsByQuestionId($brokers, $question_id, $data) {
		if (is_array($brokers) && is_numeric($question_id)) {
			if (self::deleteObjectQuestionsByQuestionId($brokers, $question_id)) {
				$status = true;
				$object_questions = is_array($data["object_questions"]) ? $data["object_questions"] : array();
				
				foreach ($object_questions as $object_question) {
					if (is_numeric($object_question["object_type_id"]) && is_numeric($object_question["object_id"])) {
						$object_question["question_id"] = $question_id;
					
						if (!self::insertObjectQuestion($brokers, $object_question)) {
							$status = false;
						}
					}
				}
				
				return $status;
			}
		}
	}

	public static function deleteObjectQuestion($brokers, $question_id, $object_type_id, $object_id) {
		if (is_array($brokers) && is_numeric($question_id) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$data = array("question_id" => $question_id, "object_type_id" => $object_type_id, "object_id" => $object_id);
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "ObjectQuestionService.deleteObjectQuestion", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/quiz", "delete_object_question", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectQuestion = $broker->callObject("module/quiz", "ObjectQuestion");
					return $ObjectQuestion->deleteByConditions(array("conditions" => $data));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mq_object_question", $data);
				}
			}
		}
	}

	public static function deleteObjectQuestionsByQuestionId($brokers, $question_id) {
		if (is_array($brokers) && is_numeric($question_id)) {
			$data = array("question_id" => $question_id);
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "ObjectQuestionService.deleteObjectQuestionsByQuestionId", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/quiz", "delete_object_questions_by_question_id", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectQuestion = $broker->callObject("module/quiz", "ObjectQuestion");
					return $ObjectQuestion->deleteByConditions(array("conditions" => $data));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mq_object_question", $data);
				}
			}
		}
	}

	public static function deleteObjectQuestionsByConditions($brokers, $conditions, $conditions_join) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "ObjectQuestionService.deleteObjectQuestionsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callDelete("module/quiz", "delete_object_questions_by_conditions", array("conditions" => $cond));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectQuestion = $broker->callObject("module/quiz", "ObjectQuestion");
					return $ObjectQuestion->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $conditions_join));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mq_object_question", $conditions, array("conditions_join" => $conditions_join));
				}
			}
		}
	}

	public static function getObjectQuestion($brokers, $question_id, $object_type_id, $object_id, $no_cache = false) {
		if (is_array($brokers) && is_numeric($question_id) && is_numeric($object_type_id) && is_numeric($object_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "ObjectQuestionService.getObjectQuestion", array("question_id" => $question_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/quiz", "get_object_question", array("question_id" => $question_id, "object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
					return $result[0];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectQuestion = $broker->callObject("module/quiz", "ObjectQuestion");
					return $ObjectQuestion->findById(array("question_id" => $question_id, "object_type_id" => $object_type_id, "object_id" => $object_id), null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$result = $broker->findObjects("mq_object_question", null, array("question_id" => $question_id, "object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
					return $result[0];
				}
			}
		}
	}

	//$conditions must be an array containing multiple conditions
	public static function getObjectQuestionsByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "ObjectQuestionService.getObjectQuestionsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callSelect("module/quiz", "get_object_questions_by_conditions", array("conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectQuestion = $broker->callObject("module/quiz", "ObjectQuestion");
					return $ObjectQuestion->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options["conditions_join"] = $conditions_join;
					return $broker->findObjects("mq_object_question", null, $conditions, $options);
				}
			}
		}
	}

	//$conditions must be an array containing multiple conditions
	public static function countObjectQuestionsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/quiz", "ObjectQuestionService.countObjectQuestionsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/quiz", "count_object_questions_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectQuestion = $broker->callObject("module/quiz", "ObjectQuestion");
					return $ObjectQuestion->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mq_object_question", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}

	public static function getAllObjectQuestions($brokers, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => $options);
					return $broker->callBusinessLogic("module/quiz", "ObjectQuestionService.getAllObjectQuestions", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/quiz", "get_all_object_questions", null, $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectQuestion = $broker->callObject("module/quiz", "ObjectQuestion");
					return $ObjectQuestion->find(null, $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mq_object_question", null, null, $options);
				}
			}
		}
	}

	public static function countAllObjectQuestions($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/quiz", "ObjectQuestionService.countAllObjectQuestions", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/quiz", "count_all_object_questions", null, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectQuestion = $broker->callObject("module/quiz", "ObjectQuestion");
					return $ObjectQuestion->count(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mq_object_question", null, array("no_cache" => $no_cache));
				}
			}
		}
	}

	public static function getObjectQuestionsByQuestionId($brokers, $question_id, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($question_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("question_id" => $question_id, "options" => $options);
					return $broker->callBusinessLogic("module/quiz", "ObjectQuestionService.getObjectQuestionsByQuestionId", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/quiz", "get_object_questions_by_question_id", array("question_id" => $question_id), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectQuestion = $broker->callObject("module/quiz", "ObjectQuestion");
					return $ObjectQuestion->find(array("conditions" => array("question_id" => $question_id)), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mq_object_question", null, array("question_id" => $question_id), $options);
				}
			}
		}
	}

	public static function countObjectQuestionsByQuestionId($brokers, $question_id, $no_cache = false) {
		if (is_array($brokers) && is_numeric($question_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("question_id" => $question_id, "options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/quiz", "ObjectQuestionService.countObjectQuestionsByQuestionId", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/quiz", "count_object_questions_by_question_id", array("question_id" => $question_id), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$ObjectQuestion = $broker->callObject("module/quiz", "ObjectQuestion");
					return $ObjectQuestion->count(array("conditions" => array("question_id" => $question_id)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mq_object_question", array("question_id" => $question_id), array("no_cache" => $no_cache));
				}
			}
		}
	}
}
?>
