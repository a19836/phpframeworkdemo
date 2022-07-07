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

include_once $EVC->getUtilPath("AdminMenuUIHandler"); include_once $EVC->getUtilPath("WorkFlowPresentationHandler"); $manage_project_url = $project_url_prefix . "phpframework/presentation/manage_file?bean_name=#bean_name#&bean_file_name=#bean_file_name#&action=#action#&item_type=presentation&extra=#extra#&path=#path#&folder_type=project"; $choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#"; $get_file_properties_url = $project_url_prefix . "phpframework/admin/get_file_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&class_name=#class_name#&type=#type#"; $head = AdminMenuUIHandler::getHeader($project_url_prefix, $project_common_url_prefix); $head .= '
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
</script>'; $main_content = ''; if ($_POST) { if (!$status) { $error_message = $error_message ? $error_message : "There was an error trying to " . ($is_rename_project ? "rename" : "create") . " project. Please try again..."; } else { $status_message = "Project successfully " . ($is_rename_project ? "renamed" : "created") . "!"; $on_success_js_func_opts = null; if ($on_success_js_func) { $file_by_layout_prefix = WorkFlowBeansFileHandler::getLayerObjFolderName( $PEVC->getPresentationLayer() ); $old_filter_by_layout = "$file_by_layout_prefix/" . (trim($_POST["old_project_folder"]) ? trim($_POST["old_project_folder"]) . "/" : "") . trim($_POST["old_name"]); $old_filter_by_layout = preg_replace("/[\/]+/", "/", $old_filter_by_layout); $old_filter_by_layout = preg_replace("/[\/]+$/", "", $old_filter_by_layout); $new_filter_by_layout = "$file_by_layout_prefix/$path"; $on_success_js_func_opts = array( "is_rename_project" => $is_rename_project, "layer_bean_folder_name" => $file_by_layout_prefix, "old_filter_by_layout" => $old_filter_by_layout, "new_filter_by_layout" => $new_filter_by_layout, ); } $on_success_js_func = $on_success_js_func ? $on_success_js_func : "refreshLastNodeParentChilds"; $on_success_js_func_opts = $on_success_js_func_opts ? json_encode($on_success_js_func_opts) : ""; $main_content .= "
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
<div class="edit_project_details' . (count($layers_projects) == 1 ? ' single_presentation_layer' : '') . '">'; $main_content .= '
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
		
		<div class="image">
			' . ($project_image ? '<img src="' . $project_image . '" alt="No Image" />' : '<div class="no_logo"></div>') . '
			
			<label>Change logo:</label>
			<input type="file" name="image" />
		</div>
		<div class="details">
			<div class="name">
				<label>Name your project:</label>
				<input type="hidden" name="old_name" value="' . $old_project . '" />
				<input name="name" placeHolder="Type a name" value="' . $project . '" />
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
			<div class="project_folder">
				<label>Want to assign this project to a folder?</label>
				<input type="hidden" name="old_project_folder" value="' . $old_project_folder . '" />
				<input name="project_folder" placeHolder="Type folder name" value="' . $project_folder . '" />
				<span class="icon search" onClick="onChooseProjectFolder(this)"></span>
			</div>
		</div>
		
		<div class="buttons">
			<input type="submit" name="save" value="Save Project" />
		</div>
	</form>
</div>'; ?>
