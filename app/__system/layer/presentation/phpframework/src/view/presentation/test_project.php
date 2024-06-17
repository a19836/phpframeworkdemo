<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include $EVC->getUtilPath("BreadCrumbsUIHandler"); function getVarsHtml($v3fb9f41470) { $v761f4d757f = $_POST ? $_POST[$v3fb9f41470 . '_vars'] : null; $v6c3cc118c9 = ""; if ($v761f4d757f) foreach ($v761f4d757f as $v43dd7d0051 => $v847e7d0a83) if ($v847e7d0a83["name"] || $v847e7d0a83["value"]) $v6c3cc118c9 .= getVarHtml($v3fb9f41470, $v43dd7d0051, $v847e7d0a83["name"], $v847e7d0a83["value"]); $pf8ed4912 = '<div class="vars ' . $v3fb9f41470 . '_vars">
		<label>' . ucfirst($v3fb9f41470) . ' Variables:</label>
		<table>
			<thead>
				<tr>
					<th class="name">Variable Name</th>
					<th class="value">Variable Name</th>
					<th class="actions">
						<i class="icon add" onClick="addVar(this, \'' . $v3fb9f41470 . '\');">Add</i>
					</th>
				</tr>
			</thead>
			<tbody index_prefix="' . $v3fb9f41470 . '_vars">
				<tr class="no_vars"' . ($v6c3cc118c9 ? ' style="display:none;"' : '') . '><td colspan="3">No vars...</td></tr>
				' . $v6c3cc118c9 . '
			</tbody>
		</table>
	</div>'; return $pf8ed4912; } function getVarHtml($v3fb9f41470, $v8a4df75785, $v5e813b295b, $v67db1bd535) { return '<tr>
		<td class="name"><input type="text" name="' . $v3fb9f41470 . '_vars[' . $v8a4df75785 . '][name]" placeHolder="write here your ' . $v3fb9f41470 . ' var name" value="' . $v5e813b295b . '" /></td>
		<td class="value"><input type="text" name="' . $v3fb9f41470 . '_vars[' . $v8a4df75785 . '][value]" placeHolder="write here your ' . $v3fb9f41470 . ' var value" value="' . $v67db1bd535 . '" /></td>
		<td class="actions"><i class="icon delete" onClick="removeVar(this)"></i></td>
	</tr>'; } $head = '
<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icons CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add PHP CODE CSS -->
<link rel="stylesheet" href="http://jplpinto.localhost/__system/css/edit_php_code.css" type="text/css" charset="utf-8" />

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/test_project.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/test_project.js"></script>

<script>
	var vars_html = \'' . addcslashes(str_replace("\n", "", getVarHtml("#type#", "#index#", "#name#", "#value#")), "'") . '\';
</script>'; $main_content = '
<div class="top_bar' . ($popup ? " in_popup" : "") . '">
	<header>
		<div class="title" title="' . $path . '">Test Page: <div class="breadcrumbs">' . BreadCrumbsUIHandler::getFilePathBreadCrumbsItemsHtml($path, null, true) . '</div></div>
		<ul>
			<li class="toggle_settings" data-title="Toggle Settings"><a onClick="toggleSettings(this)"><i class="icon maximize"></i></a></li>
		</ul>
	</header>
</div>
<div class="test_project' . ($popup ? " in_popup" : "") . '">
	<form method="post">
		' . getVarsHtml("get") . getVarsHtml("post") . '
		
		<div class="buttons">
			<input type="submit" name="test" value="Test"/>
		</div>
	</form>

	<iframe src="' . $view_project_url . '"></iframe>
</div>'; ?>
