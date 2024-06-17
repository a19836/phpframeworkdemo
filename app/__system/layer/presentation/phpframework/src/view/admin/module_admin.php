<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$head = '
<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/module_admin.css" type="text/css" charset="utf-8" />
'; $main_content = '
<div class="top_bar' . ($popup ? ' in_popup' : '') . '">
	<header>
		<div class="title">"' . ucwords($group_module_id) . '" Module Admin Panel</div>
	</header>
</div>
<div class="module_admin">
	<label>Please choose a project:</label>
	
	<form method="post">
		<select name="project">'; if ($projects) { $previous_folder = null; foreach ($projects as $project_name => $project) { if ($project["item_type"] != "project_common") { $project_folder = dirname($project_name); $project_folder = $project_folder == "." ? "" : $project_folder; if ($project_folder && $project_folder != $previous_folder) { $main_content .= '<option disabled>' . str_repeat("&nbsp;&nbsp;&nbsp;", substr_count($project_folder, '/')) . basename($project_folder) . '</option>'; $previous_folder = $project_folder; } $main_content .= '<option value="' . $project_name . '"' . ($selected_project == $project_name ? ' selected' : '') . '>' . str_repeat("&nbsp;&nbsp;&nbsp;", substr_count($project_name, '/')) . $project_name . '</option>'; } } } $main_content .= '</select>
		
		<input class="button" type="submit" value="Go to Module Admin Panel" name="submit">
	</form>
</div>'; ?>
