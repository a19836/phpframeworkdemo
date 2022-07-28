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

include_once get_lib("org.phpframework.ptl.exception.PHPTemplateLanguageException"); include_once get_lib("org.phpframework.cache.user.IUserCacheHandler"); include_once get_lib("org.phpframework.phpscript.PHPScriptHandler"); include_once get_lib("org.phpframework.util.text.TextSanitizer"); class PHPTemplateLanguage { const DEFAULT_CACHE_FILE_PREFIX = "ptl_"; private $pbc96d822; public function setCacheHandler(IUserCacheHandler $pbc96d822) { $this->pbc96d822 = $pbc96d822; $this->pbc96d822->config(false, false); } public function getCacheHandler() { return $this->pbc96d822; } public function parseTemplate($pe7333513, $v2b64fc04a8 = false, $v8d1f503e9f = false) { try { $v067674f4e4 = $this->getTemplateCode($pe7333513, $v8d1f503e9f); if ($v067674f4e4) { if ($v2b64fc04a8) foreach ($v2b64fc04a8 as $v5e813b295b => $v67db1bd535) ${$v5e813b295b} = $v67db1bd535; ob_start(); eval($v067674f4e4); $pf8ed4912 = ob_get_contents(); ob_end_clean(); } } catch (Error $paec2c009) { launch_exception(new PHPTemplateLanguageException(7, $v067674f4e4, new Exception("PHP error: " . $paec2c009->getMessage()))); } catch(ParseError $paec2c009) { launch_exception(new PHPTemplateLanguageException(7, $v067674f4e4, new Exception("Parse error: " . $paec2c009->getMessage()))); } catch(ErrorException $paec2c009) { launch_exception(new PHPTemplateLanguageException(7, $v067674f4e4, new Exception("Error exception: " . $paec2c009->getMessage()))); } catch(Exception $paec2c009) { launch_exception(new PHPTemplateLanguageException(7, $v067674f4e4, $paec2c009)); } return $pf8ed4912; } public function getTemplateCode($pe7333513, $v8d1f503e9f = false) { $v57bb97acb0 = self::DEFAULT_CACHE_FILE_PREFIX . md5($pe7333513 . $v8d1f503e9f); if ($this->pbc96d822 && $this->pbc96d822->isValid($v57bb97acb0)) return $this->pbc96d822->read($v57bb97acb0); if ($v8d1f503e9f) $pe7333513 = mb_convert_encoding($pe7333513, 'HTML-ENTITIES', $v8d1f503e9f); $pe7333513 = preg_replace('/<!--(php|ptl|\?):(.*?)-->/si', "", $pe7333513); $pac65f06f = 0; $v0911c6122e = strlen($pe7333513); $v79b7cb19d1 = $v66327139f0 = false; $v69bc793320 = array(); $pb587476d = ''; do { preg_match('/<\/?(php|ptl|\?):(\w)/iu', $pe7333513, $pbae7526c, PREG_OFFSET_CAPTURE, $pac65f06f); if ($pbae7526c) { $v7e4b517c18 = $pbae7526c[2][1]; $v27e200bd34 = $v0911c6122e; $v1b1c6a10a2 = $pbae7526c[0][0]; $pf4b9d8e6 = $pd621c542 = ""; $paddf48f6 = false; for ($v43dd7d0051 = $v7e4b517c18; $v43dd7d0051 < $v0911c6122e; $v43dd7d0051++) { $pc288256e = $pe7333513[$v43dd7d0051]; if ($v43dd7d0051 > $v7e4b517c18) $v1b1c6a10a2 .= $pc288256e; if ($pc288256e == '"' && !$v66327139f0 && !TextSanitizer::isCharEscaped($pe7333513, $v43dd7d0051)) $v79b7cb19d1 = !$v79b7cb19d1; else if ($pc288256e == "'" && !$v79b7cb19d1 && !TextSanitizer::isCharEscaped($pe7333513, $v43dd7d0051)) $v66327139f0 = !$v66327139f0; else if ($pc288256e == " " && !$v79b7cb19d1 && !$v66327139f0) $paddf48f6 = true; else if ($pc288256e == ">" && !$v79b7cb19d1 && !$v66327139f0) { $v27e200bd34 = $v43dd7d0051; break; } if (!$paddf48f6) $pf4b9d8e6 .= $pc288256e; else $pd621c542 .= $pc288256e; } if ($pf4b9d8e6) { $v160fce70f2 = substr($pbae7526c[0][0], 0, 2) == "</"; $pf4b9d8e6 = ($v160fce70f2 ? "/" : "") . $pf4b9d8e6; $pf4b9d8e6 = substr($pf4b9d8e6, -1) == "/" ? substr($pf4b9d8e6, 0, -1) : $pf4b9d8e6; if ($v160fce70f2) $pd621c542 = ""; else { $pd621c542 = substr($pd621c542, 1); $pd621c542 = substr($pd621c542, -1) == "/" ? substr($pd621c542, 0, -1) : $pd621c542; } if (strpos($pf4b9d8e6, "&gt;") !== false) $pf4b9d8e6 = str_replace("&gt;", ">", $pf4b9d8e6); if (strpos($pf4b9d8e6, "[") !== false) $pf4b9d8e6 = str_replace(']', '"]', str_replace('[', '["', str_replace(array('"', "'"), '', $pf4b9d8e6))); $v9a8b7dc209 = substr($pe7333513, $pac65f06f, $pbae7526c[0][1] - $pac65f06f); $v87af059c5d = strlen( str_replace(array("\t", "\r\n", "\n"), '', $v9a8b7dc209) ); if ($v87af059c5d) $v69bc793320[] = array("echo '" . addcslashes($v9a8b7dc209, "\\'") . "';", $pb587476d); $v3beaa307d1 = trim( $this->mcdfa55498bba($v1b1c6a10a2, $pf4b9d8e6, $pd621c542) ); if (strlen($v3beaa307d1)) { if (substr($v3beaa307d1, 0, 1) == "}") $pb587476d = substr($pb587476d, 0, -1); $v69bc793320[] = array($v3beaa307d1, $pb587476d); if (substr($v3beaa307d1, -1) == "{") $pb587476d .= "\t"; } } else launch_exception(new PHPTemplateLanguageException(1, $v1b1c6a10a2)); $pac65f06f = $v27e200bd34 + 1; } else { $v9a8b7dc209 = substr($pe7333513, $pac65f06f, $v0911c6122e); $v87af059c5d = strlen( str_replace(array("\t", "\r\n", "\n"), '', $v9a8b7dc209) ); if ($v87af059c5d) $v69bc793320[] = array("echo '" . addcslashes($v9a8b7dc209, "\\'") . "';", $pb587476d); } } while ($pbae7526c && $pac65f06f < $v0911c6122e); $pc37695cb = count($v69bc793320); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v259d35fa15 = $v69bc793320[$v43dd7d0051][0]; $v398081b899 = $v69bc793320[$v43dd7d0051 - 1][0]; if (substr($v259d35fa15, 0, 5) == "echo " && substr($v398081b899, 0, 5) == "echo ") { $v69bc793320[$v43dd7d0051][0] = substr($v398081b899, 0, -1) . " . " . substr($v259d35fa15, 5); $v69bc793320[$v43dd7d0051 - 1] = null; unset($v69bc793320[$v43dd7d0051 - 1]); } } $v067674f4e4 = ""; foreach ($v69bc793320 as $pbfa2c8b4) if (strlen($pbfa2c8b4[0])) $v067674f4e4 .= $pbfa2c8b4[1] . $pbfa2c8b4[0] . "\n"; if ($this->pbc96d822) $this->pbc96d822->write($v57bb97acb0, $v067674f4e4); return $v067674f4e4; } private function mcdfa55498bba($v1b1c6a10a2, $pf4b9d8e6, $pd621c542) { $pbd1bc7b0 = strpos($pf4b9d8e6, ":"); if ($pbd1bc7b0 > 0) { $pc4043fb5 = substr($pf4b9d8e6, $pbd1bc7b0 + 1); $pf4b9d8e6 = substr($pf4b9d8e6, 0, $pbd1bc7b0); } $v5cfdd10d6c = strtolower($pf4b9d8e6); switch ($v5cfdd10d6c) { case "definevar": $this->f1be2bd43eb($pd621c542); if (!self::f1b2d6b2aab($pd621c542) && strlen($pd621c542) > 2) launch_exception(new PHPTemplateLanguageException(2, array($v1b1c6a10a2, $pd621c542))); return '$' . $pc4043fb5 . " = " . substr($pd621c542, 1, -1) . ";"; case "var": case "incvar": case "decvar": case "joinvar": case "concatvar": $this->f1be2bd43eb($pd621c542); $pd621c542 = strlen($pd621c542) ? $pd621c542 : '""'; $v0c6dc0ffff = ""; $pbd1bc7b0 = strpos($pc4043fb5, "::"); if ($pbd1bc7b0) { $v0c6dc0ffff = substr($pc4043fb5, $pbd1bc7b0); $pc4043fb5 = substr($pc4043fb5, 0, $pbd1bc7b0); } $v19a7745bc6 = "="; switch ($v5cfdd10d6c) { case "incvar": $v19a7745bc6 = "+="; break; case "decvar": $v19a7745bc6 = "-="; break; case "joinvar": case "concatvar": $v19a7745bc6 = ".="; break; } $v9cd205cadb = explode(":", $pc4043fb5); $v5e813b295b = array_pop($v9cd205cadb) . $v0c6dc0ffff; return implode(" ", $v9cd205cadb) . " " . (!in_array("const", array_map("strtolower", $v9cd205cadb)) && !$pbd1bc7b0 ? '$' : '') . $v5e813b295b . " $v19a7745bc6 " . $pd621c542 . ";"; case "for": $v346e1685e3 = array(";" => ";"); $this->f1be2bd43eb($pd621c542, "; ", $v346e1685e3); return "for (" . $pd621c542 . ") {"; case "foreach": $pdccf64a6 = $this->f1be2bd43eb($pd621c542, ","); $v648f89f08c = count($pdccf64a6); if ($v648f89f08c >= 2 && $v648f89f08c <= 3) { if (self::f1b2d6b2aab($pdccf64a6[0]) || is_numeric($pdccf64a6[0])) launch_exception(new PHPTemplateLanguageException(5, array($v1b1c6a10a2, $pdccf64a6[0], "1st"))); if (self::f1b2d6b2aab($pdccf64a6[1])) $pdccf64a6[1] = '$' . substr($pdccf64a6[1], 1, -1); else if ($pdccf64a6[1][0] != '$') launch_exception(new PHPTemplateLanguageException(4, array($v1b1c6a10a2, $pdccf64a6[1], "2nd"))); if ($pdccf64a6[2]) { if (self::f1b2d6b2aab($pdccf64a6[2])) $pdccf64a6[2] = '$' . substr($pdccf64a6[2], 1, -1); else if ($pdccf64a6[2][0] != '$') launch_exception(new PHPTemplateLanguageException(4, array($v1b1c6a10a2, $pdccf64a6[2], "3rd"))); } return "foreach (" . $pdccf64a6[0] . " as " . ($v648f89f08c == 3 ? $pdccf64a6[1] . " => " . $pdccf64a6[2] : $pdccf64a6[1]) . ") {"; } else launch_exception(new PHPTemplateLanguageException(6, $v1b1c6a10a2)); case "if": $this->f1be2bd43eb($pd621c542); return "if (" . $pd621c542 . ") {"; case "elseif": $this->f1be2bd43eb($pd621c542); return "} else if (" . $pd621c542 . ") {"; case "else": return "} else {"; case "echo": case "print": case "return": $this->f1be2bd43eb($pd621c542); return strlen($pd621c542) ? "$v5cfdd10d6c " . $pd621c542 . ";" : ""; case "break": return "break;"; case "die": $this->f1be2bd43eb($pd621c542); return "die(" . $pd621c542 . ");"; case "require": case "include": case "include_once": case "require_once": $this->f1be2bd43eb($pd621c542); return "$v5cfdd10d6c " . $pd621c542 . ";"; case "switch": $this->f1be2bd43eb($pd621c542); return "switch (" . $pd621c542 . ") {"; case "case": $this->f1be2bd43eb($pd621c542); if (self::f1b2d6b2aab($pd621c542) || $pd621c542[0] == '$') return "case " . $pd621c542 . ":"; else launch_exception(new PHPTemplateLanguageException(4, array($v1b1c6a10a2, $pd621c542))); case "default": return "default:"; case "try": return "try {"; case "catch": $pdccf64a6 = $this->f1be2bd43eb($pd621c542, ","); if (self::f1b2d6b2aab($pdccf64a6[1])) $pdccf64a6[1] = '$' . substr($pdccf64a6[1], 1, -1); if (empty($pdccf64a6)) $pdccf64a6 = array('"Exception"', '$e'); else if (!self::f1b2d6b2aab($pdccf64a6[0])) launch_exception(new PHPTemplateLanguageException(2, array($v1b1c6a10a2, $pdccf64a6[0], "1st"))); else if ($pdccf64a6[1][0] != '$') launch_exception(new PHPTemplateLanguageException(3, array($v1b1c6a10a2, $pdccf64a6[1], "2nd"))); return "} catch (" . substr($pdccf64a6[0], 1, -1) . " " . $pdccf64a6[1] . ") {"; case "throw": if ($pc4043fb5) { $this->f1be2bd43eb($pd621c542, ", "); return "throw new " . $pc4043fb5 . "(" . $pd621c542 . ");"; } return "throw " . $pd621c542 . ";"; case "class": return "class " . implode(" ", explode(":", $pc4043fb5)) . " {"; case "function": $pdccf64a6 = $this->f1be2bd43eb($pd621c542, ","); $pd621c542 = ''; $pc37695cb = count($pdccf64a6); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pea70e132 = $pdccf64a6[$v43dd7d0051]; if ($pea70e132 == "=") { $pd621c542 .= " = " . $pdccf64a6[$v43dd7d0051 + 1]; $v43dd7d0051++; } else $pd621c542 .= ($pd621c542 ? ", " : "") . (self::f1b2d6b2aab($pea70e132) ? '$' . substr($pea70e132, 1, -1) : $pea70e132); } $v9cd205cadb = explode(":", $pc4043fb5); $v5e813b295b = array_pop($v9cd205cadb); return implode(" ", $v9cd205cadb) . " function " . $v5e813b295b . "(" . $pd621c542 . ") {"; case "code": return str_replace("&gt;", ">", $pd621c542); case "/for": case "/foreach": case "/if": case "/switch": case "/try": case "/class": case "/function": return "}"; default: if (substr($pf4b9d8e6, 0, 1) == "/") return ""; $this->f1be2bd43eb($pd621c542, ", ", array("," => ",")); return "$pf4b9d8e6 (" . $pd621c542 . ");"; } } private function f1be2bd43eb(&$pd621c542, $v3c581c912b = " . ", $pa9880268 = null) { $pdccf64a6 = array(); if ($pd621c542) { $v346e1685e3 = array( "." => ".", "+" => "+", "-" => array("->", "-&gt;", "-"), "*" => "*", "/" => "/", "%" => "%", "=" => array("===", "==", "=>", "=&gt;", "="), "!" => array("!==", "!=", "!"), "<" => array("<==", "<=", "<"), ">" => array(">==", ">=", ">"), "&" => array("&&", "&gt;==", "&gt;=", "&gt;", "&lt;==", "&lt;=", "&lt;"), "|" => "||", "?" => "?", ":" => ":", ); $v8f4eca0278 = array("+", "-", "*", "/", "%", ">==", ">=", ">", "<==", "<=", "<", "&gt;==", "&gt;=", "&gt;", "&lt;==", "&lt;=", "&lt;"); if ($pa9880268) foreach ($pa9880268 as $pe5c5e2fe => $v956913c90f) if (isset($v346e1685e3[$pe5c5e2fe])) { if (!is_array($v346e1685e3[$pe5c5e2fe])) $v346e1685e3[$pe5c5e2fe] = array($v346e1685e3[$pe5c5e2fe]); if (is_array($v956913c90f)) $v346e1685e3[$pe5c5e2fe] = array_merge($v346e1685e3[$pe5c5e2fe], $v956913c90f); else $v346e1685e3[$pe5c5e2fe][] = $v956913c90f; } else $v346e1685e3[$pe5c5e2fe] = $v956913c90f; $pb4ccc979 = array_keys($v346e1685e3); $v9898681309 = array("new"); $v7e4b517c18 = 0; $v79b7cb19d1 = $v66327139f0 = false; $v27aa6f602f = TextSanitizer::mbStrSplit($pd621c542); $v0911c6122e = count($v27aa6f602f); for ($v43dd7d0051 = $v7e4b517c18; $v43dd7d0051 < $v0911c6122e; $v43dd7d0051++) { $pc288256e = $v27aa6f602f[$v43dd7d0051]; if ($pc288256e == '"' && !$v66327139f0 && !TextSanitizer::isMBCharEscaped($pd621c542, $v43dd7d0051, $v27aa6f602f)) { $v9a8b7dc209 = implode("", array_slice($v27aa6f602f, $v7e4b517c18, $v43dd7d0051 - $v7e4b517c18)); $v9a8b7dc209 = !$v79b7cb19d1 ? trim($v9a8b7dc209) : $v9a8b7dc209; if (strlen($v9a8b7dc209) || $v79b7cb19d1) { $v04421dfbc1 = $pdccf64a6[ count($pdccf64a6) - 1 ]; $pdccf64a6[] = !$v79b7cb19d1 && (in_array($v9a8b7dc209, $v9898681309) || $v04421dfbc1 == "->" || $v04421dfbc1 == "-&gt;") ? $v9a8b7dc209 : '"' . $v9a8b7dc209 . '"'; } $v7e4b517c18 = $v43dd7d0051 + 1; $v79b7cb19d1 = !$v79b7cb19d1; } else if ($pc288256e == "'" && !$v79b7cb19d1 && !TextSanitizer::isMBCharEscaped($pd621c542, $v43dd7d0051, $v27aa6f602f)) { $v9a8b7dc209 = implode("", array_slice($v27aa6f602f, $v7e4b517c18, $v43dd7d0051 - $v7e4b517c18)); $v9a8b7dc209 = !$v66327139f0 ? trim($v9a8b7dc209) : $v9a8b7dc209; if (strlen($v9a8b7dc209) || $v66327139f0) { $v04421dfbc1 = $pdccf64a6[ count($pdccf64a6) - 1 ]; $pdccf64a6[] = !$v66327139f0 && (in_array($v9a8b7dc209, $v9898681309) || $v04421dfbc1 == "->" || $v04421dfbc1 == "-&gt;") ? $v9a8b7dc209 : "'" . $v9a8b7dc209 . "'"; } $v7e4b517c18 = $v43dd7d0051 + 1; $v66327139f0 = !$v66327139f0; } else if (!$v79b7cb19d1 && !$v66327139f0) { if (($pc288256e == " " || $pc288256e == "," || $pc288256e == '"' || $pc288256e == "'" || $pc288256e == '$') && !TextSanitizer::isMBCharEscaped($pd621c542, $v43dd7d0051, $v27aa6f602f)) { $v9a8b7dc209 = trim(implode("", array_slice($v27aa6f602f, $v7e4b517c18, $v43dd7d0051 - $v7e4b517c18))); if (strlen($v9a8b7dc209)) { $v04421dfbc1 = $pdccf64a6[ count($pdccf64a6) - 1 ]; $pdccf64a6[] = in_array($v9a8b7dc209, $v9898681309) || $v04421dfbc1 == "->" || $v04421dfbc1 == "-&gt;" ? $v9a8b7dc209 : self::ma492610b65a2($v9a8b7dc209, $pc288256e == "'"); } if (self::f2157be11c9($pc288256e, $v346e1685e3)) $pdccf64a6[] = $pc288256e; $v7e4b517c18 = $pc288256e == '$' ? $v43dd7d0051 : $v43dd7d0051 + 1; } else if (in_array($pc288256e, $pb4ccc979)) { $v19cbfb3aee = $v346e1685e3[$pc288256e]; $pfb662071 = is_array($v19cbfb3aee) ? $v19cbfb3aee : array($v19cbfb3aee); $pc857853a = implode("", array_slice($v27aa6f602f, $v43dd7d0051)); foreach ($pfb662071 as $v342a134247) { $v5f7147fb39 = preg_replace("/([\.\+\-\*\/\%\(\)\?])/i", '\\\\$1', $v342a134247); $v04421dfbc1 = implode("", array_slice($v27aa6f602f, 0, $v43dd7d0051 + strlen($v342a134247))); $v327f72fb62 = implode("", array_slice($v27aa6f602f, $v43dd7d0051, strlen($v342a134247))); if ($v342a134247 && $v342a134247 == $v327f72fb62) { $v9faa18b4d7 = $v3d4ebcaf82 = false; $v53e93f1536 = $v342a134247 == "."; if ($v342a134247 == "->" || $v342a134247 == "-&gt;") $v53e93f1536 = $v27aa6f602f[0] == '$'; else if (preg_match('/^' . $v5f7147fb39 . '[ \w\$"\'\(]+/iu', $pc857853a) && ($v342a134247 == "!" || preg_match('/[ \w"\'\)]+' . $v5f7147fb39 . '$/iu', $v04421dfbc1))) $v53e93f1536 = true; else if ($v342a134247 == "+" || $v342a134247 == "-") { $v9faa18b4d7 = preg_match('/^' . $v5f7147fb39 . $v5f7147fb39 . '\s*\$\w+/iu', $pc857853a); $v327f72fb62 = implode("", array_slice($v27aa6f602f, 0, $v43dd7d0051 + 2)); $v3d4ebcaf82 = preg_match('/\$\w+\s*' . $v5f7147fb39 . $v5f7147fb39 . '$/iu', $v327f72fb62); $v53e93f1536 = $v9faa18b4d7 || $v3d4ebcaf82; } $v9a8b7dc209 = implode("", array_slice($v27aa6f602f, $v7e4b517c18, $v43dd7d0051 - $v7e4b517c18)); if (strlen($v9a8b7dc209)) $pdccf64a6[] = self::ma492610b65a2($v9a8b7dc209); if ($v9faa18b4d7) { $pdccf64a6[] = "$v342a134247$v342a134247"; $v7e4b517c18 = $v43dd7d0051 + 2; } else if ($v3d4ebcaf82) { $pdccf64a6[ count($pdccf64a6) - 1 ] = $pdccf64a6[ count($pdccf64a6) - 1 ] . "$v342a134247$v342a134247"; $v7e4b517c18 = $v43dd7d0051 + 2; } else { $pd55652a5 = implode("", array_slice($v27aa6f602f, $v43dd7d0051, strlen($v342a134247))); $pd55652a5 = str_replace("&lt;", "<", str_replace("&gt;", ">", $v342a134247)); $pdccf64a6[] = $v53e93f1536 ? $pd55652a5 : '"' . $pd55652a5 . '"'; $v7e4b517c18 = $v43dd7d0051 + strlen($v342a134247); } $v43dd7d0051 = $v7e4b517c18 - 1; break; } } } else if ($pc288256e == '[') { $v9acf40c110 = trim(implode("", array_slice($v27aa6f602f, $v7e4b517c18, $v43dd7d0051 - $v7e4b517c18))); if (empty($pdccf64a6) || strlen($v9acf40c110)) $v9674f30c56 = $v9acf40c110; else $v9674f30c56 = $pdccf64a6[ count($pdccf64a6) - 1 ]; $pec7c1099 = $v9674f30c56 && preg_match('/^\$\{?\w/iu', trim($v9674f30c56)); if ($pec7c1099) { $v15f3268002 = 0; $v18eb7c1c98 = $v77675b0350 = false; for ($v9d27441e80 = $v43dd7d0051 + 1; $v9d27441e80 < $v0911c6122e; $v9d27441e80++) { $pc288256e = $v27aa6f602f[$v9d27441e80]; if ($pc288256e == '"' && !$v77675b0350 && !TextSanitizer::isMBCharEscaped($pd621c542, $v9d27441e80, $v27aa6f602f)) $v18eb7c1c98 = !$v18eb7c1c98; else if ($pc288256e == "'" && !$v18eb7c1c98 && !TextSanitizer::isMBCharEscaped($pd621c542, $v9d27441e80, $v27aa6f602f)) $v77675b0350 = !$v77675b0350; else if (!$v18eb7c1c98 && !$v77675b0350) { if ($pc288256e == "[") ++$v15f3268002; else if ($pc288256e == "]") { if ($v15f3268002 == 0) break; --$v15f3268002; } } } $pa4fd106d = implode("", array_slice($v27aa6f602f, $v43dd7d0051 + 1, $v9d27441e80 - ($v43dd7d0051 + 1))); $this->f1be2bd43eb($pa4fd106d); $pa4fd106d = preg_replace_callback("/\[([\w\"' \-\+\.]+)\]/u", function ($pbae7526c) { return "[" . (!self::f1b2d6b2aab($pbae7526c[1]) && !is_numeric($pbae7526c[1]) && $pbae7526c[1][0] != '$' ? '"' . trim($pbae7526c[1]) . '"' : $pbae7526c[1]) . "]"; }, $pa4fd106d); if (empty($pdccf64a6) || strlen($v9acf40c110)) $pdccf64a6[] = $v9674f30c56 . "[$pa4fd106d]"; else $pdccf64a6[ count($pdccf64a6) - 1 ] = $v9674f30c56 . "[$pa4fd106d]"; $v43dd7d0051 = $v9d27441e80; $v7e4b517c18 = $v9d27441e80 + 1; } } else if ($pc288256e == "(") { $v9acf40c110 = trim(implode("", array_slice($v27aa6f602f, $v7e4b517c18, $v43dd7d0051 - $v7e4b517c18))); if (empty($pdccf64a6) || strlen($v9acf40c110)) $v9674f30c56 = $v9acf40c110; else { $pb32eba50 = TextSanitizer::mbStrSplit($pdccf64a6[ count($pdccf64a6) - 1 ]); $v9674f30c56 = implode("", array_slice($pb32eba50, 1, -1)); } $v327f72fb62 = trim(implode("", array_slice($v27aa6f602f, 0, $v43dd7d0051))); $v2ad89ab96a = $v9674f30c56 && preg_match('/^\w+$/u', $v9674f30c56) && preg_match('/[^"\']$/iu', $v327f72fb62); if ($v2ad89ab96a) { $v15f3268002 = 0; $v18eb7c1c98 = $v77675b0350 = false; for ($v9d27441e80 = $v43dd7d0051 + 1; $v9d27441e80 < $v0911c6122e; $v9d27441e80++) { $pc288256e = $v27aa6f602f[$v9d27441e80]; if ($pc288256e == '"' && !$v77675b0350 && !TextSanitizer::isMBCharEscaped($pd621c542, $v9d27441e80, $v27aa6f602f)) $v18eb7c1c98 = !$v18eb7c1c98; else if ($pc288256e == "'" && !$v18eb7c1c98 && !TextSanitizer::isMBCharEscaped($pd621c542, $v9d27441e80, $v27aa6f602f)) $v77675b0350 = !$v77675b0350; else if (!$v18eb7c1c98 && !$v77675b0350) { if ($pc288256e == "(") ++$v15f3268002; else if ($pc288256e == ")") { if ($v15f3268002 == 0) break; --$v15f3268002; } } } $pa4fd106d = implode("", array_slice($v27aa6f602f, $v43dd7d0051 + 1, $v9d27441e80 - ($v43dd7d0051 + 1))); $this->f1be2bd43eb($pa4fd106d, ", ", array("," => ",")); if (empty($pdccf64a6) || strlen($v9acf40c110)) $pdccf64a6[] = $v9674f30c56 . "($pa4fd106d)"; else $pdccf64a6[ count($pdccf64a6) - 1 ] = $v9674f30c56 . "($pa4fd106d)"; $v43dd7d0051 = $v9d27441e80; $v7e4b517c18 = $v9d27441e80 + 1; } else { if (strlen($v9acf40c110)) $pdccf64a6[] = self::ma492610b65a2($v9acf40c110); $pdccf64a6[] = $pc288256e; $v7e4b517c18 = $v43dd7d0051 + 1; } } else if ($pc288256e == ")") { $v9acf40c110 = implode("", array_slice($v27aa6f602f, $v7e4b517c18, $v43dd7d0051 - $v7e4b517c18)); if (strlen($v9acf40c110)) $pdccf64a6[] = self::ma492610b65a2($v9acf40c110); $pdccf64a6[] = $pc288256e; $v7e4b517c18 = $v43dd7d0051 + 1; } } } self::prepareArgumentsWithQuotesThatAreNotReservedWords($pdccf64a6, $pd621c542, $v7e4b517c18, $v0911c6122e, $v9898681309); self::f0eb53c452e($pdccf64a6, $v8f4eca0278); self::f17cc1d3703($pdccf64a6, $v3c581c912b, $v8f4eca0278); self::f11ba449f28($pdccf64a6, $v31e957777d); self::f2c7adf343e($pdccf64a6); $pd621c542 = self::mcb048dcfa1f0($pdccf64a6, $v3c581c912b, $v346e1685e3, $v9898681309); } self::prepareTagCodeWithLostQuote($pd621c542); return $pdccf64a6; } public static function prepareArgumentsWithQuotesThatAreNotReservedWords(&$pdccf64a6, $pd621c542, $v7e4b517c18, $v0911c6122e, $v9898681309) { if ($v7e4b517c18 < $v0911c6122e) { $v9a8b7dc209 = trim(substr($pd621c542, $v7e4b517c18, $v0911c6122e - $v7e4b517c18)); if (strlen($v9a8b7dc209)) { $v04421dfbc1 = $pdccf64a6[ count($pdccf64a6) - 1 ]; $pdccf64a6[] = in_array($v9a8b7dc209, $v9898681309) || $v04421dfbc1 == "->" || $v04421dfbc1 == "-&gt;" ? $v9a8b7dc209 : self::ma492610b65a2($v9a8b7dc209); } } } private static function f0eb53c452e(&$pdccf64a6, $v8f4eca0278) { $pc37695cb = count($pdccf64a6); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pea70e132 = $pdccf64a6[$v43dd7d0051]; $v4bb5e2fb32 = $pdccf64a6[$v43dd7d0051 - 1]; $v3bc9fb9b0a = $pdccf64a6[$v43dd7d0051 + 1]; if (in_array($pea70e132, $v8f4eca0278) && (self::f1b2d6b2aab($v4bb5e2fb32) || self::f1b2d6b2aab($v3bc9fb9b0a))) $pdccf64a6[$v43dd7d0051] = '"' . $pea70e132 . '"'; } } private static function f17cc1d3703(&$pdccf64a6, $v3c581c912b, $v8f4eca0278) { $v31e957777d = trim($v3c581c912b); $v10f128e30f = false; $pc37695cb = count($pdccf64a6); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pea70e132 = $pdccf64a6[$v43dd7d0051]; if ($pea70e132 == "?") $v10f128e30f = true; else if ($v10f128e30f && $pea70e132 == ":") { $v4bb5e2fb32 = $pdccf64a6[$v43dd7d0051 - 1]; $v3bc9fb9b0a = $pdccf64a6[$v43dd7d0051 + 1]; if ($v4bb5e2fb32 == "?") { for ($v9d27441e80 = $pc37695cb; $v9d27441e80 >= $v43dd7d0051; $v9d27441e80--) $pdccf64a6[$v9d27441e80] = $pdccf64a6[$v9d27441e80 - 1]; $pdccf64a6[$v43dd7d0051] = '""'; $pc37695cb = count($pdccf64a6); $v43dd7d0051++; } if ($v3bc9fb9b0a == ")") { for ($v9d27441e80 = $pc37695cb; $v9d27441e80 > $v43dd7d0051; $v9d27441e80--) $pdccf64a6[$v9d27441e80] = $pdccf64a6[$v9d27441e80 - 1]; $pdccf64a6[$v43dd7d0051 + 1] = '""'; $pc37695cb = count($pdccf64a6); $v43dd7d0051++; } else if (!isset($v3bc9fb9b0a)) { $pdccf64a6[$v43dd7d0051 + 1] = '""'; $pc37695cb = count($pdccf64a6); } } else if (!$v10f128e30f && $pea70e132 == ":") $pdccf64a6[$v43dd7d0051] = '":"'; } if ($v10f128e30f) { $v7079e09a9c = array_merge($v8f4eca0278, array("===", "==", "!==", "!=", ".", "&&", "||")); $pf3def404 = array(",", ";"); $pdd5f152b = in_array($v31e957777d, $pf3def404); $pa23b8974 = $v8ae4c56f93 = null; $v94399b7718 = 0; $pc37695cb = count($pdccf64a6); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pea70e132 = $pdccf64a6[$v43dd7d0051]; if ($pea70e132 == "?" && $v94399b7718 == 0) $pa23b8974 = $v43dd7d0051; else if (is_numeric($pa23b8974)) { if ($pea70e132 == "(") $v94399b7718++; else if ($pea70e132 == ")") $v94399b7718--; else if ($pea70e132 == ":" && $v94399b7718 == 0) { $v8ae4c56f93 = $v43dd7d0051; $v0f90e928c1 = false; $v749f5dfd70 = true; $pce9201d6 = 0; $v39fe8d6668 = count($pdccf64a6) - 1; for ($v9d27441e80 = $pa23b8974 - 1; $v9d27441e80 >= 0; $v9d27441e80--) { $pea70e132 = $pdccf64a6[$v9d27441e80]; if ($v94399b7718 == 0 && $pea70e132 != ")") { if ($pea70e132 == "(") { $pce9201d6 = $v9d27441e80; break; } else if ($pdd5f152b) { if ($pea70e132 == $v31e957777d) { $pce9201d6 = $v9d27441e80 + 1; break; } else if (in_array($pea70e132, $v7079e09a9c)) $v749f5dfd70 = true; else if (substr($pea70e132, 0, 1) == '$' || self::f1b2d6b2aab($pea70e132) || is_numeric($pea70e132) || strtolower($pea70e132) == "true" || strtolower($pea70e132) == "false" || strtolower($pea70e132) == "null") { if (!$v749f5dfd70) { $pce9201d6 = $v9d27441e80 + 1; break; } $v749f5dfd70 = false; } else { $pce9201d6 = $v9d27441e80 + 1; break; } } } if ($pea70e132 == ")") { $v94399b7718--; $v0f90e928c1 = true; } else if ($pea70e132 == "(") { $v94399b7718++; $v0f90e928c1 = true; } } $v94399b7718 = 0; $v749f5dfd70 = true; for ($v9d27441e80 = $v8ae4c56f93 + 1; $v9d27441e80 < $pc37695cb; $v9d27441e80++) { $pea70e132 = $pdccf64a6[$v9d27441e80]; if ($v94399b7718 == 0 && $pea70e132 != "(") { if ($pea70e132 == ")") { $v39fe8d6668 = $v9d27441e80; break; } else if ($pdd5f152b) { if ($pea70e132 == $v31e957777d) { $v39fe8d6668 = $v9d27441e80 - 1; break; } else if (in_array($pea70e132, $v7079e09a9c)) $v749f5dfd70 = true; else if (substr($pea70e132, 0, 1) == '$' || self::f1b2d6b2aab($pea70e132) || is_numeric($pea70e132) || strtolower($pea70e132) == "true" || strtolower($pea70e132) == "false" || strtolower($pea70e132) == "null") { if (!$v749f5dfd70) { $v39fe8d6668 = $v9d27441e80 - 1; break; } $v749f5dfd70 = false; } else { $v39fe8d6668 = $v9d27441e80 - 1; break; } } } if ($pea70e132 == ")") { $v94399b7718--; $v0f90e928c1 = true; } else if ($pea70e132 == "(") { $v94399b7718++; $v0f90e928c1 = true; } } if ($pdccf64a6[$pce9201d6] != "(" || $pdccf64a6[$v39fe8d6668] != ")" || $v0f90e928c1) { $pcd92491e = array_slice($pdccf64a6, 0, $pce9201d6); $v8fb43e610d = array_slice($pdccf64a6, $pce9201d6, ($v39fe8d6668 + 1) - $pce9201d6); $pfeee5d7d = array_slice($pdccf64a6, $v39fe8d6668 + 1); array_unshift($v8fb43e610d, "("); $v8fb43e610d[] = ")"; $pdccf64a6 = array_merge($pcd92491e, $v8fb43e610d, $pfeee5d7d); $pc37695cb = count($pdccf64a6); $pa23b8974++; $v8ae4c56f93++; $pce9201d6++; $v39fe8d6668++; } else if ($pdccf64a6[$pce9201d6] == "(" && $pdccf64a6[$v39fe8d6668] == ")" && !$v0f90e928c1) { $pce9201d6++; $v39fe8d6668--; } $v783abe06e6 = array_slice($pdccf64a6, $pa23b8974 + 1, $v8ae4c56f93 - ($pa23b8974 + 1)); $v9786bdfb32 = array_slice($pdccf64a6, $v8ae4c56f93 + 1, ($v39fe8d6668 + 1) - ($v8ae4c56f93 + 1)); self::f17cc1d3703($v783abe06e6, $v3c581c912b, $v8f4eca0278); self::f17cc1d3703($v9786bdfb32, $v3c581c912b, $v8f4eca0278); $pa23b8974 = $v8ae4c56f93 = null; $v94399b7718 = 0; $v43dd7d0051 = $v39fe8d6668; } } } } } private static function f11ba449f28(&$pdccf64a6, $v31e957777d) { $v7079e09a9c = array(".", "+", "-", "*", "/", "%"); $v94399b7718 = 0; $pc37695cb = count($pdccf64a6); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pea70e132 = $pdccf64a6[$v43dd7d0051]; $v4bb5e2fb32 = $pdccf64a6[$v43dd7d0051 - 1]; $v3bc9fb9b0a = $pdccf64a6[$v43dd7d0051 + 1]; if (substr($pea70e132, 0, 1) == '$' && self::f1b2d6b2aab($v4bb5e2fb32) && self::f1b2d6b2aab($v3bc9fb9b0a)) { $v5b6376bac9 = substr(trim(substr($v3bc9fb9b0a, 1)), 0, 1) == "}"; if (substr($v4bb5e2fb32, -2, 1) == "{" && $v5b6376bac9) { $pdccf64a6[$v43dd7d0051 - 1] = substr($v4bb5e2fb32, 0, -2) . substr($v4bb5e2fb32, -1); $pdccf64a6[$v43dd7d0051 + 1] = substr($v3bc9fb9b0a, 0, 1) . substr(trim(substr($v3bc9fb9b0a, 1)), 1); } else if (substr($pea70e132, 1, 1) == "{") { $pdccf64a6[$v43dd7d0051] = '$' . substr($pdccf64a6[$v43dd7d0051], 2); if ($v5b6376bac9) $pdccf64a6[$v43dd7d0051 + 1] = substr($v3bc9fb9b0a, 0, 1) . substr(trim(substr($v3bc9fb9b0a, 1)), 1); } } else if ($pea70e132 == "(" && $v3bc9fb9b0a == ")") { $pdccf64a6[$v43dd7d0051] = "."; $pdccf64a6[$v43dd7d0051 + 1] = '"()"'; $v43dd7d0051--; } else if (self::f1b2d6b2aab($pea70e132) && self::f1b2d6b2aab($v3bc9fb9b0a) && ($v31e957777d == "." || ($v94399b7718 > 0 && $v31e957777d != ",")) && $pea70e132[0] == $v3bc9fb9b0a[0]) { $pdccf64a6[$v43dd7d0051] = false; $pdccf64a6[$v43dd7d0051 + 1] = $pea70e132[0] . substr($pea70e132, 1, -1) . substr($v3bc9fb9b0a, 1, -1) . $pea70e132[0]; } else if (in_array($pea70e132, $v7079e09a9c)) { $padaea9af = self::f1b2d6b2aab($v4bb5e2fb32); $v87fb7e9dec = self::f1b2d6b2aab($v3bc9fb9b0a); if ($padaea9af && $v87fb7e9dec && $v4bb5e2fb32[0] == $v3bc9fb9b0a[0]) { $pdccf64a6[$v43dd7d0051 - 1] = $pdccf64a6[$v43dd7d0051] = false; $pdccf64a6[$v43dd7d0051 + 1] = substr($v4bb5e2fb32, 0, -1) . ($pea70e132 == "." ? "" : $pea70e132) . substr($v3bc9fb9b0a, 1); } else if ($padaea9af && is_numeric($v3bc9fb9b0a)) { $pdccf64a6[$v43dd7d0051 - 1] = $pdccf64a6[$v43dd7d0051] = false; $pdccf64a6[$v43dd7d0051 + 1] = substr($v4bb5e2fb32, 0, -1) . ($pea70e132 == "." ? "" : $pea70e132) . $v3bc9fb9b0a . $v4bb5e2fb32[0]; } else if (is_numeric($v4bb5e2fb32) && $v87fb7e9dec) { $pdccf64a6[$v43dd7d0051 - 1] = $pdccf64a6[$v43dd7d0051] = false; $pdccf64a6[$v43dd7d0051 + 1] = $v3bc9fb9b0a[0] . $v4bb5e2fb32 . ($pea70e132 == "." ? "" : $pea70e132) . substr($v3bc9fb9b0a, 1); } else if ($padaea9af && $pea70e132 != ".") { $pdccf64a6[$v43dd7d0051 - 1] = substr($v4bb5e2fb32, 0, -1) . $pea70e132 . $v4bb5e2fb32[0]; $pdccf64a6[$v43dd7d0051] = "."; } else if ($v87fb7e9dec && $pea70e132 != ".") { $pdccf64a6[$v43dd7d0051] = "."; $pdccf64a6[$v43dd7d0051 + 1] = $v3bc9fb9b0a[0] . $pea70e132 . substr($v3bc9fb9b0a, 1); } } else if ($pea70e132 == "(") ++$v94399b7718; else if ($pea70e132 == ")") --$v94399b7718; } } private static function f2c7adf343e(&$pdccf64a6) { $pdccf64a6 = array_values(array_filter($pdccf64a6, function($v342a134247) { return strlen($v342a134247) > 0; } )); } private static function mcb048dcfa1f0(&$pdccf64a6, $v3c581c912b, $v346e1685e3, $v9898681309) { $pd621c542 = ""; $v94399b7718 = 0; $pc37695cb = count($pdccf64a6); $v31e957777d = trim($v3c581c912b); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pea70e132 = $pdccf64a6[$v43dd7d0051]; $v4bb5e2fb32 = $pdccf64a6[$v43dd7d0051 - 1]; $v3bc9fb9b0a = $pdccf64a6[$v43dd7d0051 + 1]; $v7e09c0552a = self::f2157be11c9($pea70e132, $v346e1685e3); $pabf9b7f8 = in_array($v4bb5e2fb32, $v9898681309); $pd621c542 .= strlen($pd621c542) && !$v7e09c0552a && !self::f2157be11c9($v4bb5e2fb32, $v346e1685e3) && !$pabf9b7f8 && $v4bb5e2fb32 != "(" && $pea70e132 != ")" ? ( $v94399b7718 > 0 ? " . " : $v3c581c912b ) : ""; if ($pea70e132 == "." && is_numeric($v4bb5e2fb32) && is_numeric($v3bc9fb9b0a)) $pd621c542 .= $pea70e132; else if ($pabf9b7f8) $pd621c542 .= " $pea70e132"; else if ($v7e09c0552a && $pea70e132 != "!" && $pea70e132 != "->") $pd621c542 .= " $pea70e132 "; else if ( (strtolower($pea70e132) == "true" || strtolower($pea70e132) == "false") && (strlen($v4bb5e2fb32) || strlen($v3bc9fb9b0a)) && (!strlen($v4bb5e2fb32) || $v4bb5e2fb32 == "." || !self::f2157be11c9($v4bb5e2fb32, $v346e1685e3)) && (!strlen($v3bc9fb9b0a) || $v3bc9fb9b0a == "." || !self::f2157be11c9($v3bc9fb9b0a, $v346e1685e3)) ) $pd621c542 .= '"' . $pea70e132 . '"'; else $pd621c542 .= $pea70e132; if ($pea70e132 == "(") ++$v94399b7718; else if ($pea70e132 == ")") --$v94399b7718; } $pd621c542 = str_replace("&amp;", "&", str_replace("&gt;", ">", $pd621c542)); return $pd621c542; } public static function prepareTagCodeWithLostQuote(&$pd621c542) { if ((substr($pd621c542, -1) == '"' || substr($pd621c542, -1) == "'") && TextSanitizer::isCharEscaped($pd621c542, strlen($pd621c542) - 1)) $pd621c542 = substr($pd621c542, 0, -1) . '\\' . substr($pd621c542, -1); } private static function f1b2d6b2aab($pea70e132) { return ($pea70e132[0] == '"' && substr($pea70e132, -1) == '"') || ($pea70e132[0] == "'" && substr($pea70e132, -1) == "'"); } private static function f2157be11c9($pea70e132, $v346e1685e3) { $pc288256e = substr($pea70e132, 0, 1); $pb4ccc979 = array_keys($v346e1685e3); if (in_array($pc288256e, $pb4ccc979)) { $v19cbfb3aee = $v346e1685e3[$pc288256e]; $pfb662071 = is_array($v19cbfb3aee) ? $v19cbfb3aee : array($v19cbfb3aee); return in_array($pea70e132, $pfb662071); } return false; } private static function ma492610b65a2($pea70e132, $v0ad2b2e55b = false) { if (is_numeric($pea70e132) || $pea70e132[0] == '$') return $pea70e132; else if ($pea70e132[0] == '"') return $pea70e132 . (substr($pea70e132, -1) == '"' ? "" : '"'); else if ($pea70e132[0] == "'") return $pea70e132 . (substr($pea70e132, -1) == "'" ? "" : "'"); else if (strtolower($pea70e132) == "true" || strtolower($pea70e132) == "false") return $pea70e132; return ($v0ad2b2e55b ? "'" : '"') . $pea70e132 . ($v0ad2b2e55b ? "'" : '"'); } } ?>
