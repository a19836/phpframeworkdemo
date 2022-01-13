var file_to_copy_or_cut = null;
var copy_or_cut_action = null;
var copy_or_cut_tree_node_id = null;

var MyFancyPopupTools = new MyFancyPopupClass();
var MyFancyPopupProjects = new MyFancyPopupClass();

function initFileTreeMenu() {
	//prepare menu tree
	mytree.init("file_tree");
	
	$("#file_tree").removeClass("hidden");
	
	initContextMenus();
}

function initContextMenus() {
	$("#file_tree li *:not(a > label)").click(function(originalEvent){
		if (originalEvent.preventDefault) originalEvent.preventDefault(); 
		else originalEvent.returnValue = false;
		
		$(".jqcontextmenu").hide();
	});
	
	var obj = null;
	
	obj = $("#file_tree .db_layers li.main_node_db");
	addLiContextMenu(obj.children("a").addClass("link"), "main_db_group_context_menu", {callback: onDBContextMenu});
	initDBContextMenu(obj);//This covers the scenario where the DB_DRIVER node is inside of the ".db_layers li.main_node_db" and ".db_layers" node
	
	obj = $("#file_tree .data_access_layers li.main_node_ibatis");
	addLiContextMenu(obj.children("a").addClass("link"), "main_ibatis_group_context_menu", {callback: onIbatisContextMenu});
	initIbatisContextMenu(obj);
	
	obj = $("#file_tree .data_access_layers li.main_node_hibernate");
	addLiContextMenu(obj.children("a").addClass("link"), "main_hibernate_group_context_menu", {callback: onHibernateContextMenu});
	initHibernateContextMenu(obj);
	
	obj = $("#file_tree .business_logic_layers li.main_node_businesslogic");
	addLiContextMenu(obj.children("a").addClass("link"), "main_business_logic_group_context_menu", {callback: onContextContextMenu});
	initContextContextMenu(obj);
	
	obj = $("#file_tree .presentation_layers li.main_node_presentation");
	addLiContextMenu(obj.children("a").addClass("link"), "main_presentation_group_context_menu", {callback: onPresentationContextMenu});
	initPresentationContextMenu(obj);
	
	obj = $("#file_tree li.main_node_dao");
	addLiContextMenu(obj.children("a").addClass("link"), "main_dao_group_context_menu", {callback: onDaoContextMenu});
	initDaoContextMenu(obj);
	
	obj = $("#file_tree li.main_node_vendor");
	addLiContextMenu(obj.children("a").addClass("link"), "main_vendor_group_context_menu", {callback: onVendorContextMenu});
	initVendorContextMenu(obj);
	
	obj = $("#file_tree li.main_node_test_unit");
	addLiContextMenu(obj.children("a").addClass("link"), "main_test_unit_group_context_menu", {callback: onTestUnitContextMenu});
	initTestUnitContextMenu(obj);
	
	obj = $("#file_tree li.main_node_other");
	addLiContextMenu(obj.children("a").addClass("link"), "main_other_group_context_menu", {callback: onVendorContextMenu});
	initOtherContextMenu(obj);
	
	var selected_menu_properties = $("#selected_menu_properties");
}

function initDBContextMenu(elm) {
	var dbs_driver = elm.find("li i.db_driver");
	var dbs_management = elm.find("li i.db_management");
	var dbs_diagram = elm.find("li i.db_diagram");
	var tables = elm.find("li i.table");
	
	dbs_driver.parent().addClass("link");
	dbs_management.parent().addClass("link");
	dbs_diagram.parent().addClass("link");
	tables.parent().addClass("link");
	
	addLiContextMenu(dbs_driver.parent(), "db_driver_context_menu", {callback: onDBContextMenu});
	addLiContextMenu(dbs_management.parent(), "db_driver_tables_context_menu", {callback: onDBContextMenu});
	addLiContextMenu(dbs_diagram.parent(), "db_diagram_context_menu", {callback: onDBContextMenu});
	addLiContextMenu(tables.parent(), "db_driver_table_context_menu", {callback: onDBContextMenu});
}

function initIbatisContextMenu(elm) {
	var folders = elm.find("li i.folder");
	var files = elm.find("li i.file");
	var queries = elm.find("li i.query");
	var maps = elm.find("li i.map");
	var undefined_files = elm.find("li i.undefined_file");
	var cms_commons_folder = elm.find("li i.cms_common");
	var cms_modules_folder = elm.find("li i.cms_module");
	var cms_programs_folder = elm.find("li i.cms_program");
	var zip_files = elm.find("li i.zip_file");
	
	folders.parent().addClass("link");
	files.parent().addClass("link");
	queries.parent().addClass("link");
	maps.parent().addClass("link");
	undefined_files.parent().addClass("link");
	cms_commons_folder.parent().addClass("link");
	cms_modules_folder.parent().addClass("link");
	cms_programs_folder.parent().addClass("link");
	zip_files.parent().addClass("link");

	addLiContextMenu(folders.parent(), "ibatis_group_context_menu", {callback: onIbatisContextMenu});
	addLiContextMenu(files.parent(), "ibatis_file_context_menu", {callback: onIbatisContextMenu});
	addLiContextMenu(queries.parent(), "item_context_menu", {callback: onIbatisContextMenu});
	addLiContextMenu(maps.parent(), "item_context_menu", {callback: onIbatisContextMenu});
	addLiContextMenu(undefined_files.parent(), "undefined_file_context_menu", {callback: onIbatisContextMenu});
	addLiContextMenu(cms_commons_folder.parent(), "ibatis_group_common_context_menu", {callback: onIbatisContextMenu});
	addLiContextMenu(cms_modules_folder.parent(), "cms_module_context_menu", {callback: onIbatisContextMenu});
	addLiContextMenu(cms_programs_folder.parent(), "ibatis_group_context_menu", {callback: onIbatisContextMenu});
	addLiContextMenu(zip_files.parent(), "zip_file_context_menu", {callback: onIbatisContextMenu});
}

