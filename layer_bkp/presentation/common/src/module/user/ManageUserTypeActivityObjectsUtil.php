<?php
include_once get_lib("org.phpframework.util.HashCode");

class ManageUserTypeActivityObjectsUtil {
	
	public static function getHtml($EVC, $settings = null) {
		$common_project_name = $EVC->getCommonProjectName();
		include_once $EVC->getModulePath("object/ObjectUtil", $common_project_name);
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Preparing data
		$add_action = $_GET["action"] == "add";
		
		if ($_POST) {
			if ($add_action) {
				$data = array(
					"user_type_id" => $_POST["user_type_id"],
					"object_type_id" => $_POST["object_type_id"],
					"object_id" => $_POST["object_id"],
					"activity_id" => $_POST["activity_id"],
				);
				
				if (UserUtil::insertUserTypeActivityObject($brokers, $data)) {
					return "<script>
						alert('" . translateProjectText($EVC, "Object Activity inserted successfully") . ". " . translateProjectText($EVC, "Please do not forget to delete the cache.") . "');
						goToUserTypeId('" . $data["user_type_id"] . "');
					</script>";
				}
				else {
					$message = translateProjectText($EVC, "There was an error trying to insert this object activity. Please try again...");
				}
			}
			else {
				$user_type_id = $_POST["user_type_id"];
				$user_type_activities = $_POST["user_type_activities"];
				$status = true;
				
				if ($user_type_id && UserUtil::deleteUserTypeActivityObjectsByUserTypeId($brokers, $user_type_id)) {
					if ($user_type_activities) {
						foreach ($user_type_activities as $object_type_id => $object_activities) {
							foreach ($object_activities as $object_id => $activities) {
								foreach ($activities as $activity_id => $aux) {
									$data = array(
										"user_type_id" => $user_type_id,
										"object_type_id" => $object_type_id,
										"object_id" => $object_id,
										"activity_id" => $activity_id,
									);
									
									if (!UserUtil::insertUserTypeActivityObject($brokers, $data)) {
										$status = false;
									}
								}
							}
						}
					}
				}
				else {
					$status = false;
				}
						
				if (!$status) {
					$message = translateProjectText($EVC, "There was an error trying to save the user object activities. Please try again...");
				}
				else {
					$html .= "<script>alert('" . translateProjectText($EVC, "Data saved successfully...") . " " . translateProjectText($EVC, "Please do not forget to delete the cache.") . "');</script>";
				}
			}
		}
		
		$activities = UserUtil::getAllActivities($brokers);
		$activities = $activities ? $activities : array();
		
		if (!$add_action) {
			$user_types = UserUtil::getAllUserTypes($brokers);
			$user_types = $user_types ? $user_types : array();
		
			$selected_user_type_id = $_GET["user_type_id"] ? $_GET["user_type_id"] : $user_types[0]["user_type_id"];
			
			$user_type_activities = UserUtil::getUserTypeActivityObjectsByConditions($brokers, array("user_type_id" => $selected_user_type_id), null);
			$user_type_activities = $user_type_activities ? $user_type_activities : array();
		
			$object_types_activities = array();
			foreach ($user_type_activities as $user_type_activity) {
				$object_types_activities[ $user_type_activity["object_type_id"] ][ $user_type_activity["object_id"] ][ $user_type_activity["activity_id"] ] = true;
			}
		
			$object_types = ObjectUtil::getAllObjectTypes($brokers);
			$object_types = $object_types ? $object_types : array();
		
			$pages = self::getPages($EVC);
			$modules = self::getModules($EVC);
		}
		
		//Preparing HTML
		if ($add_action) {
			$html .= '
			<div class="module_add_user_type_activity_object ' . $settings["block_class"] . '">
				<div class="title">' . translateProjectText($EVC, "Add New User Type Activity") . '</div>';
			
			if ($message) {
				$html .= '<div class="message">' . $message . '</div>';
			}
			
			$html .= '<form method="post">
				<input type="hidden" name="object_type_id" value="' . $_GET["object_type_id"] . '" />
				<input type="hidden" name="user_type_id" value="' . $_GET["user_type_id"] . '" />
			
				<div class="activity_id">
					<label>' . translateProjectText($EVC, "Activity") . ': </label>
					<select name="activity_id">';
			foreach ($activities as $activity) {
				$html .= '<option value="' . $activity["activity_id"] . '"' . ($activity["activity_id"] == $data["activity_id"] ? ' selected' : '') . '>' . $activity["name"] . '</option>';
			}
			
			$html .= '</select>
				</div>
			
				<div class="object_id">
					<label>' . translateProjectText($EVC, "Object Id") . ': </label>
					<input type="text" name="object_id" value="' . $data["object_id"] . '" />
				</div>
				
				<div class="submit_button">
					<input type="submit" name="save" value="' . translateProjectText($EVC, "Add") . '" />
				</div>
				
				<div class="go_back">
					' . translateProjectText($EVC, "To go back please click") . ' <a href="?bean_name=' . $_GET["bean_name"] . "&bean_file_name=" . $_GET["bean_file_name"] . "&path=" . $_GET["path"] . '&user_type_id=' . $_GET["user_type_id"] . '">' . translateProjectText($EVC, "here") . '</a>
				</div>
			</form>
			</div>';
		}
		else {
			$html .= '<div class="module_manage_user_type_activity_objects ' . $settings["block_class"] . '">
			<div class="title">' . translateProjectText($EVC, "Manage User Type Activities") . '</div>';
			
			if ($message) {
				$html .= '<div class="message">' . $message . '</div>';
			}
			
			$html .= '
			<form method="post">
				<div class="user_type_id">
					<label>' . translateProjectText($EVC, "User Type") . ':</label>
					<select name="user_type_id" onChange="changeUserTypeId(this)">';
		
			$t = $user_types ? count($user_types) : 0;
			for ($i = 0; $i < $t; $i++) {
				$html .= '<option value="' . $user_types[$i]["user_type_id"] . '" ' . ($selected_user_type_id == $user_types[$i]["user_type_id"] ? 'selected' : '') . '>' . $user_types[$i]["name"] . '</option>';
			}
				
			$html .= '	</select>
				</div>
				
				<div class="object_type_vs_acivities_title">' . translateProjectText($EVC, "Object types' Activities") . '</div>
		
				<ul>';
		
			foreach($object_types as $object_type) {
				$html .= self::getObjectTypeHtml($EVC, $selected_user_type_id, $object_type["object_type_id"], $object_type["name"], $activities, $object_types_activities, $pages, $modules);
			}
		
			if ($object_types_activities) {
				$html .= '
					<li>
						<div class="object_type_header object_type_header_other_types">
							<label>' . translateProjectText($EVC, "Other Object Types") . ':</label>   
						</div>
						<ul>';
			
				foreach ($object_types_activities as $object_type_id => $object_activities) {
					$html .= self::getObjectTypeHtml($EVC, $selected_user_type_id, $object_type_id, "Object Type " . $object_type_id, $activities, $object_types_activities, $pages, $modules);
				}
			
				$html .= '	</ul>
					</li>';
			}
			
			$html .= '
				</ul>
				
				<div class="submit_button">
					<input type="submit" name="save" value="' . translateProjectText($EVC, "Save") . '" onClick="this.setAttribute(\'disabled\', \'disabled\'); this.value=\'Saving... Please wait...\'; this.form.submit()" />
				</div>
			</form>
			</div>';
		}
		
		return $html;
	}
	
