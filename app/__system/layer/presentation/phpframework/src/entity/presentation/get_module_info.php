<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */
 include_once $EVC->getUtilPath("PHPVariablesFileHandler"); include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $module_id = isset($_GET["module_id"]) ? $_GET["module_id"] : null; $layer_path = $EVC->getPresentationLayer()->getLayerPathSetting(); $CMSModuleLayer = $EVC->getCMSLayer()->getCMSModuleLayer(); $CMSModuleLayer->loadModules($project_common_url_prefix . "module/"); $all_loaded_modules = $CMSModuleLayer->getLoadedModules(); $module = $CMSModuleLayer->getLoadedModule($module_id); $module = prepareModuleToBeShown($module, $layer_path); function prepareModuleToBeShown($pfb662071, $pa2bba2ac) { foreach ($pfb662071 as $pe5c5e2fe => $v956913c90f) { if (is_array($v956913c90f)) $pfb662071[$pe5c5e2fe] = prepareModuleToBeShown($v956913c90f, $pa2bba2ac); else $pfb662071[$pe5c5e2fe] = str_replace($pa2bba2ac, "", $v956913c90f); } return $pfb662071; } ?>
