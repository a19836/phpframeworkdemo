<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

namespace CMSModule\attachment\manage_object_attachment;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("attachment/AttachmentUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Preparing Data
		$action = isset($settings["action"]) ? $settings["action"] : null;
		$object_type_id = isset($settings["object_type_id"]) ? $settings["object_type_id"] : null;
		$object_id = isset($settings["object_id"]) ? $settings["object_id"] : null;
		$group = isset($settings["group"]) ? $settings["group"] : null;
		
		//Preparing Action
		$status = $attachment_id = $path = false;
		
		if (!empty($_POST) && $action && $object_type_id && is_numeric($object_id)) {
			switch ($action) {
				case "upload":
				case "image_upload_resize":
					$file_variable = isset($settings["file_variable"]) ? $settings["file_variable"] : null;
					$file = isset($_FILES[$file_variable]) ? $_FILES[$file_variable] : null;
					
					if ($file) {
						//Uploading file
						$attachment_id = \AttachmentUtil::uploadObjectFile($EVC, $file, $object_type_id, $object_id, $group, 0, $brokers);
						
						if ($attachment_id) {
							$status = true;
							
							$attachment = \AttachmentUtil::getAttachmentsByConditions($brokers, array("attachment_id" => $attachment_id), null);
							$path = isset($attachment[0]["path"]) ? $attachment[0]["path"] : null;
							
							//Resizing uploaded image
							$resize_width = isset($settings["resize_width"]) ? $settings["resize_width"] : null;
							$resize_height = isset($settings["resize_height"]) ? $settings["resize_height"] : null;
							
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
			if (isset($settings["ok_response"]) && strlen($settings["ok_response"])) {
				$settings["ok_response"] = translateProjectText($EVC, $settings["ok_response"]);
				$settings["ok_response"] = str_replace("#attachment_id#", $attachment_id, $settings["ok_response"]);
				$settings["ok_response"] = str_replace("#path#", $path, $settings["ok_response"]);
			}
			
			return !empty($settings["ok_response"]) ? $settings["ok_response"] : $status;
		}
		
		if (isset($settings["error_response"]) && strlen($settings["error_response"])) {
			$settings["error_response"] = translateProjectText($EVC, $settings["error_response"]);
			$settings["error_response"] = str_replace("#attachment_id#", $attachment_id, $settings["error_response"]);
			$settings["error_response"] = str_replace("#path#", $path, $settings["error_response"]);
		}
		
		return isset($settings["error_response"]) ? $settings["error_response"] : null;
	}
}
?>
