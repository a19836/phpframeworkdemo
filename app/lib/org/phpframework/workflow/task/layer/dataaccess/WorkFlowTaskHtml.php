<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
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

