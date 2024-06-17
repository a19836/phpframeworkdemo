<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
?><div class="foreach_task_html">
	<div class="obj">
		<label>Obj/Array Variable: </label>
		<input type="text" class="task_property_field" name="obj" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Search</span>
	</div>
	<div class="key">
		<label>Key Variable: </label>
		<input type="text" class="task_property_field" name="key" />
	</div>
	<div class="value">
		<label>Value Variable: </label>
		<input type="text" class="task_property_field" name="value" />
	</div>
	
	<div class="task_property_exit" exit_id="start_exit" exit_color="#31498f" exit_label="Start loop"></div>
	<div class="task_property_exit" exit_id="default_exit" exit_color="#2C2D34" exit_label="End loop"></div>
</div>
