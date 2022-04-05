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

include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $filter_by_layout = $_GET["filter_by_layout"]; $filter_by_layout_permission = UserAuthenticationHandler::$PERMISSION_BELONG_NAME; $filter_by_layout = str_replace("../", "", $filter_by_layout); $layers_projects = CMSPresentationLayerHandler::getPresentationLayersProjectsFiles($user_global_variables_file_path, $user_beans_folder_path, false, false, -1, false, null, true); $LayoutTypeProjectHandler = new LayoutTypeProjectHandler($UserAuthenticationHandler, $user_global_variables_file_path, $user_beans_folder_path); $LayoutTypeProjectHandler->filterPresentationLayersProjectsByUserAndLayoutPermissions($layers_projects, $filter_by_layout); $presentation_brokers = array(); if ($layers_projects) foreach ($layers_projects as $bn => $layer_props) { $layer_bean_folder_name = WorkFlowBeansFileHandler::getLayerBeanFolderName($user_beans_folder_path . $layer_props["bean_file_name"], $bn, $user_global_variables_file_path); $presentation_brokers[] = array($layer_bean_folder_name, $layer_props["bean_file_name"], $bn); } $project_details = null; if ($layers_projects) foreach ($layers_projects as $bn => $layer_props) { $layer_bean_folder_name = WorkFlowBeansFileHandler::getLayerBeanFolderName($user_beans_folder_path . $layer_props["bean_file_name"], $bn, $user_global_variables_file_path) . "/"; $proj_name = substr($filter_by_layout, strlen($layer_bean_folder_name)); if (substr($filter_by_layout, 0, strlen($layer_bean_folder_name)) == $layer_bean_folder_name && $layer_props["projects"] && $layer_props["projects"][$proj_name]) { $bean_name = $bn; $bean_file_name = $layer_props["bean_file_name"]; $project_details = $layer_props["projects"][$proj_name]; $project_id = $project_details["element_type_path"]; $project_id = preg_replace("/^[\/]+/", "", $project_id); $project_id = preg_replace("/[\/]+$/", "", $project_id); $project_details["project_id"] = $project_id; $project_details["project_id_path_parts"] = explode("/", $project_details["project_id"]); break; } } ?>
