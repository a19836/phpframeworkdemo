<?php
class CommonModuleTableExtraAttributesSettingsUtil {
	private $EVC;
	private $PEVC;
	private $db_driver_name;
	private $module_path;
	
	public function __construct($EVC, $PEVC, $db_driver_name, $module_path) {
		$this->EVC = $EVC;
		$this->PEVC = $PEVC;
		$this->db_driver_name = $db_driver_name;
		$this->module_path = $module_path;
	}
	
	/*
	 * $fields = array(
	 *	"article_id" => array("validation_type" => "bigint"),
	 *	"title", 
	 *	"sub_title", 
	 *	"published" => array("allow_null" => 1),
	 *	"tags",
	 *	"photo_id" => array("label" => "Photo", "allow_null" => 1),
	 *	"summary" => array("type" => "textarea"),
	 *	"content" => array("type" => "textarea"),
	 *	"allow_comments" => array("allow_null" => 1),
	 *	"article_attachments"
	 * );
	 */
	public function prepareNewFieldsSettings($main_attributes_table_alias, &$fields, $extra_settings = null, $options = null) {
		$this->prepareNewFieldsSettingsWithExtraAlias($main_attributes_table_alias . "_extra", $fields, $extra_settings, $options);
	}
	
	public function prepareNewFieldsSettingsWithExtraAlias($extra_attributes_table_alias, &$fields, $extra_settings = null, $options = null) {
		$fp = $this->getTableExtraAttributesSettingsFile($extra_attributes_table_alias);
		
		//get local file
		if (file_exists($fp)) {
			include $fp;
			
			$is_edit = $options && $options["is_edit"];
			$is_list = $options && $options["is_list"];
			
			if ($table_extra_attributes_settings)
				foreach ($table_extra_attributes_settings as $attr_name => $attr_settings) 
					if (!$fields || (!in_array($attr_name, $fields) && !array_key_exists($attr_name, $fields))) {
						if ($extra_settings)
							foreach ($extra_settings as $extra_setting_name => $extra_setting_value)
								$attr_settings[$extra_setting_name] = $extra_setting_value;
						
						if ($attr_settings["file_type"]) {
							if ($is_edit) {
								$attr_settings["type"] = "file"; //set type to file
								$attr_settings["validation_type"] = "";//very important otherwise when we upload the file it will check if is a bigint. This must be empty!
								$attr_settings["next_html"] = $this->getFileFieldSettingNextHtml($attr_name, $attr_settings, $is_list);
							}
							else {
								$an = $attr_name . "_url";
								$v = "#" . ($is_list ? '[\\$idx][' . $an . ']' : $an) . "#";
								
								if ($attr_settings["file_type"] == "image") {
									$attr_settings["type"] = "image"; //set type to image
									$attr_settings["src"] = $v;
									$attr_settings["extra_attributes"] = array(
										array("name" => "onError", "value" => '"$(this).remove();"'),
									);
								}
								else {
									$attr_settings["type"] = "link"; //set type to image
									$attr_settings["href"] = $v;
								}
							}
						}
						
						$fields[$attr_name] = $attr_settings;
					}
		}
	}
	
	private function getFileFieldSettingNextHtml($attr_name, $attr_settings, $is_list = false) {
		$an = strtolower(str_replace(" ",  "_", $attr_name));
		$func_name = "remove_" . $an . "_file_" . rand(0, 100000000);
		$file_type = $attr_settings["file_type"];
		
		$v_url = "#" . ($is_list ? '[\\$idx][' . $attr_name . "_url" . ']' : $attr_name . "_url") . "#";
		$v_name = "#" . ($is_list ? '[\\$idx][' . $attr_name . "_name" . ']' : $attr_name . "_name") . "#";
		$field_selector = $is_list ? ".list_items .list_column.$attr_name" : ".form_fields .form_field.$attr_name";
		$field_class_prefix = $is_list ? "list_column" : "form_field";
		
		$html = '<div class="' . $field_class_prefix . '_file' . ($file_type == "image" ? " {$field_class_prefix}_file_image" : "") . '">
	<a class="' . $field_class_prefix . '_file_url" href="' . $v_url . '" target="' . $attr_name . '_url">';
		
		if ($file_type == "image")
			$html .= '
		<img src="' . $v_url . '" alt="No Image" />';
		else
			$html .= $v_name;
		
		$html .= '
	</a>
	<a class="' . $field_class_prefix . '_file_remove" href="javascript:void(0)" onClick="' . $func_name . '(this)">Remove</a>
	<script>
		$("' . $field_selector . ' .' . $field_class_prefix . '_file").each(function(idx, div) {
			div = $(div);
			var ' . $field_class_prefix . ' = div.parent();
			var input = ' . $field_class_prefix . '.find("input[type=text], input[hidden]");
			var input_value = input.val();
			
			if (!input[0] || input_value == "" || input_value == 0) {
				div.remove();
				
				var input_file = ' . $field_class_prefix . '.children("input[type=file]");
				
				if (input_file[0] && input_file[0].hasAttribute("data-allow-null-bkp"))
					input_file.attr("data-allow-null", input_file.attr("data-allow-null-bkp"));
			}
		});
		
		function ' . $func_name . '(elm) {
			if (confirm("Do you wish to remove this ' . $file_type . '")) {
				elm = $(elm);
				var p = elm.parent();
				var gp = p.parent();
				
				gp.find("input[type=text], input[type=hidden]").val("");
				
				var input_file = gp.find("input[type=file]");
				
				if (input_file[0].hasAttribute("data-allow-null-bkp"))
					input_file.attr("data-allow-null", input_file.attr("data-allow-null-bkp"));
				
				p.remove();
			}
		}
	</script>
</div>';
		
		return $html;
	}
	
	private function getGroupModuleId() {
		//must be EVC bc of module_path. Do not use PEVC here.
		$modules_folder_path = $this->EVC->getCMSLayer()->getCMSModuleLayer()->getModulesFolderPath();
		$group_module_id = str_replace($modules_folder_path, "", $this->module_path);
		$pos = strpos($group_module_id, "/");
		$group_module_id = $pos > 0 ? substr($group_module_id, 0, $pos) : $group_module_id;
		
		return $group_module_id;
	}
	
	private function getTableExtraAttributesSettingsFile($extra_attributes_table_alias) {
		$group_module_id = $this->getGroupModuleId();
		
		//must be PEVC
		return $this->PEVC->getModulePath($group_module_id . "/" . ($this->db_driver_name ? $this->db_driver_name : "default") . "_" . $extra_attributes_table_alias . "_attributes_settings", $this->PEVC->getCommonProjectName());
	}
}
?>
