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

include_once get_lib("org.phpframework.compression.ZipHandler"); include_once get_lib("org.phpframework.util.MimeTypeHandler"); include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $item_type = $_GET["item_type"]; $folder_type = $_GET["folder_type"]; $path = str_replace("../", "", $path); $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); if ($item_type == "dao") $layer_path = DAO_PATH; else if ($item_type == "vendor") $layer_path = VENDOR_PATH; else if ($item_type == "test_unit") $layer_path = TEST_UNIT_PATH; else if ($item_type == "other") $layer_path = OTHER_PATH; else { if ($item_type != "presentation") $obj = $WorkFlowBeansFileHandler->getBeanObject($bean_name); else { $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); $obj = $PEVC ? $PEVC->getPresentationLayer() : null; } $layer_path = null; if ($obj) $layer_path = $obj->getLayerPathSetting(); } $file_path = $layer_path . $path; $file_exists = file_exists($file_path); if ($path && $file_exists) { $layer_object_id = $item_type == "dao" ? "vendor/dao/$path" : ($item_type == "vendor" || $item_type == "other" ? "$item_type/$path" : ($item_type == "test_unit" ? "vendor/testunit/$path" : $file_path)); $UserAuthenticationHandler->checkInnerFilePermissionAuthentication($layer_object_id, "layer", "access"); if (is_dir($file_path)) { $tmp_file = tmpfile(); $tmp_file_path = stream_get_meta_data($tmp_file)['uri']; if (ZipHandler::zip($file_path, $tmp_file_path)) { if ($folder_type == "template_folder" && $PEVC) { $webroot_template_path = $PEVC->getWebrootPath() . "template/" . substr($file_path, strlen($PEVC->getTemplatesPath())); if (file_exists($webroot_template_path) && is_dir($webroot_template_path)) ZipHandler::addFileToZip($tmp_file_path, $webroot_template_path, "webroot/"); } header('Content-Type: application/zip'); header('Content-Length: ' . filesize($tmp_file_path)); header('Content-Disposition: attachment; filename="' . basename($file_path) . '.zip"'); readfile($tmp_file_path); } unlink($tmp_file_path); } else { $mime_type = MimeTypeHandler::getFileMimeType($file_path); $mime_type = $mime_type ? $mime_type : "application/octet-stream"; header('Content-Type: ' . $mime_type); header('Content-Length: ' . filesize($file_path)); header('Content-Disposition: attachment; filename="' . basename($file_path) . '"'); readfile($file_path); } } die(); ?>
