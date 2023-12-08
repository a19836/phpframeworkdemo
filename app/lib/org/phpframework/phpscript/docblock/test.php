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
include get_lib("org.phpframework.phpscript.docblock.DocBlockParser"); foo(); $DocBlockParser = new DocBlockParser(); $DocBlockParser->ofFunction("bar"); $input = array( "id" => 1, "age" => "as", "full_name" => null, "options" => array( "no_cache" => null ), ); $output = "asd"; $status1 = $DocBlockParser->checkInputMethodAnnotations($input); $status2 = $DocBlockParser->checkOutputMethodAnnotations($output); echo "Status:$status1:$status2"; print_r($DocBlockParser->getObjects()); print_r($input); print_r($DocBlockParser->getTagParamsErrors()); print_r($DocBlockParser->getTagReturnErrors()); function foo() { function ma0144dcc62d2 ($v1cbfbb49c5, $v7591e93685, $v0c48c64def, $v5d3813882f = false) {} function mbde52cd6fb24 () {} function f7aeaf992f5 () {} function f9a8b7dc209 () {} function f5d3f7b52bb () {} } function get_lib($pa32be502) { $v333a329170 = dirname(dirname(dirname(dirname(__DIR__)))) . "/"; return $v333a329170 . str_replace(".", "/", $pa32be502) . ".php"; } function launch_exception($paec2c009) { throw $paec2c009; } ?>
