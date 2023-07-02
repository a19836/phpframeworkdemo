<?php
include_once get_lib("org.phpframework.util.web.html.HtmlDomHandler");

class CommonModuleUtil {
	
	/* ATTACHMENTS FUNCTIONS */
	public static function prepareObjectHtmlContent($EVC, &$html, $object_type_id, $object_id, $group, $attachment_id_regex = false, $upload_url = false, &$status = false) {
		include_once $EVC->getModulePath("attachment/AttachmentUtil", $EVC->getCommonProjectName());
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		//Get the attachments related with this object
		$attachments = AttachmentUtil::getAttachmentsByObjectGroup($brokers, $object_type_id, $object_id, $group);
		$attachment_ids = array();
		if ($attachments) 
			foreach ($attachments as $attachment)
				$attachment_ids[] = $attachment["attachment_id"];
		
		//Prepare the html content and get the attachment ids
		$result = self::prepareHtmlContent($EVC, $html, $attachment_ids, $attachment_id_regex, $upload_url, $status);
		$html_attachment_ids = $result[1];
		$attachment_ids_to_delete = $result[2];
		
		//Delete the attachments that were previously related with this object, but are not anymore.
		if ($attachment_ids_to_delete)
			foreach ($attachment_ids_to_delete as $attachment_id => $idx)
				if (!AttachmentUtil::deleteFile($EVC, $attachment_id))
					$status = false;
		
		//By default the upload from the wysiwyg editor, in the add_object page (like add_article, add_event, etc...), will add the attachment with the object_id=0, bc there is no object_id yet. This means that when we finally save the object and get the real object_id, we need to update the correspondent attachments with the right object_id. This is, when we add an article, there is no article_id, so the uploaded attachments will be related with a article_id=0. After the article be added and we hv an article_id, then we can relate the uploaded attachments with the new article_id.
		if ($html_attachment_ids)
			foreach ($html_attachment_ids as $attachment_id => $idx) {
				$data = array(
					"old_attachment_id" => $attachment_id,
					"old_object_type_id" => $object_type_id,
					"old_object_id" => 0,
					"new_attachment_id" => $attachment_id,
					"new_object_type_id" => $object_type_id,
					"new_object_id" => $object_id,
				);
				
				if (!AttachmentUtil::updateObjectAttachment($brokers, $data))
					$status = false;
			}
	}
	
	public static function prepareHtmlContent($EVC, &$html, &$attachment_ids = false, $attachment_id_regex = false, $upload_url = false, &$status = false) {
		if ($html) {
			include_once $EVC->getModulePath("attachment/AttachmentUtil", $EVC->getCommonProjectName());
			
			$brokers = $EVC->getPresentationLayer()->getBrokers();
			
			$HtmlDomHandler = new HtmlDomHandler($html);
			
			$html_attachment_ids = array();
			$attachment_ids_to_delete = $attachment_ids ? array_flip($attachment_ids) : null;
			
			if ($HtmlDomHandler->isHTML()) {
				/*
				 * Get images from html and for each image:
				 * - if is an inline image with the base64 data, upload file according with upload_url
				 * - if image contains url in src, get attachment_id and unset from $attachment_ids_to_delete
				 * Then remove old attachments, which means all ids inside of $attachment_ids_to_delete.
				*/
				$DOMDocument = $HtmlDomHandler->getDOMDocument();
				$imgs = $DOMDocument->getElementsByTagName('img');
				
				if ($imgs) {
					foreach ($imgs as $img) {
						$is_inline = $HtmlDomHandler->isInlineImage($img);
					
						if ($upload_url && $is_inline) {
							$img_data = $HtmlDomHandler->getInlineImageBase64Data($img);
			
							//Only upload inline image if image is bigger than 1000 chars. Otherwise it means the image is an icon or a very small image that can be inline, because slower if the browser needs to make a new request only for this icon.
							if (strlen($img_data) > 1000) {
								//Uploading attachment.
								$attachment_url = self::uploadInlineImage($HtmlDomHandler, $img, $upload_url);
							
								//sets image src = attachment_url, even if the $attachment_url is empty.
								$img->setAttribute("src", $attachment_url);
							
								if (!$attachment_url)
									$status = false;
							}
						}
						
						if ($attachment_id_regex) {
							$src = $img->getAttribute("src");
							
							if ($src) {
								preg_match($attachment_id_regex, $src, $matches, PREG_OFFSET_CAPTURE);
								$attachment_id = $matches[1][0];
								
								if (is_numeric($attachment_id)) {
									unset($attachment_ids_to_delete[$attachment_id]);
									
									$html_attachment_ids[$attachment_id] = true;
								}
							}
						}
					}
				
					$html = $HtmlDomHandler->getHtmlExact();
				}
			}
		}
		
		return array($html, $html_attachment_ids, $attachment_ids_to_delete);
	}
	
