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

namespace CMSModule\quiz\edit_question;

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
		
		//Getting Question Details
		$question_id = isset($_GET["question_id"]) ? $_GET["question_id"] : null;
		$data = $question_id ? \QuizUtil::getQuestionsByConditions($brokers, array("question_id" => $question_id), null, false, true) : null;
		$data = isset($data[0]) ? $data[0] : null;
		
		//Preparing Question
		if (!empty($_POST)) {
			if (!empty($_POST["delete"]) && !empty($settings["allow_deletion"])) {
				$data_question_id = isset($data["question_id"]) ? $data["question_id"] : null;
				
				$status = !$data || (\QuizUtil::deleteUserAnswersByQuestionIds($brokers, $data_question_id) && \QuizUtil::deleteAnswersByQuestionId($brokers, $data_question_id) && \QuizUtil::deleteQuestion($brokers, $data_question_id));
				
				if ($status) {
					//Add Join Point creating a new action of some kind
					$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull question deleting action", array(
						"EVC" => &$EVC,
						"question_id" => $data_question_id,
						"question_data" => &$data,
						"error_message" => &$error_message,
					));
				}
			}
			else if (!empty($_POST["save"])) {
				$title = isset($_POST["title"]) ? $_POST["title"] : null;
				$description = isset($_POST["description"]) ? $_POST["description"] : null;
				$published = isset($_POST["published"]) ? $_POST["published"] : null;
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("title" => $title, "description" => $description, "published" => $published));
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				}
				else {
					$new_data = $data;
					$new_data["title"] = !empty($settings["show_title"]) ? $title : (isset($new_data["title"]) ? $new_data["title"] : null);
					$new_data["description"] = !empty($settings["show_description"]) ? $description : (isset($new_data["description"]) ? $new_data["description"] : null);
					$new_data["published"] = !empty($settings["show_published"]) ? $published : (isset($new_data["published"]) ? $new_data["published"] : null);
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						$new_data["object_questions"] = isset($settings["object_to_objects"]) ? $settings["object_to_objects"] : null;
						
						if (!empty($settings["allow_insertion"]) && empty($data["question_id"])) {
							$status = \QuizUtil::insertQuestion($brokers, $new_data);
							if (isset($settings["on_insert_ok_action"]) && strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (isset($settings["on_insert_ok_redirect_url"]) && strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "question_id=$status";
							}
						}
						else if (!empty($settings["allow_update"]) && !empty($data["question_id"])) {
							$status = \QuizUtil::updateQuestion($brokers, $new_data);
						}
						
						if ($status) {
							$question_id = !empty($settings["allow_insertion"]) && empty($data["question_id"]) ? $status : $question_id;
							
							//Prepare inline html images
							$data_description = isset($data["description"]) ? $data["description"] : null;
							
							if ($new_data["description"] != $data_description) {
								$this->prepareQuestionHtmlAttributes($EVC, $settings, $question_id, $new_data, $status);
								$aux = $new_data;
								$aux["question_id"] = $question_id;
								if (!\QuizUtil::updateQuestion($brokers, $aux))
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
				"question_id" => !empty($settings["show_question_id"]) ? $question_id : (isset($data["question_id"]) ? $data["question_id"] : null),
				"title" => !empty($settings["show_title"]) ? $title : (isset($data["title"]) ? $data["title"] : null),
				"description" => !empty($settings["show_description"]) ? $description : (isset($data["description"]) ? $data["description"] : null),
				"published" => !empty($settings["show_published"]) ? $published : (isset($data["published"]) ? $data["published"] : null),
			);
			$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : (!empty($settings["allow_view"]) && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/quiz/edit_question.css';
		$settings["class"] = "module_edit_question";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		$is_insertion = !empty($settings["allow_insertion"]) && !$data;
		
		if (!empty($settings["show_question_id"]))
			$settings["fields"]["question_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		if (!empty($settings["show_description"]))
			$settings["fields"]["description"]["field"]["input"]["type"] = "textarea";
		
		if (!empty($settings["show_published"])) {
			$settings["fields"]["published"]["field"]["input"]["type"] = "checkbox";
			$settings["fields"]["published"]["field"]["input"]["options"] = array(
				array("value" => 1)
			);
		}
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "quiz/edit_question", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
	
	private function prepareQuestionHtmlAttributes($EVC, $settings, $question_id, &$question_data, &$status = false) {
		$upload_url = isset($settings["upload_url"]) ? str_replace("#question_id#", $question_id, str_replace("#group#", \QuizUtil::QUESTION_DESCRIPTION_HTML_IMAGE_GROUP_ID, $settings["upload_url"])) : "";
		$description = isset($question_data["description"]) ? $question_data["description"] : null;
		$regex = isset($settings["attachment_id_regex"]) ? $settings["attachment_id_regex"] : null;
		
		\CommonModuleUtil::prepareObjectHtmlContent($EVC, $description, \ObjectUtil::QUIZ_QUESTION_OBJECT_TYPE_ID, $question_id, \QuizUtil::QUESTION_DESCRIPTION_HTML_IMAGE_GROUP_ID, $regex, $upload_url, $status);
		
		return $status;
	}
}
?>
