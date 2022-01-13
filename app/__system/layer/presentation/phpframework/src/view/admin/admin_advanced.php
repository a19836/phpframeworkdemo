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

include_once $EVC->getUtilPath("AdminMenuUIHandler"); $head = AdminMenuUIHandler::getHeader($project_url_prefix, $project_common_url_prefix); $head .= '
<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/admin_advanced.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/admin_advanced.js"></script>'; $main_content = AdminMenuUIHandler::getContextMenus($exists_db_drivers); $main_content .= '
<div id="menu_panel">
	<ul>
		<li class="expand_left_panel" title="Expand Left Panel" onClick="expandLeftPanel(this)">Expand Left Panel</li>
		<li class="collapse_left_panel" title="Collapse Left Panel" onClick="collapseLeftPanel(this)">Collapse Left Panel</li>
		<li class="home" title="Go to Home" onClick="goTo(this, \'home_url\', event)" home_url="' . $project_url_prefix . 'admin/admin_home?admin_type=advanced">Home</li>
		<li class="docbook" title="Go to Doc-Book" onClick="goTo(this, \'docbook_url\', event)" docbook_url="' . $project_url_prefix . 'docbook/">Doc Book</li>
		<li class="toggle_tree_layout" title="Toggle Tree Layout" onClick="toggleTreeLayout(this, \'' . ($left_panel_tree_layout_class == "left_panel_with_tabs" ? "left_panel_without_tabs" : "left_panel_with_tabs") . '\')">Toggle Tree Layout</li>
		' . ($is_flush_cache_allowed ? '<li class="flush_cache" title="Flush Cache" onClick="flushCacheFromAdmin(\'' . $project_url_prefix . 'admin/flush_cache\')">Flush Cache</li>' : '') . '
	</ul>
</div>

