<?php
include_once $EVC->getModulePath("user/UserUtil", $EVC->getCommonProjectName());

class UserAdminUtil {
	private $CommonModuleAdminUtil;
	private $activities;
	private $user_types;
	private $object_types;
	
	public function __construct($CommonModuleAdminUtil) {
		$this->CommonModuleAdminUtil = $CommonModuleAdminUtil;
	}
	
	public function getMenuSettings() {
		return array(
			"class" => "",
			"menus" => array(
				array(
					"label" => "Activities",
					"menus" => array(
						array(
							"label" => "Activities List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_activities"),
							"title" => "View List of Activities",
							"class" => "",
						),
						array(
							"label" => "Add Activity",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_activity"),
							"title" => "Add new Activity",
							"class" => "",
						),
					)
				),
				array(
					"label" => "User Types",
					"menus" => array(
						array(
							"label" => "User Types List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_user_types"),
							"title" => "View List of User Types",
							"class" => "",
						),
						array(
							"label" => "Add User Type",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_user_type"),
							"title" => "Add new User Type",
							"class" => "",
						),
					)
				),
				array(
					"label" => "Internal Users",
					"menus" => array(
						array(
							"label" => "Users List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_users"),
							"title" => "View List of Users",
							"class" => "",
						),
						array(
							"label" => "Add User",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_user"),
							"title" => "Add new User",
							"class" => "",
						),
						array(
							"label" => "Extra Attributes",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("manage_user_extra_attributes"),
							"title" => "Manage User Extra Attributes",
							"class" => "",
						),
					)
				),
				array(
					"label" => "External Users",
					"menus" => array(
						array(
							"label" => "External Users List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_external_users"),
							"title" => "View List of External Users",
							"class" => "",
						),
						array(
							"label" => "Add External User",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_external_user"),
							"title" => "Add new External User",
							"class" => "",
						),
					)
				),
				array(
					"label" => "Users' User Types",
					"menus" => array(
						array(
							"label" => "Users' User Types List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_user_user_types"),
							"title" => "View List of Users' User Type",
							"class" => "",
						),
						array(
							"label" => "Add User's User Type",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_user_user_type"),
							"title" => "Add new User's User Type",
							"class" => "",
						),
					)
				),
				array(
					"label" => "User Type Activity Objects",
					"class" => "large",
					"menus" => array(
						array(
							"label" => "User Type Activity Objects List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_user_type_activity_objects"),
							"title" => "View List of User Type Activity Objects",
							"class" => "",
						),
						array(
							"label" => "Add User Type Activity Object",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_user_type_activity_object"),
							"title" => "Add new User Type Activity Object",
							"class" => "",
						),
						array(
							"label" => "Manage User Type Activity Objects",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("manage_user_type_activity_objects"),
							"title" => "Manage User Type Permissions",
							"class" => "highlight",
						),
					)
				),
				array(
					"label" => "User Activity Objects",
					"class" => "large",
					"menus" => array(
						array(
							"label" => "User Activity Objects List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_user_activity_objects"),
							"title" => "View List of User Activity Objects",
							"class" => "",
						),
						array(
							"label" => "Add User Activity Object",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_user_activity_object"),
							"title" => "Add new User Activity Object",
							"class" => "",
						),
					)
				),
				array(
					"label" => "Object Users",
					"menus" => array(
						array(
							"label" => "Object Users List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_object_users"),
							"title" => "View List of Object Users",
							"class" => "",
						),
						array(
							"label" => "Add Object User",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_object_user"),
							"title" => "Add new Object User",
							"class" => "",
						),
					)
				),
				array(
					"label" => "User Environments",
					"menus" => array(
						array(
							"label" => "User Environments List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_user_environments"),
							"title" => "View List of User Environments",
							"class" => "",
						),
						array(
							"label" => "Add User Environment",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_user_environment"),
							"title" => "Add new User Environment",
							"class" => "",
						),
					)
				),
				array(
					"label" => "User Sessions",
					"menus" => array(
						array(
							"label" => "User Sessions List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_user_sessions"),
							"title" => "View List of User Sessions",
							"class" => "",
						),
						array(
							"label" => "Add User Session",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_user_session"),
							"title" => "Add new User Session",
							"class" => "",
						),
					)
				),
				array(
					"label" => "Settings",
					"class" => "settings",
					"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_settings"),
					"title" => "Edit this module settings",
				),
			)
		);
	}
	
	public function initUsers($brokers) {
		$this->activities = UserUtil::getAllActivities($brokers, true);
		$this->user_types = UserUtil::getAllUserTypes($brokers, true);
		
		$this->activities = $this->activities ? $this->activities : array();
		$this->user_types = $this->user_types ? $this->user_types : array();
	}
	
	public function getAvailableActivities() {
		$available_activities = array();
		foreach ($this->activities as $activity) 
			$available_activities[ $activity["activity_id"] ] = $activity["name"];
		
		return $available_activities;
	}
	
	public function getActivityOptions($data) {
		$activity_options = array( array("value" => "", "label" => "") ); //ad default empty option
		$default_id = $data ? $data["activity_id"] : null;
		$exists = false;
		
		foreach ($this->activities as $activity) {
			$activity_options[] = array("value" => $activity["activity_id"], "label" => $activity["name"]);
			
			if ($default_id && $activity["activity_id"] == $default_id)
				$exists = true;
		}
		
		if ($default_id && !$exists)
			$activity_options[] = array("value" => $default_id, "label" => $default_id);
		
		return $activity_options;
	}
	
	public function getAvailableUserTypes() {
		$available_user_types = array();
		foreach ($this->user_types as $user_type) 
			$available_user_types[ $user_type["user_type_id"] ] = $user_type["name"];
		
		return $available_user_types;
	}
	
	public function getUserTypeOptions($data) {
		$user_type_options = array( array("value" => "", "label" => "") ); //ad default empty option
		$default_id = $data ? $data["user_type_id"] : null;
		$exists = false;
		
		foreach ($this->user_types as $user_type) {
			$user_type_options[] = array("value" => $user_type["user_type_id"], "label" => $user_type["name"]);
			
			if ($default_id && $user_type["user_type_id"] == $default_id)
				$exists = true;
		}
		
		if ($default_id && !$exists)
			$user_type_options[] = array("value" => $default_id, "label" => $default_id);
		
		return $user_type_options;
	}
}
?>
