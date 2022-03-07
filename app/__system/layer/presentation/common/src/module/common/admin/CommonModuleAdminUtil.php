<?php
include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleEnableHandler");
include_once get_lib("org.phpframework.phpscript.PHPCodePrintingHandler");
include_once get_lib("org.phpframework.util.web.html.SimpleHtmlFormHandler");
include_once get_lib("org.phpframework.util.MyArray");

class CommonModuleAdminUtil {
	private $EVC;
	private $bean_name;
	private $bean_file_name;
	private $path;
	
	private $group_module_id;
	private $project_url_prefix;
	private $project_common_url_prefix;
	
	private $head;
	private $menu_settings;
	private $content;
	
	private $object_types;
	private $all_users_count;
	private $all_users;
	
	public function __construct($EVC, $bean_name, $bean_file_name, $path, $module_path) {
		$this->EVC = $EVC;
		$this->bean_name = $bean_name;
		$this->bean_file_name = $bean_file_name;
		$this->path = $path;
		
		$this->initGroupModuleId($EVC, $module_path);
		$this->initUrlPrefixes($EVC);
	}
	
	/* INIT FUNCTIONS */
	private function initGroupModuleId($EVC, $module_path) {
		$modules_folder_path = $EVC->getCMSLayer()->getCMSModuleLayer()->getModulesFolderPath();
		$group_module_id = str_replace($modules_folder_path, "", $module_path);
		$pos = strpos($group_module_id, "/");
		$this->group_module_id = $pos > 0 ? substr($group_module_id, 0, $pos) : $group_module_id;
	}
	
	private function initUrlPrefixes($EVC) {
		include $EVC->getConfigPath("config");
		
		$this->project_url_prefix = $project_url_prefix;
		$this->project_common_url_prefix = $project_common_url_prefix;
	}
	
	/* SETTINGS FUNCTIONS */
	public function getModuleSettingsFilePath($PEVC, $file_code) {
		return $PEVC->getModulePath($file_code, $PEVC->getCommonProjectName());
	}
	
	public function getModuleSettings($PEVC, $file_code) {
		$vars = array();
		
		$settings_file_path = $this->getModuleSettingsFilePath($PEVC, $file_code);
		$file_code_class = PHPCodePrintingHandler::getClassOfFile($file_code);
		
		if ($file_code_class) {
			$settings_class_name = PHPCodePrintingHandler::prepareClassNameWithNameSpace($file_code_class["name"], $file_code_class["namespace"]);
			
			$properties = PHPCodePrintingHandler::getClassPropertiesFromFile($settings_file_path, $settings_class_name);
			
			foreach ($properties as $property) 
				$vars[ $property["name"] ] = $property["value"];
		}
		
		//echo "<pre>";print_r($properties);print_r($vars);echo "<textarea>".file_get_contents($settings_file_path)."</textarea>";die();
		return $vars;
	}
	
	public function setModuleSettings($PEVC, $file_code, $vars) {
		$settings_file_path = $this->getModuleSettingsFilePath($PEVC, $file_code);
		$file_code_class = PHPCodePrintingHandler::getClassOfFile($file_code);
		
		if ($file_code_class) {
			$settings_class_name = PHPCodePrintingHandler::prepareClassNameWithNameSpace($file_code_class["name"], $file_code_class["namespace"]);
		
			$properties = PHPCodePrintingHandler::getClassPropertiesFromFile($settings_file_path, $settings_class_name);
			
			if (is_array($vars)) {
				$code = "";
				
				foreach ($properties as $prop) {
					$name = trim($prop["name"]);
					$type = $prop["type"] ? $prop["type"] : "public";
					$type = $prop["const"] ? "const" : $type;
					$static = $prop["static"] ? " static" : "";
					
					if (isset($vars[$name])) {
						$value = $vars[$name];
						$value = is_numeric($value) ? $value : '"' . addcslashes($value, '"') . '"';//Please do not add the addcslashes($value, '\\"') otherwise it will create an extra \\. The correct is without the \\, because you are editing php code directly.
						unset($vars[$name]);
					}
					else {
						$var_type = $prop["var_type"] ? $prop["var_type"] : "string";
						$value = $var_type == "string" ? '"' . addcslashes($prop["value"], '"') . '"' : $prop["value"];//Please do not add the addcslashes($prop["value"], '\\"') otherwise it will create an extra \\. The correct is without the \\, because you are editing php code directly.
					}
					
					$name = $type == "const" ? $name : "\$$name";
					$code .= "$type$static $name = $value;\n";
				}
			
				foreach ($vars as $name => $value) {
					if ($name) {
						$code .= "const $name = " . (is_numeric($value) ? $value : '"' . addcslashes($value, '"') . '"') . ";\n";//Please do not add the addcslashes($value, '\\"') otherwise it will create an extra \\. The correct is without the \\, because you are editing php code directly.
					}
				}
			
				return PHPCodePrintingHandler::removeClassPropertiesFromFile($settings_file_path, $settings_class_name) && (!$code || PHPCodePrintingHandler::addClassPropertiesToFile($settings_file_path, $settings_class_name, $code));
			}
		}
	}
	
