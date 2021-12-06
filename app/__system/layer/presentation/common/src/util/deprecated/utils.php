<?php
function prepareListData($data, $id_columns, $name_columns) {
	$new_data = array();
	
	if (is_array($data)) {
		foreach ($data as $values) {
			if (is_array($id_columns)) {
				$new_key = "";
				foreach($id_columns as $id_column) {
					$new_key .= isset($values[$id_column]) ? $values[$id_column] : $id_column;
				}
			}
			else {
				$new_key = isset($values[$id_columns]) ? $values[$id_columns] : $id_columns;
			}
			
			if (!empty($new_key)) {
				if (is_array($name_columns)) {
					$new_value = "";
					foreach($name_columns as $name_column) {
						$new_value .= isset($values[$name_column]) ? $values[$name_column] : $name_column;
					}
				}
				else {
					$new_value = isset($values[$name_columns]) ? $values[$name_columns] : $name_columns;
				}
				
				$new_data[$new_key] = $new_value;
			}
		}
	}
	
	return $new_data;
}

function redirectUrl($redirect_url) {
	header("Location: $redirect_url");
	echo "<script>document.location = '$redirect_url';</script>";
}

function redirectUrlAfterAFewSeconds($redirect_url, $seconds = 0) {
	$seconds *= 1000;
	
	echo "<script>setTimeout(\"document.location = '$redirect_url';\", $seconds);</script>";
}

/*function getGoBackUrl() {
	$current_url = getProtocol() . "://" . $_SERVER["HTTP_HOST"] . str_replace("//", "/", $_SERVER["REQUEST_URI"]);
	$referer_url = str_replace("http:/", "http://", str_replace("//", "/", $_SERVER["HTTP_REFERER"]));
	$previous_url = null;
	
	if ($referer_url != $current_url) {
		$_COOKIE["go_back_url"] = $referer_url;
		
		return $referer_url;
	}
	else {
		return $_COOKIE["go_back_url"];
	}

}*/

function objectToArray($d) {
	if (is_object($d)) {
		// Gets the properties of the given object
		// with get_object_vars function
		$d = get_object_vars($d);
	}

	if (is_array($d)) {
		/*
		* Return array converted to object
		* Using __FUNCTION__ (Magic constant)
		* for recursive call
		*/
		return array_map(__FUNCTION__, $d);
	}
	else {
		// Return array
		return $d;
	}
}
?>
