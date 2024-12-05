<?php
namespace CMSModule\article\edit_article;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("article/ArticleUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleTableExtraAttributesUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$CommonModuleTableExtraAttributesUtil = new \CommonModuleTableExtraAttributesUtil($this, isset($GLOBALS["default_db_driver"]) ? $GLOBALS["default_db_driver"] : null, $settings, "article");
		
		//Getting Article Details
		$article_id = isset($_GET["article_id"]) ? $_GET["article_id"] : null;
		$data = \ArticleUtil::getArticleProperties($EVC, $article_id, true);
		$photo_url = isset($data["photo_url"]) ? $data["photo_url"] : null;
		
		//Getting Article Extra Details
		if ($data) {
			$data["tags"] = isset($data["tags"]) ? array_values(\TagUtil::convertTagsStringToArray($data["tags"])) : array();
			
			$data_extra = $CommonModuleTableExtraAttributesUtil->getTableExtra(array("article_id" => $article_id), true);
			$data = $data_extra ? array_merge($data, $data_extra) : $data;
		}
		
		//Add Join Point
		$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Preparing article data", array(
			"EVC" => $EVC,
			"settings" => &$settings,
			"article_data" => &$data,
		), "Use this join point to change the loaded article data.");
		
		//Preparing Action
		if (!empty($_POST)) {
			
			if (!empty($_POST["delete"]) && !empty($settings["allow_deletion"])) {
				$data_article_id = isset($data["article_id"]) ? $data["article_id"] : null;
				
				$status = !$data || \ArticleUtil::deleteArticle($EVC, $data_article_id);
				
				if ($status && $data_article_id)
					$status = $CommonModuleTableExtraAttributesUtil->deleteTableExtra(array("article_id" => $data_article_id));
				
				if ($status) 
					//Add Join Point creating a new action of some kind
					$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull article deleting action", array(
						"EVC" => &$EVC,
						"article_id" => $data_article_id,
						"article_data" => &$data,
						"error_message" => &$error_message,
					));
			}
			else if (!empty($_POST["save"])) {
				$title = isset($_POST["title"]) ? $_POST["title"] : null;
				$sub_title = isset($_POST["sub_title"]) ? $_POST["sub_title"] : null;
				$published = isset($_POST["published"]) ? $_POST["published"] : null;
				$tags = isset($_POST["tags"]) ? $_POST["tags"] : null;
				$photo_id = isset($_POST["photo_id"]) ? $_POST["photo_id"] : null;
				$summary = isset($_POST["summary"]) ? $_POST["summary"] : null;
				$content = isset($_POST["content"]) ? $_POST["content"] : null;
				$allow_comments = isset($_POST["allow_comments"]) ? $_POST["allow_comments"] : null;
				
				$photo_id = $photo_id ? $photo_id : 0;
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("title" => $title, "sub_title" => $sub_title, "published" => $published, "tags" => $tags, "photo_id" => $photo_id, "summary" => $summary, "content" => $content, "allow_comments" => $allow_comments));
				
				if (!$empty_field_name)
					$empty_field_name = $CommonModuleTableExtraAttributesUtil->checkIfEmptyFields($settings, $_POST);
				
				if ($empty_field_name)
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				else {
					$new_data = $data;
					$new_data["title"] = !empty($settings["show_title"]) ? $title : (isset($new_data["title"]) ? $new_data["title"] : null);
					$new_data["sub_title"] = !empty($settings["show_sub_title"]) ? $sub_title : (isset($new_data["sub_title"]) ? $new_data["sub_title"] : null);
					$new_data["published"] = !empty($settings["show_published"]) ? $published : (isset($new_data["published"]) ? $new_data["published"] : null);
					$new_data["tags"] = !empty($settings["show_tags"]) ? $tags : (isset($new_data["tags"]) ? $new_data["tags"] : null);
					$new_data["photo_id"] = !empty($settings["show_photo_id"]) ? $photo_id : (isset($new_data["photo_id"]) ? $new_data["photo_id"] : null);
					$new_data["summary"] = !empty($settings["show_summary"]) ? $summary : (isset($new_data["summary"]) ? $new_data["summary"] : null);
					$new_data["content"] = !empty($settings["show_content"]) ? $content : (isset($new_data["content"]) ? $new_data["content"] : null);
					$new_data["allow_comments"] = !empty($settings["show_allow_comments"]) ? $allow_comments : (isset($new_data["allow_comments"]) ? $new_data["allow_comments"] : null);
					
					$CommonModuleTableExtraAttributesUtil->prepareFieldsWithNewData($settings, $new_data, $data, $_POST);
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					//check if $_FILES["photo"] is an image
					if (!empty($_FILES["photo"]) && !empty($_FILES["photo"]["tmp_name"])) {
						$mime_type = !empty($_FILES["photo"]["type"]) ? $_FILES["photo"]["type"] : \MimeTypeHandler::getFileMimeType($_FILES["photo"]["tmp_name"]);
						
						if (!\MimeTypeHandler::isImageMimeType($mime_type))
							$error_message = "Upload photo must be an image!";
					}
					
					if (!$error_message && \CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message) && $CommonModuleTableExtraAttributesUtil->areFileFieldsValid($EVC, $settings, $error_message)) {
						$new_data["object_articles"] = isset($settings["object_to_objects"]) ? $settings["object_to_objects"] : null;
						
						//save article
						if (!empty($settings["allow_insertion"]) && empty($data["article_id"])) {
							$status = \ArticleUtil::setArticleProperties($EVC, null, $new_data, isset($_FILES["photo"]) ? $_FILES["photo"] : null);
							if (isset($settings["on_insert_ok_action"]) && strpos($settings["on_insert_ok_action"], "_redirect") !== false)
								$settings["on_insert_ok_redirect_url"] .= (isset($settings["on_insert_ok_redirect_url"]) && strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "article_id=$status";
						}
						else if (!empty($settings["allow_update"]) && !empty($data["article_id"]))
							$status = \ArticleUtil::setArticleProperties($EVC, $data["article_id"], $new_data, isset($_FILES["photo"]) ? $_FILES["photo"] : null);
						
						if (!empty($status)) {
							$article_id = $status;
							
							if (!empty($_FILES["photo"])) {
								//Load again data because of the photo_url, but without changing the $data variable
								$db_data = \ArticleUtil::getArticleProperties($EVC, $article_id, true);
								$photo_id = $new_data["photo_id"] = isset($db_data["photo_id"]) ? $db_data["photo_id"] : null;
								$photo_url = $new_data["photo_url"] = isset($db_data["photo_url"]) ? $db_data["photo_url"] : null;
							}
							else
								$photo_url = $photo_id ? $photo_url : false;
							
							$status = \AttachmentUtil::saveObjectAttachments($EVC, \ObjectUtil::ARTICLE_OBJECT_TYPE_ID, $article_id, \ArticleUtil::ARTICLE_ATTACHMENTS_GROUP_ID, $error_message);
							
							if ($status) {
								//save article extra
								$new_extra_data = $new_data;
								$new_extra_data["article_id"] = $article_id;
								$status = $CommonModuleTableExtraAttributesUtil->insertOrUpdateTableExtra($new_extra_data);
								//$CommonModuleTableExtraAttributesUtil->reloadSavedTableExtra($settings, array("article_id" => $article_id), $data, $new_data, $_POST);
								
								if ($status) {
									//Prepare inline html images
									$data_content = isset($data["content"]) ? $data["content"] : null;
									$data_summary = isset($data["summary"]) ? $data["summary"] : null;
									
									if ($new_data["content"] != $data_content || $new_data["summary"] != $data_summary) {
										$this->prepareArticleHtmlAttributes($EVC, $settings, $article_id, $new_data, $status);
										$aux = $new_data;
										$aux["article_id"] = $article_id;
										
										if (!\ArticleUtil::insertOrUpdateArticle($brokers, $aux))
											$status = false;
										
										$summary = !empty($settings["show_summary"]) ? $new_data["summary"] : $summary;
										$content = !empty($settings["show_content"]) ? $new_data["content"] : $content;
									}
									
									if ($status) {
										//Add Join Point creating a new action of some kind
										$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull article saving action", array(
											"EVC" => &$EVC,
											"object_type_id" => \ObjectUtil::ARTICLE_OBJECT_TYPE_ID,
											"object_id" => &$article_id,
											"group_id" => \ArticleUtil::ARTICLE_ATTACHMENTS_GROUP_ID,
											"article_data" => &$new_data,
											"error_message" => &$error_message,
										));
									}
								}
							}
						}
					}
				}
			}
		}
		
		if (!empty($_POST["save"])) {
			$form_data = array(
				"article_id" => !empty($settings["show_article_id"]) ? $article_id : (isset($data["article_id"]) ? $data["article_id"] : null),
				"title" => !empty($settings["show_title"]) ? $title : (isset($data["title"]) ? $data["title"] : null),
				"sub_title" => !empty($settings["show_sub_title"]) ? $sub_title : (isset($data["sub_title"]) ? $data["sub_title"] : null),
				"published" => !empty($settings["show_published"]) ? $published : (isset($data["published"]) ? $data["published"] : null),
				"tags" => !empty($settings["show_tags"]) ? $tags : (isset($data["tags"]) ? $data["tags"] : null),
				"photo_id" => !empty($settings["show_photo_id"]) ? $photo_id : (isset($data["photo_id"]) ? $data["photo_id"] : null),
				"photo_url" => $photo_url,
				"summary" => !empty($settings["show_summary"]) ? $summary : (isset($data["summary"]) ? $data["summary"] : null),
				"content" => !empty($settings["show_content"]) ? $content : (isset($data["content"]) ? $data["content"] : null),
				"allow_comments" => !empty($settings["show_allow_comments"]) ? $allow_comments : (isset($data["allow_comments"]) ? $data["allow_comments"] : null),
			);
			
			$CommonModuleTableExtraAttributesUtil->prepareFieldsWithNewData($settings, $form_data, $data, $_POST);
			
			$form_data = !empty($new_data) ? array_merge($new_data, $form_data) : (!empty($settings["allow_view"]) && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else
			$form_data = !empty($settings["allow_view"]) && $data ? $data : array();
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/article/edit_article.css';
		$settings["js_file"] = $project_common_url_prefix . 'module/article/edit_article.js';
		$settings["class"] = "module_edit_article";
		$settings["status"] = isset($status) ? $status : null;
		$settings["error_message"] = isset($error_message) ? $error_message : null;
		$settings["form_on_submit"] = "saveArticle()";
		
		$is_insertion = !empty($settings["allow_insertion"]) && !$data;
		
		$CommonModuleTableExtraAttributesUtil->prepareFileFieldsSettings($EVC, $settings);
		
		if (!empty($settings["show_article_id"]))
			$settings["fields"]["article_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		if (!empty($settings["show_published"])) {
			$settings["fields"]["published"]["field"]["input"]["type"] = "checkbox";
			$settings["fields"]["published"]["field"]["input"]["options"] = array(
				array("value" => 1)
			);
		}
		
		if (!empty($settings["show_allow_comments"])) {
			$settings["fields"]["allow_comments"]["field"]["input"]["type"] = "checkbox";
			$settings["fields"]["allow_comments"]["field"]["input"]["options"] = array(
				array("value" => 1)
			);
		}
		
		if (!empty($settings["show_photo_id"])) {
			$settings["fields"]["photo_id"]["field"]["input"]["type"] = "hidden";
			
			$label = isset($settings["fields"]["photo_id"]["field"]["label"]["value"]) ? $settings["fields"]["photo_id"]["field"]["label"]["value"] : null;
			$class = isset($settings["fields"]["photo_id"]["field"]["label"]["class"]) ? $settings["fields"]["photo_id"]["field"]["label"]["class"] : null;
			$previous_html = isset($settings["fields"]["photo_id"]["field"]["input"]["previous_html"]) ? $settings["fields"]["photo_id"]["field"]["input"]["previous_html"] : null;
			$next_html = isset($settings["fields"]["photo_id"]["field"]["input"]["next_html"]) ? $settings["fields"]["photo_id"]["field"]["input"]["next_html"] : null;
			
			$settings["fields"]["photo_id"]["field"]["input"]["previous_html"] = "";
			
			$settings["fields"]["photo_id"]["field"]["input"]["next_html"] = '
			</div>
			<div class="form-group form_field photo_file">
				' . $previous_html . '
				' . ($label ? '<label class="form-label control-label ' . $class . '">' . translateProjectText($EVC, $label) . '</label>' : '') . '
				<input type="file" class="form-control" name="photo" data-allow-null="1" data-validation-label="' . translateProjectText($EVC, \CommonModuleUI::getFieldLabel($settings, "photo_id")) . '" />';
			
			if ($photo_url) {
				$photo_url .= (strpos($photo_url, '?') !== false ? '&' : '?') . "t=" . time();
				
				$settings["fields"]["photo_id"]["field"]["input"]["next_html"] .= '
				</div>
				<div class="form_field photo_url">
					<a href="' . $photo_url . '" target="photo">
						<div class="form-group">
							<img class="form-control" src="' . $photo_url . '" onError="deletePhoto($(this).parent().closest(\'.photo_url\').find(\'.photo_remove\')[0])" alt="' . translateProjectText($EVC, "No Photo") . '" />
						</div>
					</a>
					<a class="photo_remove" onClick="deletePhoto(this)">' . translateProjectText($EVC, "Remove this photo") . '</a>';
			}
			
			$settings["fields"]["photo_id"]["field"]["input"]["next_html"] .= $next_html;
		}
		
		if (!empty($settings["show_article_attachments"])) {
			include_once $EVC->getModulePath("attachment/AttachmentUI", $common_project_name);
			
			$attachments_settings = array(
				"style_type" => isset($settings["style_type"]) ? $settings["style_type"] : null,
				"class" => isset($settings["fields"]["article_attachments"]["field"]["class"]) ? $settings["fields"]["article_attachments"]["field"]["class"] : null,
				"title" => isset($settings["fields"]["article_attachments"]["field"]["label"]["value"]) ? $settings["fields"]["article_attachments"]["field"]["label"]["value"] : null,
			);
			
			unset($settings["fields"]["article_attachments"]["field"]);
			
			$settings["fields"]["article_attachments"]["container"] = array(
				"previous_html" => \AttachmentUI::getEditObjectAttachmentsHtml($EVC, $attachments_settings, \ObjectUtil::ARTICLE_OBJECT_TYPE_ID, $article_id, \ArticleUtil::ARTICLE_ATTACHMENTS_GROUP_ID),
			);
		}
		
		//Add join point creating new fields in the article form.
		$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("New Article bottom fields", array(
			"EVC" => &$EVC,
			"settings" => &$settings,
			"object_type_id" => \ObjectUtil::ARTICLE_OBJECT_TYPE_ID,
			"object_id" => &$article_id,
			"group_id" => \ArticleUtil::ARTICLE_ATTACHMENTS_GROUP_ID,
		));
		
		$style_type = isset($settings["style_type"]) ? $settings["style_type"] : null;
		$summary_upload_url = isset($settings["upload_url"]) ? str_replace("#article_id#", $article_id ? $article_id : 0, str_replace("#group#", \ArticleUtil::ARTICLE_SUMMARY_HTML_IMAGE_GROUP_ID, $settings["upload_url"])) : null;
		$content_upload_url = isset($settings["upload_url"]) ? str_replace("#article_id#", $article_id ? $article_id : 0, str_replace("#group#", \ArticleUtil::ARTICLE_CONTENT_HTML_IMAGE_GROUP_ID, $settings["upload_url"])) : null;
		
		$html = '<script type="text/javascript">
			var style_type = "' . $style_type . '";
			
			var summary_ckeditor_active_prev = summary_ckeditor_active;
			var summary_ckeditor_active = summary_ckeditor_active ? summary_ckeditor_active : false;
			var summary_ckeditor_configs = summary_ckeditor_configs ? summary_ckeditor_configs : null;
			var summary_upload_url = "' . $summary_upload_url . '";
			
			var content_ckeditor_active_prev = content_ckeditor_active;
			var content_ckeditor_active = content_ckeditor_active ? content_ckeditor_active : false;
			var content_ckeditor_configs = content_ckeditor_configs ? content_ckeditor_configs : null;
			var content_upload_url = "' . $content_upload_url . '";
		</script>';
		
		$exists_ckeditor = file_exists($EVC->getWebrootPath($common_project_name) . "vendor/ckeditor/ckeditor.js");
		
		if (!$style_type && $exists_ckeditor)
			$html .= '<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/ckeditor/ckeditor.js"></script>
			<script>
			summary_ckeditor_active = typeof summary_ckeditor_active_prev != "undefined" ? summary_ckeditor_active_prev : true;
			content_ckeditor_active = typeof content_ckeditor_active_prev != "undefined" ? content_ckeditor_active_prev : true;
			</script>';
		
		if (!$exists_ckeditor) //be sure that ckeditor is inactive
			$html .= '<script>
			summary_ckeditor_active = false;
			content_ckeditor_active = false;
			</script>';
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "article/edit_article", $settings);
		$html .= \CommonModuleUI::getFormHtml($EVC, $settings);
		return $html;
	}
	
	private function prepareArticleHtmlAttributes($EVC, $settings, $article_id, &$article_data, &$status = false) {
		$summary_upload_url = isset($settings["upload_url"]) ? str_replace("#article_id#", $article_id, str_replace("#group#", \ArticleUtil::ARTICLE_SUMMARY_HTML_IMAGE_GROUP_ID, $settings["upload_url"])) : null;
		$content_upload_url = isset($settings["upload_url"]) ? str_replace("#article_id#", $article_id, str_replace("#group#", \ArticleUtil::ARTICLE_CONTENT_HTML_IMAGE_GROUP_ID, $settings["upload_url"])) : null;
		$summary = isset($article_data["summary"]) ? $article_data["summary"] : null;
		$content = isset($article_data["content"]) ? $article_data["content"] : null;
		$regex = isset($settings["attachment_id_regex"]) ? $settings["attachment_id_regex"] : null;
		
		\CommonModuleUtil::prepareObjectHtmlContent($EVC, $summary, \ObjectUtil::ARTICLE_OBJECT_TYPE_ID, $article_id, \ArticleUtil::ARTICLE_SUMMARY_HTML_IMAGE_GROUP_ID, $regex, $summary_upload_url, $status);
		\CommonModuleUtil::prepareObjectHtmlContent($EVC, $content, \ObjectUtil::ARTICLE_OBJECT_TYPE_ID, $article_id, \ArticleUtil::ARTICLE_CONTENT_HTML_IMAGE_GROUP_ID, $regex, $content_upload_url, $status);
		
		return $status;
	}
}
?>
