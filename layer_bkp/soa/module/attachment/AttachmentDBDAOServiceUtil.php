<?php
namespace Module\Attachment;

if (!class_exists("AttachmentDBDAOServiceUtil")) {
	class AttachmentDBDAOServiceUtil {
		
		public static function get_attachments_by_object($data = array()) {
			return "select a.*, oa.`group` `group`, oa.`order` `order`
					from mat_attachment a 
					inner join mat_object_attachment oa on oa.attachment_id=a.attachment_id and oa.object_type_id=" . $data["object_type_id"] . " and oa.object_id=" . $data["object_id"];
		}
	
		public static function get_attachments_by_objects($data = array()) {
			return "select a.*, oa.object_type_id, oa.object_id, oa.`group` `group`, oa.`order` `order`
					from mat_attachment a 
					inner join mat_object_attachment oa on oa.attachment_id=a.attachment_id and oa.object_type_id=" . $data["object_type_id"] . " and oa.object_id in (" . $data["object_ids"] . ")";
		}
	
		public static function get_attachments_by_object_group($data = array()) {
			return "select a.*, oa.`group` `group`, oa.`order` `order`
					from mat_attachment a 
					inner join mat_object_attachment oa on oa.attachment_id=a.attachment_id and oa.object_type_id=" . $data["object_type_id"] . " and oa.object_id=" . $data["object_id"] . " and oa.`group`=" . $data["group"];
		}
	
		public static function get_attachments_by_objects_group($data = array()) {
			return "select a.*, oa.object_type_id, oa.object_id, oa.`group` `group`, oa.`order` `order`
					from mat_attachment a 
					inner join mat_object_attachment oa on oa.attachment_id=a.attachment_id and oa.object_type_id=" . $data["object_type_id"] . " and oa.object_id in (" . $data["object_ids"] . ") and oa.`group` in (" . $data["groups"] . ")";
		}
	
	}
}
?>