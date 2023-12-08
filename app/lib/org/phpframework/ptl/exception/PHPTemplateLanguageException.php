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
class PHPTemplateLanguageException extends Exception { public $problem; public function __construct($v6de691233b, $v67db1bd535 = array(), $paec2c009 = null) { $v9363d877fd = $pd0c2934c = $v0f4dee0f91 = $v62e526cceb = null; if (is_array($v67db1bd535)) { $v9363d877fd = isset($v67db1bd535[0]) ? $v67db1bd535[0] : null; $pd0c2934c = isset($v67db1bd535[1]) ? $v67db1bd535[1] : null; $v0f4dee0f91 = isset($v67db1bd535[1][0]) ? $v67db1bd535[1][0] : null; $v62e526cceb = isset($v67db1bd535[2]) ? $v67db1bd535[2] : null; } switch($v6de691233b) { case 1: $this->problem = "ERROR: Invalid name for tag: " . $v67db1bd535; break; case 2: $this->problem = "ERROR: $v62e526cceb Argument must be string instead of: $pd0c2934c. Error in in the following tag: $v9363d877fd"; break; case 3: $this->problem = "ERROR: $v62e526cceb Argument must be a variable, instead of: '$v0f4dee0f91'. Error in in the following tag: $v9363d877fd"; break; case 4: $this->problem = "ERROR: $v62e526cceb Argument must be string or variable instead of: $pd0c2934c. Error in in the following tag: $v9363d877fd"; break; case 5: $this->problem = "ERROR: $v62e526cceb Argument must be a variable or function, instead of: '$v0f4dee0f91'. Error in in the following tag: $v9363d877fd"; break; case 6: $this->problem = "ERROR: Incorrect number of arguments. Minimum number is 2 and maximum is 3. Error in the following tag: " . $v67db1bd535; break; case 7: $this->problem = "ERROR: The following php code couldn't be executed with eval: \n<pre>$v67db1bd535</pre>"; break; } if (!empty($paec2c009)) { if (is_string($paec2c009)) { $this->problem .= "\n\nNATIVE ERROR\n$paec2c009"; parent::__construct($paec2c009, $v6de691233b, null); } else parent::__construct($paec2c009->problem ? $paec2c009->problem : $paec2c009->getMessage(), $v6de691233b, $paec2c009); } } } ?>
