<?php 
//TEMPLATE PARAMS:
$EVC->getCMSLayer()->getCMSTemplateLayer()->setParam("Page Title", "This is the default template...");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="icon" href="data:;base64,=" />
</head>
<body>
	<div class="menu" style="background-color: rgb(0, 0, 0); color: rgb(255, 255, 255);">
		<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion("Menu"); ?>
	</div>
	<div class="content" style="padding: 20px;">
		<h1 class="title" style="text-align:center">
			<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->getParam("Page Title"); ?>
		</h1>
		
		<?= $EVC->getCMSLayer()->getCMSTemplateLayer()->renderRegion("Content"); ?>
	</div>
</body>
</html>
