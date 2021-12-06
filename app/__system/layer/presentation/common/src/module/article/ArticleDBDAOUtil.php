<?php
if (!class_exists("ArticleDBDAOUtil")) {
	class ArticleDBDAOUtil {
		
		public static function get_articles_by_tags($data = array()) {
			return "select a.*, ot.`group` tag_group, ot.`order` tag_order
					from ma_article a
					inner join mt_object_tag ot on ot.object_type_id=" . $data["object_type_id"] . " and ot.object_id=a.article_id
					inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
					where " . $data["conditions"];
		}
	
		public static function count_articles_by_tags($data = array()) {
			return "select count(a.article_id) total
					from ma_article a
					inner join mt_object_tag ot on ot.object_type_id=" . $data["object_type_id"] . " and ot.object_id=a.article_id
					inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
					where " . $data["conditions"];
		}
	
		public static function get_articles_by_object_and_tags($data = array()) {
			return "select a.*, oa.`group` `group`, oa.`order` `order`, ot.`group` tag_group, ot.`order` tag_order
					from ma_article a
					inner join ma_object_article oa on oa.article_id=a.article_id and oa.object_type_id=" . $data["object_type_id"] . " and oa.object_id=" . $data["object_id"] . "
					inner join mt_object_tag ot on ot.object_type_id=" . $data["article_object_type_id"] . " and ot.object_id=a.article_id
					inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
					where " . $data["conditions"];
		}
	
		public static function count_articles_by_object_and_tags($data = array()) {
			return "select count(a.article_id) total
					from ma_article a
					inner join ma_object_article oa on oa.article_id=a.article_id and oa.object_type_id=" . $data["object_type_id"] . " and oa.object_id=" . $data["object_id"] . "
					inner join mt_object_tag ot on ot.object_type_id=" . $data["article_object_type_id"] . " and ot.object_id=a.article_id
					inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
					where " . $data["conditions"];
		}
	
		public static function get_articles_by_object_group_and_tags($data = array()) {
			return "select a.*, oa.`group` `group`, oa.`order` `order`, ot.`group` tag_group, ot.`order` tag_order
					from ma_article a
					inner join ma_object_article oa on oa.article_id=a.article_id and oa.object_type_id=" . $data["object_type_id"] . " and oa.object_id=" . $data["object_id"] . " and oa.group=" . $data["group"] . "
					inner join mt_object_tag ot on ot.object_type_id=" . $data["article_object_type_id"] . " and ot.object_id=a.article_id
					inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
					where " . $data["conditions"];
		}
	
		public static function count_articles_by_object_group_and_tags($data = array()) {
			return "select count(a.article_id) total
					from ma_article a
					inner join ma_object_article oa on oa.article_id=a.article_id and oa.object_type_id=" . $data["object_type_id"] . " and oa.object_id=" . $data["object_id"] . " and oa.group=" . $data["group"] . "
					inner join mt_object_tag ot on ot.object_type_id=" . $data["article_object_type_id"] . " and ot.object_id=a.article_id
					inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
					where " . $data["conditions"];
		}
	
		public static function get_articles_with_all_tags($data = array()) {
			return "select a.*, z.tag_group, z.tag_order, z.tags_count 
					from ma_article a
					inner join (
						select a.article_id, ot.`group` tag_group, ot.`order` tag_order, count(t.tag) tags_count
						from ma_article a
						inner join mt_object_tag ot on ot.object_type_id=" . $data["object_type_id"] . " and ot.object_id=a.article_id
						inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
						where " . $data["conditions"] . "
						group by a.article_id, ot.`group`, ot.`order` having count(t.tag) >= " . $data["tags_count"] . "
					) z on z.article_id=a.article_id";
		}
	
		public static function count_articles_with_all_tags($data = array()) {
			return "select count(article_id) total
					from (
						select a.article_id, count(t.tag) tags_count
						from ma_article a
						inner join mt_object_tag ot on ot.object_type_id=" . $data["object_type_id"] . " and ot.object_id=a.article_id
						inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
						where " . $data["conditions"] . "
						group by a.article_id having count(t.tag) >= " . $data["tags_count"] . "
					) Z";
		}
	
		public static function get_articles_by_object_with_all_tags($data = array()) {
			return "select a.*, z.`group`, z.`order`, z.tag_group, z.tag_order, z.tags_count 
					from ma_article a
					inner join (
						select a.article_id, oa.`group` `group`, oa.`order` `order`, ot.`group` tag_group, ot.`order` tag_order, count(t.tag) tags_count
						from ma_article a
						inner join ma_object_article oa on oa.article_id=a.article_id and oa.object_type_id=" . $data["object_type_id"] . " and oa.object_id=" . $data["object_id"] . "
						inner join mt_object_tag ot on ot.object_type_id=" . $data["article_object_type_id"] . " and ot.object_id=a.article_id
						inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
						where " . $data["conditions"] . "
						group by a.article_id, oa.`group`, oa.`order`, ot.`group`, ot.`order` having count(t.tag) >= " . $data["tags_count"] . "
					) z on z.article_id=a.article_id";
		}
	
		public static function count_articles_by_object_with_all_tags($data = array()) {
			return "select count(article_id) total
					from (
						select a.article_id, count(t.tag) tags_count
						from ma_article a
						inner join ma_object_article oa on oa.article_id=a.article_id and oa.object_type_id=" . $data["object_type_id"] . " and oa.object_id=" . $data["object_id"] . "
						inner join mt_object_tag ot on ot.object_type_id=" . $data["article_object_type_id"] . " and ot.object_id=a.article_id
						inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
						where " . $data["conditions"] . "
						group by a.article_id having count(t.tag) >= " . $data["tags_count"] . "
					) Z";
		}
	
		public static function get_articles_by_object_group_with_all_tags($data = array()) {
			return "select a.*, z.`group`, z.`order`, z.tag_group, z.tag_order, z.tags_count 
					from ma_article a
					inner join (
						select a.article_id, oa.`group` `group`, oa.`order` `order`, ot.`group` tag_group, ot.`order` tag_order, count(t.tag) tags_count
						from ma_article a
						inner join ma_object_article oa on oa.article_id=a.article_id and oa.object_type_id=" . $data["object_type_id"] . " and oa.object_id=" . $data["object_id"] . " and oa.group=" . $data["group"] . "
						inner join mt_object_tag ot on ot.object_type_id=" . $data["article_object_type_id"] . " and ot.object_id=a.article_id
						inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
						where " . $data["conditions"] . "
						group by a.article_id, oa.`group`, oa.`order`, ot.`group`, ot.`order` having count(t.tag) >= " . $data["tags_count"] . "
					) z on z.article_id=a.article_id";
		}
	
		public static function count_articles_by_object_group_with_all_tags($data = array()) {
			return "select count(article_id) total
					from (
						select a.article_id, count(t.tag) tags_count
						from ma_article a
						inner join ma_object_article oa on oa.article_id=a.article_id and oa.object_type_id=" . $data["object_type_id"] . " and oa.object_id=" . $data["object_id"] . " and oa.group=" . $data["group"] . "
						inner join mt_object_tag ot on ot.object_type_id=" . $data["article_object_type_id"] . " and ot.object_id=a.article_id
						inner join mt_tag t on t.tag_id=ot.tag_id and t.tag in (" . $data["tags"] . ")
						where " . $data["conditions"] . "
						group by a.article_id having count(t.tag) >= " . $data["tags_count"] . "
					) Z";
		}
	
		public static function get_articles_by_object($data = array()) {
			return "select a.*, oa.`group` `group`, oa.`order` `order`
					from ma_article a 
					inner join ma_object_article oa on oa.article_id=a.article_id and oa.object_type_id=" . $data["object_type_id"] . " and oa.object_id=" . $data["object_id"] . "
					where " . $data["conditions"];
		}
	
		public static function count_articles_by_object($data = array()) {
			return "select count(a.article_id) total
					from ma_article a 
					inner join ma_object_article oa on oa.article_id=a.article_id and oa.object_type_id=" . $data["object_type_id"] . " and oa.object_id=" . $data["object_id"] . "
					where " . $data["conditions"];
		}
	
		public static function get_articles_by_object_group($data = array()) {
			return "select a.*, oa.`group` `group`, oa.`order` `order`
					from ma_article a 
					inner join ma_object_article oa on oa.article_id=a.article_id and oa.object_type_id=" . $data["object_type_id"] . " and oa.object_id=" . $data["object_id"] . " and oa.`group`=" . $data["group"] . "
					where " . $data["conditions"];
		}
	
		public static function count_articles_by_object_group($data = array()) {
			return "select count(a.article_id) total
					from ma_article a 
					inner join ma_object_article oa on oa.article_id=a.article_id and oa.object_type_id=" . $data["object_type_id"] . " and oa.object_id=" . $data["object_id"] . " and oa.`group`=" . $data["group"] . "
					where " . $data["conditions"];
		}
	
	}
}
?>