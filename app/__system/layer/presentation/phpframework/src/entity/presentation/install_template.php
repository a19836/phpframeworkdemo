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

include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSTemplateInstallationHandler"); include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_POST ? $_POST["project"] : $_GET["path"]; $path = str_replace("../", "", $path); if ($bean_name && $bean_file_name && $path) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); if ($PEVC) { $PHPVariablesFileHandler = new PHPVariablesFileHandler(array($user_global_variables_file_path, $PEVC->getConfigPath("pre_init_config"))); $PHPVariablesFileHandler->startUserGlobalVariables(); $selected_project = $PEVC->getPresentationLayer()->getSelectedPresentationId(); if ($_POST["project"] && $_FILES["zip_file"]) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $UserAuthenticationHandler->checkInnerFilePermissionAuthentication($PEVC->getTemplatesPath(), "layer", "access"); $zipped_file_path = TMP_PATH . $_FILES["zip_file"]["name"]; $template_id = pathinfo($_FILES["zip_file"]["name"], PATHINFO_FILENAME); if ($template_id && move_uploaded_file($_FILES["zip_file"]["tmp_name"], $zipped_file_path)) { $unzipped_folder_path = CMSTemplateInstallationHandler::unzipTemplateFile($zipped_file_path); $info = CMSTemplateInstallationHandler::getUnzippedTemplateInfo($unzipped_folder_path); if ($info && $info["tag"] && $template_id != $info["tag"]) $template_id = $info["tag"]; $template_folder_path = $PEVC->getTemplatesPath() . $template_id; $webroot_folder_path = $PEVC->getWebrootPath() . "template/$template_id"; $CMSTemplateInstallationHandler = new CMSTemplateInstallationHandler($template_folder_path, $webroot_folder_path, $unzipped_folder_path); try { if ($CMSTemplateInstallationHandler->install()) { $status = true; } } catch(Exception $e) { $status = false; $messages[$path][] = array("msg" => "STATUS: FALSE", "type" => "error"); $messages[$path][] = array("msg" => "ERROR MESSAGE: " . $e->getMessage(), "type" => "exception"); $messages[$path][] = array("msg" => $e->problem, "type" => "exception"); } } CMSModuleUtil::deleteFolder($unzipped_folder_path); unlink($zipped_file_path); } $PHPVariablesFileHandler->endUserGlobalVariables(); } } $layers_projects = CMSPresentationLayerHandler::getPresentationLayersProjectsFiles($user_global_variables_file_path, $user_beans_folder_path, false, true, 0); ?>
