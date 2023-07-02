<?php
namespace Module\Zip;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/ZipDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class ZipService extends \soa\CommonService {
	private $Zip;
	
	private function getZipHbnObj($b, $options) {
		if (!$this->Zip)
			$this->Zip = $b->callObject("module/zip", "Zip", $options);
		
		return $this->Zip;
	}
	
	/**
	 * @param (name=data[zip_id], type=varchar, not_null=1, length=15)
	 * @param (name=data[country_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[zone_id], type=bigint, not_null=1, length=19)
	 */
	public function insertZip($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$status = $b->callInsert("module/zip", "insert_zip", $data, $options);
			return $status ? $data["zip_id"] : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Zip = $this->getZipHbnObj($b, $options);
			$status = $Zip->insert($data, $ids);
			return $status ? $data["zip_id"] : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$status = $b->insertObject("mz_zip", array(
				"zip_id" => $data["zip_id"], 
				"country_id" => $data["country_id"], 
				"zone_id" => $data["zone_id"], 
				"created_date" => $data["created_date"], 
				"modified_date" => $data["modified_date"]
			), $options);
			return $status ? $data["zip_id"] : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "ZipService.insertZip", $data, $options);
	}
	
	/**
	 * @param (name=data[zip_id], type=varchar, not_null=1, length=15)
	 * @param (name=data[country_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[zone_id], type=bigint, not_null=1, length=19)
	 */
	public function updateZip($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/zip", "update_zip", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Zip = $this->getZipHbnObj($b, $options);
			return $Zip->updatePrimaryKeys($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mz_zip", array(
					"zone_id" => $data["zone_id"], 
					"modified_date" => $data["modified_date"]
				), array(
					"zip_id" => $data["zip_id"], 
					"country_id" => $data["country_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "ZipService.updateZip", $data, $options);
	}
	
	/**
	 * @param (name=data[zip_id], type=varchar, not_null=1, length=15)  
	 * @param (name=data[country_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteZip($data) {
		$zip_id = $data["zip_id"];
		$country_id = $data["country_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/zip", "delete_zip", array("zip_id" => $zip_id, "country_id" => $country_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Zip = $this->getZipHbnObj($b, $options);
			return $Zip->delete(array("zip_id" => $zip_id, "country_id" => $country_id));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mz_zip", array("zip_id" => $zip_id, "country_id" => $country_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/zip", "ZipService.deleteZip", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][zip_id], type=varchar|array, length=15)
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][zone_id], type=bigint|array, length=19)
	 */
	public function deleteZipsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callDelete("module/zip", "delete_zips_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Zip = $this->getZipHbnObj($b, $options);
				return $Zip->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->deleteObject("mz_zip", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/zip", "ZipService.deleteZipsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[city_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteZipsByCityId($data) {
		$city_id = $data["city_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/zip", "delete_zips_by_city_id", array("city_id" => $city_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Zip = $this->getZipHbnObj($b, $options);
			return $Zip->callDelete("delete_zips_by_city_id", array("city_id" => $city_id), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = ZipDBDAOServiceUtil::delete_zips_by_city_id(array("city_id" => $city_id));
			return $b->setSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "ZipService.deleteZipsByCityId", $data, $options);
	}
	
	/**
	 * @param (name=data[state_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteZipsByStateId($data) {
		$state_id = $data["state_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient"))
			return $b->callDelete("module/zip", "delete_zips_by_state", array("state_id" => $state_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Zip = $this->getZipHbnObj($b, $options);
			return $Zip->callDelete("delete_zips_by_state", array("state_id" => $state_id), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = ZipDBDAOServiceUtil::delete_zips_by_state(array("state_id" => $state_id));
			return $b->setSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "ZipService.deleteZipsByStateId", $data, $options);
	}
	
	/**
	 * @param (name=data[country_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteZipsByCountryId($data) {
		$country_id = $data["country_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/zip", "delete_zips_by_country", array("country_id" => $country_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Zip = $this->getZipHbnObj($b, $options);
			return $Zip->callDelete("delete_zips_by_country", array("country_id" => $country_id), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = ZipDBDAOServiceUtil::delete_zips_by_country(array("country_id" => $country_id));
			return $b->setSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "ZipService.deleteZipsByCountryId", $data, $options);
	}
	
	/**
	 * @param (name=data[zip_id], type=varchar, not_null=1, length=15)  
	 * @param (name=data[country_id], type=bigint, not_null=1, length=19)  
	 */
	public function getZip($data) {
		$zip_id = $data["zip_id"];
		$country_id = $data["country_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/zip", "get_zip", array("zip_id" => $zip_id, "country_id" => $country_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Zip = $this->getZipHbnObj($b, $options);
			$result = $Zip->callSelect("get_zip", array("zip_id" => $zip_id, "country_id" => $country_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mz_zip", null, array("zip_id" => $zip_id, "country_id" => $country_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "ZipService.getZip", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][zip_id], type=varchar|array, length=15)
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][zone_id], type=bigint|array, length=19)
	 */
	public function getZipsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/zip", "get_zips_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Zip = $this->getZipHbnObj($b, $options);
				return $Zip->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mz_zip", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/zip", "ZipService.getZipsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][zip_id], type=varchar|array, length=15)
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][zone_id], type=bigint|array, length=19)
	 */
	public function countZipsByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/zip", "count_zips_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Zip = $this->getZipHbnObj($b, $options);
				return $Zip->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mz_zip", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/zip", "ZipService.countZipsByConditions", $data, $options);
		}
	}
	
	public function getAllZips($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/zip", "get_all_zips", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Zip = $this->getZipHbnObj($b, $options);
			return $Zip->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mz_zip", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/zip", "ZipService.getAllZips", null, $options);
	}
	
	public function countAllZips($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/zip", "count_all_zips", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Zip = $this->getZipHbnObj($b, $options);
			return $Zip->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mz_zip", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/zip", "ZipService.countAllZips", null, $options);
	}
}
?>
