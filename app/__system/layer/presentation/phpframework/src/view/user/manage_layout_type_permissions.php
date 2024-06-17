<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include $EVC->getUtilPath("UserAuthenticationUIHandler"); include $EVC->getUtilPath("WorkFlowPresentationHandler"); $choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#"; $upload_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/upload_file?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#"; $head = '
<!-- Add MD5 JS File -->
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquery/js/jquery.md5.js"></script>

<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icons CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add MyTree main JS and CSS files -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerymytree/css/style.min.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerymytree/js/mytree.js"></script>

<!-- Add FileManager JS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/file_manager.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/file_manager.js"></script>

<!-- Add Layout CSS and JS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/layout.js"></script>

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/user/user.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/user/user.js"></script>

<script>
var get_layout_type_permissions_url = \'' . $project_url_prefix . 'user/get_layout_type_permissions?layout_type_id=#layout_type_id#\';
'; $head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url, $upload_bean_layer_files_from_file_manager_url); $head .= WorkFlowPresentationHandler::getBusinessLogicBrokersHtml($business_logic_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url); $head .= WorkFlowPresentationHandler::getDataAccessBrokersHtml($data_access_brokers, $choose_bean_layer_files_from_file_manager_url); $head .= '
	var permissions = ' . json_encode($permissions) . ';
	var permission_belong_name = "' . UserAuthenticationHandler::$PERMISSION_BELONG_NAME . '";
	var permission_referenced_name = "' . UserAuthenticationHandler::$PERMISSION_REFERENCED_NAME . '";
	var layer_object_type_id = ' . $layer_object_type_id . ';
	var loaded_layout_type_permissions = {};
	
	$(function() {
		$(".layout_type_permissions_content").tabs();
		
		prepareFileTreeCheckbox( $(".layout_type_permissions_content input[type=checkbox]") );
		
		updateLayoutTypePermissions( $(".layout_type select[name=layout_type_id]")[0] );
	});
</script>'; $main_content = '
<div id="menu">' . UserAuthenticationUIHandler::getMenu($UserAuthenticationHandler, $project_url_prefix, $entity) . '</div>
<div id="content">
	<div class="top_bar">
		<header>
			<div class="title">Manage Layout Type Permissions</div>
			<ul>
				<li class="save" data-title="Save"><a onClick="submitForm(this)"><i class="icon save"></i> Save</a></li>
			</ul>
		</header>
	</div>
	
	<div class="layout_type_permissions_list">
		<form method="post" onSubmit="return saveLayoutTypePermissions();">
			<div class="layout_type">
				<label>Layout Type: </label>
				<select name="type_id" onChange="onChangeLayoutType(this)">'; foreach ($available_types as $tid => $tname) $main_content .= '<option value="' . $tid . '" ' . ($type_id == $tid ? ' selected' : '') . '>' . $tname . '</option>'; $main_content .= '	</select>
				<select name="layout_type_id" onChange="updateLayoutTypePermissions(this)">'; if ($type_id == 0) { $is_single_presentation_layer = count($presentation_projects_by_folders) == 1; foreach ($presentation_projects_by_folders as $layer_label => $projs) { if (!$is_single_presentation_layer) $main_content .= '<optgroup label="' . $layer_label . '">'; $main_content .= getProjectsHtml($projs, $layout_type_id); if (!$is_single_presentation_layer) $main_content .= '</optgroup>'; } } foreach ($layout_types as $lname => $lid) $main_content .= '<option value="' . $lid . '" ' . ($layout_type_id == $lid ? ' selected' : '') . '>' . $lname . '</option>'; $main_content .= '	</select>
			</div>
			
			<div class="layout_type_permissions_content">
				<ul class="tabs">
					<li><a href="#belonging_to_layout">Belonging to Layout</a></li>
					<li><a href="#referenced_in_layout">Referenced in Layout</a></li>
				</ul>
				
				<div id="belonging_to_layout">
					<ul>
				' . getLayersHtml($layers, $layers_props, $layers_object_id, $layers_label, $layer_object_id_prefix, $choose_bean_layer_files_from_file_manager_url, $layer_object_type_id, $permissions[UserAuthenticationHandler::$PERMISSION_BELONG_NAME], "removeAllThatIsFolderFromTree") . '
					</ul>
				</div>
				
				<div id="referenced_in_layout">
					<ul>
				' . getLayersHtml($layers_to_be_referenced, $layers_props, $layers_object_id, $layers_label, $layer_object_id_prefix, $choose_bean_layer_files_from_file_manager_url, $layer_object_type_id, $permissions[UserAuthenticationHandler::$PERMISSION_REFERENCED_NAME], "removeAllThatCannotBeReferencedFromTree") . '
					</ul>
				</div>
				
				<div class="loaded_permissions_by_objects hidden"></div>
			</div>
			<div class="buttons">
				<div class="submit_button">
					<input type="submit" name="save" value="Save" />
				</div>
			</div>
		</form>
	</div>
