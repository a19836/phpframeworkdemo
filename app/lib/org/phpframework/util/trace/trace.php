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

function print_code_trace() { $pf6794c9b = debug_backtrace(); $v462fc85f88 = array(); if (isset($pf6794c9b[0]["function"]) && $pf6794c9b[0]["function"] == "include" && !isset($pf6794c9b[0]["args"][0])) $pf6794c9b[0]["args"][0] = __FILE__; foreach($pf6794c9b as $v43dd7d0051 => $pd9254ae2) { $v7dffdb5a5b = isset($pd9254ae2['file']) ? $pd9254ae2['file'] : null; $v259d35fa15 = isset($pd9254ae2['line']) ? $pd9254ae2['line'] : null; $v4948cc5869 = isset($pd9254ae2['object']) ? $pd9254ae2['object'] : null; $v3fb9f41470 = isset($pd9254ae2['type']) ? $pd9254ae2['type'] : null; $v2f4e66e00a = isset($pd9254ae2['function']) ? $pd9254ae2['function'] : null; $v86066462c3 = isset($pd9254ae2['args']) ? $pd9254ae2['args'] : null; if (is_object($v4948cc5869)) $v4948cc5869 = 'CONVERTED OBJECT OF CLASS '.get_class($v4948cc5869); if (is_array($v86066462c3)) { foreach ($v86066462c3 as &$pea70e132) if (is_object($pea70e132)) $pea70e132 = 'CONVERTED OBJECT OF CLASS '.get_class($pea70e132); $v462fc85f88[$v43dd7d0051] = "#".$v43dd7d0051." ".$v7dffdb5a5b.'('.$v259d35fa15.') '; $v462fc85f88[$v43dd7d0051] .= $v4948cc5869 ? $v4948cc5869.$v3fb9f41470 : ''; $v462fc85f88[$v43dd7d0051] .= $v2f4e66e00a.'('.implode(', ',$v86066462c3).')'; } for ($v43dd7d0051 = count($v462fc85f88) - 1; $v43dd7d0051 >= 0; --$v43dd7d0051) echo $v462fc85f88[$v43dd7d0051]."<br>"; } ?>
