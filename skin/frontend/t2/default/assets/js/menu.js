
var ww = document.body.clientWidth;

jQuery(document).ready(function($) {
	$(".topMenu li a").each(function() {
		if ($(this).next().length > 0) {
			$(this).addClass("parent");
		};
	})
	
	$(".toggleMenu").click(function(e) {
		e.preventDefault();
		$(this).toggleClass("active");
		$(".topMenu").toggle();
	});
	adjustMenu();
})

jQuery(window).bind('resize orientationchange', function() {
	ww = document.body.clientWidth;
	adjustMenu();
});

var adjustMenu = function() {
	if (ww < 768) {
		jQuery(".toggleMenu").css("display", "inline-block");
		if (!jQuery(".toggleMenu").hasClass("active")) {
			jQuery(".topMenu").hide();
		} else {
			jQuery(".topMenu").show();
		}
		jQuery(".topMenu li").unbind('mouseenter mouseleave');
		jQuery(".topMenu li a.parent").unbind('click').bind('click', function(e) {
			// must be attached to anchor element to prevent bubbling
			e.preventDefault();
			jQuery(this).parent("li").toggleClass("hover");
		});
	} 
	else if (ww >= 768) {
		jQuery(".toggleMenu").css("display", "none");
		jQuery(".topMenu").show();
		jQuery(".topMenu li").removeClass("hover");
		jQuery(".topMenu li a").unbind('click');
		jQuery(".topMenu li").unbind('mouseenter mouseleave').bind('mouseenter mouseleave', function() {
		 	// must be attached to li so that mouseleave is not triggered when hover over submenu
		 	jQuery(this).toggleClass('hover');
		});
	}
}

