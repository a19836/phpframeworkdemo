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
	
	<?php include dirname(dirname($file_path)) . "/common/CommentsHtml.php"; ?>
	
	<div class="task_property_exit" exit_id="try" exit_color="#51D87A" exit_label="No exception"></div>
	<div class="task_property_exit" exit_id="catch" exit_color="#FF4D4D" exit_label="Exception to catch"></div>
</div>
