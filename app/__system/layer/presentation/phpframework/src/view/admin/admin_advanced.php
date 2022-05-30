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
	</script>'; die(); } $logged_name = $UserAuthenticationHandler->auth["user_data"]["name"] ? $UserAuthenticationHandler->auth["user_data"]["name"] : $UserAuthenticationHandler->auth["user_data"]["username"]; $logged_name_initials = explode(" ", $logged_name); $logged_name_initials = strtoupper(substr($logged_name_initials[0], 0, 1) . substr($logged_name_initials[1], 0, 1)); $filter_by_layout_url_query = $filter_by_layout ? "&filter_by_layout=$filter_by_layout&filter_by_layout_permission=$filter_by_layout_permission" : ""; $head = AdminMenuUIHandler::getHeader($project_url_prefix, $project_common_url_prefix); $head .= '
<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/admin_advanced.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/admin_advanced.js"></script>

<script>
var path_to_filter = "' . $filter_by_layout . '";
</script>'; $main_content = AdminMenuUIHandler::getContextMenus($exists_db_drivers); $main_content .= '
	<div id="top_panel">
		<ul class="left">
			<li class="logo"></li>
		</ul>
		<ul class="center">
			<li class="filter_by_layout">
				<label>Project: </label>
				<select onChange="filterByLayout(this)">
					<option value="">- All -</option>'; $selected_project_name = ""; foreach ($presentation_projects_by_layer_label_and_folders as $layer_label => $projs) { $main_content .= '<optgroup label="' . $layer_label . '">'; $main_content .= getProjectsHtml($projs, $filter_by_layout); $main_content .= '</optgroup>'; if ($filter_by_layout && $presentation_projects_by_layer_label[$layer_label][$filter_by_layout]) $selected_project_name = $presentation_projects_by_layer_label[$layer_label][$filter_by_layout]; } foreach ($non_projects_layout_types as $lname => $lid) $main_content .= '<option value="' . $lname . '" ' . ($filter_by_layout == $lname ? ' selected' : '') . '>' . $lname . '</option>'; $main_content .= '	</select>
					<!--span class="icon project" onClick="chooseAvailableProject(\'' . $project_url_prefix . 'admin/choose_available_project?redirect_path=admin&popup=1\');" data-title="' . ($selected_project_name ? 'Selected Project: \'' . $selected_project_name . '\'. ' : '') . 'Please click here to choose another project."></span-->
					<a class="got_to_project_home" onClick="goTo(this, \'home_url\', event)" home_url="' . $project_url_prefix . 'admin/admin_home_project?filter_by_layout=' . $filter_by_layout . '" data-title="Go to project homepage"><span class="icon project_home"></span></a>
			</li>
		</ul>
		<ul class="right">
			<li class="icon go_back" onClick="goBack()" data-title="Go Back"></li>
			<li class="icon go_forward" onClick="goForward()" data-title="Go Forward"></li>
			<li class="separator">|</li>
			
			' . ($is_flush_cache_allowed ? '<li class="icon flush_cache" data-title="Flush Cache" onClick="flushCacheFromAdmin(\'' . $project_url_prefix . 'admin/flush_cache\')"></li>' : '') . '
			<li class="icon refresh" onClick="refreshIframe()" data-title="Refresh"></li>
			<li class="separator">|</li>
			
			<li class="icon home" data-title="Home" onClick="goTo(this, \'home_url\', event)" home_url="' . $project_url_prefix . 'admin/admin_home?selected_layout_project=' . $filter_by_layout . '"></li>
			<li class="icon tools" onClick="chooseAvailableTool(\'' . "{$project_url_prefix}admin/choose_available_tool?filter_by_layout=$filter_by_layout&popup=1" . '\')" data-title="Tools"></li>
			<li class="separator">|</li>
			
			<li class="icon full_screen" data-title="Toggle Full Screen" onClick="toggleFullScreen(this)"></li>
			<li class="separator">|</li>
			
			<li class="sub_menu sub_menu_user">
				<span class="logged_user_icon">' . $logged_name_initials . '</span>
				<i class="icon dropdown_arrow"></i>
				<!--i class="icon user"></i-->
				
				<ul>
					<div class="triangle_up"></div>
					
					<li class="login_info" title="Logged as \'' . $logged_name . '\' user."><a><span class="logged_user_icon">' . $logged_name_initials . '</span> Logged in as "' . $logged_name . '"</a></li>
					<li class="toggle_theme_layout" title="Toggle Theme"><a onClick="toggleThemeLayout(this)"><i class="icon toggle_theme_layout"></i> <span>Show dark theme</span></a></li>
					<li class="toggle_main_navigator_side" title="Toggle Theme"><a onClick="toggleNavigatorSide(this)"><i class="icon toggle_main_navigator_side"></i> <span>Show navigator on right side</span></a></li>
					<li class="separator"></li>
					<li class="question" title="Tutorials - How To?"><a onClick="chooseAvailableTutorial(\'' . $project_url_prefix . 'admin/choose_available_tutorial?popup=1\');"><i class="icon question"></i> Tutorials - How To?</a></li>
					<li class="info" title="About"><a onClick="goTo(this, \'about_url\', event)" about_url="' . $project_url_prefix . 'admin/about"><i class="icon info"></i> About</a></li>
					<li class="separator"></li>
					<li class="logout" title="Logout"><a onClick="document.location=this.getAttribute(\'logout_url\')" logout_url="' . $project_url_prefix . 'auth/logout"><i class="icon logout"></i> Logout</a></li>
				</ul>
			</li>
			
		</ul>
	</div>

	<div id="left_panel" class="' . $tree_layout . ' ' . $advanced_level . '">
		<div class="icon sub_menu">
			<ul>
				<div class="triangle_up"></div>
				
				<li class="toggle_advanced_level"><a onClick="toggleAdvancedLevel(this)"><i class="icon enable"></i> <span>Show advanced items</span></a></li>
				<li class="toggle_tree_layout"><a onClick="toggleTreeLayout(this)"><i class="icon toggle_tree_layout"></i> <span>Show vertical layout</span></a></li>
			</ul>
		</div>
		
		<div class="file_tree_root"></div>
		<div id="file_tree" class="mytree hidden">
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
