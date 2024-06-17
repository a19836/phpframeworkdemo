<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class GlobalVars { function getUserGlobalVars() { $pde8b84cb = $GLOBALS; $pbb9a4285 = array("GLOBALS", "_ENV", "HTTP_ENV_VARS", "_POST", "HTTP_POST_VARS", "_GET", "HTTP_GET_VARS", "_COOKIE", "HTTP_COOKIE_VARS", "_SERVER", "HTTP_SERVER_VARS", "_FILES", "HTTP_POST_FILES", "_REQUEST", "_SESSION", "HTTP_SESSION_VARS"); $v798939f4bb = array(); foreach($pde8b84cb as $v9ac031280c => $pcc23f958) if(array_search($v9ac031280c, $pbb9a4285) === false) if(!strpos($v9ac031280c, "-")) $v798939f4bb[$v9ac031280c] = $pcc23f958; return $v798939f4bb; } } ?>
