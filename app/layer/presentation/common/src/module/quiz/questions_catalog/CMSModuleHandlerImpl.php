<?php
namespace CMSModule\quiz\questions_catalog;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once $EVC->getModulePath("quiz/QuizUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$html = '';
		
		if (empty($settings["style_type"])) {
			$html .= '<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/quiz/questions_catalog.css" type="text/css" charset="utf-8" />';
		}
		
		$html .= '
		' . ($settings["css"] ? '<style>' . $settings["css"] . '</style>' : '') . '
		' . ($settings["js"] ? '<script type="text/javascript">' . $settings["js"] . '</script>' : '') . '

		<div class="module_questions_catalog ' . ($settings["block_class"]) . '">';
		
		$catalog_title = $settings["catalog_title"];
		if ($catalog_title)
			$html .= '<h1 class="catalog_title">' . translateProjectText($EVC, $catalog_title) . '</h1>';
		
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
		
		//Getting questions
		if ($settings["catalog_sort_column"])
			$options["sort"][] = array("column" => $settings["catalog_sort_column"], "order" => $settings["catalog_sort_order"]);
		
		$conditions = \CommonModuleUI::getConditionsFromSearchValues($settings);
		
		if ($settings["filter_by_published"])
			$conditions["published"] = 1;
		
		//Getting questions
		switch ($settings["questions_type"]) {
			case "all":
				$total = $conditions ? \QuizUtil::countQuestionsByConditions($brokers, $conditions, null) : \QuizUtil::countAllQuestions($brokers);
				$questions = $conditions ? \QuizUtil::getQuestionsByConditions($brokers, $conditions, null, $options) : \QuizUtil::getAllQuestions($brokers, $options);
				break;
			case "parent":
				$total = \QuizUtil::countQuestionsByObject($brokers, $settings["object_type_id"], $settings["object_id"], $conditions, null);
				$questions = \QuizUtil::getQuestionsByObject($brokers, $settings["object_type_id"], $settings["object_id"], $conditions, null, $options);
				break;
			case "parent_group":
				$total = \QuizUtil::countQuestionsByObjectGroup($brokers, $settings["object_type_id"], $settings["object_id"], $settings["group"], $conditions, null);
				$questions = \QuizUtil::getQuestionsByObjectGroup($brokers, $settings["object_type_id"], $settings["object_id"], $settings["group"], $conditions, null, $options);
				break;
		}
		
		//Preparing pagination
		if ($settings["top_pagination_type"] || $settings["bottom_pagination_type"]) {
			$PaginationLayout = new \PaginationLayout($total, $rows_per_page, array("current_page" => $current_page), "current_page");
			$PaginationLayout->show_x_pages_at_once = 10;
			$pagination_data = $PaginationLayout->data;
		}
		
		$catalog_type = $settings["catalog_type"];
		
		//prepare settings with selected template html if apply
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "quiz/questions_catalog", $settings);
		
		//execute user list with ptl
		if ($catalog_type == "user_list" && $settings["ptl"]) {
			$form_settings = array("ptl" => $settings["ptl"]);
			$questions_item_input_data_var_name = $form_settings["ptl"]["external_vars"]["questions_item_input_data_var_name"]; //this should contain "article" by default, but is not mandatory. This value should be the same than the following foreach-item-value-name: <ptl:foreach $input_data i article>, but only if the user doesn't change this value. If the user changes the foreach to <ptl:foreach $input_data i item>, he must change the external var "questions_item_input_data_var_name" to "item" too.
			if ($questions_item_input_data_var_name)
				$form_settings["ptl"]["input_data_var_name"] = $questions_item_input_data_var_name;
			$HtmlFormHandler = new \HtmlFormHandler($form_settings);
			
			foreach ($settings["fields"] as $field_id => $field) 
				if ($settings["show_" . $field_id])
					\CommonModuleUI::prepareBlockFieldPTLCode($EVC, $HtmlFormHandler, $settings["ptl"]["code"], $field_id, $field, $questions);
			
			if ($settings["top_pagination_type"]) {
				$pagination_data["style"] = $settings["top_pagination_type"];
				$settings["ptl"]["code"] = preg_replace('/<ptl:block:top-pagination\s*\/?>/i', $PaginationLayout->designWithStyle(1, $pagination_data), $settings["ptl"]["code"]);
			}
			
			if ($settings["bottom_pagination_type"]) {
				$pagination_data["style"] = $settings["bottom_pagination_type"];
				$settings["ptl"]["code"] = preg_replace('/<ptl:block:bottom-pagination\s*\/?>/i', $PaginationLayout->designWithStyle(1, $pagination_data), $settings["ptl"]["code"]);
			}
			
			\CommonModuleUI::cleanBlockPTLCode($settings["ptl"]["code"]);
			
			$form_settings = array(
				"ptl" => $settings["ptl"],
				"CacheHandler" => $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler")
			);
		
			$html .= \HtmlFormHandler::createHtmlForm($form_settings, $questions);
		}
		else { //execute blog and normal list or user list with no ptl
			//showing top pagination
			if ($settings["top_pagination_type"]) {
				$pagination_data["style"] = $settings["top_pagination_type"];
				
				$html .= '<div class="top_pagination pagination_alignment_' . $settings["top_pagination_alignment"] . '">' . $PaginationLayout->designWithStyle(1, $pagination_data) . '</div>';
			}
			
			//showing catalog
			$html .= '<ul class="catalog catalog_normal_list">';
			if ($questions)
				foreach ($questions as $question)
					$html .= '<li class="question">' . self::getCatalogQuestionHtml($EVC, $settings, $question) . '</li>';
			else
				$html .= '<li><h3 class="no_questions">' . translateProjectText($EVC, "There are no available questions...") . '</h3></li>';
			$html .= '</ul>';
			
			if ($settings["bottom_pagination_type"]) {
				$pagination_data["style"] = $settings["bottom_pagination_type"];
				
				$html .= '<div class="bottom_pagination pagination_alignment_' . $settings["bottom_pagination_alignment"] . '">' . $PaginationLayout->designWithStyle(1, $pagination_data) . '</div>';
			}
		}
		
		$html .= '</div>';
		
		return $html;
	}
	
	private static function getCatalogQuestionHtml($EVC, $settings, $question) {
		//Preparing fields
		$form_settings = array(
			"with_form" => 0,
			"form_containers" => array(
				0 => array(
					"container" => array(
						"elements" => array(),
						"next_html" => '<div class="catalog_question_clear"></div>',
					)
				),
			)
		);
		
		if ($settings["question_properties_url"])
			$form_settings["form_containers"][0]["container"]["href"] = $settings["question_properties_url"] . $question["question_id"];
		
		$HtmlFormHandler = null;
		if ($settings["ptl"])
			$HtmlFormHandler = new \HtmlFormHandler(array("ptl" => $settings["ptl"]));
		
		foreach ($settings["fields"] as $field_id => $field)
			if ($settings["show_" . $field_id] && $question[$field_id]) {
				//Preparing ptl
				if ($settings["ptl"])
					\CommonModuleUI::prepareBlockFieldPTLCode($EVC, $HtmlFormHandler, $settings["ptl"]["code"], $field_id, $field, $question);
				else
					$form_settings["form_containers"][0]["container"]["elements"][] = $field;
			}
		
		//add ptl to form_settings
		if ($settings["ptl"]) {
			\CommonModuleUI::cleanBlockPTLCode($settings["ptl"]["code"]);
			$form_settings["form_containers"][0]["container"]["elements"][] = array("ptl" => $settings["ptl"]);
		}
		
		translateProjectFormSettings($EVC, $form_settings);
	
		$form_settings["CacheHandler"] = $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler");
	
		return \HtmlFormHandler::createHtmlForm($form_settings, $question);
	}
}
?>
