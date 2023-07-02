<?php
namespace CMSModule\objectsgroup\edit_object_objects_group;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("objectsgroup/ObjectsGroupUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Getting Object ObjectsGroups
		$objects_group_id = $_GET["objects_group_id"];
		$object_type_id = $_GET["object_type_id"];
		$object_id = $_GET["object_id"];
		
		$data = $objects_group_id && $object_type_id && $object_id ? \ObjectsGroupUtil::getObjectObjectsGroupsByConditions($brokers, array("objects_group_id" => $objects_group_id, "object_type_id" => $object_type_id, "object_id" => $object_id), null, null, true) : null;
		$data = $data[0];
		
		//Preparing Action
		if ($_POST) {
			if ($_POST["delete"] && $settings["allow_deletion"]) {
				$status = !$data || \ObjectsGroupUtil::deleteObjectObjectsGroup($brokers, $data["objects_group_id"], $data["object_type_id"], $data["object_id"]);
			}
			else if ($_POST["save"]) {
				$new_objects_group_id = $_POST["objects_group_id"];
				$new_object_type_id = $_POST["object_type_id"];
				$new_object_id = $_POST["object_id"];
				$new_group = $_POST["group"];
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("objects_group_id" => $new_objects_group_id, "object_type_id" => $new_object_type_id, "object_id" => $new_object_id, "group" => $new_group));
				if ($empty_field_name) {
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				}
				else {
					if ($settings["allow_insertion"] && empty($data)) {
						$new_data = $data;
						$new_data["objects_group_id"] = $new_objects_group_id;
						$new_data["object_type_id"] = $new_object_type_id;
						$new_data["object_id"] = $new_object_id;
						$new_data["group"] = $new_group;
						
						\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
						
						if (\CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message)) {
							$new_data["object_objects_groups"] = $settings["object_to_objects"];
							
							$status = \ObjectsGroupUtil::insertObjectObjectsGroup($brokers, $new_data);
							if (strpos($settings["on_insert_ok_action"], "_redirect") !== false) {
								$settings["on_insert_ok_redirect_url"] .= (strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "objects_group_id=${new_data['objects_group_id']}&object_type_id=${new_data['object_type_id']}&object_id=${new_data['object_id']}";
							}
						}
					}
					else if ($settings["allow_update"] && $data) {
						$new_data = array();
						$new_data["old_objects_group_id"] = $data["objects_group_id"];
						$new_data["old_object_type_id"] = $data["object_type_id"];
						$new_data["old_object_id"] = $data["object_id"];
						$new_data["new_objects_group_id"] = $settings["show_objects_group_id"] ? $new_objects_group_id : $data["objects_group_id"];
						$new_data["new_object_type_id"] = $settings["show_object_type_id"] ? $new_object_type_id : $data["object_type_id"];
						$new_data["new_object_id"] = $settings["show_object_id"] ? $new_object_id : $data["object_id"];
						$new_data["group"] = $new_group;
						
						\CommonModuleUI::prepareFieldWithDefaultValue($settings, "objects_group_id", $new_data["new_objects_group_id"]);
						\CommonModuleUI::prepareFieldWithDefaultValue($settings, "object_type_id", $new_data["new_object_type_id"]);
						\CommonModuleUI::prepareFieldWithDefaultValue($settings, "object_id", $new_data["new_object_id"]);
						
						$fields_to_validade = array("objects_group_id" => $new_data["new_objects_group_id"], "object_type_id" => $new_data["new_object_type_id"], "object_id" => $new_data["new_object_id"], "group" => $new_data["group"]);
						
						if (\CommonModuleUI::areFieldsValid($EVC, $settings, $fields_to_validade, $error_message)) {
							$new_data["object_objects_groups"] = $settings["object_to_objects"];
							
							$status = \ObjectsGroupUtil::updateObjectObjectsGroup($brokers, $new_data);
							if (strpos($settings["on_update_ok_action"], "_redirect") !== false) {
								$settings["on_update_ok_redirect_url"] .= (strpos($settings["on_update_ok_redirect_url"], "?") !== false ? "&" : "?") . "objects_group_id=${new_data['new_objects_group_id']}&object_type_id=${new_data['new_object_type_id']}&object_id=${new_data['new_object_id']}";
							}
						}
					}
				}
			}
		}
		
		if ($_POST["save"]) {
			$form_data = array(
				"objects_group_id" => $settings["show_objects_group_id"] ? $new_objects_group_id : $data["objects_group_id"],
				"object_type_id" => $settings["show_object_type_id"] ? $new_object_type_id : $data["object_type_id"],
				"object_id" => $settings["show_object_id"] ? $new_object_id : $data["object_id"],
				"group" => $settings["show_group"] ? $new_group : $data["group"],
			);
			$form_data = $new_data ? array_merge($new_data, $form_data) : ($settings["allow_view"] && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else {
			$form_data = $settings["allow_view"] && $data ? $data : array();
		}
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/objectsgroup/edit_object_objects_group.css';
		$settings["class"] = "module_edit_object_objects_group";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		
		$is_editable = ($settings["allow_update"] && $data) || ($settings["allow_insertion"] && !$data);
		
		if ($settings["show_object_type_id"]) {
			include_once $EVC->getModulePath("object/ObjectUtil", $common_project_name);
			
			$object_types = \ObjectUtil::getAllObjectTypes($brokers);
			$object_type_options = array();
			$available_object_types = array();
			
			if ($object_types) {
				$t = count($object_types);
				for ($i = 0; $i < $t; $i++) {
					if ($is_editable) 
						$object_type_options[] = array("value" => $object_types[$i]["object_type_id"], "label" => $object_types[$i]["name"]);
					else
						$available_object_types[ $object_types[$i]["object_type_id"] ] = $object_types[$i]["name"];
				}
			}
			
			$settings["fields"]["object_type_id"]["field"]["input"]["type"] = $is_editable ? "select" : "label";
			$settings["fields"]["object_type_id"]["field"]["input"]["options"] = $object_type_options;
			$settings["fields"]["object_type_id"]["field"]["input"]["available_values"] = $available_object_types;
		}
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "objectsgroup/edit_object_objects_group", $settings);
		return \CommonModuleUI::getFormHtml($EVC, $settings);
	}
}
?>
