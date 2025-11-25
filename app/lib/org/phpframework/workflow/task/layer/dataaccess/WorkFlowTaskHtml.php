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
?><div class="data_access_layer_task_html">
	<div class="type">
		<label>Type:</label>
		<select class="task_property_field" name="type">
			<option value="ibatis">Ibatis</option>
			<option value="hibernate">Hibernate</option>
		</select>
	</div>

	<?php
 include dirname(dirname($file_path)) . "/common/BrokersHtml.php"; ?>
	
	<div class="task_property_exit" exit_id="layer_exit" exit_color="#31498f"></div>
</div>

