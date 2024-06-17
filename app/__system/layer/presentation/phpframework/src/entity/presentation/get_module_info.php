<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once $EVC->getUtilPath("PHPVariablesFileHandler"); include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $module_id = $_GET["module_id"]; $layer_path = $EVC->getPresentationLayer()->getLayerPathSetting(); $CMSModuleLayer = $EVC->getCMSLayer()->getCMSModuleLayer(); $CMSModuleLayer->loadModules($project_common_url_prefix . "module/"); $all_loaded_modules = $CMSModuleLayer->getLoadedModules(); $module = $CMSModuleLayer->getLoadedModule($module_id); $module = prepareModuleToBeShown($module, $layer_path); function prepareModuleToBeShown($pfb662071, $pa2bba2ac) { foreach ($pfb662071 as $pe5c5e2fe => $v956913c90f) { if (is_array($v956913c90f)) $pfb662071[$pe5c5e2fe] = prepareModuleToBeShown($v956913c90f, $pa2bba2ac); else $pfb662071[$pe5c5e2fe] = str_replace($pa2bba2ac, "", $v956913c90f); } return $pfb662071; } ?>
