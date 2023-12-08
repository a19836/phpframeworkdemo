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
interface IDB { public static function getType(); public static function getLabel(); public static function getEnclosingDelimiters(); public static function getAliasEnclosingDelimiters(); public static function getDBCharsets(); public static function getTableCharsets(); public static function getColumnCharsets(); public static function getDBCollations(); public static function getTableCollations(); public static function getColumnCollations(); public static function getStorageEngines(); public static function getPHPToDBColumnTypes(); public static function getDBToPHPColumnTypes(); public static function getDBColumnTypes(); public static function getDBColumnSimpleTypes(); public static function getDBColumnDefaultValuesByType(); public static function getDBColumnTypesIgnoredProps(); public static function getDBColumnTypesHiddenProps(); public static function getDBColumnNumericTypes(); public static function getDBColumnDateTypes(); public static function getDBColumnTextTypes(); public static function getDBColumnBlobTypes(); public static function getDBColumnBooleanTypes(); public static function getDBColumnMandatoryLengthTypes(); public static function getDBColumnAutoIncrementTypes(); public static function getDBBooleanTypeAvailableValues(); public static function getDBCurrentTimestampAvailableValues(); public static function getReservedWords(); public static function getDefaultSchema(); public static function getIgnoreConnectionOptions(); public static function getIgnoreConnectionOptionsByExtension(); public static function getAvailablePHPExtensionTypes(); public static function allowTableAttributeSorting(); public function parseDSN($v56c929bbd8); public function getDSN($v5d3813882f = null); public function getVersion(); public function connect(); public function connectWithoutDB(); public function disconnect(); public function close(); public function setCharset($pbebc8cef = 'utf8'); public function selectDB($pb67a2609); public function error(); public function errno(); public function execute(&$v3c76382d93, $v5d3813882f = false); public function query(&$v3c76382d93, $v5d3813882f = false); public function freeResult($v9ad1385268); public function numRows($v9ad1385268); public function numFields($v9ad1385268); public function fetchArray($v9ad1385268, $v8966764c3b = false); public function fetchRow($v9ad1385268); public function fetchAssoc($v9ad1385268); public function fetchObject($v9ad1385268); public function fetchField($v9ad1385268, $pac65f06f); public function isResultValid($v9ad1385268); public function createDB($pb67a2609, $v5d3813882f = false); public function getSelectedDB($v5d3813882f = false); public function listDBs($v5d3813882f = false, $pf49225a7 = "name"); public function listTables($pb67a2609 = false, $v5d3813882f = false); public function listTableFields($pc661dc6b, $v5d3813882f = false); public function listForeignKeys($pc661dc6b, $v5d3813882f = false); public function getInsertedId($v5d3813882f = false); public function convertObjectToSQL($v539082ff30, $v5d3813882f = false); public function convertSQLToObject($v3c76382d93, $v5d3813882f = false); public function buildTableInsertSQL($v8c5df8072b, $pfdbbc383, $v5d3813882f = false); public function buildTableUpdateSQL($v8c5df8072b, $pfdbbc383, $paf1bc6f6 = false, $v5d3813882f = false); public function buildTableDeleteSQL($v8c5df8072b, $paf1bc6f6 = false, $v5d3813882f = false); public function buildTableFindSQL($v8c5df8072b, $pfdbbc383 = false, $paf1bc6f6 = false, $v5d3813882f = false); public function buildTableCountSQL($v8c5df8072b, $paf1bc6f6 = false, $v5d3813882f = false); public function buildTableFindRelationshipSQL($v8c5df8072b, $v10c59e20bd, $v4ec0135323 = false, $v5d3813882f = false); public function buildTableCountRelationshipSQL($v8c5df8072b, $v10c59e20bd, $v4ec0135323 = false, $v5d3813882f = false); public function buildTableFindColumnMaxSQL($v8c5df8072b, $v7162e23723, $v5d3813882f = false); public static function getCreateDBStatement($pb67a2609, $v5d3813882f = false); public static function getDropDatabaseStatement($pb67a2609, $v5d3813882f = false); public static function getSelectedDBStatement($v5d3813882f = false); public static function getDBsStatement($v5d3813882f = false); public static function getTablesStatement($pb67a2609 = false, $v5d3813882f = false); public static function getTableFieldsStatement($pc661dc6b, $pb67a2609 = false, $v5d3813882f = false); public static function getForeignKeysStatement($pc661dc6b, $pb67a2609 = false, $v5d3813882f = false); public static function getCreateTableStatement($v87a92bb1ad, $v5d3813882f = false); public static function getCreateTableAttributeStatement($v261e7b366d, $v5d3813882f = false, &$v808a08b1f9 = array()); public static function getRenameTableStatement($pe8f357f7, $v38abe7147f, $v5d3813882f = false); public static function getDropTableStatement($pc661dc6b, $v5d3813882f = false); public static function getDropTableCascadeStatement($pc661dc6b, $v5d3813882f = false); public static function getAddTableAttributeStatement($pc661dc6b, $v261e7b366d, $v5d3813882f = false); public static function getModifyTableAttributeStatement($pc661dc6b, $v261e7b366d, $v5d3813882f = false); public static function getRenameTableAttributeStatement($pc661dc6b, $pfc66218f, $paa23699f, $v5d3813882f = false); public static function getDropTableAttributeStatement($pc661dc6b, $v97915b9670, $v5d3813882f = false); public static function getAddTablePrimaryKeysStatement($pc661dc6b, $pfdbbc383, $v5d3813882f = false); public static function getDropTablePrimaryKeysStatement($pc661dc6b, $v5d3813882f = false); public static function getAddTableForeignKeyStatement($pc661dc6b, $pa7c14731, $v5d3813882f = false); public static function getDropTableForeignKeysStatement($pc661dc6b, $v5d3813882f = false); public static function getDropTableForeignConstraintStatement($pc661dc6b, $pa28639ac, $v5d3813882f = false); public static function getAddTableIndexStatement($pc661dc6b, $pfdbbc383, $v5d3813882f = false); public static function getLoadTableDataFromFileStatement($pf3dc0762, $pc661dc6b, $v5d3813882f = false); public static function getShowCreateTableStatement($pc661dc6b, $v5d3813882f = false); public static function getShowCreateViewStatement($pa36e00ea, $v5d3813882f = false); public static function getShowCreateTriggerStatement($v5ed3bce1d1, $v5d3813882f = false); public static function getShowCreateProcedureStatement($pbda8f16d, $v5d3813882f = false); public static function getShowCreateFunctionStatement($v2f4e66e00a, $v5d3813882f = false); public static function getShowCreateEventStatement($v76392c9cad, $v5d3813882f = false); public static function getShowTablesStatement($pb67a2609, $v5d3813882f = false); public static function getShowViewsStatement($pb67a2609, $v5d3813882f = false); public static function getShowTriggersStatement($pb67a2609, $v5d3813882f = false); public static function getShowTableColumnsStatement($pc661dc6b, $pb67a2609 = false, $v5d3813882f = false); public static function getShowForeignKeysStatement($pc661dc6b, $pb67a2609 = false, $v5d3813882f = false); public static function getShowProceduresStatement($pb67a2609, $v5d3813882f = false); public static function getShowFunctionsStatement($pb67a2609, $v5d3813882f = false); public static function getShowEventsStatement($pb67a2609, $v5d3813882f = false); public static function getSetupTransactionStatement($v5d3813882f = false); public static function getStartTransactionStatement($v5d3813882f = false); public static function getCommitTransactionStatement($v5d3813882f = false); public static function getStartDisableAutocommitStatement($v5d3813882f = false); public static function getEndDisableAutocommitStatement($v5d3813882f = false); public static function getStartLockTableWriteStatement($pc661dc6b, $v5d3813882f = false); public static function getStartLockTableReadStatement($pc661dc6b, $v5d3813882f = false); public static function getEndLockTableStatement($v5d3813882f = false); public static function getStartDisableKeysStatement($pc661dc6b, $v5d3813882f = false); public static function getEndDisableKeysStatement($pc661dc6b, $v5d3813882f = false); public static function getDropTriggerStatement($v5ed3bce1d1, $v5d3813882f = false); public static function getDropProcedureStatement($pbda8f16d, $v5d3813882f = false); public static function getDropFunctionStatement($v2f4e66e00a, $v5d3813882f = false); public static function getDropEventStatement($v76392c9cad, $v5d3813882f = false); public static function getDropViewStatement($pa36e00ea, $v5d3813882f = false); public static function convertObjectToDefaultSQL($v539082ff30, $v5d3813882f = false); public static function convertDefaultSQLToObject($v3c76382d93, $v5d3813882f = false); public static function buildDefaultTableInsertSQL($v8c5df8072b, $pfdbbc383, $v5d3813882f = false); public static function buildDefaultTableUpdateSQL($v8c5df8072b, $pfdbbc383, $paf1bc6f6 = false, $v5d3813882f = false); public static function buildDefaultTableDeleteSQL($v8c5df8072b, $paf1bc6f6 = false, $v5d3813882f = false); public static function buildDefaultTableFindSQL($v8c5df8072b, $pfdbbc383 = false, $paf1bc6f6 = false, $v5d3813882f = false); public static function buildDefaultTableCountSQL($v8c5df8072b, $paf1bc6f6 = false, $v5d3813882f = false); public static function buildDefaultTableFindRelationshipSQL($v8c5df8072b, $v10c59e20bd, $v4ec0135323 = false, $v5d3813882f = false); public static function buildDefaultTableCountRelationshipSQL($v8c5df8072b, $v10c59e20bd, $v4ec0135323 = false, $v5d3813882f = false); public static function buildDefaultTableFindColumnMaxSQL($v8c5df8072b, $v7162e23723, $v5d3813882f = false); public static function getDriverClassNameByPath($pc427838f); public static function getDriverTypeByClassName($v7d3018e7db); public static function getDriverTypeByPath($pc427838f); public static function getAvailableDriverClassNames(); public static function getDriverClassNameByType($v3fb9f41470); public static function getDriverPathByType($v3fb9f41470); public static function createDriverByType($v3fb9f41470); public static function convertDSNToOptions($v56c929bbd8); public static function getDSNByType($v3fb9f41470, $v5d3813882f); public static function getAllDriverLabelsByType(); public static function getAllDBCharsetsByType(); public static function getAllStorageEnginesByType(); public static function getAllExtensionsByType(); public static function getAllIgnoreConnectionOptionsByType(); public static function getAllIgnoreConnectionOptionsByExtensionAndType(); public static function getAllColumnTypesByType(); public static function getAllColumnTypes(); public static function getAllSharedColumnTypes(); public static function getAllColumnSimpleTypesByType(); public static function getAllColumnSimpleTypes(); public static function getAllSharedColumnSimpleTypes(); public static function getAllColumnNumericTypesByType(); public static function getAllColumnNumericTypes(); public static function getAllSharedColumnNumericTypes(); public static function getAllColumnDateTypesByType(); public static function getAllColumnDateTypes(); public static function getAllSharedColumnDateTypes(); public static function getAllColumnTextTypesByType(); public static function getAllColumnTextTypes(); public static function getAllSharedColumnTextTypes(); public static function getAllColumnBlobTypesByType(); public static function getAllColumnBlobTypes(); public static function getAllSharedColumnBlobTypes(); public static function getAllColumnBooleanTypesByType(); public static function getAllColumnBooleanTypes(); public static function getAllSharedColumnBooleanTypes(); public static function getAllColumnMandatoryLengthTypesByType(); public static function getAllColumnMandatoryLengthTypes(); public static function getAllSharedColumnMandatoryLengthTypes(); public static function getAllColumnAutoIncrementTypesByType(); public static function getAllColumnAutoIncrementTypes(); public static function getAllSharedColumnAutoIncrementTypes(); public static function getAllBooleanTypeAvailableValuesByType(); public static function getAllBooleanTypeAvailableValues(); public static function getAllSharedBooleanTypeAvailableValues(); public static function getAllCurrentTimestampAvailableValuesByType(); public static function getAllCurrentTimestampAvailableValues(); public static function getAllSharedCurrentTimestampAvailableValues(); public static function getAllColumnTypesIgnoredPropsByType(); public static function getAllColumnTypesIgnoredProps(); public static function getAllSharedColumnTypesIgnoredProps(); public static function getAllColumnTypesHiddenPropsByType(); public static function getAllColumnTypesHiddenProps(); public static function getAllSharedColumnTypesHiddenProps(); public static function getAllReservedWordsByType(); public static function getAllReservedWords(); public static function getAllSharedReservedWords(); public static function splitSQL($v3c76382d93, $v5d3813882f = false); public static function removeSQLComments($v3c76382d93, $v5d3813882f = false); public static function removeSQLRepeatedDelimiters($v3c76382d93, $v5d3813882f = false); public static function replaceSQLEnclosingDelimiter($v3c76382d93, $pce8f3827, $pdbe8f497); public static function isTheSameStaticTableName($v6f92ade39f, $v0f014e538c, $v5d3813882f = false); public static function isStaticTableInNamesList($pd73cd249, $pdbef745e, $v5d3813882f = false); public static function getStaticTableInNamesList($pd73cd249, $pdbef745e, $v5d3813882f = false); public function isConnected(); public function isDBSelected(); public function getConnectionLink(); public function getConnectionPHPExtensionType(); public function getOptions(); public function getOption($pe1d32923); public function setOptions($v5d3813882f); public function getFunction($v9d33ecaf56, $v9367d5be85 = false, $v5d3813882f = false); public function getData($v3c76382d93, $v5d3813882f = false); public function setData($v3c76382d93, $v5d3813882f = false); public function getSQL($v3c76382d93, $v5d3813882f = false); public function setSQL($v3c76382d93, $v5d3813882f = false); public function isTheSameTableName($v6f92ade39f, $v0f014e538c); public function isTableInNamesList($v6bf1a193b2, $pdbef745e); public function getTableInNamesList($v6bf1a193b2, $pdbef745e); public function insertObject($v8c5df8072b, $pfdbbc383, $v5d3813882f = false); public function updateObject($v8c5df8072b, $pfdbbc383, $paf1bc6f6 = false, $v5d3813882f = false); public function deleteObject($v8c5df8072b, $paf1bc6f6 = false, $v5d3813882f = false); public function findObjects($v8c5df8072b, $pfdbbc383 = false, $paf1bc6f6 = false, $v5d3813882f = false); public function countObjects($v8c5df8072b, $paf1bc6f6 = false, $v5d3813882f = false); public function findRelationshipObjects($v8c5df8072b, $v10c59e20bd, $v4ec0135323 = false, $v5d3813882f = false); public function countRelationshipObjects($v8c5df8072b, $v10c59e20bd, $v4ec0135323 = false, $v5d3813882f = false); public function findObjectsColumnMax($v8c5df8072b, $v7162e23723, $v5d3813882f = false); } ?>
