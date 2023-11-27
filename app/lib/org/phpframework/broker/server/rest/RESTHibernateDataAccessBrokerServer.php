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

include_once get_lib("org.phpframework.broker.server.rest.RESTBrokerServer"); include_once get_lib("org.phpframework.broker.server.local.LocalHibernateDataAccessBrokerServer"); class RESTHibernateDataAccessBrokerServer extends RESTBrokerServer { protected function setLocalBrokerServer() { $this->LocalBrokerServer = new LocalHibernateDataAccessBrokerServer($this->Layer); } protected function executeWebServiceResponse() { $v9cd205cadb = explode("/", $this->url); $v1858bcdeb6 = $v9cd205cadb; $v2f4e66e00a = array_pop($v9cd205cadb); $v95eeadc9e9 = array_pop($v9cd205cadb); $pc8b88eb4 = implode("/", $v9cd205cadb); if (strtolower($v2f4e66e00a) == "callobject") { $v972f1a5c2b = $this->LocalBrokerServer->callObject($pc8b88eb4, $v95eeadc9e9, $this->options); return $this->getWebServiceResponse("callObject", array("module" => $pc8b88eb4, "service" => $v95eeadc9e9, "options" => $v5d3813882f), $v972f1a5c2b, $this->response_type); } else { $v41abe84754 = isset($v1858bcdeb6[0]) ? $v1858bcdeb6[0] : null; $v7a23f99a77 = isset($v1858bcdeb6[1]) ? $v1858bcdeb6[1] : null; switch(strtolower($v41abe84754)) { case "getbrokersdbdriversname": $pa051dc1c = "getDBDriversName"; $pc0481df4 = array(); $v9ad1385268 = $this->LocalBrokerServer->getBrokersDBDriversName(); break; case "getfunction": $pa051dc1c = "getFunction"; $pc0481df4 = array("func_name" => $v7a23f99a77, "parameters" => $this->parameters, "options" => $this->options); $v9ad1385268 = $this->LocalBrokerServer->getFunction($v7a23f99a77, $this->parameters, $this->options); break; case "getdata": $pa051dc1c = "getData"; $pc0481df4 = array("parameters" => $this->parameters, "options" => $this->options); $v9ad1385268 = $this->LocalBrokerServer->getData($this->parameters, $this->options); break; case "setdata": $pa051dc1c = "setData"; $pc0481df4 = array("parameters" => $this->parameters, "options" => $this->options); $v9ad1385268 = $this->LocalBrokerServer->setData($this->parameters, $this->options); if ($v9ad1385268 && strtolower($v7a23f99a77) == "getinsertedid") $v9ad1385268 = $this->LocalBrokerServer->getInsertedId($this->options); break; case "getsql": $pa051dc1c = "getSQL"; $pc0481df4 = array("parameters" => $this->parameters, "options" => $this->options); $v9ad1385268 = $this->LocalBrokerServer->getSQL($this->parameters, $this->options); break; case "setsql": $pa051dc1c = "setSQL"; $pc0481df4 = array("parameters" => $this->parameters, "options" => $this->options); $v9ad1385268 = $this->LocalBrokerServer->setSQL($this->parameters, $this->options); if ($v9ad1385268 && strtolower($v7a23f99a77) == "getinsertedid") $v9ad1385268 = $this->LocalBrokerServer->getInsertedId($this->options); break; case "getinsertedid": $pa051dc1c = "getInsertedId"; $pc0481df4 = array("options" => $this->options); $v9ad1385268 = $this->LocalBrokerServer->getInsertedId($this->options); break; case "insertobject": $pa051dc1c = "insertObject"; $pc0481df4 = array("parameters" => $this->parameters, "options" => $v5d3813882f); $v9ad1385268 = $this->LocalBrokerServer->insertObject( isset($this->parameters["table_name"]) ? $this->parameters["table_name"] : null, isset($this->parameters["attributes"]) ? $this->parameters["attributes"] : null, $this->options ); break; case "updateobject": $pa051dc1c = "updateObject"; $pc0481df4 = array("parameters" => $this->parameters, "options" => $v5d3813882f); $v9ad1385268 = $this->LocalBrokerServer->updateObject( isset($this->parameters["table_name"]) ? $this->parameters["table_name"] : null, isset($this->parameters["attributes"]) ? $this->parameters["attributes"] : null, isset($this->parameters["conditions"]) ? $this->parameters["conditions"] : null, $this->options ); break; case "deleteobject": $pa051dc1c = "deleteObject"; $pc0481df4 = array("parameters" => $this->parameters, "options" => $v5d3813882f); $v9ad1385268 = $this->LocalBrokerServer->deleteObject( isset($this->parameters["table_name"]) ? $this->parameters["table_name"] : null, isset($this->parameters["conditions"]) ? $this->parameters["conditions"] : null, $this->options ); break; case "findobjects": $pa051dc1c = "findObjects"; $pc0481df4 = array("parameters" => $this->parameters, "options" => $v5d3813882f); $v9ad1385268 = $this->LocalBrokerServer->findObjects( isset($this->parameters["table_name"]) ? $this->parameters["table_name"] : null, isset($this->parameters["attributes"]) ? $this->parameters["attributes"] : null, isset($this->parameters["conditions"]) ? $this->parameters["conditions"] : null, $this->options ); break; case "countobjects": $pa051dc1c = "countObjects"; $pc0481df4 = array("parameters" => $this->parameters, "options" => $v5d3813882f); $v9ad1385268 = $this->LocalBrokerServer->countObjects( isset($this->parameters["table_name"]) ? $this->parameters["table_name"] : null, isset($this->parameters["conditions"]) ? $this->parameters["conditions"] : null, $this->options ); break; case "findrelationshipobjects": $pa051dc1c = "findRelationshipObjects"; $pc0481df4 = array("parameters" => $this->parameters, "options" => $v5d3813882f); $v9ad1385268 = $this->LocalBrokerServer->findRelationshipObjects( isset($this->parameters["table_name"]) ? $this->parameters["table_name"] : null, isset($this->parameters["rel_elm"]) ? $this->parameters["rel_elm"] : null, isset($this->parameters["parent_conditions"]) ? $this->parameters["parent_conditions"] : null, $this->options ); break; case "countrelationshipobjects": $pa051dc1c = "countRelationshipObjects"; $pc0481df4 = array("parameters" => $this->parameters, "options" => $v5d3813882f); $v9ad1385268 = $this->LocalBrokerServer->countRelationshipObjects( isset($this->parameters["table_name"]) ? $this->parameters["table_name"] : null, isset($this->parameters["rel_elm"]) ? $this->parameters["rel_elm"] : null, isset($this->parameters["parent_conditions"]) ? $this->parameters["parent_conditions"] : null, $this->options ); break; case "findObjectsColumnMax": $pa051dc1c = "findObjectsColumnMax"; $pc0481df4 = array("parameters" => $this->parameters, "options" => $v5d3813882f); $v9ad1385268 = $this->LocalBrokerServer->findObjectsColumnMax( isset($this->parameters["table_name"]) ? $this->parameters["table_name"] : null, isset($this->parameters["attribute_name"]) ? $this->parameters["attribute_name"] : null, $this->options ); break; default: $pdccf64a6 = $_GET["args"]; $pa051dc1c = "callObjectMethod"; $pc0481df4 = array("module" => $pc8b88eb4, "service" => $v95eeadc9e9, "options" => $v5d3813882f, "function" => $v2f4e66e00a, "func_args" => array("arguments" => $pdccf64a6, "parameters" => $this->parameters, "options" => $this->options)); $v86066462c3 = count($pdccf64a6) ? "'".(implode("','", $pdccf64a6))."', \$this->parameters,\$this->options" : "\$this->parameters,\$this->options"; $v972f1a5c2b = $this->LocalBrokerServer->callObject($pc8b88eb4, $v95eeadc9e9, $this->options); eval("\$result = \$obj->{$v2f4e66e00a}({$v86066462c3});"); } return $this->getWebServiceResponse($pa051dc1c, $pc0481df4, $v9ad1385268, $this->response_type); } } } ?>
