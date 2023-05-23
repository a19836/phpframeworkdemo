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

include_once get_lib("org.phpframework.util.web.MyCurl"); class MyRSS { private $v6f3a2700dd; private $paf2b3e1a; private $pae77d38c; private $v4ada8ed6b5; public function __construct($v6f3a2700dd, $pd4c5b91f = true, $paf2b3e1a = 60) { $this->paf2b3e1a = $paf2b3e1a ? $paf2b3e1a : 60; if (is_array($v6f3a2700dd)) { $pc37695cb = count($v6f3a2700dd); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v6f3a2700dd[$v43dd7d0051] = $this->f5c075eb890($v6f3a2700dd[$v43dd7d0051]); } $this->v6f3a2700dd = $v6f3a2700dd; $this->v4ada8ed6b5 = true; } else { $this->v6f3a2700dd = array($this->f5c075eb890($v6f3a2700dd)); $this->v4ada8ed6b5 = false; } if ($pd4c5b91f) { $this->pae77d38c = $this->getRssContent(); } } public function getRssContent() { $v539082ff30 = array(); $v7c0d95d431 = explode(":", $_SERVER["HTTP_HOST"]); $v7c0d95d431 = $v7c0d95d431[0]; foreach ($this->v6f3a2700dd as $v6f3a2700dd) { $v1fc19b96e1 = parse_url($v6f3a2700dd, PHP_URL_HOST); $v539082ff30[] = array( "url" => $v6f3a2700dd, "cookie" => $v7c0d95d431 == $v1fc19b96e1 ? $_COOKIE : null, "settings" => array( "referer" => $_SERVER["HTTP_REFERER"], "follow_location" => 1, "connection_timeout" => $this->paf2b3e1a ) ); } $v56a64ecb97 = new MyCurl(); $v56a64ecb97->initMultiple($v539082ff30); $v56a64ecb97->get_contents( $this->v4ada8ed6b5 ? array("wait" => true) : false ); $v040fc148b8 = $v56a64ecb97->getData(); if ($this->v4ada8ed6b5) { $pae77d38c = array(); $pc37695cb = $v040fc148b8 ? count($v040fc148b8) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pae77d38c[$v43dd7d0051] = $v040fc148b8[$v43dd7d0051]["content"]; } } else { $pae77d38c = $v040fc148b8[0]["content"]; } return $pae77d38c; } public function isRSSURL() { $pae77d38c = $this->pae77d38c; if ($this->v4ada8ed6b5) { $v806a006822 = array(); $v9994512d98 = array_keys($pae77d38c); $pc37695cb = count($v9994512d98); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pa559fb2d = $pae77d38c[ $v9994512d98[$v43dd7d0051] ]; $v5c1c342594 = false; if (is_numeric(stripos($pa559fb2d,"<rss")) || is_numeric(stripos($pa559fb2d,"<feed"))) { $v241205aec6 = simplexml_load_string($pa559fb2d); $v5c1c342594 = $v241205aec6 ? true : false; } $v806a006822[ $v9994512d98[$v43dd7d0051] ] = $v5c1c342594; } return $v806a006822; } else if (is_numeric(stripos($pae77d38c,"<rss")) || is_numeric(stripos($pae77d38c,"<feed"))) { $v241205aec6 = simplexml_load_string($pae77d38c); return $v241205aec6 ? true : false; } return false; } public function getRSSRequestData() { $v539082ff30 = array(); $v539082ff30["is_rss_url"] = $this->isRSSURL(); if ($this->v4ada8ed6b5) { $v9994512d98 = array_keys($v539082ff30["is_rss_url"]); $pc37695cb = count($v9994512d98); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { if($v539082ff30["is_rss_url"][ $v9994512d98[$v43dd7d0051] ]) { $v539082ff30["content"][ $v9994512d98[$v43dd7d0051] ] = $this->pae77d38c[ $v9994512d98[$v43dd7d0051] ]; } } } else { if ($v539082ff30["is_rss_url"]) { $v539082ff30["content"] = $this->pae77d38c; } } return $v539082ff30; } public function getRSSObject($v8eca11f492 = true) { $v539082ff30 = $this->getRSSRequestData(); $pae77d38c = $v539082ff30["content"]; if ($this->v4ada8ed6b5) { $v972f1a5c2b = array(); $pc37695cb = count($pae77d38c); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { if($pae77d38c[$v43dd7d0051]) $v972f1a5c2b[] = $this->convertToXmlToArray($pae77d38c[$v43dd7d0051], $v8eca11f492); } } else { $v972f1a5c2b = $this->convertToXmlToArray($pae77d38c, $v8eca11f492); } return $v972f1a5c2b; } public function convertToXmlToArray($v241205aec6, $v8eca11f492 = null) { $v6dcd71ad57 = new MyXML($v241205aec6); $pfb662071 = $v6dcd71ad57->toArray(); if ($v8eca11f492) { $pedd30cfa = is_array($v8eca11f492) ? $v8eca11f492 : array("convert_attributes_to_childs" => true); $pfb662071 = MyXML::complexArrayToBasicArray($pfb662071, $pedd30cfa); } return $pfb662071; } private function f5c075eb890(&$v6f3a2700dd) { if (!is_numeric(strpos($v6f3a2700dd, "://"))) { return "http://" . $v6f3a2700dd; } return $v6f3a2700dd; } } ?>
