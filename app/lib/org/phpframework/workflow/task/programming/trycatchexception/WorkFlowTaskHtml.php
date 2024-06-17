<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
?><div class="try_catch_exception_task_html">
	<div class="class_name">
		<label>Class Name:</label>
		<input type="text" class="task_property_field" name="class_name" placeholder="Exception" />
		<span class="icon search" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseClassName(this)" do_not_update_args="1">Search</span>
	</div>
	<div class="var_name">
		<label>Variable Name:</label>
		<input type="text" class="task_property_field" name="var_name" />
	</div>
	
	<div class="task_property_exit" exit_id="try" exit_color="#51D87A" exit_label="No exception"></div>
	<div class="task_property_exit" exit_id="catch" exit_color="#FF4D4D" exit_label="Exception to catch"></div>
</div>
