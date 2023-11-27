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

include_once get_lib("org.phpframework.joinpoint.exception.JoinPointHandlerException"); class JoinPointHandler { private $v2bb3fd16f9; public function __construct($v2bb3fd16f9) { $this->v2bb3fd16f9 = $v2bb3fd16f9; } public function executeJoinPoint($pc0e1cfe4, &$input) { if ($this->v2bb3fd16f9) foreach ($this->v2bb3fd16f9 as $v1cfba8c105 => $pa6209df1) if ($v1cfba8c105) eval('$' . $v1cfba8c105 . ' = $pa6209df1;'); $v86a0e7d16e = isset($pc0e1cfe4["method_file"]) ? $pc0e1cfe4["method_file"] : null; $v214bf3b7ee = isset($pc0e1cfe4["method_type"]) ? $pc0e1cfe4["method_type"] : null; $pb537c4d4 = isset($pc0e1cfe4["method_obj"]) ? $pc0e1cfe4["method_obj"] : null; $v603bd47baf = isset($pc0e1cfe4["method_name"]) ? $pc0e1cfe4["method_name"] : null; $pb52f9d64 = isset($pc0e1cfe4["method_static"]) ? $pc0e1cfe4["method_static"] : null; $v7433bf02e2 = isset($pc0e1cfe4["input_mapping"]) ? $pc0e1cfe4["input_mapping"] : null; $pf677bde0 = isset($pc0e1cfe4["method_args"]) ? $pc0e1cfe4["method_args"] : null; $v75e04d4657 = isset($pc0e1cfe4["output_mapping"]) ? $pc0e1cfe4["output_mapping"] : null; if (!$v603bd47baf) return false; else if ($v214bf3b7ee == "method" && !$pb537c4d4) return false; if ($v86a0e7d16e && !file_exists($v86a0e7d16e)) { launch_exception(new JoinPointHandlerException(2, null, $v86a0e7d16e)); return false; } if (is_array($v7433bf02e2)) { foreach ($v7433bf02e2 as $v342a134247) { $peb767e49 = isset($v342a134247["join_point_input"]) ? $v342a134247["join_point_input"] : null; $pf882784c = isset($v342a134247["method_input"]) ? $v342a134247["method_input"] : null; $input[$pf882784c] = &$input[$peb767e49]; if (!empty($v342a134247["erase_from_input"])) unset($input[$peb767e49]); } } $v067674f4e4 = '$v5c1c342594 = '; if ($v214bf3b7ee == "method") { if ($pb52f9d64) $v067674f4e4 .= $pb537c4d4 . '::'; else $v067674f4e4 .= (substr($pb537c4d4, 0, 1) != '$' ? '$' : '') . $pb537c4d4 . '->'; } $v067674f4e4 .= $v603bd47baf . '('; if (is_array($pf677bde0)) { $v86066462c3 = ''; foreach ($pf677bde0 as $v342a134247) { $v67db1bd535 = $v342a134247["value"]; $v3fb9f41470 = $v342a134247["type"]; if (!isset($v67db1bd535)) $v67db1bd535 = $v3fb9f41470 == "string" || $v3fb9f41470 == "date" ? "''" : (!$v3fb9f41470 ? "null" : ""); else $v67db1bd535 = $v3fb9f41470 == "variable" && $v67db1bd535 ? ((substr(trim($v67db1bd535), 0, 1) != '$' ? '$' : '') . trim($v67db1bd535)) : ($v3fb9f41470 == "string" || $v3fb9f41470 == "date" ? "\"" . addcslashes($v67db1bd535, '"') . "\"" : (!$v3fb9f41470 && strlen(trim($v67db1bd535)) == 0 ? "null" : trim($v67db1bd535)) ); $v67db1bd535 = strlen($v67db1bd535) ? $v67db1bd535 : "null"; $v86066462c3 .= ($v86066462c3 ? ", " : "") . $v67db1bd535; } $v067674f4e4 .= $v86066462c3; } $v067674f4e4 .= ');'; try { if ($v86a0e7d16e) include_once $v86a0e7d16e; eval($v067674f4e4); } catch(Exception $paec2c009) { launch_exception(new JoinPointHandlerException(1, $paec2c009, $v067674f4e4)); return false; } if (is_array($v75e04d4657)) { foreach ($v75e04d4657 as $v342a134247) { $paa4a554b = isset($v342a134247["method_output"]) ? $v342a134247["method_output"] : null; $v327bc597d8 = isset($v342a134247["join_point_output"]) ? $v342a134247["join_point_output"] : null; $input[$v327bc597d8] = &$input[$paa4a554b]; if (!empty($v342a134247["erase_from_output"])) unset($input[$paa4a554b]); } } return $v5c1c342594; } } ?>
