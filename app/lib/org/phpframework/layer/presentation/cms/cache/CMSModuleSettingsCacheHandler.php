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
include_once get_lib("org.phpframework.cache.xmlsettings.filesystem.FileSystemXmlSettingsCacheHandler"); class CMSModuleSettingsCacheHandler extends FileSystemXmlSettingsCacheHandler { const CACHE_DIR_NAME = "cms/module_layer/"; const LOADED_MODULES_FILE_NAME = "loaded_modules"; protected $cache_root_path; protected $is_active = false; public function cachedLoadedModulesExists($v72e8f45bdb) { $pf3dc0762 = $this->getCachedFilePath(self::LOADED_MODULES_FILE_NAME . "_" . $v72e8f45bdb); if($pf3dc0762 && $this->isCacheValid($pf3dc0762)) { $this->prepareFilePath($pf3dc0762); return file_exists($pf3dc0762) && file_get_contents($pf3dc0762); } return false; } public function getCachedLoadedModules($v72e8f45bdb) { $pf3dc0762 = $this->getCachedFilePath(self::LOADED_MODULES_FILE_NAME . "_" . $v72e8f45bdb); return $this->getCache($pf3dc0762); } public function setCachedLoadedModules($v72e8f45bdb, $v539082ff30) { $pf3dc0762 = $this->getCachedFilePath(self::LOADED_MODULES_FILE_NAME . "_" . $v72e8f45bdb); if($pf3dc0762) { return $this->setCache($pf3dc0762, $v539082ff30); } return true; } public function initCacheDirPath($v17be587282) { if(!$this->cache_root_path) { if($v17be587282) { CacheHandlerUtil::configureFolderPath($v17be587282); $v17be587282 .= self::CACHE_DIR_NAME; if(CacheHandlerUtil::preparePath($v17be587282)) { CacheHandlerUtil::configureFolderPath($v17be587282); $this->cache_root_path = $v17be587282; $this->is_active = true; } } } } public function isActive() { return $this->is_active; } public function getCachedId($pf3dc0762) { return md5(serialize($pf3dc0762)); } public function getCachedFilePath($pf3dc0762) { if($this->cache_root_path && $pf3dc0762) { return $this->cache_root_path . $pf3dc0762; } return false; } public function getCacheRootPath() { return $this->cache_root_path; } } ?>
