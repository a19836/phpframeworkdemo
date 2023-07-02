<?php
$common_project_name = $EVC->getCommonProjectName();
include_once get_lib("org.phpframework.util.HashCode");
include_once $EVC->getModulePath("object/ObjectUtil", $common_project_name);
include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
include_once $EVC->getModulePath("common/ObjectToObjectValidationHandler", $common_project_name);

class UserSessionActivitiesHandler {
	
	const SESSIONS_CACHE_FOLDER_NAME = "user_session_activities_handler";
	const PUBLIC_USER_TYPE_ACTIVITIES_CACHE_FILE_NAME = "public_user_type_activities";
	
	private $EVC;
	private $session_id;
	
	private $user_session;
	private $user;
	private $user_user_types;
	private $public_user_type_activities;
	private $user_type_activities;
	private $available_activities;
	private $available_object_types;
	
	public function __construct($EVC, $session_id = null) {
		$this->EVC = $EVC;
		$this->session_id = $session_id;
		
		$this->init();
	}
	
	private function getSessionCacheFileName($session_id) {
		return self::SESSIONS_CACHE_FOLDER_NAME . "/" . substr($session_id, 0, 1) . "/" . $session_id;
	}
	
	private function init() {
		$P = $this->EVC->getPresentationLayer();
		$UserCacheHandler = $P->getPHPFrameWork()->getObject("UserCacheHandler");
          
          $brokers = $P->getBrokers();
		
		if ($this->session_id) {
			$this->user_session = \UserUtil::isLoggedIn($brokers, $this->session_id, \UserUtil::getConstantVariable("DEFAULT_USER_SESSION_EXPIRATION_TTL"));
			
			if ($this->user_session["user_id"]) {
				$ttl = time() - $this->user_session["login_time"] + \UserUtil::getConstantVariable("DEFAULT_USER_SESSION_EXPIRATION_TTL");
				$UserCacheHandler->config($ttl, true);
				$cached_file_name = $this->getSessionCacheFileName($this->session_id);
				
				if ($UserCacheHandler->isValid($cached_file_name)) {
					$aux = $UserCacheHandler->read($cached_file_name);
					
					$this->user = $aux["user"];
					$this->available_activities = $aux["available_activities"];
					$this->available_object_types = $aux["available_object_types"];
					$this->user_user_types = $aux["user_user_types"];
					$this->user_type_activities = $aux["user_type_activities"];
				}
				else {
					$users = \UserUtil::getUsersByConditions($brokers, array("user_id" => $this->user_session["user_id"]), null);
					$this->user = $users[0];
					unset($this->user["password"]);
					unset($this->user["security_question_1"]);
					unset($this->user["security_answer_1"]);
					unset($this->user["security_question_2"]);
					unset($this->user["security_answer_2"]);
					unset($this->user["security_question_3"]);
					unset($this->user["security_answer_3"]);
					
					if ($this->user["user_id"]) {
						$this->available_activities = array();
						$activities = \UserUtil::getAllActivities($brokers);
						if ($activities)
							foreach ($activities as $activity)
								$this->available_activities[ $activity["name"] ] = $activity["activity_id"];
						
						$this->available_object_types = array();
						$object_types = \ObjectUtil::getAllObjectTypes($brokers);
						if ($object_types)
							foreach ($object_types as $object_type)
								$this->available_object_types[ $object_type["name"] ] = $object_type["object_type_id"];
						
						$this->user_user_types = array();
						$this->user_type_activities = array();
					
						$user_user_types = \UserUtil::getUserUserTypesByConditions($brokers, array("user_id" => $this->user["user_id"]), null);
						if ($user_user_types) {
							foreach ($user_user_types as $user_user_type) 
								$this->user_user_types[] = $user_user_type["user_type_id"];
						
							$user_type_activities = \UserUtil::getUserTypeActivityObjectsByUserTypeIds($brokers, $this->user_user_types);
							foreach ($user_type_activities as $user_type_activity) 
								$this->user_type_activities[ $user_type_activity["object_type_id"] ][ $user_type_activity["object_id"] ][ $user_type_activity["activity_id"] ] = true;
						
							$this->user["user_type_ids"] = $this->user_user_types;
						}
					}
				
					//saving cache
					$aux = array(
						"user" => $this->user,
						"available_activities" => $this->available_activities,
						"available_object_types" => $this->available_object_types,
						"user_user_types" => $this->user_user_types,
						"user_type_activities" => $this->user_type_activities,
					);
					$UserCacheHandler->write($cached_file_name, $aux);
				}
			}
		}
		
		$this->public_user_type_activities = array();
		
		$UserCacheHandler->config(3600, true);//1 hour should be fine
		$cached_file_name = self::PUBLIC_USER_TYPE_ACTIVITIES_CACHE_FILE_NAME;
		
		if ($UserCacheHandler->isValid($cached_file_name))
			$this->public_user_type_activities = $UserCacheHandler->read($cached_file_name);
		else {
			$user_type_activities = \UserUtil::getUserTypeActivityObjectsByConditions($brokers, array("user_type_id" => \UserUtil::PUBLIC_USER_TYPE_ID), null);
			
			if ($user_type_activities)
				foreach ($user_type_activities as $user_type_activity)
					$this->public_user_type_activities[ $user_type_activity["object_type_id"] ][ $user_type_activity["object_id"] ][ $user_type_activity["activity_id"] ] = true;
			
			$UserCacheHandler->write($cached_file_name, $this->public_user_type_activities);
		}
	}
	
