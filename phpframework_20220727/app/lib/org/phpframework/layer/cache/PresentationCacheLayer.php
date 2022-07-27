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

include_once get_lib("org.phpframework.layer.cache.CacheLayer"); class PresentationCacheLayer extends CacheLayer { public function initBeanObjs($pcd8c70bc) { if(!$this->bean_objs) { $this->bean_objs = $this->Layer->getPHPFrameWork()->getObjects(); $this->bean_objs["vars"] = is_array($this->bean_objs["vars"]) ? $this->bean_objs["vars"] : array(); $this->bean_objs["vars"] = array_merge($this->bean_objs["vars"], $this->Layer->settings, $this->settings); } $v7e6c4c2b22 = $this->Layer->getPresentationSettings($pcd8c70bc); $v7e6c4c2b22["current_presentation_id"] = $v7e6c4c2b22["presentation_id"]; $this->bean_objs["vars"] = array_merge($this->bean_objs["vars"], $v7e6c4c2b22); } public function getModulePath($pcd8c70bc) { return $this->Layer->getModulePath($pcd8c70bc); } public function initModuleCache($pcd8c70bc) { if(isset($this->modules_cache[$pcd8c70bc])) return true; $v7e6c4c2b22 = $this->Layer->getPresentationSettings($pcd8c70bc); $v0ff71d0593 = $v7e6c4c2b22["presentation_path"] . $this->settings["presentation_caches_path"] . $this->settings["presentations_cache_file_name"]; if($v0ff71d0593 && file_exists($v0ff71d0593)) { $this->initBeanObjs($pcd8c70bc); if($this->Layer->getModuleCacheLayer()->cachedModuleSettingsExists($pcd8c70bc)) { $pa3e341cf = $this->Layer->getModuleCacheLayer()->getCachedModuleSettings($pcd8c70bc); $this->modules_cache[$pcd8c70bc] = $pa3e341cf["modules_cache"]; $this->keys[$pcd8c70bc] = $pa3e341cf["keys"]; $this->service_related_keys_to_delete[$pcd8c70bc] = $pa3e341cf["service_related_keys_to_delete"]; } else { $this->modules_cache[$pcd8c70bc] = $this->parseCacheFile($pcd8c70bc, $v0ff71d0593); $this->prepareModulesCache($pcd8c70bc); $v1ce69f3b9f = $this->modules_cache[$pcd8c70bc]["services"]; $v264b964f12 = array_keys($v1ce69f3b9f); $pc37695cb = count($v264b964f12); for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pbfa01ed1 = $v264b964f12[$v43dd7d0051]; if($v1ce69f3b9f[$pbfa01ed1]["presentation_id"]) { $this->modules_cache[$pcd8c70bc]["services"][$pbfa01ed1]["module_id"] = $v1ce69f3b9f[$pbfa01ed1]["presentation_id"]; } if($v1ce69f3b9f[$pbfa01ed1]["to_delete"]) { $pd28479e5 = count($v1ce69f3b9f[$pbfa01ed1]["to_delete"]); for($v9d27441e80 = 0; $v9d27441e80 < $pd28479e5; $v9d27441e80++) { if(!$v1ce69f3b9f[$pbfa01ed1]["to_delete"][$v9d27441e80]["module_id"]) { $this->modules_cache[$pcd8c70bc]["services"][$pbfa01ed1]["to_delete"][$v9d27441e80]["module_id"] = $v1ce69f3b9f[$pbfa01ed1]["to_delete"][$v9d27441e80]["presentation_id"]; } } } } $pa3e341cf = array(); $pa3e341cf["modules_cache"] = $this->modules_cache[$pcd8c70bc]; $pa3e341cf["keys"] = $this->keys[$pcd8c70bc]; $pa3e341cf["service_related_keys_to_delete"] = $this->service_related_keys_to_delete[$pcd8c70bc]; $this->Layer->getModuleCacheLayer()->setCachedModuleSettings($pcd8c70bc, $pa3e341cf); } return true; } return false; } public function getModuleCacheObj($pcd8c70bc, $v20b8676a9f, $v539082ff30) { $pc8b88eb4 = $this->modules_cache[$pcd8c70bc]; $v1ce69f3b9f = $pc8b88eb4["services"]; if(isset($v1ce69f3b9f[$v20b8676a9f])) { $v95eeadc9e9 = $v1ce69f3b9f[$v20b8676a9f]; $pcee3c9fd = $v95eeadc9e9["cache_handler"]; if($pcee3c9fd) { $v972f1a5c2b = false; if($pc8b88eb4["objects"][$pcee3c9fd]) $v972f1a5c2b = $pc8b88eb4["objects"][$pcee3c9fd]; else { if($this->modules_cache[$pcd8c70bc]["bean_factory"]) $pddfc29cd = $this->modules_cache[$pcd8c70bc]["bean_factory"]; else { $this->initBeanObjs($pcd8c70bc); $pddfc29cd = new BeanFactory(); $pddfc29cd->addObjects($this->bean_objs); $pddfc29cd->init(array("settings" => $pc8b88eb4["beans"])); $this->modules_cache[$pcd8c70bc]["bean_factory"] = $pddfc29cd; } $pddfc29cd->setCacheRootPath($this->getCachedDirPath()); $v972f1a5c2b = $pddfc29cd->getObject($pcee3c9fd); if(!$v972f1a5c2b) { $pddfc29cd->initObject($pcee3c9fd, false); $this->modules_cache[$pcd8c70bc]["bean_factory"] = $pddfc29cd; $v972f1a5c2b = $pddfc29cd->getObject($pcee3c9fd); } $this->modules_cache[$pcd8c70bc]["objects"][$pcee3c9fd] = $v972f1a5c2b; } if($v972f1a5c2b) return $v972f1a5c2b; else launch_exception(new CacheLayerException(2, $pcd8c70bc . "::" . $v20b8676a9f . "::" . $pcee3c9fd)); } else launch_exception(new CacheLayerException(1, $pcd8c70bc . "::" . $v20b8676a9f)); } return false; } public function getCachedDirPath() { return $this->settings["presentations_cache_path"]; } } ?>
