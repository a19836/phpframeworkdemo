<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include $EVC->getViewPath("admin/edit_file_includes"); $head .= '<script>
	save_object_url = save_object_url.replace("/admin/save_file_includes?", "/businesslogic/save_includes?");
</script>'; ?>
