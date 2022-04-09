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

include_once $EVC->getUtilPath("AdminMenuUIHandler"); include_once $EVC->getUtilPath("WorkFlowPresentationHandler"); $entities_get_sub_files_url = $project_url_prefix . "admin/get_sub_files?bean_name=$bean_name&bean_file_name=$bean_file_name&path=" . $project_details["project_id"] . "/src/entity/&item_type=presentation&folder_type=entity"; $templates_get_sub_files_url = $project_url_prefix . "admin/get_sub_files?bean_name=$bean_name&bean_file_name=$bean_file_name&path=" . $project_details["project_id"] . "/src/template/&item_type=presentation&folder_type=template"; $webroot_get_sub_files_url = $project_url_prefix . "admin/get_sub_files?bean_name=$bean_name&bean_file_name=$bean_file_name&path=" . $project_details["project_id"] . "/webroot/&item_type=presentation&folder_type=webroot"; $choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#"; $get_file_properties_url = $project_url_prefix . "phpframework/admin/get_file_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&class_name=#class_name#&type=#type#"; $admin_home_page_url = $project_url_prefix . "admin/admin_home?selected_layout_project=$filter_by_layout"; $edit_project_url = $project_url_prefix . "phpframework/presentation/edit_project_details?bean_name=$bean_name&bean_file_name=$bean_file_name&path=" . $project_details["project_id"] . "&popup=1&on_success_js_func=onSucccessfullEditProject"; $view_entity_url = $project_url_prefix . "phpframework/presentation/view_project?bean_name=$bean_name&bean_file_name=$bean_file_name&path=#path#"; $edit_entity_url = $project_url_prefix . "phpframework/presentation/edit_entity?bean_name=$bean_name&bean_file_name=$bean_file_name&path=#path#&filter_by_layout=" . $project_details["project_id"]; $edit_template_url = $project_url_prefix . "phpframework/presentation/edit_template?bean_name=$bean_name&bean_file_name=$bean_file_name&path=#path#&filter_by_layout=" . $project_details["project_id"]; $manage_file_url = $project_url_prefix . "phpframework/presentation/manage_file?bean_name=$bean_name&bean_file_name=$bean_file_name&path=#path#&action=#action#&item_type=presentation&extra=#extra#"; $save_project_default_template_url = $project_url_prefix . "phpframework/presentation/save_project_default_template?bean_name=$bean_name&bean_file_name=$bean_file_name&path=" . $project_details["project_id"] . "/src/config/pre_init_config.php"; $install_template_url = $project_url_prefix . "phpframework/presentation/install_template?bean_name=$bean_name&bean_file_name=$bean_file_name&path=" . $project_details["project_id"] . "&filter_by_layout=" . $project_details["project_id"] . "&popup=1&on_success_js_func=onSucccessfullInstallTemplate"; $head = AdminMenuUIHandler::getHeader($project_url_prefix, $project_common_url_prefix); $head .= '
<!-- Add MD5 JS Files -->
<script type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquery/js/jquery.md5.js"></script>

<!-- Add Edit PHP Code JS -->
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/edit_php_code.js"></script>

<!-- Add Choose AvailableTemplate CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/choose_available_template.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/choose_available_template.js"></script>

<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/admin_home_project.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/admin_home_project.js"></script>
'; $head .= '<script>
var active_tab = ' . (is_numeric($active_tab) ? $active_tab : 0) . ';
var project_default_template = \'' . $project_default_template . '\';

