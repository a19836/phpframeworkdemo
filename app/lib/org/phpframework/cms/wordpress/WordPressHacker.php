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

include_once get_lib("org.phpframework.util.web.html.HtmlStringHandler"); class WordPressHacker { private $pe3a7d774; private $pd282fe9b; private $pabd7b9c4; private $v4b6875ca7c; private $pf2ba5feb = null; public function __construct($pe3a7d774, $pd282fe9b, $pabd7b9c4, $v4b6875ca7c = true) { $this->pe3a7d774 = trim($pe3a7d774); $this->pd282fe9b = trim($pd282fe9b); $this->pabd7b9c4 = trim($pabd7b9c4); $this->v4b6875ca7c = $v4b6875ca7c; $this->pe3a7d774 .= substr($this->pe3a7d774, -1) == "/" ? "" : "/"; if (!file_exists($this->pe3a7d774)) launch_exception(new Exception("WordPress installation doesn't exists!")); } public function callFile($pb12450da) { global $phpframework_wp_request_uri; $phpframework_wp_request_uri = $this->pd282fe9b; $this->f6f0364339f($pb12450da); $v9a84a79e2e = $this->pe3a7d774 . $pb12450da; if (file_exists($v9a84a79e2e)) { ob_start(); include $v9a84a79e2e; $pf8ed4912 = ob_get_contents(); ob_end_clean(); } else $pf8ed4912 = self::get404PageHtml(); $this->f15ea87f977(); return $pf8ed4912; } public function getContent($pca670fe1, $v5d3813882f) { global $phpframework_template, $phpframework_options, $phpframework_results, $current_phpframework_result_key, $phpframework_wp_request_uri, $show_admin_bar; $pee4c7870 = array(); $phpframework_template = self::getPHPFrameworkFromOptions($v5d3813882f); $phpframework_options = $v5d3813882f; $phpframework_results = array(); $current_phpframework_result_key = "theme_content"; $phpframework_wp_request_uri = $this->pd282fe9b; $this->f6f0364339f($pca670fe1); ob_start(); $show_admin_bar = false; include $this->pe3a7d774 . "index.php"; $pe076cb34 = ob_get_contents(); $v4a3fd2ed6b = ucwords(str_replace('_', ' ', $current_phpframework_result_key)); $pe076cb34 = "<!-- phpframework:template:region: \"Before $v4a3fd2ed6b\" -->$pe076cb34<!-- phpframework:template:region: \"After $v4a3fd2ed6b\" -->"; $phpframework_results[$current_phpframework_result_key] .= $pe076cb34; $phpframework_results["full_page_html"] .= $pe076cb34; ob_end_clean(); if ($v5d3813882f) { $phpframework_options = null; $pee4c7870 = $this->executeOptions($v5d3813882f, $phpframework_results); } $this->f15ea87f977(); $phpframework_results = $current_phpframework_result_key = null; return $pee4c7870; } public static function getPHPFrameworkFromOptions($v5d3813882f) { if ($v5d3813882f && $v5d3813882f["phpframework_template"]) return $v5d3813882f["phpframework_template"] === true ? "phpframework" : $v5d3813882f["phpframework_template"]; return null; } public function executeOptions($v5d3813882f, $pee4c7870 = null) { $v909cb02e1c = $pee4c7870 ? $pee4c7870 : array(); if ($v5d3813882f) foreach ($v5d3813882f as $pe5c5e2fe => $v956913c90f) if ($v956913c90f) switch($pe5c5e2fe) { case "header": $v909cb02e1c["header"] = $v909cb02e1c["header"] ? $v909cb02e1c["header"] : self::getTemplateHeaderHtml(); break; case "footer": $v909cb02e1c["footer"] = $v909cb02e1c["footer"] ? $v909cb02e1c["footer"] : self::getTemplateFooterHtml(); break; case "post": $v7ec4cb63a4 = is_array($v956913c90f["comments"]) && $v956913c90f["comments"]["pretty"]; $v82cefa67d0 = is_array($v956913c90f["comments"]) && $v956913c90f["comments"]["raw"]; $v6e438db855 = $GLOBALS["withcomments"] = "1"; if ( have_posts() ) { while ( have_posts() ) { the_post(); $v6af1f205e1 = array(); if ($v956913c90f["title"]) $v6af1f205e1["title"] = self::getCurrentPostTitleHtml(); if ($v956913c90f["content"]) $v6af1f205e1["content"] = self::getCurrentPostContentHtml(); if ($v956913c90f["comments"]) { if ($v82cefa67d0 || !$v7ec4cb63a4) $v6af1f205e1["raw_comments"] = self::getCurrentPostCommentsWithAddFormRawHtml(); if ($v7ec4cb63a4) { $pbe35f7d1 = false; $pbbcb2d04 = null; if (is_array($v956913c90f["comments"]["pretty"])) { $pbe35f7d1 = $v956913c90f["comments"]["pretty"]["comments_from_theme"]; $pbbcb2d04 = self::getCurrentPostId(); } $v6af1f205e1["pretty_comments"] = $pbe35f7d1 && $pbbcb2d04 && $v909cb02e1c["theme_comments"] && $v909cb02e1c["theme_comments"][$pbbcb2d04] ? $v909cb02e1c["theme_comments"][$pbbcb2d04][0] : self::getCurrentPostCommentsWithAddFormPrettyHtml(); } } $v909cb02e1c["posts"][] = $v6af1f205e1; } } else { $v6af1f205e1 = array(); if ($v956913c90f["title"]) $v6af1f205e1["title"] = self::getCurrentPostTitleHtml(); if ($v956913c90f["content"]) $v6af1f205e1["content"] = self::getCurrentPostContentHtml(); if ($v956913c90f["comments"]) { if ($v82cefa67d0 || !$v7ec4cb63a4) $v6af1f205e1["raw_comments"] = self::getCurrentPostCommentsWithAddFormRawHtml(); if ($v7ec4cb63a4) { $pbe35f7d1 = false; $pbbcb2d04 = null; if (is_array($v956913c90f["comments"]["pretty"])) { $pbe35f7d1 = $v956913c90f["comments"]["pretty"]["comments_from_theme"]; $pbbcb2d04 = self::getCurrentPostId(); } $v6af1f205e1["pretty_comments"] = $pbe35f7d1 && $pbbcb2d04 && $v909cb02e1c["theme_comments"] && $v909cb02e1c["theme_comments"][$pbbcb2d04] ? $v909cb02e1c["theme_comments"][$pbbcb2d04][0] : self::getCurrentPostCommentsWithAddFormPrettyHtml(); } } $v909cb02e1c["posts"][] = $v6af1f205e1; } break; case "default_menu": $pf1d4764b = is_array($v956913c90f) ? $v956913c90f["menu_from_theme"] : false; $v909cb02e1c["default_menu"] = $pf1d4764b && $v909cb02e1c["theme_menus"] && $v909cb02e1c["theme_menus"][0] ? $v909cb02e1c["theme_menus"][0][0] : self::getDefaultMenuHtml(); break; case "menu_name": $pf1d4764b = false; if (is_array($v956913c90f)) { $pf1d4764b = $v956913c90f["menu_from_theme"]; $v956913c90f = $v956913c90f["name"]; } $v909cb02e1c["menu"] = $pf1d4764b && $v909cb02e1c["theme_menus"] && $v909cb02e1c["theme_menus"][$v956913c90f] ? $v909cb02e1c["theme_menus"][$v956913c90f][0] : self::getMenuHtml($v956913c90f); break; case "menus_name": $v956913c90f = is_array($v956913c90f) ? $v956913c90f : array($v956913c90f); foreach ($v956913c90f as $v134495e57e) { $pf1d4764b = false; if (is_array($v134495e57e)) { $pf1d4764b = $v134495e57e["menu_from_theme"]; $v134495e57e = $v134495e57e["name"]; } $v909cb02e1c["menus"][] = $pf1d4764b && $v909cb02e1c["theme_menus"] && $v909cb02e1c["theme_menus"][$v134495e57e] ? $v909cb02e1c["theme_menus"][$v134495e57e][0] : self::getMenuHtml($v134495e57e); } break; case "menu_location_name": $pf1d4764b = false; if (is_array($v956913c90f)) { $pf1d4764b = $v956913c90f["menu_from_theme"]; $v956913c90f = $v956913c90f["name"]; } if ($pf1d4764b) { $v1228775e53 = self::getMenuIdByLocation($v956913c90f); $v909cb02e1c["menu_location"] = $v1228775e53 && $v909cb02e1c["theme_menus"] && $v909cb02e1c["theme_menus"][$v1228775e53] ? $v909cb02e1c["theme_menus"][$v1228775e53][0] : self::getMenuHtml($v1228775e53); } else $v909cb02e1c["menu_location"] = self::getMenuHtmlByLocation($v956913c90f); break; case "menu_locations_name": $v956913c90f = is_array($v956913c90f) ? $v956913c90f : array($v956913c90f); foreach ($v956913c90f as $v14c558781e) { $pf1d4764b = false; if (is_array($v14c558781e)) { $pf1d4764b = $v14c558781e["menu_from_theme"]; $v14c558781e = $v14c558781e["name"]; } if ($pf1d4764b) { $v1228775e53 = self::getMenuIdByLocation($v14c558781e); $v909cb02e1c["menu_locations"][] = $v1228775e53 && $v909cb02e1c["theme_menus"] && $v909cb02e1c["theme_menus"][$v1228775e53] ? $v909cb02e1c["theme_menus"][$v1228775e53][0] : self::getMenuHtml($v1228775e53); } else $v909cb02e1c["menu_locations"][] = self::getMenuHtmlByLocation($v14c558781e); } break; case "default_side_bar": $pdea65910 = is_array($v956913c90f) ? $v956913c90f["side_bar_from_theme"] : false; $v909cb02e1c["default_side_bar"] = $pdea65910 && $v909cb02e1c["theme_side_bars"] && $v909cb02e1c["theme_side_bars"][0] ? $v909cb02e1c["theme_side_bars"][0][0] : self::getDefaultSideBarHtml(); break; case "side_bar_name": $pdea65910 = false; if (is_array($v956913c90f)) { $pdea65910 = $v956913c90f["side_bar_from_theme"]; $v956913c90f = $v956913c90f["name"]; } $v909cb02e1c["side_bar"] = $pdea65910 && $v909cb02e1c["theme_side_bars"] && $v909cb02e1c["theme_side_bars"][$v956913c90f] ? $v909cb02e1c["theme_side_bars"][$v956913c90f][0] : self::getSideBarHtml($v956913c90f); break; case "side_bars_name": $v956913c90f = is_array($v956913c90f) ? $v956913c90f : array($v956913c90f); foreach ($v956913c90f as $v2fda65266d) { $pdea65910 = false; if (is_array($v2fda65266d)) { $pdea65910 = $v2fda65266d["side_bar_from_theme"]; $v2fda65266d = $v2fda65266d["name"]; } $v909cb02e1c["side_bars"][] = $pdea65910 && $v909cb02e1c["theme_side_bars"] && $v909cb02e1c["theme_side_bars"][$v2fda65266d] ? $v909cb02e1c["theme_side_bars"][$v2fda65266d][0] : self::getSideBarHtml($v2fda65266d); } break; case "widget_options": if ($this->v4b6875ca7c) { if ($v956913c90f["widget_id"]) $v909cb02e1c["widget_options"] = self::getWidgetControlOptionsById($v956913c90f["widget_id"], $v956913c90f["widget_instance"]); else if ($v956913c90f["widget_class"]) $v909cb02e1c["widget_options"] = self::getWidgetControlOptionsByClass($v956913c90f["widget_class"], $v956913c90f["widget_instance"]); } break; case "widgets_options": if ($this->v4b6875ca7c) { $v956913c90f = is_array($v956913c90f) ? $v956913c90f : array($v956913c90f); foreach ($v956913c90f as $v86aa814964) { if ($v86aa814964["widget_id"]) $v909cb02e1c["widgets_options"][] = self::getWidgetControlOptionsById($v86aa814964["widget_id"], $v86aa814964["widget_instance"]); else if ($v86aa814964["widget_class"]) $v909cb02e1c["widgets_options"][] = self::getWidgetControlOptionsByClass($v86aa814964["widget_class"], $v86aa814964["widget_instance"]); } } break; case "widget_display": if ($v956913c90f["widget_id"]) $v909cb02e1c["widget"] = self::getWidgetHtmlById($v956913c90f["widget_id"], $v956913c90f["widget_instance"], $v956913c90f["widget_args"]); else if ($v956913c90f["widget_class"]) $v909cb02e1c["widget"] = self::getWidgetHtmlByClass($v956913c90f["widget_class"], $v956913c90f["widget_instance"], $v956913c90f["widget_args"]); break; case "widgets_display": $v956913c90f = is_array($v956913c90f) ? $v956913c90f : array($v956913c90f); foreach ($v956913c90f as $v86aa814964) { if ($v86aa814964["widget_id"]) $v909cb02e1c["widgets"][] = self::getWidgetHtmlById($v86aa814964["widget_id"], $v86aa814964["widget_instance"], $v86aa814964["widget_args"]); else if ($v86aa814964["widget_class"]) $v909cb02e1c["widgets"][] = self::getWidgetHtmlByClass($v86aa814964["widget_class"], $v86aa814964["widget_instance"], $v86aa814964["widget_args"]); } break; case "pages_list": $v909cb02e1c["pages_list"] = self::getPagesListHtml( is_array($v956913c90f) ? $v956913c90f["args"] : null ); break; case "functions": $v909cb02e1c["functions"] = $this->executeFunctions($v956913c90f); break; } return $v909cb02e1c; } public function executeFunctions($v12efdd30d7) { $pee4c7870 = array(); if ($v12efdd30d7 && $this->v4b6875ca7c) { $v12efdd30d7 = is_array($v12efdd30d7) ? $v12efdd30d7 : array($v12efdd30d7); $v22ec8f2b0d = get_class(); foreach ($v12efdd30d7 as $v2f4e66e00a) { if ($v2f4e66e00a && method_exists($v22ec8f2b0d, $v2f4e66e00a["name"])) { $v15a78445b6 = array($v22ec8f2b0d, $v2f4e66e00a["name"]); if (is_array($v2f4e66e00a["args"])) $pee4c7870[] = call_user_func_array($v15a78445b6, $v2f4e66e00a["args"]); else $pee4c7870[] = call_user_func($v15a78445b6, $v2f4e66e00a["args"]); } else $pee4c7870[] = null; } } return $pee4c7870; } private function f6f0364339f($pca670fe1) { if (!$this->pf2ba5feb) { $this->pf2ba5feb = array(); $pfb662071 = array( "REQUEST_URI", "REDIRECT_SCRIPT_URI", "REDIRECT_SCRIPT_URL", "REDIRECT_URL", "SCRIPT_URI", "SCRIPT_URL", ); $pfc5312f4 = $_SERVER["REQUEST_URI"]; $pd282fe9b = $this->pd282fe9b . preg_replace("/^\/+/", "", $pca670fe1); $pbd1bc7b0 = strpos($pfc5312f4, "?"); $pfc5312f4 = $pbd1bc7b0 !== false ? substr($pfc5312f4, 0, $pbd1bc7b0) : $pfc5312f4; $pbd1bc7b0 = strpos($pfc5312f4, "#"); $pfc5312f4 = $pbd1bc7b0 !== false ? substr($pfc5312f4, 0, $pbd1bc7b0) : $pfc5312f4; foreach ($pfb662071 as $v342a134247) if (isset($_SERVER[$v342a134247])) { $this->pf2ba5feb[$v342a134247] = $_SERVER[$v342a134247]; $_SERVER[$v342a134247] = str_replace($pfc5312f4, $pd282fe9b, $_SERVER[$v342a134247]); $_SERVER[$v342a134247] = preg_replace("/(\?|&)(phpframework_block_id|wp_url|wp_file)=([^&]*)/", "\${1}", $_SERVER[$v342a134247]); $_SERVER[$v342a134247] = preg_replace("/&+/", "&", $_SERVER[$v342a134247]); $_SERVER[$v342a134247] = preg_replace("/\?&/", "?", $_SERVER[$v342a134247]); $_SERVER[$v342a134247] = preg_replace("/[&\?]+$/", "", $_SERVER[$v342a134247]); $_SERVER[$v342a134247] = preg_replace("/[&\?]+#/", "#", $_SERVER[$v342a134247]); } $pfb662071 = array( "SCRIPT_FILENAME", "SCRIPT_NAME", "PHP_SELF", ); $pedfbee80 = $_SERVER["SCRIPT_NAME"]; $pabd7b9c4 = $this->pabd7b9c4; foreach ($pfb662071 as $v342a134247) if (isset($_SERVER[$v342a134247])) { $this->pf2ba5feb[$v342a134247] = $_SERVER[$v342a134247]; $_SERVER[$v342a134247] = str_replace($pedfbee80, $pabd7b9c4, $_SERVER[$v342a134247]); } } } private function f15ea87f977() { if ($this->pf2ba5feb) foreach ($this->pf2ba5feb as $pe5c5e2fe => $v956913c90f) $_SERVER[$pe5c5e2fe] = $this->pf2ba5feb[$pe5c5e2fe]; $this->pf2ba5feb = null; } public static function getTemplateHeaderHtml() { ob_start(); get_header(); $pf8ed4912 = ob_get_contents(); ob_end_clean(); return $pf8ed4912; } public static function getTemplateFooterHtml() { ob_start(); get_footer(); $pf8ed4912 = ob_get_contents(); ob_end_clean(); return $pf8ed4912; } public static function getCurrentPostId() { global $post; return $post->ID; } public static function getCurrentPostTitleHtml() { return the_title('', '', false); } public static function getCurrentPostContentHtml() { ob_start(); the_content(); $pf8ed4912 = ob_get_contents(); ob_end_clean(); return $pf8ed4912; } public static function getPostCommentsHtml($pbbcb2d04) { $pf8ed4912 = ""; $pcc2fe66c = get_comments( array('post_id' => $pbbcb2d04) ); if ($pcc2fe66c) { $pc95b529f = strtolower(get_settings("comment_order")); $pf8ed4912 = wp_list_comments(array( 'echo' => false, 'reverse_top_level' => $pc95b529f == "asc" ), $pcc2fe66c); } return $pf8ed4912; } public static function getNewCommentForm() { ob_start(); comment_form(); $pf8ed4912 = ob_get_contents(); ob_end_clean(); return $pf8ed4912; } public static function isNewCommentFormAllowed() { return comments_open() || pings_open(); } public static function getCurrentPostCommentsHtml() { global $post; return self::getPostCommentsHtml($post->ID); } public static function getCurrentPostCommentsWithAddFormRawHtml() { $pf8ed4912 = self::getCurrentPostCommentsHtml(); if (self::isNewCommentFormAllowed()) $pf8ed4912 .= self::getNewCommentForm(); return $pf8ed4912; } public static function getCurrentPostCommentsWithAddFormPrettyHtml() { ob_start(); comments_template(); $pf8ed4912 = ob_get_contents(); ob_end_clean(); return $pf8ed4912; } public static function getMenuHtml($v134495e57e) { $pdf6c365c = wp_get_nav_menu_object($v134495e57e); if ($pdf6c365c) { ob_start(); $pf8ed4912 = wp_nav_menu(array('menu' => $v134495e57e, 'echo' => false)); $pf8ed4912 .= ob_get_contents(); ob_end_clean(); } return $pf8ed4912; } public static function getDefaultMenuHtml() { ob_start(); $pf8ed4912 = wp_nav_menu(array('echo' => false)); $pf8ed4912 .= ob_get_contents(); ob_end_clean(); return $pf8ed4912; } public static function getMenuHtmlByLocation($v9d19916a7c) { $v1228775e53 = self::getMenuIdByLocation($v9d19916a7c); return $v1228775e53 ? self::getMenuHtml($v1228775e53) : null; } public static function getMenuIdByLocation($v9d19916a7c) { $paa1b457a = self::getMenusByLocations(); if ($paa1b457a && isset($paa1b457a[$v9d19916a7c])) return $paa1b457a[$v9d19916a7c]; } public static function getMenusByLocations() { return get_nav_menu_locations(); } public static function getAvailableMenuLocations() { return get_registered_nav_menus(); } public static function getAvailableMenus() { return wp_get_nav_menus(); } public static function getAvailableSideBars() { global $wp_registered_sidebars; return $wp_registered_sidebars; } public static function getAvailableSideBarsWithWidgets() { return wp_get_sidebars_widgets(); } public static function getSideBarHtml($v2fda65266d) { global $wp_registered_sidebars, $phpframework_options, $phpframework_results; if (is_active_sidebar($v2fda65266d)) { ob_start(); dynamic_sidebar($v2fda65266d); $pf8ed4912 = ob_get_contents(); ob_end_clean(); if ($phpframework_options && !$pf8ed4912) $pf8ed4912 = $phpframework_results['theme_side_bars'][$v2fda65266d][0]; if (!$pf8ed4912) { ob_start(); self::ma9ba40516742($v2fda65266d, array(), false); $pf8ed4912 = ob_get_contents(); ob_end_clean(); } return $pf8ed4912; } } public static function getDefaultSideBarHtml() { ob_start(); self::ma9ba40516742(); $pf8ed4912 = ob_get_contents(); ob_end_clean(); return $pf8ed4912; } private static function ma9ba40516742($v5e813b295b = null, $v86066462c3 = array(), $pc8a00104 = true) { do_action( 'get_sidebar', $v5e813b295b, $v86066462c3 ); $v94d48fb72f = array(); $v5e813b295b = (string) $v5e813b295b; if ('' !== $v5e813b295b) $v94d48fb72f[] = "sidebar-{$v5e813b295b}.php"; if ($pc8a00104) $v94d48fb72f[] = 'sidebar.php'; if (!locate_template($v94d48fb72f, true, false, $v86066462c3)) return false; return true; } public static function getAvailableWidgets() { global $wp_registered_widgets; return $wp_registered_widgets; } public static function getWidgetIdByClass($v3ae55a9a2e) { global $wp_widget_factory; $v0a9404cbc7 = $wp_widget_factory->widgets[ $v3ae55a9a2e ]; return $v0a9404cbc7 ? $v0a9404cbc7->id : null; } public static function getWidgetCallbackObjById($v67d78a9820) { global $wp_registered_widgets; $pa174568b = $wp_registered_widgets[$v67d78a9820]; if (isset($pa174568b['callback'])) return $pa174568b['callback'][0]; } public static function getWidgetHtmlById($v67d78a9820, $v4957af599c = array(), $v86066462c3 = array()) { $v972f1a5c2b = self::getWidgetCallbackObjById($v67d78a9820); if ($v972f1a5c2b) { $pf48acc65 = get_class($v972f1a5c2b); return self::getWidgetHtmlByClass($pf48acc65, $v4957af599c, $v86066462c3); } } public static function getWidgetHtmlByClass($pf48acc65, $v4957af599c = array(), $v86066462c3 = array()) { global $wp_widget_factory; if ($pf48acc65) { $v0a9404cbc7 = $wp_widget_factory->widgets[ $pf48acc65 ]; if ($v0a9404cbc7) { $pd80a2a74 = $v0a9404cbc7->number; ob_start(); the_widget($pf48acc65, $v4957af599c, $v86066462c3); $pf8ed4912 = ob_get_contents(); ob_end_clean(); $v0a9404cbc7->_set($pd80a2a74); return $pf8ed4912; } } } public static function getWidgetControlOptionsById($v67d78a9820, $v4957af599c = null) { global $wp_registered_widget_controls; $pa174568b = $wp_registered_widget_controls[$v67d78a9820]; $pf8ed4912 = ""; if (isset($pa174568b['callback'])) { $v972f1a5c2b = $pa174568b['callback'][0]; $v4957af599c = apply_filters('widget_form_callback', $v4957af599c, $v972f1a5c2b); if (false !== $v4957af599c) { ob_start(); $v50890f6f30 = $v972f1a5c2b->form($v4957af599c); do_action_ref_array('in_widget_form', array(&$v972f1a5c2b, &$v50890f6f30, $v4957af599c)); $pf8ed4912 = ob_get_contents(); ob_end_clean(); if ($pf8ed4912) { $v58ac916504 = is_numeric($v4957af599c["multi_number"]) ? (int) $v4957af599c["multi_number"] : (int) $v972f1a5c2b->number; $v67d78a9820 = $v4957af599c["widget-id"] ? $v4957af599c["widget-id"] : $pa174568b["id"]; $v5d24855515 = $v4957af599c["id_base"] ? $v4957af599c["id_base"] : $v972f1a5c2b->id_base; $pf8ed4912 = '<form method="post">
							' . $pf8ed4912 . '
							<input type="hidden" name="widget-id" class="widget-id" value="' . $v67d78a9820 . '" />
							<input type="hidden" name="id_base" class="id_base" value="' . $v5d24855515 . '" />
							<input type="hidden" name="multi_number" class="multi_number" value="' . $v58ac916504 . '" />
							
							<input type="submit" name="savewidget" class="button button-primary widget-control-save right" value="Saved">
						</form>'; } } } if (!$pf8ed4912) $pf8ed4912 = "<p>" . __( 'There are no options for this widget.' ) . "</p>\n"; return $pf8ed4912; } public static function getWidgetControlOptionsByClass($pf48acc65, $v4957af599c = null) { $v67d78a9820 = self::getWidgetIdByClass($pf48acc65); return self::getWidgetControlOptionsById($v67d78a9820, $v4957af599c); } public static function getWidgetControlOptionsToSave($v1b5c739d30 = array()) { global $wp_registered_widget_updates; $v4957af599c = false; if (isset( $_POST['savewidget'] ) && $_POST['widget-id'] && $_POST['id_base']) { $v5d24855515 = $_POST['id_base']; $pa174568b = $wp_registered_widget_updates[ $v5d24855515 ]; if ($pa174568b) { $v972f1a5c2b = $pa174568b['callback'][0]; $v58ac916504 = $_POST['multi_number'] ? (int) $_POST['multi_number'] : (int) $_POST['widget_number']; $pbf7d0631 = array(); if (isset( $_POST['widget-' . $v5d24855515]) && is_array($_POST['widget-' . $v5d24855515])) $pbf7d0631 = $_POST['widget-' . $v5d24855515][$v58ac916504]; ob_start(); $v4957af599c = $v972f1a5c2b->update( $pbf7d0631, $v1b5c739d30 ); $v4957af599c = apply_filters( 'widget_update_callback', $v4957af599c, $pbf7d0631, $v1b5c739d30, $v972f1a5c2b); ob_end_clean(); } } return $v4957af599c; } public static function getPagesListHtml($v86066462c3 = null) { ob_start(); wp_list_pages($v86066462c3); $pf8ed4912 = ob_get_contents(); ob_end_clean(); return $pf8ed4912; } public static function getPages($v86066462c3 = null) { $pbb9eae73 = array( 'depth' => 0, 'show_date' => '', 'date_format' => get_option( 'date_format' ), 'child_of' => 0, 'exclude' => '', 'title_li' => __( 'Pages' ), 'echo' => 1, 'authors' => '', 'sort_column' => 'menu_order, post_title', 'link_before' => '', 'link_after' => '', 'item_spacing' => 'preserve', 'walker' => '', 'hierarchical' => 0, ); $v86066462c3 = $v86066462c3 ? array_merge($pbb9eae73, $v86066462c3) : $pbb9eae73; $pe60255bd = get_pages($v86066462c3); return $pe60255bd; } public static function getPostById($pbbcb2d04) { return get_post($pbbcb2d04); } public static function getAllPosts($v86066462c3 = null) { return get_posts($v86066462c3); } public static function getPostsByCategory($v0da33fe24a, $v86066462c3 = null) { if (!$v86066462c3) $v86066462c3 = array(); if ($v0da33fe24a) $v86066462c3["category"] = get_cat_ID($v0da33fe24a); return get_posts($v86066462c3); } public static function getPostsByTag($v1b1c6a10a2, $v86066462c3 = null) { if (!$v86066462c3) $v86066462c3 = array(); if ($v1b1c6a10a2) $v86066462c3["tag"] = $v1b1c6a10a2; return get_posts($v86066462c3); } public static function getPostsAfterDate($v8f602836b9, $v86066462c3 = null) { if (!$v86066462c3) $v86066462c3 = array(); if ($v8f602836b9) { $v86066462c3['date_query'] = array( 'after' => $v8f602836b9, ); $v86066462c3['suppress_filters'] = false; return get_posts($v86066462c3); } } public static function getPostsByDate($v8f602836b9, $v86066462c3 = null) { if (!$v86066462c3) $v86066462c3 = array(); if ($v8f602836b9) { $v86066462c3['date_query'] = array( 'year' => date('Y', strtotime($v8f602836b9)), 'month' => date('m', strtotime($v8f602836b9)), 'day' => date('d', strtotime($v8f602836b9)), ); $v86066462c3['suppress_filters'] = false; return get_posts($v86066462c3); } } public static function getCategories($v86066462c3 = null) { return get_categories($v86066462c3); } public static function getTags($v86066462c3 = null) { return get_tags($v86066462c3); } public static function getMedias($v86066462c3 = null) { $pbb9eae73 = array( 'post_type' => 'attachment', 'numberposts' => -1, 'post_status' => null, 'post_parent' => null, ); $v86066462c3 = $v86066462c3 ? array_merge($pbb9eae73, $v86066462c3) : $pbb9eae73; return get_posts($v86066462c3); } public static function getHomeUrl() { return get_home_url(); } public static function getSiteUrl() { return site_url(); } public static function getAvailableThemes() { return wp_get_themes(); } public static function getCurrentTheme() { return get_template(); } public static function get404PageHtml() { global $wp_query; ob_start(); $wp_query->set_404(); get_template_part(404); $pf8ed4912 = ob_get_contents(); ob_end_clean(); return $pf8ed4912; } public static function convertContentArrayToHtml($pfb662071) { $pf8ed4912 = ""; foreach ($pfb662071 as $pe5c5e2fe => $v956913c90f) { if (is_array($v956913c90f)) $pf8ed4912 .= self::convertContentArrayToHtml($v956913c90f); else $pf8ed4912 .= $v956913c90f; } return $pf8ed4912; } public static function convertHtmlIntoInnerHtml($pf8ed4912) { if (!$pf8ed4912) return $pf8ed4912; $v8a9674a7c9 = HtmlStringHandler::getHtmlTags($pf8ed4912, "meta", false); foreach ($v8a9674a7c9 as $v1b1c6a10a2) { if (preg_match("/(\s+|\"|')name=(\"|')?viewport(\"|'|\s|\/|>)/i", $v1b1c6a10a2)) continue 1; $pf8ed4912 = str_replace($v1b1c6a10a2, "", $pf8ed4912); } $v8a9674a7c9 = HtmlStringHandler::getHtmlTags($pf8ed4912, "title", true); foreach ($v8a9674a7c9 as $v1b1c6a10a2) $pf8ed4912 = str_replace($v1b1c6a10a2, "", $pf8ed4912); $v8a9674a7c9 = HtmlStringHandler::getHtmlTags($pf8ed4912, "link", false); foreach ($v8a9674a7c9 as $v1b1c6a10a2) { if (preg_match("/(\s+|\"|')rel=(\"|')?stylesheet(\"|'|\s|\/|>)/i", $v1b1c6a10a2) || preg_match("/(\s+|\"|')type=(\"|')?text\/css(\"|'|\s|\/|>)/i", $v1b1c6a10a2)) continue 1; $pf8ed4912 = str_replace($v1b1c6a10a2, "", $pf8ed4912); } $pf8ed4912 = preg_replace("/<(\/)?html[^>]*>/i", "", $pf8ed4912); $pf8ed4912 = preg_replace("/<!doctype[^>]*>/i", "", $pf8ed4912); $pf8ed4912 = preg_replace("/<(\/)?(head|body|foot)(\s+|>)/i", "<\${1}div\${3}", $pf8ed4912); return $pf8ed4912; } public static function getCssAndJsFromHtml($pf8ed4912, $v67ec30e2c2 = true, $v40b70e70c3 = true) { if ($pf8ed4912 && ($v67ec30e2c2 || $v40b70e70c3)) { $v3ff757a876 = ""; if ($v67ec30e2c2) { $v8a9674a7c9 = HtmlStringHandler::getHtmlTags($pf8ed4912, "link", false); foreach ($v8a9674a7c9 as $v1b1c6a10a2) if (preg_match("/(\s+|\"|')rel=(\"|')?stylesheet(\"|'|\s|\/|>)/i", $v1b1c6a10a2) || preg_match("/(\s+|\"|')type=(\"|')?text\/css(\"|'|\s|\/|>)/i", $v1b1c6a10a2)) $v3ff757a876 .= trim($v1b1c6a10a2) . "\n"; if ($v3ff757a876) $v3ff757a876 .= "\n"; $v8a9674a7c9 = HtmlStringHandler::getHtmlTags($pf8ed4912, "style", true); foreach ($v8a9674a7c9 as $v1b1c6a10a2) $v3ff757a876 .= trim($v1b1c6a10a2) . "\n"; } if ($v40b70e70c3) { if ($v3ff757a876) $v3ff757a876 .= "\n"; $v8a9674a7c9 = HtmlStringHandler::getHtmlTags($pf8ed4912, "script", true); foreach ($v8a9674a7c9 as $v1b1c6a10a2) $v3ff757a876 .= trim($v1b1c6a10a2) . "\n"; } return trim($v3ff757a876); } return $pf8ed4912; } public static function getContentParentsHtml($pf8ed4912, $v3fb9f41470 = "above") { return $v3fb9f41470 == "bellow" ? self::getContentParentsHtmlBellow($pf8ed4912) : self::getContentParentsHtmlAbove($pf8ed4912); } public static function getContentParentsHtmlAbove($pf8ed4912) { if ($pf8ed4912) { $pdfa15f64 = HtmlStringHandler::convertHtmlToElementsArray($pf8ed4912); $v217a47cf7a = self::me7109a81397d($pdfa15f64); $pf8ed4912 = HtmlStringHandler::convertElementsArrayToHtml($v217a47cf7a); } return $pf8ed4912; } public static function getContentParentsHtmlBellow($pf8ed4912) { if ($pf8ed4912) { $pdfa15f64 = HtmlStringHandler::convertHtmlToElementsArray($pf8ed4912); $pdfa15f64 = HtmlStringHandler::joinElementsArrayTextNodes($pdfa15f64); foreach ($pdfa15f64 as $pd69fb7d0 => $v06f2bc39aa) if (is_array($v06f2bc39aa) && $v06f2bc39aa["nodeType"] == 1) unset($pdfa15f64[$pd69fb7d0]); $pf8ed4912 = HtmlStringHandler::convertElementsArrayToHtml($pdfa15f64); } return $pf8ed4912; } private static function me7109a81397d($pdfa15f64) { $v217a47cf7a = array(); for ($v43dd7d0051 = count($pdfa15f64); $v43dd7d0051 >= 0; $v43dd7d0051--) { $v06f2bc39aa = $pdfa15f64[$v43dd7d0051]; $v9c4bf49720 = $v06f2bc39aa["nodeType"]; if ($v9c4bf49720 == 1 && empty($v06f2bc39aa["closeTag"])) { if ($v06f2bc39aa["childNodes"]) $v06f2bc39aa["childNodes"] = self::me7109a81397d($v06f2bc39aa["childNodes"]); $v217a47cf7a[] = $v06f2bc39aa; break; } } return $v217a47cf7a; } } ?>
