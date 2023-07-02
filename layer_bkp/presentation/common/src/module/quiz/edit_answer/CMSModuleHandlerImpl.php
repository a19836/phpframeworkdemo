<?php
namespace CMSModule\quiz\edit_answer;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("quiz/QuizUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Answer Details
		$answer_id = $_GET["answer_id"];
		$data = $answer_id ? \QuizUtil::getAnswersByConditions($brokers, array("answer_id" => $answer_id), null, false, true) : null;
		$data = $data[0];
		
		//Preparing Answer
		if ($_POST) {
			if ($_POST["delete"] && $settings["allow_deletion"]) {
				$status = !$data || (\QuizUtil::deleteUserAnswersByAnswerId($brokers, $data["answer_id"]) && \QuizUtil::deleteAnswer($brokers, $data["answer_id"]));
				
				if ($status) {
					//Add Join Point creating a new action of some kind
					$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull answer deleting action", array(
						"EVC" => &$EVC,
						"answer_id" => $data["answer_id"],
						"answer_data" => &$data,
						"error_message" => &$error_message,
					));
				}
			}
			else if ($_POST["save"]) {
				$question_id = $_POST["question_id"];
				$title = $_POST["title"];
				$description = $_POST["description"];
				$value = $_POST["value"];
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("question_id" => $question_id, "title" => $title, "description" => $description, "value" => $value));
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				}
				else {
					$new_data = $data;
					$new_data["question_id"] = $settings["show_question_id"] ? $question_id : $new_data["question_id"];
					$new_data["title"] = $settings["show_title"] ? $title : $new_data["title"];
					$new_data["description"] = $settings["show_description"] ? $description : $new_data["description"];
					$new_data["value"] = $settings["show_value"] ? $value : $new_data["value"];
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						if ($settings["allow_insertion"] && empty($data["answer_id"])) {
							$status = \QuizUtil::insertAnswer($brokers, $new_data);
							if (strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "answer_id=$status";
							}
						}
						else if ($settings["allow_update"] && $data["answer_id"]) {
							$status = \QuizUtil::updateAnswer($brokers, $new_data);
						}
						
						if ($status) {
							$answer_id = $settings["allow_insertion"] && empty($data["question_id"]) ? $status : $answer_id;
							
							//Prepare inline html images
							if ($new_data["description"] != $data["description"]) {
								$this->prepareAnswerHtmlAttributes($EVC, $settings, $answer_id, $new_data, $status);
								$aux = $new_data;
								$aux["answer_id"] = $answer_id;
								if (!\QuizUtil::updateAnswer($brokers, $aux))
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
				"answer_id" => $settings["show_answer_id"] ? $answer_id : $data["answer_id"],
				"question_id" => $settings["show_question_id"] ? $question_id : $data["question_id"],
				"title" => $settings["show_title"] ? $title : $data["title"],
				"description" => $settings["show_description"] ? $description : $data["description"],
				"value" => $settings["show_value"] ? $value : $data["value"],
			);
			$form_data = $new_data ? array_merge($new_data, $form_data) : ($settings["allow_view"] && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = $settings["allow_view"] && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/quiz/edit_answer.css';
		$settings["class"] = "module_edit_answer";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		$is_insertion = $settings["allow_insertion"] && !$data;
		
		if ($settings["show_answer_id"])
			$settings["fields"]["answer_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		if ($settings["show_value"])
			$settings["fields"]["value"]["field"]["input"]["type"] = "number";
		
		if ($settings["show_description"])
			$settings["fields"]["description"]["field"]["input"]["type"] = "textarea";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "quiz/edit_answer", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
	
	private function prepareAnswerHtmlAttributes($EVC, $settings, $answer_id, &$answer_data, &$status = false) {
		$upload_url = str_replace("#answer_id#", $answer_id, str_replace("#group#", \QuizUtil::ANSWER_DESCRIPTION_HTML_IMAGE_GROUP_ID, $settings["upload_url"]));
		\CommonModuleUtil::prepareObjectHtmlContent($EVC, $answer_data["description"], \ObjectUtil::QUIZ_ANSWER_OBJECT_TYPE_ID, $answer_id, \QuizUtil::ANSWER_DESCRIPTION_HTML_IMAGE_GROUP_ID, $settings["attachment_id_regex"], $upload_url, $status);
		
		return $status;
	}
}
?>
