<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 class ShowMessage { public static function printStatus($v1db8fcc7cd, $v5c1c342594) { $pf8ed4912 = '<div class="msg">'; if($v5c1c342594 === true) { $pf8ed4912 .= '<div class="ok">'.$v1db8fcc7cd.'</div>'; } elseif(is_array($v1db8fcc7cd) && count($v1db8fcc7cd)) { $pf8ed4912 .= '<div class="error">
				ERRORS: <br/><ul><li>- 
				'.implode("</li><li>- ", $v1db8fcc7cd).'
				</li></ul></div>'; } else { $pf8ed4912 .= '<div class="error">'.$v1db8fcc7cd.'</div>'; } $pf8ed4912 .= '</div>'; return $pf8ed4912; } } ?>
