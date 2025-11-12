<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
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
</div>'; function getTutorialHtml($v20f9a15b0d) { $pf8ed4912 = ''; if (!empty($v20f9a15b0d["video"]) || !empty($v20f9a15b0d["items"])) { $ped0a6251 = ''; $pf9ed8697 = ''; $v23ceac96ca = isset($v20f9a15b0d["video"]) ? $v20f9a15b0d["video"] : null; $v6e273317ad = isset($v20f9a15b0d["image"]) ? $v20f9a15b0d["image"] : null; $v9a60301f51 = isset($v20f9a15b0d["items"]) ? $v20f9a15b0d["items"] : null; $pc0148842 = isset($v20f9a15b0d["title"]) ? $v20f9a15b0d["title"] : null; $v0b1f91b475 = isset($v20f9a15b0d["description"]) ? $v20f9a15b0d["description"] : null; if ($v9a60301f51) { $ped0a6251 = 'onClick="toggleSubTutorials(this)"'; $pf9ed8697 = '<span class="icon dropdown_arrow"></span>'; } else $ped0a6251 = 'onClick="openVideoPopup(this)" video_url="' . $v23ceac96ca . '" image_url="' . $v6e273317ad . '"'; $pf8ed4912 = '<li' . ($v9a60301f51 ? ' class="with_sub_tutorials"' : '') . '>
					<div class="tutorial_header" ' . $ped0a6251 . '>
						<div class="tutorial_title"' . ($v0b1f91b475 ? ' title="' . str_replace('"', '&quot;', strip_tags($v0b1f91b475)) . '"' : '') . '><span class="icon video"></span>' . $pc0148842 . $pf9ed8697 . '</div>
						' . ($v0b1f91b475 ? '<div class="tutorial_description">' . $v0b1f91b475 . '</div>' : '') . '
					</div>'; if ($v9a60301f51) { $pf8ed4912 .= '<ul class="sub_tutorials">'; foreach ($v9a60301f51 as $v83cf8e0027) $pf8ed4912 .= getTutorialHtml($v83cf8e0027); $pf8ed4912 .= '</ul>'; } $pf8ed4912 .= '</li>'; } return $pf8ed4912; } ?>
