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

class SQLMapIncludesHandler { public static function getLibsOfResultClassAndMap($v21ff8db28c, $pce128343) { $pc06f1034 = array(); if($v21ff8db28c) $pc06f1034[] = self::md9652182c280($v21ff8db28c); if(isset($pce128343["attrib"]["class"]) && $pce128343["attrib"]["class"]) $pc06f1034[] = self::md9652182c280($pce128343["attrib"]["class"]); $pc37695cb = $pce128343["result"] ? count($pce128343["result"]) : 0; for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v9ad1385268 = $pce128343["result"][$v43dd7d0051]; if(is_array($v9ad1385268)) foreach($v9ad1385268 as $pbfa01ed1 => $v67db1bd535) if($v67db1bd535 && ($pbfa01ed1 == "output_type" || $pbfa01ed1 == "input_type")) $pc06f1034[] = self::md9652182c280($v67db1bd535); } return $pc06f1034; } private static function md9652182c280($pc24afc88) { $pbd1bc7b0 = strpos($pc24afc88, "("); $pc24afc88 = $pbd1bc7b0 !== false ? substr($pc24afc88, 0, $pbd1bc7b0) : $pc24afc88; return $pc24afc88; } public static function getRelationshipsLibsOfResultClassAndMap($pe33d544d) { $pc06f1034 = array(); if (is_array($pe33d544d)) { foreach($pe33d544d as $v016220e8f0 => $v10c59e20bd) { $v1b590c61a6 = self::getLibsOfResultClassAndMap($v10c59e20bd["result_class"], $v10c59e20bd["result_map"]); $pc06f1034 = array_merge($pc06f1034, $v1b590c61a6); } } return $pc06f1034; } public static function includeLibsOfResultClassAndMap($pc06f1034) { if (is_array($pc06f1034)) { $pc06f1034 = array_flip($pc06f1034); $pc06f1034 = array_flip($pc06f1034); reset($pc06f1034); foreach($pc06f1034 as $pc24afc88) { $pc24afc88 = get_lib($pc24afc88); if(file_exists($pc24afc88)) { include_once $pc24afc88; } } } } } ?>
