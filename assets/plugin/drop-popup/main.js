$(document).ready(function(){
	$(".fim-dropdown > a").click(function(){
        $('#navbarcontainer').css({'z-index': 100});
		$(".fim-dropdown").not($(this).parent()[0]).removeClass('active');

		$(this).parent().toggleClass('active');
		return false;
	});

	$(document).click(function(e){
		that = e.target;
		if ($(that).closest(".fim-dropdown").length < 1 && !$(that).hasClass("fim-dropdown")) {
            $(".fim-dropdown").removeClass('active');
            $('#navbarcontainer').css({'z-index': 98});
        }
	});


	$(window).on("load resize",function(){
		$(".fim-dropdown > .inner").each(function(){
            var top;
			var src = $(this).parent().children("a");
			// Position of the popup
			var left = src.offset().left + src.outerHeight()/2 - $(this).outerWidth()/2;
			if (left + $(this).outerWidth() > $(window).width()) {
				left = $(window).width() - $(this).outerWidth();
			}
            if($(window).width()> 1900){
                top = 160;
            }else if($(window).width()>1700){
                top = 150;
            }else if($(window).width()>1500){
                top = 130;
            }else if($(window).width()>1300){
                top = 100;
            }
			if (left < 0) left = 0;
			$(this).css({
				left: left,
                'z-index':30,
                top:top
			});
		});
	});
});
