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
include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); include_once $EVC->getUtilPath("LayoutTypeProjectHandler"); include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleEnableHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $popup = $_GET["popup"]; $filter_by_layout = $_GET["filter_by_layout"]; $filter_by_layout = str_replace("../", "", $filter_by_layout); $files = CMSPresentationLayerHandler::getPresentationLayersProjectsFiles($user_global_variables_file_path, $user_beans_folder_path); $LayoutTypeProjectHandler = new LayoutTypeProjectHandler($UserAuthenticationHandler, $user_global_variables_file_path, $user_beans_folder_path); if ($_GET["bean_name"]) $default_presentation_layer_name = $_GET["bean_name"]; else { $files_bkp = $files; $LayoutTypeProjectHandler->filterPresentationLayersProjectsByUserAndLayoutPermissions($files_bkp, $filter_by_layout, UserAuthenticationHandler::$PERMISSION_BELONG_NAME); $default_presentation_layer_name = $files_bkp ? key($files_bkp) : null; } $CMSModuleLayer = $EVC->getCMSLayer()->getCMSModuleLayer(); $CMSModuleLayer->loadModules($project_common_url_prefix . "module/"); $loaded_modules = $CMSModuleLayer->getLoadedModules(); $modules = array(); if (is_array($files)) { foreach ($files as $bean_name => $layer_props) { $bean_file_name = $layer_props["bean_file_name"]; $item_label = $layer_props["item_label"]; $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name); $PCMSModuleLayer = $PEVC->getCMSLayer()->getCMSModuleLayer(); $PCMSModuleLayer->loadModules($project_common_url_prefix . "module/"); $project_loaded_modules = $PCMSModuleLayer->getLoadedModules(); $modules[] = array( "bean_name" => $bean_name, "bean_file_name" => $bean_file_name, "item_label" => $item_label, "modules" => $project_loaded_modules, ); } } if (is_array($loaded_modules)) { $loaded_modules_by_group = array(); foreach ($loaded_modules as $module_id => $loaded_module) { $group_module_id = $loaded_module["group_id"]; $loaded_modules_by_group[$group_module_id][$module_id] = $loaded_module; } $loaded_modules = $loaded_modules_by_group; ksort($loaded_modules); } $is_install_module_allowed = $UserAuthenticationHandler->isPresentationFilePermissionAllowed($EVC->getEntityPath("admin/install_module"), "access"); $is_module_admin_allowed = $UserAuthenticationHandler->isPresentationFilePermissionAllowed($EVC->getEntityPath("admin/module_admin"), "access"); ?>
