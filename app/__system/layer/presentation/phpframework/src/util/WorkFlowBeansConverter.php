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
include_once get_lib("org.phpframework.util.xml.MyXML"); include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSProgramExtraTableInstallationUtil"); include_once get_lib("org.phpframework.cms.wordpress.WordPressUrlsParser"); include_once $EVC->getUtilPath("WorkFlowBeansFolderHandler"); include_once $EVC->getUtilPath("WorkFlowTasksFileHandler"); include_once $EVC->getUtilPath("LayoutTypeProjectHandler"); class WorkFlowBeansConverter { private $pfc8be585; private $v5039a77f9d; private $v3d55458bcd; private $pc0fc7d17; private $v994c46e305; private $v8cd3e3837d; public function __construct($v5e053dece2, $v5039a77f9d, $v3d55458bcd, $pc0fc7d17, $v73fec76b27 = array()) { $this->v5039a77f9d = $v5039a77f9d; $this->v3d55458bcd = $v3d55458bcd; $this->pc0fc7d17 = $pc0fc7d17; @mkdir($this->v5039a77f9d, 0775, true); $this->v994c46e305 = new WorkFlowBeansFolderHandler($v5039a77f9d, $v3d55458bcd, $pc0fc7d17, $v73fec76b27); $this->v8cd3e3837d = new WorkFlowTasksFileHandler($v5e053dece2); $this->pfc8be585 = WorkFlowTasksFileHandler::getTaskLayerTags(); } public function init() { $this->v8cd3e3837d->init(); } public function getWorkFlowTasksFileHandler() { return $this->v8cd3e3837d; } public function getUserBeansFolderPath() { return $this->v5039a77f9d; } public function createBeans($pdf77ee66 = null, $v5d3813882f = null) { $v5c1c342594 = true; if ($v5d3813882f && $v5d3813882f["tasks_folders"]) $v5c1c342594 = $this->mf85e2c32b136($pdf77ee66, $v5d3813882f["tasks_folders"], $v5d3813882f["tasks_labels"]); $v0494809a75 = $this->f57a25426b8(); return $v5c1c342594 && $this->v994c46e305->createDefaultFiles() && $this->v994c46e305->removeOldBeansFiles() && $this->mced76e6cc9cb() && $this->md7749ec7c299() && $this->mcebcbe562723() && $this->md1a96577b4ca() && $this->f3bb0cb9725() && $this->f637963f631() && $this->f5893907a6e() && $this->v994c46e305->createDefaultLayer($v0494809a75); } public function recreateBean($v9f49eb3ac3) { $v5c1c342594 = false; $v1d696dbd12 = $this->v8cd3e3837d->getWorkflowData(); $pba9d33da = null; if ($v1d696dbd12 && $v1d696dbd12["tasks"]) foreach ($v1d696dbd12["tasks"] as $v8282c7dd58 => $v7f5911d32d) if ($v8282c7dd58 == $v9f49eb3ac3) { $pba9d33da = $v7f5911d32d; break; } if ($pba9d33da) { switch($pba9d33da["tag"]) { case $this->pfc8be585["dbdriver"]: $v5c1c342594 = $this->md7749ec7c299($v9f49eb3ac3); break; case $this->pfc8be585["db"]: $v5c1c342594 = $this->mcebcbe562723($v9f49eb3ac3); break; case $this->pfc8be585["dataaccess"]: $v5c1c342594 = $this->md1a96577b4ca($v9f49eb3ac3); break; case $this->pfc8be585["businesslogic"]: $v5c1c342594 = $this->f3bb0cb9725($v9f49eb3ac3) && $this->f637963f631($v9f49eb3ac3); break; case $this->pfc8be585["presentation"]: $v5c1c342594 = $this->f5893907a6e($v9f49eb3ac3); break; } } return $v5c1c342594; } public function getWordPressInstallationsWithoutDBDrivers() { $v1d696dbd12 = $this->v8cd3e3837d->getWorkflowData(); $v73fec76b27 = $this->v994c46e305->getGlobalPaths(); $v9826bd2b1a = array(); $v0968c336f1 = array(); if ($v1d696dbd12 && $v1d696dbd12["tasks"]) foreach ($v1d696dbd12["tasks"] as $v7f5911d32d) if ($v7f5911d32d["tag"] == $this->pfc8be585["dbdriver"] || $v7f5911d32d["tag"] == $this->pfc8be585["presentation"]) { self::mac0ace304e61($v7f5911d32d["label"]); $pb77a7e67 = self::mea7bdac9ad9e($v7f5911d32d["label"]); if ($v7f5911d32d["tag"] == $this->pfc8be585["dbdriver"]) $v9826bd2b1a[$pb77a7e67] = $v7f5911d32d; else $v0968c336f1[] = $pb77a7e67; } $pd776aeb1 = array(); $v4818b44a94 = array(); foreach ($v0968c336f1 as $v13eedf3e61) { $pb21d90ae = $v73fec76b27["LAYER_PATH"] . "$v13eedf3e61/common/webroot/" . WordPressUrlsParser::WORDPRESS_FOLDER_PREFIX . "/"; $v6ee393d9fb = file_exists($pb21d90ae) ? array_diff(scandir($pb21d90ae), array('.', '..')) : null; if ($v6ee393d9fb) foreach ($v6ee393d9fb as $v7dffdb5a5b) if (is_dir($pb21d90ae . $v7dffdb5a5b)) { $v7f5911d32d = $v9826bd2b1a[$v7dffdb5a5b]; if (!isset($v7f5911d32d)) $pd776aeb1[] = $v7dffdb5a5b; else { $pd5b162f3 = $pb21d90ae . "$v7dffdb5a5b/wp-config.php"; if (!file_exists($pd5b162f3)) $v4818b44a94[] = $v7dffdb5a5b; else { $v6490ea3a15 = file_get_contents($pd5b162f3); if (preg_match("/define\s*\(\s*('|\")DB_NAME('|\")\s*,\s*'([^']*)'\s*\)\s*;/", $v6490ea3a15, $v87ae7286da, PREG_OFFSET_CAPTURE)) $pb67a2609 = $v87ae7286da[3][0]; else if (preg_match("/define\s*\(\s*('|\")DB_NAME('|\")\s*,\s*\"([^\"]*)\"\s*\)\s*;/", $v6490ea3a15, $v87ae7286da, PREG_OFFSET_CAPTURE)) $pb67a2609 = $v87ae7286da[3][0]; if (preg_match("/define\s*\(\s*('|\")DB_HOST('|\")\s*,\s*'([^']*)'\s*\)\s*;/", $v6490ea3a15, $v87ae7286da, PREG_OFFSET_CAPTURE)) $pcbf9a3e6 = $v87ae7286da[3][0]; else if (preg_match("/define\s*\(\s*('|\")DB_HOST('|\")\s*,\s*\"([^\"]*)\"\s*\)\s*;/", $v6490ea3a15, $v87ae7286da, PREG_OFFSET_CAPTURE)) $pcbf9a3e6 = $v87ae7286da[3][0]; $v9cd205cadb = explode(":", $pcbf9a3e6); $v771b145261 = $v9cd205cadb[0]; $pd5cc6f73 = $v9cd205cadb[1]; if ($v7f5911d32d["properties"]["host"] != $v771b145261 || $v7f5911d32d["properties"]["port"] != $pd5cc6f73 || $v7f5911d32d["properties"]["db_name"] != $pb67a2609) $v4818b44a94[] = $v7dffdb5a5b; } } } } $pa86fd893 = array_values(array_unique(array_merge($pd776aeb1, $v4818b44a94))); return $pa86fd893; } public function getDeprecatedLayerFolders() { $v1d696dbd12 = $this->v8cd3e3837d->getWorkflowData(); $v73fec76b27 = $this->v994c46e305->getGlobalPaths(); $v24d94846dc = array($this->pfc8be585["db"], $this->pfc8be585["dataaccess"], $this->pfc8be585["businesslogic"], $this->pfc8be585["presentation"]); $pee0fa7a0 = array(); if ($v1d696dbd12 && $v1d696dbd12["tasks"]) foreach ($v1d696dbd12["tasks"] as $v7f5911d32d) if (in_array($v7f5911d32d["tag"], $v24d94846dc)) { self::mac0ace304e61($v7f5911d32d["label"]); $pb77a7e67 = self::mea7bdac9ad9e($v7f5911d32d["label"]); $pee0fa7a0[] = $pb77a7e67; } $v060a90f56f = array(); $v6ee393d9fb = array_diff(scandir($v73fec76b27["LAYER_PATH"]), array('.', '..')); if ($v6ee393d9fb) foreach ($v6ee393d9fb as $v7dffdb5a5b) if (is_dir($v73fec76b27["LAYER_PATH"] . $v7dffdb5a5b) && !in_array($v7dffdb5a5b, $pee0fa7a0)) $v060a90f56f[] = $v7dffdb5a5b; return $v060a90f56f; } private function mf85e2c32b136($pdf77ee66, $pee0fa7a0, $v3893fca883) { $v1d696dbd12 = $this->v8cd3e3837d->getWorkflowData(); $v73fec76b27 = $this->v994c46e305->getGlobalPaths(); $v24d94846dc = array($this->pfc8be585["db"], $this->pfc8be585["dataaccess"], $this->pfc8be585["businesslogic"], $this->pfc8be585["presentation"]); $v5c1c342594 = true; if ($v1d696dbd12 && $v1d696dbd12["tasks"]) { $pff5612c2 = array(); $pdca4e5a9 = false; $v1eb9193558 = new LayoutTypeProjectHandler($pdf77ee66, $this->v3d55458bcd, $this->v5039a77f9d); foreach ($v1d696dbd12["tasks"] as $v7f5911d32d) { if ($v7f5911d32d["tag"] == $this->pfc8be585["db"]) { $v8282c7dd58 = $v7f5911d32d["id"]; $pe2feda50 = $pee0fa7a0[$v8282c7dd58]; self::mac0ace304e61($v7f5911d32d["label"]); $pb77a7e67 = self::mea7bdac9ad9e($v7f5911d32d["label"]); $pff5612c2[$pe2feda50] = $pb77a7e67; } } foreach ($v1d696dbd12["tasks"] as $v7f5911d32d) { if ($v7f5911d32d["tag"] == $this->pfc8be585["dbdriver"]) { $v8282c7dd58 = $v7f5911d32d["id"]; $pe312717c = $v3893fca883[$v8282c7dd58]; if ($pe312717c && $v7f5911d32d["label"] != $pe312717c) { self::mac0ace304e61($pe312717c); $v9ff72eae98 = self::mcb6114f75351($pe312717c); self::mac0ace304e61($v7f5911d32d["label"]); $pff4e506c = self::mcb6114f75351($v7f5911d32d["label"]); foreach ($pff5612c2 as $v0552b7537e => $v33adf304ce) { $v5dafd5d093 = $v73fec76b27["LAYER_PATH"] . "$v0552b7537e/$v9ff72eae98"; $v8e3da78018 = $v73fec76b27["LAYER_PATH"] . "$v33adf304ce/$pff4e506c"; if (!$v1eb9193558->renameLayoutTypePermissionsFromDBDriverPath($v5dafd5d093, $v8e3da78018)) $v5c1c342594 = false; } } } else if (in_array($v7f5911d32d["tag"], $v24d94846dc)) { $v8282c7dd58 = $v7f5911d32d["id"]; $pe2feda50 = $pee0fa7a0[$v8282c7dd58]; if ($pe2feda50) { self::mac0ace304e61($v7f5911d32d["label"]); $pb77a7e67 = self::mea7bdac9ad9e($v7f5911d32d["label"]); $pe03859d1 = $v73fec76b27["LAYER_PATH"] . $pe2feda50; $v71178be245 = $v73fec76b27["LAYER_PATH"] . $pb77a7e67; if ($pb77a7e67 != $pe2feda50 && !file_exists($v71178be245) && is_writable($pe03859d1)) { if (rename($pe03859d1, $v71178be245)) { $pdca4e5a9 = true; if (!$v1eb9193558->renameLayoutTypePermissionsFromLayerPath($pe03859d1, $v71178be245, false)) $v5c1c342594 = false; if ($v5c1c342594 && $v7f5911d32d["tag"] == $this->pfc8be585["presentation"]) { if (!$v1eb9193558->renameLayoutTypesFromLayerPath($pe03859d1, $v71178be245, false)) $v5c1c342594 = false; } } else $v5c1c342594 = false; } } } } if ($pdca4e5a9) { $v1eb9193558->refreshLoadedLayouts(); $v1eb9193558->refreshLoadedLayoutsTypePermissionsByObject(); } } return $v5c1c342594; } public function renameExtraAttributesFiles($v5d3813882f, &$v54817707c7 = false) { $v164e5cf0f6 = array(); $pee0fa7a0 = $v5d3813882f ? $v5d3813882f["tasks_folders"] : null; if ($pee0fa7a0) { $v1d696dbd12 = $this->v8cd3e3837d->getTasksByLayerTag("dbdriver"); if ($v1d696dbd12) { $v677278dc23 = $this->v8cd3e3837d->getTasksByLayerTag("presentation"); $v73fec76b27 = $this->v994c46e305->getGlobalPaths(); $v7392952b05 = array(); if ($v677278dc23) foreach ($v677278dc23 as $v7f5911d32d) { self::mac0ace304e61($v7f5911d32d["label"]); $pb77a7e67 = self::mea7bdac9ad9e($v7f5911d32d["label"]); if (file_exists($v73fec76b27["LAYER_PATH"] . $pb77a7e67)) { $v78e763b3b3 = $v73fec76b27["LAYER_PATH"] . "$pb77a7e67/common/src/module/"; $v6ee393d9fb = file_exists($v78e763b3b3) ? array_diff(scandir($v78e763b3b3), array('.', '..')) : null; if ($v6ee393d9fb) foreach ($v6ee393d9fb as $v7dffdb5a5b) if (is_dir($v78e763b3b3 . $v7dffdb5a5b)) $v7392952b05[] = $v78e763b3b3 . $v7dffdb5a5b . "/"; } } if ($v7392952b05) { $v2ce8e659b6 = $this->v8cd3e3837d->getTasksByLayerTag("businesslogic"); $v25362bdfe1 = $this->v8cd3e3837d->getTasksByLayerTag("dataaccess"); $v471f6cbc62 = $this->getChangedDBDrivers($v5d3813882f); foreach ($v471f6cbc62 as $v50fe47c8bb => $v02a69d4e0f) { $pf511003d = $v02a69d4e0f[0]; $v1bf240bbce = $v02a69d4e0f[1]; $v0911c6122e = strlen($pf511003d); foreach ($v7392952b05 as $pdd397f0a) { $v6ee393d9fb = file_exists($pdd397f0a) ? array_diff(scandir($pdd397f0a), array('.', '..')) : null; if ($v6ee393d9fb) foreach ($v6ee393d9fb as $v7dffdb5a5b) if (!is_dir($pdd397f0a . $v7dffdb5a5b)) { $v872c4849e0 = pathinfo($v7dffdb5a5b); if (strtolower($v872c4849e0["extension"]) == "php" && substr($v872c4849e0["filename"], -20) == "_attributes_settings" && substr($v872c4849e0["filename"], 0, $v0911c6122e + 1) == $pf511003d . "_") { $v0afafb1c12 = true; foreach($v1bf240bbce as $v33e968220a) if (substr($v872c4849e0["filename"], 0, strlen($v33e968220a) + 1) == $v33e968220a . "_") { $v0afafb1c12 = false; break; } if ($v0afafb1c12) { $pe7588214 = $v50fe47c8bb . substr($v7dffdb5a5b, $v0911c6122e); $v54817707c7 = true; if (!rename($pdd397f0a . $v7dffdb5a5b, $pdd397f0a . $pe7588214)) $v164e5cf0f6[] = str_replace($v73fec76b27["LAYER_PATH"], "", $pdd397f0a . $v7dffdb5a5b); else if ($v2ce8e659b6 || $v25362bdfe1) { $pf5bf6141 = substr($v872c4849e0["filename"], $v0911c6122e + 1, -20); $pcd8c70bc = basename($pdd397f0a); $v93e7cbd679 = $pf511003d . "_" . $pf5bf6141; $pf8ebc057 = $v50fe47c8bb . "_" . $pf5bf6141; $pe6d37fc5 = str_replace("_", "", self::mcb6114f75351($v93e7cbd679)); $v046c9efa9e = str_replace("_", "", self::mcb6114f75351($pf8ebc057)); $pbf4e8fd2 = $pe6d37fc5 . "Service"; $v5c46898002 = $v046c9efa9e . "Service"; if ($v2ce8e659b6) foreach ($v2ce8e659b6 as $v7f5911d32d) { self::mac0ace304e61($v7f5911d32d["label"]); $pb77a7e67 = self::mea7bdac9ad9e($v7f5911d32d["label"]); if (file_exists($v73fec76b27["LAYER_PATH"] . $pb77a7e67)) { $pe2af70e4 = $v73fec76b27["LAYER_PATH"] . "$pb77a7e67/module/$pcd8c70bc/"; $pc1171c4b = $pe2af70e4 . $pbf4e8fd2 . ".php"; if (file_exists($pc1171c4b)) { $pfe857155 = $pe2af70e4 . $v5c46898002 . ".php"; if (!file_exists($pfe857155)) { if (!CMSProgramExtraTableInstallationUtil::updateBusinessLogicServiceClassNameInFile($pc1171c4b, $pbf4e8fd2, $v5c46898002) || !CMSProgramExtraTableInstallationUtil::updateOldExtraAttributesTableCode($pc1171c4b, $v93e7cbd679, $pf8ebc057) || !rename($pc1171c4b, $pfe857155)) $v164e5cf0f6[] = str_replace($v73fec76b27["LAYER_PATH"], "", $pfe857155); } else $v164e5cf0f6[] = str_replace($v73fec76b27["LAYER_PATH"], "", $pc1171c4b); } } } if ($v25362bdfe1) foreach ($v25362bdfe1 as $v7f5911d32d) { self::mac0ace304e61($v7f5911d32d["label"]); $pb77a7e67 = self::mea7bdac9ad9e($v7f5911d32d["label"]); if (file_exists($v73fec76b27["LAYER_PATH"] . $pb77a7e67)) { $pe2af70e4 = $v73fec76b27["LAYER_PATH"] . "$pb77a7e67/module/$pcd8c70bc/"; $pc1171c4b = $pe2af70e4 . $v93e7cbd679 . ".xml"; if (file_exists($pc1171c4b)) { $pfe857155 = $pe2af70e4 . $pf8ebc057 . ".xml"; if (!file_exists($pfe857155)) { if (!CMSProgramExtraTableInstallationUtil::updateOldExtraAttributesTableCode($pc1171c4b, $v93e7cbd679, $pf8ebc057, false, true) || !rename($pc1171c4b, $pfe857155)) $v164e5cf0f6[] = str_replace($v73fec76b27["LAYER_PATH"], "", $pfe857155); } else $v164e5cf0f6[] = str_replace($v73fec76b27["LAYER_PATH"], "", $pc1171c4b); } } } } } } } } } } } } return $v164e5cf0f6; } public function getChangedDBDrivers($v5d3813882f = null) { $v471f6cbc62 = array(); $pee0fa7a0 = $v5d3813882f ? $v5d3813882f["tasks_folders"] : null; if ($pee0fa7a0) { $v1d696dbd12 = $this->v8cd3e3837d->getTasksByLayerTag("dbdriver"); if ($v1d696dbd12) { $pe2ae3be9 = count($v1d696dbd12); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051++) { $v7f5911d32d = $v1d696dbd12[$v43dd7d0051]; $v8282c7dd58 = $v7f5911d32d["id"]; $pf511003d = $pee0fa7a0[$v8282c7dd58]; if ($pf511003d) { self::mac0ace304e61($v7f5911d32d["label"]); $v50fe47c8bb = self::mea7bdac9ad9e($v7f5911d32d["label"]); if ($v50fe47c8bb != $pf511003d) { $v1bf240bbce = array(); $v0911c6122e = strlen($pf511003d); for ($v9d27441e80 = 0; $v9d27441e80 < $pe2ae3be9; $v9d27441e80++) { $pc37695cb = $v1d696dbd12[$v9d27441e80]; $v154025f3f4 = $pc37695cb["id"]; if ($v154025f3f4 != $v8282c7dd58) { self::mac0ace304e61($pc37695cb["label"]); $v82dfd45b80 = self::mea7bdac9ad9e($pc37695cb["label"]); if (strlen($v82dfd45b80) > $v0911c6122e && substr($v82dfd45b80, 0, $v0911c6122e) == $pf511003d) $v1bf240bbce[] = $v82dfd45b80; } } $v471f6cbc62[$v50fe47c8bb] = array($pf511003d, $v1bf240bbce); } } } } } return $v471f6cbc62; } public function removeDeprecatedProjectLayouts($pdf77ee66, $pee0fa7a0) { if ($pee0fa7a0) { $v1d696dbd12 = $this->v8cd3e3837d->getWorkflowData(); $v5c1c342594 = true; foreach ($pee0fa7a0 as $v8282c7dd58 => $v9a630a856f) if ($v8282c7dd58 && $v9a630a856f && (!$v1d696dbd12 || !$v1d696dbd12["tasks"] || !$v1d696dbd12["tasks"][$v8282c7dd58])) { $v41b1317965 = $v9a630a856f . "_pl"; $pce995d72 = $this->mdbc503965b4d($v41b1317965); if (file_exists($pce995d72)) { $v49bdd49c66 = self::mcb6114f75351($v9a630a856f); $v8ffce2a791 = $v49bdd49c66 . 'PLayer'; $pa0462a8e = $this->f1844369942($v41b1317965); $v09421cf63e = file_get_contents($pce995d72); if ($v09421cf63e && preg_match('/<bean\s+([^>]*)name\s*=\s*"PresPLayer"([^>]*)>/', $v09421cf63e, $pbae7526c, PREG_OFFSET_CAPTURE)) { $v1eb9193558 = new LayoutTypeProjectHandler($pdf77ee66, $this->v3d55458bcd, $this->v5039a77f9d, $pa0462a8e, $v8ffce2a791); $pa2bba2ac = LAYER_PATH . $v9a630a856f . "/"; if (!$v1eb9193558->removeLayoutFromProjectFolderPath($pa2bba2ac)) $v5c1c342594 = false; } } } } return $v5c1c342594; } public function createSetupDefaultProjectLayouts($pdf77ee66, $v830c8335f6 = true) { $v5c1c342594 = true; $v24bae6e8d7 = $this->v994c46e305->getSetupProjectName(); $v1d696dbd12 = $this->v8cd3e3837d->getTasksByLayerTag("presentation"); foreach ($v1d696dbd12 as $v7f5911d32d) { if (!$v7f5911d32d["properties"]["active"]) continue 1; self::mac0ace304e61($v7f5911d32d["label"]); $pb77a7e67 = self::mea7bdac9ad9e($v7f5911d32d["label"]); $v49bdd49c66 = self::mcb6114f75351($v7f5911d32d["label"]); $v8ffce2a791 = $v49bdd49c66 . 'PLayer'; $v41b1317965 = self::f736d852839($v7f5911d32d["label"]) . "_pl"; $pa0462a8e = $this->f1844369942($v41b1317965); $v1eb9193558 = new LayoutTypeProjectHandler($pdf77ee66, $this->v3d55458bcd, $this->v5039a77f9d, $pa0462a8e, $v8ffce2a791); if (!$v1eb9193558->createPresentationLayerSetupDefaultProjectLayouts($v24bae6e8d7, $v830c8335f6)) $v5c1c342594 = false; } return $v5c1c342594; } private function f57a25426b8() { $v1d696dbd12 = $this->v8cd3e3837d->getWorkflowData(); $v24d94846dc = array($this->pfc8be585["db"], $this->pfc8be585["dataaccess"], $this->pfc8be585["businesslogic"], $this->pfc8be585["presentation"]); if ($v1d696dbd12 && $v1d696dbd12["tasks"]) foreach ($v1d696dbd12["tasks"] as $v7f5911d32d) if (in_array($v7f5911d32d["tag"], $v24d94846dc) && $v7f5911d32d["start"]) { self::mac0ace304e61($v7f5911d32d["label"]); $pb77a7e67 = self::mea7bdac9ad9e($v7f5911d32d["label"]); return $pb77a7e67; } return null; } private function f5893907a6e($v8218e04d0b = array()) { $v5c1c342594 = true; $v8218e04d0b = $v8218e04d0b && !is_array($v8218e04d0b) ? array($v8218e04d0b) : $v8218e04d0b; $v1d696dbd12 = $this->v8cd3e3837d->getTasksByLayerTag("presentation"); foreach ($v1d696dbd12 as $v7f5911d32d) { if (!$v7f5911d32d["properties"]["active"] || ($v8218e04d0b && !in_array($v7f5911d32d["id"], $v8218e04d0b))) continue 1; self::mac0ace304e61($v7f5911d32d["label"]); $pb77a7e67 = self::mea7bdac9ad9e($v7f5911d32d["label"]); $v49bdd49c66 = self::mcb6114f75351($v7f5911d32d["label"]); $v241205aec6 = '<?xml version="1.0" encoding="UTF-8"?>
<beans>
	<import relative="1">app.xml</import>

	<!-- PRESENTATION -->
	<var name="' . $pb77a7e67 . '_p_vars">
		<list>
			<item name="presentations_path"><?php echo LAYER_PATH; ?>' . $pb77a7e67 . '/</item>
			<item name="presentations_modules_file_path"><?php echo LAYER_PATH; ?>' . $pb77a7e67 . '/modules.xml</item>
			<item name="presentation_configs_path">src/config/</item>
			<item name="presentation_utils_path">src/util/</item>
			<item name="presentation_controllers_path">src/controller/</item>
			<item name="presentation_entities_path">src/entity/</item>
			<item name="presentation_views_path">src/view/</item>
			<item name="presentation_templates_path">src/template/</item>
			<item name="presentation_blocks_path">src/block/</item>
			<item name="presentation_modules_path">src/module/</item>
			<item name="presentation_webroot_path">webroot/</item>
	
			<item name="presentation_common_project_name">common</item>
			<item name="presentation_common_path"><?php echo LAYER_PATH; ?>presentation/common/</item>
	
			<!--item name="presentation_files_extension">php</item-->
		</list>
	</var>

	<!-- DISPATCHER CACHE -->
	<var name="' . $pb77a7e67 . '_dispatcher_cache_vars">
		<list>
			<item name="dispatcher_caches_path">src/config/cache/</item>
			<item name="dispatchers_cache_file_name">dispatcher.xml</item>
			<item name="dispatchers_cache_path"><?php echo LAYER_CACHE_PATH;?>' . $pb77a7e67 . '/dispatcher/</item>
			<item name="dispatchers_default_cache_ttl">600</item>
			<item name="dispatchers_default_cache_type">text</item>
			<item name="dispatchers_module_cache_maximum_size"></item>
		</list>
	</var>

	<bean name="' . $v49bdd49c66 . 'PDispatcherCacheHandler" path="lib.org.phpframework.dispatcher.DispatcherCacheHandler">
		<constructor_arg reference="' . $pb77a7e67 . '_dispatcher_cache_vars" />
		<constructor_arg reference="' . $pb77a7e67 . '_p_vars" />
	</bean>

	<!-- PRESENTATION -->'; $v02a69d4e0f = $this->mfc06e2bf54d4($v7f5911d32d); $v241205aec6 .= $v02a69d4e0f[0]; $v4bc43beec7 = $v02a69d4e0f[1]; $v241205aec6 .= '
			
	<bean name="' . $v49bdd49c66 . 'PLayer" path="lib.org.phpframework.layer.presentation.PresentationLayer">
		<constructor_arg reference="' . $pb77a7e67 . '_p_vars" />

		<property name="isDefaultLayer" value="' . ($v7f5911d32d["start"] ? 1 : 0) . '" />
		<property name="cacheLayer" reference="' . $v49bdd49c66 . 'PCacheLayer" />
		<property name="PHPFrameWorkObjName" reference="phpframework_obj_name" />
		' . $v4bc43beec7 . '
	</bean>

	<var name="' . $pb77a7e67 . '_p_cache_vars">
		<list>
			<item name="presentation_caches_path">src/config/cache/</item>
			<item name="presentations_cache_file_name">pages.xml</item>
			<item name="presentations_cache_path"><?php echo LAYER_CACHE_PATH; ?>' . $pb77a7e67 . '/pages/</item>
			<item name="presentations_default_cache_ttl">600</item>
			<item name="presentations_default_cache_type">text</item>
			<item name="presentations_module_cache_maximum_size"></item>
		</list>
	</var>

	<bean name="' . $v49bdd49c66 . 'PCacheLayer" path="lib.org.phpframework.layer.cache.PresentationCacheLayer">
		<constructor_arg reference="' . $v49bdd49c66 . 'PLayer" />
		<constructor_arg reference="' . $pb77a7e67 . '_p_cache_vars" />
	</bean>

	<!-- EVC + CMS LAYER -->
	<bean name="' . $v49bdd49c66 . 'EVC" path="lib.org.phpframework.layer.presentation.evc.EVC">
		<property name="presentationLayer" reference="' . $v49bdd49c66 . 'PLayer" />
		<property name="defaultController">index</property>
	</bean>
	
	<bean name="' . $v49bdd49c66 . 'CMSLayer" path="lib.org.phpframework.layer.presentation.cms.CMSLayer">
		<constructor_arg reference="' . $v49bdd49c66 . 'EVC" />
		
		<property name="cacheLayer" reference="' . $v49bdd49c66 . 'MultipleCMSCacheLayer" />
	</bean>
	
	<function name="setCMSLayer" reference="' . $v49bdd49c66 . 'EVC">
		<parameter reference="' . $v49bdd49c66 . 'CMSLayer" />
	</function>
	
	<var name="' . $pb77a7e67 . '_multiple_cms_cache_vars">
		<list>
			<item name="presentation_cms_module_caches_path">src/config/cache/</item>
			<item name="presentations_cms_module_cache_file_name">modules.xml</item>
			<item name="presentations_cms_module_cache_path"><?php echo LAYER_CACHE_PATH; ?>' . $pb77a7e67 . '/modules/</item>
			<item name="presentations_cms_module_default_cache_ttl">600</item>
			<item name="presentations_cms_module_default_cache_type">text</item>
			<item name="presentations_cms_module_module_cache_maximum_size"></item>
			
			<item name="presentation_cms_block_caches_path">src/config/cache/</item>
			<item name="presentations_cms_block_cache_file_name">blocks.xml</item>
			<item name="presentations_cms_block_cache_path"><?php echo LAYER_CACHE_PATH; ?>' . $pb77a7e67 . '/blocks/</item>
			<item name="presentations_cms_block_default_cache_ttl">600</item>
			<item name="presentations_cms_block_default_cache_type">text</item>
			<item name="presentations_cms_block_module_cache_maximum_size"></item>
		</list>
	</var>
	
	<bean name="' . $v49bdd49c66 . 'MultipleCMSCacheLayer" path="lib.org.phpframework.layer.presentation.cms.cache.MultipleCMSCacheLayer">
		<constructor_arg reference="' . $v49bdd49c66 . 'CMSLayer" />
		<constructor_arg reference="' . $pb77a7e67 . '_multiple_cms_cache_vars" />
	</bean>

	<!-- ROUTER -->
	<var name="' . $pb77a7e67 . '_router_vars">
		<list>
			<item name="routers_path">src/config/</item>
			<item name="routers_file_name">router.xml</item>
		</list>
	</var>

	<bean name="' . $v49bdd49c66 . 'PRouter" path="lib.org.phpframework.router.PresentationRouter">
		<constructor_arg reference="' . $pb77a7e67 . '_router_vars" />

		<property name="presentationLayer" reference="' . $v49bdd49c66 . 'PLayer" />
	</bean>

	<!-- PRESENTATION_DISPATCHER -->
	<bean name="' . $v49bdd49c66 . 'EVCDispatcher" path="lib.org.phpframework.dispatcher.EVCDispatcher">
		<property name="router" reference="' . $v49bdd49c66 . 'PRouter" />
		<property name="EVC" reference="' . $v49bdd49c66 . 'EVC" />
	</bean>
	<!--bean name="' . $v49bdd49c66 . 'EVCDispatcher" path="org.phpframework.dispatcher.PresentationDispatcher" path_prefix="<?php echo LIB_PATH;?>">
		<property name="router" reference="' . $v49bdd49c66 . 'PRouter" />
		<property name="presentationLayer" reference="' . $v49bdd49c66 . 'PLayer" />
	</bean-->
</beans>'; if (!$this->f94a03ace64(self::f736d852839($v7f5911d32d["label"]) . "_pl", $v241205aec6)) $v5c1c342594 = false; } return $v5c1c342594; } private function f3bb0cb9725($v8218e04d0b = array()) { $v5c1c342594 = true; $v8218e04d0b = $v8218e04d0b && !is_array($v8218e04d0b) ? array($v8218e04d0b) : $v8218e04d0b; $v1d696dbd12 = $this->v8cd3e3837d->getTasksByLayerTag("businesslogic"); foreach ($v1d696dbd12 as $v7f5911d32d) { if (!$v7f5911d32d["properties"]["active"] || ($v8218e04d0b && !in_array($v7f5911d32d["id"], $v8218e04d0b))) continue 1; self::mac0ace304e61($v7f5911d32d["label"]); $pb77a7e67 = self::mea7bdac9ad9e($v7f5911d32d["label"]); $v49bdd49c66 = self::mcb6114f75351($v7f5911d32d["label"]); $v241205aec6 = '<?xml version="1.0" encoding="UTF-8"?>
<beans>
	<bean name="CommonService" namespace="' . $pb77a7e67 . '" path="CommonService" path_prefix="<?php echo $vars["business_logic_modules_common_path"];?>" extension="php">
		<property name="PHPFrameWorkObjName"><?php echo $vars["phpframework_obj_name"] ? $vars["phpframework_obj_name"] : $objs["phpframework_obj_name"]; ?></property>
		<property name="businessLogicLayer" reference="' . $v49bdd49c66 . 'BLLayer" />
		<property name="userCacheHandler" reference="UserCacheHandler" />
	</bean>
</beans>'; if (!$this->f94a03ace64(self::f736d852839($v7f5911d32d["label"]) . "_bll_common_services", $v241205aec6)) $v5c1c342594 = false; } return $v5c1c342594; } private function f637963f631($v8218e04d0b = array()) { $v5c1c342594 = true; $v8218e04d0b = $v8218e04d0b && !is_array($v8218e04d0b) ? array($v8218e04d0b) : $v8218e04d0b; $v1d696dbd12 = $this->v8cd3e3837d->getTasksByLayerTag("businesslogic"); foreach ($v1d696dbd12 as $v7f5911d32d) { if (!$v7f5911d32d["properties"]["active"] || ($v8218e04d0b && !in_array($v7f5911d32d["id"], $v8218e04d0b))) continue 1; self::mac0ace304e61($v7f5911d32d["label"]); $pb77a7e67 = self::mea7bdac9ad9e($v7f5911d32d["label"]); $v49bdd49c66 = self::mcb6114f75351($v7f5911d32d["label"]); $v241205aec6 = '<?xml version="1.0" encoding="UTF-8"?>
<beans>
	<import relative="1">app.xml</import>
	
	<!-- BUSINESS LOGIC -->'; $v02a69d4e0f = $this->mfc06e2bf54d4($v7f5911d32d); $v241205aec6 .= $v02a69d4e0f[0]; $v4bc43beec7 = $v02a69d4e0f[1]; $v241205aec6 .= '

	<var name="' . $pb77a7e67 . '_business_logic_vars">
		<list>
			<item name="business_logic_path"><?php echo LAYER_PATH; ?>' . $pb77a7e67 . '/</item>
			<item name="business_logic_modules_file_path"><?php echo LAYER_PATH; ?>' . $pb77a7e67 . '/modules.xml</item>
			<item name="business_logic_services_file_name">services.xml</item>
	
			<item name="business_logic_modules_common_name">common</item>
			<item name="business_logic_modules_common_path"><?php echo LAYER_PATH; ?>' . $pb77a7e67 . '/common/</item>
			<item name="business_logic_modules_service_common_file_path"><?php echo LAYER_PATH; ?>' . $pb77a7e67 . '/common/CommonService.php</item>
	
			<item name="business_logic_services_annotations_enabled">1</item>
		</list>
	</var>

	<bean name="' . $v49bdd49c66 . 'BLLayer" path="lib.org.phpframework.layer.businesslogic.BusinessLogicLayer">
		<constructor_arg reference="' . $pb77a7e67 . '_business_logic_vars" />

		<property name="isDefaultLayer" value="' . ($v7f5911d32d["start"] ? 1 : 0) . '" />
		<property name="cacheLayer" reference="' . $v49bdd49c66 . 'BLCacheLayer" />
		<property name="PHPFrameWorkObjName" reference="phpframework_obj_name" />
		<property name="docBlockParser" reference="' . $v49bdd49c66 . 'BLDocBlockParser" />
		' . $v4bc43beec7 . '
	</bean>
	' . $this->meaae65e4b8c9($v7f5911d32d) . '

	<var name="' . $pb77a7e67 . '_business_logic_cache_vars">
		<list>
			<item name="business_logic_cache_file_name">cache.xml</item>
			<item name="business_logic_cache_path"><?php echo LAYER_CACHE_PATH; ?>' . $pb77a7e67 . '/</item>
			<item name="business_logic_default_cache_ttl">600</item>
			<item name="business_logic_module_cache_maximum_size"></item>
		</list>
	</var>

	<bean name="' . $v49bdd49c66 . 'BLCacheLayer" path="lib.org.phpframework.layer.cache.BusinessLogicCacheLayer">
		<constructor_arg reference="' . $v49bdd49c66 . 'BLLayer" />
		<constructor_arg reference="' . $pb77a7e67 . '_business_logic_cache_vars" />
	</bean>

	<bean name="' . $v49bdd49c66 . 'BLDocBlockParser" path="org.phpframework.phpscript.docblock.DocBlockParser">
		<property name="cacheHandler" reference="' . $v49bdd49c66 . 'BLDocBlockParserCacheHandler" />
	</bean>

	<bean name="' . $v49bdd49c66 . 'BLDocBlockParserCacheHandler" path="org.phpframework.cache.user.filesystem.FileSystemUserCacheHandler">
		<property name="rootPath"><?php echo LAYER_CACHE_PATH; ?>' . $pb77a7e67 . '/annotations/</property>
	</bean>
</beans>'; if (!$this->f94a03ace64(self::f736d852839($v7f5911d32d["label"]) . "_bll", $v241205aec6, $v7f5911d32d["properties"])) $v5c1c342594 = false; } return $v5c1c342594; } private function md1a96577b4ca($v8218e04d0b = array()) { $v5c1c342594 = true; $v8218e04d0b = $v8218e04d0b && !is_array($v8218e04d0b) ? array($v8218e04d0b) : $v8218e04d0b; $v1d696dbd12 = $this->v8cd3e3837d->getTasksByLayerTag("dataaccess"); foreach ($v1d696dbd12 as $v7f5911d32d) { if (!$v7f5911d32d["properties"]["active"] || ($v8218e04d0b && !in_array($v7f5911d32d["id"], $v8218e04d0b))) continue 1; self::mac0ace304e61($v7f5911d32d["label"]); $pb77a7e67 = self::mea7bdac9ad9e($v7f5911d32d["label"]); $v49bdd49c66 = self::mcb6114f75351($v7f5911d32d["label"]); $v241205aec6 = '<?xml version="1.0" encoding="UTF-8"?>
<beans>
	<import relative="1">app.xml</import>
	'; $v02a69d4e0f = $this->mfc06e2bf54d4($v7f5911d32d); $v241205aec6 .= $v02a69d4e0f[0]; $v4bc43beec7 = $v02a69d4e0f[1]; if ($v7f5911d32d["properties"]["type"] == "ibatis") { $v241205aec6 .= '
	<!-- IBATIS -->
	<bean name="' . $v49bdd49c66 . 'IClient" path="lib.org.phpframework.sqlmap.ibatis.IBatisClient"></bean>

	<var name="' . $pb77a7e67 . '_ida_vars">
		<list>
			<item name="dal_path"><?php echo LAYER_PATH; ?>' . $pb77a7e67 . '/</item>
			<item name="dal_modules_file_path"><?php echo LAYER_PATH; ?>' . $pb77a7e67 . '/modules.xml</item>
			<item name="dal_services_file_name">services.xml</item>
		</list>
	</var>

	<bean name="' . $v49bdd49c66 . 'IDALayer" path="lib.org.phpframework.layer.dataaccess.IbatisDataAccessLayer">
		<constructor_arg reference="' . $v49bdd49c66 . 'IClient" />
		<constructor_arg reference="' . $pb77a7e67 . '_ida_vars" />

		<property name="isDefaultLayer" value="' . ($v7f5911d32d["start"] ? 1 : 0) . '" />
		<property name="cacheLayer" reference="' . $v49bdd49c66 . 'IDACacheLayer" />
		<property name="PHPFrameWorkObjName" reference="phpframework_obj_name" />
		' . $v4bc43beec7 . '

		<function name="setDefaultBrokerName">
			<parameter value="&lt;?php echo $GLOBALS[\'default_db_broker\']; ?>" />
		</function>
	</bean>
	' . $this->meaae65e4b8c9($v7f5911d32d) . '

	<var name="' . $pb77a7e67 . '_ida_cache_vars">
		<list>
			<item name="dal_cache_file_name">cache.xml</item>
			<item name="dal_cache_path"><?php echo LAYER_CACHE_PATH; ?>' . $pb77a7e67 . '/</item>
			<item name="dal_default_cache_ttl">600</item>
			<item name="dal_module_cache_maximum_size"></item>
		</list>
	</var>

	<bean name="' . $v49bdd49c66 . 'IDACacheLayer" path="lib.org.phpframework.layer.cache.DataAccessCacheLayer">
		<constructor_arg reference="' . $v49bdd49c66 . 'IDALayer" />
		<constructor_arg reference="' . $pb77a7e67 . '_ida_cache_vars" />
	</bean>'; } else { $v241205aec6 .= '
	<!-- HIBERNATE -->
	<bean name="' . $v49bdd49c66 . 'HClient" path="lib.org.phpframework.sqlmap.hibernate.HibernateClient"></bean>

	<var name="' . $pb77a7e67 . '_hda_vars">
		<list>
			<item name="dal_path"><?php echo LAYER_PATH; ?>' . $pb77a7e67 . '/</item>
			<item name="dal_modules_file_path"><?php echo LAYER_PATH; ?>' . $pb77a7e67 . '/modules.xml</item>
			<item name="dal_services_file_name">services.xml</item>
		</list>
	</var>

	<bean name="' . $v49bdd49c66 . 'HDALayer" path="lib.org.phpframework.layer.dataaccess.HibernateDataAccessLayer">
		<constructor_arg reference="' . $v49bdd49c66 . 'HClient" /> 
		<constructor_arg reference="' . $pb77a7e67 . '_hda_vars" />

		<property name="isDefaultLayer" value="' . ($v7f5911d32d["start"] ? 1 : 0) . '" />
		<property name="cacheLayer" reference="' . $v49bdd49c66 . 'HDACacheLayer" />
		<property name="PHPFrameWorkObjName" reference="phpframework_obj_name" />
		' . $v4bc43beec7 . '

		<function name="setDefaultBrokerName">
			<parameter value="&lt;?php echo $GLOBALS[\'default_db_broker\']; ?>" />
		</function>
	</bean>
	' . $this->meaae65e4b8c9($v7f5911d32d) . '

	<var name="' . $pb77a7e67 . '_hda_cache_vars">
		<list>
			<item name="dal_cache_file_name">cache.xml</item>
			<item name="dal_cache_path"><?php echo LAYER_CACHE_PATH; ?>' . $pb77a7e67 . '/</item>
			<item name="dal_default_cache_ttl">600</item>
			<item name="dal_module_cache_maximum_size"></item>
		</list>
	</var>

	<bean name="' . $v49bdd49c66 . 'HDACacheLayer" path="lib.org.phpframework.layer.cache.DataAccessCacheLayer">
		<constructor_arg reference="' . $v49bdd49c66 . 'HDALayer" />
		<constructor_arg reference="' . $pb77a7e67 . '_hda_cache_vars" />
	</bean>'; } $v241205aec6 .= '
</beans>'; if (!$this->f94a03ace64(self::f736d852839($v7f5911d32d["label"]) . "_dal", $v241205aec6, $v7f5911d32d["properties"])) $v5c1c342594 = false; } return $v5c1c342594; } private function mcebcbe562723($v8218e04d0b = array()) { $v5c1c342594 = true; $v8218e04d0b = $v8218e04d0b && !is_array($v8218e04d0b) ? array($v8218e04d0b) : $v8218e04d0b; $v1d696dbd12 = $this->v8cd3e3837d->getTasksByLayerTag("db"); foreach ($v1d696dbd12 as $v7f5911d32d) { if (!$v7f5911d32d["properties"]["active"] || ($v8218e04d0b && !in_array($v7f5911d32d["id"], $v8218e04d0b))) continue 1; self::mac0ace304e61($v7f5911d32d["label"]); $pb77a7e67 = self::mea7bdac9ad9e($v7f5911d32d["label"]); $v49bdd49c66 = self::mcb6114f75351($v7f5911d32d["label"]); $v241205aec6 = '<?xml version="1.0" encoding="UTF-8"?>
<beans>
	<import relative="1">app.xml</import>'; $v02a69d4e0f = $this->mfc06e2bf54d4($v7f5911d32d); $v241205aec6 .= $v02a69d4e0f[0]; $v4bc43beec7 = $v02a69d4e0f[1]; $v241205aec6 .= '

	<!-- DB -->
	<var name="' . $pb77a7e67 . '_dbl_vars">
		<list>
			<item name="dbl_path"><?php echo LAYER_PATH; ?>' . $pb77a7e67 . '/</item>
		</list>
	</var>
	
	<bean name="' . $v49bdd49c66 . 'DBLayer" path="lib.org.phpframework.layer.db.DBLayer">
		<constructor_arg reference="' . $pb77a7e67 . '_dbl_vars" />
		
		<property name="isDefaultLayer" value="' . ($v7f5911d32d["start"] ? 1 : 0) . '" />
		<property name="cacheLayer" reference="' . $v49bdd49c66 . 'DBCacheLayer" />
		<property name="PHPFrameWorkObjName" reference="phpframework_obj_name" />
		' . $v4bc43beec7 . '

		<function name="setDefaultBrokerName">
			<parameter value="&lt;?php echo $GLOBALS[\'default_db_driver\']; ?>" />
		</function>
	</bean>
	' . $this->meaae65e4b8c9($v7f5911d32d) . '
	
	<var name="' . $pb77a7e67 . '_dbl_cache_vars">
		<list>
			<item name="dbl_cache_file_name">cache.xml</item>
			<item name="dbl_cache_path"><?php echo LAYER_CACHE_PATH; ?>' . $pb77a7e67 . '/</item>
			<item name="dbl_default_cache_ttl">600</item>
			<item name="dbl_module_cache_maximum_size"></item>
		</list>
	</var>
	
	<bean name="' . $v49bdd49c66 . 'DBCacheLayer" path="lib.org.phpframework.layer.cache.DBCacheLayer">
		<constructor_arg reference="' . $v49bdd49c66 . 'DBLayer" />
		<constructor_arg reference="' . $pb77a7e67 . '_dbl_cache_vars" />
	</bean>
</beans>'; if (!$this->f94a03ace64(self::f736d852839($v7f5911d32d["label"]) . "_dbl", $v241205aec6, $v7f5911d32d["properties"])) $v5c1c342594 = false; } return $v5c1c342594; } private function md7749ec7c299($v8218e04d0b = array()) { $v5c1c342594 = true; $v8218e04d0b = $v8218e04d0b && !is_array($v8218e04d0b) ? array($v8218e04d0b) : $v8218e04d0b; $v1d696dbd12 = $this->v8cd3e3837d->getTasksByLayerTag("dbdriver"); foreach ($v1d696dbd12 as $v7f5911d32d) { if (!$v7f5911d32d["properties"]["active"] || ($v8218e04d0b && !in_array($v7f5911d32d["id"], $v8218e04d0b))) continue 1; self::mac0ace304e61($v7f5911d32d["label"]); $pb77a7e67 = self::mea7bdac9ad9e($v7f5911d32d["label"]); $v49bdd49c66 = self::mcb6114f75351($v7f5911d32d["label"]); $v1335217393 = DB::getDriverClassNameByType($v7f5911d32d["properties"]["type"]); $v3ae55a9a2e = "lib.org.phpframework.db.driver." . ($v1335217393 ? $v1335217393 : "MySqlDB"); $v241205aec6 = '<?xml version="1.0" encoding="UTF-8"?>
<beans>
	<import relative="1">app.xml</import>

	<!-- DRIVER -->
	<var name="' . $pb77a7e67 . '_options">
		<list>'; $v6b274fa9e2 = array("host", "db_name", "username", "password", "schema", "odbc_data_source", "odbc_driver", "extra_dsn"); foreach ($v7f5911d32d["properties"] as $pe149db72 => $v1d88a54df5) if (!in_array($pe149db72, array("type", "active"))) $v241205aec6 .= '
			<item name="' . $pe149db72 . '">' . (in_array($pe149db72, $v6b274fa9e2) ? '<![CDATA[' . self::mf73c700ed652($v1d88a54df5) . ']]>' : self::mf73c700ed652($v1d88a54df5)) . '</item>'; $v241205aec6 .= '
		</list>
	</var>
	<bean name="' . $v49bdd49c66 . '" path="' . $v3ae55a9a2e . '" bean_group="dbdriver">
		<function name="setOptions">
			<parameter reference="' . $pb77a7e67 . '_options" />
		</function>
	</bean>
</beans>'; if (!$this->f94a03ace64(self::f736d852839($v7f5911d32d["label"]) . "_dbdriver", $v241205aec6)) $v5c1c342594 = false; } return $v5c1c342594; } private function mced76e6cc9cb() { $v241205aec6 = '<?xml version="1.0" encoding="UTF-8"?>
<beans>
	<var name="phpframework_obj_name">PHPFrameWork</var>
	
	<!-- MESSAGE -->
	<var name="message_vars">
		<list>
			<item name="messages_path"><?php echo CMS_PATH; ?>other/data/messages/</item>
			<item name="messages_modules_file_path"><?php echo CMS_PATH; ?>other/data/messages/modules.xml</item>
			<item name="messages_cache_path"><?php echo LAYER_CACHE_PATH; ?>messages/</item>
			<item name="messages_module_cache_maximum_size"></item>
			<item name="messages_default_cache_ttl"><?php echo 365 * 24 * 60 * 60; ?></item>
			<item name="messages_default_cache_type">php</item>
		</list>
	</var>
	
	<bean name="MessageHandler" path="lib.org.phpframework.message.MessageHandler">
		<constructor_arg reference="message_vars" />
	</bean>
	
	<bean name="UserCacheHandler" path="org.phpframework.cache.user.filesystem.FileSystemUserCacheHandler" path_prefix="<?php echo LIB_PATH;?>">
		<property name="rootPath"><?php echo CACHE_PATH; ?>user_cache/</property>
	</bean>
	
	<!-- LOG -->
	<!-- Only change the LOG_HANDLER if you want to have your own class. But your own class must be an extend of the org.phpframework.log.LogHandler or must implements the interface org.phpframework.log.ILogHandler -->
	<!--bean name="LogHandler" path="org.phpframework.log.LogHandler" path_prefix="<?php echo LIB_PATH;?>"></bean-->
	
	<var name="log_vars">
		<list>
			<item name="log_level">&lt;?php echo $GLOBALS["log_level"]; ?&gt;</item>
			<item name="log_echo_active">&lt;?php echo $GLOBALS["log_echo_active"]; ?&gt;</item>
			<item name="log_file_path">&lt;?php echo $GLOBALS["log_file_path"]; ?&gt;</item>
			<item name="log_css"><![CDATA[
				body {
					overflow:overlay;
					background-color:#F0F1F5;
					font-family:verdana,arial,courier;
					font-size:11px;
				}
				.log_handler {
					font-style:italic;
					
					color:#83889E;
					/*background-color:#F8F9FC;
					border:1px outset #BFC4DB;
					border-radius:5px;*/
					margin:10px;
					padding:5px;
				}
				
				.log_handler .message {
					color:#333;
					position:relative;
				}
				.log_handler .message .exception {
					color:#FF0000;
				}
				.log_handler .message .error {
					color:#990000;
				}
				.log_handler .message .info {
					color:#000099;
				}
				.log_handler .message .debug {
					color:#009999;
				}
				.log_handler .message .message {
					color:#009900;
				}
				.log_handler .message .toggle_trace {
					margin-right:10px;
					display:inline-block;
					font-style:normal;
					font-weight:bold;
					cursor:pointer;
				}
				.log_handler .message p {
					margin:0;
					padding:0;
				}
				.log_handler .trace {
					margin-top:15px;
					font-size:10px;
				}
				.log_handler .trace.hidden {
					display:none;
				}
				.log_handler .trace .exception {
					white-space:nowrap;
				}
			]]></item>
		</list>
	</var>
</beans>'; return $this->f94a03ace64("app", $v241205aec6); } private function mfc06e2bf54d4($v7f5911d32d) { $pd0ce49e4 = ''; $v4bc43beec7 = ''; $pca9f244a = array(); switch($v7f5911d32d["tag"]) { case $this->pfc8be585["db"]: $pca9f244a = array( $this->pfc8be585["dbdriver"] ); break; case $this->pfc8be585["dataaccess"]: $pca9f244a = array( $this->pfc8be585["db"] ); break; case $this->pfc8be585["businesslogic"]: case $this->pfc8be585["presentation"]: $pca9f244a = array( $this->pfc8be585["businesslogic"], $this->pfc8be585["dataaccess"], $this->pfc8be585["db"] ); break; } if (!empty($v7f5911d32d["exits"]["layer_exit"])) { $v6939304e91 = isset($v7f5911d32d["exits"]["layer_exit"][0]) ? $v7f5911d32d["exits"]["layer_exit"] : array($v7f5911d32d["exits"]["layer_exit"]); if ($v6939304e91) { $v49bdd49c66 = self::mcb6114f75351($v7f5911d32d["label"]); $v1d696dbd12 = $this->v8cd3e3837d->getWorkflowData(); $v3b29dfd1b9 = array(); $pc37695cb = count($v6939304e91); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { foreach ($v1d696dbd12["tasks"] as $pe7f1e9cf) { if ($pe7f1e9cf["id"] == $v6939304e91[$v43dd7d0051]["task_id"] && in_array($pe7f1e9cf["tag"], $pca9f244a) && $pe7f1e9cf["properties"]["active"]) { self::mac0ace304e61($pe7f1e9cf["label"]); $pc08d1c60 = self::mea7bdac9ad9e($pe7f1e9cf["label"]); $v4666f035e6 = self::mcb6114f75351($pe7f1e9cf["label"]); $pc7752d7e = self::f6c6524d3a5($pe7f1e9cf["label"]); $v89d5e3abfb = $pe7f1e9cf["properties"]["layer_brokers"]; $v3033e34359 = $v6939304e91[$v43dd7d0051]["properties"]; $v002556527f = $v3033e34359["connection_type"]; $v890189c188 = "lib.org.phpframework.broker.client.local.Local"; $v7f47ccc31b = ""; $pc2cdfd1b = ""; if (!$v89d5e3abfb[$v002556527f]["active"]) $v002556527f = ""; if (!$v3b29dfd1b9[ $pe7f1e9cf["id"] ] || !in_array($v002556527f, $v3b29dfd1b9[ $pe7f1e9cf["id"] ])) { $v3b29dfd1b9[ $pe7f1e9cf["id"] ][] = $v002556527f; if ($v002556527f) { $v890189c188 = "lib.org.phpframework.broker.client.$v002556527f." . strtoupper($v002556527f); $v7f47ccc31b = self::f18f75624e4($v002556527f); if (is_array($v3033e34359["connection_settings"]) && $v3033e34359["connection_settings"]["vars_name"]) { $v45de354860 = $v3033e34359["connection_settings"]["vars_name"]; $v61fc24eb6d = $v3033e34359["connection_settings"]["vars_value"]; $v3033e34359["connection_settings"] = array(); if (!is_array($v45de354860)) { $v45de354860 = array($v45de354860); $v61fc24eb6d = array($v61fc24eb6d); } foreach ($v45de354860 as $pd69fb7d0 => $v5e813b295b) if ($v5e813b295b) $v3033e34359["connection_settings"][$v5e813b295b] = $v61fc24eb6d[$pd69fb7d0]; } else $v3033e34359["connection_settings"] = array(); if ($v89d5e3abfb[$v002556527f]) { $v3033e34359["connection_settings"] = array_merge($v89d5e3abfb[$v002556527f], $v3033e34359["connection_settings"]); unset($v3033e34359["connection_settings"]["active"]); unset($v3033e34359["connection_settings"]["other_settings"]); } $v3033e34359["connection_settings"]["response_type"] = $v3033e34359["connection_response_type"]; $pc2cdfd1b = self::f3820df7a8a($v3033e34359); $pc2cdfd1b = $pc2cdfd1b ? $pc2cdfd1b . "\n\t" : ""; } if ($pe7f1e9cf["tag"] == $this->pfc8be585["dataaccess"]) { if ($pe7f1e9cf["properties"]["type"] == "ibatis") { if ($v002556527f) $pd0ce49e4 .= '
	<bean name="' . $v49bdd49c66 . $v4666f035e6 . 'IDABrokerClient" path="' . $v890189c188 . 'IbatisDataAccessBrokerClient">' . $pc2cdfd1b . '</bean>'; else $pd0ce49e4 .= '
	<bean name="' . $v49bdd49c66 . $v4666f035e6 . 'IDABrokerClient" path="' . $v890189c188 . 'IbatisDataAccessBrokerClient">
		<property name="PHPFrameWorkObjName" reference="phpframework_obj_name" />

		<function name="addBeansFilePath">
			<parameter><?php echo BEAN_PATH; ?>' . $pc08d1c60 . '_dal.xml</parameter>
		</function>
		<function name="setBeanName">
			<parameter>' . $v7f47ccc31b . $v4666f035e6 . 'IDABrokerServer</parameter>
		</function>
	</bean>'; $v4bc43beec7 .= '
		<function name="addBroker">
			<parameter reference="' . $v49bdd49c66 . $v4666f035e6 . 'IDABrokerClient" />
			<parameter value="' . $pc7752d7e . '" />
		</function>'; } else { if ($v002556527f) $pd0ce49e4 .= '
	<bean name="' . $v49bdd49c66 . $v4666f035e6 . 'HDABrokerClient" path="' . $v890189c188 . 'HibernateDataAccessBrokerClient">' . $pc2cdfd1b . '</bean>'; else $pd0ce49e4 .= '
	<bean name="' . $v49bdd49c66 . $v4666f035e6 . 'HDABrokerClient" path="' . $v890189c188 . 'HibernateDataAccessBrokerClient">
		<property name="PHPFrameWorkObjName" reference="phpframework_obj_name" />

		<function name="addBeansFilePath">
			<parameter><?php echo BEAN_PATH; ?>' . $pc08d1c60 . '_dal.xml</parameter>
		</function>
		<function name="setBeanName">
			<parameter>' . $v7f47ccc31b . $v4666f035e6 . 'HDABrokerServer</parameter>
		</function>
	</bean>'; $v4bc43beec7 .= '
		<function name="addBroker">
			<parameter reference="' . $v49bdd49c66 . $v4666f035e6 . 'HDABrokerClient" />
			<parameter value="' . $pc7752d7e . '" />
		</function>'; } break; } else if ($pe7f1e9cf["tag"] == $this->pfc8be585["businesslogic"]) { if ($v002556527f) $pd0ce49e4 .= '
	<bean name="' . $v49bdd49c66 . $v4666f035e6 . 'BLBrokerClient" path="' . $v890189c188 . 'BusinessLogicBrokerClient">' . $pc2cdfd1b . '</bean>'; else $pd0ce49e4 .= '
	<bean name="' . $v49bdd49c66 . $v4666f035e6 . 'BLBrokerClient" path="' . $v890189c188 . 'BusinessLogicBrokerClient">
		<property name="PHPFrameWorkObjName" reference="phpframework_obj_name" />

		<function name="addBeansFilePath">
			<parameter><?php echo BEAN_PATH; ?>' . $pc08d1c60 . '_bll.xml</parameter>
		</function>
		<function name="setBeanName">
			<parameter>' . $v7f47ccc31b . $v4666f035e6 . 'BLBrokerServer</parameter>
		</function>
	</bean>'; $v4bc43beec7 .= '
		<function name="addBroker">
			<parameter reference="' . $v49bdd49c66 . $v4666f035e6 . 'BLBrokerClient" />
			<parameter value="' . $pc7752d7e . '" />
		</function>'; break; } else if ($pe7f1e9cf["tag"] == $this->pfc8be585["db"]) { if ($v002556527f) $pd0ce49e4 .= '
	<bean name="' . $v49bdd49c66 . $v4666f035e6 . 'DBBrokerClient" path="' . $v890189c188 . 'DBBrokerClient">' . $pc2cdfd1b . '</bean>'; else $pd0ce49e4 .= '
	<bean name="' . $v49bdd49c66 . $v4666f035e6 . 'DBBrokerClient" path="' . $v890189c188 . 'DBBrokerClient">
		<property name="PHPFrameWorkObjName" reference="phpframework_obj_name" />

		<function name="addBeansFilePath">
			<parameter><?php echo BEAN_PATH; ?>' . $pc08d1c60 . '_dbl.xml</parameter>
		</function>
		<function name="setBeanName">
			<parameter>' . $v7f47ccc31b . $v4666f035e6 . 'DBBrokerServer</parameter>
		</function>
	</bean>'; $v4bc43beec7 .= '
		<function name="addBroker">
			<parameter reference="' . $v49bdd49c66 . $v4666f035e6 . 'DBBrokerClient" />
			<parameter value="' . $pc7752d7e . '" />
		</function>'; break; } else if ($pe7f1e9cf["tag"] == $this->pfc8be585["dbdriver"]) { $pd0ce49e4 .= '
	<import relative="1">' . self::f736d852839($pe7f1e9cf["label"]) . '_dbdriver.xml</import>'; $v4bc43beec7 .= '
		<function name="addBroker">
			<parameter reference="' . $v4666f035e6 . '" />
			<parameter value="' . $pc7752d7e . '" />
		</function>'; break; } } } } } } } return array($pd0ce49e4, $v4bc43beec7); } private function meaae65e4b8c9($v7f5911d32d) { $paffeffd6 = ''; $v8282c7dd58 = $v7f5911d32d["id"]; $v37adac6c55 = array(); switch($v7f5911d32d["tag"]) { case $this->pfc8be585["dbdriver"]: $v37adac6c55 = array( $this->pfc8be585["db"], $this->pfc8be585["businesslogic"], $this->pfc8be585["presentation"] ); break; case $this->pfc8be585["db"]: $v37adac6c55 = array( $this->pfc8be585["dataaccess"], $this->pfc8be585["businesslogic"], $this->pfc8be585["presentation"] ); break; case $this->pfc8be585["dataaccess"]: $v37adac6c55 = array( $this->pfc8be585["businesslogic"], $this->pfc8be585["presentation"] ); break; case $this->pfc8be585["businesslogic"]: $v37adac6c55 = array( $this->pfc8be585["businesslogic"], $this->pfc8be585["presentation"] ); break; case $this->pfc8be585["presentation"]: $v37adac6c55 = array(); break; } if ($v37adac6c55) { $v49bdd49c66 = self::mcb6114f75351($v7f5911d32d["label"]); $v1d696dbd12 = $this->v8cd3e3837d->getWorkflowData(); $v484bcb5f4d = false; foreach ($v1d696dbd12["tasks"] as $pe7f1e9cf) if (!empty($pe7f1e9cf["exits"]["layer_exit"]) && $pe7f1e9cf["properties"]["active"]) { $v6939304e91 = isset($pe7f1e9cf["exits"]["layer_exit"][0]) ? $pe7f1e9cf["exits"]["layer_exit"] : array($pe7f1e9cf["exits"]["layer_exit"]); if ($v6939304e91) { $pc37695cb = count($v6939304e91); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) if ($v6939304e91[$v43dd7d0051]["task_id"] == $v8282c7dd58 && in_array($pe7f1e9cf["tag"], $v37adac6c55)) { $v3033e34359 = $v6939304e91[$v43dd7d0051]["properties"]; $v002556527f = $v3033e34359["connection_type"]; if (!$v002556527f || !$v7f5911d32d["properties"]["layer_brokers"][$v002556527f]["active"]) { $v484bcb5f4d = true; break; } } if ($v484bcb5f4d) break; } } if ($v484bcb5f4d) { if ($v7f5911d32d["tag"] == $this->pfc8be585["dataaccess"]) { if ($v7f5911d32d["properties"]["type"] == "ibatis") $paffeffd6 .= '
	<bean name="' . $v49bdd49c66 . 'IDABrokerServer" path="lib.org.phpframework.broker.server.local.LocalIbatisDataAccessBrokerServer">
		<constructor_arg reference="' . $v49bdd49c66 . 'IDALayer" />
	</bean>'; else $paffeffd6 .= '
	<bean name="' . $v49bdd49c66 . 'HDABrokerServer" path="lib.org.phpframework.broker.server.local.LocalHibernateDataAccessBrokerServer">
		<constructor_arg reference="' . $v49bdd49c66 . 'HDALayer" />
	</bean>'; } else if ($v7f5911d32d["tag"] == $this->pfc8be585["businesslogic"]) $paffeffd6 .= '
	<bean name="' . $v49bdd49c66 . 'BLBrokerServer" path="lib.org.phpframework.broker.server.local.LocalBusinessLogicBrokerServer">
		<constructor_arg reference="' . $v49bdd49c66 . 'BLLayer" />
	</bean>'; else if ($v7f5911d32d["tag"] == $this->pfc8be585["db"]) $paffeffd6 .= '
	<bean name="' . $v49bdd49c66 . 'DBBrokerServer" path="lib.org.phpframework.broker.server.local.LocalDBBrokerServer">
		<constructor_arg reference="' . $v49bdd49c66 . 'DBLayer" />
	</bean>'; } $v7a4c0ba9ac = $v7f5911d32d["properties"]["layer_brokers"]; if ($v7a4c0ba9ac) foreach ($v7a4c0ba9ac as $v676be2c810 => $v6e62221446) if ($v6e62221446["active"]) { $v890189c188 = "lib.org.phpframework.broker.server.$v676be2c810." . strtoupper($v676be2c810); $v7f47ccc31b = self::f18f75624e4($v676be2c810); if (is_array($v6e62221446["other_settings"]) && $v6e62221446["other_settings"]["vars_name"]) { $v45de354860 = $v6e62221446["other_settings"]["vars_name"]; $v61fc24eb6d = $v6e62221446["other_settings"]["vars_value"]; if (!is_array($v45de354860)) { $v45de354860 = array($v45de354860); $v61fc24eb6d = array($v61fc24eb6d); } foreach ($v45de354860 as $pd69fb7d0 => $v5e813b295b) if ($v5e813b295b) $v6e62221446[$v5e813b295b] = $v61fc24eb6d[$pd69fb7d0]; } unset($v6e62221446["url"]); unset($v6e62221446["http_auth"]); unset($v6e62221446["user_pwd"]); unset($v6e62221446["other_settings"]); unset($v6e62221446["global_variables"]); unset($v6e62221446["active"]); $pc2cdfd1b = self::f3820df7a8a(array("connection_settings" => $v6e62221446)); if ($v7f5911d32d["tag"] == $this->pfc8be585["dataaccess"]) { if ($v7f5911d32d["properties"]["type"] == "ibatis") $paffeffd6 .= '
	<bean name="' . $v7f47ccc31b . $v49bdd49c66 . 'IDABrokerServer" path="' . $v890189c188 . 'IbatisDataAccessBrokerServer">
		<constructor_arg reference="' . $v49bdd49c66 . 'IDALayer" />' . $pc2cdfd1b . '
	</bean>'; else $paffeffd6 .= '
	<bean name="' . $v7f47ccc31b . $v49bdd49c66 . 'HDABrokerServer" path="' . $v890189c188 . 'HibernateDataAccessBrokerServer">
		<constructor_arg reference="' . $v49bdd49c66 . 'HDALayer" />' . $pc2cdfd1b . '
	</bean>'; } else if ($v7f5911d32d["tag"] == $this->pfc8be585["businesslogic"]) $paffeffd6 .= '
	<bean name="' . $v7f47ccc31b . $v49bdd49c66 . 'BLBrokerServer" path="' . $v890189c188 . 'BusinessLogicBrokerServer">
		<constructor_arg reference="' . $v49bdd49c66 . 'BLLayer" />' . $pc2cdfd1b . '
	</bean>'; else if ($v7f5911d32d["tag"] == $this->pfc8be585["db"]) $paffeffd6 .= '
	<bean name="' . $v7f47ccc31b . $v49bdd49c66 . 'DBBrokerServer" path="' . $v890189c188 . 'DBBrokerServer">
		<constructor_arg reference="' . $v49bdd49c66 . 'DBLayer" />' . $pc2cdfd1b . '
	</bean>'; } } return $paffeffd6; } private function f94a03ace64($v250a1176c9, $v241205aec6, $v30857f7eca = false) { $pf3dc0762 = $this->mdbc503965b4d($v250a1176c9); return file_put_contents($pf3dc0762, $v241205aec6) && $this->v994c46e305->prepareBeansFolder($pf3dc0762, $v30857f7eca); } private function mdbc503965b4d($v250a1176c9) { return $this->v5039a77f9d . $this->f1844369942($v250a1176c9); } private function f1844369942($v250a1176c9) { return $v250a1176c9 . ".xml"; } private static function f3820df7a8a($v3033e34359) { $v241205aec6 = ""; $pce73cdeb = $v3033e34359["connection_settings"]; $pf6de834c = $v3033e34359["connection_global_variables_name"]; $pf6de834c = $pf6de834c && !is_array($pf6de834c) ? array($pf6de834c) : $pf6de834c; if ($pce73cdeb || $pf6de834c) { $v241205aec6 = ""; if ($pce73cdeb) { $v241205aec6 .= "
		<constructor_arg>
			<list>"; foreach ($pce73cdeb as $v5fd39ee691 => $v92e51d30de) $v241205aec6 .= "
				" . '<item name="' . self::mf73c700ed652($v5fd39ee691) . '">' . self::mf73c700ed652($v92e51d30de) . '</item>'; $v241205aec6 .= "
			</list>
		</constructor_arg>"; } else $v241205aec6 .= "
		<constructor_arg />"; if ($pf6de834c) { $v241205aec6 .= "
		<constructor_arg>
			<list>"; foreach ($pf6de834c as $v1cfba8c105) $v241205aec6 .= "
				" . '<item>' . self::mf73c700ed652($v1cfba8c105) . '</item>'; $v241205aec6 .= "
			</list>
		</constructor_arg>"; } } return $v241205aec6; } private static function f18f75624e4($v002556527f) { return $v002556527f ? self::mcb6114f75351($v002556527f) : ""; } private static function mea7bdac9ad9e($v9acc88059e) { if (strpos($v9acc88059e, "<?") === false && strpos($v9acc88059e, "&lt;?") === false && strpos($v9acc88059e, '$') === false) return str_replace(" ", "_", strtolower($v9acc88059e)); return $v9acc88059e; } private static function mcb6114f75351($v9acc88059e) { if (strpos($v9acc88059e, "<?") === false && strpos($v9acc88059e, "&lt;?") === false && strpos($v9acc88059e, '$') === false) return str_replace(" ", "_", ucwords(str_replace("_", " ", strtolower($v9acc88059e)))); return $v9acc88059e; } private static function f6c6524d3a5($v9acc88059e) { return self::mea7bdac9ad9e($v9acc88059e); } private static function f736d852839($v9acc88059e) { return self::mea7bdac9ad9e($v9acc88059e); } private static function mac0ace304e61(&$v9acc88059e) { self::mf73c700ed652($v9acc88059e); preg_match_all('/&lt;\?([^>]*)\?>/u', $v9acc88059e, $v62bf8bcb37, PREG_OFFSET_CAPTURE); $v761f4d757f = $v62bf8bcb37[0]; $v7e4b517c18 = 0; $pff4e506c = ""; if (!empty($v761f4d757f)) { $pc37695cb = count($v761f4d757f); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v1cfba8c105 = $v761f4d757f[$v43dd7d0051][0]; $pd69fb7d0 = $v761f4d757f[$v43dd7d0051][1]; $v342a134247 = substr($v9acc88059e, $v7e4b517c18, $pd69fb7d0 - $v7e4b517c18); if (!empty($v342a134247)) { $pff4e506c .= str_replace(array("-", " "), "_", $v342a134247); } $pff4e506c .= $v1cfba8c105; $v7e4b517c18 = $pd69fb7d0 + strlen($v1cfba8c105); } $v9acc88059e = $pff4e506c; } return $v9acc88059e; } private static function mf73c700ed652(&$v67db1bd535) { preg_match_all('/\{?([\\\\]*)\$\{?([\w]+)}?/u', $v67db1bd535, $v62bf8bcb37, PREG_PATTERN_ORDER); $v761f4d757f = $v62bf8bcb37[0]; usort($v761f4d757f, function($pbde5fb24, $v7aeaf992f5) { return strlen($v7aeaf992f5) - strlen($pbde5fb24); }); $pc37695cb = count($v761f4d757f); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v1cfba8c105 = str_replace(array("{", "}"), "", $v761f4d757f[$v43dd7d0051]); $v20d8980aa8 = false; $v0ab629d038 = null; if (preg_match('/^\\\\+\$/', $v1cfba8c105)) { preg_match_all('/\\\\/', substr($v1cfba8c105, 0, strpos($v1cfba8c105, '$')), $v0ab629d038, PREG_PATTERN_ORDER); $v20d8980aa8 = $v0ab629d038[0] && count($v0ab629d038[0]) % 2 != 0; } if (!$v20d8980aa8) { $v1cfba8c105 = preg_replace('/^\\\\*\$/', '', $v1cfba8c105); $v67db1bd535 = str_replace($v761f4d757f[$v43dd7d0051], '<?php echo $GLOBALS[\'' . $v1cfba8c105 . '\']; ?>', $v67db1bd535); } } $v67db1bd535 = str_replace('<?', '&lt;?', $v67db1bd535); return $v67db1bd535; } public static function getVariableNameFromRawLabel($v9acc88059e) { self::mac0ace304e61($v9acc88059e); return self::mea7bdac9ad9e($v9acc88059e); } public static function getObjectNameFromRawLabel($v9acc88059e) { self::mac0ace304e61($v9acc88059e); return self::mcb6114f75351($v9acc88059e); } public static function getBrokerNameFromRawLabel($v9acc88059e) { self::mac0ace304e61($v9acc88059e); return self::f6c6524d3a5($v9acc88059e); } public static function getFileNameFromRawLabel($v9acc88059e) { self::mac0ace304e61($v9acc88059e); return self::f736d852839($v9acc88059e); } } ?>
