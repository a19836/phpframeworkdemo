<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.util.web.html.HtmlFormHandler"); include $EVC->getUtilPath("UserAuthenticationUIHandler"); $head = '
<!-- Add MD5 JS File -->
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquery/js/jquery.md5.js"></script>

<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icons CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/user/user.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/user/user.js"></script>
'; $form_settings = array( "with_form" => 1, "form_id" => "", "form_method" => "post", "form_class" => "", "form_on_submit" => "", "form_action" => "", "form_containers" => array( 0 => array( "container" => array( "class" => "form_fields", "previous_html" => "", "next_html" => "", "elements" => array( 0 => array( "field" => array( "class" => "form_field hidden", "input" => array( "type" => "hidden", "name" => "object_type_data[object_type_id]", "value" => "#object_type_id#", ) ) ), 1 => array( "field" => array( "class" => "form_field", "label" => array( "value" => "Name: ", ), "input" => array( "type" => "text", "name" => "object_type_data[name]", "value" => "#name#", "extra_attributes" => array( 0 => array( "name" => "allowNull", "value" => "false" ), 1 => array( "name" => "validationMessage", "value" => "Name cannot be undefined!" ) ), ) ) ), ) ) ), 1 => array( "container" => array( "class" => "buttons", "elements" => array( 0 => array( "field" => array( "class" => "submit_button", "input" => array( "type" => "submit", "name" => "save", "value" => "Save", "class" => "save", ) ) ), 1 => array( "field" => array( "class" => "submit_button" . ($object_type_id ? "" : " hidden"), "input" => array( "type" => "submit", "name" => "delete", "value" => "Delete", "class" => "delete", "extra_attributes" => array( 0 => array( "name" => "confirmation", "value" => true ), 1 => array( "name" => "confirmationMessage", "value" => "Do you wish to remove this object type?" ) ), ) ) ) ) ) ) ) ); $main_content = '
<div id="menu">' . UserAuthenticationUIHandler::getMenu($UserAuthenticationHandler, $project_url_prefix, $entity) . '</div>
<div id="content">
	<div class="edit_object_type">
		<div class="top_bar">
			<header>
				<div class="title">' . ($object_type_id ? 'Edit' : 'Add') . ' Object Type</div>
				<ul>
					<li class="delete" data-title="Delete"><a onClick="submitForm(this, \'delete\')"><i class="icon delete"></i> Delete</a></li>
					<li class="save" data-title="Save"><a onClick="submitForm(this, \'save\')"><i class="icon save"></i> Save</a></li>
				</ul>
			</header>
		</div>'; $main_content .= HtmlFormHandler::createHtmlForm($form_settings, $object_type_data); $main_content .= '</div>
</div>'; ?>
