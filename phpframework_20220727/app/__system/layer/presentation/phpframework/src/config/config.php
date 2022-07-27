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

$project_protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; $parts = explode("/__system/", $_SERVER["REQUEST_URI"]); if (count($parts) > 1) { $project_relative_url_prefix = $parts[0] . "/__system/"; $project_common_relative_url_prefix = $parts[0] . "/__system/" . $EVC->getCommonProjectName() . "/"; } else { $document_root = str_replace("//", "/", (isset($_SERVER["CONTEXT_DOCUMENT_ROOT"]) ? $_SERVER["CONTEXT_DOCUMENT_ROOT"] : $_SERVER["DOCUMENT_ROOT"]) . "/"); $project_relative_url_prefix = "/" . (strpos($document_root, "/__system/") !== false ? "" : "__system/"); $project_common_relative_url_prefix = "/" . (strpos($document_root, "/__system/") !== false ? "" : "__system/") . $EVC->getCommonProjectName() . "/"; } $project_url_prefix = $project_protocol . $_SERVER["HTTP_HOST"] . $project_relative_url_prefix; $project_common_url_prefix = $project_protocol . $_SERVER["HTTP_HOST"] . $project_common_relative_url_prefix; $presentation_webroot_path = $EVC->getPresentationLayer()->getSelectedPresentationSetting("presentation_webroot_path"); $webroot_cache_folder_path = $presentation_webroot_path . "__system/cache/"; $webroot_cache_folder_url = $project_url_prefix . "phpframework/__system/cache/"; $css_and_js_optimizer_webroot_cache_folder_path = $presentation_webroot_path . "__system/cache/cssandjsoptimizer/"; $css_and_js_optimizer_webroot_cache_folder_url = $project_url_prefix . "phpframework/__system/cache/cssandjsoptimizer/"; $workflow_paths_id = array( "layer" => CMS_PATH . "other/workflow/layer/layers.xml", "db_diagram" => CMS_PATH . "other/workflow/db_diagram/db_diagram.xml", "php_file_workflow" => CMS_PATH . "other/workflow/php_file_flow/php_file_workflow.xml", "php_file_workflow_tmp" => TMP_PATH . "workflow/php_file_flow/php_file_workflow.xml", "business_logic_workflow" => CMS_PATH . "other/workflow/business_logic_flow/business_logic_workflow.xml", "business_logic_workflow_tmp" => TMP_PATH . "workflow/business_logic_flow/business_logic_workflow.xml", "presentation_workflow" => CMS_PATH . "other/workflow/presentation_flow/presentation_workflow.xml", "presentation_workflow_tmp" => TMP_PATH . "workflow/presentation_flow/presentation_workflow.xml", "presentation_ui" => CMS_PATH . "other/workflow/presentation_ui/presentation_uis_diagram.xml", "presentation_block_workflow" => CMS_PATH . "other/workflow/presentation_block_flow/presentation_workflow.xml", "presentation_block_workflow_tmp" => TMP_PATH . "workflow/presentation_block_flow/presentation_workflow.xml", "presentation_block_form_sla" => CMS_PATH . "other/workflow/presentation_block_form_sla/presentation_workflow.xml", "presentation_block_form_sla_tmp" => TMP_PATH . "workflow/presentation_block_form_sla/presentation_workflow.xml", "presentation_entity_sla" => CMS_PATH . "other/workflow/presentation_entity_sla/presentation_workflow.xml", "presentation_entity_sla_tmp" => TMP_PATH . "workflow/presentation_entity_sla/presentation_workflow.xml", "presentation_template_sla" => CMS_PATH . "other/workflow/presentation_template_sla/presentation_workflow.xml", "presentation_template_sla_tmp" => TMP_PATH . "workflow/presentation_template_sla/presentation_workflow.xml", "test_unit_workflow" => CMS_PATH . "other/workflow/test_unit_flow/test_unit_workflow.xml", "test_unit_workflow_tmp" => TMP_PATH . "workflow/test_unit_flow/test_unit_workflow.xml", "deployment" => CMS_PATH . "other/workflow/deployment/deployment.xml", ); $deployments_temp_folder_path = TMP_PATH . "deployment/"; $programs_temp_folder_path = TMP_PATH . "program/"; $code_workflow_editor_user_tasks_folders_path = array(CODE_WORKFLOW_EDITOR_TASK_PATH); $layout_ui_editor_user_widget_folders_path = array(LAYOUT_UI_EDITOR_WIDGET_PATH); $user_global_variables_file_path = CONFIG_PATH . "global_variables.php"; $user_global_settings_file_path = CONFIG_PATH . "global_settings.php"; $user_beans_folder_path = BEAN_PATH; $cms_page_cache_path_prefix = "presentation_cms_pages_file_modified_date/"; $sanitize_html_in_post_request = false; include_once get_lib("org.phpframework.util.web.html.CssAndJSFilesOptimizer"); $CssAndJSFilesOptimizer = new CssAndJSFilesOptimizer($css_and_js_optimizer_webroot_cache_folder_path, $css_and_js_optimizer_webroot_cache_folder_url, array( "urls_prefix" => array($project_url_prefix, $project_common_url_prefix), "url_strings_to_avoid" => array("/cssandjsoptimizer/", "highlight.pack.js"), )); $version = 1; $modules_download_page_url = "https://phpframework.pt/store#modules"; $templates_download_page_url = "https://phpframework.pt/store#templates"; $programs_download_page_url = "https://phpframework.pt/store#programs"; $get_store_modules_url = "https://phpframework.pt/get_store_type_content?type=modules&data_type=json"; $get_store_templates_url = "https://phpframework.pt/get_store_type_content?type=templates&data_type=json"; $get_store_programs_url = "https://phpframework.pt/get_store_type_content?type=programs&data_type=json"; $is_localhost = strpos($project_common_url_prefix, "jplpinto.localhost/") !== false; $gpl_js_url_prefix = $is_localhost ? $project_common_url_prefix . "vendor/" : "//jamapconsult.pt/gpl_js/"; $proprietary_js_url_prefix = $is_localhost ? $project_common_url_prefix . "vendor/" : "//jplpinto.com/others/onlineitframework/proprietary_js/"; ?>
