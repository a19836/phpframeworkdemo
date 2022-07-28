<?php
class CommentUI {
	
	public static function getObjectCommentsHtml($EVC, $settings, $object_type_id, $object_id, $group = null) {
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("comment/CommentUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$options = array("sort" => array(
			array("column" => "created_date", "order" => "desc")
		));
		$comments = null;
		
		if ($object_type_id && $object_id) {
			switch ($settings["filter"]) {
				case "filter_by_parent":
					$comments = CommentUtil::getParentObjectCommentsByObjectGroup($brokers, $settings["filter_by_parent"]["object_type_id"], $settings["filter_by_parent"]["object_id"], $object_type_id, $object_id, $group, $options);
					break;
				case "filter_by_parent_group":
					$comments = CommentUtil::getParentObjectGroupCommentsByObjectGroup($brokers, $settings["filter_by_parent"]["object_type_id"], $settings["filter_by_parent"]["object_id"], $settings["filter_by_parent"]["group"], $object_type_id, $object_id, $group, $options);
					break;
				default:
					$comments = CommentUtil::getCommentsByObjectGroup($brokers, $object_type_id, $object_id, $group, $options);
			}
		}
		
		if ($comments || $settings["add_comment_url"]) {
			self::prepareCommentsUsers($EVC, $settings, $comments, $common_project_name, $brokers);
			
			$html = '';
		
			if (empty($settings["style_type"])) {
				$html .= '<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/comment/object_comments.css" type="text/css" charset="utf-8" />';
			}
			
			$html .= $settings["css"] ? '<style>' . $settings["css"] . '</style>' : '';
			$html .= '<script type="text/javascript" src="' . $project_common_url_prefix . 'module/comment/object_comments.js"></script>
			<script type="text/javascript">' . $settings["js"] . '</script>
			
			<script>
				var jquery_lib_url = jquery_lib_url ? jquery_lib_url : \'' . $project_common_url_prefix . 'vendor/jquery/js/jquery-1.8.1.min.js\';
			</script>';
			
			$class =  trim($settings["class"] . " " . $settings["block_class"]);
			
			$html .= '
			<div class="module_object_comments ' . $class . '">';
		
			if ($settings["add_comment_url"]) {
				$html .= self::getAddCommentHtml($EVC, $settings, $object_id);
			}
		
			$html .= '
				<div class="title">' . translateProjectLabel($EVC, $settings["title"] ? $settings["title"] : "Comments:") . '</div>
				<div class="comments">
					<ul>';
			
			if ($comments) {
				$HtmlFormHandler = new \HtmlFormHandler();
				
				foreach ($comments as $idx => $comment) {
					$user = $settings["comments_users"][ $comment["user_id"] ];
					
					$user_label = $settings["user_label"];
					if ($user_label)
						$user_label = $HtmlFormHandler->getParsedValueFromData($user_label, $user);
					else 
						$user_label = $user["username"] ? $user["username"] : $user["name"];
					
					$created_date = substr($comment["created_date"], 0, strrpos($comment["created_date"], ":"));
					
					$html .= '
						<li class="' . ($idx % 2 == 0 ? 'hovered' : '') . '" user_id="' . $comment["user_id"] . '">
							<div class="user_photo">' . ($user["photo_url"] ? '<img src="' . $user["photo_url"] . '" onError="$(this).remove()" />' : '') . '</div>
							<div class="user_name">' . $user_label . '</div>
							<div class="date">' . $created_date . '</div>
							<div class="comment">' . str_replace("\n", "<br/>", $comment["comment"]) . '</div>
						</li>';
				}
			}
			else {
				$empty_comments_label = $settings["empty_comments_label"] ? $settings["empty_comments_label"] : 'There are no comments...<br/>Be the first one to insert a comment.';
				
				$html .= '<li class="empty_comments">' . translateProjectText($EVC, $empty_comments_label) . '</li>';
			}
			
			$html .= '	</ul>
				</div>';
		
			$html .= '</div>';
		}
		
