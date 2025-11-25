<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

namespace Module\User;

include_once $vars["business_logic_modules_service_common_file_path"];
include_once get_lib("org.phpframework.db.DB");
include_once __DIR__ . "/UserServiceUtil.php";

class UserSessionService extends \soa\CommonService {
	private $UserSession;
	
	private function getUserSessionHbnObj($b, $options) {
		if (!$this->UserSession)
			$this->UserSession = $b->callObject("module/user", "UserSession", $options);
		
		return $this->UserSession;
	}
	
	/**
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 * @param (name=data[environment_id], type=bigint, default=0, length=19)
	 * @param (name=data[session_id], type=varchar, length=200)
	 * @param (name=data[user_id], type=bigint, default=0, length=19)
	 * @param (name=data[logged_status], type=bool, not_null=1)
	 * @param (name=data[login_time], type=bigint, default=0, length=19)
	 * @param (name=data[login_ip], type=varchar, length=100)
	 * @param (name=data[failed_login_attempts], type=tinyint, default=0, length=10)
	 * @param (name=data[failed_login_time], type=bigint, default=0, length=19)
	 * @param (name=data[failed_login_ip], type=varchar, length=100)
	 */
	public function insertUserSession($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["created_date"] = date("Y-m-d H:i:s");
		$data["modified_date"] = $data["created_date"];
			
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($data);
			
			$data["username"] = addcslashes($data["username"], "\\'");
			$data["session_id"] = isset($data["session_id"]) ? addcslashes($data["session_id"], "\\'") : "";
			$data["login_ip"] = isset($data["login_ip"]) ? addcslashes($data["login_ip"], "\\'") : "";
			$data["failed_login_ip"] = isset($data["failed_login_ip"]) ? addcslashes($data["failed_login_ip"], "\\'") : "";
			
			return $b->callInsert("module/user", "insert_user_session", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($data);
			
			$UserSession = $this->getUserSessionHbnObj($b, $options);
			return $UserSession->insert($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($data);
			
			return $b->insertObject("mu_user_session", array(
				"username" => $data["username"], 
				"environment_id" => isset($data["environment_id"]) ? $data["environment_id"] : null, 
				"session_id" => isset($data["session_id"]) ? $data["session_id"] : null, 
				"user_id" => isset($data["user_id"]) ? $data["user_id"] : null, 
				"logged_status" => isset($data["logged_status"]) ? $data["logged_status"] : null, 
				"login_time" => isset($data["login_time"]) ? $data["login_time"] : null, 
				"login_ip" => isset($data["login_ip"]) ? $data["login_ip"] : null, 
				"failed_login_attempts" => isset($data["failed_login_attempts"]) ? $data["failed_login_attempts"] : null, 
				"failed_login_time" => isset($data["failed_login_time"]) ? $data["failed_login_time"] : null, 
				"failed_login_ip" => isset($data["failed_login_ip"]) ? $data["failed_login_ip"] : null, 
				"created_date" => $data["created_date"], 
				"modified_date" => $data["modified_date"]
			), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserSessionService.insertUserSession", $data, $options);
	}
	
	/**
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 * @param (name=data[environment_id], type=bigint, default=0, length=19)
	 * @param (name=data[session_id], type=varchar, length=200)
	 * @param (name=data[user_id], type=bigint, default=0, length=19)
	 * @param (name=data[logged_status], type=bool, not_null=1)
	 * @param (name=data[login_time], type=bigint, default=0, length=19)
	 * @param (name=data[login_ip], type=varchar, length=100)
	 * @param (name=data[failed_login_attempts], type=tinyint, default=0, length=10)
	 * @param (name=data[failed_login_time], type=bigint, default=0, length=19)
	 * @param (name=data[failed_login_ip], type=varchar, length=100)
	 */
	public function updateUserSession($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($data);
			
			$data["username"] = addcslashes($data["username"], "\\'");
			$data["session_id"] = isset($data["session_id"]) ? addcslashes($data["session_id"], "\\'") : "";
			$data["login_ip"] = isset($data["login_ip"]) ? addcslashes($data["login_ip"], "\\'") : "";
			$data["failed_login_ip"] = isset($data["failed_login_ip"]) ? addcslashes($data["failed_login_ip"], "\\'") : "";
			
			return $b->callUpdate("module/user", "update_user_session", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($data);
			
			$UserSession = $this->getUserSessionHbnObj($b, $options);
			return $UserSession->update($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($data);
			
			return $b->updateObject("mu_user_session", array(
					"session_id" => isset($data["session_id"]) ? $data["session_id"] : null, 
					"user_id" => isset($data["user_id"]) ? $data["user_id"] : null, 
					"logged_status" => isset($data["logged_status"]) ? $data["logged_status"] : null, 
					"login_time" => isset($data["login_time"]) ? $data["login_time"] : null, 
					"login_ip" => isset($data["login_ip"]) ? $data["login_ip"] : null, 
					"failed_login_attempts" => isset($data["failed_login_attempts"]) ? $data["failed_login_attempts"] : null, 
					"failed_login_time" => isset($data["failed_login_time"]) ? $data["failed_login_time"] : null, 
					"failed_login_ip" => isset($data["failed_login_ip"]) ? $data["failed_login_ip"] : null, 
					"modified_date" => $data["modified_date"]
				), array(
					"username" => $data["username"], 
					"environment_id" => isset($data["environment_id"]) ? $data["environment_id"] : null
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserSessionService.updateUserSession", $data, $options);
	}
	
	/**
	 * @param (name=data[session_id], type=varchar, not_null=1, min_length=1, max_length=200)
	 * @param (name=data[captcha], type=varchar, not_null=1)
	 */
	public function updateUserSessionCaptchaBySessionId($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["session_id"] = addcslashes($data["session_id"], "\\'");
			$data["captcha"] = addcslashes($data["captcha"], "\\'");
			
			return $b->callUpdate("module/user", "update_user_session_captcha_by_session_id", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserSession = $this->getUserSessionHbnObj($b, $options);
			$attributes = array("captcha" => $data["captcha"], "modified_date" => $data["modified_date"]);
			$conditions = array("session_id" => $data["session_id"]);
			return $UserSession->updateByConditions(array("attributes" => $attributes, "conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mu_user_session", array( 
					"captcha" => $data["captcha"],
					"modified_date" => $data["modified_date"]
				), array(
					"session_id" => $data["session_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserSessionService.updateUserSessionCaptchaBySessionId", $data, $options);
	}
	
	/**
	 * @param (name=data[session_id], type=varchar, not_null=1, min_length=1, max_length=200)
	 * @param (name=data[logout_time], type=bigint, default=0, length=19)
	 * @param (name=data[logout_ip], type=varchar, length=100)
	 */
	public function logoutBySessionId($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$data["modified_date"] = date("Y-m-d H:i:s");
		$data["logged_status"] = 0;
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$data["session_id"] = addcslashes($data["session_id"], "\\'");
			$data["logout_ip"] = isset($data["logout_ip"]) ? addcslashes($data["logout_ip"], "\\'") : "";
			
			return $b->callUpdate("module/user", "logout_by_session_id", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserSession = $this->getUserSessionHbnObj($b, $options);
			$attributes = array(
				"logged_status" => isset($data["logged_status"]) ? $data["logged_status"] : null, 
				"logout_time" => isset($data["logout_time"]) ? $data["logout_time"] : null, 
				"logout_ip" => isset($data["logout_ip"]) ? $data["logout_ip"] : null, 
				"modified_date" => $data["modified_date"]
			);
			$conditions = array("session_id" => $data["session_id"]);
			return $UserSession->updateByConditions(array("attributes" => $attributes, "conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->updateObject("mu_user_session", array( 
					"logged_status" => isset($data["logged_status"]) ? $data["logged_status"] : null, 
					"logout_time" => isset($data["logout_time"]) ? $data["logout_time"] : null, 
					"logout_ip" => isset($data["logout_ip"]) ? $data["logout_ip"] : null, 
					"modified_date" => $data["modified_date"]
				), array(
					"session_id" => $data["session_id"]
				), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserSessionService.logoutBySessionId", $data, $options);
	}
	
	/**
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 * @param (name=data[environment_id], type=bigint, default=0, length=19)
	 */
	public function deleteUserSession($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($data);
			
			$data["username"] = addcslashes($data["username"], "\\'");
			
			return $b->callDelete("module/user", "delete_user_session", array(
				"username" => $data["username"], 
				"environment_id" => isset($data["environment_id"]) ? $data["environment_id"] : null
			), $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($data);
			
			$UserSession = $this->getUserSessionHbnObj($b, $options);
			return $UserSession->delete(array(
				"username" => $data["username"], 
				"environment_id" => isset($data["environment_id"]) ? $data["environment_id"] : null
			));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($data);
			
			return $b->deleteObject("mu_user_session", array(
				"username" => $data["username"], 
				"environment_id" => isset($data["environment_id"]) ? $data["environment_id"] : null
			), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserSessionService.deleteUserSession", $data, $options);
	}
	
	/**
	 * @param (name=data[session_id], type=varchar, not_null=1, min_length=1, max_length=200)
	 */
	public function deleteUserSessionBySessionId($data) {
		$session_id = $data["session_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$session_id = addcslashes($session_id, "\\'");
			
			return $b->callDelete("module/user", "delete_user_session_by_session_id", array("session_id" => $session_id), $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserSession = $this->getUserSessionHbnObj($b, $options);
			$conditions = array("session_id" => $session_id);
			return $UserSession->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mu_user_session", array("session_id" => $session_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserSessionService.deleteUserSessionBySessionId", $data, $options);
	}
	
	/**
	 * @param (name=data[user_id], type=bigint, not_null=1, length=19)
	 */
	public function deleteUserSessionByUserId($data) {
		$user_id = $data["user_id"];
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			return $b->callDelete("module/user", "delete_user_session_by_user_id", array("user_id" => $user_id), $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserSession = $this->getUserSessionHbnObj($b, $options);
			$conditions = array("user_id" => $user_id);
			return $UserSession->deleteByConditions(array("conditions" => $conditions));
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->deleteObject("mu_user_session", array("user_id" => $user_id), $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/user", "UserSessionService.deleteUserSessionByUserId", $data, $options);
	}
	
	/**
	 * @param (name=data[username], type=varchar, not_null=1, min_length=1, max_length=50)
	 * @param (name=data[environment_id], type=bigint, default=0, length=19)
	 */
	public function getUserSession($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($data);
			
			$data["username"] = addcslashes($data["username"], "\\'");
			
			$result = $b->callSelect("module/user", "get_user_session", array(
				"username" => $data["username"], 
				"environment_id" => isset($data["environment_id"]) ? $data["environment_id"] : null
			), $options);
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
			return isset($result[0]) ? $result[0] : null;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($data);
			
			$UserSession = $this->getUserSessionHbnObj($b, $options);
			$result = $UserSession->findById(array(
				"username" => $data["username"], 
				"environment_id" => isset($data["environment_id"]) ? $data["environment_id"] : null
			));
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUserData($result);
			return $result;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($data);
			
			$result = $b->findObjects("mu_user_session", null, array(
				"username" => $data["username"], 
				"environment_id" => isset($data["environment_id"]) ? $data["environment_id"] : null
			), $options);
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
			return isset($result[0]) ? $result[0] : null;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserSessionService.getUserSession", $data, $options);
	}
	
	/**
	 * @param (name=data[conditions][username], type=varchar|array, length=50)
	 * @param (name=data[conditions][environment_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][session_id], type=varchar|array, length=200)
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][logged_status], type=bool|array)
	 * @param (name=data[conditions][login_time], type=bigint|array, length=19)
	 * @param (name=data[conditions][login_ip], type=varchar|array, length=100)
	 * @param (name=data[conditions][logout_time], type=bigint|array, length=19)
	 * @param (name=data[conditions][logout_ip], type=varchar|array, length=100)
	 * @param (name=data[conditions][failed_login_attempts], type=tinyint|array, length=10)
	 * @param (name=data[conditions][failed_login_time], type=bigint|array, length=19)
	 * @param (name=data[conditions][failed_login_ip], type=varchar|array, length=100)
	 * @param (name=data[conditions][captcha], type=varchar|array, length=50)
	 */
	public function getUserSessionsByConditions($data) {
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
				$result = $b->callSelect("module/user", "get_user_sessions_by_conditions", array("conditions" => $cond), $options);
				!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
				return $result;
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
			
				$UserSession = $this->getUserSessionHbnObj($b, $options);
				$result = $UserSession->find($data, $options);
				!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
				return $result;
			}
			else if (is_a($b, "IDBBrokerClient")) {
				!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
				$options = $options ? $options : array();
				$options["conditions_join"] = $conditions_join;
				$result = $b->findObjects("mu_user_session", null, $conditions, $options);
				!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
				return $result;
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/user", "UserSessionService.getUserSessionsByConditions", $data, $options);
		}
	}
	
	/**
	 * @param (name=data[conditions][username], type=varchar|array, length=50)
	 * @param (name=data[conditions][environment_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][session_id], type=varchar|array, length=200)
	 * @param (name=data[conditions][user_id], type=bigint|array, length=19)
	 * @param (name=data[conditions][logged_status], type=bool|array)
	 * @param (name=data[conditions][login_time], type=bigint|array, length=19)
	 * @param (name=data[conditions][login_ip], type=varchar|array, length=100)
	 * @param (name=data[conditions][logout_time], type=bigint|array, length=19)
	 * @param (name=data[conditions][logout_ip], type=varchar|array, length=100)
	 * @param (name=data[conditions][failed_login_attempts], type=tinyint|array, length=10)
	 * @param (name=data[conditions][failed_login_time], type=bigint|array, length=19)
	 * @param (name=data[conditions][failed_login_ip], type=varchar|array, length=100)
	 * @param (name=data[conditions][captcha], type=varchar|array, length=50)
	 */
	public function countUserSessionsByConditions($data) {
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
				$result = $b->callSelect("module/user", "count_user_sessions_by_conditions", array("conditions" => $cond), $options);
				return isset($result[0]["total"]) ? $result[0]["total"] : null;
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
			
				$UserSession = $this->getUserSessionHbnObj($b, $options);
				return $UserSession->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				!empty($data["encode_user_data"]) && UserServiceUtil::encodeSensitiveUserData($conditions);
				$options = $options ? $options : array();
				$options["conditions_join"] = $conditions_join;
				return $b->countObjects("mu_user_session", $conditions, $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/user", "UserSessionService.countUserSessionsByConditions", $data, $options);
		}
	}
	
	public function getAllUserSessions($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "get_all_user_sessions", null, $options);
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserSession = $this->getUserSessionHbnObj($b, $options);
			$result = $UserSession->find();
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$result = $b->findObjects("mu_user_session", null, null, $options);
			!empty($data["decode_user_data"]) && UserServiceUtil::decodeSensitiveUsersData($result);
			return $result;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserSessionService.getAllUserSessions", $data, $options);
	}
	
	public function countAllUserSessions($data) {
		$options = isset($data["options"]) ? $data["options"] : null;
		$this->mergeOptionsWithBusinessLogicLayer($options);
		
		$b = $this->getBroker($options);
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			$result = $b->callSelect("module/user", "count_all_user_sessions", null, $options);
			return isset($result[0]["total"]) ? $result[0]["total"] : null;
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$UserSession = $this->getUserSessionHbnObj($b, $options);
			return $UserSession->count();
		}
		else if (is_a($b, "IDBBrokerClient")) {
			return $b->countObjects("mu_user_session", null, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/user", "UserSessionService.countAllUserSessions", $data, $options);
	}
}
?>
