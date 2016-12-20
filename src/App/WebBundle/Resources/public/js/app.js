// JavaScript Document
var current_slide;
var totalslides;

$(window).load(function (){
$(".slider").addClass("transition_02s");

// add elements
$(".more-cary" ).append( "<div class='a'></div><div class='b'></div><div class='c'></div>" );
$(".more-cary div").addClass("ab transition_04s");
$(".tab-head" ).append( "<div class='floating-arrow'></div>" );
$(".tab-head .floating-arrow").addClass("transition_1s");

// screen height
   	var screen_height = jQuery(window).height();
	$(".Screen-height, .full_wxvh").height(screen_height);
// jquery height
	var dynamic_height = $(".dynamic_height_block").height();
	$(".dynamic_height_set").height(dynamic_height);
	var gifthome_height = $(".gift-home").height();
	$(".loyalty-home").height(gifthome_height);

	var hc_height = $(".heigh-clear").height();
	$(".tab-cary").height(hc_height);

// Click

$('.footer-submenu, .footer-bottom-click').click(function(){
	var content = $(this).parent().parent().parent(".footer-comn-catg").attr("class");
	$(this).parent().parent().parent(".footer-comn-catg").toggleClass("active");
	$(this).toggleClass("active");

	if($(this).hasClass("active")){
		$(".footer-comn-catg").removeClass("active");
		$(".footer-submenu").removeClass("active");
		$(this).parent().parent().parent(".footer-comn-catg").addClass("active");
		$(this).addClass("active");
		}
 });

$('.user-click, .close-login').click(function(){
	$(".login-register-show").toggleClass("active");
 });
 
$('.responcive-menu-cliker, .close-main-menu').click(function(){
	$("nav").toggleClass("active");
 });
$('.skip-close').click(function(){
	$(".first-location-popup").toggleClass("active");
 });
 
 $('.resposive-filter h3').click(function(){
	$(".resposive-filter").addClass("active");
 });
  $('.resposive-filter .close-filter').click(function(){
	$(".resposive-filter").removeClass("active");
 });
 $('.change-seller').click(function(){
	$(".show-change-seller").toggleClass("active");
 });


// Expand div 

//var faqshow_height = $(".faq-div h4").innerHeight();
//	$(".faq-div").height(faqshow_height);
//var faqclickshow_height = $(".faq-div").innerHeight();
//	$(".faq-div").height(faqclickshow_height);

$('.faq-div').click(function(){
	$(".faq-div").removeClass("active");
	$(this).toggleClass("active");
 });

// -- Navigations

$('.nav-catg-click').hover(function(){
	if($(".nav-submenu").hasClass("active")){
	setTimeout(function(){$(".nav-submenu").removeClass("active"); }, 2000);
		}
	else
	{
		$(".nav-submenu, .main-sub-nav").addClass("active");
		}
 });

 $('.main-sub-nav ul li').hover(function(){
  var dataId = $(this).attr("data_id");
  if ($('.main-sub-nav ul li').hasClass('active')){
 	 	$('.main-sub-nav ul li').removeClass('active')
 	 }
  $(this).addClass('active');
  if ($('.second-sub-nav').hasClass('active')){
 	 	$('.second-sub-nav').removeClass('active')
 	 }
  $('#'+dataId).addClass('active');
  });

$('.second-sub-nav li').hover(function(){
	 var dataId = $(this).attr("data_id");
	 if ($('.second-sub-nav li').hasClass('active')){
		 	$('.second-sub-nav li').removeClass('active')
		 }
	 $(this).addClass('active');
	 if ($('.third-sub-nav').hasClass('active')){
		 	$('.third-sub-nav').removeClass('active')
		 }
	 $('#'+dataId).addClass('active');
});

if($(window).width() < 768){
  $('.main-sub-nav ul li').click(function(){
   var dataId = $(this).attr("data_id");
   if ($('.main-sub-nav ul li').hasClass('active')){
  	 	$('.main-sub-nav ul li').removeClass('active')
  	 }
   $(this).addClass('active');
   if ($('.second-sub-nav').hasClass('active')){
  	 	$('.second-sub-nav').removeClass('active')
  	 }
   $('#'+dataId).addClass('active');
   });

 $('.second-sub-nav li').click(function(){
 	 var dataId = $(this).attr("data_id");
 	 if ($('.second-sub-nav li').hasClass('active')){
 		 	$('.second-sub-nav li').removeClass('active')
 		 }
 	 $(this).addClass('active');
 	 if ($('.third-sub-nav').hasClass('active')){
 		 	$('.third-sub-nav').removeClass('active')
 		 }
 	 $('#'+dataId).addClass('active');
 });
	}

$('.responsive-menu-click, .clos-menu').click(function(){
	$(".responsive-menu-carry, .responsive-menu-click").toggleClass("active");
 });
$('.tab_1').click(function(){
	var data_id = $(this).attr("data-id");
	if($(".tab-animated-cary").hasClass("active")){
			$(".tab-animated-cary").removeClass("active");
		}
	$("#"+data_id).addClass("active");
	$(".tab-head .floating-arrow").css("left",(data_id-1)*25+11+"%");
 });
// Go top
$('.go_top_button').click(function(){
    $("html, body").animate({ scrollTop: 0 }, 1000);
    return false;
 });

$(window).scroll(function() {
    var scroll = $(window).scrollTop();
    if (scroll >= 400) {
        $(".go_top_button").addClass("active");

    }
	else{
		$(".go_top_button").removeClass("active");
		}
});


if($(window).width() < 480){
	var logo_height_slider = $(".logo").height();
	var nav_height_slider = $("nav").height();
	$(".slider-over-text").css("margin-top",logo_height_slider+nav_height_slider+20);
	}


// Responsive --------
if($(window).width() < 768){$("form.fl_form_lb input[type=email],input[type=number],input[type=password],input[type=search],input[type=tel],input[type=text],input[type=time],input[type=url]").addClass("sei_input_lb");
}
if($(window).width() > 768){
$("form.fl_form input[type=email],input[type=number],input[type=password],input[type=search],input[type=tel],input[type=text],input[type=time],input[type=url]").addClass("sei_input");
var about_homepage = $(".about-home").height();
$(".about-home-image").height(about_homepage+50);
}


//---------- lightbox


$(".lightbox_carrier img").on("click", function(){
	if($(window).width() > 480){
		var lbimage = $(this).attr("src");
		$(".popup_image img").attr("src", lbimage);
		$(".popup_lightbox").addClass("active");
		$(".popup_lightbox .popup_image").addClass("active");
		}
	});
	$(".popup_lightbox .popup_image").click(function(e){
			e.stopPropagation();
		});
	$('.popup_lightbox').click(function(e){
		$(".popup_lightbox").removeClass("active");
		$(".popup_lightbox .popup_image").removeClass("active");
 	});






	//else{};

//---------- Slider --------------------------
current_slide=1;
totalslides= $(".slider-cary .slider").length;
var slider_main = setInterval(slide_show, 4000);

// responsive
	if($(window).width() > 768){
	 var section_height = $('.special-home').height();
	 $(".special-home .block_lb_7").height(section_height);
    }
	else{};

});
//-- end loader -----------------------------------------------------------------------------------------------------------------------

//---------- Slider function --------------------------

function slide_show(){
    if(current_slide < totalslides ){
            current_slide++;
            }
            else{
                    current_slide=1;
                    }
    if($(".slider-cary .slider").hasClass("active")){
                    $(".slider-cary .slider").removeClass("active");
            }
    $(".slider-left .slide_"+current_slide).addClass("active");
}

$(document).ready(function(){
    $('.add-to-cart').on('click', function(){
        var that = this;
        $.ajax({
            url: to_cart_url.replace('0', $(this).data('entry'))+'?qty=1',
            dataType: 'json',
            success: function(resp){
                if(resp.status){
                    $(that).hide();
                    $(that).parent().find('.entry-in-cart').show();
                    $('#cart-badge').html(parseInt($('#cart-badge').html()) + 1);
                    alert('Successfully added to cart');
                } else {
                    alert('Insufficient stock');
                }
            }
        });
    });
});