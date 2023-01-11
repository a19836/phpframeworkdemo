var file_to_copy_or_cut = null;
var copy_or_cut_action = null;
var copy_or_cut_tree_node_id = null;

var MyFancyPopupTools = new MyFancyPopupClass();
var MyFancyPopupProjects = new MyFancyPopupClass();
var MyFancyPopupDBTable = new MyFancyPopupClass();

function initFileTreeMenu() {
	//prepare menu tree
	mytree.init("file_tree");
	
	$("#file_tree").removeClass("hidden");
	
	initContextMenus();
}

function initContextMenus() {
	var file_tree = $("#file_tree");
	
	var obj = null;
	
	obj = file_tree.find(".db_layers li.main_node_db");
	addLiContextMenu(obj.children("a").addClass("link"), "main_db_group_context_menu", {callback: onDBContextMenu});
	initDBContextMenu(obj);//This covers the scenario where the DB_DRIVER node is inside of the ".db_layers li.main_node_db" and ".db_layers" node
	
	obj = file_tree.find(".data_access_layers li.main_node_ibatis");
	addLiContextMenu(obj.children("a").addClass("link"), "main_ibatis_group_context_menu", {callback: onIbatisContextMenu});
	initIbatisContextMenu(obj);
	
	obj = file_tree.find(".data_access_layers li.main_node_hibernate");
	addLiContextMenu(obj.children("a").addClass("link"), "main_hibernate_group_context_menu", {callback: onHibernateContextMenu});
	initHibernateContextMenu(obj);
	
	obj = file_tree.find(".business_logic_layers li.main_node_businesslogic");
	addLiContextMenu(obj.children("a").addClass("link"), "main_business_logic_group_context_menu", {callback: onContextContextMenu});
	initContextContextMenu(obj);
	
	obj = file_tree.find(".presentation_layers li.main_node_presentation");
	addLiContextMenu(obj.children("a").addClass("link"), "main_presentation_group_context_menu", {callback: onPresentationContextMenu});
	initPresentationContextMenu(obj);
	
	obj = file_tree.find("li.main_node_lib");
	addLiContextMenu(obj.children("a").addClass("link"), "main_lib_group_context_menu", {callback: onLibContextMenu});
	initLibContextMenu(obj);
	
	obj = file_tree.find("li.main_node_dao");
	addLiContextMenu(obj.children("a").addClass("link"), "main_dao_group_context_menu", {callback: onDaoContextMenu});
	initDaoContextMenu(obj);
	
	obj = file_tree.find("li.main_node_vendor");
	addLiContextMenu(obj.children("a").addClass("link"), "main_vendor_group_context_menu", {callback: onVendorContextMenu});
	initVendorContextMenu(obj);
	
	obj = file_tree.find("li.main_node_test_unit");
	addLiContextMenu(obj.children("a").addClass("link"), "main_test_unit_group_context_menu", {callback: onTestUnitContextMenu});
	initTestUnitContextMenu(obj);
	
	obj = file_tree.find("li.main_node_other");
	addLiContextMenu(obj.children("a").addClass("link"), "main_other_group_context_menu", {callback: onVendorContextMenu});
	initOtherContextMenu(obj);
	
	prepareParentChildsEventToHideContextMenu(file_tree);
	addSubMenuIconToParentChildsWithContextMenu(file_tree);
	
	//var selected_menu_properties = $("#selected_menu_properties");
}

function initDBContextMenu(elm, request_data) {
	var dbs_driver = elm.find("li i.db_driver");
	var dbs_management = elm.find("li i.db_management");
	var dbs_diagram = elm.find("li i.db_diagram");
	var tables = elm.find("li i.table");
	var attributes = elm.find("li i.attribute");
	
	var db_data = $.isPlainObject(request_data) && $.isPlainObject(request_data["properties"]) && request_data["properties"].hasOwnProperty("db_data") ? request_data["properties"]["db_data"] : null;
	
	dbs_driver.parent().addClass("link");
	dbs_management.parent().addClass("link");
	dbs_diagram.parent().addClass("link");
	tables.parent().addClass("link");
	attributes.parent().addClass("link");
	
	addLiContextMenu(dbs_driver.parent(), "db_driver_context_menu", {callback: onDBContextMenu});
	addLiContextMenu(dbs_management.parent(), "db_driver_tables_context_menu", {callback: onDBContextMenu});
	addLiContextMenu(dbs_diagram.parent(), "db_diagram_context_menu", {callback: onDBContextMenu});
	addLiContextMenu(tables.parent(), "db_driver_table_context_menu", {callback: onDBContextMenu});
	addLiContextMenu(attributes.parent(), "db_driver_table_attribute_context_menu", {callback: onDBContextMenu, db_data: db_data});
}

function initIbatisContextMenu(elm, request_data) {
	var folders = elm.find("li i.folder");
	var files = elm.find("li i.file");
	var queries = elm.find("li i.query");
	var maps = elm.find("li i.map");
	var undefined_files = elm.find("li i.undefined_file");
	var cms_commons_folder = elm.find("li i.cms_common");
	var cms_modules_folder = elm.find("li i.cms_module");
	var cms_programs_folder = elm.find("li i.cms_program");
	var cms_resources_folder = elm.find("li i.cms_resource");
	var zip_files = elm.find("li i.zip_file");
	
	folders.parent().addClass("link");
	files.parent().addClass("link");
	queries.parent().addClass("link");
	maps.parent().addClass("link");
	undefined_files.parent().addClass("link");
	cms_commons_folder.parent().addClass("link");
	cms_modules_folder.parent().addClass("link");
	cms_programs_folder.parent().addClass("link");
	cms_resources_folder.parent().addClass("link");
	zip_files.parent().addClass("link");

	addLiContextMenu(folders.parent(), "ibatis_group_context_menu", {callback: onIbatisContextMenu});
	addLiContextMenu(files.parent(), "ibatis_file_context_menu", {callback: onIbatisContextMenu});
	addLiContextMenu(queries.parent(), "item_context_menu", {callback: onIbatisContextMenu});
	addLiContextMenu(maps.parent(), "item_context_menu", {callback: onIbatisContextMenu});
	addLiContextMenu(undefined_files.parent(), "undefined_file_context_menu", {callback: onIbatisContextMenu});
	addLiContextMenu(cms_commons_folder.parent(), "ibatis_group_common_context_menu", {callback: onIbatisContextMenu});
	addLiContextMenu(cms_modules_folder.parent(), "cms_module_context_menu", {callback: onIbatisContextMenu});
	addLiContextMenu(cms_programs_folder.parent(), "ibatis_group_context_menu", {callback: onIbatisContextMenu});
	addLiContextMenu(cms_resources_folder.parent(), "ibatis_group_context_menu", {callback: onIbatisContextMenu});
	addLiContextMenu(zip_files.parent(), "zip_file_context_menu", {callback: onIbatisContextMenu});
}

function initHibernateContextMenu(elm, request_data) {
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
	var cms_resources_folder = elm.find("li i.cms_resource");
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
	cms_resources_folder.parent().addClass("link");
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
	addLiContextMenu(cms_resources_folder.parent(), "hibernate_group_context_menu", {callback: onHibernateContextMenu});
	addLiContextMenu(zip_files.parent(), "zip_file_context_menu", {callback: onHibernateContextMenu});
	
	//Remove hbn_native nodes
	elm.find("li i.hbn_native").each(function(idx, node) {
		$(node).parent().parent().remove();
	});
}

function initLibContextMenu(elm, request_data) {
	var folders = elm.find("li i.folder");
	var files = elm.find("li i.file");
	var zip_files = elm.find("li i.zip_file");
	
	folders.parent().addClass("link");
	files.parent().addClass("link");
	zip_files.parent().addClass("link");

	addLiContextMenu(folders.parent(), "lib_group_context_menu", {callback: onLibContextMenu});
	addLiContextMenu(files.parent(), "lib_file_context_menu", {callback: onLibContextMenu});
	addLiContextMenu(zip_files.parent(), "zip_file_context_menu", {callback: onLibContextMenu});
}

function initDaoContextMenu(elm, request_data) {
	var folders = elm.find("li i.folder");
	var files = elm.find("li i.file");
	var objs_type = elm.find("li i.objtype");
	var objs_hibernate = elm.find("li i.hibernatemodel");
	var cms_commons_folder = elm.find("li i.cms_common");
	var cms_modules_folder = elm.find("li i.cms_module");
	var cms_programs_folder = elm.find("li i.cms_program");
	var cms_resources_folder = elm.find("li i.cms_resource");
	var zip_files = elm.find("li i.zip_file");
	
	folders.parent().addClass("link");
	files.parent().addClass("link");
	objs_type.parent().addClass("link");
	objs_hibernate.parent().addClass("link");
	cms_commons_folder.parent().addClass("link");
	cms_modules_folder.parent().addClass("link");
	cms_programs_folder.parent().addClass("link");
	cms_resources_folder.parent().addClass("link");
	zip_files.parent().addClass("link");

	addLiContextMenu(folders.parent(), "dao_group_context_menu", {callback: onDaoContextMenu});
	addLiContextMenu(files.parent(), "undefined_file_context_menu", {callback: onDaoContextMenu});
	addLiContextMenu(objs_type.parent(), "dao_file_context_menu", {callback: onDaoContextMenu});
	addLiContextMenu(objs_hibernate.parent(), "dao_file_context_menu", {callback: onDaoContextMenu});
	addLiContextMenu(cms_commons_folder.parent(), "dao_group_context_menu", {callback: onDaoContextMenu});
	addLiContextMenu(cms_modules_folder.parent(), "cms_module_context_menu", {callback: onDaoContextMenu});
	addLiContextMenu(cms_programs_folder.parent(), "dao_group_context_menu", {callback: onDaoContextMenu});
	addLiContextMenu(cms_resources_folder.parent(), "dao_group_context_menu", {callback: onDaoContextMenu});
	addLiContextMenu(zip_files.parent(), "zip_file_context_menu", {callback: onDaoContextMenu});
}

