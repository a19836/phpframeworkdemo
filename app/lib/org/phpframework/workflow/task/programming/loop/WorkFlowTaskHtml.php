<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
?><div class="loop_task_html">
	<div class="init_counters">
		<label>Init Counters:</label>
		<a class="icon variable_add" onclick="LoopTaskPropertyObj.addInitCounterVariable(this)" title="Add Variable">Add Var</a>
		<a class="icon code_add" onclick="LoopTaskPropertyObj.addInitCounterCode(this)" title="Add Code">Add Code</a>
		
		<div class="fields">
			<ul></ul>
		</div>
	</div>
	
	<div class="test_counters">
		<label>Test Counters:</label>
		
		<div class="conditions"></div>
	</div>
	
	<div class="increment_counters">
		<label>Increment Counters:</label>
		<a class="icon variable_add" onclick="LoopTaskPropertyObj.addIncrementCounterVariable(this)" title="Add Variable">Add Var</a>
		<a class="icon code_add" onclick="LoopTaskPropertyObj.addIncrementCounterCode(this)" title="Add Code">Add Code</a>
		
		<div class="fields">
			<ul></ul>
		</div>
	</div>
	
	<div class="other_settings">
		<label>Other Settings:</label>
	
		<div class="execute_first_iteration">
			<label>Always execute the first iteration:</label>
			<input class="task_property_field" type="checkbox" name="execute_first_iteration" value="1" />
		</div>
	</div>
	
	<div class="task_property_exit" exit_id="start_exit" exit_color="#31498f" exit_label="Start loop"></div>
	<div class="task_property_exit" exit_id="default_exit" exit_color="#2C2D34" exit_label="End loop"></div>
</div>
