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

include_once get_lib("org.phpframework.db.dump.DBDumper"); class MySqlDBDumper extends DBDumper { const REGEX = 'DEFINER=`(?:[^`]|``)*`@`(?:[^`]|``)*`'; public function __construct($v834e515e94) { $this->DBDumperHandler = $v834e515e94; } public function databases($pb67a2609) { $v3c76382d93 = "SHOW VARIABLES LIKE 'collation_database';"; $v3dd67d635b = $this->DBDumperHandler->getDBDriver()->getSQL($v3c76382d93); $pf6d213c3 = $v3dd67d635b[0]["Value"]; $v3c76382d93 = "SHOW VARIABLES LIKE 'character_set_database';"; $v3dd67d635b = $this->DBDumperHandler->getDBDriver()->getSQL($v3c76382d93); $pe77db5e4 = $v3dd67d635b[0]["Value"]; $v50890f6f30 = "CREATE DATABASE /*!32312 IF NOT EXISTS*/ `${db_name}`". " /*!40100 DEFAULT CHARACTER SET ${character_set} ". " COLLATE ${collation} */;" . PHP_EOL . PHP_EOL . "USE `${db_name}`;" . PHP_EOL . PHP_EOL; return $v50890f6f30; } public function createTable($pba23d78c, $v8c5df8072b, $v11f9d89738 = false) { $pff59654a = $pba23d78c[0]; $pc5a23cfc = $this->DBDumperHandler->getDBDumperSettings(); if (!isset($pff59654a['Create Table'])) throw new \Exception("Error getting table code, unknown output"); $v813853251a = $pff59654a['Create Table']; if ($pc5a23cfc['reset-auto-increment']) { $v87ae7286da = "/AUTO_INCREMENT=[0-9]+/s"; $pba08477b = ""; $v813853251a = preg_replace($v87ae7286da, $pba08477b, $v813853251a); } if ($v11f9d89738) { $v9d1bf2c12a = array(); if ($v11f9d89738) foreach ($v11f9d89738 as $pa7c14731) $v9d1bf2c12a[ $pa7c14731["child_column"] ][ $pa7c14731["parent_table"] ][ $pa7c14731["parent_column"] ] = true; preg_match_all("/[^,]+(\s*FOREIGN\s+KEY\s*)[^,;]+/iu", $v813853251a, $pbae7526c, PREG_OFFSET_CAPTURE); if ($pbae7526c) { foreach ($pbae7526c[0] as $v6107abf109) { $v87ae7286da = $v6107abf109[0]; $pbd1bc7b0 = $v6107abf109[1]; $v0ca67a8da8 = true; if (preg_match("/create\s+table\s+/iu", $v87ae7286da, $pcd395670, PREG_OFFSET_CAPTURE)) { $v4430104888 = strpos($v87ae7286da, "(", $pcd395670[0][1] + 1) + 1; $v87ae7286da = trim( substr($v87ae7286da, $v4430104888) ); $v0ca67a8da8 = false; } $pe2ae3be9 = strlen($v87ae7286da); $v2e6bbcee7d = 0; $v79b7cb19d1 = $v66327139f0 = false; for ($v9d27441e80 = 0; $v9d27441e80 < $pe2ae3be9; $v9d27441e80++) { $pc288256e = $v87ae7286da[$v9d27441e80]; if ($pc288256e == '"' && !$v66327139f0) $v79b7cb19d1 = !$v79b7cb19d1; else if ($pc288256e == "'" && !$v79b7cb19d1) $v66327139f0 = !$v66327139f0; else if (!$v79b7cb19d1 && !$v66327139f0) { if ($pc288256e == "(") $v2e6bbcee7d++; else if ($pc288256e == ")") { $v2e6bbcee7d--; if ($v2e6bbcee7d < 0) { $v87ae7286da = trim( substr($v87ae7286da, 0, $v9d27441e80) ); break; } } } } preg_match("/\s*FOREIGN\s+KEY\s*\(\s*`?([\w]+)`?\s*\)\s*REFERENCES\s*`?([\w]+)`?\s*\(\s*`?([\w]+)`?\s*\)/iu", $v87ae7286da, $pcd395670, PREG_OFFSET_CAPTURE); $v8822d98b2e = $pcd395670[1][0]; $pfcfb6727 = $pcd395670[2][0]; $v9755371d2f = $pcd395670[3][0]; if ($v8822d98b2e && $pfcfb6727 && $v9755371d2f && $v9d1bf2c12a[ $v8822d98b2e ][ $pfcfb6727 ][ $v9755371d2f ]) { $v2343c84346 = $v87ae7286da; $v2343c84346 = substr($v2343c84346, 0, 1) == "," ? substr($v2343c84346, 1) : $v2343c84346; $v2343c84346 = substr($v2343c84346, -1) == "," ? substr($v2343c84346, 0, -1) : $v2343c84346; $this->DBDumperHandler->setTableExtraSql($pfcfb6727, "ALTER TABLE `$v8c5df8072b` ADD $v2343c84346;" . PHP_EOL); $v87ae7286da = substr($v87ae7286da, -1) == "," ? substr($v87ae7286da, 0, -1) : $v87ae7286da; $v813853251a = str_replace($v87ae7286da, "", $v813853251a); $v813853251a = preg_replace("/,(\s*)\)/", '$1)', $v813853251a); } } } } $v50890f6f30 = "/*!40101 SET @saved_cs_client     = @@character_set_client */;" . PHP_EOL . "/*!40101 SET character_set_client = ".$pc5a23cfc['default-character-set']." */;" . PHP_EOL . $v813853251a.";" . PHP_EOL . "/*!40101 SET character_set_client = @saved_cs_client */;" . PHP_EOL . PHP_EOL; return $v50890f6f30; } public function createRecordsInsertStmt($v8c5df8072b, $v3dd67d635b) { $pc5a23cfc = $this->DBDumperHandler->getDBDumperSettings(); $v8c9fa8af2d = $this->DBDumperHandler->getFileCompressionHandler(); if ($pc5a23cfc['complete-insert']) $v57c3cf2d15 = $this->DBDumperHandler->getTableAttributesNames($v8c5df8072b); $v2cc816973e = 0; $pc50aa7ff = true; $v15f3268002 = 0; $pdda759f2 = $pc5a23cfc['insert-ignore'] ? ' IGNORE' : ''; foreach ($v3dd67d635b as $pff59654a) { $v073724e2a6 = $this->DBDumperHandler->prepareTableRowAttributes($v8c5df8072b, $pff59654a); $v3fc0565720 = implode(",", $v073724e2a6); if (!$pc5a23cfc['extended-insert'] || $pc50aa7ff) { if ($pc5a23cfc['complete-insert']) $v327f72fb62 = "INSERT$pdda759f2 INTO " . $this->escapeTable($v8c5df8072b) . " (" . implode(", ", $v57c3cf2d15) . ") VALUES (" . $v3fc0565720 . ")"; else $v327f72fb62 = "INSERT$pdda759f2 INTO " . $this->escapeTable($v8c5df8072b) . " VALUES (" . $v3fc0565720 . ")"; $pc50aa7ff = false; } else $v327f72fb62 = ",(" . $v3fc0565720 . ")"; $v2cc816973e += $v8c9fa8af2d->write($v327f72fb62); if (!$pc5a23cfc['extended-insert'] || $v2cc816973e > $pc5a23cfc['net_buffer_length']) { $pc50aa7ff = true; $v2cc816973e = $v8c9fa8af2d->write(";" . PHP_EOL); } $v15f3268002++; } if (!$pc50aa7ff) $v8c9fa8af2d->write(";" . PHP_EOL); return $v15f3268002; } public function getSqlStmtWithLimit($v3c76382d93, $v552b831ecd) { return $v3c76382d93 . " LIMIT {$v552b831ecd}"; } public function createStandInTableForView($v03205f9bb8, $v6bc5012fff) { return "CREATE TABLE IF NOT EXISTS " . $this->escapeTable($v03205f9bb8) . " (". PHP_EOL . $pa2b29664 . PHP_EOL . ");" . PHP_EOL; } public function getTableAttributeProperties($v670a5790dd) { $v26ddf2909a = $this->DBDumperHandler->getDBDriver()->getDBColumnNumericTypes(); $v7ee1284650 = $this->DBDumperHandler->getDBDriver()->getDBColumnBlobTypes(); $v415a32ad4d = $this->DBDumperHandler->getDBDriver()->getDBColumnBooleanTypes(); $pd152184b = array(); $pd152184b['field'] = $v670a5790dd['column_name']; $pd152184b['type_sql'] = $v670a5790dd['data_type']; $v9cd205cadb = explode(" ", $v670a5790dd['data_type']); if ($pbd1bc7b0 = strpos($v9cd205cadb[0], "(")) { $pd152184b['type'] = substr($v9cd205cadb[0], 0, $pbd1bc7b0); $pd152184b['length'] = str_replace(")", "", substr($v9cd205cadb[0], $pbd1bc7b0 + 1)); $pd152184b['attributes'] = isset($v9cd205cadb[1]) ? $v9cd205cadb[1] : null; } else { $pd152184b['type'] = $v9cd205cadb[0]; $pd152184b['length'] = $v670a5790dd["character_maximum_length"] ? $v670a5790dd["character_maximum_length"] : $v670a5790dd["numeric_precision"]; } $pd152184b['is_nullable'] = strtolower($v670a5790dd['is_nullable']) != "no" ? false : true; $pd152184b['is_numeric'] = is_array($v26ddf2909a) && in_array($pd152184b['type'], $v26ddf2909a); $pd152184b['is_blob'] = is_array($v7ee1284650) && in_array($pd152184b['type'], $v7ee1284650); $pd152184b['is_boolean'] = is_array($v415a32ad4d) && in_array($pd152184b['type'], $v415a32ad4d); $pd152184b['is_virtual'] = strpos($v670a5790dd['Extra'], "VIRTUAL GENERATED") !== false || strpos($v670a5790dd['Extra'], "STORED GENERATED") !== false; return $pd152184b; } public function getTableAttributesPropertiesBitHexFunc($v5e45ec9bb9) { return "LPAD(HEX($v5e45ec9bb9),2,'0')"; } public function getTableAttributesPropertiesBlobHexFunc($v5e45ec9bb9) { return "HEX($v5e45ec9bb9)"; } public function createView($pff59654a) { $v50890f6f30 = ""; if (!isset($pff59654a['Create View'])) throw new \Exception("Error getting view structure, unknown output"); $v54247e47e6 = $pff59654a['Create View']; $pc5a23cfc = $this->DBDumperHandler->getDBDumperSettings(); $v4ab3c217a3 = $pc5a23cfc['skip-definer'] ? '' : '/*!50013 \2 */' . PHP_EOL; if ($pd40278c6 = preg_replace('/^(CREATE(?:\s+ALGORITHM=(?:UNDEFINED|MERGE|TEMPTABLE))?)\s+('.self::REGEX.'(?:\s+SQL SECURITY DEFINER|INVOKER)?)?\s+(VIEW .+)$/', '/*!50001 \1 */' . PHP_EOL . $v4ab3c217a3 . '/*!50001 \3 */', $v54247e47e6, 1)) $v54247e47e6 = $pd40278c6; $v50890f6f30 .= $v54247e47e6 . ';' . PHP_EOL . PHP_EOL; return $v50890f6f30; } public function createTrigger($pff59654a) { $v50890f6f30 = ""; if (!isset($pff59654a['SQL Original Statement'])) throw new \Exception("Error getting trigger code, unknown output"); $pccd161a7 = $pff59654a['SQL Original Statement']; $pc5a23cfc = $this->DBDumperHandler->getDBDumperSettings(); $v4ab3c217a3 = $pc5a23cfc['skip-definer'] ? '' : '/*!50017 \2*/ '; if ($v1e2f1ebeb3 = preg_replace('/^(CREATE)\s+('.self::REGEX.')?\s+(TRIGGER\s.*)$/s', '/*!50003 \1*/ '.$v4ab3c217a3.'/*!50003 \3 */', $pccd161a7, 1)) $pccd161a7 = $v1e2f1ebeb3; $v50890f6f30 .= "DELIMITER ;;" . PHP_EOL . $pccd161a7 . ";;" . PHP_EOL . "DELIMITER ;" . PHP_EOL . PHP_EOL; return $v50890f6f30; } public function createProcedure($pff59654a) { $v50890f6f30 = ""; if (!isset($pff59654a['Create Procedure'])) throw new \Exception("Error getting procedure code, unknown output. ". "Please check 'https://bugs.mysql.com/bug.php?id=14564'"); $v66619a1bf8 = $pff59654a['Create Procedure']; $pc5a23cfc = $this->DBDumperHandler->getDBDumperSettings(); if ($pc5a23cfc['skip-definer']) if ($v966a94bbfb = preg_replace('/^(CREATE)\s+('.self::REGEX.')?\s+(PROCEDURE\s.*)$/s', '\1 \3', $v66619a1bf8, 1 )) $v66619a1bf8 = $v966a94bbfb; $v50890f6f30 .= "/*!50003 DROP PROCEDURE IF EXISTS `".$pff59654a['Procedure']."` */;" . PHP_EOL . "/*!40101 SET @saved_cs_client     = @@character_set_client */;" . PHP_EOL . "/*!40101 SET character_set_client = ".$pc5a23cfc['default-character-set']." */;" . PHP_EOL . "DELIMITER ;;" . PHP_EOL . $v66619a1bf8 . " ;;" . PHP_EOL . "DELIMITER ;" . PHP_EOL . "/*!40101 SET character_set_client = @saved_cs_client */;" . PHP_EOL . PHP_EOL; return $v50890f6f30; } public function createFunction($pff59654a) { $v50890f6f30 = ""; if (!isset($pff59654a['Create Function'])) throw new \Exception("Error getting function code, unknown output. ". "Please check 'https://bugs.mysql.com/bug.php?id=14564'"); $pc9901b56 = $pff59654a['Create Function']; $v89ebd7916e = $pff59654a['character_set_client']; $v91e1a98d94 = $pff59654a['collation_connection']; $pa90a07f1 = $pff59654a['sql_mode']; $pc5a23cfc = $this->DBDumperHandler->getDBDumperSettings(); if ($pc5a23cfc['skip-definer']) if ($pb260b2b7 = preg_replace('/^(CREATE)\s+('.self::REGEX.')?\s+(FUNCTION\s.*)$/s', '\1 \3', $pc9901b56, 1)) $pc9901b56 = $pb260b2b7; $v50890f6f30 .= "/*!50003 DROP FUNCTION IF EXISTS `". $pff59654a['Function'] . "` */;" . PHP_EOL . "/*!40101 SET @saved_cs_client     = @@character_set_client */;" . PHP_EOL . "/*!50003 SET @saved_cs_results     = @@character_set_results */ ;" . PHP_EOL . "/*!50003 SET @saved_col_connection = @@collation_connection */ ;" . PHP_EOL . "/*!40101 SET character_set_client = " . $v89ebd7916e . " */;" . PHP_EOL . "/*!40101 SET character_set_results = " . $v89ebd7916e . " */;" . PHP_EOL . "/*!50003 SET collation_connection  = " . $v91e1a98d94 . " */ ;" . PHP_EOL . "/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;;" . PHP_EOL . "/*!50003 SET sql_mode              = '".$pa90a07f1."' */ ;;" . PHP_EOL . "/*!50003 SET @saved_time_zone      = @@time_zone */ ;;" . PHP_EOL . "/*!50003 SET time_zone             = 'SYSTEM' */ ;;" . PHP_EOL . "DELIMITER ;;" . PHP_EOL . $pc9901b56 . " ;;" . PHP_EOL . "DELIMITER ;" . PHP_EOL . "/*!50003 SET sql_mode              = @saved_sql_mode */ ;" . PHP_EOL . "/*!50003 SET character_set_client  = @saved_cs_client */ ;" . PHP_EOL . "/*!50003 SET character_set_results = @saved_cs_results */ ;" . PHP_EOL . "/*!50003 SET collation_connection  = @saved_col_connection */ ;" . PHP_EOL . "/*!50106 SET TIME_ZONE= @saved_time_zone */ ;" . PHP_EOL . PHP_EOL; return $v50890f6f30; } public function createEvent($pff59654a) { $v50890f6f30 = ""; if (!isset($pff59654a['Create Event'])) throw new \Exception("Error getting event code, unknown output. ". "Please check 'http://stackoverflow.com/questions/10853826/mysql-5-5-create-event-gives-syntax-error'"); $v93ba3bd3f2 = $pff59654a['Event']; $pf66838e2 = $pff59654a['Create Event']; $pa90a07f1 = $pff59654a['sql_mode']; $pc5a23cfc = $this->DBDumperHandler->getDBDumperSettings(); $v4ab3c217a3 = $pc5a23cfc['skip-definer'] ? '' : '/*!50117 \2*/ '; if ($v8175b5b114 = preg_replace('/^(CREATE)\s+('.self::REGEX.')?\s+(EVENT .*)$/', '/*!50106 \1*/ '.$v4ab3c217a3.'/*!50106 \3 */', $pf66838e2, 1)) $pf66838e2 = $v8175b5b114; $v50890f6f30 .= "/*!50106 SET @save_time_zone= @@TIME_ZONE */ ;" . PHP_EOL . "/*!50106 DROP EVENT IF EXISTS `" . $v93ba3bd3f2 . "` */;" . PHP_EOL . "DELIMITER ;;" . PHP_EOL . "/*!50003 SET @saved_cs_client      = @@character_set_client */ ;;" . PHP_EOL . "/*!50003 SET @saved_cs_results     = @@character_set_results */ ;;" . PHP_EOL . "/*!50003 SET @saved_col_connection = @@collation_connection */ ;;" . PHP_EOL . "/*!50003 SET character_set_client  = utf8 */ ;;" . PHP_EOL . "/*!50003 SET character_set_results = utf8 */ ;;" . PHP_EOL . "/*!50003 SET collation_connection  = utf8_general_ci */ ;;" . PHP_EOL . "/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;;" . PHP_EOL . "/*!50003 SET sql_mode              = '" . $pa90a07f1 . "' */ ;;" . PHP_EOL . "/*!50003 SET @saved_time_zone      = @@time_zone */ ;;" . PHP_EOL . "/*!50003 SET time_zone             = 'SYSTEM' */ ;;" . PHP_EOL . $pf66838e2 . " ;;" . PHP_EOL . "/*!50003 SET time_zone             = @saved_time_zone */ ;;" . PHP_EOL . "/*!50003 SET sql_mode              = @saved_sql_mode */ ;;" . PHP_EOL . "/*!50003 SET character_set_client  = @saved_cs_client */ ;;" . PHP_EOL . "/*!50003 SET character_set_results = @saved_cs_results */ ;;" . PHP_EOL . "/*!50003 SET collation_connection  = @saved_col_connection */ ;;" . PHP_EOL . "DELIMITER ;" . PHP_EOL . "/*!50106 SET TIME_ZONE= @save_time_zone */ ;" . PHP_EOL . PHP_EOL; return $v50890f6f30; } public function backupParameters() { $pc5a23cfc = $this->DBDumperHandler->getDBDumperSettings(); $v50890f6f30 = "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;" . PHP_EOL . "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;" . PHP_EOL . "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;" . PHP_EOL . "/*!40101 SET NAMES " . $pc5a23cfc['default-character-set'] . " */;" . PHP_EOL; if ($pc5a23cfc['skip-tz-utc'] === false) $v50890f6f30 .= "/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;" . PHP_EOL . "/*!40103 SET TIME_ZONE='+00:00' */;" . PHP_EOL; if ($pc5a23cfc['no-autocommit']) $v50890f6f30 .= "/*!40101 SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT */;" . PHP_EOL; $v50890f6f30 .= "/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;" . PHP_EOL . "/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;" . PHP_EOL . "/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;" . PHP_EOL . "/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;" . PHP_EOL . PHP_EOL; return $v50890f6f30; } public function restoreParameters() { $v50890f6f30 = ""; $pc5a23cfc = $this->DBDumperHandler->getDBDumperSettings(); if (false === $pc5a23cfc['skip-tz-utc']) $v50890f6f30 .= "/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;" . PHP_EOL; if ($pc5a23cfc['no-autocommit']) $v50890f6f30 .= "/*!40101 SET AUTOCOMMIT=@OLD_AUTOCOMMIT */;" . PHP_EOL; $v50890f6f30 .= "/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;" . PHP_EOL . "/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;" . PHP_EOL . "/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;" . PHP_EOL . "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;" . PHP_EOL . "/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;" . PHP_EOL . "/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;" . PHP_EOL . "/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;" . PHP_EOL . PHP_EOL; return $v50890f6f30; } public function startDisableConstraintsAndTriggersStmt($pac4bc40a) { $v3c76382d93 = "SET foreign_key_checks = 0;" . PHP_EOL; $pc5a23cfc = $this->DBDumperHandler->getDBDumperSettings(); if ($pac4bc40a && $pc5a23cfc['add-drop-table']) { $pac4bc40a = is_array($pac4bc40a) ? $pac4bc40a : array($pac4bc40a); $v3c76382d93 .= PHP_EOL . "DROP PROCEDURE IF EXISTS dropAllDBForeignKeys;
DELIMITER ;;
CREATE PROCEDURE dropAllDBForeignKeys()
BEGIN
  DECLARE bDone INT;
  DECLARE sql_str VARCHAR(1000);

  DECLARE curs CURSOR FOR SELECT 
	  CONCAT('ALTER TABLE ', TABLE_NAME, ' DROP FOREIGN KEY ', CONSTRAINT_NAME, ';') AS 'drop_sql'
	FROM information_schema.key_column_usage 
	WHERE CONSTRAINT_SCHEMA = DATABASE() AND referenced_table_name IS NOT NULL AND TABLE_NAME in ('" . implode("', '", $pac4bc40a) . "');
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;

  OPEN curs;

  SET bDone = 0;
  REPEAT
    FETCH curs INTO sql_str;

    IF sql_str != '' THEN
	  SET @sql = sql_str;
	  PREPARE stmt FROM @sql;
	  EXECUTE stmt;
	  DEALLOCATE PREPARE stmt;
    END IF;
  UNTIL bDone END REPEAT;

  CLOSE curs;
END;;
DELIMITER ;

CALL dropAllDBForeignKeys();
DROP PROCEDURE IF EXISTS dropAllDBForeignKeys;" . PHP_EOL; } return $v3c76382d93; } public function endDisableConstraintsAndTriggersStmt($pac4bc40a) { return "SET foreign_key_checks = 1;" . PHP_EOL; } } ?>
