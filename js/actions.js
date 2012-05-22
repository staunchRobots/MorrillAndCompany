var $$ = {};

	
	
$$.adjustBg = function(){
  image = $("#siteBg");
  var ratio = Math.max($(window).width()/image.width(),$(window).height()/image.height());
  if ($(window).width() > $(window).height()) {
    image.css({ width:image.width()*ratio,height:'auto' });
  } else {
    image.css({ width:'auto',height:image.height()*ratio });
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
$(window).ready(function () { // return;


	// General fade-ins
	fadeInBox = function(boxes, i) {
		box = $(boxes[i]);
		$(boxes[i]).fadeIn(1000);
		if(i < boxes.length) {
			setTimeout((function(){ return function(){ fadeInBox(boxes, i+1); }; })(boxes, i), 1000);
		}
	}

	$('#thumbsGallery a')
	  .live('click', function(e) {
	      var newImg = $('<img src="'+$(this).attr("href")+'" />');
	      $("#mainImage img")
		.animate({opacity: 0}, 400, function(){ $(this).remove(); })
		.parent()
	        .append(newImg);
	      newImg.css({opacity: 0, display: 'inline'}).animate({opacity: 1}, 400);

	      e.preventDefault();
	      return false;
	  });

	// Gallery thumbnails loading process:
	images = $("#thumbsGallery a");
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
 

	var boxesToFadeIn = $(".uiBox:visible").fadeOut(0);
	if($("#mainImage").length)
	{
	  $("body").append("<img alt='' src='/images/spinner.gif' id='mainIndicator' style='position: absolute; left: 50%; top: 50%; margin:-16px 0 0 -16px;'/>");
	}

	// Load the page background
	img = new Image();
	$(img)
	  .load(function(){
		  $("#mainImage")
			  .attr("src", $(this).attr("src"))
			  .fadeOut(0)
			  .fadeIn();

		  $$.adjustBg();

		  // When it's loaded, show other boxes
		  fadeInBox(boxesToFadeIn, 0);

		  // Also, destroy the indicator
		  $("#mainIndicator").remove();
	  })
	  .attr("src", $("#mainImage img").attr("src"));

	  $("a.contact").colorbox({inline: true});
	  $('.newsletter [type=text]').click(function() {
	    if($(this).val().indexOf(' email') != -1) $(this).val('');   
	  });
});


