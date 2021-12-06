<?php
namespace Module\Zip;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/ZoneDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class ZoneService extends \soa\CommonService {
	private $Zone;
	
	private function getZoneHbnObj($b, $options) {
		if (!$this->Zone)
			$this->Zone = $b->callObject("module/zip", "Zone", $options);
		
		return $this->Zone;
	}
	
	/**
	 * @param (name=data[zone_id], type=bigint, length=19)
	 * @param (name=data[city_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 */
	public function insertZone($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["name"] = addcslashes($data["name"], "\\'");
			
			if ($data["zone_id"]) {
				$options["hard_coded_ai_pk"] = true;
				$status = $b->callInsert("module/zip", "insert_zone_with_ai_pk", $data, $options);
				return $status ? $data["zone_id"] : $status;
			}
			
			$status = $b->callInsert("module/zip", "insert_zone", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			if (!$data["zone_id"]) 
				unset($data["zone_id"]);
			
			$Zone = $this->getZoneHbnObj($b, $options);
			$status = $Zone->insert($data, $ids);
			return $status ? $ids["zone_id"] : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$attributes = array(
				"city_id" => $data["city_id"], 
				"name" => $data["name"],
				"created_date" => $data["created_date"], 
				"modified_date" => $data["modified_date"]
			);
			
			if ($data["zone_id"]) {
				$options["hard_coded_ai_pk"] = true;
				$attributes["zone_id"] = $data["zone_id"];
			}
			
			$status = $b->insertObject("mz_zone", $attributes, $options);
			return $status ? ($data["zone_id"] ? $data["zone_id"] : $b->getInsertedId($options)) : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "ZoneService.insertZone", $data, $options);
	}
	
	/**
	 * @param (name=data[zone_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[city_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 */
	public function updateZone($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["name"] = addcslashes($data["name"], "\\'");
			
			return $b->callUpdate("module/zip", "update_zone", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Zone = $this->getZoneHbnObj($b, $options);
			return $Zone->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mz_zone", array(
					"city_id" => $data["city_id"], 
					"name" => $data["name"],
					"modified_date" => $data["modified_date"]
				), array(
					"zone_id" => $data["zone_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "ZoneService.updateZone", $data, $options);
	}
	
	/**
	 * @param (name=data[zone_id], type=bigint, not_null=1, length=19) 
	 */
	public function deleteZone($data) {
		$zone_id = $data["zone_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/zip", "delete_zone", array("zone_id" => $zone_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Zone = $this->getZoneHbnObj($b, $options);
			return $Zone->delete($zone_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mz_zone", array("zone_id" => $zone_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "ZoneService.deleteZone", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][zone_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][city_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function deleteZonesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callDelete("module/zip", "delete_zones_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Zone = $this->getZoneHbnObj($b, $options);
				return $Zone->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->deleteObject("mz_zone", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/zip", "ZoneService.deleteZonesByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[state_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteZonesByStateId($data) {
		$state_id = $data["state_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callDelete("module/zip", "delete_zones_by_state_id", array("state_id" => $state_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Zone = $this->getZoneHbnObj($b, $options);
			return $Zone->callDelete("delete_zones_by_state_id", array("state_id" => $state_id), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = ZoneDBDAOServiceUtil::delete_zones_by_state_id(array("state_id" => $state_id));
			return $b->setSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "ZoneService.deleteZonesByStateId", $data, $options);
	}
	
	/**
	 * @param (name=data[country_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteZonesByCountryId($data) {
		$country_id = $data["country_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/zip", "delete_zones_by_country", array("country_id" => $country_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Zone = $this->getZoneHbnObj($b, $options);
			return $Zone->callDelete("delete_zones_by_country", array("country_id" => $country_id), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = ZoneDBDAOServiceUtil::delete_zones_by_country(array("country_id" => $country_id));
			return $b->setSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/zip", "ZoneService.deleteZonesByCountryId", $data, $options);
	}
	
	/**
	 * @param (name=data[zone_id], type=bigint, not_null=1, length=19) 
	 */
	public function getZone($data) {
		$zone_id = $data["zone_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/zip", "get_zone", array("zone_id" => $zone_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Zone = $this->getZoneHbnObj($b, $options);
			$result = $Zone->callSelect("get_zone", array("zone_id" => $zone_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mz_zone", null, array("zone_id" => $zone_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "ZoneService.getZone", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][zone_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][city_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function getZonesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/zip", "get_zones_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Zone = $this->getZoneHbnObj($b, $options);
				return $Zone->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mz_zone", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/zip", "ZoneService.getZonesByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][zone_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][city_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function countZonesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/zip", "count_zones_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Zone = $this->getZoneHbnObj($b, $options);
				return $Zone->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mz_zone", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/zip", "ZoneService.countZonesByConditions", $data, $options);
		}
	}
	
	public function getAllZones($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/zip", "get_all_zones", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Zone = $this->getZoneHbnObj($b, $options);
			return $Zone->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mz_zone", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/zip", "ZoneService.getAllZones", null, $options);
	}
	
	public function countAllZones($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/zip", "count_all_zones", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Zone = $this->getZoneHbnObj($b, $options);
			return $Zone->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mz_zone", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/zip", "ZoneService.countAllZones", null, $options);
	}
	
	public function getFullZones($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/zip", "get_full_zones", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Zone = $this->getZoneHbnObj($b, $options);
			return $Zone->callSelect("get_full_zones", null, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = ZoneDBDAOServiceUtil::get_full_zones();
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "ZoneService.getFullZones", null, $options);
	}
}
?>
