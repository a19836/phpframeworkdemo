<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

namespace CMSModule\quiz\show_question_users_answers;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("quiz/QuizUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Question Details
		$question_type = isset($settings["question_type"]) ? $settings["question_type"] : null;
		$object_type_id = isset($settings["get_next_by_parent"]["object_type_id"]) ? $settings["get_next_by_parent"]["object_type_id"] : null;
		$object_id = isset($settings["get_next_by_parent"]["object_id"]) ? $settings["get_next_by_parent"]["object_id"] : null;
		$group = isset($settings["get_next_by_parent"]["group"]) ? $settings["get_next_by_parent"]["group"] : null;
		$previous_order = isset($settings["get_next_by_parent"]["previous_order"]) ? $settings["get_next_by_parent"]["previous_order"] : null;
		
		switch ($question_type) {
			case "get_next_by_parent":
				$data = \QuizUtil::getQuestionsByObject($brokers, $object_type_id, $object_id);
				$data = self::getNextQuestion($data, $previous_order);
				break;
			case "get_next_by_parent_group":
				$data = \QuizUtil::getQuestionsByObjectGroup($brokers, $object_type_id, $object_id, $group);
				$data = self::getNextQuestion($data, $previous_order);
				break;
			default:
				$data = \QuizUtil::getQuestionsByConditions($brokers, array("question_id" => isset($settings["question_id"]) ? $settings["question_id"] : null), null);
				$data = isset($data[0]) ? $data[0] : null;
		}
		
		if ($data) {
			$question_id = isset($data["question_id"]) ? $data["question_id"] : null;
			$data["answers"] = \QuizUtil::getAnswersByConditions($brokers, array("question_id" => $question_id), null);
			$data["user_answers"] = \QuizUtil::getUserAnswersByQuestionIds($brokers, $question_id);
			
			//Add Join Point
			$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Preparing question data", array(
				"EVC" => $EVC,
				"settings" => &$settings,
				"data" => &$data,
			), "Use this join point to change the loaded question data.");
		}
		
		//Preparing questions html
		if ($data) {
			if (!empty($settings["ptl"])) {
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
		
		if (!empty($data["answers"])) {
			$user_answers_by_user_ids = array();
			$user_answer_ids_by_user_ids = array();
			
			if (!empty($data["user_answers"]))
				foreach ($data["user_answers"] as $ua) {
					$user_id = isset($ua["user_id"]) ? $ua["user_id"] : null;
					$answer_id = isset($ua["answer_id"]) ? $ua["answer_id"] : null;
					
					$user_answers_by_user_ids[$user_id][] = $ua;
					$user_answer_ids_by_user_ids[$user_id][$answer_id] = true;
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
							<div class="answer_title">' . (isset($answer["title"]) ? $answer["title"] : null) . '</div>
							<div class="answer_description">' . (isset($answer["description"]) ? $answer["description"] : null) . '</div>
						</th>';
			
			$html .= '	</tr>
				</thead>
				<tbody>';
			
			if ($user_answers_by_user_ids)
				foreach ($user_answers_by_user_ids as $user_id => $uas) {
					$html .= '<tr>
							<td class="user">' . (!empty($uas[0]["name"]) ? $uas[0]["name"] : (isset($uas[0]["username"]) ? $uas[0]["username"] : null)) . '</td>';
					
					foreach ($data["answers"] as $answer) {
						$answer_id = isset($answer["answer_id"]) ? $answer["answer_id"] : null;
						$selected = !empty($user_answer_ids_by_user_ids[$user_id][$answer_id]);
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
			foreach ($questions as $i => $item) {
				$order = isset($item["order"]) ? $item["order"] : null;
				
				if ($order > $previous_order && ($order < $idx || !$idx))
					$idx = $i;
			}
			
			if (!is_numeric($idx)) 
				$idx = 0;
			
			return isset($questions[$idx]) ? $questions[$idx] : null;
		}
	}
}
?>
