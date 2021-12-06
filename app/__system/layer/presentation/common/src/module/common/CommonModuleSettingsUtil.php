<?php
class CommonModuleSettingsUtil {
	
	public static function getAllObjectTypes($EVC) {
		include $EVC->getConfigPath("config");
	
		$common_project_name = $EVC->getCommonProjectName();
		include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

		if ($PEVC) {
			include $EVC->getModulePath("object/ObjectUtil", $common_project_name);
	
			$data = ObjectUtil::getAllObjectTypes($brokers, true);
		}

		include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
		
		return $data;
	}
	
	public static function getTemplatesAction($EVC, $data) {
		if ($data["action"] == "available_templates") {
			$data = CommonModuleSettingsUtil::getInstalledTemplates($EVC, $data["module"]);
			return $data ? json_encode($data) : null;
		}
		else if ($data["action"] == "template_ptl")
			return CommonModuleSettingsUtil::getTemplatePTLCode($EVC, $data["module"], $data["template"]);
	}
	
	public static function getInstalledTemplates($EVC, $module) {
		if ($module) {
			include $EVC->getConfigPath("config");

			$common_project_name = $EVC->getCommonProjectName();
			include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

			if ($PEVC) {
				include $EVC->getModulePath("object/ObjectUtil", $common_project_name);

				$templates_path = $PEVC->getTemplatesPath();
				$files = $templates_path && file_exists($templates_path) ? array_diff(scandir($templates_path), array('.','..')) : array();
				$templates = array();
				$module = preg_replace("/\/+$/", "", $module);
				
				foreach ($files as $file) 
					if (is_dir("$templates_path$file")) {
						$fp = "$templates_path$file/module/$module.ptl";
						
						if (file_exists($fp))
							$templates[] = $file;
					}
			}

			include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
		}
		
		return $templates;
	}
	
	public static function getTemplatePTLCode($EVC, $module, $template) {
		if ($module && $template) {
			include $EVC->getConfigPath("config");

			$common_project_name = $EVC->getCommonProjectName();
			include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

			if ($PEVC) {
				include $EVC->getModulePath("object/ObjectUtil", $common_project_name);
				
				$module = preg_replace("/\/+$/", "", $module);
				$fp = $PEVC->getTemplatesPath() . "$template/module/$module.ptl";
				$code = file_exists($fp) ? file_get_contents($fp) : "";
			}

			include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
		}
		
		return $code;
	}
}
?>
