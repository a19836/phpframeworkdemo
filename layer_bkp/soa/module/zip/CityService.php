<?php
namespace Module\Zip;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/CityDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class CityService extends \soa\CommonService {
	private $City;
	
	private function getCityHbnObj($b, $options) {
		if (!$this->City)
			$this->City = $b->callObject("module/zip", "City", $options);
		
		return $this->City;
	}
	
	/**
	 * @param (name=data[city_id], type=bigint, length=19)
	 * @param (name=data[state_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 */
	public function insertCity($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["name"] = addcslashes($data["name"], "\\'");
			
			if ($data["city_id"]) {
				$options["hard_coded_ai_pk"] = true;
				$status = $b->callInsert("module/zip", "insert_city_with_ai_pk", $data, $options);
				return $status ? $data["city_id"] : $status;
			}
			
			$status = $b->callInsert("module/zip", "insert_city", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			if (!$data["city_id"]) 
				unset($data["city_id"]);
			
			$City = $this->getCityHbnObj($b, $options);
			$status = $City->insert($data, $ids);
			return $status ? $ids["city_id"] : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
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
			
			$status = $b->insertObject("mz_city", $attributes, $options);
			return $status ? ($data["city_id"] ? $data["city_id"] : $b->getInsertedId($options)) : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/zip", "CityService.insertCity", $data, $options);
	}
	
	/**
	 * @param (name=data[city_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[state_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 */
	public function updateCity($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["name"] = addcslashes($data["name"], "\\'");
			
			return $b->callUpdate("module/zip", "update_city", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$City = $this->getCityHbnObj($b, $options);
			return $City->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mz_city", array(
					"state_id" => $data["state_id"], 
					"name" => $data["name"],
					"modified_date" => $data["modified_date"]
				), array(
					"city_id" => $data["city_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "CityService.updateCity", $data, $options);
	}
	
	/**
	 * @param (name=data[city_id], type=bigint, not_null=1, length=19) 
	 */
	public function deleteCity($data) {
		$city_id = $data["city_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/zip", "delete_city", array("city_id" => $city_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$City = $this->getCityHbnObj($b, $options);
			return $City->delete($city_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mz_city", array("city_id" => $city_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "CityService.deleteCity", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][city_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][state_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function deleteCitiesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callDelete("module/zip", "delete_cities_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$City = $this->getCityHbnObj($b, $options);
				return $City->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->deleteObject("mz_city", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/zip", "CityService.deleteCitiesByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[country_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteCitiesByCountryId($data) {
		$country_id = $data["country_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/zip", "delete_cities_by_country_id", array("country_id" => $country_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$City = $this->getCityHbnObj($b, $options);
			return $City->callDelete("delete_cities_by_country_id", array("country_id" => $country_id), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = CityDBDAOServiceUtil::delete_cities_by_country_id(array("country_id" => $country_id));
			return $b->setSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "CityService.deleteCitiesByCountryId", $data, $options);
	}
	
	/**
	 * @param (name=data[city_id], type=bigint, not_null=1, length=19)  
	 */
	public function getCity($data) {
		$city_id = $data["city_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/zip", "get_city", array("city_id" => $city_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$City = $this->getCityHbnObj($b, $options);
			return $City->callSelect("get_city", array("city_id" => $city_id), $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mz_city", null, array("city_id" => $city_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "CityService.getCity", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][city_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][state_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function getCitiesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/zip", "get_cities_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$City = $this->getCityHbnObj($b, $options);
				return $City->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mz_city", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/zip", "CityService.getCitiesByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][city_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][state_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function countCitiesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/zip", "count_cities_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$City = $this->getCityHbnObj($b, $options);
				return $City->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mz_city", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/zip", "CityService.countCitiesByConditions", $data, $options);
		}
	}
	
	public function getAllCities($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/zip", "get_all_cities", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$City = $this->getCityHbnObj($b, $options);
			return $City->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mz_city", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/zip", "CityService.getAllCities", null, $options);
	}
	
	public function countAllCities($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/zip", "count_all_cities", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$City = $this->getCityHbnObj($b, $options);
			return $City->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mz_city", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "CityService.countAllCities", null, $options);
	}
	
	public function getFullCities($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/zip", "get_full_cities", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$City = $this->getCityHbnObj($b, $options);
			return $City->callSelect("get_full_cities", null, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$sql = CityDBDAOServiceUtil::get_full_cities();
			return $b->getSQL($sql, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "CityService.getFullCities", null, $options);
	}
}
?>
