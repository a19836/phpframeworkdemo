<?php
include_once $EVC->getModulePath("object/ObjectUtil", $EVC->getCommonProjectName());
include $EVC->getModulePath("zip/ZipUtil", $EVC->getCommonProjectName());

class ZipAdminUtil {
	private $CommonModuleAdminUtil;
	private $countries;
	
	public function __construct($CommonModuleAdminUtil) {
		$this->CommonModuleAdminUtil = $CommonModuleAdminUtil;
	}
	
	public function getMenuSettings() {
		return array(
			"class" => "",
			"menus" => array(
				array(
					"label" => "Countries",
					"menus" => array(
						array(
							"label" => "Countries List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_countries"),
							"title" => "View List of Countries",
							"class" => "",
						),
						array(
							"label" => "Add Country",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_country"),
							"title" => "Add new Country",
							"class" => "",
						),
					)
				),
				array(
					"label" => "States",
					"menus" => array(
						array(
							"label" => "States List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_states"),
							"title" => "View List of States",
							"class" => "",
						),
						array(
							"label" => "Add State",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_state"),
							"title" => "Add new State",
							"class" => "",
						),
					)
				),
				array(
					"label" => "Cities",
					"menus" => array(
						array(
							"label" => "Cities List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_cities"),
							"title" => "View List of Cities",
							"class" => "",
						),
						array(
							"label" => "Add City",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_city"),
							"title" => "Add new City",
							"class" => "",
						),
					)
				),
				array(
					"label" => "Zones",
					"menus" => array(
						array(
							"label" => "Zones List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_zones"),
							"title" => "View List of Zones",
							"class" => "",
						),
						array(
							"label" => "Add Zone",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_zone"),
							"title" => "Add new Zone",
							"class" => "",
						),
					)
				),
				array(
					"label" => "Zips",
					"menus" => array(
						array(
							"label" => "Zips List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_zips"),
							"title" => "View List of Zips",
							"class" => "",
						),
						array(
							"label" => "Add Zip",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_zip"),
							"title" => "Add new Zip",
							"class" => "",
						),
					)
				),
			)
		);
	}
	
	public function initZips($brokers) {
		$this->countries = ZipUtil::getAllCountries($brokers, false, true);
		$this->countries = $this->countries ? $this->countries : array();
	}
	
	public function getAvailableCountries() {
		$available_countries = array();
		foreach ($this->countries as $country) {
			$available_countries[ $country["country_id"] ] = $country["name"];
		}
		return $available_countries;
	}
	
	public function getCountryOptions() {
		$country_options = array();
		foreach ($this->countries as $country) {
			$country_options[] = array("value" => $country["country_id"], "label" => $country["name"]);
		}
		return $country_options;
	}
}
?>
