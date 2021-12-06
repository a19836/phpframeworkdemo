<?php
function translateText($EVC, $text, $category = null, $lang = null) {
	if ($text) {
		initTextTranslatorHandler($EVC);
		
		return $GLOBALS["TextTranslatorHandler"] ? $GLOBALS["TextTranslatorHandler"]->translateText($text, $category, $lang) : $text;
	}
	
	return $text;
}

function translateCategoryText($EVC, $text, $category = null, $lang = null) {
	if ($text) {
		initTextTranslatorHandler($EVC);
		
		return $GLOBALS["TextTranslatorHandler"] ? $GLOBALS["TextTranslatorHandler"]->translateCategoryText($text, $category, $lang) : $text;
	}
	
	return $text;
}

function translateProjectLabel($EVC, $text, $project = null, $lang = null) {
	if ($text) {
		initTextTranslatorHandler($EVC);
		
		return $GLOBALS["TextTranslatorHandler"] ? $GLOBALS["TextTranslatorHandler"]->translateProjectLabel($text, $project, $lang) : $text;
	}
	
	return $text;
}

function translateProjectText($EVC, $text, $project = null, $lang = null) {
	if ($text) {
		initTextTranslatorHandler($EVC);
		
		return $GLOBALS["TextTranslatorHandler"] ? $GLOBALS["TextTranslatorHandler"]->translateProjectText($text, $project, $lang) : $text;
	}
	
	return $text;
}

function translateProjectFormSettings($EVC, &$form_settings, $project = null, $lang = null) {
	if ($form_settings) {
		initTextTranslatorHandler($EVC);
		
		if ($GLOBALS["TextTranslatorHandler"])
			$GLOBALS["TextTranslatorHandler"]->translateProjectFormSettings($form_settings, $project, $lang);
	}
}

function translateProjectFormSettingsElement($EVC, &$form_element, $project = null, $lang = null) {
	if ($form_element) {
		initTextTranslatorHandler($EVC);
		
		if ($GLOBALS["TextTranslatorHandler"])
			$GLOBALS["TextTranslatorHandler"]->translateProjectFormElement($form_element, $project, $lang);
	}
}

function initTextTranslatorHandler($EVC) {
	if (!$GLOBALS["TextTranslatorHandler"]) {
		include_once $EVC->getModulePath("translator/TextTranslatorHandler", $EVC->getCommonProjectName());
		include_once get_lib("org.phpframework.util.web.html.HtmlFormHandler");
		
		$GLOBALS["TextTranslatorHandler"] = new \TextTranslatorHandler($EVC);
		
		HtmlFormHandler::$INVALID_VALUE_FOR_ATTRIBUTE_MESSAGE = translateProjectText($EVC, HtmlFormHandler::$INVALID_VALUE_FOR_ATTRIBUTE_MESSAGE);
		
		PaginationLayout::$PAGINATION_CURRENT_PAGE_TITLE = translateProjectText($EVC, PaginationLayout::$PAGINATION_CURRENT_PAGE_TITLE);
		PaginationLayout::$PAGINATION_GOTO_PAGE_TITLE = translateProjectText($EVC, PaginationLayout::$PAGINATION_GOTO_PAGE_TITLE);
		PaginationLayout::$PAGINATION_GOTO_PREVIOUS_PAGE_TITLE = translateProjectText($EVC, PaginationLayout::$PAGINATION_GOTO_PREVIOUS_PAGE_TITLE);
		PaginationLayout::$PAGINATION_GOTO_NEXT_PAGE_TITLE = translateProjectText($EVC, PaginationLayout::$PAGINATION_GOTO_NEXT_PAGE_TITLE);
		PaginationLayout::$PAGINATION_GO_BUTTON_TITLE = translateProjectText($EVC, PaginationLayout::$PAGINATION_GO_BUTTON_TITLE);
		
		return $GLOBALS["TextTranslatorHandler"];
	}
}

function initMyJSLibTranslations($EVC, $project = null, $lang = null) {
	$html = '
	if (MyJSLib && MyJSLib.FormHandler && MyJSLib.FormHandler.messages) {
		MyJSLib.FormHandler.messages["empty_form_object"] = "' . translateProjectText($EVC, "Empty form object detected!") . '";
		MyJSLib.FormHandler.messages["undefined_field"] = "' . translateProjectText($EVC, "Field '#label#' cannot be blank.") . '";
		MyJSLib.FormHandler.messages["invalid_field_type"] = "' . translateProjectText($EVC, "Invalid #validation_type# format in '#label#'.") . '";
		MyJSLib.FormHandler.messages["field_min_length"] = "' . translateProjectText($EVC, "Length of '#label#' cannot be less than #min_length# characters.") . '";
		MyJSLib.FormHandler.messages["field_max_length"] = "' . translateProjectText($EVC, "Length of '#label#' cannot be more than #max_length# characters.") . '";
		MyJSLib.FormHandler.messages["field_min_value"] = "' . translateProjectText($EVC, "Value of '#label#' cannot be less than #min_value#.") . '";
		MyJSLib.FormHandler.messages["field_max_value"] = "' . translateProjectText($EVC, "Value of '#label#' cannot be great than #max_value#.") . '";
		MyJSLib.FormHandler.messages["mandatory_checkbox"] = "' . translateProjectText($EVC, "Please checked the field '#label#'.") . '";
		MyJSLib.FormHandler.messages["field_min_words"] = "' . translateProjectText($EVC, "Value of '#label#' need to have more than #min_words# word(s).") . '";
		MyJSLib.FormHandler.messages["field_max_words"] = "' . translateProjectText($EVC, "Value of '#label#' need to have less than #max_words# word(s).") . '";
		MyJSLib.FormHandler.messages["confirmation"] = "' . translateProjectText($EVC, "Do you want continue?") . '";
		MyJSLib.FormHandler.messages["system_error"] = "' . translateProjectText($EVC, "System error. Please contact the system administrator!") . '";
	}';
	
	return $html;
}
?>
