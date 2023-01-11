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

 class WorkFlowBrokersSelectedDBVarsHandler { public static function getBrokersSelectedDBVars($pc4223ce1) { $pbfd2d1c4 = array(); $pd7f46171 = null; $pc66a0204 = null; $v5a331eab7e = "db"; if ($pc4223ce1) foreach ($pc4223ce1 as $v2b2cf4c0eb => $pd922c2f7) if (is_a($pd922c2f7, "IDataAccessBrokerClient") || is_a($pd922c2f7, "IDBBrokerClient")) { $pbfd2d1c4[$v2b2cf4c0eb] = is_a($pd922c2f7, "IDBBrokerClient") ? $pd922c2f7->getDBDriversName() : $pd922c2f7->getBrokersDBDriversName(); if (empty($pd7f46171)) { $pd7f46171 = $v2b2cf4c0eb; if ($GLOBALS["default_db_driver"] && in_array($GLOBALS["default_db_driver"], $pbfd2d1c4[$v2b2cf4c0eb])) $pc66a0204 = $GLOBALS["default_db_driver"]; else if (!$pc66a0204) $pc66a0204 = $pbfd2d1c4[$v2b2cf4c0eb][0]; } } return array( "db_brokers_drivers" => $pbfd2d1c4, "dal_broker" => $pd7f46171, "db_driver" => $pc66a0204, "type" => $v5a331eab7e, ); } public static function printSelectedDBVarsJavascriptCode($peb014cfd, $v8ffce2a791, $pa0462a8e, $pe44aa1fe) { $v067674f4e4 = ''; if ($peb014cfd && $v8ffce2a791 && $pa0462a8e) $v067674f4e4 .= 'var get_broker_db_data_url = typeof get_broker_db_data_url != "undefined" && get_broker_db_data_url ? get_broker_db_data_url : "' . $peb014cfd . 'phpframework/dataaccess/get_broker_db_data?bean_name=' . $v8ffce2a791 . '&bean_file_name=' . $pa0462a8e . '";'; if ($pe44aa1fe) { if (array_key_exists("dal_broker", $pe44aa1fe)) $v067674f4e4 .= 'var default_dal_broker = "' . $pe44aa1fe["dal_broker"] . '";'; if (array_key_exists("db_driver", $pe44aa1fe)) $v067674f4e4 .= 'var default_db_driver = "' . $pe44aa1fe["db_driver"] . '";'; if (array_key_exists("type", $pe44aa1fe)) $v067674f4e4 .= 'var default_db_type = "' . $pe44aa1fe["type"] . '";'; if (array_key_exists("db_table", $pe44aa1fe)) $v067674f4e4 .= 'var default_db_table = "' . $pe44aa1fe["db_table"] . '";'; if ($pe44aa1fe["db_brokers_drivers"]) { $v067674f4e4 .= '
				if (typeof db_brokers_drivers_tables_attributes == "undefined") {
					var db_brokers_drivers_tables_attributes = {};'; foreach ($pe44aa1fe["db_brokers_drivers"] as $pab752e34 => $v84bde5f80a) { $v067674f4e4 .= 'db_brokers_drivers_tables_attributes["' . $pab752e34 . '"] = {};'; if ($v84bde5f80a) { $pc37695cb = count($v84bde5f80a); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) $v067674f4e4 .= 'db_brokers_drivers_tables_attributes["' . $pab752e34 . '"]["' . $v84bde5f80a[$v43dd7d0051] . '"] = {
								db: {},
								diagram: {}
							};'; } } $v067674f4e4 .= '}'; } return $v067674f4e4; } return ""; } } ?>
