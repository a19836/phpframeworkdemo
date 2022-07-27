<div class="user_task_2_html">
	<div class="broker_method_obj" title="Write here the CMS Template Layer variable">
		<label>Template Obj:</label>
		<select onChange="BrokerOptionsUtilObj.onBrokerChange(this)"></select>
		<input type="text" class="task_property_field" name="method_obj" />
		<span class="icon search" onClick="BrokerOptionsUtilObj.chooseCreatedBrokerVariable(this)">Search</span>
	</div>
	<div class="region">
		<label>Region:</label>
		<input type="text" class="task_property_field" name="region" />
		<span class="icon search" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Search</span>
		<select class="task_property_field" name="region_type">
			<option>string</option>
			<option>variable</option>
			<option value="">code</option>
		</select>
	</div>
	<div class="block">
		<label>Block:</label>
		<input type="text" class="task_property_field" name="block" />
		<span class="icon search" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Search</span>
		<select class="task_property_field" name="block_type">
			<option>string</option>
			<option>variable</option>
			<option value="">code</option>
		</select>
	</div>
		
	<div class="task_property_exit" exit_id="default_exit" exit_color="#b70608"></div>
</div>
