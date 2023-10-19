


$(function($) {
  
  $(window).on('load', function(){
    $(".pageLoader").delay(2000).fadeOut("slow");
  });
  
	// Tooltip 
	$('[data-toggle="tooltip"]').tooltip();

	$(window).scroll(function(){
    if ($(window).scrollTop()>100) {
      $('.main-menu-area').addClass('scroll_menu shadow-sm');
    }else{
      $('.main-menu-area').removeClass('scroll_menu shadow-sm');
    }
  })
  
  $(window).scroll(function(){
    if ($(this).scrollTop()>500) {
      $(".scrollTop").addClass('show');
    }else{
      $(".scrollTop").removeClass('show');
    }
  })
  $('.scrollTop').click(function () {
      $('body,html').animate({
          scrollTop: 0
      }, 800);
      return false;
  });


  $('#toggleMenu').click(function(e){
    e.preventDefault();
    $('.main-menu-area').toggleClass('mobile_menu');
  });

  $('body,html').click(function (e) {
    var container = $(".main-menu-area");
    var toggleMenu = $("#toggleMenu");
    if (!container.is(e.target) && container.has(e.target).length === 0 && !toggleMenu.is(e.target) && toggleMenu.has(e.target).length === 0) {
      container.removeClass('mobile_menu');
    }
  });
  
  $('.selectpicker').selectpicker();

  feather.replace();

}(jQuery));



