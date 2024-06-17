<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.encryption.PublicPrivateKeyHandler"); include_once get_lib("org.phpframework.encryption.CryptoKeyHandler"); include_once get_lib("org.phpframework.phpscript.PHPCodePrintingHandler"); class CMSDeploymentSecurityHandler { public static function setSecureFiles($v08a367fe04, &$pddc51a8e) { } public static function emptyFileClassMethod($pf3dc0762, $v3ae55a9a2e, $v6cd9d4006f, &$pddc51a8e) { if ($pf3dc0762 && file_exists($pf3dc0762)) { PHPCodePrintingHandler::replaceFunctionCodeFromFile($pf3dc0762, $v6cd9d4006f, "", $v3ae55a9a2e); $v067674f4e4 = PHPCodePrintingHandler::getFunctionCodeFromFile($pf3dc0762, $v6cd9d4006f, $v3ae55a9a2e); if ($v067674f4e4) $pddc51a8e[] = "Error: Method $v3ae55a9a2e::$v6cd9d4006f NOT deleted and still exists!"; } } private static function me202360dafc0($pf3dc0762, &$pddc51a8e) { if (file_exists($pf3dc0762)) unlink($pf3dc0762); if (file_exists($pf3dc0762)) $pddc51a8e[] = "Error: File '$pf3dc0762' NOT deleted and still exists!"; } private static function mbcffab0a8efd($pf3dc0762, $v391cc249fc, $v91a962d917, &$pddc51a8e) { if ($pf3dc0762 && file_exists($pf3dc0762)) { $v6490ea3a15 = file_get_contents($pf3dc0762); if ($v6490ea3a15) { $v6490ea3a15 = str_replace($v391cc249fc, $v91a962d917, $v6490ea3a15); if (file_put_contents($pf3dc0762, $v6490ea3a15) === false) $pddc51a8e[] = "Error: Could not replace the security string: '$v391cc249fc' in file: '$pf3dc0762'"; } } } private static function f7f8c71538c($pf3dc0762, $v1cfba8c105, $pa6209df1, &$pddc51a8e) { if ($pf3dc0762 && file_exists($pf3dc0762)) { $v6490ea3a15 = file_get_contents($pf3dc0762); if ($v6490ea3a15) { if (strpos($v6490ea3a15, '$' . $v1cfba8c105) !== false) $v6490ea3a15 = preg_replace('/\$' . $v1cfba8c105 . '\s*=\s*([^;]+);/u', '$' . $v1cfba8c105 . ' = ' . $pa6209df1 . ';', $v6490ea3a15); else $v6490ea3a15 = str_replace("?>", '$' . $v1cfba8c105 . ' = ' . $pa6209df1 . ';' . "\n?>", $v6490ea3a15, $v15f3268002 = 1); if (file_put_contents($pf3dc0762, $v6490ea3a15) === false) $pddc51a8e[] = "Error: Could not replace the security string: '$v391cc249fc' in file: '$pf3dc0762'"; } } } private static function md12747b21ef0($pf3dc0762, $v76032145ee, $v59c6829ee1, &$pddc51a8e) { if ($pf3dc0762 && file_exists($pf3dc0762)) { $v5c1c342594 = true; if (!chmod($pf3dc0762, $v76032145ee)) { $pddc51a8e[] = "Error: Could not set permission 0" . decoct($v76032145ee) . " to: '$pf3dc0762'"; $v5c1c342594 = false; } if (is_dir($pf3dc0762)) { $v6ee393d9fb = scandir($pf3dc0762); if ($v6ee393d9fb) { $v6ee393d9fb = array_diff($v6ee393d9fb, array(".", "..")); foreach ($v6ee393d9fb as $v7dffdb5a5b) if (!self::md12747b21ef0("$pf3dc0762/$v7dffdb5a5b", $v76032145ee, $v59c6829ee1, $pddc51a8e)) $v5c1c342594 = false; } } return $v5c1c342594; } } public static function createAppLicence($pfba8f554, $pd4154033, $v8f86ff7f6d, $v00db7537b4, $v105fddb79a, $v1f5fcad24e, $v1426427798, $v2a25aacb5b, $pc9ad6087, $pec8209d0, $pf4c40197, $v0cba298be6, $pd0a05d6a, $pfca4e2ea, &$pddc51a8e) { if (!$pfba8f554 || !is_dir($pfba8f554)) { $pddc51a8e[] = "Error: app_licence_folder_path is not a folder or does not exists!"; return false; } if (!file_exists($pd4154033)) { $pddc51a8e[] = "Error: Private Key file does not exists! You must enter the CMS relative url for your priv.pem file!"; return false; } if (!file_exists($v8f86ff7f6d)) { $pddc51a8e[] = "Error: Public Key file does not exists! You must enter the CMS relative url for your pub.pem file!"; return false; } $pbfa01ed1 = CryptoKeyHandler::getKey(); $v00db7537b4 .= CryptoKeyHandler::binToHex($pbfa01ed1); $v5c155e3188 = $v105fddb79a && $v105fddb79a != -1 ? strtotime($v105fddb79a) : -1; $v97d30e8ef7 = $v1f5fcad24e ? strtotime($v1f5fcad24e) : time() + (60 * 60 * 24 * 30); $v1426427798 = is_numeric($v1426427798) ? $v1426427798 : -1; $v2a25aacb5b = is_numeric($v2a25aacb5b) ? $v2a25aacb5b : -1; $pc9ad6087 = is_numeric($pc9ad6087) ? $pc9ad6087 : -1; $pec8209d0 = is_numeric($pec8209d0) ? $pec8209d0 : -1; $pf4c40197 = is_array($pf4c40197) ? implode(",", $pf4c40197) : $pf4c40197; $v0cba298be6 = is_array($v0cba298be6) ? implode(",", $v0cba298be6) : $v0cba298be6; $pfca4e2ea = $pfca4e2ea ? 1 : 0; $v43400314eb = (PHP_INT_SIZE * 8) == 32; if ($v43400314eb) { $pf0eb6f86 = strtotime("19-01-2038"); if ($v97d30e8ef7 > $pf0eb6f86) $v97d30e8ef7 = $pf0eb6f86; if ($v5c155e3188 != -1 && $v5c155e3188 > $pf0eb6f86) $v5c155e3188 = $pf0eb6f86; } $pd9f85299 = $v5c155e3188 != -1 ? date("d-m-Y", $v5c155e3188) : -1; $v6de928afe8 = date("d-m-Y", $v97d30e8ef7); $v1f9288194d = $v1426427798 > 0 ? $v1426427798 + 1 : $v1426427798; $v0cba298be6 = str_replace(";", ",", trim($v0cba298be6)); $v0cba298be6 = substr(preg_replace("/:80,/", ",", $v0cba298be6 . ","), 0, -1); $v0cba298be6 = preg_replace("/,+/", ",", preg_replace("/(^,|,$)/", "", preg_replace("/\s*,\s*/", ",", $v0cba298be6))); $pf4c40197 = preg_replace("/\\/+/", "/", str_replace(";", ",", trim($pf4c40197))); $pf4c40197 = preg_replace("/,+/", ",", preg_replace("/(^,|,$)/", "", preg_replace("/\s*,\s*/", ",", $pf4c40197))); $pd0a05d6a = $pd0a05d6a ? 1 : 0; $v30ba958cfb = self::f8012935212(); $v327f72fb62 = $v30ba958cfb[0] . " = $pd9f85299\n" . $v30ba958cfb[1] . " = $v6de928afe8\n" . $v30ba958cfb[2] . " = $v1f9288194d\n" . $v30ba958cfb[3] . " = $v2a25aacb5b\n" . $v30ba958cfb[4] . " = $pc9ad6087\n" . $v30ba958cfb[5] . " = $pec8209d0\n" . $v30ba958cfb[6] . " = $pf4c40197\n" . $v30ba958cfb[7] . " = $v0cba298be6\n" . $v30ba958cfb[8] . " = $pd0a05d6a\n" . $v30ba958cfb[9] . " = $pfca4e2ea"; $v2564410bfb = new PublicPrivateKeyHandler(true); $pe4669b28 = $v2564410bfb->encryptString($v327f72fb62, $pd4154033, $v00db7537b4); $v8cf8dbc3dd = $pfba8f554 . "/" . self::f3389fbd2b0(); if (file_put_contents($v8cf8dbc3dd, $pe4669b28) === false) { $pddc51a8e[] = "Error: Could not create Licence!"; return false; } else { $v41a198a73c = $v2564410bfb->decryptString($pe4669b28, $v8f86ff7f6d); $v9acf40c110 = parse_ini_string($v41a198a73c); $v12a16c3aae = isset($v9acf40c110[ $v30ba958cfb[0] ]) ? $v9acf40c110[ $v30ba958cfb[0] ] : null; $v31e1fd8c93 = isset($v9acf40c110[ $v30ba958cfb[1] ]) ? $v9acf40c110[ $v30ba958cfb[1] ] : null; $v380dc627d1 = isset($v9acf40c110[ $v30ba958cfb[2] ]) ? $v9acf40c110[ $v30ba958cfb[2] ] : null; $v88ea8288eb = isset($v9acf40c110[ $v30ba958cfb[3] ]) ? $v9acf40c110[ $v30ba958cfb[3] ] : null; $v255558dfc4 = isset($v9acf40c110[ $v30ba958cfb[4] ]) ? $v9acf40c110[ $v30ba958cfb[4] ] : null; $v1c070d6468 = isset($v9acf40c110[ $v30ba958cfb[5] ]) ? $v9acf40c110[ $v30ba958cfb[5] ] : null; $v2c93b0cd70 = isset($v9acf40c110[ $v30ba958cfb[6] ]) ? $v9acf40c110[ $v30ba958cfb[6] ] : null; $v346053a7e8 = isset($v9acf40c110[ $v30ba958cfb[7] ]) ? $v9acf40c110[ $v30ba958cfb[7] ] : null; $v4d8b954673 = isset($v9acf40c110[ $v30ba958cfb[8] ]) ? $v9acf40c110[ $v30ba958cfb[8] ] : null; $v43e2eed641 = isset($v9acf40c110[ $v30ba958cfb[9] ]) ? $v9acf40c110[ $v30ba958cfb[9] ] : null; if ($pd9f85299 != $v12a16c3aae || $v6de928afe8 != $v31e1fd8c93 || $v1f9288194d != $v380dc627d1 || $v2a25aacb5b != $v88ea8288eb || $pc9ad6087 != $v255558dfc4 || $pec8209d0 != $v1c070d6468 || $pf4c40197 != $v2c93b0cd70 || $v0cba298be6 != $v346053a7e8 || $pd0a05d6a != $v4d8b954673 || $pfca4e2ea != $v43e2eed641) { $pddc51a8e[] = "Error: Licence created but the decoded string have different values than original values!"; return false; } if (!self::mf9552521f41f($pfba8f554, $v8f86ff7f6d, $pddc51a8e)) { $pddc51a8e[] = "Error: Could not update new Licence key in the CMS files."; return false; } } return true; } private static function mf9552521f41f($pfba8f554, $v8f86ff7f6d, &$pddc51a8e) { $v6490ea3a15 = file_get_contents($v8f86ff7f6d); $v9cd205cadb = explode("\n", $v6490ea3a15); array_shift($v9cd205cadb); array_pop($v9cd205cadb); $v6ee393d9fb = array("BeanFactory", "Bean", "BeanArgument", "BeanSettingsFileFactory", "BeanXMLParser", "BeanFunction", "BeanProperty"); $v5c1c342594 = true; foreach ($v6ee393d9fb as $pd69fb7d0 => $v7dffdb5a5b) if (!self::mfb4adc9bd3d8("$pfba8f554/lib/org/phpframework/bean/$v7dffdb5a5b.php", $v9cd205cadb[$pd69fb7d0], $pddc51a8e)) $v5c1c342594 = false; return $v5c1c342594; } private static function mfb4adc9bd3d8($pf3dc0762, $v67db1bd535, &$pddc51a8e) { if (file_exists($pf3dc0762)) { $v327f72fb62 = "65 80 80 80 95 95 75 69 69 89"; $v5910a1deae = ""; $v9cd205cadb = explode(" ", $v327f72fb62); for($v43dd7d0051 = 0; $v43dd7d0051 < count($v9cd205cadb); $v43dd7d0051 += 3) $v5910a1deae .= chr($v9cd205cadb[$v43dd7d0051]) . (!empty($v9cd205cadb[$v43dd7d0051 + 2]) ? chr($v9cd205cadb[$v43dd7d0051 + 2]) : ""); $pf06b4875 = chr(65) . chr(75); $v6490ea3a15 = file_get_contents($pf3dc0762); $pad09010b = $v6490ea3a15; $v6490ea3a15 = preg_replace('/const(\s*)' . $v5910a1deae . '(\s*)=(\s*)"([^"]*)"(\s*);/iu', 'const ' . $v5910a1deae . ' = "' . $v67db1bd535 . '";', $v6490ea3a15); $v6490ea3a15 = preg_replace('/const(\s*)' . $v5910a1deae . '(\s*)=(\s*)\'([^\']*)\'(\s*);/iu', 'const ' . $v5910a1deae . ' = \'' . $v67db1bd535 . '\';', $v6490ea3a15); $v6490ea3a15 = preg_replace('/const(\s*)' . $pf06b4875 . '(\s*)=(\s*)"([^"]*)"(\s*);/iu', 'const ' . $pf06b4875 . ' = "' . $v67db1bd535 . '";', $v6490ea3a15); $v6490ea3a15 = preg_replace('/const(\s*)' . $pf06b4875 . '(\s*)=(\s*)\'([^\']*)\'(\s*);/iu', 'const ' . $pf06b4875 . ' = \'' . $v67db1bd535 . '\';', $v6490ea3a15); if (file_put_contents($pf3dc0762, $v6490ea3a15) === false) { $pddc51a8e[] = "Error: Could not update new Licence key in '" . basename($pf3dc0762) . "'."; return false; } return true; } $pddc51a8e[] = "Error: Could not update new Licence key in '" . basename($pf3dc0762) . "' because file does not exists."; return false; } private static function f3389fbd2b0() { $v327f72fb62 = "97 97 46 112 112 112 108 108 95 99 99 105"; $pfd260091 = ""; $v9cd205cadb = explode(" ", $v327f72fb62); for($v43dd7d0051 = 0, $pe2ae3be9 = count($v9cd205cadb); $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051 += 3) $pfd260091 .= ($v43dd7d0051 + 2 < $pe2ae3be9 ? chr($v9cd205cadb[$v43dd7d0051 + 2]) : "") . chr($v9cd205cadb[$v43dd7d0051]); return $pfd260091; } private static function f8012935212() { $v327f72fb62 = "107 107 36 121 121 101 32 32 115 32 32 61 114 114 97 97 97 114 40 40 121 112 112 34 111 111 114 101 101 106 116 116 99 95 95 115 120 120 101 105 105 112 97 97 114 105 105 116 110 110 111 100 100 95 116 116 97 34 34 101 32 32 44 115 115 34 115 115 121 100 100 97 105 105 109 95 95 110 120 120 101 105 105 112 97 97 114 105 105 116 110 110 111 100 100 95 116 116 97 34 34 101 32 32 44 112 112 34 111 111 114 101 101 106 116 116 99 95 95 115 97 97 109 105 105 120 117 117 109 95 95 109 117 117 110 98 98 109 114 114 101 44 44 34 34 34 32 115 115 117 114 114 101 95 95 115 97 97 109 105 105 120 117 117 109 95 95 109 117 117 110 98 98 109 114 114 101 44 44 34 34 34 32 110 110 101 95 95 100 115 115 117 114 114 101 95 95 115 97 97 109 105 105 120 117 117 109 95 95 109 117 117 110 98 98 109 114 114 101 44 44 34 34 34 32 99 99 97 105 105 116 110 110 111 95 95 115 97 97 109 105 105 120 117 117 109 95 95 109 117 117 110 98 98 109 114 114 101 44 44 34 34 34 32 108 108 97 111 111 108 101 101 119 95 95 100 97 97 112 104 104 116 34 34 115 32 32 44 97 97 34 108 108 108 119 119 111 100 100 101 100 100 95 109 109 111 105 105 97 115 115 110 44 44 34 34 34 32 104 104 99 99 99 101 95 95 107 108 108 97 111 111 108 101 101 119 95 95 100 111 111 100 97 97 109 110 110 105 95 95 115 111 111 112 116 116 114 44 44 34 34 34 32 108 108 97 111 111 108 101 101 119 95 95 100 121 121 115 97 97 115 109 109 100 110 110 105 109 109 95 103 103 105 97 97 114 105 105 116 110 110 111 41 41 34 59"; $pd8481879 = ""; $v9cd205cadb = explode(" ", $v327f72fb62); for($v43dd7d0051 = 0, $pe2ae3be9 = count($v9cd205cadb); $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051 += 3) $pd8481879 .= ($v43dd7d0051 + 2 < $pe2ae3be9 ? chr($v9cd205cadb[$v43dd7d0051 + 2]) : "") . chr($v9cd205cadb[$v43dd7d0051]); $pd8481879 = trim($pd8481879); eval($pd8481879); return $keys; } } ?>
