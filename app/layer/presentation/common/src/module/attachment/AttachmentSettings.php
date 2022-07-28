<?php
/*$t = array_keys(MimeTypeHandler::getAvailableTypesByMimeType(array("image", "text", "video")));
sort($t);
foreach($t as $i) {
	echo "$i;";
}*/
include_once dirname(__DIR__) . "/common/CommonSettings.php";

class AttachmentSettings extends CommonSettings {
	const ATTACHMENTS_ABSOLUTE_FOLDER_PATH = '';
	const ATTACHMENTS_RELATIVE_FOLDER_PATH = 'files';
	
	const ATTACHMENTS_URL = '';
	
	//const ALLOWED_MIME_TYPES = '';
	const ALLOWED_MIME_TYPES = 'application/x-shockwave-flash;image/bmp;image/cgm;image/g3fax;image/gif;image/ief;image/jpeg;image/ktx;image/png;image/prs.btif;image/svg+xml;image/tiff;image/vnd.adobe.photoshop;image/vnd.dece.graphic;image/vnd.djvu;image/vnd.dvb.subtitle;image/vnd.dwg;image/vnd.dxf;image/vnd.fastbidsheet;image/vnd.fpx;image/vnd.fst;image/vnd.fujixerox.edmics-mmr;image/vnd.fujixerox.edmics-rlc;image/vnd.ms-modi;image/vnd.net-fpx;image/vnd.wap.wbmp;image/vnd.xiff;image/webp;image/x-cmu-raster;image/x-cmx;image/x-freehand;image/x-icon;image/x-pcx;image/x-pict;image/x-portable-anymap;image/x-portable-bitmap;image/x-portable-graymap;image/x-portable-pixmap;image/x-rgb;image/x-xbitmap;image/x-xpixmap;image/x-xwindowdump;text/calendar;text/css;text/csv;text/html;text/n3;text/plain;text/plain-bas;text/prs.lines.tag;text/richtext;text/sgml;text/tab-separated-values;text/troff;text/turtle;text/uri-list;text/vnd.curl;text/vnd.curl.dcurl;text/vnd.curl.mcurl;text/vnd.curl.scurl;text/vnd.fly;text/vnd.fmi.flexstor;text/vnd.graphviz;text/vnd.in3d.3dml;text/vnd.in3d.spot;text/vnd.sun.j2me.app-descriptor;text/vnd.wap.wml;text/vnd.wap.wmlscript;text/x-asm;text/x-c;text/x-fortran;text/x-java-source.java;text/x-pascal;text/x-setext;text/x-uuencode;text/x-vcalendar;text/x-vcard;text/yaml;video/3gpp;video/3gpp2;video/h261;video/h263;video/h264;video/jpeg;video/jpm;video/mj2;video/mp4;video/mpeg;video/ogg;video/quicktime;video/vnd.dece.hd;video/vnd.dece.mobile;video/vnd.dece.pd;video/vnd.dece.sd;video/vnd.dece.video;video/vnd.fvt;video/vnd.mpegurl;video/vnd.ms-playready.media.pyv;video/vnd.uvvu.mp4;video/vnd.vivo;video/webm;video/x-f4v;video/x-fli;video/x-flv;video/x-m4v;video/x-ms-asf;video/x-ms-wm;video/x-ms-wmv;video/x-ms-wmx;video/x-ms-wvx;video/x-msvideo;video/x-sgi-movie;application/pdf;application/msword;application/msword;application/vnd.openxmlformats-officedocument.wordprocessingml.document;application/vnd.openxmlformats-officedocument.wordprocessingml.template;application/vnd.ms-word.document.macroEnabled.12;application/vnd.ms-word.template.macroEnabled.12;application/vnd.ms-excel;application/vnd.ms-excel;application/vnd.ms-excel;application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;application/vnd.openxmlformats-officedocument.spreadsheetml.template;application/vnd.ms-excel.sheet.macroEnabled.12;application/vnd.ms-excel.template.macroEnabled.12;application/vnd.ms-excel.addin.macroEnabled.12;application/vnd.ms-excel.sheet.binary.macroEnabled.12;application/vnd.ms-powerpoint;application/vnd.ms-powerpoint;application/vnd.ms-powerpoint;application/vnd.ms-powerpoint;application/vnd.openxmlformats-officedocument.presentationml.presentation;application/vnd.openxmlformats-officedocument.presentationml.template;application/vnd.openxmlformats-officedocument.presentationml.slideshow;application/vnd.ms-powerpoint.addin.macroEnabled.12;application/vnd.ms-powerpoint.presentation.macroEnabled.12;application/vnd.ms-powerpoint.template.macroEnabled.12;application/vnd.ms-powerpoint.slideshow.macroEnabled.12;application/x-7z-compressed;application/x-bzip;application/x-bzip2;application/zip;application/x-rar-compressed;';
	const DENIED_MIME_TYPES = '';
	
	const ALLOWED_EXTENSIONS = '';
	const DENIED_EXTENSIONS = 'sh;asp;cgi;php;php3;ph3;php4;ph4;php5;ph5;phtm;phtml;';
}
?>
