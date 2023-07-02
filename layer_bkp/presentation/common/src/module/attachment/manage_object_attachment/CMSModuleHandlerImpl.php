<?php
namespace CMSModule\attachment\manage_object_attachment;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("attachment/AttachmentUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Preparing Data
		$action = $settings["action"];
		$object_type_id = $settings["object_type_id"];
		$object_id = $settings["object_id"];
		$group = $settings["group"];
		
		//Preparing Action
		$status = $attachment_id = $path = false;
		
		if ($_POST && $action && $object_type_id && is_numeric($object_id)) {
			switch ($action) {
				case "upload":
				case "image_upload_resize":
					$file_variable = $settings["file_variable"];
					$file = $_FILES[$file_variable];
					
					if ($file) {
						//Uploading file
						$attachment_id = \AttachmentUtil::uploadObjectFile($EVC, $file, $object_type_id, $object_id, $group, 0, $brokers);
						
						if ($attachment_id) {
							$status = true;
							
							$attachment = \AttachmentUtil::getAttachmentsByConditions($brokers, array("attachment_id" => $attachment_id), null);
							$path = $attachment[0]["path"];
							
							//Resizing uploaded image
							$resize_width = $settings["resize_width"];
							$resize_height = $settings["resize_height"];
							
							if ($action == "image_upload_resize" && ($resize_width || $resize_height)) {
								$file_path = \AttachmentUtil::getAttachmentsFolderPath($EVC) . $path;
								
								if (file_exists($file_path)) {
									if (!$resize_width || !$resize_height) {
										list($w, $h) = getimagesize($file_path);
						
										if ($w && $h) {
											if (!$resize_width)
												$resize_width = ($resize_height * $w) / $h;
											else
												$resize_height = ($resize_width * $h) / $w;
										}
									}
									
									if (!$resize_width || !$resize_height || !\AttachmentUtil::resizeImage($EVC, $attachment_id, $resize_width, $resize_height, $brokers))
										$status = false;
								}
							}
						}
					}
					
					break;
			}
		}
		
		//Preparing response
		if ($status) {
			if (strlen($settings["ok_response"])) {
				$settings["ok_response"] = translateProjectText($EVC, $settings["ok_response"]);
				$settings["ok_response"] = str_replace("#attachment_id#", $attachment_id, $settings["ok_response"]);
				$settings["ok_response"] = str_replace("#path#", $path, $settings["ok_response"]);
			}
			
			return $settings["ok_response"] ? $settings["ok_response"] : $status;
		}
		
		if (strlen($settings["error_response"])) {
			$settings["error_response"] = translateProjectText($EVC, $settings["error_response"]);
			$settings["error_response"] = str_replace("#attachment_id#", $attachment_id, $settings["error_response"]);
			$settings["error_response"] = str_replace("#path#", $path, $settings["error_response"]);
		}
		
		return $settings["error_response"];
	}
}
?>
