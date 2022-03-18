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

include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleEnableHandler"); include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationHandler"); include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $module_id = $_GET["module_id"]; $action = $_GET["action"]; if ($module_id && $action) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name); if ($PEVC) { $P = $PEVC->getPresentationLayer(); $PHPVariablesFileHandler = new PHPVariablesFileHandler($user_global_variables_file_path); $PHPVariablesFileHandler->startUserGlobalVariables(); $PresentationLayer = $EVC->getPresentationLayer(); $system_presentation_settings_module_path = $PresentationLayer->getLayerPathSetting() . $PresentationLayer->getCommonProjectName() . "/" . $PresentationLayer->settings["presentation_modules_path"] . $module_id; $system_presentation_settings_webroot_module_path = $PresentationLayer->getLayerPathSetting() . $PresentationLayer->getCommonProjectName() . "/" . $PresentationLayer->settings["presentation_webroot_path"] . "module/$module_id"; if ($action == "enable" || $action == "disable") { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $CMSModuleEnableHandler = CMSModuleEnableHandler::createCMSModuleEnableHandlerObject($P, $module_id, $system_presentation_settings_module_path); $status = $action == "enable" ? $CMSModuleEnableHandler->enable() : $CMSModuleEnableHandler->disable(); if ($status) $CMSModuleEnableHandler->freeModuleCache(); } else if ($action == "uninstall") { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "delete"); $layers = WorkFlowBeansFileHandler::getLocalBeanLayersFromBrokers($user_global_variables_file_path, $user_beans_folder_path, $P->getBrokers(), true); $layers[$bean_name] = $P; $delete_system_module = deleteSystemModule($user_global_variables_file_paths, $user_beans_folder_path, $module_id, $system_presentation_settings_module_path, $system_presentation_settings_webroot_module_path, $layers); $CMSModuleInstallationHandler = CMSModuleInstallationHandler::createCMSModuleInstallationHandlerObject($layers, $module_id, $system_presentation_settings_module_path, $system_presentation_settings_webroot_module_path); $status = $CMSModuleInstallationHandler->uninstall($delete_system_module); if ($status) $CMSModuleInstallationHandler->freeModuleCache(); } $PHPVariablesFileHandler->endUserGlobalVariables(); } } function deleteSystemModule($v3d55458bcd, $v5039a77f9d, $pcd8c70bc, $v01f92f852f, $v7390898d7f, $v907fb5192b) { $v8d0aa8d4e8 = WorkFlowBeansFileHandler::getAllLayersBeanObjs($v3d55458bcd, $v5039a77f9d); $v98ddc26572 = array(); if ($v8d0aa8d4e8) foreach ($v8d0aa8d4e8 as $v8ffce2a791 => $v972f1a5c2b) { if (!$v907fb5192b[$v8ffce2a791]) $v98ddc26572[$v8ffce2a791] = $v972f1a5c2b; } if (!$v98ddc26572) return true; $pb0e1c0c6 = CMSModuleInstallationHandler::createCMSModuleInstallationHandlerObject($v98ddc26572, $pcd8c70bc, $v01f92f852f, $v7390898d7f); return !$pb0e1c0c6->isModuleInstalled(); } die($status); ?>
