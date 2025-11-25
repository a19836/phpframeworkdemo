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
 if (!empty($layout_type_id)) { include $EVC->getUtilPath("WorkFlowPresentationHandler"); include $EVC->getUtilPath("BreadCrumbsUIHandler"); $layer_path = isset($layer_path) ? $layer_path : null; $selected_project_id = isset($selected_project_id) ? $selected_project_id : null; $P = isset($P) ? $P : null; $permissions = isset($permissions) ? $permissions : null; $layers_to_be_referenced = isset($layers_to_be_referenced) ? $layers_to_be_referenced : null; $layers_props = isset($layers_props) ? $layers_props : null; $layers_label = isset($layers_label) ? $layers_label : null; $layers_object_id = isset($layers_object_id) ? $layers_object_id : null; $layer_object_id_prefix = isset($layer_object_id_prefix) ? $layer_object_id_prefix : null; $layer_object_type_id = isset($layer_object_type_id) ? $layer_object_type_id : null; $presentation_brokers = isset($presentation_brokers) ? $presentation_brokers : null; $business_logic_brokers = isset($business_logic_brokers) ? $business_logic_brokers : null; $data_access_brokers = isset($data_access_brokers) ? $data_access_brokers : null; $choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#"; $upload_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/upload_file?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#"; $get_file_properties_url = $project_url_prefix . "phpframework/admin/get_file_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&class_name=#class_name#&type=#type#"; $head = '
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

	<!-- Add User CSS and JS -->
	<link rel="stylesheet" href="' . $project_url_prefix . 'css/user/user.css" type="text/css" charset="utf-8" />
	<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/user/user.js"></script>
	
	<!-- Add Local CSS and JS -->
	<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/manage_references.css" type="text/css" charset="utf-8" />
	<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/manage_references.js"></script>

	<script>
	var get_layout_type_permissions_url = \'' . $project_url_prefix . 'user/get_layout_type_permissions?layout_type_id=#layout_type_id#\';
	'; $head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url, $upload_bean_layer_files_from_file_manager_url); $head .= WorkFlowPresentationHandler::getBusinessLogicBrokersHtml($business_logic_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url); $head .= WorkFlowPresentationHandler::getDataAccessBrokersHtml($data_access_brokers, $choose_bean_layer_files_from_file_manager_url); $head .= '
		var permissions = ' . json_encode($permissions) . ';
		var permission_belong_name = "' . UserAuthenticationHandler::$PERMISSION_BELONG_NAME . '";
		var permission_referenced_name = "' . UserAuthenticationHandler::$PERMISSION_REFERENCED_NAME . '";
		var layer_object_type_id = ' . $layer_object_type_id . ';
		var loaded_layout_type_permissions = {};
		var layout_type_id = ' . $layout_type_id . ';
	</script>'; $main_content = ''; if (!empty($_POST) && empty($error_message)) { $on_success_js_func = $on_success_js_func ? $on_success_js_func : "refreshLastNodeParentChilds"; $main_content .= "<script>if (typeof window.parent.$on_success_js_func == 'function') window.parent.$on_success_js_func();</script>"; } $main_content .= '
	<div id="content">
		<div class="top_bar' . ($popup ? " in_popup" : "") . '">
			<header>
				<div class="title" title="' . $path . '">Manage References for project: ' . BreadCrumbsUIHandler::getFilePathBreadCrumbsHtml($layer_path . $selected_project_id, $P) . '</div>
				<ul>
					<li class="save" data-title="Save"><a onclick="submitForm(this)"><i class="icon save"></i> Save</a>
				</ul>
			</header>
		</div>
		<div class="layout_type_permissions_list">
			<form method="post" onSubmit="return saveProjectLayoutTypePermissions();">
				<div class="layout_type_permissions_content">
					<div id="referenced_in_layout">
						<ul>
					' . getLayersHtml($layers_to_be_referenced, $layers_props, $layers_object_id, $layers_label, $layer_object_id_prefix, $choose_bean_layer_files_from_file_manager_url, $layer_object_type_id, isset($permissions[UserAuthenticationHandler::$PERMISSION_REFERENCED_NAME]) ? $permissions[UserAuthenticationHandler::$PERMISSION_REFERENCED_NAME] : null, "removeAllThatCannotBeReferencedFromTree") . '
						</ul>
					</div>
					
					<div class="loaded_permissions_by_objects hidden"></div>
				</div>
			</form>
		</div>
	</div>'; } function getLayersHtml($v2635bad135, $v830cc461b7, $pbffdab91, $paeab4070, $v9bfd456213, $pf7b73b3a, $v0a035c60aa, $pb76ee81a, $pf3f2367a) { $pf8ed4912 = ''; foreach ($v2635bad135 as $v43974ff697 => $pfd248cca) { $pf8ed4912 .= '<li id="file_tree_' . $pb76ee81a . '_' . $v43974ff697 . '" class="mytree">
					<label><i class="icon main_node main_node_' . $v43974ff697 . '"></i> ' . strtoupper(str_replace("_", " ", $v43974ff697)) . '</label>
					<ul>'; if ($pfd248cca) foreach ($pfd248cca as $v0a5deb92d8 => $v4a24304713) { $v54307eb686 = isset($v830cc461b7[$v43974ff697][$v0a5deb92d8]) ? $v830cc461b7[$v43974ff697][$v0a5deb92d8] : null; $v3fab52f440 = "$v9bfd456213/" . (isset($pbffdab91[$v43974ff697][$v0a5deb92d8]) ? $pbffdab91[$v43974ff697][$v0a5deb92d8] : null); $pf8ed4912 .= '<li data-jstree=\'{"icon":"main_node_' . (isset($v54307eb686["item_type"]) ? $v54307eb686["item_type"] : "") . '"}\'>
							<label>
								<input type="checkbox" name="permissions_by_objects[' . $v0a035c60aa . '][' . $v3fab52f440 . '][]" value="' . $pb76ee81a . '" />
								' . (isset($paeab4070[$v43974ff697][$v0a5deb92d8]) ? $paeab4070[$v43974ff697][$v0a5deb92d8] : "") . '
							</label>'; if ($v43974ff697 == "db_layers") { $pf8ed4912 .= '<ul>'; foreach ($v4a24304713 as $v13eedf3e61 => $v25dfe304be) { $v3fab52f440 = "$v9bfd456213/" . (isset($pbffdab91[$v43974ff697][$v0a5deb92d8]) ? $pbffdab91[$v43974ff697][$v0a5deb92d8] : "") . "/$v13eedf3e61"; $pf8ed4912 .= '<li data-jstree=\'{"icon":"db_driver"}\'>
										<label>
											<input type="checkbox" name="permissions_by_objects[' . $v0a035c60aa . '][' . $v3fab52f440 . '][]" value="' . $pb76ee81a . '" />
											' . $v13eedf3e61 . '
										</label>
									</li>'; } $pf8ed4912 .= '</ul>'; } else { $v6f3a2700dd = $pf7b73b3a; $v6f3a2700dd = str_replace("#bean_name#", isset($v54307eb686["bean_name"]) ? $v54307eb686["bean_name"] : null, $v6f3a2700dd); $v6f3a2700dd = str_replace("#bean_file_name#", isset($v54307eb686["bean_file_name"]) ? $v54307eb686["bean_file_name"] : null, $v6f3a2700dd); $v6f3a2700dd = str_replace("#path#", "", $v6f3a2700dd); $pf8ed4912 .= '<ul url="' . $v6f3a2700dd . '" object_id_prefix="' . $v3fab52f440 . '"></ul>'; } $pf8ed4912 .= '</li>'; } $pf8ed4912 .= '	</ul>
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
