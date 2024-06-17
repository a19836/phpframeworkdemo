<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
?><div class="include_file_task_html">
	<div class="file_path">
		<label>File Path: <span class="icon edit edit_source" onClick="IncludeFileTaskPropertyObj.onEditFile(this)" title="Edit file">Edit</span></label>
		<input type="text" class="task_property_field" name="file_path" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Search</span>
		<span class="icon search" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseFilePath(this)">Search</span>
	</div>
	<div class="type">
		<label>Type:</label>
		<select class="task_property_field" name="type">
			<option>string</option>
			<option value="">code</option>
		<select>
	</div>
	<div class="once">
		<label>Once:</label>
		<input type="checkbox" class="task_property_field" name="once" value="1" title="Include Once" />
	</div>
	
	<div class="task_property_exit" exit_id="default_exit" exit_color="#426efa"></div>
</div>
