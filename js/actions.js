var $$ = {};

$$.adjustBg = function(){
	image = $("#siteBg");
	var ratio = Math.max($(window).width()/image.width(),$(window).height()/image.height());
	if ($(window).width() > $(window).height()) {
		image.css({width:image.width()*ratio,height:'auto'});
	} else {
		image.css({width:'auto',height:image.height()*ratio});
	}
};

window.sliderVisible = true;

$$.isSliderVisible = function() {
	return window.sliderVisible;
};

$$.hideSlider = function() {
	window.sliderVisible = false;
	h2 = $("#uiSlider > .content > h2");

	h2
		.parent().parent()
			.animate({
				top: '155px'
			})
			.find(".sliderGallery, .bg")
				.animate({
					height: '15px',
					opacity: 0
				});

	text = h2.text();
	h2.text(h2.attr("alt"));
	h2.attr("alt", text);
};

$$.showSlider = function() {
	window.sliderVisible = true;
	h2 = $("#uiSlider > .content > h2");
	parentNodeX = h2.parent().parent().animate({ top: '0px' });

	$(".sliderGallery", parentNodeX)
		.show()
		.animate({
			opacity: 1,
			height: '155px'
		});

	$(".bg", parentNodeX)
		.animate({
			opacity: 0.7,
			height: '155px'
		});

	text = h2.text();
	h2.text(h2.attr("alt"));
	h2.attr("alt", text);
};

// Load images

$$.loadNextImage = function(images, i) {
		var currentA = $(images[i]);
		var currentImg = $("img", currentA);

		onComplete = (function(images, i) {
			return function() {
				if(i >= images.length) return;
				// Run this as a new thread
				$$.loadNextImage(images, i+1);
			};
		})(images, i);

		$(new Image())
			.load((function(img){ return function(){ img.fadeIn(); }; })(currentImg))
			.attr("src", currentA.attr("href"));

		onComplete()
	};

$$.hideDetails = function(e) {
	$("#sections, #sectionData").fadeOut();
	$$.showSlider();
	$$.swapDetailsText("Show slider");
	e.preventDefault();
}

$$.showDetails = function(e) {
	$("#sections, #sectionData").fadeIn();
	lis = $("#sections > ul > li").fadeOut(0);

	for(i=0,max=lis.length;i<max;i++)
	{
		setTimeout((function(elem){
			return function() { elem.fadeIn(); };
		})($(lis[i])), i*450);
	}

	$$.hideSlider();
	$$.swapDetailsText("Hide slider");
	e.preventDefault();
};

$$.swapDetailsText = function(newText) {
	h2 = $(this);
	h2.text(h2.attr("alt"));
	h2.attr("alt", newText);	
};

// Process the slider
$(window).ready(function () {
	// General fade-ins
	fadeInBox = function(boxes, i) {
		box = $(boxes[i]);
		$(boxes[i]).fadeIn(1000);
		if(i < boxes.length) {
			setTimeout((function(){ return function(){ fadeInBox(boxes, i+1); }; })(boxes, i), 1000);
		}
	}

	$('.sliderGallery .items a, #thumbsGallery a, #sections > ul > li > span > .photos a')
		// Swap the site background only when dealing with the images
		.filter(function(){
			return !$(this).hasClass('video');
		})
		.live('click', function(e) {
			$("#siteBg").animate({opacity: 0}, 400, function(){ $(this).remove(); });
			newBg = $('<img alt="" id="siteBg" src="'+$(this).attr("href")+'" />');
			$("body").prepend(newBg);
			newBg.css({opacity: 0, display: 'inline'}).animate({opacity: 1}, 400);
	 
			$$.adjustBg()
	
			e.preventDefault();
			return false;
		});

	// Gallery thumbnails loading process:
	images = $(".sliderGallery .items li a, #thumbsGallery a, #sectionData > .content > .photos li a");
	
	images.each(function(){
		currentImg = $('img', this);
		$(this)
			.css({
				width: currentImg.attr("width"),
				height: currentImg.height()-2,
				display: 'inline-block'
			});
		if(!$(this).hasClass("video"))
		{
			$(this)
				.addClass("loading")
				.find("img")
				.hide();
		}
	});

	$$.loadNextImage(images.filter("[class!=video]"), 0);

	/*
	$('#thumbsGallery')
		.fsGallery({
	      delay: 0,
	      fade: 1
	    })
	    .find("li[class!=logo]")
	    	.hover(
	    		function() {
	    			$(this).append('<div class="cloud">'+
	    					'<div class="imgWrapper">'+
    							'<img alt="" src="'+$(this).css("background-image").replace('url("', '').replace('")','')+'" />'+
    							'<div class="arrow"></div>'+
	    					'</div>'+
	    				'</div>')
	    		}, function() {
	    			$(this).find(".cloud").remove();
	    		}
	    	)
    ;
	*/

	$(".options .contact").toggle(function() { $("#uiContactForm").fadeIn(); }, function() { $("#uiContactForm").fadeOut(); });
	$(".options .details").toggle( $$.showDetails, $$.hideDetails );

	$(".close_button").click($$.hideDetails);

	var boxesToFadeIn = $(".uiBox:visible").fadeOut(0);

	if($("#siteBg").length)
	{
		$("body").append("<img alt='' src='/images/spinner.gif' id='mainIndicator' style='position: absolute; left: 50%; top: 50%; margin:-16px 0 0 -16px;'/>");
	}

	// Load the page background
	img = new Image();
	$(img)
		.load(function(){

			$("#siteBg")
				.attr("src", $(this).attr("src"))
				.fadeOut(0)
				.fadeIn();

			$$.adjustBg();

			// When it's loaded, show other boxes
			fadeInBox(boxesToFadeIn, 0);

			// Also, destroy the indicator
			$("#mainIndicator").remove();
		})
		.attr("src", $("#siteBg").attr("title"));

	window.animLock = false;
	function animateThumbs(multiplier)
	{
		if(window.animLock == true) return;
		window.animLock = true;

		ul = $("#thumbsGallery ul.items");
		lisLength = $("#thumbsGallery ul.items > li").length;

		leftValue = ul.css("left").replace(/px/, '');
		oldLeft = parseInt(leftValue == "auto" ? 0 : leftValue);
		newLeft = 10 * 69 * multiplier + oldLeft;
		
		maxLeft = -1 * (lisLength - 12) * 69;
		newLeft = newLeft < maxLeft ? maxLeft : newLeft;
		newLeft = newLeft > 0 ? 0 : newLeft;

		// If we went too far to the right
		/*
		if(lisLength + (newLeft/69) - 12 < 0) {
			window.animLock = false;
			return;
		}
		*/

		// If we went too far to the left
		if(newLeft > 0) {
			window.animLock = false;
			return;
		}

		ul.animate({'left': newLeft+'px'}, 150, function(){ window.animLock = false; })
	}

	$(".arrLeft") .click(function(){ animateThumbs(1); });
	$(".arrRight").click(function(){ animateThumbs(-1);  });

});

