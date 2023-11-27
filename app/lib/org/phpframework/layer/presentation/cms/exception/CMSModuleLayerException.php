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

class CMSModuleLayerException extends Exception { public $problem; public $file_not_found = false; public function __construct($v6de691233b, $v67db1bd535 = "") { $v9363d877fd = $pd0c2934c = null; if (is_array($v67db1bd535)) { $v9363d877fd = isset($v67db1bd535[0]) ? $v67db1bd535[0] : null; $pd0c2934c = isset($v67db1bd535[1]) ? $v67db1bd535[1] : null; } switch($v6de691233b) { case 1: $this->problem = "Modules Path is undefined or doesn't exist: $v67db1bd535"; break; case 2: $this->problem = "CMSModuleHandlerImpl class is not a subclass of CMSModuleHandler in the file: $v67db1bd535"; break; case 3: $this->problem = "Couldn't create CMSModuleHandler obj for module: $v67db1bd535"; break; case 4: $this->problem = "Module File doesn't exist: $v67db1bd535"; $this->file_not_found = true; break; case 5: $this->problem = "Module '$v9363d877fd' doesn't exist or is disabled. Undefined file path: $pd0c2934c"; break; case 6: $this->problem = "$v67db1bd535 file doesn't exist!"; $this->file_not_found = true; break; case 7: $this->problem = "CMSModuleSimulatorHandlerImpl class is not a subclass of CMSModuleSimulatorHandler in the file: $v67db1bd535"; break; case 8: $this->problem = "Couldn't create CMSModuleSimulatorHandler obj for module: $v67db1bd535"; break; } } } ?>
