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
$popup = isset($_GET["popup"]) ? $_GET["popup"] : null; $username = isset($_GET["username"]) ? $_GET["username"] : ""; $password = isset($_GET["password"]) ? $_GET["password"] : ""; $agreement = !empty($_COOKIE["lla"]) ? 1 : 0; if ($_POST) { $username = isset($_POST["username"]) ? $_POST["username"] : null; $password = isset($_POST["password"]) ? $_POST["password"] : null; $agreement = isset($_POST["agreement"]) ? $_POST["agreement"] : null; if (empty($username) || empty($password)) $error_message = "Username or Password cannot be undefined. Please try again..."; else if (empty($agreement)) $error_message = "You must accept the terms and conditions in order to proceed. Please try again..."; else if ($UserAuthenticationHandler->isUserBlocked($username)) $error_message = "You attempted to login multiple times.<br/>Your user is now blocked."; else if ($UserAuthenticationHandler->login($username, $password)) { CookieHandler::setSafeCookie("lla", $agreement, 0, "/", CSRFValidator::$COOKIES_EXTRA_FLAGS); if ($popup) { echo "1"; die(); } else { $url_back = $UserAuthenticationHandler->getUrlBack(); $url_back = $UserAuthenticationHandler->validateUrlBack($url_back) ? $url_back : $project_url_prefix . "admin/"; header("Location: $url_back"); die("<script>document.location = '$url_back';</script>"); } } else { $UserAuthenticationHandler->insertFailedLoginAttempt($username); $error_message = "Username or Password invalid. Please try again..."; } } $login_data = array( "username" => $username, "password" => $password, "agreement" => $agreement, ); ?>
