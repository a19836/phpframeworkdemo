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

include_once get_lib("org.phpframework.layer.presentation.cms.CMSLayer"); class CMSHtmlParserLayer { const CACHE_DIR_NAME = "html_parser/"; private $v874d5d2d79; private $v1786433afd; private $peb0fcb29; private $v93f37cfb9b; private $v254eb0a585; private $v4a432b49de; private $v557ad68afd; private $v7715b7ec3a; private $pe4ea0904; private $v0b46d550f1; private $v32afcbd028; private $v83ae768e01; private $v33a175910b; private $v86b24ad560; private $v6e120baec3; private $v6a0a7afc71; private $v399fbe3e4a; private $pf1ffcca4; private $v3f09e6b1e3; private $v27b0b8b00b; private $v977419efe1; private $pf1214e8d; private $pcd29cf87; private $v963c17784f; private $pfefc55de; public function __construct(CMSLayer $v874d5d2d79) { $this->v874d5d2d79 = $v874d5d2d79; $this->v1786433afd = null; $this->peb0fcb29 = null; $this->v93f37cfb9b = null; $this->v254eb0a585 = null; $this->v4a432b49de = null; $this->v557ad68afd = null; $this->v7715b7ec3a = null; $this->pe4ea0904 = null; $this->v0b46d550f1 = null; $this->v32afcbd028 = false; $this->v83ae768e01 = false; $this->v33a175910b = array(); $this->v86b24ad560 = null; $this->v6e120baec3 = null; $this->v6a0a7afc71 = false; $this->v399fbe3e4a = false; $this->pf1ffcca4 = false; $this->v3f09e6b1e3 = false; $this->v27b0b8b00b = null; $this->v977419efe1 = null; $this->pf1214e8d = null; $this->pcd29cf87 = null; $this->v963c17784f = null; } public function init($pfefc55de, $peb014cfd, $v37d269c4fa) { $this->pfefc55de = $pfefc55de; $this->v1786433afd = $pfefc55de && $peb014cfd ? $peb014cfd . "resource/" . $pfefc55de : null; $this->peb0fcb29 = $v37d269c4fa ? $v37d269c4fa . "js/MyJSLib.js" : null; $this->v93f37cfb9b = $v37d269c4fa ? $v37d269c4fa . "js/MyWidgetResourceLib.js" : null; $this->v254eb0a585 = $v37d269c4fa ? $v37d269c4fa . "css/MyWidgetResourceLib.css" : null; $v0e2ae424ae = $this->v874d5d2d79->getCMSPagePropertyLayer()->getParseFullHtml(); $pebb2c687 = $this->v874d5d2d79->getCMSPagePropertyLayer()->getParseRegionsHtml(); if ($v0e2ae424ae && $pebb2c687) $this->v874d5d2d79->getCMSPagePropertyLayer()->setParseRegionsHtml(false); } public function parseRenderedRegionHtml(&$pf8ed4912) { $v5fb417c845 = $this->v874d5d2d79->getCMSPagePropertyLayer()->getParseRegionsHtml(); if (!$v5fb417c845) return; $v7eae0304dc = $this->v874d5d2d79->getCMSPagePropertyLayer()->getExecuteSLA(); if ($v7eae0304dc && !$this->v874d5d2d79->getCMSSequentialLogicalActivityLayer()->isSLAExecuted()) $this->v874d5d2d79->getCMSSequentialLogicalActivityLayer()->executeSequentialLogicalActivities(); $pf530f4c2 = $this->v874d5d2d79->getCMSPagePropertyLayer()->getMaximumUsageMemory(); if ($pf530f4c2 > 0 && ($pe3014864 = $this->f9e95c29942($pf8ed4912)) > $pf530f4c2) { $this->f970c3e3281("Page region cannot be parsed because the defined memory size in the page advanced settings was overloaded. If you really wants this page region to be parsed, you need to increase the maximum_usage_memory for this page. The current Rendered-Region-Html-Usage-Memory is: " . $pe3014864 . " bytes."); $this->v874d5d2d79->getCMSPagePropertyLayer()->setParseRegionsHtml(false); return; } $v4a432b49de = $this->v874d5d2d79->getCMSPagePropertyLayer()->getParseHashTags(); $v557ad68afd = $this->v874d5d2d79->getCMSPagePropertyLayer()->getParsePTL(); $v7715b7ec3a = $this->v874d5d2d79->getCMSPagePropertyLayer()->getAddMyJSLib(); $pe4ea0904 = $this->v874d5d2d79->getCMSPagePropertyLayer()->getAddWidgetResourceLib(); $v0b46d550f1 = $this->v874d5d2d79->getCMSPagePropertyLayer()->getFilterByPermission(); if ($this->v6e120baec3 === null) $this->v6e120baec3 = $this->v874d5d2d79->getCMSSequentialLogicalActivityLayer()->getSLASettings(); if ($v4a432b49de === null && !empty($this->v6e120baec3) && $this->f8a41c84587($pf8ed4912)) $v4a432b49de = true; if ($v557ad68afd === null && $this->f1a3f41697f($pf8ed4912)) $v557ad68afd = true; if (($v7eae0304dc || $v7eae0304dc === null) && !$this->v874d5d2d79->getCMSSequentialLogicalActivityLayer()->isSLAExecuted() && ($v4a432b49de || $v557ad68afd)) { $this->v874d5d2d79->getCMSSequentialLogicalActivityLayer()->executeSequentialLogicalActivities(); if ($pf530f4c2 > 0 && ($pe3014864 = $this->f9e95c29942($pf8ed4912)) > $pf530f4c2) { $this->f970c3e3281("Page region cannot be parsed because the defined memory size in the page advanced settings was overloaded. If you really wants this page region to be parsed, you need to increase the maximum_usage_memory for this page. The current Rendered-Region-Html-Usage-Memory is: " . $pe3014864 . " bytes."); $this->v874d5d2d79->getCMSPagePropertyLayer()->setParseRegionsHtml(false); return; } } if ($v4a432b49de) $this->v874d5d2d79->getCMSSequentialLogicalActivityLayer()->prepareHTMLHashTagsWithSequentialLogicalActivities($pf8ed4912); if ($v557ad68afd && $this->f1a3f41697f($pf8ed4912)) $this->v874d5d2d79->getCMSSequentialLogicalActivityLayer()->prepareHTMLPTLWithSequentialLogicalActivities($pf8ed4912); if (($v0b46d550f1 || $v0b46d550f1 === null) && $this->f6a885ee404($pf8ed4912)) $this->me34793f8824b($pf8ed4912); if (($pe4ea0904 || $pe4ea0904 === null) && $this->f3bef9e99c8($pf8ed4912)) { $v067674f4e4 = ""; if (!$this->v399fbe3e4a) { $v067674f4e4 .= $this->mcd0c9c3002d3(); $v067674f4e4 .= $this->me83254519428(); $this->v399fbe3e4a = true; } if (!$this->pf1ffcca4 && $this->ma39a59b3ae81($pf8ed4912)) $this->pf1ffcca4 = true; if (!$this->v3f09e6b1e3 && $this->f59c8574154($pf8ed4912)) $this->v3f09e6b1e3 = true; if (!$this->pf1ffcca4) { $v067674f4e4 .= $this->mef5a21e8c399(); $this->pf1ffcca4 = true; } if (!$this->v3f09e6b1e3) { $v067674f4e4 .= $this->mbb59d6005f9d(); $this->v3f09e6b1e3 = true; } $v067674f4e4 .= $this->f041b2631c4(); $pf8ed4912 .= $v067674f4e4; } if ($v7715b7ec3a || $v7715b7ec3a === null) { if (!$this->v6a0a7afc71 && $this->me9588d645d3e($pf8ed4912)) $this->v6a0a7afc71 = true; if (!$this->v6a0a7afc71 && $this->mdcedaf4a26c2($pf8ed4912)) { $this->v6a0a7afc71 = true; $v067674f4e4 = $this->f1d71c261d4(); $pf8ed4912 .= $v067674f4e4; } } } public function beforeIncludeTemplate($v8ea5ee2b30) { $v5fb417c845 = $this->v874d5d2d79->getCMSPagePropertyLayer()->getParseFullHtml(); if (!$v5fb417c845) return; $v7eae0304dc = $this->v874d5d2d79->getCMSPagePropertyLayer()->getExecuteSLA(); if ($v7eae0304dc && !$this->v874d5d2d79->getCMSSequentialLogicalActivityLayer()->isSLAExecuted()) $this->v874d5d2d79->getCMSSequentialLogicalActivityLayer()->executeSequentialLogicalActivities(); $pf530f4c2 = $this->v874d5d2d79->getCMSPagePropertyLayer()->getMaximumUsageMemory(); if ($pf530f4c2 > 0 && ($pe3014864 = $this->f25b122578e()) > $pf530f4c2) { $this->f970c3e3281("Page full html cannot be parsed because the defined memory size in the page advanced settings was overloaded. If you really wants this page full html to be parsed, you need to increase the maximum_usage_memory for this page. The current Entities-Usage-Memory is: " . $pe3014864 . " bytes."); $this->v874d5d2d79->getCMSPagePropertyLayer()->setParseFullHtml(false); return; } $this->v27b0b8b00b = null; $this->v977419efe1 = null; $this->v83ae768e01 = false; $this->v33a175910b = array(); $this->v86b24ad560 = null; $v4a432b49de = $this->v874d5d2d79->getCMSPagePropertyLayer()->getParseHashTags(); $v557ad68afd = $this->v874d5d2d79->getCMSPagePropertyLayer()->getParsePTL(); $v7715b7ec3a = $this->v874d5d2d79->getCMSPagePropertyLayer()->getAddMyJSLib(); $pe4ea0904 = $this->v874d5d2d79->getCMSPagePropertyLayer()->getAddWidgetResourceLib(); $v0b46d550f1 = $this->v874d5d2d79->getCMSPagePropertyLayer()->getFilterByPermission(); $this->v4a432b49de = $v4a432b49de !== null ? $v4a432b49de : $this->v4a432b49de; $this->v557ad68afd = $v557ad68afd !== null ? $v557ad68afd : $this->v557ad68afd; $this->v7715b7ec3a = $v7715b7ec3a !== null ? $v7715b7ec3a : $this->v7715b7ec3a; $this->pe4ea0904 = $pe4ea0904 !== null ? $pe4ea0904 : $this->pe4ea0904; $this->v0b46d550f1 = $v0b46d550f1 !== null ? $v0b46d550f1 : $this->v0b46d550f1; if ($this->v6e120baec3 === null) $this->v6e120baec3 = $this->v874d5d2d79->getCMSSequentialLogicalActivityLayer()->getSLASettings(); if ($this->v4a432b49de === null && ( (!empty($this->v6e120baec3) || $this->f776530fa5a($v8ea5ee2b30)) && ($this->mcf15cdb1bfc3() || $this->mf797d5890cb7($v8ea5ee2b30)) )) $this->v4a432b49de = true; if ($this->v557ad68afd === null && ( $this->f42302dd8b5() || $this->f13294ae482($v8ea5ee2b30) )) $this->v557ad68afd = true; if (($this->v7715b7ec3a || $this->v7715b7ec3a === null) && ( $this->f732e29aa67() || $this->f6a554a7910($v8ea5ee2b30) )) { $this->v7715b7ec3a = false; $this->v6a0a7afc71 = true; } if ($this->v7715b7ec3a === null && ( $this->f5055545679() || $this->me129bbae3e43($v8ea5ee2b30) )) $this->v7715b7ec3a = true; if ($this->pe4ea0904 === null && ( $this->f6479cf9dac() || $this->f255377f75c($v8ea5ee2b30) )) $this->pe4ea0904 = true; if ($this->v0b46d550f1 === null && ( $this->f8ecd0ed4d8() || $this->f4d0f1d7190($v8ea5ee2b30) )) $this->v0b46d550f1 = true; if (($v7eae0304dc || $v7eae0304dc === null) && !$this->v874d5d2d79->getCMSSequentialLogicalActivityLayer()->isSLAExecuted() && ($this->v4a432b49de || $this->v557ad68afd)) { $this->v874d5d2d79->getCMSSequentialLogicalActivityLayer()->executeSequentialLogicalActivities(); if ($pf530f4c2 > 0 && ($pe3014864 = $this->f25b122578e()) > $pf530f4c2) { $this->f970c3e3281("Page full html cannot be parsed because the defined memory size in the page advanced settings was overloaded. If you really wants this page full html to be parsed, you need to increase the maximum_usage_memory for this page. The current Entities-Usage-Memory is: " . $pe3014864 . " bytes."); $this->v874d5d2d79->getCMSPagePropertyLayer()->setParseFullHtml(false); return; } } if (!$this->v32afcbd028) $this->v32afcbd028 = $this->v4a432b49de || $this->v557ad68afd || $this->v7715b7ec3a || $this->pe4ea0904 || $this->v0b46d550f1; if ($this->v32afcbd028) { $v016c1cc8fd = $this->v874d5d2d79->getCMSPagePropertyLayer()->getMaximumBufferChunkSize(); $pac9b168f = !$v016c1cc8fd || ($this->v4a432b49de && $this->mf797d5890cb7($v8ea5ee2b30) && ( !empty($this->v6e120baec3) || $this->f776530fa5a($v8ea5ee2b30) )) || ($this->v557ad68afd && $this->f13294ae482($v8ea5ee2b30)); if ($pac9b168f) ob_start(null, 0); else { $this->mcf3473fcc3c9(); ob_start(array(&$this, "bufferLengthCallback"), $v016c1cc8fd); } } } public function afterIncludeTemplate($v8ea5ee2b30) { $v5fb417c845 = $this->v874d5d2d79->getCMSPagePropertyLayer()->getParseFullHtml(); if (!$v5fb417c845) return; if ($this->v32afcbd028) { $pf8ed4912 = ob_get_contents(); ob_end_clean(); if ($this->v33a175910b["PHP_OUTPUT_HANDLER_END"]) { $pf8ed4912 = $this->v86b24ad560; } $this->f89a2f46aae($pf8ed4912); echo $pf8ed4912; } } private function bufferLengthCallback($v3646220e38, $pe1390784) { chdir(dirname($_SERVER['SCRIPT_FILENAME'])); $pf2d9f89a = ($pe1390784 & PHP_OUTPUT_HANDLER_END) || ($pe1390784 & PHP_OUTPUT_HANDLER_FINAL); if ($pf2d9f89a) { $this->v33a175910b["PHP_OUTPUT_HANDLER_END"] = true; $this->v86b24ad560 = $v3646220e38; } else { $this->v83ae768e01 = true; $this->f89a2f46aae($v3646220e38); } return $v3646220e38; } private function f89a2f46aae(&$pf8ed4912) { if (!$this->v83ae768e01) { $v4a432b49de = $this->v4a432b49de && $this->f8a41c84587($pf8ed4912) && !empty($this->v874d5d2d79->getCMSSequentialLogicalActivityLayer()->getSLASettings()); $v557ad68afd = $this->v557ad68afd && $this->f1a3f41697f($pf8ed4912); if (!$this->v874d5d2d79->getCMSSequentialLogicalActivityLayer()->isSLAExecuted() && ($v4a432b49de || $v557ad68afd)) { $v7eae0304dc = $this->v874d5d2d79->getCMSPagePropertyLayer()->getExecuteSLA(); if ($v7eae0304dc || $v7eae0304dc === null) { $this->v874d5d2d79->getCMSSequentialLogicalActivityLayer()->executeSequentialLogicalActivities(); $pf530f4c2 = $this->v874d5d2d79->getCMSPagePropertyLayer()->getMaximumUsageMemory(); if ($pf530f4c2 > 0 && ($pe3014864 = $this->f25b122578e()) > $pf530f4c2) { $this->f970c3e3281("Page full html cannot be parsed because the defined memory size in the page advanced settings was overloaded. If you really wants this page full html to be parsed, you need to increase the maximum_usage_memory for this page. The current Entities-Usage-Memory is: " . $pe3014864 . " bytes."); $this->v874d5d2d79->getCMSPagePropertyLayer()->setParseFullHtml(false); return; } } } if ($v4a432b49de) $this->v874d5d2d79->getCMSSequentialLogicalActivityLayer()->prepareHTMLHashTagsWithSequentialLogicalActivities($pf8ed4912); if ($v557ad68afd) $this->v874d5d2d79->getCMSSequentialLogicalActivityLayer()->prepareHTMLPTLWithSequentialLogicalActivities($pf8ed4912); if (($this->v0b46d550f1 || $this->v0b46d550f1 === null) && $this->f6a885ee404($pf8ed4912)) { $this->v0b46d550f1 = true; $this->me34793f8824b($pf8ed4912); } } if ($this->pe4ea0904) { if (!$this->pf1ffcca4 && $this->ma39a59b3ae81($pf8ed4912)) $this->pf1ffcca4 = true; if (!$this->v3f09e6b1e3 && $this->f59c8574154($pf8ed4912)) $this->v3f09e6b1e3 = true; $v42cb46389c = stripos($pf8ed4912, "</head>") !== false || preg_match("/</head([^>]*)>/i", $pf8ed4912); $v653771daa1 = stripos($pf8ed4912, "</body>") !== false || preg_match("/</body([^>]*)>/i", $pf8ed4912); if ($v42cb46389c) { $v067674f4e4 = ""; if (!$this->v3f09e6b1e3) { $v067674f4e4 .= $this->mbb59d6005f9d(); $this->v3f09e6b1e3 = true; } $pf8ed4912 = preg_replace("/<\/(head)([^>]*)>/i", $v067674f4e4 . "</\$1\$2>", $pf8ed4912); } if ($v653771daa1) { $v067674f4e4 = $this->mcd0c9c3002d3(); $v067674f4e4 .= $this->me83254519428(); if (!$this->pf1ffcca4) { $v067674f4e4 .= $this->mef5a21e8c399(); $this->pf1ffcca4 = true; } if (!$this->v3f09e6b1e3) { $v067674f4e4 .= $this->mbb59d6005f9d(); $this->v3f09e6b1e3 = true; } $v067674f4e4 .= $this->f041b2631c4(); $pf8ed4912 = preg_replace("/<\/(body)([^>]*)>/i", $v067674f4e4 . "</\$1\$2>", $pf8ed4912); } } if ($this->v7715b7ec3a) { if (!$this->v6a0a7afc71 && $this->me9588d645d3e($pf8ed4912)) $this->v6a0a7afc71 = true; if (!$this->v6a0a7afc71) { $v653771daa1 = stripos($pf8ed4912, "</body>") !== false || preg_match("/</body([^>]*)>/i", $pf8ed4912); if ($v653771daa1) { $this->v6a0a7afc71 = true; $v067674f4e4 = $this->f1d71c261d4(); $pf8ed4912 = preg_replace("/<\/(body)([^>]*)>/i", $v067674f4e4 . "</\$1\$2>", $pf8ed4912); } } } } private function f6479cf9dac() { $v9073377656 = $this->f4f6c6f9ac1(); return $v9073377656["has_widget"]; } private function f8ecd0ed4d8() { $v9073377656 = $this->f4f6c6f9ac1(); return $v9073377656["has_widget_permission"]; } private function f5055545679() { $v9073377656 = $this->f4f6c6f9ac1(); return $v9073377656["has_my_js_lib_html_attributes"]; } private function f732e29aa67() { $v9073377656 = $this->f4f6c6f9ac1(); return $v9073377656["has_my_js_lib_url"]; } private function f7547fa7791() { $v9073377656 = $this->f4f6c6f9ac1(); return $v9073377656["has_widget_resource_lib_js_url"]; } private function f530a0a4723() { $v9073377656 = $this->f4f6c6f9ac1(); return $v9073377656["has_widget_resource_lib_css_url"]; } private function mcf15cdb1bfc3() { $v9073377656 = $this->f4f6c6f9ac1(); return $v9073377656["has_hash_tag"]; } private function f42302dd8b5() { $v9073377656 = $this->f4f6c6f9ac1(); return $v9073377656["has_ptl"]; } private function f4f6c6f9ac1() { if ($this->pf1214e8d) return $this->pf1214e8d; $v08d9602741 = $this->v874d5d2d79->getEVC(); $pe77f177a = $v08d9602741->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler"); $v57bb97acb0 = self::CACHE_DIR_NAME . $v08d9602741->getPresentationLayer()->getSelectedPresentationId() . "/entities_props_" . md5($this->v1786433afd); if ($pe77f177a && $pe77f177a->isValid($v57bb97acb0)) { $v6490ea3a15 = $pe77f177a->read($v57bb97acb0); $this->pf1214e8d = unserialize($v6490ea3a15); if (is_array($this->pf1214e8d)) return $this->pf1214e8d; } $this->pf1214e8d = array(); $v9158f5ed68 = $this->v874d5d2d79->getCMSBlockLayer()->getBlocks(); if ($v9158f5ed68) foreach ($v9158f5ed68 as $v29fec2ceaa => $pd01e30e3) if (is_string($pd01e30e3)) { if ($this->f3bef9e99c8($pd01e30e3)) $this->pf1214e8d["has_widget"] = true; if ($this->f6a885ee404($pd01e30e3)) $this->pf1214e8d["has_widget_permission"] = true; if ($this->mdcedaf4a26c2($pd01e30e3)) $this->pf1214e8d["has_my_js_lib_html_attributes"] = true; if ($this->me9588d645d3e($pd01e30e3)) $this->pf1214e8d["has_my_js_lib_url"] = true; if ($this->ma39a59b3ae81($pd01e30e3)) $this->pf1214e8d["has_widget_resource_lib_js_url"] = true; if ($this->f59c8574154($pd01e30e3)) $this->pf1214e8d["has_widget_resource_lib_css_url"] = true; if ($this->f8a41c84587($pd01e30e3)) $this->pf1214e8d["has_hash_tag"] = true; if ($this->f1a3f41697f($pd01e30e3)) $this->pf1214e8d["has_ptl"] = true; if ($this->pf1214e8d["has_widget"] && $this->pf1214e8d["has_hash_tag"] && $this->pf1214e8d["has_ptl"] && $this->pf1214e8d["has_widget_permission"] && $this->pf1214e8d["has_my_js_lib_html_attributes"] && (!$this->peb0fcb29 || $this->pf1214e8d["has_my_js_lib_url"]) && (!$this->v93f37cfb9b || $this->pf1214e8d["has_widget_resource_lib_js_url"]) && (!$this->v254eb0a585 || $this->pf1214e8d["has_widget_resource_lib_css_url"])) break; } if (!$this->pf1214e8d["has_widget"] || !$this->pf1214e8d["has_hash_tag"] || !$this->pf1214e8d["has_ptl"] || !$this->pf1214e8d["has_widget_permission"] || !$this->pf1214e8d["has_my_js_lib_html_attributes"] || ($this->peb0fcb29 && !$this->pf1214e8d["has_my_js_lib_url"]) || ($this->v93f37cfb9b && !$this->pf1214e8d["has_widget_resource_lib_js_url"]) || ($this->v254eb0a585 && !$this->pf1214e8d["has_widget_resource_lib_css_url"])) { $v86f703e78b = $this->v874d5d2d79->getCMSTemplateLayer()->getRegions(); if ($v86f703e78b) foreach ($v86f703e78b as $v9f2c84d2ad => $pfff36d74) if ($pfff36d74) foreach ($pfff36d74 as $peebaaf55) if ($peebaaf55[0] == 1 && is_string($peebaaf55[1])) { if ($this->f3bef9e99c8($peebaaf55[1])) $this->pf1214e8d["has_widget"] = true; if ($this->f6a885ee404($peebaaf55[1])) $this->pf1214e8d["has_widget_permission"] = true; if ($this->mdcedaf4a26c2($peebaaf55[1])) $this->pf1214e8d["has_my_js_lib_html_attributes"] = true; if ($this->me9588d645d3e($peebaaf55[1])) $this->pf1214e8d["has_my_js_lib_url"] = true; if ($this->ma39a59b3ae81($peebaaf55[1])) $this->pf1214e8d["has_widget_resource_lib_js_url"] = true; if ($this->f59c8574154($peebaaf55[1])) $this->pf1214e8d["has_widget_resource_lib_css_url"] = true; if ($this->f8a41c84587($peebaaf55[1])) $this->pf1214e8d["has_hash_tag"] = true; if ($this->f1a3f41697f($peebaaf55[1])) $this->pf1214e8d["has_ptl"] = true; if ($this->pf1214e8d["has_widget"] && $this->pf1214e8d["has_hash_tag"] && $this->pf1214e8d["has_ptl"] && $this->pf1214e8d["has_widget_permission"] && $this->pf1214e8d["has_my_js_lib_html_attributes"] && (!$this->peb0fcb29 || $this->pf1214e8d["has_my_js_lib_url"]) && (!$this->v93f37cfb9b || $this->pf1214e8d["has_widget_resource_lib_js_url"]) && (!$this->v254eb0a585 || $this->pf1214e8d["has_widget_resource_lib_css_url"])) break; } } if ($pe77f177a) $pe77f177a->write($v57bb97acb0, serialize($this->pf1214e8d)); return $this->pf1214e8d; } private function f255377f75c($pf3dc0762) { $v9073377656 = $this->mc39b3360989f($pf3dc0762); return $v9073377656["has_widget"]; } private function f4d0f1d7190($pf3dc0762) { $v9073377656 = $this->mc39b3360989f($pf3dc0762); return $v9073377656["has_widget_permission"]; } private function me129bbae3e43($pf3dc0762) { $v9073377656 = $this->mc39b3360989f($pf3dc0762); return $v9073377656["has_my_js_lib_html_attributes"]; } private function f6a554a7910($pf3dc0762) { $v9073377656 = $this->mc39b3360989f($pf3dc0762); return $v9073377656["has_my_js_lib_url"]; } private function f7a54721fdc($pf3dc0762) { $v9073377656 = $this->mc39b3360989f($pf3dc0762); return $v9073377656["has_widget_resource_lib_js_url"]; } private function mc60056cdd085($pf3dc0762) { $v9073377656 = $this->mc39b3360989f($pf3dc0762); return $v9073377656["has_widget_resource_lib_css_url"]; } private function mf797d5890cb7($pf3dc0762) { $v9073377656 = $this->mc39b3360989f($pf3dc0762); return $v9073377656["has_hash_tag"]; } private function f13294ae482($pf3dc0762) { $v9073377656 = $this->mc39b3360989f($pf3dc0762); return $v9073377656["has_ptl"]; } private function f776530fa5a($pf3dc0762) { $v9073377656 = $this->mc39b3360989f($pf3dc0762); return $v9073377656["has_sla"]; } private function mc39b3360989f($pf3dc0762) { if ($this->v977419efe1) return $this->v977419efe1; $v08d9602741 = $this->v874d5d2d79->getEVC(); $pe77f177a = $v08d9602741->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler"); $v57bb97acb0 = self::CACHE_DIR_NAME . $v08d9602741->getPresentationLayer()->getSelectedPresentationId() . "/template_props_" . md5($pf3dc0762); if ($pe77f177a && $pe77f177a->isValid($v57bb97acb0)) { $v6490ea3a15 = $pe77f177a->read($v57bb97acb0); $this->v977419efe1 = unserialize($v6490ea3a15); if (is_array($this->v977419efe1)) return $this->v977419efe1; } $this->v977419efe1 = array(); $pf8ed4912 = $this->v27b0b8b00b ? $this->v27b0b8b00b : (file_exists($pf3dc0762) ? file_get_contents($pf3dc0762) : ""); if ($pf8ed4912) { $this->v977419efe1["has_widget"] = $this->f3bef9e99c8($pf8ed4912); $this->v977419efe1["has_widget_permission"] = $this->f6a885ee404($pf8ed4912); $this->v977419efe1["has_my_js_lib_html_attribtues"] = $this->mdcedaf4a26c2($pf8ed4912); $this->v977419efe1["has_my_js_lib_url"] = $this->me9588d645d3e($pf8ed4912); $this->v977419efe1["has_widget_resource_lib_js_url"] = $this->ma39a59b3ae81($pf8ed4912); $this->v977419efe1["has_widget_resource_lib_css_url"] = $this->f59c8574154($pf8ed4912); $this->v977419efe1["has_hash_tag"] = $this->f8a41c84587($pf8ed4912); $this->v977419efe1["has_ptl"] = $this->f1a3f41697f($pf8ed4912); $this->v977419efe1["has_sla"] = $this->f606b2833b6($pf8ed4912); } if ($pe77f177a) $pe77f177a->write($v57bb97acb0, serialize($this->v977419efe1)); return $this->v977419efe1; } private function f559942aaac($pf8ed4912) { $v846bc1e620 = array(); if (preg_match_all("/->\s*renderRegion\s*\(\s*(\"|')(\w+)(\"|')\s*\)/i", $pf8ed4912, $pbae7526c, PREG_PATTERN_ORDER) && $pbae7526c) foreach ($pbae7526c as $v87ae7286da) $v846bc1e620[] = $v87ae7286da[2]; return $v846bc1e620; } private function f8a41c84587($pf8ed4912) { return HashTagParameter::existsHTMLHashTagParameters($pf8ed4912); } private function f1a3f41697f($pf8ed4912) { return stripos($pf8ed4912, "<ptl:") !== false; } private function f606b2833b6($pf8ed4912) { return stripos($pf8ed4912, 'addSequentialLogicalActivities') !== false && preg_match("/->\s*addSequentialLogicalActivities\s*\(/i", $pf8ed4912); } private function f3bef9e99c8($pf8ed4912) { return stripos($pf8ed4912, "data-widget-") !== false; } private function f6a885ee404($pf8ed4912) { return stripos($pf8ed4912, "data-widget-permissions") !== false; } private function mdcedaf4a26c2($pf8ed4912) { $v55497ccd3b = array("data-confirmation", "confirmation", "data-confirmation-message", "confirmationmessage", "data-validation-label", "validationlabel", "data-validation-message", "validationmessage", "data-allow-null", "allownull", "data-allow-javascript", "allowjavascript", "data-validation-type", "validationtype", "data-validation-regex", "validationregex", "minlength", "maxlength", "min", "max", "data-mandatory-checkbox", "mandatorycheckbox", "data-min-words", "minwords", "data-max-words", "maxwords", "data-ajax", "ajax", "data-security-code", "securitycode"); return preg_match("/\s(" . implode("|", $v55497ccd3b) . ")\s*=/i", $pf8ed4912); } private function me9588d645d3e($pf8ed4912) { return $this->peb0fcb29 && stripos($pf8ed4912, $this->peb0fcb29) !== false && preg_match("/<script\s([^>]*)src\s*=\s*(\"|')" . preg_quote($this->peb0fcb29, "/") . "(\"|')/i", $pf8ed4912); } private function ma39a59b3ae81($pf8ed4912) { return $this->v93f37cfb9b && stripos($pf8ed4912, $this->v93f37cfb9b) !== false && preg_match("/<script\s([^>]*)src\s*=\s*(\"|')" . preg_quote($this->v93f37cfb9b, "/") . "(\"|')/i", $pf8ed4912); } private function f59c8574154($pf8ed4912) { return $this->v254eb0a585 && stripos($pf8ed4912, $this->v254eb0a585) !== false && preg_match("/<link\s([^>]*)href\s*=\s*(\"|')" . preg_quote($this->v254eb0a585, "/") . "(\"|')/i", $pf8ed4912); } private function f1d71c261d4() { return $this->peb0fcb29 ? '<script type="text/javascript">
			if (typeof MyJSLib == "undefined")
				document.write(\'<script src="' . $this->peb0fcb29 . '" type="text/javascript"></scr\' + \'ipt>\');
		</script>' : ""; } private function f041b2631c4() { return '<script>typeof MyWidgetResourceLib == "function" && MyWidgetResourceLib.fn.initWidgets();</script>'; } private function mef5a21e8c399() { return $this->v93f37cfb9b ? '<script type="text/javascript">
			if (typeof MyWidgetResourceLib == "undefined")
				document.write(\'<script src="' . $this->v93f37cfb9b . '" type="text/javascript"></scr\' + \'ipt>\');
		</script>' : ""; } private function mbb59d6005f9d() { return $this->v254eb0a585 ? '<link href="' . $this->v254eb0a585 . '" rel="stylesheet" type="text/css" charset="utf-8" />' : ""; } private function mcd0c9c3002d3() { $v19ab4fa4eb = ''; $pcd29cf87 = $this->ma69899e22e13(); $v963c17784f = $this->mcf3473fcc3c9(); if (is_numeric($pcd29cf87)) $v19ab4fa4eb .= 'if (typeof window.public_user_type_id == "undefined") window.public_user_type_id = ' . $pcd29cf87 . ';'; if ($v963c17784f) $v19ab4fa4eb .= 'if (typeof window.logged_user_type_ids == "undefined") window.logged_user_type_ids = ' . json_encode($v963c17784f) . ';'; $v6c839727b4 = $this->v874d5d2d79->getCMSSequentialLogicalActivityLayer()->getSLASettings(); if ($v6c839727b4) $v19ab4fa4eb .= 'if (typeof window.get_resource_url == "undefined") window.get_resource_url = "' . $this->v1786433afd . '";'; if ($v19ab4fa4eb) $v19ab4fa4eb = '<script>' . $v19ab4fa4eb . '</script>'; return $v19ab4fa4eb; } private function me83254519428($pebadbc9e = null) { $pf0e17552 = ''; $pf530f4c2 = $this->v874d5d2d79->getCMSPagePropertyLayer()->getMaximumUsageMemory(); $pd65db653 = $this->mc2ff3a334e0b(); $pebadbc9e = $pebadbc9e ? $pebadbc9e : $this->v874d5d2d79->getCMSSequentialLogicalActivityLayer()->getSLAResults(); if ($pd65db653 <= $pf530f4c2) { $pf0e17552 = json_encode($pebadbc9e); } else if ($pebadbc9e) { foreach ($pebadbc9e as $pe5c5e2fe => $v956913c90f) { $pd65db653 = $this->f2039508068($v956913c90f); if ($pd65db653 <= $pf530f4c2) $pf0e17552 .= '"' . $pe5c5e2fe . '": ' . json_encode($v956913c90f) . ','; } if ($pf0e17552) $pf0e17552 = '{' . $pf0e17552 . '}'; } if ($pf0e17552) $pf0e17552 = '<script>if (typeof window.sla_results == "undefined") window.sla_results = ' . $pf0e17552 . ';</script>'; return $pf0e17552; } private function ma69899e22e13() { if (is_numeric($this->pcd29cf87)) return $this->pcd29cf87; $this->pcd29cf87 = null; $EVC = $this->v874d5d2d79->getEVC(); $pa47fac06 = $EVC->getCommonProjectName(); @include_once $EVC->getModulePath("user/UserUtil", $pa47fac06); if (class_exists("UserUtil")) $this->pcd29cf87 = UserUtil::PUBLIC_USER_TYPE_ID; return $this->pcd29cf87; } private function mcf3473fcc3c9() { if ($this->v963c17784f) return $this->v963c17784f; $this->v963c17784f = null; $EVC = $this->v874d5d2d79->getEVC(); if (!$GLOBALS["UserSessionActivitiesHandler"]) { @include_once $EVC->getUtilPath("user_session_activities_handler", $EVC->getCommonProjectName()); if (function_exists("initUserSessionActivitiesHandler")) initUserSessionActivitiesHandler($EVC); } if ($GLOBALS["UserSessionActivitiesHandler"]) { $v317efb26b3 = $GLOBALS["UserSessionActivitiesHandler"]->getUserData(); if ($v317efb26b3) $this->v963c17784f = $v317efb26b3["user_type_ids"]; } return $this->v963c17784f; } private function f25b122578e() { $pe3014864 = memory_get_usage(); $v9423459df9 = $this->v874d5d2d79->getCMSBlockLayer()->getBlocks(); $v9423459df9["_"] = 1; $pa78bb6e8 = $this->v874d5d2d79->getCMSTemplateLayer()->getRegions(); $pa78bb6e8["_"] = 1; $pe3014864 = memory_get_usage() - $pe3014864; unset($v9423459df9); unset($pa78bb6e8); return $pe3014864 + $this->mc2ff3a334e0b(); } private function f9e95c29942($pf8ed4912) { return strlen($pf8ed4912) + $this->mc2ff3a334e0b(); } private function mc2ff3a334e0b() { $pe3014864 = memory_get_usage(); $v9423459df9 = $this->v874d5d2d79->getCMSSequentialLogicalActivityLayer()->getSLAResults(); $v9423459df9["_"] = 1; $pe3014864 = memory_get_usage() - $pe3014864; unset($v9423459df9); return $pe3014864; } private function f2039508068($v0e617f8732) { if ($v1addee5bc9) return $this->f046a268ab5($v1addee5bc9); $pe3014864 = memory_get_usage(); $v02a69d4e0f = $v0e617f8732; if (is_array($v02a69d4e0f)) $v02a69d4e0f["_"] = 1; else if (is_string($v02a69d4e0f)) $v02a69d4e0f .= 1; $pe3014864 = memory_get_usage() - $pe3014864; unset($v02a69d4e0f); return $pe3014864; } private function f046a268ab5($v972f1a5c2b) { if (is_object($v972f1a5c2b)) { $pe3014864 = memory_get_usage(); $v6ad15e25e8 = clone $v972f1a5c2b; $pe3014864 = memory_get_usage() - $pe3014864; unset($v99e00f8f0e); return $pe3014864; } } private function me34793f8824b(&$pf8ed4912) { include_once get_lib("org.phpframework.util.web.html.HtmlDomHandler"); $v335d5577bf = new HtmlDomHandler($pf8ed4912); $v4a81532cb9 = $v335d5577bf->getDOMDocument(); $v0e7bc9cefd = new DOMXPath($v4a81532cb9); $v50d32a6fc4 = $v0e7bc9cefd->query("//*[@data-widget-permissions]"); if ($v50d32a6fc4) foreach($v50d32a6fc4 as $v6694236c2c) { $v2d2046720b = $this->f6d39458f23($v6694236c2c); $pab19c382 = $v2d2046720b["show"]; $v2ed101334d = $v2d2046720b["hide"]; $v280735c413 = $v2d2046720b["remove"]; if ($v280735c413) { $pce21d699 = $v6694236c2c->parentNode; $pce21d699->removeChild($v6694236c2c); } else if ($v2ed101334d) $v335d5577bf->setElementStyle($v6694236c2c, "display", "none"); else if ($pab19c382 && strtolower($v335d5577bf->getElementStyle($v6694236c2c, "display")) == "none") $v335d5577bf->setElementStyle($v6694236c2c, "display", ""); } $pf8ed4912 = $v335d5577bf->getHtmlExact(); } private function f6d39458f23(DOMNode $v6694236c2c) { $pab19c382 = true; $v2ed101334d = false; $v280735c413 = false; if ($v6694236c2c) { $pef34936b = $v6694236c2c->getAttribute("data-widget-permissions"); if ($pef34936b) { $pef34936b = substr($pef34936b, 0, 1) == "{" ? json_decode($pef34936b) : $pef34936b; if ($pef34936b) { if (is_string($pef34936b) || is_numeric($pef34936b) || is_array($pef34936b)) { $v9acf40c110 = new stdClass(); $v9acf40c110->user_type_ids = $pef34936b; $v972f1a5c2b = new stdClass(); $v972f1a5c2b->view = $v9acf40c110; } else $v972f1a5c2b = $pef34936b; if (is_object($v972f1a5c2b)) { if (property_exists($v972f1a5c2b, "access") || property_exists($v972f1a5c2b, "view") || property_exists($v972f1a5c2b, "show")) $pab19c382 = false; $pcd29cf87 = $this->ma69899e22e13(); $v963c17784f = $this->mcf3473fcc3c9(); $v67270fc949 = $v963c17784f && is_array($v963c17784f) && count($v963c17784f) > 0; $pebadbc9e = $this->v874d5d2d79->getCMSSequentialLogicalActivityLayer()->getSLAResults(); foreach ($v972f1a5c2b as $pe5c5e2fe => $v956913c90f) { if (is_string($v956913c90f) || is_numeric($v956913c90f) || is_array($v956913c90f)) { $v9acf40c110 = new stdClass(); $v9acf40c110->user_type_ids = $v956913c90f; } else $v9acf40c110 = $v956913c90f; if (is_object($v9acf40c110)) { $pa4b7bec5 = $v9acf40c110->resources; $v22fcc5754f = $v9acf40c110->user_type_ids; $pa4b7bec5 = is_array($pa4b7bec5) ? $pa4b7bec5 : array($pa4b7bec5); $v22fcc5754f = is_array($v22fcc5754f) ? $v22fcc5754f : array($v22fcc5754f); $pa1cd511d = true; $v44d7e3d491 = true; $v91f62c85fb = false; $pbf8ba389 = false; for ($v43dd7d0051 = 0; $v43dd7d0051 < count($pa4b7bec5); $v43dd7d0051++) { $v3c238d643b = $pa4b7bec5[$v43dd7d0051]; if (!is_object($v3c238d643b)) { $v7a1b9c07b3 = new stdClass(); $v7a1b9c07b3->name = $v3c238d643b; } else $v7a1b9c07b3 = $v3c238d643b; if (strlen($v7a1b9c07b3->name)) { if (!self::f2025b2b6f3($pebadbc9e, $v7a1b9c07b3->name)) $pbf8ba389 = true; else if (self::f47dcbe04c3($pebadbc9e, $v7a1b9c07b3->name)) { $pa1cd511d = true; $v91f62c85fb = true; break; } else if (!$v91f62c85fb) $pa1cd511d = false; } } if ($pbf8ba389 && !$v91f62c85fb) { $pa1cd511d = false; if ($pe5c5e2fe == "access" || $pe5c5e2fe == "view" || $pe5c5e2fe == "show") $pab19c382 = true; } if ($pa1cd511d) for ($v43dd7d0051 = 0; $v43dd7d0051 < count($v22fcc5754f); $v43dd7d0051++) { $v6bbd1726b0 = $v22fcc5754f[$v43dd7d0051]; if (is_numeric($v6bbd1726b0)) { $pd7dc7ef2 = !$v67270fc949 && ($v6bbd1726b0 === 0 || $v6bbd1726b0 === "0" || $v6bbd1726b0 == $pcd29cf87); $v016fca0500 = $v67270fc949 && in_array($v6bbd1726b0, $v963c17784f); if ($pd7dc7ef2 || $v016fca0500) { $v44d7e3d491 = true; break; } else $v44d7e3d491 = false; } } if ($pa1cd511d && $v44d7e3d491) { if ($pe5c5e2fe == "access" || $pe5c5e2fe == "view" || $pe5c5e2fe == "show") $pab19c382 = true; else if ($pe5c5e2fe == "hide") $v2ed101334d = true; else if ($pe5c5e2fe == "remove") $v280735c413 = true; } } } } if ($v2ed101334d || $v280735c413) $pab19c382 = false; if (!$pab19c382) $v2ed101334d = true; } } } return array( "show" => $pab19c382, "hide" => $v2ed101334d, "remove" => $v280735c413, ); } private static function f47dcbe04c3($pebadbc9e, $v69013e2fd6) { if (strpos($v6107abf109, "[") !== false || strpos($v6107abf109, "]") !== false) { preg_match_all("/([^\[\]]+)/u", trim($v69013e2fd6), $pcd395670, PREG_PATTERN_ORDER); $pcd395670 = $pcd395670[1]; if ($pcd395670) { $pc37695cb = count($pcd395670); $v9994512d98 = array(); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v48b10a467d = $pcd395670[$v43dd7d0051]; if (strlen($v48b10a467d)) { if (strpos($v48b10a467d, "'") === false && strpos($v48b10a467d, '"') === false) $pcd395670[$v43dd7d0051] = '"' . $v48b10a467d . '"'; $v9994512d98[] = $pcd395670[$v43dd7d0051]; } } eval('return $sla_results[' . implode('][', $v9994512d98) . '];'); } } return $pebadbc9e[$v69013e2fd6]; } private static function f2025b2b6f3($pebadbc9e, $v69013e2fd6) { if (preg_match_all("/([^\[\]]+)/u", trim($v69013e2fd6), $pcd395670, PREG_PATTERN_ORDER)) { $pcd395670 = $pcd395670[1]; if ($pcd395670) { $pc37695cb = count($pcd395670); $v9994512d98 = array(); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v48b10a467d = trim($pcd395670[$v43dd7d0051]); if (strlen($v48b10a467d)) { if (strpos($v48b10a467d, "'") === false && strpos($v48b10a467d, '"') === false) $pcd395670[$v43dd7d0051] = '"' . $v48b10a467d . '"'; $v9994512d98[] = $pcd395670[$v43dd7d0051]; } } $pe3a1a584 = array_pop($v9994512d98); if (count($v9994512d98)) eval('return is_array($sla_results[' . implode('][', $v9994512d98) . ']) && array_key_exists($last_key, $sla_results[' . implode('][', $v9994512d98) . ']);'); else eval('return is_array($sla_results) && array_key_exists($last_key, $sla_results);'); } } return is_array($pebadbc9e) && array_key_exists($v69013e2fd6, $pebadbc9e); } private function f970c3e3281($v1db8fcc7cd, $v0275e9e86c = "error") { $v6f3a2700dd = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; debug_log("[Page: '{$this->pfefc55de}' from url: $v6f3a2700dd] $v1db8fcc7cd", $v0275e9e86c); } } ?>
