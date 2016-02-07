(function($){
    $.extend($.fn, {
        extendFilterScrf: function(o){
            return this.each(function(){
               var fs = $(this).find('[data-filter]');
               o.pf.data({fs: fs});
               
               fs.each(function(){
                   $(this).on({
                       'click.filter.pf': function(){
                           var t = $(this), filter = t.data('filter'); 
                            o.pf.shuffle('shuffle', filter);
							t.hasClass('current') || t.addClass('current');
                            fs.not(t).each(function(){
                                $(this).removeClass('current'); 
                            });
                            o.pf.data({cfilter: {fa: t}});
                            return false;
                       }
                   });
               });
            });
        }
    });
})(jQuery);