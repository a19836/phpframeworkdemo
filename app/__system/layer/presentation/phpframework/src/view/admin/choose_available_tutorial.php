<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$head = '
<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Filemanager CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/file_manager.css" type="text/css" charset="utf-8" />

<!-- Icons CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS and JS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/layout.js"></script>

<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/choose_available_tutorial.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/choose_available_tutorial.js"></script>

<script>
var is_popup = ' . ($popup ? 1 : 0) . ';
</script>'; $main_content = '<div class="choose_available_tutorial ' . ($popup ? " in_popup" : "") . '">
	<div class="title' . ($popup ? " inside_popup_title" : "") . '">Video Tutorials</div>
	<div class="toggle_advanced_videos"><a href="javascript:void(0)" onClick="toggleAdvancedTutorials(this);">Show Advanced Videos</a></div>
	<ul class="simple_tutorials">'; foreach ($simple_tutorials as $tutorial) $main_content .= getTutorialHtml($tutorial); $main_content .= '<li class="next"><a href="javascript:void(0)" onClick="toggleAdvancedTutorials(this);">Next you should watch the videos from the Advanced Tutorials</a>.</li>
	</ul>
	<ul class="advanced_tutorials">'; foreach ($advanced_tutorials as $tutorial) $main_content .= getTutorialHtml($tutorial); $main_content .= '
	</ul>
	
	<div class="myfancypopup with_title show_video_popup">
		<div class="title"></div>
		<div class="content">
			<div class="video">
				<iframe width="560" height="315" title="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
			
			<div class="details">
				<img class="image" alt="Card image cap" onError="$(this).hide()">
				<div class="description"></div>
			</div>
		</div>
	</div>
</div>'; function getTutorialHtml($v20f9a15b0d) { if ($v20f9a15b0d["video"] || $v20f9a15b0d["items"]) { $ped0a6251 = ''; $pf9ed8697 = ''; if ($v20f9a15b0d["items"]) { $ped0a6251 = 'onClick="toggleSubTutorials(this)"'; $pf9ed8697 = '<span class="icon dropdown_arrow"></span>'; } else $ped0a6251 = 'onClick="openVideoPopup(this)" video_url="' . $v20f9a15b0d["video"] . '" image_url="' . $v20f9a15b0d["image"] . '"'; $pf8ed4912 = '<li' . ($v20f9a15b0d["items"] ? ' class="with_sub_tutorials"' : '') . '>
					<div class="tutorial_header" ' . $ped0a6251 . '>
						<div class="tutorial_title"' . ($v20f9a15b0d["description"] ? ' title="' . str_replace('"', '&quot;', strip_tags($v20f9a15b0d["description"])) . '"' : '') . '><span class="icon video"></span>' . $v20f9a15b0d["title"] . $pf9ed8697 . '</div>
						' . ($v20f9a15b0d["description"] ? '<div class="tutorial_description">' . $v20f9a15b0d["description"] . '</div>' : '') . '
					</div>'; if ($v20f9a15b0d["items"]) { $pf8ed4912 .= '<ul class="sub_tutorials">'; foreach ($v20f9a15b0d["items"] as $v83cf8e0027) $pf8ed4912 .= getTutorialHtml($v83cf8e0027); $pf8ed4912 .= '</ul>'; } $pf8ed4912 .= '</li>'; } return $pf8ed4912; } ?>
