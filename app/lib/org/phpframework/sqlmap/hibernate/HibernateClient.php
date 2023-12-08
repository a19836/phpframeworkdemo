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
include_once get_lib("org.phpframework.sqlmap.SQLMapClient"); include get_lib("org.phpframework.sqlmap.hibernate.HibernateClientCache"); include get_lib("org.phpframework.sqlmap.hibernate.HibernateClassHandler"); include get_lib("org.phpframework.sqlmap.hibernate.exception.HibernateException"); class HibernateClient extends SQLMapClient { private $v5b8f415790; private $pbc7e2f66; public function __construct() { parent::__construct(); $this->v5b8f415790 = new HibernateClassHandler(); $this->setSQLMapClientCache(new HibernateClientCache()); $this->v5b8f415790->setHibernateClientCache($this->getSQLMapClientCache()); } public function setRDBBroker($pdb735a1c) { parent::setRDBBroker($pdb735a1c); $this->v5b8f415790->setRDBBroker($this->getRDBBroker()); } public function loadXML($v45952cf45c) { if($this->getSQLMapClientCache()->cachedXMLElmExists($v45952cf45c)) { $v50d32a6fc4 = $this->getSQLMapClientCache()->getCachedXMLElm($v45952cf45c); $this->setNodesData($v50d32a6fc4); } else { $v50d32a6fc4 = self::getHibernateObjectNodeConfiguredFromFilePath($v45952cf45c); $this->setNodesData($v50d32a6fc4); $this->getSQLMapClientCache()->setCachedXMLElm($v45952cf45c, $v50d32a6fc4); } } public static function getHibernateObjectNodeConfiguredFromFilePath($v45952cf45c) { $v538cb1a1f7 = get_lib("org.phpframework.xmlfile.schema.hibernate_sql_mapping", "xsd"); $v50d32a6fc4 = XMLFileParser::parseXMLFileToArray($v45952cf45c, false, $v538cb1a1f7); $pa266c7f5 = is_array($v50d32a6fc4) ? array_keys($v50d32a6fc4) : array(); $pa266c7f5 = isset($pa266c7f5[0]) ? $pa266c7f5[0] : null; if (!empty($v50d32a6fc4[$pa266c7f5][0]["childs"])) { $v50d32a6fc4 = $v50d32a6fc4[$pa266c7f5][0]["childs"]; $pd29b0c43 = array(); if (!empty($v50d32a6fc4["class"])) { $pd29b0c43["class"] = array(); $pc37695cb = count($v50d32a6fc4["class"]); for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v6694236c2c = $v50d32a6fc4["class"][$v43dd7d0051]; self::prepareObjNode($v6694236c2c); $v5e813b295b = XMLFileParser::getAttribute($v6694236c2c, "name"); if($v5e813b295b) { if(!isset($pd29b0c43["class"][$v5e813b295b])) $pd29b0c43["class"][$v5e813b295b] = $v6694236c2c; else launch_exception(new HibernateException(7, $v5e813b295b)); } else launch_exception(new HibernateException(6, $v45952cf45c)); } } $v50d32a6fc4 = $pd29b0c43; } else $v50d32a6fc4 = array(); return $v50d32a6fc4; } public static function prepareObjNode(&$v6694236c2c) { if (isset($v6694236c2c["childs"]["id"])) { $v6694236c2c["childs"]["id"] = HibernateClassHandler::convertIds($v6694236c2c["childs"]["id"]); } if (isset($v6694236c2c["childs"]["parameter_map"][0])) { SQLMapClient::configureMap($v6694236c2c["childs"]["parameter_map"][0], "parameter_map"); } if (isset($v6694236c2c["childs"]["result_map"][0])) { SQLMapClient::configureMap($v6694236c2c["childs"]["result_map"][0], "result_map"); } $v6694236c2c["childs"]["relationships"] = isset($v6694236c2c["childs"]["relationships"]) ? $v6694236c2c["childs"]["relationships"] : null; $v6694236c2c["childs"]["relationships"] = XMLFileParser::combineMultipleNodesInASingleNode($v6694236c2c["childs"]["relationships"]); $v6694236c2c["childs"]["relationships"] = isset($v6694236c2c["childs"]["relationships"][0]["childs"]) ? $v6694236c2c["childs"]["relationships"][0]["childs"] : null; $pe3573e1b = array("parameter_map", "result_map"); foreach ($pe3573e1b as $v87f853c4d2) { if (isset($v6694236c2c["childs"]["relationships"][$v87f853c4d2])) { $v6270a5000a = array(); $pc37695cb = count($v6694236c2c["childs"]["relationships"][$v87f853c4d2]); for ($v9d27441e80 = 0; $v9d27441e80 < $pc37695cb; $v9d27441e80++) { $v0df63e1235 = $v6694236c2c["childs"]["relationships"][$v87f853c4d2][$v9d27441e80]; SQLMapClient::configureMap($v0df63e1235, $v87f853c4d2); $v3df2d026a1 = isset($v0df63e1235["attrib"]["id"]) ? $v0df63e1235["attrib"]["id"] : null; if ($v3df2d026a1) $v6270a5000a[$v3df2d026a1] = $v0df63e1235; } $v6694236c2c["childs"]["relationships"][$v87f853c4d2] = $v6270a5000a; } } $v52770b5c7a = array("many_to_one", "many_to_many", "one_to_many", "one_to_one"); foreach ($v52770b5c7a as $v05e3431fe2) { if (isset($v6694236c2c["childs"]["relationships"][$v05e3431fe2])) { $v6694236c2c["childs"]["relationships"][$v05e3431fe2] = HibernateClassHandler::convertRelations($v6694236c2c["childs"]["relationships"][$v05e3431fe2], $v6694236c2c); } } $v6694236c2c["childs"]["queries"] = isset($v6694236c2c["childs"]["queries"]) ? $v6694236c2c["childs"]["queries"] : null; $v6694236c2c["childs"]["queries"] = XMLFileParser::combineMultipleNodesInASingleNode($v6694236c2c["childs"]["queries"]); $v6694236c2c["childs"]["queries"] = isset($v6694236c2c["childs"]["queries"][0]["childs"]) ? self::getDataAccessNodesConfigured($v6694236c2c["childs"]["queries"][0]["childs"]) : null; return $v6694236c2c; } public function getHbnObj($v55c0c0e582, $pcd8c70bc, $v20b8676a9f, $v5d3813882f = false) { $v8ce36c307f = $this->getNodesData(); $pf232dd5a = isset($v8ce36c307f["class"][$v55c0c0e582]) ? $v8ce36c307f["class"][$v55c0c0e582] : null; if ($pf232dd5a) { $v5d3813882f = is_array($v5d3813882f) ? $v5d3813882f : array(); $pf79ea8fc = $v55c0c0e582 . '_' . hash("crc32b", serialize(array($pcd8c70bc, $v20b8676a9f, $v5d3813882f))); $v4824ca245f = $this->v5b8f415790->getClassFilePath($pf79ea8fc, $pf232dd5a, $v5d3813882f); if ($v4824ca245f && file_exists($v4824ca245f)) { include_once $v4824ca245f; if (ObjectHandler::checkObjClass($pf79ea8fc, "HibernateModel") && $this->getErrorHandler()->ok()) { eval("\$v972f1a5c2b = new ".$pf79ea8fc."();"); if ($this->getErrorHandler()->ok()) { if (!empty($v5d3813882f["reset_hbn_default_options"])) { $v9c2b052326 = $v5d3813882f; unset($v9c2b052326["reset_hbn_default_options"]); } else { $v9c2b052326 = $v972f1a5c2b->getDefaultOptions(); $v9c2b052326 = $v9c2b052326 ? array_merge($v9c2b052326, $v5d3813882f) : $v5d3813882f; } $v972f1a5c2b->setDefaultOptions($v9c2b052326); $v972f1a5c2b->setRDBBroker($this->getRDBBroker()); $v972f1a5c2b->setCacheLayer($this->getCacheLayer()); $v972f1a5c2b->setModuleId($pcd8c70bc); $v972f1a5c2b->setServiceId($v20b8676a9f); return $v972f1a5c2b; } } } else { launch_exception(new HibernateException(1, array($v55c0c0e582, $v4824ca245f))); } } else { launch_exception(new HibernateException(2, $v55c0c0e582)); } return false; } public function setCacheLayer($pbc7e2f66) {$this->pbc7e2f66 = $pbc7e2f66;} public function getCacheLayer() {return $this->pbc7e2f66;} } ?>
