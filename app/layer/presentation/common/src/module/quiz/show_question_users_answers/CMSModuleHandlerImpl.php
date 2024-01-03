<?php
namespace CMSModule\quiz\show_question_users_answers;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("quiz/QuizUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Question Details
		$question_type = $settings["question_type"];
		
		switch ($question_type) {
			case "get_next_by_parent":
				$data = \QuizUtil::getQuestionsByObject($brokers, $settings["get_next_by_parent"]["object_type_id"], $settings["get_next_by_parent"]["object_id"]);
				$data = self::getNextQuestion($data, $settings["get_next_by_parent"]["previous_order"]);
				break;
			case "get_next_by_parent_group":
				$data = \QuizUtil::getQuestionsByObjectGroup($brokers, $settings["get_next_by_parent"]["object_type_id"], $settings["get_next_by_parent"]["object_id"], $settings["get_next_by_parent"]["group"]);
				$data = self::getNextQuestion($data, $settings["get_next_by_parent"]["previous_order"]);
				break;
			default:
				$data = \QuizUtil::getQuestionsByConditions($brokers, array("question_id" => $settings["question_id"]), null);
				$data = $data[0];
		}
		
		if ($data) {
			$data["answers"] = \QuizUtil::getAnswersByConditions($brokers, array("question_id" => $data["question_id"]), null);
			$data["user_answers"] = \QuizUtil::getUserAnswersByQuestionIds($brokers, $data["question_id"]);
			
			//Add Join Point
			$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Preparing question data", array(
				"EVC" => $EVC,
				"settings" => &$settings,
				"data" => &$data,
			), "Use this join point to change the loaded question data.");
		}
		
		//Preparing questions html
		if ($data) {
			if ($settings["ptl"]) {
				//prepare new settings field
				$settings["fields"]["users_answers"] = array(
					"field" => array(
						"disable_field_group" => 1,
						"input" => array(
							"type" => "label",
							"value" => " ", //leave space on purpose, so the CommonModuleUI::getFormHtml does NOT replace it by #users_answers#
							"next_html" => self::getQuestionUsersAnswers($settings, $data),
						),
					),
				);
				$settings["show_users_answers"] = 1;
			}
			else
				$settings["next_html"] = self::getQuestionUsersAnswers($settings, $data);
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $data;
		//$settings["css_file"] = $project_common_url_prefix . 'module/quiz/show_question_users_answers.css';
		$settings["class"] = "module_show_question_users_answers";
		$settings["allow_view"] = true;
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "quiz/show_question_users_answers", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
	
	private static function getQuestionUsersAnswers($settings, $data) {
		$html = "";
		
		if ($data["answers"]) {
			$user_answers_by_user_ids = array();
			$user_answer_ids_by_user_ids = array();
			
			if ($data["user_answers"])
				foreach ($data["user_answers"] as $ua) {
					$user_answers_by_user_ids[ $ua["user_id"] ][] = $ua;
					$user_answer_ids_by_user_ids[ $ua["user_id"] ][ $ua["answer_id"] ] = true;
				}
			
			$html = '
			<div class="question_user_answers">
				<table class="table table-condensed table-hover">
				<thead>
					<tr>
						<th class="user"></th>';
			
			foreach ($data["answers"] as $answer)
				$html .= '
						<th class="answer">
							<div class="answer_title">' . $answer["title"] . '</div>
							<div class="answer_description">' . $answer["description"] . '</div>
						</th>';
			
			$html .= '	</tr>
				</thead>
				<tbody>';
			
			if ($user_answers_by_user_ids)
				foreach ($user_answers_by_user_ids as $user_id => $uas) {
					$html .= '<tr>
							<td class="user">' . ($uas[0]["name"] ? $uas[0]["name"] : $uas[0]["username"]) . '</td>';
					
					foreach ($data["answers"] as $answer) {
						$selected = $user_answer_ids_by_user_ids[$user_id][ $answer["answer_id"] ];
						$html .= '<td class="answer' . ($selected ? ' selected' : '') . '">' . ($selected ? 'X' : "") . '</td>';
					}
					
					$html .= '</tr>';
				}
			
			$html .= '</tbody>
				</table>
			</div>';
		}
		
		return $html;
	}
	
	private static function getNextQuestion($questions, $previous_order) {
		if ($questions) {
			$idx = null;
			foreach ($questions as $i => $item)
				if ($item["order"] > $previous_order && ($item["order"] < $idx || !$idx))
					$idx = $i;
			
			if (!is_numeric($idx)) 
				$idx = 0;
			
			return $questions[$idx];
		}
	}
}
?>
