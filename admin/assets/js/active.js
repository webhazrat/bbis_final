$(function(){
    
    const path = window.location.href;
    $('.vertical-menu ul li a').each(function () {
        if (this.href === path) {
            $(this).addClass('active');
            $(this).parent("li").parent("ul").addClass("expand");
            $(this).parent("li").parent("ul").parent("li").children("a").addClass("active");
        }
    })
    
    $('[data-toggle="tooltip"]').tooltip()
        
    // feather icon
    feather.replace();

    // bootstrap select
    $('.selectpicker').selectpicker();
    
    // expandable menu
    $(".sub-menu>a").click(function(e){
        e.preventDefault();
        $(this).parent(".sub-menu").children("ul").toggleClass("expand");
        $(this).find(".right").toggleClass("fa-angle-down fa-angle-up");
    });

     // mobile menu toggle
    $(".menu-toggle").click(function(){
        $(".left-sidebar").toggleClass("show");
        $(this).toggleClass("toggle-click");
    });

    $('body,html').click(function (e) {
        var container = $(".left-sidebar");
        var toggleMenu = $(".menu-toggle");
        if (!container.is(e.target) && container.has(e.target).length === 0 && !toggleMenu.is(e.target) && toggleMenu.has(e.target).length === 0) {
            container.removeClass('show');
            $(".menu-toggle").removeClass("toggle-click");
        }
      });
    
    // summernote
    $(".summerNote").summernote();

    const element = document.querySelector('.navbar-brand');
    const baseUrl = element.getAttribute('href');
    
});