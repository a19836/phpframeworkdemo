<?php
if (!class_exists("ObjectArticleDBDAOUtil")) {
	class ObjectArticleDBDAOUtil {
		
		public static function change_object_articles_object_ids_of_parent_object($data = array()) {
			return "update ma_object_article oa
					inner join ma_object_article oa2 on oa.article_id=oa2.article_id and oa2.object_type_id=" . $data["parent_object_type_id"] . " and oa2.object_id=" . $data["parent_object_id"] . "
					set oa.object_type_id=" . $data["new_object_type_id"] . ", oa.object_id=" . $data["new_object_id"] . ", oa.modified_date='" . $data["modified_date"] . "' 
					where oa.object_type_id=" . $data["old_object_type_id"] . " and oa.object_id=" . $data["old_object_id"];
		}
	
	}
}
?>