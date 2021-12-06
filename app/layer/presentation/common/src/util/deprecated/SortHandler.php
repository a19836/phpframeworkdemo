<?php
include_once $EVC->getUtilPath("SearchSortHandler", $EVC->getCommonProjectName());

class SortHandler extends SearchSortHandler {
	
	public static function getSortHTML($data) {
		$html = "";
		
		if (is_array($data["fields"])) {
			$html_field = self::getSortFieldHTML($data);
			
			//$url = self::getPageUrl($data["query_string"]);
			//$hidden_fields_html = self::getHiddenFieldsHtml($url);
			$hidden_fields_html = self::getHiddenFieldsHtml("?" . $data["query_string"]);
			
			$html .= '
			<div id="sort">
					' . $hidden_fields_html . '
					<div class="sort_panel_header">
						<span class="sort_title">' . LanguageTranslationHandler::getLanguageTranslation("Sort") . '</span>
						<input class="sort_button" type="submit" name="sort" value="' . LanguageTranslationHandler::getLanguageTranslation("Sort") . '" title="' . LanguageTranslationHandler::getLanguageTranslation("Sort") . '" />
						<input class="add_sort_field_button" type="button" name="add_sort_field" value="' . LanguageTranslationHandler::getLanguageTranslation("Add Sort Field") . '" title="' . LanguageTranslationHandler::getLanguageTranslation("Add Sort Field") . '" onClick="addSortField(\'sort_fields\')" />
					</div>
					
					<div id="sort_fields">';
			
			if (is_array($data["sorting"])) {
				$sort_bys = $data["sorting"]["bys"];
				$sort_orders = $data["sorting"]["orders"];
			
				$t = $sort_bys ? count($sort_bys) : 0;
				for ($i = 0; $i < $t; $i++) {
					$selected_data = array(
						"by" => $sort_bys[$i],
						"order" => $sort_orders[$i],
					);
					
					$html .= '<div class="sort_field">' . self::getSortFieldHTML($data, $selected_data) . '</div>';
				}
			}
			/*else {
				$html .= '<div class="sort_field">' . $html_field . '</div>';
			}*/
			
			$html .= '</div>
				<script>
					var sort_field_html = \'' . str_replace("'", "\\'", str_replace(array("\t", "\n"), "", $html_field)) . '\';
				</script>
				' . self::getJavascript($data, "sort") . '
			</div>';
		}
		
		return $html;
	}
	
	public static function getSortFieldHTML($data, $selected_data = false) {
		$html_field = '<select class="sorting_by" name="sorting[bys][]">';
		
		foreach ($data["fields"] as $key => $name) {
			$active = isset($name["class"]) && $name["class"] == "hidden" ? false : true;
			
			if ($active) {
				$field_value = isset($name["name"]) && $name["name"] ? $name["name"] : $key;
				
				$html_field .= '<option value="' . $field_value . '" ' . ($field_value == $selected_data["by"] ? "selected" : "") . '>' . $name["label"] . '</option>';
			}
		}
		
		$html_field .= '</select>
		<select class="sorting_order" name="sorting[orders][]">';
		
		$sort_order_types = self::getSortFieldTypes();
		
		foreach ($sort_order_types as $key => $name) {
			$html_field .= '<option value="' . $key . '" ' . ($key == $selected_data["order"] ? "selected" : "") . '>' . $name . '</option>';
		}
		
		$html_field .= '</select>
			<input class="remove_field_button" type="button" name="remove_field" value="rm" onClick="removeSortField(this)" />';
			
		return $html_field;
	}
	
	public static function getSortFieldTypes() {
		return array(
			"asc" => LanguageTranslationHandler::getLanguageTranslation("asc"),
			"desc" => LanguageTranslationHandler::getLanguageTranslation("desc"),
		);
	}

	public static function cleanVariablesFromUrl($url) {
        	return self::cleanVariablesTypeFromUrl($url, "sort");
	}
	
	//sort=Sort&SORTING[BYS][]=store_id&SORTING[ORDERS][]=asc
	public static function getFieldSortUrl($sort_by, $sort_order, $url = false) {
		if (empty($url)) {
			$url = self::getPageUrl();
		}
	
		$query_string = "sort=sort&sorting[bys][]={$sort_by}&sorting[orders][]={$sort_order}";
		
		if (strpos($url, "?") !== false) {
			return $url . "&" . $query_string;
		}
	
		return $url . "?" . $query_string;
	}
}
?>
