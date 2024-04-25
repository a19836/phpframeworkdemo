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
include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $region = $_GET["region"]; $sample_path = $_GET["sample_path"]; $path = str_replace("../", "", $path); if ($path) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); if ($PEVC) { $P = $PEVC->getPresentationLayer(); $layer_path = $P->getLayerPathSetting(); $selected_project_id = $P->getSelectedPresentationId(); $template_file_path = $layer_path . $path; if (file_exists($template_file_path)) { $html = file_get_contents($template_file_path); if ($sample_path) { $sample_file_path = $layer_path . $sample_path; if (!file_exists($sample_file_path)) { launch_exception(new Exception("Sample file '$sample_path' doesn't exists!")); die(); } $available_regions_list = CMSPresentationLayerHandler::getAvailableRegionsList($template_file_path, $selected_project_id, true); if ($available_regions_list) foreach ($available_regions_list as $r) if (strtolower($r) == '"' . strtolower($region) . '"') { $region = substr($r, 1, -1); break; } $html = '<?php 
					$external_vars = get_defined_vars();
					
					//Regions-Blocks:
					$html_from_file = file_get_contents("' . $sample_file_path . '");
					$html_from_file = PHPScriptHandler::parseContent($html_from_file, $external_vars);
					$EVC->getCMSLayer()->getCMSTemplateLayer()->addRegionHtml("' . $region . '", \'<div class="selected_region_sample">\' . $html_from_file . \'</div>\');
				?>' . $html; } $html = f1f204a3d16($PEVC, $user_global_variables_file_path, $html); } else { launch_exception(new Exception("Template path '$path' doesn't exists!")); die(); } } else { launch_exception(new Exception("PEVC doesn't exists!")); die(); } } else if (!$path) { launch_exception(new Exception("Undefined path!")); die(); } function f1f204a3d16($EVC, $user_global_variables_file_path, $pf8ed4912) { $pfaf08f23 = new PHPVariablesFileHandler(array($user_global_variables_file_path, $EVC->getConfigPath("pre_init_config"))); $pfaf08f23->startUserGlobalVariables(); include $EVC->getConfigPath("config"); include_once $EVC->getUtilPath("include_text_translator_handler", $EVC->getCommonProjectName()); if (!isset($v34004a0ad9)) $v34004a0ad9 = $peb014cfd; if (!isset($pbe17c8e6)) $pbe17c8e6 = $v37d269c4fa; $v37f1176ca4 = tmpfile(); $v32449e14b2 = stream_get_meta_data($v37f1176ca4); $v4e03b5e19e = $v32449e14b2['uri']; $pc3772d0d = str_split($pf8ed4912, 1024 * 4); foreach ($pc3772d0d as $v306839072f) fwrite($v37f1176ca4, $v306839072f, strlen($v306839072f)); $pf0f58138 = error_reporting(); ob_start(null, 0); error_reporting(0); include $v4e03b5e19e; error_reporting($pf0f58138); $pf8ed4912 = ob_get_contents(); ob_end_clean(); fclose($v37f1176ca4); header_remove(); $pfaf08f23->endUserGlobalVariables(); return $pf8ed4912; } ?>
