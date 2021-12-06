<?php
namespace CMSModule\message\show_chat;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("message/MessageUtil", $common_project_name);
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$html = '';
		
		if (!$settings["logged_user_id"] && $settings["session_id"]) {
			$session_data = \UserUtil::getUserSessionsByConditions($brokers, array("session_id" => $session_id), null);
			
			if ($session_data[0]) {
				$user_data = \UserUtil::getUsersByConditions($brokers, array("user_id" => $session_data[0]["user_id"]), null);
				$settings["from_user_id"] = $user_data[0]["user_id"];
			}
		}
		else 
			$settings["from_user_id"] = $settings["logged_user_id"];
		
		if (is_numeric($settings["from_user_id"]) && is_numeric($settings["to_user_id"])) {
			$settings["maximum_number_of_loaded_messages"] = $settings["maximum_number_of_loaded_messages"] ? $settings["maximum_number_of_loaded_messages"] : null;
			
			//Getting messages
			$messages = \MessageUtil::getChatMessages($brokers, $settings["from_user_id"], $settings["to_user_id"], array("limit" => $settings["maximum_number_of_loaded_messages"], "sort" => array(
				array("column" => "created_date", "order" => "desc")
			)));
			
			//Updating messages seen date
			if ($messages) {
				$current_date = date("Y-m-d H:i:s");
				$t = count($messages);
				for ($i = 0; $i < $t; $i++) {
					$message = &$messages[$i];
					if ($message["to_user_id"] == $settings["from_user_id"]) {
						if (empty($message["seen_date"])) {
							$message["seen_date"] = $current_date;
							\MessageUtil::updateMessageSeenDate($brokers, $message);
						}
						else 
							break;
					}
				}
			}
			
			$from_user_data = $user_data ? $user_data : \UserUtil::getUsersByConditions($brokers, array("user_id" => $settings["from_user_id"]), null);
			$from_user_data = $from_user_data[0];
			
			$to_user_data = \UserUtil::getUsersByConditions($brokers, array("user_id" => $settings["to_user_id"]), null);
			$to_user_data = $to_user_data[0];
			
			//Add join point updating the data
			$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Changing Messages data", array(
				"EVC" => &$EVC,
				"settings" => &$settings,
				"messages" => &$messages,
				"from_user_data" => &$from_user_data,
				"to_user_data" => &$to_user_data,
			), "This join point's method/function can change the \$settings, \$messages, \$from_user_data, \$to_user_data variables.");
			
			//Including Stylesheet
			if (empty($settings["style_type"])) {
				$html .= '<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/message/show_chat.css" type="text/css" charset="utf-8" />';
			}
		
			$html .= '<script type="text/javascript" src="' . $project_common_url_prefix . 'module/message/show_chat.js"></script>';
			$html .= $settings["css"] ? '<style>' . $settings["css"] . '</style>' : '';
			$html .= $settings["js"] ? '<script type="text/javascript">' . $settings["js"] . '</script>' : '';
			
			//Preparing messages html
			$chat_class = 'module_show_chat_from_' . $settings["from_user_id"] . '_to_' . $settings["to_user_id"];
			$chat_id = $chat_class . '_' . rand(0, 100000);
			
			$toud = $to_user_data;
			unset($toud["photo_path"]);
			
			$to_user_label = $settings["to_user_label"];
			if ($to_user_label) {
				$HtmlFormHandler = new \HtmlFormHandler();
				$to_user_label = $HtmlFormHandler->getParsedValueFromData($to_user_label, $to_user_data);
			}
			else 
				$to_user_label = $to_user_data["username"] ? $to_user_data["username"] : $to_user_data["name"];
			
			$html .= '
			<script>
			var jquery_lib_url = jquery_lib_url ? jquery_lib_url : \'' . $project_common_url_prefix . 'vendor/jquery/js/jquery-1.8.1.min.js\';
			
			var ' . $chat_id . ' = {
				id: \'' . $chat_id . '\',
				from_user_id: \'' . $settings["from_user_id"] . '\',
				to_user_id: \'' . $settings["to_user_id"] . '\',
				load_messages_url: \'' . $settings["load_messages_url"] . '\',
				on_load_error_message: \'' . translateProjectText($EVC, $settings["on_load_error_message"]) . '\',
				send_message_url: \'' . $settings["send_message_url"] . '\',
				on_send_error_message: \'' . translateProjectText($EVC, $settings["on_send_error_message"]) . '\',
				from_user_message_html: \'' . addcslashes(str_replace("\n", "", self::getFromUserMessageHtml(array("message_id" => "#message_id#", "from_user_id" => $settings["from_user_id"], "subject" => "#subject#", "content" => "#content#", "created_date" => "#created_date#", "seen_date" => "#seen_date#"), $from_user_data)), "\\'") . '\',
				to_user_message_html: \'' . addcslashes(str_replace("\n", "", self::getToUserMessageHtml(array("message_id" => "#message_id#", "subject" => "#subject#", "content" => "#content#", "created_date" => "#created_date#", "seen_date" => "#seen_date#"), $to_user_data)), "\\'") . '\',
				to_user_data: ' . json_encode($toud) . ',
			};
			
			//check if already exists before. This allows the on_xxx_function variables be set on the template level...
			var on_send_new_message_function = on_send_new_message_function ? on_send_new_message_function : null;
			var on_load_previous_messages_function = on_load_previous_messages_function ? on_load_previous_messages_function : null;
			var on_load_next_messages_function = on_load_next_messages_function ? on_load_next_messages_function : null;
			</script>
			<div id="' . $chat_id . '" class="module_show_chat ' . $chat_class . ' ' . ($settings["block_class"]) . '">
				<div class="chat_header">
					' . ($to_user_data["photo_url"] ? '<img class="to_user_photo" src="' . $to_user_data["photo_url"] . '" onError="$(this).remove()" />' : '') . '
					<label>' . $to_user_label . '</label>
					<span class="close_chat" onClick="closeChat(this, \'' . $chat_id . '\')">' . translateProjectText($EVC, "Close") . '</span>
					<span class="toggle_chat" onClick="$(this).parent().closest(\'.module_show_chat\').toggleClass(\'chat_minimized\')">' . translateProjectText($EVC, "Toggle") . '</span>
				</div>
				<div class="chat_messages' . (empty($messages) ? ' empty_messages' : '') . '">
					<div class="load_previous_messages" onClick="loadPreviousMessages(' . $chat_id . ', on_load_previous_messages_function)">
						<span class="glyphicon glyphicon-refresh icon refresh"></span>
						' . translateProjectText($EVC, "Load previous messages...") . '
					</div>
					<ul>';
			
			$current_date = date("Y-m-d");
			$previous_user_id = null;
			$t = $messages ? count($messages) : 0;
			for ($i = $t - 1; $i >= 0; $i--) {
				$message_data = $messages[$i];
				
				$cd = explode(" ", $message_data["created_date"]);
				if ($cd[0] == $current_date)
					$message_data["created_date"] = $cd[1];
				$message_data["created_date"] = strrpos($message_data["created_date"], ":")? substr($message_data["created_date"], 0, strrpos($message_data["created_date"], ":")) : $message_data["created_date"];
				
				$sd = explode(" ", $message_data["seen_date"]);
				if ($sd[0] == $current_date)
					$message_data["seen_date"] = $sd[1];
				$message_data["seen_date"] = strrpos($message_data["seen_date"], ":")? substr($message_data["seen_date"], 0, strrpos($message_data["seen_date"], ":")) : $message_data["seen_date"];
				
				$is_user_the_same_than_previous_msg = $previous_user_id == $message_data["from_user_id"];
				
				$html .= $message_data["from_user_id"] == $settings["from_user_id"] ? self::getFromUserMessageHtml($message_data, $from_user_data, $is_user_the_same_than_previous_msg) : self::getToUserMessageHtml($message_data, $to_user_data, $is_user_the_same_than_previous_msg);
				
				$previous_user_id = $message_data["from_user_id"];
			}
			
			$html .= '	</ul>
				</div>
				<div class="chat_footer">
					' . ($settings["send_message_url"] ? '<textarea class="form-control" placeHolder="' . translateProjectText($EVC, "Write your message here...") . '"></textarea>
					<input class="btn btn-default" type="button" value="' . translateProjectText($EVC, "Send") . '" onClick="sendNewMessage(this, ' . $chat_id . ', on_send_new_message_function)" />' : '') . '
				</div>
				<script>
					initChat(' . $chat_id . ');
				</script>
			</div>';
		}
		
		return $html;
	}
	
	private static function getFromUserMessageHtml($message_data, $user_data, $is_user_the_same_than_previous_msg = false) {
		$html = '
		<li class="message message_from_user' . ($is_user_the_same_than_previous_msg ? ' same_user_message' : '') . '" message_id="' . $message_data["message_id"] . '">
			<div class="photo">
				' . ($user_data["photo_url"] ? '<img class="to_user_photo" src="' . $user_data["photo_url"] . '" onError="$(this).remove()" />' : '') . '
			</div>
			<div class="msg">
				' . ($message_data["subject"] ? '<div class="subject">' . $message_data["subject"] . '</div>' : '') . '
				<div class="content">' . nl2br($message_data["content"]) . '</div>
				<div class="created_date">' . $message_data["created_date"] . '</div>
				<div class="seen_date">' . $message_data["seen_date"] . '</div>
			</div>
			<div class="clear"></div>
		</li>';
		
		return $html;
	}
	
	private static function getToUserMessageHtml($message_data, $user_data, $is_user_the_same_than_previous_msg = false) {
		$html = '
		<li class="message message_to_user' . ($is_user_the_same_than_previous_msg ? ' same_user_message' : '') . '" message_id="' . $message_data["message_id"] . '">
			<div class="photo">
				' . ($user_data["photo_url"] ? '<img class="to_user_photo" src="' . $user_data["photo_url"] . '" onError="$(this).remove()" />' : '') . '
			</div>
			<div class="msg">
				' . ($message_data["subject"] ? '<div class="subject">' . $message_data["subject"] . '</div>' : '') . '
				<div class="content">' . nl2br($message_data["content"]) . '</div>
				<div class="created_date">' . $message_data["created_date"] . '</div>
				<div class="seen_date">' . $message_data["seen_date"] . '</div>
			</div>
			<div class="clear"></div>
		</li>';
		
		return $html;
	}
}
?>