	public function removeMySessionCache() {
		return $this->removeSessionCacheBySession($this->session_id);
	}
	
	public function removeSessionCache($session_id) {
		if ($session_id) {
			$UserCacheHandler = $this->EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler");
		    	
		    	$cached_file_name = $this->getSessionCacheFileName($session_id);
			return $UserCacheHandler->delete($cached_file_name);
		}
	}
	
	public function removeSessionsCacheByEnvironments($user_environments) {
		if ($user_environments) {
			$P = $this->EVC->getPresentationLayer();
			$UserCacheHandler = $P->getPHPFrameWork()->getObject("UserCacheHandler");
		    	
		    	$brokers = $P->getBrokers();
		    	$status = true;
		    	
		    	$user_sessions = \UserUtil::getUserSessionsByConditionsAccordingWithUserEnvironmentsSettings($brokers, $user_environments, array(), null);
			
			if ($user_sessions) 
				foreach ($user_sessions as $user_session) 
					if ($user_session["session_id"]) {
					    	$cached_file_name = $this->getSessionCacheFileName($user_session["session_id"]);
					
						if (!$UserCacheHandler->delete($cached_file_name))
							$status = false;
					}
			
			return $status;
		}
	}
	
	public function removeAllCache() {
		$P = $this->EVC->getPresentationLayer();
		$UserCacheHandler = $P->getPHPFrameWork()->getObject("UserCacheHandler");
	    	
	    	//delete all sessions cache by deleting the folder
		$status = CacheHandlerUtil::deleteFolder($UserCacheHandler->getRootPath() . self::SESSIONS_CACHE_FOLDER_NAME, false);
	    	
	    	//delete public cache
		$cached_file_name = self::PUBLIC_USER_TYPE_ACTIVITIES_CACHE_FILE_NAME;
		if (!$UserCacheHandler->delete($cached_file_name))
			$status = false;
		
		return $status;
	}
	
	public function getUserData() {
		return $this->user;
	}
	
	public function getUserSessionData() {
		return $this->user_session;
	}
	
	public function validateUserActivityByModule($activity_id, $file_path, $settings = null) {
		return $this->validateUserActivity($activity_id, \ObjectUtil::MODULE_OBJECT_TYPE_ID, $file_path, $settings);
	}
	
	public function validateUserActivityByPage($activity_id, $file_path, $settings = null) {
		return $this->validateUserActivity($activity_id, \ObjectUtil::PAGE_OBJECT_TYPE_ID, $file_path, $settings);
	}
	
	public function validateUserActivity($activity_id, $object_type_id, $object_id, $settings = null) {
		$status = $this->userActivityExists($activity_id, $object_type_id, $object_id);
		
		return ObjectToObjectValidationHandler::validate($this->EVC, $status, $settings);
	}
	
	public function userActivityExistsByModule($activity_id, $file_path) {
		return $this->userActivityExists($activity_id, \ObjectUtil::MODULE_OBJECT_TYPE_ID, $file_path);
	}
	
	public function userActivityExistsByPage($activity_id, $file_path) {
		return $this->userActivityExists($activity_id, \ObjectUtil::PAGE_OBJECT_TYPE_ID, $file_path);
	}
	
	public function userActivityExists($activity_id, $object_type_id, $object_id) {
		if ($object_type_id == "current_page")
			$object_type_id = \ObjectUtil::PAGE_OBJECT_TYPE_ID;
		else if (!is_numeric($object_type_id))
			$object_type_id = $this->available_object_types[$object_type_id];
		
		if (($object_type_id == \ObjectUtil::PAGE_OBJECT_TYPE_ID || $object_type_id == \ObjectUtil::MODULE_OBJECT_TYPE_ID) && !is_numeric($object_id)) 
			$object_id = \UserUtil::getObjectIdFromFilePath($object_id);
		
		if ($object_type_id && is_numeric($object_id)) {
			$activity_ids = is_array($activity_id) ? $activity_id : array($activity_id);
			
			foreach ($activity_ids as $activity_id) {
				if (!is_numeric($activity_id))
					$activity_id = $this->available_activities[$activity_id];
				
				$status = false;
				if ($activity_id) {
					if ($this->user_type_activities)
						$status = $this->user_type_activities[$object_type_id][$object_id][$activity_id];
					
					if (!$status && $this->public_user_type_activities)
						$status = $this->public_user_type_activities[$object_type_id][$object_id][$activity_id];
				}
				
				//returns false if $activity_id is empty or if there is no permission in $this->user_type_activities or $this->public_user_type_activities
				if (!$status)
					return false;
			}
			
			return true;
		}
		
		return false;
	}
}
?>