	//upload base64 image through curl
	private static function uploadInlineImage($HtmlDomHandler, $img, $upload_url) {
		if ($upload_url) {
			$content_type = $HtmlDomHandler->getInlineImageContentType($img);
			$content_type = $content_type ? $content_type : MimeTypeHandler::getFileMimeType($file_path);
			
			$file_name = $img->getAttribute("data-filename");
			$extension = $content_type && stripos($content_type, "image/") !== false ? strtolower(substr($content_type, strlen("image/"))) : false;
			
			if (!$extension && $file_name)
				$extension = pathinfo($file_name, PATHINFO_EXTENSION);
			
			$file_path = tempnam(sys_get_temp_dir(), 'inline_image_') . ($extension ? ".$extension" : "");
			
			if ($HtmlDomHandler->saveInlineImageToFile($img, $file_path)) {
				$file_name = $file_name ? $file_name : basename($file_path);
				
				// Create a CURLFile object / procedural method 
				//$cfile = curl_file_create($file_path, $content_type, $file_name); // try adding 

				// Create a CURLFile object / oop method 
				$cfile = new CURLFile($file_path, $content_type, $file_name); // uncomment and use if the upper procedural method is not working.

				// Assign POST data
				$post = array('image' => $cfile, 'upload' => 1);//image for $_FILES and upload for $_POST
		
				// Assign cookies
				$cookies = $_COOKIE ? http_build_query($_COOKIE, '', '; ') : '';

				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $upload_url);
				curl_setopt($curl, CURLOPT_USERAGENT, 'Opera/9.80 (Windows NT 6.2; Win64; x64) Presto/2.12.388 Version/12.15');
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('User-Agent: Opera/9.80 (Windows NT 6.2; Win64; x64) Presto/2.12.388 Version/12.15', 'Content-Type: multipart/form-data'));
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // stop verifying certificate
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
				curl_setopt($curl, CURLOPT_POST, true); // enable posting
				curl_setopt($curl, CURLOPT_POSTFIELDS, $post); // post images 
				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // if any redirection after upload
				curl_setopt($curl, CURLOPT_COOKIE, $cookies); // set cookies
				/*$err = curl_errno($curl);
				$errmsg = curl_error($curl);
				$header = curl_getinfo($curl);*/
				$attachment_url = curl_exec($curl); 
				curl_close($curl);
		
				//print_r($header);
				//echo "$err:$errmsg<br><textarea>:$attachment_url</textarea><br>";
		
				if ($attachment_url) {
					$attachment_url = json_decode($attachment_url, true);
					$attachment_url = $attachment_url ? $attachment_url["url"] : null;
				}
			}

			if (file_exists($file_path))
				unlink($file_path);
			
