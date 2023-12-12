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
include_once get_lib("org.phpframework.util.web.html.CssAndJSFilesOptimizer"); if (file_exists( get_lib("lib.vendor.phpjavascriptpacker.src.Packer") )) include_once get_lib("lib.vendor.phpjavascriptpacker.src.Packer"); class CMSObfuscateJSFilesHandler { private $v65a3fffe5d; private $pdf045150 = false; public function __construct($v65a3fffe5d) { $this->v65a3fffe5d = $v65a3fffe5d; $this->pdf045150 = class_exists("Tholu\Packer\Packer"); } public function obfuscate($pebb3f429, $paad98334) { $paad98334 = $this->mf3abb0be10a2($paad98334); $v8d1f503e9f = isset($pebb3f429["encoding"]) ? $pebb3f429["encoding"] : null; $v41f0baede0 = !empty($pebb3f429["fast_decode"]); $v665d345999 = !empty($pebb3f429["special_chars"]); $v04f0e5293c = !empty($pebb3f429["remove_semi_colons"]); $pf0bdaf22 = isset($pebb3f429["copyright"]) ? $pebb3f429["copyright"] : null; $v0cba298be6 = isset($pebb3f429["allowed_domains"]) ? trim($pebb3f429["allowed_domains"]) : ""; $pd0a05d6a = isset($pebb3f429["check_allowed_domains_port"]) ? $pebb3f429["check_allowed_domains_port"] : null; $pf484cb00 = is_array($pebb3f429) && array_key_exists("php_packer", $pebb3f429) ? $pebb3f429["php_packer"] : true; $v8a29987473 = array(); $v5c1c342594 = true; foreach ($paad98334 as $pf3dc0762 => $v89de514f53) { if (!is_array($v89de514f53[1])) $v89de514f53[1] = array(); $v54a694bf0e = array( "encoding" => array_key_exists("encoding", $v89de514f53[1]) ? $v89de514f53[1]["encoding"] : $v8d1f503e9f, "fast_decode" => array_key_exists("fast_decode", $v89de514f53[1]) ? $v89de514f53[1]["fast_decode"] : $v41f0baede0, "special_chars" => array_key_exists("special_chars", $v89de514f53[1]) ? $v89de514f53[1]["special_chars"] : $v665d345999, "remove_semi_colons" => array_key_exists("remove_semi_colons", $v89de514f53[1]) ? $v89de514f53[1]["remove_semi_colons"] : $v04f0e5293c, "copyright" => array_key_exists("copyright", $v89de514f53[1]) ? $v89de514f53[1]["copyright"] : $pf0bdaf22, "allowed_domains" => array_key_exists("allowed_domains", $v89de514f53[1]) ? $v89de514f53[1]["allowed_domains"] : $v0cba298be6, "check_allowed_domains_port" => $pd0a05d6a, "php_packer" => array_key_exists("php_packer", $v89de514f53[1]) ? $v89de514f53[1]["php_packer"] : $pf484cb00, ); $v3dbd5668c7 = isset($v89de514f53[1]["save_path"]) ? $v89de514f53[1]["save_path"] : null; if (!$this->md7522fc72a2f($pf3dc0762, $v3dbd5668c7, $v54a694bf0e, $v8a29987473)) $v5c1c342594 = false; } return array( "status" => $v5c1c342594, "errors" => $v8a29987473, ); } public function getConfiguredOptions($v5d3813882f) { $pebb3f429 = array(); if (!is_array($v5d3813882f)) parse_str($v5d3813882f, $pebb3f429); else $pebb3f429 = $v5d3813882f; if (!isset($pebb3f429["encoding"])) $pebb3f429["encoding"] = "Normal"; if (!isset($pebb3f429["fast_decode"])) $pebb3f429["fast_decode"] = true; if (!isset($pebb3f429["special_chars"])) $pebb3f429["special_chars"] = false; if (!isset($pebb3f429["remove_semi_colons"])) $pebb3f429["remove_semi_colons"] = true; if (!isset($pebb3f429["copyright"])) $pebb3f429["copyright"] = '/*
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
	 */'; return $pebb3f429; } private function mf3abb0be10a2($paad98334) { if ($paad98334) foreach ($paad98334 as $pf3dc0762 => $v89de514f53) if (!file_exists($pf3dc0762)) unset($paad98334[$pf3dc0762]); return $paad98334; } public function getDefaultFilesSettings($v3806ce773c, $v644c3a506b, $v0b69598e4a) { $paad98334 = array( $this->v65a3fffe5d . $v644c3a506b . "js/MyJSLib.js" => array( 1 => array( "save_path" => $v3806ce773c . $v644c3a506b . "js/MyJSLib.js", ), ), $this->v65a3fffe5d . $v0b69598e4a . "js/MyJSLib.js" => array( 1 => array( "save_path" => $v3806ce773c . $v0b69598e4a . "js/MyJSLib.js", ), ), $this->v65a3fffe5d . $v644c3a506b . "js/MyWidgetResourceLib.js" => array( 1 => array( "save_path" => $v3806ce773c . $v644c3a506b . "js/MyWidgetResourceLib.js", ), ), $this->v65a3fffe5d . $v0b69598e4a . "js/MyWidgetResourceLib.js" => array( 1 => array( "save_path" => $v3806ce773c . $v0b69598e4a . "js/MyWidgetResourceLib.js", ), ), $this->v65a3fffe5d . $v644c3a506b . "vendor/jquerylayoutuieditor/js/" => array( 1 => array( "save_path" => $v3806ce773c . $v644c3a506b . "vendor/jquerylayoutuieditor/js/", ), ), $this->v65a3fffe5d . $v0b69598e4a . "vendor/jquerylayoutuieditor/js/" => array( 1 => array( "save_path" => $v3806ce773c . $v0b69598e4a . "vendor/jquerylayoutuieditor/js/", ), ), $this->v65a3fffe5d . $v644c3a506b . "vendor/jquerymyfancylightbox/js/jquery.myfancybox.js" => array( 1 => array( "save_path" => $v3806ce773c . $v644c3a506b . "vendor/jquerymyfancylightbox/js/jquery.myfancybox.js", ), ), $this->v65a3fffe5d . $v0b69598e4a . "vendor/jquerymyfancylightbox/js/jquery.myfancybox.js" => array( 1 => array( "save_path" => $v3806ce773c . $v0b69598e4a . "vendor/jquerymyfancylightbox/js/jquery.myfancybox.js", ), ), $this->v65a3fffe5d . $v644c3a506b . "vendor/myautocomplete/js/MyAutoComplete.js" => array( 1 => array( "save_path" => $v3806ce773c . $v644c3a506b . "vendor/myautocomplete/js/MyAutoComplete.js", ), ), $this->v65a3fffe5d . $v0b69598e4a . "vendor/myautocomplete/js/MyAutoComplete.js" => array( 1 => array( "save_path" => $v3806ce773c . $v0b69598e4a . "vendor/myautocomplete/js/MyAutoComplete.js", ), ), $this->v65a3fffe5d . $v644c3a506b . "vendor/mycodebeautifier/js/MyCodeBeautifier.js" => array( 1 => array( "save_path" => $v3806ce773c . $v644c3a506b . "vendor/mycodebeautifier/js/MyCodeBeautifier.js", ), ), $this->v65a3fffe5d . $v0b69598e4a . "vendor/mycodebeautifier/js/MyCodeBeautifier.js" => array( 1 => array( "save_path" => $v3806ce773c . $v0b69598e4a . "vendor/mycodebeautifier/js/MyCodeBeautifier.js", ), ), $this->v65a3fffe5d . $v644c3a506b . "vendor/jquerymytree/js/mytree.js" => array( 1 => array( "save_path" => $v3806ce773c . $v644c3a506b . "vendor/jquerymytree/js/mytree.js", ), ), $this->v65a3fffe5d . $v0b69598e4a . "vendor/jquerymytree/js/mytree.js" => array( 1 => array( "save_path" => $v3806ce773c . $v0b69598e4a . "vendor/jquerymytree/js/mytree.js", ), ), $this->v65a3fffe5d . $v644c3a506b . "vendor/jquerytaskflowchart/js/TaskFlowChart.js" => array( 1 => array( "save_path" => $v3806ce773c . $v644c3a506b . "vendor/jquerytaskflowchart/js/TaskFlowChart.js", ), ), $this->v65a3fffe5d . $v0b69598e4a . "vendor/jquerytaskflowchart/js/TaskFlowChart.js" => array( 1 => array( "save_path" => $v3806ce773c . $v0b69598e4a . "vendor/jquerytaskflowchart/js/TaskFlowChart.js", ), ), $this->v65a3fffe5d . $v644c3a506b . "vendor/jquerytaskflowchart/js/ExternalLibHandler.js" => array( 1 => array( "save_path" => $v3806ce773c . $v644c3a506b . "vendor/jquerytaskflowchart/js/ExternalLibHandler.js", ), ), $this->v65a3fffe5d . $v0b69598e4a . "vendor/jquerytaskflowchart/js/ExternalLibHandler.js" => array( 1 => array( "save_path" => $v3806ce773c . $v0b69598e4a . "vendor/jquerytaskflowchart/js/ExternalLibHandler.js", ), ), $this->v65a3fffe5d . $v644c3a506b . "vendor/myhtmlbeautify/MyHtmlBeautify.js" => array( 1 => array( "save_path" => $v3806ce773c . $v644c3a506b . "vendor/myhtmlbeautify/MyHtmlBeautify.js", "php_packer" => false, ), ), $this->v65a3fffe5d . $v0b69598e4a . "vendor/myhtmlbeautify/MyHtmlBeautify.js" => array( 1 => array( "save_path" => $v3806ce773c . $v0b69598e4a . "vendor/myhtmlbeautify/MyHtmlBeautify.js", "php_packer" => false, ), ), ); return $paad98334; } private function md7522fc72a2f($v6b146f3e75, $v1a74c80ef8, $v54a694bf0e, &$v8a29987473) { $v5c1c342594 = true; if (file_exists($v6b146f3e75)) { if (is_dir($v6b146f3e75)) { $v6ee393d9fb = scandir($v6b146f3e75); if ($v6ee393d9fb) foreach ($v6ee393d9fb as $v250a1176c9) if ($v250a1176c9 != "." && $v250a1176c9 != ".." && (is_dir("$v6b146f3e75/$v250a1176c9") || substr($v250a1176c9, -3) == ".js")) if (!$this->md7522fc72a2f("$v6b146f3e75/$v250a1176c9", "$v1a74c80ef8/$v250a1176c9", $v54a694bf0e, $v8a29987473)) $v5c1c342594 = false; } else { if (!$v1a74c80ef8) { $v8a29987473[] = "Save Path '$v1a74c80ef8' is empty!"; $v5c1c342594 = false; } else if (!is_dir(dirname($v1a74c80ef8)) && !mkdir(dirname($v1a74c80ef8), 0755, true)) { $v8a29987473[] = "Save Path '" . dirname($v1a74c80ef8) . "' is not a folder!"; $v5c1c342594 = false; } else { $v067674f4e4 = file_get_contents($v6b146f3e75); if ($v067674f4e4) { $pf0bdaf22 = isset($v54a694bf0e["copyright"]) ? $v54a694bf0e["copyright"] : null; $v0cba298be6 = isset($v54a694bf0e["allowed_domains"]) ? $v54a694bf0e["allowed_domains"] : null; $pd0a05d6a = isset($v54a694bf0e["check_allowed_domains_port"]) ? $v54a694bf0e["check_allowed_domains_port"] : null; $pf484cb00 = !is_array($v54a694bf0e) || !array_key_exists("php_packer", $v54a694bf0e) || $v54a694bf0e["php_packer"]; if ($v0cba298be6) { $pf6f61d2c = '/* #ADD_SECURITY_CODE_HERE# */'; $pbd1bc7b0 = strpos($v067674f4e4, $pf6f61d2c); if ($pbd1bc7b0 !== false) { $pb8a6db32 = $pd0a05d6a ? 'location.host' : 'location.hostname'; $v0cba298be6 = str_replace('"', '', $v0cba298be6); $v0a9aded945 = '
var cd = "" + ' . $pb8a6db32 . ';
var ads = "' . ($pd0a05d6a ? $v0cba298be6 . "," : preg_replace("/:[0-9]+,/", ",", $v0cba298be6 . ",")) . '";

cd = cd ? cd.replace(/:80$/, "").toLowerCase() : "";
ads = ads.replace(/;/g, ",").replace(/:80,/g, ",").toLowerCase();

if (!cd)
	return;
else {
	var arr = ads.split(",");
	var parsed_arr = [];
	
	for (var i = 0, t = arr.length; i < t; i++) {
		var ad = arr[i].replace(/(^\s+|\s+$)/, "");
		
		if (ad)
			parsed_arr.push(ad);
	}
	
	if (parsed_arr.length > 0 && parsed_arr.indexOf(cd) == -1) {
		var sd = false;
		
		for (var i = 0, t = parsed_arr.length; i < t; i++)
			if ((cd + ",").indexOf("." + parsed_arr[i] + ",")) {
				sd = true;
				break;
			}
		
		if (!sd)
			return;
	}
}'; $v0a9aded945 = preg_replace("/\n+/", "", $v0a9aded945); $v067674f4e4 = str_replace($pf6f61d2c, $v0a9aded945, $v067674f4e4); } } if ($pf484cb00 && $this->pdf045150) { $v8d1f503e9f = isset($v54a694bf0e["encoding"]) ? $v54a694bf0e["encoding"] : null; $v41f0baede0 = isset($v54a694bf0e["fast_decode"]) ? $v54a694bf0e["fast_decode"] : null; $v665d345999 = isset($v54a694bf0e["special_chars"]) ? $v54a694bf0e["special_chars"] : null; $v04f0e5293c = isset($v54a694bf0e["remove_semi_colons"]) ? $v54a694bf0e["remove_semi_colons"] : null; $pb9046901 = new Tholu\Packer\Packer($v067674f4e4, $v8d1f503e9f, $v41f0baede0, $v665d345999, $v04f0e5293c); $pb8ceef38 = $pb9046901->pack(); } else { $v2b681a6ca0 = array( "remove_single_line_comments" => true, "remove_multiple_lines_comments" => true, "remove_white_spaces" => true, ); $pb8ceef38 = CssAndJSFilesOptimizer::removeCommentsAndEndLines($v067674f4e4, $v2b681a6ca0, "js"); } if ($pb8ceef38) { $pb8ceef38 = $pf0bdaf22 . $pb8ceef38; if (file_put_contents($v1a74c80ef8, $pb8ceef38) === false) $v5c1c342594 = false; } else { $v8a29987473[] = "Obfuscate content is empty in '$v6b146f3e75'!"; $v5c1c342594 = false; } } else { $v8a29987473[] = "Empty content in '$v6b146f3e75'!"; $v5c1c342594 = false; } } } } else { $v8a29987473[] = "File '$v6b146f3e75' does not exist!"; $v5c1c342594 = false; } return $v5c1c342594; } } ?>
