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

include_once get_lib("org.phpframework.layer.dataaccess.DataAccessLayer"); class HibernateDataAccessLayer extends DataAccessLayer { public function callObject($pcd8c70bc, $v20b8676a9f, $v5d3813882f = false) { debug_log_function("HibernateDataAccessLayer->callObject", array($pcd8c70bc, $v20b8676a9f, $v5d3813882f)); $this->initModuleServices($pcd8c70bc); if($this->getErrorHandler()->ok()) { $v9ad1385268 = $this->md8cd08bf5303($pcd8c70bc, $v20b8676a9f, $v5d3813882f); if($this->getErrorHandler()->ok()) { return $v9ad1385268; } } return false; } public function getObjectProps($pcd8c70bc, $v20b8676a9f, $v5d3813882f = false) { $v9073377656 = array(); $this->initModuleServices($pcd8c70bc); if($this->getErrorHandler()->ok()) { $pc8b88eb4 = $this->modules[$pcd8c70bc]; $v11506aed93 = $this->modules_path[$pcd8c70bc]; $v9073377656["module"] = $pc8b88eb4; $v9073377656["module_path"] = $v11506aed93; if(isset($pc8b88eb4[$v20b8676a9f])) { $v95eeadc9e9 = $pc8b88eb4[$v20b8676a9f]; $v45952cf45c = $v11506aed93 . ($v95eeadc9e9[2] != "file" ? "/" . $v95eeadc9e9[0] : ""); $v55c0c0e582 = $v95eeadc9e9[1]; $v9073377656["service"] = $v95eeadc9e9; $v9073377656["obj_path"] = $v45952cf45c; $v9073377656["obj_name"] = $v55c0c0e582; } } return $v9073377656; } private function md8cd08bf5303($pcd8c70bc, $v20b8676a9f, $v5d3813882f) { $pc8b88eb4 = $this->modules[$pcd8c70bc]; $v11506aed93 = $this->modules_path[$pcd8c70bc]; if(isset($pc8b88eb4[$v20b8676a9f])) { $v95eeadc9e9 = $pc8b88eb4[$v20b8676a9f]; $v45952cf45c = $v11506aed93 . ($v95eeadc9e9[2] != "file" ? "/" . $v95eeadc9e9[0] : ""); $v55c0c0e582 = $v95eeadc9e9[1]; if($v45952cf45c && file_exists($v45952cf45c)) { $v43972b7818 = $this->getSQLClient($v5d3813882f); if($this->isCacheActive()) { $this->getCacheLayer()->initModuleCache($pcd8c70bc); $v43972b7818->setCacheRootPath($this->getCacheLayer()->getCachedDirPath()); } else { $v43972b7818->setCacheRootPath(false); } $v43972b7818->loadXML($v45952cf45c); return $v43972b7818->getHbnObj($v55c0c0e582, $pcd8c70bc, $v20b8676a9f, $v5d3813882f); } launch_exception(new DataAccessLayerException(1, $v45952cf45c)); return false; } launch_exception(new DataAccessLayerException(2, $pcd8c70bc . "::" . $v20b8676a9f)); return false; } public function setCacheLayer($pbc7e2f66) { $this->getSQLClient()->setCacheLayer($pbc7e2f66); parent::setCacheLayer($pbc7e2f66); } protected function getRegexToGrepDataAccessFilesAndGetNodeIds() { return "/<(class)([^>]*)([ ]+)name=([\"]?)([\w\-\+&#;\s\.]+)([\"]?)/iu"; } } ?>
