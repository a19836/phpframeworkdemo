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

include_once $EVC->getUtilPath("AdminMenuUIHandler"); $admin_home_project_page_url = $project_url_prefix . "admin/admin_home_project?filter_by_layout=#filter_by_layout#"; $add_project_url = $project_url_prefix . "phpframework/presentation/edit_project_details?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&popup=1&on_success_js_func=onSucccessfullAddProject"; $head = AdminMenuUIHandler::getHeader($project_url_prefix, $project_common_url_prefix); $head .= '
<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/choose_available_project.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/choose_available_project.js"></script>

<script>
var add_project_url = "' . $add_project_url . '";
var admin_home_project_page_url = "' . $admin_home_project_page_url . '";
var select_project_url = "' . $project_url_prefix . $redirect_path . (strpos($redirect_path, "?") !== false ? '&' : '?') . 'bean_name=#bean_name#&bean_file_name=#bean_file_name#&project=#project#&filter_by_layout=#filter_by_layout#";
var layers_props = ' . json_encode($layers_projects) . ';
var is_popup = ' . ($popup ? 1 : 0) . ';
var selected_project_id = "' . $selected_project_id . '";

$(function () {
	updateLayerProjects("' . $folder_to_filter . '");
});
</script>'; $main_content = '
<div class="choose_available_project' . ($popup ? " in_popup" : "") . '' . (count($layers_projects) == 1 ? ' single_presentation_layer' : '') . ($projects_exists ? '' : ' no_projects') . '">
	
	<div class="top_bar">
		<header>
			<div class="title">
				<div class="breadcrumbs"></div>
			</div>
		</header>
	</div>
	
	<div class="title' . ($popup ? " inside_popup_title" : "") . '">Choose a Project</div>'; if ($layers_projects) { $main_content .= '
		<div class="layer">
			<label>Presentation Layer:</label>
			<select onChange="updateLayerProjects(this)">'; foreach ($layers_projects as $bean_name => $layer_props) { $main_content .= '
				<option bean_name="' . $bean_name . '" bean_file_name="' . $layer_props["bean_file_name"] . '" layer_bean_folder_name="' . $layer_props["layer_bean_folder_name"] . '">' . $layer_props["item_label"] . '</option>'; } $main_content .= '
			</select>
		</div>'; } $main_content .= '
		<div class="projects_list_type">
			<a href="javascript:void(0)" onClick="toggleProjectsListType(this, \'block_view\')" title="Show blocks view"><span class="icon block_view active"></span></a>
			<a href="javascript:void(0)" onClick="toggleProjectsListType(this, \'list_view\')" title="Show list view"><span class="icon list_view"></span></a>
		</div>'; if (!$projects_exists) $main_content .= '
		<div class="new_project">
			<div class="button">
				<button onClick="addProject();">Create Your First Project!</button>
			</div>
			<div class="description">Soon this space will be filled with your projects.<br/>Create a new project to get started.</div>
		</div>'; else $main_content .= '
		<div class="new_project">
			<div class="button">
				<button onClick="addProject();">Create New Project</button>
			</div>
			<div class="description">Or select an existing project from the list below to get started.</div>
		</div>'; $main_content .= '
	<div class="loading_projects"><span class="icon loading"></span> Loading projects...</div>
	
	<div class="group projects block_view">
		<div class="title">
			<label>Projects:</label>
		</div>
		<ul></ul>
	</div>
</div>'; ?>
