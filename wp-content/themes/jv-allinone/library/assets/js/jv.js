
var JVTop = (function($){
    var fnc = {
        init: function(){
            
            var topElement = $('<div>', {'id': 'toTop','class': 'icon-arrow-up82', 'html': ''}).appendTo(document.body),
                mark = $('body, html')
            ;
            topElement.click(function(){
                mark.animate({scrollTop: 0}, 'slow');
            });

             $(window).scroll(function () {
                var top = $(this).scrollTop();
                    if(top > 2){
                        topElement.css({'opacity': 1});
                    }else {
                        topElement.css({'opacity': 0});
                    }
            }).scroll();
        }
    };
    return fnc;
})(jQuery);