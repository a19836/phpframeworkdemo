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

include_once $EVC->getUtilPath("AdminMenuUIHandler"); if (!$is_admin_ui_low_code_allowed) { echo '<script>
		alert("You don\'t have permission to access this Admin UI!");
		document.location="' . $project_url_prefix . 'auth/logout";
	</script>'; die(); } $logged_name = $UserAuthenticationHandler->auth["user_data"]["name"] ? $UserAuthenticationHandler->auth["user_data"]["name"] : $UserAuthenticationHandler->auth["user_data"]["username"]; $filter_by_layout_url_query = $filter_by_layout ? "&filter_by_layout=$filter_by_layout&filter_by_layout_permission=$filter_by_layout_permission" : ""; $main_layers_properties = array(); $head = AdminMenuUIHandler::getHeader($project_url_prefix, $project_common_url_prefix); $head .= '
<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icon CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/admin_low_code.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/admin_low_code.js"></script>

<script>
menu_item_properties = ' . json_encode($menu_item_properties) . ';
</script>'; $main_content = AdminMenuUIHandler::getContextMenus($exists_db_drivers); if (!$projects) $main_content .= '<script>alert("Error: No projects available! Please contact your sysadmin...");</script>'; $main_content .= '
<div id="selected_menu_properties" class="myfancypopup">
	<div class="title">Properties</div>
	<p class="content"></p>
</div>

<div id="menu_panel">
	<a class="selected_project" href="#" onClick="chooseAvailableProject(\'' . $project_url_prefix . 'admin/choose_available_project?redirect_path=admin&is_popup=1\');" title="Selected Project: \'' . $project . '\'. Please click here to choose another project.">' . $project . '</a>
	<span class="login_info" title="Logged as \'' . $logged_name . '\' user."><span class="fas fa-user"></span> ' . $logged_name . '</span>
	<span class="icon left" onClick="goBack()" title="Go Back">Go Back</span>
	<span class="icon refresh" onClick="refreshIframe()" title="Refresh">Refresh</span>
	<span class="icon home" onClick="goTo(this, \'home_url\', event)" home_url="' . "{$project_url_prefix}admin/admin_home?admin_type=citizen&bean_name=$bean_name&bean_file_name=$bean_file_name&project=$project" . '" title="Go Home">Home</span>
</div>

<div id="left_panel">
	<ul class="tabs">'; $available_layers = array("presentation_layers", "business_logic_layers", "data_access_layers", "db_layers"); if ($layers) { foreach ($available_layers as $layer_type_name) if ($layers[$layer_type_name]) { $label = ucwords(str_replace("_", " ", $layer_type_name)); $main_content .= '<li class="tab tab_' . $layer_type_name . '"><a href="#' . $layer_type_name . '" title="' . $label . '"><i class="icon main_node main_node_' . $layer_type_name . '"></i></a></li>'; } $main_content .= isset($layers["vendors"]["vendor"]) ? '<li class="tab tab_vendors"><a href="#vendors" title="Vendors"><i class="icon main_node main_node_vendors"></i></a></li>' : ''; } $main_content .= '
		<li class="tab tab_settings"><a href="#settings" title="Settings"><i class="icon main_node main_node_settings"></i></a></li>
	</ul>'; if ($layers) { foreach ($available_layers as $layer_type_name) if ($layers[$layer_type_name]) { $only_one_layer = count($layers[$layer_type_name]) == 1; $class = !$only_one_layer || $layer_type_name == "presentation_layers" ? "with_sub_groups" : ""; $main_content .= '<div id="' . $layer_type_name . '" class="layers with_sub_menus"><div id="' . $layer_type_name . '_tree" class="mytree hidden ' . $class . '"><ul>'; foreach ($layers[$layer_type_name] as $layer_name => $layer) $main_content .= AdminMenuUIHandler::getLayer($layer_name, $layer, $main_layers_properties, $project_url_prefix, $filter_by_layout, $filter_by_layout_permission); $main_content .= '</ul></div>'; if ($only_one_layer) { $sub_menu_html = ""; if ($layer_type_name == "presentation_layers") $sub_menu_html = '
						<li class="level">
							<label>Level:</label>
							<select onChange="toggleComplexityLevel(this)">
								<option value="0">Basic</option>
								<option value="1">Advanced</option>
							</select>
						</li>'; $main_content .= getSubMenuHtml($sub_menu_html); } $main_content .= '</div>'; } if (isset($layers["vendors"]["vendor"])) { $main_content .= '<div id="vendors" class="layers with_sub_menus"><div id="vendors_tree" class="mytree hidden"><ul>' . AdminMenuUIHandler::getLayer("vendor", $layers["vendors"]["vendor"], $main_layers_properties, $project_url_prefix) . '</ul></div>'; $main_content .= getSubMenuHtml(); $main_content .= '</div>'; } } $main_content .= '
	<div id="settings" class="layers">
		<div id="settings_tree" class="mytree hidden">
			<ul>
				' . ($is_switch_admin_ui_allowed ? '<li data-jstree=\'{"icon":"main_node_admin_simple_ui"}\'><a class="link" href="' . $project_url_prefix . 'admin/admin_uis" onClick="document.location=this.href"><label>Switch Admin UI</label></a></li>' : '') . '
				<li data-jstree=\'{"icon":"main_node_admin_simple_ui"}\'><a class="link" href="javascript:void(0)" onClick="chooseAvailableProject(\'' . $project_url_prefix . 'admin/choose_available_project?redirect_path=admin&is_popup=1\');"><label>Switch Project</label></a></li>
				' . ($is_manage_users_allowed ? '<li data-jstree=\'{"icon":"main_node_user_management"}\'><a class="link" onClick="goTo(this,\'url\', event)" url="' . $project_url_prefix . 'user/manage_users"><label>Users Management</label></a></li>' : '') . '
				' . ($is_manage_layers_allowed ? '<li data-jstree=\'{"icon":"main_node_layers_management"}\'><a class="link" onClick="goTo(this,\'url\', event)" url="' . $project_url_prefix . 'setup?step=3.1&iframe=1&hide_cancel_btn=1&hide_beginner_btn=1&strict_connections_to_one_level=1"><label>Layers Management</label></a></li>' : '') . '
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
		</div>
	</div>
</div>
<script>
	main_layers_properties = ' . json_encode($main_layers_properties) . ';
</script>
<div id="hide_panel">
	<div class="button minimize" onClick="toggleLeftPanel(this)"></div>
</div>
<div id="right_panel">
	<iframe src="' . "{$project_url_prefix}admin/admin_home?admin_type=citizen&bean_name=$bean_name&bean_file_name=$bean_file_name&project=$project" . '"></iframe>
	<div class="iframe_overlay">
		<div class="iframe_loading">Loading...</div>
	</div>
</div>'; function getSubMenuHtml($pf8ed4912 = "") { return '<div class="sub_menus">
			<label onClick="toggleSubmenus(this)">
				Sub-menus
				<i class="fas fa-sort-up"></i>
			</label>
			<ul>' . $pf8ed4912 . '</ul>
		</div>'; } ?>
