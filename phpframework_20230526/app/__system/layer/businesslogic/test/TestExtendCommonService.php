<?php
namespace __system\businesslogic;
 
include_once $vars["business_logic_modules_service_common_file_path"];

class TestExtendCommonService extends CommonService {
	
	public function getQuerySQL($data) {
		$options = $data["options"];
		$type = $data["type"];
		$module = $data["module"];
		$service = $data["service"];
		$parameters = $data["parameters"];
		
		return $this->getBroker($options["dal_broker"])->callQuerySQL($module, $type, $service, $parameters);
	}
}
?>
