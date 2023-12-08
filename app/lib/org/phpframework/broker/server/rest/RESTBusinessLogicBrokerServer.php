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
include_once get_lib("org.phpframework.broker.server.rest.RESTBrokerServer"); include_once get_lib("org.phpframework.broker.server.local.LocalBusinessLogicBrokerServer"); class RESTBusinessLogicBrokerServer extends RESTBrokerServer { protected function setLocalBrokerServer() { $this->LocalBrokerServer = new LocalBusinessLogicBrokerServer($this->Layer); } protected function executeWebServiceResponse() { $v9cd205cadb = explode("/", $this->url); if (strtolower($v9cd205cadb[0]) == "getbrokersdbdriversname") { $v9ad1385268 = $this->LocalBrokerServer->getBrokersDBdriversName(); return $this->getWebServiceResponse("getBrokersDBdriversName", null, $v9ad1385268, $this->response_type); } else { $v95eeadc9e9 = array_pop($v9cd205cadb); $pc8b88eb4 = implode("/", $v9cd205cadb); $v9ad1385268 = $this->LocalBrokerServer->callBusinessLogic($pc8b88eb4, $v95eeadc9e9, $this->parameters, $this->options); $pc0481df4 = array("module" => $pc8b88eb4, "service" => $v95eeadc9e9, "parameters" => $this->parameters, "options" => $this->options); return $this->getWebServiceResponse("callBusinessLogic", $pc0481df4, $v9ad1385268, $this->response_type); } } } ?>
