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

include_once get_lib("org.phpframework.layer.presentation.cms.module.ICMSModuleEnableHandler"); include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleUtil"); include_once get_lib("org.phpframework.layer.presentation.cms.cache.CMSModuleSettingsCacheHandler"); class CMSModuleEnableHandler implements ICMSModuleEnableHandler { protected $PresentationLayer; protected $module_id; protected $presentation_module_path; public function __construct($pd3623f40, $pcd8c70bc) { $this->PresentationLayer = $pd3623f40; $this->module_id = $pcd8c70bc; $pa2bba2ac = $pd3623f40->getLayerPathSetting(); $this->presentation_module_path = $pa2bba2ac . $pd3623f40->getCommonProjectName() . "/" . $pd3623f40->settings["presentation_modules_path"] . $pcd8c70bc; } public static function createCMSModuleEnableHandlerObject($pd3623f40, $pcd8c70bc, $v01f92f852f) { $v35792901f2 = null; try { if (file_exists($v01f92f852f . "/CMSModuleEnableHandlerImpl.php")) { $v3ae55a9a2e = 'CMSModule\\' . str_replace("/", "\\", str_replace(" ", "_", trim($pcd8c70bc))) . '\CMSModuleEnableHandlerImpl'; if (!class_exists($v3ae55a9a2e)) include_once $v01f92f852f . "/CMSModuleEnableHandlerImpl.php"; eval ('$v35792901f2 = new ' . $v3ae55a9a2e . '($pd3623f40, $pcd8c70bc);'); } else $v35792901f2 = new CMSModuleEnableHandler($pd3623f40, $pcd8c70bc); } catch (Exception $paec2c009) { launch_exception($paec2c009); } return $v35792901f2; } public function enable() { if (!is_dir($this->presentation_module_path)) launch_exception(new Exception("Module path doesn't exist: " . $this->presentation_module_path)); return file_put_contents( self::getModuleEnabledFilePath($this->presentation_module_path), '<?php $CMSModuleHandler->enable(); ?>') !== false; } public function disable() { $v4aadfbf3d8 = self::getModuleEnabledFilePath($this->presentation_module_path); return !file_exists($v4aadfbf3d8) || file_put_contents($v4aadfbf3d8, '<?php $CMSModuleHandler->disable(); ?>') !== false; } public function freeModuleCache() { if ($this->PresentationLayer->isCacheActive()) { $pd8192d9d = $this->PresentationLayer->getModuleCachedLayerDirPath(); if ($pd8192d9d) { $pd8192d9d = $pd8192d9d . CMSModuleSettingsCacheHandler::CACHE_DIR_NAME; return CMSModuleUtil::deleteFolder($pd8192d9d); } } } public static function getModuleEnabledFilePath($v4a650a2b36) { return "$v4a650a2b36/enable.php"; } public static function isModuleEnabled($v4a650a2b36) { $v4aadfbf3d8 = self::getModuleEnabledFilePath($v4a650a2b36); return file_exists($v4aadfbf3d8) && strpos(file_get_contents($v4aadfbf3d8), '$CMSModuleHandler->enable();') !== false; } } ?>
