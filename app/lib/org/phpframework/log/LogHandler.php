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

include_once get_lib("org.phpframework.log.ILogHandler"); class LogHandler implements ILogHandler { private $v471bf34077; private $pe9578251; private $pf3dc0762; private $v67ec30e2c2; private $v4ab372da3a; protected $back_traces_limit = 15; protected $files_to_exclude = array("LogHandler.php", "ExceptionLogHandler.php"); protected $max_file_path_prefix_length = 20; protected $max_file_path_suffix_length = 50; public function __construct() { $this->v471bf34077 = 0; $this->pe9578251 = false; $this->pf3dc0762 = false; $this->v67ec30e2c2 = ""; } public function setLogLevel($v471bf34077) {$this->v471bf34077 = $v471bf34077;} public function getLogLevel() {return $this->v471bf34077;} public function setEchoActive($pe9578251) {$this->pe9578251 = $pe9578251;} public function getEchoActive() {return $this->pe9578251;} public function setFilePath($pf3dc0762) {$this->pf3dc0762 = $pf3dc0762;} public function getFilePath() {return $this->pf3dc0762;} public function setCSS($v67ec30e2c2) {$this->v67ec30e2c2 = $v67ec30e2c2;} public function getCSS() {return $this->v67ec30e2c2;} public function setRootPath($v4ab372da3a) {$this->v4ab372da3a = $v4ab372da3a;} public function getRootPath() {return $this->v4ab372da3a;} public function setExceptionLog($pa4e4963d, $pb82223e4 = null, $pe9578251 = null) { if ($this->v471bf34077 >= 1) { $pb82223e4 = empty($pb82223e4) ? true : $pb82223e4; return $this->f91aef719df("EXCEPTION", $pa4e4963d, $pb82223e4, $pe9578251); } } public function setErrorLog($pa4e4963d, $pb82223e4 = null, $pe9578251 = null) { if ($this->v471bf34077 >= 2) return $this->f91aef719df("ERROR", $pa4e4963d, $pb82223e4, $pe9578251); } public function setInfoLog($pa4e4963d, $pb82223e4 = null, $pe9578251 = null) { if ($this->v471bf34077 >= 3) return $this->f91aef719df("INFO", $pa4e4963d, $pb82223e4, $pe9578251); } public function setDebugLog($pa4e4963d, $pb82223e4 = null, $pe9578251 = null) { if ($this->v471bf34077 >= 4) return $this->f91aef719df("DEBUG", $pa4e4963d, $pb82223e4, $pe9578251); } private function f91aef719df($v0275e9e86c, $pa4e4963d, $pb82223e4 = null, $pe9578251 = null) { $pa4e4963d = $this->mc0b65dbaa776($v0275e9e86c, "MESSAGE", $pa4e4963d); $v3cc61c7f06 = ""; if (!empty($pb82223e4)) { if (is_string($pb82223e4) && !is_numeric($pb82223e4)) { $v3cc61c7f06 = $pb82223e4; $v3cc61c7f06 = $this->mc0b65dbaa776($v0275e9e86c, "TRACE", "\n$v3cc61c7f06"); $v3cc61c7f06 .= "<br/><hr/><br/>"; } else if (is_array($pb82223e4)) { $pa389719d = $pb82223e4; $v3cc61c7f06 = $this->maa9584c0dcb7($pa389719d); $v3cc61c7f06 = $this->mc0b65dbaa776($v0275e9e86c, "TRACE", "\n$v3cc61c7f06"); $v3cc61c7f06 .= "<br/><hr/><br/>"; } $pa389719d = debug_backtrace(); $v819b29082b = $this->maa9584c0dcb7($pa389719d); $v819b29082b = $this->mc0b65dbaa776($v0275e9e86c, "TRACE", "\n$v819b29082b"); $v3cc61c7f06 .= $v819b29082b; } $this->f5af9e45e9b($pa4e4963d, $v3cc61c7f06, $pe9578251); } private function f5af9e45e9b($pa4e4963d, $v3cc61c7f06 = null, $pe9578251 = null) { if ($this->v471bf34077 > 0) { $this->mf543c987d471($pa4e4963d, $v3cc61c7f06); if ((isset($pe9578251) && $pe9578251) || (!isset($pe9578251) && $this->pe9578251)) echo "<style type=\"text/css\">" . $this->v67ec30e2c2 . "</style>
				<div class=\"log_handler\">
					<div class=\"message\">" . str_replace("\n", "<br/>", $pa4e4963d) . "</div>
					<div class=\"trace\">" . str_replace("\n", "<br/>", $v3cc61c7f06) . "</div>
				</div>"; } } private function mf543c987d471($pa4e4963d, $v3cc61c7f06 = null) { $pa4e4963d = $this->me6fe4e3799fe($pa4e4963d); $pffa799aa = "\n" . $pa4e4963d . $v3cc61c7f06; if (!empty($this->pf3dc0762)) error_log($pffa799aa, 3, $this->pf3dc0762); else error_log($pffa799aa, 0); } private function mc0b65dbaa776($v0275e9e86c, $pde3978c7, $pffa799aa) { $pffa799aa = $this->me6fe4e3799fe($pffa799aa); $pffa799aa = str_replace($this->v4ab372da3a, "", $pffa799aa); return "<span class=\"" . strtolower($v0275e9e86c) . "\">[$v0275e9e86c] [" . date("Y-m-d H:i:s") . "] [$pde3978c7]: $pffa799aa\n</span>"; } private function me6fe4e3799fe($pffa799aa) { return is_array($pffa799aa) || is_object($pffa799aa) ? print_r($pffa799aa, 1) : trim($pffa799aa); } private function maa9584c0dcb7($pa389719d, $pcd5a01f0 = "\n") { $v50890f6f30 = array(); $v01aed9e265 = 0; $v636b96dccf = $this->back_traces_limit; $v16ac35fd79 = $pa389719d ? count($pa389719d) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $v16ac35fd79; $v43dd7d0051++) { $pd9254ae2 = $pa389719d[$v43dd7d0051]; $v7dffdb5a5b = $pd9254ae2["file"]; $v3ffda1ef79 = basename($v7dffdb5a5b); if (in_array($v3ffda1ef79, $this->files_to_exclude)) { ++$v01aed9e265; continue 1; } $v7dffdb5a5b = str_replace($this->v4ab372da3a, "", $v7dffdb5a5b); if (strlen($v7dffdb5a5b) > $this->max_file_path_prefix_length + $this->max_file_path_suffix_length) { $v7dffdb5a5b = substr($v7dffdb5a5b, 0, $this->max_file_path_prefix_length) . "..." . substr($v7dffdb5a5b, strlen($v7dffdb5a5b) - $this->max_file_path_suffix_length); } else { $v7dffdb5a5b = $v7dffdb5a5b; } $pffa799aa = "<strong>#" . ($v43dd7d0051 - $v01aed9e265) . " " . $v7dffdb5a5b . "(" . $pd9254ae2["line"] . "):</strong> "; if($pd9254ae2["function"] == "include" || $pd9254ae2["function"] == "include_once" || $pd9254ae2["function"] == "require_once" || $pd9254ae2["function"] == "require"){ $pffa799aa .= $pd9254ae2["function"] . "('" . $pd9254ae2["args"][0] . "')"; } else { $v4948cc5869 = ""; $pf3b79f6d = ""; if (isset($pd9254ae2["class"])) { $v4948cc5869 = $pd9254ae2["class"] . $pd9254ae2["type"]; $pf3b79f6d = self::getArgsInString($pd9254ae2["args"]); } $pffa799aa .= $v4948cc5869 . $pd9254ae2["function"] . "(" . $pf3b79f6d . ")"; } $v50890f6f30[] = $pffa799aa; --$v636b96dccf; if ($v636b96dccf <= 0) { break; } } return implode($pcd5a01f0, $v50890f6f30); } public static function getArgsInString($v86066462c3) { if (is_array($v86066462c3)) { $v6beb66fea4 = array(); $v16ac35fd79 = count($v86066462c3); for ($v43dd7d0051 = 0; $v43dd7d0051 < $v16ac35fd79; $v43dd7d0051++) $v6beb66fea4[] = self::getArgInString( $v86066462c3[$v43dd7d0051] ); return implode(", ", $v6beb66fea4); } return ""; } public static function getArgInString($pea70e132) { if (is_array($pea70e132)) return stripslashes(json_encode($pea70e132)); else if (is_object($pea70e132)) return "Object(" . get_class($pea70e132) . ")"; else if ($pea70e132 === true) return "true"; else if ($pea70e132 === false) return "false"; else if ($pea70e132 == null) return "null"; else if (is_numeric($pea70e132)) return (int)$pea70e132; else return "'" . $pea70e132 . "'"; } public function tail($v00f73eb9bc = 0, $pc7f91093 = 0, $pe9b00998 = 0, $pd806302d = true, $pc48eb293 = false) { $pf7dd614f = ''; $v474e9d2927 = $v00f73eb9bc > 0; $peb16bb8b = $pc7f91093 > 0; $v9a84a79e2e = @fopen($this->pf3dc0762, "rb"); if ($pc48eb293 && @flock($v9a84a79e2e, LOCK_SH) === false) return false; if ($v9a84a79e2e === false) return false; $pb1c9532d = stream_get_meta_data($v9a84a79e2e); if ($pb1c9532d['seekable']) { if (!$pd806302d) $v3646220e38 = 4096; else { $v339f9b50e0 = $v474e9d2927 ? max($v00f73eb9bc, $pe9b00998) : $pe9b00998; $v3646220e38 = ($v339f9b50e0 < 2 ? 64 : ($v339f9b50e0 < 10 ? 512 : 4096)); } if ($peb16bb8b) { $v3b1b832e46 = filesize($this->pf3dc0762); $v9c75c2f068 = $v3b1b832e46 - $pc7f91093; if ($v9c75c2f068 < 0) return ''; $v3646220e38 = min($pc7f91093, $v3646220e38); } fseek($v9a84a79e2e, -1, SEEK_END); if (fread($v9a84a79e2e, 1) == "\n") { if ($pe9b00998 > 0) { $pe9b00998++; $v00f73eb9bc--; } } else $v00f73eb9bc--; if (!$v474e9d2927) { $v67d2093035 = $peb16bb8b ? $pc7f91093 : 0; fseek($v9a84a79e2e, $v67d2093035, SEEK_SET); while (!feof($v9a84a79e2e)) $pf7dd614f .= fread($v9a84a79e2e, $v3646220e38); while ($pe9b00998 > 0) { $pf7dd614f = substr($pf7dd614f, 0, strrpos($pf7dd614f, "\n") + 1); --$pe9b00998; } } else { fseek($v9a84a79e2e, -1, SEEK_END); $ped13d90f = ''; while (($pef0c9497 = ftell($v9a84a79e2e)) > 0 && (!$v474e9d2927 || $v00f73eb9bc >= 0)) { $v67d2093035 = min($pef0c9497, $v3646220e38); if ($peb16bb8b) { if ($pef0c9497 <= $pc7f91093) break; $v56167f16a2 = $pef0c9497 - $v67d2093035; if ($v56167f16a2 < $pc7f91093) $v67d2093035 = $pef0c9497 - $pc7f91093; } if ($pef0c9497 - $v67d2093035 < 0) $v67d2093035 -= abs($pef0c9497 - $v67d2093035); if (fseek($v9a84a79e2e, -$v67d2093035, SEEK_CUR) == -1) break; else $pef0c9497 -= $v67d2093035; if ($v67d2093035 == 0) break; $ped13d90f = fread($v9a84a79e2e, $v67d2093035); $v15f3268002 = substr_count($ped13d90f, "\n"); $v665aaf2175 = mb_strlen($ped13d90f, '8bit'); if (fseek($v9a84a79e2e, -$v665aaf2175, SEEK_CUR) == -1) break; if ($pe9b00998 > 0) { if ($pe9b00998 > $v15f3268002) { $pe9b00998 -= $v15f3268002; $ped13d90f = ''; } else { $pbd1bc7b0 = 0; while ($pe9b00998 > 0) { if ($pbd1bc7b0 > 0) $pac65f06f = $pbd1bc7b0 - $v665aaf2175 - 1; else $pac65f06f = 0; if ($pac65f06f < 0) break; $pbd1bc7b0 = strrpos($ped13d90f, "\n", $pac65f06f); if ($pbd1bc7b0 !== false) $pe9b00998--; else break; } $ped13d90f = substr($ped13d90f, 0, $pbd1bc7b0); $v15f3268002 = substr_count($ped13d90f, "\n"); } } if (strlen($ped13d90f) > 0) { $pf7dd614f = $ped13d90f . $pf7dd614f; $v00f73eb9bc -= $v15f3268002; } if ($pef0c9497 <= 0) break; } if ($v474e9d2927) while ($v00f73eb9bc++ < 0) { $pf7dd614f = substr($pf7dd614f, strpos($pf7dd614f, "\n") + 1); } } } if ($pc48eb293) @flock($v9a84a79e2e, LOCK_UN); fclose($v9a84a79e2e); return trim($pf7dd614f); } } ?>
