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

<!-- Top-Bar CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/top_bar.css" type="text/css" charset="utf-8" />

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/manage_modules.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/manage_modules.js"></script>
'; $main_content = '
<div class="top_bar">
	<header>
		<div class="title">Manage Modules in layer:</div>
	</header>
</div>

<div class="modules_list">
	<div class="layer">
		<label>Presentation Layer:</label>
		<select onChange="showModulesLayer(this)" title="Choose a Presentation Layer">'; $t = count($modules); for ($i = 0; $i < $t; $i++) { $m = $modules[$i]; $main_content .= '<option modules_id="layer_modules_' . $i . '"' . ($m["bean_name"] == $_GET["bean_name"] && $m["bean_file_name"] == $_GET["bean_file_name"] ? " selected" : "") . '>' . $m["item_label"] . '</option>'; } $main_content .= '		
		</select>
	</div>'; for ($i = 0; $i < $t; $i++) { $m = $modules[$i]; $bean_name = $m["bean_name"]; $bean_file_name = $m["bean_file_name"]; $project_loaded_modules = $m["modules"]; $delete_module_url = $project_url_prefix . "phpframework/admin/manage_module?bean_name=$bean_name&bean_file_name=$bean_file_name&action=uninstall&module_id=#module_id#"; $disable_module_url = $project_url_prefix . "phpframework/admin/manage_module?bean_name=$bean_name&bean_file_name=$bean_file_name&action=disable&module_id=#module_id#"; $enable_module_url = $project_url_prefix . "phpframework/admin/manage_module?bean_name=$bean_name&bean_file_name=$bean_file_name&action=enable&module_id=#module_id#"; $main_content .= '<div id="layer_modules_' . $i . '" class="layer_modules">'; if ($is_install_module_allowed) $main_content .= '
	<div class="install">
		<a href="' . $project_url_prefix . 'phpframework/admin/install_module?bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&filter_by_layout=' . $filter_by_layout . '">Install New Module</a>
	</div>'; $main_content .= '
	<table>
		<thead>
			<tr>
				<th class="table_header group"></th>
				<th class="table_header status">Status</th>
				<th class="table_header photo">Photo</th>
				<th class="table_header label">Label</th>
				<th class="table_header module_id">Module ID</th>
				<th class="table_header description">Description</th>
				<th class="table_header buttons">
					<span class="icon disable" onClick="executeActionInAllModules(this, \'disable\')" title="Click here to disable all modules"></span>
					<span class="icon enable" onClick="executeActionInAllModules(this, \'enable\')" title="Click here to enable all modules"></span>
				</th>
			</tr>
		</thead>
		<tbody>'; if (is_array($loaded_modules)) { foreach ($loaded_modules as $group_module_id => $loaded_modules_by_group) { $sub_main_content = ""; foreach ($loaded_modules_by_group as $module_id => $loaded_module) if ($project_loaded_modules[$module_id]) { $enable = CMSModuleEnableHandler::isModuleEnabled($project_loaded_modules[$module_id]["path"]); $admin_url = $loaded_module["admin_path"] ? $project_url_prefix . "phpframework/admin/module_admin?bean_name=$bean_name&bean_file_name=$bean_file_name&filter_by_layout=$filter_by_layout&group_module_id=$group_module_id" : null; $sub_main_content .= '<tr class="group_module_item" group_module_id="' . $group_module_id . '">
						<td class="group"></td>
						<td class="status"><span class="icon ' . ($enable ? 'enable' : 'disable') . '" title="This module is currently ' . ($enable ? 'enabled' : 'disabled') . '"></span></td>
						<td class="photo">' . ($loaded_module["images"][0]["url"] ? '<img src="' . $loaded_module["images"][0]["url"] . '" />' : 'No Photo') . '</td>
						<td class="label">' . $loaded_module["label"] . '</td>
						<td class="module_id">' . $loaded_module["id"] . '</td>
						<td class="description">' . str_replace("\n", "<br>", $loaded_module["description"]) . '</td>
						<td class="buttons">
							<span class="icon disable" ' . ($enable ? '' : 'style="display:none;"') . ' onClick="disableModule(this, \'' . str_replace("#module_id#", $loaded_module["id"], $disable_module_url) . '\')" title="Click here to disable this module"></span>
							<span class="icon enable" ' . ($enable ? 'style="display:none;"' : '') . ' onClick="enableModule(this, \'' . str_replace("#module_id#", $loaded_module["id"], $enable_module_url) . '\')" title="Click here to enable this module"></span>
							' . (!$loaded_module["is_reserved_module"] ? '<span class="icon delete" onClick="deleteModule(this, \'' . str_replace("#module_id#", $group_module_id, $delete_module_url) . '\', \'' . $module_id . '\', \'' . $group_module_id . '\')" title="Click here to delete this module permanently"></span>' : '') . '
							' . ($is_module_admin_allowed && $admin_url ? '<a href="' . $admin_url . '" class="icon settings" title="Go to this module\'s Admin Panel"></a>' : '') . '
						</td>
					</tr>'; } if ($sub_main_content) $main_content .= '<tr module_id="' . $group_module_id . '">
					<td class="group" colspan="7">
						<label>' . $group_module_id . '</label>
						<span class="icon maximize" onClick="toggleGroupOfMopdules(this, \'' . $group_module_id . '\')" title="Toggle Group of Modules"></span>
					</td>
				</tr>' . $sub_main_content; } } $main_content .= '</tbody>
		</table>
	</div>'; } $main_content .= '</div>'; ?>
