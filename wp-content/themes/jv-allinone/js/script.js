
(function($){
	
	//$(window).on("load",function(){
	//	$('body').addClass('loaded');
	//});
	$(document).ready(function(){
		
		$('ul.product-categories li.cat-parent').each(function(){
			 var 
				p = $(this),
				btn = $('<span>',{'class':'showsubmenu  icon-plus-square-o', text : '' }).click(function(){
					if(p.hasClass('parent-showsub')){
						menu.slideUp(300,function(){
							p.removeClass('parent-showsub');
						});        										
					}else{
						menu.slideDown(300,function(){
							p.addClass('parent-showsub');
						});        										
					};	
				}),
				menu = p.children('ul')
			 ;
			 p.prepend(btn)  // append : trong - duoi, prepend : trong - tren, after : duoi, before : tren
		});
		jQuery('ul.product-categories li.current-cat').parent().parent().addClass('parent-showsub');

		/////////// tooltip


 		$('[data-toggle="tooltip"]').tooltip()
		
		
/////////////////////////////  Search
		var searchBar = $('#searchtop');
		$('.btnsearchtop').click(function(){
			searchBar.slideToggle(300);
			return false;
		});			 
		$('#search-beack').click(function(){
			searchBar.slideUp(300);
			return false;
		});	
		

		//////////  Add Class

		$('.subcategories .product-category').each(function(){$(this).parent().parent().addClass('issubcategories');});
		$('.item-btn .compare-button').addClass('btn icon-params');
		$('.current-cat.cat-parent').addClass('parent-showsub');
		$('.content-item-description .addtocart, input.wpcf7-submit, .post-password-form input[type="submit"], #delete-group-avatar-button a').addClass('btn');
		$('input.button').addClass('btn').removeClass('button');
		$('.item-btn .yith-wcwl-add-to-wishlist a').each(function(){
			$(this).addClass('btn icon-heart-o').html('');	
		});
		$('#buddypress div.item-list-tabs ul li.feed a').addClass('icon-rss3');
		
		
		
		
		//////////  Move



	

		if ($('#jv-nav-right .jv-nav-right-content').length) {
			$('ul.singlemenu').appendTo("#jv-nav-right .jv-nav-right-content");
		};

		if ($('#block-breadcrumb .container').length) {
			$('#page-shop-sidebar ul.breadcrumb').prependTo("#block-breadcrumb .container");
		}		
		if ($('#block-breadcrumb .container').length) {
			$('#maincontent h1.entry-title').prependTo("#block-breadcrumb .container");
		}

		if ($('.woo-category').length) {
			$('.term-description').appendTo(".woo-category");
		}

		if ($('.shop-info-detail .shopImages-main').length) {
		//	$('.shop-info-detail .onsale').appendTo(".shop-info-detail .shopImages-main");
		}		
		
		
		
		

		// Remove text
		$(".prev.page-numbers").addClass(' icon-arrow-left10').html("");
		$(".next.page-numbers").addClass(' icon-arrow-right10').html("");

		
		// BTN Touch
		var $body= $('body');
		$('.btn-menu-mobile').click(function(){
			if($body.hasClass('show-menu-mobile')){
				$body.removeClass('show-menu-mobile');
			}else{
				$body.addClass('show-menu-mobile');
			}
		});	




		// parallax
	//	$('.sectionAboutNumbers, [class*="sectionCallout"]').parallax();
		
		// bpopup
		$('body').delegate('[data-rel=bpopup]','click',function(){
			$($(this).attr('href')).bPopup({closeClass:'bpopup-close'});
			return false;
		});
		
		
		
		/* ScrollToFixed menu */
		var body_id = $('body').attr('id');
		if(body_id !== "style-index-3" && body_id != "style-index-4" && body_id != "style-index-5"){ /*Home 5, 6, 7: header not sticky*/
			$('#header ').stick_in_parent( { sticky_class: 'scroll-to-fixed-fixed' } );
		}
		else if(body_id != "style-index-4"){
			$('#nav-mainmenu ').stick_in_parent( { sticky_class: 'scroll-to-fixed-fixed' } );
		}


		/* Cloud move */
		$(function move() {
			$(".cloud").animate( {"top": "+=30px"}, 2500, "linear",
			function() {
				$(".cloud").animate( {"top": "-=30px"}, 2500, "linear",
				function() {
					move();
				});
			});
		});
		
		/* and Cloud move */		
	
		$('.rzoom').zoom();
		$( 'form[data-product_id]' ).first().on( 'wc_variation_form', function( e, c ) {
			
			$( this ).on( 'found_variation', function( e, d ) {
				
				$( this ).closest( '.product' ).find( '.zoomImg' ).each( function() {
					
					$( this ).attr( 'src', d.image_link );
					
				} );
				
			} );
			
		} );
	
			
	});	
})(jQuery);	
(function(t){
	t.extend(t.fn, {
        syncOwl: function (e) {
            e = t.extend({
                main: {
                    items: 1
                },
                sub: {
                    items: 4,
                    afterInit: function (e) {
                        e.data({
                            items: e.find('.owl-item').each(function (e) {
                                t(this).data({
                                    owlItem: e
                                })
                            })
                        }).data('items').first().addClass('active')
                    }
                }
            }, e),
            e.main.afterAction = function (t) {
                var e = this.currentItem,
                a = t.data('sub');
				if( !a || !a.length ) { return; }
                a.data('items').removeClass('active').eq(e).addClass('active'),
                a.trigger('center', e)
            },
            e.sub.afterInit = function (e) {
                e.data({
                    items: e.find('.owl-item').each(function (e) {
                        t(this).data({
                            owlItem: e
                        })
                    })
                }).data('items').first().addClass('active')
            };
            var a = e.main.selector,
            i = e.sub.selector;
            return this.each(function () {
                var n = t(this),
                o = n.find(a),
                s = n.find(i);
                s.owlCarousel(e.sub).delegate('.owl-item', 'click', function (e) {
                    e.preventDefault(),
                    o.trigger('owl.goTo', t(this).data('owlItem'))
                }).on({
                    center: function (e, a) {
                        var i = t(this),
                        n = i.data('owlCarousel').owl.visibleItems,
                        o = t.inArray(a, n) > - 1 ? 1 : 0;
                        o ? a === n[n.length - 1] ? i.trigger('owl.goTo', n[1])  : a === n[0] && i.trigger('owl.goTo', a - 1)  : a > n[n.length - 1] ? i.trigger('owl.goTo', a - n.length + 2)  : (a - 1 === - 1 && (a = 0), i.trigger('owl.goTo', a))
                    }
                }),
                o.data({
                    sub: s
                }).owlCarousel(e.main)
            })
        }
    });
})(jQuery);
jQuery(function (t) {
    t('.inner-item').syncOwl({
        main: {
            selector: '.imgMainProduct',
			transitionStyle:'fade',
			singleItem : true,
            items: 1
        },
        sub: {
            selector: '.imgsubproduct',
            items: 4,
			itemsTablet: [768,4],
			itemsMobile: [479,4],
			navigation : true,
			navigationText : ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"]
        }
    })

    t('.shopImages').syncOwl({
        main: {
            selector: '.imgMainProduct',
			transitionStyle:'fade',
			singleItem : true,
            items: 1
        },
        sub: {
            selector: '.imgsubproduct',
            items: 5,
            navigation: false,
			itemsTablet: [768,4],
			itemsMobile: [479,4]
        }
    })
	
});




