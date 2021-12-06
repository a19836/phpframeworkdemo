<?php
include_once $EVC->getModulePath("object/ObjectUtil", $EVC->getCommonProjectName());
include_once $EVC->getModulePath("tag/TagUtil", $EVC->getCommonProjectName());
include_once __DIR__ . "/MenuGroupDBDAOUtil.php"; //this file will be automatically generated on this module installation
include_once __DIR__ . "/MenuItemDBDAOUtil.php"; //this file will be automatically generated on this module installation

class MenuUtil {
	
	/* GENERIC FUNCTIONS */
	public static function encapsulateMenuGroupItems($items, $parent_id = 0, $items_label = "items") {
		$new_items = array();
		
		if ($items)
			foreach ($items as $item) {
				if ($item["parent_id"] == $parent_id) {
					$sub_items = self::encapsulateMenuGroupItems($items, $item["item_id"], $items_label);
					
					if ($sub_items)
						$item[$items_label] = $sub_items;
					
					$new_items[] = $item;
				}
			}
		
		return $new_items;
	}
	
	public static function decapsulateMenuGroupItems($items, $parent_id = 0, $items_label = "items", $include_parent = true) {
		$new_items = array();
		
		if ($items)
			foreach ($items as $item) {
				if (empty($parent_id) || $item["item_id"] == $parent_id) {
					$sub_items = $item[$items_label];
					
					if ($include_parent) {
						unset($item[$items_label]);
						$new_items[] = $item;
					}
					
					if ($sub_items) {
						$sub_items = self::decapsulateMenuGroupItems($sub_items, 0, $items_label, true);
						$new_items = $sub_items ? array_merge($new_items, $sub_items) : $new_items;
					}
				}
				else if ($item[$items_label]) {
					$sub_items = self::decapsulateMenuGroupItems($item[$items_label], $parent_id, $items_label, $include_parent);
					$new_items = $sub_items ? array_merge($new_items, $sub_items) : $new_items;
				}
			}
		
		return $new_items;
	}
	
	/* MENU GROUP FUNCTIONS */

	public static function insertMenuGroup($brokers, $data) {
		if (is_array($brokers)) {
			$status = false;
					
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$group_id = $broker->callBusinessLogic("module/menu", "MenuGroupService.insertMenuGroup", $data);
					$status = $group_id ? true : false;
					break;
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["name"] = addcslashes($data["name"], "\\'");
				
					$status = $broker->callInsert("module/menu", "insert_menu_group", $data);
					$group_id = $status ? $broker->getInsertedId() : false;
					break;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuGroup = $broker->callObject("module/menu", "MenuGroup");
					$status = $MenuGroup->insert($data, $ids);
					$group_id = $status ? $ids["group_id"] : false;
					break;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$status = $broker->insertObject("mmenu_group", array(
							"name" => $data["name"], 
							"created_date" => $data["created_date"], 
							"modified_date" => $data["modified_date"]
						));
					$group_id = $status ? $broker->getInsertedId() : false;
					break;
				}
			}
			
			if ($status && $group_id && !self::updateMenuObjectGroupsByGroupId(array($broker), $group_id, $data))
				$status = false;
			
