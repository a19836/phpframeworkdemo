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
include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); include_once $EVC->getUtilPath("LayoutTypeProjectHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $filter_by_layout = $_GET["filter_by_layout"]; $selected_layout_project = $_GET["selected_layout_project"]; $popup = $_GET["popup"]; $folder_to_filter = $_GET["folder_to_filter"]; $redirect_path = urldecode($_GET["redirect_path"]); $redirect_path = $redirect_path ? $redirect_path : "admin"; $filter_by_layout = str_replace("../", "", $filter_by_layout);$selected_layout_project = str_replace("../", "", $selected_layout_project); $layers_projects = CMSPresentationLayerHandler::getPresentationLayersProjectsFiles($user_global_variables_file_path, $user_beans_folder_path, false, false, -1, true, null, true); $LayoutTypeProjectHandler = new LayoutTypeProjectHandler($UserAuthenticationHandler, $user_global_variables_file_path, $user_beans_folder_path); $LayoutTypeProjectHandler->filterPresentationLayersProjectsByUserAndLayoutPermissions($layers_projects, $filter_by_layout); $projects_exists = false; $selected_project_id = null; if ($layers_projects) foreach ($layers_projects as $bean_name => $layer_props) { $layers_projects[$bean_name]["layer_bean_folder_name"] = WorkFlowBeansFileHandler::getLayerBeanFolderName($user_beans_folder_path . $layer_props["bean_file_name"], $bean_name, $user_global_variables_file_path); if (count($layer_props["projects"])) { $projects_exists = true; foreach ($layer_props["projects"] as $proj_id => $proj_props) if ($proj_props["path"] == LAYER_PATH . $selected_layout_project) { $selected_project_id = $proj_id; break; } break; } } ?>
