<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.util.xml.MyXML"); include_once get_lib("org.phpframework.util.xml.MyXMLArray"); include_once get_lib("org.phpframework.xmlfile.XMLFileParser"); include_once get_lib("org.phpframework.app"); include_once $EVC->getUtilPath("PHPVariablesFileHandler"); include_once $EVC->getUtilPath("WorkFlowBeansConverter"); class WorkFlowBeansFileHandler { private $v76c01f08ea; private $v50d32a6fc4; private $v0f9512fda4; private $pfaf08f23; public function __construct($v76c01f08ea, $v0cd49d5a59) { $this->v76c01f08ea = $v76c01f08ea; $this->pfaf08f23 = new PHPVariablesFileHandler($v0cd49d5a59); } public function getError() { return $this->v0f9512fda4; } public function init() { if (!empty($this->v76c01f08ea) && file_exists($this->v76c01f08ea)) { $v241205aec6 = file_get_contents($this->v76c01f08ea); $v6dcd71ad57 = new MyXML($v241205aec6); $this->v50d32a6fc4 = $v6dcd71ad57->toArray(array("lower_case_keys" => true)); } } public function saveNodesBeans() { $pa694ba99 = new MyXMLArray($this->v50d32a6fc4); $v241205aec6 = $pa694ba99->toXML(array("lower_case_keys" => true)); $v241205aec6 = '<?xml version="1.0" encoding="UTF-8"?>' . str_replace("&amp;", "&", $v241205aec6); return file_put_contents($this->v76c01f08ea, $v241205aec6); } public function getBeanObject($v8ffce2a791) { $v2a9b6f4e3b = new PHPFrameWork(); $v2a9b6f4e3b->init(); $this->pfaf08f23->startUserGlobalVariables(); try { $v2a9b6f4e3b->loadBeansFile($this->v76c01f08ea); } catch(Exception $paec2c009) { $this->v0f9512fda4 = $paec2c009->problem; return false; } $v4948cc5869 = $v2a9b6f4e3b->getObject($v8ffce2a791); if (is_a($v4948cc5869, "Layer")) $v4948cc5869->setPHPFrameWork($v2a9b6f4e3b); $this->pfaf08f23->endUserGlobalVariables(); return $v4948cc5869; } public function getBeanFromBeanPropertyReference($v4b5886970f, $v549de46e31, $v16d4131732) { $this->pfaf08f23->startUserGlobalVariables(); $pddfc29cd = new BeanFactory(); $pddfc29cd->init(array("file" => $this->v76c01f08ea)); $v1e9dc98c41 = $pddfc29cd->getBeans(); $pa2a3fdce = null; foreach($v1e9dc98c41 as $pf00752d4 => $v7aeaf992f5) { $pddfc29cd->initObject($pf00752d4); $v972f1a5c2b = $pddfc29cd->getObject($pf00752d4); if (is_a($v972f1a5c2b, $v4b5886970f) && $v7aeaf992f5->properties) foreach ($v7aeaf992f5->properties as $v1654ac0a73) if ($v1654ac0a73->name == $v549de46e31 && $v1654ac0a73->reference == $v16d4131732) { $pa2a3fdce = $v7aeaf992f5; break; } if ($pa2a3fdce) break; } $this->pfaf08f23->endUserGlobalVariables(); return $pa2a3fdce; } public function getEVCBeanObject($v8ffce2a791, $pa32be502 = false) { $pc53fc6d1 = $this->getBeanFromBeanPropertyReference("EVC", "presentationLayer", $v8ffce2a791); $v4948cc5869 = $pc53fc6d1 ? $this->getBeanObject($pc53fc6d1->name) : null; if ($v4948cc5869 && $pa32be502) { $v2508589a4c = self::getPresentationProjectIdFromPath($v4948cc5869->getPresentationLayer(), $pa32be502); if ($v2508589a4c) { $v4948cc5869->getPresentationLayer()->setSelectedPresentationId($v2508589a4c); $pa2bba2ac = $v4948cc5869->getPresentationLayer()->getLayerPathSetting(); $pc1297168 = $v4948cc5869->getConfigPath("pre_init_config"); if (file_exists($pc1297168)) { $v19baa1242a = $this->pfaf08f23->getGlobalVariablesFilePaths(); $v19baa1242a[] = $pc1297168; $pb343aba6 = new PHPVariablesFileHandler($v19baa1242a); $pb343aba6->startUserGlobalVariables(); $v2a9b6f4e3b = new PHPFrameWork(); $v2a9b6f4e3b->init(); try { $v2a9b6f4e3b->loadBeansFile($this->v76c01f08ea); } catch(Exception $paec2c009) { $this->v0f9512fda4 = $paec2c009->problem; return false; } $v4948cc5869 = $v2a9b6f4e3b->getObject($pc53fc6d1->name); $v4948cc5869->getPresentationLayer()->setPHPFrameWork($v2a9b6f4e3b); $v4948cc5869->getPresentationLayer()->setSelectedPresentationId($v2508589a4c); $pb343aba6->endUserGlobalVariables(); } } } return $v4948cc5869; } public static function getPresentationProjectIdFromPath($pd3623f40, $pa32be502) { $pa2bba2ac = $pd3623f40->getLayerPathSetting(); $v5c398c2c28 = $pd3623f40->settings["presentation_webroot_path"]; $v9cd205cadb = explode("/", str_replace("\\", "/", $pa32be502)); $v9acf40c110 = ""; foreach ($v9cd205cadb as $v1d2d80ed32) { if ($v1d2d80ed32) $v9acf40c110 .= ($v9acf40c110 ? "/" : "") . $v1d2d80ed32; if (is_dir($pa2bba2ac . $v9acf40c110) && is_dir($pa2bba2ac . $v9acf40c110 . "/" . $v5c398c2c28)) { return $v9acf40c110; } } return null; } public static function getAllBeanObjects($v0cd49d5a59, $v0d51bbbc63) { $pd8dc8ad3 = array(); $pfaf08f23 = new PHPVariablesFileHandler($v0cd49d5a59); $pfaf08f23->startUserGlobalVariables(); if (is_dir($v0d51bbbc63) && ($v89d33f4133 = opendir($v0d51bbbc63)) ) { while( ($v7dffdb5a5b = readdir($v89d33f4133)) !== false) { if (substr($v7dffdb5a5b, strlen($v7dffdb5a5b) - 4) == ".xml" && $v7dffdb5a5b != "app.xml" && !strstr($v7dffdb5a5b, "_bll_common_services.xml", true)) { $pddfc29cd = new BeanFactory(); $pddfc29cd->init(array("file" => $v0d51bbbc63 . $v7dffdb5a5b)); $pddfc29cd->initObjects(); $v52b4591032 = $pddfc29cd->getObjects(); $pd8dc8ad3 = array_merge($pd8dc8ad3, $v52b4591032); } } closedir($v89d33f4133); } $pfaf08f23->endUserGlobalVariables(); return $pd8dc8ad3; } public static function getAllLayersBeanObjs($v0cd49d5a59, $v0d51bbbc63) { $v52b4591032 = array(); $pfaf08f23 = new PHPVariablesFileHandler($v0cd49d5a59); $pfaf08f23->startUserGlobalVariables(); if (is_dir($v0d51bbbc63) && ($v89d33f4133 = opendir($v0d51bbbc63)) ) { while( ($v7dffdb5a5b = readdir($v89d33f4133)) !== false) { if (substr($v7dffdb5a5b, strlen($v7dffdb5a5b) - 4) == ".xml" && $v7dffdb5a5b != "app.xml" && !strstr($v7dffdb5a5b, "_bll_common_services.xml", true)) { $pddfc29cd = new BeanFactory(); $pddfc29cd->init(array("file" => $v0d51bbbc63 . $v7dffdb5a5b)); $v1e9dc98c41 = $pddfc29cd->getBeans(); $pddfc29cd->initObjects(); foreach ($v1e9dc98c41 as $v8ffce2a791 => $pdec569fb) { $v972f1a5c2b = $pddfc29cd->getObject($v8ffce2a791); if (is_a($v972f1a5c2b, "ILayer")) $v52b4591032[ $v8ffce2a791 ] = $v972f1a5c2b; } } } closedir($v89d33f4133); } $pfaf08f23->endUserGlobalVariables(); return $v52b4591032; } public static function getBeanFilePath($v0cd49d5a59, $v0d51bbbc63, $v8ffce2a791) { $pce995d72 = null; $pd7283f52 = strtolower($v8ffce2a791); $pfaf08f23 = new PHPVariablesFileHandler($v0cd49d5a59); $pfaf08f23->startUserGlobalVariables(); if (is_dir($v0d51bbbc63) && ($v89d33f4133 = opendir($v0d51bbbc63)) ) { while( ($v7dffdb5a5b = readdir($v89d33f4133)) !== false) { if (substr($v7dffdb5a5b, strlen($v7dffdb5a5b) - 4) == ".xml" && $v7dffdb5a5b != "app.xml" && !strstr($v7dffdb5a5b, "_bll_common_services.xml", true)) { $pf3dc0762 = $v0d51bbbc63 . $v7dffdb5a5b; $v241205aec6 = file_get_contents($pf3dc0762); $v241205aec6 = PHPScriptHandler::parseContent($v241205aec6); $v6dcd71ad57 = new MyXML($v241205aec6); $v50d32a6fc4 = $v6dcd71ad57->toArray(array("lower_case_keys" => true)); if ($v50d32a6fc4["beans"][0]["childs"]["bean"]) { foreach ($v50d32a6fc4["beans"][0]["childs"]["bean"] as $pdec569fb) if (strtolower($pdec569fb["@"]["name"]) == $pd7283f52) { $pce995d72 = $pf3dc0762; break; } } if ($pce995d72) break; } } closedir($v89d33f4133); } $pfaf08f23->endUserGlobalVariables(); return $pce995d72; } public static function getBeanName($v0cd49d5a59, $v0d51bbbc63, $v972f1a5c2b) { $v7377aaf7a0 = null; if ($v972f1a5c2b && is_a($v972f1a5c2b, "ILayer")) { $pa2bba2ac = $v972f1a5c2b->getLayerPathSetting(); $pfaf08f23 = new PHPVariablesFileHandler($v0cd49d5a59); $pfaf08f23->startUserGlobalVariables(); if (is_dir($v0d51bbbc63) && ($v89d33f4133 = opendir($v0d51bbbc63)) ) { while( ($v7dffdb5a5b = readdir($v89d33f4133)) !== false) { if (substr($v7dffdb5a5b, strlen($v7dffdb5a5b) - 4) == ".xml" && $v7dffdb5a5b != "app.xml" && !strstr($v7dffdb5a5b, "_bll_common_services.xml", true)) { $pddfc29cd = new BeanFactory(); $pddfc29cd->init(array("file" => $v0d51bbbc63 . $v7dffdb5a5b)); $v1e9dc98c41 = $pddfc29cd->getBeans(); $pddfc29cd->initObjects(); foreach ($v1e9dc98c41 as $v8ffce2a791 => $pdec569fb) { $v4e10095a1d = $pddfc29cd->getObject($v8ffce2a791); if (is_a($v4e10095a1d, "ILayer") && $v4e10095a1d->getLayerPathSetting() == $pa2bba2ac) { $v7377aaf7a0 = $v8ffce2a791; break; } } } } closedir($v89d33f4133); } $pfaf08f23->endUserGlobalVariables(); } return $v7377aaf7a0; } public static function getLayerBeanFolderName($pce995d72, $v8ffce2a791, $v0cd49d5a59 = false) { $pb512d021 = new WorkFlowBeansFileHandler($pce995d72, $v0cd49d5a59); $v972f1a5c2b = $pb512d021->getBeanObject($v8ffce2a791); return self::getLayerObjFolderName($v972f1a5c2b); } public static function getLayerObjFolderName($v972f1a5c2b) { if ($v972f1a5c2b && is_a($v972f1a5c2b, "ILayer")) { $pa2bba2ac = $v972f1a5c2b->getLayerPathSetting(); $pa2bba2ac = substr($pa2bba2ac, strlen(LAYER_PATH)); $v8fc05368c0 = substr($pa2bba2ac, -1) == "/" ? substr($pa2bba2ac, 0, -1) : $pa2bba2ac; return $v8fc05368c0; } return null; } public static function getLayerNameFromBeanObject($v8ffce2a791, $v972f1a5c2b) { $v77cb07b555 = null; if (is_a($v972f1a5c2b, "DBLayer")) $v77cb07b555 = "DBLayer"; else if (is_a($v972f1a5c2b, "DataAccessLayer")) $v77cb07b555 = is_a($v972f1a5c2b, "HibernateDataAccessLayer") ? "HDALayer" : "IDALayer"; else if (is_a($v972f1a5c2b, "BusinessLogicLayer")) $v77cb07b555 = "BLLayer"; else if (is_a($v972f1a5c2b, "PresentationLayer")) $v77cb07b555 = "PLayer"; return $v77cb07b555 && substr($v8ffce2a791, - strlen($v77cb07b555)) == $v77cb07b555 ? substr($v8ffce2a791, 0, strlen($v8ffce2a791) - strlen($v77cb07b555)) : $v8ffce2a791; } public static function getLayerBrokersSettings($v0cd49d5a59, $v0d51bbbc63, $pc4223ce1, $v46040f972e = "") { $v6e9af47944 = array(); $v5421227efb = array(); $v9fda9fad47 = array(); $pdeced6cd = array(); $pf864769c = array(); $v6a3a9f9182 = array(); $paf75a67c = array(); $pbf7e8fcb = array(); $v5483bfa973 = array(); $v78844cd25d = array(); if ($pc4223ce1) foreach ($pc4223ce1 as $v2b2cf4c0eb => $pd922c2f7) { $v54307eb686 = self::getLocalBeanLayerFromBroker($v0cd49d5a59, $v0d51bbbc63, $pd922c2f7); $v14b72a9b5d = $v54307eb686[0]; $v251729aa25 = $v54307eb686[1][0]; $v45d9308b28 = substr($v251729aa25, strlen($v0d51bbbc63)); $v45d9308b28 = substr($v45d9308b28, 0, 1) == "/" ? substr($v45d9308b28, 1) : $v45d9308b28; $pb4a546a4 = array( $v2b2cf4c0eb, $v45d9308b28, $v14b72a9b5d, ); if (is_a($pd922c2f7, "IBusinessLogicBrokerClient")) { $v6e9af47944[] = $pb4a546a4; $v5421227efb[$v2b2cf4c0eb] = $v46040f972e . '("' . $v2b2cf4c0eb . '")'; } else if (is_a($pd922c2f7, "IDataAccessBrokerClient")) { $v9fda9fad47[] = $pb4a546a4; $pdeced6cd[$v2b2cf4c0eb] = $v46040f972e . '("' . $v2b2cf4c0eb . '")'; if (is_a($pd922c2f7, "IHibernateDataAccessBrokerClient")) { $paf75a67c[] = $v9fda9fad47[ count($v9fda9fad47) - 1 ]; $pbf7e8fcb[$v2b2cf4c0eb] = $pdeced6cd[$v2b2cf4c0eb]; } else { $pf864769c[] = $v9fda9fad47[ count($v9fda9fad47) - 1 ]; $v6a3a9f9182[$v2b2cf4c0eb] = $pdeced6cd[$v2b2cf4c0eb]; } } else if (is_a($pd922c2f7, "IDBBrokerClient")) { $v5483bfa973[] = $pb4a546a4; $v78844cd25d[$v2b2cf4c0eb] = $v46040f972e . '("' . $v2b2cf4c0eb . '")'; } } return array( "business_logic_brokers" => $v6e9af47944, "business_logic_brokers_obj" => $v5421227efb, "data_access_brokers" => $v9fda9fad47, "data_access_brokers_obj" => $pdeced6cd, "ibatis_brokers" => $pf864769c, "ibatis_brokers_obj" => $v6a3a9f9182, "hibernate_brokers" => $paf75a67c, "hibernate_brokers_obj" => $pbf7e8fcb, "db_brokers" => $v5483bfa973, "db_brokers_obj" => $v78844cd25d, ); } public static function getLocalBeanLayersFromBrokers($v0cd49d5a59, $v0d51bbbc63, $pc4223ce1, $v59c6829ee1 = false, $pce91cb9f = null, &$v103dd1a0a6 = array(), &$v616deb51d2 = array()) { $v2635bad135 = array(); if ($pc4223ce1) { foreach ($pc4223ce1 as $v2b2cf4c0eb => $pd922c2f7) if (!$pce91cb9f || !isset($pce91cb9f[$v2b2cf4c0eb])) { $v54307eb686 = self::getLocalBeanLayerFromBroker($v0cd49d5a59, $v0d51bbbc63, $pd922c2f7); $v14b72a9b5d = $v54307eb686[0]; if ($v14b72a9b5d) { $v103dd1a0a6[$v14b72a9b5d] = $v54307eb686[1]; $v616deb51d2[$v14b72a9b5d][] = $v2b2cf4c0eb; $v2635bad135[$v14b72a9b5d] = $v54307eb686[2]; } } if ($v59c6829ee1) foreach ($v2635bad135 as $pf00752d4 => $v972f1a5c2b) if (is_a($v972f1a5c2b, "BusinessLogicLayer") || is_a($v972f1a5c2b, "DataAccessLayer")) { $v694999707b = self::getLocalBeanLayersFromBrokers($v0cd49d5a59, $v0d51bbbc63, $v972f1a5c2b->getBrokers(), $v59c6829ee1, $v2635bad135, $v103dd1a0a6, $v616deb51d2); $v2635bad135 = array_merge($v2635bad135, $v694999707b); } } return $v2635bad135; } public static function getLocalBeanLayerFromBroker($v0cd49d5a59, $v0d51bbbc63, $pd922c2f7) { $v14b72a9b5d = $v251729aa25 = $v5ce4bd29b6 = null; if (is_a($pd922c2f7, "LocalBusinessLogicBrokerClient") || is_a($pd922c2f7, "LocalDataAccessBrokerClient") || is_a($pd922c2f7, "LocalDBBrokerClient")) { $v1733fce579 = $pd922c2f7->getBeansFilesPath(); $pb9794db4 = $pd922c2f7->getBeanName(); if ($pb9794db4) { $v7959970a41 = false; $pfaf08f23 = new PHPVariablesFileHandler($v0cd49d5a59); $pfaf08f23->startUserGlobalVariables(); if ($v1733fce579) foreach ($v1733fce579 as $v9ac73a5975) { $pddfc29cd = new BeanFactory(); $pddfc29cd->init(array("file" => $v9ac73a5975)); $pdec569fb = $pddfc29cd->getBean($pb9794db4); if ($pdec569fb) { $v14b72a9b5d = $pdec569fb->constructor_args[1]->reference; if ($v14b72a9b5d) { $pddfc29cd->initObjects(); $v5ce4bd29b6 = $pddfc29cd->getObject($v14b72a9b5d); $v251729aa25 = $v1733fce579; $v7959970a41 = true; break; } } } if (!$v7959970a41) { $v9ac73a5975 = self::getBeanFilePath($v0cd49d5a59, $v0d51bbbc63, $pb9794db4); $pddfc29cd = new BeanFactory(); $pddfc29cd->init(array("file" => $v9ac73a5975)); $pdec569fb = $pddfc29cd->getBean($pb9794db4); if ($pdec569fb) { $v14b72a9b5d = $pdec569fb->constructor_args[1]->reference; if ($v14b72a9b5d) { $pddfc29cd->initObjects(); $v5ce4bd29b6 = $pddfc29cd->getObject($v14b72a9b5d); $v251729aa25 = $v1733fce579; } } } $pfaf08f23->endUserGlobalVariables(); } } return array($v14b72a9b5d, $v251729aa25, $v5ce4bd29b6); } public static function getBrokersDBDrivers($v0cd49d5a59, $v0d51bbbc63, $pc4223ce1, $v59c6829ee1 = false) { $pbec04da3 = array(); if ($pc4223ce1) { $pfaf08f23 = new PHPVariablesFileHandler($v0cd49d5a59); $pfaf08f23->startUserGlobalVariables(); foreach ($pc4223ce1 as $v2b2cf4c0eb => $pd922c2f7) { if (is_a($pd922c2f7, "IDB")) { if (!array_key_exists($v2b2cf4c0eb, $pbec04da3)) $pbec04da3[$v2b2cf4c0eb] = array(); } else if ($v59c6829ee1 && !is_a($pd922c2f7, "LocalBrokerClient")) { $v9b98e0e818 = null; if (is_a($pd922c2f7, "IBusinessLogicBrokerClient") || is_a($pd922c2f7, "IDataAccessBrokerClient")) $v9b98e0e818 = $pd922c2f7->getBrokersDBDriversName(); else if (is_a($pd922c2f7, "IDBBrokerClient")) $v9b98e0e818 = $pd922c2f7->getDBDriversName(); if ($v9b98e0e818) foreach ($v9b98e0e818 as $v5ba36af525) if (!array_key_exists($v5ba36af525, $pbec04da3)) $pbec04da3[$v5ba36af525] = array(); } } if ($v59c6829ee1) { $v2635bad135 = self::getLocalBeanLayersFromBrokers($v0cd49d5a59, $v0d51bbbc63, $pc4223ce1, true, null, $v103dd1a0a6); foreach ($v2635bad135 as $v8ffce2a791 => $v847a7225e0) { $v16d245d310 = $v103dd1a0a6[$v8ffce2a791]; if ($v16d245d310) foreach ($v16d245d310 as $pce995d72) { $pb512d021 = new WorkFlowBeansFileHandler($pce995d72, $v0cd49d5a59); $pb512d021->init(); if ($pb512d021->beanExists($v8ffce2a791)) { $pa6a3476c = $pb512d021->getBeanBrokersReferences($v8ffce2a791); if ($pa6a3476c) { $v7a4c0ba9ac = $v847a7225e0->getBrokers(); foreach ($pa6a3476c as $pe55fe541 => $v60c6bbfb09) { $v7ad973191c = $v7a4c0ba9ac[$pe55fe541]; if ($v7ad973191c) { if (is_a($v7ad973191c, "IDB")) { $v2607c61455 = self::getBeanFilePath($v0cd49d5a59, $v0d51bbbc63, $v60c6bbfb09); $v29fa250416 = substr($v2607c61455, strlen($v0d51bbbc63)); $v29fa250416 = substr($v29fa250416, 0, 1) == "/" ? substr($v29fa250416, 1) : $v29fa250416; $pbec04da3[$pe55fe541] = array( $pe55fe541, $v29fa250416, $v60c6bbfb09, ); } else if (!is_a($v7ad973191c, "LocalBrokerClient")) { $v9b98e0e818 = null; if (is_a($v7ad973191c, "IBusinessLogicBrokerClient") || is_a($v7ad973191c, "IDataAccessBrokerClient")) $v9b98e0e818 = $v7ad973191c->getBrokersDBDriversName(); else if (is_a($v7ad973191c, "IDBBrokerClient")) $v9b98e0e818 = $v7ad973191c->getDBDriversName(); if ($v9b98e0e818) foreach ($v9b98e0e818 as $v5ba36af525) if (!array_key_exists($v5ba36af525, $pbec04da3)) $pbec04da3[$v5ba36af525] = array(); } } } } break; } } } } $pfaf08f23->endUserGlobalVariables(); } return $pbec04da3; } public static function getLayerDBDrivers($v0cd49d5a59, $v0d51bbbc63, $v847a7225e0, $v59c6829ee1 = false) { $v9b98e0e818 = self::getBrokersDBDrivers($v0cd49d5a59, $v0d51bbbc63, $v847a7225e0->getBrokers(), $v59c6829ee1); if ($v9b98e0e818) { if (is_a($v847a7225e0, "DBLayer")) { $v18b3110c26 = false; foreach ($v9b98e0e818 as $v5ba36af525 => $pa9b2090f) if (empty($pa9b2090f)) { if (!$v18b3110c26) { $v18b3110c26 = true; $v14b72a9b5d = self::getBeanName($v0cd49d5a59, $v0d51bbbc63, $v847a7225e0); $v45d9308b28 = $v14b72a9b5d ? self::getBeanFilePath($v0cd49d5a59, $v0d51bbbc63, $v14b72a9b5d) : null; if ($v14b72a9b5d && $v45d9308b28) { $pb512d021 = new WorkFlowBeansFileHandler($v45d9308b28, $v0cd49d5a59); $pb512d021->init(); $v5483bfa973 = $pb512d021->getBeanBrokersReferences($v14b72a9b5d); } } if (empty($v5483bfa973)) break; else { $v831632c60d = $v5483bfa973 ? $v5483bfa973[$v5ba36af525] : null; $v9ac8782c3f = $v831632c60d ? self::getBeanFilePath($v0cd49d5a59, $v0d51bbbc63, $v831632c60d) : null; if ($v831632c60d && $v9ac8782c3f) { $v9ac8782c3f = substr($v9ac8782c3f, strlen($v0d51bbbc63)); $v9ac8782c3f = substr($v9ac8782c3f, 0, 1) == "/" ? substr($v9ac8782c3f, 1) : $v9ac8782c3f; $v9b98e0e818[$v5ba36af525] = array( $v5ba36af525, $v9ac8782c3f, $v831632c60d, ); } } } } } return $v9b98e0e818; } public static function getLayerDBDriverProps($v0cd49d5a59, $v0d51bbbc63, $v847a7225e0, $v872f5b4dbb) { if ($v872f5b4dbb) { $v872f5b4dbb = strtolower($v872f5b4dbb); $v9b98e0e818 = self::getLayerDBDrivers($v0cd49d5a59, $v0d51bbbc63, $v847a7225e0, true); if ($v9b98e0e818) foreach ($v9b98e0e818 as $v5ba36af525 => $pa9b2090f) if ($v5ba36af525 == $v872f5b4dbb) return $pa9b2090f; } return null; } public static function getLayerBrokerNameForChildBrokerDBDriver($v0cd49d5a59, $v0d51bbbc63, $v847a7225e0, $v872f5b4dbb, &$pf9c71935 = false, &$v06c0ee02d0 = false) { return self::getBrokersBrokerNameForChildBrokerDBDriver($v0cd49d5a59, $v0d51bbbc63, $v847a7225e0->getBrokers(), $v872f5b4dbb, $pf9c71935, $v06c0ee02d0); } public static function getBrokersBrokerNameForChildBrokerDBDriver($v0cd49d5a59, $v0d51bbbc63, $pc4223ce1, $v872f5b4dbb, &$pf9c71935 = false, &$v06c0ee02d0 = false) { $v92a69117ce = null; if ($pc4223ce1) { $pfaf08f23 = new PHPVariablesFileHandler($v0cd49d5a59); $pfaf08f23->startUserGlobalVariables(); foreach ($pc4223ce1 as $v2b2cf4c0eb => $pd922c2f7) { $v6b0d1825d0 = is_a($pd922c2f7, "LocalBrokerClient"); if (is_a($pd922c2f7, "IDBBrokerClient")) { $v9b98e0e818 = $pd922c2f7->getDBDriversName(); if ($v9b98e0e818) foreach ($v9b98e0e818 as $v5ba36af525) if ($v5ba36af525 == $v872f5b4dbb) { $pf9c71935 = $pd922c2f7; $pa43f839c = $pf9c71935->getBeansFilesPath(); $v06c0ee02d0 = array( "broker" => $v2b2cf4c0eb, "bean_name" => $pf9c71935->getBeanName(), "bean_file_name" => basename($pa43f839c[0]), "bean_files_paths" => $pa43f839c, "is_from_rest_broker" => !$v6b0d1825d0, ); if ($v6b0d1825d0) return $v2b2cf4c0eb; else if (!$v92a69117ce || $v06c0ee02d0["is_from_rest_broker"]) $v92a69117ce = $v2b2cf4c0eb; } } else if ($v6b0d1825d0) { $v54307eb686 = self::getLocalBeanLayerFromBroker($v0cd49d5a59, $v0d51bbbc63, $pd922c2f7); $v5ce4bd29b6 = $v54307eb686[2]; if ($v5ce4bd29b6) { $pc7752d7e = self::getBrokersBrokerNameForChildBrokerDBDriver($v0cd49d5a59, $v0d51bbbc63, $v5ce4bd29b6->getBrokers(), $v872f5b4dbb, $v0bf23740a4, $v311fbbe35c); if ($pc7752d7e) { if (!$v92a69117ce || (!$v311fbbe35c["is_from_rest_broker"] && $v06c0ee02d0["is_from_rest_broker"])) { $pf9c71935 = $pd922c2f7; $pa43f839c = $pf9c71935->getBeansFilesPath(); $v06c0ee02d0 = array( "broker" => $v2b2cf4c0eb, "bean_name" => $pf9c71935->getBeanName(), "bean_file_name" => basename($pa43f839c[0]), "bean_files_paths" => $pa43f839c, "is_from_rest_broker" => $v599e8e01e8, ); $v92a69117ce = $v2b2cf4c0eb; } } } } else if (is_a($pd922c2f7, "IBusinessLogicBrokerClient") || is_a($pd922c2f7, "IDataAccessBrokerClient")) { $v9b98e0e818 = $pd922c2f7->getBrokersDBDriversName(); if ($v9b98e0e818) foreach ($v9b98e0e818 as $v5ba36af525) if ($v5ba36af525 == $v872f5b4dbb && !$v92a69117ce) { $pf9c71935 = $pd922c2f7; $pa43f839c = $pf9c71935->getBeansFilesPath(); $v06c0ee02d0 = array( "broker" => $v2b2cf4c0eb, "bean_name" => $pf9c71935->getBeanName(), "bean_file_name" => basename($pa43f839c[0]), "bean_files_paths" => $pa43f839c, "is_from_rest_broker" => true, ); $v92a69117ce = $v2b2cf4c0eb; } } } $pfaf08f23->endUserGlobalVariables(); } return $v92a69117ce; } public static function getLayerLocalDBBrokerNameForChildBrokerDBDriver($v0cd49d5a59, $v0d51bbbc63, $v847a7225e0, $v872f5b4dbb, &$pf9c71935 = false, &$v06c0ee02d0 = false) { return self::getBrokersLocalDBBrokerNameForChildBrokerDBDriver($v0cd49d5a59, $v0d51bbbc63, $v847a7225e0->getBrokers(), $v872f5b4dbb, $pf9c71935, $v06c0ee02d0); } public static function getBrokersLocalDBBrokerNameForChildBrokerDBDriver($v0cd49d5a59, $v0d51bbbc63, $pc4223ce1, $v872f5b4dbb, &$pf9c71935 = false, &$v06c0ee02d0 = false) { $v92a69117ce = null; if ($pc4223ce1) { $pfaf08f23 = new PHPVariablesFileHandler($v0cd49d5a59); $pfaf08f23->startUserGlobalVariables(); foreach ($pc4223ce1 as $v2b2cf4c0eb => $pd922c2f7) { $v6b0d1825d0 = is_a($pd922c2f7, "LocalBrokerClient"); if (is_a($pd922c2f7, "IDBBrokerClient")) { $v9b98e0e818 = $pd922c2f7->getDBDriversName(); if ($v9b98e0e818) foreach ($v9b98e0e818 as $v5ba36af525) if ($v5ba36af525 == $v872f5b4dbb) { $pf9c71935 = $pd922c2f7; $pa43f839c = $pf9c71935->getBeansFilesPath(); $v06c0ee02d0 = array( "broker" => $v2b2cf4c0eb, "bean_name" => $pf9c71935->getBeanName(), "bean_file_name" => basename($pa43f839c[0]), "bean_files_paths" => $pa43f839c, "is_from_rest_broker" => !$v6b0d1825d0, ); if ($v6b0d1825d0) return $v2b2cf4c0eb; else $v92a69117ce = $v2b2cf4c0eb; } } else if ($v6b0d1825d0) { $v54307eb686 = self::getLocalBeanLayerFromBroker($v0cd49d5a59, $v0d51bbbc63, $pd922c2f7); $v5ce4bd29b6 = $v54307eb686[2]; if ($v5ce4bd29b6) { $pc7752d7e = self::getBrokersLocalDBBrokerNameForChildBrokerDBDriver($v0cd49d5a59, $v0d51bbbc63, $v5ce4bd29b6->getBrokers(), $v872f5b4dbb, $v0bf23740a4, $v311fbbe35c); if ($pc7752d7e) { if (is_a($v0bf23740a4, "LocalBrokerClient")) { $pf9c71935 = $v0bf23740a4; $pa43f839c = $pf9c71935->getBeansFilesPath(); $v06c0ee02d0 = array( "broker" => $pc7752d7e, "bean_name" => $pf9c71935->getBeanName(), "bean_file_name" => basename($pa43f839c[0]), "bean_files_paths" => $pa43f839c, "is_from_rest_broker" => $v599e8e01e8, ); return $pc7752d7e; } else if (!$v92a69117ce) { $pf9c71935 = $v0bf23740a4; $pa43f839c = $pf9c71935->getBeansFilesPath(); $v06c0ee02d0 = array( "broker" => $pc7752d7e, "bean_name" => $pf9c71935->getBeanName(), "bean_file_name" => basename($pa43f839c[0]), "bean_files_paths" => $pa43f839c, "is_from_rest_broker" => $v599e8e01e8, ); $v92a69117ce = $pc7752d7e; } } } } } $pfaf08f23->endUserGlobalVariables(); } return $v92a69117ce; } public function getDBSettings($v8ffce2a791, &$pa5c258b6 = false, $v15eb08a4ff = false) { $v5c5dfdb754 = array(); $pd7283f52 = strtolower($v8ffce2a791); $pa5c258b6 = !is_array($pa5c258b6) ? array() : $pa5c258b6; $this->pfaf08f23->startUserGlobalVariables(); $v50d32a6fc4 = $this->v50d32a6fc4; if ($v50d32a6fc4["beans"][0]["childs"]["bean"]) { $pc37695cb = count($v50d32a6fc4["beans"][0]["childs"]["bean"]); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pdec569fb = $v50d32a6fc4["beans"][0]["childs"]["bean"][$v43dd7d0051]; if (strtolower($pdec569fb["@"]["name"]) == $pd7283f52) { $v5c5dfdb754["type"] = DB::getDriverTypeByPath($pdec569fb["@"]["path"]); $v6cc00e79ac = $v15eb08a4ff["type"]; if ($v6cc00e79ac && $v6cc00e79ac != $v5c5dfdb754["type"]) { $pc427838f = DB::getDriverPathByType($v6cc00e79ac); if ($pc427838f) { $v50d32a6fc4["beans"][0]["childs"]["bean"][$v43dd7d0051]["@"]["path"] = $pc427838f; $v5c5dfdb754["type"] = $v6cc00e79ac; } } if ($pdec569fb["childs"]["function"]) { $pd28479e5 = count($pdec569fb["childs"]["function"]); for ($v9d27441e80 = 0; $v9d27441e80 < $pd28479e5; $v9d27441e80++) { $pa051dc1c = $pdec569fb["childs"]["function"][$v9d27441e80]; if (strtolower($pa051dc1c["@"]["name"]) == "setoptions") { $pc5faab2f = $pa051dc1c["childs"]["parameter"][0]; $v6da63250f5 = $pc5faab2f["@"]["reference"]; if ($v6da63250f5) { for ($pc5166886 = 0; $pc5166886 < $pc37695cb; $pc5166886++) { $pa6229695 = $v50d32a6fc4["beans"][0]["childs"]["var"][$pc5166886]; if ($pa6229695["@"]["name"] == $v6da63250f5) { if(isset($pa6229695["childs"]["list"])) { $pf72c1d58 = $pa6229695["childs"]["list"][0]["childs"]["item"]; $pabfe5361 = array(); foreach($pf72c1d58 as $pd69fb7d0 => $v6248f28bfd) { $pbfa01ed1 = XMLFileParser::getAttribute($v6248f28bfd, "name"); $v67db1bd535 = XMLFileParser::getValue($v6248f28bfd); $v1a9c128af2 = isset($v15eb08a4ff[$pbfa01ed1]); $pa456327a = $v15eb08a4ff[$pbfa01ed1]; $pabfe5361[] = $pbfa01ed1; if (substr(trim($pa456327a), 0, 1) == '$') $pa456327a = '<?php echo $GLOBALS[\'' . substr(trim($pa456327a), 1) . '\']; ?>'; if ($v1a9c128af2) { $pe6d3dab4 = $this->mecc4f12d03cc($pa456327a); $v5c5dfdb754[$pbfa01ed1] = $pe6d3dab4 ? $GLOBALS[$pe6d3dab4] : $pa456327a; } else $v5c5dfdb754[$pbfa01ed1] = $this->mf73c700ed652($v67db1bd535); $pa5c258b6[$pbfa01ed1] = $this->mecc4f12d03cc($v1a9c128af2 ? $pa456327a : $v67db1bd535); $pa6229695["childs"]["list"][0]["childs"]["item"][$pd69fb7d0]["value"] = $v1a9c128af2 ? $pa456327a : $v67db1bd535; } if ($v15eb08a4ff) foreach($v15eb08a4ff as $pbfa01ed1 => $pa456327a) if (!in_array($pbfa01ed1, $pabfe5361) && !in_array($pbfa01ed1, array("type"))) { if (substr(trim($pa456327a), 0, 1) == '$') $pa456327a = '<?php echo $GLOBALS[\'' . substr(trim($pa456327a), 1) . '\']; ?>'; $pe6d3dab4 = $this->mecc4f12d03cc($pa456327a); $v5c5dfdb754[$pbfa01ed1] = $pe6d3dab4 ? $GLOBALS[$pe6d3dab4] : $pa456327a; $pa6229695["childs"]["list"][0]["childs"]["item"][] = array( "name" => "item", "@" => array("name" => $pbfa01ed1), "value" => $pa456327a, ); } } } $v50d32a6fc4["beans"][0]["childs"]["var"][$pc5166886] = $pa6229695; } } } } } break; } } } $this->v50d32a6fc4 = $v50d32a6fc4; $this->pfaf08f23->endUserGlobalVariables(); return $v5c5dfdb754; } public function getBeanBrokersReferences($v8ffce2a791) { $pd7283f52 = strtolower($v8ffce2a791); $pc4223ce1 = array(); if ($this->v50d32a6fc4["beans"][0]["childs"]["bean"]) { $pc37695cb = count($this->v50d32a6fc4["beans"][0]["childs"]["bean"]); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pdec569fb = $this->v50d32a6fc4["beans"][0]["childs"]["bean"][$v43dd7d0051]; if (strtolower($pdec569fb["@"]["name"]) == $pd7283f52) { if ($pdec569fb["childs"]["function"]) { $pd28479e5 = count($pdec569fb["childs"]["function"]); for ($v9d27441e80 = 0; $v9d27441e80 < $pd28479e5; $v9d27441e80++) { $pa051dc1c = $pdec569fb["childs"]["function"][$v9d27441e80]; if (strtolower($pa051dc1c["@"]["name"]) == "addbroker") { $v9367d5be85 = $pa051dc1c["childs"]["parameter"]; $v0e97b0c60d = count($v9367d5be85); $v2b2cf4c0eb = $pfc2f54bb = null; for ($pc5166886 = 0; $pc5166886 < $v0e97b0c60d; $pc5166886++) { $pc5faab2f = $v9367d5be85[$pc5166886]; $v8a4df75785 = $pc5faab2f["@"]["index"]; if (($v8a4df75785 && $v8a4df75785 == 1) || (!$v8a4df75785 && $pc5166886 == 0)) $pfc2f54bb = $pc5faab2f["@"]["reference"]; else if (($v8a4df75785 && $v8a4df75785 == 2) || (!$v8a4df75785 && $pc5166886 == 1)) $v2b2cf4c0eb = $pc5faab2f["@"]["value"]; } if ($v2b2cf4c0eb || $pfc2f54bb) $pc4223ce1[ $v2b2cf4c0eb ] = $pfc2f54bb; } } } break; } } } return $pc4223ce1; } public function beanExists($v8ffce2a791) { $pd7283f52 = strtolower($v8ffce2a791); if ($this->v50d32a6fc4["beans"][0]["childs"]["bean"]) { $pc37695cb = count($this->v50d32a6fc4["beans"][0]["childs"]["bean"]); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pdec569fb = $this->v50d32a6fc4["beans"][0]["childs"]["bean"][$v43dd7d0051]; if (strtolower($pdec569fb["@"]["name"]) == $pd7283f52) return true; } } return false; } private function mf73c700ed652($v67db1bd535) { if (strpos($v67db1bd535, "<?php") !== false) { $v67db1bd535 = trim(str_replace(array("<?php echo ", "?>"), "", $v67db1bd535)); eval("\$v67db1bd535 = $v67db1bd535;"); } return $v67db1bd535; } private function mecc4f12d03cc($v67db1bd535) { if (strpos($v67db1bd535, "<?") !== false) { $v67db1bd535 = trim(str_replace(array("<?php echo ", "<? echo ", "?>", ";", '$'), "", $v67db1bd535)); if (strpos($v67db1bd535, "GLOBALS['") !== false) { $v67db1bd535 = trim(str_replace(array("GLOBALS['", "GLOBALS[\""), "", $v67db1bd535)); if (substr($v67db1bd535, strlen($v67db1bd535) - 2) == "']" || substr($v67db1bd535, strlen($v67db1bd535) - 2) == "\"]") { $v67db1bd535 = substr($v67db1bd535, 0, strlen($v67db1bd535) - 2); } } return $v67db1bd535; } return false; } } ?>
