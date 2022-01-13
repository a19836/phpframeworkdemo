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
<!-- Add Icon CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/install_template.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/install_template.js"></script>

<script>
	var get_store_templates_url = "' . $get_store_templates_url . '";
</script>'; $main_content = '<div class="title">Install New Template</div>'; if ($_POST) { if (!$status) { $error_message = $error_message ? $error_message : "There was an error trying to install this template. Please try again..."; if ($messages) { $main_content .= '<ul class="messages">'; foreach ($messages as $project_name => $msgs) { if ($msgs) { $main_content .= '<li><label>' . ucfirst($project_name) . ' project\'s installation:</label><ul>'; foreach ($msgs as $msg) { $main_content .= '<li class="' . $msg["type"] . '">' . $msg["msg"] . '</li>'; } $main_content .= '</ul></li>'; } } $main_content .= '</ul>'; } } else { $status_message = 'Template successfully installed!'; $main_content .= '<script>if (window.parent.refreshLastNodeChilds) window.parent.refreshLastNodeChilds();</script>'; } } $main_content .= '<div class="file_upload">
	<div class="sub_title">Please choose the project and module zip file to install:</div>
	
	<div class="layer">
		<label>Layer: </label>
		<select onChange="onChangeLayer(this)">'; foreach ($layers_projects as $bn => $layer) $main_content .= '<option' . ($bean_name == $bn ? ' selected' : '') . ' value="' . $bn . '">' . $layer["item_label"] . '</option>'; $main_content .= '</select>
	</div>'; foreach ($layers_projects as $bn => $layer) { $bfn = $layer["bean_file_name"]; $projects = $layer["projects"]; $main_content .= '
	<form id="form_' . $bn . '" class="hidden" action="?bean_name=' . $bn . '&bean_file_name=' . $bfn . '" method="post" enctype="multipart/form-data">
		<div class="project">
			<label>Project: </label>
			<select name="project">'; if ($projects) { foreach ($projects as $project_name => $project) { $main_content .= '<option' . ($selected_project == $project_name ? ' selected' : '') . '>' . $project_name . '</option>'; } } $main_content .= '
			</select>
		</div>
		
		<input class="upload_file" type="file" name="zip_file">
		<input class="button" type="submit" value="Install Now" name="submit" onClick="$(\'<p class=installing>Installing...</p>\').insertBefore(this);$(this).hide();">
	</form>
	
	' . ($get_store_templates_url ? '<div class="install_store_template">To install templates from store please click <a href="javascript:void(0)" onClick="installStoreTemplatePopup();">here</a></div>' : '') . '
	' . ($templates_download_page_url ? '<div class="go_to_templates_download_page">To download more templates please click <a href="' . $templates_download_page_url . '" target="download_templates">here</a></div>' : '') . ''; } $main_content .= '</div>'; ?>
