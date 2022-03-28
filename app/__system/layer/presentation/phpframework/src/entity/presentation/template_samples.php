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

include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); include_once $EVC->getUtilPath("CMSPresentationLayerUIHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $popup = $_GET["popup"]; $path = str_replace("../", "", $path);$sample_files = array(); if ($path) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); if ($PEVC) { $P = $PEVC->getPresentationLayer(); $layer_path = $P->getLayerPathSetting(); $selected_project_id = $P->getSelectedPresentationId(); $file_path = $layer_path . $path; if (file_exists($file_path)) { $dir = dirname($path) . "/"; $pos = strpos($dir, "/src/template/"); if ($pos !== false) { $start_pos = $pos + strlen("/src/template/"); $end_pos = strpos($dir, "/", $start_pos); if ($end_pos !== false) { $selected_template_dir = substr($dir, $start_pos); $selected_template_file = strtolower(pathinfo($path, PATHINFO_FILENAME)); $template_webroot_dir = $PEVC->getWebrootPath() . "template/" . $selected_template_dir; if (file_exists($template_webroot_dir)) { $PHPVariablesFileHandler = new PHPVariablesFileHandler(array($user_global_variables_file_path, $PEVC->getConfigPath("pre_init_config"))); $PHPVariablesFileHandler->startUserGlobalVariables(); $url_prefix = mf774c99d0ef1($PEVC, $selected_project_id); $PHPVariablesFileHandler->endUserGlobalVariables(); $valid_extensions = array("php", "html", "htm"); $files = scandir($template_webroot_dir); foreach ($files as $file) if ($file != "." && $file != ".." && !is_dir($template_webroot_dir . $file)) { $file_name = strtolower(pathinfo($file, PATHINFO_FILENAME)); $file_extension = strtolower(pathinfo($file, PATHINFO_EXTENSION)); $valid = in_array($file_extension, $valid_extensions) && ($file_name == $selected_template_file || preg_match("/^{$selected_template_file}[_\-]([0-9]+)$/u", $file_name)); if ($valid) $sample_files[] = $url_prefix . "template/" . $selected_template_dir . $file; } } } } } else { launch_exception(new Exception("File '$path' does not exist!")); die(); } } else { launch_exception(new Exception("PEVC doesn't exists!")); die(); } } else if (!$path) { launch_exception(new Exception("Undefined path!")); die(); } function mf774c99d0ef1($EVC, $v2508589a4c) { @include $EVC->getConfigPath("config", $v2508589a4c); return $project_url_prefix; } ?>
