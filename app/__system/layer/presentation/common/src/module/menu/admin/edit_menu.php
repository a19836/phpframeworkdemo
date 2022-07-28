<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("menu/admin/MenuAdminUtil", $common_project_name);
	
	$MenuAdminUtil = new MenuAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$group_id = $_GET["group_id"];
	$data = $group_id ? MenuUtil::getMenuGroupsByConditions($brokers, array("group_id" => $group_id), null, null, true) : null;
	$data = $data[0];
	
	if ($data) {
		$data["tags"] = TagUtil::getObjectTagsString($brokers, ObjectUtil::MENU_OBJECT_TYPE_ID, $data["group_id"], array(), true);
			
		$options = array("sort" => array(array("column" => "order", "order" => "asc")));
		$items = MenuUtil::getMenuItemsByConditions($brokers, array("group_id" => $data["group_id"]), null, $options, true);
		$items = MenuUtil::encapsulateMenuGroupItems($items);
	}
	
	//Saving Menu
	if ($_POST) {
		if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = !$data || (MenuUtil::deleteMenuItemsByGroupId($brokers, $data["group_id"]) && MenuUtil::deleteMenuGroup($brokers, $data["group_id"]));
		}
		else if ($_POST["add"] || $_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			$name = $_POST["name"];
			$tags = $_POST["tags"];
			$old_items = $items;
			$items = $_POST["menu_items"];
			
			//Saving new group data
			if (!$data || $name != $data["name"]) {
				if (empty($name)) {
					$error_message = "Group Name cannot be undefined!";
				}
				else {
					$data["name"] = $name;
					
					if ($data["group_id"])
						$data["object_groups"] = MenuUtil::getMenuObjectGroupsByConditions($brokers, array("group_id" => $data["group_id"]), null, false, true);
					
					$status = $_POST["add"] ? MenuUtil::insertMenuGroup($brokers, $data) : MenuUtil::updateMenuGroup($brokers, $data);
					
				}
			}
			else {
				$status = true;
			}
			
			//Saving new items data
			if ($status && !$error_message) {
				$group_id = $data["group_id"] ? $data["group_id"] : $status;
				
				if ($tags != $data["tags"])
					$status = TagUtil::updateObjectTags($brokers, $tags, ObjectUtil::MENU_OBJECT_TYPE_ID, $group_id);
				
				if ($status)
					$status = deleteOldItems($brokers, $old_items, $items) && saveMenuItems($brokers, $settings, $items, $error_message, $group_id);
			}
		}
		
		if ($action) {
			if ($status) {
				$status_message = "Menu ${action}d successfully!";
			
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_menu") . "group_id=$group_id";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this menu. Please try again...";
			}
		}
	}
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Menu Group '$group_id'" : "Add Menu Group",
		"fields" => array(
			"name" => "text",
			"tags" => "text",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
		"form_on_submit" => "saveMenu(this)",
		"next_html" => '
			<div class="menu_items">
				<label>Items: </label>
				<span class="icon add" onClick="addMenuItem(this)">Add</span>
			
				<div class="items"></div>
			</div>',
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_menu.css" type="text/css" charset="utf-8" />
	<script src="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_menu.js" type="text/javascript"></script>
	<script>
		var menu_item_html = \'' . addcslashes(str_replace("\n", "", getItemHtml()), "\\'") . '\';
		var menu_items = ' . json_encode($items, true) . ';
	</script>';
	$menu_settings = $MenuAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);

function getItemHtml() {
	$html = '
	<div class="menu_item">
		<div class="item_id">
			<input type="hidden" />
		</div>
		
		<div class="item_label">
			<label>Label: </label>
			<input type="text" value="">
		</div>
		<span class="icon maximize" onClick="toggleMenuItem(this)">Toggle</span>
		<span class="icon add" onClick="addMenuItem(this)">Add</span>
		<span class="icon up" onClick="moveUpMenuItem(this)">Move Up</span>
		<span class="icon down" onClick="moveDownMenuItem(this)">Move Down</span>
		<span class="icon out" onClick="moveOutMenuItem(this)">Move Out</span>
		<span class="icon in" onClick="moveInMenuItem(this)">Move In</span>
		<span class="icon delete" onClick="removeMenuItem(this)">Remove</span>
		
		<div class="item_url">
			<label>Url: </label>
			<input type="text" value="">
		</div>
		<div class="item_title">
			<label>Title: </label>
			<input type="text" value="">
		</div>
		<div class="item_class">
			<label>Class: </label>
			<input type="text" value="">
		</div>
		<div class="item_previous_html">
			<label>Previous Html: </label>
			<textarea type="text"></textarea>
		</div>
		<div class="item_next_html">
			<label>Next Html: </label>
			<textarea type="text"></textarea>
		</div>
		
		<div class="items"></div>
	</div>';
	
	return $html;
}
	
function saveMenuItems($brokers, $settings, &$items, &$error_message, $group_id, $parent_id = 0) {
	if (is_array($items)) {
		$order = 1;
		
		foreach ($items as $idx => $item) {
			if (empty($item["label"])) {
				$error_message = "Item Label cannot be undefined!";
				return false;
			}
			else {
				$new_data = $item;
				unset($new_data["items"]);
				$new_data["group_id"] = $group_id;
				$new_data["parent_id"] = $parent_id;
				$new_data["order"] = $order;
				
				if (!$new_data["item_id"])
					$new_data["item_id"] = MenuUtil::insertMenuItem($brokers, $new_data);
				else if (!MenuUtil::updateMenuItem($brokers, $new_data))
					return false;
				
				if ($new_data["item_id"]) {
					$items[$idx]["item_id"] = $new_data["item_id"];
					
					if (!saveMenuItems($brokers, $settings, $items[$idx]["items"], $error_message, $group_id, $new_data["item_id"]))
						return false;
				}
				else
					return false;
			}
			
			$order++;
		}
	}
	
	return true;
}

function deleteOldItems($brokers, $old_items, $new_items) {
	$status = true;
	
	$new_items_decapsulated = \MenuUtil::decapsulateMenuGroupItems($new_items);
	$old_items_decapsulated = \MenuUtil::decapsulateMenuGroupItems($old_items);
	
	$new_items_ids = array();
	foreach ($new_items_decapsulated as $idx => $new_item)
		if ($new_item["item_id"])
			$new_items_ids[ $new_item["item_id"] ] = $idx;
	
	$t = count($old_items_decapsulated);
	for ($i = $t - 1; $i >= 0; --$i) {
		$old_item = $old_items_decapsulated[$i];
		$idx = $new_items_ids[ $old_item["item_id"] ];
		
		if (!isset($idx) && !\MenuUtil::deleteMenuItem($brokers, $old_item["item_id"]))
			$status = false;
	}
	
	return $status;
}
?>
