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

include_once get_lib("org.phpframework.util.web.ImageHandler"); class HtmlDomHandler { private $pf8ed4912 = null; private $v4a81532cb9 = null; private $pccb4b0fc = false; public function __construct($pf8ed4912, $v8d1f503e9f = "utf-8") { $this->pf8ed4912 = $pf8ed4912; if ($v8d1f503e9f) $pf8ed4912 = mb_convert_encoding($pf8ed4912, 'HTML-ENTITIES', $v8d1f503e9f); $this->pccb4b0fc = stripos($pf8ed4912, "<html>") === false && stripos($pf8ed4912, "<html ") === false; $this->v4a81532cb9 = new DOMDocument(); $v40954b8cf1 = libxml_use_internal_errors(); libxml_use_internal_errors(true); $this->v4a81532cb9->loadHTML($pf8ed4912); libxml_use_internal_errors($v40954b8cf1); } public function getDOMDocument() { return $this->v4a81532cb9; } public function getHtml() { if ($this->pccb4b0fc) { $v6694236c2c = $this->v4a81532cb9->getElementsByTagName("body"); $v6694236c2c = $v6694236c2c ? $v6694236c2c->item(0) : null; if (!$v6694236c2c) { $v6694236c2c = $this->v4a81532cb9->getElementsByTagName("head"); $v6694236c2c = $v6694236c2c ? $v6694236c2c->item(0) : null; } $pf8ed4912 = ''; if ($v6694236c2c && $v6694236c2c->childNodes) { foreach($v6694236c2c->childNodes as $v7c627adb6d) $pf8ed4912 .= $this->v4a81532cb9->saveHTML($v7c627adb6d); } return $pf8ed4912; } return $this->v4a81532cb9->saveHTML(); } public function getHtmlExact() { $pf8ed4912 = $this->getHtml(); $this->rollbackNonDesiredUrlsEnconding($pf8ed4912); return $pf8ed4912; } public function rollbackNonDesiredUrlsEnconding(&$pf8ed4912) { $pde547082 = array( "body" => "background", "html" => "manifest", "meta" => "content", "a" => "href", "link" => "href", "area" => "href", "base" => "href", "svg" => "href", "img" => array("src", "longdesc", "usemap", "srcset", "href"), "iframe" => array("src", "longdesc"), "frame" => array("src", "longdesc"), "input" => array("src", "usemap", "formaction"), "video" => array("src", "poster"), "audio" => "src", "script" => "src", "embed" => "src", "source" => array("src", "srcset"), "track" => "src", "form" => "action", "object" => array("data", "classid", "codebase", "usemap", "archive"), "applet" => array("codebase", "archive"), "blockquote" => "cite", "del" => "cite", "ins" => "cite", "q" => "cite", "head" => "profile", "button" => "formaction", "command" => "icon", ); foreach ($pde547082 as $v1b1c6a10a2 => $v325ffa1d87) if ($v325ffa1d87) foreach ($this->v4a81532cb9->getElementsByTagName($v1b1c6a10a2) as $v6694236c2c) { if (!is_array($v325ffa1d87)) $v325ffa1d87 = array($v325ffa1d87); foreach ($v325ffa1d87 as $v7162e23723) if ($v7162e23723) { $v09232ddb3d = $v6694236c2c->getAttribute($v7162e23723); if ($v09232ddb3d) { $pc7501f8e = $this->v4a81532cb9->createElement($v1b1c6a10a2); $pc7501f8e->setAttribute($v7162e23723, $v09232ddb3d); $pa9854dd5 = $this->v4a81532cb9->saveHTML($pc7501f8e); unset($pc7501f8e); preg_match('/' . $v7162e23723 . '\s*=\s*"([^"]*)"/i', $pa9854dd5, $pbae7526c, PREG_OFFSET_CAPTURE); if ($pbae7526c && $pbae7526c[1]) { $pb479947b = $pbae7526c[1][0]; if ($pb479947b && $pb479947b != $v09232ddb3d) { $pf8ed4912 = str_replace($pb479947b, $v09232ddb3d, $pf8ed4912); } } } } } } public function isHTML() { return $this->pf8ed4912 != strip_tags($this->pf8ed4912); } public function resizeImages() { $v5c1c342594 = true; if (stripos($this->pf8ed4912, "<img ") !== false) { $v64c793a2da = $this->v4a81532cb9->getElementsByTagName('img'); if ($v64c793a2da) foreach ($v64c793a2da as $v8e4fc13de4) if ($this->isInlineImage($v8e4fc13de4)) { $v607a49cf36 = strstr($this->getElementStyle($v8e4fc13de4, "width", "px"), "px", true); $v3a0455afd7 = strstr($this->getElementStyle($v8e4fc13de4, "height", "px"), "px", true); $v607a49cf36 = $v607a49cf36 ? $v607a49cf36 : $v8e4fc13de4->getAttribute("width"); $v3a0455afd7 = $v3a0455afd7 ? $v3a0455afd7 : $v8e4fc13de4->getAttribute("height"); if (is_numeric($v607a49cf36) || is_numeric($v3a0455afd7)) { $v7a2d3a6a7c = $this->getInlineImageBase64DataDecoded($v8e4fc13de4); if ($v7a2d3a6a7c) { $v6b146f3e75 = tempnam(sys_get_temp_dir(), 'src_img_resize_'); if (file_put_contents($v6b146f3e75, $v7a2d3a6a7c) > 0) { if (!$v607a49cf36 || !$v3a0455afd7) { list($pc5166886, $pacf2a341) = getimagesize($v6b146f3e75); if ($pc5166886 && $pacf2a341) { if (!$v607a49cf36) $v607a49cf36 = ($v3a0455afd7 * $pc5166886) / $pacf2a341; else $v3a0455afd7 = ($v607a49cf36 * $pacf2a341) / $pc5166886; } } if ($v607a49cf36 && $v3a0455afd7) { $pc96665cf = new ImageHandler(); $v1a74c80ef8 = tempnam(sys_get_temp_dir(), 'dst_img_resize_'); if (@$pc96665cf->isImageBinaryValid($v6b146f3e75) && @$pc96665cf->imageResize($v6b146f3e75, $v1a74c80ef8, $v607a49cf36, $v3a0455afd7)) { $v7a2d3a6a7c = file_get_contents($v1a74c80ef8); $v7a2d3a6a7c = base64_encode($v7a2d3a6a7c); $v92dcc541a8 = $v8e4fc13de4->getAttribute("src"); $v7e4b517c18 = stripos($v92dcc541a8, "base64,") + strlen("base64,"); $v8e4fc13de4->setAttribute("src", substr($v92dcc541a8, 0, $v7e4b517c18) . " " . $v7a2d3a6a7c); } else $v5c1c342594 = false; if (file_exists($v1a74c80ef8)) unlink($v1a74c80ef8); } else $v5c1c342594 = false; } else $v5c1c342594 = false; if (file_exists($v6b146f3e75)) unlink($v6b146f3e75); } } } } return $v5c1c342594; } public function getElementStyle($v06f2bc39aa, $pccf908cc, $v35facb36d1 = false) { $pccf908cc = strtolower($pccf908cc); $v35facb36d1 = is_array($v35facb36d1) ? $v35facb36d1 : ($v35facb36d1 ? array($v35facb36d1) : $v35facb36d1); $v6a9f910316 = $v06f2bc39aa->getAttribute("style"); if ($v6a9f910316) { $ped0a6251 = explode(";", $v6a9f910316); $pc37695cb = count($ped0a6251); for ($v43dd7d0051 = $pc37695cb - 1; $v43dd7d0051 >= 0; $v43dd7d0051--) { $v9cd205cadb = explode(":", $ped0a6251[$v43dd7d0051]); if (strtolower(trim($v9cd205cadb[0])) == $pccf908cc) { $v956913c90f = isset($v9cd205cadb[1]) ? trim($v9cd205cadb[1]) : ""; if (!$v35facb36d1) return $v956913c90f; else foreach ($v35facb36d1 as $v1b08a89324) if (stripos($v956913c90f, $v1b08a89324) !== false) return $v956913c90f; } } } return null; } public function setElementStyle($v06f2bc39aa, $pccf908cc, $pcbedc5d4, $v35facb36d1 = false) { $pccf908cc = strtolower($pccf908cc); $v35facb36d1 = is_array($v35facb36d1) ? $v35facb36d1 : ($v35facb36d1 ? array($v35facb36d1) : $v35facb36d1); $v6a9f910316 = $v06f2bc39aa->getAttribute("style"); $v77fe9fc649 = $v6a9f910316; if ($v6a9f910316) { $ped0a6251 = explode(";", $v6a9f910316); $pc37695cb = count($ped0a6251); for ($v43dd7d0051 = $pc37695cb - 1; $v43dd7d0051 >= 0; $v43dd7d0051--) { $v9cd205cadb = explode(":", $ped0a6251[$v43dd7d0051]); if (strtolower(trim($v9cd205cadb[0])) == $pccf908cc) { $v956913c90f = isset($v9cd205cadb[1]) ? trim($v9cd205cadb[1]) : ""; $v7959970a41 = false; if (!$v35facb36d1) $v7959970a41 = true; else foreach ($v35facb36d1 as $v1b08a89324) if (stripos($v956913c90f, $v1b08a89324) !== false) { $v7959970a41 = true; break; } if ($v7959970a41) { $v91a962d917 = $pcbedc5d4 === "" ? "" : $v9cd205cadb[0] . ":$pcbedc5d4;"; $v77fe9fc649 = str_replace($ped0a6251[$v43dd7d0051] . ";", $v91a962d917, $v6a9f910316); } } } if ($v77fe9fc649 == $v6a9f910316 && $pcbedc5d4 !== "") $v77fe9fc649 .= (preg_match("/;\s*$/", $v77fe9fc649) ? "" : ";") . "$pccf908cc:$pcbedc5d4;"; } else $v77fe9fc649 = "$pccf908cc:$pcbedc5d4;"; if ($v77fe9fc649 != $v6a9f910316) $v06f2bc39aa->setAttribute("style", $v77fe9fc649); } public function isInlineImage($v8e4fc13de4) { if ($v8e4fc13de4) { $v92dcc541a8 = $v8e4fc13de4->getAttribute("src"); return $v92dcc541a8 && substr($v92dcc541a8, 0, 5) == "data:"; } } public function getInlineImageBase64Data($v8e4fc13de4) { if ($v8e4fc13de4) { $v92dcc541a8 = $v8e4fc13de4->getAttribute("src"); if ($v92dcc541a8 && substr($v92dcc541a8, 0, 5) == "data:") { $v7e4b517c18 = stripos($v92dcc541a8, "base64,"); if ($v7e4b517c18 !== false) { $v7e4b517c18 += strlen("base64,"); $v92dcc541a8 = substr($v92dcc541a8, $v7e4b517c18); } return str_replace(' ', '+', trim(urldecode($v92dcc541a8))); } } } public function getInlineImageBase64DataDecoded($v8e4fc13de4) { $v7a2d3a6a7c = $this->getInlineImageBase64Data($v8e4fc13de4); if ($v7a2d3a6a7c) { $pc0e5e2e9 = ""; for ($v43dd7d0051 = 0; $v43dd7d0051 < ceil(strlen($v7a2d3a6a7c) / 256); $v43dd7d0051++) $pc0e5e2e9 = $pc0e5e2e9 . base64_decode(substr($v7a2d3a6a7c, $v43dd7d0051*256, 256)); return $pc0e5e2e9; return base64_decode($v7a2d3a6a7c); } } public function getInlineImageContentType($v8e4fc13de4) { if ($v8e4fc13de4) { $v92dcc541a8 = $v8e4fc13de4->getAttribute("src"); if ($v92dcc541a8 && substr($v92dcc541a8, 0, 5) == "data:") { $pbd1bc7b0 = stripos($v92dcc541a8, ";", 5); if ($pbd1bc7b0) { $v1e71ed36f0 = substr($v92dcc541a8, 5, $pbd1bc7b0 - 5); return strtolower(substr($v1e71ed36f0, 0, 6)) == "image/" ? $v1e71ed36f0 : null; } } } } public function saveInlineImageToFile($v8e4fc13de4, $pf3dc0762) { $v7a2d3a6a7c = $this->getInlineImageBase64DataDecoded($v8e4fc13de4); if ($v7a2d3a6a7c) { $v3d76571aa6 = @imagecreatefromstring($v7a2d3a6a7c); if ($v3d76571aa6) { $v71dafe3739 = $this->getInlineImageContentType($v8e4fc13de4); switch(strtolower($v71dafe3739)){ case 'image/bmp': $v5c1c342594 = imagewbmp($v3d76571aa6, $pf3dc0762); break; case 'image/gif': $v5c1c342594 = imagegif($v3d76571aa6, $pf3dc0762); break; case 'image/jpeg': case 'image/jpg': $v5c1c342594 = imagejpeg($v3d76571aa6, $pf3dc0762); break; case 'image/png': $v5c1c342594 = imagepng($v3d76571aa6, $pf3dc0762); break; default: $v5c1c342594 = imagejpeg($v3d76571aa6, $pf3dc0762); } imagedestroy($v3d76571aa6); } if (!$v5c1c342594) $v5c1c342594 = file_put_contents($pf3dc0762, $v7a2d3a6a7c) > 0; } return $v5c1c342594; } } ?>
