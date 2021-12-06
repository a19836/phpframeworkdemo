<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="show_object_comments_settings">
	<div class="object_type_id">
		<label>Object Type:</label>
		<select class="module_settings_property" name="object_type_id">
		</select>
	</div>
	<div class="object_id">
		<label>Object Id:</label>
		<input type="text" class="module_settings_property" name="object_id" value="$_GET['object_id']" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="object_group">
		<label>Group:</label>
		<input type="text" class="module_settings_property" name="group" value="" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="user_label">
		<label>User Label:</label>
		<input class="module_settings_property" type="text" name="user_label" value="#username#" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<div class="info">Please write the label that will identify the user for each comment. You can only use the user attributes and they should be between ##, this is, something like #username#.</div>
	</div>
	
	<div class="filter">
		<label>Filter:</label>
		<select class="module_settings_property" name="filter" onChange="onChangeFilter(this)">
			<option value="">Do not filter comments</option>
			<option value="filter_by_parent">Filter comments by object parent</option>
			<option value="filter_by_parent_group">Filter comments by object parent group</option>
		</select>
	</div>
	<div class="filter_by_parent">
		<div class="filter_object_type_id">
			<label>Object Type:</label>
			<select class="module_settings_property" name="filter_by_parent[object_type_id]">
			</select>
		</div>
		<div class="filter_object_id">
			<label>Object Id:</label>
			<input type="text" class="module_settings_property" name="filter_by_parent[object_id]" />
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
		<div class="filter_group">
			<label>Group:</label>
			<input type="text" class="module_settings_property" name="filter_by_parent[group]" />
		</div>
	</div>
	
	<div class="clear"></div>
	<div class="show_add_comment">
		<label>Show Add Comment:</label>
		<input type="checkbox" class="module_settings_property" name="show_add_comment" value="1" checked onClick="toggleAddCommentUrl(this)" />
	</div>
	<div class="add_comment_url">
		<label>Add Comment Url:</label>
		<input type="text" class="module_settings_property" name="add_comment_url" value="" url_suffix="?object_id=" />
		<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
		<div class="info">The system will add the object id at the end of the url. The url must be an ajax request which returns &lt;status&gt;1&lt;/status&gt; on success.</div>
	</div>
	<div class="show_comments">
		<label>Show Comments:</label>
		<input type="checkbox" class="module_settings_property" name="show_comments" value="1" checked />
	</div>
	<div class="clear"></div>
	
<?php 
	echo CommonModuleSettingsUI::getStyleFieldsHtml(); 
	echo CommonModuleSettingsUI::getCssFieldsHtml();
	echo CommonModuleSettingsUI::getJsFieldsHtml();
?>
</div>
