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

include_once $EVC->getUtilPath("AdminMenuUIHandler"); include_once $EVC->getUtilPath("WorkFlowPresentationHandler"); include $EVC->getUtilPath("BreadCrumbsUIHandler"); $is_project_common = $EVC->getCommonProjectName() == $project_details["project_id"]; $entities_get_sub_files_url = $project_url_prefix . "admin/get_sub_files?bean_name=$bean_name&bean_file_name=$bean_file_name&path=" . $project_details["project_id"] . "/src/entity/&item_type=presentation&folder_type=entity"; $templates_get_sub_files_url = $project_url_prefix . "admin/get_sub_files?bean_name=$bean_name&bean_file_name=$bean_file_name&path=" . $project_details["project_id"] . "/src/template/&item_type=presentation&folder_type=template"; $webroot_get_sub_files_url = $project_url_prefix . "admin/get_sub_files?bean_name=$bean_name&bean_file_name=$bean_file_name&path=" . $project_details["project_id"] . "/webroot/&item_type=presentation&folder_type=webroot"; $choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#"; $upload_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/upload_file?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#"; $get_file_properties_url = $project_url_prefix . "phpframework/admin/get_file_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&class_name=#class_name#&type=#type#"; $admin_home_page_url = $project_url_prefix . "admin/admin_home?selected_layout_project=$filter_by_layout"; $edit_project_url = $project_url_prefix . "phpframework/presentation/edit_project_details?bean_name=$bean_name&bean_file_name=$bean_file_name&path=" . $project_details["project_id"] . "&popup=1&on_success_js_func=onSuccessfullEditProject"; $view_entity_url = $project_url_prefix . "phpframework/presentation/view_project?bean_name=$bean_name&bean_file_name=$bean_file_name&path=#path#"; $edit_entity_url = $project_url_prefix . "phpframework/presentation/edit_entity?bean_name=$bean_name&bean_file_name=$bean_file_name&path=#path#&filter_by_layout=" . $project_details["project_id"]; $edit_template_url = $project_url_prefix . "phpframework/presentation/edit_template?bean_name=$bean_name&bean_file_name=$bean_file_name&path=#path#&filter_by_layout=" . $project_details["project_id"]; $manage_file_url = $project_url_prefix . "phpframework/presentation/manage_file?bean_name=$bean_name&bean_file_name=$bean_file_name&path=#path#&action=#action#&item_type=presentation&extra=#extra#"; $save_project_default_template_url = $project_url_prefix . "phpframework/presentation/save_project_default_template?bean_name=$bean_name&bean_file_name=$bean_file_name&path=" . $project_details["project_id"] . "/src/config/pre_init_config.php"; $install_template_url = $project_url_prefix . "phpframework/presentation/install_template?bean_name=$bean_name&bean_file_name=$bean_file_name&path=" . $project_details["project_id"] . "&filter_by_layout=" . $project_details["project_id"] . "&popup=1&on_success_js_func=onSucccessfullInstallTemplate"; $head = AdminMenuUIHandler::getHeader($project_url_prefix, $project_common_url_prefix); $head .= '
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
'; $head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url, $upload_bean_layer_files_from_file_manager_url); $head .= '</script>'; $main_content = '
<div class="admin_panel">
	<div class="top_bar">
		<header>
			<div class="title">
				<div class="breadcrumbs">
					<span class="breadcrumb-item fixed"><a href="' . $admin_home_page_url . '">Home</a></span>'; if ($project_details && $project_details["project_id"]) { $dirname = dirname($project_details["project_id"]); $basename = basename($project_details["project_id"]); if ($dirname && $dirname != ".") $main_content .= BreadCrumbsUIHandler::getFilePathBreadCrumbsItemsHtml($dirname, null, false, "$admin_home_page_url&folder_to_filter=#path#", "fixed"); $main_content .= '<span class="breadcrumb-item fixed"><a href="javascript:void(0)" onClick="document.location=\'\' + document.location;">' . $basename . '</a></span>'; } $main_content .= '	</div>
			</div>
		</header>
	</div>'; if ($project_details) { $project_name = basename($project_details["project_id"]); $main_content .= '
	<div class="project' . ($project_details["logo_url"] ? ' with_image' : '') . ($is_project_common ? ' project_common' : '') . '">
		<div class="project_title">'; if ($is_project_common) $main_content .= '<span class="name">' . $project_name . '</span>'; else $main_content .= '
			<span class="name" onClick="editProject()">' . $project_name . '</span>
			<span class="sub_menu" onClick="openSubmenu(this)">
				<i class="icon sub_menu_vertical active"></i>
				
				<ul class="mycontextmenu with_top_right_triangle">
					<li class="edit">
						<a onClick="editProject()">Edit Project Details</a>
					</li>
					<li class="view_project">
						<a href="' . str_replace("#path#", $project_details["project_id"], $view_entity_url) . '" target="project">Preview Project</a>
					</li>
					<li class="line_break"></li>
					<li class="remove">
						<a onClick="manageFile(this, \'project\', \'remove\', \'' . $project_details["project_id"] . '\', onSucccessfullRemoveProject)">Delete Project</a>
					</li>
					<li class="line_break"></li>
					<li class="edit_project_global_variables">
						<a href="' . $project_url_prefix . "phpframework/presentation/edit_project_global_variables?bean_name=$bean_name&bean_file_name=$bean_file_name&path=" . $project_details["project_id"] . '/src/config/pre_init_config.php">Edit Project Global Variables</a>
					</li>
					<li class="install_program">
						<a href="' . $project_url_prefix . "phpframework/admin/install_program?bean_name=$bean_name&bean_file_name=$bean_file_name&path=" . $project_details["project_id"] . '">Install Program</a>
					</li>
				</ul>
			</span>'; $main_content .= '
		</div>
		
		' . ($project_details["logo_url"] ? '<div class="project_image" onClick="editProject()"><img src="' . $project_details["logo_url"] . '" alt="No Image" onError="$(this).parent().closest(\'.project\').removeClass(\'with_image\')" /></div>' : '') . '
		<div class="project_description" onClick="editProject()">' . str_replace("\n", "<br/>", $project_details["description"]) . '</div>
	</div>
	
	<div class="project_files">
		<ul class="tabs tabs_transparent project_tabs">
			<li><a href="#pages" onClick="onClickPagesTab()">Pages</a></li>
			<li><a href="#templates" onClick="onClickTemplatesTab()">Templates</a></li>
		</ul>
		
		<div id="pages" class="pages" root_path="' . $project_details["project_id"] . '/src/entity/">
			<div class="projects_list_type">
				<a href="javascript:void(0)" onclick="toggleProjectsListType(this, \'block_view\')" title="Show blocks view"><span class="icon block_view active"></span></a>
				<a href="javascript:void(0)" onclick="toggleProjectsListType(this, \'list_view\')" title="Show list view"><span class="icon list_view"></span></a>
			</div>
			
			<button onClick="showCreateFilePopup(this, \'' . $project_details["project_id"] . '/src/entity/\')" title="Create a new folder or page">Add New</button>
			<span class="search_file">
				<i class="icon search active"></i>
				<input placeHolder="Search" onKeyUp="searchFiles(this)" />
			</span>
			
			<ul class="mytree block_view">
				<li class="jstree-open">
					<label>Pages</label>
					<ul url="' . $entities_get_sub_files_url . '"></ul>
				</li>
			</ul>
		</div>
		
		<div id="templates" class="templates" root_path="' . $project_details["project_id"] . '/src/template/">
			<div class="projects_list_type">
				<a href="javascript:void(0)" onclick="toggleProjectsListType(this, \'block_view\')" title="Show blocks view"><span class="icon block_view active"></span></a>
				<a href="javascript:void(0)" onclick="toggleProjectsListType(this, \'list_view\')" title="Show list view"><span class="icon list_view"></span></a>
			</div>
			
			<button onClick="importTemplates()" title="Upload or choose a template from our store to install it">Add New</button>
			<span class="search_file">
				<i class="icon search active"></i>
				<input placeHolder="Search" onKeyUp="searchFiles(this)" />
			</span>
			
			<div class="project_default_template">
				Default template: <span class="breadcrumbs">' . ($project_default_template ? BreadCrumbsUIHandler::getFilePathBreadCrumbsItemsHtml($project_default_template) : '-- none --') . '</span>
			</div>
			
			<ul class="mytree block_view">
				<li class="jstree-open">
					<label>Templates</label>
					<ul url="' . $templates_get_sub_files_url . '"></ul>
				</li>
			</ul>
		</div>
	</div>
	
	<div class="myfancypopup with_title create_file_popup">
		<div class="title">Create new</div>
		
		<div class="create_file_popup_content">
			<div class="type">
				<label>Type: </label>
				<select>
					<option value="page">Page</option>
					<option value="folder">Folder</option>
				</select>
			</div>
			
			<div class="name">
				<label>Name: </label>
				<input />
			</div>
			
			<button>Create</button>
		</div>
	</div>'; } else $main_content .= '<div class="no_project">The project "' . $filter_by_layout . '" doesn\'t exists anymore...</div>'; $main_content .= '
</div>'; ?>
