<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

namespace CMSModule\quiz\answer_questions;

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
		
		//Getting Questions
		$questions_type = isset($settings["questions_type"]) ? $settings["questions_type"] : null;
		$object_type_id = isset($settings["object_type_id"]) ? $settings["object_type_id"] : null;
		$object_id = isset($settings["object_id"]) ? $settings["object_id"] : null;
		$group = isset($settings["group"]) ? $settings["group"] : null;
		$data = null;
		
		switch ($questions_type) {
			case "get_by_parent":
				$data = \QuizUtil::getQuestionsByObject($brokers, $object_type_id, $object_id);
				break;
			case "get_by_parent_group":
				$data = \QuizUtil::getQuestionsByObjectGroup($brokers, $object_type_id, $object_id, $group);
				break;
		}
		
		if ($data) {
			$idx = null;
			$question_ids = array();
			$questions_by_ids = array();
			foreach ($data as $idx => $item) {
				$item_question_id = isset($item["question_id"]) ? $item["question_id"] : null;
				$questions_by_ids[$item_question_id] = $idx;
			}
			
			$question_ids = array_keys($questions_by_ids);
			
			$answers = \QuizUtil::getAnswersByConditions($brokers, array("question_id" => array("operator" => "in", "value" => $question_ids)), null);
			$user_answers = \QuizUtil::getUserAnswersByUserAndQuestionIds($brokers, $user_id, $question_ids);
			
			$data[$idx]["answers"]= array();
			$question_ids_by_answer_ids = array();
			if ($answers)
				foreach ($answers as $answer) {
					$answer_id = isset($answer["answer_id"]) ? $answer["answer_id"] : null;
					$answer_question_id = isset($answer["question_id"]) ? $answer["question_id"] : null;
					
					$idx = isset($questions_by_ids[$answer_question_id]) ? $questions_by_ids[$answer_question_id] : null;
					$data[$idx]["answers"][] = $answer;
					$question_ids_by_answer_ids[$answer_id] = $answer_question_id;
				}
			
			$data[$idx]["user_answers"]= array();
			if ($user_answers)
				foreach ($user_answers as $user_answer) {
					$user_answer_question_id = isset($user_answer["question_id"]) ? $user_answer["question_id"] : null;
					$idx = isset($questions_by_ids[$user_answer_question_id]) ? $questions_by_ids[$user_answer_question_id] : null;
					$data[$idx]["user_answers"][] = $user_answer;
				}
		}
		
		//Preparing Question
		if (!empty($_POST) && $user_id && $data) {
			if (!empty($_POST["save"])) {
				$answer_ids = isset($_POST["answer_ids"]) ? $_POST["answer_ids"] : null;
				
				if (!$answer_ids && !empty($settings["allow_deletion"])) {
					$status = !$data || \QuizUtil::deleteUserAnswersByUserAndQuestionIds($brokers, $user_id, $question_ids);
					
					if ($status && $data)
						foreach ($data as &$question)
							unset($question["user_answers"]);
				}
				else if (!$answer_ids) {
					$error_message = "You must selected at least one answer!";
				}
				else {
					$exists = true;
					$is_multiple = false;
					foreach ($answer_ids as $question_id => $quas) {
						foreach ($quas as $answer_id) {
							$question_id_by_answer_id = isset($question_ids_by_answer_ids[$answer_id]) ? $question_ids_by_answer_ids[$answer_id] : null;
							
							if ($question_id_by_answer_id != $question_id || !$question_id)
								$exists = false;
						}
						
						if ($quas && count($quas) > 1)
							$is_multiple = true;
					}
					
					if (!$exists) { //This cover the cases where the $answer_ids doesn't exist and allow_deletion is not permitted and where the user tries to hack the system with different answer ids...
						$error_message = "Error: Answer does NOT belong to this question!";
					}
					else if ($is_multiple && empty($settings["allow_multiple_answers"])) {
						$error_message = "Error: Only one answer per question is allowed!";
					}
					else {
						$status = true;
						
						foreach ($data as &$question) {
							$question_id = isset($question["question_id"]) ? $question["question_id"] : null;
							$user_answer_ids = isset($answer_ids[$question_id]) ? $answer_ids[$question_id] : null;
							
							if (!$user_answer_ids) {
								if (!empty($settings["allow_deletion"])) {
									if (\QuizUtil::deleteUserAnswersByUserAndQuestionIds($brokers, $user_id, $question_id))
										unset($question["user_answers"]);
									else
										$status = false;
								}
								else {
									$status = false;
									$error_message = "Error: You must select at least one answer per question.";
								}
							}
							else {
								if (\QuizUtil::deleteUserAnswersByUserAndQuestionIds($brokers, $user_id, $question_id))
									$question["user_answers"] = array();
								else
									$status = false;
								
								foreach ($user_answer_ids as $answer_id) {
									$new_data = array(
										"answer_id" => $answer_id,
										"user_id" => $user_id,
									);
				
									if ((!empty($settings["allow_insertion"]) || !empty($settings["allow_update"])) && !\QuizUtil::insertUserAnswer($brokers, $new_data))
										$status = false;
									
									$new_data["question_id"] = $question_id;
									$question["user_answers"][] = $new_data;
								}
							}
						}
					}
				}
			}
		}
		
		//Preparing question fields
		$elements = array();
		foreach ($settings["fields"] as $field_id => $field)
			if (!empty($settings["show_" . $field_id]))
				$elements[] = $field;
		
		$form_settings = array(
			"with_form" => 0,
			"form_containers" => array(
				0 => array(
					"container" => array(
						"class" => "question",
						"elements" => $elements,
					)
				),
			)
		);
		
		//Preparing questions html
		if ($data) {
			if (!empty($settings["ptl"])) {
				$html = '';
				
				foreach ($data as &$question) {//Leave the & here, otherwise the foreach will not work properly on the save action!
					$form_settings["form_containers"][0]["container"]["next_html"] = \QuizUI::getQuestionAnswersHtml($settings, $question);
					$html .= \HtmlFormHandler::createHtmlForm($form_settings, $question);
				}
				
				//prepare new settings field
				$settings["fields"] = array();
				$settings["fields"]["questions_answers"] = array(
					"field" => array(
						"disable_field_group" => 1,
						"input" => array(
							"type" => "label",
							"value" => " ", //leave space on purpose, so the CommonModuleUI::getFormHtml does NOT replace it by #questions_answers#
							"next_html" => $html,
						),
					),
				);
				$settings["show_questions_answers"] = 1;
			}
			else {
				$html = '';
				
				foreach ($data as &$question) {//Leave the & here, otherwise the foreach will not work properly on the save action!
					$form_settings["form_containers"][0]["container"]["next_html"] = \QuizUI::getQuestionAnswersHtml($settings, $question);
					$html .= \HtmlFormHandler::createHtmlForm($form_settings, $question);
				}
				
				$settings["fields"] = array();
				$settings["next_html"] = $html;
			}
		}
		
		$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["class"] = "module_answer_questions";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		if (empty($settings["style_type"]))
			$settings["css_file"] = $project_common_url_prefix . 'module/quiz/answer_questions.css';
		
		$settings["next_html"] = \QuizUI::getQuestionAnswersJavascript($settings) . (isset($settings["next_html"]) ? $settings["next_html"] : ""); //Preparing some javascript
		$form_settings["CacheHandler"] = $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler");
		$settings["allow_deletion"] = false;//so it doesn't show the delete button. Leave this line here - at the end!
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "quiz/answer_questions", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
