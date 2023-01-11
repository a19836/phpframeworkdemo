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

include $EVC->getUtilPath("BreadCrumbsUIHandler"); $head = '
<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icon CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/install_template.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/install_template.js"></script>

<script>
var get_store_templates_url = "' . $project_url_prefix . "phpframework/admin/get_store_type_content?type=templates" . '"; //This is a global var
var is_popup = ' . ($popup ? 1 : 0) . ';
</script>'; $main_content = '
	<div class="top_bar' . ($popup ? " in_popup" : "") . '">
		<header>
			<div class="title" title="' . $path . '">Install New Template in ' . BreadCrumbsUIHandler::getFilePathBreadCrumbsHtml($selected_project, $P) . '</div>
			<ul>
				<li class="continue" data-title="Install Template Now"><a onClick="installTemplate(this)"><i class="icon continue"></i> Install Template Now</a></li>
			</ul>
		</header>
	</div>'; if ($_POST) { if (!$status) { $error_message = $error_message ? $error_message : "There was an error trying to install this template. Please try again..."; if ($messages) { $main_content .= '<ul class="messages">'; foreach ($messages as $project_name => $msgs) { if ($msgs) { $main_content .= '<li><label>' . ucfirst($project_name) . ' project\'s installation:</label><ul>'; foreach ($msgs as $msg) { $main_content .= '<li class="' . $msg["type"] . '">' . $msg["msg"] . '</li>'; } $main_content .= '</ul></li>'; } } $main_content .= '</ul>'; } } else { $status_message = 'Template successfully installed!'; $on_success_js_func = $on_success_js_func ? $on_success_js_func : "refreshAndShowLastNodeChilds"; $main_content .= "<script>if (typeof window.parent.$on_success_js_func == 'function') window.parent.$on_success_js_func();</script>"; } } $main_content .= '<div class="file_upload">
	<div class="sub_title">Please choose a template to install: <span class="icon view" onClick="toggleLayerAndProject()" title="Click here to install a template in a different project"></span></div>
	
	<div class="layer' . (count($layers_projects) == 1 ? ' unique_layer hidden' : '') . '">
		<label>Layer: </label>
		<select onChange="onChangeLayer(this)">'; foreach ($layers_projects as $bn => $layer) $main_content .= '<option' . ($bean_name == $bn ? ' selected' : '') . ' value="' . $bn . '">' . $layer["item_label"] . '</option>'; $main_content .= '</select>
	</div>'; foreach ($layers_projects as $bn => $layer) { $bfn = $layer["bean_file_name"]; $projects = $layer["projects"]; $query_str = $_SERVER["QUERY_STRING"]; $query_str = preg_replace("/(^|&)(bean_name|bean_file_name)=[^&]*/", "", $query_str); $main_content .= '
	<form id="form_' . $bn . '" class="hidden" action="?bean_name=' . $bn . '&bean_file_name=' . $bfn . $query_str . '" method="post" enctype="multipart/form-data">
		<div class="project' . ($selected_project && $projects[$selected_project] ? ' hidden' : '') . '">
			<label>Project: </label>
			<select name="project">'; if ($projects) { $previous_folder = null; foreach ($projects as $project_name => $project) { $project_folder = dirname($project_name); $project_folder = $project_folder == "." ? "" : $project_folder; if ($project_folder && $project_folder != $previous_folder) { $main_content .= '<option disabled>' . str_repeat("&nbsp;&nbsp;&nbsp;", substr_count($project_folder, '/')) . basename($project_folder) . '</option>'; $previous_folder = $project_folder; } $main_content .= '<option' . ($bean_name == $bn && $selected_project == $project_name ? ' selected' : '') . ' value="' . $project_name . '">' . str_repeat("&nbsp;&nbsp;&nbsp;", substr_count($project_name, '/')) . basename($project_name) . '</option>'; } } $main_content .= '
			</select>
		</div>
		
		<input class="upload_file" type="file" name="zip_file">
	</form>'; } $main_content .= '
	' . ($templates_download_page_url ? '<div class="go_to_templates_download_page">To download more templates please click <a href="' . $templates_download_page_url . '" target="download_templates">here</a></div>' : '') . '
	
</div>'; if ($get_store_templates_url) $main_content .= '
<div class="install_store_template">
	<div class="title">Choose a template to install from our store:</div>
	<ul>
		<li class="loading">Loading templates from store...</li>
	</ul>
</div>'; ?>
