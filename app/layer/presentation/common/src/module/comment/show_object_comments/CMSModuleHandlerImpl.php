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

namespace CMSModule\comment\show_object_comments;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$status = $error_message = null;
		$EVC = $this->getEVC();
		
		include_once $EVC->getModulePath("object/ObjectUtil", $EVC->getCommonProjectName());
		include_once $EVC->getModulePath("comment/CommentUI", $EVC->getCommonProjectName());
		
		$settings["object_type_id"] = isset($settings["object_type_id"]) ? $settings["object_type_id"] : null;
		$settings["object_id"] = isset($settings["object_id"]) ? $settings["object_id"] : null;
		$settings["group"] = isset($settings["group"]) ? $settings["group"] : null;
		
		//Add join point initting the $settings[comments_users] with the correspondent users' data array for the object comments.
		$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Preparing object comments settings", array(
			"EVC" => &$EVC,
			"settings" => &$settings,
			"object_type_id" => &$settings["object_type_id"],
			"object_id" => &$settings["object_id"],
		), "This join point's method/function can set the \$settings[comments_users] and \$settings[current_user] items with the correspondent users' data for the object's comments and correspondent logged user data. Additionally can set the following items too: \$settings[add_comment_label], \$settings[add_comment_textarea_place_holder], \$settings[add_comment_button_label], \$settings[add_comment_error_message], \$settings[empty_comments_label], \$settings[style_type], \$settings[class], \$settings[title] and \$settings[add_comment_url]. These items are optional.");
		
		return \CommentUI::getObjectCommentsHtml($EVC, $settings, $settings["object_type_id"], $settings["object_id"], $settings["group"]);
	}
}
?>
