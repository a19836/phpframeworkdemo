<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<?php
$menu_item_html = '
<li class="item">
	<div class="menu_label">
		<label>Label:</label>
		<input class="module_settings_property" type="text" name="#prefix#[label]" value="#label#" />
		<span class="icon maximize" onClick="toggleMenuItemProperties(this)" title="Toggle properties for this menu item">Toggle other properties</span>
		<span class="icon add" onClick="addSubItem(this)" title="Add new menu sub item">Add new item</span>
		<span class="icon up" onClick="moveUpMenuItem(this)" title="Move up item">Move up</span>
		<span class="icon down" onClick="moveDownMenuItem(this)" title="Move down item">Move down</span>
		<span class="icon delete" onClick="deleteMenuItemProperties(this)" title="Delete this menu item">Delete item</span>
	</div>
	<div class="menu_attrs">
		<label>Attrs:</label>
		<input class="module_settings_property" type="text" name="#prefix#[attrs]" value="#attrs#" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="menu_url">
		<label>URL:</label>
		<input class="module_settings_property" type="text" name="#prefix#[url]" value="#url#" />
		<span class="icon search search_page_url" onClick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
	</div>
	<div class="menu_title">
		<label>Title:</label>
		<input class="module_settings_property" type="text" name="#prefix#[title]" value="#title#" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="menu_class">
		<label>Class:</label>
		<input class="module_settings_property" type="text" name="#prefix#[class]" value="#class_name#" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div style="clear:left; float:none;"></div>
	<div class="menu_previous_html">
		<label>Previous Html:</label>
		<textarea class="module_settings_property" type="text" name="#prefix#[previous_html]">#previous_html#</textarea>
	</div>
	<div class="menu_next_html">
		<label>Next Html:</label>
		<textarea class="module_settings_property" type="text" name="#prefix#[next_html]">#next_html#</textarea>
	</div>
	<div style="clear:left; float:none;"></div>
	<ul class="items hidden" prefix="#prefix#[menus]"></ul>
</li>';

echo '<script>
var menu_item_html = \'' . addcslashes(str_replace("\n", "", $menu_item_html), "\\'") . '\'
</script>';
?>

<div class="menu_settings">
	<div class="menu_type">
		<label>Menu Type:</label>
		<select class="module_settings_property" name="type">
			<option value="">-- None --</option>
			<option value="horizontal">Horizontal</option>
			<option value="horizontal_simple_dropdown">Horizontal - Simple Dropdown</option>
			<option value="horizontal_superfish_basic">Horizontal - SuperFish Basic</option>
			<option value="horizontal_superfish_navbar">Horizontal - SuperFish NavBar</option>
			<option value="vertical">Vertical</option>
			<option value="vertical_superfish">Vertical - SuperFish</option>
			<option value="accordion_clean">Accordion - Clean</option>
			<option value="accordion_blue">Accordion - Blue</option>
			<option value="accordion_grey">Accordion - Grey</option>
			<option value="accordion_graphite">Accordion - Graphite</option>
			<option value="accordion_black">Accordion - Black</option>
			<option value="accordion">Accordion - No Format</option>
		</select>
	</div>
	<div class="menu_class">
		<label>Menu Class:</label>
		<input class="module_settings_property" type="text" name="class" value="" value_type="string" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="menu_title">
		<label>Menu Title:</label>
		<input class="module_settings_property" type="text" name="title" value="" value_type="string" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="menu_items">
		<label>Menu Items:</label>
		<select class="module_settings_property items_type" name="items_type" onChange="onChangeMenuItemsType(this)">
			<option value="">Manual</option>
			<option value="from_db">From DB</option>
		</select>
		
		<span class="icon add" onClick="addMainItem(this)" title="Add new menu item">Add new item</span>
		
		<ul class="items hidden" prefix="menus"></ul>
		
		<div class="query_type">
			<label>Query Type:</label>
			<select class="module_settings_property" name="menu_query_type" onChange="onChangeMenuItemsQueryType(this)">
				<option value="selected_menu">Selected menu</option>
				<option value="first_menu_by_tag_and">First menu with all Tags bellow</option>
				<option value="first_menu_by_tag_or">First menu with at least one Tag bellow</option>
				<option value="first_menu_by_parent">First menu by parent</option>
				<option value="first_menu_by_parent_group">First menu by parent group</option>
				<option value="user_defined">Manual group id</option>
			</select>
		</div>
		
		<div class="selected_menu">
			<label>Menus:</label>
			<select class="module_settings_property" name="menu_group_id">
			</select>
		</div>
		
		<div class="menu_by_user_defined">
			<label>Menu Id:</label>
			<input class="module_settings_property" name="menu_group_id"/>
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
		
		<div class="menu_by_tag">
			<label>Tags:</label>
			<input class="module_settings_property" name="tags"/>
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
		
		<div class="menu_by_parent">
			<div class="menu_parent_object_type_id">
				<label>Parent Type:</label>
				<select class="module_settings_property" name="object_type_id">
				</select>
			</div>
			<div class="menu_parent_object_id">
				<label>Parent Id:</label>
				<input type="text" class="module_settings_property" name="object_id" value="" />
				<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
			</div>
			<div class="menu_parent_group">
				<label>Group Id:</label>
				<input type="text" class="module_settings_property" name="group" value="" />
			</div>
		</div>
		
		<div class="menu_items_default_settings">
			<label>Menu Items Default Settings: </label>
			
			<div class="item_label">
				<label>Label:</label>
				<input class="module_settings_property" type="text" name="item_label" value="#label#" />
				<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
			</div>
			<div class="item_attrs">
				<label>Attrs:</label>
				<input class="module_settings_property" type="text" name="item_attrs" value="#attrs#" />
				<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
			</div>
			<div class="item_url">
				<label>Url:</label>
				<input class="module_settings_property" type="text" name="item_url" value="{$project_url_prefix}#url#" />
				<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
			</div>
			<div class="item_title">
				<label>Title:</label>
				<input class="module_settings_property" type="text" name="item_title" value="#title#" />
				<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
			</div>
			<div class="item_class">
				<label>Class:</label>
				<input class="module_settings_property" type="text" name="item_class" value="#class#" />
				<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
			</div>
			<div class="clear"></div>
			<div class="item_previous_html">
				<label>Previous Html:</label>
				<textarea class="module_settings_property" type="text" name="item_previous_html">#previous_html#</textarea>
			</div>
			<div class="item_next_html">
				<label>Next Html:</label>
				<textarea class="module_settings_property" type="text" name="item_next_html">"#next_html#"</textarea>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	
