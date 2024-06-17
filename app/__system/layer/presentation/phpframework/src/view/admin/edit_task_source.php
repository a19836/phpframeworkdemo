<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$edit_type_label = str_replace("_", " ", $edit_type); $head = '
<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icon CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/edit_task_source.css" type="text/css" charset="utf-8" />
'; $main_content = '
<div class="top_bar' . ($popup ? " in_popup" : "") . '">
	<header>
		<div class="title">Edit ' . ucwords($edit_type_label) . '</div>
	</header>
</div>

<div class="invalid">Error: Could not detect ' . $edit_type . '\'s path.' . ($error_message ? "<br/><br/>" . $error_message : "") . '</div>'; ?>
