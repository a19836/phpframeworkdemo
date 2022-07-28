<?php
class SearchSortHandler {

	public static function getJavascript($data, $type) {
        $type_lower = strtolower($type);
        $type = ucfirst($type_lower);

		return '<script>
			if (typeof add' . $type . 'Field != "function") {
				function add' . $type . 'Field(div_id) {
					var fields_div = document.getElementById(div_id);
				
					if (fields_div) {
						var field_div = document.createElement("div");
						field_div.className = "' . $type_lower . '_field";
						field_div.innerHTML = ' . $type_lower . '_field_html;
					
						fields_div.appendChild(field_div);
					}
				}
				
				function remove' . $type . 'Field(removeButton) {
					if (removeButton) {
						var field_div = removeButton.parentNode;
						
						if (field_div) {
							var fields_div = field_div.parentNode;
							
							if (fields_div) {
								fields_div.removeChild(field_div);
							}
						}
					}
				}
			}
		</script>';
	}

	//http://localhost/phpframework/trunk/admin/protocol/list_stores?search=Search&SEARCHING[FIELDS][]=store_id&SEARCHING[VALUES][]=17&SEARCHING[TYPES][]=contains
	public static function getPageUrl($query_string) {
		$url = urldecode($_SERVER["HTTP_REFERER"]);
		
		if (empty($url)) {
			$url = "?" . $_SERVER["QUERY_STRING"];
		}
		
		$url = self::cleanVariablesFromUrl($url);
		
		if (str_pos($url, "?")) {
			$url .= "?";
		}
		
		return $url . "&" . $query_string;
	}
	
	public static function cleanVariablesTypeFromUrl($url, $type) {
		$type = strtolower($type);
		$url = urldecode($url);
		
		if (strpos($url, "&" . $type . "ing[") !== false) {
			$url = preg_replace("/(&|\?)" . $type . "=([^&]*)/i", "$1", $url);
			$url = preg_replace("/(&|\?)" . $type . "ing\[([A-Z]*)\]\[\]=([^&]*)/i", "$1", $url);
			$url = str_replace("&&", "&", $url);
		}
		
		return $url;
	}
	
	public static function getHiddenFieldsHtml($url) {
		$data = parse_url($url);
		$query_string = urldecode($data["query"]);
		
		$parts = explode("&", $query_string);

        	$query_fields = array();
        	$t = count($parts);
		for ($i = 0; $i < $t; $i++) {
			$part = explode("=", $parts[$i]);

            if (!empty($part[0])) {
                $query_fields[ $part[0] ] = $part[1];
            }
        }
        //print_r($query_fields);

        $html = "";
        foreach ($query_fields as $name => $value) {
            $html .= '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
		}
        return $html;
	}
}
?>
