<?php
if (!class_exists("MessageDBDAOUtil")) {
	class MessageDBDAOUtil {
		
		public static function get_chat_messages($data = array()) {
			return "select * from mmsg_message where from_user_id=" . $data["from_user_id"] . " and to_user_id=" . $data["to_user_id"] . " and from_user_status=1
					union
					select * from mmsg_message where from_user_id=" . $data["to_user_id"] . " and to_user_id=" . $data["from_user_id"] . " and to_user_status=1";
		}
	
		public static function count_chat_messages($data = array()) {
			return "select count(message_id) as total from (
						select * from mmsg_message where from_user_id=" . $data["from_user_id"] . " and to_user_id=" . $data["to_user_id"] . " and from_user_status=1
						union
						select * from mmsg_message where from_user_id=" . $data["to_user_id"] . " and to_user_id=" . $data["from_user_id"] . " and to_user_status=1
					) m";
		}
	
		public static function get_previous_chat_messages_from_message($data = array()) {
			return "select * from mmsg_message where from_user_id=" . $data["from_user_id"] . " and to_user_id=" . $data["to_user_id"] . " and from_user_status=1 and message_id < " . $data["message_id"] . "
					union
					select * from mmsg_message where from_user_id=" . $data["to_user_id"] . " and to_user_id=" . $data["from_user_id"] . " and to_user_status=1 and message_id < " . $data["message_id"];
		}
	
		public static function count_previous_chat_messages_from_message($data = array()) {
			return "select count(message_id) as total from (
						select * from mmsg_message where from_user_id=" . $data["from_user_id"] . " and to_user_id=" . $data["to_user_id"] . " and from_user_status=1 and message_id < " . $data["message_id"] . "
						union
						select * from mmsg_message where from_user_id=" . $data["to_user_id"] . " and to_user_id=" . $data["from_user_id"] . " and to_user_status=1 and message_id < " . $data["message_id"] . "
					) m";
		}
	
		public static function get_next_chat_messages_from_message($data = array()) {
			return "select * from mmsg_message where from_user_id=" . $data["from_user_id"] . " and to_user_id=" . $data["to_user_id"] . " and from_user_status=1 and message_id > " . $data["message_id"] . "
					union
					select * from mmsg_message where from_user_id=" . $data["to_user_id"] . " and to_user_id=" . $data["from_user_id"] . " and to_user_status=1 and message_id > " . $data["message_id"];
		}
	
		public static function count_next_chat_messages_from_message($data = array()) {
			return "select count(message_id) as total from (
						select * from mmsg_message where from_user_id=" . $data["from_user_id"] . " and to_user_id=" . $data["to_user_id"] . " and from_user_status=1 and message_id > " . $data["message_id"] . "
						union
						select * from mmsg_message where from_user_id=" . $data["to_user_id"] . " and to_user_id=" . $data["from_user_id"] . " and to_user_status=1 and message_id > " . $data["message_id"] . "
					) m";
		}
	
		public static function get_user_chat_users($data = array()) {
			return "select u.*, cu.created_date last_chat_date 
					from (
						select user_id, max(created_date) created_date from (
							select to_user_id as user_id, created_date from mmsg_message where from_user_id=" . $data["user_id"] . " and from_user_status=1
							union
							select from_user_id as user_id, created_date from mmsg_message where to_user_id=" . $data["user_id"] . " and to_user_status=1
						) m
						group by user_id
					) cu 
					inner join mu_user u on u.user_id=cu.user_id
					order by last_chat_date desc";
		}
	
		public static function get_user_last_unique_chats($data = array()) {
			return "select mu.user_id, m.*
					from (
						select user_id, max(message_id) message_id from (
							select to_user_id as user_id, message_id from mmsg_message where from_user_id=" . $data["user_id"] . " and from_user_status=1
							union
							select from_user_id as user_id, message_id from mmsg_message where to_user_id=" . $data["user_id"] . " and to_user_status=1
						) m
						group by user_id
						order by message_id desc
					) mu
					inner join mmsg_message m on m.message_id=mu.message_id 
					order by created_date desc";
		}
	
	}
}
?>