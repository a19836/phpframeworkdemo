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

include_once $EVC->getUtilPath("AdminMenuUIHandler"); if (!$is_admin_ui_advanced_allowed) { echo '<script>
		alert("You don\'t have permission to access this Workspace!");
		document.location="' . $project_url_prefix . 'auth/logout";
	</script>'; die(); } $logged_name = $UserAuthenticationHandler->auth["user_data"]["name"] ? $UserAuthenticationHandler->auth["user_data"]["name"] : $UserAuthenticationHandler->auth["user_data"]["username"]; $filter_by_layout_url_query = $filter_by_layout ? "&filter_by_layout=$filter_by_layout&filter_by_layout_permission=$filter_by_layout_permission" : ""; $head = AdminMenuUIHandler::getHeader($project_url_prefix, $project_common_url_prefix); $head .= '
<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/admin_advanced.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/admin_advanced.js"></script>

<script>
var path_to_filter = "' . $filter_by_layout . '";
</script>'; $main_content = AdminMenuUIHandler::getContextMenus($exists_db_drivers); $main_content .= '
	<div id="first_top_panel" class="top_panel ' . $theme_layout . '">
		<ul class="left">
			<li class="logo"></li>
		</ul>
		<ul class="right">
			<li class="icon full_screen" data-title="Toggle Full Screen" onClick="toggleFullScreen(this)"></li>
			<li class="icon info" data-title="About" onClick="goTo(this, \'about_url\', event)" about_url="' . $project_url_prefix . 'admin/about"></li>
			<li class="icon logout" data-title="Logout" onClick="document.location=this.getAttribute(\'logout_url\')" logout_url="' . $project_url_prefix . 'auth/logout"></li>
			<li class="separator">|</li>
			<li class="sub_menu sub_menu_user">
				<i class="icon user"></i>
				<ul>
					<li class="login_info" title="Logged as \'' . $logged_name . '\' user."><i class="icon user"></i> Logged as "' . $logged_name . '"</li>
				</ul>
			</li>
			
		</ul>
	</div>
	<div id="second_top_panel" class="top_panel ' . $theme_layout . '">
		<ul class="left">
			<li class="filter_by_layout">
				<label>Project: </label>
				<select onChange="filterByLayout(this)">
					<option value="">- All -</option>'; $selected_project_name = ""; foreach ($presentation_projects_by_layer_label_and_folders as $layer_label => $projs) { $main_content .= '<optgroup label="' . $layer_label . '">'; $main_content .= getProjectsHtml($projs, $filter_by_layout); $main_content .= '</optgroup>'; if ($filter_by_layout && $presentation_projects_by_layer_label[$layer_label][$filter_by_layout]) $selected_project_name = $presentation_projects_by_layer_label[$layer_label][$filter_by_layout]; } foreach ($non_projects_layout_types as $lname => $lid) $main_content .= '<option value="' . $lname . '" ' . ($filter_by_layout == $lname ? ' selected' : '') . '>' . $lname . '</option>'; $main_content .= '	</select>
					<!--span class="icon project" onClick="chooseAvailableProject(\'' . $project_url_prefix . 'admin/choose_available_project?redirect_path=admin&popup=1\');" data-title="' . ($selected_project_name ? 'Selected Project: \'' . $selected_project_name . '\'. ' : '') . 'Please click here to choose another project."></span-->
			</li>
		</ul>
		<ul class="right">
			<li class="icon go_back" onClick="goBack()" data-title="Go Back"></li>
			<li class="separator">|</li>
			' . ($is_flush_cache_allowed ? '<li class="icon flush_cache" data-title="Flush Cache" onClick="flushCacheFromAdmin(\'' . $project_url_prefix . 'admin/flush_cache\')"></li>' : '') . '
			<li class="icon refresh" onClick="refreshIframe()" data-title="Refresh"></li>
			<li class="separator">|</li>
			
			<li class="icon home" data-title="Home" onClick="goTo(this, \'home_url\', event)" home_url="' . $project_url_prefix . 'admin/admin_home?selected_layout_project=' . $filter_by_layout . '"></li>
			<li class="icon tools" onClick="chooseAvailableTool(\'' . "{$project_url_prefix}admin/choose_available_tool?filter_by_layout=$filter_by_layout&popup=1" . '\')" data-title="Tools"></li>
			<li class="icon question" onClick="chooseAvailableTutorial(\'' . $project_url_prefix . 'admin/choose_available_tutorial?popup=1\');" data-title="Tutorials - How To?"></li>
			
			<!--li class="icon expand_left_panel" data-title="Expand Left Panel" onClick="expandLeftPanel(this)"></li>
			<li class="icon collapse_left_panel" data-title="Collapse Left Panel" onClick="collapseLeftPanel(this)"></li-->
			
			<li class="sub_menu sub_menu_others">
				<i class="icon sub_menu_vertical"></i>
				<ul>
					<li title="Toggle Theme"><a onClick="toggleThemeLayout(this)"><i class="icon toggle_theme_layout"></i> <span>' . ($theme_layout == "dark_theme" ? "Show light theme" : "Show dark theme") . '</span></a></li>
				</ul>
			</li>
		</ul>
	</div>

	<div id="left_panel" class="' . $tree_layout . ' ' . $advanced_level . ' ' . $theme_layout . '">
		<div class="icon sub_menu">
			<ul>
				<li><a onClick="toggleAdvancedLevel(this)"><i class="icon enable"></i> <span>' . ($advanced_level == "advanced_level" ? "Show basic items" : "Show advanced items") . '</span></a></li>
				<li><a onClick="toggleTreeLayout(this)"><i class="icon toggle_tree_layout"></i> <span>' . ($tree_layout == "left_panel_with_tabs" ? "Show vertical layout" : "Show horizontal layout") . '</span></a></li>
			</ul>
		</div>
		
		<div class="file_tree_root"></div>
		<div id="file_tree" class="mytree' . ($theme_layout == "dark_theme" ? " jstree-default-light" : "") . ' hidden">
			<ul>'; $main_layers_properties = array(); $main_content .= AdminMenuUIHandler::getLayersGroup("presentation_layers", $layers["presentation_layers"], $main_layers_properties, $project_url_prefix, $filter_by_layout, $filter_by_layout_permission); $main_content .= AdminMenuUIHandler::getLayersGroup("business_logic_layers", $layers["business_logic_layers"], $main_layers_properties, $project_url_prefix, $filter_by_layout, $filter_by_layout_permission); $main_content .= AdminMenuUIHandler::getLayersGroup("data_access_layers", $layers["data_access_layers"], $main_layers_properties, $project_url_prefix, $filter_by_layout, $filter_by_layout_permission); $main_content .= AdminMenuUIHandler::getLayersGroup("db_layers", $layers["db_layers"], $main_layers_properties, $project_url_prefix, $filter_by_layout, $filter_by_layout_permission); $main_content .= '
				<li class="main_node_library jstree-open" data-jstree=\'{"icon":"main_node main_node_library"}\'>
					<label>Library</label>
					<ul>'; $main_content .= isset($layers["libs"]["lib"]) ? AdminMenuUIHandler::getLayer("lib", $layers["libs"]["lib"], $main_layers_properties, $project_url_prefix) : ''; $main_content .= isset($layers["vendors"]["vendor"]) ? AdminMenuUIHandler::getLayer("vendor", $layers["vendors"]["vendor"], $main_layers_properties, $project_url_prefix) : ''; $main_content .= $layers["others"]["other"] ? AdminMenuUIHandler::getLayer("other", $layers["others"]["other"], $main_layers_properties, $project_url_prefix) : ''; $main_content .= '
					</ul>
				</li>'; $main_content .= '</ul>
		</div>
	</div>
	<script>
		main_layers_properties = ' . json_encode($main_layers_properties) . ';
	</script>
	<div id="hide_panel">
		<div class="button minimize" onClick="toggleLeftPanel(this)"></div>
	</div>
	<div id="right_panel">
		<iframe src="' . $project_url_prefix . 'admin/' . ($filter_by_layout ? "admin_home_project?$filter_by_layout_url_query" : "admin_home?selected_layout_project=$filter_by_layout") . '"></iframe>
		<div class="iframe_overlay">
			<div class="iframe_loading">Loading...</div>
		</div>
	</div>'; if ($default_page) $main_content .= '<script>$("iframe")[0].src = \'' . $default_page . '\';</script>'; function getProjectsHtml($v12ed481092, $pb154d332, $pdcf670f6 = "") { $pf8ed4912 = ""; if (is_array($v12ed481092)) foreach ($v12ed481092 as $pcfd27d54 => $v5c37a7b23d) { if (is_array($v5c37a7b23d)) $pf8ed4912 .= '<option disabled>' . $pdcf670f6 . $pcfd27d54 . '</option>' . getProjectsHtml($v5c37a7b23d, $pb154d332, $pdcf670f6 . "&nbsp;&nbsp;&nbsp;"); else $pf8ed4912 .= '<option value="' . $pcfd27d54 . '" ' . ($pb154d332 == $pcfd27d54 ? ' selected' : '') . '>' . $pdcf670f6 . $v5c37a7b23d . '</option>'; } return $pf8ed4912; } ?>
