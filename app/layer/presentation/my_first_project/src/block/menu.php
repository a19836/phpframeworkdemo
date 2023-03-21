<?php
$block_id = $EVC->getCMSLayer()->getCMSBlockLayer()->getBlockIdFromFilePath(__FILE__);//must be the same than this file name.

$block_settings[$block_id] = array(
	"type" => "",
	"class" => "menu",
	"title" => "",
	"items_type" => "",
	"menus" => array(
		array(
			"label" => "Schools",
			"attrs" => "",
			"url" => "{$project_url_prefix}schools",
			"title" => "",
			"class" => "",
			"previous_html" => "",
			"next_html" => "",
		),
		array(
			"label" => "Teachers",
			"attrs" => "",
			"url" => "{$project_url_prefix}teachers",
			"title" => "",
			"class" => "",
			"previous_html" => "",
			"next_html" => "",
		),
		array(
			"label" => "Students",
			"attrs" => "",
			"url" => "{$project_url_prefix}students",
			"title" => "",
			"class" => "",
			"previous_html" => "",
			"next_html" => "",
		),
	),
	"menu_query_type" => "user_defined",
	"menu_group_id" => "null",
	"tags" => "",
	"object_type_id" => 1,
	"object_id" => "",
	"group" => "",
	"item_label" => "#label#",
	"item_attrs" => "#attrs#",
	"item_url" => "{$project_url_prefix}#url#",
	"item_title" => "#title#",
	"item_class" => "#class#",
	"item_previous_html" => "#previous_html#",
	"item_next_html" => "#next_html#",
	"style_type" => "",
	"block_class" => "",
	"template_type" => "user_defined",
	"ptl" => array(
		"code" => "<ptl:if !function_exists(\"getmenushtml_509\")>
	<!--ul-->
	<ptl:function:getmenushtml_509 menus>
		<ptl:if is_array(\$menus)>
			<ptl:foreach \$menus i item>
				<li class=\"module_menu_item_class <ptl:echo \$item[class]></li>\" title=\"<ptl:echo \$item[title]></ptl:echo>\" 
					<ptl:echo \$item[attrs]/>
					>
					<ptl:echo \$item[previous-html]/>
					<a class=\"nav-link pb-0\" href=\"<ptl:if \$item[url]><ptl:echo \$item[url]></ptl:echo><ptl:else><ptl:echo 'javascript:void(0)'></ptl:echo></ptl:if>\">
						<span class=\"sb-nav-link-icon\">
							<i class=\"bi bi-card-list mr-2 me-2\"></i>
						</span>
						<span class=\"small\">
							<ptl:echo \$item[label]/>
						</span>
					</a>
					<ptl:if is_array(\$item[menus])>
						<ul>
							<ptl:getmenushtml_509 \$item[menus]/>
						</ul>
						<ptl:echo \$item[next-html]/>
					</ptl:if>
				</li>
			</ptl:foreach>
		</ptl:if>
		<!--/ul-->
	</ptl:function:getmenushtml_509>
	<ul class=\"menu_main_ul\">
		<ptl:getmenushtml_509 \$input/>
	</ul>
</ptl:if>",
	),
	"menu_background_color" => "",
	"menu_text_color" => "inherit",
	"menu_background_image" => "",
	"sub_menu_background_color" => "",
	"sub_menu_text_color" => "",
	"sub_menu_background_image" => "",
	"css" => ".menu ul {
    padding:0;
    list-style:none;
}",
	"js" => "",
);

$EVC->getCMSLayer()->getCMSBlockLayer()->createBlock("menu/show_menu", $block_id, $block_settings[$block_id]);
?>