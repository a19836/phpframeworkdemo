<?php
class QuizUI {
	
	public static function getQuestionAnswersJavascript($settings) {
		$html = '<script>
			function unCheckRadioButton(elm) {
				if (elm.hasAttribute("is_checked")) {
					elm.checked = false;
					elm.removeAttribute("is_checked");
				}
				else
					elm.setAttribute("is_checked", 1);
			
				$(elm).parent().parent().parent().find("input").each(function(idx, item) {
					if (item != elm)
						item.removeAttribute("is_checked");
				});
			}
			</script>';
		
		return $html;
	}
	
	public static function getQuestionAnswersHtml($settings, $question) {
		if (!empty($question["answers"])) {
			$user_answer_ids = array();
			
			if (!empty($question["user_answers"]))
				foreach ($question["user_answers"] as $user_answer)
					$user_answer_ids[] = isset($user_answer["answer_id"]) ? $user_answer["answer_id"] : null;
			
			$html = '
			<ul class="question_answers">';
	
			foreach ($question["answers"] as $answer) {
				$question_id = isset($question["question_id"]) ? $question["question_id"] : null;
				$answer_id = isset($answer["answer_id"]) ? $answer["answer_id"] : null;
				$checked = in_array($answer_id, $user_answer_ids);
		
				$html .= '
				<li>
					<div class="answer_title">
						<input type="' . (!empty($settings["allow_multiple_answers"]) ? 'checkbox' : 'radio') . '" name="answer_ids[' . $question_id . '][]" value="' . $answer_id . '" ' . (!empty($settings["allow_deletion"]) && empty($settings["allow_multiple_answers"]) ? 'onClick="unCheckRadioButton(this)"' : '') . ' ' . ($checked ? 'checked is_checked' : '') . ' />
						<label>' . (isset($answer["title"]) ? $answer["title"] : null) . '</label>
					</div>
					<div class="answer_description">' . (isset($answer["description"]) ? $answer["description"] : null) . '</div>
				</li>';
			}
			$html .= '</ul>
			<div class="clear"></div>';
	
			return $html;
		}
	}
}
?>
