<?php
namespace Module\User;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/UserServiceUtil.php";

class ExternalUserService extends \soa\CommonService {
	private $ExternalUser;
	
	private function getExternalUserHbnObj($b, $options) {
		if (!$this->ExternalUser)
			$this->ExternalUser = $b->callObject("module/user", "ExternalUser", $options);
		
		return $this->ExternalUser;
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, length=19)
	 * @param (name=data[external_type_id], type=tinyint, not_null=1, default=0, length=2)
	 * @param (name=data[social_network_type], type=varchar, not_null=1, default="", length=255)
	 * @param (name=data[social_network_user_id], type=varchar, not_null=1, default="", length=255)
	 * @param (name=data[token_1], type=text, default="")
	 * @param (name=data[token_2], type=text, default="")
	 * @param (name=data[token_3], type=text, default="")
	 * @param (name=data[data], type=text, default="")
	 */
	public function insertExternalUser($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($data, array("data"));
			
			if (!is_numeric($data["user_id"]))
				$data["user_id"] = "NULL";
			
			if (!is_numeric($data["external_type_id"]))
				$data["external_type_id"] = 0;
			
			$data["social_network_type"] = addcslashes($data["social_network_type"], "\\'");
			$data["social_network_user_id"] = addcslashes($data["social_network_user_id"], "\\'");
			$data["token_1"] = addcslashes($data["token_1"], "\\'");
			$data["token_2"] = addcslashes($data["token_2"], "\\'");
			$data["token_3"] = addcslashes($data["token_3"], "\\'");
			$data["data"] = addcslashes($data["data"], "\\'");
			
			$status = $b->callInsert("module/user", "insert_external_user", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($data, array("data"));
			
			if (!is_numeric($data["user_id"]))
				unset($data["user_id"]);
			
			if (!is_numeric($data["external_type_id"]))
				$data["external_type_id"] = 0;
			
			$ExternalUser = $this->getExternalUserHbnObj($b, $options);
			$status = $ExternalUser->insert($data, $ids);
			return $status ? $ids["external_user_id"] : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($data, array("data"));
			
			if (!is_numeric($data["user_id"]))
				unset($data["user_id"]);
			
			if (!is_numeric($data["external_type_id"]))
				$data["external_type_id"] = 0;
			
			$status = $b->insertObject("mu_external_user", array(
				"user_id" => $data["user_id"], 
				"external_type_id" => $data["external_type_id"], 
				"social_network_type" => $data["social_network_type"], 
				"social_network_user_id" => $data["social_network_user_id"], 
				"token_1" => $data["token_1"], 
				"token_2" => $data["token_2"], 
				"token_3" => $data["token_3"], 
				"data" => $data["data"], 
				"created_date" => $data["created_date"], 
				"modified_date" => $data["modified_date"]
			), $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "ExternalUserService.insertExternalUser", $data, $options);
	}
	
	/**
	 * @param (name=data[external_user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[user_id], type=bigint, length=19)
	 * @param (name=data[external_type_id], type=tinyint, not_null=1, default=0, length=2)
	 * @param (name=data[social_network_type], type=varchar, not_null=1, default="", length=255)
	 * @param (name=data[social_network_user_id], type=varchar, not_null=1, default="", length=255)
	 * @param (name=data[token_1], type=text, default="")
	 * @param (name=data[token_2], type=text, default="")
	 * @param (name=data[token_3], type=text, default="")
	 * @param (name=data[data], type=text, default="")
	 */
	public function updateExternalUser($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($data, array("data"));
			
			if (!is_numeric($data["user_id"]))
				$data["user_id"] = "NULL";
			
			if (!is_numeric($data["external_type_id"]))
				$data["external_type_id"] = 0;
			
			$data["social_network_type"] = addcslashes($data["social_network_type"], "\\'");
			$data["social_network_user_id"] = addcslashes($data["social_network_user_id"], "\\'");
			$data["token_1"] = addcslashes($data["token_1"], "\\'");
			$data["token_2"] = addcslashes($data["token_2"], "\\'");
			$data["token_3"] = addcslashes($data["token_3"], "\\'");
			$data["data"] = addcslashes($data["data"], "\\'");
			
			return $b->callUpdate("module/user", "update_external_user", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($data, array("data"));
			
			if (!is_numeric($data["user_id"]))
				$data["user_id"] = "NULL";
			
			if (!is_numeric($data["external_type_id"]))
				$data["external_type_id"] = 0;
			
			$ExternalUser = $this->getExternalUserHbnObj($b, $options);
			return $ExternalUser->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($data, array("data"));
			
			if (!is_numeric($data["user_id"]))
				$data["user_id"] = "NULL";
			
			if (!is_numeric($data["external_type_id"]))
				$data["external_type_id"] = 0;
			
			return $b->updateObject("mu_external_user", array(
					"user_id" => $data["user_id"], 
					"external_type_id" => $data["external_type_id"], 
					"social_network_type" => $data["social_network_type"], 
					"social_network_user_id" => $data["social_network_user_id"], 
					"token_1" => $data["token_1"], 
					"token_2" => $data["token_2"], 
					"token_3" => $data["token_3"], 
					"data" => $data["data"], 
					"modified_date" => $data["modified_date"]
				), array(
					"external_user_id" => $data["external_user_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "ExternalUserService.updateExternalUser", $data, $options);
	}
	
	/**
	 * @param (name=data[external_user_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteExternalUser($data) {
		$external_user_id = $data["external_user_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/user", "delete_external_user", array("external_user_id" => $external_user_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ExternalUser = $this->getExternalUserHbnObj($b, $options);
			return $ExternalUser->delete($external_user_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mu_external_user", array("external_user_id" => $external_user_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "ExternalUserService.deleteExternalUser", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][external_user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][external_type_id], type=tinyint|array, length=2)
	 * @param (name=data[conditions][social_network_type], type=varchar|array, length=255)
	 * @param (name=data[conditions][social_network_user_id], type=varchar|array, length=255)
	 */
	public function deleteExternalUsersByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions, array("data"));
					
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				return $b->callDelete("module/user", "delete_external_users_by_conditions", array("conditions" => $cond), $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions, array("data"));
				
				$ExternalUser = $this->getExternalUserHbnObj($b, $options);
				return $ExternalUser->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]));
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions, array("data"));
				
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->deleteObject("mu_external_user", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/user", "ExternalUserService.deleteExternalUsersByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[external_user_id], type=bigint, not_null=1, length=19)  
	 */
	public function getExternalUser($data) {
		$external_user_id = $data["external_user_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "get_external_user", array("external_user_id" => $external_user_id), $options);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result, array("data"));
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ExternalUser = $this->getExternalUserHbnObj($b, $options);
			$result = $ExternalUser->findById($external_user_id);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUserData($result, array("data"));
			return $result;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mu_external_user", null, array("activity_id" => $activity_id), $options);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result, array("data"));
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "ExternalUserService.getExternalUser", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][external_user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][external_type_id], type=tinyint|array, length=2)
	 * @param (name=data[conditions][social_network_type], type=varchar|array, length=255)
	 * @param (name=data[conditions][social_network_user_id], type=varchar|array, length=255)
	 */
	public function getExternalUsersByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions, array("data"));
					
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/user", "get_external_users_by_conditions", array("conditions" => $cond), $options);
				$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result, array("data"));
				return $result;
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions, array("data"));
					
				$User = $this->getUserHbnObj($b, $options);
				$result = $User->find($data, $options);
				$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result, array("data"));
				return $result;
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions, array("data"));
					
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				$result = $b->findObjects("mu_external_user", null, $conditions, $options);
				$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result, array("data"));
				return $result;
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/user", "ExternalUserService.getExternalUsersByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][external_user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][external_type_id], type=tinyint|array, length=2)
	 * @param (name=data[conditions][social_network_type], type=varchar|array, length=255)
	 * @param (name=data[conditions][social_network_user_id], type=varchar|array, length=255)
	 */
	public function countExternalUsersByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions, array("data"));
				
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/user", "count_external_users_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions, array("data"));
					
				$ExternalUser = $this->getExternalUserHbnObj($b, $options);
				return $ExternalUser->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions, array("data"));
					
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mu_external_user", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/user", "ExternalUserService.countExternalUsersByConditions", $data, $options);
		}
	}
	
	public function getAllExternalUsers($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "get_all_external_users", null, $options);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result, array("data"));
			return $result;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ExternalUser = $this->getExternalUserHbnObj($b, $options);
			$result = $ExternalUser->find();
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result, array("data"));
			return $result;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mu_external_user", null, null, $options);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result, array("data"));
			return $result;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "ExternalUserService.getAllExternalUsers", $data, $options);
	}
	
	public function countAllExternalUsers($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "count_all_external_users", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$ExternalUser = $this->getExternalUserHbnObj($b, $options);
			return $ExternalUser->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mu_external_user", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "ExternalUserService.countAllExternalUsers", $data, $options);
	}
}
?>
