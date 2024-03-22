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
include_once $EVC->getUtilPath("AdminMenuUIHandler"); include_once $EVC->getUtilPath("TourGuideUIHandler"); if (!$is_admin_ui_advanced_allowed) { echo '<script>
		alert("You don\'t have permission to access this Workspace!");
		document.location="' . $project_url_prefix . 'auth/logout";
	</script>'; die(); } $logged_name = $UserAuthenticationHandler->auth["user_data"]["name"] ? $UserAuthenticationHandler->auth["user_data"]["name"] : $UserAuthenticationHandler->auth["user_data"]["username"]; $logged_name_initials = explode(" ", $logged_name); $logged_name_initials = strtoupper(substr($logged_name_initials[0], 0, 1) . substr($logged_name_initials[1], 0, 1)); $filter_by_layout_url_query = $filter_by_layout ? "&filter_by_layout=$filter_by_layout&filter_by_layout_permission=$filter_by_layout_permission" : ""; $admin_home_project_page_url = $project_url_prefix . "admin/admin_home_project?filter_by_layout=#filter_by_layout#"; $admin_home_projects_page_url = $project_url_prefix . "admin/admin_home?selected_layout_project=$filter_by_layout"; $head = AdminMenuUIHandler::getHeader($project_url_prefix, $project_common_url_prefix); $head .= '
<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/admin_advanced.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/admin_advanced.js"></script>

<script>
var path_to_filter = "' . $filter_by_layout . '";
var admin_home_project_page_url = "' . $admin_home_project_page_url . '";
var admin_home_projects_page_url = "' . $admin_home_projects_page_url . '";
</script>'; $main_content = AdminMenuUIHandler::getContextMenus($exists_db_drivers, $get_store_programs_url); $main_content .= '
	<div id="top_panel">
		<ul class="left">
			<li class="logo"><a href="' . $bloxtor_home_page_url . '" target="bloxtor_homepage"></a></li>
		</ul>
		<ul class="center">
			<li class="sub_menu filter_by_layout" data-title="Filter by project" current_selected_project="' . $filter_by_layout . '">
				<!--label>Project: </label-->
				<span class="selected_project" onClick="openFilterByLayoutSubMenu(this)">
					<span>' . ($filter_by_layout ? basename($filter_by_layout) : 'All Projects') . '</span>
					<i class="icon dropdown_arrow"></i>
				</span>
				
				<ul>
					<div class="triangle_up"></div>
					
					<li class="scroll">
						<ul>
							<li class="label"><a>Select a Project:</a></li>
							<li class="all_projects' . ($filter_by_layout ? '' : ' selected') . '"><a value="" onClick="filterByLayout(this)"><i class="icon all_projects"></i> <span>All Projects</span></a></li>
							<!--li class="separator"></li-->'; $selected_project_name = ""; $is_single_presentation_layer = count($presentation_projects_by_layer_label_and_folders) == 1; foreach ($presentation_projects_by_layer_label_and_folders as $layer_label => $projs) { if (!$is_single_presentation_layer) $main_content .= '	<li class="projects_group">
								<a><i class="icon project_folder"></i> <span>' . $layer_label . '</span></a>
								<ul>'; $layer_bean_folder_name = $presentation_bean_folder_name_by_layer_label[$layer_label]; $main_content .= getProjectsHtml($projs, $filter_by_layout, $layer_bean_folder_name . "/" . $EVC->getCommonProjectName()); if (!$is_single_presentation_layer) $main_content .= '		</ul>
							</li>'; if ($filter_by_layout && $presentation_projects_by_layer_label[$layer_label][$filter_by_layout]) $selected_project_name = $presentation_projects_by_layer_label[$layer_label][$filter_by_layout]; } $common_project_selected = $selected_project_name == $EVC->getCommonProjectName(); foreach ($non_projects_layout_types as $lname => $lid) $main_content .= '		<li class="project' . ($filter_by_layout == $lname ? ' selected' : '') . '">
								<a value="' . $lname . '" onClick="filterByLayout(this)"><i class="icon project"></i> <span>' . $lname . '</span></a>
							</li>'; $main_content .= '		</ul>
					</li>	
				</ul>
				
				<!--span class="icon project" onClick="chooseAvailableProject(\'' . $project_url_prefix . 'admin/choose_available_project?redirect_path=admin&popup=1\');" data-title="' . ($selected_project_name ? 'Selected Project: \'' . $selected_project_name . '\'. ' : '') . 'Please click here to choose another project."></span-->
				<!--a class="got_to_project_home" onClick="goTo(this, \'url\', event)" url="' . $project_url_prefix . 'admin/admin_home_project?filter_by_layout=' . $filter_by_layout . '" data-title="Go to project homepage"><span class="icon project_home"></span></a-->
			</li>
			' . ($filter_by_layout ? '
			<li class="separator">|</li>
			<li class="pages link" onClick="goTo(this, \'url\', event)" url="' . str_replace("#filter_by_layout#", $filter_by_layout, $admin_home_project_page_url) . '">Pages</li>
			' : '') . '
		</ul>
		<ul class="right">
			<li class="icon go_back" onClick="goBack()" data-title="Go Back"></li>
			<li class="icon go_forward" onClick="goForward()" data-title="Go Forward"></li>
			<li class="separator">|</li>
			
			' . ($is_flush_cache_allowed ? '<li class="icon flush_cache" data-title="Flush Cache" onClick="flushCacheFromAdmin(\'' . $project_url_prefix . 'admin/flush_cache\')"></li>' : '') . '
			<li class="icon refresh" onClick="refreshIframe()" data-title="Refresh"></li>
			<li class="icon full_screen" data-title="Toggle Full Screen" onClick="toggleFullScreen(this)"></li>
			<li class="separator">|</li>
			
			<li class="icon tools" onClick="chooseAvailableTool(\'' . "{$project_url_prefix}admin/choose_available_tool?filter_by_layout=$filter_by_layout&popup=1" . '\')" data-title="Tools"></li>
			<li class="icon home" data-title="Home" onClick="goTo(this, \'url\', event)" url="' . $admin_home_projects_page_url . '"></li>
			<li class="separator">|</li>
			
			<li class="sub_menu sub_menu_user" data-title="Others" onClick="openSubmenu(this)">
				<span class="logged_user_icon">' . $logged_name_initials . '</span>
				<i class="icon dropdown_arrow"></i>
				<!--i class="icon user"></i-->
				
				<ul>
					<div class="triangle_up"></div>
					
					<li class="login_info" title="Logged as \'' . $logged_name . '\' user."><a><span class="logged_user_icon">' . $logged_name_initials . '</span> Logged in as "' . $logged_name . '"</a></li>
					<li class="separator"></li>
					<li class="toggle_theme_layout" title="Toggle Theme"><a onClick="toggleThemeLayout(this)"><i class="icon toggle_theme_layout"></i> <span>Show dark theme</span></a></li>
					<li class="toggle_main_navigator_side" title="Toggle Navigator Side"><a onClick="toggleNavigatorSide(this)"><i class="icon toggle_main_navigator_side"></i> <span>Show navigator on right side</span></a></li>
					<li class="separator"></li>
					<li class="console" title="Logs Console"><a onClick="openConsole(\'' . $project_url_prefix . 'admin/logs_console?popup=1\', event);"><i class="icon logs_console"></i> Logs Console</a></li>
					<!--li class="question" title="Tutorials - How To?"><a onClick="chooseAvailableTutorial(\'' . $project_url_prefix . 'admin/choose_available_tutorial?popup=1\', event);"><i class="icon question"></i> Tutorials - How To?</a></li-->
					<li class="question" title="Tutorials - How To?"><a onClick="openOnlineTutorialsPopup(\'' . $online_tutorials_url_prefix . '\', event);"><i class="icon question"></i> Tutorials - How To?</a></li>
					<li class="info" title="About"><a onClick="goTo(this, \'url\', event)" url="' . $project_url_prefix . 'admin/about"><i class="icon info"></i> About</a></li>
					<li class="feedback" title="Feedback"><a onClick="goToPopup(this, \'url\', event, \'with_title\')" url="' . $project_url_prefix . 'admin/feedback?popup=1"><i class="icon chat"></i> Feedback</a></li>
					<li class="separator"></li>
					<li class="logout" title="Logout"><a onClick="document.location=this.getAttribute(\'logout_url\')" logout_url="' . $project_url_prefix . 'auth/logout"><i class="icon logout"></i> Logout</a></li>
				</ul>
			</li>
			
		</ul>
	</div>

	<div id="left_panel" class="' . $tree_layout . ' ' . $advanced_level . ($common_project_selected ? ' common_project_selected' : '') . '">
		<div class="icon sub_menu" onClick="openSubmenu(this)">
			<ul>
				<div class="triangle_up"></div>
				
				<li class="toggle_advanced_level"><a onClick="toggleAdvancedLevel(this)"><i class="icon enable"></i> <span>Show advanced items</span></a></li>
				<li class="toggle_tree_layout"><a onClick="toggleTreeLayout(this)"><i class="icon toggle_tree_layout"></i> <span>Show vertical layout</span></a></li>
			</ul>
		</div>
		
		<div class="file_tree_root"></div>
		<div id="file_tree" class="mytree hidden' . ($tree_layout == "left_panel_without_tabs" ? " scroll" : "") . '">
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
	<div id="right_panel">'; $iframe_url = $default_page ? $default_page : ( $project_url_prefix . 'admin/' . ($filter_by_layout ? "admin_home_project?$filter_by_layout_url_query" : "admin_home?selected_layout_project=$filter_by_layout") ); $main_content .= '
		<iframe src="' . $iframe_url . '"></iframe>
		<div class="iframe_overlay">
			<div class="iframe_loading">Loading...</div>
		</div>
	</div>'; $main_content .= TourGuideUIHandler::getHtml($entity, $project_common_url_prefix); function getProjectsHtml($v12ed481092, $pb154d332, $v02ea8e5737 = null) { $pf8ed4912 = ""; if (is_array($v12ed481092)) { if (isset($v12ed481092[$v02ea8e5737])) $pf8ed4912 .= '<li class="project project_common' . ($pb154d332 == $v02ea8e5737 ? ' selected' : '') . '">
						<a value="' . $v02ea8e5737 . '" onClick="filterByLayout(this)"><i class="icon project"></i> <span>' . $v12ed481092[$v02ea8e5737] . '</span></a>
					</li>'; foreach ($v12ed481092 as $pcfd27d54 => $v5c37a7b23d) { if (is_array($v5c37a7b23d)) $pf8ed4912 .= '<li class="projects_group">
							<a><i class="icon project_folder"></i> <span>' . $pcfd27d54 . '</span></a>
							<ul>
							' . getProjectsHtml($v5c37a7b23d, $pb154d332) . '
							</ul>
						</li>'; else if ($pcfd27d54 != $v02ea8e5737) $pf8ed4912 .= '<li class="project' . ($pb154d332 == $pcfd27d54 ? ' selected' : '') . '">
							<a value="' . $pcfd27d54 . '" onClick="filterByLayout(this)"><i class="icon project"></i> <span>' . $v5c37a7b23d . '</span></a>
						</li>'; } } return $pf8ed4912; } ?>
