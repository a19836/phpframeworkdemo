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
class ExceptionLogHandler { private $v21c0d09111; private $v85bf262ebb; public function __construct($v21c0d09111, $v85bf262ebb = false) { $this->v21c0d09111 = $v21c0d09111; $this->v85bf262ebb = $v85bf262ebb; } public function log(Exception $v4ace7728e6) { if($this->v21c0d09111) { $pffa799aa = $v4ace7728e6->getMessage(); $v9dd1efeb20 = $v4ace7728e6->problem; $v1db8fcc7cd = $pffa799aa != $v9dd1efeb20 ? "$pffa799aa\n$v9dd1efeb20" : $v9dd1efeb20; $this->v21c0d09111->setExceptionLog($v1db8fcc7cd, $v4ace7728e6->getTrace()); } if($this->v85bf262ebb) { echo "<p style=\"margin:10px; font-weight:bold; color:#2C2D34;\">DIE: Program execution ends on the ExceptionHandler class (" . date("Y-m-d H:i:s", time()) . ")</p>"; die(1); } } public function setLogHandler($v21c0d09111) {$this->v21c0d09111 = $v21c0d09111;} public function getLogHandler() {return $this->v21c0d09111;} public function setDieWhenThrowException($v85bf262ebb) {$this->v85bf262ebb = $v85bf262ebb;} public function getDieWhenThrowException() {return $this->v85bf262ebb;} } ?>
