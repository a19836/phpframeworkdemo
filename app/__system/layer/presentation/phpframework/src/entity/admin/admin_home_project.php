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

include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $active_tab = $_GET["active_tab"]; $filter_by_layout = $_GET["filter_by_layout"]; $filter_by_layout_permission = UserAuthenticationHandler::$PERMISSION_BELONG_NAME; $filter_by_layout = str_replace("../", "", $filter_by_layout); $presentation_brokers = array(); $project_details = null; $layers = AdminMenuHandler::getLayers($user_global_variables_file_path); $presentation_layers = $layers["presentation_layers"]; if (is_array($presentation_layers)) foreach ($presentation_layers as $bn => $bfn) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bfn, $user_global_variables_file_path); $P = $WorkFlowBeansFileHandler->getBeanObject($bn); $presentation_broker_name = WorkFlowBeansFileHandler::getLayerObjFolderName($P); $layer_bean_folder_name = $presentation_broker_name . "/"; if (substr($filter_by_layout, 0, strlen($layer_bean_folder_name)) == $layer_bean_folder_name) { $presentation_brokers[] = array($presentation_broker_name, $bfn, $bn); $proj_name = substr($filter_by_layout, strlen($layer_bean_folder_name)); $layer_path = $P->getLayerPathSetting(); $is_project = $proj_name ? is_dir($layer_path . $proj_name . "/" . $P->settings["presentation_webroot_path"]) : false; if ($is_project) { $bean_name = $bn; $bean_file_name = $bfn; $project_details = CMSPresentationLayerHandler::getPresentationLayerProjectFiles($user_global_variables_file_path, $user_beans_folder_path, $bean_file_name, $bean_name, $layer_path, $proj_name, "", false, -1, true); $project_id = $project_details["element_type_path"]; $project_id = preg_replace("/^[\/]+/", "", $project_id); $project_id = preg_replace("/[\/]+$/", "", $project_id); $project_details["project_id"] = $project_id; $project_details["project_id_path_parts"] = explode("/", $project_details["project_id"]); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $project_id); $PHPVariablesFileHandler = new PHPVariablesFileHandler(array($user_global_variables_file_path, $PEVC->getConfigPath("pre_init_config"))); $PHPVariablesFileHandler->startUserGlobalVariables(); $default_extension = "." . $P->getPresentationFileExtension(); $available_templates = CMSPresentationLayerHandler::getAvailableTemplatesList($PEVC, $default_extension); $available_templates = array_keys($available_templates); $available_templates_props = CMSPresentationLayerHandler::getAvailableTemplatesProps($PEVC, $project_id, $available_templates); $project_default_template = $GLOBALS["project_default_template"]; $PHPVariablesFileHandler->endUserGlobalVariables(); } } } ?>
