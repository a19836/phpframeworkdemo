<?php
include_once get_lib("org.phpframework.encryption.CryptoKeyHandler");
include_once get_lib("org.phpframework.util.web.CookieHandler");
include_once get_lib("org.phpframework.util.MyArray");
include_once get_lib("org.phpframework.util.HashCode");
include_once get_lib("org.phpframework.util.text.TextShuffler");
include_once $EVC->getModulePath("object/ObjectUtil", $EVC->getCommonProjectName());
include_once $EVC->getModulePath("attachment/AttachmentUtil", $EVC->getCommonProjectName());
include_once __DIR__ . "/UserDBDAOUtil.php"; //this file will be automatically generated on this module installation
include_once __DIR__ . "/UserTypeActivityObjectDBDAOUtil.php"; //this file will be automatically generated on this module installation

if (!class_exists("UserUtil")) {
	include __DIR__ . "/UserSettings.php";

	class UserUtil extends UserSettings {
	
		const USER_BLOCKED = 2;
		const WRONG_CAPTCHA = 3;
		const DUPLICATED_USERNAME = 4;
		const INACTIVE_USERNAME = 5;
	
		const PUBLIC_USER_TYPE_ID = 1;
		const ADMIN_USER_TYPE_ID = 2;
		const REGULAR_USER_TYPE_ID = 3;
	
		const ACCESS_ACTIVITY_ID = 1;
		const WRITE_ACTIVITY_ID = 2;
		const DELETE_ACTIVITY_ID = 3;
		
		const USER_PHOTO_GROUP_ID = 1;
		const USER_ATTACHMENTS_GROUP_ID = 2;
		
		/* GENERIC FUNCTIONS */
		public static function getReservedUserTypeIds() {
			return array_keys( self::getReservedUserTypes() );
		}
	
		public static function getReservedUserTypes() {
			return array(
				self::PUBLIC_USER_TYPE_ID => "public",
			);
		}
	
		public static function getReservedActivityIds() {
			return array_keys( self::getReservedActivities() );
		}
		
		public static function getReservedActivities() {
			return array(
				self::ACCESS_ACTIVITY_ID => "access", 
				self::WRITE_ACTIVITY_ID => "write", 
				self::DELETE_ACTIVITY_ID => "delete",
			);
		}
		
		public static function getEncryptedPassword($password) {
			return password_hash(md5($password), PASSWORD_BCRYPT);
		}
		
		public static function encodeSensitiveUserData(&$user_data, $extra_attrs = array()) {
			if (self::getConstantVariable("HASH_SENSITIVE_DATA")) {
				if ($user_data["username"] && !is_array($user_data["username"])) //it could be a condition array
					$user_data["username"] = TextShuffler::autoShuffle($user_data["username"]);
				
				if ($user_data["name"] && !is_array($user_data["name"])) //it could be a condition array
					$user_data["name"] = TextShuffler::autoShuffle($user_data["name"]);
				
				if ($user_data["email"] && !is_array($user_data["email"])) //it could be a condition array
					$user_data["email"] = TextShuffler::autoShuffle($user_data["email"]);
				
				if ($extra_attrs) {
					$extra_attrs = !is_array($extra_attrs) ? array($extra_attrs) : $extra_attrs;
					
					foreach ($extra_attrs as $attr)
						if ($attr && isset($user_data[$attr]) && !is_array($user_data[$attr])) //it could be a condition array
							$user_data[$attr] = TextShuffler::autoShuffle($user_data[$attr]);
				}
			}
		}
		
		public static function decodeSensitiveUserData(&$user_data, $extra_attrs = array()) {
			if (self::getConstantVariable("HASH_SENSITIVE_DATA")) {
				if ($user_data["username"] && !is_array($user_data["username"])) //it could be a condition array
					$user_data["username"] = TextShuffler::autoUnshuffle($user_data["username"]);
				
				if ($user_data["name"] && !is_array($user_data["name"])) //it could be a condition array
					$user_data["name"] = TextShuffler::autoUnshuffle($user_data["name"]);
				
				if ($user_data["email"] && !is_array($user_data["email"])) //it could be a condition array
					$user_data["email"] = TextShuffler::autoUnshuffle($user_data["email"]);
				
				if ($extra_attrs) {
					$extra_attrs = !is_array($extra_attrs) ? array($extra_attrs) : $extra_attrs;
					
					foreach ($extra_attrs as $attr)
						if ($attr && isset($user_data[$attr]) && !is_array($user_data[$attr])) //it could be a condition array
							$user_data[$attr] = TextShuffler::autoShuffle($user_data[$attr]);
				}
			}
		}
		
		public static function decodeSensitiveUsersData(&$users_data, $extra_attrs = array()) {
			if ($users_data)
				foreach ($users_data as &$user_data)
					self::decodeSensitiveUserData($user_data, $extra_attrs);
		}
		
		public static function getObjectIdFromFilePath($file_path) {
			$file_path = preg_replace("/\/+/", "/", $file_path);
			$file_path = str_replace(APP_PATH, "", $file_path);
			$object_id = HashCode::getHashCodePositive($file_path);
			
			return $object_id;
		}
		
		public static function usersCountExceedLimit($EVC) {
			$P = $EVC->getPresentationLayer();
			$PHPFrameWork = $P->getPHPFrameWork();
			$li = method_exists($PHPFrameWork, "getLicenceInfo") ? $PHPFrameWork->getLicenceInfo() : $PHPFrameWork->gLI();
			$brokers = $P->getBrokers();
			
			$count = self::countAllUsers($brokers, true);
			//$count = self::countUsersByConditions($brokers, array("active" => 1), null, true);
			
			return $li && $li["eumn"] > 0 && $count > $li["eumn"]; //eumn => end_users_maximum_number
		}
	
		/* ACTIVITY FUNCTIONS */
		
		public static function reinsertReservedActivities($brokers) {
			$status = true;
			$activities = self::getReservedActivities();
			
			if ($activities)
				foreach ($activities as $activity_id => $name) {
					self::deleteActivity($brokers, $activity_id);
					
					$data = array("activity_id" => $activity_id, "name" => $name);
					if (!self::insertActivity($brokers, $data))
						$status = false;
				}
			
			return $status;
		}
	
		public static function insertActivity($brokers, $data) {
			if (is_array($brokers)) {
				$data["created_date"] = date("Y-m-d H:i:s");
				$data["modified_date"] = $data["created_date"];
				$options = array();
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient"))
						return $broker->callBusinessLogic("module/user", "ActivityService.insertActivity", $data);
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["name"] = addcslashes($data["name"], "\\'");
					
						if ($data["activity_id"]) {
							$options = array("hard_coded_ai_pk" => true);
							$status = $broker->callInsert("module/user", "insert_activity_with_ai_pk", $data, $options);
							return $status ? $data["activity_id"] : $status;
						}
						
						$status = $broker->callInsert("module/user", "insert_activity", $data);
						return $status ? $broker->getInsertedId($options) : $status;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						if (!$data["activity_id"])
							unset($data["activity_id"]);
						
						$Activity = $broker->callObject("module/user", "Activity");
						$status = $Activity->insert($data, $ids);
						return $status ? $ids["activity_id"] : $status;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$attributes = array(
							"name" => $data["name"], 
							"created_date" => $data["created_date"], 
							"modified_date" => $data["modified_date"]
						);
						
						if ($data["activity_id"]) {
							$options["hard_coded_ai_pk"] = true;
							$attributes["activity_id"] = $data["activity_id"];
						}
						
						$status = $broker->insertObject("mu_activity", $attributes, $options);
						return $status ? ($data["activity_id"] ? $data["activity_id"] : $broker->getInsertedId($options)) : $status;
					}
				}
			}
		}
	
		public static function updateActivity($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["activity_id"])) {
				$data["modified_date"] = date("Y-m-d H:i:s");
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "ActivityService.updateActivity", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["name"] = addcslashes($data["name"], "\\'");
					
						return $broker->callUpdate("module/user", "update_activity", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Activity = $broker->callObject("module/user", "Activity");
						return $Activity->update($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->updateObject("mu_activity", array(
								"name" => $data["name"],
								"modified_date" => $data["modified_date"]
							), array(
								"activity_id" => $data["activity_id"], 
							));
					}
				}
			}
		}
	
		public static function deleteActivity($brokers, $activity_id) {
			if (is_array($brokers) && is_numeric($activity_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "ActivityService.deleteActivity", array("activity_id" => $activity_id));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/user", "delete_activity", array("activity_id" => $activity_id));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Activity = $broker->callObject("module/user", "Activity");
						return $Activity->delete($activity_id);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mu_activity", array("activity_id" => $activity_id));
					}
				}
			}
		}
	
		public static function getAllActivities($brokers, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/user", "ActivityService.getAllActivities", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/user", "get_all_activities", null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Activity = $broker->callObject("module/user", "Activity");
						return $Activity->find(null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mu_activity", null, null, array("no_cache" => $no_cache));
					}
				}
			}
		}
	
		public static function getActivitiesByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "ActivityService.getActivitiesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						return $broker->callSelect("module/user", "get_activities_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Activity = $broker->callObject("module/user", "Activity");
						return $Activity->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$options["conditions_join"] = $conditions_join;
						return $broker->findObjects("mu_activity", null, $conditions, array("no_cache" => $no_cache));
					}
				}
			}
		}
	
		/* USER TYPE FUNCTIONS */
		
		public static function reinsertReservedUserTypes($brokers) {
			$status = true;
			$user_types = self::getReservedUserTypes();
			
			if ($user_types)
				foreach ($user_types as $user_type_id => $name) {
					self::deleteUserType($brokers, $user_type_id);
					
					$data = array("user_type_id" => $user_type_id, "name" => $name);
					if (!self::insertUserType($brokers, $data))
						$status = false;
				}
			
			return $status;
		}
	
		public static function insertUserType($brokers, $data) {
			if (is_array($brokers)) {
				$data["created_date"] = date("Y-m-d H:i:s");
				$data["modified_date"] = $data["created_date"];
				$options = array();
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserTypeService.insertUserType", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["name"] = addcslashes($data["name"], "\\'");
					
						if ($data["user_type_id"]) {
							$options = array("hard_coded_ai_pk" => true);
							$status = $broker->callInsert("module/user", "insert_user_type_with_ai_pk", $data, $options);
							return $status ? $data["user_type_id"] : $status;
						}
						
						$status = $broker->callInsert("module/user", "insert_user_type", $data);
						return $status ? $broker->getInsertedId($options) : $status;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						if (!$data["user_type_id"])
							unset($data["user_type_id"]);
						
						$UserType = $broker->callObject("module/user", "UserType");
						$status = $UserType->insert($data, $ids);
						return $status ? $ids["user_type_id"] : $status;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$attributes = array(
							"name" => $data["name"], 
							"created_date" => $data["created_date"], 
							"modified_date" => $data["modified_date"]
						);
						
						if ($data["user_type_id"]) {
							$options["hard_coded_ai_pk"] = true;
							$attributes["user_type_id"] = $data["user_type_id"];
						}
						
						$status = $broker->insertObject("mu_user_type", $attributes, $options);
						return $status ? ($data["user_type_id"] ? $data["user_type_id"] : $broker->getInsertedId($options)) : $status;
					}
				}
			}
		}
	
		public static function updateUserType($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["user_type_id"])) {
				$data["modified_date"] = date("Y-m-d H:i:s");
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserTypeService.updateUserType", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["name"] = addcslashes($data["name"], "\\'");
					
						return $broker->callUpdate("module/user", "update_user_type", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserType = $broker->callObject("module/user", "UserType");
						return $UserType->update($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->updateObject("mu_user_type", array(
								"name" => $data["name"],
								"modified_date" => $data["modified_date"]
							), array(
								"user_type_id" => $data["user_type_id"], 
							));
					}
				}
			}
		}
	
		public static function deleteUserType($brokers, $user_type_id) {
			if (is_array($brokers) && is_numeric($user_type_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserTypeService.deleteUserType", array("user_type_id" => $user_type_id));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/user", "delete_user_type", array("user_type_id" => $user_type_id));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserType = $broker->callObject("module/user", "UserType");
						return $UserType->delete($user_type_id);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mu_user_type", array("user_type_id" => $user_type_id));
					}
				}
			}
		}
	
		public static function getAllUserTypes($brokers, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/user", "UserTypeService.getAllUserTypes", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/user", "get_all_user_types", null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserType = $broker->callObject("module/user", "UserType");
						return $UserType->find(null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mu_user_type", null, null, array("no_cache" => $no_cache));
					}
				}
			}
		}
	
		public static function getUserTypesByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserTypeService.getUserTypesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						return $broker->callSelect("module/user", "get_user_types_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserType = $broker->callObject("module/user", "UserType");
						return $UserType->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$options["conditions_join"] = $conditions_join;
						return $broker->findObjects("mu_user_type", null, $conditions, array("no_cache" => $no_cache));
					}
				}
			}
		}
	
		/* USER FUNCTIONS */
	
		public static function insertUser($EVC, $data, $brokers = array()) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
			
			if (is_array($brokers)) {
				$status = false;
					
				$data["created_date"] = date("Y-m-d H:i:s");
				$data["modified_date"] = $data["created_date"];
				
				if ($data["username"])
					$data["username"] = strtolower($data["username"]);
				
				if ($data["email"])
					$data["email"] = strtolower($data["email"]);
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = true;
						
						$user_id = $broker->callBusinessLogic("module/user", "UserService.insertUser", $data);
						$status = $user_id ? true : false;
						break;
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($data);
						
						if ($data["password"])
							$data["password"] = $data["do_not_encrypt_password"] ? $data["password"] : self::getEncryptedPassword($data["password"]);
						
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
					
						$status = $broker->callInsert("module/user", "insert_user", $data);
						$user_id = $status ? $broker->getInsertedId() : false;
						break;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($data);
						
						if ($data["password"])
							$data["password"] = $data["do_not_encrypt_password"] ? $data["password"] : self::getEncryptedPassword($data["password"]);
						
						if (isset($data["active"]))
							$data["active"] = is_numeric($data["active"]) ? $data["active"] : 0;
						
						$User = $broker->callObject("module/user", "User");
						$status = $User->insert($data, $ids);
						$user_id = $status ? $ids["user_id"] : false;
						break;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($data);
						
						if ($data["password"])
							$data["password"] = $data["do_not_encrypt_password"] ? $data["password"] : self::getEncryptedPassword($data["password"]);
						
						if (isset($data["active"]))
							$data["active"] = is_numeric($data["active"]) ? $data["active"] : 0;
						
						$status = $broker->insertObject("mu_user", array(
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
							));
						$user_id = $status ? $broker->getInsertedId() : false;
						break;
					}
				}
				
				if ($status && $user_id && (!self::updateObjectUsersByUserId(array($broker), $user_id, $data) || !self::updateUserEnvironmentsByUserId(array($broker), $user_id, $data))) {
					$status = false;
					self::deleteUser($EVC, $user_id, array($broker));
				}
				
				return $status ? $user_id : false;
			}
		}
	
		public static function updateUser($EVC, $data, $brokers = array()) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
			
			if (is_array($brokers) && is_numeric($data["user_id"])) {
				$status = false;
				
				$data["modified_date"] = date("Y-m-d H:i:s");
			
				if ($data["username"])
					$data["username"] = strtolower($data["username"]);
				
				if ($data["email"])
					$data["email"] = strtolower($data["email"]);
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = true;
						
						$status = $broker->callBusinessLogic("module/user", "UserService.updateUser", $data);
						break;
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($data);
						
						$data["username"] = addcslashes($data["username"], "\\'");
						$data["email"] = addcslashes($data["email"], "\\'");
						$data["name"] = addcslashes($data["name"], "\\'");
						$data["security_question_1"] = addcslashes($data["security_question_1"], "\\'");
						$data["security_answer_1"] = addcslashes($data["security_answer_1"], "\\'");
						$data["security_question_2"] = addcslashes($data["security_question_2"], "\\'");
						$data["security_answer_2"] = addcslashes($data["security_answer_2"], "\\'");
						$data["security_question_3"] = addcslashes($data["security_question_3"], "\\'");
						$data["security_answer_3"] = addcslashes($data["security_answer_3"], "\\'");
					
						$status = $broker->callUpdate("module/user", "update_user", $data);
						break;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($data);
						
						$User = $broker->callObject("module/user", "User");
						$status = $User->update($data);
						break;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($data);
						
						$status = $broker->updateObject("mu_user", array(
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
								"user_id" => $data["user_id"], 
							));
						break;
					}
				}
				
				if ($status && $data["user_id"] && (!self::updateObjectUsersByUserId(array($broker), $data["user_id"], $data) || !self::updateUserEnvironmentsByUserId(array($broker), $data["user_id"], $data)))
					$status = false;
				
				return $status;
			}
		}
		
		public static function updateUserPassword($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["user_id"]) && strlen($data["password"])) {
				$data["modified_date"] = date("Y-m-d H:i:s");
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserService.updateUserPassword", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["password"] = $data["do_not_encrypt_password"] ? $data["password"] : self::getEncryptedPassword($data["password"]);
						$data["password"] = addcslashes($data["password"], "\\'");
					
						return $broker->callUpdate("module/user", "update_user_password", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$data["password"] = $data["do_not_encrypt_password"] ? $data["password"] : self::getEncryptedPassword($data["password"]);
						
						$User = $broker->callObject("module/user", "User");
						return $User->update($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$data["password"] = $data["do_not_encrypt_password"] ? $data["password"] : self::getEncryptedPassword($data["password"]);
						
						return $broker->updateObject("mu_user", array(
								"password" => $data["password"], 
								"modified_date" => $data["modified_date"]
							), array(
								"user_id" => $data["user_id"], 
							));
					}
				}
			}
		}
		
		public static function updateUserActiveStatus($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["user_id"]) && strlen($data["active"])) {
				$data["modified_date"] = date("Y-m-d H:i:s");
				$data["active"] = is_numeric($data["active"]) ? $data["active"] : 0;
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserService.updateUserActiveStatus", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callUpdate("module/user", "update_user_active_status", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$User = $broker->callObject("module/user", "User");
						return $User->update($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->updateObject("mu_user", array(
								"active" => $data["active"], 
								"modified_date" => $data["modified_date"]
							), array(
								"user_id" => $data["user_id"], 
							));
					}
				}
			}
		}
		
		public static function updateNameOfUser($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["user_id"]) && strlen($data["name"])) {
				$data["modified_date"] = date("Y-m-d H:i:s");
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = true;
						
						return $broker->callBusinessLogic("module/user", "UserService.updateNameOfUser", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($data);
						
						$data["name"] = addcslashes($data["name"], "\\'");
						
						return $broker->callUpdate("module/user", "update_name_of_user", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($data);
						
						$User = $broker->callObject("module/user", "User");
						return $User->update($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->updateObject("mu_user", array(
								"name" => $data["name"], 
								"modified_date" => $data["modified_date"]
							), array(
								"user_id" => $data["user_id"], 
							));
					}
				}
			}
		}
	
		public static function deleteUser($EVC, $user_id, $brokers = array()) {
			$status = false;
			
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
			
			if (is_array($brokers) && is_numeric($user_id)) {
				$status = AttachmentUtil::deleteFileByObject($EVC, ObjectUtil::USER_OBJECT_TYPE_ID, $user_id, null, $brokers);
				
				if ($status) {
					foreach ($brokers as $broker) {
						if (is_a($broker, "IBusinessLogicBrokerClient")) {
							$status = $broker->callBusinessLogic("module/user", "UserService.deleteUser", array("user_id" => $user_id));
							break;
						}
						else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
							$status = $broker->callDelete("module/user", "delete_user", array("user_id" => $user_id));
							break;
						}
						else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
							$User = $broker->callObject("module/user", "User");
							$status = $User->delete($user_id);
							break;
						}
						else if (is_a($broker, "IDBBrokerClient")) {
							$status = $broker->deleteObject("mu_user", array("user_id" => $user_id));
							break;
						}
					}
					
					if ($status) {
						$status = self::deleteObjectUsersByUserId(array($broker), $user_id) 
							&& self::deleteUserUserTypesByConditions(array($broker), array("user_id" => $user_id), null) 
							&& self::deleteUserEnvironmentsByConditions(array($broker), array("user_id" => $user_id), null) 
							&& self::deleteUserSessionByUserId(array($broker), $user_id) 
							&& self::deleteExternalUsersByConditions(array($broker), array("user_id" => $user_id), null);
					}
				}
			}
			
			return $status;
		}
	
		public static function getAllUsers($brokers, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => $options);
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["decode_user_data"] = true;
						return $broker->callBusinessLogic("module/user", "UserService.getAllUsers", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/user", "get_all_users", null, $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$User = $broker->callObject("module/user", "User");
						$result = $User->find(null, $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mu_user", null, null, $options);
					}
				}
			}
		}
	
		public static function countAllUsers($brokers, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/user", "UserService.countAllUsers", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/user", "count_all_users", null, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$User = $broker->callObject("module/user", "User");
						return $User->count(null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mu_user", null, array("no_cache" => $no_cache));
					}
				}
			}
		}
		
		public static function getUsersByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
				
				if ($conditions["username"])
					$conditions["username"] = strtolower($conditions["username"]);
				
				if ($conditions["email"])
					$conditions["email"] = strtolower($conditions["email"]);
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options);
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = $data["decode_user_data"] = true;
						
						return $broker->callBusinessLogic("module/user", "UserService.getUsersByConditions", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/user", "get_users_by_conditions", array("conditions" => $cond), $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$User = $broker->callObject("module/user", "User");
						$result = $User->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						$options["conditions_join"] = $conditions_join;
						$result = $broker->findObjects("mu_user", null, $conditions, $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
				}
			}
		}
	
		public static function countUsersByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers)) {
				if ($conditions["username"])
					$conditions["username"] = strtolower($conditions["username"]);
				
				if ($conditions["email"])
					$conditions["email"] = strtolower($conditions["email"]);
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache));
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = $data["decode_user_data"] = true;
						
						return $broker->callBusinessLogic("module/user", "UserService.countUsersByConditions", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/user", "count_users_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$User = $broker->callObject("module/user", "User");
						return $User->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						return $broker->countObjects("mu_user", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
					}
				}
			}
		}
		
		public static function getUsersWithUserTypesByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
				
				if ($conditions["username"])
					$conditions["username"] = strtolower($conditions["username"]);
				
				if ($conditions["email"])
					$conditions["email"] = strtolower($conditions["email"]);
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options);
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = $data["decode_user_data"] = true;
						
						return $broker->callBusinessLogic("module/user", "UserService.getUsersWithUserTypesByConditions", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/user", "get_users_with_user_types_by_conditions", array("conditions" => $cond), $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$User = $broker->callObject("module/user", "User");
						$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
						$cond = $cond ? $cond : "1=1";
						$result = $User->callSelect("get_users_with_user_types_by_conditions", array("conditions" => $cond), $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
						$cond = $cond ? $cond : "1=1";
						$sql = UserDBDAOUtil::get_users_with_user_types_by_conditions(array("conditions" => $cond));
						
						$result = $broker->getSQL($sql, $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
				}
			}
		}
	
		public static function countUsersWithUserTypesByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers)) {
				if ($conditions["username"])
					$conditions["username"] = strtolower($conditions["username"]);
				
				if ($conditions["email"])
					$conditions["email"] = strtolower($conditions["email"]);
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache));
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = $data["decode_user_data"] = true;
						
						return $broker->callBusinessLogic("module/user", "UserService.countUsersWithUserTypesByConditions", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/user", "count_users_with_user_types_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$User = $broker->callObject("module/user", "User");
						$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
						$cond = $cond ? $cond : "1=1";
						$result = $User->callSelect("count_users_with_user_types_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
						$cond = $cond ? $cond : "1=1";
						$sql = UserDBDAOUtil::count_users_with_user_types_by_conditions(array("conditions" => $cond));
						
						$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
				}
			}
		}
		
		public static function getUsersByConditionsAccordingWithUserEnvironmentsSettings($brokers, $user_environments, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			$environment_ids = self::getUserEnvironmentIds($user_environments);
			
			if ($environment_ids) {
				$users = self::getUsersWithEnvironmentsAndConditions($brokers, $environment_ids, $conditions, $conditions_join, $options, $no_cache);
				
				if (!$users)
					$users = self::getUsersWithoutEnvironmentsAndWithConditions($brokers, $conditions, $conditions_join, $options, $no_cache);
			}
			else
				$users = self::getUsersByConditions($brokers, $conditions, $conditions_join, $options, $no_cache);
			
			return $users;
		}
		
		public static function getUsersWithEnvironmentsAndConditions($brokers, $environment_ids, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers) && $environment_ids) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				$environment_ids_str = "";//just in case the user tries to hack the sql query. By default all environment_id should be numeric.
				$environment_ids = is_array($environment_ids) ? $environment_ids : array($environment_ids);
				foreach ($environment_ids as $environment_id) {
					if (is_numeric($environment_id)) 
						$environment_ids_str .= ($environment_ids_str ? ", " : "") . $environment_id;
				}
			
				if ($environment_ids_str) {
					if ($conditions["username"])
						$conditions["username"] = strtolower($conditions["username"]);
				
					if ($conditions["email"])
						$conditions["email"] = strtolower($conditions["email"]);
					
					foreach ($brokers as $broker) {
						if (is_a($broker, "IBusinessLogicBrokerClient")) {
							$data = array("environment_ids" => $environment_ids, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options);
							if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = $data["decode_user_data"] = true;
							
							return $broker->callBusinessLogic("module/user", "UserService.getUsersWithEnvironmentsAndConditions", $data, $options);
						}
						else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
							self::encodeSensitiveUserData($conditions);
						
							$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
							$cond = $cond ? $cond : "1=1";
							$result = $broker->callSelect("module/user", "get_users_with_environments_and_conditions", array("environment_ids" => $environment_ids_str, "conditions" => $cond), $options);
							self::decodeSensitiveUsersData($result);
							return $result;
						}
						else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
							self::encodeSensitiveUserData($conditions);
						
							$User = $broker->callObject("module/user", "User");
							$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
							$cond = $cond ? $cond : "1=1";
							$result = $User->callSelect("get_users_with_environments_and_conditions", array("environment_ids" => $environment_ids_str, "conditions" => $cond), $options);
							self::decodeSensitiveUsersData($result);
							return $result;
						}
						else if (is_a($broker, "IDBBrokerClient")) {
							self::encodeSensitiveUserData($conditions);
							$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
							$cond = $cond ? $cond : "1=1";
							$sql = UserDBDAOUtil::get_users_with_environments_and_conditions(array("environment_ids" => $environment_ids_str, "conditions" => $cond));
							
							$result = $broker->getSQL($sql, $options);
							self::decodeSensitiveUsersData($result);
							return $result;
						}
					}
				}
			}
		}
		
		public static function getUsersWithoutEnvironmentsAndWithConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				if ($conditions["username"])
					$conditions["username"] = strtolower($conditions["username"]);
				
				if ($conditions["email"])
					$conditions["email"] = strtolower($conditions["email"]);
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options);
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = $data["decode_user_data"] = true;
						
						return $broker->callBusinessLogic("module/user", "UserService.getUsersWithoutEnvironmentsAndWithConditions", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/user", "get_users_without_environments_and_with_conditions", array("conditions" => $cond), $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$User = $broker->callObject("module/user", "User");
						$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
						$cond = $cond ? $cond : "1=1";
						$result = $User->callSelect("get_users_without_environments_and_with_conditions", array("conditions" => $cond), $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
						$cond = $cond ? $cond : "1=1";
						$sql = UserDBDAOUtil::get_users_without_environments_and_with_conditions(array("conditions" => $cond));
						
						$result = $broker->getSQL($sql, $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
				}
			}
		}
	
		public static function getUsersByUserTypesAndConditions($brokers, $user_type_ids, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers) && $user_type_ids) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				$user_type_ids_str = "";//just in case the user tries to hack the sql query. By default all user_type_id should be numeric.
				$user_type_ids = is_array($user_type_ids) ? $user_type_ids : array($user_type_ids);
				foreach ($user_type_ids as $user_type_id) {
					if (is_numeric($user_type_id)) 
						$user_type_ids_str .= ($user_type_ids_str ? ", " : "") . $user_type_id;
				}
			
				if ($user_type_ids_str) {
					if ($conditions["username"])
						$conditions["username"] = strtolower($conditions["username"]);
				
					if ($conditions["email"])
						$conditions["email"] = strtolower($conditions["email"]);
				
					foreach ($brokers as $broker) {
						if (is_a($broker, "IBusinessLogicBrokerClient")) {
							$data = array("user_type_ids" => $user_type_ids, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options);
							if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = $data["decode_user_data"] = true;
							
							return $broker->callBusinessLogic("module/user", "UserService.getUsersByUserTypesAndConditions", $data, $options);
						}
						else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
							self::encodeSensitiveUserData($conditions);
						
							$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
							$cond = $cond ? $cond : "1=1";
							$result = $broker->callSelect("module/user", "get_users_by_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "conditions" => $cond), $options);
							self::decodeSensitiveUsersData($result);
							return $result;
						}
						else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
							self::encodeSensitiveUserData($conditions);
						
							$User = $broker->callObject("module/user", "User");
							$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
							$cond = $cond ? $cond : "1=1";
							$result = $User->callSelect("get_users_by_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "conditions" => $cond), $options);
							self::decodeSensitiveUsersData($result);
							return $result;
						}
						else if (is_a($broker, "IDBBrokerClient")) {
							self::encodeSensitiveUserData($conditions);
							$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
							$cond = $cond ? $cond : "1=1";
							$sql = UserDBDAOUtil::get_users_by_user_types_and_conditions(array("user_type_ids" => $user_type_ids_str, "conditions" => $cond));
							
							$result = $broker->getSQL($sql, $options);
							self::decodeSensitiveUsersData($result);
							return $result;
						}
					}
				}
			}
		}
	
		public static function countUsersByUserTypesAndConditions($brokers, $user_type_ids, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers) && $user_type_ids) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				$user_type_ids_str = "";//just in case the user tries to hack the sql query. By default all user_type_id should be numeric.
				$user_type_ids = is_array($user_type_ids) ? $user_type_ids : array($user_type_ids);
				foreach ($user_type_ids as $user_type_id) {
					if (is_numeric($user_type_id)) 
						$user_type_ids_str .= ($user_type_ids_str ? ", " : "") . $user_type_id;
				}
			
				if ($user_type_ids_str) {
					if ($conditions["username"])
						$conditions["username"] = strtolower($conditions["username"]);
				
					if ($conditions["email"])
						$conditions["email"] = strtolower($conditions["email"]);
				
					foreach ($brokers as $broker) {
						if (is_a($broker, "IBusinessLogicBrokerClient")) {
							$data = array("user_type_ids" => $user_type_ids, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options);
							if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = $data["decode_user_data"] = true;
							
							return $broker->callBusinessLogic("module/user", "UserService.countUsersByUserTypesAndConditions", $data, $options);
						}
						else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
							self::encodeSensitiveUserData($conditions);
						
							$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
							$cond = $cond ? $cond : "1=1";
							$result = $broker->callSelect("module/user", "count_users_by_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "conditions" => $cond), $options);
							return $result[0]["total"];
						}
						else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
							self::encodeSensitiveUserData($conditions);
						
							$User = $broker->callObject("module/user", "User");
							$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
							$cond = $cond ? $cond : "1=1";
							$result = $User->callSelect("count_users_by_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "conditions" => $cond), $options);
							return $result[0]["total"];
						}
						else if (is_a($broker, "IDBBrokerClient")) {
							self::encodeSensitiveUserData($conditions);
							$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
							$cond = $cond ? $cond : "1=1";
							$sql = UserDBDAOUtil::count_users_by_user_types_and_conditions(array("user_type_ids" => $user_type_ids_str, "conditions" => $cond));
							
							$result = $broker->getSQL($sql, $options);
							return $result[0]["total"];
						}
					}
				}
			}
		}
		
		public static function getUsersByObjectAndConditions($brokers, $object_type_id, $object_id, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				if ($conditions["username"])
					$conditions["username"] = strtolower($conditions["username"]);
				
				if ($conditions["email"])
					$conditions["email"] = strtolower($conditions["email"]);
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options);
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = $data["decode_user_data"] = true;
						
						return $broker->callBusinessLogic("module/user", "UserService.getUsersByObjectAndConditions", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/user", "get_users_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$User = $broker->callObject("module/user", "User");
						$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
						$cond = $cond ? $cond : "1=1";
						$result = $User->callSelect("get_users_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
						$cond = $cond ? $cond : "1=1";
						$sql = UserDBDAOUtil::get_users_by_object_and_conditions(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
						
						$result = $broker->getSQL($sql, $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
				}
			}
		}
		
		public static function countUsersByObjectAndConditions($brokers, $object_type_id, $object_id, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				if ($conditions["username"])
					$conditions["username"] = strtolower($conditions["username"]);
				
				if ($conditions["email"])
					$conditions["email"] = strtolower($conditions["email"]);
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options);
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = $data["decode_user_data"] = true;
						
						return $broker->callBusinessLogic("module/user", "UserService.countUsersByObjectAndConditions", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/user", "count_users_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$User = $broker->callObject("module/user", "User");
						$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
						$cond = $cond ? $cond : "1=1";
						$result = $User->callSelect("count_users_by_object_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
						return $result[0]["total"];
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
						$cond = $cond ? $cond : "1=1";
						$sql = UserDBDAOUtil::count_users_by_object_and_conditions(array("object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
						
						$result = $broker->getSQL($sql, $options);
						return $result[0]["total"];
					}
				}
			}
		}
		
		public static function getUsersByObjectGroupAndConditions($brokers, $object_type_id, $object_id, $group = null, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				if ($conditions["username"])
					$conditions["username"] = strtolower($conditions["username"]);
				
				if ($conditions["email"])
					$conditions["email"] = strtolower($conditions["email"]);
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$group = is_numeric($group) ? $group : null;
						$data = array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options);
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = $data["decode_user_data"] = true;
						
						return $broker->callBusinessLogic("module/user", "UserService.getUsersByObjectGroupAndConditions", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$group = is_numeric($group) ? $group : 0;
						
						$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/user", "get_users_by_object_group_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$group = is_numeric($group) ? $group : 0;
						
						$User = $broker->callObject("module/user", "User");
						$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
						$cond = $cond ? $cond : "1=1";
						$result = $User->callSelect("get_users_by_object_group_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						$group = is_numeric($group) ? $group : 0;
						$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
						$cond = $cond ? $cond : "1=1";
						$sql = UserDBDAOUtil::get_users_by_object_group_and_conditions(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond));
						
						$result = $broker->getSQL($sql, $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
				}
			}
		}
		
		public static function countUsersByObjectGroupAndConditions($brokers, $object_type_id, $object_id, $group = null, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				if ($conditions["username"])
					$conditions["username"] = strtolower($conditions["username"]);
				
				if ($conditions["email"])
					$conditions["email"] = strtolower($conditions["email"]);
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$group = is_numeric($group) ? $group : null;
						$data = array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options);
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = $data["decode_user_data"] = true;
						
						return $broker->callBusinessLogic("module/user", "UserService.countUsersByObjectGroupAndConditions", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$group = is_numeric($group) ? $group : 0;
						
						$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/user", "count_users_by_object_group_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$group = is_numeric($group) ? $group : 0;
						
						$User = $broker->callObject("module/user", "User");
						$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
						$cond = $cond ? $cond : "1=1";
						$result = $User->callSelect("count_users_by_object_group_and_conditions", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
						return $result[0]["total"];
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						$group = is_numeric($group) ? $group : 0;
						$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
						$cond = $cond ? $cond : "1=1";
						$sql = UserDBDAOUtil::count_users_by_object_group_and_conditions(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond));
						
						$result = $broker->getSQL($sql, $options);
						return $result[0]["total"];
					}
				}
			}
		}
		
		public static function getUsersByObjectAndUserTypesAndConditions($brokers, $object_type_id, $object_id, $user_type_ids, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id) && $user_type_ids) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				$user_type_ids_str = "";//just in case the user tries to hack the sql query. By default all user_type_id should be numeric.
				$user_type_ids = is_array($user_type_ids) ? $user_type_ids : array($user_type_ids);
				foreach ($user_type_ids as $user_type_id) {
					if (is_numeric($user_type_id)) 
						$user_type_ids_str .= ($user_type_ids_str ? ", " : "") . $user_type_id;
				}
			
				if ($user_type_ids_str) {
					if ($conditions["username"])
						$conditions["username"] = strtolower($conditions["username"]);
				
					if ($conditions["email"])
						$conditions["email"] = strtolower($conditions["email"]);
				
					foreach ($brokers as $broker) {
						if (is_a($broker, "IBusinessLogicBrokerClient")) {
							$data = array("user_type_ids" => $user_type_ids, "object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options);
							if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = $data["decode_user_data"] = true;
							
							return $broker->callBusinessLogic("module/user", "UserService.getUsersByObjectAndUserTypesAndConditions", $data, $options);
						}
						else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
							self::encodeSensitiveUserData($conditions);
						
							$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
							$cond = $cond ? $cond : "1=1";
							$result = $broker->callSelect("module/user", "get_users_by_object_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
							self::decodeSensitiveUsersData($result);
							return $result;
						}
						else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
							self::encodeSensitiveUserData($conditions);
						
							$User = $broker->callObject("module/user", "User");
							$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
							$cond = $cond ? $cond : "1=1";
							$result = $User->callSelect("get_users_by_object_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
							self::decodeSensitiveUsersData($result);
							return $result;
						}
						else if (is_a($broker, "IDBBrokerClient")) {
							self::encodeSensitiveUserData($conditions);
							$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
							$cond = $cond ? $cond : "1=1";
							$sql = UserDBDAOUtil::get_users_by_object_and_user_types_and_conditions(array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
							
							$result = $broker->getSQL($sql, $options);
							self::decodeSensitiveUsersData($result);
							return $result;
						}
					}
				}
			}
		}
		
		public static function countUsersByObjectAndUserTypesAndConditions($brokers, $object_type_id, $object_id, $user_type_ids, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id) && $user_type_ids) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				$user_type_ids_str = "";//just in case the user tries to hack the sql query. By default all user_type_id should be numeric.
				$user_type_ids = is_array($user_type_ids) ? $user_type_ids : array($user_type_ids);
				foreach ($user_type_ids as $user_type_id) {
					if (is_numeric($user_type_id)) 
						$user_type_ids_str .= ($user_type_ids_str ? ", " : "") . $user_type_id;
				}
			
				if ($user_type_ids_str) {
					if ($conditions["username"])
						$conditions["username"] = strtolower($conditions["username"]);
				
					if ($conditions["email"])
						$conditions["email"] = strtolower($conditions["email"]);
				
					foreach ($brokers as $broker) {
						if (is_a($broker, "IBusinessLogicBrokerClient")) {
							$data = array("user_type_ids" => $user_type_ids, "object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options);
							if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = $data["decode_user_data"] = true;
							
							return $broker->callBusinessLogic("module/user", "UserService.countUsersByObjectAndUserTypesAndConditions", $data, $options);
						}
						else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
							self::encodeSensitiveUserData($conditions);
						
							$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
							$cond = $cond ? $cond : "1=1";
							$result = $broker->callSelect("module/user", "count_users_by_object_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
							return $result[0]["total"];
						}
						else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
							self::encodeSensitiveUserData($conditions);
						
							$User = $broker->callObject("module/user", "User");
							$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
							$cond = $cond ? $cond : "1=1";
							$result = $User->callSelect("count_users_by_object_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond), $options);
							return $result[0]["total"];
						}
						else if (is_a($broker, "IDBBrokerClient")) {
							self::encodeSensitiveUserData($conditions);
							$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
							$cond = $cond ? $cond : "1=1";
							$sql = UserDBDAOUtil::count_users_by_object_and_user_types_and_conditions(array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "conditions" => $cond));
							
							$result = $broker->getSQL($sql, $options);
							return $result[0]["total"];
						}
					}
				}
			}
		}
		
		public static function getUsersByObjectGroupAndUserTypesAndConditions($brokers, $object_type_id, $object_id, $group = null, $user_type_ids, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id) && $user_type_ids) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				$user_type_ids_str = "";//just in case the user tries to hack the sql query. By default all user_type_id should be numeric.
				$user_type_ids = is_array($user_type_ids) ? $user_type_ids : array($user_type_ids);
				foreach ($user_type_ids as $user_type_id) {
					if (is_numeric($user_type_id)) 
						$user_type_ids_str .= ($user_type_ids_str ? ", " : "") . $user_type_id;
				}
			
				if ($user_type_ids_str) {
					if ($conditions["username"])
						$conditions["username"] = strtolower($conditions["username"]);
				
					if ($conditions["email"])
						$conditions["email"] = strtolower($conditions["email"]);
				
					foreach ($brokers as $broker) {
						if (is_a($broker, "IBusinessLogicBrokerClient")) {
							$group = is_numeric($group) ? $group : null;
							$data = array("user_type_ids" => $user_type_ids, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options);
							if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = $data["decode_user_data"] = true;
							
							return $broker->callBusinessLogic("module/user", "UserService.getUsersByObjectGroupAndUserTypesAndConditions", $data, $options);
						}
						else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
							self::encodeSensitiveUserData($conditions);
						
							$group = is_numeric($group) ? $group : 0;
							
							$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
							$cond = $cond ? $cond : "1=1";
							$result = $broker->callSelect("module/user", "get_users_by_object_group_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
							self::decodeSensitiveUsersData($result);
							return $result;
						}
						else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
							self::encodeSensitiveUserData($conditions);
						
							$group = is_numeric($group) ? $group : 0;
							
							$User = $broker->callObject("module/user", "User");
							$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
							$cond = $cond ? $cond : "1=1";
							$result = $User->callSelect("get_users_by_object_group_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
							self::decodeSensitiveUsersData($result);
							return $result;
						}
						else if (is_a($broker, "IDBBrokerClient")) {
							self::encodeSensitiveUserData($conditions);
							$group = is_numeric($group) ? $group : 0;
							$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
							$cond = $cond ? $cond : "1=1";
							$sql = UserDBDAOUtil::get_users_by_object_group_and_user_types_and_conditions(array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond));
							
							$result = $broker->getSQL($sql, $options);
							self::decodeSensitiveUsersData($result);
							return $result;
						}
					}
				}
			}
		}
		
		public static function countUsersByObjectGroupAndUserTypesAndConditions($brokers, $object_type_id, $object_id, $group = null, $user_type_ids, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id) && $user_type_ids) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				$user_type_ids_str = "";//just in case the user tries to hack the sql query. By default all user_type_id should be numeric.
				$user_type_ids = is_array($user_type_ids) ? $user_type_ids : array($user_type_ids);
				foreach ($user_type_ids as $user_type_id) {
					if (is_numeric($user_type_id)) 
						$user_type_ids_str .= ($user_type_ids_str ? ", " : "") . $user_type_id;
				}
			
				if ($user_type_ids_str) {
					if ($conditions["username"])
						$conditions["username"] = strtolower($conditions["username"]);
				
					if ($conditions["email"])
						$conditions["email"] = strtolower($conditions["email"]);
				
					foreach ($brokers as $broker) {
						if (is_a($broker, "IBusinessLogicBrokerClient")) {
							$group = is_numeric($group) ? $group : null;
							$data = array("user_type_ids" => $user_type_ids, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options);
							if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = $data["decode_user_data"] = true;
							
							return $broker->callBusinessLogic("module/user", "UserService.countUsersByObjectGroupAndUserTypesAndConditions", $data, $options);
						}
						else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
							self::encodeSensitiveUserData($conditions);
						
							$group = is_numeric($group) ? $group : 0;
							
							$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
							$cond = $cond ? $cond : "1=1";
							$result = $broker->callSelect("module/user", "count_users_by_object_group_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
							return $result[0]["total"];
						}
						else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
							self::encodeSensitiveUserData($conditions);
						
							$group = is_numeric($group) ? $group : 0;
							
							$User = $broker->callObject("module/user", "User");
							$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
							$cond = $cond ? $cond : "1=1";
							$result = $User->callSelect("count_users_by_object_group_and_user_types_and_conditions", array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond), $options);
							return $result[0]["total"];
						}
						else if (is_a($broker, "IDBBrokerClient")) {
							self::encodeSensitiveUserData($conditions);
							$group = is_numeric($group) ? $group : 0;
							$cond = DB::getSQLConditions($conditions, $conditions_join, "u");
							$cond = $cond ? $cond : "1=1";
							$sql = UserDBDAOUtil::count_users_by_object_group_and_user_types_and_conditions(array("user_type_ids" => $user_type_ids_str, "object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "conditions" => $cond));
							
							$result = $broker->getSQL($sql, $options);
							return $result[0]["total"];
						}
					}
				}
			}
		}
	
		/* USER USER TYPE FUNCTIONS */
	
		public static function insertUserUserType($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["user_id"]) && is_numeric($data["user_type_id"])) {
				$data["created_date"] = date("Y-m-d H:i:s");
				$data["modified_date"] = $data["created_date"];
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserUserTypeService.insertUserUserType", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callInsert("module/user", "insert_user_user_type", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserUserType = $broker->callObject("module/user", "UserUserType");
						return $UserUserType->insert($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->insertObject("mu_user_user_type", array(
							"user_id" => $data["user_id"], 
							"user_type_id" => $data["user_type_id"], 
							"created_date" => $data["created_date"], 
							"modified_date" => $data["modified_date"]
						));
					}
				}
			}
		}
	
		public static function updateUserUserType($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["old_user_id"]) && is_numeric($data["old_user_type_id"]) && is_numeric($data["new_user_id"]) && is_numeric($data["new_user_type_id"])) {
				$data["modified_date"] = date("Y-m-d H:i:s");
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserUserTypeService.updateUserUserType", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callUpdate("module/user", "update_user_user_type", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserUserType = $broker->callObject("module/user", "UserUserType");
						return $UserUserType->update($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->updateObject("mu_user_user_type", array(
								"user_id" => $data["new_user_id"], 
								"user_type_id" => $data["new_user_type_id"], 
								"modified_date" => $data["modified_date"]
							), array(
								"user_id" => $data["old_user_id"], 
								"user_type_id" => $data["old_user_type_id"] 
							));
					}
				}
			}
		}
	
		public static function deleteUserUserType($brokers, $user_id, $user_type_id) {
			if (is_array($brokers) && is_numeric($user_id) && is_numeric($user_type_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserUserTypeService.deleteUserUserType", array("user_id" => $user_id, "user_type_id" => $user_type_id));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/user", "delete_user_user_type", array("user_id" => $user_id, "user_type_id" => $user_type_id));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserUserType = $broker->callObject("module/user", "UserUserType");
						$conditions = array("user_id" => $user_id, "user_type_id" => $user_type_id);
						return $UserUserType->deleteByConditions(array("conditions" => $conditions));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mu_user_user_type", array("user_id" => $user_id, "user_type_id" => $user_type_id));
					}
				}
			}
		}
	
		public static function deleteUserUserTypesByConditions($brokers, $conditions, $conditions_join) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserUserTypeService.deleteUserUserTypesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						return $broker->callDelete("module/user", "delete_user_user_types_by_conditions", array("conditions" => $cond));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserUserType = $broker->callObject("module/user", "UserUserType");
						return $UserUserType->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $conditions_join));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mu_user_user_type", $conditions, array("conditions_join" => $conditions_join));
					}
				}
			}
		}
	
		public static function getAllUserUserTypes($brokers, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/user", "UserUserTypeService.getAllUserUserTypes", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/user", "get_all_user_user_types", null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserUserType = $broker->callObject("module/user", "UserUserType");
						return $UserUserType->find(null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mu_user_user_type", null, null, array("no_cache" => $no_cache));
					}
				}
			}
		}
	
		public static function countAllUserUserTypes($brokers, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/user", "UserUserTypeService.countAllUserUserTypes", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/user", "count_all_user_user_types", null, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserUserType = $broker->callObject("module/user", "UserUserType");
						return $UserUserType->count(null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mu_user_user_type", null, array("no_cache" => $no_cache));
					}
				}
			}
		}
	
		public static function getUserUserTypesByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserUserTypeService.getUserUserTypesByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						return $broker->callSelect("module/user", "get_user_user_types_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserUserType = $broker->callObject("module/user", "UserUserType");
						return $UserUserType->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mu_user_user_type", null, $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
					}
				}
			}
		}
		
		/* USER SESSION FUNCTIONS */
		
		//Get the client ip address
		private static function getClientIP() {
			$ip = '';
			
			if ($_SERVER['HTTP_CLIENT_IP'])
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			else if($_SERVER['HTTP_X_FORWARDED_FOR'])
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			else if($_SERVER['HTTP_X_FORWARDED'])
				$ip = $_SERVER['HTTP_X_FORWARDED'];
			else if($_SERVER['HTTP_FORWARDED_FOR'])
				$ip = $_SERVER['HTTP_FORWARDED_FOR'];
			else if($_SERVER['HTTP_FORWARDED'])
				$ip = $_SERVER['HTTP_FORWARDED'];
			else if($_SERVER['REMOTE_ADDR'])
				$ip = $_SERVER['REMOTE_ADDR'];
			else
				$ip = 'UNKNOWN';

			return $ip;
		}
		
		public static function isSameReferer() {
			$referer_domain = $_SERVER["HTTP_REFERER"] ? strtolower(parse_url($_SERVER["HTTP_REFERER"], PHP_URL_HOST)) : null;
			$request_host = explode(":", $_SERVER["HTTP_HOST"]);
			$request_host = $request_host[0];
			return $referer_domain && $referer_domain == strtolower($request_host);
		}
		
		public static function isLoggedIn($brokers, $session_id, $expired_time = false, $no_cache = false) {
			if ($session_id) {
				$status = true;
				
				//check HTTP Referrer bc of CSRF attacks
				if (self::getConstantVariable("USER_SESSION_AUTHENTICATION_RESTRICTED_TO_SAME_REFERER_HOST"))
					$status = self::isSameReferer();
				
				if ($status) {
					$user_session = self::getUserSessionsByConditions($brokers, array("session_id" => $session_id), null, null, $no_cache);
					$user_session = $user_session[0];
					$expired_time = $expired_time ? $expired_time : self::getConstantVariable("DEFAULT_USER_SESSION_EXPIRATION_TTL");
					
					if ($user_session["logged_status"] && $user_session["login_time"] + $expired_time > time()) {
						$user_session_control_methods = self::getConstantVariable("USER_SESSION_CONTROL_METHODS");
						$user_session_control_var_name = self::getConstantVariable("USER_SESSION_CONTROL_VARIABLE_NAME");
						$user_session_control_encryption_key = CryptoKeyHandler::hexToBin( self::getConstantVariable("USER_SESSION_CONTROL_ENCRYPTION_KEY_HEX") );
						$user_session_control_expired_time = self::getConstantVariable("USER_SESSION_CONTROL_EXPIRED_TIME");
						$extra_flags = self::getConstantVariable("USER_SESSION_COOKIES_EXTRA_FLAGS");
						
						//code against xss and csfr attacks
						if (in_array(strtolower($_SERVER['REQUEST_METHOD']), $user_session_control_methods)) {
							//check session control variable and renew the expiration time. this is very important bc of xss and csfr attacks.
							$user_session_control = $_COOKIE[$user_session_control_var_name];
							if ($user_session_control) {
								$user_session_control = CryptoKeyHandler::hexToBin($user_session_control);
								$user_session_control = CryptoKeyHandler::decryptText($user_session_control, $user_session_control_encryption_key);
							}
							
							if (is_numeric($user_session_control) && $user_session_control >= time()) {
								//renew expiration time of the session control bc of xss and csfr attacks
								$ttl = CryptoKeyHandler::encryptText( time() + $user_session_control_expired_time, $user_session_control_encryption_key );
								$ttl = CryptoKeyHandler::binToHex($ttl);
								CookieHandler::setSafeCookie($user_session_control_var_name, $ttl, 0, "/", $extra_flags);
								
								//error_log("$user_session_control => ". (time() + $user_session_control_expired_time) ."\n", 3, $GLOBALS["log_file_path"] ? $GLOBALS["log_file_path"] : "/var/www/html/livingroop/default/tmp/phpframework.log");
								
								return $user_session;
							}
						}
						else {
							//renew expiration time of the session control bc of xss and csfr attacks
							$ttl = CryptoKeyHandler::encryptText( time() + $user_session_control_expired_time, $user_session_control_encryption_key );
							$ttl = CryptoKeyHandler::binToHex($ttl);
							CookieHandler::setSafeCookie($user_session_control_var_name, $ttl, 0, "/", $extra_flags);
							
							return $user_session;
						}
					}
				}
			}
		
			return false;
		}
		
		public static function login($brokers, $username, $password, $captcha = false, $expired_time = false, $settings = false, $no_cache = false) {
			if ($username && $password) {
				$username = strtolower($username);
				
				$user_sessions = self::getUserSessionsByConditionsAccordingWithUserEnvironmentsSettings($brokers, $settings["user_environments"], array("username" => $username), null, null, $no_cache);
				$user_session = $user_sessions[0] ? $user_sessions[0]: array();
				
				if ($user_session["username"]) {
					$expired_time = $expired_time ? $expired_time : self::getConstantVariable("DEFAULT_USER_SESSION_EXPIRATION_TTL");
					
					if ($settings["maximum_login_attempts_to_block_user"] && $user_session["failed_login_attempts"] >= $settings["maximum_login_attempts_to_block_user"] && $user_session["failed_login_time"] + $expired_time > time())
						return self::USER_BLOCKED;
					else if ($settings["show_captcha"] && $user_session["failed_login_attempts"] >= $settings["maximum_login_attempts_to_show_captcha"] && (!$captcha || $captcha != $user_session["captcha"]))
						return self::WRONG_CAPTCHA;
				}
				
				$users = \UserUtil::getUsersByConditionsAccordingWithUserEnvironmentsSettings($brokers, $settings["user_environments"], array("username" => $username), null, null, $no_cache);
				
				/*
				 * Note that you can only have 2 types of DBs:
				 * - a DB with repeated usernames, but where each user_id is related with diferent objects
				 * - a DB with non repeated usernames. In this case it doesn't matter if the user is related with objects.
				 * We cannot have a DB with repeated usernames where at least 1 of the users is not related with any objects, because this will means that the variable $users will have more than 1 items and will return the error: self::DUPLICATED_USERNAME
				*/
				if ($users && count($users) > 1)
					return self::DUPLICATED_USERNAME;
				
				$user = $users[0];
				
				if ($user && $user["active"] == 2)
					return self::INACTIVE_USERNAME;
				
				//verify password
				if ($user && !self::validatePassword($user["password"], $password, $settings["do_not_encrypt_password"]))
					$user = null;
				
				return self::createLoginSession($brokers, $user_session, $user, $username, $settings);
			}
			return false;
		}
		
		public static function externalLogin($brokers, $external_user_id, $expired_time = false, $settings = false, $no_cache = false) {
			if ($external_user_id) {
				$external_user = self::getExternalUser($brokers, $external_user_id, $no_cache);
				
				if ($external_user) {
					$username = $external_user["social_network_type"] . "|" . $external_user["social_network_user_id"] . "|" . $external_user["external_user_id"];
					$username = strtolower($username);
					
					$user_sessions = self::getUserSessionsByConditionsAccordingWithUserEnvironmentsSettings($brokers, $settings["user_environments"], array("username" => $username), null, null, $no_cache);
					$user_session = $user_sessions[0] ? $user_sessions[0]: array();
					
					if ($user_session["username"]) {
						$expired_time = $expired_time ? $expired_time : self::getConstantVariable("DEFAULT_USER_SESSION_EXPIRATION_TTL");
						
						if ($settings["maximum_login_attempts_to_block_user"] && $user_session["failed_login_attempts"] >= $settings["maximum_login_attempts_to_block_user"] && $user_session["failed_login_time"] + $expired_time > time())
							return self::USER_BLOCKED;
					}
					
					$user = self::getUsersByConditions($brokers, array("user_id" => $external_user["user_id"]), null, null, $no_cache);
					$user = $user[0];
					
					return self::createLoginSession($brokers, $user_session, $user, $username, $settings);
				}
			}
			return false;
		}
		
		public static function createLoginSession($brokers, $user_session, $user, $username, $settings = false) {
			if (!$user) {
				$user_session["logged_status"] = 0;
				$user_session["failed_login_attempts"]++;
				$user_session["failed_login_time"] = time();
				$user_session["failed_login_ip"] = substr(self::getClientIP(), 0, 100);
			}
			else {
				$user_session["user_id"] = $user["user_id"];
				$user_session["logged_status"] = 1;
				$user_session["login_time"] = time();
				$user_session["login_ip"] = substr(self::getClientIP(), 0, 100);
				$user_session["failed_login_attempts"] = 0;//reset bc the login is successfull.
			
				if ($user["active"] == 0)
					self::updateUserActiveStatus($brokers, array("user_id" => $user["user_id"], "active" => 1));
			}
			
			$environment_ids = self::getUserEnvironmentIds($settings["user_environments"]);
			$environment_id = $user_session["environment_id"] ? $user_session["environment_id"] : $environment_ids[0];
			$environment_id = $environment_id ? $environment_id : 0;
			
			if (empty($user_session["session_id"])) {
				$user_session["session_id"] = md5($username . "_" . $environment_id) . CryptoKeyHandler::getHexKey();
				$user_session["session_id"] = substr($user_session["session_id"], 0, 200);
			}
		
			if ($user_session["username"])
				self::updateUserSession($brokers, $user_session);
			else {
				$user_session["username"] = $username;
				$user_session["environment_id"] = $environment_id;
				self::insertUserSession($brokers, $user_session);
			}
			
			return $user_session;
		}
		
		public static function validatePassword($user_password, $password_to_check, $do_not_encrypt_password) {
			if ($do_not_encrypt_password && hash_equals($user_password, $password_to_check)) //first must be $user["password"] and then $password. User string must be the 2nd argument in the hash_equals function.
				return true;
			else if (!$do_not_encrypt_password && password_verify(md5($password_to_check), $user_password)) //first must be $password and then $user["password"]. User string must be the 1st argument in the password_verify function.
				return true;
			
			return false;
		}
		
		public static function logout($brokers, $session_id) {
			//logout session from DB
			$data = array(
				"session_id" => $session_id,
				"logout_time" => time(),
				"logout_ip" => substr(self::getClientIP(), 0, 100),
			);
			
			return self::logoutBySessionId($brokers, $data);
		}
	
		public static function insertUserSession($brokers, $data) {
			if (is_array($brokers) && $data["username"]) {
				$data["environment_id"] = $data["environment_id"] ? $data["environment_id"] : 0;
				$data["created_date"] = date("Y-m-d H:i:s");
				$data["modified_date"] = $data["created_date"];
				$data["username"] = strtolower($data["username"]);
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = true;
							
						return $broker->callBusinessLogic("module/user", "UserSessionService.insertUserSession", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($data);
						
						$data["username"] = addcslashes($data["username"], "\\'");
						$data["session_id"] = addcslashes($data["session_id"], "\\'");
						$data["logged_status"] = is_numeric($data["logged_status"]) ? $data["logged_status"] : 0;
						$data["login_time"] = is_numeric($data["login_time"]) ? $data["login_time"] : 0;
						$data["login_ip"] = addcslashes($data["login_ip"], "\\'");
						$data["failed_login_attempts"] = is_numeric($data["failed_login_attempts"]) ? $data["failed_login_attempts"] : 0;
						$data["failed_login_time"] = is_numeric($data["failed_login_time"]) ? $data["failed_login_time"] : 0;
						$data["failed_login_ip"] = addcslashes($data["failed_login_ip"], "\\'");
			
					
						return $broker->callInsert("module/user", "insert_user_session", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($data);
						
						$UserSession = $broker->callObject("module/user", "UserSession");
						return $UserSession->insert($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($data);
						
						return $broker->insertObject("mu_user_session", array(
							"username" => $data["username"], 
							"environment_id" => $data["environment_id"], 
							"session_id" => $data["session_id"], 
							"user_id" => $data["user_id"], 
							"logged_status" => $data["logged_status"], 
							"login_time" => $data["login_time"], 
							"login_ip" => $data["login_ip"], 
							"failed_login_attempts" => $data["failed_login_attempts"], 
							"failed_login_time" => $data["failed_login_time"], 
							"failed_login_ip" => $data["failed_login_ip"], 
							"created_date" => $data["created_date"], 
							"modified_date" => $data["modified_date"]
						));
					}
				}
			}
		}
	
		public static function updateUserSession($brokers, $data) {
			if (is_array($brokers) && $data["username"]) {
				$data["environment_id"] = $data["environment_id"] ? $data["environment_id"] : 0;
				$data["modified_date"] = date("Y-m-d H:i:s");
				$data["username"] = strtolower($data["username"]);
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = true;
						
						return $broker->callBusinessLogic("module/user", "UserSessionService.updateUserSession", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($data);
						
						$data["username"] = addcslashes($data["username"], "\\'");
						$data["session_id"] = addcslashes($data["session_id"], "\\'");
						$data["logged_status"] = is_numeric($data["logged_status"]) ? $data["logged_status"] : 0;
						$data["login_time"] = is_numeric($data["login_time"]) ? $data["login_time"] : 0;
						$data["login_ip"] = addcslashes($data["login_ip"], "\\'");
						$data["failed_login_attempts"] = is_numeric($data["failed_login_attempts"]) ? $data["failed_login_attempts"] : 0;
						$data["failed_login_time"] = is_numeric($data["failed_login_time"]) ? $data["failed_login_time"] : 0;
						$data["failed_login_ip"] = addcslashes($data["failed_login_ip"], "\\'");
			
					
						return $broker->callUpdate("module/user", "update_user_session", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($data);
						
						$UserSession = $broker->callObject("module/user", "UserSession");
						return $UserSession->update($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($data);
						
						return $broker->updateObject("mu_user_session", array(
								"session_id" => $data["session_id"], 
								"user_id" => $data["user_id"], 
								"logged_status" => $data["logged_status"], 
								"login_time" => $data["login_time"], 
								"login_ip" => $data["login_ip"], 
								"failed_login_attempts" => $data["failed_login_attempts"], 
								"failed_login_time" => $data["failed_login_time"], 
								"failed_login_ip" => $data["failed_login_ip"], 
								"modified_date" => $data["modified_date"]
							), array(
								"username" => $data["username"], 
								"environment_id" => $data["environment_id"]
							));
					}
				}
			}
		}
	
		public static function updateUserSessionCaptchaBySessionId($brokers, $data) {
			if (is_array($brokers) && $data["session_id"]) {
				$data["modified_date"] = date("Y-m-d H:i:s");
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserSessionService.updateUserSessionCaptchaBySessionId", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["session_id"] = addcslashes($data["session_id"], "\\'");
						$data["captcha"] = addcslashes($data["captcha"], "\\'");
					
						return $broker->callUpdate("module/user", "update_user_session_captcha_by_session_id", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserSession = $broker->callObject("module/user", "UserSession");
						$attributes = array("captcha" => $data["captcha"], "modified_date" => $data["modified_date"]);
						$conditions = array("session_id" => $data["session_id"]);
						return $UserSession->updateByConditions(array("attributes" => $attributes, "conditions" => $conditions));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->updateObject("mu_user_session", array(
								"captcha" => $data["captcha"], 
								"modified_date" => $data["modified_date"]
							), array(
								"session_id" => $data["session_id"]
							));
					}
				}
			}
		}
		
		public static function changeUserSessionUsernameByUsername($brokers, $settings, $old_username, $new_username) {
			$old_username = strtolower($old_username);
			$new_username = strtolower($new_username);
				
			//Delete sessions from new_username. There should not exist any sessions, but just in case we remove them anyways...
			$user_sessions = self::getUserSessionsByConditionsAccordingWithUserEnvironmentsSettings($brokers, $settings["user_environments"], array("username" => $new_username), null, null, true);
			
			if ($user_sessions)
				foreach ($user_sessions as $user_session)
					self::deleteUserSession($brokers, $user_session["username"], $user_session["environment_id"]);
			
			//Update new username in the user_session with the old username.
			$user_sessions = self::getUserSessionsByConditionsAccordingWithUserEnvironmentsSettings($brokers, $settings["user_environments"], array("username" => $old_username), null, null, true);
			
			if ($user_sessions)
				foreach ($user_sessions as $user_session) {
					self::deleteUserSession($brokers, $user_session["username"], $user_session["environment_id"]);
					
					$user_session["username"] = $new_username;
					self::insertUserSession($brokers, $user_session);
				}
		}
		
		public static function logoutBySessionId($brokers, $data) {
			if (is_array($brokers) && $data["session_id"]) {
				$data["modified_date"] = date("Y-m-d H:i:s");
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserSessionService.logoutBySessionId", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["session_id"] = addcslashes($data["session_id"], "\\'");
						$data["logged_status"] = 0;
						$data["logout_time"] = is_numeric($data["logout_time"]) ? $data["logout_time"] : 0;
						$data["logout_ip"] = addcslashes($data["logout_ip"], "\\'");
						
						return $broker->callUpdate("module/user", "logout_by_session_id", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$data["logout_time"] = is_numeric($data["logout_time"]) ? $data["logout_time"] : 0;
						
						$UserSession = $broker->callObject("module/user", "UserSession");
						$attributes = array("logged_status" => 0, "logout_time" => $data["logout_time"], "logout_ip" => $data["logout_ip"], "modified_date" => $data["modified_date"]);
						$conditions = array("session_id" => $data["session_id"]);
						return $UserSession->updateByConditions(array("attributes" => $attributes, "conditions" => $conditions));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$data["logout_time"] = is_numeric($data["logout_time"]) ? $data["logout_time"] : 0;
						
						return $broker->updateObject("mu_user_session", array(
								"logged_status" => $data["logged_status"], 
								"logout_time" => $data["logout_time"], 
								"logout_ip" => $data["logout_ip"],
								"modified_date" => $data["modified_date"]
							), array(
								"session_id" => $data["session_id"]
							));
					}
				}
			}
		}
	
		public static function deleteUserSession($brokers, $username, $environment_id = 0) {
			if (is_array($brokers) && $username) {
				$username = strtolower($username);
				$data = array("username" => $username, "environment_id" => $environment_id);
				$data["environment_id"] = $data["environment_id"] ? $data["environment_id"] : 0;
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = true;
						
						return $broker->callBusinessLogic("module/user", "UserSessionService.deleteUserSession", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($data);
						
						$data["username"] = addcslashes($data["username"], "\\'");
						
						return $broker->callDelete("module/user", "delete_user_session", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($data);
						
						$UserSession = $broker->callObject("module/user", "UserSession");
						return $UserSession->delete($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($data);
						
						return $broker->deleteObject("mu_user_session", $data);
					}
				}
			}
		}
	
		public static function deleteUserSessionBySessionId($brokers, $session_id) {
			if (is_array($brokers) && $session_id) {
				$data = array("session_id" => $session_id);
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserSessionService.deleteUserSessionBySessionId", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["session_id"] = addcslashes($data["session_id"], "\\'");
					
						return $broker->callDelete("module/user", "delete_user_session_by_session_id", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserSession = $broker->callObject("module/user", "UserSession");
						return $UserSession->deleteByConditions(array("conditions" => $data));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mu_user_session", $data);
					}
				}
			}
		}
	
		public static function deleteUserSessionByUserId($brokers, $user_id) {
			if (is_array($brokers) && is_numeric($user_id)) {
				$data = array("user_id" => $user_id);
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserSessionService.deleteUserSessionByUserId", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/user", "delete_user_session_by_user_id", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserSession = $broker->callObject("module/user", "UserSession");
						return $UserSession->deleteByConditions(array("conditions" => $data));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mu_user_session", $data);
					}
				}
			}
		}
	
		public static function getUserSession($brokers, $username, $environment_id = 0, $no_cache = false) {
			if (is_array($brokers) && $username) {
				$username = strtolower($username);
				$data = array("username" => $username, "environment_id" => $environment_id);
				$data["environment_id"] = $data["environment_id"] ? $data["environment_id"] : 0;
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data["options"] = array("no_cache" => $no_cache);
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = $data["decode_user_data"] = true;
						
						return $broker->callBusinessLogic("module/user", "UserSessionService.getUserSession", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($data);
						
						$data["username"] = addcslashes($data["username"], "\\'");
						
						$result = $broker->callSelect("module/user", "get_user_session", $data, array("no_cache" => $no_cache));
						self::decodeSensitiveUsersData($result);
						return $result[0];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($data);
						
						$UserSession = $broker->callObject("module/user", "UserSession");
						$result = $UserSession->findById($data, null, array("no_cache" => $no_cache));
						self::decodeSensitiveUserData($result);
						return $result;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($data);
						
						$result = $broker->findObjects("mu_user_session", null, $data, array("no_cache" => $no_cache));
						self::decodeSensitiveUsersData($result);
						return $result[0];
					}
				}
			}
		}
	
		//$conditions must be an array containing multiple conditions
		public static function getUserSessionsByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
				
				if ($conditions["username"])
					$conditions["username"] = strtolower($conditions["username"]);
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options);
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = $data["decode_user_data"] = true;
							
						return $broker->callBusinessLogic("module/user", "UserSessionService.getUserSessionsByConditions", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/user", "get_user_sessions_by_conditions", array("conditions" => $cond), $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$UserSession = $broker->callObject("module/user", "UserSession");
						$result = $UserSession->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$options["conditions_join"] = $conditions_join;
						$result = $broker->findObjects("mu_user_session", null, $conditions, $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
				}
			}
		}
	
		//$conditions must be an array containing multiple conditions
		public static function countUserSessionsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers)) {
				if ($conditions["username"])
					$conditions["username"] = strtolower($conditions["username"]);
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache));
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = $data["decode_user_data"] = true;
							
						return $broker->callBusinessLogic("module/user", "UserSessionService.countUserSessionsByConditions", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/user", "count_user_sessions_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						$UserSession = $broker->callObject("module/user", "UserSession");
						return $UserSession->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($conditions);
						
						return $broker->countObjects("mu_user_session", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
					}
				}
			}
		}
		
		//Note: This gets the sessions but for only 1 user, grouped by environment_ids
		public static function getUserSessionsByConditionsAccordingWithUserEnvironmentsSettings($brokers, $user_environments, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			$environment_ids = self::getUserEnvironmentIds($user_environments);
			if ($environment_ids)
				$conditions["environment_id"] = array("operator" => "in", "value" => $environment_ids);
		
			return self::getUserSessionsByConditions($brokers, $conditions, $conditions_join, $options, $no_cache);
		}
	
		public static function getAllUserSessions($brokers, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => $options);
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["decode_user_data"] = true;
							
						return $broker->callBusinessLogic("module/user", "UserSessionService.getAllUserSessions", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/user", "get_all_user_sessions", null, $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserSession = $broker->callObject("module/user", "UserSession");
						$result = $UserSession->find(null, $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$result = $broker->findObjects("mu_user_session", null, null, $options);
						self::decodeSensitiveUsersData($result);
						return $result;
					}
				}
			}
		}
	
		public static function countAllUserSessions($brokers, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/user", "UserSessionService.countAllUserSessions", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/user", "count_all_user_sessions", null, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserSession = $broker->callObject("module/user", "UserSession");
						return $UserSession->count(null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mu_user_session", null, array("no_cache" => $no_cache));
					}
				}
			}
		}
	
		/* USER ACTIVITY OBJECT FUNCTIONS */
	
		public static function insertUserActivityObject($brokers, $data) {
			if (is_array($brokers) && $data["thread_id"] && is_numeric($data["user_id"]) && is_numeric($data["activity_id"]) && is_numeric($data["object_type_id"]) && is_numeric($data["object_id"]) && is_numeric($data["time"])) {
				$data["created_date"] = date("Y-m-d H:i:s");
				$data["modified_date"] = $data["created_date"];
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserActivityObjectService.insertUserActivityObject", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["thread_id"] = addcslashes($data["thread_id"], "\\'");
						$data["extra"] = addcslashes($data["extra"], "\\'");
					
						return $broker->callInsert("module/user", "insert_user_activity_object", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserActivityObject = $broker->callObject("module/user", "UserActivityObject");
						return $UserActivityObject->insert($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->insertObject("mu_user_activity_object", array(
							"thread_id" => $data["thread_id"], 
							"user_id" => $data["user_id"], 
							"activity_id" => $data["activity_id"], 
							"object_type_id" => $data["object_type_id"], 
							"object_id" => $data["object_id"], 
							"time" => $data["time"], 
							"extra" => $data["extra"], 
							"created_date" => $data["created_date"], 
							"modified_date" => $data["modified_date"]
						));
					}
				}
			}
		}
	
		public static function updateUserActivityObject($brokers, $data) {
			if (is_array($brokers) && $data["thread_id"] && is_numeric($data["user_id"]) && is_numeric($data["activity_id"]) && is_numeric($data["object_type_id"]) && is_numeric($data["object_id"]) && is_numeric($data["time"])) {
				$data["created_date"] = date("Y-m-d H:i:s");
				$data["modified_date"] = $data["created_date"];
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserActivityObjectService.updateUserActivityObject", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["thread_id"] = addcslashes($data["thread_id"], "\\'");
						$data["extra"] = addcslashes($data["extra"], "\\'");
					
						return $broker->callUpdate("module/user", "update_user_activity_object", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserActivityObject = $broker->callObject("module/user", "UserActivityObject");
						return $UserActivityObject->update($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->updateObject("mu_user_activity_object", array(
								"extra" => $data["extra"], 
								"modified_date" => $data["modified_date"]
							), array(
								"thread_id" => $data["thread_id"], 
								"user_id" => $data["user_id"], 
								"activity_id" => $data["activity_id"], 
								"object_type_id" => $data["object_type_id"], 
								"object_id" => $data["object_id"], 
								"time" => $data["time"], 
							));
					}
				}
			}
		}
		
		public static function deleteUserActivityObject($brokers, $thread_id, $user_id, $activity_id, $object_type_id, $object_id, $time) {
			if (is_array($brokers) && $thread_id && is_numeric($user_id) && is_numeric($activity_id) && is_numeric($object_type_id) && is_numeric($object_id) && is_numeric($time)) {
				$data = array("thread_id" => $thread_id, "user_id" => $user_id, "activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "time" => $time);
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserActivityObjectService.deleteUserActivityObject", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["thread_id"] = addcslashes($data["thread_id"], "\\'");
						
						return $broker->callDelete("module/user", "delete_user_activity_object", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserActivityObject = $broker->callObject("module/user", "UserActivityObject");
						return $UserActivityObject->deleteByConditions(array("conditions" => $data));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mu_user_activity_object", $data);
					}
				}
			}
		}
		
		public static function deleteUserActivityObjectsByUserId($brokers, $user_id) {
			if (is_array($brokers) && is_numeric($user_id)) {
				$data = array("user_id" => $user_id);
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserActivityObjectService.deleteUserActivityObjectsByUserId", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/user", "delete_user_activity_objects_by_user_id", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserActivityObject = $broker->callObject("module/user", "UserActivityObject");
						return $UserActivityObject->deleteByConditions(array("conditions" => $data));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mu_user_activity_object", $data);
					}
				}
			}
		}
	
		//$conditions must be an array containing multiple conditions
		public static function getUserActivityObjectsByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserActivityObjectService.getUserActivityObjectsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						return $broker->callSelect("module/user", "get_user_activity_objects_by_conditions", array("conditions" => $cond), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserActivityObject = $broker->callObject("module/user", "UserActivityObject");
						return $UserActivityObject->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$options["conditions_join"] = $conditions_join;
						return $broker->findObjects("mu_user_activity_object", null, $conditions, $options);
					}
				}
			}
		}
	
		//$conditions must be an array containing multiple conditions
		public static function countUserActivityObjectsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserActivityObjectService.countUserActivityObjectsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/user", "count_user_activity_objects_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserActivityObject = $broker->callObject("module/user", "UserActivityObject");
						return $UserActivityObject->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mu_user_activity_object", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
					}
				}
			}
		}
	
		public static function getAllUserActivityObjects($brokers, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => $options);
						return $broker->callBusinessLogic("module/user", "UserActivityObjectService.getAllUserActivityObjects", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/user", "get_all_user_activity_objects", null, $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserActivityObject = $broker->callObject("module/user", "UserActivityObject");
						return $UserActivityObject->find(null, $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mu_user_activity_object", null, null, $options);
					}
				}
			}
		}
	
		public static function countAllUserActivityObjects($brokers, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/user", "UserActivityObjectService.countAllUserActivityObjects", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/user", "count_all_user_activity_objects", null, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserActivityObject = $broker->callObject("module/user", "UserActivityObject");
						return $UserActivityObject->count(null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mu_user_activity_object", null, array("no_cache" => $no_cache));
					}
				}
			}
		}
	
		/* USER TYPE ACTIVITY OBJECTS FUNCTIONS */
	
		public static function insertUserTypeActivityObject($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["user_type_id"]) && is_numeric($data["activity_id"]) && is_numeric($data["object_type_id"]) && is_numeric($data["object_id"])) {
				$data["created_date"] = date("Y-m-d H:i:s");
				$data["modified_date"] = $data["created_date"];
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserTypeActivityObjectService.insertUserTypeActivityObject", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callInsert("module/user", "insert_user_type_activity_object", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserTypeActivityObject = $broker->callObject("module/user", "UserTypeActivityObject");
						return $UserTypeActivityObject->insert($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->insertObject("mu_user_type_activity_object", array(
							"user_type_id" => $data["user_type_id"], 
							"activity_id" => $data["activity_id"], 
							"object_type_id" => $data["object_type_id"],  
							"object_id" => $data["object_id"], 
							"created_date" => $data["created_date"], 
							"modified_date" => $data["modified_date"]
						));
					}
				}
			}
		}
	
		public static function updateUserTypeActivityObject($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["new_user_type_id"]) && is_numeric($data["new_activity_id"]) && is_numeric($data["new_object_type_id"]) && is_numeric($data["new_object_id"]) && is_numeric($data["old_user_type_id"]) && is_numeric($data["old_activity_id"]) && is_numeric($data["old_object_type_id"]) && is_numeric($data["old_object_id"])) {
				$data["modified_date"] = date("Y-m-d H:i:s");
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserTypeActivityObjectService.updateUserTypeActivityObject", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callUpdate("module/user", "update_user_type_activity_object", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserTypeActivityObject = $broker->callObject("module/user", "UserTypeActivityObject");
						return $UserTypeActivityObject->updatePrimaryKeys($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->updateObject("mu_user_type_activity_object", array(
								"user_type_id" => $data["new_user_type_id"], 
								"activity_id" => $data["new_activity_id"], 
								"object_type_id" => $data["new_object_type_id"],  
								"object_id" => $data["new_object_id"], 
								"created_date" => $data["created_date"], 
								"modified_date" => $data["modified_date"]
							), array(
								"user_type_id" => $data["old_user_type_id"], 
								"activity_id" => $data["old_activity_id"], 
								"object_type_id" => $data["old_object_type_id"],  
								"object_id" => $data["old_object_id"]
							));
					}
				}
			}
		}
	
		public static function deleteUserTypeActivityObject($brokers, $user_type_id, $activity_id, $object_type_id, $object_id) {
			if (is_array($brokers) && is_numeric($user_type_id) && is_numeric($activity_id) && is_numeric($object_type_id) && is_numeric($object_id)) {
				$data = array("user_type_id" => $user_type_id, "activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id);
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserTypeActivityObjectService.deleteUserTypeActivityObject", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/user", "delete_user_type_activity_object", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserTypeActivityObject = $broker->callObject("module/user", "UserTypeActivityObject");
						return $UserTypeActivityObject->deleteByConditions(array("conditions" => $data));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mu_user_type_activity_object", $data);
					}
				}
			}
		}
	
		public static function deleteUserTypeActivityObjectsByUserTypeId($brokers, $user_type_id) {
			if (is_array($brokers) && is_numeric($user_type_id)) {
				$data = array("user_type_id" => $user_type_id);
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserTypeActivityObjectService.deleteUserTypeActivityObjectsByUserTypeId", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/user", "delete_user_type_activity_objects_by_user_type_id", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserTypeActivityObject = $broker->callObject("module/user", "UserTypeActivityObject");
						return $UserTypeActivityObject->deleteByConditions(array("conditions" => $data));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mu_user_type_activity_object", $data);
					}
				}
			}
		}
	
		public static function deleteUserTypeActivityObjectsByActivityIdAndObjectId($brokers, $activity_id, $object_type_id, $object_id) {
			if (is_array($brokers) && is_numeric($activity_id) && is_numeric($object_type_id) && is_numeric($object_id)) {
				$data = array("activity_id" => $activity_id, "object_type_id" => $object_type_id, "object_id" => $object_id);
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserTypeActivityObjectService.deleteUserTypeActivityObjectsByActivityIdAndObjectId", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/user", "delete_user_type_activity_object_by_activity_id_and_object_id", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserTypeActivityObject = $broker->callObject("module/user", "UserTypeActivityObject");
						return $UserTypeActivityObject->deleteByConditions(array("conditions" => $data));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mu_user_type_activity_object", $data);
					}
				}
			}
		}
	
		public static function getUserTypeActivityObjectsByUserTypeIds($brokers, $user_type_ids, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserTypeActivityObjectService.getUserTypeActivityObjectsByUserTypeIds", array("user_type_ids" => $user_type_ids, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$user_type_ids_str = "";//just in case the user tries to hack the sql query. By default all user_type_id should be numeric.
						$user_type_ids = is_array($user_type_ids) ? $user_type_ids : array($user_type_ids);
						foreach ($user_type_ids as $user_type_id) {
							if (is_numeric($user_type_id))
								$user_type_ids_str .= ($user_type_ids_str ? ", " : "") . $user_type_id;
						}
						
						
						return $broker->callSelect("module/user", "get_user_type_activity_objects_by_user_type_ids", array("user_type_ids" => $user_type_ids_str), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserTypeActivityObject = $broker->callObject("module/user", "UserTypeActivityObject");
						$conditions = array("user_type_id" => array("operator" => "in", "value" => $user_type_ids));
						return $UserTypeActivityObject->find(array("conditions" => $conditions), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mu_user_type_activity_object", null, array("user_type_id" => array("operator" => "in", "value" => $user_type_ids)), $options);
					}
				}
			}
		}
	
		//$conditions must be an array containing multiple conditions
		public static function getUserTypeActivityObjectsByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserTypeActivityObjectService.getUserTypeActivityObjectsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						return $broker->callSelect("module/user", "get_user_type_activity_objects_by_conditions", array("conditions" => $cond), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserTypeActivityObject = $broker->callObject("module/user", "UserTypeActivityObject");
						return $UserTypeActivityObject->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$options["conditions_join"] = $conditions_join;
						return $broker->findObjects("mu_user_type_activity_object", null, $conditions, $options);
					}
				}
			}
		}
	
		//$conditions must be an array containing multiple conditions
		public static function countUserTypeActivityObjectsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserTypeActivityObjectService.countUserTypeActivityObjectsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/user", "count_user_type_activity_objects_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserTypeActivityObject = $broker->callObject("module/user", "UserTypeActivityObject");
						return $UserTypeActivityObject->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mu_user_type_activity_object", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
					}
				}
			}
		}
		
		//$conditions must be an array containing multiple conditions
		public static function getUserTypeActivityObjectsByUserIdAndConditions($brokers, $user_id, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers) && is_numeric($user_id)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserTypeActivityObjectService.getUserTypeActivityObjectsByUserIdAndConditions", array("user_id" => $user_id, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join, "utao");
						$cond = $cond ? $cond : "1=1";
						return $broker->callSelect("module/user", "get_user_type_activity_objects_by_user_id_and_conditions", array("user_id" => $user_id, "conditions" => $cond), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserTypeActivityObject = $broker->callObject("module/user", "UserTypeActivityObject");
						$cond = DB::getSQLConditions($conditions, $conditions_join, "utao");
						$cond = $cond ? $cond : "1=1";
						return $UserTypeActivityObject->callSelect("get_user_type_activity_objects_by_user_id_and_conditions", array("user_id" => $user_id, "conditions" => $cond), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join, "utao");
						$cond = $cond ? $cond : "1=1";
						$sql = UserTypeActivityObjectDBDAOUtil::get_user_type_activity_objects_by_user_id_and_conditions(array("user_id" => $user_id, "conditions" => $cond));
						
						return $broker->getSQL($sql, $options);
					}
				}
			}
		}
	
		//$conditions must be an array containing multiple conditions
		public static function countUserTypeActivityObjectsByUserIdAndConditions($brokers, $user_id, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers) && is_numeric($user_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserTypeActivityObjectService.countUserTypeActivityObjectsByUserIdAndConditions", array("user_id" => $user_id, "conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join, "utao");
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/user", "count_user_type_activity_objects_by_user_id_and_conditions", array("user_id" => $user_id, "conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserTypeActivityObject = $broker->callObject("module/user", "UserTypeActivityObject");
						$cond = DB::getSQLConditions($conditions, $conditions_join, "utao");
						$cond = $cond ? $cond : "1=1";
						$result = $UserTypeActivityObject->callSelect("count_user_type_activity_objects_by_user_id_and_conditions", array("user_id" => $user_id, "conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join, "utao");
						$cond = $cond ? $cond : "1=1";
						$sql = UserTypeActivityObjectDBDAOUtil::count_user_type_activity_objects_by_user_id_and_conditions(array("user_id" => $user_id, "conditions" => $cond));
						
						$result = $broker->getSQL($sql, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
				}
			}
		}
	
		public static function getAllUserTypeActivityObjects($brokers, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => $options);
						return $broker->callBusinessLogic("module/user", "UserTypeActivityObjectService.getAllUserTypeActivityObjects", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/user", "get_all_user_type_activity_objects", null, $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserTypeActivityObject = $broker->callObject("module/user", "UserTypeActivityObject");
						return $UserTypeActivityObject->find(null, $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mu_user_type_activity_object", null, null, $options);
					}
				}
			}
		}
	
		public static function countAllUserTypeActivityObjects($brokers, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/user", "UserTypeActivityObjectService.countAllUserTypeActivityObjects", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/user", "count_all_user_type_activity_objects", null, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserTypeActivityObject = $broker->callObject("module/user", "UserTypeActivityObject");
						return $UserTypeActivityObject->count(null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mu_user_type_activity_object", null, array("no_cache" => $no_cache));
					}
				}
			}
		}
		
		/* OBJECT USER FUNCTIONS */

		public static function insertObjectUser($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["user_id"]) && is_numeric($data["object_type_id"]) && is_numeric($data["object_id"])) {
				$data["created_date"] = date("Y-m-d H:i:s");
				$data["modified_date"] = $data["created_date"];
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						
						return $broker->callBusinessLogic("module/user", "ObjectUserService.insertObjectUser", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : 0;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : 0;
					
						return $broker->callInsert("module/user", "insert_object_user", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
						
						$ObjectUser = $broker->callObject("module/user", "ObjectUser");
						return $ObjectUser->insert($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
						
						return $broker->insertObject("mu_object_user", array(
								"user_id" => $data["user_id"], 
								"object_type_id" => $data["object_type_id"], 
								"object_id" => $data["object_id"], 
								"group" => $data["group"], 
								"order" => $data["order"], 
								"created_date" => $data["created_date"], 
								"modified_date" => $data["modified_date"]
							));
					}
				}
			}
		}

		public static function updateObjectUser($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["new_user_id"]) && is_numeric($data["new_object_type_id"]) && is_numeric($data["new_object_id"]) && is_numeric($data["old_user_id"]) && is_numeric($data["old_object_type_id"]) && is_numeric($data["old_object_id"])) {
				$data["modified_date"] = date("Y-m-d H:i:s");
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						
						return $broker->callBusinessLogic("module/user", "ObjectUserService.updateObjectUser", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : 0;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : 0;
					
						return $broker->callUpdate("module/user", "update_object_user", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
						
						$ObjectUser = $broker->callObject("module/user", "ObjectUser");
						return $ObjectUser->updatePrimaryKeys($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
						
						return $broker->updateObject("mu_object_user", array(
								"user_id" => $data["new_user_id"], 
								"object_type_id" => $data["new_object_type_id"], 
								"object_id" => $data["new_object_id"],
								"group" => $data["group"], 
								"order" => $data["order"], 
								"modified_date" => $data["modified_date"]
							), array(
								"user_id" => $data["old_user_id"], 
								"object_type_id" => $data["old_object_type_id"], 
								"object_id" => $data["old_object_id"]
							));
					}
				}
			}
		}
	
		private static function updateObjectUsersByUserId($brokers, $user_id, $data) {
			if (is_array($brokers) && is_numeric($user_id)) {
				if (self::deleteObjectUsersByUserId($brokers, $user_id)) {
					$status = true;
					$object_users = is_array($data["object_users"]) ? $data["object_users"] : array();
				
					foreach ($object_users as $object_user) {
						if (is_numeric($object_user["object_type_id"]) && is_numeric($object_user["object_id"])) {
							$object_user["user_id"] = $user_id;
					
							if (!self::insertObjectUser($brokers, $object_user)) {
								$status = false;
							}
						}
					}
				
					return $status;
				}
			}
		}

		public static function deleteObjectUser($brokers, $user_id, $object_type_id, $object_id) {
			if (is_array($brokers) && is_numeric($user_id) && is_numeric($object_type_id) && is_numeric($object_id)) {
				$data = array("user_id" => $user_id, "object_type_id" => $object_type_id, "object_id" => $object_id);
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "ObjectUserService.deleteObjectUser", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/user", "delete_object_user", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectUser = $broker->callObject("module/user", "ObjectUser");
						return $ObjectUser->deleteByConditions(array("conditions" => $data));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mu_object_user", $data);
					}
				}
			}
		}

		public static function deleteObjectUsersByUserId($brokers, $user_id) {
			if (is_array($brokers) && is_numeric($user_id)) {
				$data = array("user_id" => $user_id);
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "ObjectUserService.deleteObjectUsersByUserId", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/user", "delete_object_users_by_user_id", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectUser = $broker->callObject("module/user", "ObjectUser");
						return $ObjectUser->deleteByConditions(array("conditions" => $data));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mu_object_user", $data);
					}
				}
			}
		}

		public static function deleteObjectUsersByConditions($brokers, $conditions, $conditions_join) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "ObjectUserService.deleteObjectUsersByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						return $broker->callDelete("module/user", "delete_object_users_by_conditions", array("conditions" => $cond));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectUser = $broker->callObject("module/user", "ObjectUser");
						return $ObjectUser->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $conditions_join));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mu_object_user", $conditions, array("conditions_join" => $conditions_join));
					}
				}
			}
		}

		public static function getObjectUser($brokers, $user_id, $object_type_id, $object_id, $no_cache = false) {
			if (is_array($brokers) && is_numeric($user_id) && is_numeric($object_type_id) && is_numeric($object_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "ObjectUserService.getObjectUser", array("user_id" => $user_id, "object_type_id" => $object_type_id, "object_id" => $object_id, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/user", "get_object_user", array("user_id" => $user_id, "object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
						return $result[0];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectUser = $broker->callObject("module/user", "ObjectUser");
						return $ObjectUser->findById(array("user_id" => $user_id, "object_type_id" => $object_type_id, "object_id" => $object_id), null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$result = $broker->findObjects("mu_object_user", null, array("user_id" => $user_id, "object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
						return $result[0];
					}
				}
			}
		}

		//$conditions must be an array containing multiple conditions
		public static function getObjectUsersByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "ObjectUserService.getObjectUsersByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						return $broker->callSelect("module/user", "get_object_users_by_conditions", array("conditions" => $cond), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectUser = $broker->callObject("module/user", "ObjectUser");
						return $ObjectUser->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$options["conditions_join"] = $conditions_join;
						return $broker->findObjects("mu_object_user", null, $conditions, $options);
					}
				}
			}
		}

		//$conditions must be an array containing multiple conditions
		public static function countObjectUsersByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "ObjectUserService.countObjectUsersByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/user", "count_object_users_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectUser = $broker->callObject("module/user", "ObjectUser");
						return $ObjectUser->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mu_object_user", null, $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
					}
				}
			}
		}

		public static function getAllObjectUsers($brokers, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => $options);
						return $broker->callBusinessLogic("module/user", "ObjectUserService.getAllObjectUsers", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/user", "get_all_object_users", null, $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectUser = $broker->callObject("module/user", "ObjectUser");
						return $ObjectUser->find(null, $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mu_object_user", null, null, $options);
					}
				}
			}
		}
		
		public static function countAllObjectUsers($brokers, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/user", "ObjectUserService.countAllObjectUsers", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/user", "count_all_object_users", null, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectUser = $broker->callObject("module/user", "ObjectUser");
						return $ObjectUser->count(null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mu_object_user", null, array("no_cache" => $no_cache));
					}
				}
			}
		}

		public static function getObjectUsersByUserId($brokers, $user_id, $options = array(), $no_cache = false) {
			if (is_array($brokers) && is_numeric($user_id)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("user_id" => $user_id, "options" => $options);
						return $broker->callBusinessLogic("module/user", "ObjectUserService.getObjectUsersByUserId", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/user", "get_object_users_by_user_id", array("user_id" => $user_id), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectUser = $broker->callObject("module/user", "ObjectUser");
						return $ObjectUser->find(array("conditions" => array("user_id" => $user_id)), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mu_object_user", null, array("user_id" => $user_id), $options);
					}
				}
			}
		}

		public static function countObjectUsersByUserId($brokers, $user_id, $no_cache = false) {
			if (is_array($brokers) && is_numeric($user_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("user_id" => $user_id, "options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/user", "ObjectUserService.countObjectUsersByUserId", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/user", "count_object_users_by_user_id", array("user_id" => $user_id), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectUser = $broker->callObject("module/user", "ObjectUser");
						return $ObjectUser->count(array("conditions" => array("user_id" => $user_id)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mu_object_user", array("user_id" => $user_id), array("no_cache" => $no_cache));
					}
				}
			}
		}
		
		/* USER ENVIRONMENT FUNCTIONS */

		public static function insertUserEnvironment($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["user_id"]) && is_numeric($data["environment_id"])) {
				$data["created_date"] = date("Y-m-d H:i:s");
				$data["modified_date"] = $data["created_date"];
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserEnvironmentService.insertUserEnvironment", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callInsert("module/user", "insert_user_environment", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserEnvironment = $broker->callObject("module/user", "UserEnvironment");
						return $UserEnvironment->insert($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->insertObject("mu_user_environment", array(
								"user_id" => $data["user_id"], 
								"environment_id" => $data["environment_id"], 
								"created_date" => $data["created_date"], 
								"modified_date" => $data["modified_date"]
							));
					}
				}
			}
		}

		public static function updateUserEnvironment($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["new_user_id"]) && is_numeric($data["new_environment_id"]) && is_numeric($data["old_user_id"]) && is_numeric($data["old_environment_id"])) {
				$data["modified_date"] = date("Y-m-d H:i:s");
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserEnvironmentService.updateUserEnvironment", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callUpdate("module/user", "update_user_environment", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserEnvironment = $broker->callObject("module/user", "UserEnvironment");
						return $UserEnvironment->updatePrimaryKeys($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->updateObject("mu_user_environment", array(
								"user_id" => $data["new_user_id"], 
								"environment_id" => $data["new_environment_id"], 
								"modified_date" => $data["modified_date"]
							), array(
								"user_id" => $data["old_user_id"], 
								"environment_id" => $data["old_environment_id"], 
							));
					}
				}
			}
		}
	
		private static function updateUserEnvironmentsByUserId($brokers, $user_id, $data) {
			if (is_array($brokers) && is_numeric($user_id)) {
				if (self::deleteUserEnvironmentsByConditions($brokers, array("user_id" => $user_id), null)) {
					$status = true;
					$environment_ids = self::getUserEnvironmentIds($data["user_environments"]);
					
					if ($environment_ids)
						foreach ($environment_ids as $environment_id) {
							if (!self::insertUserEnvironment($brokers, array("user_id" => $user_id, "environment_id" => $environment_id)))
								$status = false;
						}
				
					return $status;
				}
			}
		}
		
		//$user_environments can be an array with numeric values or with other sub-arrays where each item contains the environment_id attribute with a numeric value.
		public static function getUserEnvironmentIds($user_environments) {
			$environment_ids = array();
			
			$user_environments = is_array($user_environments) ? $user_environments : array($user_environments);
		    	
	    		foreach ($user_environments as $user_environment) {
				$user_environment = trim(is_array($user_environment) ? $user_environment["environment_id"] : $user_environment);
				
				if ($user_environment)
					$environment_ids[] = is_numeric($user_environment) ? $user_environment : HashCode::getHashCodePositive($user_environment);
			}
				
			return $environment_ids;
		}

		public static function deleteUserEnvironment($brokers, $user_id, $environment_id) {
			if (is_array($brokers) && is_numeric($user_id) && is_numeric($environment_id)) {
				$data = array("user_id" => $user_id, "environment_id" => $environment_id);
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserEnvironmentService.deleteUserEnvironment", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/user", "delete_user_environment", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserEnvironment = $broker->callObject("module/user", "UserEnvironment");
						return $UserEnvironment->deleteByConditions(array("conditions" => $data));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mu_user_environment", $data);
					}
				}
			}
		}

		public static function deleteUserEnvironmentsByConditions($brokers, $conditions, $conditions_join) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserEnvironmentService.deleteUserEnvironmentsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						return $broker->callDelete("module/user", "delete_user_environments_by_conditions", array("conditions" => $cond));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserEnvironment = $broker->callObject("module/user", "UserEnvironment");
						return $UserEnvironment->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $conditions_join));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mu_user_environment", $conditions, array("conditions_join" => $conditions_join));
					}
				}
			}
		}

		public static function getUserEnvironment($brokers, $user_id, $environment_id, $no_cache = false) {
			if (is_array($brokers) && is_numeric($user_id) && is_numeric($environment_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserEnvironmentService.getUserEnvironment", array("user_id" => $user_id, "environment_id" => $environment_id, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/user", "get_user_environment", array("user_id" => $user_id, "environment_id" => $environment_id), array("no_cache" => $no_cache));
						return $result[0];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserEnvironment = $broker->callObject("module/user", "UserEnvironment");
						return $UserEnvironment->findById(array("user_id" => $user_id, "environment_id" => $environment_id), null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$result = $broker->findObjects("mu_user_environment", null, array("user_id" => $user_id, "environment_id" => $environment_id), array("no_cache" => $no_cache));
						return $result[0];
					}
				}
			}
		}

		//$conditions must be an array containing multiple conditions
		public static function getUserEnvironmentsByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserEnvironmentService.getUserEnvironmentsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						return $broker->callSelect("module/user", "get_user_environments_by_conditions", array("conditions" => $cond), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserEnvironment = $broker->callObject("module/user", "UserEnvironment");
						return $UserEnvironment->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$options["conditions_join"] = $conditions_join;
						return $broker->findObjects("mu_user_environment", null, $conditions, $options);
					}
				}
			}
		}

		//$conditions must be an array containing multiple conditions
		public static function countUserEnvironmentsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "UserEnvironmentService.countUserEnvironmentsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/user", "count_user_environments_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserEnvironment = $broker->callObject("module/user", "UserEnvironment");
						return $UserEnvironment->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$options["conditions_join"] = $conditions_join;
						return $broker->countObjects("mu_user_environment", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
					}
				}
			}
		}

		public static function getAllUserEnvironments($brokers, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => $options);
						return $broker->callBusinessLogic("module/user", "UserEnvironmentService.getAllUserEnvironments", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/user", "get_all_user_environments", null, $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserEnvironment = $broker->callObject("module/user", "UserEnvironment");
						return $UserEnvironment->find(null, $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mu_user_environment", null, null, $options);
					}
				}
			}
		}
		
		public static function countAllUserEnvironments($brokers, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/user", "UserEnvironmentService.countAllUserEnvironments", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/user", "count_all_user_environments", null, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$UserEnvironment = $broker->callObject("module/user", "UserEnvironment");
						return $UserEnvironment->count(null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mu_user_environment", null, array("no_cache" => $no_cache));
					}
				}
			}
		}
		
		/* EXTERNAL USER FUNCTIONS */
		
		public static function insertExternalUser($brokers, $data) {
			if (is_array($brokers)) {
				$data["created_date"] = date("Y-m-d H:i:s");
				$data["modified_date"] = $data["created_date"];
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = true;
						
						return $broker->callBusinessLogic("module/user", "ExternalUserService.insertExternalUser", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($data, array("data"));
						
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
						
						$status = $broker->callInsert("module/user", "insert_external_user", $data);
						return $status ? $broker->getInsertedId() : $status;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($data, array("data"));
						
						if (!is_numeric($data["user_id"]))
							unset($data["user_id"]);
						
						if (!is_numeric($data["external_type_id"]))
							$data["external_type_id"] = 0;
						
						$ExternalUser = $broker->callObject("module/user", "ExternalUser");
						$status = $ExternalUser->insert($data, $ids);
						return $status ? $ids["external_user_id"] : $status;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($data, array("data"));
						
						if (!is_numeric($data["user_id"]))
							unset($data["user_id"]);
						
						if (!is_numeric($data["external_type_id"]))
							$data["external_type_id"] = 0;
						
						$status = $broker->insertObject("mu_external_user", array(
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
							));
						return $status ? $broker->getInsertedId() : $status;
					}
				}
			}
		}

		public static function updateExternalUser($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["external_user_id"])) {
				$data["modified_date"] = date("Y-m-d H:i:s");
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = true;
						
						return $broker->callBusinessLogic("module/user", "ExternalUserService.updateExternalUser", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($data, array("data"));
						
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
						
						return $broker->callUpdate("module/user", "update_external_user", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($data, array("data"));
						
						if (!is_numeric($data["user_id"]))
							unset($data["user_id"]);
						
						if (!is_numeric($data["external_type_id"]))
							$data["external_type_id"] = 0;
						
						$ExternalUser = $broker->callObject("module/user", "ExternalUser");
						return $ExternalUser->update($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($data, array("data"));
						
						if (!is_numeric($data["user_id"]))
							unset($data["user_id"]);
						
						if (!is_numeric($data["external_type_id"]))
							$data["external_type_id"] = 0;
						
						return $broker->updateObject("mu_external_user", array(
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
							));
					}
				}
			}
		}

		public static function deleteExternalUser($brokers, $external_user_id) {
			if (is_array($brokers) && is_numeric($external_user_id)) {
				$data = array("external_user_id" => $external_user_id);
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/user", "ExternalUserService.deleteExternalUser", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/user", "delete_external_user", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ExternalUser = $broker->callObject("module/user", "ExternalUser");
						return $ExternalUser->deleteByConditions(array("conditions" => $data));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mu_external_user", $data);
					}
				}
			}
		}

		public static function deleteExternalUsersByConditions($brokers, $conditions, $conditions_join) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("conditions" => $conditions, "conditions_join" => $conditions_join);
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = true;
						
						return $broker->callBusinessLogic("module/user", "ExternalUserService.deleteExternalUsersByConditions", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions, array("data"));
						
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						return $broker->callDelete("module/user", "delete_external_users_by_conditions", array("conditions" => $cond));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions, array("data"));
						
						$ExternalUser = $broker->callObject("module/user", "ExternalUser");
						return $ExternalUser->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $conditions_join));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($conditions, array("data"));
						
						return $broker->deleteObject("mu_external_user", $conditions, array("conditions_join" => $conditions_join));
					}
				}
			}
		}

		public static function getExternalUser($brokers, $external_user_id, $no_cache = false) {
			if (is_array($brokers) && is_numeric($external_user_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("external_user_id" => $external_user_id, "options" => array("no_cache" => $no_cache));
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["decode_user_data"] = true;
						
						return $broker->callBusinessLogic("module/user", "ExternalUserService.getExternalUser", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/user", "get_external_user", array("external_user_id" => $external_user_id), array("no_cache" => $no_cache));
						$result = $result[0];
						self::decodeSensitiveUserData($result, array("data"));
						return $result;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ExternalUser = $broker->callObject("module/user", "ExternalUser");
						$result = $ExternalUser->findById(array("external_user_id" => $external_user_id), null, array("no_cache" => $no_cache));
						self::decodeSensitiveUserData($result, array("data"));
						return $result;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$result = $broker->findObjects("mu_external_user", null, array("external_user_id" => $external_user_id), array("no_cache" => $no_cache));
						$result = $result[0];
						self::decodeSensitiveUserData($result, array("data"));
						return $result;
					}
				}
			}
		}

		//$conditions must be an array containing multiple conditions
		public static function getExternalUsersByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options);
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = $data["decode_user_data"] = true;
						
						return $broker->callBusinessLogic("module/user", "ExternalUserService.getExternalUsersByConditions", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions, array("data"));
						
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/user", "get_external_users_by_conditions", array("conditions" => $cond), $options);
						self::decodeSensitiveUsersData($result, array("data"));
						return $result;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions, array("data"));
						
						$ExternalUser = $broker->callObject("module/user", "ExternalUser");
						$result = $ExternalUser->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
						self::decodeSensitiveUsersData($result, array("data"));
						return $result;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($conditions, array("data"));
						
						$options["conditions_join"] = $conditions_join;
						$result = $broker->findObjects("mu_external_user", null, $conditions, $options);
						self::decodeSensitiveUsersData($result, array("data"));
						return $result;
					}
				}
			}
		}

		//$conditions must be an array containing multiple conditions
		public static function countExternalUsersByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache));
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["encode_user_data"] = true;
						
						return $broker->callBusinessLogic("module/user", "ExternalUserService.countExternalUsersByConditions", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions, array("data"));
						
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/user", "count_external_users_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						self::encodeSensitiveUserData($conditions, array("data"));
						
						$ExternalUser = $broker->callObject("module/user", "ExternalUser");
						return $ExternalUser->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						self::encodeSensitiveUserData($conditions, array("data"));
						
						return $broker->countObjects("mu_external_user", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
					}
				}
			}
		}

		public static function getAllExternalUsers($brokers, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => $options);
						if (self::getConstantVariable("HASH_SENSITIVE_DATA")) $data["decode_user_data"] = true;
						
						return $broker->callBusinessLogic("module/user", "ExternalUserService.getAllExternalUsers", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/user", "get_all_external_users", null, $options);
						self::decodeSensitiveUsersData($result, array("data"));
						return $result;
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ExternalUser = $broker->callObject("module/user", "ExternalUser");
						$result = $ExternalUser->find(null, $options);
						self::decodeSensitiveUsersData($result, array("data"));
						return $result;
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$result = $broker->findObjects("mu_external_user", null, null, $options);
						self::decodeSensitiveUsersData($result, array("data"));
						return $result;
					}
				}
			}
		}
		
		public static function countAllExternalUsers($brokers, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/user", "ExternalUserService.countAllExternalUsers", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/user", "count_all_external_users", null, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ExternalUser = $broker->callObject("module/user", "ExternalUser");
						return $ExternalUser->count(null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mu_external_user", null, array("no_cache" => $no_cache));
					}
				}
			}
		}
	}
}
?>