	private static function getPages($EVC) {
		$projects = $EVC->getProjectsId();
		
		$P = $EVC->getPresentationLayer();
		$selected_project_name = $P->getSelectedPresentationId();
		$common_project_name = $P->getCommonProjectName();
		
		if ($projects[0] != $selected_project_name || $projects[1] != $common_project_name) {
			$projects = array_flip($projects);
			unset($projects[$selected_project_name]);
			unset($projects[$common_project_name]);
			
			$projects = array_merge(array($selected_project_name, $common_project_name), array_flip($projects));
		}
		
		$files = array();
		foreach ($projects as $project) {
			$folder_path = $EVC->getEntitiesPath($project);
			$items = self::getFolderFilesList($folder_path, $folder_path);
			ksort($items);
			
			foreach ($items as $file_path => $file) {
				if ($file["type"] != "folder") {
					$extension = pathinfo($file_path, PATHINFO_EXTENSION);
					
					if (strtolower($extension) == "php") {
						$fc = substr($file_path, 0, -4);
						$ap = $EVC->getEntityPath($fc, $project);
						$code = UserUtil::getObjectIdFromFilePath($ap);
						
						$files[$project][$code] = array($fc, null);
					}
				}
			}
		}
		
		//echo "<pre>";print_r($files);die();
		return $files;
	}
	
