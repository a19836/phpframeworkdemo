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

namespace CMSModule\quiz\edit_answer;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("quiz/QuizUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Answer Details
		$answer_id = isset($_GET["answer_id"]) ? $_GET["answer_id"] : null;
		$data = $answer_id ? \QuizUtil::getAnswersByConditions($brokers, array("answer_id" => $answer_id), null, false, true) : null;
		$data = isset($data[0]) ? $data[0] : null;
		
		//Preparing Answer
		if (!empty($_POST)) {
			if (!empty($_POST["delete"]) && !empty($settings["allow_deletion"])) {
				$data_answer_id = isset($data["answer_id"]) ? $data["answer_id"] : null;
				
				$status = !$data || (\QuizUtil::deleteUserAnswersByAnswerId($brokers, $data_answer_id) && \QuizUtil::deleteAnswer($brokers, $data_answer_id));
				
				if ($status) {
					//Add Join Point creating a new action of some kind
					$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull answer deleting action", array(
						"EVC" => &$EVC,
						"answer_id" => $data_answer_id,
						"answer_data" => &$data,
						"error_message" => &$error_message,
					));
				}
			}
			else if (!empty($_POST["save"])) {
				$question_id = isset($_POST["question_id"]) ? $_POST["question_id"] : null;
				$title = isset($_POST["title"]) ? $_POST["title"] : null;
				$description = isset($_POST["description"]) ? $_POST["description"] : null;
				$value = isset($_POST["value"]) ? $_POST["value"] : null;
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("question_id" => $question_id, "title" => $title, "description" => $description, "value" => $value));
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				}
				else {
					$new_data = $data;
					$new_data["question_id"] = !empty($settings["show_question_id"]) ? $question_id : (isset($new_data["question_id"]) ? $new_data["question_id"] : null);
					$new_data["title"] = !empty($settings["show_title"]) ? $title : (isset($new_data["title"]) ? $new_data["title"] : null);
					$new_data["description"] = !empty($settings["show_description"]) ? $description : (isset($new_data["description"]) ? $new_data["description"] : null);
					$new_data["value"] = !empty($settings["show_value"]) ? $value : (isset($new_data["value"]) ? $new_data["value"] : null);
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						if (!empty($settings["allow_insertion"]) && empty($data["answer_id"])) {
							$status = \QuizUtil::insertAnswer($brokers, $new_data);
							if (isset($settings["on_insert_ok_action"]) && strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (isset($settings["on_insert_ok_redirect_url"]) && strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "answer_id=$status";
							}
						}
						else if (!empty($settings["allow_update"]) && !empty($data["answer_id"])) {
							$status = \QuizUtil::updateAnswer($brokers, $new_data);
						}
						
						if ($status) {
							$answer_id = !empty($settings["allow_insertion"]) && empty($data["question_id"]) ? $status : $answer_id;
							
							//Prepare inline html images
							$data_description = isset($data["description"]) ? $data["description"] : null;
							
							if ($new_data["description"] != $data_description) {
								$this->prepareAnswerHtmlAttributes($EVC, $settings, $answer_id, $new_data, $status);
								$aux = $new_data;
								$aux["answer_id"] = $answer_id;
								if (!\QuizUtil::updateAnswer($brokers, $aux))
									$status = false;
								
								$description = !empty($settings["show_description"]) ? $new_data["description"] : $description;
							}
						}
					}
				}
			}
		}
		
		if (!empty($_POST["save"])) {
			$form_data = array(
				"answer_id" => !empty($settings["show_answer_id"]) ? $answer_id : (isset($data["answer_id"]) ? $data["answer_id"] : null),
				"question_id" => !empty($settings["show_question_id"]) ? $question_id : (isset($data["question_id"]) ? $data["question_id"] : null),
				"title" => !empty($settings["show_title"]) ? $title : (isset($data["title"]) ? $data["title"] : null),
				"description" => !empty($settings["show_description"]) ? $description : (isset($data["description"]) ? $data["description"] : null),
				"value" => !empty($settings["show_value"]) ? $value : (isset($data["value"]) ? $data["value"] : null),
			);
			$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : (!empty($settings["allow_view"]) && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/quiz/edit_answer.css';
		$settings["class"] = "module_edit_answer";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		$is_insertion = !empty($settings["allow_insertion"]) && !$data;
		
		if (!empty($settings["show_answer_id"]))
			$settings["fields"]["answer_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		if (!empty($settings["show_value"]))
			$settings["fields"]["value"]["field"]["input"]["type"] = "number";
		
		if (!empty($settings["show_description"]))
			$settings["fields"]["description"]["field"]["input"]["type"] = "textarea";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "quiz/edit_answer", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
	
	private function prepareAnswerHtmlAttributes($EVC, $settings, $answer_id, &$answer_data, &$status = false) {
		$upload_url = isset($settings["upload_url"]) ? str_replace("#answer_id#", $answer_id, str_replace("#group#", \QuizUtil::ANSWER_DESCRIPTION_HTML_IMAGE_GROUP_ID, $settings["upload_url"])) : "";
		$description = isset($answer_data["description"]) ? $answer_data["description"] : null;
		$regex = isset($settings["attachment_id_regex"]) ? $settings["attachment_id_regex"] : null;
		
		\CommonModuleUtil::prepareObjectHtmlContent($EVC, $description, \ObjectUtil::QUIZ_ANSWER_OBJECT_TYPE_ID, $answer_id, \QuizUtil::ANSWER_DESCRIPTION_HTML_IMAGE_GROUP_ID, $regex, $upload_url, $status);
		
		return $status;
	}
}
?>
