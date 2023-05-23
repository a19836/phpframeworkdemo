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

include_once get_lib("org.phpframework.dispatcher.exception.EVCDispatcherException"); include_once get_lib("org.phpframework.dispatcher.Dispatcher"); class EVCDispatcher extends Dispatcher { private $v6f3a2700dd; private $v9431023a8c; private $v9367d5be85; private $v1651902203; private $v0e418ed793; private $v08d9602741; public function __construct() {} public function setEVC($v08d9602741) { $this->v08d9602741 = $v08d9602741; } public function getEVC() { return $this->v08d9602741; } public function setRouter($v0e418ed793) { $this->v0e418ed793 = $v0e418ed793; } public function getRouter() { return $this->v0e418ed793; } public function dispatch($v6f3a2700dd) { $this->v0e418ed793->load(); $this->v6f3a2700dd = $this->v0e418ed793->parse($v6f3a2700dd); $v04fae7df44 = explode("/", $this->v6f3a2700dd); while (count($v04fae7df44) > 0 && $v04fae7df44[ count($v04fae7df44) - 1] == "") array_pop($v04fae7df44); $this->v9431023a8c = $v04fae7df44[0]; if($this->v9431023a8c && $this->v08d9602741->controllerExists($this->v9431023a8c)) { $this->v1651902203 = $this->v08d9602741->getControllerPath($this->v9431023a8c); $this->v9367d5be85 = $v04fae7df44; array_shift($this->v9367d5be85); } else { $v8e5982c702 = $this->v08d9602741->getDefaultController(); if($v8e5982c702 && $this->v08d9602741->controllerExists($v8e5982c702)) { $this->v1651902203 = $this->v08d9602741->getControllerPath($v8e5982c702); $this->v9367d5be85 = $v04fae7df44; } else { launch_exception(new EVCDispatcherException(1, array($this->v9431023a8c, $v8e5982c702))); } } } public function getURL() { return $this->v6f3a2700dd;} public function getPageCode() { return $this->v9431023a8c;} public function getParameters() { return $this->v9367d5be85;} public function getRequestedFilePath() { return $this->v1651902203;} } ?>
