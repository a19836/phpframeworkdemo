<?php
namespace CMSModule\quiz\answer_question;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("quiz/QuizUtil", $common_project_name);
		include_once $EVC->getModulePath("quiz/QuizUI", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting User
		$session_id = isset($settings["session_id"]) ? $settings["session_id"] : null;
		$user_id = isset($settings["user_id"]) ? $settings["user_id"] : null;
		
		if (!$user_id && $session_id) {
			include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
	
			$session_data = $session_id ? \UserUtil::getUserSessionsByConditions($brokers, array("session_id" => $session_id), null) : null;
			
			if (isset($session_data[0]["user_id"])) {
				$user_data = \UserUtil::getUsersByConditions($brokers, array("user_id" => $session_data[0]["user_id"]), null);
				$user_id = isset($user_data[0]["user_id"]) ? $user_data[0]["user_id"] : null;
			}
		}
		
		//Getting Question Details
		$question_type = isset($settings["question_type"]) ? $settings["question_type"] : null;
		$get_next_by_parent_object_type_id = isset($settings["get_next_by_parent"]["object_type_id"]) ? $settings["get_next_by_parent"]["object_type_id"] : null;
		$get_next_by_parent_object_id = isset($settings["get_next_by_parent"]["object_id"]) ? $settings["get_next_by_parent"]["object_id"] : null;
		$get_next_by_parent_previous_order = isset($settings["get_next_by_parent"]["previous_order"]) ? $settings["get_next_by_parent"]["previous_order"] : null;
		$get_next_by_parent_group = isset($settings["get_next_by_parent"]["group"]) ? $settings["get_next_by_parent"]["group"] : null;
		
		switch ($question_type) {
			case "get_next_by_parent":
				$data = \QuizUtil::getQuestionsByObject($brokers, $get_next_by_parent_object_type_id, $get_next_by_parent_object_id);
				$data = self::getNextQuestion($data, $get_next_by_parent_previous_order);
				break;
			case "get_next_by_parent_group":
				$data = \QuizUtil::getQuestionsByObjectGroup($brokers, $get_next_by_parent_object_type_id, $get_next_by_parent_object_id, $get_next_by_parent_group);
				$data = self::getNextQuestion($data, $get_next_by_parent_previous_order);
				break;
			default:
				$data = isset($settings["question_id"]) ? \QuizUtil::getQuestionsByConditions($brokers, array("question_id" => $settings["question_id"]), null) : null;
				$data = isset($data[0]) ? $data[0] : null;
		}
		
		if ($data) {
			$question_id = isset($data["question_id"]) ? $data["question_id"] : null;
			$data["answers"] = \QuizUtil::getAnswersByConditions($brokers, array("question_id" => $question_id), null);
			$data["user_answers"] = \QuizUtil::getUserAnswersByUserAndQuestionIds($brokers, $user_id, $question_id);
			
			//Add Join Point
			$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Preparing answer question data", array(
				"EVC" => $EVC,
				"settings" => &$settings,
				"answer_question_data" => &$data,
			), "Use this join point to change the loaded question data.");
		}
		
		//Preparing Question
		if (!empty($_POST) && $user_id && $data) {
			if (!empty($_POST["save"])) {
				$answer_ids = isset($_POST["answer_ids"][$question_id]) ? $_POST["answer_ids"][$question_id] : null;
				
				if (!$answer_ids && !empty($settings["allow_deletion"])) {
					$status = empty($data["user_answers"]) || \QuizUtil::deleteUserAnswersByUserAndQuestionIds($brokers, $user_id, $question_id);
					
					if ($status)
						unset($data["user_answers"]);
				}
				else if (!$answer_ids) {
					$error_message = "You must selected at least one answer!";
				}
				else {
					$exists = false;
					
					if (!empty($data["answers"]) && $answer_ids) {
						$available_answer_ids = array();
						foreach ($data["answers"] as $answer)
							$available_answer_ids[] = isset($answer["answer_id"]) ? $answer["answer_id"] : null;
							
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
					else if (count($answer_ids) > 1 && empty($settings["allow_multiple_answers"])) {
						$error_message = "Error: Only one answer is allowed!";
					}
					else {
						$status = true;
						
						if (!empty($data["user_answers"]) && !\QuizUtil::deleteUserAnswersByUserAndQuestionIds($brokers, $user_id, $question_id))
							$status = false;
						
						if ($status) {
							$data["user_answers"] = array();
							
							foreach ($answer_ids as $answer_id) {
								$new_data = array(
									"answer_id" => $answer_id,
									"user_id" => $user_id,
								);
							
								if ((!empty($settings["allow_insertion"]) || !empty($settings["allow_update"])) && !\QuizUtil::insertUserAnswer($brokers, $new_data))
									$status = false;
					
								$new_data["question_id"] = $question_id;
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
			if (!empty($settings["ptl"])) {
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
		
		$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["class"] = "module_answer_question";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		if (empty($settings["style_type"]))
			$settings["css_file"] = $project_common_url_prefix . 'module/quiz/answer_question.css';
		
		if (!empty($data["answers"]))
			$settings["next_html"] = \QuizUI::getQuestionAnswersJavascript($settings) . (isset($settings["next_html"]) ? $settings["next_html"] : null); //Preparing some javascript
		
		$settings["allow_deletion"] = false;//so it doesn't show the delete button. Leave this line here - at the end!
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "quiz/answer_question", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
	
	private static function getNextQuestion($questions, $previous_order) {
		if ($questions) {
			$idx = null;
			foreach ($questions as $i => $item) {
				$item_order = isset($item["order"]) ? $item["order"] : null;
				
				if ($item_order > $previous_order && ($item_order < $idx || !$idx))
					$idx = $i;
			}
			
			if (!is_numeric($idx)) 
				$idx = 0;
			
			return $questions[$idx];
		}
	}
}
?>
