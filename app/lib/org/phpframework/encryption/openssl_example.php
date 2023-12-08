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
 include __DIR__ . "/OpenSSLCipherHandler.php"; $salt = "some string here. whatever you want!!!"; $text = "some message to be encrypted"; echo "\n**** SIMPLE TEXT TO ENCRYPT ****"; $cipher_text = OpenSSLCipherHandler::encryptText($text, $salt); $decrypted_text = OpenSSLCipherHandler::decryptText($cipher_text, $salt); echo "
salt: $salt
text: $text
cipher_text: $cipher_text
decrypted_text: $decrypted_text
"; echo "\n**** ARRAY TO ENCRYPT ****"; $var = array( "text1" => "some message 1 to be encrypted", "text2" => "some text 2 to be encrypted", ); $cipher_var = OpenSSLCipherHandler::encryptVariable($var, $salt); $decrypted_var = OpenSSLCipherHandler::decryptVariable($cipher_var, $salt); echo "\nsalt: $salt"; echo "\nvar:";print_r($var); echo "\ncipher_var:";print_r($cipher_var); echo "\ndecrypted_var:";print_r($decrypted_var); ?>
