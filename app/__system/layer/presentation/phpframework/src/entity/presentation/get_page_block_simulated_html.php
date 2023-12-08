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
include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $project = $_GET["project"]; $block = $_GET["block"]; $data = json_decode( htmlspecialchars_decode( file_get_contents("php://input"), ENT_NOQUOTES), true); $page_region_block_params = $data["page_region_block_params"]; $page_region_block_join_points = $data["page_region_block_join_points"]; $path = str_replace("../", "", $path); $html = $editable_settings = $block_code_id = $block_code_time = null; if ($path && $project && $block) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); if ($PEVC) { $PHPVariablesFileHandler = new PHPVariablesFileHandler(array($user_global_variables_file_path, $PEVC->getConfigPath("pre_init_config"))); $PHPVariablesFileHandler->startUserGlobalVariables(); $P = $PEVC->getPresentationLayer(); $page_include_files = getPageIncludeFiles($PEVC, $data["template_includes"]); $P->setSelectedPresentationId($project); $block_path = $PEVC->getBlockPath($block); if (file_exists($block_path)) { $block_params = CMSFileHandler::getFileCreateBlockParams($block_path, false, 1, 1); $module_id = $block_params[0]["module_type"] == "string" ? $block_params[0]["module"] : ""; $block_code_id = md5(file_get_contents($block_path)); $block_code_time = filemtime($block_path); if ($module_id) { $CMSModuleSimulatorHandler = $EVC->getCMSLayer()->getCMSModuleLayer()->getModuleSimulatorObj($module_id); if ($CMSModuleSimulatorHandler) { $CMSModuleLayer = $PEVC->getCMSLayer()->getCMSModuleLayer(); $CMSModuleHandler = $CMSModuleLayer->getModuleObj($module_id); if ($CMSModuleHandler) { $cms_settings = array( "module_id" => $module_id, "block_id" => $block, ); $CMSModuleHandler->setCMSSettings($cms_settings); $CMSModuleSimulatorHandler->setCMSModuleHandler($CMSModuleHandler); $block_settings = getProjectBlockSettings($PEVC, $project, $block_params, $page_include_files, $page_region_block_params); if ($page_region_block_join_points) { foreach ($page_region_block_join_points as $join_point_name => $join_point) { foreach ($join_point as $idx => $join_point_settings) if (is_array($join_point_settings)) { $join_point_settings = getProjectJoinPointSettings($PEVC, $project, $page_include_files, $join_point_settings); $PEVC->getCMSLayer()->getCMSJoinPointLayer()->addBlockJoinPoint($block, $join_point_name, $block_join_point_properties); } } } $html = $CMSModuleSimulatorHandler->simulate($block_settings, $editable_settings); } } } else { $html = getProjectBlockHtml($PEVC, $project, $block_path, $page_include_files); } } $PHPVariablesFileHandler->endUserGlobalVariables(); } } function getPageIncludeFiles($EVC, $includes) { $page_include_files = array(); if (is_array($includes)) { foreach ($includes as $include) if ($include["path"]) { $code = "\$include_path = " . $include["path"] . ";"; eval($code); if ($include_path && file_exists($include_path)) $page_include_files[] = $include_path; } } return $page_include_files; } function getProjectJoinPointSettings($EVC, $selected_project_id, $page_include_files, $join_point_settings) { $before_defined_vars = get_defined_vars(); include $EVC->getConfigPath("config", $selected_project_id); @include_once $EVC->getModulePath("translator/include_text_translator_handler", $EVC->getCommonProjectName()); if (is_array($page_include_files)) { foreach ($page_include_files as $include) @include_once $include; } $after_defined_vars = get_defined_vars(); $external_vars = array_diff_key($after_defined_vars, $before_defined_vars); $external_vars["EVC"] = $EVC; unset($external_vars["before_defined_vars"]); $code = "<?php return " . CMSPresentationLayerHandler::getJoinPointPropertiesCode($join_point_settings) . '; ?>'; PHPScriptHandler::parseContent($code, $external_vars, $return_values); $join_point_settings = $return_values[0]; return $join_point_settings; } function getProjectBlockSettings($EVC, $selected_project_id, $block_params, $page_include_files, $page_region_block_params) { $before_defined_vars = get_defined_vars(); include $EVC->getConfigPath("config", $selected_project_id); @include_once $EVC->getModulePath("translator/include_text_translator_handler", $EVC->getCommonProjectName()); if (is_array($page_include_files)) { foreach ($page_include_files as $include) @include_once $include; } $after_defined_vars = get_defined_vars(); $external_vars = array_diff_key($after_defined_vars, $before_defined_vars); $external_vars["EVC"] = $EVC; unset($external_vars["before_defined_vars"]); $code = "<?php \n"; if ($page_region_block_params) { $arr_code = ""; foreach($page_region_block_params as $k => $v) $arr_code .= ($arr_code ? ", " : "") . "$k => $v"; $code .= '$block_local_variables = array(' . $arr_code . ");\n"; } $code .= 'return '; if (is_array($block_params[0]["block_settings"])) $code .= WorkFlowTask::getArrayString($block_params[0]["block_settings"]); else $code .= strlen($block_params[0]["block_settings"]) ? PHPUICodeExpressionHandler::getArgumentCode($block_params[0]["block_settings"], $block_params[0]["block_settings_type"]) : '""'; $code .= '; ?>'; PHPScriptHandler::parseContent($code, $external_vars, $return_values); $block_settings = $return_values[0]; return $block_settings; } function getProjectBlockHtml($EVC, $selected_project_id, $block_path, $page_include_files) { include $EVC->getConfigPath("config", $selected_project_id); @include_once $EVC->getModulePath("translator/include_text_translator_handler", $EVC->getCommonProjectName()); if (is_array($page_include_files)) { foreach ($page_include_files as $include) @include_once $include; } ob_start(null, 0); include $block_path; $html = ob_get_contents(); ob_end_clean(); return $html; } ?>
