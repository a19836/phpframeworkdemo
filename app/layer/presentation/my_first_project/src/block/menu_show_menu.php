<?php
$block_id = $EVC->getCMSLayer()->getCMSBlockLayer()->getBlockIdFromFilePath(__FILE__);//must be the same than this file name.

$block_settings[$block_id] = array(
	"type" => "",
	"class" => "",
	"title" => "",
	"items_type" => "",
	"menus" => array(
		array(
			"label" => "Go to google",
			"attrs" => "",
			"url" => "//google.com",
			"title" => "",
			"class" => "",
			"previous_html" => "",
			"next_html" => "",
		),
		array(
			"label" => "Go to bloxtor",
			"attrs" => "",
			"url" => "//bloxtor.com",
			"title" => "",
			"class" => "",
			"previous_html" => "",
			"next_html" => "",
		),
	),
	"menu_query_type" => "selected_menu",
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
	"template_type" => "",
	"ptl" => array(
		"code" => "<ptl:if !function_exists(\"getMenusHTML_36\")>
	<!--ul-->
	
	<ptl:function:getMenusHTML_36 menus>
		<ptl:if is_array(\$menus)>
			<ptl:foreach \$menus i item>
				<li class=\"module_menu_item_class <ptl:echo \$item[class]/>\" title=\"<ptl:echo \$item[title]/>\" <ptl:echo \$item[attrs]/> >
					<ptl:echo \$item[previous-html]/>
					
					<a href=\"<ptl:if \$item[url]><ptl:echo \$item[url]/><ptl:else><ptl:echo 'javascript:void(0)'/></ptl:if>\">
						<label><ptl:echo \$item[label]/></label>
					</a>
					
					<ptl:if is_array(\$item[menus])>
						<ul>
							<ptl:getMenusHTML_36 \$item[menus]>
						</ul>
					</ptl:if>
					
					<ptl:echo \$item[next-html]/>
				</li>
			</ptl:foreach>
		</ptl:if>
	</ptl:function>
	
	<!--/ul-->
</ptl:if>

<ul class=\"menu_main_ul\">
	<ptl:getMenusHTML_36 \$input>
</ul>",
	),
	"menu_background_color" => "",
	"menu_text_color" => "",
	"menu_background_image" => "",
	"sub_menu_background_color" => "",
	"sub_menu_text_color" => "",
	"sub_menu_background_image" => "",
	"css" => "",
	"js" => "",
);

$EVC->getCMSLayer()->getCMSBlockLayer()->createBlock("menu/show_menu", $block_id, $block_settings[$block_id]);
?>