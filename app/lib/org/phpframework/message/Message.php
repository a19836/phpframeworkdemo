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
class Message { private $v1cbfbb49c5; private $pcd8c70bc; private $pffa799aa; private $pfdbbc383 = array(); public function __construct() { } public function setId($v1cbfbb49c5) {$this->v1cbfbb49c5 = $v1cbfbb49c5;} public function getId() {return $this->v1cbfbb49c5;} public function setModule($pcd8c70bc) {$this->pcd8c70bc = $pcd8c70bc;} public function getModule() {return $this->pcd8c70bc;} public function setMessage($pffa799aa) {$this->pffa799aa = $pffa799aa;} public function getMessage() {return $this->pffa799aa;} public function setAttributes($pfdbbc383) {$this->pfdbbc383 = $pfdbbc383;} public function getAttributes() {return $this->pfdbbc383;} public function getAttribute($v5e813b295b) {return isset($this->pfdbbc383[$v5e813b295b]) ? $this->pfdbbc383[$v5e813b295b] : null;} public function checkAttributes($pfdbbc383) { if(is_array($pfdbbc383)) { foreach($pfdbbc383 as $v5e813b295b => $v67db1bd535) { $v956913c90f = isset($this->pfdbbc383[$v5e813b295b]) ? $this->pfdbbc383[$v5e813b295b] : null; if( (!isset($v956913c90f) && strlen($v67db1bd535) > 0) || $v956913c90f != $v67db1bd535) return false; } } return true; } } ?>
