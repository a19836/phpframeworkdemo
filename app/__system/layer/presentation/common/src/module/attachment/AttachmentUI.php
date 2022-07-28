<?php
class AttachmentUI {
	
	public static function getEditObjectAttachmentsHtml($EVC, $settings, $object_type_id, $object_id, $group = null) {
		$common_project_name = $EVC->getCommonProjectName();
	
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("attachment/AttachmentUtil", $common_project_name);
		include_once $EVC->getModulePath("translator/include_text_translator_handler", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$delete_label = translateProjectText($EVC, "Delete");
		$move_label = translateProjectText($EVC, "Move");
		$move_up_label = translateProjectText($EVC, "Move Up");
		$move_down_label = translateProjectText($EVC, "Move Down");
		
		$options = array("sort" => array(
			array("column" => "`order`", "order" => "asc")
		));
		$attachments = $object_type_id && $object_id ? AttachmentUtil::getAttachmentsByObjectGroup($brokers, $object_type_id, $object_id, $group, $options) : null;
	
		$new_object_attachment_html = '<tr class="new_attachment">
			<td class="name">
				<input type="hidden" name="attachments[][file]" value="1" />
				<input type="file" name="attachment_files[]" />
			</td>
			<td class="size">--</td>
			<td class="icons">
				<span class="glyphicon glyphicon-remove icon delete" onClick="$(this).parent().parent().remove()" title="' . $delete_label . '">' . $delete_label . '</span>
				<span class="glyphicon glyphicon-move icon move" title="' . $move_label . '">' . $move_label . '</span>
				<!--span class="glyphicon glyphicon-arrow-up icon up" onClick="moveUp(this)" title="' . $move_up_label . '">' . $move_up_label . '</span>
				<span class="glyphicon glyphicon-arrow-down icon down" onClick="moveDown(this)" title="' . $move_down_label . '">' . $move_down_label . '</span-->
			</td>
		</tr>';
	
		if (empty($settings["style_type"]))
			$html = '<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/attachment/edit_object_attachments.css" type="text/css" charset="utf-8" />';
		
		$html = '<script type="text/javascript" src="' . $project_common_url_prefix . 'module/attachment/edit_object_attachments.js"></script>
		<script>
		var new_object_attachment_html = \'' . addcslashes(str_replace("\n", "", $new_object_attachment_html), "\\'") . '\';
		</script>
	
		<div class="module_edit_object_attachments ' . ($settings["class"] ? $settings["class"] : "") . '">
			<div class="title">' . translateProjectLabel($EVC, $settings["title"] ? $settings["title"] : "Attachments: ") . '</div>
			<div class="attachments">
				<table class="table table-condensed table-hover">
					<thead>
						<tr>
							<th class="name">' . translateProjectText($EVC, "Name") . '</th>
							<th class="size">' . translateProjectText($EVC, "Size") . '</th>
							<th class="icons">
								<span class="glyphicon glyphicon-plus icon add" onClick="addAttachment(this)" title="' . translateProjectText($EVC, "Add") . '">' . translateProjectText($EVC, "Add") . '</span>
							</th>
						</tr>
					</thead>
					<tbody>';
	
		if ($attachments)
			foreach ($attachments as $attachment) {
				$html .= '
						<tr>
							<td class="name">
								<input type="hidden" name="attachment_ids[]" value="' . $attachment["attachment_id"] . '" />
								<input type="text" name="attachments[][name]" value="' . $attachment["name"] . '" data-allow-null="0" data-validation-message="Attachment name cannot be empty" />
								<input type="hidden" name="attachment_names[]" value="' . $attachment["name"] . '" />
							</td>
							<td class="size">' . self::getSize($attachment["size"]) . '</td>
							<td class="icons">
								<span class="glyphicon glyphicon-remove icon delete" onClick="removeAttachment(this)" title="' . $delete_label . '">' . $delete_label . '</span>
								<span class="glyphicon glyphicon-move icon move" title="' . $move_label . '">' . $move_label . '</span>
								<!--span class="glyphicon glyphicon-arrow-up icon up" onClick="moveUp(this)" title="' . $move_up_label . '">' . $move_up_label . '</span>
								<span class="glyphicon glyphicon-arrow-down icon down" onClick="moveDown(this)" title="' . $move_down_label . '">' . $move_down_label . '</span-->
							</td>
						</tr>';
			}
		
		$html .= '
						<tr class="empty_object_attachments"' . ($attachments ? 'style="display:none;"' : '') . '><td colspan="3">' . translateProjectText($EVC, "There are no attachments...") . '</td></tr>
					</tbody>
				</table>
			</div>
		</div>
		<script>
		initAttachmentsMoveIcons(".module_object_attachments' . (trim($settings["class"]) ? "." . str_replace(" ", ".", preg_replace("/\s+/", " ", trim($settings["class"]))) : "") . '");
		</script>';
	
		return $html;
	}
	
	public static function getObjectAttachmentsHtml($EVC, $settings, $object_type_id, $object_id, $group = null) {
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("attachment/AttachmentUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$options = array("sort" => array(
			array("column" => "`order`", "order" => "asc")
		));
		$attachments = $object_type_id && $object_id ? AttachmentUtil::getAttachmentsByObjectGroup($brokers, $object_type_id, $object_id, $group, $options) : null;
		
		if ($attachments) {
			$html = '';
			
			if (empty($settings["style_type"])) {
				$html .= '<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/attachment/object_attachments.css" type="text/css" charset="utf-8" />';
			}
			
			$html .= '
			<div class="module_object_attachments ' . ($settings["class"] ? $settings["class"] : "") . '">
				<div class="title">' . translateProjectLabel($EVC, $settings["title"] ? $settings["title"] : "Attachments: ") . '</div>
				<div class="attachments">
				<ul>';
			
			$url = AttachmentUtil::getAttachmentsFolderUrl($EVC);
			
			foreach ($attachments as $attachment) {
				$class = $attachment["type"];
				if ($class) {
					$class = explode('/', str_replace(array("-", " "), "_", $class));
					$class = "attachment_type_" . $class[0] . " " . "attachment_type_" . $class[0] . "_" . $class[1];
				}
				
				$html .= '
				<li>
					<span class="attachment_type ' . ($class ? $class : "attachment_type_file") . '"></span>
					<span class="attachment_name"><a href="' . $url . $attachment["path"] . '" target="attachment">' . $attachment["name"] . '</a></span>
					<span class="attachment_size">' . ($attachment["size"] ? " (" . self::getSize($attachment["size"]) . ")" : "") . '</span>
				</li>';
			}
		
			$html .= '</ul>
				</div>
			</div>';
		}
		
		return $html;
	}
	
	public static function getSize($size, $unit = "") {
		if ($size) {
			$size = (int)$size;
			
			if( (!$unit && $size >= 1<<30) || $unit == "GB")
				return number_format($size/(1<<30),2)."GB";
			if( (!$unit && $size >= 1<<20) || $unit == "MB")
				return number_format($size/(1<<20),2)."MB";
			if( (!$unit && $size >= 1<<10) || $unit == "KB")
				return number_format($size/(1<<10),2)."KB";
		
			return number_format($size)." bytes";
		}
	}
}
?>