	/* GET FUNCTIONS */
	public function getAdminFilePath($file_code) {
		return $this->EVC->getModulePath($this->group_module_id . "/admin/" . $file_code, $this->EVC->getCommonProjectName());
	}
	
	public function getAdminFileUrl($file_code) {
		return $this->project_url_prefix . "module/" . $this->group_module_id . "/admin/" . $file_code . "?bean_name=" . $this->bean_name . "&bean_file_name=" . $this->bean_file_name . "&path=" . $this->path . "&";
	}
	
	public function getWebrootAdminFolderUrl() {
		return $this->project_common_url_prefix . "module/" . $this->group_module_id . "/admin/";
	}
	
	public function getProjectUrlPrefix() {
		return $this->project_url_prefix;
	}
	
	public function getProjectCommonUrlPrefix() {
		return $this->project_common_url_prefix;
	}
	
	public function getListContent($settings) {
		$settings["class"] = "module_list " . $settings["class"];
		$settings["title_class"] = "main_title";
		$settings["empty_list_message"] = "There are no available items...";
		
		return SimpleHtmlFormHandler::getListHtml($settings);
	}
	
	public function getFormContent($settings) {
		$settings["class"] = "module_edit " . $settings["class"];
		$settings["title_class"] = "main_title";
		$settings["status_message_class"] = "module_status_message";
		$settings["error_message_class"] = "module_error_message";
		
		return SimpleHtmlFormHandler::getFormHtml($settings);
	}
	
	public function getHomePageContent() {
		return '
		<div class="homepage">
			<h1>"' . ucwords($this->group_module_id) . '" Module Admin Panel</h1>
		</div>';
	}
	
	/* SET FUNCTIONS */
	public function setHead($head) {
		$this->head = $head;
	}
	
	public function setMenuSettings($menu_settings) {
		$this->menu_settings = $menu_settings;
	}
	
	public function setContent($content) {
		$this->content = $content;
	}
	
	/* PRINT FUNCTIONS */
	public function printTemplate() {
		$EVC = $this->EVC;
		
		$project_url_prefix = $this->project_url_prefix;
		$project_common_url_prefix = $this->project_common_url_prefix;
		
		$head = $this->head;
		
		$main_content = '
		<div class="menu">
			' . $this->getMenuHtml() . '
		</div>
		<div class="content">
			' . $this->content . '
		</div>';
		
		include $EVC->getModulePath("common/admin/template", $EVC->getCommonProjectName());
	}
	
	/* OPTIONS AND AVAILABLE ITEMS FUNCTIONS */
	public function initObjectTypes($brokers) {
		if (!isset($this->object_types)) {
			$EVC = $this->EVC;
			include_once $EVC->getModulePath("object/ObjectUtil", $EVC->getCommonProjectName());
			
			$this->object_types = ObjectUtil::getAllObjectTypes($brokers, true);
			$this->object_types = $this->object_types ? $this->object_types : array();
		}
	}
	
	public function getAvailableObjectTypes($brokers) {
		$this->initObjectTypes($brokers);
		
		$available_object_types = array();
		foreach ($this->object_types as $object_type)
			$available_object_types[ $object_type["object_type_id"] ] = $object_type["name"];
		
		return $available_object_types;
	}
	
	public function getObjectTypeOptions($brokers, $data) {
		$this->initObjectTypes($brokers);
		
		$object_type_options = array( array("value" => "", "label" => "") ); //ad default empty option
		$default_id = $data ? $data["object_type_id"] : null;
		$exists = false;
		
		foreach ($this->object_types as $object_type) {
			$object_type_options[] = array("value" => $object_type["object_type_id"], "label" => $object_type["name"]);
			
			if ($default_id && $object_type["object_type_id"] == $default_id)
				$exists = true;
		}
		
		if ($default_id && !$exists)
			$object_type_options[] = array("value" => $default_id, "label" => $default_id);
		
		return $object_type_options;
	}
	
	public function initUsers($brokers) {
		if (!isset($this->all_users_count)) {
			$EVC = $this->EVC;
			include_once $EVC->getModulePath("user/UserUtil", $EVC->getCommonProjectName());
			
			$this->all_users_count = UserUtil::countAllUsers($brokers, true);
			
			if ($this->all_users_count < UserUtil::getConstantVariable("MAXIMUM_USERS_RECORDS_IN_COMBO_BOX")) {
				$this->all_users = UserUtil::getAllUsers($brokers, false, true);
				$this->all_users = $this->all_users ? $this->all_users : array();
			}
		}
	}
	
