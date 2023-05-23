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
?><div class="listing_task_html page_content_task_html">
	<ul>
		<li class="settings_tab"><a href="#listing_task_html_settings">Settings</a></li>
		<li class="ui_tab"><a href="#listing_task_html_ui">UI</a></li>
		<li class="permissions_tab"><a href="#listing_task_html_permissions">Permissions</a></li>
	</ul>
	
	<div class="settings" id="listing_task_html_settings">
		<div class="listing_type">
			<label>Listing Type:</label>
			<select class="task_property_field" name="listing_type" onChange="ListingTaskPropertyObj.onChangeListingType(this, true)">
				<option value="">Table</option>
				<option value="tree">Tree</option>
				<option value="multi_form">Multi-Form</option>
			</select>
		</div>
		
		<?php include dirname(dirname($file_path)) . "/common/ChooseDBTableHtml.php"; include dirname(dirname($file_path)) . "/common/TableActionHtml.php"; include dirname(dirname($file_path)) . "/common/LinksHtml.php"; ?>
		
		<div class="pagination">
			<label>Pagination:</label>
			
			<ul>
				<li class="pagination_active">
					<label>Is Active:</label>
					<input class="task_property_field" type="checkbox" name="pagination[active]" value="1" />
				</li>
				<li class="pagination_rows_per_page">
					<label>Pagination Rows Per Page:</label>
					<input class="task_property_field" type="number" name="pagination[rows_per_page]" value="" min="1" />
				</li>
			</ul>
		</div>
		
		<?php
 include dirname(dirname($file_path)) . "/common/InnerTaskUIHtml.php"; ?>
	</div>
	
	<div class="ui" id="listing_task_html_ui">
		<?php include dirname(dirname($file_path)) . "/common/TaskUITabContentHtml.php"; ?>
	</div>
	
	<div class="permissions" id="listing_task_html_permissions">
		<?php include dirname(dirname($file_path)) . "/common/TaskPermissionsTabContentHtml.php"; ?>
	</div>
	
	<div class="task_property_exit" exit_id="default_exit" exit_color="#426efa"></div>
</div>
