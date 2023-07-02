<?php
namespace CMSModule\comment\edit_comment;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("comment/CommentUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Comment Details
		$comment_id = $_GET["comment_id"];
		$data = $comment_id ? \CommentUtil::getCommentsByConditions($brokers, array("comment_id" => $comment_id), null, null, true) : null;
		$data = $data[0];
		
		//Preparing Action
		if ($_POST) {
			if ($_POST["delete"] && $settings["allow_deletion"]) {
				$status = !$data || \CommentUtil::deleteComment($brokers, $data["comment_id"]);
			}
			else if ($_POST["save"]) {
				$user_id = $_POST["user_id"];
				$comment = $_POST["comment"];
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("user_id" => $user_id, "comment" => $comment));
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				}
				else {
					$new_data = $data;
					$new_data["user_id"] = $settings["show_user_id"] ? $user_id : $new_data["user_id"];
					$new_data["comment"] = $settings["show_comment"] ? $comment : $new_data["comment"];
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						$new_data["object_comments"] = $settings["object_to_objects"];
						
						if ($settings["allow_insertion"] && empty($data["comment_id"])) {
							$status = \CommentUtil::insertComment($brokers, $new_data);
							if (strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "comment_id=$status";
							}
						}
						else if ($settings["allow_update"] && $data["comment_id"]) {
							$status = \CommentUtil::updateComment($brokers, $new_data);
						}
					}
				}
			}
		}
		
		if ($_POST["save"]) {
			$form_data = array(
				"comment_id" => $settings["show_comment_id"] ? $comment_id : $data["comment_id"],
				"user_id" => $settings["show_user_id"] ? $user_id : $data["user_id"],
				"comment" => $settings["show_comment"] ? $comment : $data["comment"],
			);
			$form_data = $new_data ? array_merge($new_data, $form_data) : ($settings["allow_view"] && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else
			$form_data = $settings["allow_view"] && $data ? $data : array();
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/comment/edit_comment.css';
		$settings["class"] = "module_edit_comment";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		$is_insertion = $settings["allow_insertion"] && !$data["comment_id"];
		$is_editable = ($settings["allow_update"] && $data["comment_id"]) || $is_insertion;
		
		if ($settings["show_comment_id"])
			$settings["fields"]["comment_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		if ($settings["show_user_id"]) 
			\CommonModuleUtil::prepareUserIdFormSettingsField($EVC, $settings, $is_editable);
		
		if ($settings["show_comment"])
			$settings["fields"]["comment"]["field"]["input"]["type"] = $is_editable ? "textarea" : "label";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "comment/edit_comment", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
