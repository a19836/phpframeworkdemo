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
include_once $EVC->getUtilPath("PHPVariablesFileHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $popup = $_GET["popup"]; if (isset($_POST["save"])) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $vars_name = $_POST["vars_name"]; $vars_value = $_POST["vars_value"]; $global_variables = array(); if ($vars_name) { $t = count($vars_name); for($i = 0; $i < $t; $i++) { $var_name = $vars_name[$i]; $var_value = $vars_value[$i]; $var_value_lower = strtolower($var_value); if ($var_value_lower == "true") $var_value = true; else if ($var_value_lower == "false") $var_value = false; else if ($var_value_lower == "null") $var_value = null; $global_variables[$var_name] = $var_value; } } if (PHPVariablesFileHandler::saveVarsToFile($user_global_variables_file_path, $global_variables, true)) $status_message = "Variables saved successfully"; else $error_message = "There was an error trying to save variables. Please try again..."; } $vars = PHPVariablesFileHandler::getVarsFromFileContent($user_global_variables_file_path); foreach ($vars as $var_name => $var_value) { if ($var_value === true) $vars[$var_name] = "true"; else if ($var_value === false) $vars[$var_name] = "false"; else if ($var_value === null) $vars[$var_name] = "null"; } ?>
