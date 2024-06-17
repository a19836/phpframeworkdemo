<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
?><div class="call_function_task_html">
	<?php include dirname(dirname($file_path)) . "/common/IncludeFileHtml.php"; ?>
	
	<div class="func_name">
		<label>Function Name: <span class="icon edit edit_source" onClick="CallFunctionTaskPropertyObj.onEditFunction(this)" title="Edit function logic">Edit</span></label>
		<input type="text" class="task_property_field" name="func_name" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<span class="icon search" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseFunction(this)">Search</span>
	</div>
	<div class="func_args">
		<label>Function Args:</label>
		<div class="args"></div>
	</div>
	
	<?php include dirname(dirname($file_path)) . "/common/ResultVariableHtml.php"; ?>
	
	<div class="task_property_exit" exit_id="default_exit" exit_color="#426efa"></div>
</div>
