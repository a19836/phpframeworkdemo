<?php
namespace Module\Event;

if (!class_exists("EventDBDAOServiceUtil")) {
	class EventDBDAOServiceUtil {
		
		public static function get_events_by_tags($data = array()) {
			return "select e.*, ot.`group` tag_group, ot.`order` tag_order
					from me_event e
					inner join mt_object_tag ot on ot.object_type_id=" . $data["object_type_id"] . " and ot.object_id=e.event_id
					inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
					where " . $data["conditions"];
		}
	
		public static function count_events_by_tags($data = array()) {
			return "select count(e.event_id) total
					from me_event e
					inner join mt_object_tag ot on ot.object_type_id=" . $data["object_type_id"] . " and ot.object_id=e.event_id
					inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
					where " . $data["conditions"];
		}
	
		public static function get_events_by_object_and_tags($data = array()) {
			return "select e.*, oe.`group` `group`, oe.`order` `order`, ot.`group` tag_group, ot.`order` tag_order
					from me_event e
					inner join me_object_event oe on oe.event_id=e.event_id and oe.object_type_id=" . $data["object_type_id"] . " and oe.object_id=" . $data["object_id"] . "
					inner join mt_object_tag ot on ot.object_type_id=" . $data["event_object_type_id"] . " and ot.object_id=e.event_id
					inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
					where " . $data["conditions"];
		}
	
		public static function count_events_by_object_and_tags($data = array()) {
			return "select count(e.event_id) total
					from me_event e
					inner join me_object_event oe on oe.event_id=e.event_id and oe.object_type_id=" . $data["object_type_id"] . " and oe.object_id=" . $data["object_id"] . "
					inner join mt_object_tag ot on ot.object_type_id=" . $data["event_object_type_id"] . " and ot.object_id=e.event_id
					inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
					where " . $data["conditions"];
		}
	
		public static function get_events_by_object_group_and_tags($data = array()) {
			return "select e.*, oe.`group` `group`, oe.`order` `order`, ot.`group` tag_group, ot.`order` tag_order
					from me_event e
					inner join me_object_event oe on oe.event_id=e.event_id and oe.object_type_id=" . $data["object_type_id"] . " and oe.object_id=" . $data["object_id"] . " and oe.group=" . $data["group"] . "
					inner join mt_object_tag ot on ot.object_type_id=" . $data["event_object_type_id"] . " and ot.object_id=e.event_id
					inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
					where " . $data["conditions"];
		}
	
		public static function count_events_by_object_group_and_tags($data = array()) {
			return "select count(e.event_id) total
					from me_event e
					inner join me_object_event oe on oe.event_id=e.event_id and oe.object_type_id=" . $data["object_type_id"] . " and oe.object_id=" . $data["object_id"] . " and oe.group=" . $data["group"] . "
					inner join mt_object_tag ot on ot.object_type_id=" . $data["event_object_type_id"] . " and ot.object_id=e.event_id
					inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
					where " . $data["conditions"];
		}
	
		public static function get_events_with_all_tags($data = array()) {
			return "select e.*, z.tag_group, z.tag_order, z.tags_count 
					from me_event e 
					inner join (
						select e.event_id, ot.`group` tag_group, ot.`order` tag_order, count(t.tag) tags_count
						from me_event e
						inner join mt_object_tag ot on ot.object_type_id=" . $data["object_type_id"] . " and ot.object_id=e.event_id
						inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
						where " . $data["conditions"] . "
						group by e.event_id, ot.`group`, ot.`order` having count(t.tag) >= " . $data["tags_count"] . "
					) z on z.event_id=e.event_id";
		}
	
		public static function count_events_with_all_tags($data = array()) {
			return "select count(event_id) total
					from (
						select e.event_id, count(t.tag) tags_count
						from me_event e
						inner join mt_object_tag ot on ot.object_type_id=" . $data["object_type_id"] . " and ot.object_id=e.event_id
						inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
						where " . $data["conditions"] . "
						group by e.event_id having count(t.tag) >= " . $data["tags_count"] . "
					) Z";
		}
	
		public static function get_events_by_object_with_all_tags($data = array()) {
			return "select e.*, z.`group`, z.`order`, z.tag_group, z.tag_order, z.tags_count 
					from me_event e 
					inner join (
						select e.event_id, oe.`group` `group`, oe.`order` `order`, ot.`group` tag_group, ot.`order` tag_order, count(t.tag) tags_count
						from me_event e
						inner join me_object_event oe on oe.event_id=e.event_id and oe.object_type_id=" . $data["object_type_id"] . " and oe.object_id=" . $data["object_id"] . "
						inner join mt_object_tag ot on ot.object_type_id=" . $data["event_object_type_id"] . " and ot.object_id=e.event_id
						inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
						where " . $data["conditions"] . "
						group by e.event_id, oe.`group`, oe.`order`, ot.`group`, ot.`order` having count(t.tag) >= " . $data["tags_count"] . "
					) z on z.event_id=e.event_id";
		}
	
		public static function count_events_by_object_with_all_tags($data = array()) {
			return "select count(event_id) total
					from (
						select e.event_id, count(t.tag) tags_count
						from me_event e
						inner join me_object_event oe on oe.event_id=e.event_id and oe.object_type_id=" . $data["object_type_id"] . " and oe.object_id=" . $data["object_id"] . "
						inner join mt_object_tag ot on ot.object_type_id=" . $data["event_object_type_id"] . " and ot.object_id=e.event_id
						inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
						where " . $data["conditions"] . "
						group by e.event_id having count(t.tag) >= " . $data["tags_count"] . "
					) Z";
		}
	
		public static function get_events_by_object_group_with_all_tags($data = array()) {
			return "select e.*, z.`group`, z.`order`, z.tag_group, z.tag_order, z.tags_count 
					from me_event e 
					inner join (
						select e.event_id, oe.`group` `group`, oe.`order` `order`, ot.`group` tag_group, ot.`order` tag_order, count(t.tag) tags_count
						from me_event e
						inner join me_object_event oe on oe.event_id=e.event_id and oe.object_type_id=" . $data["object_type_id"] . " and oe.object_id=" . $data["object_id"] . " and oe.group=" . $data["group"] . "
						inner join mt_object_tag ot on ot.object_type_id=" . $data["event_object_type_id"] . " and ot.object_id=e.event_id
						inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
						where " . $data["conditions"] . "
						group by e.event_id, oe.`group`, oe.`order`, ot.`group`, ot.`order` having count(t.tag) >= " . $data["tags_count"] . "
					) z on z.event_id=e.event_id";
		}
	
		public static function count_events_by_object_group_with_all_tags($data = array()) {
			return "select count(event_id) total
					from (
						select e.event_id, count(t.tag) tags_count
						from me_event e
						inner join me_object_event oe on oe.event_id=e.event_id and oe.object_type_id=" . $data["object_type_id"] . " and oe.object_id=" . $data["object_id"] . " and oe.group=" . $data["group"] . "
						inner join mt_object_tag ot on ot.object_type_id=" . $data["event_object_type_id"] . " and ot.object_id=e.event_id
						inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
						where " . $data["conditions"] . "
						group by e.event_id having count(t.tag) >= " . $data["tags_count"] . "
					) Z";
		}
	
		public static function get_events_by_object($data = array()) {
			return "select e.*, oe.`group` `group`, oe.`order` `order`
					from me_event e 
					inner join me_object_event oe on oe.event_id=e.event_id and oe.object_type_id=" . $data["object_type_id"] . " and oe.object_id=" . $data["object_id"] . "
					where " . $data["conditions"];
		}
	
		public static function count_events_by_object($data = array()) {
			return "select count(e.event_id) total
					from me_event e 
					inner join me_object_event oe on oe.event_id=e.event_id and oe.object_type_id=" . $data["object_type_id"] . " and oe.object_id=" . $data["object_id"] . "
					where " . $data["conditions"];
		}
	
		public static function get_events_by_object_group($data = array()) {
			return "select e.*, oe.`group` `group`, oe.`order` `order`
					from me_event e 
					inner join me_object_event oe on oe.event_id=e.event_id and oe.object_type_id=" . $data["object_type_id"] . " and oe.object_id=" . $data["object_id"] . " and oe.`group`=" . $data["group"] . "
					where " . $data["conditions"];
		}
	
		public static function count_events_by_object_group($data = array()) {
			return "select count(e.event_id) total
					from me_event e 
					inner join me_object_event oe on oe.event_id=e.event_id and oe.object_type_id=" . $data["object_type_id"] . " and oe.object_id=" . $data["object_id"] . " and oe.`group`=" . $data["group"] . "
					where " . $data["conditions"];
		}
	
	}
}
?>