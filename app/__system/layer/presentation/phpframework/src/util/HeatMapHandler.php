<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class HeatMapHandler { public static function getHtml($peb014cfd) { $v5f5ab211b9 = strpos($peb014cfd, "jplpinto.ddns.net") !== false && strpos($peb014cfd, "/installations/") !== false; if ($v5f5ab211b9) return "
<!--script type='text/javascript'>
window.smartlook||(function(d) {
	var o=smartlook=function(){ o.api.push(arguments)},h=d.getElementsByTagName('head')[0];
	var c=d.createElement('script');o.api=new Array();c.async=true;c.type='text/javascript';
c.charset='utf-8';c.src='https://web-sdk.smartlook.com/recorder.js';h.appendChild(c);
})(document);
smartlook('init', '51ec78273cda170da574d0133c29cfc4d073c304', { region: 'eu' });
</script-->

<!-- Hotjar Tracking Code for https://jplpinto.ddns.net/ -->
<script>
(function(h,o,t,j,a,r){
	h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
	h._hjSettings={hjid:3766012,hjsv:6};
	a=o.getElementsByTagName('head')[0];
	r=o.createElement('script');r.async=1;
	r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
	a.appendChild(r);
})(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>

<script type='text/javascript'>
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src='https://www.clarity.ms/tag/'+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, 'clarity', 'script', 'mrvny1lxo8');
</script>"; } } ?>