	private static function getModules($EVC) {
		$common_project_name = $EVC->getCommonProjectName();
		
		$folder_path = $EVC->getModulesPath($common_project_name);
		$items = self::getFolderFilesList($folder_path, $folder_path);
		ksort($items);
		
		$reserved_files = array("enable", "CMSModuleHandlerImpl");
		
		foreach ($items as $file_path => $file) {
			if ($file["type"] != "folder") {
				$path_info = pathinfo($file_path);
				
				if (!in_array($path_info["filename"], $reserved_files)) {
					if (strtolower($path_info["extension"]) == "php") {
						$fc = substr($file_path, 0, -4);
						$ap = $EVC->getModulePath($fc, $common_project_name);
						
						if (!self::isClassFile($ap)) {
							$activities = self::getFileActivities($ap);
							$code = UserUtil::getObjectIdFromFilePath($ap);
							
							$files[$common_project_name][$code] = array($fc, $activities);
						}
					}
				}
			}
		}
		
		return $files;
	}
	
	private static function isClassFile($path) {
		$path_info = pathinfo($path);
		$first_char = substr($path_info["filename"], 0, 1);
		
		if ($first_char == strtoupper($first_char)) {
			$contents = file_get_contents($path);
			return preg_match('/([};\s]*)class ' . $path_info["filename"] . '([\{\s]+)/u', $contents); //'/u' means with accents and ç too.
		}
		return false;
	}
	
	private static function getFileActivities($path) {
		$activities = array();
		
		$contents = file_get_contents($path);
		
		preg_match_all('/(validateModuleUserActivity|validatePageUserActivity)\(([^,]+),([^;]+),([^)]+)\)/u', $contents, $matches, PREG_PATTERN_ORDER); //'/u' means with accents and ç too.
		
		if ($matches[3]) {
			foreach ($matches[3] as $match) {
				preg_match_all('/array(\s*)\((.+)\)/u', $match, $sub_matches, PREG_PATTERN_ORDER); //'/u' means with accents and ç too.
				$sub_matches = explode(",", str_replace(array("'", '"'), "", $sub_matches[2][0] ? $sub_matches[2][0] : $match));
				
				foreach ($sub_matches as $m) 
					$activities[ trim($m) ] = true;
			}
		}
		
		return $activities;
	}
	
	private static function getFolderFilesList($main_folder_path, $folder_path, $only_folders = false) {
		$files = array();
	
		if (is_dir($folder_path) && ($dir = opendir($folder_path)) ) {
			while( ($file = readdir($dir)) !== false) {
				if (substr($file, 0, 1) != ".") {
					$fp = $folder_path . $file;
				
					$path = str_replace($main_folder_path, "", $fp);
					
					if (is_dir($fp)) {
						$files[$path] = array(
							"type" => "folder",
							"name" => $file,
						);
						
						$sub_files = self::getFolderFilesList($main_folder_path, $fp . "/", $only_folders);
						$files = array_merge($files, $sub_files);
					}
					else if (!$only_folders) {
						$path_info = pathinfo($file);
						
						$files[$path] = array(
							"type" => "file",
							"name" => $path_info["filename"],
						);
					}
				}
			}
			
			closedir($dir);
		}
	
		return $files;
	}
	
