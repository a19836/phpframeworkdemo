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
?><html>
<head>
	<title> 500 Server Error</title>
</head>
<body style="text-align:center; background-color:#F7F7F7; ">
<h2>Sorry... Server Error!</h2>
<h2>Server Error - The server detected a syntax error in the client's request...</h2>
<?php
$ip = getenv ("REMOTE_ADDR"); $requri = getenv ("REQUEST_URI"); $servname = getenv ("SERVER_NAME"); $combine = "IP: <b>" . $ip . "</b> tried to load <b>http://" . $servname . $requri . "</b>"; $httpref = getenv ("HTTP_REFERER"); $httpagent = getenv ("HTTP_USER_AGENT"); $today = date("D M j Y g:i:s a T"); $message = "($today) \n
<br><br>
$combine, with the following navigator:<br> \n
User Agent = $httpagent \n<br> \n
Requested File = $requested_file \n
<h2> $note </h2>\n"; echo $message; $EVC->setTemplate("empty"); ?>
</body>
</html>
