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
include_once get_lib("org.phpframework.sqlmap.exception.SQLMapResultException"); include_once get_lib("org.phpframework.object.ObjectHandler"); class SQLMapResultHandler extends SQLMap { public function __construct() { parent::__construct(); } public function configureSortOptions(&$v04003a4f53, $pce128343, $v8ce36c307f = false) { if (is_array($v04003a4f53)) { $pf84078e3 = $this->getResultMapOutputToInputAttributes($pce128343, $v8ce36c307f); if ($pf84078e3) { foreach ($v04003a4f53 as $v43dd7d0051 => $pdab26aff) { if (isset($pdab26aff["column"]) && !empty($pf84078e3[ $pdab26aff["column"] ])) { $v04003a4f53[$v43dd7d0051]["column"] = $pf84078e3[ $pdab26aff["column"] ]; } } } } } public function getResultMapOutputToInputAttributes($pce128343, $v8ce36c307f = false) { $pf6fbc9a7 = array(); if($pce128343) { if(!is_array($pce128343)) { if(isset($v8ce36c307f["result_map"][$pce128343])) { $pce128343 = $v8ce36c307f["result_map"][$pce128343]; } } if(is_array($pce128343)) { $pe2bfadf3 = isset($pce128343["result"]) ? $pce128343["result"] : false; foreach ($pe2bfadf3 as $v5a38e25d0d) { $v1c301b29c5 = isset($v5a38e25d0d["output_name"]) ? trim($v5a38e25d0d["output_name"]) : ""; $v8dca298c48 = isset($v5a38e25d0d["input_name"]) ? trim($v5a38e25d0d["input_name"]) : ""; if ($v1c301b29c5 && $v8dca298c48) { $pf6fbc9a7[$v1c301b29c5] = $v8dca298c48; } } } } return $pf6fbc9a7; } public function transformData(&$v539082ff30, $v21ff8db28c = false, $pce128343 = false, $v8ce36c307f = false) { $pe76f54d6 = isset($v539082ff30["fields"]) ? $v539082ff30["fields"] : array(); $v9cba300761 = isset($v539082ff30["result"]) ? $v539082ff30["result"] : array(); if(count($v9cba300761)) { if($v21ff8db28c) { $v539082ff30 = $this->f1e02d7fa23($v21ff8db28c, $pe76f54d6, $v9cba300761); } else if($pce128343) { if(!is_array($pce128343)) { if(isset($v8ce36c307f["result_map"][$pce128343])) { $pce128343 = $v8ce36c307f["result_map"][$pce128343]; } else { launch_exception(new SQLMapResultException(4)); } } $v539082ff30 = $this->mdd9187781d39($pce128343, $pe76f54d6, $v9cba300761); } else { $v539082ff30 = $v9cba300761; } } else { $v539082ff30 = $v9cba300761; } } private function f1e02d7fa23($v21ff8db28c, $pe76f54d6, $v9cba300761) { $v65a396e40d = array(); if($this->getErrorHandler()->ok() && $v9cba300761) { $pc37695cb = count($v9cba300761); for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v6c721d8855 = $v9cba300761[$v43dd7d0051]; $pff29897b = ObjectHandler::createInstance($v21ff8db28c); if(ObjectHandler::checkIfObjType($pff29897b)) { $pff29897b->setData($v6c721d8855); } $v65a396e40d[] = $pff29897b; } } return $v65a396e40d; } private function mdd9187781d39($pce128343, $pe76f54d6, $v9cba300761) { $v65a396e40d = array(); $v5c2c9448bd = isset($pce128343["attrib"]["class"]) ? $pce128343["attrib"]["class"] : false; $pe2bfadf3 = isset($pce128343["result"]) ? $pce128343["result"] : false; $v20f6605332 = isset($v9cba300761[0]) ? array_keys($v9cba300761[0]) : array(); if(!$pe2bfadf3 || !count($pe2bfadf3)) { launch_exception(new SQLMapResultException(3)); } else { $pc37695cb = $v9cba300761 ? count($v9cba300761) : 0; $pd28479e5 = count($pe2bfadf3); for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v6c721d8855 = $v9cba300761[$v43dd7d0051]; for($v9d27441e80 = 0; $v9d27441e80 < $pd28479e5; $v9d27441e80++) { $v5a38e25d0d = $pe2bfadf3[$v9d27441e80]; $v1c301b29c5 = isset($v5a38e25d0d["output_name"]) ? trim($v5a38e25d0d["output_name"]) : ""; if(strlen($v1c301b29c5) == 0) { launch_exception(new SQLMapResultException(2)); } $v8dca298c48 = isset($v5a38e25d0d["input_name"]) ? trim($v5a38e25d0d["input_name"]) : ""; if(strlen($v8dca298c48) == 0) { launch_exception(new SQLMapResultException(1)); } if(!isset($v6c721d8855[$v8dca298c48]) && !empty($v5a38e25d0d["mandatory"])) { launch_exception(new SQLMapResultException(6, $v8dca298c48)); } $v0a9be8a39a = $this->f096b4bf4c7($pe76f54d6, $v6c721d8855, $v20f6605332, $v5a38e25d0d); unset($v6c721d8855[$v8dca298c48]); $v6c721d8855[$v1c301b29c5] = $v0a9be8a39a; } if($v5c2c9448bd) { $v972f1a5c2b = ObjectHandler::createInstance($v5c2c9448bd); if(ObjectHandler::checkIfObjType($v972f1a5c2b)) { $v972f1a5c2b->setData($v6c721d8855); } $v65a396e40d[] = $v972f1a5c2b; } else { $v65a396e40d[] = $v6c721d8855; } } } return $v65a396e40d; } private function f096b4bf4c7($pe76f54d6, $v6c721d8855, $v20f6605332, $v5a38e25d0d) { $v8dca298c48 = isset($v5a38e25d0d["input_name"]) ? trim($v5a38e25d0d["input_name"]) : ""; $pb21e5126 = isset($v6c721d8855[$v8dca298c48]) ? $v6c721d8855[$v8dca298c48] : null; $v5a911d8233 = isset($v5a38e25d0d["input_type"]) ? $v5a38e25d0d["input_type"] : false; $v7d93eab82d = false; if($v5a911d8233) { $v5a911d8233 = ObjTypeHandler::convertSimpleTypeIntoCompositeType($v5a911d8233); $v7d93eab82d = ObjectHandler::createInstance($v5a911d8233); if(ObjectHandler::checkIfObjType($v7d93eab82d) && $this->getErrorHandler()->ok()) { $v28c1cd997a = true; $pe49114c9 = ObjectHandler::getClassName($v5a911d8233); if(!$v7d93eab82d->is_primitive && ( !ObjectHandler::checkObjClass($pb21e5126, $pe49114c9) || !$this->getErrorHandler()->ok() ) ) { $v28c1cd997a = false; } if($v28c1cd997a) { $v8a4df75785 = array_search($v8dca298c48, $v20f6605332); if(!is_numeric($v8a4df75785)) { launch_exception(new SQLMapResultException(5, array($v8dca298c48, $v20f6605332))); } $v7d93eab82d->setField( (is_numeric($v8a4df75785) && isset($pe76f54d6[$v8a4df75785]) ? $pe76f54d6[$v8a4df75785] : false) ); $v7d93eab82d->setInstance($pb21e5126); } } } $pd8947afe = $v7d93eab82d ? $v7d93eab82d->getData() : $pb21e5126; $v10353796a8 = isset($v5a38e25d0d["output_type"]) ? $v5a38e25d0d["output_type"] : false; $v5fbd97449e = false; if($v10353796a8 && $this->getErrorHandler()->ok()) { $v10353796a8 = ObjTypeHandler::convertSimpleTypeIntoCompositeType($v10353796a8); $v5fbd97449e = ObjectHandler::createInstance($v10353796a8); if(ObjectHandler::checkIfObjType($v5fbd97449e) && $this->getErrorHandler()->ok()) { $v5fbd97449e->setField($v7d93eab82d); $v5fbd97449e->setInstance($pd8947afe); } } $v67db1bd535 = false; if($this->getErrorHandler()->ok()) { if(!$v5fbd97449e && !is_numeric($v5fbd97449e)) $v67db1bd535 = $pd8947afe; elseif(isset($v5fbd97449e->is_primitive) && $v5fbd97449e->is_primitive) $v67db1bd535 = $v5fbd97449e->getData(); else $v67db1bd535 = $v5fbd97449e; } return $v67db1bd535; } } ?>
