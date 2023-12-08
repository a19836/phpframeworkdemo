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
class TableDiagramException extends Exception { public $problem; public function __construct($v6de691233b, $v67db1bd535 = array()) { $v9363d877fd = $pd0c2934c = null; if (is_array($v67db1bd535)) { $v9363d877fd = isset($v67db1bd535[0]) ? $v67db1bd535[0] : null; $pd0c2934c = isset($v67db1bd535[1]) ? $v67db1bd535[1] : null; } switch($v6de691233b) { case 1: $this->problem = "Invalid table name!"; break; case 2: $this->problem = "Invalid attribute name for table '" . $v9363d877fd . "'. Attribute: " . print_r($pd0c2934c, true); break; case 3: $this->problem = "Invalid attribute type for table '" . $v9363d877fd . "'. Attribute: " . print_r($pd0c2934c, true); break; case 4: $this->problem = "Invalid unique key name for table '" . $v9363d877fd . "'. Attribute: " . print_r($pd0c2934c, true); break; case 5: $this->problem = "Invalid uniquekey type for table '" . $v9363d877fd . "'. Attribute: " . print_r($pd0c2934c, true); break; case 6: $this->problem = "Invalid foreign key attribute for table '" . $v9363d877fd . "'. Attribute: " . print_r($pd0c2934c, true); break; case 7: $this->problem = "Invalid foreign key reference table for table '" . $v9363d877fd . "'. Attribute: " . print_r($pd0c2934c, true); break; case 8: $this->problem = "Invalid foreign key reference attribute for table '" . $v9363d877fd . "'. Attribute: " . print_r($pd0c2934c, true); break; case 9: $this->problem = "Invalid index key name for table '" . $v9363d877fd . "'. Attribute: " . print_r($pd0c2934c, true); break; case 10: $this->problem = "Invalid index key type for table '" . $v9363d877fd . "'. Attribute: " . print_r($pd0c2934c, true); break; case 11: $this->problem = "Invalid TableDiagram. " . print_r($v67db1bd535, true); break; } } } ?>
