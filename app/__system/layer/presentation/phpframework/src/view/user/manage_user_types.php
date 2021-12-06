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

include $EVC->getUtilPath("UserAuthenticationUIHandler"); $head = '
<!-- Add Icons CSS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />

<!-- Add Local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/user/user.css" type="text/css" charset="utf-8" />
'; $main_content = '
<div id="menu">' . UserAuthenticationUIHandler::getMenu($UserAuthenticationHandler, $project_url_prefix) . '</div>
<div id="content">
	<div class="title">Manage User Types</div>
	<div class="user_types_list">
	<table>
		<tr>
			<th class="table_header user_type_id">Id</th>
			<th class="table_header name">Name</th>
			<th class="table_header created_date">Created Date</th>
			<th class="table_header modified_date">Modified Date</th>
			<th class="table_header buttons">
				<a class="icon add" href="' . $project_url_prefix . 'user/edit_user_type">Add</a>
			</th>
		</tr>'; $t = count($user_types); for ($i = 0; $i < $t; $i++) { $user_type = $user_types[$i]; $main_content .= '<tr>
		<td class="user_type_id">' . $user_type["user_type_id"] . '</td>
		<td class="name">' . $user_type["name"] . '</td>
		<td class="created_date">' . $user_type["created_date"] . '</td>
		<td class="modified_date">' . $user_type["modified_date"] . '</td>
		<td class="buttons">
			<a class="icon update" href="' . $project_url_prefix . 'user/edit_user_type?user_type_id=' . $user_type["user_type_id"] . '">Edit</a>
		</td>
	</tr>'; } $main_content .= '</table>
	</div>
</div>'; ?>
