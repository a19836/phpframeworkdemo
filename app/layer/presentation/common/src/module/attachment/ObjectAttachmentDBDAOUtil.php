<?php
if (!class_exists("ObjectAttachmentDBDAOUtil")) {
	class ObjectAttachmentDBDAOUtil {
		
		public static function delete_corrupted_object_attachments($data = array()) {
			return "delete from mat_object_attachment where attachment_id not in (select attachment_id from mat_attachment)";
		}
	
	}
}
?>