function initHibernateContextMenu(elm) {
	var folders = elm.find("li i.folder");
	var files = elm.find("li i.file");
	var imports = elm.find("li i.import");
	var objs = elm.find("li i.obj");
	var queries = elm.find("li i.query");
	var relationships = elm.find("li i.relationship");
	var maps = elm.find("li i.map");
	var undefined_files = elm.find("li i.undefined_file");
	var cms_commons_folder = elm.find("li i.cms_common");
	var cms_modules_folder = elm.find("li i.cms_module");
	var cms_programs_folder = elm.find("li i.cms_program");
	var zip_files = elm.find("li i.zip_file");
	
	folders.parent().addClass("link");
	files.parent().addClass("link");
	imports.parent().addClass("link");
	objs.parent().addClass("link");
	queries.parent().addClass("link");
	relationships.parent().addClass("link");
	maps.parent().addClass("link");
	undefined_files.parent().addClass("link");
	cms_commons_folder.parent().addClass("link");
	cms_modules_folder.parent().addClass("link");
	cms_programs_folder.parent().addClass("link");
	zip_files.parent().addClass("link");

	addLiContextMenu(folders.parent(), "hibernate_group_context_menu", {callback: onHibernateContextMenu});
	addLiContextMenu(files.parent(), "hibernate_file_context_menu", {callback: onHibernateContextMenu});
	addLiContextMenu(imports.parent(), "hibernate_import_context_menu", {callback: onHibernateContextMenu});
	addLiContextMenu(objs.parent(), "hibernate_object_context_menu", {callback: onHibernateContextMenu});
	addLiContextMenu(queries.parent(), "item_context_menu", {callback: onHibernateContextMenu});
	addLiContextMenu(relationships.parent(), "item_context_menu", {callback: onHibernateContextMenu});
	addLiContextMenu(maps.parent(), "item_context_menu", {callback: onHibernateContextMenu});
	addLiContextMenu(undefined_files.parent(), "undefined_file_context_menu", {callback: onHibernateContextMenu});
	addLiContextMenu(cms_commons_folder.parent(), "hibernate_group_common_context_menu", {callback: onHibernateContextMenu});
	addLiContextMenu(cms_modules_folder.parent(), "cms_module_context_menu", {callback: onHibernateContextMenu});
	addLiContextMenu(cms_programs_folder.parent(), "hibernate_group_context_menu", {callback: onHibernateContextMenu});
	addLiContextMenu(zip_files.parent(), "zip_file_context_menu", {callback: onHibernateContextMenu});
	
	//Remove hbn_native nodes
	elm.find("li i.hbn_native").each(function(idx, node) {
		$(node).parent().parent().remove();
	});
}

function initDaoContextMenu(elm) {
	var folders = elm.find("li i.folder");
	var files = elm.find("li i.file");
	var objs_type = elm.find("li i.objtype");
	var objs_hibernate = elm.find("li i.hibernatemodel");
	var cms_commons_folder = elm.find("li i.cms_common");
	var cms_modules_folder = elm.find("li i.cms_module");
	var cms_programs_folder = elm.find("li i.cms_program");
	var zip_files = elm.find("li i.zip_file");
	
	folders.parent().addClass("link");
	files.parent().addClass("link");
	objs_type.parent().addClass("link");
	objs_hibernate.parent().addClass("link");
	cms_commons_folder.parent().addClass("link");
	cms_modules_folder.parent().addClass("link");
	cms_programs_folder.parent().addClass("link");
	zip_files.parent().addClass("link");

	addLiContextMenu(folders.parent(), "dao_group_context_menu", {callback: onDaoContextMenu});
	addLiContextMenu(files.parent(), "undefined_file_context_menu", {callback: onDaoContextMenu});
	addLiContextMenu(objs_type.parent(), "dao_file_context_menu", {callback: onDaoContextMenu});
	addLiContextMenu(objs_hibernate.parent(), "dao_file_context_menu", {callback: onDaoContextMenu});
	addLiContextMenu(cms_commons_folder.parent(), "dao_group_context_menu", {callback: onDaoContextMenu});
	addLiContextMenu(cms_modules_folder.parent(), "cms_module_context_menu", {callback: onDaoContextMenu});
	addLiContextMenu(cms_programs_folder.parent(), "dao_group_context_menu", {callback: onDaoContextMenu});
	addLiContextMenu(zip_files.parent(), "zip_file_context_menu", {callback: onDaoContextMenu});
}

function initVendorContextMenu(elm) {
	var folders = elm.find("li i.folder");
	var files = elm.find("li i.file");
	var daos = elm.find("li i.dao");
	var code_workflow_editor = elm.find("li i.code_workflow_editor");
	var code_workflow_editor_tasks = elm.find("li i.code_workflow_editor_task");
	var layout_ui_editor = elm.find("li i.layout_ui_editor");
	var layout_ui_editor_widgets = elm.find("li i.layout_ui_editor_widget");
	var test_unit = elm.find("li i.test_unit");
	var zip_files = elm.find("li i.zip_file");
	
	folders.parent().addClass("link");
	files.parent().addClass("link");
	daos.parent().addClass("link");
	code_workflow_editor.parent().addClass("link");
	code_workflow_editor_tasks.parent().addClass("link");
	layout_ui_editor.parent().addClass("link");
	layout_ui_editor_widgets.parent().addClass("link");
	test_unit.parent().addClass("link");
	zip_files.parent().addClass("link");
	
	addLiContextMenu(folders.parent(), "vendor_group_context_menu", {callback: onVendorContextMenu});
	addLiContextMenu(files.parent(), "vendor_file_context_menu", {callback: onVendorContextMenu});
	addLiContextMenu(daos.parent(), "main_dao_group_context_menu", {callback: onDaoContextMenu});
	addLiContextMenu(code_workflow_editor.parent(), "main_vendor_group_context_menu", {callback: onVendorContextMenu});
	addLiContextMenu(code_workflow_editor_tasks.parent(), "main_vendor_group_context_menu", {callback: onVendorContextMenu});
	addLiContextMenu(layout_ui_editor.parent(), "main_vendor_group_context_menu", {callback: onVendorContextMenu});
	addLiContextMenu(layout_ui_editor_widgets.parent(), "main_vendor_group_context_menu", {callback: onVendorContextMenu});
	addLiContextMenu(test_unit.parent(), "main_test_unit_group_context_menu", {callback: onTestUnitContextMenu});
	addLiContextMenu(zip_files.parent(), "zip_file_context_menu", {callback: onVendorContextMenu});
}

function initTestUnitContextMenu(elm) {
	var folders = elm.find("li i.folder");
	var files = elm.find("li i.file");
	var test_unit_objs = elm.find("li i.test_unit_obj");
	var zip_files = elm.find("li i.zip_file");
	
	folders.parent().addClass("link");
	files.parent().addClass("link");
	test_unit_objs.parent().addClass("link");
	zip_files.parent().addClass("link");

	addLiContextMenu(folders.parent(), "test_unit_group_context_menu", {callback: onTestUnitContextMenu});
	addLiContextMenu(files.parent(), "undefined_file_context_menu", {callback: onTestUnitContextMenu});
	addLiContextMenu(test_unit_objs.parent(), "test_unit_obj_context_menu", {callback: onTestUnitContextMenu});
	addLiContextMenu(zip_files.parent(), "zip_file_context_menu", {callback: onTestUnitContextMenu});
}

