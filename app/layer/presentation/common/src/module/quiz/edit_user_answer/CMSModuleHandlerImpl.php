<?php
namespace CMSModule\quiz\edit_user_answer;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("quiz/QuizUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$settings["allow_update"] = false;
		
		//Getting User Answers
		$user_id = $_GET["user_id"];
		$answer_id = $_GET["answer_id"];
		
		$data = $user_id && $answer_id ? \QuizUtil::getUserAnswersByConditions($brokers, array("user_id" => $user_id, "answer_id" => $answer_id), null, null, true) : null;
		$data = $data[0];
		
		//Preparing Answer
		if ($_POST) {
			if ($_POST["delete"] && $settings["allow_deletion"]) {
				$status = !$data || \QuizUtil::deleteUserAnswer($brokers, $data["user_id"], $data["answer_id"]);
			}
			else if ($_POST["save"]) {
				if ($settings["allow_insertion"] && empty($data)) {
					$user_id = $_POST["user_id"];
					$answer_id = $_POST["answer_id"];
					
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
							if (strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "user_id=$user_id&answer_id=$answer_id";
							}
						}
					}
				}
			}
		}
		
		if ($_POST["save"]) {
			$form_data = array(
				"user_id" => $settings["show_user_id"] ? $user_id : $data["user_id"],
				"answer_id" => $settings["show_answer_id"] ? $answer_id : $data["answer_id"],
			);
			$form_data = $new_data ? array_merge($new_data, $form_data) : ($settings["allow_view"] && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = $settings["allow_view"] && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/quiz/edit_user_answer.css';
		$settings["class"] = "module_edit_user_answer";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		$is_insertion = $settings["allow_insertion"] && !$data;
		
		if ($settings["show_user_id"]) 
			\CommonModuleUtil::prepareUserIdFormSettingsField($EVC, $settings, $is_insertion);
		
		if ($settings["show_answer_id"])
			$settings["fields"]["answer_id"]["field"]["input"]["type"] = $is_insertion ? "text" : "label";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "quiz/edit_user_answer", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
