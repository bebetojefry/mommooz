// JavaScript Document
var current_slide;
var totalslides;

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

$(window).load(function (){
$(".slider").addClass("transition_02s");
    
$(".nav-submenu").addClass("firstActive");
    $('.nav-submenu ul,.nav-submenu ul li, .nav-submenu ul li a').mouseenter(function(){
	   $(".nav-submenu").removeClass("firstActive");
    });
    $('.nav-submenu').mouseleave(function(){
	   $(".nav-submenu").addClass("firstActive");
    });  
   /* var slider_caryHeight = $('.slider-cary img').innerHeight();
    $('.slider-offer-cary').height(slider_caryHeight+45);*/
    

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

$('.footer-submenu, .footer-bottom-click, .footer-bottom-click span').click(function(){
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
  
$('.user-clickB').click(function(){
	$(".LogOutDropDown").toggleClass("active");
 });
 
$('.responcive-menu-cliker, .close-main-menu').click(function(){
	$("nav").toggleClass("active");
 });
/*$('.skip-close').click(function(){
	$(".first-location-popup").toggleClass("active");
});*/
    
$('.first-cat-cary ul li:first-child').addClass("active");

$('.first-cat-cary ul li span.plusActive').click(function(e){
    $('.first-cat-cary ul li').removeClass("active");
	$(this).parent("li").toggleClass("active");
 });
 
 $('.resposive-filter h3').click(function(){
	$(".resposive-filter").toggleClass("active");
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
	setTimeout(function(){$(".nav-submenu").removeClass("active"); }, 0);
		}
	else
	{
		$(".nav-submenu, .main-sub-nav").addClass("active");
		}
 });
    
    
$('.clickY').click(function(){
    $(".cheakout_popup").addClass("active");
    $("body").addClass("paddBtm60");
 });
 
$('.user-click2').click(function(){
	$(".logAfterClick").toggleClass("active");
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
        var qty = $(this).parent().find('.txt-item-qty').val();
        if(!isNumeric(qty) || qty <= 0){
            openAlert('Invalid Quantity', 'error');
            return; 
        }
        $.ajax({
            url: to_cart_url.replace('0', $(this).data('entry'))+'?qty='+qty,
            dataType: 'json',
            success: function(resp){
                if(resp.status){
                    $(that).parent().find('.in_cart_count i').text(resp.quantity);
                    $(that).parent().find('.in_cart_count').show();
                    if(!resp.in_cart){
                        $('#cart-badge').html(parseInt($('#cart-badge').html()) + 1);
                        $('.cheakout_popup').addClass('active');
                        $('.cheakout_popup .count').html(parseInt($('.cheakout_popup .count').html()) + 1);
                    }
                    openAlert('Successfully added to cart', 'success');
                } else {
                    openAlert('Insufficient stock', 'error');
                }
            }
        });
    });
});

$.fn.imageUploader = function( options ) {
    
    var settings = $.extend({
        multiple: true,        
    }, options );
        
    var inputFile = document.createElement("input");
    var preview_box = document.createElement("div");
    $(preview_box).addClass('preview_box');
    inputFile.type = 'file';
    inputFile.id = $(this).attr('id')+'_file';
    inputFile.accept = 'image/*';
    $(this).parent().append(inputFile);
    $(this).parent().append(preview_box);
    $(this).parent().addClass('image_uploader');
    $(this).addClass('real_input');
    
    var that = this;
    if($(this).val() != ''){
        var images = $(this).val().split('<>');
        for(var i = 0; i < images.length; i++){
            var val = images[i].split('|');
            var img_box = document.createElement("div");
            $(img_box).addClass('img_box');
            var img = document.createElement("img");
            img.src = val[1];
            $(img_box).append(img);
            var img_remove_btn = document.createElement("button");
            $(img_remove_btn).attr('index', i);
            $(img_remove_btn).text('X');
            $(img_box).append(img_remove_btn);

            $(img_remove_btn).on('click', function(){
                var index = $(this).attr('index');
                images.splice(index, 1);
                $(that).val(images.join('<>'));
                $(that).parent().find('.preview_box').find('.img_box').eq(index).remove();
            });
            
            $(that).parent().find('.preview_box').append(img_box);
        }
    } else {
        var images = [];
    }
    
    $(inputFile).change(function(){
        if(!settings.multiple && images.length > 0){
            $(that).parent().find('.preview_box').html('');
            images = [];
        }
        
        if (inputFile.files && inputFile.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var img_box = document.createElement("div");
                $(img_box).addClass('img_box');
                var img = document.createElement("img");
                img.src = e.target.result;
                $(img_box).append(img);
                var img_remove_btn = document.createElement("button");
                $(img_remove_btn).attr('index', images.length);
                $(img_remove_btn).text('X');
                $(img_box).append(img_remove_btn);
                
                $(img_remove_btn).on('click', function(){
                    var index = $(this).attr('index');
                    images.splice(index, 1);
                    $(that).parent().find('.preview_box').find('.img_box').eq(index).remove();
                });
                
                $(that).parent().find('.preview_box').append(img_box);
                
                images.push('0|'+e.target.result);
                $(that).val(images.join('<>'));
            }
            reader.readAsDataURL(inputFile.files[0]);
        }
    });
};