</div>'; function getProjectsHtml($v12ed481092, $v1a222c94d4, $pdcf670f6 = "") { $pf8ed4912 = ""; if (is_array($v12ed481092)) foreach ($v12ed481092 as $v5c37a7b23d => $pcfd27d54) { if (is_array($pcfd27d54)) $pf8ed4912 .= '<option disabled>' . $pdcf670f6 . $v5c37a7b23d . '</option>' . getProjectsHtml($pcfd27d54, $v1a222c94d4, $pdcf670f6 . "&nbsp;&nbsp;&nbsp;"); else $pf8ed4912 .= '<option value="' . $pcfd27d54 . '" ' . ($v1a222c94d4 == $pcfd27d54 ? ' selected' : '') . '>' . $pdcf670f6 . $v5c37a7b23d . '</option>'; } return $pf8ed4912; } function getLayersHtml($v2635bad135, $v830cc461b7, $pbffdab91, $paeab4070, $v9bfd456213, $pf7b73b3a, $v0a035c60aa, $pb76ee81a, $pf3f2367a) { $pf8ed4912 = ''; foreach ($v2635bad135 as $v43974ff697 => $pfd248cca) { $pf8ed4912 .= '<li id="file_tree_' . $pb76ee81a . '_' . $v43974ff697 . '" class="mytree">
					<label><i class="icon main_node main_node_' . $v43974ff697 . '"></i> ' . strtoupper(str_replace("_", " ", $v43974ff697)) . '</label>
					<ul>'; if ($pfd248cca) foreach ($pfd248cca as $v0a5deb92d8 => $v4a24304713) { $v54307eb686 = $v830cc461b7[$v43974ff697][$v0a5deb92d8]; $v3fab52f440 = "$v9bfd456213/" . $pbffdab91[$v43974ff697][$v0a5deb92d8]; $pf8ed4912 .= '<li data-jstree=\'{"icon":"main_node_' . $v54307eb686["item_type"] . '"}\'>
							<label>
								<input type="checkbox" name="permissions_by_objects[' . $v0a035c60aa . '][' . $v3fab52f440 . '][]" value="' . $pb76ee81a . '" />
								' . $paeab4070[$v43974ff697][$v0a5deb92d8] . '
							</label>'; if ($v43974ff697 == "db_layers") { $pf8ed4912 .= '<ul>'; foreach ($v4a24304713 as $v13eedf3e61 => $v25dfe304be) { $v3fab52f440 = "$v9bfd456213/" . $pbffdab91[$v43974ff697][$v0a5deb92d8] . "/$v13eedf3e61"; $pf8ed4912 .= '<li data-jstree=\'{"icon":"db_driver"}\'>
										<label>
											<input type="checkbox" name="permissions_by_objects[' . $v0a035c60aa . '][' . $v3fab52f440 . '][]" value="' . $pb76ee81a . '" />
											' . $v13eedf3e61 . '
										</label>
									</li>'; } $pf8ed4912 .= '</ul>'; } else { $v6f3a2700dd = $pf7b73b3a; $v6f3a2700dd = str_replace("#bean_name#", $v54307eb686["bean_name"], $v6f3a2700dd); $v6f3a2700dd = str_replace("#bean_file_name#", $v54307eb686["bean_file_name"], $v6f3a2700dd); $v6f3a2700dd = str_replace("#path#", "", $v6f3a2700dd); $pf8ed4912 .= '<ul url="' . $v6f3a2700dd . '" object_id_prefix="' . $v3fab52f440 . '"></ul>'; } $pf8ed4912 .= '</li>'; } $pf8ed4912 .= '	</ul>
					<script>				
						var layerFromFileManagerTree_' . $pb76ee81a . '_' . $v43974ff697 . ' = new MyTree({
							multiple_selection : true,
							toggle_children_on_click : true,
							ajax_callback_before : prepareLayerNodes1,
							ajax_callback_after : ' . $pf3f2367a . ',
							on_select_callback : toggleFileTreeCheckbox,
						});
						layerFromFileManagerTree_' . $pb76ee81a . '_' . $v43974ff697 . '.init("file_tree_' . $pb76ee81a . '_' . $v43974ff697 . '");
					</script>
				</li>'; } return $pf8ed4912; } ?>
