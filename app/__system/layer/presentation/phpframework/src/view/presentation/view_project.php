<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$post_vars = $_GET["post_vars"]; $main_content = '
<form method="' . ($post_vars ? "post" : "get") . '" action="' . str_replace('"', '%22', $url) . '" style="display:none">'; if ($post_vars) foreach ($post_vars as $k => $v) $main_content .= '<input type="hidden" name="' . htmlentities($k) . '" value="' . htmlentities($v) . '" />'; $main_content .= '
	<input type="submit" value="go"/>
</form>
<script>
	document.forms[0].submit();
</script>'; ?>