		return $html;
	}
	
	private static function getAddCommentHtml($EVC, $settings, $object_id) {
		$current_user_data = $settings["current_user"];
		$current_logged_user_name = $current_user_data["username"] ? $current_user_data["username"] : $current_user_data["name"];
		
		$add_comment_label = translateProjectLabel($EVC, $settings["add_comment_label"] ? $settings["add_comment_label"] : "Write new comment:");
		$add_comment_textarea_place_holder = translateProjectText($EVC, $settings["add_comment_textarea_place_holder"] ? $settings["add_comment_textarea_place_holder"] : "Write new comment here...");
		$add_comment_button_label = translateProjectLabel($EVC, $settings["add_comment_button_label"] ? $settings["add_comment_button_label"] : "Add New Comment");
		$add_comment_error_message = translateProjectText($EVC, $settings["add_comment_error_message"] ? $settings["add_comment_error_message"] : "Error trying to add new comment. Please try again...");
		
		//Do not use #xx#, but instead use %xx%, bc the html return from this function, will be used HtmlFormHandler::createHtmlForm, which will parse it and replace it the #xx# accordingly with the current data.
		$comment_html = '
		<li user_id="%user_id%">
			<div class="user_photo"><img src="%photo_url%" onError="$(this).remove()" /></div>
			<div class="user_name">%name%</div>
			<div class="date">%date%</div>
			<div class="comment">%comment%</div>
		</li>';
		
		$html = '
		<div class="add_object_comment">
			<label>' . $add_comment_label . '</label>
			<textarea class="form-control" placeHolder="' . $add_comment_textarea_place_holder . '"></textarea>
			<input class="btn btn-default" type="button" name="add" value="' . $add_comment_button_label . '" onClick="addObjectComment(this)" />
			
			<script>
				var add_comment_url = \'' . $settings["add_comment_url"] . $object_id . '\';
				var add_comment_error_message = \'' . $add_comment_error_message . '\';
				var comment_html = \'' . addcslashes(str_replace("\n", "", $comment_html), "\\'") . '\';
				var current_user_data = ' . json_encode($current_user_data) . ';
			</script>
		</div>';
		
		return $html;
	}
	
	private static function prepareCommentsUsers($EVC, &$settings, $comments, $common_project_name, $brokers) {
		$users_data = array();
		$all_user_ids = array();
		
		if (!$settings["comments_users"]) {
			//Getting user_ids from comments
			$comments_user_ids = array();
			
			if ($comments)
				foreach ($comments as $comment)
					$comments_user_ids[ $comment["user_id"] ] = true;
			
			$comments_user_ids = array_keys($comments_user_ids);
			$all_user_ids = $comments_user_ids;
			
			//Getting users' data for correspondent $comments_user_ids
			include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
			$users_data = UserUtil::getUsersByConditions($brokers, array(
				"user_id" => array(
					"operator" => "in",
					"value" => $comments_user_ids,
				)
			), null);
		}
		
		//Preparing current logged user data
		if (!$settings["current_user"] && $GLOBALS['UserSessionActivitiesHandler']) {
			$current_user_data = $GLOBALS['UserSessionActivitiesHandler']->getUserData();
			
			if ($current_user_data["user_id"] && !in_array($current_user_data["user_id"], $all_user_ids))
				$all_user_ids[] = $current_user_data["user_id"];
		}
		
		if ($all_user_ids) {
			include_once $EVC->getModulePath("attachment/AttachmentUtil", $common_project_name);
			
			//Getting object_attachments for all_user_ids
			$attachments = AttachmentUtil::getAttachmentsByObjectsGroup($brokers, ObjectUtil::USER_OBJECT_TYPE_ID, $all_user_ids, UserUtil::USER_PHOTO_GROUP_ID);
			
			if ($attachments) {
				$folder_path = AttachmentUtil::getAttachmentsFolderPath($EVC);
				$url = AttachmentUtil::getAttachmentsFolderUrl($EVC);
				
				//Preparing indexes for $user_data
				$user_indexes = array();
				foreach ($users_data as $idx => $user_data) 
					$user_indexes[ $user_data["user_id"] ] = $idx;
				
				//Preparing attachments and add them to $user_data
				foreach ($attachments as $attachment) {
					$path = $attachment["path"];
				
					if ($path) {
						$user_id = $attachment["object_id"];
						$user_data_idx = $user_id ? $user_indexes[$user_id] : null;
						
						if ($user_data_idx && $users_data[$user_data_idx]) {
							$users_data[$user_data_idx]["photo_id"] = $attachment["attachment_id"];
							$users_data[$user_data_idx]["photo_path"] = $folder_path . $path;
							$users_data[$user_data_idx]["photo_url"] = $url . $path;
						}
						
						if ($user_id == $current_user_data["user_id"]) {
							$current_user_data["photo_id"] = $attachment["attachment_id"];
							$current_user_data["photo_path"] = $folder_path . $path;
							$current_user_data["photo_url"] = $url . $path;
						}
					}
				}
			}
			
			$settings["comments_users"] = $users_data;
			$settings["current_user"] = $current_user_data;
		}
		
		//Preparing $users by user_id
		$users = array();
		if ($settings["comments_users"])
			foreach ($settings["comments_users"] as $user)
				$users[ $user["user_id"] ] = $user;
		
		$settings["comments_users"] = $users;
	}
}
?>
