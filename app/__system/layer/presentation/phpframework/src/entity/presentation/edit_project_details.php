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

include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); include_once $EVC->getUtilPath("LayoutTypeProjectHandler"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $popup = $_GET["popup"]; $on_success_js_func = $_GET["on_success_js_func"]; $path = str_replace("../", "", $path); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $layers_projects = CMSPresentationLayerHandler::getPresentationLayersProjectsFiles($user_global_variables_file_path, $user_beans_folder_path, false, false, -1, false, null, true); $LayoutTypeProjectHandler = new LayoutTypeProjectHandler($UserAuthenticationHandler, $user_global_variables_file_path, $user_beans_folder_path); $LayoutTypeProjectHandler->filterPresentationLayersProjectsByUserAndLayoutPermissions($layers_projects, $filter_by_layout); $presentation_brokers = array(); if ($layers_projects) foreach ($layers_projects as $bn => $layer_props) { $layer_bean_folder_name = WorkFlowBeansFileHandler::getLayerBeanFolderName($user_beans_folder_path . $layer_props["bean_file_name"], $bn, $user_global_variables_file_path); $presentation_brokers[] = array($layer_bean_folder_name, $layer_props["bean_file_name"], $bn); } if ($_POST && trim($_POST["name"])) { $project = trim($_POST["old_name"]) ? trim($_POST["old_name"]) : trim($_POST["name"]); $project_folder = trim($_POST["old_project_folder"]); $path = ($project_folder ? $project_folder . "/" : "") . $project; if ($_POST["is_existent_project"]) { $is_rename_project = trim($_POST["project_folder"]) != trim($_POST["old_project_folder"]) || trim($_POST["name"]) != trim($_POST["old_name"]); if ($is_rename_project) { $path = (trim($_POST["project_folder"]) ? trim($_POST["project_folder"]) . "/" : "") . trim($_POST["name"]); } } else { $path = (trim($_POST["project_folder"]) ? trim($_POST["project_folder"]) . "/" : "") . trim($_POST["name"]); } } $path = preg_replace("/[\/]+/", "/", $path); $path = preg_replace("/^[\/]+/", "", $path); $path = preg_replace("/[\/]+$/", "", $path); $project_props = $layers_projects && $layers_projects[$bean_name] && $layers_projects[$bean_name]["projects"] && $layers_projects[$bean_name]["projects"][$path] ? $layers_projects[$bean_name]["projects"][$path] : null; $is_existent_project = $project_props && $project_props["item_type"] != "project_folder"; if ($is_existent_project) { prepareProjectPaths($path, $project_folder, $project); $old_project_folder = $project_folder; $old_project = $project; $project_description = $project_props["description"]; $project_image = $project_props["logo_url"]; if ($_POST) { if (!trim($_POST["name"])) $error_message = "Project name cannot be empty"; else { $is_previous_existent_project = $_POST["is_existent_project"]; $project = trim($_POST["name"]); $project_description = $_POST["description"]; $project_folder = trim($_POST["project_folder"]); $project_folder = $project_folder == "." ? "" : $project_folder; $project_path = ($project_folder ? $project_folder . "/" : "") . $project; prepareProjectPaths($project_path, $project_folder, $project); $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $layers_projects[$bean_name]["bean_file_name"], $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); $status = true; if ($is_rename_project) { $status = is_dir($project_props["path"]); if (!$status) $error_message = "Project could not be moved to the new folder. Maybe there is already a project with the same name in this new folder."; } if ($status) { $webroot_path = $PEVC->getWebrootPath(); $file_path = $webroot_path . "humans.txt"; $status = file_put_contents($file_path, $project_description) !== false; if ($status && $_FILES["image"]["name"]) { $dst_path = $project_props["logo_path"]; $dst_path = $dst_path ? $dst_path : $PEVC->getPresentationLayer()->getSelectedPresentationSetting("presentation_webroot_path") . "favicon.ico"; if (move_uploaded_file($_FILES["image"]["tmp_name"], $dst_path)) $project_image = $project_props["logo_path"] ? $project_props["logo_url"] : $project_props["url"] . "favicon.ico"; else $status = false; } } } } } else { $project_folder = $path; $old_project_folder = $project_folder; if ($_POST) $status = false; } function prepareProjectPaths($pa32be502, &$v42b0d9ac32, &$v93756c94b3) { $pa32be502 = preg_replace("/[\/]+/", "/", $pa32be502); $pa32be502 = preg_replace("/^[\/]+/", "", $pa32be502); $pa32be502 = preg_replace("/[\/]+$/", "", $pa32be502); $v42b0d9ac32 = $pa32be502 ? dirname($pa32be502) : ""; $v42b0d9ac32 = $v42b0d9ac32 == "." ? "" : $v42b0d9ac32; $v42b0d9ac32 = preg_replace("/^[\/]+/", "", $v42b0d9ac32); $v42b0d9ac32 = preg_replace("/[\/]+$/", "", $v42b0d9ac32); $v93756c94b3 = basename($pa32be502); } ?>
