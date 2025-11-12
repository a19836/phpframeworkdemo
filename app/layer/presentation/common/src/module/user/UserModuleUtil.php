<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

namespace CMSModule\user;

include_once $EVC->getModulePath("common/CommonModuleUtil", $EVC->getCommonProjectName());

class UserModuleUtil extends \CommonModuleUtil {
	
	public static function prepareListSettingsFields($EVC, &$settings) {
		if ($settings && !empty($settings["fields"]))
			foreach ($settings["fields"] as $field_name => $field)
				if (!empty($settings["show_" . $field_name]))
					switch ($field_name) {
						case "activity_id":
							self::prepareActivityIdListSettingsField($EVC, $settings, $field_name);
							break;
						
						case "object_type_id":
							self::prepareObjectTypeIdListSettingsField($EVC, $settings, $field_name);
							break;
						
						case "user_type_id":
							self::prepareUserTypeIdListSettingsField($EVC, $settings, $field_name);
							break;
						
						case "user_id":
							self::prepareUserIdListSettingsField($EVC, $settings, $field_name);
							break;
						
						case "username":
							self::prepareUsernameListSettingsField($EVC, $settings, $field_name);
							break;
					}
	}
	
	public static function prepareFormSettingsFields($EVC, &$settings, $is_editable) {
		if ($settings && !empty($settings["fields"]))
			foreach ($settings["fields"] as $field_name => $field)
				if (!empty($settings["show_" . $field_name]))
					switch ($field_name) {
						case "activity_id":
							self::prepareActivityIdFormSettingsField($EVC, $settings, $is_editable, $field_name);
							break;
						
						case "object_type_id":
							self::prepareObjectTypeIdFormSettingsField($EVC, $settings, $is_editable, $field_name);
							break;
						
						case "user_type_id":
							self::prepareUserTypeIdFormSettingsField($EVC, $settings, $is_editable, $field_name);
							break;
						
						case "user_id":
							self::prepareUserIdFormSettingsField($EVC, $settings, $is_editable, $field_name);
							break;
						
						case "username":
							self::prepareUserIdFormSettingsField($EVC, $settings, $is_editable, $field_name);
							break;
					}
	}
	
	public static function prepareActivityIdListSettingsField($EVC, &$settings, $field_name = "activity_id") {
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$type = isset($settings["fields"][$field_name]["field"]["input"]["type"]) ? $settings["fields"][$field_name]["field"]["input"]["type"] : null;
		$allow_options = $type == "select" || $type == "radio" || $type == "checkbox";
		
		$activities = \UserUtil::getAllActivities($brokers);
		$activity_options = array( array("value" => "", "label" => "") ); //ad default empty option
		$available_activities = array();
		$existent_ids = array();
		
		if ($activities) 
			foreach ($activities as $activity) {
				$activity_id = isset($activity["activity_id"]) ? $activity["activity_id"] : null;
				$activity_name = isset($activity["name"]) ? $activity["name"] : null;
				
				if ($allow_options)
					$activity_options[] = array("value" => $activity_id, "label" => /*$activity_id . ": " . */$activity_name);
				else 
					$available_activities[$activity_id] = /*$activity_id . ": " . */$activity_name;
				
				$existent_ids[] = $activity_id;
			}
		
		if ($allow_options && !empty($settings["data"]))
			foreach ($settings["data"] as $item)
				if (isset($item["activity_id"]) && is_numeric($item["activity_id"]) && !in_array($item["activity_id"], $existent_ids)) {
					$activity_options[] = array("value" => $item["activity_id"], "label" => $item["activity_id"]);
					$existent_ids[] = $item["activity_id"];
				}
		
		$settings["fields"][$field_name]["field"]["input"]["options"] = $activity_options;
		$settings["fields"][$field_name]["field"]["input"]["available_values"] = $available_activities;
	}
	
