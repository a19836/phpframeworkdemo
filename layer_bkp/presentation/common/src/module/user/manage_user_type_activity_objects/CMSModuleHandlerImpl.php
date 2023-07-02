<?php
namespace CMSModule\user\manage_user_type_activity_objects;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("user/ManageUserTypeActivityObjectsUtil", $common_project_name);
		
		//Including Stylesheet
		$html = '';
		if (empty($settings["style_type"])) {
			$html .= '<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/user/manage_user_type_activity_objects.css" type="text/css" charset="utf-8" />';
		}
		
		$html .= '<script type="text/javascript" src="' . $project_common_url_prefix . 'module/user/manage_user_type_activity_objects.js"></script>';
		$html .= $settings["css"] ? '<style>' . $settings["css"] . '</style>' : '';
		$html .= $settings["js"] ? '<script type="text/javascript">' . $settings["js"] . '</script>' : '';
		
		//Execute Action and Get Html
		$html .= \ManageUserTypeActivityObjectsUtil::getHtml($EVC, $settings);
		
		return $html;
	}
}
?>
