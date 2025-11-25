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
?><div class="get_bean_object_task_html">
	<div class="phpframework_obj" title="Write here the PHPFramework Object variable">
		<label>PHPFramework Obj:</label>
		<select onChange="BrokerOptionsUtilObj.onBrokerChange(this)"></select>
		<input type="text" class="task_property_field" name="phpframework_obj" />
		<span class="icon add_variable inline" onClick="BrokerOptionsUtilObj.chooseCreatedBrokerVariable(this)">Search</span>
	</div>
	<div class="bean_name">
		<label>Bean Name:</label>
		<input class="task_property_field bean_name_value" name="bean_name" />
		<input type="text" class="bean_name_variable" />
		<span class="icon add_variable inline" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<select class="bean_name_options"></select>
		<select class="task_property_field" name="bean_name_type" onChange="GetBeanObjectTaskPropertyObj.onChangeBeanNameType(this)">
			<option>string</option>
			<option>variable</option>
			<option value="">code</option>
			<option>beans</option>
		</select>
	</div>
	
	<?php include dirname(dirname($file_path)) . "/common/ResultVariableHtml.php"; ?>
	<?php include dirname(dirname($file_path)) . "/common/CommentsHtml.php"; ?>
		
	<div class="task_property_exit" exit_id="default_exit" exit_color="#426efa"></div>
</div>
