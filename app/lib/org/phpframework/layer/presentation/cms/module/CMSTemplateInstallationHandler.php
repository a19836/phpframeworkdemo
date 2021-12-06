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

include_once get_lib("org.phpframework.layer.presentation.cms.module.ICMSTemplateInstallationHandler"); include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleUtil"); class CMSTemplateInstallationHandler implements ICMSTemplateInstallationHandler { protected $template_folder_path; protected $webroot_folder_path; protected $unzipped_folder_path; protected $reserved_files; public function __construct($padb329c5, $ped275ca3, $pd3e94e4f = "") { $this->template_folder_path = $padb329c5; $this->webroot_folder_path = $ped275ca3; $this->unzipped_folder_path = $pd3e94e4f; $this->reserved_files = array(); } public static function unzipTemplateFile($v39d1337f82) { $pd3e94e4f = self::md7f46617252d(); if (!$pd3e94e4f) { return false; } $pea6ff323 = new ZipArchive(); if (file_exists($v39d1337f82) && $pea6ff323->open($v39d1337f82) === TRUE) { $v5c1c342594 = $pea6ff323->extractTo($pd3e94e4f); $pea6ff323->close(); if ($v5c1c342594) { return $pd3e94e4f; } } return null; } public static function getUnzippedTemplateInfo($v4cef51aa24) { $pa3f93bf0 = $v4cef51aa24 . "/template.xml"; $v872c4849e0 = null; if (file_exists($pa3f93bf0)) { $pfb662071 = XMLFileParser::parseXMLFileToArray($pa3f93bf0); $pfb662071 = MyXML::complexArrayToBasicArray($pfb662071, array("lower_case_keys" => true, "trim" => true)); $v872c4849e0 = $pfb662071["template"]; } return $v872c4849e0; } public function install() { if ($this->unzipped_folder_path && is_dir($this->unzipped_folder_path)) { $v5c1c342594 = true; if (CMSModuleUtil::copyFolder($this->unzipped_folder_path, $this->template_folder_path)) { if (is_dir($this->unzipped_folder_path . "/webroot")) { if (!CMSModuleUtil::deleteFolder($this->template_folder_path . "/webroot")) $v5c1c342594 = false; if (!CMSModuleUtil::copyFolder($this->unzipped_folder_path . "/webroot", $this->webroot_folder_path)) $v5c1c342594 = false; } } else $v5c1c342594 = false; if (file_exists($this->template_folder_path . "/template.xml")) @unlink($this->template_folder_path . "/template.xml"); if (file_exists($this->template_folder_path . "/modules_sub_templates.ser")) @unlink($this->template_folder_path . "/modules_sub_templates.ser"); return $v5c1c342594; } } public function uninstall() { $pae9f0543 = $this->getReservedFiles(); return CMSModuleUtil::deleteFolder($this->template_folder_path, $pae9f0543) && CMSModuleUtil::deleteFolder($this->webroot_folder_path, $pae9f0543); } protected function getReservedFiles() { $pae9f0543 = array(); if ($this->reserved_files) foreach ($this->reserved_files as $v7dffdb5a5b) $pae9f0543[] = file_exists($v7dffdb5a5b) ? realpath($v7dffdb5a5b) : $v7dffdb5a5b; return $pae9f0543; } private static function md7f46617252d() { $v6f181849e4 = tempnam(sys_get_temp_dir(), ''); if (file_exists($v6f181849e4)) { unlink($v6f181849e4); } @mkdir($v6f181849e4, 0755); if (is_dir($v6f181849e4)) { return $v6f181849e4; } } } ?>
