showLoadingBar();

(function($) {
    "use strict";
    
    //bc of the LayoutUIEditor
    //if (!document.body.querySelector("[data-widget-list]") || document.body.querySelector("[data-widget-loading]"))
        hideLoadingBar();
    
    // Toggle the side navigation
    $("#sidebarToggle").on("click", function(e) {
        e.preventDefault();
        $("body").toggleClass("sb-sidenav-toggled");
    });
    
    //load notifications
    //loadNotifications();
})(jQuery);

function loadNotifications() {
    //TODO
}

function showLoadingBar() {
    var loading_bar = document.body.querySelector('.loading-bar');
    
    if (!loading_bar) {
        var html = '<div class="loading-bar">Loading...</div>';
        document.body.insertAdjacentHTML('beforeend', html);
        
        loading_bar = document.body.querySelector('.loading-bar');
    }
    
    loading_bar.style.display = 'block';
}

function hideLoadingBar() {
    var loading_bar = document.body.querySelector('.loading-bar');
    
    if (loading_bar)
        loading_bar.style.display = 'none';
}
