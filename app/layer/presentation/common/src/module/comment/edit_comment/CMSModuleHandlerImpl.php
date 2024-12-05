<?php
namespace CMSModule\comment\edit_comment;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("comment/CommentUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Comment Details
		$comment_id = isset($_GET["comment_id"]) ? $_GET["comment_id"] : null;
		$data = $comment_id ? \CommentUtil::getCommentsByConditions($brokers, array("comment_id" => $comment_id), null, null, true) : null;
		$data = isset($data[0]) ? $data[0] : null;
		
		//Preparing Action
		if (!empty($_POST)) {
			if (!empty($_POST["delete"]) && !empty($settings["allow_deletion"])) {
				$data_comment_id = isset($data["comment_id"]) ? $data["comment_id"] : null;
				
				$status = !$data || \CommentUtil::deleteComment($brokers, $data_comment_id);
			}
			else if (!empty($_POST["save"])) {
				$user_id = isset($_POST["user_id"]) ? $_POST["user_id"] : null;
				$comment = isset($_POST["comment"]) ? $_POST["comment"] : null;
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("user_id" => $user_id, "comment" => $comment));
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				}
				else {
					$new_data = $data;
					$new_data["user_id"] = !empty($settings["show_user_id"]) ? $user_id : (isset($new_data["user_id"]) ? $new_data["user_id"] : null);
					$new_data["comment"] = !empty($settings["show_comment"]) ? $comment : (isset($new_data["comment"]) ? $new_data["comment"] : null);
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
						$new_data["object_comments"] = isset($settings["object_to_objects"]) ? $settings["object_to_objects"] : null;
						
						if (!empty($settings["allow_insertion"]) && empty($data["comment_id"])) {
							$status = \CommentUtil::insertComment($brokers, $new_data);
							if (isset($settings["on_insert_ok_action"]) && strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (isset($settings["on_insert_ok_redirect_url"]) && strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "comment_id=$status";
							}
						}
						else if (!empty($settings["allow_update"]) && !empty($data["comment_id"])) {
							$status = \CommentUtil::updateComment($brokers, $new_data);
						}
					}
				}
			}
		}
		
		if (!empty($_POST["save"])) {
			$form_data = array(
				"comment_id" => !empty($settings["show_comment_id"]) ? $comment_id : (isset($data["comment_id"]) ? $data["comment_id"] : null),
				"user_id" => !empty($settings["show_user_id"]) ? $user_id : (isset($data["user_id"]) ? $data["user_id"] : null),
				"comment" => !empty($settings["show_comment"]) ? $comment : (isset($data["comment"]) ? $data["comment"] : null),
			);
			$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : (!empty($settings["allow_view"]) && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else
			$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/comment/edit_comment.css';
		$settings["class"] = "module_edit_comment";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		
		$is_insertion = !empty($settings["allow_insertion"]) && empty($data["comment_id"]);
		$is_editable = (!empty($settings["allow_update"]) && !empty($data["comment_id"])) || $is_insertion;
		
		if (!empty($settings["show_comment_id"]))
			$settings["fields"]["comment_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		if (!empty($settings["show_user_id"]))
			\CommonModuleUtil::prepareUserIdFormSettingsField($EVC, $settings, $is_editable);
		
		if (!empty($settings["show_comment"]))
			$settings["fields"]["comment"]["field"]["input"]["type"] = $is_editable ? "textarea" : "label";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "comment/edit_comment", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