function initVendorContextMenu(elm, request_data) {
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

function initTestUnitContextMenu(elm, request_data) {
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

function initOtherContextMenu(elm, request_data) {
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

function initContextContextMenu(elm, request_data) { //business logic
	var folders = elm.find("li i.folder");
	var files = elm.find("li i.file");
	var objs = elm.find("li i.service");
	var methods = elm.find("li i.method");
	var functions = elm.find("li i.function");
	var undefined_files = elm.find("li i.undefined_file");
	var cms_commons_folder = elm.find("li i.cms_common");
	var cms_modules_folder = elm.find("li i.cms_module");
	var cms_programs_folder = elm.find("li i.cms_program");
	var cms_resources_folder = elm.find("li i.cms_resource");
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
	cms_resources_folder.parent().addClass("link");
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
	addLiContextMenu(cms_resources_folder.parent(), "business_logic_group_context_menu", {callback: onContextContextMenu});
	addLiContextMenu(zip_files.parent(), "zip_file_context_menu", {callback: onContextContextMenu});
}

function initPresentationContextMenu(elm, request_data) {
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
	addLiContextMenu(template_files.parent(), "presentation_template_file_context_menu", {callback: onPresentationContextMenu});
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
	
	contextmenu.find(".add_auto_table a").attr("add_auto_table_url", a.attr("add_auto_table_url"));
	contextmenu.find(".add_manual_table a").attr("add_manual_table_url", a.attr("add_manual_table_url"));
	contextmenu.find(".add_attribute a").attr("add_attribute_url", a.attr("add_attribute_url"));
	contextmenu.find(".rename a").attr("rename_url", a.attr("rename_url"));
	contextmenu.find(".remove a").attr("remove_url", a.attr("remove_url"));
	contextmenu.find(".db_dump a").attr("db_dump_url", a.attr("db_dump_url"));
	contextmenu.find(".manage_records a").attr("manage_records_url", a.attr("manage_records_url"));
	contextmenu.find(".edit_diagram a").attr("edit_diagram_url", a.attr("edit_diagram_url"));
	contextmenu.find(".create_diagram_sql a").attr("create_diagram_sql_url", a.attr("create_diagram_sql_url"));
	contextmenu.find(".create_sql a").attr("create_sql_url", a.attr("create_sql_url"));
	contextmenu.find(".import_data a").attr("import_data_url", a.attr("import_data_url"));
	contextmenu.find(".export_data a").attr("export_data_url", a.attr("export_data_url"));
	contextmenu.find(".primary_key a, .null a, .type a").attr("set_property_url", a.attr("set_property_url"));
	
	contextmenu.find("a").attr("execute_sql_url", a.attr("execute_sql_url")); //very important bc the manageDBTableAction method uses this attribute, so we must have this attribute set in all the menu items.
	
	//If is DB table attribute, prepare correspondent menus
	if (a.children("i.attribute")) {
		var li = a.parent();
		var properties_id = a.attr("properties_id");
		
		if (properties_id && menu_item_properties.hasOwnProperty(properties_id) && menu_item_properties[properties_id]) {
			var attribute_properties = menu_item_properties[properties_id];
			
			if (attribute_properties) {
				//activate primary key menu
				if (attribute_properties["primary_key"]) 
					contextmenu.find(".primary_key").addClass("checked");
				else
					contextmenu.find(".primary_key").removeClass("checked");
				
				//activate null menu
				if (attribute_properties["null"])
					contextmenu.find(".null").addClass("checked");
				else
					contextmenu.find(".null").removeClass("checked");
				
				//prepare length input
				var input = contextmenu.find(".type a input");
				input.val(attribute_properties["length"]);
				
				//get correspondent DB Driver types and set contextmenu with these types.
				var context_menu_options = target.data("context_menu_options");
				var db_data = context_menu_options["db_data"];
				var column_types = null, column_simple_types = null, column_mandatory_length_types = null, column_types_ignored_props = null;
				
				if ($.isPlainObject(db_data)) {
					column_types = db_data["column_types"];
					column_simple_types = db_data["column_simple_types"];
					column_mandatory_length_types = db_data["column_mandatory_length_types"];
					column_types_ignored_props = db_data["column_types_ignored_props"];
				}
				
				var select = contextmenu.find(".type a select");
				select.find("option:not([disabled])").remove();
				
				var select_checker = function(set_mandatory_length) {
					var type = select.val();
					var length = input.val();
					var simple_props = null;
					
					if (type && column_simple_types && $.isPlainObject(column_simple_types) && column_simple_types.hasOwnProperty(type)) {
						simple_props = column_simple_types[type];
						
						if (simple_props["type"]) {
							type = simple_props["type"];
							
							if ($.isArray(type))
								type = type[0];
						}
					}
					
					select.data("simple_props", simple_props);
					
					var hide_length = $.isPlainObject(column_types_ignored_props) && column_types_ignored_props.hasOwnProperty(type) && $.isArray(column_types_ignored_props[type]) && $.inArray("length", column_types_ignored_props[type]) != -1;
					
					if (hide_length) {
						input.attr("disabled", "disabled");
						input.val("");
					}
					else {
						input.removeAttr("disabled");
						
						if (set_mandatory_length && length.replace(/\s+/g, "") == "") {
							var mandatory_length = $.isPlainObject(simple_props) && simple_props.hasOwnProperty("length") ? simple_props["length"] : (
								$.isPlainObject(column_mandatory_length_types) && column_mandatory_length_types.hasOwnProperty(type) ? column_mandatory_length_types[type] : null
							);
							
							if ($.isNumeric(mandatory_length) || mandatory_length)
								input.val(mandatory_length);
						}
					}
				};
				
				//convert type into simple type if apply
				var current_attribute_type = attribute_properties["type"];
				
				if (column_simple_types && $.isPlainObject(column_simple_types))
					for (var simple_type in column_simple_types) {
						var simple_props = column_simple_types[simple_type];
						
						if ($.isPlainObject(simple_props)) {
							var is_simple_type = true;
							
							for (var prop_name in simple_props) 
								if (prop_name != "label") {
									var props_value = simple_props[prop_name];
									
									if (!$.isArray(props_value)) //if prop_name == "type" then the props_value maight be an array
										props_value = [props_value];
									
									var sub_exists = false;
									
									for (var j = 0; j < props_value.length; j++) {
										if (prop_name == "name") { //prop_name=="name" is a different property that will check if name contains the searching string.
											if (!attribute_properties[prop_name] || attribute_properties[prop_name].toLowerCase().indexOf( props_value[j].toLowerCase() ) != -1) {
												sub_exists = true;
												break;
											}
										}
										else if (props_value[j] == attribute_properties[prop_name] || (!props_value[j] && !attribute_properties[prop_name])) { //if both values are false (null or empty string or 0), then the values are the same
											sub_exists = true;
											break;
										}
									}
									
									if (!sub_exists) {
										is_simple_type = false;
										break;
									}
								}
							
							if (is_simple_type) {
								current_attribute_type = simple_type;
								break;
							}
						}
					}
				
				//prepare types html
				var exists = false;
				var types = '<option disabled></option>';
				
				if (column_simple_types && $.isPlainObject(column_simple_types)) {
					types += '<optgroup label="Simple Types">';
					
					$.each(column_simple_types, function(type_id, type_props) {
						var type_label = type_props["label"] ? type_props["label"] : type_id;
						var title = type_label + ":";
						
						for (var k in type_props)
							if (k != "label")
								title += "\n- " + k + ": " + type_props[k];
						
						types += '<option value="' + type_id + '"' + (current_attribute_type == type_id ? " selected" : "") + ' title="' + title + '">' + type_label + '</option>';
					});
					
					types += '</optgroup>';
					
					if (column_simple_types.hasOwnProperty(current_attribute_type))
						exists = true;
				}
				
				if (column_types && $.isPlainObject(column_types)) {
					types += '<option disabled></option>'
						  + '<optgroup label="Native Types">';
					
					$.each(column_types, function(type_id, type_label) {
						types += '<option value="' + type_id + '"' + (current_attribute_type == type_id ? " selected" : "") + '>' + type_label + '</option>';
					});
					
					types += '</optgroup>';
					
					if (column_types.hasOwnProperty(current_attribute_type))
						exists = true;
				}
				
				if (!exists)
					types += '<option disabled></option>'
						  + '<option value="' + current_attribute_type + '" selected>' + current_attribute_type + '</option>';
				
				select.append(types);
				select_checker(false);
				
				//prepare type and length fields events bc the firefox runs the mouseleave event when we open the select boxes.
				if (select.data("is_inited") != 1) {
					select.data("is_inited", 1);
					var span = select.parent().children("span");
					var timeout_id = null;
					var key_up_timeout_id = null;
					var change_timeout_id = null;
					
					var execute_event = function(secs, activate_mouseleave) {
						change_timeout_id && clearTimeout(change_timeout_id);
						key_up_timeout_id && clearTimeout(key_up_timeout_id);
						timeout_id && clearTimeout(timeout_id);
						
						timeout_id = setTimeout(function() {
							var type = select.val();
							var length = input.val();
							
							if (type != attribute_properties["type"] || length != attribute_properties["length"])
								span.trigger("click");
							
							if (activate_mouseleave)
								contextmenu.bind('mouseleave', function(e) {
									MyContextMenu.hideContextMenu(contextmenu);
								});
						}, secs);
					};
					
					select.bind("change", function(e) {
						//console.log("select change");
						select_checker(true);
						
						change_timeout_id && clearTimeout(change_timeout_id);
						
						change_timeout_id = setTimeout(function() {
							select.trigger("blur");
						}, 500);
					});
					select.bind("focus", function(e) {
						//console.log("select focus");
						contextmenu.unbind('mouseleave');
						timeout_id && clearTimeout(timeout_id);
						change_timeout_id && clearTimeout(change_timeout_id);
						key_up_timeout_id && clearTimeout(key_up_timeout_id);
					});
					select.bind("blur", function(e) {
						//console.log("select blur");
						execute_event(500, true);
					});
					
					input.bind("focus", function(e) {
						//console.log("input focus");
						contextmenu.unbind('mouseleave');
						timeout_id && clearTimeout(timeout_id);
						change_timeout_id && clearTimeout(change_timeout_id);
						key_up_timeout_id && clearTimeout(key_up_timeout_id);
					});
					input.bind("blur", function(e) {
						//console.log("input blur");
						execute_event(500, true);
					});
					input.bind("keyup", function(e) { //TOOD: This is wrong
						//console.log("input keyup");
						key_up_timeout_id && clearTimeout(key_up_timeout_id);
						
						key_up_timeout_id = setTimeout(function() {
							input.trigger("blur");
						}, 1500);
					});
				}
			}
		}
	}
	
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

function onLibContextMenu(target, contextmenu, originalEvent) {
	var a = $(originalEvent.target.parentNode);
	
	contextmenu.find(".manage_docbook a").attr("manage_docbook_url", a.attr("manage_docbook_url"));
	contextmenu.find(".view_docbook a").attr("view_docbook_url", a.attr("view_docbook_url"));
	contextmenu.find(".view_code a").attr("view_code_url", a.attr("view_code_url"));
	
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
	var properties_menu_item = contextmenu.find(".properties");
	var properties_prev_menu_item = properties_menu_item.prev("li");
	var properties_next_menu_item = properties_menu_item.next("li");
	var is_properties_prev_menu_item_separator = properties_prev_menu_item.is(".line_break") && (properties_next_menu_item.length == 0 || properties_next_menu_item.is(".line_break"));
	
	if (properties_id) {
		properties_menu_item.children("a").attr("properties_id", properties_id);
		properties_menu_item.show();
		
		if (is_properties_prev_menu_item_separator)
			properties_prev_menu_item.show();
	}
	else {
		properties_menu_item.hide();
		
		if (is_properties_prev_menu_item_separator)
			properties_prev_menu_item.hide();
	}
	
	mytree.deselectAll();
	
	var new_target_id = originalEvent.target.parentNode.parentNode.id;
	
	if (new_target_id) {
		contextmenu.attr("last_selected_node_id", new_target_id);
		mytree.selectNode(new_target_id);
		return true;
	}
	
	return false;
}

function initDBTablesSorting(elm) {
	var scroll_parent = elm.parent().closest(".scroll");
	var iframe = $("#right_panel > iframe");
	var iframe_win = iframe[0].contentWindow;
	var iframe_doc = iframe_win ? iframe_win.document : null;
	var iframe_offset = iframe.offset();
	var iframe_droppable_elm = null;
	var iframe_droppable_over_class = "drop_hover dragging_task task_droppable_over";
	var available_iframe_droppables_selectors = ".droppable, .tasks_flow, .connector_overlay_add_icon"; //".droppable" is related with the LayoutUIEditor, ".tasks_flow" is related with workflows and ".connector_overlay_add_icon" is related with Logic workflows.
	var PtlLayoutUIEditor = null;
	
	var lis = elm.children("li");
	var left_panel_droppable_handler = function(event, ui_obj) {
		var fk_table_li = $(this);
		var is_fk_table_li_ul = fk_table_li.is("ul") && fk_table_li.parent().is("li");
		
		if (is_fk_table_li_ul)
			fk_table_li = fk_table_li.parent();
		
		var fk_table_li_a = fk_table_li.children("a");
		var item = ui_obj.draggable;
		var a = item.children("a");
		
		fk_table_li.removeClass("drop_hover");
		
		if (fk_table_li_a.children("i.table").length == 1 && a.attr("table_name") != fk_table_li_a.attr("table_name")) { //if table is not it-self
			if (a.children("i.attribute").length == 1) {
				item.data("droppable_table_node", fk_table_li[0]);
				
				if (is_fk_table_li_ul)
					item.data("is_droppable_table_ul", true);
			}
			else if (a.children("i.table").length == 1) {
				var data = {
					attribute_table: a.attr("table_name"),
				};
				var callback = function(a, attr_name, action, new_name, url, tree_node_id_to_be_updated) {
					refreshAndShowNodeChildsByNodeId( fk_table_li.attr("id") ); //refresh all table's attributes
				};
				
				//copy attribute to another table, adding it as a foreign key
				manageDBTableAction(fk_table_li.children("a")[0], "add_fk_attribute_url", "add_fk_attribute", function(a, attr_name, action, new_name, url, tree_node_id_to_be_updated) {
					if (fk_table_li.hasClass("jstree-open")) {
						//add clone attribute
						var pk = item.find(" > ul > li.primary_key");
						
						if (pk[0]) {
							var clone = pk.clone();
							clone.removeClass("primary_key");
							
							fk_table_li.children("ul").append(clone);
						}
					}
					
					callback(a, attr_name, action, new_name, url, tree_node_id_to_be_updated);
				}, callback, data);
			}
		}
		
		//do not add "return false" otherwise the draggable will stop working for next iteractions
	};
	
	var right_panel_droppable_handler = function(event, ui_obj, tree_node, helper_clone) {
		//console.log(event);
		//console.log(ui_obj);
		
		var j_iframe_droppable_elm = $(iframe_droppable_elm);
		var li = ui_obj.helper;
		var li_a = li.children("a");
		
		//if dragged item is a table
		if (li_a.children("i.table").length > 0 && li_a.attr("table_name")) {
			var table_name = li_a.attr("table_name");
			var bean_name = li_a.attr("bean_name");
			var db_driver = getIframeBeanDBDriver(iframe_win, bean_name);
			
			//check if droppable is a LayoutUIEditor
			if (PtlLayoutUIEditor && typeof iframe_win.updateCodeLayoutUIEditorDBTableWidget == "function") {
				if (iframe_droppable_elm) { //if iframe_droppable_elm exists, it means it has the class: .droppable"
					//create widget and append it to iframe_droppable_elm
					var widget = $("<div></div>");
					iframe_droppable_elm.append(widget);
					
					//add widget in the right place and disable classes in LayoutUIEditor's droppable
					var new_event = {
						clientX: event.clientX - iframe_offset.left,
						clientY: event.clientY - iframe_offset.top,
					};
					PtlLayoutUIEditor.onWidgetDraggingStop(new_event, helper_clone, widget);
					
					//prepare widget props
					onChooseLayoutUIEditorDBTableWidgetOptions(iframe_win, db_driver, table_name, widget);
				}
				else
					PtlLayoutUIEditor.showError("Please drop element inside of a droppable element in the design area.");
			}
			//check if droppable is a DB Diagram
			else if (typeof iframe_win.addExistentTable == "function") {
				if (iframe_droppable_elm) { //if iframe_droppable_elm exists, it means it has the class: .tasks_flow"
					var tasks_flow_offset = j_iframe_droppable_elm.offset();
					var tasks_flow_event_x = event.clientX - iframe_offset.left - tasks_flow_offset.left;
					var tasks_flow_event_y = event.clientY - iframe_offset.top - tasks_flow_offset.top;
					
					//add table to diagram
					iframe_win.addExistentTable(table_name, {
						top: tasks_flow_event_y,
						left: tasks_flow_event_x,
					});
				}
				else
					iframe_win.jsPlumbWorkFlow.jsPlumbStatusMessage.showError("Please drop element inside of diagram");
			}
			//check if droppable is Logic Diagram
			else if (typeof iframe_win.DBDAOActionTaskPropertyObj == "object") {
				if (iframe_droppable_elm) {
					var tasks_menu = j_iframe_droppable_elm.parent().closest(".taskflowchart").children(".tasks_menu");
					var task_menu = tasks_menu.find(".task.task_menu.task_dbdaoaction");
					var task_type = task_menu.attr("type");
					
					if (task_type) {
						var url = $(tree_node).children("ul").attr("url");
						
						//show popup with possible actions
						onChooseWorkflowDBTableTaskOptions(event, iframe_droppable_elm, iframe_win, iframe_offset, db_driver, table_name, task_type, url);
					}
					else
						iframe_win.jsPlumbWorkFlow.jsPlumbStatusMessage.showError("This diagram doesn't allow the drop action for this element.");
				}
				else
					iframe_win.jsPlumbWorkFlow.jsPlumbStatusMessage.showError("Please drop element inside of diagram");
			}
			else
				StatusMessageHandler.showError("Sorry, droppable not allowed...");
		}
		else if (li_a.children("i.query").length > 0) { //ibatis query => create callibatisquery or callhibernatemethod task
			//check if query belongs to a hibernate obj
			var parent_li = li.parent().parent();
			var is_hbn_obj = parent_li.children("a").children("i.obj").length > 0;
			
			if (
				(!is_hbn_obj && typeof iframe_win.CallIbatisQueryTaskPropertyObj == "object") || 
				(is_hbn_obj && typeof iframe_win.CallHibernateMethodTaskPropertyObj == "object")
			) {
				if (iframe_droppable_elm) {
					var tasks_menu = j_iframe_droppable_elm.parent().closest(".taskflowchart").children(".tasks_menu");
					var task_menu = is_hbn_obj ? tasks_menu.find(".task.task_menu.task_callhibernatemethod") : tasks_menu.find(".task.task_menu.task_callibatisquery");
					var task_type = task_menu.attr("type");
					
					if (task_type) {
						//TODO: create task
					}
					else
						iframe_win.jsPlumbWorkFlow.jsPlumbStatusMessage.showError("This diagram doesn't allow the drop action for this element.");
				}
				else
					iframe_win.jsPlumbWorkFlow.jsPlumbStatusMessage.showError("Please drop element inside of diagram");
			}
			else
				StatusMessageHandler.showError("Sorry, droppable not allowed...");
		}
		else if (li_a.children("i.obj").length > 0) { //hibernate obj => create callhibernateobject task
			if (typeof iframe_win.CallHibernateObjectTaskPropertyObj == "object") {
				if (iframe_droppable_elm) {
					var tasks_menu = j_iframe_droppable_elm.parent().closest(".taskflowchart").children(".tasks_menu");
					var task_menu = tasks_menu.find(".task.task_menu.task_callhibernateobject");
					var task_type = task_menu.attr("type");
					
					if (task_type) {
						//TODO: create task
					}
					else
						iframe_win.jsPlumbWorkFlow.jsPlumbStatusMessage.showError("This diagram doesn't allow the drop action for this element.");
				}
				else
					iframe_win.jsPlumbWorkFlow.jsPlumbStatusMessage.showError("Please drop element inside of diagram");
			}
			else
				StatusMessageHandler.showError("Sorry, droppable not allowed...");
		}
		else if (li_a.children("i.file").length > 0) { //file => create includefile task
			//check if file has a php extension
			
			if (typeof iframe_win.IncludeFileTaskPropertyObj == "object") {
				if (iframe_droppable_elm) {
					var tasks_menu = j_iframe_droppable_elm.parent().closest(".taskflowchart").children(".tasks_menu");
					var task_menu = tasks_menu.find(".task.task_menu.task_includefile");
					var task_type = task_menu.attr("type");
					
					if (task_type) {
						//TODO: create task
					}
					else
						iframe_win.jsPlumbWorkFlow.jsPlumbStatusMessage.showError("This diagram doesn't allow the drop action for this element.");
				}
				else
					iframe_win.jsPlumbWorkFlow.jsPlumbStatusMessage.showError("Please drop element inside of diagram");
			}
			else
				StatusMessageHandler.showError("Sorry, droppable not allowed...");
		}
		else
			StatusMessageHandler.showError("Sorry, droppable not allowed...");
	};
	
	var getIframeElementFromPoint = function(inner_iframe, x, y, helper, helper_clone) {
		var inner_iframe_win = inner_iframe.contentWindow;
		var inner_iframe_doc = inner_iframe_win ? inner_iframe_win.document : null;
		var inner_iframe_offset = $(inner_iframe).offset();
		var inner_iframe_droppable_elm = null;
		
		if (inner_iframe_doc) {
			//hide helpers
			var helper_visible = helper.css("display") != "none";
			var helper_clone_visible = helper_clone.css("display") != "none";
			
			if (helper_visible)
				helper.hide();
			
			if (helper_clone_visible)
				helper_clone.hide();
			
			//get droppable element
			var inner_iframe_event_x = x - inner_iframe_offset.left;
			var inner_iframe_event_y = y - inner_iframe_offset.top;
			
			var inner_iframe_droppable_elm = inner_iframe_doc.elementFromPoint(inner_iframe_event_x, inner_iframe_event_y);
			
			if (inner_iframe_droppable_elm && inner_iframe_droppable_elm.nodeName && inner_iframe_droppable_elm.nodeName.toUpperCase() == "IFRAME")
				inner_iframe_droppable_elm = getIframeElementFromPoint(inner_iframe_droppable_elm, inner_iframe_event_x, inner_iframe_event_y, helper, helper_clone);
			
			//show helpers
			if (helper_visible)
				helper.show();
			
			if (helper_clone_visible)
				helper_clone.show();
		}
		
		return inner_iframe_droppable_elm;
	};
	
	lis.droppable({
		greedy: true,
		over: function(event, ui_obj) {
			$(this).addClass("drop_hover");
		},
		out: function(event, ui_obj) {
			$(this).removeClass("drop_hover");
		},
		drop: left_panel_droppable_handler,
	})
	.draggable({
		//settings for the iframe droppable
		iframeFix:true,
	     iframeScroll: true,
	     scroll: true,
	     scrollSensitivity: 20,
	     
	     //others settings
	    	items: "li.jstree-node.jstree-leaf",
		//containment: elm, //we can drag the tables to the DB Diagram or to LayoutUIEditor in edit_entity_simple and edit_template_simple.
		//appendTo: elm, //disable to allow copy attribute accross different tables.
		handle: "> a.jstree-anchor > i.jstree-icon.table",
		revert: true,
		cursor: "crosshair",
          tolerance: "pointer",
		grid: [5, 5],
		//axis: "y", //we can drag the tables to the DB Diagram or LayoutUIEditor in edit_entity_simple and edit_template_simple.
		
		//handlers
		helper: function() {
			var clone = $(this).clone();
			clone.addClass("sortable_helper");
			clone.children("a").removeClass("jstree-hovered jstree-clicked");
			clone.children("ul").remove();
			clone.children(".sub_menu").remove();
			
			return clone;
		},
		start: function(event, ui_obj) {
			var helper_clone = ui_obj.helper.clone();
			$("body").append(helper_clone);
			
			iframe_win = iframe[0].contentWindow;
			iframe_doc = iframe_win ? iframe_win.document : null;
			iframe_offset = iframe.offset();
			PtlLayoutUIEditor = iframe[0].contentWindow.$(".code_layout_ui_editor .layout-ui-editor").data("LayoutUIEditor");
		},
		drag: function(event, ui_obj) {
			//prepare scroll_parent element when the dragged element will be out of the left panel and dropped in the right panel to the DB diagram or to edit_entity_simple and edit_template_simple files.
			var helper = ui_obj.helper;
			helper.show();
			
			var right_edge = scroll_parent.offset().left + scroll_parent.width();
			var is_in_edge = (helper.offset().left + helper.width()) > (right_edge - 20);
			
			if (is_in_edge && scroll_parent.hasClass("scroll")) {
				var st = scroll_parent.scrollTop();
				var sl = scroll_parent.scrollLeft();
				
				scroll_parent.data("mt", scroll_parent.css("margin-top"));
				scroll_parent.data("ml", scroll_parent.css("margin-left"));
				scroll_parent.data("st", st);
				scroll_parent.data("sl", sl);
				
				scroll_parent.removeClass("scroll");
				scroll_parent.css("margin-top", "-" + st + "px");
				scroll_parent.css("margin-left", "-" + sl + "px");
				
				helper.css("margin-top", st + "px");
				helper.css("margin-left", sl + "px");
			}
			else if (!is_in_edge && !scroll_parent.hasClass("scroll")) {
				scroll_parent.addClass("scroll");
				scroll_parent.css("margin-top", scroll_parent.data("mt"));
				scroll_parent.css("margin-left", scroll_parent.data("ml"));
				scroll_parent.scrollTop( scroll_parent.data("st") );
				scroll_parent.scrollLeft( scroll_parent.data("sl") );
				
				helper.css("margin-top", "");
				helper.css("margin-left", "");
			}
			
			//prepare helper_clone
			var helper_clone = $("body").children(".sortable_helper");
			var is_in_right_panel = event.clientX > iframe.offset().left;
			
			helper_clone.offset({
				top: event.clientY,
				left: event.clientX,
			});
			
			if (is_in_right_panel) {
				//get droppable
				var new_iframe_droppable_elm = getIframeElementFromPoint(iframe[0], event.clientX, event.clientY, helper, helper_clone);
				
				//hide helper from left panel and show the one from the right panel
				helper_clone.show();
				helper.hide();
				
				//get real droppable based in class
				if (new_iframe_droppable_elm)
					new_iframe_droppable_elm = new_iframe_droppable_elm.closest(available_iframe_droppables_selectors);
				
				//remove from old iframe_droppable_elm
				if (iframe_droppable_elm && new_iframe_droppable_elm != iframe_droppable_elm)
					$(iframe_droppable_elm).removeClass(iframe_droppable_over_class); 
				
				//set new iframe_droppable_elm
				iframe_droppable_elm = new_iframe_droppable_elm;
				
				//prepare PtlLayoutUIEditor
				if (PtlLayoutUIEditor) {
					var new_event = {
						clientX: event.clientX - iframe_offset.left,
						clientY: event.clientY - iframe_offset.top,
					};
					PtlLayoutUIEditor.onWidgetDraggingDrag(new_event, helper_clone, null);
				}
				else if (iframe_droppable_elm) //prepare droppable over class
					$(iframe_droppable_elm).addClass(iframe_droppable_over_class);
			}
			else {
				helper_clone.hide();
				//helper.show(); //no need bc I already show it above
				
				//prepare PtlLayoutUIEditor
				if (PtlLayoutUIEditor) {
					var new_event = {
						clientX: -1,
						clientY: -1,
					};
					PtlLayoutUIEditor.onWidgetDraggingDrag(new_event, helper_clone, null);
				}
				else if (iframe_droppable_elm) //remove droppable over class
					$(iframe_droppable_elm).removeClass(iframe_droppable_over_class);
			}
		},
		stop: function(event, ui_obj) {
			var helper = ui_obj.helper;
			var helper_clone = $("body").children(".sortable_helper");
			
			helper.show();
			//helper_clone.hide(); //Do not hide helper_clone bc right_panel_droppable_handler will use its position in PtlLayoutUIEditor
			
			//prepare scroll_parent and call stop handler
			if (!scroll_parent.hasClass("scroll")) {
				scroll_parent.addClass("scroll");
				scroll_parent.css("margin-top", scroll_parent.data("mt"));
				scroll_parent.css("margin-left", scroll_parent.data("ml"));
				scroll_parent.scrollTop( scroll_parent.data("st") );
				scroll_parent.scrollLeft( scroll_parent.data("sl") );
				
				if (iframe_droppable_elm) //remove droppable over class
					$(iframe_droppable_elm).removeClass(iframe_droppable_over_class);
				
				right_panel_droppable_handler(event, ui_obj, this, helper_clone);
				
				//disable classes in LayoutUIEditor's droppable, just in case the right_panel_droppable_handler did NOT do it already
				if (PtlLayoutUIEditor) {
					var new_event = {
						clientX: event.clientX - iframe_offset.left,
						clientY: event.clientY - iframe_offset.top,
					};
					PtlLayoutUIEditor.onWidgetDraggingStop(new_event, helper_clone, null);
				}
			}
			
			helper.remove();
			helper_clone.remove();
			
			//do not add "return false" otherwise the draggable will stop working for next iteractions
		},
	});
	
	//ignore if inner ul, bc the initDBTableAttributesSorting method already takes care of this
	lis.children("ul").droppable({
		greedy: true,
		over: function(event, ui_obj) {
			$(this).parent().addClass("drop_hover");
		},
		out: function(event, ui_obj) {
			$(this).parent().removeClass("drop_hover");
		},
		drop: left_panel_droppable_handler,
	});
}

function getIframeBeanDBDriver(iframe_win, bean_name) {
	var db_driver = bean_name ? bean_name.toLowerCase() : "";
	
	//get db driver in iframe based in the bean name.
	if (bean_name && $.isPlainObject(iframe_win.brokers_db_drivers))
		for (var db_driver_name in iframe_win.brokers_db_drivers) {
			var db_driver_props = iframe_win.brokers_db_drivers[db_driver_name];
			
			if ($.isArray(db_driver_props) && db_driver_props[2] == bean_name) {
				db_driver = db_driver_name;
				break;
			}
		}
}

function onChooseLayoutUIEditorDBTableWidgetOptions(iframe_win, db_driver, table_name, widget) {
	var popup = $(".choose_db_table_widget_options_popup");
	
	if (!popup[0]) {
		popup = $('<div class="myfancypopup with_title choose_db_table_widget_options_popup">'
				+ '<div class="title">Choose your options:</div>'
				+ '<div class="widget_group">'
					+ '<label>UI Type:</label>'
					+ '<select>'
						+ '<option value="list">List</option>'
						+ '<option value="form">Form</option>'
					+ '</select>'
				+ '</div>'
				+ '<div class="widget_action">'
					+ '<label>Action:</label>'
					+ '<select>'
						+ '<option value="view">View</option>'
						+ '<option value="edit">Edit</option>'
						+ '<option value="remove">Remove</option>'
						+ '<option value="add">Add</option>'
					+ '</select>'
				+ '</div>'
				+ '<div class="button">'
					+ '<input type="button" value="Proceed" onclick="MyFancyPopupDBTable.settings.updateFunction(this)">'
				+ '</div>'
			+ '</div>');
		$(document.body).append(popup);
	}
	
	MyFancyPopupDBTable.init({
		elementToShow: popup,
		parentElement: document,
		
		updateFunction: function(elm) {
			var widget_group = popup.find(".widget_group select").val();
			var widget_action = popup.find(".widget_action select").val();
			
			//hide popup first
			MyFancyPopupDBTable.hidePopup();
			
			//replace widget by real widget
			iframe_win.updateCodeLayoutUIEditorDBTableWidget(widget, {
		    		widget_group: widget_group,
		    		widget_action: widget_action,
		    		db_driver: db_driver,
		    		db_type: "db",
				db_table: table_name,
			});
		},
	});
	MyFancyPopupDBTable.showPopup();
}

function onChooseWorkflowDBTableTaskOptions(event, iframe_droppable_elm, iframe_win, iframe_offset, db_driver, table_name, task_type, url) {
	var popup = $(".choose_db_table_task_options_popup");
	
	if (!popup[0]) {
		popup = $('<div class="myfancypopup with_title choose_db_table_task_options_popup">'
				+ '<div class="title">Choose your options:</div>'
				+ '<div class="method_name">'
					+ '<label>Action:</label>'
					+ '<select>'
						+ '<option value="insertObject">Insert</option>'
						+ '<option value="updateObject">Update</option>'
						+ '<option value="deleteObject">Delete</option>'
						+ '<option value="findObjects">List</option>'
						+ '<option value="countObjects">Count</option>'
					+ '</select>'
				+ '</div>'
				+ '<div class="button">'
					+ '<input type="button" value="Proceed" onclick="MyFancyPopupDBTable.settings.updateFunction(this)">'
				+ '</div>'
			+ '</div>');
		$(document.body).append(popup);
	}
	
	MyFancyPopupDBTable.init({
		elementToShow: popup,
		parentElement: document,
		
		updateFunction: function(elm) {
			//get table attributes url
			if (url) {
				var method_name = popup.find(".method_name select").val();
				var j_iframe_droppable_elm = $(iframe_droppable_elm);
				
				//show workflow loading
				iframe_win.jsPlumbWorkFlow.getMyFancyPopupObj().showLoading();
				
				//fetch url
				$.ajax({
					type : "get",
					url : url,
					dataType : "json",
					success : function(data, textStatus, jqXHR) {
						var task_id = null;
						var jsPlumbWorkFlow = iframe_win.jsPlumbWorkFlow;
						var DBDAOActionTaskPropertyObj = iframe_win.DBDAOActionTaskPropertyObj;
						
						//prepare table attributes
						var table_attributes = {};
						
						for (var attribute_name in data)
							if (attribute_name != "properties") {
								var attribute_props = data[attribute_name]["properties"];
								delete attribute_props["item_id"];
								delete attribute_props["item_type"];
								delete attribute_props["item_menu"];
								delete attribute_props["bean_name"];
								delete attribute_props["bean_file_name"];
								delete attribute_props["table"];
								
								table_attributes[attribute_name] = attribute_props;
							}
						
						//preparing droppable if is ".connector_overlay_add_icon"
						if (j_iframe_droppable_elm.hasClass("connector_overlay_add_icon")) {
							var overlay_id = j_iframe_droppable_elm.attr("id");
							var droppable_connection = jsPlumbWorkFlow.jsPlumbTaskFlow.getOverlayConnectionId(overlay_id);
							
							if (droppable_connection)
								task_id = jsPlumbWorkFlow.jsPlumbContextMenu.addTaskByTypeToConnection(task_type, droppable_connection);
						}
						//preparing droppable if is ".tasks_flow"
						else {
							var tasks_flow_offset = j_iframe_droppable_elm.offset();
							var tasks_flow_event_x = event.clientX - iframe_offset.left - tasks_flow_offset.left;
							var tasks_flow_event_y = event.clientY - iframe_offset.top - tasks_flow_offset.top;
							
							task_id = jsPlumbWorkFlow.jsPlumbContextMenu.addTaskByType(task_type, {
								top: tasks_flow_event_y,
								left: tasks_flow_event_x,
							});
						}
						
						//preparing task properties according with dragged and dropped table
						if (task_id) {
							//set task label
							var label_obj = {label: method_name.replace(/_/g, " ") + " " + table_name};
							
							jsPlumbWorkFlow.jsPlumbTaskFlow.setTaskLabelByTaskId(task_id, label_obj); //set {label: table_name}, so the jsPlumbTaskFlow.setTaskLabel method ignores the prompt and adds the default label or an auto generated label.
							
							//open properties
							jsPlumbWorkFlow.jsPlumbProperty.showTaskProperties(task_id);
							
							//prepare properties
							var selected_task_properties = iframe_win.$("#" + jsPlumbWorkFlow.jsPlumbProperty.selected_task_properties_id);
							var task_html_elm = selected_task_properties.find(".db_dao_action_task_html");
							
							var select = task_html_elm.find(".method_name select");
							select.val(method_name);
							DBDAOActionTaskPropertyObj.onChangeMethodName(select[0]);
							
							var table_and_attributes = {
								table: table_name,
								attributes: table_attributes,
							};
							DBDAOActionTaskPropertyObj.chooseTable(select[0], table_and_attributes);
							
							//save properties
							jsPlumbWorkFlow.jsPlumbProperty.saveTaskProperties();
							
							//get saved task properties
							var task_property_values = jsPlumbWorkFlow.jsPlumbTaskFlow.tasks_properties[task_id];
							task_property_values = task_property_values ? task_property_values : {};
							
							//set db driver, if not the default one
							if (db_driver && db_driver != iframe_win.default_db_driver) {
								task_property_values["options_type"] = "array";
								task_property_values["options"] = {
									key: "db_driver",
									key_type: "string",
									value: db_driver,
									value_type: "string"
								};
							}
							
							//set new task properties
							jsPlumbWorkFlow.jsPlumbTaskFlow.tasks_properties[task_id] = task_property_values;
							
							//load again task
							jsPlumbWorkFlow.jsPlumbProperty.showTaskProperties(task_id);
						}
						
						iframe_win.jsPlumbWorkFlow.getMyFancyPopupObj().hideLoading();
					},
					error : function(jqXHR, textStatus, errorThrown) { 
						var msg = jqXHR.responseText ? "\n" + jqXHR.responseText : "";
						jsPlumbWorkFlow.jsPlumbStatusMessage.showError((errorThrown ? errorThrown + " error.\n" : "") + "Error trying to get table attributes.\nPlease try again..." + msg);
						
						iframe_win.jsPlumbWorkFlow.getMyFancyPopupObj().hideLoading();
					},
				});
			}
			else
				iframe_win.jsPlumbWorkFlow.jsPlumbStatusMessage.showError("Could not get table attributes because there is no table correspondent url!");
			
			MyFancyPopupDBTable.hidePopup();
		},
	});
	MyFancyPopupDBTable.showPopup();
}

function initDBTableAttributesSorting(elm) {
	var li = elm.parent();
	var tables_ul = li.parent();
	
	elm.sortable({
		scroll: true,
		scrollSensitivity: 20,
		//refreshPositions: true,
		
		connectWith: "ul",
		items: "li.jstree-node.jstree-leaf",
		containment: tables_ul,
		//appendTo: elm, //disable to allow copy attribute accross different tables.
		handle: "> a.jstree-anchor > i.jstree-icon.attribute",
		revert: true,
		cursor: "ns-resize",
          tolerance: "pointer",
		grid: [5, 5],
		axis: "y",
		helper: function(event, item) {
			var clone = item.clone();
			clone.addClass("sortable_helper");
			clone.children("a").removeClass("jstree-hovered jstree-clicked");
			clone.children("ul").remove();
			clone.children(".sub_menu").remove();
			
			return clone;
		},
		start: function(event, ui_obj) {
			//check if dragged item contains the attribute sort_url
			var item = ui_obj.item;
			var sort_url = item.children("a").attr("sort_url");
			
			if (sort_url) {
				item.show();
				item.data("parent_ul", item.parent());
				item.data("droppable_table_node", null);
				item.data("is_droppable_table_ul", null);
				
				return true;
			}
			
			return false;
		},
		sort: function(event, ui_obj) {
			if (ui_obj.placeholder.parent().is( ui_obj.item.data("parent_ul") ))
				$(this).sortable("option", "cursor", "crosshair"); //set cursor to crosshair
			else
				$(this).sortable("option", "cursor", "ns-resize"); //set cursor to ns-resize
		},
		stop: function(event, ui_obj) {
			var item = ui_obj.item;
			var a = item.children("a");
			var original_parent_ul = item.data("parent_ul");
			var parent_ul = item.parent();
			var parent_li = parent_ul.parent();
			
			item.data("parent_ul", null);
			
			if (ui_obj.helper)
				ui_obj.helper.remove();
			
			if (parent_li.find(" > a > i.table").length == 1 && original_parent_ul) {
				var attribute_table = a.attr("table_name");
				var attribute_name = a.attr("attribute_name");
				var attribute_index = item.index();
				
				var previous_item = item.prev("li:not(.ui-sortable-helper)");
				var next_item = item.next("li:not(.ui-sortable-helper)");
				var previous_attribute = previous_item.length ? previous_item.children("a").attr("attribute_name") : null;
				var next_attribute = next_item.length ? next_item.children("a").attr("attribute_name") : null;
				
				var droppable_table_node = item.data("droppable_table_node");
				var is_droppable_table_ul = item.data("is_droppable_table_ul");
				
				if (droppable_table_node) {
					parent_li = $(droppable_table_node);
					parent_ul = parent_li.children("ul");
					
					if (!is_droppable_table_ul) //only resets vars if droppable is in table li, and not in the ul, bc it will get the wrong values.
						attribute_index = previous_item = next_item = previous_attribute = next_attribute = null;
				}
				
				var data = {
					attribute_table: attribute_table,
					attribute_name: attribute_name,
					attribute_index: attribute_index,
					previous_attribute: previous_attribute,
					next_attribute: next_attribute,
				};
				var callback = function(a, attr_name, action, new_name, url, tree_node_id_to_be_updated) {
					refreshAndShowNodeChildsByNodeId( parent_li.attr("id") ); //refresh all table's attributes
				};
				
				//move attribute
				if (parent_ul.is(original_parent_ul) && !droppable_table_node) {
					manageDBTableAction(a[0], "sort_url", "sort_attribute", callback, callback, data);
					
					return true; //true: so the attribute can be moved to the new position.
				}
				else { //copy attribute to another table, adding it as a foreign key
					manageDBTableAction(parent_li.children("a")[0], "add_fk_attribute_url", "add_fk_attribute", function(a, attr_name, action, new_name, url, tree_node_id_to_be_updated) {
						//add clone attribute
						var clone = item.clone();
						clone.removeClass("primary_key");
						
						if (previous_attribute) //prepare previous_item bc it looses its reference
							previous_item = parent_li.find(" > ul > li > a[attribute_name=" + previous_attribute + "]").parent();
						
						if (next_attribute) //prepare previous_item bc it looses its reference
							next_item = parent_li.find(" > ul > li > a[attribute_name=" + next_attribute + "]").parent();
						
						if (previous_item && previous_item.length > 0)
							previous_item.after(clone);
						else if (next_item && next_item.length > 0)
							next_item.before(clone);
						else
							parent_ul.append(clone);
						
						callback(a, attr_name, action, new_name, url, tree_node_id_to_be_updated);
					}, callback, data);
					
					return false; //false: so the attribute can be reverted to the initial position.
				}
			}
			
			return false; //false: so the attribute can be reverted to the initial position.
		},
	});
}

function showProperties(menu_item) {
	var selected_menu_properties = $("#selected_menu_properties");
	selected_menu_properties.hide();
	
	var id = menu_item.getAttribute("properties_id");
	//console.log(menu_item);
	var html;
	
	if (id && menu_item_properties.hasOwnProperty(id) && menu_item_properties[id]) {
		var properties = menu_item_properties[id];
		
		if (properties) {
			html = "";
			
			for (var key in properties) {
				var value = properties[key];
				
				key = key.replace(/_/g, " ").toLowerCase();
				key = key.charAt(0).toUpperCase() + key.slice(1);
				
				html += "<label>" + key + ": </label>" + value + "<br/>\n";
			}
		}
		else
			html = "There are no properties to be shown";
	}
	else
		html = "There are no properties to be shown";
	
	selected_menu_properties.find(".content").html(html);
	
	MyFancyPopup.init({
		elementToShow: $("#selected_menu_properties")
	});
	
	MyFancyPopup.showPopup();
	
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
	
	MyContextMenu.hideAllContextMenu();
	
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
		
		MyContextMenu.hideAllContextMenu();
		
		if(win) { //Browser has allowed it to be opened
			win.focus();
			return win;
		}
		else //Broswer has blocked it
			alert('Please allow popups for this site');
	}
}

function goToPopup(a, attr_name, originalEvent, popup_class_name, on_success_popup_action_handler) {
	var url = a.getAttribute(attr_name);
	//console.log(attr_name+":"+url);
	
	if (url) {
		var popup = $(".go_to_popup");
		
		if (!popup[0]) {
			popup = $('<div class="myfancypopup go_to_popup ' + (popup_class_name ? popup_class_name : "") + '"></div>');
			$(document.body).append(popup);
		}
		else
			popup[0].className = 'myfancypopup go_to_popup ' + (popup_class_name ? popup_class_name : "");
		
		popup.html('<iframe src="' + url + '"></iframe>');
		
		MyFancyPopup.init({
			elementToShow: popup,
			//parentElement: document,
			
			on_success_popup_action_handler: on_success_popup_action_handler,
		});
		MyFancyPopup.showPopup();
		
		var j_a = $(a);
		if (j_a.hasClass("jstree-anchor")) 
			last_selected_node_id = j_a.parent().attr("id");
		else 
			last_selected_node_id = j_a.parent().parent().attr("last_selected_node_id");
	}
	
	return false;
}

function onSuccessfullPopupAction(opts) {
	if (MyFancyPopup.settings && typeof MyFancyPopup.settings.on_success_popup_action_handler == "function")
		MyFancyPopup.settings.on_success_popup_action_handler(opts);
	
	MyFancyPopup.hidePopup();
}

function onSucccessfullEditProject(opts) {
	if (opts && opts["is_rename_project"]) {
		var url = "" + document.location;
		url = url.indexOf("#") != -1 ? url.substr(0, url.indexOf("#")) : url; //remove # so it can refresh page
		
		if (url.match(/(&|\?)filter_by_layout\s*=([^&#]+)/)) { //check if parent url has any filter_by_layout
			url = url.replace(/(&|\?)filter_by_layout\s*=\s*([^&#]*)/, "");
			
			if (opts["new_filter_by_layout"])
				url += (url.indexOf("?") != -1 ? "&" : "?") + "filter_by_layout=" + opts["new_filter_by_layout"];
		}
		
		//get default_page url and check if contains filter_by_layout in the url and if so, replace it with new project name
		var default_page = MyJSLib.CookieHandler.getCookie('default_page');
		
		if (default_page && opts["new_filter_by_layout"]) {
			if (default_page.match(/(&|\?)filter_by_layout\s*=([^&#]+)/)) { //check if default_page url has any filter_by_layout
				default_page = default_page.replace(/(&|\?)filter_by_layout\s*=\s*([^&#]*)/, "");
				default_page += (default_page.indexOf("?") != -1 ? "&" : "?") + "filter_by_layout=" + opts["new_filter_by_layout"];
			}
			
			//set cookie with default page
			MyJSLib.CookieHandler.setCookie('default_page', default_page, 0, "/"); //save cookie with url, so when we refresh the browser, the right panel contains the latest opened url
		}
		
		document.location = url;
	}
	else
		refreshLastNodeParentChildsIfNotTreeLayoutAndMainTreeNode(opts);
}

function refreshLastNodeParentChildsIfNotTreeLayoutAndMainTreeNode(opts) {
	var pid = getLastNodeParentId();
	
	if (pid && $("#left_panel").is(".left_panel_with_tabs") && $("#left_panel .mytree #" + pid).is(".hide_tree_item"))
		return ;
	
	if (last_selected_node_id && $("#" + last_selected_node_id + " > a > i.project")[0])
		refreshLastNodeParentChilds();
	else
		refreshLastNodeChilds();
}

function manageFile(a, attr_name, action, on_success_callbacks) {
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
				var jstree_attr = $("#" + tree_node_id_to_be_updated).attr("data-jstree");
				var file_type = jstree_attr == '{"icon":"project"}' ? "project" : (jstree_attr == '{"icon":"project_folder"}' ? "projects folder" : null);
				
				if (file_type)
					status = confirm("Do you wish to remove the " + file_type + ": '" + file_name + "'?") && confirm("If you delete this " + file_type + ", you will loose all the created pages and other files inside of this " + file_type + "!\nDo you wish to continue?") && confirm("LAST WARNING:\nIf you proceed, you cannot undo this deletion!\nAre you sure you wish to remove this " + file_type + "?");
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
					new_file_name = ("" + new_file_name).replace(/^\s+/g, "").replace(/\s+$/g, ""); //trim name
					
					if (status && new_file_name)
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
					status = copy_or_cut_action == "cut" ? confirm("You are about to cut and paste the file '" + props[2] + "' from the '" + props[0] + "' Layer to the '" + file_name + "' folder.\nDo you wish to continue?") : true; 
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
				var allow_upper_case = a.getAttribute("allow_upper_case") == 1; //in case of businesslogic services class
				new_file_name = normalizeFileName(new_file_name, allow_upper_case);
			}
			
			if (is_file_new_name_action && !new_file_name)
				alert("Error: File name cannot be empty");
			else {
				url = url.replace("#action#", action);
				url = url.replace("#extra#", new_file_name);
				
				url = encodeUrlWeirdChars(url); //Note: Is very important to add the encodeUrlWeirdChars otherwise if a value has accents, won't work in IE.
				
				var str = action == "create_folder" || action == "create_file" ? "create" : action.replace(/_/g, " ");
				
				$.ajax({
					type : "get",
					url : url,
					success : function(data, textStatus, jqXHR) {
						if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
							showAjaxLoginPopup(jquery_native_xhr_object.responseURL, url, function() {
								StatusMessageHandler.removeLastShownMessage("error");
								manageFile(a, attr_name, original_action, on_success_callbacks);
							});
						else if (data == "1") {
							if (action == "create_folder" || action == "create_file" || action == "paste" || action == "paste_and_remove")
								refreshAndShowNodeChildsByNodeId(tree_node_id_to_be_updated);
							else if (action != "remove")
								refreshNodeParentChildsByChildId(tree_node_id_to_be_updated);
							
							StatusMessageHandler.showMessage("File " + str + (action == "unzip" || action == "zip" ? "pe" : "") + "d correctly");
							
							on_success_callbacks = $.isArray(on_success_callbacks) ? on_success_callbacks : [on_success_callbacks];
							for (var i = 0; i < on_success_callbacks.length; i++)
								if (typeof on_success_callbacks[i] == "function")
									on_success_callbacks[i](a, attr_name, action, new_file_name, url, tree_node_id_to_be_updated);
							
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
	
	MyContextMenu.hideAllContextMenu();
	
	return false;
}

function normalizeFileName(new_file_name, allow_upper_case) {
	//normalize new file name
	if (new_file_name) {
		var has_accents = new_file_name.match(/([\x7f-\xff\u1EBD\u1EBC]+)/gi);
		var has_spaces = new_file_name.match(/\s+/g);
		var has_upper_case = !allow_upper_case && new_file_name.toLowerCase() != new_file_name;
		//var has_weird_chars = new_file_name.match(/([\p{L}\w\.]+)/giu).join("") != new_file_name; // \. is very important bc the new_file_name is the complete filename with the extension. \p{L} and /../u is to get parameters with accents and ç. Already includes the a-z. Cannot use this bc it does not work in IE.
		var has_weird_chars = new_file_name.match(/([\w\u00C0-\u00D6\u00D8-\u00F6\u00F8-\u024F\u1EBD\u1EBC\.]+)/gi);
		has_weird_chars = has_weird_chars && has_weird_chars.join("") != new_file_name; // \. is very important bc the new_file_name is the complete filename with the extension. '\w' means all words with '_' and 'u' means with accents and ç too.
		
		if ((has_accents || has_spaces || has_upper_case || has_weird_chars) && confirm("Is NOT advisable to have file names with spaces, dashes, letters with accents, upper case letters or weird characters.\nYou should only use the following letters: A-Z, 0-9 and '_'.\nCan I normalize this name and convert it to a proper name?")) {
			if (typeof new_file_name.normalize == "function") //This doesn't work in IE11
				new_file_name = new_file_name.normalize("NFD");
			
			new_file_name = new_file_name.replace(/[\u0300-\u036f]/g, "").replace(/[\s\-]+/g, "_").match(/[\w\.]+/g).join(""); // \. is very important bc the new_file_name is the complete filename with the extension.
			
			if (!allow_upper_case)
				new_file_name = new_file_name.toLowerCase();
		}
	}
	
	return new_file_name;
}

function renameProject(a, attr_name, action, new_file_name, url, tree_node_id_to_be_updated) {
	alert("Please don't forget to go to the permissions panel and update the correspondent permissions..."); //Do not use StatusMessageHandler.showMessage bc the onSucccessfullEditProject will refresh the main page
	
	//refresh page and replace old project in url 
	var opts = {
		is_rename_project: true,
		layer_bean_folder_name: null,
		old_filter_by_layout: null,
		new_filter_by_layout: null,
	};
	
	var bean_name = getParameterByName(url, "bean_name");
	var bean_file_name = getParameterByName(url, "bean_file_name");
	var file_path = getParameterByName(url, "path");
	file_path = file_path ? file_path.replace(/\/$/g, "") : ""; //remove last /
	
	var file_name = file_path;
	var folder_path = "";
	
	if (file_path.lastIndexOf("/") != -1) {
		file_name = file_path.substr(file_path.lastIndexOf("/") + 1);
		folder_path = file_path.substr(0, file_path.lastIndexOf("/") + 1);
	}
	
	var new_file_path = folder_path + new_file_name;
	
	if (main_layers_properties)
		for (var bn in main_layers_properties) {
			var layer_props = main_layers_properties[bn];
			
			if (layer_props["bean_name"] == bean_name && layer_props["bean_file_name"] == bean_file_name) {
				var layer_bean_folder_name = layer_props["layer_bean_folder_name"];
				
				layer_bean_folder_name = layer_bean_folder_name.replace(/\/+/g, "/").replace(/^\//g, "").replace(/\/$/g, ""); //remove duplicated slashes and at the begin and at the end.
				
				opts["layer_bean_folder_name"] = layer_bean_folder_name;
				opts["old_filter_by_layout"] = layer_bean_folder_name + "/" + file_path;
				opts["new_filter_by_layout"] = layer_bean_folder_name + "/" + new_file_path;
				break;
			}
		}
	
	//console.log(opts);
	onSucccessfullEditProject(opts);
}

function triggerFileNodeAfterCreateFile(a, attr_name, action, new_file_name, url, tree_node_id_to_be_updated) {
	var node = $("#" + tree_node_id_to_be_updated);
	
	if (node[0])
		mytree.refreshNodeChilds(node[0], {
			ajax_callback_last: function(ul, data) {
				$(ul).find(" > li > a > label").each(function(idx, item) {
					item = $(item);
					
					if (item.text() == new_file_name) {
						var a = item.parent();
						
						if (a.attr("onClick")) {
							try {
								a.trigger("click");
							}
							catch(e) {
								if (console && console.log)
									console.log(e);
							}
						}
						
						return false;
					}
				});
			},
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
		var is_template_folder = tree_node.find(" > a > i").is(".folder, .template_folder") && p.find(" > a > i").is(".templates_folder"); //by default it should be a .template_folder
		
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

function manageBusinessLogicObject(a, attr_name, action) {
	manageFile(a, attr_name, action, function(a, attr_name, action, new_file_name, url, tree_node_id_to_be_updated) {
		var file_node_id = getNodeParentIdByNodeId(tree_node_id_to_be_updated);
		refreshNodeChildsByNodeId(file_node_id);
	});
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
	
	MyContextMenu.hideAllContextMenu();
	
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
	
	MyContextMenu.hideAllContextMenu();
	
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
		//StatusMessageHandler.showMessage("File copied successfully");
	}, 100);
	
	MyContextMenu.hideAllContextMenu();
}

//a var could be a contextmenu item or a jstree-node
function manageDBTableAction(a, attr_name, action, on_success_callback, on_error_callback, opts) {
	var url = a.getAttribute(attr_name);
	//console.log(attr_name+":"+url);
	
	if (url) {
		var new_name, original_name;
		var status = false;
		var original_action = action;
		
		var is_jstree_node = $(a).parent().hasClass("jstree-node");
		var tree_node_id_to_be_updated = is_jstree_node ? $(a).parent().attr("id") : $(a).parent().parent().attr("last_selected_node_id");
		var node_a = $("#" + tree_node_id_to_be_updated + " > a");
		
		var table_name = node_a.attr("table_name");
		var attribute_name = node_a.attr("attribute_name");
		
		switch (action) {
			case "remove_table": 
				status = confirm("Do you really wish to remove this table: '" + table_name + "'?");
				break;
				
			case "remove_attribute": 
				status = confirm("Do you really wish to remove this attribute: '" + attribute_name + "'?");
				break;
				
			case "add_table": 
				status = (new_name = prompt("Please write the new table name:"));
				break;
				
			case "add_attribute": 
				status = (new_name = prompt("Please write the new attribute name:"));
				break;
			
			case "rename_table": 
				original_name = table_name;
				status = (new_name = prompt("Please write the new table name:", table_name));
				break;
			
			case "rename_attribute": 
				original_name = attribute_name;
				status = (new_name = prompt("Please write the new attribute name:", attribute_name));
				break;
			
			case "sort_attribute":
			case "add_fk_attribute": 
				if ($.isPlainObject(opts)) {
					url = url.replace("#previous_attribute#", opts["previous_attribute"] ? opts["previous_attribute"] : "");
					url = url.replace("#next_attribute#", opts["next_attribute"] ? opts["next_attribute"] : "");
					url = url.replace("#attribute_index#", $.isNumeric(opts["attribute_index"]) ? opts["attribute_index"] : "");
					url = url.replace("#fk_table#", opts["attribute_table"] ? opts["attribute_table"] : "");
					url = url.replace("#fk_attribute#", opts["attribute_name"] ? opts["attribute_name"] : "");
					
					status = true;
				}
				break;
			
			case "set_primary_key":
			case "set_null":
				var prop_key = action == "set_primary_key" ? "primary_key" : "null";
				var prop_value = $(a).parent().hasClass("checked") ? 0 : 1;
				var properties = {};
				properties[prop_key] = prop_value;
				var property_value = JSON.stringify(properties);
				
				url = url.replace("#properties#", property_value);
				status = true;
				break;
			
			case "set_type": 
				var input = $(a).children("input");
				var property_length = input[0].hasAttribute("disabled") ? null : input.val();
				var type_select = $(a).children("select");
				var property_type = type_select.val();
				var simple_props = type_select.data("simple_props");
				var properties = {
					type : property_type, 
					length: property_length
				};
				
				//check if type is simple type and update with the defaults values
				if ($.isPlainObject(simple_props)) {
					properties = simple_props;
					delete properties["label"];
					
					if ($.isArray(properties["type"]))
						properties["type"] = properties["type"][0];
					
					properties["length"] = property_length;
				}
				
				var property_value = JSON.stringify(properties);
				
				url = url.replace("#properties#", property_value);
				status = true;
				break;
		}
		
		if (status) {
			var is_new_name_action = action == "add_table" || action == "rename_table" || action == "add_attribute" || action == "rename_attribute";
			
			if (is_new_name_action && new_name) {
				new_name = ("" + new_name).replace(/^\s+/g, "").replace(/\s+$/g, ""); //trim name
				
				//normalize new file name
				var allow_upper_case = a.getAttribute("allow_upper_case") == 1; //in case of non standards DB names. DB, Table or attributes' names should always be lowercase - this is the standard.
				new_name = normalizeFileName(new_name, allow_upper_case);
			}
			
			if (is_new_name_action && (!new_name || new_name == original_name)) {
				if (!new_name)
					alert("Error: Name cannot be empty");
				else
					alert("Error: Name cannot be the same");
				
				if (typeof on_error_callback == "function")
					on_error_callback(a, attr_name, action, new_name, url, tree_node_id_to_be_updated);
			}
			else {
				StatusMessageHandler.showMessage("Saving...");
				
				url = url.replace("#action#", action);
				url = url.replace("#extra#", new_name);
				
				url = encodeUrlWeirdChars(url); //Note: Is very important to add the encodeUrlWeirdChars otherwise if a value has accents, won't work in IE.
				
				var str = action == "add_table" || action == "add_attribute" ? "add" : (
					action == "rename_table" || action == "rename_attribute" ? "rename" : action.replace(/_/g, " ")
				);
				
				$.ajax({
					type : "get",
					url : url,
					success : function(data, textStatus, jqXHR) {
						StatusMessageHandler.removeLastShownMessage("info");
						
						if (jquery_native_xhr_object && isAjaxReturnedResponseLogin(jquery_native_xhr_object.responseURL))
							showAjaxLoginPopup(jquery_native_xhr_object.responseURL, url, function() {
								StatusMessageHandler.removeLastShownMessage("error");
								manageDBTableAction(a, attr_name, original_action, on_success_callback, on_error_callback, opts);
							});
						else if (data == "1") {
							if (action == "add_table" || action == "add_attribute" || action == "add_fk_attribute")
								refreshAndShowNodeChildsByNodeId(tree_node_id_to_be_updated);
							else if (action != "remove_table" && action != "remove_attribute")
								refreshNodeParentChildsByChildId(tree_node_id_to_be_updated);
							
							StatusMessageHandler.showMessage(str + " correctly");
							
							if (typeof on_success_callback == "function")
								on_success_callback(a, attr_name, action, new_name, url, tree_node_id_to_be_updated);
							
							if (action == "remove_table" || action == "remove_attribute") {
								var li = $("#" + tree_node_id_to_be_updated);
								
								if (li.is(":last-child")) 
									li.prev("li").addClass("jstree-last");
								
								li.remove();
							}
						}
						else {
							var json_data = data && ("" + data).substr(0, 1) == "{" ? JSON.parse(data) : null;
							
							if ($.isPlainObject(json_data) && json_data.hasOwnProperty("sql") && json_data["sql"] && a.getAttribute("execute_sql_url")) { //try to show sql and then execute it manually
								if (action == "add_table" || action == "add_attribute" || action == "add_fk_attribute")
									refreshAndShowNodeChildsByNodeId(tree_node_id_to_be_updated);
								else if (action != "remove_table" && action != "remove_attribute")
									refreshNodeParentChildsByChildId(tree_node_id_to_be_updated);
								
								showSQLToExecute(a, attr_name, action, on_success_callback, on_error_callback, opts, new_name, url, tree_node_id_to_be_updated, json_data);
								
								StatusMessageHandler.showError("There was a problem trying to execute this action.\nPlease check the correspondent SQL and execute it manually." + (json_data["error"] ? "\n" + json_data["error"] : ""));
							}
							else {
								StatusMessageHandler.showError("There was a problem trying to execute this action. Please try again..." + (data ? "\n" + data : ""));
								
								if (typeof on_error_callback == "function")
									on_error_callback(a, attr_name, action, new_name, url, tree_node_id_to_be_updated);
							}
						}
					},
					error : function(jqXHR, textStatus, errorThrown) { 
						StatusMessageHandler.removeLastShownMessage("info");
						
						var msg = jqXHR.responseText ? "\n" + jqXHR.responseText : "";
						StatusMessageHandler.showError((errorThrown ? errorThrown + " error.\n" : "") + "Error trying to execute this action.\nPlease try again..." + msg);
						
						if (typeof on_error_callback == "function")
							on_error_callback(a, attr_name, action, new_name, url, tree_node_id_to_be_updated);
					},
				});
			}
		}
		else if (typeof on_error_callback == "function")
			on_error_callback(a, attr_name, action, new_name, url, tree_node_id_to_be_updated);
	}
	else if (typeof on_error_callback == "function")
		on_error_callback(a, attr_name, action, null, url, null);
	
	MyContextMenu.hideAllContextMenu();
	
	return false;
}

function showSQLToExecute(a, attr_name, action, on_success_callback, on_error_callback, opts, new_name, url, tree_node_id_to_be_updated, json_data) {
	var url = a.getAttribute("execute_sql_url");
	var sql = json_data["sql"];
	var sql_str = sql.join(";");
	
	url += (url.indexOf("?") != -1 ? "&" : "?") + "popup=1&sql=" + encodeURIComponent(sql_str);
	
	var popup = $(".execute_sql_popup");
	
	if (!popup[0]) {
		popup = $('<div class="myfancypopup execute_sql_popup with_iframe_title"></div>');
		$(document.body).append(popup);
	}
	
	popup.html('<iframe></iframe>');
	
	//add onload event handler to detect if query was successfull executed, and if yes close popup.
	var iframe = popup.children("iframe");
	var iframe_on_load_func = function() {
		var status = $(this).contents().find(".sql_results table td").first().hasClass("success");
		
		if (status)
			MyFancyPopup.hidePopup();
	};
	iframe.bind("load", iframe_on_load_func);
	iframe.bind("unload", function() {
		iframe.bind("load", iframe_on_load_func);
	});
	iframe[0].src = url;
	
	MyFancyPopup.init({
		elementToShow: popup,
		//parentElement: document,
		onClose: function() {
			var status = iframe.contents().find(".sql_results table td").first().hasClass("success");
			
			if (tree_node_id_to_be_updated) {
				if (action == "add_table" || action == "add_attribute" || action == "add_fk_attribute")
					refreshAndShowNodeChildsByNodeId(tree_node_id_to_be_updated);
				else
					refreshNodeParentChildsByChildId(tree_node_id_to_be_updated);
			}
			
			if (status) {
				if (typeof on_success_callback == "function")
					on_success_callback(a, attr_name, action, new_name, url, tree_node_id_to_be_updated);
			}
			else if (typeof on_error_callback == "function")
				on_error_callback(a, attr_name, action, new_name, url, tree_node_id_to_be_updated);
		},
		
		popup_class: "execute_sql",
	});
	MyFancyPopup.showPopup();
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
	var regex = new RegExp("[\\?&]" + name + "=([^&#]*)", "i");
	var results = ("" + url).match(regex);
	
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
		popup = $('<div class="myfancypopup with_iframe_title choose_available_tool_popup"><iframe src="' + url + '"></iframe></div>');
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
		popup = $('<div class="myfancypopup with_iframe_title choose_available_project_popup"><iframe src="' + url + '"></iframe></div>');
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

function chooseAvailableTutorial(url) {
	var popup = $(".choose_available_tutorial_popup");
	
	if (!popup[0]) {
		popup = $('<div class="myfancypopup with_iframe_title choose_available_tutorial_popup"><iframe src="' + url + '"></iframe></div>');
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
