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
include_once get_lib("org.phpframework.phpscript.PHPCodePrintingHandler"); include_once $EVC->getUtilPath("WorkFlowQueryHandler"); class WorkFlowPHPFileHandler { private static function md8f5d1b9d55a($pbc96d822, $v84bd6a89cd, $pf3dc0762) { if ($pbc96d822) { $pd8192d9d = $pbc96d822->getRootPath(); $v0ff71d0593 = CacheHandlerUtil::getCacheFilePath($pd8192d9d . $v84bd6a89cd); if (file_exists($v0ff71d0593)) { $pb0277fa1 = filemtime($v0ff71d0593); return $pbc96d822->isValid($v84bd6a89cd) && $pb0277fa1 > filemtime($pf3dc0762); } } return false; } public static function getClassData($pf3dc0762, $pfef14f0b, $pbc96d822) { $v84bd6a89cd = "php_files_code_reader/" . md5("$pf3dc0762/class:$pfef14f0b"); if (self::md8f5d1b9d55a($pbc96d822, $v84bd6a89cd, $pf3dc0762)) $pf232dd5a = $pbc96d822->read($v84bd6a89cd); else { $pf232dd5a = PHPCodePrintingHandler::getClassFromFile($pf3dc0762, $pfef14f0b); if ($pf232dd5a) { $pf232dd5a["includes"] = PHPCodePrintingHandler::getIncludesFromFile($pf3dc0762); $pf232dd5a["uses"] = PHPCodePrintingHandler::getUsesFromFile($pf3dc0762); $pf232dd5a["properties"] = PHPCodePrintingHandler::getClassPropertiesFromFile($pf3dc0762, $pfef14f0b); if ($pbc96d822) $pbc96d822->write($v84bd6a89cd, $pf232dd5a); } } return $pf232dd5a; } public static function getClassMethodData($pf3dc0762, $pfef14f0b, $v834146ce8d, $pbc96d822) { $v84bd6a89cd = "php_files_code_reader/" . md5("$pf3dc0762/method:$pfef14f0b-$v834146ce8d"); if (self::md8f5d1b9d55a($pbc96d822, $v84bd6a89cd, $pf3dc0762)) $pf232dd5a = $pbc96d822->read($v84bd6a89cd); else { $pf232dd5a = PHPCodePrintingHandler::getFunctionFromFile($pf3dc0762, $v834146ce8d, $pfef14f0b); if ($pf232dd5a) { $pf232dd5a["code"] = PHPCodePrintingHandler::getFunctionCodeFromFile($pf3dc0762, $v834146ce8d, $pfef14f0b); if ($pbc96d822) $pbc96d822->write($v84bd6a89cd, $pf232dd5a); } } return $pf232dd5a; } public static function getFunctionData($pf3dc0762, $v38c5904848, $pbc96d822) { $v84bd6a89cd = "php_files_code_reader/" . md5("$pf3dc0762/func:$v38c5904848"); if (self::md8f5d1b9d55a($pbc96d822, $v84bd6a89cd, $pf3dc0762)) { $pf232dd5a = $pbc96d822->read($v84bd6a89cd); } else { $pf232dd5a = PHPCodePrintingHandler::getFunctionFromFile($pf3dc0762, $v38c5904848); if ($pf232dd5a) { $pf232dd5a["code"] = PHPCodePrintingHandler::getFunctionCodeFromFile($pf3dc0762, $v38c5904848); if ($pbc96d822) $pbc96d822->write($v84bd6a89cd, $pf232dd5a); } } return $pf232dd5a; } public static function getIncludesAndNamespacesAndUsesData($pf3dc0762, $pbc96d822) { $v84bd6a89cd = "php_files_code_reader/" . md5("$pf3dc0762/includes"); if (self::md8f5d1b9d55a($pbc96d822, $v84bd6a89cd, $pf3dc0762)) $pf232dd5a = $pbc96d822->read($v84bd6a89cd); else { $pf232dd5a = array( "includes" => PHPCodePrintingHandler::getIncludesFromFile($pf3dc0762), "namespaces" => PHPCodePrintingHandler::getNamespacesFromFile($pf3dc0762), "uses" => PHPCodePrintingHandler::getUsesFromFile($pf3dc0762), ); if ($pbc96d822) $pbc96d822->write($v84bd6a89cd, $pf232dd5a); } return $pf232dd5a; } public static function saveClass($pf3dc0762, $v4948cc5869, $pfef14f0b = false, $v735838fe3c = true) { $v47bb97a9ac = $v4948cc5869["name"]; if ($pf3dc0762 && $v47bb97a9ac) { $v9ff79a4a24 = $pfef14f0b ? $pfef14f0b : $v47bb97a9ac; $pf3dc0762 = is_file($pf3dc0762) ? $pf3dc0762 : "$pf3dc0762/$v9ff79a4a24.php"; if ($v4948cc5869["properties"]) { $v9073377656 = empty($v4948cc5869["properties"]) || is_array($v4948cc5869["properties"]) ? $v4948cc5869["properties"] : array($v4948cc5869["properties"]); $v067674f4e4 = ""; $pc37695cb = $v9073377656 ? count($v9073377656) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) $v067674f4e4 .= PHPCodePrintingHandler::getClassPropertyString($v9073377656[$v43dd7d0051]) . "\n"; if (!empty($v067674f4e4)) $v4948cc5869["code"] = $v067674f4e4; } if (!isset($v4948cc5869["code"])) $v4948cc5869["code"] = ""; $v4948cc5869["includes"] = empty($v4948cc5869["includes"]) ? array() : $v4948cc5869["includes"]; $v4948cc5869["includes"] = is_array($v4948cc5869["includes"]) ? $v4948cc5869["includes"] : array($v4948cc5869["includes"]); $v6824ec2eb2 = array(); $pc37695cb = count($v4948cc5869["includes"]); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pc24afc88 = $v4948cc5869["includes"][$v43dd7d0051]; $v1daf7d6151 = $pc24afc88["var_type"]; $pbdb96933 = $v1daf7d6151 == "string" ? "\"" . addcslashes($pc24afc88["path"], '"') . "\"" : $pc24afc88["path"]; $v6824ec2eb2[] = array($pbdb96933, $pc24afc88["once"]); } $v4948cc5869["includes"] = $v6824ec2eb2; if ($pfef14f0b && $v47bb97a9ac) { PHPCodePrintingHandler::removeUsesFromFile($pf3dc0762); PHPCodePrintingHandler::removeIncludesFromFile($pf3dc0762); PHPCodePrintingHandler::editClassCommentsFromFile($pf3dc0762, $pfef14f0b, ""); $pb543f4d4 = PHPCodePrintingHandler::decoupleClassNameWithNameSpace($pfef14f0b); $v5c1c342594 = PHPCodePrintingHandler::editClassFromFile($pf3dc0762, array("name" => $pb543f4d4["name"], "namespace" => $pb543f4d4["namespace"]), $v4948cc5869); } else if (!$pfef14f0b) { if ($v4948cc5869["includes"]) { $pc54400f3 = PHPCodePrintingHandler::getIncludesFromFile($pf3dc0762); if ($pc54400f3) { $v107744c2fb = array(); foreach ($pc54400f3 as $v2d22d85b1f) $v107744c2fb[] = $v2d22d85b1f[0]; foreach ($v4948cc5869["includes"] as $pd69fb7d0 => $v2d22d85b1f) if (in_array($v2d22d85b1f[0], $v107744c2fb)) unset($v4948cc5869["includes"][$pd69fb7d0]); } } $v5c1c342594 = PHPCodePrintingHandler::addClassToFile($pf3dc0762, $v4948cc5869); } if ($v5c1c342594 && $v735838fe3c) { $v5442ff2fbd = pathinfo($pf3dc0762); if ($v5442ff2fbd["filename"] != $v47bb97a9ac) $v5c1c342594 = rename($pf3dc0762, $v5442ff2fbd["dirname"] . "/$v47bb97a9ac." . $v5442ff2fbd["extension"]); } } return $v5c1c342594; } public static function removeClass($pf3dc0762, $pfef14f0b, $pcee4a69f = true) { if ($pf3dc0762 && $pfef14f0b && PHPCodePrintingHandler::removeClassFromFile($pf3dc0762, $pfef14f0b)) { $v539082ff30 = PHPCodePrintingHandler::getPHPClassesFromFile($pf3dc0762); if ($v539082ff30) { unset($v539082ff30[0]["namespaces"]); unset($v539082ff30[0]["uses"]); unset($v539082ff30[0]["includes"]); if (isset($v539082ff30[0]) && empty($v539082ff30[0])) unset($v539082ff30[0]); } if (empty($v539082ff30) && $pcee4a69f) return unlink($pf3dc0762); else return true; } return false; } public static function saveClassMethod($pf3dc0762, $v4948cc5869, $pfef14f0b, $v834146ce8d = false) { if ($pf3dc0762 && is_file($pf3dc0762) && $pfef14f0b && $v4948cc5869["name"]) { self::f5de448901b($v4948cc5869); self::f9ce1e84bbc($v4948cc5869); if ($v834146ce8d) { PHPCodePrintingHandler::editFunctionCommentsFromFile($pf3dc0762, $v834146ce8d, "", $pfef14f0b); $v5c1c342594 = PHPCodePrintingHandler::editFunctionFromFile($pf3dc0762, array("name" => $v834146ce8d), $v4948cc5869, $pfef14f0b); } else { $v5c1c342594 = PHPCodePrintingHandler::addFunctionToFile($pf3dc0762, $v4948cc5869, $pfef14f0b); } } return $v5c1c342594; } public static function removeClassMethod($pf3dc0762, $pfef14f0b, $v834146ce8d) { return $pf3dc0762 && $pfef14f0b && $v834146ce8d ? PHPCodePrintingHandler::removeFunctionFromFile($pf3dc0762, $v834146ce8d, $pfef14f0b) : false; } public static function saveFunction($pf3dc0762, $v4948cc5869, $v38c5904848 = false) { if ($pf3dc0762 && $v4948cc5869["name"]) { self::f5de448901b($v4948cc5869); self::f9ce1e84bbc($v4948cc5869); $v5442ff2fbd = pathinfo($pf3dc0762); if (is_file($pf3dc0762)) { if ($v4948cc5869["file_name"] && strtolower($v5442ff2fbd["filename"]) != strtolower($v4948cc5869["file_name"])) { $v3f7a88fc27 = $v5442ff2fbd["dirname"] . "/" . ($v4948cc5869["file_name"] ? $v4948cc5869["file_name"] : "functions") . "." . $v5442ff2fbd["extension"]; PHPCodePrintingHandler::removeFunctionFromFile($pf3dc0762, $v38c5904848); $v5c1c342594 = PHPCodePrintingHandler::addFunctionToFile($v3f7a88fc27, $v4948cc5869); } else if ($v38c5904848) { PHPCodePrintingHandler::editFunctionCommentsFromFile($pf3dc0762, $v38c5904848, ""); $v5c1c342594 = PHPCodePrintingHandler::editFunctionFromFile($pf3dc0762, array("name" => $v38c5904848), $v4948cc5869); } else { $v5c1c342594 = PHPCodePrintingHandler::addFunctionToFile($pf3dc0762, $v4948cc5869); } } else { $v3f7a88fc27 = $pf3dc0762 . "/" . ($v4948cc5869["file_name"] ? $v4948cc5869["file_name"] : "functions") . ".php"; $v5c1c342594 = PHPCodePrintingHandler::addFunctionToFile($v3f7a88fc27, $v4948cc5869); } } return $v5c1c342594; } public static function removeFunction($pf3dc0762, $v38c5904848) { return $pf3dc0762 && $v38c5904848 ? PHPCodePrintingHandler::removeFunctionFromFile($pf3dc0762, $v38c5904848) : false; } public static function saveIncludesAndNamespacesAndUses($pf3dc0762, $v4948cc5869) { return self::f2a3c0d6563($pf3dc0762, $v4948cc5869["uses"]) && self::mb88c302f8b67($pf3dc0762, $v4948cc5869["includes"]) && self::f53cdfcbdad($pf3dc0762, $v4948cc5869["namespaces"]); } private static function f53cdfcbdad($pf3dc0762, $v1480702cf7) { $v5c1c342594 = PHPCodePrintingHandler::removeNamespacesFromFile($pf3dc0762); if ($v5c1c342594 && !empty($v1480702cf7)) { $v1480702cf7 = is_array($v1480702cf7) ? $v1480702cf7 : array($v1480702cf7); $v5c1c342594 = PHPCodePrintingHandler::addNamespacesToFile($pf3dc0762, $v1480702cf7); } return $v5c1c342594; } private static function f2a3c0d6563($pf3dc0762, $v7fe521c48d) { $v5c1c342594 = PHPCodePrintingHandler::removeUsesFromFile($pf3dc0762); if ($v5c1c342594 && !empty($v7fe521c48d)) { $v7fe521c48d = is_array($v7fe521c48d) ? $v7fe521c48d : array($v7fe521c48d); $v5c1c342594 = PHPCodePrintingHandler::addUsesToFile($pf3dc0762, $v7fe521c48d); } return $v5c1c342594; } private static function mb88c302f8b67($pf3dc0762, $pc06f1034) { $v5c1c342594 = PHPCodePrintingHandler::removeIncludesFromFile($pf3dc0762); if ($v5c1c342594 && !empty($pc06f1034)) { $pc06f1034 = is_array($pc06f1034) ? $pc06f1034 : array($pc06f1034); $v6824ec2eb2 = array(); $pc37695cb = count($pc06f1034); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pc24afc88 = $pc06f1034[$v43dd7d0051]; $v1daf7d6151 = $pc24afc88["var_type"]; $pbdb96933 = $v1daf7d6151 == "string" ? "\"" . addcslashes($pc24afc88["path"], '"') . "\"" : $pc24afc88["path"]; $v6824ec2eb2[] = array($pbdb96933, $pc24afc88["once"]); } $v5c1c342594 = PHPCodePrintingHandler::addIncludesToFile($pf3dc0762, $v6824ec2eb2); } return $v5c1c342594; } private static function f5de448901b(&$v4948cc5869) { if (isset($v4948cc5869["arguments"])) { $pd84fffb2 = array(); $pc37695cb = $v4948cc5869["arguments"] ? count($v4948cc5869["arguments"]) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pea70e132 = $v4948cc5869["arguments"][$v43dd7d0051]; $v5e813b295b = $pea70e132["name"]; $v67db1bd535 = $pea70e132["value"]; $v7f2aac9119 = $pea70e132["var_type"]; $pd84fffb2[$v5e813b295b] = $v7f2aac9119 != "string" && empty($v67db1bd535) && !is_numeric($v67db1bd535) ? null : ($v7f2aac9119 == "string" ? "\"" . addcslashes($v67db1bd535, '"') . "\"" : $v67db1bd535); } $v4948cc5869["arguments"] = $pd84fffb2; } } private static function f9ce1e84bbc(&$v4948cc5869) { $pcc2fe66c = isset($v4948cc5869["comments"]) && trim($v4948cc5869["comments"]) ? " * " . str_replace("\n", "\n * ", trim($v4948cc5869["comments"])) . "\n" : ""; if (isset($v4948cc5869["annotations"]) && is_array($v4948cc5869["annotations"])) { $pcc2fe66c .= $pcc2fe66c ? " * \n" : ""; foreach ($v4948cc5869["annotations"] as $v18f86e4308 => $pf81e7d81) { $pe9daf1fc = strtolower($v18f86e4308); $pc37695cb = $pf81e7d81 ? count($pf81e7d81) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v1ebe15b151 = $pf81e7d81[$v43dd7d0051]; $v5e813b295b = trim($v1ebe15b151["name"]); $v86066462c3 = ""; $v86066462c3 .= $v5e813b295b ? ($v86066462c3 ? ", " : "") . "name=" . $v5e813b295b : ""; $v86066462c3 .= trim($v1ebe15b151["type"]) ? ($v86066462c3 ? ", " : "") . "type=" . $v1ebe15b151["type"] : ""; $v86066462c3 .= trim($v1ebe15b151["not_null"]) ? ($v86066462c3 ? ", " : "") . "not_null=1" : ""; $v86066462c3 .= strlen(trim($v1ebe15b151["default"])) ? ($v86066462c3 ? ", " : "") . "default=" . $v1ebe15b151["default"] : ""; $v86066462c3 .= trim($v1ebe15b151["others"]) ? ($v86066462c3 ? ", " : "") . $v1ebe15b151["others"] : ""; if ($v86066462c3 || trim($v1ebe15b151["desc"])) { $pcc2fe66c .= " * @$pe9daf1fc ($v86066462c3) " . addcslashes($v1ebe15b151["desc"], '"') . "\n"; } } } } $v4948cc5869["comments"] = $pcc2fe66c ? "/**\n$pcc2fe66c */" : ""; } public static function getChoosePHPClassFromFileManagerHtml($v6258371fe6) { $pf8ed4912 = '<div id="choose_php_class_from_file_manager" class="myfancypopup">
			<ul class="mytree">
				<li>
					<label>Root</label>
					<ul url="' . str_replace("#path#", "", $v6258371fe6) . '"></ul>
				</li>
			</ul>
			<div class="button">
				<input type="button" value="Update" onClick="MyFancyPopup.settings.updateFunction(this)" />
			</div>
		</div>'; return $pf8ed4912; } public static function getUseHTML($v1578057dc9 = false, $v7c3c74d27f = false) { return '
			<div class="use">
				<label>Use </label>
				<input class="use_name" type="text" value="' . $v1578057dc9 . '" placeHolder="namespace" />
				<label class="use_as"> as </label>
				<input class="use_alias" type="text" value="' . $v7c3c74d27f . '" placeHolder="alias" />
				<span class="icon delete" onClick="$(this).parent().remove();" title="Delete">Remove</span>
			</div>'; } public static function getInludeHTML($pc24afc88 = false) { $v434e0ba045 = $pc24afc88 ? $pc24afc88[0] : ""; $pee6ea13c = $pc24afc88 && $pc24afc88[1]; $v1feee46607 = substr($v434e0ba045, 0, 1) == '"' || substr($v434e0ba045, 0, 1) == '"' ? "string" : ""; $v434e0ba045 = trim($v434e0ba045); $v34f0a629d3 = substr($v434e0ba045, 0, 1); if ($v34f0a629d3 == '"' || $v34f0a629d3 == "'") { $v434e0ba045 = substr($v434e0ba045, 1, -1); $v434e0ba045 = $v34f0a629d3 == '"' ? str_replace('\\"', '"', $v434e0ba045) : str_replace("\\'", "'", $v434e0ba045); } return '
			<div class="include">
				<label>Path:</label>
				<input class="include_path" type="text" value="' . str_replace('"', "&quot;", $v434e0ba045) . '" />
				<select class="include_type">
					<option value="string">string</option>
					<option value=""' . ($v1feee46607 == "" ? ' selected' : '') . '>default</option>
				</select>
				<input class="include_once" type="checkbox" value="1" title="Check this if include/require once"' . ($pee6ea13c ? ' checked' : '') . '/>
				<span class="icon search" onClick="getIncludePathFromFileManager(this, \'input\')" title="Get file from File Manager">Search</span>
				<span class="icon delete" onClick="$(this).parent().remove();" title="Delete">Remove</span>
			</div>'; } public static function getPropertyHTML($v1654ac0a73 = false) { $pe9716498 = $v1654ac0a73["name"]; $v463ddec21a = $v1654ac0a73["value"]; $v008fc6ff5f = $v1654ac0a73["var_type"]; $pcc2fe66c = ""; if ($v1654ac0a73["comments"] || $v1654ac0a73["doc_comments"]) { $v454a8d0d34 = $v1654ac0a73["doc_comments"] ? implode("\n", $v1654ac0a73["doc_comments"]) : ""; $v454a8d0d34 = trim($v454a8d0d34); $v454a8d0d34 = str_replace("\r", "", $v454a8d0d34); $v454a8d0d34 = preg_replace("/^\/[*]+\s*/", "", $v454a8d0d34); $v454a8d0d34 = preg_replace("/\s*[*]+\/\s*$/", "", $v454a8d0d34); $v454a8d0d34 = preg_replace("/\s*\n\s*[*]*\s*/", "\n", $v454a8d0d34); $v454a8d0d34 = preg_replace("/^\s*[*]*\s*/", "", $v454a8d0d34); $v454a8d0d34 = trim($v454a8d0d34); $pcc2fe66c = is_array($v1654ac0a73["comments"]) ? trim(implode("\n", $v1654ac0a73["comments"])) : ""; $pcc2fe66c .= $v454a8d0d34 ? "\n" . trim($v454a8d0d34) : ""; $pcc2fe66c = str_replace(array("/*", "*/", "//"), "", $pcc2fe66c); $pcc2fe66c = trim($pcc2fe66c); } $v008fc6ff5f = $v008fc6ff5f ? strtolower($v008fc6ff5f) : (isset($v463ddec21a) && (substr($v463ddec21a, 0, 1) == '"' || substr($v463ddec21a, 0, 1) == '"') ? "string" : ""); $v008fc6ff5f = empty($v463ddec21a) ? "string" : $v008fc6ff5f; $pe9716498 = trim($pe9716498); $pe9716498 = substr($pe9716498, 0, 1) == "\$" ? substr($pe9716498, 1) : $pe9716498; $v463ddec21a = trim($v463ddec21a); $v34f0a629d3 = substr($v463ddec21a, 0, 1); if ($v34f0a629d3 == '"' || $v34f0a629d3 == "'") { $v463ddec21a = substr($v463ddec21a, 1, -1); $v463ddec21a = $v34f0a629d3 == '"' ? str_replace('\\"', '"', $v463ddec21a) : str_replace("\\'", "'", $v463ddec21a); } $v4159504aa3 = array("public", "private", "protected", "const"); $v1f8fb57ab1 = array("string" => "string", "" => "default"); $pf8ed4912 = '
			<tr class="property">
				<td class="name">
					<input type="text" value="' . str_replace('"', "&quot;", $pe9716498) . '" />
				</td>
				<td class="value">
					<input type="text" value="' . str_replace('"', "&quot;", $v463ddec21a) . '" />
				</td>
				<td class="type">
					<select>'; $pc37695cb = count($v4159504aa3); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) $pf8ed4912 .= '<option' . (strtolower($v4159504aa3[$v43dd7d0051]) == $v1654ac0a73["type"] || ($v4159504aa3[$v43dd7d0051] == "const" && $v1654ac0a73["const"]) ? " selected" : "") . '>' . $v4159504aa3[$v43dd7d0051] . '</option>'; $pf8ed4912 .= '
					</select>
				</td>
				<td class="static">
					<input type="checkbox" value="1" ' . ($v1654ac0a73["static"] ? "checked" : "" ) . ' />
				</td>
				<td class="var_type">
					<select>'; foreach ($v1f8fb57ab1 as $v956913c90f => $pe5c5e2fe) $pf8ed4912 .= '<option' . ($v956913c90f == $v008fc6ff5f ? " selected" : "") . '>' . $pe5c5e2fe . '</option>'; $pf8ed4912 .= '
					</select>
				</td>
				<td class="comments">
					<input type="text" value="' . str_replace('"', "&quot;", $pcc2fe66c) . '" />
				</td>
				<td class="icon_cell table_header"><span class="icon delete" onClick="$(this).parent().parent().remove();" title="Delete">Remove</span></td>
			</tr>'; return $pf8ed4912; } public static function getArgumentHTML($v3e2ea7c182 = false, $v15f6545050 = false, $v9439b47442 = false) { $v1f8fb57ab1 = array("default", "string"); $v9439b47442 = $v9439b47442 ? strtolower($v9439b47442) : (isset($v15f6545050) && (substr($v15f6545050, 0, 1) == '"' || substr($v15f6545050, 0, 1) == "'") ? "string" : ""); $v3e2ea7c182 = trim($v3e2ea7c182); $v3e2ea7c182 = substr($v3e2ea7c182, 0, 1) == "\$" ? substr($v3e2ea7c182, 1) : $v3e2ea7c182; $v15f6545050 = trim($v15f6545050); $v34f0a629d3 = substr($v15f6545050, 0, 1); if ($v34f0a629d3 == '"' || $v34f0a629d3 == "'") { $v15f6545050 = substr($v15f6545050, 1, -1); $v15f6545050 = $v34f0a629d3 == '"' ? str_replace('\\"', '"', $v15f6545050) : str_replace("\\'", "'", $v15f6545050); } $pf8ed4912 = '<tr class="argument">
				<td class="name">
					<input type="text" value="' . str_replace('"', "&quot;", $v3e2ea7c182) . '" onBlur="onBlurArgumentName(this)" />
				</td>
				<td class="value">
					<input type="text" value="' . str_replace('"', "&quot;", $v15f6545050) . '" />
				</td>
				<td class="var_type">
					<select>'; $pc37695cb = count($v1f8fb57ab1); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pf8ed4912 .= '<option' . (strtolower($v1f8fb57ab1[$v43dd7d0051]) == $v9439b47442 ? " selected" : "") . '>' . $v1f8fb57ab1[$v43dd7d0051] . '</option>'; } $pf8ed4912 .= '
					</select>
				</td>
				<td class="icon_cell table_header"><span class="icon delete" onClick="removeArgument(this)" title="Delete">Remove</span></td>
			</tr>'; return $pf8ed4912; } public static function getAnnotationHTML($ped0a6251 = false, $v18f86e4308 = false) { $v5e813b295b = $v3fb9f41470 = $pf6e8b316 = $v4bfe0500a2 = $pd2c744e8 = $pe9715da5 = ""; if (is_array($ped0a6251)) { $v5e813b295b = $ped0a6251["name"]; $v3fb9f41470 = $ped0a6251["type"]; $pf6e8b316 = !empty($ped0a6251["not_null"]); $v4bfe0500a2 = $ped0a6251["default"]; $pd2c744e8 = str_replace('\\"', '"', $ped0a6251["desc"]); foreach ($ped0a6251 as $pe5c5e2fe => $v956913c90f) { if ($pe5c5e2fe != "name" && $pe5c5e2fe != "type" && $pe5c5e2fe != "not_null" && $pe5c5e2fe != "default" && $pe5c5e2fe != "desc" && $pe5c5e2fe != "sub_name") { $pe9715da5 .= ($pe9715da5 ? ", " : "") . "$pe5c5e2fe=$v956913c90f"; } } } $v220ac3f2f6 = WorkFlowDataAccessHandler::getMapPHPTypes(); $v0edffb2551 = WorkFlowDataAccessHandler::getMapDBTypes(); $v3fb9f41470 = ObjTypeHandler::convertSimpleTypeIntoCompositeType($v3fb9f41470); $pf8ed4912 = '
		<tr class="annotation">
			<td class="annotation_type">
				<select>
					<option value="param"' . ($v18f86e4308 == "param" ? ' selected' : '') . '>Param</option>
					<option value="return"' . ($v18f86e4308 == "return" ? ' selected' : '') . '>Return</option>
				</select>
			</td>
			<td class="name">
				<input type="text" value="' . str_replace('"', "&quot;", $v5e813b295b) . '" onBlur="onBlurAnnotationName(this)" />
			</td>
			<td class="type">
				<select' . (strpos($v3fb9f41470, "|") !== false ? ' style="display:none"' : '') . '>
					<option></option>
					<optgroup label="PHP Types" class="main_optgroup">
					' . WorkFlowQueryHandler::getMapSelectOptions($v220ac3f2f6, $v3fb9f41470, "org.phpframework.object.php.Primitive", false, true) . '
					</optgroup>
					<optgroup label="DB Types" class="main_optgroup">
					' . WorkFlowQueryHandler::getMapSelectOptions($v0edffb2551, $v3fb9f41470, "org.phpframework.object.db.DBPrimitive", false, false) . '
					</optgroup>
				</select>
				<input type="text" value="' . str_replace('"', "&quot;", $v3fb9f41470) . '"' . (strpos($v3fb9f41470, "|") !== false ? '' : ' style="display:none"') . ' />
				<span class="icon switch textfield" onClick="swapTypeTextField(this)" title="Swap text field type">Swap text field type</span>
				<span class="icon search" onClick="geAnnotationTypeFromFileManager(this)" title="Get type from File Manager">Search</span>
			</td>
			<td class="not_null">
				<input type="checkbox" value="1"' . ($pf6e8b316 ? ' checked' : '') . ' />
			</td>
			<td class="default">
				<input type="text" value="' . str_replace('"', "&quot;", $v4bfe0500a2) . '" />
			</td>
			<td class="description">
				<input type="text" value="' . str_replace('"', "&quot;", $pd2c744e8) . '" />
			</td>
			<td class="others">
				<input type="text" value="' . str_replace('"', "&quot;", $pe9715da5) . '" />
			</td>
			<td class="icon_cell table_header"><span class="icon delete" onClick="removeAnnotation(this)" title="Delete">Remove</span></td>
		</tr>'; return $pf8ed4912; } } ?>
