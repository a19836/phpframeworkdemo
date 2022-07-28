<?php
include $EVC->getModulePath("quiz/QuizUtil", $EVC->getCommonProjectName());

class QuizAdminUtil {
	private $CommonModuleAdminUtil;
	
	public function __construct($CommonModuleAdminUtil) {
		$this->CommonModuleAdminUtil = $CommonModuleAdminUtil;
	}
	
	public function getMenuSettings() {
		return array(
			"class" => "",
			"menus" => array(
				array(
					"label" => "Questions",
					"menus" => array(
						array(
							"label" => "Questions List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_questions"),
							"title" => "View List of Questions",
							"class" => "",
						),
						array(
							"label" => "Add Question",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_question"),
							"title" => "Add new Question",
							"class" => "",
						),
					)
				),
				array(
					"label" => "Answers",
					"menus" => array(
						array(
							"label" => "Answers List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_answers"),
							"title" => "View List of Answers",
							"class" => "",
						),
						array(
							"label" => "Add Answer",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_answer"),
							"title" => "Add new Answer",
							"class" => "",
						),
					)
				),
				array(
					"label" => "User Answers",
					"menus" => array(
						array(
							"label" => "User Answers List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_user_answers"),
							"title" => "View List of User Answers",
							"class" => "",
						),
						array(
							"label" => "Add User Answer",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_user_answer"),
							"title" => "Add new User Answer",
							"class" => "",
						),
					)
				),
				array(
					"label" => "Object Questions",
					"class" => "large",
					"menus" => array(
						array(
							"label" => "Object Questions List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_object_questions"),
							"title" => "View List of Object Questions",
							"class" => "",
						),
						array(
							"label" => "Add Object Question",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_object_question"),
							"title" => "Add new Object Question",
							"class" => "",
						),
					)
				),
			)
		);
	}
}
?>
