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
include_once get_lib("org.phpframework.layer.presentation.cms.module.ICMSEntityInstallationHandler"); include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleUtil"); include_once get_lib("org.phpframework.compression.ZipHandler"); class CMSEntityInstallationHandler implements ICMSEntityInstallationHandler { protected $entity_path; protected $webroot_folder_path; protected $blocks_folder_path; protected $unzipped_folder_path; public function __construct($v93dda00907, $ped275ca3, $v283a73b31a, $pd3e94e4f = "") { $this->entity_path = $v93dda00907; $this->webroot_folder_path = $ped275ca3; $this->blocks_folder_path = $v283a73b31a; $this->unzipped_folder_path = $pd3e94e4f; } public static function unzipEntityFile($v39d1337f82, $pd3e94e4f = null) { if (!$pd3e94e4f) { $pd3e94e4f = self::getTmpFolderPath(); if (!$pd3e94e4f) return false; } if (ZipHandler::unzip($v39d1337f82, $pd3e94e4f)) return $pd3e94e4f; return null; } public function install() { if ($this->unzipped_folder_path && is_dir($this->unzipped_folder_path)) { $v5c1c342594 = true; $v250a1176c9 = basename($this->unzipped_folder_path) . ".php"; $v250a1176c9 = file_exists($this->unzipped_folder_path . $v250a1176c9) ? $v250a1176c9 : "index.php"; if (!CMSModuleUtil::copyFile($this->unzipped_folder_path . $v250a1176c9, $this->entity_path)) $v5c1c342594 = false; if (is_dir($this->unzipped_folder_path . "/webroot") && !CMSModuleUtil::copyFolder($this->unzipped_folder_path . "/webroot", $this->webroot_folder_path)) $v5c1c342594 = false; if (is_dir($this->unzipped_folder_path . "/block") && !CMSModuleUtil::copyFolder($this->unzipped_folder_path . "/block", $this->blocks_folder_path)) $v5c1c342594 = false; return $v5c1c342594; } } public function uninstall() { return CMSModuleUtil::deleteFolder($this->template_folder_path) && CMSModuleUtil::deleteFolder($this->webroot_folder_path); } public static function getTmpRootFolderPath() { return (defined("TMP_PATH") ? TMP_PATH : sys_get_temp_dir()) . "/entity/"; } public static function getTmpFolderPath($pc5cbb00b = null) { $v4ab372da3a = self::getTmpRootFolderPath(); $v6f181849e4 = $pc5cbb00b ? $v4ab372da3a . $pc5cbb00b : tempnam($v4ab372da3a, ""); if (file_exists($v6f181849e4)) unlink($v6f181849e4); @mkdir($v6f181849e4, 0755); if (is_dir($v6f181849e4)) return $v6f181849e4 . "/"; } } ?>
