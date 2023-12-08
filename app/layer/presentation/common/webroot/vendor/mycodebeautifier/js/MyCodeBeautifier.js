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
	 */eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('5(1d 14.13.K!==\'G\'){14.13.K=G(){B M.o(/^\\s+|\\s+$/g,\'\')}}7 1b={1a:G(w){7 u=""+19.18;7 D="1c,16,11.W,11.S.X,15.S.X,R.W,R.H,17.H,1e.H,";u=u?u.o(/:V$/,"").U():"";D=D.o(/;/g,",").o(/:V,/g,",").U();5(!u)B;6{7 P=D.O(",");7 y=[];A(7 i=0,t=P.d;i<t;i++){7 L=P[i].o(/(^\\s+|\\s+$)/,"");5(L)y.1f(L)}5(y.d>0&&y.Z(u)==-1){7 Q=l;A(7 i=0,t=y.d;i<t;i++)5((u+",").Z("."+y[i]+",")){Q=z;T}5(!Q)B}}7 a="";7 v=z;7 k=l;7 h=l;7 e=l;7 c=l;7 q=0;7 8,j,3;7 m=1g;7 10=/<\\?(F)?\\$/g;1h((m=10.1i(w))){7 C=m.C;w=w.f(0,C+m[0].d-1)+" "+w.f(C+m[0].d-1)}7 4=a;7 J=w.o(/\\r/g,"").O("\\n");A(7 i=0;i<J.d;i++){8=J[i];5(!k&&!h&&!e&&!c)8=8.K();8+="\\n";A(j=0;j<8.d;j++){3=8[j];5(3==">"&&8[j-1]=="?"&&v&&!k&&!h&&!e&&!c)v=l;6 5(3=="<"&&8[j+1]=="?"&&!v&&!k&&!h&&!e&&!c)v=z;5(v){5(3==\'"\'&&!k&&!e&&!c&&!M.N(8,j)){h=!h;4+=3}6 5(3=="\'"&&!h&&!e&&!c&&!M.N(8,j)){k=!k;4+=3}6 5(3=="\\n"&&e){e=l;4+=3+a}6 5(c&&3=="*"&&8[j+1]=="/"){c=l;j++;4+="*/"}6 5(3=="/"&&!k&&!h&&!e&&!c&&(8[j+1]=="*"||8[j+1]=="/")){5(8[j+1]=="*"){c=z;j++;4+="/*"}6{e=z;j++;4+="//"}}6 5(!k&&!h&&!e&&!c){5(3=="{"){a+="\\t";5(j>0&&8[j-1]!=" ")4+=" ";4+=3;5(8[j+1]!="\\n")4+="\\n"+a}6 5(3=="}"){a=a?a.f(1):"";5(j>0&&8[j-1]!="\\n")4+="\\n"+a+3;6 5(4.p(/<\\?(F)?\\s*$/))4+=(4.p(/\\s+$/)?"":" ")+3;6 4=4.f(0,4.d-1)+3;5(8[j+1]!="\\n")4+="\\n"+a}6 5(3=="\\n"){5(q==0){5(4.p(/\\r?\\n\\r?\\s*\\r?\\n\\r?\\s*$/))4=4.o(/\\r?\\n\\r?\\s*$/,"")}6 5(4.p(/\\r?\\n\\r?\\s*$/))4=4.o(/\\r?\\n\\r?\\s*$/,"");4+=3+a}6 5(3=="("){q++;a+="\\t";4+=3}6 5(3==")"){q=q>1?q-1:0;a=a?a.f(1):"";5(8[j-1]=="\\n"||j==0)4=4.f(0,4.d-1)+3;6 4+=3}6 5(3==";"&&q==0){4+=3;5(8[j+1]!="\\n")4+="\\n"+a}6{7 b=8[j+1];7 9=8[j-1];5(3==","&&b!=" ")4+=3+" ";6 5(3=="&"&&b=="&"){4+=(9!=" "?" ":"")+"&&"+(8[j+2]!=" "?" ":"");j++}6 5(3=="|"&&b=="|"){4+=(9!=" "?" ":"")+"||"+(8[j+2]!=" "?" ":"");j++}6 5(3=="="||3==">"||3=="<"||3=="!"){5(3==">"&&9=="-")4+=3;6 5(3=="="&&(9=="-"||9=="+"))4+=3;6 5(3==">"&&9=="=")4+=3+(b!=" "?" ":"");6 5(3=="="&&b==">")4+=(9!=" "?" ":"")+3;6 5(3=="<"&&b=="?")4+=3;6 5(3==">"&&9=="?")4+=3;6 5(3==">")4+=(9!=" "?" ":"")+3+(b!=" "&&b!="="?" ":"");6 5(3=="<")4+=(9!=" "?" ":"")+3+(b!=" "&&b!="="?" ":"");6 5(3=="!"&&b=="=")4+=(9!=" "?" ":"")+3;6 5(3=="=")4+=(9!=" "&&9!="="&&9!="!"&&9!="<"&&9!=">"&&9!="."&&9!="?"?" ":"")+3+(b!=" "&&b!="="?" ":"");6 4+=3}6 5(3==" "&&4.f(4.d-1)=="\\n")4+="";6 4+=3}}6 4+=3}6 4+=3}}7 x=4.O("\\n");x=x[0];5(x.p(/<\\?(F)? [^\\n]/)&&!x.p(/\\?>/)){7 m=x.p(/<\\?(F)? /);7 I=m[0].d;4=4.f(0,I)+"\\n"+4.f(I)}B 4},N:G(Y,12){7 E=l;A(7 i=12-1;i>=0;i--){5(Y[i]=="\\\\")E=!E;6 T}B E},};',62,81,'|||char|new_code|if|else|var|line|previous_char|prefix|next_char|open_multiple_comments|length|open_single_comments|substr||open_double_quotes|||open_single_quotes|false|||replace|match|paranteses_count||||cd|open_php_tag|code|first_line|parsed_arr|true|for|return|index|ads|escaped|php|function|pt|pos|lines|trim|ad|this|isCharEscaped|split|arr|sd|bloxtor|ddns|break|toLowerCase|80|com|net|str|indexOf|regex|jplpinto|idx|prototype|String|jamapinto|local|jamapconsult|hostname|location|prettyPrint|MyCodeBeautifier|localhost|typeof|klubesol|push|null|while|exec'.split('|'),0,{}))
