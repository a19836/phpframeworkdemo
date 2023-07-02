<?php
namespace Module\Menu;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/MenuGroupDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class MenuGroupService extends \soa\CommonService {
	private $MenuGroup;
	
	private function getMenuGroupHbnObj($b, $options) {
		if (!$this->MenuGroup)
			$this->MenuGroup = $b->callObject("module/menu", "MenuGroup", $options);
		
		return $this->MenuGroup;
	}
	
	/**
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 */
	public function insertMenuGroup($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["name"] = addcslashes($data["name"], "\\'");
			
			$status = $b->callInsert("module/menu", "insert_menu_group", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuGroup = $this->getMenuGroupHbnObj($b, $options);
			$status = $MenuGroup->insert($data, $ids);
			return $status ? $ids["group_id"] : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$status = $b->insertObject("mmenu_group", array(
					"name" => $data["name"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/menu", "MenuGroupService.insertMenuGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[group_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 */
	public function updateMenuGroup($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["name"] = addcslashes($data["name"], "\\'");
			
			return $b->callUpdate("module/menu", "update_menu_group", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuGroup = $this->getMenuGroupHbnObj($b, $options);
			return $MenuGroup->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mmenu_group", array(
					"name" => $data["name"], 
					"modified_date" => $data["modified_date"]
				), array(
					"group_id" => $data["group_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuGroupService.updateMenuGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[group_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteMenuGroup($data) {
		$group_id = $data["group_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/menu", "delete_menu_group", array("group_id" => $group_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuGroup = $this->getMenuGroupHbnObj($b, $options);
			return $MenuGroup->delete($group_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mmenu_group", array("group_id" => $group_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuGroupService.deleteMenuGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[group_id], type=bigint, not_null=1, length=19)  
	 */
	public function getMenuGroup($data) {
		$group_id = $data["group_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/menu", "get_menu_group", array("group_id" => $group_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuGroup = $this->getMenuGroupHbnObj($b, $options);
			return $MenuGroup->findById($group_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mmenu_group", null, array("group_id" => $group_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuGroupService.getMenuGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][group_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function getMenuGroupsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/menu", "get_menu_groups_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$MenuGroup = $this->getMenuGroupHbnObj($b, $options);
				return $MenuGroup->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mmenu_group", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/menu", "MenuGroupService.getMenuGroupsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][group_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function countMenuGroupsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/menu", "count_menu_groups_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$MenuGroup = $this->getMenuGroupHbnObj($b, $options);
				return $MenuGroup->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mmenu_group", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/menu", "MenuGroupService.countMenuGroupsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[conditions][group_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function getMenuGroupsByObjectAndConditions($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/menu", "get_menu_groups_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				
				$MenuGroup = $this->getMenuGroupHbnObj($b, $options);
				return $MenuGroup->callSelect("get_menu_groups_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$sql = MenuGroupDBDAOServiceUtil::get_menu_groups_by_object_and_conditions(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
				
				return $b->getSQL($sql, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/menu", "MenuGroupService.getMenuGroupsByObjectAndConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[conditions][group_id], type=bigint|array, length=19)  
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function countMenuGroupsByObjectAndConditions($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/menu", "count_menu_groups_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				
				$MenuGroup = $this->getMenuGroupHbnObj($b, $options);
				$result = $MenuGroup->callSelect("count_menu_groups_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$sql = MenuGroupDBDAOServiceUtil::count_menu_groups_by_object_and_conditions(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
				
				$result = $b->getSQL($sql, $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/menu", "MenuGroupService.countMenuGroupsByObjectAndConditions", $data, $options);
		}
	}
	
	public function getAllMenuGroups($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/menu", "get_all_menu_groups", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuGroup = $this->getMenuGroupHbnObj($b, $options);
			return $MenuGroup->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mmenu_group", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuGroupService.getAllMenuGroups", $data, $options);
	}
	
	public function countAllMenuGroups($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/menu", "count_all_menu_groups", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuGroup = $this->getMenuGroupHbnObj($b, $options);
			return $MenuGroup->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mmenu_group", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuGroupService.countAllMenuGroups", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 */
	public function getMenuGroupsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/menu", "get_menu_groups_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuGroup = $this->getMenuGroupHbnObj($b, $options);
			return $MenuGroup->callSelect("get_menu_groups_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = MenuGroupDBDAOServiceUtil::get_menu_groups_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id));
			
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuGroupService.getMenuGroupsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 */
	public function countMenuGroupsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/menu", "count_menu_groups_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuGroup = $this->getMenuGroupHbnObj($b, $options);
			$result = $MenuGroup->callSelect("count_menu_groups_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = MenuGroupDBDAOServiceUtil::count_menu_groups_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id));
			
			$result = $b->getSQL($sql, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuGroupService.countMenuGroupsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 */
	public function getMenuGroupsByObjectGroup($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/menu", "get_menu_groups_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuGroup = $this->getMenuGroupHbnObj($b, $options);
			return $MenuGroup->callSelect("get_menu_groups_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = MenuGroupDBDAOServiceUtil::get_menu_groups_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group));
			
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuGroupService.getMenuGroupsByObjectGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 */
	public function countMenuGroupsByObjectGroup($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/menu", "count_menu_groups_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuGroup = $this->getMenuGroupHbnObj($b, $options);
			$result = $MenuGroup->callSelect("count_menu_groups_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = MenuGroupDBDAOServiceUtil::count_menu_groups_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group));
			
			$result = $b->getSQL($sql, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuGroupService.countMenuGroupsByObjectGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][group_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function getMenuGroupsWithAllTags($data) {
		$tags = $data["tags"];
		$object_type_id = $data["object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$tags_count = count($tags);
			
			if ($tags_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
					return $b->callSelect("module/menu", "get_menu_groups_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
					$MenuGroup = $this->getMenuGroupHbnObj($b, $options);
					return $MenuGroup->callSelect("get_menu_groups_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
					$sql = MenuGroupDBDAOServiceUtil::get_menu_groups_with_all_tags(array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id, "conditions" => $cond));
					
					return $b->getSQL($sql, $options);
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/menu", "MenuGroupService.getMenuGroupsWithAllTags", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][group_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function countMenuGroupsWithAllTags($data) {
		$tags = $data["tags"];
		$object_type_id = $data["object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$tags_count = count($tags);
			
			if ($tags_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
					$result = $b->callSelect("module/menu", "count_menu_groups_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
					$MenuGroup = $this->getMenuGroupHbnObj($b, $options);
					$result = $MenuGroup->callSelect("count_menu_groups_with_all_tags", array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
					$sql = MenuGroupDBDAOServiceUtil::count_menu_groups_with_all_tags(array("tags" => $tags_str, "tags_count" => $tags_count, "object_type_id" => $object_type_id, "conditions" => $cond));
					
					$result = $b->getSQL($sql, $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/menu", "MenuGroupService.countMenuGroupsWithAllTags", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][group_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function getMenuGroupsByTags($data) {
		$tags = $data["tags"];
		$object_type_id = $data["object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
				return $b->callSelect("module/menu", "get_menu_groups_by_tags", array("tags" => $tags_str, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
				$MenuGroup = $this->getMenuGroupHbnObj($b, $options);
				return $MenuGroup->callSelect("get_menu_groups_by_tags", array("tags" => $tags_str, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				$sql = MenuGroupDBDAOServiceUtil::get_menu_groups_by_tags(array("tags" => $tags_str, "object_type_id" => $object_type_id, "conditions" => $cond));
				
				return $b->getSQL($sql, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/menu", "MenuGroupService.getMenuGroupsByTags", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[tags], type=mixed, not_null=1)  
	 * @param (name=data[conditions][group_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function countMenuGroupsByTags($data) {
		$tags = $data["tags"];
		$object_type_id = $data["object_type_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($tags) {
			$tags_str = "";
			$tags = is_array($tags) ? $tags : array($tags);
			foreach ($tags as $tag) 
				$tags_str .= ($tags_str ? ", " : "") . "'" . addcslashes($tag, "\\'") . "'";
			
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
				$result = $b->callSelect("module/menu", "count_menu_groups_by_tags", array("tags" => $tags_str, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				
				$MenuGroup = $this->getMenuGroupHbnObj($b, $options);
				$result = $MenuGroup->callSelect("count_menu_groups_by_tags", array("tags" => $tags_str, "object_type_id" => $object_type_id, "conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$cond = self::getSQLConditions($data["conditions"], $data["conditions_join"], "a");
				$sql = MenuGroupDBDAOServiceUtil::count_menu_groups_by_tags(array("tags" => $tags_str, "object_type_id" => $object_type_id, "conditions" => $cond));
				
				$result = $b->getSQL($sql, $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/menu", "MenuGroupService.countMenuGroupsByTags", $data, $options);
		}
	}
	
	private static function getSQLConditions($conditions, $conditions_join, $key_prefix) {
		$cond = \DB::getSQLConditions($conditions, $conditions_join, $key_prefix);
		return $cond ? $cond : '1=1';
	}
}
?>
