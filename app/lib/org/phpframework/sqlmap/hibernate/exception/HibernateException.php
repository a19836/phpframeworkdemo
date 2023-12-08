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
class HibernateException extends Exception { public $problem; public function __construct($v6de691233b, $v67db1bd535 = array()) { $v9363d877fd = $pd0c2934c = null; if (is_array($v67db1bd535)) { $v9363d877fd = isset($v67db1bd535[0]) ? $v67db1bd535[0] : null; $pd0c2934c = isset($v67db1bd535[1]) ? $v67db1bd535[1] : null; } switch($v6de691233b) { case 1: $this->problem = "ERROR trying to include '$v9363d877fd' class path: $pd0c2934c"; break; case 2: $this->problem = "Hibernate obj '" . $v67db1bd535 . "' does not exist. Please check your hibernate xml files."; break; case 3: $this->problem = "Undefined id generator '$v9363d877fd'. You must select one of the following generators: [".strtolower(implode(", ", $pd0c2934c))."]"; break; case 4: $this->problem = "Object '".$v67db1bd535."' can only have one parameter map or parameter class. You cannot have multiple parameter types."; break; case 5: $this->problem = "Object '".$v67db1bd535."' can only have one result map or result class. You cannot have multiple result types."; break; case 6: $this->problem = "There is an object with out name in the hibernate xml file: '".$v67db1bd535."'."; break; case 7: $this->problem = "Duplicate class with id '".$v67db1bd535."'."; break; case 8: $this->problem = "There is a Relationship with out a name."; break; case 9: $this->problem = "Relationship '".$v67db1bd535."' can only have a result_class or a result_map."; break; case 10: $this->problem = "Relationship '".$v67db1bd535."' can only have a parameter_class or a parameter_map."; break; } } } ?>
