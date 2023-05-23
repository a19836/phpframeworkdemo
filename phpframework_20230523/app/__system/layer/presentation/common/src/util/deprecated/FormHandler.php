<?php
class FormHandler {
	
	public static function printForm($data, $settings = false) {
		$html = "";
		
		if (!empty($data) && is_array($data)) {
			if (is_array($settings["top_buttons"])) {
				$html .= "<div class=\"top_buttons\">";
				foreach ($settings["top_buttons"] as $button) {
					$html .= "<div>" . ListHandler::createButton($button, $row) . "</div>";
				}
				$html .= "</div>";
			}
		
			$html .= "<form class=\"my_form " . (isset($settings["form_class"]) ? $settings["form_class"] : "") . "\" method=\"post\" onSubmit=\"" . (isset($settings["on_submit_form_func"]) ? $settings["on_submit_form_func"] : "") . "\">
			<div class=\"form_fields\">";
			
			$sort_column_names = array_keys($settings["fields"]);
			$data_keys = array_keys($data);
			foreach ($data_keys as $name) {
				if (!in_array($name, $sort_column_names)) {
					$sort_column_names[] = $name;
				}
			}
			
			foreach ($sort_column_names as $key) {
				$value = $data[$key];
			
				if (isset($settings["fields"][$key])) {
					$html .= self::printFormField($key, $settings["fields"][$key], $value);
				}
			}
		
			$html .= "</div>";
		
			$html .= "<div class=\"buttons\">";
			if (is_array($settings["buttons"])) {
				foreach ($settings["buttons"] as $button) {
					$html .= "<div>" . self::createButton($button, $row) . "</div>";
				}
			}
			$html .= "</div></form>";
		}
		
		return $html;
	}
	
	public static function printFormField($key, $setting, $value) {
		$type = isset($setting["type"]) ? strtolower($setting["type"]) : "";
		$label = isset($setting["label"]) ? $setting["label"] : "";
		$name = isset($setting["name"]) ? $setting["name"] : $key;
		$class = isset($setting["class"]) ? $setting["class"] : "";
		$id = isset($setting["id"]) ? $setting["id"] : false;
		
		$field_attributes = "";
		if (is_array($setting["field_attributes"])) {
			foreach ($setting["field_attributes"] as $field_name => $field_value) {
				$field_attributes .= "$field_name=\"$field_value\" ";
			}
		}
		
		switch ($type) {
			case "text":
			case "hidden":
			case "password":
				$field = "<input type=\"$type\" name=\"$name\" value=\"" . self::prepareValueForTextField($value) . "\" $field_attributes />";
				break;
			
			case "textarea":
				$field = "<textarea name=\"$name\" $field_attributes >" . $value . "</textarea>";
				break;
			
			case "radio":
				$field = "<radiogroup>";
				
				$options = isset($setting["options"]) ? $setting["options"] : array();
				foreach ($options as $option_key => $option_value) {
					$field .= "<div><input type=\"radio\" name=\"$name\" value=\"" . self::prepareValueForTextField($option_key) . "\" " . ($option_key == $value ? "checked" : "") . " $field_attributes />$option_value</div>";
				}
				$field .= "</radiogroup>";
				
				break;
			
			case "select":
				$field = "<select name=\"$name\" $field_attributes >";
				
				$options = isset($setting["options"]) ? $setting["options"] : array();
				foreach ($options as $option_key => $option_value) {
					
					$field .= "<option value=\"" . self::prepareValueForTextField($option_key) . "\" " . ($option_key == $value ? "selected" : "") . ">$option_value</option>";
				}
				$field .= "</select>";
				
				break;
		
			case "combobox":
				$field = "<select name=\"$name\" $field_attributes >";
				
				$options = isset($setting["options"]) ? $setting["options"] : array();
				$field .= self::getComboboxField($options, $value);
				
				$field .= "</select>";
				
				break;
		
			case "date":
				$random = rand();
				$date_picker_id = "date_picker_" . $random;
				
				//http://jqueryui.com/demos/datepicker/
				$field = "<input id=\"$date_picker_id\" type=\"text\" name=\"$name\" value=\"" . self::prepareValueForTextField($value) . "\" $field_attributes /> (YYYY-MM-DD => 2012-05-23)";
				
				$field .= "<script>$(\"#$date_picker_id\").datepicker({ dateFormat:\"yy-mm-dd\" });</script>";

				break;
		
			default:
				$field = $value;
		}
		
		$html = "<div id=\"$id\" class=\"form_field " . $key . " " . $class . ($type == "hidden" ? " hidden" : "") . "\">
			<label>" . $label . "</label>
			<div>
				<span class=\"input\">" . $field . "</span>
				<span class=\"extra\">" . (isset($setting["extra"]) ? $setting["extra"] : "") . "</span>
			</div>
		</div>";
		
		return $html;
	}
	
	private static function getComboboxField($options, $value) {
		$field = "";
		
		if (is_array($options)) {
			foreach ($options as $option_value) {
				if (!empty($option_value["sub_group"])) {
					$field .= "<optgroup label=\"" . $option_value["text"] . "\">";
					$field .= self::getComboboxField($option_value["sub_group"], $value);
					$field .= "</optgroup>";
				}
				else {
					$field .= "<option value=\"" . self::prepareValueForTextField($option_value["value"]) . "\" " . ($option_value["value"] == $value ? "selected" : "") . ">" . $option_value["text"] . "</option>";
				}
			}
		}
		return $field;
	}
	
	private static function createButton($button, $row) {
		$html = "<input type=\"submit\" ";
		
		if (isset($button["class"]) && $button["class"]) {
			$html .= "class=\"" . $button["class"] . "\" ";
		}
		
		if (isset($button["name"]) && $button["name"]) {
			$html .= "name=\"" . $button["name"] . "\" ";
		}
		
		if (isset($button["label"]) && $button["label"]) {
			$html .= "value=\"" . $button["label"] . "\" ";
		}
		
		$button_attributes = "";
		if (is_array($button["button_attributes"])) {
			foreach ($button["button_attributes"] as $button_name => $button_value) {
				$button_attributes .= "$button_name=\"$button_value\" ";
			}
		}
		
		$html .= " $button_attributes />";
		
		return $html;
	}
	
	public static function prepareValueForTextField($value) {
		return str_replace('"', '&quot;', $value);
	}
}
?>
