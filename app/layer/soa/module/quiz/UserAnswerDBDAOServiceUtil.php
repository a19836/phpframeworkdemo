<?php
namespace Module\Quiz;

if (!class_exists("UserAnswerDBDAOServiceUtil")) {
	class UserAnswerDBDAOServiceUtil {
		
		public static function delete_user_answers_by_question_ids($data = array()) {
			return "delete ua.* from mq_user_answer ua
					inner join mq_answer a on a.answer_id=ua.answer_id and a.question_id in (" . $data["question_ids"] . ")";
		}
	
		public static function delete_user_answers_by_user_and_question_ids($data = array()) {
			return "delete ua.* from mq_user_answer ua
					inner join mq_answer a on a.answer_id=ua.answer_id and a.question_id in (" . $data["question_ids"] . ")
					where ua.user_id=" . $data["user_id"];
		}
	
		public static function get_user_answers_by_question_ids($data = array()) {
			return "select u.*, ua.*, a.question_id
					from mq_user_answer ua 
					inner join mq_answer a on a.answer_id=ua.answer_id and a.question_id in (" . $data["question_ids"] . ")
					inner join mu_user u on u.user_id=ua.user_id";
		}
	
		public static function get_user_answers_by_user_and_question_ids($data = array()) {
			return "select ua.*, a.question_id
					from mq_user_answer ua 
					inner join mq_answer a on a.answer_id=ua.answer_id and a.question_id in (" . $data["question_ids"] . ")
					where ua.user_id=" . $data["user_id"];
		}
	
		public static function get_user_answers_by_question_object($data = array()) {
			return "select u.*, ua.*, a.question_id
					from mq_user_answer ua 
					inner join mq_object_question oq on oq.object_type_id=" . $data["object_type_id"] . " and oq.object_id=" . $data["object_id"] . "
					inner join mq_answer a on a.answer_id=ua.answer_id and a.question_id=oq.question_id
					inner join mu_user u on u.user_id=ua.user_id";
		}
	
		public static function get_user_answers_by_question_ids_grouped_by_users($data = array()) {
			return "select ua.user_id, GROUP_CONCAT(DISTINCT ua.answer_id) AS answers_id
					from mq_user_answer ua 
					inner join mq_answer a on a.answer_id=ua.answer_id and a.question_id in (" . $data["question_ids"] . ")
					group by ua.user_id";
		}
	
		public static function count_user_answers_by_question_ids_grouped_by_users($data = array()) {
			return "select count(distinct(ua.user_id)) total
					from mq_user_answer ua 
					inner join mq_answer a on a.answer_id=ua.answer_id and a.question_id in (" . $data["question_ids"] . ")";
		}
	
	}
}
?>