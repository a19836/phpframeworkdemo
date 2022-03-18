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

class SQLException extends Exception { public $problem; public function __construct($v6de691233b, $paec2c009, $v67db1bd535 = array()) { switch($v6de691233b) { case 1: $v5d3813882f = $v67db1bd535; $v273d26e1b9 = ""; if (is_array($v5d3813882f)) foreach ($v5d3813882f as $pe5c5e2fe => $v956913c90f) { $v273d26e1b9 .= ($v273d26e1b9 ? "; " : "") . "$pe5c5e2fe="; if (strtolower($pe5c5e2fe) == "password") $v273d26e1b9 .= strlen($v956913c90f) ? "***" : ""; else $v273d26e1b9 .= is_array($v956913c90f) ? "[" . implode(", ", $v956913c90f) . "]" : (is_object($v956913c90f) ? "Object(" . get_class($v956913c90f) . ")" : $v956913c90f); } $this->problem = "DB connection fail with options: $v273d26e1b9"; break; case 2: $this->problem = "ERROR selecting DB: " . $v67db1bd535[0]; break; case 3: $this->problem = "ERROR cosing DB connection."; break; case 4: $this->problem = "ERROR returning DB errno." . $v67db1bd535[0]; break; case 5: $this->problem = "ERROR returning DB error." . $v67db1bd535[0]; break; case 6: $this->problem = "ERROR executing query: " . $v67db1bd535[0]; break; case 7: $this->problem = "ERROR to free result: " . $v67db1bd535[0]; break; case 8: $this->problem = "ERROR fetching result to array. Result:" . $v67db1bd535[0] . ". Array type:" . $v67db1bd535[1]; break; case 9: $this->problem = "ERROR fetching result to row. Result:" . $v67db1bd535[0]; break; case 10: $this->problem = "ERROR fetching result to assoc array. Result:" . $v67db1bd535[0]; break; case 11: $this->problem = "ERROR fetching result to object. Result:" . $v67db1bd535[0]; break; case 12: $this->problem = "ERROR fetching field. Result:" . $v67db1bd535[0] . ". Offset:" . $v67db1bd535[1]; break; case 13: $this->problem = "ERROR getting num rows. Result:" . $v67db1bd535[0]; break; case 14: $this->problem = "ERROR getting num fields. Result:" . $v67db1bd535[0]; break; case 15: $this->problem = "ERROR in DB->getData(). SQL:" . $v67db1bd535[0]; break; case 16: $this->problem = "ERROR in DB->setData(sql). SQL:" . $v67db1bd535[0]; break; case 17: $this->problem = "ERROR: Query result null. SQL:" . $v67db1bd535[0]; break; case 18: $this->problem = "ERROR: DB Driver incorrect options. Host, username and db_name are mandatory! Your options were:[" . implode("', '", $v67db1bd535) . "]"; break; case 19: $this->problem = "ERROR: DB name is undefined in query: " . $v67db1bd535; break; case 20: $this->problem = "ERROR in DB->setCharset(" . $v67db1bd535 . ")"; break; case 21: $this->problem = "ERROR checking getData resourcing for SQL:" . $v67db1bd535[0]; break; } if (!empty($paec2c009)) { if (is_string($paec2c009)) { $this->problem .= "\n\n\n\n*** NATIVE ERROR ***\n\n$paec2c009"; parent::__construct($paec2c009, $v6de691233b, null); } else { if ($paec2c009->problem) $this->problem = $paec2c009->problem . PHP_EOL . $this->problem; parent::__construct($paec2c009->getMessage(), $v6de691233b, $paec2c009); } } } } ?>
