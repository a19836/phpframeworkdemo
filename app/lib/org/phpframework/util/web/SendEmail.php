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

class SendEmail { private $v7633a72607; private $v565a00eace; private $pbebc8cef; public function __construct($v7633a72607, $v565a00eace = false, $v8d1f503e9f = "UTF8") { $pd74a39f0 = ""; $v9cd205cadb = explode(",", $v7633a72607); foreach ($v9cd205cadb as $v1d2d80ed32) $pd74a39f0 .= ($pd74a39f0 ? ", " : "") . (strpos($v7633a72607, "<") !== false ? $v1d2d80ed32 : "$v1d2d80ed32 <$v1d2d80ed32>"); $this->v7633a72607 = $pd74a39f0; $this->v565a00eace = $v565a00eace ? $v565a00eace : "NextPart00A_000_1951044531D"; if($v8d1f503e9f == "UTF8") { $this->pbebc8cef = array("utf-8", "8"); } else { $this->pbebc8cef = array("iso-8859-1", "7"); } } public function send($pbb0bffd6, $pe4c7aeda, $pffa799aa, $pc0dcc212 = false) { $v2df37fdc6e = $pffa799aa; $pc3d17c95 = str_replace(array("<br>", "<br >", "<br/>", "<br />"), "\n", $pffa799aa); $pc3d17c95 = strip_tags($pc3d17c95); $v15493e4c60 = $this->getMultiPartHeader($this->v7633a72607, $pe4c7aeda, $pc0dcc212); $pae77d38c = $this->getMultiPartContent($pc3d17c95, $v2df37fdc6e); $v5c1c342594 = mail($pbb0bffd6, $pe4c7aeda, $pae77d38c, $v15493e4c60); return $v5c1c342594; } public function getMultiPartContent($v39e1347c93, $pf8ed4912) { return '------='.$this->v565a00eace.'
Content-Type: text/plain;
	charset="'.$this->pbebc8cef[0].'"
Content-Transfer-Encoding: '.$this->pbebc8cef[1].'bit

'.$v39e1347c93.'

------='.$this->v565a00eace.'
Content-Type: text/html;
	charset="'.$this->pbebc8cef[0].'"
Content-Transfer-Encoding: '.$this->pbebc8cef[1].'bit

'.$pf8ed4912.'

------='.$this->v565a00eace.'--'; } public function getMultiPartHeader($v7633a72607, $pe4c7aeda, $pc0dcc212 = false) { $v15493e4c60 = 'From: '.$v7633a72607.'
Subject: '.$pe4c7aeda.'
Content-Transfer-Encoding: '.$this->pbebc8cef[1].'bit
MIME-Version: 1.0
Content-Type: multipart/alternative; boundary="----='.$this->v565a00eace.'"'; $pb84b9972 = false; $v6a34eec6a4 = false; if(is_array($pc0dcc212)) { $v9994512d98 = array_keys($pc0dcc212); $pc37695cb = count($v9994512d98); for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pbfa01ed1 = $v9994512d98[$v43dd7d0051]; $v15493e4c60 .= "\n{$pbfa01ed1}: " . $pc0dcc212[$pbfa01ed1]; if(strtolower($pbfa01ed1) == "return-path") { $pb84b9972 = $v43dd7d0051; } else if(strtolower($pbfa01ed1) == "reply-to") { $v6a34eec6a4 = $v43dd7d0051; } } } if(!$pb84b9972 || !$v6a34eec6a4) { $v114d593676 = $pb84b9972 !== false ? $pc0dcc212[ $v9994512d98[$pb84b9972] ] : ($v6a34eec6a4 !== false ? $pc0dcc212[ $v9994512d98[$v6a34eec6a4] ] : false); $v15493e4c60 .= $pb84b9972 === false ? "\nReturn-Path: ".($v114d593676 ? $v114d593676 : $v7633a72607) : ""; $v15493e4c60 .= $v6a34eec6a4 === false ? "\nReply-To: ".($v114d593676 ? $v114d593676 : $v7633a72607) : ""; } return $v15493e4c60; } } ?>