	private static function getObjectTypeHtml($EVC, $user_type_id, $object_type_id, $object_type_name, $activities, &$object_types_activities, $pages, $modules) {
		$is_module_type = $object_type_id == ObjectUtil::MODULE_OBJECT_TYPE_ID;
		$is_page_type = $object_type_id == ObjectUtil::PAGE_OBJECT_TYPE_ID;
		$colspan = ($activities ? count($activities) : 0) + 3;
		$is_predefined_file_path = $is_page_type || $is_module_type;
		$is_hidden = !$is_page_type;
		
		$query_string = "bean_name=" . $_GET["bean_name"] . "&bean_file_name=" . $_GET["bean_file_name"] . "&path=" . $_GET["path"];
		
		$html = '
		<li>
			<div class="object_type_header object_type_header_' . str_replace(array(" ", "-"), "_", strtolower($object_type_name)) . '">
				<label>' . translateProjectText($EVC, ucwords(str_replace(array("_", "-"), " ", $object_type_name))) . ':</label>
				<span class="icon ' . ($is_hidden ? 'maximize' : 'minimize') . '" onClick="togglePanel(this)">' . translateProjectText($EVC, "Minimize/Maximize") . '</span>';
		
		if (!$is_predefined_file_path) {
			$html .= '<span class="icon add" onClick="addObjectActivity(\'?' . $query_string . '&action=add&user_type_id=' . $user_type_id . '&object_type_id=' . $object_type_id . '\')" title="' . str_replace("#object_type_name#", translateProjectText($EVC, $object_type_name), translateProjectText($EVC, "Add new activity object '#object_type_name#'")) . '">' . translateProjectText($EVC, "Add") . '</span>';
		}
		
		$object_id_column_label = $is_page_type ? "Page Path" : ($is_module_type ? "Module File" : "Object Id");
		
		$html .= '
			</div>
			<table' . ($is_hidden ? ' style="display:none"' : '') . '>
				<tr>
					<th class="object_id">' . translateProjectText($EVC, $object_id_column_label) . '</th>';
	
		$project_html = '
		<tr class="group_name">
			<td class="group_name_label">';
		
		if ($is_page_type)
			$project_html .= '<span class="icon #toggle_icon_class#" onClick="toggleGroupFiles(this)">' . translateProjectText($EVC, "Minimize/Maximize") . '</span>';
		
		$project_html .= '#title#</td>';
		
		foreach($activities as $activity) {
			$html .= '	<th class="activity activity_' . str_replace(array(" ", "-"), "_", strtolower($activity["name"])) . ' activity_' . $activity["activity_id"] . '">
				<label> ' . translateProjectText($EVC, ucwords(str_replace(array("_", "-"), " ", $activity["name"]))) . '</label>
				<input type="checkbox" onClick="toggleCheckboxes(this, \'activity_' . $activity["activity_id"] . '\')" />
			</th>';
			
			$project_html .= '
			<td class="activity activity_' . str_replace(array(" ", "-"), "_", strtolower($activity["name"])) . ' activity_' . $activity["activity_id"] . '">
				<input type="checkbox" onClick="toggleCheckboxes(this, \'activity_' . $activity["activity_id"] . '\')" />
			</td>';
		}
		
		$html .= '		<th class="other_activities">' . translateProjectText($EVC, "Other Activities") . '</th>
					<th class="unregister_activities">' . translateProjectText($EVC, "Unregister Activities") . '</th>
				</tr>';
	
		$project_html .= '<td colspan="2"></td>
		</tr>';
		
		if ($is_predefined_file_path) {
			$items = $is_page_type ? $pages : $modules;
			$selected_project = null;
			$selected_project_name = $EVC->getPresentationLayer()->getSelectedPresentationId();
			
			foreach ($items as $project => $project_items) {
				foreach ($project_items as $file_code => $file_props) {
					$file_path = $file_props[0];
					$file_activities = $file_props[1];
					$is_file_hidden = false;
					
					if ($is_page_type && $project != $selected_project_name)
						$is_file_hidden = true; //hides sub files for the projets that are not the selected_project_name
					
					if ($project && $project != $selected_project) {
						$project_html_aux = str_replace("#title#", translateProjectText($EVC, "Files for the project") . ": '$project':", $project_html);
						$project_html_aux = str_replace("#toggle_icon_class#", $is_file_hidden ? "maximize" : "minimize", $project_html_aux);
						
						$html .= $project_html_aux;
						$selected_project = $project;
					}
					
					$html .= self::getObjectHtml($EVC, $object_type_id, $file_code, $file_path, $activities, $object_types_activities, $is_module_type, $file_activities, $is_file_hidden);
					unset($object_types_activities[$object_type_id][$file_code]);
				}
			}
		}
		
		$objects_activities = $object_types_activities[$object_type_id];
		if ($objects_activities) {
			$html .= $is_predefined_file_path ? str_replace("#title#", translateProjectText($EVC, "Independent Files based in Object Id") . ":", $project_html) : '';
			
			foreach ($objects_activities as $object_id => $object_activities) {
				$html .= self::getObjectHtml($EVC, $object_type_id, $object_id, $object_id, $activities, $object_types_activities, $is_module_type, null, true);
				unset($object_types_activities[$object_type_id][$object_id]);
			}
		}
			
		unset($object_types_activities[$object_type_id]);
		
		$html .= '</table>
		</li>';
		
		return $html;
	}
	
