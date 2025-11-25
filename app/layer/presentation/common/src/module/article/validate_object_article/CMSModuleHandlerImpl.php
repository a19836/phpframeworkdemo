<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

namespace CMSModule\article\validate_object_article;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include_once $EVC->getModulePath("common/ObjectToObjectValidationHandler", $common_project_name);
		include_once $EVC->getModulePath("article/ArticleUtil", $common_project_name);
		
		$brokers = $EVC->getPresentationLayer()->getBrokers();
		
		$article_ids = isset($settings["article_id"]) && is_array($settings["article_id"]) ? $settings["article_id"] : array($settings["article_id"]);
		
		foreach ($article_ids as $article_id) {
			if (!is_numeric($article_id))
				$status = false;
			else {
				$object_type_id = isset($settings["object_type_id"]) ? $settings["object_type_id"] : null;
				$object_id = isset($settings["object_id"]) ? $settings["object_id"] : null;
				
				if (isset($settings["group"]) && is_numeric($settings["group"]))
					$result = \ArticleUtil::getObjectArticlesByConditions($brokers, array(
						"article_id" => $article_id, 
						"object_type_id" => $object_type_id, 
						"object_id" => $object_id,
						"group" => isset($settings["group"]) ? $settings["group"] : null,
					), null);
				else
					$result = \ArticleUtil::getObjectArticle($brokers, $article_id, $object_type_id, $object_id);
		
				$status = !empty($result);
			}
			
			if (!$status)
				break;
		}
		
		return \ObjectToObjectValidationHandler::validate($EVC, $status, $settings);
	}
}
?>
