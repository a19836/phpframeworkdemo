<?php
if (!class_exists("AttachmentUtil")) {
	include_once get_lib("org.phpframework.encryption.CryptoKeyHandler");
	include_once get_lib("org.phpframework.util.web.ImageHandler");
	include __DIR__ . "/AttachmentSettings.php";
	include __DIR__ . "/AttachmentDBDAOUtil.php"; //this file will be automatically generated on this module installation
	include __DIR__ . "/ObjectAttachmentDBDAOUtil.php"; //this file will be automatically generated on this module installation

	class AttachmentUtil extends AttachmentSettings {
	
		/* GENERIC FUNCTIONS */
	
		public static function getAttachmentsFolderPath($EVC) {
			$folder_path = trim(self::getConstantVariable("ATTACHMENTS_ABSOLUTE_FOLDER_PATH"));
			if (empty($folder_path)) {
				$folder_path = $EVC->getWebrootPath( $EVC->getCommonProjectName() ) . "module/attachment/";
			}
			
			return (substr($folder_path, -1) != "/" ? "$folder_path/" : $folder_path) . self::getConstantVariable("ATTACHMENTS_RELATIVE_FOLDER_PATH") . "/";
		}
		
		public static function getAttachmentsFolderUrl($EVC) {
			include $EVC->getConfigPath("config");
			
			$attachments_url = self::getConstantVariable("ATTACHMENTS_URL");
			if ($attachments_url)
				return $attachments_url;
			
			return $project_common_url_prefix . "/module/attachment/" . self::getConstantVariable("ATTACHMENTS_RELATIVE_FOLDER_PATH") . "/";
		}
		
		public static function readAttachment($EVC, $path, $options = null) {
			$folder_path = self::getAttachmentsFolderPath($EVC);
			$file_path = $folder_path . $path;
			
			if ($path && file_exists($file_path)) {
				$fop = realpath($folder_path);
				$fip = realpath($file_path);
				
				if (substr($fip, 0, strlen($fop)) == $fop) {
					$options = $options ? $options : array();
					$name = $options["name"];
					
					if (!$name) {
						$name = basename($file_path);
						$parts = explode("_", $name);
						$attachment_id = $parts[1];
						
						if (is_numeric($attachment_id)) {
							//Caching attachment name
							$UserCacheHandler = $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler");
							$UserCacheHandler->config(600, false);//10 min
							$cached_file_name = "attachments_name/" . dirname($path) . "/" . $attachment_id;
							
							if ($UserCacheHandler->isValid($cached_file_name))
								$name = $UserCacheHandler->read($cached_file_name);
							else {
								$attachment_data = self::getAttachmentsByConditions($EVC->getPresentationLayer()->getBrokers(), array("attachment_id" => $attachment_id), null);
								$name = $attachment_data[0]["name"] ? $attachment_data[0]["name"] : $name;
								
								//saving cache
								$UserCacheHandler->write($cached_file_name, $name);
							}
						}
					}
					
					header('Content-Length: ' . filesize($file_path));
					
					if ($options["force_download"]) {
						header('Content-Type: application/octet-stream');
						header('Content-Disposition: attachment; filename="' . $name . '"');
					}
					else  {
						header('Content-Type: ' . MimeTypeHandler::getFileMimeType($file_path)); 
						header('Content-Disposition: inline; filename="' . $name . '"');
					}

					if ($options["description"])
						header('Content-Description: ' . $options["description"]);
					
					//More info about the cache-control in https://developers.google.com/web/fundamentals/performance/optimizing-content-efficiency/http-caching#defining-optimal-cache-control-policy
					if ($options["cache"])
						header('Cache-Control: ' . $options["cache"]);
					
					if ($options["expires"])
						header('Expires: ' . $options["expires"]);
					
					if ($options["pragma"])
						header('Pragma: ' . $options["pragma"]);
					
					readfile($file_path);
					exit;
				}
				else {
					header ("HTTP/1.0 500 User not authorized");
					header('Content-Type: application/octet-stream');
					exit;
				}
			}
			
			header ("HTTP/1.0 404 Not Found");
			header('Content-Type: application/octet-stream');
			exit;
		}
		
		/* DELETE OLD DATA FUNCTIONS */
		
		// Delete all temporary and expired files inside of the attachments folder. Default $ttl is 1 hour.
		public static function deleteTempFiles($EVC, $folder_path = false, $ttl = 3600, $options = array()) {
			$status = true;
			$folder_path = $folder_path ? $folder_path : self::getAttachmentsFolderPath($EVC);
			$files = $folder_path && is_dir($folder_path) ? array_diff(scandir($folder_path), array('..', '.')) : null;
			$expired_time = time() - $ttl;
			
			if ($files)
				foreach ($files as $file) {
					$f = "$folder_path/$file";
					
					//The attachment module doesn't have hidden files, so we can skip these files. But bc the svn and git files, the user may wish to ignore these files...
					$continue = $options["ignore_hidden_files"] ? substr($file, 0, 1) != "." : true;
					
					if ($continue) {
						if (is_dir($f)) {
							if (!self::deleteTempFiles($EVC, $f, $ttl, $options))
								$status = false;
						}
						else if (substr($file, 0, 5) == "temp_" && stripos($file, "_resized_") > 0 && filemtime($f) < $expired_time) {
							//echo "deleteTempFiles:$f<br>\n";
							if (!unlink($f))
								$status = false;
						}
					}
				}
			
			return $status;
		}
		
		// Delete all empty and expired folders inside of the attachments folder. Default $ttl is 1 hour.
		public static function deleteEmptyFolders($EVC, $folder_path = false, $ttl = 3600, $options = array()) {
			$status = true;
			$folder_path = $folder_path ? $folder_path : self::getAttachmentsFolderPath($EVC);
			$files = $folder_path && is_dir($folder_path) ? array_diff(scandir($folder_path), array('..', '.')) : null;
			$expired_time = time() - $ttl;
			
			if ($files)
				foreach ($files as $file) {
					$f = "$folder_path/$file";
					
					//The attachment module doesn't have hidden files, so we can skip these files. But bc the svn and git files, the user may wish to ignore these files...
					$continue = $options["ignore_hidden_files"] ? substr($file, 0, 1) != "." : true;
					
					if ($continue && is_dir($f)) {
						self::deleteEmptyFolders($EVC, $f, $ttl, $options);
						
						$has_files = false;
						if ($dh = opendir($f)) {
							while (($sub_file = readdir($dh)) !== false) {
								$continue = $options["ignore_hidden_files"] ? substr($sub_file, 0, 1) != "." : $sub_file != "." && $sub_file != "..";
								
								if ($continue) {
									$has_files = true;
									break;
								}
							}
							closedir($dh);
						}
						
						if (!$has_files && filemtime($f) < $expired_time) {
							//echo "deleteEmptyFolders:$f<br>\n";
							if ($options["ignore_hidden_files"]) {
								if (!CacheHandlerUtil::deleteFolder($f))
									$status = false;
							}
							else if (!rmdir($f))
								$status = false;
						}
					}
				}
			
			return $status;
		}
		
		// Delete all the db items from mat_object_attachment that are not in the mat_attachment
		public static function deleteDBInconsistencies($EVC) {
			$brokers = $EVC->getPresentationLayer()->getBrokers();
			
			return self::deleteCorruptedObjectAttachments($brokers);
		}
		
		// Delete all the expired folders that don't exist anymore in the DB
		public static function deleteDBNonExistentFiles($EVC, $folder_path = false, $ttl = 3600, $options = array("ignore_hidden_files" => 1), $prefix_path = "") {
			$status = true;
			$folder_path = $folder_path ? $folder_path : self::getAttachmentsFolderPath($EVC);
			$prefix_path = $prefix_path && substr($prefix_path, -1) == "/" ? substr($prefix_path, 0, -1) : $prefix_path;
			$files = $folder_path && is_dir("$folder_path/$prefix_path") ? array_diff(scandir("$folder_path/$prefix_path"), array('..', '.')) : null;
			$expired_time = time() - $ttl;
			$brokers = $EVC->getPresentationLayer()->getBrokers();
			
			if ($files)
				foreach ($files as $file) {
					//The attachment module doesn't have hidden files, so we can skip these files. But bc the svn and git files, the user may wish to ignore these files...
					$continue = $options["ignore_hidden_files"] ? substr($file, 0, 1) != "." : true;
					
					if ($continue) {
						$pf = $prefix_path ? "$prefix_path/$file" : $file;
						$f = "$folder_path/$pf";
						$is_dir = is_dir($f);
						
						$cond = $is_dir ? array("path" => array("operator" => "like", "value" => "$pf/%")) : array("path" => $pf);
						$exists = self::getAttachmentsByConditions($brokers, $cond, null);
						
						if (!$exists && filemtime($f) < $expired_time) {
							//echo "deleteDBNonExistentFiles:$f<br>\n";
							if ($is_dir) {
								if (!CacheHandlerUtil::deleteFolder($f))
									$status = false;
							}
							else if (!unlink($f))
								$status = false;
						}
						else if ($exists && $is_dir && !self::deleteDBNonExistentFiles($EVC, $folder_path, $ttl, $options, $pf))
							$status = false;
					}
				}
			
			return $status;
		}
		
		// Delete old attachments from a specific object
		public static function deleteOldObjectAttachments($EVC, $object_type_id, $object_id, $group = null, $folder_path = false, $ttl = 3600) {
			$status = true;
			$folder_path = $folder_path ? $folder_path : self::getAttachmentsFolderPath($EVC);
			$expired_time = time() - $ttl;
			$brokers = $EVC->getPresentationLayer()->getBrokers();
			
			$attachments = is_numeric($group) ? self::getAttachmentsByObjectGroup($brokers, $object_type_id, $object_id, $group) : self::getAttachmentsByObject($brokers, $object_type_id, $object_id);
			
			if ($attachments)
				foreach ($attachments as $attachment) {
					$path = $attachment["path"];
					
					if ($path && file_exists("$folder_path/$path") && filemtime("$folder_path/$path") < $expired_time) {
						//echo "deleteOldObjectAttachments:".$attachment["attachment_id"]."<br>\n";
						if (!self::deleteAttachment($brokers, $attachment["attachment_id"]))
							$status = false;
					}
				}
			
			return $status;
		}
		
		/* UPLOAD FUNCTIONS */
	
		/*
		 * Based on a UI which list the object attachments for a specific object_id, allows the user to insert new attachments, updates the name of the existent attachments or remove attachments.
		 * Saves the existent attachments names for a specific object and Upload the new attachments. 
		 * Saves attachments according with the UI order.
		 */
		public static function saveObjectAttachments($EVC, $object_type_id, $object_id, $group = null, &$error_message = null) {
			$status = true;
		
			if ($_POST && $object_type_id && is_numeric($object_id)) {
				$brokers = $EVC->getPresentationLayer()->getBrokers();
			
				//Preparing variables
				$attachments = $_POST["attachments"];
				$files = $_FILES["attachment_files"];
				$attachment_names = $_POST["attachment_names"];
				$attachment_ids = is_array($_POST["attachment_ids"]) ? $_POST["attachment_ids"] : array();
				
				$file_id = 0;
			
				//Preparing attachments to delete
				$existent_attachments = self::getObjectAttachmentsByConditions($brokers, array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), null);
			
				$attachment_ids_to_delete = array();
				
				if ($existent_attachments) {
					$t = count($existent_attachments);
					for ($i = 0; $i < $t; $i++)
						if (!in_array($existent_attachments[$i]["attachment_id"], $attachment_ids))
							$attachment_ids_to_delete[] = $existent_attachments[$i]["attachment_id"];
				}
			
				//Saving new attachment names and upload new attachments
				$t = $attachments ? count($attachments) : 0;
				for ($i = 0; $i < $t; $i++) {
					$oa = $attachments[$i];
					$attachment_id = $attachment_ids[$i];
				
					if (isset($oa["file"])) {//Upload attachment
						$file = array(
							"tmp_name" => $files["tmp_name"][$file_id],
							"name" => $files["name"][$file_id],
							"type" => $files["type"][$file_id],
							"size" => $files["size"][$file_id],
							"error" => $files["error"][$file_id],
						);
						$attachment_id = self::uploadObjectFile($EVC, $file, $object_type_id, $object_id, $group, $i + 1, $brokers);
					
						if (!$attachment_id) {
							$status = false;
							$error_message = "There was an error trying to upload attachment. Please try again...";
						}
					
						$file_id++;
					}
					else if ($attachment_id) {//Save attachment name
						$new_name = $oa["name"];
						$old_name = $attachment_names[$i];
					
						if (empty($new_name)) {
							$status = false;
							$error_message = "Attachment name cannot be empty.";
						}
						else if ($new_name != $old_name) {
							$data = array(
								"attachment_id" => $attachment_id,
								"name" => $new_name,
							);
						
							if (!self::updateAttachmentName($brokers, $data)) {
								$status = false;
								$error_message = "There was an error trying to update attachment's name. Please try again...";
							}
						}
					
						//update order in object_attachment
						$data = array(
							"old_attachment_id" => $attachment_id,
							"old_object_type_id" => $object_type_id,
							"old_object_id" => $object_id,
							"new_attachment_id" => $attachment_id,
							"new_object_type_id" => $object_type_id,
							"new_object_id" => $object_id,
							"group" => $group,
							"order" => $i + 1,
						);
						if (!self::updateObjectAttachment($brokers, $data)) {
							$status = false;
							$error_message = "There was an error trying to update attachment's order. Please try again...";
						}
					}
				}
				
				if ($status) {
					//Delete old attachments that user removed, but only remove attachment file if attachment only belongs to correspondent object...
					foreach ($attachment_ids_to_delete as $attachment_id) {
						$delete = true;
					
						//Checks if attachment belongs to another object
						$object_attachments = self::getObjectAttachmentsByAttachmentId($brokers, $attachment_id);
						
						if ($object_attachments) {
							$t = count($object_attachments);
							for ($i = 0; $i < $t; $i++) {
								$oa = $object_attachments[$i];
							
								if ($oa["object_type_id"] != $object_type_id || $oa["object_id"] != $object_id || $oa["group"] != $group) {
									$delete = false;
									break;
								}
							}
						}
						
						//If only belongs to $object_id, deletes attachment
						if ($delete && !self::deleteFile($EVC, $attachment_id, $brokers))
							$status = false;
					}
				}
			}
		
			if (!$status && !$error_message)
				$error_message = "There was an error trying to save the attachments. Please try again...";
		
			return $status;
		}
	
		/*
		 * Upload file to server and relate it with a object. If data existed in DB, update data and relationships, otherwise insert new data.
		 */
		public static function replaceObjectFile($EVC, $file, $attachment_id, $object_type_id, $object_id, $group = null, $order = 0, $brokers = array(), $is_local_file = false) {
			if (self::isUploadedFileAllowed($file, $is_local_file)) {
				$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
				$folder_path = self::getAttachmentsFolderPath($EVC);
				
				//check if file exists in DB
				if ($attachment_id) {
					$attachment_data = self::getAttachmentsByConditions($brokers, array("attachment_id" => $attachment_id), null);
					$attachment_data = $attachment_data[0];
				}
		
				if ($attachment_data) {
					//Delete file from server
					if ($attachment_data["path"]) {
						$file_path = $folder_path . $attachment_data["path"];
						if (file_exists($file_path) && !unlink($file_path)) {
							return false;
						}
					}
					
					$file["name"] = $file["name"] ? $file["name"] : basename($file["tmp_name"]);
					$extension = pathinfo($file["name"], PATHINFO_EXTENSION);
					$extension = $extension ? "." . $extension : "";
			
					$file_name = self::getAttachmentRelativePath($EVC, $attachment_id, $extension);
					$file_path = $folder_path . $file_name;
					
					//Upload file
					if (self::createAttachmentFileFolder($file_path) && self::moveUploadedFile($file["tmp_name"], $file_path, $is_local_file)) {
						//save data to DB
						$data = array(
							"attachment_id" => $attachment_id,
							"name" => str_replace(array("\\", "'"), "", $file["name"]),
							"type" => $file["type"] ? str_replace(array("\\", "'"), "", $file["type"]) : MimeTypeHandler::getFileMimeType($file_path),
							"size" => is_numeric($file["size"]) && $file["size"] > 0 ? $file["size"] : filesize($file_path),
							"path" => $file_name,
						);
					
						if (self::updateAttachment($brokers, $data)) {
							//Checks if relationship exists
							$data = array(
								"attachment_id" => $attachment_id, 
								"object_type_id" => $object_type_id, 
								"object_id" => $object_id,
							);
							if (isset($group)) {
								$data["group"] = $group;
							}
						
							$relationships = self::getObjectAttachmentsByConditions($brokers, $data, null);
						
							//insert relationship
							if (!$relationships[0] && !self::insertObjectAttachment($brokers, $data)) {
								return false;
							}
						
							return $attachment_id;
						}
					}
				}
				else {
					//Delete old relationships because this attachment_id doesn't exists anymore in the DB. It shouldn't have any relationships, but just in case, we remove it either way...
					if ($attachment_id) {
						//self::deleteObjectFilesByGroup($EVC, $attachment_id, $group, $brokers);
						self::deleteObjectFiles($EVC, $attachment_id, $brokers);
					}
					
					//upload object file
					return self::uploadObjectFile($EVC, $file, $object_type_id, $object_id, $group, $order, $brokers, $folder_path, $is_local_file);
				}	
			}
		
			return false;
		}
	
		/*
		 * Upload file to server. If data existed in DB, update data and relationships, otherwise insert new data.
		 * Note that if you wish to relate the uploaded file to an object, please use the replaceObjectFile method instead.
		 */
		public static function replaceFile($EVC, $file, $attachment_id, $brokers = array(), $is_local_file = false) {
			if (self::isUploadedFileAllowed($file, $is_local_file)) {
				$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
				$folder_path = self::getAttachmentsFolderPath($EVC);
				
				//check if file exists in DB
				if ($attachment_id) {
					$attachment_data = self::getAttachmentsByConditions($brokers, array("attachment_id" => $attachment_id), null);
					$attachment_data = $attachment_data[0];
				}
		
				if ($attachment_data) {
					//Delete file from server
					if ($attachment_data["path"]) {
						$file_path = $folder_path . $attachment_data["path"];
						if (file_exists($file_path) && !unlink($file_path)) {
							return false;
						}
					}
					
					$file["name"] = $file["name"] ? $file["name"] : basename($file["tmp_name"]);
					$extension = pathinfo($file["name"], PATHINFO_EXTENSION);
					$extension = $extension ? "." . $extension : "";
			
					$file_name = self::getAttachmentRelativePath($EVC, $attachment_id, $extension);
					$file_path = $folder_path . $file_name;
					
					//Upload file
					if (self::createAttachmentFileFolder($file_path) && self::moveUploadedFile($file["tmp_name"], $file_path, $is_local_file)) {
						//save data to DB
						$data = array(
							"attachment_id" => $attachment_id,
							"name" => str_replace(array("\\", "'"), "", $file["name"]),
							"type" => $file["type"] ? str_replace(array("\\", "'"), "", $file["type"]) : MimeTypeHandler::getFileMimeType($file_path),
							"size" => is_numeric($file["size"]) && $file["size"] > 0 ? $file["size"] : filesize($file_path),
							"path" => $file_name,
						);
					
						if (self::updateAttachment($brokers, $data)) {
							return $attachment_id;
						}
					}
				}
				else {
					//Delete old relationships because this attachment_id doesn't exists anymore in the DB. It shouldn't have any relationships, but just in case, we remove it either way...
					if ($attachment_id) {
						//self::deleteObjectFilesByGroup($EVC, $attachment_id, $group, $brokers);
						self::deleteObjectFiles($EVC, $attachment_id, $brokers);
					}
					
					//upload file
					return self::uploadFile($EVC, $file, $brokers, $folder_path, $is_local_file);
				}	
			}
		
			return false;
		}
		
		/*
		 * Update an attachment with new data. Checks the previous db data (if exists) and rename the server file with the new data[path]
		 * VERY CAREFULL USING THIS FUNCTION, bc of security issues. If there is a $attachment_data["path"] that contains an apache executable extension, and if someone finds the direct link for this attachment, the file will be executed! By default this action is disabled! If you want to enable it, please add $security = false;
		 */
		public static function updateFile($EVC, $attachment_data, $brokers = array(), $old_attachment_data = array(), $security = true) {
			$attachment_id = $attachment_data["attachment_id"];
		
			if ($attachment_id) {
				//check if exists extension in the path attribute bc of security issues. This is, if there is someone that adds a php extension and someone can find the direct link for this attachment, the code will be executed. But if there is no extension, the http server (apache) won't execute it and will not treat it as a php file. So the EXTENSION MUST NOT EVER BE PRESENT!
				$extension = $attachment_data["path"] ? pathinfo($attachment_data["path"], PATHINFO_EXTENSION) : "";
				if ($security && $extension)
					return false;
				
				$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
			
				if (!$old_attachment_data) {
					$old_attachment_data = self::getAttachmentsByConditions($brokers, array("attachment_id" => $attachment_id), null);
					$old_attachment_data = $old_attachment_data[0];
				}
				
				if ($old_attachment_data) {
					$status = true;
						
					//rename file in server
					if ($old_attachment_data["path"] && $old_attachment_data["path"] != $attachment_data["path"]) {
						$folder_path = self::getAttachmentsFolderPath($EVC);
			
						if (file_exists($folder_path . $old_attachment_data["path"])) {
							if (!$attachment_data["path"]) {
								return false;
							}
							
							$status = self::isFileExtensionAllowed($attachment_data["path"]) && self::createAttachmentFileFolder($folder_path . $attachment_data["path"]) && rename($folder_path . $old_attachment_data["path"], $folder_path . $attachment_data["path"]);
						}
					}
			
					return $status ? self::updateAttachment($brokers, $attachment_data) : false;
				}
			}
		
			return false;
		}
	
		/*
		 * Resize an attachment if is a image.
		 */
		public static function resizeImage($EVC, $attachment_id, $width, $height, $brokers = array()) {
			if ($attachment_id && is_numeric($width) && is_numeric($height)) {
				$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
			
				$attachment_data = self::getAttachmentsByConditions($brokers, array("attachment_id" => $attachment_id), null);
				$attachment_data = $attachment_data[0];
			
				if ($attachment_data) {
					$status = true;
					
					if ($attachment_data["path"]) {
						$folder_path = self::getAttachmentsFolderPath($EVC);
			
						if (file_exists($folder_path . $attachment_data["path"])) {
							$src = $folder_path . $attachment_data["path"];
							$dst = $folder_path . "attachment_{$attachment_id}_resized_{$width}x{$height}_" . uniqid();
							
							$ImageHandler = new ImageHandler();
							
							if (@$ImageHandler->isImageBinaryValid($src) && @$ImageHandler->imageResize($src, $dst, $width, $height) && unlink($src) && rename($dst, $src)) {
								$attachment_data["size"] = filesize($src);
								return self::updateAttachment($brokers, $attachment_data);
							}
							else if (file_exists($dst))
								unlink($dst);
						}
					}
				}
			}
		
			return false;
		}
	
		/*
		 * Delete attachments by object
		 */
		public static function deleteFileByObject($EVC, $object_type_id, $object_id, $group = null, $brokers = array()) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
			$cond = array("object_type_id" => $object_type_id, "object_id" => $object_id);
			if (isset($group)) {
				$cond["group"] = $group;
			}
			$attachments = self::getObjectAttachmentsByConditions($brokers, $cond, null);
		
			$status = true;
		
			if ($attachments) {
				$repeated = array();
			
				foreach ($attachments as $attachment) {
					$attachment_id = $attachment["attachment_id"];
				
					if (!$repeated[$attachment_id] && !self::deleteFile($EVC, $attachment_id, $brokers)) {
						$status = false;
					}
				
					$repeated[$attachment_id] = true;
				}
			}
		
			return $status;
		}
	
		/*
		 * Delete all relationships for a specific attachment_id and a specific group
		 */
		public static function deleteObjectFilesByGroup($EVC, $attachment_id, $group = null, $brokers = array()) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
			$cond = array("attachment_id" => $attachment_id);
			if (isset($group)) {
				$cond["group"] = $group;
			}
			return self::deleteObjectAttachmentsByConditions($brokers, $cond, null);
		}
	
		/*
		 * Delete all relationships for a specific attachment_id 
		 */
		public static function deleteObjectFiles($EVC, $attachment_id, $brokers = array()) {
			$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
		
			return self::deleteObjectAttachmentsByAttachmentId($brokers, $attachment_id);
		}
	
		/*
		 * Delete an attachment including all the relationships and server file.
		 */
		public static function deleteFile($EVC, $attachment_id, $brokers = array(), $folder_path = "") {
			if ($attachment_id) {
				$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
			
				if (self::deleteObjectFiles($EVC, $attachment_id, $brokers)) {
					$attachment_data = self::getAttachmentsByConditions($brokers, array("attachment_id" => $attachment_id), null);
				
					//Delete file from server
					if ($attachment_data[0]["path"]) {
						$folder_path = $folder_path ? $folder_path : self::getAttachmentsFolderPath($EVC);
						$attachment_file_path = $folder_path . $attachment_data[0]["path"];
					
						if (file_exists($attachment_file_path) && !unlink($attachment_file_path)) {
							return false;
						}
					}
				
					return self::deleteAttachment($brokers, $attachment_id);
				}
			}
		
			return false;
		}
	
		/*
		 * Upload multiple files to server, add them to DB with the correspondent relationships
		 */
		public static function uploadMultipleObjectFiles($EVC, $files, $object_type_id, $object_id, $group = null, $is_local_file = false) {
			if ($object_type_id && is_numeric($object_id)) {
				$status = true;
		
				$brokers = $EVC->getPresentationLayer()->getBrokers();
				$folder_path = self::getAttachmentsFolderPath($EVC);
				
				if ($files["tmp_name"]) {
					$t = count($files["tmp_name"]);
					for ($i = 0; $i < $t; $i++) {
						$file = array(
							"tmp_name" => $files["tmp_name"][$i],
							"name" => $files["name"][$i],
							"type" => $files["type"][$i],
							"size" => $files["size"][$i],
							"error" => $files["error"][$i],
						);
				
						if (!self::uploadObjectFile($file, $object_type_id, $object_id, $group, $i + 1, $brokers, $folder_path, $is_local_file))
							$status = false;
					}
				}
				
				return $status;
			}
		
			return false;
		}
	
		/*
		 * Upload file to server, add it to DB with the correspondent relationship
		 */
		public static function uploadObjectFile($EVC, $file, $object_type_id, $object_id, $group = null, $order = 0, $brokers = array(), $folder_path = "", $is_local_file = false) {
			if ($object_type_id && is_numeric($object_id)) {
				$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
				$folder_path = $folder_path ? $folder_path : self::getAttachmentsFolderPath($EVC);
				
				$attachment_id = self::uploadFile($EVC, $file, $brokers, $folder_path, $is_local_file);
				
				if ($attachment_id) {
					$data = array(
						"attachment_id" => $attachment_id,
						"object_type_id" => $object_type_id,
						"object_id" => $object_id,
						"group" => $group,
						"order" => $order,
					);
	
					if (self::insertObjectAttachment($brokers, $data))
						return $attachment_id;
				}
			}
			
			return false;
		}
		
		/*
		 * Upload file to server, add it to DB. This doesn't save the attachment relationhip, but only the attachment data to DB.
		 */
		public static function uploadFile($EVC, $file, $brokers = array(), $folder_path = "", $is_local_file = false) {
			if (self::isUploadedFileAllowed($file, $is_local_file)) {
				$brokers = $brokers ? $brokers : $EVC->getPresentationLayer()->getBrokers();
				$folder_path = $folder_path ? $folder_path : self::getAttachmentsFolderPath($EVC);
				
				$file["name"] = $file["name"] ? $file["name"] : basename($file["tmp_name"]);
				$extension = pathinfo($file["name"], PATHINFO_EXTENSION);
				$extension = $extension ? "." . $extension : "";
				
				$file_name = self::getTemporaryAttachmentRelativePath($file, $extension);
				$file_path = $folder_path . $file_name;

				if (self::createAttachmentFileFolder($file_path) && self::moveUploadedFile($file["tmp_name"], $file_path, $is_local_file)) {
					$data = array(
						"name" => str_replace(array("\\", "'"), "", $file["name"]),
						"type" => $file["type"] ? str_replace(array("\\", "'"), "", $file["type"]) : MimeTypeHandler::getFileMimeType($file_path),
						"size" => is_numeric($file["size"]) && $file["size"] > 0 ? $file["size"] : filesize($file_path),
						"path" => $file_name,
					);
					$attachment_id = self::insertAttachment($brokers, $data);
					
					if ($attachment_id) {
						$file_name = self::getAttachmentRelativePath($EVC, $attachment_id, $extension);
						
						if (self::createAttachmentFileFolder($folder_path . $file_name) && rename($file_path, $folder_path . $file_name)) {
							$data["attachment_id"] = $attachment_id;
							$data["path"] = $file_name;
					
							if (self::updateAttachment($brokers, $data)) {
								return $attachment_id;
							}
						}
					}
				}
			}
		
			return false;
		}
		
		public static function getTemporaryAttachmentRelativePath($file, $extension) {
			return "temp_" . basename($file["tmp_name"]) . "_" . uniqid() . rand(0, 1000000);// . $extension;//$extension var removed bc of security issues. This is, if there is a hack to the our upload service where the hacker can upload a php file and he can find the direct link for this file, the code will be executed. But if there is no extension, the http server (apache) won't execute it and will not treat it as a php file. So the EXTENSION MUST NOT EVER BE PRESENT!
		}
		
		public static function getAttachmentRelativePathPrefix($EVC) {
			$default_db_driver = $GLOBALS["default_db_driver"];
			
			$pre_init_config_path = $EVC->getConfigPath("pre_init_config");
			
			if (file_exists($pre_init_config_path))
				include $pre_init_config_path; //if pre_init_config exists, then it may change the $default_db_driver
			
			$db = $default_db_driver ? hash("crc32b", $default_db_driver) : "default";
			
			return $db;
		}
		
		public static function getAttachmentRelativePath($EVC, $attachment_id, $extension) {
			$db = self::getAttachmentRelativePathPrefix($EVC);
			
			$path = md5($attachment_id);
			$path = $db . "/" . substr($path, 0, 3) . "/" . substr($path, 3, 3) . "/" . substr($path, 6, 3) . "/attachment_" . $attachment_id . "_";
			
			$folder_path = self::getAttachmentsFolderPath($EVC);
			
			do {
				$file_path = $path . uniqid();// . $extension;//$extension var removed bc of security issues. This is, if there is a hack to the our upload service where the hacker can upload a php file and he can find the direct link for this file, the code will be executed. But if there is no extension, the http server (apache) won't execute it and will not treat it as a php file. So the EXTENSION MUST NOT EVER BE PRESENT!
			} while(file_exists($folder_path . $file_path));
			
			return $file_path;
		}
		
		public static function createAttachmentFileFolder($file_path) {
			$path = dirname($file_path);
			return is_dir($path) ? true : mkdir($path, 0755, true);
		}
		
		public static function moveUploadedFile($src_path, $dst_path, $is_local_file = false) {
			return $is_local_file ? rename($src_path, $dst_path) : move_uploaded_file($src_path, $dst_path);
		}
		
		public static function isUploadedFileAllowed($file, $is_local_file = false) {
			return $file["tmp_name"] && file_exists($file["tmp_name"]) && empty($file["error"]) && self::isFileMimeTypeAllowed($file["tmp_name"]) && self::isFileExtensionAllowed($file["name"]) && ($is_local_file || is_uploaded_file($file["tmp_name"]));
		}
		
		public static function isFileMimeTypeAllowed($file_path) {
			$status = true;
			
			$mime_type = MimeTypeHandler::getFileMimeType($file_path);
			
			if ($mime_type) {
				$allowed_mime_types = AttachmentSettings::getConstantVariable("ALLOWED_MIME_TYPES");
				$denied_mime_types = AttachmentSettings::getConstantVariable("DENIED_MIME_TYPES");
				
				if ($allowed_mime_types && preg_match('/\w+/', $allowed_mime_types))
					$status = strpos($allowed_mime_types, "$mime_type;");
				
				if ($status && $denied_mime_types && preg_match('/\w+/', $denied_mime_types))
					$status = strpos($denied_mime_types, "$mime_type;") === false;
			}
			
			return $status;
		}
		
		public static function isFileExtensionAllowed($file_path) {
			$status = true;
			
			$extension = pathinfo($file_path, PATHINFO_EXTENSION);
			
			if ($extension) {
				$allowed_extensions = AttachmentSettings::getConstantVariable("ALLOWED_EXTENSIONS");
				$denied_extensions = AttachmentSettings::getConstantVariable("DENIED_EXTENSIONS");
				
				if ($allowed_extensions && preg_match('/\w+/', $allowed_extensions))
					$status = strpos($allowed_extensions, "$extension;");
				
				if ($status && $denied_extensions && preg_match('/\w+/', $denied_extensions))
					$status = strpos($denied_extensions, "$extension;") === false;
			}
			
			return $status;
		}
		
		/* ATTACHMENT FUNCTIONS */
	
		public static function insertAttachment($brokers, $data) {
			if (is_array($brokers)) {
				$data["created_date"] = date("Y-m-d H:i:s");
				$data["modified_date"] = $data["created_date"];
				$data["size"] = is_numeric($data["size"]) ? $data["size"] : 0;
				
				$status = $data["path"] ? self::isFileExtensionAllowed($data["path"]) : true;
				
				if ($status) {
					foreach ($brokers as $broker) {
						if (is_a($broker, "IBusinessLogicBrokerClient")) {
							return $broker->callBusinessLogic("module/attachment", "AttachmentService.insertAttachment", $data);
						}
						else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
							$data["name"] = addcslashes($data["name"], "\\'");
							$data["type"] = addcslashes($data["type"], "\\'");
							$data["path"] = addcslashes($data["path"], "\\'");
					
							$status = $broker->callInsert("module/attachment", "insert_attachment", $data);
							return $status ? $broker->getInsertedId() : $status;
						}
						else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
							$Attachment = $broker->callObject("module/attachment", "Attachment");
							$status = $Attachment->insert($data, $ids);
							return $status ? $ids["attachment_id"] : $status;
						}
						else if (is_a($broker, "IDBBrokerClient")) {
							$status = $broker->insertObject("mat_attachment", array(
								"name" => $data["name"], 
								"type" => $data["type"], 
								"size" => $data["size"], 
								"created_date" => $data["created_date"], 
								"modified_date" => $data["modified_date"]
							));
							return $status ? $broker->getInsertedId() : $status;
						}
					}
				}
			}
		}
	
		public static function updateAttachment($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["attachment_id"])) {
				$data["modified_date"] = date("Y-m-d H:i:s");
			
				$status = $data["path"] ? self::isFileExtensionAllowed($data["path"]) : true;
				
				if ($status) {
					foreach ($brokers as $broker) {
						if (is_a($broker, "IBusinessLogicBrokerClient")) {
							return $broker->callBusinessLogic("module/attachment", "AttachmentService.updateAttachment", $data);
						}
						else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
							$data["name"] = addcslashes($data["name"], "\\'");
							$data["type"] = addcslashes($data["type"], "\\'");
							$data["size"] = is_numeric($data["size"]) ? $data["size"] : 0;
							$data["path"] = addcslashes($data["path"], "\\'");
					
							return $broker->callUpdate("module/attachment", "update_attachment", $data);
						}
						else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
							$Attachment = $broker->callObject("module/attachment", "Attachment");
							return $Attachment->update($data);
						}
						else if (is_a($broker, "IDBBrokerClient")) {
							return $broker->updateObject("mat_attachment", array(
								"name" => $data["name"], 
								"type" => $data["type"], 
								"size" => $data["size"], 
								"modified_date" => $data["modified_date"]
							), array(
								"attachment_id" => $data["attachment_id"]
							));
						}
					}
				}
			}
		}
	
		public static function updateAttachmentName($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["attachment_id"])) {
				$data["modified_date"] = date("Y-m-d H:i:s");
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/attachment", "AttachmentService.updateAttachmentName", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["name"] = addcslashes($data["name"], "\\'");
					
						return $broker->callUpdate("module/attachment", "update_attachment_name", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Attachment = $broker->callObject("module/attachment", "Attachment");
						return $Attachment->update($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->updateObject("mat_attachment", array(
							"name" => $data["name"], 
							"modified_date" => $data["modified_date"]
						), array(
							"attachment_id" => $data["attachment_id"]
						));
					}
				}
			}
		}
	
		public static function deleteAttachment($brokers, $attachment_id) {
			if (is_array($brokers) && is_numeric($attachment_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/attachment", "AttachmentService.deleteAttachment", array("attachment_id" => $attachment_id));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/attachment", "delete_attachment", array("attachment_id" => $attachment_id));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Attachment = $broker->callObject("module/attachment", "Attachment");
						return $Attachment->delete($attachment_id);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mat_attachment", array("attachment_id" => $attachment_id));
					}
				}
			}
		}
	
		public static function getAllAttachments($brokers, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => $options);
						return $broker->callBusinessLogic("module/attachment", "AttachmentService.getAllAttachments", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/attachment", "get_all_attachments", null, $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Attachment = $broker->callObject("module/attachment", "Attachment");
						return $Attachment->find(null, $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mat_attachment", null, null, $options);
					}
				}
			}
		}
	
		public static function countAllAttachments($brokers, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/attachment", "AttachmentService.countAllAttachments", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/attachment", "count_all_attachments", null, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Attachment = $broker->callObject("module/attachment", "Attachment");
						return $Attachment->count(null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mat_attachment", null, array("no_cache" => $no_cache));
					}
				}
			}
		}
	
		public static function getAttachmentsByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/attachment", "AttachmentService.getAttachmentsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						return $broker->callSelect("module/attachment", "get_attachments_by_conditions", array("conditions" => $cond), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Attachment = $broker->callObject("module/attachment", "Attachment");
						return $Attachment->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$options["conditions_join"] = $conditions_join;
						return $broker->findObjects("mat_attachment", null, $conditions, $options);
					}
				}
			}
		}
	
		public static function countAttachmentsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/attachment", "AttachmentService.countAttachmentsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/attachment", "count_attachments_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Attachment = $broker->callObject("module/attachment", "Attachment");
						return $Attachment->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$options["conditions_join"] = $conditions_join;
						return $broker->countObjects("mat_attachment", $conditions, array("no_cache" => $no_cache));
					}
				}
			}
		}
	
		public static function getAttachmentsByIds($brokers, $attachment_ids, $no_cache = false) {
			if (is_array($brokers) && $attachment_ids) {
				$attachment_ids_str = "";//just in case the user tries to hack the sql query. By default all attachment_id should be numeric.
				$attachment_ids = is_array($attachment_ids) ? $attachment_ids : array($attachment_ids);
				foreach ($attachment_ids as $attachment_id)
					$attachment_ids_str .= ($attachment_ids_str ? ", " : "") . "'" . addcslashes($attachment_id, "\\'") . "'";
				
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/attachment", "AttachmentService.getAttachmentsByIds", array("attachment_ids" => $attachment_ids, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/attachment", "get_attachments_by_ids", array("attachment_ids" => $attachment_ids_str), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Attachment = $broker->callObject("module/attachment", "Attachment");
						$conditions = array("attachment_id" => array("operator" => "in", "value" => $attachment_ids));
						return $Attachment->find(array("conditions" => $conditions), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mat_attachment", null, array("attachment_id" => array("operator" => "in", "value" => $attachment_ids)), array("no_cache" => $no_cache));
					}
				}
			}
		}
	
		public static function getAttachmentsByObject($brokers, $object_type_id, $object_id, $no_cache = false) {
			if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/attachment", "AttachmentService.getAttachmentsByObject", array("object_type_id" => $object_type_id, "object_id" => $object_id, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/attachment", "get_attachments_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$Attachment = $broker->callObject("module/attachment", "Attachment");
						return $Attachment->callSelect("get_attachments_by_object", array("object_type_id" => $object_type_id, "object_id" => $object_id), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$sql = AttachmentDBDAOUtil::get_attachments_by_object(array("object_type_id" => $object_type_id, "object_id" => $object_id));
				
						return $broker->getSQL($sql, array("no_cache" => $no_cache));
					}
				}
			}
		}
	
		public static function getAttachmentsByObjects($brokers, $object_type_id, $object_ids, $no_cache = false) {
			if (is_array($brokers) && is_numeric($object_type_id) && $object_ids) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/attachment", "AttachmentService.getAttachmentsByObjects", array("object_type_id" => $object_type_id, "object_ids" => $object_ids, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else {
						$object_ids_str = "";//just in case the user tries to hack the sql query. By default all object_id should be numeric.
						$object_ids = is_array($object_ids) ? $object_ids : array($object_ids);
						foreach ($object_ids as $object_id)
							$object_ids_str .= ($object_ids_str ? ", " : "") . "'" . addcslashes($object_id, "\\'") . "'";
						
						if (is_a($broker, "IIbatisDataAccessBrokerClient"))
							return $broker->callSelect("module/attachment", "get_attachments_by_objects", array("object_type_id" => $object_type_id, "object_ids" => $object_ids_str), array("no_cache" => $no_cache));
						else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
							$Attachment = $broker->callObject("module/attachment", "Attachment");
							return $Attachment->callSelect("get_attachments_by_objects", array("object_type_id" => $object_type_id, "object_ids" => $object_ids_str), array("no_cache" => $no_cache));
						}
						else if (is_a($broker, "IDBBrokerClient")) {
							$sql = AttachmentDBDAOUtil::get_attachments_by_objects(array("object_type_id" => $object_type_id, "object_ids" => $object_ids_str));
					
							return $broker->getSQL($sql, array("no_cache" => $no_cache));
						}
					}
				}
			}
		}
	
		public static function getAttachmentsByObjectGroup($brokers, $object_type_id, $object_id, $group = null, $options = array(), $no_cache = false) {
			if (is_array($brokers) && is_numeric($object_type_id) && is_numeric($object_id)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$group = is_numeric($group) ? $group : null;
						
						return $broker->callBusinessLogic("module/attachment", "AttachmentService.getAttachmentsByObjectGroup", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group, "options" => $options), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						
						return $broker->callSelect("module/attachment", "get_attachments_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						
						$Attachment = $broker->callObject("module/attachment", "Attachment");
						return $Attachment->callSelect("get_attachments_by_object_group", array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$group = is_numeric($group) ? $group : 0;
						$sql = AttachmentDBDAOUtil::get_attachments_by_object_group(array("object_type_id" => $object_type_id, "object_id" => $object_id, "group" => $group));
				
						return $broker->getSQL($sql, $options);
					}
				}
			}
		}
	
		public static function getAttachmentsByObjectsGroup($brokers, $object_type_id, $object_ids, $groups = null, $options = array(), $no_cache = false) {
			if (is_array($brokers) && is_numeric($object_type_id) && $object_ids) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$groups = !empty($groups) || is_numeric($groups) ? $groups : null;
						
						return $broker->callBusinessLogic("module/attachment", "AttachmentService.getAttachmentsByObjectsGroup", array("object_type_id" => $object_type_id, "object_ids" => $object_ids, "groups" => $groups, "options" => $options), array("no_cache" => $no_cache));
					}
					else {
						$object_ids_str = "";//just in case the user tries to hack the sql query. By default all object_id should be numeric.
						$object_ids = is_array($object_ids) ? $object_ids : array($object_ids);
						foreach ($object_ids as $object_id)
							$object_ids_str .= ($object_ids_str ? ", " : "") . "'" . addcslashes($object_id, "\\'") . "'";
				
						$groups_str = "";//just in case the user tries to hack the sql query. By default all groups should be numeric.
						$groups = !empty($groups) ? $groups : 0;
						$groups = is_array($groups) ? $groups : array($groups);
						foreach ($groups as $group)
							$groups_str .= ($groups_str ? ", " : "") . "'" . addcslashes($group, "\\'") . "'";
						
						if (is_a($broker, "IIbatisDataAccessBrokerClient"))
							return $broker->callSelect("module/attachment", "get_attachments_by_objects_group", array("object_type_id" => $object_type_id, "object_ids" => $object_ids_str, "groups" => $groups_str), $options);
						else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
							$Attachment = $broker->callObject("module/attachment", "Attachment");
							return $Attachment->callSelect("get_attachments_by_objects_group", array("object_type_id" => $object_type_id, "object_ids" => $object_ids_str, "groups" => $groups_str), $options);
						}
						else if (is_a($broker, "IDBBrokerClient")) {
							$sql = AttachmentDBDAOUtil::get_attachments_by_objects_group(array("object_type_id" => $object_type_id, "object_ids" => $object_ids_str, "groups" => $groups_str));
					
							return $broker->getSQL($sql, $options);
						}
					}
				}
			}
		}
	
		/* OBJECT ATTACHMENT FUNCTIONS */
	
		public static function insertObjectAttachment($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["attachment_id"]) && is_numeric($data["object_type_id"]) && is_numeric($data["object_id"])) {
				$data["created_date"] = date("Y-m-d H:i:s");
				$data["modified_date"] = $data["created_date"];
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						
						return $broker->callBusinessLogic("module/attachment", "ObjectAttachmentService.insertObjectAttachment", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : 0;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : 0;
						
						return $broker->callInsert("module/attachment", "insert_object_attachment", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
						
						$ObjectAttachment = $broker->callObject("module/attachment", "ObjectAttachment");
						return $ObjectAttachment->insert($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
						
						return $broker->insertObject("mat_object_attachment", array(
								"attachment_id" => $data["attachment_id"], 
								"object_type_id" => $data["object_type_id"], 
								"object_id" => $data["object_id"], 
								"group" => $data["group"], 
								"order" => $data["order"], 
								"created_date" => $data["created_date"], 
								"modified_date" => $data["modified_date"]
							));
					}
				}
			}
		}
	
		public static function updateObjectAttachment($brokers, $data) {
			if (is_array($brokers) && is_numeric($data["new_attachment_id"]) && is_numeric($data["new_object_type_id"]) && is_numeric($data["new_object_id"]) && is_numeric($data["old_attachment_id"]) && is_numeric($data["old_object_type_id"]) && is_numeric($data["old_object_id"])) {
				$data["modified_date"] = date("Y-m-d H:i:s");
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						
						return $broker->callBusinessLogic("module/attachment", "ObjectAttachmentService.updateObjectAttachment", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : 0;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : 0;
					
						return $broker->callUpdate("module/attachment", "update_object_attachment", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
						
						$ObjectAttachment = $broker->callObject("module/attachment", "ObjectAttachment");
						return $ObjectAttachment->updatePrimaryKeys($data);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$data["group"] = is_numeric($data["group"]) ? $data["group"] : null;
						$data["order"] = is_numeric($data["order"]) ? $data["order"] : null;
						
						return $broker->updateObject("mat_object_attachment", array(
								"attachment_id" => $data["new_attachment_id"], 
								"object_type_id" => $data["new_object_type_id"], 
								"object_id" => $data["new_object_id"], 
								"group" => $data["group"], 
								"order" => $data["order"], 
								"modified_date" => $data["modified_date"]
							), array(
								"attachment_id" => $data["old_attachment_id"], 
								"object_type_id" => $data["old_object_type_id"], 
								"object_id" => $data["old_object_id"], 
							));
					}
				}
			}
		}
	
		public static function deleteObjectAttachment($brokers, $attachment_id, $object_type_id, $object_id) {
			if (is_array($brokers) && is_numeric($attachment_id) && is_numeric($object_type_id) && is_numeric($object_id)) {
				$data = array("attachment_id" => $attachment_id, "object_type_id" => $object_type_id, "object_id" => $object_id);
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/attachment", "ObjectAttachmentService.deleteObjectAttachment", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/attachment", "delete_object_attachment", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectAttachment = $broker->callObject("module/attachment", "ObjectAttachment");
						return $ObjectAttachment->deleteByConditions(array("conditions" => $data));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mat_object_attachment", $data);
					}
				}
			}
		}
	
		public static function deleteObjectAttachmentsByAttachmentId($brokers, $attachment_id) {
			if (is_array($brokers) && is_numeric($attachment_id)) {
				$data = array("attachment_id" => $attachment_id);
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/attachment", "ObjectAttachmentService.deleteObjectAttachmentsByAttachmentId", $data);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/attachment", "delete_object_attachments_by_attachment_id", $data);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectAttachment = $broker->callObject("module/attachment", "ObjectAttachment");
						return $ObjectAttachment->deleteByConditions(array("conditions" => $data));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mat_object_attachment", $data);
					}
				}
			}
		}
	
		public static function deleteObjectAttachmentsByConditions($brokers, $conditions, $conditions_join) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/attachment", "ObjectAttachmentService.deleteObjectAttachmentsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						return $broker->callDelete("module/attachment", "delete_object_attachments_by_conditions", array("conditions" => $cond));
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectAttachment = $broker->callObject("module/attachment", "ObjectAttachment");
						return $ObjectAttachment->deleteByConditions(array("conditions" => $conditions, "conditions_join" => $conditions_join));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->deleteObject("mat_object_attachment", $conditions, array("conditions_join" => $conditions_join));
					}
				}
			}
		}
	
		public static function deleteCorruptedObjectAttachments($brokers) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/attachment", "ObjectAttachmentService.deleteCorruptedObjectAttachments");
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callDelete("module/attachment", "delete_corrupted_object_attachments");
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectAttachment = $broker->callObject("module/attachment", "ObjectAttachment");
						return $ObjectAttachment->callDelete("module/attachment", "delete_corrupted_object_attachments");
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$sql = ObjectAttachmentDBDAOUtil::delete_corrupted_object_attachments();
						return $broker->setSQL($sql);
					}
				}
			}
		}
	
		//$conditions must be an array containing multiple conditions
		public static function getObjectAttachmentsByConditions($brokers, $conditions, $conditions_join, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/attachment", "ObjectAttachmentService.getObjectAttachmentsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						return $broker->callSelect("module/attachment", "get_object_attachments_by_conditions", array("conditions" => $cond), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectAttachment = $broker->callObject("module/attachment", "ObjectAttachment");
						return $ObjectAttachment->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						$options["conditions_join"] = $conditions_join;
						return $broker->findObjects("mat_object_attachment", null, $conditions, $options);
					}
				}
			}
		}
	
		//$conditions must be an array containing multiple conditions
		public static function countObjectAttachmentsByConditions($brokers, $conditions, $conditions_join, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						return $broker->callBusinessLogic("module/attachment", "ObjectAttachmentService.countObjectAttachmentsByConditions", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => array("no_cache" => $no_cache)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$cond = DB::getSQLConditions($conditions, $conditions_join);
						$cond = $cond ? $cond : "1=1";
						$result = $broker->callSelect("module/attachment", "count_object_attachments_by_conditions", array("conditions" => $cond), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectAttachment = $broker->callObject("module/attachment", "ObjectAttachment");
						return $ObjectAttachment->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mat_object_attachment", $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
					}
				}
			}
		}
	
		public static function getAllObjectAttachments($brokers, $options = array(), $no_cache = false) {
			if (is_array($brokers)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => $options);
						return $broker->callBusinessLogic("module/attachment", "ObjectAttachmentService.getAllObjectAttachments", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/attachment", "get_all_object_attachments", null, $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectAttachment = $broker->callObject("module/attachment", "ObjectAttachment");
						return $ObjectAttachment->find(null, $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mat_object_attachment", null, null, $options);
					}
				}
			}
		}
	
		public static function countAllObjectAttachments($brokers, $no_cache = false) {
			if (is_array($brokers)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/attachment", "ObjectAttachmentService.countAllObjectAttachments", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/attachment", "count_all_object_attachments", null, array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectAttachment = $broker->callObject("module/attachment", "ObjectAttachment");
						return $ObjectAttachment->count(null, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mat_object_attachment", null, array("no_cache" => $no_cache));
					}
				}
			}
		}
	
		public static function getObjectAttachmentsByAttachmentId($brokers, $attachment_id, $options = array(), $no_cache = false) {
			if (is_array($brokers) && is_numeric($attachment_id)) {
				$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
			
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("attachment_id" => $attachment_id, "options" => $options);
						return $broker->callBusinessLogic("module/attachment", "ObjectAttachmentService.getObjectAttachmentsByAttachmentId", $data, $options);
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						return $broker->callSelect("module/attachment", "get_object_attachments_by_attachment_id", array("attachment_id" => $attachment_id), $options);
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectAttachment = $broker->callObject("module/attachment", "ObjectAttachment");
						return $ObjectAttachment->find(array("conditions" => array("attachment_id" => $attachment_id)), $options);
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->findObjects("mat_object_attachment", null, array("attachment_id" => $attachment_id), $options);
					}
				}
			}
		}
	
		public static function countObjectAttachmentsByAttachmentId($brokers, $attachment_id, $no_cache = false) {
			if (is_array($brokers) && is_numeric($attachment_id)) {
				foreach ($brokers as $broker) {
					if (is_a($broker, "IBusinessLogicBrokerClient")) {
						$data = array("attachment_id" => $attachment_id, "options" => array("no_cache" => $no_cache));
						return $broker->callBusinessLogic("module/attachment", "ObjectAttachmentService.countObjectAttachmentsByAttachmentId", $data, array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
						$result = $broker->callSelect("module/attachment", "count_object_attachments_by_attachment_id", array("attachment_id" => $attachment_id), array("no_cache" => $no_cache));
						return $result[0]["total"];
					}
					else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
						$ObjectAttachment = $broker->callObject("module/attachment", "ObjectAttachment");
						return $ObjectAttachment->count(array("conditions" => array("attachment_id" => $attachment_id)), array("no_cache" => $no_cache));
					}
					else if (is_a($broker, "IDBBrokerClient")) {
						return $broker->countObjects("mat_object_attachment", array("attachment_id" => $attachment_id), array("no_cache" => $no_cache));
					}
				}
			}
		}
	}
}
?>
