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

include_once get_lib("org.phpframework.xmlfile.XMLFileParser"); include_once get_lib("org.phpframework.dbdiagram.TableDiagram"); class DBDiagramHandler { public static function parseFile($pd404993b) { $pfb662071 = XMLFileParser::parseXMLFileToArray($pd404993b); $pac4bc40a = array(); if (is_array($pfb662071["tables"][0]["childs"]["table"])) { foreach ($pfb662071["tables"][0]["childs"]["table"] as $v87a92bb1ad) { $pcadd9b50 = new TableDiagram(); $pcadd9b50->parse($v87a92bb1ad); if ($pcadd9b50->isValid()) { $pac4bc40a[] = $pcadd9b50; } else { launch_exception(new TableDiagramException(11, $pcadd9b50)); } } } $v3c76382d93 = ""; $v16ac35fd79 = count($pac4bc40a); for ($v43dd7d0051 = 0; $v43dd7d0051 < $v16ac35fd79; $v43dd7d0051++) { $pcadd9b50 = $pac4bc40a[$v43dd7d0051]; $v3c76382d93 .= $pcadd9b50->printSQL(); } return $v3c76382d93; } } ?>
