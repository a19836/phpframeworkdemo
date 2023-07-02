<?php
include_once get_lib("org.phpframework.encryption.CryptoKeyHandler");
include_once __DIR__ . "/ZipDBDAOUtil.php"; //this file will be automatically generated on this module installation
include_once __DIR__ . "/ZoneDBDAOUtil.php"; //this file will be automatically generated on this module installation
include_once __DIR__ . "/CityDBDAOUtil.php"; //this file will be automatically generated on this module installation
include_once __DIR__ . "/StateDBDAOUtil.php"; //this file will be automatically generated on this module installation

class ZipUtil {
	
	/* ZIP FUNCTIONS */
	
	public static function insertZip($brokers, $data) {
		if (is_array($brokers) && $data["zip_id"] && is_numeric($data["country_id"]) && is_numeric($data["zone_id"])) {
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "ZipService.insertZip", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$status = $broker->callInsert("module/zip", "insert_zip", $data);
					return $status ? $data["zip_id"] : $status;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Zip = $broker->callObject("module/zip", "Zip");
					$status = $Zip->insert($data, $ids);
					return $status ? $data["zip_id"] : $status;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$status = $broker->insertObject("mz_zip", array(
						"zip_id" => $data["zip_id"], 
						"country_id" => $data["country_id"], 
						"zone_id" => $data["zone_id"], 
						"created_date" => $data["created_date"], 
						"modified_date" => $data["modified_date"]
					));
					return $status ? $data["zip_id"] : $status;
				}
			}
		}
	}
	
	public static function updateZip($brokers, $data) {
		if (is_array($brokers) && $data["zip_id"] && is_numeric($data["country_id"]) && is_numeric($data["zone_id"])) {
			$data["modified_date"] = date("Y-m-d H:i:s");
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "ZipService.updateZip", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callUpdate("module/zip", "update_zip", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Zip = $broker->callObject("module/zip", "Zip");
					return $Zip->update($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->updateObject("mz_zip", array(
						"zone_id" => $data["zone_id"], 
						"modified_date" => $data["modified_date"]
					), array(
						"zip_id" => $data["zip_id"], 
						"country_id" => $data["country_id"]
					));
				}
			}
		}
	}
	
	public static function deleteZip($brokers, $zip_id, $country_id) {
		if (is_array($brokers) && $zip_id && is_numeric($country_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "ZipService.deleteZip", array("zip_id" => $zip_id, "country_id" => $country_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/zip", "delete_zip", array("zip_id" => $zip_id, "country_id" => $country_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Zip = $broker->callObject("module/zip", "Zip");
					return $Zip->delete(array("zip_id" => $zip_id, "country_id" => $country_id));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mz_zip", array("zip_id" => $zip_id, "country_id" => $country_id));
				}
			}
		}
	}
	
	public static function deleteZipsByConditions($brokers, $conditions, $conditions_join) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "ZipService.deleteZipsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callDelete("module/zip", "delete_zips_by_conditions", array("conditions" => $cond));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Zip = $broker->callObject("module/zip", "Zip");
					return $Zip->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $conditions_join));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mz_zip", $conditions, array("conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	public static function deleteZipsByZoneId($brokers, $zone_id) {
		return self::deleteZipsByConditions($brokers, array("zone_id" => $zone_id), null);
	}
	
	public static function deleteZipsByCityId($brokers, $city_id) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "ZipService.deleteZipsByCityId", array("city_id" => $city_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/zip", "delete_zips_by_city_id", array("city_id" => $city_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Zip = $broker->callObject("module/zip", "Zip");
					return $Zip->callDelete("delete_zips_by_city_id", array("city_id" => $city_id));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = ZipDBDAOUtil::delete_zips_by_city_id(array("city_id" => $city_id));
					return $b->setSQL($sql);
				}
			}
		}
	}
	
	public static function deleteZipsByStateId($brokers, $state_id) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "ZipService.deleteZipsByStateId", array("state_id" => $state_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/zip", "delete_zips_by_state_id", array("state_id" => $state_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Zip = $broker->callObject("module/zip", "Zip");
					return $Zip->callDelete("delete_zips_by_state_id", array("state_id" => $state_id));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = ZipDBDAOUtil::delete_zips_by_state_id(array("state_id" => $state_id));
					return $b->setSQL($sql);
				}
			}
		}
	}
	
	public static function deleteZipsByCountryId($brokers, $country_id) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "ZipService.deleteZipsByCountryId", array("country_id" => $country_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/zip", "delete_zips_by_country_id", array("country_id" => $country_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Zip = $broker->callObject("module/zip", "Zip");
					return $Zip->callDelete("delete_zips_by_country_id", array("country_id" => $country_id));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = ZipDBDAOUtil::delete_zips_by_country_id(array("country_id" => $country_id));
					return $b->setSQL($sql);
				}
			}
		}
	}
	
	public static function getAllZips($brokers, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => $options);
					return $broker->callBusinessLogic("module/zip", "ZipService.getAllZips", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/zip", "get_all_zips", null, $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Zip = $broker->callObject("module/zip", "Zip");
					return $Zip->find(null, $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mz_zip", null, null, $options);
				}
			}
		}
	}
	
	public static function countAllZips($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/zip", "ZipService.countAllZips", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/zip", "count_all_zips", null, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Zip = $broker->callObject("module/zip", "Zip");
					return $Zip->count(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mz_zip", null, array("no_cache" => $no_cache));
				}
			}
		}
	}
	
	public static function getZipsByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "ZipService.getZipsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callSelect("module/zip", "get_zips_by_conditions", array("conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Zip = $broker->callObject("module/zip", "Zip");
					return $Zip->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options["conditions_join"] = $conditions_join;
					return $broker->findObjects("mz_zip", null, $conditions, $options);
				}
			}
		}
	}
	
	public static function countZipsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "ZipService.countZipsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/zip", "count_zips_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Zip = $broker->callObject("module/zip", "Zip");
					return $Zip->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options["conditions_join"] = $conditions_join;
					return $broker->countObjects("mz_zip", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	/* ZONE FUNCTIONS */
	
	public static function insertZone($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["city_id"])) {
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
			$options = array();
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "ZoneService.insertZone", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["name"] = addcslashes($data["name"], "\\'");
					
					if ($data["zone_id"]) {
						$options = array("hard_coded_ai_pk" => true);
						$status = $broker->callInsert("module/zip", "insert_zone_with_ai_pk", $data, $options);
						return $status ? $data["zone_id"] : $status;
					}
					
					$status = $broker->callInsert("module/zip", "insert_zone", $data);
					return $status ? $broker->getInsertedId($options) : $status;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					if (!$data["zone_id"]) 
						unset($data["zone_id"]);
					
					$Zone = $broker->callObject("module/zip", "Zone");
					$status = $Zone->insert($data, $ids);
					return $status ? $ids["zone_id"] : $status;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
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
					
					$status = $broker->insertObject("mz_zone", $attributes, $options);
					return $status ? ($data["zone_id"] ? $data["zone_id"] : $broker->getInsertedId($options)) : $status;
				}
			}
		}
	}
	
	public static function updateZone($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["zone_id"]) && is_numeric($data["city_id"])) {
			$data["modified_date"] = date("Y-m-d H:i:s");
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "ZoneService.updateZone", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["name"] = addcslashes($data["name"], "\\'");
					
					return $broker->callUpdate("module/zip", "update_zone", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Zone = $broker->callObject("module/zip", "Zone");
					return $Zone->update($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->updateObject("mz_zone", array(
							"city_id" => $data["city_id"], 
							"name" => $data["name"],
							"modified_date" => $data["modified_date"]
						), array(
							"zone_id" => $data["zone_id"]
						));
				}
			}
		}
	}
	
	public static function deleteZone($brokers, $zone_id) {
		if (is_array($brokers) && is_numeric($zone_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "ZoneService.deleteZone", array("zone_id" => $zone_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/zip", "delete_zone", array("zone_id" => $zone_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Zone = $broker->callObject("module/zip", "Zone");
					return $Zone->delete($zone_id);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mz_zone", array("zone_id" => $zone_id));
				}
			}
		}
	}
	
	public static function deleteZonesByConditions($brokers, $conditions, $conditions_join) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "ZoneService.deleteZonesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callDelete("module/zip", "delete_zones_by_conditions", array("conditions" => $cond));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Zone = $broker->callObject("module/zip", "Zone");
					return $Zone->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $conditions_join));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mz_zone", $conditions, array("conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	public static function deleteZonesByCityId($brokers, $city_id) {
		return self::deleteZonesByConditions($brokers, array("city_id" => $city_id), null);
	}
	
	public static function deleteZonesByStateId($brokers, $state_id) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "ZoneService.deleteZonesByStateId", array("state_id" => $state_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/zip", "delete_zones_by_state_id", array("state_id" => $state_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Zone = $broker->callObject("module/zip", "Zone");
					return $Zone->callDelete("delete_zones_by_state_id", array("state_id" => $state_id));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = ZoneDBDAOUtil::delete_zones_by_state_id(array("state_id" => $state_id));
					return $b->setSQL($sql);
				}
			}
		}
	}
	
	public static function deleteZonesByCountryId($brokers, $country_id) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "ZoneService.deleteZonesByCountryId", array("country_id" => $country_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/zip", "delete_zones_by_country_id", array("country_id" => $country_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Zone = $broker->callObject("module/zip", "Zone");
					return $Zone->callDelete("delete_zones_by_country_id", array("country_id" => $country_id));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = ZoneDBDAOUtil::delete_zones_by_country_id(array("country_id" => $country_id));
					return $b->setSQL($sql);
				}
			}
		}
	}
	
	public static function getAllZones($brokers, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => $options);
					return $broker->callBusinessLogic("module/zip", "ZoneService.getAllZones", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/zip", "get_all_zones", null, $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Zone = $broker->callObject("module/zip", "Zone");
					return $Zone->find(null, $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mz_zone", null, null, $options);
				}
			}
		}
	}
	
	public static function countAllZones($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/zip", "ZoneService.countAllZones", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/zip", "count_all_zones", null, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Zone = $broker->callObject("module/zip", "Zone");
					return $Zone->count(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mz_zone", null, array("no_cache" => $no_cache));
				}
			}
		}
	}
	
	public static function getZonesByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "ZoneService.getZonesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callSelect("module/zip", "get_zones_by_conditions", array("conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Zone = $broker->callObject("module/zip", "Zone");
					return $Zone->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options["conditions_join"] = $conditions_join;
					return $broker->findObjects("mz_zone", null, $conditions, $options);
				}
			}
		}
	}
	
	public static function countZonesByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "ZoneService.countZonesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/zip", "count_zones_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Zone = $broker->callObject("module/zip", "Zone");
					return $Zone->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mz_zone", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	/* CITY FUNCTIONS */
	
	public static function insertCity($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["state_id"])) {
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
			$options = array();
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "CityService.insertCity", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["name"] = addcslashes($data["name"], "\\'");
					
					if ($data["city_id"]) {
						$options = array("hard_coded_ai_pk" => true);
						$status = $broker->callInsert("module/zip", "insert_city_with_ai_pk", $data, $options);
						return $status ? $data["city_id"] : $status;
					}
					
					$status = $broker->callInsert("module/zip", "insert_city", $data);
					return $status ? $broker->getInsertedId($options) : $status;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					if (!$data["city_id"])
						unset($data["city_id"]);
					
					$City = $broker->callObject("module/zip", "City");
					$status = $City->insert($data, $ids);
					return $status ? $ids["city_id"] : $status;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$attributes = array(
						"state_id" => $data["state_id"], 
						"name" => $data["name"], 
						"created_date" => $data["created_date"], 
						"modified_date" => $data["modified_date"]
					);
					
					if ($data["city_id"]) {
						$options["hard_coded_ai_pk"] = true;
						$attributes["city_id"] = $data["city_id"];
					}
					
					$status = $broker->insertObject("mz_city", $attributes, $options);
					return $status ? ($data["city_id"] ? $data["city_id"] : $broker->getInsertedId($options)) : $status;
				}
			}
		}
	}
	
	public static function updateCity($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["city_id"]) && is_numeric($data["state_id"])) {
			$data["modified_date"] = date("Y-m-d H:i:s");
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "CityService.updateCity", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["name"] = addcslashes($data["name"], "\\'");
					
					return $broker->callUpdate("module/zip", "update_city", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$City = $broker->callObject("module/zip", "City");
					return $City->update($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->updateObject("mz_city", array(
							"state_id" => $data["state_id"], 
							"name" => $data["name"],
							"modified_date" => $data["modified_date"]
						), array(
							"city_id" => $data["city_id"], 
						));
				}
			}
		}
	}
	
	public static function deleteCity($brokers, $city_id) {
		if (is_array($brokers) && is_numeric($city_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "CityService.deleteCity", array("city_id" => $city_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/zip", "delete_city", array("city_id" => $city_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$City = $broker->callObject("module/zip", "City");
					return $City->delete($city_id);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mz_city", array("city_id" => $city_id));
				}
			}
		}
	}
	
	public static function deleteCitiesByConditions($brokers, $conditions, $conditions_join) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "CityService.deleteCitiesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callDelete("module/zip", "delete_cities_by_conditions", array("conditions" => $cond));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$City = $broker->callObject("module/zip", "City");
					return $City->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $conditions_join));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mz_city", $conditions, array("conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	public static function deleteCitiesByStateId($brokers, $state_id) {
		return self::deleteCitiesByConditions($brokers, array("state_id" => $state_id), null);
	}
	
	public static function deleteCitiesByCountryId($brokers, $country_id) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "CityService.deleteCitiesByCountryId", array("country_id" => $country_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/zip", "delete_cities_by_country_id", array("country_id" => $country_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$City = $broker->callObject("module/zip", "City");
					return $City->callDelete("module/zip", "delete_cities_by_country_id", array("country_id" => $country_id));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$sql = CityDBDAOUtil::delete_cities_by_country_id(array("country_id" => $country_id));
					return $b->setSQL($sql);
				}
			}
		}
	}
	
	public static function getAllCities($brokers, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => $options);
					return $broker->callBusinessLogic("module/zip", "CityService.getAllCities", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/zip", "get_all_cities", null, $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$City = $broker->callObject("module/zip", "City");
					return $City->find(null, $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mz_city", null, null, $options);
				}
			}
		}
	}
	
	public static function countAllCities($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/zip", "CityService.countAllCities", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/zip", "count_all_cities", null, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$City = $broker->callObject("module/zip", "City");
					return $City->count(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mz_city", null, array("no_cache" => $no_cache));
				}
			}
		}
	}
	
	public static function getCitiesByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "CityService.getCitiesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callSelect("module/zip", "get_cities_by_conditions", array("conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$City = $broker->callObject("module/zip", "City");
					return $City->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options["conditions_join"] = $conditions_join;
					return $broker->findObjects("mz_city", null, $conditions, $options);
				}
			}
		}
	}
	
	public static function countCitiesByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "CityService.countCitiesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/zip", "count_cities_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$City = $broker->callObject("module/zip", "City");
					return $City->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options["conditions_join"] = $conditions_join;
					return $broker->countObjects("mz_city", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	/* STATE FUNCTIONS */
	
	public static function insertState($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["country_id"])) {
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
			$options = array();
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "StateService.insertState", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["name"] = addcslashes($data["name"], "\\'");
					
					if ($data["state_id"]) {
						$options = array("hard_coded_ai_pk" => true);
						$status = $broker->callInsert("module/zip", "insert_state_with_ai_pk", $data, $options);
						return $status ? $data["state_id"] : $status;
					}
					
					$status = $broker->callInsert("module/zip", "insert_state", $data);
					return $status ? $broker->getInsertedId($options) : $status;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					if (!$data["state_id"]) 
						unset($data["state_id"]);
					
					$State = $broker->callObject("module/zip", "State");
					$status = $State->insert($data, $ids);
					return $status ? $ids["state_id"] : $status;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$attributes = array(
						"country_id" => $data["country_id"], 
						"name" => $data["name"], 
						"created_date" => $data["created_date"], 
						"modified_date" => $data["modified_date"]
					);
					
					if ($data["state_id"]) {
						$options["hard_coded_ai_pk"] = true;
						$attributes["state_id"] = $data["state_id"];
					}
					
					$status = $broker->insertObject("mz_state", $attributes, $options);
					return $status ? ($data["state_id"] ? $data["state_id"] : $broker->getInsertedId($options)) : $status;
				}
			}
		}
	}
	
	public static function updateState($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["state_id"]) && is_numeric($data["country_id"])) {
			$data["modified_date"] = date("Y-m-d H:i:s");
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "StateService.updateState", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["name"] = addcslashes($data["name"], "\\'");
					
					return $broker->callUpdate("module/zip", "update_state", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$State = $broker->callObject("module/zip", "State");
					return $State->update($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->updateObject("mz_state", array(
							"country_id" => $data["country_id"], 
							"name" => $data["name"],
							"modified_date" => $data["modified_date"]
						), array(
							"state_id" => $data["state_id"]
						));
				}
			}
		}
	}
	
	public static function deleteState($brokers, $state_id) {
		if (is_array($brokers) && is_numeric($state_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "StateService.deleteState", array("state_id" => $state_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/zip", "delete_state", array("state_id" => $state_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$State = $broker->callObject("module/zip", "State");
					return $State->delete($state_id);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mz_state", array("state_id" => $state_id));
				}
			}
		}
	}
	
	public static function deleteStatesByConditions($brokers, $conditions, $conditions_join) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "StateService.deleteStatesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callDelete("module/zip", "delete_states_by_conditions", array("conditions" => $cond));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$State = $broker->callObject("module/zip", "State");
					return $State->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $conditions_join));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mz_state", $conditions, array("conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	public static function deleteStatesByCountryId($brokers, $country_id) {
		return self::deleteStatesByConditions($brokers, array("country_id" => $country_id), null);
	}
	
	public static function getAllStates($brokers, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => $options);
					return $broker->callBusinessLogic("module/zip", "StateService.getAllStates", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/zip", "get_all_states", null, $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$State = $broker->callObject("module/zip", "State");
					return $State->find(null, $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mz_state", null, null, $options);
				}
			}
		}
	}
	
	public static function countAllStates($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/zip", "StateService.countAllStates", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/zip", "count_all_states", null, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$State = $broker->callObject("module/zip", "State");
					return $State->count(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mz_state", null, array("no_cache" => $no_cache));
				}
			}
		}
	}
	
	public static function getStatesByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "StateService.getStatesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callSelect("module/zip", "get_states_by_conditions", array("conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$State = $broker->callObject("module/zip", "State");
					return $State->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options["conditions_join"] = $conditions_join;
					return $broker->findObjects("mz_state", null, $conditions, $options);
				}
			}
		}
	}
	
	public static function countStatesByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "StateService.countStatesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/zip", "count_states_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$State = $broker->callObject("module/zip", "State");
					return $State->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mz_state", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	/* COUNTRY FUNCTIONS */
	
	public static function insertCountry($brokers, $data) {
		if (is_array($brokers)) {
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["modified_date"] = $data["created_date"];
			$options = array();
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "CountryService.insertCountry", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["name"] = addcslashes($data["name"], "\\'");
					
					if ($data["country_id"]) {
						$options = array("hard_coded_ai_pk" => true);
						$status = $broker->callInsert("module/zip", "insert_country_with_ai_pk", $data, $options);
						return $status ? $data["country_id"] : $status;
					}
					
					$status = $broker->callInsert("module/zip", "insert_country", $data);
					return $status ? $broker->getInsertedId($options) : $status;
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					if (!$data["country_id"]) 
						unset($data["country_id"]);
					
					$Country = $broker->callObject("module/zip", "Country");
					$status = $Country->insert($data, $ids);
					return $status ? $ids["country_id"] : $status;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$attributes = array(
						"name" => $data["name"], 
						"created_date" => $data["created_date"], 
						"modified_date" => $data["modified_date"]
					);
					
					if ($data["country_id"]) {
						$options["hard_coded_ai_pk"] = true;
						$attributes["country_id"] = $data["country_id"];
					}
					
					$status = $broker->insertObject("mz_country", $attributes, $options);
					return $status ? ($data["country_id"] ? $data["country_id"] : $broker->getInsertedId($options)) : $status;
				}
			}
		}
	}
	
	public static function updateCountry($brokers, $data) {
		if (is_array($brokers) && is_numeric($data["country_id"])) {
			$data["modified_date"] = date("Y-m-d H:i:s");
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "CountryService.updateCountry", $data);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$data["name"] = addcslashes($data["name"], "\\'");
					
					return $broker->callUpdate("module/zip", "update_country", $data);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Country = $broker->callObject("module/zip", "Country");
					return $Country->update($data);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->updateObject("mz_country", array(
							"name" => $data["name"],
							"modified_date" => $data["modified_date"]
						), array(
							"country_id" => $data["country_id"], 
						));
				}
			}
		}
	}
	
	public static function deleteCountry($brokers, $country_id) {
		if (is_array($brokers) && is_numeric($country_id)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "CountryService.deleteCountry", array("country_id" => $country_id));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callDelete("module/zip", "delete_country", array("country_id" => $country_id));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Country = $broker->callObject("module/zip", "Country");
					return $Country->delete($country_id);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mz_country", array("country_id" => $country_id));
				}
			}
		}
	}
	
	public static function deleteCountriesByConditions($brokers, $conditions, $conditions_join) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "CountryService.deleteCountriesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callDelete("module/zip", "delete_countries_by_conditions", array("conditions" => $cond));
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Country = $broker->callObject("module/zip", "Country");
					return $Country->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $conditions_join));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->deleteObject("mz_country", $conditions, array("conditions_join" => $conditions_join));
				}
			}
		}
	}
	
	public static function getAllCountries($brokers, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => $options);
					return $broker->callBusinessLogic("module/zip", "CountryService.getAllCountries", $data, $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					return $broker->callSelect("module/zip", "get_all_countries", null, $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Country = $broker->callObject("module/zip", "Country");
					return $Country->find(null, $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->findObjects("mz_country", null, null, $options);
				}
			}
		}
	}
	
	public static function countAllCountries($brokers, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					$data = array("options" => array("no_cache" => $no_cache));
					return $broker->callBusinessLogic("module/zip", "CountryService.countAllCountries", $data, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$result = $broker->callSelect("module/zip", "count_all_countries", null, array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Country = $broker->callObject("module/zip", "Country");
					return $Country->count(null, array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mz_country", null, array("no_cache" => $no_cache));
				}
			}
		}
	}
	
	public static function getCountriesByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (is_array($brokers)) {
			$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "CountryService.getCountriesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					return $broker->callSelect("module/zip", "get_countries_by_conditions", array("conditions" => $cond), $options);
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Country = $broker->callObject("module/zip", "Country");
					return $Country->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$options["conditions_join"] = $conditions_join;
					return $broker->findObjects("mz_country", null, $conditions, $options);
				}
			}
		}
	}
	
	public static function countCountriesByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
		if (is_array($brokers)) {
			foreach ($brokers as $broker) {
				if (is_a($broker, "IBusinessLogicBrokerClient")) {
					return $broker->callBusinessLogic("module/zip", "CountryService.countCountriesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
					$cond = DB::getSQLConditions($conditions, $conditions_join);
					$cond = $cond ? $cond : "1=1";
					$result = $broker->callSelect("module/zip", "count_countries_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					return $result[0]["total"];
				}
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$Country = $broker->callObject("module/zip", "Country");
					return $Country->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					return $broker->countObjects("mz_country", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
				}
			}
		}
	}
}
?>
