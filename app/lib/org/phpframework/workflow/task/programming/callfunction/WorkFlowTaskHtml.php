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
	<?php include dirname(dirname($file_path)) . "/common/CommentsHtml.php"; ?>
	
	<div class="task_property_exit" exit_id="default_exit" exit_color="#426efa"></div>
</div>
