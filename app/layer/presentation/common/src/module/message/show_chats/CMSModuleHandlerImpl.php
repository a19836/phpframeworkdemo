<?php
namespace CMSModule\message\show_chats;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("message/MessageUtil", $common_project_name);
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$html = '';
		$user_id = null;
		
		if (empty($settings["logged_user_id"]) && !empty($settings["session_id"])) {
			$session_data = \UserUtil::getUserSessionsByConditions($brokers, array("session_id" => $settings["session_id"]), null);
			
			if (isset($session_data[0]["user_id"])) {
				$user_data = \UserUtil::getUsersByConditions($brokers, array("user_id" => $session_data[0]["user_id"]), null);
				$user_id = isset($user_data[0]["user_id"]) ? $user_data[0]["user_id"] : null;
			}
		}
		else 
			$user_id = isset($settings["logged_user_id"]) ? $settings["logged_user_id"] : null;
		
		if (is_numeric($user_id)) {
			//Getting users
			$chat_users = \MessageUtil::getUserChatUsers($brokers, $user_id);
			$available_users = null;
			
			//Add join point updating the data
			$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Changing Chat Users data", array(
				"EVC" => &$EVC,
				"settings" => &$settings,
				"chat_users" => &$chat_users,
			), "This join point's method/function can change the \$settings and \$chat_users variables.");
			
			$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Adding New Users", array(
				"EVC" => &$EVC,
				"settings" => &$settings,
				"available_users" => &$available_users,
			), "This join point's method/function can change the \$settings and \$available_users variables. The purpose of this join point is to init the \$available_users variable with the available users to send new messages. Note that if \$available_users variable is empty, is not possible to send new messages.");
			
			//Including Stylesheet
			if (empty($settings["style_type"]))
				$html .= '<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/message/show_chats.css" type="text/css" charset="utf-8" />';
			
			$html .= '<script type="text/javascript" src="' . $project_common_url_prefix . 'module/message/show_chats.js"></script>';
			$html .= !empty($settings["css"]) ? '<style>' . $settings["css"] . '</style>' : '';
			$html .= !empty($settings["js"]) ? '<script type="text/javascript">' . $settings["js"] . '</script>' : '';
			
			//Preparing messages html
			$current_date = date("Y-m-d");
			
			$show_chat_url = isset($settings["show_chat_url"]) ? $settings["show_chat_url"] : null;
			$load_existent_chat_users_url = isset($settings["load_existent_chat_users_url"]) ? $settings["load_existent_chat_users_url"] : null;
			$delete_chat_url = isset($settings["delete_chat_url"]) ? $settings["delete_chat_url"] : null;
			$user_label = isset($settings["user_label"]) ? $settings["user_label"] : null;
			$default_chat_user_id = isset($settings["default_chat_user_id"]) ? $settings["default_chat_user_id"] : null;
			
			$html .= '
			<script>
				var jquery_lib_url = jquery_lib_url ? jquery_lib_url : \'' . $project_common_url_prefix . 'vendor/jquery/js/jquery-1.8.1.min.js\';
				
				var show_chat_url = \'' . $show_chat_url . '\';
				var load_existent_chat_users_url = \'' . $load_existent_chat_users_url . '\';
				var delete_chat_url = \'' . $delete_chat_url . '\';
				var chat_list_user_html = \'' . addcslashes(str_replace("\n", "", self::getChatListUserHtml(array("user_id" => "#user_id#", "name" => "#name#", "photo_url" => "#photo_url#", "last_chat_date" => "#last_chat_date#"), $current_date, false, false, $delete_chat_url)), "\\'") . '\';
				var on_user_chat_function = on_user_chat_function ? on_user_chat_function : null;//check if already exists before. This allows the on_user_chat_function variable be set on the template level...
				var on_check_new_users_function = on_check_new_users_function ? on_check_new_users_function : null;
				var on_delete_chat_function = on_delete_chat_function ? on_delete_chat_function : null;
				var on_delete_chat_confirmation_message = "' . translateProjectText($EVC, "Do you wish to delete these messages?") . '";
				var on_delete_chat_error_message = "' . translateProjectText($EVC, "There was an error trying to delete these messages. Please try again...") . '";
			</script>
			<div class="module_show_chats ' . (isset($settings["block_class"]) ? $settings["block_class"] : null) . '">';
			
			if ($available_users)
				$html .= '<div class="open_new_chat" onClick="$(this).parent().closest(\'.module_show_chats\').toggleClass(\'new_chat_openned\')">
					<span class="glyphicon glyphicon-plus icon add"></span>	
					' . translateProjectText($EVC, "Toggle New Chat") . '
				</div>';
			
			$html .= '<div class="close_chat_list">
					<span class="glyphicon glyphicon-chevron-left icon left" onClick="$(this).parent().closest(\'.module_show_chats\').toggleClass(\'chat_list_closed\')"></span>
				</div>
				<div class="chats_list">';
			
			if ($chat_users) {
				foreach ($chat_users as $user) {
					$chat_user_id = isset($user["user_id"]) ? $user["user_id"] : null;
					$html .= self::getChatListUserHtml($user, $current_date, $user_label, $default_chat_user_id == $chat_user_id, $delete_chat_url);
				}
			}
			else 
				$html .= '<div class="empty_items">' . translateProjectText($EVC, "There are no chats...") . '</div>';
			
			$html .= '</div>
				<div class="chat_details"></div>
				<div class="available_users">';
			
			if ($available_users)
				foreach ($available_users as $available_user)
					$html .= self::getAvailableUsersUserHtml($available_user, $current_date, $user_label);
				
			$html .= '</div>
			</div>';
			
			//Open a chat by default with the correspondent user id
			if ($default_chat_user_id)
				$html .= '<script>openUserChat(' . $default_chat_user_id . ', on_user_chat_function);</script>';
		}
		
		return $html;
	}
	
	private static function getChatListUserHtml($user, $current_date, $user_label = false, $selected = false, $delete_btn = false) {
		return self::getChatUserHtml($user, $current_date, $user_label, $selected, $delete_btn);
	}
	
	private static function getAvailableUsersUserHtml($user, $current_date, $user_label = false, $selected = false) {
		return self::getChatUserHtml($user, $current_date, $user_label, $selected, false);
	}
	
	private static function getChatUserHtml($user, $current_date, $user_label, $selected, $delete_btn) {
		$user["last_chat_date"] = isset($user["last_chat_date"]) ? $user["last_chat_date"] : null;
		$cd = explode(" ", $user["last_chat_date"]);
		if ($cd[0] == $current_date)
			$user["last_chat_date"] = $cd[1];
		$user["last_chat_date"] = strrpos($user["last_chat_date"], ":") ? substr($user["last_chat_date"], 0, strrpos($user["last_chat_date"], ":")) : $user["last_chat_date"];
		
		$delete_btn = $delete_btn ? '<span class="icon glyphicon glyphicon-trash delete" onClick="deleteChat(this, on_delete_chat_function, event)"></span>' : '';
		
		if ($user_label) {
			$HtmlFormHandler = new \HtmlFormHandler();
			$user_label = $HtmlFormHandler->getParsedValueFromData($user_label, $user);
		}
		else 
			$user_label = !empty($user["username"]) ? $user["username"] : (isset($user["name"]) ? $user["name"] : null);
		
		$user_id = isset($user["user_id"]) ? $user["user_id"] : null;
		
		return '
		<div class="user_chat' . ($selected ? ' selected' : '') . '" onClick="openUserChat(\'' . $user_id . '\', on_user_chat_function)" user_id="' . $user_id . '">
			<div class="user_photo">
				' . (!empty($user["photo_url"]) ? '<img src="' . $user["photo_url"] . '" onError="$(this).remove()"/>' : '') . '
			</div>
			<div class="user_name">' . $user_label . '</div>
			<div class="last_chat_date">' . $user["last_chat_date"] . '</div>
			' . $delete_btn . '
		</div>';
	}
}
?>
