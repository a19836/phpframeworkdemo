<?php
namespace CMSModule\quiz\edit_question;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("quiz/QuizUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Question Details
		$question_id = $_GET["question_id"];
		$data = $question_id ? \QuizUtil::getQuestionsByConditions($brokers, array("question_id" => $question_id), null, false, true) : null;
		$data = $data[0];
		
		//Preparing Question
		if ($_POST) {
			if ($_POST["delete"] && $settings["allow_deletion"]) {
				$status = !$data || (\QuizUtil::deleteUserAnswersByQuestionIds($brokers, $data["question_id"]) && \QuizUtil::deleteAnswersByQuestionId($brokers, $data["question_id"]) && \QuizUtil::deleteQuestion($brokers, $data["question_id"]));
				
				if ($status) {
					//Add Join Point creating a new action of some kind
					$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull question deleting action", array(
						"EVC" => &$EVC,
						"question_id" => $data["question_id"],
						"question_data" => &$data,
						"error_message" => &$error_message,
					));
				}
			}
			else if ($_POST["save"]) {
				$title = $_POST["title"];
				$description = $_POST["description"];
				$published = $_POST["published"];
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("title" => $title, "description" => $description, "published" => $published));
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				}
				else {
					$new_data = $data;
					$new_data["title"] = $settings["show_title"] ? $title : $new_data["title"];
					$new_data["description"] = $settings["show_description"] ? $description : $new_data["description"];
					$new_data["published"] = $settings["show_published"] ? $published : $new_data["published"];
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						$new_data["object_questions"] = $settings["object_to_objects"];
						
						if ($settings["allow_insertion"] && empty($data["question_id"])) {
							$status = \QuizUtil::insertQuestion($brokers, $new_data);
							if (strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "question_id=$status";
							}
						}
						else if ($settings["allow_update"] && $data["question_id"]) {
							$status = \QuizUtil::updateQuestion($brokers, $new_data);
						}
						
						if ($status) {
							$question_id = $settings["allow_insertion"] && empty($data["question_id"]) ? $status : $question_id;
							
							//Prepare inline html images
							if ($new_data["description"] != $data["description"]) {
								$this->prepareQuestionHtmlAttributes($EVC, $settings, $question_id, $new_data, $status);
								$aux = $new_data;
								$aux["question_id"] = $question_id;
								if (!\QuizUtil::updateQuestion($brokers, $aux))
									$status = false;
								
								$description = $settings["show_description"] ? $new_data["description"] : $description;
							}
						}
					}
				}
			}
		}
		
		if ($_POST["save"]) {
			$form_data = array(
				"question_id" => $settings["show_question_id"] ? $question_id : $data["question_id"],
				"title" => $settings["show_title"] ? $title : $data["title"],
				"description" => $settings["show_description"] ? $description : $data["description"],
				"published" => $settings["show_published"] ? $published : $data["published"],
			);
			$form_data = $new_data ? array_merge($new_data, $form_data) : ($settings["allow_view"] && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = $settings["allow_view"] && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/quiz/edit_question.css';
		$settings["class"] = "module_edit_question";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		$is_insertion = $settings["allow_insertion"] && !$data;
		
		if ($settings["show_question_id"])
			$settings["fields"]["question_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		if ($settings["show_description"])
			$settings["fields"]["description"]["field"]["input"]["type"] = "textarea";
		
		if ($settings["show_published"]) {
			$settings["fields"]["published"]["field"]["input"]["type"] = "checkbox";
			$settings["fields"]["published"]["field"]["input"]["options"] = array(
				array("value" => 1)
			);
		}
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "quiz/edit_question", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
	
	private function prepareQuestionHtmlAttributes($EVC, $settings, $question_id, &$question_data, &$status = false) {
		$upload_url = str_replace("#question_id#", $question_id, str_replace("#group#", \QuizUtil::QUESTION_DESCRIPTION_HTML_IMAGE_GROUP_ID, $settings["upload_url"]));
		\CommonModuleUtil::prepareObjectHtmlContent($EVC, $question_data["description"], \ObjectUtil::QUIZ_QUESTION_OBJECT_TYPE_ID, $question_id, \QuizUtil::QUESTION_DESCRIPTION_HTML_IMAGE_GROUP_ID, $settings["attachment_id_regex"], $upload_url, $status);
		
		return $status;
	}
}
?>
