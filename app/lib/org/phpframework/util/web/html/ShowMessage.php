<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class ShowMessage { public static function printStatus($v1db8fcc7cd, $v5c1c342594) { $pf8ed4912 = '<div class="msg">'; if($v5c1c342594 === true) { $pf8ed4912 .= '<div class="ok">'.$v1db8fcc7cd.'</div>'; } elseif(is_array($v1db8fcc7cd) && count($v1db8fcc7cd)) { $pf8ed4912 .= '<div class="error">
				ERRORS: <br/><ul><li>- 
				'.implode("</li><li>- ", $v1db8fcc7cd).'
				</li></ul></div>'; } else { $pf8ed4912 .= '<div class="error">'.$v1db8fcc7cd.'</div>'; } $pf8ed4912 .= '</div>'; return $pf8ed4912; } } ?>
