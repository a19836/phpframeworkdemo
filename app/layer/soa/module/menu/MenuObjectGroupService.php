<?php
namespace Module\Menu;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");

class MenuObjectGroupService extends \soa\CommonService {
	private $MenuObjectGroup;
	
	private function getMenuObjectGroupHbnObj($b, $options) {
		if (!$this->MenuObjectGroup)
			$this->MenuObjectGroup = $b->callObject("module/menu", "MenuObjectGroup", $options);
		
		return $this->MenuObjectGroup;
	}
	
	/**
	 * @param (name=data[group_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[order], type=smallint, default=0)
	 */
	public function insertMenuObjectGroup($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callInsert("module/menu", "insert_menu_object_group", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuObjectGroup = $this->getMenuObjectGroupHbnObj($b, $options);
			return $MenuObjectGroup->insert($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->insertObject("mmenu_object_group", array(
					"group_id" => $data["group_id"], 
					"object_type_id" => $data["object_type_id"], 
					"object_id" => $data["object_id"], 
					"group" => $data["group"], 
					"order" => $data["order"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuObjectGroupService.insertMenuObjectGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[new_group_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_group_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[order], type=smallint, default=0)
	 */
	public function updateMenuObjectGroup($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/menu", "update_menu_object_group", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuObjectGroup = $this->getMenuObjectGroupHbnObj($b, $options);
			return $MenuObjectGroup->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mmenu_object_group", array(
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
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuObjectGroupService.updateMenuObjectGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[new_group_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_group_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 */
	public function updateMenuObjectGroupIds($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/menu", "update_menu_object_group_ids", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuObjectGroup = $this->getMenuObjectGroupHbnObj($b, $options);
			return $MenuObjectGroup->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mmenu_object_group", array(
					"group_id" => $data["new_group_id"], 
					"object_type_id" => $data["new_object_type_id"], 
					"object_id" => $data["new_object_id"], 
					"modified_date" => $data["modified_date"]
				), array(
					"group_id" => $data["old_group_id"], 
					"object_type_id" => $data["old_object_type_id"], 
					"object_id" => $data["old_object_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuObjectGroupService.updateMenuObjectGroupIds", $data, $options);
	}
	
	/**
	 * @param (name=data[new_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[new_object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[old_object_id], type=bigint, not_null=1, length=19)
	 */
	public function changeMenuObjectGroupsObjectIds($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/menu", "change_menu_object_groups_object_ids", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuObjectGroup = $this->getMenuObjectGroupHbnObj($b, $options);
			return $MenuObjectGroup->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mmenu_object_group", array(
					"object_type_id" => $data["new_object_type_id"], 
					"object_id" => $data["new_object_id"], 
					"modified_date" => $data["modified_date"]
				), array(
					"object_type_id" => $data["old_object_type_id"], 
					"object_id" => $data["old_object_id"], 
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuObjectGroupService.changeMenuObjectGroupsObjectIds", $data, $options);
	}
	
	/**
	 * @param (name=data[group_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteMenuObjectGroup($data) {
		$group_id = $data["group_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/menu", "delete_menu_object_group", array("group_id" => $group_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuObjectGroup = $this->getMenuObjectGroupHbnObj($b, $options);
			return $MenuObjectGroup->delete(array("group_id" => $group_id, "object_type_id" => $object_type_id, "object_id" => $object_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mmenu_object_group", array("group_id" => $group_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuObjectGroupService.deleteMenuObjectGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[group_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteMenuObjectGroupsByGroupId($data) {
		$group_id = $data["group_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/menu", "delete_menu_object_groups_by_group_id", array("group_id" => $group_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuObjectGroup = $this->getMenuObjectGroupHbnObj($b, $options);
			$conditions = array("group_id" => $group_id);
			return $MenuObjectGroup->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mmenu_object_group", array("group_id" => $group_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuObjectGroupService.deleteMenuObjectGroupsByGroupId", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteMenuObjectGroupsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/menu", "delete_menu_object_groups_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuObjectGroup = $this->getMenuObjectGroupHbnObj($b, $options);
			$conditions = array("object_type_id" => $object_type_id, "object_id" => $object_id);
			return $MenuObjectGroup->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mmenu_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuObjectGroupService.deleteMenuObjectGroupsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][group_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function deleteMenuObjectGroupsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callDelete("module/menu", "delete_menu_object_groups_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$MenuObjectGroup = $this->getMenuObjectGroupHbnObj($b, $options);
				return $MenuObjectGroup->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]));
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->deleteObject("mmenu_object_group", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/menu", "MenuObjectGroupService.deleteMenuObjectGroupsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[group_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function getMenuObjectGroup($data) {
		$group_id = $data["group_id"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/menu", "get_menu_object_group", array("group_id" => $group_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuObjectGroup = $this->getMenuObjectGroupHbnObj($b, $options);
			return $MenuObjectGroup->findById(array("group_id" => $group_id, "object_type_id" => $object_type_id, "object_id" => $object_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mmenu_object_group", null, array("group_id" => $group_id, "object_type_id" => $object_type_id, "object_id" => $object_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuObjectGroupService.getMenuObjectGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[group_id], type=bigint, not_null=1, length=19)
	 */
	public function getMenuObjectGroupsByGroupId($data) {
		$group_id = $data["group_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/menu", "get_menu_object_groups_by_group_id", array("group_id" => $group_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuObjectGroup = $this->getMenuObjectGroupHbnObj($b, $options);
			$conditions = array("group_id" => $group_id);
			return $MenuObjectGroup->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mmenu_object_group", null, array("group_id" => $group_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuObjectGroupService.getMenuObjectGroupsByGroupId", $data, $options);
	}
	
	/**
	 * @param (name=data[group_id], type=bigint, not_null=1, length=19)
	 */
	public function countMenuObjectGroupsByGroupId($data) {
		$group_id = $data["group_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/menu", "count_menu_object_groups_by_group_id", array("group_id" => $group_id), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuObjectGroup = $this->getMenuObjectGroupHbnObj($b, $options);
			return $MenuObjectGroup->count(array("group_id" => $group_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mmenu_object_group", array("group_id" => $group_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuObjectGroupService.countMenuObjectGroupsByGroupId", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function getMenuObjectGroupsByObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/menu", "get_menu_object_groups_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuObjectGroup = $this->getMenuObjectGroupHbnObj($b, $options);
			$conditions = array("object_type_id" => $object_type_id, "object_id" => $object_id);
			return $MenuObjectGroup->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mmenu_object_group", null, array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuObjectGroupService.getMenuObjectGroupsByObject", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][group_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function getMenuObjectGroupsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/menu", "get_menu_object_groups_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$MenuObjectGroup = $this->getMenuObjectGroupHbnObj($b, $options);
				return $MenuObjectGroup->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mmenu_object_group", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/menu", "MenuObjectGroupService.getMenuObjectGroupsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][group_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_type_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][object_id], type=bigint|array, length=19)
	 */
	public function countMenuObjectGroupsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/menu", "count_menu_object_groups_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$MenuObjectGroup = $this->getMenuObjectGroupHbnObj($b, $options);
				return $MenuObjectGroup->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mmenu_object_group", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/menu", "MenuObjectGroupService.countMenuObjectGroupsByConditions", $data, $options);
		}
	}
	
	public function getAllMenuObjectGroups($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/menu", "get_all_menu_object_groups", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuObjectGroup = $this->getMenuObjectGroupHbnObj($b, $options);
			return $MenuObjectGroup->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mmenu_object_group", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuObjectGroupService.getAllMenuObjectGroups", null, $options);
	}
	
	public function countAllMenuObjectGroups($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/menu", "count_all_menu_object_groups", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuObjectGroup = $this->getMenuObjectGroupHbnObj($b, $options);
			return $MenuObjectGroup->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mmenu_object_group", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuObjectGroupService.countAllMenuObjectGroups", null, $options);
	}
}
?>
