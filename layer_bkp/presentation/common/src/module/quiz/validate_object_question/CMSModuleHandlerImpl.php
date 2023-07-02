<?php
namespace CMSModule\quiz\validate_object_question;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include_once $EVC->getModulePath("common/ObjectToObjectValidationHandler", $common_project_name);
		include_once $EVC->getModulePath("quiz/QuizUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		if (is_numeric($settings["group"]))
			$result = \QuizUtil::getObjectQuestionsByConditions($brokers, array(
				"question_id" => $settings["question_id"], 
				"object_type_id" => $settings["object_type_id"], 
				"object_id" => $settings["object_id"],
				"group" => $settings["group"],
			), null);
		else
			$result = \QuizUtil::getObjectQuestion($brokers, $settings["question_id"], $settings["object_type_id"], $settings["object_id"]);
		
		$status = !empty($result);
		
		return \ObjectToObjectValidationHandler::validate($EVC, $status, $settings);
	}
}
?>
