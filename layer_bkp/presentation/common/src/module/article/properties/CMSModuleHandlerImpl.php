<?php
namespace CMSModule\article\properties;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		$article_id = is_numeric($settings["article_id"]) ? $settings["article_id"] : null;
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("common/CommonModuleTableExtraAttributesUtil", $common_project_name);
		include_once $EVC->getModulePath("article/ArticleUtil", $common_project_name);
		include_once $EVC->getModulePath("attachment/AttachmentUI", $common_project_name);
		include_once $EVC->getModulePath("comment/CommentUI", $common_project_name);
		include_once get_lib("org.phpframework.util.web.html.HtmlFormHandler");
		
		$CommonModuleTableExtraAttributesUtil = new \CommonModuleTableExtraAttributesUtil($this, $GLOBALS["default_db_driver"], $settings, "article");
		
		if (empty($settings["style_type"])) {
			$html = '<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/article/properties.css" type="text/css" charset="utf-8" />';
		}
		
		$html .= ($settings["css"] ? '<style>' . $settings["css"] . '</style>' : '') . '
		' . ($settings["js"] ? '<script type="text/javascript">' . $settings["js"] . '</script>' : '') . '
		
		<div class="module_article ' . ($settings["block_class"]) . '">';
		
		if ($article_id) {
			$data = \ArticleUtil::getArticleProperties($EVC, $article_id, true);
			
			//Getting Article Extra Details
			if ($data) {
				$data_extra = $CommonModuleTableExtraAttributesUtil->getTableExtra(array("article_id" => $article_id), true);
				$data = $data_extra ? array_merge($data, $data_extra) : $data;
			}
			
			//Add join point changing the article properties.
			$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Preparing article properties", array(
				"EVC" => &$EVC,
				"settings" => &$settings,
				"data" => &$data,
				"article_id" => &$article_id,
			), "This join point's method/function can change the \$settings or \$data variables. \$data contains the article properties.");
			
			if ($data) {
				if (!$data["published"] && !$settings["allow_not_published"]) {
					$html .= '<h3 class="article_error">' . translateProjectText($EVC, "Article not Published!") . '</h3>';
				}
				else if ($settings["fields"]) {
					$form_settings = array(
						"with_form" => 0,
						"form_containers" => array(
							0 => array(
								"container" => array(
									"elements" => array()
								)
							),
						)
					);
					
					$attachments_html = '';
					if ($settings["show_attachments"]) {
						$attachments_settings = array(
							"style_type" => $settings["style_type"],
							"class" => $settings["fields"]["attachments"]["field"]["class"],
							"title" => $settings["fields"]["attachments"]["field"]["label"]["value"],
						);
						$attachments_html = \AttachmentUI::getObjectAttachmentsHtml($EVC, $attachments_settings, \ObjectUtil::ARTICLE_OBJECT_TYPE_ID, $article_id, \ArticleUtil::ARTICLE_ATTACHMENTS_GROUP_ID) . '<div class="clear"></div>';
					}
					
					$comments_html = '';
					if ($settings["show_comments"]) {
						$comments_settings = array(
							"style_type" => $settings["style_type"],
							"class" => $settings["fields"]["comments"]["field"]["class"],
							"title" => $settings["fields"]["comments"]["field"]["label"]["value"],
							"add_comment_url" => $data["allow_comments"] ? $settings["fields"]["comments"]["field"]["add_comment_url"] : null,
						);
						
						//Add join point initting the $settings[comments_users] with the correspondent users' data array for the article comments.
						$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Preparing article comments settings", array(
							"EVC" => &$EVC,
							"settings" => &$comments_settings,
							"object_type_id" => \ObjectUtil::ARTICLE_OBJECT_TYPE_ID,
							"object_id" => &$article_id,
						), "This join point's method/function can set the \$settings[comments_users] and \$settings[current_user] items with the correspondent users' data for the article's comments and correspondent logged user data. Additionally can set the following items too: \$settings[add_comment_label], \$settings[add_comment_textarea_place_holder], \$settings[add_comment_button_label], \$settings[add_comment_error_message], \$settings[empty_comments_label], \$settings[style_type], \$settings[class], \$settings[title] and \$settings[add_comment_url]. These items are optional.");
						
						$comments_html = \CommentUI::getObjectCommentsHtml($EVC, $comments_settings, \ObjectUtil::ARTICLE_OBJECT_TYPE_ID, $article_id);
					}
					
					//prepare settings with selected template html if apply
					\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "article/properties", $settings);
					
					$HtmlFormHandler = null;
					if ($settings["ptl"])
						$HtmlFormHandler = new \HtmlFormHandler(array("ptl" => $settings["ptl"]));
					
					$container_idx = 0;
					foreach ($settings["fields"] as $field_id => $field) {
						if ($settings["show_" . $field_id]) {
							//Preparing ptl
							if ($settings["ptl"]) {
								if ($field_id == "attachments")
									$settings["ptl"]["code"] = preg_replace('/<ptl:block:field:attachments\s*\/?>/', $attachments_html, $settings["ptl"]["code"]);
								else if ($field_id == "comments")
									$settings["ptl"]["code"] = preg_replace('/<ptl:block:field:comments\s*\/?>/', $comments_html, $settings["ptl"]["code"]);
								else if ($field_id == "photo" || $data[$field_id])
									\CommonModuleUI::prepareBlockFieldPTLCode($EVC, $HtmlFormHandler, $settings["ptl"]["code"], $field_id, $field, $data);
							}
							else {
								if ($field_id == "attachments") {
									$form_settings["form_containers"][$container_idx]["container"]["next_html"] = '<div class="clear"></div>' . $attachments_html;
									$container_idx++;
								}
								else if ($field_id == "comments") {
									$form_settings["form_containers"][$container_idx]["container"]["next_html"] = '<div class="clear"></div>' . $comments_html;
									$container_idx++;
								}
								else if ($field_id == "photo" || $data[$field_id])
									$form_settings["form_containers"][$container_idx]["container"]["elements"][] = $field;
							}
						}
					}
					
					//add ptl to form_settings
					if ($settings["ptl"]) {
						\CommonModuleUI::cleanBlockPTLCode($settings["ptl"]["code"]);
						$form_settings["form_containers"][$container_idx]["container"]["elements"][] = array("ptl" => $settings["ptl"]);
					}
					
					$form_settings["form_containers"][$container_idx]["container"]["next_html"] = '<div class="clear"></div>';
					translateProjectFormSettings($EVC, $form_settings);
					
					$form_settings["CacheHandler"] = $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler");
		
					$html .= \HtmlFormHandler::createHtmlForm($form_settings, $data);
				}
			}
		}
		else
			$html .= '<h3 class="article_error">' . translateProjectText($EVC, "Invalid Article") . '</h3>';
			
		$html .= '</div>';
				
		return $html;
	}
}
?>
