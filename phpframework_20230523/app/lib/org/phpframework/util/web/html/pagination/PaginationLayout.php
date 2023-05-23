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

include_once get_lib("org.phpframework.util.web.html.pagination.PaginationHandler"); class PaginationLayout extends PaginationHandler { public static $PAGINATION_CURRENT_PAGE_TITLE = "Page #current_page# of #num_pages#"; public static $PAGINATION_GOTO_PAGE_TITLE = "Page"; public static $PAGINATION_GOTO_PREVIOUS_PAGE_TITLE = "Prev"; public static $PAGINATION_GOTO_NEXT_PAGE_TITLE = "Next"; public static $PAGINATION_GO_BUTTON_TITLE = "GO"; public function design1($v539082ff30) { $pf8ed4912 = ""; $v6f3a2700dd = $v539082ff30["url"]; $v1598b4cc2b = $v539082ff30["post"]; $v72aaf5a611 = isset($v539082ff30["pagination_goto_page_title"]) ? $v539082ff30["pagination_goto_page_title"] : self::$PAGINATION_GOTO_PAGE_TITLE; $pd340ca09 = isset($v539082ff30["pagination_goto_previous_page_title"]) ? $v539082ff30["pagination_goto_previous_page_title"] : self::$PAGINATION_GOTO_PREVIOUS_PAGE_TITLE; $v920681538d = isset($v539082ff30["pagination_goto_next_page_title"]) ? $v539082ff30["pagination_goto_next_page_title"] : self::$PAGINATION_GOTO_NEXT_PAGE_TITLE; $v701073fa4d = isset($v539082ff30["pagination_go_button_title"]) ? $v539082ff30["pagination_go_button_title"] : self::$PAGINATION_GO_BUTTON_TITLE; if ($this->data['num_pages'] > 1) { $v90cd343a76 = "form_".md5(rand()); if ($v539082ff30["with_css"]) $pf8ed4912 .= $this->getDesign1Css(); $pf8ed4912 .= '
			<!-----  Start of Pagination Code ----->
			<div class="paging">' . $this->f32b570165e($v90cd343a76, $v6f3a2700dd, $v1598b4cc2b); if ($this->data['num_pages'] >= $this->show_x_pages_at_once) { $pf8ed4912 .= '
				<div class="go_to_page">
					' . $v72aaf5a611 . ' <select name="' . $this->data['page_attr_name'] . '" class="pgs_id">'; for ($v9d27441e80 = 1; $v9d27441e80 <= $this->data['num_pages']; $v9d27441e80++) $pf8ed4912 .= '<option' . ($v9d27441e80 == $this->data['cur_page'] ? " selected" : "") . '>' . $v9d27441e80 . '</option>'; $pf8ed4912 .= '</select>
					<button class="go_bt" type="button" value="' . $v701073fa4d . '" onclick="submitNewPageIn_' . $v90cd343a76 . '( $(this).parent().children(\'select\').first().val(), \'number\', this );return false;">' . $v701073fa4d . '</button>
				</div>'; } $v666395d036 = ceil($this->show_x_pages_at_once / 2); $pfd5604f2 = ceil($this->data['cur_page'] / $v666395d036); $v4122be0de3 = ($pfd5604f2 * $v666395d036) - $v666395d036 + 1; $pdcf33c9e = $v4122be0de3 + $v666395d036; $v3b94444af0 = $this->show_x_pages_at_once - ($pdcf33c9e - $v4122be0de3 + 1); if ($v3b94444af0 > 0) { $v63e16ffc08 = $pdcf33c9e + $v3b94444af0 < $this->data['num_pages'] ? $v3b94444af0 : $this->data['num_pages'] - $pdcf33c9e; $v4122be0de3 -= $v3b94444af0 - $v63e16ffc08 > 0 ? $v3b94444af0 - $v63e16ffc08 : 0; $pdcf33c9e += $v63e16ffc08 > 0 ? $v63e16ffc08 : 0; } $v4122be0de3 = $v4122be0de3 < 1 ? 1 : $v4122be0de3; $pdcf33c9e = $pdcf33c9e > $this->data['num_pages'] ? $this->data['num_pages'] : $pdcf33c9e; $pf8ed4912 .= '<div class="page_numbers">'; if ($this->data['cur_page'] > $v666395d036) { $v080cf38d89 = $v4122be0de3 - $v666395d036; $v080cf38d89 = $v080cf38d89 < 1 ? 1 : $v080cf38d89; $pf8ed4912 .= '<a class="prev" href="' . $v6f3a2700dd . '&' . $this->data['page_attr_name'] . '=' . $v080cf38d89 . '" onClick="submitNewPageIn_' . $v90cd343a76 . '(' . $v080cf38d89 . ', \'prev\', this);return false;"> &lt; ' . $pd340ca09 . ' ' . $v666395d036 . '</a>'; } for ($v43dd7d0051 = $v4122be0de3; $v43dd7d0051 <= $pdcf33c9e; $v43dd7d0051++) { if ($this->data['cur_page'] == $v43dd7d0051) { $pf8ed4912 .= ' <a class="selected" href="' . $v6f3a2700dd . '&' . $this->data['page_attr_name'] . '=' . $v43dd7d0051. '" onClick="submitNewPageIn_' . $v90cd343a76 . '(' . $v43dd7d0051 . ', \'number\', this);return false;"><b>' . $v43dd7d0051 . '</b></a> '; } else { $pf8ed4912 .= ' <a class="unselected" href="' . $v6f3a2700dd . '&' . $this->data['page_attr_name'] . '=' . $v43dd7d0051 . '" onClick="submitNewPageIn_' . $v90cd343a76 . '(' . $v43dd7d0051 . ', \'number\', this);return false;" style="color:#000;text-decoration:none;">' . $v43dd7d0051 . '</a> '; } } if ($pdcf33c9e < $this->data['num_pages']) { $v080cf38d89 = $pdcf33c9e + $v666395d036; $v080cf38d89 = $v080cf38d89 > $this->data['num_pages'] ? $this->data['num_pages'] : $v080cf38d89; $pf8ed4912 .= '<a class="next" href="' . $v6f3a2700dd . '&' . $this->data['page_attr_name'] . '=' . $v080cf38d89 . '" onClick="submitNewPageIn_' . $v90cd343a76 . '(' . $v080cf38d89 . ', \'next\', this);return false;">' . $v920681538d . ' ' . $v666395d036 . ' &gt;</a>'; } $pf8ed4912 .= '</div>
			</div>
			<!-----  End of Pagination Code ----->'; } return $pf8ed4912; } public function getDesign1Css() { return '.paging {margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size:12px; text-align:center; color:#666; display:table;}
.paging .cur_page {border: solid 2px #FFFFFF; border-radius:.15rem; background-color:#F7F7F7; font-size:11px; font-family:Arial, Helvetica, sans-serif;}
.paging .go_to_page {margin:0 5px; display:inline-block;}
.paging .go_to_page form {font-family:Arial, Helvetica, sans-serif;}
.paging .go_to_page select {height:25px; display:inline-block; box-sizing:border-box; font-size:12px; font-weight:bold; font-family:Arial, Helvetica, sans-serif; color:inherit; border:1px solid #ccc; border-radius:.15rem; vertical-align:middle; background:transparent;}
.paging .go_to_page button {height:25px; display:inline-block; box-sizing:border-box; font-weight:bold; font-family:Arial, Helvetica, sans-serif; color:inherit; border:1px solid #ccc; border-radius:.15rem; vertical-align:middle; cursor:pointer;}
.paging .go_to_page button:hover {text-decoration:underline;}
.paging .go_to_page div {}
.paging .page_numbers {margin:0 5px; display:inline-block; clear:both;}
.paging .page_numbers a {height:25px; padding:2px 7px; box-sizing:border-box; display:inline-block; vertical-align:middle; border:1px solid #ccc; border-radius:.15rem; color:inherit;}
.paging .prev, .paging .next {color:inherit; text-decoration:none; font-weight:bold;}
.paging .selected {color:#333; text-decoration:none; background-color:#CCCCCC; cursor:default;}
.paging .unselected {color:#333; text-decoration:none;}
.paging .unselected:hover, .paging .prev:hover, .paging .next:hover {color:#000; text-decoration:underline !important;}'; } public function bootstrap1($v539082ff30) { $pf8ed4912 = ""; $v6f3a2700dd = $v539082ff30["url"]; $v1598b4cc2b = $v539082ff30["post"]; $pd340ca09 = isset($v539082ff30["pagination_goto_previous_page_title"]) ? $v539082ff30["pagination_goto_previous_page_title"] : self::$PAGINATION_GOTO_PREVIOUS_PAGE_TITLE; $v920681538d = isset($v539082ff30["pagination_goto_next_page_title"]) ? $v539082ff30["pagination_goto_next_page_title"] : self::$PAGINATION_GOTO_NEXT_PAGE_TITLE; if ($this->data['num_pages'] > 1) { $v90cd343a76 = "form_".md5(rand()); if ($v539082ff30["with_css"]) $pf8ed4912 .= $this->getBootstrap1Css(); $pf8ed4912 .= '
			<!-----  Start of Pagination Code ----->
			<nav class="paging">' . $this->f32b570165e($v90cd343a76, $v6f3a2700dd, $v1598b4cc2b); $v666395d036 = ceil($this->show_x_pages_at_once / 2); $pfd5604f2 = ceil($this->data['cur_page'] / $v666395d036); $v4122be0de3 = ($pfd5604f2 * $v666395d036) - $v666395d036 + 1; $pdcf33c9e = $v4122be0de3 + $v666395d036; $v3b94444af0 = $this->show_x_pages_at_once - ($pdcf33c9e - $v4122be0de3 + 1); if ($v3b94444af0 > 0) { $v63e16ffc08 = $pdcf33c9e + $v3b94444af0 < $this->data['num_pages'] ? $v3b94444af0 : $this->data['num_pages'] - $pdcf33c9e; $v4122be0de3 -= $v3b94444af0 - $v63e16ffc08 > 0 ? $v3b94444af0 - $v63e16ffc08 : 0; $pdcf33c9e += $v63e16ffc08 > 0 ? $v63e16ffc08 : 0; } $v4122be0de3 = $v4122be0de3 < 1 ? 1 : $v4122be0de3; $pdcf33c9e = $pdcf33c9e > $this->data['num_pages'] ? $this->data['num_pages'] : $pdcf33c9e; $pf8ed4912 .= '<ul class="pagination">'; if ($this->data['cur_page'] > $v666395d036) { $v080cf38d89 = $v4122be0de3 - $v666395d036; $v080cf38d89 = $v080cf38d89 < 1 ? 1 : $v080cf38d89; $pf8ed4912 .= '<li class="page-item"><a class="page-link prev" href="' . $v6f3a2700dd . '&' . $this->data['page_attr_name'] . '=' . $v080cf38d89 . '" onClick="submitNewPageIn_' . $v90cd343a76 . '(' . $v080cf38d89 . ', \'prev\', this);return false;"> &lt; ' . $pd340ca09 . ' ' . $v666395d036 . '</a></li>'; } for ($v43dd7d0051 = $v4122be0de3; $v43dd7d0051 <= $pdcf33c9e; $v43dd7d0051++) { $pf8ed4912 .= '<li class="page-item">'; if ($this->data['cur_page'] == $v43dd7d0051) { if ($this->data['num_pages'] >= $this->show_x_pages_at_once) { $pf8ed4912 .= ' <select name="' . $this->data['page_attr_name'] . '" class="page-link pr-0 pgs_id" onChange="submitNewPageIn_' . $v90cd343a76 . '( $(this).val(), \'number\', this );return false;" style="height:100%; height:calc(100% - 1px);">'; for ($v9d27441e80 = 1; $v9d27441e80 <= $this->data['num_pages']; $v9d27441e80++) $pf8ed4912 .= '<option' . ($v9d27441e80 == $this->data['cur_page'] ? " selected" : "") . '>' . $v9d27441e80 . '</option>'; $pf8ed4912 .= '</select>'; } else $pf8ed4912 .= ' <a class="page-link selected" href="' . $v6f3a2700dd . '&' . $this->data['page_attr_name'] . '=' . $v43dd7d0051. '" onClick="submitNewPageIn_' . $v90cd343a76 . '(' . $v43dd7d0051 . ', \'number\', this);return false;"><b>' . $v43dd7d0051 . '</b></a> '; } else $pf8ed4912 .= ' <a class="page-link unselected" href="' . $v6f3a2700dd . '&' . $this->data['page_attr_name'] . '=' . $v43dd7d0051 . '" onClick="submitNewPageIn_' . $v90cd343a76 . '(' . $v43dd7d0051 . ', \'number\', this);return false;" style="color:#000;text-decoration:none;">' . $v43dd7d0051 . '</a> '; $pf8ed4912 .= '</li>'; } if ($pdcf33c9e < $this->data['num_pages']) { $v080cf38d89 = $pdcf33c9e + $v666395d036; $v080cf38d89 = $v080cf38d89 > $this->data['num_pages'] ? $this->data['num_pages'] : $v080cf38d89; $pf8ed4912 .= '<li class="page-item"><a class="page-link next" href="' . $v6f3a2700dd . '&' . $this->data['page_attr_name'] . '=' . $v080cf38d89 . '" onClick="submitNewPageIn_' . $v90cd343a76 . '(' . $v080cf38d89 . ', \'next\', this);return false;">' . $v920681538d . ' ' . $v666395d036 . ' &gt;</a></li>'; } $pf8ed4912 .= '</ul>
			</nav>
			<!-----  End of Pagination Code ----->'; } return $pf8ed4912; } public function getBootstrap1Css() { return '.paging > .pagination {margin:0; display:block; text-align:center;}
.paging > .pagination .page-item {display:inline-block;}
.paging > .pagination .page-link {height:2.5em !important; position: relative; display: inline-block; padding: 0.5rem 0.75rem; margin-left: -1px; line-height: 1.25em; color: #007bff; background-color: #fff; border: 1px solid #dee2e6; white-space:nowrap; text-decoration: none; box-sizing: border-box; font-size: 1em; font-family:Verdana, Arial, sans-serif; vertical-align: middle; border-radius:0;}
.paging > .pagination select.page-link {padding-top:0.45rem; padding-right:0.3rem;}
.paging > .pagination .page-item:first-child .page-link {border-top-left-radius:.25rem; border-bottom-left-radius:.25rem;}
.paging > .pagination .page-item:last-child .page-link {border-top-right-radius:.25rem; border-bottom-right-radius:.25rem;}'; } private function f32b570165e($v90cd343a76, $v6f3a2700dd, $v1598b4cc2b) { $v067674f4e4 = '<script>
		function submitNewPageIn_' . $v90cd343a76 . '(page_num, type, elm) {
			var pg_status = true;
			var func = ' . ($this->data['on_click_js_func'] ? $this->data['on_click_js_func'] : "null") . ';
			
			if (typeof func == "function")
				pg_status = func("' . $this->data['page_attr_name'] . '", page_num, type, "' . $v90cd343a76 . '", elm);
			
			if (pg_status) {
				var url = \'' . addcslashes($v6f3a2700dd, "\\'") . '&' . $this->data['page_attr_name'] . '=\' + page_num;'; if ($v1598b4cc2b) $v067674f4e4 .= '
				var oForm = document.createElement("FORM");
				oForm.setAttribute("name", "' . $v90cd343a76 . '");
				oForm.setAttribute("method", "post");
				oForm.setAttribute("action", url);
				$(oForm).html(\'' . addcslashes($v1598b4cc2b, "\\'") . '\');
				$("body").append(oForm);
				
				return oForm.submit();'; else $v067674f4e4 .= '
				document.location = url;'; $v067674f4e4 .= '
			}
			
			return false;
		}
		</script>'; return $v067674f4e4; } } ?>
