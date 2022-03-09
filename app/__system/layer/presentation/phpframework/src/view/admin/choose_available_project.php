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

include_once $EVC->getUtilPath("AdminMenuUIHandler"); $add_project_url = $project_url_prefix . "phpframework/presentation/manage_file?bean_name=#bean_name#&bean_file_name=#bean_file_name#&action=create_folder&item_type=presentation&extra=#extra#&path=&folder_type=project"; $head = AdminMenuUIHandler::getHeader($project_url_prefix, $project_common_url_prefix); $head .= '
<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/choose_available_project.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/choose_available_project.js"></script>

<script>
var add_project_url = "' . $add_project_url . '";
var select_project_url = "' . $project_url_prefix . $redirect_path . (strpos($redirect_path, "?") !== false ? '&' : '?') . 'bean_name=#bean_name#&bean_file_name=#bean_file_name#&project=#project#";
var layers_props = ' . json_encode($layers_projects) . ';
var is_popup = ' . ($is_popup ? 1 : 0) . ';

$(function () {
	updateLayerProjects("' . $folder_to_filter . '");
});
</script>'; $main_content = '
<div class="choose_available_project">
	<div class="title">Please choose a project</div>'; if ($layers_projects) { $main_content .= '
	<div class="layer">
		<label' . (count($layers_projects) == 1 ? ' class="hidden"' : '') . '>Presentation Layer:</label>
		<select' . (count($layers_projects) == 1 ? ' class="hidden"' : '') . ' onChange="updateLayerProjects(this)">'; foreach ($layers_projects as $bean_name => $layer_props) { $main_content .= '<option bean_name="' . $bean_name . '" bean_file_name="' . $layer_props["bean_file_name"] . '">' . $layer_props["item_label"] . '</option>'; } $main_content .= '
		</select>
		<a class="create_project" href="javascript:void(0)" onClick="addProject();" title="Click here to add a new project"><span class="icon add"></span> Add Project</a>
	</div>'; } $main_content .= '
	<div class="current_project_folder"></div>
	<div class="loading_projects"><span class="icon loading"></span> Loading projects...</div>
	<ul></ul>
</div>'; ?>
