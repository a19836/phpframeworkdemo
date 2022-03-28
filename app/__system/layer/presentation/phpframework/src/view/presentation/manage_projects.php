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

$head = '
<!-- Add FileManager JS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/file_manager.css" type="text/css" charset="utf-8" />

<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icon CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/manage_projects.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/manage_projects.js"></script>
'; $main_content = '
<div class="top_bar">
	<header>
		<div class="title">
			Manage Projects in the presentation layer: 
			<select class="layer" onChange="showProjectsLayer(this)">'; if (is_array($files)) foreach ($files as $bn => $layer_props) { $main_content .= '<option' . ($bn == $bean_name && $layer_props["bean_file_name"] == $bean_file_name ? " selected" : "") . ' bean_name="' . $bn . '" bean_file_name="' . $layer_props["bean_file_name"] . '">' . $layer_props["item_label"] . '</option>'; } $main_content .= '		
			</select>
			<span class="info">' . ($default_layer == $bean_folder ? 'This is the default layer' : 'This is NOT the default layer') . '</span>
		</div>
		<ul>
			<li class="save" title="Save"><a onClick="submitForm(this)"><i class="icon save"></i> Save</a>
		</ul>
	</header>
</div>
<div class="projects_list">'; $exists = false; if (is_array($files)) foreach ($files as $bn => $layer_props) if ($bn == $bean_name && $layer_props["bean_file_name"] == $bean_file_name) { $exists = true; $projects = $layer_props["projects"]; $add_url = $project_url_prefix . "phpframework/presentation/manage_file?bean_name=$bean_name&bean_file_name=$bean_file_name&action=create_folder&item_type=presentation&extra=#extra#&path=&folder_type=project"; $remove_url = $project_url_prefix . "phpframework/presentation/manage_file?bean_name=$bean_name&bean_file_name=$bean_file_name&action=remove&item_type=presentation&path=#path#"; $edit_global_vars_url = $project_url_prefix . "phpframework/presentation/edit_project_global_variables?bean_name=$bean_name&bean_file_name=$bean_file_name&path=#project#src/config/pre_init_config.php"; $edit_config_url = $project_url_prefix . "phpframework/presentation/edit_config?bean_name=$bean_name&bean_file_name=$bean_file_name&path=#project#src/config/config.php"; $edit_init_url = $project_url_prefix . "phpframework/presentation/edit_init?bean_name=$bean_name&bean_file_name=$bean_file_name&item_type=presentation&path=#project#src/config/init.php"; $manage_references_url = $project_url_prefix . "phpframework/presentation/manage_references?bean_name=$bean_name&bean_file_name=$bean_file_name&path=#project#"; $view_project_url = $project_url_prefix . "phpframework/presentation/view_project?bean_name=$bean_name&bean_file_name=$bean_file_name&path=#project#"; $main_content .= '		
			<div class="layer_projects">	
			<table>
				<tr>
					<th class="table_header project">Project</th>
					<th class="table_header path">Path</th>
					<th class="table_header buttons">
						<span class="icon add" onClick="addProject(this, \'' . $add_url . '\');" title="Click here to add a new project">Add</span>
					</th>
				</tr>'; $projects_options_html = ""; $default_project_exists = false; if (is_array($projects)) { foreach ($projects as $project_name => $project_props) { if ($default_project == $project_name) $default_project_exists = true; $projects_options_html .= '<option' . ($default_project == $project_name ? " selected" : "") . '>' . $project_name . '</option>'; $main_content .= '
					<tr>
						<td class="project">' . $project_name . '</td>
						<td class="path">' . $project_props["element_type_path"] . '</td>
						<td class="buttons">'; if ($project_props["item_type"] != "project_common") { $main_content .= '<span class="icon global_vars" onClick="goTo(this, \'' . str_replace("#project#", $project_props["element_type_path"], $edit_global_vars_url) . '\');" title="Click here to edit the project global variables"></span>
							<span class="icon settings" onClick="goTo(this, \'' . str_replace("#project#", $project_props["element_type_path"], $edit_config_url) . '\');" title="Click here to edit the project config file"></span>
							<span class="icon edit" onClick="goTo(this, \'' . str_replace("#project#", $project_props["element_type_path"], $edit_init_url) . '\');" title="Click here to edit the project init file"></span>
							<span class="icon manage_references" onClick="goTo(this, \'' . str_replace("#project#", $project_props["element_type_path"], $manage_references_url) . '\');" title="Click here to manage the references for this project"></span>
							<span class="icon view" onClick="openWindow(this, \'' . str_replace("#project#", $project_props["element_type_path"], $view_project_url) . '\', \'project\');" title="Click here to view project"></span>
							<span class="icon delete" onClick="deleteProject(this, \'' . str_replace("#path#", $project_props["element_type_path"], $remove_url) . '\')" title="Click here to delete this project permanently"></span>'; } $main_content .= '</td>
					</tr>'; } } if ($default_project && !$default_project_exists) $projects_options_html .= '<option value="' . $default_project . '" selected>' . $default_project . ' - PROJECT DOES NOT EXIST!</option>'; $main_content .= '</table>
			</div>'; $main_content .= '
			<form method="post">
				<div class="default_project">
					<label>Layer Default Project: </label>
					<select name="default_project">' . $projects_options_html . '</select>
				</div>
			</form>'; if ($save_message) $main_content .= '<script>
					$(function () {
						StatusMessageHandler.showMessage(\'' . $save_message . '\');
					});
				</script>'; } if (!$files) $main_content .= '<div class="error">No available layers</div>'; else if (!$exists) $main_content .= '<div class="error">No projects for this layer</div>'; $main_content .= '</div>'; ?>
