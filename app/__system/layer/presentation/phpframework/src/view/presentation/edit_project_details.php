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

include_once get_lib("org.phpframework.util.web.html.HtmlFormHandler"); include_once $EVC->getUtilPath("AdminMenuUIHandler"); include_once $EVC->getUtilPath("WorkFlowPresentationHandler"); $manage_project_url = $project_url_prefix . "phpframework/presentation/manage_file?bean_name=#bean_name#&bean_file_name=#bean_file_name#&action=#action#&item_type=presentation&extra=#extra#&path=#path#&folder_type=project"; $choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "phpframework/admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#"; $get_file_properties_url = $project_url_prefix . "phpframework/admin/get_file_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&class_name=#class_name#&type=#type#"; $manage_layout_type_permissions_url = $project_url_prefix . "phpframework/user/manage_layout_type_permissions?layout_type_id=$project_layout_type_id"; $head = AdminMenuUIHandler::getHeader($project_url_prefix, $project_common_url_prefix); $head .= '
<!-- Add PHP CODE CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/edit_php_code.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/edit_php_code.js"></script>

<!-- Add ADMIN MENU JS -->
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/admin_menu.js"></script>

<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/edit_project_details.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/edit_project_details.js"></script>
'; $head .= '<script>'; $head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url); $head .= '
var manage_project_url = \'' . $manage_project_url . '\';
var is_popup = ' . ($popup ? "true" : "false") . ';
</script>'; if (!$is_existent_project) $head .= '
	<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/db_driver_connection_props.js"></script>
	<script>
		var drivers_encodings = ' . json_encode($drivers_encodings) . ';
		var drivers_extensions = ' . json_encode($drivers_extensions) . ';
		var drivers_ignore_connection_options = ' . json_encode($drivers_ignore_connection_options) . ';
		var drivers_ignore_connection_options_by_extension = ' . json_encode($drivers_ignore_connection_options_by_extension) . ';
	</script>'; $main_content = ''; if ($_POST) { if (!$status) { $error_message = ($extra_message ? $extra_message . "<br/>" : "") . ($error_message ? $error_message : "There was an error trying to " . ($is_rename_project ? "rename" : "create") . " project. Please try again..."); } else { $status_message = ($extra_message ? $extra_message . "<br/>" : "") . "Project successfully " . ($is_rename_project ? "renamed" : "created") . "!"; $on_success_js_func_opts = null; if ($on_success_js_func) { $file_by_layout_prefix = WorkFlowBeansFileHandler::getLayerObjFolderName( $PEVC->getPresentationLayer() ); $old_filter_by_layout = "$file_by_layout_prefix/" . (trim($_POST["old_project_folder"]) ? trim($_POST["old_project_folder"]) . "/" : "") . trim($_POST["old_name"]); $old_filter_by_layout = preg_replace("/[\/]+/", "/", $old_filter_by_layout); $old_filter_by_layout = preg_replace("/[\/]+$/", "", $old_filter_by_layout); $new_filter_by_layout = "$file_by_layout_prefix/$path"; $on_success_js_func_opts = array( "is_rename_project" => $is_rename_project, "layer_bean_folder_name" => $file_by_layout_prefix, "old_filter_by_layout" => $old_filter_by_layout, "new_filter_by_layout" => $new_filter_by_layout, ); } $on_success_js_func = $on_success_js_func ? $on_success_js_func : "refreshLastNodeParentChilds"; $on_success_js_func_opts = $on_success_js_func_opts ? json_encode($on_success_js_func_opts) : ""; $main_content .= "
		<script>
			if (typeof window.parent.$on_success_js_func == 'function') 
				window.parent.$on_success_js_func($on_success_js_func_opts);
			else if (typeof window.parent.parent.$on_success_js_func == 'function') //could be inside of the admin_home_project.php which is inside of the admin_advanced.php
				window.parent.parent.$on_success_js_func($on_success_js_func_opts);
		</script>"; } } $main_content .= '
<div class="top_bar' . ($popup ? " in_popup" : "") . '">
	<header>
		<div class="title">' . ($is_existent_project ? 'Edit' : 'Create') . ' Project</div>
		<ul>
			<li class="save" data-title="Save Project"><a onclick="submitForm(this)"><i class="icon save"></i> Save Project</a>
		</ul>
	</header>