	public static function prepareActivityIdFormSettingsField($EVC, &$settings, $is_editable, $field_name = "activity_id") {
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$activities = \UserUtil::getAllActivities($brokers);
		$activity_options = array( array("value" => "", "label" => "") ); //ad default empty option
		$available_activities = array();
		
		$default_id = isset($settings["form_data"]["activity_id"]) ? $settings["form_data"]["activity_id"] : null;
		$exists = false;
		
		if ($activities)
			foreach ($activities as $activity) {
				$activity_id = isset($activity["activity_id"]) ? $activity["activity_id"] : null;
				$activity_name = isset($activity["name"]) ? $activity["name"] : null;
				
				if ($is_editable) 
					$activity_options[] = array("value" => $activity_id, "label" => /*$activity_id . ": " . */$activity_name);
				else
					$available_activities[$activity_id] = /*$activity_id . ": " . */$activity_name;
				
				if (is_numeric($default_id) && $activity_id == $default_id)
					$exists = true;
			}
		
		if ($is_editable && is_numeric($default_id) && !$exists)
			$activity_options[] = array("value" => $default_id, "label" => $default_id);
		
		$settings["fields"][$field_name]["field"]["input"]["type"] = $is_editable ? "select" : "label";
		$settings["fields"][$field_name]["field"]["input"]["options"] = $activity_options;
		$settings["fields"][$field_name]["field"]["input"]["available_values"] = $available_activities;
	}
	
	public static function prepareUserTypeIdListSettingsField($EVC, &$settings, $field_name = "user_type_id") {
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$type = isset($settings["fields"][$field_name]["field"]["input"]["type"]) ? $settings["fields"][$field_name]["field"]["input"]["type"] : null;
		$allow_options = $type == "select" || $type == "radio" || $type == "checkbox";
		
		$user_types = \UserUtil::getAllUserTypes($brokers);
		$user_type_options = array( array("value" => "", "label" => "") ); //ad default empty option
		$available_user_types = array();
		$existent_ids = array();
		
		if ($user_types)
			foreach ($user_types as $user_type) {
				$user_type_id = isset($user_type["user_type_id"]) ? $user_type["user_type_id"] : null;
				$user_type_name = isset($user_type["name"]) ? $user_type["name"] : null;
				
				if ($allow_options)
					$user_type_options[] = array("value" => $user_type_id, "label" => /*$user_type_id . ": " . */$user_type_name);
				else 
					$available_user_types[$user_type_id] = /*$user_type_id . ": " . */$user_type_name;
				
				$existent_ids[] = $user_type_id;
			}
		
		if ($allow_options && !empty($settings["data"]))
			foreach ($settings["data"] as $item)
				if (isset($item["user_type_id"]) && is_numeric($item["user_type_id"]) && !in_array($item["user_type_id"], $existent_ids)) {
					$user_type_options[] = array("value" => $item["user_type_id"], "label" => $item["user_type_id"]);
					$existent_ids[] = $item["user_type_id"];
				}
		
		$settings["fields"][$field_name]["field"]["input"]["options"] = $user_type_options;
		$settings["fields"][$field_name]["field"]["input"]["available_values"] = $available_user_types;
	}
	
	public static function prepareUserTypeIdFormSettingsField($EVC, &$settings, $is_editable, $field_name = "user_type_id") {
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$user_types = \UserUtil::getAllUserTypes($brokers);
		$user_type_options = array( array("value" => "", "label" => "") ); //ad default empty option
		$available_user_types = array();
		
		$default_id = !empty($settings["form_data"]) ? $settings["form_data"]["user_type_id"] : null;
		$exists = false;
		
		if ($user_types) 
			foreach ($user_types as $user_type) {
				$user_type_id = isset($user_type["user_type_id"]) ? $user_type["user_type_id"] : null;
				$user_type_name = isset($user_type["name"]) ? $user_type["name"] : null;
				
				if ($is_editable) 
					$user_type_options[] = array("value" => $user_type_id, "label" => /*$user_type_id . ": " . */$user_type_name);
				else
					$available_user_types[$user_type_id] = /*$user_type_id . ": " . */$user_type_name;
				
				if (is_numeric($default_id) && $user_type_id == $default_id)
					$exists = true;
			}
		
		if ($is_editable && is_numeric($default_id) && !$exists)
			$user_type_options[] = array("value" => $default_id, "label" => $default_id);
		
		$settings["fields"][$field_name]["field"]["input"]["type"] = $is_editable ? "select" : "label";
		$settings["fields"][$field_name]["field"]["input"]["options"] = $user_type_options;
		$settings["fields"][$field_name]["field"]["input"]["available_values"] = $available_user_types;
	}
}
?>
