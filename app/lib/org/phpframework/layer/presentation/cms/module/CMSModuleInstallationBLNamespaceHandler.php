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
include_once get_lib("org.phpframework.phpscript.PHPCodePrintingHandler"); class CMSModuleInstallationBLNamespaceHandler { public function updateExtendedCommonServiceCodeInBusinessLogicPHPFiles($v2635bad135, $v08ec12d679) { $v5c1c342594 = true; if (is_array($v2635bad135) && $v08ec12d679) foreach ($v2635bad135 as $v847a7225e0) if (is_a($v847a7225e0, "BusinessLogicLayer")) { if (empty($v847a7225e0->settings["business_logic_modules_service_common_file_path"])) launch_exception(new Exception("\$Layer->settings[business_logic_modules_service_common_file_path] cannot be empty!")); $v8b2fb51ce1 = $v847a7225e0->settings["business_logic_modules_service_common_file_path"]; if (file_exists($v8b2fb51ce1)) { $v3a2d613bf9 = PHPCodePrintingHandler::getNamespacesFromFile($v8b2fb51ce1); $v3a2d613bf9 = $v3a2d613bf9[0]; $v3a2d613bf9 = substr($v3a2d613bf9, 0, 1) == "\\" ? substr($v3a2d613bf9, 1) : $v3a2d613bf9; $v3a2d613bf9 = substr($v3a2d613bf9, -1) == "\\" ? substr($v3a2d613bf9, 0, -1) : $v3a2d613bf9; if ($v3a2d613bf9) { $pa2bba2ac = $v847a7225e0->getLayerPathSetting(); $pa2bba2ac .= substr($pa2bba2ac, -1) == "/" ? "" : "/"; foreach ($v08ec12d679 as $v11506aed93) if (substr($v11506aed93, 0, strlen($pa2bba2ac)) == $pa2bba2ac && !self::f295d86f416($v11506aed93, $v3a2d613bf9)) $v5c1c342594 = false; } } } return $v5c1c342594; } private static function f295d86f416($pa32be502, $v1efaf06c58) { $v5c1c342594 = true; if ($pa32be502 && is_dir($pa32be502)) { $v6ee393d9fb = array_diff(scandir($pa32be502), array('..', '.')); foreach ($v6ee393d9fb as $v7dffdb5a5b) { $pf3dc0762 = "$pa32be502/$v7dffdb5a5b"; if (is_dir($pf3dc0762)) { if (!self::f295d86f416($pf3dc0762, $v1efaf06c58)) $v5c1c342594 = false; } else if (strtolower(pathinfo($v7dffdb5a5b, PATHINFO_EXTENSION)) == "php") { $pae77d38c = file_get_contents($pf3dc0762); if (preg_match("/\s+extends\s+\\\\CommonService/", $pae77d38c)) { $pae77d38c = preg_replace("/\s+extends\s+\\\\CommonService/", " extends \\$v1efaf06c58\\CommonService", $pae77d38c, 1); if (file_put_contents($pf3dc0762, $pae77d38c) === false) $v5c1c342594 = false; } } } } return $v5c1c342594; } } ?>
