<?php
namespace CMSModule\workerpool;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include_once $EVC->getModulePath("workerpool/WorkerPoolHandler", $common_project_name);
		
		$WorkerPoolHandler = new \WorkerPoolHandler($EVC);
		$WorkerPoolHandler->start();
		
		return null;
	}
}
?>
