<?php
namespace Module\Menu;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/MenuItemDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class MenuItemService extends \soa\CommonService {
	private $MenuItem;
	
	private function getMenuItemHbnObj($b, $options) {
		if (!$this->MenuItem) 
			$this->MenuItem = $b->callObject("module/menu", "MenuItem", $options);
		
		return $this->MenuItem;
	}
	
	/**
	 * @param (name=data[group_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[parent_id], type=bigint, default=0)
	 * @param (name=data[label], type=varchar, not_null=1, length=255)
	 * @param (name=data[title], type=varchar, default="", length=255)
	 * @param (name=data[class], type=varchar, default="", length=255)
	 * @param (name=data[url], type=varchar, default="")
	 * @param (name=data[previous_html], type=longblob, default="")
	 * @param (name=data[next_html], type=longblob, default="")
	 * @param (name=data[order], type=smallint, default=0)
	 */
	public function insertMenuItem($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["label"] = addcslashes($data["label"], "\\'");
			$data["title"] = isset($data["title"]) ? addcslashes($data["title"], "\\'") : "";
			$data["class"] = isset($data["class"]) ? addcslashes($data["class"], "\\'") : "";
			$data["url"] = isset($data["url"]) ? addcslashes($data["url"], "\\'") : "";
			$data["previous_html"] = isset($data["previous_html"]) ? addcslashes($data["previous_html"], "\\'") : "";
			$data["next_html"] = isset($data["next_html"]) ? addcslashes($data["next_html"], "\\'") : "";
			
			$status = $b->callInsert("module/menu", "insert_menu_item", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuItem = $this->getMenuItemHbnObj($b, $options);
			$ids = null;
			$status = $MenuItem->insert($data, $ids);
			return $status ? (isset($ids["item_id"]) ? $ids["item_id"] : null) : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$status = $b->insertObject("mmenu_item", array(
					"group_id" => $data["group_id"], 
					"parent_id" => isset($data["parent_id"]) ? $data["parent_id"] : null, 
					"label" => $data["label"], 
					"title" => isset($data["title"]) ? $data["title"] : null, 
					"class" => isset($data["class"]) ? $data["class"] : null, 
					"url" => isset($data["url"]) ? $data["url"] : null, 
					"previous_html" => isset($data["previous_html"]) ? $data["previous_html"] : null, 
					"next_html" => isset($data["next_html"]) ? $data["next_html"] : null, 
					"order" => isset($data["order"]) ? $data["order"] : null, 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuItemService.insertMenuItem", $data, $options);
	}
	
	/**
	 * @param (name=data[item_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[group_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[parent_id], type=bigint, default=0)
	 * @param (name=data[label], type=varchar, not_null=1, length=255)
	 * @param (name=data[title], type=varchar, default="", length=255)
	 * @param (name=data[class], type=varchar, default="", length=255)
	 * @param (name=data[url], type=varchar, default="")
	 * @param (name=data[previous_html], type=longblob, default="")
	 * @param (name=data[next_html], type=longblob, default="")
	 * @param (name=data[order], type=smallint, default=0)
	  */
	public function updateMenuItem($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["label"] = addcslashes($data["label"], "\\'");
			$data["title"] = isset($data["title"]) ? addcslashes($data["title"], "\\'") : "";
			$data["class"] = isset($data["class"]) ? addcslashes($data["class"], "\\'") : "";
			$data["url"] = isset($data["url"]) ? addcslashes($data["url"], "\\'") : "";
			$data["previous_html"] = isset($data["previous_html"]) ? addcslashes($data["previous_html"], "\\'") : "";
			$data["next_html"] = isset($data["next_html"]) ? addcslashes($data["next_html"], "\\'") : "";
			
			return $b->callUpdate("module/menu", "update_menu_item", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuItem = $this->getMenuItemHbnObj($b, $options);
			return $MenuItem->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mmenu_item", array(
					"group_id" => $data["group_id"], 
					"parent_id" => isset($data["parent_id"]) ? $data["parent_id"] : null, 
					"label" => $data["label"], 
					"title" => isset($data["title"]) ? $data["title"] : null, 
					"class" => isset($data["class"]) ? $data["class"] : null, 
					"url" => isset($data["url"]) ? $data["url"] : null, 
					"previous_html" => isset($data["previous_html"]) ? $data["previous_html"] : null, 
					"next_html" => isset($data["next_html"]) ? $data["next_html"] : null, 
					"order" => isset($data["order"]) ? $data["order"] : null, 
					"modified_date" => $data["modified_date"]
				), array(
					"item_id" => $data["item_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuItemService.updateMenuItem", $data, $options);
	}
	
	/**
	 * @param (name=data[item_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteMenuItem($data) {
		$item_id = $data["item_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/menu", "delete_menu_item", array("item_id" => $item_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuItem = $this->getMenuItemHbnObj($b, $options);
			return $MenuItem->delete($item_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mmenu_item", array("item_id" => $item_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuItemService.deleteMenuItem", $data, $options);
	}
	
	/**
	 * @param (name=data[group_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteMenuItemsByGroupId($data) {
		$group_id = $data["group_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/menu", "delete_menu_items_by_group_id", array("group_id" => $group_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuItem = $this->getMenuItemHbnObj($b, $options);
			$conditions = array("group_id" => $group_id);
			return $MenuItem->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mmenu_item", array("group_id" => $group_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuItemService.deleteMenuItemsByGroupId", $data, $options);
	}
	
	/**
	 * @param (name=data[parent_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteMenuItemsByParentId($data) {
		$parent_id = $data["parent_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/menu", "delete_menu_items_by_parent_id", array("parent_id" => $parent_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuItem = $this->getMenuItemHbnObj($b, $options);
			$conditions = array("parent_id" => $parent_id);
			return $MenuItem->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mmenu_item", array("parent_id" => $parent_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuItemService.deleteMenuItemsByParentId", $data, $options);
	}
	
	/**
	 * @param (name=data[item_id], type=bigint, not_null=1, length=19)  
	 */
	public function getMenuItem($data) {
		$item_id = $data["item_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/menu", "get_menu_item", array("item_id" => $item_id), $options);
			return isset($result[0]) ? $result[0] : null;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuItem = $this->getMenuItemHbnObj($b, $options);
			return $MenuItem->findById($item_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mmenu_item", null, array("item_id" => $item_id), $options);
			return isset($result[0]) ? $result[0] : null;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuItemService.getMenuItem", $data, $options);
	}
	
	/**
	 * @param (name=data[group_id], type=bigint, not_null=1, length=19)
	 */
	public function getMenuItemsByGroupId($data) {
		$group_id = $data["group_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/menu", "get_menu_items_by_group_id", array("group_id" => $group_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuItem = $this->getMenuItemHbnObj($b, $options);
			$conditions = array("group_id" => $group_id);
			return $MenuItem->find(array("conditions" => $conditions), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mmenu_item", null, array("group_id" => $group_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuItemService.getMenuItemsByGroupId", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 */
	public function getMenuItemsByFirstGroupIdOfObject($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/menu", "get_menu_items_by_first_group_of_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuItem = $this->getMenuItemHbnObj($b, $options);
			return $MenuItem->callSelect("get_menu_items_by_first_group_of_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = MenuItemDBDAOServiceUtil::get_menu_items_by_first_group_of_object(array("object_type_id" => $object_type_id, "object_id" => $object_id));
			
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuItemService.getMenuItemsByFirstGroupIdOfObject", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 */
	public function getMenuItemsByFirstGroupIdOfObjectGroup($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = isset($data["group"]) ? $data["group"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/menu", "get_menu_items_by_first_group_of_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuItem = $this->getMenuItemHbnObj($b, $options);
			return $MenuItem->callSelect("get_menu_items_by_first_group_of_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = MenuItemDBDAOServiceUtil::get_menu_items_by_first_group_of_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group));
			
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuItemService.getMenuItemsByFirstGroupIdOfObjectGroup", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][item_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][label], type=varchar|array, length=255)
	 * @param (name=data[conditions][title], type=varchar|array, length=255)
	 * @param (name=data[conditions][class], type=varchar|array, length=255)
	 */
	public function getMenuItemsByConditions($data) {
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $conditions_join);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/menu", "get_menu_items_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$MenuItem = $this->getMenuItemHbnObj($b, $options);
				return $MenuItem->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $conditions_join;
				return $b->findObjects("mmenu_item", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/menu", "MenuItemService.getMenuItemsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][item_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][label], type=varchar|array, length=255)
	 * @param (name=data[conditions][title], type=varchar|array, length=255)
	 * @param (name=data[conditions][class], type=varchar|array, length=255)
	 */
	public function countMenuItemsByConditions($data) {
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $conditions_join);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/menu", "count_menu_items_by_conditions", array("conditions" => $cond), $options);
				return isset($result[0]["total"]) ? $result[0]["total"] : null;
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$MenuItem = $this->getMenuItemHbnObj($b, $options);
				return $MenuItem->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $conditions_join;
				return $b->countObjects("mmenu_item", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/menu", "MenuItemService.countMenuItemsByConditions", $data, $options);
		}
	}
	
	public function getAllMenuItems($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/menu", "get_all_menu_items", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuItem = $this->getMenuItemHbnObj($b, $options);
			return $MenuItem->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mmenu_item", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuItemService.getAllMenuItems", $data, $options);
	}
	
	public function countAllMenuItems($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/menu", "count_all_menu_items", null, $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$MenuItem = $this->getMenuItemHbnObj($b, $options);
			return $MenuItem->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mmenu_item", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/menu", "MenuItemService.countAllMenuItems", $data, $options);
	}
}
?>
