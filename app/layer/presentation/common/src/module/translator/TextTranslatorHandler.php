<?php
include_once $EVC->getModulePath("translator/TranslatorUtil", $EVC->getCommonProjectName());
	
class TextTranslatorHandler {
	private $TextTranslator;
	private $project;
	private $project_translations_files;
	
	public function __construct($EVC) {
		$this->TextTranslator = TranslatorUtil::getTextTranslatorObject($EVC);
		$this->project = $EVC->getPresentationLayer()->getSelectedPresentationId();
		$this->project_translations_files = array();
	}
	
	public function translateText($text, $category = null, $lang = null) {
		return $this->TextTranslator->translateText($text, $category, $lang);
	}
	
	public function translateCategoryText($text, $category = null, $lang = null) {
		if ($category)
			$translation = $this->TextTranslator->getTextTranslation($text, $category, $lang);
		
		if (!isset($translation))
			$translation = $this->TextTranslator->translateText($text, null, $lang);
		
		return $translation;
	}
	
	public function translateProjectLabel($text, $project = null, $lang = null) {
		if (substr(trim($text), -1) == ":") {
			$pos = strrpos($text, ":");
			return $this->translateProjectText(substr($text, 0, $pos), $project, $lang) . substr($text, $pos);
		}
		
		return $this->translateProjectText($text, $project, $lang);
	}
	
	public function translateProjectText($text, $project = null, $lang = null) {
		$project = $project ? $project : $this->project;
		
		//check project translation
		if ($project) {
			$translation = $this->TextTranslator->getTextTranslation($text, "projects/$project", $lang);
		
			//Check other project translation
			if (!isset($translation)) {
				$l = $lang ? $lang : $this->TextTranslator->getDefaultLang();
				
				if ($this->project_translations_files[$project][$l])
					foreach ($this->project_translations_files[$project][$l] as $fp) {
						$translation = $this->TextTranslator->translateTextFromFile($fp, $text);
						if (isset($translation)) 
							return $translation;
					}
			}
		}
		
		//Check common translation
		if (!isset($translation))
			$translation = $this->TextTranslator->translateText($text, null, $lang);
		
		return $translation;
	}
	
	public function addTranslationsFileToProject($file_path, $project = null, $lang = null) {
		if (file_exists($file_path))
			$this->project_translations_files[$project][$lang][] = $file_path;
	}
	
	public function addProjectTextTranslationsFile($file_path, $project = null, $lang = null) {
		return $this->TextTranslator->addTextTranslationsFile($file_path, "projects/$project", $lang);
	}

	/* Form Handler Validation Methods */
	
	public function translateProjectFormSettings(&$form_settings, $project = null, $lang = null) {
		if ($form_settings)
			$this->translateProjectFormElements($form_settings["form_containers"], $project, $lang);
	}

	private function translateProjectFormElements(&$form_elements, $project = null, $lang = null) {
		if ($form_elements)
			foreach ($form_elements as $idx => &$form_element)
				$this->translateProjectFormElement($form_element, $project, $lang);
	}

	public function translateProjectFormElement(&$form_element, $project = null, $lang = null) {
		if ($form_element) {
			if ($form_element["container"]) {
				if ($form_element["container"]["title"])
					$form_element["container"]["title"] = $this->translateProjectText($form_element["container"]["title"], $project, $lang);
		
				$this->translateProjectFormElements($form_element["container"]["elements"], $project, $lang);
			}
			else if ($form_element["field"]) {
				$this->translateProjectFormField($form_element["field"], $project, $lang);
			}
			/*else if ($form_element["pagination"]) {
				//nothing
			}*/
			else if ($form_element["table"]) {
				$this->translateProjectFormElements($form_element["table"]["elements"], $project, $lang);
			}
			else if ($form_element["tree"]) {
				$this->translateProjectFormElements($form_element["tree"]["elements"], $project, $lang);
			}
		}
	}

	private function translateProjectFormField(&$field, $project = null, $lang = null) {
		if ($field) {
			if ($field["label"]) {
				if ($field["label"]["value"])
					$field["label"]["value"] = $this->translateProjectLabel($field["label"]["value"], $project, $lang);
				
				if ($field["label"]["title"])
					$field["label"]["title"] = $this->translateProjectText($field["label"]["title"], $project, $lang);
			}
			
			if ($field["help"]) {
				if ($field["help"]["value"])
					$field["help"]["value"] = $this->translateProjectText($field["help"]["value"], $project, $lang);
			
				if ($field["help"]["title"])
					$field["help"]["title"] = $this->translateProjectText($field["help"]["title"], $project, $lang);
			}
			
			if ($field["input"]) {
				if ($field["input"]["title"])
					$field["input"]["title"] = $this->translateProjectText($field["input"]["title"], $project, $lang);
			
				if ($field["input"]["confirmation_message"])
					$field["input"]["confirmation_message"] = $this->translateProjectText($field["input"]["confirmation_message"], $project, $lang);
			
				if ($field["input"]["validation_label"])
					$field["input"]["validation_label"] = $this->translateProjectText($field["input"]["validation_label"], $project, $lang);
				
				if ($field["input"]["validation_message"])
					$field["input"]["validation_message"] = $this->translateProjectText($field["input"]["validation_message"], $project, $lang);
			
				if ($field["input"]["place_holder"])
					$field["input"]["place_holder"] = $this->translateProjectText($field["input"]["place_holder"], $project, $lang);
			
				if (in_array($field["input"]["type"], array("button", "submit", "link", "label", "h1", "h2", "h3", "h4", "h5")) && $field["input"]["value"])
					$field["input"]["value"] = $this->translateProjectText($field["input"]["value"], $project, $lang);
			
				if (in_array($field["input"]["type"], array("checkbox", "radio", "select")) && is_array($field["input"]["options"]) && count($field["input"]["options"]) <= 10) {//options should be smaller than 10 because of performance issues only...
					foreach ($field["input"]["options"] as &$option) 
						if ($option["label"])
							$option["label"] = $this->translateProjectText($option["label"], $project, $lang);
				}
				
				if (is_array($field["input"]["extra_attributes"]))
					foreach ($field["input"]["extra_attributes"] as &$attr) 
						if ($attr["value"] && in_array(strtolower($attr["name"]), array("title", "confirmation_message", "validation_label", "validation_message", "place_holder", "alt")))
							$attr["value"] = $this->translateProjectText($attr["value"], $project, $lang);
			}
		}
	}
}
?>