<div id="left_panel" class="' . $left_panel_tree_layout_class . '">
	<div class="filter_by_layout">
		<label>Filter by: </label>
		<select onChange="filterByLayout(this)">
			<option value="">- All -</option>'; foreach ($presentation_projects_by_layer_label as $layer_label => $projs) { $main_content .= '<optgroup label="' . $layer_label . '">'; foreach ($projs as $proj_id => $proj_name) $main_content .= '<option value="' . $proj_id . '" ' . ($filter_by_layout == $proj_id ? ' selected' : '') . '>' . $proj_name . '</option>'; $main_content .= '</optgroup>'; } foreach ($non_projects_layout_types as $lname => $lid) $main_content .= '<option value="' . $lname . '" ' . ($filter_by_layout == $lname ? ' selected' : '') . '>' . $lname . '</option>'; $main_content .= '	</select>
	</div>
	
	<div class="file_tree_root"></div>
	<div id="file_tree" class="mytree hidden">
		<ul>'; $main_layers_properties = array(); $main_content .= AdminMenuUIHandler::getLayersGroup("presentation_layers", $layers["presentation_layers"], $main_layers_properties, $project_url_prefix, $filter_by_layout, $filter_by_layout_permission); $main_content .= AdminMenuUIHandler::getLayersGroup("business_logic_layers", $layers["business_logic_layers"], $main_layers_properties, $project_url_prefix, $filter_by_layout, $filter_by_layout_permission); $main_content .= AdminMenuUIHandler::getLayersGroup("data_access_layers", $layers["data_access_layers"], $main_layers_properties, $project_url_prefix, $filter_by_layout, $filter_by_layout_permission); $main_content .= AdminMenuUIHandler::getLayersGroup("db_layers", $layers["db_layers"], $main_layers_properties, $project_url_prefix, $filter_by_layout, $filter_by_layout_permission); $main_content .= isset($layers["vendors"]["vendor"]) ? AdminMenuUIHandler::getLayer("vendor", $layers["vendors"]["vendor"], $main_layers_properties, $project_url_prefix) : ''; $main_content .= '
			<li class="management jstree-open" data-jstree=\'{"icon":"main_node main_node_management"}\'>
				<label>Management</label>
				<ul>
					<li data-jstree=\'{"icon":"main_node_admin_simple_ui"}\'><a class="link" href="' . $project_url_prefix . 'admin/admin_uis" onClick="document.location=this.href"><label>Switch Admin UI</label></a></li>
					' . ($is_manage_users_allowed ? '<li data-jstree=\'{"icon":"main_node_user_management"}\'><a class="link" onClick="goTo(this,\'url\', event)" url="' . $project_url_prefix . 'user/manage_users"><label>Users Management</label></a></li>' : '') . '
					' . ($is_manage_layers_allowed ? '<li data-jstree=\'{"icon":"main_node_layers_management"}\'><a class="link" onClick="goTo(this,\'url\', event)" url="' . $project_url_prefix . 'setup?step=3.1&iframe=1"><label>Layers Management</label></a></li>' : '') . '
					' . ($is_manage_modules_allowed ? '<li data-jstree=\'{"icon":"main_node_modules_management"}\'><a class="link" onClick="goTo(this,\'url\', event)" url="' . $project_url_prefix . 'phpframework/admin/manage_modules"><label>Modules Management</label></a></li>' : '') . '
					' . ($is_manage_projects_allowed ? '<li data-jstree=\'{"icon":"main_node_projects_management"}\'><a class="link" onClick="goTo(this,\'url\', event)" url="' . $project_url_prefix . 'phpframework/presentation/manage_projects"><label>Projects Management</label></a></li>' : '') . '
					' . ($is_testunits_allowed ? '<li data-jstree=\'{"icon":"main_node_testunit_management"}\'><a class="link" onClick="goTo(this,\'url\', event)" url="' . $project_url_prefix . 'phpframework/testunit/"><label>Test-Units Management</label></a></li>' : '') . '
					' . ($is_deployment_allowed ? '<li data-jstree=\'{"icon":"main_node_deployment_management"}\'><a class="link" onClick="goTo(this,\'url\', event)" url="' . $project_url_prefix . 'phpframework/deployment/"><label>Deployments Management</label></a></li>' : '') . '
					' . ($is_program_installation_allowed ? '<li data-jstree=\'{"icon":"main_node_program_installation"}\'><a class="link" onClick="goTo(this,\'url\', event)" url="' . $project_url_prefix . 'phpframework/admin/install_program"><label>Install a Program</label></a></li>' : '') . '
					' . ($is_diff_files_allowed ? '<li data-jstree=\'{"icon":"main_node_diff_files"}\'><a class="link" onClick="goTo(this,\'url\', event)" url="' . $project_url_prefix . 'phpframework/diff/"><label>Diff Files</label></a></li>' : '') . '
					' . ($layers["others"]["other"] ? AdminMenuUIHandler::getLayer("other", $layers["others"]["other"], $main_layers_properties, $project_url_prefix) : '') . '
					' . ($is_flush_cache_allowed ? '<li data-jstree=\'{"icon":"main_node_flush_cache"}\'><a class="link" onClick="flushCacheFromAdmin(\'' . $project_url_prefix . 'admin/flush_cache\');"><label>Flush Cache</label></a></li>' : '') . '
					<li data-jstree=\'{"icon":"main_node_logout"}\'><a class="link" href="' . $project_url_prefix . 'auth/logout" onClick="document.location=this.href"><label>Logout</label></a></li>
					<li data-jstree=\'{"icon":"main_node_about"}\'><a class="link" onClick="goTo(this,\'url\', event)" url="' . $project_url_prefix . 'phpframework/admin/about"><label>About</label></a></li>
				</ul>
			</li>
		</ul>
	</div>
</div>
<script>
	main_layers_properties = ' . json_encode($main_layers_properties) . ';
</script>
<div id="hide_panel">
	<div class="button minimize" onClick="toggleLeftPanel(this)"></div>
</div>
<div id="right_panel">
	<iframe src="' . $project_url_prefix . 'admin/admin_home?admin_type=advanced"></iframe>
	<div class="iframe_overlay">
		<div class="iframe_loading">Loading...</div>
	</div>
</div>'; if ($default_page) $main_content .= '<script>$("iframe")[0].src = \'' . $default_page . '\';</script>'; ?>
