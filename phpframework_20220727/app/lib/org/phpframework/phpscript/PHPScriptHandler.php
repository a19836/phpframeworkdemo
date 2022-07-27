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

include_once get_lib("org.phpframework.util.text.TextSanitizer"); class PHPScriptHandler { public static function parseContent($pae77d38c, &$pc5a892eb = array(), &$v368e4322b2 = array()) { $padecb90c = array("<?", "<?php", "<?="); $pae5bda3e = array("?>"); if(is_array($pc5a892eb)) foreach($pc5a892eb as $v1cfba8c105 => $pa6209df1) if ($v1cfba8c105) { eval('$' . $v1cfba8c105 . ' = $pa6209df1;'); } $v83cdca7600 = $pae77d38c; $pac65f06f = 0; do { $v7959970a41 = false; $pc1501395 = false; $v1b28cec599 = false; $v72a8beb63d = false; $v136f871d0e = false; $pc37695cb = count($padecb90c); for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v19cbfb3aee = $padecb90c[$v43dd7d0051]; $pbd1bc7b0 = strpos($pae77d38c, $v19cbfb3aee, $pac65f06f); if($pbd1bc7b0 !== false && ($pbd1bc7b0 <= $v72a8beb63d || $v72a8beb63d === false)) { $pbdb99a55 = substr($pae77d38c, $pbd1bc7b0 + strlen($v19cbfb3aee), 1); if($pbdb99a55 == " " || $pbdb99a55 == "(" || $pbdb99a55 == "\$" || $pbdb99a55 == "\n") { $pc1501395 = $v43dd7d0051; $v72a8beb63d = $pbd1bc7b0; $v7959970a41 = true; } } } $pc37695cb = count($pae5bda3e); for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v19cbfb3aee = $pae5bda3e[$v43dd7d0051]; $pbd1bc7b0 = strpos($pae77d38c, $v19cbfb3aee, $v72a8beb63d); if($pbd1bc7b0 !== false && ($pbd1bc7b0 <= $v136f871d0e || $v136f871d0e === false)) { $v365d20463c = false; $v8f85090cce = false; for($v9d27441e80 = $v72a8beb63d + 1; $v9d27441e80 < $pbd1bc7b0; $v9d27441e80++) { if($pae77d38c[$v9d27441e80] == '"' && !TextSanitizer::isCharEscaped($pae77d38c, $v9d27441e80)) $v365d20463c = !$v365d20463c; elseif($pae77d38c[$v9d27441e80] == "'" && !TextSanitizer::isCharEscaped($pae77d38c, $v9d27441e80)) $v8f85090cce = !$v8f85090cce; } if(!$v365d20463c && !$v8f85090cce) { $v1b28cec599 = $v43dd7d0051; $v136f871d0e = $pbd1bc7b0; } } } if(!is_numeric($v1b28cec599)) { $v1b28cec599 = 0; $v136f871d0e = strlen($pae77d38c); $v5e80bc7115 = $pae5bda3e[0]; $pac65f06f = strlen($pae77d38c); } else { $v5e80bc7115 = $pae5bda3e[$v1b28cec599]; $pac65f06f = $v136f871d0e + strlen($v5e80bc7115); } if($v7959970a41 && is_numeric($pc1501395)) { $v511e888558 = $padecb90c[$pc1501395]; $v27e200bd34 = $v136f871d0e + strlen($v5e80bc7115); $pea1e05e1 = substr($pae77d38c, $v72a8beb63d, $v27e200bd34 - $v72a8beb63d); $v7e4b517c18 = $v72a8beb63d + strlen($v511e888558); $v87bfb0879e = substr($pae77d38c, $v7e4b517c18, $v136f871d0e - $v7e4b517c18); if($v511e888558 == "<?=") { $v87bfb0879e = "echo " . $v87bfb0879e; if(substr(trim($v87bfb0879e), strlen(trim($v87bfb0879e)) - 1) != ";") $v87bfb0879e .= ";"; } ob_start(); try { $v368e4322b2[] = eval($v87bfb0879e); } catch (Error $paec2c009) { $pef612b9d = "PHP error: " . $paec2c009->getMessage(); debug_log("[PHPScriptHandler::parseContent] $pef612b9d: \n" . self::mf9a7b6df9f2d($v87bfb0879e), "error"); } catch(ParseError $paec2c009) { $pef612b9d = "Parse error: " . $paec2c009->getMessage(); debug_log("[PHPScriptHandler::parseContent] $pef612b9d: \n" . self::mf9a7b6df9f2d($v87bfb0879e), "error"); } catch(ErrorException $paec2c009) { $pef612b9d = "Error exception: " . $paec2c009->getMessage(); debug_log("[PHPScriptHandler::parseContent] $pef612b9d: \n" . self::mf9a7b6df9f2d($v87bfb0879e), "error"); } catch(Exception $paec2c009) { $pef612b9d = $paec2c009->getMessage(); debug_log("[PHPScriptHandler::parseContent] $pef612b9d: \n" . self::mf9a7b6df9f2d($v87bfb0879e), "error"); } $v87bfb0879e = ob_get_contents(); ob_end_clean(); $v83cdca7600 = str_replace($pea1e05e1, $v87bfb0879e, $v83cdca7600); } } while($v7959970a41 && $pac65f06f < strlen($pae77d38c)); if(is_array($pc5a892eb)) foreach($pc5a892eb as $v1cfba8c105 => $pa6209df1) if ($v1cfba8c105) eval('$pc5a892eb["'. $v1cfba8c105 . '"] = $' . $v1cfba8c105 . ';'); return $v83cdca7600; } public static function isValidPHPCode($v067674f4e4, &$pef612b9d) { try { if (mb_strlen($v067674f4e4)) { $v05b1c40a78 = eval("return 1; $v067674f4e4"); return $v05b1c40a78; } } catch (Error $paec2c009) { $pef612b9d = "PHP error: " . $paec2c009->getMessage(); debug_log("[PHPScriptHandler::isValidPHPCode] $pef612b9d: \n" . self::mf9a7b6df9f2d($v067674f4e4), "error"); } catch(ParseError $paec2c009) { $pef612b9d = "Parse error: " . $paec2c009->getMessage(); debug_log("[PHPScriptHandler::isValidPHPCode] $pef612b9d: \n" . self::mf9a7b6df9f2d($v067674f4e4), "error"); } catch(ErrorException $paec2c009) { $pef612b9d = "Error exception: " . $paec2c009->getMessage(); debug_log("[PHPScriptHandler::isValidPHPCode] $pef612b9d: \n" . self::mf9a7b6df9f2d($v067674f4e4), "error"); } catch(Exception $paec2c009) { $pef612b9d = $paec2c009->getMessage(); debug_log("[PHPScriptHandler::isValidPHPCode] $pef612b9d: \n" . self::mf9a7b6df9f2d($v067674f4e4), "error"); } return false; } public static function isValidPHPContents($v6490ea3a15, &$v0f9512fda4 = null) { if (!$v6490ea3a15) return true; if (!class_exists("PhpParser\Parser\Php7")) include_once get_lib("lib.vendor.phpparser.lib.bootstrap"); $pf1d2e1d9 = new PhpParser\Parser\Php5(new PhpParser\Lexer\Emulative); $v3c085bda5f = new PhpParser\Parser\Php7(new PhpParser\Lexer\Emulative); $v5f04ef1cc1 = new PhpParser\Parser\Multiple(array($pf1d2e1d9, $v3c085bda5f)); $v4ace7728e6 = null; try { $v793f92423d = $v5f04ef1cc1->parse($v6490ea3a15); } catch (Error $paec2c009) { $v4ace7728e6 = $paec2c009; } catch(ParseError $paec2c009) { $v4ace7728e6 = $paec2c009; } catch(ErrorException $paec2c009) { $v4ace7728e6 = $paec2c009; } catch(Exception $paec2c009) { $v4ace7728e6 = $paec2c009; } if ($v4ace7728e6) { $v0f9512fda4 = $v4ace7728e6->getMessage(); debug_log("[PHPScriptHandler::isValidPHPContents] $v0f9512fda4: \n" . self::mf9a7b6df9f2d($v6490ea3a15), "error"); return false; } return true; } public static function isValidPHPContents2($v6490ea3a15, &$v0f9512fda4 = null) { $v27f37c46ec = tmpfile(); $pc3772d0d = str_split($v6490ea3a15, 1024 * 4); foreach ($pc3772d0d as $v306839072f) fwrite($v27f37c46ec, $v306839072f, strlen($v306839072f)); $pa32be502 = stream_get_meta_data($v27f37c46ec)['uri']; $pf0f58138 = error_reporting(); error_reporting(0); $v4ace7728e6 = null; try { include $pa32be502; } catch (Error $paec2c009) { $v4ace7728e6 = $paec2c009; } catch(ParseError $paec2c009) { $v4ace7728e6 = $paec2c009; } catch(ErrorException $paec2c009) { $v4ace7728e6 = $paec2c009; } catch(Exception $paec2c009) { $v4ace7728e6 = $paec2c009; } error_reporting($pf0f58138); fclose($v27f37c46ec); if ($v4ace7728e6) { $v0f9512fda4 = $v4ace7728e6->getMessage(); debug_log("[PHPScriptHandler::isValidPHPContents] $v0f9512fda4: \n" . self::mf9a7b6df9f2d($v6490ea3a15), "error"); return false; } return true; } public static function isValidPHPContentsViaUrl($v6f3a2700dd, $v6490ea3a15, &$v0f9512fda4 = null, $pfe5382a0 = 0) { if (!$v6490ea3a15) return true; $v539082ff30 = array("contents" => $v6490ea3a15); $v1fc19b96e1 = parse_url($v6f3a2700dd, PHP_URL_HOST); $v7c0d95d431 = explode(":", $_SERVER["HTTP_HOST"]); $v7c0d95d431 = $v7c0d95d431[0]; $v30857f7eca = array( "url" => $v6f3a2700dd, "post" => $v539082ff30, "cookie" => $v7c0d95d431 == $v1fc19b96e1 ? $_COOKIE : null, "settings" => array( "referer" => $_SERVER["HTTP_REFERER"], "follow_location" => 1, "connection_timeout" => $pfe5382a0, ) ); if ($_SERVER["AUTH_TYPE"] && $_SERVER["PHP_AUTH_USER"]) { $v30857f7eca["settings"]["http_auth"] = $_SERVER["AUTH_TYPE"]; $v30857f7eca["settings"]["user_pwd"] = $_SERVER["PHP_AUTH_USER"] . ":" . $_SERVER["PHP_AUTH_PW"]; } $v56a64ecb97 = new MyCurl(); $v56a64ecb97->initSingle($v30857f7eca); $v56a64ecb97->get_contents(); $v7bd5d88a74 = $v56a64ecb97->getData(); $v7bd5d88a74 = $v7bd5d88a74[0]["content"]; if ($v7bd5d88a74 == 1) return true; $v0f9512fda4 = $v9ad1385268; debug_log("[PHPScriptHandler::isValidPHPContentsViaUrl] $v0f9512fda4: \n" . self::mf9a7b6df9f2d($v6490ea3a15), "error"); return false; } public static function isValidPHPContentsViaCommandLine($v6490ea3a15, &$v0f9512fda4 = null) { $v0f026b52ae = false; if (!$v0f026b52ae && function_exists("shell_exec")) { $v9ad1385268 = null; $v5a66f39a91 = addcslashes(stripslashes($v6490ea3a15), '"') != $v6490ea3a15; if (!$v5a66f39a91) $v9ad1385268 = trim(shell_exec("echo " . escapeshellarg($v6490ea3a15) . " | php -l 2>&1")); if (!$v9ad1385268) { $v27f37c46ec = tmpfile(); $pc3772d0d = str_split($v6490ea3a15, 1024 * 4); foreach ($pc3772d0d as $v306839072f) fwrite($v27f37c46ec, $v306839072f, strlen($v306839072f)); $pa32be502 = stream_get_meta_data($v27f37c46ec)['uri']; $v9ad1385268 = trim(shell_exec("php -l $pa32be502 2>&1")); fclose($v27f37c46ec); } if (strpos($v9ad1385268, "No syntax errors detected in ") !== false) return true; $v0f9512fda4 = $v9ad1385268; debug_log("[PHPScriptHandler::isValidPHPContentsViaCommandLine] $v0f9512fda4: \n" . self::mf9a7b6df9f2d($v6490ea3a15), "error"); return false; } return true; } public static function printPHPContentsViaUrl() { $v539082ff30 = htmlspecialchars_decode( file_get_contents("php://input") ); $v539082ff30 = json_decode($v539082ff30, true); $v6490ea3a15 = $v539082ff30["contents"]; $v27f37c46ec = tmpfile(); $pc3772d0d = str_split($v6490ea3a15, 1024 * 4); foreach ($pc3772d0d as $v306839072f) fwrite($v27f37c46ec, $v306839072f, strlen($v306839072f)); $pa32be502 = stream_get_meta_data($v27f37c46ec)['uri']; include $pa32be502; fclose($v27f37c46ec); echo "1"; } private static function mf9a7b6df9f2d(&$v067674f4e4) { $pf4e3c708 = ""; $v00f73eb9bc = explode("\n", $v067674f4e4); foreach ($v00f73eb9bc as $v43dd7d0051 => $v259d35fa15) $pf4e3c708 .= "line" . ($v43dd7d0051 + 1) . ": " . $v259d35fa15 . "\n"; return $pf4e3c708; } } ?>