function initOtherContextMenu(elm) {
	var folders = elm.find("li i.folder");
	var files = elm.find("li i.file");
	var zip_files = elm.find("li i.zip_file");
	
	folders.parent().addClass("link");
	files.parent().addClass("link");
	zip_files.parent().addClass("link");
	
	addLiContextMenu(folders.parent(), "vendor_group_context_menu", {callback: onVendorContextMenu});
	addLiContextMenu(files.parent(), "vendor_file_context_menu", {callback: onVendorContextMenu});
	addLiContextMenu(zip_files.parent(), "zip_file_context_menu", {callback: onVendorContextMenu});
}

function initContextContextMenu(elm) { //business logic
	var folders = elm.find("li i.folder");
	var files = elm.find("li i.file");
	var objs = elm.find("li i.service");
	var methods = elm.find("li i.method");
	var functions = elm.find("li i.function");
	var undefined_files = elm.find("li i.undefined_file");
	var cms_commons_folder = elm.find("li i.cms_common");
	var cms_modules_folder = elm.find("li i.cms_module");
	var cms_programs_folder = elm.find("li i.cms_program");
	var zip_files = elm.find("li i.zip_file");
	
	folders.parent().addClass("link");
	files.parent().addClass("link");
	objs.parent().addClass("link");
	methods.parent().addClass("link");
	functions.parent().addClass("link");
	undefined_files.parent().addClass("link");
	cms_commons_folder.parent().addClass("link");
	cms_modules_folder.parent().addClass("link");
	cms_programs_folder.parent().addClass("link");
	zip_files.parent().addClass("link");
	
	addLiContextMenu(folders.parent(), "business_logic_group_context_menu", {callback: onContextContextMenu});
	addLiContextMenu(files.parent(), "business_logic_file_context_menu", {callback: onContextContextMenu});
	addLiContextMenu(objs.parent(), "business_logic_object_context_menu", {callback: onContextContextMenu});
	addLiContextMenu(methods.parent(), "item_context_menu", {callback: onContextContextMenu});
	addLiContextMenu(functions.parent(), "item_context_menu", {callback: onContextContextMenu});
	addLiContextMenu(undefined_files.parent(), "undefined_file_context_menu", {callback: onContextContextMenu});
	addLiContextMenu(cms_commons_folder.parent(), "business_logic_group_common_context_menu", {callback: onContextContextMenu});
	addLiContextMenu(cms_modules_folder.parent(), "cms_module_context_menu", {callback: onContextContextMenu});
	addLiContextMenu(cms_programs_folder.parent(), "business_logic_group_context_menu", {callback: onContextContextMenu});
	addLiContextMenu(zip_files.parent(), "zip_file_context_menu", {callback: onContextContextMenu});
}