<?php 
	echo CommonModuleSettingsUI::getStyleFieldsHtml(); 
?>
	<div class="template_type">
		<label>Template Type:</label>
		<select class="module_settings_property" name="template_type" onChange="onChangeTemplateType(this)">
			<option value="">-- default --</option>
			<option value="user_defined">User defined</option>
		</select>
	</div>
	
	<div class="els">
		<a class="import_template_ptl" href="javascript:void(0);" onClick="importTemplatePTLCode(this, 'menu/show_menu')">Import Template PTL</a>
		
		<ul class="els_tabs"></ul> <!-- WE must leave this tabs here, bc the common/settings are initing the tabs, and if this tabs will not be here, it will get the next ul which will be the .menu-widgets and then will mess the ui bc of the tabs css-->
		
		<div id="els_ptl" class="ptl">
			<!-- LAYOUT UI EDITOR -->
			<div class="layout-ui-editor els_ui reverse fixed-side-properties hide-template-widgets-options">
				<ul class="menu-widgets hidden">
					<? 
					$common_webroot_path = $EVC->getWebrootPath($EVC->getCommonProjectName());
					$ui_menu_widgets_html = WorkFlowPresentationHandler::getUIEditorWidgetsHtml($common_webroot_path, $project_common_url_prefix, $webroot_cache_folder_path, $webroot_cache_folder_url, array("avoided_widgets" => array("php")));
					$ui_menu_widgets_html .= WorkFlowPresentationHandler::getExtraUIEditorWidgetsHtml($common_webroot_path, $EVC->getViewsPath() . "presentation/common_editor_widget/", $webroot_cache_folder_path, $webroot_cache_folder_url);
					$ui_menu_widgets_html .= WorkFlowPresentationHandler::getUserUIEditorWidgetsHtml($common_webroot_path, $layout_ui_editor_user_widget_folders_path, $webroot_cache_folder_path, $webroot_cache_folder_url);
					
					echo $ui_menu_widgets_html;
					?>
				</ul>
				<div class="template-source"><textarea></textarea></div>
			</div>
			
			<div class="ptl_external_vars array_items" array_parent_name="ptl[external_vars]"></div>
			<textarea class="ptl_code hidden" name="ptl[code]" value_type="string"></textarea>
		</div>
	</div>
	
	<div class="menu_background_color">
		<label>Menu Background Color:</label>
		<input class="module_settings_property" type="text" name="menu_background_color" value="" value_type="string" />
		<input type="color" />
	</div>
	<div class="menu_text_color">
		<label>Menu Text Color:</label>
		<input class="module_settings_property" type="text" name="menu_text_color" value="" value_type="string" />
		<input type="color" />
	</div>
	<div class="menu_background_image">
		<label>Menu Background Image:</label>
		<input class="module_settings_property" type="text" name="menu_background_image" value="" value_type="string" />
		<span class="icon search search_page_url" onClick="onIncludeImageUrlTaskChooseFile(this)" title="Search Page">Search image</span>
	</div>
	<div class="sub_menu_background_color">
		<label>Sub-Menu Background Color:</label>
		<input class="module_settings_property" type="text" name="sub_menu_background_color" value="" value_type="string" />
		<input type="color" />
	</div>
	<div class="sub_menu_text_color">
		<label>Sub-Menu Text Color:</label>
		<input class="module_settings_property" type="text" name="sub_menu_text_color" value="" value_type="string" />
		<input type="color" />
	</div>
	<div class="sub_menu_background_image">
		<label>Sub-Menu Background Image:</label>
		<input class="module_settings_property" type="text" name="sub_menu_background_image" value="" value_type="string" />
		<span class="icon search search_page_url" onClick="onIncludeImageUrlTaskChooseFile(this)" title="Search Page">Search image</span>
	</div>
	
<?php 
	echo CommonModuleSettingsUI::getCssFieldsHtml();
	echo CommonModuleSettingsUI::getJsFieldsHtml();
?>
</div>