	private static function getObjectHtml($EVC, $object_type_id, $object_id, $object_name, $activities, &$object_types_activities, $is_file, $object_available_activities = null, $is_hidden = false) {
		$object_available_activities = is_array($object_available_activities) ? $object_available_activities : array();
		
		$html = '
		<tr' . ($is_hidden ? ' style="display:none;"' : '') . '>
			<td class="object_id">' . $object_name . '</td>';

		foreach($activities as $activity) {
			$activity_id = $activity["activity_id"];
			$activity_name = $activity["name"];
			
			$checked = $object_types_activities[$object_type_id][$object_id][$activity_id];
			$warning = $is_file && !$object_available_activities[$activity_name] && !$object_available_activities[$activity_id];
			
			$html .= '
			<td class="activity activity_' . str_replace(array(" ", "-"), "_", strtolower($activity_name)) . ' activity_' . $activity_id . ($warning ? ' activity_warning' : '') . '" ' . ($warning ? 'title="' . translateProjectText($EVC, "Apparently this activity does NOT exists in this file!") . '"' : '') . '>
				<input type="checkbox" name="user_type_activities[' . $object_type_id . '][' . $object_id . '][' . $activity_id . ']" value="1" ' . ($checked ? 'checked' : '') . ' />
			</td>';
			
			unset($object_available_activities[$activity_id]);
			unset($object_available_activities[$activity_name]);
			unset($object_types_activities[$object_type_id][$object_id][$activity_id]);
		}

		$html .= '<td class="other_activities">';
		
		$other_activities = $object_types_activities[$object_type_id][$object_id];
		if ($other_activities) {
			foreach($other_activities as $activity_id) {
				$html .= '
				<div>
					<label>' . translateProjectText($EVC, $activity_id) . ': </label>
					<input type="checkbox" name="user_type_activities[' . $object_type_id . '][' . $object_id . '][' . $activity_id . ']" value="1" checked />
				</div>';
				
				unset($object_types_activities[$object_type_id][$object_id][$activity_id]);
			}
		}
		
		unset($object_types_activities[$object_type_id][$object_id]);

		$html .= '</td>
			<td class="unregister_activities">';
		
		foreach($object_available_activities as $activity_name => $aux) {
			$html .= '<div>' . translateProjectText($EVC, $activity_name) . '</div>';
		}
		
		$html .= '</td>
		</tr>';
		
		return $html;
	}
}
?>
