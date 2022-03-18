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

$ft = str_replace("edit_file_", "", $file_type); $path_extra = hash('crc32b', "$bean_file_name/$bean_name/$path/$class_id/" . ($ft == "class_method" ? $method_id : $function_id) ); $get_workflow_tasks_id = "business_logic_workflow&path_extra=_$path_extra"; $get_tmp_workflow_tasks_id = "business_logic_workflow_tmp&path_extra=_${path_extra}_" . rand(0, 1000); include $EVC->getViewPath("admin/edit_file_class_method"); $head .= '
<link rel="stylesheet" href="' . $project_url_prefix . 'css/businesslogic/edit_method.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/businesslogic/edit_method.js"></script>
<script>
	save_object_url = save_object_url.replace("/admin/save_file_' . ($ft == "class_method" ? "class_method" : "function") . '?", "/businesslogic/save_' . ($ft == "class_method" ? "method" : "function") . '?");
</script>
'; $show_business_logic_service_first = (!$method_id && $ft == "class_method") || (!$function_id && $ft == "function"); $is_bl_service_select_html = '<select class="is_business_logic_service" onChange="hideOrShowIsBusinessLogicService(this);">
				<option value="1"' . ($obj_data["is_business_logic_service"] || $show_business_logic_service_first ? " selected" : "") . '>Is business logic service</option>
				<option value="0"' . ($obj_data["is_business_logic_service"] || $show_business_logic_service_first ? "" : " selected") . '>Is regular function</option>
			</select>'; $main_content .= '
<script>
	$(".top_bar .title").append(\'' . str_replace("'", "\\'", str_replace("\n", "", $is_bl_service_select_html)) . '\');
</script>'; ?>
