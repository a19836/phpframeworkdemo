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
include_once get_lib("org.phpframework.sqlmap.ibatis.IBatisClient"); include_once get_lib("org.phpframework.sqlmap.SQLMapIncludesHandler"); include_once get_lib("org.phpframework.sqlmap.hibernate.HibernateModelCache"); class HibernateModelBase extends SQLMap { protected $RDBBroker; protected $QueryHandler; protected $ResultHandler; protected $SQLClient; protected $CacheLayer; protected $HibernateModelCache; protected $obj_name; protected $table_name; protected $extend_class_name; protected $extend_class_path; protected $parameter_class; protected $parameter_map; protected $result_class; protected $result_map; protected $ids = array(); protected $table_attributes = array(); protected $many_to_one = array(); protected $many_to_many = array(); protected $one_to_many = array(); protected $one_to_one = array(); protected $queries = array(); protected $properties_to_attributes = array(); protected $module_id; protected $service_id; public function __construct() { parent::__construct(); $this->QueryHandler = new SQLMapQueryHandler(); $this->ResultHandler = new SQLMapResultHandler(); $this->SQLClient = new IBatisClient(); $this->HibernateModelCache = new HibernateModelCache(); } protected function prepareIdsToInsert(&$v539082ff30, $v5d3813882f = false) { foreach($this->ids as $pbfa01ed1 => $v67db1bd535) { $v5e45ec9bb9 = isset($v67db1bd535["output_name"]) ? $v67db1bd535["output_name"] : null; $v3fb9f41470 = isset($v67db1bd535["generator"]["type"]) ? $v67db1bd535["generator"]["type"] : ""; $v1487b7a0dd = $v539082ff30[$v5e45ec9bb9]; if($v3fb9f41470 == "hidden") { if (array_key_exists($v5e45ec9bb9, $v539082ff30)) unset($v539082ff30[$v5e45ec9bb9]); } else if($v3fb9f41470) { $v1cbfbb49c5 = false; switch($v3fb9f41470) { case "assign": $v1cbfbb49c5 = $v1487b7a0dd; break; case "increment": case "select": case "procedure": if ($v3fb9f41470 == "increment") $v1cbfbb49c5 = $this->RDBBroker->findObjectsColumnMax($this->table_name, $v5e45ec9bb9) + 1; else { $v3c76382d93 = isset($v67db1bd535["generator"]["value"]) ? $v67db1bd535["generator"]["value"] : null; if ($v3c76382d93) { $v9ad1385268 = $this->RDBBroker->getData($v3c76382d93, $v5d3813882f); $v9ad1385268 = isset($v9ad1385268["result"][0]) ? $v9ad1385268["result"][0] : null; if (is_array($v9ad1385268)) { $v9994512d98 = array_keys($v9ad1385268); $v1cbfbb49c5 = count($v9994512d98) > 0 ? $v9ad1385268[ $v9994512d98[0] ] : null; } } } break; case "md5": $v1cbfbb49c5 = md5(microtime(true)); break; } $v539082ff30[$v5e45ec9bb9] = $v1cbfbb49c5; } else if (!empty($this->table_attributes[$v5e45ec9bb9]) && !empty($this->table_attributes[$v5e45ec9bb9]["primary_key"]) && !empty($this->table_attributes[$v5e45ec9bb9]["auto_increment"]) && ( !isset($v539082ff30[$v5e45ec9bb9]) || !strlen("" . $v539082ff30[$v5e45ec9bb9]) ) ) { if (array_key_exists($v5e45ec9bb9, $v539082ff30)) unset($v539082ff30[$v5e45ec9bb9]); } else $v539082ff30[$v5e45ec9bb9] = $v1487b7a0dd; } } public function setNodesData($pcdca0571) { $this->SQLClient->setNodesData($pcdca0571); } public function setRDBBroker($pdb735a1c) { $this->RDBBroker = $pdb735a1c; $this->SQLClient->setRDBBroker($pdb735a1c); } public function getRDBBroker() {return $this->RDBBroker;} public function setCacheLayer($pbc7e2f66) { $this->CacheLayer = $pbc7e2f66; $this->HibernateModelCache->initCacheDirPath($this->CacheLayer->getCachedDirPath()); } public function getCacheLayer() {return $this->CacheLayer;} public function setObjName($v55c0c0e582) {$this->obj_name = $v55c0c0e582;} public function getObjName() {return $this->obj_name;} public function setTableName($v8c5df8072b) {$this->table_name = $v8c5df8072b;} public function getTableName() {return $this->table_name;} public function setExtendClassName($pbbf7c473) {$this->extend_class_name = $pbbf7c473;} public function getExtendClassName() {return $this->extend_class_name;} public function setExtendClassPath($v77f1de838b) {$this->extend_class_path = $v77f1de838b;} public function getExtendClassPath() {return $this->extend_class_path;} public function setIds($v32f28291a1) {$this->ids = $v32f28291a1;} public function getIds() {return $this->ids;} public function setParameterClass($v217e7cf3c0) {$this->parameter_class = $v217e7cf3c0;} public function getParameterClass() {return $this->parameter_class;} public function setParameterMap($v2967293505) {$this->parameter_map = $v2967293505;} public function getParameterMap() {return $this->parameter_map;} public function setResultClass($v21ff8db28c) {$this->result_class = $v21ff8db28c;} public function getResultClass() {return $this->result_class;} public function setResultMap($pce128343) {$this->result_map = $pce128343;} public function getResultMap() {return $this->result_map;} public function setTableAttributes($pfaffed55) {$this->table_attributes = $pfaffed55;} public function getTableAttributes() {return $this->table_attributes;} public function setManyToOne($peb011cd3) {$this->many_to_one = $peb011cd3;} public function getManyToOne() {return $this->many_to_one;} public function setManyToMany($pf40bba44) {$this->many_to_many = $pf40bba44;} public function getManyToMany() {return $this->many_to_many;} public function setOneToMany($pdb5afb26) {$this->one_to_many = $pdb5afb26;} public function getOneToMany() {return $this->one_to_many;} public function setOneToOne($v338b04e388) {$this->one_to_one = $v338b04e388;} public function getOneToOne() {return $this->one_to_one;} public function setQueries($v1612a5ddce) {$this->queries = $v1612a5ddce;} public function getQueries() {return $this->queries;} public function setPropertiesToAttributes($v07a4643edf) {$this->properties_to_attributes = $v07a4643edf;} public function getPropertiesToAttributes() {return $this->properties_to_attributes;} public function setModuleId($pcd8c70bc) {$this->module_id = $pcd8c70bc;} public function getModuleId() {return $this->module_id;} public function setServiceId($v20b8676a9f) {$this->service_id = $v20b8676a9f;} public function getServiceId() {return $this->service_id;} } ?>
