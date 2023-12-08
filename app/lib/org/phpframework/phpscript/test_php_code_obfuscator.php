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
 include __DIR__ . "/PHPCodeObfuscator.php"; $files_settings = array( __DIR__ . "/sample_php_code_obfuscator.php" => array( 0 => array( "foo" => array( "obfuscate_name" => 0, "obfuscate_code" => 1, ), "d" => array( "obfuscate_name" => 0, "obfuscate_code" => 1, "strip_encapsed_string_eol" => 0, ), "\$p" => 0, "\$o" => 1, "\$func" => 1, "\$w" => 0, "\$q" => array("obfuscate_encapsed_string" => 1, "strip_encapsed_string_eol" => 1), ), 1 => array( "save_path" => "/tmp/sample_php_code_obfuscator.php", "obfuscate_encapsed_string" => 1, ), "Foo" => array( "obfuscate_name" => 0, "properties" => array( "\$x" => 0, "\$bar1" => array("obfuscate_name" => 1, "obfuscate_encapsed_string" => 1), "\$bar2" => 0, "\$bar3" => 1, "bar4" => 0, "\$a" => 0, "\$p" => 0, ), "methods" => array( "__construct" => array("obfuscate_code" => 1, "obfuscate_encapsed_string" => 1, "objects_methods_or_vars" => array("getName")), "bar1" => array("obfuscate_name" => 1, "obfuscate_code" => 1), "bar2" => array("obfuscate_name" => 0, "obfuscate_code" => 1), "getClass" => array("obfuscate_name" => 1, "obfuscate_code" => 1), ), ), "I" => array( "obfuscate_name" => 1, "all_methods" => array("obfuscate_name" => 1), ), "X" => array( "obfuscate_name" => 0, "properties" => array( "\$y" => 1, ), "methods" => array( "y" => array("obfuscate_name" => 1, "obfuscate_code" => 1), "t" => array("obfuscate_name" => 0, "obfuscate_code" => 1), "funcWithEval" => array("obfuscate_name" => 1, "obfuscate_code" => 1, "obfuscate_encapsed_string" => 1, "ignore_local_variables" => array('$other'), "objects_methods_or_vars" => array("getName")), "getName" => array("obfuscate_name" => 1), "cloneTask" => array("obfuscate_code" => 1, "obfuscate_encapsed_string" => 1), ), ), "W" => array( "obfuscate_name" => 1, ), ), ); $options = array("plain_encode" => 0, "strip_comments" => 1, "strip_doc_comments" => 1, "strip_eol" => 0); $PHPCodeObfuscator = new PHPCodeObfuscator($files_settings); $status = $PHPCodeObfuscator->obfuscateFiles($options); echo "STATUS:$status\n"; echo $PHPCodeObfuscator->getIncludesWarningMessage(); ?>
