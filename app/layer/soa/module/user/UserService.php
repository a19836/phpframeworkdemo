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
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($data);
			
			if ($data["password"])
				$data["password"] = !empty($data["do_not_encrypt_password"]) ? $data["password"] : UserServiceUtil::getEncryptedPassword($data["password"]);
			
			$data["username"] = addcslashes($data["username"], "\\'");
			$data["password"] = addcslashes($data["password"], "\\'");
			$data["email"] = isset($data["email"]) ? addcslashes($data["email"], "\\'") : "";
			$data["name"] = isset($data["name"]) ? addcslashes($data["name"], "\\'") : "";
			$data["active"] = isset($data["active"]) && is_numeric($data["active"]) ? $data["active"] : 0;
			$data["security_question_1"] = isset($data["security_question_1"]) ? addcslashes($data["security_question_1"], "\\'") : "";
			$data["security_answer_1"] = isset($data["security_answer_1"]) ? addcslashes($data["security_answer_1"], "\\'") : "";
			$data["security_question_2"] = isset($data["security_question_2"]) ? addcslashes($data["security_question_2"], "\\'") : "";
			$data["security_answer_2"] = isset($data["security_answer_2"]) ? addcslashes($data["security_answer_2"], "\\'") : "";
			$data["security_question_3"] = isset($data["security_question_3"]) ? addcslashes($data["security_question_3"], "\\'") : "";
			$data["security_answer_3"] = isset($data["security_answer_3"]) ? addcslashes($data["security_answer_3"], "\\'") : "";
			
			$status = $b->callInsert("module/user", "insert_user", $data, $options);
			return $status ? $b->getInsertedId($options) : $status;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($data);
			
			if ($data["password"])
				$data["password"] = !empty($data["do_not_encrypt_password"]) ? $data["password"] : UserServiceUtil::getEncryptedPassword($data["password"]);
			
			if (isset($data["active"]))
				$data["active"] = is_numeric($data["active"]) ? $data["active"] : 0;
			
			$User = $this->getUserHbnObj($b, $options);
			$ids = null;
			$status = $User->insert($data, $ids);
			return $status ? (isset($ids["user_id"]) ? $ids["user_id"] : null) : $status;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($data);
			
			if ($data["password"])
				$data["password"] = !empty($data["do_not_encrypt_password"]) ? $data["password"] : UserServiceUtil::getEncryptedPassword($data["password"]);
			
			if (isset($data["active"]))
				$data["active"] = is_numeric($data["active"]) ? $data["active"] : 0;
			
			$status = $b->insertObject("mu_user", array(
					"username" => $data["username"], 
					"password" => $data["password"], 
					"email" => isset($data["email"]) ? $data["email"] : null, 
					"name" => isset($data["name"]) ? $data["name"] : null, 
					"active" => isset($data["active"]) ? $data["active"] : null, 
					"security_question_1" => isset($data["security_question_1"]) ? $data["security_question_1"] : null, 
					"security_answer_1" => isset($data["security_answer_1"]) ? $data["security_answer_1"] : null, 
					"security_question_2" => isset($data["security_question_2"]) ? $data["security_question_2"] : null, 
					"security_answer_2" => isset($data["security_answer_2"]) ? $data["security_answer_2"] : null, 
					"security_question_3" => isset($data["security_question_3"]) ? $data["security_question_3"] : null, 
					"security_answer_3" => isset($data["security_answer_3"]) ? $data["security_answer_3"] : null, 
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
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($data);
			
			$data["username"] = addcslashes($data["username"], "\\'");
			$data["email"] = isset($data["email"]) ? addcslashes($data["email"], "\\'") : "";
			$data["name"] = isset($data["name"]) ? addcslashes($data["name"], "\\'") : "";
			$data["security_question_1"] = isset($data["security_question_1"]) ? addcslashes($data["security_question_1"], "\\'") : "";
			$data["security_answer_1"] = isset($data["security_answer_1"]) ? addcslashes($data["security_answer_1"], "\\'") : "";
			$data["security_question_2"] = isset($data["security_question_2"]) ? addcslashes($data["security_question_2"], "\\'") : "";
			$data["security_answer_2"] = isset($data["security_answer_2"]) ? addcslashes($data["security_answer_2"], "\\'") : "";
			$data["security_question_3"] = isset($data["security_question_3"]) ? addcslashes($data["security_question_3"], "\\'") : "";
			$data["security_answer_3"] = isset($data["security_answer_3"]) ? addcslashes($data["security_answer_3"], "\\'") : "";
			
			return $b->callUpdate("module/user", "update_user", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($data);
			
			$User = $this->getUserHbnObj($b, $options);
			return $User->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($data);
			
			return $b->updateObject("mu_user", array(
					"username" => $data["username"],
					"email" => isset($data["email"]) ? $data["email"] : null, 
					"name" => isset($data["name"]) ? $data["name"] : null, 
					"security_question_1" => isset($data["security_question_1"]) ? $data["security_question_1"] : null, 
					"security_answer_1" => isset($data["security_answer_1"]) ? $data["security_answer_1"] : null, 
					"security_question_2" => isset($data["security_question_2"]) ? $data["security_question_2"] : null, 
					"security_answer_2" => isset($data["security_answer_2"]) ? $data["security_answer_2"] : null, 
					"security_question_3" => isset($data["security_question_3"]) ? $data["security_question_3"] : null, 
					"security_answer_3" => isset($data["security_answer_3"]) ? $data["security_answer_3"] : null, 
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
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["password"] = !empty($data["do_not_encrypt_password"]) ? $data["password"] : UserServiceUtil::getEncryptedPassword($data["password"]);
			$data["password"] = addcslashes($data["password"], "\\'");
			
			return $b->callUpdate("module/user", "update_user_password", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$data["password"] = !empty($data["do_not_encrypt_password"]) ? $data["password"] : UserServiceUtil::getEncryptedPassword($data["password"]);
			
			$User = $this->getUserHbnObj($b, $options);
			return $User->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$data["password"] = !empty($data["do_not_encrypt_password"]) ? $data["password"] : UserServiceUtil::getEncryptedPassword($data["password"]);
			
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
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["password"] = !empty($data["do_not_encrypt_password"]) ? $data["password"] : UserServiceUtil::getEncryptedPassword($data["password"]);
			$data["password"] = addcslashes($data["password"], "\\'");
			
			return $b->callUpdate("module/user", "update_user_password_and_username", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$data["password"] = !empty($data["do_not_encrypt_password"]) ? $data["password"] : UserServiceUtil::getEncryptedPassword($data["password"]);
			
			$User = $this->getUserHbnObj($b, $options);
			return $User->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$data["password"] = !empty($data["do_not_encrypt_password"]) ? $data["password"] : UserServiceUtil::getEncryptedPassword($data["password"]);
			
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
		$options = isset($data["options"]) ? $data["options"] : null;
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
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($data);
			
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
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($data);
			
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
		$options = isset($data["options"]) ? $data["options"] : null;
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
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "get_user", array("user_id" => $user_id), $options);
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
			return isset($result[0]) ? $result[0] : null;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$User = $this->getUserHbnObj($b, $options);
			$result = $User->findById($user_id);
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUserData($result);
			return $result;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mu_user", null, array("user_id" => $user_id), $options);
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
			return isset($result[0]) ? $result[0] : null;
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
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
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
					!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
					$cond = $cond ? $cond : "1=1";
					$result = $b->callSelect("module/user", "get_users_with_environments_and_conditions", array("environment_ids" => $environment_ids_str, "conditions" => $cond), $options);
					!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
					return $result;
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$User = $this->getUserHbnObj($b, $options);
					$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
					$cond = $cond ? $cond : "1=1";
					$result = $User->callSelect("get_users_with_environments_and_conditions", array("environment_ids" => $environment_ids_str, "conditions" => $cond), $options);
					!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
					return $result;
				}
				else if (is_a($b, "IDBBrokerClient")) {
					!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
					$cond = $cond ? $cond : "1=1";
					$sql = UserDBDAOServiceUtil::get_users_with_environments_and_conditions(array("environment_ids" => $environment_ids_str, "conditions" => $cond));
					
					$result = $b->getSQL($sql, $options);
					!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
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
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
			$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
			$cond = $cond ? $cond : "1=1";
			$result = $b->callSelect("module/user", "get_users_without_environments_and_with_conditions", array("conditions" => $cond), $options);
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
			$User = $this->getUserHbnObj($b, $options);
			$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
			$cond = $cond ? $cond : "1=1";
			$result = $User->callSelect("get_users_without_environments_and_with_conditions", array("conditions" => $cond), $options);
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
			$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
			$cond = $cond ? $cond : "1=1";
			$sql = UserDBDAOServiceUtil::get_users_without_environments_and_with_conditions(array("conditions" => $cond));
			
			$result = $b->getSQL($sql, $options);
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
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
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
				$cond = \DB::getSQLConditions($conditions, $conditions_join);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/user", "get_users_by_conditions", array("conditions" => $cond), $options);
				!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
				return $result;
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
				$User = $this->getUserHbnObj($b, $options);
				$result = $User->find($data, $options);
				!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
				return $result;
			}
			else if (is_a($b, "IDBBrokerClient")) {
				!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
				$options = $options ? $options : array();
				$options["conditions_join"] = $conditions_join;
				$result = $b->findObjects("mu_user", null, $conditions, $options);
				!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
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
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		if ($conditions) {
			$b = $this->getBroker($options);
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
				$cond = \DB::getSQLConditions($conditions, $conditions_join);
				$cond = $cond ? $cond : "1=1";
				$result = $b->callSelect("module/user", "count_users_by_conditions", array("conditions" => $cond), $options);
				return isset($result[0]["total"]) ? $result[0]["total"] : null;
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
				$User = $this->getUserHbnObj($b, $options);
				return $User->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
				$options = $options ? $options : array();
				$options["conditions_join"] = $conditions_join;
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
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
			
			$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
			$cond = $cond ? $cond : "1=1";
			$result = $b->callSelect("module/user", "get_users_with_user_types_by_conditions", array("conditions" => $cond), $options);
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
			
			$User = $this->getUserHbnObj($b, $options);
			$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
			$cond = $cond ? $cond : "1=1";
			$result = $User->callSelect("get_users_with_user_types_by_conditions", array("conditions" => $cond), $options);
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
			$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
			$cond = $cond ? $cond : "1=1";
			$sql = UserDBDAOServiceUtil::get_users_with_user_types_by_conditions(array("conditions" => $cond));
			
			$result = $b->getSQL($sql, $options);
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
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
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
				
			$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
			$cond = $cond ? $cond : "1=1";
			$result = $b->callSelect("module/user", "count_users_with_user_types_by_conditions", array("conditions" => $cond), $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
				
			$User = $this->getUserHbnObj($b, $options);
			$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
			$cond = $cond ? $cond : "1=1";
			$result = $User->callSelect("count_users_with_user_types_by_conditions", array("conditions" => $cond), $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
			$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
			$cond = $cond ? $cond : "1=1";
			$sql = UserDBDAOServiceUtil::count_users_with_user_types_by_conditions(array("conditions" => $cond));
			
			$result = $b->getSQL($sql, $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
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
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
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
					!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
					$cond = $cond ? $cond : "1=1";
					$result = $b->callSelect("module/user", "get_users_by_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "conditions" => $cond), $options);
					!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
					return $result;
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$User = $this->getUserHbnObj($b, $options);
					$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
					$cond = $cond ? $cond : "1=1";
					$result = $User->callSelect("get_users_by_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "conditions" => $cond), $options);
					!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
					return $result;
				}
				else if (is_a($b, "IDBBrokerClient")) {
					!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
					$cond = $cond ? $cond : "1=1";
					$sql = UserDBDAOServiceUtil::get_users_by_user_types_and_conditions(array("user_type_ids" => $user_type_ids_str, "conditions" => $cond));
					
					$result = $b->getSQL($sql, $options);
					!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
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
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
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
					!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
					$cond = $cond ? $cond : "1=1";
					$result = $b->callSelect("module/user", "count_users_by_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "conditions" => $cond), $options);
					return isset($result[0]["total"]) ? $result[0]["total"] : null;
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$User = $this->getUserHbnObj($b, $options);
					$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
					$cond = $cond ? $cond : "1=1";
					$result = $User->callSelect("count_users_by_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "conditions" => $cond), $options);
					return isset($result[0]["total"]) ? $result[0]["total"] : null;
				}
				else if (is_a($b, "IDBBrokerClient")) {
					!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
					$cond = $cond ? $cond : "1=1";
					$sql = UserDBDAOServiceUtil::count_users_by_user_types_and_conditions(array("user_type_ids" => $user_type_ids_str, "conditions" => $cond));
					
					$result = $b->getSQL($sql, $options);
					return isset($result[0]["total"]) ? $result[0]["total"] : null;
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
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
			$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
			$cond = $cond ? $cond : "1=1";
			$result = $b->callSelect("module/user", "get_users_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
			$User = $this->getUserHbnObj($b, $options);
			$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
			$cond = $cond ? $cond : "1=1";
			$result = $User->callSelect("get_users_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
			$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
			$cond = $cond ? $cond : "1=1";
			$sql = UserDBDAOServiceUtil::get_users_by_object_and_conditions(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
			
			$result = $b->getSQL($sql, $options);
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
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
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
			$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
			$cond = $cond ? $cond : "1=1";
			$result = $b->callSelect("module/user", "count_users_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
			$User = $this->getUserHbnObj($b, $options);
			$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
			$cond = $cond ? $cond : "1=1";
			$result = $User->callSelect("count_users_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
			$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
			$cond = $cond ? $cond : "1=1";
			$sql = UserDBDAOServiceUtil::count_users_by_object_and_conditions(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
			
			$result = $b->getSQL($sql, $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
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
		$group = isset($data["group"]) ? $data["group"] : null;
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
			$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
			$cond = $cond ? $cond : "1=1";
			$result = $b->callSelect("module/user", "get_users_by_object_group_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
			$User = $this->getUserHbnObj($b, $options);
			$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
			$cond = $cond ? $cond : "1=1";
			$result = $User->callSelect("get_users_by_object_group_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
			$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
			$cond = $cond ? $cond : "1=1";
			$sql = UserDBDAOServiceUtil::get_users_by_object_group_and_conditions(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond));
			
			$result = $b->getSQL($sql, $options);
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
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
		$group = isset($data["group"]) ? $data["group"] : null;
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
	
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
			$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
			$cond = $cond ? $cond : "1=1";
			$result = $b->callSelect("module/user", "count_users_by_object_group_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
			$User = $this->getUserHbnObj($b, $options);
			$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
			$cond = $cond ? $cond : "1=1";
			$result = $User->callSelect("count_users_by_object_group_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
			$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
			$cond = $cond ? $cond : "1=1";
			$sql = UserDBDAOServiceUtil::count_users_by_object_group_and_conditions(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond));
			
			$result = $b->getSQL($sql, $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
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
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
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
					!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
					$cond = $cond ? $cond : "1=1";
					$result = $b->callSelect("module/user", "get_users_by_object_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
					!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
					return $result;
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$User = $this->getUserHbnObj($b, $options);
					$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
					$cond = $cond ? $cond : "1=1";
					$result = $User->callSelect("get_users_by_object_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
					!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
					return $result;
				}
				else if (is_a($b, "IDBBrokerClient")) {
					!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
					$cond = $cond ? $cond : "1=1";
					$sql = UserDBDAOServiceUtil::get_users_by_object_and_user_types_and_conditions(array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
					
					$result = $b->getSQL($sql, $options);
					!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
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
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
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
					!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
					$cond = $cond ? $cond : "1=1";
					$result = $b->callSelect("module/user", "count_users_by_object_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
					return isset($result[0]["total"]) ? $result[0]["total"] : null;
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$User = $this->getUserHbnObj($b, $options);
					$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
					$cond = $cond ? $cond : "1=1";
					$result = $User->callSelect("count_users_by_object_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
					return isset($result[0]["total"]) ? $result[0]["total"] : null;
				}
				else if (is_a($b, "IDBBrokerClient")) {
					!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
					$cond = $cond ? $cond : "1=1";
					$sql = UserDBDAOServiceUtil::count_users_by_object_and_user_types_and_conditions(array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
					
					$result = $b->getSQL($sql, $options);
					return isset($result[0]["total"]) ? $result[0]["total"] : null;
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
		$group = isset($data["group"]) ? $data["group"] : null;
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
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
					!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
					$cond = $cond ? $cond : "1=1";
					$result = $b->callSelect("module/user", "get_users_by_object_group_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
					!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
					return $result;
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$User = $this->getUserHbnObj($b, $options);
					$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
					$cond = $cond ? $cond : "1=1";
					$result = $User->callSelect("get_users_by_object_group_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
					!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
					return $result;
				}
				else if (is_a($b, "IDBBrokerClient")) {
					!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
					$cond = $cond ? $cond : "1=1";
					$sql = UserDBDAOServiceUtil::get_users_by_object_group_and_user_types_and_conditions(array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond));
					
					$result = $b->getSQL($sql, $options);
					!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
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
		$group = isset($data["group"]) ? $data["group"] : null;
		$conditions = isset($data["conditions"]) ? $data["conditions"] : null;
		$conditions_join = isset($data["conditions_join"]) ? $data["conditions_join"] : null;
		$options = isset($data["options"]) ? $data["options"] : null;
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
					!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
					$cond = $cond ? $cond : "1=1";
					$result = $b->callSelect("module/user", "count_users_by_object_group_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
					return isset($result[0]["total"]) ? $result[0]["total"] : null;
				}
				else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
					!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					
					$User = $this->getUserHbnObj($b, $options);
					$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
					$cond = $cond ? $cond : "1=1";
					$result = $User->callSelect("count_users_by_object_group_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
					return isset($result[0]["total"]) ? $result[0]["total"] : null;
				}
				else if (is_a($b, "IDBBrokerClient")) {
					!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
					$cond = \DB::getSQLConditions($conditions, $conditions_join, "u");
					$cond = $cond ? $cond : "1=1";
					$sql = UserDBDAOServiceUtil::count_users_by_object_group_and_user_types_and_conditions(array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond));
					
					$result = $b->getSQL($sql, $options);
					return isset($result[0]["total"]) ? $result[0]["total"] : null;
				}
				else if (is_a($b, "IBusinessLogicBrokerClient"))
					return $b->callBusinessLogic("module/user", "UserService.countUsersByObjectGroupAndUserTypesAndConditions", $data, $options);
			}
		}
	}
	
	public function getAllUsers($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "get_all_users", null, $options);
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$User = $this->getUserHbnObj($b, $options);
			$result = $User->find();
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mu_user", null, null, $options);
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserService.getAllUsers", $data, $options);
	}
	
	public function countAllUsers($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "count_all_users", null, $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
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
