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
	
	<?php include dirname(dirname($file_path)) . "/common/CommentsHtml.php"; ?>
	
	<div class="task_property_exit" exit_id="start_exit" exit_color="#31498f" exit_label="Start loop"></div>
	<div class="task_property_exit" exit_id="default_exit" exit_color="#2C2D34" exit_label="End loop"></div>
</div>
