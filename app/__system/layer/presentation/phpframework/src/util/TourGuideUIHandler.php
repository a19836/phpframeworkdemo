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
include_once get_lib("org.phpframework.util.HashCode"); include_once get_lib("org.phpframework.util.web.CookieHandler"); class TourGuideUIHandler { public static function getHtml($pfefc55de, $v37d269c4fa) { $v795c44467b = self::getPageTourGuideId($pfefc55de); $v3b90274617 = isset($_COOKIE["tourguide"]) ? $_COOKIE["tourguide"] : ""; $v6647e86705 = strpos($v3b90274617, "|$v795c44467b|") !== false; if (!$v6647e86705) { $v043ed55cc7 = self::getPageTourGuideOptions($pfefc55de); if ($v043ed55cc7) { $v67ec30e2c2 = "
:host {
	--tourguide-bg-color:#2C2D34;
	--tourguide-bg-color:var(--link-color);
	--tourguide-step-title-background-color:var(--tourguide-bg-color);
	--tourguide-step-title-color:#DFE1ED;
	--tourguide-accent-color:var(--tourguide-bg-color);
	--tourguide-focus-color:var(--tourguide-bg-color);
	--tourguide-bullet-current-color:var(--tourguide-bg-color);
	--tourguide-step-button-next-color:var(--tourguide-bg-color);
	--tourguide-step-button-complete-color:var(--tourguide-bg-color);
	--tourguide-bullet-visited-color:#83889E;
}
.guided-tour-step.active .guided-tour-step-tooltip {
	border-radius:0.2rem;
}
.guided-tour-step.active .guided-tour-step-tooltip .guided-tour-step-tooltip-inner {
	padding-bottom:3.5em;
}
.guided-tour-step.active .guided-tour-step-tooltip .guided-tour-step-tooltip-inner .guided-tour-step-button-close {
	top:0.5em;
	right:0.5em;
	width:1.5em;
	height:1.5em;
	color:var(--tourguide-step-title-color);
}
.guided-tour-step.active .guided-tour-step-tooltip .guided-tour-step-tooltip-inner .guided-tour-step-button-close:hover {
	outline:none;
	color:var(--tourguide-step-button-close-color);
}
.guided-tour-step.active .guided-tour-step-tooltip .guided-tour-step-tooltip-inner .guided-tour-step-content-wrapper {
	font-family:var(--main-font-family);
	font-size:10px;
}
.guided-tour-step.active .guided-tour-step-tooltip .guided-tour-step-tooltip-inner .guided-tour-step-title {
	border-top-left-radius:0.2rem;
	border-top-right-radius:0.2rem;
	color:var(--tourguide-step-title-color);
}
.guided-tour-step.active .guided-tour-step-tooltip .guided-tour-step-tooltip-inner .guided-tour-step-bullets {
	margin-top:-.5em;
}
.guided-tour-step.active .guided-tour-step-tooltip .guided-tour-step-tooltip-inner .guided-tour-step-button-prev, 
  .guided-tour-step.active .guided-tour-step-tooltip .guided-tour-step-tooltip-inner .guided-tour-step-button-next, 
  .guided-tour-step.active .guided-tour-step-tooltip .guided-tour-step-tooltip-inner .guided-tour-step-button-complete {
	margin-top:.5em;
}
.guided-tour-step.active .guided-tour-step-tooltip .guided-tour-step-tooltip-inner .guided-tour-step-actions {
	column-gap:2em;
	justify-content:space-between;
	position:absolute;
	right:1.5em;
	left:1.5em;
	bottom:1.5em;
}
.guided-tour-step.active .guided-tour-step-tooltip .guided-tour-step-tooltip-inner .guided-tour-step-actions .button.complete_tour {
	margin:0 auto;
}
.guided-tour-step.active .guided-tour-step-tooltip .guided-tour-step-tooltip-inner .guided-tour-step-actions .button.secondary {
	padding: 0.5em 1.5em;
	background:var(--tourguide-bullet-visited-color);
	color:#fff;
	border-radius:4px;
}
.guided-tour-step.active .guided-tour-step-tooltip .guided-tour-step-tooltip-inner .guided-tour-step-actions .button.secondary:hover, 
  .guided-tour-step.active .guided-tour-step-tooltip .guided-tour-step-tooltip-inner .guided-tour-step-actions .button.secondary:focus {
	outline-color:#83889E;
	filter:brightness(120%);
}
"; return '
<style>
	.tourguide_hidden_html_node {
		display:none !important;
	}
</style>
<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/jquerymytourguide/lib/tourguide/tourguide.js"></script>
<script language="javascript" type="text/javascript" src="' . $v37d269c4fa . 'vendor/jquerymytourguide/js/MyTourGuide.js"></script>
<script>
	$(function () {
		if (typeof MyTourGuide != "undefined" && typeof MyTourGuide.init == "function") {
			var tourguide_id = "' . $v795c44467b . '";
			var tourguide_cookie = MyJSLib.CookieHandler.getCookie("tourguide");
			var is_tourguide_done = tourguide_cookie ? ("" + tourguide_cookie).indexOf("|" + tourguide_id + "|") !== -1 : false;
			
			if (!is_tourguide_done) {
				var options = ' . json_encode($v043ed55cc7) . ';
				options = $.isPlainObject(options) ? options : {};
				options.css = (options.css ? options.css : "") + \'' . str_replace("\n", "", $v67ec30e2c2) . '\';
				
				//convert steps callbacks into functions
				if ($.isArray(options.steps))
					for (var i = 0, t = options.steps.length; i < t; i++) {
						var step = options.steps[i];
						
						if (step.onStepStart && typeof step.onStepStart == "string")
							eval("options.steps[i].onStepStart = " + step.onStepStart + ";");
						
						if (step.onStepEnd && typeof step.onStepEnd == "string")
							eval("options.steps[i].onStepEnd = " + step.onStepEnd + ";");
					}
				
				if (options.onStart && typeof options.onStart == "string")
					eval("options.onStart = " + options.onStart + ";");
				
				if (options.onStep && typeof options.onStep == "string")
					eval("options.onStep = " + options.onStep + ";");
				
				if (options.onStop && typeof options.onStop == "string")
					eval("options.onStop = " + options.onStop + ";");
				
				if (options.onComplete && typeof options.onComplete == "string")
					eval("options.onComplete = " + options.onComplete + ";");
				
				options["actionHandlers"] = [
					new Tourguide.ActionHandler(
						"dontShowTourAnymore",
						function (event, action, context) {
							/*console.log(event);
							console.log(action);
							console.log(context);
							console.log(tourguide_id);*/
							
							//set cookie so next time does not load this anymore
							tourguide_cookie = (tourguide_cookie ? tourguide_cookie : "|") + tourguide_id + "|";
							MyJSLib.CookieHandler.setCurrentDomainEternalRootSafeCookie("tourguide", tourguide_cookie);
							
							MyTourGuide.stop();
						}
					)
				
				];
				
				//console.log(options);
				MyTourGuide.init(options);
			}
		}
	});
</script>'; } } } public static function getPageTourGuideId($pfefc55de) { switch($pfefc55de) { case "admin/index": $pd75af374 = !empty($_GET["admin_type"]) ? $_GET["admin_type"] : (!empty($_COOKIE["admin_type"]) ? $_COOKIE["admin_type"] : ""); $v795c44467b = HashCode::getHashCode("$pfefc55de?$pd75af374"); break; default: $v795c44467b = HashCode::getHashCode($pfefc55de); } return $v795c44467b; } public static function getPageTourGuideOptions($pfefc55de) { $v5d3813882f = array( "steps" => array(), ); switch($pfefc55de) { case "admin/index": $pd75af374 = !empty($_GET["admin_type"]) ? $_GET["admin_type"] : (!empty($_COOKIE["admin_type"]) ? $_COOKIE["admin_type"] : ""); if ($pd75af374 == "advanced") { $v5d3813882f["steps"][] = array( "selector" => "#top_panel", "content" => "The <strong>Advanced Workspace</strong> comprises a '<strong>Top bar</strong>', a '<strong>Navigator</strong>', and a '<strong>Content Panel</strong>' displaying content corresponding to selections made in the Top bar or Navigator.", ); $v5d3813882f["steps"][] = array( "selector" => "#top_panel > .center", "content" => "The '<strong>Top bar</strong>' displays available projects in the center, enabling you to select a project to work on and access its dashboard and pages below.", ); $v5d3813882f["steps"][] = array( "selector" => "#top_panel > .right", "content" => "On the right side, you'll find various buttons and actions.", ); $v5d3813882f["steps"][] = array( "selector" => "#top_panel > .right > .tools", "content" => "From this icon, you can access various actions and functions, including the option to switch to another workspace, manage users and modules, deploy projects, and more.", ); $v5d3813882f["steps"][] = array( "selector" => "#top_panel > .right > .sub_menu_user", "content" => "In this submenu, explore advanced features such as Tutorials and a Debug console.<br/>Furthermore, you can provide feedback or report bugs via the 'Feedback' menu, or rearrange panels by flipping the <strong>Navigator</strong> to the other side.", ); $v5d3813882f["steps"][] = array( "selector" => "#left_panel", "content" => "In the '<strong>Navigator</strong>', you can access the files and components for your projects/applications.<br/>Each application is built on a <strong>Multi-Layer Structure</strong>, primarily consisting of <strong>Interface</strong>, <strong>Business Logic</strong>, and <strong>Database layers</strong>.", ); $v5d3813882f["steps"][] = array( "selector" => "#left_panel.left_panel_with_tabs .tabs .tab_main_node.tab_main_node_presentation_layers", "content" => "Here, you can browse through the pages within your projects and other interface/presentation components.", "do_not_remove_on_page_load" => true, "skip_if_not_exists" => true, ); $v5d3813882f["steps"][] = array( "selector" => "#left_panel.left_panel_with_tabs .tabs .tab_main_node.tab_main_node_business_logic_layers", "content" => "Here, you can access and view the Business Logic services.", "do_not_remove_on_page_load" => true, "skip_if_not_exists" => true, ); $v5d3813882f["steps"][] = array( "selector" => "#left_panel.left_panel_with_tabs .tabs .tab_main_node.tab_main_node_db_layers", "content" => "Here, you can access and edit the Database tables.", "do_not_remove_on_page_load" => true, "skip_if_not_exists" => true, ); $v5d3813882f["steps"][] = array( "selector" => "#right_panel", "content" => "The '<strong>Content Panel</strong>' displays content corresponding to your selections in the Top Bar or Navigator.<br/><p style=\"text-align:center; font-weight:bold;\">Enjoy...</p>", ); } else if ($pd75af374 == "simple") { $v5d3813882f["steps"][] = array( "selector" => "#top_panel", "content" => "The <strong>Simple Workspace</strong> comprises a '<strong>Top bar</strong>' and a '<strong>Content Panel</strong>' displaying content corresponding to selections made in the Top bar.", ); $v5d3813882f["steps"][] = array( "selector" => "#top_panel > .center", "content" => "The '<strong>Top bar</strong>' displays available projects in the center, enabling you to select a project to work on and access its dashboard and pages below.", ); $v5d3813882f["steps"][] = array( "selector" => "#top_panel > .right", "content" => "On the right side, you'll find various buttons and actions.", ); $v5d3813882f["steps"][] = array( "selector" => "#top_panel > .right > .tools", "content" => "From this icon, you can access various actions and features, including the option to switch to another project or workspace.", ); $v5d3813882f["steps"][] = array( "selector" => "#top_panel > .right > .sub_menu_user", "content" => "In this submenu, discover advanced features like Tutorials and a Debug console. Additionally, you can provide feedback or report bugs through the 'Feedback' menu.", ); $v5d3813882f["steps"][] = array( "selector" => "#right_panel", "content" => "The '<strong>Content Panel</strong>' displays content corresponding to your selections in the Top Bar.<br/><p style=\"text-align:center; font-weight:bold;\">Enjoy...</p>", ); } break; case "admin/admin_home": $v5d3813882f["steps"][] = array( "selector" => ".admin_panel > ul > li a[href='#projs']", "content" => "Panel with your projects.", ); $v5d3813882f["steps"][] = array( "selector" => ".admin_panel > ul > li a[href='#tutorials']", "content" => "Panel with video tutorials to learn how to work with the framework.", ); $v5d3813882f["steps"][] = array( "selector" => ".admin_panel > ul > li:nth-child(3) > a", "content" => "Panel with the framework documentation where you can learn more about it and its structure and architecture.", ); $v5d3813882f["steps"][] = array( "selector" => ".choose_available_project .new_first_project button, .choose_available_project .new_project button", "content" => "Click the blue button to create a new project. A popup will appear for you to enter the project name, then follow the instructions in the popup.", ); $v5d3813882f["steps"][] = array( "selector" => ".choose_available_project > .group", "content" => "Click on a project to select it and go to the its dashboard..." ); break; case "admin/admin_home_project": $v5d8337095f = 'function(current_step) {
					$(".status_message").data("tourguide_hide", "1").hide();
				}'; $pa6d751b3 = 'function(current_step) {
					var popup = $(".status_message");
					
					if (popup.data("tourguide_hide"))
						popup.data("tourguide_hide", null).show();
				}'; $v5d3813882f["steps"][] = array( "selector" => ".admin_panel .project .project_title .sub_menu", "content" => "Click on these icon to open the sub-menu to edit this project details or to preview it as an end-user." ); $v5d3813882f["steps"][] = array( "selector" => ".status_message .create_new_page_message button", "content" => "Click on this button to add a new page.", "skip_if_not_exists" => true, "onStepStart" => $pa6d751b3 ); $v5d3813882f["steps"][] = array( "selector" => ".admin_panel .project_files .pages button", "content" => "Click on this button to add a new page.", "onStepEnd" => $v5d8337095f ); $v5d3813882f["steps"][] = array( "selector" => ".admin_panel .project_files .pages .mytree > li > ul > li.empty_files button", "content" => "Or click on this button to add also new page.", "skip_if_not_exists" => true, "do_not_remove_on_page_load" => true, "onStepEnd" => $v5d8337095f, "step_id" => 1 ); $v5d3813882f["steps"][] = array( "selector" => ".admin_panel .project_files .pages .mytree", "content" => "The created pages will appear in this section.<br/>Click on a page to open the page editor and design your interface.", "onStepEnd" => $v5d8337095f, "step_id" => 1 ); $v5d3813882f["onStop"] = $pa6d751b3; break; case "presentation/edit_entity": $pd75af374 = !empty($_GET["edit_entity_type"]) ? $_GET["edit_entity_type"] : (!empty($_COOKIE["edit_entity_type"]) ? $_COOKIE["edit_entity_type"] : ""); $pd75af374 = !empty($pd75af374) ? strtolower($pd75af374) : "simple"; if ($pd75af374 == "simple") { $v5d8337095f = 'function(current_step) {
						$(".myfancypopup.choose_available_template_popup:visible, .popup_overlay:visible").data("tourguide_hide", "1").addClass("tourguide_hidden_html_node");
					}'; $pa6d751b3 = 'function(options) { 
						var popup = $(".myfancypopup.choose_available_template_popup, .popup_overlay");
						
						if (popup.data("tourguide_hide"))
							popup.data("tourguide_hide", null).removeClass("tourguide_hidden_html_node");
					}'; $v5d3813882f["steps"][] = array( "selector" => ".top_bar", "content" => "The top bar displays the current page name on the left side, while featuring various buttons and actions on the right side.", "onStepEnd" => $v5d8337095f, ); $v5d3813882f["steps"][] = array( "selector" => ".top_bar li.sub_menu", "content" => "Within this submenu, you'll find more advanced actions, including the ability to activate the auto-save feature, which is currently disabled. Furthermore, you can rearrange panels below by moving the <strong>Canvas</strong> area to the other side.", "onStepEnd" => $v5d8337095f, ); $v5d3813882f["steps"][] = array( "selector" => ".code_layout_ui_editor > .layout-ui-editor > .template-widgets", "content" => "This is the <strong>Canvas</strong> area where you can view the selected template and position visual components (HTML elements - <strong>Widgets</strong>) to design your page and visualize its appearance.<br/>Just drag and drop Widgets into the selected template <strong>Regions</strong> and see how easy it is to implement your vision.<br/>By clicking on the Widgets set here, a properties panel will open, allowing you to edit the widget properties.", "onStepEnd" => $v5d8337095f, ); $v5d3813882f["steps"][] = array( "selector" => ".code_layout_ui_editor > .layout-ui-editor > .menu-widgets", "content" => "This is the <strong>Widgets</strong> Panel where you can drag and drop elements into the Canvas area.", "onStepEnd" => $v5d8337095f, ); $v5d3813882f["steps"][] = array( "selector" => ".code_layout_ui_editor > .layout-ui-editor > .options > .options-left", "content" => "This panel includes buttons to switch between different side panels.", "onStepEnd" => $v5d8337095f, ); $v5d3813882f["steps"][] = array( "selector" => ".code_layout_ui_editor > .layout-ui-editor > .options > .options-left > .option.show-widgets", "content" => "This button indicates the currently selected side panel, displaying HTML elements (Widgets) available for drag-and-drop onto the Canvas area.", "onStepEnd" => $v5d8337095f, ); $v5d3813882f["steps"][] = array( "selector" => ".code_layout_ui_editor > .layout-ui-editor > .options > .options-left > .option.show-layers", "content" => "This button shows a side panel with the HTML elements' structure in the Canvas area, in a Tree layout.<br/>Keep in mind, some HTML elements may be hidden in the Canvas area and can only be accessed through the Layers panel.", "onStepEnd" => $v5d8337095f, ); $v5d3813882f["steps"][] = array( "selector" => ".code_layout_ui_editor > .layout-ui-editor > .options > .options-left > .option.show-right-container-dbs", "content" => "This button showcases the Database Tables, allowing you to edit and drag them onto your Canvas area to incorporate dynamic data into your HTML.", "do_not_remove_on_page_load" => true, "skip_if_not_exists" => true, "onStepStart" => 'function(current_step) {
							if ($(".code_layout_ui_editor > .layout-ui-editor.with_right_container_dbs").length == 0) {
								MyTourGuide.options.steps.splice(current_step.index, 1);
								MyTourGuide.prepareTourguide(current_step.index);
								
								return false;
							}
						}', "onStepEnd" => $v5d8337095f, ); $v5d3813882f["steps"][] = array( "selector" => ".code_layout_ui_editor > .layout-ui-editor > .options > .options-left > .option.choose-template", "content" => "This button enables you to switch to another template.", "do_not_remove_on_page_load" => true, "onStepEnd" => $v5d8337095f, ); $v5d3813882f["steps"][] = array( "selector" => ".code_layout_ui_editor > .layout-ui-editor > .options > .options-left > .option.show-layout-options", "content" => "This button reveals various settings to toggle the visibility of specific HTML elements (Widgets) in the Canvas area, such as 'script', 'style' or other elements.", "onStepEnd" => $v5d8337095f, ); $v5d3813882f["steps"][] = array( "selector" => ".entity_obj > .regions_blocks_includes_settings > .settings_header", "content" => "At the bottom, you'll find the '<strong>Main Settings</strong>' panel, housing settings for this page.<br/>Here, you can modify the HTML for the chosen template, add new CSS or JavaScript files, and edit or create resources.", "onStepEnd" => $v5d8337095f, "placement" => "bottom-end", ); $v5d3813882f["steps"][] = array( "selector" => ".code_layout_ui_editor > .layout-ui-editor > .options > .options-left > .option.choose-template", "content" => "Last but not least, let's click on this button to open the popup displaying available templates.", "do_not_remove_on_page_load" => true, "onStepEnd" => $v5d8337095f, ); $v5d3813882f["steps"][] = array( "selector" => ".myfancypopup.choose_available_template_popup .html_editor", "content" => "Within this popup, you can select how you want to design your page.<br/>Choose this option if you want to design it from scratch, designing all HTML elements.</p>", "do_not_remove_on_page_load" => true, "skip_if_not_exists" => true, "onStepEnd" => $pa6d751b3, ); $v5d3813882f["steps"][] = array( "selector" => ".myfancypopup.choose_available_template_popup .template_editor", "content" => "Or you can select a template to start creating your page, which is the recommended and easiest option.<br/><p style=\"text-align:center; font-weight:bold;\">Enjoy...</p>", "do_not_remove_on_page_load" => true, "skip_if_not_exists" => true, "onStepEnd" => $pa6d751b3, ); $v5d3813882f["onStop"] = $pa6d751b3; } else { } break; } if ($v5d3813882f["steps"]) for ($v43dd7d0051 = 0, $pc37695cb = count($v5d3813882f["steps"]); $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v6602edb5ab = $v5d3813882f["steps"][$v43dd7d0051]; if (!isset($v6602edb5ab["title"])) $v6602edb5ab["title"] = "Tooltip Tour Guide"; if ($v43dd7d0051 + 1 < $pc37695cb) $v6602edb5ab["actions"] = array( array( "label" => "Disable Tour", "action" => "dontShowTourAnymore", "class" => "secondary", "title" => "Do NOT show this Tour anymore.", ), array( "label" => "Skip Tour", "action" => "stop", "primary" => true, "title" => "Skip now this Tour and show it next time.", ) ); else $v6602edb5ab["actions"] = array( array( "label" => "Complete Tour and disable it for the next time", "action" => "dontShowTourAnymore", "class" => "secondary complete_tour", "title" => "End Tour and do NOT show it anymore.", ), ); $v5d3813882f["steps"][$v43dd7d0051] = $v6602edb5ab; } return $v5d3813882f; } } ?>