			return $attachment_url;
		}
	}
	
	/* OPTIONS AND AVAILABLE ITEMS FUNCTIONS */
	public static function prepareObjectTypeIdListSettingsField($EVC, &$settings, $field_name = "object_type_id") {
		include_once $EVC->getModulePath("object/ObjectUtil", $EVC->getCommonProjectName());
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$type = $settings["fields"][$field_name]["field"]["input"]["type"];
		$allow_options = $type == "select" || $type == "radio" || $type == "checkbox";
		
		$object_types = \ObjectUtil::getAllObjectTypes($brokers);
		$object_type_options = array( array("value" => "", "label" => "") ); //ad default empty option
		$available_object_types = array();
		$existent_ids = array();
		
		if ($object_types)
			foreach ($object_types as $object_type) {
				if ($allow_options)
					$object_type_options[] = array("value" => $object_type["object_type_id"], "label" => /*$object_type["object_type_id"] . ": " . */$object_type["name"]);
				else 
					$available_object_types[ $object_type["object_type_id"] ] = /*$object_type["object_type_id"] . ": " . */$object_type["name"];
				
				$existent_ids[] = $object_type["object_type_id"];
			}
		
		if ($allow_options && $settings["data"])
			foreach ($settings["data"] as $item)
				if (is_numeric($item["object_type_id"]) && !in_array($item["object_type_id"], $existent_ids)) {
					$object_type_options[] = array("value" => $item["object_type_id"], "label" => $item["object_type_id"]);
					$existent_ids[] = $item["object_type_id"];
				}
		
		$settings["fields"][$field_name]["field"]["input"]["options"] = $object_type_options;
		$settings["fields"][$field_name]["field"]["input"]["available_values"] = $available_object_types;
	}
	
	public static function prepareObjectTypeIdFormSettingsField($EVC, &$settings, $is_editable, $field_name = "object_type_id") {
		include_once $EVC->getModulePath("object/ObjectUtil", $EVC->getCommonProjectName());
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$object_types = \ObjectUtil::getAllObjectTypes($brokers);
		$object_type_options = array( array("value" => "", "label" => "") ); //ad default empty option
		$available_object_types = array();
			
		$default_id = $settings["form_data"] ? $settings["form_data"]["object_type_id"] : null;
		$exists = false;
		
		if ($object_types)
			foreach ($object_types as $object_type) {
				if ($is_editable) 
					$object_type_options[] = array("value" => $object_type["object_type_id"], "label" => /*$object_type["object_type_id"] . ": " . */$object_type["name"]);
				else
					$available_object_types[ $object_type["object_type_id"] ] = /*$object_type["object_type_id"] . ": " . */$object_type["name"];
				
				if (is_numeric($default_id) && $object_type["object_type_id"] == $default_id)
					$exists = true;
			}
		
		if ($is_editable && is_numeric($default_id) && !$exists)
			$object_type_options[] = array("value" => $default_id, "label" => $default_id);
		
		$settings["fields"][$field_name]["field"]["input"]["type"] = $is_editable ? "select" : "label";
		$settings["fields"][$field_name]["field"]["input"]["options"] = $object_type_options;
		$settings["fields"][$field_name]["field"]["input"]["available_values"] = $available_object_types;
	}
	
	//field_name could be user_id or to_user_id or from_user_id like in the messages module
	public static function prepareUserIdListSettingsField($EVC, &$settings, $field_name = "user_id") {
		include_once $EVC->getModulePath("user/UserUtil", $EVC->getCommonProjectName());
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$type = $settings["fields"][$field_name]["field"]["input"]["type"];
		$allow_options = $type == "select" || $type == "radio" || $type == "checkbox";
		
		if ($allow_options) {
			$users_count = \UserUtil::countAllUsers($brokers);
			
			if ($users_count < \UserUtil::getConstantVariable("MAXIMUM_USERS_RECORDS_IN_COMBO_BOX")) {
				$users = \UserUtil::getAllUsers($brokers);
				$user_options = array( array("value" => "", "label" => "") ); //ad default empty option
				$existent_ids = array();
				
				if ($users) 
					foreach ($users as $user) {
						$user_options[] = array("value" => $user["user_id"], "label" => /*$user["user_id"] . ": " . */$user["username"] . " - " . $user["name"]);
						$existent_ids[] = $user["user_id"];
					}
				
				if ($settings["data"])
					foreach ($settings["data"] as $item)
						if (is_numeric($item[$field_name]) && !in_array($item[$field_name], $existent_ids)) {
							$user_options[] = array("value" => $item["user_id"], "label" => $item["user_id"]);
							$existent_ids[] = $item["user_id"];
						}
				
				$settings["fields"][$field_name]["field"]["input"]["options"] = $user_options;
			}
			else
				$settings["fields"][$field_name]["field"]["input"]["type"] = "text";
		}
		else if ($settings["data"]) {
			$user_ids = array();
			foreach ($settings["data"] as $item)
				if (is_numeric($item[$field_name]))
					$user_ids[] = $item[$field_name];
			
			if ($user_ids) {
				$user_ids = array_unique($user_ids);
				$users = \UserUtil::getUsersByConditions($brokers, array("user_id" => array("value" => $user_ids, "operator" => "in")), null);
				$available_users = array();
				
				if ($users) 
					foreach ($users as $user)
						$available_users[ $user["user_id"] ] = /*$user["user_id"] . ": " . */$user["username"] . " - " . $user["name"];
				
				$settings["fields"][$field_name]["field"]["input"]["available_values"] = $available_users;
			}
		}
	}
	
	//field_name could be user_id or to_user_id or from_user_id like in the messages module
	public static function prepareUserIdFormSettingsField($EVC, &$settings, $is_editable, $field_name = "user_id") {
		include_once $EVC->getModulePath("user/UserUtil", $EVC->getCommonProjectName());
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		if ($is_editable) {
			$users_count = \UserUtil::countAllUsers($brokers);
			
			if ($users_count < \UserUtil::getConstantVariable("MAXIMUM_USERS_RECORDS_IN_COMBO_BOX")) {
				$users = \UserUtil::getAllUsers($brokers);
				$user_options = array( array("value" => "", "label" => "") ); //ad default empty option
				
				$default_id = $settings["form_data"] ? $settings["form_data"]["user_id"] : null;
				$exists = false;
				
				if ($users) 
					foreach ($users as $user) {
						$user_options[] = array("value" => $user["user_id"], "label" => /*$user["user_id"] . ": " . */$user["username"] . " - " . $user["name"]);
						
						if (is_numeric($default_id) && $user["user_id"] == $default_id)
							$exists = true;
					}
				
				if ($is_editable && is_numeric($default_id) && !$exists)
					$user_options[] = array("value" => $default_id, "label" => $default_id);
				
				$settings["fields"][$field_name]["field"]["input"]["type"] = "select";
				$settings["fields"][$field_name]["field"]["input"]["options"] = $user_options;
			}
			else
				$settings["fields"][$field_name]["field"]["input"]["type"] = "text";
		}
		else {
			$settings["fields"][$field_name]["field"]["input"]["type"] = "label";
			$default_id = $settings["form_data"] ? $settings["form_data"][$field_name] : null;
			
			if (is_numeric($default_id)) {
				$users = \UserUtil::getUsersByConditions($brokers, array("user_id" => $default_id), null);
				$available_users = array();
				$available_users[ $default_id ] = /*$users[0]["user_id"] . ": " . */$users[0]["username"] . " - " . $users[0]["name"];
				$settings["fields"][$field_name]["field"]["input"]["available_values"] = $available_users;
			}
		}
	}
	
	public static function prepareUsernameListSettingsField($EVC, &$settings, $field_name = "username") {
		include_once $EVC->getModulePath("user/UserUtil", $EVC->getCommonProjectName());
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$type = $settings["fields"][$field_name]["field"]["input"]["type"];
		$allow_options = $type == "select" || $type == "radio" || $type == "checkbox";
		
		$users = \UserUtil::getAllUsers($brokers);
		$user_options = array( array("value" => "", "label" => "") ); //ad default empty option
		$available_users = array();
		$existent_ids = array();
		
		if ($users)
			foreach ($users as $user) {
				if ($allow_options)
					$user_options[] = array("value" => $user["user_id"], "label" => /*$user["user_id"] . ": " . */$user["username"] . " - " . $user["name"]);
				else 
					$available_users[ $user["user_id"] ] = /*$user["user_id"] . ": " . */$user["username"] . " - " . $user["name"];
				
				$existent_ids[] = $user["user_id"];
			}
		
		if ($allow_options && $settings["data"])
			foreach ($settings["data"] as $item)
				if (is_numeric($item["user_id"]) && !in_array($item["user_id"], $existent_ids)) {
					$user_options[] = array("value" => $item["user_id"], "label" => $item["user_id"]);
					$existent_ids[] = $item["user_id"];
				}
		
		$settings["fields"][$field_name]["field"]["input"]["options"] = $user_options;
		$settings["fields"][$field_name]["field"]["input"]["available_values"] = $available_users;
	}
}
?>
