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
<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icons CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS and JS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/layout.js"></script>

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/install_module.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/install_module.js"></script>

<script>
	var get_store_modules_url = \'' . $project_url_prefix . "phpframework/admin/get_store_type_content?type=modules" . '\';
</script>
'; $main_content = '
<div class="top_bar' . ($popup ? ' in_popup' : '') . '">
	<header>
		<div class="title">Install New Module</div>
		<ul>
			<li class="info" data-title="Info"><a onclick="$(\'.install_module\').toggleClass(\'show_info\');"><i class="icon info"></i> Info</a></li>
			<li class="install" data-title="Install Module Now"><a onclick="onSubmitButtonClick(this);"><i class="icon continue"></i> Install Module Now</a></li>
			<li class="sub_menu" onclick="openSubmenu(this)">
				<i class="icon sub_menu"></i>
				<ul>
					<li class="info" title="Info"><a onclick="$(\'.install_module\').toggleClass(\'show_info\');"><i class="icon info"></i> Info</a></li>
					<li class="modules_list" title="List Installed Modules"><a href="' . $project_url_prefix . 'phpframework/admin/manage_modules?filter_by_layout=' . $filter_by_layout . ($popup ? "&popup=$popup" : "") . '"><i class="icon go_up"></i> List Installed Modules</a></li>
				</ul>
			</li>
		</ul>
	</header>
</div>'; if ($_POST) { if ($messages) { $messages_html = '<div class="messages">
		<span class="icon close" onClick="$(this).parent().hide()" title="Close messages"></span>
		<ul>'; $curr_module = null; foreach ($messages as $module_id => $module_projects) { if ($curr_module && $curr_module != $module_id) $messages_html .= '<li class="space"></li>'; $messages_html .= '<li class="module">' . ucwords($module_id) . ' Module\'s installation</li>'; foreach ($module_projects as $project_name => $msgs) if ($msgs) { $messages_html .= '<li class="project"><label>' . ucfirst($project_name) . ' project\'s installation:</label><ul>'; foreach ($msgs as $msg) $messages_html .= '<li class="' . $msg["type"] . '">' . str_replace("\n", "<br/>", trim($msg["msg"])) . '</li>'; $messages_html .= '</ul></li>'; } $curr_module = $module_id; } $messages_html .= '</ul>
		</div>'; } if (!$status) { $error_message = $error_message ? $error_message : "There was an error trying to install modules. Please try again..."; $main_content .= $messages_html; } else if ($messages_html) { $main_content .= $messages_html . "<script>
			alert('Please do NOT forget to activate this module and go to the \"Manage User Type Permissions\" page and add the new permissions to the correspondent files for this module, otherwise the module may NOT work propertly!');
		</script>"; } else { die("<script>
			if (window.parent.refreshAndShowLastNodeChilds) 
				window.parent.refreshAndShowLastNodeChilds();
			
			alert('Please do NOT forget to activate this module and go to the \"Manage User Type Permissions\" page and add the new permissions to the correspondent files for this module, otherwise the module may NOT work propertly!');
			
			document.location = '" . $project_url_prefix . "phpframework/admin/manage_modules?bean_name=$bean_name&bean_file_name=$bean_file_name&filter_by_layout=$filter_by_layout" . ($popup ? "&popup=$popup" : "") . "';
		</script>"); } } $main_content .= '<div class="install_module">
	<div class="project">
		<select onChange="onChangeProject(this)">
			<option value="">-- All Projects\' DBS --</option>
			<option disabled></option>'; if ($projects) { $previous_folder = null; foreach ($projects as $project_name => $project) if ($project["item_type"] != "project_common") { $project_folder = dirname($project_name); $project_folder = $project_folder == "." ? "" : $project_folder; if ($project_folder && $project_folder != $previous_folder) { $main_content .= '<option disabled>' . str_repeat("&nbsp;&nbsp;&nbsp;", substr_count($project_folder, '/')) . basename($project_folder) . '</option>'; $previous_folder = $project_folder; } $main_content .= '<option' . ($selected_project == $project_name ? ' selected' : '') . ' value="' . $project_name . '">' . str_repeat("&nbsp;&nbsp;&nbsp;", substr_count($project_name, '/')) . ucwords(basename($project_name)) . '\' DBs</option>'; } } $main_content .= '
		</select>
	</div>
	
	<div class="db_driver">
		<select onChange="onChangeDBDriver(this)">
			<option value="">-- All DB Drivers --</option>
			<option disabled></option>'; if ($available_db_drivers) { foreach ($available_db_drivers as $db_driver_name => $db_driver_props) $main_content .= '<option' . ($selected_db_driver == $db_driver_name ? ' selected' : '') . ' value="' . $db_driver_name . '">' . ucwords($db_driver_name) . ' Driver</option>'; } $main_content .= '
		</select>
	</div>
	
	<ul>
		' . ($get_store_modules_url ? '<li><a href="#store">Store Modules</a></li>' : '') . '
		<li><a href="#local">Upload Local Module</a></li>
	</ul>
	
	<div id="local" class="file_upload">
		<div class="title">Install a local module from your computer (.zip file)</div>
		
		<form method="post" enctype="multipart/form-data">
			<input type="hidden" name="project" value="" />
			<input type="hidden" name="db_driver" value="" />
			
			<div class="add_new_file">Add new file to upload <span class="icon add" onClick="addNewFile(this)" title="Add new File">Add</span></div>
			<div class="upload_file">
				<input type="file" name="zip_file[]" multiple>
			</div>
		</form>
	
		' . ($modules_download_page_url ? '<div class="go_to_modules_download_page">To download modules to your local computer, please click <a href="' . $modules_download_page_url . '" target="download_modules">here</a></div>' : '') . '
	
		<div class="go_back_to_modules_list">To go back to the modules list please click <a href="' . $project_url_prefix . 'phpframework/admin/manage_modules?bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&filter_by_layout=' . $filter_by_layout . ($popup ? "&popup=$popup" : "") . '&time=' . time() . '">here</a>.</div>
	</div>'; if ($get_store_modules_url) $main_content .= '
	<div id="store" class="install_store_module">
		<div class="title">Install a module from our store</div>
		<div class="search_module">
			<i class="icon search active"></i>
			<input placeHolder="Search" onKeyUp="searchModules(this)" />
			<i class="icon close" onClick="resetSearchModules(this)"></i>
		</div>
		<ul>
			<li class="loading">Loading modules from store...</li>
		</ul>
	</div>'; $main_content .= '	
	<div class="info">
		<div>Why do you need to choose a project?<br>
		Because the projects can change the global variables which may influence the layers structure or the db drivers configurations. So if the layers structure or the db drivers are dependent of any global variable, you should choose the correspondent project accordingly.</div>
	
		<div class="warning">
			Note that in case of have Layers remotely installed, this is, Layers that are not locally installed and are remotely accessable, and if you wish to access this module from these Layers, you must then, install this module individually in that Layers too...
		</div>
	</div>
</div>'; ?>
