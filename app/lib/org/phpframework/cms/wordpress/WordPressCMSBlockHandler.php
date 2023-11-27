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

include_once get_lib("org.phpframework.util.web.CookieHandler"); include_once get_lib("org.phpframework.util.web.MyCurl"); include_once get_lib("org.phpframework.encryption.CryptoKeyHandler"); include_once get_lib("org.phpframework.cms.wordpress.WordPressHacker"); include_once get_lib("org.phpframework.cms.wordpress.WordPressUrlsParser"); class WordPressCMSBlockHandler { private $v08d9602741; private $v30857f7eca; private $v4b6875ca7c; private $pa2507f6c = false; public function __construct($v08d9602741, $v30857f7eca, $v4b6875ca7c = true) { if (empty($v30857f7eca["cookies_prefix"])) $v30857f7eca["cookies_prefix"] = isset($v30857f7eca["wordpress_folder"]) ? $v30857f7eca["wordpress_folder"] : null; if (empty($v30857f7eca["cookies_prefix"])) launch_exception(new Exception("WordPress folder cannot be empty!")); $this->v08d9602741 = $v08d9602741; $this->v30857f7eca = $v30857f7eca; $this->v4b6875ca7c = $v4b6875ca7c; } public static function convertContentsHtmlToPHPTemplate($pf8ed4912) { if ($pf8ed4912 && preg_match_all("/<!--\s*phpframework:template:(region|param):\s*/", $pf8ed4912, $pbae7526c, PREG_OFFSET_CAPTURE) && $pbae7526c[0]) { $v29b7e75ba8 = strlen($pf8ed4912); $v3ff757a876 = $pf8ed4912; foreach ($pbae7526c[0] as $pd69fb7d0 => $v87ae7286da) { $v4430104888 = $v87ae7286da[1] + strlen($v87ae7286da[0]); $pbaeb17fb = strpos($pf8ed4912, "-->", $v4430104888); $pbaeb17fb = $pbaeb17fb !== false ? $pbaeb17fb : $v29b7e75ba8; $v6cd9d4006f = $pbae7526c[1][$pd69fb7d0][0] == "region" ? "renderRegion" : "getParam"; $v9b9b8653bc = trim( substr($pf8ed4912, $v4430104888, $pbaeb17fb - $v4430104888) ); $v9b9b8653bc = $v9b9b8653bc[0] == '"' && substr($v9b9b8653bc, -1) == '"' ? stripcslashes(substr($v9b9b8653bc, 1, -1)) : $v9b9b8653bc; $v9b9b8653bc = $v9b9b8653bc[0] == "'" && substr($v9b9b8653bc, -1) == "'" ? stripcslashes(substr($v9b9b8653bc, 1, -1)) : $v9b9b8653bc; $v327f72fb62 = substr($pf8ed4912, $v87ae7286da[1], ($pbaeb17fb + 3) - $v87ae7286da[1]); $v3ff757a876 = str_replace($v327f72fb62, '<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->' . $v6cd9d4006f . '("' . addcslashes($v9b9b8653bc, '"') . '"); ?>', $v3ff757a876); } $pf8ed4912 = $v3ff757a876; } return $pf8ed4912; } public static function addTemplateXMLRegionsAndParamsToPHPTemplate($pae77d38c) { $pac65f06f = 0; do { preg_match('/\$EVC->getCMSLayer\(\)->getCMSTemplateLayer\(\)->(renderRegion|getParam)\(("|\')([^\)]*)("|\')\)/', $pae77d38c, $pbae7526c, PREG_OFFSET_CAPTURE, $pac65f06f); if ($pbae7526c) { $pd9dcff5f = $pbae7526c[0][1]; $pf899e66b = $pbae7526c[0][0]; $v79e0ac3b03 = strlen($pf899e66b); $v6cd9d4006f = $pbae7526c[1][0] == "renderRegion" ? "region" : "param"; $v43b0f34d2c = $pbae7526c[3][0]; $v91a962d917 = "<!--phpframework:template:$v6cd9d4006f:$v43b0f34d2c-->"; $pb001ebcf = strpos($pae77d38c, ";", $pd9dcff5f + $v79e0ac3b03); $v8db5e6364e = strpos($pae77d38c, "?>", $pd9dcff5f + $v79e0ac3b03); $pbd1bc7b0 = null; if ($pb001ebcf && (!$v8db5e6364e || $pb001ebcf < $v8db5e6364e)) { $pbd1bc7b0 = $pb001ebcf + 1; $v91a962d917 = " echo '$v91a962d917';"; } else if ($v8db5e6364e && (!$pb001ebcf || $pb001ebcf > $v8db5e6364e)) { $pbd1bc7b0 = $v8db5e6364e + 2; $v91a962d917 = "<? echo '$v91a962d917'; ?>"; } if ($pbd1bc7b0) { $pae77d38c = substr($pae77d38c, 0, $pbd1bc7b0) . $v91a962d917 . substr($pae77d38c, $pbd1bc7b0); $pac65f06f = $pbd1bc7b0 + strlen($v91a962d917); } else $pac65f06f = $pd9dcff5f + $v79e0ac3b03; } } while ($pbae7526c); return $pae77d38c; } public function getBlockContent($v29fec2ceaa, $pca670fe1, $v5d3813882f) { global $wordpress_already_called, $first_wordpress_theme_to_call, $first_wordpress_theme_called; $GLOBALS["current_phpframework_block_id"] = $v29fec2ceaa; $pae77d38c = null; $v9f5acaa821 = WordPressHacker::getPHPFrameworkFromOptions($v5d3813882f); if ($wordpress_already_called) { $v6f3a2700dd = isset($this->v30857f7eca["wordpress_request_content_url"]) ? $this->v30857f7eca["wordpress_request_content_url"] : null; if (!$v6f3a2700dd) launch_exception(new Exception('You are calling multiple wordpress instances with different templates, so you must defined the "wordpress_request_content_url" settings when creating a WordPressCMSBlockHandler object!')); $v35217a3375 = parse_url($_SERVER["REQUEST_URI"]); $v6f3a2700dd .= !empty($v35217a3375["query"]) ? (strpos($v6f3a2700dd, "?") !== false ? "" : "?") . $v35217a3375["query"] : ""; $v6f3a2700dd .= !empty($v35217a3375["fragment"]) ? "#" . $v35217a3375["fragment"] : ""; $pd65a9318 = array( "settings" => $this->v30857f7eca, "block_id" => $v29fec2ceaa, "url_query" => $pca670fe1, "options" => $v5d3813882f, ); unset($pd65a9318["settings"]["wordpress_request_content_url"]); unset($pd65a9318["settings"]["wordpress_request_content_connection_timeout"]); unset($pd65a9318["settings"]["wordpress_request_content_encryption_key"]); $v5e5f060613 = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http"; $pf41879cb = $v5e5f060613 . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; $pd65a9318["options"]["current_page_url"] = $pf41879cb; $pd65a9318["options"]["request_method"] = $_SERVER['REQUEST_METHOD']; if (!empty($this->v30857f7eca["wordpress_request_content_encryption_key"])) { $v96d18ace05 = serialize($pd65a9318); $v327f72fb62 = time() . "_" . md5($v96d18ace05) . "_" . $v96d18ace05; $pbdcbd484 = $this->v30857f7eca["wordpress_request_content_encryption_key"]; $pbfa01ed1 = CryptoKeyHandler::hexToBin($pbdcbd484); $v46db43a407 = CryptoKeyHandler::encryptText($v327f72fb62, $pbfa01ed1); $v8c3792c37f = CryptoKeyHandler::binToHex($v46db43a407); $pd65a9318 = array("data" => $v8c3792c37f); } $v6af1f205e1 = $_POST; $v6af1f205e1 = $v6af1f205e1 ? $v6af1f205e1 : array(); $v6af1f205e1["phpframework_wordpress_data"] = $pd65a9318; $v1fc19b96e1 = parse_url($v6f3a2700dd, PHP_URL_HOST); $v7c0d95d431 = parse_url($pf41879cb, PHP_URL_HOST); $v30857f7eca = array( "url" => $v6f3a2700dd, "post" => $v6af1f205e1, "cookie" => $v7c0d95d431 == $v1fc19b96e1 ? $_COOKIE : null, "settings" => array( "referer" => $_SERVER["HTTP_REFERER"], "follow_location" => 0, "connection_timeout" => isset($this->v30857f7eca["wordpress_request_content_connection_timeout"]) ? $this->v30857f7eca["wordpress_request_content_connection_timeout"] : null, ) ); if (!empty($_SERVER["AUTH_TYPE"]) && !empty($_SERVER["PHP_AUTH_USER"])) { $v30857f7eca["settings"]["http_auth"] = $_SERVER["AUTH_TYPE"]; $v30857f7eca["settings"]["user_pwd"] = $_SERVER["PHP_AUTH_USER"] . ":" . $_SERVER["PHP_AUTH_PW"]; } $v30f580552e = null; while (true) { $v56a64ecb97 = new MyCurl(); $v56a64ecb97->initSingle($v30857f7eca); $v56a64ecb97->get_contents(); $v539082ff30 = $v56a64ecb97->getData(); if (!empty($v539082ff30[0]["info"]["redirect_url"])) { $v959a588da6 = $v539082ff30[0]["info"]["redirect_url"]; if ($v29fec2ceaa) WordPressUrlsParser::replaceUrlPhpFrameworkBlockId($v959a588da6, $v29fec2ceaa); $pbd1bc7b0 = strpos($pf41879cb, "?"); $v32d7748b62 = $pbd1bc7b0 !== false ? substr($pf41879cb, 0, $pbd1bc7b0) : $pf41879cb; $pbd1bc7b0 = strpos($v959a588da6, "?"); $pa99fe088 = $pbd1bc7b0 !== false ? substr($v959a588da6, 0, $pbd1bc7b0) : $v959a588da6; $v886808a152 = isset($this->v30857f7eca["wordpress_request_content_url"]) ? $this->v30857f7eca["wordpress_request_content_url"] : null; if ($pa99fe088 == $v32d7748b62 || $pa99fe088 == $v886808a152) { $v30857f7eca["url"] = $v886808a152; if ($pbd1bc7b0 !== false) $v30857f7eca["url"] .= (strpos($v30857f7eca["url"], "?") === false ? "?" : "&") . substr($v959a588da6, $pbd1bc7b0 + 1); if ($v30f580552e == $v30857f7eca["url"]) { header("Location: $v959a588da6"); return; } else $v30f580552e = $v30857f7eca["url"]; } else { header("Location: $v959a588da6"); return; } } else break; } $v7bd5d88a74 = isset($v539082ff30[0]["content"]) ? $v539082ff30[0]["content"] : null; $pae77d38c = unserialize($v7bd5d88a74); } else { $pae77d38c = $this->getBlockContentDirectly($v29fec2ceaa, $pca670fe1, $v5d3813882f); if (!$wordpress_already_called) { $wordpress_already_called = true; $first_wordpress_theme_to_call = $v9f5acaa821; $first_wordpress_theme_called = WordPressHacker::getCurrentTheme(); } } if ($v29fec2ceaa) $v5d3813882f["phpframework_block_id"] = $v29fec2ceaa; WordPressUrlsParser::parseWordPressHeaders( isset($pae77d38c["wordpress_site_url"]) ? $pae77d38c["wordpress_site_url"] : null, isset($pae77d38c["current_page_url"]) ? $pae77d38c["current_page_url"] : null, $v5d3813882f, $this->pa2507f6c ); if ($pae77d38c && !empty($pae77d38c["results"])) $pae77d38c["results"] = WordPressUrlsParser::prepareArrayWithWordPressUrls($pae77d38c["results"], $v5d3813882f, $pae77d38c); $GLOBALS["current_phpframework_block_id"] = null; return $pae77d38c; } public function getBlockContentDirectly($block_id, $url_query, $options = null) { if (!$this->pa2507f6c) { $phpframework_block_id = isset($_GET["phpframework_block_id"]) ? $_GET["phpframework_block_id"] : null; $wp_url = isset($_GET["wp_url"]) ? $_GET["wp_url"] : null; $wp_file = isset($_GET["wp_file"]) ? $_GET["wp_file"] : null; $wp_relative_file_path = null; $query_string = null; $old_get_vars = $_GET; $old_request_vars = $_REQUEST; $new_vars = array(); if (!empty($options["current_page_url"])) $current_page_url = $options["current_page_url"]; else { $current_protocol = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http"; $current_page_url = $current_protocol . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; } WordPressUrlsParser::prepareUrl($current_page_url); if ($block_id) WordPressUrlsParser::replaceUrlPhpFrameworkBlockId($current_page_url, $block_id); $cookie_flags = array("SameSite" => "Strict", "httponly" => true); $cookie_prefix = isset($this->v30857f7eca["cookies_prefix"]) ? $this->v30857f7eca["cookies_prefix"] : ""; CookieHandler::setSafeCookie($cookie_prefix . "_phpframework_url", $current_page_url, 0, "/", $cookie_flags); $_COOKIE[$cookie_prefix . "_phpframework_url"] = $current_page_url; $allowed_wordpress_urls = serialize( isset($options["allowed_wordpress_urls"]) ? $options["allowed_wordpress_urls"] : null ); CookieHandler::setSafeCookie($cookie_prefix . "_allowed_wordpress_urls", $allowed_wordpress_urls, 0, "/", $cookie_flags); $_COOKIE[$cookie_prefix . "_allowed_wordpress_urls"] = $allowed_wordpress_urls; $parse_wordpress_urls = !empty($options["parse_wordpress_urls"]) ? 1 : 0; CookieHandler::setSafeCookie($cookie_prefix . "_parse_wordpress_urls", $parse_wordpress_urls, 0, "/", $cookie_flags); $_COOKIE[$cookie_prefix . "_parse_wordpress_urls"] = $parse_wordpress_urls; $parse_wordpress_relative_urls = !empty($options["parse_wordpress_relative_urls"]) ? 1 : 0; CookieHandler::setSafeCookie($cookie_prefix . "_parse_wordpress_relative_urls", $parse_wordpress_relative_urls, 0, "/", $cookie_flags); $_COOKIE[$cookie_prefix . "_parse_wordpress_relative_urls"] = $parse_wordpress_relative_urls; $EVC = $this->v08d9602741; include $EVC->getConfigPath("config"); $project_common_relative_url_prefix = parse_url($project_common_url_prefix, PHP_URL_PATH); $project_common_relative_url_prefix .= substr($project_common_relative_url_prefix, -1) != "/" ? "/" : ""; $wordpress_folder_relative_prefix = WordPressUrlsParser::WORDPRESS_FOLDER_PREFIX . "/" . $this->v30857f7eca["wordpress_folder"] . "/"; $wordpress_folder_path = $EVC->getWebrootPath("common") . $wordpress_folder_relative_prefix; $wordpress_request_uri = $project_common_relative_url_prefix . $wordpress_folder_relative_prefix; $wordpress_request_file = $this->md0d7804869cd($wordpress_folder_relative_prefix); $wordpress_request_url_prefix = $project_common_url_prefix . $wordpress_folder_relative_prefix; if (!file_exists($wordpress_folder_path)) launch_exception(new Exception("WordPress installation with folder '" . $this->v30857f7eca["wordpress_folder"] . "' doesn't exists!")); if ($block_id == $phpframework_block_id || !$phpframework_block_id) { if ($wp_url) { $wp_url = htmlspecialchars_decode($wp_url); $wp_url = urldecode($wp_url); $parts = explode("#", $wp_url); $wp_url = $parts[0]; $parts = explode("?", $wp_url); $url_query = trim($parts[0]); $query_string = isset($parts[1]) ? trim($parts[1]) : ""; } else if ($wp_file) { $wp_file = htmlspecialchars_decode($wp_file); $wp_file = urldecode($wp_file); $wp_relative_file_path = preg_replace("/^\/+/", "", $wp_file); $wordpress_request_file = $wordpress_request_uri . $wp_relative_file_path; $url_query = null; } } if ($url_query && (strpos($url_query, "?") !== false || strpos($url_query, "#") !== false)) { $parts = explode("#", $url_query); $url_query = $parts[0]; $parts = explode("?", $url_query); $url_query = trim($parts[0]); $query_string = isset($parts[1]) ? trim($parts[1]) : ""; } if ($query_string) { parse_str($query_string, $new_vars); if ($new_vars) foreach ($new_vars as $k => $v) { $_GET[$k] = $v; if (!isset($_POST[$k])) $_REQUEST[$k] = $v; } } $WordPressHacker = new WordPressHacker($wordpress_folder_path, $wordpress_request_uri, $wordpress_request_file, $this->v4b6875ca7c); if ($wp_relative_file_path) $results = array( $WordPressHacker->callFile($wp_relative_file_path) ); else $results = $WordPressHacker->getContent($url_query, $options); if ($new_vars) { $_GET = $old_get_vars; $_REQUEST = $old_request_vars; } if ($block_id == $phpframework_block_id || !$phpframework_block_id) { unset($_GET["wp_url"]); unset($_GET["wp_file"]); } $wordpress_site_url = WordPressHacker::getSiteUrl() . "/"; return array( "results" => $results, "wordpress_folder_relative_prefix" => $wordpress_folder_relative_prefix, "current_page_url" => $current_page_url, "wordpress_site_url" => $wordpress_site_url, "url_query" => $url_query, ); } } public static function prepareRedirectUrl(&$pae397839, $pa02dc6aa) { $v1312342d80 = site_url() . "/"; if (substr($pae397839, 0, strlen($v1312342d80)) == $v1312342d80 && $_COOKIE[$pa02dc6aa . "_parse_wordpress_urls"]) { $v5841083cb4 = $_COOKIE[$pa02dc6aa . "_phpframework_url"]; if ($v5841083cb4) { WordPressUrlsParser::prepareUrl($v5841083cb4); $v01b7bef391 = WordPressUrlsParser::isWordPressPHPFile($pae397839); $v472093fee6 = !$v01b7bef391 && WordPressUrlsParser::isWordPressRawFile($pae397839); if (!$v472093fee6) { $v5d3813882f = array( "allowed_wordpress_urls" => unserialize($_COOKIE[$pa02dc6aa . "_allowed_wordpress_urls"]), "parse_wordpress_urls" => $_COOKIE[$pa02dc6aa . "_parse_wordpress_urls"], "parse_wordpress_relative_urls" => $_COOKIE[$pa02dc6aa . "_parse_wordpress_relative_urls"], "phpframework_block_id" => $GLOBALS["current_phpframework_block_id"] ? $GLOBALS["current_phpframework_block_id"] : $_GET["phpframework_block_id"], ); $pae397839 = WordPressUrlsParser::convertUrlToRedirectUrl($v1312342d80, $v5841083cb4, $pae397839, $v5d3813882f, $v01b7bef391 ? "wp_file" : "wp_url"); } } } } private function md0d7804869cd($pb7c62cdf) { $v00ba50906a = $GLOBALS["presentation_id"]; $v28312f076c = $_SERVER["SCRIPT_NAME"]; $v684aba9d8c = "/__system/layer/presentation/phpframework/webroot/"; $v9cd205cadb = explode($v684aba9d8c, $v28312f076c); if (count($v9cd205cadb) > 1) $v1482381f75 = $v9cd205cadb[0] . "/layer/presentation"; else { $v9cd205cadb = explode("/$v00ba50906a/webroot/", $v28312f076c); $v1482381f75 = ""; if (count($v9cd205cadb) > 1) $v1482381f75 = $v9cd205cadb[0]; else { $v9cd205cadb = explode("/webroot/", $v28312f076c); if (count($v9cd205cadb) > 1) $v1482381f75 = $v9cd205cadb[0]; else $v1482381f75 = dirname($v28312f076c); } } $pa47fac06 = $this->v08d9602741->getCommonProjectName(); return $v1482381f75 . "/$pa47fac06/webroot/" . $pb7c62cdf . "index.php"; } } ?>
