<?php
namespace Module\User;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/UserServiceUtil.php";
include_once __DIR__ . "/UserDBDAOServiceUtil.php"; //this file will be automatically generated on this module installation

class UserService extends \soa\CommonService {
	private $User;
	
	private function getUserHbnObj($b, $options) {
		if (!$this->User)
			$this->User = $b->callObject("module/user", "User", $options);
		
		return $this->User;
	}
	
	/**
	 * @param (name=data[username], type=varchar, not_null=1, default="", length=50)
	 * @param (name=data[password], type=varchar, not_null=1, default="", length=255)
	 * @param (name=data[email], type=varchar, default="", length=100)
	 * @param (name=data[name], type=varchar, default="", length=50)
	 * @param (name=data[active], type=bool, default=0)
	 * @param (name=data[security_question_1], type=varchar, default="", length=255)
	 * @param (name=data[security_answer_1], type=varchar, default="", length=255)
	 * @param (name=data[security_question_2], type=varchar, default="", length=255)
	 * @param (name=data[security_answer_2], type=varchar, default="", length=255)
	 * @param (name=data[security_question_3], type=varchar, default="", length=255)
	 * @param (name=data[security_answer_3], type=varchar, default="", length=255)
	 */
	public function insertUser($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($data);
			
			if ($data["password"])
				$data["password"] = $data["do_not_encrypt_password"] ? $data["password"] : UserServiceUtil::getEncryptedPassword($data["password"]);
			
			$data["username"] = addcslashes($data["username"], "\\'");
			$data["password"] = addcslashes($data["password"], "\\'");
			$data["email"] = addcslashes($data["email"], "\\'");
			$data["name"] = addcslashes($data["name"], "\\'");
			$data["active"] = is_numeric($data["active"]) ? $data["active"] : 0;
			$data["security_question_1"] = addcslashes($data["security_question_1"], "\\'");
			$data["security_answer_1"] = addcslashes($data["security_answer_1"], "\\'");
			$data["security_question_2"] = addcslashes($data["security_question_2"], "\\'");
			$data["security_answer_2"] = addcslashes($data["security_answer_2"], "\\'");
			$data["security_question_3"] = addcslashes($data["security_question_3"], "\\'");
			$data["security_answer_3"] = addcslashes($data["security_answer_3"], "\\'");
			
			$status = $b->callInsert("module/user", "insert_user", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($data);
			
			if ($data["password"])
				$data["password"] = $data["do_not_encrypt_password"] ? $data["password"] : UserServiceUtil::getEncryptedPassword($data["password"]);
			
			if (isset($data["active"]))
				$data["active"] = is_numeric($data["active"]) ? $data["active"] : 0;
			
			$User = $this->getUserHbnObj($b, $options);
			$status = $User->insert($data, $ids);
			return $status ? $ids["user_id"] : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($data);
			
			if ($data["password"])
				$data["password"] = $data["do_not_encrypt_password"] ? $data["password"] : UserServiceUtil::getEncryptedPassword($data["password"]);
			
			if (isset($data["active"]))
				$data["active"] = is_numeric($data["active"]) ? $data["active"] : 0;
			
			$status = $b->insertObject("mu_user", array(
					"username" => $data["username"], 
					"password" => $data["password"], 
					"email" => $data["email"], 
					"name" => $data["name"], 
					"active" => $data["active"], 
					"security_question_1" => $data["security_question_1"], 
					"security_answer_1" => $data["security_answer_1"], 
					"security_question_2" => $data["security_question_2"], 
					"security_answer_2" => $data["security_answer_2"], 
					"security_question_3" => $data["security_question_3"], 
					"security_answer_3" => $data["security_answer_3"], 
					"created_date" => $data["created_date"], 
					"modified_date" => $data["modified_date"]
				), $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserService.insertUser", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[username], type=varchar, not_null=1, default="", length=50)
	 * @param (name=data[email], type=varchar, default="", length=100)
	 * @param (name=data[name], type=varchar, default="", length=50)
	 * @param (name=data[security_question_1], type=varchar, default="", length=255)
	 * @param (name=data[security_answer_1], type=varchar, default="", length=255)
	 * @param (name=data[security_question_2], type=varchar, default="", length=255)
	 * @param (name=data[security_answer_2], type=varchar, default="", length=255)
	 * @param (name=data[security_question_3], type=varchar, default="", length=255)
	 * @param (name=data[security_answer_3], type=varchar, default="", length=255)
	 */
	public function updateUser($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($data);
			
			$data["username"] = addcslashes($data["username"], "\\'");
			$data["email"] = addcslashes($data["email"], "\\'");
			$data["name"] = addcslashes($data["name"], "\\'");
			$data["security_question_1"] = addcslashes($data["security_question_1"], "\\'");
			$data["security_answer_1"] = addcslashes($data["security_answer_1"], "\\'");
			$data["security_question_2"] = addcslashes($data["security_question_2"], "\\'");
			$data["security_answer_2"] = addcslashes($data["security_answer_2"], "\\'");
			$data["security_question_3"] = addcslashes($data["security_question_3"], "\\'");
			$data["security_answer_3"] = addcslashes($data["security_answer_3"], "\\'");
			
			return $b->callUpdate("module/user", "update_user", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($data);
			
			$User = $this->getUserHbnObj($b, $options);
			return $User->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($data);
			
			return $b->updateObject("mu_user", array(
					"username" => $data["username"],
					"email" => $data["email"], 
					"name" => $data["name"], 
					"security_question_1" => $data["security_question_1"], 
					"security_answer_1" => $data["security_answer_1"], 
					"security_question_2" => $data["security_question_2"], 
					"security_answer_2" => $data["security_answer_2"], 
					"security_question_3" => $data["security_question_3"], 
					"security_answer_3" => $data["security_answer_3"], 
					"modified_date" => $data["modified_date"]
				), array(
					"user_id" => $data["user_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserService.updateUser", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[password], type=varchar, not_null=1, min_length=1, max_length=255)
	 */
	public function updateUserPassword($data) {
		$user_id = $data["user_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["password"] = $data["do_not_encrypt_password"] ? $data["password"] : UserServiceUtil::getEncryptedPassword($data["password"]);
			$data["password"] = addcslashes($data["password"], "\\'");
			
			return $b->callUpdate("module/user", "update_user_password", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$data["password"] = $data["do_not_encrypt_password"] ? $data["password"] : UserServiceUtil::getEncryptedPassword($data["password"]);
			
			$User = $this->getUserHbnObj($b, $options);
			return $User->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$data["password"] = $data["do_not_encrypt_password"] ? $data["password"] : UserServiceUtil::getEncryptedPassword($data["password"]);
			
			return $b->updateObject("mu_user", array(
					"password" => $data["password"],
					"modified_date" => $data["modified_date"]
				), array(
					"user_id" => $data["user_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserService.updateUserPassword", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 * @param (name=data[password], type=varchar, not_null=1, min_length=1, max_length=255)
	 */
	public function updateUserPasswordAndUsername($data) {
		$user_id = $data["user_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["password"] = $data["do_not_encrypt_password"] ? $data["password"] : UserServiceUtil::getEncryptedPassword($data["password"]);
			$data["password"] = addcslashes($data["password"], "\\'");
			
			return $b->callUpdate("module/user", "update_user_password_and_username", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$data["password"] = $data["do_not_encrypt_password"] ? $data["password"] : UserServiceUtil::getEncryptedPassword($data["password"]);
			
			$User = $this->getUserHbnObj($b, $options);
			return $User->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$data["password"] = $data["do_not_encrypt_password"] ? $data["password"] : UserServiceUtil::getEncryptedPassword($data["password"]);
			
			return $b->updateObject("mu_user", array(
					"username" => $data["username"],
					"password" => $data["password"],
					"modified_date" => $data["modified_date"]
				), array(
					"user_id" => $data["user_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserService.updateUserPasswordAndUsername", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[active], type=tinyint, not_null=1, length=1)
	 */
	public function updateUserActiveStatus($data) {
		$user_id = $data["user_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["active"] = is_numeric($data["active"]) ? $data["active"] : 0;
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callUpdate("module/user", "update_user_active_status", $data, $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$User = $this->getUserHbnObj($b, $options);
			return $User->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mu_user", array(
					"active" => $data["active"],
					"modified_date" => $data["modified_date"]
				), array(
					"user_id" => $data["user_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserService.updateUserActiveStatus", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 */
	public function updateNameOfUser($data) {
		$user_id = $data["user_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($data);
			
			$data["name"] = addcslashes($data["name"], "\\'");
			
			return $b->callUpdate("module/user", "update_name_of_user", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$User = $this->getUserHbnObj($b, $options);
			return $User->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mu_user", array(
					"name" => $data["name"],
					"modified_date" => $data["modified_date"]
				), array(
					"user_id" => $data["user_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserService.updateNameOfUser", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 * @param (name=data[name], type=varchar, not_null=1, min_length=1, max_length=50)
	 * @param (name=data[email], type=varchar, not_null=1, min_length=1, max_length=100)
	 */
	public function updateNameAndEmailOfUser($data) {
		$user_id = $data["user_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($data);
			
			$data["name"] = addcslashes($data["name"], "\\'");
			$data["email"] = addcslashes($data["email"], "\\'");
			
			return $b->callUpdate("module/user", "update_name_and_email_of_user", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$User = $this->getUserHbnObj($b, $options);
			return $User->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mu_user", array(
					"name" => $data["name"],
					"email" => $data["email"],
					"modified_date" => $data["modified_date"]
				), array(
					"user_id" => $data["user_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserService.updateNameAndEmailOfUser", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)  
	 */
	public function deleteUser($data) {
		$user_id = $data["user_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) 
			return $b->callDelete("module/user", "delete_user", array("user_id" => $user_id), $options);
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$User = $this->getUserHbnObj($b, $options);
			return $User->delete($user_id);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mu_user", array("user_id" => $user_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserService.deleteUser", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)  
	 */
	public function getUser($data) {
		$user_id = $data["user_id"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "get_user", array("user_id" => $user_id), $options);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$User = $this->getUserHbnObj($b, $options);
			$result = $User->findById($user_id);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUserData($result);
			return $result;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mu_user", null, array("user_id" => $user_id), $options);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result[0];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserService.getUser", $data, $options);
	}
	
	/**
	 * @param (name=data[environment_ids], type=mixed, not_null=1)
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][username], type=varchar|array, length=50)
	 * @param (name=data[conditions][password], type=varchar|array, length=255)
	 * @param (name=data[conditions][email], type=varchar|array, length=100)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 * @param (name=data[conditions][security_question_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_3], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_3], type=varchar|array, length=255)
	 */
	public function getUsersWithEnvironmentsAndConditions($data) {
		$environment_ids = $data["environment_ids"];
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		if ($environment_ids) {
			$environment_ids_str = "";//just in case the user tries to hack the sql query. By default all user_type_id should be numeric.
			$environment_ids = is_array($environment_ids) ? $environment_ids : array($environment_ids);
			foreach ($environment_ids as $environment_id) 
				if (is_numeric($environment_id)) 
					$environment_ids_str .= ($environment_ids_str ? ", " : "") . $environment_id;
			
			if ($environment_ids_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
					$cond = $cond ? $cond : "1=1";
					$result = $b->callSelect("module/user", "get_users_with_environments_and_conditions", array("environment_ids" => $environment_ids_str, "conditions" => $cond), $options);
					$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
					return $result;
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$User = $this->getUserHbnObj($b, $options);
					$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
					$cond = $cond ? $cond : "1=1";
					$result = $User->callSelect("get_users_with_environments_and_conditions", array("environment_ids" => $environment_ids_str, "conditions" => $cond), $options);
					$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
					return $result;
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
					$cond = $cond ? $cond : "1=1";
					$sql = UserDBDAOServiceUtil::get_users_with_environments_and_conditions(array("environment_ids" => $environment_ids_str, "conditions" => $cond));
					
					$result = $b->getSQL($sql, $options);
					$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
					return $result;
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/user", "UserService.getUsersWithEnvironmentsAndConditions", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][username], type=varchar|array, length=50)
	 * @param (name=data[conditions][password], type=varchar|array, length=255)
	 * @param (name=data[conditions][email], type=varchar|array, length=100)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 * @param (name=data[conditions][security_question_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_3], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_3], type=varchar|array, length=255)
	 */
	public function getUsersWithoutEnvironmentsAndWithConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
			$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
			$cond = $cond ? $cond : "1=1";
			$result = $b->callSelect("module/user", "get_users_without_environments_and_with_conditions", array("conditions" => $cond), $options);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
			$User = $this->getUserHbnObj($b, $options);
			$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
			$cond = $cond ? $cond : "1=1";
			$result = $User->callSelect("get_users_without_environments_and_with_conditions", array("conditions" => $cond), $options);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
			$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
			$cond = $cond ? $cond : "1=1";
			$sql = UserDBDAOServiceUtil::get_users_without_environments_and_with_conditions(array("conditions" => $cond));
			
			$result = $b->getSQL($sql, $options);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserService.getUsersWithoutEnvironmentsAndWithConditions", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][username], type=varchar|array, length=50)
	 * @param (name=data[conditions][password], type=varchar|array, length=255)
	 * @param (name=data[conditions][email], type=varchar|array, length=100)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 * @param (name=data[conditions][security_question_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_3], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_3], type=varchar|array, length=255)
	 */
	public function getUsersByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/user", "get_users_by_conditions", array("conditions" => $cond), $options);
				$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
				return $result;
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
				$User = $this->getUserHbnObj($b, $options);
				$result = $User->find($data, $options);
				$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
				return $result;
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				$result = $b->findObjects("mu_user", null, $conditions, $options);
				$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
				return $result;
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/user", "UserService.getUsersByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][username], type=varchar|array, length=50)
	 * @param (name=data[conditions][password], type=varchar|array, length=255)
	 * @param (name=data[conditions][email], type=varchar|array, length=100)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 * @param (name=data[conditions][security_question_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_3], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_3], type=varchar|array, length=255)
	 */
	public function countUsersByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
				$cond = \DB::getSQLConditions($conditions, $data["conditions_join"]);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/user", "count_users_by_conditions", array("conditions" => $cond), $options);
				return $result[0]["total"];
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
				$User = $this->getUserHbnObj($b, $options);
				return $User->count(array("conditions" => $conditions, "conditions_join" => $data["conditions_join"]), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				return $b->countObjects("mu_user", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient")) 
				return $b->callBusinessLogic("module/user", "UserService.countUsersByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][username], type=varchar|array, length=50)
	 * @param (name=data[conditions][password], type=varchar|array, length=255)
	 * @param (name=data[conditions][email], type=varchar|array, length=100)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 * @param (name=data[conditions][security_question_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_3], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_3], type=varchar|array, length=255)
	 */
	public function getUsersWithUserTypesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
			
			$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
			$cond = $cond ? $cond : "1=1";
			$result = $b->callSelect("module/user", "get_users_with_user_types_by_conditions", array("conditions" => $cond), $options);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
			
			$User = $this->getUserHbnObj($b, $options);
			$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
			$cond = $cond ? $cond : "1=1";
			$result = $User->callSelect("get_users_with_user_types_by_conditions", array("conditions" => $cond), $options);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
			$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
			$cond = $cond ? $cond : "1=1";
			$sql = UserDBDAOServiceUtil::get_users_with_user_types_by_conditions(array("conditions" => $cond));
			
			$result = $b->getSQL($sql, $options);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserService.getUsersWithUserTypesByConditions", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][username], type=varchar|array, length=50)
	 * @param (name=data[conditions][password], type=varchar|array, length=255)
	 * @param (name=data[conditions][email], type=varchar|array, length=100)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 * @param (name=data[conditions][security_question_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_3], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_3], type=varchar|array, length=255)
	 */
	public function countUsersWithUserTypesByConditions($data) {
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
				
			$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
			$cond = $cond ? $cond : "1=1";
			$result = $b->callSelect("module/user", "count_users_with_user_types_by_conditions", array("conditions" => $cond), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
				
			$User = $this->getUserHbnObj($b, $options);
			$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
			$cond = $cond ? $cond : "1=1";
			$result = $User->callSelect("count_users_with_user_types_by_conditions", array("conditions" => $cond), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
			$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
			$cond = $cond ? $cond : "1=1";
			$sql = UserDBDAOServiceUtil::count_users_with_user_types_by_conditions(array("conditions" => $cond));
			
			$result = $b->getSQL($sql, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserService.countUsersWithUserTypesByConditions", $data, $options);
	}
	
	/**
	 * @param (name=data[user_type_ids], type=mixed, not_null=1)
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][username], type=varchar|array, length=50)
	 * @param (name=data[conditions][password], type=varchar|array, length=255)
	 * @param (name=data[conditions][email], type=varchar|array, length=100)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 * @param (name=data[conditions][security_question_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_3], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_3], type=varchar|array, length=255)
	 */
	public function getUsersByUserTypesAndConditions($data) {
		$user_type_ids = $data["user_type_ids"];
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($user_type_ids) {
			$user_type_ids_str = "";//just in case the user tries to hack the sql query. By default all user_type_id should be numeric.
			$user_type_ids = is_array($user_type_ids) ? $user_type_ids : array($user_type_ids);
			foreach ($user_type_ids as $user_type_id) 
				if (is_numeric($user_type_id)) 
					$user_type_ids_str .= ($user_type_ids_str ? ", " : "") . $user_type_id;
			
			if ($user_type_ids_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
					$cond = $cond ? $cond : "1=1";
					$result = $b->callSelect("module/user", "get_users_by_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "conditions" => $cond), $options);
					$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
					return $result;
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$User = $this->getUserHbnObj($b, $options);
					$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
					$cond = $cond ? $cond : "1=1";
					$result = $User->callSelect("get_users_by_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "conditions" => $cond), $options);
					$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
					return $result;
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
					$cond = $cond ? $cond : "1=1";
					$sql = UserDBDAOServiceUtil::get_users_by_user_types_and_conditions(array("user_type_ids" => $user_type_ids_str, "conditions" => $cond));
					
					$result = $b->getSQL($sql, $options);
					$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
					return $result;
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/user", "UserService.getUsersByUserTypesAndConditions", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[user_type_ids], type=mixed, not_null=1)
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][username], type=varchar|array, length=50)
	 * @param (name=data[conditions][password], type=varchar|array, length=255)
	 * @param (name=data[conditions][email], type=varchar|array, length=100)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 * @param (name=data[conditions][security_question_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_3], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_3], type=varchar|array, length=255)
	 */
	public function countUsersByUserTypesAndConditions($data) {
		$user_type_ids = $data["user_type_ids"];
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($user_type_ids) {
			$user_type_ids_str = "";//just in case the user tries to hack the sql query. By default all user_type_id should be numeric.
			$user_type_ids = is_array($user_type_ids) ? $user_type_ids : array($user_type_ids);
			foreach ($user_type_ids as $user_type_id) 
				if (is_numeric($user_type_id)) 
					$user_type_ids_str .= ($user_type_ids_str ? ", " : "") . $user_type_id;
			
			if ($user_type_ids_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
					$cond = $cond ? $cond : "1=1";
					$result = $b->callSelect("module/user", "count_users_by_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "conditions" => $cond), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$User = $this->getUserHbnObj($b, $options);
					$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
					$cond = $cond ? $cond : "1=1";
					$result = $User->callSelect("count_users_by_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "conditions" => $cond), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
					$cond = $cond ? $cond : "1=1";
					$sql = UserDBDAOServiceUtil::count_users_by_user_types_and_conditions(array("user_type_ids" => $user_type_ids_str, "conditions" => $cond));
					
					$result = $b->getSQL($sql, $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IBusinessLogicBrokerClient"))
					return $b->callBusinessLogic("module/user", "UserService.countUsersByUserTypesAndConditions", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][username], type=varchar|array, length=50)
	 * @param (name=data[conditions][password], type=varchar|array, length=255)
	 * @param (name=data[conditions][email], type=varchar|array, length=100)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 * @param (name=data[conditions][security_question_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_3], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_3], type=varchar|array, length=255)
	 */
	public function getUsersByObjectAndConditions($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
			$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
			$cond = $cond ? $cond : "1=1";
			$result = $b->callSelect("module/user", "get_users_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
			$User = $this->getUserHbnObj($b, $options);
			$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
			$cond = $cond ? $cond : "1=1";
			$result = $User->callSelect("get_users_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
			$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
			$cond = $cond ? $cond : "1=1";
			$sql = UserDBDAOServiceUtil::get_users_by_object_and_conditions(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
			
			$result = $b->getSQL($sql, $options);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserService.getUsersByObjectAndConditions", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][username], type=varchar|array, length=50)
	 * @param (name=data[conditions][password], type=varchar|array, length=255)
	 * @param (name=data[conditions][email], type=varchar|array, length=100)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 * @param (name=data[conditions][security_question_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_3], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_3], type=varchar|array, length=255)
	 */
	public function countUsersByObjectAndConditions($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
			$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
			$cond = $cond ? $cond : "1=1";
			$result = $b->callSelect("module/user", "count_users_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
			$User = $this->getUserHbnObj($b, $options);
			$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
			$cond = $cond ? $cond : "1=1";
			$result = $User->callSelect("count_users_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
			$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
			$cond = $cond ? $cond : "1=1";
			$sql = UserDBDAOServiceUtil::count_users_by_object_and_conditions(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
			
			$result = $b->getSQL($sql, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserService.countUsersByObjectAndConditions", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][username], type=varchar|array, length=50)
	 * @param (name=data[conditions][password], type=varchar|array, length=255)
	 * @param (name=data[conditions][email], type=varchar|array, length=100)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 * @param (name=data[conditions][security_question_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_3], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_3], type=varchar|array, length=255)
	 */
	public function getUsersByObjectGroupAndConditions($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
			$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
			$cond = $cond ? $cond : "1=1";
			$result = $b->callSelect("module/user", "get_users_by_object_group_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
			$User = $this->getUserHbnObj($b, $options);
			$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
			$cond = $cond ? $cond : "1=1";
			$result = $User->callSelect("get_users_by_object_group_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
			$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
			$cond = $cond ? $cond : "1=1";
			$sql = UserDBDAOServiceUtil::get_users_by_object_group_and_conditions(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond));
			
			$result = $b->getSQL($sql, $options);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserService.getUsersByObjectGroupAndConditions", $data, $options);
	}
	
	/**
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19) 
	 * @param (name=data[group], type=bigint, default=0, length=19) 
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][username], type=varchar|array, length=50)
	 * @param (name=data[conditions][password], type=varchar|array, length=255)
	 * @param (name=data[conditions][email], type=varchar|array, length=100)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 * @param (name=data[conditions][security_question_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_3], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_3], type=varchar|array, length=255)
	 */
	public function countUsersByObjectGroupAndConditions($data) {
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
			$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
			$cond = $cond ? $cond : "1=1";
			$result = $b->callSelect("module/user", "count_users_by_object_group_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
			$User = $this->getUserHbnObj($b, $options);
			$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
			$cond = $cond ? $cond : "1=1";
			$result = $User->callSelect("count_users_by_object_group_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
			$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
			$cond = $cond ? $cond : "1=1";
			$sql = UserDBDAOServiceUtil::count_users_by_object_group_and_conditions(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond));
			
			$result = $b->getSQL($sql, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserService.countUsersByObjectGroupAndConditions", $data, $options);
	}
	
	/**
	 * @param (name=data[user_type_ids], type=mixed, not_null=1)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][username], type=varchar|array, length=50)
	 * @param (name=data[conditions][password], type=varchar|array, length=255)
	 * @param (name=data[conditions][email], type=varchar|array, length=100)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 * @param (name=data[conditions][security_question_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_3], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_3], type=varchar|array, length=255)
	 */
	public function getUsersByObjectAndUserTypesAndConditions($data) {
		$user_type_ids = $data["user_type_ids"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($user_type_ids) {
			$user_type_ids_str = "";//just in case the user tries to hack the sql query. By default all user_type_id should be numeric.
			$user_type_ids = is_array($user_type_ids) ? $user_type_ids : array($user_type_ids);
			foreach ($user_type_ids as $user_type_id) 
				if (is_numeric($user_type_id)) 
					$user_type_ids_str .= ($user_type_ids_str ? ", " : "") . $user_type_id;
			
			if ($user_type_ids_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
					$cond = $cond ? $cond : "1=1";
					$result = $b->callSelect("module/user", "get_users_by_object_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
					$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
					return $result;
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$User = $this->getUserHbnObj($b, $options);
					$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
					$cond = $cond ? $cond : "1=1";
					$result = $User->callSelect("get_users_by_object_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
					$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
					return $result;
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
					$cond = $cond ? $cond : "1=1";
					$sql = UserDBDAOServiceUtil::get_users_by_object_and_user_types_and_conditions(array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
					
					$result = $b->getSQL($sql, $options);
					$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
					return $result;
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/user", "UserService.getUsersByObjectAndUserTypesAndConditions", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[user_type_ids], type=mixed, not_null=1)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][username], type=varchar|array, length=50)
	 * @param (name=data[conditions][password], type=varchar|array, length=255)
	 * @param (name=data[conditions][email], type=varchar|array, length=100)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 * @param (name=data[conditions][security_question_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_3], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_3], type=varchar|array, length=255)
	 */
	public function countUsersByObjectAndUserTypesAndConditions($data) {
		$user_type_ids = $data["user_type_ids"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($user_type_ids) {
			$user_type_ids_str = "";//just in case the user tries to hack the sql query. By default all user_type_id should be numeric.
			$user_type_ids = is_array($user_type_ids) ? $user_type_ids : array($user_type_ids);
			foreach ($user_type_ids as $user_type_id)
				if (is_numeric($user_type_id)) 
					$user_type_ids_str .= ($user_type_ids_str ? ", " : "") . $user_type_id;
			
			if ($user_type_ids_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
					$cond = $cond ? $cond : "1=1";
					$result = $b->callSelect("module/user", "count_users_by_object_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$User = $this->getUserHbnObj($b, $options);
					$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
					$cond = $cond ? $cond : "1=1";
					$result = $User->callSelect("count_users_by_object_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
					$cond = $cond ? $cond : "1=1";
					$sql = UserDBDAOServiceUtil::count_users_by_object_and_user_types_and_conditions(array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
					
					$result = $b->getSQL($sql, $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/user", "UserService.countUsersByObjectAndUserTypesAndConditions", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[user_type_ids], type=mixed, not_null=1)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][username], type=varchar|array, length=50)
	 * @param (name=data[conditions][password], type=varchar|array, length=255)
	 * @param (name=data[conditions][email], type=varchar|array, length=100)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 * @param (name=data[conditions][security_question_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_3], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_3], type=varchar|array, length=255)
	 */
	public function getUsersByObjectGroupAndUserTypesAndConditions($data) {
		$user_type_ids = $data["user_type_ids"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($user_type_ids) {
			$user_type_ids_str = "";//just in case the user tries to hack the sql query. By default all user_type_id should be numeric.
			$user_type_ids = is_array($user_type_ids) ? $user_type_ids : array($user_type_ids);
			foreach ($user_type_ids as $user_type_id) 
				if (is_numeric($user_type_id)) 
					$user_type_ids_str .= ($user_type_ids_str ? ", " : "") . $user_type_id;
			
			if ($user_type_ids_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
					$cond = $cond ? $cond : "1=1";
					$result = $b->callSelect("module/user", "get_users_by_object_group_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
					$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
					return $result;
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$User = $this->getUserHbnObj($b, $options);
					$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
					$cond = $cond ? $cond : "1=1";
					$result = $User->callSelect("get_users_by_object_group_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
					$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
					return $result;
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
					$cond = $cond ? $cond : "1=1";
					$sql = UserDBDAOServiceUtil::get_users_by_object_group_and_user_types_and_conditions(array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond));
					
					$result = $b->getSQL($sql, $options);
					$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
					return $result;
				}
				else if (is_a($b, "IBusinessLogicBrokerClient")) 
					return $b->callBusinessLogic("module/user", "UserService.getUsersByObjectGroupAndUserTypesAndConditions", $data, $options);
			}
		}
	}
	
	/**
	 * @param (name=data[user_type_ids], type=mixed, not_null=1)
	 * @param (name=data[object_type_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[object_id], type=bigint, not_null=1, length=19)  
	 * @param (name=data[group], type=bigint, default=0, length=19)
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][username], type=varchar|array, length=50)
	 * @param (name=data[conditions][password], type=varchar|array, length=255)
	 * @param (name=data[conditions][email], type=varchar|array, length=100)
	 * @param (name=data[conditions][name], type=varchar|array, length=50)
	 * @param (name=data[conditions][security_question_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_1], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_2], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_question_3], type=varchar|array, length=255)
	 * @param (name=data[conditions][security_answer_3], type=varchar|array, length=255)
	 */
	public function countUsersByObjectGroupAndUserTypesAndConditions($data) {
		$user_type_ids = $data["user_type_ids"];
		$object_type_id = $data["object_type_id"];
		$object_id = $data["object_id"];
		$group = $data["group"];
		$conditions = $data["conditions"];
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($user_type_ids) {
			$user_type_ids_str = "";//just in case the user tries to hack the sql query. By default all user_type_id should be numeric.
			$user_type_ids = is_array($user_type_ids) ? $user_type_ids : array($user_type_ids);
			foreach ($user_type_ids as $user_type_id)
				if (is_numeric($user_type_id)) 
					$user_type_ids_str .= ($user_type_ids_str ? ", " : "") . $user_type_id;
			
			if ($user_type_ids_str) {
				$b = $this->getBroker($options);
				if (is_a($b, "IIbatisDataAccessBrokerClient")) {
					$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
					$cond = $cond ? $cond : "1=1";
					$result = $b->callSelect("module/user", "count_users_by_object_group_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$User = $this->getUserHbnObj($b, $options);
					$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
					$cond = $cond ? $cond : "1=1";
					$result = $User->callSelect("count_users_by_object_group_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IDBBrokerClient")) {
					$data["encode_user_data"] && UserServiceUtil::encodeSensitiveUserData($conditions);
					$cond = \DB::getSQLConditions($conditions, $data["conditions_join"], "u");
					$cond = $cond ? $cond : "1=1";
					$sql = UserDBDAOServiceUtil::count_users_by_object_group_and_user_types_and_conditions(array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond));
					
					$result = $b->getSQL($sql, $options);
					return $result[0]["total"];
				}
				else if (is_a($b, "IBusinessLogicBrokerClient"))
					return $b->callBusinessLogic("module/user", "UserService.countUsersByObjectGroupAndUserTypesAndConditions", $data, $options);
			}
		}
	}
	
	public function getAllUsers($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "get_all_users", null, $options);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$User = $this->getUserHbnObj($b, $options);
			$result = $User->find();
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mu_user", null, null, $options);
			$data["decode_user_data"] && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserService.getAllUsers", $data, $options);
	}
	
	public function countAllUsers($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "count_all_users", null, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$User = $this->getUserHbnObj($b, $options);
			return $User->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mu_user", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserService.countAllUsers", $data, $options);
	}
}
?>
