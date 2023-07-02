<?php
namespace CMSModule\attachment\upload_object_attachment;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		
		include $EVC->getConfigPath("config");
		
		//Including Stylesheet
		$html = '';
		if (empty($settings["style_type"])) {
			$html .= '<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/common/module.css" type="text/css" charset="utf-8" />
			<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/attachment/upload_object_attachment.css" type="text/css" charset="utf-8" />';
		}
		
		$html .= '<script type="text/javascript" src="' . $project_common_url_prefix . 'module/attachment/upload_object_attachment.js"></script>';
		$html .= $settings["css"] ? '<style>' . $settings["css"] . '</style>' : '';
		$html .= $settings["js"] ? '<script type="text/javascript">' . $settings["js"] . '</script>' : '';
		
		$html .= '<div class="module_list module_upload_object_attachment ' . ($settings["block_class"]) . '">';
		
		switch ($settings["template"]) {
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
		
		if ($_POST["upload"]) {
			$files = $_FILES["files"];
			
			if ($files) {
				include_once $EVC->getModulePath("attachment/AttachmentUtil", $EVC->getCommonProjectName());
				
				$status = \AttachmentUtil::uploadMultipleObjectFiles($EVC, $files, $settings["object_type_id"], $settings["object_id"], $settings["group"]);
				$message = $status ? "File(s) uploaded successfully" : "There was an error trying to upload file(s). Please try again...";
			}
			else {
				$message = "You must choose at least one file to upload. Please try again...";
			}
			
			$message = translateProjectText($EVC, $message);
		}
		
		$item_html = '<div class="upload_item">
			<label>' . translateProjectText($EVC, $settings["item_label"]) . '</label>
			<input type="file" name="files[]" />
			<span class="icon delete" onClick="removeUploadItem(this)">' . translateProjectText($EVC, "Remove") . '</span>
		</div>';
		
		$html = '
		<script>
		var upload_item_html = \'' . str_replace("\n", "", addcslashes($item_html, "\\'")) . '\';
		</script>
		<div class="individual_items_upload">';
		
		if ($message) {
			$html .= '<script>alert(\'' . $message . '\');</script>';
		}
		
		$html .= '<form method="post" enctype="multipart/form-data">
				<div class="main_label">
					<label>' . translateProjectText($EVC, $settings["main_label"]) . '</label>
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
		
		$html = '
		<!-- Adding DropZone plugin -->
		<script src="' . $project_common_url_prefix . 'vendor/dropzone/min/dropzone.min.js"></script>
		<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/dropzone/min/dropzone.min.css">
		
		<div class="drag_and_drop_upload">
			<div class="main_label">
				<label>' . translateProjectText($EVC, $settings["main_label"]) . '</label>
			</div>
			
			<div class="upload_files">
				<form method="post" action="' . $project_url_prefix . 'module/attachment/upload_object_attachment/upload_file" class="dropzone">
					<input type="hidden" name="object_type_id" value="' . $settings["object_type_id"] . '" />
					<input type="hidden" name="object_id" value="' . $settings["object_id"] . '" />
					<input type="hidden" name="group" value="' . $settings["group"] . '" />
					<div class="dz-default dz-message">
						<span>' . translateProjectText($EVC, $settings["item_label"] ? $settings["item_label"] : 'Drop files here to upload') . '</span>
					</div>
				</form>
			</div>
		</div>';
		
		return $html;
	}
}
?>
