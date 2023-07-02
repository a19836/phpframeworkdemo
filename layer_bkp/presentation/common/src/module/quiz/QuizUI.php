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
		if ($question["answers"]) {
			$user_answer_ids = array();
			if ($question["user_answers"])
				foreach ($question["user_answers"] as $user_answer)
					$user_answer_ids[] = $user_answer["answer_id"];
			
			$html = '
			<ul class="question_answers">';
	
			foreach ($question["answers"] as $answer) {
				$checked = in_array($answer["answer_id"], $user_answer_ids);
		
				$html .= '
				<li>
					<div class="answer_title">
						<input type="' . ($settings["allow_multiple_answers"] ? 'checkbox' : 'radio') . '" name="answer_ids[' . $question["question_id"] . '][]" value="' . $answer["answer_id"] . '" ' . ($settings["allow_deletion"] && !$settings["allow_multiple_answers"] ? 'onClick="unCheckRadioButton(this)"' : '') . ' ' . ($checked ? 'checked is_checked' : '') . ' />
						<label>' . $answer["title"] . '</label>
					</div>
					<div class="answer_description">' . $answer["description"] . '</div>
				</li>';
			}
			$html .= '</ul>
			<div class="clear"></div>';
	
			return $html;
		}
	}
}
?>