function initPresentationContextMenu(elm) {
	var projects_common = elm.find("li i.project_common");
	var project_folders = elm.find("li i.project_folder");
	var projects = elm.find("li i.project");
	var folders = elm.find("li i.folder");
	var files = elm.find("li i.file");
	var entity_files = elm.find("li i.entity_file");
	var entities_folder = elm.find("li i.entities_folder");
	var entities_sub_folders = folders.parents("li").children("a").children("i.entities_folder").parent().parent().children("ul").find("li i.folder");
	var view_files = elm.find("li i.view_file");
	var views_folder = elm.find("li i.views_folder");
	var template_files = elm.find("li i.template_file");
	var template_folders = elm.find("li i.template_folder");
	var templates_folder = elm.find("li i.templates_folder");
	var util_files = elm.find("li i.util_file");
	var utils_folder = elm.find("li i.utils_folder");
	var config_files = elm.find("li i.config_file");
	var configs_folder = elm.find("li i.configs_folder");
	var controller_files = elm.find("li i.controller_file");
	var controllers_folder = elm.find("li i.controllers_folder");
	var webroot_folder = elm.find("li i.webroot_folder");
	var webroot_files = elm.find("li i.webroot_file");
	var css_files = elm.find("li i.css_file");
	var js_files = elm.find("li i.js_file");
	var zip_files = elm.find("li i.zip_file");
	var img_files = elm.find("li i.img_file");
	var undefined_files = elm.find("li i.undefined_file");
	var block_files = elm.find("li i.block_file");
	var blocks_folder = elm.find("li i.blocks_folder");
	var cms_modules_folder = elm.find("li i.cms_module");
	var cmses_folder = elm.find("li i.cms_folder");
	var wordpresses_folder = elm.find("li i.wordpress_folder");
	var wordpress_installations_folder = elm.find("li i.wordpress_installation_folder");
	var module_folders = elm.find("li i.module_folder");
	var module_files = elm.find("li i.module_file");
	var cache_files = elm.find("li i.cache_file");
	var caches_folder = elm.find("li i.caches_folder");
	var objs = elm.find("li i.class");
	var methods = elm.find("li i.method");
	var functions = elm.find("li i.function");
	
	projects_common.parent().addClass("link");
	project_folders.parent().addClass("link");
	projects.parent().addClass("link");
	folders.parent().addClass("link");
	files.parent().addClass("link");
	entity_files.parent().addClass("link");
	entities_folder.parent().addClass("link");
	entities_sub_folders.parent().addClass("link");
	view_files.parent().addClass("link");
	views_folder.parent().addClass("link");
	template_files.parent().addClass("link");
	template_folders.parent().addClass("link");
	templates_folder.parent().addClass("link");
	util_files.parent().addClass("link");
	utils_folder.parent().addClass("link");
	config_files.parent().addClass("link");
	configs_folder.parent().addClass("link");
	controller_files.parent().addClass("link");
	controllers_folder.parent().addClass("link");
	webroot_folder.parent().addClass("link");
	webroot_files.parent().addClass("link");
	css_files.parent().addClass("link");
	js_files.parent().addClass("link");
	zip_files.parent().addClass("link");
	img_files.parent().addClass("link");
	undefined_files.parent().addClass("link");
	block_files.parent().addClass("link");
	blocks_folder.parent().addClass("link");
	cms_modules_folder.parent().addClass("link");
	cmses_folder.parent().addClass("link");
	wordpresses_folder.parent().addClass("link");
	wordpress_installations_folder.parent().addClass("link");
	module_folders.parent().addClass("link");
	module_files.parent().addClass("link");
	cache_files.parent().addClass("link");
	caches_folder.parent().addClass("link");
	objs.parent().addClass("link");
	methods.parent().addClass("link");
	functions.parent().addClass("link");
	
	addLiContextMenu(projects_common.parent(), "presentation_project_common_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(project_folders.parent(), "presentation_project_group_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(projects.parent(), "presentation_project_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(folders.parent(), "presentation_group_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(files.parent(), "presentation_file_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(entity_files.parent(), "presentation_page_file_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(entities_folder.parent(), "presentation_main_pages_group_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(entities_sub_folders.parent(), "presentation_pages_group_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(view_files.parent(), "presentation_file_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(views_folder.parent(), "presentation_evc_group_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(template_files.parent(), "presentation_file_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(template_folders.parent(), "presentation_group_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(templates_folder.parent(), "presentation_main_templates_group_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(util_files.parent(), "presentation_util_file_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(utils_folder.parent(), "presentation_evc_group_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(config_files.parent(), "presentation_file_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(configs_folder.parent(), "presentation_evc_group_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(controller_files.parent(), "presentation_file_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(controllers_folder.parent(), "presentation_evc_group_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(webroot_folder.parent(), "presentation_evc_group_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(webroot_files.parent(), "presentation_file_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(css_files.parent(), "presentation_file_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(js_files.parent(), "presentation_file_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(zip_files.parent(), "zip_file_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(img_files.parent(), "undefined_file_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(undefined_files.parent(), "undefined_file_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(block_files.parent(), "presentation_block_file_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(blocks_folder.parent(), "presentation_evc_group_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(cms_modules_folder.parent(), "cms_module_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(cmses_folder.parent(), "presentation_group_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(wordpresses_folder.parent(), "presentation_project_common_wordpress_group_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(wordpress_installations_folder.parent(), "presentation_project_common_wordpress_group_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(module_folders.parent(), "presentation_group_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(module_files.parent(), "undefined_file_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(cache_files.parent(), "presentation_cache_file_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(caches_folder.parent(), "presentation_cache_group_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(objs.parent(), "presentation_util_object_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(methods.parent(), "item_context_menu", {callback: onPresentationContextMenu});
	addLiContextMenu(functions.parent(), "item_context_menu", {callback: onPresentationContextMenu});
}

function addLiContextMenu(target, context_menu_id, options) {
	target.addcontextmenu(context_menu_id, options);
	
	//this will be used in the presentation/list.js
	target.data("context_menu_id", context_menu_id);
	target.data("context_menu_options", options);
}

function onDBContextMenu(target, contextmenu, originalEvent) {
	var a = $(originalEvent.target.parentNode);
	
	contextmenu.find(".add_table a").attr("add_table_url", a.attr("add_table_url"));
	contextmenu.find(".db_dump a").attr("db_dump_url", a.attr("db_dump_url"));
	contextmenu.find(".execute_sql a").attr("execute_sql_url", a.attr("execute_sql_url"));
	contextmenu.find(".manage_records a").attr("manage_records_url", a.attr("manage_records_url"));
	contextmenu.find(".edit_diagram a").attr("edit_diagram_url", a.attr("edit_diagram_url"));
	contextmenu.find(".create_diagram_sql a").attr("create_diagram_sql_url", a.attr("create_diagram_sql_url"));
	contextmenu.find(".create_sql a").attr("create_sql_url", a.attr("create_sql_url"));
	contextmenu.find(".import_data a").attr("import_data_url", a.attr("import_data_url"));
	contextmenu.find(".export_data a").attr("export_data_url", a.attr("export_data_url"));
	
	return onContextMenu(target, contextmenu, originalEvent);
}

function onIbatisContextMenu(target, contextmenu, originalEvent) {
	var a = $(originalEvent.target.parentNode);
	
	contextmenu.find(".create_automatically a").attr("create_automatically_url", a.attr("create_automatically_url"));
	contextmenu.find(".query a").attr("add_query_url", a.attr("add_query_url"));
	contextmenu.find(".parameter_map a").attr("add_parameter_map_url", a.attr("add_parameter_map_url"));
	contextmenu.find(".result_map a").attr("add_result_map_url", a.attr("add_result_map_url"));
	contextmenu.find(".manage_includes a").attr("manage_includes_url", a.attr("manage_includes_url"));
	
	return onContextMenu(target, contextmenu, originalEvent);
}

function onHibernateContextMenu(target, contextmenu, originalEvent) {
	var a = $(originalEvent.target.parentNode);
	
	contextmenu.find(".create_automatically a").attr("create_automatically_url", a.attr("create_automatically_url"));
	contextmenu.find(".obj a").attr("add_obj_url", a.attr("add_obj_url"));
	contextmenu.find(".query a").attr("add_query_url", a.attr("add_query_url"));
	contextmenu.find(".relationship a").attr("add_relationship_url", a.attr("add_relationship_url"));
	contextmenu.find(".parameter_map a").attr("add_parameter_map_url", a.attr("add_parameter_map_url"));
	contextmenu.find(".result_map a").attr("add_result_map_url", a.attr("add_result_map_url"));
	contextmenu.find(".manage_includes a").attr("manage_includes_url", a.attr("manage_includes_url"));
	
	return onContextMenu(target, contextmenu, originalEvent);
}

function onContextContextMenu(target, contextmenu, originalEvent) {
	var a = $(originalEvent.target.parentNode);
	var create_automatically_url = a.attr("create_automatically_url");
	
	if (create_automatically_url)
		contextmenu.children(".create_automatically").show();
	else
		contextmenu.children(".create_automatically").hide().prev(".line_break").hide();
	
	contextmenu.find(".create_automatically a").attr("create_automatically_url", create_automatically_url);
	contextmenu.find(".service_obj a").attr("add_service_obj_url", a.attr("add_service_obj_url"));
	contextmenu.find(".service_function a").attr("add_service_func_url", a.attr("add_service_func_url"));
	contextmenu.find(".service_method a").attr("add_service_method_url", a.attr("add_service_method_url"));
	contextmenu.find(".manage_includes a").attr("manage_includes_url", a.attr("manage_includes_url"));
	
	return onContextMenu(target, contextmenu, originalEvent);
}

function onPresentationContextMenu(target, contextmenu, originalEvent) {
	var a = $(originalEvent.target.parentNode);
	var create_automatically_url = a.attr("create_automatically_url");
	var create_uis_diagram_url = a.attr("create_uis_diagram_url");
	
	if (create_automatically_url)
		contextmenu.children(".create_automatically").show();
	else
		contextmenu.children(".create_automatically").hide();
	
	if (create_uis_diagram_url)
		contextmenu.children(".create_uis_diagram").show();
	else
		contextmenu.children(".create_uis_diagram").hide();
	
	if (!create_automatically_url && !create_uis_diagram_url)
		contextmenu.children(".create_automatically").prev(".line_break").hide();
		
	contextmenu.find(".manage_wordpress a").attr("manage_wordpress_url", a.attr("manage_wordpress_url"));
	
	contextmenu.find(".create_automatically a").attr("create_automatically_url", create_automatically_url);
	contextmenu.find(".create_uis_diagram a").attr("create_uis_diagram_url", create_uis_diagram_url);
	contextmenu.find(".install_template a").attr("install_template_url", a.attr("install_template_url"));
	contextmenu.find(".convert_template a").attr("convert_template_url", a.attr("convert_template_url"));
	contextmenu.find(".add_project a").attr("create_project_url", a.attr("create_project_url"));
	contextmenu.find(".edit_project_global_variables a").attr("edit_project_global_variables_url", a.attr("edit_project_global_variables_url"));
	contextmenu.find(".edit_config a").attr("edit_config_url", a.attr("edit_config_url"));
	contextmenu.find(".edit_init a").attr("edit_init_url", a.attr("edit_init_url"));
	contextmenu.find(".manage_references a").attr("manage_references_url", a.attr("manage_references_url"));
	contextmenu.find(".view_project a").attr("view_project_url", a.attr("view_project_url"));
	contextmenu.find(".test_project a").attr("test_project_url", a.attr("test_project_url"));
	contextmenu.find(".install_program a").attr("install_program_url", a.attr("install_program_url"));
	
	contextmenu.find(".class_obj a").attr("add_class_obj_url", a.attr("add_class_obj_url"));
	contextmenu.find(".class_function a").attr("add_class_func_url", a.attr("add_class_func_url"));
	contextmenu.find(".class_method a").attr("add_class_method_url", a.attr("add_class_method_url"));
	contextmenu.find(".manage_includes a").attr("manage_includes_url", a.attr("manage_includes_url"));
	
	return onContextMenu(target, contextmenu, originalEvent);
}

function onDaoContextMenu(target, contextmenu, originalEvent) {
	var a = $(originalEvent.target.parentNode);
	
	contextmenu.find(".hbnt_obj a").attr("create_dao_hibernate_model_url", a.attr("create_dao_hibernate_model_url"));
	contextmenu.find(".objt_obj a").attr("create_dao_obj_type_url", a.attr("create_dao_obj_type_url"));
	
	return onContextMenu(target, contextmenu, originalEvent);
}

function onVendorContextMenu(target, contextmenu, originalEvent) {
	var a = $(originalEvent.target.parentNode);
	
	return onContextMenu(target, contextmenu, originalEvent);
}

function onTestUnitContextMenu(target, contextmenu, originalEvent) {
	var a = $(originalEvent.target.parentNode);
	
	contextmenu.find(".test_unit_obj a").attr("create_test_unit_obj_url", a.attr("create_test_unit_obj_url"));
	contextmenu.find(".manage_test_units a").attr("manage_test_units_url", a.attr("manage_test_units_url"));
	
	return onContextMenu(target, contextmenu, originalEvent);
}

function onContextMenu(target, contextmenu, originalEvent) {
	//console.log(target);
	//console.log(contextmenu);
	//console.log(originalEvent);
	
	if (originalEvent.preventDefault) originalEvent.preventDefault(); 
	else originalEvent.returnValue = false;
	
	var a = $(originalEvent.target.parentNode);
	
	contextmenu.find(".edit a, .edit_new a").attr("edit_url", a.attr("edit_url"));
	contextmenu.find(".edit_raw_file a, .edit_raw_file_new a").attr("edit_raw_file_url", a.attr("edit_raw_file_url"));
	contextmenu.find(".rename a").attr("rename_url", a.attr("rename_url"));
	contextmenu.find(".remove a").attr("remove_url", a.attr("remove_url"));
	contextmenu.find(".create_folder a").attr("create_url", a.attr("create_url"));
	contextmenu.find(".create_file a").attr("create_url", a.attr("create_url"));
	contextmenu.find(".upload a").attr("upload_url", a.attr("upload_url"));
	contextmenu.find(".download a").attr("download_url", a.attr("download_url"));
	contextmenu.find(".copy a").attr("copy_url", a.attr("copy_url"));
	contextmenu.find(".cut a").attr("cut_url", a.attr("cut_url"));
	contextmenu.find(".paste a").attr("paste_url", a.attr("paste_url"));
	contextmenu.find(".diff_file a").attr("diff_file_url", a.attr("diff_file_url"));
	contextmenu.find(".manage_modules a").attr("manage_modules_url", a.attr("manage_modules_url"));
	contextmenu.find(".zip a").attr("zip_url", a.attr("zip_url"));
	contextmenu.find(".unzip a").attr("unzip_url", a.attr("unzip_url"));
	
	var properties_id = a.attr("properties_id");
	if (properties_id) {
		contextmenu.find(".properties a").attr("properties_id", properties_id);
		contextmenu.find(".properties").show();
	}
	else 
		contextmenu.find(".properties").hide();
	
	mytree.deselectAll();
	
	var new_target_id = originalEvent.target.parentNode.parentNode.id;
	
	if (new_target_id) {
		contextmenu.attr("last_selected_node_id", new_target_id);
		mytree.selectNode(new_target_id);
		return true;
	}
	
	return false;
}

function showProperties(menu_item) {
	$("#selected_menu_properties").hide();
	
	var id = menu_item.getAttribute("properties_id");
	//console.log(menu_item);
	
	if (id && menu_item_properties.hasOwnProperty(id) && menu_item_properties[id]) {
		var properties = menu_item_properties[id];
		var html;
		
		if (properties) {
			html = "";
			
			for (var key in properties) {
				var value = properties[key];
				
				key = key.replace(/_/g, " ").toLowerCase();
				key = key.charAt(0).toUpperCase() + key.slice(1);
				
				html += "<label>" + key + ": </label>" + value + "<br/>\n";
			}
		}
		else {
			html = "There are no properties to be shown";
		}
		
		$("#selected_menu_properties .content").html(html);
		
		MyFancyPopup.init({
			elementToShow: $("#selected_menu_properties")
		});
		
		MyFancyPopup.showPopup();
	}
	
	return false;
}

function goTo(a, attr_name, originalEvent) {
	originalEvent = originalEvent || window.event;
	
	if (originalEvent && (originalEvent.ctrlKey || originalEvent.keyCode == 65)) 
		return goToNew(a, attr_name);
	
	var url = a.getAttribute(attr_name);
	//console.log(attr_name+":"+url);
	
	if (url) {
		var d = new Date();
		url += (url.indexOf("?") != -1 ? "&" : "?") + "t=" + d.getTime();
		
		goToHandler(url, a, attr_name, originalEvent);
	}
	
	var j_a = $(a);
	if (j_a.hasClass("jstree-anchor")) 
		last_selected_node_id = j_a.parent().attr("id");
	else 
		last_selected_node_id = j_a.parent().parent().attr("last_selected_node_id");
	
	$(".jqcontextmenu").hide();
	
	return false;
}

function goToNew(a, attr_name) {
	var rand = Math.random() * 10000;
	var win = openWindow(a, attr_name, "tab" + rand);
	
	return false;
}

function openWindow(a, attr_name, tab) {
	var url = a.getAttribute(attr_name);
	//console.log(attr_name+":"+url);
	
	if (url) {
		var win = typeof tab != "undefined" && tab ? window.open(url, tab) : window.open(url);
		
		$(".jqcontextmenu").hide();
		
		if(win) { //Browser has allowed it to be opened
			win.focus();
			return win;
		}
		else //Broswer has blocked it
			alert('Please allow popups for this site');
	}
}

function goToPopup(a, attr_name, originalEvent) {
	var url = a.getAttribute(attr_name);
	//console.log(attr_name+":"+url);
	
	if (url) {
		var popup = $(".go_to_popup");
		
		if (!popup[0]) {
			popup = $('<div class="myfancypopup go_to_popup"></div>');
			$(document.body).append(popup);
		}
		
		popup.html('<iframe src="' + url + '"></iframe>');
		
		MyFancyPopup.init({
			elementToShow: popup,
			//parentElement: document,
		});
		MyFancyPopup.showPopup();
	}
	
	return false;
}

function manageFile(a, attr_name, action, on_success_callback) {
	var url = a.getAttribute(attr_name);
	//console.log(attr_name+":"+url);
	
	if (url) {
		var new_file_name;
		var props;
		var status = false;
		var original_action = action;
		
		var tree_node_id_to_be_updated = $(a).parent().parent().attr("last_selected_node_id");
		
		var file_name = getParameterByName(url, "path");
		file_name = file_name.substr(file_name.length - 1, 1) == "/" ? file_name.substr(0, file_name.length - 1) : file_name;
		file_name = file_name.lastIndexOf("/") != -1 ? file_name.substr(file_name.lastIndexOf("/") + 1) : file_name;
		
		switch (action) {
			case "remove": 
				var jstree_attr = $("#"+tree_node_id_to_be_updated).attr("data-jstree");
				var file_type = jstree_attr == '{"icon":"project"}' ? "project" : (jstree_attr == '{"icon":"project_folder"}' ? "projects folder" : null);
				
				if (file_type)
					status = confirm("Do you wish to remove the " + file_type + ": '" + file_name + "'?") && confirm("If you delete this " + file_type + ", you will loose all the created pages and other files inside of this " + file_type + "!\nDo you wish to continue?") && confirm("LAST WARNING:\n\tIf you proceed, you cannot undo this deletion!\nAre you sure you wish to remove this " + file_type + "?");
				else
					status = confirm("Do you wish to remove the file '" + file_name + "'?");
				
				break;
				
			case "create_folder": 
			case "create_file": 
				status = (new_file_name = prompt("Please write the file name:")); 
				break;
				
			case "rename_name": 
				action = "rename";
				var pos = file_name.lastIndexOf(".");
				
				if (pos != -1) {
					var base_name = file_name.substr(0, pos);
					var extension = file_name.substr(pos + 1);
					status = (new_file_name = prompt("Please write the new name:", base_name));
					new_file_name += "." + extension;
				}
				else
					status = (new_file_name = prompt("Please write the new name:", base_name));
				break;
				
			case "rename": 
				status = (new_file_name = prompt("Please write the new name:", file_name)); 
				break;
				
			case "zip": 
			case "unzip": 
				//TODO: In the future allow the user to choose a destination folder
				new_file_name = "";//new_file_name is the destination folder. if empty, it means the zip file will be unziped into the same folder.
				
				status = confirm("You are about to " + action + " '" + file_name + "' into the same folder. Do you wish to proceed?");
				break;
				
			case "paste": 
				if (file_to_copy_or_cut) {
					try {
						props = file_to_copy_or_cut.replace(/,/g, "','").replace(/\[/g, "['").replace(/\]/g, "']");
						eval ('props = ' + props + ';');
					}
					catch (e) {
						props = null;
					}
				}
				
				if (props) {
					status = confirm("You are about to paste the file '" + props[2] + "' from the '" + props[0] + "' Layer to the '" + file_name + "' folder.\nDo you wish to continue?"); 
					new_file_name = file_to_copy_or_cut;
					action = copy_or_cut_action == "cut" ? "paste_and_remove" : action;
				}
				else
					alert("Error trying to paste file! In order to paste, you must copy or cut a valid file first...");
				
				break;
		}
		
		if (status) {
			var is_file_new_name_action = action == "rename" || action == "create_folder" || action == "create_file";
			
			if (is_file_new_name_action && new_file_name) {
				new_file_name = ("" + new_file_name).replace(/^\s+/g, "").replace(/\s+$/g, ""); //trim name
				
				//normalize new file name
				if (new_file_name) {
					var allow_upper_case = a.getAttribute("allow_upper_case") == 1; //in case of businesslogic services class
					var has_accents = new_file_name.match(/([\x7f-\xff\u1EBD\u1EBC]+)/gi);
					var has_spaces = new_file_name.match(/\s+/g);
					var has_upper_case = !allow_upper_case && new_file_name.toLowerCase() != new_file_name;
					//var has_weird_chars = new_file_name.match(/([\p{L}\w\.]+)/giu).join("") != new_file_name; // \. is very important bc the new_file_name is the complete filename with the extension. \p{L} and /../u is to get parameters with accents and ç. Already includes the a-z. Cannot use this bc it does not work in IE.
					var has_weird_chars = new_file_name.match(/([\w\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u024F\u1EBD\u1EBC\.]+)/gi).join("") != new_file_name; // \. is very important bc the new_file_name is the complete filename with the extension. '\w' means all words with '_' and 'u' means with accents and ç too.
					
					if ((has_accents || has_spaces || has_upper_case || has_weird_chars) && confirm("Is NOT advisable to have file names with spaces, dashes, letters with accents, upper case letters or weird characters.\nYou should only use the following letters: A-Z, 0-9 and '_'.\nCan I normalize this name and convert it to a proper name?")) {
						if (typeof new_file_name.normalize == "function") //This doesn't work in IE11
							new_file_name = new_file_name.normalize("NFD");
						
						new_file_name = new_file_name.replace(/[\u0300-\u036f]/g, "").replace(/[\s\-]+/g, "_").match(/[\w\.]+/g).join(""); // \. is very important bc the new_file_name is the complete filename with the extension.
						
						if (!allow_upper_case)
							new_file_name = new_file_name.toLowerCase();
					}
				}
			}
			
			if (is_file_new_name_action && !new_file_name)
				alert("Error: File name cannot be empty");
			else {
				url = url.replace("#action#", action);
				url = url.replace("#extra#", new_file_name);
				
				url = encodeUrlWeirdChars(url); //Note: Is very important to add the encodeUrlWeirdChars otherwise if a value has accents, won't work in IE.
				
				var str = action == "create_folder" || action == "create_file" ? "create" : action;
				
				$.ajax({
					type : "get",
					url : url,
					success : function(data, textStatus, jqXHR) {
						if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
							showAjaxLoginPopup(jquery_native_xhr_object.responseURL, url, function() {
								StatusMessageHandler.removeLastShownMessage("error");
								manageFile(a, attr_name, original_action, on_success_callback);
							});
						else if (data == "1") {
							if (action == "create_folder" || action == "create_file" || action == "paste" || action == "paste_and_remove")
								refreshAndShowNodeChildsByNodeId(tree_node_id_to_be_updated);
								
							else if (action != "remove")
								refreshNodeParentChildsByChildId(tree_node_id_to_be_updated);
							
							StatusMessageHandler.showMessage("The file was " + str + (action == "unzip" || action == "zip" ? "pe" : "") + "d correctly");
							
							if (typeof on_success_callback == "function")
								on_success_callback(a, attr_name, action, new_file_name, url, tree_node_id_to_be_updated);
							
							if (action == "remove" || action == "paste_and_remove") {
								var li = $("#" + (action == "remove" ? tree_node_id_to_be_updated : copy_or_cut_tree_node_id));
								
								if (li.is(":last-child")) 
									li.prev("li").addClass("jstree-last");
								
								li.remove();
							}
						}
						else
							StatusMessageHandler.showError("There was a problem trying to " + str + " file. Please try again..." + (data ? "\n" + data : ""));
					},
					error : function(jqXHR, textStatus, errorThrown) { 
						var msg = jqXHR.responseText ? "\n" + jqXHR.responseText : "";
						StatusMessageHandler.showError((errorThrown ? errorThrown + " error.\n" : "") + "Error trying to " + str + " file.\nPlease try again..." + msg);
					},
				});
			}
		}
	}
	
	$(".jqcontextmenu").hide();
	
	return false;
}

function renameProject(a, attr_name, action, new_file_name, url, tree_node_id_to_be_updated) {
	StatusMessageHandler.showMessage("Please don't forget to go to the permissions panel and update the correspondent permissions...");
}

function manageBusinessLogicObject(a, attr_name, action) {
	manageFile(a, attr_name, action, function(a, attr_name, action, new_file_name, url, tree_node_id_to_be_updated) {
		var file_node_id = getNodeParentIdByNodeId(tree_node_id_to_be_updated);
		refreshNodeChildsByNodeId(file_node_id);
	});
}

function managePresentationFile(a, attr_name, action, new_file_name, url, tree_node_id_to_be_updated) {
	if (url && url.indexOf("/src/entity/") != -1) { //deletes view file for entity
		var str = action == "create_folder" || action == "create_file" ? "create" : action;
		
		var entity_folder = $("#" + tree_node_id_to_be_updated);
		var entities_folder = entity_folder.closest('li[data-jstree=\'{"icon":"entities_folder"}\']');
		var entities_folder_a = entities_folder.children("a");
		var project_with_auto_view = parseInt(entities_folder_a.attr("project_with_auto_view")) == 1;
		
		if (project_with_auto_view && confirm("Do you wish to " + str + " the correspondent view too?")) {
			var view_url = url.replace("/src/entity/", "/src/view/"); //does not need encodeUrlWeirdChars bc the url is already encoded
			
			var options = {
				url : view_url,
				success : function(data) {
					if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
						showAjaxLoginPopup(jquery_native_xhr_object.responseURL, view_url, function() {
							StatusMessageHandler.removeLastShownMessage("error");
							managePresentationFile(a, attr_name, action, new_file_name, url, tree_node_id_to_be_updated);
						});
					else if (data == "1") {
						StatusMessageHandler.showMessage("View " + str + "d successfully");
					
						if (entity_folder[0]) {
							var p = entity_folder;
							var view_folder = null;
						
							while (p != null && view_folder == null) {
								p = p.parent().parent();
							
								if (p.children("a").children("i").hasClass("project")) {
									view_folder = p.children("ul").children("li")[1];
									break;
								}
							}
						
							if (view_folder) {
								refreshNodeChildsByNodeId( view_folder.getAttribute("id") );
							}
						}
					}
					else
						StatusMessageHandler.showError("There was a problem trying to " + str + " the correspondent view. Please try again...") + (data ? "\n" + data : "");
				},
				error : function(jqXHR, textStatus, errorThrown) { 
					var msg = jqXHR.responseText ? "\n" + jqXHR.responseText : "";
					StatusMessageHandler.showError((errorThrown ? errorThrown + " error.\n" : "") + "Error trying to " + str + " file.\nPlease try again..." + msg);
				},
				async: false,
			};
			
			$.ajax(options);
		}
	}
	else if (url && url.indexOf("/src/template/") != -1 && action == "remove") { //deletes template webroot folder if apply
		var tree_node = $("#" + tree_node_id_to_be_updated);
		var p = tree_node.parent().parent();
		var is_template_folder = tree_node.find(" > a > i").is(".folder") && p.find(" > a > i").is(".templates_folder");
		
		//deletes template folder from webroot
		if (is_template_folder && confirm("Do you wish to remove the correspondent webroot folder too")) {
			var template_url = url.replace("/src/template/", "/webroot/template/"); //does not need encodeUrlWeirdChars bc the url is already encoded
			
			var options = {
				url : template_url,
				success : function(data) {
					if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
						showAjaxLoginPopup(jquery_native_xhr_object.responseURL, template_url, function() {
							StatusMessageHandler.removeLastShownMessage("error");
							managePresentationFile(a, attr_name, action, new_file_name, url, tree_node_id_to_be_updated);
						});
					else if (data == "1") {
						StatusMessageHandler.showMessage("Template webroot deleted successfully");
						
						var folder_name = tree_node.find(" > a > label").text();
						var project = p.closest("li[data-jstree=\'{\"icon\":\"project\"}\']");
						var labels = project.find(" > ul > li[data-jstree=\'{\"icon\":\"webroot_folder\"}\']").find(" > ul > li > a > label");
						var webroot_template_li = null;
						
						$.each(labels, function(idx, label) {
							if ($(label).text() == "template") {
								webroot_template_li = $(label).parent().parent();
								return false;
							}
						});
						
						if (webroot_template_li) {
							var labels = webroot_template_li.find(" > ul > li > a > label");
							var selected_template_li = null;
							
							$.each(labels, function(idx, label) {
								if ($(label).text() == folder_name) {
									selected_template_li = $(label).parent().parent();
									return false;
								}
							});
							
							if (selected_template_li)
								selected_template_li.remove();
							else
								refreshNodeChildsByNodeId( webroot_template_li.attr("id") );
						}
					}
					else
						StatusMessageHandler.showError("There was a problem trying to delete the correspondent template webroot folder. Please try again...") + (data ? "\n" + data : "");
				},
				error : function(jqXHR, textStatus, errorThrown) { 
					var msg = jqXHR.responseText ? "\n" + jqXHR.responseText : "";
					StatusMessageHandler.showError((errorThrown ? errorThrown + " error.\n" : "") + "Error trying to delete template webroot folder.\nPlease try again..." + msg);
				},
			};
			
			$.ajax(options);
		}
	}
}

function removeItem(a, attr_name, on_success_callback) {
	if (confirm("Do you wish to remove this item?")) {
		var url = a.getAttribute(attr_name);
		//console.log(attr_name+":"+url);
	
		if (url) {
			var tree_node_id_to_be_updated = $(a).parent().parent().attr("last_selected_node_id");
			url = encodeUrlWeirdChars(url); //Note: Is very important to add the encodeUrlWeirdChars otherwise if a value has accents, won't work in IE.
			
			$.ajax({
				type : "get",
				url : url,
				dataType : "json",
				success : function(data, textStatus, jqXHR) {
					if(data == 1) {
						StatusMessageHandler.showMessage("Removed successfully");
						
						if (typeof on_success_callback == "function")
							on_success_callback(a, attr_name, tree_node_id_to_be_updated);
						
						$("#" + tree_node_id_to_be_updated).remove();
					}
					else
						StatusMessageHandler.showError("Error trying to remove item.\nPlease try again..." + (data ? "\n" + data : ""));
				},
				error : function(jqXHR, textStatus, errorThrown) { 
					if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
						showAjaxLoginPopup(jquery_native_xhr_object.responseURL, url, function() {
							StatusMessageHandler.removeLastShownMessage("error");
							removeItem(a, attr_name);
						});
					else {
						var msg = jqXHR.responseText ? "\n" + jqXHR.responseText : "";
						StatusMessageHandler.showError((errorThrown ? errorThrown + " error.\n" : "") + "Error trying to remove item.\nPlease try again..." + msg);
					}
				},
			});
		}
	}
	
	$(".jqcontextmenu").hide();
	
	return false;
}

function removeBusinessLogicObject(a, attr_name) {
	removeItem(a, attr_name, function(a, attr_name, tree_node_id_to_be_updated) {
		var file_node_id = getNodeParentIdByNodeId(tree_node_id_to_be_updated);
		refreshNodeChildsByNodeId(file_node_id);
	});
}

function refresh(a) {
	setTimeout(function() {
		var tree_node_id_to_be_refreshed = $(a).parent().parent().attr("last_selected_node_id");
		refreshAndShowNodeChildsByNodeId(tree_node_id_to_be_refreshed);
	}, 100);
	
	$(".jqcontextmenu").hide();
	
	return false;
}

function copyFile(a) {
	return copyOrCutFile(a, "copy");
}
function cutFile(a) {
	return copyOrCutFile(a, "cut");
}
function copyOrCutFile(a, action) {
	copy_or_cut_tree_node_id = $(a).parent().parent().attr("last_selected_node_id");
	copy_or_cut_action = action;
	
	setTimeout(function() {
		file_to_copy_or_cut = a.getAttribute(action == "cut" ? "cut_url" : "copy_url");
		StatusMessageHandler.showMessage("File copied successfully");
	}, 100);
	
	$(".jqcontextmenu").hide();
}

function flushCacheFromAdmin(url) {
	$.ajax({
		type : "get",
		url : url,
		dataType : "text",
		success : function(data, textStatus, jqXHR) {
			if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
				showAjaxLoginPopup(jquery_native_xhr_object.responseURL, url, function() {
					StatusMessageHandler.removeLastShownMessage("error");
					flushCacheFromAdmin(url);
				});
			else if (data == "1") 
				StatusMessageHandler.showMessage("Cache flushed!");
			else
				StatusMessageHandler.showError("Cache NOT flushed! Please try again..." + (data ? "\n" + data : ""));
		},
		error : function(jqXHR, textStatus, errorThrown) { 
			if (jqXHR.responseText);
				StatusMessageHandler.showError(jqXHR.responseText);
		}
	});
	
	return false;
}

function getParameterByName(url, name) {
	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	var results = ("" + url).match(/[\?&]path=([^&#]*)/i);
	
	if (results === null || !results[1])
		return "";
	
	var parameter = results[1].replace(/\+/g, " "); //decodes the encoded spaces into spaces.
	
	try {
		parameter = decodeURIComponent(parameter);
	}
	catch(e) {
		if (console && console.log)
			console.log(e);
	}
	
	return parameter;
}

function chooseAvailableTool(url) {
	var popup = $(".choose_available_tool_popup");
	
	if (!popup[0]) {
		popup = $('<div class="myfancypopup choose_available_tool_popup"><iframe src="' + url + '"></iframe></div>');
		$(document.body).append(popup);
	}
	
	MyFancyPopupTools.init({
		elementToShow: popup,
		parentElement: document,
		
		goTo: function(url, originalEvent) {
			var a = document.createElement("a");
			a.setAttribute("url", url);
			goTo(a, "url", originalEvent);
			
			MyFancyPopupTools.hidePopup();
		},
	});
	MyFancyPopupTools.showPopup();
	
	return false;
}

function chooseAvailableProject(url) {
	var popup = $(".choose_available_project_popup");
	
	if (!popup[0]) {
		popup = $('<div class="myfancypopup choose_available_project_popup"><iframe src="' + url + '"></iframe></div>');
		$(document.body).append(popup);
	}
	
	MyFancyPopupProjects.init({
		elementToShow: popup,
		parentElement: document,
		
		goTo: function(url, originalEvent) {
			MyFancyPopupProjects.hidePopup();
			MyFancyPopupProjects.showOverlay();
			MyFancyPopupProjects.showLoading();
			
			setTimeout(function() {
				document.location = url;
			}, 300);
		},
	});
	MyFancyPopupProjects.showPopup();
	
	return false;
}
