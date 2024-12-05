<?php
namespace CMSModule\attachment\upload_object_attachment;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		
		include $EVC->getConfigPath("config");
		
		//Including Stylesheet
		$html = '';
		if (empty($settings["style_type"])) {
			$html .= '<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/common/module.css" type="text/css" charset="utf-8" />
			<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/attachment/upload_object_attachment.css" type="text/css" charset="utf-8" />';
		}
		
		$html .= '<script type="text/javascript" src="' . $project_common_url_prefix . 'module/attachment/upload_object_attachment.js"></script>';
		$html .= !empty($settings["css"]) ? '<style>' . $settings["css"] . '</style>' : '';
		$html .= !empty($settings["js"]) ? '<script type="text/javascript">' . $settings["js"] . '</script>' : '';
		
		$html .= '<div class="module_list module_upload_object_attachment ' . (isset($settings["block_class"]) ? $settings["block_class"] : null) . '">';
		
		$template = isset($settings["template"]) ? $settings["template"] : null;
		
		switch ($template) {
			case "individual_items_upload":
				$html .= $this->getIndividualItemsUploadHtml($settings);
				break;
			default: //"drag_and_drop_upload"
				$html .= $this->getDragAndDropUploadHtml($settings);
		}
		
		$html .= '</div>';
		
		return $html;
	}
	
	private function getIndividualItemsUploadHtml($settings) {
		$EVC = $this->getEVC();
		
		if (!empty($_POST["upload"])) {
			$files = isset($_FILES["files"]) ? $_FILES["files"] : null;
			
			if ($files) {
				include_once $EVC->getModulePath("attachment/AttachmentUtil", $EVC->getCommonProjectName());
				
				$object_type_id = isset($settings["object_type_id"]) ? $settings["object_type_id"] : null;
				$object_id = isset($settings["object_id"]) ? $settings["object_id"] : null;
				$group = isset($settings["group"]) ? $settings["group"] : null;
				
				$status = \AttachmentUtil::uploadMultipleObjectFiles($EVC, $files, $object_type_id, $object_id, $group);
				$message = $status ? "File(s) uploaded successfully" : "There was an error trying to upload file(s). Please try again...";
			}
			else {
				$message = "You must choose at least one file to upload. Please try again...";
			}
			
			$message = translateProjectText($EVC, $message);
		}
		
		$main_label = isset($settings["main_label"]) ? $settings["main_label"] : null;
		$item_label = isset($settings["item_label"]) ? $settings["item_label"] : null;
		
		$item_html = '<div class="upload_item">
			<label>' . translateProjectText($EVC, $item_label) . '</label>
			<input type="file" name="files[]" />
			<span class="icon delete" onClick="removeUploadItem(this)">' . translateProjectText($EVC, "Remove") . '</span>
		</div>';
		
		$html = '
		<script>
		var upload_item_html = \'' . str_replace("\n", "", addcslashes($item_html, "\\'")) . '\';
		</script>
		<div class="individual_items_upload">';
		
		if (!empty($message)) {
			$html .= '<script>alert(\'' . $message . '\');</script>';
		}
		
		$html .= '<form method="post" enctype="multipart/form-data">
				<div class="main_label">
					<label>' . translateProjectText($EVC, $main_label) . '</label>
					<span class="icon add" title="' . translateProjectText($EVC, "Add new upload item") . '" onClick="addNewUploadItem(this)">Add</span>
				</div>
				
				<div class="upload_items">
					' . $item_html . '
				</div>
				
				<div class="submit_button">
					<input type="submit" name="upload" value="' . translateProjectText($EVC, "Upload") . '" />
				</div>
			</form>
		</div>';
		
		return $html;
	}
	
	private function getDragAndDropUploadHtml($settings) {
		$EVC = $this->getEVC();
		
		include $EVC->getConfigPath("config");
		
		$main_label = isset($settings["main_label"]) ? $settings["main_label"] : null;
		$item_label = isset($settings["item_label"]) ? $settings["item_label"] : null;
		$object_type_id = isset($settings["object_type_id"]) ? $settings["object_type_id"] : null;
		$object_id = isset($settings["object_id"]) ? $settings["object_id"] : null;
		$group = isset($settings["group"]) ? $settings["group"] : null;
		
		$html = '
		<!-- Adding DropZone plugin -->
		<script src="' . $project_common_url_prefix . 'vendor/dropzone/min/dropzone.min.js"></script>
		<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/dropzone/min/dropzone.min.css">
		
		<div class="drag_and_drop_upload">
			<div class="main_label">
				<label>' . translateProjectText($EVC, $main_label) . '</label>
			</div>
			
			<div class="upload_files">
				<form method="post" action="' . $project_url_prefix . 'module/attachment/upload_object_attachment/upload_file" class="dropzone">
					<input type="hidden" name="object_type_id" value="' . $object_type_id . '" />
					<input type="hidden" name="object_id" value="' . $object_id . '" />
					<input type="hidden" name="group" value="' . $group . '" />
					<div class="dz-default dz-message">
						<span>' . translateProjectText($EVC, $item_label ? $item_label : 'Drop files here to upload') . '</span>
					</div>
				</form>
			</div>
		</div>';
		
		return $html;
	}
}
?>
