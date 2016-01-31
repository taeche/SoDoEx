var jvThemeOptioins = (function($){
        
    var fnc = {
        initFonts : function (i){
            this.countFonts = i;
        },
        initOwl : function (i){
            this.countOwl = i;
        },
        
        addNewFont : function(){
            var self = this;
            this.countFonts = this.countFonts + 1;
            var html ='<div id="jFonts-'+ this.countFonts+ '" class="jFonts-item">' +
                    '<div class="jFonts-title">' +
                        '<div onclick="jvThemeOptioins.toggle(this)">Title</div>' +
                        '<div class="jFonts-title-control">' +
                            '<label for="enable"><input type="checkbox" checked="checked" class="jFonts-enable"><span>Enable</span></label>' + 
                            '<a class="ui-state-default ui-corner-all jFonts-button" href="javascript:void(0)" onclick="jvThemeOptioins.removeItem(this)" title="Remove"><span class="ui-icon ui-icon-close">Close</span></a>' +
                        '</div>' +
                    '</div>' +
                    '<div class="jFonts-inputs">' +
                        '<div class="jFonts-input">' +
                            '<label>Title</label>' +
                            '<input  type="text" class="jFonts-title" value="jFont - '+ this.countFonts +'"/>' +
                        '</div>' +
                        '<div class="clr"></div>' +
                        '<div class="jFonts-input">' +
                            '<label>Font family</label>' +
                            '<input  type="text" class="jFonts-family" value=""/><input  type="hidden" class="jFonts-name" value=""/>' +
                        '</div>' +
                        '<div class="clr"></div>' +
                        '<div class="jFonts-input">' +
                            '<label>Font weight</label>' +
                            '<input data="" type="text" class="jFonts-weight" value=""/>' +
                        '</div>' +
                        '<div class="clr"></div>' +
                        '<div class="jFonts-input">' +
                            '<label>Assign to selector</label>' +
                            '<input type="text"  value="" class="jFonts-element"/>' +
                        '</div>' +
                        '<div class="clr"></div>' +
                    '</div>' +
                '</div>';
            var content = $('#jvGoogleFonts .jContent');
            content.append(html);
            self.jvSelect2(content.find('#jFonts-'+ this.countFonts));
            
            
            //self.jvSelect2(jQuery(html))
        },
        toggle: function(el){
            $(el).parent().next().slideToggle(200);
        },
        jFontsSetValue: function(){
            var data = new Array();
            $.each($('#jvGoogleFonts .jFonts-item'), function(i, item){
                data[i] = {};
                data[i].enable = $(item).find('input.jFonts-enable').is(':checked');
                data[i].element = $(item).find('input.jFonts-element').val();
                data[i].title = $(item).find('input.jFonts-title').val();
                data[i].family = $(item).find('input.jFonts-family').val();
                data[i].name = $(item).find('input.jFonts-name').val();
                data[i].weight = $(item).find('input.jFonts-weight').val();
            });
            $('#googlefonts').val(JSON.stringify(data));
        },
        removeItem: function(el){
            $(el).parent().parent().parent().stop(true,true).fadeOut('200', function(){$(this).remove()});
        },
        div : function (cls, id){
            var cls  = cls || '', id = id || '';
            return $('<div>', {'id':id,'class':cls} );
        },
        
        span : function(cls, id){
            var cls  = cls || '', id = id || '';
            return $('<span>', {'id':id,'class':cls} );
        },
        
        addAnimate : function (i,data){
            var 
                jdefault = {
                    'selector' : '',
                    'duration' : 1000,
                    'iteration' : 1,
                    'groupDelay' : 0,
                    'delay' : 0,
                    'effect' : '',
                    'groupPoint' : '',
                    'offset' : 100,
                    'mobile' : false,
                    'groupDesc' : false,
                    'enable' : true
                },
                data = $.extend({}, jdefault, data),
                html = '',
                self = this
            ;
            self.aniCount = self.aniCount || 0;
            self.aniCount = self.aniCount + i;
            html = self.div('animate-item', 'animate-'+self.aniCount).append(
                self.div('ani-control').append(
                    self.div('ani-handle').append('<span class="ui-icon ui-icon-arrow-4"></span>'),
                    self.div('ani-title-control').append( self.getCheckBox('ani-enable',data.enable ) , ' <span>Enable</span><a class="ui-state-default" href="javascript:void(0)" onclick="jvThemeOptioins.removeItem(this)"><span class="ui-icon ui-icon-close">x</span></a>')
                ), 
                self.div('ani-input').append(
                    self.div('ani-head').append('<label>Selector</label>','<input value="'+data.selector+'" type="text" class="ani-selector"/>'),
                    self.div('ani-col1').append(
                        self.div('ani-input').append('<label>Duration</label>','<input  value="'+data.duration+'" type="text" class="ani-duration"/>'),
                        self.div('ani-input').append('<label>Iteration</label>','<input  value="'+data.iteration+'" type="text" class="ani-iteration"/>'),
                        self.div('ani-input').append('<label>Group delay</label>','<input  value="'+data.groupDelay+'" type="text" class="ani-groupDelay"/>')
                    ),
                    self.div('ani-col2').append(
                        self.div('ani-input').append('<label>Delay</label>','<input  value="'+data.delay+'" type="text" class="ani-delay"/>'),
                        self.div('ani-input').append('<label>Effect</label>',self.getEffect(data.effect) ),
                        self.div('ani-input').append('<label>Group point</label>','<input  value="'+data.groupPoint+'" type="text" class="ani-groupPoint"/>')
                    ),
                    self.div('ani-col3').append(
                        self.div('ani-input').append('<label>Offset</label>','<input  value="'+data.offset+'" type="text" class="ani-offset"/>'),
                        self.div('ani-input').append('<label>Mobile</label>',self.getCheckBox('ani-mobile', data.mobile )),
                        self.div('ani-input').append('<label>Direction DESC</label>',self.getCheckBox('ani-groupDesc', data.groupDesc ))
                    )
                )
            );
            
            html.appendTo('#jvAnimate-list').find( '.input-select2').each( function() {
				
				$( this ).trigger( 'init-select2' );
				
			});
        },
        
        AniSetValue: function(){
            var data = new Array();
            $.each($('#jvAnimate-list .animate-item'), function(i, item){
                data[i] = {};
                data[i].enable = $(item).find('.ani-enable').is(':checked');
                data[i].selector = $(item).find('.ani-selector').val();
                data[i].duration = $(item).find('.ani-duration').val();
                data[i].iteration = $(item).find('.ani-iteration').val();
                data[i].groupDelay = $(item).find('.ani-groupDelay').val();
                data[i].delay = $(item).find('.ani-delay').val();
                data[i].effect = $(item).find('[type="text"].ani-effect').val();
                data[i].groupPoint = $(item).find('.ani-groupPoint').val();
                data[i].offset = $(item).find('.ani-offset').val();
                data[i].mobile = $(item).find('.ani-mobile').is(':checked');
                data[i].groupDesc = $(item).find('.ani-groupDesc').is(':checked');
            });
            $('#textAnimate').val(JSON.stringify(data));
        },
        getCheckBox : function(name, val){
            if(val == true)  var box = $('<input>', {'type': 'checkbox', 'class': name, 'checked' : 'checked' });
            else var box = $('<input>', {'type': 'checkbox', 'class': name });
            return box.appendTo(this.span());
        },
        
        getEffect : function(val){
            var items = {
                    "Custom Effect":{
                        "counting": "Count number",
                        "clipcircle": "Clip circle"
                    },
                    "attention seekers": {
                        "bounce": "bounce",
                        "flash": "flash",
                        "pulse": "pulse",
                        "rubberBand": "rubberBand",
                        "shake": "shake",
                        "swing": "swing",
                        "tada": "tada",
                        "wobble": "wobble"
                    },
                    "bouncing entrances": {
                        "bounceIn": "bounceIn",
                        "bounceInDown": "bounceInDown",
                        "bounceInLeft": "bounceInLeft",
                        "bounceInRight": "bounceInRight",
                        "bounceInUp": "bounceInUp"
                    },
                    "bouncing exits": {
                        "bounceOut": "bounceOut",
                        "bounceOutDown": "bounceOutDown",
                        "bounceOutLeft": "bounceOutLeft",
                        "bounceOutRight": "bounceOutRight",
                        "bounceOutUp": "bounceOutUp"
                    },
                    "fading entrances": {
                        "fadeIn": "fadeIn",
                        "fadeInDown": "fadeInDown",
                        "fadeInDownBig": "fadeInDownBig",
                        "fadeInLeft": "fadeInLeft",
                        "fadeInLeftBig": "fadeInLeftBig",
                        "fadeInRight": "fadeInRight",
                        "fadeInRightBig": "fadeInRightBig",
                        "fadeInUp": "fadeInUp",
                        "fadeInUpBig": "fadeInUpBig"
                    },
                    "fading exits": {
                        "fadeOut": "fadeOut",
                        "fadeOutDown": "fadeOutDown",
                        "fadeOutDownBig": "fadeOutDownBig",
                        "fadeOutLeft": "fadeOutLeft",
                        "fadeOutLeftBig": "fadeOutLeftBig",
                        "fadeOutRight": "fadeOutRight",
                        "fadeOutRightBig": "fadeOutRightBig",
                        "fadeOutUp": "fadeOutUp",
                        "fadeOutUpBig": "fadeOutUpBig"
                    },
                    "flippers": {
                        "flip": "flip",
                        "flipInX": "flipInX",
                        "flipInY": "flipInY",
                        "flipOutX": "flipOutX",
                        "flipOutY": "flipOutY"
                    },
                    "lightspeed": {
                        "lightSpeedIn": "lightSpeedIn",
                        "lightSpeedOut": "lightSpeedOut"
                    },
                    "rotating entrances": {
                        "rotateIn": "rotateIn",
                        "rotateInDownLeft": "rotateInDownLeft",
                        "rotateInDownRight": "rotateInDownRight",
                        "rotateInUpLeft": "rotateInUpLeft",
                        "rotateInUpRight": "rotateInUpRight"
                    },
                    "rotating exits": {
                        "rotateOut": "rotateOut",
                        "rotateOutDownLeft": "rotateOutDownLeft",
                        "rotateOutDownRight": "rotateOutDownRight",
                        "rotateOutUpLeft": "rotateOutUpLeft",
                        "rotateOutUpRight": "rotateOutUpRight"
                    },
                    "specials": {
                        "hinge": "hinge",
                        "rollIn": "rollIn",
                        "rollOut": "rollOut"
                    },
                    "zooming entrances": {
                        "zoomIn": "zoomIn",
                        "zoomInDown": "zoomInDown",
                        "zoomInLeft": "zoomInLeft",
                        "zoomInRight": "zoomInRight",
                        "zoomInUp": "zoomInUp"
                    },
                    "zooming exits": {
                        "zoomOut": "zoomOut",
                        "zoomOutDown": "zoomOutDown",
                        "zoomOutLeft": "zoomOutLeft",
                        "zoomOutRight": "zoomOutRight",
                        "zoomOutUp": "zoomOutUp"
                    }
            },
            option = '',
            select = '';
            /*$.each(items, function(key, item){
                option += '<optgroup label="'+key+'">';
                    $.each(item, function(i, k){
                        if(k == val) option += '<option selected="selected" value="'+k+'">'+k+'</option>';
                        else option += '<option value="'+k+'">'+k+'</option>';
                    })
                option += '</optgroup>';
            });
            
            select = $('<select/>',{'class' : 'ani-effect'}).append(option);*/
            select = $('<input/>',{type: 'text', 'class' : 'input-select2 ani-effect', value: val});
			select.one( 'init-select2', function(){
				var i = $( this ).select2({
                    width: '50%',
					item: items,
					query: function (query) {
						var data = {results: []},$=jQuery;
						if(query.term) data.results.push({id: query.term, text: query.term});
						$.each(this.item,function(k,v){
							var children = [];
							$.each(v,function(k,v){
								if(!query.term || k.toLowerCase().indexOf(query.term.toLowerCase()) > -1)
									children.push({id: k, text: v});
							});
							if(children.length) data.results.push({text: k, children:children});
						});
						query.callback(data);
					}
				} ),
				iv = i.val()
				;
				i.select2( 'data', { id: iv, text: iv } );
			} );
            return select.appendTo(this.div());
        },
        
        addNewOwl: function(){
            this.countOwl = this.countOwl + 1;
            $('#OWL-list #OWL-container')
            .prepend(
                '<div id="OWL-'+ this.countOwl+ '" class="OWL-item">' +
                    '<div class="OWL-title">' +
                        '<div class="OWL-handle"><span class="ui-icon ui-icon-arrow-4"></span></div>' +
                        '<div onclick="OWL.toggle(this)">Title slider - ' + this.countOwl + '</div>' +
                        '<div class="OWL-title-control">' +
                            '<label for="enable"><input type="checkbox" checked="checked" class="OWL-enable"><span>Enable</span></label>' +
                            '<a class="ui-state-default ui-corner-all OWL-button" href="javascript:void(0)" onclick="jvThemeOptioins.removeItem(this)" title="Remove"><span class="ui-icon ui-icon-close">Close</span></a>' +
                        '</div>' +
                    '</div>' +
                    '<div>' +
                        '<div class="OWL-inputs">' +
                            '<div class="OWL-maininfo">' +
                                '<div class="OWL-input">' +
                                    '<label><span class="spanlabel">Title</span><input  type="text" class="OWL-title" value="jvSlider - '+ this.countOwl +'"/></label>' +
                                '</div>' +				
                                '<div class="clr"></div>' +
                                '<div class="OWL-input">' +
                                    '<label><span class="spanlabel">Element (.class || #Id)</span><input type="text"  value="" class="OWL-element"/></label>' +
                                '</div>' +
								
                                '<div class="clr"></div>' +
                                '<div class="OWL-input">' +
                                    '<label><span class="spanlabel">Params</span><textarea class="OWL-params"></textarea></label>' +
                                '</div>' +			   
                                '<div class="clr"></div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>' 
            );
        },
        OWLSetValue: function(){
            var data = new Array();
            $.each($('#OWL-container .OWL-item'), function(i, item){
                data[i] = {};
                data[i].enable = $(item).find('.OWL-enable').is(':checked');
                data[i].element = $(item).find('input.OWL-element').val();
                data[i].title = $(item).find('input.OWL-title').val();
                data[i].params = $(item).find('textarea.OWL-params').val();
                
            });
            $('#jsOwl').val(JSON.stringify(data));
        },
        
        logo : function (el){
          if(el.val() == 'image'){
              jQuery('.logoText').hide();
              jQuery('.logoImage').fadeIn();
          } else {
              jQuery('.logoText').fadeIn();
              jQuery('.logoImage').hide();

		  } 
        },

        btn_show_hide : function ( t ){
          return this.each( function() {
			  
			$( this ).on( 'change', function() {
					var e = $( this );
					if(e.val() == 'yes'){
						  t.show();
					  } else {
						  t.hide();
					  }
				} ).trigger( 'change' )  ;
		  } ); 
        },
        
        upload : function(url){
            
			var t = jQuery.noop();
             $('#upload_logo_button, #upload_logom_button, #upload_image_header_button, #upload_favicon_button, #upload_bannerads_button, #upload_bannerads2_button').click(function() {
                tb_show('Upload a logo', 'media-upload.php?referer=wptuts-settings&type=image&TB_iframe=true&post_id=0', false);
				t = $( this );
                return false;
            });
            window.send_to_editor = function(html) {
                var image_url = $('img',html).attr('src');
                $( t.attr( 'data-target' ) ).val(image_url.replace(url,''));
                tb_remove();
            }
        },
        
        jvSelect2 : function(item){
            var 
                family = $(item).find('.jFonts-family'),
                weight  = $(item).find(".jFonts-weight"),
                element = $(item).find(".jFonts-element"),
                name = $(item).find(".jFonts-name")
            ;

            window.jFdefault = {'text':name.val()};
            window.jEdefault = [];
            window.jWdefault = [];
            var w = weight.attr('data') || [];
            if(w.length > 0){ 
                var tt = w.split(',');
                for (var i=0; i < tt.length; i++){
                    window.jWdefault.push({
                        id: tt[i],
                        text : tt[i]
                    });
                }
            }

           family.select2({
                width: '80%',
                minimumInputLength: 1,
                formatResult: function(state){
                        if (!state.id) return state.text
                        var 
                                line = $('<div>').css('font-family',state.id),
                                label = $('<span>').addClass('icon').append(state.text)
                        ;
                        return line.append(label);
                },
                query: function(ops){
                        var 
                                self = arguments.callee,
                                This = this,
                                gFont = window.gFontSearch =  window.gFontSearch || {}
                        ;
                        if(gFont._gfontInit) return;
                        if(!gFont._gfontData){
                                gFont._gfontInit = true;
                                $.getJSON('https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyCWonrDW2amTWX30frvUf2Aq6Vl9w-E49I&callback=?').done(function( data ) {
                                                gFont._gfontData = data;
                                                delete gFont._gfontInit;
                                                self.call(This,ops);
                                  });
                                return;
                        }
                        var data = [],i=0;
                        $.each(gFont._gfontData.items,function(){
                                if(ops.matcher(ops.term,this.family)){
                                        data.push({
                                                id: this.family,
                                                text: this.family,
                                                data: this
                                        });
                                        i++;
                                        if(i>20) return false;
                                }
                        });
                        ops.callback({results: data});
                    }
            }).bind('select2-selecting',function(e){
                window.jWdefault = [];
                $.each(e.object.data.variants,function(i){
                        window.jWdefault.push({
                            id: this,
                            text:this
                        });
                });
                name.val(e.val);
                weight.select2('data',window.jWdefault);
            }).select2('data',window.jFdefault);
            // select weight
            weight.select2({
                multiple: true,
                query : function(ops){
                    var data = [];
                    $.each(window.jWdefault,function(i){
                            if(!ops.term || ops.matcher(ops.term,this)) data.push(this);
                    });
                    ops.callback({results: data});
                }
            }).select2('data',window.jWdefault);
            // element
            element.select2({
                tags: window.jEdefault
            });
        },
        InstallDataSample : function(){
            
            $('.jLoading').show();
            
            function i(){
                $.ajax({
                    url : ajaxurl,
                    type: 'post',
                    data : 'action=InstallDataSanple',
                    dataType : 'json'
                }).done(function(data){
                    
                    document.title = "Progress 100%";
                    
                    $('.jLoading').hide();
                    var msg = '', result = $('.installMsg');
                    if(data.msg) msg = data.msg;
                    else msg = 'Install successfull';
                    result.html(msg).fadeIn();
                    $('.jvInstallData').attr('disabled','disabled');
                    setTimeout(function(){  
                        result.fadeOut(); 
                        var href = window.location.href + '&permalinks=true';
                        window.location.href = href;
                    }, 1000);
                    
                    
                });
            }
            
            function supload(){
                
                $.ajax({
                    url : ajaxurl,
                    type: 'post',
                    data : 'action=InstallDataSanple&task=downloadSampleData&file=upload',
                    beforeSend: function(){
                        document.title = "Progress 0%";
                    },
                    success: function(){
                        
                        document.title = "Progress 50%";
                        i();    
                    }
                });    
            }
            
            !$( '[name="simport"]:checked' ).val() ? supload() : i();
        } 
    }
    return fnc;
})(jQuery);
    