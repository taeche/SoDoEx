jQuery(function($){
    
    function toNum() {
        return $.isNumeric( this ) ? parseInt( this ) : 0;
    }
    var 
        citems = '#frm-portfolio .box-portfolio .pfo-item',
        pf = $('#frm-portfolio .box-portfolio'),
        ppf = pf.closest('#frm-portfolio'),
        msg = ppf.find('.pf-load')
    ;
    
    $.each( [ 'limit', 'pageTotal'], function(){
        
        if( this in JV ) {
            JV[ this ] = toNum.call( JV[ this ] );
        }
    } );
    pf.infinitescroll({
        itemSelector : citems,
        loading: {msg: msg},
        maxPage: JV.pageTotal,
        path: function(p){
            var 
            limit = JV.limit,
            url = JV.link
            ; 
            return url.replace(/page=\d+/, ['page', p].join('=') ).replace(/page\/\d+/, ['page', p].join('/') );   
        }
    }, function(items){                   
        $(items).imagesLoaded(function(){
            pf.shuffle('appended', $(items)); 
        });
    });
    if(window.JV.cfilter) {
        ppf.extendFilterScrf({pf : pf});
    }
    if(window.JV.mfetch == 'button'){
        pf.extendScrfBtn({
            doc: $(document), 
            mark: ppf.find('.load-more'),
            maxPage: JV.pageTotal
        });
    }
    if(window.JV.mfetch == 'nav'){
        pf.extendScrfNav({    
            mark: ppf.find('[data-nav]'),
            pagination: {
                pages: JV.pageTotal,
                itemsOnPage: JV.limit
            }
        });
    }
    !window.JV.csort || ppf.find('#csort').asort({pf: pf});
    pf.imagesLoaded(function(){
        pf.shuffle({itemSelector: citems });
        msg.hide(0);
    });
    $(document)
    .pfQuickView({pf: pf})
    .pfVote()
    ;
});