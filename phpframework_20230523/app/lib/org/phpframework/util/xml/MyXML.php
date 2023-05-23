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

include_once get_lib("org.phpframework.util.xml.UnicodeUTF8"); include_once get_lib("org.phpframework.util.MyArray"); class MyXML extends SimpleXMLElement { public static function isXMLContentValid($v2804c706f1, $pafc99f2d = '1.0', $v8d1f503e9f = 'utf-8') { if (trim($v2804c706f1) == '') return false; libxml_use_internal_errors(true); $v28ad2c02f1 = new DOMDocument($pafc99f2d, $v8d1f503e9f); $v28ad2c02f1->loadXML($v2804c706f1); $v8a29987473 = libxml_get_errors(); libxml_clear_errors(); return empty($v8a29987473); } public function getNodes($v0af67f418a, $paf1bc6f6 = false) { $v50d32a6fc4 = $this->xpath($v0af67f418a); if(!$v50d32a6fc4) $v50d32a6fc4 = array(); else if(count($v50d32a6fc4)) { $pfb35386f = is_array($paf1bc6f6) && count($paf1bc6f6) ? array_keys($paf1bc6f6) : false; if($pfb35386f) { $pf65e5adb = array(); $pc37695cb = count($v50d32a6fc4); $pd28479e5 = count($pfb35386f); for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v3c6cb0e784 = $v50d32a6fc4[$v43dd7d0051]; $v7959970a41 = true; for($pc37695cb = 0; $pc37695cb < $pd28479e5; $pc37695cb++) { if($v3c6cb0e784->getAttribute($pfb35386f[$pc37695cb]) != $paf1bc6f6[ $pfb35386f[$pc37695cb] ]) { $v7959970a41 = false; break; } } if($v7959970a41) $pf65e5adb[] = $v3c6cb0e784; } $v50d32a6fc4 = $pf65e5adb; } } return $v50d32a6fc4; } public function getChildrenCount($v1efaf06c58 = false) { $v7354cde23e = 0; $v597df9e3ae = $this->children($v1efaf06c58); foreach($v597df9e3ae as $v6694236c2c) $v7354cde23e++; return (int)$v7354cde23e; } public function getAttribute($v5e813b295b, $v1efaf06c58 = false) { $ped0a6251 = $v1efaf06c58 ? $this->attributes($v1efaf06c58, true) : $this->attributes(); foreach($ped0a6251 as $pbfa01ed1 => $v2b3a130180) { if($pbfa01ed1 == $v5e813b295b) return (string)$v2b3a130180; } return false; } public function getAttributesName($v1efaf06c58 = false) { $v0384275a41 = array(); $ped0a6251 = $v1efaf06c58 ? $this->attributes($v1efaf06c58, true) : $this->attributes(); foreach($ped0a6251 as $pbfa01ed1 => $v2b3a130180) $v0384275a41[] = (string)$pbfa01ed1; return (array)$v0384275a41; } public function getAttributesArray($v1efaf06c58 = false) { $v0384275a41 = array(); $ped0a6251 = $v1efaf06c58 ? $this->attributes($v1efaf06c58, true) : $this->attributes(); foreach($ped0a6251 as $pbfa01ed1 => $v2b3a130180) { $pbfa01ed1 = $pbfa01ed1; $v0384275a41[$pbfa01ed1] = (string)$v2b3a130180; } return (array)$v0384275a41; } public function getAttributesCount($v1efaf06c58 = false) { $v04a366a14b = $this->getAttributesName($v1efaf06c58); return count($v04a366a14b); } public function getDocDefinedNamespaces() { $v97f1a29d75 = array(); $v46c07379c9 = $this->getDocNamespaces(); foreach($v46c07379c9 as $pdcf670f6 => $v4a2fedb8f0) { $pdcf670f6 = $pdcf670f6 ? "xmlns:" . $pdcf670f6 : "xmlns"; $pdcf670f6 = !empty($v5d3813882f["upper_case_keys"]) ? strtoupper($pdcf670f6) : (!empty($v5d3813882f["lower_case_keys"]) ? strtolower($pdcf670f6) : $pdcf670f6); $v97f1a29d75[$pdcf670f6] = $v4a2fedb8f0; } return $v97f1a29d75; } public function toArray($v5d3813882f = false, $pb237e791 = false) { $v539082ff30 = array(); $v8eca11f492 = $v5d3813882f["simple"]; $v579e857701 = $v5d3813882f["from_decimal"]; $v539082ff30["name"] = ($pb237e791 ? $pb237e791 . ":" : "") . $this->getName(); $v67db1bd535 = (string)$this; if(strlen(trim($v67db1bd535))) $v539082ff30["value"] = $v67db1bd535; $pc4222023 = $this->children(); $v22d65586f0 = $this->childsToArray($pc4222023, $v5d3813882f, false); $v8d5686fe76 = $this->getNamespaces(true); foreach($v8d5686fe76 as $pdcf670f6 => $v4a2fedb8f0) { $pc4222023 = $this->children($pdcf670f6, true); if (!$pc4222023) $pc4222023 = $this->children($v4a2fedb8f0); $v6977d3ab90 = $this->childsToArray($pc4222023, $v5d3813882f, $pdcf670f6); $v22d65586f0 = array_merge($v22d65586f0, $v6977d3ab90); } if(!$v8eca11f492) { $v424a3fd23b = array(); $v46c07379c9 = $this->getDocNamespaces(true, false); foreach($v46c07379c9 as $pdcf670f6 => $v4a2fedb8f0) { $pdcf670f6 = $pdcf670f6 ? "xmlns:" . $pdcf670f6 : "xmlns"; $pdcf670f6 = !empty($v5d3813882f["upper_case_keys"]) ? strtoupper($pdcf670f6) : (!empty($v5d3813882f["lower_case_keys"]) ? strtolower($pdcf670f6) : $pdcf670f6); $v424a3fd23b[$pdcf670f6] = $v4a2fedb8f0; } $v8d5686fe76 = $this->getNamespaces(true); foreach($v8d5686fe76 as $pdcf670f6 => $v4a2fedb8f0) { $ped0a6251 = $this->getAttributesArray($pdcf670f6); if(!empty($ped0a6251)) { !empty($v5d3813882f["upper_case_keys"]) ? MyArray::arrKeysToUpperCase($ped0a6251, false) : (!empty($v5d3813882f["lower_case_keys"]) ? MyArray::arrKeysToLowerCase($ped0a6251, false) : null); if ($ped0a6251) foreach ($ped0a6251 as $pe5c5e2fe => $v956913c90f) { if ($v579e857701) $v956913c90f = html_entity_decode($v956913c90f); $pe5c5e2fe = ($pdcf670f6 ? $pdcf670f6 . ":" : "") . $pbfa01ed1; $v424a3fd23b[$pe5c5e2fe] = $v956913c90f; } } } $ped0a6251 = $this->getAttributesArray(); if(!empty($ped0a6251)) { !empty($v5d3813882f["upper_case_keys"]) ? MyArray::arrKeysToUpperCase($ped0a6251, false) : (!empty($v5d3813882f["lower_case_keys"]) ? MyArray::arrKeysToLowerCase($ped0a6251, false) : null); if ($ped0a6251) foreach ($ped0a6251 as $pe5c5e2fe => $v956913c90f) { if ($v579e857701) $v956913c90f = html_entity_decode($v956913c90f); $v424a3fd23b[$pe5c5e2fe] = $v956913c90f; } } if ($v424a3fd23b) $v539082ff30["@"] = $v424a3fd23b; } if (!empty($v22d65586f0)) { $v539082ff30["childs"] = $v22d65586f0; } $pfb662071 = array(); $pbfa01ed1 = !empty($v5d3813882f["upper_case_keys"]) ? strtoupper($v539082ff30["name"]) : (!empty($v5d3813882f["lower_case_keys"]) ? strtolower($v539082ff30["name"]) : $v539082ff30["name"]); $pfb662071[$pbfa01ed1][] = $v539082ff30; return $pfb662071; } public function childsToArray($pc4222023, $v5d3813882f = false, $pdcf670f6 = false) { $v22d65586f0 = array(); $v2d235c6a6a = $v5d3813882f["xml_order_id_prefix"]; $v444066df26 = 0; foreach($pc4222023 as $v7c627adb6d) { ++$v444066df26; $v94b965800b = ($v2d235c6a6a ? $v2d235c6a6a . "." : "") . $v444066df26; $pf2aea42d = $v5d3813882f; $pf2aea42d["xml_order_id_prefix"] = $v94b965800b; $v9bb0d43d93 = $v7c627adb6d->toArray($pf2aea42d, $pdcf670f6); $pc1ac89a7 = ($pdcf670f6 ? $pdcf670f6 . ":" : "") . $v7c627adb6d->getName(); $pad000ab9 = !empty($v5d3813882f["upper_case_keys"]) ? strtoupper($pc1ac89a7) : (!empty($v5d3813882f["lower_case_keys"]) ? strtolower($pc1ac89a7) : $pc1ac89a7); $v9bb0d43d93[$pad000ab9][0]["xml_order_id"] = $v94b965800b; $v22d65586f0[$pad000ab9][] = $v9bb0d43d93[$pad000ab9][0]; } return $v22d65586f0; } public static function complexArrayToBasicArray($pfb662071, $v5d3813882f = false) { $v826ff96094 = array(); if (is_array($pfb662071) && !empty($pfb662071)) { $peccb2fdc = !empty($v5d3813882f["trim"]); foreach ($pfb662071 as $v50d32a6fc4) { $v16ac35fd79 = $v50d32a6fc4 ? count($v50d32a6fc4) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $v16ac35fd79; $v43dd7d0051++) { $v6694236c2c = $v50d32a6fc4[$v43dd7d0051]; $v03c7cb16f7 = array_keys($v6694236c2c); $v8f66244056 = array_flip(array_map("strtolower", $v03c7cb16f7)); MyArray::arrKeysToLowerCase($v6694236c2c, false); if (isset($v6694236c2c["value"])) { $v948cbcb5c1 = $peccb2fdc ? trim($v6694236c2c["value"]) : $v6694236c2c["value"]; if (!empty($v6694236c2c["childs"])) { $v908c6d1877 = $v03c7cb16f7[ $v8f66244056["value"] ]; $v02a69d4e0f = array( $v908c6d1877 => $v948cbcb5c1, ); $pc4222023 = self::complexArrayToBasicArray($v6694236c2c["childs"], $v5d3813882f); $v948cbcb5c1 = array_merge($pc4222023, $v02a69d4e0f); } } else if (isset($v6694236c2c["childs"])) { $v948cbcb5c1 = self::complexArrayToBasicArray($v6694236c2c["childs"], $v5d3813882f); } else { $v948cbcb5c1 = null; } if (isset($v6694236c2c["@"]) && !$v5d3813882f["convert_without_attributes"]) { !empty($v5d3813882f["upper_case_keys"]) ? MyArray::arrKeysToUpperCase($v6694236c2c["@"], false) : (!empty($v5d3813882f["upper_case_keys"]) ? MyArray::arrKeysToLowerCase($v6694236c2c["@"], false) : null); $ped0a6251 = $v6694236c2c["@"]; if (is_array($v948cbcb5c1)) { $v948cbcb5c1 = array_merge($ped0a6251, $v948cbcb5c1); } else if (isset($v948cbcb5c1)) { $v908c6d1877 = isset($v8f66244056["value"]) ? $v03c7cb16f7[ $v8f66244056["value"] ] : "value"; $v948cbcb5c1 = array( $v908c6d1877 => $v948cbcb5c1, "@" => $ped0a6251, ); } else { $v948cbcb5c1 = $ped0a6251; } } !empty($v5d3813882f["upper_case_keys"]) ? MyArray::arrKeysToUpperCase($v948cbcb5c1, false) : (!empty($v5d3813882f["upper_case_keys"]) ? MyArray::arrKeysToLowerCase($v948cbcb5c1, false) : null); $pbfa01ed1 = !empty($v5d3813882f["upper_case_keys"]) ? strtoupper($v6694236c2c["name"]) : (!empty($v5d3813882f["lower_case_keys"]) ? strtolower($v6694236c2c["name"]) : $v6694236c2c["name"]); if ($v16ac35fd79 > 1) { $v826ff96094[ $pbfa01ed1 ][] = $v948cbcb5c1; } else { $v826ff96094[ $pbfa01ed1 ] = $v948cbcb5c1; } } } } if (is_array($v5d3813882f)) foreach ($v5d3813882f as $v46d0a3fd41 => $v9ab90e5b58) if (!empty($v9ab90e5b58)) { if ($v46d0a3fd41 == "convert_childs_to_attributes") $v826ff96094 = self::convertChildsToAttributesInBasicArray($v826ff96094, $v5d3813882f); else if ($v46d0a3fd41 == "convert_attributes_to_childs") $v826ff96094 = self::convertAttributesToChildsInBasicArray($v826ff96094, $v5d3813882f); else if ($v46d0a3fd41 == "discard_nodes") $v826ff96094 = self::discardNodesInBasicArray($v826ff96094, $v5d3813882f); } return $v826ff96094; } public static function complexChildsArrayToBasicArray($pfb662071, $v5d3813882f = false) { $v826ff96094 = array(); if (is_array($pfb662071) && !empty($pfb662071)) { $pfb662071 = array("aux" => $pfb662071); $v826ff96094 = self::complexArrayToBasicArray($pfb662071, $v5d3813882f); } return $v826ff96094; } public static function basicArrayToComplexArray($pfb662071, $v5d3813882f = false) { $v826ff96094 = array(); if (is_array($pfb662071) && !empty($pfb662071)) { $peccb2fdc = !empty($v5d3813882f["trim"]); $v2d235c6a6a = $v5d3813882f["xml_order_id_prefix"] ? $v5d3813882f["xml_order_id_prefix"] . "." : ""; $v444066df26 = 1; $v6a5e2be6e7 = true; foreach ($pfb662071 as $pbfa01ed1 => $v02a69d4e0f) if (!is_numeric($pbfa01ed1)) { $v6a5e2be6e7 = false; break; } if ($v6a5e2be6e7) $pfb662071 = array("default" => $pfb662071); foreach ($pfb662071 as $pbfa01ed1 => $v50d32a6fc4) { if ($pbfa01ed1 != "@") { $pdb6e6f78 = $v50d32a6fc4; MyArray::arrKeysToLowerCase($v50d32a6fc4, false); if (is_array($pdb6e6f78)) unset($pdb6e6f78["@"]); $pe5c5e2fe = !empty($v5d3813882f["upper_case_keys"]) ? strtoupper($pbfa01ed1) : (!empty($v5d3813882f["lower_case_keys"]) ? strtolower($pbfa01ed1) : $pbfa01ed1); $pa6a2427d = !is_array($v50d32a6fc4) || count($v50d32a6fc4) == 0 || isset($v50d32a6fc4["value"]) || (is_array($v50d32a6fc4) && array_key_exists("@", $v50d32a6fc4) && count($v50d32a6fc4) == 1); if ($pa6a2427d) { if (isset($v50d32a6fc4["value"])) { $v67db1bd535 = $v50d32a6fc4["value"]; unset($v50d32a6fc4["value"]); } else $v67db1bd535 = $pdb6e6f78; $pe96e65ba = array( "name" => $pbfa01ed1, "xml_order_id" => $v2d235c6a6a . $v444066df26, ); if (!is_array($v67db1bd535)) $pe96e65ba["value"] = $peccb2fdc && $v67db1bd535 ? trim($v67db1bd535) : $v67db1bd535; else if (count($v67db1bd535) > 0) $pe96e65ba["value"] = $v67db1bd535; if (is_array($v50d32a6fc4) && array_key_exists("@", $v50d32a6fc4)) { if (is_array($v50d32a6fc4["@"]) && !$v5d3813882f["convert_without_attributes"]) { !empty($v5d3813882f["upper_case_keys"]) ? MyArray::arrKeysToUpperCase($v50d32a6fc4["@"], false) : (!empty($v5d3813882f["upper_case_keys"]) ? MyArray::arrKeysToLowerCase($v50d32a6fc4["@"], false) : null); $pe96e65ba["@"] = $v50d32a6fc4["@"]; } unset($v50d32a6fc4["@"]); } if (!empty($v50d32a6fc4)) { $pdf222112 = $v5d3813882f ? $v5d3813882f : array(); $pdf222112["xml_order_id_prefix"] = $v2d235c6a6a . $v444066df26; $v29680e12e6 = self::basicArrayToComplexArray($pdb6e6f78, $pdf222112); if ($v29680e12e6) $pe96e65ba["childs"] = $v29680e12e6; } !empty($v5d3813882f["upper_case_keys"]) ? MyArray::arrKeysToUpperCase($pe96e65ba, false) : (!empty($v5d3813882f["upper_case_keys"]) ? MyArray::arrKeysToLowerCase($pe96e65ba, false) : null); $v826ff96094[$pe5c5e2fe][] = $pe96e65ba; $v444066df26++; } else { $v8dff64d04b = true; foreach ($v50d32a6fc4 as $v02dfc972e3 => $v02a69d4e0f) { if (!is_numeric($v02dfc972e3)) { $v8dff64d04b = false; break; } } if ($v8dff64d04b) { $v16ac35fd79 = $v50d32a6fc4 ? count($v50d32a6fc4) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $v16ac35fd79; $v43dd7d0051++) { $v342a134247 = $v50d32a6fc4[$v43dd7d0051]; $v2c2ab5e4d9 = $v342a134247; if (is_array($v2c2ab5e4d9)) unset($v2c2ab5e4d9["@"]); MyArray::arrKeysToLowerCase($v342a134247, false); $pe96e65ba = array( "name" => $pbfa01ed1, "xml_order_id" => $v2d235c6a6a . $v444066df26, ); if (is_array($v342a134247) && array_key_exists("@", $v342a134247)) { if (is_array($v342a134247["@"]) && !$v5d3813882f["convert_without_attributes"]) { !empty($v5d3813882f["upper_case_keys"]) ? MyArray::arrKeysToUpperCase($v342a134247["@"], false) : (!empty($v5d3813882f["upper_case_keys"]) ? MyArray::arrKeysToLowerCase($v342a134247["@"], false) : null); $pe96e65ba["@"] = $v342a134247["@"]; } unset($v342a134247["@"]); } $pa6a2427d = !is_array($v342a134247) || count($v342a134247) == 0 || isset($v342a134247["value"]); if ($pa6a2427d) { if (isset($v342a134247["value"])) { $v67db1bd535 = $v342a134247["value"]; unset($v342a134247["value"]); } else $v67db1bd535 = $v2c2ab5e4d9; if (!is_array($v67db1bd535)) $pe96e65ba["value"] = $peccb2fdc && $v67db1bd535 ? trim($v67db1bd535) : $v67db1bd535; else if (count($v67db1bd535) > 0) $pe96e65ba["value"] = $v67db1bd535; if (!empty($v342a134247)) { $pdf222112 = $v5d3813882f ? $v5d3813882f : array(); $pdf222112["xml_order_id_prefix"] = $v2d235c6a6a . $v444066df26; $v29680e12e6 = self::basicArrayToComplexArray($v2c2ab5e4d9, $pdf222112); if ($v29680e12e6) $pe96e65ba["childs"] = $v29680e12e6; } } else { $pdf222112 = $v5d3813882f ? $v5d3813882f : array(); $pdf222112["xml_order_id_prefix"] = $v2d235c6a6a . $v444066df26; $v29680e12e6 = self::basicArrayToComplexArray($v2c2ab5e4d9, $pdf222112); if ($v29680e12e6) $pe96e65ba["childs"] = $v29680e12e6; } !empty($v5d3813882f["upper_case_keys"]) ? MyArray::arrKeysToUpperCase($pe96e65ba, false) : (!empty($v5d3813882f["upper_case_keys"]) ? MyArray::arrKeysToLowerCase($pe96e65ba, false) : null); $v826ff96094[$pe5c5e2fe][] = $pe96e65ba; $v444066df26++; } } else { $pdf222112 = $v5d3813882f ? $v5d3813882f : array(); $pdf222112["xml_order_id_prefix"] = $v2d235c6a6a . $v444066df26; $v29680e12e6 = self::basicArrayToComplexArray($pdb6e6f78, $pdf222112); $pe96e65ba = array( "name" => $pbfa01ed1, "xml_order_id" => $v2d235c6a6a . $v444066df26, ); if (array_key_exists("@", $v50d32a6fc4) && is_array($v50d32a6fc4["@"]) && !$v5d3813882f["convert_without_attributes"]) { !empty($v5d3813882f["upper_case_keys"]) ? MyArray::arrKeysToUpperCase($v50d32a6fc4["@"], false) : (!empty($v5d3813882f["upper_case_keys"]) ? MyArray::arrKeysToLowerCase($v50d32a6fc4["@"], false) : null); $pe96e65ba["@"] = $v50d32a6fc4["@"]; } if ($v29680e12e6) $pe96e65ba["childs"] = $v29680e12e6; !empty($v5d3813882f["upper_case_keys"]) ? MyArray::arrKeysToUpperCase($pe96e65ba, false) : (!empty($v5d3813882f["upper_case_keys"]) ? MyArray::arrKeysToLowerCase($pe96e65ba, false) : null); $v826ff96094[$pe5c5e2fe][] = $pe96e65ba; $v444066df26++; } } } } } if (is_array($v5d3813882f)) foreach ($v5d3813882f as $v46d0a3fd41 => $v9ab90e5b58) if (!empty($v9ab90e5b58)) { if ($v46d0a3fd41 == "convert_childs_to_attributes") $v826ff96094 = self::convertChildsToAttributesInComplexArray($v826ff96094, $v5d3813882f); else if ($v46d0a3fd41 == "convert_attributes_to_childs") $v826ff96094 = self::convertAttributesToChildsInComplexArray($v826ff96094, $v5d3813882f); else if ($v46d0a3fd41 == "discard_nodes") $v826ff96094 = self::discardNodesInComplexArray($v826ff96094, $v5d3813882f); } return $v826ff96094; } public static function discardNodesInComplexArray($pfb662071, $v5d3813882f = false) { if (is_array($pfb662071) && !empty($pfb662071) && $v5d3813882f && is_array($v5d3813882f["discard_nodes"])) { foreach ($pfb662071 as $pbfa01ed1 => $v50d32a6fc4) { $v8dff64d04b = true; foreach ($v50d32a6fc4 as $pe5c5e2fe => $v02a69d4e0f) { if (!is_numeric($pe5c5e2fe)) { $v8dff64d04b = false; break; } } if (!$v8dff64d04b) { $v50d32a6fc4 = array($v50d32a6fc4); } $pc37695cb = $v50d32a6fc4 ? count($v50d32a6fc4) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { if ($v50d32a6fc4[$v43dd7d0051]["childs"]) foreach ($v5d3813882f["discard_nodes"] as $pd6eddf7a) if (array_key_exists($pd6eddf7a, $v50d32a6fc4[$v43dd7d0051]["childs"])) unset($v50d32a6fc4[$v43dd7d0051]["childs"][$pd6eddf7a]); } $pfb662071[$pbfa01ed1] = !$v8dff64d04b ? $v50d32a6fc4[0] : $v50d32a6fc4; } } return $pfb662071; } public static function discardNodesInBasicArray($pfb662071, $v5d3813882f = false) { if (is_array($pfb662071) && !empty($pfb662071) && $v5d3813882f && is_array($v5d3813882f["discard_nodes"])) { foreach ($pfb662071 as $pbfa01ed1 => $v50d32a6fc4) { if (is_array($v50d32a6fc4)) { $v8dff64d04b = true; foreach ($v50d32a6fc4 as $pe5c5e2fe => $v02a69d4e0f) { if (!is_numeric($pe5c5e2fe)) { $v8dff64d04b = false; break; } } if (!$v8dff64d04b) $v50d32a6fc4 = array($v50d32a6fc4); $v54817707c7 = false; $pc37695cb = $v50d32a6fc4 ? count($v50d32a6fc4) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) if (is_array($v50d32a6fc4[$v43dd7d0051])) { foreach ($v5d3813882f["discard_nodes"] as $pd6eddf7a) if (array_key_exists($pd6eddf7a, $v50d32a6fc4[$v43dd7d0051])) { unset($v50d32a6fc4[$v43dd7d0051][$pd6eddf7a]); $v54817707c7 = true; } } if ($v54817707c7) $pfb662071[$pbfa01ed1] = !$v8dff64d04b ? $v50d32a6fc4[0] : $v50d32a6fc4; } } } return $pfb662071; } public static function convertComplexArrayWithoutAttributes($pfb662071, $v5d3813882f = false) { if (is_array($pfb662071) && !empty($pfb662071)) { foreach ($pfb662071 as $pbfa01ed1 => $v50d32a6fc4) { $v8dff64d04b = true; foreach ($v50d32a6fc4 as $pe5c5e2fe => $v02a69d4e0f) { if (!is_numeric($pe5c5e2fe)) { $v8dff64d04b = false; break; } } if (!$v8dff64d04b) { $v50d32a6fc4 = array($v50d32a6fc4); } $pc37695cb = $v50d32a6fc4 ? count($v50d32a6fc4) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { unset($v50d32a6fc4[$v43dd7d0051]["@"]); } $pfb662071[$pbfa01ed1] = !$v8dff64d04b ? $v50d32a6fc4[0] : $v50d32a6fc4; } } return $pfb662071; } public static function convertBasicArrayWithoutAttributes($pfb662071, $v5d3813882f = false) { if (is_array($pfb662071) && !empty($pfb662071)) { foreach ($pfb662071 as $pbfa01ed1 => $v50d32a6fc4) { if (is_array($v50d32a6fc4)) { $v8dff64d04b = true; foreach ($v50d32a6fc4 as $pe5c5e2fe => $v02a69d4e0f) { if (!is_numeric($pe5c5e2fe)) { $v8dff64d04b = false; break; } } if (!$v8dff64d04b) $v50d32a6fc4 = array($v50d32a6fc4); $v54817707c7 = false; $pc37695cb = $v50d32a6fc4 ? count($v50d32a6fc4) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) if (is_array($v50d32a6fc4[$v43dd7d0051])) { unset($v50d32a6fc4[$v43dd7d0051]["@"]); $v9994512d98 = array_keys($v50d32a6fc4[$v43dd7d0051]); if (count($v9994512d98) == 1 && array_key_exists("value", $v50d32a6fc4[$v43dd7d0051])) $v50d32a6fc4[$v43dd7d0051] = $v50d32a6fc4[$v43dd7d0051]["value"]; else if (count($v9994512d98) == 0) $v50d32a6fc4[$v43dd7d0051] = null; $v54817707c7 = true; } if ($v54817707c7) $pfb662071[$pbfa01ed1] = !$v8dff64d04b ? $v50d32a6fc4[0] : $v50d32a6fc4; } } } return $pfb662071; } public static function convertAttributesToChildsInComplexArray($pfb662071, $v5d3813882f = false) { if (is_array($pfb662071) && !empty($pfb662071)) { $pa0533c5e = !empty($v5d3813882f["upper_case_keys"]); $pb64aac1a = !empty($v5d3813882f["lower_case_keys"]); $peccb2fdc = !empty($v5d3813882f["trim"]); foreach ($pfb662071 as $pbfa01ed1 => $v50d32a6fc4) { $v8dff64d04b = true; foreach ($v50d32a6fc4 as $pe5c5e2fe => $v02a69d4e0f) { if (!is_numeric($pe5c5e2fe)) { $v8dff64d04b = false; break; } } if (!$v8dff64d04b) { $v50d32a6fc4 = array($v50d32a6fc4); } $pc37695cb = $v50d32a6fc4 ? count($v50d32a6fc4) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { if (isset($v50d32a6fc4[$v43dd7d0051]["@"]) && is_array($v50d32a6fc4[$v43dd7d0051]["@"])) { foreach ($v50d32a6fc4[$v43dd7d0051]["@"] as $v1d371a59ff => $pd6321fb0) { $v44a110e4b2 = $pa0533c5e ? strtoupper($v1d371a59ff) : ($pb64aac1a ? strtolower($v1d371a59ff) : $v1d371a59ff); $pd6321fb0 = $peccb2fdc && $pd6321fb0 ? trim($pd6321fb0) : $pd6321fb0; $v78166b0118 = isset($v50d32a6fc4[$v43dd7d0051]["NAME"]) ? "NAME" : "name"; $v908c6d1877 = isset($v50d32a6fc4[$v43dd7d0051]["NAME"]) ? "VALUE" : "value"; $pe96e65ba = array( $v78166b0118 => $v1d371a59ff, $v908c6d1877 => $pd6321fb0 ); $v03c7cb16f7 = array_keys($v50d32a6fc4[$v43dd7d0051]); $v8f66244056 = array_flip(array_map("strtolower", $v03c7cb16f7)); $v3a3f09b69d = isset($v8f66244056["childs"]) ? $v03c7cb16f7[ $v8f66244056["childs"] ] : (isset($v50d32a6fc4[$v43dd7d0051]["NAME"]) ? "CHILDS" : "childs"); if (!isset($v50d32a6fc4[$v43dd7d0051][$v3a3f09b69d][$v44a110e4b2])) { $v50d32a6fc4[$v43dd7d0051][$v3a3f09b69d][$v44a110e4b2] = $pe96e65ba; } else { if (isset($v50d32a6fc4[$v43dd7d0051][$v3a3f09b69d][$v44a110e4b2][$v78166b0118])) { $v50d32a6fc4[$v43dd7d0051][$v3a3f09b69d][$v44a110e4b2] = array($v50d32a6fc4[$v43dd7d0051][$v3a3f09b69d][$v44a110e4b2]); } $v50d32a6fc4[$v43dd7d0051][$v3a3f09b69d][$v44a110e4b2][] = $pe96e65ba; } } unset($v50d32a6fc4[$v43dd7d0051]["@"]); } } $pfb662071[$pbfa01ed1] = !$v8dff64d04b ? $v50d32a6fc4[0] : $v50d32a6fc4; } } return $pfb662071; } public static function convertAttributesToChildsInBasicArray($pfb662071, $v5d3813882f = false) { if (is_array($pfb662071) && !empty($pfb662071)) { $pa0533c5e = !empty($v5d3813882f["upper_case_keys"]); $pb64aac1a = !empty($v5d3813882f["lower_case_keys"]); $peccb2fdc = !empty($v5d3813882f["trim"]); foreach ($pfb662071 as $pbfa01ed1 => $v50d32a6fc4) { if (is_array($v50d32a6fc4)) { $v8dff64d04b = true; if (!is_array($v50d32a6fc4)){echo "$pbfa01ed1<pre>";print_r($pfb662071);} foreach ($v50d32a6fc4 as $pe5c5e2fe => $v02a69d4e0f) { if (!is_numeric($pe5c5e2fe)) { $v8dff64d04b = false; break; } } if (!$v8dff64d04b) $v50d32a6fc4 = array($v50d32a6fc4); $pc37695cb = $v50d32a6fc4 ? count($v50d32a6fc4) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) if (isset($v50d32a6fc4[$v43dd7d0051]["@"]) && is_array($v50d32a6fc4[$v43dd7d0051]["@"])) { foreach ($v50d32a6fc4[$v43dd7d0051]["@"] as $v1d371a59ff => $pd6321fb0) { $v44a110e4b2 = $pa0533c5e ? strtoupper($v1d371a59ff) : ($pb64aac1a ? strtolower($v1d371a59ff) : $v1d371a59ff); $pd6321fb0 = $peccb2fdc && $pd6321fb0 ? trim($pd6321fb0) : $pd6321fb0; if (!isset($v50d32a6fc4[$v43dd7d0051][$v44a110e4b2])) $v50d32a6fc4[$v43dd7d0051][$v44a110e4b2] = $pd6321fb0; else { if (!is_array($v50d32a6fc4[$v43dd7d0051][$v44a110e4b2])) $v50d32a6fc4[$v43dd7d0051][$v44a110e4b2] = array($v50d32a6fc4[$v43dd7d0051][$v44a110e4b2]); $v50d32a6fc4[$v43dd7d0051][$v44a110e4b2][] = $pd6321fb0; } } unset($v50d32a6fc4[$v43dd7d0051]["@"]); } $pfb662071[$pbfa01ed1] = !$v8dff64d04b ? $v50d32a6fc4[0] : $v50d32a6fc4; } } } return $pfb662071; } public static function convertChildsToAttributesInComplexArray($pfb662071, $v5d3813882f = false) { if (is_array($pfb662071) && !empty($pfb662071)) { $pa0533c5e = !empty($v5d3813882f["upper_case_keys"]); $pb64aac1a = !empty($v5d3813882f["lower_case_keys"]); $peccb2fdc = !empty($v5d3813882f["trim"]); $v02a69d4e0f = $pfb662071; foreach ($v02a69d4e0f as $pbfa01ed1 => $v50d32a6fc4) { if (is_array($v50d32a6fc4)) { $v8dff64d04b = true; foreach ($v50d32a6fc4 as $pe5c5e2fe => $v02a69d4e0f) { if (!is_numeric($pe5c5e2fe)) { $v8dff64d04b = false; break; } } if (!$v8dff64d04b) $v50d32a6fc4 = array($v50d32a6fc4); $pc37695cb = $v50d32a6fc4 ? count($v50d32a6fc4) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v03c7cb16f7 = array_keys($v50d32a6fc4[$v43dd7d0051]); $v8f66244056 = array_flip(array_map("strtolower", $v03c7cb16f7)); $v3a3f09b69d = $v03c7cb16f7[ $v8f66244056["childs"] ]; if (isset($v50d32a6fc4[$v43dd7d0051][$v3a3f09b69d]) && is_array($v50d32a6fc4[$v43dd7d0051][$v3a3f09b69d])) { foreach ($v50d32a6fc4[$v43dd7d0051][$v3a3f09b69d] as $pad000ab9 => $pc5e06a4e) { $v1e6d5fea56 = array_keys($pc5e06a4e[0]); $pc52dd837 = array_flip(array_map("strtolower", $v1e6d5fea56)); $v8b1690682c = isset($pc52dd837["childs"]) ? $v1e6d5fea56[ $pc52dd837["childs"] ] : "childs"; if ($pc5e06a4e && count($pc5e06a4e) == 1 && empty($pc5e06a4e[0][$v8b1690682c])) { $v9f4ae69006 = isset($pc52dd837["name"]) ? $v1e6d5fea56[ $pc52dd837["name"] ] : "name"; $v700bc92e97 = isset($pc52dd837["value"]) ? $v1e6d5fea56[ $pc52dd837["value"] ] : "value"; $v1d371a59ff = $pc5e06a4e[0][$v9f4ae69006]; $pd6321fb0 = $pc5e06a4e[0][$v700bc92e97]; $v1d371a59ff = $pa0533c5e ? strtoupper($v1d371a59ff) : ($pb64aac1a ? strtolower($v1d371a59ff) : $v1d371a59ff); $pd6321fb0 = $peccb2fdc && $pd6321fb0 && !is_array($pd6321fb0) ? trim($pd6321fb0) : $pd6321fb0; $v50d32a6fc4[$v43dd7d0051]["@"][$v1d371a59ff] = $pd6321fb0; unset($v50d32a6fc4[$v43dd7d0051][$v3a3f09b69d][$pad000ab9]); } } if (empty($v50d32a6fc4[$v43dd7d0051][$v3a3f09b69d])) { unset($v50d32a6fc4[$v43dd7d0051][$v3a3f09b69d]); } } } $pfb662071[$pbfa01ed1] = !$v8dff64d04b ? $v50d32a6fc4[0] : $v50d32a6fc4; } else { $pe5c5e2fe = $pa0533c5e ? strtoupper($pbfa01ed1) : ($pb64aac1a ? strtolower($pbfa01ed1) : $pbfa01ed1); $v50d32a6fc4 = $peccb2fdc && $v50d32a6fc4 ? trim($v50d32a6fc4) : $v50d32a6fc4; $v50d32a6fc4["@"][$pe5c5e2fe] = $v50d32a6fc4; unset($pfb662071[$pbfa01ed1]); } } } return $pfb662071; } public static function convertChildsToAttributesInBasicArray($pfb662071, $v5d3813882f = false) { if (is_array($pfb662071) && !empty($pfb662071)) { $pa0533c5e = !empty($v5d3813882f["upper_case_keys"]); $pb64aac1a = !empty($v5d3813882f["lower_case_keys"]); $peccb2fdc = !empty($v5d3813882f["trim"]); $v02a69d4e0f = $pfb662071; foreach ($v02a69d4e0f as $pbfa01ed1 => $v50d32a6fc4) { if (is_array($v50d32a6fc4)) { $v8dff64d04b = true; foreach ($v50d32a6fc4 as $pe5c5e2fe => $v02a69d4e0f) { if (!is_numeric($pe5c5e2fe)) { $v8dff64d04b = false; break; } } if (!$v8dff64d04b) { $v50d32a6fc4 = array($v50d32a6fc4); } $pc37695cb = $v50d32a6fc4 ? count($v50d32a6fc4) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v6694236c2c = $v50d32a6fc4[$v43dd7d0051]; foreach ($v6694236c2c as $pad000ab9 => $pc5e06a4e) { if (strtolower($pad000ab9) != "value" && $pad000ab9 != "@") { if (!is_array($pc5e06a4e)) { $v4716626df2 = $pa0533c5e ? strtoupper($pad000ab9) : ($pb64aac1a ? strtolower($pad000ab9) : $pad000ab9); $pc5e06a4e = $peccb2fdc && $pc5e06a4e ? trim($pc5e06a4e) : $pc5e06a4e; $v50d32a6fc4[$v43dd7d0051]["@"][$v4716626df2] = $pc5e06a4e; unset($v50d32a6fc4[$v43dd7d0051][$pad000ab9]); } else { $v50d32a6fc4[$v43dd7d0051][$pad000ab9] = self::convertChildsToAttributesInBasicArray($pc5e06a4e, $v5d3813882f); } } } } $pfb662071[$pbfa01ed1] = !$v8dff64d04b ? $v50d32a6fc4[0] : $v50d32a6fc4; } else if (strtolower($pbfa01ed1) != "value" && $pbfa01ed1 != "@") { $pe5c5e2fe = $pa0533c5e ? strtoupper($pbfa01ed1) : ($pb64aac1a ? strtolower($pbfa01ed1) : $pbfa01ed1); $v50d32a6fc4 = $peccb2fdc && $v50d32a6fc4 ? trim($v50d32a6fc4) : $v50d32a6fc4; $pfb662071["@"][$pe5c5e2fe] = $v50d32a6fc4; unset($pfb662071[$pbfa01ed1]); } } } return $pfb662071; } public function toXML() { return $this->asXML(); } } ?>
