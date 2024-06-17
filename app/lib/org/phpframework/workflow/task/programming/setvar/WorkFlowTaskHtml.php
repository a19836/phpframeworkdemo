<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
?><div class="set_var_task_html">
	<div class="value">
		<label>Value: </label>
		<input type="text" class="task_property_field" name="value" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Search</span>
		<span class="icon search" onclick="ProgrammingTaskUtil.onProgrammingTaskChoosePageUrl(this)" title="Search Page">Search page</span>
		<span class="icon textarea" onClick="SetVarTaskPropertyObj.changeValueTextField(this)" title="Change from text area to text input and vice-versa.">Change Text Field</span>
		<span class="icon maximize" onClick="SetVarTaskPropertyObj.maximizeEditor(this)">Maximize</span>
		<textarea></textarea>
	</div>
	<div class="var_type">
		<label>Type: </label>
		<select class="task_property_field" name="type" onChange="SetVarTaskPropertyObj.onChangeVarType(this);">
			<option>string</option>
			<option>variable</option>
			<option>date</option>
			<option value="">code</option>
		</select>
	</div>
	
	<?php include dirname(dirname($file_path)) . "/common/ResultVariableHtml.php"; ?>
	
	<div class="task_property_exit" exit_id="default_exit" exit_color="#426efa"></div>
</div>
