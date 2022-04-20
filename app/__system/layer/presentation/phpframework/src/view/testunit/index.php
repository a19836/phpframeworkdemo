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

include $EVC->getUtilPath("WorkFlowPresentationHandler"); $choose_test_units_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=test_unit&path=#path#"; $open_test_unit_file_url = $project_url_prefix . "testunit/edit_test?path=#path#"; $execute_tests_url = $project_url_prefix . "testunit/execute_tests"; $manage_file_url = $project_url_prefix . "admin/manage_file?bean_name=test_unit&bean_file_name=&path=#path#&action=#action#&item_type=test_unit&extra=#extra#"; $create_test_url = $project_url_prefix . "phpframework/testunit/create_test?path=#path#&file_name=#extra#"; $head = '
<!-- Add MyTree main JS and CSS files -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/jquerymytree/css/style.min.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_common_url_prefix . 'vendor/jquerymytree/js/mytree.js"></script>

<!-- Add FileManager JS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/file_manager.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/file_manager.js"></script>

<!-- Add Fontawsome Icons CSS -->
<link rel="stylesheet" href="' . $project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">

<!-- Add Icons CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/testunit/index.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/testunit/index.js"></script>

<script>
var open_test_unit_file_url = "' . $open_test_unit_file_url . '";
var execute_tests_url = "' . $execute_tests_url . '";
var manage_file_url = "' . $manage_file_url . '";
var create_test_url = "' . $create_test_url . '";
'; $head .= WorkFlowPresentationHandler::getDaoLibAndVendorBrokersHtml($choose_test_units_files_from_file_manager_url, "", "", ""); $head .= '</script>'; $main_content = '
<div class="test_units">
	<div class="top_bar">
		<header>
			<div class="title">Manage Test Units</div>
			<ul>
				<li class="execute" data-title="Execute Selected Tests"><a onClick="executeSelectedTests(true)"><i class="icon continue"></i> Execute Selected Tests</a></li>
			</ul>
		</header>
	</div>
	
	<div id="test_units_tree" class="test_units_tree">
		<ul class="mytree">
			<li>
				<input class="select_test_unit" type="checkbox" value=1 onClick="onTestUnitCheckboxClick(this)" />
				<label>Test Units</label>
				<ul url="' . $choose_test_units_files_from_file_manager_url . '"></ul>
			</li>
		</ul>
	</div>
</div>'; ?>