	public function getAvailableUsers($brokers) {
		$this->initUsers($brokers);
		
		$available_users = array();
		foreach ($this->all_users as $user)
			$available_users[ $user["user_id"] ] = $user["name"] . " - " . $user["username"];
		
		return $available_users;
	}
	
	public function getUserOptions($brokers, $data, &$users_limit_exceeded) {
		$this->initUsers($brokers);
		
		$user_options = array( array("value" => "", "label" => "") ); //ad default empty option
		$users_limit_exceeded = false;
		
		if ($this->all_users_count < UserUtil::getConstantVariable("MAXIMUM_USERS_RECORDS_IN_COMBO_BOX")) {
			$exists = false;
			$default_id = $data ? $data["user_id"] : null;
			
			foreach ($this->all_users as $user) {
				$user_options[] = array("value" => $user["user_id"], "label" => $user["name"] . " - " . $user["username"]);
				
				if ($default_id && $user["user_id"] == $default_id)
					$exists = true;
			}
			
			if ($default_id && !$exists)
				$user_options[] = array("value" => $default_id, "label" => $default_id);
		}
		else
			$users_limit_exceeded = true;
		
		return $user_options;
	}
	
	public function getSelectedUsers($brokers, $data) {
		$selected_users = array();
		
		if ($data) {
			$user_ids = array();
			foreach ($data as $item)
				if ($item["user_id"])
					$user_ids[] = $item["user_id"];
			
			if ($user_ids) {
				if (isset($this->all_users_count)) {
					foreach ($this->all_users as $user)
						if (in_array($user["user_id"], $user_ids))
							$selected_users[ $user["user_id"] ] = $user["name"] . " - " . $user["username"];
				}
				else {
					$EVC = $this->EVC;
					include_once $EVC->getModulePath("user/UserUtil", $EVC->getCommonProjectName());
					
					$users = UserUtil::getUsersByConditions($brokers, array("user_id" => array("value" => $user_ids, "operator" => "in")), null, false, true);
				
					foreach ($users as $user) 
						$selected_users[ $user["user_id"] ] = $user["name"] . " - " . $user["username"];
				}
			}
		}
		
		return $selected_users;
	}
	
	/* PRIVATE FUNCTIONS */
	private function getMenuHtml() {
		$menu_settings = $this->menu_settings;
		$modules_menu_settings = $this->getModulesMenuSettings();
		
		if ($modules_menu_settings) {
			if ($menu_settings) {
				$menu_settings["menus"] = $menu_settings["menus"] ? array_merge($menu_settings["menus"], $modules_menu_settings) : $modules_menu_settings;
			}
			else {
				$menu_settings = array(
					"menus" => $modules_menu_settings
				);
			}
		}
		
		return '<ul class="dropdown ' . $menu_settings["class"] . '">' . self::getMenusHTML($menu_settings["menus"]) . '</ul>';
	}
	
	private static function getMenusHTML($menus) {
		$html = '';
		
		if ($menus) {
			$t = count($menus);
			for ($i = 0; $i < $t; $i++) {
				$menu = $menus[$i];
				
				$title = $menu["title"] ? 'title="' . $menu["title"] . '"' : "";
				
				$html .= '<li class="module_menu_item_class ' . $menu["class"] . '" ' . $title . '><a href="' . ($menu["url"] ? $menu["url"] : '#') . '"><label>' . $menu["label"] . '</label></a>';
				
				if ($menu["menus"])
					$html .= '<ul>' . self::getMenusHTML($menu["menus"]) . '</ul>';
				
				$html .= '</li>';
			}
		}
		
		return $html;
	}
	
	private function getModulesMenuSettings() {
		$CMSModuleLayer = $this->EVC->getCMSLayer()->getCMSModuleLayer();
		$CMSModuleLayer->loadModules($this->project_common_url_prefix . "module/");
		$loaded_modules = $CMSModuleLayer->getLoadedModules();
		
		$admin_menu_items = array();
		
		if (is_array($loaded_modules)) {
			$items = array();
			$repeated = array();
			
			$loaded_modules = MyArray::multisort($loaded_modules, array(array("key" => "group_id")));
			
			foreach ($loaded_modules as $module_id => $loaded_module) {
				if ($loaded_module["admin_path"]) {
					$group_id = $loaded_module["group_id"];
					
					if (!$repeated[$group_id] && $group_id != $this->group_module_id) {
						$repeated[$group_id] = true;
						
						$items[] = array(
							"label" => ucwords($group_id),
							"url" => $this->project_url_prefix . "module/$group_id/admin/index?bean_name=" . $this->bean_name . "&bean_file_name=" . $this->bean_file_name . "&path=" . $this->path . "&",
							"title" => "Go to the '" . ucwords($group_id) . "' Module Admin Panel",
						);
					}
				}
			}
			
			if ($items) {
				$admin_menu_items[] = array(
					"label" => "Other Modules",
					"class" => "other_modules",
					"menus" => $items
				);
			}
		}
		
		return $admin_menu_items;
	}
}
?>
