<?php
namespace CMSModule\article\edit_article;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("article/ArticleUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleUtil", $common_project_name);
		include_once $EVC->getModulePath("common/CommonModuleTableExtraAttributesUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$CommonModuleTableExtraAttributesUtil = new \CommonModuleTableExtraAttributesUtil($this, $GLOBALS["default_db_driver"], $settings, "article");
		
		//Getting Article Details
		$article_id = $_GET["article_id"];
		$data = \ArticleUtil::getArticleProperties($EVC, $article_id, true);
		$photo_url = $data["photo_url"];
		
		//Getting Article Extra Details
		if ($data) {
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
		if ($_POST) {
			
			if ($_POST["delete"] && $settings["allow_deletion"]) {
				$status = !$data || \ArticleUtil::deleteArticle($EVC, $data["article_id"]);
				
				if ($status && $data["article_id"])
					$status = $CommonModuleTableExtraAttributesUtil->deleteTableExtra(array("article_id" => $data["article_id"]));
				
				if ($status) 
					//Add Join Point creating a new action of some kind
					$status = $EVC->getCMSLayer()->getCMSJoinPointLayer()->includeStatusJoinPoint("On successfull article deleting action", array(
						"EVC" => &$EVC,
						"article_id" => $data["article_id"],
						"article_data" => &$data,
						"error_message" => &$error_message,
					));
			}
			else if ($_POST["save"]) {
				$title = $_POST["title"];
				$sub_title = $_POST["sub_title"];
				$published = $_POST["published"];
				$tags = $_POST["tags"];
				$photo_id = $_POST["photo_id"];
				$summary = $_POST["summary"];
				$content = $_POST["content"];
				$allow_comments = $_POST["allow_comments"];
				
				$photo_id = $photo_id ? $photo_id : 0;
				
				$empty_field_name = \CommonModuleUI::checkIfEmptyFields($settings, array("title" => $title, "sub_title" => $sub_title, "published" => $published, "tags" => $tags, "photo_id" => $photo_id, "summary" => $summary, "content" => $content, "allow_comments" => $allow_comments));
				
				if (!$empty_field_name)
					$empty_field_name = $CommonModuleTableExtraAttributesUtil->checkIfEmptyFields($settings, $_POST);
				
				if ($empty_field_name)
					$error_message = \CommonModuleUI::getFieldValidationMessage($EVC, $settings, $empty_field_name);
				else {
					$new_data = $data;
					$new_data["title"] = $settings["show_title"] ? $title : $new_data["title"];
					$new_data["sub_title"] = $settings["show_sub_title"] ? $sub_title : $new_data["sub_title"];
					$new_data["published"] = $settings["show_published"] ? $published : $new_data["published"];
					$new_data["tags"] = $settings["show_tags"] ? $tags : $new_data["tags"];
					$new_data["photo_id"] = $settings["show_photo_id"] ? $photo_id : $new_data["photo_id"];
					$new_data["summary"] = $settings["show_summary"] ? $summary : $new_data["summary"];
					$new_data["content"] = $settings["show_content"] ? $content : $new_data["content"];
					$new_data["allow_comments"] = $settings["show_allow_comments"] ? $allow_comments : $new_data["allow_comments"];
					
					$CommonModuleTableExtraAttributesUtil->prepareFieldsWithNewData($settings, $new_data, $data, $_POST);
					
					\CommonModuleUI::prepareFieldsWithDefaultValue($settings, $new_data);
					
					//check if $_FILES["photo"] is an image
					if ($_FILES["photo"] && $_FILES["photo"]["tmp_name"]) {
						$mime_type = $_FILES["photo"]["type"] ? $_FILES["photo"]["type"] : MimeTypeHandler::getFileMimeType($_FILES["photo"]["tmp_name"]);
						
						if (!\MimeTypeHandler::isImageMimeType($mime_type))
							$error_message = "Upload photo must be an image!";
					}
					
					if (!$error_message && \CommonModuleUI::areFieldsValid($EVC, $settings, $new_data, $error_message) && $CommonModuleTableExtraAttributesUtil->areFileFieldsValid($EVC, $settings, $error_message)) {
						$new_data["object_articles"] = $settings["object_to_objects"];
						
						//save article
						if ($settings["allow_insertion"] && empty($data["article_id"])) {
							$status = \ArticleUtil::setArticleProperties($EVC, null, $new_data, $_FILES["photo"]);
							if (strpos($settings["on_insert_ok_action"], "_redirect") !== false)
								$settings["on_insert_ok_redirect_url"] .= (strpos($settings["on_insert_ok_redirect_url"], "?") !== false ? "&" : "?") . "article_id=$status";
						}
						else if ($settings["allow_update"] && $data["article_id"])
							$status = \ArticleUtil::setArticleProperties($EVC, $data["article_id"], $new_data, $_FILES["photo"]);
						
						if ($status) {
							$article_id = $status;
							
							if ($_FILES["photo"]) {
								//Load again data because of the photo_url, but without changing the $data variable
								$db_data = \ArticleUtil::getArticleProperties($EVC, $article_id, true);
								$new_data["photo_id"] = $db_data["photo_id"];
								$new_data["photo_url"] = $db_data["photo_url"];
								$photo_id = $db_data["photo_id"];
								$photo_url = $db_data["photo_url"];
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
									if ($new_data["content"] != $data["content"] || $new_data["summary"] != $data["summary"]) {
										$this->prepareArticleHtmlAttributes($EVC, $settings, $article_id, $new_data, $status);
										$aux = $new_data;
										$aux["article_id"] = $article_id;
										
										if (!\ArticleUtil::insertOrUpdateArticle($brokers, $aux))
											$status = false;
										
										$summary = $settings["show_summary"] ? $new_data["summary"] : $summary;
										$content = $settings["show_content"] ? $new_data["content"] : $content;
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
		
		if ($_POST["save"]) {
			$form_data = array(
				"article_id" => $settings["show_article_id"] ? $article_id : $data["article_id"],
				"title" => $settings["show_title"] ? $title : $data["title"],
				"sub_title" => $settings["show_sub_title"] ? $sub_title : $data["sub_title"],
				"published" => $settings["show_published"] ? $published : $data["published"],
				"tags" => $settings["show_tags"] ? $tags : $data["tags"],
				"photo_id" => $settings["show_photo_id"] ? $photo_id : $data["photo_id"],
				"photo_url" => $photo_url,
				"summary" => $settings["show_summary"] ? $summary : $data["summary"],
				"content" => $settings["show_content"] ? $content : $data["content"],
				"allow_comments" => $settings["show_allow_comments"] ? $allow_comments : $data["allow_comments"],
			);
			
			$CommonModuleTableExtraAttributesUtil->prepareFieldsWithNewData($settings, $form_data, $data, $_POST);
			
			$form_data = $new_data ? array_merge($new_data, $form_data) : ($settings["allow_view"] && $data ? array_merge($data, $form_data) : $form_data);//Just in case there are other fields from the joinpoints or from the field's next_html/previous_html
		}
		else
			$form_data = $settings["allow_view"] && $data ? $data : array();
		
		$settings["data"] = $data;
		$settings["form_data"] = $form_data;
		$settings["css_file"] = $project_common_url_prefix . 'module/article/edit_article.css';
		$settings["js_file"] = $project_common_url_prefix . 'module/article/edit_article.js';
		$settings["class"] = "module_edit_article";
		$settings["status"] = $status;
		$settings["error_message"] = $error_message;
		$settings["form_on_submit"] = "saveArticle()";
		
		$is_insertion = $settings["allow_insertion"] && !$data;
		
		$CommonModuleTableExtraAttributesUtil->prepareFileFieldsSettings($EVC, $settings);
		
		if ($settings["show_article_id"])
			$settings["fields"]["article_id"]["field"]["input"]["type"] = $is_insertion ? "hidden" : "label";
		
		if ($settings["show_published"]) {
			$settings["fields"]["published"]["field"]["input"]["type"] = "checkbox";
			$settings["fields"]["published"]["field"]["input"]["options"] = array(
				array("value" => 1)
			);
		}
		
		if ($settings["show_allow_comments"]) {
			$settings["fields"]["allow_comments"]["field"]["input"]["type"] = "checkbox";
			$settings["fields"]["allow_comments"]["field"]["input"]["options"] = array(
				array("value" => 1)
			);
		}
		
		if ($settings["show_photo_id"]) {
			$settings["fields"]["photo_id"]["field"]["input"]["type"] = "hidden";
			
			$label = $settings["fields"]["photo_id"]["field"]["label"]["value"];
			
			$previous_html = $settings["fields"]["photo_id"]["field"]["input"]["previous_html"];
			$next_html = $settings["fields"]["photo_id"]["field"]["input"]["next_html"];
			
			$settings["fields"]["photo_id"]["field"]["input"]["previous_html"] = "";
			
			$settings["fields"]["photo_id"]["field"]["input"]["next_html"] = '
			</div>
			<div class="form-group form_field photo_file">
				' . $previous_html . '
				' . ($label ? '<label class="form-label control-label ' . $label = $settings["fields"]["photo_id"]["field"]["label"]["class"] . '">' . translateProjectText($EVC, $label) . '</label>' : '') . '
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
		
		if ($settings["show_article_attachments"]) {
			include_once $EVC->getModulePath("attachment/AttachmentUI", $common_project_name);
			
			$attachments_settings = array(
				"style_type" => $settings["style_type"],
				"class" => $settings["fields"]["article_attachments"]["field"]["class"],
				"title" => $settings["fields"]["article_attachments"]["field"]["label"]["value"],
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
		
		$html .= '<script type="text/javascript">
			var style_type = "' . $settings["style_type"] . '";
			
			var summary_ckeditor_active_prev = summary_ckeditor_active;
			var summary_ckeditor_active = summary_ckeditor_active ? summary_ckeditor_active : false;
			var summary_ckeditor_configs = summary_ckeditor_configs ? summary_ckeditor_configs : null;
			var summary_upload_url = "' . str_replace("#article_id#", $article_id ? $article_id : 0, str_replace("#group#", \ArticleUtil::ARTICLE_SUMMARY_HTML_IMAGE_GROUP_ID, $settings["upload_url"])) . '";
			
			var content_ckeditor_active_prev = content_ckeditor_active;
			var content_ckeditor_active = content_ckeditor_active ? content_ckeditor_active : false;
			var content_ckeditor_configs = content_ckeditor_configs ? content_ckeditor_configs : null;
			var content_upload_url = "' . str_replace("#article_id#", $article_id ? $article_id : 0, str_replace("#group#", \ArticleUtil::ARTICLE_CONTENT_HTML_IMAGE_GROUP_ID, $settings["upload_url"])) . '";
		</script>';
		
		if (empty($settings["style_type"]))
			$html .= '<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/ckeditor/ckeditor.js"></script>
			<script>
			summary_ckeditor_active = typeof summary_ckeditor_active_prev != "undefined" ? summary_ckeditor_active_prev : true;
			content_ckeditor_active = typeof content_ckeditor_active_prev != "undefined" ? content_ckeditor_active_prev : true;
			</script>';
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "article/edit_article", $settings);
		$html .= \CommonModuleUI::getFormHtml($EVC, $settings);
		return $html;
	}
	
	private function prepareArticleHtmlAttributes($EVC, $settings, $article_id, &$article_data, &$status = false) {
		$summary_upload_url = str_replace("#article_id#", $article_id, str_replace("#group#", \ArticleUtil::ARTICLE_SUMMARY_HTML_IMAGE_GROUP_ID, $settings["upload_url"]));
		\CommonModuleUtil::prepareObjectHtmlContent($EVC, $article_data["summary"], \ObjectUtil::ARTICLE_OBJECT_TYPE_ID, $article_id, \ArticleUtil::ARTICLE_SUMMARY_HTML_IMAGE_GROUP_ID, $settings["attachment_id_regex"], $summary_upload_url, $status);
		
		$content_upload_url = str_replace("#article_id#", $article_id, str_replace("#group#", \ArticleUtil::ARTICLE_CONTENT_HTML_IMAGE_GROUP_ID, $settings["upload_url"]));
		\CommonModuleUtil::prepareObjectHtmlContent($EVC, $article_data["content"], \ObjectUtil::ARTICLE_OBJECT_TYPE_ID, $article_id, \ArticleUtil::ARTICLE_CONTENT_HTML_IMAGE_GROUP_ID, $settings["attachment_id_regex"], $content_upload_url, $status);
		
		return $status;
	}
}
?>
