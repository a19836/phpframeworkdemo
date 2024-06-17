<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
?><div class="inlinehtml_task_html">
	<ul>
		<li id="inlinehtml_code_editor_tab"><a href="#inlinehtml_code" onClick="InlineHTMLTaskPropertyObj.updateHtmlFromWysiwygEditor(this)">Code</a></li>
		<li id="inlinehtml_wysiwyg_editor_tab"><a href="#inlinehtml_wysiwyg" onClick="InlineHTMLTaskPropertyObj.updateHtmlFromCodeEditor(this)">WYSIWYG</a></li>
	</ul>
	
	<div id="inlinehtml_code">
		<textarea></textarea>
	</div>
	
	<div id="inlinehtml_wysiwyg">
		<textarea></textarea>
	</div>
	
	<!-- MY LAYOUT UI EDITOR -->
	<div class="layout-ui-editor reverse fixed-properties hide-template-widgets-options with_top_bar_menu">
		<ul class="menu-widgets hidden"></ul><!--  Menu widgets will be added later -->
		<div class="template-source"><textarea></textarea></div>
	</div>
	
	<textarea class="task_property_field" name="code" style="display:none"></textarea>
	
	<div class="task_property_exit" exit_id="default_exit" exit_color="#426efa"></div>
</div>
