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

class CookieHandler { public static function setCurrentDomainEternalRootSafeCookie($v5e813b295b, $v67db1bd535 = "", $v8a7544e92d = 0, $pa32be502 = "", $v5d3813882f = array()) { $v8a7544e92d = $v8a7544e92d ? $v8a7544e92d : 366 * 10; $v3ac2cc0b43 = time() + intval(60 * 60 * 24 * $v8a7544e92d); if (!$pa32be502) $pa32be502 = "/"; if (!is_array($v5d3813882f)) $v5d3813882f = array(); if (empty($v5d3813882f) || !array_key_exists("domain", $v5d3813882f)) $v5d3813882f["domain"] = $_SERVER["HTTP_HOST"]; self::setSafeCookie($v5e813b295b, $v67db1bd535, $v3ac2cc0b43, $pa32be502, $v5d3813882f); } public static function setSafeCookie($v5e813b295b, $v67db1bd535, $v3ac2cc0b43, $pa32be502, $v5d3813882f = array()) { $_COOKIE[$v5e813b295b] = $v67db1bd535; if ($v5d3813882f && !empty($v5d3813882f["domain"]) && strpos($v5d3813882f["domain"], ":") !== false) $v5d3813882f["domain"] = substr($v5d3813882f["domain"], 0, strpos($v5d3813882f["domain"], ":")); if (version_compare(PHP_VERSION, "7.3") < 0) { $pe1390784 = ""; $v473e4bc926 = ""; $pe7149103 = false; $v7f17998492 = false; if ($v5d3813882f) { foreach ($v5d3813882f as $pe5c5e2fe => $v956913c90f) if (!preg_match("/^(httponly|secure)$/i", $pe5c5e2fe)) { $pe1390784 .= "; $pe5c5e2fe=$v956913c90f"; if ($pe5c5e2fe == "domain") $v473e4bc926 = $v956913c90f; } else if ($pe5c5e2fe == "secure") { $pe1390784 .= "; secure"; $pe7149103 = true; } else if ($pe5c5e2fe == "httponly") { $pe1390784 .= "; httponly"; $v7f17998492 = true; } } return setcookie($v5e813b295b, $v67db1bd535, $v3ac2cc0b43, $pa32be502 . $pe1390784) || setcookie($v5e813b295b, $v67db1bd535, $v3ac2cc0b43, $pa32be502, $v473e4bc926, $pe7149103, $v7f17998492); } $pe1390784 = $v5d3813882f ? $v5d3813882f : array(); $pe1390784["expires"] = isset($pe1390784["expires"]) ? $pe1390784["expires"] : $v3ac2cc0b43; $pe1390784["path"] = isset($pe1390784["path"]) ? $pe1390784["path"] : $pa32be502; return setcookie($v5e813b295b, $v67db1bd535, $pe1390784); } } ?>
