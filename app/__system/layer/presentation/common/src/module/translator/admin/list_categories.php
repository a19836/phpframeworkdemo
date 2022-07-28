<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("translator/admin/TranslatorAdminUtil", $common_project_name);
	
	$TranslatorAdminUtil = new TranslatorAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$categories = TranslatorUtil::getCategories($PEVC);
	
	$head = '<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerymytree/css/style.min.css" type="text/css" charset="utf-8" />
	<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerymytree/js/mytree.js"></script>
	
	<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_categories.css" type="text/css" charset="utf-8" />
	<script type="text/javascript" src="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_categories.js"></script>';
	$menu_settings = $TranslatorAdminUtil->getMenuSettings();
	$main_content = '
	<div class="module_list">
		<div class="main_title">List Categories</div>
		<div id="file_tree">
			<a class="jstree-anchor">
				<i class="jstree-icon default" role="presentation"></i>
				<label>
					Default
					<span class="icon view" onClick="document.location=\'' . $CommonModuleAdminUtil->getAdminFileUrl("list_languages") . 'category=\';" title="View Default Category Languages"></span>
				</label>
			</a>
			' . printCategoriesTree($categories, $CommonModuleAdminUtil) . '
		</div>
	</div>';
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);

function printCategoriesTree($categories, $CommonModuleAdminUtil) {
	$html = '';
	
	if ($categories) {
		$html .= '<ul>';
		foreach ($categories as $category => $sub_categories) {
			$parent = strpos($category, "/") !== false ? dirname($category) : "";
			$category_name = basename($category);
			$sub_html = $sub_categories ? printCategoriesTree($sub_categories, $CommonModuleAdminUtil) : '';
			
			$html .= '
			<li class="jstree-open">
				<label>
					' . $category_name . '
					<span class="icon edit" onClick="document.location=\'' . $CommonModuleAdminUtil->getAdminFileUrl("edit_category") . 'parent=' . $parent . '&category=' . $category_name . '\';" title="Edit"></span>
					<span class="icon delete" onclick="deleteItem(this, \'' . $CommonModuleAdminUtil->getAdminFileUrl("delete_category") . 'category=' . $category . '\')" title="Remove"></span>
					<span class="icon add" onClick="document.location=\'' . $CommonModuleAdminUtil->getAdminFileUrl("edit_category") . 'parent=' . $category . '\';" title="Add"></span>
					<span class="icon view" onClick="document.location=\'' . $CommonModuleAdminUtil->getAdminFileUrl("list_languages") . 'category=' . $category . '\';" title="View Category Languages"></span>
				</label>
				' . $sub_html . '
			</li>';
		}
		$html .= '</ul>';
	}
	
	return $html;
}
?>
