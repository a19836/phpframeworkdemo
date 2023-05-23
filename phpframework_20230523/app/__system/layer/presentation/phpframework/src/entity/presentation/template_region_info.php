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

include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $region = $_GET["region"]; $popup = $_GET["popup"]; $path = str_replace("../", "", $path);$sample_files = array(); if ($path && $region) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); if ($PEVC) { $P = $PEVC->getPresentationLayer(); $layer_path = $P->getLayerPathSetting(); $file_path = $layer_path . $path; if (file_exists($file_path)) { $dir = dirname($path) . "/"; $pos = strpos($dir, "/src/template/"); if ($pos !== false) { $start_pos = $pos + strlen("/src/template/"); $end_pos = strpos($dir, "/", $start_pos); if ($end_pos !== false) { $template_root_path = substr($dir, 0, $end_pos + 1); $template_extra_path = substr($dir, $end_pos + 1) . pathinfo($path, PATHINFO_FILENAME) . "/"; $folder_rel_path = $template_root_path . "region/" . $template_extra_path . $region . "/"; $folder_abs_path = $layer_path . $folder_rel_path; if (!file_exists($folder_abs_path)) { $folder_rel_path = $template_root_path . "region/" . $template_extra_path . strtolower($region) . "/"; $folder_abs_path = $layer_path . $folder_rel_path; } if (file_exists($folder_abs_path)) { $files = scandir($folder_abs_path); foreach ($files as $file) if ($file != "." && $file != ".." && !is_dir($folder_abs_path . $file)) $sample_files[] = $folder_rel_path . $file; } } } } else { launch_exception(new Exception("File '$path' does not exist!")); die(); } } else { launch_exception(new Exception("PEVC doesn't exists!")); die(); } } else if (!$path) { launch_exception(new Exception("Undefined path!")); die(); } else if (!$region) { launch_exception(new Exception("Undefined region!")); die(); } ?>
