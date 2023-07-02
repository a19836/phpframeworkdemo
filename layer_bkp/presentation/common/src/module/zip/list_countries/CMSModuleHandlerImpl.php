<?php
namespace CMSModule\zip\list_countries;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("zip/ZipUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Preparing options
		$rows_per_page = $settings["rows_per_page"] > 0 ? $settings["rows_per_page"] : null;
		$options = array("limit" => $rows_per_page, "sort" => array());
		
		//Preparing pagination
		if ($settings["top_pagination_type"] || $settings["bottom_pagination_type"]) {
			include_once get_lib("org.phpframework.util.web.html.pagination.PaginationLayout");
			
			$current_page = is_numeric($_GET["current_page"]) ? $_GET["current_page"] : 0;
			$rows_per_page = $rows_per_page > 0 ? $rows_per_page : 50;
			$options["start"] = \PaginationHandler::getStartValue($current_page, $rows_per_page);
		}
		
		//preparing conditions
		$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
		
		//Getting countries
		$settings["total"] = $conditions ? \ZipUtil::countCountriesByConditions($brokers, $conditions, null) : \ZipUtil::countAllCountries($brokers);
		$settings["data"] = $conditions ? \ZipUtil::getCountriesByConditions($brokers, $conditions, null, $options) : \ZipUtil::getAllCountries($brokers, $options);
		$settings["css_file"] = $project_common_url_prefix . 'module/zip/list_countries.css';
		$settings["class"] = "module_list_countries";
		$settings["edit_page_url"] .= (strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "country_id=#[idx][country_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/zip/list_countries/delete_country?country_id=#[idx][country_id]#";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "zip/list_countries", $settings);
		return \CommonModuleUI::getListHtml($EVC, $settings);
	}
}
?>
