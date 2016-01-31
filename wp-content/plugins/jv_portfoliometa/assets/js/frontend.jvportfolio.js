(function($){
    $.extend($.fn, {
        pfVote: function(o){
            o = $.extend({event: 'click.com_jvportfolio'}, o);
            return this.delegate('[data-pfvote]', o.event, function(e){
                var target = $(this);
                if(target.hasClass('process')) return false;
                $.ajax({
                    url: target.attr('href'),
                    type: 'POST',
                    dataType: 'json',
                    cache: false, 
                    beforeSend: function(){target.addClass('process')},
                    success: function(rs) {
                        if(!$.isNumeric(rs.data)) {
                            return false;
                        };
                        target.children().html(['&nbsp;',rs.data].join(''));
                    },
                    complete: function(){target.removeClass('process')}
                });
                return false; 
            });
        },
        pfQuickView: function(o){
            o = $.extend({event: 'click.com_jvportfolio'}, o);
            return this.delegate('[data-qview]', o.event, function(){
                var target = $(this),
                    d = target.data('qview'),
                    ci = new Number(!target.toggleClass('big').hasClass('big')),
                    view = $(d.view)
                ;
                $.each(d.c[ci], function(a, size){view[a](size);});
                o.pf.shuffle('layout');
                return false;
            });
        },
        asort: function(o){
            return this.each(function(){
                $(this).on({
                    'change.asort': function(){
                        var col = this.value,
                            sort = !col ? {} : {
                                by: function(el){
                                    return el.data(col)
                                }
                            }
                        ;
                        o.pf.shuffle('sort', sort);     
                    }
                }); 
            });
        }
    });
})(jQuery);