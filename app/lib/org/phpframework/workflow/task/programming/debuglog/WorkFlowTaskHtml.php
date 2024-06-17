<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
?><div class="debug_log_task_html">
	<div class="message">
		<label>Message: </label>
		<input type="text" class="task_property_field" name="message" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Search</span>
		<select class="task_property_field" name="message_type">
			<option selected>string</option>
			<option>variable</option>
			<option value="">code</option>
		</select>
	</div>
	
	<div class="log_type">
		<label>Log Type: </label>
		<input type="text" class="task_property_field log_type_code" name="log_type" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<select class="task_property_field log_type_options" name="log_type">
			<option>debug</option>
			<option>info</option>
			<option>error</option>
			<option>exception</option>
		</select>
		<select class="task_property_field log_type_type" name="log_type_type" onChange="DebugLogTaskPropertyObj.onChangeDebugLogTypeType(this)">
			<option>options</option>
			<option>string</option>
			<option selected>variable</option>
			<option value="">code</option>
		</select>
	</div>
	
	<div class="task_property_exit" exit_id="default_exit" exit_color="#426efa"></div>
</div>
