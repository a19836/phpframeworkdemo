<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
if ($GLOBALS["BLOCK_FILE_PATH"] && $GLOBALS["BLOCK_ID"]) { ob_start(null, 0); include $GLOBALS["BLOCK_FILE_PATH"]; $block_output = ob_get_contents(); ob_end_clean(); $EVC->getCMSLayer()->getCMSBlockLayer()->createBlockHtml($GLOBALS["BLOCK_ID"], $block_output); } ?>
