<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
if ($GLOBALS["VIEW_FILE_PATH"] && $GLOBALS["VIEW_ID"]) { ob_start(null, 0); include $GLOBALS["VIEW_FILE_PATH"]; $view_output = ob_get_contents(); ob_end_clean(); $EVC->getCMSLayer()->getCMSViewLayer()->createViewHtml($GLOBALS["VIEW_ID"], $view_output); } ?>
