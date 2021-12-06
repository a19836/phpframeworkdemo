<?php
if (!class_exists("AnswerDBDAOUtil")) {
	class AnswerDBDAOUtil {
		
		public static function get_answers_stats_by_question_ids($data = array()) {
			return "select a.*, z.total_answers
					from mq_answer a 
					inner join (
						select a.answer_id, count(user_id) total_answers
						from mq_answer a
						left join mq_user_answer ua on ua.answer_id=a.answer_id
						where a.question_id in (" . $data["question_ids"] . ")
						group by a.answer_id
					) z on z.answer_id=a.answer_id";
		}
	
	}
}
?>