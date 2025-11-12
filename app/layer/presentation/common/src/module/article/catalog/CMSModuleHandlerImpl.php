<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

namespace CMSModule\article\catalog;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("common/CommonModuleTableExtraAttributesUtil", $common_project_name);
		include_once $EVC->getModulePath("article/ArticleUI", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		$CommonModuleTableExtraAttributesUtil = new \CommonModuleTableExtraAttributesUtil($this, isset($GLOBALS["default_db_driver"]) ? $GLOBALS["default_db_driver"] : null, $settings, "article");
		
		$html = '';
		
		if (empty($settings["style_type"])) {
			$html .= '<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/article/catalog.css" type="text/css" charset="utf-8" />';
		}
		
		$html .= '<script src="' . $project_common_url_prefix . 'module/article/catalog.js"></script>
		' . (!empty($settings["css"]) ? '<style>' . $settings["css"] . '</style>' : '') . '
		' . (!empty($settings["js"]) ? '<script type="text/javascript">' . $settings["js"] . '</script>' : '') . '

		<div class="module_articles_catalog ' . (isset($settings["block_class"]) ? $settings["block_class"] : null) . '">';
		
		$catalog_title = isset($settings["catalog_title"]) ? $settings["catalog_title"] : null;
		if ($catalog_title)
			$html .= '<h1 class="catalog_title">' . translateProjectText($EVC, $catalog_title) . '</h1>';
		
		//Preparing options
		$rows_per_page = isset($settings["rows_per_page"]) && $settings["rows_per_page"] > 0 ? $settings["rows_per_page"] : null;
		$options = array("limit" => $rows_per_page, "sort" => array());
		
		//Preparing pagination
		if (!empty($settings["top_pagination_type"]) || !empty($settings["bottom_pagination_type"])) {
			include_once get_lib("org.phpframework.util.web.html.pagination.PaginationLayout");
			
			$current_page = isset($_GET["current_page"]) && is_numeric($_GET["current_page"]) ? $_GET["current_page"] : 0;
			$rows_per_page = $rows_per_page > 0 ? $rows_per_page : 50;
			$options["start"] = \PaginationHandler::getStartValue($current_page, $rows_per_page);
		}
		
		//Getting articles
		$articles = \ArticleUI::getArticlesFromSettings($EVC, $settings, $brokers, $options);
		$total = $articles[0];
		$articles = $articles[1];
		
		//Getting Article Extra Details
		$CommonModuleTableExtraAttributesUtil->prepareItemsWithTableExtra($articles, "article_id");
		
		//Add join point changing the articles.
		$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Preparing articles", array(
			"EVC" => &$EVC,
			"settings" => &$settings,
			"total" => &$total,
			"articles" => &$articles,
		), "This join point's method/function can change the \$settings, \$total or \$articles variables.");
		
		$current_url = isset($settings["article_properties_url"]) ? $settings["article_properties_url"] : null;
		
		//Preparing pagination
		if (!empty($settings["top_pagination_type"]) || !empty($settings["bottom_pagination_type"])) {
			$PaginationLayout = new \PaginationLayout($total, $rows_per_page, array("current_page" => $current_page), "current_page");
			$PaginationLayout->show_x_pages_at_once = 10;
			$pagination_data = $PaginationLayout->data;
		}
		
		$catalog_type = isset($settings["catalog_type"]) ? $settings["catalog_type"] : null;
		
		//prepare settings with selected template html if apply
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "article/catalog", $settings);
		
		//execute user list with ptl
		if ($catalog_type == "user_list" && !empty($settings["ptl"])) {
			$form_settings = array("ptl" => $settings["ptl"]);
			$articles_item_input_data_var_name = isset($form_settings["ptl"]["external_vars"]["articles_item_input_data_var_name"]) ? $form_settings["ptl"]["external_vars"]["articles_item_input_data_var_name"] : null; //this should contain "article" by default, but is not mandatory. This value should be the same than the following foreach-item-value-name: <ptl:foreach $input i article>, but only if the user doesn't change this value. If the user changes the foreach to <ptl:foreach $input i item>, he must change the external var "articles_item_input_data_var_name" to "item" too.
			if ($articles_item_input_data_var_name)
				$form_settings["ptl"]["input_data_var_name"] = $articles_item_input_data_var_name;
			$HtmlFormHandler = new \HtmlFormHandler($form_settings);
			
			$settings["ptl"]["code"] = isset($settings["ptl"]["code"]) ? $settings["ptl"]["code"] : null;
			
			foreach ($settings["fields"] as $field_id => $field) 
				if (!empty($settings["show_" . $field_id]))
					\CommonModuleUI::prepareBlockFieldPTLCode($EVC, $HtmlFormHandler, $settings["ptl"]["code"], $field_id, $field, $articles);
			
			if (!empty($settings["top_pagination_type"])) {
				$pagination_data["style"] = isset($settings["top_pagination_type"]) ? $settings["top_pagination_type"] : null;
				$settings["ptl"]["code"] = preg_replace('/<ptl:block:top-pagination\s*\/?>/i', $PaginationLayout->designWithStyle(1, $pagination_data), $settings["ptl"]["code"]);
			}
			
			if (!empty($settings["bottom_pagination_type"])) {
				$pagination_data["style"] = isset($settings["bottom_pagination_type"]) ? $settings["bottom_pagination_type"] : null;
				$settings["ptl"]["code"] = preg_replace('/<ptl:block:bottom-pagination\s*\/?>/i', $PaginationLayout->designWithStyle(1, $pagination_data), $settings["ptl"]["code"]);
			}
			
			\CommonModuleUI::cleanBlockPTLCode($settings["ptl"]["code"]);
			
			$form_settings = array(
				"ptl" => $settings["ptl"],
				"CacheHandler" => $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler")
			);
		
			$html .= \HtmlFormHandler::createHtmlForm($form_settings, $articles);
		}
		else { //execute blog and normal list or user list with no ptl
			//showing top pagination
			if (!empty($settings["top_pagination_type"])) {
				$pagination_data["style"] = isset($settings["top_pagination_type"]) ? $settings["top_pagination_type"] : null;
				
				$html .= '<div class="top_pagination pagination_alignment_' . (isset($settings["top_pagination_alignment"]) ? $settings["top_pagination_alignment"] : null) . '">' . $PaginationLayout->designWithStyle(1, $pagination_data) . '</div>';
			}
			
			//showing catalog
			$html .= '<ul class="catalog catalog_' . $catalog_type . '">';
			
			if ($catalog_type == "blog_list") {
				$blog_introduction_articles_num = isset($settings["blog_introduction_articles_num"]) ? $settings["blog_introduction_articles_num"] : null;
				$blog_featured_articles_num = isset($settings["blog_featured_articles_num"]) ? $settings["blog_featured_articles_num"] : null;
				$blog_featured_articles_cols = isset($settings["blog_featured_articles_cols"]) ? $settings["blog_featured_articles_cols"] : null;
				$blog_listed_articles_num = isset($settings["blog_listed_articles_num"]) ? $settings["blog_listed_articles_num"] : null;
				
				$html .= self::getCatalogListHtml($EVC, $settings, $common_project_name, $current_url, $articles, $blog_introduction_articles_num, $blog_featured_articles_num, $blog_featured_articles_cols, $blog_listed_articles_num);
			}
			else //execute normal list and user list with no ptl
				$html .= self::getCatalogListHtml($EVC, $settings, $common_project_name, $current_url, $articles);
			
			$html .= '</ul>';
			
			if (!empty($settings["bottom_pagination_type"])) {
				$pagination_data["style"] = isset($settings["bottom_pagination_type"]) ? $settings["bottom_pagination_type"] : null;
				
				$html .= '<div class="bottom_pagination pagination_alignment_' . (isset($settings["bottom_pagination_alignment"]) ? $settings["bottom_pagination_alignment"] : null) . '">' . $PaginationLayout->designWithStyle(1, $pagination_data) . '</div>';
			}
		}
		
		$html .= '</div>';
		
		return $html;
	}
	
	private static function getCatalogListHtml($EVC, $settings, $common_project_name, $current_url, $articles, $introduction_articles_num = false, $featured_articles_num = false, $featured_articles_cols = false, $listed_articles_num = false) {
		$html = '';
		
		if ($articles) {
			$is_blog = $introduction_articles_num || $featured_articles_num || $listed_articles_num;
			$featured_aux = 1;
			$featured_50 = false;
			$featured_flag = false;
			$exists_more_articles = $has_listed = false;
			$featured_articles_cols = $featured_articles_cols ? $featured_articles_cols : 3;
			
			$t = count($articles);
			for ($i = 0; $i < $t; $i++) {
				$class = "";
				$clear = false;
				$rest = $t - $i;
				
				if ($introduction_articles_num > 0) {
					$class = "introduction_article";
					--$introduction_articles_num;
				}
				else if ($featured_articles_num > 0) {
					if ($featured_articles_cols == 3) {
						if ($featured_articles_num >= 3 || $featured_aux > 1) {
							$class = "featured_article_" . ($featured_aux == 1 ? "70" : "30") . " " . ($featured_flag ? "featured_article_right" : "featured_article_left");
							
							if ($featured_aux == 3) {
								$featured_aux = 1;
								$featured_flag = !$featured_flag;
								$clear = true;
							}
							else {
								$featured_aux++;
							}
						}
						else if (($featured_articles_num == 2 && $rest >= 2) || $featured_50) {
							$class = "featured_article_50 " . ($featured_articles_num == 2 ? "featured_article_50_left" : "featured_article_50_right");
							$clear = $featured_50;
							$featured_50 = true;
						}
						else {
							$class = "featured_article";
						}
					}
					else if ($featured_articles_cols == 2 && ($rest >= 2 || $featured_50)) {
						$class = "featured_article_50 " . (!$featured_50 ? "featured_article_50_left" : "featured_article_50_right");
						$clear = $featured_50;
						$featured_50 = !$featured_50;
					}
					else {
						$class = "featured_article";
					}
					
					--$featured_articles_num;
				}
				else if ($listed_articles_num > 0) {
					if (!$has_listed) {
						$html .= '<li class="listed_articles"><ol>';
						$has_listed = true;
					}
					
					$class = "listed_article";
					--$listed_articles_num;
				}
				else if ($is_blog) {
					if (!$has_listed) {
						$html .= '<li class="listed_articles"><ol>';
						$has_listed = true;
					}
					
					$class = "listed_article article_hidden";
					$exists_more_articles = true;
				}
				else {
					$class = "article";
				}
				
				$html .= '<li class="' . $class . '">' . self::getCatalogArticleHtml($EVC, $settings, $common_project_name, $current_url, $articles[$i]) . '</li>';
				
				if ($clear) {
					$html .= '<li class="catalog_article_clear"></li>';
				}
			}
			
			if ($has_listed) {
				$html .= '</ol></li>';
			}
			
			if ($exists_more_articles) {
				$html .= '<li class="more_articles" onClick="seeMoreArticles(this)">' . translateProjectText($EVC, "Click here to see more articles...") . '</li>';
			}
		}
		else {
			$html .= '<li><h3 class="no_articles">' . translateProjectText($EVC, "There are no available articles...") . '</h3></li>';
		}
		
		return $html;
	}
	
	private static function getCatalogArticleHtml($EVC, $settings, $common_project_name, $current_url, $article) {
		//Preparing fields
		$form_settings = array(
			"with_form" => 0,
			"form_containers" => array(
				0 => array(
					"container" => array(
						"elements" => array(),
						"next_html" => '<div class="catalog_article_clear"></div>',
					)
				),
			)
		);
		
		if ($current_url) {
			$form_settings["form_containers"][0]["container"]["href"] = $current_url . (isset($article["article_id"]) ? $article["article_id"] : null);
			$form_settings["form_containers"][0]["container"]["title"] = isset($article["title"]) ? $article["title"] : null;
		}
		
		$HtmlFormHandler = null;
		if (!empty($settings["ptl"]))
			$HtmlFormHandler = new \HtmlFormHandler(array("ptl" => $settings["ptl"]));
		
		foreach ($settings["fields"] as $field_id => $field) 
			if (!empty($settings["show_" . $field_id]) && ($field_id == "photo" || !empty($article[$field_id]))) {
				//Preparing ptl
				if (!empty($settings["ptl"])) {
					$settings["ptl"]["code"] = isset($settings["ptl"]["code"]) ? $settings["ptl"]["code"] : null;
					\CommonModuleUI::prepareBlockFieldPTLCode($EVC, $HtmlFormHandler, $settings["ptl"]["code"], $field_id, $field, $article);
				}
				else
					$form_settings["form_containers"][0]["container"]["elements"][] = $field;
			}
		
		//add ptl to form_settings
		if (!empty($settings["ptl"])) {
			$settings["ptl"]["code"] = isset($settings["ptl"]["code"]) ? $settings["ptl"]["code"] : null;
			\CommonModuleUI::cleanBlockPTLCode($settings["ptl"]["code"]);
			$form_settings["form_containers"][0]["container"]["elements"][] = array("ptl" => $settings["ptl"]);
		}
		
		translateProjectFormSettings($EVC, $form_settings);
	
		$form_settings["CacheHandler"] = $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler");
	
		return \HtmlFormHandler::createHtmlForm($form_settings, $article);
	}
	
	/*private static function getCatalogArticleHtml($EVC, $settings, $common_project_name, $current_url, $article) {
		$photo = "";
		if (!empty($article["photo_id"]) && !empty($article["photo_path"]) && file_exists($article["photo_path"])) {
			$photo = '<img class="catalog_article_photo" src="' . (isset($article["photo_url"]) ? $article["photo_url"] : null) . '" />';
		}
		
		if (!empty($article["title"])) {
			$title = '<h1 class="catalog_article_title">';
			if ($current_url)
				$title .= '<a href="' . $current_url . (isset($article["article_id"]) ? $article["article_id"] : null) . '">' . $article["title"] . '</a>';
			else
				$title .= $article["title"];
			$title .= '</h1>';
		}
		
		$sub_title = !empty($article["sub_title"]) ? '<h2 class="catalog_article_sub_title">' . $article["sub_title"] . '</h2>' : '';
		$summary = !empty($article["summary"]) ? '<div class="catalog_article_summary">' . $article["summary"] . '</div>' : '';
		
		return $photo . '
			<div class="catalog_article_data">' . $title . $sub_title . $summary . '</div>
			<div class="catalog_article_clear"></div>';
	}*/
}
?>
