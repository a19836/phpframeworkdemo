<?php
include_once $EVC->getUtilPath("SearchSortHandler", $EVC->getCommonProjectName());

class SearchHandler extends SearchSortHandler {
	
	public static function getSearchHTML($data) {
		$html = "";
		
		if (is_array($data["fields"])) {
			$html_field = self::getSearchFieldHTML($data);
			
			//$url = self::getPageUrl($data["query_string"]);
			//$hidden_fields_html = self::getHiddenFieldsHtml($url);
			$hidden_fields_html = self::getHiddenFieldsHtml("?" . $data["query_string"]);
			
			$html .= '
			<div id="search">
					' . $hidden_fields_html . '
					<div class="search_panel_header">
						<span class="search_title">' . LanguageTranslationHandler::getLanguageTranslation("Search") . '</span>
						<input class="search_button" type="submit" name="search" value="' . LanguageTranslationHandler::getLanguageTranslation("Search") . '" title="' . LanguageTranslationHandler::getLanguageTranslation("Search") . '" />
						<input class="add_search_field_button" type="button" name="add_search_field" value="' . LanguageTranslationHandler::getLanguageTranslation("Add Search Field") . '" title="' . LanguageTranslationHandler::getLanguageTranslation("Add Search Field") . '" onClick="addSearchField(\'search_fields\')" />
					</div>
					
					<div id="search_fields">';
						
			if (is_array($data["searching"]) && $data["searching"]["fields"]) {
				$search_fields = $data["searching"]["fields"];
				$search_values = $data["searching"]["values"];
				$search_types = $data["searching"]["types"];
			
				$t = count($search_fields);
				for ($i = 0; $i < $t; $i++) {
					$selected_data = array(
						"field" => $search_fields[$i],
						"value" => $search_values[$i],
						"type" => $search_types[$i],
					);
					
					$html .= '<div class="search_field">' . self::getSearchFieldHTML($data, $selected_data) . '</div>';
				}
			}
			/*else {
				$html .= '<div class="search_field">' . $html_field . '</div>';
			}*/
			
			$html .= '</div>
				<script>
					var search_field_html = \'' . str_replace("'", "\\'", str_replace(array("\t", "\n"), "", $html_field)) . '\';
				</script>
				' . self::getJavascript($data,"search" ) . '
			</div>';
		}
		
		return $html;
	}
	
	public static function getSearchFieldHTML($data, $selected_data = false) {
		$html_field = '<select class="searching_field" name="searching[fields][]">';
		
		foreach ($data["fields"] as $key => $name) {
			$active = isset($name["class"]) && $name["class"] == "hidden" ? false : true;
			
			if ($active) {
				$field_value = isset($name["name"]) && $name["name"] ? $name["name"] : $key;
				
				$html_field .= '<option value="' . $field_value . '" ' . ($field_value == $selected_data["field"] ? "selected" : "") . '>' . $name["label"] . '</option>';
			}
		}
		
		$html_field .= '</select>
			<select class="searching_type" name="searching[types][]">';
		
		$search_field_types = self::getSearchFieldTypes();
		
		foreach ($search_field_types as $key => $name) {
			$html_field .= '<option value="' . $key . '" ' . ($key == $selected_data["type"] ? "selected" : "") . '>' . $name . '</option>';
		}
		
		$html_field .= '</select>
			<input class="searching_value" type="text" name="searching[values][]" value="' . $selected_data["value"] . '" />
			<input class="remove_field_button" type="button" name="remove_field" value="Remove" onClick="removeSearchField(this)" />';
			
		return $html_field;
	}
	
	public static function getSearchFieldTypes() {
		return array(
			"contains" => LanguageTranslationHandler::getLanguageTranslation("contains"),
			"starts" => LanguageTranslationHandler::getLanguageTranslation("starts"),
			"ends" => LanguageTranslationHandler::getLanguageTranslation("ends"),
			"equals" => LanguageTranslationHandler::getLanguageTranslation("equals"),
			"bigger" => LanguageTranslationHandler::getLanguageTranslation("bigger"),
			"smaller" => LanguageTranslationHandler::getLanguageTranslation("smaller"),
		);		
	}

	public static function cleanVariablesFromUrl($url) {
		return self::cleanVariablesTypeFromUrl($url, "search");
	}
}
?>
