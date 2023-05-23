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

include_once $EVC->getUtilPath("PHPVariablesFileHandler"); include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $project = $_GET["project"]; $module_id = $_GET["module"]; $block = $_GET["block"]; if ($project && ($module_id || $block)) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $project); if ($PEVC) { $PHPVariablesFileHandler = new PHPVariablesFileHandler(array($user_global_variables_file_path, $PEVC->getConfigPath("pre_init_config"))); $PHPVariablesFileHandler->startUserGlobalVariables(); if (!$module_id) { $block_path = $PEVC->getBlockPath($block); if ($block_path) { $block_params = CMSFileHandler::getFileCreateBlockParams($block_path); $module_id = $block_params[0]["module_type"] == "string" ? $block_params[0]["module"] : ""; } } if ($module_id) { $P = $PEVC->getPresentationLayer(); $selected_project_id = $P->getSelectedPresentationId(); $PCMSModuleLayer = $PEVC->getCMSLayer()->getCMSModuleLayer(); $PCMSModuleLayer->loadModules(getProjectCommonUrlPrefix($PEVC, $selected_project_id) . "module/"); $module = $PCMSModuleLayer->getLoadedModule($module_id); $module_handler_impl_file_path = $module["module_handler_impl_file_path"]; if ($module_handler_impl_file_path) $contents = file_get_contents($module_handler_impl_file_path); } $PHPVariablesFileHandler->endUserGlobalVariables(); } } echo $contents; die(); function getProjectCommonUrlPrefix($EVC, $selected_project_id) { include $EVC->getConfigPath("config", $selected_project_id); return $project_common_url_prefix; } ?>
