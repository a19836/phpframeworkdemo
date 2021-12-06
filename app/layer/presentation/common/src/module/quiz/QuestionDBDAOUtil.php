<?php
if (!class_exists("QuestionDBDAOUtil")) {
	class QuestionDBDAOUtil {
		
		public static function get_questions_by_object($data = array()) {
			return "select oq.*, q.* 
					from mq_question q
					inner join mq_object_question oq on oq.question_id=q.question_id and oq.object_type_id=" . $data["object_type_id"] . " and oq.object_id=" . $data["object_id"];
		}
	
		public static function count_questions_by_object($data = array()) {
			return "select count(q.question_id) total
					from mq_question q
					inner join mq_object_question oq on oq.question_id=q.question_id and oq.object_type_id=" . $data["object_type_id"] . " and oq.object_id=" . $data["object_id"];
		}
	
		public static function get_questions_by_object_group($data = array()) {
			return "select oq.*, q.* 
					from mq_question q
					inner join mq_object_question oq on oq.question_id=q.question_id and oq.object_type_id=" . $data["object_type_id"] . " and oq.object_id=" . $data["object_id"] . " and oq.`group`=" . $data["group"];
		}
	
		public static function count_questions_by_object_group($data = array()) {
			return "select count(q.question_id) total
					from mq_question q
					inner join mq_object_question oq on oq.question_id=q.question_id and oq.object_type_id=" . $data["object_type_id"] . " and oq.object_id=" . $data["object_id"] . " and oq.`group`=" . $data["group"];
		}
	
	}
}
?>