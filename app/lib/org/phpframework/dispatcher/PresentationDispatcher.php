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
include_once get_lib("org.phpframework.dispatcher.exception.PresentationDispatcherException"); include_once get_lib("org.phpframework.dispatcher.Dispatcher"); class PresentationDispatcher extends Dispatcher { private $v6f3a2700dd; private $v9431023a8c; private $v9367d5be85; private $v1651902203; private $v0e418ed793; private $pd3623f40; public function __construct() {} public function setPresentationLayer($pd3623f40) { $this->pd3623f40 = $pd3623f40; } public function getPresentationLayer() { return $this->pd3623f40; } public function setRouter($v0e418ed793) { $this->v0e418ed793 = $v0e418ed793; } public function getRouter() { return $this->v0e418ed793; } public function dispatch($v6f3a2700dd) { $this->v0e418ed793->load(); $this->v6f3a2700dd = $this->v0e418ed793->parse($v6f3a2700dd); $v04fae7df44 = explode("/", $this->v6f3a2700dd); while ($v04fae7df44[ count($v04fae7df44) - 1] == "") array_pop($v04fae7df44); $this->v9431023a8c = $v04fae7df44[0]; $this->v9367d5be85 = $v04fae7df44; array_shift($this->v9367d5be85); $this->v1651902203 = $this->pd3623f40->getPagePath($this->v9431023a8c); if(!file_exists($this->v1651902203)) { launch_exception(new PresentationDispatcherException(1, $this->v1651902203)); } } public function getURL() { return $this->v6f3a2700dd;} public function getPageCode() { return $this->v9431023a8c;} public function getParameters() { return $this->v9367d5be85;} public function getRequestedFilePath() { return $this->v1651902203;} } ?>
