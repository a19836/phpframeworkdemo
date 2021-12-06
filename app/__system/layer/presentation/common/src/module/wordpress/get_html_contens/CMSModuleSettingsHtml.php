<?php
$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/init_settings", $common_project_name);
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

$installed_wordpress_folders_name = CMSPresentationLayerHandler::getWordPressInstallationsFoldersName($PEVC);
$wordpress_url_prefix = getProjectCommonUrlPrefix($PEVC) . WordPressUrlsParser::WORDPRESS_FOLDER_PREFIX . "/#installation_name#/";
$default_wordpress_installation_name = $GLOBALS["default_db_driver"];

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);

$wordpress_installation_admin_login_url_prefix = $project_url_prefix . "phpframework/cms/wordpress/admin_login?bean_name=" . $_GET["bean_name"] . "&bean_file_name=" . $_GET["bean_file_name"] . "&path=" . $_GET["path"] . "&db_driver=#installation_name#&wordpress_admin_file_to_open=options-permalink.php";


function getProjectCommonUrlPrefix($EVC) {
	include $EVC->getConfigPath("config");
	return $project_common_url_prefix;
}

function getJSExecutableCodeWithWordPressUrl() {
	return "<script>
if (typeof $ == 'undefined' && typeof jQuery != 'undefined') //wordpress does not defined the $ var, so we need to do it manually
	$ = jQuery;

var html_parent_elm = document.querySelectorAll('.module_wordpress');
html_parent_elm = html_parent_elm.length > 0 ? html_parent_elm[ html_parent_elm.length - 1 ] : null;

var from_url = ''; //wordpress original url. This is replaced according with the db driver selection...
var to_url = window.location.protocol + '//' + window.location.hostname + window.location.pathname;
to_url += to_url.substr(to_url.length - 1) == '/' ? '' : '/';

var from_url_path = getURLPathName(from_url);
var to_url_path = getURLPathName(to_url);

//setupJqueryAjax();
//convertElementsWordPressOriginalUrls(html_parent_elm, from_url, to_url);
//convertElementsWordPressOriginalUrls(html_parent_elm, from_url_path, to_url_path);
</script>";
}

