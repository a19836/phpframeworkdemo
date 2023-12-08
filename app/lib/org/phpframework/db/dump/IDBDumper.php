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
interface IDBDumper { public function databases($pb67a2609); public function createTable($pba23d78c, $v8c5df8072b, $v11f9d89738 = false); public function createRecordsInsertStmt($v8c5df8072b, $v3dd67d635b); public function getSqlStmtWithLimit($v3c76382d93, $v552b831ecd); public function createStandInTableForView($v03205f9bb8, $v6bc5012fff); public function getTableAttributeProperties($v670a5790dd); public function getTableAttributesPropertiesBitHexFunc($v5e45ec9bb9); public function getTableAttributesPropertiesBlobHexFunc($v5e45ec9bb9); public function createView($pff59654a); public function createTrigger($pff59654a); public function createProcedure($pff59654a); public function createFunction($pff59654a); public function createEvent($pff59654a); public function backupParameters(); public function restoreParameters(); public function startDisableConstraintsAndTriggersStmt($pac4bc40a); public function endDisableConstraintsAndTriggersStmt($pac4bc40a); public function setDBDumperHandler($v834e515e94); public static function create($v74128956c0, $v834e515e94); public function escapeTableAttributeAlias($v7c3c74d27f); public function escapeTable($v8c5df8072b); public function escapeTableAttribute($v5e45ec9bb9, $v8c5df8072b = false); public function getDatabaseHeader($pb67a2609); public function lockTable($v8c5df8072b); public function unlockTable(); public function getDropDatabaseStmt($pb67a2609); public function getShowTablesStmt($pb67a2609); public function getShowTableColumnsStmt($pc661dc6b, $pb67a2609 = false); public function getShowForeignKeysStmt($pc661dc6b, $pb67a2609 = false); public function getShowCreateTableStmt($pc661dc6b); public function getDropTableStmt($pc661dc6b); public function getDropTableForeignConstraintStmt($pc661dc6b, $pa28639ac); public function getStartLockTableWriteStmt($pc661dc6b); public function getStartLockTableReadStmt($pc661dc6b); public function getEndLockTableStmt(); public function getStartDisableKeysStmt($pc661dc6b); public function getEndDisableKeysStmt($pc661dc6b); public function getShowViewsStmt($pb67a2609); public function getShowCreateViewStmt($pa36e00ea); public function getDropViewStmt($pa36e00ea); public function getShowTriggersStmt($pb67a2609); public function getShowCreateTriggerStmt($v5ed3bce1d1); public function getDropTriggerStmt($v5ed3bce1d1); public function getShowProceduresStmt($pb67a2609); public function getShowCreateProcedureStmt($pbda8f16d); public function getDropProcedureStmt($pbda8f16d); public function getShowFunctionsStmt($pb67a2609); public function getShowCreateFunctionStmt($v2f4e66e00a); public function getDropFunctionStmt($v2f4e66e00a); public function getShowEventsStmt($pb67a2609); public function getShowCreateEventStmt($v76392c9cad); public function getDropEventStmt($v76392c9cad); public function getSetupTransactionStmt(); public function getStartTransactionStmt(); public function getCommitTransactionStmt(); public function getStartDisableAutocommitStmt(); public function getEndDisableAutocommitStmt(); } ?>
