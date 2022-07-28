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
	 */ if(!String.prototype.startsWith){String.prototype.startsWith = function(searchString, position){position = position || 0;return this.indexOf(searchString, position)=== position;};}var MyHtmlBeautify ={alert_errors : false, indent : "", single_html_tags : ["meta", "base", "basefont", "input", "img", "link", "br", "wbr", "hr", "frame", "area", "source", "track", "circle", "col", "embed", "param"], single_ptl_tags : ["definevar", "var", "incvar", "decvar", "joinvar", "concatvar", "echo", "print", "return", "break", "die", "require", "include", "include_once", "require_once", "throw", "code"], dec_prefix_ptl_tags : ["elseif", "else", "catch", "case", "default"], close_single_ptl_tags : false, beautify : function(html, options){if(!location.hostname ||("localhost,jplpinto.localhost,demo.localhost,phpframework.localhost,phpframework.local,phpframeworkdemo.localhost,phpframeworkdemo.local,bloxtor.localhost,bloxtor.local,bloxtordemo.localhost,bloxtordemo.local,jplpinto.com,jplpinto.ddns.net,jamapinto.ddns.net,onlineitframework.com,demo.onlineitframework.com,phpframework.pt,demo.phpframework.pt,bloxtor.pt,demo.bloxtor.pt").indexOf(location.hostname)== -1)return;var new_html = "";if(html){html = "" + html;var char, tag_html, is_tag_close, is_simple_html_tag, tag_name, tag_name_lower, prev_tag_name, is_tag_dec, is_style, is_script, is_tag, is_ptl, is_short_tag, is_single_ptl_tag, prefix = "", code, code_length;var indent_size = options && parseInt(options.indent_size)>= 0 ? parseInt(options.indent_size): 1;var indent_char = options && options.indent_char ? options.indent_char : "\t";this.indent = "";for(var i = 0;i < indent_size;i++)this.indent += indent_char;var html_length = html.length;for(var i = 0;i < html_length;i++){char = html[i];if(char == '<'){if(this.isComment(html, i)){tag_name = "<!--";tag_html = this.getComment(html, i);if(new_html != "" && !new_html.match(/\n\s*$/))new_html += "\n";new_html += prefix + tag_html[0];var c = html.substr(tag_html[1] + 1);if(c != "" && !c.match(/^\s*\n/)&& !tag_html[0].match(/\n\s*$/))new_html += "\n";i = tag_html[1];}else if(this.isPHP(html, i)){tag_name = "<?";tag_html = this.getPHP(html, i);code = tag_html[0];try{if(CodeBeautifier && typeof CodeBeautifier.prettyPrint == "function"){code = CodeBeautifier.prettyPrint(code);code = code.replace(/\n/g, "\n" + prefix);}}catch(e){if(alert_errors)alert(e);if(console && typeof console.log == "function")console.log(e);}if(new_html != "" && !new_html.match(/\n\s*$/))new_html += "\n";new_html += prefix + code;var c = html.substr(tag_html[1] + 1);if(c != "" && !c.match(/^\s*\n/)&& !code.match(/\n\s*$/))new_html += "\n";i = tag_html[1];}else if(!this.isTagHtml(html, i)){new_html += char;tag_name = "";}else{is_tag = true;is_ptl = this.isPTL(html, i);tag_html = is_ptl ? this.getPTL(html, i): this.getTagHtml(html, i, prefix + this.indent);code = tag_html[0];if(code){tag_name = is_ptl ? this.getPTLTagName(code, 0): this.getTagName(code, 0);tag_name_lower = tag_name.toLowerCase();i = tag_html[1];code_length = code.length;is_tag_close = code.substr(0, 2)== "</";is_tag_dec = this.isDecrementPrefixPTLTag(code);is_simple_html_tag = this.isSingleHtmlTag(code);is_short_tag = code.substr(code_length - 2)== "/>";is_single_ptl_tag = this.isSinglePTLTag(code);is_style = tag_name_lower == "style";is_script = tag_name_lower == "script";is_textarea = tag_name_lower == "textarea";if(is_tag_close || is_tag_dec)prefix = prefix.substr(0, prefix.length - 1);if(is_ptl && !is_tag_close && this.close_single_ptl_tags){if(is_short_tag)code = code.substr(0, code_length - 2)+ "></" + tag_name + ">";else if(is_single_ptl_tag){var next_html = html.substr(i + 1).replace(/^\s+/g, "");var patt = new RegExp("^</" + tag_name + "( |>)", "i");if(!patt.test(next_html))code = code.substr(0, code_length - 1)+ "></" + tag_name + ">";}code_length = code.length;}if(is_tag_close && is_textarea)new_html += code;else{if(new_html.match(/\n\s+$/))new_html = new_html.substr(0, new_html.lastIndexOf("\n"));if(is_tag_close && tag_name && tag_name == prev_tag_name){new_html += code;tag_name = "";}else new_html +=(!new_html || new_html.substr(new_html.length - 1)== "\n" ? "" : "\n")+ prefix + code;}if(!is_tag_close && code.substr(code_length - 1)== ">" && !is_short_tag && !is_single_ptl_tag && !is_simple_html_tag)prefix += this.indent;if(!is_tag_close &&(is_style || is_script || is_textarea)){tag_html = this.getNonParseInnerTagsNodeContent(html, i + 1, tag_name);code = tag_html[0];i = tag_html[1];try{if(is_style && typeof css_beautify == "function")code = css_beautify(code,{'indent_size' : indent_size, 'indent_char' : indent_char});if(is_script && typeof js_beautify == "function")code = js_beautify(code,{'indent_size' : indent_size, 'indent_char' : indent_char});code =("" + code).replace(/\{\s*(\\?)\s*(\$[\w\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u024F\u1EBD\u1EBC"' \-\+\[\]\.\$]+)\s*\}/g, "\{$1$2\}");}catch(e){if(alert_errors)alert(e);if(console && typeof console.log == "function")console.log(e);}new_html +=(is_textarea ? "" : "\n")+ code;if(!is_textarea)tag_name = "";}}else i = html_length;}prev_tag_name = tag_name;}else if(char != "\n" && char != "\r" && char != "\t"){var is_multiple_spaces = char == " " && new_html.substr(new_html.length - 1)== " ";if(!is_multiple_spaces)new_html +=(is_tag ?(!new_html.match(/\n\s*$/)? "\n" : "")+ prefix : "")+ char;if(char != " ")prev_tag_name = "";is_tag = false;}}}return new_html;}, getTagHtml : function(html, idx, prefix){var tag_html = html[idx];var char, attr, ptl = [], php = [], comment = [], odq = false, osq = false, is_tag_close, is_tag_dec;for(var i = idx + 1;i < html.length;i++){char = html[i];if(char == '"' && !osq && !this.isCharEscaped(html, i))odq = !odq;else if(char == "'" && !odq && !this.isCharEscaped(html, i))osq = !osq;else if(char == "<"){if(this.isComment(html, i)){comment = this.getComment(html, i);i = comment[1];tag_html += comment[0];continue;}else if(this.isPHP(html, i)){php = this.getPHP(html, i);i = php[1];tag_html += php[0];continue;}else if(this.isPTL(html, i)){ptl = this.getPTL(html, i);i = ptl[1];is_tag_close = ptl[0].substr(0, 2)== "</";is_tag_dec = this.isDecrementPrefixPTLTag(ptl[0]);if(is_tag_close || is_tag_dec)prefix = prefix.substr(0, prefix.length - 1);tag_html +=(tag_html.substr(tag_html.length - 1)== "\n" ? "" : "\n")+ prefix + ptl[0];if(!is_tag_close && ptl[0].substr(ptl[0].length - 1)== ">" && ptl[0].substr(ptl[0].length - 2)!= "/>" && !this.isSinglePTLTag(ptl[0]))prefix += this.indent;continue;}}if(char != "\n" && char != "\r" && char != "\t"){tag_html +=(ptl[0] ? "\n" + prefix : "")+ char;ptl = [];}if(char == ">" && !odq && !osq)break;else if((char == '"' && odq)||(char == "'" && osq)){attr = this.getAttribute(html, i + 1, char);tag_html += attr[0];i = attr[1];}}return [tag_html, i];}, getAttribute : function(html, idx, delimiter){var char, ptl;for(var i = idx;i < html.length;i++){char = html[i];if(char == '"' && delimiter == char && !this.isCharEscaped(html, i))break;else if(char == "'" && delimiter == char && !this.isCharEscaped(html, i))break;if(char == "<"){if(this.isPTL(html, i)){ptl = this.getPTL(html, i);i = ptl[1];}else if(this.isPHP(html, i)){php = this.getPHP(html, i);i = php[1];}}}var attr = i == html.length ? html.substr(idx): html.substr(idx, i - idx);return [attr, i == html.length ? i : i - 1];}, getPTL : function(html, idx){var res = this.getNodeContent(html, idx, ">", "php");var i = res[1] + 1 < html.length ? res[1] + 1 : html.length;var ptl = res[0] + html.substr(res[1] + 1, 1);return [ptl, i];}, getPHP : function(html, idx){var res = this.getNodeContent(html, idx, "?>");var i = res[1] + 2 < html.length ? res[1] + 2 : html.length;var php = res[0] + html.substr(res[1] + 1, 2);return [php, i];}, getComment : function(html, idx){var end = html.indexOf("-->", idx + 1);var comment = end > idx ? html.substr(idx, end + 3 - idx): html.substr(idx);idx = end > idx ? end + 2 : html.length;return [comment, idx];}, getAttributesContent : function(html, idx, stop_str){return this.getNodeContent(html, idx, stop_str);}, getNonParseInnerTagsNodeContent : function(html, idx, tag_name){return this.getNodeContent(html, idx, "</" + tag_name, ["php", "ptl"], tag_name.toLowerCase()== "textarea");}, getNodeContent : function(html, idx, stop_str, ignore_elements, is_textarea){var char, odq = false, osq = false;stop_str = stop_str.toLowerCase();ignore_elements = ignore_elements ?(Array.isArray(ignore_elements)? ignore_elements : [ignore_elements]): [];for(var i = idx;i < html.length;i++){char = html[i];if(char == '"' && !is_textarea && !osq && !this.isCharEscaped(html, i))odq = !odq;else if(char == "'" && !is_textarea && !odq && !this.isCharEscaped(html, i))osq = !osq;else if(char == "<" && ignore_elements){if(ignore_elements.indexOf("ptl")!= -1 && this.isPTL(html, i)){ptl = this.getPTL(html, i);i = ptl[1];}else if(ignore_elements.indexOf("php")!= -1 && this.isPHP(html, i)){php = this.getPHP(html, i);i = php[1];}}if(char == stop_str[0] && !odq && !osq && html.substr(i, stop_str.length).toLowerCase()== stop_str)break;}var content = i == html.length ? html.substr(idx): html.substr(idx, i - idx);idx = i == html.length ? i : i - 1;return [content, idx];}, getTextContent : function(html, idx){var i = idx - 1;do{i = html.indexOf("<", i + 1);if(i == -1)i = html.length;else if(this.isPTL(html, i)|| this.isPHP(html, i)|| this.isComment(html, i)|| this.isTagHtml(html, i))break;}while(i < html.length);var content = i == html.length ? html.substr(idx): html.substr(idx, i - idx);idx = i == html.length ? i : i - 1;return [content, idx];}, getTagContent : function(html, idx, tag_name){var char, is_same_tag = false, is_no_parse_tags = false, is_single_tag = false, is_open_tag = false, is_close_tag = false, inner_repeated_tags = 0, tag_html, code, tn, tnl, orig_i, outer_start_pos, outer_end_pos, inner_start_pos, inner_end_pos;tag_name = tag_name.toLowerCase();for(var i = idx;i < html.length;i++){char = html[i];if(char == "<"){if(this.isComment(html, i)){tag_html = this.getComment(html, i);i = tag_html[1];}else if(this.isPHP(html, i)){tag_html = this.getPHP(html, i);i = tag_html[1];}else if(this.isPTL(html, i)){tag_html = this.getPTL(html, i);i = tag_html[1];}else if(this.isTagHtml(html, i)){orig_i = i;tag_html = this.getTagHtml(html, i, "");code = tag_html[0];i = tag_html[1];tn = this.getTagName(code, 0);tnl = tn.toLowerCase();is_same_tag = tnl == tag_name;is_no_parse_tags = tnl == "style" || tnl == "script" || tnl == "textarea";if(is_same_tag){is_close_tag = code[1] == "/";is_single_tag = code[ code.length - 2 ] == "/";if(is_single_tag){if(inner_repeated_tags == 0){outer_start_pos = orig_i;outer_end_pos = inner_start_pos = inner_end_pos = i;break;}}else if(is_close_tag){--inner_repeated_tags;if(inner_repeated_tags == 0){outer_end_pos = i;inner_end_pos = i - code.length;break;}}else{if(inner_repeated_tags == 0){outer_start_pos = orig_i;inner_start_pos = i + 1;}if(!is_no_parse_tags)inner_repeated_tags++;}}else if(is_no_parse_tags){tag_html = this.getNonParseInnerTagsNodeContent(html, i + 1, tn);i = html.indexOf(">", tag_html[1] +("</" + tnl).length + 1);}}}}if(is_same_tag){var inner_content = inner_end_pos >= 0 ? html.substr(inner_start_pos, inner_end_pos - inner_start_pos + 1): html.substr(inner_start_pos);var outer_content = outer_end_pos >= 0 ? html.substr(outer_start_pos, outer_end_pos - outer_start_pos + 1): html.substr(outer_start_pos);idx = outer_end_pos >= 0 ? outer_end_pos : html.length;return [inner_content, idx, outer_content];}return null;}, getTagName : function(html, idx){var m = html.substr(idx).match(/^<\/?([a-z0-9\-_]+)/i);m = m ? m : html.substr(idx).match(/^<!([a-z0-9\-_]+)/i);m = m[0].substr(1);if(m.substr(0, 1)== "/")m = m.substr(1);return m;}, getPTLTagName : function(html, idx){var m = html.substr(idx).match(/^<\/?(php|ptl|\?):([a-z0-9])([^ >\/])*/i);if(!m)m = html.substr(idx).match(/^<\/?(php|ptl|\?):([^ >\/])*/i);m = m[0].substr(1);if(m.substr(0, 1)== "/")m = m.substr(1);return m;}, isTagHtml : function(html, idx){return /^<\/?[a-z]+/i.test(html.substr(idx, 5));}, isPTL : function(html, idx){return /^<\/?(php|ptl|\?):([a-z0-9])/i.test(html.substr(idx));}, isPHP : function(html, idx){return /^<\?(php|=)?(\s+|\$|"|'|[0-9])/.test(html.substr(idx, 10));}, isComment : function(html, idx){return /^<!--/.test(html.substr(idx));}, isSingleHtmlTag : function(html){var matches = /^<(\w+)/.exec(html);var tag = matches ? matches[1].toLowerCase(): null;return tag && this.single_html_tags.indexOf(tag)!= -1;}, isSinglePTLTag : function(ptl){var matches = /^<\/?(php|ptl|\?):(.+)$/i.exec(ptl);var tag = matches ? matches[2] : null;if(tag)for(var k in this.single_ptl_tags)if(tag.startsWith(this.single_ptl_tags[k]))return true;return false;}, isDecrementPrefixPTLTag : function(ptl){var matches = /^<\/?(php|ptl|\?):(.+)$/i.exec(ptl);var tag = matches ? matches[2] : null;if(tag)for(var k in this.dec_prefix_ptl_tags)if(tag.startsWith(this.dec_prefix_ptl_tags[k]))return true;return false;}, isCharEscaped : function(str, idx){var escaped = false;for(var i = idx - 1;i >= 0;i--){if(str[i] == "\\")escaped = !escaped;else break;}return escaped;},}