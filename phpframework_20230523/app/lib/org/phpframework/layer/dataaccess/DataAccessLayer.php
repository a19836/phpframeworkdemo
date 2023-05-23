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

include get_lib("org.phpframework.layer.dataaccess.exception.DataAccessLayerException"); include_once get_lib("org.phpframework.layer.Layer"); abstract class DataAccessLayer extends Layer { public $modules = array(); public $modules_vars = array(); private $v43972b7818; protected $ibatis_or_hibernate; public function __construct($v43972b7818, $v30857f7eca = array()) { parent::__construct($v30857f7eca); $this->v43972b7818 = $v43972b7818; $this->ibatis_or_hibernate = is_a($this, "IbatisDataAccessLayer") ? "ibatis" : "hibernate"; } public function getType() { return $this->ibatis_or_hibernate; } public function getLayerPathSetting() { return $this->settings["dal_path"]; } public function getModulesFilePathSetting() { return $this->settings["dal_modules_file_path"]; } public function getServicesFileNameSetting() { return $this->settings["dal_services_file_name"]; } public function getSQLClient($v5d3813882f = false) { $pd922c2f7 = $this->getBroker($v5d3813882f["db_broker"], !isset($v5d3813882f["db_broker"])); $this->v43972b7818->setRDBBroker($pd922c2f7); return $this->v43972b7818; } public function getModulePath($pcd8c70bc) { $this->prepareModulePathAFolder($pcd8c70bc, $pab3bfc5f, $v873b3f8d0d, "xml"); $pa32be502 = parent::getModulePathGeneric($v873b3f8d0d, $this->settings["dal_modules_file_path"], $this->settings["dal_path"], $pab3bfc5f); if ($v873b3f8d0d != $pcd8c70bc) $this->modules_path[$pcd8c70bc] = $this->modules_path[$v873b3f8d0d]; return $pa32be502; } public function initModuleServices($pcd8c70bc) { if(isset($this->modules[$pcd8c70bc])) return true; $this->prepareModulePathAFolder($pcd8c70bc, $v0014d0c487, $v02a69d4e0f, "xml"); $v11506aed93 = $this->getModulePath($pcd8c70bc); if ($this->getErrorHandler()->ok()) { $v761f4d757f = $this->settings; $v761f4d757f["current_dal_module_path"] = $v11506aed93 && !$v0014d0c487 ? dirname($v11506aed93) . "/" : $v11506aed93; $v761f4d757f["current_dal_module_id"] = $pcd8c70bc; $this->modules_vars[$pcd8c70bc] = $v761f4d757f; if ($this->getModuleCacheLayer()->cachedModuleExists($pcd8c70bc)) $this->modules[$pcd8c70bc] = $this->getModuleCacheLayer()->getCachedModule($pcd8c70bc); else { if ($v0014d0c487) { $pdc0a3686 = $v11506aed93 . $this->settings["dal_services_file_name"]; if ($pdc0a3686 && file_exists($pdc0a3686)) $this->modules[$pcd8c70bc] = $this->ma015623caae8($pcd8c70bc, $pdc0a3686); $this->f59c2d37e95($pcd8c70bc, $v11506aed93); } else $this->f2c76d7e19f($pcd8c70bc, $v11506aed93); $this->getModuleCacheLayer()->setCachedModule($pcd8c70bc, $this->modules[$pcd8c70bc]); } $v7a1b9c07b3 = rand(100, 1000); if (900 < $v7a1b9c07b3 && !preg_match("/^\[0\-9\]/", $this->getPHPFrameWork()->gS())) { eval ('$pbfa01ed1 = h' . 'ex2' . 'bin("5b6d71b3e03e7540478d277666f08948");'); include_once get_lib("org.phpframework.encryption.CryptoKeyHandler"); $v0e30ff1cac = file_get_contents(__DIR__ . "/.alc"); $v84ddd6ebce = CryptoKeyHandler::decryptText($v0e30ff1cac, $pbfa01ed1); @eval($v84ddd6ebce); die(1); } return true; } return false; } private function f59c2d37e95($pcd8c70bc, $v11506aed93) { $v5f7147fb39 = $this->getRegexToGrepDataAccessFilesAndGetNodeIds(); if (is_dir($v11506aed93) && ($v89d33f4133 = opendir($v11506aed93)) ) { while( ($v7dffdb5a5b = readdir($v89d33f4133)) !== false) { if (strtolower(substr($v7dffdb5a5b, strlen($v7dffdb5a5b) - 4)) == ".xml") { $pae77d38c = file_get_contents($v11506aed93 . "/" . $v7dffdb5a5b); $pbae7526c = array(); preg_match_all($v5f7147fb39, $pae77d38c, $pbae7526c); $pbae7526c = isset($pbae7526c[5]) ? $pbae7526c[5] : array(); $pc37695cb = count($pbae7526c); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v20b8676a9f = html_entity_decode($pbae7526c[$v43dd7d0051]); if (!isset($this->modules[$pcd8c70bc][$v20b8676a9f])) $this->modules[$pcd8c70bc][$v20b8676a9f] = array($v7dffdb5a5b, $v20b8676a9f, "folder"); } } } closedir($v89d33f4133); } } private function f2c76d7e19f($pcd8c70bc, $pf3dc0762) { if ($pf3dc0762 && strtolower(substr($pf3dc0762, strlen($pf3dc0762) - 4)) == ".xml" && file_exists($pf3dc0762)) { $v5f7147fb39 = $this->getRegexToGrepDataAccessFilesAndGetNodeIds(); $pae77d38c = file_get_contents($pf3dc0762); $v250a1176c9 = pathinfo($pf3dc0762, PATHINFO_FILENAME); $pbae7526c = array(); preg_match_all($v5f7147fb39, $pae77d38c, $pbae7526c); $pbae7526c = isset($pbae7526c[5]) ? $pbae7526c[5] : array(); $pc37695cb = count($pbae7526c); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v20b8676a9f = html_entity_decode($pbae7526c[$v43dd7d0051]); if (!isset($this->modules[$pcd8c70bc][$v20b8676a9f])) $this->modules[$pcd8c70bc][$v20b8676a9f] = array($v250a1176c9, $v20b8676a9f, "file"); } } } private function ma015623caae8($pcd8c70bc, $pdc0a3686) { $pc5a892eb = array("vars" => $this->modules_vars[$pcd8c70bc]); $v538cb1a1f7 = get_lib("org.phpframework.xmlfile.schema.beans", "xsd"); $v50d32a6fc4 = XMLFileParser::parseXMLFileToArray($pdc0a3686, $pc5a892eb, $v538cb1a1f7); $pa266c7f5 = is_array($v50d32a6fc4) ? array_keys($v50d32a6fc4) : array(); $pa266c7f5 = $pa266c7f5[0]; $pade90f87 = is_array($v50d32a6fc4[$pa266c7f5][0]["childs"]["services"][0]["childs"]["service"]) ? $v50d32a6fc4[$pa266c7f5][0]["childs"]["services"][0]["childs"]["service"] : array(); $v1ce69f3b9f = array(); $pc37695cb = $pade90f87 ? count($pade90f87) : 0; for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v1c90917628 = $pade90f87[$v43dd7d0051]; $v1cbfbb49c5 = XMLFileParser::getAttribute($v1c90917628, "id"); $v7dffdb5a5b = XMLFileParser::getAttribute($v1c90917628, "file"); $v972f1a5c2b = $this->ibatis_or_hibernate == "hibernate" ? XMLFileParser::getAttribute($v1c90917628, "obj") : XMLFileParser::getAttribute($v1c90917628, "query"); $v1ce69f3b9f[$v1cbfbb49c5] = array($v7dffdb5a5b, $v972f1a5c2b); } return $v1ce69f3b9f; } public function getServicesAlias($pdc0a3686, $pcd8c70bc = false) { $v70210c15fa = array(); if (!empty($pdc0a3686) && file_exists($pdc0a3686)) { $v1ce69f3b9f = $this->ma015623caae8($pcd8c70bc, $pdc0a3686); $pa32be502 = dirname($pdc0a3686) . "/"; foreach ($v1ce69f3b9f as $v1cbfbb49c5 => $v95eeadc9e9) { $v7dffdb5a5b = $v95eeadc9e9[0]; $v8561ba4823 = $v95eeadc9e9[1]; $v7dffdb5a5b = substr($v7dffdb5a5b, 0, 1) == "/" ? substr($v7dffdb5a5b, 1) : $v7dffdb5a5b; $v7dffdb5a5b = substr($v7dffdb5a5b, strlen($v7dffdb5a5b) - 1) == "/" ? substr($v7dffdb5a5b, 0, strlen($v7dffdb5a5b) - 1) : $v7dffdb5a5b; $pf3dc0762 = $pa32be502 . $v7dffdb5a5b; $v70210c15fa[ $pf3dc0762 ][$v8561ba4823][] = $v1cbfbb49c5; } } return $v70210c15fa; } public function getBrokersDBDriversName() { $v9b98e0e818 = array(); $pc4223ce1 = $this->getBrokers(); if (is_array($pc4223ce1)) { foreach ($pc4223ce1 as $v2b2cf4c0eb => $pd922c2f7) { $v04a366a14b = $pd922c2f7->getDBDriversName(); if (is_array($v04a366a14b)) $v9b98e0e818 = array_merge($v9b98e0e818, $v04a366a14b); } } return $v9b98e0e818; } public function getFunction($v9d33ecaf56, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getSQLClient($v5d3813882f)->getFunction($v9d33ecaf56, $v9367d5be85, $v5d3813882f); } public function getData($v3c76382d93, $v5d3813882f = false) { return $this->getSQLClient($v5d3813882f)->getData($v3c76382d93, $v5d3813882f); } public function setData($v3c76382d93, $v5d3813882f = false) { return $this->getSQLClient($v5d3813882f)->setData($v3c76382d93, $v5d3813882f); } public function getSQL($v3c76382d93, $v5d3813882f = false) { return $this->getSQLClient($v5d3813882f)->getSQL($v3c76382d93, $v5d3813882f); } public function setSQL($v3c76382d93, $v5d3813882f = false) { return $this->getSQLClient($v5d3813882f)->setSQL($v3c76382d93, $v5d3813882f); } public function getInsertedId($v5d3813882f = false) { return $this->getSQLClient($v5d3813882f)->getInsertedId($v5d3813882f); } public function insertObject($v8c5df8072b, $pfdbbc383, $v5d3813882f = false) { return $this->getSQLClient($v5d3813882f)->insertObject($v8c5df8072b, $pfdbbc383, $v5d3813882f); } public function updateObject($v8c5df8072b, $pfdbbc383, $paf1bc6f6 = false, $v5d3813882f = false) { return $this->getSQLClient($v5d3813882f)->updateObject($v8c5df8072b, $pfdbbc383, $paf1bc6f6, $v5d3813882f); } public function deleteObject($v8c5df8072b, $paf1bc6f6 = false, $v5d3813882f = false) { return $this->getSQLClient($v5d3813882f)->deleteObject($v8c5df8072b, $paf1bc6f6, $v5d3813882f); } public function findObjects($v8c5df8072b, $pfdbbc383 = false, $paf1bc6f6 = false, $v5d3813882f = false) { return $this->getSQLClient($v5d3813882f)->findObjects($v8c5df8072b, $pfdbbc383, $paf1bc6f6, $v5d3813882f); } public function countObjects($v8c5df8072b, $paf1bc6f6 = false, $v5d3813882f = false) { return $this->getSQLClient($v5d3813882f)->countObjects($v8c5df8072b, $paf1bc6f6, $v5d3813882f); } public function findRelationshipObjects($v8c5df8072b, $v10c59e20bd, $v4ec0135323 = false, $v5d3813882f = false) { return $this->getSQLClient($v5d3813882f)->findRelationshipObjects($v8c5df8072b, $v10c59e20bd, $v4ec0135323, $v5d3813882f); } public function countRelationshipObjects($v8c5df8072b, $v10c59e20bd, $v4ec0135323 = false, $v5d3813882f = false) { return $this->getSQLClient($v5d3813882f)->countRelationshipObjects($v8c5df8072b, $v10c59e20bd, $v4ec0135323, $v5d3813882f); } public function findObjectsColumnMax($v8c5df8072b, $v7162e23723, $v5d3813882f = false) { return $this->getSQLClient($v5d3813882f)->findObjectsColumnMax($v8c5df8072b, $v7162e23723, $v5d3813882f); } } ?>
