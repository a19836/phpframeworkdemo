<?php
$block_id = $EVC->getCMSLayer()->getCMSBlockLayer()->getBlockIdFromFilePath(__FILE__);//must be the same than this file name.

$block_settings[$block_id] = array(
	"actions" => array(
		array(
			"result_var_name" => "",
			"action_type" => "html",
			"condition_type" => "execute_always",
			"condition_value" => "",
			"action_description" => "",
			"action_value" => array(
				"with_form" => 1,
				"form_id" => "",
				"form_method" => "post",
				"form_class" => "",
				"form_type" => "",
				"form_on_submit" => "",
				"form_action" => "",
				"form_css" => "",
				"form_js" => ""
			)
		)
	),
	"css" => "",
	"js" => ""
);

$EVC->getCMSLayer()->getCMSBlockLayer()->createBlock("form", $block_id, $block_settings[$block_id]);
?>