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

include_once get_lib("org.phpframework.broker.client.local.LocalBrokerClient"); include_once get_lib("org.phpframework.broker.client.IDataAccessBrokerClient"); class LocalDataAccessBrokerClient extends LocalBrokerClient implements IDataAccessBrokerClient { public function getBrokersDBDriversName() { return $this->getBrokerServer()->getBrokersDBDriversName(); } public function getFunction($v9d33ecaf56, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->getFunction($v9d33ecaf56, $v9367d5be85, $v5d3813882f); } public function getData($v3c76382d93, $v5d3813882f = false) { return $this->getBrokerServer()->getData($v3c76382d93, $v5d3813882f); } public function setData($v3c76382d93, $v5d3813882f = false) { return $this->getBrokerServer()->setData($v3c76382d93, $v5d3813882f); } public function getSQL($v3c76382d93, $v5d3813882f = false) { return $this->getBrokerServer()->getSQL($v3c76382d93, $v5d3813882f); } public function setSQL($v3c76382d93, $v5d3813882f = false) { return $this->getBrokerServer()->setSQL($v3c76382d93, $v5d3813882f); } public function getInsertedId($v5d3813882f = false) { return $this->getBrokerServer()->getInsertedId($v5d3813882f); } public function insertObject($v8c5df8072b, $pfdbbc383, $v5d3813882f = false) { return $this->getBrokerServer()->insertObject($v8c5df8072b, $pfdbbc383, $v5d3813882f); } public function updateObject($v8c5df8072b, $pfdbbc383, $paf1bc6f6 = false, $v5d3813882f = false) { return $this->getBrokerServer()->updateObject($v8c5df8072b, $pfdbbc383, $paf1bc6f6, $v5d3813882f); } public function deleteObject($v8c5df8072b, $paf1bc6f6 = false, $v5d3813882f = false) { return $this->getBrokerServer()->deleteObject($v8c5df8072b, $paf1bc6f6, $v5d3813882f); } public function findObjects($v8c5df8072b, $pfdbbc383 = false, $paf1bc6f6 = false, $v5d3813882f = false) { return $this->getBrokerServer()->findObjects($v8c5df8072b, $pfdbbc383, $paf1bc6f6, $v5d3813882f); } public function countObjects($v8c5df8072b, $paf1bc6f6 = false, $v5d3813882f = false) { return $this->getBrokerServer()->countObjects($v8c5df8072b, $paf1bc6f6, $v5d3813882f); } public function findRelationshipObjects($v8c5df8072b, $v10c59e20bd, $v4ec0135323 = false, $v5d3813882f = false) { return $this->getBrokerServer()->findRelationshipObjects($v8c5df8072b, $v10c59e20bd, $v4ec0135323, $v5d3813882f); } public function countRelationshipObjects($v8c5df8072b, $v10c59e20bd, $v4ec0135323 = false, $v5d3813882f = false) { return $this->getBrokerServer()->countRelationshipObjects($v8c5df8072b, $v10c59e20bd, $v4ec0135323, $v5d3813882f); } public function findObjectsColumnMax($v8c5df8072b, $v7162e23723, $v5d3813882f = false) { return $this->getBrokerServer()->findObjectsColumnMax($v8c5df8072b, $v7162e23723, $v5d3813882f); } } ?>
