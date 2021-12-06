<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include_once get_lib("org.phpframework.util.MimeTypeHandler");
	include $EVC->getModulePath("attachment/admin/AttachmentAdminUtil", $common_project_name);
	
	$AttachmentAdminUtil = new AttachmentAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	if ($_POST) {
		$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
		
		$properties = array(
			"ATTACHMENTS_ABSOLUTE_FOLDER_PATH" => $_POST["ATTACHMENTS_ABSOLUTE_FOLDER_PATH"] ? $_POST["ATTACHMENTS_ABSOLUTE_FOLDER_PATH"] : "",
			"ATTACHMENTS_RELATIVE_FOLDER_PATH" => $_POST["ATTACHMENTS_RELATIVE_FOLDER_PATH"] ? $_POST["ATTACHMENTS_RELATIVE_FOLDER_PATH"] : "files",
			"ATTACHMENTS_URL" => $_POST["ATTACHMENTS_URL"],
			"ALLOWED_MIME_TYPES" => is_array($_POST["ALLOWED_MIME_TYPES"]) ? trim(implode(";", array_unique($_POST["ALLOWED_MIME_TYPES"]))) . ";" : "",
			"DENIED_MIME_TYPES" => is_array($_POST["DENIED_MIME_TYPES"]) ? trim(implode(";", array_unique($_POST["DENIED_MIME_TYPES"]))) . ";" : "",
			"ALLOWED_EXTENSIONS" => is_array($_POST["ALLOWED_EXTENSIONS"]) ? trim(implode(";", array_unique($_POST["ALLOWED_EXTENSIONS"]))) . ";" : "",
			"DENIED_EXTENSIONS" => is_array($_POST["DENIED_EXTENSIONS"]) ? trim(implode(";", array_unique($_POST["DENIED_EXTENSIONS"]))) . ";" : "",
		);
		
		if ($CommonModuleAdminUtil->setModuleSettings($PEVC, "attachment/AttachmentSettings", $properties)) {
			$status_message = "Settings saved successfully";
		}
		else {
			$error_message = "Error trying to save new settings. Please try again...";
		}
	}
	
	$data = $CommonModuleAdminUtil->getModuleSettings($PEVC, "attachment/AttachmentSettings");
	$project_module_path = str_replace($PEVC->getPresentationLayer()->getLayerPathSetting(), "", $PEVC->getWebrootPath($PEVC->getCommonProjectName()));
	
	$mime_types_options = array();
	$t = count(MimeTypeHandler::$types);
	for ($i = 0; $i < $t; $i++) {
		$item = MimeTypeHandler::$types[$i];
		$mime_types_options[] = array("label" => '.' . $item["extension"] . " - " . $item["mime_type"], "other_attributes" => 'extension="' . $item["extension"] . '" mime_type="' . $item["mime_type"] . '"');
	}
	
	//Preparing HTML
	$form_settings = array(
		"title" => "Edit Settings",
		"fields" => array(
			"ATTACHMENTS_ABSOLUTE_FOLDER_PATH" => array("type" => "text", "label" => "Attachments absolute folder path: ", "next_html" => '
				<div class="info">If you prefer to change the default attachments folder, please add here the correspondent absolute path (with the "/" at the beginning), otherwise leave this field in blank.<br/>
				Additionally the system will add the \'Relative Folder Path\' to your absolute path. If you don\'t want any extra folder in your absolute path, please leave the \'Relative Folder Path\' field in blank.</div>
			'),
			"ATTACHMENTS_RELATIVE_FOLDER_PATH" => array("type" => "text", "label" => "Attachments relative folder path: ", "next_html" => '
				<div class="info">The system will add this relative path to the absolute path as an extra folder. If you don\'t want any extra folder, please leave this field in blank.</div>
			'),
			"ATTACHMENTS_URL" => array("type" => "text", "label" => "Attachments URL: ", "next_html" => '
				<div class="info">To use the default module URL, please leave this field in blank. The default URL points to the default attachments folder path.<br/>
				Additionally the path for each attachment will be added to this url.</div>
				<div class="warning">Attention: Due to security reasons and if you change the attachment folder path, the system won\'t move/rename the old folder and the old attached files, which means you need to do it your-self, if applies...</div>
			'),
			"ALLOWED_MIME_TYPES" => array("name" => "none", "type" => "select", "label" => "Allowed mime types: ", "class" => "mime_types allowed_mime_types", "options" => $mime_types_options, "next_html" => '
				<span class="icon add" onClick="addMimeType(this)">Add</span>
				<table>
					<thead>
						<tr>
							<th class="extension">Extension</th>
							<th class="mime_type">Mime Type</th>
							<th class="icons"></th>
						</tr>
					</thead>
					<tbody>
						<tr><td class="empty_table" colspan="3">Empty...</td></tr>
					</tbody>
				</table>
			'),
			"DENIED_MIME_TYPES" => array("name" => "none", "type" => "select", "label" => "Denied mime types: ", "class" => "mime_types denied_mime_types", "options" => $mime_types_options, "next_html" => '
				<span class="icon add" onClick="addMimeType(this)">Add</span>
				<table>
					<thead>
						<tr>
							<th class="extension">Extension</th>
							<th class="mime_type">Mime Type</th>
							<th class="icons"></th>
						</tr>
					</thead>
					<tbody>
						<tr><td class="empty_table" colspan="3">Empty...</td></tr>
					</tbody>
				</table>
			'),
			"ALLOWED_EXTENSIONS" => array("name" => "none", "type" => "text", "label" => "Allowed extensions: ", "class" => "extensions allowed_extensions", "next_html" => '
				<span class="icon add" onClick="addExtension(this)">Add</span>
				<table>
					<thead>
						<tr>
							<th class="extension">Extension</th>
							<th class="icons"></th>
						</tr>
					</thead>
					<tbody>
						<tr><td class="empty_table" colspan="3">Empty...</td></tr>
					</tbody>
				</table>
			'),
			"DENIED_EXTENSIONS" => array("name" => "none", "type" => "text", "label" => "Denied extensions: ", "class" => "extensions denied_extensions", "next_html" => '
				<span class="icon add" onClick="addExtension(this)">Add</span>
				<table>
					<thead>
						<tr>
							<th class="extension">Extension</th>
							<th class="icons"></th>
						</tr>
					</thead>
					<tbody>
						<tr><td class="empty_table" colspan="3">Empty...</td></tr>
					</tbody>
				</table>
			'),
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_settings.css" type="text/css" charset="utf-8" />
	<script type="text/javascript" src="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_settings.js"></script>
	<script>
		var available_types_by_mime_types = ' . json_encode(MimeTypeHandler::getAvailableTypesByMimeType()) . ';
	
		var allowed_mime_types = \'' . $data["ALLOWED_MIME_TYPES"] . '\';
		var allowed_mime_type_html = \'<tr><td class="extension">#extension#</td><td class="mime_type"><input type="hidden" name="ALLOWED_MIME_TYPES[]" value="#mime_type#" />#mime_type#</td><td class="icons"><span class="icon delete" onClick="deleteMimeType(this)">Remove</span></td></tr>\';
		
		var denied_mime_types = \'' . $data["DENIED_MIME_TYPES"] . '\';
		var denied_mime_type_html = allowed_mime_type_html.replace("ALLOWED_MIME_TYPES", "DENIED_MIME_TYPES");
	
		var allowed_extensions = \'' . $data["ALLOWED_EXTENSIONS"] . '\';
		var allowed_extension_html = \'<tr><td class="extension"><input type="hidden" name="ALLOWED_EXTENSIONS[]" value="#extension#" />#extension#</td><td class="icons"><span class="icon delete" onClick="deleteExtension(this)">Remove</span></td></tr>\';
	
		var denied_extensions = \'' . $data["DENIED_EXTENSIONS"] . '\';
		var denied_extension_html = allowed_extension_html.replace("ALLOWED_EXTENSIONS", "DENIED_EXTENSIONS");
	</script>';
	$menu_settings = $AttachmentAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
