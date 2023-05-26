<?php
class ListHandler {
	
	public static function printListWithTopAndBottom($data, $settings) {
		$html = self::printListTop($settings);
		$html .= self::printList($data, $settings);
		$html .= self::printListBottom($settings);
		
		return $html;
	}
	
	public static function printListTop($settings) {
		$query_string = $_SERVER["QUERY_STRING"];
		
		$query_string = SearchHandler::cleanVariablesFromUrl($query_string);
		$query_string = SortHandler::cleanVariablesFromUrl($query_string);
		
		$search_settings = array(
			"fields" => $settings["fields"],
			"query_string" => $query_string,
			"searching" => $_GET["searching"],
		);

		$sort_settings = array(
			"fields" => $settings["fields"],
			//"query_string" => $query_string,
			"sorting" => $_GET["sorting"],
		);
	
		$html = '<form method="get">';
		$html .= '<table id="search_and_sort_table"><tr><td>';
		$html .= SearchHandler::getSearchHTML($search_settings);
		$html .= '</td><td>';
		$html .= SortHandler::getSortHTML($sort_settings);
		$html .= '</td></tr></table>';
		$html .= '</form>';
		$html .= PaginationHandler::getPaginationHTML($settings["pagination"]);
		
		return $html;
	}
	
	public static function printListBottom($settings) {
		$html = PaginationHandler::getPaginationHTML($settings["pagination"]);
		
		return $html;
	}
	
	public static function printList($data, $settings = false) {
		$html = "";
		
		if (is_array($settings["top_buttons"])) {
			$html .= "<div class=\"top_buttons\">";
			foreach ($settings["top_buttons"] as $button) {
				$html .= "<div class=\"top_button\">" . self::createButton($button, false) . "</div>";
			}
			$html .= "</div>";
		}
		
		if (!empty($data) && is_array($data)) {
			$sort_column_names = self::getSortColumnNames($data, $settings);
			
			$table_id = "table_" . rand(0, 100);
			
			$html .= "<div class=\"my_list " . (isset($settings["list_class"]) ? $settings["list_class"] : "") . "\">
			<table id=\"$table_id\" class=\"tablesorter\" cellspacing=\"0\">";
			
			$html .= "<thead>
			<tr>";
			
			if ($settings["buttons"]) {
				$html .= "<th class=\"buttons\"></th>";//BUTTONS
			}
			
			foreach ($sort_column_names as $name) {
				$is_hidden = isset($settings["fields"][$name]["class"]) && strtolower($settings["fields"][$name]["class"]) == "hidden";
				
				if (!$is_hidden) {
					$html .= "<th class=\"" . $name;
				
					if (isset($settings["fields"][$name]["class"]) && $settings["fields"][$name]["class"]) {
						$html .= " " . $settings["fields"][$name]["class"];
					}
					
					if (isset($settings["fields"][$name]["label"])) {
						$name = $settings["fields"][$name]["label"];
					}
				
					$html .="\"><label>" . $name . "</label></th>";
				}
			}
			
			$html .= "</tr>
			</thead>
			<tbody>";
			
			foreach ($data as $row) {
				$html .= "<tr " . (!empty($settings["tr_id"]) ? "id=\"" . self::prepareValue($settings["tr_id"], $row) . "\" " : "") . ($i == 0 ? " class=\"first\"" : "") . ">";
				
				$html .= self::printItem($row, $sort_column_names, $settings);
				
				$html .= "</tr>";
			}
			
			$html .= "</tbody>
				<!--tfoot>
					<tr>
						...
					</tr>
				</tfoot-->
				</table>
			</div>";
			
			$html .= '<script type="text/javascript">
				$(function(){	
					//call table scroll plugin
					//$("#' . $table_id . '").tableScroll({height:150});
					
					// call the table sorter plugin 
				    	$("#' . $table_id . '").tablesorter({}); 
				
					//call the col resizable plugin
					$("#' . $table_id . '").colResizable({
						liveDrag:true, 
						//gripInnerHtml:"<div class=\'grip\'></div>", 
						draggingClass:"dragging", 
					});
				});
			</script>';
		}
		
		return $html;
	}
	
	public static function getSortColumnNames($data, $settings) {
		$keys = array_keys($data);
		$columns = array_keys($data[ $keys[0] ]);
		
		$sort_column_names = is_array($settings["fields"]) ? array_keys($settings["fields"]) : array();
		foreach ($columns as $name) {
			if (!in_array($name, $sort_column_names)) {
				$sort_column_names[] = $name;
			}
		}
		
		return $sort_column_names;
	}
	
