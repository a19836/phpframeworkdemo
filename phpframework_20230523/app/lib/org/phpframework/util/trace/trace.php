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

function print_code_trace() { $pf6794c9b = debug_backtrace(); $v462fc85f88 = array(); if ($pf6794c9b[0]["function"] == "include" && !isset($pf6794c9b[0]["args"][0])) $pf6794c9b[0]["args"][0] = __FILE__; foreach($pf6794c9b as $v43dd7d0051 => $pd9254ae2) { if (is_object($pd9254ae2['object'])) $pd9254ae2['object'] = 'CONVERTED OBJECT OF CLASS '.get_class($pd9254ae2['object']); if (is_array($pd9254ae2['args'])) { foreach ($pd9254ae2['args'] as &$pea70e132) if (is_object($pea70e132)) $pea70e132 = 'CONVERTED OBJECT OF CLASS '.get_class($pea70e132); $v462fc85f88[$v43dd7d0051] = "#".$v43dd7d0051." ".$pd9254ae2['file'].'('.$pd9254ae2['line'].') '; $v462fc85f88[$v43dd7d0051] .= !empty($pd9254ae2['object']) ? $pd9254ae2['object'].$pd9254ae2['type'] : ''; $v462fc85f88[$v43dd7d0051] .= $pd9254ae2['function'].'('.implode(', ',$pd9254ae2['args']).')'; } for ($v43dd7d0051 = count($v462fc85f88) - 1; $v43dd7d0051 >= 0; --$v43dd7d0051) echo $v462fc85f88[$v43dd7d0051]."<br>"; } ?>
