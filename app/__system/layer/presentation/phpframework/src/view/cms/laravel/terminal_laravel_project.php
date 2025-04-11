<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include $EVC->getViewPath("admin/terminal_console"); if ($layer_path) { $head .= '<script>
on_load_shell_func = setDefaultDir;

function setDefaultDir() {
	var f = $(".terminal_console > .input > form");
	var i = f.find(".input_text");
	
	i.val("cd ' . $folder_path . '");
	f.submit();
}
</script>'; } ?>
