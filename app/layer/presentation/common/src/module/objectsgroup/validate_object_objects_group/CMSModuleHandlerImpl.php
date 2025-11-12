<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

namespace CMSModule\objectsgroup\validate_object_objects_group;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include_once $EVC->getModulePath("common/ObjectToObjectValidationHandler", $common_project_name);
		include_once $EVC->getModulePath("objectsgroup/ObjectsGroupUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$objects_group_id = isset($settings["objects_group_id"]) ? $settings["objects_group_id"] : null;
		$object_type_id = isset($settings["object_type_id"]) ? $settings["object_type_id"] : null;
		$object_id = isset($settings["object_id"]) ? $settings["object_id"] : null;
		
		if (isset($settings["group"]) && is_numeric($settings["group"]))
			$result = \ObjectsGroupUtil::getObjectObjectsGroupsByConditions($brokers, array(
				"objects_group_id" => $objects_group_id, 
				"object_type_id" => $object_type_id, 
				"object_id" => $object_id,
				"group" => $settings["group"],
			), null);
		else
			$result = \ObjectsGroupUtil::getObjectObjectsGroup($brokers, $objects_group_id, $object_type_id, $object_id);
		
		$status = !empty($result);
		
		return \ObjectToObjectValidationHandler::validate($EVC, $status, $settings);
	}
}
?>
