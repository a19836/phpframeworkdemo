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
<!-- Add Icons CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/install_program.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/install_program.js"></script>

<script>
var modules_admin_panel_url = \'' . $project_url_prefix . 'phpframework/admin/manage_modules\';
</script>'; $main_content = '<div class="install_program">
	<div class="title">Program Installation</div>'; if ($step >= 3) { $main_content .= '<div class="step_3">'; if ($errors) { $main_content .= '<label class="error">There were some erros installing this program, this is:</label>
		<ul class="errors_list">'; $files = $errors["files"]; unset($errors["files"]); foreach($errors as $k => $v) if (is_string($v)) $main_content .= '<li>' . $v . '</li>'; if ($files) { $main_content .= '<li>The following files could not be copied:</li>
			<ul>'; foreach($files as $src_path => $dst_path) $main_content .= '<li>' . (is_numeric($src_path) ? "" : $src_path . ' => ') . $dst_path . '</li>'; $main_content .= '</ul>'; } $main_content .= '</ul>'; } else if ($error_message) { $main_content .= '<label class="error">' . $error_message . '</label>'; } else if ($next_step_html) { $main_content .= '
		<form method="post" enctype="multipart/form-data">
			<input type="hidden" name="step" value="' . $next_step . '" />
			<textarea class="hidden" name="post_data">' . json_encode($post_data) . '</textarea>
			
			' . $next_step_html . '
			
			<div class="buttons">
				<input type="submit" name="continue" value="continue" onClick="$(this).hide();">
			</div>
		</form>'; } else { $main_content .= '<label class="ok">' . $status_message . '</label>
		
		<div class="users_permissions">
			<label>Users Permissions Settings</label>
			<span class="info">
				In case this program have authenticated pages and you wish to add some users permissions, please follow the steps bellow:
				<ol>
					<li>Open the "<a href="javascript:void(0)" onclick="openUsersManagementAdminPanelPopup(this)">Manage Modules Admin Panel</a>"</li>
					<li>Expand the "User Module" by clicking in the "expand" button <img src="' . $project_common_url_prefix . 'img/icon/maximize_icon.png" /></li>
					<li>Then click in the "settings" button <img src="' . $project_common_url_prefix . 'img/icon/settings_icon.png" /> at the right side of any sub-module of the "User Module"</li>
					<li>The "User Module Admin Panel" will be opened...</li>
					<li>Then choose the correspondent project and edit the users permissions...</li>
				</ol>
			</span>
			
			<div class="users_management_admin_panel_popup myfancypopup">
				<iframe></iframe>
			</div>
		</div>'; } if ($messages) $main_content .= '<label class="error">Important messages:</label><ul class="messages_list"><li>' . implode("</li><li>", $messages) . '</li></ul>'; $main_content .= '</div>'; } else if ($step == 2) { $main_content .= '<div class="step_2">'; if ($db_drivers) $main_content .= '<div class="db_drivers"><label>The DBs where the program will be installed: </label>' . implode(", ", $db_drivers) . '</div>'; $main_content .= '<div class="layers">
		<label>The files from the uploaded file will be copied to the following folders:</label>
		<ul>'; if ($layers) { foreach ($layers as $layer_type => $items) { $layer_label = ""; switch ($layer_type) { case "ibatis": $layer_label = "Data Access - Ibatis Layers"; break; case "hibernate": $layer_label = "Data Access - Hibernate Layers"; break; case "businesslogic": $layer_label = "Business Logic Layers"; break; case "presentation": $layer_label = "Presentation Layers"; break; case "vendor": $layer_label = "Vendors"; break; } $main_content .= '<li>' . $layer_label . ':
			<ul>'; foreach ($items as $broker_name => $layer_props) { $layer_files = $all_files[$broker_name]; if ($layer_type == "vendor" && !is_array($layer_files)) { $file_exists = $layer_files; $extra = $file_exists ? ($overwrite ? " (Already exists and will be replaced!)" : " (Already exists and will be backed-up!)") : ""; $main_content .= '<li class="' . ($file_exists ? 'file_exists' : 'file_ok') . '">' . $broker_name . $extra . '</li>'; } else if (is_array($layer_files) && $layer_type == "presentation") { $main_content .= '<li class="broker"><label>' . ucwords($broker_name) . ':</label>
					<ul>'; foreach ($layer_files as $project => $project_files) { $main_content .= '<li class="project"><label>' . ucwords($project) . ':</label>
						<table>'; foreach ($project_files as $file_path => $file_exists) { $is_config = strpos($file_path, "config/") === 0; $extra = $file_exists ? ($overwrite && !$is_config ? "(Already exists and will be replaced!)" : "(Already exists and will be " . ($is_config ? "merged" : "backed-up") . "!)") : ""; $main_content .= '<tr class="' . ($file_exists ? 'file_exists' : 'file_ok') . '"><td>' . $file_path . '</td><td>' . ($file_exists ? "EXISTS" : "") . '</td><td>' . $extra . '</td></tr>'; } $main_content .= '</table></li>'; } $main_content .= '</ul></li>'; } else if (is_array($layer_files)) { $main_content .= '<li class="broker"><label>' . ucwords($broker_name) . ':</label>
					<table>'; foreach ($layer_files as $file_path => $file_exists) { $extra = $file_exists ? ($overwrite ? "(Already exists and will be replaced!)" : "(Already exists and will be backed-up!)") : ""; $main_content .= '<tr class="' . ($file_exists ? 'file_exists' : 'file_ok') . '"><td>' . $file_path . '</td><td>' . ($file_exists ? "EXISTS" : "") . '</td><td>' . $extra . '</td></tr>'; } $main_content .= '</table></li>'; } } $main_content .= '</ul></li>'; } } else $main_content .= '<li>No layers selected to copy files...</li>'; $main_content .= '
			</ul>
		</div>
		
		<form method="post" enctype="multipart/form-data">
			<input type="hidden" name="step" value="3" />
			<textarea class="hidden" name="post_data">' . json_encode($_POST) . '</textarea>
			<input type="submit" name="continue" value="continue" onClick="$(this).hide();">
		</form>
	</div>'; } else if ($step == 1) { $main_content .= '
	<div class="step_1">'; if ($info && $info["description"]) $main_content .= '<div class="info"><pre>' . ($info["label"] ? $info["label"] . " - " : "") . str_replace("\n", "<br/>", $info["description"]) . '</pre></div>'; $main_content .= '
		<form method="post" enctype="multipart/form-data">
			<input type="hidden" name="step" value="2" />
			<input type="hidden" name="program_name" value="' . $program_name . '" />
			'; if ($brokers_db_drivers) { $main_content .= '
			<div class="db_drivers">
				<label>Please choose the DBs where you wish to install your program:</label>
				<ul>'; $first_item_checked = count($brokers_db_drivers) != 1; foreach ($brokers_db_drivers as $bl) { $checked = false; if ($P) $checked = $default_db_driver && $default_db_driver == $bl; else if (!$first_item_checked && $project_name != $EVC->getCommonProjectName()) { $first_item_checked = true; $checked = true; } $main_content .= '<li><input type="checkbox" name="db_drivers[]" value="' . $bl . '"' . ($checked ? ' checked' : '') . '/> ' . ucwords($bl) . '</li>'; } $main_content .= '</ul>
			</div>'; } $main_content .= '
			<div class="layers">
				<label>Please choose the Layers where you wish to install your program:</label>
				<ul>'; if ($ibatis_brokers) { $main_content .= '<li>Data Access - Ibatis Layers:
		<ul>'; foreach ($ibatis_brokers as $bl) $main_content .= '<li><input type="checkbox" name="layers[ibatis][' . $bl[0] . '][active]" value="1" checked/> ' . ucwords($bl[0]) . '</li>'; $main_content .= '</ul></li>'; } if ($hibernate_brokers) { $main_content .= '<li>Data Access - Hibernate Layers:
		<ul>'; foreach ($hibernate_brokers as $bl) $main_content .= '<li><input type="checkbox" name="layers[hibernate][' . $bl[0] . '][active]" value="1" checked/> ' . ucwords($bl[0]) . '</li>'; $main_content .= '</ul></li>'; } if ($business_logic_brokers) { $main_content .= '<li>Business Logic Layers:
		<ul>'; foreach ($business_logic_brokers as $bl) $main_content .= '<li><input type="checkbox" name="layers[businesslogic][' . $bl[0] . '][active]" value="1" checked/> ' . ucwords($bl[0]) . '</li>'; $main_content .= '</ul></li>'; } if ($presentation_brokers) { $main_content .= '<li>Presentation Layers:
		<ul>'; foreach ($presentation_brokers as $bl) { $projects = $presentation_projects[ $bl[2] ]["projects"]; $main_content .= '<li>' . ucwords($bl[0]) . ':
			<ul>'; $first_item_checked = count($projects) != 2; if ($projects) foreach ($projects as $project_name => $project_props) { $checked = false; if ($P) $checked = $selected_project_id && $selected_project_id == $project_name; else if (!$first_item_checked && $project_name != $EVC->getCommonProjectName()) { $first_item_checked = true; $checked = true; } $main_content .= '<li><input type="checkbox" name="layers[presentation][' . $bl[0] . '][' . $project_name . '][active]" value="1"' . ($checked ? ' checked' : '') . '/> ' . ucwords($project_name) . '</li>'; } $main_content .= '</ul>
			</li>'; } $main_content .= '</ul></li>'; } if ($vendor_brokers) { $main_content .= '<li>Vendor Files:
		<ul>'; foreach ($vendor_brokers as $bl) $main_content .= '<li><input type="checkbox" name="layers[vendor][' . $bl . '][active]" value="1" checked/> ' . $bl . '</li>'; $main_content .= '</ul></li>'; } $main_content .= '
				</ul>
			</div>
			
			<div class="overwrite">
				<input type="checkbox" name="overwrite" value="1" checked/> Please check this box to overwrite the existent files...
			</div>
			
			' . ($program_settings ? '<div class="program_settings"><label>Other Program Settings:</label>' . $program_settings . '</div>' : '') . '
			
			<div class="buttons">
				<input type="submit" name="continue" value="continue" onClick="$(this).hide();">
			</div>
		</form>
	</div>'; } else { $main_content .= '
	<div class="step_0">
		<script>
			var get_store_programs_url = "' . $get_store_programs_url . '";
		</script>
		<form method="post" enctype="multipart/form-data">
			<input type="hidden" name="step" value="1" />
			
			<label>Please upload your program zip file:</label>
			<input class="upload_file" type="file" name="program_file" />
			
			<input class="button" type="submit" name="upload" value="upload" onClick="$(this).hide();">
		</form>
	
		' . ($get_store_programs_url ? '<div class="install_store_program">To install programs from store please click <a href="javascript:void(0)" onClick="installStoreProgramPopup();">here</a></div>' : '') . '
		' . ($programs_download_page_url ? '<div class="go_to_programs_download_page">To download more programs please click <a href="' . $programs_download_page_url . '" target="download_programs">here</a></div>' : '') . '
		
		<div class="warning">
			Note that in case of have Layers remotely installed, this is, Layers that are not locally installed and are remotely accessable, and if you wish to access this program from these Layers, you must then, install this program individually in that Layers too...
		</div>
	</div>'; } $main_content .= '</div>'; ?>