</div>
<div class="edit_project_details' . (count($layers_projects) == 1 ? ' single_presentation_layer' : '') . ($is_existent_project ? ' existent_project' : '') . '">'; $main_content .= '
	<div id="choose_project_folder_url_from_file_manager" class="myfancypopup choose_from_file_manager">
		<div class="broker">
			<label>Broker:</label>
			<select onChange="updateLayerUrlFileManager(this)">'; $t = count($presentation_brokers); for ($i = 0; $i < $t; $i++) { $b = $presentation_brokers[$i]; $main_content .= '<option bean_file_name="' . $b[1] . '" bean_name="' . $b[2] . '" value="' . $b[0] . '"' . ($bn == $bean_name && $bean_file_name == $layer_props["bean_file_name"] ? " selected" : "") . '>' . $b[0] . '</option>'; } $main_content .= '
			</select>
		</div>
		<ul class="mytree">
			<li>
				<label>Root</label>
				<ul layer_url="' . $choose_bean_layer_files_from_file_manager_url . '"></ul>
			</li>
		</ul>
		<div class="button">
			<input type="button" value="Update" onClick="MyFancyPopup.settings.updateFunction(this)" />
		</div>
	</div>
	
	<form method="post" enctype="multipart/form-data" onSubmit="return addProject(this);" project_created="' . ($is_existent_project ? 1 : 0) . '">
		<input type="hidden" name="is_existent_project" value="' . ($is_existent_project ? 1 : 0) . '" />
		
		<div class="left_content">
			' . ($project_image ? '<img src="' . $project_image . '" alt="No Image" onClick="$(this).parent().children(\'input[type=file]\').trigger(\'click\')" />' : '<div class="no_logo" onClick="$(this).parent().children(\'input[type=file]\').trigger(\'click\')"></div>') . '
			
			<label>Change logo:</label>
			<input type="file" name="image" />'; if ($db_brokers_exist) { if (!$is_existent_project) { $main_content .= '
			<div class="project_db_driver" title="If you wish this project to access a DB, please activate this option and fill the DB details below...">
				<label>Want to assign a default DB?</label>
				<select name="project_db_driver" onChange="onChangeProjectWithDB(this)">
					<option value="0" title="Allow this project to connect with all the DB defined">-- default --</option>
					<option value="1"' . (is_numeric($project_db_driver) ? ' checked' : '') . '>New DB - User Defined</option>
					<option value="" disabled></option>'; if ($db_drivers_names) { $main_content .= '<optgroup label="Existent DBs">'; foreach ($db_drivers_names as $db_driver_name) $main_content .= '<option value="' . $db_driver_name . '">' . $db_driver_name . '</option>'; $main_content .= '</optgroup>'; } $main_content .= '
				</select>
			</div>'; } else { $main_content .= '
			<div class="project_db_driver" title="If you wish to manage the DBs that this project has access to, please click in the link below...">
				<label>Do you wish to assign different DBs?</label>
				<a href="javascript:void(0);" onClick="goToManageLayoutTypePermissions(this)" url="' . $manage_layout_type_permissions_url . '">Manage this project DBs</a>
			</div>'; } } $main_content .= '
		</div>
		<div class="right_content">
			<div class="name" title="Please write your new project\'s folder name">
				<label>Name your project:</label>
				<input type="hidden" name="old_name" value="' . $old_project . '" />
				<input name="name" placeHolder="Type a name" value="' . $project . '" required />
			</div>
			<div class="description">
				<label>Description:</label>
				<textarea name="description" placeHolder="Type some description">' . $project_description . '</textarea>
			</div>'; if ($layers_projects) { $main_content .= '
			<div class="layer">
				<label>Presentation Layer:</label>
				<select onChange="updateLayerFileManagers(this)">'; foreach ($layers_projects as $bn => $layer_props) { $main_content .= '
					<option bean_name="' . $bn . '" bean_file_name="' . $layer_props["bean_file_name"] . '" layer_bean_folder_name="' . $layer_props["layer_bean_folder_name"] . '"' . ($bn == $bean_name && $bean_file_name == $layer_props["bean_file_name"] ? " selected" : "") . '>' . $layer_props["item_label"] . '</option>'; } $main_content .= '
				</select>
			</div>'; } $main_content .= '
			<div class="project_folder" title="Create your project inside of an existent or new folder...">
				<label>Want to assign this project to a folder?</label>
				<input type="hidden" name="old_project_folder" value="' . $old_project_folder . '" />
				<input name="project_folder" placeHolder="Type folder name" value="' . $project_folder . '" autocomplete="false" />
				<span class="icon search" onClick="onChooseProjectFolder(this)"></span>
			</div>
		</div>'; if (!$is_existent_project) { $form_elements_settings = array( 0 => array( "field" => array( "class" => "form_field setup_input form_field_db db_type", "label" => array( "value" => "DataBase Type: ", ), "input" => array( "type" => "select", "name" => "db_details[type]", "value" => "#type#", "options" => $available_db_types, "extra_attributes" => array( array("name" => "onChange", "value" => "onChangeDBType(this)") ), ) ) ), 1 => array( "field" => array( "class" => "form_field setup_input form_field_db db_extension", "label" => array( "value" => "Connection Type: ", ), "input" => array( "type" => "select", "name" => "db_details[extension]", "value" => "#extension#", "options" => $available_extensions_options, "extra_attributes" => array( array("name" => "onChange", "value" => "onChangeDBExtension(this)") ), ) ) ), 2 => array( "field" => array( "class" => "form_field setup_input form_field_db db_host", "label" => array( "value" => "Host: ", ), "input" => array( "type" => "text", "name" => "db_details[host]", "value" => "#host#", ) ) ), 3 => array( "field" => array( "class" => "form_field setup_input form_field_db db_name", "label" => array( "value" => "DataBase name: ", ), "input" => array( "type" => "text", "name" => "db_details[db_name]", "value" => "#db_name#", ) ) ), 4 => array( "field" => array( "class" => "form_field setup_input form_field_db db_username", "label" => array( "value" => "Username: ", ), "input" => array( "type" => "text", "name" => "db_details[username]", "value" => "#username#", "extra_attributes" => array( array("name" => "autocomplete", "value" => "false") ), ) ) ), 5 => array( "field" => array( "class" => "form_field setup_input form_field_db db_password", "label" => array( "value" => "Password: ", ), "input" => array( "type" => "password", "name" => "db_details[password]", "value" => "#password#", "next_html" => '<span class="icon switch toggle_password" onclick="toggleDBPasswordField(this)"></span>' . ($db_settings_variables["password"] ? '<span>...with the global value: "***"</span>' : ''), "extra_attributes" => array( array("name" => "autocomplete", "value" => "false") ), ) ) ), 6 => array( "field" => array( "class" => "form_field form_field_db show_advanced_db_options", "input" => array( "type" => "label", "value" => '<a href="javascript:void(0);" onClick="toggleDBAdvancedFields(this)">Show Advanced Options</a>', ) ) ), 7 => array( "field" => array( "class" => "form_field setup_input form_field_db form_field_db_advanced db_port", "label" => array( "value" => "Port: ", ), "input" => array( "type" => "text", "name" => "db_details[port]", "value" => "#port#", ) ) ), 8 => array( "field" => array( "class" => "form_field setup_input form_field_db form_field_db_advanced db_persistent", "label" => array( "value" => "Persistent: ", ), "input" => array( "type" => "checkbox", "name" => "db_details[persistent]", "value" => "#persistent#", ) ) ), 9 => array( "field" => array( "class" => "form_field setup_input form_field_db form_field_db_advanced db_new_link", "label" => array( "value" => "New Link: ", ), "input" => array( "type" => "checkbox", "name" => "db_details[new_link]", "value" => "#new_link#", ) ) ), 10 => array( "field" => array( "class" => "form_field setup_input form_field_db form_field_db_advanced db_encoding", "label" => array( "value" => "Encoding: ", ), "input" => array( "type" => "select", "name" => "db_details[encoding]", "value" => "#encoding#", "options" => $available_encodings_options ) ) ), 11 => array( "field" => array( "class" => "form_field setup_input form_field_db form_field_db_advanced db_schema", "label" => array( "value" => "Schema: ", ), "input" => array( "type" => "text", "name" => "db_details[schema]", "value" => "#schema#", ) ) ), 12 => array( "field" => array( "class" => "form_field setup_input form_field_db form_field_db_advanced db_odbc_data_source", "label" => array( "value" => "ODBC Data Source: ", ), "input" => array( "type" => "text", "name" => "db_details[odbc_data_source]", "value" => "#odbc_data_source#", "title" => "A Data Source Name (DSN) is the logical name that is used by Open Database Connectivity (ODBC) to refer to the driver and other information that is required to access data from a data source. Data sources are usually defined in /etc/odbc.ini", ) ) ), 13 => array( "field" => array( "class" => "form_field setup_input form_field_db form_field_db_advanced db_odbc_driver", "label" => array( "value" => "ODBC Driver: ", ), "input" => array( "type" => "text", "name" => "db_details[odbc_driver]", "value" => "#odbc_driver#", "title" => "Is the file path of the installed driver that connects to a data-base from ODBC protocol. Or the name of an ODBC instance that was defined in /etc/odbcinst.ini", ) ) ), 14 => array( "field" => array( "class" => "form_field setup_input form_field_db form_field_db_advanced db_extra_dsn", "label" => array( "value" => "Extra DSN: ", ), "input" => array( "type" => "text", "name" => "db_details[extra_dsn]", "value" => "#extra_dsn#", "title" => "Other DSN attributes. Each attribute must be splitted by comma.", ) ) ) ); $HtmlFormHandler = new HtmlFormHandler(); $main_content .= '
		<div class="db_details" title="DB Details to assign to this project"' . (is_numeric($project_db_driver) ? '' : ' style="display:none;"') . '>
			<div class="form_fields">
				' . $HtmlFormHandler->createElements($form_elements_settings, $db_details) . '
			</div>
		</div>'; } $main_content .= '
		<div class="buttons">
			<input type="submit" name="save" value="Save Project" />
		</div>
	</form>
</div>'; ?>