	public static function printItem($row, $sort_column_names, $settings) {
		$html = "";
		
		if ($settings["buttons"]) {
			$html .= "<td class=\"buttons\">";
			if (is_array($settings["buttons"])) {
				foreach ($settings["buttons"] as $button) {
					$html .= "<div>" . self::createButton($button, $row) . "</div>";
				}
			}
			$html .= "</td>";
		}
		
		foreach ($sort_column_names as $name) {
			$value = $row[$name];
			
			if (isset($settings["fields"][$name])) {
				$value = isset($settings["fields"][$name]["value"]) ? $settings["fields"][$name]["value"] : $value;
				
				$value = self::getFieldReplacement($value, $settings["fields"][$name]["options"]);
				
				$value = self::getFieldValue($row, $value);
			}
			
			if (isset($settings["links"][$name])) {
				$value = self::createLink($settings["links"][$name], $value, $row);
			}
			
			$is_hidden = isset($settings["fields"][$name]["class"]) && strtolower($settings["fields"][$name]["class"]) == "hidden";
		
			if (!$is_hidden) {
				$html .= "<td class=\"list_field " . $name;
			
				if (isset($settings["fields"][$name]["class"]) && $settings["fields"][$name]["class"]) {
					$html .= " " . $settings["fields"][$name]["class"];
				}
			
				$html .="\">" . $value . "</td>";
			}
		}
		
		return $html;
	}
	
	public static function getFieldValue($row, $value) {
		if (isset($value)) {
			return self::prepareValue($value, $row);
		}
		else {
			return $value;
		}
	}
	
	public static function getFieldReplacement($value, $options) {
		if (isset($options)) {
			$value_lower = strtolower($value);
			
			foreach ($options as $key => $replacement) {
				if (strtolower($key) == $value_lower) {
					return $replacement;
				}
			}
		}
		return $value;
	}
	
	public static function createButton($button, $row) {
		$html = "<a ";
		
		if (isset($button["id"]) && $button["id"]) {
			$html .= "id=\"" . self::prepareValue($button["id"], $row) . "\" ";
		}
		
		if (isset($button["target"]) && $button["target"]) {
			$html .= "target=\"" . $button["target"] . "\" ";
		}
		
		if (isset($button["class"]) && $button["class"]) {
			$html .= "class=\"" . $button["class"] . "\" ";
		}
		
		if (isset($button["link"]) && $button["link"]) {
			$url = self::prepareValue($button["link"], $row);
			
			$html .= "href=\"" . $url . "\" ";
		}
		
		if (isset($button["label"]) && $button["label"]) {
			$html .= "title=\"" . $button["label"] . "\" ";
		}
		
		$html .= ">";
		
		if (isset($button["label"]) && $button["label"]) {
			$html .= $button["label"];
		}
		
		$html .= "</a>";
		
		return $html;
	}
	
	private static function createLink($link, $value, $row) {
		$html = "<a ";
		
		if (isset($link["target"]) && $link["target"]) {
			$html .= "target=\"" . $link["target"] . "\" ";
		}
		
		if (isset($link["class"]) && $link["class"]) {
			$html .= "class=\"" . $link["class"] . "\" ";
		}
		
		if (isset($link["link"]) && $link["link"]) {
			$url = self::prepareValue($link["link"], $row);
			
			$html .= "href=\"" . $url . "\" ";
		}
		
		$html .= ">" . $value . "</a>";
		
		return $html;
	}
	
	public static function prepareValue($link, $row) {
		if (is_array($link)) {
			return "Array";
		}
		else if (is_object($link)) {
			return get_class($link);
		}
		
		preg_match_all("/\#([^#]*)\#/", $link, $out, PREG_PATTERN_ORDER);
			
		if (!empty($out[1])) {
			$t = count($out[1]);
			for ($i = 0; $i < $t; $i++) {
				$column_name = $out[1][$i];
				
				$value = isset($row[$column_name]) ? $row[$column_name] : "";
				
				$link = str_replace("#" . $column_name . "#", $value, $link);
			}
		}
			
		return $link;
	}
	
	public static function prepareListSettingsFields(&$data, &$settings, $allowed_attributes = false) {
		$keys = is_array($data) ? array_keys($data) : array();
		
		if (is_array($data[ $keys[0] ])) {
			if (!is_array($allowed_attributes)) {
				$allowed_attributes = array();
			}
			
			$columns = array_keys($data[ $keys[0] ]);
			$sort_column_names = $allowed_attributes;
			foreach ($columns as $name) {
				if (!in_array($name, $sort_column_names)) {
					$sort_column_names[] = $name;
				}
			}
			
			foreach ($sort_column_names as $name) {
				if (!isset($settings["fields"][$name])) {
					if (empty($allowed_attributes) || in_array($name, $allowed_attributes)) {
						$label = self::getAttributeLabel($name);
						
						$settings["fields"][$name] = array("label" => LanguageTranslationHandler::getLanguageTranslation($label));
					}
					else {
						$settings["fields"][$name] = array("class" => "hidden");
					}
				}
			}
		}
	}

	public static function getAttributeLabel($attr_name) {
		$label = str_replace("_", " ", $attr_name);
		$parts = explode(" ", $label);
		$label = "";
		foreach ($parts as $word) {
			$label .= ucfirst(strtolower($word)) . " ";
		}
		$label = trim($label);
		
		return $label;
	}
}
?>
