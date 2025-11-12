<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
?><div class="create_class_object_task_html">
	<?php include dirname(dirname($file_path)) . "/common/IncludeFileHtml.php"; ?>
	
	<div class="class_name">
		<label>Class Name:</label>
		<input type="text" class="task_property_field" name="class_name" />
		<span class="icon search" onClick="ProgrammingTaskUtil.onProgrammingTaskChooseClassName(this)">Search</span>
	</div>
	<div class="class_args">
		<label>Class Args:</label>
		<div class="args"></div>
	</div>
	
	<?php include dirname(dirname($file_path)) . "/common/ResultVariableHtml.php"; ?>
	<?php include dirname(dirname($file_path)) . "/common/CommentsHtml.php"; ?>
	
	<div class="task_property_exit" exit_id="default_exit" exit_color="#426efa"></div>
</div>