function openAlert(message, type){
    $('.contentPopUp span').text(message);
    $(".comn_popUp").addClass("active");
    setInterval(function(){ $(".comn_popUp").removeClass("active"); }, 2000);
}

(function() {

    // Define our constructor
    this.FilterModal = function() {
        this.items = [];
        this.brands = [];
        this.categories = [];
        
        this.brand_items = [];
        this.category_items = [];
    }

    FilterModal.prototype.register = function(item) {
        item = JSON.parse(item);
        this.items.push(item);
        
        if(item.brand != undefined){
            if(this.brand_items[item.brand.id] == undefined){
                this.brand_items[item.brand.id] = [];
            }
            this.brands[item.brand.id] = ' '+item.brand.name;
            this.brand_items[item.brand.id][this.brand_items[item.brand.id].length] = item;
        }
        
        if(this.category_items[item.cat.id] == undefined){
            this.category_items[item.cat.id] = [];
        }
        this.categories[item.cat.id] = ' '+item.cat.name;
        
        for(var i=0; i < item.tagged_cats.length; i++){
            this.categories[item.tagged_cats[i].id] = ' '+item.tagged_cats[i].name;
            if(this.category_items[item.tagged_cats[i].id] == undefined){
                this.category_items[item.tagged_cats[i].id] = [];
            }
            
            this.category_items[item.tagged_cats[i].id][this.category_items[item.tagged_cats[i].id].length] = item;
        }
        
        this.category_items[item.cat.id][this.category_items[item.cat.id].length] = item;
    }

}());

$.fn.ItemFilter = function( FilterModal ) {
    $(this).addClass('item_page_filter');
    
    var checked = [];
    
    var that = this;
    var html = '';
    
    if(FilterModal.categories.length > 0){
        html += '<div class="block-12 block-lb-3 lb-pl10 block-mb-6 block-sb-12 mb-text-left"><h3 class="mb-10p"> Category </h3><div class="first-cat-cary mb-25p">';
        Object.keys(FilterModal.categories).forEach(function (key) {
            html += '<input class="filter_field" type="checkbox" name="cat_'+key+'" value="cat_'+key+'" class="mr-10p">'+FilterModal.categories[key]+'<div class="count-products-filter mb-10p  dispaly-inline-block prl-5p br-5p">'+FilterModal.category_items[key].length+'</div> <br/>';
        });
        
        html += '</div></div>';
    }
    
    html += '<div class="block-12 block-lb-3 lb-pl10 block-mb-6 block-sb-12 mb-text-left"><h3 class="mb-10p"> Refine By </h3>';
    html +='<div class="none-overx-pro mb-25p">';
    html +='<input class="filter_field" type="checkbox" name="on_offer" value="on_offer" class="mr-10p">   Products on offer  <br/>';
    html +='<input class="filter_field" type="checkbox" name="new" value="new" class="mr-10p">   New Launches   <br/>';
    html +='</div></div>';
    
    if(FilterModal.brands.length > 0){
        html += '<div class="block-12 block-lb-3 lb-pl10 block-mb-6 block-sb-12 mb-text-left"><h3 class="mb-10p"> Brand </h3><div class="first-cat-cary mb-25p">';
        Object.keys(FilterModal.brands).forEach(function (key) {
            html += '<input class="filter_field" type="checkbox" name="brand_'+key+'" value="brand_'+key+'" class="mr-10p">'+FilterModal.brands[key]+'<div class="count-products-filter mb-10p  dispaly-inline-block prl-5p br-5p">'+FilterModal.brand_items[key].length+'</div> <br/>';
        });
        
        html += '</div></div>';
    }
    
    $(this).html(html);
    
    $(this).find(".filter_field").on('change', function(){
        if($(this).is(':checked')){
            checked.push($(this).val());
        } else {
            var index = checked.indexOf($(this).val());
            if (index > -1) {
               checked.splice(index, 1);
            }
        }
        
        if(checked.length > 0){
            $('.item-thumb').hide();
            checked.forEach(function (val) {
                $('.'+val).show();
            });
        } else {
            $('.item-thumb').show();
        }
    });
}

function getCookie(name) {
    var dc = document.cookie;
    var prefix = name + "=";
    var begin = dc.indexOf("; " + prefix);
    if (begin == -1) {
        begin = dc.indexOf(prefix);
        if (begin != 0) return null;
    }
    else
    {
        begin += 2;
        var end = document.cookie.indexOf(";", begin);
        if (end == -1) {
        end = dc.length;
        }
    }
    // because unescape has been deprecated, replaced with decodeURI
    //return unescape(dc.substring(begin + prefix.length, end));
    return decodeURI(dc.substring(begin + prefix.length, end));
}