// Take care of the site background
$(window).resize($$.adjustBg);

// On DOM load, enable our video lightbox
$(document).ready(function() {
	$.mapLoaded = false;

	$("a.contact, .options .contact a, .photos li[class!=movie] a").prettyPhoto({theme:'facebook'});
	$(".sliderGallery a.video, .photos li.movie a").prettyPhoto({theme:'facebook'});
//	$(".sliderGallery a.video, header a.contact, .options .contact a, .photos a").prettyPhoto({theme:'facebook'});

	$("#sections > ul > li > a").click(function(){
		$("#sectionData .description p").text($(this).parent().find(".details").text());
		$("#sectionData .content h1").text($(this).parent().find(".name").text());

		container = $("#sectionData .features");
		$("ul", container).remove();
		container.append($(this).parent().find(".features").clone());

		$("#sectionData .content .photos").remove();
		$(this).parent().find(".photos").clone(true).insertAfter($("#sectionData .content h1"));

		$(this).parent().parent().find("li").removeClass("active");
		$(this).parent().addClass("active");
	});
	$("#sections > ul > li:first-child .photos").clone(true).insertAfter($("#sectionData .content h1"));
	$(".holder .image_holder .inside").corner().parent().corner();

	$("#uiFooter,#uiSlider > .content > h2").mouseover(function() {
		if($$.isSliderVisible()) return;
		$$.showSlider();
		$("body > footer").attr("hovered", "1");
	});

	$("body > footer").hover(
		function(){},
		(function() {
			footer = $(this);
			if(footer.attr("hovered") != 1)
			{
				return;
			}
			$$.hideSlider();
			footer.attr("hovered", "0");
		})
	);


	// "Type" navigation at the index page
	window.ajaxBlock = false;
	window.lastType = '';
	$('.main_nav a, .pagination_wrapper a')
		.live('click', function(e) {
			if(window.ajaxBlock) return false;
			window.ajaxBlock = true;
			
			// Figure out whether we are dealing with menu nav link or pager navigation
			if($(this).parents().filter(".pagination_wrapper").length) {
				type = window.lastType;
				pageNo = $(this).text();
			} else {
				window.lastType = type = $(this).text();
				pageNo = 1;
				$('.main_nav li').removeClass("active");
				$(this).parent().addClass("active");
			}
			
			var indicator = $(this).parent().parent().find(".indicator");
			indicator.fadeIn();

			$.get(
				'/property-list?type='+type+"&page="+pageNo,
				function(response) {
					indicator.fadeOut();
					$("#listContainer")
						.fadeOut("fast", function() {
							$(this).html(response).fadeIn();  $(".holder .image_holder .inside").corner().parent().corner();

						});

					window.ajaxBlock = false;
				}
			);
	
			e.preventDefault();
		});

});