var admin_home_page_url = \'' . $admin_home_page_url . '\';
var edit_project_url = \'' . $edit_project_url . '\';
var view_entity_url = \'' . $view_entity_url . '\';
var edit_entity_url = \'' . $edit_entity_url . '\';
var edit_template_url = \'' . $edit_template_url . '\';
var manage_file_url = \'' . $manage_file_url . '\';
var save_project_default_template_url = \'' . $save_project_default_template_url . '\';
var install_template_url = \'' . $install_template_url . '\';
var available_templates_props = ' . json_encode($available_templates_props) . ';
'; $head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url); $head .= '</script>'; $main_content = '
<div class="admin_panel">
	<ul class="header_links">
		<li>
			<a href="' . $admin_home_page_url . '">Projects</a>
		</li>'; if ($project_details && $project_details["project_id_path_parts"]) { $t = count($project_details["project_id_path_parts"]); $path_prefix = ""; for ($i = 0; $i < $t; $i++) { $part = $project_details["project_id_path_parts"][$i]; $path_prefix .= ($path_prefix ? "/" : "") . $part; $main_content .= '<li class="breadcrumb-item active">'; if ($i == $t - 1) $main_content .= '<a href="javascript:void(0)" onClick="document.location=\'\' + document.location;">' . $part . '</a>'; else $main_content .= '<a href="' . $admin_home_page_url . '&folder_to_filter=' . $path_prefix . '">' . $part . '</a>'; $main_content .= '</li>'; } } $main_content .= '</ul>'; if ($project_details) { $main_content .= '
	<div class="project">
		<div class="image">
			' . ($project_details["logo_url"] ? '<img src="' . $project_details["logo_url"] . '" alt="No Image" />' : '<div class="no_logo"></div>') . '
		</div>
		<div class="details">
			<div class="name">' . basename($project_details["project_id"]) . '</div>
			<div class="description">' . str_replace("\n", "<br/>", $project_details["description"]) . '</div>
			<div class="buttons">
				<a onClick="editProject()"><i class="icon edit"></i> Edit</a>
				<a href="' . str_replace("#path#", $project_details["project_id"], $view_entity_url) . '" target="project"><i class="icon view"></i> Preview</a>
				<div class="sub_menu">
					<i class="icon sub_menu"></i>
					
					<ul class="jqcontextmenu">
						<li class="remove">
							<a onClick="manageFile(this, \'project\', \'remove\', \'' . $project_details["project_id"] . '\', onSucccessfullRemoveProject)">Delete Project</a>
						</li>
						<li class="edit_project_global_variables">
							<a href="' . $project_url_prefix . "phpframework/presentation/edit_project_global_variables?bean_name=$bean_name&bean_file_name=$bean_file_name&path=" . $project_details["project_id"] . '/src/config/pre_init_config.php">Edit Project Global Variables</a>
						</li>
						<li class="install_program">
							<a href="' . $project_url_prefix . "phpframework/admin/install_program?bean_name=$bean_name&bean_file_name=$bean_file_name&path=" . $project_details["project_id"] . '">Install Program</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	
	<div class="project_files">
		<ul class="tabs tabs_transparent project_tabs">
			<li><a href="#pages" onClick="onClickPagesTab()">Pages</a></li>
			<li><a href="#templates" onClick="onClickTemplatesTab()">Templates</a></li>
		</ul>
		
		<div id="pages" class="pages" root_path="' . $project_details["project_id"] . '/src/entity/">
			<button class="create_page" onClick="createFile(this, \'page\', \'create_file\', \'' . $project_details["project_id"] . '/src/entity/\', onSucccessfullCreateFile)">Create a new Page</button>
			<button class="create_folder" onClick="createFile(this, \'folder\', \'create_folder\', \'' . $project_details["project_id"] . '/src/entity/\', onSucccessfullCreateFolder)">Create a new Folder</button>
			
			<ul class="mytree">
				<li class="jstree-open">
					<label>Pages</label>
					<ul url="' . $entities_get_sub_files_url . '"></ul>
				</li>
			</ul>
		</div>
		
		<div id="templates" class="templates" root_path="' . $project_details["project_id"] . '/src/template/">
			<div class="project_default_template">
				<div>Default template:</div>
				<i class="icon"></i>
				<span>' . ($project_default_template ? $project_default_template : '-- none --') . '</span>
			</div>
			' . ($get_store_templates_url ? '<button class="browse_templates" onClick="browseTemplates()" title="Choose a template from our store and install it">Browse Templates</button>' : '') . '
			<button class="import_templates" onClick="importTemplates()" title="Upload and install a new template">Import a Template</button>
			
			<ul class="mytree">
				<li class="jstree-open">
					<label>Templates</label>
					<ul url="' . $templates_get_sub_files_url . '"></ul>
				</li>
			</ul>
		</div>
	</div>'; } else $main_content .= '<div class="no_project">This project doesn\'t exists.</div>'; $main_content .= '
</div>'; ?>
