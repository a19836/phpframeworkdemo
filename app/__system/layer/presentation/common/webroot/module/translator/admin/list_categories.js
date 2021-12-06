$(function () {
	var mytree = new MyTree();
	mytree.init("file_tree");
	
	$("#file_tree").find("i.jstree-file.jstree-themeicon-custom").removeClass("jstree-file jstree-themeicon-custom");
});