function getJSFunctionsCodeWithWordPressUrl() {
	return "
function setupJqueryAjax() {
	if (typeof jQuery != 'undefined') {
		if (!$) //wordpress does not defined the $ var, so we need to do it manually
			$ = jQuery;
		
		$.ajaxSetup({
			dataFilter: function(rawResponseData, dataType) {
				if (rawResponseData) {
					var response_parsed = false;
					
					if ((dataType && dataType.toLowerCase() == 'json') || (rawResponseData.substr(0) == '{' && rawResponseData.substr(rawResponseData.length - 1) == '}')) {
						try {
							var obj = JSON.parse(rawResponseData);
							
							if (obj) {
								obj = convertWordPressOriginalUrls(obj, from_url, to_url);
								rawResponseData = JSON.stringify(obj);
								
								response_parsed = true;
							}
						}
						catch(e) {}
					}
					
					if (!response_parsed) 
						rawResponseData = convertWordPressOriginalUrls(rawResponseData, from_url, to_url);
				}
				
				return rawResponseData;
			},
		});
	}
}

function convertWordPressOriginalUrls(obj, from_url, to_url) {
	if ($.isPlainObject(obj) || $.isArray(obj))
		$.each(obj, function(prop_key, prop_value) {
			obj[prop_key] = convertWordPressOriginalUrls(prop_value, from_url, to_url);
		});
	else if (obj) {
		obj = convertStringWordPressOriginalUrls(obj, from_url, to_url);
		obj = convertStringWordPressOriginalUrls(obj, from_url.replace(/\\\\//g, '\\\\\\\\'), to_url.replace(/\\\\//g, '\\\\\\\\'));
	}
	
	return obj;
}

function convertStringWordPressOriginalUrls(str, from_url, to_url) {
	if (str) {
		var re = new RegExp(from_url);
		var from_url_length = from_url.length;
		var str_length = str.length;
		var result, index, delimiters, char;
		var control_index = -1;
		
		while (result = re.exec(str)) {
			index = result['index'];
			
			if (index >= 0) {
				if (control_index >= index) //avoids infinit loops
					break;
				
				control_index = index;
				
				//prepare delimiter
				delimiters = [' ', \"\\\\t\", \"\\\\n\"];
				
				for (var i = index; i >= 0; i--) {
					char = str[i];
					
					if (char == '\"' || char == \"'\") {
						delimiters = [char];
						break;
					}
					else if (!('' + char).match(/\\\\s+/))
						break;
				}
				
				//prepare full_url
				for (var i = index + from_url_length; i < str_length; i++)
					if ($.inArray(str[i], delimiters) !== -1)
						break;
				
				var full_url = str.substr(index, i - index);
				var is_php_file = isPHPFileUrl(full_url);
				
				//replace full_url by new_url
				if (is_php_file || !isFileUrl(full_url)) {
					var new_url = to_url + '?' + (is_php_file ? 'wp_url' : 'wp_file') + '=' + full_url.substr(from_url_length).replace('?', '&');
					
					str = str.replace(full_url, new_url);
					str_length = str.length;
				}
			}
			else
				break;
		}
	}
	
	return str;
}

function convertElementsWordPressOriginalUrls(html_parent_elm, from_url, to_url) {
	if (from_url)
		html_parent_elm.children().each(function(idx, child) {
			if (child.attributes)
				for (var i = 0, l = child.attributes.length; i < l; i++) {
					var attrib = child.attributes[i];
					
					if (attrib.value && ('' + attrib.value).startsWith(from_url)) {
						var is_php_file = isPHPFileUrl(attrib.value);
						
						if (is_php_file || !isFileUrl(attrib.value)) {
							var new_attr_value = attrib.value.substr(from_url.length);
							var value = to_url + '?' +  (is_php_file ? 'wp_file' : 'wp_url') + '=' + ('' + new_attr_value).replace('?', '&'); //new_attr_value could be a numeric value
							
							child.setAttribute(attrib.name, value);
						}
					}
				}
			
			convertElementsWordPressOriginalUrls($(child), from_url, to_url);
		});
}

function isPHPFileUrl(url) {
	if (url) {
		var pn = getURLPathName(url);
		
		if (pn) 
			return pn.substr(pn.length - 4).toLowerCase() == '.php';
	}
	
	return false;
}

function isFileUrl(url) {
	if (url) {
		var pn = getURLPathName(url);
		
		if (pn) {
			if (pn.substr(pn.length - 1) == '/')
				return false;
			else if (pn.indexOf('/wp-content/uploads/') != -1)
				return true;
			
			var parts = pn.split('/');
			var last = parts[parts.length - 1];
			var pos = last.lastIndexOf('.');
			var extension = pos != -1 ? last.substr(pos + 1) : '';
			
			return extension && extension.length < 4;
		}
	}
	
	return false;
}

//for urls with http:// or without
function getURLPathName(url) {
	if (url) {
		var path_name = url.replace('://', '');
		var pos = path_name.indexOf('/');
		
		if (pos == -1)
			return null;
		
		path_name = path_name.substr(pos + 1);
		
		pos = path_name.indexOf('?');
		if (pos !== -1)
			path_name = path_name.substr(0, pos);
		
		pos = path_name.indexOf('#');
		if (pos !== -1)
			path_name = path_name.substr(0, pos);
		
		return path_name;
	}
	
	return null;
}";
}

function getBlockTypeInputHtml() {
	return '<input type="hidden" class="module_settings_property" name="blocks[#idx#][block_type]" value="#block_type#">';
}

function getBlockTypePreviousHtml() {
	return '<div class="previous_html">
			<label>Previous Html:</label>
			<textarea class="html"></textarea>
			<textarea class="module_settings_property" name="blocks[#idx#][previous_html]"></textarea>
		</div>';
}

function getBlockTypeNextHtml() {
	return '<div class="next_html">
			<label>Next Html:</label>
			<textarea class="html"></textarea>
			<textarea class="module_settings_property" name="blocks[#idx#][next_html]"></textarea>
		</div>';
}

function getBlockTypeFullPageHtml() {
	return '
		<div class="convert_html_into_inner_html" title="If Html contains the html, head, meta, title and body tags, remove them and return their contents html">
			<label>Convert Html into inner Html:</label>
			<input type="checkbox" class="module_settings_property" name="blocks[#idx#][convert_html_into_inner_html]" value="1" />
		</div>
		<div class="exclude_theme_before_header">
			<label>Exclude Before Header:</label>
			<input type="checkbox" class="module_settings_property" name="blocks[#idx#][exclude_theme_before_header]" value="1" />
		</div>
		<div class="exclude_theme_header">
			<label>Exclude Header:</label>
			<input type="checkbox" class="module_settings_property" name="blocks[#idx#][exclude_theme_header]" value="1" />
		</div>
		<div class="exclude_theme_side_bars">
			<label>Exclude Side Bars:</label>
			<input type="checkbox" class="module_settings_property" name="blocks[#idx#][exclude_theme_side_bars]" value="1" />
		</div>
		<div class="exclude_theme_menus">
			<label>Exclude Menus:</label>
			<input type="checkbox" class="module_settings_property" name="blocks[#idx#][exclude_theme_menus]" value="1" />
		</div>
		<div class="exclude_theme_comments">
			<label>Exclude Comments:</label>
			<input type="checkbox" class="module_settings_property" name="blocks[#idx#][exclude_theme_comments]" value="1" />
		</div>
		<div class="exclude_theme_footer">
			<label>Exclude Footer:</label>
			<input type="checkbox" class="module_settings_property" name="blocks[#idx#][exclude_theme_footer]" value="1" />
		</div>
		<div class="exclude_theme_after_footer">
			<label>Exclude After Footer:</label>
			<input type="checkbox" class="module_settings_property" name="blocks[#idx#][exclude_theme_after_footer]" value="1" />
		</div>';
}

function getBlockTypeContentHtml($type) {
	return '
		<div class="html_type">
			<label>Html Type:</label>
			<select class="module_settings_property" name="blocks[#idx#][html_type]" onChange="onChangeHtmlType(this)">
				<option value="">All Html</option>
				<option value="only_css">Only Show CSS</option>
				<option value="only_js">Only Show JS</option>
				<option value="only_css_and_js">Only Show CSS and JS</option>
				
				' . ($type == "header" || $type == "footer" ? '
					<option value="only_content_parents">Only Content\'s Html Parents</option>
					<option value="only_content_parents_and_css">Only Content\'s Html Parents and CSS</option>
					<option value="only_content_parents_and_js">Only Content\'s Html Parents and JS</option>
					<option value="only_content_parents_and_css_and_js">Only Content\'s Html Parents, CSS and JS</option>
				' : '') . '
			</select>
		</div>
		<div class="convert_html_into_inner_html" title="If Html contains the html, head, meta, title and body tags, remove them and return their contents html">
			<label>Convert Html into inner Html:</label>
			<input type="checkbox" class="module_settings_property" name="blocks[#idx#][convert_html_into_inner_html]" value="1" />
		</div>
		<div class="exclude_theme_side_bars">
			<label>Exclude Side Bars:</label>
			<input type="checkbox" class="module_settings_property" name="blocks[#idx#][exclude_theme_side_bars]" value="1" />
		</div>
		<div class="exclude_theme_menus">
			<label>Exclude Menus:</label>
			<input type="checkbox" class="module_settings_property" name="blocks[#idx#][exclude_theme_menus]" value="1" />
		</div>
		<div class="exclude_theme_comments">
			<label>Exclude Comments:</label>
			<input type="checkbox" class="module_settings_property" name="blocks[#idx#][exclude_theme_comments]" value="1" />
		</div>';
}

function getBlockTypePostCommentsHtml() {
	return '
		<div class="post_comments">
			<label>With Comments:</label>
			<select class="module_settings_property" name="blocks[#idx#][post_comments]" onChange="onChangePostCommentsType(this)">
				<option value="pretty">With Pretty Comments</option>
				<option value="formatted">With Formatted Comments</option>
			</select>
		</div>
		<div class="get_directly_from_theme">
			<label>Try to get comments directly from theme content:</label>
			<input type="checkbox" class="module_settings_property" name="blocks[#idx#][get_directly_from_theme]" value="1" />
		</div>';
}

function getBlockTypeWidgetHtml() {
	return '
		<div class="widget">
			<label>Widget:</label>
			<select class="module_settings_property" name="blocks[#idx#][widget]" onChange="onChangeWordPressWidget(this)"></select>
		</div>
		<div class="widget_args">
			<label>Widget Args:</label>
			
			<div class="widget_arg_before_widget">
				<label>Before Widget Html:</label>
				<input class="module_settings_property" name="blocks[#idx#][widget_args][before_widget]" />
			</div>
			<div class="widget_arg_before_title">
				<label>Before Title Html:</label>
				<input class="module_settings_property" name="blocks[#idx#][widget_args][before_title]" />
			</div>
			<div class="widget_arg_after_title">
				<label>After Title Html:</label>
				<input class="module_settings_property" name="blocks[#idx#][widget_args][after_title]" />
			</div>
			<div class="widget_arg_after_widget">
				<label>After Widget Html:</label>
				<input class="module_settings_property" name="blocks[#idx#][widget_args][after_widget]" />
			</div>
		</div>
		<div class="widget_options"></div>';
}

function getBlockTypeSideBarHtml() {
	return '
		<div class="side_bar">
			<label>Side Bar:</label>
			<select class="module_settings_property" name="blocks[#idx#][side_bar]"></select>
		</div>
		<div class="get_directly_from_theme">
			<label>Try to get menu directly from theme content:</label>
			<input type="checkbox" class="module_settings_property" name="blocks[#idx#][get_directly_from_theme]" value="1" />
		</div>';
}

function getBlockTypeMenuHtml() {
	return '
		<div class="menu">
			<label>Menu:</label>
			<select class="module_settings_property" name="blocks[#idx#][menu]"></select>
		</div>
		<div class="get_directly_from_theme">
			<label>Try to get menu directly from theme content:</label>
			<input type="checkbox" class="module_settings_property" name="blocks[#idx#][get_directly_from_theme]" value="1" />
		</div>';
}

function getBlockTypeMenuLocationHtml() {
	return '
		<div class="menu_location">
			<label>Menu Location:</label>
			<select class="module_settings_property" name="blocks[#idx#][menu_location]"></select>
		</div>
		<div class="get_directly_from_theme">
			<label>Try to get menu directly from theme content:</label>
			<input type="checkbox" class="module_settings_property" name="blocks[#idx#][get_directly_from_theme]" value="1" />
		</div>';
}

function getBlockTypeCodeHtml() {
	return '
		<div class="code">
			<div class="code_header">
				<select class="functions">
					<option value="">-- Please choose a function to add --</option>
					<option value="" disabled></option>
					
					<optgroup label="WordPressHacker Methods:">
						<optgroup label="- Html Methods:">
							<option value="WordPressHacker::getTemplateHeaderHtml()">Get Template Header Html</option>
							<option value="WordPressHacker::getTemplateFooterHtml()">Get Template Footer Html</option>
							<option value="WordPressHacker::getCurrentPostTitleHtml()">Get Current Post Title Html</option>
							<option value="WordPressHacker::getCurrentPostContentHtml()">Get Current Post Content Html</option>
							<option value="WordPressHacker::getPostCommentsHtml($post_id)">Get Post Comments Html</option>
							<option value="WordPressHacker::getNewCommentForm()">Get Add-Comment Html</option>
							<option value="WordPressHacker::isNewCommentFormAllowed()">Is Add-Comment Allowed</option>
							<option value="WordPressHacker::getCurrentPostCommentsHtml()">Get Current Post Comments Html</option>
							<option value="WordPressHacker::getCurrentPostCommentsWithAddFormRawHtml()">Get Current Post Comments With Add Form - Raw Html</option>
							<option value="WordPressHacker::getCurrentPostCommentsWithAddFormPrettyHtml()">Get Current Post Comments With Add Form - Pretty Html</option>
							<option value="WordPressHacker::getMenuHtml($menu_slug_or_id)">Get Menu Html</option>
							<option value="WordPressHacker::getDefaultMenuHtml()">Get Default Menu Html</option>
							<option value="WordPressHacker::getMenuHtmlByLocation($location)">Get Menu Html By Location</option>
						</optgroup>
						
						<option value="" disabled></option>
						
						<optgroup label="- Data Methods:">
							<option value="WordPressHacker::getMenuIdByLocation(\\$location)">Get Menu Id By Location</option>
							<option value="WordPressHacker::getMenusByLocations()">Get Menus By Locations</option>
							<option value="WordPressHacker::getAvailableMenuLocations()">Get Available Menu Locations</option>
							<option value="WordPressHacker::getAvailableMenus()">Get Available Menus</option>
							<option value="WordPressHacker::getAvailableSideBars()">Get Available Side Bars</option>
							<option value="WordPressHacker::getAvailableSideBarsWithWidgets()">Get Available Side Bars With Widgets</option>
							<option value="WordPressHacker::getSideBarHtml(\\$side_bar_name)">Get Side Bar Html</option>
							<option value="WordPressHacker::getDefaultSideBarHtml()">Get Default Side Bar Html</option>
							<option value="WordPressHacker::getAvailableWidgets()">Get Available Widgets</option>
							<option value="WordPressHacker::getWidgetIdByClass(\\$class)">Get Widget Id By Class</option>
							<option value="WordPressHacker::getWidgetCallbackObjById(\\$widget_id)">Get Widget Callback Obj By Id</option>
							<option value="WordPressHacker::getWidgetHtmlById(\\$widget_id, \\$instance = array(), \\$args = array())">Get Widget Html By Id</option>
							<option value="WordPressHacker::getWidgetHtmlByClass(\\$widget_class, \\$instance = array(), \\$args = array())">Get Widget Html By Class</option>
							<option value="WordPressHacker::getWidgetControlOptionsById(\\$widget_id, \\$instance = null)">Get Widget Control Options By Id</option>
							<option value="WordPressHacker::getWidgetControlOptionsByClass(\\$widget_class, \\$instance = null)">Get Widget Control Options By Class</option>
							<option value="WordPressHacker::getPagesListHtml(\\$args = null)">Get Pages List Html</option>
							<option value="WordPressHacker::getPages(\\$args = null)">Get Pages List</option>
							<option value="WordPressHacker::getPostById(\\$post_id)">Get Post By Id</option>
							<option value="WordPressHacker::getAllPosts(\\$args = null)">Get All Posts</option>
							<option value="WordPressHacker::getPostsByCategory(\\$category, \\$args = null)">Get Posts By Category</option>
							<option value="WordPressHacker::getPostsByTag(\\$tag, \\$args = null)">Get Posts By Tag</option>
							<option value="WordPressHacker::getPostsAfterDate(\\$date, \\$args = null)">Get Posts After Date</option>
							<option value="WordPressHacker::getPostsByDate(\\$date, \\$args = null)">Get Posts By Date</option>
							<option value="WordPressHacker::getCategories(\\$args = null)">Get Categories</option>
							<option value="WordPressHacker::getTags(\\$args = null)">Get Tags</option>
							<option value="WordPressHacker::getMedias(\\$args = null)">Get Medias</option>
							<option value="WordPressHacker::getSiteUrl()">Get Site Url</option>
							<option value="WordPressHacker::getAvailableThemes()">Get Available Themes</option>
							<option value="WordPressHacker::getCurrentTheme()">Get Current Theme</option>
							<option value="WordPressHacker::get404PageHtml()">Get 404 Page Html</option>
						</optgroup>
						
						<option value="" disabled></option>
						
						<optgroup label="- Utils Methods:">
							<option value="WordPressHacker::convertContentArrayToHtml(\\$arr)">Convert a List To Html</option>
							<option value="WordPressHacker::convertHtmlIntoInnerHtml(\\$html)" title="If Html contains the html, head, meta, title and body tags, remove them and return their contents html">Convert a raw Html into inner Html</option>
							<option value="WordPressHacker::getCssAndJsFromHtml(\\$html, \\$css = true, \\$js = true)">Filter Html to only show CSS and JS Tags</option>
							<option value="WordPressHacker::getContentParentsHtml(\\$html, \\$type = \'above\')">Filter Html to only show the parents html structure, starting from bottom to top.</option>
							<option value="WordPressHacker::getContentParentsHtml(\\$html, \\$type = \'bellow\')">Filter Html to only show the parents html structure, starting from top to bottom.</option>
						</optgroup>
					</optgroup>
					
					<option value="" disabled></option>
					
					<optgroup label="Current html from WordPress:">
						<option value="\\$results[\'full_page_html\']">Get WordPress Full Page Html</option>
						<option value="\\$results[\'header\']">Get WordPress Page Header Html</option>
						<option value="\\$results[\'footer\']">Get WordPress Page Footer Html</option>
						<option value="\\$results[\'theme_side_bars\']">Get WordPress Page Side Bars Html</option>
						<option value="\\$results[\'theme_menus\']">Get WordPress Page Menus Html</option>
					</optgroup>
				</select>
				<span class="icon add" onClick="addCodeFunction(this)">Add Function</span>
			</div>
			
			<textarea class="php"></textarea>
			<textarea class="module_settings_property" name="blocks[#idx#][code]"></textarea>
			
			<div class="code_footer">
				<a href="javascript:void(0)" onClick="openWordPressFunctionsListPopup(this)">To see all WordPress available functions please click here</a>
			</div>
		</div>';
}

echo '<script>
var block_type_input_html = \'' . addcslashes(str_replace("\n", "", getBlockTypeInputHtml()), "\\'") . '\';
var block_type_previous_html = \'' . addcslashes(str_replace("\n", "", getBlockTypePreviousHtml()), "\\'") . '\';
var block_type_next_html = \'' . addcslashes(str_replace("\n", "", getBlockTypeNextHtml()), "\\'") . '\';

var block_type_full_page_html = \'' . addcslashes(str_replace("\n", "", getBlockTypeFullPageHtml()), "\\'") . '\';
var block_type_header_html = \'' . addcslashes(str_replace("\n", "", getBlockTypeContentHtml("header")), "\\'") . '\';
var block_type_footer_html = \'' . addcslashes(str_replace("\n", "", getBlockTypeContentHtml("footer")), "\\'") . '\';
var block_type_content_html = \'' . addcslashes(str_replace("\n", "", getBlockTypeContentHtml("content")), "\\'") . '\';
var block_type_post_comments_html = \'' . addcslashes(str_replace("\n", "", getBlockTypePostCommentsHtml()), "\\'") . '\';
var block_type_widget_html = \'' . addcslashes(str_replace("\n", "", getBlockTypeWidgetHtml()), "\\'") . '\';
var block_type_side_bar_html = \'' . addcslashes(str_replace("\n", "", getBlockTypeSidebarHtml()), "\\'") . '\';
var block_type_menu_html = \'' . addcslashes(str_replace("\n", "", getBlockTypeMenuHtml()), "\\'") . '\';
var block_type_menu_location_html = \'' . addcslashes(str_replace("\n", "", getBlockTypeMenuLocationHtml()), "\\'") . '\';
var block_type_code_html = \'' . addcslashes(str_replace("\n", "", getBlockTypeCodeHtml()), "\\'") . '\';
</script>';
?>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>
<script>
var wordpress_url_prefix = '<?= $wordpress_url_prefix; ?>';
var wordpress_installation_admin_login_url_prefix = '<?= $wordpress_installation_admin_login_url_prefix; ?>';

var default_wordpress_installation_name = '<?= $default_wordpress_installation_name; ?>';
</script>

<div class="module_get_html_contents_settings">
<?php
if (!$installed_wordpress_folders_name)
	echo '<div class="error">Note that there is not any WordPress installation yet.<br/>Please install the wordpress first...</div>';
?>
	
	<div class="wordpress_installation_name">
		<label>WordPress Installation:</label>
		<select class="module_settings_property" name="wordpress_installation_name" onChange="onChangeWordPressDBDriver(this)">
			<option value="">-- Default if exists --</option>
<?php
if ($installed_wordpress_folders_name)
	foreach ($installed_wordpress_folders_name as $name)
		echo '<option value="' . $name . '">' . ucwords(str_replace("_", " ", $name)) . '</option>';
?>		
		</select>
	</div>
	
	<div class="page_type">
		<label>Page Type:</label>
		<select class="module_settings_property" name="page_type" onClick="onChangePageType(this)">
			<option value=""></option>
			<option value="page">Page</option>
			<option value="posts_by_category">Posts by Category</option>
			<option value="posts_by_tag">Posts by Tag</option>
			<option value="posts_by_date">Posts by Date</option>
			<option value="posts_by_existent_date">Posts by Existent Dates</option>
			<option value="posts_by_id">Posts by Id</option>
		</select>
	</div>
	
	<div class="page_type_section page">
		<label>Page:</label>
		<select class="module_settings_property" name="page" onChange="onChangePageTypeElement(this)"></select>
	</div>
	
	<div class="page_type_section posts_by_category">
		<label>Category:</label>
		<select class="module_settings_property" name="posts_category" onChange="onChangePageTypeElement(this)"></select>
	</div>
	
	<div class="page_type_section posts_by_tag">
		<label>Tag:</label>
		<select class="module_settings_property" name="posts_tag" onChange="onChangePageTypeElement(this)"></select>
	</div>
	
	<div class="page_type_section posts_by_date">
		<label>Date:</label>
		<input type="date" class="module_settings_property" name="posts_date" onBlur="onChangePageTypeElement(this)" onInput="onChangePageTypeElement(this)" />
	</div>
	
	<div class="page_type_section posts_by_existent_date">
		<label>Date:</label>
		<select class="module_settings_property" name="posts_existent_date" onChange="onChangePageTypeElement(this)"></select>
	</div>
	
	<div class="page_type_section posts_by_id">
		<label>Post Id:</label>
		<select class="module_settings_property" name="post_id" onChange="onChangePageTypeElement(this)"></select>
	</div>
	
	<div class="path">
		<label>Request URI/Path:</label>
		<span class="wordpress_installation_url"></span>
		<input class="module_settings_property" name="path" />
		
		<div class="info">
			Note that this wordpress url may not be correctly accordingly with your WordPress' Permalinks settings. <br/>
			If you get a "Page Not Found" response, you should go to your <a class="wordpress_installation_admin_login_url" href="#" onClick="openWordPressPermalinksSettingsPopup(this)">WordPress Installation</a>, click in the menu "Settings -> Permalinks" and check what is the correct url format.
		</div>
	</div>
	
	<div class="theme_type">
		<label>Theme Type:</label>
		<select class="module_settings_property" name="theme_type" onChange="onChangeThemeType(this)">
			<option value="wordpress_theme">WordPress Theme</option> <!-- with wordpress default theme -->
			<option value="">Raw Html</option> <!-- with phpframework theme -->
		</select>
	</div>
	
	<div class="wordpress_theme">
		<label>WordPress Theme:</label>
		<select class="module_settings_property" name="wordpress_theme">
			<option value="">-- Current Active Theme --</option>
		</select>
	</div>
	
	<div class="block_type">
		<label>Add WordPress Block:</label>
		<select>
			<option value="">-- Choose a block type --</option>
			<option value="full_page_html">Full Page</option>
			<option value="header">Page Header</option>
			<option value="footer">Page Footer</option>
			<option value="content">Page Content</option>
			<option value="post_title">Post Title</option>
			<option value="post_content">Post Content</option>
			<option value="post_comments">Post Comments</option>
			<option value="widget">Widget</option>
			<option value="side_bar">Side Bar</option>
			<option value="menu">Menu</option>
			<option value="menu_location">Menu Location</option>
			<option value="pages_list">Pages List</option>
			<option value="code">Code</option>
		</select>
		<span class="icon add" onClick="addWordPressBlock(this)">Add WordPress Block</span>
	</div>
	
	<div class="previous_html">
		<label>Previous Html:</label>
		<textarea class="html"></textarea>
		<textarea class="module_settings_property" name="previous_html"></textarea>
	</div>
	
	<ul class="wordpress_blocks" index_prefix="blocks">
		<li class="no_wordpress_blocks">No wordpress blocks added yet...</li>
	</ul>
	
	<div class="next_html">
		<label>Next Html:</label>
		<textarea class="html"><?php echo getJSExecutableCodeWithWordPressUrl(); ?></textarea>
		<textarea class="module_settings_property" name="next_html"></textarea>
	</div>
	
	<div class="myfancypopup wordpress_functions">
		<iframe></iframe>
	</div>
	
	<div class="myfancypopup wordpress_permalinks_settings">
		<iframe></iframe>
	</div>
	
<?php 
	echo CommonModuleSettingsUI::getStyleFieldsHtml(false, true);
	echo CommonModuleSettingsUI::getCssFieldsHtml(true);
	echo CommonModuleSettingsUI::getJsFieldsHtml( getJSFunctionsCodeWithWordPressUrl() );
?>
	
	<div class="wordpress_options">
		<label>Internal Settings:</label>
		
		<div class="parse_wordpress_urls">
			<input type="checkbox" class="module_settings_property" name="parse_wordpress_urls" value="1" onClick="onCheckParseWordPressUrls(this)" checked />
			<label>Convert native WordPress URLs in the returned content with this page url:</label>
		</div>
		
		<div class="parse_wordpress_relative_urls">
			<input type="checkbox" class="module_settings_property" name="parse_wordpress_relative_urls" value="1" checked />
			<label>Convert native WordPress RELATIVE URLs in the returned content with this page url:</label>
		</div>
		
		<div class="allowed_wordpress_urls">
			<label>Native WordPress URLs to not convert: <span class="icon add" onClick="addWordPressUrlToExcludeConversion(this)">Add New</span></label>
			<div class="info">The urls can be a regex too. In this case it should start and end with '/'. All the other slashes should be escaped like in the normal regular expression.</div>
			<ul>
				<li class="no_urls">No urls added!</li>
			</ul>
		</div>
	</div>
</div>
