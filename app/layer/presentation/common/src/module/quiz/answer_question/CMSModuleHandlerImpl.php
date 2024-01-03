<?php
namespace CMSModule\quiz\answer_question;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("quiz/QuizUtil", $common_project_name);
		include_once $EVC->getModulePath("quiz/QuizUI", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting User
		$session_id = $settings["session_id"];
		$user_id = $settings["user_id"];
		
		if (!$user_id && $session_id) {
			include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
	
			$session_data = $session_id ? \UserUtil::getUserSessionsByConditions($brokers, array("session_id" => $session_id), null) : null;
			
			if ($session_data[0]) {
				$user_data = \UserUtil::getUsersByConditions($brokers, array("user_id" => $session_data[0]["user_id"]), null);
				$user_id = $user_data[0]["user_id"];
			}
		}
		
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
			$data["user_answers"] = \QuizUtil::getUserAnswersByUserAndQuestionIds($brokers, $user_id, $data["question_id"]);
			
			//Add Join Point
			$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Preparing answer question data", array(
				"EVC" => $EVC,
				"settings" => &$settings,
				"answer_question_data" => &$data,
			), "Use this join point to change the loaded question data.");
		}
		
		//Preparing Question
		if ($_POST && $user_id && $data) {
			if ($_POST["save"]) {
				$answer_ids = $_POST["answer_ids"][ $data["question_id"] ];
				
				if (!$answer_ids && $settings["allow_deletion"]) {
					$status = !$data["user_answers"] || \QuizUtil::deleteUserAnswersByUserAndQuestionIds($brokers, $user_id, $data["question_id"]);
					
					if ($status)
						unset($data["user_answers"]);
				}
				else if (!$answer_ids) {
					$error_message = "You must selected at least one answer!";
				}
				else {
					$exists = false;
					
					if ($data["answers"] && $answer_ids) {
						$available_answer_ids = array();
						foreach ($data["answers"] as $answer)
							$available_answer_ids[] = $answer["answer_id"];
							
						$exists = true;
						foreach ($answer_ids as $answer_id)
							if (!in_array($answer_id, $available_answer_ids)) {
								$exists = false;
								break;
							}
					}
					
					if (!$exists) {//This cover the cases where the $answer_ids doesn't exist and allow_deletion is not permitted and where the user tries to hack the system with different answer ids...
						$error_message = "Error: Answer does NOT belong to this question!";
					}
					else if (count($answer_ids) > 1 && !$settings["allow_multiple_answers"]) {
						$error_message = "Error: Only one answer is allowed!";
					}
					else {
						$status = true;
						
						if ($data["user_answers"] && !\QuizUtil::deleteUserAnswersByUserAndQuestionIds($brokers, $user_id, $data["question_id"]))
							$status = false;
						
						if ($status) {
							$data["user_answers"] = array();
							
							foreach ($answer_ids as $answer_id) {
								$new_data = array(
									"answer_id" => $answer_id,
									"user_id" => $user_id,
								);
							
								if (($settings["allow_insertion"] || $settings["allow_update"]) && !\QuizUtil::insertUserAnswer($brokers, $new_data))
									$status = false;
					
								$new_data["question_id"] = $data["question_id"];
								$data["user_answers"][] = $new_data;
							}
						}
					}
				}
				
				if ($status) {
					//Add Join Point creating a new action of some kind
					$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull answer question saving action", array(
						"EVC" => $EVC,
						"settings" => &$settings,
						"answer_question_data" => &$data,
						"error_message" => &$error_message,
					));
				}
			}
		}
		
		//Preparing questions html
		if ($data) {
			if ($settings["ptl"]) {
				//prepare new settings field
				$settings["fields"]["question_answers"] = array(
					"field" => array(
						"disable_field_group" => 1,
						"input" => array(
							"type" => "label",
							"value" => " ", //leave space on purpose, so the CommonModuleUI::getFormHtml does NOT replace it by #question_answers#
							"next_html" => \QuizUI::getQuestionAnswersHtml($settings, $data),
						),
					),
				);
				$settings["show_question_answers"] = 1;
			}
			else
				$settings["next_html"] = \QuizUI::getQuestionAnswersHtml($settings, $data);
		}
		
		$form_data = $settings["allow_view"] && $data ? $data : array();
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["class"] = "module_answer_question";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		if (empty($settings["style_type"]))
			$settings["css_file"] = $project_common_url_prefix . 'module/quiz/answer_question.css';
		
		if ($data["answers"])
			$settings["next_html"] = \QuizUI::getQuestionAnswersJavascript($settings) . $settings["next_html"]; //Preparing some javascript
		
		$settings["allow_deletion"] = false;//so it doesn't show the delete button. Leave this line here - at the end!
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "quiz/answer_question", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
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
