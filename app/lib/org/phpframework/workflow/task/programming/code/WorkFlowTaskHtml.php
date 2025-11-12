<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
?><div class="code_task_html">
	<div class="pretify">
		<a onclick="CodeTaskPropertyObj.pretifyCode(this)">Pretify Code</a>
	</div>
	
	<div class="code">
		<textarea></textarea>
	</div>
	
	<textarea class="task_property_field" name="code" style="display:none"></textarea>
	
	<?php include dirname(dirname($file_path)) . "/common/CommentsHtml.php"; ?>
	
	<div class="task_property_exit" exit_id="default_exit" exit_color="#426efa"></div>
</div>
