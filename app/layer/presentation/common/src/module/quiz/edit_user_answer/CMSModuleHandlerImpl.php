<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

namespace CMSModule\quiz\edit_user_answer;

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
		
		$settings["allow_update"] = false;
		
		//Getting User Answers
		$user_id = isset($_GET["user_id"]) ? $_GET["user_id"] : null;
		$answer_id = isset($_GET["answer_id"]) ? $_GET["answer_id"] : null;
		
		$data = $user_id && $answer_id ? \QuizUtil::getUserAnswersByConditions($brokers, array("user_id" => $user_id, "answer_id" => $answer_id), null, null, true) : null;
		$data = isset($data[0]) ? $data[0] : null;
		
		//Preparing Answer
		if (!empty($_POST)) {
			if (!empty($_POST["delete"]) && !empty($settings["allow_deletion"])) {
				$data_user_id = isset($data["user_id"]) ? $data["user_id"] : null;
				$data_answer_id = isset($data["answer_id"]) ? $data["answer_id"] : null;
				
				$status = !$data || \QuizUtil::deleteUserAnswer($brokers, $data_user_id, $data_answer_id);
			}
			else if (!empty($_POST["save"])) {
				if (!empty($settings["allow_insertion"]) && empty($data)) {
					$user_id = isset($_POST["user_id"]) ? $_POST["user_id"] : null;
					$answer_id = isset($_POST["answer_id"]) ? $_POST["answer_id"] : null;
					
					$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("user_id" => $user_id, "answer_id" => $answer_id));
					if ($empty_field_name) {
						$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
					}
					else {
						$new_data = array(
							"user_id" => $user_id,
							"answer_id" => $answer_id,
						);
						
						\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
						
						if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
							$status = \QuizUtil::insertUserAnswer($brokers, $new_data);
							if (isset($settings["on_insert_ok_action"]) && strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (isset($settings["on_insert_ok_redirect_url"]) && strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "user_id=$user_id&answer_id=$answer_id";
							}
						}
					}
				}
			}
		}
		
		if (!empty($_POST["save"])) {
			$form_data = array(
				"user_id" => !empty($settings["show_user_id"]) ? $user_id : (isset($data["user_id"]) ? $data["user_id"] : null),
				"answer_id" => !empty($settings["show_answer_id"]) ? $answer_id : (isset($data["answer_id"]) ? $data["answer_id"] : null),
			);
			$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : (!empty($settings["allow_view"]) && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/quiz/edit_user_answer.css';
		$settings["class"] = "module_edit_user_answer";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		$is_insertion = !empty($settings["allow_insertion"]) && !$data;
		
		if (!empty($settings["show_user_id"])) 
			\CommonModuleUtil::prepareUserIdFormSettingsField($EVC, $settings, $is_insertion);
		
		if (!empty($settings["show_answer_id"]))
			$settings["fields"]["answer_id"]["field"]["input"]["type"] = $is_insertion ? "text" : "label";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "quiz/edit_user_answer", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
