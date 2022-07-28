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

include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $popup = $_GET["popup"]; $path = str_replace("../", "", $path);$sample_files = array(); if ($path) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); if ($PEVC) { $P = $PEVC->getPresentationLayer(); $layer_path = $P->getLayerPathSetting(); $default_extension = "." . $P->getPresentationFileExtension(); $templates_folder = $PEVC->getTemplatesPath(); $available_templates = CMSPresentationLayerHandler::getAvailableTemplatesList($PEVC, $default_extension); $available_templates = array_keys($available_templates); $available_templates_regions = array(); foreach ($available_templates as $at) { $at_folder = dirname($at) . "/"; $at_file_name = basename($at); $at_regions_folder = $templates_folder . $at_folder . "region/$at_file_name/"; if (file_exists($at_regions_folder)) { $files = scandir($at_regions_folder); $regions = array(); foreach ($files as $file) if ($file != "." && $file != ".." && is_dir($at_regions_folder . $file)) { $region_path = $at_regions_folder . $file . "/"; $region_name = pathinfo($file, PATHINFO_FILENAME); $sub_files = scandir($region_path); $region_samples = array(); foreach ($sub_files as $sub_file) if ($sub_file != "." && $sub_file != ".." && !is_dir($region_path . $sub_file)) $region_samples[ pathinfo($sub_file, PATHINFO_FILENAME) ] = array( "sample_path" => substr($region_path . $sub_file, strlen($layer_path)), "template_path" => substr($PEVC->getTemplatePath($at), strlen($layer_path)), "html" => file_get_contents($region_path . $sub_file), ); $regions[$region_name] = $region_samples; } if ($regions) $available_templates_regions[$at] = $regions; } } } else { launch_exception(new Exception("PEVC doesn't exists!")); die(); } } else if (!$path) { launch_exception(new Exception("Undefined path!")); die(); } ?>
