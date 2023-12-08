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
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $parent_entity_file_path = $_GET["path"]; $task_tag = $_GET["task_tag"]; $task_tag_action = $_GET["task_tag_action"]; $db_driver = $_GET["db_driver"]; $db_type = $_GET["db_type"]; $db_table = $_GET["db_table"]; $parent_add_block_func = $_GET["parent_add_block_func"]; $popup = $_GET["popup"]; $parent_entity_file_path = str_replace("../", "", $parent_entity_file_path); if (!$parent_add_block_func) die("parent_add_block_func missing"); if ($bean_name && $parent_entity_file_path) { $new_path = dirname($parent_entity_file_path) . "/" . pathinfo($parent_entity_file_path, PATHINFO_FILENAME) . "/"; $_GET["path"] = $new_path; } $do_not_load_or_save_workflow = true; $do_not_save_vars_file = true; $do_not_check_if_path_exists = true; $task_tag_action = str_replace(array(";", "|"), ",", $task_tag_action); $task_tag_action = explode(",", $task_tag_action); include $EVC->getEntityPath("presentation/create_presentation_uis_diagram"); ?>
