(function(){if(typeof Math.sgn=="undefined")Math.sgn=function(a){return a==0?0:a>0?1:-1};var p={subtract:function(a,b){return{x:a.x-b.x,y:a.y-b.y}},dotProduct:function(a,b){return a.x*b.x+a.y*b.y},square:function(a){return Math.sqrt(a.x*a.x+a.y*a.y)},scale:function(a,b){return{x:a.x*b,y:a.y*b}}},y=Math.pow(2,-65),u=function(a,b){for(var g=[],d=b.length-1,h=2*d-1,f=[],c=[],l=[],k=[],i=[[1,0.6,0.3,0.1],[0.4,0.6,0.6,0.4],[0.1,0.3,0.6,1]],e=0;e<=d;e++)f[e]=p.subtract(b[e],a);for(e=0;e<=d-1;e++){c[e]=
p.subtract(b[e+1],b[e]);c[e]=p.scale(c[e],3)}for(e=0;e<=d-1;e++)for(var m=0;m<=d;m++){l[e]||(l[e]=[]);l[e][m]=p.dotProduct(c[e],f[m])}for(e=0;e<=h;e++){k[e]||(k[e]=[]);k[e].y=0;k[e].x=parseFloat(e)/h}h=d-1;for(f=0;f<=d+h;f++){c=Math.min(f,d);for(e=Math.max(0,f-h);e<=c;e++){j=f-e;k[e+j].y+=l[j][e]*i[j][e]}}d=b.length-1;k=s(k,2*d-1,g,0);h=p.subtract(a,b[0]);l=p.square(h);for(e=i=0;e<k;e++){h=p.subtract(a,t(b,d,g[e],null,null));h=p.square(h);if(h<l){l=h;i=g[e]}}h=p.subtract(a,b[d]);h=p.square(h);if(h<
l){l=h;i=1}return{location:i,distance:l}},s=function(a,b,g,d){var h=[],f=[],c=[],l=[],k=0,i,e;e=Math.sgn(a[0].y);for(var m=1;m<=b;m++){i=Math.sgn(a[m].y);i!=e&&k++;e=i}switch(k){case 0:return 0;case 1:if(d>=64){g[0]=(a[0].x+a[b].x)/2;return 1}var n,o,q;k=a[0].y-a[b].y;i=a[b].x-a[0].x;e=a[0].x*a[b].y-a[b].x*a[0].y;m=max_distance_below=0;for(o=1;o<b;o++){q=k*a[o].x+i*a[o].y+e;if(q>m)m=q;else if(q<max_distance_below)max_distance_below=q}n=k;o=i;q=e-m;n=0*o-n*1;n=1/n;m=(1*q-o*0)*n;n=k;o=i;q=e-max_distance_below;
n=0*o-n*1;n=1/n;k=(1*q-o*0)*n;if(Math.max(m,k)-Math.min(m,k)<y?1:0){c=a[b].x-a[0].x;l=a[b].y-a[0].y;g[0]=0+1*(c*(a[0].y-0)-l*(a[0].x-0))*(1/(c*0-l*1));return 1}}t(a,b,0.5,h,f);a=s(h,b,c,d+1);b=s(f,b,l,d+1);for(d=0;d<a;d++)g[d]=c[d];for(d=0;d<b;d++)g[d+a]=l[d];return a+b},t=function(a,b,g,d,h){for(var f=[[]],c=0;c<=b;c++)f[0][c]=a[c];for(a=1;a<=b;a++)for(c=0;c<=b-a;c++){f[a]||(f[a]=[]);f[a][c]||(f[a][c]={});f[a][c].x=(1-g)*f[a-1][c].x+g*f[a-1][c+1].x;f[a][c].y=(1-g)*f[a-1][c].y+g*f[a-1][c+1].y}if(d!=
null)for(c=0;c<=b;c++)d[c]=f[c][0];if(h!=null)for(c=0;c<=b;c++)h[c]=f[b-c][c];return f[b][0]},v={},z=function(a){var b=v[a];if(!b){b=[];var g=function(i){return function(){return i}},d=function(){return function(i){return i}},h=function(){return function(i){return 1-i}},f=function(i){return function(e){for(var m=1,n=0;n<i.length;n++)m*=i[n](e);return m}};b.push(new function(){return function(i){return Math.pow(i,a)}});for(var c=1;c<a;c++){for(var l=[new g(a)],k=0;k<a-c;k++)l.push(new d);for(k=0;k<
c;k++)l.push(new h);b.push(new f(l))}b.push(new function(){return function(i){return Math.pow(1-i,a)}});v[a]=b}return b},r=function(a,b){for(var g=z(a.length-1),d=0,h=0,f=0;f<a.length;f++){d+=a[f].x*g[f](b);h+=a[f].y*g[f](b)}return{x:d,y:h}},w=function(a,b,g){var d=r(a,b),h=0;b=b;for(var f=g>0?1:-1,c=null;h<Math.abs(g);){b+=0.0050*f;c=r(a,b);h+=Math.sqrt(Math.pow(c.x-d.x,2)+Math.pow(c.y-d.y,2));d=c}return{point:c,location:b}},x=function(a,b){var g=r(a,b),d=r(a.slice(0,a.length-1),b);return Math.atan((d.y-
g.y)/(d.x-g.x))};window.jsBezier={distanceFromCurve:u,gradientAtPoint:x,nearestPointOnCurve:function(a,b){var g=u(a,b);return{point:t(b,b.length-1,g.location,null,null),location:g.location}},pointOnCurve:r,pointAlongCurveFrom:function(a,b,g){return w(a,b,g).point},perpendicularToCurveAt:function(a,b,g,d){d=d==null?0:d;b=w(a,b,d);a=x(a,b.location);d=Math.atan(-1/a);a=g/2*Math.sin(d);g=g/2*Math.cos(d);return[{x:b.point.x+g,y:b.point.y+a},{x:b.point.x-g,y:b.point.y-a}]}}})();