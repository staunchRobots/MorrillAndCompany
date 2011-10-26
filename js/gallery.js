/*
This gallery is under copyright. Do not use or reproduce without Permission.
(c) 2009 David Hellsing – monc.se
*/

(function($) {
  
var $$,tID;
 
$$ = $.fn.fsGallery = function(options) {
  var $options = {
    loadingText: 'Loading...',
    delay: 300,
    fade: 200,
    raster: true,
    onLoad : function(){}
  };

  $$.options = $.extend($options,options);
  if($('body').find('#fsImage').length < 1) {
    $('body').prepend('<div id="fsImage"><div><table><tr><td><img src="/css/images/ajax-loader.gif" class="init"></td></tr></table></div></div>');
  }
  return this.each(function() {
    $(this).addClass('fsGallery');  
    $$.preload($(this).find('li:first'));
  });
 
};
 
$$.next = function(element) {
  if (!element || element.length < 1 || element.siblings().length < 1) {
    return false;
  } else {
    clearTimeout(tID);
    element.siblings().find('a').css('display','none');
    $$.show(element.next().length > 0 ? element.next() : element.siblings(':first'));
  }
}
 
$$.prev = function(element) {
  if (!element || element.length < 1 || element.siblings().length < 1) {
    return false;
  } else {
    clearTimeout(tID);
    element.siblings().find('a').css('display','none');
    $$.show(element.prev().length > 0 ? element.prev() : element.siblings(':last'));
  }
}
 
$$.preload = function(element) {
  var anchor  = element.find('a');
  var image   = $(new Image());
  if (anchor.text().length > 0) {
    anchor.text(anchor.text().replace(/ /,'\xa0'));
  }
  element.addClass('loading');
  image.load(function() {
    element.removeClass('loading');
    element.css({backgroundImage:'url('+anchor.attr('alt')+')',backgroundRepeat:'no-repeat',backgroundPosition:'50% 50%'});
    
    element.addClass('loaded').click(
      function() {
        $$.show($(this));
        return false;
      }
    ).hover(
      function() {
        $(this).addClass('hover');
        $('.fsGallery li a').css('display','none');
        anchor.css({
          marginLeft: ((anchor.width()/2))*-1 +'px',
          marginTop: (anchor.height() + 20)*-1+'px'
        });
        //element.siblings().find('a').css('display','none');
        clearTimeout(tID);
        tID = setTimeout(function() {
          if ($$.options.fade > 1) {
            anchor.css({marginLeft: ((anchor.width()/2)+10)*-1 +'px'});
            anchor.fadeIn($$.options.fade,function() {
              $(this).css({display:'block'});
            });
          } else {
            if ($.browser.msie) {
              anchor.css({marginLeft: ((anchor.width()/2)+10)*-1 +'px'});
            }
            anchor.css('display','block');
          }
        },$$.options.delay);
      },
      function() {
        $(this).removeClass('hover');
        clearTimeout(tID);
        anchor.css('display','none');
      }
    );
    if ($('#fsImage td img:not(.init)').length < 1) { $$.show(element); }
    if (element.next().length > 0) { $$.preload(element.next()); }
    $(this).remove();
    
  }).attr('src',anchor.attr('href'));
};
 
$$.c = function(e) {
  return $(document.createElement(e));
};
 
$$.resize = function(image) {
  var ratio = Math.max($(window).width()/image.width(),$(window).height()/image.height());
  if ($(window).width() > $(window).height()) {
    image.css({width:image.width()*ratio,height:'auto'});
  } else {
    image.css({width:'auto',height:image.height()*ratio});
  }
};
 
$$.show = function(element) {
  element.siblings('.active').removeClass('active');
  element.addClass('active');
  var image = $(new Image).attr({
    src: element.find('a').attr('href'),
    alt: element.find('a').attr('title'),
    id:  element.find('a img').attr('id')
  });
  var left = $$.c('span').addClass('left').click(function() { $$.prev(element); });
  var right = $$.c('span').addClass('right').click(function() { $$.next(element); });
  var raster = $$.options.raster ? $$.c('span').addClass('raster') : false;
  $('#fsImage td').empty().append(image.css('display','block')).prepend(left).prepend(right).prepend(raster);
  if(($.browser.msie && $.browser.version < 7) || $.browser.safari) {
    $(window).resize(function(){ $$.resize($('#fsImage td img:not(.init)')); });
    $(function($){$$.resize(image);});
  }
  $$.options.onLoad();
};
 
$.fn.hoverClass = function() {
  return this.hover(
    function() { $(this).addClass('hover') },
    function() { $(this).removeClass('hover') }
  );
}
 
})(jQuery);
