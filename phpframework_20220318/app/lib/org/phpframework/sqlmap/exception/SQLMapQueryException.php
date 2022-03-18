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

class SQLMapQueryException extends Exception { public $problem; public function __construct($v6de691233b, $v67db1bd535 = array()) { switch($v6de691233b) { case 1: $this->problem = "ERROR: ParameterMap item doesn't have column name defined!"; break; case 2: $this->problem = "ERROR: ParameterMap item doesn't have property name defined!"; break; case 3: $this->problem = "ERROR: ParameterMap doesn't have any items!"; break; case 4: $this->problem = "ERROR: ParameterMap doesn't exists!"; break; case 6: $this->problem = "ERROR: ParameterMap class obj '".get_class($v67db1bd535[0])."' doesn't contain the '".$v67db1bd535[1]."' method!"; break; case 7: $this->problem = "ERROR: Query can only have ParameterMap if the input value is an array!"; break; case 8: $this->problem = "ERROR: ParameterMap column '".$v67db1bd535."' doesn't exist in the input data! Please check your parameter map xml."; break; } } } ?>
