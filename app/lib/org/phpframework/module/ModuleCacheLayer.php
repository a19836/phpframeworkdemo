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
include_once get_lib("org.phpframework.cache.xmlsettings.filesystem.FileSystemXmlSettingsCacheHandler"); class ModuleCacheLayer extends FileSystemXmlSettingsCacheHandler { const CACHE_MODULES_PATH_RELATIVE_FILE_PATH = "__system/modules/modules"; const CACHE_MODULES_DATA_RELATIVE_DIR_PATH = "__system/modules/data/"; const CACHE_MODULES_SETTINGS_RELATIVE_DIR_PATH = "__system/modules/cache_settings/"; const CACHE_MODULES_ROUTERS_RELATIVE_DIR_PATH = "__system/modules/routers/"; private $pd8192d9d; private $pda761309; private $paf147cf9; private $v1df3d8ac90; private $pb21e9395; private $v847a7225e0; public function __construct($v847a7225e0) { $this->v847a7225e0 = $v847a7225e0; } public function getLayer() { return $this->v847a7225e0; } public function cachedModulesPathExists() { $pf3dc0762 = $this->f7e44e5e855(); if ($pf3dc0762 && $this->isCacheValid($pf3dc0762)) return true; return false; } public function getCachedModulesPath() { $pf3dc0762 = $this->f7e44e5e855(); return $this->getCache($pf3dc0762); } public function setCachedModulesPath($v162ec989f1) { $pf3dc0762 = $this->f7e44e5e855(); if ($pf3dc0762) return $this->setCache($pf3dc0762, $v162ec989f1); return true; } public function cachedModuleExists($pcd8c70bc) { $pf3dc0762 = $this->f4b8c8c33f5($pcd8c70bc); if ($pf3dc0762 && $this->isCacheValid($pf3dc0762)) { $pfb662071 = $this->getCache($pf3dc0762); return $pfb662071 ? true : false; } return false; } public function getCachedModule($pcd8c70bc) { $pf3dc0762 = $this->f4b8c8c33f5($pcd8c70bc); return $this->getCache($pf3dc0762); } public function setCachedModule($pcd8c70bc, $v5d4aa7be9b) { $pf3dc0762 = $this->f4b8c8c33f5($pcd8c70bc); if ($pf3dc0762) { return $this->setCache($pf3dc0762, $v5d4aa7be9b); } return true; } public function cachedModuleSettingsExists($pcd8c70bc) { $pf3dc0762 = $this->f722cb985bf($pcd8c70bc); if ($pf3dc0762 && $this->isCacheValid($pf3dc0762)) { $pfb662071 = $this->getCache($pf3dc0762); return $pfb662071 ? true : false; } return false; } public function getCachedModuleSettings($pcd8c70bc) { $pf3dc0762 = $this->f722cb985bf($pcd8c70bc); return $this->getCache($pf3dc0762); } public function setCachedModuleSettings($pcd8c70bc, $v5d4aa7be9b) { $pf3dc0762 = $this->f722cb985bf($pcd8c70bc); if ($pf3dc0762) return $this->setCache($pf3dc0762, $v5d4aa7be9b); return true; } public function cachedModuleRoutersExists($pcd8c70bc) { $pf3dc0762 = $this->f9188def812($pcd8c70bc); if ($pf3dc0762 && $this->isCacheValid($pf3dc0762)) { $pfb662071 = $this->getCache($pf3dc0762); return $pfb662071 ? true : false; } return false; } public function getCachedModuleRouters($pcd8c70bc) { $pf3dc0762 = $this->f9188def812($pcd8c70bc); return $this->getCache($pf3dc0762); } public function setCachedModuleRouters($pcd8c70bc, $v5d4aa7be9b) { $pf3dc0762 = $this->f9188def812($pcd8c70bc); if ($pf3dc0762) return $this->setCache($pf3dc0762, $v5d4aa7be9b); return true; } private function f07d3072285() { if (!$this->pd8192d9d) { $v17be587282 = $this->getLayer()->getModuleCachedLayerDirPath(); if ($v17be587282) { CacheHandlerUtil::configureFolderPath($v17be587282); $this->pd8192d9d = $v17be587282; } } } private function f7e44e5e855() { if (!$this->paf147cf9) { $this->f07d3072285(); if ($this->pd8192d9d) { $pf3dc0762 = $this->pd8192d9d . self::CACHE_MODULES_PATH_RELATIVE_FILE_PATH; if (CacheHandlerUtil::preparePath(dirname($pf3dc0762))) { $this->paf147cf9 = $pf3dc0762; return $this->paf147cf9; } } return false; } return $this->paf147cf9; } private function f4b8c8c33f5($pcd8c70bc) { if (!$this->pda761309) { $this->f07d3072285(); if ($this->pd8192d9d) { $v17be587282 = $this->pd8192d9d . self::CACHE_MODULES_DATA_RELATIVE_DIR_PATH; if (CacheHandlerUtil::preparePath($v17be587282)) { CacheHandlerUtil::configureFolderPath($v17be587282); $this->pda761309 = $v17be587282; return $this->pda761309 . $pcd8c70bc; } } return false; } return $this->pda761309 . $pcd8c70bc; } private function f722cb985bf($pcd8c70bc) { if (!$this->v1df3d8ac90) { $this->f07d3072285(); if ($this->pd8192d9d) { $v17be587282 = $this->pd8192d9d . self::CACHE_MODULES_SETTINGS_RELATIVE_DIR_PATH; if (CacheHandlerUtil::preparePath($v17be587282)) { CacheHandlerUtil::configureFolderPath($v17be587282); $this->v1df3d8ac90 = $v17be587282; return $this->v1df3d8ac90 . $pcd8c70bc; } } return false; } return $this->v1df3d8ac90 . $pcd8c70bc; } private function f9188def812($pcd8c70bc) { if (!$this->pb21e9395) { $this->f07d3072285(); if ($this->pd8192d9d) { $v17be587282 = $this->pd8192d9d . self::CACHE_MODULES_ROUTERS_RELATIVE_DIR_PATH; if (CacheHandlerUtil::preparePath($v17be587282)) { CacheHandlerUtil::configureFolderPath($v17be587282); $this->pb21e9395 = $v17be587282; return $this->pb21e9395 . $pcd8c70bc; } } return false; } return $this->pb21e9395 . $pcd8c70bc; } } ?>