			return $status ? $group_id : false;
		}
	}

	public static function updateMenuGroup($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["group_id"])) {
			$status = false;
				
			$data["modified_date"] = date("Y-m-d H:i:s");
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$status = $broker->callBusinessLogic("module/menu", "MenuGroupService.updateMenuGroup", $data);
					break;
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["name"] = addcslashes($data["name"], "\\'");
				
					$status = $broker->callUpdate("module/menu", "update_menu_group", $data);
					break;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuGroup = $broker->callObject("module/menu", "MenuGroup");
					$status = $MenuGroup->update($data);
					break;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$status = $broker->updateObject("mmenu_group", array(
							"name" => $data["name"], 
							"modified_date" => $data["modified_date"]
						), array(
							"group_id" => $data["group_id"]
						));
					break;
				}
			}
			
			if ($status && $data["group_id"] && !self::updateMenuObjectGroupsByGroupId(array($broker), $data["group_id"], $data))
				$status = false;
			
			return $status;
		}
	}

	public static function deleteMenuGroup($brokers, $group_id) {
		if (is_array($brokers) && is_numeric($group_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/menu", "MenuGroupService.deleteMenuGroup", array("group_id" => $group_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/menu", "delete_menu_group", array("group_id" => $group_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuGroup = $broker->callObject("module/menu", "MenuGroup");
					return $MenuGroup->delete($group_id);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mmenu_group", array("group_id" => $group_id));
				}
			}
		}
	}
	
	//$conditions must be an array containing multiple conditions
	public static function getMenuGroupsByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/menu", "MenuGroupService.getMenuGroupsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callSelect("module/menu", "get_menu_groups_by_conditions", array("conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuGroup = $broker->callObject("module/menu", "MenuGroup");
					return $MenuGroup->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options["conditions_join"] = $conditions_join;
					return $broker->findObjects("mmenu_group", null, $conditions, $options);
				}
			}
		}
	}
	
	//$conditions must be an array containing multiple conditions
	public static function countMenuGroupsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/menu", "MenuGroupService.countMenuGroupsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/menu", "count_menu_groups_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuGroup = $broker->callObject("module/menu", "MenuGroup");
					return $MenuGroup->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mmenu_group", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	//$conditions must be an array containing multiple conditions
	public static function getMenuGroupsByObjectAndConditions($brokers, $object_type_id, $object_id, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/menu", "MenuGroupService.getMenuGroupsByObjectAndConditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					return $broker->callSelect("module/menu", "get_menu_groups_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					
					$MenuGroup = $broker->callObject("module/menu", "MenuGroup");
					return $MenuGroup->callSelect("get_menu_groups_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = MenuGroupDBDAOUtil::get_menu_groups_by_object_and_conditions(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
					
					return $broker->getSQL($sql, $options);
				}
			}
		}
	}
	
	//$conditions must be an array containing multiple conditions
	public static function countMenuGroupsByObjectAndConditions($brokers, $object_type_id, $object_id, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/menu", "MenuGroupService.countMenuGroupsByObjectAndConditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$result = $broker->callSelect("module/menu", "count_menu_groups_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					
					$MenuGroup = $broker->callObject("module/menu", "MenuGroup");
					$result = $MenuGroup->callSelect("count_menu_groups_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = MenuGroupDBDAOUtil::count_menu_groups_by_object_and_conditions(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
					
					$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
			}
		}
	}

	public static function getAllMenuGroups($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/menu", "MenuGroupService.getAllMenuGroups", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/menu", "get_all_menu_groups", null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuGroup = $broker->callObject("module/menu", "MenuGroup");
					return $MenuGroup->find(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mmenu_group", null, null, array("no_cache" => $no_cache));
				}
			}
		}
	}
	
	public static function getMenuGroupsByObject($brokers, $object_type_id, $object_id, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/menu", "MenuGroupService.getMenuGroupsByObject", array("object_type_id" => $object_type_id, "object_id" => $object_id, "options" => $options), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/menu", "get_menu_groups_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuGroup = $broker->callObject("module/menu", "MenuGroup");
					return $MenuGroup->callSelect("get_menu_groups_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = MenuGroupDBDAOUtil::get_menu_groups_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id));
					
					return $broker->getSQL($sql, $options);
				}
			}
		}
	}
	
	public static function countMenuGroupsByObject($brokers, $object_type_id, $object_id, $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/menu", "MenuGroupService.countMenuGroupsByObject", array("object_type_id" => $object_type_id, "object_id" => $object_id, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/menu", "count_menu_groups_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuGroup = $broker->callObject("module/menu", "MenuGroup");
					$result = $MenuGroup->callSelect("count_menu_groups_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = MenuGroupDBDAOUtil::count_menu_groups_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id));
					
					$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
			}
		}
	}

	public static function getMenuGroupsByObjectGroup($brokers, $object_type_id, $object_id, $group = null, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$group = is_numeric($group) ? $group : null;
					
					return $broker->callBusinessLogic("module/menu", "MenuGroupService.getMenuGroupsByObjectGroup", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "options" => $options), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					
					return $broker->callSelect("module/menu", "get_menu_groups_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					
					$MenuGroup = $broker->callObject("module/menu", "MenuGroup");
					return $MenuGroup->callSelect("get_menu_groups_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$sql = MenuGroupDBDAOUtil::get_menu_groups_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group));
					
					return $broker->getSQL($sql, $options);
				}
			}
		}
	}
	
	public static function countMenuGroupsByObjectGroup($brokers, $object_type_id, $object_id, $group = null, $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$group = is_numeric($group) ? $group : null;
					
					return $broker->callBusinessLogic("module/menu", "MenuGroupService.countMenuGroupsByObjectGroup", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					
					$result = $broker->callSelect("module/menu", "count_menu_groups_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					
					$MenuGroup = $broker->callObject("module/menu", "MenuGroup");
					$result = $MenuGroup->callSelect("count_menu_groups_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$sql = MenuGroupDBDAOUtil::count_menu_groups_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group));
					
					$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
			}
		}
	}
	
	//$tags is a string containing multiple menu tags
	//This method will return the menu that contains all $tags
	public static function getMenuGroupsWithAllTags($brokers, $tags, $conditions = null, $conditions_join = null, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			$tags_count = count($tags);
			
			if ($tags_str) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/menu", "MenuGroupService.getMenuGroupsWithAllTags", array("tags" => $tags, "object_type_id" => ObjectUtil::MENU_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
						return $broker->callSelect("module/menu", "get_menu_groups_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::MENU_OBJECT_TYPE_ID, "conditions" => $cond), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						
						$MenuGroup = $broker->callObject("module/menu", "MenuGroup");
						return $MenuGroup->callSelect("get_menu_groups_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::MENU_OBJECT_TYPE_ID, "conditions" => $cond), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						$sql = MenuGroupDBDAOUtil::get_menu_groups_with_all_tags(array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::MENU_OBJECT_TYPE_ID, "conditions" => $cond));
						
						return $broker->getSQL($sql, $options);
					}
				}
			}
		}
	}
	
	public static function countMenuGroupsWithAllTags($brokers, $tags, $conditions = null, $conditions_join = null, $no_cache = false) {
		if (is_array($brokers)) {
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			$tags_count = count($tags);
			
			if ($tags_str) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/menu", "MenuGroupService.countMenuGroupsWithAllTags", array("tags" => $tags, "object_type_id" => ObjectUtil::MENU_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						
						$result = $broker->callSelect("module/menu", "count_menu_groups_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::MENU_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						
						$MenuGroup = $broker->callObject("module/menu", "MenuGroup");
						$result = $MenuGroup->callSelect("count_menu_groups_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::MENU_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$cond = self::getSQLConditions($conditions, $conditions_join, "a");
						$sql = MenuGroupDBDAOUtil::count_menu_groups_with_all_tags(array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => ObjectUtil::MENU_OBJECT_TYPE_ID, "conditions" => $cond));
						
						$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
				}
			}
		}
	}
	
	//$tags is a string containing multiple menu tags
	//This method will return the menus that contains at least one tag in $tags
	public static function getMenuGroupsByTags($brokers, $tags, $conditions = null, $conditions_join = null, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/menu", "MenuGroupService.getMenuGroupsByTags", array("tags" => $tags, "object_type_id" => ObjectUtil::MENU_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					return $broker->callSelect("module/menu", "get_menu_groups_by_tags", array("tags" => $tags_str, "object_type_id" => ObjectUtil::MENU_OBJECT_TYPE_ID, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					$MenuGroup = $broker->callObject("module/menu", "MenuGroup");
					return $MenuGroup->callSelect("get_menu_groups_by_tags", array("tags" => $tags_str, "object_type_id" => ObjectUtil::MENU_OBJECT_TYPE_ID, "conditions" => $cond), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					$sql = MenuGroupDBDAOUtil::get_menu_groups_by_tags(array("tags" => $tags_str, "object_type_id" => ObjectUtil::MENU_OBJECT_TYPE_ID, "conditions" => $cond));
					
					return $broker->getSQL($sql, $options);
				}
			}
		}
	}
	
	//$tags is a string containing multiple menu tags
	//This method will return the menu that contains at least one tag in $tags
	public static function countMenuGroupsByTags($brokers, $tags, $conditions = null, $conditions_join = null, $no_cache = false) {
		if (is_array($brokers)) {
			$tags = TagUtil::convertTagsStringToArray($tags);
			$tags = array_values($tags);
		
			$tags_str = "";
			foreach ($tags as $tag) {
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			}
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/menu", "MenuGroupService.countMenuGroupsByTags", array("tags" => $tags, "object_type_id" => ObjectUtil::MENU_OBJECT_TYPE_ID, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					$result = $broker->callSelect("module/menu", "count_menu_groups_by_tags", array("tags" => $tags_str, "object_type_id" => ObjectUtil::MENU_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					
					$MenuGroup = $broker->callObject("module/menu", "MenuGroup");
					$result = $MenuGroup->callSelect("count_menu_groups_by_tags", array("tags" => $tags_str, "object_type_id" => ObjectUtil::MENU_OBJECT_TYPE_ID, "conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($conditions, $conditions_join, "a");
					$sql = MenuGroupDBDAOUtil::count_menu_groups_by_tags(array("tags" => $tags_str, "object_type_id" => ObjectUtil::MENU_OBJECT_TYPE_ID, "conditions" => $cond));
					
					$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
			}
		}
	}
	
	/* MENU ITEM FUNCTIONS */

	public static function insertMenuItem($brokers, $data) {
		if (is_array($brokers)) {
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/menu", "MenuItemService.insertMenuItem", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["parent_id"] = is_numeric($data["parent_id"]) ? $data["parent_id"] : 0;
					$data["label"] = addcslashes($data["label"], "\\'");
					$data["title"] = addcslashes($data["title"], "\\'");
					$data["class"] = addcslashes($data["class"], "\\'");
					$data["url"] = addcslashes($data["url"], "\\'");
					$data["previous_html"] = addcslashes($data["previous_html"], "\\'");
					$data["next_html"] = addcslashes($data["next_html"], "\\'");
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : 0;
					
					$status = $broker->callInsert("module/menu", "insert_menu_item", $data);
					return $status ? $broker->getInsertedId() : $status;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuItem = $broker->callObject("module/menu", "MenuItem");
					$status = $MenuItem->insert($data, $ids);
					return $status ? $ids["item_id"] : $status;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$status = $broker->insertObject("mmenu_item", array(
							"group_id" => $data["group_id"], 
							"parent_id" => $data["parent_id"], 
							"label" => $data["label"], 
							"title" => $data["title"], 
							"class" => $data["class"], 
							"url" => $data["url"], 
							"previous_html" => $data["previous_html"], 
							"next_html" => $data["next_html"], 
							"order" => $data["order"], 
							"created_date" => $data["created_date"], 
							"modified_date" => $data["modified_date"]
						));
					return $status ? $broker->getInsertedId() : $status;
				}
			}
		}
	}

	public static function updateMenuItem($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["item_id"])) {
			$data["modified_date"] = date("Y-m-d H:i:s");
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/menu", "MenuItemService.updateMenuItem", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["parent_id"] = is_numeric($data["parent_id"]) ? $data["parent_id"] : 0;
					$data["label"] = addcslashes($data["label"], "\\'");
					$data["title"] = addcslashes($data["title"], "\\'");
					$data["class"] = addcslashes($data["class"], "\\'");
					$data["url"] = addcslashes($data["url"], "\\'");
					$data["previous_html"] = addcslashes($data["previous_html"], "\\'");
					$data["next_html"] = addcslashes($data["next_html"], "\\'");
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : 0;
					
					return $broker->callUpdate("module/menu", "update_menu_item", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuItem = $broker->callObject("module/menu", "MenuItem");
					return $MenuItem->update($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->updateObject("mmenu_item", array(
							"group_id" => $data["group_id"], 
							"parent_id" => $data["parent_id"], 
							"label" => $data["label"], 
							"title" => $data["title"], 
							"class" => $data["class"], 
							"url" => $data["url"], 
							"previous_html" => $data["previous_html"], 
							"next_html" => $data["next_html"], 
							"order" => $data["order"], 
							"modified_date" => $data["modified_date"]
						), array(
							"item_id" => $data["item_id"]
						));
				}
			}
		}
	}

	public static function deleteMenuItem($brokers, $item_id) {
		if (is_array($brokers) && is_numeric($item_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/menu", "MenuItemService.deleteMenuItem", array("item_id" => $item_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/menu", "delete_menu_item", array("item_id" => $item_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuItem = $broker->callObject("module/menu", "MenuItem");
					return $MenuItem->delete($item_id);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mmenu_item", array("item_id" => $item_id));
				}
			}
		}
	}

	public static function deleteMenuItemsByGroupId($brokers, $group_id) {
		if (is_array($brokers) && is_numeric($group_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/menu", "MenuItemService.deleteMenuItemsByGroupId", array("group_id" => $group_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/menu", "delete_menu_items_by_group_id", array("group_id" => $group_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuItem = $broker->callObject("module/menu", "MenuItem");
					$conditions = array("group_id" => $group_id);
					return $MenuItem->deleteByConditions(array("conditions" => $conditions));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mmenu_item", array("group_id" => $group_id));
				}
			}
		}
	}

	public static function getAllMenuItems($brokers, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => $options);
					$result = $broker->callBusinessLogic("module/menu", "MenuItemService.getAllMenuItems", $data, $options);
					break;
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/menu", "get_all_menu_items", null, $options);
					break;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuItem = $broker->callObject("module/menu", "MenuItem");
					$result = $MenuItem->find(null, $options);
					break;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mmenu_item", null, null, $options);
				}
			}
			
			return self::prepareMenuItemsResult($result, $options);
		}
	}

	public static function countAllMenuItems($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/menu", "MenuItemService.countAllMenuItems", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/menu", "count_all_menu_items", null, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuItem = $broker->callObject("module/menu", "MenuItem");
					return $MenuItem->count(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mmenu_item", null, array("no_cache" => $no_cache));
				}
			}
		}
	}

	public static function getMenuItemsByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$result = $broker->callBusinessLogic("module/menu", "MenuItemService.getMenuItemsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
					break;
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/menu", "get_menu_items_by_conditions", array("conditions" => $cond), $options);
					break;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuItem = $broker->callObject("module/menu", "MenuItem");
					$result = $MenuItem->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
					break;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options["conditions_join"] = $conditions_join;
					$result = $broker->findObjects("mmenu_item", null, $conditions, $options);
					break;
				}
			}
			
			return self::prepareMenuItemsResult($result, $options);
		}
	}

	public static function countMenuItemsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/menu", "MenuItemService.countMenuItemsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/menu", "count_menu_items_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuItem = $broker->callObject("module/menu", "MenuItem");
					return $MenuItem->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mmenu_item", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	public static function getMenuItemsByFirstGroupIdOfObject($brokers, $object_type_id, $object_id, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$result = $broker->callBusinessLogic("module/menu", "MenuItemService.getMenuItemsByFirstGroupIdOfObject", array("object_type_id" => $object_type_id, "object_id" => $object_id, "options" => $options), array("no_cache" => $no_cache));
					break;
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/menu", "get_menu_items_by_first_group_of_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
					break;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuItem = $broker->callObject("module/menu", "MenuItem");
					$result = $MenuItem->callSelect("get_menu_items_by_first_group_of_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
					break;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = MenuItemDBDAOUtil::get_menu_items_by_first_group_of_object(array("object_type_id" => $object_type_id, "object_id" => $object_id));
						
					return $broker->getSQL($sql, $options);
				}
			}
			
			return self::prepareMenuItemsResult($result, $options);
		}
	}
	
	public static function getMenuItemsByFirstGroupIdOfObjectGroup($brokers, $object_type_id, $object_id, $group = null, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$group = is_numeric($group) ? $group : null;
					
					$result = $broker->callBusinessLogic("module/menu", "MenuItemService.getMenuItemsByFirstGroupIdOfObjectGroup", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "options" => $options), array("no_cache" => $no_cache));
					break;
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					
					$result = $broker->callSelect("module/menu", "get_menu_items_by_first_group_of_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
					break;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					
					$MenuItem = $broker->callObject("module/menu", "MenuItem");
					$result = $MenuItem->callSelect("get_menu_items_by_first_group_of_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
					break;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$group = is_numeric($group) ? $group : 0;
					$sql = MenuItemDBDAOUtil::get_menu_items_by_first_group_of_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group));
						
					$result = $broker->getSQL($sql, $options);
					break;
				}
			}
			
			return self::prepareMenuItemsResult($result, $options);
		}
	}
	
	private static function prepareMenuItemsResult($result, $options) {
		if ($options["encapsulate"]) {
			$encapsulate = is_array($options["encapsulate"]) ? $options["encapsulate"] : array();
			$parent_id = $encapsulate["parent_id"] ? $encapsulate["parent_id"] : 0;
			$items_label = $encapsulate["items_label"] ? $encapsulate["items_label"] : "items";
			
			return self::encapsulateMenuGroupItems($result, $parent_id, $items_label);
		}
		
		return $result;
	}
	
	/* OBJECT MENU GROUP FUNCTIONS */

	public static function insertMenuObjectGroup($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["group_id"]) && is_numeric($data["object_type_id"]) && is_numeric($data["object_id"])) {
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					
					return $broker->callBusinessLogic("module/menu", "MenuObjectGroupService.insertMenuObjectGroup", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : 0;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : 0;
					
					return $broker->callInsert("module/menu", "insert_menu_object_group", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
					
					$MenuObjectGroup = $broker->callObject("module/menu", "MenuObjectGroup");
					return $MenuObjectGroup->insert($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
					
					return $broker->insertObject("mmenu_object_group", array(
							"group_id" => $data["group_id"], 
							"object_type_id" => $data["object_type_id"], 
							"object_id" => $data["object_id"], 
							"group" => $data["group"], 
							"order" => $data["order"], 
							"created_date" => $data["created_date"], 
							"modified_date" => $data["modified_date"]
						));
				}
			}
		}
	}

	public static function updateMenuObjectGroup($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["new_group_id"]) && is_numeric($data["new_object_type_id"]) && is_numeric($data["new_object_id"]) && is_numeric($data["old_group_id"]) && is_numeric($data["old_object_type_id"]) && is_numeric($data["old_object_id"])) {
			$data["modified_date"] = date("Y-m-d H:i:s");
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					
					return $broker->callBusinessLogic("module/menu", "MenuObjectGroupService.updateMenuObjectGroup", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : 0;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : 0;
					
					return $broker->callUpdate("module/menu", "update_menu_object_group", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
					
					$MenuObjectGroup = $broker->callObject("module/menu", "MenuObjectGroup");
					return $MenuObjectGroup->updatePrimaryKeys($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
					$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
					
					return $broker->updateObject("mmenu_object_group", array(
							"group_id" => $data["new_group_id"], 
							"object_type_id" => $data["new_object_type_id"], 
							"object_id" => $data["new_object_id"], 
							"group" => $data["group"], 
							"order" => $data["order"], 
							"modified_date" => $data["modified_date"]
						), array(
							"group_id" => $data["old_group_id"], 
							"object_type_id" => $data["old_object_type_id"], 
							"object_id" => $data["old_object_id"], 
						));
				}
			}
		}
	}
	
	private static function updateMenuObjectGroupsByGroupId($brokers, $group_id, $data) {
		if (is_array($brokers) && is_numeric($group_id)) {
			if (self::deleteMenuObjectGroupsByGroupId($brokers, $group_id)) {
				$status = true;
				$object_groups = is_array($data["object_groups"]) ? $data["object_groups"] : array();
				
				foreach ($object_groups as $object_group) {
					if (is_numeric($object_group["object_type_id"]) && is_numeric($object_group["object_id"])) {
						$object_group["group_id"] = $group_id;
					
						if (!self::insertMenuObjectGroup($brokers, $object_group)) {
							$status = false;
						}
					}
				}
				
				return $status;
			}
		}
	}

	public static function deleteMenuObjectGroup($brokers, $group_id, $object_type_id, $object_id) {
		if (is_array($brokers) && is_numeric($group_id) && is_numeric($object_type_id) && is_numeric($object_id)) {
			$data = array("group_id" => $group_id, "object_type_id" => $object_type_id, "object_id" => $object_id);
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/menu", "MenuObjectGroupService.deleteMenuObjectGroup", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/menu", "delete_menu_object_group", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuObjectGroup = $broker->callObject("module/menu", "MenuObjectGroup");
					return $MenuObjectGroup->deleteByConditions(array("conditions" => $data));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mmenu_object_group", $data);
				}
			}
		}
	}

	public static function deleteMenuObjectGroupsByGroupId($brokers, $group_id) {
		if (is_array($brokers) && is_numeric($group_id)) {
			$data = array("group_id" => $group_id);
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/menu", "MenuObjectGroupService.deleteMenuObjectGroupsByGroupId", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/menu", "delete_menu_object_groups_by_group_id", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuObjectGroup = $broker->callObject("module/menu", "MenuObjectGroup");
					return $MenuObjectGroup->deleteByConditions(array("conditions" => $data));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mmenu_object_group", $data);
				}
			}
		}
	}

	public static function deleteMenuObjectGroupsByConditions($brokers, $conditions, $conditions_join) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/menu", "MenuObjectGroupService.deleteMenuObjectGroupsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callDelete("module/menu", "delete_menu_object_groups_by_conditions", array("conditions" => $cond));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuObjectGroup = $broker->callObject("module/menu", "MenuObjectGroup");
					return $MenuObjectGroup->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $conditions_join));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mmenu_object_group", $conditions, array("conditions_join" => $conditions_join));
				}
			}
		}
	}

	public static function getMenuObjectGroup($brokers, $group_id, $object_type_id, $object_id, $no_cache = false) {
		if (is_array($brokers) && is_numeric($group_id) && is_numeric($object_type_id) && is_numeric($object_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/menu", "MenuObjectGroupService.getMenuObjectGroup", array("group_id" => $group_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/menu", "get_menu_object_group", array("group_id" => $group_id, "object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
					return $result[0];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuObjectGroup = $broker->callObject("module/menu", "MenuObjectGroup");
					return $MenuObjectGroup->findById(array("group_id" => $group_id, "object_type_id" => $object_type_id, "object_id" => $object_id), null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$result = $broker->findObjects("mmenu_object_group", null, array("group_id" => $group_id, "object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
					return $result[0];
				}
			}
		}
	}
	
	//$conditions must be an array containing multiple conditions
	public static function getMenuObjectGroupsByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/menu", "MenuObjectGroupService.getMenuObjectGroupsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callSelect("module/menu", "get_menu_object_groups_by_conditions", array("conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuObjectGroup = $broker->callObject("module/menu", "MenuObjectGroup");
					return $MenuObjectGroup->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options["conditions_join"] = $conditions_join;
					return $broker->findObjects("mmenu_object_group", null, $conditions, $options);
				}
			}
		}
	}

	//$conditions must be an array containing multiple conditions
	public static function countMenuObjectGroupsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/menu", "MenuObjectGroupService.countMenuObjectGroupsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/menu", "count_menu_object_groups_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuObjectGroup = $broker->callObject("module/menu", "MenuObjectGroup");
					return $MenuObjectGroup->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mmenu_object_group", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}

	public static function getAllMenuObjectGroups($brokers, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => $options);
					return $broker->callBusinessLogic("module/menu", "MenuObjectGroupService.getAllMenuObjectGroups", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/menu", "get_all_menu_object_groups", null, $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuObjectGroup = $broker->callObject("module/menu", "MenuObjectGroup");
					return $MenuObjectGroup->find(null, $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mmenu_object_group", null, null, $options);
				}
			}
		}
	}

	public static function countAllMenuObjectGroups($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/menu", "MenuObjectGroupService.countAllMenuObjectGroups", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/menu", "count_all_menu_object_groups", null, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuObjectGroup = $broker->callObject("module/menu", "MenuObjectGroup");
					return $MenuObjectGroup->count(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mmenu_object_group", null, array("no_cache" => $no_cache));
				}
			}
		}
	}

	public static function getMenuObjectGroupsByGroupId($brokers, $group_id, $options = array(), $no_cache = false) {
		if (is_array($brokers) && is_numeric($group_id)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("group_id" => $group_id, "options" => $options);
					return $broker->callBusinessLogic("module/menu", "MenuObjectGroupService.getMenuObjectGroupsByGroupId", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/menu", "get_menu_object_groups_by_group_id", array("group_id" => $group_id), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuObjectGroup = $broker->callObject("module/menu", "MenuObjectGroup");
					return $MenuObjectGroup->find(array("conditions" => array("group_id" => $group_id)), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mmenu_object_group", null, array("group_id" => $group_id), $options);
				}
			}
		}
	}

	public static function countMenuObjectGroupsByGroupId($brokers, $group_id, $no_cache = false) {
		if (is_array($brokers) && is_numeric($group_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("group_id" => $group_id, "options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/menu", "MenuObjectGroupService.countMenuObjectGroupsByGroupId", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/menu", "count_menu_object_groups_by_group_id", array("group_id" => $group_id), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$MenuObjectGroup = $broker->callObject("module/menu", "MenuObjectGroup");
					return $MenuObjectGroup->count(array("conditions" => array("group_id" => $group_id)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mmenu_object_group", array("group_id" => $group_id), array("no_cache" => $no_cache));
				}
			}
		}
	}
}
?>
