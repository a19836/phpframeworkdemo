<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

namespace CMSModule\article\list_articles;

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
		
		$html = '<div class="module_list_articles ' . (isset($settings["block_class"]) ? $settings["block_class"] : null) . '">';
		$settings["block_class"] = null;
		
		//Getting articles
		$settings["total"] = $total;
		$settings["data"] = $articles;
		$settings["css_file"] = $project_common_url_prefix . 'module/article/list_articles.css';
		$settings["js_file"] = $project_common_url_prefix . 'module/article/list_articles.js';
		$settings["class"] = "";
		$settings["edit_page_url"] .= (isset($settings["edit_page_url"]) && strpos($settings["edit_page_url"], "?") !== false ? "&" : "?") . "article_id=#[idx][article_id]#";
		$settings["delete_page_url"] = "{$project_url_prefix}module/article/list_articles/delete_article?article_id=#[idx][article_id]#";
		
		\CommonModuleUI::prepareSettingsWithSelectedTemplateModuleHtml($this, "article/list_articles", $settings);
		$html .= \CommonModuleUI::getListHtml($EVC, $settings);
		
		$html .= '</div>';
		
		return $html;
	}
}
?>
