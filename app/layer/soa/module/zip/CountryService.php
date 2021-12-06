<?php
namespace Module\Zip;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");

class CountryService extends \soa\CommonService {
	private $Country;
	
	private function getCountryHbnObj($b, $options) {
		if (!$this->Country)
			$this->Country = $b->callObject("module/zip", "Country", $options);
		
		return $this->Country;
	}
	
	/**
	 * @param (name=data[country_id], type=bigint, length=19)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 */
	public function insertCountry($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["name"] = addcslashes($data["name"], "\\'");
			
			if ($data["country_id"]) {
				$options["hard_coded_ai_pk"] = true;
				$status = $b->callInsert("module/zip", "insert_country_with_ai_pk", $data, $options);
				return $status ? $data["country_id"] : $status;
			}
			
			$status = $b->callInsert("module/zip", "insert_country", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			if (!$data["country_id"]) 
				unset($data["country_id"]);
			
			$Country = $this->getCountryHbnObj($b, $options);
			$status = $Country->insert($data, $ids);
			return $status ? $ids["country_id"] : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$attributes = array(
				"name" => $data["name"],
				"created_date" => $data["created_date"], 
				"modified_date" => $data["modified_date"]
			);
			
			if ($data["country_id"]) {
				$options["hard_coded_ai_pk"] = true;
				$attributes["country_id"] = $data["country_id"];
			}
			
			$status = $b->insertObject("mz_country", $attributes, $options);
			return $status ? ($data["country_id"] ? $data["country_id"] : $b->getInsertedId($options)) : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "CountryService.insertCountry", $data, $options);
	}
	
	/**
	 * @param (name=data[country_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 */
	public function updateCountry($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["name"] = addcslashes($data["name"], "\\'");
			
			return $b->callUpdate("module/zip", "update_country", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Country = $this->getCountryHbnObj($b, $options);
			return $Country->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mz_country", array(
					"name" => $data["name"],
					"modified_date" => $data["modified_date"]
				), array(
					"country_id" => $data["country_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "CountryService.updateCountry", $data, $options);
	}
	
	/**
	 * @param (name=data[country_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteCountry($data) {
		$country_id = $data["country_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/zip", "delete_country", array("country_id" => $country_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Country = $this->getCountryHbnObj($b, $options);
			return $Country->delete($country_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mz_country", array("country_id" => $country_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/zip", "CountryService.deleteCountry", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function deleteCountriesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callDelete("module/zip", "delete_countries_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Country = $this->getCountryHbnObj($b, $options);
				return $Country->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->deleteObject("mz_country", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/zip", "CountryService.deleteCountriesByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[country_id], type=bigint, not_null=1, length=19)  
	 */
	public function getCountry($data) {
		$country_id = $data["country_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/zip", "get_country", array("country_id" => $country_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Country = $this->getCountryHbnObj($b, $options);
			return $Country->findById($country_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mz_country", null, array("country_id" => $country_id), $options);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "CountryService.getCountry", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function getCountriesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callSelect("module/zip", "get_countries_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Country = $this->getCountryHbnObj($b, $options);
				return $Country->find($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->findObjects("mz_country", null, $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/zip", "CountryService.getCountriesByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][country_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 */
	public function countCountriesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/zip", "count_countries_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$Country = $this->getCountryHbnObj($b, $options);
				return $Country->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mz_country", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/zip", "CountryService.countCountriesByConditions", $data, $options);
		}
	}
	
	public function getAllCountries($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callSelect("module/zip", "get_all_countries", null, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Country = $this->getCountryHbnObj($b, $options);
			return $Country->find();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->findObjects("mz_country", null, null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/zip", "CountryService.getAllCountries", null, $options);
	}
	
	public function countAllCountries($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/zip", "count_all_countries", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$Country = $this->getCountryHbnObj($b, $options);
			return $Country->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mz_country", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/zip", "CountryService.countAllCountries", null, $options);
	}
}
?>
